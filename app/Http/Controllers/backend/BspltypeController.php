<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\Bsplcategory;
use App\Models\backend\Bsplsubcategory;
use App\Models\backend\Bspltype;
use Illuminate\Http\Request;


class BspltypeController extends Controller
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


        $details = Bspltype::with('bspl_subcat_name')->get();
        // dd($details);
        return view('backend.bspltype.index', compact('details'));
    }

    // //create new user
    public function create()
    {

        $bspl_subcat_data = Bsplsubcategory::pluck('bspl_subcat_name', 'bsplsubcat_id');
        // dd($bspl_subcat_data);
        return view('backend.bspltype.create', compact('bspl_subcat_data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'bsplsubcat_id' => 'required',
            'bspl_type_name' => 'required',
        ]);

        $model = new Bspltype;
        $model->fill($request->all());

        if ($model->save()) {

            $log = ['module' => 'BSPL Type Master', 'action' => 'BSPL Type Created', 'description' => 'BSPL Type Created: ' . $request->bspl_type_name];
            captureActivity($log);


            return redirect('/admin/bspltype')->with('success', 'New BSPL Type Added');

        }
    }




    //edit details
    public function edit($id)
    {
        $model = Bspltype::where('bsplstype_id', $id)->first();
        // dd($model);

        $bspl_subcat_data = Bsplsubcategory::pluck('bspl_subcat_name', 'bsplsubcat_id');
        return view('backend.bspltype.edit', compact('model', 'bspl_subcat_data'));
    }

    public function update(Request $request)
    {

        // dd($request->route);
        $request->validate([
            'bsplsubcat_id' => 'required',
            'bspl_type_name' => 'required',
        ]);

        $model = Bspltype::where('bsplstype_id', $request->bsplstype_id)->first();

        $model->fill($request->all());
        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'BSPL Type Master', 'action' => 'BSPL Type Updated', 'description' => 'BSPL Type Updated: Name=>' . $model->bspl_type_name];
                captureActivityupdate($new_changes, $log);
            }
            // dd('sdgdfg');
            return redirect('/admin/bspltype')->with('success', 'BSPL Type Updated');


        }
    }



    //function for delete address
    public function destroyBsplType($id)
    {

        $model = Bspltype::find($id);
        $model->delete();

        $log = ['module' => 'BSPL Type Master', 'action' => 'BSPL Type Deleted', 'description' => 'BSPL Type Deleted: ' . $model->bspl_type_name];
        captureActivity($log);


        return redirect(url()->previous())->with('success', 'BSPL Type Deleted Successfully');
    }
} //end of class
