<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Patent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client;

class PatentController extends ApiResponseController
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
        $date = $request->segment(5);

        if (($branch_office_id == 'null' && $date == 'null')
        || ($branch_office_id == '' && $date == '')
        ) {
            $patents = Patent::orderBy('month', 'desc')
                        ->with('branch_office')
                        ->orderBy('year', 'desc')
                        ->paginate(10);
        } else {
            $query = '';

            if ($branch_office_id != 'null') {
                $query .= 'branch_office_id = '.$branch_office_id;
            }

            if ($date != 'null') {
                if ($branch_office_id != 'null') {
                    $query .= ' AND ';
                }

                $date = explode('-', $date);
                $query .= 'month = '.$date[1];
                $query .= ' AND ';
                $query .= 'year = '.$date[0];
            }

            $patents = Patent::whereRaw($query)
                        ->orderBy('month', 'desc')
                        ->with('branch_office')
                        ->orderBy('year', 'desc')
                        ->paginate(10);
        }

        return $this->successResponse($patents);
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
        $fileName = time().'_'.'patente'.'.'.$request->file->getClientOriginalExtension();
        $date = explode('-', $request->date);
        $patent = new Patent;
        $patent->branch_office_id = $request->branch_office_id;
        $patent->month = $date[1];
        $patent->year = $date[0];
        $patent->support = $fileName;
        if ($patent->save()) {
            Storage::disk('dropbox')->putFileAs(
                'patents/',
                $request->file,
                $fileName
            );
        }

        return $this->successResponse($patent);
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
        $patent = Patent::find($id);
        if ($patent->delete()) {
            $this->dropbox->delete('patents/'.$patent->support);
        }

        return $this->successResponse($patent);
    }
}
