@extends('layouts.app')

@section('title', $activity->activity_title . ' - Activity Log Details')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 antialiased font-sans py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Top Navigation -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <a href="{{ route('programmes.hub') }}" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-slate-900">{{ $activity->activity_title }}</h1>
                        <span class="px-2.5 py-0.5 text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 rounded-full">
                            {{ $activity->project->name }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        Logged on {{ \Carbon\Carbon::parse($activity->activity_date)->format('F j, Y') }} • {{ $activity->location ?? 'Livingstone, Zambia' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('programmes.activities.edit', $activity->token) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition shadow-xs">
                    <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit Activity</span>
                </a>

                <form action="{{ route('programmes.activities.destroy', $activity->token) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this activity entry?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition shadow-xs cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Delete</span>
                    </button>
                </form>
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
                    $hasAnySub = false;
                    foreach($theme['items'] as $itemKey => $item) {
                        if (!empty($activity->{$itemKey})) { $hasAnySub = true; break; }
                    }
                @endphp

                @if($hasAnySub)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($theme['items'] as $itemKey => $item)
                    @php
                        $subVal = $activity->{$itemKey} ?? '';
                        $subPoints = !empty($subVal) ? \App\Models\ActivityEntry::extractBulletPoints($subVal) : [];
                    @endphp
                    <div class="bg-slate-50/80 border border-slate-200 rounded-xl p-4 space-y-2">
                        <div class="flex items-center gap-1.5 font-bold text-slate-900 text-xs">
                            <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                            <span>{{ $item['title'] }}</span>
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
                            @elseif(!empty($subVal))
                                <p class="text-xs text-slate-800 font-medium whitespace-pre-wrap">{{ $subVal }}</p>
                            @else
                                <p class="text-xs text-slate-400 italic">No entry recorded.</p>
                            @endif
                        </div>
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
