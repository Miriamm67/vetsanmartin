<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = 'consultas';
    protected $primaryKey = 'id_consulta';

    protected $fillable = [
        'id_mascota', 'id_usuario', 'id_veterinario', 'fecha_hora', 'motivo',
        'enfermedades_anteriores', 'tratamientos_recientes', 'ultima_desparasitacion',
        'peso', 'temperatura', 'fc', 'fr', 'mucosa', 'rc_min', 'inspeccion',
        'palpacion', 'sintomas', 'diagnostico', 'observaciones',
        'evolucion_pronostico', 'proxima_cita',
    ];

    protected $casts = [
        'fecha_hora'             => 'datetime',
        'ultima_desparasitacion' => 'date',
        'proxima_cita'           => 'date',
    ];

    public function mascota()     { return $this->belongsTo(Mascota::class, 'id_mascota', 'id_mascota'); }
    public function usuario()     { return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario'); }
    public function veterinario() { return $this->belongsTo(Veterinario::class, 'id_veterinario', 'id_veterinario'); }
    public function tratamientos(){ return $this->hasMany(Tratamiento::class, 'id_consulta', 'id_consulta'); }
    public function metodos()     { return $this->hasMany(MetodoComplementario::class, 'id_consulta', 'id_consulta'); }
    public function adjuntos()    { return $this->hasMany(AdjuntoConsulta::class, 'id_consulta', 'id_consulta'); }
    public function receta()      { return $this->hasOne(Receta::class, 'id_consulta', 'id_consulta'); }
}