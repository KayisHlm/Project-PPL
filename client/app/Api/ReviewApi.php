<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReviewApi
{
    protected $apiUrl = '';

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', 'http://localhost:3001/api') . '/review';
    }

    public function create(string $productId, array $body)
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        return Http::withHeaders($headers)->timeout(8)->post($this->apiUrl . '/products/' . $productId, $body);
    }

    public function getByProduct(string $productId)
    {
        $headers = [
            'Accept' => 'application/json',
        ];
        
        return Http::withHeaders($headers)->timeout(8)->get($this->apiUrl . '/products/' . $productId);
    }
}
