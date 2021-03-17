<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\SentTransbankCollection;
use App\Transbank;
use DB;
use Illuminate\Http\Request;

class TransbankCollectionAccountingController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branch_office_id = $request->segment(4);
        $period = $request->segment(5);
        $transbank_collections = null;
        $date = explode('-', $period);
        $sent_transbank_collection_qty = 1;
        if (isset($date[0]) && isset($date[1])) {
            $sent_transbank_collection_qty = SentTransbankCollection::where('branch_office_id', $branch_office_id)
                                                    ->where('year', $date[0])
                                                    ->where('month', $date[1])
                                                    ->count();
        }
        if ($sent_transbank_collection_qty == 0) {
            if ($branch_office_id != '' && $period != '') {
                $transbank_collections = Transbank::from('transbank_collections as c')
                                    ->selectRaw('branch_offices.branch_office_id as branch_office_id, DATE(c.created_at) as collection_date, branch_offices.branch_office as branch_office_name, sum(c.amount) as amount')
                                    ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                                    ->where('c.branch_office_id', $branch_office_id)
                                    ->where(DB::raw("DATE_FORMAT(c.created_at,'%Y-%m')"), $period)
                                    ->groupBy(DB::raw("DATE_FORMAT(c.created_at,'%Y-%m')"))
                                    ->orderBy(DB::raw('DATE(c.created_at)'), 'DESC')
                                    ->paginate(100);
            }
        }

        return $this->successResponse($transbank_collections);
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
        $selected = $request->input('selected');
        $selected = explode(',', $selected);
        for ($i = 0; $i < count($selected); $i++) {
            $selected_detail = explode('_', $selected[$i]);
            $branch_office = BranchOffice::find($selected_detail[2]);
            $utf8_date = explode('-', $selected_detail[0]);
            $month = $utf8_date[1];
            $year = $utf8_date[0];
            $utf8_date = $utf8_date[2].'-'.$utf8_date[1].'-'.$utf8_date[0];
            $message = $branch_office->branch_office.'_441000101_'.$utf8_date.'_TransbankBoletaFiscal';
            if ($selected_detail[1] > 0) {
                $url = 'https://libredte.cl';
                $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                $creator = '76063822-6';
                $data = [
                    'fecha' => $selected_detail[0],
                    'glosa' => $message,
                    'detalle' => [
                        'debe' => [
                            111000102 => $selected_detail[1],
                        ],
                        'haber' => [
                            441000101 => round($selected_detail[1] / 1.19),
                            221000226 => round($selected_detail[1] - ($selected_detail[1] / 1.19)),
                        ],
                    ],
                    'operacion' => 'I',
                    'documentos' => ['emitidos'=>[['dte'=>'', 'folio'=> '']]],
                ];
                $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                if ($seat['status']['code'] != 200) {
                    exit('Error al crear el asiento contable: '.$seat['body']."\n");
                }
                $sent_transbank_collection = new SentTransbankCollection;
                $sent_transbank_collection->branch_office_id = $selected_detail[2];
                $sent_transbank_collection->month = $month;
                $sent_transbank_collection->year = $year;
                $sent_transbank_collection->save();
            }
        }

        return $this->successResponse($sent_transbank_collection);
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
        $collection_amount = Collection::where('branch_office_id', $branch_office_id)->where('status_id', 7)->where('created_at', '>=', $collection_date.' 00:00:00')->where('created_at', '<=', $collection_date.' 23:59:59')->sum('gross_amount');

        return $this->successResponse($collection_amount);
    }
}
