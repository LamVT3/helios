<?php

namespace App\Http\Controllers;

use App\MyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Faker\Generator as Faker;

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
        $faker = \Faker\Factory::create();
        //$faker->seed(1234);
        dd($faker->dateTimeBetween($startDate = '-2 months', $endDate = 'now')->modify("+1 day"));
        //echo Faker::name;
        return view('test', compact('model'));
    }
}
