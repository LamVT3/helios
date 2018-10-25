<?php

namespace App\Http\Controllers;

use App\Ad;
use App\AdResult;
use App\Campaign;
use App\Channel;
use App\Contact;
use App\LandingPage;
use App\Subcampaign;
use App\Team;
use App\User;
use App\Config;
use DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Source;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Whoops\Util\TemplateHelper;
use App\LogExportToSale;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title     = "Contacts | Helios";
        // HoaTV multiple select
        // $page_css       = array();
        $page_css       = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active         = 'contacts';
        $breadcrumbs    = "<i class=\"fa-fw fa fa-child\"></i> Contacts <span>> C3</span>";

        $page_size      = Config::getByKey('PAGE_SIZE');
        $sources        = Source::orderBy('name')->get();
        $teams          = Team::orderBy('name')->get();
        $marketers      = User::where('is_active', 1)->orderBy('username')->get();
        $campaigns      = Campaign::orderBy('name')->get();
        $subcampaigns   = Subcampaign::orderBy('name')->get();
        $export_to_excel    = $this->countExportToExcel();
        $export_to_olm  = $this->countExportToOLM();
        $landing_page   = LandingPage::where('is_active', 1)->orderBy('name')->get();
        $channel        = Channel::where('is_active', 1)->orderBy('name')->get();

        return view('pages.contacts-c3', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'sources',
            'teams',
            'marketers',
            'campaigns',
            'page_size',
            'subcampaigns',
            'export_to_excel',
            'export_to_olm',
            'landing_page',
            'channel'
        ));
    }

    public function getC3Data()
    {
        $request     = request();
        $status      = \request('is_export');
        $columns     = $this->setColumns();
        $data_where  = $this->getWhereData();
        $data_search = $this->getSeachData();
        $order       = $this->getOrderData();
        $startDate   = strtotime("midnight")*1000;
        $endDate     = strtotime("tomorrow")*1000;

        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr   = explode(' ', str_replace('/', '-', $date_place));
            $startDate  = strtotime($date_arr[0])*1000;
            $endDate    = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }

        $query = Contact::where('submit_time', '>=', $startDate);
        $query->where('submit_time', '<', $endDate);

        // HoaTV fix multiple select channel
        $arrChannelName = array();
        if ($request->channel) {
            $arrChannelName    = explode(',',$request->channel);
            $query->whereIn('channel_name',$arrChannelName);
        }

        if(@$data_where['clevel'] == 'c3b'){
            $query->where('clevel', 'like', '%c3b%');
            unset($data_where['clevel']);
        }elseif (@$data_where['clevel'] == 'c3b_only'){
            $query->where('clevel', 'c3b');
            unset($data_where['clevel']);
        }
        if(@$data_where['current_level'] == 'l0'){
            $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
            unset($data_where['current_level']);
        }
        if(@$data_where['olm_status'] === 0){
            $query->whereIn('olm_status', [0, '0']);
            unset($data_where['olm_status']);
        }else if(@$data_where['olm_status'] === 1){
            $query->whereIn('olm_status', [1, '1']);
            unset($data_where['olm_status']);
        }else if(@$data_where['olm_status'] == -1){
            $query->whereNotIn('olm_status', [0, 1, 2, 3, '0', '1', '2', '3']);
            unset($data_where['olm_status']);
        }
        $query->where($data_where);

        if($status == '1'){
            $query->where('is_export', 1);
        }
        if($status == '0'){
            $query->where('is_export', '<>', 1);
        }

        if($request->c3bg_checkbox == "true") {
            if($request->checked_date){
                $date_place = str_replace('-', ' ', $request->checked_date);
                $date_arr   = explode(' ', str_replace('/', '-', $date_place));
                $startDate  = strtotime($date_arr[0])*1000;
                $endDate    = strtotime("+1 day", strtotime($date_arr[1]))*1000;
            }
            $queryC3bg = Contact::where('submit_time', '>=', $startDate);
            $queryC3bg->where('submit_time', '<', $endDate);
            $checkedContacts = $queryC3bg->get();
            $phoneArr = array();
            foreach($checkedContacts as $contact) {
                array_push($phoneArr, $contact['phone']);
            }
            $query->whereNotIn('phone', $phoneArr);
        }

        if($data_search != ''){
            foreach ($columns as $key => $value){
                $query->orWhere($value, 'like', "%{$data_search}%");
            }
        }
        if($order){
            $query->orderBy($columns[$order['column']], $order['type']);
        } else {
            $query->orderBy('submit_time', 'desc');
        }
        $total    = $query->count();
        $limit    = intval($request->length);
        $offset   = intval($request->start);
        $contacts = $query->skip($offset)->take($limit)->get();

        $data['contacts']   = $this->formatRecord($contacts);
        $data['total']      = $total;

        return $data;
    }

    public function countExported()
    {
        $toExcel    = $this->countExportToExcel();
        $toOLM      = $this->countExportToOLM();
        $count['to_excel']  = $toExcel;
        $count['to_olm']    = $toOLM;

        return $count;
    }

    private function countExportToOLM()
    {
        $columns     = $this->setColumns();
        $data_where  = $this->getWhereData();
        $data_search = $this->getSeachData();
        $request = request();

        $startDate = strtotime("midnight")*1000;
        $endDate = strtotime("tomorrow")*1000;
        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = strtotime($date_arr[0])*1000;
            // $endDate = Date('Y-m-d 23:59:59', strtotime($date_arr[1]));
            $endDate = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }

        $query = Contact::where('submit_time', '>=', $startDate);
        $query->where('submit_time', '<', $endDate);

        // HoaTV fix multiple select channel
        $arrChannelName = array();
        if ($request->channel) {
            $arrChannelName    = explode(',',$request->channel);
            $query->whereIn('channel_name',$arrChannelName);
        }

        if(@$data_where['olm_status'] === 0){
            $query->whereIn('olm_status', [0, '0']);
            unset($data_where['olm_status']);
        }else if(@$data_where['olm_status'] === 1){
            $query->whereIn('olm_status', [1, '1']);
            unset($data_where['olm_status']);
        }else if(@$data_where['olm_status'] == -1){
            return 0;
            unset($data_where['olm_status']);
        }else{
            $query->whereIn('olm_status', [0, 1, 2, 3, '0', '1', '2', '3']);
            unset($data_where['olm_status']);
        }

        $status = @$request->is_export;
        if($status == '1'){
            $query->where('is_export', 1);
        }
        if($status == '0'){
            $query->where('is_export', '<>', 1);
        }

        if(@$data_where['clevel'] == 'c3bg'){
            $query->where('clevel','c3bg');
            unset($data_where['clevel']);
        }else if (@$data_where['clevel'] == 'c3b' || @$data_where['clevel'] == 'c3a' || @$data_where['clevel'] == 'c3b_only'){
            $query->where('clevel','c3bg');
            unset($data_where['clevel']);
        }
        if(@$data_where['current_level'] == 'l0'){
            $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
            unset($data_where['current_level']);
        }
        $query->where($data_where);

        if($data_search != ''){
            foreach ($columns as $key => $value){
                $query->orWhere($value, 'like', "%{$data_search}%");
            }
        }

        $count = $query->count();

        return $count;
    }

    private function countExportToExcel()
    {
        $columns     = $this->setColumns();
        $data_where  = $this->getWhereData();
        $data_search = $this->getSeachData();
        $request = request();

        $startDate = strtotime("midnight")*1000;
        $endDate = strtotime("tomorrow")*1000;
        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = strtotime($date_arr[0])*1000;
            // $endDate = Date('Y-m-d 23:59:59', strtotime($date_arr[1]));
            $endDate = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }
        $data_where['is_export'] = 1;

        $query = Contact::where('submit_time', '>=', $startDate);
        $query->where('submit_time', '<', $endDate);

        // HoaTV fix multiple select channel
        $arrChannelName = array();
        if ($request->channel) {
            $arrChannelName    = explode(',',$request->channel);
            $query->whereIn('channel_name',$arrChannelName);
        }

        if(@$data_where['clevel'] == 'c3b'){
            $query->where('clevel', 'like', '%c3b%');
            unset($data_where['clevel']);
        }elseif (@$data_where['clevel'] == 'c3b_only'){
            $query->where('clevel', 'c3b');
            unset($data_where['clevel']);
        }
        if(@$data_where['current_level'] == 'l0'){
            $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
            unset($data_where['current_level']);
        }
        if(@$data_where['olm_status'] === 0){
            $query->whereIn('olm_status', [0, '0']);
            unset($data_where['olm_status']);
        }else if(@$data_where['olm_status'] === 1){
            $query->whereIn('olm_status', [1, '1']);
            unset($data_where['olm_status']);
        }else if(@$data_where['olm_status'] == -1){
            $query->whereNotIn('olm_status', [0, 1, 2, 3, '0', '1', '2', '3']);
            unset($data_where['olm_status']);
        }
        $query->where($data_where);

        if($data_search != ''){
            foreach ($columns as $key => $value){
                $query->orWhere($value, 'like', "%{$data_search}%");
            }
        }

        $count = $query->count();

        return $count;
    }

    public function export()
    {
        ini_set('memory_limit', '512M');

        $date = \request('registered_date');
        $date = str_replace('/','',$date);
        $file_name = 'Contact_C3_' . $date;

        Excel::create($file_name, function ($excel) {
//            header('Content-Encoding: UTF-8');
//            header('Content-type: application/ms-excel; charset=UTF-8');
            $excel->sheet('contacts_c3', function ($sheet) {

                $request = request();
                $columns     = $this->setColumns();
                $data_where  = $this->getWhereData();
                $data_search = $this->getSeachData();
                $order       = $this->getOrderData();

                $startDate = strtotime("midnight")*1000;
                $endDate = strtotime("tomorrow")*1000;
                if($request->registered_date){
                    $date_place = str_replace('-', ' ', $request->registered_date);
                    $date_arr = explode(' ', str_replace('/', '-', $date_place));
                    $startDate = strtotime($date_arr[0])*1000;
                    // $endDate = Date('Y-m-d 23:59:59', strtotime($date_arr[1]));
                    $endDate = strtotime("+1 day", strtotime($date_arr[1]))*1000;
                }
                $query = Contact::where('submit_time', '>=', $startDate);
                $query->where('submit_time', '<', $endDate);

                // HoaTV fix multiple select channel
                $arrChannelName = array();
                if ($request->channel) {
                    $arrChannelName    = explode(',',$request->channel);
                    $query->whereIn('channel_name',$arrChannelName);
                }

                if(@$data_where['clevel'] == 'c3b'){
                    $query->where('clevel', 'like', '%c3b%');
                    unset($data_where['clevel']);
                }elseif (@$data_where['clevel'] == 'c3b_only'){
                    $query->where('clevel', 'c3b');
                    unset($data_where['clevel']);
                }
                if(@$data_where['current_level'] == 'l0'){
                    $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
                    unset($data_where['current_level']);
                }
                if(@$data_where['olm_status'] === 0){
                    $query->whereIn('olm_status', [0, '0']);
                    unset($data_where['olm_status']);
                }else if(@$data_where['olm_status'] === 1){
                    $query->whereIn('olm_status', [1, '1']);
                    unset($data_where['olm_status']);
                }else if(@$data_where['olm_status'] == -1){
                    $query->whereNotIn('olm_status', [0, 1, 2, 3, '0', '1', '2', '3']);
                    unset($data_where['olm_status']);
                }
                $query->where($data_where);

                if($data_search != ''){
                    foreach ($columns as $key => $value){
                        $query->orWhere($value, 'like', "%{$data_search}%");
                    }
                }

                if($request->contact_id){
                    $id = explode(',', $request->contact_id);
                    $query->whereIn('_id', $id);
                }

                $query->where('is_export', '<>', 1);

                if($order){
                    $query->orderBy($columns[$order['column']], $order['type']);
                } else {
                    $query->orderBy('submit_time', 'desc');
                }

                $limit = 5000;
                $updateCnt = 0;
                if(!$request->contact_id && $request->limit){
                    $limit = \request('limit');
                }

//                $query->limit($limit);
                $query->chunk( 5000, function ( $contacts ) use ( &$updateCnt, &$limit, &$sheet ) {
                    $count = 1;
                    foreach ( $contacts as $item ) {
                        if($count > $limit){
                            break;
                        }

                        $row = array(
                            $count,
                            $item->name,
                            $item->email,
                            $item->phone,
                            Date('Y-m-d H:i:s', (int)$item->submit_time/1000),
                            $item->landing_page,
                            $item->channel_name,
                            $item->contact_id,
                            $item->age,
                            $item->current_level,
                            $item->marketer_name,
                            $item->campaign_name,
                            $item->subcampaign_name,
                            $item->ad_name,
                            $item->ads_link,
                            $item->clevel
                        );
                        $sheet->appendRow($row);
                        $count++;
                        $updateCnt++;

                    }
                    return false;
                });

                $headings = \config('constants.TEMPLATE_EXPORT');
                $sheet->prependRow(1, $headings);
                $sheet->cells('A1:P1', function ($cells) {
                    $cells->setBackground('#191919');
                    $cells->setFontColor('#DBAC69');
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                });

                $is_update = \request('mark_exported');
                if($is_update){
                    $this->updateStatusExport($updateCnt);
                }
            });

        })->export('xlsx');

//        } else {
//            return back();
//        }
    }

    private function getWhereData(){
        
        $request    = request();
        $data_where = array();
        if ($request->source_id) {
            $data_where['source_id']        = $request->source_id;
        }
        if ($request->team_id) {
            $data_where['team_id']          = $request->team_id;
        }
        if ($request->marketer_id) {
            $data_where['marketer_id']      = $request->marketer_id;
        }
        if ($request->campaign_id) {
            $data_where['campaign_id']      = $request->campaign_id;
        }
        if ($request->subcampaign_id) {
            $data_where['subcampaign_id']   = $request->subcampaign_id;
        }
        if ($request->current_level) {
            $data_where['current_level']    = $request->current_level;
        }
        if ($request->clevel) {
            $data_where['clevel']           = $request->clevel;
        }
        if ($request->landing_page) {
            $data_where['landing_page']     = $request->landing_page;
        }
        // HoaTV remove for multiple select 
        // if ($request->channel) {
        //     $data_where['channel_name']     = $request->channel;
        // }
        // if ($request->channel) {
        //     $data_where['channel_name']     = $request->channel;
        // }
        if (isset($request->olm_status)) {
            $data_where['olm_status']       = (int)$request->olm_status;
        }

        return $data_where;
    }

    private function updateStatusExport($limit){
        $data_where = $this->getWhereData();

        $request = request();

        // DB::connection( 'mongodb' )->enableQueryLog();

        $startDate = strtotime("midnight")*1000;
        $endDate = strtotime("tomorrow")*1000;
        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = strtotime($date_arr[0])*1000;
            // $endDate = Date('Y-m-d 23:59:59', strtotime($date_arr[1]));
            $endDate = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }
        $query = Contact::where('submit_time', '>=', $startDate);
        $query->where('submit_time', '<', $endDate);

        // HoaTV fix multiple select channel
        $arrChannelName = array();
        if ($request->channel) {
            $arrChannelName    = explode(',',$request->channel);
            $query->whereIn('channel_name',$arrChannelName);
        }

        if(@$data_where['clevel'] == 'c3b'){
            $query->where('clevel', 'like', '%c3b%');
            unset($data_where['clevel']);
        }elseif (@$data_where['clevel'] == 'c3b_only'){
            $query->where('clevel', 'c3b');
            unset($data_where['clevel']);
        }
        if(@$data_where['current_level'] == 'l0'){
            $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
            unset($data_where['current_level']);
        }
        if(@$data_where['olm_status'] === 0){
            $query->whereIn('olm_status', [0, '0']);
            unset($data_where['olm_status']);
        }else if(@$data_where['olm_status'] === 1){
            $query->whereIn('olm_status', [1, '1']);
            unset($data_where['olm_status']);
        }else if(@$data_where['olm_status'] == -1){
            $query->whereNotIn('olm_status', [0, 1, 2, 3, '0', '1', '2', '3']);
            unset($data_where['olm_status']);
        }
        $query->where('is_export', '<>', 1);
        $query->where($data_where);

        if($request->contact_id){
            $id = explode(',', $request->contact_id);
            $query->whereIn('_id', $id);
        }

        $query->limit((int)$limit);
        $contacts = $query->orderBy('submit_time', 'desc')->get();
        foreach ($contacts as $contact)
        {
            $contact->is_export = 1;
            $contact->save();
        }
    }


    public function import(Request $request)
    {
        $file = $request->file('import');

        $destinationPath = storage_path('app/upload');
        $file->move($destinationPath,$file->getClientOriginalName());

        $filePath =  $destinationPath . '/' . $file->getClientOriginalName();
        // DB::connection( 'mongodb' )->enableQueryLog();

        Excel::load($filePath, function($reader) {

            $client = new Client();
            // Getting all results
            $results = $reader->get();
            /*$r = $client->request('POST', "http://209.58.165.15/api/v5/tracking/submitter", [
                'json' => $results->toArray()
            ]);*/

            $import_time = time();

            $cnt = 0;
            $template = \config('constants.TEMPLATE_IMPORT');
            $invalid = array_diff($template,$results->getHeading());

            if(count($invalid) > 0){
                if(count($invalid) < 5){
                    $fields = implode("',' ",$invalid);
                    $errors =  'Invalid field(s): \''.$fields.'\' in file import !!! - Please download sample file.';
                    echo $errors;
	                exit;
                }
                $errors =  'File import is invalid !!! - Please download sample file.';
                echo $errors;
	            exit;
            }

            foreach($results as $item){

                if($item->phone == '' && $item->name == '' && $item->email == ''){
                    continue; // check import blank record
                }

                // validate submit_time
                $submit_time = $import_time * 1000;

                $contact = new Contact();
                $contact->contact_source = "import_data";
                $contact->submit_time = $submit_time;
                $contact->submit_hour = (int) date( "H", $submit_time / 1000 );
	            $contact->submit_date = (int) strtotime(date('Y-m-d',$submit_time / 1000)) * 1000;
                $contact->created_date = date('Y-m-d H:m:s');
                $contact->source_name = $item->utm_source;
                $contact->team_name = $item->utm_team;
                $contact->marketer_name = $item->utm_agent;
                $contact->utm_medium = $item->utm_medium;
                $contact->campaign_name = $item->utm_campaign;
                $contact->subcampaign_name = $item->utm_subcampaign;
                $contact->ad_name = $item->utm_ad;
                $contact->name = $item->fullname;
                $contact->phone = $this->format_phone($item->phone);
                $contact->email = $item->email;
                $contact->age = $item->age;
                $contact->landing_page = $item->landing_page;
                $contact->ad_link = $item->ad_link;
                $contact->channel_name = $item->channel;
                $contact->import_time = $import_time;
                $contact->clevel = "c3a";

                list($contact->clevel, $contact->invalid_reason) = $this->validate_c3a($contact);

                if($contact->clevel == 'c3b'){
                    list($contact->clevel, $contact->invalid_reason) = $this->validate_c3b($contact);
                }

                // match ad_id
                $uri_query = "utm_source={$item->utm_source}&utm_team={$item->utm_team}&utm_agent={$item->utm_agent}&utm_campaign={$item->utm_campaign}&utm_medium={$item->utm_medium}&utm_subcampaign={$item->utm_subcampaign}&utm_ad={$item->utm_ad}";
                $ad = Ad::where('uri_query', $uri_query)->first();
                if($ad === null){
                    $contact->ad_id = 'unknown';
	                $contact->marketer_id = 'unknown';
	                $contact->source_name = "Unknown";
	                $contact->team_name = "Unknown";
	                $contact->marketer_name = "Unknown";
	                $contact->channel_name = "Unknown";
	                $contact->campaign_name = "Unknown";
	                $contact->subcampaign_name = "Unknown";
	                $contact->ad_name = "Unknown";
                }else{
                    $contact->ad_id = $ad->_id;
                    $contact->source_id = $ad->source_id;
                    $contact->team_id = $ad->team_id;
                    $contact->marketer_id = $ad->creator_id;
                    $contact->campaign_id = $ad->campaign_id;
                    $contact-> subcampaign_id = $ad->subcampaign_id;
                }
                $contact->contact_id = $this->gen_contact_id();

                $cnt++;
                $contact->save();

                // Update c3, c3b statistic in ad_results
                $ad_result = AdResult::where('ad_id', $contact->ad_id)->where('date', date('Y-m-d', $contact->submit_time/1000))->first();
                if($ad_result === null){
                    $ad_result = new AdResult();
                    $ad_result->ad_id   = $contact->ad_id;
                    $ad_result->date    = date('Y-m-d', $contact->submit_time/1000);
                    $ad_result->c3      = 1;
                    $ad_result->c3a     = ($contact->clevel === "c3a")  ? 1 : 0;
                    $ad_result->c3b     = ($contact->clevel === "c3b")  ? 1 : 0;
                    $ad_result->c3bg    = ($contact->clevel === "c3bg") ? 1 : 0;
                    if($contact->ad_id !== 'unknown') $ad_result->creator_id = auth()->user()->_id;
                }else{
                    $ad_result->c3++;
                    $ad_result->c3a     += ($contact->clevel === "c3a")     ? 1 : 0;
                    $ad_result->c3b     += ($contact->clevel === "c3b")     ? 1 : 0;
                    $ad_result->c3bg    += ($contact->clevel === "c3bg")    ? 1 : 0;
                }
                $ad_result->save();
            }

            echo $cnt . " Contact(s) have been imported successfully.";

        });
        //DB::connection('mongodb')->getQueryLog();
        //return redirect()->back();
    }

    public function importEgentic(Request $request)
    {
        $file = $request->file('import');

        $destinationPath = storage_path('app/upload');

        $file->move($destinationPath,$file->getClientOriginalName());

        $filePath =  $destinationPath . '/' . $file->getClientOriginalName();
        // DB::connection( 'mongodb' )->enableQueryLog();

        Excel::load($filePath, function($reader) {
            // Getting all results
            $results = $reader->get();
            $import_time = time();
            $cnt = 0;
            $template = \config('constants.TEMPLATE_IMPORT_EGENTIC');
            $invalid = array_diff($template,$results->getHeading());

            if(count($invalid) > 0){
                if(count($invalid) < 5){
                    $fields = implode("',' ",$invalid);
                    $errors =  'Invalid field(s): \''.$fields.'\' in file import !!! - Please download sample file.';
                    echo $errors;
                    exit;
                }
                $errors =  'File import is invalid !!! - Please download sample file.';
	            echo $errors;
	            exit;
            }

            $request = request();

            $startDate = strtotime("midnight")*1000;
            $endDate = strtotime("tomorrow")*1000;
//            if($request->registered_date){
//                $date_place = str_replace('-', ' ', $request->registered_date);
//                $date_arr = explode(' ', str_replace('/', '-', $date_place));
//                $startDate = strtotime($date_arr[1])*1000;
//                $endDate = strtotime("+1 day", strtotime($date_arr[1]))*1000;
//                $import_time = strtotime($date_arr[1])*1000;
//            }

            $query = Contact::where('submit_time', '>=', $startDate);
            $query->where('submit_time', '<', $endDate);
            $contacts = $query->get();

            $phone_array = array();
            foreach ($contacts as $item) {
                array_push($phone_array, $item->phone);
            }

            foreach($results as $item){

                if($item->tel_number_complete == '' && $item->firstname == '' && $item->lastname == '' && $item->email == ''){
                    continue; // check import blank record
                }

                if (in_array($item->tel_number_complete, $phone_array)) {
                    continue;
                }

	            $submit_time = $import_time * 1000;

                $contact = new Contact();
                $contact->contact_source = "import_egentic";
                $contact->submit_time = $submit_time;
                $contact->submit_hour = (int) date( "H", $submit_time / 1000 );
	            $contact->submit_date = (int) strtotime(date('Y-m-d',$submit_time / 1000)) * 1000;
                $contact->created_date = date('Y-m-d H:m:s');
                $contact->source_name = "";
                $contact->team_name = "";
                $contact->marketer_name = "";
                $contact->utm_medium = "";
                $contact->campaign_name = "";
                $contact->subcampaign_name = "";
                $contact->ad_name = "";
                $contact->name = $item->firstname." ".$item->lastname;
                $contact->phone = $this->format_phone($item->tel_number_complete);
                $contact->email = $item->email;
                $contact->age = $item->age;
                $contact->landing_page = "";
                $contact->ad_link = "";
                $contact->channel_name = "TK100.eGentic";
                $contact->import_time = $import_time;
                $contact->clevel = "c3bg";

                // match ad_id
                $uri_query = "utm_source={$item->utm_source}&utm_team={$item->utm_team}&utm_agent={$item->utm_agent}&utm_campaign={$item->utm_campaign}&utm_medium={$item->utm_medium}&utm_subcampaign={$item->utm_subcampaign}&utm_ad={$item->utm_ad}";
                $ad = Ad::where('uri_query', $uri_query)->first();
                if($ad === null){
                    $contact->ad_id = 'unknown';
                    $contact->marketer_id = 'unknown';
	                $contact->source_name = "Unknown";
	                $contact->team_name = "Unknown";
	                $contact->marketer_name = "Unknown";
	                $contact->campaign_name = "Unknown";
	                $contact->subcampaign_name = "Unknown";
	                $contact->ad_name = "Unknown";
                }else{
                    $contact->ad_id = $ad->_id;
                    $contact->source_id = $ad->source_id;
                    $contact->team_id = $ad->team_id;
                    $contact->marketer_id = $ad->creator_id;
                    $contact->campaign_id = $ad->campaign_id;
                    $contact->subcampaign_id = $ad->subcampaign_id;
                }
                $contact->contact_id = $this->gen_contact_id();

                $cnt++;
                $contact->save();

                // Update c3, c3b statistic in ad_results
                $ad_result = AdResult::where('ad_id', $contact->ad_id)->where('date', date('Y-m-d', $contact->submit_time/1000))->first();
                if($ad_result === null){
                    $ad_result = new AdResult();
                    $ad_result->ad_id   = $contact->ad_id;
                    $ad_result->date    = date('Y-m-d', $contact->submit_time/1000);
                    $ad_result->c3      = 1;
                    $ad_result->c3a     = ($contact->clevel === "c3a")  ? 1 : 0;
                    $ad_result->c3b     = ($contact->clevel === "c3b")  ? 1 : 0;
                    $ad_result->c3bg    = ($contact->clevel === "c3bg") ? 1 : 0;
                    if($contact->ad_id !== 'unknown') $ad_result->creator_id = auth()->user()->_id;
                }else{
                    $ad_result->c3++;
                    $ad_result->c3a     += ($contact->clevel === "c3a")     ? 1 : 0;
                    $ad_result->c3b     += ($contact->clevel === "c3b")     ? 1 : 0;
                    $ad_result->c3bg    += ($contact->clevel === "c3bg")    ? 1 : 0;
                }
                $ad_result->save();
            }

            echo $cnt . " Contact(s) have been imported successfully.";
        });
        //DB::connection('mongodb')->getQueryLog();
        //return redirect()->back();
    }

    private function format_phone($phone)
    {
        $phone = preg_replace('/[^0-9]/', "", $phone);
        $phone = ltrim($phone, '0');

        return $phone;
    }

    private function validate_phone($phone){
        if(!preg_match('/^[0-9]/', $phone))
        {
            return false;
        }

        if($phone) return true;
        return false;
    }

    private function gen_contact_id()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s%s%s%s%s%s%sTL', str_split(bin2hex($data), 4));
    }

    private function validate_c3a($contact){

        // validate phone
        if(!$this->validate_phone($contact->phone)){
            $clevel         = "c3a";
            $invalid_reason = "invalid phone";

            return array($clevel, $invalid_reason);
        }

        // validate email
        $email = $contact->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $clevel         = "c3a";
            $invalid_reason = 'invalid email';
            return array($clevel, $invalid_reason);
        }

        // Check duplicated in current day
        $exist = Contact::where([['phone', $contact->phone], ['name', $contact->name]])
            ->orWhere([['phone', $contact->phone], ['email', $contact->email]])
            ->orWhere([['name', $contact->phone], ['email', $contact->name]])
            ->where('submit_time', '>=', strtotime("midnight")*1000)
            ->where('submit_time', '<', strtotime("tomorrow")*1000)->exists();

        if($exist){
            $clevel         = "c3a";
            $invalid_reason = 'duplicated';
            return array($clevel, $invalid_reason);
        }

        return array('c3b', '');
    }

    private function validate_c3b($contact){
        if($contact->age < 18){
            $clevel         = "c3b";
            $invalid_reason = 'c3b - age < 18';
            return array($clevel, $invalid_reason);
        }

        $past_15_date = strtotime("-15 day");

        // Check duplicated
        $exist = Contact::where([['phone', $contact->phone], ['name', $contact->name]])
            ->orWhere([['phone', $contact->phone], ['email', $contact->email]])
            ->orWhere([['name', $contact->phone], ['email', $contact->name]])
            ->where('submit_time', '>=', $past_15_date * 1000)
            ->where('submit_time', '<', strtotime("tomorrow")*1000)->exists();

        if($exist){
            $clevel         = "c3b";
            $invalid_reason = 'c3b - duplicated in 15 days';
            return array($clevel, $invalid_reason);
        }

        return array('c3bg', '');
    }

    private function getOrderData(){
        $request    = request();
        $order      = array();

        if($request['order'][0]['column'] && $request['order'][0]['dir']){
            $order['column']    = $request['order'][0]['column'];
            $order['type']      = $request['order'][0]['dir'];
        }

        return $order;
    }

    public function exportToOLM(){
//        $url = 'http://58.187.9.138/api/OlmInsert/InsertContactOLM';
        $url = 'http://210.245.115.55:8081/api/OlmInsert/InsertContactOLM';

        $data_where = $this->getWhereData();
        $request = request();

        $startDate = strtotime("midnight")*1000;
        $endDate = strtotime("tomorrow")*1000;
        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = strtotime($date_arr[0])*1000;
            // $endDate = Date('Y-m-d 23:59:59', strtotime($date_arr[1]));
            $endDate = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }
        $query = Contact::where('submit_time', '>=', $startDate);
        $query->where('submit_time', '<', $endDate);
        $query->whereNotIn('olm_status', ['0','1']);

        // HoaTV fix multiple select channel
        $arrChannelName = array();
        if ($request->channel) {
            $arrChannelName    = explode(',',$request->channel);
            $query->whereIn('channel_name',$arrChannelName);
        }

        if(@$data_where['clevel'] == 'c3bg'){
            $query->where('clevel','c3bg');
            unset($data_where['clevel']);
        }else if (@$data_where['clevel'] == 'c3b' || @$data_where['clevel'] == 'c3a' || @$data_where['clevel'] == 'c3b_only'){
            $query->where('clevel','c3bg');
            unset($data_where['clevel']);
        }
        if(@$data_where['current_level'] == 'l0'){
            $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
            unset($data_where['current_level']);
        }

        // HoaTV - Only get contact not export and error
        // if(@$data_where['olm_status'] === 0){
        //     $query->where('olm_status', 0);
        //     unset($data_where['olm_status']);
        // }
        // if(@$data_where['olm_status'] === 1){
        //     $query->where('olm_status', 0);
        //     unset($data_where['olm_status']);
        // }
        // if(@$data_where['olm_status'] == -1){
        //     $query->whereNotIn('olm_status', [0, 1, 2]);
        //     unset($data_where['olm_status']);
        // }
        $query->whereNotIn('olm_status', [0, 1, '0', '1']);
        unset($data_where['olm_status']);

        $query->where($data_where);

        if($request->id){
            if($request->id != 'All' && count($request->id) > 0){
                $query->whereIn('_id', array_keys($request->id));
            }
        }

        $result = array();
        $result['cnt_success']      = 0;
        $result['cnt_duplicate']    = 0;
        $result['cnt_error']        = 0;
        $result['cnt_total']        = 0;

        if($request->export_sale_sort){
            $query->orderBy('submit_time', $request->export_sale_sort);
        }else{
            $query->orderBy('submit_time', 'desc');
        }

        $limit = (int)$request->limit;
        $export_sale_date = '';
        if($request->export_sale_date){
            $export_sale_date = str_replace('/', '-', $request->export_sale_date);
        }
            
        $query->chunk( 1000, function ( $contacts ) use ( $url , &$result, $export_sale_date, $limit) {
            $count = 0;
            foreach ($contacts as $contact)
            {
                if($count >= $limit){
                    break;
                }
                
                if (!$contact->ad_id){
                    $contact->ad_id = "unknown";
                }

                $source_type = '';
                if(@$contact->source_name){
                    $source_type = @$contact->source_name;
                }else if(@$contact->source_id){
                    $source_id      = $contact->source_id;
                    $source         = Source::find($source_id);
                    $source_type    = @$source->name;
                }

                $data_array =  array(
                    "ads_link"          => @$contact->ads_link,
                    "email"             => @$contact->email,
                    "fullname"          => @$contact->name,
                    "phone"             => @$contact->phone,
                    "contact_channel"   => @$contact->channel_name,
                    "source_type"       => $source_type,
                    "registereddate"    => @$contact->submit_time,
                    "submit_time"       => @$contact->submit_time,
                    "code"              => @$contact->contact_id
                );

                $make_call  = $this->callAPI('POST', $url, json_encode($data_array));
                $response   = json_decode($make_call, true);
                $status     = $response['results'][0]['Status'];

                $contactUpdate    = $this->handleHandover($contact,$status);
                $contactUpdate->export_sale_date = strtotime($export_sale_date) * 1000;
                $contactUpdate->save();

                if (strtolower($status) == "ok"){
                    $result['cnt_success']  += 1;
                } else if (strtolower($status) == "duplicated"){
                    $result['cnt_duplicate'] += 1;
                } else if (strtolower($status) == "error"){
                    $result['cnt_error']    += 1;
                } else {
                    $result['cnt_error']    += 1;
                }
                
                $LogExportToSale = new LogExportToSale();
                $LogExportToSale->ads_link =  $contact->ads_link;
                $LogExportToSale->email =  $contact->email;
                $LogExportToSale->name =  $contact->name;
                $LogExportToSale->phone =  $contact->phone;
                $LogExportToSale->channel_name =  $contact->channel_name;
                $LogExportToSale->source_type =  $source_type;
                $LogExportToSale->submit_time =  $contact->submit_time;
                $LogExportToSale->submit_time =  $contact->submit_time;
                $LogExportToSale->contact_id =  $contact->contact_id;
                $LogExportToSale->status =  $status;
                $LogExportToSale->response =  $response;
                $LogExportToSale->save();
                
                $count++;
            }
            $result['cnt_total'] = $count;
        });
        return $result;
    }

    private function handleHandover($contact, $apiStatus){

        if (strtolower($apiStatus) == "ok"){
            $dateFromContactID = date('Y-m-d', $contact->submit_time/1000);

            $contact->handover_date = date("Y-m-d");
            $contact->current_level = "l1";
            $contact->olm_status    = 0;
            $contact->l1_time = date("Y-m-d");

            // Update ad_results
            // Get data base on date contact submited
            $ad_result  = AdResult::where("ad_id",$contact->ad_id)->where("date",$dateFromContactID)->get();
            $countL1    = 1;
            foreach($ad_result as $item){
                $countL1 = $countL1 + @$item->l1;
            }
            $ad_result = AdResult::where("ad_id",$contact->ad_id)->where("date",$dateFromContactID)->first();
            $ad_result->l1 = $countL1;
            $ad_result->save();
        } else if (strtolower($apiStatus) == "duplicated"){
            $contact->olm_status = 1;
        } else if (strtolower($apiStatus) == "error"){
            $contact->olm_status = 2;
        } else {
            $contact->olm_status = 3;
        }

        return $contact;
    }

    private function callAPI($method, $url, $data){
        $curl = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'APIKEY: 111111111111111111111',
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
    }

    public function getContactPaginate(){
        $params = \request();
        $data   = $this->getC3Data();

        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $data['total'] ),
            "recordsFiltered" => intval( $data['total']),
            "data"            => $data['contacts'],
        );

        echo json_encode($json_data);  // send data as json format
    }

    private function getSeachData(){
        $request        = request();

        if($request['search']['value']){
            return $request['search']['value'];
        }

        return '';
    }

    private function setColumns(){
        //define index of column
        $columns = array(
            0   =>'name',
            1   =>'email',
            2   =>'phone',
            3   =>'age',
            4   =>'submit_time',
            5   =>'clevel',
            6   =>'current_level',
            7   =>'source_name',
            8   =>'team_name',
            9   =>'marketer_name',
            10  =>'campaign_name',
            11  =>'subcampaign_name',
            12  =>'ad_name',
            13  =>'landing_page',
            14  =>'channel_name',
            15  =>'olm_status'
        );

        return $columns;
    }

    private function formatRecord($contacts){

        foreach ($contacts as $contact){
            $arr[0] = $contact['_id'];
            $arr[1] = $contact['name'];
            $duplicatedNumbers = Contact::where('_id', '<>', $contact['_id'])
                ->where('phone', $contact['phone'])->count();
            $arr[2] = $duplicatedNumbers;

            $contact['name']                = $contact['name'] ? $arr : "-";
            $contact['email']               = $contact['email'] ? $contact['email'] : "-";
            $contact['phone']               = $contact['phone'] ? $contact['phone'] : "-";
            $contact['age']                 = $contact['age'] ? $contact['age'] : "-";
            $contact['submit_time']         = $contact['submit_time'] ?
                date('d-m-Y H:i:s',$contact['submit_time']/1000) : "-";
            $contact['clevel']              = $contact['clevel']? $contact['clevel'] : "-";
            $contact['current_level']       = $contact['current_level'] ? $contact['current_level'] : "-";
            $contact['source_name']         = $contact['source_name'] ? $contact['source_name'] : "-";
            $contact['team_name']           = $contact['team_name'] ? $contact['team_name'] : "-";
            $contact['marketer_name']       = $contact['marketer_name'] ? $contact['marketer_name'] : "-";
            $contact['campaign_name']       = $contact['campaign_name'] ? $contact['campaign_name'] : "-";
            $contact['subcampaign_name']    = $contact['subcampaign_name'] ? $contact['subcampaign_name'] : "-";
            $contact['ad_name']             = $contact['ad_name'] ? $contact['ad_name'] : "-";
            $contact['landing_page']        = $contact['landing_page'] ? $contact['landing_page'] : "-";
            $contact['channel_name']        = $contact['channel_name'] ? $contact['channel_name'] : "-";
        }

        return $contacts;
    }

    public function updateContacts(){

        $data_where = $this->getWhereDataUpdateExport();

        $request = request();

        $startDate = strtotime("midnight")*1000;
        $endDate = strtotime("tomorrow")*1000;
        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = strtotime($date_arr[0])*1000;
            // $endDate = Date('Y-m-d 23:59:59', strtotime($date_arr[1]));
            $endDate = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }
        $query = Contact::where('submit_time', '>=', $startDate);
        $query->where('submit_time', '<', $endDate);

        // HoaTV fix multiple select channel
        $arrChannelName = array();
        if ($request->channel) {
            $arrChannelName    = explode(',',$request->channel);
            $query->whereIn('channel_name',$arrChannelName);
        }

        if(@$data_where['clevel'] == 'c3b'){
            $query->where('clevel', 'like', '%c3b%');
            unset($data_where['clevel']);
        }
        if(@$data_where['current_level'] == 'l0'){
            $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
            unset($data_where['current_level']);
        }
        $query->where($data_where);

        $id = [];

        if($request->id){
            $id = $request->id;
            $query->whereIn('_id', array_keys($request->id));
        }
        // only current page
        $page_size  = Config::getByKey('PAGE_SIZE');
        $query->limit((int)$page_size);

        $query->orderBy('submit_time', 'desc');

        $contacts = $query->get();
        foreach ($contacts as $contact)
        {
            if(isset($id[$contact->_id])){
                if(isset($id[$contact->_id]['status'])){
                    $contact->is_export     = (int)$id[$contact->_id]['status'];
                }
                if(isset($id[$contact->_id]['olm_status'])){
                    $contact->olm_status    = (int)$id[$contact->_id]['olm_status'];
                }
                if(isset($id[$contact->_id]['channel_name'])){
                    $contact->channel_name  = $id[$contact->_id]['channel_name'];
                }
                if(isset($id[$contact->_id]['channel_id'])){
                    $contact->channel_id    = $id[$contact->_id]['channel_id'];
                }

                // HoaTV for change level from c3bg to c3b
                if(isset($id[$contact->_id]['invalid_reason']) && $contact->clevel == "c3bg"){
                    $contact->invalid_reason    = $id[$contact->_id]['invalid_reason'];
                    $contact->invalid_reason_mode    = $id[$contact->_id]['invalid_reason_mode'];
                    $contact->is_update_manual    = true;
                    $contact->clevel = "c3b";
                    // handle count ad_result only from from c3bg down to c3b
                    $this->handleCountAdResult(true,$contact->ad_id, $contact->submit_time);
                }else if(!isset($id[$contact->_id]['invalid_reason']) && $contact->clevel == "c3b" && $contact->is_update_manual){
                    // only contact is  already update is allow to update
                    $contact->invalid_reason    = "";
                    $contact->invalid_reason_mode    = "";
                    $contact->is_update_manual    = true;
                    $contact->clevel = "c3bg";
                    // handle count ad_result only from from c3b down to c3bg
                    $this->handleCountAdResult(false, $contact->ad_id, $contact->submit_time);
                }

                $contact->save();
            }else{
                if($request->new_status != '')
                {
                    $contact->is_export = (int)$request->new_status;
                    $contact->save();
                }
            }
        }
    }

    private function getWhereDataUpdateExport(){
        $request    = request();
        $data_where = array();
        if ($request->source_id) {
            $data_where['source_id']        = $request->source_id;
        }
        if ($request->team_id) {
            $data_where['team_id']          = $request->team_id;
        }
        if ($request->marketer_id) {
            $data_where['marketer_id']      = $request->marketer_id;
        }
        if ($request->campaign_id) {
            $data_where['campaign_id']      = $request->campaign_id;
        }
        if ($request->subcampaign_id) {
            $data_where['subcampaign_id']   = $request->subcampaign_id;
        }
        if ($request->current_level) {
            $data_where['current_level']    = $request->current_level;
        }
        if ($request->clevel) {
            $data_where['clevel']           = $request->clevel;
        }
        if ($request->old_status) {
            $data_where['is_export']        = (int)$request->old_status;
        }
        if ($request->landing_page) {
            $data_where['landing_page']     = $request->landing_page;
        }
        // if ($request->channel) {
        //     $data_where['channel_name']     = $request->channel;
        // }
        if (isset($request->olm_status)) {
            $data_where['olm_status']       = $request->olm_status;
        }

        return $data_where;
    }


    // HoaTV handle ad_result when changes level from c3bg down to c3b and vice versa
    private function handleCountAdResult($isDown, $adID, $submitTime){
        $adResult = AdResult::where('ad_id', $adID)->where('date',date('Y-m-d',$submitTime/1000))->first();
        if($isDown){
            $adResult->c3bg = (int)$adResult->c3bg - 1;
            $adResult->c3b = (int)$adResult->c3b + 1;
        }else{
            $adResult->c3bg = (int)$adResult->c3bg + 1;
            $adResult->c3b = (int)$adResult->c3b - 1;
        }
        $adResult->save();
    }

}
