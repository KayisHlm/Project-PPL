<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthApi
{
    protected $apiUrl = 'http://localhost:3001/api/auth/';

    public function save($body)
    {
        return $this->connectApi('create', 'post', $body);
    }

    public function edit($user_id, $token)
    {
        return $this->connectApi('detail/' . $user_id . '/' . $token, 'get');
    }

    public function update($body)
    {
        return $this->connectApi('update', 'put', $body);
    }

    public function detail($id)
    {
        return $this->connectApi('detail/' . $id, 'get');
    }

    private function connectApi($endpoint, $method, $body = [])
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        // Untuk endpoint register (create), jangan pakai auth()->user()
        if ($endpoint !== 'create') {
            $u = auth()->user();
            if ($u) {
                $userid64 = base64_encode($u->id_encrypt);
                $headers['Authorization'] = '1';
                $headers['X-Encrypted'] = $userid64;
            }
        }

        $http = Http::withHeaders($headers)->timeout(5);

        $url = $this->apiUrl . $endpoint;
        if ($method === "post") {
            $response = $http->send('POST', $url, [
                'json'  => $body
            ]);
        } elseif ($method === "put") {
            $response = $http->send('PUT', $url, [
                'json'  => $body
            ]);
        } else {
            if (!empty($body)) {
                $url .= '?' . http_build_query($body);
            }
            $response = $http->send('GET', $url);
        }

        if ($response->successful()) {
            return $response;
        } else {
            // Log error dan return response kosong/tanda error
            Log::error('API error: ' . $response->status());
            return $response;
        }
    }
}
