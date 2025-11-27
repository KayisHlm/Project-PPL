<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Seller
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
        
        // Cek apakah user adalah seller (role: seller)
        $userData = session('user_data');
        if (!$userData || ($userData['role'] ?? '') !== 'seller') {
            // Redirect ke halaman 401 Unauthorized
            return redirect()->route('error.unauthorized')
                ->with('error_details', 'Akses ditolak. Halaman ini hanya untuk Seller.');
        }
        
        return $next($request);
    }
}
