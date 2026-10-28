<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Usuario::create([
            'nombre_usuario' => 'admin',
            'contrasena_hash' => Hash::make('password'),
            'rol' => 'admin',
            'estado' => 'activo',
        ]);

        \App\Models\Veterinario::firstOrCreate(
            ['id_usuario' => 1],
            [
                'nombre_completo' => 'Ismael Sacama Ramírez',
                'nro_colegiado'   => '762',
                'especialidad'    => 'Clínica de animales menores y mayores',
                'estado'          => 'activo',
            ]
        );
    }
}