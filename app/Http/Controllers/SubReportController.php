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

    public function getFilter(){
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
		$table['c3b'] = [];
		$table['c3bg'] = [];

		$date_time   = date('Y-m-d');

		$contacts = Contact::where( 'submit_time', '>=', strtotime( "midnight" ) * 1000 )
		                   ->where( 'submit_time', '<', strtotime( "tomorrow" ) * 1000 )
		                   ->whereIn( 'clevel', [ 'c3', 'c3b', 'c3bg' ] )
		                   ->get()
		                   ->groupBy( function ( $contact ) {
			                   return (int) date( "H", $contact->submit_time / 1000 );
		                   } )->transform( function ( $item, $k ) {
									return $item->groupBy( function ( $i ) {
										return (string) $i->clevel;
									} );
								} );

		$contacts_week = Contact::where( 'submit_time', '>=', strtotime( "midnight" ) * 1000 - 7 * 86400000)
		                   ->where( 'submit_time', '<', strtotime( "tomorrow" ) * 1000 )
		                   ->whereIn( 'clevel', [ 'c3', 'c3b', 'c3bg' ] )
		                   ->get()
		                   ->groupBy( function ( $contact ) {
			                   return (int) date( "H", $contact->submit_time / 1000 );
		                   } )->transform( function ( $item, $k ) {
				return $item->groupBy( function ( $i ) {
					return (string) $i->clevel;
				} );
			} );

		for ($i = 0; $i < 24; $i++){
			if(isset($contacts[$i]['c3'])){
				$table['c3'][$i] =  count($contacts[$i]['c3']);
				$c3_line[] =  [$i, count($contacts[$i]['c3'])];
				$c3_week_line[] =  [$i, count($contacts_week[$i]['c3'])];
			}
			else{
				$table['c3'][$i] =  0;
				$c3_line[] =  [$i, 0];
				$c3_week_line[] =  [$i, 0];
			}
			if(isset($contacts[$i]['c3b']))
			{
				$table['c3b'][$i] =  count($contacts[$i]['c3b']);
				$c3b_line[] =  [$i, count($contacts[$i]['c3b'])];
				$c3b_week_line[] =  [$i, count($contacts_week[$i]['c3b'])];
			}
			else
			{
				$table['c3b'][$i] =  0;
				$c3b_line[] =  [$i, 0];
				$c3b_week_line[] =  [$i, 0];
			}
			if(isset($contacts[$i]['c3bg'])){
				$table['c3bg'][$i] =  count($contacts[$i]['c3bg']);
				$c3bg_line[] =  [$i, count($contacts[$i]['c3bg'])];
				$c3bg_week_line[] =  [$i, count($contacts_week[$i]['c3bg'])];
			}
			else
			{
				$table['c3bg'][$i] =  0;
				$c3bg_line[] =  [$i, 0];
				$c3bg_week_line[] =  [$i, 0];
			}
		}

		$chart['c3']    = json_encode($c3_line);
		$chart['c3b']     = json_encode($c3b_line);
		$chart['c3bg']     = json_encode($c3bg_line);
		$chart['c3_week']    = json_encode($c3_week_line);
		$chart['c3b_week']     = json_encode($c3b_week_line);
		$chart['c3bg_week']     = json_encode($c3bg_week_line);

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
		$table['c3b'] = [];
		$table['c3bg'] = [];

		$date_time   = date('Y-m-d');

		$contacts = Contact::where( 'submit_time', '>=', strtotime( "midnight" ) * 1000 )
		                   ->where( 'submit_time', '<', strtotime( "tomorrow" ) * 1000 )
		                   ->whereIn( 'clevel', [ 'c3', 'c3b', 'c3bg' ] )
		                   ->get()
		                   ->groupBy( function ( $contact ) {
			                   return (int) date( "H", $contact->submit_time / 1000 );
		                   } )->transform( function ( $item, $k ) {
				return $item->groupBy( function ( $i ) {
					return (string) $i->clevel;
				} );
			} );

		$contacts_week = Contact::where( 'submit_time', '>=', strtotime( "midnight" ) * 1000 - 7 * 86400000)
		                        ->where( 'submit_time', '<', strtotime( "tomorrow" ) * 1000 )
		                        ->whereIn( 'clevel', [ 'c3', 'c3b', 'c3bg' ] )
		                        ->get()
		                        ->groupBy( function ( $contact ) {
			                        return (int) date( "H", $contact->submit_time / 1000 );
		                        } )->transform( function ( $item, $k ) {
				return $item->groupBy( function ( $i ) {
					return (string) $i->clevel;
				} );
			} );

		for ($i = 0; $i < 24; $i++){
			if(isset($contacts[$i]['c3'])){
				$table['c3'][$i] =  count($contacts[$i]['c3']);
				$c3_line[] =  [$i, count($contacts[$i]['c3'])];
				$c3_week_line[] =  [$i, count($contacts_week[$i]['c3'])];
			}
			else{
				$table['c3'][$i] =  0;
				$c3_line[] =  [$i, 0];
				$c3_week_line[] =  [$i, 0];
			}
			if(isset($contacts[$i]['c3b']))
			{
				$table['c3b'][$i] =  count($contacts[$i]['c3b']);
				$c3b_line[] =  [$i, count($contacts[$i]['c3b'])];
				$c3b_week_line[] =  [$i, count($contacts_week[$i]['c3b'])];
			}
			else
			{
				$table['c3b'][$i] =  0;
				$c3b_line[] =  [$i, 0];
				$c3b_week_line[] =  [$i, 0];
			}
			if(isset($contacts[$i]['c3bg'])){
				$table['c3bg'][$i] =  count($contacts[$i]['c3bg']);
				$c3bg_line[] =  [$i, count($contacts[$i]['c3bg'])];
				$c3bg_week_line[] =  [$i, count($contacts_week[$i]['c3bg'])];
			}
			else
			{
				$table['c3bg'][$i] =  0;
				$c3bg_line[] =  [$i, 0];
				$c3bg_week_line[] =  [$i, 0];
			}
		}

		$chart['c3']    = json_encode($c3_line);
		$chart['c3b']     = json_encode($c3b_line);
		$chart['c3bg']     = json_encode($c3bg_line);
		$chart['c3_week']    = json_encode($c3_week_line);
		$chart['c3b_week']     = json_encode($c3b_week_line);
		$chart['c3bg_week']     = json_encode($c3bg_week_line);

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
			'chart',
			'data_where',
			'date_time'
		));
	}

}
