<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tratamientos', function (Blueprint $t) {
            $t->id('id_tratamiento');
            $t->unsignedBigInteger('id_consulta');
            $t->date('fecha')->nullable();
            $t->string('medicamento', 120);
            $t->string('dosis', 120)->nullable();
            $t->string('duracion', 80)->nullable();
            $t->foreign('id_consulta')->references('id_consulta')->on('consultas')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('tratamientos'); }
};