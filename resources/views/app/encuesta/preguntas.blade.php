<script>
	function gestionpregunta(num, id){
		if(num == 1){
			if(!$('#pregunta').val()) {
				$('#pregunta').focus()
				return false;
			}
			route = 'encuesta/nuevapregunta/' + {{ $encuesta_id }};
		} else {
			route = 'encuesta/eliminarpregunta/' + id + '/' + {{ $encuesta_id }};
		}

		$.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
			data: $('#formNuevaPregunta').serialize(),
			beforeSend: function(){
				$('#tablaPreguntas').html(imgCargando());
				$('#correcto').addClass('hidden');
	        },
	        success: function(res){
	        	$('#tablaPreguntas').html(res);
				$('#pregunta').val('').focus();
				$('#correcto').removeClass('hidden');
	        }
		});
	}

	$('.carousel').carousel({
  		pause: true,
    	interval: false,
	});
</script>

<style>
	.modal{
	    overflow-y: auto;
	}
</style>

<div class="row">
	<div class="col-sm-12">
		<div id="carousel-ejemplo" class="carousel slide" data-ride="carousel">
  				<div class="carousel-inner" role="listbox">
    				<div class="item active">
      					<!-- ppppp -->
						<div class="row">
						    <div class="col-sm-12">
						        <div class="card-box table-responsive">
						            <div class="row m-b-30">
						                <div class="col-sm-12">
											{!! Form::open(['route' => null, 'method' => 'GET', 'onsubmit' => 'return false;', 'class' => 'form-inline', 'id' => 'formNuevaPregunta']) !!}
											<div class="form-group">
												{!! Form::label('pregunta', 'Pregunta:') !!}
												{!! Form::text('pregunta', '', array('class' => 'form-control input-xs', 'id' => 'pregunta')) !!}
												{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Añadir', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md', 'id' => 'btnAnadir', 'onclick' => 'gestionpregunta(1, "");')) !!}
												{!! Form::button('<i class="glyphicon glyphicon-check"></i> ¡Correcto!', array('class' => 'btn btn-success input-sm waves-effect waves-light m-l-10 btn-md hidden', 'id' => 'correcto', 'onclick' => '#')) !!}
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
													<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'gestionpregunta(2, ' . $value->id . ');', 'class' => 'btn btn-xs btn-danger')) !!}</td>
													<td><a href="#carousel-ejemplo" style="btn btn-default btn-xs" data-slide="next"><div class="glyphicon glyphicon-list"></div> Alternativas</a>
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
    				</div>
    
				    <div class="item">
				      	Contenido de las altenativas pe causa 
				      	<a href="#carousel-ejemplo" role="button" data-slide="prev">
						    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						    <span class="sr-only">Previo</span>
						</a>
				    </div>           
  				</div>
  			</div>
  		</div>
  	</div>
</div>