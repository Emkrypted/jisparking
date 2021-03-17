<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\TransbankCollection;
use Illuminate\Http\Request;

class TransbankCollectionController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $file = url('public/transbank_data/archivo.dat');
        $file = file($file);
        for ($i = 17; $i < count($file); $i++) {
            if ($i > 0) {
                $detail = explode(';', $file[$i]);
                $detail[0] = str_replace('*', '', $detail[0]);
                $date = explode(' ', $detail[0]);
                $detail[0] = $date[0];
                $date = explode('/', $detail[0]);
                $detail[0] = $date[2].'-'.$date[1].'-'.$date[0];
                $detail[1] = str_replace('*', '', $detail[1]);
                $detail[7] = str_replace('*', '', $detail[7]);
                $detail[0] = str_replace('.', '', $detail[0]);
                $detail[1] = str_replace('.', '', $detail[1]);
                $detail[7] = str_replace('.', '', $detail[7]);
                $transbank = new TransbankCollection;
                $transbank->transbank_id = $detail[1];
                $transbank->amount = $detail[7];
                $transbank->created_at = $detail[0];
                $transbank->save();
            }
        }
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
        //
    }
}
