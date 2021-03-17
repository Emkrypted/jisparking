<?php

namespace App\Http\Controllers\api;

use App\Request;
use App\PettyCash;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = Request::orderBy('created_at', 'desc')
                    ->paginate(10);

        return $this->successResponse($requests);
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
        $request = new Request;
        $request->request_type_id = $request->request_type_id;
        if($request->save()) {
            if($request->request_type_id == 1) {
                $petty_cash = new PettyCash;
                $petty_cash->request_id = $request->request_id;
                $petty_cash->rut = $request->user_id;
                $petty_cash->gross_amount = $request->gross_amount;
                $petty_cash->save();
            }
        }

        return $this->successResponse($request);
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
        $request = Request::find($id);
        $request->delete();

        return $this->successResponse($request);
    }
}
