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
    }
}