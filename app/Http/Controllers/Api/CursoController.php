<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CursoController extends Controller
{
    /**
     * Mostrar listado de cursos
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Curso::query();
        
        // Filtro por academia
        if ($request->has('academia_id')) {
            $query->where('academia_id', $request->academia_id);
        }
        
        $cursos = $query->with('academia')->get();
        
        return response()->json([
            'success' => true,
            'data' => $cursos
        ]);
    }

    /**
     * Almacenar un nuevo curso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'academia_id' => 'required|exists:academias,id',
            'precio' => 'required|numeric|min:0',
            'cupo_maximo' => 'nullable|integer|min:1',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $curso = Curso::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $curso,
            'message' => 'Curso creado exitosamente'
        ], 201);
    }

    /**
     * Mostrar un curso específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $curso = Curso::with(['academia', 'matriculas.estudiante'])->find($id);
        
        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $curso
        ]);
    }

    /**
     * Actualizar un curso
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $curso = Curso::find($id);
        
        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
            'academia_id' => 'sometimes|required|exists:academias,id',
            'precio' => 'sometimes|required|numeric|min:0',
            'cupo_maximo' => 'nullable|integer|min:1',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $curso->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $curso,
            'message' => 'Curso actualizado exitosamente'
        ]);
    }

    /**
     * Eliminar un curso
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $curso = Curso::find($id);
        
        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado'
            ], 404);
        }

        // Verificar si tiene matrículas asociadas
        if ($curso->matriculas()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el curso porque tiene matrículas asociadas'
            ], 400);
        }

        $curso->delete();

        return response()->json([
            'success' => true,
            'message' => 'Curso eliminado exitosamente'
        ]);
    }
}
