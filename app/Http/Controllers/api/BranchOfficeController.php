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
    public function index()
    {
        // It checks if the user is a supervisor.
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
            // If it's a supervisor it's necessary to return the data of a specific branch offices.
            $branch_offices = BranchOffice::whereRaw($query)
                                        ->where('visibility_id', 1)
                                        ->orderBy('branch_office', 'asc')
                                        ->get();
        } else {
            // If it's not a supervisor it must return all the data.
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
        // It creates a new branch office.
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
        // It displays a branch office data
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
        // It returns a branch office data to be edited.
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
        // It destroys a branch office.
        $branch_office = BranchOffice::find($id);
        $branch_office->delete();

        return $this->successResponse($branch_office);
    }
}
