<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Contact;
use App\LandingPage;
use App\Team;
use App\User;
use DB;
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

        $contacts = Contact::where('registered_date', '>=', Date('Y-m-d'))
            ->where('registered_date', '<=', Date('Y-m-d 23:59:59'))
            ->orderBy('registered_date', 'desc')->limit(1000)->get();
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
            $data_where['current_level'] = intval($request->current_level);
        }
        // DB::connection( 'mongodb' )->enableQueryLog();

        $startDate = Date('Y-m-d');
        $endDate = Date('Y-m-d 23:59:59');
        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = Date('Y-m-d', strtotime($date_arr[0]));
            $endDate = Date('Y-m-d 23:59:59', strtotime($date_arr[1]));
        }
        $query = Contact::where('registered_date', '>=', $startDate);
        $query->where('registered_date', '<=', $endDate);

        if(count($data_where) > 0){
            $query->where($data_where);
        }

        $contacts = $query->orderBy('registered_date', 'desc')->limit(1000)->get();
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
                            Date('d-m-Y H:i:s', strtotime($item->registered_date)),
                            $item->current_level,
                            $item->marketer_name,
                            $item->campaign_name,
                            $item->subcampaign_name,
                            $item->ad_name,
                            $item->landingpage_name,
                        );
                    }
                    $sheet->fromArray($datas, NULL, 'A1', FALSE, FALSE);
                    $headings = array('STT', 'Name', 'Email', 'Phone', 'Registered at', 'Current level', 'Marketer', 'Campaign', 'Channel', 'Ads', 'Landing page');
                    $sheet->prependRow(1, $headings);
                    $sheet->cells('A1:K1', function ($cells) {
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

    public function getContactsSource()
    {
        $data_where = array();
        $request = request();
        if ($request->source_id) {
            $data_where['source_id'] = $request->source_id;
        }

        $campaigns = Campaign::where($data_where)->get();
        $html_campaign = '<option value=\'all\' selected>All</option>';
        foreach ($campaigns as $item) {
            $html_campaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }

        $teams = Team::where($data_where)->get();
        $html_team = '<option value=\'all\' selected>All</option>';
        foreach ($teams as $item) {
            $html_team .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }

        $data_return = array(
            'status'           => TRUE,
            'content_team'     => $html_team,
            'content_campaign' => $html_campaign
        );
        echo json_encode($data_return);

    }

    public function getContactsTeam()
    {
        $data_where = array();
        $request = request();
        if ($request->team_id) {
            $data_where['team_id'] = $request->team_id;
        }
        $team = Team::find($request->team_id);
        $campaigns = Campaign::where($data_where)->get();
        $sources = Source::all();
        $html_source = "<option value='all'>All</option>";
        $html_campaign = "<option value='all'>All</option>";
        foreach ($campaigns as $item) {
            $html_campaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }
        foreach ($sources as $item) {
            $html_source .= "<option value='" . $item->id . "' " . ($team && $item->id == $team->source_id ? "selected" : '') . "> " . $item->name . " </option>";
        }

        $data_return = array(
            'status'           => TRUE,
            'content_source'   => $html_source,
            'content_campaign' => $html_campaign
        );
        echo json_encode($data_return);

    }

    public function getContactsCampaings()
    {
        $request = request();
        $data_where = array();

        $campaign = Campaign::find($request->campaign_id);
        $sources = Source::all();
        $teams = Team::all();
        $html_source = '<option value=\'all\'>All</option>';
        $html_team = '<option value=\'all\'>All</option>';
        foreach ($sources as $item) {
            $html_source .= "<option value='" . $item->id . "' " . ($campaign && $item->id == $campaign->source_id ? "selected" : '') . "> " . $item->name . " </option>";
        }
        foreach ($teams as $item) {
            $html_team .= "<option value='" . $item->id . "' " . ($campaign && $item->id == $campaign->team_id ? "selected" : '') . "> " . $item->name . " </option>";
        }

        $data_return = array(
            'status'         => TRUE,
            'content_source' => $html_source,
            'content_team'   => $html_team
        );
        echo json_encode($data_return);

    }

}
