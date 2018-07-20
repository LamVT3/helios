<?php

namespace App\Http\Controllers;

use App\Ad;
use App\AdResult;
use App\Campaign;
use App\Channel;
use App\Config;
use App\Contact;
use App\LandingPage;
use App\Source;
use App\Team;
use App\User;
use App\Subcampaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

        $sources        = Source::all();
        $teams          = Team::all();
        $marketers      = User::all();
        $campaigns      = Campaign::where('is_active', 1)->get();
        $page_size      = Config::getByKey('PAGE_SIZE');
        $subcampaigns   = Subcampaign::where('is_active', 1)->get();
        $landing_page   = LandingPage::where('is_active', 1)->get();
        $unit = config('constants.UNIT_USD');

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
            'campaigns',
            'page_size',
            'subcampaigns',
            'landing_page',
            'unit'
        ));
    }

    public function getReport()
    {
        $request = request();
        $data = $this->getReportData();

        $data['sources'] = Source::all();
        $data['teams'] = Team::all();
        $data['marketers'] = User::all();
        $data['campaigns'] = Campaign::where('is_active', 1)->get();
        $data['source'] = \request('source', 'All');
        $data['team'] = \request('team', 'All');
        $data['marketer'] = \request('marketer', 'All');
        $data['campaign'] = \request('campaign', 'All');
        $data['unit'] = config('constants.UNIT_USD');
        if($request->unit){
            $data['unit'] = $request->unit;
        }

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
        if ($request->subcampaign_id) {
            $data_where['subcampaign_id'] = $request->subcampaign_id;
        }
        if ($request->landing_page) {
            $data_where['landing_page_id']     = $request->landing_page;
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

        if (count($data_where) > 0) {
            $ads = Ad::where($data_where)->pluck('_id')->toArray();
            $query->whereIn('ad_id', $ads);
        }

        $results = $query->get();

        // DB::connection('mongodb')->getQueryLog();
        $report = $total = [];
        if ($results) {
            if(!$request->mode || $request->mode == 'TOA'){
                $report = $this->prepare_report($results);
            }else{
                $report = $this->prepare_report_tot($results);
            }
        }

        $data = $data_where;
        $data['report'] = $report;
        return $data;
    }

    private function prepare_report($results)
    {
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
                'source' => 'All',
                'team' => 'All',
                'marketer' => 'All',
                'campaign' => 'All',
                'subcampaign' => 'All',
                'ad' => 'All',
                'c1' => 0,
                'c2' => 0,
                'c3' => 0,
                'c3b' => 0,
                'c3bg' => 0,
                'spent' => 0,
                'l1' => 0,
                'l3' => 0,
                'l8' => 0,
                'revenue' => 0,
            ]
        ];

        foreach ($results as $item) {

            if(!isset($item->ad_id)){
                continue;
            }

            $spent      = isset($item->spent)      ? $this->convert_spent($item->spent)        : 0;
            $revenue    = isset($item->revenue)    ? $this->convert_revenue($item->revenue)    : 0;

            $report['total']->c1        += $item->c1        ? $item->c1        : 0;
            $report['total']->c2        += $item->c2        ? $item->c2        : 0;
            $report['total']->c3        += $item->c3a + $item->c3b + $item->c3bg;
            $report['total']->c3b       += $item->c3b + $item->c3bg;
            $report['total']->c3bg      += $item->c3bg      ? $item->c3bg      : 0;
            $report['total']->spent     += $spent;
            $report['total']->l1        += $item->l1        ? $item->l1        : 0;
            $report['total']->l3        += $item->l3        ? $item->l3        : 0;
            $report['total']->l8        += $item->l8        ? $item->l8        : 0;
            $report['total']->revenue   += $revenue;

            if (!isset($report[$item->ad_id])) {
                $ad = Ad::find($item->ad_id);
                // TO DO unknown
                $source_name        = '(unknown)';
                $team_name          = '(unknown)';
                $marketer_name      = '(unknown)';
                $campaign_name      = '(unknown)';
                $subcampaign_name   = '(unknown)';
                $ad_name            = '(unknown)';
                if($ad){
                    $source_name        = $ad->source_name;
                    $team_name          = $ad->team_name;
                    $marketer_name      = $ad->creator_name;
                    $campaign_name      = $ad->campaign_name;
                    $subcampaign_name   = $ad->subcampaign_name;
                    $ad_name            = $ad->name;
                }
                $report[$item->ad_id] = (object)[
                    'source'    => $source_name,
                    'team'      => $team_name,
                    'marketer'  => $marketer_name,
                    'campaign'  => $campaign_name,
                    'subcampaign'   => $subcampaign_name,
                    'ad'        => $ad_name,
                    'c1'        => $item->c1       ? $item->c1         : 0,
                    'c2'        => $item->c2       ? $item->c2         : 0,
                    'c3'        => $item->c3a + $item->c3b + $item->c3bg,
                    'c3b'       => $item->c3b + $item->c3bg,
                    'c3bg'      => $item->c3bg     ? $item->c3bg       : 0,
                    'spent'     => $spent,
                    'l1'        => $item->l1       ? $item->l1         : 0,
                    'l3'        => $item->l3       ? $item->l3         : 0,
                    'l8'        => $item->l8       ? $item->l8         : 0,
                    'revenue'   => $revenue,
                ];
            } else {
                $report[$item->ad_id]->c1       += isset($item->c1)        ? $item->c1         : 0;
                $report[$item->ad_id]->c2       += isset($item->c2)        ? $item->c2         : 0;
                $report[$item->ad_id]->c3       += $item->c3a + $item->c3b + $item->c3bg;
                $report[$item->ad_id]->c3b      += $item->c3b + $item->c3bg;
                $report[$item->ad_id]->c3bg     += isset($item->c3bg)      ? $item->c3bg       : 0;
                $report[$item->ad_id]->spent    += $spent;
                $report[$item->ad_id]->l1       += isset($item->l1)        ? $item->l1         : 0;
                $report[$item->ad_id]->l3       += isset($item->l3)        ? $item->l3         : 0;
                $report[$item->ad_id]->l8       += isset($item->l8)        ? $item->l8         : 0;
                $report[$item->ad_id]->revenue  += $revenue;
            }
        }
        foreach ($report as $key => $item) {
            $item->c1_cost  = $item->c1     ? round($item->spent / $item->c1, 2)    : '0';
            $item->c2_cost  = $item->c2     ? round($item->spent / $item->c2, 2)    : '0';
            $item->c2_c1    = $item->c1     ? round($item->c2 / $item->c1, 4) * 100         : '0';
            $item->c3_cost  = $item->c3     ? round($item->spent / $item->c3, 2)    : '0';
            $item->c3b_cost = $item->c3b    ? round($item->spent / $item->c3b, 2)   : '0';
            $item->c3bg_cost = $item->c3bg  ? round($item->spent / $item->c3bg, 2)   : '0';
            $item->c3_c2    = $item->c2     ? round($item->c3 / $item->c2, 4) * 100         : '0';
            $item->l3_l1    = $item->l1     ? round($item->l3 / $item->l1, 4) * 100         : '0';
            $item->l8_l1    = $item->l1     ? round($item->l8 / $item->l1, 4) * 100         : '0';
            $item->me_re    = $item->revenue ? round($item->spent / $item->revenue, 4) * 100 : '0';
            $report[$key]   = $item;
        }

        return $report;
    }

    private function prepare_report_tot($results)
    {
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
                'source' => 'All',
                'team' => 'All',
                'marketer' => 'All',
                'campaign' => 'All',
                'subcampaign' => 'All',
                'ad' => 'All',
                'c1' => 0,
                'c2' => 0,
                'c3' => 0,
                'c3b' => 0,
                'c3bg' => 0,
                'spent' => 0,
                'l1' => 0,
                'l3' => 0,
                'l8' => 0,
                'revenue' => 0,
            ]
        ];

        $data_tot = $this->getReportDataTOT();

        foreach ($results as $item) {

            if(!isset($item->ad_id)){
                continue;
            }

            $spent      = isset($item->spent)      ? $this->convert_spent($item->spent)        : 0;
            $revenue    = isset($item->revenue)    ? $this->convert_revenue($item->revenue)    : 0;

            $report['total']->c1        += $item->c1        ? $item->c1        : 0;
            $report['total']->c2        += $item->c2        ? $item->c2        : 0;
            $report['total']->c3        += $item->c3a + $item->c3b + $item->c3bg;
            $report['total']->c3b       += $item->c3b + $item->c3bg;
            $report['total']->c3bg      += $item->c3bg      ? $item->c3bg      : 0;
            $report['total']->spent     += $spent;
            $report['total']->l1        += @$data_tot[$item->ad_id]->l1 ? $data_tot[$item->ad_id]->l1  : 0;
            $report['total']->l3        += @$data_tot[$item->ad_id]->l3 ? $data_tot[$item->ad_id]->l3  : 0;
            $report['total']->l8        += @$data_tot[$item->ad_id]->l8 ? $data_tot[$item->ad_id]->l8  : 0;
            $report['total']->revenue   += $revenue;

            if (!isset($report[$item->ad_id])) {
                $ad = Ad::find($item->ad_id);
                // TO DO unknown
                $source_name        = '(unknown)';
                $team_name          = '(unknown)';
                $marketer_name      = '(unknown)';
                $campaign_name      = '(unknown)';
                $subcampaign_name   = '(unknown)';
                $ad_name            = '(unknown)';
                if($ad){
                    $source_name        = $ad->source_name;
                    $team_name          = $ad->team_name;
                    $marketer_name      = $ad->creator_name;
                    $campaign_name      = $ad->campaign_name;
                    $subcampaign_name   = $ad->subcampaign_name;
                    $ad_name            = $ad->name;
                }
                $report[$item->ad_id] = (object)[
                    'source'    => $source_name,
                    'team'      => $team_name,
                    'marketer'  => $marketer_name,
                    'campaign'  => $campaign_name,
                    'subcampaign'   => $subcampaign_name,
                    'ad'        => $ad_name,
                    'c1'        => $item->c1       ? $item->c1         : 0,
                    'c2'        => $item->c2       ? $item->c2         : 0,
                    'c3'        => $item->c3a + $item->c3b + $item->c3bg,
                    'c3b'       => $item->c3b + $item->c3bg,
                    'c3bg'      => $item->c3bg     ? $item->c3bg       : 0,
                    'spent'     => $spent,
                    'l1'        => @$data_tot[$item->ad_id]->l1 ? $data_tot[$item->ad_id]->l1  : 0,
                    'l3'        => @$data_tot[$item->ad_id]->l3 ? $data_tot[$item->ad_id]->l3  : 0,
                    'l8'        => @$data_tot[$item->ad_id]->l8 ? $data_tot[$item->ad_id]->l8  : 0,
                    'revenue'   => $revenue,
                ];
            } else {
                $report[$item->ad_id]->c1       += isset($item->c1)        ? $item->c1         : 0;
                $report[$item->ad_id]->c2       += isset($item->c2)        ? $item->c2         : 0;
                $report[$item->ad_id]->c3       += $item->c3a + $item->c3b + $item->c3bg;
                $report[$item->ad_id]->c3b      += $item->c3b + $item->c3bg;
                $report[$item->ad_id]->c3bg     += isset($item->c3bg)      ? $item->c3bg       : 0;
                $report[$item->ad_id]->spent    += $spent;
                $report[$item->ad_id]->l1       += @$data_tot[$item->ad_id]->l1  ? $data_tot[$item->ad_id]->l1  : 0;
                $report[$item->ad_id]->l3       += @$data_tot[$item->ad_id]->l3  ? $data_tot[$item->ad_id]->l3  : 0;
                $report[$item->ad_id]->l8       += @$data_tot[$item->ad_id]->l8  ? $data_tot[$item->ad_id]->l8  : 0;
                $report[$item->ad_id]->revenue  += $revenue;
            }
        }
        foreach ($report as $key => $item) {
            $item->c1_cost  = $item->c1     ? round($item->spent / $item->c1, 2)    : '0';
            $item->c2_cost  = $item->c2     ? round($item->spent / $item->c2, 2)    : '0';
            $item->c2_c1    = $item->c1     ? round($item->c2 / $item->c1, 4) * 100         : '0';
            $item->c3_cost  = $item->c3     ? round($item->spent / $item->c3, 2)    : '0';
            $item->c3b_cost = $item->c3b    ? round($item->spent / $item->c3b, 2)   : '0';
            $item->c3bg_cost = $item->c3bg  ? round($item->spent / $item->c3bg, 2)   : '0';
            $item->c3_c2    = $item->c2     ? round($item->c3 / $item->c2, 4) * 100         : '0';
            $item->l3_l1    = $item->l1     ? round($item->l3 / $item->l1, 4) * 100         : '0';
            $item->l8_l1    = $item->l1     ? round($item->l8 / $item->l1, 4) * 100         : '0';
            $item->me_re    = $item->revenue ? round($item->spent / $item->revenue, 4) * 100 : '0';
            $report[$key]   = $item;
        }

        return $report;
    }

    private function convert_revenue($revenue){
        $request    = request();

        $config     = Config::getByKeys(['USD_VND', 'USD_THB', 'THB_VND']);
        $usd_vnd    = $config['USD_VND'];
        $usd_tbh    = $config['USD_THB'];
        $thb_vnd    = $config['THB_VND'];

        if($request->unit == config('constants.UNIT_USD')){
            $revenue    = $usd_tbh ? $revenue / $usd_tbh : 0;
        }elseif ($request->unit == config('constants.UNIT_VND')){
            $revenue    = $revenue * $thb_vnd;
        }

        return round($revenue, 2);

    }



    private function convert_spent($spent){
        $request    = request();

        $config     = Config::getByKeys(['USD_VND', 'USD_THB', 'THB_VND']);
        $usd_vnd    = $config['USD_VND'];
        $usd_tbh    = $config['USD_THB'];

        if($request->unit == config('constants.UNIT_VND')){
            $spent    = $spent * $usd_vnd;
        }elseif ($request->unit == config('constants.UNIT_BAHT')){
            $spent    = $spent * $usd_tbh;
        }

        return round($spent, 2);

    }

    private function prepareReport($resultsArr, $rangeArr) {
        $config = Config::getByKeys(['USD_VND', 'USD_THB']);
        $report = array('config' => $config);

        foreach ($resultsArr as $key => $value) {
            $report[$key] = (object)[
                'c1' => 0,
                'c2' => 0,
                'c3' => 0,
                'c3b' => 0,
                'c3bg' => 0,
                'spent' => 0,
                'l1' => 0,
                'l3' => 0,
                'l6' => 0,
                'l8' => 0,
                'revenue' => 0,
                'range' => "---",
            ];

            if ($rangeArr != null) {
                $report[$key]->range = isset($rangeArr[$key]) ? $rangeArr[$key] : NULL;
            }

            if(isset($value) && $value != null) {
                foreach ($value as $item) {
                    $report[$key]->c1 += isset($item->c1) ? $item->c1 : 0;
                    $report[$key]->c2 += isset($item->c2) ? $item->c2 : 0;
                    $report[$key]->c3 += isset($item->c3) ? $item->c3 : 0;
                    $report[$key]->c3b += isset($item->c3b) ? $item->c3b : 0;
                    $report[$key]->c3bg += isset($item->c3bg) ? $item->c3bg : 0;
                    $report[$key]->spent += isset($item->spent) ? $item->spent : 0;
                    $report[$key]->l1 += isset($item->l1) ? $item->l1 : 0;
                    $report[$key]->l3 += isset($item->l3) ? $item->l3 : 0;
                    $report[$key]->l6 += isset($item->l6) ? $item->l6 : 0;
                    $report[$key]->l8 += isset($item->l8) ? $item->l8 : 0;
                    $report[$key]->revenue += isset($item->revenue) ? $item->revenue : 0;
                }
            }
        }

        return $report;
    }

    private function prepareMonthly() {
        $month       = request('month');

        if(strlen((string)$month) != 2) {
            $month = '0'.$month;
        }

        $startRange  = request('startRange');
        $endRange    = request('endRange');

        $startDate   = date('Y-' . $month .'-01');
        $year        = date('Y'); /* nam hien tai*/
        $days        = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $endDate    = date('Y-' . $month .'-'.$days);

        $startDayRange = explode(" ", $startRange)[2];
        $endDayRange = explode(" ", $endRange)[2];

        /*Mon 1 = 8 - 1 = 7
        Sun 7 = 8 - 7 = 1*/
        $daysInFirstWeek = 8 - date('N',strtotime($startDate));
        $rangeTotal = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $results = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $endDate = date('Y-' . $month .'-0'.$daysInFirstWeek);
        $rangeW1 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultW1 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $startDate   = date('Y-m-d', strtotime($endDate. ' + 1 days'));
        $endDate    = date('Y-m-d', strtotime($startDate. ' + 6 days'));
        $rangeW2 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultW2 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $startDate = date('Y-m-d', strtotime($endDate. ' + 1 days'));
        $endDate  = date('Y-m-d', strtotime($startDate. ' + 6 days'));
        $rangeW3   = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultW3  = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $startDate   = date('Y-m-d', strtotime($endDate. ' + 1 days'));
        $endDate    = date('Y-m-d', strtotime($startDate. ' + 6 days'));
        $rangeW4 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultW4 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $resultW5 = null;
        $rangeW5 = null;
        $remainDays = $days - date('d',strtotime($endDate));
        if($remainDays > 0){
            $startDate   = date('Y-m-d', strtotime($endDate. ' + 1 days'));
            if ($remainDays > 7) {
                $remainDays -= 7;
                $endDate    = date('Y-m-d', strtotime($startDate. ' + 6 days'));
            } else {
                $endDate    = date('Y-m-d', strtotime($startDate. ' + '.($remainDays-1).' days'));
                $remainDays = 0;
            }
            $rangeW5 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
            $resultW5 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
                return $collection->aggregate([
                    ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
                ]);
            });
        }

        $resultW6 = null;
        $rangeW6 = null;
        if($remainDays > 0){
            $startDate   = date('Y-m-d', strtotime($endDate. ' + 1 days'));
            $endDate    = date('Y-m-d', strtotime($startDate. ' + '.($remainDays-1).' days'));
            $rangeW6 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
            $resultW6 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
                return $collection->aggregate([
                    ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
                ]);
            });
        }

        $startDate   = date('Y-' . $month .'-'. $startDayRange);
        $endDate    = date('Y-' . $month .'-'. $endDayRange);
        $rangeDate = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultRange = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $rangeArr = array(
            'week1' => $rangeW1,
            'week2' => $rangeW2,
            'week3' => $rangeW3,
            'week4' => $rangeW4,
            'week5' => $rangeW5,
            'week6' => $rangeW6,
            'total' => $rangeTotal,
            'rangeDate' => $rangeDate);

        $resultsArr = array(
            'week1' => $resultW1,
            'week2' => $resultW2,
            'week3' => $resultW3,
            'week4' => $resultW4,
            'week5' => $resultW5,
            'week6' => $resultW6,
            'total' => $results,
            'rangeDate' => $resultRange);

        $data['report'] = $this->prepareReport($resultsArr, $rangeArr);

        return $data;
    }

    public function getReportMonthly() {
        $data = $this->prepareMonthly();
        return view('pages.table_report_monthly', $data);
    }

    public function prepareYear() {
        $month       = request('month');
        $year        = request('year');
        $noLastMonth = request('noLastMonth');

        if($month < 10){
            $month = '0'.$month;
        }
        $startDate = date($year.'-' . $month .'-01');
        $days      = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $endDate   = date($year.'-' . $month .'-'.$days);

        $resultsArr = array();
        $i = 0;
        do {
            $result = AdResult::raw(function ($collection) use ($startDate, $endDate) {
                /*if($month == "04") {
                    var_dump($startDate);
                    var_dump($endDate);
                    var_dump($collection->aggregate([
                        ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
                    ]));
                }*/
                return $collection->aggregate([
                    ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
                ]);
            });

            $resultsArr[date('Y',strtotime($startDate)).' - '.date('m',strtotime($startDate))] = $result;
            if ($month == "01") {
                $month = 12;
                $year -= 1;
            } else {
                $month -= 1;
            }

            if($month < 10){
                $month = '0'.$month;
            }
            $startDate = date($year.'-' . $month .'-01');
            $days      = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $endDate   = date($year.'-' . $month .'-'.$days);

            $i++;
        } while ($i < $noLastMonth);

        /*var_dump($resultsArr['2018 - 04']);
        die;*/

        $resultsArr = array_reverse($this->prepareReport($resultsArr, null), true);

        $data['reportY'] = $resultsArr;
        return $data;
    }

    public function getReportYear() {
        $data = $this->prepareYear();
        return view('pages.table_report_year', $data);
    }

    public function getReportStatistic() {
        $data = $this->prepareYear();
        return view('pages.table_report_statistic', $data);
    }

    public function exportMonthly() {
        $month     = request('month');
        $file_name = 'Report_Month_' . $month;
        Excel::create($file_name, function ($excel) {
            $excel->sheet('monthly_report', function ($sheet) {
                $data = $this->prepareMonthly();
                $report = $data['report'];
                $usd_vnd = $report['config']['USD_VND'];
                $usd_thb = $report['config']['USD_THB'];
                $data = array();
                $data['weeks'] = array('','Weeks');
                $data['days'] = array('','Num of days');
                $data['budget'] = array('BUDGET');
                $data['me_re'] = array('Actual', 'ME/RE (%)'); $data['spent'] = array('','ME (USD)');
                $data['revenue'] = array('','RE (THB)'); $data['c3b_cost'] = array('','C3B');
                $data['c3bg_cost'] = array('','C3BG'); $data['l1_cost'] = array('','L1');
                $data['l3_cost'] = array('','L3'); $data['l6_cost'] = array('','L6'); $data['l8_cost'] = array('','L8');

                $data['quantity'] = array('QUANTITY');
                $data['c3b'] = array('Actual','C3B'); $data['c3bg'] = array('','C3BG'); $data['l1'] = array('','L1');
                $data['l3'] = array('','L3'); $data['l6'] = array('','L6'); $data['l8'] = array('','L8');

                $data['quality'] = array('QUALITY');
                $data['l3_c3b'] = array('Actual','L3/C3B %'); $data['l3_c3bg'] = array('','L3/C3BG %');
                $data['l3_l1'] = array('','L3/L1 %'); $data['l1_c3bg'] = array('','L1/C3BG %');
                $data['c3bg_c3b'] = array('','C3BG/C3B %'); $data['return_ratio'] = array('','Return Ratio');
                $data['duplicate_ratio'] = array('','Duplicate Ratio');
                $data['l6_l3'] = array('','L6/L3 %'); $data['l8_l6'] = array('','L8/L6 %');

                unset($report['config']);
                foreach ($report as $key => $item) {
                    if($item->range != NULL) {
                        array_push($data['weeks'], $key);
                        array_push($data['days'], $item->range);
                        array_push($data['me_re'], ($item->revenue != 0 && ($item->spent * $usd_thb / $item->revenue) != 0) ? round($item->spent * $usd_thb / $item->revenue, 4) * 100 : '0');
                        array_push($data['spent'], ($item->spent != 0) ? $item->spent : '0');
                        array_push($data['revenue'], ($item->revenue != 0) ? $item->revenue : '0');
                        array_push($data['c3b_cost'], ($item->c3b != 0 && ($item->spent * $usd_vnd / $item->c3b != 0)) ? round($item->spent * $usd_vnd / $item->c3b) : '0');
                        array_push($data['c3bg_cost'], ($item->c3bg != 0 && ($item->spent * $usd_vnd / $item->c3bg != 0)) ? round($item->spent * $usd_vnd / $item->c3bg) : '0');
                        array_push($data['l1_cost'], ($item->l1 != 0 && ($item->spent * $usd_vnd / $item->l1 != 0)) ? round($item->spent * $usd_vnd / $item->l1) : '0');
                        array_push($data['l3_cost'], ($item->l3 != 0 && ($item->spent * $usd_vnd / $item->l3 != 0)) ? round($item->spent * $usd_vnd / $item->l3) : '0');
                        array_push($data['l6_cost'], ($item->l6 != 0 && ($item->spent * $usd_vnd / $item->l6 != 0)) ? round($item->spent * $usd_vnd / $item->l6) : '0');
                        array_push($data['l8_cost'], ($item->l8 != 0 && ($item->spent * $usd_vnd / $item->l8 != 0)) ? round($item->spent * $usd_vnd / $item->l8) : '0');
                        array_push($data['c3b'], ($item->c3b != 0) ? $item->c3b : '0');
                        array_push($data['c3bg'], ($item->c3bg != 0) ? $item->c3bg : '0');
                        array_push($data['l1'], ($item->l1 != 0) ? $item->l1 : '0');
                        array_push($data['l3'], ($item->l3 != 0) ? $item->l3 : '0');
                        array_push($data['l6'], ($item->l6 != 0) ? $item->l6 : '0');
                        array_push($data['l8'], ($item->l8 != 0) ? $item->l8 : '0');
                        array_push($data['l3_c3b'], ($item->c3b != 0 && ($item->l3 / $item->c3b != 0)) ? round($item->l3 / $item->c3b,4) * 100 : '0');
                        array_push($data['l3_c3bg'], ($item->c3bg != 0 && ($item->l3 / $item->c3bg != 0)) ? round($item->l3 / $item->c3bg , 4) * 100 : '0');
                        array_push($data['l3_l1'], ($item->l1 != 0 && ($item->l3 / $item->l1 != 0)) ? round($item->l3 / $item->l1, 4 ) * 100 : '0');
                        array_push($data['l1_c3bg'], ($item->c3bg != 0 && ($item->l1 / $item->c3bg != 0)) ? round($item->l1 / $item->c3bg, 4) * 100 : '0');
                        array_push($data['c3bg_c3b'], ($item->c3b != 0 && ($item->c3bg / $item->c3b != 0)) ? round($item->c3bg / $item->c3b, 4) * 100 : '0');
                        array_push($data['return_ratio'], '0');
                        array_push($data['duplicate_ratio'], '0');
                        array_push($data['l6_l3'], ($item->l3 != 0 && ($item->l6 / $item->l3 != 0)) ? round($item->l6 / $item->l3, 4) * 100 : '0');
                        array_push($data['l8_l6'], ($item->l6 != 0 && ($item->l8 / $item->l6 != 0)) ? round($item->l8 / $item->l6, 4) * 100 : '0');
                    }
                }
                $sheet->fromArray($data, NULL, 'A1', FALSE, FALSE);

                $headings1 = array('MONTHLY MARKETING REPORT');
                $headings2 = array('Budget:','', '', 'TargetL1:','', '', 'L3/C3B:','', '');
                $headings3 = array('Spent:','', $report['total']->spent." USD", 'Produced:','', $report['total']->l1,
                    'Actual:','', (($report['total']->c3bg != 0) ? round($report['total']->l3 / $report['total']->c3bg, 4) * 100 : '0')."%");
                $sheet->prependRow(1, $headings1);
                $sheet->prependRow(2, $headings2);
                $sheet->prependRow(3, $headings3);

                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:B2');
                $sheet->mergeCells('D2:E2');
                $sheet->mergeCells('G2:H2');
                $sheet->mergeCells('A3:B3');
                $sheet->mergeCells('D3:E3');
                $sheet->mergeCells('G3:H3');

                $sheet->cells('A1:J32', function ($cells) {
                    $cells->setFontSize(12);
                    $cells->setBorder('solid');
                });
                $sheet->cells('A1', function ($cells) {
                    $cells->setBackground('#fafafa');
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                    $cells->setFontSize(30);
                });
                $sheet->cells('A2:J2', function ($cells) {
                    $cells->setFontColor('#157DEC');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(20);
                });
                $sheet->cells('A3:J3', function ($cells) {
                    $cells->setFontColor('#157DEC');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(20);
                });
                $sheet->cells('C2', function ($cells) {
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('F2', function ($cells) {
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('I2', function ($cells) {
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('C3', function ($cells) {
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('F3', function ($cells) {
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('I3', function ($cells) {
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });

                $sheet->cells('A4:J4', function ($cells) {
                    $cells->setBackground('#fafafa');
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B4', function ($cells) {
                    $cells->setFontColor('#505050');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A5:J5', function ($cells) {
                    $cells->setBackground('#fafafa');
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B5', function ($cells) {
                    $cells->setFontColor('#505050');
                    $cells->setFontWeight('bold');
                });

                $sheet->cells('A6', function ($cells) {
                    $cells->setBackground('#157DEC');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A7', function ($cells) {
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B7:J7', function ($cells) {
                    $cells->setFontColor('#157DEC');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B8:J15', function ($cells) {
                    $cells->setFontColor('#505050');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B10:B15', function ($cells) {
                    $cells->setAlignment('center');
                });

                $sheet->cells('A16', function ($cells) {
                    $cells->setBackground('#157DEC');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A17', function ($cells) {
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B17:J17', function ($cells) {
                    $cells->setFontColor('#157DEC');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B18:J22', function ($cells) {
                    $cells->setFontColor('#505050');
                    $cells->setFontWeight('bold');
                });

                $sheet->cells('A23', function ($cells) {
                    $cells->setBackground('#157DEC');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A24', function ($cells) {
                    $cells->setFontColor('#ED8515');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B24:J24', function ($cells) {
                    $cells->setFontColor('#157DEC');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B25:J32', function ($cells) {
                    $cells->setFontColor('#505050');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B25:B28', function ($cells) {
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
    }

    private function getReportDataTOT(){
        $request = request();

        $startDate   = strtotime("midnight")*1000;
        $endDate     = strtotime("tomorrow")*1000;
        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr   = explode(' ', str_replace('/', '-', $date_place));
            $startDate  = strtotime($date_arr[0])*1000;
            $endDate    = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }

        $query_l1 = Contact::raw(function ($collection) use ($startDate, $endDate) {
            $start  = date('Y-m-d', $startDate/1000);
            $end    = date('Y-m-d', $endDate/1000);

            return $collection->aggregate([
                ['$match' => ['l1_time' => ['$gte' => $start, '$lte' => $end]]],
                [
                    '$group' => [
                        '_id' => '$ad_id',
                        'count' => ['$sum' => 1],
                    ]
                ]
            ]);
        });

        $query_l3 = Contact::raw(function ($collection) use ($startDate, $endDate) {
            $start  = date('Y-m-d', $startDate/1000);
            $end    = date('Y-m-d', $endDate/1000);

            return $collection->aggregate([
                ['$match' => ['l3a_time' => ['$gte' => $start, '$lte' => $end]]],
                [
                    '$group' => [
                        '_id' => '$ad_id',
                        'count' => ['$sum' => 1],
                    ]
                ]
            ]);
        });

        $query_l8 = Contact::raw(function ($collection) use ($startDate, $endDate) {
            $start  = date('Y-m-d', $startDate/1000);
            $end    = date('Y-m-d', $endDate/1000);

            return $collection->aggregate([
                ['$match' => ['l8_time' => ['$gte' => $start, '$lte' => $end]]],
                [
                    '$group' => [
                        '_id' => '$ad_id',
                        'count' => ['$sum' => 1],
                    ]
                ]
            ]);
        });

        $result = array();
        foreach ($query_l1 as $key => $item){
            @$result[$item->id]->l1 = $item->count;
        }
        foreach ($query_l3 as $key => $item){
            @$result[$item->id]->l3 = $item->count;
        }
        foreach ($query_l8 as $key => $item){
            @$result[$item->id]->l8 = $item->count;
        }

        return $result;
    }

}
