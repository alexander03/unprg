<div id="divMensajeError{!! $entidad !!}"></div>
<?php
	$fechainiciop = null;
	$fechafinp = null;
	if($experienciaslaborales != null){
		$fechainiciop = Date::parse($experienciaslaborales->fechainicio )->format('Y-m-d');
		$fechafinp = Date::parse($experienciaslaborales->fechafin )->format('Y-m-d');
	}
?>
{!! Form::model($experienciaslaborales, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		{!! Form::label('ruc', 'Ruc:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('ruc', null, array('class' => 'form-control input-xs', 'id' => 'ruc', 'placeholder' => 'Ingrese Ruc', 'maxlength' => '11')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('empresa', 'Empresa:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('empresa', null, array('class' => 'form-control input-xs', 'id' => 'empresa', 'placeholder' => 'Ingrese Empresa', 'maxlength' => '120')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('cargo', 'Cargo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('cargo', null, array('class' => 'form-control input-xs', 'id' => 'cargo', 'placeholder' => 'Ingrese Cargo', 'maxlength' => '100')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('email', 'Email:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::email('email', null, array('class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'Ingrese Email', 'maxlength' => '100')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('telefono', 'Telefono:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese Telefono', 'maxlength' => '10')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('fechainicio', 'Fecha Inicio:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::date('fechainicio', $fechainiciop, array('class' => 'form-control input-xs', 'id' => 'fechainicio', 'placeholder' => 'Ingrese Fecha Inicio')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('fechafin', 'Fecha Fin:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::date('fechafin', $fechafinp, array('class' => 'form-control input-xs', 'id' => 'fechafin', 'placeholder' => 'Ingrese Fecha Fin')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('450');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');


});
</script>