<?php
use App\Http\Controllers\{ProfileController, GameController, ParameterController, SchoolController, GradeController, Studentcontroller, SurveyController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth')->group(function () {
    Route::prefix('api')->group(function () {
        Route::prefix('schools')->group(function () {
            Route::get('/get-all', [SchoolController::class, 'getAll'])->name('api.schools.index');
            
            Route::prefix('{school}/grades')->group(function () {
                Route::get('/', [SchoolController::class, 'grades'])->name('api.schools.grades');
            });
        });

        Route::prefix('students')->group(function () {
            Route::get('{id}/school-info', [StudentController::class, 'schoolInfo'])->name('api.students.school_info');
            Route::patch('{id}/school-info', [StudentController::class, 'gradeUpdate'])->name('api.students.school_info.update');
        });

        Route::prefix('surveys')->group(function () {
            Route::post('questions/create', [SurveyController::class, 'questionCreate'])->name('api.surveys.survey_question.create');
        });
    });
});