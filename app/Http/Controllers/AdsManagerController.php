<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Campaign;
use App\Channel;
use App\LandingPage;
use App\Source;
use App\Subcampaign;
use App\User;
use Illuminate\Http\Request;

class AdsManagerController extends Controller
{
    public function index()
    {
        $page_title = "Campaigns | Helios";
        $page_css = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'campaigns';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ad Manager <span>> Campaigns</span>";

        $user = auth()->user();

        /*$source = current($user->sources);
        $team = current($source["teams"]);*/

        $campaigns = Campaign::where('creator_id', $user->id)->get();
        $landing_pages = LandingPage::where('is_active', 1)->get();

        //dd($landing_pages);
        return view('pages.ads_manager-campaigns', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'campaigns',
            'landing_pages'
        ));
    }

    public function subcampaigns()
    {
        
    }

    public function ads()
    {
        
    }

    public function campaign($id)
    {
        $page_css = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'adsmanager';

        if($id == 'all'){

        }
        $campaign = Campaign::findOrFail($id);

        $page_title = "Campaign: " . $campaign->name . " | Helios";
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ad Manager > Campaigns <span>> " . $campaign->name . "</span>";

        $user = auth()->user();
        $source = current($user->sources);
        $team = current($source["teams"]);

        $campaigns = Campaign::where('team_id', $team['team_id'])->get();
        $subcampaigns = Subcampaign::where('campaign_id', $campaign->id)->get();
        $landing_pages = LandingPage::where('is_active', 1)->get();

        return view('pages.ads_manager-subcampaigns', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'campaigns',
            'campaign',
            'campaign_id',
            'subcampaigns',
            'landing_pages'
        ));
    }

    public function subcampaign($id)
    {
        $page_css = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'adsmanager';

        $subcampaign = Subcampaign::findOrFail($id);
        $campaign = Campaign::findOrFail($subcampaign->campaign_id);

        $page_title = "Subcampaign: " . $subcampaign->name . " | Helios";
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ad Manager > Subcampaigns <span>> " . $subcampaign->name . "</span>";

        $user = auth()->user();
        $source = current($user->sources);
        $team = current($source["teams"]);

        $campaigns = Campaign::where('team_id', $team['team_id'])->get();
        $subcampaigns = Subcampaign::where('campaign_id', $campaign->id)->get();
        $ads = Ad::where('subcampaign_id', $subcampaign->id)->get();
        $landing_pages = LandingPage::where('is_active', 1)->get();

        return view('pages.ads_manager-ads', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'subcampaign',
            'campaign',
            'campaigns',
            'subcampaigns',
            'ads',
            'landing_pages'
        ));
    }

    public function landingpage()
    {
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'adsmanager-lp';
        $page_title = "Landing Pages | Helios";
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ad Manager <span>> Landing Pages</span>";

        $landing_pages = LandingPage::all();

        return view('pages.ads_manager-landing_pages', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'landing_pages'
        ));
    }

}
