<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{

    public function test_landing_returns_view_with_products_on_success()
    {
        Http::fake([
            '*/product' => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'MacBook', // Sesuaikan jika backend memakai field nama berbeda.
                        'price' => 30000000, // Sesuaikan jika backend memakai field harga berbeda.
                    ],
                ],
            ], 200),
            'wilayah.id/*' => Http::response([
                'data' => [
                    [
                        'id' => 33,
                        'name' => 'Jawa Tengah',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('Page.Store.Landing');
        $response->assertViewHas('products', fn ($products) => \is_array($products)
            && \count($products) === 1
            && ($products[0]['name'] ?? null) === 'MacBook');
        $response->assertViewHas('provinces');
    }

 
    public function test_landing_returns_empty_when_api_fails()
    {
        Http::fake([
            '*/product' => Http::response(['message' => 'Server error'], 500),
            'wilayah.id/*' => Http::response(['message' => 'Server error'], 500),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('Page.Store.Landing');
        $response->assertViewHas('products', []);
    }

  
    public function test_detail_returns_view_with_product_on_success()
    {
        Http::fake([
            '*/product/1' => Http::response([
                'data' => [
                    'id' => 1,
                    'name' => 'MacBook', // Sesuaikan jika backend memakai field nama berbeda.
                    'price' => 30000000, // Sesuaikan jika backend memakai field harga berbeda.
                    'category' => 'Laptop',
                    'averageRating' => 4.8,
                    'reviewCount' => 10,
                    'stock' => 5,
                    'weight' => 2000,
                    'description' => 'Laptop premium',
                    'images' => [],
                    'reviews' => [],
                ],
            ], 200),
        ]);

        $response = $this->get('/store/detail/1');

        $response->assertStatus(200);
        $response->assertViewIs('Page.Store.Detail');
        $response->assertViewHas('product', fn ($product) => \is_array($product)
            && ($product['name'] ?? null) === 'MacBook'
            && ($product['price'] ?? null) === 30000000);
    }

  
    public function test_detail_aborts_404_when_product_not_found()
    {
        Http::fake([
            '*/product/999' => Http::response(['message' => 'Product not found'], 404),
        ]);

        $response = $this->get('/store/detail/999');

        $response->assertStatus(404);
    }
}
