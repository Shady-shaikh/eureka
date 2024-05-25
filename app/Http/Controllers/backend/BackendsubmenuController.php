<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\BackendMenubar;
use App\Models\backend\BackendSubMenubar;
use App\Models\backend\BasePermissions;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Spatie\Permission\Models\Permission;

class BackendsubmenuController extends Controller
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
        $backendsubmenus = BackendSubMenubar::all();
        return view('backend.backendsubmenu.index', compact('backendsubmenus'));
    }

    public function menu($menu_id)
    {
        $backendsubmenus = BackendSubMenubar::Where('menu_id',$menu_id)->get();
        return view('backend.backendsubmenu.index', compact('backendsubmenus','menu_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
      // dd($_GET);
      // $backendsubmenu = BackendSubMenubar::get();
      $backendmenu = BackendMenubar::get();
      $menu_list = collect($backendmenu)->mapWithKeys(function ($item, $key) {
          return [$item['menu_id'] => $item['menu_name']];
        });
        return view('backend.backendsubmenu.create', compact('menu_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'submenu_name' => ['required'],
        'submenu_controller_name' => ['required'],
        'submenu_permissions' => ['required'],
        'menu_id' => ['required'],
      ]);
      $backendsubmenu = new BackendSubMenubar();
      $backendsubmenu->fill($request->all());
      $backendsubmenu->submenu_permissions = implode(',',$request->submenu_permissions);
      if($backendsubmenu->save())
      {
        if ($backendsubmenu->submenu_permissions)
        {
          $base_permissions = BasePermissions::all();
          foreach ($base_permissions as $permission)
          {
            if (in_array($permission->base_permission_id,$request->submenu_permissions))
            {

              $permission_name = $permission->base_permission_name.' '.$backendsubmenu->submenu_name;
              $menu_permissions = Permission::where('name',$permission_name)->first(); //Match input //permission to db record
              if ($menu_permissions)
              {
                Permission::where('id',$menu_permissions->id)->update(['name' => $permission_name,'base_permission_id'=>$permission->base_permission_id,'base_permission_name'=>$permission->base_permission_name,'menu_id'=>$backendsubmenu->menu_id,'submenu_id'=>$backendsubmenu->submenu_id]);
                // DB::table('permissions')->where('id',$menu_permissions->id)->update(['name' => $permission_name,'base_permission_id'=>$permission->base_permission_id,'base_permission_name'=>$permission->base_permission_name,'menu_id'=>$backendsubmenu->menu_id,'submenu_id'=>$backendsubmenu->submenu_id]);
              }
              else
              {
                Permission::create(['name' => $permission_name,'base_permission_id'=>$permission->base_permission_id,'base_permission_name'=>$permission->base_permission_name,'menu_id'=>$backendsubmenu->menu_id,'submenu_id'=>$backendsubmenu->submenu_id]);
              }
              // dd($permission_name);
            }
            else
            {
              $menu_permissions = Permission::where('base_permission_id',$permission->base_permission_id)->where('menu_id',$backendsubmenu->menu_id)->where('submenu_id',$backendsubmenu->submenu_id)->first(); //Match input //permission to db record
              if ($menu_permissions)
              {
                $menu_permissions->delete();
              }
            }
          }
        }
      }

      Session::flash('message', 'Sub Menu added!');
      Session::flash('status', 'success');

      return redirect('admin/backendsubmenu');

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
        $backendsubmenu = BackendSubMenubar::findOrFail($id);

        return view('backend.backendsubmenu.show', compact('backendsubmenu'));
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
        $backendsubmenu = BackendSubMenubar::findOrFail($id);
        $backendmenu = BackendMenubar::get();
        $menu_list = collect($backendmenu)->mapWithKeys(function ($item, $key) {
            return [$item['menu_id'] => $item['menu_name']];
          });
        return view('backend.backendsubmenu.edit', compact('backendsubmenu','menu_list'));
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
      $this->validate($request, [
        'submenu_name' => ['required'],
        'submenu_controller_name' => ['required'],
        'submenu_permissions' => ['required'],
        'menu_id' => ['required'],
      ]);
      $id = $request->input('submenu_id');
      $backendsubmenu = BackendSubMenubar::findOrFail($id);
      $backendsubmenu->fill($request->all());
      $backendsubmenu->submenu_permissions = implode(',',$request->submenu_permissions);
      if ($request->submenu_permissions)
      {
        $base_submenu_permissions = BasePermissions::all();
        foreach ($base_submenu_permissions as $permission)
        {
          if (in_array($permission->base_permission_id,$request->submenu_permissions))
          {

            $permission_name = $permission->base_permission_name.' '.$request->input('submenu_name');
            $submenu_permissions = Permission::where('name',$permission_name)->first(); //Match input //permission to db record
            if ($submenu_permissions)
            {
              Permission::where('id',$submenu_permissions->id)->update(['name' => $permission_name,'base_permission_id'=>$permission->base_permission_id,'base_permission_name'=>$permission->base_permission_name,'menu_id'=>$request->menu_id,'submenu_id'=>$request->submenu_id]);
              // DB::table('permissions')->where('id',$submenu_permissions->id)->update(['name' => $permission_name,'base_permission_id'=>$permission->base_permission_id,'base_permission_name'=>$permission->base_permission_name,'menu_id'=>$request->menu_id,'submenu_id'=>$request->submenu_id]);
            }
            else
            {
              Permission::create(['name' => $permission_name,'base_permission_id'=>$permission->base_permission_id,'base_permission_name'=>$permission->base_permission_name,'menu_id'=>$request->menu_id,'submenu_id'=>$request->submenu_id]);
            }
            // dd($permission_name);
          }
          else
          {
            $submenu_permissions = Permission::where('base_permission_id',$permission->base_permission_id)->where('menu_id',$request->menu_id)->where('submenu_id',$request->submenu_id)->first(); //Match input //permission to db record
            if ($submenu_permissions)
            {
              $submenu_permissions->delete();
            }
          }
        }
      }
      else
      {
        $submenu_permissions = Permission::where('base_permission_id',$permission->base_permission_id)->where('menu_id',$request->menu_id)->where('submenu_id',$request->submenu_id)->get(); //Match input //permission to db record
        if ($submenu_permissions)
        {
          $submenu_permissions->each->delete();
        }
      }
      if($backendsubmenu->update())
      {
        // $cat = BackendSubMenubar::Where('category_id',$backendsubmenu->category_id)->first();
        // $cat->category_slug = str_slug($backendsubmenu->category_name );
        // $cat->save();
      }
      Session::flash('message', 'Sub Menu updated!');
      Session::flash('status', 'success');

      return redirect('admin/backendsubmenu');
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
        $backendsubmenu = BackendSubMenubar::findOrFail($id);
        $submenu_permissions = Permission::where('menu_id',$backendsubmenu->menu_id)->where('submenu_id',$backendsubmenu->submenu_id)->get(); //Match input //permission to db record
        if ($submenu_permissions)
        {
          $submenu_permissions->each->delete();
        }
        $backendsubmenu->delete();

        Session::flash('message', 'Sub Menu deleted!');
        Session::flash('status', 'success');

        return redirect('admin/backendsubmenu');
    }

}
