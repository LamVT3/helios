<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Campaign;
use App\LandingPage;
use App\Subcampaign;
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
        /*$this->validate(request(), [
            'name' => 'required',
            'code' => 'required',
            'description' => 'required'
        ]);*/

        $select_campaign = request('select_campaign');
        $select_subcampaign = request('select_subcampaign');
        $select_ad = request('select_ad');

        $campaign_name = request('campaign_name');
        $medium = request('medium');
        $campaign = request('campaign');

        $subcampaign_name = request('subcampaign_name');
        $subcampaign = request('subcampaign');

        $ad_name = request('ad_name');
        $landing_page = request('landing_page');

        $message = "";

        debug($landing_page);

        if($select_campaign === "new"){
            $campaign = new Campaign();
            $campaign->name = $campaign_name;
            $campaign->medium = $medium;
            $campaign->is_active = 1;

            $campaign->save();
            $message .= "1 campaign";
        }else{
            $campaign = Campaign::findOrFail($campaign);
        }

        if($select_subcampaign === "new"){
            $subcampaign = new Subcampaign();
            $subcampaign->name = $subcampaign_name;
            $subcampaign->campaign_id = $campaign->id;
            $subcampaign->campaign_name = $campaign->name;
            $subcampaign->is_active = 1;

            $subcampaign->save();
            if($message) $message .= ', 1 subcampaign';
            else $message .= '1 subcampaign';
        }elseif($select_subcampaign === "old"){
            $subcampaign = Subcampaign::findOrFail($subcampaign);
        }

        if($select_ad === "new"){
            $ad = new Ad();
            $ad->name = $ad_name;
            $ad->campaign_name = $campaign->name;
            $ad->campaign_id = $campaign->id;
            $ad->subcampaign_id = $subcampaign->id;
            $ad->subcampaign_name = $subcampaign->name;
            $landing_page = LandingPage::findOrFail($landing_page);
            $ad->landing_page_id = $landing_page->id;
            $ad->landing_page_name = $landing_page->name;
            //$ad->tracking_link = $landingpage . '?utm_source=' .
            $ad->is_active = 1;

            $ad->save();
            if($message) $message .= ', 1 ad';
            else $message .= '1 ad';
        }

        if (!request('id'))
            session()->flash('message', 'Campaign has been created successfully');
        else
            session()->flash('message', $message . ' has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('campaign'), 'message' => 'Campaign has been created!']);
    }

    public function get($id)
    {
        $campaign = Campaign::find($id);
        $subcampaigns = Subcampaign::where('campaign_id', $id)->get();
        if ($campaign) {
            return response()->json(['type' => 'success', 'campaign' => $campaign, 'subcampaigns' => $subcampaigns]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Campaign not found"]);
        }
    }

}
