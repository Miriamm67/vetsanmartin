<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
  body{font-family: DejaVu Sans, sans-serif; font-size:11px}
  h2{margin:0 0 6px 0}
  h3{font-size:13px; margin:10px 0 4px 0}
  table{width:100%; border-collapse:collapse; margin-bottom:8px}
  td,th{border:1px solid #999; padding:3px 5px}
</style></head>
<body>
<h2>HISTORIA CLÍNICA — {{ $m->nombre }}</h2>
<p>{{ $m->especie }} · {{ $m->raza ?? 'mestizo' }} · Sexo: {{ $m->sexo ?? '-' }} ·
   F.N.: {{ $m->fecha_nacimiento?->format('d/m/Y') ?? '-' }} ·
   Dueño: {{ $m->propietario->nombre_completo }} ({{ $m->propietario->telefono }})</p>

<h3>CONSULTAS</h3>
<table>
  <tr><th>Fecha</th><th>Motivo / Síntomas</th><th>Examen</th><th>Diagnóstico</th><th>Tratamiento</th><th>Evolución</th></tr>
  @foreach($m->consultas as $c)
  <tr>
    <td>{{ $c->fecha_hora->format('d/m/Y') }}</td>
    <td>{{ $c->motivo }}<br>{{ $c->sintomas }}</td>
    <td>{{ $c->peso }} kg · {{ $c->temperatura }} °C<br>FC {{ $c->fc }} · FR {{ $c->fr }}</td>
    <td>{{ $c->diagnostico }}</td>
    <td>@foreach($c->tratamientos as $t){{ $t->medicamento }} ({{ $t->dosis }})@if(!$loop->last), @endif @endforeach</td>
    <td>{{ $c->evolucion_pronostico }}</td>
  </tr>
  @endforeach
</table>

<h3>MÉTODOS COMPLEMENTARIOS</h3>
<table>
  <tr><th>Fecha ord.</th><th>Examen</th><th>Resultados</th></tr>
  @foreach($m->consultas->flatMap->metodos as $mt)
    <tr><td>{{ $mt->fecha_orden?->format('d/m/Y') }}</td><td>{{ $mt->examen }}</td><td>{{ $mt->resultados }}</td></tr>
  @endforeach
</table>

<h3>VACUNAS</h3>
<table>
  <tr><th>Vacuna</th><th>Lote</th><th>Aplicada</th><th>Próxima</th></tr>
  @foreach($m->vacunas as $v)
    <tr><td>{{ $v->tipo_vacuna }}</td><td>{{ $v->lote }}</td>
    <td>{{ $v->fecha_aplicacion->format('d/m/Y') }}</td><td>{{ $v->fecha_proxima_dosis?->format('d/m/Y') ?? '-' }}</td></tr>
  @endforeach
</table>

<br>
<table><tr><td style="width:50%"><br><br>FIRMA DEL PROPIETARIO</td>
<td><br><br>FIRMA DEL MÉDICO VETERINARIO</td></tr></table>
</body></html>