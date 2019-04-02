<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Campaign;
use App\Channel;
use App\LandingPage;
use App\Source;
use App\Subcampaign;
use App\Team;
use App\Config;
use Illuminate\Http\Request;
use Mbarwick83\Shorty\Facades\Shorty;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Campaigns | Helios";
        $page_css = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'campaigns';
        // 2018-04-04 lamvt update title
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager <span>> Campaigns</span>";
        // end 2018-04-04
        $user = auth()->user();

        if ($user->role == 'Admin'){
	        $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        }
        else{
	        $campaigns = Campaign::where('creator_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        $team = Team::find($user->team_id);

        $landing_pages  = LandingPage::where('is_active', 1)->get();
        $page_size      = Config::getByKey('PAGE_SIZE');

        return view('pages.campaigns', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'campaigns',
            'team',
            'landing_pages',
            'user',
            'page_size'
        ));
    }

    public function show($id)
    {
        $page_css = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'campaigns';

        if($id == 'all'){

        }
        $campaign = Campaign::findOrFail($id);

        $page_title = "Campaign: " . $campaign->name . " | Helios";
        // 2018-04-04 lamvt update title
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager <span>> Campaigns > " . $campaign->name . "</span>";
        // end 2018-04-04
        $user = auth()->user();
        $team = Team::find($user->team_id);

        if ($user->role == 'Admin'){
            $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        }
        else{
            $campaigns = Campaign::where('creator_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        $subcampaigns = Subcampaign::where('campaign_id', $campaign->id)->get();
        $landing_pages = LandingPage::where('is_active', 1)->get();
        $page_size     = Config::getByKey('PAGE_SIZE');
        $channel       = Channel::where('is_active', 1)->get();

        return view('pages.subcampaigns', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'team',
            'campaigns',
            'campaign',
            'campaign_id',
            'subcampaigns',
            'landing_pages',
            'page_size',
            'channel'
        ));
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
        $team = $user->team_id;
        $campaign_type = request('campaign_type');
        $subcampaign_type = request('subcampaign_type');
        $select_ad = request('select_ad');

        $campaign_name = request('campaign_name');
        $campaign = request('campaign');

        $subcampaign_name = request('subcampaign_name');
        $subcampaign = request('subcampaign');

        $ad_name = request('ad_name');
        $mol_link_tracking = request('mol_link_tracking', "");
        $medium = request('medium', "helios");
        $landing_page = request('landing_page');
        $channel = request('channel');

        //$current_url = \request('current_url', route('campaign'));

        $message = "";

        $source = Source::findOrFail($source);
        $team = Team::findOrFail($team);

        if($campaign_type === "new"){
            $campaign = new Campaign();
            $campaign->name = $campaign_name;
            $campaign->source_id = $source->id;
            $campaign->source_name = $source->name;
            $campaign->team_id = $team->id;
            $campaign->team_name = $team->name;
            $campaign->creator_id = $user->id;
            $campaign->creator_name = $user->username;
            $campaign->is_active = 1;

            if (!request('id')){
                try{
                    $validator = [
                        'campaign_name' => 'required|unique:campaigns,name',
                    ];
                    $this->validate(request(), $validator);
                }catch(\Exception $e){
                    return config('constants.CAMPAIGN_NAME_INVALID');
                }
            }

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

            try{
                $validator = [
                    'subcampaign_name' => 'required|unique:subcampaigns,name',
                ];
                $this->validate(request(), $validator);
            }catch(\Exception $e){
                return config('constants.SUBCAMPAIGN_NAME_INVALID');
            }

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
            $ad->medium = $medium;
            $ad->mol_link_tracking = trim(trim($mol_link_tracking), '?&');
            $ad->source_id = $source->id;
            $ad->source_name = $source->name;
            $ad->team_id = $team->id;
            $ad->team_name = $team->name;
            $ad->campaign_name = $campaign->name;
            $ad->campaign_id = $campaign->id;
            $ad->subcampaign_id = $subcampaign->id;
            $ad->subcampaign_name = $subcampaign->name;
            $landing_page = LandingPage::findOrFail($landing_page);
            $channel = Channel::findOrFail($channel);
            $ad->landing_page_id = $landing_page->id;
            $ad->landing_page_name = $landing_page->name;
            $ad->channel_id = $channel->id;
            $ad->channel_name = $channel->name;
            $ad->creator_id = $user->id;
            $ad->creator_name = $user->username;
            $ad->tracking_link = $landing_page->url . "?utm_source={$source->name}&utm_team={$team->name}&utm_agent={$user->username}&utm_campaign={$campaign->name}&utm_medium={$ad->medium}&utm_subcampaign={$subcampaign->name}&utm_ad={$ad->name}&channel={$ad->channel_name}" . ($ad->mol_link_tracking ? "&{$ad->mol_link_tracking}" : '');
            $ad->uri_query = "utm_source={$source->name}&utm_team={$team->name}&utm_agent={$user->username}&utm_campaign={$campaign->name}&utm_medium={$ad->medium}&utm_subcampaign={$subcampaign->name}&utm_ad={$ad->name}&channel={$ad->channel_name}" . ($ad->mol_link_tracking ? "&{$ad->mol_link_tracking}" : '');

            try{
                $shorten_url = Shorty::shorten($ad->tracking_link);
            }catch (\Exception $e){
                debug($e);
                $shorten_url = "";
            }

            $ad->shorten_url = $shorten_url;
            $ad->is_active = 1;

            try{
                $validator = [
                    'ad_name' => 'required|unique:ads,name',
                ];
                $this->validate(request(), $validator);
            }catch(\Exception $e){
                return config('constants.AD_NAME_INVALID');
            }

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
