@extends('layouts.plantilla_general')

@section('title', 'Especialistas')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-user-md"></i> Especialistas</li>
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
        <h1>Especialistas</h1>
        <br>
        <div class="row">
            <div class="col-md-6">
                <!-- Cuadro de búsqueda -->
                <form action="{{ route('admin.especialistas') }}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="search" id="search-input" class="form-control" placeholder="Buscar..." value="{{ $search }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary btn-search">Buscar</button>
                        </div>
                        <!-- Botón para limpiar búsqueda -->
                        @if ($search)
                            <a href="{{ route('admin.especialistas') }}" class="btn btn-secondary btn-clear-search">Limpiar búsqueda</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <!-- Botón para agregar un nuevo especialista -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregar">
                    <i class="fa fa-plus"></i> Agregar Especialista
                </button>
            </div>
        </div>
        <br>
        <!-- Agrega un div o contenedor alrededor de la tabla para poder actualizarla fácilmente -->
        <div id="tabla-especialistas-container">
        <table id="especialistas-table" class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Cedula</th>
                    <th>Correo</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($especialistas as $especialista)
                <tr>
                    <td>{{ $especialista->idespecialista }}</td>
                    <td>{{ $especialista->nombre }}</td>
                    <td>{{ $especialista->apellidos }}</td>
                    <td>{{ $especialista->direccion }}</td>
                    <td>{{ $especialista->telefono }}</td>
                    <td>{{ $especialista->cedulaProfesional }}</td>
                    <td>{{ $especialista->user_email  ? $especialista->user_email : ''}}</td>
                    <td>
                        <img src="{{ $especialista->foto }}" alt="Imagen del especialista" width="100">
                    </td> 
                    <td>
                        <a href="#" class="btn btn-primary btn-modificar"
                            data-id="{{ $especialista->idespecialista }}"
                            data-nombre="{{ $especialista->nombre }}"
                            data-apellidos="{{ $especialista->apellidos }}"
                            data-direccion="{{ $especialista->direccion }}"
                            data-telefono="{{ $especialista->telefono }}"
                            data-cedula="{{ $especialista->cedulaProfesional }}"
                            data-correo="{{ $especialista->user_email ? $especialista->user_email : '' }}"
                            data-imagen="{{ $especialista->foto }}">
                            <i class="fa fa-pencil"></i> Modificar
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        {{ $especialistas->links('vendor.pagination.bootstrap-4') }} <!-- Enlace de paginación -->
        <br>
    </div>

    <!-- Modal de Especialista (Agregar) -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarLabel">Agregar Especialista</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAgregar" action="{{ route('especialistas.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Campos del formulario de Información Especialista -->
                                <div class="centrar-texto">
                                    <label for="infoEspecialista">Información Especialista</label>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="nombreAgregar">Nombre</label>
                                    <input type="text" name="nombre" id="nombreAgregar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="apellidosAgregar">Apellidos</label>
                                    <input type="text" name="apellidos" id="apellidosAgregar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="direccionAgregar">Dirección</label>
                                    <input type="text" name="direccion" id="direccionAgregar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="telefonoAgregar">Teléfono</label>
                                    <input type="text" name="telefono" id="telefonoAgregar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="cedulaAgregar">Cédula Profesional</label>
                                    <input type="text" name="cedula" id="cedulaAgregar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="imagenAgregar">Foto URL</label>
                                    <input type="text" name="foto" id="imagenAgregar" class="form-control" required>
                                </div>          
                                <div class="centrar-texto">
                                    <label for="infoEspecialista">Informacion Usuario</label>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="correoAgregar">Correo</label>
                                    <input type="email" name="email" id="correoAgregar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="passwordAgregar">Contraseña</label>
                                    <input type="password" name="password" id="passwordAgregar" class="form-control" pattern="[A-Za-z0-9!?-]{8,12}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Campos de selección de enfermedades -->
                                <div class="centrar-texto">
                                    <label for="infoEnfermedades">Enfermedades Relacionadas</label>
                                </div>
                                <br>
                                <h5>Buscar Enfermedades</h5>
                                <!-- Cuadro de búsqueda para enfermedades -->
                                <input type="text" id="searchEnfermedades" class="form-control mb-3" placeholder="Buscar enfermedades...">
                                <br>
                                <!-- Combobox para seleccionar enfermedades -->
                                <select name="enfermedades[]" id="enfermedadesAgregar" class="form-control" multiple>
                                    <!-- Opciones de enfermedades se agregarán dinámicamente aquí -->
                                </select>
                                <br>
                                <!-- Botón para agregar la enfermedad seleccionada a la lista -->
                                <button type="button" class="btn btn-primary mt-3" id="btnAgregarEnfermedad">Agregar Enfermedad</button>
                                <br>

                                <!-- Lista de enfermedades seleccionadas -->
                                <br>
                                <h5>Enfermedades Seleccionadas</h5>
                                <br>
                                <div class="enfermedades-seleccionadas-container">
                                    <!-- Enfermedades seleccionadas se mostrarán aquí -->
                                </div>
                            </div>
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

    <!-- Modal de Especialista (Modificar) -->
    <div class="modal fade" id="modalModificar" tabindex="-1" role="dialog" aria-labelledby="modalModificarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModificarLabel">Modificar Especialista</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formModificar" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="idespecialista" id="idespecialistaModificar" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Campos del formulario de Información Especialista -->
                                <div class="centrar-texto">
                                    <label for="infoEspecialista">Información Especialista</label>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="nombreModificar">Nombre</label>
                                    <input type="text" name="nombre" id="nombreModificar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="apellidosModificar">Apellidos</label>
                                    <input type="text" name="apellidos" id="apellidosModificar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="direccionModificar">Dirección</label>
                                    <input type="text" name="direccion" id="direccionModificar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="telefonoModificar">Teléfono</label>
                                    <input type="text" name="telefono" id="telefonoModificar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="cedulaModificar">Cédula Profesional</label>
                                    <input type="text" name="cedula" id="cedulaModificar" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="imagenModificar">Foto URL</label>
                                    <input type="text" name="foto" id="imagenModificar" class="form-control" required>
                                </div>
                                <br>
                                <div class="centrar-texto">
                                    <label for="infoEspecialista">Información Usuario</label>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="correoModificar">Correo</label>
                                    <input type="email" name="email" id="correoModificar" class="form-control" readonly required>
                                </div>
                                <div class="form-group">
                                    <label for="passwordModificar">Contraseña</label>
                                    <input type="password" name="password" id="passwordModificar" class="form-control" pattern="[A-Za-z0-9!?-]{8,12}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Campos de selección de enfermedades -->
                                <div class="centrar-texto">
                                    <label for="infoEnfermedades">Enfermedades Relacionadas</label>
                                </div>
                                <br>
                                <h5>Buscar Enfermedades</h5>
                                <!-- Cuadro de búsqueda para enfermedades -->
                                <input type="text" id="searchEnfermedadesModificar" class="form-control mb-3" placeholder="Buscar enfermedades...">
                                <br>
                                <!-- Combobox para seleccionar enfermedades -->
                                <select name="enfermedades[]" id="enfermedadesModificar" class="form-control" multiple>
                                    <!-- Opciones de enfermedades se agregarán dinámicamente aquí -->
                                </select>
                                <br>
                                <!-- Botón para agregar la enfermedad seleccionada a la lista -->
                                <button type="button" class="btn btn-primary mt-3" id="btnAgregarEnfermedadModificar">Agregar Enfermedad</button>
                                <br>

                                <!-- Lista de enfermedades seleccionadas -->
                                <br>
                                <h5>Enfermedades Seleccionadas</h5>
                                <br>
                                <div class="enfermedades-relacionadas-container">
                                    <!-- Enfermedades seleccionadas se mostrarán aquí -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" form="formModificar" id="btnGuardarCambios" class="btn btn-primary">Guardar cambios</button>
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

        #enfermedadesAgregar {
            height: 250px;
        }

        #enfermedadesModificar {
            height: 250px;
        }

        /* Estilo para las etiquetas de las enfermedades seleccionadas */
        .enfermedad-etiqueta {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f0f0f0;
            color: #333;
            margin: 5px;
            border-radius: 5px;
        }

        /* Estilo para el ícono "X" de eliminar enfermedad */
        .btn-eliminar-enfermedad {
            cursor: pointer;
            color: #d9534f;
            margin-left: 5px;
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
                    $(this).closest('.col-md-4').toggle(rowText.includes(searchText));
                });
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

        
            // Realizar una llamada AJAX para obtener la lista de enfermedades
            $.ajax({
                url: '{{ route('admin.obtenerEnfermedades') }}',
                method: 'GET',
                success: function(data) {
                    // Limpiar el contenido anterior del combo de selección de enfermedades
                    $('#enfermedadesAgregar').empty();

                    // Agregar opciones de enfermedades al combo de selección
                    data.forEach(function(enfermedad) {
                        const option = $('<option value="' + enfermedad.idenfermedad + '">' + enfermedad.nombre + '</option>');
                        $('#enfermedadesAgregar').append(option);
                    });
                },
                error: function(xhr, status, error) {
                    // Manejar errores si es necesario
                    console.error(error);
                }
            });


            //MODAL AGREGAR

            var enfermedadesSeleccionadasAgregar = [];


            $('#btnAgregarEnfermedad').on('click', function() {
                var enfermedadSeleccionada = $('#enfermedadesAgregar').val();
                if (enfermedadSeleccionada && enfermedadSeleccionada.length > 0) {
                    enfermedadSeleccionada.forEach(function(enfermedadId) {
                        // Convertir enfermedadId a número
                        enfermedadId = parseInt(enfermedadId);

                        // Verificar si la enfermedad ya está en la lista para evitar duplicados
                        if (!enfermedadesSeleccionadasAgregar.includes(enfermedadId)) {
                            enfermedadesSeleccionadasAgregar.push(enfermedadId);
                            var enfermedadNombre = $('#enfermedadesAgregar option[value="' + enfermedadId + '"]').text();
                            var tag = '<span class="enfermedad-etiqueta" data-enfermedad-id="' + enfermedadId + '">' + enfermedadNombre + ' <i class="btn-eliminar-enfermedad fa fa-times"></i></span>';
                            $('.enfermedades-seleccionadas-container').append(tag);

                            // Coloca el console.log aquí para asegurarte de que la variable tenga valor
                            console.log("id enfermedad clic: ", enfermedadSeleccionada);
                        } else {
                            // La enfermedad ya está en la lista, puedes mostrar una notificación o hacer algo
                            console.log("La enfermedad ya está en la lista.");
                        }
                    });
                }
            });




            // Escuchar el evento de clic en el ícono "X" para eliminar la enfermedad de la lista de seleccionadas
            $(document).on('click', '.btn-eliminar-enfermedad', function() {
                var enfermedadId = $(this).closest('.enfermedad-etiqueta').data('enfermedad-id');
                enfermedadesSeleccionadasAgregar = enfermedadesSeleccionadasAgregar.filter(id => id !== enfermedadId);
                $(this).parent('.enfermedad-etiqueta').remove();
                $('#enfermedadesAgregar option[value="' + enfermedadId + '"]').prop('selected', false);
            });

            // Escuchar el evento de apertura del modal de agregar
            $('#modalAgregar').on('show.bs.modal', function(event) {
                enfermedadesSeleccionadasAgregar = [];
                $('#searchEnfermedades').val('');
                $('#enfermedadesAgregar option').show();
                $('.enfermedades-seleccionadas-container').empty();
            });

            // Escuchar el evento de cambio en el campo de búsqueda de enfermedades
            $('#searchEnfermedades').on('input', function() {
                var searchText = $(this).val().toLowerCase();
                $('#enfermedadesAgregar option').each(function() {
                    var optionText = $(this).text().toLowerCase();
                    $(this).toggle(optionText.includes(searchText));
                });
            });

            /* Escuchar el evento de clic en el botón "Guardar" en el modal de agregar
            $('#modalAgregar').on('click', '.btn-primary', function() {
                $('#formAgregar').append('<input type="hidden" name="enfermedades" value=\'' + JSON.stringify(enfermedadesSeleccionadasAgregar) + '\' />');
            });*/

            // Escuchar el evento de clic en el ícono "X" para eliminar la enfermedad de la lista de seleccionadas
            $(document).on('click', '.btn-eliminar-enfermedad', function() {
                var enfermedadId = $(this).closest('.enfermedad-etiqueta').data('enfermedad-id');

                // Eliminar la enfermedad de la lista
                enfermedadesSeleccionadasAgregar = enfermedadesSeleccionadasAgregar.filter(id => id !== enfermedadId);

                // Eliminar la etiqueta span de la enfermedad
                $(this).parent('.enfermedad-etiqueta').remove();

                // Deseleccionar la enfermedad en el combo
                $('#enfermedadesAgregar option[value="' + enfermedadId + '"]').prop('selected', false);
            });

            // Escuchar el evento de clic en el botón "Guardar" en el modal de agregar
            $('#modalAgregar').on('click', '.btn-primary', function() {
                // Agregar las enfermedades seleccionadas al formulario antes de enviarlo al servidor
                $('#formAgregar').append('<input type="hidden" name="enfermedades" value=\'' + JSON.stringify(enfermedadesSeleccionadasAgregar) + '\' />');
            });

            // --------------------------------------------------------------------

            //MODAL MODIFICAR

             // Obtener las enfermedades relacionadas con el especialista y almacenar sus IDs en un array
             var enfermedadesSeleccionadasModificar = [];

            // Abrir el modal de Modificar y cargar los datos
            $('#especialistas-table').on('click', '.btn-modificar', function() {
                enfermedadesSeleccionadasModificar = [];
                var idespecialista = $(this).data('id');
                
                console.log(idespecialista);
                var modal = $('#modalModificar');

                // Limpiar el contenido anterior de la lista de enfermedades relacionadas
                $('.enfermedades-relacionadas-container').empty();

                modal.find('.modal-title').text('Modificar Especialista');
                modal.find('#idespecialistaModificar').val(idespecialista);
                modal.find('#nombreModificar').val($(this).data('nombre'));
                modal.find('#apellidosModificar').val($(this).data('apellidos'));
                modal.find('#direccionModificar').val($(this).data('direccion'));
                modal.find('#telefonoModificar').val($(this).data('telefono'));
                modal.find('#cedulaModificar').val($(this).data('cedula'));
                modal.find('#correoModificar').val($(this).data('correo'));
                modal.find('#imagenModificar').val($(this).data('imagen'));

                // Realizar una llamada AJAX para obtener todas las enfermedades disponibles
                $.ajax({
                    url: '{{ route('admin.obtenerEnfermedades') }}',
                    method: 'GET',
                    success: function(data) {
                        // Limpiar el contenido anterior del combo de selección de enfermedades
                        $('#enfermedadesModificar').empty();

                        // Agregar opciones de enfermedades al combo de selección
                        data.forEach(function(enfermedad) {
                            const option = $('<option value="' + enfermedad.idenfermedad + '">' + enfermedad.nombre + '</option>');
                            $('#enfermedadesModificar').append(option);
                        });
                    
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores si es necesario
                        console.error(error);
                    }
                });
                
                // Realizar una llamada AJAX para obtener las enfermedades relacionadas con el especialista
                $.ajax({
                    url: '{{ route('especialistas.enfermedades', ['idespecialista' => '__idespecialista__']) }}'.replace('__idespecialista__', idespecialista),
                    method: 'GET',
                    success: function(data) {
                        // Limpiar el contenido anterior de la lista de enfermedades relacionadas
                        $('.enfermedades-relacionadas-container').empty();


                        // Mostrar las enfermedades relacionadas en la lista
                        data.forEach(function(enfermedad) {
                            var etiquetaEnfermedad = '<span class="enfermedad-etiqueta" data-enfermedad-id="' + enfermedad.idenfermedad + '">' + enfermedad.nombre + ' <i class="btn-eliminar-enfermedad fa fa-times"></i></span>';
                            $('.enfermedades-relacionadas-container').append(etiquetaEnfermedad);

                            enfermedadesSeleccionadasModificar.push(enfermedad.idenfermedad);
                        });

                        // Asegúrate de que las enfermedades relacionadas lleguen correctamente
                        console.log(data);
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores si es necesario
                        console.error(error);
                    }
                });

                modal.modal('show');
            });

            // Escuchar el evento de apertura del modal de modificar
            $('#modalModificar').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var idEspecialista = button.data('id'); // Extraer el ID del especialista de los atributos de datos

                // Imprimir las enfermedades seleccionadas actualmente
                console.log("Enfermedades Seleccionadas:", enfermedadesSeleccionadasModificar);
            });

            // Escuchar el evento de clic en el botón "Guardar cambios" fuera del formulario
            $('#btnGuardarCambios').on('click', function(event) {
                event.preventDefault(); // Evitamos que el formulario se envíe automáticamente al hacer clic en el botón

                // Obtener los datos del formulario de modificar
                var idespecialista = $('#idespecialistaModificar').val();
                var datosFormularioModificar = $('#formModificar').serializeArray();

                // Agregar las enfermedades seleccionadas al array de datos
                datosFormularioModificar.push({ name: 'enfermedades', value: JSON.stringify(enfermedadesSeleccionadasModificar) });

                // Realizar una llamada AJAX para enviar los datos al servidor
                $.ajax({
                    url: '/admin/Especialistas/' + idespecialista + '/Actualizar',
                    method: 'POST', // Cambiamos a method: 'POST' para enviar una petición POST
                    data: datosFormularioModificar,
                    success: function(data) {
                        // Procesar la respuesta del servidor si es necesario
                        // Cerrar el modal después de guardar los cambios exitosamente
                        $('#modalModificar').modal('hide');

                        // Redireccionar a la página de especialistas después de que la solicitud AJAX se haya completado
                        window.location.href = '/admin/Especialistas'; // Cambia la URL según la ruta correcta de tu página de especialistas
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores si es necesario
                        console.error(error);
                    }
                });
            });

            // Escuchar el evento de clic en el ícono "X" para eliminar la enfermedad de la lista de seleccionadas en el modal de modificar
            $('.enfermedades-relacionadas-container').on('click', '.btn-eliminar-enfermedad', function() {
                var enfermedadId = $(this).closest('.enfermedad-etiqueta').data('enfermedad-id');

                // Marcar la enfermedad para eliminar
                enfermedadesSeleccionadasModificar = enfermedadesSeleccionadasModificar.filter(id => id !== enfermedadId);

                // Eliminar la etiqueta span de la enfermedad relacionada
                $(this).closest('.enfermedad-etiqueta').remove();
            });

             // Escuchar el evento de cambio en el campo de búsqueda de enfermedades en el modal de modificar
             $('#searchEnfermedadesModificar').on('input', function() {
                var searchText = $(this).val().toLowerCase();

                // Filtrar las opciones del combobox según el texto de búsqueda
                $('#enfermedadesModificar option').each(function() {
                    var optionText = $(this).text().toLowerCase();
                    $(this).toggle(optionText.includes(searchText));
                });
            });

            

            $('#btnAgregarEnfermedadModificar').on('click', function() {
                var enfermedadSeleccionada = $('#enfermedadesModificar').val();
                if (enfermedadSeleccionada && enfermedadSeleccionada.length > 0) {
                    // Agregar cada enfermedad seleccionada a la lista
                    enfermedadSeleccionada.forEach(function(enfermedadId) {
                        // Convertir enfermedadId a número
                        enfermedadId = parseInt(enfermedadId);

                        // Verificar si la enfermedad ya está en la lista para evitar duplicados
                        if (!enfermedadesSeleccionadasModificar.includes(enfermedadId)) {
                            // Si la enfermedad no está en la lista de seleccionadas, agregarla
                            enfermedadesSeleccionadasModificar.push(enfermedadId);
                            var enfermedadNombre = $('#enfermedadesModificar option[value="' + enfermedadId + '"]').text();
                            var tag = '<span class="enfermedad-etiqueta" data-enfermedad-id="' + enfermedadId + '">' + enfermedadNombre + ' <i class="btn-eliminar-enfermedad fa fa-times"></i></span>';
                            $('.enfermedades-relacionadas-container').append(tag);

                            // Coloca el console.log aquí para asegurarte de que la variable tenga valor
                            console.log("id enfermedad clic: ", enfermedadSeleccionada);
                        }
                    });
                }
            });




        });
    </script>
@endsection
