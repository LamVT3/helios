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
        // if (!\Entrust::can('edit-user')) return view('errors.403');
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

    /*public function store()
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = request('name');
        $user->email = request('email');
        $user->password = bcrypt(request('password'));
        $user->save();

        $user->attachRole(\request('role_id'));

        session()->flash('message', 'User được tạo thành công');

        return redirect()->route('users');
    }*/

    public function edit($id)
    {
        // if (!\Entrust::can('edit-user')) return view('errors.403');
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
        // if (!\Entrust::can('edit-user')) return view('errors.403');
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

    public function roles()
    {
        // if (!\Entrust::can('view-user')) return view('errors.403');
        $page_title = "User Roles List | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = 'users-roles';
        $breadcrumbs = "<i class=\"fa-fw fa fa-user\"></i> Users <span>> User Roles List </span>";

        $roles = Role::all();

        return view('pages.user-roles', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'roles'
        ));
    }

    public function roleCreate()
    {
        // if (!\Entrust::can('edit-user')) return view('errors.403');
        $page_title = "Create Role | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = 'users-roles';
        $breadcrumbs = "<i class=\"fa-fw fa fa-user\"></i>Users <span>> Create Role </span>";

        $perms = Permission::where('name', '!=', 'access-admin')->get();
        return view('pages.user-role-edit', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'perms'
        ));
    }

    public function roleEdit($id)
    {
        // if (!\Entrust::can('edit-user')) return view('errors.403');
        $page_title = "Edit Role | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = 'users-roles';
        $breadcrumbs = "<i class=\"fa-fw fa fa-user\"></i>Users<span>> Edit Role </span>";

        $role = Role::find($id);
        $perms = Permission::where('name', '!=', 'access-admin')->get();

        return view('pages.user-role-edit', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'role',
            'perms'
        ));
    }

    public function roleStore()
    {
        // if (!\Entrust::can('edit-user')) return view('errors.403');
        $this->validate(request(), [
            'name' => 'required',
        ]);

        $role = !request('id') ? new Role() : Role::find(request('id'));
        $role->name = request('name');
        $role->display_name = request('display_name');
        $role->description = request('description');
        $role->save();

        $perms = \request('perms');
        if(!is_array($perms)) $perms = array();
        $role->savePermissions($perms);

        if (!request('id'))
            session()->flash('message', 'Role has been created successfully');
        else
            session()->flash('message', 'Role has been edited successfully');

        return redirect()->route('users-roles');
    }

    public function profile()
    {
        $page_title = "User Profile | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = null;
        $breadcrumbs = "<i class=\"fa-fw fa fa-user\"></i> " . auth()->user()->username . "'s Profile";
        /* format date */
        $month = date('m');
        $year = date('Y');
        $d=cal_days_in_month(CAL_GREGORIAN,$month,$year); /* số ngày trong tháng */
        $first_day_this_month = date('Y-m-01'); /* ngày đàu tiên của tháng */
        $last_day_this_month  = date('Y-m-t'); /* ngày cuối cùng của tháng */
        $curentday = date('Y-m-d'); /* ngày hiện tại của tháng */

        $user = auth()->user();
//        $ads = Ad::where('creator_id', $user->id)->pluck('_id')->toArray();
//        $query = AdResult::whereIn('ad_id', $ads);
//        $query->where('date','>=',$first_day_this_month);
//        $query->where('date','<=',$last_day_this_month);
        /* lấy query theo lenh mogodb */
        DB::connection( 'mongodb' )->enableQueryLog();
        $query = AdResult::raw(function($collection) use ($first_day_this_month,$last_day_this_month,$user) {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'as'=>'field_ads',
                        'from'=>'ads',
                        'foreignField'=>'_id',
                        'localField'=>'ad_id'
                    ]
                ],
                /*[
                    '$lookup' => [
                        'as'=>'field_user',
                        'from'=>'users',
                        'foreignField'=>'_id',
                        'localField'=>'field_ads.creator_id'
                    ]
                ],*/
                ['$match' => [
                	'date' => ['$gte' => $first_day_this_month, '$lte' => $last_day_this_month],
                	//'field_ads.creator_id'  => $user->_id
            	]],
            	/*[
                        '$project' => [
                            'ad_id'=>'$ad_id',
                            'creator_id'=>'$field_ads.creator_id',
                        ]
                    ],*/

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
DB::connection('mongodb')->getQueryLog();
        $array_month = array();

        //dd($d);
        for($i=1;$i<=$d; $i++){
            $timestamp = strtotime(date("Y")."-".date("m")."-".$i) * 1000;
            //dd($timestamp);
            $array_month[$i] = $timestamp."";
        }
//        dd($array_month);
        /*  lay du lieu c3*/
        $c3_array = array();
        foreach ($query as $item_result_c3){
            $day = explode('-',$item_result_c3['_id']);
            $c3_array[intval($day[2])] = $item_result_c3['c3'];
        }
        $chart_c3 = array();
        foreach($array_month as $key =>  $c3_day){
            if(isset($c3_array[$key])){
                $chart_c3[$c3_day] = $c3_array[$key];
            }else{
                $chart_c3[$c3_day] = 0;
            }
        }


        $key_arr = [];
        foreach($chart_c3 as $key_c3=> $value_c3){
            $key_arr_c3[] = $key_c3;
            $value_arr_c3[] = $value_c3;
        }

        /* end c3 */
        /* lay du lieu l8*/
        $l8_array = array();
        foreach ($query as $item_result_l8){
            $day = explode('-',$item_result_l8['_id']);
            $l8_array[intval($day[2])] = $item_result_l8['l8'];
        }
        $chart_l8 = array();
        foreach($array_month as $key =>  $l8_day){
            if(isset($l8_array[$key])){
                $chart_l8[$l8_day] = $l8_array[$key];
            }else{
                $chart_l8[$l8_day] = 0;
            }
        }
        foreach($chart_l8 as $key_l8=> $value_l8){
            $key_arr_l8[] = $key_l8;
            $value_arr_l8[] = $value_l8;
        }
        /* end l8 */

        /*  lấy tổng số c3 và số l8*/
        $profile['c3'] = $query->sum('c3');
        $profile['revenue'] = $query->sum('revenue');
        $profile['l8'] = $query->sum('l8');
        $profile['chart_c3'] = $chart_c3;
        $profile['chart_l8'] = $chart_l8;
        return view('pages.user-profile', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'user',
            'profile',
            'key_arr_c3',
            'value_arr_c3',
            'key_arr_l8',
            'value_arr_l8'
        ));
    }
}
