<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    protected $table = 'vacunas';
    protected $primaryKey = 'id_vacuna';
    protected $fillable = ['id_mascota','tipo_vacuna','lote','fecha_aplicacion','fecha_proxima_dosis'];
    protected $casts = ['fecha_aplicacion'=>'date','fecha_proxima_dosis'=>'date'];
    public function mascota() { return $this->belongsTo(Mascota::class, 'id_mascota'); }
    public function scopeProximas($q) {
        return $q->whereNotNull('fecha_proxima_dosis')->where('fecha_proxima_dosis','<=',now()->addDays(30));
    }
}