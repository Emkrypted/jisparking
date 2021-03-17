<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class OutController extends Controller
{
    /**
     * Close the session to the use.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        if (Auth::logout()) {
            return redirect('login');
        } else {
            return redirect('account');
        }
    }
}
