<div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
    <!-- Identity & Title -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-lg shadow-md shrink-0">
            <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase bg-brand-50 text-brand-700 px-2 py-0.5 rounded border border-brand-200">Admin Portal</span>
                <span class="text-xs text-slate-400 font-medium">Logged in as: <strong class="text-slate-700">{{ Auth::user()->name ?? 'Administrator' }}</strong></span>
            </div>
            <h1 class="text-lg sm:text-xl font-black text-slate-900 font-display">
                @if(($activeTab ?? '') === 'staff')
                    Staff &amp; Personnel Management
                @elseif(($activeTab ?? '') === 'teams')
                    Teams &amp; Project Initiatives
                @else
                    Cohort Submissions &amp; Reports
                @endif
            </h1>
        </div>
    </div>

    <!-- Navigation Tabs & Actions -->
    <div class="flex flex-wrap items-center gap-2">
        <!-- Admin Section Tabs -->
        <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/60">
            <a href="{{ route('admin.submissions.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ ($activeTab ?? '') === 'submissions' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Submissions</span>
            </a>

            <a href="{{ route('admin.staff.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ ($activeTab ?? '') === 'staff' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Team &amp; Staff</span>
            </a>

            <a href="{{ route('admin.teams.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ ($activeTab ?? '') === 'teams' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Teams &amp; Projects</span>
            </a>
        </div>

        <!-- Link to Programmes Hub -->
        <a href="{{ route('programmes.hub') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition shadow-xs">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Project Hub</span>
        </a>

        <!-- Sign Out -->
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
