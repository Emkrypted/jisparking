<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Commune;
use App\Customer;
use App\Dte;
use App\ExpenseType;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManualSeatController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        // It is used to connect to a dropbox account. It allows to get the client.
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
        // It's necessary to check if the user has the permissions.
        $this->user = User::where('api_token', $request->api_token)->first();

        // It checks if the user is rol_id = 4 because if it is, it's necessary to get all the branch offices which they belong to this supervisor.
        if ($this->user->rol_id == 4) {
            // It finds a supervisor by rut.
            $this->branch_offices = BranchOffice::where('supervisor_id', $this->user->rut)->pluck('branch_office_id')->toArray();
        } else {
            // If it's not supervisor it just needs to return all the branch offices.
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
        $period = $request->period;
        $utf8_date = explode('-', $period);
        $utf8_date = '01-'.$utf8_date[1].'-'.$utf8_date[0];
        $date = $period.'-01';
        $branch_office = BranchOffice::find($request->branch_office_id);
        $accounting_account = $request->expense_type_id;
        $amount = $request->amount;
        $message = $branch_office->branch_office.'_'.$accounting_account.'_'.$utf8_date.'_AsientoLibre';

        if ($request->tax_id == 1) {
            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
            $creator = '76063822-6';
            $data = [
                'fecha' => $date,
                'glosa' => $message,
                'detalle' => [
                    'debe' => [
                        111000102 => round($amount),
                    ],
                    'haber' => [
                        $accounting_account => round($amount / 1.19),
                        221000226 => round($amount - ($amount / 1.19)),
                    ],
                ],
                'operacion' => 'I',
                'documentos' => ['emitidos'=>[['dte'=>'', 'folio'=>'']]],
            ];

            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
            if ($seat['status']['code'] != 200) {
                exit('Error al crear el asiento contable: '.$seat['body']."\n");
            }
        } else {
            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
            $creator = '76063822-6';
            $data = [
                'fecha' => $date,
                'glosa' => $message,
                'detalle' => [
                    'debe' => [
                        111000102 => round($amount),
                    ],
                    'haber' => [
                        $accounting_account => round($amount),
                    ],
                ],
                'operacion' => 'I',
                'documentos' => ['emitidos'=>[['dte'=>'', 'folio'=>'']]],
            ];

            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
            $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
            if ($seat['status']['code'] != 200) {
                exit('Error al crear el asiento contable: '.$seat['body']."\n");
            }
        }

        return $this->successResponse($seat);
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
        $dte = Dte::with('user')
                ->where('dte_id', $id)
                ->first();

        return $this->successResponse($dte);
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
        if ($this->user->rol_id == 4) {
            $dte_type_id = $request->dte_type_id;
            $branch_office_id = $request->branch_office_id;
            $rut = $request->rut;
            $dte = new Dte;
            $dte->rut = $rut;
            $dte->branch_office_id = $branch_office_id;
            $dte->dte_type_id = $dte_type_id;
            $dte->dte_version_id = 1;
            $dte->amount = $request->amount;
            $dte->expense_type_id = 25;
            $dte->status_id = 24;
            $dte->created_at = date('Y:m:d 00:00:00');
            $dte->save();

            // Si no existe el cliente lo creamos.
            $client_qty = Customer::where('rut', $rut)->count();
            $user_qty = User::where('rut', $rut)->count();
            if ($client_qty == 0 && $user_qty == 0) {
                $customer = new Customer;
                $customer->rut = $rut;
                $customer->address = $request->address;
                $customer->email = $request->email;
                $customer->activity = '';
                $customer->commune_id = $request->commune_id;
                $customer->region_id = $request->region_id;
                $customer->save();
                $user = new User;
                $user->rut = $rut;
                $user->names = $request->client;
                $user->rol_id = 14;
                $user->email = $request->email;
                $user->save();
            }
        } else {
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
            $description = 'No hay descripción';
            $commune = Commune::find($request->commune_id);

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
                        'CmnaRecep' => $commune->commune,
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
            $dte->expense_type_id = 25;
            $dte->temporal_code = $temporal_code;
            $dte->status_id = 18;
            $dte->created_at = date('Y:m:d 00:00:00');
            $dte->save();

            // Si no existe el cliente lo creamos.
            $client_qty = Customer::where('rut', $rut)->count();
            $user_qty = User::where('rut', $rut)->count();
            if ($client_qty == 0 && $user_qty == 0) {
                $customer = new Customer;
                $customer->rut = $rut;
                $customer->address = $request->address;
                $customer->email = $email;
                $customer->activity = '';
                $customer->commune_id = $request->commune_id;
                $customer->region_id = $request->region_id;
                $customer->save();
                $user = new User;
                $user->rut = $rut;
                $user->names = $client;
                $user->rol_id = 14;
                $user->email = $email;
            }

            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
            // se obtiene los datos para la factura.
            $dte_type_id = $request->dte_type_id;
            $branch_office_id = $request->branch_office_id;
            $rut = $request->rut;
            $customer = Customer::find($rut);
            $user = User::find($rut);
            $client = $request->client;
            $address = $customer->address;
            $email = $user->email;
            $amount = $request->amount;
            $branch_office = BranchOffice::find($branch_office_id);
            $dte_code = $branch_office->dte_code;
            $description = 'No hay descripción';
            $commune = Commune::find($customer->commune_id);
            $branch_office = BranchOffice::find($dte->branch_office_id);
            $utf8_date = date('d-m-Y');
            $date = date('Y-m');
            $date = $date.'-01';
            $expense_type = ExpenseType::find($dte->expense_type_id);

            if ($request->dte_type_id == 33) {
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
            } elseif ($request->dte_type_id == 39) {
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
    public function accept(Request $request)
    {
        $url = 'https://libredte.cl';
        $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';

        // se obtiene los datos para la factura.
        $id = $request->segment(4);
        $dte_type_id = $request->dte_type_id;
        $branch_office_id = $request->branch_office_id;
        $rut = $request->rut;
        $customer = Customer::find($rut);
        $user = User::find($rut);
        $client = $request->client;
        $address = $customer->address;
        $email = $user->email;
        $amount = $request->amount;
        $branch_office = BranchOffice::find($branch_office_id);
        $dte_code = $branch_office->dte_code;
        $description = 'No hay descripción';
        $commune = Commune::find($customer->commune_id);

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
                    'CmnaRecep' => $commune->commune,
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
        $dte = Dte::find($id);
        $dte->rut = $rut;
        $dte->folio = $folio;
        $dte->temporal_code = $temporal_code;
        $dte->status_id = 18;
        $dte->created_at = date('Y:m:d 00:00:00');
        if ($dte->save()) {
            if ($dte->dte_version_id == 1) {
                if ($dte->dte_type_id == 33) {
                    $branch_office = BranchOffice::find($dte->branch_office_id);
                    $utf8_date = explode('-', $request->period);
                    $utf8_date = '01-'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                    $date = $dte->period;
                    $date = $date.'-01';
                    $amount = $new_dte->amount;
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
                    $utf8_date = explode('-', $request->period);
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
                    $utf8_date = explode('-', $request->period);
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
                    $utf8_date = explode('-', $request->period);
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
                    $utf8_date = explode('-', $request->period);
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
                    $utf8_date = explode('-', $request->period);
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
                    $utf8_date = explode('-', $request->period);
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
                $dte->branch_office_id = $request->branch_office_id;
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
        if ($request->rut != null) {
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
                'emisor' => $request->rut,
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

            return $this->successResponse($dte);
        } else {
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
}
