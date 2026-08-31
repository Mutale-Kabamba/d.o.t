<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show admin login view.
     */
    public function showLoginForm(): View
    {
        $this->ensureDatabaseReady();

        return view('admin.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $this->ensureDatabaseReady();

        try {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                return redirect()->intended(route('programmes.hub'));
            }
        } catch (\Throwable $e) {
            Log::error('Admin login error: ' . $e->getMessage());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Ensure database tables and default admin user exist without throwing 500 error on cloud.
     */
    protected function ensureDatabaseReady(): void
    {
        try {
            if (!Schema::hasTable('users')) {
                Schema::create('users', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->timestamps();
                });
            }

            if (Schema::hasTable('users') && User::count() === 0) {
                User::create([
                    'name' => 'Supervisor',
                    'email' => 'admin@dot.org',
                    'password' => Hash::make('password'),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Database auto-initialization note: ' . $e->getMessage());
        }
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out and returned to the app.');
    }
}
