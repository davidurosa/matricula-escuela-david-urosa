<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comunicacion;
use App\Models\Padre;
use App\Models\Estudiante;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComunicadoController extends Controller
{
    /**
     * Mostrar listado de comunicados
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Comunicacion::query();
        
        // Filtros
        if ($request->has('padre_id')) {
            $query->where('padre_id', $request->padre_id);
        }
        
        if ($request->has('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }
        
        if ($request->has('fecha_desde')) {
            $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta')) {
            $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);
        }
        
        $comunicados = $query->with(['padre', 'curso'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $comunicados
        ]);
    }

    /**
     * Almacenar un nuevo comunicado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'filtro_tipo' => 'required|in:todos,curso,edad',
            'curso_id' => 'required_if:filtro_tipo,curso|exists:cursos,id',
            'edad_min' => 'required_if:filtro_tipo,edad|integer|min:1',
            'edad_max' => 'required_if:filtro_tipo,edad|integer|gte:edad_min'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $padres_ids = [];
            $fecha_envio = now();
            
            // Seleccionar padres según el filtro
            if ($request->filtro_tipo === 'todos') {
                // Seleccionar todos los padres que tienen estudiantes
                $padres_ids = Padre::has('estudiantes')->pluck('id')->toArray();
            } 
            elseif ($request->filtro_tipo === 'curso' && $request->curso_id) {
                // Seleccionar padres de estudiantes en un curso específico
                $padres_ids = Estudiante::whereHas('matriculas', function($query) use ($request) {
                    $query->where('curso_id', $request->curso_id);
                })->pluck('padre_id')->unique()->toArray();
            } 
            elseif ($request->filtro_tipo === 'edad' && $request->edad_min && $request->edad_max) {
                // Seleccionar padres de estudiantes en un rango de edad
                $fechaMin = Carbon::now()->subYears($request->edad_max)->format('Y-m-d');
                $fechaMax = Carbon::now()->subYears($request->edad_min)->format('Y-m-d');
                
                $padres_ids = Estudiante::whereBetween('fecha_nacimiento', [$fechaMin, $fechaMax])
                    ->pluck('padre_id')
                    ->unique()
                    ->toArray();
            }
            
            if (empty($padres_ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron destinatarios para el filtro seleccionado'
                ], 400);
            }
            
            // Crear comunicados para cada padre
            $comunicados = [];
            foreach ($padres_ids as $padre_id) {
                $comunicado = Comunicacion::create([
                    'titulo' => $request->titulo,
                    'mensaje' => $request->mensaje,
                    'fecha_envio' => $fecha_envio,
                    'padre_id' => $padre_id,
                    'curso_id' => $request->filtro_tipo === 'curso' ? $request->curso_id : null
                ]);
                
                $comunicados[] = $comunicado;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Comunicado enviado exitosamente a ' . count($padres_ids) . ' padres',
                'data' => [
                    'total_enviados' => count($padres_ids),
                    'comunicado_ejemplo' => $comunicados[0] ?? null
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el comunicado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un comunicado específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comunicado = Comunicacion::with(['padre', 'curso'])->find($id);
        
        if (!$comunicado) {
            return response()->json([
                'success' => false,
                'message' => 'Comunicado no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $comunicado
        ]);
    }

    /**
     * Eliminar un comunicado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comunicado = Comunicacion::find($id);
        
        if (!$comunicado) {
            return response()->json([
                'success' => false,
                'message' => 'Comunicado no encontrado'
            ], 404);
        }

        $comunicado->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comunicado eliminado exitosamente'
        ]);
    }
    
    /**
     * Obtener comunicados por padre
     *
     * @param  int  $padreId
     * @return \Illuminate\Http\Response
     */
    public function comunicadosPorPadre($padreId)
    {
        $padre = Padre::find($padreId);
        
        if (!$padre) {
            return response()->json([
                'success' => false,
                'message' => 'Padre no encontrado'
            ], 404);
        }
        
        $comunicados = Comunicacion::where('padre_id', $padreId)
            ->with('curso')
            ->orderBy('fecha_envio', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'padre' => $padre,
                'comunicados' => $comunicados
            ]
        ]);
    }
}
