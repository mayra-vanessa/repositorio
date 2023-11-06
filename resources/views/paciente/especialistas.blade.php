@extends('layouts.plantilla_general')

@section('title', 'Especialistas de Enfermedad')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><a href="{{ route('enfermedades') }}" class="breadcrumb-link"><i class="fa fa-fw fa-heartbeat"></i> Enfermedades</a></li>
        <li><i class="fa fa-user-md"></i> Especialistas </li>
    </ul>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-5 font-weight-bold">Especialistas de {{ $enfermedad->nombre }}</h1>
            </div>
        </div>
        <br>
        <div class="row justify-content-center" id="especialistas-grid">
            @forelse ($especialistas as $especialista)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h3 class="card-title font-weight-bold" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $especialista->nombre }} {{ $especialista->apellidos }}</h3>
                            <div class="img-container">
                                <img src="{{ $especialista->foto }}" class="card-img-top img-fluid img-especialista" alt="{{ $especialista->nombre }} {{ $especialista->apellidos }}">
                            </div>
                            <br>
                            <p><strong>Dirección:</strong> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $especialista->direccion }}</span></p>
                            <p><strong>Teléfono:</strong> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $especialista->telefono }}</span></p>
                            <!-- Aquí puedes mostrar otros detalles del especialista si lo deseas -->
                            <!-- Botón para hacer consulta -->
                           <!-- Botón para hacer consulta -->
                            <a href="#" class="btn btn-success btn-lg btn-block mt-3" data-toggle="modal" data-target="#consultaModal" data-id="{{ $especialista->idespecialista }}">Realizar Consulta</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12 text-center">
                    <p>No hay especialistas disponibles para esta enfermedad.</p>
                </div>
            @endforelse
        </div>

        {{ $especialistas->links('vendor.pagination.bootstrap-4') }} <!-- Enlace de paginación -->
        <br>
    </div>

    <!-- Modal para realizar la consulta -->
    <div class="modal fade" id="consultaModal" tabindex="-1" role="dialog" aria-labelledby="consultaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="consultaModalLabel">Realizar Consulta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('consulta.hacer', ['idenfermedad' => $enfermedad->idenfermedad, 'idespecialista' => $especialista->idespecialista]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="fecha">Fecha de la consulta:</label>
                            <input type="date" id="fecha" name="fecha" required>
                        </div>

                        <div class="form-group">
                            <label for="hora">Hora de la consulta:</label>
                            <input type="time" id="hora" name="hora" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción de la consulta:</label>
                            <textarea id="descripcion" name="descripcion" rows="6" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Consulta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    @parent
    <style>
        .img-especialista {
            max-height: 200px;
            object-fit: cover;
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
        .img-especialista {
            display: block;
            margin: 0 auto;
            border-radius: 15px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script>
        // JavaScript para mostrar y ocultar el modal de realizar consulta y configurar la ruta correcta del formulario
        $(document).ready(function() {
            $('#consultaModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var idEspecialista = button.data('id'); // Obtener el ID del especialista del atributo data-id

                // Configurar la ruta del formulario de consulta con el ID del especialista
                var formAction = "{{ route('consulta.hacer', ['idenfermedad' => $enfermedad->idenfermedad, 'idespecialista' => ':idEspecialista']) }}";
                formAction = formAction.replace(':idEspecialista', idEspecialista);

                // Asignar la ruta configurada al atributo "action" del formulario en el modal
                $('#consultaModal form').attr('action', formAction);
            });
        });

    </script>
@endsection