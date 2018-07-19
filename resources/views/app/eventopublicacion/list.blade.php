@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron suscriptores.</h3>
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
			<td>{{ $value->detalle }}</td>
			<td>{{ $value->Tipoevento->nombre}}</td>
			<td>
			<?php
				$cant = DB::table('evento_alumno')->where('evento_id','=', $value->id)->count();
				echo $cant
			?>
			</td>
			<td><a href="{{ route('downloadPDF', $value->id)}}" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-download-alt"></i> descargar pdf</a></td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-list"></div> Suscriptores', array('onclick' => 'modal(\''.URL::route($ruta["listsuscriptores"], $value->id).'\', \'Lista de suscriptores para: '.$value->nombre.'\', this);', 'class' => 'btn btn-default btn-xs')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif
