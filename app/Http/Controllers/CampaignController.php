<?php

namespace App\Http\Controllers;

use App\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
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
            'code' => 'required',
            'description' => 'required'
        ]);

        $campaign = request('campaign_id') ? Campaign::find(request('campaign_id')) : new Campaign();
        $campaign->name = request('name');
        $campaign->code = request('code');
        $campaign->description = request('description');
        $campaign->is_active = \request('is_active');

        $campaign->save();

        if (!request('id'))
            session()->flash('message', 'Campaign has been created successfully');
        else
            session()->flash('message', 'Campaign has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('campaign'), 'message' => 'Campaign has been created!']);
    }

    public function get($id)
    {
        $campaign = Campaign::find($id);
        if ($campaign) {
            return response()->json(['type' => 'success', 'campaign' => $campaign]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Campaign not found"]);
        }
    }

}
