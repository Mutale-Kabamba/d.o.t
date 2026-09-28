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
        'narrative' => old('pillar_1_narrative', $activity->pillar_1_narrative ?? $activity->achievements_narrative ?? ''),
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
        'narrative' => old('pillar_2_narrative', $activity->pillar_2_narrative ?? $activity->challenges_narrative ?? ''),
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
        'narrative' => old('pillar_3_narrative', $activity->pillar_3_narrative ?? $activity->learning_narrative ?? ''),
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
        'narrative' => old('pillar_4_narrative', $activity->pillar_4_narrative ?? $activity->mne_narrative ?? ''),
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
        'narrative' => old('pillar_5_narrative', $activity->pillar_5_narrative ?? $activity->collab_narrative ?? ''),
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

            <!-- 2. Smart Stepper & Tabbed Pillar Navigation Bar -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-xs sticky top-18 z-30 space-y-3">
                <!-- Stepper Pills -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                    <!-- Pillar 1 -->
                    <button type="button" onclick="switchPillar(1)" id="pillar-tab-1"
                            data-active-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-blue-600 text-white shadow-md shadow-blue-500/20"
                            data-inactive-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200"
                            class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-blue-600 text-white shadow-md shadow-blue-500/20">
                        <div class="min-w-0">
                            <div class="text-[10px] font-black uppercase tracking-wider opacity-80">Pillar 1</div>
                            <div class="text-xs font-bold truncate">Achievements</div>
                        </div>
                        <span id="pillar-tab-count-1" class="text-xs shrink-0 ml-1">0 pts</span>
                    </button>

                    <!-- Pillar 2 -->
                    <button type="button" onclick="switchPillar(2)" id="pillar-tab-2"
                            data-active-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-amber-600 text-white shadow-md shadow-amber-500/20"
                            data-inactive-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200"
                            class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200">
                        <div class="min-w-0">
                            <div class="text-[10px] font-black uppercase tracking-wider opacity-80">Pillar 2</div>
                            <div class="text-xs font-bold truncate">Challenges &amp; Risks</div>
                        </div>
                        <span id="pillar-tab-count-2" class="text-xs shrink-0 ml-1">0 pts</span>
                    </button>

                    <!-- Pillar 3 -->
                    <button type="button" onclick="switchPillar(3)" id="pillar-tab-3"
                            data-active-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-purple-600 text-white shadow-md shadow-purple-500/20"
                            data-inactive-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200"
                            class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200">
                        <div class="min-w-0">
                            <div class="text-[10px] font-black uppercase tracking-wider opacity-80">Pillar 3</div>
                            <div class="text-xs font-bold truncate">Learning</div>
                        </div>
                        <span id="pillar-tab-count-3" class="text-xs shrink-0 ml-1">0 pts</span>
                    </button>

                    <!-- Pillar 4 -->
                    <button type="button" onclick="switchPillar(4)" id="pillar-tab-4"
                            data-active-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-indigo-600 text-white shadow-md shadow-indigo-500/20"
                            data-inactive-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200"
                            class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200">
                        <div class="min-w-0">
                            <div class="text-[10px] font-black uppercase tracking-wider opacity-80">Pillar 4</div>
                            <div class="text-xs font-bold truncate">M&amp;E Metrics</div>
                        </div>
                        <span id="pillar-tab-count-4" class="text-xs shrink-0 ml-1">0 pts</span>
                    </button>

                    <!-- Pillar 5 -->
                    <button type="button" onclick="switchPillar(5)" id="pillar-tab-5"
                            data-active-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-emerald-600 text-white shadow-md shadow-emerald-500/20 col-span-2 sm:col-span-1"
                            data-inactive-class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 col-span-2 sm:col-span-1"
                            class="p-2.5 rounded-xl text-left transition flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 col-span-2 sm:col-span-1">
                        <div class="min-w-0">
                            <div class="text-[10px] font-black uppercase tracking-wider opacity-80">Pillar 5</div>
                            <div class="text-xs font-bold truncate">Collaboration</div>
                        </div>
                        <span id="pillar-tab-count-5" class="text-xs shrink-0 ml-1">0 pts</span>
                    </button>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div id="pillar-progress-bar" class="h-full transition-all duration-300 ease-out bg-blue-600" style="width: 20%;"></div>
                </div>
            </div>

            <!-- ==================== PILLAR 1: PROJECT ACHIEVEMENTS ==================== -->
            <div id="pillar-panel-1" class="space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-blue-600 text-white font-black text-base flex items-center justify-center shrink-0 shadow-xs">
                                1
                            </span>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Pillar 1: Project Achievements</h3>
                                <p class="text-xs text-slate-500">Capture discrete milestones, quantitative impact evidence, and positive human stories.</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full shrink-0">
                            Step 1 of 5
                        </span>
                    </div>

                    <!-- 3 Dynamic Sub-Focus Bullet Builders with Dedicated Qualitative Narrative Entries -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Key Milestones -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        <span>1. Key Milestones</span>
                                    </div>
                                    <span id="badge-count-1-milestones" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Major operational, structural, or program activities completed this cycle.</p>

                                <div id="bullet-container-1-milestones" class="space-y-2">
                                    @foreach($initialPillars[1]['milestones']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_1_achievements[milestones][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. 5 Coach Clinics conducted across 3 zones"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bullet-input"
                                               data-pillar="1" data-key="milestones">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(1, 'milestones', 'e.g. 5 Coach Clinics conducted across 3 zones', 'blue')" class="w-full py-1.5 bg-white hover:bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Milestone Point</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded">Milestones Context</span>
                                    </div>
                                    <textarea name="pillar_1_achievements[milestones][narrative]"
                                              rows="3"
                                              placeholder="Provide qualitative narrative on operational context, stakeholder execution, and underlying drivers behind these milestones..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[1]['milestones']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Impact Evidence -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        <span>2. Impact Evidence</span>
                                    </div>
                                    <span id="badge-count-1-impact" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Tangible changes, participant numbers, percentages (e.g. 98% passed), or survey data.</p>

                                <div id="bullet-container-1-impact" class="space-y-2">
                                    @foreach($initialPillars[1]['impact']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_1_achievements[impact][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. 94% retention rate achieved among girls cohort"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bullet-input"
                                               data-pillar="1" data-key="impact">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(1, 'impact', 'e.g. 94% retention rate achieved among girls cohort', 'blue')" class="w-full py-1.5 bg-white hover:bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Impact Evidence</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded">Impact Deep-Dive</span>
                                    </div>
                                    <textarea name="pillar_1_achievements[impact][narrative]"
                                              rows="3"
                                              placeholder="Detail qualitative change observations, participant testimonials, behavioral shifts, and baseline comparisons..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[1]['impact']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Success Stories -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        <span>3. Success Stories</span>
                                    </div>
                                    <span id="badge-count-1-stories" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Individual or community transformation highlights, quotes, and peer role model wins.</p>

                                <div id="bullet-container-1-stories" class="space-y-2">
                                    @foreach($initialPillars[1]['stories']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_1_achievements[stories][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Chanda secured tertiary scholarship after leadership training"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bullet-input"
                                               data-pillar="1" data-key="stories">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(1, 'stories', 'e.g. Chanda secured tertiary scholarship after leadership training', 'blue')" class="w-full py-1.5 bg-white hover:bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Success Story</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded">Human Story</span>
                                    </div>
                                    <textarea name="pillar_1_achievements[stories][narrative]"
                                              rows="3"
                                              placeholder="Share the full human story, participant biography, direct quotes, and long-term trajectory..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[1]['stories']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== PILLAR 2: CHALLENGES & RISKS ==================== -->
            <div id="pillar-panel-2" class="space-y-6 hidden">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-amber-600 text-white font-black text-base flex items-center justify-center shrink-0 shadow-xs">
                                2
                            </span>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Pillar 2: Challenges &amp; Risks</h3>
                                <p class="text-xs text-slate-500">Document operational hurdles, resource constraints, and proactive risk monitoring protocols.</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full shrink-0">
                            Step 2 of 5
                        </span>
                    </div>

                    <!-- 3 Dynamic Sub-Focus Bullet Builders with Dedicated Qualitative Narrative Entries -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Operational Challenges -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                        <span>1. Operational Challenges</span>
                                    </div>
                                    <span id="badge-count-2-operational" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Field execution bottlenecks, transport, scheduling, or attendance barriers.</p>

                                <div id="bullet-container-2-operational" class="space-y-2">
                                    @foreach($initialPillars[2]['operational']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_2_challenges[operational][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Heavy seasonal rains delayed week 4 fixtures"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 bullet-input"
                                               data-pillar="2" data-key="operational">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(2, 'operational', 'e.g. Heavy seasonal rains delayed week 4 fixtures', 'amber')" class="w-full py-1.5 bg-white hover:bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Operational Point</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded">Operational Context</span>
                                    </div>
                                    <textarea name="pillar_2_challenges[operational][narrative]"
                                              rows="3"
                                              placeholder="Detail operational bottlenecks encountered, root-cause analyses, and concrete on-ground resolution steps taken..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[2]['operational']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Resource Gaps -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                        <span>2. Resource Gaps</span>
                                    </div>
                                    <span id="badge-count-2-resources" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Deficits in kit, sports equipment, learning materials, or venue accessibility.</p>

                                <div id="bullet-container-2-resources" class="space-y-2">
                                    @foreach($initialPillars[2]['resources']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_2_challenges[resources][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Additional size 4 footballs and bibs required for junior hub"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 bullet-input"
                                               data-pillar="2" data-key="resources">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(2, 'resources', 'e.g. Additional size 4 footballs and bibs required for junior hub', 'amber')" class="w-full py-1.5 bg-white hover:bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Resource Gap</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded">Resource Needs</span>
                                    </div>
                                    <textarea name="pillar_2_challenges[resources][narrative]"
                                              rows="3"
                                              placeholder="Elaborate on specific resource deficits, programmatic implications, and procurement/partner mobilization plans..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[2]['resources']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Risk Monitoring -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                        <span>3. Risk Monitoring</span>
                                    </div>
                                    <span id="badge-count-2-risks" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Safeguarding, child protection, health risks, or external dependencies with mitigation.</p>

                                <div id="bullet-container-2-risks" class="space-y-2">
                                    @foreach($initialPillars[2]['risks']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_2_challenges[risks][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Safeguarding refreshers completed for all volunteer referees"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 bullet-input"
                                               data-pillar="2" data-key="risks">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(2, 'risks', 'e.g. Safeguarding refreshers completed for all volunteer referees', 'amber')" class="w-full py-1.5 bg-white hover:bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Risk Monitoring Point</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded">Risk Analysis</span>
                                    </div>
                                    <textarea name="pillar_2_challenges[risks][narrative]"
                                              rows="3"
                                              placeholder="Provide detailed narrative on safeguarding risk monitoring, external environment changes, and active mitigation protocols..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[2]['risks']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== PILLAR 3: LEARNING & ADAPTATION ==================== -->
            <div id="pillar-panel-3" class="space-y-6 hidden">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-purple-600 text-white font-black text-base flex items-center justify-center shrink-0 shadow-xs">
                                3
                            </span>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Pillar 3: Learning &amp; Adaptation</h3>
                                <p class="text-xs text-slate-500">Record insights, direct participant stakeholder feedback, and novel innovation experiments.</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-full shrink-0">
                            Step 3 of 5
                        </span>
                    </div>

                    <!-- 3 Dynamic Sub-Focus Bullet Builders with Dedicated Qualitative Narrative Entries -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Lessons Learned -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                        <span>1. Lessons Learned</span>
                                    </div>
                                    <span id="badge-count-3-lessons" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Key takeaways from this activity that will shape future delivery.</p>

                                <div id="bullet-container-3-lessons" class="space-y-2">
                                    @foreach($initialPillars[3]['lessons']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_3_learning[lessons][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Afternoon sessions increased girl attendance by 40%"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 bullet-input"
                                               data-pillar="3" data-key="lessons">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(3, 'lessons', 'e.g. Afternoon sessions increased girl attendance by 40%', 'purple')" class="w-full py-1.5 bg-white hover:bg-purple-50 text-purple-700 border border-purple-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Lesson Learned</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-purple-700 bg-purple-50 border border-purple-200 px-1.5 py-0.5 rounded">Adaptive Insight</span>
                                    </div>
                                    <textarea name="pillar_3_learning[lessons][narrative]"
                                              rows="3"
                                              placeholder="Provide full qualitative analysis on how lessons learned will refine subsequent programming workflows..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[3]['lessons']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Community Feedback -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                        <span>2. Community Feedback</span>
                                    </div>
                                    <span id="badge-count-3-feedback" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Direct feedback received from youth participants, parents, and community leaders.</p>

                                <div id="bullet-container-3-feedback" class="space-y-2">
                                    @foreach($initialPillars[3]['feedback']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_3_learning[feedback][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Parents requested additional financial literacy modules"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 bullet-input"
                                               data-pillar="3" data-key="feedback">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(3, 'feedback', 'e.g. Parents requested additional financial literacy modules', 'purple')" class="w-full py-1.5 bg-white hover:bg-purple-50 text-purple-700 border border-purple-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Feedback Point</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-purple-700 bg-purple-50 border border-purple-200 px-1.5 py-0.5 rounded">Voice Synthesis</span>
                                    </div>
                                    <textarea name="pillar_3_learning[feedback][narrative]"
                                              rows="3"
                                              placeholder="Synthesize stakeholder voices, focus group feedback, community dialogue sentiments, and actionable adjustments..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[3]['feedback']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Innovation -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                        <span>3. Innovation</span>
                                    </div>
                                    <span id="badge-count-3-innovation" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Piloted novel tools, digital apps, peer-led workshops, or creative models.</p>

                                <div id="bullet-container-3-innovation" class="space-y-2">
                                    @foreach($initialPillars[3]['innovation']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_3_learning[innovation][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Piloted SMS attendance tracker with youth peer captains"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 bullet-input"
                                               data-pillar="3" data-key="innovation">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(3, 'innovation', 'e.g. Piloted SMS attendance tracker with youth peer captains', 'purple')" class="w-full py-1.5 bg-white hover:bg-purple-50 text-purple-700 border border-purple-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Innovation Point</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-purple-700 bg-purple-50 border border-purple-200 px-1.5 py-0.5 rounded">Pilot Context</span>
                                    </div>
                                    <textarea name="pillar_3_learning[innovation][narrative]"
                                              rows="3"
                                              placeholder="Describe innovative approaches explored, methodology tested, pilot feedback, and scalability potential..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[3]['innovation']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== PILLAR 4: MONITORING & EVALUATION ==================== -->
            <div id="pillar-panel-4" class="space-y-6 hidden">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-base flex items-center justify-center shrink-0 shadow-xs">
                                4
                            </span>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Pillar 4: Monitoring &amp; Evaluation</h3>
                                <p class="text-xs text-slate-500">Track target vs actual performance, data verification checks, and scheduled evaluation plans.</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full shrink-0">
                            Step 4 of 5
                        </span>
                    </div>

                    <!-- 3 Dynamic Sub-Focus Bullet Builders with Dedicated Qualitative Narrative Entries -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Performance -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                        <span>1. Performance</span>
                                    </div>
                                    <span id="badge-count-4-performance" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Progress against quarterly workplan targets and KPIs.</p>

                                <div id="bullet-container-4-performance" class="space-y-2">
                                    @foreach($initialPillars[4]['performance']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_4_monitoring[performance][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Achieved 96% of quarterly outreach target (480/500 youth)"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bullet-input"
                                               data-pillar="4" data-key="performance">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(4, 'performance', 'e.g. Achieved 96% of quarterly outreach target (480/500 youth)', 'indigo')" class="w-full py-1.5 bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Performance Metric</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-1.5 py-0.5 rounded">KPI Analysis</span>
                                    </div>
                                    <textarea name="pillar_4_monitoring[performance][narrative]"
                                              rows="3"
                                              placeholder="Provide detailed narrative on KPI performance drivers, variance analysis, and targets trajectory..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[4]['performance']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Data Quality -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                        <span>2. Data Quality</span>
                                    </div>
                                    <span id="badge-count-4-data_quality" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Verification checks, attendance audit logs, and data integrity reconciliations.</p>

                                <div id="bullet-container-4-data_quality" class="space-y-2">
                                    @foreach($initialPillars[4]['data_quality']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_4_monitoring[data_quality][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. 100% physical registers digitized and audited against mobile app"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bullet-input"
                                               data-pillar="4" data-key="data_quality">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(4, 'data_quality', 'e.g. 100% physical registers digitized and audited against mobile app', 'indigo')" class="w-full py-1.5 bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Data Quality Check</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-1.5 py-0.5 rounded">Audit Narrative</span>
                                    </div>
                                    <textarea name="pillar_4_monitoring[data_quality][narrative]"
                                              rows="3"
                                              placeholder="Detail verification protocols applied, anomalies resolved, attendance validation checks, and data reliability notes..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[4]['data_quality']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Evaluation Plans -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                        <span>3. Evaluation Plans</span>
                                    </div>
                                    <span id="badge-count-4-evaluation" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Midterm assessments, focus groups, survey cycles, or longitudinal reviews.</p>

                                <div id="bullet-container-4-evaluation" class="space-y-2">
                                    @foreach($initialPillars[4]['evaluation']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_4_monitoring[evaluation][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Endline survey scheduled for June 28 with 50 sample households"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bullet-input"
                                               data-pillar="4" data-key="evaluation">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(4, 'evaluation', 'e.g. Endline survey scheduled for June 28 with 50 sample households', 'indigo')" class="w-full py-1.5 bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Evaluation Plan</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-1.5 py-0.5 rounded">Evaluation Scope</span>
                                    </div>
                                    <textarea name="pillar_4_monitoring[evaluation][narrative]"
                                              rows="3"
                                              placeholder="Detail evaluation methodology, sample design, focus group plans, and research timelines..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[4]['evaluation']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== PILLAR 5: COLLABORATION & COORDINATION ==================== -->
            <div id="pillar-panel-5" class="space-y-6 hidden">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-black text-base flex items-center justify-center shrink-0 shadow-xs">
                                5
                            </span>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Pillar 5: Collaboration &amp; Coordination</h3>
                                <p class="text-xs text-slate-500">Capture cross-project partnerships, civic stakeholder alliances, and shared learnings.</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full shrink-0">
                            Step 5 of 5
                        </span>
                    </div>

                    <!-- 3 Dynamic Sub-Focus Bullet Builders with Dedicated Qualitative Narrative Entries -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Project Collaboration -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                        <span>1. Project Collaboration</span>
                                    </div>
                                    <span id="badge-count-5-project_collab" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Resource sharing and joint activities conducted with adjacent PIFZ projects.</p>

                                <div id="bullet-container-5-project_collab" class="space-y-2">
                                    @foreach($initialPillars[5]['project_collab']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_5_collaboration[project_collab][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Shared pitch resources and referees with Disability Sports project"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bullet-input"
                                               data-pillar="5" data-key="project_collab">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(5, 'project_collab', 'e.g. Shared pitch resources and referees with Disability Sports project', 'emerald')" class="w-full py-1.5 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Project Collab Point</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded">Project Synergy</span>
                                    </div>
                                    <textarea name="pillar_5_collaboration[project_collab][narrative]"
                                              rows="3"
                                              placeholder="Elaborate on joint sessions delivered, resource pooling efficiencies, and cross-project coordination dynamics..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[5]['project_collab']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Partnerships -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                        <span>2. Partnerships</span>
                                    </div>
                                    <span id="badge-count-5-partnerships" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Civic, ministry, local school, health center, and community leadership agreements.</p>

                                <div id="bullet-container-5-partnerships" class="space-y-2">
                                    @foreach($initialPillars[5]['partnerships']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_5_collaboration[partnerships][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Formalized MOU with Maramba Clinic for monthly health screenings"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bullet-input"
                                               data-pillar="5" data-key="partnerships">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(5, 'partnerships', 'e.g. Formalized MOU with Maramba Clinic for monthly health screenings', 'emerald')" class="w-full py-1.5 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Partnership Point</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded">Partner Alliance</span>
                                    </div>
                                    <textarea name="pillar_5_collaboration[partnerships][narrative]"
                                              rows="3"
                                              placeholder="Provide detailed narrative on external partner engagement, institutional alliances, and joint sustainability roadmaps..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[5]['partnerships']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Cross Learning -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                        <span>3. Cross Learning</span>
                                    </div>
                                    <span id="badge-count-5-cross_learning" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug">Knowledge shared across project officers, peer coaches, and regional team summits.</p>

                                <div id="bullet-container-5-cross_learning" class="space-y-2">
                                    @foreach($initialPillars[5]['cross_learning']['points'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_5_collaboration[cross_learning][points][]"
                                               value="{{ $val }}"
                                               placeholder="e.g. Shared coaching methodology toolkit at provincial quarterly summit"
                                               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bullet-input"
                                               data-pillar="5" data-key="cross_learning">
                                        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="pt-1">
                                    <button type="button" onclick="addBulletRow(5, 'cross_learning', 'e.g. Shared coaching methodology toolkit at provincial quarterly summit', 'emerald')" class="w-full py-1.5 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                        <span>+ Add Cross Learning Point</span>
                                    </button>
                                </div>

                                <!-- Respective Sub-Pillar Detailed Narrative & Qualitative Summary -->
                                <div class="pt-3 border-t border-slate-200/80 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span>Detailed Summary</span>
                                        </label>
                                        <span class="text-[9.5px] uppercase font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded">Knowledge Transfer</span>
                                    </div>
                                    <textarea name="pillar_5_collaboration[cross_learning][narrative]"
                                              rows="3"
                                              placeholder="Share qualitative insights on cross-team learning exchanges, peer mentor transfers, and organizational scaling..."
                                              class="w-full text-xs font-normal rounded-xl border border-slate-300 bg-white p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 leading-relaxed placeholder:text-slate-400 shadow-2xs">{{ $initialPillars[5]['cross_learning']['narrative'] }}</textarea>
                                </div>
                            </div>
                        </div>
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

            <!-- Stepper Action Buttons Navigation Footer -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="button" id="btn-prev-pillar" onclick="prevPillarStep()" class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition cursor-pointer flex items-center gap-1.5 hidden">
                        <span>← Previous Pillar</span>
                    </button>
                    <button type="button" onclick="saveDraftManual()" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition cursor-pointer flex items-center gap-1.5">
                        <span>💾 Save Draft</span>
                    </button>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <button type="button" id="btn-next-pillar" onclick="nextPillarStep()" class="px-6 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/10 transition cursor-pointer flex items-center gap-1.5">
                        <span id="btn-next-pillar-label">Next: Pillar 2 →</span>
                    </button>

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

function switchPillar(num) {
    currentActivePillar = num;
    for (let i = 1; i <= 5; i++) {
        const panel = document.getElementById(`pillar-panel-${i}`);
        const tab = document.getElementById(`pillar-tab-${i}`);
        if (panel) {
            if (i === num) {
                panel.classList.remove('hidden');
            } else {
                panel.classList.add('hidden');
            }
        }
        if (tab) {
            if (i === num) {
                tab.className = tab.getAttribute('data-active-class');
            } else {
                tab.className = tab.getAttribute('data-inactive-class');
            }
        }
    }

    // Progress bar
    const bar = document.getElementById('pillar-progress-bar');
    if (bar) {
        bar.style.width = (num * 20) + '%';
        bar.className = 'h-full transition-all duration-300 ease-out ' + (pillarColors[num] || 'bg-blue-600');
    }

    // Next / Prev buttons
    const btnPrev = document.getElementById('btn-prev-pillar');
    const btnNext = document.getElementById('btn-next-pillar');
    const btnNextLabel = document.getElementById('btn-next-pillar-label');

    if (btnPrev) {
        if (num > 1) {
            btnPrev.classList.remove('hidden');
        } else {
            btnPrev.classList.add('hidden');
        }
    }
    if (btnNext) {
        if (num < 5) {
            btnNext.classList.remove('hidden');
            if (btnNextLabel) btnNextLabel.textContent = `Next: Pillar ${num + 1} →`;
        } else {
            btnNext.classList.add('hidden');
        }
    }

    window.scrollTo({ top: 180, behavior: 'smooth' });
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

// --- Bullet Points Addition & Removal ---
function addBulletRow(pillarNum, subKey, placeholder, color) {
    const container = document.getElementById(`bullet-container-${pillarNum}-${subKey}`);
    if (!container) return;

    const div = document.createElement('div');
    div.className = 'flex items-start gap-1.5 group';
    div.innerHTML = `
        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
        <input type="text"
               name="pillar_${pillarNum}_${pillarFieldNames[pillarNum]}[${subKey}][points][]"
               value=""
               placeholder="${placeholder}"
               class="flex-1 text-xs font-medium rounded-xl border border-slate-300 bg-white p-2 focus:border-${color}-500 focus:ring-1 focus:ring-${color}-500 bullet-input"
               data-pillar="${pillarNum}" data-key="${subKey}">
        <button type="button" onclick="removeBulletRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Point">
            ✕
        </button>
    `;
    container.appendChild(div);
    const input = div.querySelector('input');
    if (input) {
        input.focus();
        input.addEventListener('input', updatePillarCounts);
    }
    updatePillarCounts();
}

function removeBulletRow(btn) {
    const row = btn.closest('.flex');
    const container = row.parentElement;
    if (container.querySelectorAll('.flex').length > 1) {
        row.remove();
    } else {
        const input = row.querySelector('input');
        if (input) input.value = '';
    }
    updatePillarCounts();
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
            const container = document.getElementById(`bullet-container-${p}-${key}`);
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
        const container = document.getElementById(`bullet-container-${pillar}-${key}`);
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

    // Pillar 2
    document.getElementById('flat_challenges_operational').value = getBulletLines(2, 'operational');
    document.getElementById('flat_challenges_resources').value = getBulletLines(2, 'resources');
    document.getElementById('flat_challenges_risks').value = getBulletLines(2, 'risks');

    // Pillar 3
    document.getElementById('flat_learning_lessons').value = getBulletLines(3, 'lessons');
    document.getElementById('flat_learning_feedback').value = getBulletLines(3, 'feedback');
    document.getElementById('flat_learning_innovation').value = getBulletLines(3, 'innovation');

    // Pillar 4
    document.getElementById('flat_mne_performance').value = getBulletLines(4, 'performance');
    document.getElementById('flat_mne_data_quality').value = getBulletLines(4, 'data_quality');
    document.getElementById('flat_mne_evaluation_plans').value = getBulletLines(4, 'evaluation');

    // Pillar 5
    document.getElementById('flat_collab_projects').value = getBulletLines(5, 'project_collab');
    document.getElementById('flat_collab_partnerships').value = getBulletLines(5, 'partnerships');
    document.getElementById('flat_collab_cross_learning').value = getBulletLines(5, 'cross_learning');
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

    updatePillarCounts();
    syncDates();
});
</script>
@endsection
