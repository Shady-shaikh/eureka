<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\Beat;
use App\Models\backend\BillBooking;
use App\Models\backend\BillBookingItems;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\Expensemaster;
use App\Models\backend\Expenses;
use App\Models\backend\Financialyear;
use App\Models\backend\Gst;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class BillbookingController extends Controller
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
    public function index(Request $request)
    {
        // $data = BillBooking::orderby('created_at','desc')->get();
        // dd($data);
        if ($request->ajax()) {

            $billbooking = BillBooking::where(['fy_year' => session('fy_year'), 'company_id' => session('company_id'), 'status' => 'open'])->with('get_partyname')->orderby('created_at', 'desc')->get();

            return DataTables::of($billbooking)
                ->addIndexColumn()
                ->addColumn('action', function ($billbooking) {
                    $actionBtn = '<div id="action_buttons">';

                    if (request()->user()->can('Update Bill Booking')) {
                        // dd("yes");
                        $actionBtn .= '<a href="' . route('admin.billbooking.edit', ['id' => $billbooking->bill_booking_id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                    }
                    if (request()->user()->can('Delete Bill Booking')) {
                        $actionBtn .= '<a href="' . route('admin.billbooking.delete', ['id' => $billbooking->bill_booking_id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a></div>';
                    }
                    return $actionBtn;
                })
                ->addColumn('new_action', function ($billbooking) {
                    $actionBtn = ' <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">Action</button>
                    <ul class="dropdown-menu" id="action_dropdown_menu">';

                    if (request()->user()->can('Update Bill Booking')) {
                        $actionBtn .= ' <li> <a href="' . route('admin.billbooking.edit', ['id' => $billbooking->bill_booking_id]) . '
                     " class="dropdown-item" id="action_dropdown_item">
                     <span class="badge rounded-pill bg-success">Edit</span></a></li>';
                    }
                    if (request()->user()->can('Delete Banking Payments')) {
                        $actionBtn .= '<li><a href="' . route('admin.billbooking.delete', ['id' => $billbooking->bill_booking_id]) . '"
                   class="dropdown-item" id="action_dropdown_item" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                  <span class="badge rounded-pill bg-danger">Delete</span></a></li></ul>
                  </div> ';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['new_action', 'action'])
                ->make(true);
        }
        return view('backend.billbooking.index');
    }

    // //create new user
    public function create()
    {
        $party = BussinessPartnerMaster::with('get_partnerTypeName')->get();

        $party = $party->filter(function ($item) {
            $data = $item->get_partnerTypeName;

            return $data;
        })->mapWithKeys(function ($item) {
            return [$item['business_partner_id'] => $item['bp_name']];
        });
        $expense_data = Expensemaster::pluck('expense_name', 'expense_id');
        $gst = Gst::pluck('gst_name', 'gst_id');

        $values = array_keys($party->toArray());
        $party_code = array_combine($values, $values);

        $area_data = Area::pluck('area_name', 'area_id');
        return view('backend.billbooking.create', compact('area_data', 'party', 'party_code', 'expense_data', 'gst'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'vendor_id' => 'required',
            'invoice_ref_date' => 'required',
            // 'series_no' => 'required',
            // 'doc_no' => 'required',
            'posting_date' => 'required',
        ]);


        // $moduleName = "Bill Booking";
        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $series_no = get_series_number($moduleName);
        if (empty($series_no)) {
            return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
        }
        // set counter
        $financial_year = Financialyear::where(['year' => session('fy_year')])->first();
        $bill_booking_counter = 0;
        if ($financial_year) {
            $bill_booking_counter = $financial_year->bill_booking_counter + 1;
        }
        $bill_no = $series_no . '-' . $financial_year->year . "-" . $bill_booking_counter;
        $financial_year->bill_booking_counter = $bill_booking_counter;
        $financial_year->save();

        $billbooking = new BillBooking();
        $billbooking->fill($request->all());
        $billbooking->is_bb_updated = 1;
        $billbooking->fy_year = session('fy_year');
        $billbooking->company_id = session('company_id');
        $billbooking->doc_no = $bill_no;

        if ($billbooking->save()) {
            // dd($purchaseorder->toArray());
            $sub_total = $cgst_total = $sgst_utgst_total = $igst_total = $gst_grand_total = $grand_total = 0;
            $gst_rate = 0;
            // store purchaseorder items
            // dd($request->all());
            if (isset($request->invoice_items)) {
                $bill_booking_items = $request->invoice_items;
                $bill_booking_id = $billbooking->bill_booking_id;
                foreach ($bill_booking_items as $item) {
                    // dd($item);
                    $billbooking_items = new BillBookingItems();
                    $billbooking_items->fill($item);
                    $billbooking_items->bill_booking_id = $bill_booking_id;

                    $billbooking_items->save();


                    $bill_booking_id = $billbooking->bill_booking_id;
                    $bill_booking_item_id = $billbooking_items->bill_booking_item_id;
                }
            }


            $log = ['module' => 'BillBooking', 'action' => 'BillBooking Created', 'description' => 'BillBooking Created: ' . $request->doc_no];
            captureActivity($log);


            Session::flash('success', 'BillBooking Added Successfully!');
            Session::flash('status', 'success');
            return redirect()->route('admin.billbooking');
            //return redirect('admin/billbooking');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/billbooking');
        }
    }


    //edit details
    public function edit($id)
    {
        $party = BussinessPartnerMaster::with('get_partnerTypeName')->get();

        $party = $party->filter(function ($item) {
            $data = $item->get_partnerTypeName;

            return $data;
        })->mapWithKeys(function ($item) {
            return [$item['business_partner_id'] => $item['bp_name']];
        });
        $expense_data = Expensemaster::pluck('expense_name', 'expense_id');
        $gst = Gst::pluck('gst_name', 'gst_id');


        $values = array_keys($party->toArray());
        $party_code = array_combine($values, $values);


        $area_data = Area::pluck('area_name', 'area_id');
        $model = BillBooking::with('billbooking_items')->where('bill_booking_id', $id)->first();
        return view('backend.billbooking.edit', compact('model', 'area_data', 'party', 'party_code', 'expense_data', 'gst'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required',
            'invoice_ref_date' => 'required',
            // 'series_no' => 'required',
            // 'doc_no' => 'required',
            'posting_date' => 'required',
        ]);


        $invoice_items_array = array();
        $current_invoice_items = array();


        $id = $request->input('bill_booking_id');


        $billbooking = BillBooking::where('bill_booking_id', $id)->first();
        $billbooking->fill($request->all());
        $billbooking->is_bb_updated = 1;


        if ($billbooking->update()) {

            // dd($purchaseorder->toArray());
            $sub_total = $cgst_total = $sgst_utgst_total = $igst_total = $gst_grand_total = $grand_total = 0;
            $gst_rate = 0;

            $old_invoice_items  = BillBookingItems::where('bill_booking_id', $billbooking->bill_booking_id)->get('bill_booking_item_id');

            if ($old_invoice_items && count($old_invoice_items) > 0) {
                foreach ($old_invoice_items as $inv_item) {
                    // $invoice_items_array[] = $inv_item->goods_service_receipts_item_id;
                    array_push($invoice_items_array, $inv_item->bill_booking_item_id);
                }
            }

            if (isset($request->old_invoice_items)) {
                $old_purchaseorder_items = $request->old_invoice_items;

                $bill_booking_id = $billbooking->bill_booking_id;
                // dd($purchaseorder_items);
                $total_inr = 0;
                foreach ($old_purchaseorder_items as $old_item) {
                    // dd($item);
                    // $purchaseorder_item = new PurchaseOrderItems();
                    $total_inr += $old_item['total'];
                    $purchaseorder_item = BillBookingItems::where('bill_booking_item_id', $old_item['bill_booking_item_id'])->first();
                    $purchaseorder_item->fill($old_item);
                    $purchaseorder_item->purchase_order_id = $bill_booking_id;
                    // dd($purchaseorder_item);
                    $purchaseorder_item->save();
                    array_push($current_invoice_items, $purchaseorder_item->bill_booking_item_id);

                    //amount calculation
                    //   $sub_total = $sub_total + ($old_item['taxable_amount'] * $purchaseorder_item->qty);
                    $cgst_total = $cgst_total + $old_item['cgst_amount'];
                    $sgst_utgst_total = $sgst_utgst_total + $old_item['sgst_utgst_amount'];
                    $igst_total = $igst_total + $old_item['igst_amount'];

                    // gst rate
                    if ($gst_rate == 0) {
                        $gst_rate = $purchaseorder_item->cgst_rate + $purchaseorder_item->sgst_utgst_rate + $purchaseorder_item->igst_rate;
                    }



                }
               

                $invoice_difference = array_diff($invoice_items_array, $current_invoice_items);

                if ($invoice_difference && count($invoice_difference) > 0) {
                    foreach ($invoice_difference as $inv_diff) {
                        $delete_invoice = BillBookingItems::where('bill_booking_item_id', $inv_diff)->first();
                        if ($delete_invoice) {
                            $delete_invoice->delete();
                        }
                    }
                }
            } else {
                foreach ($invoice_items_array as $inv_diff) {
                    $delete_invoice = BillBookingItems::where('bill_booking_item_id', $inv_diff)->first();
                    if ($delete_invoice) {
                        $delete_invoice->delete();
                    }
                }
            }


            // store purchaseorder items
            // dd($request->all());
            if (isset($request->invoice_items)) {
                $bill_booking_items = $request->invoice_items;
                $bill_booking_id = $billbooking->bill_booking_id;
                foreach ($bill_booking_items as $item) {
                    // dd($item);
                    $billbooking_items = new BillBookingItems();
                    $billbooking_items->fill($item);
                    $billbooking_items->bill_booking_id = $bill_booking_id;

                    $billbooking_items->save();


                    $bill_booking_id = $billbooking->bill_booking_id;
                    $bill_booking_item_id = $billbooking_items->bill_booking_item_id;
                }
            }


            if ($billbooking->getChanges()) {
                $new_changes = $billbooking->getChanges();
                $log = ['module' => 'BillBooking', 'action' => 'BillBooking Updated', 'description' => 'BillBooking Updated: Name=>' . $billbooking->doc_no];
                captureActivityupdate($new_changes, $log);
            }



            Session::flash('success', 'BillBooking Updated Successfully!');
            Session::flash('status', 'success');
            return redirect()->route('admin.billbooking');
            //return redirect('admin/billbooking');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/billbooking');
        }
    }





    //function for delete address
    public function destroyBillBooking($id)
    {
        $model = BillBooking::where('bill_booking_id', $id)->first();
        if ($model) {
            if ($model->delete()) {
                BillBookingItems::where('bill_booking_id', $id)->delete();


                $log = ['module' => 'Bill Booking', 'action' => 'Bill Booking Deleted', 'description' => 'Bill Booking Deleted: ' . $model->doc_no];
                captureActivity($log);
            }
        }
        return redirect()->route('admin.billbooking')->with('success', 'Bill Booking Deleted Successfully');
    }
} //end of class
