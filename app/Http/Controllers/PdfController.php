<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use PDF;
use Illuminate\Support\Facades\Auth;
use App\Alumno;
use App\Experiencias_Laborales;
use App\CompetenciaAlumno;
use App\Librerias\Libreria;

class PdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generarcurriculum(Request $request)
    {    
        $user         = Auth::user();
        $alumno       = Alumno::find($user->alumno_id);
        $nombrealumno = $alumno->apellidopaterno . '_' . $alumno->apellidomaterno;
        $nombrealumno = 'CV_' . $nombrealumno;
        $explaborales = Experiencias_Laborales::listartodo($user->alumno_id)->get();
        $competencias = CompetenciaAlumno::listar($user->alumno_id,'')->get();

        $view = \View::make('app.reporte.generarcurriculum')->with(compact('alumno', 'explaborales', 'competencias'));
        $html_content = $view->render();      
 
        PDF::SetTitle($nombrealumno);
        PDF::AddPage();
        PDF::writeHTML($html_content, true, false, true, false, '');
 
        PDF::Output($nombrealumno.'.pdf', 'D');
    }
}
