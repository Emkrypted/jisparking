<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\RolPermission;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Session;

class EnterController extends Controller
{
    /**
     * Retrieve the user data to log into the site.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Take the rut and password inputs.
        $rut = $request->rut;
        $password = md5($request->password);
        /////////////////////
        // Count if there is some user with the given information.
        $user_qty = User::where('rut', $rut)->where('password', $password)->count();
        /////////////////////
        if ($user_qty > 0) {
            // Evaluate the information to check that there is a user with the given information.
            $user = User::with('rol')->where('rut', $rut)->where('password', $password)->first();

            if ($user->status_id == 1 && ($user->rol_id == 1 || $user->rol_id == 4 || $user->rol_id == 11 || $user->rol_id == 16)) {
                $rol_permissions = RolPermission::where('rol_id', $user->rol_id)->get();
                /////////////////////

                // Add the permissions.
                foreach ($rol_permissions as $rol_permission) {
                    $permissions[] = $rol_permission->permission_id;
                }
                Session::put('permissions', $permissions);
                /////////////////////
                // If the user exists it is sent to log in.
                // Login the user.
                if (! Auth::loginUsingId($user->rut, true)) {
                    // Redirect the user to login view if Auth function fails.
                    return redirect('login');
                } else {
                    // Redirect the user to account if it's successfully.
                    return redirect('account');
                }
            } else {
                return redirect('password')->with(['rut' => $user->rut]);
            }
        } else {
            // If there is not a user, redirect this one to login view.
            return redirect('login');
        }
    }
}
