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

        $sources = Source::orderBy('created_at', 'desc')->get();

        return view('pages.sources', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'sources'
        ));
    }

    public function store()
    {
        if(auth()->user()->role !== "Manager") return view('errors.403');
        $this->validate(request(), [
            'name' => 'required|alpha_dash',
            'description' => 'required'
        ]);

        $user = auth()->user();

        $source = request('source_id') ? Source::find(request('source_id')) : new Source();
        $source->name = request('name');
        $source->description = request('description');
        $source->creator_id = $user->id;
        $source->creator_name = $user->username;

        $source->save();

        if (!request('source_id'))
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
