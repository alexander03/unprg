@include('auth.bolsa.header')
<div class="wrapper-page">
    <div class="card-box">
        <div class="text-center">
            <a class="logo-lg"><i class="md md-equalizer"></i> <span>Registrar Empresa</span> </a>
        </div>
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div id="divMensajeError"></div>

            <form class="form-horizontal" id="registro" role="form" method="POST" action="{{ url('/registro') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('ruc') ? ' has-error' : '' }}">
                    <div class="col-xs-12">
                        <input name="ruc" id="ruc" class="form-control" type="text" placeholder="RUC" autofocus>
                        <i class="md md-account-circle form-control-feedback l-h-34"></i>
                        @if ($errors->has('ruc'))
                            <span class="help-block">
                                <strong>{{ $errors->first('ruc') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="razonsocial" id="razonsocial" class="form-control" type="text" placeholder="Razón Social" >
                        <i class="md md-info-outline form-control-feedback l-h-34"></i>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="direccion" id="direccion" class="form-control" type="text" placeholder="Dirección" >
                        <i class="md md-account-balance form-control-feedback l-h-34"></i>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="telefono" id="telefono" class="form-control" type="text" placeholder="Teléfono">
                        <i class="md md-phone form-control-feedback l-h-34"></i>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                    <div class="col-md-12">
                        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" placeholder="Correo Electrónico" autofocus>
                        <i class="md md-mail form-control-feedback l-h-34"></i>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                    <div class="col-md-12">
                        <input id="password" type="password" class="form-control" name="password" placeholder="Contraseña" >
                        <i class="md md-vpn-key form-control-feedback l-h-34"></i>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

                    <div class="col-md-12">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirme Contraseña">
                        <i class="md md-vpn-key form-control-feedback l-h-34"></i>

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group" style="text-align: center">
                    <div class="col-md-6">
                        <a class="btnGuardar btn btn-primary btn-custom w-md waves-effect waves-light">
                            Registrar
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url('/bolsa/login') }}" class="btn btn-success btn-custom w-md waves-effect waves-light">
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('auth.bolsa.footer')


<script>

$('.btnGuardar').on('click', function(){
    guardarRegistro();    
});

 /*   function consultaRUC(){
        var ruc = $("#ruc").val();
        $.ajax({
            type: 'GET',
            url: "SunatPHP/demo.php",
            data: "ruc="+ruc,
            beforeSend(){
                $("#ruc").val('Comprobando Empresa');
            },
            success: function (data, textStatus, jqXHR) {
                if(data.RazonSocial == null) {
                    alert('Empresa no encontrada');
                    $("#ruc").val('').focus();
                } else {
                    $("#ruc").val(ruc);
                    $("#razonsocial").val(data.RazonSocial);
                    $("#direccion").val(data.Direccion);
                    $("#telefono").val('').focus();
                }
            }
        });
    };

    $(document).on('keyup', '#ruc', function() {
        if($(this).val().length === 11) {
            consultaRUC();
        } else {
            $("#razonsocial").val('');
            $("#direccion").val('');
        }
    });*/
</script>

