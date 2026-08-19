@extends('layouts.app')
@section('contenido')
<h4>{{ $m->id_mascota ? 'Editar' : 'Nueva' }} Mascota</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $m->id_mascota ? route('admin.mascotas.update',$m->id_mascota) : route('admin.mascotas.store') }}">
  @csrf @if($m->id_mascota) @method('PUT') @endif
  <div class="row g-2">
    <div class="col-md-6"><label class="form-label">Propietario *</label>
      <select name="id_propietario" class="form-select" required>
        @foreach($propietarios as $p)
          <option value="{{ $p->id_propietario }}" {{ old('id_propietario',$m->id_propietario)==$p->id_propietario?'selected':'' }}>{{ $p->nombre_completo }}</option>
        @endforeach
      </select></div>
    <div class="col-md-6"><label class="form-label">Nombre *</label>
      <input name="nombre" class="form-control" value="{{ old('nombre',$m->nombre) }}" required></div>
    <div class="col-md-3"><label class="form-label">Especie *</label>
      <select name="especie" class="form-select" required>
        @foreach(['perro','gato','ave','roedor'] as $e)
          <option {{ old('especie',$m->especie)==$e?'selected':'' }}>{{ $e }}</option>
        @endforeach
      </select></div>
    <div class="col-md-3"><label class="form-label">Raza</label>
      <input name="raza" class="form-control" value="{{ old('raza',$m->raza) }}"></div>
    <div class="col-md-2"><label class="form-label">Edad</label>
      <input type="number" name="edad" class="form-control" value="{{ old('edad',$m->edad) }}"></div>
    <div class="col-md-4"><label class="form-label">F. Nacimiento</label>
      <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento',$m->fecha_nacimiento?->format('Y-m-d')) }}"></div>
    <div class="col-md-2"><label class="form-label">Sexo</label>
      <select name="sexo" class="form-select">
        <option value="">-</option>
        <option {{ old('sexo',$m->sexo)=='macho'?'selected':'' }}>macho</option>
        <option {{ old('sexo',$m->sexo)=='hembra'?'selected':'' }}>hembra</option>
      </select></div>
    <div class="col-md-4"><label class="form-label">Color</label>
      <input name="color" class="form-control" value="{{ old('color',$m->color) }}"></div>
    <div class="col-md-8"><label class="form-label">Señas particulares</label>
      <input name="senas_particulares" class="form-control" value="{{ old('senas_particulares',$m->senas_particulares) }}"></div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.mascotas.index') }}" class="btn btn-secondary">Volver</a>
  </div>
</form>
</div></div>
@endsection