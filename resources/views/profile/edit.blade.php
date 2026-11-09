@extends('layouts.app')

@section('title', 'My Profile & Account Settings')

@section('content')
<div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Top Navigation Header -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('programmes.hub') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Hub</span>
            </a>

            @if($user->hasAdminAccess())
            <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Admin Directory</span>
            </a>
            @endif
        </div>

        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-bold text-xs transition shadow-xs cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Sign Out</span>
            </button>
        </form>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-xs">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-semibold shadow-xs space-y-1">
        <div class="font-bold flex items-center gap-2 text-rose-900">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Please correct the errors below:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-rose-700 pl-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Profile Overview Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl {{ $user->isSuperAdmin() ? 'bg-purple-100 text-purple-700 border-purple-200' : ($user->isMealOfficer() ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-blue-100 text-blue-700 border-blue-200') }} border-2 flex items-center justify-center font-black text-2xl shrink-0 shadow-inner">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 font-display">{{ $user->name }}</h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $user->isSuperAdmin() ? 'bg-purple-100 text-purple-800 border border-purple-200' : ($user->isMealOfficer() ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($user->role === 'project_officer' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-amber-100 text-amber-800 border border-amber-200')) }}">
                            <span>{{ $user->isSuperAdmin() ? '👑' : ($user->isMealOfficer() ? '📊' : ($user->role === 'project_officer' ? '🎯' : '🤝')) }}</span>
                            <span>{{ $user->role_label }}</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $user->email }}</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Member Since</span>
                <span class="text-xs font-bold text-slate-800">{{ $user->created_at->format('F d, Y') }}</span>
            </div>
        </div>

        <!-- Metric Badges -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/70">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Assigned Projects</span>
                <div class="text-lg font-black text-slate-900 mt-0.5">
                    {{ $user->hasAdminAccess() ? 'Global Access' : $user->projects->count() . ' Projects' }}
                </div>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/70">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Logged Activities</span>
                <div class="text-lg font-black text-slate-900 mt-0.5">
                    {{ $user->activityEntries->count() }} Entries
                </div>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/70">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Account Status</span>
                <div class="text-lg font-black text-emerald-600 mt-0.5 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Active Account</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Forms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Card 1: Profile Information -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs space-y-5">
            <div>
                <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    Profile Information
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Update your account name and registered email address.</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('name')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('email')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assigned Role & Projects (Informative Read-Only) -->
                <div class="pt-2 border-t border-slate-100 space-y-2.5">
                    <div>
                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Account Role</span>
                        <div class="mt-1 p-2.5 bg-slate-50 rounded-xl border border-slate-200/70 text-xs font-bold text-slate-700 flex items-center justify-between">
                            <span>{{ $user->role_label }}</span>
                            <span class="text-[10px] font-normal text-slate-400 italic">Managed by Admin</span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Project Assignments</span>
                        <div class="mt-1 p-2.5 bg-slate-50 rounded-xl border border-slate-200/70 text-xs text-slate-700">
                            @if($user->hasAdminAccess())
                                <span class="font-bold text-purple-700 text-[11px]">★ Global Organizational Scope (All Projects)</span>
                            @elseif($user->projects->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->projects as $p)
                                        <span class="px-2 py-0.5 rounded-md bg-white text-slate-800 text-[10px] font-bold border border-slate-200">
                                            {{ $p->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 italic text-[11px]">No projects currently assigned.</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer active:scale-95">
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Card 2: Update Password -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs space-y-5">
            <div>
                <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    Security &amp; Password
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Ensure your account is using a secure password.</p>
            </div>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required autocomplete="current-password" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="••••••••">
                    @error('current_password')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1">New Password</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="Minimum 6 characters">
                    @error('password')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="Re-enter new password">
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70 text-[11px] text-slate-500 leading-relaxed">
                    💡 <strong>Tip:</strong> Use a combination of uppercase letters, numbers, and symbols to maximize account security.
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer active:scale-95">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
