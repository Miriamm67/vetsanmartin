@extends('layouts.app')
@section('contenido')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
  <h4 class="mb-0">🐾 {{ $m->nombre }} — historial clínico</h4>
  <a href="{{ route('cliente.historial') }}" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-arrow-left"></i> Volver a mis mascotas
  </a>
</div>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card mb-3">
      @if($m->foto)
        <img src="{{ asset($m->foto) }}" alt="Foto de {{ $m->nombre }}" class="card-img-top pet-photo">
      @else
        <div class="d-flex align-items-center justify-content-center bg-light text-primary" style="height:180px;">
          <i class="bi bi-heart-pulse" style="font-size:4rem;"></i>
        </div>
      @endif
      <div class="card-body">
        <h5 class="card-title">{{ $m->nombre }}</h5>
        <p class="text-muted mb-0">{{ ucfirst($m->especie) }} · {{ $m->raza ?? 'Mestizo' }}</p>
      </div>
    </div>
    <div class="card"><div class="card-header">💉 Calendario de vacunación</div>
      <table class="table table-sm mb-0">
        <thead><tr><th>Vacuna</th><th>Aplicada</th><th>Próxima</th></tr></thead>
        <tbody>
        @forelse($m->vacunas as $v)
          <tr>
            <td>{{ $v->tipo_vacuna }}</td>
            <td>{{ $v->fecha_aplicacion->format('d/m/Y') }}</td>
            <td>{{ $v->fecha_proxima_dosis?->format('d/m/Y') ?? '-' }}</td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-center text-muted">Sin vacunas registradas.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-8">
    <h5>Historial de consultas ({{ $m->consultas->count() }})</h5>
    @forelse($m->consultas as $c)
    <div class="card mb-2"><div class="card-body small">
      <h6><b>{{ $c->fecha_hora->format('d/m/Y H:i') }}</b> — {{ $c->motivo }}</h6>
      @if($c->veterinario)
        <div><b>Veterinario:</b> {{ $c->veterinario->nombre_completo }}</div>
      @endif
      <div><b>Enfermedades anteriores:</b> {{ $c->enfermedades_anteriores ?? '—' }}</div>
      <div><b>Tratamientos recientes:</b> {{ $c->tratamientos_recientes ?? '—' }}</div>
      <div><b>Última desparasitación:</b> {{ $c->ultima_desparasitacion?->format('d/m/Y') ?? '—' }}</div>
      <div class="mt-2"><b>Examen general:</b>
        Peso {{ $c->peso ?? '—' }} kg · Temperatura {{ $c->temperatura ?? '—' }} °C ·
        F.C. {{ $c->fc ?? '—' }} · F.R. {{ $c->fr ?? '—' }} ·
        Mucosa {{ $c->mucosa ?? '—' }} · R.C. {{ $c->rc_min ?? '—' }}
      </div>
      <div><b>Inspección:</b> {{ $c->inspeccion ?? '—' }}</div>
      <div><b>Palpación:</b> {{ $c->palpacion ?? '—' }}</div>
      <div><b>Síntomas:</b> {{ $c->sintomas ?? '—' }}</div>
      <div><b>Diagnóstico:</b> {{ $c->diagnostico }}</div>
      @if($c->tratamientos->isNotEmpty())
        <div class="mt-2"><b>Tratamiento:</b>
          <ul class="mb-1">
            @foreach($c->tratamientos as $tratamiento)
              <li>{{ $tratamiento->medicamento }}@if($tratamiento->dosis) — {{ $tratamiento->dosis }}@endif @if($tratamiento->duracion) ({{ $tratamiento->duracion }})@endif</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if($c->metodos->isNotEmpty())
        <div class="mt-2"><b>Métodos complementarios:</b>
          <ul class="mb-1">
            @foreach($c->metodos as $metodo)
              <li>{{ $metodo->fecha_orden?->format('d/m/Y') ?? '—' }} — {{ $metodo->examen }}: {{ $metodo->resultados ?? '—' }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if($c->evolucion_pronostico)
        <div><b>Evolución y pronóstico:</b> {{ $c->evolucion_pronostico }}</div>
      @endif
      @if($c->observaciones)
        <div><b>Observaciones:</b> {{ $c->observaciones }}</div>
      @endif
      <div><b>Próxima cita:</b> {{ $c->proxima_cita?->format('d/m/Y') ?? '-' }}</div>
      @if($c->receta)
        @if($c->receta->notas)
          <div><b>Notas de receta:</b> {{ $c->receta->notas }}</div>
        @endif
        <br><a class="btn btn-sm btn-outline-success mt-1" href="{{ route('cliente.receta',$c->id_consulta) }}">⬇ Receta PDF</a>
      @endif
      @if($c->adjuntos->isNotEmpty())
        <div class="mt-2"><b>Archivos adjuntos:</b>
          @foreach($c->adjuntos as $adjunto)
            <a href="{{ asset($adjunto->ruta_archivo) }}" target="_blank" rel="noopener" class="badge bg-light text-dark text-decoration-none me-1">
              {{ $adjunto->nombre_original }}
            </a>
          @endforeach
        </div>
      @endif
    </div></div>
    @empty
      <p class="text-muted">Esta mascota todavía no tiene consultas registradas.</p>
    @endforelse
  </div>
</div>
@endsection