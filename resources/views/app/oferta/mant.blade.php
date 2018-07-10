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

	var cadena="";
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
	});

</script>

<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($oferta, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<fieldset class="col-12">
		<legend>Datos Oferta</legend>
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
				{!! Form::label('opcionoferta', 'Opcion de Oferta:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
				<div class="col-lg-10 col-md-10 col-sm-10">
					{!! Form::select('opcionoferta', $cboOpcionOferta, null, array('class' => 'form-control input-sm', 'id' => 'opcionoferta')) !!}
				</div>
			</div>
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
						
						@foreach ($arrayDirec as $key => $value)
						<tr>
							<td><script> $("#tablaDir tr").length;</script></td>
							<td>{{ $value->facultad_id}} -> {{$value->escuela_id}} -> {{$value->especialidad_id }}</td>
							<td>eliminar</td>
						</tr>
						
						@endforeach
					@endif
				</tbody>
				
				<input type="hidden" id="cadenaDirecciones" name="cadenaDirecciones" value="">
			</table>
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
	configurarAnchoModal('650');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>