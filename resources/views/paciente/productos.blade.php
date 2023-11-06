@extends('layouts.plantilla_general')

@section('title', 'Medicamentos')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-medkit"></i> Medicamentos</li>
    </ul>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-5 font-weight-bold">Medicamentos</h1>
            </div>
        </div>
        <br>
        <div class="row justify-content-center" id="medicamentos-grid">
            <!-- Mostrar todas las tarjetas de medicamentos al cargar la página -->
            @foreach ($productos as $producto)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h3 class="card-title font-weight-bold">{{ $producto->nombreProducto }}</h3>
                            <div class="img-container">
                                <img src="{{ $producto->imagen }}" class="card-img-top img-fluid img-medicamento rounded" alt="{{ $producto->nombre }}">
                            </div>
                            <p class="font-weight-bold text-success" style="font-size: 22px;">${{ number_format($producto->precioVenta, 2) }}</p>
                            
                            <!-- Cuadro de cantidad y botón "Agregar al carrito" -->
                            <div class="input-group mt-3">
                                <input type="number" name="cantidad{{ $producto->idproducto }}" id="cantidad{{ $producto->idproducto }}" min="1" value="1" class="form-control cantidad-item" style="width: 40%; font-size: 16px;">
                                <div class="input-group-append" style="width: 60%;">
                                    <a href="#" class="btn btn-success btn-lg btn-agregar-carrito" data-producto-id="{{ $producto->idproducto }}">Agregar al carrito</a>
                                </div>
                            </div>

                            <!-- Botón "Más Detalles" para abrir el modal -->
                            <button type="button" class="btn btn-primary btn-lg btn-mas-detalles mt-3" data-toggle="modal" data-target="#modalDescripcion{{ $producto->idproducto }}">Detalles</button>

                        </div>
                    </div>
                </div>


                <!-- Modal para mostrar la descripción del medicamento -->
                <div class="modal fade" id="modalDescripcion{{ $producto->idproducto }}" tabindex="-1" role="dialog" aria-labelledby="modalDescripcionLabel{{ $producto->idproducto }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalDescripcionLabel{{ $producto->idproducto }}"> {{ $producto->nombreProducto }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {{ $producto->descripcion }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $productos->links('vendor.pagination.bootstrap-4') }} <!-- Enlace de paginación -->
        <br>
    </div>
@endsection

@section('styles')
    @parent
    <style>
        .img-medicamento {
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
        .img-medicamento {
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

        /* Estilo para el botón "Más Detalles" en cada tarjeta de medicamento */
        .btn-mas-detalles {
            margin-top: 10px;
            width: 70%;
        }

        /* Estilo para el botón "Agregar al carrito" en cada tarjeta de medicamento */
        .btn-agregar-carrito {
            margin-top: 10px;
            margin-left:10px;
        }

        /* Estilo para el botón "Agregar al carrito" en cada tarjeta de medicamento */
        .cantidad-item {
            margin-top: 15px;
        }

    </style>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            // Manejar clic en el botón "Agregar al carrito"
            $('.btn-agregar-carrito').click(function(e) {
                e.preventDefault();

                // Obtener el ID del producto desde el atributo de datos
                var productoId = $(this).data('producto-id');

                // Realizar la solicitud AJAX para agregar el producto al carrito
                $.ajax({
                    type: 'POST',
                    url: '{{ route("carrito.agregar") }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        producto_id: productoId
                    },
                    success: function(response) {
                        // Mostrar mensaje de éxito (puedes agregar una notificación más elegante aquí)
                        alert(response.message);
                    },
                    error: function(error) {
                        // Mostrar mensaje de error si la solicitud falla
                        alert('Error al agregar el producto al carrito');
                    }
                });
            });
        });
    </script>
@endsection
