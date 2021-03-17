<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Deposit;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client;

class ElectronicDepositController extends ApiResponseController
{
    public function __construct(Request $request)
    {
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
        $branch_office_id = $request->segment(4);
        $status_id = $request->segment(5);
        $since = $request->segment(6);
        $until = $request->segment(7);
        if (($branch_office_id == 'null' && $status_id == 'null' && $since == 'null' && $until == 'null')
        || ($branch_office_id == '' && $status_id == '' && $since == '' && $until == '')
        ) {
            if ($this->user->rol_id != 1) {
                $branch_offices = $this->branch_offices;
                $query = '';
                for ($i = 0; $i < count($branch_offices); $i++) {
                    if ($i == 0) {
                        $query .= '(c.branch_office_id = '.$branch_offices[$i];
                    } else {
                        $query .= ' OR c.branch_office_id = '.$branch_offices[$i];
                    }
                }

                $query .= ')';

                $deposits = Deposit::from('deposits as c')
                                ->whereRaw($query)
                                ->selectRaw('c.collection_amount as collection_amount, c.deposit_id as deposit_id, branch_offices.branch_office as branch_office, c.deposit_amount as deposit_amount, statuses.status as status, statuses.status_id as status_id, c.collection_date as collection_date')
                                ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                                ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                                ->orderBy('c.collection_date', 'DESC')
                                ->paginate(10);
            } else {
                $deposits = Deposit::from('deposits as c')
                                ->selectRaw('c.collection_amount as collection_amount, c.deposit_id as deposit_id, branch_offices.branch_office as branch_office, c.deposit_amount as deposit_amount, statuses.status as status, statuses.status_id as status_id, c.collection_date as collection_date')
                                ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                                ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                                ->where('c.status_id', '<>', 14)
                                ->where('c.status_id', '=', 11)
                                ->orderBy('c.status_id', 'DESC')
                                ->orderBy('c.deposit_id', 'DESC')
                                ->paginate(10);
            }
        } else {
            $query = '';

            if ($branch_office_id != 'null') {
                $query .= 'c.branch_office_id = '.$branch_office_id;
            }

            if ($status_id != 'null') {
                if ($branch_office_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.status_id = '.$status_id;
            }

            if ($since != 'null') {
                if ($branch_office_id != 'null' || $status_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= '(c.collection_date >= "'.$since.' 00:00:00")';
            }

            if ($until != 'null') {
                if ($branch_office_id != 'null' || $status_id != 'null' || $since != 'null') {
                    $query .= ' AND ';
                }

                $query .= '(c.collection_date <= "'.$until.' 23:59:59")';
            }

            $deposits = Deposit::from('deposits as c')
                                ->whereRaw($query)
                                ->selectRaw('c.collection_amount as collection_amount, c.deposit_id as deposit_id, branch_offices.branch_office as branch_office, c.deposit_amount as deposit_amount, statuses.status as status, statuses.status_id as status_id, c.collection_date as collection_date')
                                ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                                ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                                ->orderBy('c.status_id', 'DESC')
                                ->orderBy('c.deposit_id', 'DESC')
                                ->paginate(10);
        }

        return $this->successResponse($deposits);
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
        $fileName = time().'_'.'deposito'.'_'.$request->branch_office_id.'_'.$request->collection_date.'_'.$request->created_at.'.'.$request->file->getClientOriginalExtension();
        $deposit = new Deposit;
        $deposit->branch_office_id = $request->branch_office_id;
        $deposit->status_id = $request->status_id;
        $deposit->deposit_type_id = 2;
        $deposit->deposit_amount = $request->deposit_amount;
        $deposit->deposit_number = $request->deposit_number;
        $deposit->collection_amount = $request->collection_amount;
        $deposit->collection_date = $request->collection_date;
        $deposit->created_at = $request->created_at;
        $deposit->status_id = 11;
        $deposit->support = $fileName;
        if ($deposit->save()) {
            Storage::disk('dropbox')->putFileAs(
                'deposits/',
                $request->file,
                $fileName
            );
        }

        return $this->successResponse($deposit);
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
        $deposit = Deposit::find($id);

        return $this->successResponse($deposit);
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
        $deposit = Deposit::find($id);
        if ($request->branch_office_id != '') {
            $deposit->branch_office_id = $request->branch_office_id;
        }
        if ($request->status_id != '') {
            $deposit->status_id = $request->status_id;
        }
        if ($request->branch_office_id != '') {
            $deposit->branch_office_id = $request->branch_office_id;
        }
        if ($request->description != '') {
            $deposit->description = $request->description;
        }
        if ($request->deposit_amount != '') {
            $deposit->deposit_amount = $request->deposit_amount;
        }
        if ($request->deposit_number != '') {
            $deposit->deposit_number = $request->deposit_number;
        }
        if ($request->collection_amount != '') {
            $deposit->collection_amount = $request->collection_amount;
        }
        if ($request->collection_date != '') {
            $deposit->collection_date = $request->collection_date;
        }
        if ($request->created_at != '') {
            $deposit->created_at = $request->created_at;
        }
        if ($request->status_id != '') {
            $deposit->status_id = $request->status_id;
        }
        if ($request->collection_date != '') {
            $deposit->collection_date = $request->collection_date;
        }
        if ($request->file('file') != '') {
            $fileName = time().'_'.'deposito'.'_'.$request->branch_office_id.'_'.$request->collection_date.'_'.$request->created_at.'.'.$request->file->getClientOriginalExtension();
            $oldFileName = $deposit->support;
            $deposit->support = $fileName;
            $this->dropbox->delete('deposits/'.$oldFileName);
            Storage::disk('dropbox')->putFileAs(
                    'deposits/',
                    $request->file,
                    $fileName
            );
        }
        $deposit->save();

        return $this->successResponse($deposit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deposit = Deposit::find($id);
        $fileName = $deposit->support;
        if ($deposit->delete()) {
            $this->dropbox->delete('deposits/'.$fileName);
        }

        return $this->successResponse($deposit);
    }

    /**
     * Get the image of the support from Dropbox.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function support(Request $request)
    {
        $deposit_id = $request->segment(4);
        $deposit = Deposit::where('deposit_id', $deposit_id)->first();
        $dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
        $filename = $deposit->support;
        $image = $dropbox->getTemporaryLink('deposits/'.$filename);

        return $this->successResponse($image);
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
        $deposit_amount = Deposit::where('deposit_type_id', 2)->where('branch_office_id', $branch_office_id)->where('collection_date', '>=', $collection_date.' 00:00:00')->where('collection_date', '<=', $collection_date.' 23:59:59')->sum('deposit_amount');

        return $this->successResponse($deposit_amount);
    }
}
