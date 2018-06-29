<?php

namespace App\Http\Controllers;

use App\Ad;
use App\AdResult;
use App\Campaign;
use App\Contact;
use App\LandingPage;
use App\Source;
use App\Subcampaign;
use App\Team;
use App\User;
use App\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSubcampaigns($id)
    {
        $campaign = Campaign::find($id);
        $subcampaigns = Subcampaign::where('campaign_id', $id)->get();
        if ($campaign) {
            return response()->json(['type' => 'success', 'campaign' => $campaign, 'subcampaigns' => $subcampaigns]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Campaign not found"]);
        }
    }

    /*public function getTeamsCampaigns($source_id)
    {
        $user = auth()->user();
        $sources = $user->sources;
        $teams = $sources[$source_id]['teams'];

        $campaigns = Campaign::where(['team_id' => current($teams)['team_id'], 'creator_id' => $user->id])->get();
        return response()->json(['type' => 'success', 'teams' => $teams, 'campaigns' => $campaigns]);
    }*/

    public function getCampaigns($source_id)
    {
        $user = auth()->user();
        if($user->role == config('constants.ROLE_ADMIN')){
            $campaigns = Campaign::where(['source_id' => $source_id])->get();
        }
        else {
            $campaigns = Campaign::where(['source_id' => $source_id, 'creator_id' => $user->id])->get();
        }

        return response()->json(['type' => 'success', 'campaigns' => $campaigns]);
    }

    public function contactDetails($id)
    {
        $contact = Contact::findOrFail($id);

        $duplicatedContacts = Contact::where('_id', '<>', $id)->where('phone', $contact->phone)->get();

        return view('components.contact-details', compact(
            'contact',
            'duplicatedContacts'
        ));
    }

    public function getFilterSource()
    {
        $data_where = array();
        $request = request();
        $html_team          = "<option value='' selected>All</option>";
        $html_marketer      = "<option value='' selected>All</option>";
        $html_campaign      = "<option value='' selected>All</option>";
        $html_subcampaign   = "<option value='' selected>All</option>";

        if ($request->source_id) {
            $data_where['source_id'] = $request->source_id;
            $teams = Team::all();
            foreach ($teams as $team) {
                $source = array_keys($team->sources);
                if(in_array($request->source_id, $source)){
                    $html_team .= "<option value=" . $team->id . "> " . $team->name . " </option>";
                    $marketers  = $team->members;
                    foreach ($marketers as $item) {
                        $html_marketer .= "<option value='" . $item['user_id'] . "'> " . $item['username'] . " </option>";
                    }

                    $campaigns = Campaign::where('team_id', $team->id)->get();
                    foreach ($campaigns as $item) {
                        $html_campaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
                    }

                    $subcampaign    = Subcampaign::where('team_id', $team->id)->get();
                    foreach ($subcampaign as $item) {
                        $html_subcampaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
                    }
                }
            }
        }
        else {
            $teams = Team::all();
            foreach ($teams as $team) {
                $html_team .= "<option value=" . $team->id . "> " . $team->name . " </option>";
                $marketers  = $team->members;
                foreach ($marketers as $item) {
                    $html_marketer .= "<option value='" . $item['user_id'] . "'> " . $item['username'] . " </option>";
                }
            }
            $campaigns = Campaign::all();
            foreach ($campaigns as $item) {
                $html_campaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
            }

            $subcampaign    = Subcampaign::all();
            foreach ($subcampaign as $item) {
                $html_subcampaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
            }
        }

        $data_return = array(
            'status'                => TRUE,
            'content_team'          => $html_team,
            'content_campaign'      => $html_campaign,
            'content_marketer'      => $html_marketer,
            'content_subcampaign'   => $html_subcampaign
        );
        echo json_encode($data_return);

    }

    public function getFilterTeam()
    {
        $data_where = array();
        $request = request();
        DB::connection( 'mongodb' )->enableQueryLog();

        $html_campaign      = "<option value=''>All</option>";
        $html_marketer      = "<option value=''>All</option>";
        $html_subcampaign   = "<option value='' selected>All</option>";

        if ($request->team_id) {
            $data_where['team_id'] = $request->team_id;
            $team           = Team::find($request->team_id);
            $campaigns      = Campaign::where($data_where)->get();
            $marketers      = $team->members;
            $subcampaign    = Subcampaign::where($data_where)->get();

        }else{
            $campaigns      = Campaign::all();
            $marketers      = User::all();
            $subcampaign    = Subcampaign::all();
        }

        foreach ($marketers as $item) {
            $html_marketer .= "<option value='" . $item['user_id'] . "'> " . $item['username'] . " </option>";
        }
        foreach ($campaigns as $item) {
            $html_campaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }
        foreach ($subcampaign as $item) {
            $html_subcampaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }
        DB::connection('mongodb')->getQueryLog();
        $data_return = array(
            'status'                => TRUE,
            'content_campaign'      => $html_campaign,
            'content_marketer'      => $html_marketer,
            'content_subcampaign'   => $html_subcampaign
        );
        echo json_encode($data_return);

    }

    public function getFilterMaketer()
    {
        $request = request();
        $html_campaign      = "<option value='' selected>All</option>";
        $html_subcampaign   = "<option value='' selected>All</option>";

        if ($request->creator_id) {
            $data_where['creator_id'] = $request->creator_id;
            $campaigns      = Campaign::where($data_where)->get();
            $subcampaign    = Subcampaign::where($data_where)->get();
        }else{
            $campaigns      = Campaign::all();
            $subcampaign    = Subcampaign::all();
        }

        foreach ($campaigns as $item) {
            $html_campaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }
        foreach ($subcampaign as $item) {
            $html_subcampaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }

        $data_return = array(
            'status'                => TRUE,
            'content_campaign'      => $html_campaign,
            'content_subcampaign'   => $html_subcampaign,
        );
        echo json_encode($data_return);

    }

    public function getFilterCampaign()
    {
        $request = request();

        $html_subcampaign   = "<option value='' selected>All</option>";
        $html_landingpage   = "<option value='' selected>All</option>";

        if ($request->campaign_id) {
            $subcampaign    = Subcampaign::where('campaign_id', $request->campaign_id)->get();
            $ad             = Ad::where('campaign_id', $request->campaign_id)->get();
        }else{
            $subcampaign    = Subcampaign::all();
            $ad = Ad::all();
        }

        foreach ($subcampaign as $item) {
            $html_subcampaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }

        $arr_landingpage = array();
        foreach ($ad as $item) {
            $arr_landingpage[] = $item->landing_page_id;
        }
        $landing_page = LandingPage::whereIn('_id', $arr_landingpage)->get();
        foreach ($landing_page as $item) {
            $html_landingpage .= "<option value=" . $item->id . "> " . $item->url . " </option>";
        }

        $data_return = array(
            'status'                => TRUE,
            'content_subcampaign'   => $html_subcampaign,
            'content_landingpage'   => $html_landingpage
        );
        echo json_encode($data_return);

    }

    public function getFilterSubCampaign()
    {
        $request = request();

        $html_landingpage   = "<option value='' selected>All</option>";

        if ($request->subcampaign_id) {
            $ad = Ad::where('subcampaign_id', $request->subcampaign_id)->get();
        }else{
            $ad = Ad::all();
        }

        $arr_landingpage = array();
        foreach ($ad as $item) {
            $arr_landingpage[] = $item->landing_page_id;
        }
        $landing_page = LandingPage::whereIn('_id', $arr_landingpage)->get();
        foreach ($landing_page as $item) {
            $html_landingpage .= "<option value=" . $item->id . "> " . $item->url . " </option>";
        }

        $data_return = array(
            'status'                => TRUE,
            'content_landingpage'   => $html_landingpage
        );
        echo json_encode($data_return);

    }

    public function dashboard()
    {
        // 2018-04-18 LamVT [HEL_9] Add more setting for VND/USD conversion
        $config     = Config::getByKeys(['USD_VND', 'USD_THB']);
        $rate       = $config['USD_VND'];
        // end 2018-04-18 LamVT [HEL_9] Add more setting for VND/USD conversion

        $request = request();
        /* phan dashboard*/
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : Date('Y-m-d');
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : Date('Y-m-d');

        $query_dashboard = AdResult::where('date', '>=', $startDate)
            ->where('date', '<=', $endDate);

        $dashboard['c3'] = $query_dashboard->sum('c3');
        $dashboard['spent'] = $query_dashboard->sum('spent');
        $dashboard['revenue'] = $query_dashboard->sum('revenue');
        $dashboard['c3_cost'] = $dashboard['c3'] ? round($dashboard['spent'] * $rate / $dashboard['c3'], 2) : '0';

        $dashboard['c3_cost'] = number_format($dashboard['c3_cost']);
        $dashboard['c3'] = number_format((int)$query_dashboard->sum('c3'));
        $dashboard['revenue'] = number_format($query_dashboard->sum('revenue'));
        /* end Dashboard */

        return response()->json(['type' => 'success', 'dashboard' => $dashboard]);
    }

    public function c3_leaderboard()
    {
        $request = request();
        $period = $request->period ? $request->period : 'today';

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');

        if ($period === 'thisweek') {
            $startDate = date('Y-m-d', strtotime('Last Monday', time()));
            // 2018-04-13 LamVT [HEL-13] update "Leaderboard" in Dashboard
            $endDate = date('Y-m-d', strtotime('Next Sunday', time()));
            // end 2018-04-13 LamVT [HEL-13] update "Leaderboard" in Dashboard
        }

        if ($period === 'thismonth') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }

        $query = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                [
                    '$group' => [
                        '_id' => '$creator_id',
                        'c3' => [
                            '$sum' => '$c3'
                        ]
                    ]
                ],
                ['$sort' => ['c3' => -1]]
            ]);
        });

        $table = '<table  class="table table-striped table-bordered table-hover"
                                           width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>C3</th>
                                </tr>
                            </thead>
                            <tbody>';

        foreach ($query as $i => $item) {
//            if($i > 4) break;
//            if(!$item->c3) continue;  // not show if c3 = 0

            $user = User::find($item->_id);
            // 2018-04-18 LamVT update leaderboard
            if(!$user){ // if not found user
                $unknown            = config('constants.UNKNOWN');
                $user['username']   = $unknown;
                $user['rank']       = $unknown;
            }
            // end 2018-04-18 LamVT update leaderboard
            $no = $i+1;
            $table .= "<tr>
                                <th>{$no}</th>
                                <th>{$user['username']}</th>
                                <td>{$user['rank']}</td>
                                <td>{$item->c3}</td>
                            </tr>";
        }

        $table .= '</tbody> </table>';

        return $table;
    }

    public function revenue_leaderboard()
    {
        $request = request();
        $period = $request->period ? $request->period : 'today';

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');

        if ($period === 'thisweek') {
            $startDate = date('Y-m-d', strtotime('Last Monday', time()));
            $endDate = date('Y-m-d', strtotime('Next Sunday', time()));
        }

        if ($period === 'thismonth') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }

        $query = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                [
                    '$group' => [
                        '_id' => '$creator_id',
                        'revenue' => [
                            '$sum' => '$revenue'
                        ]
                    ]
                ],
                ['$sort' => ['revenue' => -1]]
            ]);
        });

        $table = '<table  class="table table-striped table-bordered table-hover"
                                           width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>Revenue (baht)</th>
                                </tr>
                            </thead>
                            <tbody>';

        foreach ($query as $i => $item) {
//            if($i > 4) break;
//            if(!$item->revenue) continue;

            $user = User::find($item->_id);
            // 2018-04-18 LamVT update leaderboard
            if(!$user){ // if not found user
                $unknown            = config('constants.UNKNOWN');
                $user['username']   = $unknown;
                $user['rank']       = $unknown;
            }
            // end 2018-04-18 LamVT update leaderboard
            $no = $i+1;
            $table .= "<tr>
                                <th>{$no}</th>
                                <th>{$user['username']}</th>
                                <td>{$user['rank']}</td>
                                <td>{$item->revenue}</td>
                            </tr>";
        }

        $table .= '</tbody> </table>';

        return $table;
    }

    public function spent_leaderboard()
    {
        $request = request();
        $period = $request->period ? $request->period : 'today';

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');

        if ($period === 'thisweek') {
            $startDate = date('Y-m-d', strtotime('Last Monday', time()));
            $endDate = date('Y-m-d', strtotime('Next Sunday', time()));
        }

        if ($period === 'thismonth') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }

        $query = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                [
                    '$group' => [
                        '_id' => '$creator_id',
                        'spent' => [
                            '$sum' => '$spent'
                        ]
                    ]
                ],
                ['$sort' => ['spent' => -1]]
            ]);
        });

        $table = '<table  class="table table-striped table-bordered table-hover"
                                           width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>Spent (USD)</th>
                                </tr>
                            </thead>
                            <tbody>';

        foreach ($query as $i => $item) {
//            if($i > 4) break;
//            if(!$item->spent) continue;

            $user = User::find($item->_id);
            // 2018-04-18 LamVT update leaderboard
            if(!$user){ // if not found user
                $unknown            = config('constants.UNKNOWN');
                $user['username']   = $unknown;
                $user['rank']       = $unknown;
            }
            // end 2018-04-18 LamVT update leaderboard
            $no = $i+1;
            $table .= "<tr>
                                <th>{$no}</th>
                                <th>{$user['username']}</th>
                                <td>{$user['rank']}</td>
                                <td>{$item->spent}</td>
                            </tr>";
        }

        $table .= '</tbody> </table>';

        return $table;
    }

    // 2018-04-17 [HEL-9] LamVT add dropdown for C3/L8 chart
    public function getC3Chart(){

        /*  phan date*/
        $month  = request('month');
        $year   = date('Y'); /* nam hien tai*/
        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month    = date('Y-' . $month .'-t'); /* ngày cuối cùng của tháng */
        /* end date */

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($first_day_this_month, $last_day_this_month) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => '$c3'
                        ],
                    ]
                ]
            ]);
        });

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        $c3_array = array();

        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);
            $c3_array[(int)($day[2])] = $item_result['c3'];
        }

        /*  lay du lieu c3*/
        $chart_c3 = array();
        foreach ($array_month as $key => $timestamp) {
            if (isset($c3_array[$key])) {
                $chart_c3[] = [$timestamp, $c3_array[$key]];
            } else {
                $chart_c3[] = [$timestamp, 0];
            }
        }
        $chart_c3 = json_encode($chart_c3);

    return $chart_c3;
    }

    public function getL8Chart(){

        /*  phan date*/
        $month  = request('month');
        $year   = date('Y'); /* nam hien tai*/
        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month    = date('Y-' . $month .'-t'); /* ngày cuối cùng của tháng */

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($first_day_this_month, $last_day_this_month) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'l8' => [
                            '$sum' => '$l8'
                        ]
                    ]
                ]
            ]);
        });

        $array_month = array();
        for ($i = 1; $i <= $d; $i++) {
            //$array_month[date($i)] = 0;
            $timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
            $array_month[$i] = $timestamp;
        }

        $l8_array = array();

        foreach ($query_chart as $item_result) {
            $day = explode('-', $item_result['_id']);
            $l8_array[(int)($day[2])] = $item_result['l8'];
        }

        /* lay du lieu l8*/
        $chart_l8 = array();
        foreach ($array_month as $key => $timestamp) {
            if (isset($l8_array[$key])) {
                $chart_l8[] = [$timestamp, $l8_array[$key]];
            } else {
                $chart_l8[] = [$timestamp, 0];
            }
        }
        $chart_l8 = json_encode($chart_l8);
        /* end l8 */

        return $chart_l8;
    }
    // end 2018-04-17 [HEL-9] LamVT add dropdown for C3/L8 chart

    public function getReportMonthly() {

        $month       = request('month');
        $startRange  = request('startDate');
        $endRange    = request('endDate');
        $startDate   = date('Y-' . $month .'-01');
        $year        = date('Y'); /* nam hien tai*/
        $days        = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $endDate    = date('Y-' . $month .'-'.$days);

        $startDayRange = explode(" ", $startRange)[2];
        $endDayRange = explode(" ", $endRange)[2];

        /*Mon 1 = 8 - 1 = 7
        Sun 7 = 8 - 7 = 1*/
        $daysInFirstWeek = 8 - date('N',strtotime($startDate));
        $rangeTotal = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $results = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $endDate = date('Y-' . $month .'-0'.$daysInFirstWeek);
        $rangeW1 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultW1 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $startDate   = date('Y-m-d', strtotime($endDate. ' + 1 days'));
        $endDate    = date('Y-m-d', strtotime($startDate. ' + 6 days'));
        $rangeW2 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultW2 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $startDate = date('Y-m-d', strtotime($endDate. ' + 1 days'));
        $endDate  = date('Y-m-d', strtotime($startDate. ' + 6 days'));
        $rangeW3   = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultW3  = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $startDate   = date('Y-m-d', strtotime($endDate. ' + 1 days'));
        $endDate    = date('Y-m-d', strtotime($startDate. ' + 6 days'));
        $rangeW4 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultW4 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $resultW5 = null;
        $rangeW5 = null;
        $remainDays = $days - date('d',strtotime($endDate));
        if($remainDays > 0){
            $startDate   = date('Y-m-d', strtotime($endDate. ' + 1 days'));
            if ($remainDays > 7) {
                $remainDays -= 7;
                $endDate    = date('Y-m-d', strtotime($startDate. ' + 6 days'));
            } else {
                $endDate    = date('Y-m-d', strtotime($startDate. ' + '.($remainDays-1).' days'));
                $remainDays = 0;
            }
            $rangeW5 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
            $resultW5 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
                return $collection->aggregate([
                    ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
                ]);
            });
        }

        $resultW6 = null;
        $rangeW6 = null;
        if($remainDays > 0){
            $startDate   = date('Y-m-d', strtotime($endDate. ' + 1 days'));
            $endDate    = date('Y-m-d', strtotime($startDate. ' + '.($remainDays-1).' days'));
            $rangeW6 = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
            $resultW6 = AdResult::raw(function ($collection) use ($startDate, $endDate) {
                return $collection->aggregate([
                    ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
                ]);
            });
        }

        $startDate   = date('Y-' . $month .'-'. $startDayRange);
        $endDate    = date('Y-' . $month .'-'. $endDayRange);
        $rangeDate = "( ".date('d',strtotime($startDate))." - ".date('d',strtotime($endDate))." )";
        $resultRange = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
            ]);
        });

        $rangeArr = array('total' => $rangeTotal,
            'week1' => $rangeW1,
            'week2' => $rangeW2,
            'week3' => $rangeW3,
            'week4' => $rangeW4,
            'week5' => $rangeW5,
            'week6' => $rangeW6,
            'rangeDate' => $rangeDate);

        $resultsArr = array('total' => $results,
            'week1' => $resultW1,
            'week2' => $resultW2,
            'week3' => $resultW3,
            'week4' => $resultW4,
            'week5' => $resultW5,
            'week6' => $resultW6,
            'rangeDate' => $resultRange);

        $data['report'] = $this->prepare_report($resultsArr, $rangeArr);
        return view('pages.table_report_monthly', $data);
    }

    private function prepare_report($resultsArr, $rangeArr) {
        $config = Config::getByKeys(['USD_VND', 'USD_THB']);
        $report = array('config' => $config);

        foreach ($resultsArr as $key => $value) {
            $report[$key] = (object)[
                'c1' => 0,
                'c2' => 0,
                'c3' => 0,
                'c3b' => 0,
                'c3bg' => 0,
                'spent' => 0,
                'l1' => 0,
                'l3' => 0,
                'l6' => 0,
                'l8' => 0,
                'revenue' => 0,
                'range' => "---",
            ];

            if ($rangeArr != null) {
                $report[$key]->range = isset($rangeArr[$key]) ? $rangeArr[$key] : NULL;
            }

            if(isset($value) && $value != null) {
                foreach ($value as $item) {
                    $report[$key]->c1 += isset($item->c1) ? $item->c1 : 0;
                    $report[$key]->c2 += isset($item->c2) ? $item->c2 : 0;
                    $report[$key]->c3 += isset($item->c3) ? $item->c3 : 0;
                    $report[$key]->c3b += isset($item->c3b) ? $item->c3b : 0;
                    $report[$key]->c3bg += isset($item->c3bg) ? $item->c3bg : 0;
                    $report[$key]->spent += isset($item->spent) ? $item->spent : 0;
                    $report[$key]->l1 += isset($item->l1) ? $item->l1 : 0;
                    $report[$key]->l3 += isset($item->l3) ? $item->l3 : 0;
                    $report[$key]->l6 += isset($item->l6) ? $item->l6 : 0;
                    $report[$key]->l8 += isset($item->l8) ? $item->l8 : 0;
                    $report[$key]->revenue += isset($item->revenue) ? $item->revenue : 0;
                }
            }
        }

        return $report;
    }

    private function getReport() {
        $month       = request('month');
        $year        = request('year');
        $noLastMonth = request('noLastMonth');

        if($month < 10){
            $month = '0'.$month;
        }
        $startDate = date($year.'-' . $month .'-01');
        $days      = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $endDate   = date($year.'-' . $month .'-'.$days);

        $resultsArr = array();
        $i = 0;
        do {
            $result = AdResult::raw(function ($collection) use ($startDate, $endDate) {
                /*if($month == "04") {
                    var_dump($startDate);
                    var_dump($endDate);
                    var_dump($collection->aggregate([
                        ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
                    ]));
                }*/
                return $collection->aggregate([
                    ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]]
                ]);
            });

            $resultsArr[date('Y',strtotime($startDate)).' - '.date('m',strtotime($startDate))] = $result;
            if ($month == "01") {
                $month = 12;
                $year -= 1;
            } else {
                $month -= 1;
            }

            if($month < 10){
                $month = '0'.$month;
            }
            $startDate = date($year.'-' . $month .'-01');
            $days      = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $endDate   = date($year.'-' . $month .'-'.$days);

            $i++;
        } while ($i < $noLastMonth);

        /*var_dump($resultsArr['2018 - 04']);
        die;*/

        $resultsArr = array_reverse($this->prepare_report($resultsArr, null), true);

        $data['reportY'] = $resultsArr;
        return $data;
    }

    public function getReportYear() {
        $data = $this->getReport();
        return view('pages.table_report_year', $data);
    }

    public function getReportStatistic() {
        $data = $this->getReport();
        return view('pages.table_report_statistic', $data);
    }

    public function prepareStatisticChart(){
        $data = $this->getReport();
        $usd_vnd = $data['reportY']['config']['USD_VND'];

        $c3b_array           = array();
        $c3b_price_array    = array();
        $l3_c3bg_array       = array();
        foreach ($data['reportY'] as $key => $value){
            if ($key == 'config'){
                continue;
            }
            $c3b    = @$value->c3b ? $value->c3b : 0;
            $spent  = @$value->spent ? $value->spent : 0;

            $key = str_replace(" ","",$key);
            $key = strtotime($key)*1000;
            $c3b_array[]        = [$key, $c3b];
            $c3b_price_array[]  = [$key, $c3b != 0 ? round($spent * $usd_vnd / $c3b) : 0];
            $l3_c3bg_array[]    = [$key, @$value->c3bg ? round(@$value->l3 / $value->c3bg, 4) * 100 : 0];
        }

        $result = array();
        $result['c3b']           = json_encode($c3b_array);
        $result['c3b_price']    = json_encode($c3b_price_array);
        $result['l3_c3bg']       = json_encode($l3_c3bg_array);
        return $result;
    }

    public function getContactPaginate(){
        $params = \request();
        $data   = $this->getC3Data();

        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $data['total'] ),
            "recordsFiltered" => intval( $data['total']),
            "data"            => $data['contacts'],
        );

        echo json_encode($json_data);  // send data as json format
    }

    public function getC3Data()
    {
        $request     = request();
        $status      = \request('is_export');
        $columns     = $this->setColumns();
        $data_where  = $this->getWhereData();
        $data_search = $this->getSeachData();
        $order       = $this->getOrderData();
        $startDate   = strtotime("midnight")*1000;
        $endDate     = strtotime("tomorrow")*1000;

        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr   = explode(' ', str_replace('/', '-', $date_place));
            $startDate  = strtotime($date_arr[0])*1000;
            $endDate    = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }

        $query = Contact::where('submit_time', '>=', $startDate);
        $query->where('submit_time', '<', $endDate);

        if(count($data_where) > 0){
            if(@$data_where['clevel'] == 'c3b'){
                $query->where('clevel', 'like', '%c3b%');
                unset($data_where['clevel']);
            }
            $query->where($data_where);
        }
        if($status == '1'){
            $query->where('is_export', 1);
        }
        if($status == '0'){
            $query->where('is_export', '<>', 1);
        }

        if($request->c3bg_checkbox == "true") {
            if($request->checked_date){
                $date_place = str_replace('-', ' ', $request->checked_date);
                $date_arr   = explode(' ', str_replace('/', '-', $date_place));
                $startDate  = strtotime($date_arr[0])*1000;
                $endDate    = strtotime("+1 day", strtotime($date_arr[1]))*1000;
            }
            $queryC3bg = Contact::where('submit_time', '>=', $startDate);
            $queryC3bg->where('submit_time', '<', $endDate);
            $checkedContacts = $queryC3bg->get();
            $phoneArr = array();
            foreach($checkedContacts as $contact) {
                array_push($phoneArr, $contact['phone']);
            }
            $query->whereNotIn('phone', $phoneArr);
        }

        if($data_search != ''){
            foreach ($columns as $key => $value){
                $query->orWhere($value, 'like', "%{$data_search}%");
            }
        }
        if($order){
            $query->orderBy($columns[$order['column']], $order['type']);
        } else {
            $query->orderBy('submit_time', 'desc');
        }
        $total    = $query->get();
        $limit    = intval($request->length);
        $offset   = intval($request->start);
        $contacts = $query->skip($offset)->take($limit)->get();

        $data['contacts']   = $this->formatRecord($contacts);
        $data['total']      = count($total);

        return $data;
    }

    private function getSeachData(){
        $request        = request();

        if($request->search_inp){
            return $request->search_inp;
        }

        if($request['search']['value']){
            return $request['search']['value'];
        }

        return '';
    }

    private function getOrderData(){
        $request    = request();
        $order      = array();

        if($request['order'][0]['column'] && $request['order'][0]['dir']){
            $order['column']    = $request['order'][0]['column'];
            $order['type']      = $request['order'][0]['dir'];
        }

        return $order;
    }

    private function setColumns(){
        //define index of column
        $columns = array(
            0   =>'name',
            1   =>'email',
            2   =>'phone',
            3   =>'age',
            4   =>'submit_time',
            5   =>'clevel',
            6   =>'current_level',
            7   =>'source_name',
            8   =>'team_name',
            9   =>'marketer_name',
            10  =>'campaign_name',
            11  =>'subcampaign_name',
            12  =>'ad_name',
            13  =>'landing_page',
        );

        return $columns;
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
            $data_where['marketer_id']      = $request->marketer_id;
        }
        if ($request->campaign_id) {
            $data_where['campaign_id']      = $request->campaign_id;
        }
        if ($request->subcampaign_id) {
            $data_where['subcampaign_id']   = $request->subcampaign_id;
        }
        if ($request->current_level) {
            $data_where['current_level']    = $request->current_level;
        }
        if ($request->clevel) {
            $data_where['clevel']           = $request->clevel;
        }
        if ($request->landing_page) {
            $data_where['landing_page']     = $request->landing_page;
        }

        return $data_where;
    }

    private function formatRecord($contacts){

        $name[0] = 11;
        $name[1] = 22;
        $name[2] = 33;

        foreach ($contacts as $contact){
            $arr[0] = $contact['_id'];
            $arr[1] = $contact['name'];
            $duplicatedNumbers = Contact::where('_id', '<>', $contact['_id'])
                ->where('phone', $contact['phone'])->count();
            $arr[2] = $duplicatedNumbers;

            $contact['name']                = $contact['name'] ? $arr : "-";
            $contact['email']               = $contact['email'] ? $contact['email'] : "-";
            $contact['phone']               = $contact['phone'] ? $contact['phone'] : "-";
            $contact['age']                 = $contact['age'] ? $contact['age'] : "-";
            $contact['submit_time']         = $contact['submit_time'] ?
                date('d-m-Y H:i:s',$contact['submit_time']/1000) : "-";
            $contact['clevel']              = $contact['clevel']? $contact['clevel'] : "-";
            $contact['current_level']       = $contact['current_level'] ? $contact['current_level'] : "-";
            $contact['source_name']         = $contact['source_name'] ? $contact['source_name'] : "-";
            $contact['team_name']           = $contact['team_name'] ? $contact['team_name'] : "-";
            $contact['marketer_name']       = $contact['marketer_name'] ? $contact['marketer_name'] : "-";
            $contact['campaign_name']       = $contact['campaign_name'] ? $contact['campaign_name'] : "-";
            $contact['subcampaign_name']    = $contact['subcampaign_name'] ? $contact['subcampaign_name'] : "-";
            $contact['ad_name']             = $contact['ad_name'] ? $contact['ad_name'] : "-";
            $contact['landing_page']        = $contact['landing_page'] ? $contact['landing_page'] : "-";
        }

        return $contacts;
    }

    public function updateStatusExport(){

        $data_where = $this->getWhereDataUpdateExport();

        $request = request();

        $startDate = strtotime("midnight")*1000;
        $endDate = strtotime("tomorrow")*1000;
        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr = explode(' ', str_replace('/', '-', $date_place));
            $startDate = strtotime($date_arr[0])*1000;
            // $endDate = Date('Y-m-d 23:59:59', strtotime($date_arr[1]));
            $endDate = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }
        $query = Contact::where('submit_time', '>=', $startDate);
        $query->where('submit_time', '<', $endDate);

        if(count($data_where) > 0){
            if(@$data_where['clevel'] == 'c3b'){
                $query->where('clevel', 'like', '%c3b%');
                unset($data_where['clevel']);
            }
            $query->where($data_where);
        }
        $id = [];

        if($request->id){
            $id = $request->id;
            $query->whereIn('_id', array_keys($request->id));
        }
        // only current page
        $page_size  = Config::getByKey('PAGE_SIZE');
        $query->limit((int)$page_size);

        $query->orderBy('submit_time', 'desc');

        $contacts = $query->get();
        foreach ($contacts as $contact)
        {
            if(isset($id[$contact->_id])){
                $contact->is_export = (int)$id[$contact->_id];
                $contact->save();
            }else{
                if($request->new_status != '')
                {
                    $contact->is_export = (int)$request->new_status;
                    $contact->save();
                }
            }
        }
    }

    private function getWhereDataUpdateExport(){
        $request    = request();
        $data_where = array();
        if ($request->source_id) {
            $data_where['source_id']        = $request->source_id;
        }
        if ($request->team_id) {
            $data_where['team_id']          = $request->team_id;
        }
        if ($request->marketer_id) {
            $data_where['marketer_id']      = $request->marketer_id;
        }
        if ($request->campaign_id) {
            $data_where['campaign_id']      = $request->campaign_id;
        }
        if ($request->subcampaign_id) {
            $data_where['subcampaign_id']   = $request->subcampaign_id;
        }
        if ($request->current_level) {
            $data_where['current_level']    = $request->current_level;
        }
        if ($request->clevel) {
            $data_where['clevel']           = $request->clevel;
        }
        if ($request->old_status) {
            $data_where['is_export']        = (int)$request->old_status;
        }
        if ($request->landing_page) {
            $data_where['landing_page']     = $request->landing_page;
        }

        return $data_where;
    }

	public function getHourC3Chart(){

		/*  phan date*/
		$month  = request('month');
		$year   = date('Y'); /* nam hien tai*/
		$d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
		$first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
		$last_day_this_month    = date('Y-' . $month .'-t'); /* ngày cuối cùng của tháng */
		/* end date */

		$array_month = array();
		for ($i = 1; $i <= $d; $i++) {
			//$array_month[date($i)] = 0;
			$timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
			$array_month[$i] = $timestamp;
		}

		$contacts_month = Contact::where( 'submit_time', '>=', strtotime( $first_day_this_month ) * 1000 )
		                         ->where( 'submit_time', '<=', strtotime( $last_day_this_month ) * 1000 )
		                         ->whereIn( 'clevel', [ 'c3a', 'c3b', 'c3bg' ] )
		                         ->get()
		                         ->groupBy( function ( $contact ) {
			                         return (int) strtotime(date('Y-m-d',$contact->submit_time / 1000)) * 1000;
		                         } )->transform( function ( $item, $k ) {
				return $item->groupBy( function ( $i ) {
					return (string) $i->clevel;
				} );
			} );

		foreach ($array_month as $key => $timestamp){
			if (isset($contacts_month[$timestamp])){
				$data_c3a[$timestamp] = [];
				$data_c3b[$timestamp] = [];
				$data_c3bg[$timestamp] = [];
				if (isset($contacts_month[$timestamp]['c3a'])){
					foreach ($contacts_month[$timestamp]['c3a'] as $item){
						$hour = (int) date( "H", $item->submit_time / 1000 );
						if (isset($data_c3a[$timestamp][$hour]))
							$data_c3a[$timestamp][$hour] += 1;
						else{
							$data_c3a[$timestamp][$hour] = 1;
						}
					}

				}
				if (isset($contacts_month[$timestamp]['c3b'])){
					foreach ($contacts_month[$timestamp]['c3b'] as $item){
						$hour = (int) date( "H", $item->submit_time / 1000 );
						if (isset($data_c3b[$timestamp][$hour]))
							$data_c3b[$timestamp][$hour] += 1;
						else{
							$data_c3b[$timestamp][$hour] = 1;
						}
					}

				}
				if (isset($contacts_month[$timestamp]['c3bg'])){
					foreach ($contacts_month[$timestamp]['c3bg'] as $item){
						$hour = (int) date( "H", $item->submit_time / 1000 );
						if (isset($data_c3bg[$timestamp][$hour]))
							$data_c3bg[$timestamp][$hour] += 1;
						else{
							$data_c3bg[$timestamp][$hour] = 1;
						}
					}

				}

			}
		}

		for ($h = 0; $h < 24; $h++){
			foreach ($array_month as $key => $timestamp){

				$line_c3a[$h][] = isset($data_c3a[$timestamp]) && isset($data_c3a[$timestamp][$h])  ? [$timestamp, $data_c3a[$timestamp][$h]] : [$timestamp, 0] ;
				$line_c3b[$h][] = isset($data_c3b[$timestamp]) && isset($data_c3b[$timestamp][$h])  ? [$timestamp, $data_c3b[$timestamp][$h]] : [$timestamp, 0] ;
				$line_c3bg[$h][] = isset($data_c3bg[$timestamp]) && isset($data_c3bg[$timestamp][$h])  ? [$timestamp, $data_c3bg[$timestamp][$h]] : [$timestamp, 0] ;
				$line_c3[$h][] = [$timestamp, (isset($data_c3a[$timestamp][$h]) ? $data_c3a[$timestamp][$h] : 0)
				                              + (isset($data_c3b[$timestamp][$h]) ? $data_c3b[$timestamp][$h] : 0)
				                              + (isset($data_c3bg[$timestamp][$h]) ? $data_c3bg[$timestamp][$h] : 0) ];
			}
			$chart_c3[$h] = json_encode($line_c3[$h]);
			$chart_c3b[$h] = json_encode($line_c3b[$h]);
			$chart_c3bg[$h] = json_encode($line_c3bg[$h]);
		}

		return $chart_c3;
	}

	public function getHourC3BChart(){

		/*  phan date*/
		$month  = request('month');
		$year   = date('Y'); /* nam hien tai*/
		$d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
		$first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
		$last_day_this_month    = date('Y-' . $month .'-t'); /* ngày cuối cùng của tháng */
		/* end date */

		$array_month = array();
		for ($i = 1; $i <= $d; $i++) {
			//$array_month[date($i)] = 0;
			$timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
			$array_month[$i] = $timestamp;
		}

		$contacts_month = Contact::where( 'submit_time', '>=', strtotime( $first_day_this_month ) * 1000 )
		                         ->where( 'submit_time', '<=', strtotime( $last_day_this_month ) * 1000 )
		                         ->whereIn( 'clevel', ['c3b'] )
		                         ->get()
		                         ->groupBy( function ( $contact ) {
			                         return (int) strtotime(date('Y-m-d',$contact->submit_time / 1000)) * 1000;
		                         } )->transform( function ( $item, $k ) {
				return $item->groupBy( function ( $i ) {
					return (string) $i->clevel;
				} );
			} );

		foreach ($array_month as $key => $timestamp){
			if (isset($contacts_month[$timestamp])){
				$data_c3b[$timestamp] = [];
				if (isset($contacts_month[$timestamp]['c3b'])){
					foreach ($contacts_month[$timestamp]['c3b'] as $item){
						$hour = (int) date( "H", $item->submit_time / 1000 );
						if (isset($data_c3b[$timestamp][$hour]))
							$data_c3b[$timestamp][$hour] += 1;
						else{
							$data_c3b[$timestamp][$hour] = 1;
						}
					}

				}

			}
		}

		for ($h = 0; $h < 24; $h++){
			foreach ($array_month as $key => $timestamp){
				$line_c3b[$h][] = isset($data_c3b[$timestamp]) && isset($data_c3b[$timestamp][$h])  ? [$timestamp, $data_c3b[$timestamp][$h]] : [$timestamp, 0] ;
			}
			$chart_c3b[$h] = json_encode($line_c3b[$h]);
		}

		return $chart_c3b;
	}

	public function getHourC3BGChart(){

		/*  phan date*/
		$month  = request('month');
		$year   = date('Y'); /* nam hien tai*/
		$d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
		$first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
		$last_day_this_month    = date('Y-' . $month .'-t'); /* ngày cuối cùng của tháng */
		/* end date */

		$array_month = array();
		for ($i = 1; $i <= $d; $i++) {
			//$array_month[date($i)] = 0;
			$timestamp = strtotime($year . "-" . $month . "-" . $i) * 1000;
			$array_month[$i] = $timestamp;
		}

		$contacts_month = Contact::where( 'submit_time', '>=', strtotime( $first_day_this_month ) * 1000 )
		                         ->where( 'submit_time', '<=', strtotime( $last_day_this_month ) * 1000 )
		                         ->whereIn( 'clevel', ['c3bg'] )
		                         ->get()
		                         ->groupBy( function ( $contact ) {
			                         return (int) strtotime(date('Y-m-d',$contact->submit_time / 1000)) * 1000;
		                         } )->transform( function ( $item, $k ) {
				return $item->groupBy( function ( $i ) {
					return (string) $i->clevel;
				} );
			} );

		foreach ($array_month as $key => $timestamp){
			if (isset($contacts_month[$timestamp])){
				$data_c3bg[$timestamp] = [];
				if (isset($contacts_month[$timestamp]['c3bg'])){
					foreach ($contacts_month[$timestamp]['c3bg'] as $item){
						$hour = (int) date( "H", $item->submit_time / 1000 );
						if (isset($data_c3bg[$timestamp][$hour]))
							$data_c3bg[$timestamp][$hour] += 1;
						else{
							$data_c3bg[$timestamp][$hour] = 1;
						}
					}

				}

			}
		}

		for ($h = 0; $h < 24; $h++){
			foreach ($array_month as $key => $timestamp){
				$line_c3bg[$h][] = isset($data_c3bg[$timestamp]) && isset($data_c3bg[$timestamp][$h])  ? [$timestamp, $data_c3bg[$timestamp][$h]] : [$timestamp, 0] ;
			}
			$chart_c3bg[$h] = json_encode($line_c3bg[$h]);
		}

		return $chart_c3bg;
	}


}
