@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-vet">
            <div class="card-cabecera"><i class="bi bi-shield-lock-fill"></i> Cambiar mi contraseña</div>
            <div class="card-body p-4">
                @if (session('ok'))
                    <div class="alert alert-success">{{ session('ok') }}</div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <label class="form-label fw-bold small">Contraseña actual *</label>
                    <input type="password" name="password_actual" class="form-control mb-1" required>
                    @error('password_actual')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                    <label class="form-label fw-bold small mt-2">Contraseña nueva *</label>
                    <input type="password" name="password_nueva" class="form-control mb-1" minlength="6" required>
                    @error('password_nueva')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                    <label class="form-label fw-bold small mt-2">Confirmar contraseña nueva *</label>
                    <input type="password" name="password_nueva_confirmation" class="form-control mb-3" minlength="6" data-match-field="password_nueva" required>

                    <button class="btn btn-primary w-100"><i class="bi bi-key-fill"></i> Actualizar contraseña</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection