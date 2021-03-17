@extends('layouts.auth_app')

@section('content')
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                        <div class="col-lg-7">
                            <div class="p-5">
                                <br><br><br><br>
                                <center><img src="{{ url('public/backend/img/logo.png') }}" id="logo" style="height: 120px;"></center>
                                <br>
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Cambiar contraseña</h1>
                                </div>
                                <form class="user" method="POST" action="{{ url('password/update') }}">
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Contraseña">
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ $rut }}" name="rut">
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        {{ __('Actualizar') }}
                                    </button>
                                </form>
                                <br><br><br>
                                <hr>
                                <br><br><br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
