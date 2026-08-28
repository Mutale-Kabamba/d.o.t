<?php

namespace App\Http\Controllers;

use App\Models\AnonymousSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class WorksheetController extends Controller
{
    /**
     * Form fields array for defaults
     */
    protected array $fields = [
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

    /**
     * Generate and stream/download a high-quality PDF report directly from form data.
     */
    public function exportPdf(Request $request): Response
    {
        $data = [];
        foreach ($this->fields as $f) {
            $data[$f] = $request->input($f, '');
        }
        $data['date'] = date('Y-m-d');

        $pdf = Pdf::loadView('pdf.worksheet', $data)
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Helvetica',
            ]);

        $filename = 'Cohort1_Anonymous_Reflection_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download PDF by stored submission token.
     */
    public function exportPdfByToken(string $token): Response
    {
        $submission = AnonymousSubmission::where('token', $token)->firstOrFail();
        
        $data = [];
        foreach ($this->fields as $f) {
            $data[$f] = $submission->{$f} ?? '';
        }
        $data['date'] = $submission->created_at->format('Y-m-d');

        $pdf = Pdf::loadView('pdf.worksheet', $data)
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Helvetica',
            ]);

        $filename = 'Cohort1_Anonymous_Reflection_' . $submission->token . '.pdf';

        return $pdf->download($filename);
    }
}
