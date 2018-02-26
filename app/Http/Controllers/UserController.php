<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Contact;
use App\Permission;
use App\Role;
use App\User;
use DB;
use App\AdResult;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // if (!\Entrust::can('view-user')) return view('errors.403');
        $page_title = "Users List | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = 'users';
        $breadcrumbs = "<i class=\"fa-fw fa fa-user\"></i> Users <span>> Users List </span>";

        $users = User::all();

        return view('pages.users', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'users'
        ));
    }

    public function create()
    {
        if(auth()->user()->role !== "Manager") return view('errors.403');
        $page_title = "Create User | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = 'users-create';
        $breadcrumbs = "<i class=\"fa-fw fa fa-user\"></i>Users <span>> Create User </span>";

        // $roles = Role::all();

        return view('pages.user-create', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'roles'
        ));
    }

    public function edit($id)
    {
        if(auth()->user()->role !== "Manager") return view('errors.403');
        $page_title = "Edit User | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = 'users';
        $breadcrumbs = "<i class=\"fa-fw fa fa-user\"></i>Users<span>> Edit User </span>";

        $user = User::find($id);
        // $roles = Role::all();

        return view('pages.user-edit', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'user',
            'roles'
        ));
    }

    public function store()
    {
        if(auth()->user()->role !== "Manager") return view('errors.403');
        $validator = [
            'username' => 'required|alpha_dash|unique:users',
            'email' => 'required|email|max:255|unique:users'
        ];

        if (request('password') || !request('id')) {
            $validator['password'] = 'required|min:6|confirmed';
        }

        $user = !request('id') ? new User() : User::find(request('id'));

        if(request('id')){
            if(request('username') == $user->username) $validator['username'] = 'required|alpha_dash';
            if(request('email') == $user->email) $validator['email'] = 'required|email|max:255';
        }

        $this->validate(request(), $validator);

        // $user = new User();
        $user->username = request('username');
        $user->email = request('email');
        $user->rank = request('rank');
        $user->role = request('role');
        $user->is_active = (int)request('is_active');

        if (request('password') || !request('id'))
            $user->password = bcrypt(request('password'));
        $user->save();

        // $user->detachRoles();
        // $user->attachRole(\request('role_id'));

        if (!request('id'))
            session()->flash('message', 'User has been created successfully');
        else
            session()->flash('message', 'User has been edited successfully');

        return redirect()->route('users');
    }

    public function profile($username = null)
    {
        $page_title = "User Profile | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = null;

        $user = $username ? User::where('username', $username)->firstOrFail() : auth()->user();
        $breadcrumbs = "<i class=\"fa-fw fa fa-user\"></i> " . $user->username . "'s Profile";

        /* format date */
        $month = date('m');
        $year = date('Y');
        $d=cal_days_in_month(CAL_GREGORIAN,$month,$year); /* số ngày trong tháng */
        $first_day_this_month = date('Y-m-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month  = date('Y-m-t'); /* ngày cuối cùng của tháng */
        // $currentday = date('Y-m-d'); /* ngày hiện tại của tháng */

        // DB::connection( 'mongodb' )->enableQueryLog();
        $query = AdResult::raw(function($collection) use ($first_day_this_month,$last_day_this_month,$user) {
            return $collection->aggregate([
                ['$match' => [
                	'date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month],
                	'creator_id'  => $user->_id
            	]],
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
        // DB::connection('mongodb')->getQueryLog();

        $array_month = array();

        for($i=1;$i<=$d; $i++){
            $timestamp = strtotime(date("Y")."-".date("m")."-".$i) * 1000;
            //dd($timestamp);
            $array_month[$i] = $timestamp;
        }

        $c3_array = array();
        $l8_array = array();

        foreach ($query as $item_result){
            $day = explode('-',$item_result['_id']);
            $c3_array[(int)($day[2])] = $item_result['c3'];
            $l8_array[(int)($day[2])] = $item_result['l8'];
        }

        /*  lay du lieu c3*/
        $chart_c3 = array();
        foreach($array_month as $key =>  $timestamp){
            if(isset($c3_array[$key])){
                $chart_c3[] = [$timestamp, $c3_array[$key]];
            }else{
                $chart_c3[] = [$timestamp, 0];
            }
        }
        $chart_c3 = json_encode($chart_c3);
        /* end c3 */

        /* lay du lieu l8*/
        $chart_l8 = array();
        foreach($array_month as $key =>  $timestamp){
            if(isset($l8_array[$key])){
                $chart_l8[] = [$timestamp, $l8_array[$key]];
            }else{
                $chart_l8[] = [$timestamp, 0];
            }
        }
        $chart_l8 = json_encode($chart_l8);
        /* end l8 */

        /*  lấy tổng số c3 và số l8*/
        $profile['total_c3'] = AdResult::where('creator_id', $user->id)->sum('c3');
        $profile['total_revenue'] = AdResult::where('creator_id', $user->id)->sum('revenue');
        $profile['total_l8'] = AdResult::where('creator_id', $user->id)->sum('l8');

        $profile['chart_c3'] = $chart_c3;
        $profile['chart_l8'] = $chart_l8;
        return view('pages.user-profile', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'user',
            'profile'
        ));
    }
}
