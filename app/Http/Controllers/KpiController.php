<?php

namespace App\Http\Controllers;

use App\User;
use App\AdResult;
use App\Channel;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Kpis | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'kpis';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager <span>> Kpis</span>";

        return view('pages.kpis', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs'
        ));
    }

    public function assign_kpi(){
        $page_title = "Assign KPI | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'assign_kpi';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Assign KPI </span>";

        $users = User::all();

        $data = $this->get_data();
        $days = $this->get_days_in_month();
        $month= date('M');
        $year = date('Y');

        return view('pages.assign_kpi', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'users',
            'data',
            'days',
            'month',
            'year'
        ));
    }

    public function save_kpi(){

        $request    = request();
        $username   = $request->username;
        $month      = $request->month;
        $year       = $request->year;
        $user       = $username ? User::where('username', $username)->firstOrFail() : auth()->user();

        $kpi        = $user->kpi;
        $kpi[$year][$month] = $request->kpi;
        ksort( $kpi[$year]);
        $user->kpi  = $kpi;
        $user->save();
    }

    public function get_kpi(){

        $request    = request();
        $username   = $request->username;
        $month      = $request->month;
        $year       = $request->year;
        $user       = $username ? User::where('username', $username)->firstOrFail() : auth()->user();

        $kpi        = $user->kpi;
        return @$kpi[$year][$month];

    }

    public function get_data(){

        $request = request();
        /*  phan date*/
        $month = date('m'); /* thang hien tai */
        $year = date('Y'); /* nam hien tai*/

        if($request->month){
            $month = $request->month;
        }

        $users = User::all();

        foreach ($users as $user){
            $data[$user->username]['kpi']   = $user->kpi[$year][$month];
            $data[$user->username]['c3b']   = $this->get_c3b_data($user);
            $data[$user->username]['total_kpi'] = $user->kpi[$year][$month] ? array_sum($user->kpi[$year][$month]) : 0;
            $data[$user->username]['total_c3b'] = $data[$user->username]['c3b'] ? array_sum($data[$user->username]['c3b']) : 0;
        }
        return $data;

    }

    function get_days_in_month($month = null){
        $request = request();
        $year = date('Y');
        $month = date('m');
        if($request->month){
            $month = $request->month;
        }
        $d = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        return $d;

    }

    function get_c3b_data($user){
        /*  phan date*/
        $month = date('m'); /* thang hien tai */
        $year = date('Y'); /* nam hien tai*/
        $d = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $first_day_this_month = date('Y-m-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month = date('Y-m-t'); /* ngày cuối cùng của tháng */
        /* end date */

        $query = AdResult::raw(function($collection) use ($first_day_this_month,$last_day_this_month,$user) {
            return $collection->aggregate([
                ['$match' => [
                    'date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month],
                    'creator_id'  => $user->_id
                ]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3b' => [
                            '$sum' => '$c3b'
                        ],
                    ]
                ]
            ]);
        });
        $data = array();
        foreach ($query as $item){
            $day = explode("-",$item['_id']);
            $key = intval($day[2]);
            $data[$key] = @$item['c3b'];
        }

        return $data;
    }


}
