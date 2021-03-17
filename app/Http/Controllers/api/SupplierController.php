<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;

class SupplierController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suppliers = User::where('rol_id', '18')
                        ->orderBy('names', 'asc')
                        ->get();

        return $this->successResponse($suppliers);
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
        $supplier_qty = Supplier::where('rut', $id)->count();
        $user_qty = User::where('rut', $id)->count();
        if ($supplier_qty == 0 && $user_qty == 0) {
            // abrimos la sesión cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://libredte.cl/api/dte/contribuyentes/info/'.$id);
            $header = [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic '.base64_encode('rcabezas:Jisparking2018'),
            ];
            // pass header variable in curl method
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            // recibimos la respuesta y la guardamos en una variable
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $remote_server_output = curl_exec($ch);
            // cerramos la sesión cURL
            curl_close($ch);
            $results = json_decode($remote_server_output, true);
            $supplier = new User;
            $supplier->rut = $id;
            $supplier->names = $results['razon_social'];
            $supplier->email = $results['email'];
            $supplier->rol_id = 14;
            if ($supplier->save()) {
                $supplier = new Supplier;
                $supplier->rut = $id;
                if ($supplier->save()) {
                    $supplier = Supplier::where('rut', $id)
                        ->with('user')
                        ->first();
                }
            }
        } else {
            $supplier = Supplier::where('rut', $id)
                        ->with('user')
                        ->first();
        }

        return $this->successResponse($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::where('rut', $id)
                        ->with('user')
                        ->first();

        return $this->successResponse($supplier);
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
