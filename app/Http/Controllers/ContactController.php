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

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title     = "Contacts | Helios";
        $page_css       = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active         = 'contacts';
        $breadcrumbs    = "<i class=\"fa-fw fa fa-child\"></i> Contacts <span>> C3</span>";

        $page_size  = Config::getByKey('PAGE_SIZE');
        $contacts   = Contact::where('submit_time', '>=', strtotime("midnight")*1000)
            ->where('submit_time', '<', strtotime("tomorrow")*1000)
            ->where('clevel', 'like', '%c3b%')
            ->orderBy('submit_time', 'desc')->limit((int)$page_size)->get();
        $sources        = Source::orderBy('name')->get();
        $teams          = Team::orderBy('name')->get();
        $marketers      = User::where('is_active', 1)->orderBy('username')->get();
        $campaigns      = Campaign::orderBy('name')->get();
        $subcampaigns   = Subcampaign::orderBy('name')->get();
        $exported       = $this->countExported();
        $landing_page   = LandingPage::where('is_active', 1)->orderBy('name')->get();
        $channel        = Channel::where('is_active', 1)->orderBy('name')->get();

        return view('pages.contacts-c3', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'contacts',
            'sources',
            'teams',
            'marketers',
            'campaigns',
            'page_size',
            'subcampaigns',
            'exported',
            'landing_page',
            'channel'
        ));
    }

    /*public function details($id)
    {
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'adsmanager';

        $contact = Contact::findOrFail($id);

        $page_title = "Contact: " . $contact->name . " | Helios";
        $breadcrumbs = "<i class=\"fa-fw fa fa-child\"></i> Contacts > <span>> " . $contact->name . "</span>";
        $landing_pages = LandingPage::where('is_active', 1)->get();

        return view('pages.contacts-details', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'contact',
            'landing_pages'
        ));
    }*/

    public function getC3()
    {
        $data = $this->getC3Data();
        return view('pages.table_contact_c3', $data);

    }

    public function getC3Data()
    {
        $columns     = $this->setColumns();
        $data_where  = $this->getWhereData();
        $data_search = $this->getSeachData();
        $order       = $this->getOrderData();

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

        if(count($data_where) > 0){
            if(@$data_where['clevel'] == 'c3b'){
                $query->where('clevel', 'like', '%c3b%');
                unset($data_where['clevel']);
            }
            if(@$data_where['current_level'] == 'l0'){
                $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
                unset($data_where['current_level']);
            }
            $query->where($data_where);
        }
//        if($request->limit)
//        {
//            $query->limit((int)$request->limit);
//        }

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

        $contacts = $query->get();
        // DB::connection('mongodb')->getQueryLog();
        $data = $data_where;
        $data['contacts'] = $contacts;

        return $data;
    }

    public function countExported()
    {
        $columns     = $this->setColumns();
        $data_where  = $this->getWhereData();
        $data_search = $this->getSeachData();
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
        $data_where['is_export'] = 1;

        $query = Contact::where('submit_time', '>=', $startDate);
        $query->where('submit_time', '<', $endDate);

        if(count($data_where) > 0){
            if(@$data_where['clevel'] == 'c3b'){
                $query->where('clevel', 'like', '%c3b%');
                unset($data_where['clevel']);
            }
            if(@$data_where['current_level'] == 'l0'){
                $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
                unset($data_where['current_level']);
            }
            $query->where($data_where);
        }

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
//        $data = $this->getC3Data();
//        $contacts = $data['contacts'];
//        if (count($contacts) >= 1) {
        $date = \request('registered_date');
        $date = str_replace('/','',$date);
        $file_name = 'Contact_C3_' . $date;
        Excel::create($file_name, function ($excel) {
            header('Content-Encoding: UTF-8');
            header('Content-type: text/csv; charset=UTF-8');
            $excel->sheet('contacts_c3', function ($sheet) {
                $data = $this->getC3Data();
                $count = 1;
                $contacts = $data['contacts'];
                $datas = array();
                $limit = 0;
                $updateCnt = 0;
                if(\request('limit')){
                    $limit = \request('limit');
                }
                foreach ($contacts as $item) {
                    $updateCnt++;
                    if($item->is_export){
                        continue;
                    }
                    $datas[] = array(
                        $count++,
//                        $this->checkSpecialSymbols($item->name),
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
                    if(\request('limit') && $count > $limit){
                        break;
                    }
                }
                $sheet->fromArray($datas, NULL, 'A1', FALSE, FALSE);
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
        if ($request->channel) {
            $data_where['channel_name']     = $request->channel;
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

        if(count($data_where) > 0){
            if(@$data_where['clevel'] == 'c3b'){
                $query->where('clevel', 'like', '%c3b%');
                unset($data_where['clevel']);
            }
            if(@$data_where['current_level'] == 'l0'){
                $query->whereNotIn('current_level', \config('constants.CURRENT_LEVEL'));
                unset($data_where['current_level']);
            }
            $query->where($data_where);
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
                    return redirect()->back()->withErrors($errors);
                }
                $errors =  'File import is invalid !!! - Please download sample file.';
                return redirect()->back()->withErrors($errors);
            }

            foreach($results as $item){

                if($item->phone == '' && $item->name == '' && $item->email == ''){
                    continue; // check import blank record
                }

                // validate submit_time
                $submit_time = is_object($item->submit_time) ? $item->submit_time->timestamp : $import_time;

                $contact = new Contact();
                $contact->contact_source = "import_data";
                $contact->msg_type = "submitter";
                $contact->submit_time = $submit_time * 1000;
                $contact->source_name = $item->utm_source;
                $contact->team_name = $item->utm_team;
                $contact->marketer_name = $item->utm_agent;
                $contact->utm_medium = $item->utm_medium;
                $contact->campaign_name = $item->utm_campaign;
                $contact->subcampaign_name = $item->utm_subcampaign;
                $contact->ad_name = $item->utm_ad;
                $contact->name = $item->fullname;
                $contact->phone = $item->phone;
                $contact->email = $item->email;
                $contact->age = $item->age;
                $contact->landing_page = $item->landing_page;
                $contact->ad_link = $item->ad_link;
                $contact->channel = $item->channel;
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
                }else{
                    $contact->ad_id = $ad->_id;
                    $contact->source_id = $ad->source_id;
                    $contact->team_id = $ad->team_id;
                    $contact->marketer_id = $ad->creator_id;
                    $contact->campaign_id = $ad->campaign_id;
                    $contact-> subcampaign_id = $ad->subcampaign_id;
                }
                $contact->contact_id = $this->gen_contact_id($contact);

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
                    if($contact->ad_id !== 'unknown') $ad_result->creator_id = $ad->creator_id;
                }else{
                    $ad_result->c3++;
                    $ad_result->c3a     += ($contact->clevel === "c3a")     ? 1 : 0;
                    $ad_result->c3b     += ($contact->clevel === "c3b")     ? 1 : 0;
                    $ad_result->c3bg    += ($contact->clevel === "c3bg")    ? 1 : 0;
                }
                $ad_result->save();
            }

            session()->flash('message', $cnt.' Contact(s) have been imported successfully');

        });
        //DB::connection('mongodb')->getQueryLog();
        return redirect()->back();
    }

    private function format_phone($phone)
    {
        $phone = preg_replace('/[^0-9]/', "", $phone);
        $phone = trim($phone, '0');

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

    private function gen_contact_id($contact)
    {
        $submit_time = date('Ymd', $contact->submit_time/1000);
        $phone = substr($contact->phone, -6);
        return $submit_time.$phone.'TL';
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

    private function getSeachData(){
        $request        = request();

        if($request->search){
            return $request->search;
        }

        return '';
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
            14  =>'channel_name'
        );

        return $columns;
    }

    private function checkSpecialSymbols($str){
        $pattern        = get_html_translation_table( HTML_ENTITIES );
        $pattern['💝']   = '💝';

        $newStr = str_replace($pattern, "", $str);

        return $newStr;
    }

}
