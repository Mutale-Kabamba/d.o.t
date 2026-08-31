@extends('layouts.app')

@section('title', 'Submission Successful - Play It Forward Zambia')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-sm text-center relative">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <span class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 rounded-full inline-block mb-3">
            {{ $submission->reporting_period }}
        </span>

        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Project Brief Submitted!</h1>
        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
            Thank you, <strong class="text-slate-900">{{ $submission->officer_name }}</strong>. Key points for <strong class="text-blue-700">{{ $submission->project_name }}</strong> have been recorded and compiled into the supervisor's master presentation queue.
        </p>

        <!-- Project Summary Card -->
        <div class="mt-6 bg-slate-50 border border-slate-200/90 rounded-2xl p-4 text-left space-y-2.5 text-xs">
            <div class="flex justify-between border-b border-slate-200/60 pb-2">
                <span class="text-slate-500 font-medium">Officer / Lead:</span>
                <span class="font-semibold text-slate-800">{{ $submission->officer_name }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200/60 pb-2">
                <span class="text-slate-500 font-medium">Project Title:</span>
                <span class="font-bold text-slate-900">{{ $submission->project_name }}</span>
            </div>
            <div class="flex justify-between pt-0.5">
                <span class="text-slate-500 font-medium">Receipt Token:</span>
                <code class="font-mono text-[10px] text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">{{ $submission->token }}</code>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('programmes.download_single_pdf', ['token' => $submission->token]) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-600/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download My Slide Deck (PDF)
            </a>
            <a href="{{ route('programmes.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                Submit Another Project Brief
            </a>
        </div>
    </div>
</div>
@endsection
