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

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index'); // Show user list
    Route::get('/create', [UserController::class, 'create'])->name('users.create'); // Page to create user
    Route::post('/', [UserController::class, 'store'])->name('users.store'); // Save new user
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show'); // Show user by ID
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); // Page to edit user
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update'); // Update user
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy'); // Delete user
});

Route::prefix('surveys')->group(function () {
    Route::get('/', [SurveyController::class, 'index'])->name('surveys.index'); // List surveys
    Route::post('/', [SurveyController::class, 'store'])->name('surveys.store'); // Create survey
    Route::get('/{id}', [SurveyController::class, 'show'])->name('surveys.show'); // Show survey
    Route::put('/{id}', [SurveyController::class, 'update'])->name('surveys.update'); // Update survey
    Route::delete('/{id}', [SurveyController::class, 'destroy'])->name('surveys.destroy'); // Delete survey
});

Route::prefix('questions')->group(function () {
    Route::get('/', [QuestionController::class, 'index'])->name('questions.index'); // List questions
    Route::post('/', [QuestionController::class, 'store'])->name('questions.store'); // Create question
    Route::get('/{id}', [QuestionController::class, 'show'])->name('questions.show'); // Show question
    Route::put('/{id}', [QuestionController::class, 'update'])->name('questions.update'); // Update question
    Route::delete('/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy'); // Delete question
});

Route::prefix('options')->group(function () {
    Route::get('/', [OptionController::class, 'index'])->name('options.index'); // List options
    Route::post('/', [OptionController::class, 'store'])->name('options.store'); // Create option
    Route::get('/{id}', [OptionController::class, 'show'])->name('options.show'); // Show option
    Route::put('/{id}', [OptionController::class, 'update'])->name('options.update'); // Update option
    Route::delete('/{id}', [OptionController::class, 'destroy'])->name('options.destroy'); // Delete option
});

Route::prefix('answers')->group(function () {
    Route::get('/', [AnswerController::class, 'index'])->name('answers.index'); // List answers
    Route::post('/', [AnswerController::class, 'store'])->name('answers.store'); // Create answer
    Route::get('/{id}', [AnswerController::class, 'show'])->name('answers.show'); // Show answer
    Route::put('/{id}', [AnswerController::class, 'update'])->name('answers.update'); // Update answer
    Route::delete('/{id}', [AnswerController::class, 'destroy'])->name('answers.destroy'); // Delete answer
});

Route::prefix('reports')->group(function () {
    Route::get('/answers/date-range', [ReportController::class, 'listAnswersByDateRange'])->name('reports.dateRange'); // List answers by date range
    Route::get('/user/{userId}/surveys', [ReportController::class, 'listSurveysByUser'])->name('reports.userSurveys'); // List surveys answered by user
    Route::get('/user/{userId}/survey/{surveyId}/detail', [ReportController::class, 'getUserSurveyDetail'])->name('reports.surveyDetail'); // Answer details
});


