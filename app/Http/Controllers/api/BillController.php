<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Commune;
use App\Customer;
use App\Dte;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BillController extends ApiResponseController
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
        // It gets the information sent by the url path.
        $branch_office_id = $request->segment(4);
        $rut = $request->segment(5);
        $status_id = $request->segment(6);
        $supervisor_id = $request->segment(7);
        // It checks if any of them exist or they are nulled.
        if (($branch_office_id == 'null' && $rut == 'null' && $status_id == 'null' && $supervisor_id == 'null')
        || ($branch_office_id == '' && $rut == '' && $status_id == '' && $supervisor_id == '')
        ) {
            // It checks if the user is an administrator.
            if ($this->user->rol_id != 1) {
                // If it's not administrator then it's a supervisor and it must return all the data which they belong to some branch offices.
                $branch_offices = $this->branch_offices;
                $query = 'c.dte_type_id = 33 AND c.dte_version_id = 1 AND c.status_id <> 18 AND c.status_id <> 19';
                $query .= ' AND ';
                for ($i = 0; $i < count($branch_offices); $i++) {
                    if ($i == 0) {
                        $query .= '(c.branch_office_id = '.$branch_offices[$i];
                    } else {
                        $query .= ' OR c.branch_office_id = '.$branch_offices[$i];
                    }
                }

                $query .= ')';

                // This is a query which it returns the dte data. It contains joins to multiple tables.
                /*
                    - dte_type_id: means the dte document type.
                    - dte_version_id: means if it was created for us or it was received.
                    - status_id: means the status of the dte document, it can be paid or waiting to be paid.
                */
                $dtes = Dte::from('dtes as c')
                        ->selectRaw('users.rut as rut, users.names as names, c.dte_id as dte_id, branch_offices.branch_office as branch_office, c.folio as folio, c.dte_version_id as dte_version_id, c.amount as amount, c.dte_type_id as dte_type_id, c.created_at as created_at, c.status_id as status_id, statuses.status as status')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->whereRaw($query)
                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                        ->orderBy('c.status_id', 'DESC')
                        ->paginate(10);
            } else {
                // This is a query which it returns the dte data. It contains joins to multiple tables.
                /*
                    - dte_type_id: means the dte document type.
                    - dte_version_id: means if it was created for us or it was received.
                    - status_id: means the status of the dte document, it can be paid or waiting to be paid.
                */
                $dtes = Dte::from('dtes as c')
                        ->selectRaw('users.rut as rut, users.names as names, c.dte_id as dte_id, branch_offices.branch_office as branch_office, c.folio as folio, c.dte_version_id as dte_version_id, c.amount as amount, c.dte_type_id as dte_type_id, c.created_at as created_at, c.status_id as status_id, statuses.status as status')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->where('dte_type_id', 33)
                        ->where('dte_version_id', 1)
                        ->where('c.status_id', '<>', 18)
                        ->where('c.status_id', '<>', 19)
                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                        ->orderBy('c.status_id', 'DESC')
                        ->orderBy('branch_offices.branch_office', 'ASC')
                        ->paginate(10);
            }
        } else {
            // If they are not empty, it's necessary to use them to create the database query.
            $query = '';
            // It checks every single variable to see if it's nulled or not.
            if ($branch_office_id != 'null') {
                $query .= 'c.branch_office_id = '.$branch_office_id;
            }
            // It checks every single variable to see if it's nulled or not.
            if ($rut != 'null') {
                if ($branch_office_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.rut = "'.$rut.'"';
            }
            // It checks every single variable to see if it's nulled or not.
            if ($status_id != 'null') {
                if ($branch_office_id != 'null' || $rut != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.status_id = '.$status_id;
            }
            // It checks every single variable to see if it's nulled or not.
            if ($supervisor_id != 'null') {
                if ($branch_office_id != 'null' || $rut != 'null' || $status_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'branch_offices.supervisor_id = "'.$supervisor_id.'"';
            }
            // This is a query which it returns the dte data. It contains joins to multiple tables.
            /*
                - dte_type_id: means the dte document type.
                - dte_version_id: means if it was created for us or it was received.
                - status_id: means the status of the dte document, it can be paid or waiting to be paid.
            */
            $dtes = Dte::from('dtes as c')
                        ->selectRaw('users.rut as rut, users.names as names, c.dte_id as dte_id, branch_offices.branch_office as branch_office, c.folio as folio, c.dte_version_id as dte_version_id, c.amount as amount, c.dte_type_id as dte_type_id, c.created_at as created_at, c.status_id as status_id, statuses.status as status')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->whereRaw($query)
                        ->where('dte_type_id', 33)
                        ->where('dte_version_id', 1)
                        ->where('c.status_id', '<>', 18)
                        ->where('c.status_id', '<>', 19)
                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                        ->orderBy('c.status_id', 'DESC')
                        ->paginate(10);
        }

        return $this->successResponse($dtes);
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
        // It stores a new dte document.
        $dte = new Dte;
        $dte->rut = $request->rut;
        $dte->folio = $request->folio;
        $dte->branch_office_id = $request->branch_office_id;
        $dte->dte_type_id = $request->dte_type_id;
        $dte->dte_version_id = 1;
        $dte->amount = $request->amount;
        $dte->expense_type_id = 0;
        $dte->temporal_code = $request->temporal_code;
        $dte->status_id = 17;
        $dte->created_at = $request->created_at;
        $dte->save();

        return $this->successResponse($dte);
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
