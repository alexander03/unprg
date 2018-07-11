<?php

namespace App\Http\Controllers\Auth;

use App\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Empresa;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectPath = '/bolsa/login';
    protected $redirectTo = '/bolsa/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(){
        return view('auth.registrarEmpresa');
    }

    //Handles registration request for seller
    public function register(Request $request)
    {

       //Validates data
        $this->validator($request->all())->validate();

       //Create seller
        $seller = $this->create($request->all());

        //Authenticates seller
        $this->guard()->login($seller);

       //Redirects sellers
        return redirect($this->redirectPath);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'ruc'       => 'required|numeric|digits:11|unique:empresa',
            'razonsocial'    => 'required|string|max:200|unique:empresa',
            'direccion' => 'required|string|max:255',
            'telefono'   => 'required|numeric|digits:9',
            'email' => 'required|email|max:255|unique:usuario',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    protected function create(array $data)
    {
        
        DB::transaction(function() use($data){
            $empresa               = new Empresa();
            $empresa->ruc          = $data['ruc'];
            $empresa->razonsocial  = $data['razonsocial'];
            $empresa->direccion    = $data['direccion'];
            $empresa->telefono     = $data['telefono'];
            $empresa->email        = $data['email'];
            $empresa->save();
        });

        $empresaa = DB::table('empresa')->where('ruc', '=', $data['ruc'])->first();
        $usertype = 4;

        return Usuario::create([
            'login' => $data['ruc'],
            'email' => $data['email'],
            'usertype_id' => $usertype ,
            'empresa_id' => $empresaa->id ,
            'state' => 'H' ,
            'password' => bcrypt($data['password']),
        ]);
    }

    //Get the guard to authenticate Usuario
    protected function guard()
    {
        return Auth::guard('web_usuario');
    }
}
