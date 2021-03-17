<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Collection;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client;

class ElectronicCollectionController extends ApiResponseController
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
        $branch_office_id = $request->segment(4);
        $created_at = $request->segment(5);
        if (($branch_office_id == 'null' && $created_at == 'null')
        || ($branch_office_id == '' && $created_at == '')
        ) {
            if ($this->user->rol_id != 1) {
                $branch_offices = $this->branch_offices;
                $query = '';
                for ($i = 0; $i < count($branch_offices); $i++) {
                    if ($i == 0) {
                        $query .= 'branch_office_id = '.$branch_offices[$i];
                    } else {
                        $query .= ' OR branch_office_id = '.$branch_offices[$i];
                    }
                }
                $collections = Collection::whereRaw($query)
                                        ->where('collection_type_id', 2)
                                        ->with('branch_office')
                                        ->with('cashier')
                                        ->with('status')
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(10);
            } else {
                $collections = Collection::where('collection_type_id', 2)
                                        ->with('branch_office')
                                        ->with('cashier')
                                        ->with('status')
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(10);
            }
        } else {
            $query = '';

            if ($branch_office_id != 'null') {
                $query .= 'branch_office_id = '.$branch_office_id;
            }

            if ($created_at != 'null') {
                if ($branch_office_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= '(created_at >= "'.$created_at.' 00:00:00" AND created_at <= "'.$created_at.' 23:59:59")';
            }

            $collections = Collection::whereRaw($query)
                     ->where('collection_type_id', 2)
                     ->with('branch_office')
                     ->with('cashier')
                     ->with('status')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
        }

        return $this->successResponse($collections);
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
            $collection = new Collection;
            $collection->branch_office_id = $request->branch_office_id;
            $collection->cashier_id = $request->cashier_id;
            $collection->gross_amount = $request->gross_amount;
            $gross_amount = $request->gross_amount;
            $net_amount = $gross_amount - (($gross_amount * 19) / 100);
            $collection->net_amount = $net_amount;
            $collection->created_at = $request->created_at;
            $collection->status_id = 7;
            $collection->collection_type_id = 2;
            $collection->save();

            return $this->successResponse($collection);
        } else {
            $fileName = time().'_'.'recaudacion'.'_'.$request->branch_office_id.'_'.$request->cashier_id.'_'.$request->created_at.'.'.$request->file->getClientOriginalExtension();
            $collection = new Collection;
            $collection->branch_office_id = $request->branch_office_id;
            $collection->cashier_id = $request->cashier_id;
            $collection->gross_amount = $request->gross_amount;
            $gross_amount = $request->gross_amount;
            $net_amount = $gross_amount - (($gross_amount * 19) / 100);
            $collection->net_amount = $net_amount;
            $collection->start_ticket = $request->start_ticket;
            $collection->end_ticket = $request->end_ticket;
            $start_ticket = $request->start_ticket;
            $end_ticket = $request->end_ticket;
            $ticket_number = $end_ticket - $start_ticket + 1;
            $collection->ticket_number = $ticket_number;
            $collection->released_tickets = $request->released_tickets;
            $collection->z_inform_number = $request->z_inform_number;
            $collection->created_at = $request->created_at;
            $collection->support = $fileName;
            $collection->status_id = 7;
            $collection->collection_type_id = 2;
            if ($collection->save()) {
                Storage::disk('dropbox')->putFileAs(
                    'collections/',
                    $request->file,
                    $fileName
                );
            }

            return $this->successResponse($collection);
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
        $collection = Collection::find($id);

        return $this->successResponse($collection);
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
        if ($request->special == 1) {
            $collection = Collection::where('branch_office_id', $request->branch_office_id)
                                    ->where('cashier_id', $request->cashier_id)
                                    ->where('created_at', '>=', $request->created_at)
                                    ->where('created_at', '<=', $request->created_at)
                                    ->first();

            $collection_qty = Collection::where('branch_office_id', $request->branch_office_id)
                                    ->where('cashier_id', $request->cashier_id)
                                    ->where('created_at', '>=', $request->created_at)
                                    ->where('created_at', '<=', $request->created_at)
                                    ->count();
        } else {
            $collection = Collection::find($id);
        }

        if ($collection_qty > 0) {
            if ($request->branch_office_id != '') {
                $collection->branch_office_id = $request->branch_office_id;
            }
            if ($request->cashier_id != '') {
                $collection->cashier_id = $request->cashier_id;
            }
            if ($request->gross_amount != '') {
                $collection->gross_amount = $request->gross_amount;
                $gross_amount = $request->gross_amount;
                $net_amount = $gross_amount - (($gross_amount * 19) / 100);
                $collection->net_amount = $net_amount;
            }
            if ($request->start_ticket != '') {
                $collection->start_ticket = $request->start_ticket;
            }
            if ($request->end_ticket != '') {
                $collection->end_ticket = $request->end_ticket;
            }
            if ($request->ticket_number != '') {
                $ticket_number = $end_ticket - $start_ticket + 1;
                $collection->ticket_number = $ticket_number;
            }
            if ($request->released_tickets != '') {
                $collection->released_tickets = $request->released_tickets;
            }
            if ($request->z_inform_number != '') {
                $collection->z_inform_number = $request->z_inform_number;
            }
            if ($request->created_at != '') {
                $collection->created_at = $request->created_at;
            }
            if ($request->status_id != '') {
                $collection->status_id = $request->status_id;
            }
            if ($request->file('file') != '') {
                $fileName = time().'_'.'recaudacion'.'_'.$request->branch_office_id.'_'.$request->cashier_id.'_'.$request->created_at.'.'.$request->file->getClientOriginalExtension();
                $oldFileName = $collection->support;
                $collection->support = $fileName;
                $this->dropbox->delete('collections/'.$oldFileName);
                Storage::disk('dropbox')->putFileAs(
                    'collections/',
                    $request->file,
                    $fileName
                );
            }
            $collection->save();
        } else {
            $collection = new Collection;
            $collection->branch_office_id = $request->branch_office_id;
            $collection->cashier_id = $request->cashier_id;
            $collection->gross_amount = $request->gross_amount;
            $gross_amount = $request->gross_amount;
            $net_amount = $gross_amount - (($gross_amount * 19) / 100);
            $collection->net_amount = $net_amount;
            $collection->created_at = $request->created_at;
            $collection->status_id = 7;
            $collection->collection_type_id = 2;
            $collection->save();
        }

        return $this->successResponse($collection);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collection = Collection::find($id);
        $fileName = $collection->support;
        if ($collection->delete()) {
            $this->dropbox->delete('collections/'.$fileName);
        }

        return $this->successResponse($collection);
    }

    /**
     * Find a specific collection row.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        $branch_office_id = $request->segment(4);
        $cashier_id = $request->segment(5);
        $collection = Collection::where('branch_office_id', $branch_office_id)->where('cashier_id', $cashier_id)->orderBy('created_at', 'desc')->first();

        return $this->successResponse($collection);
    }

    /**
     * Get the image of the support from Dropbox.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function support(Request $request)
    {
        $collection_id = $request->segment(4);
        $collection = Collection::where('collection_id', $collection_id)->first();
        $dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
        $filename = $collection->support;
        $image = $dropbox->getTemporaryLink('collections/'.$filename);

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
        $collection_amount = Collection::where('branch_office_id', $branch_office_id)->where('created_at', '>=', $collection_date.' 00:00:00')->where('created_at', '<=', $collection_date.' 23:59:59')->sum('gross_amount');
        $card_collection_amount = Collection::where('branch_office_id', $branch_office_id)->where('created_at', '>=', $collection_date.' 00:00:00')->where('created_at', '<=', $collection_date.' 23:59:59')->sum('card_gross_amount');

        $total = $collection_amount + $card_collection_amount;

        return $this->successResponse($total);
    }
}
