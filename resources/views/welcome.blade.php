@extends('layouts.plantilla_general')

@section('title', 'Inicio')

@section('content')

@if(session('success'))
<div id="success-message" class="alert alert-success text-right" style="background-color: #4CAF50; color: white; display: inline-block; float: right;">
    <strong style="font-size: 16px; margin-right: 10px;">{{ session('success') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!--CARRUSEL-->
<div id="myCarouselCustom" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#myCarouselCustom" data-slide-to="0" class="active"></li>
        <li data-target="#myCarouselCustom" data-slide-to="1"></li>
        <li data-target="#myCarouselCustom" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('img/doc1.jpg') }}" alt="" class="img-fluid">
            <div class="carousel-caption">
                <h3 class="carousel-title">Repositorio Virtual de Enfermedades Crónicas</h3>
                <p class="carousel-description">No tienes que vivir con la diabetes, sino vivir con la diabetes</p>
            </div>
        </div>

        <div class="carousel-item">
            <img src="{{ asset('img/hospital1.jpg') }}" alt="" class="img-fluid">
            <div class="carousel-caption">
                <h3 class="carousel-title">Repositorio Virtual de Enfermedades Crónicas</h3>
                <p class="carousel-description">Una actitud positiva te da poder sobre tus circunstancias en lugar de que tus circunstancias tengan poder sobre ti</p>
            </div>
        </div>

        <div class="carousel-item">
            <img src="{{ asset('img/hospital2.jpg') }}" alt="" class="img-fluid">
            <div class="carousel-caption">
                <h3 class="carousel-title">Repositorio Virtual de Enfermedades Crónicas</h3>
                <p class="carousel-description">La curación requiere coraje, y todos tenemos coraje, incluso si tenemos que cavar un poco para encontrarlo</p>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <a class="carousel-control-prev" href="#myCarouselCustom" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next" href="#myCarouselCustom" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>

<section class="product-category section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="title text-center">
                    <h2>Enfermedades Crónicas</h2>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="category-box category-box-1">
                    <a href="{{ route('Informacion') }}">
                        <img src="https://www.farmaciagt.com/blog/wp-content/uploads/2021/10/istockphoto-953795236-612x612-1.jpg" alt="" class="img-fluid">
                        <div class="content" style="background-color: rgba(255, 255, 255, 0.3);">
                            <h3>Diabetes</h3>
                            <p>No tienes que vivir para la diabetes, sino vivir con la diabetes</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-12 col-sm-12">
                <div class="category-box category-box-1">
                    <a href="{{ route('Informacion') }}">
                        <img src="https://clinicalasmonjas.com/wp-content/uploads/2020/12/LA-EPILEPSIA-EN-EL-DESARROLLO-DEL-NINO.jpg" alt="" class="img-fluid">
                        <div class="content" style="background-color: rgba(255, 255, 255, 0.3);">
                            <h3>Epilepsia</h3>
                            <p>Se caracteriza por convulsiones recurrentes no provocadas.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-12 col-sm-12">
                <div class="category-box category-box-1">
                    <a href="{{ route('Informacion') }}">
                        <img src="https://img.freepik.com/foto-gratis/3d-rendering-of-people-looking-at-human-brain_23-2150723102.jpg?t=st=1696096620~exp=1696097220~hmac=bf607d8550e06034cf0bdc856ffa64d4c02a72a4fadd19cd09182f32e7f016d2" alt="" class="img-fluid">
                        <div class="content" style="background-color: rgba(255, 255, 255, 0.3);">
                            <h3>Alzheimer</h3>
                            <p>Del Alzheimer he aprendido a dejar la razón de lado y comunicarme con la emoción</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- COOKIES-->

<div class="wrapper cookies-message" style="background-color: rgba(255, 255, 255, 1);"> <!-- Agrega la clase "cookies-message" aquí -->
    <img src="img/cookie.png" alt="">
    <div class="content">
        <header>Cookies</header>
        <p>Utilizamos cookies propias y de terceros para mejorar nuestros servicios.</p>
        <div class="buttons">
            <button class="item">De acuerdo</button>
            <a href="{{ route('Cockies') }}" class="item">Leer más...</a>
        </div>
    </div>
</div>


@endsection

@section('styles')
@parent
<style>
    @media (max-width: 768px) {
        .carousel-title {
            font-size: 17px; /* Ajusta el tamaño de fuente para dispositivos móviles */
        }
        .carousel-description {
            font-size: 13px; /* Ajusta el tamaño de fuente para dispositivos móviles */
        }
    }
</style>
@endsection

@section('scripts')
@parent

<script>
    // Recuperar los datos pasados desde el controlador
    var userData = JSON.parse(@json(session('userData')));

    // Verificar si se recuperaron los datos correctamente
    console.log("Local Storage",userData);

    if (userData) {
        // Ahora puedes acceder a los datos como userData.idusuario, userData.email, userData.tipo_usuario, etc.
        console.log(userData.idusuario);
        console.log(userData.email);
        console.log(userData.tipo_usuario);
        // Agrega más datos según tus necesidades

        // Guardar los datos en localStorage
        localStorage.setItem('userData', JSON.stringify(userData));
    }
</script>
<!-- Bootstrap -->
<script>

    $(document).ready(function() {
        // Cuando se hace clic en el botón "De acuerdo"
        $('.cookies-message button.item').on('click', function() {
            // Ocultar el mensaje de cookies
            $('.cookies-message').hide();
        });
    });

    // Cerrar el mensaje de éxito después de 5 segundos (5000 ms)
    $(document).ready(function() {
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 1500); // 5000 ms = 5 segundos
    });

    // Opcional: Permitir al usuario cerrar el mensaje haciendo clic en el botón "Cerrar"
    $(document).on('click', '[data-dismiss="alert"]', function() {
        $(this).closest('.alert').fadeOut('slow');
    });
</script>


@endsection
