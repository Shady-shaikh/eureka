<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Auth()->guard('admin')->user()->role);
        // $roles = Role::where('id', '!=', Auth()->guard('admin')->user()->role)->where('department_id', '!=', 1)->get();
        $roles = Role::get();
        return view('backend.roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = DB::table('deapartment')->where('id', '!=', 1)->pluck('name', 'id')->toArray();
        return view('backend.roles.create', compact('departments'));
        // exit;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => ['required', 'unique:roles,name']
        ]);

        $menu_ids = ($request->input('menu_id')) ? implode(',', $request->input('menu_id')) : NULL;
        $submenu_ids = ($request->input('submenu_id')) ? implode(',', $request->input('submenu_id')) : NULL;
        if ($request->input('submenu_id')) {
            $is_sub = 1;
        } else {
            $is_sub = 0;
        }

        $already_exists = Role::where('department_id', $request->input('name'))->first();
        if (!empty($already_exists)) {
            return redirect()->back()->with('error', 'Role Already Added!');
        }

        $departments = DB::table('deapartment')->where('id', $request->input('name'))->pluck('name', 'id')->toArray();

        $role = Role::create([
            'name' =>  $departments[$request->input('name')], 'menu_ids' => $menu_ids, 'parent_roles' => $request->input('parent_roles') ?? null, 'submenu_ids' => $submenu_ids, 'is_sub' => $is_sub, 'readonly' => $request->readonly ?? null, 'readwrite' => $request->readwrite ?? null
        ]);
        $role->department_id = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permissions'));
        // $role->givePermissionTo($request->input('permissions', []));


        $log = ['module' => 'Roles Management', 'action' => 'Role Created', 'description' => 'Role Created: ' . $request->input('name')];
        captureActivity($log);

        return redirect()->route('admin.roles')->with('success', 'New Role Added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $has_permissions = $role->getAllPermissions();
        $has_permissions = collect($has_permissions)->mapWithKeys(function ($item, $key) {
            return [$item['id'] => $item['id']];
        })->toArray();
        $departments = DB::table('deapartment')->where('id', '!=', 1)->pluck('name', 'id')->toArray();

        return view('backend.roles.edit', compact('role', 'has_permissions', 'departments'));
        // return view('backend.roles.edit')->with('role', $role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            // 'name' => 'required'
        ]);
        $id = $request->input('id');
        // echo "string".$id;exit;
        // dd($request->all());
        // $parent_roles = '';
        // if (!empty($request->parent_roles)) {
        //     $parent_roles = implode(',', $request->parent_roles);
        // }

        $already_exists = Role::where('id', '!=', $id)->where('department_id', $request->input('name'))->first();
        if (!empty($already_exists)) {
            return redirect()->back()->with('error', 'Role Already Added!');
        }

        $departments = DB::table('deapartment')->where('id', $request->input('name'))->pluck('name', 'id')->toArray();


        $role = Role::findOrFail($id);
        $role->name = $departments[$request->input('name')]??'Super Admin';
        $role->department_id = $request->input('name')??1;
        $role->parent_roles = $request->input('parent_roles');
        $role->menu_ids = ($request->input('menu_id')) ? implode(',', $request->input('menu_id')) : NULL;
        $role->submenu_ids = ($request->input('submenu_id')) ? implode(',', $request->input('submenu_id')) : NULL;
        $role->readonly = $request->input('readonly') ?? null;
        $role->readwrite = $request->input('readwrite') ?? null;
        if ($request->input('submenu_id')) {
            $role->is_sub = 1;
        } else {
            $role->is_sub = 0;
        }
        $role->update();


        $role->syncPermissions($request->input('permissions'));
        // $role->givePermissionTo($request->input('permissions', []));

        // Reset Cache
        // Artisan::call('role:cache-reset');
        // app()->make(\Spatie\Role\RoleRegistrar::class)->forgetCachedRoles();

        if ($role->getChanges()) {
            $new_changes = $role->getChanges();
            $log = ['module' => 'Roles Management', 'action' => 'Role Updated', 'description' => 'Role Updated: Name=>' . $role->name];
            captureActivityupdate($new_changes, $log);
        }


        return redirect()->route('admin.roles')->with('success', 'Role Name Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        $log = ['module' => 'Roles Management', 'action' => 'Role Deleted', 'description' => 'Role Deleted: ' . $role->name];
        captureActivity($log);

        return redirect()->route('admin.roles')->with('success', 'Role Deleted!');
    }
}
