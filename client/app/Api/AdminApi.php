<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;

class AdminApi
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_BASE_URL', 'http://localhost:3001/api');
    }

    /**
     * Get authorization header from session
     */
    private function getAuthHeaders()
    {
        $token = session('auth_token');
        
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Get all pending sellers
     * GET /api/admin/pending-sellers
     */
    public function getPendingSellers()
    {
        return Http::withHeaders($this->getAuthHeaders())
            ->get($this->baseUrl . '/admin/pending-sellers');
    }

    /**
     * Approve seller by ID
     * POST /api/admin/sellers/{id}/approve
     */
    public function approveSeller($sellerId)
    {
        return Http::withHeaders($this->getAuthHeaders())
            ->post($this->baseUrl . "/admin/sellers/{$sellerId}/approve");
    }

    /**
     * Reject seller by ID
     * POST /api/admin/sellers/{id}/reject
     */
    public function rejectSeller($sellerId)
    {
        return Http::withHeaders($this->getAuthHeaders())
            ->post($this->baseUrl . "/admin/sellers/{$sellerId}/reject");
    }

    public function getProducts()
    {
        return Http::withHeaders($this->getAuthHeaders())
            ->get($this->baseUrl . '/admin/products');
    }
}
