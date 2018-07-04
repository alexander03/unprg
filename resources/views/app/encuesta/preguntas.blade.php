<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <div class="row m-b-30">
                <div class="col-sm-12">
					{!! Form::open(['route' => null, 'method' => 'GET', 'onsubmit' => 'return false;', 'class' => 'form-inline', 'id' => 'formNuevaPregunta']) !!}
					<div class="form-group">
						{!! Form::label('pregunta', 'Pregunta:') !!}
						{!! Form::text('pregunta', '', array('class' => 'form-control input-xs', 'id' => 'pregunta')) !!}
						{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Añadir', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md', 'id' => 'btnAnadir', 'onclick' => 'cargarRuta(\''.URL::route($ruta["nuevapregunta"], $encuesta_id) . '\', "tablaPreguntas");')) !!}
					</div>					
					{!! Form::close() !!}
                </div>
            </div>

            <div id="tablaPreguntas">
	            @if(count($lista) == 0)
				<h3 class="text-warning">No se encontraron resultados.</h3>
				@else
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
							<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-xs btn-warning')) !!}</td>
							<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
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
			</div>
        </div>
    </div>
</div>