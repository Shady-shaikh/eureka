<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\backend\StockCountAdjustment;
use App\Models\backend\Manufacturers;
use App\Http\Controllers\Controller;
use App\Models\backend\Products;
use Illuminate\Validation\Rule; //import Rule class

class StockcountadjustmentController extends Controller
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
    $stockcountadjustment = StockCountAdjustment::with('product', 'storagelocation')->get();
    return view('backend.stockcountadjustment.index', compact('stockcountadjustment'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $products = Products::pluck('consumer_description', 'product_item_id');
    return view('backend.stockcountadjustment.create', compact('products'));
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
      'storage_location_name' => ['required',],
    ]);
    // echo "string";exit;
    // dd($request->all());
    $stockcountadjustment = new StockCountAdjustment();
    $stockcountadjustment->fill($request->all());

    if ($stockcountadjustment->save()) {


      $log = ['module' => 'Storage Count Adjustment', 'action' => 'Storage Count Adjustment Created', 'description' => 'Storage Count Adjustment Created: ' . $request->storage_location_id];
      captureActivity($log);

      return redirect('admin/stockcountadjustment/index/')->with('success', 'New Storage Count Adjustment Added!');
    } else {
      if (isset($request->form_type) && $request->form_type == 'stockcountadjustment_modal') {
        return ['flag' => 'error', 'message' => 'Something went wrong!'];
      }
      return redirect('admin/stockcountadjustment/index/')->with('error', 'Something went wrong!');
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
    $stockcountadjustment = StockCountAdjustment::findOrFail($id);
    // dd($stockcountadjustment);
    $products = Products::pluck('consumer_description', 'product_item_id');
    return view('backend.stockcountadjustment.edit', compact('stockcountadjustment', 'products'));
    // return view('backend.stockcountadjustment.edit')->with('role', $role);
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
    $stockcountadjustment_id = $request->input('storage_locations_id');
    $this->validate($request, [
      'storage_location_name' => ['required',],
    ]);
    // echo "string".$stockcountadjustment_id;exit;
    // dd($request->all());
    // $stockcountadjustment = new StockCountAdjustment();
    $stockcountadjustment = StockCountAdjustment::findOrFail($stockcountadjustment_id);
    $stockcountadjustment->fill($request->all());

    if ($stockcountadjustment->update()) {


      if ($stockcountadjustment->getChanges()) {
        $new_changes = $stockcountadjustment->getChanges();
        $log = ['module' => 'Storage Count Adjustment', 'action' => 'Storage Count Adjustment Updated', 'description' => 'Storage Count Adjustment Updated: Name=>' . $stockcountadjustment->storage_location_id];
        captureActivityupdate($new_changes, $log);
      }

      // dd($stockcountadjustment);
      return redirect('admin/stockcountadjustment/index/')->with('success', 'Storage Count Adjustment Updated');
    } else {
      return redirect('admin/stockcountadjustment/index/')->with('error', 'Something went wrong!');
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
    $stockcountadjustment = StockCountAdjustment::findOrFail($id);
    $stockcountadjustment->delete();


    $log = ['module' => 'Storage Count Adjustment', 'action' => 'Storage Count Adjustment Deleted', 'description' => 'Storage Count Adjustment Deleted: ' . $stockcountadjustment->storage_location_id];
    captureActivity($log);

    return redirect('admin/stockcountadjustment/index/')->with('success', 'Storage Count Adjustment Deleted!');
  }
}
