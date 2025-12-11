<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\AdminApi;
use App\Api\ProfileApi;
use Illuminate\Support\Facades\Log; 
use Barryvdh\DomPDF\Facade\Pdf;

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

            // Get approved sellers count
            $approvedResponse = $this->adminApi->getApprovedSellers($token);
            
            $approvedCount = 0;
            if ($approvedResponse && $approvedResponse->successful()) {
                $data = $approvedResponse->json();
                $approvedCount = $data['data']['total'] ?? $data['total'] ?? 0;
            }

            // Get products for catalog section
            Log::info('Fetching products for dashboard');
            $productResponse = $this->adminApi->getProducts();
            
            $products = [];
            if ($productResponse && $productResponse->successful()) {
                $data = $productResponse->json();
                $products = $data['data'] ?? [];
                
                Log::info('Products loaded for dashboard', [
                    'count' => count($products)
                ]);
            } else {
                Log::error('Failed to fetch products for dashboard', [
                    'status' => $productResponse ? $productResponse->status() : 'null'
                ]);
            }

            // Calculate average rating and total reviews
            $avgRating = 0;
            $totalReviews = 0;
            
            if (!empty($products)) {
                $totalRating = 0;
                $productCount = 0;
                
                foreach ($products as $product) {
                    // API returns camelCase: averageRating, reviewCount
                    $productRating = $product['averageRating'] ?? $product['average_rating'] ?? 0;
                    if ($productRating > 0) {
                        $totalRating += $productRating;
                        $productCount++;
                    }
                    
                    $reviewCount = $product['reviewCount'] ?? $product['review_count'] ?? 0;
                    if ($reviewCount > 0) {
                        $totalReviews += $reviewCount;
                    }
                }
                
                if ($productCount > 0) {
                    $avgRating = round($totalRating / $productCount, 1);
                }
            }

            // Fetch statistics data for charts
            $productsByCategory = [];
            $shopsByProvince = [];
            $sellersActiveStatus = [];
            $reviewsRatingStats = [];

            // Get products by category
            $categoryResponse = $this->adminApi->getProductsByCategory();
            if ($categoryResponse && $categoryResponse->successful()) {
                $data = $categoryResponse->json();
                $productsByCategory = $data['data'] ?? [];
            }

            // Get shops by province
            $provinceResponse = $this->adminApi->getShopsByProvince();
            if ($provinceResponse && $provinceResponse->successful()) {
                $data = $provinceResponse->json();
                $shopsByProvince = $data['data'] ?? [];
            }

            // Get sellers active status
            $activeStatusResponse = $this->adminApi->getSellersActiveStatus();
            if ($activeStatusResponse && $activeStatusResponse->successful()) {
                $data = $activeStatusResponse->json();
                $sellersActiveStatus = $data['data'] ?? [];
            }

            // Get reviews rating stats
            $reviewStatsResponse = $this->adminApi->getReviewsRatingStats();
            if ($reviewStatsResponse && $reviewStatsResponse->successful()) {
                $data = $reviewStatsResponse->json();
                $reviewsRatingStats = $data['data'] ?? [];
            }

            return view('Page.DashboardAdmin.Dashboard', compact(
                'pendingCount', 
                'approvedCount', 
                'products', 
                'avgRating', 
                'totalReviews',
                'productsByCategory',
                'shopsByProvince',
                'sellersActiveStatus',
                'reviewsRatingStats'
            ));

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return view('Page.DashboardAdmin.Dashboard')
                ->with('pendingCount', 0)
                ->with('approvedCount', 0)
                ->with('products', [])
                ->with('avgRating', 0)
                ->with('totalReviews', 0)
                ->with('productsByCategory', [])
                ->with('shopsByProvince', [])
                ->with('sellersActiveStatus', [])
                ->with('reviewsRatingStats', []);
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

            $reason = trim(request()->input('reason', ''));
            if (strlen($reason) < 10) {
                return back()->withErrors(['reject' => 'Alasan penolakan wajib diisi minimal 10 karakter']);
            }

            Log::info('Rejecting seller', ['id' => $sellerId]);

            $response = $this->adminApi->rejectSeller($token, $sellerId, $reason);

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
                Log::warning('No auth token found for products');
                return redirect()->route('login.loginIndex');
            }

            Log::info('Fetching products list');
            
            $response = $this->adminApi->getProducts();
            
            $products = [];
            if ($response && $response->successful()) {
                $data = $response->json();
                $products = $data['data'] ?? [];
                
                Log::info('Products loaded successfully', [
                    'count' => count($products)
                ]);
            } else {
                Log::error('Failed to fetch products', [
                    'status' => $response ? $response->status() : 'null',
                    'error' => $response ? $response->body() : 'No response'
                ]);
            }
            
            return view('Page.DashboardAdmin.Produk', compact('products'));
            
        } catch (\Exception $e) {
            Log::error('Products Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return view('Page.DashboardAdmin.Produk')
                ->withErrors(['fetch' => 'Failed to load products: ' . $e->getMessage()])
                ->with('products', []);
        }
    }
    public function pdfAkun()
    {
        try {
            $token = session('auth_token');

            if (!$token) {
                Log::warning('No auth token found for pdfAkun');
                return redirect()->route('login.loginIndex');
            }

            Log::info('Fetching active and nonactive sellers PDF');

            $activeSellers = $this->adminApi->getActiveSellers($token);

            if ($activeSellers && $activeSellers->successful()) {
                $activeSellers = $activeSellers->json()['data']['sellers'] ?? [];
            } else {
                Log::error('Failed to fetch active sellers', [
                    'status' => $activeSellers ? $activeSellers->status() : 'null',
                    'error' => $activeSellers ? $activeSellers->body() : 'No response'
                ]);
                $activeSellers = [];
            }

            $nonActiveSellers = $this->adminApi->getNonActiveSellers($token);

            if ($nonActiveSellers && $nonActiveSellers->successful()) {
                $nonActiveSellers = $nonActiveSellers->json()['data']['sellers'] ?? [];
            } else {
                Log::error('Failed to fetch nonactive sellers', [
                    'status' => $nonActiveSellers ? $nonActiveSellers->status() : 'null',
                    'error' => $nonActiveSellers ? $nonActiveSellers->body() : 'No response'
                ]);
                $nonActiveSellers = [];
            }

            $pdf = Pdf::loadView('Page.DashboardAdmin.PenjualAktifNonaktif-PDF', compact('activeSellers', 'nonActiveSellers'));
            return $pdf->download('penjual-aktif-nonaktif.pdf');
        } catch (\Exception $e) {
            Log::error('PDF Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return view('Page.DashboardAdmin.PenjualAktifNonaktif-PDF')
                ->withErrors(['fetch' => 'Failed to load sellers PDF: ' . $e->getMessage()])
                ->with('activeSellers', [])
                ->with('nonActiveSellers', []);
        }
    }
}
