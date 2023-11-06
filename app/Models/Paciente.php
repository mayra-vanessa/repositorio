<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'tblpacientes';
    protected $primaryKey = 'idpaciente';

    // Definir las columnas que se pueden llenar masivamente
    protected $fillable = [
        'nombre',
        'telefono',
        'apellidos',
        'fechaNacimiento',
        'direccion',
        'telefono',
        'sexo',
        'idusuario',
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // Definir la relación con las consultas
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'idpaciente', 'idpaciente');
    }

    // Nuevo método para obtener el nombre completo del especialista
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    // Otras propiedades y métodos del modelo...
}
