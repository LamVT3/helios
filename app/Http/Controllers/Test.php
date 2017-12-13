<?php

namespace App\Http\Controllers;

use App\MyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Test extends Controller
{
    public function index()
    {
        $m = new MyModel();
        $m->name = 'John';
        $m->email = 'John@yahoo.com';
        $m->password = 'Johndfhd';
        $m->save();
        $model = MyModel::all();
        // dd($model);
        return view('test', compact('model'));
    }
}
