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

    public function filter(){
        $request = request();

        $data = $this->prepare_data();

        return view('pages.table_performance_report', compact(
            'data'
        ));

    }

    private function prepare_data(){
        $request = request();

        $startDate  = strtotime("midnight")*1000;
        $endDate    = strtotime("tomorrow")*1000;
        if($request->date){
            $date_place = str_replace('-', ' ', $request->date);
            $date_arr   = explode(' ', str_replace('/', '-', $date_place));
            $startDate  = strtotime($date_arr[0])*1000;
            $endDate    = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }

        $c3_produce     = $this->get_c3_produce($startDate, $endDate);
        $c3_transfer    = $this->get_c3_transfer($startDate, $endDate);
        $c3_inventory   = $this->get_c3_inventory($startDate, $endDate);
        $cts_data       = $this->get_cts_data();

        if($request->marketer){
            $users   = User::whereIn('_id', explode(',', $request->marketer))->get();
        }
        else{
            $users = User::all();
        }

        $result = array();
        foreach ($users as $user){

            $id     = $user->_id;
            $name   = $user->username;

            @$result[$name]['c3b_produce']      = @$c3_produce[$id]['c3b_produce']      ? $c3_produce[$id]['c3b_produce']       : 0;
            @$result[$name]['c3b_transfer']     = @$c3_transfer[$id]['c3b_transfer']    ? $c3_transfer[$id]['c3b_transfer']     : 0;
            @$result[$name]['c3b_inventory']    = @$c3_inventory[$id]['c3b_inventory']  ? $c3_inventory[$id]['c3b_inventory']   : 0;

            $c3b    =  @$cts_data[$id]['c3b'];
            $l1     =  @$cts_data[$id]['l1'];
            $l3     =  @$cts_data[$id]['l3'];
            $l6     =  @$cts_data[$id]['l6'];
            $l8     =  @$cts_data[$id]['l8'];
            $spent  =  @$cts_data[$id]['spent'];

            @$result[$name]['c3_l1']    = $l1   ? round($c3b / $l1, 4) * 100    : 0;
            @$result[$name]['c3_l3']    = $l3   ? round($c3b / $l3, 4) * 100    : 0;
            @$result[$name]['c3_l6']    = $l6   ? round($c3b / $l6, 4) * 100    : 0;
            @$result[$name]['c3_l8']    = $l8   ? round($c3b / $l8, 4) * 100    : 0;
            @$result[$name]['spent']    = $spent    ? round($spent, 2)    : 0;
            @$result[$name]['c3_cost']  = $spent    ? round($spent / $c3b, 2)    : 0;

        }
        return $result;
    }

    private function get_c3_produce($startDate, $endDate){
        $request = request();
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

    private function get_c3_inventory($startDate, $endDate){
        $request    = request();

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

    private function get_c3_transfer($startDate, $endDate){
        $request = request();
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

//        $month      = date('Y-m-d');
//        $year       = date('Y');
//        $request    = request();
//        if($request->month){
//            $month = $request->month;
//        }
//
//        $d  = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
//
//        $startDate  = $year.'-'.$month.'-01';
//        $endDate    = $year.'-'.$month.'-'.$d;

        $request    = request();

        $startDate  = date('Y-m-d');
        $endDate    = date('Y-m-d');
        if($request->date){
            $date_place = str_replace('-', ' ', $request->date);
            $date_arr   = explode(' ', str_replace('/', '-', $date_place));
            $start      = strtotime($date_arr[0]);
            $startDate  = date('Y-m-d',$start);
            $end        = strtotime($date_arr[1]);
            $endDate    = date('Y-m-d',$end);
        }

        if($request->marketer){
            $marketer = explode(',', $request->marketer);
            $match = [
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['creator_id' => ['$in' => $marketer]]],
                [
                    '$group' => [
                        '_id'   => '$creator_id',
                        'spent' => ['$sum' => '$spent'],
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
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
                        'spent' => ['$sum' => '$spent'],
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
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

        $result = array();
        foreach ($query as $item) {
            if (isset($result[$item['_id']])) {
                @$result[$item['_id']]['c3b']   += @$item['c3b'];
                @$result[$item['_id']]['spent'] += @$item['spent'];
                @$result[$item['_id']]['l1']    += @$item['l1'];
                @$result[$item['_id']]['l3']    += @$item['l3'];
                @$result[$item['_id']]['l6']    += @$item['l6'];
                @$result[$item['_id']]['l8']    += @$item['l8'];
            } else {
                @$result[$item['_id']]['c3b']   = @$item['c3b'];
                @$result[$item['_id']]['spent'] = @$item['spent'];
                @$result[$item['_id']]['l1']    = @$item['l1'];
                @$result[$item['_id']]['l3']    = @$item['l3'];
                @$result[$item['_id']]['l6']    = @$item['l6'];
                @$result[$item['_id']]['l8']    = @$item['l8'];
            }
        }
        return $result;
    }

}
