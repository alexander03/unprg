<?php

namespace App\Http\Controllers;

use App\Competencia;
use App\CompetenciaAlumno;
use App\Http\Controllers\Controller;
use App\Librerias\Libreria;
use Illuminate\Http\Request;
use Validator;

class CompetenciaAlumnoController extends Controller
{
    //
    protected $folderview = 'app.competencia_alumno';
    protected $tituloAdmin = 'Mis Competencias';
    protected $tituloRegistrar = 'Registrar Competencia';
    protected $tituloModificar = 'Modificar Competencia';
    protected $tituloEliminar = 'Eliminar Competencia';
    protected $rutas = array('create' => 'competencia_alumno.create',
        'edit' => 'competencia_alumno.edit',
        'delete' => 'competencia_alumno.eliminar',
        'search' => 'competencia_alumno.buscar',
        'index' => 'competencia_alumno.index',
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
        $pagina = $request->input('page');
        $filas = $request->input('filas');
        $entidad = 'CompetenciaAlumno';
        $alumno_id = CompetenciaAlumno::getIdAlumno();
        $resultado = CompetenciaAlumno::listar($alumno_id);
        $lista = $resultado->get();
        $cabecera = array();
        $cabecera[] = array('valor' => '#', 'numero' => '1');
        $cabecera[] = array('valor' => 'Competencia', 'numero' => '1');
        $cabecera[] = array('valor' => 'Calificacion', 'numero' => '1');
        $cabecera[] = array('valor' => 'Operaciones', 'numero' => '2');

        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar = $this->tituloEliminar;
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
            return view($this->folderview . '.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
        }
        return view($this->folderview . '.list')->with(compact('lista', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad = 'CompetenciaAlumno';
        $title = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta = $this->rutas;
        return view($this->folderview . '.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $entidad = 'CompetenciaAlumno';
        $competencia_alumno = null;
        $cboCompetencia = array('' => 'Seleccione') + Competencia::pluck('nombre', 'id')->all();
        $formData = array('competencia_alumno.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton = 'Registrar';
        return view($this->folderview . '.mant')->with(compact('competencia_alumno', 'formData', 'entidad', 'boton', 'listar', 'cboCompetencia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'nombre' => 'required|max:50|unique:escuela,nombre,NULL,id,deleted_at,NULL',
            'competencia_id' => 'required|integer|exists:competencia,id,deleted_at,NULL',
        );
        $validacion = Validator::make($request->all(), $reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function () use ($request) {
            $competencia_alumno = new CompetenciaAlumno();
            $competencia_alumno->calificacion = $request->input('calificacion');
            $competencia_alumno->competencia_id = $request->input('competencia_id');
            $competencia_alumno->alumno_id = getIdAlumno();
            $competencia_alumno->save();
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
    public function edit($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'competencia_alumno');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $cboCompetencia = array('' => 'Seleccione') + Competencia::pluck('nombre', 'id')->all();

        $competencia_alumno = CompetenciaAlumno::find($id);
        $entidad = 'CompetenciaAlumno';
        $formData = array('competencia_alumno.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton = 'Modificar';
        return view($this->folderview . '.mant')->with(compact('competencia_alumno', 'formData', 'entidad', 'boton', 'listar', 'cboCompetencia'));
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
        $existe = Libreria::verificarExistencia($id, 'competencia_alumno');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'nombre' => 'required|max:50|unique:escuela,nombre,' . $id . ',id,deleted_at,NULL',
            'competencia_id' => 'required|integer|exists:competencia,id,deleted_at,NULL',
        );
        $validacion = Validator::make($request->all(), $reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function () use ($request, $id) {
            $competencia_alumno = CompetenciaAlumno::find($id);
            $competencia_alumno->calificacion = $request->input('calificacion');
            $competencia_alumno->competencia_id = $request->input('competencia_id');
            $competencia_alumno->save();
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
        $existe = Libreria::verificarExistencia($id, 'competencia_alumno');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function () use ($id) {
            $competencia_alumno = CompetenciaAlumno::find($id);
            $competencia_alumno->delete();
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
        $existe = Libreria::verificarExistencia($id, 'competencia_alumno');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo = CompetenciaAlumno::find($id);
        $entidad = 'CompetenciaAlumno';
        $formData = array('route' => array('competencia_alumno.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
}
