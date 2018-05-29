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

class SubReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $page_title = "Sub Report | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'sub-report-line';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Line Chart </span>";

        $sources        = Source::all();
        $teams          = Team::all();
        $marketers      = User::all();
        $campaigns      = Campaign::where('is_active', 1)->get();
        $page_size      = Config::getByKey('PAGE_SIZE');
        $subcampaigns   = Subcampaign::where('is_active', 1)->get();

        $budget     = $this->getBudget();
        $quantity   = $this->getQuantity();
        $quality    = $this->getQuality();

        return view('pages.sub-report-line', compact(
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
            'budget',
            'quantity',
            'quality'
        ));
    }

    public function getBudget($budget_month = null){
        // get start date and end date
        list($year, $month, $d, $first_day_this_month, $last_day_this_month) = $this->getDate($budget_month);

        // get Ad id
        $ad_id  = $this->getAds();

        if(count($ad_id) > 0){
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['ad_id' => ['$in' => $ad_id]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
                        'c3bg'  => ['$sum' => '$c3bg'],
                        'l1'    => ['$sum' => '$l1'],
                        'l3'    => ['$sum' => '$l3'],
                        'l6'    => ['$sum' => '$l6'],
                        'l8'    => ['$sum' => '$l8'],
                    ]
                ]
            ];
        }else{
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
                        'c3bg'  => ['$sum' => '$c3bg'],
                        'l1'    => ['$sum' => '$l1'],
                        'l3'    => ['$sum' => '$l3'],
                        'l6'    => ['$sum' => '$l6'],
                        'l8'    => ['$sum' => '$l8'],
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        $config     = Config::getByKeys(['USD_VND', 'USD_THB']);
        $usd_vnd    = $config['USD_VND'];
        $usd_thb    = $config['USD_VND'];

        $me_array   = array();
        $re_array   = array();
        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();

        $total_me   = 0;
        $total_re   = 0;


        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);
            if($item_result['me'] == 0){
                $me_array[(int)($day[2])]   = 0;
                $re_array[(int)($day[2])]   = 0;
                $c3b_array[(int)($day[2])]  = 0;
                $c3bg_array[(int)($day[2])] = 0;
                $l1_array[(int)($day[2])]   = 0;
                $l3_array[(int)($day[2])]   = 0;
                $l6_array[(int)($day[2])]   = 0;
                $l8_array[(int)($day[2])]   = 0;
            }
            else{

                $me         = $item_result['me'] * $usd_vnd;
                $re         = $item_result['re'] / $usd_thb * $usd_vnd;

                $total_me   += $me;
                $total_re   += $re;

                $me_array[(int)($day[2])]   = $me;
                $re_array[(int)($day[2])]   = $re;
                $c3b_array[(int)($day[2])]  = $item_result['c3b']   ? $me / $item_result['c3b']     : 0 ;
                $c3bg_array[(int)($day[2])] = $item_result['c3bg']  ? $me / $item_result['c3bg']    : 0 ;
                $l1_array[(int)($day[2])]   = $item_result['l1']    ? $me / $item_result['l1']      : 0 ;
                $l3_array[(int)($day[2])]   = $item_result['l3']    ? $me / $item_result['l3']      : 0 ;
                $l6_array[(int)($day[2])]   = $item_result['l6']    ? $me / $item_result['l6']      : 0 ;
                $l8_array[(int)($day[2])]   = $item_result['l8']    ? $me / $item_result['l8']      : 0 ;

            }
        }

        $me_result   = array();
        $re_result   = array();
        $c3b_result  = array();
        $c3bg_result = array();
        $l1_result   = array();
        $l3_result   = array();
        $l6_result   = array();
        $l8_result   = array();

        foreach ($array_month as $key => $timestamp) {
            $me_result[]     = [$timestamp, isset($me_array[$key])  ? $me_array[$key]   : 0];
            $re_result[]    = [$timestamp, isset($re_array[$key])   ? $re_array[$key]   : 0];
            $c3b_result[]   = [$timestamp, isset($c3b_array[$key])  ? $c3b_array[$key]  : 0];
            $c3bg_result[]  = [$timestamp, isset($c3bg_array[$key]) ? $c3bg_array[$key] : 0];
            $l1_result[]    = [$timestamp, isset($l1_array[$key])   ? $l1_array[$key]   : 0];
            $l3_result[]    = [$timestamp, isset($l3_array[$key])   ? $l3_array[$key]   : 0];
            $l6_result[]    = [$timestamp, isset($l6_array[$key])   ? $l6_array[$key]   : 0];
            $l8_result[]    = [$timestamp, isset($l8_array[$key])   ? $l8_array[$key]   : 0];
        }

        $me_re  = $total_re ? round ($total_me / $total_re, 4) * 100 : 0;

        $result = array();
        $result['me']       = json_encode($me_result);
        $result['re']       = json_encode($re_result);
        $result['c3b']      = json_encode($c3b_result);
        $result['c3bg']     = json_encode($c3bg_result);
        $result['l1']       = json_encode($l1_result);
        $result['l3']       = json_encode($l3_result);
        $result['l6']       = json_encode($l6_result);
        $result['l8']       = json_encode($l8_result);
        $result['me_re']    = $me_re;

        return $result;
    }

    public function getQuantity($quantity_month = null){
        // get start date and end date
        list($year, $month, $d, $first_day_this_month, $last_day_this_month) = $this->getDate($quantity_month);

        // get Ad id
        $ad_id  = $this->getAds();

        if(count($ad_id) > 0){
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['ad_id' => ['$in' => $ad_id]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3b'   => ['$sum' => '$c3b'],
                        'c3bg'  => ['$sum' => '$c3bg'],
                        'l1'    => ['$sum' => '$l1'],
                        'l3'    => ['$sum' => '$l3'],
                        'l6'    => ['$sum' => '$l6'],
                        'l8'    => ['$sum' => '$l8'],
                    ]
                ]
                ];
        }else{
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3b'   => ['$sum' => '$c3b'],
                        'c3bg'  => ['$sum' => '$c3bg'],
                        'l1'    => ['$sum' => '$l1'],
                        'l3'    => ['$sum' => '$l3'],
                        'l6'    => ['$sum' => '$l6'],
                        'l8'    => ['$sum' => '$l8'],
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();

        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);
            $c3b_array[(int)($day[2])]  = $item_result['c3b'];
            $c3bg_array[(int)($day[2])] = $item_result['c3bg'];
            $l1_array[(int)($day[2])]   = $item_result['l1'];
            $l3_array[(int)($day[2])]   = $item_result['l3'];
            $l6_array[(int)($day[2])]   = $item_result['l6'];
            $l8_array[(int)($day[2])]   = $item_result['l8'];

        }

        $c3b_result  = array();
        $c3bg_result = array();
        $l1_result   = array();
        $l3_result   = array();
        $l6_result   = array();
        $l8_result   = array();
        foreach ($array_month as $key => $timestamp) {
            $c3b_result[]    = [$timestamp, isset($c3b_array[$key])     ? $c3b_array[$key]  : 0];
            $c3bg_result[]   = [$timestamp, isset($c3bg_array[$key])    ? $c3bg_array[$key] : 0];
            $l1_result[]     = [$timestamp, isset($l1_array[$key])      ? $l1_array[$key]   : 0];
            $l3_result[]     = [$timestamp, isset($l3_array[$key])      ? $l3_array[$key]   : 0];
            $l6_result[]     = [$timestamp, isset($l6_array[$key])      ? $l6_array[$key]   : 0];
            $l8_result[]     = [$timestamp, isset($l8_array[$key])      ? $l8_array[$key]   : 0];
        }

        $result = array();
        $result['c3b']  = json_encode($c3b_result);
        $result['c3bg'] = json_encode($c3bg_result);
        $result['l1']   = json_encode($l1_result);
        $result['l3']   = json_encode($l3_result);
        $result['l6']   = json_encode($l6_result);
        $result['l8']   = json_encode($l8_result);

        return $result;
    }

    public function getQuality($quality_month = null){

        // get start date and end date
        list($year, $month, $d, $first_day_this_month, $last_day_this_month) = $this->getDate($quality_month);

        // get Ad id
        $ad_id  = $this->getAds();

        if(count($ad_id) > 0){
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['ad_id' => ['$in' => $ad_id]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3b'   => ['$sum' => '$c3b'],
                        'c3bg'  => ['$sum' => '$c3bg'],
                        'l1'    => ['$sum' => '$l1'],
                        'l3'    => ['$sum' => '$l3'],
                        'l6'    => ['$sum' => '$l6'],
                        'l8'    => ['$sum' => '$l8'],
                    ]
                ]
            ];
        }else{
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3b'   => ['$sum' => '$c3b'],
                        'c3bg'  => ['$sum' => '$c3bg'],
                        'l1'    => ['$sum' => '$l1'],
                        'l3'    => ['$sum' => '$l3'],
                        'l6'    => ['$sum' => '$l6'],
                        'l8'    => ['$sum' => '$l8'],
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        $l3_c3b_array   = array();
        $l3_c3bg_array  = array();
        $l3_l1_array    = array();
        $l1_c3bg_array  = array();
        $c3bg_c3b_array = array();
        $l6_l3_array    = array();
        $l8_l6_array    = array();

        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);

            $l3_c3b_array[(int)($day[2])]  = $item_result['c3b'] ?
                round($item_result['l3'] / $item_result['c3b'],2) * 100 : 0;
            $l3_c3bg_array[(int)($day[2])]  = $item_result['c3bg'] ?
                round($item_result['l3'] / $item_result['c3bg'],2) * 100 : 0;
            $l3_l1_array[(int)($day[2])]  = $item_result['l1'] ?
                round($item_result['l3'] / $item_result['l1'],2) * 100 : 0;
            $l1_c3bg_array[(int)($day[2])]  = $item_result['c3bg'] ?
                round($item_result['l1'] / $item_result['c3bg'],2) * 100 : 0;
            $c3bg_c3b_array[(int)($day[2])]  = $item_result['c3b'] ?
                round($item_result['c3bg'] / $item_result['c3b'],2) * 100 : 0;
            $l6_l3_array[(int)($day[2])]  = $item_result['l3'] ?
                round($item_result['l6'] / $item_result['l3'],2) * 100 : 0;
            $l8_l6_array[(int)($day[2])]  = $item_result['l6'] ?
                round($item_result['l8'] / $item_result['l6'],2) * 100 : 0;
        }

        $l3_c3b_result   = array();
        $l3_c3bg_result  = array();
        $l3_l1_result    = array();
        $l1_c3bg_result  = array();
        $c3bg_c3b_result = array();
        $l6_l3_result    = array();
        $l8_l6_result    = array();
        foreach ($array_month as $key => $timestamp) {
            $l3_c3b_result[]    = [$timestamp, isset($l3_c3b_array[$key])   ? $l3_c3b_array[$key]   : 0];
            $l3_c3bg_result[]   = [$timestamp, isset($l3_c3bg_array[$key])  ? $l3_c3bg_array[$key]  : 0];
            $l3_l1_result[]     = [$timestamp, isset($l3_l1_array[$key])    ? $l3_l1_array[$key]    : 0];
            $l1_c3bg_result[]   = [$timestamp, isset($l1_c3bg_array[$key])  ? $l1_c3bg_array[$key]  : 0];
            $c3bg_c3b_result[]  = [$timestamp, isset($c3bg_c3b_array[$key]) ? $c3bg_c3b_array[$key] : 0];
            $l6_l3_result[]     = [$timestamp, isset($l6_l3_array[$key])    ? $l6_l3_array[$key]    : 0];
            $l8_l6_result[]     = [$timestamp, isset($l8_l6_array[$key])    ? $l8_l6_array[$key]    : 0];
        }

        $result = array();
        $result['l3_c3b']   = json_encode($l3_c3b_result);
        $result['l3_c3bg']  = json_encode($l3_c3bg_result);
        $result['l3_l1']    = json_encode($l3_l1_result);
        $result['l1_c3bg']  = json_encode($l1_c3bg_result);
        $result['c3bg_c3b'] = json_encode($c3bg_c3b_result);
        $result['l6_l3']    = json_encode($l6_l3_result);
        $result['l8_l6']    = json_encode($l8_l6_result);

        return $result;
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

        return $data_where;
    }

    private function getAds(){
        $data_where = $this->getWhereData();
        $ads    = array();
        if (count($data_where) >= 1) {
            $ads = Ad::where($data_where)->pluck('_id')->toArray();
        }
        return $ads;
    }

    private function getDate($month){
        $request = request();
        if($month){
            $year   = date('Y'); /* nam hien tai*/
            $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
            $first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
            $last_day_this_month    = date('Y-' . $month .'-t'); /* ngày cuối cùng của tháng */
        }else if($request->month){
            $month  = request('month');
            $year   = date('Y'); /* nam hien tai*/
            $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
            $first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
            $last_day_this_month    = date('Y-' . $month .'-t'); /* ngày cuối cùng của tháng */
        }else {
            $month  = date('m'); /* thang hien tai */
            $year   = date('Y'); /* nam hien tai*/
            $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
            $first_day_this_month = date('Y-m-01'); /* ngày đàu tiên của tháng */
            $last_day_this_month = date('Y-m-t'); /* ngày cuối cùng của tháng */
        }

        return [$year, $month, $d, $first_day_this_month, $last_day_this_month];
    }

    public function getBudgetByWeeks(){
        // get start date and end date
        $w          = $this->getWeek();
        $start_date = date('Y-01-01'); /* ngày đàu tiên của nam */
        $end_date   = date('Y-m-d'); /* ngày hien tai  */

        // get Ad id
        $ad_id  = $this->getAds();

        if(count($ad_id) > 0){
            $match = [
                ['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
                ['$match' => ['ad_id' => ['$in' => $ad_id]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
                        'c3bg'  => ['$sum' => '$c3bg'],
                        'l1'    => ['$sum' => '$l1'],
                        'l3'    => ['$sum' => '$l3'],
                        'l6'    => ['$sum' => '$l6'],
                        'l8'    => ['$sum' => '$l8'],
                    ]
                ]
            ];
        }else{
            $match = [
                ['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
                        'c3bg'  => ['$sum' => '$c3bg'],
                        'l1'    => ['$sum' => '$l1'],
                        'l3'    => ['$sum' => '$l3'],
                        'l6'    => ['$sum' => '$l6'],
                        'l8'    => ['$sum' => '$l8'],
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $array_weeks = array();
        for ($i = 1; $i <= $w; $i++) {
            $array_weeks[$i] = 0;
        }

        $config     = Config::getByKeys(['USD_VND', 'USD_THB']);
        $usd_vnd    = $config['USD_VND'];
        $usd_thb    = $config['USD_VND'];

        $me_array   = array();
        $re_array   = array();
        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();

        $total_me   = 0;
        $total_re   = 0;

        foreach ($query_chart as $item_result) {
            $week = $this->getWeek($item_result['_id']);

            $me         = $item_result['me'] * $usd_vnd;
            $re         = $item_result['re'] / $usd_thb * $usd_vnd;

            $total_me   += $me;
            $total_re   += $re;

            @$me_array[$week]   += $me;
            @$re_array[$week]   += $re;
            @$c3b_array[$week]  += $item_result['c3b']   ? $me / $item_result['c3b']     : 0 ;
            @$c3bg_array[$week] += $item_result['c3bg']  ? $me / $item_result['c3bg']    : 0 ;
            @$l1_array[$week]   += $item_result['l1']    ? $me / $item_result['l1']      : 0 ;
            @$l3_array[$week]   += $item_result['l3']    ? $me / $item_result['l3']      : 0 ;
            @$l6_array[$week]   += $item_result['l6']    ? $me / $item_result['l6']      : 0 ;
            @$l8_array[$week]   += $item_result['l8']    ? $me / $item_result['l8']      : 0 ;

        }

        $me_result   = array();
        $re_result   = array();
        $c3b_result  = array();
        $c3bg_result = array();
        $l1_result   = array();
        $l3_result   = array();
        $l6_result   = array();
        $l8_result   = array();

        for ($i = 1; $i <= $w; $i++) {

            $me_result[]    = [$i, isset($me_array[$i])   ? $me_array[$i]   : 0];
            $re_result[]    = [$i, isset($re_array[$i])   ? $re_array[$i]   : 0];
            $c3b_result[]   = [$i, isset($c3b_array[$i])  ? $c3b_array[$i]  : 0];
            $c3bg_result[]  = [$i, isset($c3bg_array[$i]) ? $c3bg_array[$i] : 0];
            $l1_result[]    = [$i, isset($l1_array[$i])   ? $l1_array[$i]   : 0];
            $l3_result[]    = [$i, isset($l3_array[$i])   ? $l3_array[$i]   : 0];
            $l6_result[]    = [$i, isset($l6_array[$i])   ? $l6_array[$i]   : 0];
            $l8_result[]    = [$i, isset($l8_array[$i])   ? $l8_array[$i]   : 0];
        }

        $me_re  = $total_re ? round ($total_me / $total_re, 4) * 100 : 0;

        $result = array();
        $result['me']       = json_encode($me_result);
        $result['re']       = json_encode($re_result);
        $result['c3b']      = json_encode($c3b_result);
        $result['c3bg']     = json_encode($c3bg_result);
        $result['l1']       = json_encode($l1_result);
        $result['l3']       = json_encode($l3_result);
        $result['l6']       = json_encode($l6_result);
        $result['l8']       = json_encode($l8_result);
        $result['me_re']    = $me_re;

        return $result;
    }



    private function getWeek( $date = null ){
        if($date){
            $week_count = date('W', strtotime($date));
            return (int)$week_count;
        }

        $year = date('Y');

        $week_count = date('W', strtotime($year . '-12-31'));

        if ($week_count == '01')
        {
            $week_count = date('W', strtotime($year . '-12-24'));
        }

        return (int)$week_count;
    }

    public function getDataByDays(){
        $request        = request();
        $budget_month   = $request->budget_month;
        $quantity_month = $request->quantity_month;
        $quality_month  = $request->quality_month;

        $budget     = $this->getBudget($budget_month);
        $quantity   = $this->getQuantity($quantity_month);
        $quality    = $this->getQuality($quality_month);

        $result['budget']   = $budget;
        $result['quantity'] = $quantity;
        $result['quality']  = $quality;

        return $result;
    }

    public function getDataByWeeks(){

//        $budget     = $this->getBudgetByWeeks();
//
//        $result['budget']   = $budget;

        



        return $result;
    }

    public function getDataByMonths(){
        $request        = request();
        $budget_month   = $request->budget_month;
        $quantity_month = $request->quantity_month;
        $quality_month  = $request->quality_month;

        $budget     = $this->getBudget($budget_month);
        $quantity   = $this->getQuantity($quantity_month);
        $quality    = $this->getQuality($quality_month);

        $result['budget']   = $budget;
        $result['quantity'] = $quantity;
        $result['quality']  = $quality;

        return $result;
    }

}
