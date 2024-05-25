<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\ActivityLog;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\Brands;

use App\Models\backend\Categories;
use App\Models\backend\Gst;
use App\Models\backend\HSNCodes;
use App\Models\backend\ItemTypes;
use App\Models\backend\ProductQtyStorage;
use App\Models\backend\ProductQtyStorageRevision;
use App\Models\backend\ProductRevision;
use App\Models\backend\Products;
use App\Models\backend\StorageLocations;
use App\Models\backend\SubCategories;
use App\Models\backend\UoMs;
use App\Models\backend\Variant;
use Carbon\Carbon;

class ProductshistoryController extends Controller
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
        $startDate = Carbon::now()->subYear(); // Get the date one year ago from now

        $products = ProductRevision::whereBetween('created_at', [$startDate, now()])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('backend.productshistory.index', compact('products'));
    }

    public function show($id)
    {
        $products = ProductRevision::where('product_revision_id', $id)->first();
        $categories = Categories::where('visibility', 1)->pluck('category_name', 'category_id');
        $sub_categories = SubCategories::where('visibility', 1)->pluck('subcategory_name', 'subcategory_id');
        $hsncodes = HSNCodes::pluck('hsncode_desc', 'hsncode_desc');
        $item_types = ItemTypes::pluck('item_type_name', 'item_type_id');
        $brands = Brands::pluck('brand_name', 'brand_id');
        $variants = Variant::pluck('name', 'id');

        $qty_location = ProductQtyStorageRevision::where('product_item_id', $products->product_item_id)->get();
        // dd($products);
        $gst = Gst::pluck('gst_percent', 'gst_id');
        $uoms = UoMs::pluck('uom_name', 'uom_id');
        $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id');
        return view('backend.productshistory.show', compact('products', 'categories', 'variants','sub_categories', 'hsncodes', 'item_types', 'brands', 'uoms', 'storage_locations', 'qty_location', 'gst'));
    }
}
