<?php

use App\Http\Controllers\AnswerController;
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

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::patch('/users', [UserController::class, 'update'])->name('users.update');
Route::delete('/users', [UserController::class, 'destroy'])->name('users.destroy');

// Surveys
Route::get('/surveys', function () {
    return Inertia::render('Surveys/Index');
})->name('surveys');

Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
Route::patch('/surveys', [SurveyController::class, 'update'])->name('surveys.update');
Route::delete('/surveys', [SurveyController::class, 'destroy'])->name('surveys.destroy');

// Questions
Route::post('/questions/update', [QuestionController::class, 'update'])->name('questions.update');

// Answers
Route::post('/answers/getAvailableSurveys', [AnswerController::class, 'getAvailableSurveys'])->name('answers.getAvailableSurveys');
Route::post('/answers/saveAnswers', [AnswerController::class, 'saveAnswers'])->name('answers.saveAnswers');

Route::get('/user/{userId}/answers', [QuestionController::class, 'getUserAnswers'])->name('user.answers');
Route::get('/user/{userId}/survey/{surveyId}/answers', [QuestionController::class, 'getUserAnswers'])->name('user.survey.answers');
