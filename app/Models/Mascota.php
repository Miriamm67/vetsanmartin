<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'mascotas';
    protected $primaryKey = 'id_mascota';
    protected $fillable = ['id_propietario','nombre','especie','raza','edad','fecha_nacimiento','sexo','color','senas_particulares','estado'];
    protected $casts = ['fecha_nacimiento' => 'date'];
    public function propietario() { return $this->belongsTo(Propietario::class, 'id_propietario'); }
    public function consultas() { return $this->hasMany(Consulta::class, 'id_mascota')->orderByDesc('fecha_hora'); }
    public function vacunas() { return $this->hasMany(Vacuna::class, 'id_mascota')->orderByDesc('fecha_aplicacion'); }
    public function scopeActivas($q) { return $q->where('estado','activo'); }
}