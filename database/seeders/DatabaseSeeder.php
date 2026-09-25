<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Core Projects
        $p1 = Project::firstOrCreate(
            ['code' => 'YFLA'],
            [
                'name' => 'Youth Football & Leadership Academy',
                'description' => 'Grassroots sports coaching, youth leadership, and life skills mentorship across community hubs.',
                'location' => 'Lusaka Hub',
                'status' => 'active',
            ]
        );

        $p2 = Project::firstOrCreate(
            ['code' => 'GEEI'],
            [
                'name' => 'Girls Empowerment & Education Initiative',
                'description' => 'Targeted mentorship, menstrual hygiene management, and school retention programs for young women and girls.',
                'location' => 'Livingstone Hub',
                'status' => 'active',
            ]
        );

        $p3 = Project::firstOrCreate(
            ['code' => 'CHWO'],
            [
                'name' => 'Community Health & Wellness Outreach',
                'description' => 'Integrated community health screening, HIV awareness, and adolescent health education sessions.',
                'location' => 'Ndola Hub',
                'status' => 'active',
            ]
        );

        $p4 = Project::firstOrCreate(
            ['code' => 'DSLH'],
            [
                'name' => 'Digital Skills & Livelihoods Hub',
                'description' => 'Vocational technology training, digital literacy, and youth entrepreneurship incubation.',
                'location' => 'Kitwe Hub',
                'status' => 'active',
            ]
        );

        // 2. Seed Super Admin Accounts
        User::updateOrCreate(
            ['email' => 'admin@pifzambia.org'],
            [
                'name' => 'Super Administrator',
                'role' => User::ROLE_SUPER_ADMIN,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@dot.org'],
            [
                'name' => 'DOT Admin',
                'role' => User::ROLE_SUPER_ADMIN,
                'password' => Hash::make('password'),
            ]
        );

        // 3. Seed Project Officer
        $officer = User::updateOrCreate(
            ['email' => 'officer@pifzambia.org'],
            [
                'name' => 'Lead Project Officer',
                'role' => User::ROLE_PROJECT_OFFICER,
                'password' => Hash::make('password'),
            ]
        );
        $officer->projects()->syncWithoutDetaching([$p1->id, $p2->id]);
    }
}

