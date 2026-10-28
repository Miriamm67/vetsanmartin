@extends('layouts.app')

@section('content')
<div class="card card-vet mb-3">
    <div class="card-cabecera"><i class="bi bi-calendar2-week"></i> Consultas por fecha y veterinario</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.consultas.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Fecha exacta</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control" data-after-or-equal-field="desde">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Veterinario</label>
                <select name="id_veterinario" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($veterinarios as $v)
                        <option value="{{ $v->id_veterinario }}" @selected(request('id_veterinario') == $v->id_veterinario)>
                            {{ $v->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filtrar</button>
                <a href="{{ route('admin.consultas.index') }}" class="btn btn-outline-primary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="card card-vet">
    <div class="card-body table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Fecha y hora</th>
                    <th>Mascota</th>
                    <th>Propietario</th>
                    <th>Veterinario que atendió</th>
                    <th>Diagnóstico</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($consultas as $c)
                    <tr>
                        <td>{{ $c->fecha_hora->format('d/m/Y H:i') }}</td>
                        <td>🐾 {{ $c->mascota->nombre }}</td>
                        <td>{{ $c->mascota->propietario->nombre_completo }}</td>
                        <td>{{ $c->veterinario?->nombre_completo ?? '—' }}</td>
                        <td>{{ Str::limit($c->diagnostico, 40) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.mascotas.show', $c->id_mascota) }}" class="btn btn-sm btn-outline-primary">Ver ficha</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay consultas para los filtros seleccionados.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $consultas->links() }}
    </div>
</div>
@endsection