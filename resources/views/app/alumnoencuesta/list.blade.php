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
			<td>{!! Form::button('<div class="glyphicon glyphicon-list"></div> Preguntas', array('onclick' => 'cargarRutaMenu(\'http://localhost/unprg/alumnoencuesta/llenarencuesta?encuesta_id=' . $value->id . '\', \'container\', \'\');', 'class' => 'btn btn-default btn-xs')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Estado', array('onclick' => '#', 'class' => 'btn btn-xs btn-warning')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
{!! $paginacion or '' !!}
@endif
