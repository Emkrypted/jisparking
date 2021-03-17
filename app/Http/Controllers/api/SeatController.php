<?php

namespace App\Http\Controllers\api;

use App\Seat;
use App\Eerr;
use App\Remuneration;
use App\Dte;
use App\ExpenseType;
use App\BranchOffice;
use App\Capitulation;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeatController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        if($month == 1)
        {
            $days = '31';
        }
        if($month == 2)
        {
            $days = '28';
        }
        if($month == 3)
        {
            $days = '31';
        }
        if($month == 4)
        {
            $days = '30';
        }
        if($month == 5)
        {
            $days = '31';
        }
        if($month == 6)
        {
            $days = '30';
        }
        if($month == 7)
        {
            $days = '31';
        }
        if($month == 8)
        {
            $days = '31';
        }
        if($month == 9)
        {
            $days = '30';
        }
        if($month == 10)
        {
            $days = '31';
        }
        if($month == 11)
        {
            $days = '30';
        }
        if($month == 12)
        {
            $days = '31';
        }

        if($month < 10) {
            $month = '0'.$month;
        }
        $period = $year .'-'. $month;
        Seat::where('period', $period)->delete();
        Eerr::where('period', $period)->delete();

        $since = $year .'-'. $month .'-'. '01';
        $until = $year .'-'. $month .'-'. $days;
        $data = array(
            "periodo" => null, 
            "fecha_desde" => $since,
            "fecha_hasta" => $until,
            "glosa" => '',
            "operacion" => null,
            "cuenta" => null,
            "debe" => null,
            "debe_desde" => null,
            "debe_hasta" => null,
            "haber" => null,
            "haber_desde" => null,
            "haber_hasta" => null
        );           

        $data_string = json_encode($data);  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://libredte.cl/api/lce/lce_asientos/buscar/76063822");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 

        $header = array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic '. base64_encode("rcabezas:Jisparking2018"),
            'Content-Length: ' . strlen($data_string)
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
        curl_close ($ch);
        $results = json_decode($remote_server_output, true);
        for($i = 0; $i < count($results); $i++)
        {
            for($j = 0; $j < count($results[$i]['detalle']); $j++)
            {
                $description = explode('_', $results[$i]['glosa']);
                $detail = $results[$i]['glosa'];
                if(isset($description[4]))
                {
                    if((strpos($detail, 'Factura') !== false) || (strpos($detail, 'FacturaCompra') !== false) || (strpos($detail, 'BoletaElectronica') !== false) || (strpos($detail, 'NotaCredito') !== false) || (strpos($detail, 'NotaCreditoCompra') !== false) || (strpos($detail, 'NotaDebitoCompra') !== false) || (strpos($detail, 'ExentaCompra') !== false))
                    {
                        $dte = Dte::find($description[4]);
                        $dte_qty = Dte::where('dte_id', $description[4])->count();
                        $capitulation_qty = 0;
                    }
                        
                    if(strpos($detail, 'GastoRendido') !== false || strpos($detail, 'FondoRendido') !== false)
                    {
                        $capitulation = Capitulation::find($description[4]);
                        $capitulation_qty = Capitulation::where('capitulation_id', $description[4])->count();
                        $dte_qty = 0;
                    }
                }
                else
                {
                    $dte_qty = 0;
                    $capitulation_qty = 0;
                }

                if($dte_qty > 0 || $capitulation_qty > 0)
                {
                    if($dte_qty > 0)
                    {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                    }

                    if($capitulation_qty > 0)
                    {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                    }

                    $seat_qty = Seat::where('seat_number_id', $results[$i]['asiento'])->where('period', $results[$i]['id'])->where('description', $results[$i]['glosa'])->where('branch_office_id', $branch_office->branch_office_id)->count();

                    if($seat_qty == 0)
                    {
                        $eerr = new Eerr;
                        $period = explode('-', $results[$i]['fecha']);
                        $seat = new Seat;
                        $seat->location_id = $description[4];
                        $seat->seat_number_id = $results[$i]['asiento'];
                        $seat->branch_office_id = $branch_office->branch_office_id;
                        $eerr->branch_office_id = $branch_office->branch_office_id;
                        $eerr->seat_id = $results[$i]['asiento'];
                        $seat->period = $period[0].'-'.$period[1];
                        $eerr->period = $period[0].'-'.$period[1];
                        $seat->description = $results[$i]['glosa'];
                        $expense_type = ExpenseType::where('accounting_account', $results[$i]['detalle'][$j]['cuenta_codigo'])->first();
                        $expense_type_qty = ExpenseType::where('accounting_account', $results[$i]['detalle'][$j]['cuenta_codigo'])->count();
                        $seat->accounting_account = $results[$i]['detalle'][$j]['cuenta_codigo'];
                        $eerr->accounting_account = $results[$i]['detalle'][$j]['cuenta_codigo'];
                        if($results[$i]['detalle'][$j]['debe'] != '')
                        {
                            $seat->seat_type_id = 1;
                            $seat->amount = $results[$i]['detalle'][$j]['debe'];
   
                            if($expense_type_qty > 0)
                            {
                                if(strpos($detail, 'NotaCredito') !== false && $dte->dte_version_id == 1)
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['debe'] * -1;
                                }
                                else if(strpos($detail, 'NotaCreditoCompra') !== false && $dte->dte_version_id == 2)
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['debe'] * $expense_type->positive_negative_id * -1;
                                }
                                else
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['debe'] * $expense_type->positive_negative_id;
                                }
                            }
                            else
                            {
                                $results[$i]['detalle'][$j]['cuenta_codigo'].'<br>';
                            }
                                        
                        }
                        else
                        {
                            $seat->seat_type_id = 2;
                            $seat->amount = $results[$i]['detalle'][$j]['haber'];
                            if($expense_type_qty > 0)
                            {
                                if(strpos($detail, 'NotaCredito') !== false && $dte->dte_version_id == 1)
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['haber'] * -1;
                                }
                                else if(strpos($detail, 'NotaCreditoCompra') !== false && $dte->dte_version_id == 2)
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['haber'] * $expense_type->positive_negative_id * -1;
                                }
                                else
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['haber'] * $expense_type->positive_negative_id;
                                }
                            }
                            else
                            {
                                echo $results[$i]['detalle'][$j]['cuenta_codigo'].'<br>';
                            }
                        }
                        if($results[$i]['detalle'][$j]['cuenta_glosa'] != 'Banco')
                        {
                            $eerr->save();
                        }
                        $seat->created_at = $results[$i]['fecha'];
                        echo 1;
                        $seat->save();
                    }
                }
                else
                {
                    $branch_office = BranchOffice::where('branch_office', $description[0])->first();
                    $seat_qty = Seat::where('seat_number_id', $results[$i]['asiento'])->where('period', $results[$i]['id'])->where('description', $results[$i]['glosa'])->where('branch_office_id', $branch_office->branch_office_id)->count();

                    if($seat_qty == 0)
                    {
                        $eerr = new Eerr;
                        $period = explode('-', $results[$i]['fecha']);
                        $seat = new Seat;
                        $seat->location_id = 0;
                        $seat->seat_number_id = $results[$i]['asiento'];
                        $seat->branch_office_id = $branch_office->branch_office_id;
                        $eerr->branch_office_id = $branch_office->branch_office_id;
                        $eerr->seat_id = $results[$i]['asiento'];
                        $seat->period = $period[0].'-'.$period[1];
                        $eerr->period = $period[0].'-'.$period[1];
                        $seat->description = $results[$i]['glosa'];
                        $expense_type = ExpenseType::where('accounting_account', $results[$i]['detalle'][$j]['cuenta_codigo'])->first();
                        $seat->accounting_account = $results[$i]['detalle'][$j]['cuenta_codigo'];
                        $eerr->accounting_account = $results[$i]['detalle'][$j]['cuenta_codigo'];
                        if($results[$i]['detalle'][$j]['debe'] != '')
                        {
                            $seat->seat_type_id = 1;
                            $seat->amount = $results[$i]['detalle'][$j]['debe'];
                            if(isset($expense_type->positive_negative_id))
                            {
                                if(strpos($detail, 'NotaCredito') !== false && $dte->dte_version_id == 1)
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['debe'] * -1;
                                }
                                else if(strpos($detail, 'NotaCreditoCompra') !== false && $dte->dte_version_id == 2)
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['debe'] * $expense_type->positive_negative_id * $expense_type->positive_negative_id * -1;
                                }
                                else
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['debe'] * $expense_type->positive_negative_id;
                                }
                            }
                            else
                            {
                                echo $results[$i]['detalle'][$j]['cuenta_codigo'].'<br>';
                            }
                                        
                        }
                        else
                        {
                            $seat->seat_type_id = 2;
                            $seat->amount = $results[$i]['detalle'][$j]['haber'];
                            if(isset($expense_type->positive_negative_id))
                            {
                                if(strpos($detail, 'NotaCredito') !== false && $dte->dte_version_id == 1)
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['haber'] * -1;
                                }
                                else if(strpos($detail, 'NotaCreditoCompra') !== false && $dte->dte_version_id == 2)
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['haber'] * $expense_type->positive_negative_id * -1;
                                }
                                else
                                {
                                    $eerr->amount = $results[$i]['detalle'][$j]['haber'] * $expense_type->positive_negative_id;
                                }
                                    
                            }
                            else
                            {
                                echo $results[$i]['detalle'][$j]['cuenta_codigo'].'<br>';
                            }
                        }
                        if($results[$i]['detalle'][$j]['cuenta_glosa'] != 'Banco')
                        {
                            $eerr->save();
                        }

                        $seat->created_at = $results[$i]['fecha'];
                        $seat->save();
                    }
                }
            }
        }

        $remunerations = Remuneration::where('period', $period)->get();
        foreach ($remunerations as $remuneration) {
            $eerr = new Eerr;
            $eerr->branch_office_id = $remuneration->branch_office_id;
            $eerr->accounting_account = $remuneration->accounting_account;
            $eerr->amount = $remuneration->amount;
            $eerr->period = $remuneration->period;
            $eerr->save();
        }
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
