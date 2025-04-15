<?php

namespace App\Livewire;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Computed;

class MatriculaForm extends Component
{
    // Form steps
    public $currentStep = 1;
    public $totalSteps = 4;
    
    // Step 1: Course selection
    public $curso_id;
    public $academia_id;
    
    // Step 2: Student information
    public $estudiante_nombre;
    public $estudiante_apellido;
    public $estudiante_fecha_nacimiento;
    public $estudiante_genero;
    public $estudiante_observaciones;
    
    // Padre information
    public $padre_id;
    public $padre_nombre;
    public $padre_email;
    public $padre_telefono;
    public $usar_padre_existente = false;
    
    // Step 3: Payment information
    public $metodo_pago = 'efectivo';
    public $referencia_pago;
    
    // Step 4: Confirmation
    public $fecha_inicio;
    public $estado = 'activo';
    
    protected $rules = [
        // Step 1
        'curso_id' => 'required|exists:cursos,id',
        
        // Step 2
        'estudiante_nombre' => 'required|string|max:100',
        'estudiante_apellido' => 'required|string|max:100',
        'estudiante_fecha_nacimiento' => 'required|date|before:today',
        'estudiante_genero' => 'required|in:masculino,femenino,otro',
        'estudiante_observaciones' => 'nullable|string',
        
        // Padre information
        'padre_id' => 'required_if:usar_padre_existente,true|nullable|exists:padres,id',
        'padre_nombre' => 'required_if:usar_padre_existente,false|nullable|string|max:100',
        'padre_email' => 'required_if:usar_padre_existente,false|nullable|email|max:100',
        'padre_telefono' => 'required_if:usar_padre_existente,false|nullable|string|max:20',
        
        // Step 3
        'metodo_pago' => 'required|in:efectivo,transferencia',
        'referencia_pago' => 'required_if:metodo_pago,transferencia',
        
        // Step 4
        'fecha_inicio' => 'required|date|after_or_equal:today',
        'estado' => 'required|in:activo,inactivo,completado'
    ];
    
    public function mount($curso_id = null)
    {
        $this->curso_id = $curso_id;
        $this->fecha_inicio = date('Y-m-d');
        
        if ($curso_id) {
            $curso = Curso::find($curso_id);
            if ($curso) {
                $this->academia_id = $curso->academia_id;
            }
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'curso_id' => 'required|exists:cursos,id',
            ]);
        } elseif ($this->currentStep === 2) {
            $validationRules = [
                'estudiante_nombre' => 'required|string|max:100',
                'estudiante_apellido' => 'required|string|max:100',
                'estudiante_fecha_nacimiento' => 'required|date|before:today',
                'estudiante_genero' => 'required|in:masculino,femenino,otro',
            ];
            
            if ($this->usar_padre_existente) {
                $validationRules['padre_id'] = 'required|exists:padres,id';
            } else {
                $validationRules['padre_nombre'] = 'required|string|max:100';
                $validationRules['padre_email'] = 'required|email|max:100';
                $validationRules['padre_telefono'] = 'required|string|max:20';
            }
            
            $this->validate($validationRules);
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'metodo_pago' => 'required|in:efectivo,transferencia',
                'referencia_pago' => 'required_if:metodo_pago,transferencia',
            ]);
        }
        
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }
    
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }
    
    public function save()
    {
        $this->validate();
        
        DB::beginTransaction();
        
        try {
            // Create or find padre
            $padre_id = null;
            if ($this->usar_padre_existente) {
                $padre_id = $this->padre_id;
            } else {
                $padre = \App\Models\Padre::create([
                    'nombre' => $this->padre_nombre,
                    'email' => $this->padre_email,
                    'telefono' => $this->padre_telefono,
                ]);
                $padre_id = $padre->id;
            }
            
            // Create student
            $estudiante = Estudiante::create([
                'padre_id' => $padre_id,
                'nombre' => $this->estudiante_nombre,
                'apellido' => $this->estudiante_apellido,
                'fecha_nacimiento' => $this->estudiante_fecha_nacimiento,
                'genero' => $this->estudiante_genero,
                'observaciones' => $this->estudiante_observaciones,
            ]);
            
            // Create matricula
            $matricula = Matricula::create([
                'curso_id' => $this->curso_id,
                'estudiante_id' => $estudiante->id,
                'fecha_inscripcion' => now()->format('Y-m-d')
            ]);
            
            // Create payment record
            $curso = Curso::find($this->curso_id);
            
            Pago::create([
                'matricula_id' => $matricula->id,
                'monto' => $curso->costo,
                'metodo' => $this->metodo_pago,
                'fecha' => now()->format('Y-m-d')
            ]);
            
            DB::commit();
            
            // Reset form
            $this->reset([
                'currentStep',
                'estudiante_nombre',
                'estudiante_apellido',
                'estudiante_fecha_nacimiento',
                'estudiante_genero',
                'estudiante_observaciones',
                'padre_id',
                'padre_nombre',
                'padre_email',
                'padre_telefono',
                'usar_padre_existente',
                'metodo_pago',
                'referencia_pago'
            ]);
            
            $this->currentStep = 1;
            $this->dispatch('matricula-created');
            session()->flash('message', 'Matrícula creada exitosamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al crear la matrícula: ' . $e->getMessage());
        }
    }

    public function updatedCursoId($value)
    {
        if ($value) {
            $curso = Curso::find($value);
            if ($curso) {
                $this->academia_id = $curso->academia_id;
            }
        }
    }
    
    public function updatedAcademiaId($value)
    {
        if ($value) {
            $this->curso_id = null;
            $academia = \App\Models\Academia::find($value);
            if ($academia) {
                session()->flash('info', 'Mostrando cursos de la academia: ' . $academia->nombre);
            }
        }
    }


    #[Computed]
    public function cursos(){
        return $this->academia_id 
            ? Curso::where('academia_id', $this->academia_id)->get() 
            : Curso::all();
    }


    #[Computed]
    public function curso_seleccionado(){
        return $this->curso_id ? Curso::find($this->curso_id) : null;
    }
    
    #[Computed]
    public function padres(){
        return \App\Models\Padre::orderBy('nombre')->get();
    }
    
    public function render()
    {
        return view('livewire.matricula-form', [
            'academias' => \App\Models\Academia::all(),
            
        ]);
    }
}
