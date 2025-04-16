<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    /**
     * Mostrar listado de pagos
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Pago::query();
        
        // Filtros
        if ($request->has('matricula_id')) {
            $query->where('matricula_id', $request->matricula_id);
        }
        
        if ($request->has('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }
        
        if ($request->has('metodo')) {
            $query->where('metodo', $request->metodo);
        }
        
        $pagos = $query->with('matricula.estudiante')->get();
        
        return response()->json([
            'success' => true,
            'data' => $pagos
        ]);
    }

    /**
     * Almacenar un nuevo pago
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matricula_id' => 'required|exists:matriculas,id',
            'monto' => 'required|numeric|min:0.01',
            'metodo' => 'required|in:efectivo,transferencia',
            'fecha' => 'required|date',
            'referencia' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar que la matrícula exista y esté activa
        $matricula = Matricula::find($request->matricula_id);
        if (!$matricula) {
            return response()->json([
                'success' => false,
                'message' => 'La matrícula no existe'
            ], 404);
        }
        
        if ($matricula->estado === 'cancelada') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede registrar un pago para una matrícula cancelada'
            ], 400);
        }

        $pago = Pago::create([
            'matricula_id' => $request->matricula_id,
            'monto' => $request->monto,
            'metodo' => $request->metodo,
            'fecha' => $request->fecha,
            'referencia' => $request->referencia ?? null
        ]);

        return response()->json([
            'success' => true,
            'data' => $pago->load('matricula.estudiante'),
            'message' => 'Pago registrado exitosamente'
        ], 201);
    }

    /**
     * Mostrar un pago específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pago = Pago::with('matricula.estudiante')->find($id);
        
        if (!$pago) {
            return response()->json([
                'success' => false,
                'message' => 'Pago no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pago
        ]);
    }

    /**
     * Actualizar un pago
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pago = Pago::find($id);
        
        if (!$pago) {
            return response()->json([
                'success' => false,
                'message' => 'Pago no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'monto' => 'sometimes|required|numeric|min:0.01',
            'metodo' => 'sometimes|required|in:efectivo,transferencia',
            'fecha' => 'sometimes|required|date',
            'referencia' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $pago->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $pago->load('matricula.estudiante'),
            'message' => 'Pago actualizado exitosamente'
        ]);
    }

    /**
     * Eliminar un pago
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pago = Pago::find($id);
        
        if (!$pago) {
            return response()->json([
                'success' => false,
                'message' => 'Pago no encontrado'
            ], 404);
        }

        $pago->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pago eliminado exitosamente'
        ]);
    }
    
    /**
     * Obtener resumen de pagos por matrícula
     *
     * @param  int  $matriculaId
     * @return \Illuminate\Http\Response
     */
    public function resumenPorMatricula($matriculaId)
    {
        $matricula = Matricula::with(['curso', 'pagos'])->find($matriculaId);
        
        if (!$matricula) {
            return response()->json([
                'success' => false,
                'message' => 'Matrícula no encontrada'
            ], 404);
        }
        
        $totalPagado = $matricula->pagos->sum('monto');
        $precioCurso = $matricula->curso->precio;
        $saldoPendiente = max(0, $precioCurso - $totalPagado);
        
        return response()->json([
            'success' => true,
            'data' => [
                'matricula_id' => $matricula->id,
                'curso' => $matricula->curso->nombre,
                'precio_curso' => $precioCurso,
                'total_pagado' => $totalPagado,
                'saldo_pendiente' => $saldoPendiente,
                'pagos' => $matricula->pagos
            ]
        ]);
    }
}
