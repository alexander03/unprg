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
		<tr  >
			<td>{{ $contador }}</td>
			<td>{{ $value->dni }}</td>
			<td>{{ $value->codigo }}</td>
			<td>{{ $value->nombres.' '.$value->apellidopaterno.' '.$value->apellidomaterno  }}</td>		
			<td>{{ $value->escuela->nombre or  '-'  }}</td>
			<td>{{ $value->especialidad->nombre or '-' }}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-list"></div> Exp. Laborales', array('onclick' => 'modal (\''.URL::route($ruta["experienciaslaborales"], array('SI',$value->id)).'\', \'<h5>ALUMNO [ '.$value->nombres.' '.$value->apellidopaterno.' '.$value->apellidomaterno.' ]<h5>\', this);', 'class' => 'btn btn-xs btn-default')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</tfoot>
</table>
{!! $paginacion or '' !!}
@endif