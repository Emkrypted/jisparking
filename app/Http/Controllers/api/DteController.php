<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Commune;
use App\Customer;
use App\Dte;
use App\Eerr;
use App\Email;
use App\ExpenseType;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\Seat;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;

class DteController extends ApiResponseController
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
        $branch_office_id = $request->segment(4);
        $status_id = $request->segment(5);
        $since = $request->segment(6);
        $until = $request->segment(7);
        $folio = $request->segment(8);
        $dte_version_id = $request->segment(9);
        $rut = $request->segment(10);
        $supervisor_id = $request->segment(11);
        if (($branch_office_id == 'null' && $status_id == 'null' && $since == 'null' && $until == 'null' && $folio == 'null' && $dte_version_id == 'null' && $rut == 'null' && $supervisor_id == 'null')
        || ($branch_office_id == '' && $status_id == '' && $since == '' && $until == '' && $folio == '' && $dte_version_id == '' && $rut == '' && $supervisor_id == '')
        ) {
            if ($this->user->rol_id != 1) {
                $branch_offices = $this->branch_offices;
                $query = 'c.rut <> "66666666-6" AND (c.status_id <> 7 AND c.status_id <> 14 AND c.status_id <> 24)';
                $query .= ' AND ';
                for ($i = 0; $i < count($branch_offices); $i++) {
                    if ($i == 0) {
                        $query .= '(c.branch_office_id = '.$branch_offices[$i];
                    } else {
                        $query .= ' OR c.branch_office_id = '.$branch_offices[$i];
                    }
                }

                $query .= ')';

                $dtes = Dte::from('dtes as c')
                        ->selectRaw('c.payment_date as payment_date, users.names as names, c.dte_id as dte_id, branch_offices.branch_office as branch_office, c.folio as folio, c.dte_version_id as dte_version_id, c.amount as amount, c.dte_type_id as dte_type_id, dte_types.dte_type as dte_type, c.created_at as created_at, c.status_id as status_id, statuses.status as status')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->whereRaw($query)
                        ->where('c.rut', '<>', '66666666-6')
                        ->where('c.dte_version_id', '<>', '2')
                        ->where('c.dte_type_id', '<>', '61')
                        ->where('c.status_id', '<>', '6')
                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                        ->leftJoin('dte_types', 'c.dte_type_id', '=', 'dte_types.dte_type_id')
                        ->orderBy('c.created_at', 'DESC')
                        ->paginate(10);
            } else {
                $dtes = Dte::from('dtes as c')
                        ->selectRaw('c.payment_date as payment_date, users.names as names, c.dte_id as dte_id, branch_offices.branch_office as branch_office, c.folio as folio, c.dte_version_id as dte_version_id, dte_types.dte_type as dte_type, c.amount as amount, c.dte_type_id as dte_type_id, c.created_at as created_at, c.status_id as status_id, statuses.status as status')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->where('c.rut', '<>', '66666666-6')
                        ->where('c.status_id', '<>', '24')
                        ->where('c.status_id', '<>', '7')
                        ->where('c.status_id', '<>', '14')
                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                        ->leftJoin('dte_types', 'c.dte_type_id', '=', 'dte_types.dte_type_id')
                        ->orderBy('c.created_at', 'DESC')
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

                $query .= '(c.created_at >= "'.$since.' 00:00:00")';
            }

            if ($until != 'null') {
                if ($branch_office_id != 'null' || $status_id != 'null' || $since != 'null') {
                    $query .= ' AND ';
                }

                $query .= '(c.created_at <= "'.$until.' 23:59:59")';
            }

            if ($folio != 'null') {
                if ($branch_office_id != 'null' || $status_id != 'null' || $since != 'null' || $until != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.folio = "'.$folio.'"';
            }

            if ($dte_version_id != 'null') {
                if ($branch_office_id != 'null' || $status_id != 'null' || $since != 'null' || $until != 'null' || $folio != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.dte_version_id = '.$dte_version_id.'';
            }

            if ($rut != 'null') {
                if ($branch_office_id != 'null' || $status_id != 'null' || $since != 'null' || $until != 'null' || $folio != 'null' || $dte_version_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.rut = "'.$rut.'"';
            }

            if ($supervisor_id != 'null') {
                if ($branch_office_id != 'null' || $status_id != 'null' || $since != 'null' || $until != 'null' || $folio != 'null' || $dte_version_id != 'null' || $rut != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'branch_offices.supervisor_id = "'.$supervisor_id.'"';
            }

            $dtes = Dte::from('dtes as c')
                        ->selectRaw('c.payment_date as payment_date, users.names as names, c.dte_id as dte_id, branch_offices.branch_office as branch_office, c.folio as folio, c.dte_version_id as dte_version_id, dte_types.dte_type as dte_type, c.amount as amount, c.dte_type_id as dte_type_id, c.created_at as created_at, c.status_id as status_id, statuses.status as status')
                        ->leftJoin('users', 'users.rut', '=', 'c.rut')
                        ->whereRaw($query)
                        ->where('c.rut', '<>', '66666666-6')
                        ->where('c.status_id', '<>', '24')
                        ->where('c.status_id', '<>', '14')
                        ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                        ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                        ->leftJoin('dte_types', 'c.dte_type_id', '=', 'dte_types.dte_type_id')
                        ->orderBy('c.rut', 'DESC')
                        ->paginate(10);
        }

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
        if ($request->special == 1) {
            // Se guarda los datos de la boleta.
            $dte = new Dte;
            $dte->rut = $request->rut;
            $dte->folio = $request->folio;
            $dte->branch_office_id = $request->branch_office_id;
            $dte->dte_type_id = 39;
            $dte->dte_version_id = 1;
            $dte->amount = $request->amount;
            $dte->expense_type_id = 0;
            $dte->temporal_code = $request->temporal_code;
            $dte->status_id = 17;
            $dte->created_at = $request->created_at;
            $dte->save();
        }

        return $this->successResponse($dte);
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
        $dte = Dte::find($id);
        if ($request->branch_office_id != '') {
            $dte->branch_office_id = $request->branch_office_id;
        }
        if ($request->period != '') {
            $dte->period = $request->period;
        }
        if ($request->amount != '') {
            $dte->amount = $request->amount;
        }
        if ($request->expense_type_id != '') {
            $dte->expense_type_id = $request->expense_type_id;
        }
        if ($request->status_id != '') {
            $dte->status_id = $request->status_id;
        }

        $dte->save();

        $seat_qty = Seat::where('location_id', $dte->dte_id)->count();

        if ($seat_qty > 0) {
            $seat = Seat::where('location_id', $dte->dte_id)->first();
            $period = explode('-', $seat->period);
            $period = $period[0];
            $seat_number = $seat->seat_number_id;
            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
            $rut = 76063822;
            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
            echo '/lce/lce_asientos/eliminar/'.$period.'/'.$seat_number.'/'.$rut.'';
            $info = $LibreDTE->get('/lce/lce_asientos/eliminar/'.$period.'/'.$seat_number.'/'.$rut.'');
            if ($info['status']['code'] != 200) {
                exit('Error al borrar el asiento: '.$info['body']."\n");
            }
            $seats = Seat::where('location_id', $dte->dte_id)->get();
            foreach ($seats as $seat) {
                echo 6;
                $seat_detail = Seat::where('seat_id', $seat->seat_id)->first();
                $seat_detail->delete();
            }
            $eerrs = Eerr::where('seat_id', $seat_number)->get();
            foreach ($eerrs as $eerr) {
                echo 5;
                $eerr = Eerr::where('eerr_id', $eerr->eerr_id)->first();
                $eerr->delete();
            }

            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
            // se obtiene los datos para la factura.
            $dte_type_id = $dte->dte_type_id;
            $branch_office_id = $dte->branch_office_id;
            $rut = strtoupper($dte->rut);
            $amount = $dte->amount;
            $branch_office = BranchOffice::find($branch_office_id);
            $dte_code = $branch_office->dte_code;
            $description = 'Prestación de estacionamientos';
            $expense_type = ExpenseType::find($dte->expense_type_id);

            if ($dte->dte_version_id == 1) {
                if ($dte->dte_type_id == 33) {
                    $branch_office = BranchOffice::find($dte->branch_office_id);
                    $date = explode(' ', $dte->created_at);
                    $date = explode('-', $date[0]);
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
                                $accounting_account => round($amount / 1.19), // venta abonados neto
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
                    $date = explode(' ', $dte->created_at);
                    $date = explode('-', $date[0]);
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
                                $accounting_account => round($amount / 1.19),
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
                    $date = explode(' ', $dte->created_at);
                    $date = explode('-', $date[0]);
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
                                $accounting_account => round($amount / 1.19), // venta abonados neto
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
                    $date = explode(' ', $dte->created_at);
                    $date = explode('-', $date[0]);
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
                    $date = explode(' ', $dte->created_at);
                    $date = explode('-', $date[0]);
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
                    if ($seat['status']['code'] != 200) {
                        exit('Error al crear el asiento contable: '.$seat['body']."\n");
                    }
                } elseif ($dte->dte_type_id == 56) {
                    $branch_office = BranchOffice::find($dte->branch_office_id);
                    $date = explode(' ', $dte->created_at);
                    $date = explode('-', $date[0]);
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
                    $date = explode(' ', $dte->created_at);
                    $date = explode('-', $date[0]);
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

        return $this->successResponse($dte);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request)
    {
        $libre_dte_status_id = $request->segment(4);

        if ($libre_dte_status_id == 1) {
            // configuración auth en LibreDTE
            $emisor = 76063822; // RUT sin puntos ni DV
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1'; // en perfil del usuario
            $user = 'rcabezas'; // definido en la config del servicio web en LibreDTE
            $pass = 'Jisparking2018'; // definido en la config del servicio web en LibreDTE

            // verificar si la consulta tiene las credenciales válidas
            if ($this->auth_check($user, $pass)) {
                exit('Usuario no autenticado o credenciales incorrectas');
            }

            // recibir datos del servicio web y extraer EnvioDTE o EnvioBOLETA
            $cobro_informado = json_decode(file_get_contents('php://input'), true);
            if (! $cobro_informado) {
                exit('No se recibió datos del cobro');
            }

            // verificar que el cobro efectivamente esté pagado
            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash);
            $cobro = $LibreDTE->get('/pagos/cobros/info/'.$cobro_informado['codigo'].'/'.$emisor);
            if ($cobro['status']['code'] != 200) {
                exit('Error al realizar la consulta del cobro: '.$cobro['body']);
            }

            $Cobro = (object) $cobro['body'];

            // si el cobro está pagado (esto realmente asegura que está pagado, ya que se consultó a LibreDTE)
            if ($Cobro->pagado) {
                $data = json_decode($Cobro->datos);

                $dte_qty = Dte::where('folio', $Cobro->emitido)->where('dte_type_id', 39)->orWhere('dte_type_id', 33)->count();

                if ($dte_qty > 0) {
                    $folio = $Cobro->emitido;
                    $dte = Dte::where('folio', $folio)->first();
                    $dte->payment_type_id = 2;
                    $dte->payment_date = $Cobro->pagado;
                    $dte->comment = 'Código de autorización: '.$data->detailOutput->authorizationCode;
                    $dte->expense_type_id = 25;
                    $dte->status_id = 19;
                    if ($dte->save()) {
                        $date = $dte->created_at;
                        $date = explode(' ', $date);
                        $exploded_date = explode('-', $date[0]);
                        $date = $exploded_date[0].'-'.$exploded_date[1].'-'.$exploded_date[2];
                        $utf8_date = $exploded_date[2].'-'.$exploded_date[1].'-'.$exploded_date[0];
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        if ($dte->dte_type_id == 33) {
                            $message = $branch_office->branch_office.'_44100010_'.$utf8_date.'_Factura_'.$dte->dte_id;
                        } else {
                            $message = $branch_office->branch_office.'_44100010_'.$utf8_date.'_BoletaElectronica_'.$dte->dte_id;
                        }
                        $amount = $dte->amount;
                        $url = 'https://libredte.cl';
                        $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                        $emisor = '76063822-6';
                        $datos = [
                            'fecha' => $date,
                            'glosa' => $message,
                            'detalle' => [
                                'debe' => [
                                    111000102 => $amount, // banco total
                                ],
                                'haber' => [
                                    441000102 => round($amount / 1.19), // venta abonados neto
                                    221000226 => round($amount - ($amount / 1.19)), // iva débito
                                ],
                            ],
                            'operacion' => 'I',
                            'documentos' => ['emitidos'=>[['dte'=>$dte->dte_type_id, 'folio'=>$dte->folio]]], // esto es opcional, pero se recomienda ya que el SII lo puede pedir
                        ]; // este es un ejemplo de una venta, obviamente puede ser cualquier tipo de asiento contable
                                // incluir autocarga de composer

                                // crear cliente
                        $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                        // crear asiento
                        $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$emisor, $datos);
                        if ($seat['status']['code'] != 200) {
                            exit('Error al crear el asiento contable: '.$seat['body']."\n");
                        }

                        $email = Email::find(1);
                        $user = User::where('rut', $dte->rut)->first();
                        $client_name = $user->names;
                        $branch_office_name = $branch_office->branch_office;
                        $folio = $dte->folio;
                        $payment_date = $dte->payment_date;
                        $email = Email::find(1);
                        $body = $email->email;
                        $supervisor = User::where('rut', $branch_office->supervisor_id)->first();
                        $search = ['$client_name', '$branch_office', '$folio', '$auth_code', '$payment_date', '$amount'];
                        $replace = [$client_name, $branch_office_name, $folio, $data->detailOutput->authorizationCode, $payment_date, $Cobro->total];
                        $body = str_replace($search, $replace, $body);
                        $data = ['email' => 'jesus_cova88@hotmail.com', 'subject' => $email->subject, 'body' => $body];

                        Mail::send([], $data, function ($message) use ($data) {
                            $message->to($data['email'])
                            ->subject($data['subject'])
                            ->setBody($data['body'], 'text/html');
                        });

                        // check for failures
                        if (count(Mail::failures()) > 0) {
                            $errors = 'Failed to send password reset email, please try again.';
                            exit();
                        }
                    }
                }
            }
        } else {
            $id = $request->dte_id;
            $dte = Dte::find($id);
            $dte->payment_type_id = $request->payment_type_id;
            $dte->payment_date = $request->payment_date;
            $dte->comment = $request->comment;
            $dte->status_id = 19;
            $dte->save();
        }

        return $this->successResponse($dte);
    }

    // función que verifica las credenciales
    public function auth_check($user, $pass)
    {
        if (! $user && ! $pass) {
            return true;
        }
        $headers = $this->apache_request_headers();
        if (empty($headers['Authorization'])) {
            return false;
        }
        [$basic, $Authorization] = explode(' ', $headers['Authorization']);
        [$u, $p] = explode(':', base64_decode($Authorization));

        return $u == $user && $p == $pass;
    }

    public function apache_request_headers()
    {
        $arh = [];
        $rx_http = '/\AHTTP_/';
        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = [];
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    }
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }

        return  $arh;
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
            if ($request->quantity == '' || $request->quantity == null) {
                $quantity = 1;
            } else {
                $quantity = $request->quantity;
            }
            $dte_type_id = $request->dte_type_id;
            $branch_office_id = $request->branch_office_id;
            $rut = strtoupper($request->rut);
            $dte = new Dte;
            $dte->rut = $rut;
            $dte->branch_office_id = $branch_office_id;
            $dte->dte_type_id = $dte_type_id;
            $dte->dte_version_id = 1;
            $dte->amount = $request->amount * $quantity;
            $dte->expense_type_id = 25;
            $dte->status_id = 24;
            $dte->period = date('m-Y');
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
                $customer->activity = $request->activity;
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

            if ($request->quantity == '' || $request->quantity == null) {
                $quantity = 1;
            } else {
                $quantity = $request->quantity;
            }
            // se obtiene los datos para la factura.
            $dte_type_id = $request->dte_type_id;
            $branch_office_id = $request->branch_office_id;
            $rut = strtoupper($request->rut);
            $client = $request->client;
            $address = $request->address;
            $email = $request->email;
            $amount = $request->amount;
            $branch_office = BranchOffice::find($branch_office_id);
            $dte_code = $branch_office->dte_code;
            $description = 'Prestación de estacionamientos';
            $commune = Commune::find($request->commune_id);

            // Se utiliza la API para generar el temporal de la boleta o factura.
            if ($dte_type_id == 33) {
                $dte = [
                    'Encabezado' => [
                        'IdDoc' => [
                            'TipoDTE' => $dte_type_id,
                            'MntBruto' => 1,
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
            $folio = $generar['body']['folio'];
            $dte = new Dte;
            $dte->rut = strtoupper($rut);
            $dte->folio = $folio;
            $dte->branch_office_id = $branch_office_id;
            $dte->dte_type_id = $dte_type_id;
            $dte->dte_version_id = 1;
            $dte->amount = $request->amount * $quantity;
            $dte->expense_type_id = 25;
            $dte->temporal_code = $temporal_code;
            $dte->status_id = 18;
            $dte->period = date('m-Y');
            $dte->created_at = date('Y:m:d 00:00:00');
            $dte->save();

            // Si no existe el cliente lo creamos.
            $client_qty = Customer::where('rut', $rut)->count();
            if ($client_qty == 0) {
                $customer = new Customer;
                $customer->rut = $rut;
                $customer->address = $request->address;
                $customer->email = $email;
                $customer->activity = $request->activity;
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
            $rut = strtoupper($request->rut);
            $customer = Customer::find($rut);
            $user = User::find($rut);
            $client = $request->client;
            $address = $customer->address;
            $email = $user->email;
            $amount = $request->amount;
            $branch_office = BranchOffice::find($branch_office_id);
            $dte_code = $branch_office->dte_code;
            $description = 'Prestación de estacionamientos';
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
    public function reject(Request $request)
    {
        $id = $request->segment(4);
        $dte = DTE::find($id);
        $dte->status_id = 14;
        $dte->save();

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
        if ($this->user->rol_id == 1) {
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
            $description = 'Prestación de estacionamientos';
            $commune = '';

            // Se utiliza la API para generar el temporal de la boleta o factura.
            if ($dte_type_id == 33) {
                $dte = [
                    'Encabezado' => [
                        'IdDoc' => [
                            'TipoDTE' => $dte_type_id,
                            'MntBruto' => 1,
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
            if ($dte->save()) {
                if ($dte->dte_version_id == 1) {
                    if ($dte->dte_type_id == 33) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $date = explode(' ', $dte->created_at);
                        $date = explode('-', $date[0]);
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
                                    $accounting_account => round($amount / 1.19), // venta abonados neto
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
                        $date = explode(' ', $dte->created_at);
                        $date = explode('-', $date[0]);
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
                                    $accounting_account => round($amount / 1.19),
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
                        $date = explode(' ', $dte->created_at);
                        $date = explode('-', $date[0]);
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
                                    $accounting_account => round($amount / 1.19), // venta abonados neto
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
                        $date = explode(' ', $dte->created_at);
                        $date = explode('-', $date[0]);
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
                        $date = explode(' ', $dte->created_at);
                        $date = explode('-', $date[0]);
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
                        if ($seat['status']['code'] != 200) {
                            exit('Error al crear el asiento contable: '.$seat['body']."\n");
                        }
                    } elseif ($dte->dte_type_id == 56) {
                        $branch_office = BranchOffice::find($dte->branch_office_id);
                        $date = explode(' ', $dte->created_at);
                        $date = explode('-', $date[0]);
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
                        $date = explode(' ', $dte->created_at);
                        $date = explode('-', $date[0]);
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
        if (count($splits) > 1) {
            $id = $request->dte_id;
            $dte = Dte::find($id);
            if ($splits[0]->split_comment != '') {
                $dte->comment = $splits[0]->split_comment;
            }
            $dte->period = $splits[0]->split_period;
            $dte->amount = $splits[0]->split_amount;
            $dte->branch_office_id = $request->branch_office_id;
            $dte->expense_type_id = $request->expense_type_id;
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
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                        $utf8_date = explode('-', $new_dte->period);
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                        $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
                        $date = $dte->period;
                        $date = $date.'-01';
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
            for ($i = 1; $i < count($splits); $i++) {
                $new_dte = new Dte;
                $new_dte->rut = $dte->rut;
                $new_dte->folio = $dte->folio;
                $new_dte->branch_office_id = $dte->branch_office_id;
                $new_dte->dte_type_id = $dte->dte_type_id;
                $new_dte->expense_type_id = $request->expense_type_id;
                $new_dte->dte_version_id = $dte->dte_version_id;
                if ($new_dte->dte_version_id == 1) {
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
                if ($new_dte->save()) {
                    if ($new_dte->dte_version_id == 1) {
                        if ($new_dte->dte_type_id == 33) {
                            $branch_office = BranchOffice::find($new_dte->branch_office_id);
                            $utf8_date = explode('-', $new_dte->period);
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
                            $utf8_date = '01'.'-'.$utf8_date[1].'-'.$utf8_date[0];
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
        if ($envio['status']['code'] != 200) {
            exit('Error al enviar el correo del DTE emitido: '.$envio['body']."\n");
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
        if ($request->rut != null) {
            echo 1;
            // datos a utilizar
            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
            $rut = '76063822-6';
            $until = date('Y-m-d');
            $since = date('Y-m-d', strtotime($until.'- 500 days'));
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
                        $dte->amount = $search['body'][$i]['total'];
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
            $since = date('Y-m-d', strtotime($until.'- 30 days'));
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

            ////////////

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
                'dte' => ['61'],
                'fecha_desde' => $since,
                'fecha_hasta' => $until,
            ];
            $search = $LibreDTE->post('/dte/dte_emitidos/buscar/'.$rut, $data);
            if ($search['status']['code'] != 200) {
                exit('Error al realizar la búsqueda de DTEs emitidos: '.$search['body']."\n");
            }

            for ($i = 0; $i < count($search['body']); $i++) {
                $s = 1;
                $r = $search['body'][$i]['receptor'];
                for ($m = 0; $r != 0; $r /= 10) {
                    $s = ($s + $r % 10 * (9 - $m++ % 6)) % 11;
                }
                $index = chr($s ? $s + 47 : 75);
                $rut = $search['body'][$i]['receptor'].'-'.$index;
                $user_qty = User::where('rut', $search['body'][$i]['receptor'])->count();
                if ($user_qty == 0) {
                    $user = new User;
                    $user->rut = $search['body'][$i]['receptor'].'-'.$index;
                    $user->names = $search['body'][$i]['razon_social'];
                    $user->rol_id = 14;
                    $user->save();
                    $customer = new Customer;
                    $customer->rut = $search['body'][$i]['receptor'].'-'.$index;
                    $customer->save();
                }

                $dte_qty = Dte::where('folio', $search['body'][$i]['folio'])
                                ->where('dte_version_id', '1')
                                ->where('dte_type_id', '61')
                                ->count();
                if ($dte_qty == 0) {
                    $branch_office = BranchOffice::where('dte_code', $search['body'][$i]['sucursal_sii'])->first();
                    $dte = new Dte;
                    $dte->rut = $search['body'][$i]['receptor'].'-'.$index;
                    $dte->folio = $search['body'][$i]['folio'];
                    $dte->branch_office_id = $branch_office->branch_office_id;
                    $dte->dte_type_id = 61;
                    $dte->amount = $search['body'][$i]['total'] * -1;
                    $dte->dte_version_id = 1;
                    $dte->status_id = 19;
                    $dte->created_at = $search['body'][$i]['fecha'].' 00:00:00';
                    $dte->save();

                    $branch_office = BranchOffice::find($dte->branch_office_id);
                    $detail_utf8_date = explode('-', $search['body'][$i]['fecha']);
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
            }
        }
    }
}
