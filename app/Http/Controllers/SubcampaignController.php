<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Channel;
use App\Subcampaign;
use Illuminate\Http\Request;

class SubcampaignController extends Controller
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

        $subcampaign = request('subcampaign_id') ? Subcampaign::find(request('subcampaign_id')) : new Subcampaign();
		$subcampaign->campaign_id = request('campaign_id');
        $subcampaign->name = request('name');
        $subcampaign->code = request('code');
        $subcampaign->description = request('description');
        $subcampaign->is_active = \request('is_active');

        $subcampaign->save();

        if (!request('id'))
            session()->flash('message', 'Subcampaign has been created successfully');
        else
            session()->flash('message', 'Subcampaign has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('campaign-details', $subcampaign->campaign_id), 'message' => 'Subcampaign has been created!']);
    }

    public function get($id = 'all')
    {
        if($id == 'all'){
            $subcampaign = Subcampaign::orderBy('name', 'asc')->limit(1000)->get();
        }else{
            $subcampaign = Subcampaign::find($id);
        }
        if ($subcampaign) {
            return response()->json(['type' => 'success', 'subcampaign' => $subcampaign]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Channel not found"]);
        }
    }

}
