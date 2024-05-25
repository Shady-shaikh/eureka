<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use App\Models\backend\Area;
use App\Models\backend\Beat;
use App\Models\backend\Bsplcategory;
use App\Models\backend\Bsplsubcategory;
use App\Models\backend\Bspltype;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\BussinessPartnerContactDetails;
use App\Models\backend\Route;

class DropdownController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth:admin');
  }


  public function get_areas(){
    $area_data = Area::pluck('area_name','area_id');
    return json_encode($area_data);
    // dd($area_id);
  }
  public function get_routes(){
    $area_id = $_POST['area_id'];
    $route_data = Route::where('area_id',$area_id)->pluck('route_name','route_id');
    return json_encode($route_data);
    // dd($area_id);
  }


  public function get_beat(){
    $route_id = $_POST['route_id'];
    $beat_data = Beat::where('route_id',$route_id)->pluck('beat_name','beat_id');
    // dd($beat_data);
    return json_encode($beat_data);
    // dd($area_id);
  }

  public function get_contact_person(){
    $party_id = $_POST['party_id'];
    $contact_person_data = BussinessPartnerContactDetails::where(['type'=>'Bill-To/ Bill-From','bussiness_partner_id'=>$party_id])->pluck('contact_person','contact_details_id');
    // dd($beat_data);
    return json_encode($contact_person_data);
    // dd($area_id);
  }

  public function get_ship_from_data(){
    $party_id = $_POST['party_id'];
    $ship_from_data = BussinessPartnerAddress::where(['bussiness_partner_id'=>$party_id,'address_type'=>'Ship-To/ Ship-From'])->pluck('bp_address_name','bp_address_id');
    // dd($beat_data);
    return json_encode($ship_from_data);
    // dd($area_id);
  }

  public function get_billto_data(){
    $party_id = $_POST['party_id'];
    $ship_from_data = BussinessPartnerAddress::where(['bussiness_partner_id'=>$party_id,'address_type'=>'Bill-To/ Bill-From'])->pluck('bp_address_name','bp_address_id');
    // dd($beat_data);
    return json_encode($ship_from_data);
    // dd($area_id);
  }


  public function get_bank_data(){
    $id = $_POST['id'];
    $data = BussinessPartnerBankingDetails::where(['bussiness_partner_id'=>$id])->pluck('banking_details_id','bank_name');
    return json_encode($data);
  }

  public function get_bank_acc_data(){
    $id = $_POST['id'];
    $data = BussinessPartnerBankingDetails::where(['banking_details_id'=>$id])->first();
    return json_encode($data);
  }

  public function get_category_data(){
    $bsplheads_id = $_POST['id'];
    $data = Bsplcategory::where(['bsplheads_id'=>$bsplheads_id])->pluck('bspl_cat_name','bsplcat_id');
    return json_encode($data);
  }

  public function get_subcategory_data(){
    $bsplcat_id = $_POST['id'];
    $data = Bsplsubcategory::where(['bsplcat_id'=>$bsplcat_id])->pluck('bspl_subcat_name','bsplsubcat_id');
    return json_encode($data);
  }

  public function get_type_data(){
    $bsplsubcat_id = $_POST['id'];
    $data = Bspltype::where(['bsplsubcat_id'=>$bsplsubcat_id])->pluck('bspl_type_name','bsplstype_id');
    return json_encode($data);
  }





  
}
