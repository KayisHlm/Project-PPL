<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminCategoryControllerTest extends TestCase
{
    /**
     * Tes validasi error ketika nama kategori kosong.
     */
    public function test_category_validation_required()
    {
        $response = $this->withSession([
            'auth_token' => 'mock-token',
            'user_data' => ['role' => 'platform_admin']
        ])->post(route('dashboard-admin.kategori.create'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'Nama kategori harus diisi',
        ]);
    }

    /**
     * Test validasi error ketika nama kategori terlalu pendek.
     */
    public function test_category_validation_min_length()
    {
        $response = $this->withSession([
            'auth_token' => 'mock-token',
            'user_data' => ['role' => 'platform_admin']
        ])->post(route('dashboard-admin.kategori.create'), [
            'name' => 'A', // Hanya 1 karakter
        ]);

        $response->assertSessionHasErrors([
            'name' => 'Nama kategori minimal 2 karakter',
        ]);
    }
}
