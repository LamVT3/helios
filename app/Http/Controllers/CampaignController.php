<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Campaign;
use App\LandingPage;
use App\Source;
use App\Subcampaign;
use App\Team;
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
        $user = auth()->user();

        $source = request('source');
        $team = request('team');
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

        $source = Source::findOrFail($source);
        $team = Team::findOrFail($team);

        if($select_campaign === "new"){
            $campaign = new Campaign();
            $campaign->name = $campaign_name;
            $campaign->medium = $medium;
            $campaign->source_id = $source->id;
            $campaign->source_name = $source->name;
            $campaign->team_id = $team->id;
            $campaign->team_name = $team->name;
            $campaign->creator_id = $user->id;
            $campaign->creator_name = $user->name;
            $campaign->is_active = 1;

            $campaign->save();
            $message .= "1 campaign";
        }else{
            $campaign = Campaign::findOrFail($campaign);
        }

        if($select_subcampaign === "new"){
            $subcampaign = new Subcampaign();
            $subcampaign->name = $subcampaign_name;
            $subcampaign->source_id = $source->id;
            $subcampaign->source_name = $source->name;
            $subcampaign->team_id = $team->id;
            $campaign->team_name = $team->name;
            $subcampaign->campaign_id = $campaign->id;
            $subcampaign->campaign_name = $campaign->name;
            $subcampaign->creator_id = $user->id;
            $subcampaign->creator_name = $user->name;
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
            $ad->source_id = $source->id;
            $ad->source_name = $source->name;
            $ad->team_id = $team->id;
            $ad->team_name = $team->name;
            $ad->campaign_name = $campaign->name;
            $ad->campaign_id = $campaign->id;
            $ad->subcampaign_id = $subcampaign->id;
            $ad->subcampaign_name = $subcampaign->name;
            $landing_page = LandingPage::findOrFail($landing_page);
            $ad->landing_page_id = $landing_page->id;
            $ad->landing_page_name = $landing_page->name;
            $ad->creator_id = $user->id;
            $ad->creator_name = $user->name;
            $ad->tracking_link = $landing_page->url . "?utm_source={$source->name}&utm_team={$team->name}&utm_agent={$user->name}&utm_campaign={$campaign->name}&utm_ad={$ad->name}";
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
