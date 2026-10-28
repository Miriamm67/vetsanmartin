<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Vet San Martín')</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('vet.css') }}" rel="stylesheet">
</head>
<body>
@if(auth()->check())
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ auth()->user()->rol === 'cliente' ? route('cliente.historial') : route('admin.dashboard') }}">🐾 Veterinaria San Martín</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuVet">
            <i class="bi bi-list text-white fs-2"></i>
        </button>
        <div class="collapse navbar-collapse" id="menuVet">
            <ul class="navbar-nav me-auto">
                @if(auth()->user()->rol === 'cliente')
                    <li class="nav-item"><a class="nav-link" href="{{ route('cliente.historial') }}"><i class="bi bi-heart-fill"></i> Mis mascotas</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-house-heart-fill"></i> Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.propietarios.index') }}"><i class="bi bi-people-fill"></i> Propietarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.mascotas.index') }}"><i class="bi bi-heart-fill"></i> Mascotas</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.consultas.index') }}"><i class="bi bi-calendar2-week"></i> Consultas</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.veterinarios.index') }}"><i class="bi bi-person-vcard"></i> Veterinarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.usuarios.index') }}"><i class="bi bi-person-gear"></i> Usuarios</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="{{ route('password.edit') }}"><i class="bi bi-key-fill"></i> Mi contraseña</a></li>
            </ul>
            <span class="navbar-text text-white me-3"><i class="bi bi-person-circle"></i> {{ auth()->user()->nombre_usuario }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-vet-claro btn-sm">Salir <i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </div>
</nav>
@endif

<main class="container py-4">
    @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @hasSection('content')
        @yield('content')
    @else
        @yield('contenido')
    @endif
</main>

<footer class="footer-vet">🐾 Veterinaria San Martín — Sistema de Historiales Clínicos · Dr. Ismael Sacama Ramírez · El Alto, Bolivia</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function (form) {
        let alertaValidacionVisible = false;

        const actualizarValidacionesPersonalizadas = function () {
            form.querySelectorAll('[data-after-field], [data-after-or-equal-field], [data-match-field], [data-max-size], [data-max-files], [data-allowed-extensions]').forEach(function (field) {
                let mensaje = '';

                if (field.dataset.afterField || field.dataset.afterOrEqualField) {
                    const nombreCampo = field.dataset.afterField || field.dataset.afterOrEqualField;
                    const campoReferencia = form.elements.namedItem(nombreCampo);
                    const valorReferencia = campoReferencia && 'value' in campoReferencia ? campoReferencia.value : '';
                    const esPosterior = Boolean(field.dataset.afterField);

                    if (field.value && valorReferencia && (esPosterior ? field.value <= valorReferencia : field.value < valorReferencia)) {
                        mensaje = esPosterior
                            ? 'La fecha debe ser posterior a la fecha relacionada.'
                            : 'La fecha debe ser igual o posterior a la fecha relacionada.';
                    }
                }

                if (!mensaje && field.dataset.matchField) {
                    const campoReferencia = form.elements.namedItem(field.dataset.matchField);
                    if (campoReferencia && 'value' in campoReferencia && field.value !== campoReferencia.value) {
                        mensaje = 'La confirmación no coincide.';
                    }
                }

                if (!mensaje && field.type === 'file' && field.files) {
                    const maximoArchivos = Number(field.dataset.maxFiles || 0);
                    const maximoBytes = Number(field.dataset.maxSize || 0);
                    const extensiones = (field.dataset.allowedExtensions || '').split(',').filter(Boolean);

                    if (maximoArchivos && field.files.length > maximoArchivos) {
                        mensaje = 'Puedes seleccionar como máximo ' + maximoArchivos + ' archivos.';
                    } else {
                        for (const archivo of field.files) {
                            const extension = archivo.name.split('.').pop().toLowerCase();
                            if ((maximoBytes && archivo.size > maximoBytes) || (extensiones.length && !extensiones.includes(extension))) {
                                mensaje = maximoBytes && archivo.size > maximoBytes
                                    ? 'Cada archivo debe pesar como máximo ' + Math.floor(maximoBytes / 1024 / 1024) + ' MB.'
                                    : 'El formato del archivo no está permitido.';
                                break;
                            }
                        }
                    }
                }

                field.setCustomValidity(mensaje);
            });
        };

        form.addEventListener('input', actualizarValidacionesPersonalizadas);
        form.addEventListener('change', actualizarValidacionesPersonalizadas);
        actualizarValidacionesPersonalizadas();

        form.addEventListener('invalid', function (event) {
            if (!window.Swal) return;
            event.preventDefault();
            if (alertaValidacionVisible) return;

            alertaValidacionVisible = true;
            const campoInvalido = event.target;
            const mensaje = campoInvalido.validationMessage || 'Revisa los campos obligatorios y los valores ingresados.';

            Swal.fire('Formulario inválido', mensaje, 'warning').then(function () {
                campoInvalido.focus();
            });
            window.setTimeout(function () {
                alertaValidacionVisible = false;
            }, 0);
        }, true);
    });
});
</script>
</body>
</html>