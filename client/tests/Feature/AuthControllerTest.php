<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * Test validation error when the email is empty.
     */
    public function test_login_validation_email_required()
    {
        $response = $this->post(route('login.authenticate'), [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email wajib diisi.',
        ]);
    }

    /**
     * Test validation error when the email format is invalid.
     */
    public function test_login_validation_email_invalid_format()
    {
        $response = $this->post(route('login.authenticate'), [
            'email' => 'bukan-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Format email tidak valid.',
        ]);
    }
}
