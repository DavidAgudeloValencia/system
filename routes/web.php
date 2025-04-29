<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/users');
});

// Users
Route::get('/users', function () {
    return Inertia::render('Users/Index');
})->name('users.index'); // Show user list

Route::post('/users', [UserController::class, 'store'])->name('users.index'); // Save new user
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.index'); // Update user
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.index'); // Delete user

// Surveys
Route::get('/surveys', function () {
    return Inertia::render('Surveys/Index');
})->name('surveys.index'); // List surveys

Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.index'); // Create survey
Route::put('/surveys/{id}', [SurveyController::class, 'update'])->name('surveys.index'); // Update survey
Route::delete('/surveys/{id}', [SurveyController::class, 'destroy'])->name('surveys.index'); // Delete survey


// Questions
Route::get('/questions', function () {
    return Inertia::render('Questions/Index');
})->name('questions.index'); // List questions

Route::post('/questions', [QuestionController::class, 'store'])->name('questions.index'); // Create question
Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('questions.index'); // Update question
Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.index'); // Delete question

// Options
Route::get('/options', function () {
    return Inertia::render('Options/Index');
})->name('options.index'); // List options

Route::post('/options', [OptionController::class, 'store'])->name('options.index'); // Create option
Route::put('/options/{id}', [OptionController::class, 'update'])->name('options.index'); // Update option
Route::delete('/options/{id}', [OptionController::class, 'destroy'])->name('options.index'); // Delete option


// Answers
Route::get('/answers', function () {
    return Inertia::render('Answers/Index');
})->name('answers.index'); // List answers

Route::post('/answers', [AnswerController::class, 'store'])->name('answers.index'); // Create answer
Route::put('/answers/{id}', [AnswerController::class, 'update'])->name('answers.index'); // Update answer
Route::delete('/answers/{id}', [AnswerController::class, 'destroy'])->name('answers.index'); // Delete answer


// Reports
Route::get('/reports/answers/date-range', function () {
    return Inertia::render('Reports/DateRange');
})->name('reports.dateRange'); // List answers by date range

Route::get('/reports/user/{userId}/surveys', function ($userId) {
    return Inertia::render('Reports/UserSurveys', ['userId' => $userId]);
})->name('reports.userSurveys'); // List surveys answered by user

Route::get('/reports/user/{userId}/survey/{surveyId}/detail', function ($userId, $surveyId) {
    return Inertia::render('Reports/SurveyDetail', ['userId' => $userId, 'surveyId' => $surveyId]);
})->name('reports.surveyDetail'); // Answer details