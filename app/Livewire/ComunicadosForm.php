<?php

namespace App\Livewire;

use App\Models\Comunicacion;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Padre;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Computed;

class ComunicadosForm extends Component
{
    // Filtros para seleccionar destinatarios
    public $filtro_tipo = 'todos'; // todos, curso, edad
    public $curso_id;
    public $edad_min;
    public $edad_max;
    
    // Datos del comunicado
    public $titulo;
    public $mensaje;
    
    // Lista de padres seleccionados
    public $padres_seleccionados = [];
    
    protected $rules = [
        'titulo' => 'required|string|max:255',
        'mensaje' => 'required|string',
        'padres_seleccionados' => 'required|array|min:1',
        'padres_seleccionados.*' => 'exists:padres,id'
    ];
    
    public function mount()
    {
        $this->resetExcept('filtro_tipo');
    }
    
    public function updatedFiltroTipo()
    {
        $this->padres_seleccionados = [];
        $this->curso_id = null;
        $this->edad_min = null;
        $this->edad_max = null;
    }
    
    public function aplicarFiltro()
    {
        $this->padres_seleccionados = [];
        
        if ($this->filtro_tipo === 'todos') {
            // Seleccionar todos los padres
            $padres = Padre::has('estudiantes')->pluck('id')->toArray();
            $this->padres_seleccionados = $padres;
        } 
        elseif ($this->filtro_tipo === 'curso' && $this->curso_id) {
            // Seleccionar padres de estudiantes en un curso específico
            $padres = Estudiante::whereHas('matriculas', function($query) {
                $query->where('curso_id', $this->curso_id);
            })->pluck('padre_id')->unique()->toArray();
            
            $this->padres_seleccionados = $padres;
        } 
        elseif ($this->filtro_tipo === 'edad' && $this->edad_min && $this->edad_max) {
            // Seleccionar padres de estudiantes en un rango de edad
            $fechaMin = Carbon::now()->subYears($this->edad_max)->format('Y-m-d');
            $fechaMax = Carbon::now()->subYears($this->edad_min)->format('Y-m-d');
            
            $padres = Estudiante::whereBetween('fecha_nacimiento', [$fechaMin, $fechaMax])
                ->pluck('padre_id')
                ->unique()
                ->toArray();
                
            $this->padres_seleccionados = $padres;
        }
    }
    
    public function enviarComunicado()
    {
        $this->validate();
        
        DB::beginTransaction();
        
        try {
            $fecha_envio = now();
            
            foreach ($this->padres_seleccionados as $padre_id) {
                Comunicacion::create([
                    'titulo' => $this->titulo,
                    'mensaje' => $this->mensaje,
                    'fecha_envio' => $fecha_envio,
                    'padre_id' => $padre_id,
                    'curso_id' => $this->filtro_tipo === 'curso' ? $this->curso_id : null
                ]);
            }
            
            DB::commit();
            
            // Limpiar formulario
            $this->reset(['titulo', 'mensaje', 'padres_seleccionados']);
            
            session()->flash('message', 'Comunicado enviado exitosamente a ' . count($this->padres_seleccionados) . ' padres.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al enviar el comunicado: ' . $e->getMessage());
        }
    }
    
    #[Computed]
    public function cursos()
    {
        return Curso::orderBy('nombre')->get();
    }
    
    #[Computed]
    public function padres()
    {
        if (empty($this->padres_seleccionados)) {
            return collect();
        }
        
        return Padre::whereIn('id', $this->padres_seleccionados)
            ->with('estudiantes')
            ->get();
    }
    
    #[Computed]
    public function total_padres_seleccionados()
    {
        return count($this->padres_seleccionados);
    }
    
    public function render()
    {
        return view('livewire.comunicados-form');
    }
}
