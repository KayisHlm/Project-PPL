<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LoginWithValidAccountTest extends TestCase
{
    /**
     * Test successful login with valid seller credentials.
     */
    public function test_login_success_with_valid_email_and_password()
    {
        Http::fake([
            '*/auth/login' => Http::response([
                'token' => 'fake-token-123',
                'user' => [
                    'id' => 10,
                    'email' => 'seller@example.com',
                    'role' => 'seller',
                    'status' => 'approved',
                ],
            ], 200),
        ]);

        $payload = [
            'email' => 'seller@example.com',
            'password' => 'password123',
        ];

        $response = $this->post(route('login.authenticate'), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('auth_token', 'fake-token-123');
        $response->assertSessionHas('user_role', 'seller');
        $response->assertSessionHas('user_data');
        $response->assertRedirect(route('dashboard-seller.dashboard'));

        Http::assertSent(function ($request) use ($payload) {
            return $request->method() === 'POST'
                && str_contains($request->url(), '/auth/login')
                && $request['email'] === $payload['email']
                && $request['password'] === $payload['password'];
        });
    }
}
