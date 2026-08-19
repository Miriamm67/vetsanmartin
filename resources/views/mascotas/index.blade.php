@extends('layouts.app')
@section('contenido')
<div class="d-flex justify-content-between mb-3">
  <h4>Mascotas</h4>
  <a href="{{ route('admin.mascotas.create') }}" class="btn btn-primary">+ Nueva</a>
</div>
<form class="row g-2 mb-3">
  <div class="col"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Mascota, dueño o raza..."></div>
  <div class="col-auto">
    <select name="especie" class="form-select">
      <option value="">Todas las especies</option>
      @foreach(['perro','gato','ave','roedor'] as $e)
        <option {{ request('especie')==$e?'selected':'' }}>{{ $e }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-auto"><button class="btn btn-outline-primary">Buscar</button></div>
</form>
<div class="card">
  <table class="table table-hover mb-0">
    <thead><tr><th>Nombre</th><th>Especie</th><th>Dueño</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    @foreach($mascotas as $m)
    <tr>
      <td><a href="{{ route('admin.mascotas.show',$m->id_mascota) }}">{{ $m->nombre }}</a></td>
      <td><span class="badge text-bg-secondary">{{ $m->especie }}</span></td>
      <td>{{ $m->propietario->nombre_completo }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.mascotas.edit',$m->id_mascota) }}">Editar</a>
        <form class="d-inline" method="POST" action="{{ route('admin.mascotas.destroy',$m->id_mascota) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Baja</button>
        </form>
      </td>
    </tr>
    @endforeach
    </tbody>
  </table>
</div>
{{ $mascotas->links() }}
@endsection