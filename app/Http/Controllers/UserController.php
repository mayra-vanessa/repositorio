<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Paciente;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'tipo_usuario' => $request->tipo_usuario,
        ];

        if (Auth::attempt($credentials)) {
            // Autenticación exitosa
            $user = Auth::user();

            // Datos que deseas almacenar en localStorage
            $dataToStore = [
                'idusuario' => $user->idusuario,
                'email' => $user->email,
                'tipo_usuario' => $user->tipo_usuario,
                // Agrega más datos según tus necesidades
            ];

            //dd($dataToStore);

            // Redirigir al usuario y pasar los datos como JSON en la respuesta
            return redirect()->route('Inicio')->with('userData', json_encode($dataToStore));
        }

        // Autenticación fallida
        return redirect()->back()->with('status', 'Credenciales inválidas');
    }

    public function registro(Request $request)
    {
        // Validar los datos del formulario de registro
        $request->validate([
            'email' => 'required|email|unique:tblusuarios',
            'password' => 'required|min:8',
            'nombre' => 'required',
            'telefono' => 'required',
        ]);

        // Validar el número de teléfono utilizando la API de Numverify
        $url = 'http://apilayer.net/api/validate?access_key=89045f1b7f8a1dd6cf7faa144136a585&number=' . $request->telefono . '&country_code=MX&format=1';
        $response = @file_get_contents($url); // Usamos @ para suprimir los errores
        $responseData = json_decode($response, true);

        // Verificar si la respuesta contiene el estado de validación y si el número de teléfono es válido
        if (!isset($responseData['valid']) || !$responseData['valid']) {
            // El número de teléfono no es válido, redirigir a la vista de registro con un mensaje de error
            return redirect()->route('Login')->with('error', 'El número de teléfono no es válido');
        }

        // Crear un nuevo usuario
        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
            'tipo_usuario' => 3, // Tipo de usuario predefinido
        ]);

        // Obtener el ID del usuario recién creado
        $userId = $user->id;

        // Crear un nuevo paciente y asociarlo con el usuario
        $paciente = new Paciente([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono, // Guardar el número de teléfono en el paciente
            'user_id' => $userId, // Asignar el ID del usuario al paciente
        ]);

        $user->paciente()->save($paciente);

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Redireccionar a la página de bienvenida después del registro
        return redirect()->route('Inicio')->with('success', 'Usuario y paciente creados correctamente');
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('Login');
    }

}
