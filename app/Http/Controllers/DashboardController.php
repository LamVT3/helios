<?php

namespace App\Http\Controllers;

use App\ActivityBooking;
use App\Admin_account;
use App\AdResult;
use App\CarBooking;
use App\Customer;
use App\CustomerActivity;
use App\Dm_contact;
use App\HotelBooking;
use App\TourBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ad;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*  phan date*/
        $month = date('m'); /* thang hien tai */
        $year = date('Y'); /* nam hien tai*/
        $curentday = date('Y-m-d'); /* ngày hiện tại của tháng */
        $d = cal_days_in_month(CAL_GREGORIAN,$month,$year); /* số ngày trong tháng */
        $first_day_of_week = date('Y-m-d', strtotime('Last Monday', time())); /* ngay dau tien cua tuan */
//        dd($first_day_of_week);
        $last_day_of_week = date('y-m-d', strtotime('Next Sunday', time())); /* ngay cuoi cung cua tuan */
        $first_day_this_month = date('Y-m-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month  = date('Y-m-t'); /* ngày cuối cùng của tháng */

        /* end date */
        /* phan dashboard*/
        $input = $request->all();
        $startDate = $request->startDate ? date('Y-m-d',strtotime($request->startDate)): Date('Y-m-d');
        $endDate = $request->endDate ? date('Y-m-d',strtotime($request->endDate)) : Date('Y-m-d');

//        dd($startDate);
        $query_dashboard = DB::table('ad_results')
            ->select('c3')
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate);
            $dashboard['c3'] = $query_dashboard->sum('c3');
            $dashboard['c3_cost'] = $query_dashboard->sum('c3_cost');
            $dashboard['spent'] = $query_dashboard->sum('spent');
            $dashboard['revenue'] = $query_dashboard->sum('revenue');
            /* end Dashboard */

            /*  start Chart*/
            $query_chart = AdResult::raw(function($collection) use ($first_day_this_month,$last_day_this_month) {
                return $collection->aggregate([
                    ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
                    [
                        '$group' => [
                            '_id' => '$date',
                            'c3' => [
                                '$sum' => '$c3'
                            ],
                            'l8'=>[
                                '$sum' => '$l8'
                            ]
                        ]
                    ]
                ]);
            });

            $array_month = array();
            for($i=1;$i<=$d; $i++){
                $array_month[date($i)] = 0;
            }
            /*  lay du lieu c3*/
            $c3_array = array();
            foreach ($query_chart as $item_result_c3){
                $day = explode('-',$item_result_c3['_id']);
                $c3_array[intval($day[2])] = $item_result_c3['c3'];
            }
            $chart_c3 = array();
            foreach($array_month as $key =>  $c3_day){
                if(isset($c3_array[$key])){
                    $chart_c3[$key] = $c3_array[$key];
                }else{
                    $chart_c3[$key] = 0;
                }
            }
            /* end c3 */
            /* lay du lieu l8*/
            $l8_array = array();
            foreach ($query_chart as $item_result_l8){
                $day = explode('-',$item_result_l8['_id']);
                $l8_array[intval($day[2])] = $item_result_l8['l8'];
            }
            $chart_l8 = array();
            foreach($array_month as $key =>  $l8_day){
                if(isset($l8_array[$key])){
                    $chart_l8[$key] = $l8_array[$key];
                }else{
                    $chart_l8[$key] = 0;
                }
            }
            /* end l8 */
            $dashboard['chart_c3'] = $chart_c3;
            $dashboard['chart_l8'] = $chart_l8;
//            dd($dashboard['chart_c3']);
            /* end Chart */
            /* start Leaderboard */
            /*-- query ------------------- */

            /* query lấy ra tất cả các ads */
            $query_ads = Ad::raw(function($collection)  {
                return $collection->aggregate([
                    [
                        '$lookup' => [
                            'as'=>'field_users',
                            'from'=>'users',
                            'foreignField'=>'_id',
                            'localField'=>'creator_id'
                        ]
                    ],
                    [
                        '$project' => [
                            'ad_id'=>'$ad_id',
                            'creator_id'=>'$creator_id',
                            'user_name'=>'$field_users.username',
                            'rank'=>'$field_users.rank'
                        ]
                    ]
                ]);
            });
            /* end query ads */

            /* gom tất cả các ads vào cùng một creator_id */
            $ads_array = array();
            foreach ($query_ads as  $value_ads){
                if(!isset($ads_array[$value_ads['creator_id']])){
                    $ads_array[$value_ads['creator_id']] = [];
                }
                $ads_array[$value_ads['creator_id']][] = $value_ads;
            }
            /* end gom ads */
            /* ------------------------TODAY-------------------------------- */
            /* lấy ra dữ c3,revenue  liệu bảng ad_result theo ngày today */
            $query_adresult_today = DB::table('ad_results')
                ->select('ad_id','c3','revenue')
                ->where('date', '>=', $curentday)
                ->where('date', '<=', $curentday)
                ->get();
            /* end query */
            /* gom tất cả c3 của cac ads */
            $adresult_today = array();
            foreach ($query_adresult_today as $value_adresult_today){
                if(!isset($adresult_today[$value_adresult_today['ad_id']])){
                    $adresult_today[$value_adresult_today['ad_id']] = 0;
                }
                if(isset($value_adresult_today['c3'])) {
                    $adresult_today[$value_adresult_today['ad_id']] += $value_adresult_today['c3'];
                }
            }
//            dd($adresult_today);
            /* end gom ads */
            /* gộp mảng adresult và mảng ads theo ad_id  */
            $data_c3_today = array();
            foreach ($ads_array as $key => $value_ads_array){
              foreach ($value_ads_array as $item_ads_aray){
                  if(!isset($data_c3_today[$key])){
                      $data_c3_today[$key] = [
                          'sum_c3' => 0,
                          'user_name' => '',
                          'rank'=>''
                      ];
                  }

                  //dd($item_ads_aray);
                  if(isset($adresult_today[$item_ads_aray['_id']])){
                      $data_c3_today[$key]['sum_c3'] += $adresult_today[$item_ads_aray['_id']];
                      if(empty($data_c3_today[$key]['user_name'])){
                          $data_c3_today[$key]['user_name'] = $item_ads_aray['user_name'];
                          $data_c3_today[$key]['rank'] = $item_ads_aray['rank'];
                      }

                  }

                }
            }
            /* -----------------------------ENDTODAY---------------------------------------------------- */
            /* end gộp mảng */
        /* ------------------------THISWEEK-------------------------------- */
                /* lấy ra dữ c3,revenue  liệu bảng ad_result theo ngày today */
//                dd($first_day_of_week);
//                dd($last_day_of_week);
                $query_adresult_thisweek = DB::table('ad_results')
                    ->select('ad_id','c3')
                    ->where('date', '>=', $first_day_of_week)
                    ->where('date', '<=', $last_day_of_week)
                    ->get();

                /* end query */
                /* gom tất cả c3 của cac ads */
                $adresult_today = array();
                foreach ($query_adresult_today as $value_adresult_today){
                    if(!isset($adresult_today[$value_adresult_today['ad_id']])){
                        $adresult_today[$value_adresult_today['ad_id']] = 0;
                    }
                    if(isset($value_adresult_today['c3'])) {
                        $adresult_today[$value_adresult_today['ad_id']] += $value_adresult_today['c3'];
                    }

                }
        //            dd($adresult_today);
                /* end gom ads */
                /* gộp mảng adresult và mảng ads theo ad_id  */
                $data_c3_today = array();
                foreach ($ads_array as $key => $value_ads_array){
                    foreach ($value_ads_array as $item_ads_aray){
                        if(!isset($data_c3_today[$key])){
                            $data_c3_today[$key] = [
                                'sum_c3' => 0,
                                'user_name' => '',
                                'rank'=>''
                            ];
                        }
                        if(isset($adresult_today[$item_ads_aray['_id']])){
                            $data_c3_today[$key]['sum_c3'] += $adresult_today[$item_ads_aray['_id']];
                            if(empty($data_c3_today[$key]['user_name'])){
                                $data_c3_today[$key]['user_name'] = $item_ads_aray['user_name'];
                                $data_c3_today[$key]['rank'] = $item_ads_aray['rank'];
                            }

                        }

                    }
                }
                /* -----------------------------ENDTHISWEEK---------------------------------------------------- */

//            die();

//            dd($query_today);
//       DB::connection( 'mongodb' )->enableQueryLog();
//       die();

//dd($query_today);
            /*-- end query data today */
            /*-- query lấy ra data this week */
//        $query = AdResult::raw(function($collection) use ($first_day_this_month,$last_day_this_month) {
//            return $collection->aggregate([
//                [
//                    '$lookup' => [
//                        'as'=>'field_ads',
//                        'from'=>'ads',
//                        'foreignField'=>'ad_id',
//                        'localField'=>'_id'
//                    ]
//                ],
//                [
//                    '$lookup' => [
//                        'as'=>'field_user',
//                        'from'=>'users',
//                        'foreignField'=>'_id',
//                        'localField'=>'field_ads.creator_id'
//                    ]
//                ],
//                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
//                [
//                    '$group' => [
//                        '_id' => '$field_ads.creator_id',
//                        'c3' => [
//                            '$sum' => '$c3'
//                        ],
//                        'l8'=>[
//                            '$sum' => '$l8'
//                        ]
//                    ]
//                ]
//            ]);
//        });
//        dd($query);
//        echo '<pre>';
//        print_r($query_thisweek);
//        die();
            /*-- end query data this week */
            /*-- query lấy ra data this month */
//        $query_thismonth = AdResult::raw(function($collection) use ($first_day_this_month,$last_day_this_month) {
//            return $collection->aggregate([
//                [
//                    '$lookup' => [
//                        'as'=>'field_ads',
//                        'from'=>'ads',
//                        'foreignField'=>'ad_id',
//                        'localField'=>'_id'
//                    ]
//                ],
//                [
//                    '$lookup' => [
//                        'as'=>'field_user',
//                        'from'=>'users',
//                        'foreignField'=>'_id',
//                        'localField'=>'field_ads.creator_id'
//                    ]
//                ],
//                ['$match' => ['date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month]]],
//                [
//                    '$group' => [
//                        '_id' => '$date',
//                        'c3' => [
//                            '$sum' => '$c3'
//                        ],
//                        'l8'=>[
//                            '$sum' => '$l8'
//                        ]
//                    ]
//                ]
//            ]);
//        });
            /*-- end query data this month */
            /* end Leaderboard */
//        dd($revenue);
        $page_title = "Dashboard | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'dashboard';
        $breadcrumbs = "<i class=\"fa-fw fa fa-home\"></i> Dashboard";

        //$user = Admin_account::all();
        //$contacts = Dm_contact::where('link_cv', null)->orderBy('id', 'desc')->limit(5)->get();
        //debug($contacts);
        $ad_results = AdResult::where("date", Carbon::yesterday()->toDateString());

        return view('pages.dashboard', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'ad_results',
            'dashboard'
        ));
    }
}
