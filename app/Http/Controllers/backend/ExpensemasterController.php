<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\Bsplheads;

use Illuminate\Http\Request;

use App\Models\backend\Expensemaster;
use App\Models\backend\GLCodes;
use Illuminate\Support\Facades\DB;

class ExpensemasterController extends Controller
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


        $details = Expensemaster::get();
        // dd($details);
        return view('backend.expensemaster.index', compact('details'));
    }

    // //create new user
    public function create()
    {
        $heads = BsplHeads::pluck('bspl_heads_name', 'bsplheads_id');
        $glcodes = GLCodes::pluck('gl_code', 'gl_code');
        return view('backend.expensemaster.create', compact('heads', 'glcodes'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'bsplheads_id' => 'required',
            'bsplcat_id' => 'required',
            'bsplsubcat_id' => 'required',
            'bsplstype_id' => 'required',
            'expense_name' => 'required',
            'gl_code' => 'required',
        ]);

        $model = new Expensemaster;
        $model->fill($request->all());

        if ($model->save()) {
            $log = ['module' => 'Expense Master', 'action' => 'Expense Created', 'description' => 'Expense Created: ' . $request->expense_name];
            captureActivity($log);

            return redirect('/admin/expensemaster')->with('success', 'New Expense Added');
        }
    }




    //edit details
    public function edit($id)
    {
        $model = Expensemaster::where('expense_id', $id)->first();
        $heads = BsplHeads::pluck('bspl_heads_name', 'bsplheads_id');

        $glcodes = GLCodes::pluck('gl_code', 'gl_code');
        return view('backend.expensemaster.edit', compact('model', 'heads', 'glcodes'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'bsplheads_id' => 'required',
            'bsplcat_id' => 'required',
            'bsplsubcat_id' => 'required',
            'bsplstype_id' => 'required',
            'expense_name' => 'required',
            'gl_code' => 'required',
        ]);

        $model = Expensemaster::where('expense_id', $request->expense_id)->first();
        // dd($model);

        $model->fill($request->all());
        if ($model->save()) {

            if ($model->getChanges()) {
                $new_changes = $model->getChanges();
                $log = ['module' => 'Expense Master', 'action' => 'Expense Updated', 'description' => 'Expense Updated: Name=>' . $model->expense_name];
                captureActivityupdate($new_changes, $log);
            }

            // dd('sdgdfg');
            return redirect('/admin/expensemaster')->with('success', 'Expense Updated');
        }
    }





    //function for delete address
    public function destroyExpenseMaster($id)
    {

        $model = Expensemaster::find($id);

        $model->delete();

        $log = ['module' => 'Expense Master', 'action' => 'Expense Deleted', 'description' => 'Expense Deleted: ' . $model->expense_name];
        captureActivity($log);



        return redirect()->route('admin.expensemaster')->with('success', 'Expense Deleted Successfully');
    }
} //end of class
