<?php

namespace App\Http\Controllers;

use App\Api\ProductApi;
use App\Api\WilayahApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    protected $productApi;
    protected $wilayahApi;

    public function __construct()
    {
        $this->productApi = new ProductApi();
        $this->wilayahApi = new WilayahApi();
    }

    public function landing()
    {
        try {
            $response = $this->productApi->getAllWithImages();
            $provinceResponse = $this->wilayahApi->provinces();
            
            if ($response->successful()) {
                $products = $response->json()['data'] ?? [];
                $provinces = $provinceResponse && $provinceResponse->successful()
                    ? ($provinceResponse->json()['data'] ?? [])
                    : [];
                return view('Page.Store.Landing', compact('products', 'provinces'));
            }

            Log::error('Failed to fetch products', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return view('Page.Store.Landing', ['products' => [], 'provinces' => []]);
        } catch (\Exception $e) {
            Log::error('Exception fetching products: ' . $e->getMessage());
            return view('Page.Store.Landing', ['products' => [], 'provinces' => []]);
        }
    }

    public function detail(Request $request, $id)
    {
        try {
            $response = $this->productApi->getById($id);
            
            if ($response->successful()) {
                $product = $response->json()['data'] ?? null;
                
                if (!$product) {
                    abort(404, 'Product not found');
                }

                $isProductOwner = $request->attributes->get('isProductOwner', false);
                $userRole = $request->attributes->get('userRole', null);
                
                return view('Page.Store.Detail', compact('product', 'isProductOwner', 'userRole'));
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
