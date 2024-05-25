<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\Bankingpayment;
use App\Models\backend\Beat;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\Expensemaster;
use App\Models\backend\Products;
use App\Models\backend\Route;
use Illuminate\Http\Request;

class StockonhandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('backend.stockonhand.index');
    }

  
} //end of class
