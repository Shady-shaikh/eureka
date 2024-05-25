<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Company;

use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Session;


use Spatie\Permission\Models\Role;
use App\Models\backend\Zones;
use App\Models\backend\Financialyear;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // echo "string";exit;
        return view('backend.admin.dashboard');
    }

    public function showusers()
    {
        //  dd('welcoome');
        //to get only distributor users 
        $is_distributor = Auth()->guard('admin')->user()->role; //distributor
        if ($is_distributor == 41) {
            $adminusers = AdminUsers::where('company_id', Auth()->guard('admin')->user()->company_id)->orderBy('admin_user_id', 'DESC')->get(); //where('admin_user_id', '!=', Auth()->guard('admin')->user()->admin_user_id)->
        } else {
            $adminusers = AdminUsers::where('admin_user_id', '!=', Auth()->guard('admin')->user()->admin_user_id)->orderBy('admin_user_id', 'DESC')->get();
        }
        // dd($adminusers);
        return view('backend.admin.index', compact('adminusers'));
    }

    public function create()
    {
        $is_super_admin = AdminUsers::where('role', 17)->first();
        if (!empty($is_super_admin)) {
            $role = Role::where('id', '!=', Auth()->guard('admin')->user()->role)->where('department_id', '!=', 1)->get(['id', 'name'])->toArray();
        } else {
            $role = Role::where('id', '!=', Auth()->guard('admin')->user()->role)->get(['id', 'name'])->toArray();
        }
        // dd($role);
        $company = Company::pluck('name', 'company_id');
        $zones = Zones::pluck('zone_name', 'zone_id');

        return view('backend.admin.create', compact('role', 'company', 'zones'));
    }

    public function get_parent_roles()
    {
        $role_id = $_GET['role_id'];
        $admin_user_id = $_GET['admin_user_id'] ?? '';
        $master_id = $_GET['master_id'] ?? '';
        $role = Role::where('id', $role_id)->first();
        $html = '';



        if (!empty($role->parent_roles)) {
            $roles = explode(",", $role->parent_roles);
            foreach ($roles as $row) {
                $users = AdminUsers::where('role', $row)->get();
                $role_name = Role::where('id', $row)->first();
                $selectedUserIds = [];

                $html .= "<label> $role_name->name </label>";
                $html .= "<select name='parent_users[]' class='form-control'>";

                $html .= "<option value=''>Select $role_name->name</option>";
                foreach ($users as $user) {
                    if (!empty($admin_user_id)) {
                        $current_user = AdminUsers::where('admin_user_id', $admin_user_id)->first();
                        if (!empty($current_user->parent_users)) {
                            $selectedUserIds = explode(",", $current_user->parent_users);
                        }
                    }
                    if (!empty($master_id)) {
                        $selectedUserIds = explode(",", $master_id);
                    }
                    $selected = in_array($user->admin_user_id, $selectedUserIds) ? 'selected' : '';
                    $html .= "<option value='$user->admin_user_id' $selected>$user->first_name  $user->last_name</option>";
                }
                $html .= "</select> <br>";
            }
        }
        return $html;
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'company_id' => 'nullable|required_if:company_id,!=null',
        ]);
        if (!empty($request->parent_users)) {
            $parent_users = implode(',', $request->parent_users);
        }

        // dd($request->parent_users);

        $user = new AdminUsers;
        $user->fill($request->all());
        $user->parent_users = $parent_users ?? '';
        $user->save();

        $user->assignRole($request->input('role'));

        $log = ['module' => 'Internal User Management', 'action' => 'Internal User Created', 'description' => 'Internal User Created: ' . $request->first_name . ' ' . $request->last_name];
        captureActivity($log);

        return redirect('/admin/users')->with('success', 'New User Registered');
    }

    public function edit($id)
    {
        $userdata = AdminUsers::where('admin_user_id', $id)->first();
        $role = Role::get(['id', 'name']);
        $is_super_admin = AdminUsers::where('role', 17)->first();
        if (!empty($is_super_admin)) {
            $role = Role::where('id', '!=', Auth()->guard('admin')->user()->role)->where('department_id', '!=', 1)->get(['id', 'name']);
        } else {
            $role = Role::where('id', '!=', Auth()->guard('admin')->user()->role)->get(['id', 'name']);
        }

        $company = Company::pluck('name', 'company_id');
        $zones = Zones::pluck('zone_name', 'zone_id');

        return view('backend.admin.edit', compact('userdata', 'role', 'company', 'zones'));
    }
    public function update(Request $request)
    {
        $update_data = $request->all();
        //unset($update_data['_token']);
        //  dd($update_data);
        // $parent_users = implode(',', $request->parent_users);
        if (!empty($request->parent_users)) {
            $parent_users = implode(',', $request->parent_users);
        }


        $data = AdminUsers::where('admin_user_id', $request->admin_user_id)->get();
        // dd($data);
        if (count($data) > 0) {
            // $userdata = InternalUser::where('user_id', $request->id)->update($update_data);
            $userdata = AdminUsers::where('admin_user_id', $request->admin_user_id)->first();
            if (!empty($request->beat_id)) {
                $beats = implode(",", $request->beat_id);
            }

            $userdata->fill($request->all());
            $userdata->beat_id = $beats ?? '';
            $userdata->parent_users = $parent_users ?? '';
            if ($userdata->save()) {
                $userdata->assignRole($request->input('role'));


                if ($userdata->getChanges()) {
                    $new_changes = $userdata->getChanges();
                    $log = ['module' => 'Internal User Management', 'action' => 'Internal User Updated', 'description' => 'Internal User Updated: Name=>' . $userdata->first_name . ' ' . $userdata->last_name];
                    captureActivityupdate($new_changes, $log);
                }

                return redirect('/admin/users')->with('success', 'User Has Been Updated');
            }
        }
    }

    //delete user
    public function destroyUser($id)
    {
        $user = AdminUsers::where('admin_user_id', $id)->first();

        $log = ['module' => 'Internal User Management', 'action' => 'Internal User Deleted', 'description' => 'Internal User Deleted: ' . $user->first_name . ' ' . $user->last_name];
        captureActivity($log);

        if (AdminUsers::where('admin_user_id', $id)->delete()) {

            return redirect('/admin/users')->with('success', 'User Has Been Deleted');
        }
    }

    //update Status
    public function  updatestatus(Request $request)
    {
        $data = AdminUsers::where('admin_user_id', $request->admin_user_id)->get();
        if (count($data) > 0) {
            $userdata = AdminUsers::where('admin_user_id', $request->admin_user_id)->first();
            $userdata->fill($request->all());
            if ($userdata->save()) {
                return redirect('/admin/users')->with('success', 'User Status Been Updated');
            }
        }
    }

    public function myProfile()
    {
        $user_id = Auth()->guard('admin')->user()->admin_user_id;
        // dd($user_id);
        $adminuser = AdminUsers::where('admin_user_id', $user_id)->first();
        // dd($adminuser->toArray());
        return view('backend.admin.myprofile', compact('adminuser'));
    }


    public function updateProfile(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_no' => 'required|numeric|digits:10',
            'email' => 'required|email|unique:users,email',
        ]);

        $user_id = Auth()->guard('admin')->user()->admin_user_id;
        $userdata = AdminUsers::where('admin_user_id', $user_id)->first();
        $userdata->fill($request->all());
        if ($userdata->save()) {
            return redirect('/admin/profile')->with('success', 'User Status Been Updated');
        }
    }

    public function changePassword(Request $request)
    {
        // // if()
        $id = Auth()->guard('admin')->id();
        $userdata = AdminUsers::where('admin_user_id', $id)->first();
        return view('backend.admin.changepassword', compact('userdata'));
    }

    public function updatePassword(Request $request)
    {
        $id = Auth()->guard('admin')->id();
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|required_with:new_password_confirmation|same:new_password_confirmation|min:6|confirmed|regex:/^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ]);
        $data = AdminUsers::where('admin_user_id', $id)->first();
        // dd($data->toArray());
        if (count($data->toArray()) > 0) {
            if (FacadesHash::check($request->current_password, $data->password)) {
                // dd('Password matches');
                // dd($request->new_password);
                $data->password = $request->new_password;
                if ($data->save()) {
                    // dd('success');

                    // Activity Log
                    // $log = ['module' => 'Change Password', 'action' => 'Change Password', 'description' => 'Account Password Changed '];
                    // captureActivity($log);

                    return redirect()->back()->with('success', 'Password Has Been Updated');
                } else {
                    // dd('failure');
                    return redirect()->back()->with('error', 'Unable to change the password');
                }
            } else {
                // dd("Password doesn't match");
                return redirect()->route('admin.change_password')->with('error', "Password doesn't match");
            }
        }
    }

    public function changeYear(Request $request)
    {
        Session::put('fy_year', $request->year);
        Financialyear::where('active', 1)->update(['active' => 0]);
        Financialyear::where('year', $request->year)->update(['active' => 1]);
    }
}//end of class
