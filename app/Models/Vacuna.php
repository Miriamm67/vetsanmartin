<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    private const OPCIONES_POR_ESPECIE = [
        'perro' => [
            'Polivalente canina (quíntuple)',
            'Polivalente canina (séxtuple)',
            'Polivalente canina (óctuple)',
            'Rabia',
            'Bordetella (tos de las perreras)',
            'Leptospirosis',
            'Influenza canina',
        ],
        'gato' => [
            'Triple felina (RCP)',
            'Cuádruple felina',
            'Leucemia felina (FeLV)',
            'Rabia',
            'Clamidiosis felina',
            'Bordetella felina',
        ],
    ];

    protected $table = 'vacunas';
    protected $primaryKey = 'id_vacuna';

    protected $fillable = [
        'id_mascota', 'tipo_vacuna', 'lote', 'fecha_aplicacion',
        'fecha_proxima_dosis', 'estado_proxima', 'observacion',
    ];

    protected $casts = [
        'fecha_aplicacion'    => 'date',
        'fecha_proxima_dosis' => 'date',
    ];

    public static function opcionesPorEspecie(string $especie): array
    {
        return self::OPCIONES_POR_ESPECIE[strtolower($especie)] ?? [];
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota', 'id_mascota');
    }

    /** Alertas: dosis pendientes con próxima fecha en ≤ 30 días */
    public function scopeProximas($query)
    {
        return $query->where('estado_proxima', 'pendiente')
                     ->whereNotNull('fecha_proxima_dosis')
                     ->where('fecha_proxima_dosis', '<=', now()->addDays(30))
                     ->orderBy('fecha_proxima_dosis');
    }

    /** Dosis pendientes ya vencidas */
    public function scopeVencidas($query)
    {
        return $query->where('estado_proxima', 'pendiente')
                     ->whereNotNull('fecha_proxima_dosis')
                     ->where('fecha_proxima_dosis', '<', now());
    }
}