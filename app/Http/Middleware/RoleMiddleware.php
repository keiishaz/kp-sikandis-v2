<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        if (!$user->role || !in_array($user->role->nama_role, $roles)) {
            // Redirect to their appropriate dashboard if they try to access unauthorized area
            if ($user->role && $user->role->nama_role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role && $user->role->nama_role === 'operator') {
                return redirect()->route('operator.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
