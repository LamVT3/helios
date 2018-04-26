<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Campaign;
use App\Channel;
use App\LandingPage;
use App\Subcampaign;
use App\Team;
use App\Config;
use Illuminate\Http\Request;

class SubcampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id)
    {
        $page_css = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'campaigns';

        $subcampaign = Subcampaign::findOrFail($id);
        $campaign = Campaign::findOrFail($subcampaign->campaign_id);

        $page_title = "Subcampaign: " . $subcampaign->name . " | Helios";
        // 2018-04-04 lamvt update title
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Ads Manager <span>> Campaigns > " . $campaign->name . " > " . $subcampaign->name . "</span>";
        // end 2018-04-04

        $user = auth()->user();
        $team = Team::find($user->team_id);

        $campaigns = Campaign::where(['creator_id' => $user->id])->get();
        $subcampaigns = Subcampaign::where('campaign_id', $campaign->id)->get();
        $ads = Ad::where('subcampaign_id', $subcampaign->id)->orderBy('created_at', 'desc')->get();
        $landing_pages = LandingPage::where('is_active', 1)->get();
        $page_size     = Config::getByKey('PAGE_SIZE');

        return view('pages.ads', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'subcampaign',
            'campaign',
            'team',
            'campaigns',
            'subcampaigns',
            'ads',
            'landing_pages',
            'page_size'
        ));
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
        $active = 'campaigns';
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
