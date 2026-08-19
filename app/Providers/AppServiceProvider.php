<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    foreach ([\App\Models\Propietario::class,\App\Models\Mascota::class,\App\Models\Consulta::class,
              \App\Models\Tratamiento::class,\App\Models\MetodoComplementario::class,\App\Models\Vacuna::class,
              \App\Models\Receta::class,\App\Models\Usuario::class] as $m) {
        $m::observe(\App\Observers\BitacoraObserver::class);
    }
}
}
