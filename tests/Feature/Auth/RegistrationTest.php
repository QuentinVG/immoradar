<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        config(['app.registration_access_code' => null]);

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        config(['app.registration_access_code' => null]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            'website' => '',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_registration_rejects_honeypot_bots(): void
    {
        config(['app.registration_access_code' => null]);

        $response = $this->post('/register', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            'website' => 'https://spam.test',
        ]);

        $response->assertSessionHasErrors('website');
        $this->assertGuest();
    }

    public function test_registration_access_code_can_be_required(): void
    {
        config(['app.registration_access_code' => 'private-code']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'private@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            'website' => '',
            'access_code' => 'wrong',
        ]);

        $response->assertSessionHasErrors('access_code');
        $this->assertGuest();
    }
}
