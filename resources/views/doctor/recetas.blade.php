@extends('layouts.plantilla_general')

@section('title', 'Crear Receta')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><a href="{{ route('doctor.consultas') }}" class="breadcrumb-link"><i class="fa fa-heartbeat"></i> Consultas</a></li>
        <li><i class="fa fa-medkit"></i> Recetas</li>
    </ul>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-5 font-weight-bold">Crear Receta</h1>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <!-- Titulo de productos disponibles -->
                <h3 style="margin-top: 20px;">Productos Disponibles</h3>
                <br>
                <!-- Cuadro de búsqueda -->
                <div class="input-group mb-3">
                    <input type="text" id="search-input" class="form-control form-control-lg" placeholder="Buscar...">
                </div>

                <!-- Tabla de productos con scroll -->
                <div style="overflow-y: auto; max-height: 600px;">
                    <table class="table table-bordered table-productos">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Existencias</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="productos-tabla">
                            <!-- Recorre la lista de productos -->
                            @foreach ($productos as $producto)
                                <tr>
                                    <td>{{ $producto->nombreProducto }}</td>
                                    <td>${{ $producto->precioVenta }}</td>
                                    <td>{{ $producto->existencias }}</td>
                                    <td>
                                        <!-- Aquí se muestra el cuadro para agregar cantidad -->
                                        <input type="number" class="form-control cantidad-producto" name="cantidad" value="1" min="1" max="{{ $producto->existencias }}">
                                        <button class="btn btn-primary btn-agregar" data-id="{{ $producto->idproducto }}">Agregar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                </div>
                <br>
                <br>
            </div>
            <div class="col-md-4">
                <!-- Lista de productos seleccionados -->
                <h3>Productos Seleccionados</h3>
                <br>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>SubTotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista-productos">
                        <!-- Aquí se mostrarán los productos seleccionados -->
                    </tbody>
                </table>
                <!-- Total de la receta -->
                <h4>Total: $<span id="total-receta">0</span></h4>
                <!-- Botón para finalizar la receta -->
                 <!-- Botón para finalizar la receta -->
                <button class="btn btn-success btn-finalizar">Finalizar Consulta</button>

            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent
    <style>
        /* Ajustar márgenes del botón */
        .btn-search {
            margin-left: 10px;
        }

        /* Ajustar margen del título "Productos Disponibles" */
        h3 {
            margin-bottom: 0;
        }

        /* Ajustar ancho del cuadro de búsqueda */
        #search-input {
            width: calc(100% - 30px); /* Modificar el valor para ajustar el tamaño */
        }

        /* Estilo para resaltar el producto seleccionado en la tabla */
        .producto-seleccionado {
            background-color: #f2f2f2;
        }

        /* Estilo para resaltar el producto seleccionado en la lista */
        .producto-lista-seleccionado {
            background-color: #d9edf7;
        }
    </style>
@endsection

@section('scripts')
    @parent
    
    <script>
        // Declarar la variable productosSeleccionados fuera del evento $(document).ready()
        var productosSeleccionados = [];
        var idConsulta = {{ $idconsulta }}; // Obtener el valor de idconsulta desde la vista
        
        $(document).ready(function() {

            // Función para calcular el total de la receta
            function calcularTotalReceta() {
                var totalReceta = 0;
                $('#lista-productos tr').each(function () {
                    var precio = parseFloat($(this).find('.precio-producto').text().substring(1));
                    var cantidad = parseInt($(this).find('.cantidad-producto').val());
                    var subtotal = precio * cantidad;
                    totalReceta += subtotal;
                });
                $('#total-receta').text(totalReceta.toFixed(2));
            }

            // Manejar clic en el botón "Agregar"
            $(document).on('click', '.btn-agregar', function () {
                var productoId = $(this).data('id');
                var productoNombre = $(this).closest('tr').find('td:first').text();
                var productoPrecio = $(this).closest('tr').find('td:eq(1)').text();
                var productoExistencias = $(this).closest('tr').find('td:eq(2)').text();
                var cantidad = $(this).closest('td').find('input[name="cantidad"]').val();

                // Verificar si el producto ya está en la lista
                var productoEnLista = false;
                $('#lista-productos tr').each(function () {
                    var productoIdLista = $(this).find('.btn-quitar').data('id');
                    if (productoId === productoIdLista) {
                        productoEnLista = true;
                        return false;
                    }
                });

                if (!productoEnLista) {
                    // Agregar el producto a la lista de productos seleccionados
                    var producto = {
                        id: productoId,
                        nombre: productoNombre,
                        precio: parseFloat(productoPrecio.substring(1)),
                        cantidad: cantidad
                    };
                    productosSeleccionados.push(producto);
                    console.log(productosSeleccionados);
                    // Agregar el producto a la lista visual
                    var fila = '<tr class="producto-lista-seleccionado">' +
                        '<td>' + productoNombre + '</td>' +
                        '<td class="precio-producto">' + productoPrecio + '</td>' +
                        '<td><input type="number" class="form-control cantidad-producto" value="' + cantidad + '" min="1" max="' + productoExistencias + '"></td>' +
                        '<td class="subtotal-producto">' + (parseFloat(productoPrecio.substring(1)) * parseInt(cantidad)).toFixed(2) + '</td>' +
                        '<td><button class="btn btn-danger btn-quitar" data-id="' + productoId + '">Quitar</button></td>' +
                        '</tr>';
                    $('#lista-productos').append(fila);

                    // Marcar el producto seleccionado en la tabla de productos
                    $('#productos-tabla tr').each(function () {
                        var productoIdTabla = $(this).find('.btn-agregar').data('id');
                        if (productoId === productoIdTabla) {
                            $(this).addClass('producto-seleccionado');
                            $(this).find('.btn-agregar').prop('disabled', true);
                            return false;
                        }
                    });

                    // Habilitar el botón "Finalizar Consulta" si hay productos seleccionados
                    $('.btn-finalizar').prop('disabled', productosSeleccionados.length === 0);

                    // Calcular el total de la receta
                    calcularTotalReceta();
                }
            });

            // Manejar clic en el botón "Quitar"
            $(document).on('click', '.btn-quitar', function () {
                var productoId = $(this).data('id');
                // Quitar el producto de la lista de productos seleccionados
                for (var i = 0; i < productosSeleccionados.length; i++) {
                    if (productosSeleccionados[i].id === productoId) {
                        productosSeleccionados.splice(i, 1);
                        break;
                    }
                }
                // Quitar la selección del producto en la tabla de productos
                $('#productos-tabla tr').each(function () {
                    var productoIdTabla = $(this).find('.btn-agregar').data('id');
                    if (productoId === productoIdTabla) {
                        $(this).removeClass('producto-seleccionado');
                        $(this).find('.btn-agregar').prop('disabled', false);
                        return false;
                    }
                });
                $('.btn-finalizar').prop('disabled', productosSeleccionados.length === 0);
                console.log(productosSeleccionados);

                // Quitar el producto de la lista visual
                $(this).closest('tr').remove();

                // Calcular el total de la receta
                calcularTotalReceta();
            });

            // Manejar cambio en el campo de cantidad
            $(document).on('change', '.cantidad-producto', function () {
                var cantidad = parseInt($(this).val());
                var precio = parseFloat($(this).closest('tr').find('.precio-producto').text().substring(1));
                var subtotal = (precio * cantidad).toFixed(2);
                $(this).closest('tr').find('.subtotal-producto').text(subtotal);

                // Actualizar la cantidad en la lista de productos seleccionados
                var productoId = $(this).closest('tr').find('.btn-quitar').data('id');
                for (var i = 0; i < productosSeleccionados.length; i++) {
                    if (productosSeleccionados[i].id === productoId) {
                        productosSeleccionados[i].cantidad = cantidad;
                        break;
                    }
                }

                // Calcular el total de la receta
                calcularTotalReceta();
            });

            // Manejar cambio en el campo de búsqueda
            $('#search-input').on('input', function () {
                var searchText = $(this).val().toLowerCase();

                // Filtrar las filas de la tabla de productos según el texto de búsqueda
                $('#productos-tabla tr').each(function () {
                    var rowText = $(this).text().toLowerCase();

                    if (rowText.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('.btn-finalizar').click(function () {
                console.log(idConsulta);
                console.log(productosSeleccionados);
                

                if (productosSeleccionados.length === 0) {
                    alert('No hay productos seleccionados para finalizar la consulta.');
                    return;
                }
                /*
                // Obtener el valor de idConsulta
                var idConsulta = 123; // Reemplaza "123" con el valor real del ID de la consulta seleccionada
                */
                // Envío de datos mediante una solicitud POST para depuración
                $.post('{{ route("consulta.finalizar", ["idconsulta" => ":idconsulta"]) }}'.replace(':idconsulta', idConsulta), {
                    _token: '{{ csrf_token() }}',
                    productosSeleccionados: productosSeleccionados
                })
                .done(function (response) {
                    // Mostrar en la consola del navegador los datos recibidos en el servidor
                   
                    // Redireccionar a la página de consultas después de mostrar la alerta
                    window.location.href = '{{ route("doctor.consultas") }}';
                })
                .fail(function () {
                    alert('Error al finalizar la consulta.');
                });
            });


        });
    </script>
@endsection
