<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $roles): Response
    {
        if(!Auth::check())
            return redirect('login');

        $user = Auth::user();

        if($user->isAdmin())
            return $next($request);

        foreach($roles as $role) {
            if($user->type == $role)
                return $next($request);
        }

        return redirect()->back()->with('notification', ['status' => 401, 'message' => 'No tienes permiso para entrar a esta ruta']);
    }
}
