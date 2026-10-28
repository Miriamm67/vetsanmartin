@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bolder mb-0"><i class="bi bi-clipboard2-pulse" style="color:var(--vet-morado)"></i> Panel del Veterinario</h3>
    <span class="text-muted small"><i class="bi bi-calendar3"></i> {{ now()->format('d/m/Y') }}</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="kpi kpi-morado">
            <i class="bi bi-people-fill"></i>
            <div class="kpi-num">{{ \App\Models\Propietario::where('estado','activo')->count() }}</div>
            <div class="kpi-txt">Propietarios</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi kpi-verde">
            <i class="bi bi-heart-fill"></i>
            <div class="kpi-num">{{ \App\Models\Mascota::where('estado','activo')->count() }}</div>
            <div class="kpi-txt">Mascotas</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi kpi-cian">
            <i class="bi bi-file-earmark-medical-fill"></i>
            <div class="kpi-num">{{ \App\Models\Consulta::count() }}</div>
            <div class="kpi-txt">Consultas</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi kpi-ambar">
            <i class="bi bi-bell-fill"></i>
            <div class="kpi-num">{{ \App\Models\Vacuna::proximas()->count() }}</div>
            <div class="kpi-txt">Alertas vacunas</div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><i class="bi bi-calendar2-check"></i> Consultas por fecha</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-2 align-items-end mb-3">
            <div class="col-sm-6 col-md-4">
                <label for="fecha" class="form-label fw-bold">Selecciona el día</label>
                <input type="date" id="fecha" name="fecha" value="{{ $fecha }}" class="form-control" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
            </div>
        </form>
        <p class="mb-0">
            <strong>{{ $consultas->total() }}</strong>
            {{ $consultas->total() === 1 ? 'consulta encontrada' : 'consultas encontradas' }}
            el {{ \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') }}.
        </p>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Mascota</th>
                    <th>Propietario</th>
                    <th>Veterinario</th>
                    <th>Diagnóstico</th>
                    <th class="text-end">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consultas as $consulta)
                    <tr>
                        <td>{{ $consulta->fecha_hora->format('H:i') }}</td>
                        <td>{{ $consulta->mascota->nombre }}</td>
                        <td>{{ $consulta->mascota->propietario->nombre_completo }}</td>
                        <td>{{ $consulta->veterinario?->nombre_completo ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($consulta->diagnostico, 50) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.mascotas.show', $consulta->id_mascota) }}" class="btn btn-sm btn-outline-primary">Ver ficha</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No hay consultas registradas para esta fecha.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($consultas->hasPages())
        <div class="card-body">{{ $consultas->links() }}</div>
    @endif
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-bell-fill"></i> Vacunas próximas a vencer (30 días) — RF10</div>
    <div class="card-body p-0 table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr><th>Mascota</th><th>Vacuna</th><th>Próxima dosis</th><th>Estado</th></tr>
            </thead>
            <tbody>
                @forelse($alertas as $v)
                    <tr>
                        <td>🐾 {{ $v->mascota->nombre }}</td>
                        <td>{{ $v->tipo_vacuna }}</td>
                        <td>{{ $v->fecha_proxima_dosis?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if($v->fecha_proxima_dosis && $v->fecha_proxima_dosis->lt(now()))
                                <span class="badge bg-danger">VENCIDA</span>
                            @else
                                <span class="badge bg-warning text-dark">Por vencer</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Sin alertas ✔</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection