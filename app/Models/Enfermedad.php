<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    protected $table = 'tblenfermedades';
    protected $primaryKey = 'idenfermedad';
    public $timestamps = false;

    // Atributos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'descripcion', 'imagen'];

    // Relación muchos a muchos con el modelo Especialista
    public function especialistas()
    {
        return $this->belongsToMany(Especialistas::class, 'tblespecialista_enfermedad', 'idenfermedad', 'idespecialista');
    }
}
