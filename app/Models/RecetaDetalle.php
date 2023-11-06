<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecetaDetalle extends Model
{
    use HasFactory;

    protected $table = 'tblrecetasdetalle';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idreceta',
        'idproducto',
        'cantidad',
        'precio',
        'subtotal', // Agrega otros campos de la tabla tblrecetasdetalle si los necesitas
    ];

    public $timestamps = false;

    // Agregar relaciones con otras tablas o métodos adicionales si es necesario

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'idproducto');
    }

}
