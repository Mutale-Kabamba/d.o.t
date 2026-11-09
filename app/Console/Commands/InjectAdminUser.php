<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InjectAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:inject
                            {--name= : The name of the admin user (defaults to System Administrator)}
                            {--email= : The email address of the admin user}
                            {--password= : The password for the admin user}
                            {--role=super_admin : The administrative role (super_admin or meal_officer)}
                            {--update : Update user details and password if email already exists}
                            {--random-password : Force generation of a secure random password}
                            {--show-password : Print the final password to the terminal}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inject or update an administrative user (optimized for Laravel Cloud, CI/CD, and local environments)';

    /**
     * Alternative names for this command.
     *
     * @var array<int, string>
     */
    protected $aliases = [
        'admin:create',
        'make:admin',
        'app:inject-admin-user',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Initializing Admin User Injection...');

        // 1. Resolve Email
        $email = $this->option('email') ?: env('ADMIN_EMAIL');
        if (empty($email) && $this->input->isInteractive()) {
            $email = $this->ask('Admin Email');
        }

        if (empty($email)) {
            $this->error('The --email option is required (or set ADMIN_EMAIL in your environment).');
            return self::FAILURE;
        }

        $emailValidator = Validator::make(['email' => $email], [
            'email' => ['required', 'email'],
        ]);

        if ($emailValidator->fails()) {
            $this->error("Invalid email address provided: [{$email}]");
            return self::FAILURE;
        }

        // 2. Resolve Role
        $role = $this->option('role') ?: env('ADMIN_ROLE', User::ROLE_SUPER_ADMIN);
        $allowedRoles = [User::ROLE_SUPER_ADMIN, User::ROLE_MEAL_OFFICER];

        if (!in_array($role, $allowedRoles, true)) {
            if ($this->input->isInteractive()) {
                $role = $this->choice('Select Administrative Role', $allowedRoles, 0);
            } else {
                $this->error("Invalid role [{$role}]. Allowed roles: " . implode(', ', $allowedRoles));
                return self::FAILURE;
            }
        }

        // 3. Resolve Name
        $defaultName = $role === User::ROLE_SUPER_ADMIN ? 'System Administrator' : 'MEAL Officer';
        $name = $this->option('name') ?: env('ADMIN_NAME', $defaultName);

        // 4. Resolve Password
        $generatedPassword = false;
        $password = null;

        if ($this->option('random-password')) {
            $password = Str::password(16);
            $generatedPassword = true;
        } else {
            $password = $this->option('password') ?: env('ADMIN_PASSWORD');
            if (empty($password) && $this->input->isInteractive()) {
                $password = $this->secret('Password (leave empty to generate a random 16-character password)');
            }

            if (empty($password)) {
                $password = Str::password(16);
                $generatedPassword = true;
            }
        }

        // 5. Check if user already exists
        $user = User::where('email', $email)->first();
        $isExisting = $user !== null;

        if ($isExisting) {
            $shouldUpdate = $this->option('update');

            if (!$shouldUpdate && $this->input->isInteractive()) {
                $shouldUpdate = $this->confirm("User with email [{$email}] already exists. Update password and role to admin?", true);
            }

            if (!$shouldUpdate) {
                $this->warn("User [{$email}] already exists. Pass --update to overwrite credentials/role.");
                return self::FAILURE;
            }

            $user->name = $name;
            $user->role = $role;
            $user->password = Hash::make($password);
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
            }
            $user->save();

            $this->info("Admin user [{$email}] successfully updated!");
        } else {
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->role = $role;
            $user->password = Hash::make($password);
            $user->email_verified_at = now();
            $user->save();

            $this->info("Admin user [{$email}] successfully created!");
        }

        // 6. Output Summary
        $displayPassword = ($generatedPassword || $this->option('show-password'))
            ? $password
            : '******** (hidden)';

        $this->table(
            ['ID', 'Name', 'Email', 'Role', 'Status', 'Password'],
            [[
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $isExisting ? 'Updated' : 'Created',
                $displayPassword,
            ]]
        );

        if ($generatedPassword) {
            $this->newLine();
            $this->warn("Generated Password: {$password}");
            $this->comment('Please store this password in a secure password manager.');
        }

        return self::SUCCESS;
    }
}
