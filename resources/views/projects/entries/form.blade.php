@extends('layouts.app')

@section('title', ($isEdit ? 'Edit Activity Entry' : 'Log Thematic Activity') . ' - Play It Forward Zambia')

@section('content')
@php
// Helper to resolve initial bullet points and narratives for edit vs new
$resolvePoints = function(int $pNum, string $subKey, string $oldKey, $activity) {
    $oldVal = old($oldKey . '.points', old($oldKey));
    if ($oldVal !== null) {
        if (is_array($oldVal)) {
            $filtered = array_values(array_filter(array_map('trim', $oldVal), fn($v) => $v !== ''));
            return !empty($filtered) ? $filtered : [''];
        }
        $pts = \App\Models\ActivityEntry::extractBulletPoints((string)$oldVal);
        return !empty($pts) ? $pts : [''];
    }
    if ($activity) {
        $pts = $activity->getPillarBullets($pNum, $subKey);
        if (!empty($pts)) {
            return $pts;
        }
    }
    return [''];
};

$resolveNarrative = function(int $pNum, string $subKey, string $oldKey, $activity) {
    $oldVal = old($oldKey . '.narrative');
    if ($oldVal !== null) {
        return (string)$oldVal;
    }
    if ($activity) {
        return $activity->getSubPillarNarrative($pNum, $subKey);
    }
    return '';
};

$resolvePillarNarrative = function(int $pNum, string $pillarKey, string $flatKey, $activity, array $subKeys = []) {
    $oldVal = old($pillarKey, old($flatKey));
    if ($oldVal !== null) {
        return (string)$oldVal;
    }
    if ($activity) {
        $pNarrative = $activity->getPillarNarrative($pNum);
        if (!empty($pNarrative)) {
            return $pNarrative;
        }
        if (!empty($activity->{$flatKey})) {
            return $activity->{$flatKey};
        }
        $subNarratives = [];
        foreach ($subKeys as $sk) {
            $sub = $activity->getSubPillarNarrative($pNum, $sk);
            if (!empty($sub)) {
                $subNarratives[] = $sub;
            }
        }
        if (!empty($subNarratives)) {
            return implode("\n\n", $subNarratives);
        }
    }
    return '';
};

$initialPillars = [
    1 => [
        'milestones' => [
            'points' => $resolvePoints(1, 'milestones', 'pillar_1_achievements.milestones', $activity),
            'narrative' => $resolveNarrative(1, 'milestones', 'pillar_1_achievements.milestones', $activity),
        ],
        'impact' => [
            'points' => $resolvePoints(1, 'impact', 'pillar_1_achievements.impact', $activity),
            'narrative' => $resolveNarrative(1, 'impact', 'pillar_1_achievements.impact', $activity),
        ],
        'stories' => [
            'points' => $resolvePoints(1, 'stories', 'pillar_1_achievements.stories', $activity),
            'narrative' => $resolveNarrative(1, 'stories', 'pillar_1_achievements.stories', $activity),
        ],
        'narrative' => $resolvePillarNarrative(1, 'pillar_1_narrative', 'achievements_narrative', $activity, ['milestones', 'impact', 'stories']),
    ],
    2 => [
        'operational' => [
            'points' => $resolvePoints(2, 'operational', 'pillar_2_challenges.operational', $activity),
            'narrative' => $resolveNarrative(2, 'operational', 'pillar_2_challenges.operational', $activity),
        ],
        'resources' => [
            'points' => $resolvePoints(2, 'resources', 'pillar_2_challenges.resources', $activity),
            'narrative' => $resolveNarrative(2, 'resources', 'pillar_2_challenges.resources', $activity),
        ],
        'risks' => [
            'points' => $resolvePoints(2, 'risks', 'pillar_2_challenges.risks', $activity),
            'narrative' => $resolveNarrative(2, 'risks', 'pillar_2_challenges.risks', $activity),
        ],
        'narrative' => $resolvePillarNarrative(2, 'pillar_2_narrative', 'challenges_narrative', $activity, ['operational', 'resources', 'risks']),
    ],
    3 => [
        'lessons' => [
            'points' => $resolvePoints(3, 'lessons', 'pillar_3_learning.lessons', $activity),
            'narrative' => $resolveNarrative(3, 'lessons', 'pillar_3_learning.lessons', $activity),
        ],
        'feedback' => [
            'points' => $resolvePoints(3, 'feedback', 'pillar_3_learning.feedback', $activity),
            'narrative' => $resolveNarrative(3, 'feedback', 'pillar_3_learning.feedback', $activity),
        ],
        'innovation' => [
            'points' => $resolvePoints(3, 'innovation', 'pillar_3_learning.innovation', $activity),
            'narrative' => $resolveNarrative(3, 'innovation', 'pillar_3_learning.innovation', $activity),
        ],
        'narrative' => $resolvePillarNarrative(3, 'pillar_3_narrative', 'learning_narrative', $activity, ['lessons', 'feedback', 'innovation']),
    ],
    4 => [
        'performance' => [
            'points' => $resolvePoints(4, 'performance', 'pillar_4_monitoring.performance', $activity),
            'narrative' => $resolveNarrative(4, 'performance', 'pillar_4_monitoring.performance', $activity),
        ],
        'data_quality' => [
            'points' => $resolvePoints(4, 'data_quality', 'pillar_4_monitoring.data_quality', $activity),
            'narrative' => $resolveNarrative(4, 'data_quality', 'pillar_4_monitoring.data_quality', $activity),
        ],
        'evaluation' => [
            'points' => $resolvePoints(4, 'evaluation', 'pillar_4_monitoring.evaluation', $activity),
            'narrative' => $resolveNarrative(4, 'evaluation', 'pillar_4_monitoring.evaluation', $activity),
        ],
        'narrative' => $resolvePillarNarrative(4, 'pillar_4_narrative', 'mne_narrative', $activity, ['performance', 'data_quality', 'evaluation']),
    ],
    5 => [
        'project_collab' => [
            'points' => $resolvePoints(5, 'project_collab', 'pillar_5_collaboration.project_collab', $activity),
            'narrative' => $resolveNarrative(5, 'project_collab', 'pillar_5_collaboration.project_collab', $activity),
        ],
        'partnerships' => [
            'points' => $resolvePoints(5, 'partnerships', 'pillar_5_collaboration.partnerships', $activity),
            'narrative' => $resolveNarrative(5, 'partnerships', 'pillar_5_collaboration.partnerships', $activity),
        ],
        'cross_learning' => [
            'points' => $resolvePoints(5, 'cross_learning', 'pillar_5_collaboration.cross_learning', $activity),
            'narrative' => $resolveNarrative(5, 'cross_learning', 'pillar_5_collaboration.cross_learning', $activity),
        ],
        'narrative' => $resolvePillarNarrative(5, 'pillar_5_narrative', 'collab_narrative', $activity, ['project_collab', 'partnerships', 'cross_learning']),
    ],
];

// Ensure every bullet category has points array with at least one element if empty
foreach ($initialPillars as $pNum => &$pData) {
    foreach ($pData as $k => &$sub) {
        if ($k !== 'narrative' && is_array($sub)) {
            if (!isset($sub['points']) || !is_array($sub['points']) || empty($sub['points'])) {
                $sub['points'] = [''];
            }
            if (!isset($sub['narrative'])) {
                $sub['narrative'] = '';
            }
        }
    }
}
unset($pData, $sub);

$rawLocation = (string)old('location', $activity->location ?? '');
if (str_starts_with($rawLocation, 'http://') || str_starts_with($rawLocation, 'https://')) {
    $rawLocation = '';
}
$rawActivityType = (string)old('activity_type', $activity->activity_type ?? 'activity');
$rawPeriodType = (string)old('period_type', $activity->period_type ?? 'single_day');
$rawStartDate = (string)old('start_date', (isset($activity) && $activity && $activity->start_date ? $activity->start_date->toDateString() : (isset($activity) && $activity && $activity->activity_date ? $activity->activity_date->toDateString() : now()->toDateString())));
$rawEndDate = (string)old('end_date', (isset($activity) && $activity && $activity->end_date ? $activity->end_date->toDateString() : ''));
$rawPeriodCadence = (string)old('period_cadence', $activity->period_cadence ?? '');
$isOngoingType = in_array(strtolower($rawActivityType), ['training', 'class', 'session']);

$entryFormConfig = [
    'isEdit' => (bool)$isEdit,
    'activityToken' => (string)($activity->token ?? ''),
    'projectId' => (string)($activity->project_id ?? $preselectedProject->id ?? ''),
    'activityTitle' => (string)old('activity_title', $activity->activity_title ?? ''),
    'activityType' => $rawActivityType,
    'activityDate' => $rawStartDate,
    'activityEndDate' => $rawEndDate,
    'activityLocation' => $rawLocation,
    'initialPillars' => $initialPillars,
];
@endphp

<div class="min-h-screen bg-slate-50 text-slate-800 antialiased font-sans pb-16">

    <!-- Top Sticky Header & Global Stepper Info -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('programmes.hub') }}" class="w-9 h-9 rounded-lg bg-blue-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-xs hover:bg-blue-700 transition">
                    P
                </a>
                <div class="flex items-center gap-2.5 truncate">
                    <span class="text-base font-bold text-slate-900 truncate">Play It Forward Zambia</span>
                    <span class="text-[11px] font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-full shrink-0">
                        {{ $isEdit ? 'Edit Activity Entry' : 'Thematic Pillar Activity Log' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <!-- Auto-Save Status Pill -->
                <div class="hidden sm:flex items-center gap-1.5 text-xs text-slate-500 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg font-medium">
                    <span id="draft-status-indicator" class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span id="draft-status-text">Draft ready</span>
                </div>

                <a href="{{ route('programmes.hub') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition shadow-xs">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Back to Hub</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-6 space-y-6">

        @php
            $backUrl = route('programmes.hub');
            if (!empty($programme)) {
                $backUrl = route('programmes.activities.index', $programme->slug);
            } elseif (!empty($project)) {
                $backUrl = route('projects.entries.index', $project);
            }
        @endphp

        <div class="flex items-center justify-between">
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-blue-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs shadow-xs">
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

        <form id="activity-entry-form" action="{{ $isEdit ? route('programmes.activities.update', $activity->token) : route('programmes.activities.store') }}" method="POST" class="space-y-6">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- 1. Activity Overview & Metadata Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        Activity Overview &amp; Scoping
                    </h2>
                    <span id="activity-nature-pill" class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $isOngoingType ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                        {{ $isOngoingType ? 'Ongoing / Multi-Day Schedule' : 'Single-Day Activity' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Project Selector (grouped by Departments and Sub-projects) -->
                    <div class="md:col-span-12">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Assigned Project / Department <span class="text-rose-500">*</span>
                        </label>
                        @php
                            $selectedProjId = old('project_id', $activity->project_id ?? $preselectedProject->id ?? '');
                            $departments = $projects->filter(fn($p) => $p->isDepartment() || $p->children->isNotEmpty());
                            $deptIds = [];
                            foreach ($departments as $d) {
                                $deptIds[] = $d->id;
                                foreach ($d->children as $c) {
                                    $deptIds[] = $c->id;
                                }
                            }
                            $otherProjects = $projects->filter(fn($p) => !in_array($p->id, $deptIds));
                        @endphp
                        <select name="project_id" id="activity_project_id" required class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                            @foreach($departments as $dept)
                                <optgroup label="🏢 Department: {{ $dept->name }}">
                                    <option value="{{ $dept->id }}" {{ $selectedProjId == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} (Department Level / General)
                                    </option>
                                    @foreach($dept->children as $child)
                                        <option value="{{ $child->id }}" {{ $selectedProjId == $child->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }} ({{ $child->code ?: 'PIFZ' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach

                            @if($otherProjects->isNotEmpty())
                                <optgroup label="📁 Projects &amp; Initiatives">
                                    @foreach($otherProjects as $p)
                                        <option value="{{ $p->id }}" {{ $selectedProjId == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->code ?: 'PIFZ' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>

                    <!-- Type of Activity Selector -->
                    <div class="md:col-span-12">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>Type of Activity <span class="text-rose-500">*</span></span>
                            <span class="text-[10px] text-slate-400 font-medium">Select type to configure duration and venue / location</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Option 1: Activity (Single Day) -->
                            <label class="activity-type-btn cursor-pointer p-3.5 rounded-xl border transition flex flex-col justify-between gap-1.5 {{ !$isOngoingType ? 'border-blue-500 bg-blue-50/60 shadow-xs ring-2 ring-blue-500/20' : 'border-slate-200 bg-slate-50 hover:bg-white' }}" data-type="activity">
                                <div class="flex items-center justify-between">
                                    <span class="font-extrabold text-xs sm:text-sm text-slate-900 flex items-center gap-2">
                                        ⚡ Activity <span class="text-[10px] font-semibold text-blue-700 bg-blue-100/80 px-2 py-0.5 rounded-full">Single Day</span>
                                    </span>
                                    <input type="radio" name="activity_type" id="activity_type_single" value="activity" {{ !$isOngoingType ? 'checked' : '' }} onchange="handleActivityTypeChange('activity')" class="rounded-full text-blue-600 focus:ring-blue-500">
                                </div>
                                <p class="text-[11px] text-slate-500 leading-normal">
                                    Single-day event, sports match, meeting, or outreach. Uses <strong class="text-slate-700">Venue</strong> and a single date.
                                </p>
                            </label>

                            <!-- Option 2: Ongoing (Sessions, Classes, Trainings) -->
                            <label class="activity-type-btn cursor-pointer p-3.5 rounded-xl border transition flex flex-col justify-between gap-1.5 {{ $isOngoingType ? 'border-purple-500 bg-purple-50/60 shadow-xs ring-2 ring-purple-500/20' : 'border-slate-200 bg-slate-50 hover:bg-white' }}" data-type="ongoing">
                                <div class="flex items-center justify-between">
                                    <span class="font-extrabold text-xs sm:text-sm text-slate-900 flex items-center gap-2">
                                        📅 Sessions, Classes &amp; Trainings <span class="text-[10px] font-semibold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-full">Multi-Day / Period</span>
                                    </span>
                                    <input type="radio" name="activity_type" id="activity_type_ongoing" value="{{ in_array($rawActivityType, ['training', 'class', 'session']) ? $rawActivityType : 'training' }}" {{ $isOngoingType ? 'checked' : '' }} onchange="handleActivityTypeChange('ongoing')" class="rounded-full text-purple-600 focus:ring-purple-500">
                                </div>
                                <p class="text-[11px] text-slate-500 leading-normal">
                                    Ongoing engagements across a period (bootcamps, courses, series). Uses <strong class="text-slate-700">Location</strong> and <strong class="text-slate-700">Period</strong>.
                                </p>
                            </label>
                        </div>
                    </div>

                    <!-- Row 3: Activity Title -->
                    <div class="md:col-span-7">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            <span id="activity-title-label">Activity Title</span> <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="activity_title" id="activity_title_input" value="{{ old('activity_title', $activity->activity_title ?? '') }}" placeholder="e.g. Weekly Health Match &amp; Coach Clinic" required class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                    </div>

                    <!-- Venue (for activities) or Location (for ongoing sessions/trainings/classes) -->
                    <div class="md:col-span-5">
                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span id="venue-location-label">{{ $isOngoingType ? 'Location' : 'Venue' }}</span>
                            <span id="venue-location-hint" class="text-[10px] font-normal text-slate-400">
                                {{ $isOngoingType ? 'Facility / Lab Room / Online' : 'Event Grounds / Stadium' }}
                            </span>
                        </label>
                        <input type="text" name="location" id="activity_location_input" value="{{ $rawLocation }}" placeholder="{{ $isOngoingType ? 'e.g. Digital Skills Innovation Hub - Lab 1, Livingstone or Online' : 'e.g. Maramba Community Grounds, Livingstone' }}" autocomplete="off" data-lpignore="true" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                    </div>

                    <!-- Single Day Container -->
                    <div id="single-day-period-container" class="md:col-span-12 {{ $isOngoingType ? 'hidden' : '' }}">
                        <div class="bg-blue-50/40 border border-blue-100 rounded-xl p-3.5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <div class="space-y-0.5">
                                <label class="block text-xs font-bold text-slate-800">
                                    Activity Date <span class="text-rose-500">*</span>
                                </label>
                                <p class="text-[11px] text-slate-500">The specific day this activity was executed.</p>
                            </div>
                            <div class="w-full sm:w-64">
                                <input type="date" name="activity_date" id="activity_date_input" value="{{ $rawStartDate }}" required class="w-full text-xs font-bold rounded-xl border-slate-300 bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                            </div>
                        </div>
                    </div>

                    <!-- Multi-Day / Ongoing Period Container -->
                    <div id="ongoing-period-container" class="md:col-span-12 {{ $isOngoingType ? '' : 'hidden' }} space-y-3">
                        <div class="bg-purple-50/50 border border-purple-100 rounded-2xl p-4 space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-purple-100 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-black text-purple-900 uppercase tracking-wider">🗓️ Period &amp; Schedule Selection</span>
                                    <span class="text-[10px] font-semibold text-purple-700 bg-purple-100/70 px-2 py-0.5 rounded-md">Ongoing Course / Series</span>
                                </div>
                                <div id="period-duration-badge" class="text-xs font-bold px-2.5 py-1 rounded-xl bg-purple-600 text-white shadow-xs">
                                    Duration: Calculating...
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Period Start Date <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" name="start_date" id="start_date_input" value="{{ $rawStartDate }}" onchange="syncDates()" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white focus:border-purple-500 focus:ring-purple-500 p-2.5 border">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Period End Date <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" name="end_date" id="end_date_input" value="{{ $rawEndDate }}" onchange="syncDates()" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white focus:border-purple-500 focus:ring-purple-500 p-2.5 border">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Cadence / Schedule Pattern
                                    </label>
                                    <input type="text" name="period_cadence" id="period_cadence_input" value="{{ $rawPeriodCadence }}" placeholder="e.g. Weekly (4 Weeks) or Mon/Wed/Fri" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white focus:border-purple-500 focus:ring-purple-500 p-2.5 border">
                                </div>
                            </div>

                            <!-- Optional Specific Subtype -->
                            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-purple-100/80 text-xs">
                                <span class="text-[11px] font-bold text-purple-900">Activity Format:</span>
                                <div class="inline-flex items-center gap-1.5" id="ongoing-format-pills">
                                    <button type="button" onclick="setOngoingSubtype('training')" id="subtype-btn-training" class="cursor-pointer px-2.5 py-1 rounded-lg text-xs font-semibold transition border {{ (!in_array($rawActivityType, ['class', 'session'])) ? 'bg-purple-600 text-white border-purple-600 shadow-2xs' : 'bg-white text-purple-800 border-purple-200 hover:bg-purple-50' }}">
                                        🎓 Training
                                    </button>
                                    <button type="button" onclick="setOngoingSubtype('class')" id="subtype-btn-class" class="cursor-pointer px-2.5 py-1 rounded-lg text-xs font-semibold transition border {{ ($rawActivityType === 'class') ? 'bg-purple-600 text-white border-purple-600 shadow-2xs' : 'bg-white text-purple-800 border-purple-200 hover:bg-purple-50' }}">
                                        📚 Class
                                    </button>
                                    <button type="button" onclick="setOngoingSubtype('session')" id="subtype-btn-session" class="cursor-pointer px-2.5 py-1 rounded-lg text-xs font-semibold transition border {{ ($rawActivityType === 'session') ? 'bg-purple-600 text-white border-purple-600 shadow-2xs' : 'bg-white text-purple-800 border-purple-200 hover:bg-purple-50' }}">
                                        👥 Session
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden compatibility elements for legacy scripts & counters -->
            <div class="hidden" aria-hidden="true" style="display: none !important;">
                <div id="pillar-progress-bar"></div>
                <button type="button" id="btn-prev-pillar"></button>
                <button type="button" id="btn-next-pillar"><span id="btn-next-pillar-label"></span></button>
                <div id="pillar-tab-1" data-active-class="" data-inactive-class=""></div>
                <div id="pillar-tab-2" data-active-class="" data-inactive-class=""></div>
                <div id="pillar-tab-3" data-active-class="" data-inactive-class=""></div>
                <div id="pillar-tab-4" data-active-class="" data-inactive-class=""></div>
                <div id="pillar-tab-5" data-active-class="" data-inactive-class=""></div>
                <span id="pillar-tab-count-1"></span>
                <span id="pillar-tab-count-2"></span>
                <span id="pillar-tab-count-3"></span>
                <span id="pillar-tab-count-4"></span>
                <span id="pillar-tab-count-5"></span>
            </div>

            <!-- ==================== PILLAR 1: PROJECT ACHIEVEMENTS ==================== -->
            <div id="pillar-card-1" class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4 transition-all duration-200">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-blue-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                1
                            </span>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">Pillar 1: Project Achievements</h3>
                        </div>
                        <p class="text-xs text-slate-500 max-w-2xl">Capture discrete milestones, quantitative impact evidence, and positive human stories.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                        <button type="button"
                                onclick="togglePillarEdit(1)"
                                id="btn-toggle-pillar-1"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition shadow-2xs cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <span id="btn-toggle-label-1">Collapse</span>
                        </button>
                    </div>
                </div>

                <!-- Status / Preview summary (visible when collapsed) -->
                <div id="pillar-summary-1" class="text-xs text-slate-400 italic hidden">
                    Not yet filled
                </div>

                <!-- Editing panel (progressive disclosure) -->
                <div id="pillar-edit-area-1" class="space-y-6 pt-4 border-t border-slate-100">
                    <div id="pillar-panel-1" class="space-y-6">
                                                <!-- 3 Dynamic Sub-Focus Point Section Cards (Pop up modal on Update) -->
                        <div class="space-y-4">
                            <!-- Sub-Section: 1. Key Milestones -->
                            <div id="point-card-1-milestones" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">1. Key Milestones</h4>
                                            <span id="badge-count-1-milestones" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Major operational, structural, or program activities completed this cycle.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(1, 'milestones')"
                                                id="btn-open-point-1-milestones"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-1-milestones" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-1-milestones" class="hidden">
                                    @foreach($initialPillars[1]['milestones']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_1_achievements[milestones][points][]" value="{{ $val }}" class="bullet-input" data-pillar="1" data-key="milestones">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_1_achievements[milestones][narrative]" value="{{ $initialPillars[1]['milestones']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 2. Impact Evidence -->
                            <div id="point-card-1-impact" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">2. Impact Evidence</h4>
                                            <span id="badge-count-1-impact" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Tangible changes, participant numbers, percentages (e.g. 98% passed), or survey data.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(1, 'impact')"
                                                id="btn-open-point-1-impact"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-1-impact" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-1-impact" class="hidden">
                                    @foreach($initialPillars[1]['impact']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_1_achievements[impact][points][]" value="{{ $val }}" class="bullet-input" data-pillar="1" data-key="impact">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_1_achievements[impact][narrative]" value="{{ $initialPillars[1]['impact']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 3. Success Stories -->
                            <div id="point-card-1-stories" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">3. Success Stories</h4>
                                            <span id="badge-count-1-stories" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Individual or community transformation highlights, quotes, and peer role model wins.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(1, 'stories')"
                                                id="btn-open-point-1-stories"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-1-stories" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-1-stories" class="hidden">
                                    @foreach($initialPillars[1]['stories']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_1_achievements[stories][points][]" value="{{ $val }}" class="bullet-input" data-pillar="1" data-key="stories">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_1_achievements[stories][narrative]" value="{{ $initialPillars[1]['stories']['narrative'] }}">
                            </div>
                        </div>

                        <!-- Pillar 1 Detailed Summary (Qualitative Narrative for this Pillar Section) -->
                        <div class="pt-5 border-t border-slate-200/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="pillar_1_narrative" class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Detailed Summary</span>
                                    <span class="text-[11px] font-normal text-slate-500">(Detailed Narrative for Pillar 1 Achievements)</span>
                                </label>
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md uppercase tracking-wider">Pillar 1 Narrative</span>
                            </div>
                            <textarea id="pillar_1_narrative"
                                      name="pillar_1_narrative"
                                      rows="4"
                                      placeholder="Provide the comprehensive qualitative narrative on operational context, stakeholder execution dynamics, and positive human stories for Pillar 1 Achievements..."
                                      class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[1]['narrative'] }}</textarea>
                        </div>
                    </div>

                    <!-- Progressive Bottom Actions -->
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <button type="button" onclick="togglePillarEdit(1)" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                            Done Editing
                        </button>
                        <button type="button" onclick="goToNextPillar(1)" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition cursor-pointer">
                            <span>Continue to Pillar 2 →</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ==================== PILLAR 2: CHALLENGES & RISKS ==================== -->
            <div id="pillar-card-2" class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4 transition-all duration-200">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-amber-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                2
                            </span>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">Pillar 2: Challenges &amp; Risks</h3>
                        </div>
                        <p class="text-xs text-slate-500 max-w-2xl">Document operational hurdles, resource constraints, and proactive risk monitoring protocols.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                        <button type="button"
                                onclick="togglePillarEdit(2)"
                                id="btn-toggle-pillar-2"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition shadow-2xs cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <span id="btn-toggle-label-2">Update</span>
                        </button>
                    </div>
                </div>

                <!-- Status / Preview summary (visible when collapsed) -->
                <div id="pillar-summary-2" class="text-xs text-slate-400 italic">
                    Not yet filled
                </div>

                <!-- Editing panel (progressive disclosure) -->
                <div id="pillar-edit-area-2" class="space-y-6 pt-4 border-t border-slate-100 hidden">
                    <div id="pillar-panel-2" class="space-y-6">
                                                <!-- 3 Dynamic Sub-Focus Point Section Cards (Pop up modal on Update) -->
                        <div class="space-y-4">
                            <!-- Sub-Section: 1. Operational Challenges -->
                            <div id="point-card-2-operational" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">1. Operational Challenges</h4>
                                            <span id="badge-count-2-operational" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Field execution bottlenecks, transport, scheduling, or attendance barriers.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(2, 'operational')"
                                                id="btn-open-point-2-operational"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-2-operational" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-2-operational" class="hidden">
                                    @foreach($initialPillars[2]['operational']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_2_challenges[operational][points][]" value="{{ $val }}" class="bullet-input" data-pillar="2" data-key="operational">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_2_challenges[operational][narrative]" value="{{ $initialPillars[2]['operational']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 2. Resource Gaps -->
                            <div id="point-card-2-resources" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">2. Resource Gaps</h4>
                                            <span id="badge-count-2-resources" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Deficits in kit, sports equipment, learning materials, or venue accessibility.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(2, 'resources')"
                                                id="btn-open-point-2-resources"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-2-resources" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-2-resources" class="hidden">
                                    @foreach($initialPillars[2]['resources']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_2_challenges[resources][points][]" value="{{ $val }}" class="bullet-input" data-pillar="2" data-key="resources">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_2_challenges[resources][narrative]" value="{{ $initialPillars[2]['resources']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 3. Risk Monitoring -->
                            <div id="point-card-2-risks" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">3. Risk Monitoring</h4>
                                            <span id="badge-count-2-risks" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Safeguarding, child protection, health risks, or external dependencies with mitigation.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(2, 'risks')"
                                                id="btn-open-point-2-risks"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-2-risks" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-2-risks" class="hidden">
                                    @foreach($initialPillars[2]['risks']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_2_challenges[risks][points][]" value="{{ $val }}" class="bullet-input" data-pillar="2" data-key="risks">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_2_challenges[risks][narrative]" value="{{ $initialPillars[2]['risks']['narrative'] }}">
                            </div>
                        </div>

                        <!-- Pillar 2 Detailed Summary (Qualitative Narrative for this Pillar Section) -->
                        <div class="pt-5 border-t border-slate-200/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="pillar_2_narrative" class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span>Detailed Summary</span>
                                    <span class="text-[11px] font-normal text-slate-500">(Qualitative narrative for Pillar 2 Challenges &amp; Risks)</span>
                                </label>
                                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md uppercase tracking-wider">Pillar 2 Narrative</span>
                            </div>
                            <textarea id="pillar_2_narrative"
                                      name="pillar_2_narrative"
                                      rows="4"
                                      placeholder="Provide the comprehensive qualitative narrative on operational bottlenecks, resource constraints, and active risk mitigation protocols for Pillar 2 Challenges..."
                                      class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-3 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[2]['narrative'] }}</textarea>
                        </div>
                    </div>

                    <!-- Progressive Bottom Actions -->
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <button type="button" onclick="togglePillarEdit(2)" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                            Done Editing
                        </button>
                        <button type="button" onclick="goToNextPillar(2)" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-xs transition cursor-pointer">
                            <span>Continue to Pillar 3 →</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ==================== PILLAR 3: LEARNING & ADAPTATION ==================== -->
            <div id="pillar-card-3" class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4 transition-all duration-200">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-purple-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                3
                            </span>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">Pillar 3: Learning &amp; Adaptation</h3>
                        </div>
                        <p class="text-xs text-slate-500 max-w-2xl">Record insights, direct participant stakeholder feedback, and novel innovation experiments.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                        <button type="button"
                                onclick="togglePillarEdit(3)"
                                id="btn-toggle-pillar-3"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition shadow-2xs cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <span id="btn-toggle-label-3">Update</span>
                        </button>
                    </div>
                </div>

                <!-- Status / Preview summary (visible when collapsed) -->
                <div id="pillar-summary-3" class="text-xs text-slate-400 italic">
                    Not yet filled
                </div>

                <!-- Editing panel (progressive disclosure) -->
                <div id="pillar-edit-area-3" class="space-y-6 pt-4 border-t border-slate-100 hidden">
                    <div id="pillar-panel-3" class="space-y-6">
                                                <!-- 3 Dynamic Sub-Focus Point Section Cards (Pop up modal on Update) -->
                        <div class="space-y-4">
                            <!-- Sub-Section: 1. Key Lessons Learned -->
                            <div id="point-card-3-lessons" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">1. Key Lessons Learned</h4>
                                            <span id="badge-count-3-lessons" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Operational or pedagogical adaptations discovered during implementation.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(3, 'lessons')"
                                                id="btn-open-point-3-lessons"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-3-lessons" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-3-lessons" class="hidden">
                                    @foreach($initialPillars[3]['lessons']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_3_learning[lessons][points][]" value="{{ $val }}" class="bullet-input" data-pillar="3" data-key="lessons">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_3_learning[lessons][narrative]" value="{{ $initialPillars[3]['lessons']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 2. Stakeholder Feedback -->
                            <div id="point-card-3-feedback" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">2. Stakeholder Feedback</h4>
                                            <span id="badge-count-3-feedback" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Community, coach, or participant sentiment and suggestions for improvement.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(3, 'feedback')"
                                                id="btn-open-point-3-feedback"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-3-feedback" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-3-feedback" class="hidden">
                                    @foreach($initialPillars[3]['feedback']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_3_learning[feedback][points][]" value="{{ $val }}" class="bullet-input" data-pillar="3" data-key="feedback">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_3_learning[feedback][narrative]" value="{{ $initialPillars[3]['feedback']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 3. Innovations & Adaptations -->
                            <div id="point-card-3-innovation" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">3. Innovations & Adaptations</h4>
                                            <span id="badge-count-3-innovation" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Novel methodologies, digital tools, or creative solutions deployed.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(3, 'innovation')"
                                                id="btn-open-point-3-innovation"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-3-innovation" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-3-innovation" class="hidden">
                                    @foreach($initialPillars[3]['innovation']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_3_learning[innovation][points][]" value="{{ $val }}" class="bullet-input" data-pillar="3" data-key="innovation">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_3_learning[innovation][narrative]" value="{{ $initialPillars[3]['innovation']['narrative'] }}">
                            </div>
                        </div>

                        <!-- Pillar 3 Detailed Summary (Qualitative Narrative for this Pillar Section) -->
                        <div class="pt-5 border-t border-slate-200/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="pillar_3_narrative" class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <span>Detailed Summary</span>
                                    <span class="text-[11px] font-normal text-slate-500">(Qualitative narrative for Pillar 3 Learning &amp; Adaptation)</span>
                                </label>
                                <span class="text-[10px] font-bold text-purple-700 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-md uppercase tracking-wider">Pillar 3 Narrative</span>
                            </div>
                            <textarea id="pillar_3_narrative"
                                      name="pillar_3_narrative"
                                      rows="4"
                                      placeholder="Provide the comprehensive qualitative narrative on key lessons learned, participant feedback loops, and innovation experiments for Pillar 3 Learning..."
                                      class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-3 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[3]['narrative'] }}</textarea>
                        </div>
                    </div>

                    <!-- Progressive Bottom Actions -->
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <button type="button" onclick="togglePillarEdit(3)" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                            Done Editing
                        </button>
                        <button type="button" onclick="goToNextPillar(3)" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-xs transition cursor-pointer">
                            <span>Continue to Pillar 4 →</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ==================== PILLAR 4: MONITORING & EVALUATION ==================== -->
            <div id="pillar-card-4" class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4 transition-all duration-200">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                4
                            </span>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">Pillar 4: Monitoring &amp; Evaluation</h3>
                        </div>
                        <p class="text-xs text-slate-500 max-w-2xl">Track target vs actual performance, data verification checks, and scheduled evaluation plans.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                        <button type="button"
                                onclick="togglePillarEdit(4)"
                                id="btn-toggle-pillar-4"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition shadow-2xs cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <span id="btn-toggle-label-4">Update</span>
                        </button>
                    </div>
                </div>

                <!-- Status / Preview summary (visible when collapsed) -->
                <div id="pillar-summary-4" class="text-xs text-slate-400 italic">
                    Not yet filled
                </div>

                <!-- Editing panel (progressive disclosure) -->
                <div id="pillar-edit-area-4" class="space-y-6 pt-4 border-t border-slate-100 hidden">
                    <div id="pillar-panel-4" class="space-y-6">
                                                <!-- 3 Dynamic Sub-Focus Point Section Cards (Pop up modal on Update) -->
                        <div class="space-y-4">
                            <!-- Sub-Section: 1. KPI & Target Performance -->
                            <div id="point-card-4-performance" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">1. KPI & Target Performance</h4>
                                            <span id="badge-count-4-performance" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Actual progress against quarterly milestones, participant targets, and output metrics.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(4, 'performance')"
                                                id="btn-open-point-4-performance"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-4-performance" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-4-performance" class="hidden">
                                    @foreach($initialPillars[4]['performance']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_4_monitoring[performance][points][]" value="{{ $val }}" class="bullet-input" data-pillar="4" data-key="performance">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_4_monitoring[performance][narrative]" value="{{ $initialPillars[4]['performance']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 2. Data Quality & Verification -->
                            <div id="point-card-4-data_quality" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">2. Data Quality & Verification</h4>
                                            <span id="badge-count-4-data_quality" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Completeness of attendance registers, verification checks, and data discrepancies.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(4, 'data_quality')"
                                                id="btn-open-point-4-data_quality"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-4-data_quality" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-4-data_quality" class="hidden">
                                    @foreach($initialPillars[4]['data_quality']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_4_monitoring[data_quality][points][]" value="{{ $val }}" class="bullet-input" data-pillar="4" data-key="data_quality">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_4_monitoring[data_quality][narrative]" value="{{ $initialPillars[4]['data_quality']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 3. Upcoming Evaluations -->
                            <div id="point-card-4-evaluation" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">3. Upcoming Evaluations</h4>
                                            <span id="badge-count-4-evaluation" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Planned assessments, internal audits, baseline/endline studies, or reviews.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(4, 'evaluation')"
                                                id="btn-open-point-4-evaluation"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-4-evaluation" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-4-evaluation" class="hidden">
                                    @foreach($initialPillars[4]['evaluation']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_4_monitoring[evaluation][points][]" value="{{ $val }}" class="bullet-input" data-pillar="4" data-key="evaluation">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_4_monitoring[evaluation][narrative]" value="{{ $initialPillars[4]['evaluation']['narrative'] }}">
                            </div>
                        </div>

                        <!-- Pillar 4 Detailed Summary (Qualitative Narrative for this Pillar Section) -->
                        <div class="pt-5 border-t border-slate-200/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="pillar_4_narrative" class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <span>Detailed Summary</span>
                                    <span class="text-[11px] font-normal text-slate-500">(Qualitative narrative for Pillar 4 Monitoring &amp; Evaluation)</span>
                                </label>
                                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-md uppercase tracking-wider">Pillar 4 Narrative</span>
                            </div>
                            <textarea id="pillar_4_narrative"
                                      name="pillar_4_narrative"
                                      rows="4"
                                      placeholder="Provide the comprehensive qualitative narrative on KPI performance metrics, data quality assurance, and scheduled evaluation plans for Pillar 4 M&E..."
                                      class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[4]['narrative'] }}</textarea>
                        </div>
                    </div>

                    <!-- Progressive Bottom Actions -->
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <button type="button" onclick="togglePillarEdit(4)" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                            Done Editing
                        </button>
                        <button type="button" onclick="goToNextPillar(4)" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition cursor-pointer">
                            <span>Continue to Pillar 5 →</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ==================== PILLAR 5: COLLABORATION & COORDINATION ==================== -->
            <div id="pillar-card-5" class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4 transition-all duration-200">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                5
                            </span>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">Pillar 5: Collaboration &amp; Coordination</h3>
                        </div>
                        <p class="text-xs text-slate-500 max-w-2xl">Capture cross-project partnerships, civic stakeholder alliances, and shared learnings.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                        <button type="button"
                                onclick="togglePillarEdit(5)"
                                id="btn-toggle-pillar-5"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition shadow-2xs cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <span id="btn-toggle-label-5">Update</span>
                        </button>
                    </div>
                </div>

                <!-- Status / Preview summary (visible when collapsed) -->
                <div id="pillar-summary-5" class="text-xs text-slate-400 italic">
                    Not yet filled
                </div>

                <!-- Editing panel (progressive disclosure) -->
                <div id="pillar-edit-area-5" class="space-y-6 pt-4 border-t border-slate-100 hidden">
                    <div id="pillar-panel-5" class="space-y-6">
                                                <!-- 3 Dynamic Sub-Focus Point Section Cards (Pop up modal on Update) -->
                        <div class="space-y-4">
                            <!-- Sub-Section: 1. Inter-Project Collaboration -->
                            <div id="point-card-5-project_collab" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">1. Inter-Project Collaboration</h4>
                                            <span id="badge-count-5-project_collab" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Cross-team synergies, joint activity delivery, or shared resources within the hub.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(5, 'project_collab')"
                                                id="btn-open-point-5-project_collab"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-5-project_collab" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-5-project_collab" class="hidden">
                                    @foreach($initialPillars[5]['project_collab']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_5_collaboration[project_collab][points][]" value="{{ $val }}" class="bullet-input" data-pillar="5" data-key="project_collab">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_5_collaboration[project_collab][narrative]" value="{{ $initialPillars[5]['project_collab']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 2. Institutional Partnerships -->
                            <div id="point-card-5-partnerships" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">2. Institutional Partnerships</h4>
                                            <span id="badge-count-5-partnerships" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Engagement with ministries, schools, civic authorities, or external partners.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(5, 'partnerships')"
                                                id="btn-open-point-5-partnerships"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-5-partnerships" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-5-partnerships" class="hidden">
                                    @foreach($initialPillars[5]['partnerships']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_5_collaboration[partnerships][points][]" value="{{ $val }}" class="bullet-input" data-pillar="5" data-key="partnerships">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_5_collaboration[partnerships][narrative]" value="{{ $initialPillars[5]['partnerships']['narrative'] }}">
                            </div>
                            <!-- Sub-Section: 3. Cross-Learning Opportunities -->
                            <div id="point-card-5-cross_learning" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">3. Cross-Learning Opportunities</h4>
                                            <span id="badge-count-5-cross_learning" class="text-[10px] font-semibold text-slate-400 ml-1.5">0 points</span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">Knowledge exchanges, peer-to-peer visits, or shared best practices across hubs.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                                        <button type="button"
                                                onclick="openPointModal(5, 'cross_learning')"
                                                id="btn-open-point-5-cross_learning"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary preview -->
                                <div id="point-summary-5-cross_learning" class="text-xs text-slate-400 italic">
                                    Not yet filled
                                </div>

                                <!-- Hidden inputs container for form submission -->
                                <div id="point-inputs-5-cross_learning" class="hidden">
                                    @foreach($initialPillars[5]['cross_learning']['points'] as $idx => $val)
                                    <input type="hidden" name="pillar_5_collaboration[cross_learning][points][]" value="{{ $val }}" class="bullet-input" data-pillar="5" data-key="cross_learning">
                                    @endforeach
                                </div>

                                <!-- Hidden input preserving legacy sub-pillar narrative -->
                                <input type="hidden" name="pillar_5_collaboration[cross_learning][narrative]" value="{{ $initialPillars[5]['cross_learning']['narrative'] }}">
                            </div>
                        </div>

                        <!-- Pillar 5 Detailed Summary (Qualitative Narrative for this Pillar Section) -->
                        <div class="pt-5 border-t border-slate-200/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="pillar_5_narrative" class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span>Detailed Summary</span>
                                    <span class="text-[11px] font-normal text-slate-500">(Qualitative narrative for Pillar 5 Collaboration &amp; Coordination)</span>
                                </label>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md uppercase tracking-wider">Pillar 5 Narrative</span>
                            </div>
                            <textarea id="pillar_5_narrative"
                                      name="pillar_5_narrative"
                                      rows="4"
                                      placeholder="Provide the comprehensive qualitative narrative on project collaboration, external institutional partnerships, and cross-learning for Pillar 5..."
                                      class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[5]['narrative'] }}</textarea>
                        </div>
                    </div>

                    <!-- Progressive Bottom Actions -->
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <button type="button" onclick="togglePillarEdit(5)" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                            Done Editing
                        </button>
                        <button type="button" onclick="togglePillarEdit(5)" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition cursor-pointer">
                            <span>Done with All Pillars ✓</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Flat columns for backward compatibility -->
            <input type="hidden" name="achievements_milestones" id="flat_achievements_milestones" value="">
            <input type="hidden" name="achievements_impact" id="flat_achievements_impact" value="">
            <input type="hidden" name="achievements_stories" id="flat_achievements_stories" value="">
            <input type="hidden" name="achievements_narrative" id="flat_achievements_narrative" value="">

            <input type="hidden" name="challenges_operational" id="flat_challenges_operational" value="">
            <input type="hidden" name="challenges_resources" id="flat_challenges_resources" value="">
            <input type="hidden" name="challenges_risks" id="flat_challenges_risks" value="">
            <input type="hidden" name="challenges_narrative" id="flat_challenges_narrative" value="">

            <input type="hidden" name="learning_lessons" id="flat_learning_lessons" value="">
            <input type="hidden" name="learning_feedback" id="flat_learning_feedback" value="">
            <input type="hidden" name="learning_innovation" id="flat_learning_innovation" value="">
            <input type="hidden" name="learning_narrative" id="flat_learning_narrative" value="">

            <input type="hidden" name="mne_performance" id="flat_mne_performance" value="">
            <input type="hidden" name="mne_data_quality" id="flat_mne_data_quality" value="">
            <input type="hidden" name="mne_evaluation_plans" id="flat_mne_evaluation_plans" value="">
            <input type="hidden" name="mne_narrative" id="flat_mne_narrative" value="">

            <input type="hidden" name="collab_projects" id="flat_collab_projects" value="">
            <input type="hidden" name="collab_partnerships" id="flat_collab_partnerships" value="">
            <input type="hidden" name="collab_cross_learning" id="flat_collab_cross_learning" value="">
            <input type="hidden" name="collab_narrative" id="flat_collab_narrative" value="">

            <!-- Point Section Update Modal (Free Large Text Area with Drive Media Link & Done) -->
            <div id="point-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="point-modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" onclick="closePointModal()"></div>

                <!-- Dialog container: Large & Spacious (sm:max-w-4xl) -->
                <div class="min-h-full flex items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200 text-left overflow-hidden transform transition-all sm:my-8 sm:max-w-4xl sm:w-full p-6 sm:p-8 space-y-5" onclick="event.stopPropagation()">
                        
                        <!-- Header matching DOT modal -->
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <div class="space-y-1">
                                <h3 id="point-modal-title" class="text-lg sm:text-xl font-bold text-slate-800">
                                    Update Key Milestones
                                </h3>
                                <p id="point-modal-subtitle" class="text-xs text-slate-400 leading-snug">
                                    Major operational, structural, or program activities completed this cycle.
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="closePointModal()" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                                    Cancel
                                </button>
                                <button type="button" id="point-modal-save-btn" onclick="savePointModal()" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Save</span>
                                </button>
                                <button type="button" onclick="closePointModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer" title="Close">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body with Free Large Text Area and Drive Media Link Drawer -->
                        <div class="space-y-3">
                            <!-- Helper toolbar / point controls -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-700">Points &amp; Key Highlights</span>
                                    <span id="point-modal-badge" class="text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">0 points</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <!-- Icon-only link button with tooltip -->
                                    <button type="button"
                                            onclick="toggleDriveLinkArea()"
                                            id="btn-toggle-drive-link"
                                            title="Add Media Link from drive"
                                            class="p-2 text-slate-600 hover:text-blue-600 bg-white hover:bg-blue-50 border border-slate-300 hover:border-blue-300 rounded-xl transition shadow-2xs cursor-pointer flex items-center justify-center"
                                            aria-label="Add Media Link from drive">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                    </button>
                                    <!-- Add bullet point button -->
                                    <button type="button" onclick="insertBulletPoint()" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-xl transition cursor-pointer shadow-2xs">
                                        <span>+ Add Bullet Point</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Expandable Link Input Area (Auto-Detects Document Type + Done button, No doc name) -->
                            <div id="drive-link-box" class="hidden p-4 bg-gradient-to-r from-blue-50/90 to-indigo-50/70 border border-blue-200/90 rounded-2xl space-y-3 transition-all shadow-xs">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold shadow-2xs">
                                            🔗
                                        </span>
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-900">Add Media Link from Drive</h5>
                                            <p class="text-[11px] text-slate-500">Auto-detects document/media type or click a pill to choose</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            onclick="applyDriveLinkAndClose()"
                                            class="px-4 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-xs cursor-pointer flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Done</span>
                                    </button>
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 flex items-center justify-between">
                                        <span>Google Drive / Document Link <span class="text-rose-500">*</span></span>
                                        <span id="drive-detected-badge" class="text-[10px] font-bold text-blue-700 bg-white border border-blue-200 px-2 py-0.5 rounded-md hidden">
                                            🖼️ Image
                                        </span>
                                    </label>
                                    <input type="url"
                                           id="drive-link-input"
                                           placeholder="Paste link: https://drive.google.com/... or Google Doc / Image / Sheet link"
                                           class="w-full text-xs font-mono text-slate-800 placeholder:text-slate-400 bg-white border border-slate-300 rounded-xl p-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                                           oninput="onDriveUrlInput()">
                                </div>

                                <!-- Quick Type Selector Chips -->
                                <div class="space-y-1.5 pt-1">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-slate-500 font-medium">Media / Document Type:</span>
                                        <span class="text-[10px] text-slate-400">Auto-detected or click to choose</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-wrap" id="drive-type-chips">
                                        <button type="button" onclick="setManualLinkType('🖼️', 'Image')" data-type="Image"
                                                class="type-chip px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                            🖼️ Image
                                        </button>
                                        <button type="button" onclick="setManualLinkType('📄', 'Google Doc')" data-type="Google Doc"
                                                class="type-chip px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                            📄 Doc
                                        </button>
                                        <button type="button" onclick="setManualLinkType('📊', 'Google Sheets')" data-type="Google Sheets"
                                                class="type-chip px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                            📊 Sheet
                                        </button>
                                        <button type="button" onclick="setManualLinkType('📑', 'Google Slides')" data-type="Google Slides"
                                                class="type-chip px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                            📑 Slides
                                        </button>
                                        <button type="button" onclick="setManualLinkType('📁', 'Drive Folder')" data-type="Drive Folder"
                                                class="type-chip px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                            📁 Folder
                                        </button>
                                        <button type="button" onclick="setManualLinkType('📁', 'Drive File')" data-type="Drive File"
                                                class="type-chip px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                            📁 File
                                        </button>
                                        <button type="button" onclick="setManualLinkType('🎥', 'Video')" data-type="Video"
                                                class="type-chip px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                            🎥 Video
                                        </button>
                                        <button type="button" onclick="setManualLinkType('📕', 'PDF')" data-type="PDF"
                                                class="type-chip px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                            📕 PDF
                                        </button>
                                    </div>
                                </div>

                                <!-- Live Preview Badge -->
                                <div class="flex items-center justify-between pt-1 border-t border-blue-100 text-[11px]">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-500 font-medium">Link preview:</span>
                                        <span id="drive-link-preview-badge" class="font-semibold text-blue-800 bg-white px-2.5 py-0.5 rounded-lg border border-blue-200 shadow-2xs">
                                            ( 🖼️ Image )
                                        </span>
                                    </div>
                                    <span class="text-slate-400 hidden sm:inline">Will be inserted into the text area as a clickable badge</span>
                                </div>
                            </div>

                            <!-- Free Large Text Area with Clickable Links Support -->
                            <style>
                                #point-modal-editor {
                                    line-height: 1.85 !important;
                                }
                                #point-modal-editor > div {
                                    padding-top: 8px !important;
                                    padding-bottom: 8px !important;
                                    margin-bottom: 6px !important;
                                    border-bottom: 1px dashed rgba(226, 232, 240, 0.8) !important;
                                    min-height: 36px !important;
                                }
                                #point-modal-editor > div:last-child {
                                    border-bottom: none !important;
                                    margin-bottom: 0 !important;
                                }
                            </style>
                            <div class="relative">
                                <div id="point-modal-editor"
                                     contenteditable="true"
                                     role="textbox"
                                     aria-multiline="true"
                                     class="w-full text-xs sm:text-sm font-normal text-slate-800 border border-slate-200 rounded-2xl p-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 leading-loose outline-none min-h-[240px] max-h-[50vh] overflow-y-auto bg-white whitespace-pre-wrap select-text"
                                     data-placeholder="• e.g. 5 Coach Clinics conducted across 3 zones&#10;• e.g. 40 youth peer educators trained and certified"></div>
                            </div>

                            <!-- Footer tip -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-[11px] text-slate-400 pt-1 gap-2">
                                <span class="flex items-center gap-1.5">
                                    <span>💡</span>
                                    <span>Each line is recorded as a distinct point. Press <strong>Enter</strong> to start a new bullet. Click <span class="inline-flex items-center justify-center w-5 h-5 bg-white border border-slate-300 rounded text-slate-600">🔗</span> to paste a Drive link.</span>
                                </span>
                                <span class="hidden sm:inline shrink-0">Press <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-300 rounded text-[10px] font-mono text-slate-600">Ctrl</kbd> + <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-300 rounded text-[10px] font-mono text-slate-600">Enter</kbd> to save</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="button" onclick="saveDraftManual()" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition cursor-pointer flex items-center gap-1.5 shadow-2xs">
                        <span>💾 Save Draft</span>
                    </button>
                    <span id="draft-status-indicator" class="text-xs text-slate-400"></span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <a href="{{ $backUrl }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-800 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md shadow-emerald-500/20 transition cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ $isEdit ? 'Update Activity Entry' : 'Submit Activity Entry' }}</span>
                    </button>
                </div>
            </div>
        </form>
    </main>
</div>

<script>
// --- Pillar Navigation & Stepper Logic ---
let currentActivePillar = 1;

const pillarFieldNames = {
    1: 'achievements',
    2: 'challenges',
    3: 'learning',
    4: 'monitoring',
    5: 'collaboration'
};

const pillarColors = {
    1: 'bg-blue-600',
    2: 'bg-amber-600',
    3: 'bg-purple-600',
    4: 'bg-indigo-600',
    5: 'bg-emerald-600'
};

function togglePillarEdit(num) {
    const editArea = document.getElementById(`pillar-edit-area-${num}`);
    const summaryArea = document.getElementById(`pillar-summary-${num}`);
    const btnLabel = document.getElementById(`btn-toggle-label-${num}`);
    const btn = document.getElementById(`btn-toggle-pillar-${num}`);

    if (!editArea) return;

    const isCurrentlyOpen = !editArea.classList.contains('hidden');

    if (isCurrentlyOpen) {
        // Close it
        editArea.classList.add('hidden');
        if (summaryArea) summaryArea.classList.remove('hidden');
        if (btnLabel) btnLabel.textContent = 'Update';
        if (btn) btn.classList.remove('bg-slate-100', 'font-bold');
    } else {
        // Open it
        editArea.classList.remove('hidden');
        if (summaryArea) summaryArea.classList.add('hidden');
        if (btnLabel) btnLabel.textContent = 'Collapse';
        if (btn) btn.classList.add('bg-slate-100', 'font-bold');

        const card = document.getElementById(`pillar-card-${num}`);
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    refreshPillarSummaries();
}

function goToNextPillar(currentNum) {
    // Close current
    const editArea = document.getElementById(`pillar-edit-area-${currentNum}`);
    const summaryArea = document.getElementById(`pillar-summary-${currentNum}`);
    const btnLabel = document.getElementById(`btn-toggle-label-${currentNum}`);
    const btn = document.getElementById(`btn-toggle-pillar-${currentNum}`);

    if (editArea) editArea.classList.add('hidden');
    if (summaryArea) summaryArea.classList.remove('hidden');
    if (btnLabel) btnLabel.textContent = 'Update';
    if (btn) btn.classList.remove('bg-slate-100', 'font-bold');

    // Open next
    if (currentNum < 5) {
        const nextNum = currentNum + 1;
        const nextEditArea = document.getElementById(`pillar-edit-area-${nextNum}`);
        const nextSummaryArea = document.getElementById(`pillar-summary-${nextNum}`);
        const nextBtnLabel = document.getElementById(`btn-toggle-label-${nextNum}`);
        const nextBtn = document.getElementById(`btn-toggle-pillar-${nextNum}`);

        if (nextEditArea) nextEditArea.classList.remove('hidden');
        if (nextSummaryArea) nextSummaryArea.classList.add('hidden');
        if (nextBtnLabel) nextBtnLabel.textContent = 'Collapse';
        if (nextBtn) nextBtn.classList.add('bg-slate-100', 'font-bold');

        const nextCard = document.getElementById(`pillar-card-${nextNum}`);
        if (nextCard) {
            nextCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    refreshPillarSummaries();
}

function switchPillar(num) {
    currentActivePillar = num;
    for (let i = 1; i <= 5; i++) {
        const editArea = document.getElementById(`pillar-edit-area-${i}`);
        const summaryArea = document.getElementById(`pillar-summary-${i}`);
        const btnLabel = document.getElementById(`btn-toggle-label-${i}`);
        const btn = document.getElementById(`btn-toggle-pillar-${i}`);

        if (i === num) {
            if (editArea) editArea.classList.remove('hidden');
            if (summaryArea) summaryArea.classList.add('hidden');
            if (btnLabel) btnLabel.textContent = 'Collapse';
            if (btn) btn.classList.add('bg-slate-100', 'font-bold');
        } else {
            if (editArea) editArea.classList.add('hidden');
            if (summaryArea) summaryArea.classList.remove('hidden');
            if (btnLabel) btnLabel.textContent = 'Update';
            if (btn) btn.classList.remove('bg-slate-100', 'font-bold');
        }
    }
    const card = document.getElementById(`pillar-card-${num}`);
    if (card) {
        card.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    refreshPillarSummaries();
}

function nextPillarStep() {
    if (currentActivePillar < 5) {
        switchPillar(currentActivePillar + 1);
    }
}

function prevPillarStep() {
    if (currentActivePillar > 1) {
        switchPillar(currentActivePillar - 1);
    }
}

// --- Point Section Meta Dictionary for Modal ---
const pointSectionMeta = {
    1: {
        field: 'achievements',
        color: 'blue',
        sub: {
            milestones: {
                title: 'Key Milestones',
                subtitle: 'Major operational, structural, or program activities completed this cycle.',
                placeholder: '• e.g. 5 Coach Clinics conducted across 3 zones\n• e.g. 40 youth peer educators trained and certified'
            },
            impact: {
                title: 'Impact Evidence',
                subtitle: 'Tangible changes, participant numbers, percentages (e.g. 98% passed), or survey data.',
                placeholder: '• e.g. 94% retention rate achieved among girls cohort\n• e.g. 1,250 youth participants reached across 8 communities'
            },
            stories: {
                title: 'Success Stories',
                subtitle: 'Individual or community transformation highlights, quotes, and peer role model wins.',
                placeholder: '• e.g. Chanda secured tertiary sports leadership scholarship\n• e.g. Coach Mary awarded Community Mentor of the Quarter'
            }
        }
    },
    2: {
        field: 'challenges',
        color: 'amber',
        sub: {
            operational: {
                title: 'Operational Challenges',
                subtitle: 'Field execution bottlenecks, transport, scheduling, or attendance barriers.',
                placeholder: '• e.g. Heavy seasonal rains delayed week 4 fixtures\n• e.g. Transport logistics interrupted by road repairs'
            },
            resources: {
                title: 'Resource Gaps',
                subtitle: 'Deficits in kit, sports equipment, learning materials, or venue accessibility.',
                placeholder: '• e.g. Additional size 4 footballs and bibs required for junior hub\n• e.g. First aid supplies replenishment needed'
            },
            risks: {
                title: 'Risk Monitoring',
                subtitle: 'Safeguarding, child protection, health risks, or external dependencies with mitigation.',
                placeholder: '• e.g. Safeguarding refreshers completed for all volunteer referees\n• e.g. Updated participant sign-in registers to prevent unescorted departures'
            }
        }
    },
    3: {
        field: 'learning',
        color: 'purple',
        sub: {
            lessons: {
                title: 'Key Lessons Learned',
                subtitle: 'Operational or pedagogical adaptations discovered during implementation.',
                placeholder: '• e.g. Shifting match kickoff to 09:00 increased spectator attendance by 35%\n• e.g. Pre-session warmups improved participant focus'
            },
            feedback: {
                title: 'Stakeholder Feedback',
                subtitle: 'Community, coach, or participant sentiment and suggestions for improvement.',
                placeholder: '• e.g. Parents requested additional weekend life skills workshops\n• e.g. Headteachers praised reduction in truancy on activity days'
            },
            innovation: {
                title: 'Innovations & Adaptations',
                subtitle: 'Novel methodologies, digital tools, or creative solutions deployed.',
                placeholder: '• e.g. Piloted peer-led digital registers using shared mobile tablets\n• e.g. Introduced peer voting for Fair Play MVP'
            }
        }
    },
    4: {
        field: 'monitoring',
        color: 'indigo',
        sub: {
            performance: {
                title: 'KPI & Target Performance',
                subtitle: 'Actual progress against quarterly milestones, participant targets, and output metrics.',
                placeholder: '• e.g. Achieved 104% of quarterly youth engagement target (520 vs 500 planned)\n• e.g. 92% coach attendance across all sessions'
            },
            data_quality: {
                title: 'Data Quality & Verification',
                subtitle: 'Completeness of attendance registers, verification checks, and data discrepancies.',
                placeholder: '• e.g. 100% of attendance registers digitised and cross-verified by MEAL officer\n• e.g. Resolved 3 duplicate registration records'
            },
            evaluation: {
                title: 'Upcoming Evaluations',
                subtitle: 'Planned assessments, internal audits, baseline/endline studies, or reviews.',
                placeholder: '• e.g. Mid-term cohort outcome survey scheduled for next month\n• e.g. Focus group discussion planned with youth peer leaders'
            }
        }
    },
    5: {
        field: 'collaboration',
        color: 'emerald',
        sub: {
            project_collab: {
                title: 'Inter-Project Collaboration',
                subtitle: 'Cross-team synergies, joint activity delivery, or shared resources within the hub.',
                placeholder: '• e.g. Partnered with Health & Wellness team for nutrition screenings\n• e.g. Shared tournament pitch with Livingstone Youth League'
            },
            partnerships: {
                title: 'Institutional Partnerships',
                subtitle: 'Engagement with ministries, schools, civic authorities, or external partners.',
                placeholder: '• e.g. Signed MOU with Ministry of Youth & Sport for community pitch usage\n• e.g. District Education Board approved secondary school sports calendar'
            },
            cross_learning: {
                title: 'Cross-Learning Opportunities',
                subtitle: 'Knowledge exchanges, peer-to-peer visits, or shared best practices across hubs.',
                placeholder: '• e.g. Shared coaching methodology toolkit at provincial quarterly summit\n• e.g. Hosted benchmark visit for Lusaka youth leadership fellows'
            }
        }
    }
};

let currentModalPillar = 1;
let currentModalKey = 'milestones';
let lastSavedRange = null;
let currentLinkType = { icon: '🖼️', type: 'Image' };

function detectDocumentInfo(url) {
    if (!url) return { icon: '📁', type: 'Drive Link' };
    const u = url.toLowerCase();

    // 1. Photos & Images (Drive images, Google Photos, web images, image extensions)
    if (
        u.includes('photos.google.com') ||
        u.includes('googleusercontent.com') ||
        /\.(jpg|jpeg|png|gif|webp|svg|bmp|heic|tiff)(\?|$)/i.test(url) ||
        /(photo|image|picture|screenshot|img_)/i.test(url)
    ) {
        return { icon: '🖼️', type: 'Image' };
    }

    // 2. Google Workspace
    if (u.includes('docs.google.com/spreadsheets')) {
        return { icon: '📊', type: 'Google Sheets' };
    }
    if (u.includes('docs.google.com/document')) {
        return { icon: '📄', type: 'Google Doc' };
    }
    if (u.includes('docs.google.com/presentation')) {
        return { icon: '📑', type: 'Google Slides' };
    }

    // 3. Drive Folders & Files
    if (u.includes('drive.google.com/drive/folders')) {
        return { icon: '📁', type: 'Drive Folder' };
    }
    if (u.includes('drive.google.com/file') || u.includes('drive.google.com/open')) {
        return { icon: '📁', type: 'Drive File' };
    }
    if (u.includes('drive.google.com')) {
        return { icon: '📁', type: 'Google Drive' };
    }

    // 4. Media & Docs
    if (u.includes('youtube.com') || u.includes('youtu.be')) {
        return { icon: '🎥', type: 'Video' };
    }
    if (/\.pdf(\?|$)/i.test(url)) {
        return { icon: '📕', type: 'PDF' };
    }
    if (u.includes('dropbox.com')) {
        return { icon: '📦', type: 'Dropbox' };
    }
    if (u.includes('onedrive') || u.includes('sharepoint.com')) {
        return { icon: '📁', type: 'OneDrive' };
    }

    return { icon: '🔗', type: 'Media Link' };
}

function updateActiveTypeChip(type) {
    document.querySelectorAll('#drive-type-chips .type-chip').forEach(btn => {
        if (btn.getAttribute('data-type') === type) {
            btn.className = 'type-chip px-2.5 py-1 text-xs font-bold rounded-lg border border-blue-600 bg-blue-600 text-white shadow-xs transition cursor-pointer flex items-center gap-1 scale-[1.02]';
        } else {
            btn.className = 'type-chip px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 transition cursor-pointer flex items-center gap-1 shadow-2xs';
        }
    });
}

function setManualLinkType(icon, type) {
    currentLinkType = { icon, type };
    updateActiveTypeChip(type);
    const badge = document.getElementById('drive-detected-badge');
    const preview = document.getElementById('drive-link-preview-badge');
    if (badge) {
        badge.textContent = `${icon} ${type}`;
        badge.classList.remove('hidden');
    }
    if (preview) {
        preview.textContent = `( ${icon} ${type} )`;
    }
}

function onDriveUrlInput() {
    const input = document.getElementById('drive-link-input');
    const badge = document.getElementById('drive-detected-badge');
    const preview = document.getElementById('drive-link-preview-badge');
    if (!input) return;

    const url = input.value.trim();
    if (url.length > 0) {
        const info = detectDocumentInfo(url);
        currentLinkType = info;
        updateActiveTypeChip(info.type);
        if (badge) {
            badge.textContent = `${info.icon} ${info.type}`;
            badge.classList.remove('hidden');
        }
        if (preview) {
            preview.textContent = `( ${info.icon} ${info.type} )`;
        }
    } else {
        if (badge) badge.classList.add('hidden');
        if (preview) preview.textContent = `( ${currentLinkType.icon} ${currentLinkType.type} )`;
    }
}

function toggleDriveLinkArea() {
    const box = document.getElementById('drive-link-box');
    const input = document.getElementById('drive-link-input');
    const editor = document.getElementById('point-modal-editor');
    if (!box) return;

    const isHidden = box.classList.contains('hidden');
    if (isHidden) {
        // Save current editor caret position
        const sel = window.getSelection();
        if (sel && sel.rangeCount > 0) {
            lastSavedRange = sel.getRangeAt(0).cloneRange();
        }
        box.classList.remove('hidden');
        if (input) {
            input.focus();
        }
        onDriveUrlInput();
    } else {
        box.classList.add('hidden');
        if (editor) editor.focus();
    }
}

function applyDriveLinkAndClose() {
    const box = document.getElementById('drive-link-box');
    const urlInput = document.getElementById('drive-link-input');
    const editor = document.getElementById('point-modal-editor');

    if (urlInput && editor) {
        const url = urlInput.value.trim();
        if (url.length > 0) {
            const info = currentLinkType || detectDocumentInfo(url);
            
            // Create clickable link badge element: ( Drive Icon: Document Type )
            const linkA = document.createElement('a');
            linkA.href = url;
            linkA.target = '_blank';
            linkA.rel = 'noopener noreferrer';
            linkA.contentEditable = 'false';
            linkA.setAttribute('data-icon', info.icon);
            linkA.setAttribute('data-type', info.type);
            linkA.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50/90 hover:bg-blue-100 text-blue-700 border border-blue-200/90 font-semibold text-xs no-underline mx-2 align-middle transition cursor-pointer select-none shadow-2xs';
            linkA.title = `Open ${info.type} in new tab`;
            linkA.innerHTML = `<span>${info.icon} ${info.type}</span> <span class="text-[10px] opacity-75 font-bold">↗</span>`;

            editor.focus();
            const sel = window.getSelection();

            // Restore saved range if available
            if (lastSavedRange) {
                sel.removeAllRanges();
                sel.addRange(lastSavedRange);
            }

            if (sel && sel.rangeCount > 0) {
                const range = sel.getRangeAt(0);
                range.deleteContents();
                range.insertNode(linkA);

                // Add non-breaking space after link and place cursor after
                const space = document.createTextNode('\u00A0');
                linkA.parentNode.insertBefore(space, linkA.nextSibling);

                range.setStartAfter(space);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            } else {
                editor.appendChild(linkA);
                editor.appendChild(document.createTextNode('\u00A0'));
            }

            urlInput.value = '';
            lastSavedRange = null;
        }
    }

    if (box) box.classList.add('hidden');
    if (editor) editor.focus();
    updateModalPointsBadge();
}

function insertBulletPoint() {
    const editor = document.getElementById('point-modal-editor');
    if (!editor) return;

    editor.focus();
    const sel = window.getSelection();
    
    const div = document.createElement('div');
    div.innerHTML = '•\u00A0';

    if (sel && sel.rangeCount > 0) {
        const range = sel.getRangeAt(0);
        range.deleteContents();
        range.insertNode(div);
        range.setStart(div, div.childNodes.length);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
    } else {
        editor.appendChild(div);
    }
    updateModalPointsBadge();
}

function openPointModal(pillarNum, subKey) {
    currentModalPillar = pillarNum;
    currentModalKey = subKey;
    lastSavedRange = null;

    const meta = pointSectionMeta[pillarNum]?.sub[subKey];
    if (!meta) return;

    const titleEl = document.getElementById('point-modal-title');
    const subtitleEl = document.getElementById('point-modal-subtitle');
    const editor = document.getElementById('point-modal-editor');
    const saveBtn = document.getElementById('point-modal-save-btn');
    const linkBox = document.getElementById('drive-link-box');
    const linkInput = document.getElementById('drive-link-input');

    if (titleEl) titleEl.textContent = `Update ${meta.title}`;
    if (subtitleEl) subtitleEl.textContent = meta.subtitle;
    if (linkBox) linkBox.classList.add('hidden');
    if (linkInput) linkInput.value = '';

    // Reset default active chip
    currentLinkType = { icon: '🖼️', type: 'Image' };
    updateActiveTypeChip('Image');

    const colorClasses = {
        blue: 'bg-blue-600 hover:bg-blue-700',
        amber: 'bg-amber-600 hover:bg-amber-700',
        purple: 'bg-purple-600 hover:bg-purple-700',
        indigo: 'bg-indigo-600 hover:bg-indigo-700',
        emerald: 'bg-emerald-600 hover:bg-emerald-700'
    };
    if (saveBtn) {
        const pillarColor = pointSectionMeta[pillarNum]?.color || 'blue';
        saveBtn.className = `px-5 py-2 text-xs font-bold text-white rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5 ${colorClasses[pillarColor] || 'bg-blue-600 hover:bg-blue-700'}`;
    }

    // Populate editor with existing points, rendering clickable link badges
    const container = document.getElementById(`point-inputs-${pillarNum}-${subKey}`);
    const existingPoints = [];
    if (container) {
        container.querySelectorAll('input.bullet-input').forEach(inp => {
            if (inp.value && inp.value.trim().length > 0) {
                existingPoints.push(inp.value.trim());
            }
        });
    }

    if (editor) {
        editor.innerHTML = '';
        if (existingPoints.length > 0) {
            existingPoints.forEach(p => {
                let text = p.startsWith('•') ? p.substring(1).trim() : p;
                
                // Extract custom badge and URL if present
                const badgeRegex = /(?:\(\s*([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎][^()\n]+?)\s*\)|\[\s*([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎][^\[\]\n]+?)\s*\])\s*\(?\s*(https?:\/\/[^\s<>"')]+)\s*\)?/u;
                const badgeMatch = text.match(badgeRegex);

                let linkHtml = '';
                if (badgeMatch) {
                    const label = (badgeMatch[1] || badgeMatch[2]).trim();
                    const url = badgeMatch[3];
                    text = text.replace(badgeMatch[0], '').trim();
                    
                    // Separate icon from type if present
                    const iconMatch = label.match(/^([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎])\s*(.*)$/u);
                    const icon = iconMatch ? iconMatch[1] : '🔗';
                    const type = iconMatch ? iconMatch[2] : label;

                    linkHtml = ` <a href="${escapeHtmlAttr(url)}" target="_blank" rel="noopener noreferrer" contenteditable="false" data-icon="${icon}" data-type="${escapeHtmlAttr(type)}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50/90 hover:bg-blue-100 text-blue-700 border border-blue-200/90 font-semibold text-xs no-underline mx-2 align-middle transition cursor-pointer select-none shadow-2xs" title="Open ${escapeHtmlAttr(type)} in new tab"><span>${icon} ${escapeHtml(type)}</span> <span class="text-[10px] opacity-75 font-bold">↗</span></a>\u00A0`;
                } else {
                    const urlMatch = text.match(/https?:\/\/[^\s<>"')]+/i);
                    if (urlMatch) {
                        const url = urlMatch[0];
                        const info = detectDocumentInfo(url);
                        text = text.replace(urlMatch[0], '').trim();
                        linkHtml = ` <a href="${escapeHtmlAttr(url)}" target="_blank" rel="noopener noreferrer" contenteditable="false" data-icon="${info.icon}" data-type="${escapeHtmlAttr(info.type)}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50/90 hover:bg-blue-100 text-blue-700 border border-blue-200/90 font-semibold text-xs no-underline mx-2 align-middle transition cursor-pointer select-none shadow-2xs" title="Open ${info.type} in new tab"><span>${info.icon} ${info.type}</span> <span class="text-[10px] opacity-75 font-bold">↗</span></a>\u00A0`;
                    }
                }

                const lineDiv = document.createElement('div');
                lineDiv.innerHTML = `•\u00A0${escapeHtml(text)}${linkHtml}`;
                editor.appendChild(lineDiv);
            });
        } else {
            const lineDiv = document.createElement('div');
            lineDiv.innerHTML = '•\u00A0';
            editor.appendChild(lineDiv);
        }
    }

    updateModalPointsBadge();

    // Show modal
    const modal = document.getElementById('point-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    setTimeout(() => {
        if (editor) {
            editor.focus();
        }
    }, 50);
}

function closePointModal() {
    const modal = document.getElementById('point-modal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function updateModalPointsBadge() {
    const badge = document.getElementById('point-modal-badge');
    const editor = document.getElementById('point-modal-editor');
    if (!badge || !editor) return;

    let count = 0;
    const lines = editor.innerText.split('\n');
    lines.forEach(line => {
        const clean = line.replace(/^[•\-\*\d+\.]\s*/, '').trim();
        if (clean.length > 0) count++;
    });

    badge.textContent = `${count} point${count === 1 ? '' : 's'}`;
}

function savePointModal() {
    const editor = document.getElementById('point-modal-editor');
    if (!editor) return;

    const points = [];
    const childNodes = Array.from(editor.childNodes);
    const lineElements = [];

    childNodes.forEach(node => {
        if (node.nodeType === Node.ELEMENT_NODE && node.tagName.toLowerCase() === 'div') {
            lineElements.push(node);
        } else if (node.nodeType === Node.TEXT_NODE && node.textContent.trim().length > 0) {
            const d = document.createElement('div');
            d.textContent = node.textContent;
            lineElements.push(d);
        }
    });

    if (lineElements.length === 0) {
        const raw = editor.innerText || editor.textContent || '';
        raw.split('\n').forEach(l => {
            const clean = l.replace(/^[•\-\*\d+\.]\s*/, '').trim();
            if (clean.length > 0) points.push(clean);
        });
    } else {
        lineElements.forEach(lineEl => {
            const linkA = lineEl.querySelector('a[href]');
            const url = linkA ? linkA.getAttribute('href') : null;
            let icon = linkA ? linkA.getAttribute('data-icon') : null;
            let type = linkA ? linkA.getAttribute('data-type') : null;

            if (linkA && (!icon || !type)) {
                const textBadge = linkA.innerText || linkA.textContent || '';
                const clean = textBadge.replace(/↗/g, '').trim();
                const m = clean.match(/^([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎])\s*(.*)$/u);
                if (m) {
                    icon = m[1];
                    type = m[2];
                }
            }

            const clone = lineEl.cloneNode(true);
            const cloneA = clone.querySelector('a');
            if (cloneA) cloneA.remove();

            let text = clone.innerText || clone.textContent || '';
            text = text.replace(/\u00A0/g, ' ').replace(/^[•\-\*\d+\.]\s*/, '').trim();

            if (url) {
                if (!icon || !type) {
                    const info = detectDocumentInfo(url);
                    icon = info.icon;
                    type = info.type;
                }
                const badge = `( ${icon} ${type} )`;
                const combined = text ? `${text} ${badge} ${url}` : `${badge} ${url}`;
                points.push(combined);
            } else if (text.length > 0) {
                points.push(text);
            }
        });
    }

    const pillarNum = currentModalPillar;
    const subKey = currentModalKey;
    const field = pointSectionMeta[pillarNum]?.field || 'achievements';

    // Update hidden inputs container
    const container = document.getElementById(`point-inputs-${pillarNum}-${subKey}`);
    if (container) {
        container.innerHTML = points.map(p => `
            <input type="hidden"
                   name="pillar_${pillarNum}_${field}[${subKey}][points][]"
                   value="${escapeHtmlAttr(p)}"
                   class="bullet-input"
                   data-pillar="${pillarNum}"
                   data-key="${subKey}">
        `).join('');
    }

    updatePillarCounts();
    closePointModal();
}

function escapeHtmlAttr(text) {
    if (!text) return '';
    return text
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function renderPointPreview(pt) {
    // 1. Check for custom badge + url pattern: ( Icon Name - Type ) URL
    const badgeMatch = pt.match(/(?:\(\s*([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎][^()\n]+?)\s*\)|\[\s*([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎][^\[\]\n]+?)\s*\])\s*\(?\s*(https?:\/\/[^\s<>"'\)]+)\s*\)?/u);
    if (badgeMatch) {
        const label = badgeMatch[1] || badgeMatch[2];
        const url = badgeMatch[3];
        const textOnly = pt.replace(badgeMatch[0], '').trim();
        const linkHtml = `<a href="${escapeHtmlAttr(url)}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 font-semibold text-[11px] transition shadow-2xs align-middle mx-1.5 my-0.5">${escapeHtml(label)} <span class="text-[10px] opacity-70">↗</span></a>`;
        return textOnly ? `${escapeHtml(textOnly)} ${linkHtml}` : linkHtml;
    }

    // 2. Check for raw URL
    const urlMatch = pt.match(/https?:\/\/[^\s<>"')]+/i);
    if (!urlMatch) {
        return escapeHtml(pt);
    }
    const url = urlMatch[0];
    const textOnly = pt.replace(url, '').trim();
    const info = detectDocumentInfo(url);
    const label = `${info.icon} ${info.type}`;

    const linkHtml = `<a href="${escapeHtmlAttr(url)}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 font-semibold text-[11px] transition shadow-2xs align-middle mx-1.5 my-0.5">${escapeHtml(label)} <span class="text-[10px] opacity-70">↗</span></a>`;

    return textOnly ? `${escapeHtml(textOnly)} ${linkHtml}` : linkHtml;
}

function refreshPillarSummaries() {
    const subKeys = {
        1: ['milestones', 'impact', 'stories'],
        2: ['operational', 'resources', 'risks'],
        3: ['lessons', 'feedback', 'innovation'],
        4: ['performance', 'data_quality', 'evaluation'],
        5: ['project_collab', 'partnerships', 'cross_learning']
    };

    for (let p = 1; p <= 5; p++) {
        let totalCount = 0;
        subKeys[p].forEach(key => {
            const container = document.getElementById(`point-inputs-${p}-${key}`);
            if (container) {
                container.querySelectorAll('input.bullet-input').forEach(inp => {
                    if (inp.value && inp.value.trim().length > 0) totalCount++;
                });
            }
        });

        const narrative = document.getElementById(`pillar_${p}_narrative`);
        const hasNarrative = narrative && narrative.value.trim().length > 0;

        const summaryEl = document.getElementById(`pillar-summary-${p}`);
        if (summaryEl) {
            if (totalCount === 0 && !hasNarrative) {
                summaryEl.innerHTML = '<span class="italic text-slate-400">Not yet filled</span>';
            } else {
                let badgeText = '';
                if (totalCount > 0) {
                    badgeText += `${totalCount} point${totalCount === 1 ? '' : 's'} recorded`;
                }
                if (hasNarrative) {
                    badgeText += (badgeText ? ' • ' : '') + 'Detailed summary included';
                }
                summaryEl.innerHTML = `
                    <div class="flex items-center gap-2 text-xs text-emerald-700 font-semibold not-italic">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>${badgeText}</span>
                    </div>
                `;
            }
        }
    }
}

function refreshPointSummaries() {
    const subKeys = {
        1: ['milestones', 'impact', 'stories'],
        2: ['operational', 'resources', 'risks'],
        3: ['lessons', 'feedback', 'innovation'],
        4: ['performance', 'data_quality', 'evaluation'],
        5: ['project_collab', 'partnerships', 'cross_learning']
    };

    for (let p = 1; p <= 5; p++) {
        subKeys[p].forEach(key => {
            const container = document.getElementById(`point-inputs-${p}-${key}`);
            const summaryEl = document.getElementById(`point-summary-${p}-${key}`);
            const badge = document.getElementById(`badge-count-${p}-${key}`);
            if (!container || !summaryEl) return;

            const bullets = [];
            container.querySelectorAll('input.bullet-input').forEach(inp => {
                if (inp.value && inp.value.trim().length > 0) {
                    bullets.push(inp.value.trim());
                }
            });

            if (badge) {
                badge.textContent = `${bullets.length} points`;
            }

            if (bullets.length === 0) {
                summaryEl.className = 'text-xs text-slate-400 italic';
                summaryEl.innerHTML = 'Not yet filled';
            } else {
                summaryEl.className = 'text-xs text-slate-700 space-y-2 pt-1';
                summaryEl.innerHTML = bullets.map(b => `
                    <div class="flex items-start gap-2.5 py-1 border-b border-slate-100/80 last:border-b-0">
                        <span class="text-blue-500 font-bold leading-relaxed">•</span>
                        <div class="font-normal text-slate-700 leading-relaxed flex-1">${renderPointPreview(b)}</div>
                    </div>
                `).join('');
            }
        });
    }
}

// --- Realtime Item & Badge Counting ---
function updatePillarCounts() {
    const subKeys = {
        1: ['milestones', 'impact', 'stories'],
        2: ['operational', 'resources', 'risks'],
        3: ['lessons', 'feedback', 'innovation'],
        4: ['performance', 'data_quality', 'evaluation'],
        5: ['project_collab', 'partnerships', 'cross_learning']
    };

    for (let p = 1; p <= 5; p++) {
        let pillarTotal = 0;
        subKeys[p].forEach(key => {
            const container = document.getElementById(`point-inputs-${p}-${key}`);
            const badge = document.getElementById(`badge-count-${p}-${key}`);
            let count = 0;
            if (container) {
                const inputs = container.querySelectorAll('input.bullet-input');
                inputs.forEach(inp => {
                    if (inp.value && inp.value.trim().length > 0) count++;
                });
            }
            if (badge) badge.textContent = `${count} points`;
            pillarTotal += count;
        });

        const tabCount = document.getElementById(`pillar-tab-count-${p}`);
        if (tabCount) tabCount.textContent = `${pillarTotal} pts`;
    }

    refreshPillarSummaries();
    refreshPointSummaries();
}

// --- Form Serialization & Sync ---
document.getElementById('activity-entry-form').addEventListener('submit', function(e) {
    // Sanitize location
    const locInput = document.getElementById('activity_location_input');
    if (locInput && (locInput.value.startsWith('http://') || locInput.value.startsWith('https://'))) {
        locInput.value = '';
    }

    // Sync flat text fields
    function getBulletLines(pillar, key) {
        const container = document.getElementById(`point-inputs-${pillar}-${key}`);
        if (!container) return '';
        const lines = [];
        container.querySelectorAll('input.bullet-input').forEach(inp => {
            const v = inp.value ? inp.value.trim() : '';
            if (v.length > 0) lines.push(v);
        });
        return lines.join('\n');
    }

    // Pillar 1
    document.getElementById('flat_achievements_milestones').value = getBulletLines(1, 'milestones');
    document.getElementById('flat_achievements_impact').value = getBulletLines(1, 'impact');
    document.getElementById('flat_achievements_stories').value = getBulletLines(1, 'stories');
    const p1Narr = document.getElementById('pillar_1_narrative');
    if (p1Narr) document.getElementById('flat_achievements_narrative').value = p1Narr.value.trim();

    // Pillar 2
    document.getElementById('flat_challenges_operational').value = getBulletLines(2, 'operational');
    document.getElementById('flat_challenges_resources').value = getBulletLines(2, 'resources');
    document.getElementById('flat_challenges_risks').value = getBulletLines(2, 'risks');
    const p2Narr = document.getElementById('pillar_2_narrative');
    if (p2Narr) document.getElementById('flat_challenges_narrative').value = p2Narr.value.trim();

    // Pillar 3
    document.getElementById('flat_learning_lessons').value = getBulletLines(3, 'lessons');
    document.getElementById('flat_learning_feedback').value = getBulletLines(3, 'feedback');
    document.getElementById('flat_learning_innovation').value = getBulletLines(3, 'innovation');
    const p3Narr = document.getElementById('pillar_3_narrative');
    if (p3Narr) document.getElementById('flat_learning_narrative').value = p3Narr.value.trim();

    // Pillar 4
    document.getElementById('flat_mne_performance').value = getBulletLines(4, 'performance');
    document.getElementById('flat_mne_data_quality').value = getBulletLines(4, 'data_quality');
    document.getElementById('flat_mne_evaluation_plans').value = getBulletLines(4, 'evaluation');
    const p4Narr = document.getElementById('pillar_4_narrative');
    if (p4Narr) document.getElementById('flat_mne_narrative').value = p4Narr.value.trim();

    // Pillar 5
    document.getElementById('flat_collab_projects').value = getBulletLines(5, 'project_collab');
    document.getElementById('flat_collab_partnerships').value = getBulletLines(5, 'partnerships');
    document.getElementById('flat_collab_cross_learning').value = getBulletLines(5, 'cross_learning');
    const p5Narr = document.getElementById('pillar_5_narrative');
    if (p5Narr) document.getElementById('flat_collab_narrative').value = p5Narr.value.trim();
});

// --- LocalStorage Draft Backup ---
const storageKey = 'PIFZ_ACTIVITY_DRAFT_{{ $isEdit ? ($activity->token ?? "") : (old("project_id", $preselectedProject->id ?? "NEW")) }}';

function saveDraftManual() {
    try {
        const draft = {
            savedAt: new Date().toISOString(),
            title: document.getElementById('activity_title_input')?.value || '',
            date: document.getElementById('activity_date_input')?.value || '',
            location: document.getElementById('activity_location_input')?.value || ''
        };
        localStorage.setItem(storageKey, JSON.stringify(draft));
        const statusText = document.getElementById('draft-status-text');
        if (statusText) {
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            statusText.textContent = 'Draft saved ' + time;
        }
    } catch(e) {}
}

// --- Activity Type, Period, and Venue/Location dynamic handler ---
let currentOngoingSubtype = '{{ in_array($rawActivityType, ['class', 'session']) ? $rawActivityType : 'training' }}';

function setOngoingSubtype(subtype) {
    currentOngoingSubtype = subtype;
    const ongoingRadio = document.getElementById('activity_type_ongoing');
    if (ongoingRadio) {
        ongoingRadio.value = subtype;
        ongoingRadio.checked = true;
    }
    
    ['training', 'class', 'session'].forEach(st => {
        const btn = document.getElementById('subtype-btn-' + st);
        if (btn) {
            if (st === subtype) {
                btn.className = 'cursor-pointer px-2.5 py-1 rounded-lg text-xs font-semibold transition border bg-purple-600 text-white border-purple-600 shadow-2xs';
            } else {
                btn.className = 'cursor-pointer px-2.5 py-1 rounded-lg text-xs font-semibold transition border bg-white text-purple-800 border-purple-200 hover:bg-purple-50';
            }
        }
    });
}

function handleActivityTypeChange(type) {
    const isOngoing = (type === 'ongoing' || type === 'training' || type === 'class' || type === 'session');
    
    // Update active styles on radio buttons
    document.querySelectorAll('.activity-type-btn').forEach(btn => {
        const btnType = btn.getAttribute('data-type');
        const radio = btn.querySelector('input[type="radio"]');
        if ((isOngoing && btnType === 'ongoing') || (!isOngoing && btnType === 'activity')) {
            if (radio) radio.checked = true;
            btn.classList.remove('border-slate-200', 'bg-slate-50');
            if (isOngoing) {
                btn.classList.add('border-purple-500', 'bg-purple-50/60', 'shadow-xs', 'ring-2', 'ring-purple-500/20');
                btn.classList.remove('border-blue-500', 'bg-blue-50/60', 'ring-blue-500/20');
            } else {
                btn.classList.add('border-blue-500', 'bg-blue-50/60', 'shadow-xs', 'ring-2', 'ring-blue-500/20');
                btn.classList.remove('border-purple-500', 'bg-purple-50/60', 'ring-purple-500/20');
            }
        } else {
            btn.classList.remove('border-blue-500', 'bg-blue-50/60', 'border-purple-500', 'bg-purple-50/60', 'shadow-xs', 'ring-2', 'ring-blue-500/20', 'ring-purple-500/20');
            btn.classList.add('border-slate-200', 'bg-slate-50');
        }
    });

    // Update nature pill
    const naturePill = document.getElementById('activity-nature-pill');
    if (naturePill) {
        if (isOngoing) {
            naturePill.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200';
            naturePill.textContent = 'Ongoing / Multi-Day Schedule';
        } else {
            naturePill.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200';
            naturePill.textContent = 'Single-Day Activity';
        }
    }

    // Toggle Period Containers
    const singleContainer = document.getElementById('single-day-period-container');
    const ongoingContainer = document.getElementById('ongoing-period-container');
    if (singleContainer && ongoingContainer) {
        if (isOngoing) {
            singleContainer.classList.add('hidden');
            ongoingContainer.classList.remove('hidden');
        } else {
            singleContainer.classList.remove('hidden');
            ongoingContainer.classList.add('hidden');
        }
    }

    // Toggle Venue vs Location
    const venueLocLabel = document.getElementById('venue-location-label');
    const venueLocHint = document.getElementById('venue-location-hint');
    const locInput = document.getElementById('activity_location_input');
    if (venueLocLabel) {
        venueLocLabel.textContent = isOngoing ? 'Location' : 'Venue';
    }
    if (venueLocHint) {
        venueLocHint.textContent = isOngoing ? 'Facility / Lab Room / Online' : 'Event Grounds / Stadium';
    }
    if (locInput) {
        locInput.placeholder = isOngoing
            ? 'e.g. Digital Skills Innovation Hub - Lab 1, Livingstone or Online'
            : 'e.g. Maramba Community Grounds, Livingstone';
    }

    syncDates();
}

function syncDates() {
    const singleDateInput = document.getElementById('activity_date_input');
    const startDateInput = document.getElementById('start_date_input');
    const endDateInput = document.getElementById('end_date_input');
    const durationBadge = document.getElementById('period-duration-badge');

    // Sync values between single and start date
    if (startDateInput && singleDateInput) {
        if (startDateInput.value) {
            singleDateInput.value = startDateInput.value;
        } else if (singleDateInput.value) {
            startDateInput.value = singleDateInput.value;
        }
    }

    // Calculate duration badge
    if (durationBadge && startDateInput && endDateInput) {
        if (startDateInput.value && endDateInput.value) {
            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);
            const diffTime = end - start;
            if (diffTime >= 0) {
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                const weeks = Math.ceil(diffDays / 7);
                const text = diffDays >= 14
                    ? `${weeks} Weeks (${diffDays} days)`
                    : `${diffDays} Day${diffDays > 1 ? 's' : ''}`;
                durationBadge.textContent = `⏱️ Duration: ${text}`;
                durationBadge.className = 'text-xs font-bold px-2.5 py-1 rounded-xl bg-purple-600 text-white shadow-xs';
            } else {
                durationBadge.textContent = '⚠️ End date must be after start date';
                durationBadge.className = 'text-xs font-bold px-2.5 py-1 rounded-xl bg-rose-600 text-white shadow-xs';
            }
        } else if (startDateInput.value) {
            durationBadge.textContent = '⏱️ Set End Date';
            durationBadge.className = 'text-xs font-bold px-2.5 py-1 rounded-xl bg-purple-100 text-purple-800';
        }
    }
}

// Initial count calculation and input event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Clear unexpected URL in location input if autofilled
    const locInput = document.getElementById('activity_location_input');
    if (locInput && (locInput.value.startsWith('http://') || locInput.value.startsWith('https://'))) {
        locInput.value = '';
    }

    document.querySelectorAll('input.bullet-input').forEach(inp => {
        inp.addEventListener('input', updatePillarCounts);
    });

    for (let p = 1; p <= 5; p++) {
        const narr = document.getElementById(`pillar_${p}_narrative`);
        if (narr) {
            narr.addEventListener('input', refreshPillarSummaries);
        }
    }

    // Point modal editor Enter & keyboard shortcuts
    const editor = document.getElementById('point-modal-editor');
    if (editor) {
        editor.addEventListener('keydown', function(e) {
            // Ctrl + Enter to save
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                savePointModal();
                return;
            }

            // Normal Enter key creates a new bullet div
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const sel = window.getSelection();
                if (sel && sel.rangeCount > 0) {
                    const range = sel.getRangeAt(0);
                    const newDiv = document.createElement('div');
                    newDiv.innerHTML = '•\u00A0';

                    // Insert after current block
                    let currentBlock = range.startContainer;
                    while (currentBlock && currentBlock.parentNode !== editor) {
                        currentBlock = currentBlock.parentNode;
                    }

                    if (currentBlock && currentBlock.parentNode === editor) {
                        currentBlock.parentNode.insertBefore(newDiv, currentBlock.nextSibling);
                    } else {
                        editor.appendChild(newDiv);
                    }

                    range.setStart(newDiv, newDiv.childNodes.length);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
                updateModalPointsBadge();
            }
        });

        editor.addEventListener('input', updateModalPointsBadge);
    }

    // Drive link input Enter key triggers "Done"
    const linkInput = document.getElementById('drive-link-input');
    if (linkInput) {
        linkInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyDriveLinkAndClose();
            }
        });
    }

    // Close modal on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const linkBox = document.getElementById('drive-link-box');
            if (linkBox && !linkBox.classList.contains('hidden')) {
                linkBox.classList.add('hidden');
                const ed = document.getElementById('point-modal-editor');
                if (ed) ed.focus();
                return;
            }
            const modal = document.getElementById('point-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closePointModal();
            }
        }
    });

    updatePillarCounts();
    refreshPillarSummaries();
    refreshPointSummaries();
    syncDates();
});
</script>
@endsection
