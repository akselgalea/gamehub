<?php

use App\Http\Controllers\{GameController, ParameterController, ExperimentController, EntryPointController, SurveyController};
use App\Http\Controllers\{ProfileController, SchoolController, GradeController};
use App\Models\{AdministratorPanel, Experiment, Game};
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

    Route::prefix('controlpanel')->group(function () {
        Route::get('/users', [AdministratorPanel::class, 'index_users_panel'])->name('users_panel.index'); // Ruta para ver el panel de administracion de los usuarios
        Route::get('/experiments', [ExperimentController::class, 'index'])->name('experiments_panel.index'); // Ruta para ver el panel de administracion de los experimentos
        
    });

    Route::prefix('experiments')->group(function () {
        Route::get('/{id}', [ExperimentController::class, 'experimentManagement'])->name('experiment.management');
        Route::get('{id}/edit', [ExperimentController::class, 'generalInformationEdit'])->name('experiment_information.edit');
        Route::patch('{id}/update', [ExperimentController::class, 'generalInformationUpdate'])->name('experiment_information.update');

        // CRUD
        Route::get('/new', [ExperimentController::class, 'create'])->name('experiments.create');
        Route::post('/new', [ExperimentController::class, 'store'])->name('experiments.store');
        Route::get('/{id}/surveys/new', [SurveyController::class, 'create'])->name('surveys.create');
        Route::post('/{id}/surveys/new', [SurveyController::class, 'store'])->name('surveys.store');
    });

    Route::prefix('entrypoints')->group(function () {
        Route::get('/new', [EntryPointController::class, 'create'])->name('entrypoints.create');
        Route::post('/new', [EntryPointController::class, 'store'])->name('entrypoints.store');
        Route::get('{id}/edit', [EntryPointController::class, 'edit'])->name('entrypoints.edit');
        Route::patch('{id}/update', [EntryPointController::class, 'update'])->name('entrypoints.update');
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


    Route::prefix('schools')->group(function () {
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
