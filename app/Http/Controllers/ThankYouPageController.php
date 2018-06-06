<?php

namespace App\Http\Controllers;

use App\ThankYouPage;
use App\Config;
use Illuminate\Http\Request;

class ThankYouPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_css       = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active         = 'mktmanager-tksPage';
        $page_title     = "Thank You Pages | Helios";
        $breadcrumbs    = "<i class=\"fa-fw fa fa-gift\"></i> MKT Manager <span>> Thank You Pages</span>";

        $thankyou_pages = ThankYouPage::all();
        $page_size      = Config::getByKey('PAGE_SIZE');
        return view('pages.thankyou_pages', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'thankyou_pages',
            'page_size'
        ));
    }

    public function store()
    {
        /*if (!\Entrust::can('edit-review')) return view('errors.403');*/
        if (!request('thankyou_page_id')){
            try{
                $validator = [
                    'name' => 'required|unique:thank_you_pages,name',
                ];
                $this->validate(request(), $validator);
            }catch(\Exception $e){
                return config('constants.THANKYOU_PAGE_INVALID');
            }
        }

        $user = auth()->user();

        $thankyou_page = request('thankyou_page_id') ? ThankYouPage::find(request('thankyou_page_id')) : new ThankYouPage();
        $thankyou_page->name            = request('name');
        $thankyou_page->url             = request('url');
        $thankyou_page->description     = request('description');
        $thankyou_page->creator_id      = $user->id;
        $thankyou_page->creator_name    = $user->username;
        $thankyou_page->is_active       = (int) \request('is_active');

        $thankyou_page->save();

        if (!request('thankyou_page_id'))
            session()->flash('message', 'Thank you page has been created successfully');
        else
            session()->flash('message', 'Thank you page has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('thankyou-page'), 'message' => 'Thank you page has been created!']);
    }

    public function get($id)
    {
        $thankyou_page = ThankYouPage::find($id);
        if ($thankyou_page) {
            return response()->json(['type' => 'success', 'thankyou_page' => $thankyou_page]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Thank you page not found"]);
        }
    }

}
