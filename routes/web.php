<?php

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

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');

Route::get('/', function(){
    return redirect('/dashboard');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function(){
        return View::make('dashboard.home');
    });

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

    /*ALUMNO*/
    Route::post('alumno/buscar', 'AlumnoController@buscar')->name('alumno.buscar');
    Route::get('alumno/eliminar/{id}/{listarluego}', 'AlumnoController@eliminar')->name('alumno.eliminar');
    Route::resource('alumno', 'AlumnoController', array('except' => array('show')));

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