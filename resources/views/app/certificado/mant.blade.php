<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($certificado, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}
		</div>
	</div>
	<div class="form-group">
			{!! Form::label('nombre_certificadora', 'Emp. Cert/Estudios:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
			<div class="col-lg-9 col-md-9 col-sm-9">
				{!! Form::textarea('nombre_certificadora', null, array('class' => 'form-control input-xs', 'id' => 'nombre_certificadora', 'placeholder' => 'Ingrese Emp. Certificadora/Estudios', 'rows' => '2')) !!}
			</div>
	</div>
	<div class="form-group">
			<input type="file" id="archivo" name="archivo">
			{!! Form::hidden('url_archivo', null, array('id' => 'url_archivo')) !!}
	</div>
	{!! Form::hidden('operacion', $operacion, array('id' => 'operacion')) !!}
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('550');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$('#btnGuardar').click(function(){
		if($('#nombre').val() !== "" && $('#nombre_certificadora').val() !== ""){
			var form = $('#formMantenimientocertificado')[0];
			// Create an FormData object
			var DATA = new FormData(form);
			procesarAjax(DATA);
		}else{
			$.Notification.autoHideNotify('warning', 'top right', 'Campos vacios','Por favor Llenar todos los campos');
		}
	});

}); 

function procesarAjax(DATA){
	var url = "certificado/"+$('#operacion').val();
	$.ajax({
		url: url,
		headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        type: 'POST',
        enctype: 'multipart/form-data',
        data: DATA,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        success: function (JSONRESPONSE) {
            if($('#operacion').val()==="update"){
				mostrarMensaje('Datos actualizados exitosamente!','OK');
				//$('#btnBuscar').trigger(click);
			}else if($('#operacion').val()==="create"){
				mostrarMensaje('Datos registrados exitosamente!','OK');
			}
        },
        error: function () {
            mostrarMensaje('Error Interno!','ERROR');
        }
    });
    return false;
}
</script>