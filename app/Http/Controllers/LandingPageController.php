<?php

namespace App\Http\Controllers;

use App\LandingPage;
use App\Config;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'adsmanager-lp';
        $page_title = "Landing Pages | Helios";
        // 2018-04-04 lamvt update title
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager <span>> Landing Pages</span>";
        // end 2018-04-04

        $landing_pages  = LandingPage::all();
        $page_size      = Config::getByKey('PAGE_SIZE');

        return view('pages.landing_pages', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'landing_pages',
            'page_size'
        ));
    }

    public function store()
    {
        /*if (!\Entrust::can('edit-review')) return view('errors.403');*/
        if (!request('landing_page_id')){
            try{
                $validator = [
                    'name' => 'required|unique:landing_pages,name',
                ];
                $this->validate(request(), $validator);
            }catch(\Exception $e){
                return config('constants.LANDING_PAGE_INVALID');
            }
        }

        $user = auth()->user();

        $landing_page = request('landing_page_id') ? LandingPage::find(request('landing_page_id')) : new LandingPage();
        $landing_page->name = request('name');
        $landing_page->platform = request('platform');
        $landing_page->url = request('url');
        $landing_page->description = request('description');
        $landing_page->creator_id = $user->id;
        $landing_page->creator_name = $user->username;
        $landing_page->is_active = (int) \request('is_active');

        $landing_page->save();

        if (!request('landing_page_id'))
            session()->flash('message', 'Landing page has been created successfully');
        else
            session()->flash('message', 'Landing page has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('landing-page'), 'message' => 'Landing page has been created!']);
    }

    public function get($id)
    {
        $landing_page = LandingPage::find($id);
        if ($landing_page) {
            return response()->json(['type' => 'success', 'landing_page' => $landing_page]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Landing page not found"]);
        }
    }

}
