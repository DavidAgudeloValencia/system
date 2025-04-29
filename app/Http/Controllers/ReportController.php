<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Survey;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function listAnswersByDateRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'survey_id' => 'nullable|exists:survey_surveys,id',
        ]);

        $query = Answer::query()
            ->whereBetween('created_at', [$request->start_date, $request->end_date])
            ->with('user', 'survey', 'question', 'option');

        if ($request->filled('survey_id')) {
            $query->where('survey_id', $request->survey_id);
        }

        $answers = $query->get()->groupBy('user_id');

        return response()->json($answers);
    }
    public function listSurveysByUser($userId)
    {
        $surveys = Survey::whereHas('answers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('answers')->get();

        return response()->json($surveys);
    }
    public function listAnswersBySurvey($surveyId)
    {
        $answers = Answer::where('survey_id', $surveyId)
            ->with('user', 'question', 'option')
            ->get();

        return response()->json($answers);
    }
    public function getUserSurveyDetail($userId, $surveyId)
    {
        $answers = Answer::where('user_id', $userId)
            ->where('survey_id', $surveyId)
            ->with('question', 'option')
            ->get();

        return response()->json($answers);
    }
}
