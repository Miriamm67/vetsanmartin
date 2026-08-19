@extends('layouts.app')
@section('contenido')
<h4>{{ $c->id_consulta ? 'Editar' : 'Nueva' }} Consulta — Historia Clínica</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $c->id_consulta ? route('admin.consultas.update',$c->id_consulta) : route('admin.consultas.store') }}">
  @csrf @if($c->id_consulta) @method('PUT') @endif

  <div class="row g-2 mb-2">
    <div class="col-md-6"><label class="form-label">Mascota *</label>
      <select name="id_mascota" class="form-select" required>
        @foreach($mascotas as $m)
          <option value="{{ $m->id_mascota }}" {{ old('id_mascota',$c->id_mascota ?? request('mascota_id'))==$m->id_mascota?'selected':'' }}>{{ $m->nombre }} ({{ $m->propietario->nombre_completo }})</option>
        @endforeach
      </select></div>
    <div class="col-md-6"><label class="form-label">Motivo de la visita *</label>
      <input name="motivo" class="form-control" value="{{ old('motivo',$c->motivo) }}" required></div>
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
      <input step="0.01" name="peso" class="form-control form-control-sm" value="{{ old('peso',$c->peso) }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">T° C</label>
      <input step="0.1" name="temperatura" class="form-control form-control-sm" value="{{ old('temperatura',$c->temperatura) }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">F.C.</label>
      <input type="number" name="fc" class="form-control form-control-sm" value="{{ old('fc',$c->fc) }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">F.R.</label>
      <input type="number" name="fr" class="form-control form-control-sm" value="{{ old('fr',$c->fr) }}"></div>
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
  @for($i=0;$i<2;$i++)
  <div class="row g-2 mb-1">
    <div class="col-3"><input type="date" name="f_met[]" class="form-control form-control-sm" value="{{ $c->metodos[$i]->fecha_orden?->format('Y-m-d') ?? '' }}"></div>
    <div class="col-4"><input name="examen[]" class="form-control form-control-sm" placeholder="Examen" value="{{ $c->metodos[$i]->examen ?? '' }}"></div>
    <div class="col-5"><input name="resultados[]" class="form-control form-control-sm" placeholder="Resultados" value="{{ $c->metodos[$i]->resultados ?? '' }}"></div>
  </div>
  @endfor

  <h6 class="text-primary">Diagnóstico presuntivo / definitivo *</h6>
  <input name="diagnostico" class="form-control form-control-sm mb-2" value="{{ old('diagnostico',$c->diagnostico) }}" required>

  <h6 class="text-primary">Tratamiento</h6>
  @for($i=0;$i<3;$i++)
  <div class="row g-2 mb-1">
    <div class="col-3"><input type="date" name="f_trat[]" class="form-control form-control-sm" value="{{ $c->tratamientos[$i]->fecha?->format('Y-m-d') ?? '' }}"></div>
    <div class="col-3"><input name="medicamento[]" class="form-control form-control-sm" placeholder="Medicamento" value="{{ $c->tratamientos[$i]->medicamento ?? '' }}"></div>
    <div class="col-3"><input name="dosis[]" class="form-control form-control-sm" placeholder="Dosis" value="{{ $c->tratamientos[$i]->dosis ?? '' }}"></div>
    <div class="col-3"><input name="duracion[]" class="form-control form-control-sm" placeholder="Duración" value="{{ $c->tratamientos[$i]->duracion ?? '' }}"></div>
  </div>
  @endfor

  <h6 class="text-primary">Evolución y pronóstico / Observaciones</h6>
  <textarea name="evolucion_pronostico" class="form-control form-control-sm mb-1" rows="2">{{ old('evolucion_pronostico',$c->evolucion_pronostico) }}</textarea>
  <textarea name="observaciones" class="form-control form-control-sm mb-2" rows="2" placeholder="Recomendaciones al dueño">{{ old('observaciones',$c->observaciones) }}</textarea>

  <div class="row g-2 mb-3">
    <div class="col-md-4"><label class="form-label small">Próxima cita</label>
      <input type="date" name="proxima_cita" class="form-control form-control-sm" value="{{ old('proxima_cita',$c->proxima_cita?->format('Y-m-d')) }}"></div>
    <div class="col-md-4 form-check mt-4">
      <input type="checkbox" name="con_receta" value="1" class="form-check-input" id="rec">
      <label for="rec" class="form-check-label">Generar receta</label>
    </div>
    <div class="col-md-4"><label class="form-label small">Notas de receta</label>
      <input name="notas_receta" class="form-control form-control-sm"></div>
  </div>
  <button class="btn btn-primary">Guardar consulta</button>
</form>
</div></div>
@endsection