<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Campaign;
use App\Channel;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Kpis | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'kpis';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager <span>> Kpis</span>";

        return view('pages.kpis', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs'
        ));
    }

    public function assign_kpi(){
        $page_title = "Assign KPI | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'assign_kpi';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> Assign KPI </span>";

        return view('pages.assign_kpi', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs'
        ));

    }

    public function save_kpi(){

        $request    = request();
        $username   = $request->username;
        $month      = $request->month;
        $year       = $request->year;
        $user       = $username ? User::where('username', $username)->firstOrFail() : auth()->user();

        $kpi        = $user->kpi;
        $kpi[$year][$month] = $request->kpi;
        ksort( $kpi[$year]);
        $user->kpi  = $kpi;
        $user->save();
    }

    public function get_kpi(){

        $request    = request();
        $username   = $request->username;
        $month      = $request->month;
        $year       = $request->year;
        $user       = $username ? User::where('username', $username)->firstOrFail() : auth()->user();

        $kpi        = $user->kpi;
        return @$kpi[$year][$month];

    }


}
