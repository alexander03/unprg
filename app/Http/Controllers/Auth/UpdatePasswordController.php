<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Usuario;
use App\Http\Controllers\Auth\UpdatePasswordController;

class UpdatePasswordController extends Controller
{
    
    public function showUpdatePasswordForm(){
        return view('app.cambiarpassword.password');
    }    

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updatePassword(Request $request){
 
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return view('app.cambiarpassword.password')->with("error","Su contraseña actual no coincide con la contraseña que proporcionó. Inténtalo de nuevo.");
        }
 
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return view('app.cambiarpassword.password')->with("error","La nueva contraseña no puede ser igual a su contraseña actual. Por favor, elija una contraseña diferente.");
        }
 
        $validatedData = $this->validate($request,[
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
 
        return view('app.cambiarpassword.password')->with("success","Contraseña cambiada satisfactoriamente!");
 
    }
}