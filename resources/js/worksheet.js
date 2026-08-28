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

// Form fields
const formFields = [
  's1_recruitment_high', 's1_recruitment_challenges',
  's1_grad_high', 's1_grad_challenges',
  's1_bds_high', 's1_bds_challenges',
  's2_ops_comm', 's2_host_criteria', 's2_reporting_fixes',
  's3_pacra_strategy', 's3_market_access',
  's4_household_buyin', 's4_safeguarding_accessible', 's4_safeguarding_details',
  's5_recruitment_walkthroughs', 's5_finance_stipends',
  's6_yl_transition',
  's7_skills_gained', 's7_mindset_shift', 's7_action_taken',
  's8_top_worked', 's8_top_barriers',
  's8_change_one_thing', 's8_one_word', 's8_final_message'
];

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

// Step Switcher
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

  // Prev/Next buttons
  const prevBtn = document.getElementById('prev-btn');
  const mobilePrevBtn = document.getElementById('mobile-prev-btn');
  if (prevBtn) prevBtn.disabled = currentStep === 0;
  if (mobilePrevBtn) mobilePrevBtn.disabled = currentStep === 0;

  const nextBtnText = document.getElementById('next-btn-text');
  const mobileNextBtnText = document.getElementById('mobile-next-btn-text');
  
  let label = 'Next Section';
  if (currentStep === 0) {
    label = 'Get Started';
  } else if (currentStep === TOTAL_STEPS - 2) {
    label = 'Review Summary';
  } else if (currentStep === TOTAL_STEPS - 1) {
    label = 'Download PDF';
  }

  if (nextBtnText) nextBtnText.innerText = label;
  if (mobileNextBtnText) mobileNextBtnText.innerText = label;
}

export function nextStep() {
  if (currentStep === TOTAL_STEPS - 1) {
    exportToPdf();
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

// Dynamic Review Summary
export function renderReviewSummary() {
  const container = document.getElementById('review-container');
  if (!container) return;

  let html = `
    <div class="bg-emerald-50 p-3.5 rounded-xl border border-emerald-200 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <span class="text-xs font-bold text-emerald-900">Anonymous Submission (No identifying profile collected)</span>
      </div>
      <span class="text-[11px] text-emerald-700 font-medium">${new Date().toLocaleDateString()}</span>
    </div>
  `;

  const addSection = (title, items) => {
    let itemsHtml = '';
    items.forEach(item => {
      const val = item.value ? escapeHtml(item.value) : '<span class="text-slate-400 italic">No entry provided</span>';
      itemsHtml += `
        <div class="mt-2 text-xs">
          <span class="font-bold text-slate-700 block text-[11px]">${item.label}:</span>
          <p class="text-slate-600 bg-white p-2 sm:p-2.5 rounded-lg border border-slate-100 mt-0.5 whitespace-pre-wrap leading-relaxed">${val}</p>
        </div>
      `;
    });

    return `
      <div class="bg-slate-100/70 p-3 sm:p-4 rounded-xl border border-slate-200">
        <h4 class="text-xs font-extrabold uppercase text-slate-800 tracking-wide">${title}</h4>
        ${itemsHtml}
      </div>
    `;
  };

  html += addSection("1. Journey Mapping", [
    { label: "Recruitment - Highs", value: document.getElementById('s1_recruitment_high')?.value },
    { label: "Recruitment - Challenges", value: document.getElementById('s1_recruitment_challenges')?.value },
    { label: "Graduation - Highs", value: document.getElementById('s1_grad_high')?.value },
    { label: "Graduation - Challenges", value: document.getElementById('s1_grad_challenges')?.value },
    { label: "BDS - Highs", value: document.getElementById('s1_bds_high')?.value },
    { label: "BDS - Challenges", value: document.getElementById('s1_bds_challenges')?.value }
  ]);

  html += addSection("2. Operations & Placements", [
    { label: "Operational & Communication Hurdles", value: document.getElementById('s2_ops_comm')?.value },
    { label: "Host Org Criteria & Placements", value: document.getElementById('s2_host_criteria')?.value },
    { label: "Standardized Reporting Fixes", value: document.getElementById('s2_reporting_fixes')?.value }
  ]);

  html += addSection("3. PACRA 50% Target & BDS", [
    { label: "Strategy for 50% PACRA Goal", value: document.getElementById('s3_pacra_strategy')?.value },
    { label: "BDS Linkages & Market Access", value: document.getElementById('s3_market_access')?.value }
  ]);

  let safeguardingVal = '';
  const radios = document.getElementsByName('s4_safeguarding_accessible');
  for (const r of radios) { if (r.checked) safeguardingVal = r.value; }

  html += addSection("4. Community & Safeguarding", [
    { label: "Household Buy-In & Retention", value: document.getElementById('s4_household_buyin')?.value },
    { label: "Safeguarding Accessible?", value: safeguardingVal },
    { label: "Specific Safeguarding & Inclusion Protocols", value: document.getElementById('s4_safeguarding_details')?.value }
  ]);

  html += addSection("5. Recruitment Walk-Throughs & Finance", [
    { label: "Market Walk-Through Strategy", value: document.getElementById('s5_recruitment_walkthroughs')?.value },
    { label: "Financial Processes & Stipends", value: document.getElementById('s5_finance_stipends')?.value }
  ]);

  html += addSection("6. Youth Leader Transition", [
    { label: "Barriers & Practical Linkages", value: document.getElementById('s6_yl_transition')?.value }
  ]);

  html += addSection("7. Personal & Mindset Impact", [
    { label: "Skills Gained", value: document.getElementById('s7_skills_gained')?.value },
    { label: "Mindset / Confidence Shift", value: document.getElementById('s7_mindset_shift')?.value },
    { label: "Action Taken in Community", value: document.getElementById('s7_action_taken')?.value }
  ]);

  html += addSection("8. Summary Recommendations", [
    { label: "Top Things That Worked Well", value: document.getElementById('s8_top_worked')?.value },
    { label: "Top Challenges / Barriers", value: document.getElementById('s8_top_barriers')?.value },
    { label: "Change One Thing", value: document.getElementById('s8_change_one_thing')?.value },
    { label: "One Word Feeling", value: document.getElementById('s8_one_word')?.value },
    { label: "Final Message for YSO / DOT", value: document.getElementById('s8_final_message')?.value }
  ]);

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

// 100% Guaranteed High-Performance PDF Generation & Download
export async function exportToPdf() {
  saveData();
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

// Server submission (optional online sync)
export async function submitToServer() {
  saveData();
  const form = document.getElementById('worksheet-form');
  const formData = new FormData(form);
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  showToast('Submitting anonymous responses...', 'info');

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
      showToast('Worksheet successfully submitted!', 'success');
      if (result.redirect_url) {
        window.location.href = result.redirect_url;
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
  if (type === 'error') {
    icon.className = 'w-4 h-4 text-rose-400';
  } else if (type === 'info') {
    icon.className = 'w-4 h-4 text-sky-400';
  } else {
    icon.className = 'w-4 h-4 text-emerald-400';
  }

  toast.classList.remove('translate-y-20', 'opacity-0');
  toast.classList.add('translate-y-0', 'opacity-100');

  setTimeout(() => {
    toast.classList.add('translate-y-20', 'opacity-0');
    toast.classList.remove('translate-y-0', 'opacity-100');
  }, 3500);
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
  renderReviewSummary
};
