<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;

    protected $table = 'tblrecetas';
    protected $primaryKey = 'idreceta';
    protected $fillable = [
        'idconsulta',
        'total', // Agrega otros campos de la tabla tblrecetas si los necesitas
    ];

    public function detalles()
    {
        return $this->hasMany(RecetaDetalle::class, 'idreceta', 'idreceta');
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'idconsulta');
    }

    // Agregar relaciones con otras tablas o métodos adicionales si es necesario
}
