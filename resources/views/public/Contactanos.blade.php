@extends('layouts.plantilla_general')

@section('title', 'Contactanos')

@section('content')
<ul class="breadcrumb">
  <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
  <li><i class="fa fa-pencil-square-o"></i> Contactanos</li>
</ul>

<div class="container">
    <div class="row">
      <div class="col-md-8 mx-auto"> <!-- Cambiamos col-md-6 col-md-offset-3 por col-md-8 mx-auto -->
        <div class="text-center">
          <h2>ESCRIBENOS UN MENSAJE</h2>
          <p>DESCRIBE TU PROBLEMA BREVEMENTE <br>ESTE CORREO SERA ENVIADO A <br>revec@gmail.com</p>
        </div>
        <hr>
      </div>
    </div>
  </div>

<section id="contact-page">
    <div class="container">
      <div class="row contact-wrap">
        <div class="col-md-10 mx-auto"> <!-- Cambiamos col-md-8 col-md-offset-2 por col-md-10 mx-auto -->
          <div id="sendmessage">Your message has been sent. Thank you!</div>
          <div id="errormessage"></div>
          <form action="/enviar.php" method="POST" role="form" class="contactForm">
            <!-- Cambiamos el method de GET a POST -->

            <div class="form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder=" Nombre" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="email" class="form-control" name="email" id="email" placeholder="Correo " data-rule="email" data-msg="Please enter a valid email" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Asunto" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Mensaje"></textarea>
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="checkbox" required> Acepto la política de privacidad.
              <div class="validation"></div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary btn-lg">Enviar Correo</button>
            </div>
          </form>
        </div>
      </div>
      <!--/.row-->
    </div>
    <!--/.container-->
  </section>
  <br>
  <br>
  <br>
@endsection
