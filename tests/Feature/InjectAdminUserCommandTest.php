<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InjectAdminUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_inject_admin_user_with_explicit_options(): void
    {
        $this->artisan('admin:inject', [
            '--name' => 'Cloud Admin',
            '--email' => 'cloudadmin@example.com',
            '--password' => 'SecurePass123!',
            '--role' => User::ROLE_SUPER_ADMIN,
        ])
        ->assertSuccessful()
        ->expectsOutputToContain('Admin user [cloudadmin@example.com] successfully created!');

        $user = User::where('email', 'cloudadmin@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Cloud Admin', $user->name);
        $this->assertEquals(User::ROLE_SUPER_ADMIN, $user->role);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check('SecurePass123!', $user->password));
        $this->assertTrue($user->hasAdminAccess());
    }

    public function test_can_inject_meal_officer_admin(): void
    {
        $this->artisan('admin:inject', [
            '--name' => 'Cloud MEAL Officer',
            '--email' => 'cloudmeal@example.com',
            '--password' => 'MealPass456!',
            '--role' => User::ROLE_MEAL_OFFICER,
        ])
        ->assertSuccessful();

        $user = User::where('email', 'cloudmeal@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(User::ROLE_MEAL_OFFICER, $user->role);
        $this->assertTrue($user->hasAdminAccess());
    }

    public function test_can_inject_admin_with_random_password(): void
    {
        $this->artisan('admin:inject', [
            '--name' => 'Random Pass Admin',
            '--email' => 'random@example.com',
            '--random-password' => true,
        ])
        ->assertSuccessful()
        ->expectsOutputToContain('Generated Password:');

        $user = User::where('email', 'random@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(User::ROLE_SUPER_ADMIN, $user->role);
    }

    public function test_fails_when_email_is_invalid(): void
    {
        $this->artisan('admin:inject', [
            '--email' => 'not-an-email',
            '--password' => 'Password123!',
        ])
        ->assertFailed()
        ->expectsOutputToContain('Invalid email address provided');
    }

    public function test_fails_when_role_is_invalid_in_non_interactive_mode(): void
    {
        $this->artisan('admin:inject', [
            '--email' => 'test@example.com',
            '--password' => 'Password123!',
            '--role' => 'invalid_role',
            '--no-interaction' => true,
        ])
        ->assertFailed()
        ->expectsOutputToContain('Invalid role [invalid_role]');
    }

    public function test_fails_when_user_exists_without_update_flag(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
            'role' => User::ROLE_PROJECT_OFFICER,
        ]);

        $this->artisan('admin:inject', [
            '--email' => 'existing@example.com',
            '--password' => 'NewPass123!',
            '--no-interaction' => true,
        ])
        ->assertFailed()
        ->expectsOutputToContain('Pass --update to overwrite credentials/role.');
    }

    public function test_can_update_existing_user_with_update_flag(): void
    {
        $user = User::factory()->create([
            'email' => 'promote@example.com',
            'role' => User::ROLE_PROJECT_OFFICER,
            'password' => Hash::make('OldPass123!'),
        ]);

        $this->artisan('admin:inject', [
            '--name' => 'Promoted Admin',
            '--email' => 'promote@example.com',
            '--password' => 'BrandNewPass123!',
            '--role' => User::ROLE_SUPER_ADMIN,
            '--update' => true,
        ])
        ->assertSuccessful()
        ->expectsOutputToContain('Admin user [promote@example.com] successfully updated!');

        $user->refresh();
        $this->assertEquals('Promoted Admin', $user->name);
        $this->assertEquals(User::ROLE_SUPER_ADMIN, $user->role);
        $this->assertTrue(Hash::check('BrandNewPass123!', $user->password));
    }

    public function test_command_aliases_work(): void
    {
        $this->artisan('admin:create', [
            '--email' => 'alias1@example.com',
            '--password' => 'Pass123!',
        ])->assertSuccessful();

        $this->artisan('make:admin', [
            '--email' => 'alias2@example.com',
            '--password' => 'Pass123!',
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', ['email' => 'alias1@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'alias2@example.com']);
    }
}

