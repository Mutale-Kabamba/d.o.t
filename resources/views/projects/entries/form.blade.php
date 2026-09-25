@extends('layouts.app')

@section('title', ($isEdit ? 'Edit Activity Entry' : 'Log Thematic Activity') . ' - Play It Forward Zambia')

@section('content')
@php
$initialPillars = [
    1 => [
        'milestones' => $activity ? $activity->getPillarBullets(1, 'milestones') : old('pillar_1_achievements.milestones', !empty($activity->achievements_milestones) ? \App\Models\ActivityEntry::extractBulletPoints($activity->achievements_milestones) : ['']),
        'impact' => $activity ? $activity->getPillarBullets(1, 'impact') : old('pillar_1_achievements.impact', !empty($activity->achievements_impact) ? \App\Models\ActivityEntry::extractBulletPoints($activity->achievements_impact) : ['']),
        'stories' => $activity ? $activity->getPillarBullets(1, 'stories') : old('pillar_1_achievements.stories', !empty($activity->achievements_stories) ? \App\Models\ActivityEntry::extractBulletPoints($activity->achievements_stories) : ['']),
        'narrative' => old('pillar_1_narrative', $activity->pillar_1_narrative ?? $activity->achievements_narrative ?? ''),
    ],
    2 => [
        'operational' => $activity ? $activity->getPillarBullets(2, 'operational') : old('pillar_2_challenges.operational', !empty($activity->challenges_operational) ? \App\Models\ActivityEntry::extractBulletPoints($activity->challenges_operational) : ['']),
        'resources' => $activity ? $activity->getPillarBullets(2, 'resources') : old('pillar_2_challenges.resources', !empty($activity->challenges_resources) ? \App\Models\ActivityEntry::extractBulletPoints($activity->challenges_resources) : ['']),
        'risks' => $activity ? $activity->getPillarBullets(2, 'risks') : old('pillar_2_challenges.risks', !empty($activity->challenges_risks) ? \App\Models\ActivityEntry::extractBulletPoints($activity->challenges_risks) : ['']),
        'narrative' => old('pillar_2_narrative', $activity->pillar_2_narrative ?? $activity->challenges_narrative ?? ''),
    ],
    3 => [
        'lessons' => $activity ? $activity->getPillarBullets(3, 'lessons') : old('pillar_3_learning.lessons', !empty($activity->learning_lessons) ? \App\Models\ActivityEntry::extractBulletPoints($activity->learning_lessons) : ['']),
        'feedback' => $activity ? $activity->getPillarBullets(3, 'feedback') : old('pillar_3_learning.feedback', !empty($activity->learning_feedback) ? \App\Models\ActivityEntry::extractBulletPoints($activity->learning_feedback) : ['']),
        'innovation' => $activity ? $activity->getPillarBullets(3, 'innovation') : old('pillar_3_learning.innovation', !empty($activity->learning_innovation) ? \App\Models\ActivityEntry::extractBulletPoints($activity->learning_innovation) : ['']),
        'narrative' => old('pillar_3_narrative', $activity->pillar_3_narrative ?? $activity->learning_narrative ?? ''),
    ],
    4 => [
        'performance' => $activity ? $activity->getPillarBullets(4, 'performance') : old('pillar_4_monitoring.performance', !empty($activity->mne_performance) ? \App\Models\ActivityEntry::extractBulletPoints($activity->mne_performance) : ['']),
        'data_quality' => $activity ? $activity->getPillarBullets(4, 'data_quality') : old('pillar_4_monitoring.data_quality', !empty($activity->mne_data_quality) ? \App\Models\ActivityEntry::extractBulletPoints($activity->mne_data_quality) : ['']),
        'evaluation' => $activity ? $activity->getPillarBullets(4, 'evaluation') : old('pillar_4_monitoring.evaluation', !empty($activity->mne_evaluation_plans) ? \App\Models\ActivityEntry::extractBulletPoints($activity->mne_evaluation_plans) : ['']),
        'narrative' => old('pillar_4_narrative', $activity->pillar_4_narrative ?? $activity->mne_narrative ?? ''),
    ],
    5 => [
        'project_collab' => $activity ? $activity->getPillarBullets(5, 'project_collab') : old('pillar_5_collaboration.project_collab', !empty($activity->collab_projects) ? \App\Models\ActivityEntry::extractBulletPoints($activity->collab_projects) : ['']),
        'partnerships' => $activity ? $activity->getPillarBullets(5, 'partnerships') : old('pillar_5_collaboration.partnerships', !empty($activity->collab_partnerships) ? \App\Models\ActivityEntry::extractBulletPoints($activity->collab_partnerships) : ['']),
        'cross_learning' => $activity ? $activity->getPillarBullets(5, 'cross_learning') : old('pillar_5_collaboration.cross_learning', !empty($activity->collab_cross_learning) ? \App\Models\ActivityEntry::extractBulletPoints($activity->collab_cross_learning) : ['']),
        'narrative' => old('pillar_5_narrative', $activity->pillar_5_narrative ?? $activity->collab_narrative ?? ''),
    ],
];

// Ensure every bullet category is an array with at least one element if empty
foreach ($initialPillars as $pNum => &$pData) {
    foreach ($pData as $k => &$v) {
        if ($k !== 'narrative') {
            if (!is_array($v) || empty($v)) {
                $v = [''];
            }
        }
    }
}
unset($pData, $v);

$rawLocation = (string)old('location', $activity->location ?? '');
if (str_starts_with($rawLocation, 'http://') || str_starts_with($rawLocation, 'https://')) {
    $rawLocation = '';
}

$entryFormConfig = [
    'isEdit' => (bool)$isEdit,
    'activityToken' => (string)($activity->token ?? ''),
    'projectId' => (string)($activity->project_id ?? $preselectedProject->id ?? ''),
    'activityTitle' => (string)old('activity_title', $activity->activity_title ?? ''),
    'activityDate' => (string)old('activity_date', (isset($activity) && $activity && $activity->activity_date ? $activity->activity_date->toDateString() : now()->toDateString())),
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
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        Activity Overview &amp; Scoping
                    </h2>
                    <span class="text-[11px] font-semibold text-slate-500">Step 1: Activity Context</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Project Selector -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Assigned Project <span class="text-rose-500">*</span>
                        </label>
                        <select name="project_id" id="activity_project_id" required class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ (old('project_id', $activity->project_id ?? $preselectedProject->id ?? '') == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->code ?: 'PIFZ' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Activity Title -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Activity Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="activity_title" id="activity_title_input" value="{{ old('activity_title', $activity->activity_title ?? '') }}" placeholder="e.g. Weekly Health Match &amp; Coach Clinic" required class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                    </div>

                    <!-- Activity Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Activity Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="activity_date" id="activity_date_input" value="{{ old('activity_date', (isset($activity) && $activity && $activity->activity_date ? $activity->activity_date->toDateString() : now()->toDateString())) }}" required class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
                    </div>

                    <!-- Venue / Location -->
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Venue / Community Pitch / Location
                        </label>
                        <input type="text" name="location" id="activity_location_input" value="{{ $rawLocation }}" placeholder="e.g. Maramba Community Grounds, Livingstone" autocomplete="off" data-lpignore="true" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 p-2.5 border">
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

                    <!-- 3 Dynamic Sub-Focus Bullet Builders -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Key Milestones -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        <span>1. Key Milestones</span>
                                    </div>
                                    <span id="badge-count-1-milestones" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Major operational, structural, or program activities completed this cycle.</p>

                                <div id="bullet-container-1-milestones" class="space-y-2">
                                    @foreach($initialPillars[1]['milestones'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_1_achievements[milestones][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(1, 'milestones', 'e.g. 5 Coach Clinics conducted across 3 zones', 'blue')" class="w-full py-2 bg-white hover:bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Milestone Point</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Impact Evidence -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        <span>2. Impact Evidence</span>
                                    </div>
                                    <span id="badge-count-1-impact" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Tangible changes, participant numbers, percentages (e.g. 98% passed), or survey data.</p>

                                <div id="bullet-container-1-impact" class="space-y-2">
                                    @foreach($initialPillars[1]['impact'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_1_achievements[impact][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(1, 'impact', 'e.g. 94% retention rate achieved among girls cohort', 'blue')" class="w-full py-2 bg-white hover:bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Impact Evidence</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Success Stories -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        <span>3. Success Stories</span>
                                    </div>
                                    <span id="badge-count-1-stories" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Individual or community transformation highlights, quotes, and peer role model wins.</p>

                                <div id="bullet-container-1-stories" class="space-y-2">
                                    @foreach($initialPillars[1]['stories'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_1_achievements[stories][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(1, 'stories', 'e.g. Chanda secured tertiary scholarship after leadership training', 'blue')" class="w-full py-2 bg-white hover:bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Success Story</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dedicated Detailed Qualitative Narrative for Pillar 1 -->
                    <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-blue-950 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Detailed Narrative &amp; Comprehensive Qualitative Summary (Pillar 1 Master Report)</span>
                            </label>
                            <span class="text-[10px] uppercase font-bold text-blue-700 bg-white border border-blue-200 px-2 py-0.5 rounded-md">Executive Synthesis</span>
                        </div>
                        <p class="text-[11px] text-slate-600">Provide full qualitative context, participant quotes, operational adaptations, and donor-ready reflections for achievements...</p>
                        <textarea name="pillar_1_narrative" id="narrative-pillar-1" rows="4" placeholder="Synthesize overarching narrative, stakeholder observations, and longitudinal context..." class="w-full text-xs font-medium rounded-xl border border-slate-300 bg-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 leading-relaxed">{{ $initialPillars[1]['narrative'] }}</textarea>
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

                    <!-- 3 Dynamic Sub-Focus Bullet Builders -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Operational Challenges -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                        <span>1. Operational Challenges</span>
                                    </div>
                                    <span id="badge-count-2-operational" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Field execution bottlenecks, transport, scheduling, or attendance barriers.</p>

                                <div id="bullet-container-2-operational" class="space-y-2">
                                    @foreach($initialPillars[2]['operational'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_2_challenges[operational][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(2, 'operational', 'e.g. Heavy seasonal rains delayed week 4 fixtures', 'amber')" class="w-full py-2 bg-white hover:bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Operational Point</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Resource Gaps -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                        <span>2. Resource Gaps</span>
                                    </div>
                                    <span id="badge-count-2-resources" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Deficits in kit, sports equipment, learning materials, or venue accessibility.</p>

                                <div id="bullet-container-2-resources" class="space-y-2">
                                    @foreach($initialPillars[2]['resources'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_2_challenges[resources][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(2, 'resources', 'e.g. Additional size 4 footballs and bibs required for junior hub', 'amber')" class="w-full py-2 bg-white hover:bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Resource Gap</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Risk Monitoring -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                        <span>3. Risk Monitoring</span>
                                    </div>
                                    <span id="badge-count-2-risks" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Safeguarding, child protection, health risks, or external dependencies with mitigation.</p>

                                <div id="bullet-container-2-risks" class="space-y-2">
                                    @foreach($initialPillars[2]['risks'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_2_challenges[risks][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(2, 'risks', 'e.g. Safeguarding refreshers completed for all volunteer referees', 'amber')" class="w-full py-2 bg-white hover:bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Risk Monitoring Point</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dedicated Detailed Qualitative Narrative for Pillar 2 -->
                    <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-amber-950 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Detailed Narrative &amp; Risk Mitigation Strategy (Pillar 2 Master Report)</span>
                            </label>
                            <span class="text-[10px] uppercase font-bold text-amber-700 bg-white border border-amber-200 px-2 py-0.5 rounded-md">Risk Roadmap</span>
                        </div>
                        <p class="text-[11px] text-slate-600">Provide full qualitative reflections on mitigation actions taken, donor flags, and adaptive operational planning...</p>
                        <textarea name="pillar_2_narrative" id="narrative-pillar-2" rows="4" placeholder="Detail operational hurdles, root-cause analyses, and mitigation strategies implemented..." class="w-full text-xs font-medium rounded-xl border border-slate-300 bg-white p-3 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 leading-relaxed">{{ $initialPillars[2]['narrative'] }}</textarea>
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

                    <!-- 3 Dynamic Sub-Focus Bullet Builders -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Lessons Learned -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                        <span>1. Lessons Learned</span>
                                    </div>
                                    <span id="badge-count-3-lessons" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Key takeaways from this activity that will shape future delivery.</p>

                                <div id="bullet-container-3-lessons" class="space-y-2">
                                    @foreach($initialPillars[3]['lessons'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_3_learning[lessons][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(3, 'lessons', 'e.g. Afternoon sessions increased girl attendance by 40%', 'purple')" class="w-full py-2 bg-white hover:bg-purple-50 text-purple-700 border border-purple-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Lesson Learned</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Community Feedback -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                        <span>2. Community Feedback</span>
                                    </div>
                                    <span id="badge-count-3-feedback" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Direct feedback received from youth participants, parents, and community leaders.</p>

                                <div id="bullet-container-3-feedback" class="space-y-2">
                                    @foreach($initialPillars[3]['feedback'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_3_learning[feedback][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(3, 'feedback', 'e.g. Parents requested additional financial literacy modules', 'purple')" class="w-full py-2 bg-white hover:bg-purple-50 text-purple-700 border border-purple-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Feedback Point</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Innovation -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                        <span>3. Innovation</span>
                                    </div>
                                    <span id="badge-count-3-innovation" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Piloted novel tools, digital apps, peer-led workshops, or creative models.</p>

                                <div id="bullet-container-3-innovation" class="space-y-2">
                                    @foreach($initialPillars[3]['innovation'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_3_learning[innovation][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(3, 'innovation', 'e.g. Piloted SMS attendance tracker with youth peer captains', 'purple')" class="w-full py-2 bg-white hover:bg-purple-50 text-purple-700 border border-purple-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Innovation Point</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dedicated Detailed Qualitative Narrative for Pillar 3 -->
                    <div class="bg-purple-50/50 border border-purple-100 rounded-2xl p-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-purple-950 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <span>Detailed Narrative &amp; Adaptive Learning Summary (Pillar 3 Master Report)</span>
                            </label>
                            <span class="text-[10px] uppercase font-bold text-purple-700 bg-white border border-purple-200 px-2 py-0.5 rounded-md">Adaptive Insights</span>
                        </div>
                        <p class="text-[11px] text-slate-600">Provide full qualitative analysis on how participant feedback and test innovations will refine subsequent workplans...</p>
                        <textarea name="pillar_3_narrative" id="narrative-pillar-3" rows="4" placeholder="Detail qualitative reflections on curriculum adaptations and stakeholder dialogues..." class="w-full text-xs font-medium rounded-xl border border-slate-300 bg-white p-3 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 leading-relaxed">{{ $initialPillars[3]['narrative'] }}</textarea>
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

                    <!-- 3 Dynamic Sub-Focus Bullet Builders -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Performance -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                        <span>1. Performance</span>
                                    </div>
                                    <span id="badge-count-4-performance" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Progress against quarterly workplan targets and KPIs.</p>

                                <div id="bullet-container-4-performance" class="space-y-2">
                                    @foreach($initialPillars[4]['performance'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_4_monitoring[performance][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(4, 'performance', 'e.g. Achieved 96% of quarterly outreach target (480/500 youth)', 'indigo')" class="w-full py-2 bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Performance Metric</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Data Quality -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                        <span>2. Data Quality</span>
                                    </div>
                                    <span id="badge-count-4-data_quality" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Verification checks, attendance audit logs, and data integrity reconciliations.</p>

                                <div id="bullet-container-4-data_quality" class="space-y-2">
                                    @foreach($initialPillars[4]['data_quality'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_4_monitoring[data_quality][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(4, 'data_quality', 'e.g. 100% physical registers digitized and audited against mobile app', 'indigo')" class="w-full py-2 bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Data Quality Check</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Evaluation Plans -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                        <span>3. Evaluation Plans</span>
                                    </div>
                                    <span id="badge-count-4-evaluation" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Midterm assessments, focus groups, survey cycles, or longitudinal reviews.</p>

                                <div id="bullet-container-4-evaluation" class="space-y-2">
                                    @foreach($initialPillars[4]['evaluation'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_4_monitoring[evaluation][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(4, 'evaluation', 'e.g. Endline survey scheduled for June 28 with 50 sample households', 'indigo')" class="w-full py-2 bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Evaluation Plan</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dedicated Detailed Qualitative Narrative for Pillar 4 -->
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-indigo-950 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span>Detailed Narrative &amp; M&amp;E Analytical Summary (Pillar 4 Master Report)</span>
                            </label>
                            <span class="text-[10px] uppercase font-bold text-indigo-700 bg-white border border-indigo-200 px-2 py-0.5 rounded-md">Evaluation Report</span>
                        </div>
                        <p class="text-[11px] text-slate-600">Provide full qualitative reflections on data trends, anomalies investigated, and evaluation methodology...</p>
                        <textarea name="pillar_4_narrative" id="narrative-pillar-4" rows="4" placeholder="Detail M&amp;E methodological notes, outlier assessments, and longitudinal trend analyses..." class="w-full text-xs font-medium rounded-xl border border-slate-300 bg-white p-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 leading-relaxed">{{ $initialPillars[4]['narrative'] }}</textarea>
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

                    <!-- 3 Dynamic Sub-Focus Bullet Builders -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Sub-Section 1: Project Collaboration -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                        <span>1. Project Collaboration</span>
                                    </div>
                                    <span id="badge-count-5-project_collab" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Resource sharing and joint activities conducted with adjacent PIFZ projects.</p>

                                <div id="bullet-container-5-project_collab" class="space-y-2">
                                    @foreach($initialPillars[5]['project_collab'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_5_collaboration[project_collab][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(5, 'project_collab', 'e.g. Shared pitch resources and referees with Disability Sports project', 'emerald')" class="w-full py-2 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Project Collab Point</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 2: Partnerships -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                        <span>2. Partnerships</span>
                                    </div>
                                    <span id="badge-count-5-partnerships" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Civic, ministry, local school, health center, and community leadership agreements.</p>

                                <div id="bullet-container-5-partnerships" class="space-y-2">
                                    @foreach($initialPillars[5]['partnerships'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_5_collaboration[partnerships][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(5, 'partnerships', 'e.g. Formalized MOU with Maramba Clinic for monthly health screenings', 'emerald')" class="w-full py-2 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Partnership Point</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sub-Section 3: Cross Learning -->
                        <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                        <span>3. Cross Learning</span>
                                    </div>
                                    <span id="badge-count-5-cross_learning" class="text-[10px] font-bold text-slate-400">0 points</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug mb-3">Knowledge shared across project officers, peer coaches, and regional team summits.</p>

                                <div id="bullet-container-5-cross_learning" class="space-y-2">
                                    @foreach($initialPillars[5]['cross_learning'] as $idx => $val)
                                    <div class="flex items-start gap-1.5 group">
                                        <span class="text-slate-400 font-bold text-xs mt-2">•</span>
                                        <input type="text"
                                               name="pillar_5_collaboration[cross_learning][]"
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
                            </div>

                            <div class="pt-2">
                                <button type="button" onclick="addBulletRow(5, 'cross_learning', 'e.g. Shared coaching methodology toolkit at provincial quarterly summit', 'emerald')" class="w-full py-2 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span>+ Add Cross Learning Point</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dedicated Detailed Qualitative Narrative for Pillar 5 -->
                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-emerald-950 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>Detailed Narrative &amp; Partnership Synthesis (Pillar 5 Master Report)</span>
                            </label>
                            <span class="text-[10px] uppercase font-bold text-emerald-700 bg-white border border-emerald-200 px-2 py-0.5 rounded-md">Partner Synergy</span>
                        </div>
                        <p class="text-[11px] text-slate-600">Provide full qualitative reflections on stakeholder synergy, community buy-in, and joint scale opportunities...</p>
                        <textarea name="pillar_5_narrative" id="narrative-pillar-5" rows="4" placeholder="Detail qualitative notes on external stakeholder synergies and community ownership..." class="w-full text-xs font-medium rounded-xl border border-slate-300 bg-white p-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 leading-relaxed">{{ $initialPillars[5]['narrative'] }}</textarea>
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
               name="pillar_${pillarNum}_${pillarFieldNames[pillarNum]}[${subKey}][]"
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
    document.getElementById('flat_achievements_narrative').value = document.getElementById('narrative-pillar-1').value;

    // Pillar 2
    document.getElementById('flat_challenges_operational').value = getBulletLines(2, 'operational');
    document.getElementById('flat_challenges_resources').value = getBulletLines(2, 'resources');
    document.getElementById('flat_challenges_risks').value = getBulletLines(2, 'risks');
    document.getElementById('flat_challenges_narrative').value = document.getElementById('narrative-pillar-2').value;

    // Pillar 3
    document.getElementById('flat_learning_lessons').value = getBulletLines(3, 'lessons');
    document.getElementById('flat_learning_feedback').value = getBulletLines(3, 'feedback');
    document.getElementById('flat_learning_innovation').value = getBulletLines(3, 'innovation');
    document.getElementById('flat_learning_narrative').value = document.getElementById('narrative-pillar-3').value;

    // Pillar 4
    document.getElementById('flat_mne_performance').value = getBulletLines(4, 'performance');
    document.getElementById('flat_mne_data_quality').value = getBulletLines(4, 'data_quality');
    document.getElementById('flat_mne_evaluation_plans').value = getBulletLines(4, 'evaluation');
    document.getElementById('flat_mne_narrative').value = document.getElementById('narrative-pillar-4').value;

    // Pillar 5
    document.getElementById('flat_collab_projects').value = getBulletLines(5, 'project_collab');
    document.getElementById('flat_collab_partnerships').value = getBulletLines(5, 'partnerships');
    document.getElementById('flat_collab_cross_learning').value = getBulletLines(5, 'cross_learning');
    document.getElementById('flat_collab_narrative').value = document.getElementById('narrative-pillar-5').value;
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
});
</script>
@endsection
