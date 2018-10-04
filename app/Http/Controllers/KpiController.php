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
            if ($item1['total_actual'] == $item2['total_actual']) return 0;
            return $item2['total_actual'] < $item1['total_actual'] ? -1 : 1;
        });
        return $data;
    }

    public function save_kpi(){

        $request   = request();
        $userId    = $request->user_id;
        $channelId = $request->channel_id;
        $month     = $request->month;
        $year      = $request->year;
        $userKpi   = UserKpi::firstOrNew(['user_id' => $userId, 'channel_id'=> $channelId]); // find first or create new row

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

        // only get for marketers was activated
        $users = User::getMarketerActive();

        $data = array();
        foreach ($users as $user) {
            $userId = $user->id;
            $data[$user->username]['user_id'] = $userId;

            $days = $this->get_days_in_month();
            $data[$user->username]['kpi'] = array();
            $data[$user->username]['actual'] = array();
            $data[$user->username]['total_kpi'] = 0;
            $data[$user->username]['total_actual'] = 0;

            $channels = $this->get_channel_user($userId);
            $db_data = $this->get_db_data($userId);

            foreach ($channels as $channel) {
                $userKpi = UserKpi::getKpiTwoParam($user->_id, $channel->_id);

                switch ($request->kpi_selection) {
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
                $data[$user->username]['channels'][$channel->name]['kpi'] = $kpi;
                $actual = isset($db_data[$channel->name][$request->kpi_selection]) ?
                    $db_data[$channel->name][$request->kpi_selection] : array();
                $data[$user->username]['channels'][$channel->name]['actual'] = $actual;

                switch ($request->kpi_selection) {
                    case "c3b_cost":
                    case "l3_c3bg":
                        $data[$user->username]['channels'][$channel->name]['total_kpi'] = round(array_sum($kpi)/$days, 2);
                        $data[$user->username]['channels'][$channel->name]['total_actual'] = round(array_sum($actual)/$days, 2);
                        break;
                    case "c3b":
                    default:
                        $data[$user->username]['channels'][$channel->name]['total_kpi'] = array_sum($kpi);
                        $data[$user->username]['channels'][$channel->name]['total_actual'] = array_sum($actual);
                        break;
                }

                for($i = 1; $i <= $days; $i++) {
                    if(isset($data[$user->username]['kpi'][$i])) {
                        if(isset($kpi[$i])) {
                            $data[$user->username]['kpi'][$i] = $data[$user->username]['kpi'][$i] + $kpi[$i];
                        }
                    } else if(isset($kpi[$i])) {
                        $data[$user->username]['kpi'][$i] = $kpi[$i];
                    }

                    if(isset($data[$user->username]['actual'][$i])) {
                        if(isset($actual[$i])) {
                            $data[$user->username]['actual'][$i] = $data[$user->username]['actual'][$i] + $actual[$i];
                        }
                    } else if(isset($actual[$i])) {
                        $data[$user->username]['actual'][$i] = $actual[$i];
                    }
                }
                $data[$user->username]['total_kpi'] += array_sum($kpi);
                $data[$user->username]['total_actual'] += array_sum($actual);
            }
            switch ($request->kpi_selection) {
                case "c3b_cost":
                case "l3_c3bg":
                    $data[$user->username]['total_kpi'] = round($data[$user->username]['total_kpi']/$days, 2);
                    $data[$user->username]['total_actual'] = round($data[$user->username]['total_actual']/$days, 2);
                    break;
                case "c3b":
                default:
                    break;
            }

            $team_name = $this->get_team($userId);
            $data[$user->username]['team'] = $team_name;
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

    function get_db_data($userId){
        $month = date('m'); /* thang hien tai */
        $request = request();
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
                $day = explode("-", $adResult->date);
                $key = intval($day[2]);
                $spent = $adResult->spent;
                $c3b = $adResult->c3b + $adResult->c3bg;
                $c3bg = $adResult->c3bg;
                $l3 = $adResult->l3;

                $data[$channel->name]['c3b'][$key] =
                    isset($data[$channel->name]['c3b'][$key]) ? ($data[$channel->name]['c3b'][$key] + $c3b) : $c3b;
                $c3b_cost = $c3b != 0 ? round($spent/$c3b,2) : 0;
                $data[$channel->name]['c3b_cost'][$key] =
                    isset($data[$channel->name]['c3b_cost'][$key]) ? ($data[$channel->name]['c3b_cost'][$key] + $c3b_cost) : $c3b_cost;
                $l3_c3bg = $c3bg != 0 ? round($l3/$c3bg,2) : 0;
                $data[$channel->name]['l3_c3bg'][$key] =
                    isset($data[$channel->name]['l3_c3bg'][$key]) ? ($data[$channel->name]['l3_c3bg'][$key] + $l3_c3bg) : $l3_c3bg;
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
            foreach ($data_maketer as $key => $item ){
                if(!in_array($item['user_id'], $arr_maketer)){
                    unset($data_maketer[$key]);
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
