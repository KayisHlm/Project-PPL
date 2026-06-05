<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductUploadWithValidDataTest extends TestCase
{
    /**
     * Test product upload with valid data.
     */
    public function test_product_can_be_uploaded_with_valid_data()
    {
        Http::fake([
            '*/product' => Http::response([
                'data' => [
                    'id' => 123,
                ],
            ], 201),
        ]);

        $uploadDir = public_path('uploads/products');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $beforeFiles = File::files($uploadDir);
        $beforeNames = array_map(function ($file) {
            return $file->getFilename();
        }, $beforeFiles);

        $image = UploadedFile::fake()->image('produk-sample.jpg', 640, 480);

        $payload = [
            'name' => 'Produk Contoh',
            'category' => 'Electronics',
            'price' => 250000,
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

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('dashboard-seller.produk'));

        $afterFiles = File::files($uploadDir);
        $this->assertGreaterThan(count($beforeFiles), count($afterFiles));

        foreach ($afterFiles as $file) {
            if (!in_array($file->getFilename(), $beforeNames, true)) {
                File::delete($file->getPathname());
            }
        }

        Http::assertSent(function ($request) use ($payload) {
            return $request->method() === 'POST'
                && str_contains($request->url(), '/product')
                && $request['name'] === $payload['name']
                && $request['category'] === $payload['category'];
        });
    }
}
