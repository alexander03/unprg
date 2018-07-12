<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Evento;
use App\Direccion_evento;
use App\Empresa;
use App\Tipoevento;
use App\Facultad;
use App\Escuela;
use App\Especialidad;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class EventoController extends Controller
{
    protected $folderview      = 'app.evento';
    protected $tituloAdmin     = 'Evento';
    protected $tituloRegistrar = 'Registrar evento';
    protected $tituloModificar = 'Modificar evento';
    protected $tituloEliminar  = 'Eliminar evento';
    protected $rutas           = array('create' => 'evento.create', 
            'edit'   => 'evento.edit',
            'delete' => 'evento.eliminar',
            'search' => 'evento.buscar',
            'index'  => 'evento.index',
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
        $fechai = Libreria::getParam($request->input('fechai'));
        $fechaf = Libreria::getParam($request->input('fechaf'));
        $resultado        = Evento::listar($nombre, $empresa_id, $fechai, $fechaf);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo Evento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Inicio', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Fin', 'numero' => '1');
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
        $cboFacultad = [''=>'Todos'];// + Facultad::pluck('nombre', 'id')->all();
        $cboEscuela = [''=>'Todos']; //+ Escuela::pluck('nombre', 'id')->all();
        $cboEspecialidad = [''=>'Todos']; //+ Especialidad::pluck('nombre', 'id')->all();
        $cboOpcionEvento    = array('0'=>'Libre','1' => 'Con restricciones');
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cboFacultad','cboEscuela','cboEspecialidad','cboOpcionEvento'));
    }

    public function getEscuelas(Request $request, $id){
        if($request->ajax()){
            $escuelas = Escuela::escuelas($id);
            return response()->json($escuelas);
        }
    }
    public function getEspecialidades(Request $request, $id){
        if($request->ajax()){
            $especialidades = Especialidad::especialidades($id);
            return response()->json($especialidades);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Evento';
        $evento        = null;
        $cboTipoevento = array('' => 'Seleccione') + Tipoevento::pluck('nombre', 'id')->all();
        $cboFacultad = array('' => 'Seleccione') + Facultad::pluck('nombre', 'id')->all();
        $cboEscuela = array('' => 'Seleccione');// + Escuela::pluck('nombre', 'id')->all();
        $cboEspecialidad = array('' => 'Seleccione') ;//+ Especialidad::pluck('nombre', 'id')->all();
        $cboOpcionEvento = array('0'=>'Libre','1' => 'Con restricciones');
        $formData  = array('evento.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('evento', 'formData', 'entidad', 'boton', 'listar','cboTipoevento','cboFacultad','cboEscuela','cboEspecialidad','cboOpcionEvento'));
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
                'nombre'       => 'required|max:120|unique:evento,nombre,NULL,id,deleted_at,NULL',
                'tipoevento_id' => 'required',
                'fechaInicio' => 'required',
                'fechaFin' => 'required',
            )
        );
        
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }

        $error = DB::transaction(function() use($request){
            $evento               = new Evento();
            $evento->nombre = $request->input('nombre');
            $evento->empresa_id = Evento::getIdEmpresa();
            $evento->tipoevento_id =$request->input('tipoevento_id');
            $evento->opcionevento =$request->input('opcionevento');
            $evento->fechai     = $request->input('fechaInicio');
            $evento->fechaf     = $request->input('fechaFin');
            $evento->save();

            if($request->input('cadenaDirecciones') != ''){
                $direcciones = explode(",", $request->input('cadenaDirecciones'));
                for( $i=0; $i< count($direcciones); $i++){
                    $direccion_evento = new  Direccion_evento();

                    $direc =  explode(":", $direcciones[$i]);
                    $direccion_evento->evento_id = (int)$evento->id;
                    if((int)$direc[0]!=-1){
                    $direccion_evento->facultad_id = (int)$direc[0];
                    }
                    if((int)$direc[1]!=-1){
                        $direccion_evento->facultad_id = null;
                    $direccion_evento->escuela_id = (int)$direc[1];
                    }
                    if((int)$direc[2]!=-1){
                        $direccion_evento->escuela_id =null;
                    $direccion_evento->especialidad_id = (int) $direc[2];
                    }
                    $direccion_evento->save();
                }
            }
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
        $existe = Libreria::verificarExistencia($id, 'evento');
        if ($existe !== true) {
            return $existe;
        }
   
        $listaDetalle        = Evento::listarDetalleEvento( $id);
        $listaDet           = $listaDetalle->get();

        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $evento       = Evento::find($id);
        $entidad        = 'Evento';
        $cboTipoevento = array('' => 'Seleccione') + Tipoevento::pluck('nombre', 'id')->all();
        $cboFacultad = array('' => 'Seleccione') + Facultad::pluck('nombre', 'id')->all();
        $cboEscuela = array('' => 'Seleccione') + Escuela::pluck('nombre', 'id')->all();
        $cboEspecialidad = array('' => 'Seleccione') + Especialidad::pluck('nombre', 'id')->all();
        $cboOpcionEvento = array('0'=>'Libre','1' => 'Con restricciones');
        $formData       = array('evento.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('evento', 'formData', 'entidad', 'boton', 'listar','listaDet','cboTipoevento','cboFacultad','cboEscuela','cboEspecialidad','cboOpcionEvento'));
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
        $existe = Libreria::verificarExistencia($id, 'evento');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
        array(
                'nombre'       => 'required|max:120|unique:evento,nombre,'.$id.',id,deleted_at,NULL',
                'tipoevento_id' => 'required',
                'fechaInicio' => 'required',
                'fechaFin' => 'required',
            )
        );
//
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $evento                 = Evento::find($id);
            $evento->nombre = $request->input('nombre');
            $evento->empresa_id = Evento::getIdEmpresa();
            $evento->tipoevento_id =$request->input('tipoevento_id');
            $evento->opcionevento = $request->input('opcionevento');
            $evento->fechai     = $request->input('fechaInicio');
            $evento->fechaf     = $request->input('fechaFin');
            $evento->save();
            //*************** */
            Evento::eliminarDetalle($id);
            if($request->input('cadenaDirecciones') != ''){
                $direcciones = explode(",", $request->input('cadenaDirecciones'));
                for( $i=0; $i< count($direcciones); $i++){
                    $direccion_evento = new  Direccion_evento();

                    $direc =  explode(":", $direcciones[$i]);
                    $direccion_evento->evento_id = $id;
                    if((int)$direc[0]!=-1){
                    $direccion_evento->facultad_id = (int)$direc[0];
                    }
                    if((int)$direc[1]!=-1){
                        $direccion_evento->facultad_id = null;
                    $direccion_evento->escuela_id = (int)$direc[1];
                    }
                    if((int)$direc[2]!=-1){
                        $direccion_evento->escuela_id =null;
                    $direccion_evento->especialidad_id = (int) $direc[2];
                    }
                    $direccion_evento->save();
                }
            }
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
