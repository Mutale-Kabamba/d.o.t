@extends('layouts.app')

@section('title', 'Staff Profile - ' . $staff->name)

@section('content')
<div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Navigation Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Staff Directory</span>
        </a>

        @if(Auth::id() !== $staff->id)
        <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Delete this staff account?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-transparent hover:border-rose-100 cursor-pointer" title="Delete Staff Account">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
        @endif
    </div>

    <!-- Staff Profile Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl {{ $staff->isSuperAdmin() ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-indigo-100 text-indigo-700 border-indigo-200' }} border flex items-center justify-center font-black text-xl shrink-0">
                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-black text-slate-900 font-display">{{ $staff->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $staff->isSuperAdmin() ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                            {{ $staff->role_label }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $staff->email }}</p>
                </div>
            </div>

            <div class="text-right sm:text-right">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Registered Since</span>
                <span class="text-xs font-bold text-slate-800">{{ $staff->created_at->format('F d, Y') }}</span>
            </div>
        </div>

        <!-- Assigned Projects -->
        <div>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                Assigned Projects &amp; Initiatives
            </h3>

            @if($staff->isSuperAdmin())
                <div class="p-3.5 bg-purple-50 border border-purple-200 rounded-xl text-xs text-purple-800 font-medium">
                    👑 As a Super Administrator, this user has global administrative access to all organizational projects and systems.
                </div>
            @elseif($staff->projects->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($staff->projects as $proj)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">{{ $proj->name }}</span>
                            <span class="text-[10px] font-black uppercase text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">{{ $proj->code ?: 'PROJ' }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500">{{ $proj->location ?: 'Livingstone' }} ● <span class="{{ $proj->status === 'active' ? 'text-emerald-600' : 'text-slate-400' }}">{{ ucfirst($proj->status) }}</span></p>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 italic">No projects currently assigned to this personnel.</p>
            @endif
        </div>

        <!-- Recent Activity Entries -->
        <div class="pt-4 border-t border-slate-100">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                Recent Logged Continuous Activities ({{ $staff->activityEntries->count() }})
            </h3>

            @if($staff->activityEntries->isNotEmpty())
                <div class="space-y-2">
                    @foreach($staff->activityEntries->take(5) as $entry)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70 flex items-center justify-between gap-3 text-xs">
                        <div class="min-w-0">
                            <div class="font-bold text-slate-900 truncate">{{ $entry->activity_title }}</div>
                            <div class="text-[11px] text-slate-400">{{ $entry->project->name ?? 'Project' }} ● {{ \Carbon\Carbon::parse($entry->activity_date)->format('M d, Y') }}</div>
                        </div>
                        <a href="{{ route('programmes.activities.show', $entry->token) }}" class="px-2.5 py-1 text-[11px] font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition shrink-0">
                            View →
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 italic">No activity entries recorded yet by this staff member.</p>
            @endif
        </div>
    </div>

</div>
@endsection
