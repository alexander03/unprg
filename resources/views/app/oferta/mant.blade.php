<?php 

use App\Facultad;
use App\Escuela;

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
		if($('#facultad_id').val()!==''||$('#escuela_id').val()!==''){
			$('#tablaDirecciones').append("<tr><td>"+$("#tablaDir tr").length+"</td><td idFacultad='"+$('#facultad_id').val()+
			"' idEscuela='"+$('#escuela_id').val()+
			"' class='direciones'>"+(($('#facultad_id').val()==='')?"":"->"+$("#facultad_id option:selected").text()) +""+
			(($('#escuela_id').val()==='')?"":"->"+$("#escuela_id option:selected").text())+""+
			"</td><td><button class='btn btn-danger btn-xs borrar'><div class='glyphicon glyphicon-remove'></div>Eliminar</button></td></tr>");
			$('#facultad_id').val('');
			$('#escuela_id').empty();
			$('#escuela_id').append("<option value=''>Seleccione</option>");
		}
		$('#cadenaDirecciones').val(getCadenaTablaDetalles);
	}

	function getCadenaTablaDetalles() {
		var cadenaDir = "";
		
		var botones = document.getElementsByClassName("direciones");
		if(botones.length !== 0){
			for (var i = 0; i < botones.length; i++) {
				if (i === botones.length - 1) {
					cadenaDir += ($(botones[i]).attr("idFacultad")===''?"-1":$(botones[i]).attr("idFacultad"))+":"+($(botones[i]).attr("idEscuela")===''?"-1":$(botones[i]).attr("idEscuela"));
				} else {
					cadenaDir += ($(botones[i]).attr("idFacultad")===''?"-1":$(botones[i]).attr("idFacultad"))+":"+($(botones[i]).attr("idEscuela")===''?"-1":$(botones[i]).attr("idEscuela"));
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
		{!! Form::label('nombre', 'Nombre: *', array('class' => 'control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12" >
			{!! Form::text('nombre', null, array('class' => 'form-control input-sm', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}					
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('detalle', 'Descripción: ', array('class' => 'control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::textarea('detalle', null, array('class' => 'form-control input-sm', 'id' => 'detalle', 'placeholder' => 'Ingrese descripción', 'rows' => '3')) !!}
		</div>
	</div>
	<h4>Vigencia Oferta:</h4>
	<div class="form-group col-xs-6">
		{!! Form::label('fechaInicio', 'Fecha Inicio: *', array('class' => '')) !!}
		{!! Form::date('fechaInicio', null, array('class' => 'form-control ', 'id' => 'fechaInicio', 'placeholder' => 'fecha inicio')) !!}
	</div>
	<?php
	if($oferta != null){
		?>
		<input type='hidden' id='fechaI' value='{{ Date::parse($oferta->fechai )->format('d/m/Y') }}'>
		<?php
	}else{
		echo "<input type='hidden' id='fechaI' value=''>";
	}
	?>
	<div class="form-group col-xs-6" style="margin-left: 8px;">
		{!! Form::label('fechaFin', 'Fecha Fin: *', array('class' => '')) !!}
		{!! Form::date('fechaFin', null, array('class' => 'form-control ', 'id' => 'fechaFin', 'placeholder' => 'fecha fin')) !!}
	</div>
	<?php
	if($oferta != null){
		?>
		<input type='hidden' id='fechaF' value='{{ Date::parse($oferta->fechaf)->format('d/m/Y') }}'>
		<?php
	}else{
		echo "<input type='hidden' id='fechaF' value=''>";
	}
	?>

	<div class="form-group col-xs-12">
		{!! Form::label('dedicacion', 'Tipo de oferta: *', array('class' => '')) !!}
		{!! Form::select('dedicacion', $cboDedicacion, null, array('class' => 'form-control', 'id' => 'dedicacion')) !!}
	</div>
	<h4>Requisitos:</h4>
	<div class="row m-b-30">
		<div class="form-group col-xs-10" style="margin-left: 5px;">
			{!! Form::text('requisito', null, array('class' => 'form-control input-sm', 'id' => 'requisito', 'placeholder' => 'Ingrese requisito')) !!}
		</div>
		<div class="form-group col-xs-2" >
			{!! Form::button('<i class="glyphicon glyphicon-plus">Agregar</i>', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md input-sm','id' => 'btnAddReq')) !!}
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::textarea('requisitos', null, array('class' => 'form-control input-sm', 'id' => 'requisitos', 'placeholder' => 'Ingrese requisitos necesarios...', 'rows' => '4')) !!}
		</div>
	</div>
	

	<div class="form-group col-xs-6">
		{!! Form::label('experiencia', 'Experiencia: ', array('class' => '')) !!}
		{!! Form::text('experiencia', null, array('class' => 'form-control input-sm', 'id' => 'experiencia', 'placeholder' => 'Ingrese Experiencia...')) !!}
	</div>
	<div class="form-group col-xs-6" style="margin-left: 8px;">
		{!! Form::label('opcionevento', 'Definir interesados: *', array('class' => '')) !!}
		{!! Form::select('opcionevento', $cboOpcionEvento, null, array('class' => 'form-control input-sm opOferte', 'id' => 'opcionevento')) !!}
	</div>

	<div class="form-group col-xs-6" >
		{!! Form::label('facultad_id', 'Facultad: ', array('class' => ' visualisar')) !!}
		{!! Form::select('facultad_id', $cboFacultad, null, array('class' => 'form-control  visualisar', 'id' => 'facultad_id')) !!}
	</div>
	<div class="form-group col-xs-6" style="margin-left: 8px;">
		{!! Form::label('escuela_id', 'Escuela:', array('class' => 'visualisar')) !!}
		{!! Form::select('escuela_id', $cboEscuela, null, array('class' => 'form-control visualisar', 'id' => 'escuela_id')) !!}
	</div>
	<div class="form-group col-xs-12">
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
				<td idfacultad='{{ $value->id_facultad }}' idescuela='{{ $value->id_escuela }}' class='direciones'>{{ $value->nombre_facultad}} {{$value->nombre_escuela}}</td>
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
	<div class="form-group col-xs-12">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => '')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		configurarAnchoModal('650');
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');		
	}); 
	$('#btnGuardar').click(function(){
		if(validarcampos()){
			guardar('Oferta',this);
		}
	});
	function validarcampos(){
		var msje = true;
		var datos = [];
		var divErr = '#divMensajeErrorOferta';
		$(divErr).html('');

		datos[0] = ($('#nombre').val() == "")?'El campo nombre es obligatorio':'';
		datos[1] = ($('#fechaInicio').val() == '')? 'El campo fecha inicio es obligatorio':'';
		datos[2] = ($('#fechaFin').val() == '')? 'El campo fecha fin es obligatorio':'';
		var cadenaErr = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
		if(datos[0] != "" || datos[1] != "" || datos[2] != ""){
			msje = false;
			for (var i = 0; i < datos.length; i++) {
				if (datos[i] != '') {
					cadenaErr += ' <li>' + datos[i] + '</li>';
				}
				datos[i]="";
			}
			cadenaErr += '</ul></div>';
			$(divErr).html(cadenaErr);
		}
		return msje;
	}
	$('#btnAddReq').click(function(){
		var textar = $('#requisitos').val();
		var text =  (textar == '')?textar +' - '+ $('#requisito').val():textar + '\n' + ' - '+ $('#requisito').val();
		$('#requisitos').val(text);
		$('#requisito').val("");
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
