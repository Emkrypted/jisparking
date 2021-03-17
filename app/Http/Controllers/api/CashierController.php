<?php

namespace App\Http\Controllers\api;

use App\Cashier;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashierController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashiers = Cashier::with('branch_office')->orderBy('cashier_id', 'asc')->paginate(10);

        return $this->successResponse($cashiers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cashier = new Cashier;
        $cashier->cashier_type_id = $request->cashier_type_id;
        $cashier->branch_office_id = $request->branch_office_id;
        $cashier->status_id = $request->status_id;
        $cashier->cashier = $request->cashier;
        $cashier->serie = $request->serie;
        $cashier->save();

        return $this->successResponse($cashier);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function multiple($id)
    {
        $cashiers = Cashier::where('branch_office_id', $id)->where('visibility_id', 1)->get();

        return $this->successResponse($cashiers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cashier = Cashier::find($id);

        return $this->successResponse($cashier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cashier = Cashier::find($id);

        $cashier->delete();

        return $this->successResponse($cashier);
    }
}
