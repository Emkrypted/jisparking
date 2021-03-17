<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Transbank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

class TransbankController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transbanks = Transbank::with('branch_office')->orderBy('transbank_collection_id', 'desc')->paginate(10);

        return $this->successResponse($transbanks);
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
        if ($files = $request->file('file')) {
            $name = $files->getClientOriginalName();
            $files->move('uploads', $name);
        }

        $file = url('public/uploads/'.$name);
        $file = file($file);
        $month = $request->month_id;
        $year = $request->year_id;
        $date = $year.'-'.$month.'-01';
        $collections = Transbank::whereDate('created_at', '=', $date)->get();
        foreach($collections as $collection) {
            $transbank = Transbank::find($collection->transbank_collection_id);
            $transbank->delete();
        }

        for ($i = 17; $i < count($file); $i++) {
            if ($i > 0) {
                $detail = explode(';', $file[$i]);
                print_r($detail);
                $detail[0] = str_replace('*', '', $detail[0]);
                $date = explode(' ', $detail[0]);
                $detail[0] = $date[0];
                $detail[1] = str_replace('*', '', $detail[1]);
                $detail[3] = str_replace('*', '', $detail[4]);
                $detail[6] = str_replace('*', '', $detail[6]);
                $detail[7] = str_replace('*', '', $detail[7]);
                $detail[0] = str_replace('.', '', $detail[0]);
                $detail[1] = str_replace('.', '', $detail[1]);
                $detail[3] = str_replace('.', '', $detail[4]);
                $detail[6] = str_replace('.', '', $detail[6]);
                $detail[7] = str_replace('.', '', $detail[7]);
                $date = explode('/', $detail[0]);
                $branch_office = BranchOffice::where('transbank_code', $detail[1])->first();
                $branch_office_qty = BranchOffice::where('transbank_code', $detail[1])->count();
                if($branch_office_qty > 0) {
                    $transbank = new Transbank;
                    $transbank->branch_office_id = $branch_office->branch_office_id;
                    $transbank->transbank_id = $detail[1];
                    $transbank->card_type = $detail[4];
                    $transbank->fee_type = $detail[6];
                    $transbank->amount = $detail[7];
                    $transbank->created_at = $date[2].'-'.$date[1].'-'.$date[0];
                    $transbank->save();
                }
            }
        }
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

    /**
     * Get the image of the support from Dropbox.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function amount(Request $request)
    {
        $branch_office_id = $request->segment(4);
        $collection_date = $request->segment(5);
        $transbank_amount = Transbank::where('branch_office_id', $branch_office_id)->where('created_at', '>=', $collection_date.' 00:00:00')->where('created_at', '<=', $collection_date.' 23:59:59')->sum('amount');

        return $this->successResponse($transbank_amount);
    }
}
