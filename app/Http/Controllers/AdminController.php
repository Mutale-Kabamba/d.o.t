<?php

namespace App\Http\Controllers;

use App\Models\AnonymousSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Display all anonymous submissions with analytics and search.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $query = AnonymousSubmission::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('token', 'like', "%{$search}%")
                  ->orWhere('s8_one_word', 'like', "%{$search}%")
                  ->orWhere('s1_recruitment_high', 'like', "%{$search}%")
                  ->orWhere('s1_recruitment_challenges', 'like', "%{$search}%")
                  ->orWhere('s2_ops_comm', 'like', "%{$search}%")
                  ->orWhere('s3_pacra_strategy', 'like', "%{$search}%")
                  ->orWhere('s4_household_buyin', 'like', "%{$search}%")
                  ->orWhere('s5_finance_stipends', 'like', "%{$search}%")
                  ->orWhere('s6_yl_transition', 'like', "%{$search}%")
                  ->orWhere('s7_skills_gained', 'like', "%{$search}%")
                  ->orWhere('s8_change_one_thing', 'like', "%{$search}%");
            });
        }

        $submissions = $query->paginate(15)->withQueryString();

        // Analytics
        $totalCount = AnonymousSubmission::count();
        $safeguardingClear = AnonymousSubmission::where('s4_safeguarding_accessible', 'like', '%Yes%')->count();
        $safeguardingNeedsFix = AnonymousSubmission::where('s4_safeguarding_accessible', 'like', '%Partially%')->count();
        $safeguardingUnclear = AnonymousSubmission::where('s4_safeguarding_accessible', 'like', '%No%')->count();

        $recentWords = AnonymousSubmission::whereNotNull('s8_one_word')
            ->where('s8_one_word', '!=', '')
            ->latest()
            ->limit(10)
            ->pluck('s8_one_word')
            ->filter();

        return view('admin.index', [
            'submissions' => $submissions,
            'totalCount' => $totalCount,
            'safeguardingClear' => $safeguardingClear,
            'safeguardingNeedsFix' => $safeguardingNeedsFix,
            'safeguardingUnclear' => $safeguardingUnclear,
            'recentWords' => $recentWords,
            'search' => $search,
        ]);
    }

    /**
     * Show single submission details.
     */
    public function show(string $token): View
    {
        $submission = AnonymousSubmission::where('token', $token)->firstOrFail();

        return view('admin.show', [
            'submission' => $submission,
        ]);
    }

    /**
     * Export Full Master PDF Report with all submissions sectioned per submission.
     */
    public function exportMasterPdf(): Response
    {
        $submissions = AnonymousSubmission::oldest()->get();

        $pdf = Pdf::loadView('pdf.master_report', [
            'submissions' => $submissions,
        ])->setPaper('a4', 'portrait')
          ->setOption([
              'isHtml5ParserEnabled' => true,
              'isRemoteEnabled' => true,
              'defaultFont' => 'Helvetica',
          ]);

        $filename = 'DOT_Cohort1_Full_Master_Report_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export all submissions to CSV.
     */
    public function exportCsv(): StreamedResponse
    {
        $fileName = 'DOT_Anonymous_Submissions_' . date('Y-m-d_His') . '.csv';

        $columns = [
            'Receipt Token',
            'Submission Date',
            '1A Recruitment Highs',
            '1A Recruitment Challenges',
            '1B Grad Highs',
            '1B Grad Challenges',
            '1C BDS Highs',
            '1C BDS Challenges',
            '2 Ops & Comms',
            '2 Host Criteria',
            '2 Reporting Fixes',
            '3 PACRA Strategy',
            '3 BDS Linkages',
            '4 Household Buy-In',
            '4 Safeguarding Accessible',
            '4 Safeguarding Details',
            '5 Recruitment Walkthroughs',
            '5 Finance Stipends',
            '6 YL Transition',
            '7 Skills Gained',
            '7 Mindset Shift',
            '7 Community Action',
            '8 Top Worked',
            '8 Top Barriers',
            '8 Change One Thing',
            '8 One Word Feeling',
            '8 Final Message'
        ];

        return response()->streamDownload(function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            AnonymousSubmission::latest()->chunk(100, function ($submissions) use ($file) {
                foreach ($submissions as $s) {
                    fputcsv($file, [
                        $s->token,
                        $s->created_at->format('Y-m-d H:i:s'),
                        $s->s1_recruitment_high,
                        $s->s1_recruitment_challenges,
                        $s->s1_grad_high,
                        $s->s1_grad_challenges,
                        $s->s1_bds_high,
                        $s->s1_bds_challenges,
                        $s->s2_ops_comm,
                        $s->s2_host_criteria,
                        $s->s2_reporting_fixes,
                        $s->s3_pacra_strategy,
                        $s->s3_market_access,
                        $s->s4_household_buyin,
                        $s->s4_safeguarding_accessible,
                        $s->s4_safeguarding_details,
                        $s->s5_recruitment_walkthroughs,
                        $s->s5_finance_stipends,
                        $s->s6_yl_transition,
                        $s->s7_skills_gained,
                        $s->s7_mindset_shift,
                        $s->s7_action_taken,
                        $s->s8_top_worked,
                        $s->s8_top_barriers,
                        $s->s8_change_one_thing,
                        $s->s8_one_word,
                        $s->s8_final_message,
                    ]);
                }
            });

            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Delete a single submission (e.g. test entry).
     */
    public function destroy(string $token): RedirectResponse
    {
        $submission = AnonymousSubmission::where('token', $token)->firstOrFail();
        $submission->delete();

        return redirect()->route('admin.submissions.index')->with('success', 'Submission deleted successfully.');
    }
}
