<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Zones;
use Illuminate\Http\Request;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\BussinessMasterType;
use App\Models\backend\BussinessPartnerOrganizationType;
use App\Models\backend\TermPayment;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerContactDetails;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\BusinessPartnerCategory;


class ZonesController extends Controller
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
        $details = Zones::get();
        // dd($details);
        return view('backend.zones.index', compact('details'));
    }

    // //create new user
    public function create()
    {
        return view('backend.zones.create');
    }

    public function store(Request $request)
    {
        // dd($request->url);
        $request->validate([
            'zone_name' => 'required',
        ]);

        $model = new Zones;
        $model->fill($request->all());

        if ($model->save()) {

            $log = ['module' => 'Zones Master', 'action' => 'Zones Created', 'description' => 'Zones Created: ' . $request->zone_name];
            captureActivity($log);

            if (isset($request->form_type) && $request->form_type == 'zone_modal') {
                $zone = Zones::pluck('zone_name', 'zone_id');
                // $brands->put('add_brand','Add Brand +');
                $zone_options = "";
                foreach ($zone as $zone_id => $zone_name) {
                    $zone_options .= '<option value="' . $zone_id . '">' . $zone_name . '</option>';
                }
                return ['flag' => 'success', 'message' => 'New Zone Added!', 'zone' => $zone_options];
            }
            return redirect('/admin/zones')->with('success', 'New Zone Added');
        }
    }




    //edit details
    public function edit($id)
    {
        $model = Zones::where('zone_id', $id)->first();
        return view('backend.zones.edit', compact('model'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'zone_name' => 'required',
        ]);
        $model = Zones::where('zone_id', $request->zone_id)->first();

        $model->fill($request->all());
        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Zones Master', 'action' => 'Zone Updated', 'description' => 'Zone Updated: Name=>' . $model->zone_name];
                captureActivityupdate($new_changes, $log);
            }

            // dd('sdgdfg');
            return redirect('/admin/zones')->with('success', 'Zone Updated');
        }
    }





    //function for delete address
    public function destroyZones($id)
    {


        $model = Zones::find($id);

        foreach ($model->get_all_data as $route) {
            $route->get_all_beat_data->each->delete();
        }
        $model->get_all_data->each->delete();
        $model->delete();

        $log = ['module' => 'Zones Master', 'action' => 'Zones Deleted', 'description' => 'Zones Deleted: ' . $model->zone_name];
        captureActivity($log);


        return redirect()->route('admin.zone')->with('success', 'Zones Deleted Successfully');
    }
} //end of class
