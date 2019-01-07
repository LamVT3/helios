<?php

namespace App\Http\Controllers;

use App\ApiSmsResult;
use App\Config;
use App\ApiPhoneCheckResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhoneCheckResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Phone Check Report | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = 'phone_check_report';
        $breadcrumbs = "<i class=\"fa-fw fa fa-check-square-o\"></i> Report <span>> Phone Check Report </span>";
        $page_size  = Config::getByKey('PAGE_SIZE');

        $data = $this->get_data();

        return view('pages.phone-check-result', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'page_size',
            'data'
        ));
    }

    private function get_data(){

        $request     = request();
        $startDate   = strtotime("midnight")*1000;
        $endDate     = strtotime("tomorrow")*1000;

        if($request->registered_date){
            $date_place = str_replace('-', ' ', $request->registered_date);
            $date_arr   = explode(' ', str_replace('/', '-', $date_place));
            $startDate  = strtotime($date_arr[0])*1000;
            $endDate    = strtotime("+1 day", strtotime($date_arr[1]))*1000;
        }

        $query = ApiSmsResult::where('created_date', '>=', date('Y-m-d', $startDate/1000));
        $query->where('created_date', '<', date('Y-m-d', $endDate/1000));

        if($request->result != ''){
            $query->where('result', $request->result);
        }

        return $query->get();

    }

    public function filter(){

        $data = $this->get_data();

        return view('pages.table_phone_check', compact(
            'data'
        ));
    }


}
