<?php

namespace App\Http\Controllers;

use App\Ads;
use App\Campaign;
use App\Channel;
use Illuminate\Http\Request;

class AdsController extends Controller
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
            'keyword' => 'required',
            'landing_page' => 'required'
        ]);

        $ads = request('ads_id') ? Ads::find(request('ads_id')) : new Ads();
		$ads->channel_id = request('channel_id');
        $ads->name = request('name');
        $ads->keyword = request('keyword');
        $ads->landing_page = request('landing_page');
        $ads->description = request('description');
        $ads->is_active = \request('is_active');

        $ads->save();

        if (!request('id'))
            session()->flash('message', 'Ads has been created successfully');
        else
            session()->flash('message', 'Ads has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('channel-details', $ads->channel_id), 'message' => 'Ads has been created!']);
    }

    public function get($id)
    {
        $ads = Ads::find($id);
        if ($ads) {
            return response()->json(['type' => 'success', 'ads' => $ads]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Ads not found"]);
        }
    }

}
