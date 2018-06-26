<?php
/**
 * Created by PhpStorm.
 * User: Topica
 * Date: 6/22/2018
 * Time: 3:26 PM
 */

namespace App\Http\Controllers;


use App\KPIUser;

class KPIUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($userId) {
        if(auth()->user()->role !== "Manager" && auth()->user()->role !== "Admin") return view('errors.403');

        $data = request('data');
        $month = request('month');
        $year = request('year');

        for ($i = 0; $i < sizeof($data); $i++) {
            if($data[$i] != "") {
                $day = $i + 1;
                $kpiUser = KPIUser::checkExists($userId, $month, $year, $day);
                $newKPIUser = ($kpiUser!= null) ? $kpiUser : new KPIUser();
                $newKPIUser->userId = $userId;
                $newKPIUser->date = $year.'-'.$month.'-'.$day;
                $newKPIUser->kpiC3b = $data[$i];
                $newKPIUser->save();
            }
        }
    }

    public function get($userId) {
        $date = request("date");
        list($year, $month, $day) = explode('-', $date);
        $kpiUsers = KPIUser::find($userId, $year, $month);

        return $kpiUsers;
    }
}