<?php

namespace App\Http\Controllers;

use App\Ad;
use App\AdResult;
use App\Campaign;
use App\Channel;
use App\Source;
use App\Team;
use App\User;
use Carbon\Carbon;
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

        $ads = Ad::where("is_active", 1)->get();
        //$ad_results = AdResult::where("date", Carbon::yesterday()->toDateString())->get();
        $ad_results = AdResult::where("date", "2017-12-29")->get();
        $results = $total = [];
        foreach ($ad_results as $item) {
            $results[$item->ad_id] = $item;

            if (isset($total["c1"])) {
                $total["c1"] += $item->c1;
                $total["c2"] += $item->c2;
                $total["c3"] += $item->c3;
                $total["c3b"] += $item->c3;
                $total["spent"] += $item->spent;
                $total["l1"] += $item->l1;
                $total["l3"] += $item->l3;
                $total["l8"] += $item->l8;
                $total["revenue"] += $item->revenue;
            } else {
                $total["c1"] = $item->c1;
                $total["c2"] = $item->c2;
                $total["c3"] = $item->c3;
                $total["c3b"] = $item->c3;
                $total["spent"] = $item->spent;
                $total["l1"] = $item->l1;
                $total["l3"] = $item->l3;
                $total["l8"] = $item->l8;
                $total["revenue"] = $item->revenue;
            }

        }
        $total["c1_cost"] = $total["c1"] ? round($total["spent"] * 22000 / $total["c1"], 2) : 'n/a';
        $total["c2_cost"] = $total["c2"] ? round($total["spent"] * 22000 / $total["c2"], 2) : 'n/a';
        $total["c3_cost"] = $total["c3"] ? round($total["spent"] * 22000 / $total["c3"], 2) : 'n/a';
        $total["c3b_cost"] = $total["c3b"] ? round($total["spent"] * 22000 / $total["c3b"], 2) : 'n/a';
        $total["c3_c2"] = $total["c2"] ? round($total["c3"] / $total["c2"], 4) * 100 : 'n/a';
        $total["l3_l1"] = $total["l1"] ? round($total["l3"] / $total["l1"], 4) * 100 : 'n/a';
        $total["l8_l1"] = $total["l1"] ? round($total["l8"] / $total["l1"], 4) * 100 : 'n/a';
        $total["me_re"] = $total["revenue"] ? round($total["spent"] / $total["revenue"], 4) * 100 : 'n/a';

        $sources = Source::all();
        $teams = Team::all();
        $marketers = User::all();
        $campaigns = Campaign::where('is_active', 1)->get();

        $source = \request('source', 'All');
        $team = \request('team', 'All');
        $marketer = \request('marketer', 'All');
        $campaign = \request('campaign', 'All');

        return view('pages.report-quality', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'ads',
            'results',
            'sources',
            'teams',
            'marketers',
            'campaigns',
            'source',
            'team',
            'marketer',
            'campaign',
            'total'
        ));
    }

    public function getReport()
    {
        $ads = Ad::where("is_active", 1)->get();
        $sources = Source::all();
        $teams = Team::all();
        $marketers = User::all();
        $campaigns = Campaign::where('is_active', 1)->get();
        $source = \request('source', 'All');
        $team = \request('team', 'All');
        $marketer = \request('marketer', 'All');
        $campaign = \request('campaign', 'All');
        $data = $this->getReportData();
        return view('pages.table_report-quality', $data, compact('ads', 'sources',
            'teams',
            'marketers',
            'campaigns',
            'source',
            'team',
            'marketer',
            'campaign'));
    }

    public function getReportData()
    {
        $data_where = array();
        $request = request();
        if ($request->source_id) {
            $data_where['source_id'] = $request->source_id;
        }
        if ($request->team_id) {
            $data_where['team_id'] = $request->team_id;
        }
        if ($request->marketer_id) {
            $data_where['marketer_id'] = $request->marketer_id;
        }
        if ($request->campaign_id) {
            $data_where['campaign_id'] = $request->campaign_id;
        }
        if ($request->current_level) {
            $data_where['current_level'] = intval($request->current_level);
        }
//        dd($data_where);
        if ($request->registered_date) {
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = Date('Y-m-d', strtotime($date_arr[0]));
            $endDate = Date('Y-m-d', strtotime($date_arr[1]));
            $reports = AdResult::where('date', '>=', $startDate)
                ->where('date', '<=', $endDate);
        }
        if (count($data_where) >= 1) {
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = Date('Y-m-d', strtotime($date_arr[0]));
            $endDate = Date('Y-m-d', strtotime($date_arr[1]));
            $reports = AdResult::where($data_where)
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate);
        }
        if (!$request->registered_date) {
            $reports = AdResult::where('date', '>=', Date('Y-m-d'))
                ->where('date', '<=', Date('Y-m-d'));
        }
        $reports = $reports->get();

        $results = $total = [];
        foreach ($reports as $item) {
            $results[$item->ad_id] = $item;

            if (isset($total["c1"])) {
                $total["c1"] += $item->c1;
                $total["c2"] += $item->c2;
                $total["c3"] += $item->c3;
                $total["c3b"] += $item->c3;
                $total["spent"] += $item->spent;
                $total["l1"] += $item->l1;
                $total["l3"] += $item->l3;
                $total["l8"] += $item->l8;
                $total["revenue"] += $item->revenue;
            } else {
                $total["c1"] = $item->c1;
                $total["c2"] = $item->c2;
                $total["c3"] = $item->c3;
                $total["c3b"] = $item->c3;
                $total["spent"] = $item->spent;
                $total["l1"] = $item->l1;
                $total["l3"] = $item->l3;
                $total["l8"] = $item->l8;
                $total["revenue"] = $item->revenue;
            }

        }
        $total["c1_cost"] = $total["c1"] ? round($total["spent"] * 22000 / $total["c1"], 2) : 'n/a';
        $total["c2_cost"] = $total["c2"] ? round($total["spent"] * 22000 / $total["c2"], 2) : 'n/a';
        $total["c3_cost"] = $total["c3"] ? round($total["spent"] * 22000 / $total["c3"], 2) : 'n/a';
        $total["c3b_cost"] = $total["c3b"] ? round($total["spent"] * 22000 / $total["c3b"], 2) : 'n/a';
        $total["c3_c2"] = $total["c2"] ? round($total["c3"] / $total["c2"], 4) * 100 : 'n/a';
        $total["l3_l1"] = $total["l1"] ? round($total["l3"] / $total["l1"], 4) * 100 : 'n/a';
        $total["l8_l1"] = $total["l1"] ? round($total["l8"] / $total["l1"], 4) * 100 : 'n/a';
        $total["me_re"] = $total["revenue"] ? round($total["spent"] / $total["revenue"], 4) * 100 : 'n/a';
        $data = $data_where;
        $data['total'] = $total;
        return $data;
    }

    public function exportReport()
    {

    }

}
