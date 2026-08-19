<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = ['nombre_usuario','contrasena_hash','rol','id_propietario','estado'];
    protected $hidden = ['contrasena_hash','remember_token'];

    // Le dice a Laravel que la contraseña vive en 'contrasena_hash' (no en 'password')
    public function getAuthPassword()
    {
        return $this->contrasena_hash;
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'id_propietario');
    }
}