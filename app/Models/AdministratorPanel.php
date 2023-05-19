<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Auth;

class AdministratorPanel extends Model
{
    use HasFactory;

    public function users_panel() {
        // $users = User::all()->toArray();
        // dd($users);
        
        return Inertia::render('Admin/Users/Index', ['users' => (User::all()->except(Auth::id()))->toArray()]); //obtiene todos los usuarios registrados en un arreglo, excepto el usuario logueado 
    }

}