<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\backend\StockCountAdjustment;
use App\Models\backend\Manufacturers;
use App\Http\Controllers\Controller;
use App\Models\backend\BinManagement;
use App\Models\backend\BinTransfer;
use App\Models\backend\Bintype;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\Company;
use App\Models\backend\Financialyear;
use App\Models\backend\Gst;
use App\Models\backend\Inventory;
use App\Models\backend\PerDayInventory;
use App\Models\backend\Products;
use App\Models\backend\StorageLocations;
use App\Models\backend\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; //import Rule class
use Spatie\Permission\Models\Role;


class StockManagementController extends Controller
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
    $company = Company::pluck('name', 'company_id');
    $storage_locations = StorageLocations::pluck('storage_location_name', 'storage_location_id')->all();

    $stockcountadjustment = StockCountAdjustment::with('product', 'storagelocation')->orderby('created_at', 'desc')->get();
    return view('backend.stockmanagement.index', compact('company', 'stockcountadjustment', 'storage_locations'));
  }

  public function bin_transfer_history()
  {
    if (session('company_id') != 0 && session('fy_year') != 0) {
      $data = BinTransfer::where(['fy_year' => session('fy_year'), 'company_id' => session('company_id')])->orderby('created_at', 'desc')->get();
    } else {
      $data = BinTransfer::orderby('created_at', 'desc')->get();
    }
    return view('backend.stockmanagement.bin_history', compact('data'));
  }
  public function update(Request $request)
  {
    // dd($request->all());
    // Additional validation or manipulation of data
    $invoiceItems = $request->input('invoice_items');

    $company_id = session('company_id');
    $fy_year = session('fy_year');
    if (!empty($request->company_id)) {
      $company_id = $request->company_id;
      $Financialyear = get_fy_year($company_id);
      $fy_year = $Financialyear;
    }


    $routeName = Route::currentRouteName();
    $moduleName = explode('.', $routeName)[1] ?? null;
    $series_no = get_series_number($moduleName, $company_id);
    if (empty($series_no)) {
      return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
    }

    foreach ($invoiceItems as $item) {
      if ($item['qty'] > $item['from_qty']) {
        return redirect()->back()->withErrors(['error' => 'Quantity must be less than or equal to Available Quantity']);
      }

      $product = Products::where('item_code', $item['item_code'])->first();
      $item['sku'] = $product->sku;

      // Fetch data for 'from' and 'to' bins
      $from_binData = BinManagement::where(['bin_type' => $item['from_bin'], 'warehouse_id' => $item['from_warehouse']])->first();
      $to_binData = BinManagement::where(['bin_type' => $item['to_bin'], 'warehouse_id' => $item['to_warehouse']])->first();

      if ($from_binData->warehouse_id == $to_binData->warehouse_id && $from_binData->bin_id == $to_binData->bin_id) {
        return redirect()->back()->withErrors(['error' => 'Cannot transfer to same warehouse and bin']);
      }

      // dd($from_binData,$to_binData);
      $binTransfer = new BinTransfer();
      $binTransfer->remarks = $request->remarks;
      $binTransfer->fy_year = $fy_year;
      $binTransfer->company_id = $company_id;
      $binTransfer->user_id = Auth()->guard('admin')->user()->admin_user_id;
      $binTransfer->fill($item);
      $binTransfer->from_bin =  $from_binData->bin_id;
      $binTransfer->to_bin = $to_binData->bin_id;

      if ($binTransfer->save()) {


        if ($binTransfer->getChanges()) {
          $new_changes = $binTransfer->getChanges();
          $log = ['module' => 'Bin Transfer', 'action' => 'Bin Transfer Updated', 'description' => 'Bin Transfer Updated For Item ' . $binTransfer->item_code];
          captureActivityupdate($new_changes, $log);
        }
        // Update 'from' inventory
        // dd($item,$from_binData);
        if (!empty($from_inventory = Inventory::where([
          'warehouse_id' => $item['from_warehouse'],
          'bin_id' => $from_binData->bin_id,
          'sku' => $item['sku'],
          'item_code' => $item['item_code'],
        ])
          ->where('company_id', $company_id)
          // ->when(
          //   session('company_id') != 0 && session('fy_year') != 0,
          //   function ($query) {
          //     return $query->where([
          //       // 'fy_year' => session('fy_year'),
          //       'company_id' => session('company_id'),
          //     ]);
          //   }
          // )
          ->first())) {
          // dd($from_inventory,$item);
          $this->updateInventory($from_inventory, $item['from_qty'], -$item['qty'], $request->remarks);
          $this->transaction($from_inventory, $item['from_qty'], -$item['qty']);
        }

        if (!empty($from_per_day_inventory = PerDayInventory::where([
          'warehouse_id' => $item['from_warehouse'],
          'bin_id' => $from_binData->bin_id,
          'sku' => $item['sku'],
          'item_code' => $item['item_code'],
        ])
          ->where('company_id', $company_id)
          // ->when(
          //   session('company_id') != 0 && session('fy_year') != 0,
          //   function ($query) {
          //     return $query->where([
          //       // 'fy_year' => session('fy_year'),
          //       'company_id' => session('company_id'),
          //     ]);
          //   }
          // )
          ->whereDate('created_at', Carbon::today())
          ->first())) {
          // dd($from_inventory,$item);
          $this->updateInventory($from_per_day_inventory, $item['from_qty'], -$item['qty'], $request->remarks);
        } else {
          $financial_year = Financialyear::where(['year' => $fy_year, 'company_id' => $company_id])->first();
          $bin_transfer_counter = 0;
          if ($financial_year) {
            $bin_transfer_counter = $financial_year->bin_transfer_counter + 1;
            $doc_no = $series_no . '-' . $financial_year->year . "-" . $bin_transfer_counter;

            $financial_year->bin_transfer_counter = $bin_transfer_counter;
            $financial_year->save();
          }
          $per_day_inventory = new PerDayInventory();
          $per_day_inventory->doc_no = $doc_no;
          $per_day_inventory->batch_no = $item['batch_no'];
          $per_day_inventory->warehouse_id = $item['to_warehouse'];
          $per_day_inventory->bin_id = $from_binData->bin_id;
          $per_day_inventory->sku = $item['sku'] ?? null;
          $per_day_inventory->item_code = $item['item_code'] ?? null;
          $per_day_inventory->qty = $item['from_qty'] - $item['qty'];
          // $per_day_inventory->unit_price = $inventory->unit_price;
          // $per_day_inventory->manufacturing_date = $inventory->manufacturing_date;
          // $per_day_inventory->expiry_date = $inventory->manufacturing_date;
          $per_day_inventory->user_id = Auth()->guard('admin')->user()->admin_user_id;
          $per_day_inventory->fy_year = $fy_year;
          $per_day_inventory->company_id = $company_id;
          $per_day_inventory->remarks = $request->remarks;
          $per_day_inventory->save();
        }

        // Update 'to' inventory
        if (!empty($to_inventory = Inventory::where([
          'warehouse_id' => $item['to_warehouse'],
          'bin_id' => $to_binData->bin_id,
          'sku' => $item['sku'],
          'item_code' => $item['item_code'],
        ])
          ->where('company_id', $company_id)
          // ->when(
          //   session('company_id') != 0 && session('fy_year') != 0,
          //   function ($query) {
          //     return $query->where([
          //       // 'fy_year' => session('fy_year'),
          //       'company_id' => session('company_id'),
          //     ]);
          //   }
          // )
          ->first())) {
          $this->transaction($to_inventory, $to_inventory->qty, $item['qty']);
          $this->updateInventory($to_inventory, $to_inventory->qty, $item['qty'], $request->remarks);
        } else {
          // Create new inventory if 'to' inventory doesn't exist
          $this->createNewInventory($item, $to_binData->bin_id, $request->remarks, null, $series_no, $fy_year, $company_id);
        }

        if (!empty($to_per_day_inventory = PerDayInventory::where([
          'warehouse_id' => $item['to_warehouse'],
          'bin_id' => $to_binData->bin_id,
          'sku' => $item['sku'],
          'item_code' => $item['item_code'],
        ])
          ->where('company_id', $company_id)
          // ->when(
          //   session('company_id') != 0 && session('fy_year') != 0,
          //   function ($query) {
          //     return $query->where([
          //       // 'fy_year' => session('fy_year'),
          //       'company_id' => session('company_id'),
          //     ]);
          //   }
          // )
          ->whereDate('created_at', Carbon::today())
          ->first())) {
          $this->updateInventory($to_per_day_inventory, $to_per_day_inventory->qty, $item['qty'], $request->remarks);
        } else {
          // Create new inventory if 'to' inventory doesn't exist
          $this->createNewInventory($item, $to_binData->bin_id, $request->remarks, 1, $series_no, $fy_year, $company_id);
        }
      }
    }
    return redirect()->back()->with(['success' => 'Data Transfer Successfully']);
  }


  // Function to update inventory and create transaction history
  private function updateInventory($inventory, $qty, $changeQty, $remarks)
  {

    $inventory->company_id = $inventory->company_id;
    $inventory->user_id = Auth()->guard('admin')->user()->admin_user_id;
    $inventory->remarks = $remarks;
    $inventory->qty = $qty + $changeQty;
    $inventory->save();
  }

  // Function to create new inventory and transaction history
  private function createNewInventory($item, $binId, $remarks, $for_per_day = null, $series_no, $fy_year, $company_id)
  {

    $financial_year = Financialyear::where(['year' => $fy_year, 'company_id' => $company_id])->first();
    $bin_transfer_counter = 0;
    if ($financial_year) {
      $bin_transfer_counter = $financial_year->bin_transfer_counter + 1;
      $doc_no = $series_no . '-' . $financial_year->year . "-" . $bin_transfer_counter;

      $financial_year->bin_transfer_counter = $bin_transfer_counter;
      $financial_year->save();
    }

    $per_day_inv = PerDayInventory::where(['warehouse_id' => $item['to_warehouse'], 'bin_id' => $binId, 'item_code' => $item['item_code'], 'company_id' => $company_id])->latest()->first();

    $inventory = new Inventory();
    $inventory->doc_no = $doc_no;
    $inventory->batch_no = $item['batch_no'];
    $inventory->warehouse_id = $item['to_warehouse'];
    $inventory->bin_id = $binId;
    $inventory->sku = $item['sku'] ?? null;
    $inventory->item_code = $item['item_code'] ?? null;
    $inventory->qty = $item['qty'];
    $inventory->unit_price = $inventory->unit_price;
    $inventory->manufacturing_date = $inventory->manufacturing_date;
    $inventory->expiry_date = $inventory->manufacturing_date;
    $inventory->user_id = Auth()->guard('admin')->user()->admin_user_id;
    $inventory->fy_year = $fy_year;
    $inventory->company_id = $company_id;
    $inventory->remarks = $remarks;

    if (!empty($for_per_day)) {
      $per_day_inventory = new PerDayInventory();
      $per_day_inventory->fill($inventory->toArray());
      $per_day_inventory->qty = $per_day_inv->qty ?? 0 + $item['qty'];

      $per_day_inventory->save();
    } else {
      $inventory->save();
      $this->transaction($inventory, 0, $item['qty']);
    }
  }

  private function transaction($inventory, $qty, $changeQty)
  {


    $routeName = Route::currentRouteName();
    $moduleName = explode('.', $routeName)[1] ?? null;
    $transaction_type = get_transaction_type($moduleName);

    $transactionHistory = new Transaction();
    $transactionHistory->doc_no = $inventory->doc_no;
    $transactionHistory->batch_no = $inventory->batch_no;
    $transactionHistory->transaction_type = $transaction_type;
    $transactionHistory->warehouse_id = $inventory->warehouse_id;
    $transactionHistory->bin_id = $inventory->bin_id;
    $transactionHistory->item_code = $inventory->item_code;
    $transactionHistory->unit_price = $inventory->unit_price;
    $transactionHistory->manufacturing_date = $inventory->manufacturing_date;
    $transactionHistory->expiry_date = $inventory->expiry_date;
    $transactionHistory->sku = $inventory->sku;
    $transactionHistory->qty = $qty;
    $transactionHistory->updated_qty = $changeQty;
    $transactionHistory->final_qty = $qty + $changeQty;
    $transactionHistory->user_id = Auth()->guard('admin')->user()->admin_user_id;
    $transactionHistory->fy_year = $inventory->fy_year;
    $transactionHistory->company_id = $inventory->company_id;
    $transactionHistory->save();
  }


  public function get_bins()
  {
    $from_warehouse_id = $_GET['from_warehouse_id'] ?? null;
    $to_warehouse_id = $_GET['to_warehouse_id'] ?? null;

    $options = '<option value="">Select Bin</option>';

    if (!is_null($from_warehouse_id)) {
      $bin_data = BinManagement::where('warehouse_id', $from_warehouse_id)->pluck('bin_type', 'bin_id');
    } elseif (!is_null($to_warehouse_id)) {
      $bin_data = BinManagement::where('warehouse_id', $to_warehouse_id)->pluck('bin_type', 'bin_id');
    }

    if (isset($bin_data)) {
      $bin_type = Bintype::whereIn('bin_type_id', $bin_data)->pluck('name', 'bin_type_id');

      foreach ($bin_type as $binTypeId => $binTypeName) {
        $options .= '<option value="' . $binTypeId . '">' . $binTypeName . '</option>';
      }
    }

    return $options;
  }

  public function get_batches()
  {
    $sku = $_GET['sku'];
    $data = Inventory::where('sku', $sku)->pluck('batch_no', 'batch_no');
    $options = '<option value="">Select Batch</option>';
    if (!empty($data)) {
      foreach ($data as $binTypeId => $binTypeName) {
        $options .= '<option value="' . $binTypeId . '">' . $binTypeName . '</option>';
      }
    }

    return $options;
  }

  public function get_available_qty()
  {
    $warehouse_id = $_GET['warehouse_id'];
    $from_bin_id = $_GET['from_bin_id'];
    $item_code = $_GET['item_code'];
    $company_id = $_GET['company_id'];



    $binData = BinManagement::where(['bin_type' => $from_bin_id, 'warehouse_id' => $warehouse_id])->first();
    $data = Inventory::where(['company_id' => $company_id, 'warehouse_id' => $warehouse_id, 'item_code' => $item_code, 'bin_id' => $binData->bin_id])->first();


    if (!empty($data)) {
      return $data->toArray();
    } else {
      return false;
    }
  }
}
