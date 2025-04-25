<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = User::pluck('id')->toArray(); // Asegúrate de tener usuarios creados

        // Si no hay usuarios, no continuamos
        if (empty($usuarios)) {
            $this->command->warn('No hay usuarios en la tabla users para asignar a creado_por / actualizado_por.');
            return;
        }

        foreach (range(1, 20) as $i) {
            $creadoPor = fake()->randomElement($usuarios);
            $fechaCreacion = fake()->dateTimeBetween('-1 year', 'now');
            $actualizadoPor = fake()->randomElement($usuarios);
            $fechaActualizacion = fake()->dateTimeBetween($fechaCreacion, 'now');

            Persona::create([
                'nombre' => fake()->firstName(),
                'apellido' => fake()->lastName(),
                'numero_identidad' => fake()->unique()->numerify('0801########'),
                'telefono' => fake()->phoneNumber(),
                'direccion' => fake()->address(),
                'ruta_foto' => null,
                'tipo' => fake()->randomElement(['Cliente', 'Socio']),
                'estado' => fake()->boolean(80), // 80% de probabilidad de estar activo
                'creado_por' => $creadoPor,
                'fecha_creacion' => $fechaCreacion,
                'actualizado_por' => $actualizadoPor,
                'fecha_actualizacion' => $fechaActualizacion,
            ]);
        }
    }
}
