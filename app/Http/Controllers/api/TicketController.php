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

class TicketController extends ApiResponseController
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
        $branch_office_id = $request->segment(4);
        $rut = $request->segment(5);
        $status_id = $request->segment(6);
        $supervisor_id = $request->segment(7);
        if (($branch_office_id == 'null' && $rut == 'null' && $status_id == 'null' && $supervisor_id == 'null')
        || ($branch_office_id == '' && $rut == '' && $status_id == '' && $supervisor_id == '')
        ) {
            if ($this->user->rol_id != 1) {
                $branch_offices = $this->branch_offices;
                $query = 'c.dte_type_id = 39 AND c.rut <> "66666666-6" AND c.status_id <> 18 AND c.status_id <> 19 AND c.dte_version_id = 1';
                $query .= ' AND ';
                for ($i = 0; $i < count($branch_offices); $i++) {
                    if ($i == 0) {
                        $query .= '(c.branch_office_id = '.$branch_offices[$i];
                    } else {
                        $query .= ' OR c.branch_office_id = '.$branch_offices[$i];
                    }
                }

                $query .= ')';

                $dtes = Dte::from('dtes as c')
                        ->selectRaw('users.rut as rut, users.names as names, c.dte_id as dte_id, branch_offices.branch_office as branch_office, c.folio as folio, c.dte_version_id as dte_version_id, c.amount as amount, c.dte_type_id as dte_type_id, c.created_at as created_at, c.status_id as status_id, statuses.status as status')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->whereRaw($query)
                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                        ->orderBy('c.status_id', 'DESC')
                        ->paginate(10);
            } else {
                $dtes = Dte::from('dtes as c')
                        ->where('c.dte_type_id', 39)
                        ->where('c.dte_version_id', 1)
                        ->where('c.rut', '<>', '66666666-6')
                        ->where('c.status_id', '<>', 18)
                        ->where('c.status_id', '<>', 19)
                        ->selectRaw('users.rut as rut, users.names as names, c.dte_id as dte_id, branch_offices.branch_office as branch_office, c.folio as folio, c.dte_version_id as dte_version_id, c.amount as amount, c.dte_type_id as dte_type_id, c.created_at as created_at, c.status_id as status_id, statuses.status as status')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                        ->orderBy('c.status_id', 'DESC')
                        ->paginate(10);
            }
        } else {
            $query = '';

            if ($branch_office_id != 'null') {
                $query .= 'c.branch_office_id = '.$branch_office_id;
            }

            if ($rut != 'null') {
                if ($branch_office_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.rut = "'.$rut.'"';
            }

            if ($status_id != 'null') {
                if ($branch_office_id != 'null' || $rut != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.status_id = '.$status_id;
            }

            if ($supervisor_id != 'null') {
                if ($branch_office_id != 'null' || $rut != 'null' || $status_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'branch_offices.supervisor_id = "'.$supervisor_id.'"';
            }

            $dtes = Dte::from('dtes as c')
                        ->selectRaw('users.rut as rut, users.names as names, c.dte_id as dte_id, branch_offices.branch_office as branch_office, c.folio as folio, c.dte_version_id as dte_version_id, c.amount as amount, c.dte_type_id as dte_type_id, c.created_at as created_at, c.status_id as status_id, statuses.status as status')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->whereRaw($query)
                        ->where('c.rut', '<>', '66666666-6')
                        ->where('dte_type_id', 39)
                        ->where('c.status_id', '<>', 18)
                        ->where('c.status_id', '<>', 19)
                        ->where('dte_version_id', 1)
                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                        ->orderBy('c.created_at', 'DESC')
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
        if ($request->special == 1) {
            // Se guarda los datos de la boleta.
            $dte = new Dte;
            $dte->rut = $request->rut;
            $dte->folio = $request->folio;
            $dte->branch_office_id = $request->branch_office_id;
            $dte->dte_type_id = 39;
            $dte->dte_version_id = 1;
            $dte->amount = $request->amount;
            $dte->expense_type_id = 0;
            $dte->temporal_code = $request->temporal_code;
            $dte->status_id = 17;
            $dte->created_at = $request->created_at;
            $dte->save();
        }

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
