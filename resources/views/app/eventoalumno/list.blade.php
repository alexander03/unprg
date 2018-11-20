<?php
use App\EventoAlumno;
use App\Evento;
use Illuminate\Support\Facades\DB;
?>

<table id="example1" class="table table-bordered table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th style='width: 5%' class='text-center'>#</th>
			<th style='width: 30%'>EVENTO</th>
			<th style='width: 20%' class='text-center'>FECHA APERTURA</th>
			<th style='width: 20%' class='text-center'>FECHA CESE</th>
			<th style='width: 25%' colspan="2">OPERACIONES</th>
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
		$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE, E.PONENTE, E.DIRECCION, E.HORA, E.ACCESO, EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF, EMP.RAZONSOCIAL, EMP.TELEFONO, EMP.EMAIL  
		FROM EVENTO E 
		     LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID 
			 INNER JOIN EMPRESA EMP ON EMP.ID = E.EMPRESA_ID 
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
			echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ".$alumno_id.">".$txtbtn."</button></td>";
			
			echo "<td><button class='btn-ver btn btn-xs btn-light' idevento='".$r->id."' nombreevt= '".$r->nombre."' detalleevt= '".$r->detalle.
			"' direccionevt = '".$r->direccion."' horaevt = '".$r->hora."' accesoevt = '".$r->acceso."' ponenteevt = '".$r->ponente.
			"' empresaevt = '".$r->razonsocial."' emailevt = '".$r->email."' telefonoevt = '".$r->telefono."' data-toggle='modal' data-target='#detalleModal'>Visualizar</button>";

			echo "</tr>";
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


<div class="modal fade" id="detalleModal" tabindex="-1" role="dialog" aria-labelledby="detalleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalle del evento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  	<fieldset class="col-12">
			<div class="form-group col-lg-12 col-sm-12">
				<label for="nombrevt" class="col-lg-4 col-sm-4 col-xs-12 control-label" style="float: left;">Nombre: </label>
				<p id="nombrevt" class="col-lg-8 col-sm-8 col-xs-12 control-label" style="float: right;"></p>
			</div>
			
			<div class="form-group col-lg-12 col-sm-12">
				<label for="empresa" class="col-lg-4 col-sm-4 col-xs-12 control-label" >Empresa: </label>
				<p id="empresa" class="col-lg-8 col-sm-8 col-xs-12 control-label" ></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12">
				<label for="email" class="col-lg-4 col-sm-4 col-xs-12 control-label" >Email: </label>
				<p id="email" class="col-lg-8 col-sm-8 col-xs-12 control-label" ></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12">
				<label for="telefono" class="col-lg-4 col-sm-4 col-xs-12 control-label" >Teléfono: </label>
				<p id="telefono" class="col-lg-8 col-sm-8 col-xs-12 control-label" ></p>
			</div>

			<div class="form-group col-lg-12 col-sm-12" >
				<label for="direccion" class="col-lg-4 col-sm-4 col-xs-12 control-label float-left" >Dirección de evento: </label>
				<p id="direccion" class="col-lg-8 col-sm-8 col-xs-12 control-label float-right" ></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12" >
				<label for="hora" class="col-lg-4 col-sm-4 col-xs-12 control-label float-left" >Hora del evento: </label>
				<p id="hora" class="col-lg-8 col-sm-8 col-xs-12 control-label float-right" ></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12" >
				<label for="acceso" class="col-lg-4 col-sm-4 col-xs-12 control-label float-left">Acceso: </label>
				<p id="acceso" class="col-lg-8 col-sm-8 col-xs-12 control-label float-right" ></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12">
				<label for="ponente" class="col-lg-4 col-sm-4 col-xs-12 control-label float-left" >Ponente: </label>
				<p id="ponente" class="col-lg-8 col-sm-8 col-xs-12 control-label float-right" ></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12">
				<label for="descripcion" class="col-lg-4 col-sm-4 col-xs-12 control-label" style="float: left;" >Descripción del evento: </label>
				<textarea  id="descripcion" class="col-lg-8 col-sm-8 col-xs-12 control-label rounded" rows="8"  style="float: right;" readonly></textarea >
			</div>
		</fieldset>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
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

		 $('.btn-ver').each(function (){
			 $(this).click(function (){
				$("#nombrevt").text(''+$(this).attr('nombreevt'));
				$("#descripcion").text(''+$(this).attr('detalleevt'));
				$("#empresa").text(''+$(this).attr('empresaevt'));
				$("#email").text(''+$(this).attr('emailevt'));
				$("#telefono").text(''+$(this).attr('telefonoevt'));
				$("#direccion").text(''+$(this).attr('direccionevt'));
				$("#hora").text(''+$(this).attr('horaevt'));
				$("#acceso").text(''+($(this).attr('accesoevt') === '0')?'Libre':'Pagado');
				$("#ponente").text(''+$(this).attr('ponenteevt'));
			 });
		 });
		 
		 
	 });

	function procesarAjax(accion, idelementCargando, id){
        var route = 'ofertaalumno/'+accion;
        route += '?id='+id;
        $.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
			beforeSend: function(){
                var tempCargando;
				tempCargando= "<tr><td colspan='6'>"+imgCargando()+"</td></tr>";                
				$('#'+ idelementCargando).html(tempCargando);
	        },
	        success: function(JSONRESPONSE){
                $('#'+ idelementCargando).html('');
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
