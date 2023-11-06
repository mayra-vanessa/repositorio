<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Instituciones;
use App\Models\Especialistas;
use App\Models\Proveedores;
use App\Models\Productos;
use App\Models\Enfermedad;
use App\Models\EspecialistaEnfermedad;
use App\Models\Receta;
use App\Models\RecetaDetalle;

class AdminController extends Controller
{

    // RECETAS PEDIDOS
    public function recetas()
    {
        $recetas = Receta::where('estatus', '>=', 1)->get();
        $productosEnPedido = RecetaDetalle::where('estatus', 1)
            ->whereIn('idreceta', $recetas->pluck('idreceta'))
            ->with('producto')
            ->get();
    
        return view('admin.recetas', compact('recetas', 'productosEnPedido'));
    }

    public function receta_enviarPedido($idreceta)
    {
    
        $receta = Receta::find($idreceta);

        if (!$receta) {
            return redirect()->route('admin.recetas')->with('error', 'Receta no encontrada.');
        }


        // Puedes obtener los productos en estatus 1 de la receta para mostrarlos en el modal
        $productosEnPedido = $receta->detalles->where('estatus', 1);
    
        // Construir el mensaje de WhatsApp con la información del pedido en tránsito
        $messageBody = "¡Hola! Tu pedido va en camino. Detalles del pedido:" . PHP_EOL;
        $totalReceta = 0;
    
        foreach ($productosEnPedido as $detalle) {
            $producto = $detalle->producto;
            $nombreProducto = $producto->nombreProducto;
            $cantidadSeleccionada = $detalle->cantidad;
            $precioProducto = $detalle->precio;
            $subtotal = $detalle->subtotal;
            $totalReceta += $subtotal;
    
            // Agregar detalles del producto al mensaje
            $messageBody .= "Producto: $nombreProducto, Cantidad: $cantidadSeleccionada, Precio: $$precioProducto, Subtotal: $$subtotal" . PHP_EOL;
    
            // Actualizar la cantidad de productos en existencia
            $nuevaCantidad = $producto->existencias - $cantidadSeleccionada;
            if ($nuevaCantidad >= 0) {
                $producto->existencias = $nuevaCantidad;
                $producto->save();
            }
        }
    
        $messageBody .= "Total del pedido: $$totalReceta" . PHP_EOL;
        $messageBody .= "Gracias por tu compra. Pronto recibirás tu pedido.";
    

        //dd($messageBody);

        // Configurar los datos para la solicitud a la API de Ultramsg
        $ultramsgUrl = 'https://api.ultramsg.com/instance55435/messages/chat';
        $ultramsgToken = '2g02i1v2e6jepyv4';
        $telefonoPaciente = '+52' . $receta->consulta->paciente->telefono; // Número del paciente con el prefijo de país
    
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
            // Actualizar el estatus de la receta a 2 (En tránsito)
            $receta->estatus = 2;
            $receta->save();
    
            return redirect()->route('admin.recetas')->with('success', 'Pedido enviado exitosamente.');
        }
    }

    // ENFERMEDADES

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
    
        $enfermedades = $query->paginate(10);
    
        return view('admin.enfermedades', compact('enfermedades', 'search'));
    }
    
    public function enfermedades_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'imagen' => 'required|url',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        Enfermedad::create($request->all());
    
        return redirect()->route('admin.enfermedades')->with('success', 'Enfermedad agregada exitosamente');
    }
    
    public function enfermedades_update(Request $request, $idenfermedad)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'imagen' => 'required|url',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $enfermedad = Enfermedad::findOrFail($idenfermedad);
        $enfermedad->update($request->all());
    
        return redirect()->route('admin.enfermedades')->with('success', 'Enfermedad actualizada exitosamente');
    }

    public function obtenerEnfermedades(Request $request)
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

        $enfermedades = $query->get();

        // Devolver los datos en formato JSON
        return response()->json($enfermedades);
    }

    public function obtenerEnfermedadesRelacionadas($idespecialista)
    {
        
        try {
            // Buscar las relaciones entre el especialista y las enfermedades en la tabla EspecialistaEnfermedad
            $especialistaEnfermedades = EspecialistaEnfermedad::where('idespecialista', $idespecialista)
                ->get();
    
            // Verificar si se encontraron enfermedades relacionadas
            if ($especialistaEnfermedades->isEmpty()) {
                // Si no se encontraron enfermedades relacionadas, devolver un mensaje de error
                return response()->json(['message' => 'No se encontraron enfermedades relacionadas con el especialista.'], 404);
            }
    
            // Obtener las enfermedades relacionadas en un arreglo
            $enfermedadesRelacionadas = $especialistaEnfermedades->pluck('enfermedad');
    
            // Devolver las enfermedades relacionadas como respuesta
            return response()->json($enfermedadesRelacionadas, 200);
        } catch (\Exception $e) {
            // En caso de error, devolver un mensaje de error con el código de estado 500 (Error interno del servidor)
            return response()->json(['message' => 'Ha ocurrido un error al obtener las enfermedades relacionadas.'], 500);
        }
    }
    

     //PRODUCTOS

     public function productos(Request $request)
     {
         // Obtener el término de búsqueda del request
         $search = $request->input('search');
 
         // Realizar la consulta con el filtro de búsqueda
         $query = Productos::orderBy('idproducto', 'desc');
 
         if ($search) {
             $query->where('idproducto', 'LIKE', "%$search%")
                 ->orWhere('nombreProducto', 'LIKE', "%$search%")
                 ->orWhere('descripcion', 'LIKE', "%$search%")
                 ->orWhere('clasificacion', 'LIKE', "%$search%");
         }
 
         $productos = $query->paginate(10);
 
         return view('admin.productos', compact('productos', 'search'));
     }
 
     public function productos_store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'nombreProducto' => 'required',
             'descripcion' => 'required',
             'clasificacion' => 'required',
             'precioVenta' => 'required',
             'existencias' => 'required',
             'imagen' => 'required|url',
         ]);
 
        
 
         Productos::create($request->all());
 
         return redirect()->route('admin.productos')->with('success', 'producto agregado exitosamente');
     }
 
     public function productos_update(Request $request, $idproducto)
     {
         $validator = Validator::make($request->all(), [
             'nombreProducto' => 'required',
             'descripcion' => 'required',
             'clasificacion' => 'required',
             'precioVenta' => 'required',
             'existencias' => 'required',
             'imagen' => 'required|url',
         ]);
 
         if ($validator->fails()) {
             return redirect()->back()->withErrors($validator)->withInput();
         }
 
         $productos = Productos::findOrFail($idproducto);
         $productos->update($request->all());
 
         return redirect()->route('admin.productos')->with('success', 'Producto actualizado exitosamente');
     }

      //ESPECIALISTAS

    public function especialistas(Request $request)
    {
        // Obtener el término de búsqueda del request
        $search = $request->input('search');

        // Realizar la consulta con el filtro de búsqueda
        $query = Especialistas::orderBy('idespecialista', 'desc')->with('user:idusuario,email');

        if ($search) {
            $query->where('idespecialista', 'LIKE', "%$search%")
                ->orWhere('nombre', 'LIKE', "%$search%")
                ->orWhere('apellidos', 'LIKE', "%$search%")
                ->orWhere('direccion', 'LIKE', "%$search%")
                ->orWhere('telefono', 'LIKE', "%$search%")
                ->orWhere('cedulaProfesional', 'LIKE', "%$search%");
        }

        $especialistas = $query->paginate(10);

        return view('admin.especialistas', compact('especialistas', 'search'));
    }

    public function especialistas_store(Request $request)
    {   
        // Asegurarse de que lleguen las enfermedades seleccionadas
        \Log::info($request->input('enfermedades'));
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'foto' => 'required|url',
            'cedula' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Crear el usuario
        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
            'tipo_usuario' => 2,
        ]);

        // Obtener el ID del usuario recién creado
        $userId = $user->id;

        // Crear un nuevo especialista y asociarlo con el usuario
        $especialista = new Especialistas([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'foto' => $request->foto,
            'cedulaProfesional' => $request->cedula,
            'user_id' => $userId, // Asignar el ID del usuario al especialista
        ]);

        $user->especialista()->save($especialista);

       // Obtener las enfermedades seleccionadas como un arreglo JSON
        $enfermedadesSeleccionadas = json_decode($request->input('enfermedades'));

        // Guardar el especialista en la tabla tblespecialista_enfermedad
        if ($enfermedadesSeleccionadas && is_array($enfermedadesSeleccionadas)) {
            $especialista->enfermedades()->attach($enfermedadesSeleccionadas);
        }
        
        return redirect()->route('admin.especialistas')->with('success', 'Especialista agregado exitosamente');
    }

    public function especialistas_update(Request $request, $idespecialista)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'foto' => 'required|url',
            'cedula' => 'required',
            'password' => 'nullable|string|min:8', // Ajusta las reglas de validación según tus requisitos
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $especialista = Especialistas::findOrFail($idespecialista);

        // Actualizar los datos del especialista
        $especialista->update($request->except('password'));

        // Obtener el usuario asociado al especialista
        $usuario = $especialista->user;

        // Verificar si se proporcionó una nueva contraseña
        if ($request->filled('password')) {
            $passwordValidator = Validator::make($request->all(), [
                'password' => 'required|string|min:8', // Ajusta las reglas de validación para la contraseña según tus requisitos
            ]);

            if ($passwordValidator->fails()) {
                return redirect()->back()->withErrors($passwordValidator)->withInput();
            }

            // Verificar si el usuario existe
            if ($usuario) {
                // Actualizar la contraseña del usuario asociado
                $usuario->password = bcrypt($request->password);
                $usuario->save();
            } else {
                // El usuario no existe, puedes manejar el error de alguna forma
                return redirect()->back()->withErrors(['password' => 'El usuario asociado no existe'])->withInput();
            }
        }

        // Actualizar la cédula del especialista
        $especialista->cedulaProfesional = $request->cedula;
        $especialista->save();

        // Obtener las enfermedades relacionadas seleccionadas
        $enfermedadesSeleccionadas = json_decode($request->input('enfermedades'));

        // Obtener las enfermedades relacionadas actuales del especialista
        $enfermedadesActuales = $especialista->enfermedades->pluck('idenfermedad')->toArray();

        // Calcular las enfermedades a eliminar
        $enfermedadesEliminar = array_diff($enfermedadesActuales, $enfermedadesSeleccionadas);

        // Calcular las enfermedades a agregar
        $enfermedadesAgregar = array_diff($enfermedadesSeleccionadas, $enfermedadesActuales);

        // Eliminar las enfermedades que ya no están relacionadas con el especialista
        $especialista->enfermedades()->detach($enfermedadesEliminar);

        // Agregar las nuevas enfermedades relacionadas al especialista
        $especialista->enfermedades()->attach($enfermedadesAgregar);

        return redirect()->route('admin.especialistas')->with('success', 'Especialista actualizado exitosamente');
    }


    //INTITUCIONES

    public function instituciones(Request $request)
    {
        // Obtener el término de búsqueda del request
        $search = $request->input('search');

        // Realizar la consulta con el filtro de búsqueda
        $query = Instituciones::orderBy('idinstitucion', 'desc');

        if ($search) {
            $query->where('idinstitucion', 'LIKE', "%$search%")
                ->orWhere('nombre', 'LIKE', "%$search%")
                ->orWhere('direccion', 'LIKE', "%$search%")
                ->orWhere('telefono', 'LIKE', "%$search%")
                ->orWhere('correo', 'LIKE', "%$search%");
        }

        $instituciones = $query->paginate(10);

        return view('admin.instituciones', compact('instituciones', 'search'));
    }

    public function instituciones_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email',
            'imagen' => 'required|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Instituciones::create($request->all());

        return redirect()->route('admin.instituciones')->with('success', 'Institución agregada exitosamente');
    }

    public function instituciones_update(Request $request, $idinstitucion)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email',
            'imagen' => 'required|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $institucion = Instituciones::findOrFail($idinstitucion);
        $institucion->update($request->all());

        return redirect()->route('admin.instituciones')->with('success', 'Institución actualizada exitosamente');
    }

    

    //PROVEEDORES

    public function proveedores(Request $request)
    {
        // Obtener el término de búsqueda del request
        $search = $request->input('search');

        // Realizar la consulta con el filtro de búsqueda
        $query = Proveedores::orderBy('idproveedor', 'desc');

        if ($search) {
            $query->where('idproveedor', 'LIKE', "%$search%")
                ->orWhere('nombre', 'LIKE', "%$search%")
                ->orWhere('apellidos', 'LIKE', "%$search%")
                ->orWhere('nombreEmpresa', 'LIKE', "%$search%")
                ->orWhere('direccion', 'LIKE', "%$search%")
                ->orWhere('correo', 'LIKE', "%$search%")
                ->orWhere('telefono', 'LIKE', "%$search%");
        }

        $proveedores = $query->paginate(10);

        return view('admin.proveedores', compact('proveedores', 'search'));
    }

    public function proveedores_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'nombreEmpresa' => 'required',
            'direccion' => 'required',
            'correo' => 'required|email',
            'telefono' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Proveedores::create($request->all());

        return redirect()->route('admin.proveedores')->with('success', 'Proveedor agregado exitosamente');
    }

    public function proveedores_update(Request $request, $idproveedor)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'nombreEmpresa' => 'required',
            'direccion' => 'required',
            'correo' => 'required|email',
            'telefono' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $proveedor = Proveedores::findOrFail($idproveedor);
        $proveedor->update($request->all());

        return redirect()->route('admin.proveedores')->with('success', 'Proveedor actualizado exitosamente');
    }

   

}
