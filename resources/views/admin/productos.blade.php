@extends('layouts.plantilla_general')

@section('title', 'Productos')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-medkit"></i> Productos</li>
    </ul>

    @if(session('success'))
    <div id="success-message" class="alert alert-success text-right" style="background-color: #4CAF50; color: white; display: inline-block; float: right;">
        <strong style="font-size: 16px; margin-right: 10px;">{{ session('success') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: red; font-size: 20px;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="container">
        <h1>Productos</h1>
        <br>
        <div class="row">
            <div class="col-md-6">
                <!-- Cuadro de búsqueda -->
                <form action="{{ route('admin.productos') }}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="search" id="search-input" class="form-control" placeholder="Buscar..." value="{{ $search }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary btn-search">Buscar</button>
                        </div>
                        <!-- Botón para limpiar búsqueda -->
                        @if ($search)
                            <a href="{{ route('admin.productos') }}" class="btn btn-secondary btn-clear-search">Limpiar búsqueda</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <!-- Botón para agregar un nuevo especialista -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregar">
                    <i class="fa fa-plus"></i> Agregar Producto
                </button>
            </div>
        </div>
        <br>
        <table id="productos-table" class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Clasificacion</th>
                    <th>Precio</th>
                    <th>Existencias</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->idproducto }}</td>
                    <td>{{ $producto->nombreProducto }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>{{ $producto->clasificacion }}</td>
                    <td>${{ $producto->precioVenta }}</td>
                    <td>{{ $producto->existencias }}</td>
                    <td>
                        <img src="{{ $producto->imagen }}" alt="Imagen del producto" width="100">
                    </td> 
                    <td>
                        <a href="#" class="btn btn-primary btn-modificar"
                            data-id="{{ $producto->idproducto }}"
                            data-nombre="{{ $producto->nombreProducto }}"
                            data-descripcion="{{ $producto->descripcion }}"
                            data-clasificacion="{{ $producto->clasificacion }}"
                            data-precioVenta="{{ $producto->precioVenta }}"
                            data-existencias="{{ $producto->existencias }}"
                            data-imagen="{{ $producto->imagen }}">
                            <i class="fa fa-pencil"></i> Modificar
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $productos->links('vendor.pagination.bootstrap-4') }} <!-- Enlace de paginación -->
        <br>
    </div>

   <!-- Modal de Producto (Agregar) -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAgregar" action="{{ route('productos.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombreAgregar">Nombre</label>
                            <input type="text" name="nombreProducto" id="nombreAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcionAgregar">Descripción</label>
                            <input type="text" name="descripcion" id="descripcionAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="clasificacionAgregar">Clasificación</label>
                            <input type="text" name="clasificacion" id="clasificacionAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="precioVentaAgregar">Precio</label>
                            <input type="number" name="precioVenta" id="precioVentaAgregar" class="form-control" step="any" required>
                        </div>
                        <div class="form-group">
                            <label for="existenciasAgregar">Existencias</label>
                            <input type="number" name="existencias" id="existenciasAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="imagenAgregar">Imagen URL</label>
                            <input type="text" name="imagen" id="imagenAgregar" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" form="formAgregar" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de Producto (Modificar) -->
    <div class="modal fade" id="modalModificar" tabindex="-1" role="dialog" aria-labelledby="modalModificarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModificarLabel">Modificar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formModificar" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="idproducto" id="idproductoModificar" value="">
                        <div class="form-group">
                            <label for="nombreModificar">Nombre</label>
                            <input type="text" name="nombreProducto" id="nombreModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcionModificar">Descripción</label>
                            <input type="text" name="descripcion" id="descripcionModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="clasificacionModificar">Clasificación</label>
                            <input type="text" name="clasificacion" id="clasificacionModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="precioVentaModificar">Precio</label>
                            <input type="number" name="precioVenta" id="precioVentaModificar" class="form-control" step="any" required>
                        </div>
                        <div class="form-group">
                            <label for="existenciasModificar">Existencias</label>
                            <input type="number" name="existencias" id="existenciasModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="imagenModificar">Imagen URL</label>
                            <input type="text" name="imagen" id="imagenModificar" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" form="formModificar" class="btn btn-primary">Guardar cambios</button>
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
                var modal = $(this);
                modal.find('.modal-title').text('Agregar Producto');
                modal.find('#nombreAgregar').val('');
                modal.find('#descripcionAgregar').val('');
                modal.find('#clasificacionAgregar').val('');
                modal.find('#precioVentaAgregar').val('');
                modal.find('#existenciasAgregar').val('');
                modal.find('#imagenAgregar').val('');
                modal.find('form').attr('action', '{{ route('productos.store') }}');
            });

            // Abrir el modal de Modificar y cargar los datos
            $('#productos-table').on('click', '.btn-modificar', function() {
                var idproducto = $(this).data('id');
                var nombre = $(this).data('nombre');
                var descripcion = $(this).data('descripcion');
                var clasificacion = $(this).data('clasificacion');
                var precioVenta = $(this).data('precioventa');
                var existencias = $(this).data('existencias');
                var imagen = $(this).data('imagen');

                var modal = $('#modalModificar');
                modal.find('.modal-title').text('Modificar Producto');
                modal.find('#idproductoModificar').val(idproducto);
                modal.find('#nombreModificar').val(nombre);
                modal.find('#descripcionModificar').val(descripcion);
                modal.find('#clasificacionModificar').val(clasificacion);
                modal.find('#precioVentaModificar').val(precioVenta);
                modal.find('#existenciasModificar').val(existencias);
                modal.find('#imagenModificar').val(imagen);
                modal.find('form').attr('action', '{{ route('productos.update', ['idproducto' => '__idproducto__']) }}'.replace('__idproducto__', idproducto));
                modal.modal('show');
            });

            // Cerrar el mensaje de éxito después de 5 segundos (5000 ms)
            $(document).ready(function() {
                setTimeout(function() {
                    $('#success-message').fadeOut('slow');
                }, 3000); // 5000 ms = 5 segundos
            });

            // Opcional: Permitir al usuario cerrar el mensaje haciendo clic en el botón "Cerrar"
            $(document).on('click', '[data-dismiss="alert"]', function() {
                $(this).closest('.alert').fadeOut('slow');
            });
        });
    </script>
@endsection
