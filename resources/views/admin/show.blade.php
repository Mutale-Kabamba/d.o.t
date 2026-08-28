@extends('layouts.app')

@section('title', 'Submission Details - Token: ' . $submission->token)

@section('content')
<div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Top Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.submissions.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Dashboard</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('worksheet.download_pdf', $submission->token) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Download Official PDF</span>
            </a>

            <form action="{{ route('admin.submissions.destroy', $submission->token) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this submission?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-transparent hover:border-rose-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Header Banner -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-2">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                100% Anonymous Participant Submission
            </span>
            <span class="text-xs text-slate-400 font-medium">Submitted on {{ $submission->created_at->format('M d, Y \a\t H:i:s') }}</span>
        </div>

        <div class="pt-1">
            <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Token Receipt:</span>
            <h1 class="text-sm sm:text-base font-mono font-bold text-slate-900 break-all select-all">{{ $submission->token }}</h1>
        </div>
    </div>

    <!-- Section 1: Journey Mapping -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-2">
            <span class="text-[10px] font-extrabold uppercase text-brand-600">Section 1 of 8</span>
            <h2 class="text-sm sm:text-base font-bold text-slate-900 font-display">1. Journey Mapping</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                <span class="text-[11px] font-bold text-emerald-800 uppercase block mb-1">Recruitment Highs:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s1_recruitment_high ?: '--' }}</p>
            </div>
            <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100">
                <span class="text-[11px] font-bold text-rose-800 uppercase block mb-1">Recruitment Challenges:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s1_recruitment_challenges ?: '--' }}</p>
            </div>
            <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                <span class="text-[11px] font-bold text-emerald-800 uppercase block mb-1">Graduation Highs:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s1_grad_high ?: '--' }}</p>
            </div>
            <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100">
                <span class="text-[11px] font-bold text-rose-800 uppercase block mb-1">Graduation Challenges:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s1_grad_challenges ?: '--' }}</p>
            </div>
            <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                <span class="text-[11px] font-bold text-emerald-800 uppercase block mb-1">BDS Linkage Highs:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s1_bds_high ?: '--' }}</p>
            </div>
            <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100">
                <span class="text-[11px] font-bold text-rose-800 uppercase block mb-1">BDS Linkage Challenges:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s1_bds_challenges ?: '--' }}</p>
            </div>
        </div>
    </div>

    <!-- Section 2: Operations & Placements -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-2">
            <span class="text-[10px] font-extrabold uppercase text-brand-600">Section 2 of 8</span>
            <h2 class="text-sm sm:text-base font-bold text-slate-900 font-display">2. Operations, Communication &amp; Host Placements</h2>
        </div>

        <div class="space-y-3">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Operational &amp; Communication Hurdles:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s2_ops_comm ?: '--' }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Host Org Criteria:</span>
                    <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s2_host_criteria ?: '--' }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Standardized Reporting Fixes:</span>
                    <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s2_reporting_fixes ?: '--' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: PACRA & BDS -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-2">
            <span class="text-[10px] font-extrabold uppercase text-brand-600">Section 3 of 8</span>
            <h2 class="text-sm sm:text-base font-bold text-slate-900 font-display">3. PACRA 50% Goal &amp; BDS Linkages</h2>
        </div>

        <div class="space-y-3">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Strategy to Hit 50% PACRA:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s3_pacra_strategy ?: '--' }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">BDS Linkages &amp; Market Access:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s3_market_access ?: '--' }}</p>
            </div>
        </div>
    </div>

    <!-- Section 4: Community & Safeguarding -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-2">
            <span class="text-[10px] font-extrabold uppercase text-brand-600">Section 4 of 8</span>
            <h2 class="text-sm sm:text-base font-bold text-slate-900 font-display">4. Community Engagement, Safeguarding &amp; Inclusion</h2>
        </div>

        <div class="space-y-3">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Household Buy-In &amp; Dropout Prevention:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s4_household_buyin ?: '--' }}</p>
            </div>
            <div class="p-3 bg-sky-50/60 rounded-xl border border-sky-200">
                <span class="text-[11px] font-bold text-brand-800 uppercase block mb-1">Safeguarding Accessibility Rating:</span>
                <span class="font-bold text-xs text-brand-900">{{ $submission->s4_safeguarding_accessible ?: '--' }}</span>
                <p class="text-xs text-slate-700 mt-2 whitespace-pre-wrap">{{ $submission->s4_safeguarding_details ?: '' }}</p>
            </div>
        </div>
    </div>

    <!-- Section 5: Recruitment & Finance -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-2">
            <span class="text-[10px] font-extrabold uppercase text-brand-600">Section 5 of 8</span>
            <h2 class="text-sm sm:text-base font-bold text-slate-900 font-display">5. Recruitment Walk-Throughs &amp; Finance</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Market Walk-Through Strategy:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s5_recruitment_walkthroughs ?: '--' }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Financial Processes &amp; Stipends:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s5_finance_stipends ?: '--' }}</p>
            </div>
        </div>
    </div>

    <!-- Section 6: YL Transition -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-2">
            <span class="text-[10px] font-extrabold uppercase text-brand-600">Section 6 of 8</span>
            <h2 class="text-sm sm:text-base font-bold text-slate-900 font-display">6. Youth Leader Employment Linkages &amp; Transition</h2>
        </div>

        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
            <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Key Transition Barriers &amp; Pathways:</span>
            <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s6_yl_transition ?: '--' }}</p>
        </div>
    </div>

    <!-- Section 7: Mindset Impact -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-2">
            <span class="text-[10px] font-extrabold uppercase text-brand-600">Section 7 of 8</span>
            <h2 class="text-sm sm:text-base font-bold text-slate-900 font-display">7. Personal &amp; Mindset Impact</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Skills Gained:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s7_skills_gained ?: '--' }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Mindset Shift:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s7_mindset_shift ?: '--' }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Community Action:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s7_action_taken ?: '--' }}</p>
            </div>
        </div>
    </div>

    <!-- Section 8: Summary Recommendations -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-2">
            <span class="text-[10px] font-extrabold uppercase text-brand-600">Section 8 of 8</span>
            <h2 class="text-sm sm:text-base font-bold text-slate-900 font-display">8. Summary Recommendations &amp; Final Message</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                <span class="text-[11px] font-bold text-emerald-800 uppercase block mb-1">Top Things That Worked:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s8_top_worked ?: '--' }}</p>
            </div>
            <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100">
                <span class="text-[11px] font-bold text-rose-800 uppercase block mb-1">Top Barriers:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s8_top_barriers ?: '--' }}</p>
            </div>
        </div>

        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
            <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Change ONE Thing:</span>
            <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s8_change_one_thing ?: '--' }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="p-3 bg-sky-50 rounded-xl border border-sky-100">
                <span class="text-[11px] font-bold text-brand-800 uppercase block mb-1">One Word Feeling:</span>
                <p class="text-xs font-bold text-brand-700">{{ $submission->s8_one_word ?: '--' }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-[11px] font-bold text-slate-800 uppercase block mb-1">Final Message for YSO / DOT:</span>
                <p class="text-xs text-slate-700 whitespace-pre-wrap">{{ $submission->s8_final_message ?: '--' }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
