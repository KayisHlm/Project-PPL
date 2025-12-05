<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;

class CategoryApi
{
    protected $apiUrl = 'http://localhost:3001/api/categories';

    public function list(string $token)
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->timeout(8)->get($this->apiUrl);
    }

    public function listWithCount(string $token)
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->timeout(8)->get($this->apiUrl . '/with-count');
    }

    public function publicList(?string $token = null)
    {
        $headers = [ 'Accept' => 'application/json' ];
        if ($token) { $headers['Authorization'] = 'Bearer ' . $token; }
        return Http::withHeaders($headers)->timeout(8)->get($this->apiUrl);
    }

    public function create(array $body, string $token)
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->timeout(8)->post($this->apiUrl, $body);
    }
}
