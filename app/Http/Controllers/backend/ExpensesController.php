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
use App\Models\backend\Expenses;
use App\Models\backend\BussinessMasterType;
use App\Models\backend\BussinessPartnerOrganizationType;
use App\Models\backend\TermPayment;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerContactDetails;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\ExpenseCategories;
use App\Models\backend\ItemTypes;
use App\Models\backend\Brands;
use App\Models\backend\HSNCodes;
use App\Models\backend\ExpenseSubCategories;
use App\Models\backend\UoMs;
use App\Models\backend\ExpenseTypes;

class ExpensesController extends Controller
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
         $expenses = Expenses::all();
        //$expenses = [];

        return view('backend.expenses.index', compact('expenses'));
    }

    // //create new user
    public function create()
    {
        $expense_categories = ExpenseCategories::where('visibility',1)->pluck('expense_category_name','expense_category_id');
        $expense_sub_categories = [];
        $hsncodes = HSNCodes::pluck('hsncode_name','hsncode_id');
        // $expense_sub_categories = [];
        $expense_types = ExpenseTypes::pluck('expense_type_name','expense_type_id');
        $brands = Brands::pluck('brand_name','brand_id');
        $uoms = UoMs::pluck('uom_name','uom_id');
        // dd($categories);
        return view('backend.expenses.create', compact('expense_categories','expense_sub_categories','hsncodes','expense_types','brands','uoms'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            // 'item_type_id' => 'required',
            // 'item_code' => 'required',
            // 'brand_id' => 'required',
            // 'category_id' => 'required',
            // 'sub_category_id' => 'required',
        ]);
        $data = $request->all();
        $expenses = new Expenses;
        $expenses->fill($request->all());
        if ($expenses->save()) {
            return redirect('/admin/expenses')->with('success', 'Expense Added');
        }
    }


    public function delete($id)
    {
        $data = Expenses::where('product_item_id', $id)->get();
        if (count($data) > 0) {
            if (Expenses::where('product_item_id', $id)->delete()) {
                return redirect('/admin/expenses')->with('success', 'Partner Has Been Deleted');
            }
        }
    }

    //edit expenses
    public function edit($id)
    {
        $expenses = Expenses::where('product_item_id', $id)->first();
        $categories = Categories::where('visibility',1)->pluck('category_name','category_id');
        $sub_categories = SubCategories::where('visibility',1)->pluck('subcategory_name','subcategory_id');
        $hsncodes = HSNCodes::pluck('hsncode_name','hsncode_id');
        $item_types = ItemTypes::pluck('item_type_name','item_type_id');
        $brands = Brands::pluck('brand_name','brand_id');
        $uoms = UoMs::pluck('uom_name','uom_id');
        return view('backend.expenses.edit', compact('expenses','categories','sub_categories','hsncodes','item_types','brands','uoms'));
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

        $bussiness = Expenses::where('product_item_id', $request->product_item_id)->first();
        $bussiness->fill($request->all());
        if ($bussiness->save()) {
            return redirect('/admin/expenses')->with('success', 'Partner Has Been Updated');
        }
    }

    public function destroy($id)
    {
        $expenses = Expenses::findOrFail($id);
        $expenses->delete();

        Session::flash('message', 'Expense deleted!');
        Session::flash('status', 'success');

        return redirect('admin/expenses');
    }

    public function address($id)
    {
        $address = BussinessPartnerAddress::where('bussiness_partner_id', $id)->get();
        return view('backend.expenses.address', compact('address', 'id'));
    }

    //show new address form
    public function addaddress($id)
    {
        return view('backend.expenses.add_address', compact('id'));
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
                return redirect()->route('admin.expenses.address', ['id' => $address->bussiness_partner_id])->with('success', 'Address Has Been updated');
                // return redirect('admin/expenses/address/'.$id)->with('success', 'New Address Has Been Added');
            } else {
                return redirect()->route('admin.expenses.address', ['id' => $address->bussiness_partner_id])->with('error', 'Failed to Rupdate Address');
            }
        } else {
            //Insert Code
            $address = new BussinessPartnerAddress;
            $address->fill($request->all());
            $id = $request->bussiness_partner_id;
            if ($address->save()) {
                return redirect()->route('admin.expenses.address', ['id' => $id])->with('success', 'New Address Has Been Added');
                // return redirect('admin/expenses/address/'.$id)->with('success', 'New Address Has Been Added');
            } else {
                return redirect()->route('admin.expenses.address', ['id' => $id])->with('error', 'Failed to Register Address');
            }
        }
    }

    public function editaddress($addressid)
    {
        $address = BussinessPartnerAddress::where('bp_address_id', $addressid)->first();
        return view('backend.expenses.updateaddress', compact('address'));
    }

    //function for delete address
    public function deleteaddress($id){
        $address = BussinessPartnerAddress::where('bp_address_id', $id)->first();

        if(count($address->toArray()) > 0){
            $addresddata = BussinessPartnerAddress::where('bp_address_id', $id)->delete();
            return redirect()->route('admin.expenses.address', ['id' => $address->bussiness_partner_id])->with('error', 'Failed to Register Address');
        }
    }

    public function contactexpenses($id)
    {
        $contact = BussinessPartnerContactDetails::where('bussiness_partner_id', $id)->get();
        return view('backend.expenses.contact', compact('contact','id'));
    }

    public function createcontact($id){
return view('backend.expenses.add_contact', compact('id'));
    }

    public function storecontact(Request $request)
    {

        $request->validate([
            'type' =>'required',
            'email_id' =>'required|email',
            'mobile_no' =>'required|digits:10|min:10',
        ]);


        if($request->has('contact_expenses_id') && $request->contact_expenses_id !=""){
            //update the data
            $contact = BussinessPartnerContactDetails::where('contact_expenses_id' , $request->contact_expenses_id)->first();
            $contact->fill($request->all());
            if($contact->save()){
                return redirect()->route('admin.expenses.contact', ['id' => $contact->bussiness_partner_id])->with('success', 'Contact Has Been Updates');
            }else{
                return redirect()->route('admin.expenses.contact', ['id' => $contact->bussiness_partner_id])->with('error', 'Failed to Update Contact');
            }

        }else{
            //inset the data
            $contact = new BussinessPartnerContactDetails;
        $contact->fill($request->all());
        if($contact->save() ){
            return redirect()->route('admin.expenses.contact', ['id' => $request->bussiness_partner_id])->with('success', 'Contact Has Been Added');
        }else{
            return redirect()->route('admin.expenses.contact', ['id' => $request->bussiness_partner_id])->with('error', 'Unable to add contact expenses');
        }
        }
    }


    public function editcontact($id){
    $contact = BussinessPartnerContactDetails::where('contact_expenses_id' , $id)->first();
    return view('backend.expenses.update_contact', compact('contact','id'));
    }

    //delete contact
    public function deletecontact($id){
        $contact = BussinessPartnerContactDetails::where('contact_expenses_id' , $id)->first();
        if(isset($contact) && count($contact->toArray()) > 0){
            $deleted = BussinessPartnerContactDetails::where('contact_expenses_id' , $id)->delete();
            if($deleted){
                return redirect()->route('admin.expenses.contact', ['id' => $contact->bussiness_partner_id])->with('success', 'Address Has Been Removed');
            }else{
                return redirect()->route('admin.expenses.contact', ['id' => $contact->bussiness_partner_id])->with('error', 'Unable to remove Address');
            }
        }

    }
//banking expenses
public function banking($id){
    $banking_data = BussinessPartnerBankingDetails::where('bussiness_partner_id', $id)->get();
    return view('backend.expenses.bank_detail', compact('banking_data','id'));
}

public function addbank($id)
{
return view('backend.expenses.add_bank', compact('id'));
}

public function bankstore(Request $request){

    $request->validate([
        'bank_name' => 'required',
        'bank_branch' => 'required',
        'ifsc' => 'required',
        'ac_number' => 'required|integer',
        'bank_address' => 'required',
            ]);

            if(isset($request->banking_expenses_id) && $request->banking_expenses_id !=""){
                $bankexpenses = BussinessPartnerBankingDetails::where('banking_expenses_id', $request->banking_expenses_id)->first();
                $bankexpenses ->fill($request->all());
                if($bankexpenses->save()){
                    return redirect()->route('admin.expenses.banking', ['id' => $bankexpenses->bussiness_partner_id])->with('success', 'bank Details Has Been Updated');
                }else{
                    return redirect()->route('admin.expenses.banking', ['id' => $bankexpenses->bussiness_partner_id])->with('error', 'Unable to Update bank expenses');
                }
            }else{
                //fresh request
                $bank = new BussinessPartnerBankingDetails;
                $bank->fill($request->all());

                if($bank->save()){
                    return redirect()->route('admin.expenses.banking', ['id' => $request->bussiness_partner_id])->with('success', 'bank Details Has Been Added');
                }else{
                    return redirect()->route('admin.expenses.banking', ['id' => $request->bussiness_partner_id])->with('error', 'Unable to Add bank expenses');
                }

            }
    }

    public function editbank($id){
        $bankexpenses = BussinessPartnerBankingDetails::where('banking_expenses_id',$id)->first();
        return view('backend.expenses.edit_bank', compact('id','bankexpenses'));
    }

    public function deletebank($id){
        $bank = BussinessPartnerBankingDetails::where('banking_expenses_id',$id)->first();
        if(isset($bank) &&  count($bank->toArray()) > 0){
            $delete = BussinessPartnerBankingDetails::where('banking_expenses_id',$id)->delete();
            if($delete){
                return redirect()->route('admin.expenses.banking', ['id' => $bank->bussiness_partner_id])->with('error', 'bank Details Has Been Added');
            }
        }
    }




    public function getexpensesubcategory(Request $request)
    {
      $data = $request->all();
      $subcategory = ExpenseSubCategories::where('expense_category_id',$data['expense_category_id'])->get();

      $subcategory_list = "<option value=''>Please Select</option>";

      foreach ($subcategory as $key => $value)
      {
        $subcategory_list .= "<option value='".$value['expense_category_id']."'>".$value['expense_subcategory_name']."</option>";
      }
      if (count($subcategory)==0)
      {
        $subcategory_list .= "<option value=''>No Record Found</option>";
      }
      return $subcategory_list;
    }
} //end of class
