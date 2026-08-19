<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
  body{font-family: DejaVu Sans, sans-serif; font-size:12px}
  h2{margin:0}
  .head{border-bottom:2px solid #333; padding-bottom:8px; margin-bottom:12px}
  table{width:100%; border-collapse:collapse}
  td,th{border:1px solid #999; padding:4px 6px; font-size:11px}
</style></head>
<body>
<div class="head">
  <h2>Veterinaria "San Martín"</h2>
  Zona Villa Mercedes "D" · El Alto · Tel. 67007040
</div>
<p><b>Receta N° {{ $c->id_consulta }}</b> — {{ $c->fecha_hora->format('d/m/Y') }}</p>
<p><b>Paciente:</b> {{ $c->mascota->nombre }} ({{ $c->mascota->especie }}) ·
   <b>Propietario:</b> {{ $c->mascota->propietario->nombre_completo }}</p>
<p><b>Diagnóstico:</b> {{ $c->diagnostico }}</p>
<table>
  <tr><th>Medicamento</th><th>Dosis</th><th>Duración</th></tr>
  @foreach($c->tratamientos as $t)
    <tr><td>{{ $t->medicamento }}</td><td>{{ $t->dosis }}</td><td>{{ $t->duracion }}</td></tr>
  @endforeach
</table>
@if($c->receta && $c->receta->notas)
  <p><b>Indicaciones:</b> {{ $c->receta->notas }}</p>
@endif
<br><br>
_________________________<br>Dr. Ismael Sacama Ramírez<br>Médico Veterinario
</body></html>