<?php

namespace App\Http\Controllers;

use App\Ad;
use App\AdResult;
use App\Campaign;
use App\Channel;
use App\Config;
use App\Source;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Report | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'report';
        // 2018-04-04 lamvt update title
        $breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Quality Report </span>";
        // end 2018-04-04

        // $ads = Ad::pluck('_id')->toArray();

        $startDate = Date('Y-m-d');
        $endDate = Date('Y-m-d');

        $query = AdResult::where('date', '>=', $startDate);
        $query->where('date', '<=', $endDate);

        $results = $query->get();

        $report = [];
        if ($results) {
            $report = $this->prepare_report($results);
        }

        $sources = Source::all();
        $teams = Team::all();
        $marketers = User::all();
        $campaigns = Campaign::where('is_active', 1)->get();

        return view('pages.report-quality', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'report',
            'sources',
            'teams',
            'marketers',
            'campaigns'
        ));
    }

    public function getReport()
    {
        $data = $this->getReportData();

        $data['sources'] = Source::all();
        $data['teams'] = Team::all();
        $data['marketers'] = User::all();
        $data['campaigns'] = Campaign::where('is_active', 1)->get();
        $data['source'] = \request('source', 'All');
        $data['team'] = \request('team', 'All');
        $data['marketer'] = \request('marketer', 'All');
        $data['campaign'] = \request('campaign', 'All');
        return view('pages.table_report-quality', $data);
    }

    public function getReportData()
    {
        // DB::connection( 'mongodb' )->enableQueryLog();
        $data_where = array();
        $request = request();
        if ($request->source_id) {
            $data_where['source_id'] = $request->source_id;
        }
        if ($request->team_id) {
            $data_where['team_id'] = $request->team_id;
        }
        if ($request->marketer_id) {
            $data_where['creator_id'] = $request->marketer_id;
        }
        if ($request->campaign_id) {
            $data_where['campaign_id'] = $request->campaign_id;
        }

        $startDate = Date('Y-m-d');
        $endDate = Date('Y-m-d');
        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = Date('Y-m-d', strtotime($date_arr[0]));
            $endDate = Date('Y-m-d', strtotime($date_arr[1]));
        }
        $query = AdResult::where('date', '>=', $startDate);
        $query->where('date', '<=', $endDate);

        if (count($data_where) >= 1) {
            $ads = Ad::where($data_where)->pluck('_id')->toArray();
            $query->whereIn('ad_id', $ads);
        }

        $results = $query->get();

        // DB::connection('mongodb')->getQueryLog();
        $report = $total = [];
        if ($results) {
            $report = $this->prepare_report($results);
        }

        $data = $data_where;
        $data['report'] = $report;
        return $data;
    }

    public function exportReport()
    {

    }

    private function prepare_report($results)
    {
        // Conversion rate from 1 USD to VND
        // 2018-04-18 LamVT [HEL_9] Add more setting for VND/USD conversion
        $config     = Config::getByKeys(['USD_VND', 'USD_THB']);
        $rate       = $config['USD_VND'];
        $usd_thb    = $config['USD_THB'];
        // end 2018-04-18 LamVT [HEL_9] Add more setting for VND/USD conversion

        $request = request();
        $source_name = $team_name = $campaign_name = $marketer_name = 'All';
        if($request->source_id){
            $source = Source::find($request->source_id);
            $source_name = $source ? $source->name : 'All';
        }
        if($request->team_id){
            $team = Team::find($request->team_id);
            $team_name = $team ? $team->name : 'All';
        }
        if($request->marketer_id){
            $marketer = User::find($request->marketer_id);
            $marketer_name = $marketer ? $marketer->username : 'All';
        }
        if($request->campaign_id){
            $campaign = Campaign::find($request->campaign_id);
            $campaign_name = $campaign ? $campaign->name : 'All';
        }

        $report = [
            'total' => (object)[
                'source' => $source_name,
                'team' => $team_name,
                'marketer' => $marketer_name,
                'campaign' => $campaign_name,
                'subcampaign' => 'All',
                'ad' => 'All',
                'c1' => 0,
                'c2' => 0,
                'c3' => 0,
                'c3b' => 0,
                'spent' => 0,
                'l1' => 0,
                'l3' => 0,
                'l8' => 0,
                'revenue' => 0,
            ]
        ];

        foreach ($results as $item) {
            $report['total']->c1 += $item->c1 ? $item->c1 : 0;
            $report['total']->c2 += $item->c2 ? $item->c2 : 0;
            $report['total']->c3 += $item->c3 ? $item->c3 : 0;
            $report['total']->c3b += $item->c3b ? $item->c3b : 0;
            $report['total']->spent += $item->spent ? $item->spent : 0;
            $report['total']->l1 += $item->l1 ? $item->l1 : 0;
            $report['total']->l3 += $item->l3 ? $item->l3 : 0;
            $report['total']->l8 += $item->l8 ? $item->l8 : 0;
            $report['total']->revenue += $item->revenue ? $item->revenue : 0;

            if (!isset($report[$item->ad_id])) {
                $ad = Ad::find($item->ad_id);
                // TO DO unknown
                $source_name = '(unknown)';
                // 2018-04-13 LamVT update show team name in report
                $team_name = '(unknown)';
                // end 2018-04-13 LamVT update show team name in report
                $marketer_name = '(unknown)';
                $campaign_name = '(unknown)';
                $subcampaign_name = '(unknown)';
                $ad_name = '(unknown)';
                if($ad){
                    $source_name = $ad->source_name;
                    // 2018-04-13 LamVT update show team name in report
                    $team_name = $ad->team_name;
                    // end 2018-04-13 LamVT update show team name in report
                    $marketer_name = $ad->creator_name;
                    $campaign_name = $ad->campaign_name;
                    $subcampaign_name = $ad->subcampaign_name;
                    $ad_name = $ad->name;
                }
                $report[$item->ad_id] = (object)[
                    'source' => $source_name,
                    'team' => $team_name,
                    'marketer' => $marketer_name,
                    'campaign' => $campaign_name,
                    'subcampaign' => $subcampaign_name,
                    'ad' => $ad_name,
                    'c1' => $item->c1 ? $item->c1 : 0,
                    'c2' => $item->c2 ? $item->c2 : 0,
                    'c3' => $item->c3 ? $item->c3 : 0,
                    'c3b' => $item->c3b ? $item->c3b : 0,
                    'spent' => $item->spent ? $item->spent : 0,
                    'l1' => $item->l1 ? $item->l1 : 0,
                    'l3' => $item->l3 ? $item->l3 : 0,
                    'l8' => $item->l8 ? $item->l8 : 0,
                    'revenue' => $item->revenue ? $item->revenue : 0,
                ];

            } else {
                $report[$item->ad_id]->c1 += $item->c1 ? $item->c1 : 0;
                $report[$item->ad_id]->c2 += $item->c2 ? $item->c2 : 0;
                $report[$item->ad_id]->c3 += $item->c3 ? $item->c3 : 0;
                $report[$item->ad_id]->c3b += $item->c3b ? $item->c3b : 0;
                $report[$item->ad_id]->spent += $item->spent ? $item->spent : 0;
                $report[$item->ad_id]->l1 += $item->l1 ? $item->l1 : 0;
                $report[$item->ad_id]->l3 += $item->l3 ? $item->l3 : 0;
                $report[$item->ad_id]->l8 += $item->l8 ? $item->l8 : 0;
                $report[$item->ad_id]->revenue += $item->revenue ? $item->revenue : 0;
            }

        }

        foreach ($report as $key => $item) {
            $item->c1_cost = $item->c1 ? round($item->spent * $rate / $item->c1, 2) : '0';
            $item->c2_cost = $item->c2 ? round($item->spent * $rate / $item->c2, 2) : '0';
            $item->c2_c1 = $item->c1 ? round($item->c2 / $item->c1, 4) * 100 : '0';
            $item->c3_cost = $item->c3 ? round($item->spent * $rate / $item->c3, 2) : '0';
            $item->c3b_cost = $item->c3b ? round($item->spent * $rate / $item->c3b, 2) : '0';
            $item->c3_c2 = $item->c2 ? round($item->c3 / $item->c2, 4) * 100 : '0';
            $item->l3_l1 = $item->l1 ? round($item->l3 / $item->l1, 4) * 100 : '0';
            $item->l8_l1 = $item->l1 ? round($item->l8 / $item->l1, 4) * 100 : '0';
            $item->me_re = $item->revenue ? round($item->spent * $usd_thb / $item->revenue, 4) * 100 : '0';
            $report[$key] = $item;
        }

        return $report;
    }

}
