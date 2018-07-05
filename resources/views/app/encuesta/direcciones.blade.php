<script>
	function gestionpa(num, tipo, id, idpadre){
		if(num == 1){
			if(!$('#' + tipo).val()) {
				$('#' + tipo).focus()
				return false;
			}
			route = 'encuesta/nueva' + tipo + '/' + idpadre;
		} else if(num == 2){
			route = 'encuesta/eliminar' + tipo + '/' + id + '/' + idpadre;
		} else {
			route = 'encuesta/listar' + tipo + 's/' + idpadre;
		}

		$.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
			data: $('#formnueva' + tipo).serialize(),
			beforeSend: function(){
				$('#tabla' + tipo + 's').html(imgCargando());
				$('.correcto').addClass('hidden');
	        },
	        success: function(res){
	        	$('#tabla' + tipo + 's').html(res);
				$('#' + tipo).val('').focus();
				$('.correcto').removeClass('hidden');
				if(num == 3) {
					$('.correcto').addClass('hidden');
				}
	        }
		});
	}

	function cargarselect(entidad){
		padre = 'escuela';
		if(entidad == 'escuela'){
			padre = 'facultad';
		}
		var select = $('#' + padre + '_id').val();
		route = 'encuesta/cargarselect/' + select + '?entidad=' + entidad;

		$.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
	        success: function(res){
	        	$('#select' + entidad).html(res);
	        	if(padre == 'facultad'){
					$('#selectespecialidad').html('<select class="form-control input-sm" id="especialidad_id" name="especialidad_id"><option value="" selected="selected">Seleccione</option></select>');

	        	}
	        	alert(si);
	        }
		});
	}
</script>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <div class="row">
				{!! Form::open(['route' => null, 'method' => 'GET', 'onsubmit' => 'return false;', 'class' => 'form-horizontal', 'id' => 'formnuevapregunta']) !!}
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
						{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md btnAnadir input-sm', 'onclick' => 'gestionpa(1, "pregunta", "", ' . $encuesta_id . ');')) !!}
						{!! Form::button('<i class="glyphicon glyphicon-check"></i> ¡Correcto!', array('class' => 'correcto btn btn-info waves-effect waves-light m-l-10 btn-md hidden input-sm', 'onclick' => '#')) !!}	
					</div>
				</div>
				{!! Form::close() !!}
            </div>

            <div id="tablapreguntas">
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
							<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'gestionpa(2, "pregunta", ' . $value->id . ', ' . $encuesta_id . ');', 'class' => 'btn btn-xs btn-danger')) !!}</td>
							<td><a href="#carousel-ejemplo" style="btn btn-default btn-xs" data-slide="next" onclick='gestionpa(3, "alternativa", "", {{ $value->id }}); $(".correcto").addClass("hidden");'><div class="glyphicon glyphicon-list"></div> Alternativas</a>
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