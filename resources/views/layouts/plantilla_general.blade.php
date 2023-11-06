<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>@yield('title')</title>

    <link rel="manifest" href="js/manifest.json">
    
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/js/sw.js').then(function (registration) {
            console.log('Service Worker registrado con éxito');
        }).catch(function (error) {
            console.log('Error al registrar el Service Worker:', error);
        });
        });
    }
    </script>

    <meta name="description" content="RVEC Un repositorio virtual de información sobre enfermedades crónicas.">
    <meta name="author" content="RVEC">
    <meta name="language" content="es">
    <meta name="theme-color" content="#29b73a">  <!-- Cambia #29b73a al color de tu elección -->

    <!-- Metas de aplicación web -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="RVEC">
    <meta name="msapplication-config" content="/browserconfig.xml">

    <!-- Metas de redes sociales -->
    <meta property="og:title" content="RVEC">
    <meta property="og:description" content="RVEC Un repositorio virtual de información sobre enfermedades crónicas.">
    <meta property="og:image" content="https://rvec.proyectowebuni.com/img/logo.png">
    <meta property="og:url" content="https://rvec.proyectowebuni.com/">
    <meta name="twitter:card" content="summary_large_image">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('/css/estilos.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @yield('styles')

    <style>
        .custom-bg {
            background-color: #29b73a;
        }
        .custom-toggler .navbar-toggler-icon {
            color: white;
        }
    </style>
</head>

<body id="body">

<nav class="navbar navbar-expand-lg navbar-light custom-bg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('Inicio') }}"><img src="{{ asset('img/logo.png') }}" alt="" width="200" height="70"></a>
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon custom-toggler"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- Elemento de Inicio -->
                <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                    <a class="nav-link text-white" href="{{ route('Inicio') }}"><i class="fa fa-home"></i> Inicio</a>
                </li>

                <!-- Suponiendo que hay otros elementos del menú aquí -->

                @if(Auth::check())
                    <!-- Elementos para usuarios autenticados -->
                    @if(Auth::user()->tipo_usuario == 1) <!-- Admin -->
                        <li class="nav-item">
                            <a class="nav-link text-white {{ Request::is('Pedidos') ? 'active' : '' }}" href="{{ route('admin.recetas') }}"><i class="fa fa-truck"></i> Pedidos receta</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="catalogosDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-th-list"></i> Catálogos
                            </a>
                            
                            <div class="dropdown-menu" aria-labelledby="catalogosDropdown">
                                <!-- Usando botones para desencadenar acciones de navegación -->
                                <button class="dropdown-item {{ Request::is('admin/Enfermedades') ? 'active' : '' }}" onclick="location.href='{{ route('admin.enfermedades') }}'">Enfermedades</button>
                                <button class="dropdown-item {{ Request::is('admin/Productos') ? 'active' : '' }}" onclick="location.href='{{ route('admin.productos') }}'">Productos</button>
                                <button class="dropdown-item {{ Request::is('admin/Especialistas') ? 'active' : '' }}" onclick="location.href='{{ route('admin.especialistas') }}'">Especialistas</button>
                                <button class="dropdown-item {{ Request::is('admin/Instituciones') ? 'active' : '' }}" onclick="location.href='{{ route('admin.instituciones') }}'">Instituciones</button>
                                <button class="dropdown-item {{ Request::is('admin/Proveedores') ? 'active' : '' }}" onclick="location.href='{{ route('admin.proveedores') }}'">Proveedores</button>
                            </div>
                        </li>
                    @elseif(Auth::user()->tipo_usuario == 2) <!-- Doctor -->
                        <li class="nav-item">
                            <a class="nav-link text-white {{ Request::is('Consultas') ? 'active' : '' }}" href="{{ route('doctor.consultas') }}"><i class="fa fa-stethoscope"></i> Consultas</a>
                        </li>
                    @elseif(Auth::user()->tipo_usuario == 3) <!-- Paciente -->
                        <li class="nav-item">
                            <a class="nav-link text-white {{ Request::is('Enfermedades') ? 'active' : '' }}" href="{{ route('enfermedades') }}"><i class="fa fa-heartbeat"></i> Enfermedades</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ Request::is('Consultas') ? 'active' : '' }}" href="{{ route('paciente.consultas') }}"><i class="fa fa-stethoscope"></i> Consultas</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item {{ Request::is('Informacion') ? 'active' : '' }}">
                        <a class="nav-link text-white" href="{{ route('Informacion') }}"><i class="fa fa-newspaper-o"></i> Información</a>
                    </li>
                @endif

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="acercaDeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-info-circle"></i> Acerca de
                    </a>
                    <div class="dropdown-menu" aria-labelledby="acercaDeDropdown">
                        <button class="dropdown-item {{ Request::is('Quienes_Somos') ? 'active' : '' }}" onclick="location.href='{{ route('QuienesSomos') }}'">Quiénes somos</button>
                        <button class="dropdown-item {{ Request::is('Contactanos') ? 'active' : '' }}" onclick="location.href='{{ route('Contactanos') }}'">Contáctanos</button>
                    </div>
                </li>

                @if(Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="perfilDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <!-- Icono condicional basado en el tipo de usuario -->
                            @if(Auth::user()->tipo_usuario == 3) <i class="fa fa-user"></i>
                            @elseif(Auth::user()->tipo_usuario == 2) <i class="fa fa-user-md"></i>
                            @elseif(Auth::user()->tipo_usuario == 1) <i class="fa fa-user-secret"></i> 
                            @endif Perfil
                        </a>
                        <div class="dropdown-menu" aria-labelledby="perfilDropdown">
                           <!--  @if(Auth::user()->tipo_usuario == 2)
                                <button class="btn btn-link dropdown-item" onclick="location.href='{{ route('doctor.editarPerfil') }}'">Editar Perfil</button>
                            @endif
                            @if(Auth::user()->tipo_usuario == 3)
                                <button class="dropdown-item" onclick="location.href='#'">Editar Perfil</button>
                            @endif -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <button class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</button>
                        </div>
                    </li>
                @endif

                @guest
                    <li class="nav-item {{ Request::is('Login') ? 'active' : '' }}">
                        <a class="nav-link text-white" href="{{ route('Login') }}"><i class="fa fa-sign-in"></i> Iniciar Sesión</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>




<!-- Cuerpo (contenido específico de cada página) -->
<main>
    @yield('content')
</main>

<!-- Pie de página (footer) -->
<!-- PIE DE PAGINA-->
<footer>
    <div class="footer-container">
        <div class="footer-content-container">
            <h3 class="website-logo">RVEC</h3>
            <span class="footer-info">Repositorio Virtual de las </span>
            <span class="footer-info">Enfermedades Cronicas</span>
        </div>
        <div class="footer-menus">
            <div class="footer-content-container">
                <span class="menu-title"><i class="fa fa-bars"></i> Menu</span>
                <li class="nav-item">
                    <a class="menu-item-footer" href="{{ route('Inicio') }}"><i class="fa fa-fw fa-home"></i> Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="menu-item-footer" href="{{ route('Informacion') }}"><i class="fa fa-newspaper-o"></i> Informacion</a>
                </li>
                <li class="nav-item">
                    <a class="menu-item-footer" href="#"><i class="fa fa-camera-retro"></i> Testimonios</a>
                </li>
            </div>
            <div class="footer-content-container">
                <span class="menu-title"><i class="fa fa-gavel"></i> legal</span>
                <li class="nav-item">
                    <a class="menu-item-footer" href="{{ route('Aviso') }}"><i class="fa fa-file-text"></i> Aviso de Privacidad</a>
                </li>
                <li class="nav-item">
                    <a class="menu-item-footer" href="{{ route('Cockies') }}"><i class="fa fa-file-text-o"></i> Cookies</a>
                </li>
            </div>
        </div>

        <div class="footer-content-container">
            <span class="menu-title">Siguenos</span>
            <div class="social-container">
                <li class="nav-item">
                <a href="" class="social-link"><i class="fa fa-facebook-official" style="color: #00FF00;"></i></a>
                </li>
                <li class="nav-item">
                <a href="" class="social-link"><i class="fa fa-instagram" style="color: #00FF00;"></i></a>
                </li>
                <li class="nav-item">
                <a href="" class="social-link"><i class="fa fa-twitter-square" style="color: #00FF00;"></i></a>
                </li>
            </div>
        </div>
    </div>
    <div class="copyright-container">
        <span class="footer-info"> <center> Copyright 2023, RVEC.com. Todos los derechos reservados.</center></span>
    </div>
</footer>

<!-- Scripts JavaScript -->
<!-- jQuery -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Popper.js (para Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


@yield('scripts')
</body>
</html>



