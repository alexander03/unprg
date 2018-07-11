<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Evento;
use App\OfertaAlumno;
use App\Tipoevento;
use App\Facultad;
use App\Escuela;
use App\Especialidad;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OfertaAlumnoController extends Controller
{
    protected $folderview      = 'app.ofertaalumno';
    protected $tituloAdmin     = 'Oferta Alumno';
    protected $tituloRegistrar = 'Registrar Oferta alumno';
    protected $tituloModificar = 'Confirmar suscripcion!';
    protected $tituloEliminar  = 'Confirmar suscripcion!';
    protected $rutas           = array('create' => 'ofertaalumno.create', 
            'edit'   => 'ofertaalumno.edit', 
            'delete' => 'ofertaalumno.eliminar',
            'search' => 'ofertaalumno.buscar',
            'index'  => 'ofertaalumno.index',
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
        $entidad          = 'Evento';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $resultado          = OfertaAlumno::listar($nombre);
        $lista              = $resultado->get();
        $cabecera       = array();
        $cabecera[]     = array('valor' => '#', 'numero' => '1');
        $cabecera[]     = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]     = array('valor' => 'Operaciones', 'numero' => '2');
        
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
        $entidad          = 'Evento';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
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
        $title1 = "¿Esta seguro de  Suscribirse a la oferta?";
        $listar = "NO";
        $modelo   = 'Evento Alumno';
        $entidad  = 'EventoAlumno';
        $formData       = array('ofertaalumno.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Suscribirse';
        return view($this->folderview.'.mant')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar', 'title1'));
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
        $reglas = array(
            );

        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        
        $error = DB::transaction(function() use($request, $id){
            $eventoalumno = new EventoAlumno();
            $eventoalumno->alumno_id = OfertaALumno::getIdALumno();
            $eventoalumno->evento_id = $id;
            $eventoalumno->save();
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
        $eventoalumno_id = DB::table('Evento_ALumno')->where('evento_id', $id)->value('id');
        $error = DB::transaction(function() use($eventoalumno_id){
            $eventoalumno = OfertaAlumno::find($eventoalumno_id);
            $eventoalumno->delete();
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
        $listar = "NO";
        $title1 ="¿Esta seguro de  Desuscribirse a la oferta?";
        $modelo   = 'Oferta Alumno';
        $entidad  = 'OfertaAlumno';
        $formData       = array('ofertaalumno.destroy', $id);
        $formData = array('route' => $formData, 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Desuscribirse';
        return view($this->folderview.'.mant')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar', 'title1'));
    }
}
