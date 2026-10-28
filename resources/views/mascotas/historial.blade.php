@extends('layouts.app')
@section('titulo', 'Historial clínico — '.$m->nombre)
@section('contenido')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
  <h4 class="mb-0">Historia clínica — {{ $m->nombre }}</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.mascotas.show', $m->id_mascota) }}" class="btn btn-outline-secondary btn-sm">Volver a la ficha</a>
    <a href="{{ route('admin.historial.pdf.preview', $m->id_mascota) }}" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener">Visualizar PDF</a>
    <a href="{{ route('admin.historial.pdf', $m->id_mascota) }}" class="btn btn-primary btn-sm">Descargar PDF</a>
  </div>
</div>

<article class="card border-0 shadow-sm p-3 p-md-4">
  <header class="mb-3">
    <h3 class="h5 fw-bold">HISTORIA CLÍNICA — {{ $m->nombre }}</h3>
    <p class="mb-1">
      {{ ucfirst($m->especie) }} · {{ $m->raza ?? 'Mestizo' }} ·
      Sexo: {{ $m->sexo ?? '—' }} · Color: {{ $m->color ?? '—' }} ·
      Edad: {{ $m->edad ?? '—' }} · F.N.: {{ $m->fecha_nacimiento?->format('d/m/Y') ?? '—' }}
    </p>
    <p class="mb-1"><b>Dueño:</b> {{ $m->propietario->nombre_completo }} ({{ $m->propietario->telefono ?? '—' }})</p>
    <p class="mb-0"><b>Señas particulares:</b> {{ $m->senas_particulares ?? '—' }}</p>
  </header>

  <h5 class="h6 fw-bold mt-3">CONSULTAS ({{ $m->consultas->count() }})</h5>
  <div class="table-responsive mb-3">
    <table class="table table-bordered table-sm align-middle historial-tabla">
      <thead class="table-light">
        <tr>
          <th>Fecha</th><th>Motivo / Síntomas</th><th>Examen</th>
          <th>Diagnóstico</th><th>Tratamiento</th><th>Evolución</th>
        </tr>
      </thead>
      <tbody>
      @forelse($m->consultas as $c)
        <tr>
          <td>{{ $c->fecha_hora->format('d/m/Y H:i') }}</td>
          <td>{{ $c->motivo }}@if($c->sintomas)<br>{{ $c->sintomas }}@endif</td>
          <td>
            {{ $c->peso ?? '—' }} kg<br>{{ $c->temperatura ?? '—' }} °C<br>
            FC {{ $c->fc ?? '—' }} · FR {{ $c->fr ?? '—' }}
          </td>
          <td>{{ $c->diagnostico }}</td>
          <td>
            @forelse($c->tratamientos as $t)
              {{ $t->medicamento }}@if($t->dosis) ({{ $t->dosis }})@endif
              @if($t->duracion)<br>{{ $t->duracion }}@endif
              @if(!$loop->last)<br>@endif
            @empty
              —
            @endforelse
          </td>
          <td>{{ $c->evolucion_pronostico ?? '—' }}</td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center text-muted">No hay consultas registradas.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  @if($m->consultas->isNotEmpty())
    <h5 class="h6 fw-bold mt-2">OTROS DATOS CLÍNICOS</h5>
    @foreach($m->consultas as $c)
      <section class="border rounded p-2 mb-2">
        <div class="fw-bold mb-1">{{ $c->fecha_hora->format('d/m/Y H:i') }} — {{ $c->motivo }}</div>
        <div class="row g-2 small">
          <div class="col-md-6"><b>Veterinario:</b> {{ $c->veterinario?->nombre_completo ?? '—' }}</div>
          <div class="col-md-6"><b>Enfermedades anteriores:</b> {{ $c->enfermedades_anteriores ?? '—' }}</div>
          <div class="col-md-6"><b>Tratamientos recientes:</b> {{ $c->tratamientos_recientes ?? '—' }}</div>
          <div class="col-md-6"><b>Última desparasitación:</b> {{ $c->ultima_desparasitacion?->format('d/m/Y') ?? '—' }}</div>
          <div class="col-md-6"><b>Mucosa:</b> {{ $c->mucosa ?? '—' }} · <b>R.C.:</b> {{ $c->rc_min ?? '—' }}</div>
          <div class="col-md-6"><b>Inspección:</b> {{ $c->inspeccion ?? '—' }}</div>
          <div class="col-md-6"><b>Palpación:</b> {{ $c->palpacion ?? '—' }}</div>
          <div class="col-md-6"><b>Observaciones:</b> {{ $c->observaciones ?? '—' }}</div>
          <div class="col-md-6"><b>Próxima cita:</b> {{ $c->proxima_cita?->format('d/m/Y') ?? '—' }}</div>
          <div class="col-md-6"><b>Notas de receta:</b> {{ $c->receta?->notas ?? '—' }}</div>
          @if($c->metodos->isNotEmpty())
            <div class="col-12"><b>Métodos complementarios:</b>
              @foreach($c->metodos as $metodo)
                {{ $metodo->fecha_orden?->format('d/m/Y') ?? '—' }} — {{ $metodo->examen }}: {{ $metodo->resultados ?? '—' }}@if(!$loop->last); @endif
              @endforeach
            </div>
          @endif
          @if($c->adjuntos->isNotEmpty())
            <div class="col-12"><b>Archivos adjuntos:</b>
              @foreach($c->adjuntos as $adjunto)
                {{ $adjunto->nombre_original }} ({{ str_replace('_', ' ', $adjunto->categoria) }})@if(!$loop->last); @endif
              @endforeach
            </div>
          @endif
        </div>
      </section>
    @endforeach
  @endif

  <h5 class="h6 fw-bold mt-3">VACUNAS</h5>
  <div class="table-responsive">
    <table class="table table-bordered table-sm align-middle">
      <thead class="table-light"><tr><th>Vacuna</th><th>Lote</th><th>Aplicada</th><th>Próxima</th></tr></thead>
      <tbody>
      @forelse($m->vacunas as $v)
        <tr>
          <td>{{ $v->tipo_vacuna }}</td><td>{{ $v->lote ?? '—' }}</td>
          <td>{{ $v->fecha_aplicacion?->format('d/m/Y') ?? '—' }}</td>
          <td>{{ $v->fecha_proxima_dosis?->format('d/m/Y') ?? '—' }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-muted">No hay vacunas registradas.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="row mt-4 pt-4">
    <div class="col-6 border-top pt-2">FIRMA DEL PROPIETARIO</div>
    <div class="col-6 border-top pt-2">FIRMA DEL MÉDICO VETERINARIO</div>
  </div>
</article>
<style>
  .historial-tabla { min-width: 850px; }
</style>
@endsection
