<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instituciones extends Model
{
    use HasFactory;

    protected $table = 'tblinstituciones';
    protected $primaryKey = 'idinstitucion';

    // Definir las columnas que se pueden llenar masivamente
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'correo',
        'imagen',
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // Otras propiedades y métodos del modelo...
}
