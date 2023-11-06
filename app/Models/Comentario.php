<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'tblcomentarios';
    protected $primaryKey = 'idcomentario';

    protected $fillable = [
        'fecha',
        'hora',
        'comentario',
        'tipo',
        'departede',
        'estatus',
        'idconsulta',
    ];

    public $timestamps = false;

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'idconsulta', 'idconsulta');
    }
}
