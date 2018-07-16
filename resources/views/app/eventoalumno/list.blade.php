<?php
use App\EventoAlumno;
use App\Evento;
use Illuminate\Support\Facades\DB;
?>

<table id="example1" class="table table-bordered table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th style='width: 5%' class='text-center'>#</th>
			<th>EVENTO</th>
			<th>DESCRIPCIÓN</th>
			<th style='width: 15%' class='text-center'>FECHA APERTURA</th>
			<th style='width: 15%' class='text-center'>FECHA CESE</th>
			<th style='width: 10%'>OPERACIONES</th>
		</tr>
	</thead>

	<tbody id='registrostabla'>
		<?php
		$cant_filas = $filas;
		$inicio = 0;
		$contador = $inicio + 1;
		$contadortemp = 0;

		$classbtn = '';
		$txtbtn = '';

		$alumno_id = EventoALumno::getIdALumno();
        $escuela_id = DB::table('Alumno')->where('id', $alumno_id)->value('escuela_id');
        $especialidad_id = DB::table('Alumno')->where('id', $alumno_id)->value('especialidad_id');
        $facultad_id = DB::table('Escuela')->where('id', $escuela_id)->value('facultad_id');
		$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE, EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF FROM EVENTO E 
		     LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID 
			 WHERE E.OPCIONEVENTO = 0 AND ROWNUM <= ".$cant_filas."  
			 AND E.TIPOEVENTO_ID IS NOT NULL AND E.NOMBRE LIKE '%".$nombre."%' AND E.FECHAF BETWEEN TO_DATE('".$fechai."','yyyy-mm-dd') AND TO_DATE('".$fechaf."','yyyy-mm-dd') ");
        foreach ($result as $r) {
			if($r->id_validador != null){
				$classbtn  = 'btn btn-xs btn-danger btn-block btn-des';
				$txtbtn = 'SALIR';
			}else{
				$classbtn  = 'btn btn-xs btn-warning btn-block btn-sus';
				$txtbtn = 'POSTULAR';
			}
			echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td>".$r->detalle."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ".$alumno_id.">".$txtbtn."</button></td></tr>";
			$contador++;
			$contadortemp++;
		}
		$cant_filas = $cant_filas - $contador;
		if($cant_filas>0){
			$contadortemp = 0;
			$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE,EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF FROM EVENTO E 
			 LEFT JOIN DIRECCION_EVENTO DE ON DE.EVENTO_ID = E.ID 
			 LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID 
			 where ROWNUM <= ".$cant_filas." AND DE.FACULTAD_ID = ".$facultad_id." AND E.TIPOEVENTO_ID IS NOT NULL 
			 AND NOMBRE LIKE '%".$nombre."%' AND E.FECHAF BETWEEN TO_DATE('".$fechai."','yyyy-mm-dd') AND TO_DATE('".$fechaf."','yyyy-mm-dd') ");
			foreach ($result as $r) {
				if($r->id_validador != null){
					$classbtn  = 'btn btn-xs btn-danger btn-block btn-des';
					$txtbtn = 'SALIR';
				}else{
					$classbtn  = 'btn btn-xs btn-warning btn-block btn-sus';
					$txtbtn = 'POSTULAR';
				}
				echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td>".$r->detalle."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ".$alumno_id.">".$txtbtn."</button></td></tr>";
				$contador++;
				$contadortemp++;
			}
		}

		$cant_filas = $cant_filas - $contador;
		if($cant_filas>0){
			$contadortemp = 0;
			$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE, EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF FROM EVENTO E 
			 LEFT JOIN DIRECCION_EVENTO DE ON DE.EVENTO_ID = E.ID 
			 LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID 
			 where ROWNUM <= ".$cant_filas." AND DE.ESCUELA_ID = ".$escuela_id." AND E.TIPOEVENTO_ID IS NOT NULL 
			 AND NOMBRE LIKE '%".$nombre."%' AND E.FECHAF BETWEEN TO_DATE('".$fechai."','yyyy-mm-dd') AND TO_DATE('".$fechaf."','yyyy-mm-dd') ");
			foreach ($result as $r) {
				if($r->id_validador != null){
					$classbtn  = 'btn btn-xs btn-danger btn-block btn-des';
					$txtbtn = 'SALIR';
				}else{
					$classbtn  = 'btn btn-xs btn-warning btn-block btn-sus';
					$txtbtn = 'POSTULAR';
				}
				echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td>".$r->detalle."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ".$alumno_id.">".$txtbtn."</button></td></tr>";
				$contador++;
				$contadortemp++;
			}
		}

		$cant_filas = $cant_filas - $contador;
		if($cant_filas>0){
			$contadortemp = 0;
			$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE, EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF FROM EVENTO E 
			 LEFT JOIN DIRECCION_EVENTO DE ON DE.EVENTO_ID = E.ID
			 LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID 
			 where ROWNUM <= ".$cant_filas." AND DE.ESPECIALIDAD_ID = ".$especialidad_id." AND E.TIPOEVENTO_ID IS NOT NULL 
			 AND NOMBRE LIKE '%".$nombre."%' AND E.FECHAF BETWEEN TO_DATE('".$fechai."','yyyy-mm-dd') AND TO_DATE('".$fechaf."','yyyy-mm-dd') ");
			foreach ($result as $r) {
				if($r->id_validador != null){
					$classbtn  = 'btn btn-xs btn-danger btn-block btn-des';
					$txtbtn = 'SALIR';
				}else{
					$classbtn  = 'btn btn-xs btn-warning btn-block btn-sus';
					$txtbtn = 'POSTULAR';
				}
				echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td>".$r->detalle."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ".$alumno_id.">".$txtbtn."</button></td></tr>";
				$contador++;
				$contadortemp++;
			}
		}
		?>
		
	</tbody>
	<tfoot>
	</tfoot>
</table>

<script>

	 $(document).ready(function() {

		 $('.btn-sus').each(function (){
			 $(this).click(function (){
				 procesarAjax('suscribir','registrostabla',$(this).attr('idevento'));
			 });
		 });

		 $('.btn-des').each(function (){
			 $(this).click(function (){
				 procesarAjax('dessuscribir','registrostabla',$(this).attr('idevento'));
			 });
		 });
		 
		 
	 });

	function procesarAjax(accion, idelementCargando, id){
        var route = 'ofertaalumno/'+accion;
        route += '?id='+id;
        console.log(route);
        $.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
			beforeSend: function(){
                var tempCargando;
				tempCargando= "<tr><td colspan='5'>"+imgCargando()+"</td></tr>";                
				$('#'+ idelementCargando).html(tempCargando);
	        },
	        success: function(JSONRESPONSE){
                $('#'+ idelementCargando).html('');
				console.log(JSONRESPONSE);
				if(JSONRESPONSE.toLowerCase()==="ok"){
					if(accion.toLowerCase()==="suscribir"){
						mostrarMensaje('Suscripción exitosa!','OK');
					}else{
						mostrarMensaje('Cambios guardados!','OK');
					}
					buscar('{{ $entidad }}');
				}else{
					mostrarMensaje('Error interno en el Servidor!','ERROR');
				}
            },
            error: function () {
                $('#'+ idelementCargando).html('');
                /*MOSTRAMOS MENSAJE ERROR SERVIDOR*/
                mostrarMensaje('Error interno en el Servidor!','ERROR');
            }
        });
    }
</script>
