<?php

namespace App\Http\Controllers;

use App\AdResult;
use App\Channel;
use App\User;
use App\UserKpi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ad;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*  phan date*/
        $month = date('m'); /* thang hien tai */
        $year = date('Y'); /* nam hien tai*/
        $d = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $first_day_this_month = date('Y-m-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month = date('Y-m-t'); /* ngày cuối cùng của tháng */
        /* end date */

        $user_role  = auth()->user()->role;
        $user_id    = auth()->user()->_id;

        $users = User::where('role', 'Marketer')->where('is_active', 1)->get();

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime(date("Y") . "-" . date("m") . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        if($user_role == 'Marketer'){
            $kpi        = $this->getTotalKpi($user_id);
            $channels   = $this->get_channel($user_id);
            $ads_kpi    = $this->get_ad_kpi($user_id);
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['ad_id' => $ads_kpi]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        ],
                        'C3A_Duplicated'       => [ '$sum' => '$C3A_Duplicated' ],
                        'C3B_Under18'          => [ '$sum' => '$C3B_Under18' ],
                        'C3B_Duplicated15Days' => [ '$sum' => '$C3B_Duplicated15Days' ],
                        'C3A_Test'             => [ '$sum' => '$C3A_Test' ],
                    ]
                ]
            ];
        }else{
            $kpi        = $this->getTotalKpi();
            $channels   = Channel::all();
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        ],
                        'C3A_Duplicated'       => [ '$sum' => '$C3A_Duplicated' ],
                        'C3B_Under18'          => [ '$sum' => '$C3B_Under18' ],
                        'C3B_Duplicated15Days' => [ '$sum' => '$C3B_Duplicated15Days' ],
                        'C3A_Test'             => [ '$sum' => '$C3A_Test' ],
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match, $first_day_this_month, $last_day_this_month) {
            return $collection->aggregate($match);
        });


        $c3_array   = array();
        $rs         = array();

        foreach ( $array_month as $key => $timestamp ) {
            $rs['c3'][$key] = 0;
            $rs['C3A_Duplicated'][$key] = 0;
            $rs['C3B_Under18'][$key] = 0;
            $rs['C3B_Duplicated15Days'][$key] = 0;
            $rs['C3A_Test'][$key] = 0;
        }

        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);

            $c3_array[(int)($day[2])] = $item_result['c3'];

            $rs['c3'][ (int) ( $day[2] ) ]                   += $item_result['c3'];
            $rs['C3A_Duplicated'][ (int) ( $day[2] ) ]       += $item_result['C3A_Duplicated'];
            $rs['C3B_Under18'][ (int) ( $day[2] ) ]          += $item_result['C3B_Under18'];
            $rs['C3B_Duplicated15Days'][ (int) ( $day[2] ) ] += $item_result['C3B_Duplicated15Days'];
            $rs['C3A_Test'][ (int) ( $day[2] ) ]             += $item_result['C3A_Test'];
        }

        /*  lay du lieu c3*/
        $chart_c3   = array();
        $chart_kpi  = array();
        $chart      = array();
        foreach ($array_month as $key => $timestamp) {
            if (isset($c3_array[$key])) {
                $chart_c3[] = [$timestamp, $c3_array[$key]];
            } else {
                $chart_c3[] = [$timestamp, 0];
            }
            if (isset($kpi[$year][$month][$key])) {
                $chart_kpi[] = [$timestamp, (int)$kpi[$year][$month][$key]];
            } else {
                $chart_kpi[] = [$timestamp, 0];
            }

            $chart['C3A_Duplicated'][] = [
                $timestamp,
                isset( $rs['C3A_Duplicated'][ $key ] ) ? $rs['C3A_Duplicated'][ $key ] : 0,
            ];

            $chart['C3B_Under18'][] = [
                $timestamp,
                isset( $rs['C3B_Under18'][ $key ] ) ? $rs['C3B_Under18'][ $key ] : 0,
            ];

            $chart['C3B_Duplicated15Days'][] = [
                $timestamp,
                isset( $rs['C3B_Duplicated15Days'][ $key ] ) ? $rs['C3B_Duplicated15Days'][ $key ] : 0,
            ];

            $chart['C3A_Test'][] = [
                $timestamp,
                isset( $rs['C3A_Test'][ $key ] ) ? $rs['C3A_Test'][ $key ] : 0,
            ];
        }
        $chart_c3   = json_encode($chart_c3);
        $chart_kpi  = json_encode($chart_kpi);

        $result['C3A_Duplicated']       = json_encode( $chart['C3A_Duplicated'] );
        $result['C3B_Under18']          = json_encode( $chart['C3B_Under18'] );
        $result['C3B_Duplicated15Days'] = json_encode( $chart['C3B_Duplicated15Days'] );
        $result['C3A_Test']             = json_encode( $chart['C3A_Test'] );
        $result['c3']                   = json_encode($rs['c3']);

        $dashboard['chart_c3']  = $chart_c3;
        $dashboard['chart_kpi'] = $chart_kpi;
        $dashboard['c3a_c3b']   = $result;

        /* end Chart */

        $page_title = "Dashboard | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'dashboard';
        $breadcrumbs = "<i class=\"fa-fw fa fa-home\"></i> Dashboard";

        return view('pages.dashboard', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'dashboard',
            'users',
            'channels',
            'month'
        ));
    }

    private function getTotalKpi($user_id = null){
        if($user_id){
            $users  = UserKpi::where('user_id', $user_id)->get();
        }else{
            $users  = UserKpi::all();
        }

        $month  = date('m');
        $year   = date('Y');
        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $total_kpi = array();
        foreach ($users as $user){
            $kpi = @$user->kpi[$year][$month];
            if(count($kpi) < 1){
                continue;
            }
            if(isset($total_kpi[$year][$month])){
                for ($i = 1; $i <= $d; $i++) {
                    if (isset($total_kpi[$year][$month][$i])) {
                        @$total_kpi[$year][$month][$i] += (int)@$kpi[$i];
                    } else {
                        @$total_kpi[$year][$month][$i] = (int)@$kpi[$i];
                    }
                }
            }else{
                $total_kpi[$year][$month] = @$kpi;
            }
        }

        return $total_kpi;
    }

    public function get_channel($marketer_id = null){
        $request = request();

        if($request->marketer){
            $marketer_id = $request->marketer;
            $ads        = Ad::where('creator_id', $marketer_id)->pluck('channel_id')->toArray();
            $channel    = Channel::whereIn('_id', $ads)->get();
        }else if($marketer_id){
            $ads        = Ad::where('creator_id', $marketer_id)->pluck('channel_id')->toArray();
            $channel    = Channel::whereIn('_id', $ads)->get();
        }else{
            $channel    = Channel::get();
        }
        return $channel;
    }

    private function get_ad_kpi($marketer_id = null){
        $request = request();

        $channel    = UserKpi::where('user_id', $marketer_id)->groupBy('channel_id')->pluck('channel_id')->toArray();
        $ads_kpi    = Ad::whereIn('channel_id', $channel)->pluck('_id')->toArray();

        return $ads_kpi;
    }

    private function getDate($month = null){
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

    public function getC3AC3B(){
        $request = request();
        // get start date and end date
        list($year, $month, $d, $first_day_this_month, $last_day_this_month) = $this->getDate();

        $source_id = request()->source_id;
        $marketer_id = request()->marketer_id;
        $team_id = request()->team_id;
        $campaign_id = request()->campaign_id;
        $subcampaign_id = request()->subcampaign_id;
        $channel_name = request()->channel_name;
        $channel_id = request()->channel_id;

        $isEmpy = false;
        if($channel_name != "" || $source_id != "" || $marketer_id != "" ||$team_id != "" ||$campaign_id != "" ||$subcampaign_id != "" || $channel_id!= ""){
            $isEmpy =true;
        }

        $channel = [];
        if($request->marketer_id){
            $channel    = UserKpi::where('user_id', $request->marketer_id)->groupBy('channel_id')->pluck('channel_id')->toArray();
        }

        if($request->channel_id){
            $channel = [$request->channel_id];
        }

        $ad_id  = Ad::whereIn('channel_id', $channel)->pluck('_id')->toArray();

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        $array_reason = [ 'C3A_Duplicated', 'C3B_Under18', 'C3B_Duplicated15Days', 'C3A_Test' ];
        $rs = [];

        if(count($ad_id) >= 0 && $isEmpy){
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['ad_id' => ['$in' => $ad_id]]],
                [
                    '$group' => [
                        '_id'                  => '$date',
                        'c3'                   => [ '$sum' => '$c3' ],
                        'C3A_Duplicated'       => [ '$sum' => '$C3A_Duplicated' ],
                        'C3B_Under18'          => [ '$sum' => '$C3B_Under18' ],
                        'C3B_Duplicated15Days' => [ '$sum' => '$C3B_Duplicated15Days' ],
                        'C3A_Test'             => [ '$sum' => '$C3A_Test' ],
                    ]
                ]
            ];
        }else{
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'                  => '$date',
                        'c3'                   => [ '$sum' => '$c3' ],
                        'C3A_Duplicated'       => [ '$sum' => '$C3A_Duplicated' ],
                        'C3B_Under18'          => [ '$sum' => '$C3B_Under18' ],
                        'C3B_Duplicated15Days' => [ '$sum' => '$C3B_Duplicated15Days' ],
                        'C3A_Test'             => [ '$sum' => '$C3A_Test' ],
                    ]
                ]
            ];
        }

        $chart = [];
        $result = [];

        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        foreach ( $array_month as $key => $timestamp ) {
            $rs['c3'][$key] = 0;
            $rs['C3A_Duplicated'][$key] = 0;
            $rs['C3B_Under18'][$key] = 0;
            $rs['C3B_Duplicated15Days'][$key] = 0;
            $rs['C3A_Test'][$key] = 0;
        }

        foreach ( $query_chart as $item_result ) {
            $day = explode( '-', $item_result['_id'] );

            $rs['c3'][ (int) ( $day[2] ) ]                   += $item_result['c3'];
            $rs['C3A_Duplicated'][ (int) ( $day[2] ) ]       += $item_result['C3A_Duplicated'];
            $rs['C3B_Under18'][ (int) ( $day[2] ) ]          += $item_result['C3B_Under18'];
            $rs['C3B_Duplicated15Days'][ (int) ( $day[2] ) ] += $item_result['C3B_Duplicated15Days'];
            $rs['C3A_Test'][ (int) ( $day[2] ) ]             += $item_result['C3A_Test'];
        }

        foreach ( $array_month as $key => $timestamp ) {
            $chart['C3A_Duplicated'][] = [
                $timestamp,
                isset( $rs['C3A_Duplicated'][ $key ] ) ? $rs['C3A_Duplicated'][ $key ] : 0,
            ];

            $chart['C3B_Under18'][] = [
                $timestamp,
                isset( $rs['C3B_Under18'][ $key ] ) ? $rs['C3B_Under18'][ $key ] : 0,
            ];

            $chart['C3B_Duplicated15Days'][] = [
                $timestamp,
                isset( $rs['C3B_Duplicated15Days'][ $key ] ) ? $rs['C3B_Duplicated15Days'][ $key ] : 0,
            ];

            $chart['C3A_Test'][] = [
                $timestamp,
                isset( $rs['C3A_Test'][ $key ] ) ? $rs['C3A_Test'][ $key ] : 0,
            ];
        }

        $result['C3A_Duplicated']       = json_encode( $chart['C3A_Duplicated'] );
        $result['C3B_Under18']          = json_encode( $chart['C3B_Under18'] );
        $result['C3B_Duplicated15Days'] = json_encode( $chart['C3B_Duplicated15Days'] );
        $result['C3A_Test']             = json_encode( $chart['C3A_Test'] );
        $result['c3']                   = json_encode($rs['c3']);

        return $result;
    }

}
