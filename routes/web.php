<?php

use App\Http\Controllers\{ProfileController, GameController, ParameterController};
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
        Route::post('{id}', [GameController::class, 'update'])->name('games.update');
        Route::delete('/{id}', [GameController::class, 'destroy'])->name('games.destroy');  
        Route::get('/{id}/play', [GameController::class, 'play'])->name('games.play');
    });

    Route::prefix('games/parameters')->group(function () {    
        Route::post('/new', [ParameterController::class, 'store'])->name('games.params.store');
        Route::put('{id}', [ParameterController::class, 'update'])->name('games.params.update');
        Route::delete('/{id}', [ParameterController::class, 'delete'])->name('games.params.destroy');
    });
});

require __DIR__.'/auth.php';
