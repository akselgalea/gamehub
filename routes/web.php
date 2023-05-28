<?php

use App\Http\Controllers\{ProfileController, GameController, ParameterController, SchoolController, GradeController};
use App\Models\{AdministratorPanel};
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('controlpanel')->group(function () {
        Route::get('/users', [AdministratorPanel::class, 'users_panel'])->name('users_panel.index');
    });

    Route::prefix('games')->group(function () {
        Route::get('/', [GameController::class, 'index'])->name('games.index');
        Route::get('/new', [GameController::class, 'create'])->name('games.create');
        Route::post('/new', [GameController::class, 'store'])->name('games.store');
        Route::get('/{id}', [GameController::class, 'edit'])->name('games.edit');
        Route::patch('{id}', [GameController::class, 'update'])->name('games.update');
        Route::delete('/{id}', [GameController::class, 'destroy'])->name('games.destroy');  
        Route::get('/{id}/play', [GameController::class, 'play'])->name('games.play');
        
        Route::prefix('parameters')->group(function () {
            Route::post('/new', [ParameterController::class, 'store'])->name('games.params.store');
            Route::patch('{id}', [ParameterController::class, 'update'])->name('games.params.update');
            Route::delete('/{id}', [ParameterController::class, 'destroy'])->name('games.params.destroy');
        });
    });


    Route::prefix('schools')->group(function () {
        Route::get('/', [SchoolController::class, 'index'])->name('schools.index');
        Route::get('/new', [SchoolController::class, 'create'])->name('schools.create');
        Route::post('/new', [SchoolController::class, 'store'])->name('schools.store');
        Route::get('/{id}', [SchoolController::class, 'edit'])->name('schools.edit');
        Route::patch('{id}', [SchoolController::class, 'update'])->name('schools.update');
        Route::delete('/{id}', [SchoolController::class, 'destroy'])->name('schools.destroy');
        
        Route::prefix('/grades')->group(function () {
            Route::get('/', [GradeController::class, 'get'])->name('schools.grades.get');
            Route::post('/new', [GradeController::class, 'store'])->name('schools.grades.store');
            Route::patch('/{id}', [GradeController::class, 'update'])->name('schools.grades.update');
            Route::delete('/{id}', [GradeController::class, 'destroy'])->name('schools.grades.destroy');
        });
    });
    
});

require __DIR__.'/api.php';
require __DIR__.'/auth.php';
