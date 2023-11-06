<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspecialistaEnfermedad extends Model
{
    use HasFactory;

    protected $table = 'tblespecialista_enfermedad';
    protected $primaryKey = 'idee';

    protected $fillable = [
        'idespecialista',
        'idenfermedad',
        // Agrega aquí los campos adicionales que quieras guardar en la tabla tblespecialista_enfermedad
    ];

    // Relación con el modelo Especialista
    public function especialista()
    {
        return $this->belongsTo(Especialistas::class, 'idespecialista', 'idespecialista');
    }

    // Relación con el modelo Enfermedad
    public function enfermedad()
    {
        return $this->belongsTo(Enfermedad::class, 'idenfermedad', 'idenfermedad');
    }
}
