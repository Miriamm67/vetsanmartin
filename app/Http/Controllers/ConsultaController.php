<?php

namespace App\Http\Controllers;

use App\Models\AdjuntoConsulta;
use App\Models\Consulta;
use App\Models\Mascota;
use App\Models\Veterinario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ConsultaController extends Controller
{
    /**
     * UC05.5 – Listado de consultas con filtros por fecha y veterinario.
     * Ruta: admin.consultas.index
     */
    public function index(Request $request)
    {
        $consultas = Consulta::with(['mascota.propietario', 'veterinario'])
            ->when($request->filled('fecha'), fn ($q) => $q->whereDate('fecha_hora', $request->fecha))
            ->when($request->filled('desde'), fn ($q) => $q->whereDate('fecha_hora', '>=', $request->desde))
            ->when($request->filled('hasta'), fn ($q) => $q->whereDate('fecha_hora', '<=', $request->hasta))
            ->when($request->filled('id_veterinario'), fn ($q) => $q->where('id_veterinario', $request->id_veterinario))
            ->orderByDesc('fecha_hora')
            ->paginate(15)
            ->withQueryString();

        $veterinarios = Veterinario::activos()->orderBy('nombre_completo')->get();

        return view('consultas.index', compact('consultas', 'veterinarios'));
    }

    /**
     * Muestra el formulario de nueva consulta (si existe vista independiente).
     * Ruta: admin.consultas.create
     */
    public function create(Request $request)
    {
        $mascotaId = $request->input('id_mascota', $request->input('mascota_id'));
        if (!$mascotaId) {
            return redirect()->route('admin.mascotas.index')
                             ->with('error', 'Debe seleccionar una mascota para registrar una consulta.');
        }

        $mascota = Mascota::with('propietario')->findOrFail($mascotaId);
        $veterinarios = Veterinario::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        return view('consultas.form', [
            'c' => new Consulta(),
            'mascotas' => collect([$mascota]),
            'veterinarios' => $veterinarios,
        ]);
    }

    /**
     * Registro con TRANSACCIÓN + validaciones estrictas + adjuntos.
     * Ruta: admin.consultas.store
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->reglas());
        unset(
            $data['genera_receta'],
            $data['notas_receta'],
            $data['tratamientos'],
            $data['metodos'],
            $data['archivos'],
            $data['categoria']
        );

        try {
            DB::transaction(function () use ($request, $data) {

                // 1) Crear la consulta
                $consulta = Consulta::create($data + ['id_usuario' => auth()->id()]);

                // Guardar el veterinario explícitamente si viene en el request
                if ($request->filled('id_veterinario')) {
                    $consulta->id_veterinario = $request->input('id_veterinario');
                    $consulta->save();
                }

                // 2) Tratamientos: ignora filas vacías, exige medicamento si hay dosis
                foreach ($request->tratamientos ?? [] as $t) {
                    if (blank($t['medicamento'] ?? null) && blank($t['dosis'] ?? null)) {
                        continue;
                    }
                    if (blank($t['medicamento'] ?? null)) {
                        throw new \Exception('Toda fila de tratamiento con dosis debe indicar el medicamento.');
                    }
                    $consulta->tratamientos()->create([
                        'fecha'       => $t['fecha'] ?? now()->toDateString(),
                        'medicamento' => $t['medicamento'],
                        'dosis'       => $t['dosis'] ?? null,
                        'duracion'    => $t['duracion'] ?? null,
                    ]);
                }

                // 3) Métodos complementarios
                foreach ($request->metodos ?? [] as $m) {
                    if (blank($m['examen'] ?? null)) {
                        continue;
                    }
                    $consulta->metodos()->create([
                        'fecha_orden' => $m['fecha_orden'] ?? null,
                        'examen'      => $m['examen'],
                        'resultados'  => $m['resultados'] ?? null,
                    ]);
                }

                // 4) Adjuntos con creación automática de carpeta
                foreach ($request->file('archivos', []) as $i => $file) {
                    $destino = $this->uploadBase() . '/adjuntos';
                    File::ensureDirectoryExists($destino);

                    $nombre = 'adj_' . time() . '_' . $i . '.' . $file->getClientOriginalExtension();
                    $file->move($destino, $nombre);

                    AdjuntoConsulta::create([
                        'id_mascota'      => $data['id_mascota'],
                        'id_consulta'     => $consulta->id_consulta,
                        'id_usuario'      => auth()->id(),
                        'categoria'       => ($request->categoria[$i] ?? 'otro'),
                        'nombre_original' => $file->getClientOriginalName(),
                        'ruta_archivo'    => 'uploads/adjuntos/' . $nombre,
                        'tipo_mime'       => $file->getClientMimeType(),
                        'peso_kb'         => (int) round($file->getSize() / 1024),
                    ]);
                }

                // 5) Receta opcional
                if ($request->boolean('genera_receta')) {
                    $consulta->receta()->create(['notas' => $request->notas_receta]);
                }
            });
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'No se pudo registrar la consulta: ' . $e->getMessage());
        }

        return redirect()->route('admin.mascotas.show', $data['id_mascota'])
                         ->with('ok', 'Consulta registrada correctamente.');
    }

    /**
     * Muestra el formulario de edición de consulta.
     * Ruta: admin.consultas.edit
     */
    public function edit($id)
    {
        $consulta = Consulta::with(['mascota.propietario', 'tratamientos', 'metodos', 'adjuntos', 'receta'])->findOrFail($id);
        $mascotas = Mascota::with('propietario')
            ->where('estado', 'activo')
            ->orWhere('id_mascota', $consulta->id_mascota)
            ->get();
        $veterinarios = Veterinario::where(function ($query) use ($consulta) {
            $query->where('estado', 'activo')
                ->orWhere('id_veterinario', $consulta->id_veterinario);
        })->orderBy('nombre_completo')->get();

        return view('consultas.form', [
            'c' => $consulta,
            'mascotas' => $mascotas,
            'veterinarios' => $veterinarios,
        ]);
    }

    /**
     * Actualiza consulta con transacción.
     * Ruta: admin.consultas.update
     */
    public function update(Request $request, $id)
    {
        $consulta = Consulta::findOrFail($id);
        $data = $request->validate($this->reglas(false));
        $generaReceta = $request->boolean('genera_receta');
        $notasReceta = $data['notas_receta'] ?? null;
        unset($data['genera_receta'], $data['notas_receta'], $data['tratamientos'], $data['metodos'], $data['archivos'], $data['categoria']);

        try {
            DB::transaction(function () use ($request, $data, $consulta, $generaReceta, $notasReceta) {
                $consulta->update($data);

                if ($request->filled('id_veterinario')) {
                    $consulta->id_veterinario = $request->input('id_veterinario');
                    $consulta->save();
                }

                // Recrear tratamientos
                $consulta->tratamientos()->delete();
                foreach ($request->tratamientos ?? [] as $t) {
                    if (blank($t['medicamento'] ?? null) && blank($t['dosis'] ?? null)) {
                        continue;
                    }
                    if (blank($t['medicamento'] ?? null)) {
                        throw new \Exception('Toda fila de tratamiento con dosis debe indicar el medicamento.');
                    }
                    $consulta->tratamientos()->create([
                        'fecha'       => $t['fecha'] ?? now()->toDateString(),
                        'medicamento' => $t['medicamento'],
                        'dosis'       => $t['dosis'] ?? null,
                        'duracion'    => $t['duracion'] ?? null,
                    ]);
                }

                // Recrear métodos complementarios
                $consulta->metodos()->delete();
                foreach ($request->metodos ?? [] as $m) {
                    if (blank($m['examen'] ?? null)) {
                        continue;
                    }
                    $consulta->metodos()->create([
                        'fecha_orden' => $m['fecha_orden'] ?? null,
                        'examen'      => $m['examen'],
                        'resultados'  => $m['resultados'] ?? null,
                    ]);
                }

                if ($generaReceta) {
                    $consulta->receta()->updateOrCreate(
                        ['id_consulta' => $consulta->id_consulta],
                        ['notas' => $notasReceta]
                    );
                } else {
                    $consulta->receta()->delete();
                }
            });
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'No se pudo actualizar la consulta: ' . $e->getMessage());
        }

        return redirect()->route('admin.mascotas.show', $consulta->id_mascota)
                         ->with('ok', 'Consulta actualizada correctamente.');
    }

    /**
     * Reglas de validación estrictas (capa backend).
     */
    private function reglas(bool $conArchivos = true): array
    {
        $reglas = [
            'id_mascota'     => 'required|exists:mascotas,id_mascota',
            'id_veterinario' => 'required|exists:veterinarios,id_veterinario',
            'fecha_hora'     => 'required|date',
            'motivo'         => 'required|string|max:200',
            'diagnostico'    => 'required|string|max:200',
            'enfermedades_anteriores' => 'nullable|string',
            'tratamientos_recientes' => 'nullable|string',
            'ultima_desparasitacion' => 'nullable|date',
            'peso'           => 'nullable|numeric|between:0.01,200',
            'temperatura'    => 'nullable|numeric|between:25,45',
            'fc'             => 'nullable|integer|min:1|max:400',
            'fr'             => 'nullable|integer|min:1|max:200',
            'mucosa'         => 'nullable|string|max:60',
            'rc_min'         => 'nullable|string|max:30',
            'inspeccion'     => 'nullable|string',
            'palpacion'      => 'nullable|string',
            'sintomas'       => 'nullable|string',
            'observaciones'  => 'nullable|string',
            'evolucion_pronostico' => 'nullable|string',
            'proxima_cita'   => 'nullable|date|after_or_equal:fecha_hora',
            'genera_receta'  => 'nullable|boolean',
            'notas_receta'   => 'nullable|string',
            'tratamientos'   => 'nullable|array',
            'tratamientos.*.medicamento' => 'nullable|string|max:120',
            'tratamientos.*.dosis'       => 'nullable|string|max:120',
            'tratamientos.*.duracion'    => 'nullable|string|max:80',
            'tratamientos.*.fecha'       => 'nullable|date',
            'metodos'        => 'nullable|array',
            'metodos.*.examen'     => 'nullable|string|max:150',
            'metodos.*.resultados' => 'nullable|string',
            'metodos.*.fecha_orden' => 'nullable|date',
        ];

        if ($conArchivos) {
            $reglas['archivos']    = 'nullable|array|max:5';
            $reglas['archivos.*']  = 'file|mimes:jpg,jpeg,png,pdf|max:5120';
            $reglas['categoria']   = 'nullable|array';
            $reglas['categoria.*'] = 'in:radiografia,laboratorio,foto_clinica,documento,otro';
        }

        return $reglas;
    }

    /**
     * Carpeta raíz de subidas: local = public/uploads, hosting = htdocs/uploads.
     */
    private function uploadBase(): string
    {
        return env('UPLOAD_PATH', public_path('uploads'));
    }
}