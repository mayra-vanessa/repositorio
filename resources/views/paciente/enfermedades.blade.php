@extends('layouts.plantilla_general')

@section('title', 'Enfermedades')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-heartbeat"></i> Enfermedades</li>
    </ul>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-5 font-weight-bold">Enfermedades</h1>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Formulario de búsqueda -->
                <form action="{{ route('enfermedades') }}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="search" id="search-input" class="form-control" placeholder="Buscar..." value="{{ $search }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary btn-search">Buscar</button>
                        </div>
                        <!-- Botón para limpiar búsqueda -->
                        @if ($search)
                            <a href="{{ route('enfermedades') }}" class="btn btn-secondary btn-clear-search">Limpiar búsqueda</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div class="row justify-content-center" id="enfermedades-grid">
        <!-- Mostrar todas las tarjetas de enfermedades al cargar la página -->
        @foreach ($enfermedades as $enfermedad)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title font-weight-bold">{{ $enfermedad->nombre }}</h3>
                        <div class="img-container">
                            <img src="{{ $enfermedad->imagen }}" class="card-img-top img-fluid img-enfermedad" alt="{{ $enfermedad->nombre }}">
                        </div>
                        <br>
                        <!-- Botón "Más Detalles" para abrir el modal -->
                        <button type="button" class="btn btn-primary btn-lg btn-mas-detalles mb-3" data-toggle="modal" data-target="#modalDescripcion{{ $enfermedad->idenfermedad }}">Detalles</button>
                        <a href="{{ route('enfermedades.especialistas', $enfermedad->idenfermedad) }}" class="mb-3 btn btn-success btn-lg">Ver Especialistas</a>
                    </div>
                </div>
            </div>

            <!-- Modal para mostrar la descripción de la enfermedad -->
            <div class="modal fade" id="modalDescripcion{{ $enfermedad->idenfermedad }}" tabindex="-1" role="dialog" aria-labelledby="modalDescripcionLabel{{ $enfermedad->idenfermedad }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDescripcionLabel{{ $enfermedad->idenfermedad }}"> {{ $enfermedad->nombre }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{ $enfermedad->descripcion }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
      
        {{ $enfermedades->links('vendor.pagination.bootstrap-4') }} <!-- Enlace de paginación -->
     
        <br>
    </div>
@endsection

@section('styles')
    @parent
    <style>
        .img-enfermedad {
            max-height: 200px;
            object-fit: cover;
        }

        /* Estilo para alinear el botón de búsqueda */
        .input-group-append {
            display: flex;
            align-items: center;
        }

        /* Ajustar márgenes del botón */
        .btn-search {
            margin-left: 10px;
        }

        /* Ajustar márgenes del botón "Limpiar búsqueda" */
        .btn-clear-search {
            margin-left: 5px;
        }

        /* Estilo para el contenedor de la imagen */
        .img-container {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Estilo para centrar verticalmente la imagen */
        .img-enfermedad {
            display: block;
            margin: 0 auto;
            border-radius: 15px;
        }

        /* Estilo para el grupo de botones */
        .btn-group {
            display: flex;
            justify-content: space-between;
        }

        /* Estilo para el contenido del modal */
        .modal-content {
            padding: 20px;
        }

    </style>
@endsection
