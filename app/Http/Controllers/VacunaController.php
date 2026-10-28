<?php

namespace App\Http\Controllers;

use App\Models\Vacuna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacunaController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_mascota'          => 'required|exists:mascotas,id_mascota',
            'tipo_vacuna'         => 'required|string|max:80',
            'tipo_vacuna_otra'    => 'required_if:tipo_vacuna,__otra__|nullable|string|max:80',
            'lote'                => 'nullable|string|max:40',
            'fecha_aplicacion'    => 'required|date|before_or_equal:today',
            'fecha_proxima_dosis' => 'nullable|date|after:fecha_aplicacion',
        ]);

        if ($data['tipo_vacuna'] === '__otra__') {
            $data['tipo_vacuna'] = $data['tipo_vacuna_otra'];
        }
        unset($data['tipo_vacuna_otra']);

        DB::transaction(function () use ($data) {
            Vacuna::where('id_mascota', $data['id_mascota'])
                  ->where('tipo_vacuna', $data['tipo_vacuna'])
                  ->where('estado_proxima', 'pendiente')
                  ->update([
                      'estado_proxima' => 'aplicada',
                      'observacion'    => 'Dosis completada con nueva aplicación.',
                  ]);

            Vacuna::create($data + ['estado_proxima' => 'pendiente']);
        });

        return back()->with('ok', 'Vacuna registrada; la dosis pendiente anterior quedó como APLICADA.');
    }

    public function edit($id)
    {
        $vacuna = Vacuna::with('mascota')->findOrFail($id);
        $opcionesVacuna = Vacuna::opcionesPorEspecie($vacuna->mascota->especie);
        return view('vacunas.edit', compact('vacuna', 'opcionesVacuna'));
    }

    public function update(Request $request, $id)
    {
        $vacuna = Vacuna::findOrFail($id);
        $data = $request->validate([
            'tipo_vacuna'         => 'required|string|max:80',
            'tipo_vacuna_otra'    => 'required_if:tipo_vacuna,__otra__|nullable|string|max:80',
            'lote'                => 'nullable|string|max:40',
            'fecha_aplicacion'    => 'required|date|before_or_equal:today',
            'fecha_proxima_dosis' => 'nullable|date|after:fecha_aplicacion',
            'estado_proxima'      => 'required|in:pendiente,aplicada,omitida',
            'observacion'         => 'nullable|string|max:200',
        ]);
        if ($data['tipo_vacuna'] === '__otra__') {
            $data['tipo_vacuna'] = $data['tipo_vacuna_otra'];
        }
        unset($data['tipo_vacuna_otra']);

        $vacuna->update($data);
        return redirect()->route('admin.mascotas.show', $vacuna->id_mascota)
                         ->with('ok', 'Vacuna actualizada correctamente.');
    }

    public function marcarAplicada(Request $request, $id)
    {
        $vacuna = Vacuna::findOrFail($id);
        $data = $request->validate([
            'fecha_aplicacion' => 'required|date|before_or_equal:today',
        ]);

        if ($vacuna->estado_proxima !== 'pendiente') {
            return back()->with('error', 'Solo se pueden marcar como aplicadas las dosis pendientes.');
        }

        $aplicadaAntes = $vacuna->fecha_proxima_dosis
            && $data['fecha_aplicacion'] < $vacuna->fecha_proxima_dosis->toDateString();

        $vacuna->update([
            'fecha_aplicacion' => $data['fecha_aplicacion'],
            'estado_proxima' => 'aplicada',
            'observacion' => $aplicadaAntes
                ? 'Dosis aplicada antes de la fecha programada.'
                : 'Dosis aplicada.',
        ]);

        return back()->with('ok', $aplicadaAntes
            ? 'Dosis marcada como aplicada antes de la fecha programada.'
            : 'Dosis marcada como aplicada.');
    }

    public function marcarOmitida(Request $request, $id)
    {
        $vacuna = Vacuna::findOrFail($id);
        $request->validate([
            'observacion' => 'nullable|string|max:200',
            'nueva_fecha' => 'nullable|date|after:today',
        ]);

        if ($request->filled('nueva_fecha')) {
            $vacuna->update([
                'fecha_proxima_dosis' => $request->nueva_fecha,
                'estado_proxima'      => 'pendiente',
                'observacion'         => $request->observacion ?: 'El propietario no asistió; dosis reprogramada.',
            ]);
            $mensaje = 'Dosis reprogramada correctamente.';
        } else {
            $vacuna->update([
                'estado_proxima' => 'omitida',
                'observacion'    => $request->observacion ?: 'El propietario no acudió a la fecha programada.',
            ]);
            $mensaje = 'Dosis marcada como OMITIDA.';
        }
        return back()->with('ok', $mensaje);
    }
}