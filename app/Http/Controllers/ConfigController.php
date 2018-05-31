<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(auth()->user()->role != "Manager"){
            return redirect()->route('dashboard');
        }

        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'config';
        $page_title = "Config | Helios";
        // 2018-04-04 lamvt update title
        $breadcrumbs = "<i class=\"fa-fw fa fa-cog\"></i> Config";
        // end 2018-04-04

        $config = Config::all();
        $page_size  = Config::getByKey('PAGE_SIZE');

        return view('pages.config', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'config',
            'page_size'
        ));
    }

    public function store()
    {
        if(auth()->user()->role != "Manager"){
            return redirect()->route('dashboard');
        }

        /*if (!\Entrust::can('edit-review')) return view('errors.403');*/
        try
        {
            $this->validate(request(), [
                'key'   => 'required|unique:configs,key',
                'value' => 'required',
            ]);
        }catch(\Exception $e){
            return config('constants.CONFIG_INVALID');
        }


        $user = auth()->user();

        $config                 = request('config_id') ? Config::find(request('config_id')) : new Config();
        $config->name           = request('name');
        $config->key            = request('key');
        $config->value          = request('value');
        $config->description    = request('description');
        $config->creator_id     = $user->id;
        $config->creator_name   = $user->username;
        $config->active         = (int) \request('active');

        $config->save();

        if (!request('config_id'))
            session()->flash('message', 'Config has been created successfully');
        else
            session()->flash('message', 'Config has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('config'), 'message' => 'Config has been created!']);
    }

    public function get($id)
    {
        if(auth()->user()->role != "Manager"){
            return redirect()->route('dashboard');
        }

        $config = Config::find($id);
        if ($config) {
            return response()->json(['type' => 'success', 'config' => $config]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Landing page not found"]);
        }
    }

}
