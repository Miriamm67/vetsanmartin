@extends('layouts.app')
@section('contenido')
<h4>{{ $c->id_consulta ? 'Editar' : 'Nueva' }} Consulta — Historia Clínica{{ !$c->id_consulta && $mascotas->count() === 1 ? ' · '.$mascotas->first()->nombre : '' }}</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $c->id_consulta ? route('admin.consultas.update',$c->id_consulta) : route('admin.consultas.store') }}" id="form-consulta" enctype="multipart/form-data">
  @csrf @if($c->id_consulta) @method('PUT') @endif

  <div class="row g-2 mb-2">
    <div class="col-md-4"><label class="form-label">Mascota *</label>
      <select name="id_mascota" class="form-select" required>
        @foreach($mascotas as $m)
          <option value="{{ $m->id_mascota }}" {{ old('id_mascota', $c->id_mascota ?? request('id_mascota', request('mascota_id', $mascotas->count() === 1 ? $mascotas->first()->id_mascota : null))) == $m->id_mascota ? 'selected' : '' }}>{{ $m->nombre }} ({{ $m->propietario->nombre_completo }})</option>
        @endforeach
      </select></div>
    <div class="col-md-4"><label class="form-label">Fecha y hora de consulta *</label>
      <input type="datetime-local" name="fecha_hora" class="form-control" value="{{ old('fecha_hora', $c->fecha_hora?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" required>
      @error('fecha_hora')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4"><label class="form-label">Motivo de la visita *</label>
      <input name="motivo" class="form-control" value="{{ old('motivo',$c->motivo) }}" maxlength="200" required></div>
  </div>

  <h6 class="text-primary">Anamnesis</h6>
  <div class="row g-2 mb-2">
    <div class="col-md-4"><label class="form-label small">Enfermedades anteriores</label>
      <textarea name="enfermedades_anteriores" class="form-control form-control-sm" rows="2">{{ old('enfermedades_anteriores',$c->enfermedades_anteriores) }}</textarea></div>
    <div class="col-md-4"><label class="form-label small">Tratamientos recientes</label>
      <textarea name="tratamientos_recientes" class="form-control form-control-sm" rows="2">{{ old('tratamientos_recientes',$c->tratamientos_recientes) }}</textarea></div>
    <div class="col-md-4"><label class="form-label small">Última desparasitación</label>
      <input type="date" name="ultima_desparasitacion" class="form-control form-control-sm" value="{{ old('ultima_desparasitacion',$c->ultima_desparasitacion?->format('Y-m-d')) }}"></div>
  </div>

  <h6 class="text-primary">Examen general</h6>
  <div class="row g-2 mb-2">
    <div class="col-6 col-md-2"><label class="form-label small">Peso kg</label>
      <input type="number" step="0.01" min="0.01" max="200" name="peso" class="form-control form-control-sm" value="{{ old('peso',$c->peso) }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">T° C</label>
      <input type="number" step="0.1" min="25" max="45" name="temperatura" class="form-control form-control-sm" value="{{ old('temperatura',$c->temperatura) }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">F.C.</label>
      <input type="number" name="fc" min="1" max="400" step="1" class="form-control form-control-sm" value="{{ old('fc',$c->fc) }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">F.R.</label>
      <input type="number" name="fr" min="1" max="200" step="1" class="form-control form-control-sm" value="{{ old('fr',$c->fr) }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">Mucosa</label>
      <input name="mucosa" class="form-control form-control-sm" value="{{ old('mucosa',$c->mucosa) }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">R.C. min</label>
      <input name="rc_min" class="form-control form-control-sm" value="{{ old('rc_min',$c->rc_min) }}"></div>
    <div class="col-md-6"><label class="form-label small">Inspección</label>
      <textarea name="inspeccion" class="form-control form-control-sm" rows="2">{{ old('inspeccion',$c->inspeccion) }}</textarea></div>
    <div class="col-md-6"><label class="form-label small">Palpación</label>
      <textarea name="palpacion" class="form-control form-control-sm" rows="2">{{ old('palpacion',$c->palpacion) }}</textarea></div>
    <div class="col-12"><label class="form-label small">Síntomas</label>
      <textarea name="sintomas" class="form-control form-control-sm" rows="2">{{ old('sintomas',$c->sintomas) }}</textarea></div>
  </div>

  <h6 class="text-primary">Métodos complementarios</h6>
@for($i=0;$i<max(2, $c->metodos->count());$i++)
@php($metodo = $c->metodos[$i] ?? null)
<div class="row g-2 mb-1">
  <div class="col-3"><input type="date" name="metodos[{{ $i }}][fecha_orden]" class="form-control form-control-sm" value="{{ old("metodos.$i.fecha_orden", $metodo?->fecha_orden?->format('Y-m-d')) }}"></div>
  <div class="col-4"><input name="metodos[{{ $i }}][examen]" class="form-control form-control-sm" placeholder="Examen" value="{{ old("metodos.$i.examen", $metodo?->examen) }}" maxlength="150"></div>
  <div class="col-5"><input name="metodos[{{ $i }}][resultados]" class="form-control form-control-sm" placeholder="Resultados" value="{{ old("metodos.$i.resultados", $metodo?->resultados) }}"></div>
</div>
@endfor

  <h6 class="text-primary">Diagnóstico presuntivo / definitivo *</h6>
  <input name="diagnostico" class="form-control form-control-sm mb-2" value="{{ old('diagnostico',$c->diagnostico) }}" maxlength="200" required>

  <h6 class="text-primary">Tratamiento</h6>
@for($i=0;$i<max(3, $c->tratamientos->count());$i++)
@php($tratamiento = $c->tratamientos[$i] ?? null)
<div class="row g-2 mb-1">
  <div class="col-3"><input type="date" name="tratamientos[{{ $i }}][fecha]" class="form-control form-control-sm" value="{{ old("tratamientos.$i.fecha", $tratamiento?->fecha?->format('Y-m-d')) }}"></div>
  <div class="col-3"><input name="tratamientos[{{ $i }}][medicamento]" class="form-control form-control-sm" placeholder="Medicamento" value="{{ old("tratamientos.$i.medicamento", $tratamiento?->medicamento) }}" maxlength="120"></div>
  <div class="col-3"><input name="tratamientos[{{ $i }}][dosis]" class="form-control form-control-sm" placeholder="Dosis" value="{{ old("tratamientos.$i.dosis", $tratamiento?->dosis) }}" maxlength="120"></div>
  <div class="col-3"><input name="tratamientos[{{ $i }}][duracion]" class="form-control form-control-sm" placeholder="Duración" value="{{ old("tratamientos.$i.duracion", $tratamiento?->duracion) }}" maxlength="80"></div>
</div>
@endfor

  <h6 class="text-primary">Evolución y pronóstico / Observaciones</h6>
  <textarea name="evolucion_pronostico" class="form-control form-control-sm mb-1" rows="2">{{ old('evolucion_pronostico',$c->evolucion_pronostico) }}</textarea>
  <textarea name="observaciones" class="form-control form-control-sm mb-2" rows="2" placeholder="Recomendaciones al dueño">{{ old('observaciones',$c->observaciones) }}</textarea>

  <div class="row g-2 mb-3">
    <div class="col-md-4"><label class="form-label small">Próxima cita</label>
      <input type="date" name="proxima_cita" class="form-control form-control-sm" value="{{ old('proxima_cita',$c->proxima_cita?->format('Y-m-d')) }}" data-after-or-equal-field="fecha_hora"></div>
    <div class="col-md-4 mt-4">
      <input type="checkbox" name="genera_receta" value="1" class="form-check-input" id="rec" @checked(old('genera_receta', $c->receta ? 1 : 0))>
      <label for="rec" class="form-check-label">Generar receta</label>
    </div>
    <div class="col-md-4"><label class="form-label small">Notas de receta</label>
      <input name="notas_receta" class="form-control form-control-sm" value="{{ old('notas_receta', $c->receta?->notas) }}">
  </div>
  {{-- VETERINARIO QUE ATIENDE --}}
<div class="mb-3">
    <label class="form-label fw-bold small">Veterinario que atiende *</label>
    <select name="id_veterinario" class="form-select form-control-vet" required>
        <option value="">-- Seleccione --</option>
        @foreach ($veterinarios as $v)
            <option value="{{ $v->id_veterinario }}" @selected(old('id_veterinario', $c->id_veterinario) == $v->id_veterinario)>
                {{ $v->nombre_completo }} (Matrícula {{ $v->nro_colegiado }})
            </option>
        @endforeach
    </select>
    @error('id_veterinario')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

{{-- BOTÓN ADJUNTAR ARCHIVOS --}}
<div class="mb-3">
    <label class="form-label fw-bold small"><i class="bi bi-paperclip"></i> Adjuntar archivos (radiografías, fotos, documentos)</label>
    <input type="file" name="archivos[]" id="input-archivos" class="form-control form-control-vet" multiple accept=".jpg,.jpeg,.png,.pdf" data-max-files="5" data-max-size="5242880" data-allowed-extensions="jpg,jpeg,png,pdf">
    <div class="form-text">Máximo 5 archivos de 5 MB (JPG, PNG o PDF).</div>
    <div id="categorias-adjuntos" class="mt-2"></div>
    @error('archivos')<div class="text-danger small">{{ $message }}</div>@enderror
    @error('archivos.*')<div class="text-danger small">{{ $message }}</div>@enderror
</div>
  <button class="btn btn-primary">Guardar consulta</button>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('form-consulta');
    if (!form) return;

    // Genera un selector de categoría por cada archivo elegido
    const inputArchivos = document.getElementById('input-archivos');
    const contCategorias = document.getElementById('categorias-adjuntos');
    if (inputArchivos && contCategorias) {
        inputArchivos.addEventListener('change', function () {
            contCategorias.innerHTML = '';
            const cats = {
                radiografia: 'Radiografía', laboratorio: 'Laboratorio',
                foto_clinica: 'Foto clínica', documento: 'Documento', otro: 'Otro'
            };
            Array.from(inputArchivos.files).forEach(function (f, i) {
                const div = document.createElement('div');
                div.className = 'd-flex gap-2 align-items-center mb-1';
                div.innerHTML =
                    '<span class="small text-muted flex-fill">📎 ' + f.name + '</span>' +
                    '<select name="categoria[' + i + ']" class="form-select form-select-sm w-auto">' +
                    Object.entries(cats).map(function ([v, t]) {
                        return '<option value="' + v + '">' + t + '</option>';
                    }).join('') +
                    '</select>';
                contCategorias.appendChild(div);
            });
        });
    }

    // ===== CAPA FRONTEND: intercepta el envío =====
    form.addEventListener('submit', function (e) {
        const motivo = form.querySelector('[name=motivo]')?.value.trim();
        const diag   = form.querySelector('[name=diagnostico]')?.value.trim();
        const fecha  = form.querySelector('[name=fecha_hora]')?.value;
        const vet    = form.querySelector('[name=id_veterinario]')?.value;

        if (!motivo || !diag || !fecha || !vet) {
            e.preventDefault();
            Swal.fire('Campos obligatorios', 'Veterinario, fecha, motivo y diagnóstico no pueden estar vacíos.', 'warning');
            return;
        }

        // Filas de tratamiento: si hay dosis, debe haber medicamento
        const filas = form.querySelectorAll('#tabla-tratamientos tr');
        for (const tr of filas) {
            const med  = tr.querySelector('[name$="[medicamento]"]');
            const dos  = tr.querySelector('[name$="[dosis]"]');
            if (dos && dos.value.trim() && (!med || !med.value.trim())) {
                e.preventDefault();
                Swal.fire('Tratamiento incompleto', 'Toda fila con dosis debe indicar el medicamento.', 'warning');
                return;
            }
        }

        // Adjuntos: tipo y tamaño permitidos
        const files = inputArchivos ? inputArchivos.files : [];
        if (files.length > 5) {
            e.preventDefault();
            Swal.fire('Demasiados archivos', 'Máximo 5 archivos por consulta.', 'error');
            return;
        }
        for (const f of files) {
            if (f.size > 5 * 1024 * 1024 || !/\.(jpg|jpeg|png|pdf)$/i.test(f.name)) {
                e.preventDefault();
                Swal.fire('Archivo no válido', '"' + f.name + '" excede 5 MB o no es JPG/PNG/PDF.', 'error');
                return;
            }
        }
    });
});
</script>
</div></div>
@endsection