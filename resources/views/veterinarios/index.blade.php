@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bolder mb-0"><i class="bi bi-person-vcard" style="color:var(--vet-morado)"></i> Veterinarios</h3>
    <a href="{{ route('admin.veterinarios.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo veterinario</a>
</div>

<div class="card card-vet">
    <div class="card-body table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>Usuario del sistema</th>
                    <th>Matrícula</th>
                    <th>Especialidad</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($veterinarios as $v)
                    <tr>
                        <td>🩺 {{ $v->nombre_completo }}</td>
                        <td>{{ $v->usuario?->nombre_usuario ?? '—' }}</td>
                        <td>{{ $v->nro_colegiado ?? '—' }}</td>
                        <td>{{ $v->especialidad ?? '—' }}</td>
                        <td>
                            @if ($v->estado === 'activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.veterinarios.edit', $v->id_veterinario) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="{{ route('admin.veterinarios.toggle', $v->id_veterinario) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-danger">{{ $v->estado === 'activo' ? 'Desactivar' : 'Activar' }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay veterinarios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $veterinarios->links() }}
    </div>
</div>
@endsection