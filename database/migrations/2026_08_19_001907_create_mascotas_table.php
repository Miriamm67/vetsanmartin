<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mascotas', function (Blueprint $t) {
            $t->id('id_mascota');
            $t->unsignedBigInteger('id_propietario')->index();
            $t->string('nombre', 80)->index('idx_mascotas_nombre');
            $t->enum('especie', ['perro','gato','ave','roedor'])->index('idx_mascotas_especie');
            $t->string('raza', 60)->nullable();
            $t->tinyInteger('edad')->nullable();
            $t->date('fecha_nacimiento')->nullable();
            $t->enum('sexo', ['macho','hembra'])->nullable();
            $t->string('color', 40)->nullable();
            $t->string('senas_particulares', 200)->nullable();
            $t->enum('estado', ['activo','inactivo'])->default('activo');
            $t->timestamps();
            $t->foreign('id_propietario')->references('id_propietario')->on('propietarios');
        });
    }
    public function down(): void { Schema::dropIfExists('mascotas'); }
};