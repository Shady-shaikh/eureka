<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\AdminUsers;
use App\Models\backend\Apinvoice;
use App\Models\backend\ApInvoiceBatches;
use App\Models\backend\ApInvoiceItems;
use App\Models\backend\ArInvoice;
use App\Models\backend\OrderFulfilment;
use App\Models\backend\ArInvoiceBatches;
use App\Models\backend\ArInvoiceItems;
use App\Models\backend\BillBooking;
use App\Models\backend\BillBookingItems;
use App\Models\backend\BinManagement;
use App\Models\backend\Bintype;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\BussinessPartnerContactDetails;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\backend\GoodsServiceReceipts;
use App\Models\backend\GoodsServiceReceiptsItems;
use App\Models\backend\Financialyear;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\BussinessPartnerOrganizationType;
use App\Models\backend\Company;
use App\Models\backend\GoodsServiceReceiptsBatches;
use App\Models\backend\Gst;
use App\Models\backend\Inventory;
use App\Models\backend\OrderBooking;
use App\Models\backend\Partnerledger;
use App\Models\backend\PerDayInventory;
use App\Models\backend\PurchaseOrder;
use App\Models\backend\Products;
use App\Models\backend\StorageLocations;
use App\Models\backend\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ArinvoiceController extends Controller
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
        $GLOBALS['breadcrumb'] = [['name' => 'A/R Invoice', 'route' => ""]];
        // $apinvoice = ArInvoice::with('get_partyname')->get();
        if ($request->ajax()) {
            if (session('company_id') != 0 && session('fy_year') != 0) {
                $order_book_ids = OrderBooking::pluck('order_booking_id');
                $apinvoice = ArInvoice::whereIn('order_booking_id', $order_book_ids)->where(['fy_year' => session('fy_year'), 'company_id' => session('company_id')])->with('get_partyname')->orderby('created_at', 'desc')->get();
            } else {
                $apinvoice = ArInvoice::with('get_partyname')->orderby('created_at', 'desc')->get();
            }
            return DataTables::of($apinvoice)
                ->addIndexColumn()
                ->addColumn('action', function ($apinvoice) {
                    $actionBtn = '<div id="action_buttons">';
                    if (request()->user()->can('View A/R Invoice')) {
                        $actionBtn .= '<a href="' . route('admin.arinvoice.view', ['id' => $apinvoice->order_fulfillment_id]) . '"
                    class="btn btn-sm btn-primary" title="View"><i class="feather icon-eye"></i></a> ';
                    }
                    // if (request()->user()->can('Update A/R Invoice') && (empty($apinvoice->is_bill_booking_done))) {
                    //     // dd("yes");
                    //     $actionBtn .= '<a href="' . route('admin.arinvoice.edit', ['id' => $apinvoice->order_fulfillment_id]) . '
                    //  " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                    // }
                    if (request()->user()->can('Delete A/R Invoice')) {
                        $actionBtn .= '<a href="' . route('admin.arinvoice.delete', ['id' => $apinvoice->order_fulfillment_id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a></div>';
                    }
                    return $actionBtn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.arinvoice.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $GLOBALS['breadcrumb'] = [['name' => 'A/P Invoice', 'route' => "admin.apinvoice"], ['name' => 'Create', 'route' => ""]];

        $roles = Role::pluck('name', 'id')->all();
        $gsts = Gst::pluck('gst_name', 'gst_id')->all();
        $party = BussinessPartnerMaster::get();
        // $party = collect($party)->mapWithKeys(function ($item, $key) {
        //     return [$item['business_partner_id'] => $item['bp_name']];
        // });
        $party = $party->filter(function ($item) {
            $data = $item->getpartnertype;

            return $data;
        })->mapWithKeys(function ($item) {
            return [$item['business_partner_id'] => $item['bp_name']];
        });

        $financial_year = Financialyear::where('active', 1)->first();
        // dd($financial_year->toArray());
        if (!$financial_year) {
            Session::flash('message', 'Financial Year Not Active!');
            Session::flash('status', 'error');
            return redirect()->back();
        }
        $purchase_order_counter = 1;
        $fyear = "";
        if ($financial_year) {
            $purchase_order_counter = $financial_year->purchase_order_counter + 1;
            $fyear = $financial_year->year;
        }
        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id')->all();
        $gst = Gst::pluck('gst_name', 'gst_id');
        $purchaseorder = PurchaseOrder::latest()->pluck('bill_no', 'purchase_order_id');

        return view('backend.apinvoice.create', compact('roles', 'gst', 'party', 'purchase_order_counter', 'fyear', 'storage_locations', 'purchaseorder'));
    }


    public function show($id)
    {
        // dd('asdf');
        $GLOBALS['breadcrumb'] = [['name' => 'GoodsServiceReceipts', 'route' => "admin.goodsservicereceipts"], ['name' => 'View', 'route' => ""]];
        $roles = Role::pluck('name', 'id')->all();
        $goodsservicereceipts = Arinvoice::where('order_fulfillment_id', $id)->with('goodsservicereceipts_items')->with('get_bill_toaddress')->with('get_ship_toaddress')->first();
        // dd($goodsservicereceipts->toArray());
        $party = BussinessPartnerMaster::where('business_partner_id', $goodsservicereceipts->party_id)->first();
        $bill_address = BussinessPartnerAddress::where(['bussiness_partner_id' => $goodsservicereceipts->party_id, 'address_type' => 'Bill-To/ Bill-From'])->first();
        $ship_address = BussinessPartnerAddress::where(['bussiness_partner_id' => $goodsservicereceipts->party_id, 'address_type' => 'Ship-To/ Ship-From'])->first();
        $contact = BussinessPartnerContactDetails::where('bussiness_partner_id', $goodsservicereceipts->party_id)->first();
        $bank_details = BussinessPartnerBankingDetails::where('bussiness_partner_id', $goodsservicereceipts->party_id)->first();
        $invoice = $goodsservicereceipts;
        // dd($goodsservicereceipts->toArray());

        return view('backend.arinvoice.show', compact('roles', 'bill_address', 'ship_address', 'contact', 'bank_details', 'goodsservicereceipts', 'party', 'invoice'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
            'bill_date' => 'required',
            'receipt_type' => 'required',
            'party_id' => 'required',
            'vendor_ref_no' => 'required',
            'ship_from' => 'required',
            'delivery_date' => 'required',
            'document_date' => 'required',
            'status' => 'required',
            'bill_to_gst_no' => 'required',
            'place_of_supply' => 'required',
        ]);
        $goodsservicereceipts = new Apinvoice();
        $goodsservicereceipts->fill($request->all());
        if ($goodsservicereceipts->save()) {
            $sub_total = $cgst_total = $sgst_utgst_total = $igst_total = $gst_grand_total = $grand_total = 0;
            $gst_rate = 0;

            //check for file and upload file
            if ($request->has('po_document')) {
                $location = public_path('/uploads/po_document/' . $goodsservicereceipts->goods_service_receipt_id);
                $upload_file = 'po_' . date('dmyhis') . '_.' . $request->file('po_document')->getClientOriginalExtension();  //
                $request->file('po_document')->move($location, $upload_file);
                $goodsservicereceipts->po_document = $upload_file;
                $goodsservicereceipts->save();
            }


            // store goodsservicereceipts items
            // dd($request->all());
            if (isset($request->invoice_items)) {
                $goodsservicereceipts_items = $request->invoice_items;
                $purchase_order_id = $goodsservicereceipts->purchase_order_id;
                foreach ($goodsservicereceipts_items as $item) {
                    $goodsservicereceipts_item = new ApInvoiceItems();
                    $goodsservicereceipts_item->fill($item);
                    $goodsservicereceipts_item->purchase_order_id = $purchase_order_id;
                    $goodsservicereceipts_item->goods_service_receipt_id = $goodsservicereceipts->goods_service_receipt_id;
                    // dd($goodsservicereceipts_item->toArray());
                    $goodsservicereceipts_item->save();
                    // dd($item);
                    //amount calculation
                    $sub_total = $sub_total + ($item['taxable_amount'] * $goodsservicereceipts_item->qty);
                    $cgst_total = $cgst_total + $item['cgst_amount'];
                    $sgst_utgst_total = $sgst_utgst_total + $item['sgst_utgst_amount'];
                    $igst_total = $igst_total + $item['igst_amount'];

                    // gst rate
                    $gst_rate = $goodsservicereceipts_item->gst_rate;
                    if (!empty($gst_rate)) {
                        $get_data = Gst::where('gst_id', $gst_rate)->first();
                        $gst_rate = $get_data->gst_percent;
                    }

                    //save batches
                    $goodsservicereceipts_id = $goodsservicereceipts->goods_service_receipt_id;
                    $goodsservicereceipts_item_id = $goodsservicereceipts_item->goods_service_receipts_item_id;
                    // dd($goodsservicereceipts_item_id);


                    if (isset($item['batches'])) {
                        foreach ($item['batches'] as $batches) {
                            $goodsservicereceiptsbatches = new ApInvoiceBatches;

                            $data = [
                                'goods_service_receipt_id' => $goodsservicereceipts_id,
                                'goods_service_receipts_item_id' => $goodsservicereceipts_item_id,
                                'batch_no' => $batches['batch_no'],
                                'manufacturing_date' => $batches['manufacturing_date'],
                                'expiry_date' => $batches['expiry_date']
                            ];

                            if ($batches['batch_no'] != '') {
                                $goodsservicereceiptsbatches->fill($data);
                                $goodsservicereceiptsbatches->save();
                            }
                        }
                    }
                }   //loop ends of goods services reciepnts items
                $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                $gst_grand_total_rounded = round($gst_grand_total);
                $grand_total = $sub_total + $gst_grand_total_rounded;
                if (!empty($request->discount)) {
                    $discount = ($grand_total * $request->discount) / 100;
                    $grand_total = round($sub_total + $gst_grand_total_rounded - $discount);
                }
                $rounded_off = round(($gst_grand_total_rounded - $gst_grand_total), 2);
                $amount_in_words = amount_in_words($grand_total) . " Only";
                $tax_in_words = amount_in_words($gst_grand_total_rounded) . " Only";

                Apinvoice::where('purchase_order_id', $goodsservicereceipts->purchase_order_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $grand_total, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);
            }

            // set job counter
            $financial_year = Financialyear::where('active', 1)->first();
            $purchase_order_counter = 1;
            if ($financial_year) {
                $purchase_order_counter = $financial_year->purchase_order_counter + 1;
            }
            $bill_no = "EUREKA/" . $financial_year->year . "/" . $purchase_order_counter;

            $customer = BussinessPartnerMaster::where('business_partner_id', $goodsservicereceipts->party_id)->first();
            // $party_name = "";
            // if ($customer) {
            //     $party_name = $customer->name;
            // }

            Apinvoice::where('purchase_order_id', $goodsservicereceipts->purchase_order_id)->update(['purchase_order_counter' => $purchase_order_counter, 'bill_no' => $bill_no]);
            $financial_year->purchase_order_counter = $purchase_order_counter;
            $financial_year->save();

            Session::flash('message', 'A/P Invoice Added Successfully!');
            Session::flash('status', 'success');
            // return redirect('admin/goodsservicereceipts/view/'.$goodsservicereceipts->purchase_order_id.'?print=yes');
            return redirect('admin/apinvoice');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/apinvoice');
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
        $party = BussinessPartnerMaster::get();
        $gst = Gst::pluck('gst_name', 'gst_id');
        // $party = collect($party)->mapWithKeys(function ($item, $key) {
        //     return [$item['business_partner_id'] => $item['bp_name']];
        // });
        $party = $party->filter(function ($item) {
            $data = $item->getpartnertypecustomer;

            return $data;
        })->mapWithKeys(function ($item) {
            return [$item['business_partner_id'] => $item['bp_name']];
        });

        $financial_year = Financialyear::where('active', 1)->first();
        if (!$financial_year) {
            Session::flash('message', 'Financial Year Not Active!');
            Session::flash('status', 'error');
            return redirect()->back();
        }
        $order_booking_counter = 1;
        $fyear = "";
        if ($financial_year) {
            $order_booking_counter = $financial_year->order_booking_counter + 1;
            $fyear = $financial_year->year;
        }
        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id')->all();

        $purchaseorder = OrderBooking::latest()->pluck('bill_no', 'order_booking_id');

        if ($id != '' && $id != '') {
            $model = Arinvoice::with('goodsservicereceipts_items')->where('order_fulfillment_id', $id)->first();
            $data = ArInvoiceItems::first();
            // dd($model->toArray());
            // dd($goodsservicereceipt->toArray());
            if ($model) {
                return view('backend.arinvoice.edit', compact('model', 'gst', 'party', 'financial_year', 'order_booking_counter', 'fyear', 'storage_locations', 'purchaseorder'));
            }
        }
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

        // dd($request->all());
        $this->validate($request, [
            // 'receipt_type' => 'required',
            // 'trans_type' => 'required',
            'party_id' => 'required',
            // 'place_of_supply' => 'required',
            'customer_ref_no' => 'required',
            // 'posting_date' => 'required',
            'due_date' => 'required',
            'document_date' => 'required',
            'status' => 'required',
            'contact_person' => 'required',
        ]);

        $bp_master = BussinessPartnerMaster::where('business_partner_id', $request->party_id)->first();
        $company = Company::where('company_id', $bp_master->company_id)->first();

        $invoice_items_array = array();
        $current_invoice_items = array();

        $goodsservicereceipt = Arinvoice::where('order_fulfillment_id', $request->order_fulfillment_id)->first();

        if ($goodsservicereceipt) {
            $goodsservicereceipt->fill($request->all());
            $goodsservicereceipt->status = 'close';

            $of = OrderFulfilment::where('order_fulfillment_id', $request->order_fulfillment_id)->first();
            $of->status = 'close';
            $of->save();
            // dd($goodsservicereceipt);
            //update Goods service Receipts
            if ($goodsservicereceipt->save()) {

                //save initial data
                $sub_total = $cgst_total = $sgst_utgst_total = $igst_total = $gst_grand_total = $grand_total = 0;
                $gst_rate = 0;

                //update old data

                //get old invoice items for
                $old_invoice_items  = ArInvoiceItems::where('order_fulfillment_id', $goodsservicereceipt->order_fulfillment_id)->get('order_fulfillment_item_id');
                //store ids in saparate array()

                if ($old_invoice_items && count($old_invoice_items) > 0) {
                    foreach ($old_invoice_items as $inv_item) {
                        // $invoice_items_array[] = $inv_item->goods_service_receipts_item_id;
                        array_push($invoice_items_array, $inv_item->order_fulfillment_item_id);
                    }
                }
                // dd($invoice_items_array);
                // dd($request->old_invoice_items);

                if (isset($request->old_invoice_items)) {

                    //save data of GoodsService Receipts
                    // $old_goodsservicereceipts_items = $request->old_invoice_items;
                    $filteredItems = $request->old_invoice_items;

                    $old_goodsservicereceipts_items = array_filter($filteredItems, function ($item) {
                        return $item['order_fulfillment_item_id'] !== null;
                    });

                    $new_goodsservicereceipts_items = array_filter($filteredItems, function ($item) {
                        return $item['order_fulfillment_item_id'] == null;
                    });

                    //update inventory

                    foreach ($filteredItems as $row) {

                        $good_bin_type_id = Bintype::where('name', 'Good')->first();
                        $good_bin = BinManagement::where(['bin_type' => $good_bin_type_id->bin_type_id, 'warehouse_id' => $row['storage_location_id']])->first();


                        $batch_no = $row['batch_no'] ?? '';
                        if (empty($batch_no) || $batch_no == null) {

                            $check_for_batch = Inventory::where([
                                'warehouse_id' => $row['storage_location_id'],
                                'bin_id' => $good_bin->bin_id,
                                'item_code' => $row['item_code'],
                                // 'fy_year' => $goodsservicereceipt->fy_year,
                                'company_id' => $goodsservicereceipt->company_id,
                            ])->first();

                            if (!empty($check_for_batch)) {
                                $batch_no = $check_for_batch->batch_no;
                            } else {
                                DB::table('def_bacth_no_counter')->increment('counter');
                                $counterValue = DB::table('def_bacth_no_counter')->value('counter');
                                $batch_no = $counterValue . '-Batch-' . $row['item_code'];
                            }
                        }

                        $product = Products::where('item_code', $row['item_code'])->first();

                        if (!empty($product)) {
                            // dd($row);
                            $inventoryExist = Inventory::where([
                                'warehouse_id' => $row['storage_location_id'],
                                'bin_id' => $good_bin->bin_id,
                                'sku' => $product->sku,
                                // 'fy_year' => $goodsservicereceipt->fy_year,
                                'company_id' => $goodsservicereceipt->company_id,
                            ])->when(($company->batch_system != 0), function ($q) use ($batch_no) {
                                return $q->where([
                                    'batch_no' => $batch_no,
                                ]);
                            })->first();

                            if (empty($inventoryExist)) {
                                return redirect()->back()->with('error', 'Selected batch is not found in this warehouse');
                            }

                            $perday_inventoryExist = PerDayInventory::where([
                                'warehouse_id' => $row['storage_location_id'],
                                'bin_id' => $good_bin->bin_id,
                                'sku' => $product->sku,
                                // 'fy_year' => $goodsservicereceipt->fy_year,
                                'company_id' => $goodsservicereceipt->company_id,
                            ])
                                ->when(($company->batch_system != 0), function ($q) use ($batch_no) {
                                    return $q->where([
                                        'batch_no' => $batch_no,
                                    ]);
                                })

                                ->whereDate('created_at', Carbon::today())
                                ->first();


                            $inventory = Inventory::where(['batch_no' => $batch_no])->where('manufacturing_date', '!=', null)
                                ->orWhere('expiry_date', '!=', null)
                                ->first();


                            $inventoryData = [
                                'doc_no' => $goodsservicereceipt->bill_no,
                                'warehouse_id' => $row['storage_location_id'],
                                'bin_id' => optional($good_bin)->bin_id,
                                // 'batch_no' => $batch_no,
                                'item_code' => $row['item_code'],
                                'sku' => $product->sku,
                                'qty' => $inventoryExist->qty - $row['final_qty'],
                                'blocked_qty' => 0,
                                'fy_year' => $goodsservicereceipt->fy_year,
                                'company_id' => $goodsservicereceipt->company_id,
                                'user_id' => Auth()->guard('admin')->user()->admin_user_id,
                                // 'unit_price' => $row['taxable_amount'],
                                'manufacturing_date' => $row['manufacturing_date'] ?? optional($inventory)->manufacturing_date ?? '',
                                'expiry_date' => $row['expiry_date'] ?? optional($inventory)->expiry_date ?? '',
                            ];
                            $base_quantity = optional($inventoryExist)->qty ?? 0;

                            // Check if $inventoryExist is null before deciding to update or create
                            if ($inventoryExist === null) {
                                // Inventory::create($inventoryData);
                            } else {
                                $inventoryExist->update($inventoryData);
                            }

                            if ($perday_inventoryExist === null) {
                                // PerDayInventory::create($inventoryData);
                            } else {
                                $perday_inventoryExist->update($inventoryData);
                            }



                            // Use the same data for Transaction
                            $transactionData = $inventoryData;
                            unset($transactionData['qty']);

                            // $routeName = Route::currentRouteName();
                            $moduleName = "A/r Invoice";
                            // dd($moduleName);
                            $series_no = get_series_number($moduleName, $bp_master->company_id);
                            $transaction_type = get_transaction_type($moduleName, $bp_master->company_id);

                            if (empty($series_no)) {
                                return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
                            }


                            $transactionHistory = new Transaction();
                            $transactionHistory->transaction_type =  $transaction_type;
                            $transactionHistory->qty =  $base_quantity;
                            $transactionHistory->updated_qty = $row['final_qty'];
                            $transactionHistory->final_qty = $base_quantity - $row['final_qty'];
                            $transactionHistory->sku = $product->sku;
                            $transactionHistory->unit_price = $row['taxable_amount'];
                            $transactionHistory->fill($transactionData);
                            $transactionHistory->save();

                            // update ledger
                            $partnerLedger = new Partnerledger();
                            $partnerLedger->party_id = $request->party_id;
                            $partnerLedger->doc_no = $request->ar_temp_no;
                            $partnerLedger->transaction_type =  $transaction_type;
                            $partnerLedger->qty =  $base_quantity;
                            $partnerLedger->updated_qty = $row['final_qty'];
                            $partnerLedger->final_qty = $base_quantity + $row['final_qty'];
                            $partnerLedger->sku = $row['sku'];
                            $partnerLedger->unit_price = $row['taxable_amount'];
                            $partnerLedger->total = $row['total'];
                            $partnerLedger->cgst_amount = $row['cgst_amount'];
                            $partnerLedger->sgst_utgst_amount = $row['sgst_utgst_amount'];
                            $partnerLedger->igst_amount = $row['igst_amount'];
                            $partnerLedger->gst_rate = $row['gst_rate'];
                            $partnerLedger->gst_amount = $row['gst_amount'];
                            $partnerLedger->gross_total = $row['gross_total'];
                            $partnerLedger->fill($transactionData);
                            $partnerLedger->save();
                        }
                    }




                    $purchase_order_id = $goodsservicereceipt->order_booking_id;
                    $total_inr = 0;

                    if (!empty($new_goodsservicereceipts_items)) {
                        foreach ($new_goodsservicereceipts_items as $item) {
                            $total_inr += $item['total'];
                            if ($item['item_name'] != '') {

                                $goodsservicereceipts_item = new ArInvoiceItems();
                                $sku = Products::where('item_code', $item['item_code'])->first();
                                $goodsservicereceipts_item->sku = $sku->sku;
                                $goodsservicereceipts_item->fill($item);
                                $goodsservicereceipts_item->order_booking_id = $purchase_order_id;
                                $goodsservicereceipts_item->order_fulfillment_id = $request->order_fulfillment_id;
                                $goodsservicereceipts_item->party_id = $request->party_id;
                                $goodsservicereceipts_item->save();


                                //amount calculation
                                $sub_total = $sub_total + ($item['taxable_amount'] * $goodsservicereceipts_item->qty);
                                $cgst_total = $cgst_total + $item['cgst_amount'];
                                $sgst_utgst_total = $sgst_utgst_total + $item['sgst_utgst_amount'];
                                $igst_total = $igst_total + $item['igst_amount'];

                                // gst rate
                                $gst_rate = $goodsservicereceipts_item->gst_rate;
                                if (!empty($gst_rate)) {
                                    $get_data = Gst::where('gst_id', $gst_rate)->first();
                                    $gst_rate = $get_data->gst_percent;
                                }
                            }

                            //update old  data of repeater


                            //end of if
                        } ////end of foreach
                        //update old
                        $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                        $gst_grand_total_rounded = $gst_grand_total;
                        // $grand_total = $sub_total + $gst_grand_total_rounded;
                        $gst_grand_total_rounded = $gst_grand_total;
                        // $grand_total = $sub_total + $gst_grand_total_rounded;

                        $final_amt = 0;
                        // dd($gross_total);
                        if (!empty($request->discount)) {
                            $discount = ($total_inr * $request->discount) / 100;
                            // dd($discount);
                            $final_amt = round(($total_inr + $gst_grand_total_rounded) - $discount);
                        } else {
                            $final_amt = round($total_inr + $gst_grand_total_rounded);
                        }

                        $rounded_off = round(($gst_grand_total_rounded - $gst_grand_total), 2);
                        $amount_in_words = amount_in_words(round($final_amt)) . " Only";
                        $tax_in_words = amount_in_words(round($gst_grand_total_rounded)) . " Only";

                        Arinvoice::where('order_booking_id', $goodsservicereceipt->order_booking_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);
                    }

                    foreach ($old_goodsservicereceipts_items as $old_item) {
                        $total_inr += $old_item['total'];
                        if (isset($old_item['order_fulfillment_item_id']) && $old_item['order_fulfillment_item_id'] != '') {

                            $old_goodsservicereceipts_item = ArInvoiceItems::where('order_fulfillment_item_id', $old_item['order_fulfillment_item_id'])->first();
                            //   dd($old_goodsservicereceipts_item->toArray());
                            $sku = Products::where('item_code', $old_item['item_code'])->first();
                            $old_goodsservicereceipts_item->sku = $sku->sku;
                            $old_goodsservicereceipts_item->fill($old_item);
                            $old_goodsservicereceipts_item->order_booking_id = $purchase_order_id;
                            $old_goodsservicereceipts_item->order_fulfillment_id = $request->order_fulfillment_id;
                            $old_goodsservicereceipts_item->save();
                            array_push($current_invoice_items, $old_goodsservicereceipts_item->order_fulfillment_item_id);


                            //amount Calculation

                            $sub_total = $sub_total + ($old_item['taxable_amount'] * $old_goodsservicereceipts_item->qty);
                            $cgst_total = $cgst_total + $old_item['cgst_amount'];
                            $sgst_utgst_total = $sgst_utgst_total + $old_item['sgst_utgst_amount'];
                            $igst_total = $igst_total + $old_item['igst_amount'];

                            // gst rate
                            $gst_rate = $old_goodsservicereceipts_item->gst_rate;
                            if (!empty($gst_rate)) {
                                $get_data = Gst::where('gst_id', $gst_rate)->first();
                                $gst_rate = $get_data->gst_percent;
                            }


                            // dd($updated_data);
                        }
                    }   //end of loop of old items
                    $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                    // dd($gst_grand_total);
                    $gst_grand_total_rounded = $gst_grand_total;
                    $final_amt = 0;
                    // dd($gross_total);
                    if (!empty($request->discount)) {
                        $discount = ($total_inr * $request->discount) / 100;
                        // dd($discount);
                        $final_amt = round(($total_inr + $gst_grand_total_rounded) - $discount);
                    } else {
                        $final_amt = round($total_inr + $gst_grand_total_rounded);
                    }

                    $rounded_off = round(($gst_grand_total_rounded - $gst_grand_total), 2);
                    $amount_in_words = amount_in_words(round($final_amt)) . " Only";
                    $tax_in_words = amount_in_words(round($gst_grand_total_rounded)) . " Only";
                    // dd($sgst_utgst_total);
                    $updated_data =  Arinvoice::where('order_booking_id', $goodsservicereceipt->order_booking_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);

                    $invoice_difference = array_diff($invoice_items_array, $current_invoice_items);
                    if ($invoice_difference && count($invoice_difference) > 0) {
                        foreach ($invoice_difference as $inv_diff) {
                            $delete_invoice = ArInvoiceItems::where('order_fulfillment_item_id', $inv_diff)->first();
                            if ($delete_invoice) {
                                $delete_invoice->delete();
                            }
                        }
                    }
                } else {
                    foreach ($invoice_items_array as $inv_diff) {
                        $delete_invoice = ArInvoiceItems::where('order_fulfillment_item_id', $inv_diff)->first();
                        if ($delete_invoice) {
                            $delete_invoice->delete();
                        }
                    }
                }
            }
        }

        //update ar invoice first update status
        $goodsservicereceipt->is_bill_booking_done = 1;
        $goodsservicereceipt->save();



        if ($goodsservicereceipt->getChanges()) {
            $new_changes = $goodsservicereceipt->getChanges();
            $log = ['module' => 'A/R Invoice', 'action' => 'A/R Invoice Updated', 'description' => 'A/R Invoice Updated: Name=>' . $goodsservicereceipt->bill_no];
            captureActivityupdate($new_changes, $log);
        }

        // dd($request->all());
        // dd('out');
        // return back to edit page
        return redirect()->route('admin.arinvoice')->with('success', 'Details has Been Updated');
    }     //end of function

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        // dd($id);
        $goodsservicereceipts = Arinvoice::findOrFail($id);

        // dd($goodsservicereceipts);

        if ($goodsservicereceipts->delete()) {

            $log = ['module' => 'A/R Invoice', 'action' => 'A/R Invoice Deleted', 'description' => 'A/R Invoice Deleted: ' . $goodsservicereceipts->bill_no];
            captureActivity($log);

            Session::flash('message', 'A/R Invoice Deleted Successfully!');
            Session::flash('status', 'success');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
        }

        return redirect('admin/arinvoice');
    }


    public function amountinwords($number = 0)
    {
        return amount_in_words($number);
    }



    public function partydetails($business_partner_id)
    {
        $business_partner = BussinessPartnerMaster::where('business_partner_id', $business_partner_id)->first();
        $comapny_data = Company::where('company_id', $business_partner->company_id)->first();

        // $comapny_data = Company::first();
        $business_partner_detail = "";
        $bill_to_state = "";
        $business_partner_state = $comapny_data->state;
        $bill_to_gst_no = "";
        $party_name = "";

        $business_partner = BussinessPartnerMaster::where('business_partner_id', $business_partner_id)->first();

        $business_partner_address = BussinessPartnerAddress::where(['bussiness_partner_id' => $business_partner_id, 'address_type' => 'Bill-To/ Bill-From'])->first();

        // dd($business_partner_address);

        if ($business_partner && $business_partner_address) {
            $party_name = $business_partner->bp_name;
            $bill_to_state = $business_partner_address->state;
            $business_partner_detail = "<strong>" . $business_partner->bp_name . "</strong><br>";
            $business_partner_detail .= "<span>" . $business_partner_address->building_no_name . ' ' . $business_partner_address->street_name . ' ' .
                $business_partner_address->landmark . ' ' . $business_partner_address->city . ' ' . $business_partner_address->district .  "</span><br>";
            $business_partner_detail .= "<span>POS: Code & State: " . $business_partner_address->pin_code . ' ' . $business_partner_address->state . "</span><br>";
            $bill_to_gst_no .= $business_partner->gst_details;

            // $business_partner_state = $business_partner->state;
        }


        $details['party_name'] = $party_name;
        $details['party_detail'] = $business_partner_detail;
        $details['party_state'] = $business_partner_state;
        $details['bill_to_state'] = $bill_to_state;
        $details['bill_to_gst_no'] = $bill_to_gst_no;

        // dd($details);
        return json_encode($details);
    }


    public function autocomplete()
    {
        //dd($request->query);
        $query = $_GET['query'];
        // dd($query);
        $data = Products::select(DB::raw("consumer_desc as name"), 'product_item_id', 'hsncode_id')->where("consumer_desc", "LIKE", "%" . $query . "%")->get();
        // dd($data);
        return response()->json($data);
    }


    public function download($id)
    {
        $roles = Role::pluck('name', 'id')->all();
        $goodsservicereceipts = GoodsServiceReceipts::where('purchase_order_id', $id)->with('goodsservicereceipts_items')->first();

        $party = BussinessPartnerMaster::where('business_partner_id', $goodsservicereceipts->party_id)->first();

        $filename = $goodsservicereceipts->bill_no;
        if (1 != 1) {
            $pdf = PDF::loadView('backend.goodsservicereceipts.goodsservicereceipts_format_split', ['roles' => $roles, 'goodsservicereceipts' => $goodsservicereceipts, 'party' => $party, 'download' => true]);
        } else {
            $pdf = Pdf::loadView('backend.goodsservicereceipts.invoice_format', ['roles' => $roles, 'goodsservicereceipts' => $goodsservicereceipts, 'party' => $party, 'download' => true]);
        }

        return $pdf->stream($filename . '.pdf');
    }
}
