@extends('layouts.app')

@section('title', 'Admin Portal - Teams & Projects Management')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Top Admin Header Navigation -->
    @include('admin.nav', ['activeTab' => 'teams'])

    <!-- Flash Notifications -->
    @if(session('success'))
    <div class="p-3.5 bg-emerald-50 text-emerald-800 rounded-2xl text-xs font-bold border border-emerald-200 flex items-center justify-between shadow-xs">
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-3.5 bg-rose-50 text-rose-800 rounded-2xl text-xs font-bold border border-rose-200 flex items-center justify-between shadow-xs">
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ session('error') }}
        </span>
    </div>
    @endif

    @if($errors->any())
    <div class="p-3.5 bg-rose-50 text-rose-800 rounded-2xl text-xs font-bold border border-rose-200 space-y-1">
        <div class="flex items-center gap-1.5 text-rose-700 font-extrabold">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Please correct the errors below:</span>
        </div>
        <ul class="list-disc list-inside font-normal space-y-0.5 pl-2">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Team & Project KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Projects -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Initiatives</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">📁</span>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-black text-slate-900 font-display">{{ $totalProjects }}</div>
                <p class="text-[11px] text-slate-400 font-medium">All registered projects</p>
            </div>
        </div>

        <!-- Active Initiatives -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-emerald-600">Active Teams</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">⚡</span>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-black text-emerald-600 font-display">{{ $activeProjects }}</div>
                <p class="text-[11px] text-slate-400 font-medium">In active field execution</p>
            </div>
        </div>

        <!-- Archived Initiatives -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Archived</span>
                <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs">📦</span>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-black text-slate-600 font-display">{{ $archivedProjects }}</div>
                <p class="text-[11px] text-slate-400 font-medium">Completed or paused</p>
            </div>
        </div>

        <!-- Staff Pool -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-indigo-600">Available Staff</span>
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">👥</span>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-black text-indigo-600 font-display">{{ $allStaff->count() }}</div>
                <p class="text-[11px] text-slate-400 font-medium">Eligible personnel</p>
            </div>
        </div>
    </div>

    <!-- Toolbar & Action Bar -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search and Filter Form -->
        <form action="{{ route('admin.teams.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-md">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search by name, code, location..." class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-3 py-2 text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $selectedStatus === 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="archived" {{ $selectedStatus === 'archived' ? 'selected' : '' }}>Archived Only</option>
            </select>

            @if($search || $selectedStatus !== 'all')
                <a href="{{ route('admin.teams.index') }}" class="px-2.5 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-100 rounded-xl transition">
                    Clear Filters
                </a>
            @endif
        </form>

        <!-- Create Project Button -->
        <button type="button" onclick="document.getElementById('create-team-modal').classList.remove('hidden')" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-xs active:scale-95 cursor-pointer shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Create Team / Project</span>
        </button>
    </div>

    <!-- Teams & Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($teams as $team)
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-300 transition flex flex-col justify-between p-5 space-y-4">
            <div class="space-y-3">
                <!-- Top Badges -->
                <div class="flex items-center justify-between gap-2">
                    <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 rounded-md">
                        {{ $team->code ?: 'PROJECT' }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold {{ $team->status === 'active' ? 'text-emerald-600' : 'text-slate-400' }}">
                        <span>●</span>
                        <span>{{ ucfirst($team->status) }}</span>
                    </span>
                </div>

                <!-- Team Title & Description -->
                <div>
                    <h3 class="text-sm font-black text-slate-900 leading-snug">{{ $team->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $team->description ?: 'Community development and youth empowerment programming.' }}</p>
                </div>

                <!-- Team Staff & Location Details -->
                <div class="pt-2 border-t border-slate-100 text-xs space-y-2">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Assigned Staff</span>
                            @if($team->users->isNotEmpty())
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($team->users as $u)
                                        <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 text-[10px] font-bold border border-slate-200">
                                            {{ $u->name }} <span class="font-normal text-slate-500">({{ $u->role === 'project_officer' ? 'Lead' : 'Staff' }})</span>
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-[11px] text-rose-500 font-medium italic">No staff assigned</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1 text-[11px] text-slate-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>{{ $team->location ?: 'Livingstone, Zambia' }}</span>
                        </span>

                        <span class="font-bold text-slate-700">
                            {{ $team->activityEntries->count() }} activities
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                <div class="flex items-center gap-1">
                    <a href="{{ route('programmes.projects.projector', $team->id) }}" class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition" title="Project this specific team/project directly">
                        ▶ Project
                    </a>
                    <a href="{{ route('programmes.projects.export_pdf', $team->id) }}" class="px-2.5 py-1 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition" title="Download Isolated PDF">
                        PDF
                    </a>
                </div>

                <div class="flex items-center gap-1">
                    <!-- Edit Team -->
                    <button type="button" onclick="openEditTeamModal({{ $team->id }}, '{{ addslashes($team->name) }}', '{{ addslashes($team->code) }}', '{{ addslashes($team->location) }}', '{{ addslashes($team->description) }}', '{{ $team->status }}', {{ json_encode($team->users->pluck('id')) }})" class="p-1.5 text-xs text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg cursor-pointer" title="Edit Team Details">
                        ⚙️
                    </button>

                    <!-- Toggle Status -->
                    <form action="{{ route('admin.teams.toggle_status', $team->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-1.5 text-xs {{ $team->status === 'active' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} border border-slate-200 rounded-lg cursor-pointer" title="{{ $team->status === 'active' ? 'Archive Project' : 'Activate Project' }}">
                            {{ $team->status === 'active' ? '📁' : '⚡' }}
                        </button>
                    </form>

                    <!-- Delete Team -->
                    <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete project \'{{ addslashes($team->name) }}\'? This will remove team linkages.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-xs text-rose-600 hover:bg-rose-50 border border-slate-200 rounded-lg cursor-pointer" title="Delete Team/Project">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400 font-medium bg-white rounded-2xl border border-slate-200/90 p-8">
            No projects or teams found matching the filters.
        </div>
        @endforelse
    </div>

    @if($teams->hasPages())
    <div class="p-4 border-t border-slate-100 bg-white rounded-2xl shadow-xs">
        {{ $teams->links() }}
    </div>
    @endif

</div>

<!-- ==================== CREATE TEAM MODAL ==================== -->
<div id="create-team-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                Create New Team &amp; Project
            </h3>
            <button type="button" onclick="document.getElementById('create-team-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm cursor-pointer">✕</button>
        </div>

        <form action="{{ route('admin.teams.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Project / Team Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Disability Sports &amp; Inclusion" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Code</label>
                    <input type="text" name="code" placeholder="e.g. DSI" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Location</label>
                    <input type="text" name="location" placeholder="e.g. Livingstone" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="Brief summary of project scope and goals..." class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Assign Staff Members</label>
                <div class="max-h-36 overflow-y-auto p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
                    @foreach($allStaff as $u)
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-800 cursor-pointer">
                            <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>{{ $u->name }} <span class="text-[10px] text-slate-500">({{ $u->role_label }})</span></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('create-team-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs cursor-pointer">Create Project</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== EDIT TEAM MODAL ==================== -->
<div id="edit-team-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                Edit Team &amp; Project
            </h3>
            <button type="button" onclick="document.getElementById('edit-team-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm cursor-pointer">✕</button>
        </div>

        <form id="edit-team-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Project / Team Name <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-team-name" name="name" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Code</label>
                    <input type="text" id="edit-team-code" name="code" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                    <select id="edit-team-status" name="status" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="active">Active</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Location</label>
                <input type="text" id="edit-team-location" name="location" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                <textarea id="edit-team-desc" name="description" rows="2" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Assigned Staff Members</label>
                <div class="max-h-36 overflow-y-auto p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5" id="edit-team-staff-container">
                    @foreach($allStaff as $u)
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-800 cursor-pointer">
                            <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" id="edit-team-staff-{{ $u->id }}" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>{{ $u->name }} <span class="text-[10px] text-slate-500">({{ $u->role_label }})</span></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('edit-team-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs cursor-pointer">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditTeamModal(id, name, code, location, description, status, userIds) {
    document.getElementById('edit-team-form').action = '/admin/teams/' + id;
    document.getElementById('edit-team-name').value = name || '';
    document.getElementById('edit-team-code').value = code || '';
    document.getElementById('edit-team-location').value = location || '';
    document.getElementById('edit-team-desc').value = description || '';
    document.getElementById('edit-team-status').value = status || 'active';

    const checkboxes = document.querySelectorAll('#edit-team-staff-container input[type="checkbox"]');
    checkboxes.forEach(cb => {
        cb.checked = userIds && userIds.includes(parseInt(cb.value));
    });

    document.getElementById('edit-team-modal').classList.remove('hidden');
}
</script>

@endsection
