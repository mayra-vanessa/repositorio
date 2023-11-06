<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'tblcarrito';
    protected $primaryKey = 'id_carrito';

    protected $fillable = [
        'id_usuario',
    ];

    public function detalles()
    {
        return $this->hasMany(CarritoDetalle::class, 'id_carrito', 'id_carrito');
    }
}
