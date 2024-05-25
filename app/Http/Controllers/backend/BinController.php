<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\backend\StorageLocations;
use App\Models\backend\Manufacturers;
use App\Http\Controllers\Controller;
use App\Models\backend\BinManagement;
use App\Models\backend\Bintype;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\Company;
use App\Models\backend\Products;
use Illuminate\Validation\Rule; //import Rule class

class BinController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth:admin');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $storagelocations = BinManagement::get();
    // dd($storagelocations);
    return view('backend.binmanagement.index', compact('storagelocations'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $warehouses = StorageLocations::pluck('storage_location_name', 'storage_location_id');
    $bin_type = Bintype::pluck('name', 'bin_type_id');
    // $bin_type = array_combine($bin_type, $bin_type);

    return view('backend.binmanagement.create', compact('warehouses', 'bin_type'));
    // exit;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      // 'bin_name' => 'required',
      'bin_type' => 'required',
      'warehouse_id' => 'required',

    ]);
    // echo "string";exit;
    // dd($request->all());
    $storagelocations = new BinManagement();
    $storagelocations->fill($request->all());

    if ($storagelocations->save()) {


      $log = ['module' => 'Bin Management', 'action' => 'Bin Management Created', 'description' => 'Bin Management Created: ' . $storagelocations->get_bin->name];
      captureActivity($log);

      return redirect('admin/bin/index/')->with('success', 'New Bin Management Added!');
    } else {
      // if (isset($request->form_type) && $request->form_type == 'storagelocations_modal') {
      //   return ['flag' => 'error', 'message' => 'Something went wrong!'];
      // }
      return redirect('admin/bin/index/')->with('error', 'Something went wrong!');
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $storagelocations = BinManagement::findOrFail($id);

    $warehouses = StorageLocations::pluck('storage_location_name', 'storage_location_id');
    // $bin_type = ['Goods','Damage','Block'];.
    $bin_type = Bintype::pluck('name', 'bin_type_id');
    // $bin_type = array_combine($bin_type, $bin_type);

    return view('backend.binmanagement.edit', compact('storagelocations', 'warehouses', 'bin_type'));
    // return view('backend.storagelocations.edit')->with('role', $role);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {

    $storagelocations_id = $request->input('storage_locations_id');
    $this->validate($request, [
      // 'bin_name' => 'required',
      'bin_type' => 'required',
      'warehouse_id' => 'required',

    ]);
    // echo "string".$storagelocations_id;exit;
    // $storagelocations = new StorageLocations();
    // dd($request->all());
    $storagelocations = BinManagement::findOrFail($storagelocations_id);
    $storagelocations->fill($request->all());

    if ($storagelocations->update()) {

      if ($storagelocations->getChanges()) {
        $new_changes = $storagelocations->getChanges();
        $log = ['module' => 'Bin Management', 'action' => 'Bin Management Updated', 'description' => 'Bin Management Updated: Name=>' . $storagelocations->get_bin->name];
        captureActivityupdate($new_changes, $log);
      }

      // dd($storagelocations);
      return redirect('admin/bin/index/')->with('success', 'Bin Management Updated');
    } else {
      return redirect('admin/bin/index/')->with('error', 'Something went wrong!');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $storagelocations = BinManagement::findOrFail($id);
    $storagelocations->delete();


    $log = ['module' => 'Bin Management', 'action' => 'Bin Management Deleted', 'description' => 'Bin Management Deleted: ' . $storagelocations->get_bin->name];
    captureActivity($log);

    return redirect('admin/bin/index/')->with('success', 'Bin Management Deleted!');
  }
}
