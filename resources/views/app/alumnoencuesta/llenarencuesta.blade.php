@if(!$existe)
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <div class="row m-b-30">
                <div class="col-sm-12">
                	<h3 class="text-warning">No está autorizado para llenar esta encuesta.</h3>
				</div>
				<div class="col-md-12">
					{!! Form::button('<div class="glyphicon glyphicon-chevron-left"></div> Regresar', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm', 'onclick' => 'cargarRutaMenu(\'http://localhost/unprg/alumnoencuesta\', \'container\', \'\');')) !!}
				</div>
            </div>
        </div>
    </div>
</div>

@else

<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <div class="row m-b-30">
                <div class="col-sm-12">
                	<h3 class="text-warning">Estás autorizado para llenar esta encuesta.</h3>
				</div>
				<div class="col-md-12">
					{!! Form::button('<div class="glyphicon glyphicon-chevron-left"></div> Regresar', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm', 'onclick' => 'cargarRutaMenu(\'http://localhost/unprg/alumnoencuesta\', \'container\', \'\');')) !!}
				</div>
            </div>
        </div>
    </div>
</div>

@endif