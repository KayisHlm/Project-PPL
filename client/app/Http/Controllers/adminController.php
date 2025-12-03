<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\AdminApi;
use App\Api\ProfileApi;
use Illuminate\Support\Facades\Log; 

class AdminController extends Controller
{
    protected $adminApi;

    public function __construct()
    {   
        $this->adminApi = new AdminApi();
    }

    public function dashboard()
    {
        try {
            $token = session('auth_token');
            
            if (!$token) {
                return redirect()->route('login.loginIndex');
            }

            $response = $this->adminApi->getPendingSellers($token);
            
            $pendingCount = 0;
            if ($response && $response->successful()) {
                $data = $response->json();
                $pendingCount = $data['data']['total'] ?? $data['total'] ?? 0;
            }

            return view('Page.DashboardAdmin.Dashboard', compact('pendingCount'));

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return view('Page.DashboardAdmin.Dashboard')
                ->with('pendingCount', 0);
        }
    }

    public function pendingSellers()
    {
        try {
            $token = session('auth_token');
            
            if (!$token) {
                Log::warning('No auth token found');
                return redirect()->route('login.loginIndex')
                    ->with('error', 'Silakan login terlebih dahulu');
            }

            Log::info('Fetching pending sellers with token');

            $response = $this->adminApi->getPendingSellers($token);

            if ($response && $response->successful()) {
                $responseData = $response->json();
                
                Log::info('Raw API Response:', $responseData);
                
                // Get data from response
                $data = $responseData['data'] ?? $responseData;
                $rawSellers = $data['sellers'] ?? [];
                $total = $data['total'] ?? 0;

                // Pending sellers API returns camelCase, no transformation needed
                $sellers = $rawSellers;

                Log::info('Sellers data loaded', [
                    'total' => $total,
                    'count' => count($sellers),
                    'first_seller_id' => $sellers[0]['id'] ?? null
                ]);

                return view('Page.DashboardAdmin.PendingSellers', compact('sellers', 'total'));
            } else {
                $errorMessage = $response ? ($response->json()['message'] ?? 'Failed to fetch pending sellers') : 'No response from API';
                
                Log::error('Failed to fetch pending sellers', [
                    'error' => $errorMessage,
                    'status' => $response ? $response->status() : 'null'
                ]);

                return view('Page.DashboardAdmin.PendingSellers')
                    ->withErrors(['fetch' => $errorMessage])
                    ->with('sellers', [])
                    ->with('total', 0);
            }

        } catch (\Exception $e) {
            Log::error('Pending Sellers Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return view('Page.DashboardAdmin.PendingSellers')
                ->withErrors(['fetch' => 'Connection error: ' . $e->getMessage()])
                ->with('sellers', [])
                ->with('total', 0);
        }
    }

    /**
     * ✨ NEW METHOD: Get approved sellers
     */
    public function approvedSellers()
    {
        try {
            $token = session('auth_token');
            
            if (!$token) {
                Log::warning('No auth token found');
                return redirect()->route('login.loginIndex')
                    ->with('error', 'Silakan login terlebih dahulu');
            }

            Log::info('Fetching approved sellers with token');

            $response = $this->adminApi->getApprovedSellers($token);

            if ($response && $response->successful()) {
                $responseData = $response->json();
                
                Log::info('Raw API Response:', $responseData);
                
                // Get data from response
                $data = $responseData['data'] ?? $responseData;
                $rawSellers = $data['sellers'] ?? [];
                $total = $data['total'] ?? 0;

                // Transform sellers data
                $sellers = $this->transformSellersData($rawSellers);

                Log::info('Approved sellers data transformed', [
                    'total' => $total,
                    'count' => count($sellers),
                    'first_seller_id' => $sellers[0]['id'] ?? null
                ]);

                return view('Page.DashboardAdmin.Seller', compact('sellers', 'total'));
            } else {
                $errorMessage = $response ? ($response->json()['message'] ?? 'Failed to fetch approved sellers') : 'No response from API';
                
                Log::error('Failed to fetch approved sellers', [
                    'error' => $errorMessage,
                    'status' => $response ? $response->status() : 'null'
                ]);

                return view('Page.DashboardAdmin.Seller')
                    ->withErrors(['fetch' => $errorMessage])
                    ->with('sellers', [])
                    ->with('total', 0);
            }

        } catch (\Exception $e) {
            Log::error('Approved Sellers Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return view('Page.DashboardAdmin.Seller')
                ->withErrors(['fetch' => 'Connection error: ' . $e->getMessage()])
                ->with('sellers', [])
                ->with('total', 0);
        }
    }

    /**
     * 🔧 HELPER: Transform sellers data from snake_case to camelCase
     */
    private function transformSellersData($rawSellers)
    {
        return collect($rawSellers)->map(function($seller) {
            return [
                'id' => $seller['id'] ?? null,
                'userId' => $seller['user_id'] ?? null,
                'userEmail' => $seller['user_email'] ?? 'N/A',
                'shopName' => $seller['shop_name'] ?? 'N/A',
                'shopDescription' => $seller['shop_description'] ?? '',
                'picName' => $seller['pic_name'] ?? 'N/A',
                'picPhone' => $seller['pic_phone_number'] ?? 'N/A',
                'picEmail' => $seller['pic_email'] ?? '',
                'picKtp' => $seller['pic_ktp_number'] ?? 'N/A',
                'picAddress' => $seller['pic_address'] ?? '',
                'picRt' => $seller['pic_rt'] ?? '',
                'picRw' => $seller['pic_rw'] ?? '',
                'picProvince' => $seller['pic_province'] ?? '',
                'picCity' => $seller['pic_city'] ?? '',
                'picDistrict' => $seller['pic_district'] ?? '',
                'picVillage' => $seller['pic_village'] ?? '',
                'picPhotoPath' => $seller['pic_photo_path'] ?? '',
                'picKtpPath' => $seller['pic_ktp_path'] ?? '',
                'status' => $seller['status'] ?? 'pending',
                'verifiedAt' => $seller['verified_at'] ?? null,
                'createdAt' => $seller['created_at'] ?? null,
                'updatedAt' => $seller['updated_at'] ?? null,
            ];
        })->toArray();
    }

    /**
     * Approve seller
     */
    public function approveSeller($sellerId)
    {
        try {
            $token = session('auth_token');
            
            if (!$token) {
                return redirect()->route('login.loginIndex');
            }

            if (!$sellerId || !is_numeric($sellerId)) {
                Log::error('Invalid seller ID', ['id' => $sellerId]);
                return back()->withErrors(['approve' => 'Invalid seller ID']);
            }

            Log::info('Approving seller', ['id' => $sellerId]);

            $response = $this->adminApi->approveSeller($token, $sellerId);

            if ($response && $response->successful()) {
                Log::info('Seller approved successfully', ['id' => $sellerId]);
                return redirect()->route('dashboard-admin.pending-sellers')
                    ->with('success', 'Penjual berhasil disetujui!');
            } else {
                $errorMessage = $response ? ($response->json()['message'] ?? 'Failed to approve seller') : 'No response';
                Log::error('Approve failed', ['error' => $errorMessage, 'sellerId' => $sellerId]);
                return back()->withErrors(['approve' => $errorMessage]);
            }

        } catch (\Exception $e) {
            Log::error('Approve Exception', [
                'message' => $e->getMessage(),
                'sellerId' => $sellerId
            ]);
            return back()->withErrors(['approve' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject seller
     */
    public function rejectSeller($sellerId)
    {
        try {
            $token = session('auth_token');
            
            if (!$token) {
                return redirect()->route('login.loginIndex');
            }

            if (!$sellerId || !is_numeric($sellerId)) {
                Log::error('Invalid seller ID', ['id' => $sellerId]);
                return back()->withErrors(['reject' => 'Invalid seller ID']);
            }

            Log::info('Rejecting seller', ['id' => $sellerId]);

            $response = $this->adminApi->rejectSeller($token, $sellerId);

            if ($response && $response->successful()) {
                Log::info('Seller rejected successfully', ['id' => $sellerId]);
                return redirect()->route('dashboard-admin.pending-sellers')
                    ->with('success', 'Penjual berhasil ditolak!');
            } else {
                $errorMessage = $response ? ($response->json()['message'] ?? 'Failed to reject seller') : 'No response';
                Log::error('Reject failed', ['error' => $errorMessage, 'sellerId' => $sellerId]);
                return back()->withErrors(['reject' => $errorMessage]);
            }

        } catch (\Exception $e) {
            Log::error('Reject Exception', [
                'message' => $e->getMessage(),
                'sellerId' => $sellerId
            ]);
            return back()->withErrors(['reject' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get all products
     */
    public function products()
    {
        try {
            $token = session('auth_token');
            
            if (!$token) {
                return redirect()->route('login.loginIndex');
            }

            $response = $this->adminApi->getProducts();
            
            $products = [];
            if ($response && $response->successful()) {
                $data = $response->json();
                $products = $data['data'] ?? [];
            }
            
            return view('Page.DashboardAdmin.Produk', compact('products'));
            
        } catch (\Exception $e) {
            Log::error('Products Exception: ' . $e->getMessage());
            return view('Page.DashboardAdmin.Produk')
                ->withErrors(['fetch' => 'Failed to load products'])
                ->with('products', []);
        }
    }
}