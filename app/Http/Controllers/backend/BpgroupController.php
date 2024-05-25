<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Bpgroup;
use Illuminate\Http\Request;



class BpgroupController extends Controller
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
        $details = Bpgroup::get();
        // dd($details);
        return view('backend.bpgroup.index', compact('details'));
    }

    // //create new user
    public function create()
    {
        return view('backend.bpgroup.create');
    }

    public function store(Request $request)
    {
        // dd($request->url);
        $request->validate([
            'name' => 'required',
        ]);

        $model = new Bpgroup();
        $model->fill($request->all());

        if ($model->save()) {

            $log = ['module' => 'Bp-Group Master', 'action' => 'Bp-Group Created', 'description' => 'Bp-Group Created: ' . $request->name];
            captureActivity($log);

            if (isset($request->form_type) && $request->form_type == 'group_modal') {
                $zone = Bpgroup::pluck('name', 'id');
                // $brands->put('add_brand','Add Brand +');
                $zone_options = "";
                foreach ($zone as $zone_id => $zone_name) {
                    $zone_options .= '<option value="' . $zone_id . '">' . $zone_name . '</option>';
                }
                return ['flag' => 'success', 'message' => 'New Group Added!', 'zone' => $zone_options];
            }
            return redirect('/admin/bpgroup')->with('success', 'New Group Added');
        }
    }




    //edit details
    public function edit($id)
    {
        $model = Bpgroup::where('id', $id)->first();
        return view('backend.bpgroup.edit', compact('model'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $model = Bpgroup::where('id', $request->id)->first();

        $model->fill($request->all());
        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Bp-Group Master', 'action' => 'Bp-Group Updated', 'description' => 'Bp-Group Updated: Name=>' . $model->name];
                captureActivityupdate($new_changes, $log);
            }

            // dd('sdgdfg');
            return redirect('/admin/bpgroup')->with('success', 'Bp-Group Updated');
        }
    }





    //function for delete address
    public function destroybpgroup($id)
    {


        $model = Bpgroup::find($id);
        $model->delete();

        $log = ['module' => 'BP-Group Master', 'action' => 'BP-Group Deleted', 'description' => 'BP-Group Deleted: ' . $model->name];
        captureActivity($log);


        return redirect()->route('admin.bpgroup')->with('success', 'Group Deleted Successfully');
    }
} //end of class
