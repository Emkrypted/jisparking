<?php

namespace App\Http\Controllers;

use App\Dte;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client;

class TicketController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accountability()
    {
        $dtes = Dte::where('accountability_status_id', 0)->where('rut', '66666666-6')->get();

        foreach($dtes as $dte) {
            $id = $dte->dte_id;
            $date = explode(' ', $dte->created_at);
            $date = explode('-', $date[0]);
            $period = $date[1].'-'.$date[0];
            $updated_dte = $Dte::find($id);
            $updated_dte->accountability_status_id = 1;
            $updated_dte->period
            if($updated_dte->save()) {
                $branch_office = BranchOffice::find($updated_dte->branch_office_id);
                $utf8_date = explode("-", $updated_dte->period);
                $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                $date = $dte->period;
                $date = $date .'-01';
                $amount = $updated_dte->amount;
                $expense_type = ExpenseType::find();
                $message = $branch_office->branch_office.'_441000102_'.$utf8_date.'_BoletaElectronica_'.$dte->dte_id;
                $url = 'https://libredte.cl';
                $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                $creator = '76063822-6';
                $data = [
                            'fecha' => $date,
                            'glosa' => $message,
                            'detalle' => [
                                'debe' => [
                                    111000102 => $amount, // banco total
                                ],
                                'haber' => [
                                    $expense_type->accounting_account => round($amount/1.19),
                                    221000226 => round($amount - ($amount/1.19)),
                                ],
                            ],
                            'operacion' => 'I',
                            'documentos' => ['emitidos'=>[['dte'=>$dte->dte_type_id, 'folio'=>$dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                        ];
                $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                if ($seat['status']['code']!=200) {
                    die('Error al crear el asiento contable: '.$seat['body']."\n");
                }
            }
        }
    }
}
