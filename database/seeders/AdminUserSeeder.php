<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds for provisioning an initial administrator.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@dot.org');
        $name = env('ADMIN_NAME', 'System Administrator');
        $password = env('ADMIN_PASSWORD', 'password');
        $role = env('ADMIN_ROLE', User::ROLE_SUPER_ADMIN);

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'role' => $role,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Admin user [{$user->email}] provisioned with role [{$user->role}].");
    }
}

