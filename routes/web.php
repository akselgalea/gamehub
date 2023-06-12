<?php

use App\Models\{AdministratorPanel, Experiment, Game};
use App\Http\Controllers\{GameController, ParameterController, ExperimentController, EntryPointController, SurveyController, GameInstanceController};
use App\Http\Controllers\{ProfileController, SchoolController, GradeController};
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

    Route::prefix('controlpanel')->middleware('role:admin')->group(function () {
        
        // Administracion de usuarios //
        Route::prefix('users')->group(function () {
            Route::get('/', [AdministratorPanel::class, 'indexUsersPanel'])->name('users_panel.index'); // Ruta para ver el panel de administracion de los usuarios
            Route::get('/new', [AdministratorPanel::class, 'userCreate'])->name('user.create');
            Route::post('/new', [AdministratorPanel::class, 'userStore'])->name('user.store');
            Route::get('{id}/edit', [AdministratorPanel::class, 'userEdit'])->name('user.edit');
            Route::patch('{id}/update', [AdministratorPanel::class, 'userUpdate'])->name('user.update');
        });
    });

    Route::prefix('experiments')->middleware('role:admin')->group(function () {
        Route::get('/', [ExperimentController::class, 'index'])->name('experiments.index'); // Ruta para ver el panel de administracion de los experimentos
        Route::get('/new', [ExperimentController::class, 'create'])->name('experiments.create');
        Route::get('/{id}', [ExperimentController::class, 'experimentManagement'])->name('experiment.management');
        Route::get('{id}/edit', [ExperimentController::class, 'generalInformationEdit'])->name('experiment_information.edit');
        Route::patch('{id}/update', [ExperimentController::class, 'generalInformationUpdate'])->name('experiment_information.update');

        // CRUD
        Route::post('/new', [ExperimentController::class, 'store'])->name('experiments.store');
        Route::get('/{id}/surveys/new', [SurveyController::class, 'create'])->name('surveys.create');
        Route::post('/{id}/surveys/new', [SurveyController::class, 'store'])->name('surveys.store');
        Route::get('/{id}/surveys/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
        Route::post('/{id}/surveys/{survey}/edit', [SurveyController::class, 'update'])->name('surveys.update');
        Route::delete('/{id}/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
        Route::get('/{id}/surveys/tests/new', [SurveyController::class, 'testCreate'])->name('surveys.tests.create');
        Route::post('/{id}/surveys/tests/new', [SurveyController::class, 'store'])->name('surveys.tests.store');

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

        // Instancias de juego asociados al experimento // 

        Route::prefix('game_instances')->group(function () {
            Route::get('{id}/show', [GameInstanceController::class, 'show'])->name('game_instances.show');
            Route::get('{id}/new', [GameInstanceController::class, 'create'])->name('game_instances.create');
            Route::post('/new', [GameInstanceController::class, 'store'])->name('game_instances.store');
            Route::get('{id}/edit', [GameInstanceController::class, 'edit'])->name('game_instances.edit');
            Route::patch('{id}/update', [GameInstanceController::class, 'update'])->name('game_instances.update');
            Route::delete('/{id}', [GameInstanceController::class, 'destroy'])->name('game_instances.destroy');  

            Route::prefix('parameters')->group(function () {
                Route::get('{id}/editParameters', [GameInstanceController::class, 'editParams'])->name('instances_params.edit');
                Route::patch('{id}/updateParameters', [GameInstanceController::class, 'updateParams'])->name('instances_params.update');
            });

            Route::prefix('gamification')->group(function () {
                Route::get('{id}/editGamification', [GameInstanceController::class, 'editGamification'])->name('instances_gamification.edit');
                Route::patch('{id}/updateGamification', [GameInstanceController::class, 'updateGamification'])->name('instances_gamification.update');
            });
        });
    });

    
    Route::get('games/{id}/play', [GameController::class, 'play'])->name('games.play');
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    
    Route::prefix('games')->middleware('role:admin')->group(function () {
        Route::get('/new', [GameController::class, 'create'])->name('games.create');
        Route::post('/new', [GameController::class, 'store'])->name('games.store');
        Route::get('/{id}', [GameController::class, 'edit'])->name('games.edit');
        Route::patch('{id}', [GameController::class, 'update'])->name('games.update');
        Route::delete('/{id}', [GameController::class, 'destroy'])->name('games.destroy');  
        
        Route::prefix('parameters')->group(function () {
            Route::post('/new', [ParameterController::class, 'store'])->name('games.params.store');
            Route::patch('{id}', [ParameterController::class, 'update'])->name('games.params.update');
            Route::delete('/{id}', [ParameterController::class, 'destroy'])->name('games.params.destroy');
        });
    });

    // Obtener archivos juegos GameHub.
    Route::get('/uploads/games/{slug}/{filename}', function($slug, $filename){
        $game = Game::where('slug', $slug)->first();
        
        if (substr($filename, 0, strlen('html5game')) == 'html5game') {
            $filename = substr($filename, strlen('html5game'));
        }
        
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


    Route::prefix('schools')->middleware('role:admin')->group(function () {
        Route::get('/', [SchoolController::class, 'index'])->name('schools.index');
        Route::get('/new', [SchoolController::class, 'create'])->name('schools.create');
        Route::post('/new', [SchoolController::class, 'store'])->name('schools.store');
        Route::get('/{id}', [SchoolController::class, 'edit'])->name('schools.edit');
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

require __DIR__.'/api.php';
require __DIR__.'/auth.php';
