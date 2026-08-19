<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'recetas';
    protected $primaryKey = 'id_receta';
    protected $fillable = ['id_consulta','notas'];
    public function consulta() { return $this->belongsTo(Consulta::class, 'id_consulta'); }
}