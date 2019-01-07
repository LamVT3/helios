<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Contact;
use GuzzleHttp\Client;
use App\Config;

class LocationReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Location Report | Helios";
        $page_css = array();
        $no_main_header = FALSE;
        $active = 'location-report';
        $breadcrumbs = "<i class=\"fa-fw fa fa-map-marker\"></i> Report <span>> Location Report </span>";
        $page_size  = Config::getByKey('PAGE_SIZE');

        return view('pages.location-report', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'page_size'
        ));
    }

    public function import(Request $request){
        $file = $request->file('import');

        $destinationPath = storage_path('app/upload');
        $file->move($destinationPath,$file->getClientOriginalName());

        $filePath =  $destinationPath . '/' . $file->getClientOriginalName();

        $contacts = [];
        $result = [];

        Excel::load($filePath, function($reader) use (&$contacts, &$result) {

            $results = $reader->get();

            $template = ['contact_id'];
            $invalid = array_diff($template,$results->getHeading());

            if(count($invalid) > 0){
                if(count($invalid) < 5){
                    $fields = implode("',' ",$invalid);
                    $errors =  'Invalid field(s): \''.$fields.'\' in file import !!! - Please download sample file.';
                    echo $errors;
                    exit;
                }
                $errors =  'File import is invalid !!! - Please download sample file.';
                echo $errors;
                exit;
            }

            $contact_id = [];
            foreach($results as $item){
                if($item['contact_id']){
                    $contact_id[] = $item['contact_id'];
                }
            }

            $query = Contact::whereIn('contact_id', $contact_id)
//                        ->where('current_level', 'l8')
                        ->orderBy('submit_time')->get();

            foreach($query as $item){
                    $contact = [];
                    $contact['contact_id']  = @$item->contact_id;
                    $contact['name']        = @$item->name;
                    $contact['phone']       = @$item->phone;
                    $contact['email']       = @$item->email;
                    $contact['submit_time'] = @$item->submit_time;
                    $contact['ip']          = @$item->ip;

                    $api_result = $this->get_location(@$item->ip);
                    if($api_result['city'] != ''){
                        $contact['location']    = $api_result['city'];
                    }else{
                        $contact['location']    = 'N/A';
                    }

                    @$result[$contact['location']] += 1;

                    array_push($contacts, $contact);
            }

        });

        if(count($result) > 25){
            $result = array_slice($result, 0, 25);
        }

        $cnt = 0;
        $location_key   = [];
        $location_value = [];
        foreach ($result as $key => $value){
            $location_key[] = [$cnt, $key];
            $location_value[] = [$cnt, $value];
            $cnt++;
        }

        $location_key   = json_encode($location_key);
        $location_value = json_encode($location_value);

        return view('pages.table_location_report', compact('contacts', 'location_key', 'location_value'));
    }

    private function get_location($ip){
        $access_key = Config::getByKey('LOCATION_ACCESS_KEY');
        // Initialize CURL:
        $ch = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$access_key.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $api_result = json_decode($json, true);

        return $api_result;
    }

}
