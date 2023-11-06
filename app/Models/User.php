<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tblusuarios';
    protected $primaryKey = 'idusuario'; // Especifica la clave primaria personalizada

    // Definir las columnas que se pueden llenar masivamente
    protected $fillable = [
        'email',
        'password',
        'tipo_usuario',
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';


    /**
     * Set the user's password with hash.
     *
     * @param  string  $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'idusuario');
    }

    public function especialista()
    {
        return $this->hasOne(Especialistas::class, 'idusuario');
    }

    

    // Otras propiedades y métodos del modelo...
}
