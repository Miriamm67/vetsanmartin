<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = 'consultas';
    protected $primaryKey = 'id_consulta';
    protected $fillable = ['id_mascota','id_usuario','fecha_hora','motivo','enfermedades_anteriores','tratamientos_recientes','ultima_desparasitacion','peso','temperatura','fc','fr','mucosa','rc_min','inspeccion','palpacion','sintomas','diagnostico','observaciones','evolucion_pronostico','proxima_cita'];
    protected $casts = ['fecha_hora'=>'datetime','proxima_cita'=>'date','ultima_desparasitacion'=>'date'];
    public function mascota() { return $this->belongsTo(Mascota::class, 'id_mascota'); }
    public function veterinario() { return $this->belongsTo(Usuario::class, 'id_usuario'); }
    public function tratamientos() { return $this->hasMany(Tratamiento::class, 'id_consulta'); }
    public function metodos() { return $this->hasMany(MetodoComplementario::class, 'id_consulta'); }
    public function receta() { return $this->hasOne(Receta::class, 'id_consulta'); }
}