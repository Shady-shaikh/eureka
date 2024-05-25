<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\backend\AdminUsers;
use Carbon\Carbon;
use Auth;
use Hash;
use Session;
use App\Models\backend\Gst;

use Spatie\Permission\Models\Role;


class GSTController extends Controller
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
        $gst = Gst::orderBy('gst_id', 'DESC')->get();
        return view('backend.gst.index', compact('gst'));
    }

    public function showusers()
    {
        //  dd('welcoome');
        $adminusers = AdminUsers::orderBy('admin_user_id', 'DESC')->get();
        return view('backend.admin.index', compact('adminusers'));
    }

    public function create()
    {
        return view('backend.gst.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'gst_name' => 'required',
            'gst_percent' => 'required | integer'
        ]);
        $gst = new Gst;
        $gst->fill($request->all());
        $gst->save();

        $log = ['module' => 'Gst', 'action' => 'Gst Created', 'description' => 'Gst Created: ' . $request->gst_name];
        captureActivity($log);


        return redirect('/admin/gst')->with('success', 'New Gst record Created');
    }

    public function edit($id)
    {
        $gst = Gst::where('gst_id', $id)->first();
        // $role = Role::get(['id', 'name']);
        return view('backend.gst.edit', compact('gst'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'gst_name' => 'required',
            'gst_percent' => 'required | integer'
        ]);
        $update_data = $request->all();
        //unset($update_data['_token']);
        //  dd($update_data);
        $data = Gst::where('gst_id', $request->gst_id)->get();
        if (count($data) > 0) {
            // $userdata = InternalUser::where('user_id', $request->id)->update($update_data);
            $gst = Gst::where('gst_id', $request->gst_id)->first();

            $gst->fill($request->all());
            if ($gst->save()) {

                if ($gst->getChanges()) {
                    $new_changes = $gst->getChanges();
                    $log = ['module' => 'Gst', 'action' => 'Gst Updated', 'description' => 'Gst Updated: Name=>' . $gst->gst_name];
                    captureActivityupdate($new_changes, $log);
                }


                return redirect('/admin/gst')->with('success', 'GST Has Been Updated');
            }
        }
    }

    //delete user
    public function destroyUser($id)
    {
        $gst = Gst::where('gst_id', $id)->first();
        if (!empty($gst)) {
            if (Gst::where('gst_id', $id)->delete()) {

                $log = ['module' => 'Gst', 'action' => 'Gst Deleted', 'description' => 'Gst Deleted: ' . $gst->gst_name];
                captureActivity($log);

                return redirect('/admin/gst')->with('success', 'GST Has Been Deleted');
            }
        }
    }
}//end of class
