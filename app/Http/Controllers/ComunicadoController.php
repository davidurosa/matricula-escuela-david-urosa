<?php

namespace App\Http\Controllers;

use App\Models\Comunicacion;
use Illuminate\Http\Request;

class ComunicadoController extends Controller
{
    public function index()
    {
        $comunicados = Comunicacion::with(['padre', 'curso'])
            ->orderBy('fecha_envio', 'desc')
            ->get();
            
        return view('comunicados.index', compact('comunicados'));
    }
    
    public function create()
    {
        return view('comunicados.create');
    }
}
