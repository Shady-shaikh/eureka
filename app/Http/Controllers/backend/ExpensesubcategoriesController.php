<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\ExpenseCategories;
use App\Models\backend\ExpenseSubCategories;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class ExpensesubcategoriesController extends Controller
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
        $expense_subcategories = ExpenseSubCategories::all();
        return view('backend.expensesubcategories.index', compact('expense_subcategories'));
    }

    public function expensecategory($expense_category_id)
    {
      $expense_category = ExpenseCategories::Where('expense_category_id',$expense_category_id)->first();
      $expense_subcategories = ExpenseSubCategories::Where('expense_category_id',$expense_category_id)->get();
      return view('backend.expensesubcategories.index', compact('expense_subcategories','expense_category_id','expense_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
      $categories = ExpenseCategories::all();
      $expense_category_list = collect($categories)->mapWithKeys(function ($item, $key) {
          return [$item['expense_category_id'] => $item['expense_category_name']];
        });
      return view('backend.expensesubcategories.create', compact('expense_category_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'expense_subcategory_name' => ['required'],
      ]);
      $expensesubcategories = new ExpenseSubCategories();

      if($expensesubcategories->fill($request->all())->save())
      {
        Session::flash('success', 'Sub Category added!');
        Session::flash('status', 'success');

        return redirect('admin/expensesubcategories/expensecategory/'.$expensesubcategories->expense_category_id);
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
        $expensesubcategories = ExpenseSubCategories::findOrFail($id);

        return view('backend.expensesubcategories.show', compact('expensesubcategories'));
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
      
      $categories = ExpenseCategories::all();
    
      $category_list = collect($categories)->mapWithKeys(function ($item, $key) {
        // dd($item->toArray());
          return [$item['expense_category_id'] => $item['expense_category_name']];
        });
        // dd($category_list->toArray());
      $expensesubcategories = ExpenseSubCategories::findOrFail($id);
  
      return view('backend.expensesubcategories.edit', compact('expensesubcategories','category_list'));
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
        'expense_subcategory_name' => 'required',
        'expense_category_id' => 'required',
      ]);
      
      $id = $request->input('subcategory_id');
      $expensesubcategories = ExpenseSubCategories::findOrFail($id);
      if($expensesubcategories->update($request->all()))
      {
        // $cat = ExpenseSubCategories::Where('category_id',$expensesubcategories->category_id)->first();
        // $cat->category_slug = str_slug($expensesubcategories->category_name );
        // $cat->save();
        Session::flash('success', 'Sub Category updated!');
        Session::flash('status', 'success');

        return redirect('admin/expensesubcategories/category/'.$expensesubcategories->category_id);
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
        $expensesubcategories = ExpenseSubCategories::findOrFail($id);

        $expensesubcategories->delete();

        Session::flash('success', 'Sub Category deleted!');
        Session::flash('status', 'success');

        return redirect('admin/expensesubcategories/category/'.$expensesubcategories->category_id);
    }

}
