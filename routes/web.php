<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/users');
});

// Users
Route::get('/users', function () {
    return Inertia::render('Users/Index');
})->name('users');

Route::get('/users', [UserController::class, 'index'])->name('users.index'); // Show user list
Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Save new user
Route::patch('/users', [UserController::class, 'update'])->name('users.update'); // Update user
Route::delete('/users', [UserController::class, 'destroy'])->name('users.destroy'); // Delete user

// Surveys
Route::get('/surveys', function () {
    return Inertia::render('Surveys/Index');
})->name('surveys');

Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index'); // List surveys
Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store'); // Create survey
Route::patch('/surveys', [SurveyController::class, 'update'])->name('surveys.update'); // Update survey
Route::delete('/surveys', [SurveyController::class, 'destroy'])->name('surveys.destroy'); // Delete survey

// Questions
Route::post('/questions/update', [QuestionController::class, 'update'])->name('questions.update');
