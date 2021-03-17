<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Str;

class BranchOfficeController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->user = User::where('api_token', $request->api_token)->first();

        if ($this->user->rol_id == 4) {
            $this->branch_offices = BranchOffice::where('visibility_id', 1)->where('supervisor_id', $this->user->rut)->pluck('branch_office_id')->toArray();
        } else {
            $this->branch_offices = BranchOffice::where('visibility_id', 1)->get();

            $this->branch_offices->toArray();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->rol_id != 1 && $this->user->rol_id != 11 && $this->user->rol_id != 16) {
            $branch_offices = $this->branch_offices;
            $query = '';
            for ($i = 0; $i < count($branch_offices); $i++) {
                if ($i == 0) {
                    $query .= 'branch_office_id = '.$branch_offices[$i];
                } else {
                    $query .= ' OR branch_office_id = '.$branch_offices[$i];
                }
            }
            $branch_offices = BranchOffice::whereRaw($query)
                                        ->where('visibility_id', 1)
                                        ->orderBy('branch_office', 'asc')
                                        ->get();
        } else {
            $branch_offices = BranchOffice::where('visibility_id', 1)
                                        ->orderBy('branch_office', 'asc')
                                        ->get();
        }

        return $this->successResponse($branch_offices);
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
        $branch_office = new BranchOffice;
        $branch_office->branch_office = $request->name;
        $branch_office->commune_id = $request->commune_id;
        $branch_office->dte_code = $request->dte_code;
        $branch_office->price_minute = $request->price_minute;
        $branch_office->spots = $request->spots;
        $branch_office->folios_number = $request->folios_number;
        $branch_office->status_id = 15;
        $branch_office->save();

        return $this->successResponse($branch_office);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $branch_office = BranchOffice::find($id);

        return $this->successResponse($branch_office);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch_office = BranchOffice::find($id);

        return $this->successResponse($branch_office);
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
        $branch_office = BranchOffice::find($id);

        $branch_office->delete();

        return $this->successResponse($branch_office);
    }
}
