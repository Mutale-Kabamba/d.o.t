@extends('layouts.app')

@section('title', 'Sign In — Play It Forward Zambia')

@section('content')
<div class="min-h-[90vh] flex items-center justify-center p-4 bg-slate-50">
    <div class="bg-white rounded-3xl p-6 sm:p-10 max-w-md w-full border border-slate-200 shadow-xl space-y-6">
        
        <!-- Header & Branding -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center mx-auto shadow-md shadow-blue-500/20 font-black text-xl">
                P
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Play It Forward Zambia</h1>
            <p class="text-xs text-slate-500 leading-relaxed">
                Sign in to your authenticated account to access assigned projects, continuous activity logging, and presentation tools.
            </p>
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
        <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="off" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="email">Account Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="off" placeholder="officer@pifzambia.org" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 text-sm outline-none transition font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 text-sm outline-none transition font-medium">
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600">
                    <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition shadow-md shadow-blue-600/20 active:scale-95 cursor-pointer">
                Sign In to Dashboard
            </button>
        </form>

        <div class="pt-3 text-center border-t border-slate-100 text-[11px] text-slate-400">
            Play It Forward Zambia • Continuous Activity Logging System
        </div>
    </div>
</div>
@endsection
