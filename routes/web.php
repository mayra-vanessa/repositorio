<?php

use Illuminate\Support\Facades\Route;
//controladores
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\DoctorController;


//Rutas Generales
Route::get('/', function () {
    return view('welcome');
})->name('Inicio');

Route::get('Cockies', function () {
    return view('public.Cockies_Aviso');
})->name('Cockies');

Route::get('Aviso_de_Privacidad', function () {
    return view('public.Aviso_Privacidad');
})->name('Aviso');

Route::get('Contactanos', function () {
    return view('public.Contactanos');
})->name('Contactanos');

Route::get('Informacion', function () {
    return view('public.Informacion');
})->name('Informacion');

Route::get('Quienes_Somos', function () {
    return view('public.QuienesSomos');
})->name('QuienesSomos');

//Rutas de Inicio de sesion Usuario
Route::get('Login', function () {
    return view('public.Registro');
})->name('Login');

Route::post('Login', [UserController::class, 'login'])->name('login.submit');
Route::post('Registro', [UserController::class, 'registro'])->name('registro.submit');
Route::post('logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('admin')->prefix('/admin')->group(function () {
    //Rutas para Enfermedades
    Route::get('/Enfermedades', [AdminController::class, 'enfermedades'])->name('admin.enfermedades');
    Route::post('/Enfermedades/Agregar', [AdminController::class, 'enfermedades_store'])->name('enfermedades.store');
    Route::put('/Enfermedades/{idenfermedad}/Actualizar', [AdminController::class, 'enfermedades_update'])->name('enfermedades.update');
    Route::get('/ObtenerEnfermedades', [AdminController::class, 'obtenerEnfermedades'])->name('admin.obtenerEnfermedades');

    //Rutas para Especialistas
    Route::get('/Especialistas', [AdminController::class, 'especialistas'])->name('admin.especialistas');
    Route::post('/Especialistas/Agregar', [AdminController::class, 'especialistas_store'])->name('especialistas.store');
    Route::put('/Especialistas/{idespecialista}/Actualizar', [AdminController::class, 'especialistas_update'])->name('especialistas.update');
    Route::get('/Especialistas/{idespecialista}/enfermedades', [AdminController::class, 'obtenerEnfermedadesRelacionadas'])->name('especialistas.enfermedades');

    //Rutas para Productos
    Route::get('/Productos', [AdminController::class, 'productos'])->name('admin.productos');
    Route::post('/Productos/Agregar', [AdminController::class, 'productos_store'])->name('productos.store');
    Route::put('/Productos/{idproducto}/Actualizar', [AdminController::class, 'productos_update'])->name('productos.update');

    //Rutas para Instituciones
    Route::get('/Instituciones', [AdminController::class, 'instituciones'])->name('admin.instituciones');
    Route::post('/Instituciones/Agregar', [AdminController::class, 'instituciones_store'])->name('instituciones.store');
    Route::put('/Instituciones/{idinstitucion}/Actualizar', [AdminController::class, 'instituciones_update'])->name('instituciones.update');

    //Rutas para Proveedores
    Route::get('/Proveedores', [AdminController::class, 'proveedores'])->name('admin.proveedores');
    Route::post('/Proveedores/Agregar', [AdminController::class, 'proveedores_store'])->name('proveedores.store');
    Route::put('/Proveedores/{idproveedor}/Actualizar', [AdminController::class, 'proveedores_update'])->name('proveedores.update');

    Route::get('/Recetas', [AdminController::class, 'recetas'])->name('admin.recetas');
    Route::post('/Recetas/{idreceta}/EnviarPedido', [AdminController::class, 'receta_enviarPedido'])->name('receta.enviarPedido');

});


Route::middleware('doctor')->prefix('/doctor')->group(function () {
    // Rutas para Consultas
    Route::get('/Consultas', [DoctorController::class, 'consultas_especialistas'])->name('doctor.consultas');

    // Ruta para crear recetas
    Route::get('/Consulta/{idconsulta}/Receta', [DoctorController::class, 'recetas_vista'])->name('doctor.recetas');
    Route::post('/Consulta/{idconsulta}/Finalizar', [DoctorController::class, 'finalizarConsulta'])->name('consulta.finalizar');

    Route::get('/Editar', [DoctorController::class, 'editarPerfil'])->name('doctor.editarPerfil');

    Route::put('/Actualizar', [DoctorController::class, 'actualizarPerfil'])->name('doctor.actualizarPerfil');

    // Ruta para ver los comentarios de una consulta
    Route::get('/Consulta/{idconsulta}/Comentarios', [DoctorController::class, 'obtenerComentarios'])->name('comentarios.doctor.ver');

    // Ruta para agregar comentarios a una consulta
    Route::post('/Consulta/{idconsulta}/Comentarios/Agregar', [DoctorController::class, 'registrarComentario'])->name('comentarios.doctor.agregar');
});


// Rutas protecgidas del paciente
Route::middleware('paciente')->group(function () {
    //Rutas para Enfermedades
    Route::get('/Enfermedades', [PacienteController::class, 'enfermedades'])->name('enfermedades');
    Route::get('/Enfermedades/{idenfermedad}/Especialistas', [PacienteController::class, 'enfermedades_especialistas'])->name('enfermedades.especialistas');
    
    //Rutas para Consultas
    Route::post('/Enfermedades/{idenfermedad}/Especialistas/{idespecialista}/consulta', [PacienteController::class, 'hacerConsulta'])->name('consulta.hacer');
    Route::get('/Consultas', [PacienteController::class, 'consultas_paciente'])->name('paciente.consultas');

    //Rutas para Recetas
    Route::get('/Consultas/{idconsulta}/Receta', [PacienteController::class, 'verReceta'])->name('receta.ver');
    Route::post('/Consultas/{idconsulta}/Receta/GuardarPedido', [PacienteController::class, 'guardarPedido'])->name('pedido.guardar');

    //Rutas para Productos
    Route::get('/Medicamentos', [PacienteController::class, 'productos'])->name('productos');
    Route::post('/Agregaralcarrito', [PacienteController::class, 'agregarAlCarrito'])->name('carrito.agregar');

    Route::get('/Editar', [PacienteController::class, 'editarPerfil'])->name('paciente.editarPerfil');

    Route::get('/Consultas/{idconsulta}/Comentarios', [PacienteController::class, 'obtenerComentarios'])->name('comentarios.ver');
    Route::post('/Consultas/{idconsulta}/Comentarios/Agregar', [PacienteController::class, 'registrarComentario'])->name('comentarios.agregar');

});
