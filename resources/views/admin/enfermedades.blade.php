@extends('layouts.plantilla_general')

@section('title', 'Enfermedades')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-heartbeat"></i> Enfermedades</li>
    </ul>

    @if(session('success'))
        <div id="success-message" class="alert alert-success text-right" style="background-color: #4CAF50; color: white; display: inline-block; float: right;">
            <strong style="font-size: 16px; margin-right: 10px;">{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-5 font-weight-bold">Enfermedades</h1>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <!-- Cuadro de búsqueda -->
                <form action="{{ route('admin.enfermedades') }}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="search" id="search-input" class="form-control" placeholder="Buscar..." value="{{ $search }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary btn-search">Buscar</button>
                        </div>
                        <!-- Botón para limpiar búsqueda -->
                        @if ($search)
                            <a href="{{ route('admin.enfermedades') }}" class="btn btn-secondary btn-clear-search">Limpiar búsqueda</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <!-- Botón para agregar una nueva enfermedad -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregar">
                    <i class="fa fa-plus"></i> Agregar Enfermedad
                </button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="enfermedades-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Imagen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enfermedades as $enfermedad)
                                <tr>
                                    <td>{{ $enfermedad->idenfermedad }}</td>
                                    <td>{{ $enfermedad->nombre }}</td>
                                    <td>{{ $enfermedad->descripcion }}</td>
                                    <td>
                                        <img src="{{ $enfermedad->imagen }}" alt="{{ $enfermedad->nombre }}" width="100">
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-modificar"
                                            data-id="{{ $enfermedad->idenfermedad }}"
                                            data-nombre="{{ $enfermedad->nombre }}"
                                            data-descripcion="{{ $enfermedad->descripcion }}"
                                            data-tratamiento="{{ $enfermedad->tratamiento }}"
                                            data-imagen="{{ $enfermedad->imagen }}">
                                            <i class="fa fa-pencil"></i> Modificar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{ $enfermedades->links('vendor.pagination.bootstrap-4') }}<!-- Enlace de paginación -->
        <br>
    </div>

   <!-- Modal de Enfermedad (Agregar) -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAgregarLabel">Agregar Enfermedad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="FormAgregar" action="{{ route('enfermedades.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombreAgregar">Nombre</label>
                            <input type="text" name="nombre" id="nombreAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcionAgregar">Descripción</label>
                            <textarea name="descripcion" id="descripcionAgregar" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="imagenAgregar">Imagen URL</label>
                            <input type="text" name="imagen" id="imagenAgregar" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" form="FormAgregar" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Enfermedad (Modificar) -->
    <div class="modal fade" id="ModalModificar" tabindex="-1" role="dialog" aria-labelledby="ModalModificarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalModificarLabel">Modificar Enfermedad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="FormModificar" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="idenfermedad" id="idenfermedadModificar" value="">
                        <div class="form-group">
                            <label for="nombreModificar">Nombre</label>
                            <input type="text" name="nombre" id="nombreModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcionModificar">Descripción</label>
                            <textarea name="descripcion" id="descripcionModificar" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="imagenModificar">Imagen URL</label>
                            <input type="text" name="imagen" id="imagenModificar" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" form="FormModificar" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </div>
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
    </style>
@endsection


@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            // Escuchar el evento de cambio en el campo de búsqueda
            $('#search-input').on('input', function() {
                var searchText = $(this).val().toLowerCase();

                // Filtrar las filas de la tabla según el texto de búsqueda
                $('#enfermedades .card-body').each(function() {
                    var rowText = $(this).text().toLowerCase();

                    if (rowText.includes(searchText)) {
                        $(this).closest('.col-md-4').show();
                    } else {
                        $(this).closest('.col-md-4').hide();
                    }
                });
            });

            // Abrir el modal de Agregar
            $('#modalAgregar').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var modal = $(this);
                modal.find('.modal-title').text('Agregar Enfermedad');
                modal.find('#nombreAgregar').val('');
                modal.find('#descripcionAgregar').val('');
                modal.find('#imagenAgregar').val('');
                modal.find('form').attr('action', '{{ route('enfermedades.store') }}');
            });

            // Abrir el modal de Modificar y cargar los datos
            $('#enfermedades-table').on('click', '.btn-modificar', function() {
                var idenfermedad = $(this).data('id');
                var nombre = $(this).data('nombre');
                var descripcion = $(this).data('descripcion');
                var imagen = $(this).data('imagen');

                var modal = $('#ModalModificar');
                modal.find('.modal-title').text('Modificar Enfermedad');
                modal.find('#idenfermedadModificar').val(idenfermedad);
                modal.find('#nombreModificar').val(nombre);
                modal.find('#descripcionModificar').val(descripcion);
                modal.find('#imagenModificar').val(imagen);
                modal.find('form').attr('action', '{{ route('enfermedades.update', ['idenfermedad' => '__idenfermedad__']) }}'.replace('__idenfermedad__', idenfermedad));
                modal.modal('show');
            });

            // Cerrar el mensaje de éxito después de 5 segundos (5000 ms)
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 1500); // 5000 ms = 5 segundos

            // Opcional: Permitir al usuario cerrar el mensaje haciendo clic en el botón "Cerrar"
            $(document).on('click', '[data-dismiss="alert"]', function() {
                $(this).closest('.alert').fadeOut('slow');
            });

            

        });
    </script>
@endsection
