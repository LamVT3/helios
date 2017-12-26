<?php

namespace App\Http\Controllers;

use App\Source;
use App\User;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Source | Helios";
        $page_css = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'mktmanager';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ad Manager <span>> Sources</span>";

        $sources = Source::all();
        $allMembers = User::get(['name', 'id']);
        $members = '';

        return view('pages.mkt_manager-sources', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'sources',
            'allMembers',
            'members'
        ));
    }

    public function store()
    {
        /*if (!\Entrust::can('edit-review')) return view('errors.403');*/
        $this->validate(request(), [
            'name' => 'required',
            'description' => 'required'
        ]);

        $source = request('source_id') ? Source::find(request('source_id')) : new Source();
        $source->name = request('name');
        $source->description = request('description');

        $source->save();

        if (!request('id'))
            session()->flash('message', 'Source has been created successfully');
        else
            session()->flash('message', 'Source has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('source'), 'message' => 'Source has been created!']);
    }

    public function get($id)
    {
        $source = Source::find($id);
        if ($source) {
            return response()->json(['type' => 'success', 'source' => $source]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Source not found"]);
        }
    }

}
