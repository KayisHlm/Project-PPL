<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductUploadNegativePriceTest extends TestCase
{
    /**
     * Test product upload is rejected when price is negative.
     */
    public function test_product_upload_rejected_when_price_is_negative()
    {
        Http::fake();

        $image = UploadedFile::fake()->image('produk-sample.png', 640, 480);

        $payload = [
            'name' => 'Produk Contoh',
            'category' => 'Electronics',
            'price' => -1000,
            'stock' => 10,
            'weight' => 500,
            'description' => 'Deskripsi produk valid minimal sepuluh karakter.',
            'images' => [$image],
        ];

        $response = $this->withSession([
            'auth_token' => 'fake-token-123',
            'user_data' => [
                'id' => 10,
                'email' => 'seller@example.com',
                'role' => 'seller',
                'status' => 'approved',
            ],
            'user_role' => 'seller',
        ])->post(route('dashboard-seller.produk.create'), $payload);

        $response->assertSessionHasErrors(['price']);
        Http::assertNothingSent();
    }
}
