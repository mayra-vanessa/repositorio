<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Consulta; // Asegúrate de importar el modelo Consulta

class Especialistas extends Model
{
    use HasFactory;

    protected $table = 'tblespecialistas';
    protected $primaryKey = 'idespecialista';

    // Definir las columnas que se pueden llenar masivamente
    protected $fillable = [
        'nombre',
        'apellidos',
        'direccion',
        'telefono',
        'foto',
        'cedulaProfesional',
        'idusuario',
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // Relación con el modelo User (usuario del especialista)
    public function user()
    {
        return $this->belongsTo(User::class, 'idusuario')->select('idusuario', 'email');
    }

    // Obtener el email del usuario a través del atributo dinámico
    public function getUserEmailAttribute()
    {
        return $this->user ? $this->user->email : '';
    }

    // Relación con el modelo Enfermedad (muchas a muchas)
    public function enfermedades()
    {
        return $this->belongsToMany(Enfermedad::class, 'tblespecialista_enfermedad', 'idespecialista', 'idenfermedad');
    }

    // En el modelo Especialistas
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'idespecialista', 'idespecialista');
    }

    // Nuevo método para obtener el nombre completo del especialista
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    // Otras propiedades y métodos del modelo...
}
