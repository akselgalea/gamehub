<?php

use App\Http\Controllers\{ProfileController, GameController, ParameterController, ExperimentController, EntryPointController};
use App\Models\{AdministratorPanel, Experiment};
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
        
        // Administracion de usuarios //
        Route::prefix('users')->group(function () {
            Route::get('/', [AdministratorPanel::class, 'indexUsersPanel'])->name('users_panel.index'); // Ruta para ver el panel de administracion de los usuarios
            Route::get('/new', [AdministratorPanel::class, 'userCreate'])->name('user.create');
            Route::post('/new', [AdministratorPanel::class, 'userStore'])->name('user.store');
            Route::get('{id}/edit', [AdministratorPanel::class, 'userEdit'])->name('user.edit');
            Route::patch('{id}/update', [AdministratorPanel::class, 'userUpdate'])->name('user.update');
        });
         


        Route::get('/experiments', [ExperimentController::class, 'index'])->name('experiments_panel.index'); // Ruta para ver el panel de administracion de los experimentos

        
        
    });

    Route::prefix('experiments')->group(function () {
        Route::get('/new', [ExperimentController::class, 'create'])->name('experiments.create');
        Route::post('/new', [ExperimentController::class, 'store'])->name('experiments.store');
        
    });

    Route::prefix('experimentManagement')->group(function () {
        Route::get('/{id}', [ExperimentController::class, 'experimentManagement'])->name('experiment.management');

        // Informacion general del experimento //

        Route::get('{id}/edit', [ExperimentController::class, 'generalInformationEdit'])->name('experiment_information.edit');
        Route::patch('{id}/update', [ExperimentController::class, 'generalInformationUpdate'])->name('experiment_information.update');

        // Usuarios asociados al experimento //

        Route::prefix('users')->group(function () {
            Route::get('{id}/new', [ExperimentController::class, 'usersExperiment'])->name('users_experiment.edit');
            Route::post('/link', [ExperimentController::class, 'userAssociateExperiment'])->name('user_experiment.link');
            Route::post('/unlink', [ExperimentController::class, 'userDisassociateExperiment'])->name('user_experiment.unlink');
        });

        // Entrypoints asociados al experimento //
        Route::prefix('entrypoints')->group(function () {
            Route::get('{id}/show', [EntryPointController::class, 'show'])->name('entrypoints.show');
            Route::get('{id}/new', [EntryPointController::class, 'create'])->name('entrypoints.create');
            Route::post('/new', [EntryPointController::class, 'store'])->name('entrypoints.store');
            Route::get('{id}/edit', [EntryPointController::class, 'edit'])->name('entrypoints.edit');
            Route::patch('{id}/update', [EntryPointController::class, 'update'])->name('entrypoints.update');
            Route::delete('/{id}', [EntryPointController::class, 'destroy'])->name('entrypoints.destroy');  
        });
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

});

require __DIR__.'/auth.php';
