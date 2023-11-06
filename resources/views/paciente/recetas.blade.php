@extends('layouts.plantilla_general')

@section('title', 'Receta')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><a href="{{ route('paciente.consultas') }}" class="breadcrumb-link"><i class="fa fa-heartbeat"></i> Consultas</a></li>
        <li><i class="fa fa-medkit"></i> Receta</li>
    </ul>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-5 font-weight-bold">Receta</h1>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Especialista:</strong> {{ $consulta->especialista->nombre_completo }}</p>
                <p><strong>Enfermedad Atendida:</strong> {{ $consulta->enfermedad->nombre }}</p>
                <p><strong>Descripción de la Consulta:</strong> {{ $consulta->descripcion }}</p>
            </div>
            <div class="col-md-6 text-right">
                <p><strong>Fecha de consulta:</strong> {{ \Carbon\Carbon::parse($consulta->fecha_consulta)->format('d-m-Y') }}</p>
                <p><strong>Hora de consulta:</strong> {{ \Carbon\Carbon::parse($consulta->hora_consulta)->format('h:i A') }}</p>
                <p><strong>Estatus de la Consulta:</strong>
                    @if ($consulta->estatus === 0)
                        Pendiente
                    @elseif ($consulta->estatus === 1)
                        Finalizada
                    @endif
                </p>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th>Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalReceta = 0;
                            @endphp
                            @foreach ($receta->detalles as $detalle)
                                @php
                                    $subtotal = $detalle->cantidad * $detalle->precio;
                                    $totalReceta += $subtotal;
                                @endphp
                                <tr>
                                    <td>{{ $detalle->producto->nombreProducto }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>${{ $detalle->precio }}</td>
                                    <td>${{ $subtotal }}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="producto-checkbox" data-subtotal="{{ $subtotal }}" data-idproducto="{{ $detalle->producto->idproducto }}" name="productosSeleccionados[{{ $detalle->producto->idproducto }}]" value="1" checked> <!-- Añadimos checked aquí -->
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 text-right"> <!-- Ajustamos a 12 columnas y colocamos el contenido a la derecha -->
                <p style="font-size: 20px; margin-bottom: 0;"><strong>Total: <span id="totalReceta">{{ number_format($totalReceta, 2) }}</span></strong></p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 text-right"> <!-- Ajustamos a 12 columnas y colocamos el contenido a la derecha -->
                <form id="pedidoForm" action="{{ route('pedido.guardar', ['idconsulta' => $consulta->idconsulta]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="productosSeleccionados" id="productosSeleccionados" value="{{ implode(',', $receta->detalles->pluck('producto.idproducto')->toArray()) }}"> <!-- Añadimos los IDs de los productos seleccionados -->

                    <button type="submit" class="btn btn-primary">Realizar Pedido</button>
                </form>
            </div>
        </div>
        <br><br><br> <!-- Agregar espacios después del contenido -->
    </div>
@endsection

@section('styles')
    @parent
    <style>
        /* Estilo del switch en color verde */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        /* Cambio del color del switch a verde */
        input:checked + .slider {
            background-color: #4CAF50;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #4CAF50;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Ajuste del total hacia la derecha */
        #totalReceta {
            float: right;
        }

        /* Ajuste del botón "Realizar Pedido" cuando el total es 0 */
        #pedidoForm[disabled] button {
            pointer-events: none;
            opacity: 0.6;
        }

        /* Ajuste del total y el botón */
        .total-btn-container {
            display: flex;
            align-items: center;
        }

        .total-container {
            margin-right: 10px;
        }
    </style>
@endsection

<!-- Deja únicamente el script aquí, sin duplicados -->
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            // Actualizar total al seleccionar o deseleccionar un producto
            var productosSeleccionados = [];

            function actualizarEstadoBoton() {
                var totalReceta = 0;

                $('.producto-checkbox:checked').each(function() {
                    totalReceta += parseFloat($(this).data('subtotal'));
                    var idProducto = $(this).data('idproducto');
                    if (!productosSeleccionados.includes(idProducto)) {
                        productosSeleccionados.push(idProducto);
                    }
                });

                $('.producto-checkbox:not(:checked)').each(function() {
                    var idProducto = $(this).data('idproducto');
                    if (productosSeleccionados.includes(idProducto)) {
                        var index = productosSeleccionados.indexOf(idProducto);
                        if (index !== -1) {
                            productosSeleccionados.splice(index, 1);
                        }
                    }
                });

                // Mostrar en el console.log los productos seleccionados
                console.log('Productos seleccionados:', productosSeleccionados);

                $('#totalReceta').text('$' + totalReceta.toFixed(2));
                console.log("Total actualizado: $" + totalReceta.toFixed(2));

                // Bloquear el botón "Realizar Pedido" cuando el total es 0
                var pedidoForm = $('#pedidoForm');
                if (totalReceta > 0) {
                    pedidoForm.removeAttr('disabled');
                } else {
                    pedidoForm.attr('disabled', 'disabled');
                }
            }

            $('.producto-checkbox').change(function() {
                actualizarEstadoBoton();

                // Actualizar el campo oculto con la lista de productos seleccionados
                $('#productosSeleccionados').val(productosSeleccionados.join(','));
            });

            // Restaurar la lista de productos seleccionados al cargar la página
            $('.producto-checkbox:checked').each(function() {
                var idProducto = $(this).data('idproducto');
                if (!productosSeleccionados.includes(idProducto)) {
                    productosSeleccionados.push(idProducto);
                }
            });

            // Cerrar el mensaje de éxito después de 5 segundos (5000 ms)
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 1500); // 5000 ms = 5 segundos

            // Opcional: Permitir al usuario cerrar el mensaje haciendo clic en el botón "Cerrar"
            $(document).on('click', '[data-dismiss="alert"]', function() {
                $(this).closest('.alert').fadeOut('slow');
            });

            // Ejecutar actualización del total al cargar la página
            actualizarEstadoBoton();
        });
    </script>
@endsection