<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * L'inscription publique est désactivée (voir routes/auth.php) : la
     * gestion des utilisateurs est réservée au Super Admin, conformément au
     * cahier des charges. Ces tests vérifient que ce choix reste en place.
     */
    public function test_registration_screen_is_not_accessible(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_new_users_cannot_register_via_the_public_route(): void
    {
        $usersBefore = User::count();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(404);
        $this->assertGuest();
        $this->assertSame($usersBefore, User::count());
    }
}
