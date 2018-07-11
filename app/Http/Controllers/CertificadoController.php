<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Certificado; 
use App\CompetenciaAlumno; 
use App\Binnacle;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Image;

class CertificadoController extends Controller
{
    //
    protected $folderview      = 'app.certificado';
    protected $tituloAdmin     = 'Certificados / Estudios';
    protected $tituloRegistrar = 'Registrar Certificado';
    protected $tituloModificar = 'Modificar Certificado';
    protected $tituloEliminar  = 'Eliminar Certificado';
    protected $rutas           = array('create' => 'certificado.create', 
            'edit'     => 'certificado.edit', 
            'delete'   => 'certificado.eliminar',
            'search'   => 'certificado.buscar',
            'index'    => 'certificado.index'
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'certificado';
        $name             = Libreria::getParam($request->input('name'));
        $resultado        = Certificado::listar($name);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Em. Certificadora/Estudios', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    public function index()
    {
        $entidad          = 'certificado';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $operacion = "store";
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'certificado';
        $certificado  = null;
        $formData     = array('certificado.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('operacion','certificado', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $validacion = Validator::make($request->all(),
            array(
                'nombre' => 'required|max:100',
                'nombre_certificadora' => 'required|max:120'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $certificado       = new Certificado();
            $certificado->nombre = $request->input('nombre');
            $certificado->nombre_certificadora = $request->input('nombre_certificadora');
            $certificado->alumno_id = CompetenciaAlumno::getIdAlumno();
            /*CODIGO PARA LA IMAGEN*/
            $file = $request->file('archivo');
            $extension = $file->getClientOriginalExtension();
            $fileName = $certificado->nombre. '.' . $extension;
            $path = public_path('images/files/'.$fileName);
            Image::make($file)->fit(144, 144)->save($path);
            /* ASGINAMOS EL PATH AL OBJETO*/
            $certificado->url_archivo = $extension;
            //$certificado->save();
        });
        return is_null($error) ? "OK" : $error;
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
    public function edit($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'certificado');
        if ($existe !== true) {
            return $existe;
        }
        $operacion = "update";
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $certificado  = Certificado::find($id);
        $entidad      = 'certificado';
        $formData     = array('certificado.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('operacion','certificado', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'certificado');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'nombre' => 'required|max:100',
                'nombre_certificadora' => 'required|max:120'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $certificado       = Certificado::find($id);
            $certificado->nombre = $request->input('nombre');
            $certificado->nombre_certificadora = $request->input('nombre_certificadora');
            $certificado->alumno_id = CompetenciaAlumno::getIdAlumno();
            $certificado->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'certificado');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $facultad = Certificado::find($id);
            $facultad->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Función para confirmar la eliminación de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listará
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'certificado');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Certificado::find($id);
        $entidad  = 'certificado';
        $formData = array('route' => array('certificado.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
