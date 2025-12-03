<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProfileApi
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('BACKEND_URL', 'http://localhost:3001') . '/api/profile';
    }

    /**
     * Get current user profile
     */
    public function getProfile()
    {
        try {
            $token = session('auth_token');

            if (!$token) {
                throw new \Exception('No authentication token found');
            }

            Log::info('Fetching user profile', ['token' => substr($token, 0, 20) . '...']);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/profile');

            if ($response->successful()) {
                $data = $response->json();
                Log::info('User profile fetched successfully', ['user' => $data['data']['user']['name'] ?? 'Unknown']);
                return $data;
            }

            Log::error('Failed to fetch user profile', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Failed to fetch user profile: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('UserApi getProfile error: ' . $e->getMessage());
            throw $e;
        }
    }
}