<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Contact;
use App\Channel;
use App\Source;
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

        $channel = Channel::all();

        return view('pages.inventory_report', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
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

        $days   = $this->get_days_in_month();
        $month  = date('m');
        $year   = date('Y');

        if($request->month){
            $month = $request->month;
        }

        $data = $this->prepare_data();

        return view('pages.table_inventory_report', compact(
            'data',
            'days',
            'month',
            'year'
        ));
    }

    private function get_c3_produce($startDate, $endDate){
        $request    = request();

        if($request->channel){
            $channel = explode(',', $request->channel);

            $match = [
                ['$match' => ['submit_time' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => ['$in' => ['c3b','c3bg']]]],
                ['$match' => ['channel_name' => ['$in' => $channel]]],
                [
                    '$group' => [
                        '_id' => ['ad_id' => '$ad_id', 'submit_time' => '$submit_time'],
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
                        '_id' => ['ad_id' => '$ad_id', 'submit_time' => '$submit_time'],
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
            $date   = (int)date('d', @$item['_id']['submit_time'] / 1000);
            $ad_id  = @$item['_id']['ad_id'];

            @$result[$ad_id]['c3b_produce'][$date] += @$item['c3b_produce'];
        }

        return $result;
    }

    private function get_c3_inventory($startDate, $endDate){
        $request    = request();

        if($request->channel){
            $channel = explode(',', $request->channel);
            $match = [
                ['$match' => ['submit_time' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => ['$in' => ['c3b','c3bg']]]],
                ['$match' => ['olm_status' => ['$nin' => [0, 1]]]],
                ['$match' => ['channel_name' => ['$in' => $channel]]],
                ['$match' => ['current_level' => ['$nin' => \config('constants.CURRENT_LEVEL')]]],
                [
                    '$group' => [
                        '_id' => ['ad_id' => '$ad_id', 'submit_time' => '$submit_time'],
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
                ['$match' => ['current_level' => ['$nin' => \config('constants.CURRENT_LEVEL')]]],
                [
                    '$group' => [
                        '_id' => ['ad_id' => '$ad_id', 'submit_time' => '$submit_time'],
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
        foreach ($query as $item){
            $date   = (int)date('d', @$item['_id']['submit_time'] / 1000);
            $ad_id  = @$item['_id']['ad_id'];

            @$result[$ad_id]['c3b_inventory'][$date] += @$item['c3b_inventory'];
        }

        return $result;
    }

    private function get_c3_transfer($startDate, $endDate){
        $request    = request();

        if($request->channel) {
            $channel = explode(',', $request->channel);
            $match = [
                ['$match' => ['export_sale_date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                ['$match' => ['clevel' => ['$in' => ['c3b','c3bg']]]],
                ['$match' => ['olm_status' => ['$in' => [0, 1, 2, 3]]]],
                ['$match' => ['channel_name' => ['$in' => $channel]]],
                [
                    '$group' => [
                        '_id' => ['export_sale_date' => '$export_sale_date', 'ad_id' => '$ad_id'],
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
                ['$match' => ['olm_status' => ['$in' => [0, 1, 2, 3]]]],
                [
                    '$group' => [
                        '_id' => ['export_sale_date' => '$export_sale_date', 'ad_id' => '$ad_id'],
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
        foreach ($query as $item){
            $date   = (int)date('d', @$item['_id']['export_sale_date'] / 1000);
            $ad_id  = @$item['_id']['ad_id'];

            @$result[$ad_id]['c3b_transfer'][$date] += @$item['c3b_transfer'];
        }

        return $result;
    }

    public function prepare_data(){
        $request    = request();
        $month  = date('m'); /* thang hien tai */
        $year   = date('Y'); /* nam hien tai*/

        if($request->month){
            $month = $request->month;
        }

        $days   = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $start  = '01-'.$month.'-'.$year; /* ngày đàu tiên của tháng */
        $end    = $days.'-'.$month.'-'.$year; /* ngày cuối cùng của tháng */

        $startDate  = strtotime($start)*1000;
        $endDate    = strtotime("+1 day", strtotime($end))*1000;

        $c3_produce     = $this->get_c3_produce($startDate, $endDate);
        $c3_transfer    = $this->get_c3_transfer($startDate, $endDate);
        $c3_inventory   = $this->get_c3_inventory($startDate, $endDate);

        if($request->channel){
            $ads = Ad::whereIn('channel_name', explode(',', $request->channel))->get();
        }else{
            $ads = Ad::all();
        }

        $result = array();

        // sum for channels
        foreach ($ads as $ad){

            $source     = $ad->source_name;
            $channel    = $ad->channel_name;
            $ad_id      = $ad->_id;

            if(strtolower($source) == 'unknown' || strtolower($channel) == 'unknown'){
                $source     = 'Unknown';
                $channel    = 'Unknown';
                $ad_id      = 'unknown';
            }

            for($i = 1; $i <= $days; $i++){
                @$result['data'][$source][$channel]['produce'][$i]     = @$c3_produce[$ad_id]['c3b_produce'][$i]       ? @$c3_produce[$ad_id]['c3b_produce'][$i]     : 0;
                @$result['data'][$source][$channel]['transfer'][$i]    = @$c3_transfer[$ad_id]['c3b_transfer'][$i]     ? @$c3_transfer[$ad_id]['c3b_transfer'][$i]   : 0;
                @$result['data'][$source][$channel]['inventory'][$i]   = @$c3_inventory[$ad_id]['c3b_inventory'][$i]   ? @$c3_inventory[$ad_id]['c3b_inventory'][$i]: 0;

                @$result['total_source'][$source]  += @$result['data'][$source][$channel]['inventory'][$i];

                @$result['total_channel'][$source][$channel]   += @$result['data'][$source][$channel]['inventory'][$i];

                @$result['total']['produce'][$i]    += @$result['data'][$source][$channel]['produce'][$i];
                @$result['total']['transfer'][$i]   += @$result['data'][$source][$channel]['transfer'][$i];
                @$result['total']['inventory'][$i]  += @$result['data'][$source][$channel]['inventory'][$i];

                @$result['grand_total']['produce']    += @$result['data'][$source][$channel]['produce'][$i];
                @$result['grand_total']['transfer']   += @$result['data'][$source][$channel]['transfer'][$i];
                @$result['grand_total']['inventory']  += @$result['data'][$source][$channel]['inventory'][$i];
            }
        }
        // sum for sources
        foreach(@$result['data'] as $source => $channel){
            foreach ($channel as $item){
                if(isset($result['day_source'][$source])){
                    for($i = 1; $i <= $days; $i++){
                        $result['day_source'][$source]['produce'][$i] += $item['produce'][$i];
                        $result['day_source'][$source]['transfer'][$i] += $item['transfer'][$i];
                        $result['day_source'][$source]['inventory'][$i] += $item['inventory'][$i];
                    }
                }else{
                    $result['day_source'][$source] = $item;
                }
            }
        }

        return $result;
    }
}
