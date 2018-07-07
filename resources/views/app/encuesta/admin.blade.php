<script>
	function cargarselect2(entidad){
		padre = 'escuela';
		if(entidad == 'escuela'){
			padre = 'facultad';
		}

		var select = $('#_' + padre + '_id').val();

		if(select == '' && entidad == 'escuela'){
			$('#_escuela_id').html('<option value="" selected="selected">Seleccione</option>');
			$('#_especialidad_id').html('<option value="" selected="selected">Seleccione</option>');
    	}

		route = 'encuesta/cargarselect/' + select + '?entidad=' + entidad + '&t=si';

		$.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
			beforeSend: function() {
				$('#_' + entidad + '_id').html('<option value="" selected="selected">Seleccione</option>');
	        	if(padre == 'facultad'){
					$('#_especialidad_id').html('<option value="" selected="selected">Seleccione</option>');
	        	}
			},
	        success: function(res){
	        	$('#_' + entidad + '_id').html(res);
	        	if(padre == 'facultad'){
					$('#_especialidad_id').html('<option value="" selected="selected">Seleccione</option>');
	        	}
	        }
		});
	}
</script>
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
						{!! Form::label('nombre', 'Nombre:', array('class' => 'input-sm')) !!}
						{!! Form::text('nombre', '', array('class' => 'form-control input-sm', 'id' => 'nombre')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('tipoencuesta_id', 'Tipo:', array('class' => 'input-sm')) !!}
						{!! Form::select('tipoencuesta_id', $cboTipoEncuesta, null, array('class' => 'form-control input-sm', 'id' => 'tipoencuesta_id')) !!}
					</div>					
					<div class="form-group">
						{!! Form::label('_facultad_id', 'Facultad:', array('class' => 'input-sm')) !!}
						{!! Form::select('_facultad_id', $cboFacultad, null, array('class' => 'form-control input-sm', 'id' => '_facultad_id', 'onchange' => 'cargarselect2("escuela")')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('_escuela_id', 'Escuela:', array('class' => 'input-sm')) !!}
						{!! Form::select('_escuela_id', $cboEscuela, null, array('class' => 'form-control input-sm', 'id' => '_escuela_id', 'onchange' => 'cargarselect2("especialidad")')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('_especialidad_id', 'Especialidad:', array('class' => 'input-sm')) !!}
						{!! Form::select('_especialidad_id', $cboEspecialidad, null, array('class' => 'form-control input-sm', 'id' => '_especialidad_id')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('filas', 'Filas:', array('class' => 'input-sm'))!!}
						{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					{!! Form::button('<i class="glyphicon glyphicon-search"></i>', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm input-sm', 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm input-sm', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
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
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="name"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
	});
</script>
