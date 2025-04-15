<?php

namespace Database\Seeders;

use App\Models\Academia;
use Illuminate\Database\Seeder;

class AcademiaSeeder extends Seeder
{
    public function run(): void
    {
        Academia::create([
            'nombre' => 'Academia Principal',
            'descripcion' => 'La academia principal de nuestra institución',
        ]);

        Academia::create([
            'nombre' => 'Academia de Artes',
            'descripcion' => 'Especializada en cursos de arte y música',
        ]);

        Academia::create([
            'nombre' => 'Academia de Deportes',
            'descripcion' => 'Dedicada a la formación deportiva integral',
        ]);
    }
}
