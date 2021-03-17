<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Dte;
use App\Customer;
use App\CreditNote;
use App\Email;
use App\Commune;
use App\ExpenseType;
use App\User;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;

class CreditNoteController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();

        $this->user = User::where('api_token', $request->api_token)->first();

        if($this->user->rol_id == 4) {
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
    public function generate(Request $request)
    {
        $option_id = $request->option_id;
        $dte_id = $request->dte_id;
        $comment = $request->comment;

        if($option_id == 1) {
            $dte = Dte::find($dte_id);
            $dte->status_id = 19;
            $branch_office = BranchOffice::find($dte->branch_office_id);
            $customer = Customer::where('rut', $dte->rut)->first();
            $user = User::where('rut', $dte->rut)->first();
            $rut = $customer->rut;
            $client = $user->names;
            $address = $customer->address;
            $email = $customer->email;
            $description = "Nota de Crédito";
            $amount = $dte->amount;
            if($dte->save()) {
                $url = 'https://libredte.cl';
                $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';

                $dte = [
                    'Encabezado' => [
                        'IdDoc' => [
                            'TipoDTE' => 61,
                            'MntBruto' => 1
                        ],
                        'Emisor' => [
                            'RUTEmisor' => '76063822-6',
                            'CdgSIISucur' => $branch_office->dte_code,
                        ],
                        'Receptor' => [
                            'RUTRecep' => $rut,
                            'RznSocRecep' => $client,
                            'DirRecep' =>  $address,
                            'CmnaRecep' => '',
                            'Contacto' =>  $email,
                            'CorreoRecep' =>  $email,
                        ],
                    ],
                    'Detalle' => [
                        [
                            'NmbItem' => $description.'. Fecha: '.date('d').'-'.date('m').'-'.date('Y'),
                            'QtyItem' => 1,
                            'PrcItem' => round($amount, 0),
                        ],
                    ],
                    "Referencia" => [
                        "TpoDocRef" => $dte->dte_type_id,
                        "CodRef" => 1,
                        "FolioRef" => $dte->folio,
                        "RazonRef" => "Anula factura"
                    ]
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

                $credit_note = new CreditNote;
                $credit_note->folio = $dte->folio;
                $credit_note->comment = $comment;
                $credit_note->save();
            }
        } else if($option_id == 2) {
            $dte = Dte::find($dte_id);
            $dte->status_id = 19;
            $branch_office = BranchOffice::find($dte->branch_office_id);
            $customer = Customer::where('rut', $dte->rut)->first();
            $user = User::where('rut', $dte->rut)->first();
            $rut = $customer->rut;
            $client = $user->names;
            $address = $customer->address;
            $email = $customer->email;
            $description = "Nota de Crédito";
            $amount = $dte->amount;
            if($dte->save()) {
                $new_dte = new Dte;
                $new_dte->rut = $dte->rut;
                $new_dte->branch_office_id = $dte->branch_office_id;
                $new_dte->expense_type_id = $dte->expense_type_id;
                $new_dte->dte_version_id = $dte->dte_version_id;
                $new_dte->status_id = 6;
                $new_dte->amount = $dte->amount;
                $new_dte->created_at = $dte->created_at;
                $new_dte->save();
                $url = 'https://libredte.cl';
                $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';

                $dte = [
                    'Encabezado' => [
                        'IdDoc' => [
                            'TipoDTE' => 61,
                            'MntBruto' => 1
                        ],
                        'Emisor' => [
                            'RUTEmisor' => '76063822-6',
                            'CdgSIISucur' => $branch_office->dte_code,
                        ],
                        'Receptor' => [
                            'RUTRecep' => $rut,
                            'RznSocRecep' => $client,
                            'DirRecep' =>  $address,
                            'CmnaRecep' => '',
                            'Contacto' =>  $email,
                            'CorreoRecep' =>  $email,
                        ],
                    ],
                    'Detalle' => [
                        [
                            'NmbItem' => $description.'. Fecha: '.date('d').'-'.date('m').'-'.date('Y'),
                            'QtyItem' => 1,
                            'PrcItem' => round($amount, 0),
                        ],
                    ],
                    "Referencia" => [
                        "TpoDocRef" => $dte->dte_type_id,
                        "CodRef" => 1,
                        "FolioRef" => $dte->folio,
                        "RazonRef" => "Anula factura"
                    ]
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

                $credit_note = new CreditNote;
                $credit_note->folio = $dte->folio;
                $credit_note->comment = $comment;
                $credit_note->save();
            }
        }
    }


    /**
     * Impute the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request)
    {
        if($this->user->rol_id == 1) {
            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';

            $id = $request->segment(4);

            // se obtiene los datos para la factura.
            $dte = Dte::find($id);
            
            $dte_type_id = $dte->dte_type_id;
            $branch_office_id = $dte->branch_office_id;
            $rut = $dte->rut;
            $customer = Customer::find($rut);
            $user = User::find($rut);
            $client = $user->names;
            $address = '';
            $email = $user->email;
            $amount = $dte->amount;
            $branch_office = BranchOffice::find($branch_office_id);
            $dte_code = $branch_office->dte_code;
            $description = 'No hay descripción';
            $commune = '';

            // Se utiliza la API para generar el temporal de la boleta o factura.
            if($dte_type_id == 33) {
                $dte = [
                    'Encabezado' => [
                        'IdDoc' => [
                            'TipoDTE' => $dte_type_id,
                            'MntBruto' => 1
                        ],
                        'Emisor' => [
                            'RUTEmisor' => '76063822-6',
                            'CdgSIISucur' => $dte_code,
                        ],
                        'Receptor' => [
                            'RUTRecep' => $rut,
                            'RznSocRecep' => $client,
                            'DirRecep' =>  $address,
                            'CmnaRecep' => '',
                            'Contacto' =>  $email,
                            'CorreoRecep' =>  $email,
                        ],
                    ],
                    'Detalle' => [
                        [
                            'NmbItem' => $description.'. Fecha: '.date('d').'-'.date('m').'-'.date('Y'),
                            'QtyItem' => 1,
                            'PrcItem' => round($amount, 0),
                        ],
                    ],
                ];
            } else {
                $dte = [
                    'Encabezado' => [
                        'IdDoc' => [
                            'TipoDTE' => $dte_type_id
                        ],
                        'Emisor' => [
                            'RUTEmisor' => '76063822-6',
                            'CdgSIISucur' => $dte_code,
                        ],
                        'Receptor' => [
                            'RUTRecep' => $rut,
                            'RznSocRecep' => $client,
                            'DirRecep' =>  $address,
                            'CmnaRecep' => '',
                            'Contacto' =>  $email,
                            'CorreoRecep' =>  $email,
                        ],
                    ],
                    'Detalle' => [
                        [
                            'NmbItem' => $description.'. Fecha: '.date('d').'-'.date('m').'-'.date('Y'),
                            'QtyItem' => 1,
                            'PrcItem' => round($amount, 0),
                        ],
                    ],
                ];
            }

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
            $dte = Dte::find($id);
            $dte->status_id = 18;
            $folio = $generar['body']['folio'];
            $dte->folio = $folio;
            $dte->temporal_code = $temporal_code;
            if($dte->save()) {
                if($dte->dte_version_id == 1) {
                    if($dte->dte_type_id == 33) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $date = explode(" ", $dte->created_at);
                        $date = explode("-", $date[0]);
                        $utf8_date = '01-'.$date[1].'-'.$date[0];
                        $date = $date[0].'-'.$date[1].'-01';
                        $amount = $dte->amount;
                        $accounting_account = 441000102;
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
                                            $accounting_account => round($amount/1.19), // venta abonados neto
                                            221000226 => round($amount - ($amount/1.19)), // iva débito
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
                    } else if($dte->dte_type_id == 39) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $date = explode(" ", $dte->created_at);
                        $date = explode("-", $date[0]);
                        $utf8_date = '01-'.$date[1].'-'.$date[0];
                        $date = $date[0].'-'.$date[1].'-01';
                        $amount = $dte->amount;
                        $accounting_account = 441000102;
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
                                            $accounting_account => round($amount/1.19),
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
                    } else if($dte->dte_type_id == 61) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $date = explode(" ", $dte->created_at);
                        $date = explode("-", $date[0]);
                        $utf8_date = '01-'.$date[1].'-'.$date[2];
                        $date = $date[0].'-'.$date[1].'-01';
                        $amount = $dte->amount;
                        $accounting_account = 441000102;
                        $message = $branch_office->branch_office.'_441000102_'.$utf8_date.'_NotaCredito_'.$dte->dte_id;
                        $url = 'https://libredte.cl';
                        $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                        $creator = '76063822-6';
                        $data = [
                                    'fecha' => $date,
                                    'glosa' => $message,
                                    'detalle' => [
                                        'debe' => [
                                            $accounting_account => round($amount/1.19), // venta abonados neto
                                            221000226 => round($amount - ($amount/1.19)), // iva débito
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
                        if ($seat['status']['code']!=200) {
                            die('Error al crear el asiento contable: '.$seat['body']."\n");
                        }
                    }
                } else {
                    if($dte->dte_type_id == 33) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $date = explode(" ", $dte->created_at);
                        $date = explode("-", $date[0]);
                        $utf8_date = '01-'.'-'.$date[1].'-'.$date[2];
                        $date = $date[2].'-'.$date[1].'-01';
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
                                        trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                        111000122 => round($amount - ($amount/1.19)), // iva débito
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
                        if ($seat['status']['code']!=200) {
                            die('Error al crear el asiento contable: '.$seat['body']."\n");
                        }
                    } else if($dte->dte_type_id == 34) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $date = explode(" ", $dte->created_at);
                        $date = explode("-", $date[0]);
                        $utf8_date = '01-'.'-'.$date[1].'-'.$date[2];
                        $date = $date[2].'-'.$date[1].'-01';
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
                        if ($seat['status']['code']!=200) {
                            die('Error al crear el asiento contable: '.$seat['body']."\n");
                        }
                    } else if($dte->dte_type_id == 56) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $date = explode(" ", $dte->created_at);
                        $date = explode("-", $date[0]);
                        $utf8_date = '01-'.'-'.$date[1].'-'.$date[2];
                        $date = $date[2].'-'.$date[1].'-01';
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
                                            trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                            111000122 => round($amount - ($amount/1.19)), // iva débito
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
                    } else if($dte->dte_type_id == 61) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $date = explode(" ", $dte->created_at);
                        $date = explode("-", $date[0]);
                        $utf8_date = '01-'.'-'.$date[1].'-'.$date[2];
                        $date = $date[2].'-'.$date[1].'-01';
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
                                            trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                            111000122 => round($amount - ($amount/1.19)), // iva débito
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
                        if ($seat['status']['code']!=200) {
                            die('Error al crear el asiento contable: '.$seat['body']."\n");
                        }
                    }
                }
            }
        } else {
            $id = $request->segment(4);
            $dte = Dte::find($id);
            $dte->status_id = 24;
            $dte->save();
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
        if(count($splits) > 1) {
            $id = $request->dte_id;
            $dte = Dte::find($id);
            if ($splits[0]->split_comment != '') {
                $dte->comment = $splits[0]->split_comment;
            }
            $dte->period = $splits[0]->split_period;
            $dte->amount = $splits[0]->split_amount;
            $dte->branch_office_id = $request->branch_office_id;
            $dte->expense_type_id = $request->expense_type_id;
            if($dte->dte_version_id == 1) {
                $dte->status_id = 17;
            } else {
                $dte->status_id = 18;
            }
            if($dte->save()) {
                if($dte->dte_version_id == 1) {
                    if($dte->dte_type_id == 33) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $utf8_date = explode("-", $dte->period);
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                        $date = $dte->period;
                        $date = $date .'-01';
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
                                            $expense_type->accounting_account => round($amount/1.19), // venta abonados neto
                                            221000226 => round($amount - ($amount/1.19)), // iva débito
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
                    } else if($dte->dte_type_id == 39) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $utf8_date = explode("-", $dte->period);
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                        $date = $dte->period;
                        $date = $date .'-01';
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
                    } else if($dte->dte_type_id == 61) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $utf8_date = explode("-", $dte->period);
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                        $date = $dte->period;
                        $date = $date .'-01';
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
                                            $expense_type->accounting_account => round($amount/1.19), // venta abonados neto
                                            221000226 => round($amount - ($amount/1.19)), // iva débito
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
                        if ($seat['status']['code']!=200) {
                            die('Error al crear el asiento contable: '.$seat['body']."\n");
                        }
                    }
                } else {
                    if($dte->dte_type_id == 33) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $utf8_date = explode("-", $dte->period);
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                        $date = $dte->period;
                        $date = $date .'-01';
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
                                        trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                        111000122 => round($amount - ($amount/1.19)), // iva débito
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
                        if ($seat['status']['code']!=200) {
                            die('Error al crear el asiento contable: '.$seat['body']."\n");
                        }
                    } else if($dte->dte_type_id == 34) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $utf8_date = explode("-", $dte->period);
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                        $date = $dte->period;
                        $date = $date .'-01';
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
                        if ($seat['status']['code']!=200) {
                            die('Error al crear el asiento contable: '.$seat['body']."\n");
                        }
                    } else if($dte->dte_type_id == 56) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $utf8_date = explode("-", $new_dte->period);
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                        $date = $dte->period;
                        $date = $date .'-01';
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
                                            trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                            111000122 => round($amount - ($amount/1.19)), // iva débito
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
                    } else if($dte->dte_type_id == 61) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $utf8_date = explode("-", $dte->period);
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                        $date = $dte->period;
                        $date = $date .'-01';
                        $amount = $dte->amount;
                        $expense_type = ExpenseType::find($dte->expense_type_id);
                        $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_NotaCreditoCompra_'.$dte->dte_id;
                        $url = 'https://libredte.cl';
                        $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                        $creator = '76063822-6';
                        $data = [
                                    'fecha' => $date,
                                    'glosa' => $message,
                                    'detalle' => [
                                        'debe' => [
                                            trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                            111000122 => round($amount - ($amount/1.19)), // iva débito
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
                        if ($seat['status']['code']!=200) {
                            die('Error al crear el asiento contable: '.$seat['body']."\n");
                        }
                    }
                }
            }
            for($i = 1; $i < count($splits); $i++) {
                $new_dte = new Dte;
                $new_dte->rut = $dte->rut;
                $new_dte->folio = $dte->folio;
                $new_dte->branch_office_id = $dte->branch_office_id;
                $new_dte->dte_type_id = $dte->dte_type_id;
                $new_dte->expense_type_id = $request->expense_type_id;
                $new_dte->dte_version_id = $dte->dte_version_id;
                if($new_dte->dte_version_id == 1) {
                    $new_dte->status_id = 17;
                } else {
                    $new_dte->status_id = 18;
                }
                $new_dte->amount = $splits[$i]->split_amount;
                $new_dte->period = $splits[$i]->split_period;
                if ($splits[$i]->split_comment != '') {
                    $new_dte->comment = $splits[$i]->split_comment;
                }
                $new_dte->created_at = $dte->created_at;
                if($new_dte->save()) {
                    if($new_dte->dte_version_id == 1) {
                        if($new_dte->dte_type_id == 33) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode("-", $new_dte->period);
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date .'-01';
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
                                                $expense_type->accounting_account => round($amount/1.19), // venta abonados neto
                                                221000226 => round($amount - ($amount/1.19)), // iva débito
                                            ],
                                        ],
                                        'operacion' => 'I',
                                        'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                                    ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } else if($new_dte->dte_type_id == 39) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode("-", $new_dte->period);
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date .'-01';
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
                                                $expense_type->accounting_account => round($amount/1.19),
                                                221000226 => round($amount - ($amount/1.19)),
                                            ],
                                        ],
                                        'operacion' => 'I',
                                        'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                                    ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } else if($new_dte->dte_type_id == 61) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode("-", $new_dte->period);
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date .'-01';
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
                                                $expense_type->accounting_account => round($amount/1.19), // venta abonados neto
                                                221000226 => round($amount - ($amount/1.19)), // iva débito
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
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        }
                    } else {
                        if($new_dte->dte_type_id == 33) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode("-", $new_dte->period);
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date .'-01';
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
                                            trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                            111000122 => round($amount - ($amount/1.19)), // iva débito
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
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } else if($new_dte->dte_type_id == 34) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode("-", $new_dte->period);
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date .'-01';
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
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } else if($new_dte->dte_type_id == 56) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode("-", $new_dte->period);
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date .'-01';
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
                                                trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                                111000122 => round($amount - ($amount/1.19)), // iva débito
                                            ],
                                        ],
                                        'operacion' => 'I',
                                        'documentos' => ['emitidos'=>[['dte'=>$new_dte->dte_type_id, 'folio'=>$new_dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                                    ];
                            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } else if($new_dte->dte_type_id == 61) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode("-", $new_dte->period);
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $new_dte->period;
                            $date = $date .'-01';
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
                                                trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                                111000122 => round($amount - ($amount/1.19)), // iva débito
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
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        }
                    }
                }
            }
        } else {
            for($i = 0; $i < count($splits); $i++) {
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
                if($dte->dte_version_id == 1) {
                    $dte->status_id = 17;
                } else {
                    $dte->status_id = 18;
                }
                if($dte->save()) {
                    if($dte->dte_version_id == 1) {
                        if($dte->dte_type_id == 33) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode("-", $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date .'-01';
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
                                                $expense_type->accounting_account => round($amount/1.19), // venta abonados neto
                                                221000226 => round($amount - ($amount/1.19)), // iva débito
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
                        } else if($dte->dte_type_id == 39) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode("-", $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date .'-01';
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
                        } else if($dte->dte_type_id == 61) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode("-", $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date .'-01';
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
                                                $expense_type->accounting_account => round($amount/1.19), // venta abonados neto
                                                221000226 => round($amount - ($amount/1.19)), // iva débito
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
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        }
                    } else {
                        if($dte->dte_type_id == 33) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode("-", $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date .'-01';
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
                                            trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                            111000122 => round($amount - ($amount/1.19)), // iva débito
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
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } else if($dte->dte_type_id == 34) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode("-", $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date .'-01';
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
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
                            }
                        } else if($dte->dte_type_id == 56) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode("-", $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date .'-01';
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
                                                trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                                111000122 => round($amount - ($amount/1.19)), // iva débito
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
                        } else if($dte->dte_type_id == 61) {
                            $branch_office = BranchOffice::find($dte->branch_office_id);
                            $utf8_date = explode("-", $dte->period);
                            $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                            $date = $dte->period;
                            $date = $date .'-01';
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
                                                trim($expense_type->accounting_account) => round($amount/1.19), // venta abonados neto
                                                111000122 => round($amount - ($amount/1.19)), // iva débito
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
                            if ($seat['status']['code']!=200) {
                                die('Error al crear el asiento contable: '.$seat['body']."\n");
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
    public function send(Request $request)
    {
        $email = $request->email;
        $folio = $request->folio;
        $dte = $request->dte;

        $url = 'https://libredte.cl';
        $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
        $rut = 76063822;
        $datos = [
            'emails' => [''.$email.''],
            'asunto' => 'Envío de factura',
            'mensaje' => 'Esta es su factura',
            'pdf' => true,
            'cedible' => true,
            'papelContinuo' => false,
        ];

        $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
        
        // enviar email
        $envio = $LibreDTE->post('/dte/dte_emitidos/enviar_email/'.$dte.'/'.$folio.'/'.$rut, $datos);
        if ($envio['status']['code']!=200) {
            die('Error al enviar el correo del DTE emitido: '.$envio['body']."\n");
        }
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
        if($request->rut != null) {
            // datos a utilizar
            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
            $rut = '76063822-6';
            $until = date('Y-m-d'); 
            $since = date("Y-m-d",strtotime($until."- 5 days"));
            $until = date('Y-m-d');  

            // crear cliente
            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);

            // hacer la búsqueda de los DTEs
            $data = [
                'fecha_desde' => $since,
                'fecha_hasta' => $until,
                'emisor' => $request->rut
            ];
            $search = $LibreDTE->post('/dte/dte_recibidos/buscar/'.$rut, $data);
            if ($search['status']['code']!=200) {
                die('Error al realizar la búsqueda de DTEs emitidos: '.$search['body']."\n");
            }

            for($i = 0; $i < count($search['body']); $i++) {
                $s = 1;
                $r = $search['body'][$i]['emisor'];
                for($m=0;$r!=0;$r/=10) {
                    $s = ($s+$r%10*(9-$m++%6))%11;
                }
                $index = chr($s?$s+47:75);
                $rut = $search['body'][$i]['emisor'] .'-'. $index;
                $user_qty = User::where('rut', $search['body'][$i]['emisor'])->count();
                if($user_qty == 0) {
                    $user = new User;
                    $user->rut = $search['body'][$i]['emisor'] .'-'. $index;
                    $user->names = $search['body'][$i]['razon_social'];
                    $user->rol_id = 18;
                    $user->save();
                    $supplier = new Supplier;
                    $supplier->rut = $search['body'][$i]['emisor'] .'-'. $index;
                    $supplier->payment_commitment = 30;
                    $supplier->save();
                }

                $dte_qty = Dte::where('folio', $search['body'][$i]['folio'])
                                ->where('dte_version_id', '2')
                                ->count();
                if($dte_qty == 0) {
                    $dte = new Dte;
                    $dte->rut = $search['body'][$i]['emisor'] .'-'. $index;
                    $dte->folio = $search['body'][$i]['folio'];
                    $dte->branch_office_id = 80;
                    $dte->dte_type_id = $search['body'][$i]['dte'];
                    if($search['body'][$i]['dte'] == 61) {
                        $dte->amount = $search['body'][$i]['total'];
                    } else {
                        $dte->amount = $search['body'][$i]['total'];
                    }
                    $dte->dte_version_id = 2;
                    $dte->status_id = 6;
                    $dte->created_at = $search['body'][$i]['fecha'] .' 00:00:00';
                    $dte->save();
                }
            }

            return $this->successResponse($dte);
        } else {
            // datos a utilizar
            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
            $rut = '76063822-6';
            $until = date('Y-m-d'); 
            $since = date("Y-m-d",strtotime($until."- 5 days"));
            $until = date('Y-m-d');  

            // crear cliente
            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);

            // hacer la búsqueda de los DTEs
            $data = [
                'fecha_desde' => $since,
                'fecha_hasta' => $until,
            ];
            $search = $LibreDTE->post('/dte/dte_recibidos/buscar/'.$rut, $data);
            if ($search['status']['code']!=200) {
                die('Error al realizar la búsqueda de DTEs emitidos: '.$search['body']."\n");
            }

            for($i = 0; $i < count($search['body']); $i++) {
                $s = 1;
                $r = $search['body'][$i]['emisor'];
                for($m=0;$r!=0;$r/=10) {
                    $s = ($s+$r%10*(9-$m++%6))%11;
                }
                $index = chr($s?$s+47:75);
                $rut = $search['body'][$i]['emisor'] .'-'. $index;
                $user_qty = User::where('rut', $search['body'][$i]['emisor'])->count();
                if($user_qty == 0) {
                    $user = new User;
                    $user->rut = $search['body'][$i]['emisor'] .'-'. $index;
                    $user->names = $search['body'][$i]['razon_social'];
                    $user->rol_id = 18;
                    $user->save();
                    $supplier = new Supplier;
                    $supplier->rut = $search['body'][$i]['emisor'] .'-'. $index;
                    $supplier->payment_commitment = 30;
                    $supplier->save();
                }

                $dte_qty = Dte::where('folio', $search['body'][$i]['folio'])
                                ->where('dte_version_id', '2')
                                ->count();
                if($dte_qty == 0) {
                    $dte = new Dte;
                    $dte->rut = $search['body'][$i]['emisor'] .'-'. $index;
                    $dte->folio = $search['body'][$i]['folio'];
                    $dte->branch_office_id = 80;
                    $dte->dte_type_id = $search['body'][$i]['dte'];
                    if($search['body'][$i]['dte'] == 61) {
                        $dte->amount = $search['body'][$i]['total'] * -1;
                    } else {
                        $dte->amount = $search['body'][$i]['total'];
                    }
                    $dte->dte_version_id = 2;
                    $dte->status_id = 6;
                    $dte->created_at = $search['body'][$i]['fecha'] .' 00:00:00';
                    $dte->save();
                }
            }


            ////////////


            // datos a utilizar
            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
            $rut = '76063822-6';
            $until = date('Y-m-d'); 
            $since = date("Y-m-d",strtotime($until."- 5 days"));
            $until = date('Y-m-d');  

            // crear cliente
            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);

            // hacer la búsqueda de los DTEs
            $data = [
                'dte' => ['61'],
                'fecha_desde' => $since,
                'fecha_hasta' => $until,
            ];
            $search = $LibreDTE->post('/dte/dte_emitidos/buscar/'.$rut, $data);
            if ($search['status']['code']!=200) {
                die('Error al realizar la búsqueda de DTEs emitidos: '.$search['body']."\n");
            }

            for($i = 0; $i < count($search['body']); $i++) {
                $s = 1;
                $r = $search['body'][$i]['receptor'];
                for($m=0;$r!=0;$r/=10) {
                    $s = ($s+$r%10*(9-$m++%6))%11;
                }
                $index = chr($s?$s+47:75);
                $rut = $search['body'][$i]['receptor'] .'-'. $index;
                $user_qty = User::where('rut', $search['body'][$i]['receptor'])->count();
                if($user_qty == 0) {
                    $user = new User;
                    $user->rut = $search['body'][$i]['receptor'] .'-'. $index;
                    $user->names = $search['body'][$i]['razon_social'];
                    $user->rol_id = 14;
                    $user->save();
                    $customer = new Customer;
                    $customer->rut = $search['body'][$i]['receptor'] .'-'. $index;
                    $customer->save();
                }

                $dte_qty = Dte::where('folio', $search['body'][$i]['folio'])
                                ->where('dte_version_id', '1')
                                ->where('dte_type_id', '61')
                                ->count();
                if($dte_qty == 0) {
                    $branch_office = BranchOffice::where('dte_code', $search['body'][$i]['sucursal_sii'])->first();
                    $dte = new Dte;
                    $dte->rut = $search['body'][$i]['receptor'] .'-'. $index;
                    $dte->folio = $search['body'][$i]['folio'];
                    $dte->branch_office_id = $branch_office->branch_office_id;
                    $dte->dte_type_id = 61;
                    $dte->amount = $search['body'][$i]['total'] * -1;
                    $dte->dte_version_id = 1;
                    $dte->status_id = 19;
                    $dte->created_at = $search['body'][$i]['fecha'] .' 00:00:00';
                    $dte->save();

                    $branch_office = BranchOffice::find($dte->branch_office_id);
                    $detail_utf8_date = explode("-", $search['body'][$i]['fecha']);
                    $utf8_date = '01'.'-'.$detail_utf8_date[1].'-'.$detail_utf8_date[0];
                    $date = $detail_utf8_date[0].'-'.$detail_utf8_date[1].'-01';
                    $amount = $search['body'][$i]['total'];
                    $expense_type = ExpenseType::find(25);
                    $message = $branch_office->branch_office.'_441000102_'.$utf8_date.'_NotaCredito_'.$dte->dte_id;
                    $url = 'https://libredte.cl';
                    $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                    $creator = '76063822-6';
                    $data = [
                                'fecha' => $date,
                                'glosa' => $message,
                                'detalle' => [
                                    'debe' => [
                                        $expense_type->accounting_account => round($amount/1.19), // venta abonados neto
                                        221000226 => round($amount - ($amount/1.19)), // iva débito
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
                    if ($seat['status']['code']!=200) {
                        die('Error al crear el asiento contable: '.$seat['body']."\n");
                    }
                }
            }
        }
        
    }
}
