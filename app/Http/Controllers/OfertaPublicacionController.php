<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Oferta;
use App\Direccion_oferta;
use App\Empresa;
use App\Alumno;
use App\Tipoevento;
use App\OfertaALumno;
use App\EventoALumno;
use App\Facultad;
use App\Escuela;
use App\Especialidad;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OfertaPublicacionController extends Controller
{
    protected $folderview      = 'app.ofertapublicacion';
    protected $tituloAdmin     = 'Oferta Publicacion';
    protected $tituloListar = 'Oferta';
    protected $rutas           = array(
            'listsuscriptores' => 'ofertapublicacion.listsuscriptores',
            'search' => 'ofertapublicacion.buscar',
            'index'  => 'ofertapublicacion.index',
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
        $entidad          = 'Oferta';
        $nombre      = Libreria::getParam($request->input('nombre'));
        $empresa_id      = Oferta::getIdEmpresa();
        //$tipoevento_id      = Libreria::getParam($request->input('tipoevento_id'));
        $resultado        = Oferta::listarsuscritos($nombre, $empresa_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Descripción', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Tipo Evento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cantidad Suscritos', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Tipo Evento', 'numero' => '1');
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
        $entidad          = 'Oferta';
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

}
