<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Channel;
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title     = " Inventory Report | Helios";
        $page_css       = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active         = 'inventory-report';
        $breadcrumbs    = "<i class=\"fa-fw fa fa-empire\"></i> Report <span>> Inventory Report </span>";

        $days   = $this->get_days_in_month();
        $month  = date('m');
        $year   = date('Y');
        $channel = Channel::all();

        $c3_produce_channel     = $this->get_c3_produce_by_channel();
        $c3_transfer_channel    = $this->get_c3_transfer_by_channel();
        $c3_produce_source      = $this->get_c3_produce_by_source();
        $c3_transfer_source     = $this->get_c3_transfer_by_source();

        return view('pages.inventory_report', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'c3_produce_channel',
            'c3_transfer_channel',
            'c3_produce_source',
            'c3_transfer_source',
            'days',
            'month',
            'year',
            'channel'
        ));
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

    public function filter(){
        $request = request();

        $result = $this->get_data();
        $data_maketer   = $this->get_data_by_maketer($result);
        $days   = $this->get_days_in_month();
        $month  = date('M');
        $year   = date('Y');

        if($request->month){
            $month  = date('M', strtotime($year.'-'.$request->month));
        }

        if($request->maketer){
            $arr_maketer = explode(',',$request->maketer);
            foreach ($data_maketer as $key => $item ){
                if(!in_array($item['user_id'], $arr_maketer)){
                    unset($data_maketer[$key]);
                }
            }
        }

        return view('pages.table_report_kpi', compact(
            'data_maketer',
            'days',
            'month',
            'year'
        ));
    }

    private function get_c3_produce_by_channel(){

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

        $query = Contact::raw(function ($collection) use ($startDate, $endDate) {

            $match = [
                ['$match' => ['submit_time' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => 'c3b']],
                [
                    '$group' => [
                        '_id' => ['submit_time' => '$submit_time', 'channel_name' => '$channel_name', 'source_name' => '$source_name'],
                        'c3b_produce' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_produce' => -1,
                    ]
                ]
            ];

            return $collection->aggregate($match);
        });

        $result = array();
        foreach ($query as $item){
            $date       = date('d/m/Y', @$item['_id']['submit_time'] / 1000);
            $channel    = @$item['_id']['channel_name'];
            $source     = @$item['_id']['source_name'] ? $item['_id']['source_name'] : 'N/A';
            @$result[$date]['channel_name'] = $channel;
            @$result[$date]['c3b_produce']  = @$item['c3b_produce'];
            @$result[$date]['source_name']  = $source;
        }

        return $query;

    }

    private function get_c3_transfer_by_channel(){

        $month      = date('m'); /* thang hien tai */
        $year       = date('Y'); /* nam hien tai*/
        $request    = request();
        if($request->month){
            $month = $request->month;
        }

        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $start  = '01/'.$month.'/'.$year; /* ngày đàu tiên của tháng */
        $end    = $d.'/'.$month.'/'.$year; /* ngày cuối cùng của tháng */

//        $startDate  = strtotime($start)*1000;
//        $endDate    = strtotime("+1 day", strtotime($end))*1000;

        $query = Contact::raw(function ($collection) use ($start, $end) {

            $match = [
                ['$match' => ['export_sale_date' => ['$gte' => $start, '$lte' => $end]]],
                ['$match' => ['clevel' => 'c3b']],
                ['$match' => ['olm_status' => ['$in' => ['0', '1']]]],
                [
                    '$group' => [
                        '_id' => ['export_sale_date' => '$export_sale_date', 'channel_name' => '$channel_name', 'source_name' => '$source_name'],
                        'c3b_transfer' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_transfer' => -1,
                    ]
                ]
            ];

            return $collection->aggregate($match);
        });

        $result = array();
        foreach ($query as $item){
            $date       = date('d/m/Y', @$item['_id']['submit_time'] / 1000);
            $channel    = @$item['_id']['channel_name'];
            $source     = @$item['_id']['source_name'] ? $item['_id']['source_name'] : 'N/A';
            @$result[$date]['channel_name'] = $channel;
            @$result[$date]['c3b_transfer'] = @$item['c3b_transfer'];
            @$result[$date]['source_name']  = $source;
        }

        return $result;

    }

    private function get_c3_produce_by_source(){

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

        $query = Contact::raw(function ($collection) use ($startDate, $endDate) {

            $match = [
                ['$match' => ['submit_time' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => 'c3b']],
                [
                    '$group' => [
                        '_id' => ['submit_time' => '$submit_time', 'source_name' => '$source_name'],
                        'c3b_produce' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_produce' => -1,
                    ]
                ]
            ];

            return $collection->aggregate($match);
        });

        $result = array();
        foreach ($query as $item){
            $date       = date('d/m/Y', @$item['_id']['submit_time'] / 1000);
            $source     = @$item['_id']['source_name'] ? $item['_id']['source_name'] : 'N/A';
            @$result[$date]['c3b_produce']  = @$item['c3b_produce'];
            @$result[$date]['source_name']  = $source;
        }

        return $query;

    }

    private function get_c3_transfer_by_source(){

        $month      = date('m'); /* thang hien tai */
        $year       = date('Y'); /* nam hien tai*/
        $request    = request();
        if($request->month){
            $month = $request->month;
        }

        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $start  = '01/'.$month.'/'.$year; /* ngày đàu tiên của tháng */
        $end    = $d.'/'.$month.'/'.$year; /* ngày cuối cùng của tháng */

//        $startDate  = strtotime($start)*1000;
//        $endDate    = strtotime("+1 day", strtotime($end))*1000;

        $query = Contact::raw(function ($collection) use ($start, $end) {

            $match = [
                ['$match' => ['export_sale_date' => ['$gte' => $start, '$lte' => $end]]],
                ['$match' => ['clevel' => 'c3b']],
                ['$match' => ['olm_status' => ['$in' => ['0', '1']]]],
                [
                    '$group' => [
                        '_id' => ['export_sale_date' => '$export_sale_date', 'source_name' => '$source_name'],
                        'c3b_transfer' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_transfer' => -1,
                    ]
                ]
            ];

            return $collection->aggregate($match);
        });

        $result = array();
        foreach ($query as $item){
            $date       = @$item['_id']['export_sale_date'];
            $source     = @$item['_id']['source_name'] ? $item['_id']['source_name'] : 'N/A';
            @$result[$date]['c3b_transfer'] = @$item['c3b_transfer'];
            @$result[$date]['source_name']  = $source;
        }

        return $result;

    }

}
