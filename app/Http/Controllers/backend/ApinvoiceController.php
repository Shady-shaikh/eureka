<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\AdminUsers;
use App\Models\backend\Apinvoice;
use App\Models\backend\ApInvoiceBatches;
use App\Models\backend\ApInvoiceItems;
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
use App\Models\backend\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\backend\Products;
use App\Models\backend\StorageLocations;
use App\Models\backend\BillBooking;
use App\Models\backend\BillBookingItems;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\PDF;

class ApinvoiceController extends Controller
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
        $GLOBALS['breadcrumb'] = [['name' => 'A/P Invoice', 'route' => ""]];

        if ($request->ajax()) {
            $apinvoice = Apinvoice::where(['fy_year' => session('fy_year'), 'company_id' => session('company_id'), 'status' => 'open'])->with('get_partyname')->orderby('created_at', 'desc')->get();

            return DataTables::of($apinvoice)
                ->addIndexColumn()
                ->addColumn('action', function ($apinvoice) {
                    $actionBtn = '<div id="action_buttons">';
                    if (request()->user()->can('View A/P Invoice')) {
                        $actionBtn .= '<a href="' . route('admin.apinvoice.view', ['id' => $apinvoice->goods_service_receipt_id]) . '"
                    class="btn btn-sm btn-primary" title="View"><i class="feather icon-eye"></i></a> ';
                    }
                    if (request()->user()->can('Update A/P Invoice') && !$apinvoice->is_bill_booking_done) {
                        // dd("yes");
                        $actionBtn .= '<a href="' . route('admin.apinvoice.edit', ['id' => $apinvoice->goods_service_receipt_id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                    }
                    if (request()->user()->can('Delete A/P Invoice')) {
                        $actionBtn .= '<a href="' . route('admin.apinvoice.delete', ['id' => $apinvoice->goods_service_receipt_id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a></div>';
                    }
                    return $actionBtn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.apinvoice.index');
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
        $goodsservicereceipts = Apinvoice::where('goods_service_receipt_id', $id)->with('goodsservicereceipts_items')->with('get_bill_toaddress')->with('get_ship_toaddress')->first();
        // dd($goodsservicereceipts->toArray());
        $party = BussinessPartnerMaster::where('business_partner_id', $goodsservicereceipts->party_id)->first();
        $bill_address = BussinessPartnerAddress::where(['bussiness_partner_id' => $goodsservicereceipts->party_id, 'address_type' => 'Bill-To/ Bill-From'])->first();
        $ship_address = BussinessPartnerAddress::where(['bussiness_partner_id' => $goodsservicereceipts->party_id, 'address_type' => 'Ship-To/ Ship-From'])->first();
        $contact = BussinessPartnerContactDetails::where('bussiness_partner_id', $goodsservicereceipts->party_id)->first();
        $bank_details = BussinessPartnerBankingDetails::where('bussiness_partner_id', $goodsservicereceipts->party_id)->first();
        $invoice = $goodsservicereceipts;
        // dd($ship_address->toArray());

        return view('backend.apinvoice.show', compact('roles', 'bill_address', 'ship_address', 'contact', 'bank_details', 'goodsservicereceipts', 'party', 'invoice'));
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

        if ($id != '' && $id != '') {
            $model = Apinvoice::with('goodsservicereceipts_items')->where('goods_service_receipt_id', $id)->first();
            $data = ApInvoiceItems::first();
            // dd($model->toArray());
            // dd($goodsservicereceipt->toArray());
            if ($model) {
                return view('backend.apinvoice.edit', compact('model', 'gst', 'party', 'financial_year', 'purchase_order_counter', 'fyear', 'storage_locations', 'purchaseorder'));
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
            'trans_type' => 'required',
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

        $goodsservicereceipt = Apinvoice::where('goods_service_receipt_id', $request->goods_service_receipt_id)->first();

        if ($goodsservicereceipt) {
            $goodsservicereceipt->fill($request->all());
            // dd($goodsservicereceipt);
            //update Goods service Receipts
            if ($goodsservicereceipt->save()) {

                //check for file and upload file
                if ($request->has('po_document')) {
                    $location = public_path('/uploads/po_document/' . $goodsservicereceipt->goods_service_receipt_id);
                    $upload_file = 'po_' . date('dmyhis') . '_.' . $request->file('po_document')->getClientOriginalExtension();  //
                    $request->file('po_document')->move($location, $upload_file);
                    $goodsservicereceipt->po_document = $upload_file;
                    $goodsservicereceipt->save();
                }


                //save initial data
                $sub_total = $cgst_total = $sgst_utgst_total = $igst_total = $gst_grand_total = $grand_total = 0;
                $gst_rate = 0;

                //update old data

                //get old invoice items for
                $old_invoice_items  = ApInvoiceItems::where('goods_service_receipt_id', $goodsservicereceipt->goods_service_receipt_id)->get('goods_service_receipts_item_id');
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
                    // $old_goodsservicereceipts_items = $request->old_invoice_items;
                    $filteredItems = $request->old_invoice_items;
                    // dd($old_goodsservicereceipts_items);
                    $old_goodsservicereceipts_items = array_filter($filteredItems, function ($item) {
                        return $item['goods_service_receipts_item_id'] !== null;
                    });

                    $new_goodsservicereceipts_items = array_filter($filteredItems, function ($item) {
                        return $item['goods_service_receipts_item_id'] == null;
                    });

                    $purchase_order_id = $goodsservicereceipt->purchase_order_id;
                    $total_inr = 0;

                    if (!empty($new_goodsservicereceipts_items)) {
                        // dd($goodsservicereceipts_items);
                        $total_inr = 0;
                        foreach ($new_goodsservicereceipts_items as $item) {
                            $total_inr += $item['total'];
                            if ($item['item_name'] != '') {

                                $goodsservicereceipts_item = new ApInvoiceItems();
                                $sku = Products::where('item_code', $item['item_code'])->first();
                                $goodsservicereceipts_item->sku = $sku->sku;
                                $goodsservicereceipts_item->fill($item);
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
                                $gst_rate = $goodsservicereceipts_item->gst_rate;
                                if (!empty($gst_rate)) {
                                    $get_data = Gst::where('gst_id', $gst_rate)->first();
                                    $gst_rate = $get_data->gst_percent;
                                }

                                // batches
                                $goods_service_receipt_id = $goodsservicereceipt->goods_service_receipt_id;
                                $purchase_order_item_id = $goodsservicereceipts_item->purchase_order_item_id;
                                $storage_location_id = $goodsservicereceipts_item->storage_location_id;

                                if (isset($item['batches'])) {
                                    foreach ($item['batches'] as $batches) {
                                        $purchaseOrderBatches = new ApInvoiceBatches;

                                        $data = [
                                            'goods_service_receipt_id' => $goods_service_receipt_id,
                                            'goods_service_receipts_item_id' => $purchase_order_item_id,
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

                        Apinvoice::where('purchase_order_id', $goodsservicereceipt->purchase_order_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);
                    }

                    foreach ($old_goodsservicereceipts_items as $old_item) {
                        $total_inr += $old_item['total'];
                        if (isset($old_item['goods_service_receipts_item_id']) && $old_item['goods_service_receipts_item_id'] != '') {

                            $old_goodsservicereceipts_item = ApInvoiceItems::where('goods_service_receipts_item_id', $old_item['goods_service_receipts_item_id'])->first();
                            $sku = Products::where('item_code', $old_item['item_code'])->first();
                            $old_goodsservicereceipts_item->sku = $sku->sku;
                            $old_goodsservicereceipts_item->fill($old_item);
                            $old_goodsservicereceipts_item->purchase_order_id = $purchase_order_id;
                            $old_goodsservicereceipts_item->goods_service_receipt_id = $request->goods_service_receipt_id;
                            $old_goodsservicereceipts_item->save();
                            array_push($current_invoice_items, $old_goodsservicereceipts_item->goods_service_receipts_item_id);


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

                            //batches
                            $goods_service_receipt_id = $goodsservicereceipt->goods_service_receipt_id;
                            $purchase_order_item_id = $old_goodsservicereceipts_item->goods_service_receipts_item_id;
                            $storage_location_id = $old_goodsservicereceipts_item->storage_location_id;
                            // dd($item);



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
                    $updated_data =  Apinvoice::where('purchase_order_id', $goodsservicereceipt->purchase_order_id)->update(['sub_total' => $sub_total, 'cgst_total' => $cgst_total, 'sgst_utgst_total' => $sgst_utgst_total, 'igst_total' => $igst_total, 'gst_grand_total' => $gst_grand_total, 'grand_total' => $final_amt, 'amount_in_words' => $amount_in_words, 'gst_rate' => $gst_rate, 'tax_in_words' => $tax_in_words]);

                    //    dd($current_invoice_items);
                    $invoice_difference = array_diff($invoice_items_array, $current_invoice_items);
                    if ($invoice_difference && count($invoice_difference) > 0) {
                        foreach ($invoice_difference as $inv_diff) {
                            $delete_invoice = ApInvoiceItems::where('goods_service_receipts_item_id', $inv_diff)->first();
                            if ($delete_invoice) {
                                $delete_invoice->delete();
                            }
                        }
                    }
                } else {
                    foreach ($invoice_items_array as $inv_diff) {
                        $delete_invoice = ApInvoiceItems::where('goods_service_receipts_item_id', $inv_diff)->first();
                        if ($delete_invoice) {
                            $delete_invoice->delete();
                        }
                    }
                }
            }
        }

        // dd($goodsservicereceipt->toArray());

        // get invoice amount
        $inv_amt = ApInvoiceItems::where('goods_service_receipt_id', $goodsservicereceipt->goods_service_receipt_id)->get()->toArray();
        $totalAmount = array_sum(array_column($inv_amt, 'total'));
        $discountAmt = $totalAmount * ($goodsservicereceipt->discount / 100);
        $totalAmount = $totalAmount - $discountAmt + $goodsservicereceipt->gst_grand_total;

        // insert in bill booking n items
        if ($goodsservicereceipt->is_bill_booking_done == 0) {

            $moduleName = "Bill Booking";
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
            $billbooking->vendor_id = $goodsservicereceipt->party_id;
            $billbooking->fy_year = session('fy_year');
            $billbooking->company_id = session('company_id');
            $billbooking->status = "open";
            $billbooking->doc_no = $bill_no;
            $billbooking->bill_date = $goodsservicereceipt->bill_date;
            $billbooking->posting_date = $goodsservicereceipt->posting_date;
            $billbooking->type = 'Invoice';
            if ($billbooking->save()) {

                // limit this only once
                $goodsservicereceipt->is_bill_booking_done = 1;
                $goodsservicereceipt->save();


                $bill_booking_id = $billbooking->bill_booking_id;
                $billbooking_items = new BillBookingItems();
                $billbooking_items->bill_booking_id = $bill_booking_id;
                $billbooking_items->invoice_ref_no = $goodsservicereceipt->ap_inv_no;
                $billbooking_items->amount = $totalAmount;
                $billbooking_items->save();

                Session::flash('message', 'Bill Booking Created Successfully!');
                Session::flash('status', 'success');
                //   return redirect('admin/purchaseorder');
                return redirect()->route('admin.billbooking.edit', [$bill_booking_id]);
            }
        }



        if ($goodsservicereceipt->getChanges()) {
            $new_changes = $goodsservicereceipt->getChanges();
            $log = ['module' => 'A/P Invoice', 'action' => 'A/P Invoice Updated', 'description' => 'A/P Invoice Updated: Name=>' . $goodsservicereceipt->bill_no];
            captureActivityupdate($new_changes, $log);
        }
        return redirect()->route('admin.apinvoice')->with('success', 'Details has Been Updated');
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
        $goodsservicereceipts = Apinvoice::findOrFail($id);

        // dd($goodsservicereceipts);

        if ($goodsservicereceipts->delete()) {

            $log = ['module' => 'A/P Invoice', 'action' => 'A/P Invoice Deleted', 'description' => 'A/P Invoice Deleted: ' . $goodsservicereceipts->bill_no];
            captureActivity($log);

            Session::flash('message', 'A/P Invoice Deleted Successfully!');
            Session::flash('status', 'success');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('status', 'error');
        }

        return redirect('admin/apinvoice');
    }


    public function amountinwords($number = 0)
    {
        return amount_in_words($number);
    }



    public function partydetails($business_partner_id)
    {
        // $comapny_data = Company::first();
        $comapny_data = Company::where('company_id', Session::get('company_id'))->first();

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
        // dd($request->query);
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
            $pdf = PDF::loadView('backend.goodsservicereceipts.invoice_format', ['roles' => $roles, 'goodsservicereceipts' => $goodsservicereceipts, 'party' => $party, 'download' => true]);
        }

        return $pdf->stream($filename . '.pdf');
    }
}
