<?php

namespace App\Http\Controllers;

use App\Ad;
use App\AdResult;
use App\Config;
use App\Contact;

class SubReportTOTController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDataTOTByDays(){
        $result = $this->prepareDataByDays();
        return $result;
    }

    public function getDataTOTByWeeks(){
        $result = $this->prepareDataByWeeks();
        return $result;
    }

    public function getDataTOTByMonths(){
        $result = $this->prepareDataByMonths();
        return $result;
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
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        'c3bg'  => ['$sum' => '$c3bg'],
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
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        'c3bg'  => ['$sum' => '$c3bg'],
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $data_tot = $this->getDataTOT($first_day_this_month, $last_day_this_month, $ad_id);

        $tot_array = array();
        foreach ($data_tot as $key => $data){
            $day =  date('d',strtotime($key));
            if(isset($tot_array[$day])){
                $tot_array[$day]->l1 += @$data->l1 ? $data->l1 : 0;
                $tot_array[$day]->l3 += @$data->l3 ? $data->l3 : 0;
                $tot_array[$day]->l6 += @$data->l6 ? $data->l6 : 0;
                $tot_array[$day]->l8 += @$data->l8 ? $data->l8 : 0;
                $tot_array[$day]->re += @$data->re ? $data->re : 0;
            }else{
                @$tot_array[$day]->l1 = @$data->l1 ? $data->l1 : 0;
                @$tot_array[$day]->l3 = @$data->l3 ? $data->l3 : 0;
                @$tot_array[$day]->l6 = @$data->l6 ? $data->l6 : 0;
                @$tot_array[$day]->l8 = @$data->l8 ? $data->l8 : 0;
                @$tot_array[$day]->re = @$data->re ? $data->re : 0;
            }
        }

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

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

                $me         = $item_result['me'] ? $this->convert_spent($item_result['me'])     : 0;
                $re         = @$tot_array[$day[2]]->re != 0 ? $this->convert_revenue(@$tot_array[$day[2]]->re)   : 0;

                $total_me   += $me;
                $total_re   += $re;

                $me_array[(int)($day[2])]   = $me;
                $re_array[(int)($day[2])]   = $re;
                $c3b_array[(int)($day[2])]  = $item_result['c3b']   ? round ($me / $item_result['c3b'], 2)     : 0 ;
                $c3bg_array[(int)($day[2])] = $item_result['c3bg']  ? round ($me / $item_result['c3bg'], 2)    : 0 ;
                $l1_array[(int)($day[2])]   = @$tot_array[$day[2]]->l1 != 0  ? round ($me / $tot_array[$day[2]]->l1, 2)  : 0 ;
                $l3_array[(int)($day[2])]   = @$tot_array[$day[2]]->l3 != 0  ? round ($me / $tot_array[$day[2]]->l3, 2)  : 0 ;
                $l6_array[(int)($day[2])]   = @$tot_array[$day[2]]->l6 != 0  ? round ($me / $tot_array[$day[2]]->l6, 2)  : 0 ;
                $l8_array[(int)($day[2])]   = @$tot_array[$day[2]]->l8 != 0  ? round ($me / $tot_array[$day[2]]->l8, 2)  : 0 ;
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
            $me_result[]    = [$timestamp, isset($me_array[$key])   ? $me_array[$key]   : 0];
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

    private function getDataTOT($start, $end, $ad_id){
        $query_l1 = Contact::raw(function ($collection) use ($start, $end, $ad_id) {
            if(count($ad_id) > 0){
                $match = [
                    ['$match' => ['l1_time' => ['$gte' => $start, '$lte' => $end]]],
                    ['$match' => ['ad_id' => ['$in' => $ad_id]]],
                    [
                        '$group' => [
                            '_id'   => '$l1_time',
                            'l1'    => ['$sum' => 1],
                        ]
                    ]
                ];
            }else{
                $match = [
                    ['$match' => ['l1_time' => ['$gte' => $start, '$lte' => $end]]],
                    [
                        '$group' => [
                            '_id'   => '$l1_time',
                            'l1'    => ['$sum' => 1],
                        ]
                    ]
                ];
            }
            return $collection->aggregate($match);
        });

        $query_l3 = Contact::raw(function ($collection) use ($start, $end, $ad_id) {
            if(count($ad_id) > 0){
                $match = [
                    ['$match' => ['l3_time' => ['$gte' => $start, '$lte' => $end]]],
                    ['$match' => ['ad_id' => ['$in' => $ad_id]]],
                    [
                        '$group' => [
                            '_id' => '$l3_time',
                            'l3' => ['$sum' => 1],
                        ]
                    ]
                ];
            }else{
                $match = [
                    ['$match' => ['l3_time' => ['$gte' => $start, '$lte' => $end]]],
                    [
                        '$group' => [
                            '_id' => '$l3_time',
                            'l3' => ['$sum' => 1],
                        ]
                    ]
                ];
            }
            return $collection->aggregate($match);
        });

        $query_l6 = Contact::raw(function ($collection) use ($start, $end, $ad_id) {
            if(count($ad_id) > 0){
                $match = [
                    ['$match' => ['l6_time' => ['$gte' => $start, '$lte' => $end]]],
                    ['$match' => ['ad_id' => ['$in' => $ad_id]]],
                    [
                        '$group' => [
                            '_id' => '$l6_time',
                            'l6' => ['$sum' => 1],
                        ]
                    ]
                ];
            }else{
                $match = [
                    ['$match' => ['l6_time' => ['$gte' => $start, '$lte' => $end]]],
                    [
                        '$group' => [
                            '_id' => '$l6_time',
                            'l6' => ['$sum' => 1],
                        ]
                    ]
                ];
            }
            return $collection->aggregate($match);
        });

        $query_l8 = Contact::raw(function ($collection) use ($start, $end, $ad_id) {
            if(count($ad_id) > 0){
                $match = [
                    ['$match' => ['l8_time' => ['$gte' => $start, '$lte' => $end]]],
                    ['$match' => ['ad_id' => ['$in' => $ad_id]]],
                    [
                        '$group' => [
                            '_id' => '$l8_time',
                            'l8' => ['$sum' => 1],
                            're' => ['$sum' => '$revenue'],
                        ]
                    ]
                ];
            }else{
                $match = [
                    ['$match' => ['l8_time' => ['$gte' => $start, '$lte' => $end]]],
                    [
                        '$group' => [
                            '_id' => '$l8_time',
                            'l8' => ['$sum' => 1],
                            're' => ['$sum' => '$revenue'],
                        ]
                    ]
                ];
            }
            return $collection->aggregate($match);
        });

        $result = array();
        foreach ($query_l1 as $key => $item){
            if(isset($result[$item->id]->l1)){
                $result[$item->id]->l1 += $item->l1;
            }else{
                @$result[$item->id]->l1 = $item->l1;
            }
        }
        foreach ($query_l3 as $key => $item){
            if(isset($result[$item->id]->l3)){
                $result[$item->id]->l3 += $item->l3;
            }else {
                @$result[$item->id]->l3 = $item->l3;
            }
        }
        foreach ($query_l6 as $key => $item){
            if(isset($result[$item->id]->l6)){
                $result[$item->id]->l6 += $item->l6;
            }else {
                @$result[$item->id]->l6 = $item->l6;
            }
        }
        foreach ($query_l8 as $key => $item){
            if(isset($result[$item->id]->l8)){
                $result[$item->id]->l8 += $item->l8;
                $result[$item->id]->re += $item->re;
            }else{
                @$result[$item->id]->l8 = $item->l8;
                @$result[$item->id]->re = $item->re;
            }
        }

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
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        'c3bg'  => ['$sum' => '$c3bg'],
                    ]
                ]
                ];
        }else{
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        'c3bg'  => ['$sum' => '$c3bg'],
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $data_tot = $this->getDataTOT($first_day_this_month, $last_day_this_month, $ad_id);

        $tot_array = array();
        foreach ($data_tot as $key => $data){
            $day =  date('d',strtotime($key));
            if(isset($tot_array[$day])){
                $tot_array[$day]->l1 += @$data->l1 ? $data->l1 : 0;
                $tot_array[$day]->l3 += @$data->l3 ? $data->l3 : 0;
                $tot_array[$day]->l6 += @$data->l6 ? $data->l6 : 0;
                $tot_array[$day]->l8 += @$data->l8 ? $data->l8 : 0;
            }else{
                @$tot_array[$day]->l1 = @$data->l1 ? $data->l1 : 0;
                @$tot_array[$day]->l3 = @$data->l3 ? $data->l3 : 0;
                @$tot_array[$day]->l6 = @$data->l6 ? $data->l6 : 0;
                @$tot_array[$day]->l8 = @$data->l8 ? $data->l8 : 0;
            }
        }

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
            $l1_array[(int)($day[2])]   = @$tot_array[$day[2]]->l1 ? $tot_array[$day[2]]->l1 : 0;
            $l3_array[(int)($day[2])]   = @$tot_array[$day[2]]->l3 ? $tot_array[$day[2]]->l3 : 0;
            $l6_array[(int)($day[2])]   = @$tot_array[$day[2]]->l6 ? $tot_array[$day[2]]->l6 : 0;
            $l8_array[(int)($day[2])]   = @$tot_array[$day[2]]->l8 ? $tot_array[$day[2]]->l8 : 0;
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
                        'c3'    => ['$sum' => ['$sum' => ['$c3a', '$c3b', '$c3bg']]],
                        'c3a'   => ['$sum' => '$c3a'],
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        'c3bg'  => ['$sum' => '$c3bg'],
                    ]
                ]
            ];
        }else{
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3'    => ['$sum' => ['$sum' => ['$c3a', '$c3b', '$c3bg']]],
                        'c3a'   => ['$sum' => '$c3a'],
                        'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        'c3bg'  => ['$sum' => '$c3bg'],
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $data_tot = $this->getDataTOT($first_day_this_month, $last_day_this_month, $ad_id);

        $tot_array = array();
        foreach ($data_tot as $key => $data){
            $day =  date('d',strtotime($key));
            if(isset($tot_array[$day])){
                $tot_array[$day]->l1 += @$data->l1 ? $data->l1 : 0;
                $tot_array[$day]->l3 += @$data->l3 ? $data->l3 : 0;
                $tot_array[$day]->l6 += @$data->l6 ? $data->l6 : 0;
                $tot_array[$day]->l8 += @$data->l8 ? $data->l8 : 0;
            }else{
                @$tot_array[$day]->l1 = @$data->l1 ? $data->l1 : 0;
                @$tot_array[$day]->l3 = @$data->l3 ? $data->l3 : 0;
                @$tot_array[$day]->l6 = @$data->l6 ? $data->l6 : 0;
                @$tot_array[$day]->l8 = @$data->l8 ? $data->l8 : 0;
            }
        }

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
        $c3a_c3_array   = array();

        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);

            $l3_c3b_array[(int)($day[2])]  = $item_result['c3b'] ?
                round(@$tot_array[$day[2]]->l3 / $item_result['c3b'],2) * 100 : 0;
            $l3_c3bg_array[(int)($day[2])]  = $item_result['c3bg'] ?
                round(@$tot_array[$day[2]]->l3 / $item_result['c3bg'],2) * 100 : 0;
            $l3_l1_array[(int)($day[2])]  = @$tot_array[$day[2]]->l1 ?
                round(@$tot_array[$day[2]]->l3 / @$tot_array[$day[2]]->l1,2) * 100 : 0;
            $l1_c3bg_array[(int)($day[2])]  = $item_result['c3bg'] ?
                round(@$tot_array[$day[2]]->l1 / $item_result['c3bg'],2) * 100 : 0;
            $c3bg_c3b_array[(int)($day[2])]  = $item_result['c3b'] ?
                round($item_result['c3bg'] / $item_result['c3b'],2) * 100 : 0;
            $l6_l3_array[(int)($day[2])]  = @$tot_array[$day[2]]->l3 ?
                round(@$tot_array[$day[2]]->l6 / @$tot_array[$day[2]]->l3,2) * 100 : 0;
            $l8_l6_array[(int)($day[2])]  = @$tot_array[$day[2]]->l6 ?
                round(@$tot_array[$day[2]]->l8 / @$tot_array[$day[2]]->l6,2) * 100 : 0;
            $c3a_c3_array[(int)($day[2])]  = $item_result['c3'] ?
                round($item_result['c3a'] / $item_result['c3'],2) * 100 : 0;
        }

        $l3_c3b_result   = array();
        $l3_c3bg_result  = array();
        $l3_l1_result    = array();
        $l1_c3bg_result  = array();
        $c3bg_c3b_result = array();
        $l6_l3_result    = array();
        $l8_l6_result    = array();
        $c3a_c3_result   = array();

        foreach ($array_month as $key => $timestamp) {
            $l3_c3b_result[]    = [$timestamp, isset($l3_c3b_array[$key])   ? $l3_c3b_array[$key]   : 0];
            $l3_c3bg_result[]   = [$timestamp, isset($l3_c3bg_array[$key])  ? $l3_c3bg_array[$key]  : 0];
            $l3_l1_result[]     = [$timestamp, isset($l3_l1_array[$key])    ? $l3_l1_array[$key]    : 0];
            $l1_c3bg_result[]   = [$timestamp, isset($l1_c3bg_array[$key])  ? $l1_c3bg_array[$key]  : 0];
            $c3bg_c3b_result[]  = [$timestamp, isset($c3bg_c3b_array[$key]) ? $c3bg_c3b_array[$key] : 0];
            $l6_l3_result[]     = [$timestamp, isset($l6_l3_array[$key])    ? $l6_l3_array[$key]    : 0];
            $l8_l6_result[]     = [$timestamp, isset($l8_l6_array[$key])    ? $l8_l6_array[$key]    : 0];
            $c3a_c3_result[]    = [$timestamp, isset($c3a_c3_array[$key])   ? $c3a_c3_array[$key]   : 0];
        }

        $result = array();
        $result['l3_c3b']   = json_encode($l3_c3b_result);
        $result['l3_c3bg']  = json_encode($l3_c3bg_result);
        $result['l3_l1']    = json_encode($l3_l1_result);
        $result['l1_c3bg']  = json_encode($l1_c3bg_result);
        $result['c3bg_c3b'] = json_encode($c3bg_c3b_result);
        $result['l6_l3']    = json_encode($l6_l3_result);
        $result['l8_l6']    = json_encode($l8_l6_result);
        $result['c3a_c3']   = json_encode($c3a_c3_result);

        return $result;
    }

	public function getC3AC3B($quality_month = null){

		// get start date and end date
		list($year, $month, $d, $first_day_this_month, $last_day_this_month) = $this->getDate($quality_month);

		// get Ad id
		$ad_id  = $this->getAds();

		$array_month = array();
		for ($i = 1; $i <= $d; $i++) {
			//$array_month[date($i)] = 0;
			$timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
			$array_month[$i] = $timestamp;
		}

		$array_reason = [ 'C3A_Duplicated', 'C3B_Under18', 'C3B_Duplicated15Days', 'C3A_Test' ];
		$rs = [];

		if(count($ad_id) > 0){
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

	private function getWhereDataByCreatorID(){
        $request    = request();
        $data_where = array();
        if ($request->source_id) {
            $data_where['source_id']        = $request->source_id;
        }
        if ($request->team_id) {
            $data_where['team_id']          = $request->team_id;
        }
        if ($request->marketer_id) {
            $data_where['creator_id']      = $request->marketer_id;
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
        $data_where = $this->getWhereDataByCreatorID();
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

    public function prepareDataByWeeks(){
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
                        'c3'    => ['$sum' => ['$sum' => ['$c3a', '$c3b', '$c3bg']]],
                        'c3a'   => ['$sum' => '$c3a'],
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
                ['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3'    => ['$sum' => ['$sum' => ['$c3a', '$c3b', '$c3bg']]],
                        'c3a'   => ['$sum' => '$c3a'],
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

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $type = \request('type');

        $data_tot = $this->getDataTOT($start_date, $end_date, $ad_id);

        $tot_array = array();
        foreach ($data_tot as $key => $data){
            $week = $this->getWeek($key);
            if(isset($tot_array[$week])){
                $tot_array[$week]->l1 += @$data->l1 ? $data->l1 : 0;
                $tot_array[$week]->l3 += @$data->l3 ? $data->l3 : 0;
                $tot_array[$week]->l6 += @$data->l6 ? $data->l6 : 0;
                $tot_array[$week]->l8 += @$data->l8 ? $data->l8 : 0;
                $tot_array[$week]->re += @$data->re ? $data->re : 0;
            }else{
                @$tot_array[$week]->l1 = @$data->l1 ? $data->l1 : 0;
                @$tot_array[$week]->l3 = @$data->l3 ? $data->l3 : 0;
                @$tot_array[$week]->l6 = @$data->l6 ? $data->l6 : 0;
                @$tot_array[$week]->l8 = @$data->l8 ? $data->l8 : 0;
                @$tot_array[$week]->re = @$data->re ? $data->re : 0;
            }
        }

        if ($type == 'budget') {
            $result['budget']   = $this->getBudgetByWeeks($query_chart, $tot_array, $w);
        }
        else if ($type == 'quantity') {
            $result['quantity'] = $this->getQuantityByWeeks($query_chart, $tot_array, $w);
        }
        else if ($type == 'quality') {
            $result['quality']  = $this->getQualityByWeeks($query_chart, $tot_array, $w);
        }
        else if ($type == 'C3AC3B') {
	        $result['C3AC3B']  = $this->getC3AC3BByWeeks($start_date, $end_date, $w);
        } else {
            $result['budget']   = $this->getBudgetByWeeks($query_chart, $tot_array, $w);
            $result['quantity'] = $this->getQuantityByWeeks($query_chart, $tot_array, $w);
            $result['quality']  = $this->getQualityByWeeks($query_chart, $tot_array, $w);
	        $result['C3AC3B']   = $this->getC3AC3BByWeeks($start_date, $end_date, $w);
        }

        return $result;
    }

    private function getBudgetByWeeks($query_chart, $tot_array, $w){

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

            $me         = $item_result['me']    ? $this->convert_spent($item_result['me'])      : 0;
            $re         = @$tot_array[$week]->re ? $this->convert_revenue($tot_array[$week]->re)  : 0;

            $total_me   += $me;
            $total_re   += $re;

            @$me_array[$week]   += $me;
            @$re_array[$week]   += $re;
            @$c3b_array[$week]  += $item_result['c3b']  ? round($me / $item_result['c3b'], 2)   : 0 ;
            @$c3bg_array[$week] += $item_result['c3bg'] ? round($me / $item_result['c3bg'], 2)  : 0 ;
            @$l1_array[$week]   += @$tot_array[$week]->l1 ? round($me / $tot_array[$week]->l1, 2)  : 0 ;
            @$l3_array[$week]   += @$tot_array[$week]->l3 ? round($me / $tot_array[$week]->l3, 2)  : 0 ;
            @$l6_array[$week]   += @$tot_array[$week]->l6 ? round($me / $tot_array[$week]->l6, 2)  : 0 ;
            @$l8_array[$week]   += @$tot_array[$week]->l8 ? round($me / $tot_array[$week]->l8, 2)  : 0 ;
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

    private function getQuantityByWeeks($query_chart, $tot_array, $w){

        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();

        foreach ($query_chart as $item_result) {
            $week = $this->getWeek($item_result['_id']);

            @$c3b_array[$week]  += $item_result['c3b']      ? $item_result['c3b']   : 0 ;
            @$c3bg_array[$week] += $item_result['c3bg']     ? $item_result['c3bg']  : 0 ;
            @$l1_array[$week]   += @$tot_array[$week]->l1   ? $tot_array[$week]->l1 : 0 ;
            @$l3_array[$week]   += @$tot_array[$week]->l3   ? $tot_array[$week]->l3 : 0 ;
            @$l6_array[$week]   += @$tot_array[$week]->l6   ? $tot_array[$week]->l6 : 0 ;
            @$l8_array[$week]   += @$tot_array[$week]->l8   ? $tot_array[$week]->l8 : 0 ;
        }

        $c3b_result  = array();
        $c3bg_result = array();
        $l1_result   = array();
        $l3_result   = array();
        $l6_result   = array();
        $l8_result   = array();

        for ($i = 1; $i <= $w; $i++) {
            $c3b_result[]   = [$i, isset($c3b_array[$i])  ? $c3b_array[$i]  : 0];
            $c3bg_result[]  = [$i, isset($c3bg_array[$i]) ? $c3bg_array[$i] : 0];
            $l1_result[]    = [$i, isset($l1_array[$i])   ? $l1_array[$i]   : 0];
            $l3_result[]    = [$i, isset($l3_array[$i])   ? $l3_array[$i]   : 0];
            $l6_result[]    = [$i, isset($l6_array[$i])   ? $l6_array[$i]   : 0];
            $l8_result[]    = [$i, isset($l8_array[$i])   ? $l8_array[$i]   : 0];
        }

        $result = array();
        $result['c3b']      = json_encode($c3b_result);
        $result['c3bg']     = json_encode($c3bg_result);
        $result['l1']       = json_encode($l1_result);
        $result['l3']       = json_encode($l3_result);
        $result['l6']       = json_encode($l6_result);
        $result['l8']       = json_encode($l8_result);

        return $result;
    }

    private function getTotalDataByWeeks($query_chart, $tot_array, $w){
        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();
        $c3_array   = array();
        $c3a_array  = array();

        foreach ($query_chart as $item_result) {
            $week = $this->getWeek($item_result['_id']);

            @$c3b_array[$week]  += $item_result['c3b']      ? $item_result['c3b']   : 0 ;
            @$c3bg_array[$week] += $item_result['c3bg']     ? $item_result['c3bg']  : 0 ;
            @$l1_array[$week]   += @$tot_array[$week]->l1   ? $tot_array[$week]->l1 : 0 ;
            @$l3_array[$week]   += @$tot_array[$week]->l3   ? $tot_array[$week]->l3 : 0 ;
            @$l6_array[$week]   += @$tot_array[$week]->l6   ? $tot_array[$week]->l6 : 0 ;
            @$l8_array[$week]   += @$tot_array[$week]->l8   ? $tot_array[$week]->l8 : 0 ;
            @$c3_array[$week]   += $item_result['c3']       ? $item_result['c3']    : 0 ;
            @$c3a_array[$week]  += $item_result['c3a']      ? $item_result['c3a']   : 0 ;
        }

        $c3b_result  = array();
        $c3bg_result = array();
        $l1_result   = array();
        $l3_result   = array();
        $l6_result   = array();
        $l8_result   = array();
        $c3_result   = array();
        $c3a_result  = array();

        for ($i = 1; $i <= $w; $i++) {
            $c3b_result[]   = [$i, isset($c3b_array[$i])  ? $c3b_array[$i]  : 0];
            $c3bg_result[]  = [$i, isset($c3bg_array[$i]) ? $c3bg_array[$i] : 0];
            $l1_result[]    = [$i, isset($l1_array[$i])   ? $l1_array[$i]   : 0];
            $l3_result[]    = [$i, isset($l3_array[$i])   ? $l3_array[$i]   : 0];
            $l6_result[]    = [$i, isset($l6_array[$i])   ? $l6_array[$i]   : 0];
            $l8_result[]    = [$i, isset($l8_array[$i])   ? $l8_array[$i]   : 0];
            $c3_result[]    = [$i, isset($c3_array[$i])   ? $c3_array[$i]   : 0];
            $c3a_result[]   = [$i, isset($c3a_array[$i])  ? $c3a_array[$i]  : 0];
        }

        $result = array();
        $result['c3b']      = $c3b_result;
        $result['c3bg']     = $c3bg_result;
        $result['l1']       = $l1_result;
        $result['l3']       = $l3_result;
        $result['l6']       = $l6_result;
        $result['l8']       = $l8_result;
        $result['c3']       = $c3_result;
        $result['c3a']      = $c3a_result;

        return $result;
    }

    private function getQualityByWeeks($query_chart, $tot_array, $w){

        $total = $this->getTotalDataByWeeks($query_chart, $tot_array, $w);

        $l3_c3b_array   = array();
        $l3_c3bg_array  = array();
        $l3_l1_array    = array();
        $l1_c3bg_array  = array();
        $c3bg_c3b_array = array();
        $l6_l3_array    = array();
        $l8_l6_array    = array();
        $c3a_c3_array   = array();

        for ($i = 0; $i < $w; $i++) {
            $cnt = $i + 1;

            $l3_c3b_array[$cnt]     = $total['c3b'][$i][1] ?
                round($total['l3'][$i][1] / $total['c3b'][$i][1],2) * 100 : 0;
            $l3_c3bg_array[$cnt]    = $total['c3bg'][$i][1] ?
                round($total['l3'][$i][1] / $total['c3bg'][$i][1],2) * 100 : 0;
            $l3_l1_array[$cnt]      = $total['l1'][$i][1] ?
                round($total['l3'][$i][1] / $total['l1'][$i][1],2) * 100 : 0;
            $l1_c3bg_array[$cnt]    = $total['c3bg'][$i][1] ?
                round($total['l1'][$i][1] / $total['c3bg'][$i][1],2) * 100 : 0;
            $c3bg_c3b_array[$cnt]   = $total['c3b'][$i][1] ?
                round($total['c3bg'][$i][1] / $total['c3b'][$i][1],2) * 100 : 0;
            $l6_l3_array[$cnt]      = $total['l3'][$i][1] ?
                round($total['l6'][$i][1] / $total['l3'][$i][1],2) * 100 : 0;
            $l8_l6_array[$cnt]      = $total['l6'][$i][1] ?
                round($total['l8'][$i][1] / $total['l6'][$i][1],2) * 100 : 0;
            $c3a_c3_array[$cnt]      = $total['c3'][$i][1] ?
                round($total['c3a'][$i][1] / $total['c3'][$i][1],2) * 100 : 0;
        }

        $l3_c3b_result   = array();
        $l3_c3bg_result  = array();
        $l3_l1_result    = array();
        $l1_c3bg_result  = array();
        $c3bg_c3b_result = array();
        $l6_l3_result    = array();
        $l8_l6_result    = array();
        $c3a_c3_result   = array();

        for ($i = 1; $i <= $w; $i++) {
            $l3_c3b_result[]    = [$i, isset($l3_c3b_array[$i])   ? $l3_c3b_array[$i]   : 0];
            $l3_c3bg_result[]   = [$i, isset($l3_c3bg_array[$i])  ? $l3_c3bg_array[$i]  : 0];
            $l3_l1_result[]     = [$i, isset($l3_l1_array[$i])    ? $l3_l1_array[$i]    : 0];
            $l1_c3bg_result[]   = [$i, isset($l1_c3bg_array[$i])  ? $l1_c3bg_array[$i]  : 0];
            $c3bg_c3b_result[]  = [$i, isset($c3bg_c3b_array[$i]) ? $c3bg_c3b_array[$i] : 0];
            $l6_l3_result[]     = [$i, isset($l6_l3_array[$i])    ? $l6_l3_array[$i]    : 0];
            $l8_l6_result[]     = [$i, isset($l8_l6_array[$i])    ? $l8_l6_array[$i]    : 0];
            $c3a_c3_result[]    = [$i, isset($c3a_c3_array[$i])   ? $c3a_c3_array[$i]   : 0];
        }

        $result = array();
        $result['l3_c3b']   = json_encode($l3_c3b_result);
        $result['l3_c3bg']  = json_encode($l3_c3bg_result);
        $result['l3_l1']    = json_encode($l3_l1_result);
        $result['l1_c3bg']  = json_encode($l1_c3bg_result);
        $result['c3bg_c3b'] = json_encode($c3bg_c3b_result);
        $result['l6_l3']    = json_encode($l6_l3_result);
        $result['l8_l6']    = json_encode($l8_l6_result);
        $result['c3a_c3']   = json_encode($c3a_c3_result);

        return $result;
    }

	private function getC3AC3BByWeeks($start_date, $end_date, $w){
		$ad_id  = $this->getAds();

		$array_reason = [ 'C3A_Duplicated', 'C3B_Under18', 'C3B_Duplicated15Days', 'C3A_Test' ];
		$rs = [];

		if(count($ad_id) > 0){
			$match = [
				['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
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
				['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
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

		$query_chart = AdResult::raw(function ($collection) use ($match) {
			return $collection->aggregate($match);
		});

		for ($week = 1; $week <= $w; $week++) {
			$rs['c3'][ $week ] = 0;
			$rs['C3A_Duplicated'][ $week ] = 0;
			$rs['C3B_Under18'][ $week ] = 0;
			$rs['C3B_Duplicated15Days'][ $week ] = 0;
			$rs['C3A_Test'][ $week ] = 0;
		}

		foreach ( $query_chart as $item_result ) {
			$week = $this->getWeek($item_result['_id']);

			$rs['c3'][ $week ]                   += $item_result['c3'];
			$rs['C3A_Duplicated'][ $week ]       += $item_result['C3A_Duplicated'] ;
			$rs['C3B_Under18'][ $week ]          += $item_result['C3B_Under18'];
			$rs['C3B_Duplicated15Days'][ $week ] += $item_result['C3B_Duplicated15Days'];
			$rs['C3A_Test'][ $week ]             += $item_result['C3A_Test'];
		}

		$chart = [];
		$result = [];

		foreach ( $array_reason as $reason ) {
			for ($week = 1; $week <= $w; $week++) {
				$chart[ $reason ][] = [
					$week,
					isset( $rs[ $reason ][ $week ] ) ? $rs[ $reason ][ $week ] : 0,
				];
			}
			$result[$reason] = json_encode($chart[ $reason ]);
		}

		$result['c3']                   = json_encode($rs['c3']);

		return $result;
	}

	private function getC3AC3BByMonths($start_date, $end_date){
		$ad_id  = $this->getAds();

		$array_reason = [ 'C3A_Duplicated', 'C3B_Under18', 'C3B_Duplicated15Days', 'C3A_Test' ];
		$rs = [];

		if(count($ad_id) > 0){
			$match = [
				['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
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
				['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
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

		$query_chart = AdResult::raw(function ($collection) use ($match) {
			return $collection->aggregate($match);
		});

		for ($month = 1; $month <= 12; $month++) {
			$rs['c3'][ $month ] = 0;
			$rs['C3A_Duplicated'][ $month ] = 0;
			$rs['C3B_Under18'][ $month ] = 0;
			$rs['C3B_Duplicated15Days'][ $month ] = 0;
			$rs['C3A_Test'][ $month ] = 0;
		}

		foreach ( $query_chart as $item_result ) {
			$month = $this->getMonths($item_result['_id']);

			$rs['c3'][ $month ]                   += $item_result['c3'];
			$rs['C3A_Duplicated'][ $month ]       += $item_result['C3A_Duplicated'] ;
			$rs['C3B_Under18'][ $month ]          += $item_result['C3B_Under18'];
			$rs['C3B_Duplicated15Days'][ $month ] += $item_result['C3B_Duplicated15Days'];
			$rs['C3A_Test'][ $month ]             += $item_result['C3A_Test'];
		}

		$chart = [];
		$result = [];

		foreach ( $array_reason as $reason ) {
			for ($month = 1; $month <= 12; $month++) {
				$chart[ $reason ][] = [
					$month,
					isset( $rs[ $reason ][ $month ] ) ? $rs[ $reason ][ $month ] : 0,
				];
			}
			$result[$reason] = json_encode($chart[ $reason ]);
		}

		$result['c3']                   = json_encode($rs['c3']);

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

    private function getMonths( $date = null ){
        $month = date('m',  strtotime($date));
        return (int)$month;
    }

    private function prepareDataByDays(){
        $request        = request();
        $budget_month   = $request->budget_month;
        $quantity_month = $request->quantity_month;
        $quality_month  = $request->quality_month;
        $C3AC3B_month   = $request->C3AC3B_month;

        $budget     = $this->getBudget($budget_month);
        $quantity   = $this->getQuantity($quantity_month);
        $quality    = $this->getQuality($quality_month);
        $C3AC3B     = $this->getC3AC3B($C3AC3B_month);

        $result['budget']   = $budget;
        $result['quantity'] = $quantity;
        $result['quality']  = $quality;
        $result['C3AC3B']  = $C3AC3B;

        return $result;
    }

    public function prepareDataByMonths(){
        // get start date and end date
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
                        'c3'    => ['$sum' => ['$sum' => ['$c3a', '$c3b', '$c3bg']]],
                        'c3a'   => ['$sum' => '$c3a'],
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
                ['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3'    => ['$sum' => ['$sum' => ['$c3a', '$c3b', '$c3bg']]],
                        'c3a'   => ['$sum' => '$c3a'],
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

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($match) {
            return $collection->aggregate($match);
        });

        $data_tot = $this->getDataTOT($start_date, $end_date, $ad_id);

        $tot_array = array();
        foreach ($data_tot as $key => $data){
            $month = $this->getMonths($key);
            if(isset($tot_array[$month])){
                $tot_array[$month]->l1 += @$data->l1 ? $data->l1 : 0;
                $tot_array[$month]->l3 += @$data->l3 ? $data->l3 : 0;
                $tot_array[$month]->l6 += @$data->l6 ? $data->l6 : 0;
                $tot_array[$month]->l8 += @$data->l8 ? $data->l8 : 0;
                $tot_array[$month]->re += @$data->re ? $data->re : 0;
            }else{
                @$tot_array[$month]->l1 = @$data->l1 ? $data->l1 : 0;
                @$tot_array[$month]->l3 = @$data->l3 ? $data->l3 : 0;
                @$tot_array[$month]->l6 = @$data->l6 ? $data->l6 : 0;
                @$tot_array[$month]->l8 = @$data->l8 ? $data->l8 : 0;
                @$tot_array[$month]->re = @$data->re ? $data->re : 0;
            }
        }

        $type = \request('type');

        if ($type == 'budget') {
            $result['budget']   = $this->getBudgetByMonths($query_chart, $tot_array);
        }
        else if ($type == 'quantity') {
            $result['quantity'] = $this->getQuantityByMonths($query_chart, $tot_array);
        }
        else if ($type == 'quality') {
            $result['quality']  = $this->getQualityByMonths($query_chart, $tot_array);
        }
        else if ($type == 'C3AC3B') {
	        $result['C3AC3B']  = $this->getC3AC3BByMonths($start_date, $end_date);
        } else {
	        $result['budget']   = $this->getBudgetByMonths($query_chart, $tot_array);
            $result['quantity'] = $this->getQuantityByMonths($query_chart, $tot_array);
            $result['quality']  = $this->getQualityByMonths($query_chart, $tot_array);
            $result['C3AC3B']  = $this->getC3AC3BByMonths($start_date, $end_date);
        }

        return $result;
    }

    private function getBudgetByMonths($query_chart, $tot_array){

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
            $month = $this->getMonths($item_result['_id']);

            $me         = $item_result['me']        ? $this->convert_spent($item_result['me'])          : 0;
            $re         = @$tot_array[$month]->re   ? $this->convert_revenue($tot_array[$month]->re)    : 0;

            $total_me   += $me;
            $total_re   += $re;

            @$me_array[$month]   += $me;
            @$re_array[$month]   += $re;
            @$c3b_array[$month]  += $item_result['c3b']     ? round($me / $item_result['c3b'], 2)       : 0 ;
            @$c3bg_array[$month] += $item_result['c3bg']    ? round($me / $item_result['c3bg'], 2)      : 0 ;
            @$l1_array[$month]   += @$tot_array[$month]->l1 ? round($me / $tot_array[$month]->l1, 2)    : 0 ;
            @$l3_array[$month]   += @$tot_array[$month]->l3 ? round($me / $tot_array[$month]->l3, 2)    : 0 ;
            @$l6_array[$month]   += @$tot_array[$month]->l6 ? round($me / $tot_array[$month]->l6, 2)    : 0 ;
            @$l8_array[$month]   += @$tot_array[$month]->l8 ? round($me / $tot_array[$month]->l8, 2)    : 0 ;
        }

        $me_result   = array();
        $re_result   = array();
        $c3b_result  = array();
        $c3bg_result = array();
        $l1_result   = array();
        $l3_result   = array();
        $l6_result   = array();
        $l8_result   = array();

        for ($i = 1; $i <= 12; $i++) {

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

    private function getQuantityByMonths($query_chart, $tot_array){

        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();

        foreach ($query_chart as $item_result) {
            $month = $this->getMonths($item_result['_id']);

            @$c3b_array[$month]  += $item_result['c3b']     ? $item_result['c3b']       : 0 ;
            @$c3bg_array[$month] += $item_result['c3bg']    ? $item_result['c3bg']      : 0 ;
            @$l1_array[$month]   += @$tot_array[$month]->l1 ? $tot_array[$month]->l1    : 0 ;
            @$l3_array[$month]   += @$tot_array[$month]->l3 ? $tot_array[$month]->l3    : 0 ;
            @$l6_array[$month]   += @$tot_array[$month]->l6 ? $tot_array[$month]->l6    : 0 ;
            @$l8_array[$month]   += @$tot_array[$month]->l8 ? $tot_array[$month]->l8    : 0 ;
        }

        $c3b_result  = array();
        $c3bg_result = array();
        $l1_result   = array();
        $l3_result   = array();
        $l6_result   = array();
        $l8_result   = array();

        for ($i = 1; $i <= 12; $i++) {
            $c3b_result[]   = [$i, isset($c3b_array[$i])  ? $c3b_array[$i]  : 0];
            $c3bg_result[]  = [$i, isset($c3bg_array[$i]) ? $c3bg_array[$i] : 0];
            $l1_result[]    = [$i, isset($l1_array[$i])   ? $l1_array[$i]   : 0];
            $l3_result[]    = [$i, isset($l3_array[$i])   ? $l3_array[$i]   : 0];
            $l6_result[]    = [$i, isset($l6_array[$i])   ? $l6_array[$i]   : 0];
            $l8_result[]    = [$i, isset($l8_array[$i])   ? $l8_array[$i]   : 0];
        }

        $result = array();
        $result['c3b']      = json_encode($c3b_result);
        $result['c3bg']     = json_encode($c3bg_result);
        $result['l1']       = json_encode($l1_result);
        $result['l3']       = json_encode($l3_result);
        $result['l6']       = json_encode($l6_result);
        $result['l8']       = json_encode($l8_result);

        return $result;
    }

    private function getTotalDataByMonths($query_chart, $tot_array){
        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();
        $c3a_array  = array();
        $c3_array   = array();

        foreach ($query_chart as $item_result) {
            $month = $this->getMonths($item_result['_id']);

            @$c3b_array[$month]  += $item_result['c3b']     ? $item_result['c3b']       : 0 ;
            @$c3bg_array[$month] += $item_result['c3bg']    ? $item_result['c3bg']      : 0 ;
            @$l1_array[$month]   += @$tot_array[$month]->l1 ? $tot_array[$month]->l1    : 0 ;
            @$l3_array[$month]   += @$tot_array[$month]->l3 ? $tot_array[$month]->l3    : 0 ;
            @$l6_array[$month]   += @$tot_array[$month]->l6 ? $tot_array[$month]->l6    : 0 ;
            @$l8_array[$month]   += @$tot_array[$month]->l8 ? $tot_array[$month]->l8    : 0 ;
            @$c3a_array[$month]  += $item_result['c3a']     ? $item_result['c3a']       : 0 ;
            @$c3_array[$month]   += $item_result['c3']      ? $item_result['c3']        : 0 ;
        }

        $c3b_result  = array();
        $c3bg_result = array();
        $l1_result   = array();
        $l3_result   = array();
        $l6_result   = array();
        $l8_result   = array();
        $c3a_result  = array();
        $c3_result   = array();

        for ($i = 1; $i <= 12; $i++) {
            $c3b_result[]   = [$i, isset($c3b_array[$i])  ? $c3b_array[$i]  : 0];
            $c3bg_result[]  = [$i, isset($c3bg_array[$i]) ? $c3bg_array[$i] : 0];
            $l1_result[]    = [$i, isset($l1_array[$i])   ? $l1_array[$i]   : 0];
            $l3_result[]    = [$i, isset($l3_array[$i])   ? $l3_array[$i]   : 0];
            $l6_result[]    = [$i, isset($l6_array[$i])   ? $l6_array[$i]   : 0];
            $l8_result[]    = [$i, isset($l8_array[$i])   ? $l8_array[$i]   : 0];
            $c3a_result[]   = [$i, isset($c3a_array[$i])  ? $c3a_array[$i]  : 0];
            $c3_result[]    = [$i, isset($c3_array[$i])   ? $c3_array[$i]   : 0];
        }

        $result = array();
        $result['c3b']      = $c3b_result;
        $result['c3bg']     = $c3bg_result;
        $result['l1']       = $l1_result;
        $result['l3']       = $l3_result;
        $result['l6']       = $l6_result;
        $result['l8']       = $l8_result;
        $result['c3a']      = $c3a_result;
        $result['c3']       = $c3_result;

        return $result;
    }

    private function getQualityByMonths($query_chart, $tot_array){

        $total = $this->getTotalDataByMonths($query_chart, $tot_array);

        $l3_c3b_array   = array();
        $l3_c3bg_array  = array();
        $l3_l1_array    = array();
        $l1_c3bg_array  = array();
        $c3bg_c3b_array = array();
        $l6_l3_array    = array();
        $l8_l6_array    = array();
        $c3a_c3_array   = array();

        for ($i = 0; $i < 12; $i++) {
            $cnt = $i + 1;

            $l3_c3b_array[$cnt]     = $total['c3b'][$i][1] ?
                round($total['l3'][$i][1] / $total['c3b'][$i][1],2) * 100 : 0;
            $l3_c3bg_array[$cnt]    = $total['c3bg'][$i][1] ?
                round($total['l3'][$i][1] / $total['c3bg'][$i][1],2) * 100 : 0;
            $l3_l1_array[$cnt]      = $total['l1'][$i][1] ?
                round($total['l3'][$i][1] / $total['l1'][$i][1],2) * 100 : 0;
            $l1_c3bg_array[$cnt]    = $total['c3bg'][$i][1] ?
                round($total['l1'][$i][1] / $total['c3bg'][$i][1],2) * 100 : 0;
            $c3bg_c3b_array[$cnt]   = $total['c3b'][$i][1] ?
                round($total['c3bg'][$i][1] / $total['c3b'][$i][1],2) * 100 : 0;
            $l6_l3_array[$cnt]      = $total['l3'][$i][1] ?
                round($total['l6'][$i][1] / $total['l3'][$i][1],2) * 100 : 0;
            $l8_l6_array[$cnt]      = $total['l6'][$i][1] ?
                round($total['l8'][$i][1] / $total['l6'][$i][1],2) * 100 : 0;
            $c3a_c3_array[$cnt]     = $total['c3'][$i][1] ?
                round($total['c3a'][$i][1] / $total['c3'][$i][1],2) * 100 : 0;
        }

        $l3_c3b_result   = array();
        $l3_c3bg_result  = array();
        $l3_l1_result    = array();
        $l1_c3bg_result  = array();
        $c3bg_c3b_result = array();
        $l6_l3_result    = array();
        $l8_l6_result    = array();
        $c3a_c3_result   = array();

        for ($i = 1; $i <= 12; $i++) {
            $l3_c3b_result[]    = [$i, isset($l3_c3b_array[$i])   ? $l3_c3b_array[$i]   : 0];
            $l3_c3bg_result[]   = [$i, isset($l3_c3bg_array[$i])  ? $l3_c3bg_array[$i]  : 0];
            $l3_l1_result[]     = [$i, isset($l3_l1_array[$i])    ? $l3_l1_array[$i]    : 0];
            $l1_c3bg_result[]   = [$i, isset($l1_c3bg_array[$i])  ? $l1_c3bg_array[$i]  : 0];
            $c3bg_c3b_result[]  = [$i, isset($c3bg_c3b_array[$i]) ? $c3bg_c3b_array[$i] : 0];
            $l6_l3_result[]     = [$i, isset($l6_l3_array[$i])    ? $l6_l3_array[$i]    : 0];
            $l8_l6_result[]     = [$i, isset($l8_l6_array[$i])    ? $l8_l6_array[$i]    : 0];
            $c3a_c3_result[]    = [$i, isset($c3a_c3_array[$i])   ? $c3a_c3_array[$i]   : 0];
        }

        $result = array();
        $result['l3_c3b']   = json_encode($l3_c3b_result);
        $result['l3_c3bg']  = json_encode($l3_c3bg_result);
        $result['l3_l1']    = json_encode($l3_l1_result);
        $result['l1_c3bg']  = json_encode($l1_c3bg_result);
        $result['c3bg_c3b'] = json_encode($c3bg_c3b_result);
        $result['l6_l3']    = json_encode($l6_l3_result);
        $result['l8_l6']    = json_encode($l8_l6_result);
        $result['c3a_c3']   = json_encode($c3a_c3_result);

        return $result;
    }

    private function convert_revenue($revenue){
        $request    = request();

        $config     = Config::getByKeys(['USD_VND', 'USD_THB', 'THB_VND']);
        $usd_vnd    = $config['USD_VND'];
        $usd_tbh    = $config['USD_THB'];
        $thb_vnd    = $config['THB_VND'];

        $rate = config('constants.UNIT_USD');
        if($request->unit){
            $rate = $request->unit;
        }

        if($rate == config('constants.UNIT_USD')){
            $revenue    = $usd_tbh ? $revenue / $usd_tbh : 0;
        }elseif ($rate == config('constants.UNIT_VND')){
            $revenue    = $revenue * $thb_vnd;
        }

        return round($revenue,2);
    }

    private function convert_spent($spent){
        $request    = request();

        $config     = Config::getByKeys(['USD_VND', 'USD_THB', 'THB_VND']);
        $usd_vnd    = $config['USD_VND'];
        $usd_tbh    = $config['USD_THB'];

        if($request->unit == config('constants.UNIT_VND')){
            $spent    = $spent * $usd_vnd;
        }elseif ($request->unit == config('constants.UNIT_BAHT')){
            $spent    = $spent * $usd_tbh;
        }

        return round($spent, 2);
    }
}
