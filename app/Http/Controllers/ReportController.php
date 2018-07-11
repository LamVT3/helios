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
            'subcampaigns'
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
        if ($request->subcampaign_id) {
            $data_where['subcampaign_id'] = $request->subcampaign_id;
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

    private function prepareYear() {
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
                $data['days'] = array('','N.o Days');
                $data['budget'] = array('BUDGET');
                $data['me_re'] = array('Actual', 'ME/RE'); $data['spent'] = array('','ME');
                $data['revenue'] = array('','RE'); $data['c3b_cost'] = array('','C3B');
                $data['c3bg_cost'] = array('','C3BG'); $data['l1_cost'] = array('','L1');
                $data['l3_cost'] = array('','L3'); $data['l6_cost'] = array('','L6'); $data['l8_cost'] = array('','L8');

                $data['quantity'] = array('QUANTITY');
                $data['c3b'] = array('Actual','C3B'); $data['c3bg'] = array('','C3BG'); $data['l1'] = array('','L1');
                $data['l3'] = array('','L3'); $data['l6'] = array('','L6'); $data['l8'] = array('','L8');

                $data['quality'] = array('QUALITY');
                $data['l3_c3b'] = array('Actual','L3/C3B'); $data['l3_c3bg'] = array('','L3/C3BG');
                $data['l3_l1'] = array('','L3/L1'); $data['l1_c3bg'] = array('','L1/C3BG');
                $data['c3bg_c3b'] = array('','C3BG/C3B'); $data['return_ratio'] = array('','Return Ratio');
                $data['duplicate_ratio'] = array('','Duplicate Ratio');
                $data['l6_l3'] = array('','L6/L3'); $data['l8_l6'] = array('','L8/L6');

                foreach ($report as $key => $item) {
                    if ($key == 'config') continue;
                    array_push($data['weeks'], $key);
                    array_push($data['days'], $item->range);
                    array_push($data['me_re'], ($item->revenue != 0) ? round($item->spent * $usd_thb / $item->revenue,4)*100 : 0);
                    array_push($data['spent'], $item->spent); array_push($data['revenue'], $item->revenue);
                    array_push($data['c3b_cost'], ($item->c3b != 0) ? round($item->spent * $usd_vnd / $item->c3b) : 0);
                    array_push($data['c3bg_cost'], ($item->c3bg != 0) ? round($item->spent * $usd_vnd / $item->c3bg) : 0);
                    array_push($data['l1_cost'], ($item->l1 != 0) ? round($item->spent * $usd_vnd / $item->l1) : 0);
                    array_push($data['l3_cost'], ($item->l3 != 0) ? round($item->spent * $usd_vnd / $item->l3) : 0);
                    array_push($data['l6_cost'], ($item->l6 != 0) ? round($item->spent * $usd_vnd / $item->l6) : 0);
                    array_push($data['l8_cost'], ($item->l8 != 0) ? round($item->spent * $usd_vnd / $item->l8) : 0);
                    array_push($data['c3b'], $item->c3b); array_push($data['c3bg'], $item->c3bg);
                    array_push($data['l1'], $item->l1); array_push($data['l3'], $item->l3);
                    array_push($data['l6'], $item->l6); array_push($data['l8'], $item->l8);
                    array_push($data['l3_c3b'], ($item->c3b != 0) ? round($item->l3 / $item->c3b,4)*100 : 0);
                    array_push($data['l3_c3bg'], ($item->c3bg != 0) ? round($item->l3 / $item->c3bg,4)*100 : 0);
                    array_push($data['l3_l1'], ($item->l1 != 0) ? round($item->l3 / $item->l1,4)*100 : 0);
                    array_push($data['l1_c3bg'], ($item->c3bg != 0) ? round($item->l1 / $item->c3bg,4)*100 : 0);
                    array_push($data['c3bg_c3b'], ($item->c3b != 0) ? round($item->c3bg / $item->c3b,4)*100 : 0);
                    array_push($data['return_ratio'], 0); array_push($data['duplicate_ratio'], 0);
                    array_push($data['l6_l3'], ($item->l3 != 0) ? round($item->l6 / $item->l3,4)*100 : 0);
                    array_push($data['l8_l6'], ($item->l6 != 0) ? round($item->l8 / $item->l6,4)*100 : 0);
                }
                $sheet->fromArray($data, NULL, 'A1', FALSE, FALSE);

                $headings1 = array('MONTHLY MARKETING REPORT');
                $headings2 = array('Budget :', '', 'Target L1 :', '', 'L3/C3B :', '');
                $headings3 = array('Spent :', $report['total']->spent, 'Produced :', $report['total']->l1,
                    'Actual :', ($report['total']->c3bg != 0) ? round($report['total']->l3 / $report['total']->c3bg,4)*100 : 0);
                $sheet->prependRow(1, $headings1);
                $sheet->prependRow(2, $headings2);
                $sheet->prependRow(3, $headings3);

                $sheet->mergeCells('A1:J1', function ($cells) {
                    $cells->setBackground('#fafafa');
                    $cells->setFontColor('#ED8515');
                    $cells->setFontSize('xx-large');
                    $cells->setFontWeight('bold');
                });

            });
        })->export('xls');
    }

}
