@extends('layouts.plantilla_general')

@section('title', 'Mis Consultas')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-stethoscope"></i> Consultas</li>
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
                <h1 class="display-5 font-weight-bold">Consultas</h1>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Cuadro de búsqueda -->
                <form action="{{ route('doctor.consultas') }}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="search" id="search-input" class="form-control" placeholder="Buscar..." value="{{ $search }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary btn-search">Buscar</button>
                        </div>
                        <!-- Botón para limpiar búsqueda -->
                        @if ($search)
                            <a href="{{ route('doctor.consultas') }}" class="btn btn-secondary btn-clear-search">Limpiar búsqueda</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Enfermedad</th>
                                <th>Descripción</th>
                                <th>Fecha de consulta</th>
                                <th>Hora de consulta</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($consultas as $consulta)
                                <tr>
                                    <td>{{ $consulta->paciente->nombre_completo }}</td>
                                    <td>{{ $consulta->enfermedad->nombre }}</td>
                                    <td>{{ $consulta->descripcion }}</td>
                                    <td>{{ \Carbon\Carbon::parse($consulta->fecha_consulta)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($consulta->hora_consulta)->format('h:i A') }}</td>
                                    <td style="@if ($consulta->estatus === 0) background-color: #FFE79B; @elseif ($consulta->estatus === 1) background-color: #A2FF86; @elseif ($consulta->estatus === 2) background-color: #AEEEEE; @endif">
                                        @if ($consulta->estatus === 0)
                                            Pendiente
                                        @elseif ($consulta->estatus === 1)
                                            Finalizada
                                        @elseif ($consulta->estatus === 2)
                                            Finalizada con Pedido
                                        @endif
                                    </td>
                                    <td>
                                        @if ($consulta->estatus === 0) <!-- Verificar si la consulta está finalizada -->
                                            <a href="{{ route('doctor.recetas', ['idconsulta' => $consulta->idconsulta]) }}" class="btn btn-primary">Crear Receta</a>
                                        @endif
                                        <button class="btn btn-primary chat-button"  data-enfermedad="{{ $consulta->enfermedad->nombre }}" data-consulta-id="{{ $consulta->idconsulta }}" data-toggle="modal" data-target="#chatModal{{ $consulta->idconsulta }}"><i class="fa fa-comments"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        {{ $consultas->links('vendor.pagination.bootstrap-4') }} <!-- Enlace de paginación -->
    </div>


    <div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">Chat enfermedad: <span id="enfermedadNombre"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <ul class="chat list-unstyled" id="chatContainer">
                        <!-- Contenido del chat se cargará aquí -->
                    </ul>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" id="chatInput" placeholder="Escribe un mensaje..." data-consulta-id="">
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="sendButton"><i class="fa fa-paper-plane"></i></button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="offlineFlag" value="false">
@endsection

@section('styles')
    @parent
    <style>

        /* Ajustar márgenes del botón */
        .btn-search {
            margin-left: 10px;
        }

        .img-especialista-small {
            max-width: 100%;
            height: auto;
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

        .img-especialista-small {
            max-width: 100%;
            height: auto;
        }

        /* Añade estos estilos CSS en tu sección de estilos */
        .chat-container {
            height: 350px;
            overflow-y: scroll;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
        }

        .chat {
            list-style: none;
            padding: 0;
        }

        .chat li {
            list-style: none;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            clear: both;
            max-width: 80%;
            margin: 0 10px;
        }

        .message-doctor {
            background-color: #E6E6E6;
            float: left;
            clear: both;
        }

        .message-user {
            background-color: #DCF8C6;
            float: right;
            clear: both;
        }

        .user-avatar {
            max-width: 30px;
            max-height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .input-group {
            margin-top: 15px;
        }

        /* Estilos para el botón de enviar */
        #sendButton {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
        }

        #sendButton:hover {
            background-color: #0056b3;
        }
    </style>
@endsection


@section('scripts')
    @parent
    <script>

        $(document).ready(function () {

            // Escuchar el evento de cambio en el campo de búsqueda
            $('#search-input').on('input', function() {
                var searchText = $(this).val().toLowerCase();

                // Filtrar las filas de la tabla según el texto de búsqueda
                $('tbody tr').each(function() {
                    var rowText = $(this).text().toLowerCase();

                    if (rowText.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Cerrar el mensaje de éxito después de 5 segundos (5000 ms)
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 1500); // 5000 ms = 5 segundos

            // Opcional: Permitir al usuario cerrar el mensaje haciendo clic en el botón "Cerrar"
            $(document).on('click', '[data-dismiss="alert"]', function() {
                $(this).closest('.alert').fadeOut('slow');
            });


            // Función para cargar los mensajes iniciales
            if (!navigator.onLine) {
                $('#offlineFlag').val('true');
            }

            function formatTime(time) {
                if (time) {
                    // Dividir la hora en partes: horas, minutos y segundos
                    const parts = time.split(':');
                    if (parts.length === 3) {
                        const hours = parseInt(parts[0]);
                        const minutes = parseInt(parts[1]);
                        const ampm = hours >= 12 ? 'PM' : 'AM';
                        const formattedHours = (hours % 12) || 12; // Asegura que las 12:00 PM se muestren como 12:00 PM
                        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

                        return `${formattedHours}:${formattedMinutes} ${ampm}`;
                    }
                }

                return ''; // Devuelve una cadena vacía si el tiempo no es válido
            }

            $('#chatModal').on('shown.bs.modal', function () {
                var chatContainer = $('#chatContainer');
                chatContainer.scrollTop(chatContainer.prop('scrollHeight'));
            });

            function loadMessages(consultaId) {
                $.get('/doctor/Consulta/' + consultaId + '/Comentarios', function (data) {

                    console.log(data)
                    var chatContainer = $('#chatContainer');
                    chatContainer.empty();

                    

                    if (data.consulta && data.consulta.comentarios) {
                        data.consulta.comentarios.forEach(function (comentario) {
                            chatContainer.append(
                                '<li>' +
                                '<div class="message ' + (comentario.departede === 1 ? 'message-doctor' : 'message-user') + '">' +
                                '<div class="message-content" style="width: 100%; float: left;">' +
                                (comentario.departede === 1 ?
                                '<i class="fa fa-user"></i>' +
                                    '<strong> {{ $consulta->paciente->nombre_completo }}</strong>  <br>' : '') +
                                comentario.comentario +
                                (comentario.departede === 1 ?  '<br>' +formatTime(comentario.hora) : '') +
                                (comentario.departede !== 1 ?
                                    '<div class="message-info">' +
                                   
                                    (comentario.estatus === 1 ? '<i class="fa fa-check" style="color: gray;"></i>' :
                                        (comentario.estatus === 2 ? '<i class="fa fa-check" style="color: blue;"></i>' : '')) +
                                        '<span>' + formatTime(comentario.hora) + '</span>' +
                                        '<img src="{{ $consulta->especialista->foto }}" alt="Doctor" class="user-avatar">' +
                                    '</div>' : '') +
                                '</div>' +
                                '</div>' +
                                '</li>'
                            );
                        });
                    }

                    chatContainer.scrollTop(chatContainer.prop('scrollHeight'));
                });
            }

            // Captura el clic en el botón de chat
            $('.chat-button').click(function () {
                var consultaId = $(this).data('consulta-id');
                var enfermedadNombre = $(this).data('enfermedad');
                console.log('Clic en el botón de chat para el ID ' + consultaId);

                // Limpia el contenido del modal
                $('#chatContainer').empty();

                // Actualiza el título del modal con el nombre de la enfermedad
                $('#enfermedadNombre').text(enfermedadNombre);

                // Asigna el valor de consultaId al atributo data-consulta-id
                $('#chatInput').attr('data-consulta-id', consultaId);

                // Carga los mensajes iniciales
                loadMessages(consultaId);

                // Abre el modal
                $('#chatModal').modal('show');
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Función para enviar mensajes
            function sendMessage(message, consultaId) {
                // Registra el comentario en el servidor o en el almacenamiento local
                if (navigator.onLine) {
                    $.ajax({
                        url: '/doctor/Consulta/' + consultaId + '/Comentarios/Agregar',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            mensaje: message,
                            idconsulta: consultaId
                        },
                        success: function (data) {
                            console.log('Comentario registrado con éxito en el servidor:', data.mensaje);
                            // Implementa la lógica para mostrar el comentario en la vista si es necesario
                            // Llama a la función para cargar el nuevo mensaje
                            loadMessages(consultaId);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error al registrar el comentario en el servidor');
                        }
                    });
                } else {
                    // La aplicación está fuera de línea, guarda el comentario localmente
                    const pendingMessages = JSON.parse(localStorage.getItem('pendingMessages')) || [];
                    const messageObject = { message, status: 0 };
                    const messages = [...pendingMessages, messageObject];

                    localStorage.setItem('pendingMessages', JSON.stringify(messages));
                }
            }

            // Maneja el clic en el botón de enviar
           /*  document.getElementById('sendButton').addEventListener('click', function () {
                const inputElement = document.getElementById('chatInput');
                const message = inputElement.value.trim();
                const consultaId = inputElement.dataset.consultaId;

                if (message) {
                    // Llama a la función para enviar el mensaje
                    sendMessage(message, consultaId);
                    inputElement.value = '';
                }
            }); */

            // Maneja el clic en el botón de enviar
document.getElementById('sendButton').addEventListener('click', function () {
    const inputElement = document.getElementById('chatInput');
    const message = inputElement.value.trim();
    const consultaId = inputElement.dataset.consultaId;
    const isOffline = $('#offlineFlag').val() === 'true';

    if (message) {
        // Verifica si el usuario está desconectado
        if (isOffline) {
            // Guarda el mensaje localmente
            const pendingMessages = JSON.parse(localStorage.getItem('pendingMessages')) || [];
            const messageObject = { message, idconsulta: consultaId };
            const messages = [...pendingMessages, messageObject];

            localStorage.setItem('pendingMessages', JSON.stringify(messages));
            // Luego, muestra el mensaje en la UI con indicador de espera
            appendMessageToUI(message, true, true);
        } else {
            // El usuario está en línea, llama a la función para enviar el mensaje
            sendMessage(message, consultaId);
        }
        inputElement.value = '';
    }
});


            // Escucha eventos de conexión y desconexión
            window.addEventListener('online', function () {
                $('#offlineFlag').val('false');
                const pendingMessages = JSON.parse(localStorage.getItem('pendingMessages')) || [];

                if (pendingMessages.length > 0) {
                    pendingMessages.forEach((messageObject) => {
                        sendMessage(messageObject.message, messageObject.idconsulta);
                    });

                    localStorage.removeItem('pendingMessages');
                }
            });

            window.addEventListener('offline', function () {
                $('#offlineFlag').val('true');
                // La aplicación está desconectada
                // Puedes mostrar un indicador visual de falta de conexión
            });

            // Función para mostrar el mensaje en la UI
        // Función para mostrar el mensaje en la UI para el paciente
        function appendMessageToUI(message, isOffline) {
            var chatContainer = $('#chatContainer');

            // Obten la hora actual
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            var amOrPm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // Convierte la hora al formato de 12 horas
            var timeString = hours + ':' + (minutes < 10 ? '0' : '') + minutes + ' ' + amOrPm + ' ';
            
            chatContainer.append(
                '<li>' +
                '<div class="message message-user">' +
                '<div class="message-content" style="width: 100%; float: left;">' +

                message +
                
                '</div>' +
                '<i class="fa fa-clock-o" style="color: gray;">' + timeString + '</i>'+
                '<img src="{{ $consulta->especialista->foto }}" alt="Doctor" class="user-avatar">' +
                '</div>' +
                '</li>'
            );
        }



            // Resto del código...
        });
    </script>
@endsection

