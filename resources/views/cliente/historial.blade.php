@extends('layouts.app')
@section('contenido')
<h4 class="mb-3">Mis mascotas</h4>
<div class="row g-3">
@forelse($mascotas as $m)
  <div class="col-md-4">
    <div class="card h-100"><div class="card-body">
      <h5>🐾 {{ $m->nombre }}</h5>
      <p class="small text-muted mb-2">{{ $m->especie }} · {{ $m->raza ?? 'mestizo' }}</p>
      <a href="{{ route('cliente.detalle',$m->id_mascota) }}" class="btn btn-outline-primary btn-sm w-100">Ver historial y vacunas</a>
    </div></div>
  </div>
@empty
  <p class="text-muted">Aún no tienes mascotas registradas.</p>
@endforelse
</div>
@endsection