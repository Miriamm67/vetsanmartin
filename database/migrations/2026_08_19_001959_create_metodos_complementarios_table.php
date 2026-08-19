<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_complementarios', function (Blueprint $t) {
            $t->id('id_metodo');
            $t->unsignedBigInteger('id_consulta');
            $t->date('fecha_orden')->nullable();
            $t->string('examen', 150);
            $t->text('resultados')->nullable();
            $t->foreign('id_consulta')->references('id_consulta')->on('consultas')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('metodos_complementarios'); }
};