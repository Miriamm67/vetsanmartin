@extends('layouts.app')
@section('contenido')
<div class="d-flex justify-content-between mb-3">
  <h4>Propietarios</h4>
  <a href="{{ route('admin.propietarios.create') }}" class="btn btn-primary">+ Nuevo</a>
</div>
<form class="mb-3">
  <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nombre...">
</form>
<div class="card">
  <table class="table table-hover mb-0">
    <thead><tr><th>Nombre</th><th>Teléfono</th><th>Zona</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    @foreach($propietarios as $p)
    <tr>
      <td>{{ $p->nombre_completo }}</td><td>{{ $p->telefono }}</td><td>{{ $p->zona }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.propietarios.edit',$p->id_propietario) }}">Editar</a>
        <form class="d-inline" method="POST" action="{{ route('admin.propietarios.destroy',$p->id_propietario) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Dar de baja?')">Baja</button>
        </form>
      </td>
    </tr>
    @endforeach
    </tbody>
  </table>
</div>
{{ $propietarios->links() }}
@endsection