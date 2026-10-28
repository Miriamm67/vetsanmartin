<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Tabla VETERINARIOS: perfil profesional ligado 1:1 a un usuario
        Schema::create('veterinarios', function (Blueprint $table) {
            $table->id('id_veterinario');
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->string('nombre_completo', 120);
            $table->string('nro_colegiado', 40)->nullable();
            $table->string('especialidad', 80)->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });

        // 2) CONSULTAS: quién atendió cada consulta
        Schema::table('consultas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_veterinario')->nullable()->after('id_usuario');
            $table->foreign('id_veterinario')->references('id_veterinario')->on('veterinarios');
        });

        // 3) ADJUNTOS CLÍNICOS: archivos subidos desde el botón del formulario
        Schema::create('adjuntos_consultas', function (Blueprint $table) {
            $table->id('id_adjunto');
            $table->unsignedBigInteger('id_mascota');
            $table->unsignedBigInteger('id_consulta')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->enum('categoria', ['radiografia', 'laboratorio', 'foto_clinica', 'documento', 'otro'])->default('otro');
            $table->string('nombre_original', 150);
            $table->string('ruta_archivo', 255);
            $table->string('tipo_mime', 100)->nullable();
            $table->unsignedInteger('peso_kb')->nullable();
            $table->timestamps();
            $table->foreign('id_mascota')->references('id_mascota')->on('mascotas')->onDelete('cascade');
            $table->foreign('id_consulta')->references('id_consulta')->on('consultas')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });

        // 4) MASCOTAS: fotografía de identificación
        Schema::table('mascotas', function (Blueprint $table) {
            $table->string('foto', 255)->nullable()->after('senas_particulares');
        });

        // 5) VACUNAS: control de cumplimiento de la próxima dosis
        Schema::table('vacunas', function (Blueprint $table) {
            $table->enum('estado_proxima', ['pendiente', 'aplicada', 'omitida'])->default('pendiente')->after('fecha_proxima_dosis');
            $table->string('observacion', 200)->nullable()->after('estado_proxima');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adjuntos_consultas');
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropForeign(['id_veterinario']);
            $table->dropColumn('id_veterinario');
        });
        Schema::dropIfExists('veterinarios');
        Schema::table('mascotas', fn (Blueprint $table) => $table->dropColumn('foto'));
        Schema::table('vacunas', fn (Blueprint $table) => $table->dropColumn(['estado_proxima', 'observacion']));
    }
};