<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacunas', function (Blueprint $t) {
            $t->id('id_vacuna');
            $t->unsignedBigInteger('id_mascota')->index();
            $t->string('tipo_vacuna', 80);
            $t->string('lote', 40)->nullable();
            $t->date('fecha_aplicacion');
            $t->date('fecha_proxima_dosis')->nullable()->index('idx_vacunas_proxima');
            $t->timestamps();
            $t->foreign('id_mascota')->references('id_mascota')->on('mascotas');
        });
    }
    public function down(): void { Schema::dropIfExists('vacunas'); }
};