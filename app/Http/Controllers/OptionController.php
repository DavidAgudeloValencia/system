<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index(Request $request)
{
    $options = Option::query();

    if ($request->filled('question_id')) {
        $options->where('question_id', $request->question_id);
    }

    return response()->json($options->get());
}
public function show($id)
{
    $option = Option::find($id);

    if (!$option) {
        return response()->json(['message' => 'Option not found'], 404);
    }

    return response()->json($option);
}
public function store(Request $request)
{
    $validatedData = $request->validate([
        'option' => 'required|string|max:255',
        'question_id' => 'required|exists:survey_questions,id',
    ]);

    $option = Option::create($validatedData);

    return response()->json($option, 201);
}
public function update(Request $request, $id)
{
    $option = Option::find($id);

    if (!$option) {
        return response()->json(['message' => 'Option not found'], 404);
    }

    $validatedData = $request->validate([
        'option' => 'nullable|string|max:255',
        'question_id' => 'nullable|exists:survey_questions,id',
    ]);

    $option->update($validatedData);

    return response()->json($option);
}
public function destroy($id)
{
    $option = Option::find($id);

    if (!$option) {
        return response()->json(['message' => 'Option not found'], 404);
    }

    $option->delete();

    return response()->json(['message' => 'Option deleted successfully']);
}

}
