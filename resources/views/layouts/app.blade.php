<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('titulo','Vet San Martín')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
@auth
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🐾 Vet San Martín</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="menu">
      @if(auth()->user()->esAdmin())
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.propietarios.index') }}">Propietarios</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.mascotas.index') }}">Mascotas</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.usuarios.index') }}">Usuarios</a></li>
      </ul>
      @else
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('cliente.historial') }}">Mis Mascotas</a></li>
      </ul>
      @endif
      <form method="POST" action="{{ route('logout') }}" class="d-flex">
        @csrf
        <button class="btn btn-outline-light btn-sm">Salir ({{ auth()->user()->nombre_usuario }})</button>
      </form>
    </div>
  </div>
</nav>
@endauth
<main class="container py-4">
  @if(session('ok'))<div class="alert alert-success py-2">{{ session('ok') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
  @yield('contenido')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>