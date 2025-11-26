<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductApi
{
    protected $apiUrl = 'http://localhost:3001/api/products';

    public function create(array $body, string $token)
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $response = Http::withHeaders($headers)->timeout(8)->post($this->apiUrl, $body);
        return $response;
    }

    public function list(string $token)
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        return Http::withHeaders($headers)->timeout(8)->get($this->apiUrl);
    }

    public function categories(string $token)
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        return Http::withHeaders($headers)->timeout(8)->get($this->apiUrl . '/categories');
    }
}
