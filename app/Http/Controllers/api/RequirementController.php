<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\ExpenseType;
use App\Honorary;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Maintenance;
use App\PettyCash;
use App\Publicity;
use App\Requirement;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client;

class RequirementController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
        $this->user = User::where('api_token', $request->api_token)->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requirements = Requirement::with('user')
                    ->with('status')
                    ->with('requirement_type')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return $this->successResponse($requirements);
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
        if ($request->requirement_type_id == 1) {
            $rut = $request->user_id;
            $status_id = 25;
        } elseif ($request->requirement_type_id == 2) {
            $rut = $request->rut;
            $status_id = 9;
        } elseif ($request->requirement_type_id == 3) {
            $rut = $this->user->rut;
            $status_id = 12;
        } elseif ($request->requirement_type_id == 4) {
            $rut = $this->user->rut;
            $status_id = 12;
        }
        $requirement = new Requirement;
        $requirement->requirement_type_id = $request->requirement_type_id;
        $requirement->rut = $rut;
        $requirement->status_id = $status_id;
        $requirement->step1_date = date('Y-m-d');
        if ($requirement->save()) {
            if ($requirement->requirement_type_id == 1) {
                $petty_cash = new PettyCash;
                $petty_cash->requirement_id = $requirement->requirement_id;
                $petty_cash->rut = $request->user_id;
                $petty_cash->gross_amount = $request->gross_amount;
                $petty_cash->save();
            } elseif ($requirement->requirement_type_id == 2) {
                $honorary = new Honorary;
                $honorary->requirement_id = $requirement->requirement_id;
                $honorary->branch_office_id = $request->branch_office_id;
                $honorary->foreigner_id = $request->foreigner_id;
                $honorary->bank_id = $request->bank_id;
                $honorary->schedule_id = $request->employee_schedule_id;
                $honorary->rut = $request->rut;
                $honorary->reason = $request->reason;
                $honorary->full_name = $request->full_name;
                $honorary->email = $request->email;
                $honorary->address = $request->address;
                $honorary->account_number = $request->account_number;
                $honorary->start_date = $request->start_date;
                $honorary->end_date = $request->end_date;
                $honorary->employee_to_replace = $request->employee_to_replace;
                $honorary->save();
            } elseif ($requirement->requirement_type_id == 3) {
                $fileName = time().'_'.'requerimiento'.'.'.$request->file->getClientOriginalExtension();
                $maintenance = new Maintenance;
                $maintenance->requirement_id = $requirement->requirement_id;
                $maintenance->branch_office_id = $request->branch_office_id;
                $maintenance->reason = $request->reason;
                $maintenance->evidence = $fileName;
                if ($maintenance->save()) {
                    Storage::disk('dropbox')->putFileAs(
                        'requirements/',
                        $request->file,
                        $fileName
                    );
                }
            } elseif ($requirement->requirement_type_id == 4) {
                $publicity = new Publicity;
                $publicity->requirement_id = $requirement->requirement_id;
                $publicity->branch_office_id = $request->branch_office_id;
                $publicity->reason = $request->reason;
                $publicity->save();
            }
        }

        return $this->successResponse($requirement);
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
        $requirement = Requirement::find($id);

        return $this->successResponse($requirement);
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
        $requirement = Requirement::find($id);

        if ($requirement->requirement_type_id == 1) {
            $requirement->step2_date = date('Y-m-d');
            $requirement->status_id = 17;
            ///////////
            $fileName = time().'_'.'requerimiento'.'.'.$request->file->getClientOriginalExtension();
            $pettycash = PettyCash::where('requirement_id', $id)->first();
            $pettycash->supplier_rut = $pettycash->supplier_rut;
            $pettycash->cost = $pettycash->amount;
            $pettycash->support = $fileName;
            if ($pettycash->save()) {
                Storage::disk('dropbox')->putFileAs(
                    'requirements/',
                    $request->file,
                    $fileName
                );
            }
        } elseif ($requirement->requirement_type_id == 2) {
            $requirement->status_id = 27;

            $honorary = Honorary::where('requirement_id', $id)->first();
            $honorary->amount = $request->amount;
            $honorary->save();

            $url = 'https://libredte.cl';
            $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';

            $dte = [
                'Encabezado' => [
                    'IdDoc' => [
                        'FchEmis' => date('Y-m-d'),
                    ],
                    'Emisor' => [
                        'RUTEmisor' => '76063822-6',
                    ],
                    'Receptor' => [
                        'RUTRecep' => $honorary->rut,
                        'RznSocRecep' => $honorary->full_name,
                        'DirRecep' =>  $honorary->address,
                    ],
                ],
                'Detalle' => [
                    [
                        'NmbItem' => 'Boleta de Honorarios para '.$honorary->full_name,
                        'MontoItem' => $request->amount,
                    ],
                ],
            ];

            // incluir autocarga de composer

            // crear cliente
            $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
            // $LibreDTE->setSSL(false, false); ///< segundo parámetro =false desactiva verificación de SSL
            // crear DTE temporal
            $emitir = $LibreDTE->post('/dte/boleta_terceros/emitir', $dte);
            if ($emitir['status']['code'] != 200) {
                exit('Error al emitir la boleta a terceros: '.$emitir['body']."\n");
            }

            $request->expense_type_id = 38;
        } elseif ($requirement->requirement_type_id == 3) {
            if ($request->end == '') {
                $requirement->step2_date = date('Y-m-d');
                $requirement->status_id = 26;
                ///////////
                $maintenance = Maintenance::where('requirement_id', $id)->first();
                $maintenance->amount = $request->amount;
                $maintenance->start_date = $request->start_date;
                $maintenance->end_date = $request->end_date;
                $maintenance->save();
            } else {
                $requirement->step3_date = date('Y-m-d');
                $requirement->status_id = 27;
                ///////////
                $fileName = time().'_'.'requerimiento'.'.'.$request->file->getClientOriginalExtension();
                $maintenance = Maintenance::where('requirement_id', $id)->first();
                $maintenance->support = $fileName;
                if ($maintenance->save()) {
                    Storage::disk('dropbox')->putFileAs(
                        'requirements/',
                        $request->file,
                        $fileName
                    );
                }
            }
            $request->expense_type_id = 9;
        } elseif ($requirement->requirement_type_id == 4) {
            if ($request->end == '') {
                $requirement->step2_date = date('Y-m-d');
                $requirement->status_id = 26;
                ///////////
                $publicity = Publicity::where('requirement_id', $id)->first();
                $publicity->amount = $request->amount;
                $publicity->start_date = $request->start_date;
                $publicity->end_date = $request->end_date;
                $publicity->save();
            } else {
                $requirement->step3_date = date('Y-m-d');
                $requirement->status_id = 27;
                ///////////
                $fileName = time().'_'.'requerimiento'.'.'.$request->file->getClientOriginalExtension();
                $publicity = Publicity::where('requirement_id', $id)->first();
                $publicity->support = $fileName;
                if ($publicity->save()) {
                    Storage::disk('dropbox')->putFileAs(
                        'requirements/',
                        $request->file,
                        $fileName
                    );
                }
            }
            $request->expense_type_id = 9;
        }

        $requirement->save();

        if ($request->end == '') {
            $period = date('m-Y');
            $date = explode('-', $period);
            $utf8_date = '01-'.$period;
            $date = $date[1].'-'.$date[0].'-01';
            $branch_office = BranchOffice::find($request->branch_office_id);
            $expense_type = ExpenseType::find($request->expense_type_id);
            $accounting_account = $expense_type->accounting_account;
            $amount = $request->amount;
            $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_FacturaCompra_'.$requirement->requirement_id;

            if ($request->is_bill == 1) {
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
        }

        return $this->successResponse($requirement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requirement = Requirement::find($id);
        $requirement->delete();

        return $this->successResponse($requirement);
    }
}
