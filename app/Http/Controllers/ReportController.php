<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Campaign;
use App\Channel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Report | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'report';
        $breadcrumbs = "<i class=\"fa-fw fa fa-child\"></i> Report <span>>Quality Report </span>";

        $ads = Ad::all();

        return view('pages.report-quality', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'ads'
        ));
    }

}
