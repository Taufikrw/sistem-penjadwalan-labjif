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

    Route::get('/admin/dashboard', [FrontController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [FrontController::class, 'dashboardAssistant'])->name('dashboard.assistant');

    Route::get('assistant', [AssistantController::class, 'index'])->name('assistant.index');
    Route::get('assistant/create', [AssistantController::class, 'create'])->name('assistant.create');
    Route::post('assistant/create', [AssistantController::class, 'store'])->name('assistant.store');

    Route::get('assistant/{nim}/detail-course', [AssistantController::class, 'show'])->name('assistant.showCourse');
    Route::get('assistant/{nim}/detail-schedule', [AssistantController::class, 'showSchedule'])->name('assistant.showSchedule');
    Route::get('assistant/{nim}/edit', [AssistantController::class, 'edit'])->name('assistant.edit');
    Route::put('assistant/{nim}/edit', [AssistantController::class, 'update'])->name('assistant.update');
    Route::delete('assistant/{nim}', [AssistantController::class, 'destroy'])->name('assistant.delete');

    Route::get('course/{nim}/create', [AssistantController::class, 'courseCreate'])->name('course.create');
    Route::post('course/{nim}/create', [AssistantController::class, 'courseStore'])->name('course.store');

    Route::get('course/{nim}/{id}', [AssistantController::class, 'courseEdit'])->name('course.edit');
    Route::put('course/{nim}/{id}', [AssistantController::class, 'courseUpdate'])->name('course.update');
    Route::delete('course/{nim}/{id}', [AssistantController::class, 'courseDestroy'])->name('course.delete');

    Route::get('practicum', [ScheduleController::class, 'listPracticums'])->name('practicum.index');

    Route::get('get-practicum-data', [ScheduleController::class, 'getPracticumData'])->name('practicum.data');
    Route::get('practicum/create', [ScheduleController::class, 'createPracticum'])->name('practicum.create');
    Route::post('practicum/create', [ScheduleController::class, 'storePracticum'])->name('practicum.store');

    Route::get('practicum/{kode_praktikum}', [ScheduleController::class, 'editPracticum'])->name('practicum.edit');
    Route::put('practicum/{kode_praktikum}', [ScheduleController::class, 'updatePracticum'])->name('practicum.update');
    Route::delete('practicum/{kode_praktikum}', [ScheduleController::class, 'destroyPracticum'])->name('practicum.delete');

    Route::get('lab', [ScheduleController::class, 'listLaboratoriums'])->name('lab.index');
    Route::get('lab/create', [ScheduleController::class, 'createLab'])->name('lab.create');
    Route::post('lab/create', [ScheduleController::class, 'storeLab'])->name('lab.store');
    
    Route::get('lab/{id}', [ScheduleController::class, 'editLab'])->name('lab.edit');
    Route::put('lab/{id}', [ScheduleController::class, 'updateLab'])->name('lab.update');
    Route::delete('lab/{id}', [ScheduleController::class, 'destroyLab'])->name('lab.delete');

    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
    Route::post('schedule/create', [ScheduleController::class, 'store'])->name('schedule.store');

    Route::get('schedule/{id}', [ScheduleController::class, 'edit'])->name('schedule.edit');
    Route::put('schedule/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.delete');

    Route::get('schedule/set-assistant/{id}', [AssistantController::class, 'setAssistant'])->name('schedule.set-assistant');
    Route::post('schedule/set-assistant/{id}', [AssistantController::class, 'storeSetAssistant'])->name('schedule.store-assistant');
    Route::get('schedule/edit-assistant/{id}', [AssistantController::class, 'editAssistant'])->name('schedule.edit-assistant');
    Route::put('schedule/edit-assistant/{id}', [AssistantController::class, 'updateAssistant'])->name('schedule.update-assistant');

    Route::get('course-schedule', [AssistantController::class, 'courseSchedules'])->name('course.index');
    Route::get('course-schedule/create', [AssistantController::class, 'createCourseSchedule'])->name('course-schedule.create');
    Route::post('course-schedule/create', [AssistantController::class, 'storeCourseSchedule'])->name('course-schedule.store');

    Route::get('course-schedule/{id}', [AssistantController::class, 'editCourseSchedule'])->name('course-schedule.edit');
    Route::put('course-schedule/{id}', [AssistantController::class, 'updateCourseSchedule'])->name('course-schedule.update');
    Route::delete('course-schedule/{id}', [AssistantController::class, 'destroyCourseSchedule'])->name('course-schedule.delete');

    Route::get('history', [AssistantController::class, 'history'])->name('history.index');
});
