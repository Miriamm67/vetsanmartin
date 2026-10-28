@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-vet">
            <div class="card-cabecera">
                <i class="bi bi-person-vcard"></i>
                {{ isset($veterinario) ? 'Editar veterinario' : 'Nuevo veterinario' }}
            </div>
            <div class="card-body p-4">
                <form method="POST"
                      action="{{ isset($veterinario) ? route('admin.veterinarios.update', $veterinario->id_veterinario) : route('admin.veterinarios.store') }}">
                    @csrf
                    @if (isset($veterinario)) @method('PUT') @endif

                    <label class="form-label fw-bold small">Usuario del sistema *</label>
                    <select name="id_usuario" class="form-select mb-2" required>
                        <option value="">-- Seleccione el usuario --</option>
                        @foreach ($usuarios as $u)
                            <option value="{{ $u->id_usuario }}"
                                @selected(old('id_usuario', $veterinario->id_usuario ?? '') == $u->id_usuario)>
                                {{ $u->nombre_usuario }} (rol: {{ $u->rol }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text mb-3">Solo aparecen usuarios activos sin perfil de veterinario.</div>

                    <label class="form-label fw-bold small">Nombre completo *</label>
                    <input name="nombre_completo" class="form-control mb-2"
                           value="{{ old('nombre_completo', $veterinario->nombre_completo ?? '') }}" maxlength="120" required>

                    <label class="form-label fw-bold small">Matrícula / N.° de colegiado</label>
                    <input name="nro_colegiado" class="form-control mb-2"
                           value="{{ old('nro_colegiado', $veterinario->nro_colegiado ?? '') }}" maxlength="40">

                    <label class="form-label fw-bold small">Especialidad</label>
                    <input name="especialidad" class="form-control mb-2"
                           value="{{ old('especialidad', $veterinario->especialidad ?? '') }}"
                           placeholder="Ej.: Clínica de animales menores y mayores" maxlength="80">

                    @if (isset($veterinario))
                        <label class="form-label fw-bold small">Estado *</label>
                        <select name="estado" class="form-select mb-2" required>
                            <option value="activo" @selected(old('estado', $veterinario->estado) === 'activo')>Activo</option>
                            <option value="inactivo" @selected(old('estado', $veterinario->estado) === 'inactivo')>Inactivo</option>
                        </select>
                    @endif

                    <button class="btn btn-primary w-100 mt-2"><i class="bi bi-save"></i> Guardar</button>
                    <a href="{{ route('admin.veterinarios.index') }}" class="btn btn-outline-primary w-100 mt-2">Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection