<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\backend\UoMs;
use App\Models\backend\Manufacturers;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule; //import Rule class

class UomsController extends Controller
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
        $uoms = UoMs::all();
        return view('backend.uoms.index',compact('uoms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('backend.uoms.create');
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
          'uom_name' => ['required',],
        ]);
        // echo "string";exit;
        // dd($request->all());
        $uom = new UoMs();
        $uom->fill($request->all());

        if ($uom->save())
        {
          if(isset($request->form_type) && $request->form_type=='uom_modal')
          {
            $uoms = UoMs::orderBy('created_at', 'desc')->pluck('uom_name','uom_id');
            // $uoms->put('add_uom','Add UoM +');
            $uom_options= "";
            foreach($uoms as $uom_id => $uom_name)
            {
              $uom_options .= '<option value="'.$uom_id.'">'.$uom_name.'</option>';
            }
            return ['flag'=>'success', 'message'=> 'New UoM Added!','uoms'=>$uom_options];
          }
          return redirect('admin/uoms/index/')->with('success', 'New UoM Added!');
        }
        else
        {
          if(isset($request->form_type) && $request->form_type=='uom_modal')
          {
            return ['flag'=>'error', 'message'=> 'Something went wrong!'];
          }
          return redirect('admin/uoms/index/')->with('error', 'Something went wrong!');
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
        $uom = UoMs::findOrFail($id);
        // dd($has_permissions);
        return view('backend.uoms.edit',compact('uom'));
        // return view('backend.uoms.edit')->with('role', $role);
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
        $uom_id = $request->input('uom_id');
        $this->validate( $request, [
          'uom_name' => ['required',],
        ]);
        // echo "string".$uom_id;exit;
        // dd($request->all());
        // $uom = new UoMs();
        $uom = UoMs::findOrFail($uom_id);
        $uom->fill($request->all());

        if ($uom->update())
        {
          // dd($uom);
          return redirect('admin/uoms/index/')->with('success','UoM Updated');
        }
        else
        {
          return redirect('admin/uoms/index/')->with('error','Something went wrong!');
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
        $uom = UoMs::findOrFail($id);
        $uom->delete();
        return redirect('admin/uoms/index/')->with('success', 'UoM Deleted!');
    }
}
