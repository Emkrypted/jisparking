<?php

namespace App\Http\Controllers\api;

use App\Customer;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::from('customers as c')
                        ->selectRaw('c.rut as rut, users.names as names, c.email as email, c.created_at as created_at')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->orderBy('c.rut', 'DESC')
                        ->paginate(10);

        return $this->successResponse($customers);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $url = 'https://libredte.cl';
        $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
        $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
        $customer = $LibreDTE->get('/dte/contribuyentes/info/'.$id);
        if ($customer['status']['code'] != 200) {
            exit('Error al emitir DTE temporal: '.$customer['body']."\n");
        }
        
        return $this->successResponse($customer);
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
        //
    }
}
