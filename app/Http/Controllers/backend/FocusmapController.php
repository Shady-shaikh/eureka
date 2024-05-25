<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\Beat;
use App\Models\backend\Brands;
use App\Models\backend\Focusmap;
use App\Models\backend\InternalUser;
use App\Models\backend\Outlet;
use App\Models\backend\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class FocusmapController extends Controller
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

        $details = Focusmap::get();
        $all_data = getCommonArrays();


        if ($request->ajax()) {
            $data = Focusmap::get();
            // dd($purchaseorder);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('brand_id', fn ($row) => $row->brand->brand_name)
                ->addColumn('format_id', fn ($row) => $row->sub_category->subcategory_name)
                ->addColumn('product_id', fn ($row) => $row->product->consumer_desc)
                ->addColumn('action', function ($data) {
                    $actionBtn = '<div id="action_buttons">';


                    if (request()->user()->can('Update Focus Pack (Must Sell Pack)')) {
                        $actionBtn .= '<a href="' . route('admin.focusmap.edit', ['id' => $data->id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                    }
                    if (request()->user()->can('Delete Focus Pack (Must Sell Pack)')) {
                        $actionBtn .= '<a href="' . route('admin.focusmap.delete', ['id' => $data->id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a></div>';
                    }
                    return $actionBtn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.focusmap.index', compact('all_data', 'details'));
    }

    // //create new user
    public function create()
    {

        $brands = Brands::pluck('brand_name', 'brand_id');
        $all_data = getCommonArrays();
        return view('backend.focusmap.create', compact('all_data', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'brand_id' => 'required',
            'format_id' => 'required',
            'product_id' => 'required',
            'month' => 'required',
        ]);

        $focusmap_validate = Focusmap::where(['brand_id' => $request->brand_id, 'format_id' => $request->format_id, 'product_id' => $request->product_id, 'month' => $request->month])->first();

        if (!empty($focusmap_validate)) {
            return redirect()->back()->with('error', 'This foucs map is already added in this month');
        }

        $model = new Focusmap();
        $model->fill($request->all());
        if ($model->save()) {

            $log = ['module' => 'Focus Pack Master', 'action' => 'Focus Pack Created', 'description' => 'Focus Pack Created For Product ' . $model->product->consumer_desc];
            captureActivity($log);
            return redirect('/admin/focusmap')->with('success', 'New Focus Pack Added');
        }
    }


    //edit details
    public function edit($id)
    {
        $brands = Brands::pluck('brand_name', 'brand_id');
        $all_data = getCommonArrays();
        $model = Focusmap::where('id', $id)->first();


        return view('backend.focusmap.edit', compact('all_data', 'model', 'brands'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'brand_id' => 'required',
            'format_id' => 'required',
            'product_id' => 'required',
            'month' => 'required',
        ]);

        $model = Focusmap::where('id', $request->id)->first();
        $model->fill($request->all());

        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Focus Pack Master', 'action' => 'Focus Pack Updated', 'description' => 'Focus Pack Updated For Product ' . $model->product->consumer_desc];
                captureActivityupdate($new_changes, $log);
            }
            return redirect('/admin/focusmap')->with('success', 'Focus Pack Updated');
        }
    }





    //function for delete address
    public function destroyfocusmap($id)
    {
        $model = Focusmap::where('id', $id)->first();
        $model->delete();

        $log = ['module' => 'Focus Pack Master', 'action' => 'Focus Pack Deleted', 'description' => 'Focus Pack Deleted Of Product ' . $model->product->consumer_desc];
        captureActivity($log);

        return redirect()->route('admin.focusmap')->with('success', 'Focus Pack Deleted Successfully');
    }
} //end of class
