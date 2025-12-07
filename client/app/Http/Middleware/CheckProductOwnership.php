<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Api\ProductApi;

class CheckProductOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $productId = $request->route('id');
        
        $token = session('auth_token');
        $userData = session('user_data');
        
        if (!$token || !$userData) {
            $request->attributes->set('isProductOwner', false);
            $request->attributes->set('userRole', null);
            return $next($request);
        }
        
        // If user is not seller, continue
        $userRole = $userData['role'] ?? null;
        if ($userRole !== 'seller') {
            $request->attributes->set('isProductOwner', false);
            $request->attributes->set('userRole', $userRole);
            return $next($request);
        }
        
        try {
            $productApi = new ProductApi();
            $response = $productApi->getById($productId);
            
            if ($response->successful()) {
                $product = $response->json()['data'] ?? null;
                $sellerId = $userData['sellerId'] ?? null;
                $productSellerId = $product['sellerId'] ?? $product['seller_id'] ?? null;
                
                $isOwner = ($sellerId && $productSellerId && $sellerId == $productSellerId);
                
                Log::info('CheckProductOwnership middleware', [
                    'productId' => $productId,
                    'userSellerId' => $sellerId,
                    'productSellerId' => $productSellerId,
                    'isOwner' => $isOwner
                ]);
                
                $request->attributes->set('isProductOwner', $isOwner);
                $request->attributes->set('userRole', $userRole);
            } else {
                $request->attributes->set('isProductOwner', false);
                $request->attributes->set('userRole', $userRole);
            }
        } catch (\Exception $e) {
            Log::error('Error in CheckProductOwnership middleware', [
                'error' => $e->getMessage()
            ]);
            $request->attributes->set('isProductOwner', false);
            $request->attributes->set('userRole', $userRole);
        }
        
        return $next($request);
    }
}
