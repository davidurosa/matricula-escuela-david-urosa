<?php

namespace Database\Seeders;

use App\Models\Comunicacion;
use Illuminate\Database\Seeder;

class ComunicacionSeeder extends Seeder
{
    public function run(): void
    {
        // Comunicación para un curso
        Comunicacion::create([
            'titulo' => 'Inicio de Clases Matemáticas',
            'mensaje' => 'Las clases de Matemáticas Avanzadas comenzarán el próximo lunes.',
            'fecha_envio' => '2025-01-10 09:00:00',
            'curso_id' => 1,
            'padre_id' => null,
        ]);

        // Comunicación para un padre específico
        Comunicacion::create([
            'titulo' => 'Recordatorio de Pago',
            'mensaje' => 'Recordatorio del pago pendiente del curso.',
            'fecha_envio' => '2025-01-12 10:00:00',
            'curso_id' => null,
            'padre_id' => 1,
        ]);

        // Comunicación general para un curso
        Comunicacion::create([
            'titulo' => 'Material necesario para Pintura',
            'mensaje' => 'Lista de materiales necesarios para el curso de Pintura al Óleo.',
            'fecha_envio' => '2025-01-25 14:00:00',
            'curso_id' => 3,
            'padre_id' => null,
        ]);
    }
}
