<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Login | Helios";
        $page_css[] = "lockscreen.min.css";
        $no_main_header = TRUE;

        return view('auth.login',
            compact('page_title', 'page_css', 'no_main_header'));
    }
}
