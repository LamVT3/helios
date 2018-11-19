<?php

namespace App\Http\Controllers;

use App\PhoneTest;
use App\Source;
use App\Team;
use App\User;
use App\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhoneTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // if (!\Entrust::can('view-destination')) return view('errors.403');
        $page_title = "Phone Test | Helios";
        $no_main_header = FALSE;
        $active = 'mktmanager-phone-test';
        $breadcrumbs = "<i class=\"fa-fw fa fa-phone-square\"></i> Phone Test ";

        $page_size  = Config::getByKey('PAGE_SIZE');

        $data = PhoneTest::orderBy('created_date', 'desc')->get();

        return view('pages.phone-test', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'page_size',
            'data'
        ));
    }

    public function create()
    {
        $request    = request();
        $phones      = explode(";", $request->phone);
        foreach ($phones as $phone){
            $phone_test = new PhoneTest();
            $phone      = preg_replace("/[^0-9]/", "", $phone );

            if(strlen($phone) == 10 && strlen(ltrim($phone,"0")) == 9){
                $phone_test->status = '1';
            }else{
                $phone_test->status = '0';
            }
            
            $phone_test->phone      = ltrim($phone,"0");
            $phone_test->creator    = auth()->user()->username;
            $date   = date('Y-m-d H:i:s');
            $phone_test->created_date   = strtotime($date) * 1000;

            $phone_test->save();
        }

        return response()->json(['type' => 'success', 'message' => 'Phone has been Test!']);
    }

    public function filter()
    {
        $request     = request();
        $startDate   = strtotime("midnight")*1000;
        $endDate     = strtotime("tomorrow")*1000;

        if($request->date){
            $date_place = str_replace('-', ' ', $request->date);
            $date_arr   = explode(' ', str_replace('/', '-', $date_place));
            $startDate  = strtotime($date_arr[0])*1000;
            $endDate    = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }

        $query = PhoneTest::where('created_date', '>=', $startDate);
        $query->where('created_date', '<', $endDate);

        if($request->status != ''){
            $query->where('status', $request->status);
        }

        $data = $query->orderBy('created_date', 'desc')->get();

        return view('pages.table_phone_test',['data' => $data]);
    }

}
