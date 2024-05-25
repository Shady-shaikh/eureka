<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\InternalUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;

use Spatie\Permission\Models\Role;
use App\Models\backend\Products;
use App\Models\backend\BussinessMasterType;
use App\Models\backend\BussinessPartnerOrganizationType;
use App\Models\backend\TermPayment;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerContactDetails;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\Categories;
use App\Models\backend\ItemTypes;
use App\Models\backend\Brands;
use App\Models\backend\HSNCodes;
use App\Models\backend\SubCategories;
use App\Models\backend\UoMs;

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
    public function index()
    {
        $products = Products::all();

        return view('backend.products.index', compact('products'));
    }

    // //create new user
    public function create()
    {
        $categories = Categories::where('visibility',1)->pluck('category_name','category_id');
        $sub_categories = [];
        $hsncodes = HSNCodes::pluck('hsncode_name','hsncode_id');
        $item_types = ItemTypes::pluck('item_type_name','item_type_id');
        $brands = Brands::pluck('brand_name','brand_id');
        $uoms = UoMs::pluck('uom_name','uom_id');
        // dd($categories);
        return view('backend.products.create', compact('categories','sub_categories','hsncodes','item_types','brands','uoms'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'item_type_id' => 'required',
            'item_code' => 'required',
            'brand_id' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);
        $data = $request->all();
        $products = new Products;
        $products->fill($request->all());
        if ($products->save()) {
            return redirect('/admin/products')->with('success', 'New Product Added');
        }
    }


    public function delete($id)
    {
        $data = Products::where('product_item_id', $id)->get();
        if (count($data) > 0) {
            if (Products::where('product_item_id', $id)->delete()) {
                return redirect('/admin/products')->with('success', 'Partner Has Been Deleted');
            }
        }
    }

    //edit products
    public function edit($id)
    {
        $products = Products::where('product_item_id', $id)->first();
        $categories = Categories::where('visibility',1)->pluck('category_name','category_id');
        $sub_categories = SubCategories::where('visibility',1)->pluck('subcategory_name','subcategory_id');
        $hsncodes = HSNCodes::pluck('hsncode_name','hsncode_id');
        $item_types = ItemTypes::pluck('item_type_name','item_type_id');
        $brands = Brands::pluck('brand_name','brand_id');
        // $brands->put('add_brand','Add Brand <i class="feather icon-plus"></i>');
        // $brands->add_brand = 'Add Brand';
        // dd($hsncodes);
        $uoms = UoMs::pluck('uom_name','uom_id');
        return view('backend.products.edit', compact('products','categories','sub_categories','hsncodes','item_types','brands','uoms'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_item_id' => 'required',
            'item_type_id' => 'required',
            'item_code' => 'required',
            'brand_id' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);

        $bussiness = Products::where('product_item_id', $request->product_item_id)->first();
        $bussiness->fill($request->all());
        if ($bussiness->save()) {
            return redirect('/admin/products')->with('success', 'Partner Has Been Updated');
        }
    }

    public function destroy($id)
    {
        $products = Products::findOrFail($id);
        $products->delete();

        Session::flash('message', 'Product deleted!');
        Session::flash('status', 'success');

        return redirect('admin/products');
    }

    public function address($id)
    {
        $address = BussinessPartnerAddress::where('bussiness_partner_id', $id)->get();
        return view('backend.products.address', compact('address', 'id'));
    }

    //show new address form
    public function addaddress($id)
    {
        return view('backend.products.add_address', compact('id'));
    }

    public function storeaddress(Request $request)
    {
        $request->validate([
            'address_type' => 'required',
            'building_no_name' => 'required',
            'street_name' => 'required',
            'landmark' => 'required',
            'city' => 'required',
            'pin_code' => 'required|digits:6|min:6|max:6',
            'district' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        //check bp_address id set or not
        if ($request->has('bp_address_id')) {
            //update code
            $address = BussinessPartnerAddress::where('bp_address_id', $request->bp_address_id)->first();
            $address->fill($request->all());
            if ($address->save()) {
                return redirect()->route('admin.products.address', ['id' => $address->bussiness_partner_id])->with('success', 'Address Has Been updated');
                // return redirect('admin/products/address/'.$id)->with('success', 'New Address Has Been Added');
            } else {
                return redirect()->route('admin.products.address', ['id' => $address->bussiness_partner_id])->with('error', 'Failed to Rupdate Address');
            }
        } else {
            //Insert Code
            $address = new BussinessPartnerAddress;
            $address->fill($request->all());
            $id = $request->bussiness_partner_id;
            if ($address->save()) {
                return redirect()->route('admin.products.address', ['id' => $id])->with('success', 'New Address Has Been Added');
                // return redirect('admin/products/address/'.$id)->with('success', 'New Address Has Been Added');
            } else {
                return redirect()->route('admin.products.address', ['id' => $id])->with('error', 'Failed to Register Address');
            }
        }
    }

    public function editaddress($addressid)
    {
        $address = BussinessPartnerAddress::where('bp_address_id', $addressid)->first();
        return view('backend.products.updateaddress', compact('address'));
    }

    //function for delete address
    public function deleteaddress($id){
        $address = BussinessPartnerAddress::where('bp_address_id', $id)->first();

        if(count($address->toArray()) > 0){
            $addresddata = BussinessPartnerAddress::where('bp_address_id', $id)->delete();
            return redirect()->route('admin.products.address', ['id' => $address->bussiness_partner_id])->with('error', 'Failed to Register Address');
        }
    }

    public function contactproducts($id)
    {
        $contact = BussinessPartnerContactDetails::where('bussiness_partner_id', $id)->get();
        return view('backend.products.contact', compact('contact','id'));
    }

    public function createcontact($id){
return view('backend.products.add_contact', compact('id'));
    }

    public function storecontact(Request $request)
    {

        $request->validate([
            'type' =>'required',
            'email_id' =>'required|email',
            'mobile_no' =>'required|digits:10|min:10',
        ]);


        if($request->has('contact_products_id') && $request->contact_products_id !=""){
            //update the data
            $contact = BussinessPartnerContactDetails::where('contact_products_id' , $request->contact_products_id)->first();
            $contact->fill($request->all());
            if($contact->save()){
                return redirect()->route('admin.products.contact', ['id' => $contact->bussiness_partner_id])->with('success', 'Contact Has Been Updates');
            }else{
                return redirect()->route('admin.products.contact', ['id' => $contact->bussiness_partner_id])->with('error', 'Failed to Update Contact');
            }

        }else{
            //inset the data
            $contact = new BussinessPartnerContactDetails;
        $contact->fill($request->all());
        if($contact->save() ){
            return redirect()->route('admin.products.contact', ['id' => $request->bussiness_partner_id])->with('success', 'Contact Has Been Added');
        }else{
            return redirect()->route('admin.products.contact', ['id' => $request->bussiness_partner_id])->with('error', 'Unable to add contact products');
        }
        }
    }


    public function editcontact($id){
    $contact = BussinessPartnerContactDetails::where('contact_products_id' , $id)->first();
    return view('backend.products.update_contact', compact('contact','id'));
    }

    //delete contact
    public function deletecontact($id){
        $contact = BussinessPartnerContactDetails::where('contact_products_id' , $id)->first();
        if(isset($contact) && count($contact->toArray()) > 0){
            $deleted = BussinessPartnerContactDetails::where('contact_products_id' , $id)->delete();
            if($deleted){
                return redirect()->route('admin.products.contact', ['id' => $contact->bussiness_partner_id])->with('success', 'Address Has Been Removed');
            }else{
                return redirect()->route('admin.products.contact', ['id' => $contact->bussiness_partner_id])->with('error', 'Unable to remove Address');
            }
        }

    }
//banking products
public function banking($id){
    $banking_data = BussinessPartnerBankingDetails::where('bussiness_partner_id', $id)->get();
    return view('backend.products.bank_detail', compact('banking_data','id'));
}

public function addbank($id)
{
return view('backend.products.add_bank', compact('id'));
}

public function bankstore(Request $request){

    $request->validate([
        'bank_name' => 'required',
        'bank_branch' => 'required',
        'ifsc' => 'required',
        'ac_number' => 'required|integer',
        'bank_address' => 'required',
            ]);

            if(isset($request->banking_products_id) && $request->banking_products_id !=""){
                $bankproducts = BussinessPartnerBankingDetails::where('banking_products_id', $request->banking_products_id)->first();
                $bankproducts ->fill($request->all());
                if($bankproducts->save()){
                    return redirect()->route('admin.products.banking', ['id' => $bankproducts->bussiness_partner_id])->with('success', 'bank Details Has Been Updated');
                }else{
                    return redirect()->route('admin.products.banking', ['id' => $bankproducts->bussiness_partner_id])->with('error', 'Unable to Update bank products');
                }
            }else{
                //fresh request
                $bank = new BussinessPartnerBankingDetails;
                $bank->fill($request->all());

                if($bank->save()){
                    return redirect()->route('admin.products.banking', ['id' => $request->bussiness_partner_id])->with('success', 'bank Details Has Been Added');
                }else{
                    return redirect()->route('admin.products.banking', ['id' => $request->bussiness_partner_id])->with('error', 'Unable to Add bank products');
                }

            }
    }

    public function editbank($id){
        $bankproducts = BussinessPartnerBankingDetails::where('banking_products_id',$id)->first();
        return view('backend.products.edit_bank', compact('id','bankproducts'));
    }

    public function deletebank($id){
        $bank = BussinessPartnerBankingDetails::where('banking_products_id',$id)->first();
        if(isset($bank) &&  count($bank->toArray()) > 0){
            $delete = BussinessPartnerBankingDetails::where('banking_products_id',$id)->delete();
            if($delete){
                return redirect()->route('admin.products.banking', ['id' => $bank->bussiness_partner_id])->with('error', 'bank Details Has Been Added');
            }
        }
    }




    public function getsubcategory(Request $request)
    {
      $data = $request->all();
      $subcategory = SubCategories::where('category_id',$data['category_id'])->get();

      $subcategory_list = "<option value=''>Please Select</option>";

      foreach ($subcategory as $key => $value)
      {
        $subcategory_list .= "<option value='".$value['subcategory_id']."'>".$value['subcategory_name']."</option>";
      }
      if (count($subcategory)==0)
      {
        $subcategory_list .= "<option value=''>No Record Found</option>";
      }
      return $subcategory_list;
    }
} //end of class
