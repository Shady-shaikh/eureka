<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Products;
use App\Models\backend\Brands;
use App\Models\backend\BusinessPartnerCategory;
use App\Models\backend\Company;
use App\Models\backend\Margin;
use App\Models\backend\PricingItem;
use App\Models\backend\Pricingladder;
use App\Models\backend\Subdmargin;
use App\Models\backend\Pricings;
use App\Models\backend\Scheme;
use App\Models\backend\SubCategories;
use App\Models\backend\Variant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;;


class MarginSchemesController extends Controller
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

    public function margin_update(Request $request)
    {
        // dd($request->all());
        $request->input('invoice_items');

        $pricing_master_id = $request->pricing_master_id;

        if (!empty($invoiceItems)) {
            foreach ($invoiceItems as $item) {

                $pricings = Margin::where([
                    'pricing_master_id' => $pricing_master_id,
                    'brand_id' => $item['brand_id'], 'sub_category_id' => $item['sub_category_id'],
                    'variant' => $item['variant'], 'buom_pack_size' => $item['buom_pack_size'],
                ])->first();

                if (!empty($pricings)) {
                    $pricings->margin = $item['margin'];
                } else {
                    $pricings = new Margin();
                    $pricings->fill($item);
                    $pricings->pricing_master_id = $pricing_master_id;
                }
                // dd($pricings);
                $pricings->save();
            }
        } else {
            // for import
            $excel_file = $request->file('file');
            try {

                $spreadsheet = IOFactory::load($excel_file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $row_limit = $sheet->getHighestDataRow();
                $column_limit = $sheet->getHighestDataColumn();
                $row_range = range(2, $row_limit);
                $column_range = range('E', $column_limit);
                $startcount = 1;
                $srno = 0;
                $uploadedFile = $excel_file;
                $filename = date('Y-m-d_H-i-s') . '_' . str_replace(' ', '_', $uploadedFile->getClientOriginalName());
                $uploadedFile->move(public_path('uploads/'), $filename);


                foreach ($row_range as $row) {

                    // $isEmptyRow = true;
                    // foreach ($column_range as $column) {
                    //     if (!empty($sheet->getCell($column . $row)->getValue())) {
                    //         $isEmptyRow = false;
                    //         break;
                    //     }
                    // }
                    if ($srno > 0) {
                        // dd($sheet->getCell('D' . $row)->getValue());
                        $data = [
                            'brand_id' => getOrCreateIdUnified(Brands::class, 'brand_name', $sheet->getCell('B' . $row)->getValue(), 'brand_id'),
                            'sub_category_id' => getOrCreateIdUnified(SubCategories::class, 'subcategory_name', $sheet->getCell('C' . $row)->getValue(), 'subcategory_id'),
                            'variant' => getOrCreateIdUnified(Variant::class, 'name', $sheet->getCell('D' . $row)->getValue(), 'id'),
                            'buom_pack_size' => trim(addslashes($sheet->getCell('E' . $row)->getValue())),
                            'margin' => number_format(trim(addslashes($sheet->getCell('F' . $row)->getValue())), 2, '.', '')
                            // Add more fields as needed
                        ];

                        $pricings = Margin::where([
                            'pricing_master_id' => $pricing_master_id,
                            'brand_id' => $data['brand_id'], 'sub_category_id' => $data['sub_category_id'],
                            'variant' => $data['variant'], 'buom_pack_size' => $data['buom_pack_size'],
                        ])->first();


                        if (!empty($pricings)) {
                            $pricings->margin = $data['margin'];
                        } else {
                            $pricings = new Margin();
                            $pricings->fill($data);
                        }

                        $pricings->pricing_master_id = $pricing_master_id;
                        $pricings->save();

                        //update status of all pricing ladder with same margin if margin got updated
                        $pricing_master = Pricings::where('pricing_master_id', $pricing_master_id)->first();
                        $ladder_pricing = Pricings::where([
                            'pricing_type' => 'ladder',
                            'margin' => $pricing_master->pricing_master_id,
                            'bp_channel' => $pricing_master->bp_channel,
                            'bp_group' => $pricing_master->bp_group
                        ])->get();
                        foreach ($ladder_pricing as $row) {
                            $row->status = 0;
                            $row->save();
                        }


                        // array_push($imported_data, $data);
                    }
                    $startcount++;
                    $srno++;
                    // $data = User::all();
                }
                // dd($imported_data);
            } catch (Exception $e) {
                dd($e);
            }
        }
        return redirect()->back()->with('success', 'Margin Updated');
    }
    public function scheme_update(Request $request)
    {
        // dd($request->all());
        $request->input('invoice_items');

        $pricing_master_id = $request->pricing_master_id;

        if (!empty($invoiceItems)) {
            foreach ($invoiceItems as $item) {

                $pricings = Scheme::where([
                    'pricing_master_id' => $pricing_master_id,
                    'brand_id' => $item['brand_id'], 'sub_category_id' => $item['sub_category_id'],
                    'variant' => $item['variant'], 'buom_pack_size' => $item['buom_pack_size'],
                ])->first();

                if (!empty($pricings)) {
                    $pricings->scheme = $item['scheme'];
                } else {
                    $pricings = new Scheme();
                    $pricings->fill($item);
                    $pricings->pricing_master_id = $pricing_master_id;
                }
                // dd($pricings);
                $pricings->save();
            }
        } else {
            // for import
            $excel_file = $request->file('file');
            try {

                $spreadsheet = IOFactory::load($excel_file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $row_limit = $sheet->getHighestDataRow();
                $column_limit = $sheet->getHighestDataColumn();
                $row_range = range(2, $row_limit);
                $column_range = range('E', $column_limit);
                $startcount = 1;
                $srno = 0;
                $uploadedFile = $excel_file;
                $filename = date('Y-m-d_H-i-s') . '_' . str_replace(' ', '_', $uploadedFile->getClientOriginalName());
                $uploadedFile->move(public_path('uploads/'), $filename);


                foreach ($row_range as $row) {

                    // $isEmptyRow = true;
                    // foreach ($column_range as $column) {
                    //     if (!empty($sheet->getCell($column . $row)->getValue())) {
                    //         $isEmptyRow = false;
                    //         break;
                    //     }
                    // }
                    if ($srno > 0) {
                        // dd($sheet->getCell('D' . $row)->getValue());
                        $data = [
                            'brand_id' => getOrCreateIdUnified(Brands::class, 'brand_name', $sheet->getCell('B' . $row)->getValue(), 'brand_id'),
                            'sub_category_id' => getOrCreateIdUnified(SubCategories::class, 'subcategory_name', $sheet->getCell('C' . $row)->getValue(), 'subcategory_id'),
                            'variant' => getOrCreateIdUnified(Variant::class, 'name', $sheet->getCell('D' . $row)->getValue(), 'id'),
                            'buom_pack_size' => trim(addslashes($sheet->getCell('E' . $row)->getValue())),
                            'scheme' => number_format(trim(addslashes($sheet->getCell('F' . $row)->getValue())), 2, '.', '')
                            // Add more fields as needed
                        ];

                        $pricings = Scheme::where([
                            'pricing_master_id' => $pricing_master_id,
                            'brand_id' => $data['brand_id'], 'sub_category_id' => $data['sub_category_id'],
                            'variant' => $data['variant'], 'buom_pack_size' => $data['buom_pack_size'],
                        ])->first();

                        // if ($data['scheme'] != 0) {

                        if (!empty($pricings)) {
                            $pricings->scheme = $data['scheme'];
                        } else {
                            $pricings = new Scheme();
                            $pricings->fill($data);
                        }

                        $pricings->pricing_master_id = $pricing_master_id;
                        $pricings->save();
                        // }

                        //update status of all pricing ladder with same margin if margin got updated
                        $pricing_master = Pricings::where('pricing_master_id', $pricing_master_id)->first();
                        $ladder_pricing = Pricings::where([
                            'pricing_type' => 'ladder',
                            'scheme' => $pricing_master->pricing_master_id,
                            'bp_channel' => $pricing_master->bp_channel,
                            'bp_group' => $pricing_master->bp_group
                        ])->get();
                        foreach ($ladder_pricing as $row) {
                            $row->status = 0;
                            $row->save();
                        }

                        // array_push($imported_data, $data);
                    }
                    $startcount++;
                    $srno++;
                    // $data = User::all();
                }
                // dd($imported_data);
            } catch (Exception $e) {
                dd($e);
            }
        }
        return redirect()->back()->with('success', 'Scheme Updated');
    }
    public function subdmargin_update(Request $request)
    {
        // dd($request->all());
        $request->input('invoice_items');

        $bp_channel = $request->bp_channel;

        if (!empty($invoiceItems)) {
            foreach ($invoiceItems as $item) {

                $pricings = Subdmargin::where([
                    'customer_code' => $item['customer_code'],
                ])->first();

                if (!empty($pricings)) {
                    $pricings->margin = $item['margin'];
                } else {
                    $pricings = new Subdmargin();
                    $pricings->fill($item);
                }
                // dd($pricings);
                $pricings->save();
            }
        } else {
            // for import
            $excel_file = $request->file('file');
            try {

                $spreadsheet = IOFactory::load($excel_file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $row_limit = $sheet->getHighestDataRow();
                $column_limit = $sheet->getHighestDataColumn();
                $row_range = range(2, $row_limit);
                $column_range = range('E', $column_limit);
                $startcount = 1;
                $srno = 0;
                $uploadedFile = $excel_file;
                $filename = date('Y-m-d_H-i-s') . '_' . str_replace(' ', '_', $uploadedFile->getClientOriginalName());
                $uploadedFile->move(public_path('uploads/'), $filename);


                foreach ($row_range as $row) {

                    // $isEmptyRow = true;
                    // foreach ($column_range as $column) {
                    //     if (!empty($sheet->getCell($column . $row)->getValue())) {
                    //         $isEmptyRow = false;
                    //         break;
                    //     }
                    // }
                    if ($srno > 0) {
                        // dd($sheet->getCell('D' . $row)->getValue());
                        $data = [
                            'customer_code' => trim(addslashes($sheet->getCell('B' . $row)->getValue())),
                            'customer_name' => trim(addslashes($sheet->getCell('C' . $row)->getValue())),
                            'location' => trim(addslashes($sheet->getCell('D' . $row)->getValue())),
                            'margin' => number_format(trim(addslashes($sheet->getCell('E' . $row)->getValue())), 2, '.', ''),
                            // Add more fields as needed
                        ];

                        $pricings = Subdmargin::where([
                            'customer_code' => $data['customer_code'],
                        ])->first();

                        // if ($data['margin'] != 0) {

                        if (!empty($pricings)) {
                            $pricings->margin = $data['margin'];
                        } else {
                            $pricings = new Subdmargin();
                            $pricings->fill($data);
                        }

                        $pricings->save();

                        //update status of all pricing ladder with same margin if margin got updated
                        $ladder_pricing = Pricings::where([
                            'pricing_type' => 'ladder',
                        ])->get();
                        foreach ($ladder_pricing as $row) {
                            $row->status = 0;
                            $row->save();
                        }
                        // }

                        // array_push($imported_data, $data);
                    }
                    $startcount++;
                    $srno++;
                    // $data = User::all();
                }
                // dd($imported_data);
            } catch (Exception $e) {
                dd($e);
            }
        }
        return redirect()->back()->with('success', 'Sub-D Margin Updated')->with('bp_channel', $bp_channel);
    }

    public function fetch_margin()
    {
        $pricing_master_id = $_GET['pricing_master_id'];

        $margin_data = Margin::with('brand', 'format', 'variants')->where([
            'pricing_master_id' => $pricing_master_id,
        ])->get()->toArray();


        return json_encode($margin_data);
    }

    public function fetch_pricingladder()
    {
        $pricing_master_id = $_GET['pricing_master_id'];

        $all_data = Margin::with('brand', 'format')->where([
            'pricing_master_id' => $pricing_master_id,
        ])->get()->toArray();

        return json_encode($all_data);
    }

    public function fetch_scheme()
    {
        $pricing_master_id = $_GET['pricing_master_id'];

        $scheme_data = Scheme::with('brand', 'format', 'variants')->where([
            'pricing_master_id' => $pricing_master_id,
        ])->get()->toArray();

        return json_encode($scheme_data);
    }

    public function fetch_subdmargin()
    {


        $subdmargin_data = Subdmargin::get();

        $result_array['subdmargin_data'] = $subdmargin_data;


        return json_encode($result_array);
    }

    public function margin_form($id, Request $request)
    {
        $products = Products::select('brand_id', 'sub_category_id', 'variant', 'buom_pack_size', 'uom_id')
            ->distinct()
            ->get();

        $pricing = Pricings::where('pricing_master_id', $id)->first();


        if ($request->ajax()) {
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('brand_id', fn ($row) => $row->brand->brand_name ?? '')
                ->addColumn('sub_category_id', fn ($row) => $row->sub_category->subcategory_name ?? '')
                ->addColumn('variant', fn ($row) => $row->variants->name ?? '')
                ->addColumn('buom_pack_size', function ($row) {
                    if ($row->get_uom !== null) {
                        return $row->buom_pack_size . ' ' . $row->get_uom->uom_name;
                    } else {
                        return $row->buom_pack_size ?? '';
                    }
                })

                ->addColumn('margin', function ($row) use ($id) {
                    if ($row->get_uom !== null) {
                        $buom_pack_size =  $row->buom_pack_size . ' ' . $row->get_uom->uom_name;
                    } else {
                        $buom_pack_size =  $row->buom_pack_size ?? '';
                    }
                    $margin_data = Margin::where([
                        'pricing_master_id' => $id,
                        'brand_id' => $row->brand_id,
                        'sub_category_id' => $row->sub_category_id,
                        'variant' => $row->variant
                    ])
                        ->where('buom_pack_size', $buom_pack_size)
                        ->first();

                    return $margin_data->margin ?? 0.00;
                })
                ->rawColumns(['margin'])
                ->make(true);
        }


        return view('backend.marginscheme.margin_form', compact('pricing'));
    }

    public function store_db()
    {
        $pricing_master_id = request('id');
        $serialized_data = request('dataArray');

        $pricing = Pricings::where('pricing_master_id', $pricing_master_id)->first();
        if ($pricing && $pricing->status == 0) {
            $data = json_decode($serialized_data, true);
            foreach ($data as $row) {
                $ladder = new Pricingladder();
                $ladder->fill($row);
                $ladder->pricing_master_id = $pricing_master_id;
                $ladder->date = date('Y-m-d');
                $ladder->save();
            }

            $pricing->status = 1;
            $pricing->save();
        }

        return json_encode(['success', 'Data Stored!']);
    }

    public function pricingladder_form($id, Request $request)
    {
        $products = Products::with('get_gst', 'brand', 'category', 'sub_category', 'variants', 'get_uom')->get();

        $pricing = Pricings::with('get_channel', 'get_sub_d_margin', 'get_scheme', 'get_margin')
            ->where('pricing_master_id', $id)->first();

        // dd($pricing->toArray());

        $purchase_pricing = Pricings::where('pricing_type', 'purchase')->first();
        $purchase_pricing_items = PricingItem::where('pricing_master_id', $purchase_pricing->pricing_master_id)->pluck('selling_price', 'item_code');
        // $dist_margin_perc = Company::where(['db_type' => 'Distributor', 'is_subd' => '0'])->first();



        if ($request->ajax()) {
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('visibility', fn ($row) => $row->visibility == 1 ? 'Active' : 'In-Active')
                ->addColumn('bp_channel', fn ($row) => $pricing->get_channel->business_partner_category_name)
                ->addColumn('brand_id', fn ($row) => $row->brand->brand_name)
                ->addColumn('category_id', fn ($row) => $row->category->category_name)
                ->addColumn('sub_category_id', fn ($row) => $row->sub_category->subcategory_name)
                ->addColumn('variant', fn ($row) => $row->variants->name)
                ->addColumn('final_sellp_dist', fn ($row) => isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0)
                ->addColumn('dist_margin_perc', fn ($row) => $pricing->distributor_margin)
                ->addColumn('dist_margin', function ($row) use ($purchase_pricing_items, $pricing) {
                    $marginPercentage = $pricing->distributor_margin / 100; // Convert percentage to decimal
                    $purchase_price = isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0;
                    return round($purchase_price * $marginPercentage, 2);
                })
                ->addColumn('gst_total_val_add', function ($row) use ($purchase_pricing_items, $pricing) {
                    $gstPercentage = $row->get_gst->gst_percent / 100; // Convert percentage to decimal
                    $final_sellp_dist = isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0;
                    $dist_margin = round($final_sellp_dist *  $pricing->distributor_margin / 100, 2);
                    $total = $final_sellp_dist + $dist_margin;
                    return round($total * $gstPercentage, 2);
                })
                ->addColumn('net_bill_price_dist', function ($row) use ($purchase_pricing_items, $pricing) {
                    $gstPercentage = $row->get_gst->gst_percent / 100; // Convert percentage to decimal
                    $final_sellp_dist = isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0;
                    $dist_margin = round($final_sellp_dist *  $pricing->distributor_margin / 100, 2);
                    $total = $final_sellp_dist + $dist_margin;
                    $gst_total_val_add =  round($total * $gstPercentage, 2);

                    return round($final_sellp_dist + $dist_margin + $gst_total_val_add, 2);
                })
                ->addColumn('tts', function ($row) use ($pricing, $purchase_pricing_items) {
                    $margin = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }

                    if (!empty($row->mrp)) {

                        $retailer_margin = round($row->mrp - ($row->mrp / (1 + ($margin / 100))), 1);
                        $ptr =  $row->mrp - $retailer_margin;

                        $gstPercentage = $row->get_gst->gst_percent / 100; // Convert percentage to decimal
                        $final_sellp_dist = isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0;
                        $dist_margin = round($final_sellp_dist *  $pricing->distributor_margin / 100, 2);
                        $total = $final_sellp_dist + $dist_margin;
                        $gst_total_val_add =  round($total * $gstPercentage, 2);

                        $net_bill_price_dist =  $final_sellp_dist + $dist_margin + $gst_total_val_add;
                        $tts_wo_gst = round(abs($ptr - $net_bill_price_dist), 2);

                        $tts =  $tts_wo_gst / $row->mrp;
                    } else {
                        $tts = 0;
                    }
                    return round($tts * 100.0) . "%";
                })
                ->addColumn('tts_wo_gst', function ($row) use ($pricing, $purchase_pricing_items) {
                    $margin = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    if (!empty($row->mrp)) {

                        $retailer_margin = round($row->mrp - ($row->mrp / (1 + ($margin / 100))), 1);
                        $ptr =  $row->mrp - $retailer_margin;
                    } else {
                        $ptr = 0;
                    }
                    $gstPercentage = $row->get_gst->gst_percent / 100; // Convert percentage to decimal
                    $final_sellp_dist = isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0;
                    $dist_margin = round($final_sellp_dist *  $pricing->distributor_margin / 100, 2);
                    $total = $final_sellp_dist + $dist_margin;
                    $gst_total_val_add =  round($total * $gstPercentage, 2);

                    $net_bill_price_dist =  $final_sellp_dist + $dist_margin + $gst_total_val_add;

                    return round(abs($ptr - $net_bill_price_dist), 2);
                })
                ->addColumn('tts_aft_sch', function ($row) use ($pricing, $purchase_pricing_items) {
                    $margin = 0;
                    $scheme = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    foreach ($pricing->get_scheme as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $scheme = $item->scheme;
                        }
                    }
                    if (!empty($row->mrp)) {

                        $retailer_margin = round($row->mrp - ($row->mrp / (1 + ($margin / 100))), 1);
                        $ptr = $row->mrp - $retailer_margin;
                        $scheme_md =  $ptr * ($scheme / 100);
                        $ptr_af_sch = round($ptr - $scheme_md, 2);
                        $sub_d_margin = ($pricing->get_sub_d_margin->margin ?? 0) / 100;
                        $sub_d_landing =  round($ptr_af_sch / (1 + ($sub_d_margin)), 2);

                        $gstPercentage = $row->get_gst->gst_percent / 100; // Convert percentage to decimal
                        $final_sellp_dist = isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0;
                        $dist_margin = round($final_sellp_dist *  $pricing->distributor_margin / 100, 2);
                        $total = $final_sellp_dist + $dist_margin;
                        $gst_total_val_add =  round($total * $gstPercentage, 2);

                        $net_bill_price_dist =  $final_sellp_dist + $dist_margin + $gst_total_val_add;
                        $tts_wo_gst_af_scheme =  round(abs($sub_d_landing - $net_bill_price_dist), 2);

                        $tts_aft_sch = $tts_wo_gst_af_scheme / $row->mrp;
                    } else {
                        $tts_aft_sch = 0;
                    }
                    return round($tts_aft_sch * 100.0) . "%";
                })
                ->addColumn('tts_wo_gst_af_scheme', function ($row) use ($purchase_pricing_items, $pricing) {
                    $margin = 0;
                    $scheme = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    foreach ($pricing->get_scheme as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $scheme = $item->scheme;
                        }
                    }

                    if (!empty($row->mrp)) {

                        $retailer_margin = round($row->mrp - ($row->mrp / (1 + ($margin / 100))), 1);
                        $ptr = $row->mrp - $retailer_margin;
                    } else {
                        $ptr = 0;
                    }
                    $scheme_md =  $ptr * ($scheme / 100);
                    $ptr_af_sch = round($ptr - $scheme_md, 2);
                    $sub_d_margin = ($pricing->get_sub_d_margin->margin ?? 0) / 100;
                    $sub_d_landing =  round($ptr_af_sch / (1 + ($sub_d_margin)), 2);

                    $gstPercentage = $row->get_gst->gst_percent / 100; // Convert percentage to decimal
                    $final_sellp_dist = isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0;
                    $dist_margin = round($final_sellp_dist *  $pricing->distributor_margin / 100, 2);
                    $total = $final_sellp_dist + $dist_margin;
                    $gst_total_val_add =  round($total * $gstPercentage, 2);

                    $net_bill_price_dist =  $final_sellp_dist + $dist_margin + $gst_total_val_add;

                    return round(abs($sub_d_landing - $net_bill_price_dist), 2);
                })


                ->addColumn('actual_dt_margin', function ($row) use ($purchase_pricing_items, $pricing) {
                    $marginPercentage = $pricing->distributor_margin / 100; // Convert percentage to decimal
                    $final_sellp_dist = isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0;
                    $dist_margin =  round($final_sellp_dist * $marginPercentage, 2);

                    $margin = 0;
                    $scheme = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    foreach ($pricing->get_scheme as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $scheme = $item->scheme;
                        }
                    }

                    if (!empty($row->mrp)) {

                        $retailer_margin = $row->mrp - ($row->mrp / (1 + ($margin / 100)));
                        $ptr = $row->mrp - $retailer_margin;
                    } else {
                        $ptr = 0;
                    }
                    $scheme_md =  $ptr * ($scheme / 100);
                    $ptr_af_sch = $ptr - $scheme_md;
                    // $sub_d_landing = $ptr_af_sch / (1 + ($pricing->get_sub_d_margin->margin ?? 0 / 100));
                    $sub_d_margin = ($pricing->get_sub_d_margin->margin ?? 0) / 100;
                    $sub_d_landing =  round($ptr_af_sch / (1 + ($sub_d_margin)), 2);
                    $sub_d_margin_abs_exc =  round(($sub_d_margin * $sub_d_landing) / 1.18, 2);

                    $diff_in_margin = $dist_margin - $sub_d_margin_abs_exc;

                    $actual_dt_margin = $final_sellp_dist != 0 ? ($diff_in_margin / $final_sellp_dist * 100) : 0;
                    return round($actual_dt_margin, 2) . "%";
                })
                ->addColumn('diff_in_margin', function ($row) use ($purchase_pricing_items, $pricing) {
                    $marginPercentage = $pricing->distributor_margin / 100; // Convert percentage to decimal
                    $final_sellp_dist = isset($purchase_pricing_items[$row->item_code]) ? $purchase_pricing_items[$row->item_code] : 0;
                    $dist_margin =  round($final_sellp_dist * $marginPercentage, 2);

                    $margin = 0;
                    $scheme = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    foreach ($pricing->get_scheme as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $scheme = $item->scheme;
                        }
                    }

                    if (!empty($row->mrp)) {


                        $retailer_margin = $row->mrp - ($row->mrp / (1 + ($margin / 100)));
                        $ptr = $row->mrp - $retailer_margin;
                    } else {
                        $ptr = 0;
                    }

                    $scheme_md =  $ptr * ($scheme / 100);
                    $ptr_af_sch = $ptr - $scheme_md;

                    $sub_d_margin = ($pricing->get_sub_d_margin->margin ?? 0) / 100;
                    $sub_d_landing =  round($ptr_af_sch / (1 + ($sub_d_margin)), 2);
                    $sub_d_margin_abs_exc =  round(($sub_d_margin * $sub_d_landing) / 1.18, 2);

                    return round(abs($dist_margin - $sub_d_margin_abs_exc), 2);
                })
                ->addColumn('sub_d_margin_abs_exc', function ($row) use ($pricing) {
                    $margin = 0;
                    $scheme = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    foreach ($pricing->get_scheme as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->sub_category_id
                        ) {
                            $scheme = $item->scheme;
                        }
                    }

                    if (!empty($row->mrp)) {

                        $retailer_margin = $row->mrp - ($row->mrp / (1 + ($margin / 100)));
                        $ptr = $row->mrp - $retailer_margin;
                    } else {
                        $ptr = 0;
                    }
                    $scheme_md =  $ptr * ($scheme / 100);
                    $ptr_af_sch = $ptr - $scheme_md;
                    $sub_d_margin = ($pricing->get_sub_d_margin->margin ?? 0) / 100;

                    $sub_d_landing =  round($ptr_af_sch / (1 + ($sub_d_margin)), 2);
                    return round(($sub_d_margin * $sub_d_landing) / 1.18, 2);
                })
                ->addColumn('sub_d_landing', function ($row) use ($pricing) {
                    $margin = 0;
                    $scheme = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    foreach ($pricing->get_scheme as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $scheme = $item->scheme;
                        }
                    }

                    if (!empty($row->mrp)) {

                        $retailer_margin = $row->mrp - ($row->mrp / (1 + ($margin / 100)));
                        $ptr = $row->mrp - $retailer_margin;
                    } else {
                        $ptr = 0;
                    }
                    $scheme_md =  $ptr * ($scheme / 100);
                    $ptr_af_sch = round($ptr - $scheme_md, 2);
                    $sub_d_margin = ($pricing->get_sub_d_margin->margin ?? 0) / 100;
                    return round($ptr_af_sch / (1 + ($sub_d_margin)), 2);
                })
                ->addColumn('sub_d_margin', fn ($row) => $pricing->get_sub_d_margin->margin ?? 0)
                ->addColumn('ptr_af_sch', function ($row) use ($pricing) {
                    $margin = 0;
                    $scheme = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    foreach ($pricing->get_scheme as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $scheme = $item->scheme;
                        }
                    }

                    if (!empty($row->mrp)) {
                        $retailer_margin = $row->mrp - ($row->mrp / (1 + ($margin / 100)));
                        $ptr = $row->mrp - $retailer_margin;
                    } else {
                        $ptr = 0;
                    }
                    $scheme_md =  round($ptr * ($scheme / 100), 2);
                    $ptr_af_sch =  $ptr - $scheme_md;

                    return round($ptr_af_sch, 2);
                })
                ->addColumn('scheme_md', function ($row) use ($pricing) {
                    $margin = 0;
                    $scheme = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    foreach ($pricing->get_scheme as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $scheme = $item->scheme;
                        }
                    }

                    if (!empty($row->mrp)) {
                        $retailer_margin = $row->mrp - ($row->mrp / (1 + ($margin / 100)));
                        $ptr = $row->mrp - $retailer_margin;
                    } else {
                        $ptr = 0;
                    }
                    $scheme_md = $ptr * ($scheme / 100);
                    return round($scheme_md, 2);
                })
                //for same brand and format
                ->addColumn('scheme', function ($row) use ($pricing) {
                    foreach ($pricing->get_scheme as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            return $item->scheme;
                        }
                    }
                })
                ->addColumn('ptr', function ($row) use ($pricing) {
                    $margin = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    if (!empty($row->mrp)) {
                        $retailer_margin = round($row->mrp - ($row->mrp / (1 + ($margin / 100))), 1);
                        return $row->mrp - $retailer_margin;
                    } else {
                        return 0;
                    }
                })
                //for same brand and format
                ->addColumn('margin', function ($row) use ($pricing) {
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            return $item->margin;
                        }
                    }
                })
                ->addColumn('retailer_margin', function ($row) use ($pricing) {
                    $margin = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    if (!empty($row->mrp)) {
                        return round($row->mrp - ($row->mrp / (1 + ($margin / 100))), 1);
                    } else {
                        return 0;
                    }
                })
                ->addColumn('derived_mrp', function ($row) use ($pricing) {
                    $margin = 0;
                    foreach ($pricing->get_margin as $item) {
                        if (
                            $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id &&
                            $item->variant == $row->variant && $item->buom_pack_size == $row->buom_pack_size . ' ' . $row->get_uom->uom_name
                        ) {
                            $margin = $item->margin;
                        }
                    }
                    if (!empty($row->mrp)) {
                        $retailer_margin = round($row->mrp - ($row->mrp / (1 + ($margin / 100))), 1);
                        $ptr = $row->mrp - $retailer_margin;
                    } else {
                        $ptr = 0;
                        $retailer_margin = 0;
                    }
                    return $ptr + $retailer_margin;
                })
                ->addColumn('intended_mrp_exc', function ($row) {
                    $gstPercentage = $row->get_gst->gst_percent / 100; // Convert percentage to decimal

                    if (!empty($row->mrp)) {
                        return round($row->mrp / (1 + $gstPercentage));
                    } else {
                        return 0;
                    }
                })

                // ->rawColumns(['margin'])
                ->make(true);
        }


        return view('backend.marginscheme.pricingladder_form', compact('pricing'));
    }

    public function scheme_form($id, Request $request)
    {

        $products = Products::select('brand_id', 'sub_category_id', 'variant', 'buom_pack_size', 'uom_id')
            ->distinct()
            ->get();

        $pricing = Pricings::where('pricing_master_id', $id)->first();


        if ($request->ajax()) {
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('brand_id', fn ($row) => $row->brand->brand_name ?? '')
                ->addColumn('sub_category_id', fn ($row) => $row->sub_category->subcategory_name ?? '')
                ->addColumn('variant', fn ($row) => $row->variants->name ?? '')
                ->addColumn('buom_pack_size', function ($row) {
                    if ($row->get_uom !== null) {
                        return $row->buom_pack_size . ' ' . $row->get_uom->uom_name;
                    } else {
                        return $row->buom_pack_size ?? '';
                    }
                })
                ->addColumn('scheme', function ($row) use ($id) {
                    if ($row->get_uom !== null) {
                        $buom_pack_size =  $row->buom_pack_size . ' ' . $row->get_uom->uom_name;
                    } else {
                        $buom_pack_size =  $row->buom_pack_size ?? '';
                    }
                    $scheme_data = Scheme::where([
                        'pricing_master_id' => $id,
                        'brand_id' => $row->brand_id,
                        'sub_category_id' => $row->sub_category_id,
                        'variant' => $row->variant
                    ])
                        ->where('buom_pack_size', $buom_pack_size)
                        ->first();

                    return $scheme_data->scheme ?? 0.00;
                })
                ->rawColumns(['scheme'])
                ->make(true);
        }

        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");

        return view('backend.marginscheme.scheme_form', compact('business_partner_category', 'pricing'));
    }

    public function subdmargin(Request $request)
    {

        $company = Company::where('is_subd', 1)->get();


        if ($request->ajax()) {
            return DataTables::of($company)
                ->addIndexColumn()
                ->addColumn('customer_code', fn ($row) => $row->company_id ?? '')
                ->addColumn('customer_name', fn ($row) => $row->name ?? '')
                ->addColumn('customer_name', fn ($row) => $row->name ?? '')
                ->addColumn('location', fn ($row) => $row->city ?? '')
                ->addColumn('margin', function ($row) {
                    return  0;
                })
                ->make(true);
        }

        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");

        return view('backend.marginscheme.subdmargin', compact('business_partner_category'));
    }

    public function pricingladder(Request $request)
    {

        $pricings = Pricings::where('pricing_type', 'ladder')->get();
        return view('backend.marginscheme.marginladder', compact('pricings'));
    }

    public function margin()
    {
        $pricings = Pricings::where('pricing_type', 'margin')->get();
        return view('backend.marginscheme.margin', compact('pricings'));
    }

    public function mrp(Request $request)
    {
        $products = Products::with('brand', 'sub_category', 'variants', 'get_combi_type')->get();


        if ($request->ajax()) {
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('brand_id', fn ($row) => $row->brand->brand_name ?? '')
                ->addColumn('sub_category_id', fn ($row) => $row->sub_category->subcategory_name ?? '')
                ->addColumn('variant', fn ($row) => $row->variants->name ?? '')
                ->addColumn('combi_type', fn ($row) => $row->get_combi_type->name ?? '')
                ->make(true);
        }


        return view('backend.marginscheme.mrp');
    }

    public function scheme()
    {
        $pricings = Pricings::where('pricing_type', 'scheme')->get();
        return view('backend.marginscheme.scheme', compact('pricings'));
    }

    public function margin_create()
    {
        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");

        return view('backend.marginscheme.margin_create', compact('business_partner_category'));
    }

    public function scheme_create()
    {
        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");

        return view('backend.marginscheme.scheme_create', compact('business_partner_category'));
    }

    public function pricingladder_create()
    {
        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");

        return view('backend.marginscheme.pricingladder_create', compact('business_partner_category'));
    }

    public function margin_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'pricing_name' => 'required',
            'bp_category' => 'required',
            'bp_channel' => 'required',
            'bp_group' => [
                'required',
                Rule::unique('pricing_master')->where(function ($query) use ($request) {
                    return $query->where('bp_category', $request->input('bp_category'))
                        ->where('bp_channel', $request->input('bp_channel'))
                        ->where('bp_group', $request->input('bp_group'))
                        ->whereNull('deleted_at')
                        ->where('pricing_type', 'margin');
                })
            ],
        ]);

        $pricings = new Pricings();
        $pricings->fill($request->all());
        if ($pricings->save()) {

            $log = ['module' => 'Margin', 'action' => 'Margin Created', 'description' => 'Margin Created Name=> ' . $pricings->pricing_name];
            captureActivity($log);

            return redirect()->route('admin.margin')->with('success', 'New Margin Pricing Added');
        }
    }

    public function margin_modify(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'pricing_name' => 'required',
            'bp_category' => 'required',
            'bp_channel' => 'required',
            'bp_group' => [
                'required',
                Rule::unique('pricing_master')->where(function ($query) use ($request) {
                    return $query->where('bp_category', $request->input('bp_category'))
                        ->where('bp_channel', $request->input('bp_channel'))
                        ->where('pricing_type', 'margin')
                        ->whereNull('deleted_at')
                        ->where('pricing_master_id', '!=', $request->pricing_master_id); // Exclude current record
                })
            ],
        ]);

        $pricings = Pricings::where('pricing_master_id', $request->pricing_master_id)->first();
        $pricings->fill($request->all());
        if ($pricings->save()) {


            if ($pricings->getChanges()) {
                $new_changes = $pricings->getChanges();
                $log = ['module' => 'Margin', 'action' => 'Margin Updated', 'description' => 'Margin Updated Name=> ' . $pricings->pricing_name];
                captureActivityupdate($new_changes, $log);
            }

            return redirect()->route('admin.margin')->with('success', 'Margin Pricing Updated');
        }
    }

    public function scheme_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'pricing_name' => 'required',
            'bp_category' => 'required',
            'bp_channel' => 'required',
            'bp_group' => [
                'required',
                Rule::unique('pricing_master')->where(function ($query) use ($request) {
                    return $query->where('bp_category', $request->input('bp_category'))
                        ->where('bp_channel', $request->input('bp_channel'))
                        ->where('bp_group', $request->input('bp_group'))
                        ->whereNull('deleted_at')
                        ->where('pricing_type', 'scheme');
                })
            ],
        ]);
        $pricings = new Pricings();
        $pricings->fill($request->all());
        if ($pricings->save()) {

            $log = ['module' => 'Scheme', 'action' => 'Scheme Updated', 'description' => 'Scheme Updated Name=> ' . $pricings->pricing_name];
            captureActivity($log);

            return redirect()->route('admin.scheme')->with('success', 'New Scheme Pricing Added');
        }
    }

    public function scheme_modify(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'pricing_name' => 'required',
            'bp_category' => 'required',
            'bp_channel' => 'required',
            'bp_group' => [
                'required',
                Rule::unique('pricing_master')->where(function ($query) use ($request) {
                    return $query->where('bp_category', $request->input('bp_category'))
                        ->where('bp_channel', $request->input('bp_channel'))
                        ->where('pricing_type', 'scheme')
                        ->whereNull('deleted_at')
                        ->where('pricing_master_id', '!=', $request->pricing_master_id); // Exclude current record
                })
            ],
        ]);

        $pricings = Pricings::where('pricing_master_id', $request->pricing_master_id)->first();
        $pricings->fill($request->all());
        if ($pricings->save()) {

            if ($pricings->getChanges()) {
                $new_changes = $pricings->getChanges();
                $log = ['module' => 'Scheme', 'action' => 'Scheme Updated', 'description' => 'Scheme Updated Name=> ' . $pricings->pricing_name];
                captureActivityupdate($new_changes, $log);
            }

            return redirect()->route('admin.scheme')->with('success', 'Scheme Pricing Updated');
        }
    }

    public function pricingladder_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'pricing_name' => 'required',
            'margin' => 'required',
            'scheme' => 'required',
            'distributor_margin' => 'required',
            'bp_channel' => [
                'required',
                Rule::unique('pricing_master')->where(function ($query) use ($request) {
                    return $query->where('bp_channel', $request->input('bp_channel'))
                        ->where('margin', $request->input('margin'))
                        ->where('scheme', $request->input('scheme'))
                        ->whereNull('deleted_at')
                        ->where('pricing_type', 'ladder');
                })
            ],
        ]);

        //save same group as margin for ladder
        $margin = Pricings::where(['pricing_type' => 'margin', 'pricing_master_id' => $request->margin])->first();

        $pricings = new Pricings();
        $pricings->fill($request->all());
        $pricings->status = 0;
        $pricings->bp_group = $margin->bp_group;
        if ($pricings->save()) {

            $log = ['module' => 'Pricing Ladder', 'action' => 'Pricing Ladder Updated', 'description' => 'Pricing Ladder Updated Name=> ' . $pricings->pricing_name];
            captureActivity($log);

            return redirect()->route('admin.pricingladder')->with('success', 'New Pricing Ladder Added');
        }
    }

    public function pricingladder_modify(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'pricing_name' => 'required',
            'margin' => 'required',
            'scheme' => 'required',
            'distributor_margin' => 'required',
            'bp_channel' => [
                'required',
                Rule::unique('pricing_master')->where(function ($query) use ($request) {
                    return $query->where('bp_channel', $request->input('bp_channel'))
                        ->where('margin', $request->input('margin'))
                        ->where('scheme', $request->input('scheme'))
                        ->where('pricing_type', 'ladder')
                        ->whereNull('deleted_at')
                        ->where('pricing_master_id', '!=', $request->pricing_master_id); // Exclude current record
                })
            ],
        ]);

        $pricings = Pricings::where('pricing_master_id', $request->pricing_master_id)->first();

        //save same group as margin for ladder
        $margin = Pricings::where(['pricing_type' => 'margin', 'pricing_master_id' => $request->margin])->first();

        $pricings->fill($request->all());
        $pricings->bp_group = $margin->bp_group;
        if ($pricings->save()) {

            if ($pricings->getChanges()) {
                $new_changes = $pricings->getChanges();
                $log = ['module' => 'Pricing Ladder', 'action' => 'Pricing Ladder Updated', 'description' => 'Pricing Ladder Updated Name=> ' . $pricings->pricing_name];
                captureActivityupdate($new_changes, $log);
            }

            return redirect()->route('admin.pricingladder')->with('success', 'Pricing Ladder Updated');
        }
    }

    public function margin_edit($id)
    {
        $pricings = Pricings::where('pricing_master_id', $id)->first();
        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");

        return view('backend.marginscheme.margin_edit', compact('pricings', 'business_partner_category'));
    }
    public function pricingladder_edit($id)
    {
        $pricings = Pricings::where('pricing_master_id', $id)->first();
        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");

        return view('backend.marginscheme.pricingladder_edit', compact('pricings', 'business_partner_category'));
    }

    public function scheme_edit($id)
    {
        $pricings = Pricings::where('pricing_master_id', $id)->first();
        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");

        return view('backend.marginscheme.scheme_edit', compact('pricings', 'business_partner_category'));
    }

    public function margin_destroy($id)
    {
        $pricings = Pricings::findOrFail($id);
        if ($pricings->delete()) {
            // Margin::where('pricing_master_id', $id)->delete();
            $log = ['module' => 'Margin', 'action' => 'Margin Updated', 'description' => 'Margin Updated Name=> ' . $pricings->pricing_name];
            captureActivity($log);
        }

        Session::flash('success', 'Margin Pricing deleted!');
        Session::flash('status', 'success');

        return redirect('admin/margin');
    }

    public function pricingladder_destroy($id)
    {
        $pricings = Pricings::findOrFail($id);
        if ($pricings->delete()) {
            // Margin::where('pricing_master_id', $id)->delete();
            $log = ['module' => 'Pricing Ladder', 'action' => 'Pricing Ladder Updated', 'description' => 'Pricing Ladder Updated Name=> ' . $pricings->pricing_name];
            captureActivity($log);
        }

        Session::flash('success', 'Pricing Ladder deleted!');
        Session::flash('status', 'success');

        return redirect('admin/pricingladder');
    }

    public function scheme_destroy($id)
    {
        $pricings = Pricings::findOrFail($id);
        if ($pricings->delete()) {
            // Margin::where('pricing_master_id', $id)->delete();
            $log = ['module' => 'Scheme', 'action' => 'Scheme Updated', 'description' => 'Scheme Updated Name=> ' . $pricings->pricing_name];
            captureActivity($log);
        }

        Session::flash('success', 'Scheme Pricing deleted!');
        Session::flash('status', 'success');

        return redirect('admin/scheme');
    }
} //end of class
