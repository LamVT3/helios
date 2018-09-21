<?php

namespace App\Http\Controllers;

use App\ActivityBooking;
use App\Admin_account;
use App\AdResult;
use App\CarBooking;
use App\Channel;
use App\Customer;
use App\CustomerActivity;
use App\Dm_contact;
use App\HotelBooking;
use App\TourBooking;
use App\User;
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

        $users      = User::all();
        $channels   = Channel::all();
        $kpi = array();

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime(date("Y") . "-" . date("m") . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        if($user_role == 'Marketer'){
            if(auth()->user()->kpi){
                $kpi = auth()->user()->kpi;
            }
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['creator_id' => $user_id]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        ],
                        'l8' => [
                            '$sum' => '$l8'
                        ]
                    ]
                ]
            ];
        }else{
            $kpi = $this->getTotalKpi();
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        ],
                        'l8' => [
                            '$sum' => '$l8'
                        ]
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match, $first_day_this_month, $last_day_this_month) {
            return $collection->aggregate($match);
        });

        $c3_array = array();
        $l8_array = array();

        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);
            $c3_array[(int)($day[2])] = $item_result['c3'];
            $l8_array[(int)($day[2])] = $item_result['l8'];
        }

        /*  lay du lieu c3*/
        $chart_c3  = array();
        $chart_kpi = array();
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
        }
        $chart_c3   = json_encode($chart_c3);
        $chart_kpi  = json_encode($chart_kpi);

        /* lay du lieu l8*/
        $chart_l8 = array();
        foreach ($array_month as $key => $timestamp) {
            if (isset($l8_array[$key])) {
                $chart_l8[] = [$timestamp, $l8_array[$key]];
            } else {
                $chart_l8[] = [$timestamp, 0];
            }
        }
        $chart_l8 = json_encode($chart_l8);
        /* end l8 */

        $dashboard['chart_c3']  = $chart_c3;
        $dashboard['chart_kpi'] = $chart_kpi;
        $dashboard['chart_l8']  = $chart_l8;

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

    private function getTotalKpi(){
        $users  = User::all();
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
}
