<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Tax;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client;

class TaxController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        // It is used to connect to a dropbox account. It allows to get the client.
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
        // It's necessary to check if the user has the permissions.
        $this->user = User::where('api_token', $request->api_token)->first();

        // It checks if the user is rol_id = 4 because if it is, it's necessary to get all the branch offices which they belong to this supervisor.
        if ($this->user->rol_id == 4) {
            // It finds a supervisor by rut.
            $this->branch_offices = BranchOffice::where('supervisor_id', $this->user->rut)->pluck('branch_office_id')->toArray();
        } else {
            // If it's not supervisor it just needs to return all the branch offices.
            $this->branch_offices = BranchOffice::all();
            $this->branch_offices->toArray();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = $request->segment(4);

        if (($date == 'null')
        || ($date == '')
        ) {
            $taxes = Tax::orderBy('tax_id', 'desc')
                        ->paginate(10);
        } else {
            $query = '';

            $date = explode('-', $date);

            if ($date != 'null') {
                $query .= 'month = '.$date[1];
            }

            if ($date != 'null') {
                $query .= ' AND ';

                $query .= 'year = '.$date[0];
            }

            $taxes = Tax::whereRaw($query)
                        ->orderBy('tax_id', 'desc')
                        ->paginate(10);
        }

        return $this->successResponse($taxes);
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
        $fileName = time().'_'.'iva'.'.'.$request->file->getClientOriginalExtension();
        $date = explode('-', $request->date);
        $tax = new Tax;
        $tax->month = $date[1];
        $tax->year = $date[0];
        $tax->support = $fileName;
        if ($tax->save()) {
            Storage::disk('dropbox')->putFileAs(
                'taxes/',
                $request->file,
                $fileName
            );
        }

        return $this->successResponse($tax);
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
        $tax = Tax::find($id);
        if ($tax->delete()) {
            $this->dropbox->delete('taxes/'.$tax->support);
        }

        return $this->successResponse($tax);
    }
}
