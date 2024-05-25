<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\BeatCalender;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\Outlet;
use Illuminate\Http\Request;

use App\Models\backend\SeriesMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class OutletController extends Controller
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


        $details = BussinessPartnerMaster::where('is_converted', 0)->get();
        // dd($details);
        return view('backend.outlet.index', compact('details'));
    }

    // //create new user
    public function create()
    {

        $area_data = Area::pluck('area_name', 'area_id');
        $salesman_department_ids = Role::where('department_id', 8)->pluck('id')->toArray();
        $sales_execu_ids = Role::where('department_id', 9)->pluck('id')->toArray();
        $ase_data_ids = Role::where('department_id', 4)->pluck('id')->toArray();
        $asm_data_ids = Role::where('department_id', 5)->pluck('id')->toArray();


        $salesman = AdminUsers::where('company_id', session('company_id'))->whereIn('role', $salesman_department_ids)->get()->pluck('first_name', 'admin_user_id');
        $sales_execu_data = AdminUsers::where('company_id', session('company_id'))->whereIn('role', $sales_execu_ids)->get()->pluck('full_name', 'admin_user_id');
        $ase_data = AdminUsers::where('company_id', session('company_id'))->whereIn('role', $ase_data_ids)->get()->pluck('full_name', 'admin_user_id');
        $asm_data = AdminUsers::where('company_id', session('company_id'))->whereIn('role', $asm_data_ids)->get()->pluck('full_name', 'admin_user_id');

        //   dd($area_data,$sales_execu_data,$salesman,$ase_data,$asm_data);


        return view('backend.outlet.create', compact('area_data', 'sales_execu_data', 'salesman', 'ase_data', 'asm_data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'outlet_name' => 'required',
            'building_no_name' => 'required',
            'street_name' => 'required',
            'landmark' => 'required',
            'country' => 'required',
            'state' => 'required',
            'district' => 'required',
            'city' => 'required',
            'pin_code' => 'required|numeric',
            // 'phone' => 'required|numeric',
            'salesman' => 'required',
            'area_id' => 'required',
            'route_id' => 'required',
            'beat_id' => 'required',
        ]);

        $data = $request->all();
        $bussiness = new BussinessPartnerMaster();
        $bussiness->fill($request->all());
        $bussiness->business_partner_type = 1;
        $bussiness->bp_name = $data['outlet_name'];
        $bussiness->is_converted = 0;

        if ($bussiness->save()) {

            $bid = $bussiness->business_partner_id;
            $uid = ['bussiness_partner_id' => $bid];
            $full_data = array_merge($uid, $data);

            //usama_07-02-2024
            $billToAddress = new BussinessPartnerAddress();
            $billToAddress->fill($full_data);
            $billToAddress->address_type = 'Bill-To/ Bill-From';
            $billToAddress->bp_address_name = $full_data['street_name']; // Adjust field name as needed
            $billToAddress->save();

            // Create or update Ship-To/ Ship-From address
            $shipToAddress = new BussinessPartnerAddress();
            $shipToAddress->fill($full_data);
            $shipToAddress->address_type = 'Ship-To/ Ship-From';
            $shipToAddress->bp_address_name = $full_data['street_name']; // Adjust field name as needed
            $shipToAddress->save();


            $log = ['module' => 'Outlet', 'action' => 'Outlet Created', 'description' => 'Outlet Created: ' . $request->outlet_name];
            captureActivity($log);


            return redirect('/admin/outlet')->with('success', 'New Outlet Added');
        }
    }




    //edit details
    public function edit($id)
    {
        $model = Outlet::where('id', $id)->first();


        $area_data = Area::pluck('area_name', 'area_id');
        $salesman_department_ids = Role::where('department_id', 8)->pluck('id')->toArray();
        $sales_execu_ids = Role::where('department_id', 9)->pluck('id')->toArray();
        $ase_data_ids = Role::where('department_id', 4)->pluck('id')->toArray();
        $asm_data_ids = Role::where('department_id', 5)->pluck('id')->toArray();


        $salesman = AdminUsers::where('company_id', session('company_id'))->whereIn('role', $salesman_department_ids)->get()->pluck('first_name', 'admin_user_id');
        $sales_execu_data = AdminUsers::where('company_id', session('company_id'))->whereIn('role', $sales_execu_ids)->get()->pluck('full_name', 'admin_user_id');
        $ase_data = AdminUsers::where('company_id', session('company_id'))->whereIn('role', $ase_data_ids)->get()->pluck('full_name', 'admin_user_id');
        $asm_data = AdminUsers::where('company_id', session('company_id'))->whereIn('role', $asm_data_ids)->get()->pluck('full_name', 'admin_user_id');


        return view('backend.outlet.edit', compact('model', 'area_data', 'sales_execu_data', 'salesman', 'ase_data', 'asm_data'));
    }

    public function update(Request $request)
    {

        $request->validate([
            'outlet_name' => 'required',
            'building_no_name' => 'required',
            'street_name' => 'required',
            'landmark' => 'required',
            'country' => 'required',
            'state' => 'required',
            'district' => 'required',
            'city' => 'required',
            'pin_code' => 'required|numeric',
            'phone' => 'required|numeric',
            'salesman' => 'required',
            'area_id' => 'required',
            'route_id' => 'required',
            'beat_id' => 'required',
        ]);


        $model = Outlet::where('id', $request->id)->first();

        $beat_days = implode(",", $request->beat_day);
        $model->fill($request->except('beat_day'));
        unset($model->beat_day);
        $model->beat_day = $beat_days;
        if ($model->save()) {


            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Outlet', 'action' => 'Outlet Updated', 'description' => 'Outlet Updated: Name=>' . $model->series_number];
                captureActivityupdate($new_changes, $log);
            }

            // dd('sdgdfg');
            return redirect('/admin/outlet')->with('success', 'Outlet Updated');
        }
    }




    //function for delete address
    public function destroyoutlet($id)
    {


        $data = BussinessPartnerMaster::where('business_partner_id', $id)->get();

        if (count($data) > 0) {
            if (BussinessPartnerMaster::where('business_partner_id', $id)->delete()) {
                BussinessPartnerAddress::where('bussiness_partner_id', $id)->delete();

                $log = ['module' => 'Outlet', 'action' => 'Outlet Deleted', 'description' => 'Outlet Deleted: '];
                captureActivity($log);


                return redirect(url()->previous())->with('success', 'Outlet Deleted Successfully');
            }
        }
    }
} //end of class
