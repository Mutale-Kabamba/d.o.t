@extends('layouts.app')

@section('title', $activity->activity_title . ' - Activity Log Details')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 antialiased font-sans py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Top Back Navigation & Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <a href="{{ route('programmes.hub') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition shadow-2xs group w-fit">
                <svg class="w-4 h-4 text-slate-500 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Programmes Hub</span>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('programmes.activities.edit', $activity->token) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit Activity</span>
                </a>

                <form action="{{ route('programmes.activities.destroy', $activity->token) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this activity entry?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-xl transition shadow-2xs cursor-pointer" title="Delete Activity">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Delete</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Activity Overview Hero Banner -->
        <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs relative overflow-hidden space-y-4">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500"></div>

            <!-- Project Tag & Granularity -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80 rounded-full shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                    {{ $activity->project->name ?? 'General Project' }} @if(!empty($activity->project->code))({{ $activity->project->code }})@endif
                </span>
                @if(!empty($activity->period_granularity))
                <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] font-bold text-slate-600 bg-slate-100 border border-slate-200/90 rounded-full uppercase tracking-wider">
                    {{ ucfirst($activity->period_granularity) }} Log
                </span>
                @endif
            </div>

            <!-- Main Activity Title -->
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-snug">
                {{ $activity->activity_title }}
            </h1>

            <!-- Metadata Chips Row -->
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 pt-3 border-t border-slate-100 text-xs text-slate-600 font-medium">
                <!-- Date -->
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Logged for <strong class="text-slate-900 font-bold">{{ \Carbon\Carbon::parse($activity->activity_date)->format('F j, Y') }}</strong></span>
                </div>

                <!-- Location -->
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $activity->location ?: 'Livingstone, Zambia' }}</span>
                </div>

                @if(!empty($activity->reporting_period))
                <!-- Reporting Period -->
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-purple-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>{{ $activity->reporting_period }}</span>
                </div>
                @endif

                @if($activity->user)
                <!-- Logged By -->
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Submitted by <strong class="text-slate-800 font-bold">{{ $activity->user->name }}</strong></span>
                </div>
                @endif
            </div>
        </div>

        <!-- 5 Thematic Pillars Breakdown -->
        <div class="space-y-6">
            @foreach($slidesConfig as $themeKey => $theme)
            @php
                $points = $activity->getPoints($theme['points_field']);
                $narrative = $activity->{$theme['narrative_field']};
            @endphp
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black text-sm flex items-center justify-center shrink-0">
                            {{ $theme['number'] }}
                        </span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">{{ $theme['title'] }}</h2>
                            <p class="text-xs text-slate-500">Thematic Pillar {{ $theme['number'] }} breakdown</p>
                        </div>
                    </div>
                </div>

                <!-- 3 Respective Focus Sections -->
                @php
                    $itemSubKeyMap = [
                        'achievements_milestones' => 'milestones',
                        'achievements_impact' => 'impact',
                        'achievements_stories' => 'stories',
                        'challenges_operational' => 'operational',
                        'challenges_resources' => 'resources',
                        'challenges_risks' => 'risks',
                        'learning_lessons' => 'lessons',
                        'learning_feedback' => 'feedback',
                        'learning_innovation' => 'innovation',
                        'mne_performance' => 'performance',
                        'mne_data_quality' => 'data_quality',
                        'mne_evaluation_plans' => 'evaluation',
                        'collab_projects' => 'project_collab',
                        'collab_partnerships' => 'partnerships',
                        'collab_cross_learning' => 'cross_learning',
                    ];
                @endphp

                @php
                    $hasAnySub = false;
                    foreach($theme['items'] as $itemKey => $item) {
                        $subKey = $itemSubKeyMap[$itemKey] ?? $itemKey;
                        $subPts = $activity->getPillarBullets($theme['number'], $subKey);
                        if (!empty($subPts) || !empty($activity->{$itemKey}) || !empty($activity->getSubPillarNarrative($theme['number'], $subKey))) {
                            $hasAnySub = true;
                            break;
                        }
                    }
                @endphp

                @if($hasAnySub)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($theme['items'] as $itemKey => $item)
                    @php
                        $subKey = $itemSubKeyMap[$itemKey] ?? $itemKey;
                        $subPoints = $activity->getPillarBullets($theme['number'], $subKey);
                        if (empty($subPoints) && !empty($activity->{$itemKey})) {
                            $subPoints = \App\Models\ActivityEntry::extractBulletPoints($activity->{$itemKey});
                        }
                        $subNarrative = $activity->getSubPillarNarrative($theme['number'], $subKey);
                    @endphp
                    <div class="bg-slate-50/80 border border-slate-200 rounded-xl p-4 flex flex-col justify-between space-y-3">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 font-bold text-slate-900 text-xs">
                                    <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                                    <span>{{ $item['title'] }}</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400">{{ count($subPoints) }} points</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-snug">{{ $item['prompt'] }}</p>

                            <div class="pt-2 border-t border-slate-200/60">
                                @if(!empty($subPoints))
                                    <ul class="space-y-1.5 list-disc list-inside text-xs text-slate-800 font-medium">
                                        @foreach($subPoints as $pt)
                                            <li class="leading-relaxed">
                                                {!! \App\Models\ActivityEntry::formatPointHtml($pt) !!}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-xs text-slate-400 italic">No bullet points recorded.</p>
                                @endif
                            </div>
                        </div>

                        @if(!empty($subNarrative))
                        <div class="pt-2.5 border-t border-slate-200/80 bg-white/70 p-2.5 rounded-lg border border-slate-200/60 space-y-1">
                            <div class="flex items-center gap-1 text-[10.5px] font-bold text-blue-900 uppercase tracking-wider">
                                <svg class="w-3 h-3 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span>Narrative &amp; Qualitative Summary</span>
                            </div>
                            <p class="text-[11.5px] text-slate-700 leading-relaxed whitespace-pre-line">{{ $subNarrative }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @elseif(!empty($points))
                <div class="bg-blue-50/40 p-4 rounded-xl border border-blue-100 space-y-2">
                    <div class="text-xs font-bold text-blue-900 uppercase tracking-wider">
                        Key Presentation Points
                    </div>
                    <ul class="space-y-1.5 list-disc list-inside text-xs text-slate-800 font-medium">
                        @foreach($points as $pt)
                            <li class="leading-relaxed">
                                {!! \App\Models\ActivityEntry::formatPointHtml($pt) !!}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Comprehensive Narrative -->
                @if(!empty($narrative))
                <div class="bg-blue-50/40 p-4 rounded-xl border border-blue-100 space-y-1.5">
                    <div class="text-xs font-bold text-blue-900 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Detailed Qualitative Narrative</span>
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $narrative }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
