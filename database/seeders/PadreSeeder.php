<?php

namespace Database\Seeders;

use App\Models\Padre;
use Illuminate\Database\Seeder;

class PadreSeeder extends Seeder
{
    public function run(): void
    {
        Padre::create([
            'nombre' => 'Juan Pérez',
            'email' => 'juan.perez@email.com',
            'telefono' => '0414-1234567',
        ]);

        Padre::create([
            'nombre' => 'María González',
            'email' => 'maria.gonzalez@email.com',
            'telefono' => '0424-7654321',
        ]);

        Padre::create([
            'nombre' => 'Carlos Rodríguez',
            'email' => 'carlos.rodriguez@email.com',
            'telefono' => '0412-9876543',
        ]);
    }
}
