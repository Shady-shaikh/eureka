<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\Schemes;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;


class SchemesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $schemes = Schemes::all();

        return view('backend.schemes.index', compact('schemes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.schemes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
              'scheme_title' => 'required',
              'min_product_qty' => 'required|integer',
              'free_product_qty' => 'required|integer',
          ]);
        Schemes::create($request->all());

        Session::flash('success', 'Offer added successfully!');
        Session::flash('status', 'success');

        return redirect('admin/schemes');
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
        $schemes = Schemes::findOrFail($id);

        return view('backend.schemes.show', compact('schemes'));
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
        $schemes = Schemes::findOrFail($id);
        return view('backend.schemes.edit', compact('schemes'));
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
        $this->validate(request(), [
              'scheme_title' => 'required',
              'min_product_qty' => 'required|integer',
              'free_product_qty' => 'required|integer',
          ]);
        $schemes = Schemes::findOrFail($request->coupon_id);
        $schemes->update($request->all());

        Session::flash('success', 'Offer updated Successfylly!');
        Session::flash('status', 'success');

        return redirect('admin/schemes');
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
        $schemes = Schemes::findOrFail($id);

        $schemes->delete();

        Session::flash('success', 'Offer deleted!');
        Session::flash('status', 'success');

        return redirect('admin/schemes');
    }

}
