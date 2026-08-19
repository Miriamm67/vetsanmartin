@extends('layouts.app')
@section('titulo', $m->nombre)
@section('contenido')
<div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
  <h4>🐾 {{ $m->nombre }} <small class="text-muted">({{ $m->especie }} · {{ $m->raza ?? 'mestizo' }})</small></h4>
  <div>
    <a href="{{ route('admin.historial.pdf',$m->id_mascota) }}" class="btn btn-outline-secondary btn-sm">📄 Historial PDF</a>
    <a href="{{ route('admin.consultas.create',['mascota_id'=>$m->id_mascota]) }}" class="btn btn-primary btn-sm">+ Nueva Consulta</a>
  </div>
</div>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card mb-3"><div class="card-header">Reseña del animal</div>
      <div class="card-body small">
        <b>Dueño:</b> {{ $m->propietario->nombre_completo }}<br>
        <b>F.N.:</b> {{ $m->fecha_nacimiento?->format('d/m/Y') ?? '-' }} · <b>Edad:</b> {{ $m->edad ?? '-' }}<br>
        <b>Sexo:</b> {{ $m->sexo ?? '-' }} · <b>Color:</b> {{ $m->color ?? '-' }}<br>
        <b>Señas:</b> {{ $m->senas_particulares ?? '-' }}
      </div>
    </div>
    <div class="card"><div class="card-header">💉 Registrar vacuna</div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.vacunas.store') }}">
          @csrf
          <input type="hidden" name="id_mascota" value="{{ $m->id_mascota }}">
          <input name="tipo_vacuna" class="form-control form-control-sm mb-1" placeholder="Tipo de vacuna" required>
          <input name="lote" class="form-control form-control-sm mb-1" placeholder="Lote">
          <label class="small">Aplicación</label>
          <input type="date" name="fecha_aplicacion" class="form-control form-control-sm mb-1" required>
          <label class="small">Próxima dosis</label>
          <input type="date" name="fecha_proxima_dosis" class="form-control form-control-sm mb-2">
          <button class="btn btn-sm btn-success w-100">Guardar vacuna</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <h6>Historial clínico (más reciente primero)</h6>
    @forelse($m->consultas as $c)
    <div class="card mb-2">
      <div class="card-header d-flex justify-content-between py-2">
        <span class="small"><b>{{ $c->fecha_hora->format('d/m/Y H:i') }}</b> — {{ $c->motivo }}</span>
        <span>
          <a class="btn btn-sm btn-outline-primary py-0" href="{{ route('admin.consultas.edit',$c->id_consulta) }}">Editar</a>
          <a class="btn btn-sm btn-outline-success py-0" href="{{ route('admin.receta.pdf',$c->id_consulta) }}">Receta PDF</a>
        </span>
      </div>
      <div class="card-body small">
        <b>Examen:</b> {{ $c->peso }} kg · {{ $c->temperatura }} °C · FC {{ $c->fc }} · FR {{ $c->fr }} · Mucosa {{ $c->mucosa }}<br>
        <b>Diagnóstico:</b> {{ $c->diagnostico }}<br>
        @if($c->tratamientos->count())
          <b>Tratamiento:</b>
          <ul class="mb-1">
            @foreach($c->tratamientos as $t)
              <li>{{ $t->fecha?->format('d/m/Y') }} — {{ $t->medicamento }} — {{ $t->dosis }} — {{ $t->duracion }}</li>
            @endforeach
          </ul>
        @endif
        @if($c->metodos->count())
          <b>Métodos complementarios:</b>
          <ul class="mb-1">
            @foreach($c->metodos as $mt)
              <li>{{ $mt->examen }}: {{ $mt->resultados }}</li>
            @endforeach
          </ul>
        @endif
        <b>Evolución:</b> {{ $c->evolucion_pronostico ?? '-' }} ·
        <b>Próx. cita:</b> {{ $c->proxima_cita?->format('d/m/Y') ?? '-' }}
      </div>
    </div>
    @empty
      <p class="text-muted">Sin consultas registradas.</p>
    @endforelse

    <h6 class="mt-3">Calendario de vacunación</h6>
    <table class="table table-sm">
      <thead><tr><th>Vacuna</th><th>Lote</th><th>Aplicada</th><th>Próxima</th></tr></thead>
      <tbody>
      @foreach($m->vacunas as $v)
        <tr>
          <td>{{ $v->tipo_vacuna }}</td><td>{{ $v->lote }}</td>
          <td>{{ $v->fecha_aplicacion->format('d/m/Y') }}</td>
          <td>{{ $v->fecha_proxima_dosis?->format('d/m/Y') ?? '-' }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection