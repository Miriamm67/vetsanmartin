<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $table = 'tratamientos';
    protected $primaryKey = 'id_tratamiento';
    public $timestamps = false;
    protected $fillable = ['id_consulta','fecha','medicamento','dosis','duracion'];
    protected $casts = ['fecha' => 'date'];
}