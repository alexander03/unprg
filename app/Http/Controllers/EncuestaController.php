<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Encuesta;
use App\Tipoencuesta;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EncuestaController extends Controller
{
    protected $folderview      = 'app.encuesta';
    protected $tituloAdmin     = 'Encuesta';
    protected $tituloRegistrar = 'Registrar encuesta';
    protected $tituloModificar = 'Modificar encuesta';
    protected $tituloEliminar  = 'Eliminar encuesta';
    protected $rutas           = array('create' => 'encuesta.create', 
            'edit'   => 'encuesta.edit', 
            'delete' => 'encuesta.eliminar',
            'search' => 'encuesta.buscar',
            'index'  => 'encuesta.index',
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
     * Mostrar el resultado de búsquedas
     * 
     * @return Response 
     */
    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Encuesta';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $tipoencuesta_id  = Libreria::getParam($request->input('tipoencuesta_id'));
        $resultado        = Encuesta::listar($nombre,$tipoencuesta_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Objetivo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo', 'numero' => '1');
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = 'Encuesta';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboTipoEncuesta  = [''=>'Todos'] + Tipoencuesta::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cboTipoEncuesta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar              = Libreria::getParam($request->input('listar'), 'NO');
        $entidad             = 'Encuesta';
        $cboTipoEncuesta     = [''=>'Seleccione una categoría'] + Tipoencuesta::pluck('nombre', 'id')->all();
        $encuesta            = null;
        $formData            = array('encuesta.store');
        $formData            = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton               = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('encuesta', 'formData', 'entidad', 'boton', 'cboTipoEncuesta', 'listar'));
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
                'nombre'            => 'required|max:60',
                'objetivo'          => 'required|max:200',
                'tipoencuesta_id'   => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $encuesta                  = new Encuesta();
            $encuesta->nombre          = $request->input('nombre');
            $encuesta->objetivo        = $request->input('objetivo');
            $encuesta->tipoencuesta_id = $request->input('tipoencuesta_id');
            $encuesta->save();
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
    public function edit(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'encuesta');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $encuesta = Encuesta::find($id);
        $entidad  = 'Encuesta';
        $cboTipoEncuesta     = [''=>'Seleccione una categoría'] + Tipoencuesta::pluck('nombre', 'id')->all();
        $formData = array('encuesta.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('encuesta', 'formData', 'entidad', 'boton', 'cboTipoEncuesta', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'encuesta');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'nombre'            => 'required|max:60',
                'objetivo'          => 'required|max:200',
                'tipoencuesta_id'   => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $encuesta                 = Encuesta::find($id);
            $encuesta->nombre           = $request->input('nombre');
            $encuesta->objetivo       = $request->input('objetivo');
            $encuesta->tipoencuesta_id = $request->input('tipoencuesta_id');
            $encuesta->save();
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
        $existe = Libreria::verificarExistencia($id, 'encuesta');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $encuesta = Encuesta::find($id);
            $encuesta->delete();
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
        $existe = Libreria::verificarExistencia($id, 'encuesta');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Encuesta::find($id);
        $mensaje = '<p class="text-inverse">¿Esta seguro de eliminar el registro "'.$modelo->nombre.'"?</p>';
        $entidad  = 'Encuesta';
        $formData = array('route' => array('encuesta.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar','mensaje'));
    }
}
