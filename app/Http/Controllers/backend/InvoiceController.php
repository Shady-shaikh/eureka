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
use App\Models\backend\Invoice;
use App\Models\backend\InvoiceItems;
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

class InvoiceController extends Controller
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
      $GLOBALS['breadcrumb'] = [['name'=>'Invoice','route'=>""]];
        $invoice = Invoice::latest()->get();

        return view('backend.invoice.index', compact('invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
      $GLOBALS['breadcrumb'] = [['name'=>'Invoice','route'=>"admin.invoice"],['name'=>'Create','route'=>""]];

      $roles = Role::pluck('name','id')->all();
      $party = BussinessPartnerMaster::get();
      $party = collect($party)->mapWithKeys(function ($item, $key) {
            return [$item['business_partner_id'] => $item['bp_name']];
        });
 
      $financial_year = Financialyear::where('active',1)->first();
      if(!$financial_year){
        Session::flash('message', 'Financial Year Not Active!');
            Session::flash('status', 'error');
        return redirect()->back();
      }
      $invoice_counter = 1;
      $fyear = "";
      if($financial_year){
        $invoice_counter = $financial_year->invoice_counter+1;
        $fyear = $financial_year->year;
      }
      return view('backend.invoice.create', compact('roles','party','invoice_counter','fyear'));
    }


    public function show($id){
      $GLOBALS['breadcrumb'] = [['name'=>'Invoice','route'=>"admin.invoice"],['name'=>'View','route'=>""]];
        $roles = Role::pluck('name','id')->all();
        $invoice = Invoice::where('invoice_id',$id)->with('invoice_items')->first();
        $party = BussinessPartnerMaster::where('business_partner_id',$invoice->party_id)->first();


        return view('backend.invoice.show', compact('roles','invoice','party'));
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
      $invoice = new Invoice();
      $split_invoice = 0;
      if($request->split_invoice == 'on'){
        $split_invoice = 1;
      }
      $invoice->fill($request->all());
      $invoice->split_invoice = $split_invoice;
        if($invoice->save())
        {
            $sub_total=$cgst_total=$sgst_utgst_total=$igst_total=$gst_grand_total=$grand_total=0;
            $gst_rate= 0;
            // store invoice items
            if(isset($request->invoice_items)){
                $invoice_items = $request->invoice_items;
                $invoice_id = $invoice->invoice_id;
                foreach($invoice_items as $item){
                    $invoice_item = new InvoiceItems();
                    $invoice_item->fill($item);
                    $invoice_item->invoice_id = $invoice_id;
                    $invoice_item->save();

                    //amount calculation
                    $sub_total = $sub_total + ($item['taxable_amount']*$invoice_item->qty);
                    $cgst_total = $cgst_total + $item['cgst_amount'];
                    $sgst_utgst_total = $sgst_utgst_total + $item['sgst_utgst_amount'];
                    $igst_total = $igst_total + $item['igst_amount'];

                    // gst rate
                    if($gst_rate == 0){
                      $gst_rate = $invoice_item->cgst_rate+$invoice_item->sgst_utgst_rate+$invoice_item->igst_rate;
                    }
                }
                $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                $gst_grand_total_rounded = round($gst_grand_total);
                $grand_total = $sub_total + $gst_grand_total_rounded;
                $rounded_off = round(($gst_grand_total_rounded - $gst_grand_total),2);
                $amount_in_words = amount_in_words($grand_total)." Only";
                $tax_in_words = amount_in_words($gst_grand_total_rounded)." Only";

                Invoice::where('invoice_id',$invoice->invoice_id)->update(['sub_total'=>$sub_total,'cgst_total'=>$cgst_total,'sgst_utgst_total'=>$sgst_utgst_total,'igst_total'=>$igst_total,'gst_grand_total'=>$gst_grand_total,'grand_total'=>$grand_total,'amount_in_words'=>$amount_in_words,'gst_rate'=>$gst_rate,'tax_in_words'=>$tax_in_words]);

            }

            // set job counter
          $financial_year = Financialyear::where('active',1)->first();
            $invoice_counter = 1;
            if($financial_year){
              $invoice_counter = $financial_year->invoice_counter+1;
            }
            $bill_no = "RMS/".$financial_year->year."/".$invoice_counter;

            $customer = BussinessPartnerMaster::where('business_partner_id',$invoice->party_id)->first();
            $party_name="";
            if($customer){
                $party_name=$customer->name;
            }

            Invoice::where('invoice_id',$invoice->invoice_id)->update(['invoice_counter'=>$invoice_counter,'bill_no'=>$bill_no,'party_name'=>$party_name]);
            $financial_year->invoice_counter = $invoice_counter;
            $financial_year->save();

            Session::flash('message', 'Invoice Added Successfully!');
            Session::flash('status', 'success');
            return redirect('admin/invoice/view/'.$invoice->invoice_id.'?print=yes');
            //return redirect('admin/invoice');
        }else{
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/invoice');
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
      $GLOBALS['breadcrumb'] = [['name'=>'Invoice','route'=>"admin.invoice"],['name'=>'Edit','route'=>""]];
        $invoice = Invoice::findOrFail($id);
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
        $party = collect($party)->mapWithKeys(function ($item, $key) {
            return [$item['business_partner_id'] => $item['name']];
        });

        $job = Job::get();
        $job = collect($job)->mapWithKeys(function ($item, $key) {
                return [$item['job_id'] => $item['job_id']];
            });

        $bill_to = BussinessPartnerMaster::where('business_partner_id',$invoice->bill_to)->first();
        $selected_party = BussinessPartnerMaster::where('business_partner_id',$invoice->party_id)->first();
        $bill_to_state = "Andaman and Nicobar Islands";
        $party_state = $selected_party->state;

        return view('backend.invoice.edit', compact('invoice','roles','fyear','bill_to_state','party_state','party','selected_party','job'));
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
      $id = $request->input('invoice_id');
      $this->validate($request, [
        //'invoice_type_name' => ['required', 'email', Rule::unique(Invoice::class,'email')->ignore($id, 'invoice_type_id')],
      ]);
      $invoice = Invoice::findOrFail($id);
      $split_invoice = 0;
      if($request->split_invoice == 'on'){
        $split_invoice = 1;
      }
      $invoice->fill($request->all());
      $invoice->split_invoice = $split_invoice;
      if($invoice->update())
      {
        // delete existing items
        InvoiceItems::where(['invoice_id'=>$invoice->invoice_id])->delete();

            $sub_total=$cgst_total=$sgst_utgst_total=$igst_total=$gst_grand_total=$grand_total=0;
            $gst_rate= 0;
            // store invoice items
            if(isset($request->invoice_items)){
                $invoice_items = $request->invoice_items;
                $invoice_id = $invoice->invoice_id;
                foreach($invoice_items as $item){
                    $invoice_item = new InvoiceItems();
                    $invoice_item->fill($item);
                    $invoice_item->invoice_id = $invoice_id;
                    $invoice_item->save();

                    //amount calculation
                    $sub_total = $sub_total + ($item['taxable_amount']*$invoice_item->qty);
                    $cgst_total = $cgst_total + $item['cgst_amount'];
                    $sgst_utgst_total = $sgst_utgst_total + $item['sgst_utgst_amount'];
                    $igst_total = $igst_total + $item['igst_amount'];

                    // gst rate
                    if($gst_rate == 0){
                      $gst_rate = $invoice_item->cgst_rate+$invoice_item->sgst_utgst_rate+$invoice_item->igst_rate;
                    }
                }
                $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                $gst_grand_total_rounded = round($gst_grand_total);
                $grand_total = $sub_total + $gst_grand_total_rounded;
                $rounded_off = round(($gst_grand_total_rounded - $gst_grand_total),2);
                $amount_in_words = amount_in_words($grand_total)." Only";
                $tax_in_words = amount_in_words($gst_grand_total_rounded)." Only";

                Invoice::where('invoice_id',$invoice->invoice_id)->update(['sub_total'=>$sub_total,'cgst_total'=>$cgst_total,'sgst_utgst_total'=>$sgst_utgst_total,'igst_total'=>$igst_total,'gst_grand_total'=>$gst_grand_total,'grand_total'=>$grand_total,'amount_in_words'=>$amount_in_words,'gst_rate'=>$gst_rate,'tax_in_words'=>$tax_in_words]);

            }


            $customer = BussinessPartnerMaster::where('business_partner_id',$invoice->party_id)->first();
            $party_name="";
            if($customer){
                $party_name=$customer->name;
            }

            Invoice::where('invoice_id',$invoice->invoice_id)->update(['party_name'=>$party_name]);

            Session::flash('message', 'Invoice Updated Successfully!');
            Session::flash('status', 'success');
            return redirect('admin/invoice/view/'.$invoice->invoice_id);
        }else{
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/invoice');
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
        $invoice = Invoice::findOrFail($id);

        if($invoice->delete()){
            Session::flash('message', 'Invoice Deleted Successfully!');
            Session::flash('status', 'success');
        }else{
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
        }

        return redirect('admin/invoice');
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
        $invoice = Invoice::where('invoice_id',$id)->with('invoice_items')->first();

        $party = Party::where('business_partner_id',$invoice->party_id)->first();

        $filename = $invoice->bill_no;
        if($invoice->split_invoice == 1)
        {
          $pdf = PDF::loadView('backend.invoice.invoice_format_split', ['roles'=>$roles,'invoice'=>$invoice,'party'=>$party,'download'=>true]);
        }else{
          $pdf = PDF::loadView('backend.invoice.invoice_format', ['roles'=>$roles,'invoice'=>$invoice,'party'=>$party,'download'=>true]);
        }

        return $pdf->stream($filename.'.pdf');
    }

}
