<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_create_an_account_and_is_logged_in(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Ayu Pratama',
            'email' => 'ayu@example.test',
            'password' => 'secure-password-123',
            'password_confirmation' => 'secure-password-123',
        ]);

        $user = User::query()->where('email', 'ayu@example.test')->firstOrFail();

        $response->assertRedirectToRoute('designs.index');
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('secure-password-123', $user->password));
    }

    public function test_guest_can_view_the_registration_form(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Buat akun pengguna')
            ->assertSee('name="password_confirmation"', false);
    }

    public function test_registration_rejects_an_email_that_is_already_in_use(): void
    {
        User::factory()->create(['email' => 'ayu@example.test']);

        $response = $this->post(route('register'), [
            'name' => 'Ayu Pratama',
            'email' => 'ayu@example.test',
            'password' => 'secure-password-123',
            'password_confirmation' => 'secure-password-123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email sudah terdaftar.',
        ]);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_registration_rejects_a_mismatched_password_confirmation(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Ayu Pratama',
            'email' => 'ayu@example.test',
            'password' => 'secure-password-123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Konfirmasi password tidak cocok.',
        ]);
        $this->assertDatabaseEmpty('users');
    }
}
