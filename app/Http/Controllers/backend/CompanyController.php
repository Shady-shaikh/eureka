<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\AdminUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\backend\State;
use App\Models\backend\Company;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
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
    $controllerName = class_basename(request()->route()->getAction('controller'));
    session(['previous_controller' => $controllerName]);

    $company = Company::orderby('company_id', 'desc')->get();
    return view('backend.company.index', compact('company'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $roles = Role::pluck('name', 'id')->all();
    $states = State::all();
    $states = collect($states)->mapWithKeys(function ($item, $key) {
      return [$item['name'] => $item['name']];
    });
    return view('backend.company.create', compact('roles', 'states'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'email|required',
      'mobile_no' => 'digits:10|required',
      'address_line1' => 'required',
      'address_line2' => 'required',
      'landmark' => 'required',
      'city' => 'required',
      'country' => 'required',
      'state' => 'required',
      'district' => 'required',
      'pincode' => 'digits:6|required',
      'gstno' => 'required',
      'db_type' => 'required',
      'ay_type' => 'required',


    ]);


    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }
    $company = new Company();
    if (!empty($request->parent_users)) {
      $parent_users = implode(',', $request->parent_users);
    }
    $company->parent_users = $parent_users;
    if ($company->fill($request->all())->save()) {

      if (!empty($request->company_logo)) {
        $imageName = time() . '.' . $request->company_logo->extension();
        if (!file_exists(public_path('backend-assets/images'))) {
          mkdir(public_path('backend-assets/images'), 0777);
        }
        $request->company_logo->move(public_path('backend-assets/images'), $imageName);
        $company->company_logo = $imageName;
      }

      $model = Company::where('company_id', $company->company_id)->first();

      $log = ['module' => 'Distributor Master', 'action' => 'Distributor Created', 'description' => 'Distributor Created: Name=>' . $company->name];
      captureActivity($log);

      $distributor_check = AdminUsers::where('email', $model->email)->first();
      //to create distributor in admin users if not exists 27-02-2024
      if (!$distributor_check) {
        // fetch master user
        if (!empty($request->parent_users)) {
          $parent_users = implode(',', $request->parent_users);
        }

        $create_distributor = new AdminUsers();
        $create_distributor->first_name = $model->name;
        $create_distributor->email = $model->email;
        $create_distributor->mobile_no = $model->mobile_no;
        $create_distributor->role = 41; //distributor role id
        $create_distributor->company_id = $model->company_id;
        $create_distributor->account_status = 1;
        $create_distributor->parent_users = $parent_users ?? '';
        $create_distributor->zone_id = $request->zone_id ?? '';
        $create_distributor->password = 123456;

        $create_distributor->save();

        // $subject = 'User Credentials';
        // $body = 'Username: '.$model->email."<br>"."Password: ".'123456';
        // send_email($model->email,$subject,$body);

        $create_distributor->assignRole(41); //distributor role id//28-02-2024
      }
      Session::flash('message', 'Distributor Added Successfully!');
      Session::flash('status', 'success');
    } else {
      Session::flash('message', 'Something went wrong!');
      Session::flash('status', 'error');
    }



    return redirect('admin/company');
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
    $company = Company::findOrFail($id);
    $roles = Role::pluck('name', 'id')->all();
    $states = State::all();
    $states = collect($states)->mapWithKeys(function ($item, $key) {
      return [$item['name'] => $item['name']];
    });
    return view('backend.company.edit', compact('company', 'roles', 'states'));
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
    $id = $request->input('company_id');
    // $this->validate($request, []);
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'email|required',
      'mobile_no' => 'digits:10|required',
      'address_line1' => 'required',
      'address_line2' => 'required',
      'landmark' => 'required',
      'city' => 'required',
      'country' => 'required',
      'state' => 'required',
      'district' => 'required',
      'pincode' => 'digits:6|required',
      'gstno' => 'required',
      'db_type' => 'required',
      'ay_type' => 'required',


    ]);


    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $company = Company::findOrFail($id);
    if (!empty($request->parent_users)) {
      $parent_users = implode(',', $request->parent_users);
    }
    $company->parent_users = $parent_users;

    // dd($request->all());
    if (!empty($request->company_logo)) {
      $imageName = time() . '.' . $request->company_logo->extension();
      if (!file_exists(public_path('backend-assets/images'))) {
        mkdir(public_path('backend-assets/images'), 0777);
      }
      $request->company_logo->move(public_path('backend-assets/images'), $imageName);
      $company->company_logo = $imageName;
    }

    if ($company->update($request->all())) {

      if ($company->getChanges()) {
        $new_changes = $company->getChanges();
        $log = ['module' => 'Distributor Master', 'action' => 'Distributor Updated', 'description' => 'Distributor Updated: Name=>' . $company->name];
        captureActivityupdate($new_changes, $log);
      }

      $logo = Company::upload_logo($request);
      $signature = Company::upload_signature($request);
      if ($logo == '') {
        $logo = $company->logo;
      }
      if ($signature == '') {
        $signature = $company->signature;
      }
      Company::where('company_id', $company->company_id)->update(['logo' => $logo, 'signature' => $signature]);
      //create distributor user profile if not exits//28-02-2024
      $distributor_check = AdminUsers::where('email', $company->email)->first();
      // dd($company);

      //to create distributor in admin users if not exists 27-02-2024
      if (!$distributor_check) {
        // fetch master user
        if (!empty($request->parent_users)) {
          $parent_users = implode(',', $request->parent_users);
        }

        $create_distributor = new AdminUsers();
        $create_distributor->first_name = $company->name;
        $create_distributor->email = $company->email;
        $create_distributor->mobile_no = $company->mobile_no;
        $create_distributor->role = 41; //distributor role id
        $create_distributor->company_id = $company->company_id;
        $create_distributor->account_status = 1;
        $create_distributor->parent_users = $parent_users ?? '';
        $create_distributor->zone_id = $request->zone_id ?? '';
        $create_distributor->password = 123456;

        $create_distributor->save();
        $create_distributor->assignRole(41); //distributor role id//28-02-2024
      }
      Session::flash('success', 'Distributor Updated Successfully!');
      Session::flash('status', 'success');
    } else {
      Session::flash('error', 'Something went wrong!');
      Session::flash('status', 'error');
    }


    return redirect('admin/company');
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
    $company = Company::findOrFail($id);

    if ($company->delete()) {

      $log = ['module' => 'Distributor Master', 'action' => 'Distributor Deleted', 'description' => 'Distributor Deleted: Name=>' . $company->name];
      captureActivity($log);
      Session::flash('message', 'Distributor Deleted Successfully!');
      Session::flash('status', 'success');
    } else {
      Session::flash('message', 'Something went wrong!');
      Session::flash('status', 'error');
    }

    return redirect('admin/company');
  }
}
