<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\backend\StorageLocations;
use App\Models\backend\Manufacturers;
use App\Http\Controllers\Controller;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\Company;
use App\Models\backend\Products;
use Illuminate\Validation\Rule; //import Rule class

class StoragelocationsController extends Controller
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
    $storagelocations = StorageLocations::get();
    // dd($storagelocations);
    return view('backend.storagelocations.index', compact('storagelocations'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $products = Products::pluck('consumer_description', 'product_item_id');
    $company_ship_add = Company::orderby('company_id', 'desc')->pluck('city', 'company_id');

    // dd($company_ship_add->toArray());
    return view('backend.storagelocations.create', compact('products', 'company_ship_add'));
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
      'storage_location_name' => 'required',
      'warehouse_address' => 'required',
      // 'location' => 'required',
      // 'zip_code' => 'required',
      // 'country' => 'required',
      // 'state' => 'required',
      // 'city' => 'required',
    ]);
    // echo "string";exit;
    // dd($request->all());
    $storagelocations = new StorageLocations();
    $storagelocations->fill($request->all());

    if ($storagelocations->save()) {


      $log = ['module' => 'Storage Location', 'action' => 'Storage Location Created', 'description' => 'Storage Location Created: ' . $request->storage_location_name];
      captureActivity($log);

      return redirect('admin/storagelocations/index/')->with('success', 'New Storage Location Added!');
    } else {
      if (isset($request->form_type) && $request->form_type == 'storagelocations_modal') {
        return ['flag' => 'error', 'message' => 'Something went wrong!'];
      }
      return redirect('admin/storagelocations/index/')->with('error', 'Something went wrong!');
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
    $storagelocations = StorageLocations::findOrFail($id);
    // dd($storagelocations);
    $products = Products::pluck('consumer_description', 'product_item_id');

    $company_ship_add = Company::orderby('company_id', 'desc')->pluck('city', 'company_id');

    return view('backend.storagelocations.edit', compact('storagelocations', 'products', 'company_ship_add'));
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
      'storage_location_name' => 'required',
      'warehouse_address' => 'required',

    ]);

    $storagelocations = StorageLocations::findOrFail($storagelocations_id);
    $storagelocations->fill($request->all());

    if ($storagelocations->update()) {

      if ($storagelocations->getChanges()) {
        $new_changes = $storagelocations->getChanges();
        $log = ['module' => 'Storage Location', 'action' => 'Storage Location Updated', 'description' => 'Storage Location Updated: Name=>' . $storagelocations->storage_location_name];
        captureActivityupdate($new_changes, $log);
      }

      // dd($storagelocations);
      return redirect('admin/storagelocations/index/')->with('success', 'Storage Location Updated');
    } else {
      return redirect('admin/storagelocations/index/')->with('error', 'Something went wrong!');
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
    $storagelocations = StorageLocations::findOrFail($id);
    $storagelocations->delete();


    $log = ['module' => 'Storage Location', 'action' => 'Storage Location Deleted', 'description' => 'Storage Location Deleted: ' . $storagelocations->storage_location_name];
    captureActivity($log);

    return redirect('admin/storagelocations/index/')->with('success', 'Storage Location Deleted!');
  }
}
