<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use Illuminate\Database\Seeder;

class EstudianteSeeder extends Seeder
{
    public function run(): void
    {
        Estudiante::create([
            'padre_id' => 1,
            'nombre' => 'Pedro',
            'apellido' => 'Pérez',
            'fecha_nacimiento' => '2010-05-15',
        ]);

        Estudiante::create([
            'padre_id' => 1,
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'fecha_nacimiento' => '2012-03-20',
        ]);

        Estudiante::create([
            'padre_id' => 2,
            'nombre' => 'Luis',
            'apellido' => 'González',
            'fecha_nacimiento' => '2011-08-10',
        ]);

        Estudiante::create([
            'padre_id' => 3,
            'nombre' => 'Carmen',
            'apellido' => 'Rodríguez',
            'fecha_nacimiento' => '2013-12-25',
        ]);
    }
}
