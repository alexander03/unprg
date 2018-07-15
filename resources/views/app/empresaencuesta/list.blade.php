@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td>{{ $contador }}</td>
			<td>{{ $value->encuesta->nombre }}</td>
			<?php 
				$alumno = $value->alumno->nombres . ' ' . $value->alumno->apellidopaterno . ' ' . $value->alumno->apellidomaterno;
			?>
			<td>{{ $alumno }}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-eye-open"></div> Ver', array('class' => 'btn btn-xs btn-success', 'onclick' => 'modal (\'' . URL::route($ruta["respuestasencuesta"], array('encuesta_id'=>$value->encuesta->id)) . '\', \'Respuestas de encuesta: ' . $value->encuesta->nombre . '<br>Alumno: ' . $alumno . '\', this);')) !!}</td>		
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
{!! $paginacion or '' !!}
@endif
