<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Authentication validation feature tests.
 */
class AuthValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registration requires name.
     */
    public function test_registration_requires_name(): void
    {
        $response = $this->postJson('/api/register', [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => UserRole::SPEAKER->value,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test registration requires email.
     */
    public function test_registration_requires_email(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => UserRole::SPEAKER->value,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test registration requires valid email.
     */
    public function test_registration_requires_valid_email(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => UserRole::SPEAKER->value,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test registration requires unique email.
     */
    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => UserRole::SPEAKER->value,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test registration requires password confirmation.
     */
    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => UserRole::SPEAKER->value,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test registration requires valid role.
     */
    public function test_registration_requires_valid_role(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'invalid-role',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role']);
    }

    /**
     * Test registration cannot use admin role.
     */
    public function test_registration_cannot_use_admin_role(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => UserRole::ADMIN->value,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role']);
    }
}
