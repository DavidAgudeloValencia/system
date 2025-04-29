<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::all();
        return response()->json($surveys);
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'required|boolean',
        ]);

        $survey = Survey::create($validatedData);

        return response()->json($survey, 201);
    }
    public function update(Request $request, $id)
    {
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'state' => 'nullable|boolean',
        ]);

        $survey->update($validatedData);

        return response()->json($survey);
    }
    public function destroy($id)
    {
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }

        $survey->delete();

        return response()->json(['message' => 'Survey deleted successfully']);
    }
}
