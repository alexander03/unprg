<?php
use App\Menuoptioncategory;
use App\Menuoption;
use App\Permission;
use App\User;
use App\Usuario;
use App\Alumno;
use App\Empresa;
$user                  = Auth::user();
session(['usertype_id' => $user->usertype_id]);
$tipousuario_id        = session('usertype_id');
$menu                  = generarMenu($tipousuario_id);
//$person                = Person::find($user->person_id);
$usuario = Usuario::find($user->id);
if($user->usertype_id == 1 || $user->usertype_id == 2 || $user->usertype_id == 3  ){ //admin - bolsa - seguimiento -> funcionan como alumno
    $alumno = Alumno::find($user->alumno_id);
    $nombrecompleto = $alumno->nombres." ".$alumno->apellidopaterno;
}else if($user->usertype_id == 4 || $user->usertype_id == 5){
    $empresa = Empresa::find($user->empresa_id);
    $razonsocial = $empresa->razonsocial;
}

?>

<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">

        <div id="sidebar-menu">
            
            {!! $menu !!}
            
            <div class="clearfix"></div>
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="user-detail" style ="text-align : center">
        <div class="dropup" style="width: 100%;">
            <a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
                <div id="avatar">
                <img src="avatar\{!! $usuario->avatar !!}" alt="user-img" class="img-circle" style ="height: 75px;width: 75px;float: none;">
                </div>
                <span class="user-info-span" style ="padding-top: 10px;">
                    @if($user->usertype_id == 1 || $user->usertype_id == 2 || $user->usertype_id == 3 )
                        <h5 class="m-t-0 m-b-0">{!! $nombrecompleto !!}</h5>
                    @elseif($user->usertype_id == 4 || $user->usertype_id == 5)
                        <h5 class="m-t-0 m-b-0">{!! $razonsocial !!}</h5>
                    @endif
                </span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="#" onclick="cargarRuta('/unprg/actualizardatos', 'container');"><i class="md md-face-unlock"></i> Perfil</a></li>
                <li><a href="#" onclick="cargarRuta('/unprg/updatepassword', 'container');"><i class="md md-vpn-key"></i> Cambiar Contraseña</a></li>
                <li><a href="bolsa/logout"><i class="md md-settings-power"></i> Cerrar Sesión</a></li>
            </ul>

        </div>
    </div>
</div>
<?php
function generarMenu($idtipousuario)
{
    $menu = array();
    #Paso 1°: Buscar las categorias principales
    $categoriaopcionmenu = new Menuoptioncategory();
    $opcionmenu          = new Menuoption();
    $permiso             = new Permission();
    $catPrincipales      = $categoriaopcionmenu->where('position','=','V')->whereNull('menuoptioncategory_id')->orderBy('order', 'ASC')->get();
    $cadenaMenu          = '<ul><li class="menu-title">Principal</li>';
    foreach ($catPrincipales as $key => $catPrincipal) {
        #Buscamos a las categorias hijo
        $hijos = buscarHijos($catPrincipal->id, $idtipousuario);
        $usar = false;
        $aux = array();
        $opciones = $opcionmenu->where('menuoptioncategory_id', '=', $catPrincipal->id)->orderBy('order', 'ASC')->get();
        if ($opciones->count()) {               
            foreach ($opciones as $key => $opcion) {
                $permisos = $permiso->where('menuoption_id', '=', $opcion->id)->where('usertype_id', '=', $idtipousuario)->first();
                if ($permisos) {
                    $usar  = true;
                    $aux2  = $opcionmenu->find($permisos->menuoption_id);
                    $aux[] = array(
                        'id'     => $aux2->id,
                        'nombre' => $aux2->name,
                        'link'   => $aux2->link,
                        'icono'  => $aux2->icon
                        );
                }
            }           
        }
        if ($hijos != '' || $usar === true ) {
            $cadenaMenu .= '<li class="has_sub">';
            $cadenaMenu .= '<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="'.$catPrincipal->icon.'"></i> <span> '.$catPrincipal->name.' </span>
                    <span class="menu-arrow"></span></a>';
            $cadenaMenu .= '<ul class="list-unstyled">';
            for ($i=0; $i < count($aux); $i++) { 
                if (strtoupper($aux[$i]['nombre']) === 'SEPARADOR') {
                    //$cadenaMenu .= '<li class="divider"></li>';
                }else{
                    $cadenaMenu .= '<li id="'.$aux[$i]['id'].'"><a href="#" onclick="cargarRutaMenu(\''.URL::to($aux[$i]['link']).'\', \'container\', \''.$aux[$i]['id'].'\');"><i class="'.$aux[$i]['icono'].'"></i> '.$aux[$i]['nombre'].'</a></li>';
                }
            }
            if (count($aux) > 0 && $hijos != '' ) {
                //$cadenaMenu .= '<li class="divider"></li>';
            }
            if ($hijos != '') {
                $cadenaMenu .= $hijos;
            }
            $cadenaMenu .= '</ul>';
            $cadenaMenu .= '</li>';
        }
    }
    $cadenaMenu .= '</ul>';
    return $cadenaMenu;
}

function buscarHijos($categoriaopcionmenu_id, $tipousuario_id)
{
    $menu = array();
    $categoriaopcionmenu = new Menuoptioncategory();
    $opcionmenu          = new Menuoption();
    $permiso             = new Permission();

    $catHijos = $categoriaopcionmenu->where('position','=','V')->where('menuoptioncategory_id', '=', $categoriaopcionmenu_id)->orderBy('order', 'ASC')->get();
    $cadenaMenu = '';
    foreach ($catHijos as $key => $catHijo) {
        $usar = false;
        $aux = array();
        $hijos = buscarHijos($catHijo->id, $tipousuario_id);
        $opciones = $opcionmenu->where('menuoptioncategory_id', '=', $catHijo->id)->orderBy('order', 'ASC')->get();
        if ($opciones->count()) {

            foreach ($opciones as $key => $opcion) {
                $permisos = $permiso->where('menuoption_id', '=', $opcion->id)->where('usertype_id', '=', $tipousuario_id)->first();
                if ($permisos) {
                    $usar = true;
                    $aux2 = $opcionmenu->find($permisos->menuoption_id);
                    $aux[] = array(
                        'id'     => $aux2->id,
                        'nombre' => $aux2->name,
                        'link'   => $aux2->link,
                        'icono'  => $aux2->icon
                        );
                }
            }

        }
        if ($hijos != '' || $usar === true ) {

            $cadenaMenu .= '<li class="has_sub">';
            $cadenaMenu .= '<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="'.$catHijo->icon.'"></i> <span> '.$catHijo->name.' </span>
                    <span class="menu-arrow"></span></a>';
            $cadenaMenu .= '<ul class="list-unstyled">';
            for ($i=0; $i < count($aux); $i++) { 
                if (strtoupper($aux[$i]['nombre']) === 'SEPARADOR') {
                    //$cadenaMenu .= '<li class="divider"></li>';
                } else {
                    $cadenaMenu .= '<li id="'.$aux[$i]['id'].'"><a onclick="cargarRutaMenu(\''.URL::to($aux[$i]['link']).'\', \'container\', \''.$aux[$i]['id'].'\');"><i class="'.$aux[$i]['icono'].'" ></i> '.$aux[$i]['nombre'].'</a></li>';
                }
            }
            if (count($aux) > 0 && $hijos != '' ) {
                //$cadenaMenu .= '<li class="divider"></li>';
            }
            if ($hijos != '') {
                $cadenaMenu .= $hijos;
            }
            $cadenaMenu .= '</ul>';
            $cadenaMenu .= '</li>';
        }
    }
    return $cadenaMenu;
}
?>