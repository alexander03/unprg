<?php

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication routes...
// Route::get('auth/login', 'Auth\AuthController@getLogin');
// Route::post('auth/login', ['as' =>'auth/login', 'uses' => 'Auth\AuthController@postLogin']);
// Route::get('auth/logout', ['as' => 'auth/logout', 'uses' => 'Auth\AuthController@getLogout']);

// Registration routes...
// Route::get('auth/register', 'Auth\AuthController@getRegister');
// Route::post('auth/register', ['as' => 'auth/register', 'uses' => 'Auth\AuthController@postRegister']);

//Auth::routes();

Route::get('seguimiento/login', 'Auth\LoginSeguimientoController@showLoginForm')->name('login');
Route::post('seguimiento/login', 'Auth\LoginSeguimientoController@login');
Route::get('seguimiento/logout', 'Auth\LoginSeguimientoController@logout');
Route::post('seguimiento/logout', 'Auth\LoginSeguimientoController@logout');

Route::get('bolsa/login', 'Auth\LoginBolsaController@showLoginForm')->name('login');
Route::post('bolsa/login', 'Auth\LoginBolsaController@login');
Route::get('bolsa/logout', 'Auth\LoginBolsaController@logout');
Route::post('bolsa/logout', 'Auth\LoginBolsaController@logout');

/*
Route::get('/', function(){
    return redirect('/bolsa');
});
*/

Route::group(['middleware' => 'auth'], function () {

        Route::get('/seguimiento', function(){
            if(Auth::user()->usertype_id == "2" || Auth::user()->usertype_id == "5"|| Auth::user()->usertype_id == "1"){
                return View::make('seguimiento.home');
            }else{
                return redirect('/bolsa');
            }
        });

        Route::get('/bolsa', function(){
            if(Auth::user()->usertype_id == "3" || Auth::user()->usertype_id == "4"|| Auth::user()->usertype_id == "1"){
                return View::make('bolsa.home');
            }else{
                return redirect('/seguimiento');
            }
        });

    /*ACTUALIZAR DATOS*/
    Route::resource('actualizardatos', 'ActualizarDatosController', array('except' => array('show')));


    Route::post('encuesta/buscar', 'EncuestaController@buscar')->name('encuesta.buscar');
    Route::get('encuesta/eliminar/{id}/{listarluego}', 'EncuestaController@eliminar')->name('encuesta.eliminar');
    Route::resource('encuesta', 'EncuestaController', array('except' => array('show')));

    Route::post('tipoencuesta/buscar', 'TipoencuestaController@buscar')->name('tipoencuesta.buscar');
    Route::get('tipoencuesta/eliminar/{id}/{listarluego}', 'TipoencuestaController@eliminar')->name('tipoencuesta.eliminar');
    Route::resource('tipoencuesta', 'TipoencuestaController', array('except' => array('show')));

    Route::post('categoriaopcionmenu/buscar', 'CategoriaopcionmenuController@buscar')->name('categoriaopcionmenu.buscar');
    Route::get('categoriaopcionmenu/eliminar/{id}/{listarluego}', 'CategoriaopcionmenuController@eliminar')->name('categoriaopcionmenu.eliminar');
    Route::resource('categoriaopcionmenu', 'CategoriaopcionmenuController', array('except' => array('show')));

    Route::post('opcionmenu/buscar', 'OpcionmenuController@buscar')->name('opcionmenu.buscar');
    Route::get('opcionmenu/eliminar/{id}/{listarluego}', 'OpcionmenuController@eliminar')->name('opcionmenu.eliminar');
    Route::resource('opcionmenu', 'OpcionmenuController', array('except' => array('show')));

    Route::post('tipousuario/buscar', 'TipousuarioController@buscar')->name('tipousuario.buscar');
    Route::get('tipousuario/obtenerpermisos/{listar}/{id}', 'TipousuarioController@obtenerpermisos')->name('tipousuario.obtenerpermisos');
    Route::post('tipousuario/guardarpermisos/{id}', 'TipousuarioController@guardarpermisos')->name('tipousuario.guardarpermisos');
    Route::get('tipousuario/eliminar/{id}/{listarluego}', 'TipousuarioController@eliminar')->name('tipousuario.eliminar');
    Route::resource('tipousuario', 'TipousuarioController', array('except' => array('show')));
    
    /*TIPO EVENTO*/
    Route::post('tipoevento/buscar', 'TipoeventoController@buscar')->name('tipoevento.buscar');
    Route::get('tipoevento/eliminar/{id}/{listarluego}', 'TipoeventoController@eliminar')->name('tipoevento.eliminar');
    Route::resource('tipoevento', 'TipoeventoController', array('except' => array('show')));

    /*SECTOR*/
    Route::post('sector/buscar', 'SectorController@buscar')->name('sector.buscar');
    Route::get('sector/eliminar/{id}/{listarluego}', 'SectorController@eliminar')->name('sector.eliminar');
    Route::resource('sector', 'SectorController', array('except' => array('show')));


    /*EVENTO*/
    Route::post('evento/buscar', 'EventoController@buscar')->name('evento.buscar');
    Route::get('evento/eliminar/{id}/{listarluego}', 'EventoController@eliminar')->name('evento.eliminar');
    Route::resource('evento', 'EventoController', array('except' => array('show')));
    Route::get('socio/asistente', 'EventoController@asistente')->name('evento.asistente');
    Route::post('evento/buscarasistente', 'EventoController@buscarasistente')->name('evento.buscarasistente');
    Route::post('evento/cargarasistente', 'EventoController@cargarasistente')->name('evento.cargarasistente');

    /*SOCIO*/
    Route::post('socio/buscar', 'SocioController@buscar')->name('socio.buscar');
    Route::get('socio/eliminar/{id}/{listarluego}', 'SocioController@eliminar')->name('socio.eliminar');
    Route::resource('socio', 'SocioController', array('except' => array('show')));

    Route::post('employee/buscar', 'EmployeeController@buscar')->name('employee.buscar');
    Route::get('employee/eliminar/{id}/{listarluego}', 'EmployeeController@eliminar')->name('employee.eliminar');
    Route::resource('employee', 'EmployeeController', array('except' => array('show')));

    /* CUSTOMERS */
    Route::post('customer/search', 'CustomerController@search')->name('customer.search');
    Route::get('customer/eliminar/{id}/{listarluego}', 'CustomerController@eliminar')->name('customer.eliminar');
    Route::resource('customer', 'CustomerController', array('except' => array('show')));

    /* PROVIDERS */
    Route::post('provider/search', 'ProviderController@search')->name('provider.search');
    Route::get('provider/eliminar/{id}/{listarluego}', 'ProviderController@eliminar')->name('provider.eliminar');
    Route::resource('provider', 'ProviderController', array('except' => array('show')));

    /* COMPANY */
    Route::post('company/search', 'CompanyController@search')->name('company.search');
    Route::get('company/eliminar/{id}/{listarluego}', 'CompanyController@eliminar')->name('company.eliminar');
    Route::resource('company', 'CompanyController', array('except' => array('show')));

    Route::post('usuario/buscar', 'UsuarioController@buscar')->name('usuario.buscar');
    Route::get('usuario/eliminar/{id}/{listarluego}', 'UsuarioController@eliminar')->name('usuario.eliminar');
    Route::resource('usuario', 'UsuarioController', array('except' => array('show')));

    /*PERSON*/
    Route::post('person/search', 'PersonController@search')->name('person.search');
    Route::get('person/employeesautocompleting/{searching}', 'PersonController@employeesautocompleting')->name('person.employeesautocompleting');
    Route::get('person/providersautocompleting/{searching}', 'PersonController@providersautocompleting')->name('person.providersautocompleting');
    Route::get('person/customersautocompleting/{searching}', 'PersonController@customersautocompleting')->name('person.customersautocompleting');

    /* EMPRESA */
    Route::post('empresa/buscar', 'EmpresaController@buscar')->name('empresa.buscar');
    Route::get('empresa/eliminar/{id}/{listarluego}', 'EmpresaController@eliminar')->name('empresa.eliminar');
    Route::resource('empresa', 'EmpresaController', array('except' => array('show')));

   /* PREGUNTAS */
    Route::get('encuesta/listarpreguntas/{encuesta_id}', 'EncuestaController@listarpreguntas')->name('encuesta.listarpreguntas');
    Route::get('encuesta/nuevapregunta/{encuesta_id}', 'EncuestaController@nuevapregunta')->name('encuesta.nuevapregunta');
    Route::get('encuesta/eliminarpregunta/{id}/{encuesta_id}', 'EncuestaController@eliminarpregunta')->name('encuesta.eliminarpregunta');

    /* ALTERNATIVAS */
    Route::get('encuesta/listaralternativas/{pregunta_id}', 'EncuestaController@retornarTablaAlternativas')->name('encuesta.listaralternativas');
    Route::get('encuesta/nuevaalternativa/{pregunta_id}', 'EncuestaController@nuevaalternativa')->name('encuesta.nuevaalternativa');
    Route::get('encuesta/eliminaralternativa/{id}/{pregunta_id}', 'EncuestaController@eliminaralternativa')->name('encuesta.eliminaralternativa');

    /* RESPUESTAS */
    Route::get('encuesta/alternativacorrecta', 'EncuestaController@alternativacorrecta')->name('encuesta.alternativacorrecta');

    /* DIRECCIONES */
    Route::get('encuesta/listardirecciones/{encuesta_id}', 'EncuestaController@listardirecciones')->name('encuesta.listardirecciones');
    Route::get('encuesta/nuevadireccion/{encuesta_id}', 'EncuestaController@nuevadireccion')->name('encuesta.nuevadireccion');
    Route::get('encuesta/eliminardireccion/{id}/{encuesta_id}', 'EncuestaController@eliminardireccion')->name('encuesta.eliminardireccion');
    Route::get('encuesta/cargarselect/{idselect}', 'EncuestaController@cargarselect')->name('encuesta.cargarselect');

    /* ALUMNO-ENCUESTAS */
    Route::post('alumnoencuesta/buscar', 'AlumnoEncuestaController@buscar')->name('alumnoencuesta.buscar');
    Route::resource('alumnoencuesta', 'AlumnoEncuestaController', array('except' => array('show')));
 
    /*ALUMNO*/
    Route::post('alumno/buscar', 'AlumnoController@buscar')->name('alumno.buscar');
    Route::get('alumno/eliminar/{id}/{listarluego}', 'AlumnoController@eliminar')->name('alumno.eliminar');
    Route::resource('alumno', 'AlumnoController', array('except' => array('show')));
    Route::get('alumno/cargarselect/{idselect}', 'AlumnoController@cargarselect')->name('alumno.cargarselect');

    /*COMPETENCIA*/
    Route::post('competencia/buscar', 'CompetenciaController@buscar')->name('competencia.buscar');
    Route::get('competencia/eliminar/{id}/{listarluego}', 'CompetenciaController@eliminar')->name('competencia.eliminar');
    Route::resource('competencia', 'CompetenciaController', array('except' => array('show')));

    /*ESPECIALIDAD*/
    Route::post('especialidad/buscar', 'EspecialidadController@buscar')->name('especialidad.buscar');
    Route::get('especialidad/eliminar/{id}/{listarluego}', 'EspecialidadController@eliminar')->name('especialidad.eliminar');
    Route::resource('especialidad', 'EspecialidadController', array('except' => array('show')));

    /*FACULTAD */
    Route::post('facultad/buscar', 'FacultadController@buscar')->name('facultad.buscar');
    Route::get('facultad/eliminar/{id}/{listarluego}', 'FacultadController@eliminar')->name('facultad.eliminar');
    Route::resource('facultad', 'FacultadController', array('except' => array('show')));

    /*ESCUELA */
    Route::post('escuela/buscar', 'EscuelaController@buscar')->name('escuela.buscar');
    Route::get('escuela/eliminar/{id}/{listarluego}', 'EscuelaController@eliminar')->name('escuela.eliminar');
    Route::resource('escuela', 'EscuelaController', array('except' => array('show')));

    /*EXPERIENCIAS LABORALES */
    Route::post('experienciaslaborales/buscar', 'Experiencias_LaboralesController@buscar')->name('experienciaslaborales.buscar');
    Route::get('experienciaslaborales/eliminar/{id}/{listarluego}', 'Experiencias_LaboralesController@eliminar')->name('experienciaslaborales.eliminar');
    Route::resource('experienciaslaborales', 'Experiencias_LaboralesController', array('except' => array('show')));
    Route::get('experienciaslaborales/obtenercompetencias/{listar}/{id}', 'Experiencias_LaboralesController@obtenerpermisos')->name('experienciaslaborales.obtenercompetencias');

    /*COMPETENCIAS ALUMNO */
    Route::post('competencia_alumno/buscar', 'CompetenciaAlumnoController@buscar')->name('competencia_alumno.buscar');
    Route::get('competencia_alumno/eliminar/{id}/{listarluego}', 'CompetenciaAlumnoController@eliminar')->name('competencia_alumno.eliminar');
    Route::resource('competencia_alumno', 'CompetenciaAlumnoController', array('except' => array('show')));

    /*CERTIFICADOS */
    Route::post('certificado/buscar', 'CertificadoController@buscar')->name('certificado.buscar');
    Route::get('certificado/eliminar/{id}/{listarluego}', 'CertificadoController@eliminar')->name('certificado.eliminar');
    Route::resource('certificado', 'CertificadoController', array('except' => array('show')));    
});

Route::get('provincia/cboprovincia/{id?}', array('as' => 'provincia.cboprovincia', 'uses' => 'ProvinciaController@cboprovincia'));
Route::get('distrito/cbodistrito/{id?}', array('as' => 'distrito.cbodistrito', 'uses' => 'DistritoController@cbodistrito'));

Route::get('storage/{archivo}', function ($archivo) {
     $public_path = storage_path();
     $url = $public_path.'/app/'.$archivo;print_r($url);
     //verificamos si el archivo existe y lo retornamos
     if (Storage::exists($archivo))
     {
       return response()->download($url);
     }
     //si no se encuentra lanzamos un error 404.
     abort(404);

});
