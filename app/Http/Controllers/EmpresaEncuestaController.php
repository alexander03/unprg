<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Alumno;
use App\Encuesta;
use App\AlumnoEncuesta;
use App\Tipoencuesta;
use App\Pregunta;
use App\Alternativa;
use App\Direccion;
use App\Facultad;
use App\Escuela;
use App\Especialidad;
use App\Respuesta;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class EmpresaEncuestaController extends Controller
{
	protected $folderview      = 'app.empresaencuesta';
    protected $tituloAdmin     = 'Encuestas Respondidas';
    protected $rutas           = array(
            'search' => 'empresaencuesta.buscar',
            'respuestasencuesta' => 'empresaencuesta.respuestasencuesta',
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
        $resultado        = AlumnoEncuesta::listar($nombre);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Encuesta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Alumno', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Respuestas', 'numero' => '1');

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
        $entidad          = 'EmpresaEncuesta';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;

        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'ruta'));
    }

    public function respuestasencuesta(Request $request) {
        $encuesta_id = $request->get('encuesta_id');

        $preguntas   = Pregunta::select('id', 'nombre')->where('encuesta_id', '=', $encuesta_id)->get();

        return view($this->folderview.'.respuestasencuesta')->with(compact('preguntas'));
    }
}

