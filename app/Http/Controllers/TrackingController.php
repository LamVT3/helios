<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use App\Config;
use DB;
use Auth;

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

		$page_size = Config::getByKey( 'PAGE_SIZE' );
		$jobs      = DB::table( 'tracking_inventory' )->get();

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

	public function show( $id ) {
		// if (!\Entrust::can('view-user')) return view('errors.403');
		$page_title     = "Monitor Cron Jobs | Helios";
		$page_css       = [];
		$no_main_header = false;
		$active         = 'users';
		$breadcrumbs    = "<i class=\"fa-fw fa fa-monitor\"></i> <span> Monitor Cron Jobs </span>";

		$page_size     = Config::getByKey( 'PAGE_SIZE' );
		$job           = DB::table( 'tracking_inventory' )->where( '_id', $id )->first();
		$success_phone = $job['success_phone'];

		return view( 'pages.tracking-monitor-detail', compact(
			'no_main_header',
			'page_title',
			'page_css',
			'active',
			'breadcrumbs',
			'success_phone',
			'page_size'
		) );
	}
}
