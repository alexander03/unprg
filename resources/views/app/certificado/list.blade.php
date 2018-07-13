<style>
	.panel-front { /* Only for this preview */
    margin-bottom:20px;
    margin-top:20px;
}

.panel-front .panel-heading .panel-title img {
	border-radius:3px 3px 0px 0px;
	width:100%;
}

.panel-front .panel-heading {
	padding: 0px;	
}

.panel-front .panel-heading h4 {
	line-height: 0;
}

.panel-front .panel-body h4 {
	font-weight: bold;
	margin-top: 5px;
	margin-bottom: 15px;
}

.text-right {
    margin-top: 10px;
}
</style>

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
			<td>{{ $value->nombre_certificadora }}</td>
			<td><button class='btn btn-xs btn-default ver-archivo' url_file = '{{ $value->url_archivo }}'><i class="fa fa-eye"></i>Ver Archivo</button></td>
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
<div class="modal fade" tabindex="-1" role="dialog" id="view-archivo">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <h4 class="modal-title">Vista Previa</h4>
		</div>
		<div class="modal-body">
			<div class="col-12">		
				<img src="https://i.imgur.com/UuosGKL.jpg" id="img_file" width="840" height="480"></a>
			</div>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		  <!--button type="button" class="btn btn-primary">Save changes</button-->
		</div>
	  </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <script>
	$(document).ready(function() {
		$('.ver-archivo').each(function(){
			if($(this).attr('url_file') !== ""){
				$(this).click(function(){
					$('#img_file').attr('src',$(this).attr('url_file'));
					$('#view-archivo').modal('show');							
				});
				//mostrarMensaje('No existe archivo!','ERROR');
			}else{
				$(this).attr("disabled",'disabled');
			}			
		});
	});
	
  </script>