<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
    public function getPendingSellers($token)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . '/admin/pending-sellers');
    }

    /**
     * Get all approved sellers ✨ NEW METHOD
     * GET /admin/approved-sellers
     */
    public function getApprovedSellers($token)
    {
        Log::info('AdminApi.getApprovedSellers called');
        
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/admin/approved-sellers");
    }

    /**
     * Approve seller by ID
     * POST /api/admin/sellers/{id}/approve
     */
    public function approveSeller($token, $sellerId)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . "/admin/sellers/{$sellerId}/approve");
    }

    /**
     * Reject seller by ID
     * POST /api/admin/sellers/{id}/reject
     */
    public function rejectSeller($token, $sellerId, string $reason = '')
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . "/admin/sellers/{$sellerId}/reject", [
            'reason' => $reason,
        ]);
    }

    public function getProducts()
    {
        Log::info('AdminApi.getProducts called');
        
        return Http::withHeaders($this->getAuthHeaders())
            ->timeout(10)
            ->get($this->baseUrl . '/products');
    }

    /**
     * Get products by category statistics
     * GET /api/statistics/products-by-category
     */
    public function getProductsByCategory()
    {
        return Http::withHeaders($this->getAuthHeaders())
            ->timeout(10)
            ->get($this->baseUrl . '/statistics/products-by-category');
    }

    /**
     * Get shops by province statistics
     * GET /api/statistics/shops-by-province
     */
    public function getShopsByProvince()
    {
        return Http::withHeaders($this->getAuthHeaders())
            ->timeout(10)
            ->get($this->baseUrl . '/statistics/shops-by-province');
    }

    /**
     * Get sellers active status statistics
     * GET /api/statistics/sellers-active-status
     */
    public function getSellersActiveStatus()
    {
        return Http::withHeaders($this->getAuthHeaders())
            ->timeout(10)
            ->get($this->baseUrl . '/statistics/sellers-active-status');
    }

    /**
     * Get reviews and rating statistics
     * GET /api/statistics/reviews-rating-stats
     */
    public function getReviewsRatingStats()
    {
        return Http::withHeaders($this->getAuthHeaders())
            ->timeout(10)
            ->get($this->baseUrl . '/statistics/reviews-rating-stats');
    }
}
