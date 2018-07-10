@include('auth.bolsa.header')
<div class="wrapper-page">
    <div class="card-box">
        <div class="text-center">
            <a class="logo-lg"><i class="md md-equalizer"></i> <span>Restablecer Contraseña</span> </a>
        </div>
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div style="text-align: center">
                <a href="{{ url('/bolsa/login') }}" class="col-md-5 btn btn-primary btn-custom w-md waves-effect waves-light">
                    App Bolsa
                </a>
            </div>
            <div class="col-md-2"></div>
            <div style="text-align: center">
                <a href="{{ url('/seguimiento/login') }}" class=" col-md-5 btn btn-primary btn-custom w-md waves-effect waves-light">
                    App Seguimiento
                </a>
            </div>
        </div>
    </div>
</div>
@include('auth.bolsa.footer')