<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\backend\HSNCodes;
use App\Models\backend\Manufacturers;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule; //import Rule class

class HsncodesController extends Controller
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
        $hsncodes = HSNCodes::all();
        return view('backend.hsncodes.index',compact('hsncodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('backend.hsncodes.create');
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
          'hsncode_name' => ['required',],
        ]);
        // echo "string";exit;
        // dd($request->all());
        $hsncode = new HSNCodes();
        $hsncode->fill($request->all());

        if ($hsncode->save())
        {
          if(isset($request->form_type) && $request->form_type=='hsncode_modal')
          {
            $hsncodes = HSNCodes::pluck('hsncode_id','hsncode_name');
            // $hsncodes->put('add_hsncode','Add HSN Code +');
            $hsncode_options= "";
            foreach($hsncodes as $hsncode_id => $hsncode_name)
            {
              $hsncode_options .= '<option value="'.$hsncode_id.'">'.$hsncode_name.'</option>';
            }
            return ['flag'=>'success', 'message'=> 'New HSN Code Added!','hsncodes'=>$hsncode_options];
          }
          return redirect('admin/hsncodes/index/')->with('success', 'New HSN Code Added!');
        }
        else
        {
          if(isset($request->form_type) && $request->form_type=='hsncode_modal')
          {
            return ['flag'=>'error', 'message'=> 'Something went wrong!'];
          }
          return redirect('admin/hsncodes/index/')->with('error', 'Something went wrong!');
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
        $hsncode = HSNCodes::findOrFail($id);
        // dd($has_permissions);
        return view('backend.hsncodes.edit',compact('hsncode'));
        // return view('backend.hsncodes.edit')->with('role', $role);
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
        $hsncode_id = $request->input('hsncode_id');
        $this->validate( $request, [
          'hsncode_name' => ['required',],
        ]);
        // echo "string".$hsncode_id;exit;
        // dd($request->all());
        // $hsncode = new HSNCodes();
        $hsncode = HSNCodes::findOrFail($hsncode_id);
        $hsncode->fill($request->all());

        if ($hsncode->update())
        {
          // dd($hsncode);
          return redirect('admin/hsncodes/index/')->with('success','HSN Code Updated');
        }
        else
        {
          return redirect('admin/hsncodes/index/')->with('error','Something went wrong!');
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
        $hsncode = HSNCodes::findOrFail($id);
        $hsncode->delete();
        return redirect('admin/hsncodes/index/')->with('success', 'HSN Code Deleted!');
    }
}
