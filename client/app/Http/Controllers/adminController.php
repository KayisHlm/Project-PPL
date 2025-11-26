<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\AdminApi;
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

            Log::info('API Response received', [
                'has_response' => !is_null($response),
                'is_successful' => $response ? $response->successful() : false
            ]);

            if ($response && $response->successful()) {
                $responseData = $response->json();
                
                // ✅ FIX: Handle both response formats
                $data = $responseData['data'] ?? $responseData;
                $sellers = $data['sellers'] ?? [];
                $total = $data['total'] ?? 0;

                // ✅ TRANSFORM: Pastikan setiap seller punya 'id'
                $sellers = collect($sellers)->map(function($seller) {
                    return [
                        'id' => $seller['id'] ?? null,  // ✅ PENTING!
                        'userId' => $seller['userId'] ?? null,
                        'userEmail' => $seller['userEmail'] ?? 'N/A',
                        'shopName' => $seller['shopName'] ?? 'N/A',
                        'shopDescription' => $seller['shopDescription'] ?? '',
                        'picName' => $seller['picName'] ?? 'N/A',
                        'picPhone' => $seller['picPhone'] ?? 'N/A',
                        'picEmail' => $seller['picEmail'] ?? '',
                        'picKtp' => $seller['picKtp'] ?? 'N/A',
                        'picAddress' => $seller['picAddress'] ?? '',
                        'picRt' => $seller['picRt'] ?? '',
                        'picRw' => $seller['picRw'] ?? '',
                        'picProvince' => $seller['picProvince'] ?? '',
                        'picCity' => $seller['picCity'] ?? '',
                        'picDistrict' => $seller['picDistrict'] ?? '',
                        'picVillage' => $seller['picVillage'] ?? '',
                        'picPhotoPath' => $seller['picPhotoPath'] ?? '',
                        'picKtpPath' => $seller['picKtpPath'] ?? '',
                        'status' => $seller['status'] ?? 'pending',
                        'verifiedAt' => $seller['verifiedAt'] ?? null,
                        'createdAt' => $seller['createdAt'] ?? null,
                        'updatedAt' => $seller['updatedAt'] ?? null,
                    ];
                })->toArray();

                Log::info('Sellers data transformed', [
                    'total' => $total,
                    'count' => count($sellers),
                    'first_seller_has_id' => isset($sellers[0]['id'])
                ]);

                return view('Page.DashboardAdmin.PendingSellers', compact('sellers', 'total'));
            } else {
                $errorMessage = $response ? ($response->json()['message'] ?? 'Failed to fetch pending sellers') : 'No response from API';
                
                Log::error('Failed to fetch sellers', [
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
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('Page.DashboardAdmin.PendingSellers')
                ->withErrors(['fetch' => 'Connection error: ' . $e->getMessage()])
                ->with('sellers', [])
                ->with('total', 0);
        }
    }

    public function approveSeller($sellerId)
        {
            try {
                $token = session('auth_token');
                
                if (!$token) {
                    return redirect()->route('login.loginIndex');
                }

                // ✅ FIX: Validasi sellerId
                if (!$sellerId || !is_numeric($sellerId)) {
                    Log::error('Invalid seller ID', ['id' => $sellerId]);
                    return back()->withErrors(['approve' => 'Invalid seller ID']);
                }

                Log::info('Approving seller', ['id' => $sellerId]);

                $response = $this->adminApi->approveSeller($token, $sellerId);

                if ($response && $response->successful()) {
                    Log::info('Seller approved', ['id' => $sellerId]);
                    return redirect()->route('dashboard-admin.pending-sellers')
                        ->with('success', 'Penjual berhasil disetujui!');
                } else {
                    $errorMessage = $response ? ($response->json()['message'] ?? 'Failed to approve seller') : 'No response';
                    Log::error('Approve failed', ['error' => $errorMessage]);
                    return back()->withErrors(['approve' => $errorMessage]);
                }

            } catch (\Exception $e) {
                Log::error('Approve Exception: ' . $e->getMessage());
                return back()->withErrors(['approve' => 'Connection error: ' . $e->getMessage()]);
            }
        }

        public function rejectSeller($sellerId)
        {
            try {
                $token = session('auth_token');
                
                if (!$token) {
                    return redirect()->route('login.loginIndex');
                }

                // ✅ FIX: Validasi sellerId
                if (!$sellerId || !is_numeric($sellerId)) {
                    Log::error('Invalid seller ID', ['id' => $sellerId]);
                    return back()->withErrors(['reject' => 'Invalid seller ID']);
                }

                Log::info('Rejecting seller', ['id' => $sellerId]);

                $response = $this->adminApi->rejectSeller($token, $sellerId);

                if ($response && $response->successful()) {
                    Log::info('Seller rejected', ['id' => $sellerId]);
                    return redirect()->route('dashboard-admin.pending-sellers')
                        ->with('success', 'Penjual berhasil ditolak!');
                } else {
                    $errorMessage = $response ? ($response->json()['message'] ?? 'Failed to reject seller') : 'No response';
                    Log::error('Reject failed', ['error' => $errorMessage]);
                    return back()->withErrors(['reject' => $errorMessage]);
                }

            } catch (\Exception $e) {
                Log::error('Reject Exception: ' . $e->getMessage());
                return back()->withErrors(['reject' => 'Connection error: ' . $e->getMessage()]);
            }
        }

    public function products()
    {
        $response = $this->adminApi->getProducts();
        $products = [];
        if ($response->successful()) {
            $products = $response->json()['data'] ?? [];
        }
        return view('Page.DashboardAdmin.Produk', compact('products'));
    }
}
