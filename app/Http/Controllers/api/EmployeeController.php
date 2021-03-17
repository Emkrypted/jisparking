<?php

namespace App\Http\Controllers\api;

use App\User;
use App\Employee;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('rol_id', '!=', 9)
                        ->where('rol_id', '!=', 17)
                        ->where('rol_id', '!=', 18)
                        ->orderBy('names', 'asc')
                        ->get();
        
        return $this->successResponse($users);
    }

    public function list(Request $request)
    {
        $branch_office_id = $request->segment(4);

        $employees = Employee::where('branch_office_id', $branch_office_id)
                        ->orderBy('names', 'asc')
                        ->get();
        
        return $this->successResponse($employees);
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
