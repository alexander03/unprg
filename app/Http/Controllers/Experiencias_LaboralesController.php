<?php

namespace App\Http\Controllers;

use App\Experiencias_Laborales;
use App\CompetenciaAlumno;
use App\Http\Controllers\Controller;
use App\Librerias\Libreria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Validator;

class Experiencias_LaboralesController extends Controller
{
    protected $folderview = 'app.experienciaslaborales';
    protected $tituloAdmin = 'Experiencias Laborales';
    protected $tituloRegistrar = 'Registrar Exp. Laboral';
    protected $tituloModificar = 'Modificar Exp. Laboral';
    protected $tituloEliminar = 'Eliminar Exp. Laboral';
    protected $rutas = array('create' => 'experienciaslaborales.create',
        'edit' => 'experienciaslaborales.edit',
        'delete' => 'experienciaslaborales.eliminar',
        'search' => 'experienciaslaborales.buscar',
        'index' => 'experienciaslaborales.index',
        'competencias' => 'experienciaslaborales.obtenercompetencias',
    );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina = $request->input('page');
        $filas = $request->input('filas');
        $entidad = 'experiencias_laborales';
        $fechai = Libreria::getParam($request->input('fechai'));
        $fechaf = Libreria::getParam($request->input('fechaf'));
        $alumno_id = CompetenciaAlumno::getIdAlumno();
        $resultado = Experiencias_Laborales::listar($fechai, $fechaf, $alumno_id);
        $lista = $resultado->get();
        $cabecera = array();
        $cabecera[] = array('valor' => '#', 'numero' => '1');
        $cabecera[] = array('valor' => 'Ruc', 'numero' => '1');
        $cabecera[] = array('valor' => 'Empresa', 'numero' => '1');
        $cabecera[] = array('valor' => 'Cargo', 'numero' => '1');
        $cabecera[] = array('valor' => 'Contacto', 'numero' => '1');
        $cabecera[] = array('valor' => 'F. Inicio', 'numero' => '1');
        $cabecera[] = array('valor' => 'F. Fin', 'numero' => '1');
        $cabecera[] = array('valor' => 'Operaciones', 'numero' => '3');
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

    public function index()
    {
        $entidad = 'experienciaslaborales';
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
        $entidad = 'experienciaslaborales';
        $experienciaslaborales = null;
        $formData = array('experienciaslaborales.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton = 'Registrar';
        return view($this->folderview . '.mant')->with(compact('experienciaslaborales', 'formData', 'entidad', 'boton', 'listar'));
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
        $validacion = Validator::make($request->all(),
            array(
                'ruc' => 'required|max:11',
                'empresa' => 'required|max:120',
                'cargo' => 'required|max:100',
                'telefono' => 'required|max:30',
                'fechainicio' => 'required',
            )
        );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function () use ($request) {
            $experiencias_laborales = new Experiencias_Laborales();
            $experiencias_laborales->ruc = $request->input('ruc');
            $experiencias_laborales->empresa = $request->input('empresa');
            $experiencias_laborales->cargo = $request->input('cargo');
            $experiencias_laborales->email = $request->input('email');
            $experiencias_laborales->telefono = $request->input('telefono');
            $experiencias_laborales->fechainicio = $request->input('fechainicio');
            $experiencias_laborales->fechafin = $request->input('fechafin');
            $result = DB::select('SELECT id FROM alumno WHERE codigo = ?', ['2009966J']);
            foreach ($result as $r) {
                //echo $r->id;
                $experiencias_laborales->alumno_id = $r->id;
            }
            $experiencias_laborales->save();
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
        $existe = Libreria::verificarExistencia($id, 'experiencias_laborales');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $experienciaslaborales = Experiencias_Laborales::find($id);
        $entidad = 'experienciaslaborales';
        $formData = array('experienciaslaborales.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton = 'Modificar';
        return view($this->folderview . '.mant')->with(compact('experienciaslaborales', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'experiencias_laborales');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'ruc' => 'required|max:11',
                'empresa' => 'required|max:120',
                'cargo' => 'required|max:100',
                'telefono' => 'required|max:30',
            )
        );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function () use ($request, $id) {
            $experiencias_laborales = Experiencias_Laborales::find($id);
            $experiencias_laborales->ruc = $request->input('ruc');
            $experiencias_laborales->empresa = $request->input('empresa');
            $experiencias_laborales->cargo = $request->input('cargo');
            $experiencias_laborales->email = $request->input('email');
            $experiencias_laborales->telefono = $request->input('telefono');
            $experiencias_laborales->fechainicio = $request->input('fechainicio');
            $experiencias_laborales->fechafin = $request->input('fechafin');
            $result = DB::select('SELECT id FROM alumno WHERE codigo = ?', ['2009966J ']);
            foreach ($result as $r) {
                //echo $r->id;
                $experiencias_laborales->alumno_id = $r->id;
            }
            /*
            $respuesta = Alumno::where('codigo', '=', '2009966J ')->get();
            echo $respuesta[0]->id;
             */
            //$experiencias_laborales->alumno_id = '2009966J';
            $experiencias_laborales->save();
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
        $existe = Libreria::verificarExistencia($id, 'experiencias_laborales');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function () use ($id) {
            $experiencias_laborales = Experiencias_Laborales::find($id);
            $experiencias_laborales->delete();
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
        $existe = Libreria::verificarExistencia($id, 'experiencias_laborales');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo = Experiencias_Laborales::find($id);
        $entidad = 'experienciaslaborales';
        $formData = array('route' => array('experienciaslaborales.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function obtenercompetencias($listarParam, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'experiencias_laborales');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        $entidad = 'Permiso';
        if (isset($listarParam)) {
            $listar = $listarParam;
        }
        //$modelo = Experiencias_Laborales::find($id);
        return view($this->folderview.'.competencias')->with(compact('id', 'listar', 'entidad'));
    }

}
