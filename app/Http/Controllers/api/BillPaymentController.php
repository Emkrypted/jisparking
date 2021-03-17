<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Dte;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BillPaymentController extends ApiResponseController
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
        $supplier_dte_id = $request->segment(4);
        $folio = $request->segment(5);
        $rut = $request->segment(6);
        if (($supplier_dte_id == 'null' && $folio == 'null' && $rut == 'null')
        || ($supplier_dte_id == '' && $folio == '' && $rut == '')
        ) {
            $query = '(c.dte_type_id = 33 || c.dte_type_id = 39)';

            $dtes = Dte::from('dtes as c')
                                ->selectRaw('c.dte_id as dte_id, c.comment as comment, c.dte_id as dte_id, users.names as names, c.rut as rut, sum(c.amount) as amount, c.rut as rut, c.folio as folio, expense_types.expense_type as expense_type')
                                ->leftJoin('users', 'users.rut', '=', 'c.rut')
                                ->leftJoin('expense_types', 'expense_types.expense_type_id', '=', 'c.expense_type_id')
                                ->where('c.status_id', '18')
                                ->where('users.rol_id', '18')
                                ->where('c.dte_version_id', '2')
                                ->whereRaw($query)
                                ->groupBy('c.rut')
                                ->orderBy('users.names', 'DESC')
                                ->paginate(10);
        } else {
            $query = '';

            if ($supplier_dte_id != 'null') {
                $query .= 'c.rut = "'.$supplier_dte_id.'"';
            }

            if ($folio != 'null') {
                if ($supplier_dte_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.folio = '.$folio;
            }

            if ($rut != 'null') {
                if ($supplier_dte_id != 'null' || $folio != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.rut = "'.$rut.'"';
            }

            $dtes = Dte::whereRaw($query)
                    ->from('dtes as c')
                    ->selectRaw('c.dte_id as dte_id, c.comment as comment, c.dte_id as dte_id, users.names as names, c.rut as rut, sum(c.amount) as amount, c.rut as rut, c.folio as folio, expense_types.expense_type as expense_type')
                    ->leftJoin('users', 'users.rut', '=', 'c.rut')
                    ->leftJoin('expense_types', 'expense_types.expense_type_id', '=', 'c.expense_type_id')
                    ->where('c.status_id', '18')
                    ->where('users.rol_id', '18')
                    ->where('c.dte_version_id', '2')
                    ->groupBy('c.rut')
                     ->orderBy('users.names', 'DESC')
                    ->paginate(10);
        }

        return $this->successResponse($dtes);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $rut = $request->segment(4);
        $dtes = Dte::from('dtes as c')
                        ->selectRaw('c.dte_id as dte_id, c.comment as comment, c.dte_id as dte_id, users.names as names, c.rut as rut, c.amount as amount, c.rut as rut, c.folio as folio, expense_types.expense_type as expense_type, c.created_at as created_at')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->leftJoin('expense_types', 'expense_types.expense_type_id', '=', 'c.expense_type_id')
                        ->where('c.status_id', '18')
                        ->where('users.rol_id', '18')
                        ->where('c.dte_version_id', '2')
                        ->where('c.rut', $rut)
                        ->orderBy('c.created_at', 'DESC')
                        ->paginate(100);

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
        $date_now = date('d-m-Y');
        $date_past = strtotime('-30 day', strtotime($date_now));
        $date_past = date('d-m-Y', $date_past);
        $data = ['dte' => [33, 39, 56, 61], 'fecha_desde' => $date_past];
        $data_string = json_encode($data);
        // abrimos la sesión cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://libredte.cl/api/dte/dte_emitidos/buscar/76063822');
        // indicamos el tipo de petición: POST
        curl_setopt($ch, CURLOPT_POST, true);
        // definimos cada uno de los parámetros
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $header = [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic '.base64_encode('rcabezas:Jisparking2018'),
            'Content-Length: '.strlen($data_string),
        ];
        // pass header variable in curl method
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // recibimos la respuesta y la guardamos en una variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec($ch);
        // cerramos la sesión cURL
        curl_close($ch);
        $results = json_decode($remote_server_output, true);
        print_r($results);
        exit();
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
        $dte = Dte::find($id);

        return $this->successResponse($dte);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        $selected = $request->input('selected');
        $selected = explode(',', $selected);

        for ($i = 0; $i < count($selected); $i++) {
            $bill_payment = Dte::find($selected[$i]);
            if ($request->payment_type_id != '') {
                $bill_payment->payment_type_id = $request->payment_type_id;
            }
            if ($request->payment_date != '') {
                $bill_payment->payment_date = $request->payment_date;
            }
            $bill_payment->status_id = 19;
            $bill_payment->payment_comment = $request->payment_comment;
            $bill_payment->save();
        }

        return $this->successResponse($bill_payment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dte = Dte::find($id);
        $dte->delete();

        return $this->successResponse($dte);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        $url = 'https://libredte.cl';
        $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';

        // se obtiene los datos para la factura.
        $dte_type_id = $request->dte_type_id;
        $branch_office_id = $request->branch_office_id;
        $rut = $request->rut;
        $client = $request->client;
        $address = $request->address;
        $email = $request->email;
        $amount = $request->amount;
        $branch_office = BranchOffice::find($branch_office_id);
        $dte_code = $branch_office->dte_code;
        $description = '';

        // Se utiliza la API para generar el temporal de la boleta o factura.
        $dte = [
            'Encabezado' => [
                'IdDoc' => [
                    'TipoDTE' => $dte_type_id,
                ],
                'Emisor' => [
                    'RUTEmisor' => '76063822-6',
                    'CdgSIISucur' => $dte_code,
                ],
                'Receptor' => [
                    'RUTRecep' => $rut,
                    'RznSocRecep' => $client,
                    'DirRecep' =>  $address,
                    'Contacto' =>  $email,
                    'CorreoRecep' =>  $email,
                ],
            ],
            'Detalle' => [
                [
                    'NmbItem' => $description.'. Fecha: '.date('m').'-'.date('d'),
                    'QtyItem' => 1,
                    'PrcItem' => round($amount, 0),
                ],
            ],
        ];

        // crear cliente
        $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);

        // $LibreDTE->setSSL(false, false); ///< segundo parámetro =false desactiva verificación de SSL
        // crear DTE temporal
        $emitir = $LibreDTE->post('/dte/documentos/emitir', $dte);
        if ($emitir['status']['code'] != 200) {
            exit('Error al emitir DTE temporal: '.$emitir['body']."\n");
        }
        $temporal_code = serialize($emitir['body']);

        // Generamos la boleta o factura.
        $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);

        // crear DTE real
        $generar = $LibreDTE->post('/dte/documentos/generar?email=1', $emitir['body']);
        if ($generar['status']['code'] != 200) {
            exit('Error al generar DTE real: '.$generar['body']."\n");
        }

        // Se guarda los datos de la boleta o factura.
        $folio = $generar['body']['folio'];
        $dte = new Dte;
        $dte->rut = $rut;
        $dte->folio = $folio;
        $dte->branch_office_id = $branch_office_id;
        $dte->dte_type_id = $dte_type_id;
        $dte->dte_version_id = 1;
        $dte->amount = $request->amount;
        $dte->expense_type_id = $request->expense_type_id;
        $dte->temporal_code = $temporal_code;
        $dte->status_id = 6;
        $dte->save();

        // Si no existe el cliente lo creamos.
        $supplier_client_qty = Supplier::where('rut', $rut)->count();
        $user_client_qty = Supplier::where('rut', $rut)->count();
        if ($client_qty == 0 && $user_client_qty == 0) {
            $client = new Supplier;
            $client->rut = $rut;
            $client->save();
            $user = new User;
            $user->rut = $rut;
            $user->names = $client;
            $user->rol_id = 14;
            $user->email = $email;
            $user->save();
        }

        return $this->successResponse($dte);
    }

    /**
     * Impute the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function impute(Request $request)
    {
        $splits = $request->input('splits');
        $splits = json_decode($splits);
        if (count($splits) > 1) {
            for ($i = 0; $i < count($splits); $i++) {
                $id = $request->dte_id;
                $dte = Dte::find($id);
                if ($request->comment != '') {
                    $dte->comment = $request->comment;
                }
                $dte->period = $splits[$i]->split_period;
                $dte->amount = $splits[$i]->split_amount;
                $dte->expense_type_id = $request->expense_type_id;
                $dte->payment_date = $request->payment_date;
                $dte->payment_type_id = $request->payment_type_id;
                if ($dte->dte_version_id == 1) {
                    $dte->status_id = 17;
                } else {
                    $dte->status_id = 18;
                }
                $dte->save();

                $new_dte = new Dte;
                $new_dte->rut = $dte->rut;
                $new_dte->folio = $dte->folio;
                $new_dte->branch_office_id = $dte->branch_office_id;
                $new_dte->dte_type_id = $dte->dte_type_id;
                $new_dte->expense_type_id = $dte->expense_type_id;
                $new_dte->payment_type_id = $dte->payment_type_id;
                $new_dte->payment_date = $dte->payment_date;
                $new_dte->dte_version_id = $dte->dte_version_id;
                $new_dte->status_id = $dte->status_id;
                $new_dte->amount = $dte->amount;
                $new_dte->period = $dte->period;
                $new_dte->comment = $dte->comment;
                $new_dte->temporal_code = $dte->temporal_code;
                $new_dte->created_at = $dte->created_at;
                if ($new_dte->save()) {
                    if ($new_dte->dte_version_id == 1) {
                        if ($new_dte->dte_type_id == 33) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode('-', $new_dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date.'-01';
                            $amount = $new_dte->amount;
                            $expense_type = ExpenseType::find($new_dte->expense_type_id);
                            $message = $branch_office->branch_office.'_441000102_'.$utf8_date.'_Factura_'.$new_dte->dte_id;
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
                                        $expense_type->accounting_account => round($amount / 1.19), // venta abonados neto
                                        221000226 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($new_dte->dte_type_id == 39) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode('-', $new_dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date.'-01';
                            $amount = $new_dte->amount;
                            $expense_type = ExpenseType::find($new_dte->expense_type_id);
                            $message = $branch_office->branch_office.'_441000102_'.$utf8_date.'_BoletaElectronica_'.$new_dte->dte_id;
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
                                        $expense_type->accounting_account => round($amount / 1.19),
                                        221000226 => round($amount - ($amount / 1.19)),
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($new_dte->dte_type_id == 61) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode('-', $new_dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date.'-01';
                            $amount = $new_dte->amount;
                            $expense_type = ExpenseType::find($new_dte->expense_type_id);
                            $message = $branch_office->branch_office.'_441000102_'.$utf8_date.'_NotaCredito_'.$new_dte->dte_id;
                            $url = 'https://libredte.cl';
                            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                            $creator = '76063822-6';
                            $data = [
                                'fecha' => $date,
                                'glosa' => $message,
                                'detalle' => [
                                    'debe' => [
                                        $expense_type->accounting_account => round($amount / 1.19), // venta abonados neto
                                        221000226 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                    'haber' => [
                                        111000102 => $amount, // banco total
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        }
                    } else {
                        if ($new_dte->dte_type_id == 33) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode('-', $new_dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date.'-01';
                            $amount = $new_dte->amount;
                            $expense_type = ExpenseType::find($new_dte->expense_type_id);
                            $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_FacturaCompra_'.$new_dte->dte_id;
                            $url = 'https://libredte.cl';
                            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                            $creator = '76063822-6';
                            $data = [
                                'fecha' => $date,
                                'glosa' => $message,
                                'detalle' => [
                                    'debe' => [
                                        trim($expense_type->accounting_account) => round($amount / 1.19), // venta abonados neto
                                        111000122 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                    'haber' => [
                                        111000102 => $amount, // banco total
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($new_dte->dte_type_id == 34) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode('-', $new_dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date.'-01';
                            $amount = $new_dte->amount;
                            $expense_type = ExpenseType::find($new_dte->expense_type_id);
                            $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_ExentaCompra_'.$new_dte->dte_id;
                            $url = 'https://libredte.cl';
                            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                            $creator = '76063822-6';
                            $data = [
                                'fecha' => $date,
                                'glosa' => $message,
                                'detalle' => [
                                    'debe' => [
                                        trim($expense_type->accounting_account) => $amount, // venta abonados neto
                                    ],
                                    'haber' => [
                                        111000102 => $amount, // banco total
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($new_dte->dte_type_id == 56) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode('-', $new_dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date.'-01';
                            $amount = $new_dte->amount;
                            $expense_type = ExpenseType::find($new_dte->expense_type_id);
                            $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_NotaDebitoCompra_'.$new_dte->dte_id;
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
                                        trim($expense_type->accounting_account) => round($amount / 1.19), // venta abonados neto
                                        111000122 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($new_dte->dte_type_id == 61) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode('-', $new_dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date.'-01';
                            $amount = $new_dte->amount;
                            $expense_type = ExpenseType::find($new_dte->expense_type_id);
                            $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_NotaCreditoCompra_'.$new_dte->dte_id;
                            $url = 'https://libredte.cl';
                            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                            $creator = '76063822-6';
                            $data = [
                                'fecha' => $date,
                                'glosa' => $message,
                                'detalle' => [
                                    'debe' => [
                                        trim($expense_type->accounting_account) => round($amount / 1.19), // venta abonados neto
                                        111000122 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                    'haber' => [
                                        111000102 => $amount, // banco total
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        }
                    }
                }
            }

            $dte = Dte::find($id);
            $dte->delete();
        } else {
            for ($i = 0; $i < count($splits); $i++) {
                $id = $request->dte_id;
                $dte = Dte::find($id);
                if ($request->comment != '') {
                    $dte->comment = $request->comment;
                }
                $dte->period = $splits[$i]->split_period;
                $dte->amount = $splits[$i]->split_amount;
                $dte->expense_type_id = $request->expense_type_id;
                $dte->payment_date = $request->payment_date;
                $dte->payment_type_id = $request->payment_type_id;
                if ($dte->dte_version_id == 1) {
                    $dte->status_id = 17;
                } else {
                    $dte->status_id = 18;
                }
                if ($dte->save()) {
                    if ($dte->dte_version_id == 1) {
                        if ($dte->dte_type_id == 33) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode('-', $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date.'-01';
                            $amount = $dte->amount;
                            $expense_type = ExpenseType::find($dte->expense_type_id);
                            $message = $branch_office->branch_office.'_441000102_'.$utf8_date.'_Factura_'.$dte->dte_id;
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
                                        $expense_type->accounting_account => round($amount / 1.19), // venta abonados neto
                                        221000226 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$dte->dte_type_id, 'folio'=>$dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($dte->dte_type_id == 39) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode('-', $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date.'-01';
                            $amount = $dte->amount;
                            $expense_type = ExpenseType::find($dte->expense_type_id);
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
                                        $expense_type->accounting_account => round($amount / 1.19),
                                        221000226 => round($amount - ($amount / 1.19)),
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$dte->dte_type_id, 'folio'=>$dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($dte->dte_type_id == 61) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode('-', $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date.'-01';
                            $amount = $dte->amount;
                            $expense_type = ExpenseType::find($dte->expense_type_id);
                            $message = $branch_office->branch_office.'_441000102_'.$utf8_date.'_NotaCredito_'.$dte->dte_id;
                            $url = 'https://libredte.cl';
                            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                            $creator = '76063822-6';
                            $data = [
                                'fecha' => $date,
                                'glosa' => $message,
                                'detalle' => [
                                    'debe' => [
                                        $expense_type->accounting_account => round($amount / 1.19), // venta abonados neto
                                        221000226 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                    'haber' => [
                                        111000102 => $amount, // banco total
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$dte->dte_type_id, 'folio'=>$dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        }
                    } else {
                        if ($dte->dte_type_id == 33) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode('-', $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date.'-01';
                            $amount = $dte->amount;
                            $expense_type = ExpenseType::find($dte->expense_type_id);
                            $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_FacturaCompra_'.$dte->dte_id;
                            $url = 'https://libredte.cl';
                            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                            $creator = '76063822-6';
                            $data = [
                                'fecha' => $date,
                                'glosa' => $message,
                                'detalle' => [
                                    'debe' => [
                                        trim($expense_type->accounting_account) => round($amount / 1.19), // venta abonados neto
                                        111000122 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                    'haber' => [
                                        111000102 => $amount, // banco total
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$dte->dte_type_id, 'folio'=>$dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($dte->dte_type_id == 34) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode('-', $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date.'-01';
                            $amount = $dte->amount;
                            $expense_type = ExpenseType::find($dte->expense_type_id);
                            $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_ExentaCompra_'.$dte->dte_id;
                            $url = 'https://libredte.cl';
                            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                            $creator = '76063822-6';
                            $data = [
                                'fecha' => $date,
                                'glosa' => $message,
                                'detalle' => [
                                    'debe' => [
                                        trim($expense_type->accounting_account) => $amount, // venta abonados neto
                                    ],
                                    'haber' => [
                                        111000102 => $amount, // banco total
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$dte->dte_type_id, 'folio'=>$dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($dte->dte_type_id == 56) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode('-', $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date.'-01';
                            $amount = $dte->amount;
                            $expense_type = ExpenseType::find($dte->expense_type_id);
                            $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_NotaDebitoCompra_'.$dte->dte_id;
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
                                        trim($expense_type->accounting_account) => round($amount / 1.19), // venta abonados neto
                                        111000122 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$dte->dte_type_id, 'folio'=>$dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } elseif ($dte->dte_type_id == 61) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode('-', $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date.'-01';
                            $amount = $dte->amount;
                            $expense_type = ExpenseType::find($dte->expense_type_id);
                            $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_NotaCreditoCompra_'.$new_dte->dte_id;
                            $url = 'https://libredte.cl';
                            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                            $creator = '76063822-6';
                            $data = [
                                'fecha' => $date,
                                'glosa' => $message,
                                'detalle' => [
                                    'debe' => [
                                        trim($expense_type->accounting_account) => round($amount / 1.19), // venta abonados neto
                                        111000122 => round($amount - ($amount / 1.19)), // iva débito
                                    ],
                                    'haber' => [
                                        111000102 => $amount, // banco total
                                    ],
                                ],
                                'operacion' => 'I',
                                'documentos' => ['emitidos'=>[['dte'=>$dte->dte_type_id, 'folio'=>$dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                            ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code'] != 200) {
                                exit('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        }
                    }
                }
            }
        }

        return $this->successResponse($dte);
    }

    /**
     * Impute the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        // datos a utilizar
        $url = 'https://libredte.cl';
        $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
        $rut = '76063822-6';
        $until = date('Y-m-d');
        $since = date('Y-m-d', strtotime($until.'- 5 days'));
        $until = date('Y-m-d');

        // crear cliente
        $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);

        // hacer la búsqueda de los DTEs
        $data = [
            'fecha_desde' => $since,
            'fecha_hasta' => $until,
        ];
        $search = $LibreDTE->post('/dte/dte_recibidos/buscar/'.$rut, $data);
        if ($search['status']['code'] != 200) {
            exit('Error al realizar la búsqueda de DTEs emitidos: '.$search['body']."\n");
        }

        for ($i = 0; $i < count($search['body']); $i++) {
            $s = 1;
            $r = $search['body'][$i]['emisor'];
            for ($m = 0; $r != 0; $r /= 10) {
                $s = ($s + $r % 10 * (9 - $m++ % 6)) % 11;
            }
            $index = chr($s ? $s + 47 : 75);
            $rut = $search['body'][$i]['emisor'].'-'.$index;
            $user_qty = User::where('rut', $search['body'][$i]['emisor'])->count();
            if ($user_qty == 0) {
                $user = new User;
                $user->rut = $search['body'][$i]['emisor'].'-'.$index;
                $user->names = $search['body'][$i]['razon_social'];
                $user->rol_id = 18;
                $user->save();
                $supplier = new Supplier;
                $supplier->rut = $search['body'][$i]['emisor'].'-'.$index;
                $supplier->payment_commitment = 30;
                $supplier->save();
            }

            $dte_qty = Dte::where('folio', $search['body'][$i]['folio'])
                            ->where('dte_version_id', '2')
                            ->count();
            if ($dte_qty == 0) {
                $dte = new Dte;
                $dte->rut = $search['body'][$i]['emisor'].'-'.$index;
                $dte->folio = $search['body'][$i]['folio'];
                $dte->branch_office_id = 80;
                $dte->dte_type_id = $search['body'][$i]['dte'];
                if ($search['body'][$i]['dte'] == 61) {
                    $dte->amount = $search['body'][$i]['total'] * -1;
                } else {
                    $dte->amount = $search['body'][$i]['total'];
                }
                $dte->dte_version_id = 2;
                $dte->status_id = 6;
                $dte->created_at = $search['body'][$i]['fecha'].' 00:00:00';
                $dte->save();
            }
        }
    }
}
