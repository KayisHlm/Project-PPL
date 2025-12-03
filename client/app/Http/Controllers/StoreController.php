<?php

namespace App\Http\Controllers;

use App\Api\ProductApi;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    protected $productApi;

    public function __construct()
    {
        $this->productApi = new ProductApi();
    }

    public function landing()
    {
        try {
            $response = $this->productApi->getAllWithImages();
            
            if ($response->successful()) {
                $products = $response->json()['data'] ?? [];
                return view('Page.Store.Landing', compact('products'));
            }

            Log::error('Failed to fetch products', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return view('Page.Store.Landing', ['products' => []]);
        } catch (\Exception $e) {
            Log::error('Exception fetching products: ' . $e->getMessage());
            return view('Page.Store.Landing', ['products' => []]);
        }
    }

    public function detail($id)
    {
        try {
            $response = $this->productApi->getById($id);
            
            if ($response->successful()) {
                $product = $response->json()['data'] ?? null;
                
                if (!$product) {
                    abort(404, 'Product not found');
                }
                
                return view('Page.Store.Detail', compact('product'));
            }

            if ($response->status() === 404) {
                abort(404, 'Product not found');
            }

            Log::error('Failed to fetch product detail', [
                'id' => $id,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            abort(500, 'Failed to fetch product details');
        } catch (\Exception $e) {
            Log::error('Exception fetching product detail: ' . $e->getMessage());
            abort(500, 'An error occurred while fetching product details');
        }
    }
}
