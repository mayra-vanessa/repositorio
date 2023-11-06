<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Models\Consulta; 
use App\Models\Especialistas;

use App\Models\Receta;
use App\Models\RecetaDetalle;
use App\Models\Productos;
use App\Models\Comentario;

class DoctorController extends Controller
{
    // CONSULTAS

    public function consultas_especialistas(Request $request)
    {
        // Obtener el especialista autenticado (asumiendo que el modelo para el especialista es Especialistas)
        $especialista = \Auth::user()->especialista;

        // Verificar que el usuario autenticado sea un especialista válido antes de obtener sus consultas
        if (!$especialista) {
            // Si el usuario no es un especialista válido, redireccionar o mostrar un mensaje de error
            return redirect()->route('Inicio'); // Cambiar 'pagina_de_acceso_no_autorizado' por la ruta deseada o mostrar un mensaje de error apropiado
        }

        // Obtener las consultas del especialista a través de la relación definida en el modelo Especialistas
        $consultas = $especialista->consultas()->with('enfermedad', 'paciente');

        // Si deseas realizar la búsqueda por texto
        $search = $request->input('search');
        if ($search) {
            $consultas = $consultas->where('descripcion', 'LIKE', "%$search%"); // Puedes agregar más campos si deseas buscar en otros atributos
        }

        // Paginar las consultas para mostrarlas en la vista
        $consultas = $consultas->paginate(10);

        return view('doctor.consultas', compact('consultas', 'search'));
    }

    public function recetas_vista($idconsulta)
    {
        // Obtiene todos los productos disponibles, ordenados descendentemente por idproducto
        $productos = Productos::orderByDesc('idproducto')->get();

        // Lógica para mostrar la vista de creación de recetas
        return view('doctor.recetas', ['idconsulta' => $idconsulta, 'productos' => $productos]);
    }

    
    public function finalizarConsulta(Request $request,$idconsulta)
    {
        // Obtener los datos de la consulta
        $consulta = Consulta::find($idconsulta);


        // Obtener la lista de productos seleccionados desde el formulario
        $productosSeleccionados = $request->input('productosSeleccionados');

         // Calcular el total de la receta
         $totalReceta = 0;
         $recetaDetalleInfo = '';
         foreach ($productosSeleccionados as $producto) {
             $subtotal = $producto['precio'] * $producto['cantidad'];
             $totalReceta += $subtotal;
 
             // Obtener información adicional del producto (nombre)
             $productoInfo = Productos::find($producto['id']);
             $nombreProducto = $productoInfo->nombreProducto;
 
             // Agregar detalles del producto a la variable
             $recetaDetalleInfo .= "Producto: $nombreProducto, Cantidad: {$producto['cantidad']}, Precio: $ {$producto['precio']}, Subtotal: $ {$subtotal}" . PHP_EOL;
         }

        // Crear una nueva receta
        $receta = new Receta([
            'idconsulta' => $idconsulta,
            'total' => $totalReceta,
        ]);
        $receta->save();

        // Guardar el detalle de la receta
        foreach ($productosSeleccionados as $producto) {
            $recetaDetalle = new RecetaDetalle([
                'idreceta' => $receta->idreceta,
                'idproducto' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'precio' => $producto['precio'],
                'subtotal' => $producto['precio'] * $producto['cantidad'], // O puedes utilizar el subtotal calculado anteriormente
            ]);
            $recetaDetalle->save();
        }

        // Actualizar el estatus de la consulta a finalizada (1)
        $consulta->estatus = 1;
        $consulta->save();

        // Obtener información adicional de la consulta
        $especialista = $consulta->especialista;
        $nombreDoctor = $especialista->nombreCompleto;// Nombre del especialista
        $fechaConsulta = $consulta->fecha_consulta;
        $enfermedad = $consulta->enfermedad->nombre; // Nombre de la enfermedad

        // Construir el mensaje de WhatsApp con la información de la consulta y receta
        $messageBody = "¡Hola! la consulta del $fechaConsulta para la enfermedad $enfermedad que atendio el doctor $nombreDoctor ." . PHP_EOL;
        $messageBody .= "Te mandó la receta para tu tratamiento:" . PHP_EOL . $recetaDetalleInfo;
        $messageBody .= "Total de la receta: $ $totalReceta" . PHP_EOL;
        $messageBody .= "Visita la página https://rvec.proyectowebuni.com para ver más detalles sobre tu consulta y receta.";

        // Configurar los datos para la solicitud a la API de Ultramsg
        $ultramsgUrl = 'https://api.ultramsg.com/instance55435/messages/chat';
        $ultramsgToken = '2g02i1v2e6jepyv4';
        $telefonoPaciente = '+52' . $consulta->paciente->telefono; // Número del paciente con el prefijo de país

        // Realizar la solicitud a la API de Ultramsg usando cURL
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $ultramsgUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query([
                'token' => $ultramsgToken,
                'to' => $telefonoPaciente,
                'body' => $messageBody,
            ]),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // Ocurrió un error al enviar el mensaje de WhatsApp
            return redirect()->back()->with('error', 'Error al enviar el mensaje de WhatsApp.');
        } else {
            // El mensaje se envió correctamente
            // Actualizar el estatus de la consulta a finalizada (1)
            $consulta->estatus = 1;
            $consulta->save();

            return redirect()->route('doctor.consultas')->with('success', 'Consulta finalizada y receta creada exitosamente. Se envió un mensaje de WhatsApp al paciente.');
        }
    }

    public function editarPerfil()
    {
        // Obtener el doctor autenticado (asumiendo que el modelo para el doctor es Especialistas)
        $doctor = \Auth::user()->especialista;

        // Verificar que el usuario autenticado sea un doctor válido
        if (!$doctor) {
            return redirect()->route('Inicio'); // Puedes redirigir o mostrar un mensaje de error apropiado
        }

        return view('doctor.editarPerfil', compact('doctor'));
    }

    public function actualizarPerfil(Request $request)
    {
        // Validación de los datos del formulario (ajusta las reglas de validación según tus necesidades)
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ejemplo: para subir imágenes
        ]);

        // Obtener el doctor autenticado
        $doctor = Auth::user()->especialista;

        if (!$doctor) {
            return redirect()->route('Inicio'); // Redirecciona o muestra un mensaje de error si no se encuentra el doctor
        }

        // Actualizar los datos del doctor
        $doctor->nombre = $request->input('nombre');
        $doctor->apellidos = $request->input('apellidos');
        $doctor->direccion = $request->input('direccion');
        $doctor->telefono = $request->input('telefono');

        // Subir y guardar la nueva foto de perfil (si se proporciona)
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/fotos_doctores'), $fileName);
            $doctor->foto = 'uploads/fotos_doctores/' . $fileName;
        }

        $doctor->save();

        return redirect()->route('doctor.editarPerfil')->with('success', 'Perfil actualizado correctamente.');
    }

    public function obtenerComentarios($idconsulta)
    {
        try {
            // Actualizar los mensajes del doctor en la consulta del paciente
            Comentario::where('idconsulta', $idconsulta)
            ->where('departede', 1)
            ->update(['estatus' => 2]);

            $consulta = Consulta::with('especialista', 'enfermedad', 'comentarios')->find($idconsulta);
            return response()->json(['consulta' => $consulta]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    // Función para registrar un comentario
    public function registrarComentario(Request $request)
    {
        $idconsulta = $request->input('idconsulta');
        $mensaje = $request->input('mensaje');

       // Obtén la fecha actual
       $zonaHoraria = new DateTimeZone('America/Monterrey'); // Zona horaria de Monterrey, México
       $fechaActual = new DateTime('now', $zonaHoraria);

       // Formatea la fecha en el formato deseado (por ejemplo, 'Y-m-d')
       $fechaFormateada = $fechaActual->format('Y-m-d');

       // Obtén la hora actual
       $horaActual = new DateTime('now', $zonaHoraria);

       // Formatea la hora en el formato deseado (por ejemplo, 'H:i:s')
       $horaFormateada = $horaActual->format('H:i:s');

        // Validar los datos (puedes agregar validaciones según tus necesidades)
        $request->validate([
            'mensaje' => 'required|string|max:255', // Ejemplo de validación, ajusta según tus necesidades
        ]);

        $comentario = new Comentario();
        $comentario->idconsulta = $idconsulta;
        $comentario->comentario = $mensaje;
        $comentario->departede = 0; // Asigna adecuadamente el valor de departede
        $comentario->fecha = $fechaFormateada;
        $comentario->hora = $horaFormateada;
        $comentario->tipo = 1; // Asigna un valor adecuado
        $comentario->estatus = 1; // Asigna un valor adecuado

        // Puedes configurar otros campos del comentario según tus requisitos

        // Intenta guardar el comentario en la base de datos
        $comentario->save();

        // Comentario guardado con éxito
        return response()->json(['mensaje' => 'Comentario registrado con éxito']);
    }


}
