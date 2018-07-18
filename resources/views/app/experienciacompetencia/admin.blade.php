<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            {{--
            <ol class="breadcrumb pull-right">
                <li><a href="#">Minton</a></li>
                <li><a href="#">Tables</a></li>
                <li class="active">Datatable</li>
            </ol>
            --}}
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">

            <div class="row m-b-30">
                <div class="col-sm-12">
					{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
					{!! Form::hidden('page', 1, array('id' => 'page')) !!}
					{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
					
					<div class="form-group">
						{!! Form::label('codigo', 'Codigo:') !!}
						{!! Form::text('codigo', '', array('class' => 'form-control input-xs buscar-alumno', 'id' => 'codigo')) !!}
					</div>

					<div class="form-group">
						{!! Form::label('nombre', 'Nombre:') !!}
						{!! Form::text('nombre', '', array('class' => 'form-control input-xs buscar-alumno', 'id' => 'nombre')) !!}
					</div>

					<div class="form-group">
						{!! Form::label('escuela1_id', 'Escuela:') !!}
						{!! Form::select('escuela1_id', $cboEscuela, null, array('class' => 'form-control input-xs', 'id' => 'escuela1_id')) !!}
					</div>

					<div class="form-group">
						{!! Form::label('filas', 'Filas a mostrar:')!!}
						{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md', 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
					{!! Form::close() !!}
				</div>
            </div>

			<div id="listado{{ $entidad }}"></div>
			
            <table id="datatable" class="table table-striped table-bordered">
            </table>
        </div>
    </div>
</div>

<script>
	$(document).ready(function () {
		console.log("sdasd");
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="name"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});

		$('#codigo').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				console.log("entro 13");
				buscar('{{ $entidad }}');
			}
		});

		$('#nombre').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				console.log("entro 13");
				buscar('{{ $entidad }}');
			}
		});

		$('#escuela1_id').change(function(e){
			buscar('{{ $entidad }}');
		});
	});
</script>