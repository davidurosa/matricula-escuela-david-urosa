<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Models\Estudiante;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MatriculaController extends Controller
{
    /**
     * Mostrar listado de matrículas
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Matricula::query();
        
        // Filtros
        if ($request->has('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }
        
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $matriculas = $query->with(['estudiante', 'curso', 'pagos'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $matriculas
        ]);
    }

    /**
     * Almacenar una nueva matrícula
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'curso_id' => 'required|exists:cursos,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha_inscripcion' => 'required|date',
            'estado' => 'required|in:pendiente,activa,completada,cancelada',
            'monto_inicial' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            // Crear la matrícula
            $matricula = Matricula::create([
                'curso_id' => $request->curso_id,
                'estudiante_id' => $request->estudiante_id,
                'fecha_inscripcion' => $request->fecha_inscripcion,
                'estado' => $request->estado
            ]);
            
            // Registrar el pago inicial si se proporciona
            if ($request->monto_inicial > 0) {
                Pago::create([
                    'matricula_id' => $matricula->id,
                    'monto' => $request->monto_inicial,
                    'metodo' => $request->metodo_pago,
                    'fecha' => now()->format('Y-m-d')
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $matricula->load(['estudiante', 'curso', 'pagos']),
                'message' => 'Matrícula creada exitosamente'
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la matrícula: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una matrícula específica
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $matricula = Matricula::with(['estudiante', 'curso', 'pagos'])->find($id);
        
        if (!$matricula) {
            return response()->json([
                'success' => false,
                'message' => 'Matrícula no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $matricula
        ]);
    }

    /**
     * Actualizar una matrícula
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $matricula = Matricula::find($id);
        
        if (!$matricula) {
            return response()->json([
                'success' => false,
                'message' => 'Matrícula no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'curso_id' => 'sometimes|required|exists:cursos,id',
            'estudiante_id' => 'sometimes|required|exists:estudiantes,id',
            'fecha_inscripcion' => 'sometimes|required|date',
            'estado' => 'sometimes|required|in:pendiente,activa,completada,cancelada'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $matricula->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $matricula->load(['estudiante', 'curso', 'pagos']),
            'message' => 'Matrícula actualizada exitosamente'
        ]);
    }

    /**
     * Eliminar una matrícula
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $matricula = Matricula::find($id);
        
        if (!$matricula) {
            return response()->json([
                'success' => false,
                'message' => 'Matrícula no encontrada'
            ], 404);
        }

        // Verificar si tiene pagos asociados
        if ($matricula->pagos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la matrícula porque tiene pagos asociados'
            ], 400);
        }

        $matricula->delete();

        return response()->json([
            'success' => true,
            'message' => 'Matrícula eliminada exitosamente'
        ]);
    }
}
