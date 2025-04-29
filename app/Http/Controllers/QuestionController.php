<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
{
    $questions = Question::query();

    if ($request->filled('survey_id')) {
        $questions->where('survey_id', $request->survey_id);
    }

    return response()->json($questions->get());
}
public function show($id)
{
    $question = Question::find($id);

    if (!$question) {
        return response()->json(['message' => 'Question not found'], 404);
    }

    return response()->json($question);
}
public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'survey_id' => 'required|exists:survey_surveys,id',
    ]);

    $question = Question::create($validatedData);

    return response()->json($question, 201);
}
public function update(Request $request, $id)
{
    $question = Question::find($id);

    if (!$question) {
        return response()->json(['message' => 'Question not found'], 404);
    }

    $validatedData = $request->validate([
        'title' => 'nullable|string|max:255',
        'survey_id' => 'nullable|exists:survey_surveys,id',
    ]);

    $question->update($validatedData);

    return response()->json($question);
}
public function destroy($id)
{
    $question = Question::find($id);

    if (!$question) {
        return response()->json(['message' => 'Question not found'], 404);
    }

    $question->delete();

    return response()->json(['message' => 'Question deleted successfully']);
}

}
