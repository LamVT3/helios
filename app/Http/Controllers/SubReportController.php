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
use App\UserKpi;
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
        $page_title = "Line Report | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'sub-report-line';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Line Report </span>";

        $sources        = Source::all();
        $teams          = Team::all();
        $marketers      = User::all();
        $campaigns      = Campaign::where('is_active', 1)->get();
        $page_size      = Config::getByKey('PAGE_SIZE');
        $subcampaigns   = Subcampaign::where('is_active', 1)->get();

        $budget     = $this->getBudget();
        $quantity   = $this->getQuantity();
        $quality    = $this->getQuality();
	    $C3AC3B     = $this->getC3AC3B();

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
            'quality',
            'C3AC3B'
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
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
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
                $re         = $item_result['re'] ? $this->convert_revenue($item_result['re'])   : 0;

                $total_me   += $me;
                $total_re   += $re;

                $me_array[(int)($day[2])]   = $me;
                $re_array[(int)($day[2])]   = $re;
                $c3b_array[(int)($day[2])]  = $item_result['c3b']   ? round ($me / $item_result['c3b'], 2)     : 0 ;
                $c3bg_array[(int)($day[2])] = $item_result['c3bg']  ? round ($me / $item_result['c3bg'], 2)    : 0 ;
                $l1_array[(int)($day[2])]   = $item_result['l1']    ? round ($me / $item_result['l1'], 2)      : 0 ;
                $l3_array[(int)($day[2])]   = $item_result['l3']    ? round ($me / $item_result['l3'], 2)      : 0 ;
                $l6_array[(int)($day[2])]   = $item_result['l6']    ? round ($me / $item_result['l6'], 2)      : 0 ;
                $l8_array[(int)($day[2])]   = $item_result['l8']    ? round ($me / $item_result['l8'], 2)      : 0 ;
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
                        'c3'    => ['$sum' => ['$sum' => ['$c3a', '$c3b', '$c3bg']]],
                        'c3a'   => ['$sum' => '$c3a'],
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
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id'   => '$date',
                        'c3'    => ['$sum' => ['$sum' => ['$c3a', '$c3b', '$c3bg']]],
                        'c3a'   => ['$sum' => '$c3a'],
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

        $source_id = request()->source_id;
        $marketer_id = request()->marketer_id;
        $team_id = request()->team_id;
        $campaign_id = request()->campaign_id;
        $subcampaign_id = request()->subcampaign_id;
        $channel_name = request()->channel_name;
        $channel_id = request()->channel_id;

        $isEmpy = false;
        if($channel_name != "" || $source_id != "" || $marketer_id != "" ||$team_id != "" ||$campaign_id != "" ||$subcampaign_id != "" || $channel_id!= ""){
            $isEmpy =true;
        }

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

        if(count($ad_id) >= 0 && $isEmpy){
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
	
	private function getWhereDataByCreatorID(){
		$request    = request();
        $data_where = array();
        if ($request->source_id) {
            $data_where['source_id']        = $request->source_id;
        }
        if ($request->team_id) {
            $data_where['team_id']          = $request->team_id;
        }
//        if ($request->marketer_id) {
//            $data_where['creator_id']      = $request->marketer_id;
//        }
        if ($request->campaign_id) {
            $data_where['campaign_id']      = $request->campaign_id;
        }
        if ($request->subcampaign_id) {
            $data_where['subcampaign_id']   = $request->subcampaign_id;
        }
        if ($request->channel_id) {
            $data_where['channel_id']   = $request->channel_id;
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

	private function getAdsWhereData(){
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
            $last_day_this_month    = date('Y-' . $month .'-'.$d); /* ngày cuối cùng của tháng */
        }else if($request->month){
            $month  = request('month');
            $year   = date('Y'); /* nam hien tai*/
            $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
            $first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
            $last_day_this_month    = date('Y-' . $month .'-'.$d); /* ngày cuối cùng của tháng */
        }else {
            $month  = date('m'); /* thang hien tai */
            $year   = date('Y'); /* nam hien tai*/
            $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
            $first_day_this_month = date('Y-m-01'); /* ngày đàu tiên của tháng */
            $last_day_this_month = date('Y-m-'.$d); /* ngày cuối cùng của tháng */
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
			$temp['c3'][$timestamp] = 0;
			$temp['c3a'][$timestamp] = 0;
			$temp['c3b'][$timestamp] = 0;
			$temp['c3bg'][$timestamp] = 0;
			for ($i = 0; $i < 24; $i++){
				$data_c3[ $timestamp ][$i]  = 0;
				$data_c3b[ $timestamp ][$i]  = 0;
				$data_c3bg[ $timestamp ][$i]  = 0;
			}
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

		$_contacts_c3 = Contact::raw( function ( $collection ) use ($first_day_this_month, $last_day_this_month) {
			$match = [
				[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
				[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
				[
					'$group' => [
						'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
						'c3' => [ '$sum' => 1 ],
					]
				]
			];
			return $collection->aggregate( $match );
		} );
		$_contacts_c3b = Contact::raw( function ( $collection ) use ($first_day_this_month, $last_day_this_month) {
			$match = [
				[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
				[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
				[
					'$group' => [
						'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
						'c3b' => [ '$sum' => 1 ],
					]
				]
			];
			return $collection->aggregate( $match );
		} );
		$_contacts_c3bg = Contact::raw( function ( $collection ) use ($first_day_this_month, $last_day_this_month) {
			$match = [
				[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
				[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
				[
					'$group' => [
						'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
						'c3bg' => [ '$sum' => 1 ],
					]
				]
			];
			return $collection->aggregate( $match );
		} );

		foreach ( $_contacts_c3 as $item_result ) {
			$timestamp = $item_result['_id'];
			if (isset($data_c3[$timestamp->submit_date]))
				$data_c3[$timestamp->submit_date][$timestamp->submit_hour] += (int)$item_result->c3;
		}
		foreach ( $_contacts_c3b as $item_result ) {
			$timestamp = $item_result['_id'];
			if (isset($data_c3b[$timestamp->submit_date]))
				$data_c3b[$timestamp->submit_date][$timestamp->submit_hour] += (int)$item_result->c3b;
		}
		foreach ( $_contacts_c3bg as $item_result ) {
			$timestamp = $item_result['_id'];
			if (isset($data_c3bg[$timestamp->submit_date]))
				$data_c3bg[$timestamp->submit_date][$timestamp->submit_hour] += (int)$item_result->c3bg;
		}

		for ($h = 0; $h <= $current_hour; $h++){
			foreach ($array_month as $key => $timestamp){
				$temp['c3'][$timestamp] += $data_c3[$timestamp][$h];
				$temp['c3b'][$timestamp] += $data_c3b[$timestamp][$h];
				$temp['c3bg'][$timestamp] += $data_c3bg[$timestamp][$h];
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


		$contacts_c3 = Contact::raw( function ( $collection ) {
			$match = [
				[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( "midnight" ) * 1000, '$lte' => strtotime( "tomorrow" ) * 1000 ] ] ],
				[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
				[
					'$group' => [
						'_id' => '$submit_hour',
						'c3' => [ '$sum' => 1 ],
					]
				]
			];
			return $collection->aggregate( $match );
		} );
		$contacts_c3b = Contact::raw( function ( $collection ) {
			$match = [
				[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( "midnight" ) * 1000, '$lte' => strtotime( "tomorrow" ) * 1000 ] ] ],
				[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
				[
					'$group' => [
						'_id' => '$submit_hour',
						'c3b' => [ '$sum' => 1 ],
					]
				]
			];
			return $collection->aggregate( $match );
		} );
		$contacts_c3bg = Contact::raw( function ( $collection ) {
			$match = [
				[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( "midnight" ) * 1000, '$lte' => strtotime( "tomorrow" ) * 1000 ] ] ],
				[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
				[
					'$group' => [
						'_id' => '$submit_hour',
						'c3bg' => [ '$sum' => 1 ],
					]
				]
			];
			return $collection->aggregate( $match );
		} );
		$contacts_c3_week = Contact::raw( function ( $collection ) {
			$match = [
				[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( "midnight" ) * 1000 - 7 * 86400000, '$lte' => strtotime( "tomorrow" ) * 1000 ] ] ],
				[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
				[
					'$group' => [
						'_id' => '$submit_hour',
						'c3' => [ '$sum' => 1 ],
					]
				]
			];
			return $collection->aggregate( $match );
		} );
		$contacts_c3b_week = Contact::raw( function ( $collection ) {
			$match = [
				[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( "midnight" ) * 1000 - 7 * 86400000, '$lte' => strtotime( "tomorrow" ) * 1000 ] ] ],
				[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
				[
					'$group' => [
						'_id' => '$submit_hour',
						'c3b' => [ '$sum' => 1 ],
					]
				]
			];
			return $collection->aggregate( $match );
		} );
		$contacts_c3bg_week = Contact::raw( function ( $collection ) {
			$match = [
				[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( "midnight" ) * 1000 - 7 * 86400000, '$lte' => strtotime( "tomorrow" ) * 1000 ] ] ],
				[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
				[
					'$group' => [
						'_id' => '$submit_hour',
						'c3bg' => [ '$sum' => 1 ],
					]
				]
			];
			return $collection->aggregate( $match );
		} );

		foreach ( $contacts_c3 as $item_result ) {
			$hour = (int)$item_result['_id'];
			$table['c3'][$hour] += (int)$item_result->c3;
		}
		foreach ( $contacts_c3b as $item_result ) {
				$hour = $item_result['_id'];
				$table['c3b'][$hour] += $item_result->c3b;
		}
		foreach ( $contacts_c3bg as $item_result ) {
				$hour = $item_result['_id'];
				$table['c3bg'][$hour] += $item_result->c3bg;
		}
		foreach ( $contacts_c3_week as $item_result ) {
				$hour = $item_result['_id'];
				$table['c3_week'][$hour] += $item_result->c3;
		}
		foreach ( $contacts_c3b_week as $item_result ) {
				$hour = $item_result['_id'];
				$table['c3b_week'][$hour] += $item_result->c3b;
		}
		foreach ( $contacts_c3bg_week as $item_result ) {
				$hour = $item_result['_id'];
				$table['c3bg_week'][$hour] += $item_result->c3bg;
		}

		for ($i = 0; $i < 24; $i++){
			$c3_line[] =  [$i, $table['c3'][$i]];
			$c3b_line[] =  [$i, $table['c3b'][$i]];
			$c3bg_line[] =  [$i, $table['c3bg'][$i]];

			$table['c3_week'][$i] = intval( round($table['c3_week'][$i] / 7));
			$table['c3b_week'][$i] = intval( round($table['c3b_week'][$i] / 7));
			$table['c3bg_week'][$i] = intval( round($table['c3bg_week'][$i] / 7));

			$c3_week_line[] =  [$i, $table['c3_week'][$i]];
			$c3b_week_line[] =  [$i, $table['c3b_week'][$i]];
			$c3bg_week_line[] =  [$i, $table['c3bg_week'][$i]];

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

		$data_where = $this->getWhereData();
		$request        = request();
		$date_time   = $request->date_time;
		$ad_id  = $this->getAdsWhereData();

		$isEmpy = false;
		if(count($data_where) > 0){
			$isEmpy =true;
		}

		foreach ($array_month as $key => $timestamp){
			$temp['c3'][$timestamp] = 0;
			$temp['c3b'][$timestamp] = 0;
			$temp['c3bg'][$timestamp] = 0;
			for ($i = 0; $i < 24; $i++){
				$data_c3[ $timestamp ][$i]  = 0;
				$data_c3b[ $timestamp ][$i]  = 0;
				$data_c3bg[ $timestamp ][$i]  = 0;
			}
		}

		$_contacts_c3 = Contact::raw( function ( $collection ) use ($ad_id, $first_day_this_month, $last_day_this_month, $isEmpy) {
			if (count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3' => [ '$sum' => 1 ],
						]
					]
				];
			} else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );
		$_contacts_c3b = Contact::raw( function ( $collection ) use ($ad_id, $first_day_this_month, $last_day_this_month, $isEmpy) {
			if (count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3b' => [ '$sum' => 1 ],
						]
					]
				];
			} else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3b' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );
		$_contacts_c3bg = Contact::raw( function ( $collection ) use ($ad_id, $first_day_this_month, $last_day_this_month, $isEmpy) {
			if (count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3bg' => [ '$sum' => 1 ],
						]
					]
				];
			} else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3bg' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );

		foreach ( $_contacts_c3 as $item_result ) {
			$timestamp = $item_result['_id'];
			if (isset($data_c3[$timestamp->submit_date]))
				$data_c3[$timestamp->submit_date][$timestamp->submit_hour] += (int)$item_result->c3;
		}
		foreach ( $_contacts_c3b as $item_result ) {
			$timestamp = $item_result['_id'];
			if (isset($data_c3b[$timestamp->submit_date]))
				$data_c3b[$timestamp->submit_date][$timestamp->submit_hour] += (int)$item_result->c3b;
		}
		foreach ( $_contacts_c3bg as $item_result ) {
			$timestamp = $item_result['_id'];
			if (isset($data_c3bg[$timestamp->submit_date]))
				$data_c3bg[$timestamp->submit_date][$timestamp->submit_hour] += (int)$item_result->c3bg;
		}

		for ($h = 0; $h <= $current_hour; $h++){
			foreach ($array_month as $key => $timestamp){
				$temp['c3'][$timestamp] += $data_c3[$timestamp][$h];
				$temp['c3b'][$timestamp] += $data_c3b[$timestamp][$h];
				$temp['c3bg'][$timestamp] += $data_c3bg[$timestamp][$h];
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

		for ($i = 0; $i < 24; $i++){
			$table['c3b'][ $i ]       = 0;
			$table['c3bg'][ $i ]      = 0;
			$table['c3'][ $i ]        = 0;
			$table['c3b_week'][ $i ]  = 0;
			$table['c3bg_week'][ $i ] = 0;
			$table['c3_week'][ $i ]   = 0;
		}

		$contacts_c3 = Contact::raw( function ( $collection ) use ($ad_id, $date_time, $isEmpy) {
			if (count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3' => [ '$sum' => 1 ],
						]
					]
				];
			}
			else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );
		$contacts_c3b = Contact::raw( function ( $collection ) use ($ad_id, $date_time, $isEmpy) {
			if (count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3b' => [ '$sum' => 1 ],
						]
					]
				];
			}
			else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3b' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );
		$contacts_c3bg = Contact::raw( function ( $collection ) use ($ad_id, $date_time, $isEmpy) {
			if (count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3bg' => [ '$sum' => 1 ],
						]
					]
				];
			}
			else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3bg' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );
		$contacts_c3_week = Contact::raw( function ( $collection ) use ($ad_id, $date_time, $isEmpy) {
			if (count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000  - 7 * 86400000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3' => [ '$sum' => 1 ],
						]
					]
				];
			}
			else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000  - 7 * 86400000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );
		$contacts_c3b_week = Contact::raw( function ( $collection ) use ($ad_id, $date_time, $isEmpy) {
			if (count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000  - 7 * 86400000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3b' => [ '$sum' => 1 ],
						]
					]
				];
			}
			else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000  - 7 * 86400000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3b' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );
		$contacts_c3bg_week = Contact::raw( function ( $collection ) use ($ad_id, $date_time, $isEmpy) {
			if (count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000  - 7 * 86400000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3bg' => [ '$sum' => 1 ],
						]
					]
				];
			}
			else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $date_time ) * 1000  - 7 * 86400000, '$lte' => strtotime( $date_time ) * 1000 + 86400000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
					[
						'$group' => [
							'_id' => '$submit_hour',
							'c3bg' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );

		foreach ( $contacts_c3 as $item_result ) {
			$hour = (int)$item_result['_id'];
			$table['c3'][$hour] += (int)$item_result->c3;
		}
		foreach ( $contacts_c3b as $item_result ) {
			$hour = $item_result['_id'];
			$table['c3b'][$hour] += $item_result->c3b;
		}
		foreach ( $contacts_c3bg as $item_result ) {
			$hour = $item_result['_id'];
			$table['c3bg'][$hour] += $item_result->c3bg;
		}
		foreach ( $contacts_c3_week as $item_result ) {
			$hour = $item_result['_id'];
			$table['c3_week'][$hour] += $item_result->c3;
		}
		foreach ( $contacts_c3b_week as $item_result ) {
			$hour = $item_result['_id'];
			$table['c3b_week'][$hour] += $item_result->c3b;
		}
		foreach ( $contacts_c3bg_week as $item_result ) {
			$hour = $item_result['_id'];
			$table['c3bg_week'][$hour] += $item_result->c3bg;
		}

		for ($i = 0; $i < 24; $i++){
			$c3_line[] =  [$i, $table['c3'][$i]];
			$c3b_line[] =  [$i, $table['c3b'][$i]];
			$c3bg_line[] =  [$i, $table['c3bg'][$i]];

			$table['c3_week'][$i] = intval( round($table['c3_week'][$i] / 7));
			$table['c3b_week'][$i] = intval( round($table['c3b_week'][$i] / 7));
			$table['c3bg_week'][$i] = intval( round($table['c3bg_week'][$i] / 7));

			$c3_week_line[] =  [$i, $table['c3_week'][$i]];
			$c3b_week_line[] =  [$i, $table['c3b_week'][$i]];
			$c3bg_week_line[] =  [$i, $table['c3bg_week'][$i]];

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
		$page_css = array('selectize.default.css');
		$no_main_header = FALSE; //set true for lock.php and login.php
		$active = 'channel-report';
		$breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Channel Report </span>";

		$sources        = Source::all();
		$teams          = Team::all();
		$marketers      = User::all();
		$campaigns      = Campaign::where('is_active', 1)->get();
		$page_size      = Config::getByKey('PAGE_SIZE');
		$subcampaigns   = Subcampaign::where('is_active', 1)->get();
		$channels        = Channel::where('is_active', 1)->orderBy('name')->get();

		$date_time = $start_date = $end_date = date('Y-m-d');

		$data = $this->getChannel($start_date, $start_date);
		$data_reason = $this->getChannelReason($start_date, $start_date);

		$table = $data['table'];
		$array_channel = $data['array_channel'];
		$array_sum = $data['array_sum'];


		return view('pages.channel-report', compact(
			'page_title',
			'page_css',
			'no_main_header',
			'active',
			'breadcrumbs',
			'sources',
			'teams',
			'marketers',
			'channels',
			'campaigns',
			'page_size',
			'subcampaigns',
			'table',
			'data_where',
			'date_time',
			'array_channel',
			'array_sum',
			'data_reason'
		));
	}

	private function getChannel($start_date, $end_date, $type = 'TOA'){
		$array_channel = array();

		$data_where = $this->getWhereDataByCreatorID();

		if (request()->channel_name){
			$channels_arr       = explode(',',request()->channel_name);
			$channels           = Channel::whereIn('name', $channels_arr)->get();
			$channels_id        = Channel::whereIn('name', $channels_arr)->get()->pluck('_id');
			$query              = Ad::where($data_where)->whereIn('channel_id', $channels_id);
			$arr_ad             = $query->pluck('channel_id','_id');
			$ad_id              = $query->pluck('_id')->toArray();
		}
		else {
			if (request()->marketer_id){
				$arr_channels_id = UserKpi::where('user_id', request()->marketer_id)->pluck('channel_id')->toArray();
				$channels           = Channel::whereIn('_id', $arr_channels_id)->get();
				$query              = Ad::where($data_where)->whereIn('channel_id', $arr_channels_id);
			}
			else{
				$channels           = Channel::all();
				$query              = Ad::where($data_where);
			}
			$arr_ad             = $query->pluck('channel_id','_id');
			$ad_id              = $query->pluck('_id')->toArray();
		}

		foreach ($channels as $key => $channel) {
			$array_channel[$channel->_id] = $channel->name;
		}

		$array_channel['unknown'] = 'Unknown';
		$array_channel[""] = 'Unknown';

		foreach ($array_channel as $channel) {
			$table['c3'][$channel] = 0;
			$table['c3b'][$channel] = 0;
			$table['c3bg'][$channel] = 0;
			$table['l1'][$channel]   = 0;
			$table['l3'][$channel]   = 0;
			$table['l6'][$channel]   = 0;
			$table['l8'][$channel]   = 0;
            $table['spent'][$channel]   = 0;

			$table['c3_week'][$channel] = 0;
			$table['c3b_week'][$channel] = 0;
			$table['c3bg_week'][$channel] = 0;
		}

		$source_id = request()->source_id;
		$marketer_id = request()->marketer_id;
		$team_id = request()->team_id;
		$campaign_id = request()->campaign_id;
		$subcampaign_id = request()->subcampaign_id;
		$channel_name = request()->channel_name;

		$isEmpy = false;
		if($channel_name != "" || $source_id != "" || $marketer_id != "" ||$team_id != "" ||$campaign_id != "" ||$subcampaign_id != ""){
			$isEmpy =true;
		}

		if ($type === 'TOA'){
			if(count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[
						'$group' => [
							'_id'   => '$ad_id',
							'c3'    => ['$sum' => '$c3'],
							'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
							'c3bg'  => ['$sum' => '$c3bg'],
							'l1'    => ['$sum' => '$l1'],
							'l3'    => ['$sum' => '$l3'],
							'l6'    => ['$sum' => '$l6'],
							'l8'    => ['$sum' => '$l8'],
                            'spent' => ['$sum' => '$spent'],
						]
					]
				];
				$match_week = [
					['$match' => ['date' => ['$gte' => date('Y-m-d', strtotime( $start_date ) - 7 * 86400), '$lte' => $end_date]]],
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[
						'$group' => [
							'_id'   => '$ad_id',
							'c3'    => ['$sum' => '$c3'],
							'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
							'c3bg'  => ['$sum' => '$c3bg'],
						]
					]
				];
			}else{
				$match = [
					['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
					[
						'$group' => [
							'_id'   => '$ad_id',
							'c3'    => ['$sum' => '$c3'],
							'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
							'c3bg'  => ['$sum' => '$c3bg'],
							'l1'    => ['$sum' => '$l1'],
							'l3'    => ['$sum' => '$l3'],
							'l6'    => ['$sum' => '$l6'],
							'l8'    => ['$sum' => '$l8'],
                            'spent' => ['$sum' => '$spent'],
						]
					]
				];
				$match_week = [
					['$match' => ['date' => ['$gte' => date('Y-m-d', strtotime( $start_date ) - 7 * 86400), '$lte' => $end_date]]],
					[
						'$group' => [
							'_id'   => '$ad_id',
							'c3'    => ['$sum' => '$c3'],
							'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
							'c3bg'  => ['$sum' => '$c3bg'],
						]
					]
				];
			}

			$query_chart = AdResult::raw(function ($collection) use ($match) {
				return $collection->aggregate($match);
			});
			$query_chart_week = AdResult::raw(function ($collection) use ($match_week) {
				return $collection->aggregate($match_week);
			});

			foreach ( $query_chart as $item_result ) {
				$channel_id = @$arr_ad[$item_result['_id']];
				if ( $channel_id ) {
					$channel    = @$array_channel[ $channel_id ];
					if ( ! $channel ) {
						$channel = 'Unknown';
					}
				} else {
					$channel = 'Unknown';
				}

				$table['c3'][ $channel ]   += $item_result->c3;
				$table['c3b'][ $channel ]  += $item_result->c3b;
				$table['c3bg'][ $channel ] += $item_result->c3bg;
				$table['l1'][ $channel ]   += $item_result->l1;
				$table['l3'][ $channel ]   += $item_result->l3;
				$table['l6'][ $channel ]   += $item_result->l6;
				$table['l8'][ $channel ]   += $item_result->l8;
                $table['spent'][ $channel ] += $item_result->spent;
			}
			foreach ( $query_chart_week as $item_result ) {
				$channel_id = @$arr_ad[$item_result['_id']];
				if ( $channel_id ) {
					$channel    = @$array_channel[ $channel_id ];
					if ( ! $channel ) {
						$channel = 'Unknown';
					}
				} else {
					$channel = 'Unknown';
				}

				$table['c3_week'][ $channel ]   += intval( round($item_result->c3 / 7));
				$table['c3b_week'][ $channel ]  += intval( round($item_result->c3b / 7));
				$table['c3bg_week'][ $channel ] += intval( round($item_result->c3bg / 7));
			}

			arsort($table['c3']);
			arsort($table['c3_week']);

		}else if($type === 'TOT'){
			if(count($ad_id) >= 0 && $isEmpy){
				$match = [
					['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[
						'$group' => [
							'_id'   => '$ad_id',
							'c3'    => ['$sum' => '$c3'],
							'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
							'c3bg'  => ['$sum' => '$c3bg'],
						]
					]
				];
				$match_week = [
					['$match' => ['date' => ['$gte' => date('Y-m-d', strtotime( $start_date ) - 7 * 86400), '$lte' => $end_date]]],
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[
						'$group' => [
							'_id'   => '$ad_id',
							'c3'    => ['$sum' => '$c3'],
							'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
							'c3bg'  => ['$sum' => '$c3bg'],
						]
					]
				];
			}else{
				$match = [
					['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
					[
						'$group' => [
							'_id'   => '$ad_id',
							'c3'    => ['$sum' => '$c3'],
							'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
							'c3bg'  => ['$sum' => '$c3bg'],
						]
					]
				];
				$match_week = [
					['$match' => ['date' => ['$gte' => date('Y-m-d', strtotime( $start_date ) - 7 * 86400), '$lte' => $end_date]]],
					[
						'$group' => [
							'_id'   => '$ad_id',
							'c3'    => ['$sum' => '$c3'],
							'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
							'c3bg'  => ['$sum' => '$c3bg'],
						]
					]
				];
			}

			$query_chart = AdResult::raw(function ($collection) use ($match) {
				return $collection->aggregate($match);
			});
			$query_chart_week = AdResult::raw(function ($collection) use ($match_week) {
				return $collection->aggregate($match_week);
			});

			foreach ( $query_chart as $item_result ) {
				$channel_id = @$arr_ad[$item_result['_id']];
				if ( $channel_id ) {
					$channel    = @$array_channel[ $channel_id ];
					if ( ! $channel ) {
						$channel = 'Unknown';
					}
				} else {
					$channel = 'Unknown';
				}

				$table['c3'][ $channel ]   += $item_result->c3;
				$table['c3b'][ $channel ]  += $item_result->c3b;
				$table['c3bg'][ $channel ] += $item_result->c3bg;
			}
			foreach ( $query_chart_week as $item_result ) {
				$channel_id = @$arr_ad[$item_result['_id']];
				if ( $channel_id ) {
					$channel    = @$array_channel[ $channel_id ];
					if ( ! $channel ) {
						$channel = 'Unknown';
					}
				} else {
					$channel = 'Unknown';
				}

				$table['c3_week'][ $channel ]   += intval( round($item_result->c3 / 7));
				$table['c3b_week'][ $channel ]  += intval( round($item_result->c3b / 7));
				$table['c3bg_week'][ $channel ] += intval( round($item_result->c3bg / 7));
			}

			arsort($table['c3']);
			arsort($table['c3_week']);
			$array_channel_new = [];

			$start = $start_date;
			$end = date('Y-m-d', strtotime( $end_date ) + 86400);

			$query_l1 = Contact::raw(function ($collection) use ($start, $end, $ad_id, $isEmpy) {
				if(count($ad_id) >= 0 && $isEmpy){
					$match = [
						['$match' => ['l1_time' => ['$gte' => $start, '$lte' => $end]]],
						['$match' => ['ad_id' => ['$in' => $ad_id]]],
						[
							'$group' => [
								'_id' => '$ad_id',
								'count' => ['$sum' => 1],
							]
						]
					];
				}else{
					$match = [
						['$match' => ['l1_time' => ['$gte' => $start, '$lte' => $end]]],
						[
							'$group' => [
								'_id' => '$ad_id',
								'count' => ['$sum' => 1],
							]
						]
					];
				}
				return $collection->aggregate($match);
			});

			$query_l3 = Contact::raw(function ($collection) use ($start, $end, $ad_id, $isEmpy) {
				if(count($ad_id) >= 0 && $isEmpy){
					$match = [
						['$match' => ['l3_time' => ['$gte' => $start, '$lte' => $end]]],
						['$match' => ['ad_id' => ['$in' => $ad_id]]],
						[
							'$group' => [
								'_id' => '$ad_id',
								'count' => ['$sum' => 1],
							]
						]
					];
				}else{
					$match = [
						['$match' => ['l3_time' => ['$gte' => $start, '$lte' => $end]]],
						[
							'$group' => [
								'_id' => '$ad_id',
								'count' => ['$sum' => 1],
							]
						]
					];
				}
				return $collection->aggregate($match);
			});

			$query_l6 = Contact::raw(function ($collection) use ($start, $end, $ad_id, $isEmpy) {
				if(count($ad_id) >= 0 && $isEmpy){
					$match = [
						['$match' => ['l6_time' => ['$gte' => $start, '$lte' => $end]]],
						['$match' => ['ad_id' => ['$in' => $ad_id]]],
						[
							'$group' => [
								'_id' => '$ad_id',
								'count' => ['$sum' => 1],
							]
						]
					];
				}else{
					$match = [
						['$match' => ['l6_time' => ['$gte' => $start, '$lte' => $end]]],
						[
							'$group' => [
								'_id' => '$ad_id',
								'count' => ['$sum' => 1],
							]
						]
					];
				}
				return $collection->aggregate($match);
			});

			$query_l8 = Contact::raw(function ($collection) use ($start, $end, $ad_id, $isEmpy) {
				if(count($ad_id) >= 0 && $isEmpy){
					$match = [
						['$match' => ['l8_time' => ['$gte' => $start, '$lte' => $end]]],
						['$match' => ['ad_id' => ['$in' => $ad_id]]],
						[
							'$group' => [
								'_id' => '$ad_id',
								'count' => ['$sum' => 1],
							]
						]
					];
				}else{
					$match = [
						['$match' => ['l8_time' => ['$gte' => $start, '$lte' => $end]]],
						[
							'$group' => [
								'_id' => '$ad_id',
								'count' => ['$sum' => 1],
							]
						]
					];
				}
				return $collection->aggregate($match);
			});

			$result = array();
			foreach ($query_l1 as $key => $item){
				if(isset($result[$item->id]->l1)){
					$result[$item->id]->l1 += $item->count;
				}
				@$result[$item->id]->l1 = $item->count;
			}
			foreach ($query_l3 as $key => $item){
				if(isset($result[$item->id]->l3)){
					$result[$item->id]->l3 += $item->count;
				}
				@$result[$item->id]->l3 = $item->count;
			}
			foreach ($query_l6 as $key => $item){
				if(isset($result[$item->id]->l6)){
					$result[$item->id]->l6 += $item->count;
				}
				@$result[$item->id]->l6 = $item->count;
			}
			foreach ($query_l8 as $key => $item){
				if(isset($result[$item->id]->l8)){
					$result[$item->id]->l8 += $item->count;
				}
				@$result[$item->id]->l8 = $item->count;
			}


			foreach ( $result as $key => $item_result ) {
				$channel_id = @$arr_ad[$key];
				if ( $channel_id ) {
					$channel    = @$array_channel[ $channel_id ];
					if ( ! $channel ) {
						$channel = 'Unknown';
					}
				} else {
					$channel = 'Unknown';
				}

				$table['l1'][ $channel ] += @$item_result->l1;
				$table['l3'][ $channel ] += @$item_result->l3;
				$table['l6'][ $channel ] += @$item_result->l6;
				$table['l8'][ $channel ] += @$item_result->l8;
			}
		}

		$array_channel_new = [];

		if (request()->channel_name){
			$array_channel_new = $channels_arr;
		}
		else{
			foreach ($table['c3'] as $key=>$value) {
				if ($value != 0){
					$array_channel_new[] = $key;
				}

			}
			foreach ($table['l1'] as $key=>$value) {
				if ($value != 0 && !in_array($key,$array_channel_new)){
					$array_channel_new[] = $key;
				}
			}
			foreach ($table['l3'] as $key=>$value) {
				if ($value != 0 && !in_array($key,$array_channel_new)){
					$array_channel_new[] = $key;
				}
			}
			foreach ($table['l6'] as $key=>$value) {
				if ($value != 0 && !in_array($key,$array_channel_new)){
					$array_channel_new[] = $key;
				}
			}
			foreach ($table['l8'] as $key=>$value) {
				if ($value != 0 && !in_array($key,$array_channel_new)){
					$array_channel_new[] = $key;
				}
			}
		}

		$array_channel = $array_channel_new;

		$array_sum['c3']   = 0;
		$array_sum['c3b']  = 0;
		$array_sum['c3bg'] = 0;
		$array_sum['l1']   = 0;
		$array_sum['l3']   = 0;
		$array_sum['l6']   = 0;
		$array_sum['l8']   = 0;
        $array_sum['spent']   = 0;

		foreach ($array_channel as $i){
			$array_sum['c3']   += $table['c3'][$i];
			$array_sum['c3b']  += $table['c3b'][$i];
			$array_sum['c3bg'] += $table['c3bg'][$i];
			$array_sum['l1']   += $table['l1'][$i];
			$array_sum['l3']   += $table['l3'][$i];
			$array_sum['l6']   += $table['l6'][$i];
			$array_sum['l8']   += $table['l8'][$i];
            $array_sum['spent']   += $table['spent'][$i];
		}

		$data_reason = $this->getChannelReason($start_date, $end_date);

		return ['table'=>$table,'array_channel' => $array_channel, 'array_sum' => $array_sum, 'data_reason' => $data_reason, 'tyep' => $type];

	}

	private function getAdsDetail($start_date, $end_date, $type = 'TOA'){
		$array_ads = array();
		$arr_ad = array();
		$ad_id    = array();

		$channel            = Channel::where('name', request()->channel_name)->first();

		$data_where = $this->getWhereDataByCreatorID();

		if (count($data_where) >= 1) {
			$query = Ad::where($data_where)->where('channel_id',$channel->_id);
		}
		else{
			$query = Ad::where('channel_id',$channel->_id);
		}

		$arr_ad = $query->get();

		$ad_id = $query->pluck('_id')->toArray();

		foreach ($arr_ad as $key => $ad) {
			$array_ads[$ad->_id] = $ad->name;

			$table['c3'][$ad->name] = 0;
			$table['c3b'][$ad->name] = 0;
			$table['c3bg'][$ad->name] = 0;
			$table['l1'][$ad->name]   = 0;
			$table['l3'][$ad->name]   = 0;
			$table['l6'][$ad->name]   = 0;
			$table['l8'][$ad->name]   = 0;
            $table['spent'][$ad->name]  = 0;
		}

		$table['c3']['Unknown'] = 0;
		$table['c3b']['Unknown'] = 0;
		$table['c3bg']['Unknown'] = 0;
		$table['l1']['Unknown']   = 0;
		$table['l3']['Unknown']   = 0;
		$table['l6']['Unknown']   = 0;
		$table['l8']['Unknown']   = 0;
        $table['spent']['Unknown']   = 0;

		if ($type === 'TOA')
		{
			$match = [
				['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
				['$match' => ['ad_id' => ['$in' => $ad_id]]],
				[
					'$group' => [
						'_id'   => '$ad_id',
						'c3'    => ['$sum' => '$c3'],
						'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
						'c3bg'  => ['$sum' => '$c3bg'],
						'l1'    => ['$sum' => '$l1'],
						'l3'    => ['$sum' => '$l3'],
						'l6'    => ['$sum' => '$l6'],
						'l8'    => ['$sum' => '$l8'],
                        'spent' => ['$sum' => '$spent'],
					]
				]
			];


			$query_chart = AdResult::raw(function ($collection) use ($match) {
				return $collection->aggregate($match);
			});

			foreach ( $query_chart as $key => $item_result ) {
				$ad_name = @$array_ads[$item_result['_id']];

				if ( $ad_name == '' ) {
					$ad_name = 'Unknown';
				}

				$table['c3'][ $ad_name ]   += $item_result->c3;
				$table['c3b'][ $ad_name ]  += $item_result->c3b;
				$table['c3bg'][ $ad_name ] += $item_result->c3bg;
				$table['l1'][ $ad_name ]   += $item_result->l1;
				$table['l3'][ $ad_name ]   += $item_result->l3;
				$table['l6'][ $ad_name ]   += $item_result->l6;
				$table['l8'][ $ad_name ]   += $item_result->l8;
                $table['spent'][ $ad_name ] += $item_result->spent;
			}

			arsort($table['c3']);

		}
		else if($type === 'TOT')
		{
			$match = [
				['$match' => ['date' => ['$gte' => $start_date, '$lte' => $end_date]]],
				['$match' => ['ad_id' => ['$in' => $ad_id]]],
				[
					'$group' => [
						'_id'   => '$ad_id',
						'c3'    => ['$sum' => '$c3'],
						'c3b'   => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
						'c3bg'  => ['$sum' => '$c3bg'],
					]
				]
			];

			$query_chart = AdResult::raw(function ($collection) use ($match) {
				return $collection->aggregate($match);
			});

			foreach ( $query_chart as $item_result ) {
				$ad_name = @$array_ads[$item_result['_id']];

				if ( $ad_name == '' ) {
					$ad_name = 'Unknown';
				}

				$table['c3'][ $ad_name ]   += $item_result->c3;
				$table['c3b'][ $ad_name ]  += $item_result->c3b;
				$table['c3bg'][ $ad_name ] += $item_result->c3bg;
			}

			arsort($table['c3']);

			$start = $start_date;
			$end = date('Y-m-d', strtotime( $end_date ) + 86400);

			$query_l1 = Contact::raw(function ($collection) use ($start, $end, $ad_id) {
				$match = [
					['$match' => ['l1_time' => ['$gte' => $start, '$lte' => $end]]],
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[
						'$group' => [
							'_id' => '$ad_id',
							'count' => ['$sum' => 1],
						]
					]
				];
				return $collection->aggregate($match);
			});

			$query_l3 = Contact::raw(function ($collection) use ($start, $end, $ad_id) {
				$match = [
					['$match' => ['l3_time' => ['$gte' => $start, '$lte' => $end]]],
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[
						'$group' => [
							'_id' => '$ad_id',
							'count' => ['$sum' => 1],
						]
					]
				];
				return $collection->aggregate($match);
			});

			$query_l6 = Contact::raw(function ($collection) use ($start, $end, $ad_id) {
				$match = [
					['$match' => ['l6_time' => ['$gte' => $start, '$lte' => $end]]],
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[
						'$group' => [
							'_id' => '$ad_id',
							'count' => ['$sum' => 1],
						]
					]
				];
				return $collection->aggregate($match);
			});

			$query_l8 = Contact::raw(function ($collection) use ($start, $end, $ad_id) {
				$match = [
					['$match' => ['l8_time' => ['$gte' => $start, '$lte' => $end]]],
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[
						'$group' => [
							'_id' => '$ad_id',
							'count' => ['$sum' => 1],
						]
					]
				];
				return $collection->aggregate($match);
			});

			$result = array();
			foreach ($query_l1 as $key => $item){
				if(isset($result[$item->id]->l1)){
					$result[$item->id]->l1 += $item->count;
				}
				@$result[$item->id]->l1 = $item->count;
			}
			foreach ($query_l3 as $key => $item){
				if(isset($result[$item->id]->l3)){
					$result[$item->id]->l3 += $item->count;
				}
				@$result[$item->id]->l3 = $item->count;
			}
			foreach ($query_l6 as $key => $item){
				if(isset($result[$item->id]->l6)){
					$result[$item->id]->l6 += $item->count;
				}
				@$result[$item->id]->l6 = $item->count;
			}
			foreach ($query_l8 as $key => $item){
				if(isset($result[$item->id]->l8)){
					$result[$item->id]->l8 += $item->count;
				}
				@$result[$item->id]->l8 = $item->count;
			}

			foreach ( $result as $key => $item_result ) {
				$ad_name = @$array_ads[$key];

				if ( $ad_name && $ad_name == '' ) {
					$ad_name = 'Unknown';
				}

				$table['l1'][ $ad_name ] += @$item_result->l1;
				$table['l3'][ $ad_name ] += @$item_result->l3;
				$table['l6'][ $ad_name ] += @$item_result->l6;
				$table['l8'][ $ad_name ] += @$item_result->l8;
			}
		}

		$array_ad_new = [];

		foreach ($table['c3'] as $key=>$value) {
			if ($value != 0){
				$array_ad_new[] = $key;
			}
		}
		foreach ($table['l1'] as $key=>$value) {
			if ($value != 0 && !in_array($key,$array_ad_new)){
				$array_ad_new[] = $key;
			}
		}
		foreach ($table['l3'] as $key=>$value) {
			if ($value != 0 && !in_array($key,$array_ad_new)){
				$array_ad_new[] = $key;
			}
		}
		foreach ($table['l6'] as $key=>$value) {
			if ($value != 0 && !in_array($key,$array_ad_new)){
				$array_ad_new[] = $key;
			}
		}
		foreach ($table['l8'] as $key=>$value) {
			if ($value != 0 && !in_array($key,$array_ad_new)){
				$array_ad_new[] = $key;
			}
		}

		$array_ad = $array_ad_new;

		return ['table'=>$table,'array_ad' => $array_ad, 'type' => $type];

	}

	private function getChannelReason($start_date, $end_date){

		$channels_arr       = explode(',', request()->channel_name);
		$channels_id        = Channel::whereIn('name', $channels_arr)->get()->pluck('_id');
		$data_where         = $this->getWhereDataByCreatorID();

		if (count($channels_id) > 0){
			if (count($data_where) >= 1) {
				$ad_id = Ad::where($data_where)->whereIn('channel_id', $channels_id)->pluck('_id')->toArray();
			}
			else{
				$ad_id = Ad::whereIn('channel_id', $channels_id)->pluck('_id')->toArray();
			}
		}
		else{
			if (request()->marketer_id){
				$arr_channels_id    = UserKpi::where('user_id', request()->marketer_id)->pluck('channel_id')->toArray();
				$ad_id              = Ad::where($data_where)->whereIn('channel_id', $arr_channels_id)->pluck('_id')->toArray();

			}
			else{
				$ad_id              = $this->getAds();
			}
		}

		$array_reason = [ 'C3A_Duplicated', 'C3B_Under18', 'C3B_Duplicated15Days', 'C3A_Test' , 'C3B_SMS_Error'];
		$rs = [];

		$source_id = request()->source_id;
		$marketer_id = request()->marketer_id;
		$team_id = request()->team_id;
		$campaign_id = request()->campaign_id;
		$subcampaign_id = request()->subcampaign_id;
		$channel_name = request()->channel_name;

		$isEmpy = false;
		if($channel_name != "" || $source_id != "" || $marketer_id != "" ||$team_id != "" ||$campaign_id != "" ||$subcampaign_id != ""){
			$isEmpy =true;
		}

		if(count($ad_id) >= 0 && $isEmpy){
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
						'C3B_SMS_Error'        => [ '$sum' => '$C3B_SMS_Error' ]
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
						'C3B_SMS_Error'        => [ '$sum' => '$C3B_SMS_Error' ]
					]
				]
			];
		}

		$query_chart = AdResult::raw(function ($collection) use ($match) {
			return $collection->aggregate($match);
		});

		$rs['C3A_Duplicated'] = 0;
		$rs['C3B_Under18'] = 0;
		$rs['C3B_Duplicated15Days'] = 0;
		$rs['C3A_Test'] = 0;
		$rs['C3B_SMS_Error'] = 0;

		foreach ( $query_chart as $item_result ) {
			$rs['C3A_Duplicated']       += $item_result['C3A_Duplicated'];
			$rs['C3B_Under18']          += $item_result['C3B_Under18'];
			$rs['C3B_Duplicated15Days'] += $item_result['C3B_Duplicated15Days'];
			$rs['C3A_Test']             += $item_result['C3A_Test'];
			$rs['C3B_SMS_Error']        += $item_result['C3B_SMS_Error'];
		}

		return $rs;
	}

	public function channelReportFilter(){
		$startDate = Date('Y-m-d');
		$endDate = Date('Y-m-d');
		$request = request();
		if($request->registered_date){
			$date_place = str_replace('-', ' ', $request->registered_date);
			$date_arr = explode(' ', str_replace('/', '-', $date_place));
			$startDate = Date('Y-m-d', strtotime($date_arr[0]));
			$endDate = Date('Y-m-d', strtotime($date_arr[1]));
		}

		$data = $this->getChannel($startDate, $endDate, $request->type);

		if ($startDate != $endDate){
			$data['week'] = false;
		}
		else{
			$data['week'] = true;
		}

		return view('pages.table_sub-report-channel', $data);
	}

	public function channelAdsDetail(){
		$startDate = Date('Y-m-d');
		$endDate = Date('Y-m-d');
		$request = request();

		if($request->registered_date){
			$date_place = str_replace('-', ' ', $request->registered_date);
			$date_arr = explode(' ', str_replace('/', '-', $date_place));
			$startDate = Date('Y-m-d', strtotime($date_arr[0]));
			$endDate = Date('Y-m-d', strtotime($date_arr[1]));
		}

		$data = $this->getAdsDetail($startDate, $endDate, $request->type);

		return view('pages.lists.table_report_channel_ads_detail', $data);
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

        if ($type == 'budget') {
            $result['budget']   = $this->getBudgetByWeeks($query_chart, $w);
        }
        else if ($type == 'quantity') {
            $result['quantity'] = $this->getQuantityByWeeks($query_chart, $w);
        }
        else if ($type == 'quality') {
            $result['quality']  = $this->getQualityByWeeks($query_chart, $w);
        }
        else if ($type == 'C3AC3B') {
	        $result['C3AC3B']  = $this->getC3AC3BByWeeks($start_date, $end_date, $w);
        } else {
            $result['budget']   = $this->getBudgetByWeeks($query_chart, $w);
            $result['quantity'] = $this->getQuantityByWeeks($query_chart, $w);
            $result['quality']  = $this->getQualityByWeeks($query_chart, $w);
	        $result['C3AC3B']   = $this->getC3AC3BByWeeks($start_date, $end_date, $w);
        }

        return $result;
    }

    private function getBudgetByWeeks($query_chart, $w){

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

            $me         = $item_result['me'] ? $this->convert_spent($item_result['me'])     : 0;
            $re         = $item_result['re'] ? $this->convert_revenue($item_result['re'])   : 0;

            $total_me   += $me;
            $total_re   += $re;

            @$me_array[$week]   += $me;
            @$re_array[$week]   += $re;
            @$c3b_array[$week]  += $item_result['c3b']   ? round($me / $item_result['c3b'], 2)     : 0 ;
            @$c3bg_array[$week] += $item_result['c3bg']  ? round($me / $item_result['c3bg'], 2)    : 0 ;
            @$l1_array[$week]   += $item_result['l1']    ? round($me / $item_result['l1'], 2)      : 0 ;
            @$l3_array[$week]   += $item_result['l3']    ? round($me / $item_result['l3'], 2)      : 0 ;
            @$l6_array[$week]   += $item_result['l6']    ? round($me / $item_result['l6'], 2)      : 0 ;
            @$l8_array[$week]   += $item_result['l8']    ? round($me / $item_result['l8'], 2)      : 0 ;

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
        $c3_array   = array();
        $c3a_array  = array();

        foreach ($query_chart as $item_result) {
            $week = $this->getWeek($item_result['_id']);

            @$c3b_array[$week]  += $item_result['c3b']   ? $item_result['c3b']     : 0 ;
            @$c3bg_array[$week] += $item_result['c3bg']  ? $item_result['c3bg']    : 0 ;
            @$l1_array[$week]   += $item_result['l1']    ? $item_result['l1']      : 0 ;
            @$l3_array[$week]   += $item_result['l3']    ? $item_result['l3']      : 0 ;
            @$l6_array[$week]   += $item_result['l6']    ? $item_result['l6']      : 0 ;
            @$l8_array[$week]   += $item_result['l8']    ? $item_result['l8']      : 0 ;
            @$c3_array[$week]   += $item_result['c3']    ? $item_result['c3']      : 0 ;
            @$c3a_array[$week]  += $item_result['c3a']   ? $item_result['c3a']      : 0 ;

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

    private function getQualityByWeeks($query_chart, $w){

        $total = $this->getTotalDataByWeeks($query_chart, $w);

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
        $source_id = request()->source_id;
        $marketer_id = request()->marketer_id;
        $team_id = request()->team_id;
        $campaign_id = request()->campaign_id;
        $subcampaign_id = request()->subcampaign_id;
        $channel_name = request()->channel_name;
        $channel_id = request()->channel_id;

        $isEmpy = false;
        if($channel_name != "" || $source_id != "" || $marketer_id != "" ||$team_id != "" ||$campaign_id != "" ||$subcampaign_id != "" || $channel_id!= ""){
            $isEmpy =true;
        }


		$ad_id  = $this->getAds();

		$array_reason = [ 'C3A_Duplicated', 'C3B_Under18', 'C3B_Duplicated15Days', 'C3A_Test' ];
		$rs = [];

        if(count($ad_id) >= 0 && $isEmpy){
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
        $source_id = request()->source_id;
        $marketer_id = request()->marketer_id;
        $team_id = request()->team_id;
        $campaign_id = request()->campaign_id;
        $subcampaign_id = request()->subcampaign_id;
        $channel_name = request()->channel_name;
        $channel_id = request()->channel_id;

        $isEmpy = false;
        if($channel_name != "" || $source_id != "" || $marketer_id != "" ||$team_id != "" ||$campaign_id != "" ||$subcampaign_id != "" || $channel_id!= ""){
            $isEmpy =true;
        }

		$ad_id  = $this->getAds();

		$array_reason = [ 'C3A_Duplicated', 'C3B_Under18', 'C3B_Duplicated15Days', 'C3A_Test' ];
		$rs = [];

        if(count($ad_id) >= 0 && $isEmpy){
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

    public function getDataByDays(){
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

        if ($type == 'budget') {
            $result['budget']   = $this->getBudgetByMonths($query_chart);
        }
        else if ($type == 'quantity') {
            $result['quantity'] = $this->getQuantityByMonths($query_chart);
        }
        else if ($type == 'quality') {
            $result['quality']  = $this->getQualityByMonths($query_chart);
        }
        else if ($type == 'C3AC3B') {
	        $result['C3AC3B']  = $this->getC3AC3BByMonths($start_date, $end_date);
        } else {
	        $result['budget']   = $this->getBudgetByMonths($query_chart);
            $result['quantity'] = $this->getQuantityByMonths($query_chart);
            $result['quality']  = $this->getQualityByMonths($query_chart);
            $result['C3AC3B']  = $this->getC3AC3BByMonths($start_date, $end_date);
        }

        return $result;
    }

    private function getBudgetByMonths($query_chart){

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

            $me         = $item_result['me'] ? $this->convert_spent($item_result['me'])     : 0;
            $re         = $item_result['re'] ? $this->convert_revenue($item_result['re'])   : 0;

            $total_me   += $me;
            $total_re   += $re;

            @$me_array[$month]   += $me;
            @$re_array[$month]   += $re;
            @$c3b_array[$month]  += $item_result['c3b']   ? round($me / $item_result['c3b'], 2)     : 0 ;
            @$c3bg_array[$month] += $item_result['c3bg']  ? round($me / $item_result['c3bg'], 2)    : 0 ;
            @$l1_array[$month]   += $item_result['l1']    ? round($me / $item_result['l1'], 2)      : 0 ;
            @$l3_array[$month]   += $item_result['l3']    ? round($me / $item_result['l3'], 2)      : 0 ;
            @$l6_array[$month]   += $item_result['l6']    ? round($me / $item_result['l6'], 2)      : 0 ;
            @$l8_array[$month]   += $item_result['l8']    ? round($me / $item_result['l8'], 2)      : 0 ;

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
        $c3a_array  = array();
        $c3_array   = array();

        foreach ($query_chart as $item_result) {
            $month = $this->getMonths($item_result['_id']);

            @$c3b_array[$month]  += $item_result['c3b']   ? $item_result['c3b']     : 0 ;
            @$c3bg_array[$month] += $item_result['c3bg']  ? $item_result['c3bg']    : 0 ;
            @$l1_array[$month]   += $item_result['l1']    ? $item_result['l1']      : 0 ;
            @$l3_array[$month]   += $item_result['l3']    ? $item_result['l3']      : 0 ;
            @$l6_array[$month]   += $item_result['l6']    ? $item_result['l6']      : 0 ;
            @$l8_array[$month]   += $item_result['l8']    ? $item_result['l8']      : 0 ;
            @$c3a_array[$month]  += $item_result['c3a']   ? $item_result['c3a']     : 0 ;
            @$c3_array[$month]   += $item_result['c3']    ? $item_result['c3']      : 0 ;
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

    private function getQualityByMonths($query_chart){

        $total = $this->getTotalDataByMonths($query_chart);

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
