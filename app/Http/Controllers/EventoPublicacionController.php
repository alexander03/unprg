<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Evento;
use App\Direccion_evento;
use App\Empresa;
use App\Alumno;
use App\Tipoevento;
use App\EventoALumno;
use App\Facultad;
use App\Escuela;
use App\Especialidad;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PDF;
use Jenssegers\Date\Date;
class EventoPublicacionController extends Controller
{
    
    protected $folderview      = 'app.eventopublicacion';
    protected $tituloAdmin     = 'Evento Publicacion';
    protected $tituloListar = 'Evento';
    protected $rutas           = array(
            'listsuscriptores' => 'eventopublicacion.listsuscriptores',
            'search' => 'eventopublicacion.buscar',
            'index'  => 'eventopublicacion.index',
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
        $nombre      = Libreria::getParam($request->input('nombre'));
        $empresa_id      = Evento::getIdEmpresa();
        //$tipoevento_id      = Libreria::getParam($request->input('tipoevento_id'));
        $resultado        = Evento::listarsuscritos($nombre, $empresa_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Descripción', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo Evento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cantidad Suscritos', 'numero' => '1');
        $cabecera[]       = array('valor' => 'reporte pdf', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '1');
        
        $tituloListar = $this->tituloListar;
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'tituloListar', 'ruta'));
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
        $tituloListar = $this->tituloListar;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'tituloListar', 'ruta'));
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
    public function listsuscriptores($id, Request $request)
    {
        $listarSuscriptores        = EventoAlumno::listarSuscriptores($id);
        $lista           = $listarSuscriptores->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Alumno', 'numero' => '1');
        $ruta             = $this->rutas;

        $tituloListar = $this->tituloListar;
        $ruta             = $this->rutas;
        $inicio           = 0;
        if (count($lista) > 0) {
            return view($this->folderview.'.suscriptores')->with(compact('lista', 'entidad', 'cabecera', 'tituloListar', 'ruta', 'inicio', 'id'));
        }
        return view($this->folderview.'.suscriptores')->with(compact('lista', 'entidad', 'id', 'ruta'));

    }
    // METODO PARA PDF DE SUSCRIPTORES POR CADA EVENTO


    public function downloadPDF($id, Request $request)
    {    
        $listarSuscriptoresPDF        = EventoAlumno::listarSuscriptoresPDF($id);
        $lista           = $listarSuscriptoresPDF->get();
        $evento       = Evento::find($id);
        $nomevento = $evento->nombre;
        $view = \View::make('app.eventopublicacion.downloadPDF')->with(compact('lista','evento', 'id'));
        $html_content = $view->render();      
 
        PDF::SetTitle($nomevento);
        PDF::AddPage('L','A4',0);
        PDF::SetDisplayMode('fullpage');
        PDF::writeHTML($html_content, true, false, true, false, '');
 
        PDF::Output($nomevento.'.pdf', 'D');
    }



}
