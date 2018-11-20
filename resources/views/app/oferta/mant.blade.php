<?php 

use App\Facultad;
use App\Escuela;
use App\Especialidad;

?>

<script>

	$('#facultad_id').change(function(event){
		$.get("escuelas/"+event.target.value+"",function(response, facultad){
			$('#escuela_id').empty();
			$("#escuela_id").append("<option value=''>Seleccione</option>");
			for(i=0; i<response.length; i++){
				$("#escuela_id").append("<option value='"+response[i].id+"'> "+response[i].nombre+"</option>");
			}
		});
	});

	$('#escuela_id').change(function(event){
		$.get("especialidades/"+event.target.value+"",function(response, escuela){
			$('#especialidad_id').empty();
			$("#especialidad_id").append("<option value=''>Seleccione</option>");
			for(i=0; i<response.length; i++){
				$("#especialidad_id").append("<option value='"+response[i].id+"'> "+response[i].nombre+"</option>");
			}
		});
	});

	$('.opOferte').change(function(event){
		if(event.target.value == 0){
			$('.visualisar').attr('disabled','disabled');
			$('#tablaDirecciones').empty();
		}else{
			$('.visualisar').removeAttr('disabled');
		}
	});

	$('#btnAgregar').click(function(){
		process();
	});
	function process(){
		if($('#facultad_id').val()!==''||$('#escuela_id').val()!==''||$('#especialidad_id').val()!==''){
			$('#tablaDirecciones').append("<tr><td>"+$("#tablaDir tr").length+"</td><td idFacultad='"+$('#facultad_id').val()+
			"' idEscuela='"+$('#escuela_id').val()+
			"' idEspecialidad='"+$('#especialidad_id').val()+
			"' class='direciones'>"+(($('#facultad_id').val()==='')?"":"->"+$("#facultad_id option:selected").text()) +""+
			(($('#escuela_id').val()==='')?"":"->"+$("#escuela_id option:selected").text())+""+
			(($('#especialidad_id').val()==='')?"":"->"+$("#especialidad_id option:selected").text())+"</td>"+
			"<td><button class='btn btn-danger btn-xs borrar'><div class='glyphicon glyphicon-remove'></div>Eliminar</button></td></tr>");
			$('#facultad_id').val('');
			$('#escuela_id').empty();
			$('#especialidad_id').empty();
			$('#escuela_id').append("<option value=''>Seleccione</option>");
			$('#especialidad_id').append("<option value=''>Seleccione</option>");
		}
		$('#cadenaDirecciones').val(getCadenaTablaDetalles);
	}

	function getCadenaTablaDetalles() {
		var cadenaDir = "";
		
		var botones = document.getElementsByClassName("direciones");
		if(botones.length !== 0){
			for (var i = 0; i < botones.length; i++) {
				if (i === botones.length - 1) {
					cadenaDir += ($(botones[i]).attr("idFacultad")===''?"-1":$(botones[i]).attr("idFacultad"))+":"+($(botones[i]).attr("idEscuela")===''?"-1":$(botones[i]).attr("idEscuela"))+
					":"+($(botones[i]).attr("idEspecialidad")===''?"-1":$(botones[i]).attr("idEspecialidad"));
				} else {
					cadenaDir += ($(botones[i]).attr("idFacultad")===''?"-1":$(botones[i]).attr("idFacultad"))+":"+($(botones[i]).attr("idEscuela")===''?"-1":$(botones[i]).attr("idEscuela"))+
					":"+($(botones[i]).attr("idEspecialidad")===''?"-1":$(botones[i]).attr("idEspecialidad"))+",";
				}
			}
		}else{
			cadenaDir = "";
		}
		return cadenaDir;
	}

	$(document).on('click', '.borrar', function (event) {
		event.preventDefault();
		$(this).closest('tr').remove();
		$('#cadenaDirecciones').val(getCadenaTablaDetalles);
	});

</script>

<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($oferta, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	
				<div class="form-group">
					{!! Form::label('nombre', 'Nombre:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12" >
						{!! Form::text('nombre', null, array('class' => 'form-control input-sm', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}					
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('detalle', 'Descripción:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						{!! Form::text('detalle', null, array('class' => 'form-control input-sm', 'id' => 'detalle', 'placeholder' => 'Ingrese descripción')) !!}
					</div>
				</div>
				<div class="form-group col-xs-6">
					{!! Form::label('fechaInicio', 'Fecha Inicio:', array('class' => '')) !!}
					{!! Form::date('fechaInicio', null, array('class' => 'form-control ', 'id' => 'fechaInicio', 'placeholder' => 'fecha inicio')) !!}
				</div>
				<?php
				if($oferta != null){
					echo "<input type='hidden' id='fechaI' value='".Date::parse($oferta->fechai )->format('d/m/Y')."'>";
				}else{
				echo "<input type='hidden' id='fechaI' value=''>";
					
				}
				?>
				<div class="form-group col-xs-6" style="margin-left: 28px;">
					{!! Form::label('fechaFin', 'Fecha Fin:', array('class' => '')) !!}
					{!! Form::date('fechaFin', null, array('class' => 'form-control ', 'id' => 'fechaFin', 'placeholder' => 'fecha fin')) !!}
				</div>
				<?php
				if($oferta != null){
					echo "<input type='hidden' id='fechaF' value='".Date::parse($oferta->fechaf )->format('d/m/Y')."'>";

				}else{
				echo "<input type='hidden' id='fechaF' value=''>";
					
				}
				?>

				<div class="form-group col-xs-6" >
					{!! Form::label('temporalidad', 'Temporalidad:', array('class' => '')) !!}
					{!! Form::select('temporalidad', $cboTemporalidad, null, array('class' => 'form-control ', 'id' => 'temporalidad')) !!}
				</div>
				<div class="form-group col-xs-6" style="margin-left: 28px;">
					{!! Form::label('dedicacion', 'Dedicacion:', array('class' => '')) !!}
					{!! Form::select('dedicacion', $cboDedicacion, null, array('class' => 'form-control', 'id' => 'dedicacion')) !!}
				</div>

				<div class="form-group">
					{!! Form::label('requisitos', 'Requisitos:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						{!! Form::text('requisitos', null, array('class' => 'form-control input-sm', 'id' => 'requisitos', 'placeholder' => 'Ingrese requisitos necesarios...')) !!}
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('experiencia', 'Experiencia:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						{!! Form::text('experiencia', null, array('class' => 'form-control input-sm', 'id' => 'experiencia', 'placeholder' => 'Ingrese Experiencia...')) !!}
					</div>
				</div>

		<div class="form-group">
			{!! Form::label('opcionevento', 'Opcion:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label')) !!}
			<div class="col-lg-12 col-md-12 col-sm-12" >
				{!! Form::select('opcionevento', $cboOpcionEvento, null, array('class' => 'form-control input-sm opOferte', 'id' => 'opcionevento')) !!}
			</div>
		</div>

		<div class="form-group col-xs-4" >
			{!! Form::label('facultad_id', 'Facultad:', array('class' => ' visualisar')) !!}
			{!! Form::select('facultad_id', $cboFacultad, null, array('class' => 'form-control  visualisar', 'id' => 'facultad_id')) !!}
		</div>
		<div class="form-group col-xs-4" style="margin-left: 15px;">
			{!! Form::label('escuela_id', 'Escuela:', array('class' => 'visualisar')) !!}
			<div id="selectescuela">
				{!! Form::select('escuela_id', $cboEscuela, null, array('class' => 'form-control  visualisar', 'id' => 'escuela_id')) !!}
			</div>
		</div>
		<div class="form-group col-xs-4" style="margin-left: 15px;">
			{!! Form::label('especialidad_id', 'Especialidad:', array('class' => 'visualisar')) !!}
			<div id="selectespecialidad">
				{!! Form::select('especialidad_id', $cboEspecialidad, null, array('class' => 'form-control  visualisar', 'id' => 'especialidad_id')) !!}
			</div>
		</div>

			<div class="form-group ">
				<div class="col-lg-12 col-md-12 col-sm-12 text-right">
					{!! Form::button('<i class="glyphicon glyphicon-check"></i> ¡Correcto!', array('class' => 'correcto btn btn-success waves-effect waves-light m-l-10 btn-md hidden input-sm', 'onclick' => '#')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> ¡Incorrecto!', array('class' => 'incorrecto btn btn-danger waves-effect waves-light m-l-10 btn-md hidden input-sm', 'onclick' => '#')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-plus visualisar"></i>', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md btnAnadir input-sm visualisar','id' => 'btnAgregar')) !!}
				</div>
			</div>
			<table id="tablaDir" class="table table-bordered table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th width='7%'>#</th>
						<th>Ruta</th>
						<th width='20%'>Operacion</th>
					</tr>
				</thead>

				<tbody id="tablaDirecciones">
				@if ($boton == "Modificar")
					<?php
					$cont = 1;
					?>
					@foreach ($listaDet as $key => $value)
					
					<tr>
						<td >{{$cont}}</td>
						<td idfacultad='{{ $value->id_facultad }}' idescuela='{{ $value->id_escuela }}' idespecialidad='{{ $value->id_especialidad }}' class='direciones'>{{ $value->nombre_facultad}} {{$value->nombre_escuela}} {{$value->nombre_especialidad }}</td>
						<td><button class='btn btn-danger btn-xs borrar'><div class='glyphicon glyphicon-remove'></div>Eliminar</button></td>
					</tr>
					<?php
					$cont ++;
					?>
					@endforeach
				@endif
			</tbody>
				<input type="hidden" id="cadenaDirecciones" name="cadenaDirecciones" value="">
			</table>


<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('650');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');		
}); 
$('#cadenaDirecciones').val(getCadenaTablaDetalles);
if($('#fechaI').val() !== ""){
		// DD/MM/YYYY
		var valoresFecha = $('#fechaI').val().split('/');
		//yyy/MM/DD
		var fecha = valoresFecha[2] + "-" + valoresFecha[1] + "-" + valoresFecha[0];
		$('#fechaInicio').val(fecha);
}
if($('#fechaF').val() !== ""){
		// DD/MM/YYYY
		var valoresFecha = $('#fechaF').val().split('/');
		//yyy/MM/DD
		var fecha = valoresFecha[2] + "-" + valoresFecha[1] + "-" + valoresFecha[0];
		$('#fechaFin').val(fecha);
}
if($('#opcionevento').val() == 0){
	$('.visualisar').attr('disabled','disabled');
}else{
	$('.visualisar').removeAttr('disabled');
}
</script>
