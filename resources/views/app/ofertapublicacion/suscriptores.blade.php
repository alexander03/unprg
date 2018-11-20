
<div class="row">
	<div class="col-sm-12">
		<div id="carousel-ejemplo" class="carousel slide" data-ride="carousel">
  				<div class="carousel-inner" role="listbox">
    				<div class="item active">
      				
						<div class="row">
						    <div class="col-sm-12">
						        <div class="card-box table-responsive">
						            <div id="listsuscriptores">
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
													<td>{{ $value->alumno_nombres.'  '.$value->alumno_apellidopaterno.'  '.$value->alumno_apellidomaterno }}</td>
													<td>
													<a href="{{ route('vercurriculum',array('id'=>$value->alumno_id)) }}" class="btn btn-info waves-effect waves-light m-l-10 btn-xs" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> Ver </a>
													</td>
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
				      	<a href="#carousel-ejemplo" style="btn btn-default btn-xs" data-slide="prev" onclick="$('.correcto').addClass('hidden');"><div class="retorno glyphicon glyphicon-chevron-left"></div> Atrás</a>
						<div class="row">
						    <div class="col-sm-12">
						        <div class="card-box table-responsive">
						            <div id="tablaalternativas">							            
									</div>
						        </div>
						    </div>
						</div>
				    </div>           
  				</div>
  			</div>
  		</div>
  	</div>
</div>
