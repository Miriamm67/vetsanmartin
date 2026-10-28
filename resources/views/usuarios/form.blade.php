@extends('layouts.app')
@section('contenido')
<h4>{{ $u->id_usuario ? 'Editar' : 'Nuevo' }} Usuario</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $u->id_usuario ? route('admin.usuarios.update',$u->id_usuario) : route('admin.usuarios.store') }}">
  @csrf @if($u->id_usuario) @method('PUT') @endif
  <div class="row g-2">
    <div class="col-md-6"><label class="form-label">Usuario *</label>
      <input name="nombre_usuario" class="form-control" value="{{ old('nombre_usuario',$u->nombre_usuario) }}" maxlength="50" required></div>
    @unless($u->id_usuario)
    <div class="col-md-6"><label class="form-label">Contraseña *</label>
      <input type="password" name="contrasena" class="form-control" minlength="6" required></div>
    @else
    <div class="col-12"><small class="text-muted">Para cambiar tu propia contraseña, usa el apartado «Mi contraseña».</small></div>
    @endunless
    <div class="col-md-4"><label class="form-label">Rol *</label>
      <select name="rol" class="form-select" required>
        <option value="admin" {{ old('rol',$u->rol)=='admin'?'selected':'' }}>admin</option>
        <option value="cliente" {{ old('rol',$u->rol)=='cliente'?'selected':'' }}>cliente</option>
      </select></div>
    <div class="col-md-4"><label class="form-label">Vincular a propietario (cliente)</label>
      <select name="id_propietario" class="form-select">
        <option value="">—</option>
        @foreach($propietarios as $p)
          <option value="{{ $p->id_propietario }}" {{ old('id_propietario',$u->id_propietario)==$p->id_propietario?'selected':'' }}>{{ $p->nombre_completo }}</option>
        @endforeach
      </select></div>
    @if($u->id_usuario)
    <div class="col-md-4"><label class="form-label">Estado</label>
      <select name="estado" class="form-select" required>
        <option {{ $u->estado=='activo'?'selected':'' }}>activo</option>
        <option {{ $u->estado=='inactivo'?'selected':'' }}>inactivo</option>
      </select></div>
    @endif
  </div>
  <div class="mt-3"><button class="btn btn-primary">Guardar</button>
  <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Volver</a></div>
</form>
</div></div>
@endsection