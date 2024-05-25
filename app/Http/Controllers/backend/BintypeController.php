<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\backend\BinManagement;
use App\Models\backend\Bintype;


class BintypeController extends Controller
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
    $storagelocations = Bintype::get();
    // dd($storagelocations);
    return view('backend.bintype.index', compact('storagelocations'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {

    return view('backend.bintype.create');
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
      'name' => 'required',

    ]);
    // echo "string";exit;
    // dd($request->all());
    $bintype_data = BinType::where('name',$request->name)->first();
    if(!empty($bintype_data)){
        return redirect('admin/bintype/create/')->with('error', 'This Type Already Exist!');
    }
    $storagelocations = new Bintype();
    $storagelocations->fill($request->all());

    if ($storagelocations->save()) {


      $log = ['module' => 'Bin Type', 'action' => 'Bin Type Created', 'description' => 'Bin Type Created: ' . $request->name];
      captureActivity($log);

      return redirect('admin/bintype/index/')->with('success', 'New Bin Type Added!');
    } else {

      return redirect('admin/bintype/index/')->with('error', 'Something went wrong!');
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
    $storagelocations = Bintype::findOrFail($id);

    return view('backend.bintype.edit', compact('storagelocations'));
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
      'name' => 'required',
    ]);
    // echo "string".$storagelocations_id;exit;
    // $storagelocations = new StorageLocations();
    // dd($request->all());
    $storagelocations = Bintype::findOrFail($storagelocations_id);

    $storagelocations->fill($request->all());

    if ($storagelocations->update()) {

      if ($storagelocations->getChanges()) {
        $new_changes = $storagelocations->getChanges();
        $log = ['module' => 'Bin Type', 'action' => 'Bin Type Updated', 'description' => 'Bin Type Updated: Name=>' . $storagelocations->name];
        captureActivityupdate($new_changes, $log);
      }

      // dd($storagelocations);
      return redirect('admin/bintype/index/')->with('success', 'Bin Type Updated');
    } else {
      return redirect('admin/bintype/index/')->with('error', 'Something went wrong!');
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
    $storagelocations = Bintype::findOrFail($id);
    $storagelocations->delete();


    $log = ['module' => 'Bin Type', 'action' => 'Bin Type Deleted', 'description' => 'Bin Type Deleted: ' . $storagelocations->name];
    captureActivity($log);

    return redirect('admin/bintype/index/')->with('success', 'Bin Type Deleted!');
  }
}
