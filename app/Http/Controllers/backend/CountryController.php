<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Country;
use Illuminate\Http\Request;



class CountryController extends Controller
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
        $details = Country::get();
        // dd($details);
        return view('backend.country.index', compact('details'));
    }

    // //create new user
    public function create()
    {
        return view('backend.country.create');
    }

    public function store(Request $request)
    {
        // dd($request->url);
        $request->validate([
            'name' => 'required',
        ]);

        $model = new Country;
        $model->fill($request->all());

        if ($model->save()) {

            $log = ['module' => 'Country Master', 'action' => 'Country Created', 'description' => 'Country Created: ' . $request->name];
            captureActivity($log);

            return redirect('/admin/country')->with('success', 'New Country Added');
        }
    }




    //edit details
    public function edit($id)
    {
        $model = Country::where('country_id', $id)->first();
        return view('backend.country.edit', compact('model'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);;
        $model = Country::where('country_id', $request->country_id)->first();

        $model->fill($request->all());
        if ($model->save()) {
            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Country Master', 'action' => 'Country Updated', 'description' => 'Country Updated: Name=>' . $model->name];
                captureActivityupdate($new_changes, $log);
            }
            // dd('sdgdfg');
            return redirect('/admin/country')->with('success', 'Country Updated');
        }
    }





    //function for delete address
    public function destroy($id)
    {

        $model = Country::find($id);
        $model->delete();
        $log = ['module' => 'Country Master', 'action' => 'Country Deleted', 'description' => 'Country Deleted: ' . $model->name];
        captureActivity($log);


        return redirect()->route('admin.country')->with('success', 'Country Deleted Successfully');
    }
} //end of class
