<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Contact;
use App\LandingPage;
use App\Team;
use App\User;
use App\Source;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

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
        $active = 'contacts-c3';
        $breadcrumbs = "<i class=\"fa-fw fa fa-child\"></i> Contacts <span>> C3</span>";

        $contacts = Contact::orderBy('registered_ at', 'desc')->limit(1000)->get();
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

    public function details($id)
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
    }

    public function getC3()
    {
        $data = $this->getC3Data();
        return view('pages.table_contact_c3', $data);

    }

    public function getC3Data()
    {
        $data_where = array();
        $request = request();
//        if ($request->registered_date) {
//        $data_where['registered_date >='] = substr($request->registered_date, 0, 8);
//        }
//        if ($request->registered_date) {
//            $data_where['registered_date <='] = substr($request->registered_date, 13, 19);
//        }

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
        if (count($data_where) >= 1) {
            $contacts = Contact::where($data_where);
        }
        $date_arr = explode(' ',$request->registered_date);
        $startDate =Date('Y-m-d',  strtotime($date_arr[0]));
        $endDate =Date('Y-m-d',  strtotime($date_arr[2]));
        $contacts = $contacts->where('registered_date', '>=', $startDate)
            ->where('registered_date', '<=',$endDate)
            ->orderBy('registered_ at', 'desc')->limit(1000)->get();
        $data = $data_where;
        $data['contacts'] = $contacts;
        return $data;
    }

    public function export()
    {

        Excel::create('contacts_c3', function ($excel)  {
            $excel->sheet('contacts_c3', function ($sheet)  {
                $data = $this->getC3Data();
//                echo '<pre>';
//                print_r($data);die();
                $contacts = $data['contacts'];
                foreach ($contacts as $item){
                    $datas[] = array(
                        $item->name,
                        $item->email,
                        $item->phone,
                        Date('d-m-Y H:i:s', strtotime($item->registered_date)),
                        $item->current_level,
                        $item->marketer_name,
                        $item->campaign_name,
                        $item->subcampaign_name,
                        $item->ad_name,
                        $item->landingpage_name,
                    );
                }
                $sheet->fromArray($datas, null, 'A1', false, false);
                $headings = array('Name', 'Email', 'Phone', 'Registered at', 'Current level', 'Marketer', 'Campaign', 'Channel', 'Ads', 'Landing page');
                $sheet->prependRow(1, $headings);
            });
        })->export('xls');
    }

}
