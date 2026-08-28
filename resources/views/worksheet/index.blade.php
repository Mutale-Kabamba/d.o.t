@extends('layouts.app')

@section('title', 'Cohort Review & Planning: Anonymous Participant Reflection & Action Worksheet')

@section('content')
  <!-- Main Top Header -->
  <header class="sticky top-0 z-40 glass-nav border-b border-slate-200 shadow-sm transition-all">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-14 sm:h-16 gap-2">
        
        <!-- Logo & Title -->
        <div class="flex items-center gap-2.5 min-w-0">
          <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-brand-700 to-sky-500 flex items-center justify-center text-white shadow-md shadow-brand-500/20 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <div class="min-w-0">
            <div class="flex items-center gap-1.5">
              <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200 shrink-0">100% Anonymous</span>
              <span id="save-status-mobile" class="sm:hidden inline-flex items-center text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span> Auto-Saved
              </span>
            </div>
            <h1 class="text-xs sm:text-base font-bold text-slate-900 leading-tight truncate font-display">Reflection & Action Worksheet</h1>
          </div>
        </div>

        <!-- Top Actions -->
        <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
          <!-- Desktop Auto-Save Badge -->
          <div id="save-status" class="hidden md:flex items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 font-medium">
            <svg class="w-3.5 h-3.5 text-emerald-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <span id="save-status-text">Draft auto-saved</span>
          </div>

          <!-- Admin Dashboard Button -->
          <a href="{{ route('admin.submissions.index') }}" title="Admin Dashboard" class="p-2 sm:p-2.5 text-slate-400 hover:text-brand-700 hover:bg-brand-50 rounded-xl transition-colors border border-transparent hover:border-brand-100 active:scale-95 cursor-pointer">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </a>

          <!-- Reset Button -->
          <button type="button" data-action="reset" onclick="openResetModal()" title="Reset Worksheet" class="p-2 sm:p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors border border-transparent hover:border-rose-100 active:scale-95 cursor-pointer">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>

          <!-- Download PDF Button -->
          <button type="button" data-action="export-pdf" onclick="exportToPdf()" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-brand-700 text-white font-semibold text-xs sm:text-sm px-3 sm:px-4 py-2 rounded-xl transition shadow-sm active:scale-95 cursor-pointer">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="hidden xs:inline sm:inline">PDF</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Progress bar -->
    <div class="w-full bg-slate-100 h-1 sm:h-1.5 overflow-hidden">
      <div id="overall-progress-bar" class="bg-gradient-to-r from-sky-500 via-brand-600 to-indigo-600 h-full transition-all duration-300 w-0"></div>
    </div>

    <!-- Mobile Horizontal Stepper Carousel (Visible below lg) -->
    <div class="lg:hidden bg-slate-50/95 border-b border-slate-200 px-3 py-2">
      <div class="flex items-center justify-between gap-2 mb-1.5">
        <span id="mobile-step-title" class="text-[11px] font-bold text-slate-700 truncate font-display">Introduction &amp; Overview</span>
        <div class="flex items-center gap-1.5 shrink-0">
          <span id="mobile-progress-badge" class="text-[10px] font-extrabold bg-brand-50 text-brand-700 px-2 py-0.5 rounded-md border border-brand-200">0%</span>
          <button type="button" data-action="toggle-drawer" onclick="toggleMobileStepDrawer()" class="text-[11px] text-brand-600 font-bold px-2 py-0.5 rounded hover:bg-brand-50 cursor-pointer">View All</button>
        </div>
      </div>
      <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5" id="mobile-chips-container">
        <!-- Generated via JavaScript -->
      </div>
    </div>
  </header>

  <!-- Main Container -->
  <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6 flex-1 w-full grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    <!-- Sidebar Stepper (Desktop) -->
    <aside class="hidden lg:block lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm sticky top-24">
      <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
        <div>
          <h2 class="text-sm font-bold text-slate-900 font-display">Worksheet Outline</h2>
          <p class="text-xs text-slate-500 mt-0.5">Anonymous &amp; Auto-saved</p>
        </div>
        <span id="progress-percent" class="text-xs font-bold bg-brand-50 text-brand-700 px-2.5 py-1 rounded-full border border-brand-200">0%</span>
      </div>

      <!-- Desktop Steps List -->
      <nav class="space-y-1 max-h-[calc(100vh-230px)] overflow-y-auto pr-1" id="stepper-list">
        <!-- Injected via JavaScript -->
      </nav>

      <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <span>No names or identifiers are collected.</span>
      </div>
    </aside>

    <!-- Main Step Form Content Area -->
    <main class="lg:col-span-8 w-full">
      <form id="worksheet-form" onsubmit="event.preventDefault();" class="space-y-6">
        @csrf

        <!-- STEP 0: Introduction & Overview Page -->
        <div id="step-0" class="step-content bg-white rounded-2xl p-5 sm:p-8 border border-slate-200 shadow-sm space-y-6">
          <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-900 via-slate-900 to-sky-950 p-6 sm:p-8 text-white shadow-lg">
            <div class="relative z-10 space-y-3">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Confidential &amp; 100% Anonymous Feedback</span>
              </div>
              <h2 class="text-xl sm:text-3xl font-extrabold font-display leading-tight">
                Cohort Review &amp; Planning: Reflection &amp; Action Worksheet
              </h2>
              <p class="text-xs sm:text-sm text-sky-100/90 leading-relaxed max-w-2xl">
                Welcome to your post-cohort review! This form has been created to collect genuine, unvarnished experiences, operational lessons, and high-impact recommendations to elevate future cohorts.
              </p>
            </div>
            <!-- Background Decorative Glow -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-sky-500/10 blur-3xl pointer-events-none"></div>
          </div>

          <!-- Key Guidelines Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1.5">
              <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                </svg>
              </div>
              <h3 class="text-xs font-bold text-slate-900">Total Anonymity</h3>
              <p class="text-[11px] text-slate-500 leading-normal">No names, emails, or personal identifiers are collected. Share freely and truthfully.</p>
            </div>

            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1.5">
              <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
              </div>
              <h3 class="text-xs font-bold text-slate-900">Live Auto-Save</h3>
              <p class="text-[11px] text-slate-500 leading-normal">If you accidentally close or refresh your browser, your draft is restored automatically.</p>
            </div>

            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1.5">
              <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <h3 class="text-xs font-bold text-slate-900">Executive PDF Export</h3>
              <p class="text-[11px] text-slate-500 leading-normal">Download a clean, structured multi-page PDF formatted cleanly without cut-offs.</p>
            </div>
          </div>

          <!-- Structure Breakdown Overview -->
          <div class="bg-brand-50/60 rounded-xl p-4 sm:p-5 border border-brand-100 space-y-3">
            <h3 class="text-xs font-bold text-brand-900 uppercase tracking-wide">Worksheet Sections:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-brand-950">
              <div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-brand-200 text-brand-800 text-[10px] font-bold flex items-center justify-center">1</span> Journey Mapping (Recruitment, Grad, BDS)</div>
              <div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-brand-200 text-brand-800 text-[10px] font-bold flex items-center justify-center">2</span> Operations, Comms &amp; Host Placements</div>
              <div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-brand-200 text-brand-800 text-[10px] font-bold flex items-center justify-center">3</span> Tracking, PACRA 50% &amp; Markets</div>
              <div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-brand-200 text-brand-800 text-[10px] font-bold flex items-center justify-center">4</span> Family Engagement, Safeguarding &amp; PWD</div>
              <div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-brand-200 text-brand-800 text-[10px] font-bold flex items-center justify-center">5</span> Market Walks &amp; Stipend Disbursements</div>
              <div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-brand-200 text-brand-800 text-[10px] font-bold flex items-center justify-center">6</span> Youth Leader Transition Linkages</div>
              <div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-brand-200 text-brand-800 text-[10px] font-bold flex items-center justify-center">7</span> Personal Growth &amp; Mindset Shift</div>
              <div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-brand-200 text-brand-800 text-[10px] font-bold flex items-center justify-center">8</span> Summary Recommendations &amp; Final Message</div>
            </div>
          </div>
        </div>

        <!-- STEP 1: Journey Mapping -->
        <div id="step-1" class="step-content hidden bg-white rounded-2xl p-4 sm:p-7 border border-slate-200 shadow-sm space-y-5">
          <div class="border-b border-slate-100 pb-3 sm:pb-4">
            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Section 1 of 8</span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 font-display mt-0.5">Participant &amp; Youth Leader Journey Mapping</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Reflect on the complete journey from initial recruitment through to graduation and post-training linkages.</p>
          </div>

          <!-- 1.A -->
          <div class="space-y-2.5 bg-slate-50/70 p-3.5 sm:p-5 rounded-2xl border border-slate-200">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-brand-600 text-white flex items-center justify-center font-bold text-xs shrink-0">A</span>
              <h3 class="text-xs sm:text-base font-bold text-slate-900">Recruitment &amp; Application / Mobilization Phase</h3>
            </div>
            <p class="text-[11px] sm:text-xs text-slate-500 italic pl-8">Application experience, community mobilization support, reaching target youth</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
              <div>
                <label class="block text-[11px] sm:text-xs font-bold text-emerald-800 uppercase mb-1" for="s1_recruitment_high">High Points &amp; Successes</label>
                <textarea id="s1_recruitment_high" name="s1_recruitment_high" rows="3" placeholder="What worked effectively during mobilization and applications?" class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
              </div>
              <div>
                <label class="block text-[11px] sm:text-xs font-bold text-rose-800 uppercase mb-1" for="s1_recruitment_challenges">Challenges &amp; Bottlenecks</label>
                <textarea id="s1_recruitment_challenges" name="s1_recruitment_challenges" rows="3" placeholder="What hurdles were faced in reaching or selecting target youth?" class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
              </div>
            </div>
          </div>

          <!-- 1.B -->
          <div class="space-y-2.5 bg-slate-50/70 p-3.5 sm:p-5 rounded-2xl border border-slate-200">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-brand-600 text-white flex items-center justify-center font-bold text-xs shrink-0">B</span>
              <h3 class="text-xs sm:text-base font-bold text-slate-900">Graduation &amp; Milestone Completion</h3>
            </div>
            <p class="text-[11px] sm:text-xs text-slate-500 italic pl-8">Graduation logistics, milestone celebrations, certification, participant retention</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
              <div>
                <label class="block text-[11px] sm:text-xs font-bold text-emerald-800 uppercase mb-1" for="s1_grad_high">High Points &amp; Successes</label>
                <textarea id="s1_grad_high" name="s1_grad_high" rows="3" placeholder="What went smoothly during milestone delivery and graduation?" class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
              </div>
              <div>
                <label class="block text-[11px] sm:text-xs font-bold text-rose-800 uppercase mb-1" for="s1_grad_challenges">Challenges &amp; Bottlenecks</label>
                <textarea id="s1_grad_challenges" name="s1_grad_challenges" rows="3" placeholder="What retention or logistical issues were experienced?" class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
              </div>
            </div>
          </div>

          <!-- 1.C -->
          <div class="space-y-2.5 bg-slate-50/70 p-3.5 sm:p-5 rounded-2xl border border-slate-200">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-brand-600 text-white flex items-center justify-center font-bold text-xs shrink-0">C</span>
              <h3 class="text-xs sm:text-base font-bold text-slate-900">End of Journey: BDS Support &amp; Market Linkages</h3>
            </div>
            <p class="text-[11px] sm:text-xs text-slate-500 italic pl-8">Connecting to mentors, buyers, digital platforms, business development services</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
              <div>
                <label class="block text-[11px] sm:text-xs font-bold text-emerald-800 uppercase mb-1" for="s1_bds_high">High Points &amp; Successes</label>
                <textarea id="s1_bds_high" name="s1_bds_high" rows="3" placeholder="Successful linkages, mentorship connections, or market access gained..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
              </div>
              <div>
                <label class="block text-[11px] sm:text-xs font-bold text-rose-800 uppercase mb-1" for="s1_bds_challenges">Challenges &amp; Bottlenecks</label>
                <textarea id="s1_bds_challenges" name="s1_bds_challenges" rows="3" placeholder="Gaps in BDS support, mentor responsiveness, or buyer connections..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 2: Operations & Placements -->
        <div id="step-2" class="step-content hidden bg-white rounded-2xl p-4 sm:p-7 border border-slate-200 shadow-sm space-y-5">
          <div class="border-b border-slate-100 pb-3 sm:pb-4">
            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Section 2 of 8</span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 font-display mt-0.5">Operational Hurdles, Communication &amp; Placements</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Focus: Internal coordination, partner reporting, venue logistics, and assigning YLs to host organizations.</p>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s2_ops_comm">A. Operational &amp; Communication Hurdles</label>
              <p class="text-xs text-slate-500 mb-1.5">List key operational bottlenecks and internal/external communication breakdowns encountered.</p>
              <textarea id="s2_ops_comm" name="s2_ops_comm" rows="3" placeholder="Describe communication gaps between YSOs, YLs, and partners..." class="w-full p-3.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
              <div>
                <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s2_host_criteria">Host Org Criteria &amp; Placements</label>
                <p class="text-xs text-slate-500 mb-1.5">Selection criteria for host organizations, venue setups, and scheduling improvements.</p>
                <textarea id="s2_host_criteria" name="s2_host_criteria" rows="3" placeholder="How should we improve host organization selection and venue setup?" class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s2_reporting_fixes">Standardized Reporting &amp; Coordination Fixes</label>
                <p class="text-xs text-slate-500 mb-1.5">Actionable fixes for reporting cadence, attendance logging, and coordination.</p>
                <textarea id="s2_reporting_fixes" name="s2_reporting_fixes" rows="3" placeholder="Recommended fixes for reports, attendance tools, check-ins..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 3: PACRA & BDS -->
        <div id="step-3" class="step-content hidden bg-white rounded-2xl p-4 sm:p-7 border border-slate-200 shadow-sm space-y-5">
          <div class="border-b border-slate-100 pb-3 sm:pb-4">
            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Section 3 of 8</span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 font-display mt-0.5">Participant Tracking, PACRA 50% Target &amp; BDS Linkages</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Focus: Achieving the mandate of 50% PACRA registration for graduated YPs, using the performance tracker, and expanding BDS.</p>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s3_pacra_strategy">B. Strategy to Hit the 50% PACRA Goal &amp; Performance Tracker Use</label>
              <p class="text-xs text-slate-500 mb-1.5">Concrete execution steps to monitor and achieve the 50% registration target among graduated youth.</p>
              <textarea id="s3_pacra_strategy" name="s3_pacra_strategy" rows="3" placeholder="What practical steps, on-ground clinics, or fee assistance will ensure youth complete PACRA formalization?" class="w-full p-3.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s3_market_access">C. BDS Linkages &amp; Market Access</label>
              <p class="text-xs text-slate-500 mb-1.5">How can we connect graduated youth to tangible market buyers, finance, and ongoing business support?</p>
              <textarea id="s3_market_access" name="s3_market_access" rows="3" placeholder="Identify specific buyers, microfinance partners, trade fairs, or digital platforms..." class="w-full p-3.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
            </div>
          </div>
        </div>

        <!-- STEP 4: Community & Safeguarding -->
        <div id="step-4" class="step-content hidden bg-white rounded-2xl p-4 sm:p-7 border border-slate-200 shadow-sm space-y-5">
          <div class="border-b border-slate-100 pb-3 sm:pb-4">
            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Section 4 of 8</span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 font-display mt-0.5">Community &amp; Family Engagement, Safeguarding &amp; Inclusion</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Focus: Household buy-in, family sensitization, community entry, safe spaces, and safeguarding protocols.</p>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s4_household_buyin">A. Household Buy-In &amp; Drop-out Prevention</label>
              <p class="text-xs text-slate-500 mb-1.5">Root causes of participant dropouts or family hesitation, and proposed community sensitization structure.</p>
              <textarea id="s4_household_buyin" name="s4_household_buyin" rows="3" placeholder="Why did some youth drop out? How can parents and elders be better engaged early on?" class="w-full p-3.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
            </div>

            <div class="bg-slate-50/80 p-3.5 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
              <div>
                <label class="block text-xs font-bold text-slate-800 uppercase mb-1">B. Safeguarding Channels &amp; Inclusive Spaces</label>
                <p class="text-xs text-slate-600 font-medium">Are reporting channels accessible and clear to all youth?</p>
              </div>
              
              <!-- Touch-Friendly Radio Buttons -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1">
                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-white hover:border-brand-400 cursor-pointer transition select-none">
                  <input type="radio" name="s4_safeguarding_accessible" value="Yes, completely clear" class="w-4 h-4 text-brand-600 focus:ring-brand-500">
                  <span class="text-xs font-medium text-slate-800">Yes, completely clear</span>
                </label>
                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-white hover:border-brand-400 cursor-pointer transition select-none">
                  <input type="radio" name="s4_safeguarding_accessible" value="Partially clear but needs improvement" class="w-4 h-4 text-brand-600 focus:ring-brand-500">
                  <span class="text-xs font-medium text-slate-800">Partially clear / Needs fixes</span>
                </label>
                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-white hover:border-brand-400 cursor-pointer transition select-none">
                  <input type="radio" name="s4_safeguarding_accessible" value="No, not clear to participants" class="w-4 h-4 text-brand-600 focus:ring-brand-500">
                  <span class="text-xs font-medium text-slate-800">No, not clear</span>
                </label>
              </div>

              <div class="pt-2">
                <label class="block text-xs font-bold text-slate-700 mb-1" for="s4_safeguarding_details">Specific Protocols, Incident Reporting &amp; PWD Inclusion Measures:</label>
                <textarea id="s4_safeguarding_details" name="s4_safeguarding_details" rows="3" placeholder="Steps to improve physical accessibility, safe reporting, and confidentiality..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 5: Recruitment & Finance -->
        <div id="step-5" class="step-content hidden bg-white rounded-2xl p-4 sm:p-7 border border-slate-200 shadow-sm space-y-5">
          <div class="border-b border-slate-100 pb-3 sm:pb-4">
            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Section 5 of 8</span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 font-display mt-0.5">Recruitment Strategy, Market Walk-Throughs &amp; Finance</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Focus: Participant onboarding, conducting center market walk-throughs, stipend disbursements, and financial accountability.</p>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s5_recruitment_walkthroughs">A. Recruitment Strategy &amp; Center Market Walk-Throughs</label>
              <p class="text-xs text-slate-500 mb-1.5">Logistics, visibility, timing, and community presence for unit walk-throughs in center markets.</p>
              <textarea id="s5_recruitment_walkthroughs" name="s5_recruitment_walkthroughs" rows="3" placeholder="How can market walks and community activations be made more impactful?" class="w-full p-3.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s5_finance_stipends">B. Financial Processes &amp; Stipend Disbursements</label>
              <p class="text-xs text-slate-500 mb-1.5">Financial bottlenecks from Cohort 1 and solutions for smooth, timely disbursements.</p>
              <textarea id="s5_finance_stipends" name="s5_finance_stipends" rows="3" placeholder="Address delays in stipends, transport reimbursements, mobile money issues..." class="w-full p-3.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
            </div>
          </div>
        </div>

        <!-- STEP 6: YL Transition -->
        <div id="step-6" class="step-content hidden bg-white rounded-2xl p-4 sm:p-7 border border-slate-200 shadow-sm space-y-5">
          <div class="border-b border-slate-100 pb-3 sm:pb-4">
            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Section 6 of 8</span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 font-display mt-0.5">Youth Leader (YL) Employment Linkages &amp; Transition</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Focus: Post-project career transition plans, job placement, internships, mentorship, and enterprise launches.</p>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s6_yl_transition">Key Transition Barriers &amp; Practical Linkage Pathways</label>
            <p class="text-xs text-slate-500 mb-2">What key transition barriers do YLs face upon cohort completion, and what practical linkage pathways should be implemented?</p>
            <textarea id="s6_yl_transition" name="s6_yl_transition" rows="5" placeholder="Detail transition barriers (experience, capital, pipelines) and proposed solutions (internships, alumni network, capital linkages)..." class="w-full p-3.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
          </div>
        </div>

        <!-- STEP 7: Mindset Shift -->
        <div id="step-7" class="step-content hidden bg-white rounded-2xl p-4 sm:p-7 border border-slate-200 shadow-sm space-y-5">
          <div class="border-b border-slate-100 pb-3 sm:pb-4">
            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Section 7 of 8</span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 font-display mt-0.5">What Changed for You? (Personal &amp; Mindset Impact)</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Reflect on your personal growth, capability expansion, and direct actions taken.</p>
          </div>

          <div class="space-y-3.5">
            <div class="bg-slate-50/70 p-3.5 sm:p-4 rounded-xl border border-slate-200">
              <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s7_skills_gained">Skills Gained:</label>
              <p class="text-xs text-slate-500 mb-1.5">Technical, facilitation, digital, or business skills mastered during Cohort 1.</p>
              <textarea id="s7_skills_gained" name="s7_skills_gained" rows="2" placeholder="e.g. Digital marketing tools, facilitation in local language, record keeping..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
            </div>

            <div class="bg-slate-50/70 p-3.5 sm:p-4 rounded-xl border border-slate-200">
              <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s7_mindset_shift">Mindset / Confidence Shift:</label>
              <p class="text-xs text-slate-500 mb-1.5">Changes in personal confidence, problem-solving attitude, leadership identity.</p>
              <textarea id="s7_mindset_shift" name="s7_mindset_shift" rows="2" placeholder="e.g. Gained confidence in public speaking and mentoring younger peers..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
            </div>

            <div class="bg-slate-50/70 p-3.5 sm:p-4 rounded-xl border border-slate-200">
              <label class="block text-xs font-bold text-slate-800 uppercase mb-1" for="s7_action_taken">Action Taken in Community:</label>
              <p class="text-xs text-slate-500 mb-1.5">Concrete projects, youth mobilized, initiatives or enterprises started.</p>
              <textarea id="s7_action_taken" name="s7_action_taken" rows="2" placeholder="e.g. Started a peer savings group, trained 15 youths on digital mobile tools..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition bg-white"></textarea>
            </div>
          </div>
        </div>

        <!-- STEP 8: Recommendations -->
        <div id="step-8" class="step-content hidden bg-white rounded-2xl p-4 sm:p-7 border border-slate-200 shadow-sm space-y-5">
          <div class="border-b border-slate-100 pb-3 sm:pb-4">
            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Section 8 of 8</span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 font-display mt-0.5">Summary Recommendations &amp; Final Reflections</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Synthesize what worked well, major barriers, and your direct recommendations for future cohorts.</p>
          </div>

          <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
              <div>
                <label class="block text-xs font-bold text-emerald-800 uppercase mb-1" for="s8_top_worked">Top Things That Worked Well</label>
                <textarea id="s8_top_worked" name="s8_top_worked" rows="3" placeholder="1. ...&#10;2. ...&#10;3. ..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
              </div>
              <div>
                <label class="block text-xs font-bold text-rose-800 uppercase mb-1" for="s8_top_barriers">Top Challenges / Barriers</label>
                <textarea id="s8_top_barriers" name="s8_top_barriers" rows="3" placeholder="1. ...&#10;2. ...&#10;3. ..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
              </div>
            </div>

            <div class="space-y-3.5 pt-2 border-t border-slate-100">
              <h3 class="text-xs font-bold text-slate-900 uppercase">B. Key Recommendation &amp; Closing Thoughts</h3>
              
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="s8_change_one_thing">If you could change ONE thing for the next cohort, what would it be?</label>
                <textarea id="s8_change_one_thing" name="s8_change_one_thing" rows="2" placeholder="My single most important recommendation is..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="s8_one_word">One word to describe how you feel now:</label>
                <input type="text" id="s8_one_word" name="s8_one_word" placeholder="e.g. Empowered, Inspired, Resilient" class="w-full sm:w-80 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition font-semibold text-brand-700">
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="s8_final_message">Final message for your YSO or for DOT:</label>
                <textarea id="s8_final_message" name="s8_final_message" rows="2" placeholder="Any final thoughts, words of appreciation, or strategic counsel..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 text-sm outline-none transition"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 9: Review & Export -->
        <div id="step-9" class="step-content hidden bg-white rounded-2xl p-4 sm:p-7 border border-slate-200 shadow-sm space-y-5">
          <div class="border-b border-slate-100 pb-3 sm:pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Review &amp; Complete</span>
              <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 font-display mt-0.5">Review Anonymous Responses</h2>
              <p class="text-xs sm:text-sm text-slate-500 mt-1">Review your feedback summary below before downloading your official PDF copy.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
              <button type="button" data-action="export-pdf" onclick="exportToPdf()" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-3 py-1.5 rounded-lg transition shadow-xs active:scale-95 cursor-pointer whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>PDF Only</span>
              </button>
              <button type="button" data-action="submit-server" onclick="submitToServer()" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition shadow-sm shadow-emerald-500/20 active:scale-95 cursor-pointer group whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Submit &amp; Download PDF</span>
                <span class="group-hover:translate-x-0.5 transition-transform font-black text-emerald-200">&raquo;</span>
              </button>
            </div>
          </div>

          <!-- Live Dynamic Preview Box -->
          <div id="review-container" class="bg-slate-50 rounded-xl p-3 sm:p-5 border border-slate-200 text-xs sm:text-sm space-y-3.5 max-h-[500px] overflow-y-auto">
            <!-- Dynamically populated via JS -->
          </div>
        </div>

        <!-- Desktop & In-Flow Nav Footer -->
        <div class="hidden lg:flex items-center justify-between pt-2">
          <button type="button" id="prev-btn" data-action="prev" onclick="prevStep()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm transition shadow-sm disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Back</span>
          </button>

          <div class="flex items-center gap-2">
            <span id="step-indicator" class="text-xs font-semibold text-slate-400">Step 1 of 10</span>
          </div>

          <button type="button" id="next-btn" data-action="next" onclick="nextStep()" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm transition shadow-sm active:scale-95 cursor-pointer">
            <span id="next-btn-text">Get Started</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </form>
    </main>
  </div>

  <!-- Mobile Sticky Bottom Navigation Bar (Visible below lg) -->
  <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-3 py-2.5 shadow-lg">
    <div class="max-w-md mx-auto flex items-center justify-between gap-3">
      <button type="button" id="mobile-prev-btn" data-action="prev" onclick="prevStep()" class="flex-1 py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 active:bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center gap-1.5 transition disabled:opacity-30 disabled:pointer-events-none cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        <span>Back</span>
      </button>

      <button type="button" id="mobile-next-btn" data-action="next" onclick="nextStep()" class="flex-[2] py-2.5 px-4 rounded-xl bg-brand-600 active:bg-brand-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition cursor-pointer">
        <span id="mobile-next-btn-text">Get Started</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile Steps Drawer Modal -->
  <div id="mobile-steps-drawer" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex flex-col justify-end transition-opacity">
    <div class="bg-white rounded-t-3xl max-h-[85vh] flex flex-col p-4 shadow-2xl animate-slide-up">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div>
          <h3 class="text-sm font-bold text-slate-900 font-display">Worksheet Outline</h3>
          <p class="text-xs text-slate-500">Jump directly to any section</p>
        </div>
        <button type="button" data-action="toggle-drawer" onclick="toggleMobileStepDrawer()" class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <nav class="overflow-y-auto py-2 space-y-1" id="mobile-drawer-list">
        <!-- Populated via JS -->
      </nav>
    </div>
  </div>

  <!-- Mandatory Validation Alert Modal -->
  <div id="validation-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-5 sm:p-7 shadow-2xl border border-slate-200 space-y-4 animate-scale-up">
      <div class="flex items-start gap-3.5">
        <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </div>
        <div class="flex-1">
          <span class="text-[10px] font-extrabold uppercase bg-amber-50 text-amber-800 px-2 py-0.5 rounded border border-amber-200">Mandatory Review</span>
          <h3 class="text-base sm:text-lg font-black text-slate-900 font-display mt-0.5">Your Feedback Matters, Say Something</h3>
          <p class="text-xs text-slate-500 mt-1">Every reflection question is required before submitting and downloading your official PDF. Please complete the remaining questions below.</p>
        </div>
      </div>

      <!-- Missing Fields List -->
      <div class="space-y-2">
        <div class="flex items-center justify-between text-xs font-bold text-slate-700">
          <span>Items Left Out:</span>
          <span id="missing-count-badge" class="bg-rose-50 text-rose-700 px-2.5 py-0.5 rounded-full border border-rose-200 text-[11px] font-extrabold">0 Missing</span>
        </div>
        <div id="missing-fields-list" class="max-h-56 overflow-y-auto space-y-1.5 p-1.5 bg-slate-50 rounded-2xl border border-slate-200">
          <!-- Dynamically populated via JS -->
        </div>
      </div>

      <div class="flex items-center justify-end gap-2.5 pt-1">
        <button type="button" onclick="closeValidationModal()" class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition cursor-pointer">
          Dismiss
        </button>
        <button type="button" id="fix-first-missing-btn" onclick="fixFirstMissingField()" class="px-5 py-2.5 text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition shadow-md shadow-brand-500/20 active:scale-95 cursor-pointer">
          Complete Missing Items &rarr;
        </button>
      </div>
    </div>
  </div>

  <!-- Reset Confirmation Modal -->
  <div id="reset-modal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm sm:max-w-md w-full p-5 sm:p-6 shadow-xl border border-slate-200 space-y-4">
      <div class="w-11 h-11 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
      </div>
      <div>
        <h3 class="text-base sm:text-lg font-bold text-slate-900 font-display">Clear Saved Responses?</h3>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">This will erase your saved draft from this device's memory. This action cannot be undone.</p>
      </div>
      <div class="flex items-center justify-end gap-2.5 pt-2">
        <button type="button" onclick="closeResetModal()" class="px-3.5 py-2 text-xs sm:text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition cursor-pointer">Cancel</button>
        <button type="button" onclick="confirmReset()" class="px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition shadow-sm cursor-pointer">Yes, Clear All</button>
      </div>
    </div>
  </div>

  <!-- Hidden Offscreen Container for Clean Multi-Page Anonymous PDF Generation -->
  <div id="pdf-render-zone">
    <div id="pdf-printable-template" class="p-8 text-slate-900 leading-normal" style="width: 794px; background: #ffffff;">
      
      <!-- PAGE 1: Header + Section 1 (Journey Mapping) -->
      <div class="pdf-section pdf-avoid-break">
        <!-- PDF Document Header -->
        <div class="border-b-2 border-slate-800 pb-3 mb-4">
          <div class="flex justify-between items-start">
            <div>
              <span class="text-[9px] font-extrabold uppercase tracking-widest text-sky-800 bg-sky-100 px-2 py-0.5 rounded">Digital Opportunity Trust &amp; Partner Network</span>
              <h1 class="text-xl font-black text-slate-900 mt-1">Cohort Review &amp; Planning: Participant Reflection &amp; Action Worksheet</h1>
              <p class="text-xs text-slate-600 mt-0.5 font-medium">Anonymous Performance Evaluation &amp; Strategic Action Plan for Cohorts 2 &amp; 3</p>
            </div>
            <div class="text-right shrink-0">
              <span class="inline-block text-[9px] font-black uppercase tracking-wider text-emerald-800 bg-emerald-100 border border-emerald-300 px-2.5 py-1 rounded">
                100% Anonymous Submission
              </span>
            </div>
          </div>

          <!-- Anonymous Meta Banner -->
          <div class="flex items-center justify-between bg-slate-100 p-2.5 rounded-lg mt-3 text-[11px] border border-slate-200">
            <div>
              <span class="font-bold text-slate-600 uppercase text-[8.5px]">Worksheet Type:</span>
              <span class="font-semibold text-slate-900">Cohort 1 Anonymous Feedback &amp; Review</span>
            </div>
            <div>
              <span class="font-bold text-slate-600 uppercase text-[8.5px]">Submission Status:</span>
              <span class="font-semibold text-emerald-700">Confidential / De-identified</span>
            </div>
            <div>
              <span class="font-bold text-slate-600 uppercase text-[8.5px]">Document Generated:</span>
              <span id="pdf-meta-date" class="font-semibold text-slate-900">--</span>
            </div>
          </div>
        </div>

        <!-- Section 1 in PDF -->
        <div class="mb-4 pdf-avoid-break">
          <div class="bg-slate-800 text-white px-3 py-1.5 rounded-t-lg font-bold text-xs uppercase tracking-wide flex items-center justify-between">
            <span>1. Participant &amp; Youth Leader Journey Mapping</span>
            <span class="text-[10px] text-slate-300 font-normal">Section 1 of 8</span>
          </div>
          <p class="text-[9.5px] text-slate-600 bg-slate-50 px-3 py-1 border-x border-slate-300 italic">Reflect on the complete journey from initial recruitment through to graduation and post-training linkages.</p>
          
          <table class="w-full border-collapse border border-slate-300 text-[10px]">
            <thead>
              <tr class="bg-slate-100 text-slate-800">
                <th class="border border-slate-300 p-2 text-left w-1/2 font-bold">High Points &amp; Successes</th>
                <th class="border border-slate-300 p-2 text-left w-1/2 font-bold">Challenges &amp; Bottlenecks</th>
              </tr>
            </thead>
            <tbody>
              <tr class="pdf-avoid-break">
                <td colspan="2" class="bg-slate-50 font-bold px-2.5 py-1 border border-slate-300 text-sky-900 text-[9.5px]">
                  A. Recruitment &amp; Application / Mobilization Phase
                </td>
              </tr>
              <tr class="pdf-avoid-break">
                <td id="pdf_s1_recruitment_high" class="border border-slate-300 p-2.5 align-top text-slate-700 min-h-[45px] whitespace-pre-wrap">--</td>
                <td id="pdf_s1_recruitment_challenges" class="border border-slate-300 p-2.5 align-top text-slate-700 min-h-[45px] whitespace-pre-wrap">--</td>
              </tr>
              <tr class="pdf-avoid-break">
                <td colspan="2" class="bg-slate-50 font-bold px-2.5 py-1 border border-slate-300 text-sky-900 text-[9.5px]">
                  B. Graduation &amp; Milestone Completion
                </td>
              </tr>
              <tr class="pdf-avoid-break">
                <td id="pdf_s1_grad_high" class="border border-slate-300 p-2.5 align-top text-slate-700 min-h-[45px] whitespace-pre-wrap">--</td>
                <td id="pdf_s1_grad_challenges" class="border border-slate-300 p-2.5 align-top text-slate-700 min-h-[45px] whitespace-pre-wrap">--</td>
              </tr>
              <tr class="pdf-avoid-break">
                <td colspan="2" class="bg-slate-50 font-bold px-2.5 py-1 border border-slate-300 text-sky-900 text-[9.5px]">
                  C. End of Journey: BDS Support &amp; Market Linkages
                </td>
              </tr>
              <tr class="pdf-avoid-break">
                <td id="pdf_s1_bds_high" class="border border-slate-300 p-2.5 align-top text-slate-700 min-h-[45px] whitespace-pre-wrap">--</td>
                <td id="pdf_s1_bds_challenges" class="border border-slate-300 p-2.5 align-top text-slate-700 min-h-[45px] whitespace-pre-wrap">--</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- PAGE BREAK: Section 2 & 3 -->
      <div class="pdf-page-break-before pdf-section">
        <!-- Section 2 in PDF -->
        <div class="mb-4 pdf-avoid-break">
          <div class="bg-slate-800 text-white px-3 py-1.5 rounded-t-lg font-bold text-xs uppercase tracking-wide flex items-center justify-between">
            <span>2. Operational Hurdles, Communication &amp; Host Org Placements</span>
            <span class="text-[10px] text-slate-300 font-normal">Section 2 of 8</span>
          </div>
          <div class="border border-slate-300 p-3 space-y-2.5 text-[10px] bg-white rounded-b-lg">
            <div>
              <span class="font-bold text-slate-800 block text-[10px]">A. Operational &amp; Communication Hurdles:</span>
              <div id="pdf_s2_ops_comm" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
            </div>
            <div class="grid grid-cols-2 gap-2.5 pt-0.5">
              <div>
                <span class="font-bold text-slate-800 block text-[10px]">Host Org Criteria &amp; Placements:</span>
                <div id="pdf_s2_host_criteria" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
              </div>
              <div>
                <span class="font-bold text-slate-800 block text-[10px]">Standardized Reporting &amp; Coordination Fixes:</span>
                <div id="pdf_s2_reporting_fixes" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Section 3 in PDF -->
        <div class="mb-4 pdf-avoid-break">
          <div class="bg-slate-800 text-white px-3 py-1.5 rounded-t-lg font-bold text-xs uppercase tracking-wide flex items-center justify-between">
            <span>3. Participant Tracking, PACRA 50% Target &amp; BDS Linkages</span>
            <span class="text-[10px] text-slate-300 font-normal">Section 3 of 8</span>
          </div>
          <div class="border border-slate-300 p-3 space-y-2.5 text-[10px] bg-white rounded-b-lg">
            <div>
              <span class="font-bold text-slate-800 block text-[10px]">B. Strategy to Hit the 50% PACRA Goal &amp; Performance Tracker Use:</span>
              <div id="pdf_s3_pacra_strategy" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
            </div>
            <div>
              <span class="font-bold text-slate-800 block text-[10px]">C. BDS Linkages &amp; Market Access:</span>
              <div id="pdf_s3_market_access" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
            </div>
          </div>
        </div>
      </div>

      <!-- PAGE BREAK: Section 4 & 5 -->
      <div class="pdf-page-break-before pdf-section">
        <!-- Section 4 in PDF -->
        <div class="mb-4 pdf-avoid-break">
          <div class="bg-slate-800 text-white px-3 py-1.5 rounded-t-lg font-bold text-xs uppercase tracking-wide flex items-center justify-between">
            <span>4. Community &amp; Family Engagement, Safeguarding &amp; Inclusion</span>
            <span class="text-[10px] text-slate-300 font-normal">Section 4 of 8</span>
          </div>
          <div class="border border-slate-300 p-3 space-y-2.5 text-[10px] bg-white rounded-b-lg">
            <div>
              <span class="font-bold text-slate-800 block text-[10px]">A. Household Buy-In &amp; Drop-out Prevention:</span>
              <div id="pdf_s4_household_buyin" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="font-bold text-slate-800 text-[10px]">B. Safeguarding Channels Accessible:</span>
                <span id="pdf_s4_safeguarding_accessible" class="font-bold text-sky-900 bg-sky-50 px-2 py-0.5 rounded border border-sky-200 text-[9.5px]">--</span>
              </div>
              <div class="mt-1.5">
                <span class="text-[9px] text-slate-600 block font-semibold">Specific protocols, incident reporting steps, or inclusion measures:</span>
                <div id="pdf_s4_safeguarding_details" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Section 5 in PDF -->
        <div class="mb-4 pdf-avoid-break">
          <div class="bg-slate-800 text-white px-3 py-1.5 rounded-t-lg font-bold text-xs uppercase tracking-wide flex items-center justify-between">
            <span>5. Recruitment Strategy, Market Walk-Throughs &amp; Finance</span>
            <span class="text-[10px] text-slate-300 font-normal">Section 5 of 8</span>
          </div>
          <div class="border border-slate-300 p-3 space-y-2.5 text-[10px] bg-white rounded-b-lg">
            <div>
              <span class="font-bold text-slate-800 block text-[10px]">A. Recruitment Strategy &amp; Center Market Walk-Throughs:</span>
              <div id="pdf_s5_recruitment_walkthroughs" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
            </div>
            <div>
              <span class="font-bold text-slate-800 block text-[10px]">B. Financial Processes &amp; Stipend Disbursements:</span>
              <div id="pdf_s5_finance_stipends" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
            </div>
          </div>
        </div>
      </div>

      <!-- PAGE BREAK: Section 6, 7, 8 & Anonymous Verification -->
      <div class="pdf-page-break-before pdf-section">
        <!-- Section 6 in PDF -->
        <div class="mb-4 pdf-avoid-break">
          <div class="bg-slate-800 text-white px-3 py-1.5 rounded-t-lg font-bold text-xs uppercase tracking-wide flex items-center justify-between">
            <span>6. Youth Leader (YL) Employment Linkages &amp; Transition</span>
            <span class="text-[10px] text-slate-300 font-normal">Section 6 of 8</span>
          </div>
          <div class="border border-slate-300 p-3 text-[10px] bg-white rounded-b-lg">
            <span class="font-bold text-slate-800 block text-[10px]">Key transition barriers faced and practical linkage pathways:</span>
            <div id="pdf_s6_yl_transition" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
          </div>
        </div>

        <!-- Section 7 in PDF -->
        <div class="mb-4 pdf-avoid-break">
          <div class="bg-slate-800 text-white px-3 py-1.5 rounded-t-lg font-bold text-xs uppercase tracking-wide flex items-center justify-between">
            <span>7. What Changed for You? (Personal &amp; Mindset Impact)</span>
            <span class="text-[10px] text-slate-300 font-normal">Section 7 of 8</span>
          </div>
          <table class="w-full border-collapse border border-slate-300 text-[10px]">
            <tbody>
              <tr class="pdf-avoid-break">
                <td class="w-1/3 bg-slate-100 font-bold p-2.5 border border-slate-300 text-slate-800 align-top">Skills Gained:</td>
                <td id="pdf_s7_skills_gained" class="p-2.5 border border-slate-300 text-slate-700 whitespace-pre-wrap align-top">--</td>
              </tr>
              <tr class="pdf-avoid-break">
                <td class="w-1/3 bg-slate-100 font-bold p-2.5 border border-slate-300 text-slate-800 align-top">Mindset / Confidence Shift:</td>
                <td id="pdf_s7_mindset_shift" class="p-2.5 border border-slate-300 text-slate-700 whitespace-pre-wrap align-top">--</td>
              </tr>
              <tr class="pdf-avoid-break">
                <td class="w-1/3 bg-slate-100 font-bold p-2.5 border border-slate-300 text-slate-800 align-top">Action Taken in Community:</td>
                <td id="pdf_s7_action_taken" class="p-2.5 border border-slate-300 text-slate-700 whitespace-pre-wrap align-top">--</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Section 8 in PDF -->
        <div class="mb-4 pdf-avoid-break">
          <div class="bg-slate-800 text-white px-3 py-1.5 rounded-t-lg font-bold text-xs uppercase tracking-wide flex items-center justify-between">
            <span>8. Summary Recommendations &amp; Final Reflections</span>
            <span class="text-[10px] text-slate-300 font-normal">Section 8 of 8</span>
          </div>
          <div class="border border-slate-300 p-3 space-y-2.5 text-[10px] bg-white rounded-b-lg">
            <div class="grid grid-cols-2 gap-2.5">
              <div>
                <span class="font-bold text-emerald-900 block text-[9.5px] uppercase">Top Things That Worked Well:</span>
                <div id="pdf_s8_top_worked" class="mt-1 text-slate-700 whitespace-pre-wrap bg-emerald-50/60 p-2.5 rounded border border-emerald-200">--</div>
              </div>
              <div>
                <span class="font-bold text-rose-900 block text-[9.5px] uppercase">Top Challenges / Barriers:</span>
                <div id="pdf_s8_top_barriers" class="mt-1 text-slate-700 whitespace-pre-wrap bg-rose-50/60 p-2.5 rounded border border-rose-200">--</div>
              </div>
            </div>
            
            <div class="pt-1.5 border-t border-slate-200">
              <span class="font-bold text-slate-800 block">If you could change ONE thing for the next cohort:</span>
              <div id="pdf_s8_change_one_thing" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2.5 rounded border border-slate-200">--</div>
            </div>

            <div class="grid grid-cols-2 gap-2.5 pt-1">
              <div>
                <span class="font-bold text-slate-800 block">One word feeling:</span>
                <div id="pdf_s8_one_word" class="mt-1 font-bold text-brand-700 bg-sky-50 p-2 rounded border border-sky-200">--</div>
              </div>
              <div>
                <span class="font-bold text-slate-800 block">Final message for YSO / DOT:</span>
                <div id="pdf_s8_final_message" class="mt-1 text-slate-700 whitespace-pre-wrap bg-slate-50 p-2 rounded border border-slate-200">--</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Anonymous Submission Footer -->
        <div class="mt-6 pt-3 border-t-2 border-slate-200 flex justify-between items-end text-[9.5px] text-slate-500 pdf-avoid-break">
          <div>
            <p class="font-semibold text-slate-700">Digital Opportunity Trust Continuous Evaluation Framework</p>
            <p id="pdf-timestamp">Timestamp: --</p>
          </div>
          <div class="text-right">
            <span class="text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Verified Anonymous Participant Report</span>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
