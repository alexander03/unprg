<?php
use App\OfertaAlumno;
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
		$idAlumno = OfertaAlumno::getIdAlumno();
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr 'idEvento'= {{ $value->id }} >
			<td>{{ $contador }}</td>
			<td>{{ $value->nombre }}</td>
			<?php
			$estaRegistrado  =  DB::table('evento_alumno')->where('alumno_id','=', $idAlumno)->where('evento_id','=', $value->evento_id)->count();
			echo $estaRegistrado;
			if($estaRegistrado > 0 ){
			?>
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Desuscribirse', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
			<?php
			}else{
			?>
			<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Suscribirse', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-xs btn-warning')) !!}</td>
			<?php
			}
			?>
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
@endif