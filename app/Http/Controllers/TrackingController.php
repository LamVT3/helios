<?php

namespace App\Http\Controllers;

use App\AdResult;
use App\Contact;
use App\Permission;
use App\Role;
use App\User;
use App\Config;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

class TrackingController extends Controller {
	public function __construct() {
		$this->middleware( 'auth' );
	}

	public function index() {
		// if (!\Entrust::can('view-user')) return view('errors.403');
		$page_title     = "Monitor Cron Jobs | Helios";
		$page_css       = [];
		$no_main_header = false;
		$active         = 'users';
		$breadcrumbs    = "<i class=\"fa-fw fa fa-monitor\"></i> <span> Monitor Cron Jobs </span>";

		$page_size = (int) Config::getByKey( 'PAGE_SIZE' );
		$jobs      = DB::table( 'tracking_inventory' )->orderBy('_id','DESC')->paginate( $page_size );

		return view( 'pages.tracking-monitor', compact(
			'no_main_header',
			'page_title',
			'page_css',
			'active',
			'breadcrumbs',
			'jobs',
			'page_size'
		) );
	}

	public function showSuccess( $id, Request $request ) {
		// if (!\Entrust::can('view-user')) return view('errors.403');
		$page_title     = "Monitor Cron Jobs | Helios";
		$page_css       = [];
		$no_main_header = false;
		$active         = 'users';
		$breadcrumbs    = "<i class=\"fa-fw fa fa-monitor\"></i> <span> Monitor Cron Jobs </span>";

		$page_size           = (int) Config::getByKey( 'PAGE_SIZE' );
		$job                 = DB::table( 'tracking_inventory' )->where( '_id', $id )->first();
		$success_phone       = $job['success_phone'];
		$page                = Input::get( 'page' ) === null ? 1 : (int) Input::get( 'page' );

		$phone_paged = new LengthAwarePaginator( array_slice( $success_phone, $page_size * ( $page - 1 ), $page_size, true ),
		                                                 count( $success_phone ),
		                                                 $page_size,
		                                                 $page,
		                                                 [
			                                                 'path'  => $request->url(),
			                                                 'query' => $request->query(),
		                                                 ] );

		return view( 'pages.tracking-monitor-detail', compact(
			'no_main_header',
			'page_title',
			'page_css',
			'active',
			'breadcrumbs',
			'phone_paged',
			'page_size'
		) );
	}

	public function showDuplicate( $id, Request $request ) {
		// if (!\Entrust::can('view-user')) return view('errors.403');
		$page_title     = "Monitor Cron Jobs | Helios";
		$page_css       = [];
		$no_main_header = false;
		$active         = 'users';
		$breadcrumbs    = "<i class=\"fa-fw fa fa-monitor\"></i> <span> Monitor Cron Jobs </span>";

		$page_size           = (int) Config::getByKey( 'PAGE_SIZE' );
		$job                 = DB::table( 'tracking_inventory' )->where( '_id', $id )->first();
		$duplicate_phone       = $job['duplicate_phone'];
		$page                = Input::get( 'page' ) === null ? 1 : (int) Input::get( 'page' );

		$phone_paged = new LengthAwarePaginator( array_slice( $duplicate_phone, $page_size * ( $page - 1 ), $page_size, true ),
		                                                 count( $duplicate_phone ),
		                                                   $page_size,
		                                                 $page,
		                                                 [
			                                                 'path'  => $request->url(),
			                                                 'query' => $request->query(),
		                                                 ] );

		return view( 'pages.tracking-monitor-detail', compact(
			'no_main_header',
			'page_title',
			'page_css',
			'active',
			'breadcrumbs',
			'phone_paged',
			'page_size'
		) );
	}

	public function doubleCheck(Request $request){
		$page_title = "Double Check | Helios";
		$page_css = array();
		$no_main_header = FALSE; //set true for lock.php and login.php
		$active = 'double-check';
		$breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Tracking <span>> Double Check</span>";

		$table['ad_results'] = [];
		$table['contacts'] = [];


		if (isset($request->submit_date)) {
			$submit_date = $request->submit_date;
			$date_place = str_replace('-', ' ', $submit_date);
			$date_arr = explode(' ', str_replace('/', '-', $date_place));
			$date_start = strtotime($date_arr[0]);
			$date_end = strtotime("+1 day", strtotime($date_arr[1]));
		}
		else {
			$date_time   = date('d/m/Y');
			$date_start  = strtotime(str_replace('/', '-', $date_time));
			$date_end    = strtotime(str_replace('/', '-', $date_time)) + 86400;
			$submit_date = $date_time . '-' . $date_time;
		}

		$contacts = Contact::where( 'submit_time', '>=',  $date_start * 1000 )
	                         ->where( 'submit_time', '<',  $date_end  * 1000 )
	                         ->get();

		$table['contacts']['c3'] = 0;
		$table['contacts']['l1'] = 0;
		$table['contacts']['l2'] = 0;
		$table['contacts']['l3'] = 0;
		$table['contacts']['l4'] = 0;
		$table['contacts']['l5'] = 0;
		$table['contacts']['l6'] = 0;
		$table['contacts']['l7'] = 0;
		$table['contacts']['l8'] = 0;
		$table['contacts']['spent'] = 0;
		$table['contacts']['revenue'] = 0;

		foreach ($contacts as $contact){
			if(in_array($contact['clevel'], ['c3a','c3b','c3bg'])){
				$table['contacts']['c3'] += 1;
			}
			$table['contacts']['l1'] += isset($contact['l1_time']) && !empty($contact['l1_time']) ? 1 : 0 ;
			$table['contacts']['l2'] += isset($contact['l2_time']) && !empty($contact['l2_time']) ? 1 : 0 ;
			$table['contacts']['l3'] += isset($contact['l3_time']) && !empty($contact['l3_time']) ? 1 : 0 ;
			$table['contacts']['l4'] += isset($contact['l4_time']) && !empty($contact['l4_time']) ? 1 : 0 ;
			$table['contacts']['l5'] += isset($contact['l5_time']) && !empty($contact['l5_time']) ? 1 : 0 ;
			$table['contacts']['l6'] += isset($contact['l6_time']) && !empty($contact['l6_time']) ? 1 : 0 ;
			$table['contacts']['l7'] += isset($contact['l7_time']) && !empty($contact['l7_time']) ? 1 : 0 ;
			$table['contacts']['l8'] += isset($contact['l8_time']) && !empty($contact['l8_time']) ? 1 : 0 ;
			$table['contacts']['spent'] += isset($contact['spent']) ? $contact['spent'] : 0 ;
			$table['contacts']['revenue'] += isset($contact['revenue']) ? $contact['revenue'] : 0 ;
		}

		$ad_results = AdResult::where( 'date', '>=',  date('Y-m-d', $date_start) )
		                      ->where( 'date', '<', date('Y-m-d', $date_end))
		                      ->get();

		$table['ad_results']['c3'] = 0;
		$table['ad_results']['l1'] = 0;
		$table['ad_results']['l2'] = 0;
		$table['ad_results']['l3'] = 0;
		$table['ad_results']['l4'] = 0;
		$table['ad_results']['l5'] = 0;
		$table['ad_results']['l6'] = 0;
		$table['ad_results']['l7'] = 0;
		$table['ad_results']['l8'] = 0;
		$table['ad_results']['spent'] = 0;
		$table['ad_results']['revenue'] = 0;

		 foreach ($ad_results as $ad_result){
			 $table['ad_results']['c3'] += isset($ad_result['c3']) ? $ad_result['c3'] : 0 ;
			 $table['ad_results']['l1'] += isset($ad_result['l1']) ? $ad_result['l1'] : 0 ;
			 $table['ad_results']['l2'] += isset($ad_result['l2']) ? $ad_result['l2'] : 0 ;
			 $table['ad_results']['l3'] += isset($ad_result['l3']) ? $ad_result['l3'] : 0 ;
			 $table['ad_results']['l4'] += isset($ad_result['l4']) ? $ad_result['l4'] : 0 ;
			 $table['ad_results']['l5'] += isset($ad_result['l5']) ? $ad_result['l5'] : 0 ;
			 $table['ad_results']['l6'] += isset($ad_result['l6']) ? $ad_result['l6'] : 0 ;
			 $table['ad_results']['l7'] += isset($ad_result['l7']) ? $ad_result['l7'] : 0 ;
			 $table['ad_results']['l8'] += isset($ad_result['l8']) ? $ad_result['l8'] : 0 ;
			 $table['ad_results']['spent'] += isset($ad_result['spent']) ? $ad_result['spent'] : 0 ;
			 $table['ad_results']['revenue'] += isset($ad_result['revenue']) ? $ad_result['revenue'] : 0 ;
		 }

		return view('pages.double-check', compact(
			'page_title',
			'page_css',
			'no_main_header',
			'active',
			'breadcrumbs',
			'page_size',
			'subcampaigns',
			'table',
			'submit_date'
		));
	}

}
