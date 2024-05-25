<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\InternalUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use Spatie\Permission\Models\Role;
use App\Models\backend\Products;

use App\Models\backend\PricingItem;
use App\Models\backend\SubCategories;
use App\Models\backend\UoMs;
use App\Models\backend\Pricings;
use App\Models\backend\Pricingslog;
use Carbon\Exceptions\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PricingsController extends Controller
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
        $pricings = Pricings::where('pricing_type', 'purchase')->get();
        return view('backend.pricings.index', compact('pricings'));
    }

    // //create new user
    public function create()
    {
        return view('backend.pricings.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'company_id' => 'required',
            'pricing_name' => 'required',
        ]);
        $pricings = new Pricings;
        $pricings->fill($request->all());
        if ($pricings->save()) {

            $log = ['module' => 'Pricing', 'action' => 'Pricing Updated', 'description' => 'Pricing Updated Name=> ' . $pricings->pricing_name];
            captureActivity($log);

            return redirect()->route('admin.pricings')->with('success', 'New Pricing Added');
        }
    }


    public function delete($id)
    {
        $data = Products::where('product_item_id', $id)->get();
        if (count($data) > 0) {
            if (Products::where('product_item_id', $id)->delete()) {
                return redirect('/admin/pricings')->with('success', 'Partner Has Been Deleted');
            }
        }
    }

    //edit pricings
    public function edit($id)
    {
        $pricings = Pricings::where('pricing_master_id', $id)->first();
        return view('backend.pricings.edit', compact('pricings'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'company_id' => 'required',
            'pricing_name' => 'required',
        ]);

        $pricings = Pricings::where('pricing_master_id', $request->pricing_master_id)->first();
        $pricings->fill($request->all());
        if ($pricings->save()) {

            if ($pricings->getChanges()) {
                $new_changes = $pricings->getChanges();
                $log = ['module' => 'Pricing', 'action' => 'Pricing Updated', 'description' => 'Pricing Updated Name=> ' . $pricings->pricing_name];
                captureActivityupdate($new_changes, $log);
            }

            return redirect()->route('admin.pricings')->with('success', 'Pricing Added');
        }
    }

    public function destroy($id)
    {
        $pricings = Pricings::findOrFail($id);
        if ($pricings->delete()) {
            PricingItem::where('pricing_master_id', $id)->delete();

            $log = ['module' => 'Pricing', 'action' => 'Pricing Updated', 'description' => 'Pricing Updated Name=> ' . $pricings->pricing_name];
            captureActivity($log);
        }

        Session::flash('success', 'Pricing deleted!');
        Session::flash('status', 'success');

        return redirect('admin/pricings');
    }

    public function setpricing(Request $request, $id)
    {
        $pricings = Pricings::where('pricing_master_id', $id)->first();
        $products = Products::all();
        if ($request->ajax()) {
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('brand.brand_name', fn ($row) => $row->brand->brand_name ?? '')
                ->addColumn('category.category_name', fn ($row) => $row->category->category_name ?? '')
                ->addColumn('sub_category.subcategory_name', fn ($row) => $row->sub_category->subcategory_name ?? '')
                ->addColumn('variants.name', fn ($row) => $row->variants->name ?? '')
                ->addColumn('selling_price', function ($row) use ($id) {
                    $pricing_item = PricingItem::where(['item_code' => $row->item_code, 'pricing_master_id' => $id])->first();
                    return $pricing_item->selling_price ?? 0;
                })
                ->rawColumns(['selling_price'])
                ->make(true);
        }
        $pricing_items = PricingItem::where('pricing_master_id', $id)->get();
        return view('backend.pricings.setpricing', compact('pricings', 'pricing_items', 'products'));
    }

    public function updatepricings(Request $request)
    {

        // dd($request->all());
        $invoiceItems = $request->input('invoice_items');
        if (!empty($invoiceItems)) {
            foreach ($invoiceItems as $item) {
                $pricings = PricingItem::where(['pricing_master_id' => $request->pricing_master_id, 'sku' => $item['sku'], 'item_code' => $item['item_code']])->first();
                if (!empty($pricings)) {
                    $pricings->selling_price = $item['selling_price'];
                } else {
                    $pricings = new PricingItem();
                    $pricings->fill($item);
                    $pricings->pricing_master_id = $request->pricing_master_id;
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
                $uploadedFile->move(public_path('uploads/' . $request->pricing_master_id), $filename);


                foreach ($row_range as $row) {

                    $isEmptyRow = true;
                    foreach ($column_range as $column) {
                        if (!empty($sheet->getCell($column . $row)->getValue())) {
                            $isEmptyRow = false;
                            break;
                        }
                    }
                    if ($srno > 0  &&  !$isEmptyRow) {
                        $data = [
                            // 'sku' => trim(addslashes($sheet->getCell('B' . $row)->getValue())),
                            'item_code' => trim(addslashes($sheet->getCell('B' . $row)->getValue())),
                            'consumer_desc' => trim(addslashes($sheet->getCell('C' . $row)->getValue())),
                            'brand_id' => trim(addslashes($sheet->getCell('D' . $row)->getValue())),
                            'category_id' => trim(addslashes($sheet->getCell('E' . $row)->getValue())),
                            'sub_category_id' => trim(addslashes($sheet->getCell('F' . $row)->getValue())),
                            'variant' => trim(addslashes($sheet->getCell('G' . $row)->getValue())),
                            'selling_price' => number_format(trim(addslashes($sheet->getCell('H' . $row)->getValue())), 2, '.', '')
                            // Add more fields as needed
                        ];

                        $pricings = PricingItem::where(['pricing_master_id' => $request->pricing_master_id, 'item_code' => $data['item_code']])->first();

                        if ($data['selling_price'] != 0) {
                            if (!empty($pricings)) {
                                $pricings->selling_price = $data['selling_price'];
                                $product = Products::where('item_code', $data['item_code'])->first();
                                $pricings->sku = $product->sku;
                            } else {
                                $pricings = new PricingItem();
                                $pricings->fill($data);
                                $product = Products::where('item_code', $data['item_code'])->first();
                                $pricings->sku = $product->sku;
                                $pricings->pricing_master_id = $request->pricing_master_id;
                            }
                            $pricings->save();
                            $pricing_log = new Pricingslog();
                            $pricing_log->fill($data);
                            $pricing_log->pricing_master_id = $request->pricing_master_id;
                            $pricing_log->save();
                        }

                        // array_push($imported_data, $data);
                    }
                    $startcount++;
                    $srno++;
                    // $data = User::all();
                }
                // dd($imported_data);
            } catch (Exception $e) {
                $error_code = $e->errorInfo[1];
            }
        }
        return redirect()->back()->with('success', 'Pricing Updated');
        // return redirect('/admin/pricings')->with('success', 'Pricing Updated');
    }

    public function item_codes()
    {
        $products = Products::where(['sku' => $_GET['sku']])->pluck('item_code', 'item_code')->toArray();
        if (!empty($products)) {
            $options = '<option value="">Select Item</option>';
            foreach ($products as $binTypeId => $binTypeName) {
                $options .= '<option value="' . $binTypeId . '">' . $binTypeName . '</option>';
            }


            return $options;
        } else {
            return false;
        }
    }

    public function get_product()
    {
        $products = Products::where(['item_code' => $_GET['item_code']])->first();
        if (!empty($products)) {

            return $products->consumer_desc;
        } else {
            return false;
        }
    }
} //end of class
