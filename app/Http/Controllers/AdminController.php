<?php

namespace App\Http\Controllers;

use App\Models\AnonymousSubmission;
use App\Models\Project;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
     * Export single submission to PDF.
     */
    public function exportSinglePdf(string $token): Response
    {
        $submission = AnonymousSubmission::where('token', $token)->firstOrFail();

        $data = $submission->toArray();
        $data['token'] = $submission->token;
        $data['date'] = $submission->created_at->format('Y-m-d');

        $pdf = Pdf::loadView('pdf.worksheet', $data)
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Helvetica',
            ]);

        return $pdf->download("DOT_Submission_{$token}.pdf");
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

    /* =========================================================================
       STAFF & PERSONNEL CRUD (ADMIN)
       ========================================================================= */

    /**
     * Display all staff and personnel directory with filters, search, and KPI analytics.
     */
    public function staffIndex(Request $request): View
    {
        $search = $request->query('q');
        $role = $request->query('role');
        $projectId = $request->query('project_id');

        $query = User::with(['projects', 'activityEntries'])->latest();

        if ($search) {
            $query->search($search);
        }

        if ($role && $role !== 'all') {
            $query->role($role);
        }

        if ($projectId && $projectId !== 'all') {
            $query->whereHas('projects', function ($q) use ($projectId) {
                $q->where('projects.id', $projectId);
            });
        }

        $staffMembers = $query->paginate(15)->withQueryString();

        // Statistical Analytics
        $totalStaff = User::count();
        $totalOfficers = User::where('role', User::ROLE_PROJECT_OFFICER)->count();
        $totalAssistants = User::where('role', User::ROLE_PROJECT_ASSISTANT)->count();
        $totalAdmins = User::where('role', User::ROLE_SUPER_ADMIN)->count();
        $unassignedStaff = User::whereDoesntHave('projects')->where('role', '!=', User::ROLE_SUPER_ADMIN)->count();

        $allProjects = Project::active()->orderBy('name')->get();

        return view('admin.staff.index', [
            'staffMembers' => $staffMembers,
            'totalStaff' => $totalStaff,
            'totalOfficers' => $totalOfficers,
            'totalAssistants' => $totalAssistants,
            'totalAdmins' => $totalAdmins,
            'unassignedStaff' => $unassignedStaff,
            'allProjects' => $allProjects,
            'search' => $search,
            'selectedRole' => $role ?? 'all',
            'selectedProjectId' => $projectId ?? 'all',
        ]);
    }

    /**
     * Provision and store a new staff account with assigned projects.
     */
    public function staffStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,project_officer,project_assistant',
            'project_ids' => 'nullable|array',
            'project_ids.*' => 'exists:projects,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if (!empty($validated['project_ids'])) {
            $user->projects()->sync($validated['project_ids']);
        }

        return redirect()->route('admin.staff.index')->with('success', "Staff account for '{$user->name}' ({$user->role_label}) created successfully.");
    }

    /**
     * View detailed staff profile, assigned projects, and logged activities.
     */
    public function staffShow(Request $request, User $user)
    {
        $user->load(['projects', 'activityEntries.project' => function ($q) {
            $q->latest('activity_date');
        }]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_label' => $user->role_label,
                'is_super_admin' => $user->isSuperAdmin(),
                'project_ids' => $user->projects->pluck('id'),
                'projects' => $user->projects->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'code' => $p->code, 'status' => $p->status]),
                'activity_count' => $user->activityEntries->count(),
                'created_at' => $user->created_at->format('M d, Y'),
            ]);
        }

        return view('admin.staff.show', [
            'staff' => $user,
        ]);
    }

    /**
     * Update an existing staff member's credentials, role, or project assignments.
     */
    public function staffUpdate(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:super_admin,project_officer,project_assistant',
            'project_ids' => 'nullable|array',
            'project_ids.*' => 'exists:projects,id',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->projects()->sync($validated['project_ids'] ?? []);

        return redirect()->route('admin.staff.index')->with('success', "Staff account for '{$user->name}' updated successfully.");
    }

    /**
     * Delete a staff account with safety checks.
     */
    public function staffDestroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()->back(fallback: route('admin.staff.index'))->with('error', 'You cannot delete your own active administrator account.');
        }

        $name = $user->name;
        $user->projects()->detach();
        $user->delete();

        return redirect()->back(fallback: route('admin.staff.index'))->with('success', "Staff account for '{$name}' deleted successfully.");
    }

    /* =========================================================================
       TEAMS & PROJECTS CRUD (ADMIN)
       ========================================================================= */

    /**
     * Display all teams & project initiatives with member assignments.
     */
    public function teamsIndex(Request $request): View
    {
        $search = $request->query('q');
        $status = $request->query('status');

        $query = Project::with(['users', 'officers', 'assistants', 'activityEntries'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $teams = $query->paginate(12)->withQueryString();

        // Statistics
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'active')->count();
        $archivedProjects = Project::where('status', 'archived')->count();
        $allStaff = User::orderBy('name')->get();

        return view('admin.teams.index', [
            'teams' => $teams,
            'totalProjects' => $totalProjects,
            'activeProjects' => $activeProjects,
            'archivedProjects' => $archivedProjects,
            'allStaff' => $allStaff,
            'search' => $search,
            'selectedStatus' => $status ?? 'all',
        ]);
    }

    /**
     * Create a new team / project initiative.
     */
    public function teamStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'code' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,archived',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        if (!empty($validated['user_ids'])) {
            $project->users()->sync($validated['user_ids']);
        }

        return redirect()->back(fallback: route('admin.teams.index'))->with('success', "Team/Project '{$project->name}' created successfully.");
    }

    /**
     * Update an existing team / project initiative.
     */
    public function teamUpdate(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'code' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,archived',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project->update([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        $project->users()->sync($validated['user_ids'] ?? []);

        return redirect()->back(fallback: route('admin.teams.index'))->with('success', "Team/Project '{$project->name}' updated successfully.");
    }

    /**
     * Toggle status between active and archived.
     */
    public function teamToggleStatus(Project $project): RedirectResponse
    {
        $newStatus = $project->status === 'active' ? 'archived' : 'active';
        $project->update(['status' => $newStatus]);

        return redirect()->back(fallback: route('admin.teams.index'))->with('success', "Team/Project '{$project->name}' marked as {$newStatus}.");
    }

    /**
     * Delete a team / project initiative.
     */
    public function teamDestroy(Project $project): RedirectResponse
    {
        $name = $project->name;
        $project->users()->detach();
        $project->delete();

        return redirect()->back(fallback: route('admin.teams.index'))->with('success', "Team/Project '{$name}' deleted successfully.");
    }
}

