<?php

namespace App\Http\Controllers;

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

        $days   = $this->get_days_in_month();
        $month  = date('m');
        $year   = date('Y');
        $channel = Channel::all();

        $data = $this->prepare_data();

        return view('pages.inventory_report', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'data',
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

    private function get_c3_produce($mode){

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
                        '_id' => ['channel_name' => '$channel_name', 'submit_time' => '$submit_time', 'source_name' => '$source_name'],
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
        if($mode == 'channel'){

            foreach ($query as $item){
                $date       = date('d', @$item['_id']['submit_time'] / 1000);
                $channel    = @$item['_id']['channel_name'];
                if(isset($result[$date][$channel])){
                    @$result[$date][$channel]['c3b_produce'] += @$item['c3b_produce'];
                }else{
                    @$result[$date][$channel]['c3b_produce'] = @$item['c3b_produce'];
                }

            }
        }else if($mode == 'source'){
            foreach ($query as $item){
                $date       = date('d', @$item['_id']['submit_time'] / 1000);
                $source     = @$item['_id']['source_name'];
                if(isset($result[$date][$source])){
                    @$result[$date][$source]['c3b_produce'] += @$item['c3b_produce'];
                }else{
                    @$result[$date][$source]['c3b_produce'] = @$item['c3b_produce'];
                }

            }
        }

        return $result;

    }

    private function get_c3_inventory($mode){

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
                ['$match' => ['olm_status' => ['$nin' => [0, 1]]]],
                [
                    '$group' => [
                        '_id' => ['channel_name' => '$channel_name', 'submit_time' => '$submit_time', 'source_name' => '$source_name'],
                        'c3b_inventory' => ['$sum' => 1],
                    ]
                ],
                [
                    '$sort' => [
                        'c3b_inventory' => -1,
                    ]
                ]
            ];

            return $collection->aggregate($match);
        });

        $result = array();

        if($mode == 'channel'){
            foreach ($query as $item){
                $date       = date('d', @$item['_id']['submit_time'] / 1000);
                $channel    = @$item['_id']['channel_name'];
                if(isset($result[$date][$channel])){
                    @$result[$date][$channel]['c3b_inventory'] += @$item['c3b_inventory'];
                }else{
                    @$result[$date][$channel]['c3b_inventory'] = @$item['c3b_inventory'];
                }

            }
        }else if ($mode == 'source'){
            foreach ($query as $item){
                $date       = date('d', @$item['_id']['submit_time'] / 1000);
                $source    = @$item['_id']['source_name'];
                if(isset($result[$date][$source])){
                    @$result[$date][$source]['c3b_inventory'] += @$item['c3b_inventory'];
                }else{
                    @$result[$date][$source]['c3b_inventory'] = @$item['c3b_inventory'];
                }

            }
        }

        return $result;

    }

    private function get_c3_transfer($mode){

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
                ['$match' => ['olm_status' => ['$in' => [0, 1]]]],
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
        if($mode == 'channel'){
            foreach ($query as $item){
                $date = 0;
                if(@$item['_id']['export_sale_date']){
                    $date = explode('/', $item['_id']['export_sale_date']);
                }

                $channel    = @$item['_id']['channel_name'];
                if(isset($result[$date[0]][$channel])){
                    @$result[$date[0]][$channel]['c3b_transfer'] += @$item['c3b_transfer'];
                }else{
                    @$result[$date[0]][$channel]['c3b_transfer'] = @$item['c3b_transfer'];
                }

            }
        }else if ($mode == 'source'){
            foreach ($query as $item){
                $date = 0;
                if(@$item['_id']['export_sale_date']){
                    $date = explode('/', $item['_id']['export_sale_date']);
                }

                $source    = @$item['_id']['source_name'];
                if(isset($result[$date[0]][$source])){
                    @$result[$date[0]][$source]['c3b_transfer'] += @$item['c3b_transfer'];
                }else{
                    @$result[$date[0]][$source]['c3b_transfer'] = @$item['c3b_transfer'];
                }

            }
        }



        return $result;

    }

    public function prepare_data(){
        $request = request();
        $days   = $this->get_days_in_month();

        $c3_produce_channel     = $this->get_c3_produce('channel');
        $c3_transfer_channel    = $this->get_c3_transfer('channel');
        $c3_inventory_channel   = $this->get_c3_inventory('channel');

        $c3_produce_source     = $this->get_c3_produce('source');
        $c3_transfer_source    = $this->get_c3_transfer('source');
        $c3_inventory_source   = $this->get_c3_inventory('source');

        if($request->channel){
            $channels = Channel::whereIn('_id', explode(',', $request->channel))->get();
        }else{
            $channels = Channel::all();
        }

        $sources = Source::all();
        $result = array();
        $label = array();

        foreach ($sources as $source){
            $source_name = $source->name;

            for($i = 1; $i <= $days; $i++){
                @$result['data'][$source_name][$i]['produce']      = @$c3_produce_source[$i][$source_name]['c3b_produce']        ? @$c3_produce_source[$i][$source_name]['c3b_produce']        : 0;
                @$result['data'][$source_name][$i]['transfer']     = @$c3_transfer_source[$i][$source_name]['c3b_transfer']      ? @$c3_transfer_source[$i][$source_name]['c3b_transfer']      : 0;
                @$result['data'][$source_name][$i]['inventory']    = @$c3_inventory_source[$i][$source_name]['c3b_inventory']    ? @$c3_inventory_source[$i][$source_name]['c3b_inventory']    : 0;

                @$result['total_source'][$source_name]    +=  @$result['data'][$source_name][$i]['inventory'];

                @$result['total'][$i]['produce']    +=  @$result['data'][$source_name][$i]['produce'];
                @$result['total'][$i]['transfer']   +=  @$result['data'][$source_name][$i]['transfer'];
                @$result['total'][$i]['inventory']  +=  @$result['data'][$source_name][$i]['inventory'];
            }

        }

        foreach ($channels as $channel){
            $channel_name = $channel->name;

            for($i = 1; $i <= $days; $i++){
                @$result['data'][$channel_name][$i]['produce']      = @$c3_produce_channel[$i][$channel_name]['c3b_produce']        ? @$c3_produce_channel[$i][$channel_name]['c3b_produce']        : 0;
                @$result['data'][$channel_name][$i]['transfer']     = @$c3_transfer_channel[$i][$channel_name]['c3b_transfer']      ? @$c3_transfer_channel[$i][$channel_name]['c3b_transfer']      : 0;
                @$result['data'][$channel_name][$i]['inventory']    = @$c3_inventory_channel[$i][$channel_name]['c3b_inventory']    ? @$c3_inventory_channel[$i][$channel_name]['c3b_inventory']    : 0;

                @$result['total_channel'][$channel_name]    +=  @$result['data'][$channel_name][$i]['inventory'];

                @$result['total'][$i]['produce']    +=  @$result['data'][$channel_name][$i]['produce'];
                @$result['total'][$i]['transfer']   +=  @$result['data'][$channel_name][$i]['transfer'];
                @$result['total'][$i]['inventory']  +=  @$result['data'][$channel_name][$i]['inventory'];

                @$result['grand_total']['produce']      +=  @$result['data'][$channel_name][$i]['produce'];
                @$result['grand_total']['transfer']     +=  @$result['data'][$channel_name][$i]['transfer'];
                @$result['grand_total']['inventory']    +=  @$result['data'][$channel_name][$i]['inventory'];
            }

        }

        foreach ($channels as $channel){
            $channel_name   = $channel->name;
            $source_id      = $channel->source_id;
            $source         = Source::find($source_id);
            $source_name    = 'Other';
            if($source){
                $source_name = $source->name;
            }

            if(isset($label[$source_name])){
                array_push($label[$source_name], $channel_name);
            }else{
                $label[$source_name] = [$channel_name];
            }
        }

        uasort($label, function ($item1, $item2) {
            if ($item1 == $item2) return 0;
            return $item2 < $item1 ? -1 : 1;
        });

        $result['lable'] = $label;

        return $result;
    }

}
