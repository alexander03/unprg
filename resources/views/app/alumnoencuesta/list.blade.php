<?php 
	use App\AlumnoEncuesta;
?>
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
			<td>{{ $value->nombre }}</td>
			<td>{{ $value->objetivo }}</td>
			<td>{{ $value->tipoencuesta->nombre or '-' }}</td>

			<?php 
				$user                        = Auth::user();
        			$alumno_id                   = $user->alumno_id;
				$alumnoencuesta = AlumnoEncuesta::select('estado', 'alumno_id')->where('encuesta_id', '=', $value->id)->where('alumno_id','=',$alumno_id)->get();
			?>
			@if(count($alumnoencuesta) == 0)
			<td>{!! Form::button('<div class="glyphicon glyphicon-list"></div> Preguntas', array('onclick' => 'cargarRuta(\'alumnoencuesta/llenarencuesta?encuesta_id=' . $value->id . '\', \'container\');', 'class' => 'btn btn-default btn-xs')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Pendiente', array('onclick' => '#', 'class' => 'btn btn-xs btn-danger')) !!}</td>
			@else
			<td>{!! Form::button('<div class="glyphicon glyphicon-list"></div> Ver', array('class' => 'btn btn-default btn-xs', 'onclick' => 'modal (\'' . URL::route($ruta["respuestasencuesta"], array('encuesta_id'=>$value->id, 'alumno_id'=>$alumnoencuesta[0]->alumno_id)) . '\', \'Respuestas de encuesta ' . $value->nombre . '\', this);')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-ok"></div> Completo', array('onclick' => '#', 'class' => 'btn btn-xs btn-success')) !!}</td>
			@endif
			
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
{!! $paginacion or '' !!}
@endif
