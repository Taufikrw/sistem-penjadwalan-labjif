<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\PracticumController;
use App\Http\Controllers\ScheduleController;
use App\Models\Assistant;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');

    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [FrontController::class, 'dashboard'])->name('home');
    Route::get('/dashboard', [FrontController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin,assistant'])->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::post('course/create', [ScheduleController::class, 'storeCourse'])->name('course.store');
    Route::put('course/{id}', [ScheduleController::class, 'updateCourse'])->name('course.update');

    Route::prefix('api')->group(function () {
        Route::get('get-course-table/{nim}', [ScheduleController::class, 'courseTable'])->name('api.course-schedules.table');
        Route::get('get-year-schedule-list', [ScheduleController::class, 'yearScheduleList'])->name('api.year-schedule.list');
        Route::get('get-schedule-table', [ScheduleController::class, 'scheduleTable'])->name('api.schedule.table');
        Route::get('get-history-table/{nim}', [AssistantController::class, 'historyTable'])->name('api.history.table');
    });
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('assistants', [AssistantController::class, 'index'])->name('assistant.index');
    
    Route::get('assistants/create', [AssistantController::class, 'create'])->name('assistant.create');
    Route::post('assistants/create', [AssistantController::class, 'store'])->name('assistant.store');

    Route::get('assistants/{nim}/detail-jadwal-kuliah', [ScheduleController::class, 'indexCourseSchedule'])->name('assistant.showCourse');
    Route::get('assistants/{nim}/detail-jadwal-praktikum', [AssistantController::class, 'showSchedule'])->name('assistant.showSchedule');
    Route::get('assistants/{nim}', [AssistantController::class, 'edit'])->name('assistant.edit');
    Route::put('assistants/{nim}', [AssistantController::class, 'update'])->name('assistant.update');
    Route::delete('assistants/{nim}', [AssistantController::class, 'destroy'])->name('assistant.delete');
    Route::post('assistants/bulk-delete', [AssistantController::class, 'bulkDelete'])->name('assistant.bulk-delete');

    Route::get('course/{nim}/create', [ScheduleController::class, 'createCourseLaboran'])->name('course.create');
    Route::get('course/{nim}/{id}', [ScheduleController::class, 'editCourseLaboran'])->name('course.edit');
    Route::delete('course/{nim}/{id}', [ScheduleController::class, 'destroyCourseLaboran'])->name('course.delete');
    Route::post('course/{nim}/bulk-delete', [ScheduleController::class, 'bulkDeleteCourse'])->name('course.bulk-delete');

    Route::get('practicums', [PracticumController::class, 'index'])->name('practicum.index');
    Route::get('practicums/create', [PracticumController::class, 'create'])->name('practicum.create');
    Route::post('practicums/create', [PracticumController::class, 'store'])->name('practicum.store');

    Route::get('practicums/{kode_praktikum}', [PracticumController::class, 'edit'])->name('practicum.edit');
    Route::put('practicums/{kode_praktikum}', [PracticumController::class, 'update'])->name('practicum.update');
    Route::delete('practicums/{kode_praktikum}', [PracticumController::class, 'destroy'])->name('practicum.delete');
    Route::post('practicums/bulk-delete', [PracticumController::class, 'bulkDelete'])->name('practicum.bulk-delete');

    Route::get('labs', [LaboratoriumController::class, 'index'])->name('lab.index');
    Route::get('labs/create', [LaboratoriumController::class, 'create'])->name('lab.create');
    Route::post('labs/create', [LaboratoriumController::class, 'store'])->name('lab.store');
    
    Route::get('labs/{id}', [LaboratoriumController::class, 'edit'])->name('lab.edit');
    Route::put('labs/{id}', [LaboratoriumController::class, 'update'])->name('lab.update');
    Route::delete('labs/{id}', [LaboratoriumController::class, 'destroy'])->name('lab.delete');
    Route::post('labs/bulk-delete', [LaboratoriumController::class, 'bulkDelete'])->name('lab.bulk-delete');

    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('schedule-detail', [ScheduleController::class, 'show'])->name('schedule.detail');
    Route::get('schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
    Route::post('schedule/create', [ScheduleController::class, 'store'])->name('schedule.store');

    Route::get('schedule/{id}', [ScheduleController::class, 'edit'])->name('schedule.edit');
    Route::put('schedule/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.delete');
    Route::post('schedule/bulk-delete', [ScheduleController::class, 'bulkDelete'])->name('schedule.bulk-delete');

    Route::get('schedule/set-assistant/{id}', [AssistantController::class, 'setAssistant'])->name('schedule.set-assistant');
    Route::post('schedule/set-assistant/{id}', [AssistantController::class, 'storeSetAssistant'])->name('schedule.store-assistant');
    Route::get('schedule/edit-assistant/{id}', [AssistantController::class, 'editAssistant'])->name('schedule.edit-assistant');
    Route::put('schedule/edit-assistant/{id}', [AssistantController::class, 'updateAssistant'])->name('schedule.update-assistant');

    Route::prefix('api')->group(function () {
        Route::get('get-today-schedules', [ScheduleController::class, 'todaySchedule'])->name('api.today-schedules');
        Route::get('get-assistant-table', [AssistantController::class, 'assistantTable'])->name('api.assistant.table');
        Route::get('get-laboratorium-table', [LaboratoriumController::class, 'labsTable'])->name('api.laboratorium.table');
        Route::get('get-practicum-table', [PracticumController::class, 'practicumsTable'])->name('api.practicum.table');
        Route::get('get-assistant-overview', [AssistantController::class, 'assistantOverview'])->name('api.assistant.overview');
    });
});

Route::middleware(['auth', 'role:assistant'])->group(function () {
    Route::get('jadwal-kuliah', [ScheduleController::class, 'indexCourse'])->name('course.index');

    Route::get('jadwal-kuliah/create', [ScheduleController::class, 'createCourseAssistant'])->name('course-schedule.create');
    Route::get('jadwal-kuliah/{id}', [ScheduleController::class, 'editCourseAssistant'])->name('course-schedule.edit');
    Route::delete('jadwal-kuliah/{id}', [ScheduleController::class, 'destroyCourseAssistant'])->name('course-schedule.delete');
    Route::post('jadwal-kuliah/bulk-delete', [ScheduleController::class, 'bulkDeleteCourse'])->name('course-schedule.bulk-delete');

    Route::get('jadwal-praktikum', [ScheduleController::class, 'indexScheduleAssistant'])->name('schedule.index-assistant');

    Route::get('edit-biodata', [AssistantController::class, 'editBiodata'])->name('assistant.edit-biodata');
    Route::put('edit-biodata', [AssistantController::class, 'updateBiodata'])->name('assistant.update-biodata');
    Route::get('preference/create', [AssistantController::class, 'createPreference'])->name('assistant.create-preference');
    Route::post('preference/create', [AssistantController::class, 'storePreference'])->name('assistant.store-preference');
});