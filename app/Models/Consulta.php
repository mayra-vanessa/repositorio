<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = 'tblconsultas';
    protected $primaryKey = 'idconsulta';
    public $timestamps = true;

    // Atributos que se pueden asignar masivamente
    protected $fillable = ['idespecialista', 'idpaciente', 'idenfermedad', 'fecha_consulta', 'hora_consulta', 'descripcion', 'estatus'];

    // Relación con el modelo Especialista
    public function especialista()
    {
        return $this->belongsTo(Especialistas::class, 'idespecialista', 'idespecialista');
    }

    // Relación con el modelo Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idpaciente', 'idpaciente');
    }

    // Definimos la relación con la tabla Enfermedad
    public function enfermedad()
    {
        return $this->belongsTo(Enfermedad::class, 'idenfermedad');
    }

    public function receta()
    {
        return $this->hasOne(Receta::class, 'idconsulta');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'idconsulta', 'idconsulta');
    }
}
