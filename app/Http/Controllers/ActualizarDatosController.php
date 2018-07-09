<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Alumno;
use App\Empresa;
use App\Escuela;
use App\Especialidad;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;

class ActualizarDatosController extends Controller
{

    protected $folderview      = 'app.actualizardatos';
    protected $tituloAdmin     = 'Actualizar Datos';
    protected $tituloModificar = 'Modificar datos';
    protected $rutas           = array(
            'edit'   => 'actualizardatos.edit',
            'index'  => 'actualizardatos.index',
        );

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$entidad          = 'Alumno';

        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;

        $user             = Auth::user();

        if($user->alumno_id !== null){

            $entidad          = 'Alumno';
            $alumno        = Alumno::find($user->alumno_id);
            $existe = Libreria::verificarExistencia($user->alumno_id, 'alumno');
            if ($existe !== true) {
                return $existe;
            }
            $listar         = Libreria::getParam($request->input('listar'), 'NO');
            $cboEscuela = array('' => 'Seleccione') + Escuela::pluck('nombre', 'id')->all();
            $cboEspecialidad = array('' => 'Seleccione') + Especialidad::pluck('nombre', 'id')->all();

            $formData       = array('actualizardatos.update', $user->alumno_id);
            $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
            $boton          = 'Modificar';

            return view($this->folderview.'.user')->with(compact('alumno', 'title', 'ruta', 'formData', 'entidad', 'boton', 'listar', 'cboEscuela','cboEspecialidad'));

        }else if($user->empresa_id !== null){

            $entidad          = 'Empresa';
            $empresa       = Empresa::find($user->empresa_id);
            $existe = Libreria::verificarExistencia($user->empresa_id, 'empresa');
            if ($existe !== true) {
                return $existe;
            }
            $listar         = Libreria::getParam($request->input('listar'), 'NO');
            $formData       = array('actualizardatos.update', $user->empresa_id);
            $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
            $boton          = 'Modificar';
            return view($this->folderview.'.user')->with(compact('empresa','title', 'ruta','formData', 'entidad', 'boton', 'listar'));
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {

        $user             = Auth::user();

        if($user->alumno_id !== null){

            $existe = Libreria::verificarExistencia($id, 'alumno');
            if ($existe !== true) {
                return $existe;
            }
            $reglas = array(
                'dni' => 'required|max:8',
                'fechanacimiento' => 'required|max:100',
                'direccion' => 'required|max:50',
                'telefono' => 'required|max:12',
                'especialidad_id' => 'required|integer|exists:especialidad,id,deleted_at,NULL',
                );
            $validacion = Validator::make($request->all(),$reglas);
            if ($validacion->fails()) {
                return $validacion->messages()->toJson();
            } 
            $error = DB::transaction(function() use($request, $id){
                $alumno                 = Alumno::find($id);
                $alumno->dni     = $request->input('dni');
                $alumno->fechanacimiento     = $request->input('fechanacimiento');
                $alumno->direccion     = $request->input('direccion');
                $alumno->telefono     = $request->input('telefono');
                $alumno->email     = $request->input('email');
                $alumno->especialidad_id    = $request->input('especialidad_id');
                $alumno->save();
            });
            return is_null($error) ? "OK" : $error;

        }else if($user->empresa_id !== null){
            
            $existe = Libreria::verificarExistencia($id, 'empresa');
            if ($existe !== true) {
                return $existe;
            }

            $reglas = array(
                'razonsocial'    => 'required|max:200',
                'direccion' => 'required|max:120',
                'telefono'   => 'required|numeric|digits:9',
                );
            $validacion = Validator::make($request->all(),$reglas);
            if ($validacion->fails()) {
                return $validacion->messages()->toJson();
            } 
            $error = DB::transaction(function() use($request, $id){
                $empresa               = Empresa::find($id);
                $empresa->razonsocial  = $request->input('razonsocial');
                $empresa->direccion    = $request->input('direccion');
                $empresa->telefono     = $request->input('telefono');
                $empresa->save();
            });
            return is_null($error) ? "OK" : $error;

        }

    }

   
}