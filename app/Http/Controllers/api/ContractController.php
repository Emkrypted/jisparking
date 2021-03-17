<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Contract;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client;

class ContractController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->user = User::where('api_token', $request->api_token)->first();
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
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
        $branch_office_id = $request->segment(4);

        if (($branch_office_id == 'null')
        || ($branch_office_id == '')
        ) {
            $contracts = Contract::orderBy('created_at', 'desc')
                        ->with('branch_office')
                        ->with('user')
                        ->paginate(10);
        } else {
            $query = '';

            if ($branch_office_id != 'null') {
                $query .= 'branch_office_id = '.$branch_office_id;
            }

            $contracts = Contract::whereRaw($query)
                        ->orderBy('created_at', 'desc')
                        ->with('branch_office')
                        ->with('user')
                        ->paginate(10);
        }

        return $this->successResponse($contracts);
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
        $supplier_qty = Supplier::where('rut', $request->rut)->count();
        $user_qty = User::where('rut', $request->rut)->count();

        if ($supplier_qty == 0 && $user_qty == 0) {
            $supplier = new Supplier;
            $supplier->rut = $request->rut;
            $supplier->payment_commitment = 30;
            $supplier->save();

            $user = new User;
            $user->rut = $request->rut;
            $user->names = $request->boss;
            $user->rol_id = 18;
            $user->email = '';
            $user->save();
        }

        $fileName = time().'_'.'iva'.'.'.$request->file->getClientOriginalExtension();
        $date = explode('-', $request->date);
        $contract = new Contract;
        $contract->rut = $request->rut;
        $contract->branch_office_id = $request->branch_office_id;
        $contract->duration = $request->duration;
        $contract->uf = $request->uf;
        $contract->address = $request->address;
        $contract->renewal_date = $request->renewal_date;
        $contract->support = $fileName;
        $contract->created_at = $request->created_at;
        if ($contract->save()) {
            Storage::disk('dropbox')->putFileAs(
                'company_contracts/',
                $request->file,
                $fileName
            );
        }
        $contract->save();

        return $this->successResponse($contract);
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
        $contract = Contract::find($id);
        if ($contract->delete()) {
            $this->dropbox->delete('company_contracts/'.$contract->support);
        }

        return $this->successResponse($contract);
    }
}
