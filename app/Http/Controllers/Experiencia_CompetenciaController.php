<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Experiencias_Laborales;
use App\Experiencia_Competencia;
use App\CompetenciaAlumno;
use App\Http\Controllers\Controller;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Validator;


class Experiencia_CompetenciaController extends Controller
{

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Escuela';
        $descripcion      = Libreria::getParam($request->input('name'));
        $resultado        = Escuela::listar($descripcion);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DNI', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CODIGO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NOMBRE COMPLETO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ESCUELA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ESPECIALIDAD', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '1');
        
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

    public function listarCompetencias(Request $request){
        $id = $request->input('id');
        $competencias_experiencia = Experiencia_Competencia::listarCompetencias('',$id);
        return response()->json([
            'LIST' => $competencias_experiencia->get(),
            'LIST_COMBO' => [],
            'RESPUESTA_SERVER' => ''
        ]);
        //return response()->json($competencias_experiencia->get());
    }

    public function listarCompetenciasAlumno(){
        $alumno_id = CompetenciaAlumno::getIdAlumno();
        $results = CompetenciaAlumno::listar($alumno_id,'');
        $list = $results->get();
        return response()->json([
            'LIST' => [],
            'LIST_COMBO' => $list,
            'RESPUESTA_SERVER' => 'listar_combo'
        ]);
    }

    public function agregarCompetencia(Request $request){
        $respuesta_server = 'ERROR_INTERNO';
        $competencias_experiencia = [];
        $error = DB::transaction(function () use (&$respuesta_server, $request) {
            $experiencia_competencia = new Experiencia_Competencia();
            $experiencia_competencia->competencia_alumno_id = $request->input('competencia_alumno_id');
            $experiencia_competencia->experiencia_laboral_id = $request->input('id');
            $experiencia_competencia->save();
            $respuesta_server = 'Registrado';
        });
        if($respuesta_server = 'Registrado'){
            $competencias_experiencia = Experiencia_Competencia::listarCompetencias('',$request->input('id'))->get();
        }
        return response()->json([
            'LIST' => $competencias_experiencia,
            'LIST_COMBO' => [],
            'RESPUESTA_SERVER' => $respuesta_server
        ]);
    }

    public function eliminarCompetencia(Request $request){
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
        if($respuesta_server = 'Eliminado'){
            $competencias_experiencia = Experiencia_Competencia::listarCompetencias('',$request->input('id'))->get();
        }
        return response()->json([
            'LIST' => $competencias_experiencia,
            'LIST_COMBO' => [],
            'RESPUESTA_SERVER' => $respuesta_server
        ]);
    }



}
