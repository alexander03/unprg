<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($empresa, $formData) !!}	
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group">
	{!! Form::label('ruc', 'RUC:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
	<div class="col-lg-9 col-md-9 col-sm-9">
		{!! Form::text('ruc', null, array('class' => 'form-control input-xs', 'id' => 'ruc', 'placeholder' => 'Ingrese RUC', 'maxlength' => '11')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('razonsocial', 'Razón Social:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
	<div class="col-lg-9 col-md-9 col-sm-9">
		{!! Form::text('razonsocial', null, array('class' => 'form-control input-xs', 'id' => 'razonsocial', 'placeholder' => 'Ingrese Razón Social')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('direccion', 'Dirección:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
	<div class="col-lg-9 col-md-9 col-sm-9">
		{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese Dirección')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('telefono', 'Teléfono:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
	<div class="col-lg-9 col-md-9 col-sm-9">
		{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese Teléfono', 'maxlength' => '9')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('email', 'Email:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
	<div class="col-lg-9 col-md-9 col-sm-9">
		{!! Form::email('email', null, array('class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'Ingrese Email')) !!}
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
		configurarAnchoModal('550');
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	}); 
</script>