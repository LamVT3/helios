<?php

namespace App\Http\Controllers;

use App\Ad;
use App\UserKpi;
use App\Team;
use App\User;
use App\AdResult;
use App\Channel;
use function Faker\Provider\pt_BR\check_digit;
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
        $page_title     = " KPI Report | Helios";
        $page_css       = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active         = 'assign_kpi';
        $breadcrumbs    = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> KPI Report </span>";

        $users  = User::getMarketerActive();
        $teams  = Team::all();
        $days   = $this->get_days_in_month();
        $month  = date('M');
        $year   = date('Y');
        $result = $this->get_data();

        $kpi_selection  = "c3b";
        $data_maketer   = $this->get_data_by_maketer($result); // get data to show in table follow marketer
        $data_team      = $this->get_data_by_team($result, $days); // get data to show in table follow team

        return view('pages.assign_kpi', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'users',
            'kpi_selection',
            'data_maketer',
            'data_team',
            'days',
            'month',
            'year',
            'teams'
        ));
    }

    function get_data_by_maketer($data){
        uasort($data, function ($item1, $item2) {
            if (@$item1['total_actual'] == @$item2['total_actual']) return 0;
            return @$item2['total_actual'] < @$item1['total_actual'] ? -1 : 1;
        });
        return $data;
    }

    public function save_kpi(){

        $request   = request();
        $userId    = $request->user_id;
        $channelId = $request->channel_id;
        $month     = $request->month;
        $year      = $request->year;

        // find first or create new row
        $userKpi   = UserKpi::firstOrNew(['user_id' => $userId, 'channel_id'=> $channelId]);

        $kpi = $userKpi->kpi;
        $kpi[$year][$month] = $request->kpi;
        ksort($kpi[$year]);
        $userKpi->kpi = $kpi;

        $kpi_cost = $userKpi->kpi_cost;
        $kpi_cost[$year][$month] = $request->kpi_cost;
        ksort($kpi_cost[$year]);
        $userKpi->kpi_cost = $kpi_cost;

        $kpi_l3_c3bg = $userKpi->kpi_l3_c3bg ;
        $kpi_l3_c3bg[$year][$month] = $request->kpi_l3_c3bg;
        ksort($kpi_l3_c3bg[$year]);

        $size_l3_c3bg = sizeof($kpi_l3_c3bg[$year][$month]);
        if($size_l3_c3bg>0){
            for ($i = 1; $i <= $size_l3_c3bg; $i++) {
                $kpi_l3_c3bg[$year][$month][$i] /= 100;
            }
        }
        $userKpi->kpi_l3_c3bg = $kpi_l3_c3bg;

        $userKpi->save();
    }

    public function get_kpi(){

        $request    = request();
        $userId     = $request->user_id;
        $channelId  = $request->channel_id;
        $month      = $request->month;
        $year       = $request->year;

        if($channelId == null) {
            $channels = $this->get_channel_user($userId); // get channels was own user
            $channelId = sizeof($channels) > 0 ? $channels[0]->_id : 0;
        }

        $userKpi = UserKpi::getKpiTwoParam($userId, $channelId); // get kpi follow user and channel

        $kpi = $kpi_cost = $kpi_l3_c3bg = array();
        if($userId) {
            if(isset($userKpi->kpi[$year][$month])) {
                $kpi = $userKpi->kpi[$year][$month];
            }
            if(isset($userKpi->kpi[$year][$month])) {
                $kpi_cost = $userKpi->kpi_cost[$year][$month];
            }
            if(isset($userKpi->kpi[$year][$month])) {
                $kpi_l3_c3bg = $userKpi->kpi_l3_c3bg[$year][$month];
            }
        }

        $data = array();
        $data['kpi'] = $kpi;
        $data['kpi_cost'] = $kpi_cost;

        $size_l3_c3bg = sizeof($kpi_l3_c3bg);
        for ($i = 1; $i <= $size_l3_c3bg; $i++) {
            $kpi_l3_c3bg[$i] *= 100;
        }
        $data['kpi_l3_c3bg'] = $kpi_l3_c3bg;

        return @$data;

    }

    // get channels of user
    public function get_channel_user($userId = null){

        $request = request();
        $userId  = $userId == null ? $request->user_id : $userId;

        $ads = Ad::where('creator_id', $userId)->get();
        $channel_ids = array();
        foreach ($ads as $ad) {
            if(isset($ad->channel_id)) {
                array_push($channel_ids, $ad->channel_id);
            }
        }
        $channels = Channel::whereIn('_id', $channel_ids)->get();
        return $channels;
    }

    public function get_data(){

        $request = request();
        $month = date('m'); /* thang hien tai */
        $year = date('Y'); /* nam hien tai*/

        if($request->month){
            $month = $request->month;
        }

        $kpi_selection = isset($request->kpi_selection) ? $request->kpi_selection : "c3b";

        // only get for marketers was activated
        $users = User::getMarketerActive();
        // get days of month
        $days = $this->get_days_in_month();

        $data = array();

        // count number of users
        $data['total']['count'] = 0;
        foreach ($users as $user) {
            $userId = $user->id;
            $data[$user->username]['user_id'] = $userId;

            $data[$user->username]['kpi'] = array();
            $data[$user->username]['actual'] = array();
            $data[$user->username]['total_kpi'] = 0;
            $data[$user->username]['total_actual'] = 0;

            $db_data = $this->get_db_data($userId);
            $userKpis = UserKpi::getKpiOneParam($user->_id);

            // count number of channels of user
            $data[$user->username]['count'] = 0;
            foreach ($userKpis as $userKpi) {
                $channel = Channel::find($userKpi->channel_id);

                // prepare data to calc kpi each channel
                switch ($kpi_selection) {
                    case "c3b_cost":
                        $kpi = isset($userKpi->kpi_cost[$year][$month]) ? $userKpi->kpi_cost[$year][$month] : array();
                        break;
                    case "l3_c3bg":
                        $kpi = isset($userKpi->kpi_l3_c3bg[$year][$month]) ? $userKpi->kpi_l3_c3bg[$year][$month] : array();
                        break;
                    case "c3b":
                    default:
                        $kpi = isset($userKpi->kpi[$year][$month]) ? $userKpi->kpi[$year][$month] : array();
                        break;
                }

                for($i = 1; $i <= $days; $i++) {
                    // day kpi each channel
                    if(isset($data[$user->username]['kpi'][$i])) {
                        $data[$user->username]['kpi'][$i] += isset($kpi[$i]) ? $kpi[$i] : 0;
                    } else {
                        $data[$user->username]['kpi'][$i] = isset($kpi[$i]) ? $kpi[$i] : 0;
                    }

                    // kpi each day
                    if(isset($data['total']['kpi'][$i])) {
                        $data['total']['kpi'][$i] += isset($kpi[$i]) ? $kpi[$i] : 0;
                    } else {
                        $data['total']['kpi'][$i] = isset($kpi[$i]) ? $kpi[$i] : 0;
                    }

                    // prepare data to calc actual
                    switch ($kpi_selection) {
                        case "c3b_cost":
                            if(isset($data[$user->username]['spent'][$i])) {
                                $data[$user->username]['spent'][$i] +=
                                    isset($db_data[$channel->name]['spent'][$i]) ? $db_data[$channel->name]['spent'][$i] :0;
                            } else {
                                $data[$user->username]['spent'][$i] =
                                    isset($db_data[$channel->name]['spent'][$i]) ? $db_data[$channel->name]['spent'][$i] :0;
                            }

                            if(isset($data[$user->username]['channels'][$channel->name]['spent'][$i])) {
                                $data[$user->username]['channels'][$channel->name]['spent'][$i] +=
                                    isset($db_data[$channel->name]['spent'][$i]) ? $db_data[$channel->name]['spent'][$i] :0;
                            } else {
                                $data[$user->username]['channels'][$channel->name]['spent'][$i] =
                                    isset($db_data[$channel->name]['spent'][$i]) ? $db_data[$channel->name]['spent'][$i] :0;
                            }

                            if(isset($data['total']['spent'][$i])) {
                                $data['total']['spent'][$i] +=
                                    isset($db_data[$channel->name]['spent'][$i]) ? $db_data[$channel->name]['spent'][$i] :0;
                            } else {
                                $data['total']['spent'][$i] =
                                    isset($db_data[$channel->name]['spent'][$i]) ? $db_data[$channel->name]['spent'][$i] :0;
                            }

                            if(isset($data[$user->username]['c3b'][$i])) {
                                $data[$user->username]['c3b'][$i] +=
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            } else {
                                $data[$user->username]['c3b'][$i] =
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            }

                            if(isset($data[$user->username]['channels'][$channel->name]['c3b'][$i])) {
                                $data[$user->username]['channels'][$channel->name]['c3b'][$i] +=
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            } else {
                                $data[$user->username]['channels'][$channel->name]['c3b'][$i] =
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            }

                            if(isset($data['total']['c3b'][$i])) {
                                $data['total']['c3b'][$i] +=
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            } else {
                                $data['total']['c3b'][$i] =
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            }
                           break;
                        case "l3_c3bg":
                            if(isset($data[$user->username]['l3'][$i])) {
                                $data[$user->username]['l3'][$i] +=
                                    isset($db_data[$channel->name]['l3'][$i]) ? $db_data[$channel->name]['l3'][$i] :0;
                            } else {
                                $data[$user->username]['l3'][$i] =
                                    isset($db_data[$channel->name]['l3'][$i]) ? $db_data[$channel->name]['l3'][$i] :0;
                            }

                            if(isset($data[$user->username]['channels'][$channel->name]['l3'][$i])) {
                                $data[$user->username]['channels'][$channel->name]['l3'][$i] +=
                                    isset($db_data[$channel->name]['l3'][$i]) ? $db_data[$channel->name]['l3'][$i] :0;
                            } else {
                                $data[$user->username]['channels'][$channel->name]['l3'][$i] =
                                    isset($db_data[$channel->name]['l3'][$i]) ? $db_data[$channel->name]['l3'][$i] :0;
                            }

                            if(isset($data['total']['l3'][$i])) {
                                $data['total']['l3'][$i] +=
                                    isset($db_data[$channel->name]['l3'][$i]) ? $db_data[$channel->name]['l3'][$i] :0;
                            } else {
                                $data['total']['l3'][$i] =
                                    isset($db_data[$channel->name]['l3'][$i]) ? $db_data[$channel->name]['l3'][$i] :0;
                            }

                            if(isset($data[$user->username]['c3bg'][$i])) {
                                $data[$user->username]['c3bg'][$i] +=
                                    isset($db_data[$channel->name]['c3bg'][$i]) ? $db_data[$channel->name]['c3bg'][$i] : 0;
                            } else {
                                $data[$user->username]['c3bg'][$i] =
                                    isset($db_data[$channel->name]['c3bg'][$i]) ? $db_data[$channel->name]['c3bg'][$i] : 0;
                            }

                            if(isset($data[$user->username]['channels'][$channel->name]['c3bg'][$i])) {
                                $data[$user->username]['channels'][$channel->name]['c3bg'][$i] +=
                                    isset($db_data[$channel->name]['c3bg'][$i]) ? $db_data[$channel->name]['c3bg'][$i] :0;
                            } else {
                                $data[$user->username]['channels'][$channel->name]['c3bg'][$i] =
                                    isset($db_data[$channel->name]['c3bg'][$i]) ? $db_data[$channel->name]['c3bg'][$i] :0;
                            }

                            if(isset($data['total']['c3bg'][$i])) {
                                $data['total']['c3bg'][$i] +=
                                    isset($db_data[$channel->name]['c3bg'][$i]) ? $db_data[$channel->name]['c3bg'][$i] :0;
                            } else {
                                $data['total']['c3bg'][$i] =
                                    isset($db_data[$channel->name]['c3bg'][$i]) ? $db_data[$channel->name]['c3bg'][$i] :0;
                            }
                            break;
                        case "c3b":
                        default:
                            if(isset($data[$user->username]['c3b'][$i])) {
                                $data[$user->username]['c3b'][$i] +=
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] : 0;
                            } else {
                                $data[$user->username]['c3b'][$i] =
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] : 0;
                            }

                            if(isset($data[$user->username]['channels'][$channel->name]['c3b'][$i])) {
                                $data[$user->username]['channels'][$channel->name]['c3b'][$i] +=
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            } else {
                                $data[$user->username]['channels'][$channel->name]['c3b'][$i] =
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            }

                            if(isset($data['total']['c3b'][$i])) {
                                $data['total']['c3b'][$i] +=
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            } else {
                                $data['total']['c3b'][$i] =
                                    isset($db_data[$channel->name]['c3b'][$i]) ? $db_data[$channel->name]['c3b'][$i] :0;
                            }
                            break;
                    }
                }

                // day actual each channel
                $actual_channel = array();
                for($i = 1; $i <= $days; $i++) {
                    switch ($kpi_selection) {
                        case "c3b_cost":
                            $actual_channel[$i] = $data[$user->username]['channels'][$channel->name]['c3b'][$i] > 0 ?
                                round($data[$user->username]['channels'][$channel->name]['spent'][$i] /
                                    $data[$user->username]['channels'][$channel->name]['c3b'][$i],2) : 0;
                            break;
                        case "l3_c3bg":
                            $actual_channel[$i] = $data[$user->username]['channels'][$channel->name]['c3bg'][$i] > 0 ?
                                round($data[$user->username]['channels'][$channel->name]['l3'][$i]*100 /
                                    $data[$user->username]['channels'][$channel->name]['c3bg'][$i],2) : 0;
                            break;
                        case "c3b":
                        default:
                        $actual_channel[$i] = $data[$user->username]['channels'][$channel->name]['c3b'][$i];
                            break;
                    }
                }

                // total kpi, actual each channel
                switch ($kpi_selection) {
                    case "c3b_cost":
                        $kpi_channel = isset($userKpi->kpi_cost[$year][$month]) ? $userKpi->kpi_cost[$year][$month] : array();
                        $data[$user->username]['channels'][$channel->name]['total_kpi'] = round(array_sum($kpi)/$days,2);
                        $data[$user->username]['channels'][$channel->name]['total_actual'] =
                            array_sum($data[$user->username]['channels'][$channel->name]['c3b']) > 0 ?
                            round(array_sum($data[$user->username]['channels'][$channel->name]['spent']) /
                                array_sum($data[$user->username]['channels'][$channel->name]['c3b']),2) : 0;
                        break;
                    case "l3_c3bg":
                        $kpi_channel = isset($userKpi->kpi_l3_c3bg[$year][$month]) ? $userKpi->kpi_l3_c3bg[$year][$month] : array();
                        $data[$user->username]['channels'][$channel->name]['total_kpi'] = round(array_sum($kpi)*100/$days,2);
                        $data[$user->username]['channels'][$channel->name]['total_actual'] =
                            array_sum($data[$user->username]['channels'][$channel->name]['c3bg']) > 0 ?
                            round(array_sum($data[$user->username]['channels'][$channel->name]['l3'])*100 /
                                array_sum($data[$user->username]['channels'][$channel->name]['c3bg']),2) :0;
                        break;
                    case "c3b":
                    default:
                        $kpi_channel = isset($userKpi->kpi[$year][$month]) ? $userKpi->kpi[$year][$month] : array();
                        $data[$user->username]['channels'][$channel->name]['total_kpi'] = array_sum($kpi);
                        $data[$user->username]['channels'][$channel->name]['total_actual'] = array_sum($actual_channel);
                        break;
                }

                if($kpi_selection == "l3_c3bg"){
                    for($i = 1; $i <= $days; $i++) {
                        $data[$user->username]['channels'][$channel->name]['kpi'][$i] =
                             isset($kpi_channel[$i]) ? $kpi_channel[$i] * 100 : 0;
                    }
                } else {
                    $data[$user->username]['channels'][$channel->name]['kpi'] = $kpi_channel;
                }
                $data[$user->username]['channels'][$channel->name]['actual'] = $actual_channel;

                $data[$user->username]['count']++;
                $data['total']['count']++;
            }

            if (!$userKpis->isEmpty()) {
                // day kpi, actual each user
                for($i = 1; $i <= $days; $i++) {
                    switch ($kpi_selection) {
                        case "c3b_cost":
                            $data[$user->username]['kpi'][$i] = $data[$user->username]['count'] > 0 ?
                                round($data[$user->username]['kpi'][$i]/$data[$user->username]['count'],2) : 0;
                            $data[$user->username]['actual'][$i] = $data[$user->username]['c3b'][$i] > 0 ?
                                round($data[$user->username]['spent'][$i]/$data[$user->username]['c3b'][$i],2) : 0;
                            break;
                        case "l3_c3bg":
                            $data[$user->username]['kpi'][$i] = $data[$user->username]['count'] > 0 ?
                                round($data[$user->username]['kpi'][$i]*100/$data[$user->username]['count'],2) : 0;

                            $data[$user->username]['actual'][$i] = $data[$user->username]['c3bg'][$i] > 0 ?
                                round($data[$user->username]['l3'][$i]*100/$data[$user->username]['c3bg'][$i],2) : 0;
                            break;
                        case "c3b":
                        default:
                            $data[$user->username]['actual'][$i] = $data[$user->username]['c3b'][$i];
                            break;
                    }
                }

                // total kpi, actual each user
                switch ($kpi_selection){
                    case "c3b_cost":
                        $data[$user->username]['total_kpi'] = round(array_sum($data[$user->username]['kpi'])/$days, 2);
                        $data[$user->username]['total_actual'] =
                            array_sum($data[$user->username]['c3b']) > 0 ?
                                round(array_sum($data[$user->username]['spent']) / (array_sum($data[$user->username]['c3b'])), 2) : 0;
                        break;
                    case "l3_c3bg":
                        $data[$user->username]['total_kpi'] = round(array_sum($data[$user->username]['kpi'])/$days,2);
                        $data[$user->username]['total_actual'] =
                            array_sum($data[$user->username]['c3bg']) > 0 ?
                                round(array_sum($data[$user->username]['l3'])*100 / (array_sum($data[$user->username]['c3bg'])),2) : 0;
                        break;
                    case "c3b":
                    default:
                        $data[$user->username]['total_kpi'] = array_sum($data[$user->username]['kpi']);
                        $data[$user->username]['total_actual'] = array_sum($data[$user->username]['actual']);
                        break;
                }
            }

            $team_name = $this->get_team($userId);
            $data[$user->username]['team'] = $team_name;
        }

        if (isset($data['total']['kpi'])){
            // total kpi, actual each day
            for ($i = 1; $i <= $days; $i++) {
                switch ($kpi_selection) {
                    case "c3b_cost":
                        $data['total']['kpi'][$i] = @$data['total']['count'] > 0 ?
                            round(@$data['total']['kpi'][$i]/@$data['total']['count'],2) : 0 ;
                        $data['total']['actual'][$i] = @$data['total']['c3b'][$i] > 0 ?
                            round(@$data['total']['spent'][$i]/@$data['total']['c3b'][$i],2) : 0;
                        break;
                    case "l3_c3bg":
                        $data['total']['kpi'][$i] = @$data['total']['count'] > 0 ?
                            round(@$data['total']['kpi'][$i]*100/@$data['total']['count'],2) : 0 ;
                        $data['total']['actual'][$i] = $data['total']['c3bg'][$i] > 0 ?
                            round(@$data['total']['l3'][$i]*100/@$data['total']['c3bg'][$i],2) : 0;
                        break;
                    case "c3b":
                    default:
                        $data['total']['actual'][$i] = @$data['total']['c3b'][$i];
                        break;
                }
            }

            // total kpi, actual
            switch ($kpi_selection) {
                case "c3b_cost":
                    $data['total']['total_kpi'] = @$data['total']['count'] > 0 ?
                        round(array_sum(@$data['total']['kpi'])/$days,2) : 0;
                    $data['total']['total_actual'] = @$data['total']['count'] > 0 ? array_sum(@$data['total']['c3b']) > 0 ?
                        round(array_sum(@$data['total']['spent'])/array_sum(@$data['total']['c3b']),2) : 0 : 0;
                    break;
                case "l3_c3bg":
                    $data['total']['total_kpi'] = @$data['total']['count'] > 0 ?
                        round(array_sum(@$data['total']['kpi'])/$days,2) : 0;
                    $data['total']['total_actual'] = @$data['total']['count'] > 0 ? array_sum(@$data['total']['c3bg']) > 0 ?
                        round(array_sum(@$data['total']['l3'])*100/array_sum(@$data['total']['c3bg']),2) : 0 : 0;
                    break;
                case "c3b":
                default:
                    $data['total']['total_kpi'] = array_sum(@$data['total']['kpi']);
                    $data['total']['total_actual'] = array_sum(@$data['total']['c3b']);
                    break;
            }
        }
        return $data;
    }

    function get_days_in_month(){
        $request = request();
        $year = date('Y');
        $month = date('m');
        if($request->month){
            $month = $request->month;
        }
        $d = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        return $d;

    }

    // get data c3b, c3bg, spent, l3 follow user, channel
    function get_db_data($userId){
        $request = request();
        $month = date('m'); /* thang hien tai */

        if($request->month){
            $month = $request->month;
        }

        $first_day_this_month = date('Y-'.$month.'-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month = date('Y-'.$month.'-t'); /* ngày cuối cùng của tháng */

        $channels = $this->get_channel_user($userId);

        $data = array();
        foreach ($channels as $channel) {
            $ads = $this->get_ad_ids_channel($channel->_id);

            $adResults = AdResult::where('creator_id', $userId)
                ->whereIn('ad_id', $ads)
                ->whereBetween('date', [$first_day_this_month, $last_day_this_month])
                ->get();

            foreach ($adResults as $adResult){
                $date = explode("-", $adResult->date);
                $day = intval($date[2]);
                $spent = $adResult->spent;
                $c3b = $adResult->c3b + $adResult->c3bg;
                $c3bg = $adResult->c3bg;
                $l3 = $adResult->l3;

                $data[$channel->name]['c3b'][$day] =
                    isset($data[$channel->name]['c3b'][$day]) ? ($data[$channel->name]['c3b'][$day] + $c3b) : $c3b;
                $data[$channel->name]['c3bg'][$day] =
                    isset($data[$channel->name]['c3bg'][$day]) ? ($data[$channel->name]['c3bg'][$day] + $c3bg) : $c3bg;
                $data[$channel->name]['l3'][$day] =
                    isset($data[$channel->name]['l3'][$day]) ? ($data[$channel->name]['l3'][$day] + $l3) : $l3;
                $data[$channel->name]['spent'][$day] =
                    isset($data[$channel->name]['spent'][$day]) ? ($data[$channel->name]['spent'][$day] + $spent) : $spent;
            }
        }
        return $data;
    }

    public function get_ad_ids_channel($channel_id) {
        $ads = Ad::where('channel_id', $channel_id)
            ->get();

        $ad_ids = array();
        foreach ($ads as $ad) {
            array_push($ad_ids, $ad->_id);
        }

        return $ad_ids;
    }

    public function kpi_by_maketer(){
        $request = request();

        $result = $this->get_data();
        $data_maketer   = $this->get_data_by_maketer($result);
        $days   = $this->get_days_in_month();
        $month  = date('M');
        $year   = date('Y');

        if($request->month){
            $month  = date('M', strtotime($year.'-'.$request->month));
        }

        if($request->maketer){
            $arr_maketer = explode(',',$request->maketer);

            if(isset($data_maketer["total"])) {
                unset($data_maketer["total"]);
            }
            
            foreach ($data_maketer as $key => $item ){
                if(isset($item['user_id'])) {
                    if(!in_array($item['user_id'], $arr_maketer)){
                        unset($data_maketer[$key]);
                    }
                }
            }
        }

        $kpi_selection = $request->kpi_selection;

        return view('pages.table_report_kpi', compact(
            'kpi_selection',
            'data_maketer',
            'days',
            'month',
            'year'
        ));
    }

    public function kpi_by_team(){
        $request    = request();

        $month  = date('M');
        $year   = date('Y');

        if($request->month){
            $month  = date('M', strtotime($year.'-'.$request->month));
        }

        $days       = $this->get_days_in_month();
        $result     = $this->get_data();
        $data_team  = $this->get_data_by_team($result, $days);

        if($request->team){
            $arr_team = explode(',',$request->team);
            foreach ($data_team as $key => $item){
                if(!in_array($key, $arr_team)){
                    unset($data_team[$key]);
                }
            }
        }

        $kpi_selection = $request->kpi_selection;

        return view('pages.table_report_kpi_by_team', compact(
            'kpi_selection',
            'data_team',
            'days',
            'month',
            'year'
        ));
    }

    public function get_data_by_team($data, $day){
        $res = [];
        $all_team = Team::all();
        foreach ($data as $item){
            if (!isset($item['team'])) continue;

            $team = $item['team'];
            if(!$team){
                continue;
            }

            if (!isset($res[$team])) {
                $res[$team]['kpi']        = @$item['kpi'];
                $res[$team]['actual']     = @$item['actual'];
                $res[$team]['total_kpi']  = @$item['total_kpi'];
                $res[$team]['total_actual']  = @$item['total_actual'];
            } else {
                $cnt = $day;
                for($i = 1; $i <= $cnt; $i++){
                    @$res[$team]['kpi'][$i] += @$item['kpi'][$i];
                    @$res[$team]['actual'][$i] += @$item['actual'][$i];
                }
                @$res[$team]['total_kpi']  += @$item['total_kpi'];
                @$res[$team]['total_actual']  += @$item['total_actual'];
            }
        }

        foreach ($all_team as $item){
            $team = $item['name'];
            if(!isset($res[$team])){
                $cnt = $day;
                for($i = 1; $i <= $cnt; $i++){
                    @$res[$team]['kpi'][$i] += 0;
                    @$res[$team]['actual'][$i] += 0;
                }
                @$res[$team]['total_kpi']  = 0;
                @$res[$team]['total_actual']  = 0;
            }
        }

        uasort($res, function ($item1, $item2) {
            if ($item1['total_actual'] == $item2['total_actual']) return 0;
            return $item2['total_actual'] < $item1['total_actual'] ? -1 : 1;
        });
        return $res;
    }

    private function get_team($user_id){
        $team = Team::where('members.'.$user_id.'.user_id', $user_id)
                    ->first();
        return isset($team->name) ? $team->name : "";
    }

}
