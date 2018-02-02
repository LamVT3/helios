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
        $campaign_type = request('campaign_type');
        $subcampaign_type = request('subcampaign_type');
        $select_ad = request('select_ad');

        $campaign_name = request('campaign_name');
        $medium = request('medium');
        $campaign = request('campaign');

        $subcampaign_name = request('subcampaign_name');
        $subcampaign = request('subcampaign');

        $ad_name = request('ad_name');
        $landing_page = request('landing_page');

        //$current_url = \request('current_url', route('campaign'));

        $message = "";

        $source = Source::findOrFail($source);
        $team = Team::findOrFail($team);

        if($campaign_type === "new"){
            $campaign = new Campaign();
            $campaign->name = $campaign_name;
            $campaign->medium = $medium;
            $campaign->source_id = $source->id;
            $campaign->source_name = $source->name;
            $campaign->team_id = $team->id;
            $campaign->team_name = $team->name;
            $campaign->creator_id = $user->id;
            $campaign->creator_name = $user->username;
            $campaign->is_active = 1;

            $campaign->save();
            $message .= "1 campaign";
        }else{
            $campaign = Campaign::findOrFail($campaign);
        }

        if($subcampaign_type === "new"){
            $subcampaign = new Subcampaign();
            $subcampaign->name = $subcampaign_name;
            $subcampaign->source_id = $source->id;
            $subcampaign->source_name = $source->name;
            $subcampaign->team_id = $team->id;
            $subcampaign->team_name = $team->name;
            $subcampaign->campaign_id = $campaign->id;
            $subcampaign->campaign_name = $campaign->name;
            $subcampaign->creator_id = $user->id;
            $subcampaign->creator_name = $user->username;
            $subcampaign->is_active = 1;

            $subcampaign->save();
            if($message) $message .= ', 1 subcampaign';
            else $message .= '1 subcampaign';
        }elseif($subcampaign_type === "old"){
            $subcampaign = Subcampaign::findOrFail($subcampaign);
        }else {
            if(Subcampaign::where(array('campaign_id' => $campaign->id, 'subcampaign_name' => '[default]'))->exists()){
                $subcampaign = Subcampaign::where(array('campaign_id' => $campaign->id, 'subcampaign_name' => '[default]'))->first();
            }else{
                if($select_ad === "new"){
                    $subcampaign = new Subcampaign();
                    $subcampaign->name = '[default]';
                    $subcampaign->source_id = $source->id;
                    $subcampaign->source_name = $source->name;
                    $subcampaign->team_id = $team->id;
                    $subcampaign->team_name = $team->name;
                    $subcampaign->campaign_id = $campaign->id;
                    $subcampaign->campaign_name = $campaign->name;
                    $subcampaign->creator_id = $user->id;
                    $subcampaign->creator_name = $user->username;
                    $subcampaign->is_active = 1;

                    $subcampaign->save();
                }

            }
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
            $ad->creator_name = $user->username;
            $ad->tracking_link = $landing_page->url . "?utm_source={$source->name}&utm_team={$team->name}&utm_agent={$user->username}&utm_campaign={$campaign->name}&utm_ad={$ad->name}";
            $ad->uri_query = "utm_source={$source->name}&utm_team={$team->name}&utm_agent={$user->username}&utm_campaign={$campaign->name}&utm_ad={$ad->name}";
            $ad->is_active = 1;

            $ad->save();
            if($message) $message .= ', 1 ad';
            else $message .= '1 ad';
        }

        $url = $select_ad === "new" ? route('subcampaign-details', $subcampaign->id) : url()->previous();

        if (!request('id'))
            session()->flash('message', 'Campaign has been created successfully');
        else
            session()->flash('message', $message . ' has been updated successfully');

        return response()->json(['type' => 'success', 'url' => $url, 'message' => 'Campaign has been created!']);
    }

}
