<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use App\Config;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
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
		$jobs      = DB::table( 'tracking_inventory' )->paginate( $page_size );

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
}
