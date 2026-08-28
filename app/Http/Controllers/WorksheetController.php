<?php

namespace App\Http\Controllers;

use App\Models\AnonymousSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorksheetController extends Controller
{
    /**
     * Show the main interactive anonymous worksheet application.
     */
    public function index(): View
    {
        return view('worksheet.index');
    }

    /**
     * Store an anonymous submission securely in the database.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            's1_recruitment_high' => 'nullable|string',
            's1_recruitment_challenges' => 'nullable|string',
            's1_grad_high' => 'nullable|string',
            's1_grad_challenges' => 'nullable|string',
            's1_bds_high' => 'nullable|string',
            's1_bds_challenges' => 'nullable|string',
            's2_ops_comm' => 'nullable|string',
            's2_host_criteria' => 'nullable|string',
            's2_reporting_fixes' => 'nullable|string',
            's3_pacra_strategy' => 'nullable|string',
            's3_market_access' => 'nullable|string',
            's4_household_buyin' => 'nullable|string',
            's4_safeguarding_accessible' => 'nullable|string',
            's4_safeguarding_details' => 'nullable|string',
            's5_recruitment_walkthroughs' => 'nullable|string',
            's5_finance_stipends' => 'nullable|string',
            's6_yl_transition' => 'nullable|string',
            's7_skills_gained' => 'nullable|string',
            's7_mindset_shift' => 'nullable|string',
            's7_action_taken' => 'nullable|string',
            's8_top_worked' => 'nullable|string',
            's8_top_barriers' => 'nullable|string',
            's8_change_one_thing' => 'nullable|string',
            's8_one_word' => 'nullable|string|max:100',
            's8_final_message' => 'nullable|string',
        ]);

        $submission = AnonymousSubmission::create($validated);

        return response()->json([
            'success' => true,
            'token' => $submission->token,
            'message' => 'Anonymous worksheet successfully recorded.',
            'redirect_url' => route('worksheet.success', ['token' => $submission->token]),
        ]);
    }

    /**
     * Show confirmation for the submitted anonymous worksheet.
     */
    public function success(string $token): View
    {
        $submission = AnonymousSubmission::where('token', $token)->firstOrFail();

        return view('worksheet.success', [
            'submission' => $submission,
        ]);
    }
}
