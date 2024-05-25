<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\backend\Brands;
use App\Models\backend\Manufacturers;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Apinvoice;
use App\Models\backend\Area;
use App\Models\backend\ArInvoice;
use App\Models\backend\Beat;
use App\Models\backend\BillBooking;
use App\Models\backend\BillBookingItems;
use App\Models\backend\Bpgroup;
use App\Models\backend\BusinessPartnerCategory;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\Categories;
use App\Models\backend\Expensemaster;
use App\Models\backend\Financialyear;
use App\Models\backend\GLCodes;
use App\Models\backend\GoodsServiceReceipts;
use App\Models\backend\Gst;
use App\Models\backend\Company;
use App\Models\backend\HSNCodes;
use App\Models\backend\PricingItem;
use App\Models\backend\Pricings;
use App\Models\backend\Products;
use App\Models\backend\PurchaseOrder;
use App\Models\backend\Route;
use App\Models\backend\SeriesMaster;
use App\Models\backend\SubCategories;
use App\Models\backend\Variant;
use App\Models\backend\State;
use App\Models\backend\City;
use App\Models\backend\Combitype;
use App\Models\backend\Pricingladder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; //import Rule class
use Spatie\Permission\Models\Role;

class MasterDropdownController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function store_category(Request $request)
    {

        // dd($request->all());
        $model = new BusinessPartnerCategory();
        $model->business_partner_category_name = $request->data_name[0];
        $model->save();
        // $model->fill($request->all());

        $data = BusinessPartnerCategory::orderBy('created_at', 'desc')->pluck('business_partner_category_name', 'business_partner_category_id');
        // $brands->put('add_brand','Add Brand +');
        $data_options = "";
        foreach ($data as $key => $val) {
            $data_options .= '<option value="' . $key . '">' . $val . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Business Partner Channel Added!', 'data' => $data_options];
    }

    public function store_group(Request $request)
    {

        // dd($request->all());
        $model = new Bpgroup();
        $model->bp_channel_id = $request->data_name[0];
        $model->name = $request->data_name[1];
        $model->save();
        // $model->fill($request->all());

        $data = Bpgroup::orderBy('created_at', 'desc')->pluck('name', 'id');
        // $brands->put('add_brand','Add Brand +');
        $data_options = "";
        foreach ($data as $key => $val) {
            $data_options .= '<option value="' . $key . '">' . $val . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Business Partner Group Added!', 'data' => $data_options];
    }

    public function store_users(Request $request)
    {
        $email = $request->data_name[2];
        $check_user = AdminUsers::where(['email' => $email, 'account_status' => 1])->first();

        if (!empty($check_user)) {
            return ['flag' => 'error', 'message' => 'User exists', 'data' => []];
        }

        $model = new AdminUsers();
        $model->company_id = session('company_id');
        $model->first_name = $request->data_name[0];
        $model->last_name = $request->data_name[1];
        $model->email = $request->data_name[2];
        $model->parent_users = $request->data_name[3] ?? '';
        $model->password = 'Pass@123';
        $model->account_status = 1;
        $model->role_id = $request->role;
        $model->role = $request->role;
        $model->save();

        $data = AdminUsers::orderBy('created_at', 'desc')->where(['parent_users' => $request->data_name[3], 'role' => $request->role])->pluck('first_name', 'admin_user_id');
        $data_options = "";
        foreach ($data as $key => $val) {
            $data_options .= '<option value="' . $key . '">' . $val . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Item Added!', 'data' => $data_options];
    }

    public function store_area(Request $request)
    {

        // dd($request->all());
        $model = new Area();
        $model->area_name = $request->data_name[0];
        $model->save();
        // $model->fill($request->all());

        $data = Area::pluck('area_name', 'area_id');
        // $brands->put('add_brand','Add Brand +');
        $data_options = "";
        foreach ($data as $key => $val) {
            $data_options .= '<option value="' . $key . '">' . $val . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Area Added!', 'data' => $data_options];
    }

    public function store_brand(Request $request)
    {
        $brand = new Brands();
        $brand->brand_name = $request->data_name[0];
        $brand->save();

        $brands = Brands::orderBy('created_at', 'desc')->pluck('brand_name', 'brand_id');
        // $brands->put('add_brand','Add Brand +');
        $brand_options = "";
        foreach ($brands as $brand_id => $brand_name) {
            $brand_options .= '<option value="' . $brand_id . '">' . $brand_name . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Brand Added!', 'data' => $brand_options];
    }



    public function store_variant(Request $request)
    {
        $brand = new Variant();
        $brand->name = $request->data_name[0];
        $brand->save();

        $brands = Variant::orderBy('created_at', 'desc')->pluck('name', 'id');
        // $brands->put('add_brand','Add Brand +');
        $brand_options = "";
        foreach ($brands as $brand_id => $brand_name) {
            $brand_options .= '<option value="' . $brand_id . '">' . $brand_name . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Variant Added!', 'data' => $brand_options];
    }


    public function store_combitype(Request $request)
    {
        $combitype = new Combitype();
        $combitype->name = $request->data_name[0];
        $combitype->save();

        $combitype = Combitype::orderBy('created_at', 'desc')->pluck('name', 'id');
        // $brands->put('add_brand','Add Brand +');
        $brand_options = "";
        foreach ($combitype as $id => $name) {
            $brand_options .= '<option value="' . $id . '">' . $name . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Combi Type Added!', 'data' => $brand_options];
    }


    public function store_product_category(Request $request)
    {
        // dd($request->all());
        $brand = new Categories();
        $brand->category_name = $request->data_name[0];
        $brand->visibility = $request->data_name[1];
        $brand->save();

        $categories_list = Categories::orderBy('created_at', 'desc')->where('visibility', 1)->pluck('category_name', 'category_id');
        // $brands->put('add_brand','Add Brand +');
        $category_options = "";
        foreach ($categories_list as $category_id => $category_name) {
            $category_options .= '<option value="' . $category_id . '">' . $category_name . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Category Added!', 'data' => $category_options];
    }

    public function get_categories(Request $request)
    {
        $type = $request->input('id');
        if ($type != 1) {
            $categories_list = DB::table('bp_category')->orderBy('id', 'desc')->limit(2)->pluck('name', 'id');
        } else {
            $categories_list = DB::table('bp_category')->limit(2)->pluck('name', 'id');
        }

        return response()->json($categories_list);
    }

    public function get_ar_invoices(Request $request)
    {
        $id = $request->input('id');
        $invocies = ArInvoice::when((session('company_id') != 0 && session('fy_year')), function ($query) {
            $query->where([
                'company_id' => session('company_id'),
                'fy_year' => session('fy_year'),
            ]);
        })
            ->where('party_id', $id)
            ->pluck('bill_no', 'bill_no');
        return response()->json($invocies);
    }

    public function store_product_sub_category(Request $request)
    {
        // dd($request->all());
        $brand = new SubCategories();
        $brand->subcategory_name = $request->data_name[0];
        $brand->visibility = $request->data_name[1];
        $brand->save();

        $sub_categories_list = SubCategories::orderBy('created_at', 'desc')->where('visibility', 1)->pluck('subcategory_name', 'subcategory_id');
        // $brands->put('add_brand','Add Brand +');
        $sub_category_options = "";
        foreach ($sub_categories_list as $subcategory_id => $subcategory_name) {
            $sub_category_options .= '<option value="' . $subcategory_id . '">' . $subcategory_name . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Sub Category Added!', 'data' => $sub_category_options];
    }

    public function store_hsn(Request $request)
    {
        // dd($request->all());
        $brand = new HSNCodes();
        $brand->hsncode_name = $request->data_name[0];
        $brand->hsncode_desc = $request->data_name[1];
        $brand->save();

        $hsncodes = HSNCodes::orderBy('created_at', 'desc')->pluck('hsncode_desc', 'hsncode_name');
        // $hsncodes->put('add_hsncode','Add HSN Code +');
        $hsncode_options = "";
        foreach ($hsncodes as $hsncode_id => $hsncode_name) {
            $hsncode_options .= '<option value="' . $hsncode_id . '">' . $hsncode_name . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Gl Code Added!', 'data' => $hsncode_options];
    }

    public function store_gl(Request $request)
    {
        // dd($request->all());
        $brand = new GLCodes();
        $brand->gl_code = $request->data_name[0];
        $brand->save();

        $hsncodes = GLCodes::pluck('gl_code', 'gl_code');
        // $hsncodes->put('add_hsncode','Add HSN Code +');
        $hsncode_options = "";
        foreach ($hsncodes as $hsncode_id => $hsncode_name) {
            $hsncode_options .= '<option value="' . $hsncode_id . '">' . $hsncode_name . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New GL Code Added!', 'data' => $hsncode_options];
    }

    public function get_expense(Request $request)
    {
        $expense_id = $request->id;

        $data = Expensemaster::where('expense_id', $expense_id)
            ->with('get_type', 'get_sub_cat', 'get_cat', 'get_heads', 'get_gl')
            ->first();

        return response()->json($data);
    }

    public function get_doc_numbers(Request $request)
    {
        $party_id = $request->party_id;
        $series_no = $request->series_no;
        $bp_master = BussinessPartnerMaster::where('business_partner_master', $party_id)->first();
        $series = SeriesMaster::where('company_id', $bp_master->company_id)->first();
        // dd($party_id,$series_no);

        $data = BillBooking::where(['vendor_id' => $party_id, 'series_no' => $series->series_number])->pluck('doc_no', 'doc_no');

        // $data = SeriesMaster::WhereIn('id',$series_ids)->get();

        // dd($data->toArray());

        return response()->json($data);
    }


    public function get_companies(Request $request)
    {
        $company_id = $request->company_id;
        $bp_master = BussinessPartnerMaster::where('company_id', $company_id)->pluck('bp_name', 'business_partner_id');

        return response()->json($bp_master);
    }

    public function get_company(Request $request)
    {
        $party_id = $request->party_id;
        $bp_master = BussinessPartnerMaster::with('get_category', 'get_group')->where('business_partner_id', $party_id)->first();
        $company = Company::where('company_id', $bp_master->company_id)->first();

        $result['bp_master'] = $bp_master;
        $result['company'] = $company;

        return response()->json($result);
    }

    public function get_company_details(Request $request)
    {
        $company_id = $request->company_id;
        $company = Company::where('company_id', $company_id)->first();
        return response()->json($company);
    }



    public function get_series(Request $request)
    {
        $party_id = $request->party_id;


        $data = BillBooking::where(['vendor_id' => $party_id])->pluck('series_no');


        $data = SeriesMaster::WhereIn('id', $data)->pluck('id', 'series_number');

        // dd($data);
        // dd($data->toArray());

        return response()->json($data);
    }

    public function getStates(Request $request)
    {
        $id = $request->input('id');
        $sales_officer = State::where(['country_id' => $id])->pluck('name', 'id');

        return response()->json($sales_officer);
    }

    public function getCities(Request $request)
    {
        $id = $request->input('id');
        $sales_officer = City::where(['state_id' => $id])->pluck('city_name', 'city_id');

        return response()->json($sales_officer);
    }



    public function getAsm(Request $request)
    {
        $role = $request->input('id');
        $sales_managers = AdminUsers::where(['account_status' => 1, 'role' => $role])->pluck('first_name', 'admin_user_id');
        return response()->json($sales_managers);
    }


    public function getAse(Request $request)
    {
        $salesManagerId = $request->input('id');
        $sales_officer = AdminUsers::where(['account_status' => 1, 'parent_users' => $salesManagerId])->pluck('first_name', 'admin_user_id');

        return response()->json($sales_officer);
    }

    public function getGroups(Request $request)
    {
        $id = $request->input('id');
        $groups_data = Bpgroup::where(['bp_channel_id' => $id])->pluck('name', 'id');

        return response()->json($groups_data);
    }
    public function getMargins(Request $request)
    {
        $id = $request->input('id');
        $groups_data = Pricings::where(['pricing_type' => 'margin', 'bp_channel' => $id])->pluck('pricing_name', 'pricing_master_id');

        return response()->json($groups_data);
    }
    public function getScheme(Request $request)
    {
        $id = $request->input('id');
        $groups_data = Pricings::where(['pricing_type' => 'scheme', 'bp_channel' => $id])->pluck('pricing_name', 'pricing_master_id');

        return response()->json($groups_data);
    }

    public function send_email()
    {
        $email = $_GET['email'];
        $subject = 'User Credentials';
        $body = 'Username: ' . $email . "<br>" . "Password: " . '123456';
        send_email($email, $subject, $body);
        return true;
    }


    public function getChannels(Request $request)
    {
        $id = $request->input('id');
        $channel_data = BusinessPartnerCategory::pluck('business_partner_category_name', 'business_partner_category_id');

        return response()->json($channel_data);
    }


    public function getSalesOfficers(Request $request)
    {
        $aseId = $request->input('id');
        $sales_officer = AdminUsers::where(['account_status' => 1, 'parent_users' => $aseId])->pluck('first_name', 'admin_user_id');

        return response()->json($sales_officer);
    }

    public function getSalesmen(Request $request)
    {
        $salesOfficerId = $request->input('id');
        $salesmen = AdminUsers::where(['account_status' => 1, 'role' => 37])->pluck('first_name', 'admin_user_id'); //,'parent_users' => $salesOfficerId

        return response()->json($salesmen);
    }


    public function getPricingPurchase(Request $request)
    {
        $company_id = $request->input('id');
        $pricing = Pricings::where(['company_id' => $company_id, 'pricing_type' => 'purchase', 'status' => 1])->pluck('pricing_name', 'pricing_master_id');
        return response()->json($pricing);
    }

    public function getPricingSale(Request $request)
    {
        $company_id = $request->input('id');
        $pricing = Pricings::where(['company_id' => $company_id, 'pricing_type' => 'sale', 'status' => 1])->pluck('pricing_name', 'pricing_master_id');
        return response()->json($pricing);
    }

    public function get_format(Request $request)
    {
        $brand_id = $request->input('id');
        $products = Products::where(['brand_id' => $brand_id])->pluck('sub_category_id', 'sub_category_id');
        $formats = SubCategories::whereIn('subcategory_id', $products)->pluck('subcategory_name', 'subcategory_id');
        return response()->json($formats);
    }

    public function get_product(Request $request)
    {
        $format_id = $request->input('id');
        $products = Products::where(['sub_category_id' => $format_id])->pluck('consumer_desc', 'product_item_id');
        return response()->json($products);
    }


    public function get_bill_bookings(Request $request)
    {
        $vendor_id = $request->doc_number;

        // dd($vendor_id);
        $data = BillBooking::where(['vendor_id' => $vendor_id, 'is_bb_updated' => 1, 'status' => 'open', 'fy_year' => session('fy_year'), 'company_id' => session('company_id')])
            ->with(
                'billbooking_items.get_expense_name',
                'billbooking_items.get_type',
                'billbooking_items.get_sub_cat',
                'billbooking_items.get_cat',
                'billbooking_items.get_heads',
                'billbooking_items.get_gl'
            )->get();

        // dd($data->toArray());
        return response()->json($data);
    }

    function getModelClass($index)
    {
        switch ($index) {
            case 1:
                return PurchaseOrder::class;
            case 2:
                return GoodsServiceReceipts::class;
            case 3:
                return Apinvoice::class;
                // Add more cases as needed
            default:
                return null;
        }
    }

    public function get_doc_number(Request $request)
    {

        $series_number = $request->id;
        $party_id = $request->party_id;
        $exploded = explode('-', $series_number);
        $module = $exploded[0];



        $data = SeriesMaster::where('series_number', $series_number)->first();
        $modules = DB::table('modules')->pluck('name', 'id')->toArray();
        $moduleName = DB::table('modules')->where('id', $data->module)->value('name');

        if (!empty($party_id)) {
            $bp = BussinessPartnerMaster::where('business_partner_id', $party_id)->first();
            $Financialyear = get_fy_year($bp->company_id);
            $fy_year = Financialyear::where(['year' => $Financialyear])->first();
        } else {
            $fy_year = Financialyear::where(['year' => session('fy_year')])->first();
        }

        if ($moduleName == $modules[1]) {
            $doc_number = $series_number . '-' .  $fy_year->year . '-' . $fy_year->purchase_order_counter;
        } else if ($moduleName == $modules[2]) {
            $doc_number = $series_number . '-' .  $fy_year->year . '-' .  $fy_year->goods_servie_receipt_counter;
        } else if ($moduleName == $modules[3]) {
            $doc_number = $series_number . '-' .  $fy_year->year . '-' .  $fy_year->ap_invoice_counter;
        } else if ($moduleName == $modules[9]) {
            $doc_number = $series_number . '-' .  $fy_year->year . '-' .  $fy_year->order_booking_counter;
        } else if ($moduleName == $modules[13]) {
            $doc_number = $series_number . '-' .  $fy_year->year . '-' .  $fy_year->invoice_return_counter;
        } else if ($moduleName == $modules[14]) {
            $doc_number = $series_number . '-' .  $fy_year->year . '-' .  $fy_year->claim_counter;
        }


        return $doc_number;
    }


    public function getHsnCodes(Request $request)
    {
        $query = $request->get('query');
        $hsnCodes = Products::where('hsncode_id', 'like', $query . '%')->pluck('hsncode_id');

        return response()->json($hsnCodes);
    }

    public function getEanBarCodes(Request $request)
    {
        $query = $request->get('query');
        $eanbarcodes = Products::where('ean_barcode', 'like', $query . '%')->pluck('ean_barcode');

        return response()->json($eanbarcodes);
    }


    public function getGst(Request $request)
    {
        $query = $request->get('id');
        // $uom = $request->get('uom')??'';
        // if(!empty($uom)){
        //     $
        // }
        $gst = Gst::where('gst_id', $query)->first();

        return response()->json($gst);
    }

    public function autocomplete()
    {

        $query = $_GET['query'] ?? '';
        $type = $_GET['type'];
        if ($type == 'code') {
            $data = Products::select(DB::raw("item_code as name"), 'product_item_id', 'unit_case', 'dimensions_unit_pack', 'hsncode_id', 'sku', 'mrp', 'gst_id', 'consumer_desc')
                ->where('visibility', 1)
                ->where("item_code", "LIKE", "%" . $query . "%")
                ->orWhere("hsncode_id", "LIKE", "%" . $query . "%")
                ->get();
        } else if (preg_match('/^\d+-\d+$/', $query)) {
            // Check if the query matches the pattern "digits-hyphen-digits"
            $data = Products::select(DB::raw("sku as name"), 'product_item_id', 'unit_case', 'dimensions_unit_pack', 'hsncode_id', 'sku', 'mrp', 'gst_id', 'item_code')
                ->where('visibility', 1)
                ->where("sku", "LIKE", "%" . $query . "%")
                ->get();
        } else {
            $data = Products::select(DB::raw("consumer_desc as name"), 'product_item_id', 'hsncode_id', 'sku', 'mrp', 'gst_id', 'item_code')
                ->where('visibility', 1)
                ->where("consumer_desc", "LIKE", "%" . $query . "%")
                ->get();
        }

        DB::table('def_bacth_no_counter')->increment('counter');
        $counterValue = DB::table('def_bacth_no_counter')->value('counter');

        return response()->json(['data' => $data, 'counter' => $counterValue]);
    }

    public function get_customer(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $item_code = $request->input('item_code');
        $sku = $request->input('sku');
        $inv_type = $request->input('inv_type', '');

        $customer = BussinessPartnerMaster::find($customer_id);

        if ($customer->business_partner_type == 3) {
            $pricing = Pricings::where(['pricing_type' => 'purchase'])->first();


            $pricing_data = PricingItem::where('pricing_master_id', $pricing->pricing_master_id)
                ->where('item_code', $item_code)
                ->first();
            return $pricing_data->selling_price;
        } else {

            if (!$customer) {
                // If customer not found, return product's MRP
                $product = Products::where(['sku' => $sku, 'item_code' => $item_code])->first();
                return $product ? $product->mrp : -1;
            }

            $pricing = Pricings::where('bp_channel', $customer->bp_channel)
                ->where('bp_group', $customer->bp_group)
                ->whereIn('pricing_type', ['margin', 'scheme'])
                ->pluck('pricing_master_id', 'pricing_type');

            if (!$pricing->has('margin') || !$pricing->has('scheme')) {
                // If margin or scheme pricing not found, return -1
                return -1;
            }

            $pricing_ladder = Pricings::where('bp_channel', $customer->bp_channel)
                ->where('pricing_type', 'ladder')
                ->where('margin', $pricing['margin'])
                ->where('scheme', $pricing['scheme'])
                ->value('pricing_master_id');

            if (!$pricing_ladder) {
                // If pricing ladder not found, return -1
                return -1;
            }

            $pricing_data = Pricingladder::where('pricing_master_id', $pricing_ladder)
                ->where('item_code', $item_code)
                ->latest()
                ->first();

            if (!$pricing_data) {
                // If pricing data not found, return -1
                return -1;
            }

            // Determine pricing based on channel, category, and inv_type
            if ($customer->bp_channel == 5 && $customer->bp_category == 1) {
                return $pricing_data->sub_d_landing;
            } elseif ($customer->bp_channel == 5 && $customer->bp_category == 2) {
                return $pricing_data->ptr_af_sch;
            } elseif ($customer->bp_channel != 5 && $customer->bp_category == 1) {
                return $pricing_data->sub_d_landing;
            } elseif ($customer->bp_channel != 5 && $customer->bp_category == 2) {
                if (!empty($inv_type)) {
                    return $inv_type == 'on' ? $pricing_data->ptr_af_sch : $pricing_data->ptr;
                }
            }
        }


        return -1; // Default return value if conditions are not met
    }




    public function change_fy()
    {
        $selectedYear = $_GET['year'];
        // session(['fy_year' => $selectedYear]);
        session()->put('fy_year', $selectedYear);

        // Update or create the financial year
        $financial_year = Financialyear::firstOrNew(['year' => $selectedYear]);
        $financial_year->active = 1;
        $financial_year->save();

        // Deactivate other financial years
        Financialyear::where('year', '!=', $selectedYear)->update(['active' => 0]);


        return response()->json(['success' => true]);
    }

    public function store_route(Request $request)
    {

        // dd($request->all());
        $model = new Route();
        $model->area_id = $request->data_name[0];
        $model->route_name = $request->data_name[1];
        $model->save();
        // $model->fill($request->all());

        $data = Route::where('area_id', $request->data_name[0])->pluck('route_name', 'route_id');
        // $brands->put('add_brand','Add Brand +');
        $data_options = "";
        foreach ($data as $key => $val) {
            $data_options .= '<option value="' . $key . '">' . $val . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Route Added!', 'data' => $data_options];
    }

    public function store_beat(Request $request)
    {

        // dd($request->all());
        $model = new Beat();
        $model->beat_name = $request->data_name[0];
        $model->area_id = $request->data_name[1];
        $model->route_id = $request->data_name[2];
        $model->save();
        // $model->fill($request->all());

        $data = Beat::orderBy('created_at', 'desc')->pluck('beat_name', 'beat_id');
        // $brands->put('add_brand','Add Brand +');
        $data_options = "";
        foreach ($data as $key => $val) {
            $data_options .= '<option value="' . $key . '">' . $val . '</option>';
        }
        return ['flag' => 'success', 'message' => 'New Beat Added!', 'data' => $data_options];
    }
}
