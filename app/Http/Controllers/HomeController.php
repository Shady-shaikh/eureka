<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\HomePageSections;

class HomeController extends Controller
{
    public function index()
    {
      // echo "string";exit;
      $homepagesections = HomePageSections::orderBy('home_page_section_priority')->with('home_page_section_type','section_childs')->get();
      return view('welcome',compact('homepagesections'));
    }
}
