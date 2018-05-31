<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Campaign;
use App\Channel;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store()
    {
        /*if (!\Entrust::can('edit-review')) return view('errors.403');*/
        try{
            $validator = [
                'name' => 'required|unique:ads,name',
            ];
            $this->validate(request(), $validator);
        }catch(\Exception $e){
            return config('constants.AD_NAME_INVALID');
        }

        $ads = request('ads_id') ? Ad::find(request('ads_id')) : new Ad();
		$ads->channel_id = request('channel_id');
        $ads->name = request('name');
        $ads->keyword = request('keyword');
        $ads->landing_page = request('landing_page');
        $ads->description = request('description');
        $ads->is_active = \request('is_active');

        $ads->save();

        if (!request('id'))
            session()->flash('message', 'Ad has been created successfully');
        else
            session()->flash('message', 'Ad has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('channel-details', $ads->channel_id), 'message' => 'Ad has been created!']);
    }

    public function get($id)
    {
        $ads = Ad::find($id);
        if ($ads) {
            return response()->json(['type' => 'success', 'ads' => $ads]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Ad not found"]);
        }
    }

}
