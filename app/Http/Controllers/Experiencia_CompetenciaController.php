<?php

namespace App\Http\Controllers;

use App\CompetenciaAlumno;
use App\Experiencia_Competencia;
use App\Experiencias_Laborales;
use App\Http\Controllers\Controller;
use App\Librerias\Libreria;
use Hash;
use Validator;
use App\Alumno;
use App\Escuela;
use App\Especialidad;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Illuminate\Http\Request;

class Experiencia_CompetenciaController extends Controller
{
    protected $folderview = 'app.experienciacompetencia';
    protected $tituloAdmin = 'Exp. Laborales del Alumno';
    protected $rutas = array(
        'search' => 'experienciacompetencia.buscar',
        'index' => 'experienciacompetencia.index',
        'experienciaslaborales' => 'experienciacompetencia.obtenerexperienciaslaborales',
    );

    public function buscar(Request $request)
    {
        $pagina = $request->input('page');
        $filas = $request->input('filas');
        $entidad = 'Alumno';
        $codigo = Libreria::getParam($request->input('codigo'));
        $nombre = Libreria::getParam($request->input('nombre'));
        $escuela_id = Libreria::getParam($request->input('escuela1_id'));
        $resultado = Alumno::listar($codigo, $nombre, $escuela_id);
        $lista = $resultado->get();
        $cabecera = array();
        $cabecera[] = array('valor' => '#', 'numero' => '1');
        $cabecera[] = array('valor' => 'DNI', 'numero' => '1');
        $cabecera[] = array('valor' => 'CODIGO', 'numero' => '1');
        $cabecera[] = array('valor' => 'NOMBRE COMPLETO', 'numero' => '1');
        $cabecera[] = array('valor' => 'ESCUELA', 'numero' => '1');
        $cabecera[] = array('valor' => 'ESPECIALIDAD', 'numero' => '1');
        $cabecera[] = array('valor' => 'OPERACIONES', 'numero' => '2');

        $ruta = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion = $paramPaginacion['cadenapaginacion'];
            $inicio = $paramPaginacion['inicio'];
            $fin = $paramPaginacion['fin'];
            $paginaactual = $paramPaginacion['nuevapagina'];
            $lista = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview . '.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'ruta'));
        }
        return view($this->folderview . '.list')->with(compact('lista', 'entidad'));
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $entidad = 'experienciacompetencia';
        $title = $this->tituloAdmin;
        $ruta = $this->rutas;
        $cboEscuela     = [''=>'Todos'] + Escuela::pluck('nombre', 'id')->all();
        return view($this->folderview . '.admin')->with(compact('entidad', 'cboEscuela', 'title', 'ruta'));
    }

    public function listarCompetencias(Request $request)
    {
        $id = $request->input('id'); // ID -> EXPERIENCIA LABORAL
        $competencias_experiencia = Experiencia_Competencia::listarCompetencias('', $id);
        return response()->json([
            'LIST' => $competencias_experiencia->get(),
            'LIST_COMBO' => [],
            'RESPUESTA_SERVER' => '',
        ]);
        //return response()->json($competencias_experiencia->get());
    }

    public function listarCompetenciasAlumno()
    {
        $alumno_id = CompetenciaAlumno::getIdAlumno();
        $results = CompetenciaAlumno::listar($alumno_id, '');
        $list = $results->get();
        return response()->json([
            'LIST' => [],
            'LIST_COMBO' => $list,
            'RESPUESTA_SERVER' => 'listar_combo',
        ]);
    }

    public function listarExperienciasLaborales(Request $request)
    {
        $alumno_id = Libreria::getParam($request->input('idalumno'));
        $fechai = Libreria::getParam($request->input('fechai'));
        $fechaf = Libreria::getParam($request->input('fechaf'));
        $resultado = Experiencias_Laborales::listar($fechai, $fechaf, $alumno_id);
        $lista = $resultado->get();
        return response()->json([
            'LIST' => $lista,
            'RESPUESTA_SERVER' => 'get_experiencias_laborales',
        ]);
    }

    public function agregarCompetencia(Request $request)
    {
        $respuesta_server = 'ERROR_INTERNO';
        $competencias_experiencia = [];
        $error = DB::transaction(function () use (&$respuesta_server, $request) {
            $experiencia_competencia = new Experiencia_Competencia();
            $experiencia_competencia->competencia_alumno_id = $request->input('competencia_alumno_id');
            $experiencia_competencia->experiencia_laboral_id = $request->input('id');
            $experiencia_competencia->save();
            $respuesta_server = 'Registrado';
        });
        if ($respuesta_server = 'Registrado') {
            $competencias_experiencia = Experiencia_Competencia::listarCompetencias('', $request->input('id'))->get();
        }
        return response()->json([
            'LIST' => $competencias_experiencia,
            'LIST_COMBO' => [],
            'RESPUESTA_SERVER' => $respuesta_server,
        ]);
    }

    public function eliminarCompetencia(Request $request)
    {
        /**ELIMINAMOS LA EXPERIENCIA_COMPETENCIA */
        $respuesta_server = 'ERROR_INTERNO';
        $competencias_experiencia = [];
        $idexperiencia_competencia = $request->input('idexperiencia_competencia');
        $existe = Libreria::verificarExistencia($idexperiencia_competencia, 'experiencia_competencia');
        $error = DB::transaction(function () use ($idexperiencia_competencia, &$respuesta_server) {
            $experiencia_competencia = Experiencia_Competencia::find($idexperiencia_competencia);
            $experiencia_competencia->delete();
            $respuesta_server = 'Eliminado';
        });
        if ($respuesta_server = 'Eliminado') {
            $competencias_experiencia = Experiencia_Competencia::listarCompetencias('', $request->input('id'))->get();
        }
        return response()->json([
            'LIST' => $competencias_experiencia,
            'LIST_COMBO' => [],
            'RESPUESTA_SERVER' => $respuesta_server,
        ]);
    }

    public function editarCompetencia(Request $request)
    {
        /**EDITAMOS LA EXPERIENCIA_COMPETENCIA */
        $respuesta_server = 'ERROR_INTERNO';
        $competencias_experiencia = [];
        $idexperiencia_competencia = $request->input('idexperiencia_competencia');
        $existe = Libreria::verificarExistencia($idexperiencia_competencia, 'experiencia_competencia');
        $calificacion = $request->input('calificacion');
        $error = DB::transaction(function () use ($idexperiencia_competencia, &$respuesta_server, $calificacion) {
            $experiencia_competencia = Experiencia_Competencia::find($idexperiencia_competencia);
            $experiencia_competencia->calificacion = $calificacion;
            $experiencia_competencia->save();
            $respuesta_server = 'Modificado';
        });
        if ($respuesta_server = 'Modificado') {
            $competencias_experiencia = Experiencia_Competencia::listarCompetencias('', $request->input('id'))->get();
        }
        return response()->json([
            'LIST' => $competencias_experiencia,
            'LIST_COMBO' => [],
            'RESPUESTA_SERVER' => $respuesta_server,
        ]);
    }

    public function obtenerexperienciaslaborales($listarParam, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'alumno');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (isset($listarParam)) {
            $listar = $listarParam;
        }
        return view($this->folderview.'.experienciaslaborales')->with(compact('id', 'listar'));
    }


}
