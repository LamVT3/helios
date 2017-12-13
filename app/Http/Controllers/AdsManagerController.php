<?php

namespace App\Http\Controllers;

use App\Ads;
use App\Campaign;
use App\Channel;
use App\LandingPage;
use Illuminate\Http\Request;

class AdsManagerController extends Controller
{
    public function index()
    {
        $page_title = "Campaigns | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'adsmanager';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager <span>> Campaigns</span>";

        $campaigns = Campaign::all();

        return view('pages.ads_manager_campaigns', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'campaigns'
        ));
    }

    public function campaign($id)
    {
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'adsmanager';

        $campaign = Campaign::findOrFail($id);

        $page_title = "Campaign: " . $campaign->name . " | Helios";
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager > Campaigns <span>> " . $campaign->name . "</span>";

        $channels = Channel::where('campaign_id', $campaign->id)->get();

        return view('pages.ads_manager_channels', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'campaign',
            'channels'
        ));
    }

    public function channel($id)
    {
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'adsmanager';

        $channel = Channel::findOrFail($id);

        $page_title = "Channel: " . $channel->name . " | Helios";
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager > Channels <span>> " . $channel->name . "</span>";

        $ads = Ads::where('channel_id', $channel->id)->get();
        $landing_pages = LandingPage::where('is_active', "1")->get();

        return view('pages.ads_manager_ads', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'channel',
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
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager <span>> Landing Pages</span>";

        $landing_pages = LandingPage::all();

        return view('pages.ads_manager_landing_pages', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'landing_pages'
        ));
    }

}
