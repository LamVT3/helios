<?php

namespace App\Http\Controllers;

use App\ActivityBooking;
use App\Admin_account;
use App\AdResult;
use App\CarBooking;
use App\Customer;
use App\CustomerActivity;
use App\Dm_contact;
use App\HotelBooking;
use App\TourBooking;
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

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($first_day_this_month, $last_day_this_month) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => '$c3'
                        ],
                        'l8' => [
                            // 2018-04-13 LamVT [HEL-12] update "L8 this month" chart
                            '$sum' => '$L8'
                            // end 2018-04-13 LamVT [HEL-12] update "L8 this month" chart
                        ]
                    ]
                ]
            ]);
        });

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime(date("Y") . "-" . date("m") . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        $c3_array = array();
        $l8_array = array();

        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);
            $c3_array[(int)($day[2])] = $item_result['c3'];
            $l8_array[(int)($day[2])] = $item_result['l8'];
        }

        /*  lay du lieu c3*/
        $chart_c3 = array();
        foreach ($array_month as $key => $timestamp) {
            if (isset($c3_array[$key])) {
                $chart_c3[] = [$timestamp, $c3_array[$key]];
            } else {
                $chart_c3[] = [$timestamp, 0];
            }
        }
        $chart_c3 = json_encode($chart_c3);

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

        $dashboard['chart_c3'] = $chart_c3;
        $dashboard['chart_l8'] = $chart_l8;
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
            'dashboard'
        ));
    }
}
