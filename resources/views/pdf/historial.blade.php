<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
  @page{margin:28px 30px}
  body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#171717;line-height:1.4}
  h2{font-size:18px;margin:0 0 8px}
  h3{font-size:13px;margin:12px 0 6px}
  h4{font-size:12px;margin:0 0 5px}
  p{margin:4px 0}
  table{width:100%;border-collapse:collapse;margin-bottom:10px;font-size:12px}
  td,th{border:1px solid #999;padding:5px 6px;vertical-align:top}
  th{font-weight:bold;text-align:center}
  .pet-info{margin-bottom:12px}
  .consultations{table-layout:fixed}
  .consultations th:nth-child(1){width:11%}
  .consultations th:nth-child(2){width:20%}
  .consultations th:nth-child(3){width:15%}
  .consultations th:nth-child(4){width:20%}
  .consultations th:nth-child(5){width:18%}
  .consultations th:nth-child(6){width:16%}
  .consultation-row{page-break-inside:avoid}
  .detail-table th{width:22%;text-align:left;background:#f1f1f1}
  .muted{color:#666}
  .signatures{margin-top:22px}
</style>
</head>
<body>
<h2>HISTORIA CLÍNICA — {{ $m->nombre }}</h2>
<div class="pet-info">
  <p>{{ ucfirst($m->especie) }} · {{ $m->raza ?? 'Mestizo' }} · Sexo: {{ $m->sexo ?? '-' }} · Color: {{ $m->color ?? '-' }} · Edad: {{ $m->edad ?? '-' }} · F.N.: {{ $m->fecha_nacimiento?->format('d/m/Y') ?? '-' }}</p>
  <p><b>Dueño:</b> {{ $m->propietario->nombre_completo }} ({{ $m->propietario->telefono ?? '-' }})</p>
  <p><b>Señas particulares:</b> {{ $m->senas_particulares ?? '-' }}</p>
</div>

<h3>CONSULTAS ({{ $m->consultas->count() }})</h3>
<table class="consultations">
  <thead>
    <tr><th>Fecha</th><th>Motivo / Síntomas</th><th>Examen</th><th>Diagnóstico</th><th>Tratamiento</th><th>Evolución</th></tr>
  </thead>
  <tbody>
  @forelse($m->consultas as $c)
    <tr class="consultation-row">
      <td>{{ $c->fecha_hora->format('d/m/Y H:i') }}</td>
      <td>{{ $c->motivo }}@if($c->sintomas)<br>{{ $c->sintomas }}@endif</td>
      <td>{{ $c->peso ?? '-' }} kg<br>{{ $c->temperatura ?? '-' }} °C<br>FC {{ $c->fc ?? '-' }} · FR {{ $c->fr ?? '-' }}</td>
      <td>{{ $c->diagnostico }}</td>
      <td>
        @forelse($c->tratamientos as $t)
          {{ $t->medicamento }}@if($t->dosis) ({{ $t->dosis }})@endif
          @if($t->duracion)<br>{{ $t->duracion }}@endif
          @if(!$loop->last)<br>@endif
        @empty
          -
        @endforelse
      </td>
      <td>{{ $c->evolucion_pronostico ?? '-' }}</td>
    </tr>
  @empty
    <tr><td colspan="6" class="muted">No hay consultas registradas.</td></tr>
  @endforelse
  </tbody>
</table>

@if($m->consultas->isNotEmpty())
  <h3>OTROS DATOS CLÍNICOS</h3>
  @foreach($m->consultas as $c)
    <h4>{{ $c->fecha_hora->format('d/m/Y H:i') }} — {{ $c->motivo }}</h4>
    <table class="detail-table">
      <tr><th>Veterinario</th><td>{{ $c->veterinario?->nombre_completo ?? '-' }}</td></tr>
      <tr><th>Enfermedades anteriores</th><td>{{ $c->enfermedades_anteriores ?? '-' }}</td></tr>
      <tr><th>Tratamientos recientes</th><td>{{ $c->tratamientos_recientes ?? '-' }}</td></tr>
      <tr><th>Última desparasitación</th><td>{{ $c->ultima_desparasitacion?->format('d/m/Y') ?? '-' }}</td></tr>
      <tr><th>Examen y observaciones</th><td>
        Mucosa: {{ $c->mucosa ?? '-' }} · R.C.: {{ $c->rc_min ?? '-' }}<br>
        Inspección: {{ $c->inspeccion ?? '-' }}<br>
        Palpación: {{ $c->palpacion ?? '-' }}<br>
        Observaciones: {{ $c->observaciones ?? '-' }}<br>
        Próxima cita: {{ $c->proxima_cita?->format('d/m/Y') ?? '-' }}<br>
        Notas de receta: {{ $c->receta?->notas ?? '-' }}
      </td></tr>
      <tr><th>Métodos complementarios</th><td>
        @forelse($c->metodos as $mt)
          {{ $mt->fecha_orden?->format('d/m/Y') ?? '-' }} — {{ $mt->examen }}: {{ $mt->resultados ?? '-' }}@if(!$loop->last)<br>@endif
        @empty
          -
        @endforelse
      </td></tr>
      <tr><th>Archivos adjuntos</th><td>
        @forelse($c->adjuntos as $adjunto)
          {{ $adjunto->nombre_original }} ({{ str_replace('_', ' ', $adjunto->categoria) }})@if(!$loop->last), @endif
        @empty
          -
        @endforelse
      </td></tr>
    </table>
  @endforeach
@endif

<h3>VACUNAS</h3>
<table>
  <thead><tr><th>Vacuna</th><th>Lote</th><th>Aplicada</th><th>Próxima</th></tr></thead>
  <tbody>
  @forelse($m->vacunas as $v)
    <tr>
      <td>{{ $v->tipo_vacuna }}</td>
      <td>{{ $v->lote ?? '-' }}</td>
      <td>{{ $v->fecha_aplicacion?->format('d/m/Y') ?? '-' }}</td>
      <td>{{ $v->fecha_proxima_dosis?->format('d/m/Y') ?? '-' }}</td>
    </tr>
  @empty
    <tr><td colspan="4">No hay vacunas registradas.</td></tr>
  @endforelse
  </tbody>
</table>

<table class="signatures"><tr><td style="width:50%"><br><br>FIRMA DEL PROPIETARIO</td>
<td><br><br>FIRMA DEL MÉDICO VETERINARIO</td></tr></table>
</body>
</html>
