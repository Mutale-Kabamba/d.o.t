@extends('layouts.app')

@section('title', 'Worksheet Submitted - 100% Anonymous')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-10 max-w-md w-full border border-slate-200 shadow-xl text-center space-y-6">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                100% Anonymous &amp; Encrypted
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 font-display">Worksheet Submitted!</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Thank you for providing your honest feedback and strategic insights. Your responses have been recorded without any personal identifiers to improve upcoming cohorts.
            </p>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-left space-y-1">
            <span class="text-[10px] uppercase font-extrabold text-slate-400">Submission Receipt Token:</span>
            <div class="font-mono text-xs font-bold text-brand-800 break-all select-all">
                {{ $submission->token }}
            </div>
            <div class="text-[11px] text-slate-400 pt-1">
                Recorded on: {{ $submission->created_at->format('M d, Y - H:i') }}
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <a href="{{ route('worksheet.index') }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to App</span>
            </a>
        </div>
    </div>
</div>
@endsection
