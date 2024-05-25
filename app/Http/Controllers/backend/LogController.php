<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\backend\ActivityLog;
use App\Models\backend\AdminUsers;

use App\Models\backend\Gst;



class LogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = ActivityLog::orderby('activity_log_id', 'desc')->get();
        return view('backend.logs.index', compact('users'));
    }

    public function userlogs()
    {
        return view('backend.logs.userlogs');
    }
}//end of class
