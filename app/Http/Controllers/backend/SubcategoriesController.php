<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\Categories;
use App\Models\backend\SubCategories;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class SubcategoriesController extends Controller
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
        $subcategories = SubCategories::all();
        return view('backend.subcategories.index', compact('subcategories'));
    }

    public function category($category_id)
    {
      $category = Categories::Where('category_id',$category_id)->first();
      $subcategories = SubCategories::Where('category_id',$category_id)->get();
      return view('backend.subcategories.index', compact('subcategories','category_id','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
      $categories = Categories::where('visibility',1)->pluck('category_name','category_id');
      // $category_list = collect($categories)->mapWithKeys(function ($item, $key) {
      //     return [$item['category_id'] => $item['category_name']];
      //   });
      return view('backend.subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'subcategory_name' => ['required'],
        // 'subcategory_description' => ['required'],
      ]);
      $subcategories = new SubCategories();

      // dd($request->all());
      if($subcategories->fill($request->all())->save())
      {
        // $cat = SubCategories::Where('category_id',$category->category_id)->first();
        // $cat->category_slug = str_slug($category->category_name );
        // $cat->save();
        // dd($request->all());
        if(isset($request->form_type) && $request->form_type=='sub_category_modal')
        {
          $sub_categories_list = SubCategories::where('visibility',1)->pluck('subcategory_name','subcategory_id');
          // $brands->put('add_brand','Add Brand +');
          $sub_category_options= "";
          foreach($sub_categories_list as $subcategory_id => $subcategory_name)
          {
            $sub_category_options .= '<option value="'.$subcategory_id.'">'.$subcategory_name.'</option>';
          }
          return ['flag'=>'success', 'message'=> 'New Sub Category Added!','subcategories'=>$sub_category_options];
        }
        Session::flash('success', 'Sub Category added!');
        Session::flash('status', 'success');

        return redirect('admin/subcategories/category/'.$subcategories->category_id);
      }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $subcategories = SubCategories::findOrFail($id);

        return view('backend.subcategories.show', compact('subcategories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
      $categories = Categories::all();
      $category_list = collect($categories)->mapWithKeys(function ($item, $key) {
          return [$item['category_id'] => $item['category_name']];
        });
      $subcategories = SubCategories::findOrFail($id);
      return view('backend.subcategories.edit', compact('subcategories','category_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update(Request $request)
    {
      $this->validate($request, [
        'subcategory_name' => ['required'],
        'subcategory_description' => ['required'],
      ]);
      $id = $request->input('subcategory_id');
      $subcategories = SubCategories::findOrFail($id);
      if($subcategories->update($request->all()))
      {
        // $cat = SubCategories::Where('category_id',$subcategories->category_id)->first();
        // $cat->category_slug = str_slug($subcategories->category_name );
        // $cat->save();
        Session::flash('success', 'Sub Category updated!');
        Session::flash('status', 'success');

        return redirect('admin/subcategories/category/'.$subcategories->category_id);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $subcategories = SubCategories::findOrFail($id);

        $subcategories->delete();

        Session::flash('success', 'Sub Category deleted!');
        Session::flash('status', 'success');

        return redirect('admin/subcategories/category/'.$subcategories->category_id);
    }

}
