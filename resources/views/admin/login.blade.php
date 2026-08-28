@extends('layouts.app')

@section('title', 'Admin Login - Cohort Management')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-10 max-w-md w-full border border-slate-200 shadow-xl space-y-6">
        
        <!-- Header & Branding -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center mx-auto shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 font-display">Administrator Login</h1>
            <p class="text-xs text-slate-500">Sign in to access cohort analytics, individual reports, and full PDF downloads.</p>
        </div>

        @if(session('status'))
            <div class="p-3 bg-emerald-50 text-emerald-800 rounded-xl text-xs font-semibold border border-emerald-200 text-center">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-3 bg-rose-50 text-rose-800 rounded-xl text-xs font-semibold border border-rose-200">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="email">Admin Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', 'admin@dot.org') }}" required autofocus placeholder="admin@dot.org" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="password">Password</label>
                <input type="password" id="password" name="password" value="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition font-medium">
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500">
                    <span>Remember me</span>
                </label>
                <span class="text-[11px] text-slate-400">Default: admin@dot.org / password</span>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-brand-700 text-white font-bold text-sm transition shadow-md active:scale-95 cursor-pointer">
                Sign In to Dashboard
            </button>
        </form>

        <div class="pt-2 text-center border-t border-slate-100">
            <a href="{{ route('worksheet.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-800 transition">
                &larr; Return to Participant App
            </a>
        </div>
    </div>
</div>
@endsection
