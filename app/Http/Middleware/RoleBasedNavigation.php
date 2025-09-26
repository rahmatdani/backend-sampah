<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedNavigation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Menentukan navigasi berdasarkan role pengguna
        if (auth()->check()) {
            $user = auth()->user();
            
            // Jika pengguna adalah peneliti, batasi navigasi
            if ($user->role === 'peneliti') {
                // Kita akan menangani pembatasan navigasi di level Filament
            }
        }

        return $next($request);
    }
}
