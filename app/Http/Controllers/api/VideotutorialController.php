<?php

namespace App\Http\Controllers\api;

use App\BranchOffice;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use App\User;
use App\Videotutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideotutorialController extends ApiResponseController
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
        $videotutorials = Videotutorial::orderBy('videotutorial_id', 'desc')
                                ->paginate(10);

        return $this->successResponse($videotutorials);
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
        $videotutorial = new Videotutorial;
        $videotutorial->title = $request->title;
        $videotutorial->iframe = $request->iframe;
        $videotutorial->save();

        return $this->successResponse($videotutorial);
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
        $videotutorial = Videotutorial::find($id);

        return $this->successResponse($videotutorial);
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
        $videotutorial = Induction::find($id);
        $videotutorial->delete();

        return $this->successResponse($videotutorial);
    }
}
