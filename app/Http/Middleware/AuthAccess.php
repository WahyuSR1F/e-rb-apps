<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
            // Cek apakah user sudah login
            if (!Auth::check()) {
                // Jika request berasal dari Web
                return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
            }

            return $next($request);
    }
}
