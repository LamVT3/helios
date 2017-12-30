<?php

namespace App\Http\Controllers;

use App\ActivityBooking;
use App\Admin_account;
use App\AdResult;
use App\CarBooking;
use App\Customer;
use App\CustomerActivity;
use App\Dm_contact;
use App\HotelBooking;
use App\TourBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Dashboard | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'dashboard';
        $breadcrumbs = "<i class=\"fa-fw fa fa-home\"></i> Dashboard";

        //$user = Admin_account::all();
        //$contacts = Dm_contact::where('link_cv', null)->orderBy('id', 'desc')->limit(5)->get();
        //debug($contacts);
        $ad_results = AdResult::where("date", Carbon::yesterday()->toDateString());

        return view('pages.dashboard', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'ad_results'
        ));
    }
}
