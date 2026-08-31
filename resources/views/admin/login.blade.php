@extends('layouts.app')

@section('title', 'Supervisor Login — Play It Forward Zambia')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-10 max-w-md w-full border border-slate-200 shadow-xl space-y-6">
        
        <!-- Header & Branding -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center mx-auto shadow-md shadow-blue-500/20 font-black text-xl">
                P
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Supervisor Login</h1>
            <p class="text-xs text-slate-500 leading-relaxed">
                Sign in with your supervisor credentials to access the compilation hub, live slide projector, and export tools.
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

            <!-- Fake inputs to disable aggressive browser autofill -->
            <input style="display:none" type="text" name="fakeusernameremembered"/>
            <input style="display:none" type="password" name="fakepasswordremembered"/>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="email">Supervisor Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', 'admin@dot.org') }}" required autocomplete="off" placeholder="supervisor@pifzambia.org" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 text-sm outline-none transition font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 text-sm outline-none transition font-medium">
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600">
                    <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition shadow-md shadow-blue-600/20 active:scale-95 cursor-pointer">
                Sign In to Supervisor Hub
            </button>
        </form>

        <div class="pt-3 text-center border-t border-slate-100 flex items-center justify-between text-xs">
            <a href="{{ route('programmes.index') }}" class="font-bold text-blue-600 hover:text-blue-800 transition">
                &larr; Return to Project Brief
            </a>
            <span class="text-[11px] text-slate-400">Play It Forward Zambia</span>
        </div>
    </div>
</div>
@endsection
