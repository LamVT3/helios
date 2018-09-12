<?php

namespace App\Http\Controllers;


use App\Campaign;
use App\Config;
use App\Source;
use App\Subcampaign;
use App\Team;
use App\User;

class MyDashBoardController extends Controller
{
	public function index()
	{
		$page_title = "My Dashboard | Helios";
		$page_css = array();
		$no_main_header = FALSE; //set true for lock.php and login.php
		$active = '';
		$breadcrumbs = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> My Dashboard";

		$sources        = Source::all();
		$teams          = Team::all();
		$campaigns      = Campaign::where('is_active', 1)->get();
		$page_size      = Config::getByKey('PAGE_SIZE');
		$subcampaigns   = Subcampaign::where('is_active', 1)->get();

		return view('pages.my-dashboard', compact(
			'page_title',
			'page_css',
			'no_main_header',
			'active',
			'breadcrumbs',
			'sources',
			'teams',
			'campaigns',
			'page_size',
			'subcampaigns'
		));
	}
}
