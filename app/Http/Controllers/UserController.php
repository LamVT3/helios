<?php

namespace App\Http\Controllers;

use App\Ad;
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
//        $fillable = ['c3,c3_cost,l8'];
        /*$query = DB::table('adresults as ars')
            ->join('ads', 'ads.id','=','ars.creator_id')
            ->join('users','user.id','=','ars.creator_id')
            ->where('users_id', Auth::user()->user_id);*/

        $user = auth()->user();

        $ads = Ad::where('creator_id', $user->id)->pluck('_id')->toArray();
        $query = AdResult::whereIn('ad_id', $ads);

        // dd($query->sum('c3'));

        $profile['c3'] = $query->sum('c3');
        $profile['revenue'] = $query->sum('revenue');
        $profile['l8'] = $query->sum('l8');
        $profile['username'] = Auth::user()->username;

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
