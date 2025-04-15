<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeding in order based on dependencies
        $this->call([
            AcademiaSeeder::class,    // First, create academias
            CursoSeeder::class,       // Then courses that belong to academias
            PadreSeeder::class,       // Create parents
            EstudianteSeeder::class,  // Then students that belong to parents
            MatriculaSeeder::class,   // Then enrollments that need students and courses
           /*  PagoSeeder::class,        // Then payments that need enrollments */
            ComunicacionSeeder::class // Finally communications that may reference courses or parents
        ]);
    }
}
