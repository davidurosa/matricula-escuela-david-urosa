<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Academia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademiaController extends Controller
{
    /**
     * Mostrar listado de academias
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academias = Academia::withCount('cursos')->get();
        return response()->json([
            'success' => true,
            'data' => $academias
        ]);
    }

    /**
     * Almacenar una nueva academia
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $academia = Academia::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $academia,
            'message' => 'Academia creada exitosamente'
        ], 201);
    }

    /**
     * Mostrar una academia específica
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $academia = Academia::with('cursos')->find($id);
        
        if (!$academia) {
            return response()->json([
                'success' => false,
                'message' => 'Academia no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $academia
        ]);
    }

    /**
     * Actualizar una academia
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $academia = Academia::find($id);
        
        if (!$academia) {
            return response()->json([
                'success' => false,
                'message' => 'Academia no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $academia->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $academia,
            'message' => 'Academia actualizada exitosamente'
        ]);
    }

    /**
     * Eliminar una academia
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $academia = Academia::find($id);
        
        if (!$academia) {
            return response()->json([
                'success' => false,
                'message' => 'Academia no encontrada'
            ], 404);
        }

        // Verificar si tiene cursos asociados
        if ($academia->cursos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la academia porque tiene cursos asociados'
            ], 400);
        }

        $academia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Academia eliminada exitosamente'
        ]);
    }
}
