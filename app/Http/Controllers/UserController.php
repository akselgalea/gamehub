<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\Users\{UserCreateRequest, UserUpdateRequest};
use App\Http\Requests\Uploads\UploadUsersRequest;
use App\Models\{User, Student, Category};
use Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function indexUsersPanel() {
        return Inertia::render('Admin/Users/Index', ['users' => $this->userService->allButMe()]); //obtiene todos los usuarios registrados en un arreglo, excepto el usuario logueado 
    }

    public function create() {
        return Inertia::render('Admin/Users/Create');
    }

    public function userProfile($id) {
        $user = Student::find($id);
        return Inertia::render('Admin/Users/Profile/View', ['user' => $user, 'experiments' => $user->experiments, 'games' => $user->getGamesICanPlay(), 'categoryes' => Category::all()->toArray()]);
    }

    public function edit($id) {
        return Inertia::render('Admin/Users/Edit', ['user' => User::find($id)]);
    }

    public function store(UserCreateRequest $request) {
        $res = $this->userService->store($request);
        return redirect()->back()->with('notification', $res);
    }

    public function update(UserUpdateRequest $request) {
        $res = $this->userService->update($request);
        return redirect()->back()->with('notification', $res);
    }

    // Funcion para carga masiva de usuarios en base a un excel csv o xlsx
    public function storeBatch(UploadUsersRequest $request) {
        $res = $this->userService->storeBatch($request);
        return redirect()->back()->with('notification', $res);
    }
}
