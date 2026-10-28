<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdjuntoConsulta extends Model
{
    protected $table = 'adjuntos_consultas';
    protected $primaryKey = 'id_adjunto';

    protected $fillable = [
        'id_mascota', 'id_consulta', 'id_usuario', 'categoria',
        'nombre_original', 'ruta_archivo', 'tipo_mime', 'peso_kb',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota', 'id_mascota');
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'id_consulta', 'id_consulta');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}