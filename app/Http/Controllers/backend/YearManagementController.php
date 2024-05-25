<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\backend\StorageLocations;
use App\Models\backend\Manufacturers;
use App\Http\Controllers\Controller;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\Company;
use App\Models\backend\Products;
use App\Models\backend\YearManagement;
use Illuminate\Validation\Rule; //import Rule class

class YearManagementController extends Controller
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
    $data = YearManagement::get();

    // dd($storagelocations);
    return view('backend.yearmanage.index', compact('data'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $currentYear = date('Y');
    $year_data = [];

    for ($i = $currentYear - 3; $i <= $currentYear + 3; $i++) {
      $startYear = $i;
      $academicYear = "$startYear-" . ($startYear + 1);

      if ($i < $currentYear) {
        $year_data[$startYear] = $startYear; // Past year
      } else {
        $year_data[$startYear] = $startYear; // Future year
      }
    }

    // dd($year_data);
    return view('backend.yearmanage.create', compact('year_data'));
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
      'year_id' => 'required',

    ]);
    // echo "string";exit;
    // dd($request->all());
    $storagelocations = new YearManagement();
    $storagelocations->fill($request->all());

    if ($storagelocations->save()) {


      $log = ['module' => 'Academic Year', 'action' => 'Academic Year Created', 'description' => 'Academic Year Created: ' . $request->storage_location_name];
      captureActivity($log);

      return redirect('admin/yearmanage/index/')->with('success', 'New Academic Year Added!');
    } else {
      return redirect('admin/yearmanage/index/')->with('error', 'Something went wrong!');
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
    $data = YearManagement::findOrFail($id);

    $currentYear = date('Y');
    $year_data = [];

    for ($i = $currentYear - 3; $i <= $currentYear + 3; $i++) {
      $startYear = $i;
      $academicYear = "$startYear-" . ($startYear + 1);

      if ($i < $currentYear) {
        $year_data[$startYear] = $startYear; // Past year
      } else {
        $year_data[$startYear] = $startYear; // Future year
      }
    }

    return view('backend.yearmanage.edit', compact('data', 'year_data'));
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

    $id = $request->input('id');
    $this->validate($request, [
      'year_id' => 'required',
    ]);


    $storagelocations = YearManagement::findOrFail($id);
    $storagelocations->fill($request->all());

    if ($storagelocations->update()) {

      if ($storagelocations->getChanges()) {
        $new_changes = $storagelocations->getChanges();
        $log = ['module' => 'Year Academic', 'action' => 'Year Academic Updated', 'description' => 'Year Academic Updated: Name=>' . $storagelocations->storage_location_name];
        captureActivityupdate($new_changes, $log);
      }

      // dd($storagelocations);
      return redirect('admin/yearmanage/index/')->with('success', 'Year Academic Updated');
    } else {
      return redirect('admin/yearmanage/index/')->with('error', 'Something went wrong!');
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
    $storagelocations = YearManagement::findOrFail($id);
    $storagelocations->delete();

    $log = ['module' => 'Academic Year', 'action' => 'Academic Year Deleted', 'description' => 'Academic Year Deleted: ' . $storagelocations->storage_location_name];
    captureActivity($log);

    return redirect('admin/yearmanage/index/')->with('success', 'Academic Year Deleted!');
  }
}
