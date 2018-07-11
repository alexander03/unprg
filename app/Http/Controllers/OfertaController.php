<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Oferta;
use App\Direccion_oferta;
use App\Empresa;
use App\Tipoevento;
use App\Facultad;
use App\Escuela;
use App\Especialidad;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class OfertaController extends Controller
{
    protected $folderview      = 'app.oferta';
    protected $tituloAdmin     = 'Oferta';
    protected $tituloRegistrar = 'Registrar Oferta';
    protected $tituloModificar = 'Modificar Oferta';
    protected $tituloEliminar  = 'Eliminar Oferta';
    protected $rutas           = array('create' => 'oferta.create', 
            'edit'   => 'oferta.edit',
            'delete' => 'oferta.eliminar',
            'search' => 'oferta.buscar',
            'index'  => 'oferta.index',
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
        $resultado        = Oferta::listar($nombre, $empresa_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Empresa', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Tipo Evento', 'numero' => '1');
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
        $entidad          = 'Oferta';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboFacultad = [''=>'Todos'];// + Facultad::pluck('nombre', 'id')->all();
        $cboEscuela = [''=>'Todos']; //+ Escuela::pluck('nombre', 'id')->all();
        $cboEspecialidad = [''=>'Todos']; //+ Especialidad::pluck('nombre', 'id')->all();
        $cboOpcionOferta    = array('0'=>'Libre','1' => 'Con restricciones');
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cboFacultad','cboEscuela','cboEspecialidad','cboOpcionOferta'));
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
        $entidad        = 'Oferta';
        $oferta        = null;
        $cboFacultad = array('' => 'Seleccione') + Facultad::pluck('nombre', 'id')->all();
        $cboEscuela = array('' => 'Seleccione');// + Escuela::pluck('nombre', 'id')->all();
        $cboEspecialidad = array('' => 'Seleccione') ;//+ Especialidad::pluck('nombre', 'id')->all();
        $cboOpcionOferta = array('0'=>'Libre','1' => 'Con restricciones');
        $formData  = array('oferta.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('oferta', 'formData', 'entidad', 'boton', 'listar','cboFacultad','cboEscuela','cboEspecialidad','cboOpcionOferta'));
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
            'nombre'            => 'required|max:200',
            )
        );
        
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }

        $error = DB::transaction(function() use($request){
            $oferta               = new Oferta();
            $oferta->nombre = $request->input('nombre');
            $oferta->empresa_id = Oferta::getIdEmpresa();
            $oferta->opcionevento =$request->input('opcionoferta');
            $oferta->save();

            if($request->input('cadenaDirecciones') != ''){
                $direcciones = explode(",", $request->input('cadenaDirecciones'));
                for( $i=0; $i< count($direcciones); $i++){
                    $direccion_oferta = new  Direccion_oferta();

                    $direc =  explode(":", $direcciones[$i]);
                    $direccion_oferta->evento_id = (int)$oferta->id;
                    if((int)$direc[0]!=-1){
                    $direccion_oferta->facultad_id = (int)$direc[0];
                    }
                    if((int)$direc[1]!=-1){
                        $direccion_oferta->facultad_id = null;
                    $direccion_oferta->escuela_id = (int)$direc[1];
                    }
                    if((int)$direc[2]!=-1){
                        $direccion_oferta->escuela_id =null;
                    $direccion_oferta->especialidad_id = (int) $direc[2];
                    }
                    $direccion_oferta->save();
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
   
        $listaDetalle        = Oferta::listarDetalleOferta( $id);
        $listaDet           = $listaDetalle->get();

        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $oferta       = Oferta::find($id);
        $entidad        = 'Oferta';
        $cboFacultad = array('' => 'Seleccione') + Facultad::pluck('nombre', 'id')->all();
        $cboEscuela = array('' => 'Seleccione') + Escuela::pluck('nombre', 'id')->all();
        $cboEspecialidad = array('' => 'Seleccione') + Especialidad::pluck('nombre', 'id')->all();
        $cboOpcionOferta = array('0'=>'Libre','1' => 'Con restricciones');
        $formData       = array('oferta.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('oferta', 'formData', 'entidad', 'boton', 'listar','listaDet','cboFacultad','cboEscuela','cboEspecialidad','cboOpcionOferta'));
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
            'nombre'            => 'required|max:200',
            )
        );

        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $oferta                 = Oferta::find($id);
            $oferta->nombre = $request->input('nombre');
            $oferta->empresa_id = Oferta::getIdEmpresa();
            $oferta->opcionevento = $request->input('opcionoferta');
            //$evento->tipoevento_id = $request->input('tipoevento_id');
            $oferta->save();
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
            $oferta = Oferta::find($id);
            $oferta->delete();
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
        $modelo   = Oferta::find($id);
        $entidad  = 'Oferta';
        $formData = array('route' => array('oferta.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
