<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Config;
use App\Source;
use App\ThankYouPage;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_css       = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active         = 'mktmanager-channel';
        $page_title     = "Channel | Helios";
        $breadcrumbs    = "<i class=\"fa-fw fa fa-gift\"></i> MKT Manager <span>> Channel</span>";

        $channel        = Channel::all();
        $sources        = Source::all();
        $page_size      = Config::getByKey('PAGE_SIZE');
        $thankyou_page  = ThankYouPage::where('is_active', 1)->get();

        return view('pages.channel', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'channel',
            'page_size',
            'thankyou_page',
            'sources'
        ));
    }

    public function store()
    {
        /*if (!\Entrust::can('edit-review')) return view('errors.403');*/

        if (!request('channel_id')){
            try{
                $validator = [
                    'name' => 'required|unique:channels,name',
                ];
                $this->validate(request(), $validator);
            }catch(\Exception $e){
                return config('constants.CHANNEL_INVALID');
            }
        }

        $user = auth()->user();

        $channel                = request('channel_id') ? Channel::find(request('channel_id')) : new Channel();
        $channel->name          = request('name');
        $channel->fb_id         = request('fb_id');

        $channel->creator_id    = $user->id;
        $channel->creator_name  = $user->username;
        $channel->is_active     = (int) \request('is_active');
        if(request('thankyou_page')){
            $thankyou_page = request('thankyou_page');
            $thankyou_page = ThankYouPage::find($thankyou_page);
            $channel->thankyou_page_id      = $thankyou_page->id;
            $channel->thankyou_page_name    = $thankyou_page->name;
            $channel->thankyou_page_url     = $thankyou_page->url;
        }

        $channel->source_name   = request('source_name');
        $channel->source_id     = request('source_id');

        $channel->save();

        if (!request('channel_id'))
            session()->flash('message', 'Channel has been created successfully');
        else
            session()->flash('message', 'Channel has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('channel'), 'message' => 'Channel been created!']);
    }

    public function get($id)
    {
        $channel = Channel::find($id);
        if ($channel) {
            return response()->json(['type' => 'success', 'channel' => $channel]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Channel not found"]);
        }
    }

    public function getAllChannel(){
        $channels = Channel::select('id', 'name')->get();

        return json_encode($channels);
    }

}
