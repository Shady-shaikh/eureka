<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\AdminUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\backend\Proforma;
use App\Models\backend\ProformaItems;
use App\Models\backend\Party;
use App\Models\backend\Agent;
use App\Models\backend\Consignee;
use App\Models\backend\Financialyear;
use App\Models\backend\Job;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\Services;
use App\Models\backend\Company;
use DB;
use PDF;

class ProformaController extends Controller
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
      $GLOBALS['breadcrumb'] = [['name'=>'Proforma','route'=>""]];
        $proforma = Proforma::latest()->get();

        return view('backend.proforma.index', compact('proforma'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
      $GLOBALS['breadcrumb'] = [['name'=>'Proforma','route'=>"admin.proforma"],['name'=>'Create','route'=>""]];

      $roles = Role::pluck('name','id')->all();
      $party = BussinessPartnerMaster::get();
      // $party = collect($party)->mapWithKeys(function ($item, $key) {
      //       return [$item['business_partner_id'] => $item['bp_name']];
      //   });
      $party = $party->filter(function ($item) {
        $data = $item->getpartnertypecustomer;
    
        return $data;
      })->mapWithKeys(function ($item) {
        return [$item['business_partner_id'] => $item['bp_name']];
      });
 
      $financial_year = Financialyear::where('active',1)->first();
      if(!$financial_year){
        Session::flash('message', 'Financial Year Not Active!');
            Session::flash('status', 'error');
        return redirect()->back();
      }
      $proforma_counter = 1;
      $fyear = "";
      if($financial_year){
        $proforma_counter = $financial_year->proforma_counter+1;
        $fyear = $financial_year->year;
      }
      return view('backend.proforma.create', compact('roles','party','proforma_counter','fyear'));
    }


    public function show($id){
      $GLOBALS['breadcrumb'] = [['name'=>'Proforma','route'=>"admin.proforma"],['name'=>'View','route'=>""]];
        $roles = Role::pluck('name','id')->all();
        $invoice = Proforma::where('proforma_id',$id)->with('proforma_items')->first();
        $party = BussinessPartnerMaster::where('business_partner_id',$invoice->party_id)->first();


        return view('backend.proforma.show', compact('roles','invoice','party'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        //'' => ['required'],
      ]);
      $proforma = new proforma();
      $split_proforma = 0;
      if($request->split_proforma == 'on'){
        $split_proforma = 1;
      }
      $proforma->fill($request->all());
      $proforma->split_proforma = $split_proforma;
        if($proforma->save())
        {
            $sub_total=$cgst_total=$sgst_utgst_total=$igst_total=$gst_grand_total=$grand_total=0;
            $gst_rate= 0;
            // store proforma items
            if(isset($request->proforma_items)){
                $proforma_items = $request->proforma_items;
                $proforma_id = $proforma->proforma_id;
                foreach($proforma_items as $item){
                    $proforma_item = new proformaItems();
                    $proforma_item->fill($item);
                    $proforma_item->proforma_id = $proforma_id;
                    $proforma_item->save();

                    //amount calculation
                    $sub_total = $sub_total + ($item['taxable_amount']*$proforma_item->qty);
                    $cgst_total = $cgst_total + $item['cgst_amount'];
                    $sgst_utgst_total = $sgst_utgst_total + $item['sgst_utgst_amount'];
                    $igst_total = $igst_total + $item['igst_amount'];

                    // gst rate
                    if($gst_rate == 0){
                      $gst_rate = $proforma_item->cgst_rate+$proforma_item->sgst_utgst_rate+$proforma_item->igst_rate;
                    }
                }
                $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                $gst_grand_total_rounded = round($gst_grand_total);
                $grand_total = $sub_total + $gst_grand_total_rounded;
                $rounded_off = round(($gst_grand_total_rounded - $gst_grand_total),2);
                $amount_in_words = amount_in_words($grand_total)." Only";
                $tax_in_words = amount_in_words($gst_grand_total_rounded)." Only";

                Proforma::where('proforma_id',$proforma->proforma_id)->update(['sub_total'=>$sub_total,'cgst_total'=>$cgst_total,'sgst_utgst_total'=>$sgst_utgst_total,'igst_total'=>$igst_total,'gst_grand_total'=>$gst_grand_total,'grand_total'=>$grand_total,'amount_in_words'=>$amount_in_words,'gst_rate'=>$gst_rate,'tax_in_words'=>$tax_in_words]);

            }

            // set job counter
          $financial_year = Financialyear::where('active',1)->first();
            $proforma_counter = 1;
            if($financial_year){
              $proforma_counter = $financial_year->proforma_counter+1;
            }
            $bill_no = "RMS/".$financial_year->year."/".$proforma_counter;

            $customer = BussinessPartnerMaster::where('business_partner_id',$proforma->party_id)->first();
            $party_name="";
            if($customer){
                $party_name=$customer->name;
            }

            Proforma::where('proforma_id',$proforma->proforma_id)->update(['proforma_counter'=>$proforma_counter,'bill_no'=>$bill_no,'party_name'=>$party_name]);
            $financial_year->proforma_counter = $proforma_counter;
            $financial_year->save();

            Session::flash('message', 'proforma Added Successfully!');
            Session::flash('status', 'success');
            return redirect('admin/proforma/view/'.$proforma->proforma_id.'?print=yes');
            //return redirect('admin/proforma');
        }else{
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/proforma');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
      $GLOBALS['breadcrumb'] = [['name'=>'Proforma','route'=>"admin.proforma"],['name'=>'Edit','route'=>""]];
        $proforma = Proforma::findOrFail($id);
        $roles = Role::pluck('name','id')->all();

        $financial_year = Financialyear::where('active',1)->first();
        if(!$financial_year){
          Session::flash('message', 'Financial Year Not Active!');
              Session::flash('status', 'error');
          return redirect()->back();
        }
        $fyear = "";
      if($financial_year){
        $fyear = $financial_year->year;
      }
        $party = BussinessPartnerMaster::Where('type','party')->get();
        // $party = collect($party)->mapWithKeys(function ($item, $key) {
        //     return [$item['business_partner_id'] => $item['name']];
        // });
        $party = $party->filter(function ($item) {
          $data = $item->getpartnertypecustomer;
      
          return $data;
        })->mapWithKeys(function ($item) {
          return [$item['business_partner_id'] => $item['bp_name']];
        });

        $job = Job::get();
        $job = collect($job)->mapWithKeys(function ($item, $key) {
                return [$item['job_id'] => $item['job_id']];
            });

        $bill_to = BussinessPartnerMaster::where('business_partner_id',$proforma->bill_to)->first();
        $selected_party = BussinessPartnerMaster::where('business_partner_id',$proforma->party_id)->first();
        $bill_to_state = "Andaman and Nicobar Islands";
        $party_state = $selected_party->state;

        return view('backend.proforma.edit', compact('proforma','roles','fyear','bill_to_state','party_state','party','selected_party','job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update(Request $request)
    {
      $id = $request->input('proforma_id');
      $this->validate($request, [
        //'proforma_type_name' => ['required', 'email', Rule::unique(Proforma::class,'email')->ignore($id, 'proforma_type_id')],
      ]);
      $proforma = Proforma::findOrFail($id);
      $split_proforma = 0;
      if($request->split_proforma == 'on'){
        $split_proforma = 1;
      }
      $proforma->fill($request->all());
      $proforma->split_proforma = $split_proforma;
      if($proforma->update())
      {
        // delete existing items
        proformaItems::where(['proforma_id'=>$proforma->proforma_id])->delete();

            $sub_total=$cgst_total=$sgst_utgst_total=$igst_total=$gst_grand_total=$grand_total=0;
            $gst_rate= 0;
            // store proforma items
            if(isset($request->proforma_items)){
                $proforma_items = $request->proforma_items;
                $proforma_id = $proforma->proforma_id;
                foreach($proforma_items as $item){
                    $proforma_item = new proformaItems();
                    $proforma_item->fill($item);
                    $proforma_item->proforma_id = $proforma_id;
                    $proforma_item->save();

                    //amount calculation
                    $sub_total = $sub_total + ($item['taxable_amount']*$proforma_item->qty);
                    $cgst_total = $cgst_total + $item['cgst_amount'];
                    $sgst_utgst_total = $sgst_utgst_total + $item['sgst_utgst_amount'];
                    $igst_total = $igst_total + $item['igst_amount'];

                    // gst rate
                    if($gst_rate == 0){
                      $gst_rate = $proforma_item->cgst_rate+$proforma_item->sgst_utgst_rate+$proforma_item->igst_rate;
                    }
                }
                $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                $gst_grand_total_rounded = round($gst_grand_total);
                $grand_total = $sub_total + $gst_grand_total_rounded;
                $rounded_off = round(($gst_grand_total_rounded - $gst_grand_total),2);
                $amount_in_words = amount_in_words($grand_total)." Only";
                $tax_in_words = amount_in_words($gst_grand_total_rounded)." Only";

                Proforma::where('proforma_id',$proforma->proforma_id)->update(['sub_total'=>$sub_total,'cgst_total'=>$cgst_total,'sgst_utgst_total'=>$sgst_utgst_total,'igst_total'=>$igst_total,'gst_grand_total'=>$gst_grand_total,'grand_total'=>$grand_total,'amount_in_words'=>$amount_in_words,'gst_rate'=>$gst_rate,'tax_in_words'=>$tax_in_words]);

            }


            $customer = BussinessPartnerMaster::where('business_partner_id',$proforma->party_id)->first();
            $party_name="";
            if($customer){
                $party_name=$customer->name;
            }

            Proforma::where('proforma_id',$proforma->proforma_id)->update(['party_name'=>$party_name]);

            Session::flash('message', 'proforma Updated Successfully!');
            Session::flash('status', 'success');
            return redirect('admin/proforma/view/'.$proforma->proforma_id);
        }else{
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/proforma');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $proforma = Proforma::findOrFail($id);

        if($proforma->delete()){
            Session::flash('message', 'proforma Deleted Successfully!');
            Session::flash('status', 'success');
        }else{
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
        }

        return redirect('admin/proforma');
    }


    public function amountinwords($number=0)
    {
      return amount_in_words($number);
    }



    public function partydetails($partyid){

        $party_detail = "";
        $bill_to_state = "Andaman and Nicobar Islands";
        $party_state = "";

            $party = BussinessPartnerMaster::where('business_partner_id',$partyid)->first();

            if($party){
                $party_detail = "<strong>".$party->name."</strong><br>";
                $party_detail .= "<span>".$party->address."</span><br>";
                $party_detail .= "<span>POS: Code & State: ".$party->state."</span><br>";
                $party_detail .= "<span>GSTIN:".$party->gst_no."</span><br>";
                $party_state = $party->state;
            }

        $details['party_detail'] = $party_detail;
        $details['party_state'] = $party_state;
        $details['bill_to_state'] = $bill_to_state;
        $details['bill_to_gst_no'] = $party->gst_no;
        return json_encode($details);
    }


    public function autocomplete()
    {
      //dd($request->query);
      $query = $_GET['query'];
      $data = Services::select(DB::raw("service_name as name"),"hsn_sac","taxable_amount","gst_percent")->where("service_name","LIKE","%".$query."%")->get();
      //var_dump($data);exit;
      return response()->json($data);
    }


    public function download($id){
        $roles = Role::pluck('name','id')->all();
        $proforma = Proforma::where('proforma_id',$id)->with('proforma_items')->first();

        $party = Party::where('business_partner_id',$proforma->party_id)->first();

        $filename = $proforma->bill_no;
        if($proforma->split_proforma == 1)
        {
          $pdf = PDF::loadView('backend.proforma.proforma_format_split', ['roles'=>$roles,'proforma'=>$proforma,'party'=>$party,'download'=>true]);
        }else{
          $pdf = PDF::loadView('backend.proforma.proforma_format', ['roles'=>$roles,'proforma'=>$proforma,'party'=>$party,'download'=>true]);
        }

        return $pdf->stream($filename.'.pdf');
    }

}
