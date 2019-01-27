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
use App\OfertaAlumno;
use App\EventoAlumno;
use App\Facultad;
use App\Escuela;
use App\Experiencias_Laborales;
use App\Certificado;
use App\CompetenciaAlumno;
use Illuminate\Support\Facades\DB;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;
use PDF;


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
        $fechai = Libreria::getParam($request->input('fechai'));
        $fechaf = Libreria::getParam($request->input('fechaf'));
        $resultado        = Oferta::listarsuscritos($nombre, $empresa_id, $fechai, $fechaf);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Descripción', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Tipo Evento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cantidad Suscritos', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Reporte pdf', 'numero' => '1');
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
        $listarSuscriptores        = OfertaAlumno::listarSuscriptores($id);
        $lista           = $listarSuscriptores->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Alumno', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Curriculum', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Enviar e-mail', 'numero' => '1');
        $ruta             = $this->rutas;

        $tituloListar = $this->tituloListar;
        $ruta             = $this->rutas;
        $inicio           = 0;
        if (count($lista) > 0) {
            return view($this->folderview.'.suscriptores')->with(compact('lista', 'entidad', 'cabecera', 'tituloListar', 'ruta', 'inicio', 'id'));
        }
        return view($this->folderview.'.suscriptores')->with(compact('lista', 'entidad', 'id', 'ruta'));

    }

    public function PDFOferta($id, Request $request)
    {    
        $listarSuscriptoresPDF        = OfertaAlumno::listarSuscriptoresPDF($id);
        $lista           = $listarSuscriptoresPDF->get();
        $oferta       = Oferta::find($id);
        $nomoferta = $oferta->nombre;
        $view = \View::make('app.ofertapublicacion.PDFOferta')->with(compact('lista','oferta', 'id'));
        $html_content = $view->render();      
 
        PDF::SetTitle($nomoferta);
        PDF::AddPage('L','A4',0);
        PDF::SetDisplayMode('fullpage');
        PDF::writeHTML($html_content, true, false, true, false, '');
 
        PDF::Output($nomoferta.'.pdf', 'I');
    }

    public function vercurriculum(Request $request, $id)
    {    
        $alumno = DB::table('alumno')->where('id', $id)->first();
        $nombrealumno = $alumno->apellidopaterno . '_' . $alumno->apellidomaterno;
        $nombrealumno = 'CV_' . $nombrealumno;
        $explaborales = Experiencias_Laborales::listartodo($id)->get();
        $competencias = CompetenciaAlumno::listar($id,'')->get();
        $certificados = Certificado::listarparacv($id,'')->get();

        $view = \View::make('app.ofertapublicacion.vercurriculum')->with(compact('alumno', 'explaborales', 'competencias', 'certificados'));
        $html_content = $view->render();      
 
        PDF::SetTitle($nombrealumno);
        PDF::AddPage(); 

        // set margins
        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(PDF_MARGIN_HEADER);
        PDF::SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);
        PDF::writeHTML($html_content, true, true, true, true, '');

        PDF::Output($nombrealumno.'.pdf', 'I');
    }

}
