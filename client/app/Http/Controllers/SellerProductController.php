<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Api\ProductApi;
use Barryvdh\DomPDF\Facade\Pdf;

class SellerProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $token = session('auth_token');
            if (!$token) {
                return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $api = new ProductApi();
            $resp = $api->getMyProducts(); 
            $products = [];
            
            if ($resp->successful()) {
                $json = $resp->json();
                $products = $json['data']['products'] ?? [];
                
                Log::info('Products loaded successfully', [
                    'count' => count($products)
                ]);
            } else {
                Log::warning('Failed to fetch seller products', [
                    'status' => $resp->status(),
                    'body' => $resp->body()
                ]);
            }
            
            // Fetch available categories from database
            $categories = [];
            try {
                $catApi = new \App\Api\CategoryApi();
                $catResp = $catApi->publicList($token);
                if ($catResp->status() === 200) {
                    $categories = $catResp->json()['data'] ?? [];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to fetch categories', ['error' => $e->getMessage()]);
            }
            
            return view('Page.DashboardSeller.Produk', compact('products', 'categories'));
            
        } catch (\Exception $e) {
            Log::error('Error in SellerProductController@index', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->route('dashboard-seller.dashboard')
                ->with('error', 'Gagal memuat produk: ' . $e->getMessage());
        }
    }

    public function createView(Request $request)
    {
        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }
        $catApi = new \App\Api\CategoryApi();
        $resp = $catApi->publicList($token);
        $categories = [];
        if ($resp->status() === 200) {
            $categories = $resp->json()['data'] ?? [];
        }
        return view('Page.DashboardSeller.TambahProduk', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'price' => 'required|integer|min:1',
            'weight' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'description' => 'required|string|min:10',
            'images' => 'required|array|min:1|max:6',
            'images.*' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            // Upload images langsung ke public/uploads/products
            $imageUrls = [];
            $uploadDir = public_path('uploads/products');
            
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }

            $files = $request->file('images');
            foreach ($files as $file) {
                // Validate image
                if (!$file->isValid()) {
                    return back()->withInput()->with('error', 'File upload gagal');
                }

                // Generate unique filename
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                // Move file
                $file->move($uploadDir, $filename);
                
                // Store full URL
                $imageUrls[] = url('uploads/products/' . $filename);
            }

            // Prepare payload for backend API
            $payload = [
                'name' => $request->input('name'),
                'price' => (int) $request->input('price'),
                'weight' => (int) $request->input('weight'),
                'stock' => (int) $request->input('stock'),
                'category' => $request->input('category'),
                'description' => $request->input('description'),
                'imageUrls' => $imageUrls,
            ];

            Log::info('Creating product', [
                'payload' => $payload,
                'image_count' => count($imageUrls)
            ]);

            // Call backend API
            $api = new ProductApi();
            $resp = $api->create($payload, $token);

            if ($resp->status() === 201) {
                Log::info('Product created successfully', ['response' => $resp->json()]);
                return redirect()->route('dashboard-seller.produk')->with('success', 'Produk berhasil ditambahkan!');
            }

            // Handle error - hapus file yang sudah diupload
            $errorMessage = $resp->json()['message'] ?? 'Gagal menambahkan produk.';
            Log::warning('Failed to create product', [
                'status' => $resp->status(),
                'response' => $resp->json()
            ]);

            // Delete uploaded images on failure
            foreach ($imageUrls as $url) {
                $path = str_replace(url('/'), '', $url);
                $fullPath = public_path($path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            return back()->withInput()->with('error', $errorMessage);

        } catch (\Exception $e) {
            Log::error('Error in SellerProductController@store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Delete uploaded images on exception
            if (isset($imageUrls) && count($imageUrls) > 0) {
                foreach ($imageUrls as $url) {
                    $path = str_replace(url('/'), '', $url);
                    $fullPath = public_path($path);
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function categories(Request $request)
    {
        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }
        $api = new ProductApi();
        $resp = $api->categories($token);
        $categories = [];
        if ($resp->status() === 200) {
            $json = $resp->json();
            $categories = $json['data'] ?? [];
        }
        return view('Page.DashboardSeller.Kategori', compact('categories'));
    }

    public function dashboard()
    {
        try {
            $token = session('auth_token');
            if (!$token) {
                return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $api = new ProductApi();
            $resp = $api->getMyProducts(); 
            
            $totalProducts = 0;
            $averageRating = 0;
            $totalReviews = 0;
            $products = [];
            
            // For charts
            $stockByProduct = [];
            $ratingByProduct = [];
            $reviewersByProvince = [];
            
            if ($resp->successful()) {
                $json = $resp->json();
                $products = $json['data']['products'] ?? [];
                
                $totalProducts = count($products);
                
                // Calculate average rating and prepare chart data
                $ratingSum = 0;
                $ratingCount = 0;
                $provinceReviewCount = [];
                
                foreach ($products as $product) {
                    $rating = $product['averageRating'] ?? $product['average_rating'] ?? 0;
                    $reviewCount = $product['reviewCount'] ?? $product['review_count'] ?? 0;
                    $stock = $product['stock'] ?? 0;
                    $name = $product['name'] ?? 'Produk';
                    
                    // Stock by product chart data
                    $stockByProduct[] = [
                        'name' => strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name,
                        'stock' => $stock
                    ];
                    
                    // Rating by product chart data
                    if ($rating > 0) {
                        $ratingByProduct[] = [
                            'name' => strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name,
                            'rating' => $rating
                        ];
                        $ratingSum += $rating;
                        $ratingCount++;
                    }
                    
                    $totalReviews += $reviewCount;
                    
                    // Count reviews by province from actual review data
                    if (isset($product['reviews']) && is_array($product['reviews'])) {
                        foreach ($product['reviews'] as $review) {
                            $province = $review['province'] ?? null;
                            
                            // Skip if province is null or empty
                            if (empty($province)) {
                                continue;
                            }
                            
                            if (!isset($provinceReviewCount[$province])) {
                                $provinceReviewCount[$province] = 0;
                            }
                            $provinceReviewCount[$province]++;
                        }
                    }
                }
                
                if ($ratingCount > 0) {
                    $averageRating = round($ratingSum / $ratingCount, 1);
                }
                
                // Sort and limit chart data
                usort($stockByProduct, function($a, $b) {
                    return $b['stock'] - $a['stock'];
                });
                $stockByProduct = array_slice($stockByProduct, 0, 10);
                
                usort($ratingByProduct, function($a, $b) {
                    return $b['rating'] <=> $a['rating'];
                });
                $ratingByProduct = array_slice($ratingByProduct, 0, 10);
                
                // Prepare province data
                arsort($provinceReviewCount);
                $provinceReviewCount = array_slice($provinceReviewCount, 0, 10, true);
                
                foreach ($provinceReviewCount as $province => $count) {
                    $reviewersByProvince[] = [
                        'province' => $province,
                        'count' => $count
                    ];
                }
                
                Log::info('Seller dashboard data loaded', [
                    'totalProducts' => $totalProducts,
                    'averageRating' => $averageRating,
                    'totalReviews' => $totalReviews,
                    'chartDataCount' => [
                        'stock' => count($stockByProduct),
                        'rating' => count($ratingByProduct),
                        'province' => count($reviewersByProvince)
                    ]
                ]);
            }
            
            return view('Page.DashboardSeller.Dashboard', compact(
                'totalProducts', 
                'averageRating', 
                'totalReviews',
                'stockByProduct',
                'ratingByProduct',
                'reviewersByProvince',
                'products'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in SellerProductController@dashboard', [
                'message' => $e->getMessage()
            ]);
            
            return view('Page.DashboardSeller.Dashboard', [
                'totalProducts' => 0,
                'averageRating' => 0,
                'totalReviews' => 0,
                'stockByProduct' => [],
                'ratingByProduct' => [],
                'reviewersByProvince' => [],
                'products' => []
            ]);
        }
    }

    public function pdfStokProduk()
    {
        try {
            $token = session('auth_token');
            
            if (!$token) {
                Log::warning('No auth token found for stok produk');
                return redirect()->route('login.loginIndex');
            }

            Log::info('Fetching stok produk list');
            $productApi = new ProductApi();
            $response = $productApi->getMyProducts();
            
            $products = [];
            if ($response && $response->successful()) {
                $data = $response->json();
                $products = $data['data']['products'] ?? [];
                
                Log::info('Stok produk loaded successfully', [
                    'count' => count($products)
                ]);
            } else {
                Log::error('Failed to fetch stok produk', [
                    'status' => $response ? $response->status() : 'null',
                    'error' => $response ? $response->body() : 'No response'
                ]);
            }
            
            $pdf = Pdf::loadView('Page.DashboardSeller.StokPerProduk-PDF', compact('products'));
            return $pdf->download('stok-per-produk.pdf');
            
        } catch (\Exception $e) {
            Log::error('Stok Produk Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return view('Page.DashboardSeller.StokPerProduk-PDF')
                ->withErrors(['fetch' => 'Failed to load stok produk: ' . $e->getMessage()])
                ->with('products', []);
        }
    }

}
