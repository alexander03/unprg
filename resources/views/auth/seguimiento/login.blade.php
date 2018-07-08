@include('auth.seguimiento.header')
<div class="wrapper-page">
    <div class="card-box">
        <div class="text-center">
            <a href="index.php" class="logo-lg"><i class="md md-equalizer"></i> <span>Seguimiento - UNPRG</span> </a>
        </div>
        <form action="{{ url('/seguimiento/login') }}" method="post" class="form-horizontal m-t-20">
            {{ csrf_field() }}
            @if (count($errors) > 0)
            <div class="form-group has-error has-feedback">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-red">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="form-group">
                <div class="col-xs-12">
                    <input name="login" class="form-control" type="text" required="" placeholder="Usuario">
                    <i class="md md-account-circle form-control-feedback l-h-34"></i>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <input name="password" class="form-control" type="password" required="" placeholder="Password">
                    <i class="md md-vpn-key form-control-feedback l-h-34"></i>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <i class="md md-group form-control-feedback l-h-34"></i>
                    <select name="usertype_id" class="form-control" required="" >
                        <option value="1" selected>Tipo de Usuario</option>
                        <option value="2">ALUMNO</option>
                        <option value="5">UNPRG</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="checkbox checkbox-primary">
                        <input name="remember" id="checkbox-signup" type="checkbox">
                        <label for="checkbox-signup">
                            Recordarme
                        </label>
                    </div>

                </div>
            </div>
            <div class="form-group text-right m-t-20">
                <div class="col-xs-12">
                    <button class="btn btn-primary btn-custom w-md waves-effect waves-light" type="submit">Ingresar
                    </button>
                </div>
            </div>
            <div class="form-group m-t-30" style="display: none;">
                <div class="col-sm-7">
                    <a href="pages-recoverpw.php" class="text-muted"><i class="fa fa-lock m-r-5"></i> ¿Olvidó su contraseña?</a>
                </div>
                <div class="col-sm-5 text-right">
                    <a href="pages-register.php" class="text-muted">Crear una cuenta</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('auth.seguimiento.footer')