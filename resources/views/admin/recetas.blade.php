@extends('layouts.plantilla_general')

@section('title', 'Pedidos de recetas')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-truck"></i> Pedidos</li>
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
        <h1 class="text-center">Pedidos de recetas</h1>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha de consulta</th>
                        <th>Especialista</th>
                        <th>Enfermedad Atendida</th>
                        <th>Paciente</th>
                        <th>Teléfono Paciente</th>
                        <th>Total</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recetas as $receta)
                        <tr>
                            <td>{{ $receta->idreceta }}</td>
                            <td>{{ \Carbon\Carbon::parse($receta->consulta->fecha_consulta)->format('d-m-Y') }}</td>
                            <td>{{ $receta->consulta->especialista->nombre_completo }}</td>
                            <td>{{ $receta->consulta->enfermedad->nombre }}</td>
                            <td>{{ $receta->consulta->paciente->nombre_completo }}</td>
                            <td>{{ $receta->consulta->paciente->telefono }}</td>
                            <td>${{ number_format($receta->total, 2) }}</td>
                            <td style="background-color: @if($receta->estatus === 1) #A2FF86 @elseif($receta->estatus === 2) #1E90FF @endif;">
                                @if($receta->estatus === 1) Finalizada @elseif($receta->estatus === 2) En Tránsito @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDetalles{{ $receta->idreceta }}" >
                                    Ver Pedido
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($recetas as $receta)
    <!-- Modal para detalles de receta -->
    <div class="modal fade" id="modalDetalles{{ $receta->idreceta }}" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Receta #{{ $receta->idreceta }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí puedes mostrar los detalles de la receta, por ejemplo, los productos y su cantidad -->
                    <ul>
                        @foreach($productosEnPedido as $producto)
                            @if($producto->idreceta === $receta->idreceta)
                                <li>{{ $producto->producto->nombreProducto }} - Cantidad: {{ $producto->cantidad }} - Precio Unitario: ${{ number_format($producto->precio, 2) }} - Subtotal: ${{ number_format($producto->subtotal, 2) }}</li>
                            @endif
                        @endforeach
                    </ul>
                    <p>Total de la Receta: ${{ number_format($receta->total, 2) }}</p>
                </div>
                <div class="modal-footer">
                    @if($receta->estatus !== 2)
                        <form action="{{ route('receta.enviarPedido', ['idreceta' => $receta->idreceta]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">Enviar Pedido</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('scripts')
    @parent
    <script>
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
