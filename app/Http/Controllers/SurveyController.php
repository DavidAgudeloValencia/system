<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SurveyController extends Controller
{
    public function index()
    {
        try {
            $surveys = Survey::with('questions.options')->get();
            return Inertia::render('Surveys/Index', [
                'surveys' => $surveys,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cargar la lista de encuestas: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al cargar la lista de encuestas.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $survey = Survey::with('questions.options')->findOrFail($id);
            return response()->json(['success' => true, 'survey' => $survey]);
        } catch (\Exception $e) {
            Log::error('Error al cargar la encuesta: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Encuesta no encontrada.'], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'state' => 'required|boolean',
            ]);

            Survey::create($validatedData);

            $surveys = Survey::with('questions.options')->get(); // Obtener todas las encuestas actualizadas
            return response()->json(['success' => true, 'message' => 'Encuesta creada exitosamente.', 'surveys' => $surveys]);
        } catch (\Throwable $e) {
            Log::error('Error al crear la encuesta: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al crear la encuesta.'], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $survey = Survey::findOrFail($request->id);

            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'state' => 'nullable|boolean',
            ]);

            $survey->update($validatedData);

            $surveys = Survey::with('questions.options')->get(); // Obtener todas las encuestas actualizadas
            return response()->json(['success' => true, 'message' => 'Encuesta actualizada exitosamente.', 'surveys' => $surveys]);
        } catch (\Throwable $e) {
            Log::error('Error al actualizar la encuesta: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $survey = Survey::findOrFail($request->id);

            $survey->delete();

            $surveys = Survey::with('questions.options')->get(); // Obtener todas las encuestas actualizadas
            return response()->json(['success' => true, 'message' => 'Encuesta eliminada exitosamente.', 'surveys' => $surveys]);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar la encuesta: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al eliminar la encuesta.'], 500);
        }
    }
}
