<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\Application\Api\{
    FacultiesController, SpecialtiesController, CoursesController, DictionariesController
};

// Маршруты для анкет с поддержкой мультиязычности
Route::pattern('locale', 'ru|kk|en');

Route::group(['prefix' => '{locale}', 'middleware' => 'set.locale'], function () {

    // Страница формы + сохранение
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');

    // API для списков (JSON)
    Route::prefix('api')->group(function() {
        Route::get('/faculties', [FacultiesController::class, 'index']);
        Route::get('/specialties', [SpecialtiesController::class, 'index']); // ?faculty_id=
        Route::get('/courses', [CoursesController::class, 'index']);     // ?faculty_id=&specialty_id=
        Route::get('/org-types', [DictionariesController::class, 'orgTypes']);
        Route::get('/degrees', [DictionariesController::class, 'degrees']);
        Route::get('/course-languages', [DictionariesController::class, 'courseLanguages']);
    });
});
