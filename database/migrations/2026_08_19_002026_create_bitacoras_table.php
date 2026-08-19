<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacoras', function (Blueprint $t) {
            $t->id('id_bitacora');
            $t->unsignedBigInteger('id_usuario');
            $t->string('tabla', 60);
            $t->unsignedBigInteger('registro_id');
            $t->enum('accion', ['create','update','delete']);
            $t->dateTime('fecha_hora')->useCurrent();
            $t->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });
    }
    public function down(): void { Schema::dropIfExists('bitacoras'); }
};