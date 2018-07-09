<?php 

use App\Facultad;
use App\Escuela;
use App\Especialidad;

?>

<script>
$('#facultad_id').change(function(event){
	$.get("escuelas/"+event.target.value+"",function(response, facultad){
		console.log(response);
		$('#escuela_id').empty();
		$("#escuela_id").append("<option value=''>Seleccione</option>");
		for(i=0; i<response.length; i++){
			$("#escuela_id").append("<option value='"+response[i].id+"'> "+response[i].nombre+"</option>");
		}
	});
});
$('#escuela_id').change(function(event){
	$.get("especialidades/"+event.target.value+"",function(response, escuela){
		console.log(response);
		$('#especialidad_id').empty();
		$("#especialidad_id").append("<option value=''>Seleccione</option>");
		for(i=0; i<response.length; i++){
			$("#especialidad_id").append("<option value='"+response[i].id+"'> "+response[i].nombre+"</option>");
		}
	});
});

var contador=0;
$('#btnAgregar').click(function(){
	if($('#facultad_id').val()!==''||$('#escuela_id').val()!==''||$('#especialidad_id').val()!==''){
		$('#tablaDirecciones').append("<tr><td>"+contador+"</td><td idFacultad='"+$('#facultad_id').val()+
		"' idEscuela='"+$('#escuela_id').val()+
		"' idEspecialidad='"+$('#especialidad_id').val()+
		"'>"+(($('#facultad_id').val()==='')?"":$("#facultad_id option:selected").text()) +""+
		(($('#escuela_id').val()==='')?"":" ->"+$("#escuela_id option:selected").text())+""+
		(($('#especialidad_id').val()==='')?"":" ->"+$("#especialidad_id option:selected").text())+"</td></tr>");
		$('#facultad_id').val('');
		$('#escuela_id').val('');
		$('#especialidad_id').val('');
		contador ++;
	}
	
});


/*
	function gestionpa(evento_id, id, num){
		if(num == 1){
			if($('#facultad_id').val() == '') {
				return false;
			}
			route = 'evento/nuevadireccion/' + evento_id;
		} else if(num == 2){
			route = 'evento/eliminardireccion/' + id + '/' + evento_id;
		} 

		$.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
			data: $('#formnuevadireccion').serialize(),
			beforeSend: function(){
				$('#tabladirecciones').html(imgCargando());
				$('.correcto').addClass('hidden');
				$('.incorrecto').addClass('hidden');
	        },
	        success: function(res){
	        	$('#tabladirecciones').html(res);
				$('.correcto').removeClass('hidden');
				$('.incorrecto').addClass('hidden');
				$('#facultad_id').val('');
				$('#evento_id').val('');
				$('#especialidad_id').val('');
	        }
		}).fail(function(){
			$('.incorrecto').removeClass('hidden');
			$('.correcto').addClass('hidden');
		});
	}

	function cargarselect(entidad){
		padre = 'evento';
		if(entidad == 'escuela'){
			padre = 'facultad';
		}
		var select = $('#' + padre + '_id').val();
		route = 'eventi/cargarselect/' + select + '?entidad=' + entidad + '&t=no';

		$.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
	        success: function(res){
	        	$('#select' + entidad).html(res);
	        	if(padre == 'facultad'){
					$('#selectespecialidad').html('<select class="form-control input-sm" id="especialidad_id" name="especialidad_id"><option value="" selected="selected">Seleccione</option></select>');
	        	}
	        }
		});
	}*/
</script>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($evento, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<fieldset class="col-12">
	<legend>Datos evento</legend>
	<div class="panel panel-default" style="margin-bottom: 10px;">
		<div class="panel-body">
			<div class="form-group">
				{!! Form::label('nombre', 'Nombre:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
				<div class="col-lg-9 col-md-9 col-sm-9">
					{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}
				</div>
			</div>
		</div>
	</div>
</fieldset>
	<legend>Datos dirección</legend>
	<div class="panel panel-default" style="margin-bottom: 10px;">
		<div class="panel-body">
			<div class="form-group">
				{!! Form::label('facultad_id', 'Facultad:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
				<div class="col-lg-10 col-md-10 col-sm-10">
					{!! Form::select('facultad_id', $cboFacultad, null, array('class' => 'form-control input-sm', 'id' => 'facultad_id')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('escuela_id', 'Escuela:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
				<div class="col-lg-10 col-md-10 col-sm-10">
					<div id="selectescuela">
						{!! Form::select('escuela_id', $cboEscuela, null, array('class' => 'form-control input-sm', 'id' => 'escuela_id')) !!}
					</div>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('especialidad_id', 'Especialidad:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
				<div class="col-lg-10 col-md-10 col-sm-10">
					<div id="selectespecialidad">
						{!! Form::select('especialidad_id', $cboEspecialidad, null, array('class' => 'form-control input-sm', 'id' => 'especialidad_id')) !!}
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12 col-md-12 col-sm-12 text-right">
					{!! Form::button('<i class="glyphicon glyphicon-check"></i> ¡Correcto!', array('class' => 'correcto btn btn-success waves-effect waves-light m-l-10 btn-md hidden input-sm', 'onclick' => '#')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> ¡Incorrecto!', array('class' => 'incorrecto btn btn-danger waves-effect waves-light m-l-10 btn-md hidden input-sm', 'onclick' => '#')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md btnAnadir input-sm','id' => 'btnAgregar')) !!}
				</div>
			</div>
			<!-- //************************************************* -->
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Ruta</th>
		</tr>
	</thead>
	<tbody id="tablaDirecciones">
		
		
	</tbody>
</table>
<!-- //************************************************* -->

		</div>
	</div>
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
	configurarAnchoModal('750');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>