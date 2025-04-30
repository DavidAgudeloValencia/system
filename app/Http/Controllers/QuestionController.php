<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'questions' => 'required|array',
                'questions.*.title' => 'required|string|max:255',
                'questions.*.survey_id' => 'required|exists:survey_surveys,id',
                'questions.*.options' => 'required|array|min:1',
                'questions.*.options.*.option' => 'required|string|max:255',
            ]);

            // Procesar las preguntas y opciones
            foreach ($validatedData['questions'] as $questionData) {
                // Crear la pregunta
                $question = Question::create([
                    'title' => $questionData['title'],
                    'survey_id' => $questionData['survey_id'],
                ]);

                // Crear las opciones asociadas
                foreach ($questionData['options'] as $optionData) {
                    Option::create([
                        'option' => $optionData['option'],
                        'question_id' => $question->id,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Preguntas y opciones guardadas exitosamente.']);
        } catch (\Exception $e) {
            Log::error('Error al guardar preguntas y opciones: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al guardar preguntas y opciones.'], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'questions' => 'required|array',
                'questions.*.title' => 'required|string|max:255',
                'questions.*.survey_id' => 'required|exists:survey_surveys,id',
                'questions.*.options' => 'required|array|min:1',
                'questions.*.options.*.option' => 'required|string|max:255',
            ]);

            // Eliminar todas las preguntas y opciones asociadas a la encuesta
            $surveyId = $validatedData['questions'][0]['survey_id'];
            $questionsToDelete = Question::where('survey_id', $surveyId)->get();

            foreach ($questionsToDelete as $question) {
                $question->options()->delete(); // Eliminar las opciones asociadas
                $question->delete(); // Eliminar la pregunta
            }

            // Crear las nuevas preguntas y opciones
            foreach ($validatedData['questions'] as $questionData) {
                $question = Question::create([
                    'title' => $questionData['title'],
                    'survey_id' => $questionData['survey_id'],
                ]);

                foreach ($questionData['options'] as $optionData) {
                    Option::create([
                        'option' => $optionData['option'],
                        'question_id' => $question->id,
                    ]);
                }
            }

            // Obtener las preguntas actualizadas con sus opciones
            $updatedQuestions = Question::where('survey_id', $surveyId)
                ->with('options')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Preguntas y opciones actualizadas exitosamente.',
                'questions' => $updatedQuestions,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar preguntas y opciones: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al actualizar preguntas y opciones.'], 500);
        }
    }

    public function destroy($id)
    {
        ///
    }
}
