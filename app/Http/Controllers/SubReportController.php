<?php

namespace App\Http\Controllers;

use App\Ad;
use App\AdResult;
use App\Campaign;
use App\Channel;
use App\Config;
use App\Contact;
use App\Source;
use App\Team;
use App\User;
use App\Subcampaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $page_title = "Sub Report | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'sub-report-line';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Line Chart </span>";

        $sources        = Source::all();
        $teams          = Team::all();
        $marketers      = User::all();
        $campaigns      = Campaign::where('is_active', 1)->get();
        $page_size      = Config::getByKey('PAGE_SIZE');
        $subcampaigns   = Subcampaign::where('is_active', 1)->get();

        $budget     = $this->getBudget();
        $quantity   = $this->getQuantity();
        $quality    = $this->getQuality();

        return view('pages.sub-report-line', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'sources',
            'teams',
            'marketers',
            'campaigns',
            'page_size',
            'subcampaigns',
            'budget',
            'quantity',
            'quality'
        ));
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
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
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
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
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

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        $config     = Config::getByKeys(['USD_VND', 'USD_THB']);
        $usd_vnd    = $config['USD_VND'];
        $usd_thb    = $config['USD_VND'];

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

                $me         = $item_result['me'] * $usd_vnd;
                $re         = $item_result['re'] / $usd_thb * $usd_vnd;

                $total_me   += $me;
                $total_re   += $re;

                $me_array[(int)($day[2])]   = $me;
                $re_array[(int)($day[2])]   = $re;
                $c3b_array[(int)($day[2])]  = $item_result['c3b']   ? $me / $item_result['c3b']     : 0 ;
                $c3bg_array[(int)($day[2])] = $item_result['c3bg']  ? $me / $item_result['c3bg']    : 0 ;
                $l1_array[(int)($day[2])]   = $item_result['l1']    ? $me / $item_result['l1']      : 0 ;
                $l3_array[(int)($day[2])]   = $item_result['l3']    ? $me / $item_result['l3']      : 0 ;
                $l6_array[(int)($day[2])]   = $item_result['l6']    ? $me / $item_result['l6']      : 0 ;
                $l8_array[(int)($day[2])]   = $item_result['l8']    ? $me / $item_result['l8']      : 0 ;

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
            $me_result[]     = [$timestamp, isset($me_array[$key])  ? $me_array[$key]   : 0];
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
                        'c3b'   => ['$sum' => '$c3b'],
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
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3b'   => ['$sum' => '$c3b'],
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
            $l1_array[(int)($day[2])]   = $item_result['l1'];
            $l3_array[(int)($day[2])]   = $item_result['l3'];
            $l6_array[(int)($day[2])]   = $item_result['l6'];
            $l8_array[(int)($day[2])]   = $item_result['l8'];

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
                        'c3b'   => ['$sum' => '$c3b'],
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
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3b'   => ['$sum' => '$c3b'],
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

        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);

            $l3_c3b_array[(int)($day[2])]  = $item_result['c3b'] ?
                round($item_result['l3'] / $item_result['c3b'],2) * 100 : 0;
            $l3_c3bg_array[(int)($day[2])]  = $item_result['c3bg'] ?
                round($item_result['l3'] / $item_result['c3bg'],2) * 100 : 0;
            $l3_l1_array[(int)($day[2])]  = $item_result['l1'] ?
                round($item_result['l3'] / $item_result['l1'],2) * 100 : 0;
            $l1_c3bg_array[(int)($day[2])]  = $item_result['c3bg'] ?
                round($item_result['l1'] / $item_result['c3bg'],2) * 100 : 0;
            $c3bg_c3b_array[(int)($day[2])]  = $item_result['c3b'] ?
                round($item_result['c3bg'] / $item_result['c3b'],2) * 100 : 0;
            $l6_l3_array[(int)($day[2])]  = $item_result['l3'] ?
                round($item_result['l6'] / $item_result['l3'],2) * 100 : 0;
            $l8_l6_array[(int)($day[2])]  = $item_result['l6'] ?
                round($item_result['l8'] / $item_result['l6'],2) * 100 : 0;
        }

        $l3_c3b_result   = array();
        $l3_c3bg_result  = array();
        $l3_l1_result    = array();
        $l1_c3bg_result  = array();
        $c3bg_c3b_result = array();
        $l6_l3_result    = array();
        $l8_l6_result    = array();

        foreach ($array_month as $key => $timestamp) {
            $l3_c3b_result[]    = [$timestamp, isset($l3_c3b_array[$key])   ? $l3_c3b_array[$key]   : 0];
            $l3_c3bg_result[]   = [$timestamp, isset($l3_c3bg_array[$key])  ? $l3_c3bg_array[$key]  : 0];
            $l3_l1_result[]     = [$timestamp, isset($l3_l1_array[$key])    ? $l3_l1_array[$key]    : 0];
            $l1_c3bg_result[]   = [$timestamp, isset($l1_c3bg_array[$key])  ? $l1_c3bg_array[$key]  : 0];
            $c3bg_c3b_result[]  = [$timestamp, isset($c3bg_c3b_array[$key]) ? $c3bg_c3b_array[$key] : 0];
            $l6_l3_result[]     = [$timestamp, isset($l6_l3_array[$key])    ? $l6_l3_array[$key]    : 0];
            $l8_l6_result[]     = [$timestamp, isset($l8_l6_array[$key])    ? $l8_l6_array[$key]    : 0];
        }

        $result = array();
        $result['l3_c3b']   = json_encode($l3_c3b_result);
        $result['l3_c3bg']  = json_encode($l3_c3bg_result);
        $result['l3_l1']    = json_encode($l3_l1_result);
        $result['l1_c3bg']  = json_encode($l1_c3bg_result);
        $result['c3bg_c3b'] = json_encode($c3bg_c3b_result);
        $result['l6_l3']    = json_encode($l6_l3_result);
        $result['l8_l6']    = json_encode($l8_l6_result);

        return $result;
    }

    private function getWhereData(){
        $request    = request();
        $data_where = array();
        if ($request->source_id) {
            $data_where['source_id']        = $request->source_id;
        }
        if ($request->team_id) {
            $data_where['team_id']          = $request->team_id;
        }
        if ($request->marketer_id) {
            $data_where['marketer_id']      = $request->marketer_id;
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
        $data_where = $this->getWhereData();
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

	public function hourReport(){
		$page_title = "Hour Report | Helios";
		$page_css = array();
		$no_main_header = FALSE; //set true for lock.php and login.php
		$active = 'hour-report';
		$breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Hour Report </span>";

		$sources        = Source::all();
		$teams          = Team::all();
		$marketers      = User::all();
		$campaigns      = Campaign::where('is_active', 1)->get();
		$page_size      = Config::getByKey('PAGE_SIZE');
		$subcampaigns   = Subcampaign::where('is_active', 1)->get();

		$table['c3'] = [];
		$table['c3a'] = [];
		$table['c3b'] = [];
		$table['c3bg'] = [];
		$table['c3_week'] = [];
		$table['c3a_week'] = [];
		$table['c3b_week'] = [];
		$table['c3bg_week'] = [];

		$table_accumulated['c3'] = [];
		$table_accumulated['c3b'] = [];
		$table_accumulated['c3bg'] = [];

		list($year, $month, $d, $first_day_this_month, $last_day_this_month) = $this->getDate(null);

		$array_month = array();
		for ($i = 1; $i <= $d; $i++) {
			//$array_month[date($i)] = 0;
			$timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
			$array_month[$i] = $timestamp;
		}

		$date_time   = date('Y-m-d');
		$current_hour = (int)date('H');

		foreach ($array_month as $key => $timestamp){
			$data_c3a[ $timestamp ]  = [];
			$data_c3b[ $timestamp ]  = [];
			$data_c3bg[ $timestamp ] = [];

			$temp['c3'][$timestamp] = 0;
			$temp['c3a'][$timestamp] = 0;
			$temp['c3b'][$timestamp] = 0;
			$temp['c3bg'][$timestamp] = 0;
		}

		Contact::where( 'submit_time', '>=', strtotime( $first_day_this_month ) * 1000 )
		       ->where( 'submit_time', '<=', strtotime( $last_day_this_month ) * 1000 )
		       ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
		       ->chunk( 1000, function ( $contacts ) use ( &$data_c3a , &$data_c3b, &$data_c3bg) {
			       foreach ( $contacts as $contact ) {
				        if ($contact->clevel == 'c3a'){
				        	$timestamp = (int) strtotime(date('Y-m-d',$contact->submit_time / 1000)) * 1000;
					        $hour = (int) date( "H", $contact->submit_time / 1000 );
					        if (isset($data_c3a[$timestamp][$hour]))
						        $data_c3a[$timestamp][$hour] += 1;
					        else{
						        $data_c3a[$timestamp][$hour] = 1;
					        }
				        }
				       else if ($contact->clevel == 'c3b'){
					       $timestamp = (int) strtotime(date('Y-m-d',$contact->submit_time / 1000)) * 1000;
					       $hour = (int) date( "H", $contact->submit_time / 1000 );
					       if (isset($data_c3b[$timestamp][$hour]))
						       $data_c3b[$timestamp][$hour] += 1;
					       else{
						       $data_c3b[$timestamp][$hour] = 1;
					       }
				       }
				       else if ($contact->clevel == 'c3bg'){
					       $timestamp = (int) strtotime(date('Y-m-d',$contact->submit_time / 1000)) * 1000;
					       $hour = (int) date( "H", $contact->submit_time / 1000 );
					       if (isset($data_c3bg[$timestamp][$hour]))
						       $data_c3bg[$timestamp][$hour] += 1;
					       else{
						       $data_c3bg[$timestamp][$hour] = 1;
					       }
				       }
			       }
		       } );


		for ($h = 0; $h <= $current_hour; $h++){
			foreach ($array_month as $key => $timestamp){
				$temp['c3a'][$timestamp] += isset($data_c3a[$timestamp]) && isset($data_c3a[$timestamp][$h])  ? $data_c3a[$timestamp][$h] : 0;
				$temp['c3b'][$timestamp] += ((isset($data_c3b[$timestamp]) && isset($data_c3b[$timestamp][$h])  ? $data_c3b[$timestamp][$h] : 0)
				                             + (isset($data_c3bg[$timestamp]) && isset($data_c3bg[$timestamp][$h])  ? $data_c3bg[$timestamp][$h] : 0));
				$temp['c3bg'][$timestamp] += isset($data_c3bg[$timestamp]) && isset($data_c3bg[$timestamp][$h])  ? $data_c3bg[$timestamp][$h] : 0;
				$temp['c3'][$timestamp] += ((isset($data_c3a[$timestamp]) && isset($data_c3a[$timestamp][$h])  ? $data_c3a[$timestamp][$h] : 0)
										+ (isset($data_c3b[$timestamp]) && isset($data_c3b[$timestamp][$h])  ? $data_c3b[$timestamp][$h] : 0)
										+ (isset($data_c3bg[$timestamp]) && isset($data_c3bg[$timestamp][$h])  ? $data_c3bg[$timestamp][$h] : 0)) ;

			}
		}

		foreach ($array_month as $key => $timestamp){
			$line_c3b[$current_hour][] = [$timestamp, $temp['c3b'][$timestamp]];
			$line_c3bg[$current_hour][] = [$timestamp, $temp['c3bg'][$timestamp]];
			$line_c3[$current_hour][] = [$timestamp, $temp['c3'][$timestamp]];
		}
		$chart_c3b[$current_hour] = json_encode($line_c3b[$current_hour]);
		$chart_c3bg[$current_hour] = json_encode($line_c3bg[$current_hour]);
		$chart_c3[$current_hour] = json_encode($line_c3[$current_hour]);

		foreach ($array_month as $key => $timestamp){
			$data_c3a[ $timestamp ]  = [];
			$data_c3b[ $timestamp ]  = [];
			$data_c3bg[ $timestamp ] = [];
		}

		for ($i = 0; $i < 24; $i++){
			$table['c3a'][$i] =  0;
			$table['c3b'][$i] = 0;
			$table['c3bg'][$i] = 0;

			$table['c3'][$i] =  0;

			$table['c3a_week'][$i] = 0;
			$table['c3b_week'][$i] = 0;
			$table['c3bg_week'][$i] = 0;

			$table['c3_week'][$i] = 0;
		}


		$contacts = Contact::where( 'submit_time', '>=', strtotime( "midnight" ) * 1000 )
		                   ->where( 'submit_time', '<', strtotime( "tomorrow" ) * 1000 )
		                   ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
							->chunk( 1000, function ( $contacts ) use ( &$table ) {
								foreach ( $contacts as $contact ) {
									if ($contact->clevel == 'c3a'){
										$hour = (int) date( "H", $contact->submit_time / 1000 );
										if (isset($table['c3a'][$hour]))
											$table['c3a'][$hour] += 1;
										else{
											$table['c3a'][$hour] = 1;
										}
									}
									else if ($contact->clevel == 'c3b'){
										$hour = (int) date( "H", $contact->submit_time / 1000 );
										if (isset($table['c3b'][$hour]))
											$table['c3b'][$hour] += 1;
										else{
											$table['c3b'][$hour] = 1;
										}
									}
									else if ($contact->clevel == 'c3bg'){
										$hour = (int) date( "H", $contact->submit_time / 1000 );
										if (isset($table['c3bg'][$hour]))
											$table['c3bg'][$hour] += 1;
										else{
											$table['c3bg'][$hour] = 1;
										}
									}
								}
							} );

		$contacts_week = Contact::where( 'submit_time', '>=', strtotime( "midnight" ) * 1000 - 7 * 86400000)
		                   ->where( 'submit_time', '<', strtotime( "tomorrow" ) * 1000 )
		                   ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
							->chunk( 1000, function ( $contacts ) use ( &$table ) {
								foreach ( $contacts as $contact ) {
									if ($contact->clevel == 'c3a'){
										$hour = (int) date( "H", $contact->submit_time / 1000 );
										if (isset($table['c3a_week'][$hour]))
											$table['c3a_week'][$hour] += 1;
										else{
											$table['c3a_week'][$hour] = 1;
										}
									}
									else if ($contact->clevel == 'c3b'){
										$hour = (int) date( "H", $contact->submit_time / 1000 );
										if (isset($table['c3b_week'][$hour]))
											$table['c3b_week'][$hour] += 1;
										else{
											$table['c3b_week'][$hour] = 1;
										}
									}
									else if ($contact->clevel == 'c3bg'){
										$hour = (int) date( "H", $contact->submit_time / 1000 );
										if (isset($table['c3bg_week'][$hour]))
											$table['c3bg_week'][$hour] += 1;
										else{
											$table['c3bg_week'][$hour] = 1;
										}
									}
								}
							} );

		for ($i = 0; $i < 24; $i++){
			$table['c3'][$i] =  $table['c3a'][$i] + $table['c3b'][$i] + $table['c3bg'][$i];
			$table['c3b'][$i] =  $table['c3b'][$i] + $table['c3bg'][$i];

			$c3_line[] =  [$i, $table['c3'][$i]];
			$c3b_line[] =  [$i, $table['c3b'][$i]];
			$c3bg_line[] =  [$i, $table['c3bg'][$i]];
		}

		for ($i = 0; $i < 24; $i++){
			$table['c3a_week'][$i] = intval( round($table['c3a_week'][$i] / 7));
			$table['c3b_week'][$i] = intval( round($table['c3b_week'][$i] / 7));
			$table['c3bg_week'][$i] = intval( round($table['c3bg_week'][$i] / 7));

			$table['c3_week'][$i] =  $table['c3a_week'][$i] + $table['c3b_week'][$i] + $table['c3bg_week'][$i];
			$table['c3b_week'][$i] = $table['c3b_week'][$i] + $table['c3bg_week'][$i];

			$c3_week_line[] =  [$i, $table['c3_week'][$i]];
			$c3b_week_line[] =  [$i, $table['c3b_week'][$i]];
			$c3bg_week_line[] =  [$i, $table['c3bg_week'][$i]];
		}

		for ($i = 0; $i < 24; $i++){
			$table_accumulated['c3'][$i] = 0;
			$table_accumulated['c3b'][$i] = 0;
			$table_accumulated['c3bg'][$i] = 0;
			$table_accumulated['c3_week'][$i] = 0;
			$table_accumulated['c3b_week'][$i] = 0;
			$table_accumulated['c3bg_week'][$i] = 0;
			for ($j = 0; $j <= $i; $j++){
				$table_accumulated['c3'][$i] += $table['c3'][$j];
				$table_accumulated['c3b'][$i] += $table['c3b'][$j];
				$table_accumulated['c3bg'][$i] += $table['c3bg'][$j];

				$table_accumulated['c3_week'][$i] += $table['c3_week'][$j];
				$table_accumulated['c3b_week'][$i] += $table['c3b_week'][$j];
				$table_accumulated['c3bg_week'][$i] += $table['c3bg_week'][$j];
			}

			$c3_line_accumulated[] =  [$i, $table_accumulated['c3'][$i]];
			$c3b_line_accumulated[] =  [$i, $table_accumulated['c3b'][$i]];
			$c3bg_line_accumulated[] =  [$i, $table_accumulated['c3bg'][$i]];

			$c3_week_line_accumulated[] =  [$i, $table_accumulated['c3_week'][$i]];
			$c3b_week_line_accumulated[] =  [$i, $table_accumulated['c3b_week'][$i]];
			$c3bg_week_line_accumulated[] =  [$i, $table_accumulated['c3bg_week'][$i]];
		}

		$chart['c3']    = json_encode($c3_line);
		$chart['c3b']     = json_encode($c3b_line);
		$chart['c3bg']     = json_encode($c3bg_line);
		$chart['c3_week']    = json_encode($c3_week_line);
		$chart['c3b_week']     = json_encode($c3b_week_line);
		$chart['c3bg_week']     = json_encode($c3bg_week_line);
		$chart['c3_accumulated']    = json_encode($c3_line_accumulated);
		$chart['c3b_accumulated']     = json_encode($c3b_line_accumulated);
		$chart['c3bg_accumulated']     = json_encode($c3bg_line_accumulated);
		$chart['c3_week_accumulated']    = json_encode($c3_week_line_accumulated);
		$chart['c3b_week_accumulated']     = json_encode($c3b_week_line_accumulated);
		$chart['c3bg_week_accumulated']     = json_encode($c3bg_week_line_accumulated);

		return view('pages.hour-report', compact(
			'page_title',
			'page_css',
			'no_main_header',
			'active',
			'breadcrumbs',
			'sources',
			'teams',
			'marketers',
			'campaigns',
			'page_size',
			'subcampaigns',
			'table',
			'table_accumulated',
			'chart_c3',
			'chart_c3b',
			'chart_c3bg',
			'current_hour',
			'chart',
			'data_where',
			'date_time'
		));
	}

	public function hourReportFilter(){
		$page_title = "Hour Report | Helios";
		$page_css = array();
		$no_main_header = FALSE; //set true for lock.php and login.php
		$active = 'hour-report';
		$breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Hour Report </span>";

		$sources        = Source::all();
		$teams          = Team::all();
		$marketers      = User::all();
		$campaigns      = Campaign::where('is_active', 1)->get();
		$page_size      = Config::getByKey('PAGE_SIZE');
		$subcampaigns   = Subcampaign::where('is_active', 1)->get();


		$table['c3'] = [];
		$table['c3a'] = [];
		$table['c3b'] = [];
		$table['c3bg'] = [];
		$table['c3_week'] = [];
		$table['c3a_week'] = [];
		$table['c3b_week'] = [];
		$table['c3bg_week'] = [];

		$table_accumulated['c3'] = [];
		$table_accumulated['c3b'] = [];
		$table_accumulated['c3bg'] = [];

		list($year, $month, $d, $first_day_this_month, $last_day_this_month) = $this->getDate(null);

		$array_month = array();
		for ($i = 1; $i <= $d; $i++) {
			//$array_month[date($i)] = 0;
			$timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
			$array_month[$i] = $timestamp;
		}

		$current_hour = (int)date('H');

		foreach ($array_month as $key => $timestamp){
			$data_c3a[ $timestamp ]  = [];
			$data_c3b[ $timestamp ]  = [];
			$data_c3bg[ $timestamp ] = [];

			$temp['c3'][$timestamp] = 0;
			$temp['c3a'][$timestamp] = 0;
			$temp['c3b'][$timestamp] = 0;
			$temp['c3bg'][$timestamp] = 0;
		}

		Contact::where( 'submit_time', '>=', strtotime( $first_day_this_month ) * 1000 )
		       ->where( 'submit_time', '<=', strtotime( $last_day_this_month ) * 1000 )
		       ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
		       ->chunk( 1000, function ( $contacts ) use ( &$data_c3a , &$data_c3b, &$data_c3bg) {
			       foreach ( $contacts as $contact ) {
				       if ($contact->clevel == 'c3a'){
					       $timestamp = (int) strtotime(date('Y-m-d',$contact->submit_time / 1000)) * 1000;
					       $hour = (int) date( "H", $contact->submit_time / 1000 );
					       if (isset($data_c3a[$timestamp][$hour]))
						       $data_c3a[$timestamp][$hour] += 1;
					       else{
						       $data_c3a[$timestamp][$hour] = 1;
					       }
				       }
				       else if ($contact->clevel == 'c3b'){
					       $timestamp = (int) strtotime(date('Y-m-d',$contact->submit_time / 1000)) * 1000;
					       $hour = (int) date( "H", $contact->submit_time / 1000 );
					       if (isset($data_c3b[$timestamp][$hour]))
						       $data_c3b[$timestamp][$hour] += 1;
					       else{
						       $data_c3b[$timestamp][$hour] = 1;
					       }
				       }
				       else if ($contact->clevel == 'c3bg'){
					       $timestamp = (int) strtotime(date('Y-m-d',$contact->submit_time / 1000)) * 1000;
					       $hour = (int) date( "H", $contact->submit_time / 1000 );
					       if (isset($data_c3bg[$timestamp][$hour]))
						       $data_c3bg[$timestamp][$hour] += 1;
					       else{
						       $data_c3bg[$timestamp][$hour] = 1;
					       }
				       }
			       }
		       } );


		for ($h = 0; $h <= $current_hour; $h++){
			foreach ($array_month as $key => $timestamp){
				$temp['c3a'][$timestamp] += isset($data_c3a[$timestamp]) && isset($data_c3a[$timestamp][$h])  ? $data_c3a[$timestamp][$h] : 0;
				$temp['c3b'][$timestamp] += ((isset($data_c3b[$timestamp]) && isset($data_c3b[$timestamp][$h])  ? $data_c3b[$timestamp][$h] : 0)
				                             + (isset($data_c3bg[$timestamp]) && isset($data_c3bg[$timestamp][$h])  ? $data_c3bg[$timestamp][$h] : 0));
				$temp['c3bg'][$timestamp] += isset($data_c3bg[$timestamp]) && isset($data_c3bg[$timestamp][$h])  ? $data_c3bg[$timestamp][$h] : 0;
				$temp['c3'][$timestamp] += ((isset($data_c3a[$timestamp]) && isset($data_c3a[$timestamp][$h])  ? $data_c3a[$timestamp][$h] : 0)
				                            + (isset($data_c3b[$timestamp]) && isset($data_c3b[$timestamp][$h])  ? $data_c3b[$timestamp][$h] : 0)
				                            + (isset($data_c3bg[$timestamp]) && isset($data_c3bg[$timestamp][$h])  ? $data_c3bg[$timestamp][$h] : 0)) ;

			}
		}

		foreach ($array_month as $key => $timestamp){
			$line_c3b[$current_hour][] = [$timestamp, $temp['c3b'][$timestamp]];
			$line_c3bg[$current_hour][] = [$timestamp, $temp['c3bg'][$timestamp]];
			$line_c3[$current_hour][] = [$timestamp, $temp['c3'][$timestamp]];
		}
		$chart_c3b[$current_hour] = json_encode($line_c3b[$current_hour]);
		$chart_c3bg[$current_hour] = json_encode($line_c3bg[$current_hour]);
		$chart_c3[$current_hour] = json_encode($line_c3[$current_hour]);

		foreach ($array_month as $key => $timestamp){
			$data_c3a[ $timestamp ]  = [];
			$data_c3b[ $timestamp ]  = [];
			$data_c3bg[ $timestamp ] = [];
		}

		for ($i = 0; $i < 24; $i++){
			$table['c3a'][$i] =  0;
			$table['c3b'][$i] = 0;
			$table['c3bg'][$i] = 0;

			$table['c3'][$i] =  0;

			$table['c3a_week'][$i] = 0;
			$table['c3b_week'][$i] = 0;
			$table['c3bg_week'][$i] = 0;

			$table['c3_week'][$i] = 0;
		}

		$data_where = $this->getWhereData();

		$request        = request();
		$date_time   = $request->date_time;

		$contacts = Contact::where($data_where)->where( 'submit_time', '>=', strtotime( $date_time ) * 1000 )
		                   ->where( 'submit_time', '<', strtotime( $date_time ) * 1000 + 86400000)
		                   ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
		                   ->chunk( 1000, function ( $contacts ) use ( &$table ) {
			                   foreach ( $contacts as $contact ) {
				                   if ($contact->clevel == 'c3a'){
					                   $hour = (int) date( "H", $contact->submit_time / 1000 );
					                   if (isset($table['c3a'][$hour]))
						                   $table['c3a'][$hour] += 1;
					                   else{
						                   $table['c3a'][$hour] = 1;
					                   }
				                   }
				                   else if ($contact->clevel == 'c3b'){
					                   $hour = (int) date( "H", $contact->submit_time / 1000 );
					                   if (isset($table['c3b'][$hour]))
						                   $table['c3b'][$hour] += 1;
					                   else{
						                   $table['c3b'][$hour] = 1;
					                   }
				                   }
				                   else if ($contact->clevel == 'c3bg'){
					                   $hour = (int) date( "H", $contact->submit_time / 1000 );
					                   if (isset($table['c3bg'][$hour]))
						                   $table['c3bg'][$hour] += 1;
					                   else{
						                   $table['c3bg'][$hour] = 1;
					                   }
				                   }
			                   }
		                   } );

		$contacts_week = Contact::where($data_where)->where( 'submit_time', '>=', strtotime( $date_time ) * 1000  - 7 * 86400000)
		                        ->where( 'submit_time', '<', strtotime( $date_time ) * 1000 + 86400000)
		                        ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
		                        ->chunk( 1000, function ( $contacts ) use ( &$table ) {
			                        foreach ( $contacts as $contact ) {
				                        if ($contact->clevel == 'c3a'){
					                        $hour = (int) date( "H", $contact->submit_time / 1000 );
					                        if (isset($table['c3a_week'][$hour]))
						                        $table['c3a_week'][$hour] += 1;
					                        else{
						                        $table['c3a_week'][$hour] = 1;
					                        }
				                        }
				                        else if ($contact->clevel == 'c3b'){
					                        $hour = (int) date( "H", $contact->submit_time / 1000 );
					                        if (isset($table['c3b_week'][$hour]))
						                        $table['c3b_week'][$hour] += 1;
					                        else{
						                        $table['c3b_week'][$hour] = 1;
					                        }
				                        }
				                        else if ($contact->clevel == 'c3bg'){
					                        $hour = (int) date( "H", $contact->submit_time / 1000 );
					                        if (isset($table['c3bg_week'][$hour]))
						                        $table['c3bg_week'][$hour] += 1;
					                        else{
						                        $table['c3bg_week'][$hour] = 1;
					                        }
				                        }
			                        }
		                        } );

		for ($i = 0; $i < 24; $i++){
			$table['c3'][$i] =  $table['c3a'][$i] + $table['c3b'][$i] + $table['c3bg'][$i];
			$table['c3b'][$i] =  $table['c3b'][$i] + $table['c3bg'][$i];

			$c3_line[] =  [$i, $table['c3'][$i]];
			$c3b_line[] =  [$i, $table['c3b'][$i]];
			$c3bg_line[] =  [$i, $table['c3bg'][$i]];
		}

		for ($i = 0; $i < 24; $i++){
			$table['c3a_week'][$i] = intval( round($table['c3a_week'][$i] / 7));
			$table['c3b_week'][$i] = intval( round($table['c3b_week'][$i] / 7));
			$table['c3bg_week'][$i] = intval( round($table['c3bg_week'][$i] / 7));

			$table['c3_week'][$i] =  $table['c3a_week'][$i] + $table['c3b_week'][$i] + $table['c3bg_week'][$i];
			$table['c3b_week'][$i] = $table['c3b_week'][$i] + $table['c3bg_week'][$i];

			$c3_week_line[] =  [$i, $table['c3_week'][$i]];
			$c3b_week_line[] =  [$i, $table['c3b_week'][$i]];
			$c3bg_week_line[] =  [$i, $table['c3bg_week'][$i]];
		}

		for ($i = 0; $i < 24; $i++){
			$table_accumulated['c3'][$i] = 0;
			$table_accumulated['c3b'][$i] = 0;
			$table_accumulated['c3bg'][$i] = 0;
			$table_accumulated['c3_week'][$i] = 0;
			$table_accumulated['c3b_week'][$i] = 0;
			$table_accumulated['c3bg_week'][$i] = 0;
			for ($j = 0; $j <= $i; $j++){
				$table_accumulated['c3'][$i] += $table['c3'][$j];
				$table_accumulated['c3b'][$i] += $table['c3b'][$j];
				$table_accumulated['c3bg'][$i] += $table['c3bg'][$j];

				$table_accumulated['c3_week'][$i] += $table['c3_week'][$j];
				$table_accumulated['c3b_week'][$i] += $table['c3b_week'][$j];
				$table_accumulated['c3bg_week'][$i] += $table['c3bg_week'][$j];
			}

			$c3_line_accumulated[] =  [$i, $table_accumulated['c3'][$i]];
			$c3b_line_accumulated[] =  [$i, $table_accumulated['c3b'][$i]];
			$c3bg_line_accumulated[] =  [$i, $table_accumulated['c3bg'][$i]];

			$c3_week_line_accumulated[] =  [$i, $table_accumulated['c3_week'][$i]];
			$c3b_week_line_accumulated[] =  [$i, $table_accumulated['c3b_week'][$i]];
			$c3bg_week_line_accumulated[] =  [$i, $table_accumulated['c3bg_week'][$i]];
		}

		$chart['c3']    = json_encode($c3_line);
		$chart['c3b']     = json_encode($c3b_line);
		$chart['c3bg']     = json_encode($c3bg_line);
		$chart['c3_week']    = json_encode($c3_week_line);
		$chart['c3b_week']     = json_encode($c3b_week_line);
		$chart['c3bg_week']     = json_encode($c3bg_week_line);
		$chart['c3_accumulated']    = json_encode($c3_line_accumulated);
		$chart['c3b_accumulated']     = json_encode($c3b_line_accumulated);
		$chart['c3bg_accumulated']     = json_encode($c3bg_line_accumulated);
		$chart['c3_week_accumulated']    = json_encode($c3_week_line_accumulated);
		$chart['c3b_week_accumulated']     = json_encode($c3b_week_line_accumulated);
		$chart['c3bg_week_accumulated']     = json_encode($c3bg_week_line_accumulated);


		return view('pages.hour-report', compact(
			'page_title',
			'page_css',
			'no_main_header',
			'active',
			'breadcrumbs',
			'sources',
			'teams',
			'marketers',
			'campaigns',
			'page_size',
			'subcampaigns',
			'table',
			'table_accumulated',
			'chart_c3',
			'chart_c3b',
			'chart_c3bg',
			'current_hour',
			'chart',
			'data_where',
			'date_time'
		));
	}

	public function channelReport(){
		$page_title = "Channel Report | Helios";
		$page_css = array();
		$no_main_header = FALSE; //set true for lock.php and login.php
		$active = 'channel-report';
		$breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Channel Report </span>";

		$sources        = Source::all();
		$teams          = Team::all();
		$marketers      = User::all();
		$campaigns      = Campaign::where('is_active', 1)->get();
		$page_size      = Config::getByKey('PAGE_SIZE');
		$subcampaigns   = Subcampaign::where('is_active', 1)->get();

		$channels      = Channel::all();

		$table['c3'] = [];
		$table['c3a'] = [];
		$table['c3b'] = [];
		$table['c3bg'] = [];
		$table['c3_week'] = [];
		$table['c3a_week'] = [];
		$table['c3b_week'] = [];
		$table['c3bg_week'] = [];

		$date_time   = date('Y-m-d');

		$array_channel = array();
		foreach ($channels as $key => $channel) {
			$array_channel[$key] = $channel->name;
		}

		$array_channel[] = 'Unknown';

		foreach ($array_channel as $channel) {
			$table['c3'][$channel] = 0;
			$table['c3a'][$channel] = 0;
			$table['c3b'][$channel] = 0;
			$table['c3bg'][$channel] = 0;
			$table['c3_week'][$channel] = 0;
			$table['c3a_week'][$channel] = 0;
			$table['c3b_week'][$channel] = 0;
			$table['c3bg_week'][$channel] = 0;
		}

		Contact::where( 'submit_time', '>=', strtotime( "midnight" ) * 1000 )
               ->where( 'submit_time', '<', strtotime( "tomorrow" ) * 1000 )
               ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
               ->chunk( 1000, function ( $contacts ) use ( &$table ) {
                   foreach ( $contacts as $contact ) {
	                   if ($contact->clevel == 'c3a'){
		                   $channel = $contact->channel_name;
		                   if (isset($table['c3a'][$channel]))
			                   $table['c3a'][$channel] += 1;
		                   else{
			                   $table['c3a'][$channel] = 1;
		                   }
	                   }
	                   else if ($contact->clevel == 'c3b'){
		                   $channel = $contact->channel_name;
		                   if (isset($table['c3b'][$channel]))
			                   $table['c3b'][$channel] += 1;
		                   else{
			                   $table['c3b'][$channel] = 1;
		                   }
	                   }
	                   else if ($contact->clevel == 'c3bg'){
		                   $channel = $contact->channel_name;
		                   if (isset($table['c3bg'][$channel]))
			                   $table['c3bg'][$channel] += 1;
		                   else{
			                   $table['c3bg'][$channel] = 1;
		                   }
	                   }
                   }
               } );

		Contact::where( 'submit_time', '>=', strtotime( "midnight" ) * 1000 - 7 * 86400000)
                ->where( 'submit_time', '<', strtotime( "tomorrow" ) * 1000 )
                ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
				->chunk( 1000, function ( $contacts ) use ( &$table ) {
					foreach ( $contacts as $contact ) {
						if ($contact->clevel == 'c3a'){
							$channel = $contact->channel_name;
							if (isset($table['c3a_week'][$channel]))
								$table['c3a_week'][$channel] += 1;
							else{
								$table['c3a_week'][$channel] = 1;
							}
						}
						else if ($contact->clevel == 'c3b'){
							$channel = $contact->channel_name;
							if (isset($table['c3b_week'][$channel]))
								$table['c3b_week'][$channel] += 1;
							else{
								$table['c3b_week'][$channel] = 1;
							}
						}
						else if ($contact->clevel == 'c3bg'){
							$channel = $contact->channel_name;
							if (isset($table['c3bg_week'][$channel]))
								$table['c3bg_week'][$channel] += 1;
							else{
								$table['c3bg_week'][$channel] = 1;
							}
						}
					}
				} );

		foreach ($array_channel as $i){
			$temp['c3a'] = isset($table['c3a'][$i]) ? $table['c3a'][$i] : 0;
			$temp['c3b'] = isset($table['c3b'][$i]) ? $table['c3b'][$i] : 0;
			$temp['c3bg'] = isset($table['c3bg'][$i]) ? $table['c3bg'][$i] : 0;

			$table['c3'][$i] =  $temp['c3a'] + $temp['c3b'] + $temp['c3bg'];
			$table['c3b'][$i] =  $temp['c3b'] + $temp['c3bg'];

			$temp['c3a_week'] = isset($table['c3a_week'][$i]) ? $table['c3a_week'][$i] : 0;
			$temp['c3b_week'] = isset($table['c3b_week'][$i]) ? $table['c3b_week'][$i] : 0;
			$temp['c3bg_week'] = isset($table['c3bg_week'][$i]) ? $table['c3bg_week'][$i] : 0;

			$table['c3a_week'][$i] = intval( round($temp['c3a_week'] / 7));
			$table['c3b_week'][$i] = intval( round($temp['c3b_week'] / 7));
			$table['c3bg_week'][$i] = intval( round($temp['c3bg_week'] / 7));

			$table['c3_week'][$i] =  $table['c3a_week'][$i] + $table['c3b_week'][$i] + $table['c3bg_week'][$i];
			$table['c3b_week'][$i] = $table['c3b_week'][$i] + $table['c3bg_week'][$i];
		}

		return view('pages.channel-report', compact(
			'page_title',
			'page_css',
			'no_main_header',
			'active',
			'breadcrumbs',
			'sources',
			'teams',
			'marketers',
			'campaigns',
			'page_size',
			'subcampaigns',
			'table',
			'data_where',
			'date_time',
			'array_channel'
		));
	}

	public function channelReportFilter(){
		$page_title = "Channel Report | Helios";
		$page_css = array();
		$no_main_header = FALSE; //set true for lock.php and login.php
		$active = 'channel-report';
		$breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Channel Report </span>";

		$sources        = Source::all();
		$teams          = Team::all();
		$marketers      = User::all();
		$campaigns      = Campaign::where('is_active', 1)->get();
		$page_size      = Config::getByKey('PAGE_SIZE');
		$subcampaigns   = Subcampaign::where('is_active', 1)->get();

		$channels      = Channel::all();

		$table['c3'] = [];
		$table['c3a'] = [];
		$table['c3b'] = [];
		$table['c3bg'] = [];
		$table['c3_week'] = [];
		$table['c3a_week'] = [];
		$table['c3b_week'] = [];
		$table['c3bg_week'] = [];

		$data_where = $this->getWhereData();

		$request        = request();
		$date_time   = $request->date_time;

		$array_channel = array();
		foreach ($channels as $key => $channel) {
			$array_channel[$key] = $channel->name;
		}

		$array_channel[] = 'Unknown';

		foreach ($array_channel as $channel) {
			$table['c3'][$channel] = 0;
			$table['c3a'][$channel] = 0;
			$table['c3b'][$channel] = 0;
			$table['c3bg'][$channel] = 0;
			$table['c3_week'][$channel] = 0;
			$table['c3a_week'][$channel] = 0;
			$table['c3b_week'][$channel] = 0;
			$table['c3bg_week'][$channel] = 0;
		}

		Contact::where($data_where)->where( 'submit_time', '>=', strtotime( $date_time ) * 1000 )
		       ->where( 'submit_time', '<', strtotime( $date_time ) * 1000 + 86400000)
		       ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
		       ->chunk( 1000, function ( $contacts ) use ( &$table ) {
			       foreach ( $contacts as $contact ) {
				       if ($contact->clevel == 'c3a'){
					       $channel = $contact->channel_name;
					       if (isset($table['c3a'][$channel]))
						       $table['c3a'][$channel] += 1;
					       else{
						       $table['c3a'][$channel] = 1;
					       }
				       }
				       else if ($contact->clevel == 'c3b'){
					       $channel = $contact->channel_name;
					       if (isset($table['c3b'][$channel]))
						       $table['c3b'][$channel] += 1;
					       else{
						       $table['c3b'][$channel] = 1;
					       }
				       }
				       else if ($contact->clevel == 'c3bg'){
					       $channel = $contact->channel_name;
					       if (isset($table['c3bg'][$channel]))
						       $table['c3bg'][$channel] += 1;
					       else{
						       $table['c3bg'][$channel] = 1;
					       }
				       }
			       }
		       } );

		Contact::where($data_where)->where( 'submit_time', '>=', strtotime( $date_time ) * 1000  - 7 * 86400000)
		       ->where( 'submit_time', '<', strtotime( $date_time ) * 1000 + 86400000 )
		       ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
		       ->chunk( 1000, function ( $contacts ) use ( &$table ) {
			       foreach ( $contacts as $contact ) {
				       if ($contact->clevel == 'c3a'){
					       $channel = $contact->channel_name;
					       if (isset($table['c3a_week'][$channel]))
						       $table['c3a_week'][$channel] += 1;
					       else{
						       $table['c3a_week'][$channel] = 1;
					       }
				       }
				       else if ($contact->clevel == 'c3b'){
					       $channel = $contact->channel_name;
					       if (isset($table['c3b_week'][$channel]))
						       $table['c3b_week'][$channel] += 1;
					       else{
						       $table['c3b_week'][$channel] = 1;
					       }
				       }
				       else if ($contact->clevel == 'c3bg'){
					       $channel = $contact->channel_name;
					       if (isset($table['c3bg_week'][$channel]))
						       $table['c3bg_week'][$channel] += 1;
					       else{
						       $table['c3bg_week'][$channel] = 1;
					       }
				       }
			       }
		       } );

		foreach ($array_channel as $i){
			$temp['c3a'] = isset($table['c3a'][$i]) ? $table['c3a'][$i] : 0;
			$temp['c3b'] = isset($table['c3b'][$i]) ? $table['c3b'][$i] : 0;
			$temp['c3bg'] = isset($table['c3bg'][$i]) ? $table['c3bg'][$i] : 0;

			$table['c3'][$i] =  $temp['c3a'] + $temp['c3b'] + $temp['c3bg'];
			$table['c3b'][$i] =  $temp['c3b'] + $temp['c3bg'];

			$temp['c3a_week'] = isset($table['c3a_week'][$i]) ? $table['c3a_week'][$i] : 0;
			$temp['c3b_week'] = isset($table['c3b_week'][$i]) ? $table['c3b_week'][$i] : 0;
			$temp['c3bg_week'] = isset($table['c3bg_week'][$i]) ? $table['c3bg_week'][$i] : 0;

			$table['c3a_week'][$i] = intval( round($temp['c3a_week'] / 7));
			$table['c3b_week'][$i] = intval( round($temp['c3b_week'] / 7));
			$table['c3bg_week'][$i] = intval( round($temp['c3bg_week'] / 7));

			$table['c3_week'][$i] =  $table['c3a_week'][$i] + $table['c3b_week'][$i] + $table['c3bg_week'][$i];
			$table['c3b_week'][$i] = $table['c3b_week'][$i] + $table['c3bg_week'][$i];
		}

		return view('pages.channel-report', compact(
			'page_title',
			'page_css',
			'no_main_header',
			'active',
			'breadcrumbs',
			'sources',
			'teams',
			'marketers',
			'campaigns',
			'page_size',
			'subcampaigns',
			'table',
			'data_where',
			'date_time',
			'array_channel'
		));
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
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
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
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
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

        if ($type == 'budget') {
            $result['budget']   = $this->getBudgetByWeeks($query_chart, $w);
        }
        else if ($type == 'quantity') {
            $result['quantity'] = $this->getQuantityByWeeks($query_chart, $w);
        }
        else if ($type == 'quality') {
            $result['quality']  = $this->getQualityByWeeks($query_chart, $w);
        } else {
            $result['budget']   = $this->getBudgetByWeeks($query_chart, $w);
            $result['quantity'] = $this->getQuantityByWeeks($query_chart, $w);
            $result['quality']  = $this->getQualityByWeeks($query_chart, $w);
        }

        return $result;
    }

    private function getBudgetByWeeks($query_chart, $w){

        $config     = Config::getByKeys(['USD_VND', 'USD_THB']);
        $usd_vnd    = $config['USD_VND'];
        $usd_thb    = $config['USD_VND'];

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

            $me         = $item_result['me'] * $usd_vnd;
            $re         = $item_result['re'] / $usd_thb * $usd_vnd;

            $total_me   += $me;
            $total_re   += $re;

            @$me_array[$week]   += $me;
            @$re_array[$week]   += $re;
            @$c3b_array[$week]  += $item_result['c3b']   ? $me / $item_result['c3b']     : 0 ;
            @$c3bg_array[$week] += $item_result['c3bg']  ? $me / $item_result['c3bg']    : 0 ;
            @$l1_array[$week]   += $item_result['l1']    ? $me / $item_result['l1']      : 0 ;
            @$l3_array[$week]   += $item_result['l3']    ? $me / $item_result['l3']      : 0 ;
            @$l6_array[$week]   += $item_result['l6']    ? $me / $item_result['l6']      : 0 ;
            @$l8_array[$week]   += $item_result['l8']    ? $me / $item_result['l8']      : 0 ;

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

    private function getQuantityByWeeks($query_chart, $w){

        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();

        foreach ($query_chart as $item_result) {
            $week = $this->getWeek($item_result['_id']);

            @$c3b_array[$week]  += $item_result['c3b']   ? $item_result['c3b']     : 0 ;
            @$c3bg_array[$week] += $item_result['c3bg']  ? $item_result['c3bg']    : 0 ;
            @$l1_array[$week]   += $item_result['l1']    ? $item_result['l1']      : 0 ;
            @$l3_array[$week]   += $item_result['l3']    ? $item_result['l3']      : 0 ;
            @$l6_array[$week]   += $item_result['l6']    ? $item_result['l6']      : 0 ;
            @$l8_array[$week]   += $item_result['l8']    ? $item_result['l8']      : 0 ;

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

    private function getTotalDataByWeeks($query_chart, $w){
        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();

        foreach ($query_chart as $item_result) {
            $week = $this->getWeek($item_result['_id']);

            @$c3b_array[$week]  += $item_result['c3b']   ? $item_result['c3b']     : 0 ;
            @$c3bg_array[$week] += $item_result['c3bg']  ? $item_result['c3bg']    : 0 ;
            @$l1_array[$week]   += $item_result['l1']    ? $item_result['l1']      : 0 ;
            @$l3_array[$week]   += $item_result['l3']    ? $item_result['l3']      : 0 ;
            @$l6_array[$week]   += $item_result['l6']    ? $item_result['l6']      : 0 ;
            @$l8_array[$week]   += $item_result['l8']    ? $item_result['l8']      : 0 ;

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
        $result['c3b']      = $c3b_result;
        $result['c3bg']     = $c3bg_result;
        $result['l1']       = $l1_result;
        $result['l3']       = $l3_result;
        $result['l6']       = $l6_result;
        $result['l8']       = $l8_result;

        return $result;
    }

    private function getQualityByWeeks($query_chart, $w){

        $total = $this->getTotalDataByWeeks($query_chart, $w);

        $l3_c3b_array   = array();
        $l3_c3bg_array  = array();
        $l3_l1_array    = array();
        $l1_c3bg_array  = array();
        $c3bg_c3b_array = array();
        $l6_l3_array    = array();
        $l8_l6_array    = array();

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
        }

        $l3_c3b_result   = array();
        $l3_c3bg_result  = array();
        $l3_l1_result    = array();
        $l1_c3bg_result  = array();
        $c3bg_c3b_result = array();
        $l6_l3_result    = array();
        $l8_l6_result    = array();

        for ($i = 1; $i <= $w; $i++) {
            $l3_c3b_result[]    = [$i, isset($l3_c3b_array[$i])   ? $l3_c3b_array[$i]   : 0];
            $l3_c3bg_result[]   = [$i, isset($l3_c3bg_array[$i])  ? $l3_c3bg_array[$i]  : 0];
            $l3_l1_result[]     = [$i, isset($l3_l1_array[$i])    ? $l3_l1_array[$i]    : 0];
            $l1_c3bg_result[]   = [$i, isset($l1_c3bg_array[$i])  ? $l1_c3bg_array[$i]  : 0];
            $c3bg_c3b_result[]  = [$i, isset($c3bg_c3b_array[$i]) ? $c3bg_c3b_array[$i] : 0];
            $l6_l3_result[]     = [$i, isset($l6_l3_array[$i])    ? $l6_l3_array[$i]    : 0];
            $l8_l6_result[]     = [$i, isset($l8_l6_array[$i])    ? $l8_l6_array[$i]    : 0];
        }

        $result = array();
        $result['l3_c3b']   = json_encode($l3_c3b_result);
        $result['l3_c3bg']  = json_encode($l3_c3bg_result);
        $result['l3_l1']    = json_encode($l3_l1_result);
        $result['l1_c3bg']  = json_encode($l1_c3bg_result);
        $result['c3bg_c3b'] = json_encode($c3bg_c3b_result);
        $result['l6_l3']    = json_encode($l6_l3_result);
        $result['l8_l6']    = json_encode($l8_l6_result);

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

    public function getDataByDays(){
        $request        = request();
        $budget_month   = $request->budget_month;
        $quantity_month = $request->quantity_month;
        $quality_month  = $request->quality_month;

        $budget     = $this->getBudget($budget_month);
        $quantity   = $this->getQuantity($quantity_month);
        $quality    = $this->getQuality($quality_month);

        $result['budget']   = $budget;
        $result['quantity'] = $quantity;
        $result['quality']  = $quality;

        return $result;
    }

    public function getDataByWeeks(){
        $result = $this->prepareDataByWeeks();
        return $result;
    }

    public function getDataByMonths(){
        $result = $this->prepareDataByMonths();
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
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
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
                        'me'    => ['$sum' => '$spent'],
                        're'    => ['$sum' => '$revenue'],
                        'c3b'   => ['$sum' => '$c3b'],
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

        if ($type == 'budget') {
            $result['budget']   = $this->getBudgetByMonths($query_chart);
        }
        else if ($type == 'quantity') {
            $result['quantity'] = $this->getQuantityByMonths($query_chart);
        }
        else if ($type == 'quality') {
            $result['quality']  = $this->getQualityByMonths($query_chart);
        } else {
            $result['budget']   = $this->getBudgetByMonths($query_chart);
            $result['quantity'] = $this->getQuantityByMonths($query_chart);
            $result['quality']  = $this->getQualityByMonths($query_chart);
        }

        return $result;
    }

    private function getBudgetByMonths($query_chart){

        $config     = Config::getByKeys(['USD_VND', 'USD_THB']);
        $usd_vnd    = $config['USD_VND'];
        $usd_thb    = $config['USD_VND'];

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

            $me         = $item_result['me'] * $usd_vnd;
            $re         = $item_result['re'] / $usd_thb * $usd_vnd;

            $total_me   += $me;
            $total_re   += $re;

            @$me_array[$month]   += $me;
            @$re_array[$month]   += $re;
            @$c3b_array[$month]  += $item_result['c3b']   ? $me / $item_result['c3b']     : 0 ;
            @$c3bg_array[$month] += $item_result['c3bg']  ? $me / $item_result['c3bg']    : 0 ;
            @$l1_array[$month]   += $item_result['l1']    ? $me / $item_result['l1']      : 0 ;
            @$l3_array[$month]   += $item_result['l3']    ? $me / $item_result['l3']      : 0 ;
            @$l6_array[$month]   += $item_result['l6']    ? $me / $item_result['l6']      : 0 ;
            @$l8_array[$month]   += $item_result['l8']    ? $me / $item_result['l8']      : 0 ;

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

    private function getQuantityByMonths($query_chart){

        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();

        foreach ($query_chart as $item_result) {
            $month = $this->getMonths($item_result['_id']);

            @$c3b_array[$month]  += $item_result['c3b']   ? $item_result['c3b']     : 0 ;
            @$c3bg_array[$month] += $item_result['c3bg']  ? $item_result['c3bg']    : 0 ;
            @$l1_array[$month]   += $item_result['l1']    ? $item_result['l1']      : 0 ;
            @$l3_array[$month]   += $item_result['l3']    ? $item_result['l3']      : 0 ;
            @$l6_array[$month]   += $item_result['l6']    ? $item_result['l6']      : 0 ;
            @$l8_array[$month]   += $item_result['l8']    ? $item_result['l8']      : 0 ;

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

    private function getTotalDataByMonths($query_chart){
        $c3b_array  = array();
        $c3bg_array = array();
        $l1_array   = array();
        $l3_array   = array();
        $l6_array   = array();
        $l8_array   = array();

        foreach ($query_chart as $item_result) {
            $month = $this->getMonths($item_result['_id']);

            @$c3b_array[$month]  += $item_result['c3b']   ? $item_result['c3b']     : 0 ;
            @$c3bg_array[$month] += $item_result['c3bg']  ? $item_result['c3bg']    : 0 ;
            @$l1_array[$month]   += $item_result['l1']    ? $item_result['l1']      : 0 ;
            @$l3_array[$month]   += $item_result['l3']    ? $item_result['l3']      : 0 ;
            @$l6_array[$month]   += $item_result['l6']    ? $item_result['l6']      : 0 ;
            @$l8_array[$month]   += $item_result['l8']    ? $item_result['l8']      : 0 ;

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
        $result['c3b']      = $c3b_result;
        $result['c3bg']     = $c3bg_result;
        $result['l1']       = $l1_result;
        $result['l3']       = $l3_result;
        $result['l6']       = $l6_result;
        $result['l8']       = $l8_result;

        return $result;
    }

    private function getQualityByMonths($query_chart){

        $total = $this->getTotalDataByMonths($query_chart);

        $l3_c3b_array   = array();
        $l3_c3bg_array  = array();
        $l3_l1_array    = array();
        $l1_c3bg_array  = array();
        $c3bg_c3b_array = array();
        $l6_l3_array    = array();
        $l8_l6_array    = array();

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
        }

        $l3_c3b_result   = array();
        $l3_c3bg_result  = array();
        $l3_l1_result    = array();
        $l1_c3bg_result  = array();
        $c3bg_c3b_result = array();
        $l6_l3_result    = array();
        $l8_l6_result    = array();

        for ($i = 1; $i <= 12; $i++) {
            $l3_c3b_result[]    = [$i, isset($l3_c3b_array[$i])   ? $l3_c3b_array[$i]   : 0];
            $l3_c3bg_result[]   = [$i, isset($l3_c3bg_array[$i])  ? $l3_c3bg_array[$i]  : 0];
            $l3_l1_result[]     = [$i, isset($l3_l1_array[$i])    ? $l3_l1_array[$i]    : 0];
            $l1_c3bg_result[]   = [$i, isset($l1_c3bg_array[$i])  ? $l1_c3bg_array[$i]  : 0];
            $c3bg_c3b_result[]  = [$i, isset($c3bg_c3b_array[$i]) ? $c3bg_c3b_array[$i] : 0];
            $l6_l3_result[]     = [$i, isset($l6_l3_array[$i])    ? $l6_l3_array[$i]    : 0];
            $l8_l6_result[]     = [$i, isset($l8_l6_array[$i])    ? $l8_l6_array[$i]    : 0];
        }

        $result = array();
        $result['l3_c3b']   = json_encode($l3_c3b_result);
        $result['l3_c3bg']  = json_encode($l3_c3bg_result);
        $result['l3_l1']    = json_encode($l3_l1_result);
        $result['l1_c3bg']  = json_encode($l1_c3bg_result);
        $result['c3bg_c3b'] = json_encode($c3bg_c3b_result);
        $result['l6_l3']    = json_encode($l6_l3_result);
        $result['l8_l6']    = json_encode($l8_l6_result);

        return $result;
    }
}
