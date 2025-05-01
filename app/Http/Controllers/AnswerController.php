<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Answer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnswerController extends Controller
{
    public function getAvailableSurveys(Request $request)
    {
        try {
            $userId = $request->user_id;

            // Obtener encuestas activas que no tengan respuestas del usuario
            $surveys = Survey::with('questions.options')
                ->where('state', true)
                ->whereDoesntHave('questions.answers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();

            if ($surveys->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No hay encuestas pendientes para este usuario.']);
            }

            return response()->json(['success' => true, 'surveys' => $surveys]);
        } catch (\Exception $e) {
            Log::error('Error al obtener encuestas disponibles: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al obtener encuestas disponibles.'], 500);
        }
    }

    public function saveAnswers(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'survey_id' => 'required|exists:survey_surveys,id',
                'answers' => 'required|array',
                'answers.*.question_id' => 'required|exists:survey_questions,id',
                'answers.*.option_id' => 'required|exists:survey_options,id',
            ]);

            foreach ($validatedData['answers'] as $answerData) {
                Answer::create([
                    'user_id' => $validatedData['user_id'],
                    'survey_id' => $validatedData['survey_id'],
                    'question_id' => $answerData['question_id'],
                    'option_id' => $answerData['option_id'],
                ]);
            }

            // Obtener encuestas actualizadas
            $surveys = Survey::with('questions.options')
                ->where('state', true)
                ->whereDoesntHave('questions.answers', function ($query) use ($validatedData) {
                    $query->where('user_id', $validatedData['user_id']);
                })
                ->get();

            $users = User::with(['answers' => function ($query) {
                $query->select('user_id', 'survey_id')->distinct();
            }, 'answers.survey'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Respuestas guardadas exitosamente.',
                'surveys' => $surveys,
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al guardar respuestas: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al guardar respuestas.'], 500);
        }
    }
}
