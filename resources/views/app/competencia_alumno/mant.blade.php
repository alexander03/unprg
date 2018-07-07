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
<p class="clasificacion">
		<input id="radio1" type="radio" name="estrellas" value="5"><label for="radio1">&#9733;</label>
		<input id="radio2" type="radio" name="estrellas" value="4"><!--
		--><label for="radio2">&#9733;</label><!--
		--><input id="radio3" type="radio" name="estrellas" value="3"><!--
		--><label for="radio3">&#9733;</label><!--
		--><input id="radio4" type="radio" name="estrellas" value="2"><!--
		--><label for="radio4">&#9733;</label><!--
		--><input id="radio5" type="radio" name="estrellas" value="1"><!--
		--><label for="radio5">&#9733;</label>
</p>
{!! Form::model($competencia_alumno, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		{!! Form::label('competencia_id', 'Facultad:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::select('competencia_id', $cboCompetencia, null, array('class' => 'form-control input-xs', 'id' => 'facultad_id')) !!}
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
// ★
</script>