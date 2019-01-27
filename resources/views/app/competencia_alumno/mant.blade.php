<style>
	#form {
  width: 250px;
  margin: 0 auto;
  height: 50px;
}

#form p {
  text-align: center;
}

#form label {
  font-size: 20px;
}

input[type="radio"] {
  display: none;
}

label {
  color: grey;
}

.clasificacion {
  direction: rtl;
  unicode-bidi: bidi-override;
}

label:hover,
label:hover ~ label {
  color: orange;
}

input[type="radio"]:checked ~ label {
  color: orange;
}
</style>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($competencia_alumno, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		<label class="control-label col-xs-3">Competencia: </label>
		<div class="col-xs-9">
			<select class="form-control input-xs" id="competencia_id" name="competencia_id">
					<option value=''>Seleccione...</option>
				<?php
					foreach ($cboCompetencia as $value) {
						echo '<option value='.$value->id.'>'.$value->nombre.'</option>';
					}
					//VALIDAMOS PARA AGREGAR EL EDITAR
					if($competencia_alumno != null){
						echo '<option value='.$competencia_alumno->competencia->id.' selected>'.$competencia_alumno->competencia->nombre.'</option>';
					}
				?>
			</select>
		</div>
	</div>
	<div class="form-group" style="display: none">
		<label class="control-label col-xs-3">Calificación: </label>
		<div class="col-xs-9">
			<p class='clasificacion text-left'>
				<input id="radio1" type="radio" class="iestrella" name="estrellas" value="5"><label for="radio1" class="lblestrella">&#9733;</label>
				<input id="radio2" type="radio" class="iestrella" name="estrellas" value="4"><label for="radio2" class="lblestrella">&#9733;</label>
				<input id="radio3" type="radio" class="iestrella" name="estrellas" value="3"><label for="radio3" class="lblestrella">&#9733;</label>
				<input id="radio4" type="radio" class="iestrella" name="estrellas" value="2"><label for="radio4" class="lblestrella">&#9733;</label>
				<input id="radio5" type="radio" class="iestrella" name="estrellas" value="1"><label for="radio5" class="lblestrella">&#9733;</label>
			</p>
			<!--input type="hidden" value="" name="calificacion" id="calificacion"-->
			{!! Form::hidden('calificacion', null, array('id' => 'calificacion')) !!}
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
	var idclick;
	$('.lblestrella').each(function(index, value){
		$(this).click(function(){
			idclick = $(this).attr('for');
			$("#" + idclick).prop('checked', true);
			$('#calificacion').val($('#'+idclick).val());
		});
	});

	if($('#calificacion').val()!==""){
		$('.iestrella').each(function(index, value){
			//console.log('COMPARANDO ' + $(this).val() + " - " +$('#calificacion').val());
			if($(this).val() === $('#calificacion').val()){
				$(this).prop('checked', true);
				return false;
			}
		});
	}

}); 
// ★
</script>
