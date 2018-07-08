<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Alumno;
use App\Encuesta;
use App\Tipoencuesta;
use App\Pregunta;
use App\Alternativa;
use App\Direccion;
use App\Facultad;
use App\Escuela;
use App\Especialidad;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class AlumnoEncuestaController extends Controller
{
	protected $folderview      = 'app.alumnoencuesta';
    protected $tituloAdmin     = 'Mis Encuestas Disponibles';
    protected $rutas           = array(
            'search' => 'alumnoencuesta.buscar',
            'index'  => 'alumnoencuesta.index',
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
        $user             = Auth::user();
        $alumno_id        = $user->alumno_id;
        $alumno           = Alumno::find($alumno_id);        
        $escuela_id       = $alumno->escuela_id;
        $especialidad_id  = $alumno->especialidad_id;
        $escuela          = Escuela::find($escuela_id);     
        $facultad_id      = $escuela->facultad_id;
        $encuestas        = Direccion::select('encuesta_id')
                            ->orWhere('escuela_id', '=', $escuela_id)
                            ->orWhere('especialidad_id', '=', $especialidad_id)
                            ->orWhere('facultad_id', '=', $facultad_id)
                            ->distinct()
                            ->get();

        $resultado        = DB::table('encuesta'); 

        if(count($encuestas) != 0) {
            foreach ($encuestas as $encuesta) {
                $resultado->orWhere('id', '=', $encuesta->encuesta_id);
            }
        } else {
            $resultado->where('nombre', '=', '%%%%%%%');
        }

        $entidad          = 'Encuesta';
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Objetivo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Preguntas', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Direcciones', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');

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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    public function index()
    {
        $entidad          = 'Encuesta';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $cboTipoEncuesta  = [''=>'Todos'] + Tipoencuesta::pluck('nombre', 'id')->all();

        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'ruta', 'cboTipoEncuesta'));
    }
}
