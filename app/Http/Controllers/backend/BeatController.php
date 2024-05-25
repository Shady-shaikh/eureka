<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\Beat;
use App\Models\backend\Route;
use Illuminate\Http\Request;

class BeatController extends Controller
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
        $details = Beat::get();
        $area_data = Area::get();
        $route_data = Route::get();
        return view('backend.beat.index', compact('details', 'area_data', 'route_data'));
    }

    // //create new user
    public function create()
    {
        $area_data = Area::pluck('area_name', 'area_id');
        return view('backend.beat.create', compact('area_data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'area_id' => 'required',
            'route_id' => 'required',
            'beat_name' => 'required',
        ]);

        $model = new Beat;
        $model->fill($request->all());

        $beat_num = strtoupper($model->area_id . $model->route_id . substr($model->beat_name, -3) . substr(time(), -4));
        $beat_num = str_replace(' ', '', $beat_num);
        // dd($beat_num);
        $model->beat_number = $beat_num;

        if ($model->save()) {

            $log = ['module' => 'Beat Master', 'action' => 'Beat Created', 'description' => 'Beat Created: ' . $request->beat_name];
            captureActivity($log);

            // dd('sdgdfg');
            $beat_options = "";
            if (isset($request->form_type) && $request->form_type == 'beat_modal') {
                if ($request->route != null && $request->area != null) {
                    $beat = Beat::where(['area_id' => $request->area, 'route_id' => $request->route])->pluck('beat_name', 'beat_id');
                } else {
                    $beat_options .= '<option value="">Select Beat</option>';
                    return ['flag' => 'success', 'message' => 'New Beat Added!', 'beat' => $beat_options];
                }

                // $brands->put('add_brand','Add Brand +');

                foreach ($beat as $beat_id => $beat_name) {
                    $beat_options .= '<option value="' . $beat_id . '">' . $beat_name . '</option>';
                }
                return ['flag' => 'success', 'message' => 'New Beat Added!', 'beat' => $beat_options];
            }

            return redirect('/admin/beat')->with('success', 'New Beat Added');
        }
    }


    //edit details
    public function edit($id)
    {
        $model = Beat::where('beat_id', $id)->first();
        $area_data = Area::pluck('area_name', 'area_id');
        return view('backend.beat.edit', compact('model', 'area_data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'area_id' => 'required',
            'route_id' => 'required',
            'beat_name' => 'required',
        ]);

        $model = Beat::where('beat_id', $request->beat_id)->first();

        $model->fill($request->all());
        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Beat Master', 'action' => 'Beat Updated', 'description' => 'Beat Updated: Name=>' . $model->beat_name];
                captureActivityupdate($new_changes, $log);
            }


            // dd('sdgdfg');
            return redirect('/admin/beat')->with('success', 'Beat Updated');
        }
    }





    //function for delete address
    public function destroyBeat($id)
    {
        $model = Beat::find($id);
        $model->delete();


        $log = ['module' => 'Beat Master', 'action' => 'Beat Deleted', 'description' => 'Beat Deleted: ' . $model->beat_name];
        captureActivity($log);

        return redirect()->route('admin.beat')->with('success', 'Beat Deleted Successfully');
    }
} //end of class
