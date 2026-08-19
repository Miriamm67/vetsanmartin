<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recetas', function (Blueprint $t) {
            $t->id('id_receta');
            $t->unsignedBigInteger('id_consulta')->unique();
            $t->text('notas')->nullable();
            $t->timestamps();
            $t->foreign('id_consulta')->references('id_consulta')->on('consultas')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('recetas'); }
};