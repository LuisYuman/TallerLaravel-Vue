<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use App\Exports\TareasPendientesExport;
use Maatwebsite\Excel\Facades\Excel;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tareas = Tarea::with('user:id,nombre,email')
            ->select('id', 'titulo', 'descripcion', 'estado', 'fecha_vencimiento', 'user_id', 'created_at')
            ->get();
        
        return response()->json($tareas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:pendiente,en progreso,completada',
            'fecha_vencimiento' => 'required|date|after_or_equal:today',
            'user_id' => 'required|exists:usuarios,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $tarea = Tarea::create($request->all());

        return response()->json([
            'message' => 'Tarea creada correctamente',
            'data' => $tarea->load('user:id,nombre,email')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tarea = Tarea::with('user:id,nombre,email')->find($id);
        
        if (!$tarea) {
            return response()->json([
                'message' => 'Tarea no encontrada'
            ], 404);
        }
        
        return response()->json($tarea);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tarea = Tarea::find($id);
        
        if (!$tarea) {
            return response()->json([
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'sometimes|required|in:pendiente,en progreso,completada',
            'fecha_vencimiento' => 'sometimes|required|date',
            'user_id' => 'sometimes|required|exists:usuarios,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $tarea->update($request->all());

        return response()->json([
            'message' => 'Tarea actualizada correctamente',
            'data' => $tarea->load('user:id,nombre,email')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tarea = Tarea::find($id);
        
        if (!$tarea) {
            return response()->json([
                'message' => 'Tarea no encontrada'
            ], 404);
        }
        
        $tarea->delete();
        
        return response()->json([
            'message' => 'Tarea eliminada correctamente'
        ]);
    }

    /**
     * Obtener usuarios para el selector
     */
    public function getUsers()
    {
        $usuarios = Usuario::select('id', 'nombre', 'email')->get();
        return response()->json($usuarios);
    }

    /**
     * Descargar reporte de tareas pendientes en Excel
     */
    public function downloadPendingReport()
    {
        $fileName = 'tareas_pendientes_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new TareasPendientesExport, $fileName);
    }
}
