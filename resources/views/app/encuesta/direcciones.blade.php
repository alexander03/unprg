<?php 

use App\Facultad;
use App\Escuela;
use App\Especialidad;

?>
<script>
	function gestionpa(encuesta_id, id, num){
		if(num == 1){
			if($('#facultad_id').val() == '') {
				return false;
			}
			route = 'encuesta/nuevadireccion/' + encuesta_id;
		} else if(num == 2){
			route = 'encuesta/eliminardireccion/' + id + '/' + encuesta_id;
		} 

		$.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
			data: $('#formnuevadireccion').serialize(),
			beforeSend: function(){
				$('#tabladirecciones').html(imgCargando());
				$('.correcto').addClass('hidden');
				$('.incorrecto').addClass('hidden');
	        },
	        success: function(res){
	        	$('#tabladirecciones').html(res);
				$('.correcto').removeClass('hidden');
				$('.incorrecto').addClass('hidden');
				$('#facultad_id').val('');
				$('#escuela_id').val('');
				$('#especialidad_id').val('');
	        }
		}).fail(function(){
			$('.incorrecto').removeClass('hidden');
			$('.correcto').addClass('hidden');
		});
	}

	function cargarselect(entidad){
		padre = 'escuela';
		if(entidad == 'escuela'){
			padre = 'facultad';
		}
		var select = $('#' + padre + '_id').val();
		route = 'encuesta/cargarselect/' + select + '?entidad=' + entidad + '&t=no';

		$.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
	        success: function(res){
	        	$('#select' + entidad).html(res);
	        	if(padre == 'facultad'){
					$('#selectespecialidad').html('<select class="form-control input-sm" id="especialidad_id" name="especialidad_id"><option value="" selected="selected">Seleccione</option></select>');
	        	}
	        }
		});
	}
</script>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <div class="row">
				{!! Form::open(['route' => null, 'method' => 'GET', 'onsubmit' => 'return false;', 'class' => 'form-horizontal', 'id' => 'formnuevadireccion']) !!}
				<div class="form-group">
					{!! Form::label('facultad_id', 'Facultad:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
					<div class="col-lg-10 col-md-10 col-sm-10">
						{!! Form::select('facultad_id', $cboFacultad, null, array('class' => 'form-control input-sm', 'id' => 'facultad_id', 'onchange' => 'cargarselect("escuela")')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('escuela_id', 'Escuela:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
            		<div class="col-lg-10 col-md-10 col-sm-10">
            			<div id="selectescuela">
							{!! Form::select('escuela_id', $cboEscuela, null, array('class' => 'form-control input-sm', 'id' => 'escuela_id', 'onchange' => 'cargarselect("especialidad")')) !!}
						</div>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('especialidad_id', 'Especialidad:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
					<div class="col-lg-10 col-md-10 col-sm-10">
						<div id="selectespecialidad">
							{!! Form::select('especialidad_id', $cboEspecialidad, null, array('class' => 'form-control input-sm', 'id' => 'especialidad_id')) !!}
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-12 col-md-12 col-sm-12 text-right">
						{!! Form::button('<i class="glyphicon glyphicon-check"></i> ¡Correcto!', array('class' => 'correcto btn btn-success waves-effect waves-light m-l-10 btn-md hidden input-sm', 'onclick' => '#')) !!}
						{!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> ¡Incorrecto!', array('class' => 'incorrecto btn btn-danger waves-effect waves-light m-l-10 btn-md hidden input-sm', 'onclick' => '#')) !!}
						{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md btnAnadir input-sm', 'onclick' => 'gestionpa(' . $encuesta_id . ', "", 1);')) !!}
					</div>
				</div>
				{!! Form::close() !!}
            </div>

            <div id="tabladirecciones">
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
						<?php

						$facultadx = Facultad::find($value->facultad_id);
				        $escuelax = Escuela::find($value->escuela_id);
				        $especialidadx = Especialidad::find($value->especialidad_id);

				        $rutadireccion = '';
				        if($facultadx != null) {
				            $rutadireccion .= $facultadx->nombre;
				        } if($escuelax != null) {
				            $rutadireccion .= ' -> ' . $escuelax->nombre;
				        } if($especialidadx != null) {
				            $rutadireccion .= ' -> ' . $especialidadx->nombre;
				        }

						?>
						<tr>
							<td>{{ $contador }}</td>
							<td>{{ $rutadireccion }}</td>
							<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'gestionpa(' . $encuesta_id . ', ' . $value->id . ', 2);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
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
