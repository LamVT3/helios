<?php

namespace App\Http\Controllers;

use App\AdResult;
use App\Campaign;
use App\Contact;
use App\Source;
use App\Subcampaign;
use App\User;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSubcampaigns($id)
    {
        $campaign = Campaign::find($id);
        $subcampaigns = Subcampaign::where('campaign_id', $id)->get();
        if ($campaign) {
            return response()->json(['type' => 'success', 'campaign' => $campaign, 'subcampaigns' => $subcampaigns]);
        } else {
            return response()->json(['type' => 'error', 'message' => "Campaign not found"]);
        }
    }

    /*public function getTeamsCampaigns($source_id)
    {
        $user = auth()->user();
        $sources = $user->sources;
        $teams = $sources[$source_id]['teams'];

        $campaigns = Campaign::where(['team_id' => current($teams)['team_id'], 'creator_id' => $user->id])->get();
        return response()->json(['type' => 'success', 'teams' => $teams, 'campaigns' => $campaigns]);
    }*/

    public function getCampaigns($source_id)
    {
        $user = auth()->user();

        $campaigns = Campaign::where(['source_id' => $source_id, 'creator_id' => $user->id])->get();
        return response()->json(['type' => 'success', 'campaigns' => $campaigns]);
    }

    public function contactDetails($id)
    {
        $contact = Contact::findOrFail($id);

        return view('components.contact-details', compact(
            'contact'
        ));
    }

    public function dashboard()
    {
        $request = request();
        /* phan dashboard*/
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : Date('Y-m-d');
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : Date('Y-m-d');

        $query_dashboard = AdResult::where('date', '>=', $startDate)
            ->where('date', '<=', $endDate);

        $dashboard['c3'] = $query_dashboard->sum('c3');
        $dashboard['c3_cost'] = $query_dashboard->sum('c3_cost');
        $dashboard['spent'] = $query_dashboard->sum('spent');
        $dashboard['revenue'] = $query_dashboard->sum('revenue');
        /* end Dashboard */

        return response()->json(['type' => 'success', 'dashboard' => $dashboard]);
    }

    public function c3_leaderboard()
    {
        $request = request();
        $period = $request->period ? $request->period : 'today';

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');

        if ($period === 'thisweek') {
            $startDate = date('Y-m-d', strtotime('Last Monday', time()));
            $endDate = date('y-m-d', strtotime('Next Sunday', time()));
        }

        if ($period === 'thismonth') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }

        $query = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate], 'creator_id' => ['$ne' => null]]],
                [
                    '$group' => [
                        '_id' => '$creator_id',
                        'c3' => [
                            '$sum' => '$c3'
                        ]
                    ]
                ],
                ['$sort' => ['c3' => -1]]
            ]);
        });

        $table = '<table  class="table table-striped table-bordered table-hover"
                                           width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>C3</th>
                                </tr>
                            </thead>
                            <tbody>';

        foreach ($query as $i => $item) {
            if($i > 4) break;

            $user = User::find($item->_id);
            $no = $i+1;
            $table .= "<tr>
                                <th>{$no}</th>
                                <th>{$user['username']}</th>
                                <td>{$user['rank']}</td>
                                <td>{$item->c3}</td>
                            </tr>";
        }

        $table .= '</tbody> </table>';

        return $table;
    }

    public function revenue_leaderboard()
    {
        $request = request();
        $period = $request->period ? $request->period : 'today';

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');

        if ($period === 'thisweek') {
            $startDate = date('Y-m-d', strtotime('Last Monday', time()));
            $endDate = date('y-m-d', strtotime('Next Sunday', time()));
        }

        if ($period === 'thismonth') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }

        $query = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate], 'creator_id' => ['$ne' => null]]],
                [
                    '$group' => [
                        '_id' => '$creator_id',
                        'revenue' => [
                            '$sum' => '$revenue'
                        ]
                    ]
                ],
                ['$sort' => ['revenue' => -1]]
            ]);
        });

        $table = '<table  class="table table-striped table-bordered table-hover"
                                           width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>Revenue (baht)</th>
                                </tr>
                            </thead>
                            <tbody>';

        foreach ($query as $i => $item) {
            if($i > 4) break;

            $user = User::find($item->_id);
            $no = $i+1;
            $table .= "<tr>
                                <th>{$no}</th>
                                <th>{$user['username']}</th>
                                <td>{$user['rank']}</td>
                                <td>{$item->revenue}</td>
                            </tr>";
        }

        $table .= '</tbody> </table>';

        return $table;
    }

    public function spent_leaderboard()
    {
        $request = request();
        $period = $request->period ? $request->period : 'today';

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');

        if ($period === 'thisweek') {
            $startDate = date('Y-m-d', strtotime('Last Monday', time()));
            $endDate = date('y-m-d', strtotime('Next Sunday', time()));
        }

        if ($period === 'thismonth') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }

        $query = AdResult::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                ['$match' => ['date' => ['$gte' => $startDate, '$lte' => $endDate], 'creator_id' => ['$ne' => null]]],
                [
                    '$group' => [
                        '_id' => '$creator_id',
                        'spent' => [
                            '$sum' => '$spent'
                        ]
                    ]
                ],
                ['$sort' => ['spent' => -1]]
            ]);
        });

        $table = '<table  class="table table-striped table-bordered table-hover"
                                           width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>C3</th>
                                </tr>
                            </thead>
                            <tbody>';

        foreach ($query as $i => $item) {
            if($i > 4) break;

            $user = User::find($item->_id);
            $no = $i+1;
            $table .= "<tr>
                                <th>{$no}</th>
                                <th>{$user['username']}</th>
                                <td>{$user['rank']}</td>
                                <td>{$item->spent}</td>
                            </tr>";
        }

        $table .= '</tbody> </table>';

        return $table;
    }
}
