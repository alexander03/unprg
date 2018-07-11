<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="card-box table-responsive">
            <div id="divMensajeError{!! $entidad !!}"></div>
            {!! Form::model($user, $formData) !!}	
            <div class="form-group">
                <label class="control-label col-md-4" for="mypassword">Contraseña Actual:</label>
                <div class="col-md-6">
                    <input type="password" name="mypassword" class="form-control">
                </div>
                <div class="text-danger">{{$errors->first('mypassword')}}</div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4" for="password">Nueva Contraseña:</label>
                <div class="col-md-6">
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="text-danger">{{$errors->first('password')}}</div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4" for="mypassword">Confirme Contraseña:</label>
                <div class="col-md-6">
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-md-offset-5">
                    {!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-primary', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>