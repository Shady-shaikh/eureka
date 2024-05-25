<?php

namespace App\Http\Controllers\backend;

use Session;
use Carbon\Carbon;

use App\Http\Requests;
use App\Models\backend\Gst;
use App\Models\backend\UoMs;
use Illuminate\Http\Request;
use App\Models\backend\Brands;

use Illuminate\Validation\Rule;
use App\Models\backend\HSNCodes;
use App\Models\backend\Products;
use App\Models\backend\UoMGroup;
use App\Models\backend\UoMTypes;
use App\Models\backend\ItemTypes;
use App\Models\backend\Categories;
use Spatie\Permission\Models\Role;
use App\Models\backend\TermPayment;
use App\Http\Controllers\Controller;
use App\Models\backend\InternalUser;
use App\Models\backend\SubCategories;
use App\Models\backend\StorageLocations;
use App\Models\backend\ProductQtyStorage;
use App\Models\backend\BussinessMasterType;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\BussinessPartnerContactDetails;
use App\Models\backend\BussinessPartnerOrganizationType;
use App\Models\backend\Combitype;
use App\Models\backend\ProductQtyStorageRevision;
use App\Models\backend\ProductRevision;
use App\Models\backend\Variant;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Session as FacadesSession;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
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
        if ($request->ajax()) {
            $products = Products::with('category', 'sub_category', 'item_type', 'brand')
                ->orderBy('product_item_id', 'desc')->get();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($product) {
                    $actionBtn = '<div id="action_buttons">';
                    if (request()->user()->can('Update Products')) {
                        $actionBtn .= '<a href="' . route('admin.products.edit', ['id' => $product->product_item_id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                    }
                    if (request()->user()->can('Delete Products')) {
                        $actionBtn .= '<a href="' . route('admin.products.delete', ['id' => $product->product_item_id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a>';
                    }
                    return $actionBtn;
                })
                ->addColumn('visibility', fn ($row) => $row->visibility == 1 ? 'Yes' : 'No')
                ->addColumn('combi_type', fn ($row) => $row->get_combi_type->name ?? '')
                ->addColumn('dimensions_net_uom_id', fn ($row) => $row->get_side_uom->uom_name ?? '')
                ->addColumn('gst_id', fn ($row) => $row->get_gst->gst_name ?? '')
                ->addColumn('uom_id', fn ($row) => $row->get_uom->uom_name ?? '')
                ->addColumn('dimensions_length_uom_id', fn ($row) => $row->get_side_uom->uom_name ?? '')
                ->addColumn('hsncode_id', fn ($row) => $row->hsncode_id ?? '')
                ->addColumn('item_type_id', fn ($row) => $row->item_type->item_type_name ?? '')
                ->addColumn('brand_id', fn ($row) => $row->brand->brand_name ?? '')
                ->addColumn('category_id', fn ($row) => $row->category->category_name ?? '')
                ->addColumn('sub_category_id', fn ($row) => $row->sub_category->subcategory_name ?? '')
                ->addColumn('variant', fn ($row) => $row->variants->name ?? '')
                ->addColumn('product_thumb', function ($row) {
                    if (!empty($row->product_thumb)) {
                        $url = asset('public/backend-assets/images/') . '/' . $row->product_thumb;
                        $html = '<a href="' . $url . '" target="_blank">' .
                            '<img class="card-img-top img-fluid mb-1" src="' . $url . '" alt="Product Image" style="width:50px">' .
                            '</a>';
                    } else {
                        $html = '';
                    }
                    return $html;
                })
                ->rawColumns(['product_thumb', 'action'])
                ->make(true);
        }

        return view('backend.products.index');
    }

    public function updateProduct(Request $request)
    {

        // dd($request->all());
        // for import
        $excel_file = $request->file('file');
        try {

            $spreadsheet = IOFactory::load($excel_file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $row_limit = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range = range(1, $row_limit);
            $column_range = range('A', $column_limit);
            $startcount = 1;
            $srno = 0;
            $uploadedFile = $excel_file;
            $filename = date('Y-m-d_H-i-s') . '_' . str_replace(' ', '_', $uploadedFile->getClientOriginalName());
            $uploadedFile->move(public_path('uploads/products'), $filename);


            foreach ($row_range as $row) {
                $isEmptyRow = true;
                foreach ($column_range as $column) {
                    if (!empty($sheet->getCell($column . $row)->getValue())) {
                        $isEmptyRow = false;
                        break;
                    }
                }

                if ($srno > 0 &&  !$isEmptyRow) {

                    $gst_percent =  $numericPart = preg_replace('/[^0-9]/', '', $sheet->getCell('AB' . $row)->getFormattedValue());

                    $buom_pack_size_value = trim(addslashes($sheet->getCell('I' . $row)->getValue()));
                    $buom_pack_size = intval($buom_pack_size_value); // Extracts the integer part of the string
                    $unit_part = trim(str_replace($buom_pack_size, '', $buom_pack_size_value)); // Removes the numeric part from the string


                    $data = [
                        // 'item_type_id' => trim(addslashes($sheet->getCell('A' . $row)->getValue())),
                        'item_type_id' => getOrCreateIdUnified(ItemTypes::class, 'item_type_name', $sheet->getCell('A' . $row)->getValue(), 'item_type_id'),
                        'item_code' => trim(addslashes($sheet->getCell('B' . $row)->getValue())),
                        'consumer_desc' => trim(addslashes($sheet->getCell('C' . $row)->getFormattedValue())),
                        'product_desc' => trim(addslashes($sheet->getCell('D' . $row)->getValue())),
                        'brand_id' => getOrCreateIdUnified(Brands::class, 'brand_name', $sheet->getCell('E' . $row)->getValue(), 'brand_id'),
                        'category_id' => getOrCreateIdUnified(Categories::class, 'category_name', $sheet->getCell('F' . $row)->getValue(), 'category_id'),
                        'sub_category_id' => getOrCreateIdUnified(SubCategories::class, 'subcategory_name', $sheet->getCell('G' . $row)->getValue(), 'subcategory_id'),
                        'variant' => getOrCreateIdUnified(Variant::class, 'name', $sheet->getCell('H' . $row)->getValue(), 'id'),
                        'buom_pack_size' => $buom_pack_size,
                        'uom_id' => getOrCreateIdUnified(UoMs::class, 'uom_name', $unit_part, 'uom_id'),
                        'unit_case' => trim(addslashes($sheet->getCell('K' . $row)->getValue())),
                        'hsncode_id' =>  trim(addslashes($sheet->getCell('L' . $row)->getValue())),
                        'shelf_life_number' =>  trim(addslashes($sheet->getCell('M' . $row)->getValue())),
                        'shelf_life' =>  trim(addslashes($sheet->getCell('N' . $row)->getValue())),
                        'sourcing' =>  trim(addslashes($sheet->getCell('O' . $row)->getValue())),
                        'case_pallet' =>  trim(addslashes($sheet->getCell('P' . $row)->getValue())),
                        'layer_pallet' =>  trim(addslashes($sheet->getCell('Q' . $row)->getValue())),
                        'dimensions_unit_pack' =>  trim(addslashes($sheet->getCell('R' . $row)->getValue())),
                        'dimensions_length' =>  trim(addslashes($sheet->getCell('S' . $row)->getValue())),
                        'dimensions_width' =>  trim(addslashes($sheet->getCell('T' . $row)->getValue())),
                        'dimensions_height' =>  trim(addslashes($sheet->getCell('U' . $row)->getValue())),
                        // 'dimensions_length_uom_id' =>  trim(addslashes($sheet->getCell('V' . $row)->getValue())),
                        'dimensions_length_uom_id' => getOrCreateIdUnified(UoMs::class, 'uom_name', $sheet->getCell('V' . $row)->getValue(), 'uom_id'),
                        'dimensions_net_weight' =>  trim(addslashes($sheet->getCell('W' . $row)->getCalculatedValue())),
                        'dimensions_gross_weight' =>  trim(addslashes($sheet->getCell('X' . $row)->getCalculatedValue())),
                        // 'dimensions_net_uom_id' =>  trim(addslashes($sheet->getCell('Y' . $row)->getValue())),
                        'dimensions_net_uom_id' => getOrCreateIdUnified(UoMs::class, 'uom_name', $sheet->getCell('Y' . $row)->getValue(), 'uom_id'),
                        'ean_barcode' =>  trim(addslashes($sheet->getCell('Z' . $row)->getValue())),
                        'mrp' => trim(addslashes($sheet->getCell('AA' . $row)->getCalculatedValue())),
                        // 'gst_id' => (int) trim(addslashes($sheet->getCell('AB' . $row)->getValue())),
                        'gst_id' => getOrCreateIdUnified(Gst::class, 'gst_name', $sheet->getCell('AB' . $row)->getFormattedValue(), 'gst_id', $gst_percent),
                        'visibility' => (int) trim(addslashes($sheet->getCell('AC' . $row)->getValue() == 'Yes' ? 1 : 0)),
                        'combi_type' => getOrCreateIdUnified(Combitype::class, 'name', $sheet->getCell('AD' . $row)->getValue(), 'id'),
                        'combi_type_int' => trim(addslashes($sheet->getCell('AD' . $row)->getValue())),

                        // Add more fields as needed
                    ];

                    // dd($data);

                    // usama_16-02-2024- generate sku
                    $sku = $data['brand_id']  . $data['sub_category_id'] . $data['variant'];

                    // dd($data);
                    $pricings = Products::where(['item_code' => $data['item_code']])->first();

                    //dont insert product with same item code again
                    if (!empty($pricings)) {
                        $pricings->fill($data);
                        $pricings->sku = $sku;
                        $pricings->save();
                    } else {
                        $pricings = new Products();
                        $pricings->fill($data);
                        $pricings->sku = $sku;
                        $pricings->save();

                        $productsRevision = new ProductRevision();
                        $productsRevision->fill($data);
                        $productsRevision->sku = $sku;
                        $productsRevision->save();
                    }


                    // array_push($imported_data, $data);
                }
                $startcount++;
                $srno++;
                // $data = User::all();

                $log = ['module' => 'Products', 'action' => 'Product Created', 'description' => 'Product Created: ' . $data['item_code']];
                captureActivity($log);
            }
            // dd($imported_data);
        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            return redirect()->back()->with('error', $error_code);
        }

        return redirect()->back()->with('success', 'Products Updated');
        // return redirect('/admin/pricings')->with('success', 'Pricing Updated');
    }

    // //create new user
    public function create()
    {
        $categories = Categories::where('visibility', 1)->pluck('category_name', 'category_id');
        $sub_categories = SubCategories::where('visibility', 1)->pluck('subcategory_name', 'subcategory_id');
        $hsncodes = HSNCodes::pluck('hsncode_desc', 'hsncode_desc');
        $item_types = ItemTypes::pluck('item_type_name', 'item_type_id');
        $brands = Brands::pluck('brand_name', 'brand_id');
        $variants = Variant::pluck('name', 'id');
        $uoms = UoMs::pluck('uom_name', 'uom_id');
        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id');
        $uom_types = UoMTypes::pluck('uom_type', 'uom_type_id');
        $gst = Gst::pluck('gst_percent', 'gst_id');
        // dd($categories);
        return view('backend.products.create', compact('categories', 'variants', 'sub_categories', 'hsncodes', 'item_types', 'brands', 'uoms', 'storage_locations', 'uom_types', 'gst'));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            // 'item_type_id' => 'required',
            'item_code' => 'required|unique:product_item_sku_master,item_code',
            // 'brand_id' => 'required',
            // 'category_id' => 'required',
            // 'sub_category_id' => 'required',
            // 'hsncode_id' => 'required',
        ]);
        $data = $request->all();

        // unset($data['qty']);
        // unset($data['storage_location_id']);

        // client said to diable
        // $quantities = $request->qty;
        // dd($request->base_qty);
        // $quantities = [1, 1, 1, 1];

        $locations = $request->storage_location_id;

        $products = new Products;
        $productsRevision = new ProductRevision();
        $products->fill($data);
        if (!empty($request->product_thumb)) {
            $imageName = time() . '.' . $request->product_thumb->extension();
            if (!file_exists(public_path('backend-assets/images'))) {
                mkdir(public_path('backend-assets/images'), 0777);
            }
            $request->product_thumb->move(public_path('backend-assets/images'), $imageName);
            $products->product_thumb = $imageName;
        }

        $sku = $request->brand_id  . $request->sub_category_id . $request->variant;
        $products->sku = $sku;

        // $products->save();
        if ($products->save()) {
            $productsRevision->fill($products->toArray());
            $productsRevision->save();

            $log = ['module' => 'Products', 'action' => 'Product Created', 'description' => 'Product Created: ' . $request->item_code];
            captureActivity($log);


            return redirect('/admin/products')->with('success', 'New Product Added');
        }
    }


    public function delete($id)
    {
        $data = Products::where('product_item_id', $id)->get();
        if (count($data) > 0) {
            if (Products::where('product_item_id', $id)->delete()) {
                return redirect('/admin/products')->with('success', 'Product Has Been Deleted');
            }
        }
    }

    //edit products
    public function edit($id)
    {
        $products = Products::where('product_item_id', $id)->first();
        $categories = Categories::where('visibility', 1)->pluck('category_name', 'category_id');
        $sub_categories = SubCategories::where('visibility', 1)->pluck('subcategory_name', 'subcategory_id');
        $hsncodes = HSNCodes::pluck('hsncode_desc', 'hsncode_desc');
        $item_types = ItemTypes::pluck('item_type_name', 'item_type_id');
        $brands = Brands::pluck('brand_name', 'brand_id');
        $qty_location = ProductQtyStorage::where('product_item_id', $id)->get();
        $gst = Gst::pluck('gst_percent', 'gst_id');


        $uoms = UoMs::pluck('uom_name', 'uom_id');
        $variants = Variant::pluck('name', 'id');
        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id');
        return view('backend.products.edit', compact('products', 'categories', 'variants', 'sub_categories', 'hsncodes', 'item_types', 'brands', 'uoms', 'storage_locations', 'qty_location', 'gst'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            // 'product_item_id' => 'required',
            // 'item_type_id' => 'required',
            // 'item_code' => 'required',
            // 'brand_id' => 'required',
            // 'category_id' => 'required',
            // 'sub_category_id' => 'required',
        ]);
        $data = $request->all();
        // unset($data['storage_location_id']);

        // client said
        // $quantities = $request->qty;
        $quantities = [1];

        $locations = $request->storage_location_id;

        $bussiness = Products::where('product_item_id', $request->product_item_id)->first();
        $productsRevision = new ProductRevision();
        $bussiness->fill($data);
        if (!empty($request->product_thumb)) {
            $imageName = time() . '.' . $request->product_thumb->extension();
            if (!file_exists(public_path('backend-assets/images'))) {
                mkdir(public_path('backend-assets/images'), 0777);
            }
            $request->product_thumb->move(public_path('backend-assets/images'), $imageName);
            $bussiness->product_thumb = $imageName;
        }


        $sku = $request->brand_id  . $request->category_id  . $request->sub_category_id  . $request->variant;
        $bussiness->sku = $sku;

        if ($bussiness->save()) {
            // dd($bussiness);



            $productsRevision->fill($bussiness->toArray());
            $productsRevision->save();

            if ($bussiness->getChanges()) {
                $new_changes = $bussiness->getChanges();
                $log = ['module' => 'Products', 'action' => 'Product Updated', 'description' => 'Product Updated: Name=>' . $bussiness->item_code];
                captureActivityupdate($new_changes, $log);
            }


            return redirect('/admin/products')->with('success', 'Product Has Been Updated');
        }
    }

    public function destroy($id)
    {
        $products = Products::findOrFail($id);
        $products->delete();

        $log = ['module' => 'Products', 'action' => 'Product Deleted', 'description' => 'Product Deleted: ' . $products->item_code];
        captureActivity($log);

        FacadesSession::flash('message', 'Product deleted!');
        FacadesSession::flash('status', 'success');

        return redirect('admin/products');
    }





    public function getsubcategory(Request $request)
    {
        $data = $request->all();
        $subcategory = SubCategories::where('category_id', $data['category_id'])->get();

        $subcategory_list = "<option value=''>Please Select</option>";

        foreach ($subcategory as $key => $value) {
            $subcategory_list .= "<option value='" . $value['subcategory_id'] . "'>" . $value['subcategory_name'] . "</option>";
        }
        if (count($subcategory) == 0) {
            $subcategory_list .= "<option value=''>No Record Found</option>";
        }
        return $subcategory_list;
    }
} //end of class
