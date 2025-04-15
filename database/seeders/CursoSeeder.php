<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Academia;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $academiaPrincipal = Academia::where('nombre', 'Academia Principal')->first()->id;
        $academiaArtes = Academia::where('nombre', 'Academia de Artes')->first()->id;
        $academiaDeportes = Academia::where('nombre', 'Academia de Deportes')->first()->id;

        // Cursos para Academia Principal
        Curso::create([
            'academia_id' => $academiaPrincipal,
            'nombre' => 'Matemáticas Avanzadas',
            'descripcion' => 'Curso intensivo de matemáticas para estudiantes de nivel avanzado',
            'costo' => 250.00,
            'duracion' => 48,
            'modalidad' => 'presencial'
        ]);

        Curso::create([
            'academia_id' => $academiaPrincipal,
            'nombre' => 'Física Básica',
            'descripcion' => 'Introducción a los conceptos fundamentales de la física',
            'costo' => 200.00,
            'duracion' => 36,
            'modalidad' => 'presencial'
        ]);

        Curso::create([
            'academia_id' => $academiaPrincipal,
            'nombre' => 'Programación para Niños',
            'descripcion' => 'Curso de programación básica para niños de 8 a 12 años',
            'costo' => 180.00,
            'duracion' => 24,
            'modalidad' => 'virtual'
        ]);

        // Cursos para Academia de Artes
        Curso::create([
            'academia_id' => $academiaArtes,
            'nombre' => 'Pintura al Óleo',
            'descripcion' => 'Aprende técnicas de pintura al óleo desde cero',
            'costo' => 300.00,
            'duracion' => 40,
            'modalidad' => 'presencial'
        ]);

        Curso::create([
            'academia_id' => $academiaArtes,
            'nombre' => 'Piano para Principiantes',
            'descripcion' => 'Curso básico de piano para personas sin experiencia previa',
            'costo' => 350.00,
            'duracion' => 32,
            'modalidad' => 'presencial'
        ]);

        Curso::create([
            'academia_id' => $academiaArtes,
            'nombre' => 'Fotografía Digital',
            'descripcion' => 'Aprende a sacar el máximo provecho a tu cámara digital',
            'costo' => 220.00,
            'duracion' => 20,
            'modalidad' => 'mixta'
        ]);

        // Cursos para Academia de Deportes
        Curso::create([
            'academia_id' => $academiaDeportes,
            'nombre' => 'Fútbol Infantil',
            'descripcion' => 'Entrenamiento de fútbol para niños de 6 a 10 años',
            'costo' => 150.00,
            'duracion' => 48,
            'modalidad' => 'presencial'
        ]);

        Curso::create([
            'academia_id' => $academiaDeportes,
            'nombre' => 'Natación Avanzada',
            'descripcion' => 'Perfeccionamiento de técnicas de natación para nivel intermedio-avanzado',
            'costo' => 280.00,
            'duracion' => 36,
            'modalidad' => 'presencial'
        ]);

        Curso::create([
            'academia_id' => $academiaDeportes,
            'nombre' => 'Yoga para Todos',
            'descripcion' => 'Clases de yoga adaptadas a todos los niveles y edades',
            'costo' => 120.00,
            'duracion' => 24,
            'modalidad' => 'mixta'
        ]);
    }
}
