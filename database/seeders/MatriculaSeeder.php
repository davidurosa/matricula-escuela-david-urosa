<?php

namespace Database\Seeders;

use App\Models\Matricula;
use Illuminate\Database\Seeder;

class MatriculaSeeder extends Seeder
{
    public function run(): void
    {
        // Pedro Pérez en Matemáticas Avanzadas
        Matricula::create([
            'estudiante_id' => 1,
            'curso_id' => 1,
            'fecha_inscripcion' => '2025-01-15',
        ]);

        // Ana Pérez en Física Básica
        Matricula::create([
            'estudiante_id' => 2,
            'curso_id' => 2,
            'fecha_inscripcion' => '2025-01-15',
        ]);

        // Luis González en Pintura al Óleo
        Matricula::create([
            'estudiante_id' => 3,
            'curso_id' => 3,
            'fecha_inscripcion' => '2025-02-01',
        ]);

        // Carmen Rodríguez en Fútbol Juvenil
        Matricula::create([
            'estudiante_id' => 4,
            'curso_id' => 4,
            'fecha_inscripcion' => '2025-02-15',
        ]);
    }
}
