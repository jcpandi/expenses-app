<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_see_page_or_api(): void
    {
        User::factory()->create();
        $this->get('/')->assertRedirect('/login');
        $this->getJson('/api/expenses')->assertUnauthorized();
        $this->postJson('/api/expenses', ['amount' => 1, 'category' => 'Food', 'date' => '2026-11-01'])->assertUnauthorized();
        $this->getJson('/api/settings')->assertUnauthorized();
    }

    public function test_first_run_goes_to_setup_and_creates_account(): void
    {
        $this->get('/login')->assertRedirect('/setup');
        $this->post('/setup', [
            'name' => 'JC', 'email' => 'owner@example.com',
            'password' => 'secret-pass', 'password_confirmation' => 'secret-pass',
        ])->assertRedirect('/');
        $this->assertAuthenticated();
        $this->get('/')->assertOk()->assertSee('Sign out');
    }

    public function test_setup_is_closed_once_an_account_exists(): void
    {
        User::factory()->create();
        $this->get('/setup')->assertNotFound();
        $this->post('/setup', [
            'name' => 'Intruder', 'email' => 'x@example.com',
            'password' => 'secret-pass', 'password_confirmation' => 'secret-pass',
        ])->assertNotFound();
        $this->assertSame(1, User::count());
    }

    public function test_login_and_logout(): void
    {
        User::factory()->create(['email' => 'owner@example.com', 'password' => 'secret-pass']);

        $this->post('/login', ['email' => 'owner@example.com', 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/login', ['email' => 'owner@example.com', 'password' => 'secret-pass'])->assertRedirect('/');
        $this->assertAuthenticated();

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        User::factory()->create(['email' => 'owner@example.com', 'password' => 'secret-pass']);
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'owner@example.com', 'password' => 'wrong']);
        }
        $this->post('/login', ['email' => 'owner@example.com', 'password' => 'secret-pass'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
