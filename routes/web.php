<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LoginController, PropietarioController, MascotaController, ConsultaController,
    VacunaController, ReporteController, ClienteController, UsuarioController};
use App\Models\Vacuna;
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
    Route::get('/', function () {
        $alertas = Vacuna::proximas()->with('mascota')->get();
        return view('admin.dashboard', compact('alertas'));
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
});

Route::middleware(['auth','rol:cliente'])->prefix('portal')->name('cliente.')->group(function () {
    Route::get('/historial', [ClienteController::class,'historial'])->name('historial');
    Route::get('/mascota/{id}', [ClienteController::class,'detalle'])->name('detalle');
    Route::get('/recetas/{id}/pdf', [ClienteController::class,'descargarReceta'])->name('receta');
});