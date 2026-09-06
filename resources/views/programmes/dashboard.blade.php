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

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="document.getElementById('import-pptx-modal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-purple-700 hover:text-purple-800 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-xl transition shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Import PPTX
                </button>

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

                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition shadow-xs cursor-pointer" title="Log Out of Supervisor Hub">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
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

        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-xs flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2 font-medium">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ session('error') }}
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
                            <div class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 line-clamp-3 leading-relaxed whitespace-pre-line break-words overflow-hidden">
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

        <!-- Footer & Quick Actions -->
        <footer class="mt-12 pt-8 border-t border-slate-200 space-y-6">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1 text-center sm:text-left">
                    <h4 class="text-sm font-bold text-slate-900">Manage Project Briefs</h4>
                    <p class="text-xs text-slate-500">Submit a new project report or generate sample data for meeting preparation.</p>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <form action="{{ route('programmes.seed') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition shadow-xs cursor-pointer">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Seed Sample Projects
                        </button>
                    </form>

                    <a href="{{ route('programmes.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        + Submit Brief
                    </a>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-2 pb-6 px-1">
                <div>
                    &copy; {{ date('Y') }} <span class="font-semibold text-slate-600">Play It Forward Zambia</span>. All rights reserved.
                </div>
                <div class="flex items-center gap-4">
                    <span>Programmes Meeting Hub</span>
                    <span>•</span>
                    <a href="{{ route('programmes.projector') }}" class="hover:text-slate-600 transition">Presentation Screen</a>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- ==================== IMPORT PPTX MODAL ==================== -->
<div id="import-pptx-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-5 animate-in fade-in zoom-in duration-150">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Import from PowerPoint (.pptx)</h3>
                    <p class="text-xs text-slate-500">Upload a single project deck or master meeting PPTX.</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('import-pptx-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('programmes.import_pptx') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Select PowerPoint File (.pptx)</label>
                <div class="border-2 border-dashed border-slate-300 hover:border-purple-400 rounded-xl p-6 text-center bg-slate-50/70 hover:bg-purple-50/30 transition cursor-pointer" onclick="document.getElementById('pptx_file_input').click()">
                    <svg class="w-8 h-8 text-purple-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <div class="text-xs font-bold text-slate-700" id="file_chosen_label">Click to browse or drop PowerPoint (.pptx) here</div>
                    <div class="text-[11px] text-slate-400 mt-1">Supports consolidated slide decks or individual project briefs (Max 50MB)</div>
                    <input type="file" id="pptx_file_input" name="pptx_file" accept=".pptx,application/vnd.openxmlformats-officedocument.presentationml.presentation" required class="hidden" onchange="if(this.files[0]) document.getElementById('file_chosen_label').innerHTML = '📄 ' + this.files[0].name;">
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-xs text-blue-800 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="leading-relaxed">The parser will automatically map project titles, officer names, and all 15 slide items (Milestones, Impact, Challenges, Learning, M&E, Collaboration) directly into your database.</span>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('import-pptx-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-xs transition">
                    Start Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
