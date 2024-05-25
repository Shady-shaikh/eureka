<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\AdminUsers;
use App\Models\backend\Users;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AdminusersController extends Controller
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
    $adminusers = AdminUsers::with('userrole')->get();
    // dd($adminusers);
    return view('backend.adminusers.index', compact('adminusers'));
  }

  public function getemp()
  {
    $users = Users::all();
    return response()->json($users);
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $roles = Role::pluck('name', 'id')->all();
    return view('backend.adminusers.create', compact('roles'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'first_name' => ['required'],
      'last_name' => ['required'],
      'email' => ['required', 'email', 'unique:admin_users,email'],
      'password' => ['required', 'min:6', 'confirmed'],
    ]);
    $adminuser = new AdminUsers();

    if ($adminuser->fill($request->all())->save()) {
      // $cat = AdminUsers::Where('category_id',$category->category_id)->first();
      // $cat->category_slug = str_slug($category->category_name );
      // $cat->save();
    }

    Session::flash('message', 'Menu added!');
    Session::flash('status', 'success');

    return redirect('admin/adminusers');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   *
   * @return Response
   */
  public function show($id)
  {
    $adminuser = AdminUsers::findOrFail($id);

    return view('backend.adminusers.show', compact('adminuser'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   *
   * @return Response
   */
  public function edit($id)
  {
    $adminuser = AdminUsers::findOrFail($id);
    $roles = Role::pluck('name', 'id')->all();
    return view('backend.adminusers.edit', compact('adminuser', 'roles'));
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
    // echo "<pre>";print_r($request->all());exit;
    $id = $request->input('admin_user_id');
    $this->validate($request, [
      'first_name' => ['required'],
      'last_name' => ['required'],
      'email' => ['required', 'email', Rule::unique(AdminUsers::class, 'email')->ignore($id, 'admin_user_id')],
      // 'password' => ['required', 'min:6'],
      'last_name' => ['required'],
    ]);
    $adminuser = AdminUsers::findOrFail($id);
    if ($adminuser->update($request->all())) {
      $adminuser->assignRole($request->input('role'));
      // $cat = AdminUsers::Where('category_id',$adminuser->category_id)->first();
      // $cat->category_slug = str_slug($adminuser->category_name );
      // $cat->save();
    }
    Session::flash('message', 'Menu updated!');
    Session::flash('status', 'success');

    return redirect('admin/adminusers');
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
    $adminuser = AdminUsers::findOrFail($id);

    $adminuser->delete();

    Session::flash('message', 'Menu deleted!');
    Session::flash('status', 'success');

    return redirect('admin/adminusers');
  }

  public function editstatus($id)
  {
    $adminuser = AdminUsers::findOrFail($id);
    return view('backend.adminuser.edit', compact('adminuser'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   *
   * @return Response 
   */
  public function updatestatus(Request $request)
  {
    //  echo "<pre>";print_r($request->all());exit;
    $id = $request->input('admin_user_id');
    $this->validate($request, [
      'admin_user_id' => ['required'],
      'account_status' => ['required'],
    ]);
    $adminuser = AdminUsers::findOrFail($id);
    // if($adminuser->update($request->all()))
    if ($adminuser->fill($request->all())->save()) {


      return redirect('admin/adminusers')->with('success', 'admin  User updated!');
    } else {
      return back()->with('error', 'Something Went Wrong!');
    }
  }
}
