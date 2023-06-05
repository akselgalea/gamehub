<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Auth;
use App\Models;
use App\Http\Requests\Users\{UserCreateRequest, UserUpdateRequest};

class AdministratorPanel extends Model
{
    use HasFactory;

    public function indexUsersPanel() {
        return Inertia::render('Admin/Users/Index', ['users' => (User::all()->except(Auth::id()))->toArray()]); //obtiene todos los usuarios registrados en un arreglo, excepto el usuario logueado 
    }

    public function userCreate() {
        return Inertia::render('Admin/Users/Create');
    }

    public function userStore(UserCreateRequest $req) {

        $user = new User;
        $user->store($req);
        return redirect()->route('users_panel.index')->with('notification');
    }

    public function userEdit($id) {
        return Inertia::render('Admin/Users/Edit', ['user' => User::find($id)]);
    }

    public function userUpdate(UserUpdateRequest $req) {

        $validated = $req->validated();

        try {
            $this->update($validated);
            return ['status' => 200, 'message' => 'Datos del experimento actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

}
