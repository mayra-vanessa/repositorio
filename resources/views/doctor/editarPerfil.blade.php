@extends('layouts.plantilla_general')

@section('title', 'Editar Perfil')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-user-md"></i> Editar Perfil</li>
    </ul>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-5 font-weight-bold">Editar Perfil</h1>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <h2 class="font-weight-bold">Información del Especialista</h2>
                <form method="POST" action="{{ route('doctor.actualizarPerfil') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Campos de edición del perfil del especialista -->
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $doctor->nombre }}">
                    </div>

                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ $doctor->apellidos }}">
                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $doctor->direccion }}">
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" pattern="[0-9]{10}" value="{{ $doctor->telefono }}">
                        <small class="form-text text-muted">Ingresa solo números (10 dígitos).</small>
                    </div>

                    <div class="form-group">
                        <label for="foto">URL de Foto de Perfil</label>
                        <input type="url" class="form-control" id="foto" name="foto" value="{{ $doctor->foto }}">
                    </div>

                    <h2 class="font-weight-bold mt-4">Información de la Cuenta</h2>
                    <!-- Campos de edición de la cuenta -->
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="text" class="form-control" id="correo" name="correo" value="{{ $doctor->user->email }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="password">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" pattern="[A-Za-z0-9!?-]{8,12}">
                        <small class="form-text text-muted">De 8 a 12 caracteres alfanuméricos.</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="showPassword">
                            <label class="custom-control-label" for="showPassword">Mostrar Contraseña</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar la contraseña
        document.getElementById('showPassword').addEventListener('change', function() {
            var passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>
@endsection
