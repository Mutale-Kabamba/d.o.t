import re

path = 'resources/views/programmes/dashboard.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update overall container background
content = content.replace(
    '<div class="min-h-screen bg-slate-100 flex flex-col md:flex-row font-sans text-slate-800 antialiased">',
    '<div class="min-h-screen bg-slate-50/70 flex flex-col md:flex-row font-sans text-slate-800 antialiased">'
)

# 2. Update aside styling
content = content.replace(
    '<aside class="w-full md:w-64 lg:w-72 bg-white border-r border-slate-200/90 flex flex-col justify-between shrink-0 shadow-xs">',
    '<aside class="w-full md:w-64 lg:w-72 bg-white border-r border-slate-200/80 flex flex-col justify-between shrink-0">'
)

# 3. User profile summary card in sidebar
old_profile = """            <!-- User Profile Summary Pill -->
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
            </a>"""

new_profile = """            <!-- User Profile Summary -->
            <a href="{{ route('profile.edit') }}" class="p-2.5 bg-slate-50/80 hover:bg-slate-100/80 border border-slate-200/70 rounded-xl flex items-center gap-2.5 transition group cursor-pointer" title="Manage your account & profile">
                <div class="w-8 h-8 rounded-lg bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-slate-300 transition">
                    {{ strtoupper(substr($currentUser->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-semibold text-slate-900 group-hover:text-blue-600 transition truncate">
                        {{ $currentUser->name ?? 'Supervisor' }}
                    </div>
                    <span class="text-[10px] text-slate-500 font-medium block truncate">
                        {{ $currentUser ? $currentUser->role_label : 'Staff' }}
                    </span>
                </div>
            </a>"""

content = content.replace(old_profile, new_profile)

# 4. Navigation tabs styling:
nav_pattern = r'<!-- Navigation Links / Tabs -->[\s\S]*?<!-- Quick Creation Actions -->'
new_nav = """<!-- Navigation Links / Tabs -->
            <nav class="space-y-1" aria-label="Main Sidebar Navigation">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 py-1.5">
                    Workspace
                </div>

                @php
                    $baseQuery = request()->except(['tab', 'page']);
                @endphp

                <!-- Tab 0: Executive Dashboard -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'dashboard'])) }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition {{ ($currentTab === 'dashboard' || !$currentTab) ? 'bg-slate-900 text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ ($currentTab === 'dashboard' || !$currentTab) ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ ($currentTab === 'dashboard' || !$currentTab) ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-500' }}">
                        KPIs
                    </span>
                </a>

                <!-- Tab 1: Activity Logs -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'activities'])) }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition {{ $currentTab === 'activities' ? 'bg-slate-900 text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'activities' ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>Activity Logs</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $currentTab === 'activities' ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $activities->total() }}
                    </span>
                </a>

                <!-- Tab 2: Projects Directory -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'projects'])) }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition {{ $currentTab === 'projects' ? 'bg-slate-900 text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'projects' ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Projects</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $currentTab === 'projects' ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $projects->where('is_department', false)->count() }}
                    </span>
                </a>

                <!-- Tab: Parent Projects / Departments -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'departments'])) }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition {{ $currentTab === 'departments' ? 'bg-slate-900 text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'departments' ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Departments</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $currentTab === 'departments' ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $departments->count() }}
                    </span>
                </a>

                <!-- Tab 3: Thematic Pillars Matrix -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'matrix'])) }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition {{ $currentTab === 'matrix' ? 'bg-slate-900 text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'matrix' ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Thematic Matrix</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $currentTab === 'matrix' ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-500' }}">
                        5 Pillars
                    </span>
                </a>

                <!-- Account & Profile -->
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition text-slate-600 hover:bg-slate-100/80 hover:text-slate-900">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>My Profile</span>
                    </div>
                </a>

                @if($isSuperAdmin)
                <!-- Administration Section -->
                <div class="pt-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 py-1.5">
                    Administration
                </div>

                <!-- Tab 4: Staff & Team Management -->
                <a href="{{ route('programmes.hub', array_merge($baseQuery, ['tab' => 'staff'])) }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition {{ $currentTab === 'staff' ? 'bg-slate-900 text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $currentTab === 'staff' ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Team &amp; Staff</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $currentTab === 'staff' ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $allUsers->count() }}
                    </span>
                </a>
                @endif
            </nav>

            <!-- Quick Creation Actions -->"""

content = re.sub(nav_pattern, lambda m: new_nav, content, flags=re.MULTILINE)

# 5. Quick Creation Actions in sidebar
old_quick_actions = """            <!-- Quick Creation Actions -->
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
            </div>"""

new_quick_actions = """            <!-- Quick Creation Actions -->
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ route('programmes.activities.create') }}" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-lg shadow-2xs transition active:scale-95 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Log New Activity</span>
                </a>

                @if($isSuperAdmin)
                <div class="grid grid-cols-2 gap-1.5">
                    <button type="button" onclick="document.getElementById('create-project-modal').classList.remove('hidden')" class="px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg transition cursor-pointer text-center shadow-2xs">
                        + Project
                    </button>
                    <button type="button" onclick="document.getElementById('create-user-modal').classList.remove('hidden')" class="px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg transition cursor-pointer text-center shadow-2xs">
                        + Staff
                    </button>
                </div>
                @endif
            </div>"""

content = content.replace(old_quick_actions, new_quick_actions)

# 6. Sidebar logout button
old_logout = """        <!-- Sidebar Footer & Logout -->
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
        </div>"""

new_logout = """        <!-- Sidebar Footer & Logout -->
        <div class="p-3 border-t border-slate-100">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-1.5 text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100/80 rounded-lg transition cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>"""

content = content.replace(old_logout, new_logout)

# 7. Top Header Bar styling & buttons
old_header = """        <!-- Top Header Navigation (Quick Access Links) -->
        <header class="bg-white border-b border-slate-200/90 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-30 shadow-xs">
            <!-- Current Section Breadcrumb -->
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    <span>Play It Forward</span>
                    <span>/</span>
                    <span class="text-blue-600 font-bold">
                        @if($currentTab === 'projects') Projects Directory
                        @elseif($currentTab === 'departments') Departments Directory
                        @elseif($currentTab === 'staff') Team &amp; Staff Management
                        @elseif($currentTab === 'matrix') 5 Thematic Pillars Matrix
                        @elseif($currentTab === 'activities') Continuous Activity Logging
                        @else Executive Overview &amp; KPIs
                        @endif
                    </span>
                </div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight mt-0.5">
                    @if($currentTab === 'projects') Organizational Projects
                    @elseif($currentTab === 'departments') Organizational Departments
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
        </header>"""

new_header = """        <!-- Top Header Navigation (Quick Access Links) -->
        <header class="bg-white border-b border-slate-200/80 px-6 py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sticky top-0 z-30">
            <!-- Current Section Breadcrumb -->
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                    <span>Play It Forward</span>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">
                        @if($currentTab === 'projects') Projects Directory
                        @elseif($currentTab === 'departments') Departments Directory
                        @elseif($currentTab === 'staff') Team &amp; Staff Management
                        @elseif($currentTab === 'matrix') 5 Thematic Pillars Matrix
                        @elseif($currentTab === 'activities') Continuous Activity Logging
                        @else Executive Overview &amp; KPIs
                        @endif
                    </span>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight mt-0.5">
                    @if($currentTab === 'projects') Organizational Projects
                    @elseif($currentTab === 'departments') Organizational Departments
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
                <a href="{{ route('programmes.projector', $exportQuery) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-slate-900 hover:bg-slate-800 rounded-lg shadow-2xs transition" title="Open Fullscreen Matrix Projector">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Live Projector</span>
                </a>

                <a href="{{ route('programmes.export_consolidated_pptx', $exportQuery) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg shadow-2xs transition" title="Generate Consolidated PowerPoint Deck">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>PowerPoint (.pptx)</span>
                </a>

                <a href="{{ route('programmes.export_consolidated_pdf', $exportQuery) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg shadow-2xs transition" title="Generate Consolidated PDF Report">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>PDF (16:9)</span>
                </a>
            </div>
        </header>"""

content = content.replace(old_header, new_header)

# 8. Executive Overview Banner, KPI Cards, Thematic Pillars, Bottom 2 columns
tab0_start = content.find("<!-- ==================== TAB 0: EXECUTIVE DASHBOARD WITH KEY METRICS ==================== -->")
tab1_start = content.find("<!-- ==================== TAB 1: CONTINUOUS ACTIVITY LOGS ==================== -->")

if tab0_start != -1 and tab1_start != -1:
    new_tab0 = """<!-- ==================== TAB 0: EXECUTIVE DASHBOARD WITH KEY METRICS ==================== -->
            @if($currentTab === 'dashboard' || !$currentTab)
            <div class="space-y-6">

                <!-- Compact Executive Scope & Filter Banner -->
                <div class="bg-white border border-slate-200/70 rounded-xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-2xs">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-900 tracking-tight">Executive Overview</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200/60">
                                @if($interval === 'month')
                                    {{ \\Carbon\\Carbon::create($filterParams['year'], $filterParams['month'], 1)->format('F Y') }}
                                @elseif($interval === 'quarter')
                                    Q{{ $filterParams['quarter'] }} {{ $filterParams['year'] }}
                                @elseif($interval === 'year')
                                    Year {{ $filterParams['year'] }}
                                @else
                                    All Time
                                @endif
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 font-normal mt-0.5">High-level strategic performance &amp; review matrix overview</p>
                    </div>

                    <!-- Sleek Inline Scope Controls -->
                    <form action="{{ route('programmes.hub') }}" method="GET" class="flex flex-wrap items-center gap-2">
                        <input type="hidden" name="tab" value="dashboard">
                        
                        <div class="inline-flex items-center bg-slate-100 p-0.5 rounded-lg border border-slate-200/50">
                            @foreach(['all' => 'All', 'quarter' => 'Quarter', 'month' => 'Month', 'year' => 'Year'] as $iKey => $iLabel)
                                <button type="submit" name="interval" value="{{ $iKey }}" class="px-2.5 py-1 text-xs font-medium rounded-md transition {{ $interval === $iKey ? 'bg-white text-slate-900 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900' }}">
                                    {{ $iLabel }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Year Selection -->
                        <select name="year" onchange="this.form.submit()" class="text-xs font-medium bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                            @php
                                $currentYear = (int) now()->year;
                                $selectedYear = (int) ($filterParams['year'] ?? $currentYear);
                            @endphp
                            @foreach(range($currentYear - 2, $currentYear + 1) as $yr)
                                <option value="{{ $yr }}" {{ $selectedYear === $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>

                        @if($interval === 'quarter')
                        <select name="quarter" onchange="this.form.submit()" class="text-xs font-medium bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                            @php $selectedQuarter = (int) ($filterParams['quarter'] ?? ceil(now()->month / 3)); @endphp
                            @foreach([1 => 'Q1', 2 => 'Q2', 3 => 'Q3', 4 => 'Q4'] as $qNum => $qName)
                                <option value="{{ $qNum }}" {{ $selectedQuarter === $qNum ? 'selected' : '' }}>{{ $qName }}</option>
                            @endforeach
                        </select>
                        @elseif($interval === 'month')
                        <select name="month" onchange="this.form.submit()" class="text-xs font-medium bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                            @php $selectedMonth = (int) ($filterParams['month'] ?? now()->month); @endphp
                            @foreach([1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'] as $mNum => $mName)
                                <option value="{{ $mNum }}" {{ $selectedMonth === $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                            @endforeach
                        </select>
                        @endif

                        <select name="project_id" onchange="this.form.submit()" class="text-xs font-medium bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400 max-w-[140px] truncate">
                            <option value="all" {{ (!$selectedProjectId || $selectedProjectId === 'all') ? 'selected' : '' }}>All Projects</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ $selectedProjectId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <!-- ==================== 4 MINIMAL KPI METRIC CARDS ==================== -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Metric Card 1: Active Projects -->
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs hover:border-slate-300 transition flex flex-col justify-between group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">Total Projects</span>
                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="my-3">
                            <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $metrics['total_projects'] }}</div>
                            <div class="text-xs text-slate-500 font-normal mt-0.5">
                                <span class="font-medium text-slate-700">{{ $metrics['active_projects'] }} active</span> across hubs
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Excludes {{ $metrics['total_departments'] }} parent departments</div>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'projects']) }}" class="pt-2 text-xs font-medium text-slate-600 hover:text-slate-900 flex items-center justify-between group-hover:text-blue-600 transition">
                            <span>Manage Projects</span>
                            <span class="group-hover:translate-x-0.5 transition">→</span>
                        </a>
                    </div>

                    <!-- Metric Card 2: Activities Logged -->
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs hover:border-slate-300 transition flex flex-col justify-between group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">Activities Logged</span>
                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="my-3">
                            <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $metrics['total_activities'] }}</div>
                            <div class="text-xs text-slate-500 font-normal mt-0.5">
                                Continuous entries recorded
                            </div>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'activities']) }}" class="pt-2 text-xs font-medium text-slate-600 hover:text-slate-900 flex items-center justify-between group-hover:text-blue-600 transition">
                            <span>Open Activity Logs</span>
                            <span class="group-hover:translate-x-0.5 transition">→</span>
                        </a>
                    </div>

                    <!-- Metric Card 3: Pillar Reporting Health / Coverage -->
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs hover:border-slate-300 transition flex flex-col justify-between group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">Pillar Coverage</span>
                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="my-3">
                            <div class="flex items-baseline gap-2">
                                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $metrics['reporting_coverage_rate'] }}%</div>
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded {{ $metrics['reporting_coverage_rate'] >= 80 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($metrics['reporting_coverage_rate'] >= 50 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                    @if($metrics['reporting_coverage_rate'] >= 80)
                                        Healthy
                                    @elseif($metrics['reporting_coverage_rate'] >= 50)
                                        In Progress
                                    @else
                                        Needs Update
                                    @endif
                                </span>
                            </div>
                            <div class="text-xs text-slate-500 font-normal mt-0.5">
                                {{ $metrics['fully_reported_projects'] }} of {{ $metrics['active_projects'] }} projects fully updated (5/5)
                            </div>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="pt-2 text-xs font-medium text-slate-600 hover:text-slate-900 flex items-center justify-between group-hover:text-blue-600 transition">
                            <span>Explore 5 Pillars Matrix</span>
                            <span class="group-hover:translate-x-0.5 transition">→</span>
                        </a>
                    </div>

                    <!-- Metric Card 4: Personnel & Officers -->
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs hover:border-slate-300 transition flex flex-col justify-between group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">Team &amp; Personnel</span>
                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="my-3">
                            <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $metrics['total_staff'] }}</div>
                            <div class="text-xs text-slate-500 font-normal mt-0.5">
                                {{ $metrics['total_officers'] }} Field Project Officers
                            </div>
                        </div>
                        @if($isSuperAdmin)
                        <a href="{{ route('programmes.hub', ['tab' => 'staff']) }}" class="pt-2 text-xs font-medium text-slate-600 hover:text-slate-900 flex items-center justify-between group-hover:text-blue-600 transition">
                            <span>Manage Team</span>
                            <span class="group-hover:translate-x-0.5 transition">→</span>
                        </a>
                        @else
                        <div class="pt-2 text-xs font-normal text-slate-400">
                            Field Operations Team
                        </div>
                        @endif
                    </div>
                </div>

                <!-- ==================== STREAMLINED 5 THEMATIC PILLARS SUMMARY ROW ==================== -->
                <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                                5 Thematic Pillars Distribution
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 font-normal">Overview of highlights recorded across organizational pillars</p>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="text-xs font-medium text-slate-600 hover:text-slate-900 flex items-center gap-1 transition">
                            <span>View Full Matrix</span>
                            <span>→</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                        <!-- Pillar 1 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-slate-50 border border-slate-200/70 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Pillar 1</span>
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition">{{ $metrics['total_achievements'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Achievements</div>
                        </a>

                        <!-- Pillar 2 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-slate-50 border border-slate-200/70 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Pillar 2</span>
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-rose-600 transition">{{ $metrics['total_challenges'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Challenges &amp; Risks</div>
                        </a>

                        <!-- Pillar 3 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-slate-50 border border-slate-200/70 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Pillar 3</span>
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-amber-600 transition">{{ $metrics['total_learning'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Learning &amp; Adaptation</div>
                        </a>

                        <!-- Pillar 4 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-slate-50 border border-slate-200/70 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Pillar 4</span>
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition">{{ $metrics['total_mne'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Monitoring &amp; Eval</div>
                        </a>

                        <!-- Pillar 5 -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-slate-50 border border-slate-200/70 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Pillar 5</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-emerald-600 transition">{{ $metrics['total_collab'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Collaboration</div>
                        </a>
                    </div>
                </div>

                <!-- ==================== TWO-COLUMN SECTION: RECENT ACTIVITY & PRESENTATION HUB ==================== -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left: Recent Activity Stream (7 cols) -->
                    <div class="lg:col-span-7 bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                                    Recent Activity Updates
                                </h3>
                                <a href="{{ route('programmes.activities.create') }}" class="text-xs font-medium text-blue-600 hover:text-blue-800 transition">
                                    + Log Activity
                                </a>
                            </div>

                            @if($metrics['latest_activities']->isEmpty())
                            <div class="py-12 text-center text-xs text-slate-400">
                                No activity logs recorded for this period.
                            </div>
                            @else
                            <div class="space-y-2">
                                @foreach($metrics['latest_activities'] as $item)
                                <div class="p-3 bg-white hover:bg-slate-50/80 border border-slate-200/60 rounded-lg flex items-center justify-between gap-3 transition">
                                    <div class="min-w-0 flex-1 space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <span class="px-1.5 py-0.5 text-[9px] font-semibold uppercase bg-slate-100 text-slate-700 border border-slate-200/70 rounded shrink-0">
                                                {{ $item->project->code ?? 'PROJECT' }}
                                            </span>
                                            <span class="text-xs font-semibold text-slate-900 truncate">{{ $item->activity_title }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 truncate">
                                            {{ $item->location ?: 'Location recorded' }} • Logged by {{ $item->user->name ?? 'Staff' }}
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <div class="text-[10px] text-slate-400 font-medium">{{ \\Carbon\\Carbon::parse($item->activity_date)->format('M d') }}</div>
                                        <a href="{{ route('programmes.activities.show', $item->token) }}" class="text-xs font-medium text-slate-600 hover:text-blue-600 transition">View ↗</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-normal">Total in scope: <strong class="text-slate-700 font-medium">{{ $metrics['total_activities'] }}</strong></span>
                            <a href="{{ route('programmes.hub', ['tab' => 'activities']) }}" class="font-medium text-slate-600 hover:text-slate-900 transition flex items-center gap-1">
                                <span>View All Logs</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right: Quick Presentation Launchers (5 cols) -->
                    <div class="lg:col-span-5 bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="border-b border-slate-100 pb-3">
                                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                                    Presentations &amp; Outputs
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5 font-normal">Present or export consolidated executive materials</p>
                            </div>

                            <div class="space-y-2">
                                <!-- Projector -->
                                <a href="{{ route('programmes.projector', $exportQuery) }}" class="flex items-center justify-between p-3 bg-white hover:bg-slate-50 border border-slate-200/70 rounded-lg transition shadow-2xs group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-slate-900 group-hover:text-blue-600 transition">Live Matrix Projector</div>
                                            <div class="text-[11px] text-slate-500">16:9 Fullscreen Slide Deck</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400 group-hover:text-slate-900 group-hover:translate-x-0.5 transition">→</span>
                                </a>

                                <!-- PPTX -->
                                <a href="{{ route('programmes.export_consolidated_pptx', $exportQuery) }}" class="flex items-center justify-between p-3 bg-white hover:bg-slate-50 border border-slate-200/70 rounded-lg transition shadow-2xs group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-slate-900 group-hover:text-blue-600 transition">PowerPoint Presentation</div>
                                            <div class="text-[11px] text-slate-500">Editable .pptx presentation</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400 group-hover:text-slate-900 group-hover:translate-x-0.5 transition">→</span>
                                </a>

                                <!-- PDF -->
                                <a href="{{ route('programmes.export_consolidated_pdf', $exportQuery) }}" class="flex items-center justify-between p-3 bg-white hover:bg-slate-50 border border-slate-200/70 rounded-lg transition shadow-2xs group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-slate-900 group-hover:text-blue-600 transition">Executive PDF Brief</div>
                                            <div class="text-[11px] text-slate-500">Print-ready 16:9 document</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400 group-hover:text-slate-900 group-hover:translate-x-0.5 transition">→</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100">
                            <span class="text-[11px] text-slate-400 block text-center">Consolidated across {{ $metrics['active_projects'] }} active project initiatives</span>
                        </div>
                    </div>
                </div>

            </div>
            @endif
            
            """
    content = content[:tab0_start] + new_tab0 + content[tab1_start:]

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Minimal dashboard applied successfully!")
