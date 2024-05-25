<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Gstvalue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GstvalueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Gstvalue::all();
        return view('backend.gst_value.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.gst_value.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $model = new Gstvalue();
        $model->fill($request->all());

        if ($model->save()) {

            $log = ['module' => 'GST Value', 'action' => 'GST Value Created', 'description' => 'GST Value Created: Name=>' . $model->value];
            captureActivity($log);

            return redirect()->route('gst_value.index')->with('success', 'Gst Value added successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Gstvalue::find($id);
        return view('backend.gst_value.view', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Gstvalue::find($id);
        return view('backend.gst_value.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $model = Gstvalue::findorFail($id);
        $model->fill($request->all());

        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'GST Value', 'action' => 'GST Value Updated', 'description' => 'GST Value Updated: Name=>' . $model->value];
                captureActivityupdate($new_changes, $log);
            }

            return redirect()->route('gst_value.index')->with('success', 'Gst Value updated successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Gstvalue::findOrFail($id);
        $permission->delete();
        $log = ['module' => 'GST Value', 'action' => 'GST Value Deleted', 'description' => 'GST Value Deleted: Name=>' . $permission->value];
        captureActivity($log);

        return redirect()->route('gst_value.index')->with('success', 'Gst Value deleted successfully');
    }
}
