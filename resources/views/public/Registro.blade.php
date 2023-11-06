@extends('layouts.plantilla_general')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                
                <div class="card-body">
                    <div id="signUpForm">
                        <h3 class="text-center mb-4">Registrarse</h3>
                        <form action="{{ route('registro.submit') }}" method="POST">
                            @csrf
                            <!-- Agregar campos del formulario de registro aquí -->
                            <div class="form-group">
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
                            </div>
                            <div class="form-group">
                                <input type="tel" class="form-control" name="telefono" placeholder="Teléfono" required maxlength="10" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirmar Contraseña" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">Mostrar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                            </div>
                        </form>
                        <p class="text-center mt-3">¿Ya tienes una cuenta?</p>
                        <p class="text-center mt-3"> <a href="#" id="showSignInLink" class="white-link">Iniciar Sesión</a></p>

                    </div>
                    <div id="signInForm" style="display: none;">
                        <h3 class="text-center mb-4">Iniciar Sesión</h3>
                        <form action="{{ route('login.submit') }}" method="POST">
                            @csrf
                            <!-- Agregar campos del formulario de inicio de sesión aquí -->
                            <div class="form-group">
                                <select name="tipo_usuario" class="form-control" required>
                                    <option value="" disabled selected>Selecciona el tipo de usuario</option>
                                    <option value="3">Paciente</option>
                                    <option value="2">Doctor</option>
                                    <option value="1">Administrador</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password_login" placeholder="Contraseña" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordLogin">Mostrar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                            </div>
                        </form>
                        <p class="text-center mt-3">¿No tienes una cuenta? </p>
                        <p class="text-center mt-3"><a href="#" id="showSignUpLink" class="white-link">Registrarse</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function () {
        // Mostrar/ocultar contraseña en el formulario de registro
        $("#togglePassword").click(function () {
            var passwordField = $("#password");
            var passwordConfirmationField = $("#password_confirmation");

            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                passwordConfirmationField.attr("type", "text");
                $(this).text("Ocultar");
            } else {
                passwordField.attr("type", "password");
                passwordConfirmationField.attr("type", "password");
                $(this).text("Mostrar");
            }
        });

        // Mostrar/ocultar contraseña en el formulario de inicio de sesión
        $("#togglePasswordLogin").click(function () {
            var passwordField = $("#password_login");

            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                $(this).text("Ocultar");
            } else {
                passwordField.attr("type", "password");
                $(this).text("Mostrar");
            }
        });

        // Validación de confirmación de contraseña en el formulario de registro
        $("#password_confirmation").on("keyup", function () {
            var password = $("#password").val();
            var confirmPassword = $(this).val();

            if (password === confirmPassword) {
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
            } else {
                $(this).removeClass("is-valid");
                $(this).addClass("is-invalid");
            }
        });

        $("#showSignInLink").click(function () {
            $("#signUpForm").hide();
            $("#signInForm").fadeIn();
        });

        $("#showSignUpLink").click(function () {
            $("#signInForm").hide();
            $("#signUpForm").fadeIn();
        });
    });
</script>
<!-- Dentro de tu vista blade -->

@endsection
