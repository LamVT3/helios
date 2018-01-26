<?php

namespace App\Http\Controllers;

use App\LandingPage;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store()
    {
        /*if (!\Entrust::can('edit-review')) return view('errors.403');*/
        $this->validate(request(), [
            'name' => 'required',
            'url' => 'required',
        ]);

        $landing_page = request('landing_page_id') ? LandingPage::find(request('landing_page_id')) : new LandingPage();
        $landing_page->name = request('name');
        $landing_page->platform = request('platform');
        $landing_page->url = request('url');
        $landing_page->description = request('description');
        $landing_page->is_active = (int) \request('is_active');

        $landing_page->save();

        if (!request('id'))
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
