@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-vet">
            <div class="card-cabecera"><i class="bi bi-syringe"></i> Editar vacuna – {{ $vacuna->mascota->nombre }}</div>
            <div class="card-body p-4">
                @php
                    $tipoExiste = in_array($vacuna->tipo_vacuna, $opcionesVacuna, true);
                    $tipoSeleccionado = old('tipo_vacuna', $tipoExiste ? $vacuna->tipo_vacuna : '__otra__');
                @endphp
                <form method="POST" action="{{ route('admin.vacunas.update', $vacuna->id_vacuna) }}">
                    @csrf @method('PUT')
                    <label class="form-label small fw-bold">Tipo de vacuna *</label>
                    <select name="tipo_vacuna" id="tipo-vacuna-editar" class="form-select mb-2" required>
                        <option value="">Seleccionar vacuna...</option>
                        @foreach($opcionesVacuna as $opcion)
                            <option value="{{ $opcion }}" @selected($tipoSeleccionado === $opcion)>{{ $opcion }}</option>
                        @endforeach
                        <option value="__otra__" @selected($tipoSeleccionado === '__otra__')>Otra (especificar)</option>
                    </select>
                    <input name="tipo_vacuna_otra" id="tipo-vacuna-otra-editar" value="{{ old('tipo_vacuna_otra', $tipoExiste ? '' : $vacuna->tipo_vacuna) }}" class="form-control mb-2" placeholder="Escribe el nombre de la vacuna" maxlength="80" hidden>
                    <label class="form-label small fw-bold">Lote</label>
                    <input name="lote" value="{{ old('lote', $vacuna->lote) }}" class="form-control mb-2" maxlength="40">
                    <label class="form-label small fw-bold">Fecha de aplicación *</label>
                    <input type="date" name="fecha_aplicacion" value="{{ old('fecha_aplicacion', $vacuna->fecha_aplicacion->format('Y-m-d')) }}" max="{{ now()->toDateString() }}" class="form-control mb-2" required>
                    <label class="form-label small fw-bold">Próxima dosis</label>
                    <input type="date" name="fecha_proxima_dosis" value="{{ old('fecha_proxima_dosis', $vacuna->fecha_proxima_dosis?->format('Y-m-d')) }}" data-after-field="fecha_aplicacion" class="form-control mb-2">
                    <label class="form-label small fw-bold">Estado de la próxima dosis *</label>
                    <select name="estado_proxima" class="form-select mb-2" required>
                        @foreach (['pendiente'=>'Pendiente','aplicada'=>'Aplicada','omitida'=>'Omitida'] as $k=>$t)
                            <option value="{{ $k }}" @selected(old('estado_proxima', $vacuna->estado_proxima)===$k)>{{ $t }}</option>
                        @endforeach
                    </select>
                    <label class="form-label small fw-bold">Observación</label>
                    <textarea name="observacion" class="form-control mb-3" rows="2" maxlength="200">{{ old('observacion', $vacuna->observacion) }}</textarea>
                    <button class="btn btn-primary w-100">Guardar cambios</button>
                    <a href="{{ route('admin.mascotas.show', $vacuna->id_mascota) }}" class="btn btn-outline-primary w-100 mt-2">Volver</a>
                </form>
                <script>
                    (() => {
                        const select = document.getElementById('tipo-vacuna-editar');
                        const otra = document.getElementById('tipo-vacuna-otra-editar');
                        const actualizarCampoOtra = () => {
                            const mostrar = select.value === '__otra__';
                            otra.hidden = !mostrar;
                            otra.required = mostrar;
                        };
                        select.addEventListener('change', actualizarCampoOtra);
                        actualizarCampoOtra();
                    })();
                </script>
            </div>
        </div>
    </div>
</div>
@endsection