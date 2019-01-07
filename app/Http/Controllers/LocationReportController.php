<?php

namespace App\Http\Controllers;

use App\FileImport;
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
	    $page_css = array('selectize.default.css');
        $no_main_header = FALSE;
        $active = 'location-report';
        $breadcrumbs = "<i class=\"fa-fw fa fa-map-marker\"></i> Report <span>> Location Report </span>";
        $page_size  = Config::getByKey('PAGE_SIZE');

        $file = FileImport::orderBy('created_at','desc')->first();
	    $files = FileImport::all();
	    $destinationPath = storage_path('app/upload');
	    $contacts = [];
	    $location_key = [];
	    $location_value = [];
	    $location_key   = json_encode($location_key);
	    $location_value = json_encode($location_value);

	    if ($file){
		    $filePath =  $destinationPath . '/' . $file->name;
		    $rs = $this->loadFile($filePath);
		    $contacts = $rs['contacts'];
		    $location_key = $rs['location_key'];
		    $location_value = $rs['location_value'];
	    }

	    return view('pages.location-report', compact(
            'no_main_header',
            'page_title',
            'page_css',
            'active',
            'breadcrumbs',
            'page_size',
		    'files',
		    'contacts',
		    'location_key',
		    'location_value'
        ));
    }

    public function import(Request $request){
        $file = $request->file('import');

        $destinationPath = storage_path('app/upload');
	    $date = date('Y-m-d H:i:s');

	    $file_name = $date . '-' . $file->getClientOriginalName();
        $file->move($destinationPath, $file_name);

        $filePath =  $destinationPath . '/' . $file_name;

	    $file_import = new FileImport();
	    $file_import->name = $file_name;
	    $file_import->created_date = date('Y-m-d');
	    $file_import->date = $date;
	    $file_import->save();

        $rs = $this->loadFile($filePath);
	    $contacts = $rs['contacts'];
	    $location_key = $rs['location_key'];
	    $location_value = $rs['location_value'];

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

	private function loadFile($filePath){
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
		arsort($result);

		foreach ($result as $key => $value){
			$location_key[] = [$cnt, $key];
			$location_value[] = [$cnt, $value];
			$cnt++;
		}

		$location_key   = json_encode($location_key);
		$location_value = json_encode($location_value);


		return ['contacts'=>$contacts, 'location_key'=>$location_key, 'location_value'=>$location_value, 'result'=>$result];
	}

	public function filter(Request $request){
		$file_name = $request->get('file_name');
		$arr_file_name = explode(',', $file_name);
		$arr_data = [];
		$destinationPath = storage_path('app/upload');
		$contacts = [];
		$result = [];
		foreach ($arr_file_name as $key => $file_name){
			$filePath =  $destinationPath . '/' . $file_name;
			$arr_data[$key] = $this->loadFile($filePath);
		}

		foreach ($arr_data as $key => $item){
			$contacts = array_merge($contacts,$item['contacts']);
			foreach ($item['result'] as $k=>$value) {
				if (!isset($result[$k])){
					$result[$k]=$value;
				}else{
					$result[$k]+=$value;
				}
			}
		}

		$cnt = 0;
		$location_key   = [];
		$location_value = [];
		arsort($result);
		foreach ($result as $key => $value){
			$location_key[] = [$cnt, $key];
			$location_value[] = [$cnt, $value];
			$cnt++;
		}

		$location_key   = json_encode($location_key);
		$location_value = json_encode($location_value);

		return view('pages.table_location_report', compact('contacts', 'location_key', 'location_value'));
	}

}
