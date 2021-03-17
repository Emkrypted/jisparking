<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Cashier;
use App\Collection;
use App\Deposit;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;
use Spatie\Dropbox\Client;

class CollectionController extends ApiResponseController
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
        $status_id = $request->segment(6);
        $supervisor_id = $request->segment(7);
        if (($branch_office_id == 'null' && $created_at == 'null' && $status_id == 'null' && $supervisor_id == 'null')
        || ($branch_office_id == '' && $created_at == '' && $status_id == '' && $supervisor_id == '')
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

                $collections = Collection::from('collections as c')
                                        ->whereRaw($query)
                                        ->selectRaw('c.z_inform_number as z_inform_number, statuses.status_id as status_id, c.collection_id as collection_id, branch_offices.branch_office as branch_office, cashiers.cashier as cashier, c.gross_amount as gross_amount, c.net_amount as net_amount, c.ticket_number as ticket_number, statuses.status as status, c.created_at as created_at')
                                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                                        ->leftJoin('cashiers', 'cashiers.cashier_id', '=', 'c.cashier_id')
                                        ->where('c.collection_type_id', '<>', 2)
                                        ->orderBy('c.created_at', 'DESC')
                                        ->paginate(10);
            } else {
                $collections = Collection::from('collections as c')
                                        ->selectRaw('c.z_inform_number as z_inform_number, statuses.status_id as status_id, c.collection_id as collection_id, branch_offices.branch_office as branch_office, cashiers.cashier as cashier, c.gross_amount as gross_amount, c.net_amount as net_amount, c.ticket_number as ticket_number, statuses.status as status, c.created_at as created_at')
                                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                                        ->leftJoin('cashiers', 'cashiers.cashier_id', '=', 'c.cashier_id')
                                        ->where('c.collection_type_id', '<>', 2)
                                        ->where('c.status_id', '<>', null)
                                        ->orderBy('c.status_id', 'ASC')
                                        ->paginate(10);
            }
        } else {
            $query = '';

            if ($branch_office_id != 'null') {
                $query .= 'c.branch_office_id = '.$branch_office_id;
            }
            if ($created_at != 'null') {
                if ($branch_office_id != 'null') {
                    $query .= ' AND ';
                }
                $query .= '(c.created_at >= "'.$created_at.' 00:00:00" AND c.created_at <= "'.$created_at.' 23:59:59")';
            }
            if ($status_id != 'null') {
                if ($created_at != 'null' || $branch_office_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.status_id = '.$status_id;
            }
            if ($supervisor_id != 'null') {
                if ($created_at != 'null' || $branch_office_id != 'null' || $status_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'branch_offices.supervisor_id = "'.$supervisor_id.'"';
            }

            $collections = Collection::from('collections as c')
                     ->selectRaw('c.z_inform_number as z_inform_number, statuses.status_id as status_id, c.collection_id as collection_id, branch_offices.branch_office as branch_office, cashiers.cashier as cashier, c.gross_amount as gross_amount, c.net_amount as net_amount, c.ticket_number as ticket_number, statuses.status as status, c.created_at as created_at')
                     ->whereRaw($query)
                     ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                     ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                     ->leftJoin('cashiers', 'cashiers.cashier_id', '=', 'c.cashier_id')
                     ->where('c.collection_type_id', '<>', 2)
                     ->orderBy('c.status_id', 'ASC')
                     ->orderBy('c.created_at', 'DESC')
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
            $branch_office_id = $request->branch_office_id;
            $branch_office = BranchOffice::find($branch_office_id);
            $supervisor_rut = $branch_office->supervisor_id;
            $user = User::where('rut', $supervisor_rut)->first();
            $cashier_id = $request->cashier_id;
            $cashier = Cashier::find($cashier_id);
            $collection = new Collection;
            $collection->branch_office_id = $request->branch_office_id;
            $collection->cashier_id = $request->cashier_id;

            $collection->gross_amount = $request->gross_amount;
            $gross_amount = $request->gross_amount;
            $net_amount = round($gross_amount / 1.19);
            $collection->net_amount = $net_amount;

            $collection->card_gross_amount = $request->card_gross_amount;
            $card_gross_amount = $request->card_gross_amount;
            $card_net_amount = round($card_gross_amount / 1.19);
            $collection->card_net_amount = $card_net_amount;

            $collection->status_id = 7;
            $collection->collection_type_id = 2;
            if ($collection->save()) {
                $to_name = $user->names;
                $to_email = $user->email;
                $data = ['supervisor'=> $user->names, 'branch_office'=> $branch_office->branch_office, 'cashier'=> $cashier->cashier, 'day'=> date('d/m/Y')];
                Mail::send(['html' => 'emails.collection'], $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->subject('Nueva Recaudación');
                    $message->from('recaudacion@jisparking.com', 'JIS Parking SpA');
                });
            }

            return $this->successResponse($collection);
        } else {
            $fileName = time().'_'.'recaudacion'.'_'.$request->branch_office_id.'_'.$request->cashier_id.'_'.$request->created_at.'.'.$request->file->getClientOriginalExtension();
            $collection = new Collection;
            $collection->branch_office_id = $request->branch_office_id;
            $collection->cashier_id = $request->cashier_id;

            $collection->gross_amount = $request->gross_amount;
            $gross_amount = $request->gross_amount;
            $net_amount = round($gross_amount / 1.19);
            $collection->net_amount = $net_amount;

            $collection->card_gross_amount = $request->card_gross_amount;
            $card_gross_amount = $request->card_gross_amount;
            $card_net_amount = round($card_gross_amount / 1.19);
            $collection->card_net_amount = $card_net_amount;

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
            $collection->status_id = 4;
            $collection->collection_type_id = 1;
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
        $collection = Collection::find($id);

        if ($request->branch_office_id != '') {
            $collection->branch_office_id = $request->branch_office_id;
        }
        if ($request->cashier_id != '') {
            $collection->cashier_id = $request->cashier_id;
        }
        if ($request->gross_amount != '') {
            $old_gross_amount = $collection->gross_amount;
            $collection->gross_amount = $request->gross_amount;
            $gross_amount = $request->gross_amount;
            $net_amount = round($gross_amount / 1.19);
            $collection->net_amount = $net_amount;
        }
        if ($request->card_gross_amount != '') {
            $old_card_gross_amount = $collection->card_gross_amount;
            $collection->card_gross_amount = $request->card_gross_amount;
            $card_gross_amount = $request->card_gross_amount;
            $card_net_amount = round($card_gross_amount / 1.19);
            $collection->card_net_amount = $card_net_amount;
        }
        if ($request->start_ticket != '') {
            $collection->start_ticket = $request->start_ticket;
        }
        if ($request->end_ticket != '') {
            $collection->end_ticket = $request->end_ticket;
        }
        if ($request->ticket_number != '') {
            $collection->ticket_number = $request->ticket_number;
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

        if ($request->branch_office_id != '' && $request->created_at != '') {
            $deposit_qty = Deposit::where('branch_office_id', $collection->branch_office_id)
                        ->where('collection_date', $collection->created_at)
                        ->count();

            if ($deposit_qty > 0) {
                $deposit = Deposit::where('branch_office_id', $collection->branch_office_id)
                        ->where('collection_date', $collection->created_at)
                        ->first();
                $collection_amount = $deposit->collection_amount;
                $collection_amount = $collection_amount - $old_gross_amount + $request->collection_amount;
                $deposit->collection_amount = $collection_amount;
                $deposit->save();
            }
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
        $collection = Collection::where('collection_type_id', 1)->where('branch_office_id', $branch_office_id)->where('cashier_id', $cashier_id)->orderBy('created_at', 'desc')->first();

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
}
