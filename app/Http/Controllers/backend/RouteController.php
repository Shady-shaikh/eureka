<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use Illuminate\Http\Request;
use App\Models\backend\Route;

class RouteController extends Controller
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
        $details = Route::get();
        $area_data = Area::get();
        return view('backend.route.index', compact('details', 'area_data'));
    }

    // //create new user
    public function create()
    {
        $area_data = Area::pluck('area_name', 'area_id');
        return view('backend.route.create', compact('area_data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'area_id' => 'required',
            'route_name' => 'required',
        ]);

        $model = new Route;
        $model->fill($request->all());
        // dd($model);
        if ($model->save()) {

            $log = ['module' => 'Route Master', 'action' => 'Route Created', 'description' => 'Route Created: ' . $request->route_name];
            captureActivity($log);

            // dd('sdgdfg');
            if (isset($request->form_type) && $request->form_type == 'route_modal') {
                $route_options = "";
                if ($request->area != null) {
                    $route = Route::where('area_id', $request->area)->pluck('route_name', 'route_id');
                } else {
                    $route_options .= '<option value="">Select Route</option>';
                    return ['flag' => 'success', 'message' => 'New Route Added!', 'routes' => $route_options];
                }

                foreach ($route as $route_id => $route_name) {
                    $route_options .= '<option value="' . $route_id . '">' . $route_name . '</option>';
                }
                return ['flag' => 'success', 'message' => 'New Route Added!', 'routes' => $route_options];
            }
            return redirect('/admin/route')->with('success', 'New Route Added');
        }
    }


    //edit details
    public function edit($id)
    {
        $model = Route::where('route_id', $id)->first();
        $area_data = Area::pluck('area_name', 'area_id');
        return view('backend.route.edit', compact('model', 'area_data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'area_id' => 'required',
            'route_name' => 'required',
        ]);;
        $model = Route::where('route_id', $request->route_id)->first();

        $model->fill($request->all());
        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Route Master', 'action' => 'Route Updated', 'description' => 'Route Updated: Name=>' . $model->route_name];
                captureActivityupdate($new_changes, $log);
            }

            // dd('sdgdfg');
            return redirect('/admin/route')->with('success', 'Route Updated');
        }
    }





    //function for delete address
    public function destroyRoute($id)
    {
        $model = Route::find($id);

        $model->get_all_beat_data->each->delete();
        $model->delete();

        $log = ['module' => 'Route Master', 'action' => 'Route Deleted', 'description' => 'Route Deleted: ' . $model->route_name];
        captureActivity($log);



        return redirect()->route('admin.route')->with('success', 'Route Deleted Successfully');
    }
} //end of class
