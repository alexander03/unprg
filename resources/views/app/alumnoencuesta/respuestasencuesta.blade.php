<?php 
use App\Respuesta;
use App\Alternativa;
?>
<div class="form-group border">
	<?php $i = 1; ?>
	@foreach($preguntas as $pregunta)
		{!! '<b><u><span class=\'text-info\'>'.$i. '. ' .$pregunta->nombre.'<span></u></b><br>' !!}
		@if($pregunta->tipo == 1)
		<?php 
			$alternativas = Alternativa::select('id', 'nombre')->where('pregunta_id', '=', $pregunta->id)->get();
			foreach ($alternativas as $alternativa) {
				$respuesta = Respuesta::select('id')->where('alternativa_id', '=', $alternativa->id)->get();
				if(count($respuesta) > 0) {
					echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>RESPUESTA: </b> <b>' . $alternativa->nombre . '</b><br><br>';
				}
			}
		?>
		@else 
			<?php 
				$user                        = Auth::user();
        			$alumno_id                   = $user->alumno_id;
				$respuesta = Respuesta::select('libre')->where('pregunta_id', '=', $pregunta->id)->where('alumno_id', '=', $alumno_id)->get();
				if(count($respuesta)>0){
					echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>RESPUESTA: </b> <b>' . $respuesta[0]->libre . '</b><br><br>';
				}
			?>
		@endif
		<?php $i++; ?>
	@endforeach
</div>
<div class="form-group text-center">
	{!! Form::button('Cerrar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCerrar', 'onclick' => 'cerrarModal();')) !!}
</div>
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('400');
}); 
</script>
