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
			// $("#tablaDir tr").length;
		}
		$('#cadenaDirecciones').val(getCadenaTablaDetalles);
	});

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
{!! Form::model($evento, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<fieldset class="col-12">
		<legend>Datos Evento</legend>
		<div class="form-group col-xs-8">
			{!! Form::label('nombre', 'Nombre:', array('class' => '')) !!}
			{!! Form::text('nombre', null, array('class' => 'form-control input-sm', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}
		</div>

		<div class="form-group col-xs-4 " style="margin-left: 10px;">
			{!! Form::label('tipoevento_id', 'Tipo evento:', array('class' => '')) !!}
			{!! Form::select('tipoevento_id', $cboTipoevento, null, array('class' => 'form-control input-sm opOferte', 'id' => 'tipoevento_id')) !!}
		</div>
		<div class="form-group col-xs-12">
			{!! Form::label('detalle', 'Descripción:', array('class' => '')) !!}
			{!! Form::textarea('detalle', null, array('class' => 'form-control input-sm','rows' => 4, 'id' => 'detalle', 'placeholder' => 'Ingrese descripción')) !!}
		</div>
	
		<div class="form-group col-xs-6">
			{!! Form::label('ponente', 'Ponente:', array('class' => '')) !!}
			{!! Form::text('ponente', null, array('class' => 'form-control input-sm', 'id' => 'ponente', 'placeholder' => 'Nombre del ponente')) !!}
		</div>
		<div class="form-group col-xs-6" style="margin-left: 10px;">
			{!! Form::label('direccion', 'Direccion:', array('class' => '')) !!}
			{!! Form::text('direccion', null, array('class' => 'form-control input-sm', 'id' => 'direccion', 'placeholder' => 'Ingrese una dirección')) !!}
		</div>

		<div class="form-group col-xs-6">
			{!! Form::label('hora', 'Hora:', array('class' => '')) !!}
			{!! Form::text('hora', null, array('class' => 'form-control input-sm', 'id' => 'hora', 'placeholder' => 'Ingrese Hora del evento')) !!}
		</div>
		<div class="form-group col-xs-6 " style="margin-left: 10px;">
			{!! Form::label('acceso', 'Acceso:', array('class' => '')) !!}
			{!! Form::select('acceso', $cboAcceso, null, array('class' => 'form-control input-sm', 'id' => 'acceso')) !!}
		</div>

		<div class="form-group col-xs-6">
			{!! Form::label('fechaInicio', 'Fecha Inicio:', array('class' => '')) !!}
			{!! Form::date('fechaInicio', null, array('class' => 'form-control input-xs', 'id' => 'fechaInicio', 'placeholder' => 'fecha inicio')) !!}
		</div>

		<?php
			if($evento != null){
				echo "<input type='hidden' id='fechaI' value='".Date::parse($evento->fechai )->format('d/m/Y')."'>";
			}else{
			echo "<input type='hidden' id='fechaI' value=''>";
			}
		?>
		<div class="form-group col-xs-6" style="margin-left: 10px;">
			{!! Form::label('fechaFin', 'Fecha Fin:', array('class' => '')) !!}
			{!! Form::date('fechaFin', null, array('class' => 'form-control input-xs', 'id' => 'fechaFin', 'placeholder' => 'fecha fin')) !!}
		</div>
		<?php
			if($evento != null){
				echo "<input type='hidden' id='fechaF' value='".Date::parse($evento->fechaf )->format('d/m/Y')."'>";
			}else{
			echo "<input type='hidden' id='fechaF' value=''>";
			}
		?>
	</fieldset>
	<fieldset class="col-12">
	<legend>Datos dirección</legend>
		<div class="form-group col-xs-12">
			{!! Form::label('opcionevento', 'Opcion de evento:', array('class' => '')) !!}
			{!! Form::select('opcionevento', $cboOpcionEvento, null, array('class' => 'form-control opOferte', 'id' => 'opcionevento')) !!}
		</div>

		<div class="form-group col-xs-6">
			{!! Form::label('facultad_id', 'Facultad:', array('class' => ' visualisar')) !!}
			{!! Form::select('facultad_id', $cboFacultad, null, array('class' => 'form-control input-sm visualisar', 'id' => 'facultad_id')) !!}
		</div>

		<div class="form-group col-xs-6" style="margin-left: 10px;">
			{!! Form::label('escuela_id', 'Escuela:', array('class' => 'visualisar')) !!}
			<div id="selectescuela">
				{!! Form::select('escuela_id', $cboEscuela, null, array('class' => 'form-control input-sm visualisar', 'id' => 'escuela_id')) !!}
			</div>
		</div>

		<div style="display:none;">
			{!! Form::label('especialidad_id', 'Especialidad:') !!}
			<div id="selectespecialidad">
				{!! Form::select('especialidad_id', $cboEspecialidad, null, array('id' => 'especialidad_id')) !!}
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
	</fieldset>
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
