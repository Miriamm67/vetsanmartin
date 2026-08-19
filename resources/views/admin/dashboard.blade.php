@extends('layouts.app')
@section('titulo','Panel')
@section('contenido')
<h4 class="mb-3">Panel del Veterinario</h4>
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="card text-bg-primary"><div class="card-body">
    <h6>Propietarios</h6><h3>{{ \App\Models\Propietario::activos()->count() }}</h3></div></div></div>
  <div class="col-6 col-md-3"><div class="card text-bg-success"><div class="card-body">
    <h6>Mascotas</h6><h3>{{ \App\Models\Mascota::activas()->count() }}</h3></div></div></div>
  <div class="col-6 col-md-3"><div class="card text-bg-info"><div class="card-body">
    <h6>Consultas</h6><h3>{{ \App\Models\Consulta::count() }}</h3></div></div></div>
  <div class="col-6 col-md-3"><div class="card text-bg-warning"><div class="card-body">
    <h6>Alertas vacunas</h6><h3>{{ $alertas->count() }}</h3></div></div></div>
</div>
<div class="card">
  <div class="card-header bg-warning bg-opacity-25">🔔 Vacunas próximas a vencer (30 días) — RF10</div>
  <table class="table table-sm table-hover mb-0">
    <thead><tr><th>Mascota</th><th>Vacuna</th><th>Próxima dosis</th></tr></thead>
    <tbody>
    @forelse($alertas as $a)
      <tr><td>{{ $a->mascota->nombre }}</td><td>{{ $a->tipo_vacuna }}</td>
      <td><span class="badge text-bg-danger">{{ $a->fecha_proxima_dosis->format('d/m/Y') }}</span></td></tr>
    @empty
      <tr><td colspan="3" class="text-center text-muted">Sin alertas ✔</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection