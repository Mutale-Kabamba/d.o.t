@extends('layouts.app')

@section('title', 'Programmes Hub - Play It Forward Zambia')

@section('content')
<div class="min-h-screen bg-slate-100 flex flex-col md:flex-row font-sans text-slate-800 antialiased">

    <!-- ==================== SIDE NAVIGATION BAR ==================== -->
    <aside class="w-full md:w-64 lg:w-72 bg-white border-r border-slate-200/90 flex flex-col justify-between shrink-0 shadow-xs">
        <div class="p-5 space-y-6">
            <!-- Brand & Organization Identity -->
            <div class="flex items-center gap-3 pb-5 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-lg shadow-md shadow-blue-500/20 shrink-0">
                    P
                </div>
                <div class="min-w-0">
                    <h1 class="text-sm font-black text-slate-900 tracking-tight leading-tight truncate">Play It Forward</h1>
                    <span class="text-[11px] text-slate-400 font-medium">Programmes Hub</span>
                </div>
            </div>

            <!-- User Profile Summary Pill -->
            <a href="{{ route('profile.edit') }}" class="p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200/70 hover:border-blue-300 rounded-2xl flex items-center gap-3 transition group cursor-pointer" title="Manage your account & profile">
                <div class="w-9 h-9 rounded-xl {{ $isSuperAdmin ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-blue-100 text-blue-700 border-blue-200' }} border flex items-center justify-center font-bold text-xs shrink-0 group-hover:scale-105 transition">
                    {{ strtoupper(substr($currentUser->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition truncate flex items-center justify-between gap-1">
                        <span class="truncate">{{ $currentUser->name ?? 'Supervisor' }}</span>
                        <span class="text-[10px] text-slate-400 font-normal">⚙️</span>
                    </div>
                    <span class="inline-block px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-md {{ $isSuperAdmin ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                        {{ $currentUser ? $currentUser->role_label : 'Staff' }}
                    </span>
                </div>
            </a>

            <!-- Navigation Links / Tabs -->
            <nav class="space-y-1.5" aria-label="Main Sidebar Navigation">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider px-3 py-1">
                    Workspace
                </div>

                @php
                    $baseQuery = request()->except(['tab', 'page']);
                @endphp

                <!-- Tab 0: Executive Dashboard -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'dashboard'])) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ ($currentTab === 'dashboard' || !$currentTab) ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ ($currentTab === 'dashboard' || !$currentTab) ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ ($currentTab === 'dashboard' || !$currentTab) ? 'bg-blue-700/50 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                        KPIs
                    </span>
                </a>

                <!-- Tab 1: Activity Logs -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'activities'])) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ $currentTab === 'activities' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'activities' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>Activity Logs</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $currentTab === 'activities' ? 'bg-blue-700/50 text-white' : 'bg-slate-100 text-slate-600' }}">
                        {{ $activities->total() }}
                    </span>
                </a>

                <!-- Tab 2: Projects Directory -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'projects'])) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ $currentTab === 'projects' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'projects' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Projects</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $currentTab === 'projects' ? 'bg-blue-700/50 text-white' : 'bg-slate-100 text-slate-600' }}">
                        {{ $projects->where('is_department', false)->count() }}
                    </span>
                </a>

                <!-- Tab: Parent Projects / Departments -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'departments'])) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ $currentTab === 'departments' ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'departments' ? 'text-white' : 'text-purple-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Parent Projects</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $currentTab === 'departments' ? 'bg-purple-700/50 text-white' : 'bg-purple-50 text-purple-700' }}">
                        {{ $departments->count() }}
                    </span>
                </a>

                <!-- Tab 3: Thematic Pillars Matrix -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'matrix'])) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ $currentTab === 'matrix' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'matrix' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Thematic Matrix</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $currentTab === 'matrix' ? 'bg-blue-700/50 text-white' : 'bg-slate-100 text-slate-600' }}">
                        5 Pillars
                    </span>
                </a>

                <!-- Account & Profile -->
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>My Profile</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-bold">
                        ⚙️
                    </span>
                </a>

                @if($isSuperAdmin)
                <!-- Administration Section -->
                <div class="pt-4 text-[10px] font-black text-slate-400 uppercase tracking-wider px-3 py-1">
                    Administration
                </div>

                <!-- Tab 4: Staff & Team Management -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'staff'])) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ $currentTab === 'staff' ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'staff' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Team &amp; Staff</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $currentTab === 'staff' ? 'bg-purple-700/50 text-white' : 'bg-purple-100 text-purple-800' }}">
                        {{ $allUsers->count() }}
                    </span>
                </a>
                @endif
            </nav>

            <!-- Quick Creation Actions -->
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ route('programmes.activities.create') }}" class="w-full flex items-center justify-center gap-2 px-3.5 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Log New Activity</span>
                </a>

                @if($isSuperAdmin)
                <div class="grid grid-cols-2 gap-1.5">
                    <button type="button" onclick="document.getElementById('create-project-modal').classList.remove('hidden')" class="px-2.5 py-2 text-[11px] font-bold text-purple-700 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-xl transition cursor-pointer text-center">
                        + Project
                    </button>
                    <button type="button" onclick="document.getElementById('create-user-modal').classList.remove('hidden')" class="px-2.5 py-2 text-[11px] font-bold text-indigo-700 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition cursor-pointer text-center">
                        + Staff
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Footer & Logout -->
        <div class="p-4 border-t border-slate-100">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl border border-rose-200 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ==================== MAIN WORKSPACE AREA ==================== -->
    <main class="flex-1 min-w-0 flex flex-col overflow-y-auto">

        <!-- Top Header Navigation (Quick Access Links) -->
        <header class="bg-white border-b border-slate-200/90 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-30 shadow-xs">
            <!-- Current Section Breadcrumb -->
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    <span>Play It Forward</span>
                    <span>/</span>
                    <span class="text-blue-600 font-bold">
                        @if($currentTab === 'projects') Projects Directory
                        @elseif($currentTab === 'staff') Team &amp; Staff Management
                        @elseif($currentTab === 'matrix') 5 Thematic Pillars Matrix
                        @elseif($currentTab === 'activities') Continuous Activity Logging
                        @else Executive Overview &amp; KPIs
                        @endif
                    </span>
                </div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight mt-0.5">
                    @if($currentTab === 'projects') Organizational Projects
                    @elseif($currentTab === 'staff') Team &amp; Staff Directory
                    @elseif($currentTab === 'matrix') 5 Thematic Pillars Review
                    @elseif($currentTab === 'activities') Activity Feed &amp; Reports
                    @else Dashboard &amp; Key Performance Metrics
                    @endif
                </h2>
            </div>

            <!-- Top Quick Access Links (Presentations & Live Projector) -->
            @php
                $exportQuery = array_merge(request()->query(), [
                    'project_id' => $selectedProjectId ?? 'all',
                    'interval' => $interval ?? 'all',
                    'year' => $filterParams['year'] ?? now()->year,
                    'month' => $filterParams['month'] ?? now()->month,
                    'quarter' => $filterParams['quarter'] ?? ceil(now()->month / 3),
                ]);
            @endphp
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('programmes.projector', $exportQuery) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition cursor-pointer" title="Open Fullscreen Matrix Projector">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>▶ Live Projector</span>
                </a>

                <a href="{{ route('programmes.export_consolidated_pptx', $exportQuery) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-xs transition" title="Generate Consolidated PowerPoint Deck">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>PowerPoint (.pptx)</span>
                </a>

                <a href="{{ route('programmes.export_consolidated_pdf', $exportQuery) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-slate-700 hover:bg-slate-800 rounded-xl shadow-xs transition" title="Generate Consolidated PDF Report">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>PDF (16:9)</span>
                </a>
            </div>
        </header>

        <!-- Main Body Content Canvas -->
        <div class="p-6 sm:p-8 space-y-6 max-w-7xl w-full">

            <!-- Flash Notifications -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs flex items-center justify-between shadow-xs">
                <span class="flex items-center gap-2 font-semibold">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs flex items-center justify-between shadow-xs">
                <span class="flex items-center gap-2 font-semibold">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    {{ session('error') }}
                </span>
            </div>
            @endif

            <!-- ==================== TAB 0: EXECUTIVE DASHBOARD WITH KEY METRICS ==================== -->
            @if($currentTab === 'dashboard' || !$currentTab)
            <div class="space-y-6">

                <!-- Compact Executive Scope & Filter Banner -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-base shrink-0">
                            📊
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-black text-slate-900 tracking-tight">Executive Overview</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                    @if($interval === 'month')
                                        {{ \Carbon\Carbon::create($filterParams['year'], $filterParams['month'], 1)->format('F Y') }}
                                    @elseif($interval === 'quarter')
                                        Q{{ $filterParams['quarter'] }} {{ $filterParams['year'] }}
                                    @elseif($interval === 'year')
                                        Year {{ $filterParams['year'] }}
                                    @else
                                        All Time
                                    @endif
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">High-level strategic performance &amp; review matrix overview</p>
                        </div>
                    </div>

                    <!-- Sleek Inline Scope Controls -->
                    <form action="{{ route('programmes.hub') }}" method="GET" class="flex flex-wrap items-center gap-2">
                        <input type="hidden" name="tab" value="dashboard">
                        
                        <div class="flex items-center bg-slate-100 p-1 rounded-xl">
                            @foreach(['all' => 'All', 'quarter' => 'Quarter', 'month' => 'Month', 'year' => 'Year'] as $iKey => $iLabel)
                                <button type="submit" name="interval" value="{{ $iKey }}" class="px-2.5 py-1 text-xs font-bold rounded-lg transition {{ $interval === $iKey ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                                    {{ $iLabel }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Year Selection (Compact) -->
                        <select name="year" onchange="this.form.submit()" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @php
                                $currentYear = (int) now()->year;
                                $selectedYear = (int) ($filterParams['year'] ?? $currentYear);
                            @endphp
                            @foreach(range($currentYear - 2, $currentYear + 1) as $yr)
                                <option value="{{ $yr }}" {{ $selectedYear === $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>

                        @if($interval === 'quarter')
                        <select name="quarter" onchange="this.form.submit()" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @php $selectedQuarter = (int) ($filterParams['quarter'] ?? ceil(now()->month / 3)); @endphp
                            @foreach([1 => 'Q1', 2 => 'Q2', 3 => 'Q3', 4 => 'Q4'] as $qNum => $qName)
                                <option value="{{ $qNum }}" {{ $selectedQuarter === $qNum ? 'selected' : '' }}>{{ $qName }}</option>
                            @endforeach
                        </select>
                        @elseif($interval === 'month')
                        <select name="month" onchange="this.form.submit()" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @php $selectedMonth = (int) ($filterParams['month'] ?? now()->month); @endphp
                            @foreach([1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'] as $mNum => $mName)
                                <option value="{{ $mNum }}" {{ $selectedMonth === $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                            @endforeach
                        </select>
                        @endif

                        <select name="project_id" onchange="this.form.submit()" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 max-w-[140px] truncate">
                            <option value="all" {{ (!$selectedProjectId || $selectedProjectId === 'all') ? 'selected' : '' }}>All Projects</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ $selectedProjectId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <!-- ==================== 4 SPACIOUS HERO KPI METRIC CARDS ==================== -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Metric Card 1: Active Projects -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Projects</span>
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">📁</div>
                        </div>
                        <div class="my-3">
                            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $metrics['total_projects'] }}</div>
                            <div class="text-xs text-slate-500 font-medium mt-1">
                                <span class="font-bold text-emerald-600">{{ $metrics['active_projects'] }} active</span> across hubs
                            </div>
                            <div class="text-[11px] text-slate-400 font-medium mt-0.5">Excludes {{ $metrics['total_departments'] }} parent departments</div>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'projects']) }}" class="pt-3 border-t border-slate-100 text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center justify-between">
                            <span>Manage Projects</span>
                            <span>→</span>
                        </a>
                    </div>

                    <!-- Metric Card 2: Activities Logged -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Activities Logged</span>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">📝</div>
                        </div>
                        <div class="my-3">
                            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $metrics['total_activities'] }}</div>
                            <div class="text-xs text-slate-500 font-medium mt-1">
                                Continuous entries recorded
                            </div>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'activities']) }}" class="pt-3 border-t border-slate-100 text-xs font-bold text-emerald-600 hover:text-emerald-800 flex items-center justify-between">
                            <span>Open Activity Logs</span>
                            <span>→</span>
                        </a>
                    </div>

                    <!-- Metric Card 3: Pillar Reporting Health / Coverage -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Pillar Coverage</span>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">📊</div>
                        </div>
                        <div class="my-3">
                            <div class="flex items-baseline gap-2">
                                <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $metrics['reporting_coverage_rate'] }}%</div>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $metrics['reporting_coverage_rate'] >= 80 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($metrics['reporting_coverage_rate'] >= 50 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                    @if($metrics['reporting_coverage_rate'] >= 80)
                                        Healthy
                                    @elseif($metrics['reporting_coverage_rate'] >= 50)
                                        In Progress
                                    @else
                                        Needs Update
                                    @endif
                                </span>
                            </div>
                            <div class="text-xs text-slate-500 font-medium mt-1">
                                {{ $metrics['fully_reported_projects'] }} of {{ $metrics['active_projects'] }} projects fully updated (5/5)
                            </div>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="pt-3 border-t border-slate-100 text-xs font-bold text-emerald-600 hover:text-emerald-800 flex items-center justify-between">
                            <span>Explore 5 Pillars Matrix</span>
                            <span>→</span>
                        </a>
                    </div>

                    <!-- Metric Card 4: Personnel & Officers -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Team &amp; Personnel</span>
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">👥</div>
                        </div>
                        <div class="my-3">
                            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $metrics['total_staff'] }}</div>
                            <div class="text-xs text-slate-500 font-medium mt-1">
                                {{ $metrics['total_officers'] }} Field Project Officers
                            </div>
                        </div>
                        @if($isSuperAdmin)
                        <a href="{{ route('programmes.hub', ['tab' => 'staff']) }}" class="pt-3 border-t border-slate-100 text-xs font-bold text-purple-600 hover:text-purple-800 flex items-center justify-between">
                            <span>Manage Team</span>
                            <span>→</span>
                        </a>
                        @else
                        <div class="pt-3 border-t border-slate-100 text-xs font-bold text-slate-400">
                            Field Operations Team
                        </div>
                        @endif
                    </div>
                </div>

                <!-- ==================== STREAMLINED 5 THEMATIC PILLARS SUMMARY ROW ==================== -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                5 Thematic Pillars Distribution
                            </h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">High-level overview of highlights recorded across organizational pillars</p>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <span>View Full Matrix</span>
                            <span>→</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                        <!-- Pillar 1 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-blue-50/50 hover:bg-blue-50 border border-blue-100 rounded-xl transition space-y-1 block">
                            <div class="flex items-center justify-between text-blue-700">
                                <span class="text-[10px] font-black uppercase">Pillar 1</span>
                                <span class="text-xs">🏆</span>
                            </div>
                            <div class="text-xl font-black text-slate-900">{{ $metrics['total_achievements'] }}</div>
                            <div class="text-[11px] font-bold text-slate-700 truncate">Achievements</div>
                        </a>

                        <!-- Pillar 2 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-rose-50/50 hover:bg-rose-50 border border-rose-100 rounded-xl transition space-y-1 block">
                            <div class="flex items-center justify-between text-rose-700">
                                <span class="text-[10px] font-black uppercase">Pillar 2</span>
                                <span class="text-xs">🛡️</span>
                            </div>
                            <div class="text-xl font-black text-slate-900">{{ $metrics['total_challenges'] }}</div>
                            <div class="text-[11px] font-bold text-slate-700 truncate">Challenges &amp; Risks</div>
                        </a>

                        <!-- Pillar 3 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-amber-50/50 hover:bg-amber-50 border border-amber-100 rounded-xl transition space-y-1 block">
                            <div class="flex items-center justify-between text-amber-700">
                                <span class="text-[10px] font-black uppercase">Pillar 3</span>
                                <span class="text-xs">💡</span>
                            </div>
                            <div class="text-xl font-black text-slate-900">{{ $metrics['total_learning'] }}</div>
                            <div class="text-[11px] font-bold text-slate-700 truncate">Learning &amp; Adaptation</div>
                        </a>

                        <!-- Pillar 4 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-indigo-50/50 hover:bg-indigo-50 border border-indigo-100 rounded-xl transition space-y-1 block">
                            <div class="flex items-center justify-between text-indigo-700">
                                <span class="text-[10px] font-black uppercase">Pillar 4</span>
                                <span class="text-xs">📈</span>
                            </div>
                            <div class="text-xl font-black text-slate-900">{{ $metrics['total_mne'] }}</div>
                            <div class="text-[11px] font-bold text-slate-700 truncate">Monitoring &amp; Eval</div>
                        </a>

                        <!-- Pillar 5 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-emerald-50/50 hover:bg-emerald-50 border border-emerald-100 rounded-xl transition space-y-1 block">
                            <div class="flex items-center justify-between text-emerald-700">
                                <span class="text-[10px] font-black uppercase">Pillar 5</span>
                                <span class="text-xs">🤝</span>
                            </div>
                            <div class="text-xl font-black text-slate-900">{{ $metrics['total_collab'] }}</div>
                            <div class="text-[11px] font-bold text-slate-700 truncate">Collaboration</div>
                        </a>
                    </div>
                </div>

                <!-- ==================== TWO-COLUMN SECTION: RECENT ACTIVITY & PRESENTATION HUB ==================== -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left: Recent Activity Stream (7 cols) -->
                    <div class="lg:col-span-7 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                    Recent Activity Updates
                                </h3>
                                <a href="{{ route('programmes.activities.create') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                                    + Log Activity
                                </a>
                            </div>

                            @if($metrics['latest_activities']->isEmpty())
                            <div class="py-8 text-center text-xs text-slate-400">
                                No activity logs recorded for this period.
                            </div>
                            @else
                            <div class="space-y-2.5">
                                @foreach($metrics['latest_activities'] as $item)
                                <div class="p-3 bg-slate-50 border border-slate-200/60 rounded-xl flex items-center justify-between gap-3 hover:bg-slate-100/80 transition">
                                    <div class="min-w-0 flex-1 space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase bg-blue-50 text-blue-700 border border-blue-200 rounded-md shrink-0">
                                                {{ $item->project->code ?? 'PROJECT' }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-900 truncate">{{ $item->activity_title }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-400 truncate">
                                            {{ $item->location ?: 'Location recorded' }} • Logged by {{ $item->user->name ?? 'Staff' }}
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <div class="text-[10px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($item->activity_date)->format('M d') }}</div>
                                        <a href="{{ route('programmes.activities.show', $item->token) }}" class="text-[11px] font-bold text-blue-600 hover:underline">View ↗</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400">Total activities in scope: <strong class="text-slate-700">{{ $metrics['total_activities'] }}</strong></span>
                            <a href="{{ route('programmes.hub', ['tab' => 'activities']) }}" class="font-bold text-blue-600 hover:text-blue-800">
                                View All Logs →
                            </a>
                        </div>
                    </div>

                    <!-- Right: Quick Presentation Launchers (5 cols) -->
                    <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="border-b border-slate-100 pb-3">
                                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                    Presentations &amp; Outputs
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">Quickly present or export consolidated executive materials</p>
                            </div>

                            <div class="space-y-2.5">
                                <!-- Projector -->
                                <a href="{{ route('programmes.projector', $exportQuery) }}" class="flex items-center justify-between p-3 bg-emerald-50/60 hover:bg-emerald-50 border border-emerald-200/80 rounded-xl transition">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">▶</div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-900">Live Matrix Projector</div>
                                            <div class="text-[10px] text-slate-500">16:9 Fullscreen Slide Deck</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-700">Launch →</span>
                                </a>

                                <!-- PPTX -->
                                <a href="{{ route('programmes.export_consolidated_pptx', $exportQuery) }}" class="flex items-center justify-between p-3 bg-amber-50/60 hover:bg-amber-50 border border-amber-200/80 rounded-xl transition">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-amber-600 text-white flex items-center justify-center font-bold text-xs">📊</div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-900">PowerPoint Deck</div>
                                            <div class="text-[10px] text-slate-500">Editable .pptx presentation</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-amber-700">Download →</span>
                                </a>

                                <!-- PDF -->
                                <a href="{{ route('programmes.export_consolidated_pdf', $exportQuery) }}" class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-slate-700 text-white flex items-center justify-center font-bold text-xs">📄</div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-900">Executive PDF Brief</div>
                                            <div class="text-[10px] text-slate-500">Print-ready 16:9 document</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">Export →</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 text-center">
                            <a href="{{ route('programmes.activities.create') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition active:scale-95">
                                <span>+ Log New Activity Entry</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            @endif

            <!-- ==================== TAB 1: CONTINUOUS ACTIVITY LOGS ==================== -->
            @if($currentTab === 'activities')
            <div class="space-y-6">
                <!-- Robust Period & Project Filter Toolbar -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-4">
                    <form action="{{ route('programmes.hub') }}" method="GET" id="activity-filter-form" class="space-y-4">
                        <input type="hidden" name="tab" value="activities">

                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 pb-3 border-b border-slate-100">
                            <!-- Aggregation Mode Selector (Pills) -->
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1">Period:</span>
                                @php
                                    $intervals = [
                                        'all' => 'All Time',
                                        'month' => 'Monthly',
                                        'quarter' => 'Quarterly',
                                        'year' => 'Yearly',
                                    ];
                                @endphp
                                @foreach($intervals as $iKey => $iLabel)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="interval" value="{{ $iKey }}" {{ $interval === $iKey ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                                        <span class="inline-block px-3.5 py-1.5 text-xs font-bold rounded-xl transition select-none {{ $interval === $iKey ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                                            {{ $iLabel }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Current Filter Status Badge -->
                            <div class="text-xs text-slate-500 font-medium flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Filtering: 
                                    <strong class="text-slate-800 font-bold">
                                        @if($interval === 'month')
                                            {{ \Carbon\Carbon::create($filterParams['year'], $filterParams['month'], 1)->format('F Y') }}
                                        @elseif($interval === 'quarter')
                                            Quarter {{ $filterParams['quarter'] }} ({{ $filterParams['year'] }})
                                        @elseif($interval === 'year')
                                            Year {{ $filterParams['year'] }}
                                        @else
                                            All Time Activities
                                        @endif
                                    </strong>
                                </span>
                            </div>
                        </div>

                        <!-- Dropdown Selectors Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
                            <!-- 1. Year Dropdown Selector -->
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Year</label>
                                <select name="year" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @php
                                        $currentYear = (int) now()->year;
                                        $selectedYear = (int) ($filterParams['year'] ?? $currentYear);
                                        $yearRange = range($currentYear - 2, $currentYear + 2);
                                    @endphp
                                    @foreach($yearRange as $yr)
                                        <option value="{{ $yr }}" {{ $selectedYear === $yr ? 'selected' : '' }}>
                                            Year {{ $yr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 2. Dynamic Period Selector (Month or Quarter) -->
                            @if($interval === 'month')
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Month</label>
                                <select name="month" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @php
                                        $selectedMonth = (int) ($filterParams['month'] ?? now()->month);
                                        $months = [
                                            1 => 'January', 2 => 'February', 3 => 'March',
                                            4 => 'April', 5 => 'May', 6 => 'June',
                                            7 => 'July', 8 => 'August', 9 => 'September',
                                            10 => 'October', 11 => 'November', 12 => 'December'
                                        ];
                                    @endphp
                                    @foreach($months as $mNum => $mName)
                                        <option value="{{ $mNum }}" {{ $selectedMonth === $mNum ? 'selected' : '' }}>
                                            {{ $mName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @elseif($interval === 'quarter')
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Quarter</label>
                                <select name="quarter" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @php
                                        $selectedQuarter = (int) ($filterParams['quarter'] ?? ceil(now()->month / 3));
                                        $quarters = [
                                            1 => 'Q1 (Jan – Mar)',
                                            2 => 'Q2 (Apr – Jun)',
                                            3 => 'Q3 (Jul – Sep)',
                                            4 => 'Q4 (Oct – Dec)',
                                        ];
                                    @endphp
                                    @foreach($quarters as $qNum => $qName)
                                        <option value="{{ $qNum }}" {{ $selectedQuarter === $qNum ? 'selected' : '' }}>
                                            {{ $qName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Scope</label>
                                <div class="text-xs font-semibold text-slate-600 bg-slate-100/80 border border-slate-200 rounded-xl px-3 py-2">
                                    {{ $interval === 'year' ? 'Entire Calendar Year' : 'All Historical Entries' }}
                                </div>
                            </div>
                            @endif

                            <!-- 3. Project Filter Dropdown -->
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Project / Department</label>
                                <select name="project_id" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="all" {{ (!$selectedProjectId || $selectedProjectId === 'all') ? 'selected' : '' }}>
                                        All Projects ({{ $projects->where('is_department', false)->count() }})
                                    </option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" class="font-bold text-slate-900 bg-slate-100" {{ $selectedProjectId == $dept->id ? 'selected' : '' }}>
                                            🏢 {{ $dept->name }} {{ $dept->is_department ? '(Department / All Sub-Projects)' : '' }}
                                        </option>
                                        @foreach($dept->children as $child)
                                            <option value="{{ $child->id }}" {{ $selectedProjectId == $child->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                    @php
                                        $deptAndChildIds = $departments->pluck('id')->merge($departments->flatMap->children->pluck('id'))->unique();
                                        $standaloneProjects = $projects->whereNotIn('id', $deptAndChildIds);
                                    @endphp
                                    @if($standaloneProjects->isNotEmpty())
                                        @foreach($standaloneProjects as $sp)
                                            <option value="{{ $sp->id }}" {{ $selectedProjectId == $sp->id ? 'selected' : '' }}>
                                                {{ $sp->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- 4. Search Field & Reset -->
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Search Keyword</label>
                                <div class="relative flex items-center">
                                    <input type="text" name="q" value="{{ $search }}" placeholder="Search title or bullet..." class="w-full text-xs rounded-xl bg-slate-50 border border-slate-300 pl-8 pr-8 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    @if($search || $selectedProjectId || $interval !== 'all')
                                        <a href="{{ route('programmes.hub', ['tab' => 'activities']) }}" class="absolute right-2 text-xs font-black text-rose-500 hover:text-rose-700" title="Reset Filters">✕</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Activity Feed Table -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                                Continuous Activity Logs ({{ $activities->total() }})
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Showing entries filtered by: <strong>{{ ucfirst($interval) }}</strong></p>
                        </div>

                        <a href="{{ route('programmes.activities.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-xs">
                            + Log New Activity
                        </a>
                    </div>

                    @if($activities->isEmpty())
                    <div class="text-center py-12 border border-dashed border-slate-200 rounded-2xl space-y-3 bg-slate-50/50">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-lg font-bold">
                            📝
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">No activity logs found</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            No entries logged matching the selected date interval or project. Click below to record a new activity.
                        </p>
                        <a href="{{ route('programmes.activities.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-xs">
                            + Log First Activity
                        </a>
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-slate-700 font-bold uppercase tracking-wider text-[11px]">
                                    <th class="py-3 px-3.5">Period / Date</th>
                                    <th class="py-3 px-3.5">Project / Dept</th>
                                    <th class="py-3 px-3.5">Activity Title &amp; Location</th>
                                    <th class="py-3 px-3.5">Key Highlights (Bullets)</th>
                                    <th class="py-3 px-3.5">Logged By</th>
                                    <th class="py-3 px-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($activities as $act)
                                @php
                                    $pts = $act->getPoints('achievements_points');
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3 px-3.5 whitespace-nowrap">
                                        <div class="font-semibold text-slate-900">
                                            {{ $act->formatted_period }}
                                        </div>
                                        <div class="mt-1">
                                            @if($act->activity_type === 'training')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200">
                                                    🎓 Training
                                                </span>
                                            @elseif($act->activity_type === 'class')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                    📚 Class
                                                </span>
                                            @elseif($act->activity_type === 'session')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                                    ⏱ Session
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    ⚡ Activity
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-3.5">
                                        <div class="flex flex-col gap-0.5">
                                            @if($act->project && $act->project->parent)
                                                <span class="text-[10px] text-purple-700 font-bold flex items-center gap-1">
                                                    <span>🏢</span> {{ $act->project->parent->name }}
                                                </span>
                                                <span class="px-2 py-0.5 text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 rounded-md whitespace-nowrap w-fit">
                                                    ↳ {{ $act->project->name }}
                                                </span>
                                            @elseif($act->project && $act->project->is_department)
                                                <span class="px-2 py-0.5 text-[11px] font-black bg-purple-50 text-purple-700 border border-purple-200 rounded-md whitespace-nowrap w-fit">
                                                    🏢 {{ $act->project->name }}
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 rounded-md whitespace-nowrap w-fit">
                                                    {{ $act->project->name ?? 'Unassigned' }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-3.5">
                                        <a href="{{ route('programmes.activities.show', $act->token) }}" class="font-bold text-slate-900 hover:text-blue-600 transition block">
                                            {{ $act->activity_title }}
                                        </a>
                                        @if($act->venue)
                                            <div class="text-[11px] text-slate-500 font-medium flex items-center gap-1 mt-0.5">
                                                <span class="text-slate-400">📍 {{ $act->venue_or_location_label }}:</span>
                                                <span>{{ $act->venue }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3.5 text-slate-600 max-w-xs truncate">
                                        @if(!empty($pts))
                                            <span class="font-medium text-slate-800">{{ $pts[0] }}</span>
                                        @else
                                            <span class="text-slate-400 italic">No bullet points</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3.5 text-slate-600 whitespace-nowrap font-medium">
                                        {{ $act->user->name ?? 'Project Officer' }}
                                    </td>
                                    <td class="py-3 px-3.5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('programmes.activities.show', $act->token) }}" class="p-1.5 text-slate-500 hover:text-blue-600 font-bold rounded-lg hover:bg-slate-100 transition" title="View Details">
                                                👁️
                                            </a>
                                            <a href="{{ route('programmes.activities.edit', $act->token) }}" class="p-1.5 text-slate-500 hover:text-slate-900 font-bold rounded-lg hover:bg-slate-100 transition" title="Edit">
                                                ✏️
                                            </a>
                                            <form action="{{ route('programmes.activities.destroy', $act->token) }}" method="POST" class="inline" onsubmit="return confirm('Delete this activity entry?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 font-bold rounded-lg hover:bg-rose-50 transition cursor-pointer" title="Delete">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        {{ $activities->links() }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- ==================== TAB 2: PROJECTS DIRECTORY & EXPORTS ==================== -->
            @if($currentTab === 'projects')
            @php
                $operationalProjects = $projects->where('is_department', false);
                $childProjectsCount = $operationalProjects->whereNotNull('parent_id')->count();
                $standaloneProjectsCount = $operationalProjects->whereNull('parent_id')->count();
            @endphp
            <div class="space-y-6">
                <!-- Projects Header Toolbar -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">
                                Operational Projects Directory ({{ $operationalProjects->count() }})
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Manage field initiatives, child sub-projects, and standalone development programs.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'departments'])) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-xl transition">
                            <span>🏢 Parent Projects</span>
                            <span class="px-1.5 py-0.2 rounded-full bg-purple-200 text-purple-800 text-[10px] font-black">{{ $departments->count() }}</span>
                        </a>

                        @if($isSuperAdmin)
                        <button type="button" onclick="document.getElementById('create-project-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>+ Create New Project</span>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Interactive Hierarchy Filter Pills -->
                <div class="flex flex-wrap items-center gap-2" id="project-filter-pills">
                    <button type="button" onclick="filterProjectsList('all')" id="pill-all" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-900 text-white shadow-2xs transition cursor-pointer">
                        All Projects ({{ $operationalProjects->count() }})
                    </button>
                    <button type="button" onclick="filterProjectsList('child')" id="pill-child" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-purple-700 border border-purple-200 hover:bg-purple-50 transition cursor-pointer flex items-center gap-1.5">
                        <span>↳ Child Sub-Projects ({{ $childProjectsCount }})</span>
                    </button>
                    <button type="button" onclick="filterProjectsList('standalone')" id="pill-standalone" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-blue-700 border border-blue-200 hover:bg-blue-50 transition cursor-pointer flex items-center gap-1.5">
                        <span>📁 Standalone Projects ({{ $standaloneProjectsCount }})</span>
                    </button>
                    @if($projects->where('is_department', true)->count() > 0)
                    <button type="button" onclick="filterProjectsList('department')" id="pill-department" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-indigo-700 border border-indigo-200 hover:bg-indigo-50 transition cursor-pointer flex items-center gap-1.5">
                        <span>🏢 Parent Departments ({{ $projects->where('is_department', true)->count() }})</span>
                    </button>
                    @endif
                </div>

                <!-- Projects Grid with Distinct Differentiated Card Displays -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="projects-grid-container">
                    @foreach($projects as $p)
                        @php
                            $isChild = !empty($p->parent_id) && !$p->is_department;
                            $isDept = (bool) $p->is_department;
                            $isStandalone = empty($p->parent_id) && !$p->is_department;
                            $cardType = $isDept ? 'department' : ($isChild ? 'child' : 'standalone');
                        @endphp

                        @if($isDept)
                        <!-- ==================== PARENT DEPARTMENT CARD ==================== -->
                        <div class="project-card bg-linear-to-br from-white via-white to-purple-50/25 border-2 border-purple-300 hover:border-purple-400 rounded-2xl p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-4" data-card-type="department">
                            <div class="space-y-3">
                                <!-- Top Badges -->
                                <div class="flex items-start justify-between gap-2 flex-wrap">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-purple-600 text-white rounded-md shadow-2xs flex items-center gap-1">
                                            <span>🏢</span> PARENT DEPT
                                        </span>
                                        <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200 rounded-md">
                                            {{ $p->code ?: 'DEPT' }}
                                        </span>
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 rounded-md">
                                            📁 {{ $p->children->count() }} Sub-Projects
                                        </span>
                                    </div>
                                    <span class="text-[11px] font-bold {{ $p->status === 'active' ? 'text-emerald-600' : 'text-slate-400' }}">
                                        ● {{ ucfirst($p->status) }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-black text-slate-900 leading-snug">{{ $p->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $p->description ?: 'Umbrella department coordinating thematic projects and shared resources.' }}</p>
                                </div>

                                <!-- Sub-Projects Chips -->
                                @if($p->children->isNotEmpty())
                                    <div class="bg-purple-50/60 border border-purple-100 rounded-xl p-2.5 space-y-1.5">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-purple-700 block">Sub-Projects ({{ $p->children->count() }}):</span>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($p->children as $child)
                                                <span class="px-2 py-0.5 bg-white border border-purple-200 rounded-md text-[10px] font-bold text-purple-900 shadow-2xs">
                                                    ↳ {{ $child->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="pt-2 text-[11px] text-slate-600 space-y-1.5 border-t border-purple-100">
                                    <div class="flex items-center gap-1.5 font-medium">
                                        <span class="text-slate-400">Team:</span>
                                        <strong class="text-slate-800">{{ $p->assigned_team_summary }}</strong>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-500">
                                        <span>📍 {{ $p->location ?: 'Livingstone' }}</span>
                                        <span class="font-bold text-purple-700">{{ $p->totalDescendantActivitiesCount() }} activities (all sub-projects)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Presentation & Export Actions -->
                            <div class="pt-3 border-t border-purple-100 flex flex-wrap items-center justify-between gap-1.5">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('programmes.projects.projector', $p->id) }}" class="px-2.5 py-1 text-xs font-black text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition shadow-2xs" title="Project this department with all sub-projects">
                                        ▶ Project Dept
                                    </a>
                                    <a href="{{ route('programmes.projects.export_pptx', $p->id) }}" class="px-2 py-1 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition" title="Download Department PPTX">
                                        PPTX
                                    </a>
                                    <a href="{{ route('programmes.projects.export_pdf', $p->id) }}" class="px-2 py-1 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition" title="Download Department PDF">
                                        PDF
                                    </a>
                                </div>

                                @if($isSuperAdmin)
                                <div class="flex items-center gap-1">
                                    <button type="button" onclick="openEditProjectModal({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->code) }}', '{{ addslashes($p->location) }}', '{{ addslashes($p->description) }}', '{{ $p->status }}', {{ json_encode($p->users->pluck('id')) }}, {{ $p->parent_id ? $p->parent_id : 'null' }}, 1, null)" class="p-1.5 text-xs text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg cursor-pointer" title="Edit Department">
                                        ⚙️
                                    </button>
                                    <form action="{{ route('programmes.projects.toggle_status', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-xs {{ $p->status === 'active' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} border border-slate-200 rounded-lg cursor-pointer" title="{{ $p->status === 'active' ? 'Archive Department' : 'Activate Department' }}">
                                            {{ $p->status === 'active' ? '📁' : '⚡' }}
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>

                        @elseif($isChild)
                        <!-- ==================== CHILD SUB-PROJECT CARD ==================== -->
                        <div class="project-card bg-white border-l-4 border-l-purple-500 border-t border-r border-b border-slate-200/90 rounded-2xl p-5 shadow-xs hover:border-l-purple-600 hover:shadow-md transition flex flex-col justify-between space-y-4" data-card-type="child">
                            <div class="space-y-3">
                                <!-- Top Hierarchy Header -->
                                <div class="space-y-1.5">
                                    <div class="flex items-start justify-between gap-2 flex-wrap">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200 rounded-md flex items-center gap-1">
                                                <span>↳</span> Sub-Project
                                            </span>
                                            <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 rounded-md">
                                                {{ $p->code ?: 'CHILD' }}
                                            </span>
                                        </div>
                                        <span class="text-[11px] font-bold {{ $p->status === 'active' ? 'text-emerald-600' : 'text-slate-400' }}">
                                            ● {{ ucfirst($p->status) }}
                                        </span>
                                    </div>

                                    <!-- Parent Department Banner -->
                                    <div class="px-2.5 py-1 text-[11px] font-black bg-purple-50 text-purple-900 border border-purple-200/80 rounded-lg flex items-center gap-1.5 truncate" title="Sub-project under parent department {{ $p->parent->name }}">
                                        <span class="text-xs">🏢</span>
                                        <span class="text-purple-700 font-bold truncate">{{ $p->parent->name }}</span>
                                        <span class="text-purple-400">↳</span>
                                        <span class="text-purple-950 font-black truncate">{{ $p->name }}</span>
                                    </div>

                                    @if($p->isLinked())
                                        @php
                                            $linkedSiblings = $p->linkedSiblingProjects()->where('id', '!=', $p->id);
                                        @endphp
                                        <div class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-amber-50 text-amber-900 border border-amber-200 rounded-lg flex items-center gap-1" title="Linked with {{ $linkedSiblings->pluck('name')->implode(', ') }}">
                                            <span>🔗</span>
                                            <span>Linked Slide: {{ $linkedSiblings->pluck('name')->implode(', ') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $p->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $p->description ?: 'Community development and youth empowerment programming.' }}</p>
                                </div>
                                
                                <div class="pt-2 text-[11px] text-slate-600 space-y-1.5 border-t border-slate-100">
                                    <div class="flex items-center gap-1.5 font-medium">
                                        <span class="text-slate-400">Team:</span>
                                        <strong class="text-slate-800">{{ $p->assigned_team_summary }}</strong>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-500">
                                        <span>📍 {{ $p->location ?: 'Livingstone, Zambia' }}</span>
                                        <span class="font-bold text-slate-700">{{ $p->activityEntries->count() }} activities</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Presentation & Export Actions -->
                            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-1.5">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('programmes.projects.projector', $p->id) }}" class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition" title="Project this specific sub-project">
                                        ▶ Project
                                    </a>
                                    <a href="{{ route('programmes.projects.export_pptx', $p->id) }}" class="px-2 py-1 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition" title="Download Isolated PPTX">
                                        PPTX
                                    </a>
                                    <a href="{{ route('programmes.projects.export_pdf', $p->id) }}" class="px-2 py-1 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition" title="Download Isolated PDF">
                                        PDF
                                    </a>
                                </div>

                                @if($isSuperAdmin)
                                <div class="flex items-center gap-1">
                                    <button type="button" onclick="openEditProjectModal({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->code) }}', '{{ addslashes($p->location) }}', '{{ addslashes($p->description) }}', '{{ $p->status }}', {{ json_encode($p->users->pluck('id')) }}, {{ $p->parent_id ? $p->parent_id : 'null' }}, 0, {{ $p->isLinked() ? ($p->linkedSiblingProjects()->where('id', '!=', $p->id)->first()?->id ?? 'null') : 'null' }})" class="p-1.5 text-xs text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg cursor-pointer" title="Edit Child Project">
                                        ⚙️
                                    </button>
                                    <form action="{{ route('programmes.projects.toggle_status', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-xs {{ $p->status === 'active' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} border border-slate-200 rounded-lg cursor-pointer" title="{{ $p->status === 'active' ? 'Archive Project' : 'Activate Project' }}">
                                            {{ $p->status === 'active' ? '📁' : '⚡' }}
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>

                        @else
                        <!-- ==================== STANDALONE PROJECT CARD ==================== -->
                        <div class="project-card bg-white border-l-4 border-l-blue-500 border-t border-r border-b border-slate-200/90 rounded-2xl p-5 shadow-xs hover:border-l-blue-600 hover:shadow-md transition flex flex-col justify-between space-y-4" data-card-type="standalone">
                            <div class="space-y-3">
                                <!-- Top Badges -->
                                <div class="flex items-start justify-between gap-2 flex-wrap">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 rounded-md flex items-center gap-1">
                                            <span>📁</span> Standalone Project
                                        </span>
                                        <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 rounded-md">
                                            {{ $p->code ?: 'PROJECT' }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] font-bold {{ $p->status === 'active' ? 'text-emerald-600' : 'text-slate-400' }}">
                                        ● {{ ucfirst($p->status) }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $p->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $p->description ?: 'Community development and youth empowerment programming.' }}</p>
                                </div>
                                
                                <div class="pt-2 text-[11px] text-slate-600 space-y-1.5 border-t border-slate-100">
                                    <div class="flex items-center gap-1.5 font-medium">
                                        <span class="text-slate-400">Team:</span>
                                        <strong class="text-slate-800">{{ $p->assigned_team_summary }}</strong>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-500">
                                        <span>📍 {{ $p->location ?: 'Livingstone, Zambia' }}</span>
                                        <span class="font-bold text-slate-700">{{ $p->activityEntries->count() }} activities</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Presentation & Export Actions -->
                            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-1.5">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('programmes.projects.projector', $p->id) }}" class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition" title="Project this specific project directly">
                                        ▶ Project
                                    </a>
                                    <a href="{{ route('programmes.projects.export_pptx', $p->id) }}" class="px-2 py-1 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition" title="Download Isolated PPTX">
                                        PPTX
                                    </a>
                                    <a href="{{ route('programmes.projects.export_pdf', $p->id) }}" class="px-2 py-1 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition" title="Download Isolated PDF">
                                        PDF
                                    </a>
                                </div>

                                @if($isSuperAdmin)
                                <div class="flex items-center gap-1">
                                    <button type="button" onclick="openEditProjectModal({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->code) }}', '{{ addslashes($p->location) }}', '{{ addslashes($p->description) }}', '{{ $p->status }}', {{ json_encode($p->users->pluck('id')) }}, null, 0, null)" class="p-1.5 text-xs text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg cursor-pointer" title="Edit Project">
                                        ⚙️
                                    </button>
                                    <form action="{{ route('programmes.projects.toggle_status', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-xs {{ $p->status === 'active' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} border border-slate-200 rounded-lg cursor-pointer" title="{{ $p->status === 'active' ? 'Archive Project' : 'Activate Project' }}">
                                            {{ $p->status === 'active' ? '📁' : '⚡' }}
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- ==================== TAB: PARENT PROJECTS / DEPARTMENTS DIRECTORY ==================== -->
            @if($currentTab === 'departments')
            <div class="space-y-6">
                <!-- Parent Projects Header Toolbar -->
                <div class="bg-linear-to-r from-purple-900 via-indigo-900 to-purple-800 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="space-y-2 max-w-2xl relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/30 text-purple-200 border border-purple-400/30 text-xs font-bold uppercase tracking-wider backdrop-blur-xs">
                            <span>🏢</span>
                            <span>Strategic Departments &amp; Umbrella Areas</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Parent Projects Directory ({{ $departments->count() }})</h2>
                        <p class="text-xs sm:text-sm text-purple-200/90 leading-relaxed">
                            Parent departments act as program umbrellas coordinating operational sub-projects. Departments group related initiatives, pool technical resources, and generate aggregated multi-project presentations.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 relative z-10">
                        @if($isSuperAdmin)
                        <button type="button" onclick="openCreateDepartmentModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-purple-950 bg-white hover:bg-purple-50 rounded-xl shadow-md transition active:scale-95 cursor-pointer">
                            <span class="text-sm">🏢</span>
                            <span>+ Create Parent Department</span>
                        </button>
                        @endif
                        <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'projects'])) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-purple-700/60 hover:bg-purple-700/80 border border-purple-400/40 rounded-xl transition">
                            <span>View Child Projects ({{ $projects->where('is_department', false)->count() }})</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>

                <!-- Departments Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @forelse($departments as $dept)
                    <div class="bg-white border-2 border-purple-200/80 hover:border-purple-400 rounded-3xl p-6 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-5 bg-linear-to-br from-white via-white to-purple-50/20">
                        <div class="space-y-4">
                            <!-- Top Card Header -->
                            <div class="flex items-start justify-between gap-3 flex-wrap">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider bg-purple-600 text-white rounded-lg shadow-2xs flex items-center gap-1.5">
                                        <span>🏢</span>
                                        <span>PARENT DEPARTMENT</span>
                                    </span>
                                    <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200 rounded-lg">
                                        {{ $dept->code ?: 'DEPT' }}
                                    </span>
                                    <span class="px-2.5 py-1 text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 rounded-lg">
                                        📁 {{ $dept->children->count() }} Sub-{{ Str::plural('Project', $dept->children->count()) }}
                                    </span>
                                </div>
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $dept->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                    ● {{ ucfirst($dept->status) }}
                                </span>
                            </div>

                            <!-- Department Title & Details -->
                            <div>
                                <h3 class="text-lg font-black text-slate-900 leading-snug tracking-tight">{{ $dept->name }}</h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $dept->description ?: 'Umbrella department coordinating thematic projects, partner linkages, and reporting.' }}
                                </p>
                            </div>

                            <!-- Sub-Projects Listing Box -->
                            <div class="bg-purple-50/50 border border-purple-100/80 rounded-2xl p-4 space-y-3">
                                <div class="flex items-center justify-between text-xs font-bold text-purple-900">
                                    <span class="flex items-center gap-1.5 uppercase text-[10px] tracking-wider text-purple-700">
                                        <span>↳</span>
                                        <span>Operational Child Projects</span>
                                    </span>
                                    <span class="text-[11px] font-semibold text-purple-600">
                                        {{ $dept->children->count() }} registered
                                    </span>
                                </div>

                                @if($dept->children->isNotEmpty())
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($dept->children as $child)
                                    <div class="bg-white border border-purple-100 rounded-xl p-3 shadow-2xs hover:border-purple-300 transition space-y-1.5">
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="text-xs font-bold text-slate-900 truncate">
                                                {{ $child->name }}
                                            </span>
                                            <span class="text-[10px] font-black {{ $child->status === 'active' ? 'text-emerald-600' : 'text-slate-400' }}">
                                                ●
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-[10px] text-slate-500">
                                            <span>{{ $child->activityEntries->count() }} activities</span>
                                            @if($child->isLinked())
                                                @php
                                                    $sib = $child->linkedSiblingProjects()->where('id', '!=', $child->id)->first();
                                                @endphp
                                                <span class="px-1.5 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 rounded font-bold" title="Linked with {{ $sib?->name }}">
                                                    🔗 Linked
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-xs text-purple-400 italic py-2 text-center bg-white/60 rounded-xl border border-dashed border-purple-200">
                                    No child projects assigned yet. Edit this department or set parent when creating a project.
                                </div>
                                @endif
                            </div>

                            <!-- Department Meta Stats -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-1 text-xs">
                                <div class="bg-white border border-slate-200/80 rounded-xl p-3">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Activities</span>
                                    <span class="text-base font-black text-slate-900 mt-0.5 block">
                                        {{ $dept->totalDescendantActivitiesCount() }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">across sub-projects</span>
                                </div>

                                <div class="bg-white border border-slate-200/80 rounded-xl p-3">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Location Hub</span>
                                    <span class="text-xs font-bold text-slate-800 mt-1 block truncate">
                                        📍 {{ $dept->location ?: 'Livingstone' }}
                                    </span>
                                </div>

                                <div class="bg-white border border-slate-200/80 rounded-xl p-3 col-span-2 sm:col-span-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Staff / Oversight</span>
                                    <span class="text-xs font-bold text-purple-700 mt-1 block truncate" title="{{ $dept->assigned_team_summary }}">
                                        {{ $dept->assigned_team_summary }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Department Presentation & Actions Toolbar -->
                        <div class="pt-4 border-t border-purple-100 flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <a href="{{ route('programmes.projects.projector', $dept->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-black text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-xs transition active:scale-95" title="Present all child projects under this department together">
                                    <span>▶ Project Department Deck</span>
                                </a>
                                <a href="{{ route('programmes.projects.export_pptx', $dept->id) }}" class="px-2.5 py-1.5 text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-xl transition" title="Download Department PPTX">
                                    PPTX
                                </a>
                                <a href="{{ route('programmes.projects.export_pdf', $dept->id) }}" class="px-2.5 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition" title="Download Department PDF">
                                    PDF
                                </a>
                            </div>

                            @if($isSuperAdmin)
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="openEditProjectModal({{ $dept->id }}, '{{ addslashes($dept->name) }}', '{{ addslashes($dept->code) }}', '{{ addslashes($dept->location) }}', '{{ addslashes($dept->description) }}', '{{ $dept->status }}', {{ json_encode($dept->users->pluck('id')) }}, {{ $dept->parent_id ? $dept->parent_id : 'null' }}, 1, null)" class="px-2.5 py-1.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition cursor-pointer" title="Edit Department Details">
                                    ⚙️ Edit
                                </button>
                                <form action="{{ route('programmes.projects.toggle_status', $dept->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-xs {{ $dept->status === 'active' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} border border-slate-200 rounded-xl cursor-pointer" title="{{ $dept->status === 'active' ? 'Archive Department' : 'Activate Department' }}">
                                        {{ $dept->status === 'active' ? '📁' : '⚡' }}
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full bg-white border border-slate-200 rounded-3xl p-12 text-center space-y-4">
                        <div class="text-4xl">🏢</div>
                        <h3 class="text-base font-bold text-slate-800">No Parent Departments Configured Yet</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">Create a parent department (e.g. Digital Skills, Education) and assign sub-projects under it to coordinate programming and aggregated reviews.</p>
                        @if($isSuperAdmin)
                        <button type="button" onclick="openCreateDepartmentModal()" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-xs transition cursor-pointer">
                            <span>+ Create First Parent Department</span>
                        </button>
                        @endif
                    </div>
                    @endforelse
                </div>
            </div>
            @endif

            <!-- ==================== TAB 3: TEAM & STAFF MANAGEMENT (SUPER ADMIN) ==================== -->
            @if($currentTab === 'staff')
            <div class="space-y-6">
                <!-- Staff Management Header -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200">
                                👑 System Admin & MEAL Officer
                            </span>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight mt-1">Team &amp; Personnel Directory</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Provision staff accounts, assign project scopes, and configure operational permissions.</p>
                    </div>

                    <button type="button" onclick="document.getElementById('create-user-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>+ Provision Staff Account</span>
                    </button>
                </div>

                <!-- Metrics Overview -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 space-y-2 shadow-xs">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Registered</span>
                        <div class="text-3xl font-black text-slate-900">{{ $allUsers->count() }}</div>
                        <div class="text-[11px] text-slate-500">Active personnel accounts</div>
                    </div>

                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 space-y-2 shadow-xs">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Project Officers</span>
                        <div class="text-3xl font-black text-slate-900">{{ $allUsers->where('role', 'project_officer')->count() }}</div>
                        <div class="text-[11px] text-slate-500">Assigned to active field projects</div>
                    </div>

                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 space-y-2 shadow-xs">
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-600">System Admins</span>
                        <div class="text-3xl font-black text-slate-900">{{ $allUsers->where('role', 'super_admin')->count() }}</div>
                        <div class="text-[11px] text-slate-500">Full system &amp; org privileges</div>
                    </div>
                </div>

                <!-- Staff Table -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-slate-700 font-bold uppercase tracking-wider text-[11px]">
                                    <th class="py-3 px-4">Staff Member</th>
                                    <th class="py-3 px-4">Role</th>
                                    <th class="py-3 px-4">Assigned Projects</th>
                                    <th class="py-3 px-4">Date Added</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($allUsers as $staff)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3.5 px-4 font-bold text-slate-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 font-black text-xs flex items-center justify-center border border-indigo-200 shrink-0">
                                                {{ strtoupper(substr($staff->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-slate-900 font-bold">{{ $staff->name }}</div>
                                                <div class="text-[11px] text-slate-500 font-normal">{{ $staff->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $staff->isSuperAdmin() ? 'bg-purple-100 text-purple-800 border border-purple-200' : ($staff->isMealOfficer() ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($staff->role === 'project_officer' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-amber-100 text-amber-800 border border-amber-200')) }}">
                                            {{ $staff->role_label }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($staff->hasAdminAccess())
                                            <span class="text-purple-700 font-semibold text-[11px]">★ Global Access (All Projects)</span>
                                        @elseif($staff->projects->isNotEmpty())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($staff->projects as $p)
                                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 text-[10px] font-bold border border-slate-200">
                                                        {{ $p->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">No projects assigned</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap text-[11px]">
                                        {{ $staff->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            <!-- Edit Staff -->
                                            <button type="button" onclick="openEditUserModal({{ $staff->id }}, '{{ addslashes($staff->name) }}', '{{ addslashes($staff->email) }}', '{{ $staff->role }}', {{ json_encode($staff->projects->pluck('id')) }})" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition cursor-pointer" title="Edit Staff Member">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            @if(Auth::id() !== $staff->id)
                                            <form action="{{ route('programmes.users.destroy', $staff->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete staff account for {{ addslashes($staff->name) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Delete Account">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                            @else
                                            <span class="text-[10px] text-slate-400 font-medium italic">Active</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- ==================== TAB 4: 5 THEMATIC PILLARS MATRIX ==================== -->
            @if($currentTab === 'matrix')
            <div class="space-y-6">
                <!-- Matrix Banner -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-black text-slate-900 tracking-tight">5 Thematic Reporting Pillars</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Overview of the organizational pillars and their 15 discrete operational focus areas.</p>
                    </div>

                    <a href="{{ route('programmes.projector', $exportQuery) }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition cursor-pointer">
                        <span>▶ Launch Matrix Projector View</span>
                    </a>
                </div>

                <!-- 5 Pillars Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($slidesConfig as $key => $pillar)
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 rounded-md">
                                Pillar {{ $pillar['number'] }}
                            </span>
                        </div>
                        <h4 class="text-sm font-black text-slate-900">{{ $pillar['title'] }}</h4>
                        
                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            @foreach($pillar['items'] as $itemKey => $item)
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/60 space-y-1">
                                <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    <span>{{ $item['title'] }}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-relaxed">{{ $item['prompt'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </main>
</div>

<!-- ==================== SUPER ADMIN MODALS ==================== -->
@if($isSuperAdmin)
<!-- Create Project Modal -->
<div id="create-project-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                Create New Project
            </h3>
            <button type="button" onclick="document.getElementById('create-project-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm cursor-pointer">✕</button>
        </div>

        <form action="{{ route('programmes.projects.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Project Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Disability Sports &amp; Inclusion" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <!-- Department & Hierarchy Settings -->
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="create-p-is-department" name="is_department" value="1" class="rounded text-purple-600 focus:ring-purple-500">
                    <label for="create-p-is-department" class="text-xs font-bold text-slate-800 cursor-pointer">
                        🏢 Acts as a Department / Program Area
                    </label>
                </div>
                <p class="text-[11px] text-slate-500 pl-6">
                    Check this if this initiative groups other sub-projects (e.g. <em>Digital Skills</em> containing Ehub, Going Beyond, Secondary School).
                </p>

                <div class="pt-2 border-t border-slate-200">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Parent Department / Area (Optional)</label>
                    <select name="parent_id" id="create-p-parent-id" onchange="filterSiblingOptions('create-p-parent-id', 'create-p-linked-project-id')" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 bg-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">-- Standalone / Top-Level Project --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">
                                🏢 {{ $dept->name }} {{ $dept->is_department ? '(Department)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">If this project belongs under a Department, select it here.</p>
                </div>

                <div id="create-p-link-group" class="pt-2 border-t border-slate-200">
                    <label class="block text-xs font-bold text-slate-700 mb-1">🔗 Link with Sibling Project (Combined Slide)</label>
                    <select name="linked_project_id" id="create-p-linked-project-id" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 bg-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">-- Standalone Slide (Not Linked) --</option>
                        @foreach($departments as $dept)
                            @foreach($dept->children as $child)
                                <option value="{{ $child->id }}" data-parent="{{ $dept->id }}">
                                    {{ $dept->name }} ↳ {{ $child->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">If this child project shares a combined slide under the parent (e.g. <em>Education (Literacy &amp; After Class)</em>), select its sibling project.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Project Code</label>
                    <input type="text" name="code" placeholder="e.g. DSI" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Location</label>
                    <input type="text" name="location" placeholder="e.g. Livingstone" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="Brief summary of project scope..." class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Assign Project Officers &amp; Assistants</label>
                <div class="max-h-36 overflow-y-auto p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
                    @foreach($allUsers as $u)
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-800 cursor-pointer">
                            <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>{{ $u->name }} <span class="text-[10px] text-slate-500 font-normal">({{ $u->role_label }})</span></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('create-project-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-xs cursor-pointer">Create Project</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Project Modal -->
<div id="edit-project-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                Edit Project &amp; Team Assignment
            </h3>
            <button type="button" onclick="document.getElementById('edit-project-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm cursor-pointer">✕</button>
        </div>

        <form id="edit-project-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Project Name <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-p-name" name="name" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <!-- Department & Hierarchy Settings -->
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="edit-p-is-department" name="is_department" value="1" class="rounded text-purple-600 focus:ring-purple-500">
                    <label for="edit-p-is-department" class="text-xs font-bold text-slate-800 cursor-pointer">
                        🏢 Acts as a Department / Program Area
                    </label>
                </div>
                <p class="text-[11px] text-slate-500 pl-6">
                    Check this if this initiative groups other sub-projects.
                </p>

                <div class="pt-2 border-t border-slate-200">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Parent Department / Area (Optional)</label>
                    <select id="edit-p-parent-id" name="parent_id" onchange="filterSiblingOptions('edit-p-parent-id', 'edit-p-linked-project-id')" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 bg-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">-- Standalone / Top-Level Project --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" class="edit-p-dept-opt-{{ $dept->id }}">
                                🏢 {{ $dept->name }} {{ $dept->is_department ? '(Department)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">If this project belongs under a Department, select it here.</p>
                </div>

                <div id="edit-p-link-group" class="pt-2 border-t border-slate-200">
                    <label class="block text-xs font-bold text-slate-700 mb-1">🔗 Link with Sibling Project (Combined Slide)</label>
                    <select name="linked_project_id" id="edit-p-linked-project-id" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 bg-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">-- Standalone Slide (Not Linked) --</option>
                        @foreach($departments as $dept)
                            @foreach($dept->children as $child)
                                <option value="{{ $child->id }}" data-parent="{{ $dept->id }}" class="edit-p-link-opt-{{ $child->id }}">
                                    {{ $dept->name }} ↳ {{ $child->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Select sibling project to present under a combined slide (e.g. <em>Education (Literacy &amp; After Class)</em>).</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Project Code</label>
                    <input type="text" id="edit-p-code" name="code" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                    <select id="edit-p-status" name="status" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="active">Active</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Location</label>
                <input type="text" id="edit-p-location" name="location" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                <textarea id="edit-p-desc" name="description" rows="2" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Assigned Team Members</label>
                <div class="max-h-36 overflow-y-auto p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5" id="edit-user-checkboxes">
                    @foreach($allUsers as $u)
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-800 cursor-pointer">
                            <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" id="edit-user-{{ $u->id }}" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>{{ $u->name }} <span class="text-[10px] text-slate-500">({{ $u->role_label }})</span></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <button type="button" id="delete-project-btn" class="px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl cursor-pointer">Delete Project</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="document.getElementById('edit-project-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs cursor-pointer">Save Changes</button>
                </div>
            </div>
        </form>

        <form id="delete-project-form" method="POST" class="hidden" onsubmit="return confirm('Are you sure you want to delete this project permanently?')">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<!-- Create User Account Modal (Super Admin) -->
<div id="create-user-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                Create New Staff Account
            </h3>
            <button type="button" onclick="document.getElementById('create-user-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm cursor-pointer">✕</button>
        </div>

        <form action="{{ route('programmes.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Chanda Bwalya" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                <input type="email" name="email" required placeholder="chanda@dot.org" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required value="password" placeholder="••••••••" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Assigned Role <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="project_officer">Project Officer</option>
                        <option value="project_assistant">Project Assistant</option>
                        <option value="meal_officer">MEAL Officer</option>
                        <option value="super_admin">System Admin</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Assign to Projects</label>
                <div class="max-h-32 overflow-y-auto p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
                    @foreach($projects as $proj)
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-800 cursor-pointer">
                            <input type="checkbox" name="project_ids[]" value="{{ $proj->id }}" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>{{ $proj->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('create-user-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs cursor-pointer">Create User</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Account Modal (Super Admin) -->
<div id="edit-user-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                Edit Staff Account
            </h3>
            <button type="button" onclick="document.getElementById('edit-user-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm cursor-pointer">✕</button>
        </div>

        <form id="edit-user-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-u-name" name="name" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                <input type="email" id="edit-u-email" name="email" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">New Password</label>
                    <input type="password" id="edit-u-password" name="password" placeholder="Leave blank to keep" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Assigned Role <span class="text-rose-500">*</span></label>
                    <select id="edit-u-role" name="role" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="project_officer">Project Officer</option>
                        <option value="project_assistant">Project Assistant</option>
                        <option value="meal_officer">MEAL Officer</option>
                        <option value="super_admin">System Admin</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Assign to Projects</label>
                <div class="max-h-32 overflow-y-auto p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5" id="edit-u-projects-container">
                    @foreach($projects as $proj)
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-800 cursor-pointer">
                            <input type="checkbox" name="project_ids[]" value="{{ $proj->id }}" id="edit-u-proj-{{ $proj->id }}" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>{{ $proj->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('edit-user-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs cursor-pointer">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateDepartmentModal() {
    const modal = document.getElementById('create-project-modal');
    if (!modal) return;
    modal.classList.remove('hidden');
    const deptCb = document.getElementById('create-p-is-department');
    if (deptCb) deptCb.checked = true;
    const parentSel = document.getElementById('create-p-parent-id');
    if (parentSel) {
        parentSel.value = '';
        filterSiblingOptions('create-p-parent-id', 'create-p-linked-project-id');
    }
}

function filterProjectsList(type) {
    const cards = document.querySelectorAll('.project-card');
    cards.forEach(card => {
        const cType = card.getAttribute('data-card-type');
        if (type === 'all') {
            card.style.display = '';
        } else if (type === cType) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });

    const pillIds = ['all', 'child', 'standalone', 'department'];
    pillIds.forEach(pId => {
        const btn = document.getElementById('pill-' + pId);
        if (btn) {
            if (pId === type) {
                btn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-900 text-white shadow-2xs transition cursor-pointer';
            } else {
                btn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 transition cursor-pointer';
            }
        }
    });
}

function filterSiblingOptions(parentSelectId, linkSelectId) {
    const parentSelect = document.getElementById(parentSelectId);
    const linkSelect = document.getElementById(linkSelectId);
    if (!parentSelect || !linkSelect) return;

    const parentVal = parentSelect.value;
    Array.from(linkSelect.options).forEach((opt, idx) => {
        if (idx === 0) return; // Keep "-- Standalone --"
        const optParent = opt.getAttribute('data-parent');
        if (!parentVal || (optParent && optParent !== parentVal)) {
            opt.style.display = 'none';
        } else {
            opt.style.display = '';
        }
    });
    if (parentVal) {
        const currentSelectedOpt = linkSelect.options[linkSelect.selectedIndex];
        if (currentSelectedOpt && currentSelectedOpt.getAttribute('data-parent') !== parentVal && linkSelect.selectedIndex !== 0) {
            linkSelect.value = '';
        }
    } else {
        linkSelect.value = '';
    }
}

function openEditProjectModal(id, name, code, location, description, status, userIds, parentId, isDepartment, linkedProjectId) {
    document.getElementById('edit-project-form').action = '/programmes-meeting/projects/' + id;
    document.getElementById('delete-project-form').action = '/programmes-meeting/projects/' + id;
    document.getElementById('delete-project-btn').onclick = function() {
        if(confirm('Delete this project?')) {
            document.getElementById('delete-project-form').submit();
        }
    };

    document.getElementById('edit-p-name').value = name || '';
    document.getElementById('edit-p-code').value = code || '';
    document.getElementById('edit-p-location').value = location || '';
    document.getElementById('edit-p-desc').value = description || '';
    document.getElementById('edit-p-status').value = status || 'active';
    document.getElementById('edit-p-is-department').checked = !!isDepartment;

    const parentSelect = document.getElementById('edit-p-parent-id');
    Array.from(parentSelect.options).forEach(opt => {
        opt.disabled = (opt.value && parseInt(opt.value) === parseInt(id));
    });
    parentSelect.value = parentId || '';

    // Disable self in linked siblings
    const linkSelect = document.getElementById('edit-p-linked-project-id');
    Array.from(linkSelect.options).forEach(opt => {
        opt.disabled = (opt.value && parseInt(opt.value) === parseInt(id));
    });
    filterSiblingOptions('edit-p-parent-id', 'edit-p-linked-project-id');
    linkSelect.value = linkedProjectId || '';

    // Checkboxes
    const allCbs = document.querySelectorAll('#edit-user-checkboxes input[type="checkbox"]');
    allCbs.forEach(cb => {
        cb.checked = userIds && userIds.includes(parseInt(cb.value));
    });

    document.getElementById('edit-project-modal').classList.remove('hidden');
}

function openEditUserModal(id, name, email, role, projectIds) {
    document.getElementById('edit-user-form').action = '/programmes-meeting/users/' + id;
    document.getElementById('edit-u-name').value = name || '';
    document.getElementById('edit-u-email').value = email || '';
    document.getElementById('edit-u-password').value = '';
    document.getElementById('edit-u-role').value = role || 'project_officer';

    const allCbs = document.querySelectorAll('#edit-u-projects-container input[type="checkbox"]');
    allCbs.forEach(cb => {
        cb.checked = projectIds && projectIds.includes(parseInt(cb.value));
    });

    document.getElementById('edit-user-modal').classList.remove('hidden');
}
</script>
@endif

@endsection
