<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;

    protected $table = 'tblproductos';
    protected $primaryKey = 'idproducto';

    // Definir las columnas que se pueden llenar masivamente
    protected $fillable = [
        'nombreProducto',
        'descripcion',
        'clasificacion',
        'precioVenta',
        'existencias',
        'imagen',
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // Otras propiedades y métodos del modelo...
}
