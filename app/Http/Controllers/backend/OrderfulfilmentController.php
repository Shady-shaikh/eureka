<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\AdminUsers;
use App\Models\backend\Apinvoice;
use App\Models\backend\ApInvoiceBatches;
use App\Models\backend\ApInvoiceItems;
use App\Models\backend\ArInvoice;
use App\Models\backend\Incentives;
use App\Models\backend\ArInvoiceBatches;
use App\Models\backend\ArInvoiceItems;
use App\Models\backend\BinManagement;
use App\Models\backend\Bintype;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerBankingDetails;
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
use App\Models\backend\Invoice;
use App\Models\backend\OrderBooking;
use App\Models\backend\OrderBookingItems;
use App\Models\backend\OrderFulfilment;
use App\Models\backend\OrderFulfilmentBatches;
use App\Models\backend\OrderFulfilmentItems;
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

class OrderfulfilmentController extends Controller
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
        $GLOBALS['breadcrumb'] = [['name' => 'OrderFulfillment', 'route' => ""]];
        // $goodsservicereceipts = OrderFulfilment::with('get_partyname')->get();

        if ($request->ajax()) {

            if (session('company_id') != 0 && session('fy_year') != 0) {
                $order_book_ids = OrderBooking::pluck('order_booking_id');
                $purchaseorder = OrderFulfilment::whereIn('order_booking_id', $order_book_ids)->where(['status' => 'open', 'fy_year' => session('fy_year'), 'company_id' => session('company_id')])->with('get_partyname')->orderby('created_at', 'desc')->get();
            } else {
                $purchaseorder = OrderFulfilment::where(['status' => 'open'])->with('get_partyname')->orderby('created_at', 'desc')->get();
            }
            return DataTables::of($purchaseorder)
                ->addIndexColumn()
                ->addColumn('action', function ($purchaseorder) {

                    $ap_inv_data = ArInvoice::where('of_id', $purchaseorder->order_fulfillment_id)->first();


                    $actionBtn = '<a href="' . route('admin.orderbooking.close_of_status', ['id' => $purchaseorder->order_booking_id]) . '"
                    class="btn btn-sm btn-light mr-1" title="Close Sale Order And Order Fulfilment">Close</a></div>';


                    $actionBtn .= '<div id="action_buttons">';
                    if (request()->user()->can('View Order Fulfilment/Dispatch') && $purchaseorder->is_of_done == 1) {
                        $actionBtn .= '<a href="' . route('admin.orderfulfilment.view', ['id' => $purchaseorder->order_fulfillment_id]) . '"
                    class="btn btn-sm btn-primary" title="View"><i class="feather icon-eye"></i></a> ';
                    }


                    if (request()->user()->can('Clone Order Fulfilment/Dispatch')) {
                        if ($purchaseorder->is_of_done == 1 && empty($ap_inv_data)) {
                            $actionBtn .= '<a href="' . route('admin.orderfulfilment.createarinvoice', ['id' => $purchaseorder->order_fulfillment_id]) . '
                     " class="btn btn-sm btn-info" title="Create A/R Invoice"   >
                     <i class="feather icon-plus"></i></a> ';
                        }
                    }
                    if (request()->user()->can('Update Order Fulfilment/Dispatch')) {
                        // dd("yes");
                        if (!$purchaseorder->is_inventory_updated) {
                            $actionBtn .= '<a href="' . route('admin.orderfulfilment.edit', ['id' => $purchaseorder->order_fulfillment_id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                        }
                    }
                    if (request()->user()->can('Delete Order Fulfilment/Dispatch')) {
                        $actionBtn .= '<a href="' . route('admin.orderfulfilment.delete', ['id' => $purchaseorder->order_fulfillment_id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a></div>';
                    }

                    return $actionBtn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.orderfulfilment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $GLOBALS['breadcrumb'] = [['name' => 'GoodsServiceReceipts', 'route' => "admin.goodsservicereceipts"], ['name' => 'Create', 'route' => ""]];

        $roles = Role::pluck('name', 'id')->all();
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

        $purchaseorder = PurchaseOrder::latest()->pluck('bill_no', 'purchase_order_id');

        return view('backend.goodsservicereceipts.create', compact('roles', 'party', 'purchase_order_counter', 'fyear', 'storage_locations', 'purchaseorder'));
    }


    public function show($id)
    {
        $GLOBALS['breadcrumb'] = [['name' => 'OrderFulfilment', 'route' => "admin.orderfulfilment"], ['name' => 'View', 'route' => ""]];
        $roles = Role::pluck('name', 'id')->all();
        $goodsservicereceipts = OrderFulfilment::where('order_fulfillment_id', $id)->with('goodsservicereceipts_items')->with('get_bill_toaddress')->with('get_ship_toaddress')->first();
        // dd($goodsservicereceipts->toArray());
        $party = BussinessPartnerMaster::where('business_partner_id', $goodsservicereceipts->party_id)->first();
        $bank_details = BussinessPartnerBankingDetails::where('bussiness_partner_id', $goodsservicereceipts->party_id)->first();
        $invoice = $goodsservicereceipts;
        // dd($invoice->toArray());
        // gst_rate

        return view('backend.orderfulfilment.show', compact('roles', 'bank_details', 'goodsservicereceipts', 'party', 'invoice'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'purchase_order_id' => ['unique:goods_service_receipts,purchase_order_id'],
            [
                'bill_to.unique' => 'GR Invoice Already Present'
            ]
        ]);
        $goodsservicereceipts = new GoodsServiceReceipts();
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
            if (isset($request->invoice_items)) {
                $goodsservicereceipts_items = $request->invoice_items;
                $purchase_order_id = $goodsservicereceipts->purchase_order_id;
                foreach ($goodsservicereceipts_items as $item) {
                    $goodsservicereceipts_item = new GoodsServiceReceiptsItems();
                    $goodsservicereceipts_item->fill($item);

                    $goodsservicereceipts_item->purchase_order_id = $purchase_order_id;
                    $goodsservicereceipts_item->save();

                    //amount calculation
                    $sub_total = $sub_total + ($item['taxable_amount'] * $goodsservicereceipts_item->qty);
                    $cgst_total = $cgst_total + $item['cgst_amount'];
                    $sgst_utgst_total = $sgst_utgst_total + $item['sgst_utgst_amount'];
                    $igst_total = $igst_total + $item['igst_amount'];

                    // gst rate
                    if ($gst_rate == 0) {
                        $gst_rate = $goodsservicereceipts_item->cgst_rate + $goodsservicereceipts_item->sgst_utgst_rate + $goodsservicereceipts_item->igst_rate;
                    }
                    //save batches
                    $goodsservicereceipts_id = $goodsservicereceipts->goods_service_receipt_id;
                    $goodsservicereceipts_item_id = $goodsservicereceipts_item->goods_service_receipts_item_id;


                    if (isset($item['batches'])) {
                        foreach ($item['batches'] as $batches) {
                            $goodsservicereceiptsbatches = new GoodsServiceReceiptsBatches;

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

                GoodsServiceReceipts::where('purchase_order_id', $goodsservicereceipts->purchase_order_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $grand_total, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);
            }

            // set job counter
            $financial_year = Financialyear::where('active', 1)->first();
            $purchase_order_counter = 1;
            if ($financial_year) {
                $purchase_order_counter = $financial_year->purchase_order_counter + 1;
            }
            $bill_no = "EUREKA/" . $financial_year->year . "/" . $purchase_order_counter;

            $customer = BussinessPartnerMaster::where('business_partner_id', $goodsservicereceipts->party_id)->first();
            $party_name = "";
            if ($customer) {
                $party_name = $customer->name;
            }

            GoodsServiceReceipts::where('purchase_order_id', $goodsservicereceipts->purchase_order_id)->update(['purchase_order_counter' => $purchase_order_counter, 'bill_no' => $bill_no, 'party_name' => $party_name]);
            $financial_year->purchase_order_counter = $purchase_order_counter;
            $financial_year->save();

            Session::flash('message', 'GoodsServiceReceipts Added Successfully!');
            Session::flash('status', 'success');
            // return redirect('admin/goodsservicereceipts/view/'.$goodsservicereceipts->purchase_order_id.'?print=yes');
            return redirect('admin/goodsservicereceipts');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/goodsservicereceipts');
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

        $model = OrderFulfilment::with('goodsservicereceipts_items')->where('order_fulfillment_id', $id)->first();


        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $Financialyear = get_fy_year($model->company_id);

        $financial_year = Financialyear::where(['year' => $Financialyear, 'company_id' => $model->company_id])->first();


        $order_booking_counter = 1;
        $fyear = "";
        if (isset($financial_year)) {
            $order_booking_counter = $financial_year->order_booking_counter + 1;
            $fyear = $financial_year->year;
        }
        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id')->all();

        $purchaseorder = OrderFulfilment::latest()->pluck('bill_no', 'order_fulfillment_id');

        return view('backend.orderfulfilment.edit', compact('model', 'gst', 'party', 'financial_year', 'order_booking_counter', 'fyear', 'storage_locations', 'purchaseorder'));
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
            'party_id' => 'required',
            'bill_to' => 'required',
            'ship_from' => 'required',
            // 'place_of_supply' => 'required',
            'customer_ref_no' => 'required',
            // 'ship_from' => 'required',
            // 'posting_date' => 'required',
            'due_date' => 'required',
            'document_date' => 'required',
            'status' => 'required',
            'contact_person' => 'required',
        ]);
        $invoice_items_array = array();
        $current_invoice_items = array();


        $goodsservicereceipt = OrderFulfilment::where('order_fulfillment_id', $request->order_fulfillment_id)->first();
        // $goodsservicereceipt = GoodsServiceReceipts::findOrFail($id);

        if ($goodsservicereceipt) {
            $goodsservicereceipt->fill($request->all());
            // dd($goodsservicereceipt);
            //update Goods service Receipts
            if ($goodsservicereceipt->update()) {



                //save initial data
                $sub_total = $cgst_total = $sgst_utgst_total = $igst_total = $gst_grand_total = $grand_total = 0;
                $gst_rate = 0;

                //update old data

                //get old invoice items for
                $old_invoice_items  = OrderFulfilmentItems::where('order_fulfillment_id', $goodsservicereceipt->order_fulfillment_id)->get('order_fulfillment_item_id');
                //store ids in saparate array()

                if ($old_invoice_items && count($old_invoice_items) > 0) {
                    foreach ($old_invoice_items as $inv_item) {
                        // $invoice_items_array[] = $inv_item->goods_service_receipts_item_id;
                        array_push($invoice_items_array, $inv_item->order_fulfillment_item_id);
                    }
                }
                // dd($invoice_items_array);
                if (isset($request->old_invoice_items)) {

                    //save data of GoodsService Receipts
                    // $old_goodsservicereceipts_items = $request->old_invoice_items;
                    // dd($old_goodsservicereceipts_items);
                    $filteredItems = $request->old_invoice_items;
                    // dd($filteredItems);

                    $old_goodsservicereceipts_items = array_filter($filteredItems, function ($item) {
                        return $item['order_fulfillment_item_id'] !== null;
                    });

                    $new_goodsservicereceipts_items = array_filter($filteredItems, function ($item) {
                        return $item['order_fulfillment_item_id'] == null;
                    });

                    // //update inventory

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

                            $inventoryExist = Inventory::where([
                                'warehouse_id' => $row['storage_location_id'],
                                'bin_id' => $good_bin->bin_id,
                                'sku' => $product->sku,
                                'batch_no' => $batch_no,
                                // 'fy_year' => $goodsservicereceipt->fy_year,
                                'company_id' => $goodsservicereceipt->company_id,
                            ])->first();


                            // dd($inventoryExist, $goodsservicereceipt);
                            if (!empty($inventoryExist)) {
                                $inventoryExist->blocked_qty = $row['final_qty'];
                                $inventoryExist->save();
                            }
                        }
                    }




                    $purchase_order_id = $goodsservicereceipt->order_booking_id;
                    $total_inr = 0;

                    if (!empty($new_goodsservicereceipts_items)) {
                        foreach ($new_goodsservicereceipts_items as $item) {
                            $total_inr += $item['total'];
                            if ($item['item_name'] != '') {

                                $batch_no = $item['batch_no'] ?? '';
                                if (empty($batch_no) || $batch_no == null) {

                                    $check_for_batch = Inventory::where([
                                        'warehouse_id' => $item['storage_location_id'],
                                        'bin_id' => $good_bin->bin_id,
                                        'item_code' => $item['item_code'],
                                        // 'fy_year' => $goodsservicereceipt->fy_year,
                                        'company_id' => $goodsservicereceipt->company_id,
                                    ])->first();

                                    if (!empty($check_for_batch)) {
                                        $batch_no = $check_for_batch->batch_no;
                                    } else {
                                        DB::table('def_bacth_no_counter')->increment('counter');
                                        $counterValue = DB::table('def_bacth_no_counter')->value('counter');
                                        $batch_no = $counterValue . '-Batch-' . $item['item_code'];
                                    }
                                }

                                $goodsservicereceipts_item = new OrderFulfilmentItems();
                                $sku = Products::where('item_code', $item['item_code'])->first();
                                $goodsservicereceipts_item->sku = $sku->sku;
                                $goodsservicereceipts_item->fill($item);
                                $goodsservicereceipts_item->batch_no = $batch_no;
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
                                if ($gst_rate == 0) {
                                    $gst_rate = $goodsservicereceipts_item->cgst_rate + $goodsservicereceipts_item->sgst_utgst_rate + $goodsservicereceipts_item->igst_rate;
                                }


                                // batches
                                $goods_service_receipt_id = $goodsservicereceipt->order_fulfillment_id;
                                $purchase_order_item_id = $goodsservicereceipts_item->order_fulfillment_item_id;
                                $storage_location_id = $goodsservicereceipts_item->storage_location_id;
                            } //end of if
                        } ////end of foreach
                        $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
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

                        OrderFulfilment::where('order_fulfillment_id', $goodsservicereceipt->order_fulfillment_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);
                    }

                    foreach ($old_goodsservicereceipts_items as $old_item) {
                        $total_inr += $old_item['total'];

                        $batch_no = $old_item['batch_no'] ?? '';
                        if (empty($batch_no) || $batch_no == null) {

                            $check_for_batch = Inventory::where([
                                'warehouse_id' => $old_item['storage_location_id'],
                                'bin_id' => $good_bin->bin_id,
                                'item_code' => $old_item['item_code'],
                                // 'fy_year' => $goodsservicereceipt->fy_year,
                                'company_id' => $goodsservicereceipt->company_id,
                            ])->first();

                            if (!empty($check_for_batch)) {
                                $batch_no = $check_for_batch->batch_no;
                            } else {
                                DB::table('def_bacth_no_counter')->increment('counter');
                                $counterValue = DB::table('def_bacth_no_counter')->value('counter');
                                $batch_no = $counterValue . '-Batch-' . $old_item['item_code'];
                            }
                        }


                        // if (isset($old_item['goods_service_receipts_item_id']) && $old_item['goods_service_receipts_item_id'] != '') {

                        $old_goodsservicereceipts_item = OrderFulfilmentItems::where('order_fulfillment_item_id', $old_item['order_fulfillment_item_id'])->first();
                        $sku = Products::where('item_code', $old_item['item_code'])->first();
                        $old_goodsservicereceipts_item->sku = $sku->sku;
                        $old_goodsservicereceipts_item->fill($old_item);
                        $old_goodsservicereceipts_item->batch_no = $batch_no;
                        // dd($old_goodsservicereceipts_item->toArray());
                        $old_goodsservicereceipts_item->order_booking_id = $purchase_order_id;
                        $old_goodsservicereceipts_item->order_fulfillment_id = $request->order_fulfillment_id;
                        $old_goodsservicereceipts_item->save();
                        array_push($current_invoice_items, $old_goodsservicereceipts_item->order_fulfillment_item_id);
                        // dd($old_item);

                        //amount Calculation

                        $sub_total = $sub_total + ($old_item['taxable_amount'] * $old_goodsservicereceipts_item->qty);
                        $cgst_total = $cgst_total + $old_item['cgst_amount'];
                        $sgst_utgst_total = $sgst_utgst_total + $old_item['sgst_utgst_amount'];
                        $igst_total = $igst_total + $old_item['igst_amount'];
                        // dd($old_goodsservicereceipts_item->gst_rate);
                        // gst rate
                        $gst_rate = $old_goodsservicereceipts_item->gst_rate;
                        if (!empty($gst_rate)) {
                            $get_data = Gst::where('gst_id', $gst_rate)->first();
                            $gst_rate = $get_data->gst_percent;
                        }
                    }   //end of loop of old items
                    $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                    // dd($gst_grand_total);
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
                    // dd($sgst_utgst_total);
                    OrderFulfilment::where('order_booking_id', $goodsservicereceipt->order_booking_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);

                    //  dd($invoice_items_array,$current_invoice_items);
                    $invoice_difference = array_diff($invoice_items_array, $current_invoice_items);

                    if ($invoice_difference && count($invoice_difference) > 0) {
                        foreach ($invoice_difference as $inv_diff) {
                            $delete_invoice = OrderFulfilmentItems::where('order_fulfillment_item_id', $inv_diff)->first();
                            if ($delete_invoice) {
                                $delete_invoice->delete();
                            }
                        }
                    }
                } else {
                    foreach ($invoice_items_array as $inv_diff) {
                        $delete_invoice = OrderFulfilmentItems::where('order_fulfillment_item_id', $inv_diff)->first();
                        if ($delete_invoice) {
                            $delete_invoice->delete();
                        }
                    }
                }

                //end of invoice data
                //Old Item Repeater Data

                //insert new repeater data
                // dd($request->party_id);

            }
        }

        // get invoice amount
        $inv_amt = OrderFulfilmentItems::where('order_booking_id', $goodsservicereceipt->order_booking_id)->get();
        // dd($inv_amt->toArray());
        $totalAmount = array_sum(array_column($inv_amt->toArray(), 'total'));
        $discountAmt = $totalAmount * ($goodsservicereceipt->discount / 100);
        $totalAmount = $totalAmount - $discountAmt + $goodsservicereceipt->gst_grand_total;


        //compare quantity of po and gr
        $gr_quantity_arr = [];
        $po_quantity_arr = [];
        $gr_quantity_arr_final = [];
        $po_quantity_arr_final = [];

        $po_data = OrderBookingItems::where('order_booking_id', $goodsservicereceipt->order_booking_id)->get()->toArray();

        // dd($inv_amt->toArray() );
        foreach ($inv_amt->toArray() as $row) {
            $item_code = $row['item_code'];

            // Convert existing values to integers before addition
            $gr_quantity_arr[$item_code] = isset($gr_quantity_arr[$item_code]) ? (int)$gr_quantity_arr[$item_code] : 0;
            $gr_quantity_arr_final[$item_code] = isset($gr_quantity_arr_final[$item_code]) ? (int)$gr_quantity_arr_final[$item_code] : 0;

            if (array_key_exists($item_code, $gr_quantity_arr)) {
                $gr_quantity_arr[$item_code] += (int)$row['qty']; // Add the current value
                $gr_quantity_arr_final[$item_code] += (int)$row['final_qty']; // Add the current value
            } else {
                $gr_quantity_arr[$item_code] = (int)$row['qty']; // Initialize with the current value
                $gr_quantity_arr_final[$item_code] = (int)$row['final_qty']; // Initialize with the current value
            }
        }


        foreach ($po_data as $row) {
            $po_quantity_arr[$row['item_code']] = $row['qty'];
            $po_quantity_arr_final[$row['item_code']] = $row['final_qty'];
        }

        $totalQuant_gr = array_sum($gr_quantity_arr);
        $totalQuant_po = array_sum($po_quantity_arr);

        // dd($totalQuant_gr,$totalQuant_po);
        // dd($po_quantity_arr, $gr_quantity_arr);

        $difference_arr = [];
        $difference_arr_final = [];

        // Loop through each item code
        foreach ($po_quantity_arr as $item_code => $po_qty) {
            // If the item code exists in both arrays, subtract the quantities
            if (isset($gr_quantity_arr[$item_code])) {
                $difference = $po_qty - $gr_quantity_arr[$item_code];
            } else {
                // If the item code only exists in the purchase order array, set the difference as the quantity
                $difference = $po_qty;
            }

            // Add the difference to the result array
            $difference_arr[$item_code] = (string) $difference;
        }

        // Loop through each item code
        foreach ($po_quantity_arr_final as $item_code => $po_qty) {
            // If the item code exists in both arrays, subtract the quantities
            if (isset($gr_quantity_arr_final[$item_code])) {
                $difference = $po_qty - $gr_quantity_arr_final[$item_code];
            } else {
                // If the item code only exists in the purchase order array, set the difference as the quantity
                $difference = $po_qty;
            }

            // Add the difference to the result array
            $difference_arr_final[$item_code] = (string) $difference;
        }


        $existing_items = OrderFulfilmentItems::where('order_fulfillment_id', $goodsservicereceipt->order_fulfillment_id)->get();

        // dd($totalQuant_gr,$totalQuant_po, $goodsservicereceipt->is_gr_done);

        if ($totalQuant_gr != $totalQuant_po && $goodsservicereceipt->is_of_done == 0) {
            $this->create_remaining_of($goodsservicereceipt->order_booking_id, $goodsservicereceipt, $existing_items, $difference_arr, $difference_arr_final);
        }

        $remaining_quant = array_sum($difference_arr);
        // dd($remaining_quant);
        // if ($remaining_quant > 0) {
        //     $this->create_remaining_gr($goodsservicereceipt->purchase_order_id, $goodsservicereceipt, $existing_items, $difference_arr);
        // } else
        if ($remaining_quant == 0) {
            //update status
            $old_po = OrderBooking::where('order_booking_id', $goodsservicereceipt['order_booking_id'])->first();
            if (!empty($old_po)) {
                $old_po->status = 'close';
                $old_po->save();
            }
        }

        //update status
        $goodsservicereceipt->is_of_done = 1;
        $goodsservicereceipt->is_inventory_updated = 1;
        $goodsservicereceipt->save();

        if ($goodsservicereceipt->getChanges()) {
            $new_changes = $goodsservicereceipt->getChanges();
            $log = ['module' => 'Order Fulfilment', 'action' => 'Order Fulfilment Updated', 'description' => 'Order Fulfilment Updated: Name=>' . $goodsservicereceipt->bill_no];
            captureActivityupdate($new_changes, $log);
        }


        return redirect()->route('admin.orderfulfilment')->with('success', 'Details has Been Updated');
    }     //end of function


    public function create_remaining_of($id, $existing_data, $existing_items, $new_quantity, $final_qtys)
    {

        $update_status = OrderBooking::where('order_booking_id', $id)->first();
        $update_status->split_of = 1;
        $update_status->save();

        //get current module and get module id from modules table
        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $series_no = get_series_number($moduleName, $existing_data->company_id);
        if (empty($series_no)) {
            return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
        }

        $bp_master = BussinessPartnerMaster::where('business_partner_id', $existing_data->party_id)->first();
        $Financialyear = get_fy_year($bp_master->company_id);
        // set counter
        $financial_year = Financialyear::where(['year' => $Financialyear, 'company_id' => $bp_master->company_id])->first();
        $order_fulfilment_counter = 0;
        if ($financial_year) {
            $order_fulfilment_counter = $financial_year->order_fulfilment_counter + 1;
        }
        $bill_no = $series_no . '-' . $financial_year->year . "-" . $order_fulfilment_counter;
        $financial_year->order_fulfilment_counter = $order_fulfilment_counter;
        $financial_year->save();


        $existing_data = $existing_data->getAttributes();
        $goodsservicereceipt = new OrderFulfilment();
        unset($existing_data['order_fulfillment_id']);
        $goodsservicereceipt->fill($existing_data);
        $goodsservicereceipt->bill_no = $bill_no;
        $goodsservicereceipt->fy_year  = $Financialyear;
        $goodsservicereceipt->order_booking_id = $id;
        $goodsservicereceipt->customer_inv_no = '';


        $log = ['module' => 'Order Fulfilment', 'action' => 'Order Fulfilment Created', 'description' => 'Order Fulfilment Created: ' . $goodsservicereceipt->bill_no];
        captureActivity($log);

        foreach ($existing_items as $row) {
            // dd($row);
            $quant = $new_quantity[$row['item_code']] ?? '';
            $quant_final = $final_qtys[$row['item_code']] ?? '';
            if ($quant > 0) {
                $goodsservicereceipt->save();
                $order_fulfillment_id = $goodsservicereceipt->order_fulfillment_id;
                $existing_item_data = $row->getAttributes();
                unset($existing_item_data['fulfil_qty']);
                $goodsservicereceipt_items = new OrderFulfilmentItems();
                $goodsservicereceipt_items->fill($existing_item_data);
                $goodsservicereceipt_items->order_fulfillment_id = $order_fulfillment_id;
                $goodsservicereceipt_items->fulfil_qty = $row['qty'];
                $goodsservicereceipt_items->qty = $quant;
                $goodsservicereceipt_items->final_qty = $quant_final;
                $goodsservicereceipt_items->batch_no = $existing_item_data['batch_no'];
                $goodsservicereceipt_items->save();
            }
        }
    }

    public function createarinvoice($id)
    {

        $greceipt = OrderFulfilment::where('order_fulfillment_id', $id)->first();
        $moduleName = "A/R INVOICE";
        $series_no = get_series_number($moduleName, $greceipt->company_id);
        if (empty($series_no)) {
            return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
        }


        $greceipt->status = 'close';
        $greceipt->save();



        $apinvoice = ArInvoice::where('of_id', $greceipt->order_fulfillment_id)->first();
        // $apinvoice = ArInvoice::where('order_fulfillment_id', $id)->first();
        // dd($goods_receipt_exist->toArray());

        $bp_master = BussinessPartnerMaster::where('business_partner_id', $greceipt->party_id)->first();
        $company = Company::where('company_id', $bp_master->company_id)->first();

        $Financialyear = get_fy_year($bp_master->company_id);
        // set counter
        $financial_year = Financialyear::where(['year' => $Financialyear, 'company_id' => $bp_master->company_id])->first();
        $ar_invoice_counter = 0;
        if ($financial_year) {
            $ar_invoice_counter = $financial_year->ar_invoice_counter + 1;
        }
        $bill_no = $series_no . '-' . $financial_year->year . "-" . $ar_invoice_counter;
        $financial_year->ar_invoice_counter = $ar_invoice_counter;
        $financial_year->save();

        if (!isset($apinvoice)) {


            $goods_receipt = new ArInvoice();
            //$party = BussinessPartnerMaster::where('business_partner_id',$purchaseorder->party_id)->first();
            $properties = $greceipt->attributesToArray();
            // dd($properties)->toArray();
            // unset($properties['purchase_order_id']);//by mahesh for copy data
            $goods_receipt->document_date = $greceipt->document_date;
            $goods_receipt->fill($properties);
            $goods_receipt->of_id = $id;
            $goods_receipt->bill_no = $bill_no;
            $goods_receipt->status = 'close';

            $total_incentive_amount = 0;


            if ($goods_receipt->save()) {
                $purchaseorder_items = OrderFulfilmentItems::where('final_qty', '!=', '0')->where('order_fulfillment_id', $id)->get();
                if ($purchaseorder_items) {
                    foreach ($purchaseorder_items as $item) {
                        $product = Products::where('item_code', $item['item_code'])->first();
                        $incentives = Incentives::where(['product_id' => $product->product_item_id, 'month' => date('F')])->first();
                        if (!empty($incentives)) {
                            $total_incentive_amount += $item['final_qty'] * $incentives->amount;
                        }

                        $goods_receipt_items = new ArInvoiceItems();
                        //$party = BussinessPartnerMaster::where('business_partner_id',$purchaseorder->party_id)->first();
                        $properties_item = $item->attributesToArray();
                        unset($properties_item['order_fulfillment_item_id']);
                        $sku = Products::where('item_code', $item['item_code'])->first();
                        $goods_receipt_items->sku = $sku->sku;
                        $goods_receipt_items->fill($properties_item);
                        // dd($goods_receipt_items->toArray());
                        // dd($goods_receipt_items->gst_rate,$goods_receipt_items->purchase_order_id);
                        $goods_receipt_items->order_fulfillment_id = $goods_receipt->order_fulfillment_id;
                        $inserted = $goods_receipt_items->save();

                        if ($inserted) {
                            $gst_rate = $goods_receipt_items->gst_rate;
                            if (!empty($gst_rate)) {
                                $get_data = Gst::where('gst_id', $gst_rate)->first();
                                $gst_rate = $get_data->gst_percent;
                            }
                            ArInvoice::where('order_fulfillment_id', $goods_receipt_items->order_fulfillment_id)->update(['gst_rate' => $gst_rate]);
                        }
                    }

                    // update salesman incentives
                    $get_salesman = OrderBooking::where(['from_app' => 1, 'order_booking_id' => $greceipt->order_booking_id])->first();
                    if (!empty($get_salesman)) {
                        $salesman = $get_salesman->created_by;
                    }
                    if (!empty($salesman)) {
                        $get_previous_incentive_amt = DB::table('salesman_incentives')->where(['salesman' => $salesman, 'month' => date('F')])->first();
                        if (!empty($get_previous_incentive_amt)) {
                            $previous_incentive_amount = $get_previous_incentive_amt->amount;
                        } else {
                            $previous_incentive_amount = 0;
                        }

                        $new_incentive_amount = $previous_incentive_amount + $total_incentive_amount;

                        // Update salesman incentives table
                        DB::table('salesman_incentives')
                            ->where(['salesman' => $salesman, 'month' => date('F')])
                            ->update(['amount' => $new_incentive_amount]);
                    }

                    //update inventory

                    foreach ($purchaseorder_items as $row) {

                        $good_bin_type_id = Bintype::where('name', 'Good')->first();
                        $good_bin = BinManagement::where(['bin_type' => $good_bin_type_id->bin_type_id, 'warehouse_id' => $row['storage_location_id']])->first();


                        $batch_no = $row['batch_no'] ?? '';
                        if (empty($batch_no) || $batch_no == null) {

                            $check_for_batch = Inventory::where([
                                'warehouse_id' => $row['storage_location_id'],
                                'bin_id' => $good_bin->bin_id,
                                'item_code' => $row['item_code'],
                                // 'fy_year' => $goodsservicereceipt->fy_year,
                                'company_id' => $goods_receipt->company_id,
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
                                'company_id' => $goods_receipt->company_id,
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
                                'company_id' => $goods_receipt->company_id,
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
                                'doc_no' => $goods_receipt->bill_no,
                                'warehouse_id' => $row['storage_location_id'],
                                'bin_id' => optional($good_bin)->bin_id,
                                'batch_no' => $batch_no,
                                'item_code' => $row['item_code'],
                                'sku' => $product->sku,
                                'qty' => $inventoryExist->qty - $row['final_qty'],
                                'blocked_qty' => 0,
                                'fy_year' => $goods_receipt->fy_year,
                                'company_id' => $goods_receipt->company_id,
                                'user_id' => Auth()->guard('admin')->user()->admin_user_id,
                                'unit_price' => $row['taxable_amount'],
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
                                PerDayInventory::create($inventoryData);
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
                            $transactionHistory->updated_qty = - ($row['final_qty']);
                            $transactionHistory->final_qty = $base_quantity - $row['final_qty'];
                            $transactionHistory->sku = $product->sku;
                            $transactionHistory->unit_price = $row['taxable_amount'];
                            $transactionHistory->fill($transactionData);
                            $transactionHistory->save();

                            // update ledger
                            $partnerLedger = new Partnerledger();
                            $partnerLedger->party_id = $goods_receipt->party_id;
                            $partnerLedger->doc_no = $goods_receipt->ar_temp_no;
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
                }
            }

            $log = ['module' => 'A/R Invoice', 'action' => 'A/R Invoice Created', 'description' => 'A/R Invoice Created: ' . $goods_receipt->bill_no];
            captureActivity($log);


            Session::flash('message', 'A/R Invoice Created Successfully!');
            Session::flash('status', 'success');
            //   return redirect('admin/purchaseorder');
            return redirect()->route('admin.arinvoice.view', [$goods_receipt->order_fulfillment_id]);
        } else {
            // Session::flash('error', 'A/R Invoice already exists!');
            // Session::flash('status', 'error');
            // return redirect('admin/orderfulfilment');
            $ar_id = Arinvoice::where(['of_id' => $id])->first();
            // dd($ar_id);
            if (!empty($ar_id)) {
                return redirect('admin/arinvoice/view/' . $ar_id->order_fulfillment_id);
            } else {
                Session::flash('error', 'A/R Invoice already exists!');
                return redirect('admin/orderfulfilment');
            }
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
        // dd($id);
        $goodsservicereceipts = OrderFulfilment::findOrFail($id);
        $goodsservicereceipts_items = OrderFulfilmentItems::where('order_fulfillment_id', $id);


        // dd($goodsservicereceipts);

        if ($goodsservicereceipts->delete()) {
            $goodsservicereceipts_items->delete();

            $log = ['module' => 'Order Fulfilment', 'action' => 'Order Fulfilment Deleted', 'description' => 'Order Fulfilment Deleted: ' . $goodsservicereceipts->bill_no];
            captureActivity($log);

            Session::flash('message', 'Order Fulfilment Deleted Successfully!');
            Session::flash('status', 'success');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
        }

        return redirect('admin/orderfulfilment');
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

        $business_partner = BussinessPartnerMaster::where('business_partner_id', $business_partner_id)->first();

        $business_partner_address = BussinessPartnerAddress::where(['bussiness_partner_id' => $business_partner_id, 'address_type' => 'Bill-To/ Bill-From'])->first();

        // dd($business_partner_address);

        if ($business_partner && $business_partner_address) {
            $bill_to_state = $business_partner_address->state;
            $business_partner_detail = "<strong>" . $business_partner->bp_name . "</strong><br>";
            $business_partner_detail .= "<span>" . $business_partner_address->building_no_name . ' ' . $business_partner_address->street_name . ' ' .
                $business_partner_address->landmark . ' ' . $business_partner_address->city . ' ' . $business_partner_address->district .  "</span><br>";
            $business_partner_detail .= "<span>POS: Code & State: " . $business_partner_address->pin_code . ' ' . $business_partner_address->state . "</span><br>";
            $bill_to_gst_no .= $business_partner->gst_details;
            // $business_partner_state = $business_partner->state;
        }

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
        $data = Products::select(DB::raw("item_name as name"), 'product_item_id')->where("item_name", "LIKE", "%" . $query . "%")->get();
        //var_dump($data);exit;
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
