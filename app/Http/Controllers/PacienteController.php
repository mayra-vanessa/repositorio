<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


use App\Models\Enfermedad;
use App\Models\Consulta;
use App\Models\EspecialistaEnfermedad;
use App\Models\Paciente;
use App\Models\Productos;
use App\Models\Carrito;
use App\Models\CarritoDetalle;
use App\Models\Receta;
use App\Models\RecetaDetalle;
use App\Models\Comentario;

class PacienteController extends Controller
{
    //ENFERMEDADES

    public function enfermedades(Request $request)
    {
        // Obtener el término de búsqueda del request
        $search = $request->input('search');

        // Realizar la consulta con el filtro de búsqueda
        $query = Enfermedad::orderBy('idenfermedad', 'desc');

        if ($search) {
            $query->where('idenfermedad', 'LIKE', "%$search%")
                ->orWhere('nombre', 'LIKE', "%$search%")
                ->orWhere('descripcion', 'LIKE', "%$search%");
        }

        $enfermedades = $query->paginate(9);

        return view('paciente.enfermedades', compact('enfermedades', 'search'));
    }


    public function enfermedades_especialistas($idEnfermedad)
    {
        $enfermedad = Enfermedad::findOrFail($idEnfermedad);
    
        // Realizar la consulta de los especialistas relacionados con la enfermedad
        $especialistas = $enfermedad->especialistas()->paginate(9);
    
        return view('paciente.especialistas', compact('enfermedad', 'especialistas'));
    }


    public function hacerConsulta(Request $request, $idenfermedad, $idespecialista)
    {

        // Validar los datos del formulario
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'descripcion' => 'required|string|max:255',
        ]);
    
        // Obtener el paciente autenticado (asumiendo que el modelo para el paciente es Paciente)
        $paciente = \Auth::user()->paciente;
    
        // Verificar que el usuario autenticado sea un paciente válido antes de crear la consulta
        if (!$paciente) {
            // Si el usuario no es un paciente válido, redireccionar o mostrar un mensaje de error
            return redirect()->route('Inicio');
        }
    
        // Crear una nueva consulta y guardarla en la base de datos
        Consulta::create([
            'idespecialista' => $idespecialista,
            'idpaciente' => $paciente->idpaciente,
            'idenfermedad' => $idenfermedad, // Asegúrate de pasar el ID de la enfermedad aquí
            'fecha_consulta' => $request->fecha,
            'hora_consulta' => $request->hora,
            'descripcion' => $request->descripcion,
            'estatus' => 0,
        ]);
    
        // Redireccionar a la vista de "Mis Consultas" con un mensaje de éxito
        return redirect()->route('paciente.consultas')->with('success', 'Consulta realizada exitosamente');
    }

    public function consultas_paciente(Request $request)
    {
        // Obtener el paciente autenticado (asumiendo que el modelo para el paciente es Paciente)
        $paciente = \Auth::user()->paciente;

        // Verificar que el usuario autenticado sea un paciente válido antes de obtener sus consultas
        if (!$paciente) {
            // Si el usuario no es un paciente válido, redireccionar o mostrar un mensaje de error
            return redirect()->route('Inicio'); // Cambiar 'pagina_de_acceso_no_autorizado' por la ruta deseada o mostrar un mensaje de error apropiado
        }

        // Obtener las consultas del paciente a través de la relación definida en el modelo Paciente
        $consultas = $paciente->consultas()->with('enfermedad');

        // Si deseas realizar la búsqueda por texto
        $search = $request->input('search');
        if ($search) {
            $consultas = $consultas->where('descripcion', 'LIKE', "%$search%"); // Puedes agregar más campos si deseas buscar en otros atributos
        }

        // Paginar las consultas para mostrarlas en la vista
        $consultas = $consultas->paginate(10);

        return view('paciente.consultas', compact('consultas', 'search'));
    }



    public function productos(Request $request)
    {
        // Obtener el término de búsqueda del request
        $search = $request->input('search');

        // Realizar la consulta con el filtro de búsqueda
        $query = Productos::orderBy('idproducto', 'desc');

        if ($search) {
            $query->where('nombreProducto', 'LIKE', "%$search%")
                ->orWhere('clasificacion', 'LIKE', "%$search%")
                ->orWhere('descripcion', 'LIKE', "%$search%");
        }

        $productos = $query->paginate(9);

        return view('paciente.productos', compact('productos', 'search'));
    }

    public function verReceta($idconsulta)
    {
        $consulta = Consulta::findOrFail($idconsulta);
        
        $receta = Receta::where('idconsulta', $idconsulta)->first();

        // Obtener todos los productos disponibles (podría ser una consulta más específica)
        $productos = Productos::all();

        return view('paciente.recetas', compact('consulta', 'receta', 'productos'));
    }

    public function guardarPedido(Request $request, $idconsulta)
    {
        $consulta = Consulta::findOrFail($idconsulta);
        $receta = $consulta->receta;

        if ($receta) {
            $productosSeleccionados = $request->input('productosSeleccionados');

            // Verificar si $productosSeleccionados es una cadena y convertirlo a un array si es necesario
            if (is_string($productosSeleccionados)) {
                $productosSeleccionados = explode(',', $productosSeleccionados);
            }

            // Obtener los detalles de la receta
            $detallesReceta = $receta->detalles;

            // Inicializar el total de la receta
            $totalReceta = 0;

            // Recorrer los detalles de la receta y sumar el subtotal de los productos seleccionados
            foreach ($detallesReceta as $detalle) {
                if (in_array($detalle->producto->idproducto, $productosSeleccionados)) {
                    $totalReceta += $detalle->subtotal;
                }
            }

            // Actualizar el total de la receta
            $receta->total = $totalReceta;
            $receta->save();

            // Actualizar el estatus de los productos seleccionados en la tabla "tblrecetasdetalle"
            DB::table('tblrecetasdetalle')
                ->whereIn('idreceta', [$receta->idreceta])
                ->whereIn('idproducto', $productosSeleccionados)
                ->update(['estatus' => 1]);

            // Actualizar el estatus de la receta a 1 (pedido)
            $receta->estatus = 1;
            $receta->save();

            // Actualizar el estatus de la consulta a 2
            $consulta->estatus = 2;
            $consulta->save();
        } else {
            \Log::info("No se encontró la receta asociada a la consulta");
        }

        return redirect()->route('paciente.consultas')->with('success', 'Pedido guardado exitosamente.');
    }


    public function agregarAlCarrito(Request $request)
    {
        // Obtener el ID del producto desde la solicitud AJAX
        $productoId = $request->input('producto_id');
        // Obtener la cantidad del formulario enviado
        $cantidad = $request->input('cantidad');

        // Verificar si el usuario ya tiene un carrito creado
        $carrito = Carrito::where('id_usuario', auth()->user()->id)->first();

        if (!$carrito) {
            // Si el usuario no tiene carrito, crear uno nuevo
            $carrito = new Carrito();
            $carrito->id_usuario = auth()->user()->id;
            $carrito->save();
        }

        // Obtener el producto desde la base de datos
        $producto = Productos::find($productoId);

        // Verificar si el producto existe
        if (!$producto) {
            return redirect()->back()->with('error', 'El producto no existe.');
        }

        // Verificar si el producto ya existe en el carrito
        $detalle = CarritoDetalle::where('id_carrito', $carrito->id_carrito)
                                 ->where('id_producto', $productoId)
                                 ->first();

        if ($detalle) {
            // Si el producto ya existe, actualizar la cantidad
            $detalle->cantidad += $cantidad;
            $detalle->save();
        } else {
            // Si el producto no existe, agregarlo al carrito
            $detalle = new CarritoDetalle();
            $detalle->id_carrito = $carrito->id_carrito;
            $detalle->id_producto = $productoId;
            $detalle->cantidad = $cantidad;
            $detalle->precio_unitario = $producto->precioVenta;
            $detalle->save();
        }

        return redirect()->back()->with('success', 'Producto agregado al carrito exitosamente.');
    }

    public function obtenerComentarios($idconsulta)
    {
        try {
            // Actualizar los mensajes del doctor en la consulta del paciente
            Comentario::where('idconsulta', $idconsulta)
            ->where('departede', 0)
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
        $comentario->departede = 1; // Asigna adecuadamente el valor de departede
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
