<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Apinvoice;
use App\Models\backend\Area;
use App\Models\backend\Bankingreceipt;
use App\Models\backend\Beat;
use App\Models\backend\BillBooking;
use App\Models\backend\BillBookingItems;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\Financialyear;
use App\Models\backend\GoodsServiceReceipts;
use App\Models\backend\Products;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class BankingreceiptController extends Controller
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
        // $details = Bankingpayment::orderby('created_at', 'desc')->get();

        if ($request->ajax()) {
            $bankingreceipt = Bankingreceipt::where(['fy_year' => session('fy_year'), 'company_id' => session('company_id')])->with('get_partyname')->orderby('created_at', 'desc')->get();

            return DataTables::of($bankingreceipt)
                ->addIndexColumn()
                ->addColumn('action', function ($bankingreceipt) {
                    $actionBtn = '<div id="action_buttons">';

                    if (request()->user()->can('View Banking Receipts')) {
                        // dd("yes");
                        $actionBtn .= '<a href="' . route('admin.bankingreceipt.view', ['id' => $bankingreceipt->banking_receipt_id]) . '
                     " class="btn btn-sm btn-primary" title="View"><i class="feather icon-eye"></i></a> ';
                    }
                    if (request()->user()->can('Delete Banking Receipts')) {
                        $actionBtn .= '<a href="' . route('admin.bankingreceipt.delete', ['id' => $bankingreceipt->banking_receipt_id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a></div>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.bankingreceipt.index');
    }


    // //create new user
    public function create()
    {
        $user_id = Auth()->guard('admin')->user()->admin_user_id;

        $party = BussinessPartnerMaster::with('get_partnerTypeName')->get();

        $party = $party->filter(function ($item) {
            $data = $item->get_partnerTypeName;

            return $data;
        })->mapWithKeys(function ($item) {
            return [$item['business_partner_id'] => $item['bp_name']];
        });


        $values = array_keys($party->toArray());
        $party_code = array_combine($values, $values);

        $user_data = AdminUsers::where('admin_user_id', $user_id)->first();
        $banks = BussinessPartnerBankingDetails::where('bussiness_partner_id', $user_data->company_id)->pluck('bank_name', 'banking_details_id');


        return view('backend.bankingreceipt.create', compact('party', 'party_code', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required',
            'overdue_range' => 'required',
            'vendor_id' => 'required',
            'payment_type' => 'required',
            // 'series_no' => 'required',
            // 'doc_no' => 'required',
            'posting_date' => 'required',
        ]);

        // dd($request->all());
        $selectedRows = $request->selected_rows;
        // Update the status for related BillBooking records in bulk
        BillBooking::whereIn('bill_booking_id', function ($query) use ($selectedRows) {
            $query->select('bill_booking_id')
                ->from('bill_booking_items')
                ->whereIn('bill_booking_item_id', $selectedRows);
        })->update(['status' => 'close']);


        $model = new Bankingreceipt();
        $bill_booking_item_ids = implode(",", $selectedRows);

        $model->bill_booking_item_ids = $bill_booking_item_ids;
        $model->fill($request->all());

        // $moduleName = "Bill Booking";
        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $series_no = get_series_number($moduleName);
        if (empty($series_no)) {
            return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
        }
        // set counter
        $financial_year = Financialyear::where(['year' => session('fy_year')])->first();
        $banking_payment_counter = 0;
        if ($financial_year) {
            $banking_payment_counter = $financial_year->banking_payment_counter + 1;
        }
        $bill_no = $series_no . '-' . $financial_year->year . "-" . $banking_payment_counter;
        $financial_year->banking_payment_counter = $banking_payment_counter;

        $model->fy_year = session('fy_year');
        $model->company_id = session('company_id');
        $model->doc_no = $bill_no;
        // dd($model);

        $financial_year->save();

        if ($model->save()) {

            //update status of ap and gr
            // dd($request->toArray());
            $bill_booking_item = BillBookingItems::whereIn('bill_booking_item_id', $request->selected_rows)->first();
            $bill_booking = BillBooking::where('bill_booking_id', $bill_booking_item->bill_booking_id)->first();
            if ($bill_booking->type == 'Invoice') {
                $ap_invoice_data = Apinvoice::where('ap_inv_no', $bill_booking_item->invoice_ref_no)->first();
                $ap_invoice_data->status = 'close';
                if ($ap_invoice_data->save()) {
                    $gr_data = GoodsServiceReceipts::where('vendor_inv_no', $ap_invoice_data->vendor_inv_no)->first();
                    $gr_data->status = 'close';
                    $gr_data->save();
                }
            }

            $log = ['module' => 'Banking Receipt', 'action' => 'Banking Receipt Created', 'description' => 'Banking Receipt Created: ' . $request->doc_no];
            captureActivity($log);

            return redirect('/admin/bankingreceipt')->with('success', 'New Banking Receipt Added');
        }
    }


    public function show($id)
    {

        $GLOBALS['breadcrumb'] = [['name' => 'Banking Receipt', 'route' => "admin.bankingreceipt"], ['name' => 'View', 'route' => ""]];
        $roles = Role::pluck('name', 'id')->all();
        $purchaseorder = Bankingreceipt::where('banking_receipt_id', $id)->first();
        $selected_items = explode(",", $purchaseorder->bill_booking_item_ids);
        $bill_booking_items = BillBookingItems::whereIn('bill_booking_item_id', $selected_items)->get();
        $party = BussinessPartnerMaster::where('business_partner_id', $purchaseorder->vendor_id)->first();
        $bank_details = BussinessPartnerBankingDetails::where('bussiness_partner_id', $purchaseorder->vendor_id)->first();
        $invoice = $purchaseorder;
        // dd($purchaseorder->toArray());

        return view('backend.bankingreceipt.show', compact('roles', 'bank_details', 'bill_booking_items', 'purchaseorder', 'party', 'invoice'));
    }

    //edit details
    public function edit($id)
    {
        $model = Beat::where('beat_id', $id)->first();
        $area_data = Area::pluck('area_name', 'area_id');
        return view('backend.bankingreceipt.edit', compact('model', 'area_data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'area_id' => 'required',
            'route_id' => 'required',
            'beat_number' => 'required',
            'beat_name' => 'required',
        ]);

        $model = Beat::where('beat_id', $request->beat_id)->first();

        $model->fill($request->all());
        if ($model->save()) {
            // dd('sdgdfg');
            return redirect('/admin/bankingpayment')->with('success', 'Beat Updated');
        }
    }



    public function destroyBankingreceipt($id)
    {
        $model = Bankingreceipt::where('banking_receipt_id', $id)->first();
        $bb_item_ids = explode(",", $model->bill_booking_item_ids);
        $bill_booking_item = BillBookingItems::whereIn('bill_booking_item_id', $bb_item_ids)->first();
        $bill_booking = BillBooking::where('bill_booking_id', $bill_booking_item->bill_booking_id)->first();
        $bill_booking->status = 'open';
        $bill_booking->save();
        $model->delete();


        $log = ['module' => 'Banking Receipt', 'action' => 'Banking Receipt Deleted', 'description' => 'Banking Receipt Deleted: ' . $model->doc_no];
        captureActivity($log);

        return redirect()->route('admin.bankingreceipt')->with('success', 'Banking Receipt Deleted Successfully');
    }
} //end of class
