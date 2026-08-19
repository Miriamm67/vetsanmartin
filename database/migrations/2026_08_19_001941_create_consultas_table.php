<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultas', function (Blueprint $t) {
            $t->id('id_consulta');
            $t->unsignedBigInteger('id_mascota')->index();
            $t->unsignedBigInteger('id_usuario');
            $t->dateTime('fecha_hora')->index('idx_consultas_fecha');
            $t->string('motivo', 200);
            $t->text('enfermedades_anteriores')->nullable();
            $t->text('tratamientos_recientes')->nullable();
            $t->date('ultima_desparasitacion')->nullable();
            $t->decimal('peso', 5, 2)->nullable();
            $t->decimal('temperatura', 4, 1)->nullable();
            $t->integer('fc')->nullable();
            $t->integer('fr')->nullable();
            $t->string('mucosa', 60)->nullable();
            $t->string('rc_min', 30)->nullable();
            $t->text('inspeccion')->nullable();
            $t->text('palpacion')->nullable();
            $t->text('sintomas')->nullable();
            $t->string('diagnostico', 200);
            $t->text('observaciones')->nullable();
            $t->text('evolucion_pronostico')->nullable();
            $t->date('proxima_cita')->nullable();
            $t->timestamps();
            $t->foreign('id_mascota')->references('id_mascota')->on('mascotas');
            $t->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });
    }
    public function down(): void { Schema::dropIfExists('consultas'); }
};