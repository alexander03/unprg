<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Evento;
use App\EventoAlumno;
use App\Tipoevento;
use App\Facultad;
use App\Escuela;
use App\Especialidad;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class EventoAlumnoController extends Controller
{
    protected $folderview      = 'app.eventoalumno';
    protected $tituloAdmin     = 'Evento Alumno';
    protected $tituloRegistrar = 'Registrar Evento alumno';
    protected $tituloModificar = 'Confirmar suscripcion!';
    protected $tituloEliminar  = 'Eliminar Evento alumno';
    protected $rutas           = array('create' => 'eventoalumno.create', 
            'edit'   => 'eventoalumno.edit', 
            'delete' => 'eventoalumno.eliminar',
            'search' => 'eventoalumno.buscar',
            'index'  => 'eventoalumno.index',
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

        $alumno_id        = EventoALumno::getIdALumno();
        $escuela_id = DB::table('Alumno')->where('id', $alumno_id)->value('escuela_id');
        $facultad_id = DB::table('Escuela')->where('id', $escuela_id)->value('facultad_id');
        $resultado  = Evento::join("DIRECCION_EVENTO","DIRECCION_EVENTO.EVENTO_ID","=","EVENTO.ID")
                        ->where('DIRECCION_EVENTO.facultad_id','=',$facultad_id)
                        ->where('nombre', 'LIKE', '%'.$nombre.'%');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
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
        $listar = "NO";
        $modelo   = 'Evento Alumno';
        $entidad  = 'EventoAlumno';
        $formData       = array('eventoalumno.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Suscribirse';
        return view($this->folderview.'.mant')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
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
        $error = DB::transaction(function() use($request, $id){
            $eventoalumno = new EventoAlumno();
            $eventoalumno->alumno_id = EventoALumno::getIdALumno();
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
        $existe = Libreria::verificarExistencia($id, 'evento');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $evento = Evento::find($id);
            $evento->delete();
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
        $existe = Libreria::verificarExistencia($id, 'evento');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Evento::find($id);
        $entidad  = 'Evento';
        $formData = array('route' => array('evento.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
}
