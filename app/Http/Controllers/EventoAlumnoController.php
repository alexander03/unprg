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
    protected $tituloEliminar  = 'Confirmar suscripcion!';
    protected $rutas           = array('create' => 'eventoalumno.create', 
            'detalleevento'   => 'eventoalumno.detalleevento', 
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
public function detalleevento($id, Request $request){
    $evento       = Evento::find($id);
    return view($this->folderview.'.detalle')->with(compact('evento'));
}

    public function suscribir(Request $request){
        $res = '';
        $error = DB::transaction(function() use($request,&$res){
            $eventoalumno = new EventoALumno();
            $eventoalumno->alumno_id = EventoALumno::getIdALumno();
            $evento_id           = Libreria::getParam($request->input('id'));
            $eventoalumno->evento_id = $evento_id;
            $eventoalumno->save();
            $res='OK';
        });
        return response()->json($res);
    }
     
    public function dessuscribir(Request $request){
        $res = '';
        $evento_id           = Libreria::getParam($request->input('id'));
        //OfertaAlumno::where('EVENTO_ID','=',$oferta_id)->where('ALUMNO_ID','=',OfertaALumno::getIdALumno())->delete();
        DB::delete('DELETE FROM EVENTO_ALUMNO WHERE ALUMNO_ID='.EventoALumno::getIdALumno().' AND EVENTO_ID = '.$evento_id);
        $res='OK';
        return response()->json($res);
    }

    public function buscar(Request $request)
    {
        //$pagina           = $request->input('page');
        $entidad          = 'Evento';
        $filas            = $request->input('filas');
        $nombre           = Libreria::getParam($request->input('nombre'));
        $fechai           = Libreria::getParam($request->input('fechai'));
        $fechaf           = Libreria::getParam($request->input('fechaf'));
        return view($this->folderview.'.list')->with(compact('entidad', 'filas','nombre','fechai','fechaf','entidad'));
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
        $title1 = "¿Esta seguro de  Suscribirse a la evento?";
        $listar = "SI";
        $modelo   = 'EventoAlumno';
        $entidad  = 'EventoAlumno';
        $formData       = array('eventoalumno.update', $id);
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
        
        $error = DB::transaction(function() use($request, $id){
            $eventoalumno = new EventoALumno();
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
        // $ofertaalumno_id = DB::table('Evento_ALumno')->where('evento_id', $id)->value('id');
        // $error = DB::transaction(function() use($ofertaalumno_id){
        //     $ofertaalumno = OfertaAlumno::find($ofertaalumno_id);
        //     $ofertaalumno->delete();
        // });
        $error = EventoAlumno::where('EVENTO_ID','=',$evento_id)->where('EVENTO_ID','=',EventoALumno::getIdALumno())->delete();
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
        $listar = "SI";
        $title1 ="¿Esta seguro de  Desuscribirse a la evento?";
        $modelo   = 'EventoAlumno';
        $entidad  = 'EventoAlumno';
        $formData       = array('eventoalumno.destroy', $id);
        $formData = array('route' => $formData, 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $nombreBtn    = 'POSTULAR';
        return view($this->folderview.'.mant')->with(compact('modelo', 'formData', 'entidad', 'nombreBtn', 'listar', 'title1'));
    }
}
