<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $transaction = new Transaction;
        $transaction->branch_office_id = $request->branch_office_id;
        $transaction->cashier_id = $request->cashier_id;
        $transaction->folio = $request->folio;
        $transaction->gross_amount = $request->gross_amount;
        $transaction->net_amount = $request->net_amount;
        $transaction->created_at = $request->created_at;
        $transaction->updated_at = $request->updated_at;
        $transaction->ticket_number = 0;
        $transaction->released = 0;
        $transaction->save();

        return $this->successResponse($transaction);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bank = Bank::find($id);

        return $this->successResponse($bank);
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
        $transaction = Transaction::find($id);
        $transaction->delete();

        return $this->successResponse($transaction);
    }
}
