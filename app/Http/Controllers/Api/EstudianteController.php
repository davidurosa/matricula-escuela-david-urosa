<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Padre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EstudianteController extends Controller
{
    /**
     * Mostrar listado de estudiantes
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Estudiante::query();
        
        // Filtros
        if ($request->has('padre_id')) {
            $query->where('padre_id', $request->padre_id);
        }
        
        $estudiantes = $query->with(['padre', 'matriculas.curso'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $estudiantes
        ]);
    }

    /**
     * Almacenar un nuevo estudiante
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:masculino,femenino,otro',
            'padre_id' => 'nullable|exists:padres,id',
            'padre_nombre' => 'required_without:padre_id|string|max:100',
            'padre_apellido' => 'required_without:padre_id|string|max:100',
            'padre_email' => 'required_without:padre_id|email|max:100',
            'padre_telefono' => 'required_without:padre_id|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            // Si no se proporciona un padre existente, crear uno nuevo
            if (!$request->padre_id) {
                $padre = Padre::create([
                    'nombre' => $request->padre_nombre,
                    'apellido' => $request->padre_apellido,
                    'email' => $request->padre_email,
                    'telefono' => $request->padre_telefono
                ]);
                
                $padre_id = $padre->id;
            } else {
                $padre_id = $request->padre_id;
            }
            
            // Crear el estudiante
            $estudiante = Estudiante::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'genero' => $request->genero,
                'padre_id' => $padre_id
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $estudiante->load('padre'),
                'message' => 'Estudiante creado exitosamente'
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el estudiante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un estudiante específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estudiante = Estudiante::with(['padre', 'matriculas.curso', 'matriculas.pagos'])->find($id);
        
        if (!$estudiante) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $estudiante
        ]);
    }

    /**
     * Actualizar un estudiante
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estudiante = Estudiante::find($id);
        
        if (!$estudiante) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:100',
            'apellido' => 'sometimes|required|string|max:100',
            'fecha_nacimiento' => 'sometimes|required|date',
            'genero' => 'sometimes|required|in:masculino,femenino,otro',
            'padre_id' => 'sometimes|required|exists:padres,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $estudiante->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $estudiante->load('padre'),
            'message' => 'Estudiante actualizado exitosamente'
        ]);
    }

    /**
     * Eliminar un estudiante
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estudiante = Estudiante::find($id);
        
        if (!$estudiante) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        // Verificar si tiene matrículas asociadas
        if ($estudiante->matriculas()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el estudiante porque tiene matrículas asociadas'
            ], 400);
        }

        $estudiante->delete();

        return response()->json([
            'success' => true,
            'message' => 'Estudiante eliminado exitosamente'
        ]);
    }
}
