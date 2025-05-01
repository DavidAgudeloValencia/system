<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        try {
            // Cargar todos los usuarios junto con las encuestas que han contestado (sin duplicados)
            $users = User::with(['answers' => function ($query) {
                $query->select('user_id', 'survey_id')->distinct();
            }, 'answers.survey'])->get();

            return Inertia::render('Users/Index', [
                'users' => $users
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cargar la lista de usuarios: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al cargar la lista de usuarios.'], 500);
        }
    }

    public function create()
    {
        //
    }

    public function edit(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'user_document' => 'required|string|max:20|unique:users,user_document',
                'apartment' => 'nullable|string|max:10',
            ]);

            User::create($validatedData);

            $users = User::with(['answers' => function ($query) {
                $query->select('user_id', 'survey_id')->distinct();
            }, 'answers.survey'])->get();
            return response()->json(['success' => true, 'message' => 'Usuario creado exitosamente.', 'users' => $users]);
        } catch (\Throwable $e) {
            Log::error('Error al crear el usuario: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al crear el usuario.'], 500);
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);

            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $user->id . '|max:255',
                'user_document' => 'nullable|string|max:20|unique:users,user_document,' . $user->id,
                'apartment' => 'nullable|string|max:10',
            ]);

            $user->update($validatedData);

            $users = User::with(['answers' => function ($query) {
                $query->select('user_id', 'survey_id')->distinct();
            }, 'answers.survey'])->get();
            return response()->json(['success' => true, 'message' => 'Usuario actualizado exitosamente.', 'users' => $users]);
        } catch (\Throwable $e) {
            Log::error('Error al actualizar el usuario: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al actualizar el usuario.'], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);

            $user->delete();

            $users = User::with(['answers' => function ($query) {
                $query->select('user_id', 'survey_id')->distinct();
            }, 'answers.survey'])->get();
            return response()->json(['success' => true, 'message' => 'Usuario eliminado exitosamente.', 'users' => $users]);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar el usuario: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al eliminar el usuario.'], 500);
        }
    }
}
