@extends('layouts.app')

@section('title', 'Play It Forward Zambia - Programmes Meeting Brief')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 antialiased font-sans">
    <!-- Top Fixed Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-blue-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-xs">
                    P
                </div>
                <div class="flex items-center gap-2.5 truncate">
                    <span class="text-base font-bold text-slate-900 truncate">Play It Forward Zambia</span>
                    <span class="hidden sm:inline-block text-[11px] font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full shrink-0">
                        Q2 (Apr - Jun 2026)
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <span id="save-status" class="inline-flex items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-md font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="hidden sm:inline">Auto-saved</span>
                </span>

                <a href="{{ route('programmes.hub') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition shadow-xs">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>Supervisor Hub</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <!-- Page Title & Instructions -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Programmes Meeting — Project Brief</h1>
            <p class="text-sm text-slate-600 mt-1">
                Enter your project's key presentation points below. All submissions will be compiled into the supervisor's master slide deck for the meeting.
            </p>
        </div>

        <!-- Submission Form -->
        <form id="project-brief-form" class="space-y-8">
            @csrf

            <!-- Section 0: Project Details -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 shadow-xs">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Project Information
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="project_name" class="block text-xs font-semibold text-slate-700 mb-1">
                            Project Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="project_name" name="project_name" required placeholder="e.g. Football for Health" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                    </div>
                    <div>
                        <label for="officer_name" class="block text-xs font-semibold text-slate-700 mb-1">
                            Project Officer / Lead <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="officer_name" name="officer_name" required placeholder="e.g. Mwila Tembo" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                    </div>
                    <div>
                        <label for="reporting_period" class="block text-xs font-semibold text-slate-700 mb-1">
                            Reporting Period
                        </label>
                        <input type="text" id="reporting_period" name="reporting_period" value="{{ $defaultPeriod }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 font-medium cursor-default">
                    </div>
                </div>
            </div>

            <!-- 5 Slide Sections -->
            @foreach($slidesConfig as $slideKey => $slide)
            <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 shadow-xs">
                <!-- Section Header -->
                <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xs font-bold px-2 py-0.5 bg-blue-600 text-white rounded-md">
                            Slide {{ $slide['number'] }}
                        </span>
                        <h3 class="text-base font-bold text-slate-900">{{ $slide['title'] }}</h3>
                    </div>
                    <span class="text-xs text-slate-400">3 items</span>
                </div>

                <!-- 3 Items Column Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($slide['items'] as $itemKey => $item)
                    <div class="bg-slate-50/70 border border-slate-200 rounded-lg p-4 flex flex-col justify-between">
                        <div>
                            <!-- Blue indicator bar from slide template -->
                            <div class="w-7 h-1 bg-blue-600 rounded-full mb-2.5"></div>
                            <h4 class="text-sm font-bold text-slate-900 mb-1">{{ $item['title'] }}</h4>
                            <p class="text-xs text-slate-500 mb-3 leading-relaxed bg-white p-2 rounded border border-slate-200/80">
                                {{ $item['prompt'] }}
                            </p>
                        </div>
                        <div>
                            <textarea id="{{ $itemKey }}" name="{{ $itemKey }}" rows="4" placeholder="• {{ $item['placeholder'] }}" class="bullet-textarea w-full bg-white border border-slate-300 rounded-md p-2.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition resize-y leading-relaxed font-sans"></textarea>
                            <div class="flex items-center justify-between mt-1.5 text-[11px] text-slate-500">
                                <span class="flex items-center gap-1 text-slate-400">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-500"></span> Auto-bulleted on Enter
                                </span>
                                <button type="button" onclick="formatBullets('{{ $itemKey }}')" class="text-blue-600 hover:text-blue-700 font-semibold cursor-pointer">
                                    + Add bullet
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Action Bar Footer -->
            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500 text-center sm:text-left">
                    Entries will be compiled and displayed side-by-side in the master presentation deck.
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <button type="button" id="reset-draft-btn" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition cursor-pointer">
                        Clear Draft
                    </button>
                    <button type="submit" id="submit-btn" style="background-color: #2563eb !important; color: #ffffff !important;" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-xs font-bold rounded-lg shadow-sm hover:opacity-90 transition cursor-pointer">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Submit Project Brief</span>
                    </button>
                </div>
            </div>
        </form>
    </main>
</div>

@push('scripts')
<script>
const STORAGE_KEY = 'PIFZ_PROJECT_BRIEF_DRAFT_Q2_2026';
const form = document.getElementById('project-brief-form');
const saveStatus = document.getElementById('save-status');

// Helper to manually add bullet point
function formatBullets(textareaId) {
    const el = document.getElementById(textareaId);
    if (!el) return;
    const val = el.value.trim();
    if (val.length === 0) {
        el.value = '• ';
    } else {
        el.value = val + '\n• ';
    }
    el.focus();
    el.selectionStart = el.selectionEnd = el.value.length;
    saveDraft();
}

// Auto-bulleting logic for all item textareas
document.querySelectorAll('.bullet-textarea').forEach(textarea => {
    // On focus: auto-insert bullet if completely empty
    textarea.addEventListener('focus', () => {
        if (!textarea.value.trim()) {
            textarea.value = '• ';
            textarea.setSelectionRange(2, 2);
        }
    });

    // On blur: clear if only empty bullet was left
    textarea.addEventListener('blur', () => {
        if (textarea.value.trim() === '•' || textarea.value.trim() === '') {
            textarea.value = '';
            saveDraft();
        }
    });

    // On keydown: handle Enter and Backspace
    textarea.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const val = textarea.value;
            const before = val.substring(0, start);
            const after = val.substring(end);
            
            // Check if current line is just an empty bullet "• " or "•"
            const lines = before.split('\n');
            const currentLine = lines[lines.length - 1];
            
            if (currentLine.trim() === '•') {
                // User pressed enter on an empty bullet line -> clean up that bullet
                const lastBulletIndex = before.lastIndexOf('•');
                const newBefore = before.substring(0, lastBulletIndex);
                textarea.value = newBefore + after;
                textarea.selectionStart = textarea.selectionEnd = newBefore.length;
            } else {
                // Add a new bullet on the new line
                textarea.value = before + '\n• ' + after;
                textarea.selectionStart = textarea.selectionEnd = start + 3;
            }
            textarea.dispatchEvent(new Event('input'));
        } else if (e.key === 'Backspace') {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            if (start === end) {
                const val = textarea.value;
                const before = val.substring(0, start);
                // If the user backspaces right after "• " (at index 2 of line or start)
                if (before.endsWith('• ')) {
                    e.preventDefault();
                    const newBefore = before.slice(0, -2);
                    textarea.value = newBefore + val.substring(start);
                    textarea.selectionStart = textarea.selectionEnd = newBefore.length;
                    textarea.dispatchEvent(new Event('input'));
                }
            }
        }
    });

    // On input: if user started typing on an empty field that somehow missed the leading bullet
    textarea.addEventListener('input', () => {
        const val = textarea.value;
        if (val.length > 0 && !val.startsWith('• ') && !val.startsWith('•')) {
            textarea.value = '• ' + val;
            textarea.selectionStart = textarea.selectionEnd = textarea.value.length;
        }
    });
});

// Auto-save form to localStorage
function saveDraft() {
    const formData = new FormData(form);
    const data = {};
    for (let [k, v] of formData.entries()) {
        if (k !== '_token') data[k] = v;
    }
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        saveStatus.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span><span class="hidden sm:inline">Auto-saved</span>';
        saveStatus.className = 'inline-flex items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-md font-medium';
    } catch (e) {
        console.error('Save failed', e);
    }
}

// Load draft from localStorage
function loadDraft() {
    try {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (!saved) return;
        const data = JSON.parse(saved);
        for (let k in data) {
            const el = form.querySelector(`[name="${k}"]`);
            if (el && data[k]) {
                el.value = data[k];
            }
        }
    } catch (e) {
        console.error('Load draft error', e);
    }
}

// Clear draft button
document.getElementById('reset-draft-btn')?.addEventListener('click', () => {
    if (confirm('Are you sure you want to clear this draft?')) {
        localStorage.removeItem(STORAGE_KEY);
        form.reset();
        saveDraft();
    }
});

// Event listeners for auto-saving
form.querySelectorAll('input, textarea').forEach(input => {
    input.addEventListener('input', () => {
        saveStatus.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span><span class="hidden sm:inline">Saving...</span>';
        saveStatus.className = 'inline-flex items-center gap-1.5 text-xs text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-md font-medium';
        clearTimeout(window._saveTimer);
        window._saveTimer = setTimeout(saveDraft, 500);
    });
});

// Submit handler
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const submitBtn = document.getElementById('submit-btn');
    const origHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Submitting...</span>
    `;

    try {
        const formData = new FormData(form);
        const response = await fetch("{{ route('programmes.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const res = await response.json();

        if (response.ok && res.success) {
            localStorage.removeItem(STORAGE_KEY);
            window.location.href = res.redirect_url;
        } else {
            alert(res.message || 'Validation failed. Please check all required fields.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = origHtml;
        }
    } catch (err) {
        console.error(err);
        alert('An error occurred during submission. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = origHtml;
    }
});

document.addEventListener('DOMContentLoaded', loadDraft);
</script>
@endpush
@endsection
