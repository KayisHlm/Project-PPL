<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Api\ProfileApi;
use Illuminate\Support\Facades\Log;

class LoadUserData
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('auth_token') && !session()->has('user_data')) {
            try {
                $profileApi = new ProfileApi();
                $response = $profileApi->getProfile();
                
                if ($response && isset($response['data']['user'])) {
                    $userData = $response['data']['user'];
                    
                    session(['user_data' => $userData]);
                    
                    Log::info('User data loaded into session', [
                        'user_id' => $userData['userId'] ?? null,
                        'name' => $userData['name'] ?? 'Unknown',
                        'role' => $userData['role'] ?? 'Unknown'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to load user data in middleware', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        return $next($request);
    }
}
