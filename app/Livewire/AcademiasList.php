<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Academia;
use App\Models\Curso;
use App\Models\Estudiante;

class AcademiasList extends Component
{
    public function inscribir($cursoId)
    {
        // TODO: Implement inscription logic
    }

    public function render()
    {
        return view('livewire.academias-list', [
            'academias' => Academia::withCount('cursos')->with('cursos')->get(),
            'totalAcademias' => Academia::count(),
            'totalCursos' => Curso::count(),
            'totalEstudiantes' => Estudiante::count(),
        ]);
    }
}