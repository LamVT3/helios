<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
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

        $channel = request('channel_id') ? Channel::find(request('channel_id')) : new Channel();
		$channel->campaign_id = request('campaign_id');
        $channel->name = request('name');
        $channel->code = request('code');
        $channel->description = request('description');
        $channel->is_active = \request('is_active');

        $channel->save();

        if (!request('id'))
            session()->flash('message', 'Channel has been created successfully');
        else
            session()->flash('message', 'Channel has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('campaign-details', $channel->campaign_id), 'message' => 'Channel has been created!']);
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

}
