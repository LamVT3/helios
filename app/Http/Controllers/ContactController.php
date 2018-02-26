<?php

namespace App\Http\Controllers;

use App\Ad;
use App\AdResult;
use App\Campaign;
use App\Contact;
use App\LandingPage;
use App\Team;
use App\User;
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
        $page_title = "Contacts | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'contacts';
        $breadcrumbs = "<i class=\"fa-fw fa fa-child\"></i> Contacts <span>> C3</span>";

        $contacts = Contact::where('submit_time', '>=', strtotime("midnight")*1000)
            ->where('submit_time', '<', strtotime("tomorrow")*1000)
            ->orderBy('submit_time', 'desc')->limit(10000)->get();
        $sources = Source::all();
        $teams = Team::all();
        $marketers = User::all();
        $campaigns = Campaign::where('is_active', 1)->get();
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
            'campaigns'
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
        $data_where = array();
        $request = request();
        if ($request->source_id) {
            $data_where['source_id'] = $request->source_id;
        }
        if ($request->team_id) {
            $data_where['team_id'] = $request->team_id;
        }
        if ($request->marketer_id) {
            $data_where['marketer_id'] = $request->marketer_id;
        }
        if ($request->campaign_id) {
            $data_where['campaign_id'] = $request->campaign_id;
        }
        if ($request->current_level) {
            $data_where['current_level'] = $request->current_level;
        }
        if ($request->clevel) {
            $data_where['clevel'] = $request->clevel;
        }

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
            $query->where($data_where);
        }

        $contacts = $query->orderBy('submit_time', 'desc')->limit(20000)->get();
        // DB::connection('mongodb')->getQueryLog();
        $data = $data_where;
        $data['contacts'] = $contacts;
        return $data;
    }

    public function export()
    {
        $data = $this->getC3Data();
        $contacts = $data['contacts'];
        if (count($contacts) >= 1) {
            Excel::create('contacts_c3', function ($excel) {
                $excel->sheet('contacts_c3', function ($sheet) {
                    $data = $this->getC3Data();
                    $count = 1;
                    $contacts = $data['contacts'];
                    foreach ($contacts as $item) {
                        $datas[] = array(
                            $count++,
                            $item->name,
                            $item->email,
                            $item->phone,
                            $item->age,
                            Date('Y-m-d H:i:s', $item->submit_time/1000),
                            $item->current_level,
                            $item->marketer_name,
                            $item->campaign_name,
                            $item->subcampaign_name,
                            $item->ad_name,
                            $item->landing_page,
                            $item->contact_id,
                            $item->ads_link
                        );
                    }
                    $sheet->fromArray($datas, NULL, 'A1', FALSE, FALSE);
                    $headings = array('STT', 'Name', 'Email', 'Phone', 'Age', 'Time', 'Current level', 'Marketer', 'Campaign', 'Subcampaign', 'Ads', 'Landing page', 'ContactID', 'Link Tracking');
                    $sheet->prependRow(1, $headings);
                    $sheet->cells('A1:N1', function ($cells) {
                        $cells->setBackground('#191919');
                        $cells->setFontColor('#DBAC69');
                        $cells->setFontSize(12);
                        $cells->setFontWeight('bold');
                    });
                });

            })->export('xls');
        } else {
            return back();
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
            $invalid_item = [];

            foreach($results as $item){

                // validate submit_time

                $submit_time = is_object($item->submit_time) ? $item->submit_time->timestamp : $import_time;
                if($submit_time < strtotime("01/01/2010")
                    || $submit_time > strtotime("01/01/2035") ){
                    $submit_time = $import_time;
                }

                $phone = $this->format_phone($item->phone."");
                if(!$this->validate_phone($phone)) continue;

                // validate email
                $email = $item->email;
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $email = 'invalid@email.com';
                }

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
                $contact->phone = $phone;
                $contact->email = $email;
                $contact->age = $item->age;
                $contact->landing_page = $item->landing_page;
                $contact->ad_link = $item->ad_link;
                $contact->channel = $item->channel;
                $contact->import_time = $import_time;
                $contact->clevel = "c3b";

                // validate phone
                if(!$this->validate_phone($phone)){
                    $contact->clevel = "c3a";
                    $contact->invalid_reason = "invalid phone";
                }

                // Check duplicated
                $submit_date = date('Y-m-d', $contact->submit_time/1000);
                $startDate = strtotime($submit_date)*1000;
                $endDate = strtotime("+1 day", strtotime($submit_date)) * 1000;

                if(Contact::where('phone', $phone)->where('submit_time', '>=', $startDate)->where('submit_time', '<', $endDate)->exists()){
                    $contact->clevel = "c3a";
                    $contact->invalid_reason = "duplicated";
                }
                // Check age
                if(is_numeric($item->age) && $item->age < 18){
                    $contact->clevel = "c3a";
                    $contact->invalid_reason = "invalid age";
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

                $contact->save();

                // Update c3, c3b statistic in ad_results
                $ad_result = AdResult::where('ad_id', $contact->ad_id)->where('date', date('Y-m-d', $contact->submit_time/1000))->first();
                if($ad_result === null){
                    $ad_result = new AdResult();
                    $ad_result->ad_id = $contact->ad_id;
                    $ad_result->date = date('Y-m-d', $contact->submit_time/1000);
                    $ad_result->c3 = 1;
                    $ad_result->c3b = ($contact->clevel === "c3a") ? 0 : 1;
                    if($contact->ad_id !== 'unknown') $ad_result->creator_id = $ad->creator_id;
                }else{
                    $ad_result->c3 ++;
                    $ad_result->c3b += ($contact->clevel === "c3a") ? 0 : 1;
                }

                $ad_result->save();

            }

        });
        //DB::connection('mongodb')->getQueryLog();
        session()->flash('message', 'Contacts have been imported successfully');
        return redirect()->back();
    }

    private function format_phone($phone)
    {
        $phone = preg_replace('/[^0-9]/', "", $phone);
        $phone = trim($phone, '0');

        return $phone;
    }

    private function validate_phone($phone){
        if($phone) return true;
        return false;
    }

    private function gen_contact_id($contact)
    {
        $submit_time = date('Ymd', $contact->submit_time/1000);
        $phone = substr($contact->phone, -6);
        return $submit_time.$phone.'TL';
    }

}
