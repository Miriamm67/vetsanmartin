@extends('layouts.app')
@section('titulo','Iniciar Sesión')
@section('contenido')
<div class="d-flex justify-content-center align-items-center" style="min-height:90vh">
  <div class="card shadow" style="max-width:400px;width:100%">
    <div class="card-body p-4">
      <h5 class="card-title text-center mb-1">Veterinaria "San Martín"</h5>
      <p class="text-muted text-center small mb-4">Sistema de Historiales Clínicos</p>
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Usuario</label>
          <input type="text" name="nombre_usuario" class="form-control" value="{{ old('nombre_usuario') }}" maxlength="50" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="contrasena" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Ingresar</button>
      </form>
    </div>
  </div>
</div>
@endsection