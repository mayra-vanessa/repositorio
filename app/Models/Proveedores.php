<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    use HasFactory;

    protected $table = 'tblproveedores';
    protected $primaryKey = 'idproveedor';

    // Definir las columnas que se pueden llenar masivamente
    protected $fillable = [
        'nombre',
        'apellidos',
        'nombreEmpresa',
        'direccion',
        'correo',
        'telefono',
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // Otras propiedades y métodos del modelo...
}
