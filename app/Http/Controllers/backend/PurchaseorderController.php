<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\AdminUsers;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\BussinessPartnerContactDetails;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\backend\PurchaseOrder;
use App\Models\backend\PurchaseOrderItems;
use App\Models\backend\Financialyear;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\BussinessPartnerOrganizationType;
use App\Models\backend\Company;
use App\Models\backend\Products;
use App\Models\backend\GoodsServiceReceipts;
use App\Models\backend\GoodsServiceReceiptsBatches;
use App\Models\backend\GoodsServiceReceiptsItems;
use App\Models\backend\Gst;
use App\Models\backend\PurchaseOrderBatches;
use App\Models\backend\SeriesMaster;
use App\Models\backend\StorageLocations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\PDF;

class PurchaseorderController extends Controller
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
        $GLOBALS['breadcrumb'] = [['name' => 'PurchaseOrder', 'route' => ""]];

        if ($request->ajax()) {
            if (session('company_id') != 0 && session('fy_year') != 0) {
                $purchaseorder = PurchaseOrder::where(['created_by' => Auth()->guard('admin')->user()->admin_user_id, 'status' => 'open', 'fy_year' => session('fy_year'), 'company_id' => session('company_id')])->with('get_partyname')->orderby('created_at', 'desc')->get();
            } else {
                $purchaseorder = PurchaseOrder::where(['status' => 'open'])->with('get_partyname')->orderby('created_at', 'desc')->get();
            }
            // dd($purchaseorder);

            return DataTables::of($purchaseorder)
                ->addIndexColumn()
                ->addColumn('action', function ($purchaseorder) {
                    $actionBtn = '<div id="action_buttons">';
                    if (request()->user()->can('View Purchase Order')) {
                        $actionBtn .= '<a href="' . route('admin.purchaseorder.view', ['id' => $purchaseorder->purchase_order_id]) . '"
                    class="btn btn-sm btn-primary" title="View"><i class="feather icon-eye"></i></a> ';
                    }
                    $is_any_of_exist = GoodsServiceReceipts::where('purchase_order_id', $purchaseorder->purchase_order_id)->first();

                    if (request()->user()->can('Clone Purchase Order')) {
                        if (empty($is_any_of_exist)) {
                            $actionBtn .= '<a href="' . route('admin.purchaseorder.creategr', ['id' => $purchaseorder->purchase_order_id]) . '
                     " class="btn btn-sm btn-info" title="Create GR" >
                     <i class="feather icon-plus"></i></a> ';
                        }
                    }
                    if (request()->user()->can('Update Purchase Order') && empty($is_any_of_exist)) {
                        // dd("yes");
                        $actionBtn .= '<a href="' . route('admin.purchaseorder.edit', ['id' => $purchaseorder->purchase_order_id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                    }
                    if (request()->user()->can('Delete Purchase Order')) {
                        $actionBtn .= '<a href="' . route('admin.purchaseorder.delete', ['id' => $purchaseorder->purchase_order_id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a></div>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // dd($purchaseorder->toArray());
        return view('backend.purchaseorder.index');

        // apply @can on edit button

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        $GLOBALS['breadcrumb'] = [['name' => 'PurchaseOrder', 'route' => "admin.purchaseorder"], ['name' => 'Create', 'route' => ""]];

        $roles = Role::pluck('name', 'id')->all();
        $gsts = Gst::pluck('gst_name', 'gst_id')->all();
        $party = BussinessPartnerMaster::when(session('company_id') != 0, function ($query) {
            return $query->where('company_id', session('company_id'));
        })
            ->with('getpartnertype')
            ->get();

        // $contac_data = BussinessPartnerContactDetails::get('contact_details_id','contact_person');

        $party = $party->filter(function ($item) {
            $data = $item->getpartnertype;

            return $data;
        })->mapWithKeys(function ($item) {
            return [$item['business_partner_id'] => $item['bp_name']];
        });
        // dd($party);


        // $lastPoRecord = PurchaseOrder::latest('purchase_order_id')->first();
        // $lastPoRecordId = $lastPoRecord->purchase_order_id;
        if (session('fy_year') != 0 && session('company_id') != 0) {
            $financial_year = Financialyear::where(['year' => session('fy_year'), 'company_id' => session('company_id')])->first();
            if (!$financial_year) {
                Session::flash('message', 'Financial Year Not Active!');
                Session::flash('status', 'error');
                return redirect()->back();
            }
        }
        $latestPoRecordNumber = 0;
        $moduleName = "Purchase Order";
        $series_no = get_series_number($moduleName);
        $purchase_order_counter = 1;
        $fyear = "";
        if (isset($financial_year)) {
            $purchase_order_counter = $financial_year->purchase_order_counter + 1;
            $latestPoRecordNumber = $series_no . '-' . $financial_year->year . "-" . $purchase_order_counter;
        }

        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id')->all();
        $gst = Gst::pluck('gst_name', 'gst_id');
        return view('backend.purchaseorder.create', compact('roles', 'gst', 'series_no', 'storage_locations', 'party', 'gsts', 'latestPoRecordNumber'));
    }


    public function show($id)
    {

        $GLOBALS['breadcrumb'] = [['name' => 'PurchaseOrder', 'route' => "admin.purchaseorder"], ['name' => 'View', 'route' => ""]];
        $roles = Role::pluck('name', 'id')->all();
        $purchaseorder = PurchaseOrder::where('purchase_order_id', $id)->with('purchaseorder_items')->with('get_ship_toaddress')->first();
        $party = BussinessPartnerMaster::where('business_partner_id', $purchaseorder->party_id)->first();
        $bank_details = BussinessPartnerBankingDetails::where('bussiness_partner_id', $purchaseorder->party_id)->first();
        $invoice = $purchaseorder;
        // dd($purchaseorder->toArray());

        return view('backend.purchaseorder.show', compact('roles', 'bank_details', 'purchaseorder', 'party', 'invoice'));
    }

    public function creategr($id)
    {
        $roles = Role::pluck('name', 'id')->all();
        $purchaseorder = PurchaseOrder::where('purchase_order_id', $id)->first();
        // dd($purchaseorder->purchase_order_batches->toArray());
        $goods_receipt_exist = GoodsServiceReceipts::where('purchase_order_id', $id)->first();
        // dd($goods_receipt_exist->toArray());
        if (!isset($goods_receipt_exist)) {
            $goods_receipt = new GoodsServiceReceipts();
            //$party = BussinessPartnerMaster::where('business_partner_id',$purchaseorder->party_id)->first();
            $properties = $purchaseorder->attributesToArray();
            // dd($properties)->toArray();
            // unset($properties['purchase_order_id']);//by mahesh for copy data
            $goods_receipt->due_date = $purchaseorder->delivery_date;
            $goods_receipt->fill($properties);


            //set counter and doc number for new gr
            $moduleName = "Goods Service Receipts";
            $series_no = get_series_number($moduleName, $purchaseorder->company_id);
            if (empty($series_no)) {
                return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
            }

            $bp_master = BussinessPartnerMaster::where('business_partner_id', $purchaseorder->party_id)->first();
            $Financialyear = get_fy_year($bp_master->company_id);
            $financial_year = Financialyear::where(['year' => $Financialyear, 'company_id' => $bp_master->company_id])->first();
            $goods_servie_receipt_counter = 0;
            if ($financial_year) {
                $goods_servie_receipt_counter = $financial_year->goods_servie_receipt_counter + 1;
            }
            $bill_no = $series_no . '-' . $financial_year->year . "-" . $goods_servie_receipt_counter;
            $goods_receipt->bill_no  = $bill_no;
            $financial_year->goods_servie_receipt_counter = $goods_servie_receipt_counter;
            $financial_year->save();

            if ($goods_receipt->save()) {
                $purchaseorder_items = PurchaseOrderItems::where('purchase_order_id', $id)->get();
                if ($purchaseorder_items) {
                    foreach ($purchaseorder_items as $item) {
                        $goods_receipt_items = new GoodsServiceReceiptsItems();
                        $properties_item = $item->attributesToArray();
                        unset($properties_item['purchase_order_item_id']);
                        $sku = Products::where('item_code', $item['item_code'])->first();
                        $goods_receipt_items->sku = $sku->sku ?? '';
                        $goods_receipt_items->fill($properties_item);
                        $goods_receipt_items->goods_service_receipt_id = $goods_receipt->goods_service_receipt_id;
                        $inserted = $goods_receipt_items->save();

                        if ($inserted) {

                            $gst_rate = $goods_receipt_items->gst_rate;
                            if (!empty($gst_rate)) {
                                $get_data = Gst::where('gst_id', $gst_rate)->first();
                                $gst_rate = $get_data->gst_percent;
                            }
                            GoodsServiceReceipts::where('purchase_order_id', $goods_receipt_items->purchase_order_id)->update(['gst_rate' => $gst_rate]);

                            //add batches

                            // foreach ($item->purchase_order_batches as $secondary_item) {
                            //     $goods_receipt_item_bacthes = new GoodsServiceReceiptsBatches();
                            //     // dd($secondary_item->toArray());
                            //     $properties_item_baches = $secondary_item->attributesToArray();
                            //     unset($properties_item_baches['purchase_order_batches_id']);
                            //     $goods_receipt_item_bacthes->fill($properties_item_baches);
                            //     $goods_receipt_item_bacthes->goods_service_receipt_id = $goods_receipt->goods_service_receipt_id;
                            //     $goods_receipt_item_bacthes->goods_service_receipts_item_id = $goods_receipt_items->goods_service_receipts_item_id;
                            //     $goods_receipt_item_bacthes->save();
                            // }
                        }
                    }
                }
            }


            $log = ['module' => 'GoodsServiceReceipts', 'action' => 'GoodsServiceReceipts Created', 'description' => 'GoodsServiceReceipts Created: ' . $goods_receipt->bill_no];
            captureActivity($log);


            // Session::flash('message', 'GR Receipt Created Successfully!');
            Session::flash('status', 'success');
            //   return redirect('admin/purchaseorder');
            return redirect()->route('admin.goodsservicereceipts.edit', [$goods_receipt->goods_service_receipt_id]);
        } else {
            // Session::flash('error', 'GR Receipt already exists!');
            // Session::flash('status', 'error');
            $gr_id = GoodsServiceReceipts::where(['purchase_order_id' => $id, 'is_gr_done' => 0])->first();
            // dd($gr_id);
            if (!empty($gr_id)) {
                return redirect('admin/goodsservicereceipts/edit/' . $gr_id->goods_service_receipt_id);
            } else {
                Session::flash('error', 'No Remaining GR exists!');
                return redirect('admin/purchaseorder');
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $dynamicRowCount = !empty($request->invoice_items) ? count($request->invoice_items) : 0;

        $this->validate($request, [
            // 'receipt_type' => 'required',
            'party_id' => 'required',
            'vendor_ref_no' => 'required',
            'ship_from' => 'required',
            // 'posting_date' => 'required',
            'delivery_date' => 'required',
            'document_date' => 'required',
            'status' => 'required',
            'contact_person' => 'required',
        ]);

        // usama_12-03-2024-fetch fy_year first
        $bp_master = BussinessPartnerMaster::where('business_partner_id', $request->party_id)->first();
        $Financialyear = get_fy_year($bp_master->company_id);
        // dd($request->all());
        $purchaseorder = new PurchaseOrder();
        $purchaseorder->fill($request->all());
        $purchaseorder->fy_year = $Financialyear;
        $purchaseorder->company_id = $bp_master->company_id;
        $purchaseorder->created_by = Auth()->guard('admin')->user()->admin_user_id;

        //get current module and get module id from modules table
        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $series_no = get_series_number($moduleName, $bp_master->company_id);
        if (empty($series_no)) {
            return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
        }



        if ($purchaseorder->save()) {
            // dd($purchaseorder->toArray());
            $sub_total = $cgst_total = $sgst_utgst_total = $igst_total = $gst_grand_total = $grand_total = 0;
            $gst_rate = 0;
            // store purchaseorder items
            // dd($request->all());
            if (isset($request->invoice_items)) {
                $purchaseorder_items = $request->invoice_items;
                $purchase_order_id = $purchaseorder->purchase_order_id;
                $total_inr = 0;
                foreach ($purchaseorder_items as $item) {
                    // dd($item);
                    $total_inr += (int) $item['total'];
                    $purchaseorder_item = new PurchaseOrderItems();
                    $sku = Products::where('item_code', $item['item_code'])->first();
                    $purchaseorder_item->sku = $sku->sku ?? '';
                    $purchaseorder_item->fill($item);
                    $purchaseorder_item->purchase_order_id = $purchase_order_id;

                    $purchaseorder_item->save();

                    //amount calculation
                    // dd($request->discount);
                    $sub_total = $sub_total + ($item['taxable_amount'] * $purchaseorder_item->qty);


                    $cgst_total = $cgst_total + $item['cgst_amount'];
                    $sgst_utgst_total = $sgst_utgst_total + $item['sgst_utgst_amount'];
                    $igst_total = $igst_total + $item['igst_amount'];

                    // gst rate
                    // dd($purchaseorder_item->cgst_rate,$purchaseorder_item->sgst_utgst_rate,$purchaseorder_item->igst_rate);
                    // if ($gst_rate == 0) {
                    //   $gst_rate = $purchaseorder_item->cgst_rate + $purchaseorder_item->sgst_utgst_rate + $purchaseorder_item->igst_rate;
                    // }
                    $gst_rate = $purchaseorder_item->gst_rate;
                    if (!empty($gst_rate)) {
                        $get_data = Gst::where('gst_id', $gst_rate)->first();
                        $gst_rate = $get_data->gst_percent;
                    }

                    // dd($gst_rate);
                    //save batches
                    $purchase_order_id = $purchaseorder->purchase_order_id;
                }
                $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                $gst_grand_total_rounded = $gst_grand_total;
                $final_amt = 0;
                // $grand_total = $sub_total + $gst_grand_total_rounded;
                if (!empty($request->discount)) {
                    $discount = ($total_inr * $request->discount) / 100;
                    // dd($discount);
                    $final_amt = round(($total_inr + $gst_grand_total_rounded) - $discount);
                } else {
                    $final_amt = round($total_inr + $gst_grand_total_rounded);
                }
                // dd($grand_total);
                $rounded_off = round(($gst_grand_total_rounded - $gst_grand_total), 2);
                $amount_in_words = amount_in_words(round($final_amt)) . " Only";
                $tax_in_words = amount_in_words(round($gst_grand_total_rounded)) . " Only";

                PurchaseOrder::where('purchase_order_id', $purchaseorder->purchase_order_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);
            }





            // set counter
            $financial_year = Financialyear::where(['year' => $Financialyear, 'company_id' => $bp_master->company_id])->first();
            $purchase_order_counter = 0;
            if ($financial_year) {
                $purchase_order_counter = $financial_year->purchase_order_counter + 1;
            }
            $bill_no = $series_no . '-' . $financial_year->year . "-" . $purchase_order_counter;

            // $customer = BussinessPartnerMaster::where('business_partner_id', $purchaseorder->party_id)->first();

            // dd($customer);
            // $party_name = "";
            // if ($customer) {
            //   $party_name = $customer->name;
            // }

            $log = ['module' => 'PurchaseOrder', 'action' => 'PurchaseOrder Created', 'description' => 'PurchaseOrder Created: ' . $bill_no];
            captureActivity($log);


            PurchaseOrder::where('purchase_order_id', $purchaseorder->purchase_order_id)->update(['purchase_order_counter' => $purchase_order_counter, 'bill_no' => $bill_no]);
            $financial_year->purchase_order_counter = $purchase_order_counter;
            $financial_year->save();

            Session::flash('message', 'PurchaseOrder Added Successfully!');
            Session::flash('status', 'success');
            return redirect('admin/purchaseorder/view/' . $purchaseorder->purchase_order_id . '?print=yes');
            //return redirect('admin/purchaseorder');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/purchaseorder');
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
        $GLOBALS['breadcrumb'] = [['name' => 'PurchaseOrder', 'route' => "admin.purchaseorder"], ['name' => 'Create', 'route' => ""]];

        $roles = Role::pluck('name', 'id')->all();
        $gsts = Gst::pluck('gst_name', 'gst_id')->all();
        $party = BussinessPartnerMaster::when(session('company_id') != 0, function ($query) {
            return $query->where('company_id', session('company_id'));
        })->with('getpartnertype')->get();

        // $contac_data = BussinessPartnerContactDetails::get('contact_details_id','contact_person');

        $party = $party->filter(function ($item) {
            $data = $item->getpartnertype;

            return $data;
        })->mapWithKeys(function ($item) {
            return [$item['business_partner_id'] => $item['bp_name']];
        });

        $model = PurchaseOrder::with('purchaseorder_items')->where('purchase_order_id', $id)->first();

        // dd($party->toArray());
        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $Financialyear = get_fy_year($model->company_id);

        $financial_year = Financialyear::where(['year' => $Financialyear, 'company_id' => $model->company_id])->first();

        // dd($financial_year->toArray());
        $purchase_order_counter = 1;
        $fyear = "";
        if (isset($financial_year)) {
            $purchase_order_counter = $financial_year->purchase_order_counter + 1;
            $fyear = $financial_year->year;
        }
        // dd($model->toArray());
        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id')->all();
        $gst = Gst::pluck('gst_name', 'gst_id');
        return view('backend.purchaseorder.edit', compact('roles', 'model', 'gst', 'storage_locations', 'party', 'purchase_order_counter', 'fyear', 'gsts'));
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
            'vendor_ref_no' => 'required',
            'ship_from' => 'required',
            // 'posting_date' => 'required',
            'delivery_date' => 'required',
            'document_date' => 'required',
            'status' => 'required',
            'contact_person' => 'required',
        ]);

        $invoice_items_array = array();
        $current_invoice_items = array();


        $id = $request->input('purchase_order_id');
        $this->validate($request, [
            //'purchaseorder_type_name' => ['required', 'email', Rule::unique(PurchaseOrder::class,'email')->ignore($id, 'purchaseorder_type_id')],
        ]);
        $purchaseorder = PurchaseOrder::findOrFail($id);
        $purchaseorder->fill($request->all());
        if ($purchaseorder->update()) {
            // delete existing items
            // PurchaseOrderItems::where(['purchase_order_id' => $purchaseorder->purchase_order_id])->delete();

            $sub_total = $cgst_total = $sgst_utgst_total = $igst_total = $gst_grand_total = $grand_total = 0;
            $gst_rate = 0;

            //update old data

            //get old invoice items for
            $old_invoice_items  = PurchaseOrderItems::where('purchase_order_id', $purchaseorder->purchase_order_id)->get('purchase_order_item_id');
            //store ids in saparate array()

            // dd($old_invoice_items->toArray());
            if ($old_invoice_items && count($old_invoice_items) > 0) {
                foreach ($old_invoice_items as $inv_item) {
                    // $invoice_items_array[] = $inv_item->goods_service_receipts_item_id;
                    array_push($invoice_items_array, $inv_item->purchase_order_item_id);
                }
            }

            // store purchaseorder items
            // dd($request->all());
            if (isset($request->old_invoice_items)) {
                // $old_purchaseorder_items = $request->old_invoice_items;
                $filteredItems = $request->old_invoice_items;


                $old_purchaseorder_items = array_filter($filteredItems, function ($item) {
                    return $item['purchase_order_item_id'] !== null;
                });

                $new_purchaseorder_items = array_filter($filteredItems, function ($item) {
                    return $item['purchase_order_item_id'] == null;
                });

                $purchase_order_id = $purchaseorder->purchase_order_id;
                $total_inr = 0;

                if (!empty($new_purchaseorder_items)) {
                    foreach ($new_purchaseorder_items as $item) {
                        // dd($item);
                        $total_inr += $item['total'];
                        $purchaseorder_item = new PurchaseOrderItems();
                        // $purchaseorder_item = PurchaseOrderItems::where('purchase_order_item_id', $item['purchase_order_item_id'])->first();
                        $sku = Products::where('item_code', $item['item_code'])->first();
                        $purchaseorder_item->sku = $sku->sku;
                        $purchaseorder_item->fill($item);
                        $purchaseorder_item->purchase_order_id = $purchase_order_id;
                        $purchaseorder_item->save();

                        //amount calculation
                        $sub_total = $sub_total + ($item['taxable_amount'] * $purchaseorder_item->qty);
                        $cgst_total = $cgst_total + $item['cgst_amount'];
                        $sgst_utgst_total = $sgst_utgst_total + $item['sgst_utgst_amount'];
                        $igst_total = $igst_total + $item['igst_amount'];

                        // gst rate
                        if ($gst_rate == 0) {
                            $gst_rate = $purchaseorder_item->cgst_rate + $purchaseorder_item->sgst_utgst_rate + $purchaseorder_item->igst_rate;
                        }

                        // batches
                        $purchase_order_item_id = $purchaseorder_item->purchase_order_item_id;
                        $storage_location_id = $purchaseorder_item->storage_location_id;

                        if (isset($item['batches'])) {
                            foreach ($item['batches'] as $batches) {
                                $purchaseOrderBatches = new PurchaseOrderBatches;

                                $data = [
                                    'purchase_order_id' => $purchase_order_id,
                                    'purchase_order_item_id' => $purchase_order_item_id,
                                    'storage_location_id' => $storage_location_id,
                                    'batch_no' => $batches['batch_no'],
                                    'manufacturing_date' => $batches['manufacturing_date'],
                                    'expiry_date' => $batches['expiry_date']
                                ];

                                if ($batches['batch_no'] != '') {
                                    $purchaseOrderBatches->fill($data);
                                    $purchaseOrderBatches->save();
                                }
                            }
                        }
                    }
                    $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
                    $gst_grand_total_rounded = $gst_grand_total;
                    $final_amt = 0;
                    // $grand_total = $sub_total + $gst_grand_total_rounded;
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

                    PurchaseOrder::where('purchase_order_id', $purchaseorder->purchase_order_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);
                }
                // dd($purchaseorder_items);
                $total_inr = 0;
                foreach ($old_purchaseorder_items as $old_item) {
                    // dd($item);
                    // $purchaseorder_item = new PurchaseOrderItems();
                    $total_inr += $old_item['total'];
                    $purchaseorder_item = PurchaseOrderItems::where('purchase_order_item_id', $old_item['purchase_order_item_id'])->first();
                    $sku = Products::where('item_code', $old_item['item_code'])->first();
                    $purchaseorder_item->sku = $sku->sku;
                    $purchaseorder_item->fill($old_item);
                    $purchaseorder_item->purchase_order_id = $purchase_order_id;
                    // dd($purchaseorder_item);
                    $purchaseorder_item->save();
                    array_push($current_invoice_items, $purchaseorder_item->purchase_order_item_id);

                    //amount calculation
                    $sub_total = $sub_total + ($old_item['taxable_amount'] * $purchaseorder_item->qty);
                    $cgst_total = $cgst_total + $old_item['cgst_amount'];
                    $sgst_utgst_total = $sgst_utgst_total + $old_item['sgst_utgst_amount'];
                    $igst_total = $igst_total + $old_item['igst_amount'];

                    // gst rate
                    if ($gst_rate == 0) {
                        $gst_rate = $purchaseorder_item->cgst_rate + $purchaseorder_item->sgst_utgst_rate + $purchaseorder_item->igst_rate;
                    }

                    //batches
                    $purchase_order_id = $purchaseorder->purchase_order_id;
                    $purchase_order_item_id = $purchaseorder_item->purchase_order_item_id;
                    $storage_location_id = $purchaseorder_item->storage_location_id;
                    // dd($item);

                }
                // dd($gross_total);
                $gst_grand_total = ($cgst_total + $sgst_utgst_total + $igst_total);
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
                // dd($grand_total);
                $rounded_off = round(($gst_grand_total_rounded - $gst_grand_total), 2);
                $amount_in_words = amount_in_words(round($final_amt)) . " Only";
                $tax_in_words = amount_in_words(round($gst_grand_total_rounded)) . " Only";

                PurchaseOrder::where('purchase_order_id', $purchaseorder->purchase_order_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);

                $invoice_difference = array_diff($invoice_items_array, $current_invoice_items);

                if ($invoice_difference && count($invoice_difference) > 0) {
                    foreach ($invoice_difference as $inv_diff) {
                        $delete_invoice = PurchaseOrderItems::where('purchase_order_item_id', $inv_diff)->first();
                        if ($delete_invoice) {
                            $delete_invoice->delete();
                        }
                    }
                }
            } else {
                foreach ($invoice_items_array as $inv_diff) {
                    $delete_invoice = PurchaseOrderItems::where('purchase_order_item_id', $inv_diff)->first();
                    if ($delete_invoice) {
                        $delete_invoice->delete();
                    }
                }
            }


            $customer = BussinessPartnerMaster::where('business_partner_id', $purchaseorder->party_id)->first();
            $party_name = "";
            if ($customer) {
                $party_name = $customer->name;
            }

            PurchaseOrder::where('purchase_order_id', $purchaseorder->purchase_order_id)->update(['party_name' => $party_name]);


            if ($purchaseorder->getChanges()) {
                $new_changes = $purchaseorder->getChanges();
                $log = ['module' => 'PurchaseOrder', 'action' => 'PurchaseOrder Updated', 'description' => 'PurchaseOrder Updated: Name=>' . $purchaseorder->bill_no];
                captureActivityupdate($new_changes, $log);
            }

            Session::flash('message', 'PurchaseOrder Updated Successfully!');
            Session::flash('status', 'success');
            return redirect('admin/purchaseorder');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
            return redirect('admin/purchaseorder');
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
        $purchaseorder = PurchaseOrder::findOrFail($id);
        $purchaseorder_items = PurchaseOrderItems::where('purchase_order_id', $id);
        $purchaseorder_batches = PurchaseOrderBatches::where('purchase_order_id', $id);
        // dd($purchaseorder_items);

        if ($purchaseorder->delete()) {
            $purchaseorder_items->delete();
            $purchaseorder_batches->delete();

            $log = ['module' => 'PurchaseOrder', 'action' => 'PurchaseOrder Deleted', 'description' => 'PurchaseOrder Deleted: ' . $purchaseorder->bill_no];
            captureActivity($log);

            Session::flash('message', 'PurchaseOrder Deleted Successfully!');
            Session::flash('status', 'success');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
        }

        return redirect('admin/purchaseorder');
    }


    public function amountinwords($number = 0)
    {
        return amount_in_words($number);
    }



    public function partydetails($business_partner_id)
    {
        $business_partner = BussinessPartnerMaster::where('business_partner_id', $business_partner_id)->first();

        // $comapny_data = Company::first(); //old
        $comapny_data = Company::where('company_id', $business_partner->company_id)->first();
        $business_partner_detail = "";
        $bill_to_state = "";
        $business_partner_state = $comapny_data->state;
        $bill_to_gst_no = "";
        $party_name = "";


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
        $query = $_GET['query'];
        // dd($query);
        $data = Products::select(DB::raw("item_name as name"), 'product_item_id')->where("item_name", "LIKE", "%" . $query . "%")->get();
        //var_dump($data);exit;
        return response()->json($data);
    }


    public function download($id)
    {

        $roles = Role::pluck('name', 'id')->all();
        $purchaseorder = PurchaseOrder::where('purchase_order_id', $id)->with('purchaseorder_items')->first();

        $party = BussinessPartnerMaster::where('business_partner_id', $purchaseorder->party_id)->first();

        // dd($party);

        $filename = $purchaseorder->bill_no;
        if (1 != 1) {
            $pdf = PDF::loadView('backend.purchaseorder.purchaseorder_format_split', ['roles' => $roles, 'purchaseorder' => $purchaseorder, 'party' => $party, 'download' => true]);
        } else {
            $pdf = PDF::loadView('backend.purchaseorder.invoice_format', ['roles' => $roles, 'purchaseorder' => $purchaseorder, 'party' => $party, 'download' => true]);
        }

        return $pdf->stream($filename . '.pdf');
    }
}
