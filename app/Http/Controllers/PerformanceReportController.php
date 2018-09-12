<?php

namespace App\Http\Controllers;

use App\AdResult;
use App\Contact;
use App\Channel;
use App\Source;
use App\User;
use Illuminate\Http\Request;

class  PerformanceReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title     = "  Performance Report | Helios";
        $page_css       = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active         = 'performance-report';
        $breadcrumbs    = "<i class=\"fa fa-universal-access\"></i> Report <span>>  Performance Report </span>";

        $users = User::all();

        $data = $this->prepare_data();

        return view('pages.performance_report', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'users',
            'data'
        ));
    }

    public function prepare_data(){

        $c3_produce     = $this->get_c3_produce();
        $c3_transfer    = $this->get_c3_transfer();
        $c3_inventory   = $this->get_c3_inventory();

//        $cts_data = $this->get_cts_data();

        $users = User::all();

        $result = array();
        foreach ($users as $user){

            $id     = $user->_id;
            $name   = $user->username;

            @$result[$name]['c3b_produce']      = @$c3_produce[$id]['c3b_produce'] ? $c3_produce[$id]['c3b_produce'] : 0;
            @$result[$name]['c3b_transfer']     = @$c3_transfer[$id]['c3b_transfer'] ? $c3_transfer[$id]['c3b_transfer'] : 0;
            @$result[$name]['c3b_inventory']    = @$c3_inventory[$id]['c3b_inventory'] ? $c3_inventory[$id]['c3b_inventory'] : 0;

        }
        return $result;
    }

    private function get_c3_produce(){

        $month      = date('m'); /* thang hien tai */
        $year       = date('Y'); /* nam hien tai*/
        $request    = request();
        if($request->month){
            $month = $request->month;
        }

        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $start  = '01-'.$month.'-'.$year; /* ngày đàu tiên của tháng */
        $end    = $d.'-'.$month.'-'.$year; /* ngày cuối cùng của tháng */

        $startDate  = strtotime($start)*1000;
        $endDate    = strtotime("+1 day", strtotime($end))*1000;

        if($request->marketer){
            $marketer = explode(',', $request->marketer);

            $match = [
                ['$match' => ['submit_time' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => ['$in' => ['c3b','c3bg']]]],
                ['$match' => ['marketer_id' => ['$in' => $marketer]]],
                [
                    '$group' => [
                        '_id' => '$marketer_id',
                        'c3b_produce' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_produce' => -1,
                    ]
                ]
            ];
        }else{
            $match = [
                ['$match' => ['submit_time' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => ['$in' => ['c3b','c3bg']]]],
                [
                    '$group' => [
                        '_id' => '$marketer_id',
                        'c3b_produce' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_produce' => -1,
                    ]
                ]
            ];
        }

        $query = Contact::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $result = array();
        foreach ($query as $item) {
            if (isset($result[$item['_id']])) {
                @$result[$item['_id']]['c3b_produce'] += @$item['c3b_produce'];
            } else {
                @$result[$item['_id']]['c3b_produce'] = @$item['c3b_produce'];
            }
        }

        return $result;

    }

    private function get_c3_inventory(){

        $month      = date('m'); /* thang hien tai */
        $year       = date('Y'); /* nam hien tai*/
        $request    = request();
        if($request->month){
            $month = $request->month;
        }

        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $start  = '01-'.$month.'-'.$year; /* ngày đàu tiên của tháng */
        $end    = $d.'-'.$month.'-'.$year; /* ngày cuối cùng của tháng */

        $startDate  = strtotime($start)*1000;
        $endDate    = strtotime("+1 day", strtotime($end))*1000;

        if($request->marketer){
            $marketer = explode(',', $request->marketer);
            $match = [
                ['$match' => ['submit_time' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => ['$in' => ['c3b','c3bg']]]],
                ['$match' => ['olm_status' => ['$nin' => [0, 1]]]],
                ['$match' => ['marketer_id' => ['$in' => $marketer]]],
                [
                    '$group' => [
                        '_id' => '$marketer_id',
                        'c3b_inventory' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_inventory' => -1,
                    ]
                ]
            ];
        }else{
            $match = [
                ['$match' => ['submit_time' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => ['$in' => ['c3b','c3bg']]]],
                ['$match' => ['olm_status' => ['$nin' => [0, 1]]]],
                [
                    '$group' => [
                        '_id' => '$marketer_id',
                        'c3b_inventory' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_inventory' => -1,
                    ]
                ]
            ];
        }

        $query = Contact::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $result = array();
        foreach ($query as $item) {
            if (isset($result[$item['_id']])) {
                @$result[$item['_id']]['c3b_inventory'] += @$item['c3b_inventory'];
            } else {
                @$result[$item['_id']]['c3b_inventory'] = @$item['c3b_inventory'];
            }
        }
        return $result;

    }

    private function get_c3_transfer(){

        $month      = date('m'); /* thang hien tai */
        $year       = date('Y'); /* nam hien tai*/
        $request    = request();
        if($request->month){
            $month = $request->month;
        }

        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $start  = '01-'.$month.'-'.$year; /* ngày đàu tiên của tháng */
        $end    = $d.'-'.$month.'-'.$year; /* ngày cuối cùng của tháng */

        $startDate  = strtotime($start)*1000;
        $endDate    = strtotime("+1 day", strtotime($end))*1000;

        if($request->marketer){
            $marketer = explode(',', $request->marketer);
            $match = [
                ['$match' => ['export_sale_date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => ['$in' => ['c3b','c3bg']]]],
                ['$match' => ['olm_status' => ['$in' => [0, 1]]]],
                ['$match' => ['marketer_id' => ['$in' => $marketer]]],
                [
                    '$group' => [
                        '_id' => '$marketer_id',
                        'c3b_transfer' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_transfer' => -1,
                    ]
                ]
            ];
        }else{
            $match = [
                ['$match' => ['export_sale_date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => ['$in' => ['c3b','c3bg']]]],
                ['$match' => ['olm_status' => ['$in' => [0, 1]]]],
                [
                    '$group' => [
                        '_id' => '$marketer_id',
                        'c3b_transfer' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_transfer' => -1,
                    ]
                ]
            ];
        }

        $query = Contact::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $result = array();
        foreach ($query as $item) {
            if (isset($result[$item['_id']])) {
                @$result[$item['_id']]['c3b_transfer'] += @$item['c3b_transfer'];
            } else {
                @$result[$item['_id']]['c3b_transfer'] = @$item['c3b_transfer'];
            }
        }
        return $result;
    }

    private function get_cts_data(){

        $month      = date('m'); /* thang hien tai */
        $year       = date('Y'); /* nam hien tai*/
        $request    = request();
        if($request->month){
            $month = $request->month;
        }

        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */

        $startDate  = $year.'-'.$month.'-01';
        $endDate    = $year.'-'.$month.'-'.$d;

        if($request->marketer){
            $marketer = explode(',', $request->marketer);
            $match = [
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['creator_id' => ['$in' => $marketer]]],
                [
                    '$group' => [
                        '_id'   => '$creator_id',
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
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
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                [
                    '$group' => [
                        '_id'   => '$creator_id',
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        'c3bg'  => ['$sum' => '$c3bg'],
                        'l1'    => ['$sum' => '$l1'],
                        'l3'    => ['$sum' => '$l3'],
                        'l6'    => ['$sum' => '$l6'],
                        'l8'    => ['$sum' => '$l8'],
                    ]
                ]
            ];
        }

        $query = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

//        var_dump($query);die;
        $result = array();
        foreach ($query as $item) {
            if (isset($result[$item['_id']])) {
                @$result[$item['_id']]['c3b'] += @$item['c3b'];
            } else {
                @$result[$item['_id']]['c3b'] = @$item['c3b'];
            }
        }
        return $result;
    }

}
