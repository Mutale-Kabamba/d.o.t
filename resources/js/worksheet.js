const TOTAL_STEPS = 10;
let currentStep = 0;
const STORAGE_KEY = 'DOT_COHORT1_WORKSHEET_ANONYMOUS_V3';
const CURRENT_STEP_KEY = 'DOT_COHORT1_CURRENT_STEP_ANONYMOUS_V3';

// Step definitions starting with Intro
const stepDefinitions = [
  { id: 0, title: 'Introduction', subtitle: 'Overview & Purpose' },
  { id: 1, title: '1. Journey', subtitle: 'Recruitment & Graduation' },
  { id: 2, title: '2. Ops & Placements', subtitle: 'Host Org & Comms' },
  { id: 3, title: '3. PACRA & BDS', subtitle: '50% Target & Markets' },
  { id: 4, title: '4. Community', subtitle: 'Sensitization & Safe Spaces' },
  { id: 5, title: '5. Finance & Walk', subtitle: 'Disbursements & Markets' },
  { id: 6, title: '6. YL Transitions', subtitle: 'Post-Project Linkages' },
  { id: 7, title: '7. Mindset Impact', subtitle: 'Skills & Confidence' },
  { id: 8, title: '8. Recommendations', subtitle: 'Top Fixes & Message' },
  { id: 9, title: 'Review & PDF', subtitle: 'Summary & Export' }
];

// All mandatory questions with labels, sections, and steps
const fieldDefinitions = [
  // Section 1 (Step 1)
  { id: 's1_recruitment_high', step: 1, section: '1. Journey Mapping', label: 'Recruitment Highs & Successes' },
  { id: 's1_recruitment_challenges', step: 1, section: '1. Journey Mapping', label: 'Recruitment Challenges & Bottlenecks' },
  { id: 's1_grad_high', step: 1, section: '1. Journey Mapping', label: 'Graduation Highs & Successes' },
  { id: 's1_grad_challenges', step: 1, section: '1. Journey Mapping', label: 'Graduation Challenges & Bottlenecks' },
  { id: 's1_bds_high', step: 1, section: '1. Journey Mapping', label: 'BDS Support Highs' },
  { id: 's1_bds_challenges', step: 1, section: '1. Journey Mapping', label: 'BDS Support Challenges' },
  // Section 2 (Step 2)
  { id: 's2_ops_comm', step: 2, section: '2. Operations & Placements', label: 'Operational & Communication Hurdles' },
  { id: 's2_host_criteria', step: 2, section: '2. Operations & Placements', label: 'Host Organization Criteria' },
  { id: 's2_reporting_fixes', step: 2, section: '2. Operations & Placements', label: 'Standardized Reporting Fixes' },
  // Section 3 (Step 3)
  { id: 's3_pacra_strategy', step: 3, section: '3. PACRA 50% Target', label: 'Strategy for 50% PACRA Goal' },
  { id: 's3_market_access', step: 3, section: '3. PACRA 50% Target', label: 'BDS Linkages & Market Access' },
  // Section 4 (Step 4)
  { id: 's4_household_buyin', step: 4, section: '4. Community & Safeguarding', label: 'Household Buy-In & Retention' },
  { id: 's4_safeguarding_accessible', step: 4, section: '4. Community & Safeguarding', label: 'Safeguarding Channels Accessible' },
  { id: 's4_safeguarding_details', step: 4, section: '4. Community & Safeguarding', label: 'Safeguarding Protocols & Inclusion' },
  // Section 5 (Step 5)
  { id: 's5_recruitment_walkthroughs', step: 5, section: '5. Finance & Recruitment', label: 'Market Walk-Through Strategy' },
  { id: 's5_finance_stipends', step: 5, section: '5. Finance & Recruitment', label: 'Financial Processes & Stipends' },
  // Section 6 (Step 6)
  { id: 's6_yl_transition', step: 6, section: '6. Youth Leader Transition', label: 'Transition Barriers & Linkages' },
  // Section 7 (Step 7)
  { id: 's7_skills_gained', step: 7, section: '7. Personal & Mindset Impact', label: 'Skills Gained' },
  { id: 's7_mindset_shift', step: 7, section: '7. Personal & Mindset Impact', label: 'Mindset & Confidence Shift' },
  { id: 's7_action_taken', step: 7, section: '7. Personal & Mindset Impact', label: 'Action Taken in Community' },
  // Section 8 (Step 8)
  { id: 's8_top_worked', step: 8, section: '8. Summary Recommendations', label: 'Top Things That Worked Well' },
  { id: 's8_top_barriers', step: 8, section: '8. Summary Recommendations', label: 'Top Challenges & Barriers' },
  { id: 's8_change_one_thing', step: 8, section: '8. Summary Recommendations', label: 'Change ONE Thing for Next Cohort' },
  { id: 's8_one_word', step: 8, section: '8. Summary Recommendations', label: 'One Word Feeling' },
  { id: 's8_final_message', step: 8, section: '8. Summary Recommendations', label: 'Final Message for YSO / DOT' }
];

const formFields = fieldDefinitions.map(f => f.id);

let currentMissingFields = [];

// Initialize DOM
export function initWorksheet() {
  buildNavigations();
  loadSavedData();
  setupAutoSave();
  setupButtonListeners();

  const savedStep = localStorage.getItem(CURRENT_STEP_KEY);
  if (savedStep !== null) {
    const num = parseInt(savedStep, 10);
    if (!isNaN(num) && num >= 0 && num < TOTAL_STEPS) {
      goToStep(num);
    } else {
      goToStep(0);
    }
  } else {
    goToStep(0);
  }

  updateProgress();
}

// Attach direct event listeners to static buttons
export function setupButtonListeners() {
  document.querySelectorAll('[data-action="next"]').forEach(btn => {
    btn.addEventListener('click', (e) => { e.preventDefault(); nextStep(); });
  });

  document.querySelectorAll('[data-action="prev"]').forEach(btn => {
    btn.addEventListener('click', (e) => { e.preventDefault(); prevStep(); });
  });

  document.querySelectorAll('[data-action="export-pdf"]').forEach(btn => {
    btn.addEventListener('click', (e) => { e.preventDefault(); exportToPdf(); });
  });

  document.querySelectorAll('[data-action="reset"]').forEach(btn => {
    btn.addEventListener('click', (e) => { e.preventDefault(); openResetModal(); });
  });

  document.querySelectorAll('[data-action="toggle-drawer"]').forEach(btn => {
    btn.addEventListener('click', (e) => { e.preventDefault(); toggleMobileStepDrawer(); });
  });

  document.querySelectorAll('[data-action="submit-server"]').forEach(btn => {
    btn.addEventListener('click', (e) => { e.preventDefault(); submitToServer(); });
  });
}

// Build responsive navigation items
export function buildNavigations() {
  // Desktop Stepper
  const desktopNav = document.getElementById('stepper-list');
  if (desktopNav) {
    desktopNav.innerHTML = stepDefinitions.map(s => `
      <button type="button" onclick="goToStep(${s.id})" class="step-nav-btn w-full text-left p-2.5 sm:p-3 rounded-xl transition flex items-center gap-3 text-xs font-medium group cursor-pointer" data-step="${s.id}">
        <div class="step-badge w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs bg-slate-100 text-slate-600 group-hover:bg-brand-100 group-hover:text-brand-700">${s.id === 0 ? '★' : (s.id === 9 ? '✓' : s.id)}</div>
        <div class="flex-1 truncate">
          <span class="block font-semibold text-slate-800">${s.title}</span>
          <span class="text-[11px] text-slate-400">${s.subtitle}</span>
        </div>
      </button>
    `).join('');
  }

  // Mobile Horizontal Chips
  const mobileChips = document.getElementById('mobile-chips-container');
  if (mobileChips) {
    mobileChips.innerHTML = stepDefinitions.map(s => `
      <button type="button" onclick="goToStep(${s.id})" class="mobile-chip-btn whitespace-nowrap px-3 py-1 rounded-full text-xs font-bold transition shrink-0 border cursor-pointer" data-step="${s.id}">
        ${s.id === 0 ? 'Intro' : `${s.id}: ${s.title}`}
      </button>
    `).join('');
  }

  // Mobile Drawer List
  const mobileDrawer = document.getElementById('mobile-drawer-list');
  if (mobileDrawer) {
    mobileDrawer.innerHTML = stepDefinitions.map(s => `
      <button type="button" onclick="goToStep(${s.id}); toggleMobileStepDrawer();" class="drawer-step-btn w-full text-left p-3 rounded-xl transition flex items-center gap-3 text-xs font-medium cursor-pointer" data-step="${s.id}">
        <div class="step-badge w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs bg-slate-100 text-slate-700">${s.id === 0 ? '★' : (s.id === 9 ? '✓' : s.id)}</div>
        <div class="flex-1 truncate">
          <span class="block font-bold text-slate-800 text-sm">${s.title}</span>
          <span class="text-xs text-slate-400">${s.subtitle}</span>
        </div>
      </button>
    `).join('');
  }
}

// Step Switcher (Users can freely jump to any step)
export function goToStep(stepIndex) {
  if (stepIndex < 0 || stepIndex >= TOTAL_STEPS) return;

  for (let i = 0; i < TOTAL_STEPS; i++) {
    const el = document.getElementById(`step-${i}`);
    if (el) el.classList.add('hidden');
  }

  const target = document.getElementById(`step-${stepIndex}`);
  if (target) target.classList.remove('hidden');

  currentStep = stepIndex;
  localStorage.setItem(CURRENT_STEP_KEY, currentStep.toString());

  updateStepperUI();

  if (currentStep === 9) {
    renderReviewSummary();
  }

  window.scrollTo({ top: 0, behavior: 'smooth' });
}

export function updateStepperUI() {
  // Desktop Stepper Active State
  document.querySelectorAll('.step-nav-btn').forEach(btn => {
    const step = parseInt(btn.getAttribute('data-step'), 10);
    const badge = btn.querySelector('.step-badge');
    if (step === currentStep) {
      btn.classList.add('bg-brand-50', 'text-brand-900', 'font-bold', 'border', 'border-brand-200');
      btn.classList.remove('text-slate-600', 'hover:bg-slate-50');
      if (badge) {
        badge.classList.add('bg-brand-600', 'text-white');
        badge.classList.remove('bg-slate-100', 'text-slate-600');
      }
    } else {
      btn.classList.remove('bg-brand-50', 'text-brand-900', 'font-bold', 'border', 'border-brand-200');
      btn.classList.add('text-slate-600', 'hover:bg-slate-50');
      if (badge) {
        badge.classList.remove('bg-brand-600', 'text-white');
        badge.classList.add('bg-slate-100', 'text-slate-600');
      }
    }
  });

  // Mobile Horizontal Chips
  document.querySelectorAll('.mobile-chip-btn').forEach(chip => {
    const step = parseInt(chip.getAttribute('data-step'), 10);
    if (step === currentStep) {
      chip.className = 'mobile-chip-btn whitespace-nowrap px-3 py-1 rounded-full text-xs font-bold transition shrink-0 bg-brand-600 text-white border-brand-600 shadow-xs cursor-pointer';
      chip.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    } else {
      chip.className = 'mobile-chip-btn whitespace-nowrap px-3 py-1 rounded-full text-xs font-bold transition shrink-0 bg-white text-slate-600 border-slate-200 hover:bg-slate-100 cursor-pointer';
    }
  });

  // Mobile Drawer Buttons
  document.querySelectorAll('.drawer-step-btn').forEach(btn => {
    const step = parseInt(btn.getAttribute('data-step'), 10);
    if (step === currentStep) {
      btn.className = 'drawer-step-btn w-full text-left p-3 rounded-xl transition flex items-center gap-3 text-xs bg-brand-50 border border-brand-200 text-brand-900 font-bold cursor-pointer';
    } else {
      btn.className = 'drawer-step-btn w-full text-left p-3 rounded-xl transition flex items-center gap-3 text-xs text-slate-700 hover:bg-slate-50 cursor-pointer';
    }
  });

  // Labels & Buttons
  const stepTitle = stepDefinitions[currentStep]?.title || '';
  const mobileTitle = document.getElementById('mobile-step-title');
  if (mobileTitle) {
    mobileTitle.innerText = currentStep === 0 ? 'Introduction & Purpose' : `Step ${currentStep + 1}/${TOTAL_STEPS}: ${stepTitle}`;
  }

  const desktopStepInd = document.getElementById('step-indicator');
  if (desktopStepInd) {
    desktopStepInd.innerText = currentStep === 0 ? 'Welcome & Intro' : `Step ${currentStep + 1} of ${TOTAL_STEPS}`;
  }

  // Prev/Next buttons - Hide Back on Step 0
  const prevBtn = document.getElementById('prev-btn');
  const mobilePrevBtn = document.getElementById('mobile-prev-btn');
  
  if (prevBtn) {
    if (currentStep === 0) {
      prevBtn.classList.add('hidden');
    } else {
      prevBtn.classList.remove('hidden');
    }
  }

  if (mobilePrevBtn) {
    if (currentStep === 0) {
      mobilePrevBtn.classList.add('hidden');
    } else {
      mobilePrevBtn.classList.remove('hidden');
    }
  }

  const nextBtnText = document.getElementById('next-btn-text');
  const mobileNextBtnText = document.getElementById('mobile-next-btn-text');
  
  let label = 'Next Section';
  if (currentStep === 0) {
    label = 'Get Started';
  } else if (currentStep === TOTAL_STEPS - 2) {
    label = 'Review Summary';
  } else if (currentStep === TOTAL_STEPS - 1) {
    label = 'Submit and Download Official PDF »';
  }

  if (nextBtnText) nextBtnText.innerText = label;
  if (mobileNextBtnText) mobileNextBtnText.innerText = label;
}

export function nextStep() {
  if (currentStep === TOTAL_STEPS - 1) {
    submitToServer();
    return;
  }
  goToStep(currentStep + 1);
}

export function prevStep() {
  if (currentStep > 0) {
    goToStep(currentStep - 1);
  }
}

export function toggleMobileStepDrawer() {
  const drawer = document.getElementById('mobile-steps-drawer');
  if (!drawer) return;
  if (drawer.classList.contains('hidden')) {
    drawer.classList.remove('hidden');
  } else {
    drawer.classList.add('hidden');
  }
}

// Persistence with Auto-Save
export function saveData() {
  const data = {};
  formFields.forEach(field => {
    const el = document.getElementById(field);
    if (el) {
      data[field] = el.value;
    } else {
      const radios = document.getElementsByName(field);
      for (const r of radios) {
        if (r.checked) {
          data[field] = r.value;
          break;
        }
      }
    }
  });

  localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
  localStorage.setItem(CURRENT_STEP_KEY, currentStep.toString());

  const statusText = document.getElementById('save-status-text');
  if (statusText) {
    statusText.innerText = 'Saved ' + new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
  }
  updateProgress();
}

export function loadSavedData() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return;
    const data = JSON.parse(raw);
    if (!data) return;

    formFields.forEach(field => {
      if (data[field] !== undefined) {
        const el = document.getElementById(field);
        if (el) {
          el.value = data[field];
        } else {
          const radios = document.getElementsByName(field);
          for (const r of radios) {
            if (r.value === data[field]) {
              r.checked = true;
            }
          }
        }
      }
    });
  } catch (err) {
    console.error('Failed to load saved form', err);
  }
}

export function setupAutoSave() {
  const form = document.getElementById('worksheet-form');
  if (form) {
    form.addEventListener('input', saveData);
    form.addEventListener('change', saveData);
  }
}

// Check for missing mandatory questions
export function getMissingFields() {
  const missing = [];
  
  fieldDefinitions.forEach(field => {
    let value = '';
    const el = document.getElementById(field.id);
    if (el) {
      value = el.value.trim();
    } else {
      const radios = document.getElementsByName(field.id);
      for (const r of radios) {
        if (r.checked) {
          value = r.value.trim();
          break;
        }
      }
    }

    if (!value) {
      missing.push(field);
    }
  });

  return missing;
}

// Show Mandatory Validation Alert
export function showValidationModal(missing) {
  currentMissingFields = missing;
  const modal = document.getElementById('validation-modal');
  const listContainer = document.getElementById('missing-fields-list');
  const badge = document.getElementById('missing-count-badge');

  if (!modal || !listContainer) return;

  if (badge) {
    badge.innerText = `${missing.length} Item${missing.length > 1 ? 's' : ''} Missing`;
  }

  listContainer.innerHTML = missing.map(f => `
    <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-rose-100 hover:border-rose-300 transition text-xs">
      <div class="truncate mr-2">
        <span class="font-bold text-slate-800 block text-[11px]">${f.section}</span>
        <span class="text-rose-600 text-[11px] truncate block">&bull; ${f.label}</span>
      </div>
      <button type="button" onclick="jumpToField(${f.step}, '${f.id}')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] rounded-lg transition shrink-0 cursor-pointer">
        Complete &rarr;
      </button>
    </div>
  `).join('');

  modal.classList.remove('hidden');
}

export function closeValidationModal() {
  const modal = document.getElementById('validation-modal');
  if (modal) modal.classList.add('hidden');
}

export function fixFirstMissingField() {
  if (currentMissingFields.length > 0) {
    const first = currentMissingFields[0];
    jumpToField(first.step, first.id);
  } else {
    closeValidationModal();
  }
}

export function jumpToField(step, fieldId) {
  closeValidationModal();
  goToStep(step);
  
  setTimeout(() => {
    const el = document.getElementById(fieldId);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'center' });
      el.focus();
      el.classList.add('ring-2', 'ring-rose-500', 'border-rose-500');
      setTimeout(() => {
        el.classList.remove('ring-2', 'ring-rose-500', 'border-rose-500');
      }, 3500);
    } else {
      const radioContainer = document.getElementsByName(fieldId)[0]?.closest('.space-y-2');
      if (radioContainer) {
        radioContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        radioContainer.classList.add('ring-2', 'ring-rose-500', 'p-2', 'rounded-xl');
        setTimeout(() => {
          radioContainer.classList.remove('ring-2', 'ring-rose-500', 'p-2', 'rounded-xl');
        }, 3500);
      }
    }
  }, 350);
}

export function updateProgress() {
  let filled = 0;
  let total = formFields.length;

  formFields.forEach(f => {
    const el = document.getElementById(f);
    if (el && el.value.trim().length > 0) {
      filled++;
    } else {
      const radios = document.getElementsByName(f);
      for (const r of radios) {
        if (r.checked) {
          filled++;
          break;
        }
      }
    }
  });

  const percentage = Math.round((filled / total) * 100);
  const progressBar = document.getElementById('overall-progress-bar');
  const percentBadge = document.getElementById('progress-percent');
  const mobileBadge = document.getElementById('mobile-progress-badge');

  if (progressBar) progressBar.style.width = `${percentage}%`;
  if (percentBadge) percentBadge.innerText = `${percentage}% Done`;
  if (mobileBadge) mobileBadge.innerText = `${percentage}%`;
}

// Dynamic Review Summary with Incomplete/Complete Badges
export function renderReviewSummary() {
  const container = document.getElementById('review-container');
  if (!container) return;

  const missing = getMissingFields();
  const isComplete = missing.length === 0;

  let html = `
    <div class="${isComplete ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200'} p-3.5 rounded-xl border flex flex-col sm:flex-row sm:items-center justify-between gap-2">
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5 ${isComplete ? 'text-emerald-600' : 'text-amber-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isComplete ? 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'}" />
        </svg>
        <div>
          <span class="text-xs font-bold ${isComplete ? 'text-emerald-900' : 'text-amber-900'} block">
            ${isComplete ? 'All Mandatory Sections Completed!' : 'Your Feedback Matters, Say Something'}
          </span>
          <span class="text-[11px] ${isComplete ? 'text-emerald-700' : 'text-amber-700'}">
            ${isComplete ? 'Ready to submit anonymously & download official PDF receipt.' : `${missing.length} question${missing.length > 1 ? 's' : ''} left out. Complete all questions to enable submit/download.`}
          </span>
        </div>
      </div>
      <span class="text-[11px] ${isComplete ? 'text-emerald-700' : 'text-amber-800 font-bold'}">${new Date().toLocaleDateString()}</span>
    </div>
  `;

  const addSection = (title, items, step) => {
    let itemsHtml = '';
    let sectionHasMissing = false;

    items.forEach(item => {
      const isMissing = !item.value || item.value.trim().length === 0;
      if (isMissing) sectionHasMissing = true;

      const val = !isMissing 
        ? escapeHtml(item.value) 
        : '<span class="text-rose-500 font-semibold italic">Left out (Mandatory response required)</span>';

      itemsHtml += `
        <div class="mt-2 text-xs">
          <div class="flex items-center justify-between">
            <span class="font-bold text-slate-700 block text-[11px]">${item.label}:</span>
            ${isMissing ? `<button type="button" onclick="jumpToField(${step}, '${item.id}')" class="text-[10px] text-rose-600 font-bold hover:underline cursor-pointer">Fill Now &rarr;</button>` : ''}
          </div>
          <p class="text-slate-600 bg-white p-2 sm:p-2.5 rounded-lg border ${isMissing ? 'border-rose-200 bg-rose-50/30' : 'border-slate-100'} mt-0.5 whitespace-pre-wrap leading-relaxed">${val}</p>
        </div>
      `;
    });

    return `
      <div class="bg-slate-100/70 p-3 sm:p-4 rounded-xl border ${sectionHasMissing ? 'border-rose-200' : 'border-slate-200'}">
        <div class="flex items-center justify-between">
          <h4 class="text-xs font-extrabold uppercase text-slate-800 tracking-wide">${title}</h4>
          <span class="text-[10px] font-bold px-2 py-0.5 rounded-full ${sectionHasMissing ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700'}">
            ${sectionHasMissing ? 'Incomplete' : 'Complete ✓'}
          </span>
        </div>
        ${itemsHtml}
      </div>
    `;
  };

  html += addSection("1. Journey Mapping", [
    { id: 's1_recruitment_high', label: "Recruitment - Highs", value: document.getElementById('s1_recruitment_high')?.value },
    { id: 's1_recruitment_challenges', label: "Recruitment - Challenges", value: document.getElementById('s1_recruitment_challenges')?.value },
    { id: 's1_grad_high', label: "Graduation - Highs", value: document.getElementById('s1_grad_high')?.value },
    { id: 's1_grad_challenges', label: "Graduation - Challenges", value: document.getElementById('s1_grad_challenges')?.value },
    { id: 's1_bds_high', label: "BDS - Highs", value: document.getElementById('s1_bds_high')?.value },
    { id: 's1_bds_challenges', label: "BDS - Challenges", value: document.getElementById('s1_bds_challenges')?.value }
  ], 1);

  html += addSection("2. Operations & Placements", [
    { id: 's2_ops_comm', label: "Operational & Communication Hurdles", value: document.getElementById('s2_ops_comm')?.value },
    { id: 's2_host_criteria', label: "Host Org Criteria & Placements", value: document.getElementById('s2_host_criteria')?.value },
    { id: 's2_reporting_fixes', label: "Standardized Reporting Fixes", value: document.getElementById('s2_reporting_fixes')?.value }
  ], 2);

  html += addSection("3. PACRA 50% Target & BDS", [
    { id: 's3_pacra_strategy', label: "Strategy for 50% PACRA Goal", value: document.getElementById('s3_pacra_strategy')?.value },
    { id: 's3_market_access', label: "BDS Linkages & Market Access", value: document.getElementById('s3_market_access')?.value }
  ], 3);

  let safeguardingVal = '';
  const radios = document.getElementsByName('s4_safeguarding_accessible');
  for (const r of radios) { if (r.checked) safeguardingVal = r.value; }

  html += addSection("4. Community & Safeguarding", [
    { id: 's4_household_buyin', label: "Household Buy-In & Retention", value: document.getElementById('s4_household_buyin')?.value },
    { id: 's4_safeguarding_accessible', label: "Safeguarding Accessible?", value: safeguardingVal },
    { id: 's4_safeguarding_details', label: "Specific Safeguarding & Inclusion Protocols", value: document.getElementById('s4_safeguarding_details')?.value }
  ], 4);

  html += addSection("5. Recruitment Walk-Throughs & Finance", [
    { id: 's5_recruitment_walkthroughs', label: "Market Walk-Through Strategy", value: document.getElementById('s5_recruitment_walkthroughs')?.value },
    { id: 's5_finance_stipends', label: "Financial Processes & Stipends", value: document.getElementById('s5_finance_stipends')?.value }
  ], 5);

  html += addSection("6. Youth Leader Transition", [
    { id: 's6_yl_transition', label: "Barriers & Practical Linkages", value: document.getElementById('s6_yl_transition')?.value }
  ], 6);

  html += addSection("7. Personal & Mindset Impact", [
    { id: 's7_skills_gained', label: "Skills Gained", value: document.getElementById('s7_skills_gained')?.value },
    { id: 's7_mindset_shift', label: "Mindset / Confidence Shift", value: document.getElementById('s7_mindset_shift')?.value },
    { id: 's7_action_taken', label: "Action Taken in Community", value: document.getElementById('s7_action_taken')?.value }
  ], 7);

  html += addSection("8. Summary Recommendations", [
    { id: 's8_top_worked', label: "Top Things That Worked Well", value: document.getElementById('s8_top_worked')?.value },
    { id: 's8_top_barriers', label: "Top Challenges / Barriers", value: document.getElementById('s8_top_barriers')?.value },
    { id: 's8_change_one_thing', label: "Change One Thing", value: document.getElementById('s8_change_one_thing')?.value },
    { id: 's8_one_word', label: "One Word Feeling", value: document.getElementById('s8_one_word')?.value },
    { id: 's8_final_message', label: "Final Message for YSO / DOT", value: document.getElementById('s8_final_message')?.value }
  ], 8);

  container.innerHTML = html;
}

export function escapeHtml(text) {
  if (!text) return '';
  return text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

// Guarded PDF Generation & Download
export async function exportToPdf() {
  saveData();

  // Validate all fields before export
  const missing = getMissingFields();
  if (missing.length > 0) {
    showToast('Your Feedback Matters, Say Something', 'error');
    showValidationModal(missing);
    return false;
  }

  showToast('Generating official PDF...', 'info');

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const formData = {};

  formFields.forEach(field => {
    const el = document.getElementById(field);
    if (el) {
      formData[field] = el.value;
    } else {
      const radios = document.getElementsByName(field);
      for (const r of radios) {
        if (r.checked) {
          formData[field] = r.value;
          break;
        }
      }
    }
  });

  try {
    const response = await fetch('/export-pdf', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken || '',
        'Accept': 'application/pdf',
      },
      body: JSON.stringify(formData),
    });

    if (!response.ok) {
      throw new Error(`Server returned status ${response.status}`);
    }

    const blob = await response.blob();
    const filename = `Cohort1_Anonymous_Reflection_${Date.now()}.pdf`;

    // Download via object URL
    const blobUrl = window.URL.createObjectURL(blob);
    const downloadLink = document.createElement('a');
    downloadLink.href = blobUrl;
    downloadLink.download = filename;
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();

    setTimeout(() => {
      window.URL.revokeObjectURL(blobUrl);
      downloadLink.remove();
    }, 1500);

    showToast('PDF downloaded successfully!', 'success');
  } catch (error) {
    console.warn('Direct fetch download failed, using standard form submit fallback...', error);
    
    // Fallback: standard POST form submit
    const exportForm = document.createElement('form');
    exportForm.method = 'POST';
    exportForm.action = '/export-pdf';
    exportForm.style.display = 'none';

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken || '';
    exportForm.appendChild(csrfInput);

    Object.keys(formData).forEach(key => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = key;
      input.value = formData[key] || '';
      exportForm.appendChild(input);
    });

    document.body.appendChild(exportForm);
    exportForm.submit();

    setTimeout(() => {
      exportForm.remove();
    }, 2500);
  }
}

// Guarded Server submission with instant official PDF download
export async function submitToServer() {
  saveData();

  // Validate all fields before submission
  const missing = getMissingFields();
  if (missing.length > 0) {
    showToast('Your Feedback Matters, Say Something', 'error');
    showValidationModal(missing);
    return false;
  }

  const form = document.getElementById('worksheet-form');
  const formData = new FormData(form);
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  showToast('Submitting responses & generating PDF...', 'info');

  try {
    const res = await fetch('/submit', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: formData,
    });

    const result = await res.json();
    if (result.success) {
      showToast('Worksheet submitted! Downloading official PDF...', 'success');
      
      // Trigger PDF download with token
      if (result.token) {
        const downloadUrl = `/download-pdf/${result.token}`;
        const a = document.createElement('a');
        a.href = downloadUrl;
        a.download = `DOT_Cohort1_Anonymous_Reflection_${result.token}.pdf`;
        document.body.appendChild(a);
        a.click();
        setTimeout(() => a.remove(), 1200);
      } else {
        exportToPdf();
      }

      // Smooth transition to receipt confirmation page
      if (result.redirect_url) {
        setTimeout(() => {
          window.location.href = result.redirect_url;
        }, 1500);
      }
    } else {
      showToast('Submission error. Please try again.', 'error');
    }
  } catch (err) {
    console.error('Submission failed:', err);
    showToast('Failed to connect to server. Saved locally.', 'error');
  }
}

// Modal controls
export function openResetModal() {
  const modal = document.getElementById('reset-modal');
  if (modal) modal.classList.remove('hidden');
}

export function closeResetModal() {
  const modal = document.getElementById('reset-modal');
  if (modal) modal.classList.add('hidden');
}

export function confirmReset() {
  localStorage.removeItem(STORAGE_KEY);
  localStorage.removeItem(CURRENT_STEP_KEY);
  
  const form = document.getElementById('worksheet-form');
  if (form) form.reset();

  closeResetModal();
  goToStep(0);
  updateProgress();
  showToast('Worksheet has been reset.', 'success');
}

export function showToast(message, type = 'success') {
  const toast = document.getElementById('toast');
  const msg = document.getElementById('toast-msg');
  const icon = document.getElementById('toast-icon');

  if (!toast || !msg) return;

  msg.innerText = message;
  if (icon) {
    if (type === 'error') {
      icon.setAttribute('class', 'w-4 h-4 text-rose-400 shrink-0');
    } else if (type === 'info') {
      icon.setAttribute('class', 'w-4 h-4 text-sky-400 shrink-0');
    } else {
      icon.setAttribute('class', 'w-4 h-4 text-emerald-400 shrink-0');
    }
  }

  toast.classList.remove('translate-y-20', 'opacity-0');
  toast.classList.add('translate-y-0', 'opacity-100');

  setTimeout(() => {
    toast.classList.add('translate-y-20', 'opacity-0');
    toast.classList.remove('translate-y-0', 'opacity-100');
  }, 4000);
}

// Assign globally to window for BOTH window.worksheet.* and direct function calls
window.goToStep = goToStep;
window.nextStep = nextStep;
window.prevStep = prevStep;
window.toggleMobileStepDrawer = toggleMobileStepDrawer;
window.saveData = saveData;
window.exportToPdf = exportToPdf;
window.submitToServer = submitToServer;
window.openResetModal = openResetModal;
window.closeResetModal = closeResetModal;
window.confirmReset = confirmReset;
window.showToast = showToast;
window.renderReviewSummary = renderReviewSummary;
window.showValidationModal = showValidationModal;
window.closeValidationModal = closeValidationModal;
window.fixFirstMissingField = fixFirstMissingField;
window.jumpToField = jumpToField;
window.getMissingFields = getMissingFields;

window.worksheet = {
  initWorksheet,
  goToStep,
  nextStep,
  prevStep,
  toggleMobileStepDrawer,
  saveData,
  exportToPdf,
  submitToServer,
  openResetModal,
  closeResetModal,
  confirmReset,
  showToast,
  renderReviewSummary,
  showValidationModal,
  closeValidationModal,
  fixFirstMissingField,
  jumpToField,
  getMissingFields
};
