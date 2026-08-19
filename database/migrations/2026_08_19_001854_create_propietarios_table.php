<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propietarios', function (Blueprint $t) {
            $t->id('id_propietario');
            $t->string('nombre_completo', 120)->index('idx_propietarios_nombre');
            $t->string('telefono', 20);
            $t->string('direccion', 200)->nullable();
            $t->string('zona', 100)->nullable();
            $t->string('calle_av', 150)->nullable();
            $t->string('numero', 20)->nullable();
            $t->string('nro_registro', 30)->nullable();
            $t->enum('estado', ['activo','inactivo'])->default('activo');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('propietarios'); }
};