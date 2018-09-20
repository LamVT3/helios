<?php

namespace App\Http\Controllers;

use App\Ad;
use App\AdResult;
use App\Campaign;
use App\Channel;
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
        $html_channel       = "<option value='' selected>All</option>";
        $html_landingpage   = "<option value='' selected>All</option>";

        if ($request->source_id) {
            $data_where['source_id'] = $request->source_id;
            $teams = Team::orderBy('name')->get();
            foreach ($teams as $team) {
                $source = array_keys($team->sources);
                if(in_array($request->source_id, $source)){
                    $html_team .= "<option value=" . $team->id . "> " . $team->name . " </option>";
                    $marketers  = $team->members;
                    foreach ($marketers as $item) {
                        $html_marketer .= "<option value='" . $item['user_id'] . "'> " . $item['username'] . " </option>";
                    }

                    $campaigns = Campaign::where('team_id', $team->id)->orderBy('name')->get();
                    foreach ($campaigns as $item) {
                        $html_campaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
                    }

                    $subcampaign = Subcampaign::where('team_id', $team->id)->orderBy('name')->get();
                    foreach ($subcampaign as $item) {
                        $html_subcampaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
                    }

                    $ad = Ad::where('team_id', $team->id)->orderBy('channel_name')->get();
                    foreach ($ad as $item) {
                        if(!isset($arr_landingpage[$item->landing_page_id])){
                            $arr_landingpage[$item->landing_page_id]    = $item->landing_page_id;
                        }
                        if(!isset($arr_channel[$item->channel_id])){
                            $arr_channel[$item->channel_id]      = $item->channel_id;
                        }
                    }
                }
            }

            $landing_page = LandingPage::whereIn('_id', $arr_landingpage)->orderBy('name')->get();
            foreach ($landing_page as $item) {
                $html_landingpage .= "<option value=" . $item->id . "> " . $item->url . " </option>";
            }
            $channel = Channel::whereIn('_id', $arr_channel)->where('is_active', 1)->orderBy('name')->get();
            foreach ($channel as $item){
                $html_channel .= "<option value=" . $item->name . "> " . $item->name . " </option>";
            }
        }
        else {
            $teams = Team::orderBy('name')->get();
            foreach ($teams as $team) {
                $html_team .= "<option value=" . $team->id . "> " . $team->name . " </option>";
                $marketers  = $team->members;
                foreach ($marketers as $item) {
                    $html_marketer .= "<option value='" . $item['user_id'] . "'> " . $item['username'] . " </option>";
                }
            }
            $campaigns = Campaign::orderBy('name')->get();
            foreach ($campaigns as $item) {
                $html_campaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
            }

            $subcampaign    = Subcampaign::orderBy('name')->get();
            foreach ($subcampaign as $item) {
                $html_subcampaign .= "<option value=" . $item->id . "> " . $item->name . " </option>";
            }
            $channel = Channel::where('is_active', 1)->orderBy('name')->get();
            foreach ($channel as $item) {
                $html_channel .= "<option value=" . $item->name . "> " . $item->name . " </option>";
            }
            $landing_page = LandingPage::orderBy('name')->get();
            foreach ($landing_page as $item) {
                $html_landingpage .= "<option value=" . $item->id . "> " . $item->url . " </option>";
            }
        }

        $data_return = array(
            'status'                => TRUE,
            'content_team'          => $html_team,
            'content_campaign'      => $html_campaign,
            'content_marketer'      => $html_marketer,
            'content_subcampaign'   => $html_subcampaign,
            'content_channel'       => $html_channel,
            'content_landingpage'   => $html_landingpage
        );
        echo json_encode($data_return);

    }

    public function getFilterTeam()
    {
        $data_where = array();
        $request = request();

        $html_campaign      = "<option value='' selected>All</option>";
        $html_marketer      = "<option value='' selected>All</option>";
        $html_subcampaign   = "<option value='' selected>All</option>";
        $html_channel       = "<option value='' selected>All</option>";
        $html_landingpage   = "<option value='' selected>All</option>";

        if ($request->team_id) {
            $data_where['team_id'] = $request->team_id;
            $team           = Team::find($request->team_id);
            $campaigns      = Campaign::where($data_where)->orderBy('name')->get();
            $marketers      = $team->members;
            $subcampaign    = Subcampaign::where($data_where)->orderBy('name')->get();
            $ad             = Ad::where('team_id', $team->id)->orderBy('channel_name')->get();
            foreach ($ad as $item) {
                if(!isset($arr_landingpage[$item->landing_page_id])){
                    $arr_landingpage[$item->landing_page_id]    = $item->landing_page_id;
                }
                if(!isset($arr_channel[$item->channel_id])){
                    $arr_channel[$item->channel_id]      = $item->channel_id;
                }
            }
            $landing_page   = LandingPage::whereIn('_id', $arr_landingpage)->orderBy('name')->get();
            $channel        = Channel::whereIn('_id', $arr_channel)->where('is_active', 1)->orderBy('name')->get();

        }else{
            $campaigns      = Campaign::orderBy('name')->get();
            $marketers      = User::where('is_active', 1)->orderBy('username')->get();
            $subcampaign    = Subcampaign::orderBy('name')->get();
            $ad             = Ad::orderBy('channel_name')->get();
            $landing_page   = LandingPage::orderBy('name')->get();
            $channel        = Channel::where('is_active', 1)->orderBy('name')->get();
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
        foreach ($landing_page as $item) {
            $html_landingpage .= "<option value=" . $item->id . "> " . $item->url . " </option>";
        }

        foreach ($channel as $item) {
            $html_channel .= "<option value=" . $item->name . "> " . $item->name . " </option>";
        }

        $data_return = array(
            'status'                => TRUE,
            'content_campaign'      => $html_campaign,
            'content_marketer'      => $html_marketer,
            'content_subcampaign'   => $html_subcampaign,
            'content_channel'       => $html_channel,
            'content_landingpage'   => $html_landingpage
        );
        echo json_encode($data_return);

    }

    public function getFilterMaketer()
    {
        $request = request();
        $html_campaign      = "<option value='' selected>All</option>";
        $html_subcampaign   = "<option value='' selected>All</option>";
        $html_channel       = "<option value='' selected>All</option>";
        $html_landingpage   = "<option value='' selected>All</option>";

        if ($request->creator_id) {
            $data_where['creator_id'] = $request->creator_id;
            $campaigns      = Campaign::where($data_where)->orderBy('name')->get();
            $subcampaign    = Subcampaign::where($data_where)->orderBy('name')->get();
            $ad             = Ad::where($data_where)->orderBy('channel_name')->get();
            foreach ($ad as $item) {
                if(!isset($arr_landingpage[$item->landing_page_id])){
                    $arr_landingpage[$item->landing_page_id]    = $item->landing_page_id;
                }
                if(!isset($arr_channel[$item->channel_id])){
                    $arr_channel[$item->channel_id]      = $item->channel_id;
                }
            }
            $landing_page   = LandingPage::whereIn('_id', $arr_landingpage)->orderBy('name')->get();
            $channel        = Channel::whereIn('_id', $arr_channel)->where('is_active', 1)->orderBy('name')->get();
        }else{
            $campaigns      = Campaign::orderBy('name')->get();
            $subcampaign    = Subcampaign::orderBy('name')->get();
            $ad             = Ad::orderBy('channel_name')->get();
            $landing_page   = LandingPage::orderBy('name')->get();
            $channel        = Channel::where('is_active', 1)->orderBy('name')->get();
        }

        foreach ($campaigns as $item) {
            $html_campaign      .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }
        foreach ($subcampaign as $item) {
            $html_subcampaign   .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }
        foreach ($landing_page as $item) {
            $html_landingpage   .= "<option value=" . $item->id . "> " . $item->url . " </option>";
        }
        foreach ($channel as $item) {
            $html_channel       .= "<option value=" . $item->name . "> " . $item->name . " </option>";
        }

        $data_return = array(
            'status'                => TRUE,
            'content_campaign'      => $html_campaign,
            'content_subcampaign'   => $html_subcampaign,
            'content_channel'       => $html_channel,
            'content_landingpage'   => $html_landingpage
        );
        echo json_encode($data_return);

    }

    public function getFilterCampaign()
    {
        $request = request();

        $html_subcampaign   = "<option value='' selected>All</option>";
        $html_landingpage   = "<option value='' selected>All</option>";
        $html_channel       = "<option value='' selected>All</option>";

        if ($request->campaign_id) {
            $subcampaign    = Subcampaign::where('campaign_id', $request->campaign_id)->get();
            $ad             = Ad::where('campaign_id', $request->campaign_id)->get();
            foreach ($ad as $item) {
                if(!isset($arr_landingpage[$item->landing_page_id])){
                    $arr_landingpage[$item->landing_page_id]    = $item->landing_page_id;
                }
                if(!isset($arr_channel[$item->channel_id])){
                    $arr_channel[$item->channel_id]      = $item->channel_id;
                }
            }
            $landing_page   = LandingPage::whereIn('_id', $arr_landingpage)->orderBy('name')->get();
            $channel        = Channel::whereIn('_id', $arr_channel)->where('is_active', 1)->orderBy('name')->get();
        }else{
            $subcampaign    = Subcampaign::orderBy('name')->get();
            $ad = Ad::all();
            $landing_page   = LandingPage::orderBy('name')->get();
            $channel        = Channel::where('is_active', 1)->orderBy('name')->get();
        }

        foreach ($subcampaign as $item) {
            $html_subcampaign   .= "<option value=" . $item->id . "> " . $item->name . " </option>";
        }
        foreach ($landing_page as $item) {
            $html_landingpage   .= "<option value=" . $item->id . "> " . $item->url . " </option>";
        }
        foreach ($channel as $item) {
            $html_channel       .= "<option value=" . $item->name . "> " . $item->name . " </option>";
        }

        $data_return = array(
            'status'                => TRUE,
            'content_subcampaign'   => $html_subcampaign,
            'content_channel'       => $html_channel,
            'content_landingpage'   => $html_landingpage
        );
        echo json_encode($data_return);

    }

    public function getFilterSubCampaign()
    {
        $request = request();

        $html_landingpage   = "<option value='' selected>All</option>";
        $html_channel       = "<option value='' selected>All</option>";

        if ($request->subcampaign_id) {
            $ad = Ad::where('subcampaign_id', $request->subcampaign_id)->get();
            foreach ($ad as $item) {
                if(!isset($arr_landingpage[$item->landing_page_id])){
                    $arr_landingpage[$item->landing_page_id]    = $item->landing_page_id;
                }
                if(!isset($arr_channel[$item->channel_id])){
                    $arr_channel[$item->channel_id]      = $item->channel_id;
                }
            }
            $landing_page   = LandingPage::whereIn('_id', $arr_landingpage)->orderBy('name')->get();
            $channel        = Channel::whereIn('_id', $arr_channel)->where('is_active', 1)->orderBy('name')->get();
        }else{
            $ad = Ad::orderBy('name')->get();
            $landing_page   = LandingPage::orderBy('name')->get();
            $channel        = Channel::where('is_active', 1)->orderBy('name')->get();
        }

        foreach ($landing_page as $item) {
            $html_landingpage   .= "<option value=" . $item->id . "> " . $item->url . " </option>";
        }
        foreach ($channel as $item) {
            $html_channel       .= "<option value=" . $item->name . "> " . $item->name . " </option>";
        }

        $data_return = array(
            'status'                => TRUE,
            'content_channel'       => $html_channel,
            'content_landingpage'   => $html_landingpage
        );
        echo json_encode($data_return);
    }

    public function dashboard()
    {
        $request = request();
        /* phan dashboard*/
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : Date('Y-m-d');
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : Date('Y-m-d');

        $query_dashboard = AdResult::where('date', '>=', $startDate)
            ->where('date', '<=', $endDate);

        if($request->marketer_id){
            $query_dashboard->where('creator_id', $request->marketer_id);
        }
        if($request->channel_id){
            $ads    = array();
            $ads = Ad::where('channel_id', $request->channel_id)->pluck('_id')->toArray();
            $query_dashboard->whereIn('creator_id', $ads);
        }

        $c3         = $query_dashboard->sum('c3');
        $spent      = $query_dashboard->sum('spent');  // USD
        $revenue    = $query_dashboard->sum('revenue'); // Bath

        $dashboard['c3']        = $c3;
        $dashboard['spent']     = $this->convert_spent($spent);
        $dashboard['revenue']   = $this->convert_revenue($revenue);
        $dashboard['c3_cost']   = $dashboard['c3'] ? round( $dashboard['spent'] / $dashboard['c3'], 2) : 0;

        $dashboard['c3']        = number_format($dashboard['c3']);
        $dashboard['c3_cost']   = number_format($dashboard['c3_cost']);
        $dashboard['spent']     = number_format($dashboard['spent']);
        $dashboard['revenue']   = number_format($dashboard['revenue']);
        /* end Dashboard */

        return response()->json(['type' => 'success', 'dashboard' => $dashboard]);
    }

    public function c3_leaderboard()
    {
        $request    = request();
        $period     = $request->period ? $request->period : 'today';

        $startDate  = date('Y-m-d');
        $endDate    = date('Y-m-d');

        if ($period === 'thisweek') {
            $startDate  = date('Y-m-d', strtotime('Last Monday', time()));
            $endDate    = date('Y-m-d', strtotime('Next Sunday', time()));
        }

        if ($period === 'thismonth') {
            $startDate  = date('Y-m-01');
            $endDate    = date('Y-m-t');
        }

        $query = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                [
                    '$group' => [
                        '_id' => '$creator_id',
                        'c3b' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                    ]
                ],
                ['$sort' => ['c3b' => -1]]
            ]);
        });


        $table = '<table id="c3_leaderboard" class="table table-bordered table-hover no-boder-top" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">C3B</th>
                                    <th class="text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>';

        $data = array();
        $total = 0;
        foreach ($query as $i => $item) {
//            if($i > 4) break;
//            if(!$item->c3) continue;  // not show if c3 = 0

            $user = User::find($item->_id);
            if(!$user){ // if not found user
                $unknown            = config('constants.UNKNOWN');
                $user['username']   = $unknown;
                $user['rank']       = $unknown;
            }

            if(isset($data[$user['username']])){
                $data[$user['username']]['c3b'] += $item->c3b;
            }else{
                $data[$user['username']]['username']    = $user['username'];
                $data[$user['username']]['rank']        = $user['rank'];
                $data[$user['username']]['c3b']         = $item->c3b;
            }
            $total += $item->c3b;
        }

        $no = 0;
        foreach ($data as $item){
            $no++;
            $rate = round($item['c3b'] * 100 / $total, 2);

            $table .= "<tr>
                            <th>{$no}</th>
                            <th class='text-center'>{$item['username']}</th>
                            <td class='text-center'>{$item['c3b']}</td>
                             <td class='text-center'>{$rate}</td>
                        </tr>";
        }

        $table .= '</tbody> </table>';

        return $table;
    }

    public function revenue_leaderboard()
    {
        $request    = request();
        $period     = $request->period ? $request->period : 'today';

        $startDate  = date('Y-m-d');
        $endDate    = date('Y-m-d');

        if ($period === 'thisweek') {
            $startDate  = date('Y-m-d', strtotime('Last Monday', time()));
            $endDate    = date('Y-m-d', strtotime('Next Sunday', time()));
        }

        if ($period === 'thismonth') {
            $startDate  = date('Y-m-01');
            $endDate    = date('Y-m-t');
        }

        $query = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                [
                    '$group' => [
                        '_id'       => '$creator_id',
                        'revenue'   => [
                            '$sum'  => '$revenue'
                        ]
                    ]
                ],
                ['$sort' => ['revenue' => -1]]
            ]);
        });

        $table = '<table id="revenue_leaderboard" class="table table-striped table-bordered table-hover no-boder-top" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Revenue ('. $request->unit .')</th>
                                    <th class="text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>';

        $data = array();
        $total = 0;
        foreach ($query as $i => $item) {
//            if($i > 4) break;
//            if(!$item->revenue) continue;
            if($item->_id == config('constants.SALE_CRM')){
                $sale_crm           = config('constants.SALE_CRM');
                $user['username']   = $sale_crm;
                $user['rank']       = $sale_crm;
            }else{
                $user = User::find($item->_id);
                if(!$user){
                    $unknown            = config('constants.UNKNOWN');
                    $user['username']   = $unknown;
                    $user['rank']       = $unknown;
                }
            }

            $revenue = $this->convert_revenue($item->revenue);

            if(isset($data[$user['username']])){
                $data[$user['username']]['revenue'] += $revenue;
            }else{
                $data[$user['username']]['username']    = $user['username'];
                $data[$user['username']]['rank']        = $user['rank'];
                $data[$user['username']]['revenue']     = $revenue;
            }

            $total += $revenue;
        }

        $no = 0;
        foreach ($data as $item){
            $revenue = number_format($item['revenue'], 2);
            $rate = round($item['revenue'] * 100 / $total, 2);
            $no++;
            $table .= "<tr>
                            <th>{$no}</th>
                            <th class='text-center'>{$item['username']}</th>
                            <td class='text-center'>{$revenue}</td>
                            <td class='text-center'>{$rate}</td>
                        </tr>";
        }

        $table .= '</tbody> </table>';

        return $table;
    }

    private function convert_revenue($revenue){
        $request    = request();

        $config     = Config::getByKeys(['USD_VND', 'USD_THB', 'THB_VND']);
        $usd_vnd    = $config['USD_VND'];
        $usd_tbh    = $config['USD_THB'];
        $thb_vnd    = $config['THB_VND'];

        $rate = config('constants.UNIT_USD');
        if($request->unit){
            $rate = $request->unit;
        }

        if($request->unit == config('constants.UNIT_USD')){
            $revenue    = $usd_tbh ? $revenue / $usd_tbh : 0;
        }elseif ($request->unit == config('constants.UNIT_VND')){
            $revenue    = $revenue * $thb_vnd;
        }

        return $revenue;

    }

    private function convert_spent($spent){
        $request    = request();

        $config     = Config::getByKeys(['USD_VND', 'USD_THB', 'THB_VND']);
        $usd_vnd    = $config['USD_VND'];
        $usd_tbh    = $config['USD_THB'];

        if($request->unit == config('constants.UNIT_VND')){
            $spent    = $spent * $usd_vnd;
        }elseif ($request->unit == config('constants.UNIT_BAHT')){
            $spent    = $spent * $usd_tbh;
        }
        return $spent;
    }

    public function spent_leaderboard()
    {
        $request    = request();
        $period     = $request->period ? $request->period : 'today';

        $startDate  = date('Y-m-d');
        $endDate    = date('Y-m-d');

        if ($period === 'thisweek') {
            $startDate  = date('Y-m-d', strtotime('Last Monday', time()));
            $endDate    = date('Y-m-d', strtotime('Next Sunday', time()));
        }

        if ($period === 'thismonth') {
            $startDate  = date('Y-m-01');
            $endDate    = date('Y-m-t');
        }

        $query = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate]]],
                [
                    '$group' => [
                        '_id'   => '$creator_id',
                        'spent' => [
                            '$sum' => '$spent'
                        ]
                    ]
                ],
                ['$sort' => ['spent' => -1]]
            ]);
        });

        $table = '<table id="spent_leaderboard" class="table table-striped table-bordered table-hover no-boder-top" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Spent ('. $request->unit .')</th>
                                    <th class="text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>';

        $data = array();
        $total = 0;
        foreach ($query as $i => $item) {
//            if($i > 4) break;
//            if(!$item->spent) continue;

            $user = User::find($item->_id);
            if(!$user){ // if not found user
                $unknown            = config('constants.UNKNOWN');
                $user['username']   = $unknown;
                $user['rank']       = $unknown;
            }

            $spent = $this->convert_spent($item->spent);

            if(isset($data[$user['username']])){
                $data[$user['username']]['spent'] += $spent;
            }else{
                $data[$user['username']]['username']    = $user['username'];
                $data[$user['username']]['rank']        = $user['rank'];
                $data[$user['username']]['spent']       = $spent;
            }
            $total += $spent;
        }

        $no = 0;
        foreach ($data as $item){
            $spent = number_format($item['spent'], 2);
            $rate = round($item['spent'] * 100 / $total, 2);
            $no++;
            $table .= "<tr>
                            <th>{$no}</th>
                            <th class='text-center'>{$item['username']}</th>
                            <td class='text-center'>{$spent}</td>
                            <td class='text-center'>{$rate}</td>
                        </tr>";
        }

        $table .= '</tbody> </table>';

        return $table;
    }

    // 2018-04-17 [HEL-9] LamVT add dropdown for C3/L8 chart

    public function prepareStatisticChart(){
        $report = new ReportController();
        $data = $report->prepareYear();
        $usd_vnd = $data['reportY']['config']['USD_VND'];

        $c3b_array          = array();
        $c3b_price_array    = array();
        $l3_c3bg_array      = array();
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

    public function getC3Chart(){

        /*  phan date*/
        $month  = request('month');
        $year   = date('Y'); /* nam hien tai*/
        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month    = date('Y-' . $month .'-t'); /* ngày cuối cùng của tháng */
        /* end date */

        $request    = request();

        if($request->marketer_id && $request->channel_id){
            $ads    = array();
            $ads = Ad::where('channel_id', $request->channel_id)->pluck('_id')->toArray();
            $kpi = $this->get_kpi();
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['creator_id' => $request->marketer_id]],
                ['$match' => ['ad_id' => ['$in' => $ads]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        ],
                    ]
                ]
            ];
        }elseif($request->marketer_id){
            $kpi = $this->get_kpi();
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['creator_id' => $request->marketer_id]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        ],
                    ]
                ]
            ];
        }elseif($request->channel_id){
            $kpi = $this->getTotalKpi();
            $ads    = array();
            $ads = Ad::where('channel_id', $request->channel_id)->pluck('_id')->toArray();

            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['ad_id' => ['$in' => $ads]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        ],
                    ]
                ]
            ];
        }else{
            $kpi = $this->getTotalKpi();
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3' => [
                            '$sum' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        ],
                    ]
                ]
            ];
        }
        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($first_day_this_month, $last_day_this_month, $match) {
            return $collection->aggregate($match);
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
            if (isset($kpi[$year][$month][$key])) {
                $chart_kpi[] = [$timestamp, (int)$kpi[$year][$month][$key]];
            } else {
                $chart_kpi[] = [$timestamp, 0];
            }
        }
        $chart_c3   = json_encode($chart_c3);
        $chart_kpi  = json_encode($chart_kpi);
        $dashboard['chart_c3']  = $chart_c3;
        $dashboard['chart_kpi'] = $chart_kpi;

        return $dashboard;
    }

    public function getL8Chart(){

        /*  phan date*/
        $month  = request('month');
        $year   = date('Y'); /* nam hien tai*/
        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $first_day_this_month   = date('Y-' . $month .'-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month    = date('Y-' . $month .'-t'); /* ngày cuối cùng của tháng */

        $request    = request();

        if($request->marketer_id && $request->channel_id){
            $ads    = array();
            $ads = Ad::where('channel_id', $request->channel_id)->pluck('_id')->toArray();
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['creator_id' => $request->marketer_id]],
                ['$match' => ['ad_id' => ['$in' => $ads]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'l8' => [
                            '$sum' => '$l8'
                        ]
                    ]
                ]
            ];
        }elseif($request->marketer_id){
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['creator_id' => $request->marketer_id]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'l8' => [
                            '$sum' => '$l8'
                        ]
                    ]
                ]
            ];
        }elseif($request->channel_id){
            $ads    = array();
            $ads = Ad::where('channel_id', $request->channel_id)->pluck('_id')->toArray();

            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                ['$match' => ['ad_id' => ['$in' => $ads]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'l8' => [
                            '$sum' => '$l8'
                        ]
                    ]
                ]
            ];
        }else{
            $match = [
                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'l8' => [
                            '$sum' => '$l8'
                        ]
                    ]
                ]
            ];
        }

        /*  start Chart*/
        $query_chart = AdResult::raw(function ($collection) use ($first_day_this_month, $last_day_this_month, $match) {
            return $collection->aggregate($match);
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

		foreach ($array_month as $key => $timestamp){
			$data_c3[ $timestamp ]  = [];
			for ($hr = 0; $hr <= 23; $hr++) {
				$data_c3[ $timestamp ][ $hr ]  = 0;
				$temp_c3[ $timestamp ][ $hr ]   = 0;
			}
		}
		$ad_id  = $this->getAds();

		$_contacts_c3 = Contact::raw( function ( $collection ) use ( $ad_id, $first_day_this_month, $last_day_this_month) {
			if (count($ad_id) > 0){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3' => [ '$sum' => 1 ],
						]
					]
				];
			} else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3a', 'c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );

		foreach ( $_contacts_c3 as $item_result ) {
			$timestamp = $item_result['_id'];
			if (isset($data_c3[$timestamp->submit_date]))
				$data_c3[$timestamp->submit_date][$timestamp->submit_hour] += (int)$item_result->c3;
		}

		for ($hr = 0; $hr <= 23; $hr++){
			for ($h = 0; $h <= $hr; $h++){
				foreach ($array_month as $key => $timestamp){
					$temp_c3[$timestamp][$hr] += $data_c3[$timestamp][$h];

				}
			}

			foreach ($array_month as $key => $timestamp){
				$line_c3[$hr][] = [$timestamp, $temp_c3[$timestamp][$hr]];
			}

			$chart_c3[$hr] = json_encode($line_c3[$hr]);
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

		foreach ($array_month as $key => $timestamp){
			$data_c3b[ $timestamp ]  = [];

			for ($hr = 0; $hr <= 23; $hr++) {
				$data_c3b[ $timestamp ][ $hr ]  = 0;
				$temp_c3b[ $timestamp ][ $hr ]  = 0;
			}
		}
		$ad_id  = $this->getAds();

		$_contacts_c3b = Contact::raw( function ( $collection ) use ( $ad_id, $first_day_this_month, $last_day_this_month) {
			if (count($ad_id) > 0){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3b' => [ '$sum' => 1 ],
						]
					]
				];
			} else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3b','c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3b' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );

		foreach ( $_contacts_c3b as $item_result ) {
			$timestamp = $item_result['_id'];
			if (isset($data_c3b[$timestamp->submit_date]))
				$data_c3b[$timestamp->submit_date][$timestamp->submit_hour] += (int)$item_result->c3b;
		}

		for ($hr = 0; $hr <= 23; $hr++){
			for ($h = 0; $h <= $hr; $h++){
				foreach ($array_month as $key => $timestamp){
					$temp_c3b[$timestamp][$hr] += $data_c3b[$timestamp][$h];
				}
			}

			foreach ($array_month as $key => $timestamp){
				$line_c3b[$hr][] = [$timestamp, $temp_c3b[$timestamp][$hr]];
			}

			$chart_c3b[$hr] = json_encode($line_c3b[$hr]);
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

		foreach ($array_month as $key => $timestamp){
			$data_c3bg[ $timestamp ] = [];
			for ($hr = 0; $hr <= 23; $hr++) {
				$data_c3bg[ $timestamp ][ $hr ]  = 0;
				$temp_c3bg[ $timestamp ][ $hr ]  = 0;
			}
		}

		$ad_id  = $this->getAds();

		$_contacts_c3bg = Contact::raw( function ( $collection ) use ( $ad_id, $first_day_this_month, $last_day_this_month) {
			if (count($ad_id) > 0){
				$match = [
					['$match' => ['ad_id' => ['$in' => $ad_id]]],
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3bg' => [ '$sum' => 1 ],
						]
					]
				];
			} else{
				$match = [
					[ '$match' => [ 'submit_time' => [ '$gte' => strtotime( $first_day_this_month ) * 1000, '$lte' => strtotime( $last_day_this_month ) * 1000 ] ] ],
					[ '$match' => [ 'clevel' => ['$in' => ['c3bg']] ] ],
					[
						'$group' => [
							'_id' => ['submit_date'=>'$submit_date', 'submit_hour'=>'$submit_hour'],
							'c3bg' => [ '$sum' => 1 ],
						]
					]
				];
			}
			return $collection->aggregate( $match );
		} );

		foreach ( $_contacts_c3bg as $item_result ) {
			$timestamp = $item_result['_id'];
			if (isset($data_c3bg[$timestamp->submit_date]))
				$data_c3bg[$timestamp->submit_date][$timestamp->submit_hour] += (int)$item_result->c3bg;
		}


		for ($hr = 0; $hr <= 23; $hr++){
			for ($h = 0; $h <= $hr; $h++){
				foreach ($array_month as $key => $timestamp){
					$temp_c3bg[$timestamp][$hr] += $data_c3bg[$timestamp][$h];

				}
			}

			foreach ($array_month as $key => $timestamp){
				$line_c3bg[$hr][] = [$timestamp, $temp_c3bg[$timestamp][$hr]];
			}

			$chart_c3bg[$hr] = json_encode($line_c3bg[$hr]);
		}

		return $chart_c3bg;
	}

	private function getAds(){
		$data_where = $this->getWhereData();
		$ads    = array();
		if (count($data_where) >= 1) {
			$ads = Ad::where($data_where)->pluck('_id')->toArray();
		}
		return $ads;
	}

    public function get_channel(){
        $request = request();

        if($request->source_id){

            $source_id = $request->source_id;
            $channel =  Channel::where('source_id', $source_id)->get();
        }else{
            $channel =  Channel::all();
        }

        return json_encode($channel);
    }

    private function getTotalKpi(){
        $request = request();
        $users  = User::all();
        $month  = date('m');
        if($request->month){
            $month = $request->month;
        }
        $year   = date('Y');
        $d      = cal_days_in_month(CAL_GREGORIAN, $month, $year);


        $total_kpi = array();
        foreach ($users as $user){
            $kpi = @$user->kpi[$year][$month];
            if(count($kpi) < 1){
                continue;
            }
            if(isset($total_kpi[$year][$month])){
                for ($i = 1; $i <= $d; $i++) {
                    if (isset($total_kpi[$year][$month][$i])) {
                        @$total_kpi[$year][$month][$i] += (int)@$kpi[$i];
                    } else {
                        @$total_kpi[$year][$month][$i] = (int)@$kpi[$i];
                    }
                }
            }else{
                $total_kpi[$year][$month] = @$kpi;
            }
        }

        return $total_kpi;
    }

    private function get_kpi(){
        $request = request();
        $kpi = array();
        if($request->marketer_id){
            $user   = User::find($request->marketer_id);
            $kpi    = @$user->kpi;
        }
        return $kpi;
    }


}
