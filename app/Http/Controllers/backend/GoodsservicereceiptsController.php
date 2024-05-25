<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Models\backend\AdminUsers;
use App\Models\backend\Apinvoice;
use App\Models\backend\ApInvoiceBatches;
use App\Models\backend\ApInvoiceItems;
use App\Models\backend\BinManagement;
use App\Models\backend\Bintype;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerBankingDetails;
use Illuminate\Http\Request;
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
use App\Models\backend\PerDayInventory;
use App\Models\backend\PurchaseOrder;
use App\Models\backend\Products;
use App\Models\backend\PurchaseOrderItems;
use App\Models\backend\StorageLocations;
use App\Models\backend\Transaction;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class GoodsservicereceiptsController extends Controller
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
        $GLOBALS['breadcrumb'] = [['name' => 'GoodsServiceReceipts', 'route' => ""]];

        if ($request->ajax()) {
            if (session('company_id') != 0 && session('fy_year') != 0) {
                $po_ids = PurchaseOrder::where(['created_by' => Auth()->guard('admin')->user()->admin_user_id])->pluck('purchase_order_id');
                $goodsservicereceipts = GoodsServiceReceipts::whereIn('purchase_order_id', $po_ids)->where(['fy_year' => session('fy_year'), 'company_id' => session('company_id')])->with('get_partyname')->orderby('created_at', 'desc')->get();
            } else {
                $goodsservicereceipts = GoodsServiceReceipts::with('get_partyname')->orderby('created_at', 'desc')->get();
            }
            // dd($purchaseorder);

            return DataTables::of($goodsservicereceipts)
                ->addIndexColumn()
                ->addColumn('action', function ($goodsservicereceipts) {
                    $ap_inv_data = ApInvoice::where('gr_id', $goodsservicereceipts->goods_service_receipt_id)->first();

                    $actionBtn = '<div id="action_buttons">';
                    if (request()->user()->can('View Goods Receipt PO')) {
                        $actionBtn .= '<a href="' . route('admin.goodsservicereceipts.view', ['id' => $goodsservicereceipts->goods_service_receipt_id]) . '"
                    class="btn btn-sm btn-primary" title="View"><i class="feather icon-eye"></i></a> ';
                    }
                    // if (request()->user()->can('Clone Goods Receipt PO')) {
                    //     if ($goodsservicereceipts->is_inventory_updated && empty($ap_inv_data->ap_inv_no)) {
                    //         $actionBtn .= '<a href="' . route('admin.goodsservicereceipts.createapinvoice', ['id' => $goodsservicereceipts->goods_service_receipt_id]) . '
                    //  " class="btn btn-sm btn-info" title="Create A/P Invoice"   >
                    //  <i class="feather icon-plus"></i></a> ';
                    //     }
                    // }
                    if (request()->user()->can('Update Goods Receipt PO')) {
                        // dd("yes");
                        if (!$goodsservicereceipts->is_inventory_updated) {
                            $actionBtn .= '<a href="' . route('admin.goodsservicereceipts.edit', ['id' => $goodsservicereceipts->goods_service_receipt_id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                        }
                    }
                    if (request()->user()->can('Delete Goods Receipt PO')) {
                        $actionBtn .= '<a href="' . route('admin.goodsservicereceipts.delete', ['id' => $goodsservicereceipts->goods_service_receipt_id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a></div>';
                    }
                    return $actionBtn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.goodsservicereceipts.index');
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

        if (session('fy_year') != 0 && session('company_id') != 0) {
            $financial_year = Financialyear::where(['year' => session('fy_year'), 'company_id' => session('company_id')])->first();
            if (!$financial_year) {
                return redirect()->back()->with(['error' => 'Financial Year Not Active!']);
            }
        }
        $purchase_order_counter = 1;
        $fyear = "";
        if (isset($financial_year)) {
            $purchase_order_counter = $financial_year->purchase_order_counter + 1;
            $fyear = $financial_year->year;
        }
        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id')->all();

        $purchaseorder = PurchaseOrder::latest()->pluck('bill_no', 'purchase_order_id');

        return view('backend.goodsservicereceipts.create', compact('roles', 'party', 'purchase_order_counter', 'fyear', 'storage_locations', 'purchaseorder'));
    }


    public function show($id)
    {
        $GLOBALS['breadcrumb'] = [['name' => 'GoodsServiceReceipts', 'route' => "admin.goodsservicereceipts"], ['name' => 'View', 'route' => ""]];
        $roles = Role::pluck('name', 'id')->all();
        $goodsservicereceipts = GoodsServiceReceipts::where('goods_service_receipt_id', $id)->with('goodsservicereceipts_items')->with('get_ship_toaddress')->first();
        // dd($goodsservicereceipts->toArray());
        $party = BussinessPartnerMaster::where('business_partner_id', $goodsservicereceipts->party_id)->first();
        $bank_details = BussinessPartnerBankingDetails::where('bussiness_partner_id', $goodsservicereceipts->party_id)->first();
        $invoice = $goodsservicereceipts;
        // dd($invoice->toArray());
        // gst_rate

        return view('backend.goodsservicereceipts.show', compact('roles', 'bank_details', 'goodsservicereceipts', 'party', 'invoice'));
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


            $log = ['module' => 'GoodsServiceReceipts', 'action' => 'GoodsServiceReceipts Created', 'description' => 'GoodsServiceReceipts Created: ' . $goodsservicereceipts->bill_no];
            captureActivity($log);


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
            $data = $item->getpartnertype;

            return $data;
        })->mapWithKeys(function ($item) {
            return [$item['business_partner_id'] => $item['bp_name']];
        });

        $model = GoodsServiceReceipts::with('goodsservicereceipts_items')->where('goods_service_receipt_id', $id)->first();


        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $Financialyear = get_fy_year($model->company_id);

        $financial_year = Financialyear::where(['year' => $Financialyear, 'company_id' => $model->company_id])->first();


        $purchase_order_counter = 1;
        $fyear = "";
        if (isset($financial_year)) {
            $purchase_order_counter = $financial_year->purchase_order_counter + 1;
            $fyear = $financial_year->year;
        }
        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id')->all();

        $purchaseorder = PurchaseOrder::latest()->pluck('bill_no', 'purchase_order_id');

        return view('backend.goodsservicereceipts.edit', compact('model', 'gst', 'party', 'financial_year', 'purchase_order_counter', 'fyear', 'storage_locations', 'purchaseorder'));
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
            // 'place_of_supply' => 'required',
            'ship_from' => 'required',
            // 'posting_date' => 'required',
            'due_date' => 'required',
            'document_date' => 'required',
            'status' => 'required',
            'contact_person' => 'required',
        ]);
        $invoice_items_array = array();
        $current_invoice_items = array();


        $goodsservicereceipt = GoodsServiceReceipts::where('goods_service_receipt_id', $request->goods_service_receipt_id)->first();
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
                $old_invoice_items  = GoodsServiceReceiptsItems::where('goods_service_receipt_id', $goodsservicereceipt->goods_service_receipt_id)->get('goods_service_receipts_item_id');
                //store ids in saparate array()

                if ($old_invoice_items && count($old_invoice_items) > 0) {
                    foreach ($old_invoice_items as $inv_item) {
                        // $invoice_items_array[] = $inv_item->goods_service_receipts_item_id;
                        array_push($invoice_items_array, $inv_item->goods_service_receipts_item_id);
                    }
                }
                // dd($request->old_invoice_items);
                if (isset($request->old_invoice_items)) {

                    //save data of GoodsService Receipts
                    $filteredItems = $request->old_invoice_items;


                    $old_goodsservicereceipts_items = array_filter($filteredItems, function ($item) {
                        return $item['goods_service_receipts_item_id'] !== null;
                    });

                    $new_goodsservicereceipts_items = array_filter($filteredItems, function ($item) {
                        return $item['goods_service_receipts_item_id'] == null;
                    });


                    // dd($filteredItems);

                    // save data in inventory
                    foreach ($filteredItems as $row) {


                        $good_bin_type_id = BinType::where('name', 'Good')->first();
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

                        // dd( $good_bin);


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

                            $perday_inventoryExist = PerDayInventory::where([
                                'warehouse_id' => $row['storage_location_id'],
                                'bin_id' => $good_bin->bin_id,
                                'sku' => $product->sku,
                                'batch_no' => $batch_no,
                                'company_id' => $goodsservicereceipt->company_id,
                            ])
                                ->whereDate('created_at', Carbon::today())
                                ->first();


                            $inventory = Inventory::where(['batch_no' => $batch_no])->where('manufacturing_date', '!=', null)
                                ->orWhere('expiry_date', '!=', null)
                                ->first();


                            $inventoryData = [
                                'doc_no' => $goodsservicereceipt->bill_no,
                                'warehouse_id' => $row['storage_location_id'],
                                'bin_id' => optional($good_bin)->bin_id,
                                'batch_no' => $batch_no,
                                'item_code' => $row['item_code'],
                                'sku' => $product->sku,
                                'qty' => optional($inventoryExist)->qty + $row['final_qty'],
                                'fy_year' => $goodsservicereceipt->fy_year,
                                'company_id' => $goodsservicereceipt->company_id,
                                'user_id' => Auth()->guard('admin')->user()->admin_user_id,
                                'unit_price' => $row['taxable_amount'],
                                'manufacturing_date' => $row['manufacturing_date'] ?? optional($inventory)->manufacturing_date ?? '',
                                'expiry_date' => $row['expiry_date'] ?? optional($inventory)->expiry_date ?? '',
                            ];
                            $base_quantity = optional($inventoryExist)->qty ?? 0;

                            // Check if $inventoryExist is null before deciding to update or create
                            if ($inventoryExist === null) {
                                Inventory::create($inventoryData);
                            } else {
                                $inventoryExist->update($inventoryData);
                            }

                            // dd($inventoryData);

                            if ($perday_inventoryExist === null) {
                                PerDayInventory::create($inventoryData);
                            } else {
                                $perday_inventoryExist->update($inventoryData);
                            }

                            // Use the same data for Transaction
                            $transactionData = $inventoryData;
                            unset($transactionData['qty']);

                            $routeName = Route::currentRouteName();
                            $moduleName = explode('.', $routeName)[1] ?? null;
                            $series_no = get_series_number($moduleName, $goodsservicereceipt->company_id);
                            $transaction_type = get_transaction_type($moduleName, $goodsservicereceipt->company_id);
                            if (empty($series_no)) {
                                return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
                            }


                            $transactionHistory = new Transaction();
                            $transactionHistory->transaction_type =  $transaction_type;
                            $transactionHistory->qty =  $base_quantity;
                            $transactionHistory->updated_qty = $row['final_qty'];
                            $transactionHistory->final_qty = $base_quantity + $row['final_qty'];
                            $transactionHistory->sku = $product->sku;
                            $transactionHistory->fill($transactionData);
                            $transactionHistory->save();
                        }
                    }



                    $purchase_order_id = $goodsservicereceipt->purchase_order_id;
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

                                $goodsservicereceipts_item = new GoodsServiceReceiptsItems();
                                $sku = Products::where('item_code', $item['item_code'])->first();
                                $goodsservicereceipts_item->sku = $sku->sku;
                                $goodsservicereceipts_item->fill($item);
                                $goodsservicereceipts_item->batch_no = $batch_no;
                                $goodsservicereceipts_item->purchase_order_id = $purchase_order_id;
                                $goodsservicereceipts_item->goods_service_receipt_id = $request->goods_service_receipt_id;
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

                        GoodsServiceReceipts::where('purchase_order_id', $goodsservicereceipt->purchase_order_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);
                    }


                    // dd($old_goodsservicereceipts_items);
                    foreach ($old_goodsservicereceipts_items as $old_item) {
                        $total_inr += $old_item['total'];

                        // if (isset($old_item['goods_service_receipts_item_id']) && $old_item['goods_service_receipts_item_id'] != '') {

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

                        $old_goodsservicereceipts_item = GoodsServiceReceiptsItems::where('goods_service_receipts_item_id', $old_item['goods_service_receipts_item_id'])->first();
                        // dd($old_goodsservicereceipts_item->toArray(),$old_item);
                        $sku = Products::where('item_code', $old_item['item_code'])->first();
                        $old_goodsservicereceipts_item->sku = $sku->sku;
                        $old_goodsservicereceipts_item->fill($old_item);
                        $old_goodsservicereceipts_item->batch_no = $batch_no;
                        $old_goodsservicereceipts_item->purchase_order_id = $purchase_order_id;
                        $old_goodsservicereceipts_item->goods_service_receipt_id = $request->goods_service_receipt_id;
                        $old_goodsservicereceipts_item->save();
                        array_push($current_invoice_items, $old_goodsservicereceipts_item->goods_service_receipts_item_id);
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
                    GoodsServiceReceipts::where('purchase_order_id', $goodsservicereceipt->purchase_order_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);


                    $invoice_difference = array_diff($invoice_items_array, $current_invoice_items);

                    if ($invoice_difference && count($invoice_difference) > 0) {
                        foreach ($invoice_difference as $inv_diff) {
                            $delete_invoice = GoodsServiceReceiptsItems::where('goods_service_receipts_item_id', $inv_diff)->first();
                            if ($delete_invoice) {
                                $delete_invoice->delete();
                            }
                        }
                    }
                } else {
                    foreach ($invoice_items_array as $inv_diff) {
                        $delete_invoice = GoodsServiceReceiptsItems::where('goods_service_receipts_item_id', $inv_diff)->first();
                        if ($delete_invoice) {
                            $delete_invoice->delete();
                        }
                    }
                }
            }
        }

        // get invoice amount
        $inv_amt = GoodsServiceReceiptsItems::where('purchase_order_id', $goodsservicereceipt->purchase_order_id)->get();
        // dd($inv_amt->toArray());
        $totalAmount = array_sum(array_column($inv_amt->toArray(), 'total'));
        $discountAmt = $totalAmount * ($goodsservicereceipt->discount / 100);
        $totalAmount = $totalAmount - $discountAmt + $goodsservicereceipt->gst_grand_total;



        //compare quantity of po and gr
        $gr_quantity_arr = [];
        $po_quantity_arr = [];
        $po_quantity_arr_final = [];
        $gr_quantity_arr_final = [];

        $po_data = PurchaseOrderItems::where('purchase_order_id', $goodsservicereceipt->purchase_order_id)->get()->toArray();

        // dd($inv_amt->toArray() );
        foreach ($inv_amt->toArray() as $row) {
            // $gr_quantity_arr[$row['item_code']] = $row['qty'];
            $item_code = $row['item_code'];

            if (array_key_exists($item_code, $gr_quantity_arr)) {
                $gr_quantity_arr[$item_code] = (string)($gr_quantity_arr[$item_code] + $row['qty']);
                $gr_quantity_arr_final[$item_code] = (string)($gr_quantity_arr_final[$item_code] + $row['final_qty']);
            } else {
                $gr_quantity_arr[$item_code] = (string)$row['qty'];
                $gr_quantity_arr_final[$item_code] = (string)$row['final_qty'];
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


        $existing_items = GoodsServiceReceiptsItems::where('goods_service_receipt_id', $goodsservicereceipt->goods_service_receipt_id)->get();

        // dd($totalQuant_gr,$totalQuant_po, $goodsservicereceipt->is_gr_done);

        if ($totalQuant_gr != $totalQuant_po && $goodsservicereceipt->is_gr_done == 0) {
            $this->create_remaining_gr($goodsservicereceipt->purchase_order_id, $goodsservicereceipt, $existing_items, $difference_arr, $difference_arr_final);
        }

        $remaining_quant = array_sum($difference_arr);
        // dd($remaining_quant);
        // if ($remaining_quant > 0) {
        //     $this->create_remaining_gr($goodsservicereceipt->purchase_order_id, $goodsservicereceipt, $existing_items, $difference_arr);
        // } else
        if ($remaining_quant == 0) {
            //update status
            $old_po = PurchaseOrder::where('purchase_order_id', $goodsservicereceipt['purchase_order_id'])->first();
            $old_po->status = 'close';
            $old_po->save();
        }

        //update status
        $update_data = GoodsServiceReceipts::where('goods_service_receipt_id', $goodsservicereceipt['goods_service_receipt_id'])->first();
        $update_data->is_gr_done = 1;
        $update_data->is_inventory_updated = 1;
        $update_data->status = 'close';
        $update_data->save();



        // dd($request->all());
        // dd('out');
        // return back to edit page
        if ($goodsservicereceipt->getChanges()) {
            $new_changes = $goodsservicereceipt->getChanges();
            $log = ['module' => 'GoodsServiceReceipts', 'action' => 'GoodsServiceReceipts Updated', 'description' => 'GoodsServiceReceipts Updated: Name=>' . $goodsservicereceipt->bill_no];
            captureActivityupdate($new_changes, $log);
        }

        return redirect()->route('admin.goodsservicereceipts')->with('success', 'Details has Been Updated');
    }     //end of function

    public function create_remaining_gr($id, $existing_data, $existing_items, $new_quantity, $final_qtys)
    {

        // dd($new_quantity,$final_qtys);
        // set job counter

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
        $goods_servie_receipt_counter = 0;
        if ($financial_year) {
            $goods_servie_receipt_counter = $financial_year->goods_servie_receipt_counter + 1;
        }
        $bill_no = $series_no . '-' . $financial_year->year . "-" . $goods_servie_receipt_counter;
        $financial_year->goods_servie_receipt_counter = $goods_servie_receipt_counter;
        $financial_year->save();


        $existing_data = $existing_data->getAttributes();
        $goodsservicereceipt = new GoodsServiceReceipts();
        $goodsservicereceipt->fill($existing_data);
        $goodsservicereceipt->bill_no = $bill_no;
        $goodsservicereceipt->fy_year  = $Financialyear;
        $goodsservicereceipt->purchase_order_id = $id;
        $goodsservicereceipt->vendor_inv_no = '';


        $log = ['module' => 'GoodsServiceReceipts', 'action' => 'GoodsServiceReceipts Created', 'description' => 'GoodsServiceReceipts Created: ' . $goodsservicereceipt->bill_no];
        captureActivity($log);

        foreach ($existing_items as $row) {
            // dd($row);
            $quant = $new_quantity[$row['item_code']] ?? '';
            $quant_final = $final_qtys[$row['item_code']] ?? '';
            if ($quant > 0) {
                $goodsservicereceipt->save();

                $goods_service_receipt_id = $goodsservicereceipt->goods_service_receipt_id;
                $existing_item_data = $row->getAttributes();
                $goodsservicereceipt_items = new GoodsServiceReceiptsItems();
                $goodsservicereceipt_items->fill($existing_item_data);
                $goodsservicereceipt_items->goods_service_receipt_id = $goods_service_receipt_id;
                $goodsservicereceipt_items->qty = $quant;
                $goodsservicereceipt_items->final_qty = $quant_final;
                $goodsservicereceipt_items->batch_no = $existing_item_data['batch_no'];
                $goodsservicereceipt_items->manufacturing_date = $existing_item_data['manufacturing_date'];
                $goodsservicereceipt_items->expiry_date = $existing_item_data['expiry_date'];
                $goodsservicereceipt_items->save();
            }
        }
    }

    public function createapinvoice($id)
    {




        $roles = Role::pluck('name', 'id')->all();
        $greceipt = GoodsServiceReceipts::where('goods_service_receipt_id', $id)->first();
        // dd($purchaseorder->toArray());

        $moduleName = "A/P Invoice";
        $series_no = get_series_number($moduleName, $greceipt->company_id);
        if (empty($series_no)) {
            return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
        }


        $apinvoice = Apinvoice::where('vendor_inv_no', $greceipt->vendor_inv_no)->first();
        // dd($goods_receipt_exist->toArray());

        // set counter
        $bp_master = BussinessPartnerMaster::where('business_partner_id', $greceipt->party_id)->first();
        $Financialyear = get_fy_year($bp_master->company_id);
        $financial_year = Financialyear::where(['year' => $Financialyear, 'company_id' => $bp_master->company_id])->first();
        $ap_invoice_counter = 0;
        if ($financial_year) {
            $ap_invoice_counter = $financial_year->ap_invoice_counter + 1;
        }
        $bill_no = $series_no . '-' . $financial_year->year . "-" . $ap_invoice_counter;
        $financial_year->ap_invoice_counter = $ap_invoice_counter;
        $financial_year->save();

        if (!isset($apinvoice)) {
            $goods_receipt = new Apinvoice();
            //$party = BussinessPartnerMaster::where('business_partner_id',$purchaseorder->party_id)->first();
            $properties = $greceipt->attributesToArray();
            // dd($properties)->toArray();
            // unset($properties['purchase_order_id']);//by mahesh for copy data
            $goods_receipt->document_date = $greceipt->document_date;
            $goods_receipt->fill($properties);
            $goods_receipt->gr_id = $id;
            $goods_receipt->bill_no = $bill_no;
            // dd($goods_receipt);
            if ($goods_receipt->save()) {
                $purchaseorder_items = GoodsServiceReceiptsItems::where('goods_service_receipt_id', $id)->get();
                if ($purchaseorder_items) {
                    foreach ($purchaseorder_items as $item) {
                        $goods_receipt_items = new ApInvoiceItems();
                        //$party = BussinessPartnerMaster::where('business_partner_id',$purchaseorder->party_id)->first();
                        $properties_item = $item->attributesToArray();
                        unset($properties_item['goods_service_receipts_item_id']);
                        $sku = Products::where('item_code', $item['item_code'])->first();
                        $goods_receipt_items->sku = $sku->sku;
                        $goods_receipt_items->fill($properties_item);
                        // dd($goods_receipt_items->toArray());
                        // dd($goods_receipt_items->gst_rate,$goods_receipt_items->purchase_order_id);
                        $goods_receipt_items->goods_service_receipt_id = $goods_receipt->goods_service_receipt_id;
                        $inserted = $goods_receipt_items->save();

                        if ($inserted) {
                            $gst_rate = $goods_receipt_items->gst_rate;
                            // dd($gst_rate);
                            if (!empty($gst_rate)) {
                                $get_data = Gst::where('gst_id', $gst_rate)->first();
                                $gst_rate = isset($get_data->gst_percent) ? $get_data->gst_percent : 18;
                            }
                            Apinvoice::where('purchase_order_id', $goods_receipt_items->goods_service_receipt_id)->update(['gst_rate' => $gst_rate]);


                            //add batches
                            foreach ($item->goods_service_receipts_batches as $secondary_item) {
                                $goods_receipt_item_bacthes = new ApInvoiceBatches();
                                $properties_item_baches = $secondary_item->attributesToArray();
                                unset($properties_item_baches['goods_service_receipts_batches_id']);
                                $goods_receipt_item_bacthes->fill($properties_item_baches);
                                $goods_receipt_item_bacthes->goods_service_receipt_id = $goods_receipt->goods_service_receipt_id;
                                $goods_receipt_item_bacthes->goods_service_receipts_item_id = $goods_receipt_items->goods_service_receipts_item_id;
                                $goods_receipt_item_bacthes->save();
                            }
                        }
                    }
                }
            }


            $log = ['module' => 'A/P Invoice', 'action' => 'A/P Invoice Created', 'description' => 'A/P Invoice Created: ' . $goods_receipt->bill_no];
            captureActivity($log);


            Session::flash('message', 'A/P Invoice Created Successfully!');
            Session::flash('status', 'success');
            //   return redirect('admin/purchaseorder');
            return redirect()->route('admin.apinvoice.edit', [$goods_receipt->goods_service_receipt_id]);
        } else {
            // Session::flash('error', 'A/P Invoice already exists!');
            // Session::flash('status', 'error');
            // return redirect('admin/goodsservicereceipts');
            $ap_id = Apinvoice::where(['goods_service_receipt_id' => $id])->first();
            // dd($id);
            if (!empty($ap_id)) {
                return redirect('admin/apinvoice/edit/' . $ap_id->gr_id);
            } else {
                Session::flash('error', 'A/P Invoice already exists!');
                return redirect('admin/goodsservicereceipts');
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
        $goodsservicereceipts = GoodsServiceReceipts::findOrFail($id);
        $goodsservicereceipts_items = GoodsServiceReceiptsItems::where('goods_service_receipt_id', $id);
        $goodsservicereceipts_batches = GoodsServiceReceiptsBatches::where('goods_service_receipt_id', $id);

        // dd($goodsservicereceipts);

        if ($goodsservicereceipts->delete()) {
            $goodsservicereceipts_items->delete();
            $goodsservicereceipts_batches->delete();

            $log = ['module' => 'GoodsServiceReceipts', 'action' => 'GoodsServiceReceipts Deleted', 'description' => 'GoodsServiceReceipts Deleted: ' . $goodsservicereceipts->bill_no];
            captureActivity($log);

            Session::flash('message', 'GoodsServiceReceipts Deleted Successfully!');
            Session::flash('status', 'success');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
        }

        return redirect('admin/goodsservicereceipts');
    }


    public function amountinwords($number = 0)
    {
        return amount_in_words($number);
    }



    public function partydetails($business_partner_id)
    {
        $business_partner = BussinessPartnerMaster::where('business_partner_id', $business_partner_id)->first();

        // $comapny_data = Company::first();
        $comapny_data = Company::where('company_id', $business_partner->company_id)->first();
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
            $pdf = PDF::loadView('backend.goodsservicereceipts.goodsservicereceipts_format_split', [
                'roles' => $roles,
                'goodsservicereceipts' => $goodsservicereceipts,
                'party' => $party,
                'download' => true,
            ]);
        } else {
            $pdf = PDF::loadView('backend.goodsservicereceipts.invoice_format', [
                'roles' => $roles,
                'goodsservicereceipts' => $goodsservicereceipts,
                'party' => $party,
                'download' => true,
            ]);
        }

        return $pdf->stream($filename . '.pdf');
    }
}
