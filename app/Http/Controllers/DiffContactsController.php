<?php

namespace App\Http\Controllers;

use App\Config;
use App\Contact;
use Illuminate\Http\Request;

class DiffContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(auth()->user()->role != "Manager" && auth()->user()->role != "Admin"){
            return redirect()->route('dashboard');
        }

        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'diff_contacts';
        $page_title = "Diff Contacts | Helios";
        // 2018-04-04 lamvt update title
        $breadcrumbs = "<i class=\"fa-fw fa fa-exclamation-triangle\"></i> Diff Contacts";
        // end 2018-04-04

        $config = Config::all();
        $page_size  = Config::getByKey('PAGE_SIZE');

        $start_date = '2018-06-17';
        $end_date = date("Y-m-d");;

        $helios_contacts    = Contact::where('submit_time', '>=', strtotime($start_date)*1000)
            ->where('submit_time', '<', strtotime("tomorrow")*1000)
            ->where('clevel', 'like', '%c3b%')
            ->orderBy('submit_time', 'desc')->limit((int)$page_size)->get();

        $mol_contacts       = $this->getMOLContacts($start_date, $end_date);

        list($helios_diff, $mol_diff) = $this->diff_contact($helios_contacts, $mol_contacts);

        return view('pages.diff_contacts', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'config',
            'page_size',
            'helios_diff',
            'mol_diff'
        ));
    }

    public function getMOLContacts($start_date, $end_date){
        $url = 'http://209.58.170.196:42580/contact_collection_api/helios';

        $data_array =  array(
            "key"           => 'Yx4uhuqGsLW2Tym0pDvNyUHKGyu4WTzKht5oOlV6',
            "start_date"    => $start_date,
            "end_date"      => $end_date,
        );
        $make_call = $this->callAPI('POST', $url, json_encode($data_array));
        $response = json_decode($make_call, true);
        $errors   = @$response['status'];
        $data     = @$response['data'];

        return $data;


    }

    private function callAPI($method, $url, $data){
        $curl = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'APIKEY: 111111111111111111111',
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
    }

    private function diff_contact($helios_contacts, $mol_contacts){

        $arr_phone_helios   = array();
        $arr_phone_mol      = array();

        foreach($helios_contacts as $contact){
            $arr_phone_helios[] = '0'.$contact->phone;
        }

        foreach($mol_contacts as $contact){
            $arr_phone_mol[] = $contact['phone'];
        }

        $mol_diff = array_diff($arr_phone_mol, $arr_phone_helios);
        $helios_diff = array_diff($arr_phone_helios, $arr_phone_mol);

        foreach($helios_contacts as $key => $contact){
            if(!in_array('0'.$contact->phone, $helios_diff)){
                unset($helios_contacts[$key]);
            }
        }

        foreach($mol_contacts as $key => $contact){
            if(!in_array($contact['phone'], $mol_diff)){
                unset($mol_contacts[$key]);
            }
        }

        return array($helios_contacts, $mol_contacts);

    }


}
