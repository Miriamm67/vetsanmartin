<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MetodoComplementario extends Model
{
    protected $table = 'metodos_complementarios';
    protected $primaryKey = 'id_metodo';
    public $timestamps = false;
    protected $fillable = ['id_consulta','fecha_orden','examen','resultados'];
    protected $casts = ['fecha_orden' => 'date'];
}