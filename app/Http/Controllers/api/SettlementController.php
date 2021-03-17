<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Cashier;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\Settlement;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;
use Spatie\Dropbox\Client;

class SettlementController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();

        $this->user = User::where('api_token', $request->api_token)->first();

        if ($this->user->rol_id == 4) {
            $this->branch_offices = BranchOffice::where('supervisor_id', $this->user->rut)->pluck('branch_office_id')->toArray();
        } else {
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
        $rut = $request->segment(4);
        $father_lastname = $request->segment(5);
        $branch_office_id = $request->segment(6);
        if (($rut == 'null' && $father_lastname == 'null' && $branch_office_id == 'null')
        || ($rut == '' && $father_lastname == '' && $branch_office_id == '')
        ) {
            if ($this->user->rol_id != 1) {
                $branch_offices = $this->branch_offices;
                $query = '';
                for ($i = 0; $i < count($branch_offices); $i++) {
                    if ($i == 0) {
                        $query .= 'employees.branch_office_id = '.$branch_offices[$i];
                    } else {
                        $query .= ' OR employees.branch_office_id = '.$branch_offices[$i];
                    }
                }

                $settlements = Settlement::from('settlements as c')
                        ->selectRaw('c.settlement_id as settlement_id, users.rut as rut, users.names as names, c.month as month, c.year as year')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->leftJoin('employees', 'employees.rut', '=', 'c.rut')
                        ->whereRaw($query)
                        ->orderBy('c.year', 'DESC')
                        ->paginate(10);
            } else {
                $settlements = Settlement::from('settlements as c')
                        ->selectRaw('c.settlement_id as settlement_id, users.rut as rut, users.names as names, c.month as month, c.year as year')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->orderBy('c.year', 'DESC')
                        ->paginate(10);
            }
        } else {
            $query = '';

            if ($rut != 'null') {
                $query .= 'c.rut = '.$rut;
            }

            if ($father_lastname != 'null') {
                if ($rut != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'employees.father_lastname = "'.$father_lastname.'"';
            }

            if ($branch_office_id != 'null') {
                if ($rut != 'null' || $father_lastname != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'employees.branch_office_id = '.$branch_office_id;
            }

            $settlements = Settlement::from('settlements as c')
                        ->selectRaw('c.settlement_id as settlement_id, users.rut as rut, users.names as names, c.month as month, c.year as year')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->leftJoin('employees', 'employees.rut', '=', 'c.rut')
                        ->whereRaw($query)
                        ->orderBy('c.year', 'DESC')
                        ->paginate(10);
        }

        return $this->successResponse($settlements);
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
