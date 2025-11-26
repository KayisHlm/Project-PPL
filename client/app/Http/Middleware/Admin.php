<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
   /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!session()->has('auth_token')) {
            return redirect()->route('login.loginIndex')
                ->withErrors(['access' => 'Please login first']);
        }
        
        // Cek apakah user adalah admin (role: platform_admin)
        $userData = session('user_data');
        if (!$userData || ($userData['role'] ?? '') !== 'platform_admin') {
            return redirect()->route('login.loginIndex')
                ->withErrors(['access' => 'Unauthorized access. Admin only.']);
        }
        
        return $next($request);
    }
}
