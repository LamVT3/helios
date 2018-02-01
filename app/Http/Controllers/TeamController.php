<?php

namespace App\Http\Controllers;

use App\Source;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id = 'all')
    {
        // if (!\Entrust::can('view-destination')) return view('errors.403');
        $page_title = "Teams | Helios";
        $page_css = array('selectize.default.css');
        $no_main_header = FALSE;
        $active = 'mktmanager-teams';
        $breadcrumbs = "<i class=\"fa-fw fa fa-bullhorn\"></i>Ad Mananger <span>> Teams </span>";
        DB::connection( 'mongodb' )->enableQueryLog();
        $sources = Source::all();

        if($id == 'all'){
            $teams = Team::all();
        }else{
            $teams = Team::where('source_id', $id)->orderBy('created_at', 'desc')->get();
        }
        DB::connection('mongodb')->getQueryLog();
        //where('destination_id', $id)->orderBy('order', 'asc')->with('user', 'destination')->get();

        $allMembers = User::get(['username', 'id']);
        $members = '';

        return view('pages.mkt_manager-teams', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'sources',
            'teams',
            'id',
            'allMembers',
            'members'
        ));
    }

    public function store()
    {
        /*if (!\Entrust::can('edit-review')) return view('errors.403');*/
        $this->validate(request(), [
            'source' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);

        $user = auth()->user();

        $team = request('team_id') ? Team::find(request('team_id')) : new Team();

        $source = Source::find(request('source'));
        if(!$source)
        {
            return response()->json(['type' => 'error', 'message' => 'Source not found!']);
        }

        $team->source_id = $source->_id;
        $team->source_name = $source->name;
        $team->name = request('name');
        $team->description = request('description');
        $team->creator_id = $user->id;
        $team->creator_name = $user->username;

        $array_members = [];

        $members = explode(',', request('members'));
        foreach($members as $item){
            $m = User::find($item);
            if(!$m)
            {
                return response()->json(['type' => 'error', 'message' => 'Member not found!']);
            }

            $array_members[$m->id] = array('user_id' => $m->_id, 'username' => $m->username);
        }
        $team->members = $array_members;

        $team->save();

        // debug($team);

        foreach($members as $item){
            $m = User::find($item);
            if(isset($m->sources)){
                if(isset($m->sources[$source->id])){
                    // debug($m, $m->sources[$source->id]);
                    $sources = $m->sources;
                    $sources[$source->id]['teams'][$team->id] = array('team_name' => $team->name, 'team_id' => $team->id);
                    $m->sources = $sources;
                }else{
                    $sources = $m->sources;
                    $sources[$source->id] = array(
                        'source_id' => $source->id,
                        'source_name' => $source->name,
                        'teams' => [$team->id => array('team_name' => $team->name, 'team_id' => $team->id)]
                    );
                    $m->sources = $sources;
                }
            }else{
                $m->sources = [$source->id => array(
                    'source_id' => $source->id,
                    'source_name' => $source->name,
                    'teams' => [$team->id => array('team_name' => $team->name, 'team_id' => $team->id)]
                )];
            }
            $m->save();
        }

        if (!request('id'))
            session()->flash('message', 'Team has been created successfully');
        else
            session()->flash('message', 'Team has been updated successfully');

        return response()->json(['type' => 'success', 'url' => route('team', 'all'), 'message' => 'Team has been created!']);
    }

    public function get($id)
    {
        $team = Team::find($id);
        $member_ids_array = $team->member_ids_array;
        $team->member_ids_array = $member_ids_array;
        if ($team) {
            return response()->json(['type' => 'success', 'team' => $team]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Team not found"]);
        }
    }

}
