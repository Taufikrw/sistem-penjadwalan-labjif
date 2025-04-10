<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');

    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/', [FrontController::class, 'dashboard'])->name('home');

    Route::get('/dashboard', [FrontController::class, 'dashboard'])->name('dashboard');

    Route::get('assistant', [AssistantController::class, 'index'])->name('assistant.index');

    Route::get('assistant/{nim}', [AssistantController::class, 'show'])->name('assistant.show');

    Route::get('course/{nim}', [AssistantController::class, 'courseCreate'])->name('course.create');
    Route::post('course/{nim}', [AssistantController::class, 'courseStore'])->name('course.store');

    Route::get('practicum', [ScheduleController::class, 'listPracticums'])->name('practicum.index');

    Route::get('practicum/create', [ScheduleController::class, 'createPracticum'])->name('practicum.create');
    Route::post('practicum/create', [ScheduleController::class, 'storePracticum'])->name('practicum.store');

    Route::get('practicum/{kode_praktikum}', [ScheduleController::class, 'editPracticum'])->name('practicum.edit');
    Route::put('practicum/{kode_praktikum}', [ScheduleController::class, 'updatePracticum'])->name('practicum.update');
    Route::delete('practicum/{kode_praktikum}', [ScheduleController::class, 'destroyPracticum'])->name('practicum.delete');
});
