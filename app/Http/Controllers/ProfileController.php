<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use Exception;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {   
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status')
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = User::find($request->id);
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        
        $res = ['status' => 200, 'message' => 'Perfil actualizado con éxito'];

        return redirect()->back()->with('notification', $res);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        if($request->userId)
            return $this->destroyOthersAccount($request->userId);

        else
            return $this->destroyMyAccount($request);
    }
    
    public function destroyOthersAccount($id) {
        try {
            if(!Auth()->user()->isAdmin()) 
                throw new Exception ('No tienes permisos para realizar esta operación.');

            User::findOrFail($id)->delete();
            $res = ['status' => 200, 'message' => 'Usuario eliminado con éxito!'];
        } catch(Exception $e) {
            $res = ['status' => 500, 'message' => $e->getMessage()];
        }

        return redirect()->back()->with('notification', $res);
    }

    public function destroyMyAccount($request) {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
