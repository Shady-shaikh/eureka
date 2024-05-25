<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\Bsplcategory;
use App\Models\backend\Bsplsubcategory;
use Illuminate\Http\Request;



class BsplsubcategoryController extends Controller
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


        $details = Bsplsubcategory::get();
        $bspl_cat_data = Bsplcategory::get();
        // dd($details);
        return view('backend.bsplsubcat.index', compact('details', 'bspl_cat_data'));
    }

    // //create new user
    public function create($bspl_category_id = null)
    {
        $create_route_check  = request()->segment(count(request()->segments()));
        $bspl_category = Bsplcategory::where('bsplcat_id', $bspl_category_id)->first();
        // dd($bspl_category[0]);
        $bspl_cat_data = Bsplcategory::pluck('bspl_cat_name', 'bsplcat_id');
        return view('backend.bsplsubcat.create', compact('bspl_cat_data', 'bspl_category_id', 'bspl_category', 'create_route_check'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'bsplcat_id' => 'required',
            'bspl_subcat_name' => 'required',
        ]);

        $model = new Bsplsubcategory;
        $model->fill($request->all());
        // $model->bsplheads_id = $request->route;
        if ($model->save()) {

            $log = ['module' => 'BSPL Sub-Category Master', 'action' => 'BSPL Sub-Category Created', 'description' => 'BSPL Sub-Category Created: ' . $request->bspl_subcat_name];
            captureActivity($log);

 
            if ($request->route == 0) {
                return redirect('/admin/bsplsubcat')->with('success', 'New BSPL Sub-Category Added');
            } else {
                return redirect('/admin/bsplsubcat/bsplcat/' . $request->bsplcat_id)->with('success', 'New BSPL Sub-Category Added');
            }
        }
    }




    //edit details
    public function edit($id)
    {
        $model = Bsplsubcategory::where('bsplsubcat_id', $id)->first();
        $bspl_cat_data = Bsplcategory::pluck('bspl_cat_name', 'bsplcat_id');
        return view('backend.bsplsubcat.edit', compact('model', 'bspl_cat_data'));
    }

    public function update(Request $request)
    {

        // dd($request->route);
        $request->validate([
            'bsplcat_id' => 'required',
            'bspl_subcat_name' => 'required',
        ]);;
        $model = Bsplsubcategory::where('bsplsubcat_id', $request->bsplsubcat_id)->first();

        $model->fill($request->all());
        if ($model->save()) {
            // dd('sdgdfg');

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'BSPL Sub-Category Master', 'action' => 'BSPL Sub-Category Updated', 'description' => 'BSPL Sub-Category Updated: Name=>' . $model->bspl_subcat_name];
                captureActivityupdate($new_changes, $log);
            }

            if ($request->route == 'admin.bsplsubcat.bsplcat') {
                return redirect('/admin/bsplsubcat/bsplcat/' . $request->bsplcat_id)->with('success', 'BSPL Sub-Category Updated');
            } else {
                return redirect('/admin/bsplsubcat')->with('success', 'BSPL Sub-Category Updated');
            }
        }
    }



    public function bsplcategory($bspl_category_id)
    {
        $bspl_category = Bsplcategory::Where('bsplcat_id', $bspl_category_id)->first();
        $details = Bsplsubcategory::Where('bsplcat_id', $bspl_category_id)->get();
        //   dd($bspl_category->toArray());
        return view('backend.bsplsubcat.index', compact('details', 'bspl_category_id', 'bspl_category'));
    }


    //function for delete address
    public function destroyBsplSubCat($id)
    {

        $model = Bsplsubcategory::find($id);
        $model->bspl_type->each->delete();
        $model->delete();

        $log = ['module' => 'BSPL Sub-Category Master', 'action' => 'BSPL Sub-Category Deleted', 'description' => 'BSPL Sub-Category Deleted: ' . $model->bspl_subcat_name];
        captureActivity($log);


        return redirect(url()->previous())->with('success', 'BSPL Sub-Category Deleted Successfully');
    }
} //end of class
