<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'rol' => 'Administrador',
                'estado' => true,
            ],
            [
                'name' => 'allan',
                'email' => 'allan@example.com',
                'password' => Hash::make('allan123'),
                'rol' => 'Administrador',
                'estado' => true,
            ],
            [
                'name' => 'anubis',
                'email' => 'anubis@example.com',
                'password' => Hash::make('anubis123'),
                'rol' => 'Supervisor',
                'estado' => true,
            ],
            [
                'name' => 'Supervisor Juan',
                'email' => 'supervisor@example.com',
                'password' => Hash::make('supervisor123'),
                'rol' => 'Supervisor',
                'estado' => true,
            ],
            [
                'name' => 'Usuario Pedro',
                'email' => 'usuario@example.com',
                'password' => Hash::make('usuario123'),
                'rol' => 'Usuario',
                'estado' => false,
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::updateOrCreate(
                ['email' => $usuario['email']],
                $usuario
            );
        }
    }
}
