<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title = "Contacts | Helios";
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'contacts-c3';
        $breadcrumbs = "<i class=\"fa-fw fa fa-child\"></i> Contacts <span>> C3</span>";

        $contacts = Contact::orderBy('registered_at', 'desc')->limit(1000)->get();

        return view('pages.contacts_c3', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'contacts'
        ));
    }

    public function details($id)
    {
        $page_css = array();
        $no_main_header = FALSE; //set true for lock.php and login.php
        $active = 'adsmanager';

        $contact = Contact::findOrFail($id);

        $page_title = "Contact: " . $contact->name . " | Helios";
        $breadcrumbs = "<i class=\"fa-fw fa fa-child\"></i> Contacts > <span>> " . $contact->name . "</span>";

        return view('pages.contacts_details', compact(
            'page_title',
            'page_css',
            'no_main_header',
            'active',
            'breadcrumbs',
            'contact'
        ));
    }

}
