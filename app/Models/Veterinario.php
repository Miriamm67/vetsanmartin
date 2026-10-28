<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veterinario extends Model
{
    protected $table = 'veterinarios';
    protected $primaryKey = 'id_veterinario';

    protected $fillable = [
        'id_usuario', 'nombre_completo', 'nro_colegiado', 'especialidad', 'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'id_veterinario', 'id_veterinario');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}