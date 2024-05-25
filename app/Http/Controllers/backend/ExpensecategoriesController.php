<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\ExpenseCategories;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class ExpensecategoriesController extends Controller
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
        $expensecategories = ExpenseCategories::all();

        return view('backend.expensecategories.index', compact('expensecategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.expensecategories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'expense_category_name' => ['required'],
      ]);

      $expensecategories = new ExpenseCategories();
      $expensecategories->fill($request->all());
      if ($request->hasFile('expense_category_banner_image'))
      {
        $image = $request->file('expense_category_banner_image');
        $destinationPath = public_path('/expense_category_images');
        if (!file_exists($destinationPath))
        {
          mkdir($destinationPath,0777);
        }
        $name = time().rand(1,100).'.'.$image->getClientOriginalExtension();
        $image->move($destinationPath, $name);
        $expensecategories->expense_category_banner_image = $name;
      }

      if($expensecategories->save())
      {
        // $cat = ExpenseCategories::Where('expense_category_id',$category->expense_category_id)->first();
        // $cat->expense_category_slug = str_slug($category->expense_category_name );
        // $cat->save();
        if(isset($request->form_type) && $request->form_type=='expense_category_modal')
        {
          $categories_list = ExpenseCategories::where('visibility',1)->pluck('expense_category_name','expense_category_id');
          // $brands->put('add_brand','Add Brand +');
          $category_options= "";
          foreach($categories_list as $category_id => $category_name)
          {
            $category_options .= '<option value="'.$category_id.'">'.$category_name.'</option>';
          }
          return ['flag'=>'success', 'message'=> 'New Category Added!','expense_categories'=>$category_options];
        }
      }
      else
      {
        if(isset($request->form_type) && $request->form_type=='expense_category_modal')
        {
          return ['flag'=>'error', 'message'=> 'Something went wrong!'];
        }
      }

        Session::flash('success', 'Category added!');
        Session::flash('status', 'success');

        return redirect('admin/expensecategories');

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
        $expensecategories = ExpenseCategories::findOrFail($id);

        return view('backend.expensecategories.show', compact('expensecategories'));
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

        $expensecategories = ExpenseCategories::findOrFail($id);

        return view('backend.expensecategories.edit', compact('expensecategories'));
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
        'expense_category_name' => ['required'],
      ]);
      $id = $request->input('expense_category_id');
      $expensecategories = ExpenseCategories::findOrFail($id);
      $expensecategories->fill($request->all());
      if ($request->hasFile('expense_category_banner_image'))
      {
        $image = $request->file('expense_category_banner_image');
        $destinationPath = public_path('/expense_category_images');
        if (!file_exists($destinationPath))
        {
          mkdir($destinationPath,0777);
        }
        $name = time().'.'.$image->getClientOriginalExtension();
        $image->move($destinationPath, $name);
        $expensecategories->expense_category_banner_image = $name;
      }
      if($expensecategories->update())
      {
        // $cat = ExpenseCategories::Where('expense_category_id',$expensecategories->expense_category_id)->first();
        // $cat->expense_category_slug = str_slug($expensecategories->expense_category_name );
        // $cat->save();
      }
      Session::flash('success', 'Category updated!');
      Session::flash('status', 'success');

      return redirect('admin/expensecategories');
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
        $expensecategories = ExpenseCategories::findOrFail($id);

        $expensecategories->delete();

        Session::flash('success', 'Category deleted!');
        Session::flash('status', 'success');

        return redirect('admin/expensecategories');
    }

}
