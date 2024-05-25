<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\backend\Brands;
use App\Models\backend\Manufacturers;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule; //import Rule class

class BrandsController extends Controller
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
        $brands = Brands::all();
        return view('backend.brands.index',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('backend.brands.create');
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
          'brand_name' => ['required',],
        ]);
        // echo "string";exit;
        // dd($request->all());
        $brand = new Brands();
        $brand->fill($request->all());

        if ($brand->save())
        {
          if(isset($request->form_type) && $request->form_type=='brand_modal')
          {
            $brands = Brands::pluck('brand_name','brand_id');
            // $brands->put('add_brand','Add Brand +');
            $brand_options= "";
            foreach($brands as $brand_id => $brand_name)
            {
              $brand_options .= '<option value="'.$brand_id.'">'.$brand_name.'</option>';
            }
            return ['flag'=>'success', 'message'=> 'New Brand Added!','brands'=>$brand_options];
          }
          return redirect('admin/brands/index/')->with('success', 'New Brand Added!');
        }
        else
        {
          if(isset($request->form_type) && $request->form_type=='brand_modal')
          {
            return ['flag'=>'error', 'message'=> 'Something went wrong!'];
          }
          return redirect('admin/brands/index/')->with('error', 'Something went wrong!');
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
        $brand = Brands::findOrFail($id);
        // dd($has_permissions);
        return view('backend.brands.edit',compact('brand'));
        // return view('backend.brands.edit')->with('role', $role);
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
        $brand_id = $request->input('brand_id');
        $this->validate( $request, [
          'brand_name' => ['required',],
        ]);
        // echo "string".$brand_id;exit;
        // dd($request->all());
        // $brand = new Brands();
        $brand = Brands::findOrFail($brand_id);
        $brand->fill($request->all());

        if ($brand->update())
        {
          // dd($brand);
          return redirect('admin/brands/index/')->with('success','Brand Updated');
        }
        else
        {
          return redirect('admin/brands/index/')->with('error','Something went wrong!');
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
        $brand = Brands::findOrFail($id);
        $brand->delete();
        return redirect('admin/brands/index/')->with('success', 'Brand Deleted!');
    }
}
