<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AdjuntoController, LoginController, PropietarioController, MascotaController, ConsultaController,
    VacunaController, ReporteController, ClienteController, UsuarioController, VeterinarioController};
use App\Models\Consulta;
use App\Models\Vacuna;
use Illuminate\Http\Request;
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->esAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('cliente.historial');
    }
    return redirect()->route('login');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class,'show'])->name('login');
    Route::post('/login', [LoginController::class,'login']);
});
Route::post('/logout', [LoginController::class,'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth','rol:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function (Request $request) {
        $request->validate(['fecha' => 'nullable|date']);
        $fecha = $request->input('fecha', now()->toDateString());
        $alertas = Vacuna::proximas()->with('mascota')->get();
        $consultas = Consulta::with(['mascota.propietario', 'veterinario'])
            ->whereDate('fecha_hora', $fecha)
            ->orderByDesc('fecha_hora')
            ->paginate(10)
            ->withQueryString();
        return view('admin.dashboard', compact('alertas', 'consultas', 'fecha'));
    })->name('dashboard');

    Route::resource('propietarios', PropietarioController::class);
    Route::resource('mascotas', MascotaController::class);
    Route::resource('usuarios', UsuarioController::class)->except(['show']);

    Route::get('consultas/crear', [ConsultaController::class,'create'])->name('consultas.create');
    Route::post('consultas', [ConsultaController::class,'store'])->name('consultas.store');
    Route::get('consultas/{id}/editar', [ConsultaController::class,'edit'])->name('consultas.edit');
    Route::put('consultas/{id}', [ConsultaController::class,'update'])->name('consultas.update');

    Route::post('vacunas', [VacunaController::class,'store'])->name('vacunas.store');
    Route::put('vacunas/{id}', [VacunaController::class,'update'])->name('vacunas.update');

    Route::get('consultas/{id}/receta.pdf', [ReporteController::class,'receta'])->name('receta.pdf');
    Route::get('mascotas/{id}/historial.pdf', [ReporteController::class,'historial'])->name('historial.pdf');
    Route::get('mascotas/{id}/historial', [ReporteController::class, 'historialPreview'])->name('historial.preview');
    Route::get('mascotas/{id}/historial/visualizar-pdf', [ReporteController::class, 'historialPdfPreview'])->name('historial.pdf.preview');
        // ===== CORRECCIONES DEL TUTOR (octubre 2026) =====
    Route::get('consultas/listado', [ConsultaController::class, 'index'])->name('consultas.index');
    Route::get('vacunas/{id}/edit', [VacunaController::class, 'edit'])->name('vacunas.edit');
    Route::put('vacunas/{id}', [VacunaController::class, 'update'])->name('vacunas.update');
    Route::patch('vacunas/{id}/aplicada', [VacunaController::class, 'marcarAplicada'])->name('vacunas.aplicada');
    Route::patch('vacunas/{id}/omitida', [VacunaController::class, 'marcarOmitida'])->name('vacunas.omitida');
    Route::post('adjuntos', [AdjuntoController::class, 'store'])->name('adjuntos.store');
    Route::delete('adjuntos/{id}', [AdjuntoController::class, 'destroy'])->name('adjuntos.destroy');

    Route::get('veterinarios', [VeterinarioController::class, 'index'])->name('veterinarios.index');
    Route::get('veterinarios/create', [VeterinarioController::class, 'create'])->name('veterinarios.create');
    Route::post('veterinarios', [VeterinarioController::class, 'store'])->name('veterinarios.store');
    Route::get('veterinarios/{id}/edit', [VeterinarioController::class, 'edit'])->name('veterinarios.edit');
    Route::put('veterinarios/{id}', [VeterinarioController::class, 'update'])->name('veterinarios.update');
    Route::patch('veterinarios/{id}/estado', [VeterinarioController::class, 'toggleEstado'])->name('veterinarios.toggle');
});

Route::middleware(['auth','rol:cliente'])->prefix('portal')->name('cliente.')->group(function () {
    Route::get('/historial', [ClienteController::class,'historial'])->name('historial');
    Route::get('/mascota/{id}', [ClienteController::class,'detalle'])->name('detalle');
    Route::get('/recetas/{id}/pdf', [ClienteController::class,'descargarReceta'])->name('receta');
});
Route::middleware('auth')->group(function () {
    Route::get('cambiar-password', [UsuarioController::class, 'editPassword'])->name('password.edit');
    Route::put('cambiar-password', [UsuarioController::class, 'updatePassword'])->name('password.update');
});
