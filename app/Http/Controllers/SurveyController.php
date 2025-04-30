<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SurveyController extends Controller
{
    public function index()
    {
        try {
            $surveys = Survey::with('questions.options')->get(); // Cargar preguntas y opciones asociadas
            return Inertia::render('Surveys/Index', [
                'surveys' => $surveys,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cargar la lista de encuestas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al cargar la lista de encuestas.');
        }
    }

    public function show($id)
    {
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }

        return response()->json($survey);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'state' => 'required|boolean',
            ]);

            Survey::create($validatedData);

            return redirect()->route('surveys.index')
                ->with('message', 'Encuesta creada exitosamente.');
        } catch (\Throwable $e) {
            Log::error('Error al crear la encuesta: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al crear la encuesta.');
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

            return redirect()->route('surveys.index')
                ->with('message', 'Encuesta actualizada exitosamente.');
        } catch (\Throwable $e) {
            Log::error('Error al actualizar la encuesta: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar la encuesta.');
        }
    }

    public function destroy(Request $request)
    {
        try {
            $survey = Survey::findOrFail($request->id);

            $survey->delete();
            return redirect()->back()
                ->with('message', 'Encuesta eliminada exitosamente.');
        } catch (\Throwable $e) {
            Log::error('Error al eliminar la encuesta: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar la encuesta.');
        }
    }
}
