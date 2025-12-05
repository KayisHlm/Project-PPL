<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Api\ProductApi;

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
            
            return view('Page.DashboardSeller.Produk', compact('products'));
            
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
}
