<?php

namespace App\Livewire;

use App\Models\Matricula;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Computed;

class PagosMatricula extends Component
{
    public $matricula_id;
    public $metodo_pago = 'efectivo';
    public $monto;
    public $referencia_pago;
    public $fecha;
    
    protected $rules = [
        'matricula_id' => 'required|exists:matriculas,id',
        'metodo_pago' => 'required|in:efectivo,transferencia',
        'monto' => 'required|numeric|min:0',
        'referencia_pago' => 'required_if:metodo_pago,transferencia',
        'fecha' => 'required|date|before_or_equal:today',
    ];
    
    public function mount($matricula_id = null)
    {
        $this->matricula_id = $matricula_id;
        $this->fecha = now()->format('Y-m-d');
    }
    
    public function registrarPago()
    {
        $this->validate();
        
        DB::beginTransaction();
        
        try {
            // Crear registro de pago
            Pago::create([
                'matricula_id' => $this->matricula_id,
                'metodo' => $this->metodo_pago,
                'monto' => $this->monto,
                'fecha' => $this->fecha
            ]);
            
            DB::commit();
            
            // Limpiar formulario
            $this->reset(['metodo_pago', 'monto', 'referencia_pago']);
            $this->fecha = now()->format('Y-m-d');
            
            $this->dispatch('pago-registrado');
            session()->flash('message', 'Pago registrado exitosamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }
    
    #[Computed]
    public function matricula()
    {
        return Matricula::with(['estudiante', 'curso', 'pagos'])->find($this->matricula_id);
    }
    
    #[Computed]
    public function pagos()
    {
        return $this->matricula_id ? Pago::where('matricula_id', $this->matricula_id)->orderBy('fecha', 'desc')->get() : collect();
    }
    
    #[Computed]
    public function total_pagado()
    {
        return $this->pagos->sum('monto');
    }
    
    #[Computed]
    public function costo_curso()
    {
        return $this->matricula ? $this->matricula->curso->costo : 0;
    }
    
    #[Computed]
    public function saldo_pendiente()
    {
        return $this->costo_curso - $this->total_pagado;
    }
    
    public function render()
    {
        return view('livewire.pagos-matricula');
    }
}
