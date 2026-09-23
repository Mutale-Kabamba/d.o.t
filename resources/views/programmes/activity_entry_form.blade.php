@extends('layouts.app')

@section('title', ($isEdit ? 'Edit Activity Entry' : 'Log New Activity') . ' - Play It Forward Zambia')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 antialiased font-sans">
    <!-- Top Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('programmes.hub') }}" class="w-9 h-9 rounded-lg bg-blue-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-xs hover:bg-blue-700 transition">
                    P
                </a>
                <div class="flex items-center gap-2.5 truncate">
                    <span class="text-base font-bold text-slate-900 truncate">Play It Forward Zambia</span>
                    <span class="text-[11px] font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full shrink-0">
                        {{ $isEdit ? 'Edit Activity Log' : 'Continuous Activity Logging' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('programmes.hub') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition shadow-xs">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Back to Hub</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Form Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                {{ $isEdit ? 'Update Activity Entry' : 'Log New Activity Entry' }}
            </h1>
            <p class="text-xs text-slate-600 mt-1">
                Capture key outcomes immediately at the conclusion of every activity. Dual-field recording ensures short bullets for slide decks and detailed narrative for full master reports.
            </p>
        </div>

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-xs mb-6 shadow-xs">
            <div class="font-bold mb-1 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Please correct the following errors:
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ $isEdit ? route('programmes.activities.update', $activity->token) : route('programmes.activities.store') }}" method="POST" class="space-y-8">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- Section 1: Activity Metadata -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Activity Overview & Project Scoping
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Assigned Project <span class="text-rose-500">*</span>
                        </label>
                        <select name="project_id" required class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ (old('project_id', $activity->project_id ?? $preselectedProject->id ?? '') == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Activity Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="activity_title" value="{{ old('activity_title', $activity->activity_title ?? '') }}" placeholder="e.g. Weekly Health Match & Coach Clinic" required class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Activity Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="activity_date" value="{{ old('activity_date', $activity->activity_date ?? now()->toDateString()) }}" required class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Activity Location / Pitch / Venue
                        </label>
                        <input type="text" name="location" value="{{ old('location', $activity->location ?? '') }}" placeholder="e.g. Maramba Community Ground / School Hall" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Reporting Period
                        </label>
                        <input type="text" name="reporting_period" value="{{ old('reporting_period', $activity->reporting_period ?? 'Quarter 2 April, May, June 2026') }}" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                    </div>
                </div>
            </div>

            <!-- Section 2: 5 Mandatory Thematic Pillars (Dual-Field Structure) -->
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        Qualitative Data: 5 Thematic Pillars
                    </h2>
                    <span class="text-xs text-slate-500 font-medium">Dual-Field: Bullets for Slides + Full Narrative</span>
                </div>

                @foreach($slidesConfig as $themeKey => $theme)
                @php
                    $pField = $theme['points_field'];
                    $nField = $theme['narrative_field'];
                    $defaultPts = old($pField, $activity->{$pField} ?? '');
                    $defaultNarrative = old($nField, $activity->{$nField} ?? '');
                @endphp
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-5">
                    <!-- Pillar Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black text-sm flex items-center justify-center shrink-0 shadow-xs">
                                {{ $theme['number'] }}
                            </span>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900">{{ $theme['title'] }}</h3>
                                <p class="text-[11px] text-slate-500">Record distinct entries across each of the 3 respective thematic focus areas below</p>
                            </div>
                        </div>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-full shrink-0">
                            Thematic Pillar {{ $theme['number'] }} of 5
                        </span>
                    </div>

                    <!-- 3 Dedicated Respective Focus Section Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($theme['items'] as $itemKey => $item)
                        @php
                            $subValue = old($itemKey, $activity->{$itemKey} ?? '');
                        @endphp
                        <div class="bg-slate-50/80 border border-slate-200 rounded-xl p-3.5 flex flex-col justify-between space-y-2.5 focus-within:border-blue-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition">
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5 font-bold text-slate-900 text-xs">
                                    <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                                    <span>{{ $item['title'] }}</span>
                                </div>
                                <p class="text-[11px] text-slate-600 leading-snug">{{ $item['prompt'] }}</p>
                            </div>

                            <div>
                                <label class="sr-only">{{ $item['title'] }}</label>
                                <textarea name="{{ $itemKey }}" rows="4" placeholder="{{ $item['placeholder'] }}" class="w-full text-xs font-medium rounded-lg border-slate-300 bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border leading-relaxed">{{ $subValue }}</textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Detailed Narrative Explanation (Full Master Report) -->
                    <div class="bg-blue-50/40 border border-blue-100 rounded-xl p-4 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-blue-950 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Detailed Narrative & Comprehensive Qualitative Summary</span>
                            </label>
                            <span class="text-[10px] uppercase font-bold text-blue-700 bg-white border border-blue-200 px-2 py-0.5 rounded">Master Report</span>
                        </div>
                        <p class="text-[11px] text-slate-600">Comprehensive qualitative overview synthesizing context, observations, and community feedback for annual reporting and donors.</p>
                        <textarea name="{{ $nField }}" rows="3" placeholder="Provide full qualitative context, participant quotes, operational adaptations, and donor-ready reflections..." class="w-full text-xs font-medium rounded-lg border-slate-300 bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border leading-relaxed">{{ $defaultNarrative }}</textarea>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Submit Button Bar -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('programmes.hub') }}" class="px-5 py-2.5 text-xs font-bold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-300 rounded-xl transition shadow-xs">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/10 transition cursor-pointer flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ $isEdit ? 'Update Activity Entry' : 'Save Activity Entry' }}</span>
                </button>
            </div>
        </form>
    </main>
</div>
@endsection
