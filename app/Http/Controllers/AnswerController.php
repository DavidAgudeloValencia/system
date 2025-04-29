<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function index(Request $request)
{
    $answers = Answer::query();

    if ($request->filled('user_id')) {
        $answers->where('user_id', $request->user_id);
    }

    if ($request->filled('survey_id')) {
        $answers->where('survey_id', $request->survey_id);
    }

    if ($request->filled('question_id')) {
        $answers->where('question_id', $request->question_id);
    }

    return response()->json($answers->with('user', 'survey', 'question', 'option')->get());
}
public function show($id)
{
    $answer = Answer::with('user', 'survey', 'question', 'option')->find($id);

    if (!$answer) {
        return response()->json(['message' => 'Answer not found'], 404);
    }

    return response()->json($answer);
}
public function store(Request $request)
{
    $validatedData = $request->validate([
        'user_id' => 'required|exists:users,id',
        'survey_id' => 'required|exists:survey_surveys,id',
        'question_id' => 'required|exists:survey_questions,id',
        'option_id' => 'nullable|exists:survey_options,id',
    ]);

    $answer = Answer::create($validatedData);

    return response()->json($answer, 201);
}
public function update(Request $request, $id)
{
    $answer = Answer::find($id);

    if (!$answer) {
        return response()->json(['message' => 'Answer not found'], 404);
    }

    $validatedData = $request->validate([
        'user_id' => 'nullable|exists:users,id',
        'survey_id' => 'nullable|exists:survey_surveys,id',
        'question_id' => 'nullable|exists:survey_questions,id',
        'option_id' => 'nullable|exists:survey_options,id',
    ]);

    $answer->update($validatedData);

    return response()->json($answer);
}
public function destroy($id)
{
    $answer = Answer::find($id);

    if (!$answer) {
        return response()->json(['message' => 'Answer not found'], 404);
    }

    $answer->delete();

    return response()->json(['message' => 'Answer deleted successfully']);
}

}
