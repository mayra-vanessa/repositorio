@extends('layouts.plantilla_general')

@section('title', 'Proveedores')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-briefcase"></i> Proveedores</li>
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
        <h1>Proveedores</h1>
        <br>
        <div class="row">
            <div class="col-md-6">
                <!-- Cuadro de búsqueda -->
                <form action="{{ route('admin.proveedores') }}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="search" id="search-input" class="form-control" placeholder="Buscar..." value="{{ $search }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary btn-search">Buscar</button>
                        </div>
                        <!-- Botón para limpiar búsqueda -->
                        @if ($search)
                            <a href="{{ route('admin.proveedores') }}" class="btn btn-secondary btn-clear-search">Limpiar búsqueda</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <!-- Botón para agregar un nuevo proveedor -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregar">
                    <i class="fa fa-plus"></i> Agregar Proveedor
                </button>
            </div>
        </div>
        <br>
        <table id="proveedores-table" class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Nombre Empresa</th>
                    <th>Direccion</th>
                    <th>Correo</th>
                    <th>Telefono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proveedores as $proveedor)
                    <tr>
                        <td>{{ $proveedor->idproveedor }}</td>
                        <td>{{ $proveedor->nombre }}</td>
                        <td>{{ $proveedor->apellidos }}</td>
                        <td>{{ $proveedor->nombreEmpresa }}</td>
                        <td>{{ $proveedor->direccion }}</td>
                        <td>{{ $proveedor->correo }}</td>
                        <td>{{ $proveedor->telefono }}</td>
                        <td>
                            <a href="#" class="btn btn-primary btn-modificar"
                                data-id="{{ $proveedor->idproveedor }}"
                                data-nombre="{{ $proveedor->nombre }}"
                                data-apellidos="{{ $proveedor->apellidos }}"
                                data-nombreEmpresa="{{ $proveedor->nombreEmpresa }}"
                                data-direccion="{{ $proveedor->direccion }}"
                                data-correo="{{ $proveedor->correo }}"
                                data-telefono="{{ $proveedor->telefono }}">
                                <i class="fa fa-pencil"></i> Modificar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $proveedores->links('vendor.pagination.bootstrap-4') }} <!-- Enlace de paginación -->
        <br>
    </div>


    <!-- Modal de Proveedor (Agregar) -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAgregarLabel">Agregar Proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="FormAgregar" action="{{ route('proveedores.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombreAgregar">Nombre</label>
                            <input type="text" name="nombre" id="nombreAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="apellidosAgregar">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidosAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nombreEmpresaAgregar">Nombre Empresa</label>
                            <input type="text" name="nombreEmpresa" id="nombreEmpresaAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="direccionAgregar">Dirección</label>
                            <input type="text" name="direccion" id="direccionAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="correoAgregar">Correo</label>
                            <input type="email" name="correo" id="correoAgregar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="telefonoAgregar">Teléfono</label>
                            <input type="text" name="telefono" id="telefonoAgregar" class="form-control" required>
                        </div>
                        <!-- Agrega aquí los campos adicionales si es necesario -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" form="FormAgregar" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Proveedor (Modificar) -->
    <div class="modal fade" id="ModalModificar" tabindex="-1" role="dialog" aria-labelledby="ModalModificarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalModificarLabel">Modificar Proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="FormModificar" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="idproveedor" id="idproveedorModificar" value="">
                        <div class="form-group">
                            <label for="nombreModificar">Nombre</label>
                            <input type="text" name="nombre" id="nombreModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="apellidosModificar">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidosModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nombreEmpresaModificar">Nombre Empresa</label>
                            <input type="text" name="nombreEmpresa" id="nombreEmpresaModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="direccionModificar">Dirección</label>
                            <input type="text" name="direccion" id="direccionModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="correoModificar">Correo</label>
                            <input type="email" name="correo" id="correoModificar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="telefonoModificar">Teléfono</label>
                            <input type="text" name="telefono" id="telefonoModificar" class="form-control" required>
                        </div>
                        <!-- Agrega aquí los campos adicionales si es necesario -->
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
                modal.find('.modal-title').text('Agregar Proveedor');
                modal.find('#nombreAgregar').val('');
                modal.find('#apellidosAgregar').val('');
                modal.find('#nombreEmpresaAgregar').val('');
                modal.find('#direccionAgregar').val('');
                modal.find('#correoAgregar').val('');
                modal.find('#telefonoAgregar').val('');
                modal.find('form').attr('action', '{{ route('proveedores.store') }}');
            });

            // Abrir el modal de Modificar y cargar los datos
            $('#proveedores-table').on('click', '.btn-modificar', function() {
                var idproveedor = $(this).data('id');
                var nombre = $(this).data('nombre');
                var apellidos = $(this).data('apellidos');
                var nombreEmpresa = $(this).data('nombreempresa');
                var direccion = $(this).data('direccion');
                var correo = $(this).data('correo');
                var telefono = $(this).data('telefono');

                var modal = $('#ModalModificar');
                modal.find('.modal-title').text('Modificar Proveedor');
                modal.find('#idproveedorModificar').val(idproveedor);
                modal.find('#nombreModificar').val(nombre);
                modal.find('#apellidosModificar').val(apellidos);
                modal.find('#nombreEmpresaModificar').val(nombreEmpresa);
                modal.find('#direccionModificar').val(direccion);
                modal.find('#correoModificar').val(correo);
                modal.find('#telefonoModificar').val(telefono);
                modal.find('form').attr('action', '{{ route('proveedores.update', ['idproveedor' => '__idproveedor__']) }}'.replace('__idproveedor__', idproveedor));
                modal.modal('show');
            });

        });

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
