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
        // 2018-04-04 lamvt update title
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager <span>> Kpis</span>";
        // end 2018-04-04

        return view('pages.kpis', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs'
        ));
    }


}
