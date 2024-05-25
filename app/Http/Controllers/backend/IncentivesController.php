<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\Beat;
use App\Models\backend\Brands;
use App\Models\backend\Incentives;
use App\Models\backend\InternalUser;
use App\Models\backend\Outlet;
use App\Models\backend\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class IncentivesController extends Controller
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
    public function index(Request $request)
    {

        $details = Incentives::get();
        $all_data = getCommonArrays();


        if ($request->ajax()) {
            $data = Incentives::get();
            // dd($purchaseorder);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('brand_id', fn ($row) => $row->brand->brand_name)
                ->addColumn('format_id', fn ($row) => $row->sub_category->subcategory_name)
                ->addColumn('product_id', fn ($row) => $row->product->consumer_desc ?? '')
                ->addColumn('action', function ($data) {
                    $actionBtn = '<div id="action_buttons">';


                    if (request()->user()->can('Update Incentives')) {
                        $actionBtn .= '<a href="' . route('admin.incentives.edit', ['id' => $data->id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                    }
                    if (request()->user()->can('Delete Incentives')) {
                        $actionBtn .= '<a href="' . route('admin.incentives.delete', ['id' => $data->id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a></div>';
                    }
                    return $actionBtn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.incentives.index', compact('all_data', 'details'));
    }

    // //create new user
    public function create()
    {

        $brands = Brands::pluck('brand_name', 'brand_id');
        $all_data = getCommonArrays();
        return view('backend.incentives.create', compact('all_data', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'brand_id' => 'required',
            'format_id' => 'required',
            'product_id' => 'required',
            'month' => 'required',
            'amount' => 'required',
        ]);

        $focusmap_validate = Incentives::where(['brand_id' => $request->brand_id, 'format_id' => $request->format_id, 'product_id' => $request->product_id, 'month' => $request->month])->first();

        if (!empty($focusmap_validate)) {
            return redirect()->back()->with('error', 'This incentives is already added in this month');
        }

        $model = new Incentives();
        $model->fill($request->all());
        if ($model->save()) {

            $log = ['module' => 'Incentives Master', 'action' => 'Incentives Created', 'description' => 'Incentives Created For Product' . $model->product->consumer_desc];
            captureActivity($log);
            return redirect('/admin/incentives')->with('success', 'New Incentives Added');
        }
    }


    //edit details
    public function edit($id)
    {
        $brands = Brands::pluck('brand_name', 'brand_id');
        $all_data = getCommonArrays();
        $model = Incentives::where('id', $id)->first();


        return view('backend.incentives.edit', compact('all_data', 'model', 'brands'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'brand_id' => 'required',
            'format_id' => 'required',
            'product_id' => 'required',
            'month' => 'required',
            'amount' => 'required',
        ]);

        $model = Incentives::where('id', $request->id)->first();
        $model->fill($request->all());

        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Incentives Master', 'action' => 'Incentives Updated', 'description' => 'Incentives Updated For Product ' . $model->product->consumer_desc];
                captureActivityupdate($new_changes, $log);
            }
            return redirect('/admin/incentives')->with('success', 'Incentives Updated');
        }
    }





    //function for delete address
    public function destroyincentives($id)
    {
        $model = Incentives::where('id', $id)->first();
        $model->delete();

        $log = ['module' => 'Incentives Master', 'action' => 'Incentives Deleted', 'description' => 'Incentives Deleted For Product ' . $model->product->consumer_desc];
        captureActivity($log);

        return redirect()->route('admin.incentives')->with('success', 'Incentives Deleted Successfully');
    }
} //end of class
