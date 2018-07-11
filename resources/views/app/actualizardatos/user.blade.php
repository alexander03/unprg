<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>
<?php
use Illuminate\Support\Facades\Auth;
$user = Auth::user();
?>
<!-- Main content -->
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
			@if($user->usertype_id == "2" || $user->usertype_id == "3")
			<div id="divMensajeError{!! $entidad !!}"></div>
				{!! Form::model($alumno, $formData) !!}	
				{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
				<div class="col-sm-6">
						<div class="form-group">
							{!! Form::label('codigo', 'Codigo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::text('codigo', null, array('disabled','class' => 'form-control input-xs', 'placeholder' => 'Ingrese codigo')) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('nombres', 'Nombres:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::text('nombres', null, array('disabled','class' => 'form-control input-xs', 'placeholder' => 'Ingrese nombre')) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('apellidopaterno', 'Apellido Paterno:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::text('apellidopaterno', null, array('disabled','class' => 'form-control input-xs', 'placeholder' => 'Ingrese apellido paterno')) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('apellidomaterno', 'Apellido Materno:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::text('apellidomaterno', null, array('disabled','class' => 'form-control input-xs', 'placeholder' => 'Ingrese apellido materno')) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('dni', 'DNI:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::text('dni', null, array('class' => 'form-control input-xs input-number', 'id' => 'dni', 'placeholder' => 'Ingrese dni')) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('fechanacimiento', 'Fecha de Nacimiento:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::date('fechanacimiento', null, array('class' => 'form-control input-xs', 'id' => 'fechanacimiento', 'placeholder' => 'fecha nacimiento')) !!}
							</div>
							<?php
							if($alumno != null){
								echo "<input type='hidden' id='fechaNac' value='".Date::parse($alumno->fechanacimiento )->format('yyyy-MM-dd')."'>";

							}else{
							echo "<input type='hidden' id='fechaNac' value=''>";
								
							}
							?>
						</div>

						<div class="form-group">
							{!! Form::label('direccion', 'Direccion:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese direccion')) !!}
							</div>
						</div>
				</div>
				<div class="col-sm-6">

						<div class="form-group">
							{!! Form::label('telefono', 'Telefono:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::text('telefono', null, array('class' => 'form-control input-xs input-number', 'id' => 'telefono', 'placeholder' => 'Ingrese telefono')) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('email', 'E-Mail:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::text('email', null, array('class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'email@ejemplo.com')) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('escuela_id', 'Escuela:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::select('escuela_id', $cboEscuela, null, array('disabled','class' => 'form-control input-xs', 'id' => 'escuela_id')) !!}
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('especialidad_id', 'Especialidad:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								{!! Form::select('especialidad_id', $cboEspecialidad, null, array('class' => 'form-control input-xs', 'id' => 'especialidad_id')) !!}
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('image', 'Imagen de perfil:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
							<div class="col-lg-9 col-md-9 col-sm-9">
								<input type="file" name="image" class ="form-control input-xs" id="image" >
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-12 col-md-12 col-sm-12 text-right">
								{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-primary', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
							</div>
						</div>
				{!! Form::close() !!}
				
			@elseif($user->usertype_id == "4" || $user->usertype_id == "1" || $user->usertype_id == "5")

				<div id="divMensajeError{!! $entidad !!}"></div>
				{!! Form::model($empresa, $formData) !!}	
				{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
				<div class="col-sm-6">
					<div class="form-group">
						{!! Form::label('ruc', 'RUC:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
						<div class="col-lg-9 col-md-9 col-sm-9">
							{!! Form::text('ruc', null, array('disabled','class' => 'form-control input-xs', 'id' => 'ruc', 'placeholder' => 'Ingrese RUC', 'maxlength' => '11')) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('razonsocial', 'Razón Social:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
						<div class="col-lg-9 col-md-9 col-sm-9">
							{!! Form::text('razonsocial', null, array('class' => 'form-control input-xs', 'id' => 'razonsocial', 'placeholder' => 'Ingrese Razón Social')) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('direccion', 'Dirección:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
						<div class="col-lg-9 col-md-9 col-sm-9">
							{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese Dirección')) !!}
						</div>
					</div>
				</div>
				<div class="col-sm-6">	
					<div class="form-group">
						{!! Form::label('telefono', 'Teléfono:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
						<div class="col-lg-9 col-md-9 col-sm-9">
							{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese Teléfono', 'maxlength' => '9')) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('email', 'Email:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
						<div class="col-lg-9 col-md-9 col-sm-9">
							{!! Form::email('email', null, array('disabled','class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'Ingrese Email')) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('image', 'Imagen de perfil:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
						<div class="col-lg-9 col-md-9 col-sm-9">
							<input type="file" name="image" class ="form-control input-xs" id="image" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-12 col-md-12 col-sm-12 text-right">
							{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-primary', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
						</div>
					</div>
				</div>
				{!! Form::close() !!}
			@endif
        </div>
    </div>
</div>
