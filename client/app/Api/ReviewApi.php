<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReviewApi
{
    protected $apiUrl = 'http://localhost:3001/api/reviews';

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
