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
			<td>{!! Form::button('<div class="glyphicon glyphicon-list"></div> Preguntas', array('onclick' => 'modal(\''.URL::route($ruta["listarpreguntas"], $value->id).'\', \'Preguntas para: '.$value->nombre.'\', this);', 'class' => 'btn btn-default btn-xs')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-list"></div> Direcciones', array('onclick' => 'modal(\''.URL::route($ruta["listardirecciones"], $value->id).'\', \'Direcciones para: '.$value->nombre.'\', this);', 'class' => 'btn btn-default btn-xs')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-xs btn-warning')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
{!! $paginacion or '' !!}
@endif
