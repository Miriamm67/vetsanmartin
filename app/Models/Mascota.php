<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'mascotas';
    protected $primaryKey = 'id_mascota';

    protected $fillable = [
        'id_propietario', 'nombre', 'especie', 'raza', 'edad',
        'fecha_nacimiento', 'sexo', 'color', 'senas_particulares', 'foto', 'estado',
    ];

    protected $casts = ['fecha_nacimiento' => 'date'];

    public function propietario() { return $this->belongsTo(Propietario::class, 'id_propietario', 'id_propietario'); }
    public function consultas()   { return $this->hasMany(Consulta::class, 'id_mascota', 'id_mascota'); }
    public function vacunas()     { return $this->hasMany(Vacuna::class, 'id_mascota', 'id_mascota'); }
    public function adjuntos()    { return $this->hasMany(AdjuntoConsulta::class, 'id_mascota', 'id_mascota'); }

    public function scopeActivos($query) { return $query->where('estado', 'activo'); }
        /** Alias usado por el dashboard: mascotas con estado activo */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
    }
}