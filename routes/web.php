<?php

use App\Models\{Experiment, Game};
use App\Http\Controllers\{GameController, ParameterController, ExperimentController, EntryPointController, SurveyController, GameInstanceController, UserController};
use App\Http\Controllers\{ProfileController, SchoolController, GradeController, SurveyResponseController};
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;

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
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('controlpanel')->middleware('role:admin')->group(function () {
        
        // Administracion de usuarios //
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'indexUsersPanel'])->name('users_panel.index'); // Ruta para ver el panel de administracion de los usuarios
            Route::get('{id}/Profile', [UserController::class, 'userProfile'])->name('user-profile.index');
            Route::get('/new', [UserController::class, 'create'])->name('user.create');
            Route::post('/new', [UserController::class, 'store'])->name('user.store');
            Route::get('{id}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::patch('{id}/update', [UserController::class, 'update'])->name('user.update');
            Route::post('/uploadsUser', [UserController::class, 'storeBatch'])->name('upload-users.create');
        });
    });

    Route::prefix('experiments')->group(function () {
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/', [ExperimentController::class, 'index'])->name('experiments.index'); // Ruta para ver el panel de administracion de los experimentos
            Route::get('/new', [ExperimentController::class, 'create'])->name('experiments.create');
            Route::post('/new', [ExperimentController::class, 'store'])->name('experiments.store');
            Route::get('/{id}', [ExperimentController::class, 'experimentManagement'])->name('experiment.management');
            Route::get('{id}/edit', [ExperimentController::class, 'generalInformationEdit'])->name('experiment_information.edit');
            Route::patch('{id}/update', [ExperimentController::class, 'generalInformationUpdate'])->name('experiment_information.update');
            Route::delete('/{id}', [ExperimentController::class, 'destroy'])->name('experiment.destroy');
            
            Route::prefix('{id}/surveys')->group(function () {
                // CRUD
                Route::get('new', [SurveyController::class, 'create'])->name('surveys.create');
                Route::post('new', [SurveyController::class, 'store'])->name('surveys.store');
                Route::get('{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
                Route::post('{survey}/edit', [SurveyController::class, 'update'])->name('surveys.update');
                Route::delete('{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
                Route::get('surveys/tests/new', [SurveyController::class, 'testCreate'])->name('surveys.tests.create');
                Route::post('surveys/tests/new', [SurveyController::class, 'store'])->name('surveys.tests.store');
            });
            
            // Usuarios asociados al experimento //
            Route::prefix('users')->group(function () {
                Route::get('{id}/new', [ExperimentController::class, 'usersExperiment'])->name('users_experiment.edit');
                Route::post('/link', [ExperimentController::class, 'userAssociateExperiment'])->name('user_experiment.link');
                Route::post('/unlink', [ExperimentController::class, 'userDisassociateExperiment'])->name('user_experiment.unlink');
            });
            
            // Entrypoints asociados al experimento //
            Route::prefix('entrypoints')->group(function () {
                Route::get('{id}/new', [EntryPointController::class, 'create'])->name('entrypoints.create');
                Route::get('{id}/show', [EntryPointController::class, 'show'])->name('entrypoints.show');
                Route::post('/new', [EntryPointController::class, 'store'])->name('entrypoints.store');
                Route::get('{id}/edit', [EntryPointController::class, 'edit'])->name('entrypoints.edit');
                Route::patch('{id}/update', [EntryPointController::class, 'update'])->name('entrypoints.update');
                Route::delete('/{id}', [EntryPointController::class, 'destroy'])->name('entrypoints.destroy');  
            });
        });

        // Instancias de juego asociados al experimento // 
        Route::prefix('{id}/game-instances')->group(function () {
            Route::middleware(['role:admin'])->group(function () {
                Route::get('/', [GameInstanceController::class, 'show'])->name('game_instances.show');
                Route::get('/new', [GameInstanceController::class, 'create'])->name('game_instances.create');
                Route::post('/new', [GameInstanceController::class, 'store'])->name('game_instances.store');
                Route::get('{slug}/edit', [GameInstanceController::class, 'edit'])->name('game_instances.edit');
                Route::patch('{slug}/update', [GameInstanceController::class, 'update'])->name('game_instances.update');
                Route::delete('/', [GameInstanceController::class, 'destroy'])->name('game_instances.destroy');  
                Route::get('/export/stats', [GameInstanceController::class, 'exportGameInstance'])->name('game_instances.export');

                Route::prefix('{slug}/parameters')->group(function () {
                    Route::get('edit', [GameInstanceController::class, 'editParams'])->name('game_instances.params.edit');
                    Route::patch('update', [GameInstanceController::class, 'updateParams'])->name('game_instances.params.update');
                });

                Route::prefix('{slug}/gamification')->group(function () {
                    Route::get('/edit', [GameInstanceController::class, 'editGamification'])->name('game_instances.gamification.edit');
                    Route::patch('/update', [GameInstanceController::class, 'updateGamification'])->name('game_instances.gamification.update');
                });
            });
            
        });
        
        Route::get('{id}/survey/{survey}', [SurveyController::class, 'run'])->name('surveys.run');
        Route::get('{id}/survey/{survey}/test', [SurveyController::class, 'runTest'])->name('surveys.tests.run');
        Route::post('{id}/survey/{survey}/response', [SurveyResponseController::class, 'store'])->name('surveys.response.store');
        Route::get('{id}/play', [GameInstanceController::class, 'selectInstance'])->name('game_instances.select_instance');
    });
    
    Route::get('register/{token}', [EntryPointController::class, 'register'])->name('entrypoints.register');
    Route::get('game-instances/{game}/{instance}/play', [GameInstanceController::class, 'play'])->name('game_instances.play');
    
    //Obtener juegos que no son de GameMaker
    Route::get('/uploads/games/{game}/{filename}', function($gameSlug, $filename) {
        $game = Game::firstWhere('slug', $gameSlug);
        $path = base_path() . $game->file . '/'. $filename;

        if(!File::exists($path)) {
            return response()->json(['message' => 'File not found.', 'path' => $path, 'filename' => $filename], 404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    })->where('filename', '(.*)');

    // Obtener archivos juegos GameHub - GM2.
    Route::get('/game-instances/{instance}/{game}/{filename}', [GameController::class, 'getFile'])->where('filename', '(.*)');
    Route::post('game-instances/data/load', [GameInstanceController::class, 'initialParams'])->name('game_instances.game.load');
    Route::post('game-instances/data/save', [GameInstanceController::class, 'saveData'])->name('game_instances.game.save');

    Route::get('games/{slug}/play', [GameController::class, 'play'])->name('games.play');
    Route::get('/games', [GameController::class, 'myGames'])->name('games.my_games');
    
    Route::prefix('games')->middleware('role:admin')->group(function () {
        Route::get('/all', [GameController::class, 'index'])->name('games.index');
        Route::get('/new', [GameController::class, 'create'])->name('games.create');
        Route::post('/new', [GameController::class, 'store'])->name('games.store');
        Route::get('/{slug}', [GameController::class, 'edit'])->name('games.edit');
        Route::patch('{slug}', [GameController::class, 'update'])->name('games.update');
        Route::delete('/{id}', [GameController::class, 'destroy'])->name('games.destroy');  
        
        Route::prefix('{slug}/parameters')->group(function () {
            Route::post('/new', [ParameterController::class, 'store'])->name('games.params.store');
            Route::patch('{id}', [ParameterController::class, 'update'])->name('games.params.update');
            Route::delete('/{id}', [ParameterController::class, 'destroy'])->name('games.params.destroy');
        });
    });

    Route::prefix('schools')->middleware('role:admin')->group(function () {
        Route::get('/', [SchoolController::class, 'index'])->name('schools.index');
        Route::get('/new', [SchoolController::class, 'create'])->name('schools.create');
        Route::post('/new', [SchoolController::class, 'store'])->name('schools.store');
        Route::get('/{slug}', [SchoolController::class, 'edit'])->name('schools.edit');
        Route::patch('{id}', [SchoolController::class, 'update'])->name('schools.update');
        Route::delete('/{id}', [SchoolController::class, 'destroy'])->name('schools.destroy');
        
        Route::get('/{school}/grades/{grade}', [GradeController::class, 'get'])->name('schools.grades.get');

        Route::prefix('/grades')->group(function () {
            Route::post('/new', [GradeController::class, 'store'])->name('schools.grades.store');
            Route::patch('/{id}', [GradeController::class, 'update'])->name('schools.grades.update');
            Route::delete('/{id}', [GradeController::class, 'destroy'])->name('schools.grades.destroy');
        });
    });
    
});

require __DIR__.'/api2.php';
require __DIR__.'/auth.php';
