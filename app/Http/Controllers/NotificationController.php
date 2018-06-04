<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Campaign;
use App\LandingPage;
use App\Notification;
use App\Source;
use App\Subcampaign;
use App\Team;
use App\Config;
use Illuminate\Http\Request;
use Mbarwick83\Shorty\Facades\Shorty;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Notification | Helios";
        $page_css = array('selectize.default.css');
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'notification';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Notification";
        $user = auth()->user();

	    $notifications = Notification::orderBy('created_at', 'desc')->get();

        $page_size      = Config::getByKey('PAGE_SIZE');

        return view('pages.notifications', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'notifications',
            'user',
            'page_size'
        ));
    }

    public function show($id)
    {
	    $page_title = "Notification | Helios";
	    $page_css = array('selectize.default.css');
	    $no_main_header = FALSE; //set true for lock.php and login.php
	    $active = 'notification';
	    $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i> Notification <span>> User</span>";
	    $user = auth()->user();

	    $notification = Notification::find($id);

	    $type = 'show';

	    $page_size      = Config::getByKey('PAGE_SIZE');

	    return view('pages.notifications', compact(
		    'page_title',
		    'page_css',
		    'no_main_header',
		    'active',
		    'breadcrumbs',
		    'notification',
		    'user',
		    'page_size',
		    'type'
	    ));
    }

	public function get($id)
	{
		$notification = Notification::find($id);
		if ($notification) {
			return response()->json(['type' => 'success', 'notification' => $notification]);
		} else {
			return response()->json(['type' => 'error', 'message' => "Notification not found"]);
		}
	}

	public function confirm($id)
	{
		$notification = Notification::find($id);

		$user = auth()->user();

		$array_user = $notification->users;

		$array_user[$user->id] = array('user_id' => $user->_id, 'username' => $user->username, 'date' => date('Y-m-d H:i:s'));

		$notification->users = $array_user;

		$notification->save();

		if ($notification) {
			return response()->json(['type' => 'success', 'notification' => $notification]);
		} else {
			return response()->json(['type' => 'error', 'message' => "Notification not found"]);
		}
	}

    public function save()
    {
        /*if (!\Entrust::can('edit-review')) return view('errors.403');*/

        $user = auth()->user();
        $title = request('title');
        $content = request('content');
	    $notification_type = request('notification_type');

        if($notification_type === "Create"){
            $notification = new Notification();
            $notification->title = $title;
            $notification->content = $content;
            $notification->creator_id = $user->id;
	        $notification->creator_name = $user->username;

	        try{
                $validator = [
                    'title' => 'required|unique:notifications,title',
                    'content' => 'required',
                ];
                $this->validate(request(), $validator);
            }catch(\Exception $e){
                return config('constants.NOTIFICATION_INVALID');
            }

            $notification->save();

	        session()->flash('message', 'Notification has been created successfully');

        }else{
            $notification = Notification::findOrFail(request('notification_id'));
	        $notification->title = $title;
	        $notification->content = $content;
	        $notification->save();

	        session()->flash('message', 'Notification has been updated successfully');
        }

	    return redirect()->route('notification');
    }

}
