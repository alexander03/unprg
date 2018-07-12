<style>
	.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
</style>

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
		<span class="btn btn-default btn-file btn-xs"><span>Seleccionar Archivo</span><input type="file" id="archivo" name="archivo"  /></span>
		<span class="fileinput-filename"></span><span class="fileinput-new" id="txtFile">Archivo no Seleccionado</span>
	</div>
	{!! Form::hidden('url_archivo', null, array('id' => 'url_archivo')) !!}
	{!! Form::hidden('id', null, array('id' => 'id')) !!}
	<!--div class="col-xs-12">
		<label style="margin-left: 40px;">Archivo: </label>
		<div class="input-group">
			<label class="input-group-btn">
				<span class="btn btn-primary btn-xs" style="left: 30px;">
					Seleccionar...<input type="file" id="archivo" name="archivo" style="display: none;">
				</span>
			</label>
			<input type="text" class="form-control input-xs" style="bottom: 7px;left: 30px;width: 360px;" readonly="" disabled>
		</div>
		<span class="help-block"  style="left: 30px;margin-left: 110px;">
			<small>Seleccione Archivo < 1500 KB</small>
		</span>
	</div-->
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
			$(this).button('loading');
			var form = $('#formMantenimientocertificado')[0];
			// Create an FormData object
			var DATA = new FormData(form);
			//DATA.append("id",$('#id').val());
			procesarAjax(DATA);
		}else{
			$.Notification.autoHideNotify('warning', 'top right', 'Campos vacios','Por favor Llenar todos los campos');
		}
	});

	if($("#url_archivo").val()!==""){
		var cadena = $("#url_archivo").val();
		var cadenasim = "";
		for(var i=0; i< cadena.length; i++){
			if(cadena.substring(i, i+1)==="-"){
				cadenasim = cadena.substring(i+1, cadena.length);
				break;
			}
		}
		$("#txtFile").html(cadenasim);
	}

	$("#archivo").change(function () {
		var val = $(this).val();
		switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
			case 'gif': case 'jpg': case 'png':
				//alert("an image");
				readViewNameFile(this, 'txtFile');
				break;
			default:
				$(this).val(null);
				// error message here
				$.Notification.autoHideNotify('warning', 'top right', 'Error de Selección','Seleccione solo imagenes');
				break;
		}
		
    });


}); 

function readViewNameFile(input, idtxtname) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            //PARA QUE SE MUESTRE EN UNA ETIQUETA IMG
            //$('#visorImagenResultado').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        $('#' + idtxtname).html(input.files[0].name);
    }
}

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
			//console.log(JSONRESPONSE);
			$('#btnGuardar').button('reset');
			if(JSONRESPONSE.toLowerCase() === "ok"){
				if($('#operacion').val()==="update"){
					buscarCompaginado(1, 'Datos actualizados exitosamente', 'certificado', 'OK');
				}else if($('#operacion').val()==="store"){
					buscarCompaginado(1, 'Datos registrados exitosamente', 'certificado', 'OK');
				}

			}else{
				$.Notification.autoHideNotify('warning', 'top right', 'No se completo el registro','Ocurrió un error interno!');
			}
			cerrarModal();
        },
        error: function () {
            mostrarMensaje('Error Interno!','ERROR');
        }
    });
    return false;
}
</script>