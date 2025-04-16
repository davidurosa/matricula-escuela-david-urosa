<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $matriculas = Matricula::with(['estudiante', 'curso', 'pagos'])->get();
        return view('pagos.index', compact('matriculas'));
    }
    
    public function show($id)
    {
        $matricula = Matricula::with(['estudiante', 'curso', 'pagos'])->findOrFail($id);
        return view('pagos.show', compact('matricula'));
    }
}
