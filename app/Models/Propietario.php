<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    protected $table = 'propietarios';
    protected $primaryKey = 'id_propietario';
    protected $fillable = ['nombre_completo','telefono','direccion','zona','calle_av','numero','nro_registro','estado'];
    public function mascotas() { return $this->hasMany(Mascota::class, 'id_propietario'); }
    public function scopeActivos($q) { return $q->where('estado','activo'); }
}