@extends('layouts.app')

@section('title', 'Admin Portal - Team & Staff Management')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Top Admin Header Navigation -->
    @include('admin.nav', ['activeTab' => 'staff'])

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

    <!-- Staff Analytics KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Personnel -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Staff</span>
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">👥</span>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-black text-slate-900 font-display">{{ $totalStaff }}</div>
                <p class="text-[11px] text-slate-400 font-medium">Active accounts</p>
            </div>
        </div>

        <!-- Project Officers -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-blue-600">Project Officers</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">🎯</span>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-black text-blue-600 font-display">{{ $totalOfficers }}</div>
                <p class="text-[11px] text-slate-400 font-medium">Field leads</p>
            </div>
        </div>

        <!-- Project Assistants -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-amber-600">Assistants</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">🤝</span>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-black text-amber-600 font-display">{{ $totalAssistants }}</div>
                <p class="text-[11px] text-slate-400 font-medium">Community facilitators</p>
            </div>
        </div>

        <!-- Super Admins -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-purple-600">Super Admins</span>
                <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">👑</span>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-black text-purple-600 font-display">{{ $totalAdmins }}</div>
                <p class="text-[11px] text-slate-400 font-medium">Full governance access</p>
            </div>
        </div>

        <!-- Unassigned / Floating -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Unassigned</span>
                <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">⚠️</span>
            </div>
            <div class="my-2">
                <div class="text-2xl sm:text-3xl font-black text-slate-700 font-display">{{ $unassignedStaff }}</div>
                <p class="text-[11px] text-slate-400 font-medium">Pending project link</p>
            </div>
        </div>
    </div>

    <!-- Toolbar & Filter Controls -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search and Filter Form -->
        <form action="{{ route('admin.staff.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-md">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search staff by name or email..." class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-3 py-2 text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Role Filter Pills -->
            <select name="role" onchange="this.form.submit()" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="all" {{ $selectedRole === 'all' ? 'selected' : '' }}>All Roles</option>
                <option value="super_admin" {{ $selectedRole === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="project_officer" {{ $selectedRole === 'project_officer' ? 'selected' : '' }}>Project Officer</option>
                <option value="project_assistant" {{ $selectedRole === 'project_assistant' ? 'selected' : '' }}>Project Assistant</option>
            </select>

            <!-- Project Assignment Filter -->
            <select name="project_id" onchange="this.form.submit()" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 max-w-[160px] truncate">
                <option value="all" {{ $selectedProjectId === 'all' ? 'selected' : '' }}>All Projects</option>
                @foreach($allProjects as $proj)
                    <option value="{{ $proj->id }}" {{ $selectedProjectId == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                @endforeach
            </select>

            @if($search || $selectedRole !== 'all' || $selectedProjectId !== 'all')
                <a href="{{ route('admin.staff.index') }}" class="px-2.5 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-100 rounded-xl transition">
                    Clear Filters
                </a>
            @endif
        </form>

        <!-- Create Staff Button -->
        <button type="button" onclick="document.getElementById('create-staff-modal').classList.remove('hidden')" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-xs active:scale-95 cursor-pointer shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <span>+ Provision Staff Account</span>
        </button>
    </div>

    <!-- Staff Members Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Staff Member</th>
                        <th class="py-3.5 px-4">Role &amp; Permissions</th>
                        <th class="py-3.5 px-4">Project Deployments</th>
                        <th class="py-3.5 px-4 text-center">Activity Logs</th>
                        <th class="py-3.5 px-4">Created Date</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($staffMembers as $staff)
                    <tr class="hover:bg-slate-50/70 transition">
                        <!-- Name & Avatar -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $staff->isSuperAdmin() ? 'bg-purple-100 text-purple-800 border-purple-200' : ($staff->role === 'project_officer' ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-amber-100 text-amber-800 border-amber-200') }} border font-black text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">{{ $staff->name }}</div>
                                    <div class="text-[11px] text-slate-400 truncate">{{ $staff->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Role -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $staff->isSuperAdmin() ? 'bg-purple-100 text-purple-800 border border-purple-200' : ($staff->role === 'project_officer' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-amber-100 text-amber-800 border border-amber-200') }}">
                                <span>{{ $staff->isSuperAdmin() ? '👑' : ($staff->role === 'project_officer' ? '🎯' : '🤝') }}</span>
                                <span>{{ $staff->role_label }}</span>
                            </span>
                        </td>

                        <!-- Assigned Projects -->
                        <td class="py-3.5 px-4">
                            @if($staff->isSuperAdmin())
                                <span class="text-purple-700 font-bold text-[11px] flex items-center gap-1">
                                    <span>★ Global Access (All Projects)</span>
                                </span>
                            @elseif($staff->projects->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach($staff->projects as $p)
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 text-[10px] font-bold border border-slate-200" title="{{ $p->name }}">
                                            {{ $p->code ?: $p->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 italic text-[11px]">Unassigned</span>
                            @endif
                        </td>

                        <!-- Activity Logs Count -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <span class="inline-block px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[11px]">
                                {{ $staff->activityEntries->count() }}
                            </span>
                        </td>

                        <!-- Joined Date -->
                        <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap text-[11px]">
                            {{ $staff->created_at->format('M d, Y') }}
                        </td>

                        <!-- Action Buttons -->
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <!-- View Staff Profile Details -->
                                <button type="button" onclick="openViewStaffModal({{ $staff->id }}, '{{ addslashes($staff->name) }}', '{{ addslashes($staff->email) }}', '{{ $staff->role_label }}', '{{ $staff->isSuperAdmin() ? 'Global (All Projects)' : ($staff->projects->pluck('name')->implode(', ') ?: 'None') }}', {{ $staff->activityEntries->count() }}, '{{ $staff->created_at->format('M d, Y') }}')" class="p-1.5 text-slate-500 hover:text-indigo-600 font-bold rounded-lg hover:bg-indigo-50 transition cursor-pointer" title="View Profile">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <!-- Edit Staff -->
                                <button type="button" onclick="openEditStaffModal({{ $staff->id }}, '{{ addslashes($staff->name) }}', '{{ addslashes($staff->email) }}', '{{ $staff->role }}', {{ json_encode($staff->projects->pluck('id')) }})" class="p-1.5 text-slate-500 hover:text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition cursor-pointer" title="Edit Staff Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <!-- Delete Staff (Safe Protection for Self) -->
                                @if(Auth::id() !== $staff->id)
                                <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete staff account for \'{{ addslashes($staff->name) }}\'? This will remove access and unassign from all projects.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Delete Account">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <span class="p-1.5 text-[10px] text-slate-400 font-bold italic" title="Your Account">Active</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 font-medium">
                            No staff members match the specified filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffMembers->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $staffMembers->links() }}
        </div>
        @endif
    </div>

</div>

<!-- ==================== PROVISION STAFF MODAL (CREATE) ==================== -->
<div id="create-staff-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                Provision New Staff Account
            </h3>
            <button type="button" onclick="document.getElementById('create-staff-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm cursor-pointer">✕</button>
        </div>

        <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Kondwani Banda" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                <input type="email" name="email" required placeholder="kondwani@dot.org" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required value="password" placeholder="••••••••" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Assigned Role <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="project_officer">Project Officer</option>
                        <option value="project_assistant">Project Assistant</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Assign to Projects</label>
                <div class="max-h-36 overflow-y-auto p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
                    @foreach($allProjects as $proj)
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-800 cursor-pointer">
                            <input type="checkbox" name="project_ids[]" value="{{ $proj->id }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span>{{ $proj->name }} <span class="text-[10px] text-slate-500">({{ $proj->code ?: 'Project' }})</span></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('create-staff-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs cursor-pointer">Create Account</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== EDIT STAFF MODAL (UPDATE) ==================== -->
<div id="edit-staff-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                Edit Staff Member
            </h3>
            <button type="button" onclick="document.getElementById('edit-staff-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm cursor-pointer">✕</button>
        </div>

        <form id="edit-staff-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-staff-name" name="name" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                <input type="email" id="edit-staff-email" name="email" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">New Password</label>
                    <input type="password" id="edit-staff-password" name="password" placeholder="Leave blank to keep" class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Assigned Role <span class="text-rose-500">*</span></label>
                    <select id="edit-staff-role" name="role" required class="w-full text-xs font-semibold rounded-xl border border-slate-300 p-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="project_officer">Project Officer</option>
                        <option value="project_assistant">Project Assistant</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Assign to Projects</label>
                <div class="max-h-36 overflow-y-auto p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5" id="edit-staff-projects-container">
                    @foreach($allProjects as $proj)
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-800 cursor-pointer">
                            <input type="checkbox" name="project_ids[]" value="{{ $proj->id }}" id="edit-proj-{{ $proj->id }}" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>{{ $proj->name }} <span class="text-[10px] text-slate-500">({{ $proj->code ?: 'Project' }})</span></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('edit-staff-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs cursor-pointer">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== VIEW STAFF PROFILE MODAL (READ) ==================== -->
<div id="view-staff-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                Staff Profile Summary
            </h3>
            <button type="button" onclick="document.getElementById('view-staff-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm cursor-pointer">✕</button>
        </div>

        <div class="space-y-3.5">
            <div class="flex items-center gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80">
                <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 font-black text-base flex items-center justify-center border border-indigo-200" id="view-staff-initials">
                    U
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-black text-slate-900 truncate" id="view-staff-name">Staff Name</h4>
                    <p class="text-xs text-slate-500 truncate" id="view-staff-email">staff@dot.org</p>
                    <span class="inline-block mt-1 px-2 py-0.5 text-[9px] font-black uppercase rounded-md bg-indigo-100 text-indigo-800" id="view-staff-role">Role</span>
                </div>
            </div>

            <div class="space-y-2 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60 space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Assigned Initiatives / Projects</span>
                    <p class="font-medium text-slate-800" id="view-staff-projects">None</p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Activities Logged</span>
                        <div class="text-lg font-black text-slate-900 mt-0.5" id="view-staff-activities">0</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Date Added</span>
                        <div class="text-xs font-bold text-slate-800 mt-1" id="view-staff-created">Jan 01, 2026</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
            <button type="button" onclick="document.getElementById('view-staff-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-100 rounded-xl cursor-pointer">Close</button>
        </div>
    </div>
</div>

<script>
function openEditStaffModal(id, name, email, role, projectIds) {
    document.getElementById('edit-staff-form').action = '/admin/staff/' + id;
    document.getElementById('edit-staff-name').value = name || '';
    document.getElementById('edit-staff-email').value = email || '';
    document.getElementById('edit-staff-password').value = '';
    document.getElementById('edit-staff-role').value = role || 'project_officer';

    const checkboxes = document.querySelectorAll('#edit-staff-projects-container input[type="checkbox"]');
    checkboxes.forEach(cb => {
        cb.checked = projectIds && projectIds.includes(parseInt(cb.value));
    });

    document.getElementById('edit-staff-modal').classList.remove('hidden');
}

function openViewStaffModal(id, name, email, roleLabel, projectsSummary, activityCount, createdDate) {
    document.getElementById('view-staff-initials').textContent = (name || 'U').charAt(0).toUpperCase();
    document.getElementById('view-staff-name').textContent = name || '';
    document.getElementById('view-staff-email').textContent = email || '';
    document.getElementById('view-staff-role').textContent = roleLabel || '';
    document.getElementById('view-staff-projects').textContent = projectsSummary || 'None';
    document.getElementById('view-staff-activities').textContent = activityCount || '0';
    document.getElementById('view-staff-created').textContent = createdDate || '';

    document.getElementById('view-staff-modal').classList.remove('hidden');
}
</script>

@endsection
