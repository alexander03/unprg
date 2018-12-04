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
	<tbody id="registrosTab">
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td>{{ $contador }}</td>
			<td>{{ $value->nombre }}</td>
			<td>{{ Date::parse($value->fechai)->format('d/m/y') }}</td>
			<td>{{ Date::parse($value->fechaf)->format('d/m/Y') }}</td>
			<td>{{ ($value->estado == 0)?'Cerrado':'Aperturado'}}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-xs btn-warning')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
			@if($value->estado == 0)
			<td>{!! Form::button('<div class="glyphicon"></div> Aperturar', array('onclick' => '', 'class' => 'btn btn-xs btn-success btn_aperturar','idoferta' => ''.$value->id,'id'=>''.$value->id)) !!}</td>
			@else
			<td>{!! Form::button('<div class="glyphicon"></div> Cerrar', array('onclick' => '', 'class' => 'btn btn-xs btn-danger btn_cerrar','idoferta' => ''.$value->id,'id'=>''.$value->id)) !!}</td>
			@endif
			
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>

<div class="modal" id="aperturaModal" tabindex="-1" data-keyboard="false" role="dialog" aria-labelledby="aperturaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header">
			<h3>Modificar fecha final de apertura</h3>
		</div>
		<div class="modal-body">
			<fieldset class="col-12">
				<div>
					<div id = "msjeerror" class = "alert alert-success">Asegurese que la fecha final sea mayor a la actual !</div>
				</div>
				<form id = 'formFecha' name = 'formFecha'>
					<div class="form-group col-xs-12">
						{!! Form::label('fechaFinal', 'Fecha Final: *', array('class' => '')) !!}
						{!! Form::date('fechaFinal', null, array('class' => 'form-control ', 'id' => 'fechaFinal', 'placeholder' => 'fecha fin')) !!}
					</div>
					<input type='hidden' id='idofertm' value=''>
				</form>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button type="button" id="btnModifecha" class="btn btn-success">Guardar</button>
			<button type="button" id ="btnCerrar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
		</div>
		
    </div>
  </div>
</div>

<script>
$('.btn_aperturar').each(function (){
	$(this).click(function (){
		$('#aperturaModal').fadeIn();
		$('#idofertm').val(''+$(this).attr('id'));
	});
});

$('#btnModifecha').click(function(){
	var fechaActual = new Date();
	var fechaFinal = new Date($('#fechaFinal').val());
	var result = fechaActual.getTime() < fechaFinal.getTime();
	if(result){
		procesarAjaxofert('aperturar',$('#idofertm').val());
		
	}else{
		$('#msjeerror').removeClass("alert-success");
        $('#msjeerror').addClass( "alert-danger" );
		$('#msjeerror').html("La fecha final debe ser mayor a la actual !");
	}
});
$('#btnCerrar').click(function(){
	$('#aperturaModal').fadeOut();
});
$('.btn_cerrar').each(function (){
	$(this).click(function (){
		procesarAjaxofert('cerrar',$(this).attr('id'));
	});
});


function procesarAjaxofert(accion, id){
	var route = 'oferta/'+accion+"/"+id;
	console.log("id: "+id);
	$.ajax({
		url: route,
		headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
		type: 'GET',
		data: $('#formFecha').serialize()+"&idofert="+id+"",
		beforeSend: function(){
			
		},
		success: function(JSONRESPONSE){
			if(JSONRESPONSE.toLowerCase()==="ok"){
				if(accion.toLowerCase()==="aperturar"){
					$('#aperturaModal').fadeOut();
					$('#idofertm').val("");
					mostrarMensaje('Oferta aperturada!','OK');
					
				}else{
					mostrarMensaje('Oferta cerrada!','OK');
				}
				buscar('{{ $entidad }}');
			}else{
				mostrarMensaje('Error interno en el Servidor!','ERROR');
			}
		},
		error: function () {
			/*MOSTRAMOS MENSAJE ERROR SERVIDOR*/
			mostrarMensaje('Error interno en el Servidor!','ERROR');
		}
	});
}

</script>
@endif
