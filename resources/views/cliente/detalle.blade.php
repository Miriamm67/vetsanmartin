@extends('layouts.app')
@section('contenido')
<h4 class="mb-3">🐾 {{ $m->nombre }} — solo lectura</h4>
<div class="row g-3">
  <div class="col-md-6">
    <div class="card"><div class="card-header">💉 Calendario de vacunación</div>
      <table class="table table-sm mb-0">
        <thead><tr><th>Vacuna</th><th>Aplicada</th><th>Próxima</th></tr></thead>
        <tbody>
        @foreach($m->vacunas as $v)
          <tr>
            <td>{{ $v->tipo_vacuna }}</td>
            <td>{{ $v->fecha_aplicacion->format('d/m/Y') }}</td>
            <td>{{ $v->fecha_proxima_dosis?->format('d/m/Y') ?? '-' }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-6">
    <h6>Consultas recientes</h6>
    @foreach($m->consultas as $c)
    <div class="card mb-2"><div class="card-body small">
      <b>{{ $c->fecha_hora->format('d/m/Y') }}</b> — {{ $c->motivo }}<br>
      <b>Próxima cita:</b> {{ $c->proxima_cita?->format('d/m/Y') ?? '-' }}
      @if($c->receta)
        <br><a class="btn btn-sm btn-outline-success mt-1" href="{{ route('cliente.receta',$c->id_consulta) }}">⬇ Receta PDF</a>
      @endif
    </div></div>
    @endforeach
  </div>
</div>
@endsection