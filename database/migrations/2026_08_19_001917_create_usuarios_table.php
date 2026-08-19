<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $t) {
            $t->id('id_usuario');
            $t->string('nombre_usuario', 50)->unique();
            $t->string('contrasena_hash');
            $t->enum('rol', ['admin','cliente']);
            $t->unsignedBigInteger('id_propietario')->nullable()->unique();
            $t->enum('estado', ['activo','inactivo'])->default('activo');
            $t->rememberToken();
            $t->timestamps();
            $t->foreign('id_propietario')->references('id_propietario')->on('propietarios')->onDelete('set null');
        });
    }
    public function down(): void { Schema::dropIfExists('usuarios'); }
};