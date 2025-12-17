<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Allowed roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin can access everything
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Check if user's role is in the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // If not authorized, redirect to dashboard with error
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
