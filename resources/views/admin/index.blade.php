@extends('layouts.app')

@section('title', 'Admin Dashboard - Cohort Submissions & Insights')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Top Admin Header -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black shadow-md shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-extrabold uppercase bg-brand-50 text-brand-700 px-2 py-0.5 rounded border border-brand-200">Admin Portal</span>
                    <span class="text-xs text-slate-400">Authenticated: {{ Auth::user()->name ?? 'Administrator' }}</span>
                </div>
                <h1 class="text-lg sm:text-2xl font-black text-slate-900 font-display">Cohort Submissions &amp; Reports</h1>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('worksheet.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition shadow-sm">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Participant App</span>
            </a>

            <!-- Full Master PDF Report Export -->
            <a href="{{ route('admin.submissions.export_master_pdf') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-brand-700 text-white font-bold text-xs transition shadow-sm active:scale-95">
                <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Export Full PDF Report</span>
            </a>

            <!-- CSV Export -->
            <a href="{{ route('admin.submissions.export_csv') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export CSV</span>
            </a>

            <!-- Logout -->
            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-2 rounded-xl border border-slate-200 text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Sign Out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 text-emerald-800 rounded-2xl text-xs font-bold border border-emerald-200 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <!-- Total Card -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Submissions</span>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 font-display">{{ $totalCount }}</div>
            <p class="text-[11px] text-slate-400">100% De-identified responses</p>
        </div>

        <!-- Safeguarding Clear -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-emerald-800 uppercase tracking-wide">Safeguarding: Clear</span>
            <div class="text-2xl sm:text-3xl font-black text-emerald-600 font-display">{{ $safeguardingClear }}</div>
            <p class="text-[11px] text-slate-400">"Completely clear to youth"</p>
        </div>

        <!-- Safeguarding Needs Improvement -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-amber-800 uppercase tracking-wide">Needs Protocol Fixes</span>
            <div class="text-2xl sm:text-3xl font-black text-amber-600 font-display">{{ $safeguardingNeedsFix }}</div>
            <p class="text-[11px] text-slate-400">"Partially clear / Needs fixes"</p>
        </div>

        <!-- Safeguarding Unclear -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-rose-800 uppercase tracking-wide">Reporting Gaps</span>
            <div class="text-2xl sm:text-3xl font-black text-rose-600 font-display">{{ $safeguardingUnclear }}</div>
            <p class="text-[11px] text-slate-400">"No, not clear"</p>
        </div>
    </div>

    <!-- Sentiment Word Cloud / Highlight Pills -->
    @if($recentWords->isNotEmpty())
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex flex-wrap items-center gap-2">
        <span class="text-xs font-bold text-slate-500 uppercase mr-1">Top Feelings Reported:</span>
        @foreach($recentWords as $word)
            <span class="inline-flex items-center text-xs font-bold bg-brand-50 text-brand-700 px-3 py-1 rounded-full border border-brand-200">
                ✨ {{ $word }}
            </span>
        @endforeach
    </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.submissions.index') }}" class="flex flex-col sm:flex-row gap-2.5">
            <div class="relative flex-1">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ $search }}" placeholder="Search by keyword, token, topic (e.g. stipend, PACRA, transport)..." class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-sm cursor-pointer">
                Search
            </button>
            @if($search)
                <a href="{{ route('admin.submissions.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm rounded-xl transition text-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Submissions Table / Cards -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($submissions->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">No Submissions Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    {{ $search ? "No responses match the keyword \"{$search}\"." : "Participants have not submitted any worksheets yet." }}
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px] tracking-wider">
                            <th class="py-3.5 px-4">Receipt Token</th>
                            <th class="py-3.5 px-4">Submitted</th>
                            <th class="py-3.5 px-4">One Word</th>
                            <th class="py-3.5 px-4">Key Recommendation Preview</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($submissions as $s)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-brand-700 text-xs">
                                <a href="{{ route('admin.submissions.show', $s->token) }}" class="hover:underline">
                                    {{ Str::limit($s->token, 16) }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $s->created_at->format('M d, Y - H:i') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($s->s8_one_word)
                                    <span class="inline-flex items-center text-[11px] font-bold bg-emerald-50 text-emerald-700 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                        {{ $s->s8_one_word }}
                                    </span>
                                @else
                                    <span class="text-slate-300 italic text-xs">--</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600 max-w-xs truncate">
                                {{ $s->s8_change_one_thing ?: ($s->s1_recruitment_high ?: '--') }}
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('worksheet.download_pdf', $s->token) }}" title="Download Individual PDF" class="inline-flex items-center p-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-700 text-slate-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>

                                <a href="{{ route('admin.submissions.show', $s->token) }}" title="View Full Report" class="inline-flex items-center p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($submissions->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $submissions->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
