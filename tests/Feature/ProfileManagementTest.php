<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_profile_page(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_all_account_roles_can_view_profile_page(): void
    {
        $roles = [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_MEAL_OFFICER,
            User::ROLE_PROJECT_OFFICER,
            User::ROLE_PROJECT_ASSISTANT,
        ];

        foreach ($roles as $role) {
            $user = User::factory()->create([
                'name' => "User {$role}",
                'email' => "user.{$role}@dot.org",
                'role' => $role,
            ]);

            $response = $this->actingAs($user)->get('/profile');
            $response->assertStatus(200);
            $response->assertSee("User {$role}");
            $response->assertSee("user.{$role}@dot.org");
            $response->assertSee('Profile Information');
            $response->assertSee('Security &amp; Password', false);
        }
    }

    public function test_user_can_update_profile_name_and_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@dot.org',
            'role' => User::ROLE_PROJECT_OFFICER,
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Updated Officer Name',
            'email' => 'updated.officer@dot.org',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Updated Officer Name', $user->name);
        $this->assertEquals('updated.officer@dot.org', $user->email);
    }

    public function test_email_must_be_unique_when_updating_profile(): void
    {
        $existingUser = User::factory()->create(['email' => 'existing@dot.org']);
        $user = User::factory()->create(['email' => 'user@dot.org']);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Attempt Duplicate Email',
            'email' => 'existing@dot.org',
        ]);

        $response->assertSessionHasErrors(['email']);
        $user->refresh();
        $this->assertEquals('user@dot.org', $user->email);
    }

    public function test_user_can_update_password_with_valid_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'oldpassword123',
            'password' => 'newsecurepassword456',
            'password_confirmation' => 'newsecurepassword456',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newsecurepassword456', $user->password));
    }

    public function test_password_update_fails_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword789',
            'password_confirmation' => 'newpassword789',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $user->refresh();
        $this->assertTrue(Hash::check('correctpassword', $user->password));
    }

    public function test_password_update_fails_with_unconfirmed_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'correctpassword',
            'password' => 'newpassword789',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
