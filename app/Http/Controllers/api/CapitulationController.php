<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Capitulation;
use App\ExpenseType;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CapitulationController extends ApiResponseController
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
        // It gets the information sent by the url path.
        $rut = $request->segment(4);
        $expense_type_id = $request->segment(5);
        $since = $request->segment(6);
        $until = $request->segment(7);
        $status_id = $request->segment(8);
        // It checks if any of them exist or they are nulled.
        if (($rut == 'null' && $expense_type_id == 'null' && $since == '' && $until == '' && $status_id == '')
        || ($rut == '' && $expense_type_id == '' && $since == '' && $until == '' && $status_id == '')
        ) {
            // It checks if the user is an administrator.
            if ($this->user->rol_id != 1) {
                // It check if the user is rol_id = 11 because it means that it's the manager.
                if ($this->user->rol_id == 11) {
                    // This data is displayed all group by amount.
                    $capitulations = Capitulation::from('capitulations as c')
                            ->selectRaw('users.rut as rut, users.names as names, SUM(c.amount) as amount')
                            ->leftJoin('users', 'users.rut', '=', 'c.rut')
                            ->orderBy('c.status_id', 'desc')
                            ->where('c.status_id', '7')
                            ->where('c.capitulation_type_id', '1')
                            ->groupBy('users.rut')
                            ->paginate(10);
                } else {
                    // It returns the capitulations of a specific user.
                    $capitulations = Capitulation::from('capitulations as c')
                            ->selectRaw('c.capitulation_id as capitulation_id, users.names as names, c.capitulation_type_id as capitulation_type_id, expense_types.expense_type as expense_type, c.description as description, c.amount as amount, c.document_date as document_date, c.status_id as status_id, statuses.status as status')->leftJoin('expense_types', 'expense_types.expense_type_id', '=', 'c.expense_type_id')
                            ->where('c.rut', $this->user->rut)
                            ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                            ->leftJoin('users', 'users.rut', '=', 'c.rut')
                            ->orderBy('c.document_date', 'desc')
                            ->orderBy('c.status_id', 'desc')
                            ->paginate(10);
                }
            } else {
                // It returns all the capitulations with status_id = 11.
                $capitulations = Capitulation::from('capitulations as c')
                            ->selectRaw('c.capitulation_id as capitulation_id, users.names as names, c.capitulation_type_id as capitulation_type_id, expense_types.expense_type as expense_type, c.description as description, c.amount as amount, c.document_date as document_date, c.status_id as status_id, statuses.status as status')
                            ->leftJoin('expense_types', 'expense_types.expense_type_id', '=', 'c.expense_type_id')
                            ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                            ->where('c.status_id', 11)
                            ->leftJoin('users', 'users.rut', '=', 'c.rut')
                            ->orderBy('c.status_id', 'asc')
                            ->paginate(10);
            }
        } else {
            // If they are not empty, it's necessary to use them to create the database query.
            $query = '';
            // It checks every single variable to see if it's nulled or not.
            if ($rut != 'null') {
                $query .= 'c.rut = "'.$rut.'"';
            }
            // It checks every single variable to see if it's nulled or not.
            if ($expense_type_id != 'null') {
                if ($rut != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.expense_type_id = '.$expense_type_id;
            }
            // It checks every single variable to see if it's nulled or not.
            if ($since != 'null') {
                if ($rut != 'null' || $expense_type_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= '(c.created_at >= "'.$since.' 00:00:00")';
            }
            // It checks every single variable to see if it's nulled or not.
            if ($until != 'null') {
                if ($rut != 'null' || $expense_type_id != 'null' || $since != 'null') {
                    $query .= ' AND ';
                }

                $query .= '(c.created_at <= "'.$until.' 23:59:59")';
            }
            // It checks every single variable to see if it's nulled or not.
            if ($status_id != 'null') {
                if ($rut != 'null' || $expense_type_id != 'null' || $since != 'null' || $until != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.status_id = '.$status_id;
            }
            // It checks if the user is a manager, because it must return the data with a group by.
            if ($this->user->rol_id == 11) {
                $capitulations = Capitulation::from('capitulations as c')
                            ->whereRaw($query)
                            ->selectRaw('c.capitulation_id as capitulation_id, users.names as names, c.capitulation_type_id as capitulation_type_id, expense_types.expense_type as expense_type, c.description as description, c.amount as amount, c.document_date as document_date, c.status_id as status_id, statuses.status as status, c.created_at as created_at')
                            ->leftJoin('expense_types', 'expense_types.expense_type_id', '=', 'c.expense_type_id')
                            ->leftJoin('users', 'users.rut', '=', 'c.rut')
                            ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                            ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                            ->orderBy('c.status_id', 'desc')
                            ->where('c.status_id', '7')
                            ->where('c.capitulation_type_id', '1')
                            ->paginate(500);
            } else {
                $capitulations = Capitulation::from('capitulations as c')
                            ->whereRaw($query)
                            ->selectRaw('c.capitulation_id as capitulation_id, users.names as names, c.capitulation_type_id as capitulation_type_id, expense_types.expense_type as expense_type, c.description as description, c.amount as amount, c.document_date as document_date, c.status_id as status_id, statuses.status as status')
                            ->leftJoin('expense_types', 'expense_types.expense_type_id', '=', 'c.expense_type_id')
                            ->leftJoin('statuses', 'statuses.status_id', '=', 'c.status_id')
                            ->leftJoin('branch_offices', 'branch_offices.branch_office_id', '=', 'c.branch_office_id')
                            ->leftJoin('users', 'users.rut', '=', 'c.rut')
                            ->orderBy('c.document_date', 'desc')
                            ->paginate(10);
            }
        }

        return $this->successResponse($capitulations);
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
        // It stores a capitulation in the table.
        $capitulation = new Capitulation;
        $capitulation->capitulation_type_id = $request->capitulation_type_id;
        $capitulation->branch_office_id = $request->branch_office_id;
        $capitulation->expense_type_id = $request->expense_type_id;
        $capitulation->dte_type_id = $request->dte_type_id;
        $capitulation->document_number = $request->document_number;
        $capitulation->status_id = 11;
        $capitulation->rut = $this->user->rut;
        $capitulation->description = $request->description;
        $capitulation->document_date = $request->document_date;
        $capitulation->amount = $request->amount;
        // It uploads a capitulation imagen on dropbox.
        if ($request->file('file') != '') {
            $fileName = time().'_'.'rendicion'.'_'.$request->document_date.'_'.$request->capitulation_type_id.'.'.$request->file->getClientOriginalExtension();
            $capitulation->support = $fileName;
            Storage::disk('dropbox')->putFileAs(
                'capitulations/',
                $request->file,
                $fileName
            );
        }
        $capitulation->save();
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
        $capitulation = Capitulation::find($id);

        return $this->successResponse($capitulation);
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
        $capitulation = Capitulation::find($id);
        // It checks if it's not empty.
        if ($request->document_date != '') {
            $capitulation->document_date = $request->document_date;
        }
        // It checks if it's not empty.
        if ($request->dte_type_id != '') {
            $capitulation->dte_type_id = $request->dte_type_id;
        }
        // It checks if it's not empty.
        if ($request->capitulation_type_id != '') {
            $capitulation->capitulation_type_id = $request->capitulation_type_id;
        }
        // It checks if it's not empty.
        if ($request->branch_office_id != '') {
            $capitulation->branch_office_id = $request->branch_office_id;
        }
        // It checks if it's not empty.
        if ($request->expense_type_id != '') {
            $capitulation->expense_type_id = $request->expense_type_id;
        }
        // It checks if it's not empty.
        if ($request->description != '') {
            $capitulation->description = $request->description;
        }
        // It checks if it's not empty.
        if ($request->amount != '') {
            $capitulation->amount = $request->amount;
        }
        // It checks if it's not empty.
        if ($request->status_id != '') {
            $capitulation->status_id = $request->status_id;
        }
        // It checks if it's not empty.
        if ($request->period != '') {
            $capitulation->impute_period = $request->period;
        }
        // It checks if it's not empty. If it is not it stores the a capitulation picture on dropbox.
        if ($request->file('file') != '') {
            $fileName = time().'_'.'rendicion'.'_'.$request->document_date.'_'.$request->capitulation_type_id.'.'.$request->file->getClientOriginalExtension();
            $oldFileName = $deposit->support;
            $deposit->support = $fileName;
            $this->dropbox->delete('capitulations/'.$oldFileName);
            Storage::disk('dropbox')->putFileAs(
                'capitulations/',
                $request->file,
                $fileName
            );
        }
        if ($capitulation->save()) {
            // It's necessary to create for every update a new accounting seat.
            $period = $request->period.'-01';
            $branch_office = BranchOffice::find($request->branch_office_id);
            $expense_type = ExpenseType::find($request->expense_type_id);
            $explode_period = explode('-', $request->period);
            $utf8_date = '01-'.$explode_period[1].'-'.$explode_period[0];
            if ($capitulation->capitulation_type_id == 2) {
                $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_GastoRendido_'.$capitulation->capitulation_id;
            } else {
                $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_FondoRendido_'.$capitulation->capitulation_id;
            }
            if ($capitulation->capitulation_type_id == 2) {
                if ($request->dte_type_id == 33) {
                    // It connects to LibreDTE API.
                    $url = 'https://libredte.cl';
                    $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                    $creator = '76063822-6';
                    $data = [
                        'fecha' => $period,
                        'glosa' => $message,
                        'detalle' => [
                            'debe' => [
                                $expense_type->accounting_account => $request->amount,
                            ],
                            'haber' => [
                                111000102 => round($request->amount / 1.19), // venta abonados neto
                                221000226 => round($request->amount - ($request->amount / 1.19)), // iva débito
                            ],
                        ],
                        'operacion' => 'I',
                        'documentos' => ['emitidos'=>[['dte'=>'', 'folio'=>$capitulation->capitulation_id]]],
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
                        'fecha' => $period,
                        'glosa' => $message,
                        'detalle' => [
                            'debe' => [
                                $expense_type->accounting_account => $request->amount,
                            ],
                            'haber' => [
                                111000102 => $request->amount,
                            ],
                        ],
                        'operacion' => 'I',
                        'documentos' => ['emitidos'=>[['dte'=>'', 'folio'=>$capitulation->capitulation_id]]],
                    ];
                    print_r($data);

                    $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                    $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                    if ($seat['status']['code'] != 200) {
                        exit('Error al crear el asiento contable: '.$seat['body']."\n");
                    }
                }
            }
        }

        return $this->successResponse($capitulation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $capitulation = Capitulation::find($id);
        $fileName = $capitulation->support;
        if ($capitulation->delete()) {
            $this->dropbox->delete('capitulations/'.$fileName);
        }

        return $this->successResponse($capitulation);
    }

    /**
     * Get the image of the support from Dropbox.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function support(Request $request)
    {
        // It connects to dropbox to find a file to return it.
        $capitulation_id = $request->segment(4);
        $capitulation = Capitulation::where('capitulation_id', $capitulation_id)->first();
        $dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
        $filename = $capitulation->support;
        $image = $dropbox->getTemporaryLink('capitulations/'.$filename);

        return $this->successResponse($image);
    }

    /**
     * Check the capitulation data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        // It allows to review every single capitulation. It reads the array which it comes from the form input.
        $selected = $request->input('selected');
        $selected = explode(',', $selected);
        for ($i = 0; $i < count($selected); $i++) {
            $capitulation = Capitulation::find($selected[$i]);
            $capitulation->status_id = 17;
            $capitulation->payment_type_id = $request->payment_type_id;
            $capitulation->payment_date = $request->payment_date;
            $capitulation->payment_comment = $request->payment_comment;
            if ($capitulation->save()) {
                // If it's ok it creates a new seat in accounting.
                $period = $capitulation->impute_period.'-01';
                $branch_office = BranchOffice::find($capitulation->branch_office_id);
                $expense_type = ExpenseType::find($capitulation->expense_type_id);
                $explode_period = explode('-', $capitulation->impute_period);
                $utf8_date = '01-'.$explode_period[1].'-'.$explode_period[0];
                if ($capitulation->capitulation_type_id == 2) {
                    $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_GastoRendido_'.$capitulation->capitulation_id;
                } else {
                    $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_FondoRendido_'.$capitulation->capitulation_id;
                }
                // it checks if the DTE = 33.
                if ($capitulation->dte_type_id == 33) {
                    $url = 'https://libredte.cl';
                    $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                    $creator = '76063822-6';
                    $data = [
                        'fecha' => $period,
                        'glosa' => $message,
                        'detalle' => [
                            'debe' => [
                                $expense_type->accounting_account => $capitulation->amount,
                            ],
                            'haber' => [
                                111000102 => round($capitulation->amount / 1.19), // venta abonados neto
                                221000226 => round($capitulation->amount - ($capitulation->amount / 1.19)), // iva débito
                            ],
                        ],
                        'operacion' => 'I',
                        'documentos' => ['emitidos'=>[['dte'=>'', 'folio'=>$capitulation->capitulation_id]]],
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
                        'fecha' => $period,
                        'glosa' => $message,
                        'detalle' => [
                            'debe' => [
                                $expense_type->accounting_account => $capitulation->amount,
                            ],
                            'haber' => [
                                111000102 => $capitulation->amount,
                            ],
                        ],
                        'operacion' => 'I',
                        'documentos' => ['emitidos'=>[['dte'=>'', 'folio'=>$capitulation->capitulation_id]]],
                    ];
                    print_r($data);

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
     * Pay a capitulation to the user. It changes he status in the table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request)
    {
        // It returns the data with status_id in the capitulation table.
        $capitulations = Capitulation::where('rut', $request->segment(4))
                                    ->where('status_id', '7')->get();

        foreach ($capitulations as $capitulation) {
            // It changes the status to 17.
            $capitulation_detail = Capitulation::where('capitulation_id', $capitulation->capitulation_id)
                                    ->where('status_id', '7')->first();
            $capitulation_detail->status_id = 17;
            if ($capitulation_detail->save()) {
                // If it's ok it creates an accounting seat.
                $period = $capitulation_detail->impute_period.'-01';
                $branch_office = BranchOffice::find($capitulation_detail->branch_office_id);
                $expense_type = ExpenseType::find($capitulation_detail->expense_type_id);
                $explode_period = explode('-', $capitulation_detail->impute_period);
                $utf8_date = '01-'.$explode_period[1].'-'.$explode_period[0];
                if ($capitulation_detail->capitulation_type_id == 2) {
                    $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_GastoRendido_'.$capitulation_detail->capitulation_id;
                } else {
                    $message = $branch_office->branch_office.'_'.$expense_type->accounting_account.'_'.$utf8_date.'_FondoRendido_'.$capitulation_detail->capitulation_id;
                }
                if ($capitulation_detail->dte_type_id == 33) {
                    $url = 'https://libredte.cl';
                    $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
                    $creator = '76063822-6';
                    $data = [
                        'fecha' => $period,
                        'glosa' => $message,
                        'detalle' => [
                            'debe' => [
                                $expense_type->accounting_account => $capitulation_detail->amount,
                            ],
                            'haber' => [
                                111000102 => round($capitulation_detail->amount / 1.19), // venta abonados neto
                                221000226 => round($capitulation_detail->amount - ($capitulation_detail->amount / 1.19)), // iva débito
                            ],
                        ],
                        'operacion' => 'I',
                        'documentos' => ['emitidos'=>[['dte'=>'', 'folio'=>$capitulation_detail->capitulation_id]]],
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
                        'fecha' => $period,
                        'glosa' => $message,
                        'detalle' => [
                            'debe' => [
                                $expense_type->accounting_account => $capitulation_detail->amount,
                            ],
                            'haber' => [
                                111000102 => $capitulation_detail->amount,
                            ],
                        ],
                        'operacion' => 'I',
                        'documentos' => ['emitidos'=>[['dte'=>'', 'folio'=>$capitulation_detail->capitulation_id]]],
                    ];
                    print_r($data);

                    $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
                    $seat = $LibreDTE->post('/lce/lce_asientos/crear/'.$creator, $data);
                    if ($seat['status']['code'] != 200) {
                        exit('Error al crear el asiento contable: '.$seat['body']."\n");
                    }
                }
            }
        }

        return $this->successResponse($capitulation);
    }
}
