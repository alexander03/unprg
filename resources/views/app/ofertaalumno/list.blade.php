<?php
use App\OfertaAlumno;
use App\Oferta;
use Illuminate\Support\Facades\DB;
?>

<table id="example1" class="table table-bordered table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th style='width: 5%' class='text-center'>#</th>
			<th class='text-center'>OFERTA</th>
			<th class='text-center'>REQUISITOS</th>
			<th class='text-center'>EXPERIENCIA</th>
			<th style='width: 10%' class='text-center'>FECHA APERTURA</th>
			<th style='width: 10%' class='text-center'>FECHA CESE</th>
			<th style='width: 10%' class='text-center' colspan="2">OPERACIONES</th>
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
		$alumno_id = OfertaALumno::getIdALumno();
        $escuela_id = DB::table('Alumno')->where('id', $alumno_id)->value('escuela_id');
        $especialidad_id = DB::table('Alumno')->where('id', $alumno_id)->value('especialidad_id');
		$facultad_id = DB::table('Escuela')->where('id', $escuela_id)->value('facultad_id');
		echo("facultad_id: ".$facultad_id);
		if($facultad_id == '' || $alumno_id == 1){
			$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE, E.TEMPORALIDAD, E.DEDICACION, E.REQUISITOS, E.EXPERIENCIA, EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF, 
			 EMP.RUC, EMP.RAZONSOCIAL, EMP.TELEFONO, EMP.EMAIL  
			 FROM EVENTO E LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID 
			 INNER JOIN EMPRESA EMP ON EMP.ID = E.EMPRESA_ID 
			 WHERE ROWNUM <= ".$cant_filas."  
			 AND E.TIPOEVENTO_ID IS NULL AND E.NOMBRE LIKE '%".$nombre."%' AND E.FECHAF BETWEEN TO_DATE('".$fechai."','yyyy-mm-dd') AND TO_DATE('".$fechaf."','yyyy-mm-dd') ");
			foreach ($result as $r) {
					$classbtn  = 'btn btn-xs btn-light btn-block';
					$txtbtn = '- ° -';
				echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td class='text-center'>".$r->requisitos."</td><td class='text-center'>".$r->experiencia."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ''>- ° -</button><td>";
				echo "<button class='btn btn-default btn-xs btn-ver' idoferta='".$r->id."' nombreOferta='".$r->nombre."' detalleOferta='".$r->detalle."' temporalidadOferta ='".$r->temporalidad."' dedicacionOferta='".$r->dedicacion."' requisitosOferta = '".$r->requisitos."' expererienciaOferta='".$r->experiencia."'  data-toggle='modal' data-target='#detalleModal'>VER DETALLE</button>";
				echo "</td></tr>";
				$contador++;
				$contadortemp++;
			}
		}else{

			$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE, E.TEMPORALIDAD, E.DEDICACION, E.REQUISITOS, E.EXPERIENCIA, EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF, 
				EMP.RUC, EMP.RAZONSOCIAL, EMP.TELEFONO, EMP.EMAIL  
				FROM EVENTO E LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID 
				INNER JOIN EMPRESA EMP ON EMP.ID = E.EMPRESA_ID 
				WHERE E.OPCIONEVENTO = 0 AND ROWNUM <= ".$cant_filas."  
				AND E.TIPOEVENTO_ID IS NULL AND E.NOMBRE LIKE '%".$nombre."%' AND E.FECHAF BETWEEN TO_DATE('".$fechai."','yyyy-mm-dd') AND TO_DATE('".$fechaf."','yyyy-mm-dd') ");
			foreach ($result as $r) {
				if($r->id_validador != null){
					$classbtn  = 'btn btn-xs btn-danger btn-block btn-des';
					$txtbtn = 'SALIR';

				}else{
					$classbtn  = 'btn btn-xs btn-warning btn-block btn-sus';
					$txtbtn = 'POSTULAR';
				}
				echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ".$alumno_id.">".$txtbtn."</button><td>";
				echo "<button class='btn btn-default btn-xs btn-ver' idoferta='".$r->id."' nombreOferta='".$r->nombre."' detalleOferta='".$r->detalle."' temporalidadOferta ='".$r->temporalidad."' dedicacionOferta='".$r->dedicacion."' requisitosOferta = '".$r->requisitos."' expererienciaOferta='".$r->experiencia."'  data-toggle='modal' data-target='#detalleModal'>VER DETALLE</button>";
				echo "</td></tr>";
				$contador++;
				$contadortemp++;
			}
			$cant_filas = $cant_filas - $contador;
			if($cant_filas>0){
				$facultad_id = ($facultad_id =='')?0:$facultad_id;
				$contadortemp = 0;
				$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE, E.TEMPORALIDAD, E.DEDICACION, E.REQUISITOS, E.EXPERIENCIA, EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF, 
				EMP.RUC, EMP.RAZONSOCIAL, EMP.TELEFONO, EMP.EMAIL
				FROM EVENTO E 
				LEFT JOIN DIRECCION_EVENTO DE ON DE.EVENTO_ID = E.ID 
				LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID 
				INNER JOIN EMPRESA EMP ON EMP.ID = E.EMPRESA_ID 
				where ROWNUM <= ".$cant_filas." AND DE.FACULTAD_ID = ".$facultad_id." AND E.TIPOEVENTO_ID IS NULL 
				AND NOMBRE LIKE '%".$nombre."%' AND E.FECHAF BETWEEN TO_DATE('".$fechai."','yyyy-mm-dd') AND TO_DATE('".$fechaf."','yyyy-mm-dd') ");
				foreach ($result as $r) {
					$fechaActual = strtotime(date("d-m-Y"));
					$fechafinal=strtotime($r->fechai);
					if($fechaActual>$fechafinal){
						$classbtn  = 'btn btn-xs btn-light btn-block ';
						$txtbtn = 'CADUCÓ';
					}else{
						if($r->id_validador != null){
							$classbtn  = 'btn btn-xs btn-danger btn-block btn-des';
							$txtbtn = 'SALIR';
						}else{
							$classbtn  = 'btn btn-xs btn-warning btn-block btn-sus';
							$txtbtn = 'POSTULAR';
						}
					}
					echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td class='text-center'>".$r->requisitos."</td><td class='text-center'>".$r->experiencia."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ''>- ° -</button><td>";
				echo "<button class='btn btn-default btn-xs btn-ver' idoferta='".$r->id."' nombreOferta='".$r->nombre."' detalleOferta='".$r->detalle."' temporalidadOferta ='".$r->temporalidad."' dedicacionOferta='".$r->dedicacion."' requisitosOferta = '".$r->requisitos."' expererienciaOferta='".$r->experiencia."'  data-toggle='modal' data-target='#detalleModal'>VER DETALLE</button>";
					echo "</td></tr>";
					$contador++;
					$contadortemp++;
				}
			}

			$cant_filas = $cant_filas - $contador;
			if($cant_filas>0){
				$escuela_id = ($escuela_id == '')?0:$escuela_id;
				$contadortemp = 0;
				$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE, E.TEMPORALIDAD, E.DEDICACION, E.REQUISITOS, E.EXPERIENCIA, EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF,
				EMP.RUC, EMP.RAZONSOCIAL, EMP.TELEFONO, EMP.EMAIL
				FROM EVENTO E 
				LEFT JOIN DIRECCION_EVENTO DE ON DE.EVENTO_ID = E.ID 
				LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID 
				INNER JOIN EMPRESA EMP ON EMP.ID = E.EMPRESA_ID  
				where ROWNUM <= ".$cant_filas." AND DE.ESCUELA_ID = ".$escuela_id." AND E.TIPOEVENTO_ID IS NULL 
				AND NOMBRE LIKE '%".$nombre."%' AND E.FECHAF BETWEEN TO_DATE('".$fechai."','yyyy-mm-dd') AND TO_DATE('".$fechaf."','yyyy-mm-dd') ");
				foreach ($result as $r) {
					$fechaActual = strtotime(date("d-m-Y"));
					$fechafinal=strtotime($r->fechai);
					if($fechaActual>$fechafinal){
						$classbtn  = 'btn btn-xs btn-light btn-block ';
						$txtbtn = 'CADUCÓ';
					}else{
						if($r->id_validador != null){
							$classbtn  = 'btn btn-xs btn-danger btn-block btn-des';
							$txtbtn = 'SALIR';
						}else{
							$classbtn  = 'btn btn-xs btn-warning btn-block btn-sus';
							$txtbtn = 'POSTULAR';
						}
					}
					echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td class='text-center'>".$r->requisitos."</td><td class='text-center'>".$r->experiencia."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ''>- ° -</button><td>";
				echo "<button class='btn btn-default btn-xs btn-ver' idoferta='".$r->id."' nombreOferta='".$r->nombre."' detalleOferta='".$r->detalle."' temporalidadOferta ='".$r->temporalidad."' dedicacionOferta='".$r->dedicacion."' requisitosOferta = '".$r->requisitos."' expererienciaOferta='".$r->experiencia."'  data-toggle='modal' data-target='#detalleModal'>VER DETALLE</button>";
					echo "</td></tr>";
					$contador++;
					$contadortemp++;
				}
			}

			$cant_filas = $cant_filas - $contador;
			if($cant_filas>0){
				$especialidad_id = ($especialidad_id == '')?0:$especialidad_id;
				$contadortemp = 0;
				$result = DB::select("SELECT DISTINCT E.ID, E.NOMBRE, E.DETALLE, E.TEMPORALIDAD, E.DEDICACION, E.REQUISITOS, E.EXPERIENCIA, EA.EVENTO_ID AS ID_VALIDADOR, E.FECHAI, E.FECHAF, 
				EMP.RUC, EMP.RAZONSOCIAL, EMP.TELEFONO, EMP.EMAIL
				FROM EVENTO E 
				LEFT JOIN DIRECCION_EVENTO DE ON DE.EVENTO_ID = E.ID
				LEFT JOIN EVENTO_ALUMNO EA ON EA.EVENTO_ID = E.ID  
				INNER JOIN EMPRESA EMP ON EMP.ID = E.EMPRESA_ID   
				where ROWNUM <= ".$cant_filas." AND DE.ESPECIALIDAD_ID = ".$especialidad_id." AND E.TIPOEVENTO_ID IS NULL 
				AND NOMBRE LIKE '%".$nombre."%' AND E.FECHAF BETWEEN TO_DATE('".$fechai."','yyyy-mm-dd') AND TO_DATE('".$fechaf."','yyyy-mm-dd') ");
				foreach ($result as $r) {
					$fechaActual = strtotime(date("d-m-Y"));
					$fechafinal=strtotime($r->fechai);
					if($fechaActual>$fechafinal){
						$classbtn  = 'btn btn-xs btn-light btn-block ';
						$txtbtn = 'CADUCÓ';
					}else{
						if($r->id_validador != null){
							$classbtn  = 'btn btn-xs btn-danger btn-block btn-des';
							$txtbtn = 'SALIR';
						}else{
							$classbtn  = 'btn btn-xs btn-warning btn-block btn-sus';
							$txtbtn = 'POSTULAR';
						}
					}
					echo "<tr><td class='text-center'>".$contador."</td><td>".$r->nombre."</td><td class='text-center'>".$r->requisitos."</td><td class='text-center'>".$r->experiencia."</td><td class='text-center'>".Date::parse($r->fechai)->format('d/m/y')."</td><td class='text-center'>".Date::parse($r->fechaf)->format('d/m/y')."</td><td><button class='".$classbtn."' idevento='".$r->id."' idalumno = ''>- ° -</button><td>";
				echo "<button class='btn btn-default btn-xs btn-ver' idoferta='".$r->id."' nombreOferta='".$r->nombre."' detalleOferta='".$r->detalle."' temporalidadOferta ='".$r->temporalidad."' dedicacionOferta='".$r->dedicacion."' requisitosOferta = '".$r->requisitos."' expererienciaOferta='".$r->experiencia."'  data-toggle='modal' data-target='#detalleModal'>VER DETALLE</button>";
					echo "</td></tr>";
					$contador++;
					$contadortemp++;
				}
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
      </div>
      <div class="modal-body">
		<fieldset class"col-12">
			<div class="form-group col-lg-12 col-sm-12">
				<label for="nombreOfert" class="col-lg-4 col-sm-4  col-xs-12 control-label" style="float: left;">NOMBRE:</label>
				<p  id="nombreOfert" class="col-lg-8 cols-sm-8 col-xs-12 control-label rounded" style="float: right;"></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12">
				<label for="detalleOfert" class="col-lg-4 col-sm-4  col-xs-12 control-label" style="float: left;">DETALLE:</label>
				<p  id="detalleOfert" class="col-lg-8 cols-sm-8 col-xs-12 control-label rounded" style="float: right;"></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12">
				<label for="temporalidadOfert" class="col-lg-4 col-sm-4  col-xs-12 control-label" style="float: left;">TEMPORALIDAD:</label>
				<p  id="temporalidadOfert" class="col-lg-8 cols-sm-8 col-xs-12 control-label rounded" style="float: right;"></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12">
				<label for="dedicacionOfert" class="col-lg-4 col-sm-4  col-xs-12 control-label" style="float: left;">DEDICACION:</label>
				<p  id="dedicacionOfert" class="col-lg-8 cols-sm-8 col-xs-12 control-label rounded" style="float: right;"></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12">
				<label for="requisitosOfert" class="col-lg-4 col-sm-4  col-xs-12 control-label" style="float: left;">REQUISITOS:</label>
				<p  id="requisitosOfert" class="col-lg-8 cols-sm-8 col-xs-12 control-label rounded" style="float: right;"></p>
			</div>
			<div class="form-group col-lg-12 col-sm-12">
				<label for="experienciaOfert" class="col-lg-4 col-sm-4  col-xs-12 control-label" style="float: left;">EXPERIENCIA:</label>
				<p  id="experienciaOfert" class="col-lg-8 cols-sm-8 col-xs-12 control-label rounded" style="float: right;"></p>
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
		 
		$('.btn-ver').each(function(){
			$(this).click(function(){
				$("#nombreOfert").text(''+$(this).attr('nombreOferta'));
				$("#detalleOfert").text(''+$(this).attr('detalleOferta'));

				if($(this).attr('temporalidadOferta') ==='0'){
					$("#temporalidadOfert").text(''+"TIEMPO COMPLETO");
				}else if($(this).attr('temporalidadOferta') ==='1'){
					$("#temporalidadOfert").text(''+"MEDIO TIEMPO");
				}else if($(this).attr('temporalidadOferta') ==='2'){
					$("#temporalidadOfert").text(''+"POR HORAS");
				}
				
				$("#requisitosOfert").text(''+$(this).attr('requisitosOferta'));
				if($(this).attr('dedicacionOferta')==='0'){
					$("#dedicacionOfert").text(''+"COMPLETO");
				}else if($(this).attr('dedicacionOferta')=='1'){
					$("#dedicacionOfert").text(''+"PARCIAL");
				}
				$("#experienciaOfert").text(''+$(this).attr('expererienciaOferta'));

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
				tempCargando= "<tr><td colspan='6'>"+imgCargando()+"</td></tr>";                
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
