<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WilayahApi
{
    protected $apiUrl = 'https://wilayah.id/api/';

    public function provinces()
    {
        return $this->connectApi('provinces.json', 'get');
    }

    public function regencies($idProvince)
    {
        return $this->connectApi('regencies/' . $idProvince . '.json', 'get');
    }

    public function districts($idRegency)
    {
        return $this->connectApi('districts/' . $idRegency . '.json', 'get');
    }

    public function villages($idDistrict)
    {
        return $this->connectApi('villages/' . $idDistrict . '.json', 'get');
    }

    private function connectApi($endpoint, $method, $body = [])
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

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
