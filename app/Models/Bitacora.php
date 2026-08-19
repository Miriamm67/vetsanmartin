<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacoras';
    protected $primaryKey = 'id_bitacora';
    public $timestamps = false;
    protected $fillable = ['id_usuario','tabla','registro_id','accion'];
}