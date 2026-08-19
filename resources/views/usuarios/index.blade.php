@extends('layouts.app')
@section('contenido')
<div class="d-flex justify-content-between mb-3">
  <h4>Usuarios</h4>
  <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">+ Nuevo</a>
</div>
<div class="card">
  <table class="table mb-0">
    <thead><tr><th>Usuario</th><th>Rol</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    @foreach($usuarios as $u)
    <tr>
      <td>{{ $u->nombre_usuario }}</td>
      <td><span class="badge text-bg-{{ $u->rol=='admin'?'primary':'secondary' }}">{{ $u->rol }}</span></td>
      <td>{{ $u->estado }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.usuarios.edit',$u->id_usuario) }}">Editar</a>
        <form class="d-inline" method="POST" action="{{ route('admin.usuarios.destroy',$u->id_usuario) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Desactivar</button>
        </form>
      </td>
    </tr>
    @endforeach
    </tbody>
  </table>
</div>
{{ $usuarios->links() }}
@endsection