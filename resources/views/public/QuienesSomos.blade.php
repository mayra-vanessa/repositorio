@extends('layouts.plantilla_general')

@section('title', 'Quienes Somos')

@section('content')
<ul class="breadcrumb">
  <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
  <li><i class="fa fa-pencil-square-o"></i> Quienes Somos</li>
</ul>

<div class="container">
    <div class="row">
      <div class="col-md-8 mx-auto"> <!-- Cambiamos col-md-8 col-md-offset-2 por col-md-8 mx-auto -->
        <div class="text-center">
          <img src="img/logo.png" alt="" class="img-fluid"> <!-- Añadimos la clase "img-fluid" para hacer la imagen responsive -->
          <h2>QUIENES SOMOS</h2>
          <p>Somos un equipo comprometido con brindar información relevante y actualizada sobre enfermedades crónicas. Nuestro objetivo es proporcionar recursos y apoyo a las personas que viven con estas condiciones y a sus familias.</p>
          <p>Estamos dedicados a promover la conciencia y el conocimiento sobre las enfermedades crónicas, así como a fomentar la prevención y el cuidado adecuado. Nuestro equipo de expertos en salud trabaja arduamente para recopilar y ofrecer información confiable y de calidad.</p>
          <p>En nuestro sitio web, encontrarás una amplia gama de artículos, noticias, consejos y recursos relacionados con diversas enfermedades crónicas, como diabetes, Parkinson, Alzheimer, esclerosis múltiple, entre otras.</p>
          <p>Nuestro objetivo principal es empoderar a las personas con información y apoyo para que puedan llevar una vida saludable y mejorar su calidad de vida a pesar de las enfermedades crónicas.</p>
          <p>¡Gracias por visitarnos y esperamos que encuentres nuestra plataforma útil y enriquecedora!</p>
        </div>
        <hr>
      </div>
    </div>
</div>
@endsection
