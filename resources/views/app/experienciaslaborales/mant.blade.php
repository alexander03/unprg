<div id="divMensajeError{!! $entidad !!}"></div>
<?php
$fechainiciop = null;
$fechafinp = null;
if ($experienciaslaborales != null) {
    $fechainiciop = Date::parse($experienciaslaborales->fechainicio)->format('Y-m-d');
    $fechafinp = Date::parse($experienciaslaborales->fechafin)->format('Y-m-d');
}
?>
{!! Form::model($experienciaslaborales, $formData) !!}
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<fieldset class="col-12">
		<legend>Empresa</legend>
		<div class="panel panel-default" style="margin-bottom: 10px;">
			<div class="panel-body">
				<div class="form-group col-xs-4">
					{!! Form::label('ruc', 'Ruc:', array('class' => '')) !!}
					{!! Form::text('ruc', null, array('class' => 'form-control input-xs', 'id' => 'ruc', 'placeholder' => 'Ingrese Ruc', 'maxlength' => '11')) !!}
				</div>
				<div class="form-group col-xs-8" style="margin-left: 10px;">
					{!! Form::label('empresa', 'Empresa:', array('class' => '')) !!}
					{!! Form::text('empresa', null, array('class' => 'form-control input-xs', 'id' => 'empresa', 'placeholder' => 'Ingrese Empresa', 'maxlength' => '120')) !!}
				</div>
				<div class="form-group col-xs-12">
					{!! Form::label('cargo', 'Cargo Desempeñaste:', array('class' => '')) !!}
					{!! Form::text('cargo', null, array('class' => 'form-control input-xs', 'id' => 'cargo', 'placeholder' => 'Ingrese Cargo', 'maxlength' => '100')) !!}
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset class="col-12" style="margin-top: 10px;">
		<legend>Datos Adicionales</legend>
		<div class="panel panel-default" style="margin-bottom: 10px;">
			<div class="panel-body">
				<div class="form-group col-xs-4">
					{!! Form::label('telefono', 'Telefono / Celular:', array('class' => '')) !!}
					{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese Telefono', 'maxlength' => '9')) !!}
				</div>	
				<div class="form-group col-xs-8" style="margin-left: 10px;">
					{!! Form::label('email', 'Email:', array('class' => '')) !!}
					{!! Form::email('email', null, array('class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'Ingrese Email', 'maxlength' => '100')) !!}
				</div>
				<div class="form-group col-xs-6">
					{!! Form::label('fechainicio', 'Fecha Inicio:', array('class' => '')) !!}
					{!! Form::date('fechainicio', $fechainiciop, array('class' => 'form-control input-xs', 'id' => 'fechainicio', 'placeholder' => 'Ingrese Fecha Inicio')) !!}
				</div>		
				<div class="form-group col-xs-6" style="margin-left: 10px;">
					{!! Form::label('fechafin', 'Fecha Fin:', array('class' => '')) !!}
					{!! Form::date('fechafin', $fechafinp, array('class' => 'form-control input-xs', 'id' => 'fechafin', 'placeholder' => 'Ingrese Fecha Fin')) !!}
				</div>			
			</div>
		</div>
	</fieldset>
	<div class="col-12">
		<br>
		<div class="form-group text-right">
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