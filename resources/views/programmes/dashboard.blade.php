@extends('layouts.app')

@section('title', 'Programmes Meeting Compilation Hub - Play It Forward Zambia')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Top Header & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/10">
                    P
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Play It Forward Zambia</h1>
                        <span class="px-2.5 py-0.5 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 rounded-full">Supervisor Hub</span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium">Programmes Meeting • Quarter 2 April, May, June 2026</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <form action="{{ route('programmes.seed') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl transition shadow-xs">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Seed Sample Projects
                    </button>
                </form>

                <a href="{{ route('programmes.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 hover:text-blue-600 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl transition shadow-xs">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    + Submit Brief
                </a>

                <a href="{{ route('programmes.projector') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ▶ Project Directly
                </a>

                <a href="{{ route('programmes.export_consolidated_pptx') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    PowerPoint (.pptx)
                </a>

                <a href="{{ route('programmes.export_consolidated_pdf') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF (16:9)
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2 font-medium">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </span>
        </div>
        @endif

        <!-- Compilation Summary Card -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="max-w-2xl space-y-1.5">
                    <span class="px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 rounded-full inline-block">
                        One Presentation • Consolidated Meeting
                    </span>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Consolidated Slide Deck Overview</h2>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Each slide in the master template compiles the corresponding items (Key Milestones, Impact Evidence, Challenges, Lessons Learned, M&E, Collaboration) from all project officers into unified slides so the meeting is presented cohesively.
                    </p>
                </div>
                <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 shrink-0">
                    <div class="text-center px-4 border-r border-slate-200">
                        <div class="text-2xl font-black text-blue-600">{{ $submissions->count() }}</div>
                        <div class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Active Projects</div>
                    </div>
                    <div class="text-center px-4">
                        <div class="text-2xl font-black text-slate-800">5</div>
                        <div class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Slide Themes</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Submissions List -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Submitted Project Briefs</h3>
                <span class="text-xs text-slate-500 font-medium">{{ $submissions->count() }} projects compiled</span>
            </div>

            @if($submissions->isEmpty())
            <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-12 text-center space-y-4">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900">No project briefs submitted yet</h4>
                    <p class="text-xs text-slate-500 mt-1">Project officers can submit their briefs, or you can seed realistic sample projects to preview the consolidated PDF.</p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <form action="{{ route('programmes.seed') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm">
                            Seed 3 Sample Projects
                        </button>
                    </form>
                    <a href="{{ route('programmes.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Fill Project Brief
                    </a>
                </div>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($submissions as $sub)
                <div class="bg-white border border-slate-200 hover:border-slate-300 rounded-2xl p-5 shadow-sm flex flex-col justify-between transition">
                    <div>
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider bg-blue-50 border border-blue-200/80 px-2 py-0.5 rounded">
                                    {{ $sub->reporting_period ?? 'Q2 2026' }}
                                </span>
                                <h4 class="text-sm font-bold text-slate-900 mt-1.5 leading-snug">{{ $sub->project_name }}</h4>
                            </div>
                        </div>

                        <!-- Officer Meta -->
                        <div class="flex items-center gap-2 text-xs text-slate-500 mb-3 pb-3 border-b border-slate-100">
                            <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-[10px] font-bold border border-slate-200">
                                {{ strtoupper(substr($sub->officer_name, 0, 1)) }}
                            </span>
                            <span class="font-medium text-slate-700">{{ $sub->officer_name }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-[11px] text-slate-400">{{ $sub->created_at->format('M j, Y') }}</span>
                        </div>

                        <!-- Key Milestones Snippet -->
                        <div class="space-y-1.5 mb-4">
                            <div class="text-[11px] font-bold text-slate-600 uppercase tracking-wide">Key Milestones:</div>
                            <div class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 line-clamp-3 leading-relaxed whitespace-pre-line">
                                {{ $sub->achievements_milestones ?: 'No milestones listed' }}
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <a href="{{ route('programmes.download_single_pdf', ['token' => $sub->token]) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Slide Deck
                        </a>
                        <form action="{{ route('programmes.destroy', ['token' => $sub->token]) }}" method="POST" onsubmit="return confirm('Delete this project submission?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-rose-500 text-xs p-1 transition" title="Delete submission">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
