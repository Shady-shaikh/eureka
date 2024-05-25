<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use Illuminate\Http\Request;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\BussinessMasterType;
use App\Models\backend\BussinessPartnerOrganizationType;
use App\Models\backend\TermPayment;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerContactDetails;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\BusinessPartnerCategory;


class AreaController extends Controller
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
        $details = Area::get();
        // dd($details);
        return view('backend.area.index', compact('details'));
    }

    // //create new user
    public function create()
    {
        return view('backend.area.create');
    }

    public function store(Request $request)
    {
        // dd($request->url);
        $request->validate([
            'area_name' => 'required',
        ]);

        $model = new Area;
        $model->fill($request->all());

        if ($model->save()) {

            $log = ['module' => 'Area Master', 'action' => 'Area Created', 'description' => 'Area Created: ' . $request->area_name];
            captureActivity($log);

            if (isset($request->form_type) && $request->form_type == 'area_modal') {
                $area = Area::pluck('area_name', 'area_id');
                // $brands->put('add_brand','Add Brand +');
                $area_options = "";
                foreach ($area as $area_id => $area_name) {
                    $area_options .= '<option value="' . $area_id . '">' . $area_name . '</option>';
                }
                return ['flag' => 'success', 'message' => 'New Area Added!', 'area' => $area_options];
            }
            return redirect('/admin/area')->with('success', 'New Area Added');
        }
    }




    //edit details
    public function edit($id)
    {
        $model = Area::where('area_id', $id)->first();
        return view('backend.area.edit', compact('model'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'area_name' => 'required',
        ]);;
        $model = Area::where('area_id', $request->area_id)->first();

        $model->fill($request->all());
        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Area Master', 'action' => 'Area Updated', 'description' => 'Area Updated: Name=>' . $model->area_name];
                captureActivityupdate($new_changes, $log);
            }

            // dd('sdgdfg');
            return redirect('/admin/area')->with('success', 'Area Updated');
        }
    }





    //function for delete address
    public function destroyArea($id)
    {


        $model = Area::find($id);

        foreach ($model->get_all_data as $route) {
            $route->get_all_beat_data->each->delete();
        }
        $model->get_all_data->each->delete();
        $model->delete();

        $log = ['module' => 'Area Master', 'action' => 'Area Deleted', 'description' => 'Area Deleted: ' . $model->area_name];
        captureActivity($log);


        return redirect()->route('admin.area')->with('success', 'Area Deleted Successfully');
    }
} //end of class
