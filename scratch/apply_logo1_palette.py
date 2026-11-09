import re

path = 'resources/views/programmes/dashboard.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. User Profile in Sidebar:
old_user_profile = """            <!-- User Profile Summary -->
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

new_user_profile = """            <!-- User Profile Summary -->
            <a href="{{ route('profile.edit') }}" class="p-2.5 bg-slate-50/80 hover:bg-slate-100/80 border border-slate-200/70 rounded-xl flex items-center gap-2.5 transition group cursor-pointer" title="Manage your account & profile">
                <div class="w-8 h-8 rounded-lg bg-[#0d6c60]/10 text-[#0d6c60] flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-[#0d6c60]/15 transition">
                    {{ strtoupper(substr($currentUser->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-semibold text-slate-900 group-hover:text-[#0d6c60] transition truncate">
                        {{ $currentUser->name ?? 'Supervisor' }}
                    </div>
                    <span class="text-[10px] text-[#0d6c60] font-medium block truncate">
                        {{ $currentUser ? $currentUser->role_label : 'Staff' }}
                    </span>
                </div>
            </a>"""

content = content.replace(old_user_profile, new_user_profile)

# 2. Sidebar Navigation Items: Use PIF Deep Forest Teal for active state with vibrant green icon accent
content = content.replace(
    "($currentTab === 'dashboard' || !$currentTab) ? 'bg-slate-900 text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'",
    "($currentTab === 'dashboard' || !$currentTab) ? 'bg-[#0d6c60] text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'"
)
content = content.replace(
    "($currentTab === 'dashboard' || !$currentTab) ? 'text-white' : 'text-slate-400'",
    "($currentTab === 'dashboard' || !$currentTab) ? 'text-[#4f992f]' : 'text-slate-400'"
)
content = content.replace(
    "($currentTab === 'dashboard' || !$currentTab) ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-500'",
    "($currentTab === 'dashboard' || !$currentTab) ? 'bg-[#09544a] text-white/90' : 'bg-slate-100 text-slate-500'"
)

# For other tabs when active:
for tab in ['activities', 'projects', 'departments', 'matrix', 'staff']:
    content = content.replace(
        f"$currentTab === '{tab}' ? 'bg-slate-900 text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'",
        f"$currentTab === '{tab}' ? 'bg-[#0d6c60] text-white shadow-2xs font-semibold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'"
    )
    content = content.replace(
        f"$currentTab === '{tab}' ? 'text-white' : 'text-slate-400'",
        f"$currentTab === '{tab}' ? 'text-[#4f992f]' : 'text-slate-400'"
    )
    content = content.replace(
        f"$currentTab === '{tab}' ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-500'",
        f"$currentTab === '{tab}' ? 'bg-[#09544a] text-white/90' : 'bg-slate-100 text-slate-500'"
    )

# 3. Sidebar Log New Activity button:
old_sidebar_btn = """                <a href="{{ route('programmes.activities.create') }}" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-lg shadow-2xs transition active:scale-95 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Log New Activity</span>
                </a>"""

new_sidebar_btn = """                <a href="{{ route('programmes.activities.create') }}" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-white bg-[#0d6c60] hover:bg-[#09544a] rounded-lg shadow-2xs transition active:scale-95 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-[#4f992f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Log New Activity</span>
                </a>"""

content = content.replace(old_sidebar_btn, new_sidebar_btn)

# 4. Top Header Bar:
old_header_links = """            <div class="flex flex-wrap items-center gap-2 shrink-0">
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
            </div>"""

new_header_links = """            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('programmes.projector', $exportQuery) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[#0d6c60] hover:bg-[#09544a] rounded-lg shadow-2xs transition" title="Open Fullscreen Matrix Projector">
                    <svg class="w-3.5 h-3.5 text-[#4f992f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Live Projector</span>
                </a>

                <a href="{{ route('programmes.export_consolidated_pptx', $exportQuery) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 hover:text-[#0d6c60] hover:border-[#0d6c60]/30 border border-slate-200 rounded-lg shadow-2xs transition" title="Generate Consolidated PowerPoint Deck">
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#0d6c60]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>PowerPoint (.pptx)</span>
                </a>

                <a href="{{ route('programmes.export_consolidated_pdf', $exportQuery) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 hover:text-[#0d6c60] hover:border-[#0d6c60]/30 border border-slate-200 rounded-lg shadow-2xs transition" title="Generate Consolidated PDF Report">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>PDF (16:9)</span>
                </a>
            </div>"""

content = content.replace(old_header_links, new_header_links)

# Update breadcrumb active label
content = content.replace('<span class="text-slate-700 font-semibold">', '<span class="text-[#0d6c60] font-semibold">')

# 5. Executive Scope & Filter Banner:
old_scope_header = """                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-900 tracking-tight">Executive Overview</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200/60">"""

new_scope_header = """                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#4f992f]"></span>
                            <h3 class="text-sm font-bold text-slate-900 tracking-tight">Executive Overview</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[#0d6c60]/8 text-[#0d6c60] border border-[#0d6c60]/20">"""

content = content.replace(old_scope_header, new_scope_header)

content = content.replace(
    "$interval === $iKey ? 'bg-white text-slate-900 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'",
    "$interval === $iKey ? 'bg-white text-[#0d6c60] shadow-2xs font-bold border border-slate-200/60' : 'text-slate-600 hover:text-slate-900'"
)

# 6. Hero 4 KPI Cards:
# Replace the KPI cards block with palette-tinted icons and accents
kpi_start = content.find("<!-- ==================== 4 MINIMAL KPI METRIC CARDS ==================== -->")
pillars_start = content.find("<!-- ==================== STREAMLINED 5 THEMATIC PILLARS SUMMARY ROW ==================== -->")

if kpi_start != -1 and pillars_start != -1:
    new_kpi = """<!-- ==================== 4 MINIMAL KPI METRIC CARDS ==================== -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Metric Card 1: Active Projects -->
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs hover:border-[#0d6c60]/40 transition flex flex-col justify-between group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">Total Projects</span>
                            <div class="w-8 h-8 rounded-lg bg-[#0d6c60]/10 text-[#0d6c60] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="my-3">
                            <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $metrics['total_projects'] }}</div>
                            <div class="text-xs text-slate-500 font-normal mt-0.5">
                                <span class="font-semibold text-[#0d6c60]">{{ $metrics['active_projects'] }} active</span> across hubs
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Excludes {{ $metrics['total_departments'] }} parent departments</div>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'projects']) }}" class="pt-2 text-xs font-medium text-slate-600 hover:text-[#0d6c60] flex items-center justify-between group-hover:text-[#0d6c60] transition">
                            <span>Manage Projects</span>
                            <span class="group-hover:translate-x-0.5 transition">→</span>
                        </a>
                    </div>

                    <!-- Metric Card 2: Activities Logged -->
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs hover:border-[#4f992f]/50 transition flex flex-col justify-between group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">Activities Logged</span>
                            <div class="w-8 h-8 rounded-lg bg-[#4f992f]/15 text-[#3e7c25] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <a href="{{ route('programmes.hub', ['tab' => 'activities']) }}" class="pt-2 text-xs font-medium text-slate-600 hover:text-[#3e7c25] flex items-center justify-between group-hover:text-[#3e7c25] transition">
                            <span>Open Activity Logs</span>
                            <span class="group-hover:translate-x-0.5 transition">→</span>
                        </a>
                    </div>

                    <!-- Metric Card 3: Pillar Reporting Health / Coverage -->
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs hover:border-[#2c6d51]/40 transition flex flex-col justify-between group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">Pillar Coverage</span>
                            <div class="w-8 h-8 rounded-lg bg-[#2c6d51]/10 text-[#2c6d51] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="my-3">
                            <div class="flex items-baseline gap-2">
                                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $metrics['reporting_coverage_rate'] }}%</div>
                                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $metrics['reporting_coverage_rate'] >= 80 ? 'bg-[#4f992f]/15 text-[#3e7c25] border border-[#4f992f]/30' : ($metrics['reporting_coverage_rate'] >= 50 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-[#992f50]/10 text-[#992f50] border border-[#992f50]/20') }}">
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
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="pt-2 text-xs font-medium text-slate-600 hover:text-[#2c6d51] flex items-center justify-between group-hover:text-[#2c6d51] transition">
                            <span>Explore 5 Pillars Matrix</span>
                            <span class="group-hover:translate-x-0.5 transition">→</span>
                        </a>
                    </div>

                    <!-- Metric Card 4: Personnel & Officers -->
                    <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs hover:border-[#992f50]/40 transition flex flex-col justify-between group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">Team &amp; Personnel</span>
                            <div class="w-8 h-8 rounded-lg bg-[#992f50]/10 text-[#992f50] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <a href="{{ route('programmes.hub', ['tab' => 'staff']) }}" class="pt-2 text-xs font-medium text-slate-600 hover:text-[#992f50] flex items-center justify-between group-hover:text-[#992f50] transition">
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

                """
    content = content[:kpi_start] + new_kpi + content[pillars_start:]

# 7. 5 Thematic Pillars:
pillars_start = content.find("<!-- ==================== STREAMLINED 5 THEMATIC PILLARS SUMMARY ROW ==================== -->")
two_col_start = content.find("<!-- ==================== TWO-COLUMN SECTION: RECENT ACTIVITY & PRESENTATION HUB ==================== -->")

if pillars_start != -1 and two_col_start != -1:
    new_pillars = """<!-- ==================== STREAMLINED 5 THEMATIC PILLARS SUMMARY ROW ==================== -->
                <div class="bg-white border border-slate-200/70 rounded-xl p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#0d6c60]"></span>
                            <div>
                                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                                    5 Thematic Pillars Distribution
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5 font-normal">Overview of highlights recorded across organizational pillars</p>
                            </div>
                        </div>
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="text-xs font-medium text-slate-600 hover:text-[#0d6c60] flex items-center gap-1 transition">
                            <span>View Full Matrix</span>
                            <span>→</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                        <!-- Pillar 1: Achievements (Forward Green) -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-[#4f992f]/5 border border-slate-200/70 hover:border-[#4f992f]/50 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-[#3e7c25] uppercase tracking-wider">Pillar 1</span>
                                <span class="w-2 h-2 rounded-full bg-[#4f992f]"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-[#3e7c25] transition">{{ $metrics['total_achievements'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Achievements</div>
                        </a>

                        <!-- Pillar 2: Challenges & Risks (Berry Crimson) -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-[#992f50]/5 border border-slate-200/70 hover:border-[#992f50]/50 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-[#992f50] uppercase tracking-wider">Pillar 2</span>
                                <span class="w-2 h-2 rounded-full bg-[#992f50]"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-[#992f50] transition">{{ $metrics['total_challenges'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Challenges &amp; Risks</div>
                        </a>

                        <!-- Pillar 3: Learning & Adaptation (Warm Amber) -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-amber-50/50 border border-slate-200/70 hover:border-amber-300 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-amber-700 uppercase tracking-wider">Pillar 3</span>
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-amber-700 transition">{{ $metrics['total_learning'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Learning &amp; Adaptation</div>
                        </a>

                        <!-- Pillar 4: Monitoring & Eval (Forest Teal) -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-[#0d6c60]/5 border border-slate-200/70 hover:border-[#0d6c60]/50 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-[#0d6c60] uppercase tracking-wider">Pillar 4</span>
                                <span class="w-2 h-2 rounded-full bg-[#0d6c60]"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-[#0d6c60] transition">{{ $metrics['total_mne'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Monitoring &amp; Eval</div>
                        </a>

                        <!-- Pillar 5: Collaboration (Pine Green) -->
                        <a href="{{ route('programmes.hub', ['tab' => 'matrix']) }}" class="p-3.5 bg-white hover:bg-[#2c6d51]/5 border border-slate-200/70 hover:border-[#2c6d51]/50 rounded-xl transition space-y-1 block shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-[#2c6d51] uppercase tracking-wider">Pillar 5</span>
                                <span class="w-2 h-2 rounded-full bg-[#2c6d51]"></span>
                            </div>
                            <div class="text-xl font-bold text-slate-900 group-hover:text-[#2c6d51] transition">{{ $metrics['total_collab'] }}</div>
                            <div class="text-xs font-medium text-slate-700 truncate">Collaboration</div>
                        </a>
                    </div>
                </div>

                """
    content = content[:pillars_start] + new_pillars + content[two_col_start:]

# 8. Two columns: Recent Activity updates & Presentations
# Update Project code badge, View link, and presentation icon colors
content = content.replace(
    'class="px-1.5 py-0.5 text-[9px] font-semibold uppercase bg-slate-100 text-slate-700 border border-slate-200/70 rounded shrink-0"',
    'class="px-1.5 py-0.5 text-[9px] font-semibold uppercase bg-[#0d6c60]/8 text-[#0d6c60] border border-[#0d6c60]/20 rounded shrink-0"'
)
content = content.replace(
    'class="text-xs font-medium text-slate-600 hover:text-blue-600 transition">View ↗</a>',
    'class="text-xs font-medium text-slate-600 hover:text-[#0d6c60] transition">View ↗</a>'
)
content = content.replace(
    'class="text-xs font-medium text-blue-600 hover:text-blue-800 transition">\n                                    + Log Activity',
    'class="text-xs font-medium text-[#0d6c60] hover:text-[#09544a] transition">\n                                    + Log Activity'
)

# Presentations icons:
old_pres_cards = """                            <div class="space-y-2">
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
                            </div>"""

new_pres_cards = """                            <div class="space-y-2">
                                <!-- Projector -->
                                <a href="{{ route('programmes.projector', $exportQuery) }}" class="flex items-center justify-between p-3 bg-white hover:bg-[#0d6c60]/5 border border-slate-200/70 hover:border-[#0d6c60]/40 rounded-lg transition shadow-2xs group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#0d6c60] text-[#4f992f] flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-slate-900 group-hover:text-[#0d6c60] transition">Live Matrix Projector</div>
                                            <div class="text-[11px] text-slate-500">16:9 Fullscreen Slide Deck</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400 group-hover:text-[#0d6c60] group-hover:translate-x-0.5 transition">→</span>
                                </a>

                                <!-- PPTX -->
                                <a href="{{ route('programmes.export_consolidated_pptx', $exportQuery) }}" class="flex items-center justify-between p-3 bg-white hover:bg-amber-50/40 border border-slate-200/70 hover:border-amber-300 rounded-lg transition shadow-2xs group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-slate-900 group-hover:text-amber-800 transition">PowerPoint Presentation</div>
                                            <div class="text-[11px] text-slate-500">Editable .pptx presentation</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400 group-hover:text-amber-800 group-hover:translate-x-0.5 transition">→</span>
                                </a>

                                <!-- PDF -->
                                <a href="{{ route('programmes.export_consolidated_pdf', $exportQuery) }}" class="flex items-center justify-between p-3 bg-white hover:bg-[#992f50]/5 border border-slate-200/70 hover:border-[#992f50]/30 rounded-lg transition shadow-2xs group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#992f50]/10 text-[#992f50] flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-slate-900 group-hover:text-[#992f50] transition">Executive PDF Brief</div>
                                            <div class="text-[11px] text-slate-500">Print-ready 16:9 document</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400 group-hover:text-[#992f50] group-hover:translate-x-0.5 transition">→</span>
                                </a>
                            </div>"""

content = content.replace(old_pres_cards, new_pres_cards)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Logo1 palette successfully applied!")

