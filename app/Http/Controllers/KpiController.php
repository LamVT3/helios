<?php

namespace App\Http\Controllers;

use App\Team;
use App\User;
use App\AdResult;
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
        $page_title     = " KPI Report | Helios";
        $page_css       = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active         = 'assign_kpi';
        $breadcrumbs    = "<i class=\"fa-fw fa fa-bar-chart-o\"></i> Report <span>> KPI Report </span>";

        $users  = User::all();
        $teams  = Team::all();
        $days   = $this->get_days_in_month();
        $month  = date('M');
        $year   = date('Y');
        $result = $this->get_data();

        $kpi_selection  = "c3b";
        $data_maketer   = $this->get_data_by_maketer($result);
        $data_team      = $this->get_data_by_team($result, $days);

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

        $request    = request();
        $user       = $request->user_id;
        $month      = $request->month;
        $year       = $request->year;
        $user       = User::find($user);

        $kpi        = $user->kpi;
        $kpi[$year][$month] = $request->kpi;
        ksort( $kpi[$year]);
        $user->kpi  = $kpi;

        $kpi_cost   = $user->kpi_cost;
        $kpi_cost[$year][$month] = $request->kpi_cost;
        ksort( $kpi_cost[$year]);
        $user->kpi_cost  = $kpi_cost;

        $kpi_l3_c3bg = $user->kpi_l3_c3bg;
        $kpi_l3_c3bg[$year][$month] = $request->kpi_l3_c3bg;
        ksort( $kpi_l3_c3bg[$year]);
        $user->kpi_l3_c3bg  = $kpi_l3_c3bg;

        $user->save();
    }

    public function get_kpi(){

        $request    = request();
        $user       = $request->user_id;
        $month      = $request->month;
        $year       = $request->year;
        $user       = User::where('_id', $user)->firstOrFail();

        $kpi        = isset($user->kpi[$year][$month]) ? $user->kpi[$year][$month] : array();
        $kpi_cost   = isset($user->kpi_cost[$year][$month]) ? $user->kpi_cost[$year][$month] : array();
        $kpi_l3_c3bg = isset($user->kpi_l3_c3bg[$year][$month]) ? $user->kpi_l3_c3bg[$year][$month] : array();

        $data = array();
        $data['kpi'] = $kpi;
        $data['kpi_cost'] = $kpi_cost;
        $data['kpi_l3_c3bg'] = $kpi_l3_c3bg;

        return @$data;

    }

    public function get_data(){

        $request = request();
        /*  phan date*/
        $month = date('m'); /* thang hien tai */
        $year = date('Y'); /* nam hien tai*/

        if($request->month){
            $month = $request->month;
        }

        $users = User::all();
        foreach ($users as $user){

            $data[$user->username]['user_id']   = $user->id;
            switch ($request->kpi_selection) {
                case "c3b_cost":
                    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $kpi_cost = isset($user->kpi_cost[$year][$month]) ? $user->kpi_cost[$year][$month] : array();
                    $data[$user->username]['kpi']       = $kpi_cost;
                    $data[$user->username]['total_kpi'] = round(array_sum($kpi_cost)/$days, 2);

                    $db_data = $this->get_db_data($user);

                    $data[$user->username]['actual']       = isset($db_data['c3b_cost']) ? $db_data['c3b_cost'] : array();
                    $actual = isset($data[$user->username]['actual']) ? $data[$user->username]['actual'] : array();
                    $data[$user->username]['total_actual'] = round(array_sum($actual)/$days, 2);
                    break;
                case "l3_c3bg":
                    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $kpi_l3_c3bg = isset($user->kpi_l3_c3bg[$year][$month]) ? $user->kpi_l3_c3bg[$year][$month] : array();
                    $data[$user->username]['kpi']       = $kpi_l3_c3bg;
                    $data[$user->username]['total_kpi'] = round(array_sum($kpi_l3_c3bg)/$days, 2);

                    $db_data = $this->get_db_data($user);

                    $data[$user->username]['actual']       = isset($db_data['l3_c3bg']) ? $db_data['l3_c3bg'] : array() ;
                    $actual = isset($data[$user->username]['actual']) ? $data[$user->username]['actual'] : array();
                    $data[$user->username]['total_actual'] = round(array_sum($actual)/$days, 2);
                    break;
                case "c3b":
                default:
                    $kpi = isset($user->kpi[$year][$month]) ? $user->kpi[$year][$month] : array();
                    $data[$user->username]['kpi']       = $kpi;
                    $data[$user->username]['total_kpi'] = array_sum($kpi);

                    $data[$user->username]['actual']       = $this->get_c3b_data($user);
                    $actual = isset($data[$user->username]['actual']) ? $data[$user->username]['actual'] : array();
                    $data[$user->username]['total_actual'] = array_sum($actual);
                    break;
            }

            $team_name = $this->get_team($user->id);
            $data[$user->username]['team']   = $team_name;
        }
        return $data;

    }

    function get_days_in_month($month = null){
        $request = request();
        $year = date('Y');
        $month = date('m');
        if($request->month){
            $month = $request->month;
        }
        $d = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        return $d;

    }

    function get_c3b_data($user){
        /*  phan date*/
        $month = date('m'); /* thang hien tai */
        $year = date('Y'); /* nam hien tai*/
        $request = request();
        if($request->month){
            $month = $request->month;
        }

        $d = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $first_day_this_month = date('Y-'.$month.'-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month = date('Y-'.$month.'-t'); /* ngày cuối cùng của tháng */
        /* end date */
        $query = AdResult::raw(function($collection) use ($first_day_this_month,$last_day_this_month,$user) {
            return $collection->aggregate([
                ['$match' => [
                    'date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month],
                    'creator_id'  => $user->_id
                ]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3b' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                    ]
                ]
            ]);
        });
        $data = array();
        foreach ($query as $item){
            $day = explode("-",@$item['_id']);
            $key = intval($day[2]);
            $data[$key] = @$item['c3b'];
        }

        return $data;
    }

    function get_db_data($user){
        /*  phan date*/
        $month = date('m'); /* thang hien tai */
        $year = date('Y'); /* nam hien tai*/
        $request = request();
        if($request->month){
            $month = $request->month;
        }

        $d = cal_days_in_month(CAL_GREGORIAN, $month, $year); /* số ngày trong tháng */
        $first_day_this_month = date('Y-'.$month.'-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month = date('Y-'.$month.'-t'); /* ngày cuối cùng của tháng */
        /* end date */
        $query = AdResult::raw(function($collection) use ($first_day_this_month,$last_day_this_month,$user) {
            return $collection->aggregate([
                ['$match' => [
                    'date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month],
                    'creator_id'  => $user->_id
                ]],
                [
                    '$group' => [
                        '_id' => '$date',
                        'c3b' => ['$sum' => ['$sum' => ['$c3b', '$c3bg']]],
                        'c3bg' => ['$sum' => '$c3bg'],
                        'spent' => ['$sum' => '$spent'],
                        'l3' => ['$sum' => '$l3'],
                    ]
                ]
            ]);
        });
        $data = array();
        foreach ($query as $item){
            $day = explode("-",@$item['_id']);
            $key = intval($day[2]);
            $spent = @$item['spent'];
            $c3b = @$item['c3b'];
            $c3bg = @$item['c3bg'];
            $l3 = @$item['l3'];
            $data['c3b_cost'][$key] = $c3b != 0 ? round($spent/$c3b,2) : 0;
            $data['l3_c3bg'][$key] = $c3bg != 0 ? round($l3/$c3bg,2) : 0;
        }

        return $data;
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
        $query = Team::raw(function($collection) use ($user_id){
            $key = 'members.'.$user_id.'.user_id';
            return $collection->aggregate([
                ['$match' => [
                    $key => $user_id,
                ]],
                [
                    '$group' => [
                        '_id'  => '$name',
                    ]
                ]
            ]);
        });
        $team_name = '';
        if(count($query) == 1){
            foreach ($query as $item){
                $team_name  = $item->_id;
            }
        }
        return$team_name;
    }

}
