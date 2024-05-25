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

use App\Models\backend\Company;
use App\Models\backend\SeriesMaster;
use Illuminate\Support\Facades\DB;

class SeriesmasterController extends Controller
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


        $details = SeriesMaster::get();
        // dd($details);
        return view('backend.seriesmaster.index', compact('details'));
    }

    // //create new user
    public function create()
    {
        $company = Company::pluck('name', 'company_id');

        $bspl_subcat_data = SeriesMaster::pluck('series_number', 'id');
        $modules = DB::table('modules')->pluck('name', 'id')->toArray();

        return view('backend.seriesmaster.create', compact('bspl_subcat_data', 'company', 'modules'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'company_id' => 'required',
            'module' => 'required',
        ]);

        $if_exists = SeriesMaster::where(['module' => $request->module, 'company_id' => $request->company_id])->first();
        if (!empty($if_exists)) {
            return redirect()->back()->with('error', 'This module is already having series number');
        }

        $model = new SeriesMaster;
        $model->fill($request->all());

        if ($model->save()) {

            $log = ['module' => 'Series Number Master', 'action' => 'Series Number Created', 'description' => 'Series Number Created: ' . $request->series_number];
            captureActivity($log);


            return redirect('/admin/seriesmaster')->with('success', 'New Series Number Added');
        }
    }




    //edit details
    public function edit($id)
    {
        $model = SeriesMaster::where('id', $id)->first();
        $company = Company::pluck('name', 'company_id');

        $modules = DB::table('modules')->pluck('name', 'id')->toArray();

        // dd($model);
        return view('backend.seriesmaster.edit', compact('company', 'model', 'modules'));
    }

    public function update(Request $request)
    {

        // dd($request->route);
        $request->validate([
            'company_id' => 'required',
            'module' => 'required',
        ]);


        $if_exists = SeriesMaster::where(['module' => $request->module, 'company_id' => $request->company_id])->where('id', '!=', $request->id)->first();
        if (!empty($if_exists)) {
            return redirect()->back()->with('error', 'This module is already having series number');
        }

        $model = SeriesMaster::where('id', $request->id)->first();

        $model->fill($request->all());
        if ($model->save()) {


            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Series Number Master', 'action' => 'Series Number Updated', 'description' => 'Series Number Updated: Name=>' . $model->series_number];
                captureActivityupdate($new_changes, $log);
            }

            // dd('sdgdfg');
            return redirect('/admin/seriesmaster')->with('success', 'Series Number Updated');
        }
    }




    //function for delete address
    public function Destroyseriesmaster($id)
    {

        $model = SeriesMaster::find($id);
        $model->delete();

        $log = ['module' => 'Series Number Master', 'action' => 'Series Number Deleted', 'description' => 'Series Number Deleted: ' . $model->series_number];
        captureActivity($log);


        return redirect(url()->previous())->with('success', 'Series Number Deleted Successfully');
    }
} //end of class
