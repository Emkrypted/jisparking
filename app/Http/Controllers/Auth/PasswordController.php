<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Session;

class PasswordController extends Controller
{
    /**
     * Retrieve the user data to log into the site.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rut = request()->session()->get('rut');

        return view('auth.password', compact('rut'));
    }

     /**
     * Retrieve the user data to log into the site.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rut = $request->rut;

        $user = User::where('rut', $rut)->first();
        $user->password = md5($request->password);
        $user->status_id = 1;
        $user->save();

        return redirect('login');
    }
}
