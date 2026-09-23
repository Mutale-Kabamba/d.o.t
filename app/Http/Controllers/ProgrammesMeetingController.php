<?php

namespace App\Http\Controllers;

use App\Models\ActivityEntry;
use App\Models\Project;
use App\Models\ProjectSubmission;
use App\Models\User;
use App\Services\PptxParserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProgrammesMeetingController extends Controller
{
    /**
     * 5 Thematic Pillars Configuration matching organizational standards.
     */
    public static array $slidesConfig = [
        'achievements' => [
            'number' => 1,
            'title' => 'Project Achievements',
            'icon' => 'trophy',
            'points_field' => 'achievements_points',
            'narrative_field' => 'achievements_narrative',
            'items' => [
                'achievements_milestones' => [
                    'title' => 'Key Milestones',
                    'prompt' => 'Major milestones since our last review and how they align with the overall project objectives.',
                    'placeholder' => "• Key milestone reached with direct alignment to project goal\n• 98% of target participants completed foundational training\n• 100+ youth leaders mobilized across community zones",
                ],
                'achievements_impact' => [
                    'title' => 'Impact Evidence',
                    'prompt' => 'What evidence do we have that the interventions on each project are creating meaningful change/impact in the communities we serve.',
                    'placeholder' => "• Concrete qualitative & quantitative evidence of change\n• 88% knowledge retention and measurable community adoption\n• Verified reduction in key target risk indicators",
                ],
                'achievements_stories' => [
                    'title' => 'Success Stories',
                    'prompt' => 'Share examples of positive success - attach a story.',
                    'placeholder' => '• Narrative spotlight: Direct personal testimonial or case study demonstrating individual transformation.',
                ],
            ],
        ],
        'challenges' => [
            'number' => 2,
            'title' => 'Challenges & Risks',
            'icon' => 'shield-alert',
            'points_field' => 'challenges_points',
            'narrative_field' => 'challenges_narrative',
            'items' => [
                'challenges_operational' => [
                    'title' => 'Operational Challenges',
                    'prompt' => 'Challenges faced during implementation of the project activities and how they were addressed.',
                    'placeholder' => '• Logistical bottleneck encountered and adaptive measures deployed to resolve it.',
                ],
                'challenges_resources' => [
                    'title' => 'Resource Gaps',
                    'prompt' => 'Addressing critical gaps across human, financial, and technical resources.',
                    'placeholder' => '• Specific technical, staffing, or material deficit identified with mitigation requisition.',
                ],
                'challenges_risks' => [
                    'title' => 'Risk Monitoring',
                    'prompt' => 'Continuous tracking of identified risks and execution of timely mitigation strategies.',
                    'placeholder' => '• Monitored environmental/safeguarding risks with active mitigation protocols in place.',
                ],
            ],
        ],
        'learning' => [
            'number' => 3,
            'title' => 'Learning and Adaptation',
            'icon' => 'lightbulb',
            'points_field' => 'learning_points',
            'narrative_field' => 'learning_narrative',
            'items' => [
                'learning_lessons' => [
                    'title' => 'Lessons Learned',
                    'prompt' => 'Identifying and documenting key insights gained from project activities to guide future implementation.',
                    'placeholder' => '• Strategic discovery and operational insight to incorporate into subsequent programming cycles.',
                ],
                'learning_feedback' => [
                    'title' => 'Community Feedback',
                    'prompt' => 'Are we actively and systematically incorporating feedback from our key stakeholders and participants?',
                    'placeholder' => '• Direct community and participant feedback synthesized into actionable enhancements.',
                ],
                'learning_innovation' => [
                    'title' => 'Innovation',
                    'prompt' => 'Any innovative approaches we should explore and consider to continuously enhance the overall effectiveness of the project.',
                    'placeholder' => '• Novel pedagogical tool or tech-enabled method piloted to accelerate impact.',
                ],
            ],
        ],
        'mne' => [
            'number' => 4,
            'title' => 'Monitoring & Evaluation',
            'icon' => 'chart-bar',
            'points_field' => 'mne_points',
            'narrative_field' => 'mne_narrative',
            'items' => [
                'mne_performance' => [
                    'title' => 'Performance',
                    'prompt' => 'How are we performing against our plans?',
                    'placeholder' => '• Target vs actual metrics: Tracked 94% achievement against quarterly workplan.',
                ],
                'mne_data_quality' => [
                    'title' => 'Data Quality',
                    'prompt' => 'Data quality check systems to ensure accuracy, completeness, and reliability.',
                    'placeholder' => '• Multi-tiered verification protocol conducted with 100% data audit compliance.',
                ],
                'mne_evaluation_plans' => [
                    'title' => 'Evaluation Plans',
                    'prompt' => 'Any evaluation plan for the projects to measure long-term outcomes and impacts.',
                    'placeholder' => '• End-of-cycle evaluation roadmap and longitudinal outcome assessment scheduled.',
                ],
            ],
        ],
        'collab' => [
            'number' => 5,
            'title' => 'Collaboration and Coordination',
            'icon' => 'users',
            'points_field' => 'collab_points',
            'narrative_field' => 'collab_narrative',
            'items' => [
                'collab_projects' => [
                    'title' => 'Project Collaboration',
                    'prompt' => 'How are we collaborating across projects to maximize alignment and shared resources?',
                    'placeholder' => '• Resource-pooling and joint session delivery with adjacent project teams.',
                ],
                'collab_partnerships' => [
                    'title' => 'Partnerships',
                    'prompt' => 'Are there key opportunities to strengthen partnerships with local organisations and stakeholders?',
                    'placeholder' => '• Formalized collaborative agreement with local civic and community partners.',
                ],
                'collab_cross_learning' => [
                    'title' => 'Cross Learning',
                    'prompt' => 'Share critical knowledge and best practices across all active projects to improve programming.',
                    'placeholder' => '• Best practice dissemination workshop held across cross-functional project leads.',
                ],
            ],
        ],
    ];

    /**
     * Show the Project Brief / Quick Activity Entry Form.
     */
    public function index(): View
    {
        $this->ensureDatabaseReady();

        $user = Auth::user();
        $projects = $user 
            ? Project::active()->forUser($user)->orderBy('name')->get() 
            : Project::active()->orderBy('name')->get();

        return view('programmes.project_brief', [
            'slidesConfig' => self::$slidesConfig,
            'projects' => $projects,
            'defaultPeriod' => 'Quarter 2 April, May, June 2026',
        ]);
    }

    /**
     * Store public/quick submission (compatible with both ActivityEntry & legacy ProjectSubmission).
     */
    public function store(Request $request): JsonResponse
    {
        $this->ensureDatabaseReady();

        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'officer_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'reporting_period' => 'required|string|max:255',

            'achievements_milestones' => 'nullable|string',
            'achievements_impact' => 'nullable|string',
            'achievements_stories' => 'nullable|string',

            'challenges_operational' => 'nullable|string',
            'challenges_resources' => 'nullable|string',
            'challenges_risks' => 'nullable|string',

            'learning_lessons' => 'nullable|string',
            'learning_feedback' => 'nullable|string',
            'learning_innovation' => 'nullable|string',

            'mne_performance' => 'nullable|string',
            'mne_data_quality' => 'nullable|string',
            'mne_evaluation_plans' => 'nullable|string',

            'collab_projects' => 'nullable|string',
            'collab_partnerships' => 'nullable|string',
            'collab_cross_learning' => 'nullable|string',
        ]);

        try {
            // Save to legacy table
            $submission = ProjectSubmission::create($validated);

            // Also create or find Project and create ActivityEntry
            $project = Project::firstOrCreate(
                ['name' => $validated['project_name']],
                ['location' => $validated['location'] ?? null, 'status' => 'active']
            );

            // Combine sub-fields into clean thematic presentation points
            $achievements = array_filter([
                $validated['achievements_milestones'] ?? '',
                $validated['achievements_impact'] ?? '',
                $validated['achievements_stories'] ?? '',
            ]);
            $challenges = array_filter([
                $validated['challenges_operational'] ?? '',
                $validated['challenges_resources'] ?? '',
                $validated['challenges_risks'] ?? '',
            ]);
            $learning = array_filter([
                $validated['learning_lessons'] ?? '',
                $validated['learning_feedback'] ?? '',
                $validated['learning_innovation'] ?? '',
            ]);
            $mne = array_filter([
                $validated['mne_performance'] ?? '',
                $validated['mne_data_quality'] ?? '',
                $validated['mne_evaluation_plans'] ?? '',
            ]);
            $collab = array_filter([
                $validated['collab_projects'] ?? '',
                $validated['collab_partnerships'] ?? '',
                $validated['collab_cross_learning'] ?? '',
            ]);

            ActivityEntry::create([
                'project_id' => $project->id,
                'user_id' => Auth::id(),
                'activity_title' => 'Programmes Review Activity Log',
                'activity_date' => now()->toDateString(),
                'location' => $validated['location'] ?? $project->location,
                'reporting_period' => $validated['reporting_period'],
                'achievements_points' => implode("\n", $achievements),
                'achievements_narrative' => implode("\n\n", $achievements),
                'challenges_points' => implode("\n", $challenges),
                'challenges_narrative' => implode("\n\n", $challenges),
                'learning_points' => implode("\n", $learning),
                'learning_narrative' => implode("\n\n", $learning),
                'mne_points' => implode("\n", $mne),
                'mne_narrative' => implode("\n\n", $mne),
                'collab_points' => implode("\n", $collab),
                'collab_narrative' => implode("\n\n", $collab),
            ]);

            return response()->json([
                'success' => true,
                'token' => $submission->token,
                'message' => 'Project submission saved successfully.',
                'redirect_url' => route('programmes.success', ['token' => $submission->token]),
            ]);
        } catch (\Throwable $e) {
            Log::error('Project submission error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save submission: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Confirmation page with single project slide download.
     */
    public function success(string $token): View
    {
        $submission = ProjectSubmission::where('token', $token)->firstOrFail();

        return view('programmes.success', [
            'submission' => $submission,
            'slidesConfig' => self::$slidesConfig,
        ]);
    }

    /**
     * Supervisor Hub: Unified dashboard with Interval Filters, Project Scoping, and Continuous Activity Logging.
     */
    public function hub(Request $request): View
    {
        $this->ensureDatabaseReady();

        $user = Auth::user();
        $isSuperAdmin = $user && $user->isSuperAdmin();

        // 1. Projects Scoping
        $projectsQuery = Project::query()->with(['users', 'activityEntries']);
        if (!$isSuperAdmin && $user) {
            $projectsQuery->whereHas('users', fn($q) => $q->where('users.id', $user->id));
        }
        $projects = $projectsQuery->orderBy('name')->get();

        // 2. Interval & Project Filtering
        $interval = $request->query('interval', 'all'); // 'all', 'day', 'month', 'quarter', 'year', 'custom'
        $selectedProjectId = $request->query('project_id');
        $search = $request->query('q');

        $activitiesQuery = ActivityEntry::with(['project', 'user'])->latest('activity_date');

        if (!$isSuperAdmin && $user) {
            $activitiesQuery->forUser($user);
        }

        if ($selectedProjectId && $selectedProjectId !== 'all') {
            $activitiesQuery->where('project_id', $selectedProjectId);
        }

        if ($search) {
            $activitiesQuery->where(function ($q) use ($search) {
                $q->where('activity_title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('achievements_points', 'like', "%{$search}%")
                  ->orWhere('challenges_points', 'like', "%{$search}%")
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        // Apply interval filtering
        $filterParams = [
            'date' => $request->query('date', now()->toDateString()),
            'month' => (int) $request->query('month', now()->month),
            'quarter' => (int) $request->query('quarter', ceil(now()->month / 3)),
            'year' => (int) $request->query('year', now()->year),
            'from_date' => $request->query('from_date'),
            'to_date' => $request->query('to_date'),
        ];
        if ($interval !== 'all') {
            $activitiesQuery->filterInterval($interval, $filterParams);
        }

        $activities = $activitiesQuery->paginate(15)->withQueryString();

        // Legacy submissions (for backward compatibility display if needed)
        $submissions = ProjectSubmission::latest()->get();

        // All users for Super Admin management features
        $allUsers = $isSuperAdmin ? User::with('projects')->orderBy('name')->get() : collect();

        // Active Tab (dashboard, activities, projects, staff, matrix)
        $currentTab = $request->query('tab', 'dashboard');

        // 3. Compute High-Level Metrics for Key Metric Cards
        $allActivitiesInScope = ActivityEntry::with(['project', 'user']);
        if (!$isSuperAdmin && $user) {
            $allActivitiesInScope->forUser($user);
        }
        if ($selectedProjectId && $selectedProjectId !== 'all') {
            $allActivitiesInScope->where('project_id', $selectedProjectId);
        }
        if ($interval !== 'all') {
            $allActivitiesInScope->filterInterval($interval, $filterParams);
        }
        $filteredActivities = $allActivitiesInScope->get();

        $metrics = [
            'total_activities' => $filteredActivities->count(),
            'total_projects' => $projects->count(),
            'active_projects' => $projects->where('status', 'active')->count(),
            'total_staff' => $allUsers->count(),
            'total_officers' => $allUsers->where('role', User::ROLE_PROJECT_OFFICER)->count(),
            'total_achievements' => $filteredActivities->sum(fn($a) => count($a->getPoints('achievements_points'))),
            'total_challenges' => $filteredActivities->sum(fn($a) => count($a->getPoints('challenges_points'))),
            'total_learning' => $filteredActivities->sum(fn($a) => count($a->getPoints('learning_points'))),
            'total_mne' => $filteredActivities->sum(fn($a) => count($a->getPoints('mne_points'))),
            'total_collab' => $filteredActivities->sum(fn($a) => count($a->getPoints('collab_points'))),
            'latest_activities' => $filteredActivities->sortByDesc('activity_date')->take(5),
            'project_counts' => $projects->mapWithKeys(fn($p) => [$p->id => $filteredActivities->where('project_id', $p->id)->count()]),
        ];

        return view('programmes.dashboard', [
            'projects' => $projects,
            'activities' => $activities,
            'submissions' => $submissions,
            'slidesConfig' => self::$slidesConfig,
            'interval' => $interval,
            'filterParams' => $filterParams,
            'selectedProjectId' => $selectedProjectId,
            'search' => $search,
            'isSuperAdmin' => $isSuperAdmin,
            'allUsers' => $allUsers,
            'currentUser' => $user,
            'currentTab' => $currentTab,
            'metrics' => $metrics,
        ]);
    }

    /**
     * Show form to log a new continuous activity entry.
     */
    public function createActivity(Request $request): View
    {
        $this->ensureDatabaseReady();

        $user = Auth::user();
        $projects = $user->isSuperAdmin()
            ? Project::active()->orderBy('name')->get()
            : $user->projects()->where('status', 'active')->orderBy('name')->get();

        $preselectedProject = $request->query('project_id') 
            ? $projects->firstWhere('id', $request->query('project_id'))
            : $projects->first();

        return view('programmes.activity_entry_form', [
            'projects' => $projects,
            'preselectedProject' => $preselectedProject,
            'slidesConfig' => self::$slidesConfig,
            'activity' => null,
            'isEdit' => false,
        ]);
    }

    /**
     * Store new continuous activity entry.
     */
    public function storeActivity(Request $request): RedirectResponse
    {
        $this->ensureDatabaseReady();
        $user = Auth::user();

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'activity_title' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'reporting_period' => 'nullable|string|max:255',

            // 1. Project Achievements (3 respective sections)
            'achievements_milestones' => 'nullable|string',
            'achievements_impact' => 'nullable|string',
            'achievements_stories' => 'nullable|string',
            'achievements_points' => 'nullable|string',
            'achievements_narrative' => 'nullable|string',

            // 2. Challenges & Risks (3 respective sections)
            'challenges_operational' => 'nullable|string',
            'challenges_resources' => 'nullable|string',
            'challenges_risks' => 'nullable|string',
            'challenges_points' => 'nullable|string',
            'challenges_narrative' => 'nullable|string',

            // 3. Learning & Adaptation (3 respective sections)
            'learning_lessons' => 'nullable|string',
            'learning_feedback' => 'nullable|string',
            'learning_innovation' => 'nullable|string',
            'learning_points' => 'nullable|string',
            'learning_narrative' => 'nullable|string',

            // 4. Monitoring & Evaluation (3 respective sections)
            'mne_performance' => 'nullable|string',
            'mne_data_quality' => 'nullable|string',
            'mne_evaluation_plans' => 'nullable|string',
            'mne_points' => 'nullable|string',
            'mne_narrative' => 'nullable|string',

            // 5. Collaboration & Coordination (3 respective sections)
            'collab_projects' => 'nullable|string',
            'collab_partnerships' => 'nullable|string',
            'collab_cross_learning' => 'nullable|string',
            'collab_points' => 'nullable|string',
            'collab_narrative' => 'nullable|string',
        ]);

        // Scoping check: non-super-admins can only log for assigned projects
        if (!$user->canAccessProject($validated['project_id'])) {
            abort(403, 'Unauthorized. You are not assigned to this project.');
        }

        $validated['user_id'] = $user->id;

        $entry = ActivityEntry::create($validated);

        return redirect()->route('programmes.hub')->with('success', "Activity '{$entry->activity_title}' logged successfully.");
    }

    /**
     * Show single activity entry narrative and presentation points.
     */
    public function showActivity(string $token): View
    {
        $user = Auth::user();
        $activity = ActivityEntry::with(['project', 'user'])->where('token', $token)->firstOrFail();

        if ($user && !$user->canAccessProject($activity->project_id)) {
            abort(403, 'Unauthorized.');
        }

        return view('programmes.activity_show', [
            'activity' => $activity,
            'slidesConfig' => self::$slidesConfig,
        ]);
    }

    /**
     * Edit activity entry.
     */
    public function editActivity(string $token): View
    {
        $user = Auth::user();
        $activity = ActivityEntry::with('project')->where('token', $token)->firstOrFail();

        if ($user && !$user->canAccessProject($activity->project_id)) {
            abort(403, 'Unauthorized.');
        }

        $projects = $user->isSuperAdmin()
            ? Project::active()->orderBy('name')->get()
            : $user->projects()->where('status', 'active')->orderBy('name')->get();

        return view('programmes.activity_entry_form', [
            'projects' => $projects,
            'preselectedProject' => $activity->project,
            'slidesConfig' => self::$slidesConfig,
            'activity' => $activity,
            'isEdit' => true,
        ]);
    }

    /**
     * Update activity entry.
     */
    public function updateActivity(Request $request, string $token): RedirectResponse
    {
        $user = Auth::user();
        $activity = ActivityEntry::where('token', $token)->firstOrFail();

        if (!$user->canAccessProject($activity->project_id)) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'activity_title' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'reporting_period' => 'nullable|string|max:255',

            // 1. Project Achievements (3 respective sections)
            'achievements_milestones' => 'nullable|string',
            'achievements_impact' => 'nullable|string',
            'achievements_stories' => 'nullable|string',
            'achievements_points' => 'nullable|string',
            'achievements_narrative' => 'nullable|string',

            // 2. Challenges & Risks (3 respective sections)
            'challenges_operational' => 'nullable|string',
            'challenges_resources' => 'nullable|string',
            'challenges_risks' => 'nullable|string',
            'challenges_points' => 'nullable|string',
            'challenges_narrative' => 'nullable|string',

            // 3. Learning & Adaptation (3 respective sections)
            'learning_lessons' => 'nullable|string',
            'learning_feedback' => 'nullable|string',
            'learning_innovation' => 'nullable|string',
            'learning_points' => 'nullable|string',
            'learning_narrative' => 'nullable|string',

            // 4. Monitoring & Evaluation (3 respective sections)
            'mne_performance' => 'nullable|string',
            'mne_data_quality' => 'nullable|string',
            'mne_evaluation_plans' => 'nullable|string',
            'mne_points' => 'nullable|string',
            'mne_narrative' => 'nullable|string',

            // 5. Collaboration & Coordination (3 respective sections)
            'collab_projects' => 'nullable|string',
            'collab_partnerships' => 'nullable|string',
            'collab_cross_learning' => 'nullable|string',
            'collab_points' => 'nullable|string',
            'collab_narrative' => 'nullable|string',
        ]);

        if (!$user->canAccessProject($validated['project_id'])) {
            abort(403, 'Unauthorized project selected.');
        }

        $activity->update($validated);

        return redirect()->route('programmes.hub')->with('success', "Activity '{$activity->activity_title}' updated successfully.");
    }

    /**
     * Delete activity entry.
     */
    public function destroyActivity(string $token): RedirectResponse
    {
        $user = Auth::user();
        $activity = ActivityEntry::where('token', $token)->firstOrFail();

        if (!$user->canAccessProject($activity->project_id)) {
            abort(403, 'Unauthorized.');
        }

        $activity->delete();

        return redirect()->route('programmes.hub')->with('success', 'Activity entry deleted successfully.');
    }

    /**
     * Super Admin: Create new project.
     */
    public function storeProject(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Only Super Admins can create projects.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'code' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'active',
        ]);

        if (!empty($validated['user_ids'])) {
            $project->users()->sync($validated['user_ids']);
        }

        return redirect()->route('programmes.hub')->with('success', "Project '{$project->name}' created successfully.");
    }

    /**
     * Super Admin: Update project details and team assignments.
     */
    public function updateProject(Request $request, Project $project): RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Only Super Admins can edit projects.');
        }

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

        return redirect()->route('programmes.hub')->with('success', "Project '{$project->name}' updated successfully.");
    }

    /**
     * Super Admin: Toggle project status (active/archived).
     */
    public function toggleProjectStatus(Project $project): RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Only Super Admins can archive/restore projects.');
        }

        $newStatus = $project->status === 'active' ? 'archived' : 'active';
        $project->update(['status' => $newStatus]);

        return redirect()->route('programmes.hub')->with('success', "Project '{$project->name}' marked as {$newStatus}.");
    }

    /**
     * Super Admin: Delete project.
     */
    public function destroyProject(Project $project): RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Only Super Admins can delete projects.');
        }

        $name = $project->name;
        $project->delete();

        return redirect()->route('programmes.hub')->with('success', "Project '{$name}' deleted.");
    }

    /**
     * Super Admin: Assign/unassign user to project.
     */
    public function assignUserToProject(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_ids' => 'present|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project = Project::findOrFail($request->input('project_id'));
        $project->users()->sync($request->input('user_ids'));

        return response()->json([
            'success' => true,
            'message' => "Team assigned to '{$project->name}' successfully.",
        ]);
    }

    /**
     * Super Admin: Create new user account with role and project assignments.
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Only Super Admins can create user accounts.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,project_officer,project_assistant',
            'project_ids' => 'nullable|array',
            'project_ids.*' => 'exists:projects,id',
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if (!empty($validated['project_ids'])) {
            $newUser->projects()->sync($validated['project_ids']);
        }

        return redirect()->route('programmes.hub')->with('success', "User account for '{$newUser->name}' ({$newUser->role_label}) created successfully.");
    }

    /**
     * Super Admin: Delete user account.
     */
    public function destroyUser(User $user): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->isSuperAdmin()) {
            abort(403, 'Only Super Admins can delete user accounts.');
        }

        if ($user->id === $currentUser->id) {
            return redirect()->route('programmes.hub')->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('programmes.hub')->with('success', "User account for '{$name}' removed.");
    }

    /**
     * Helper to aggregate presentation data (transposed matrix ready) for Consolidated or Single Project views.
     */
    public function getAggregatedPresentationData(Request $request, ?Project $singleProject = null): array
    {
        $this->ensureDatabaseReady();
        $user = Auth::user();

        // 1. Determine projects to include
        if ($singleProject) {
            $projects = collect([$singleProject]);
        } else {
            $projectQuery = Project::active()->with('users');
            if ($user && !$user->isSuperAdmin()) {
                $projectQuery->forUser($user);
            }
            if ($pId = $request->query('project_id')) {
                if ($pId !== 'all') {
                    $projectQuery->where('id', $pId);
                }
            }
            $projects = $projectQuery->orderBy('name')->get();

            if ($projects->isEmpty()) {
                $this->seedSampleProjectsData();
                $projects = Project::active()->orderBy('name')->get();
            }
        }

        // 2. Determine interval filter
        $interval = $request->query('interval', 'all');
        $filterParams = [
            'date' => $request->query('date', now()->toDateString()),
            'month' => (int) $request->query('month', now()->month),
            'quarter' => (int) $request->query('quarter', ceil(now()->month / 3)),
            'year' => (int) $request->query('year', now()->year),
            'from_date' => $request->query('from_date'),
            'to_date' => $request->query('to_date'),
        ];

        // 3. For each project, fetch its activity entries and aggregate bullet points per theme
        $projectDataList = [];

        foreach ($projects as $project) {
            $entriesQuery = $project->activityEntries();
            if ($interval !== 'all') {
                $entriesQuery->filterInterval($interval, $filterParams);
            }
            $entries = $entriesQuery->get();

            // Structure to hold aggregated bullet points and narrative per theme
            $themePoints = [
                'achievements' => [],
                'challenges' => [],
                'learning' => [],
                'mne' => [],
                'collab' => [],
            ];
            $themeNarratives = [
                'achievements' => [],
                'challenges' => [],
                'learning' => [],
                'mne' => [],
                'collab' => [],
            ];

            foreach ($entries as $entry) {
                foreach (self::$slidesConfig as $themeKey => $themeConfig) {
                    $pField = $themeConfig['points_field'];
                    $nField = $themeConfig['narrative_field'];

                    $pts = $entry->getPoints($pField);
                    if (!empty($pts)) {
                        $themePoints[$themeKey] = array_merge($themePoints[$themeKey], $pts);
                    }
                    if (!empty($entry->{$nField})) {
                        $themeNarratives[$themeKey][] = $entry->{$nField};
                    }
                }
            }

            // Fallback to legacy project_submission if no continuous entries found yet
            if (empty(array_filter($themePoints))) {
                $legacy = ProjectSubmission::where('project_name', $project->name)->first();
                if ($legacy) {
                    foreach (self::$slidesConfig as $themeKey => $themeConfig) {
                        foreach ($themeConfig['items'] as $itemKey => $item) {
                            $pts = $legacy->getPoints($itemKey);
                            if (!empty($pts)) {
                                $themePoints[$themeKey] = array_merge($themePoints[$themeKey], $pts);
                            }
                        }
                    }
                }
            }

            // De-duplicate points preserving order and metrics
            foreach ($themePoints as $themeKey => $pts) {
                $themePoints[$themeKey] = array_values(array_unique($pts));
            }

            $projectDataList[] = [
                'project' => $project,
                'project_name' => $project->name,
                'officer_name' => $project->lead_officer_name,
                'location' => $project->location ?? 'Zambia',
                'theme_points' => $themePoints,
                'theme_narratives' => $themeNarratives,
                'entries_count' => $entries->count(),
            ];
        }

        // Period title for slides
        $periodTitle = match ($interval) {
            'day' => 'Day: ' . Carbon::parse($filterParams['date'])->format('d M Y'),
            'month' => Carbon::create($filterParams['year'], $filterParams['month'], 1)->format('F Y'),
            'quarter' => "Quarter {$filterParams['quarter']} {$filterParams['year']}",
            'year' => "Annual {$filterParams['year']}",
            default => 'Quarter 2 April, May, June 2026',
        };

        return [
            'projects' => $projects,
            'projectDataList' => $projectDataList,
            'slidesConfig' => self::$slidesConfig,
            'periodTitle' => $periodTitle,
            'interval' => $interval,
            'isSingleProject' => (bool) $singleProject,
            'singleProject' => $singleProject,
        ];
    }

    /**
     * Live Fullscreen Web Projector Mode (Transposed Matrix Layout: Columns = Projects, Rows = Thematic Sub-items).
     */
    public function projector(Request $request): View
    {
        $data = $this->getAggregatedPresentationData($request);

        return view('programmes.projector', [
            'projects' => $data['projects'],
            'projectDataList' => $data['projectDataList'],
            'slidesConfig' => $data['slidesConfig'],
            'quarter' => $data['periodTitle'],
            'interval' => $data['interval'],
            'isSingleProject' => false,
        ]);
    }

    /**
     * Live Fullscreen Web Projector Mode for Single Isolated Project.
     */
    public function singleProjector(Request $request, Project $project): View
    {
        $user = Auth::user();
        if ($user && !$user->canAccessProject($project)) {
            abort(403, 'Unauthorized.');
        }

        $data = $this->getAggregatedPresentationData($request, $project);

        return view('programmes.projector', [
            'projects' => $data['projects'],
            'projectDataList' => $data['projectDataList'],
            'slidesConfig' => $data['slidesConfig'],
            'quarter' => $data['periodTitle'],
            'interval' => $data['interval'],
            'isSingleProject' => true,
            'singleProject' => $project,
        ]);
    }

    /**
     * Export Consolidated PowerPoint (.pptx) Presentation with Transposed Matrix Layout.
     */
    public function exportConsolidatedPptx(Request $request): StreamedResponse
    {
        $data = $this->getAggregatedPresentationData($request);
        return $this->buildPptxPresentationStream($data, 'PIFZ_Consolidated_Programmes_Meeting');
    }

    /**
     * Export Isolated Single Project PowerPoint (.pptx) Presentation.
     */
    public function exportSingleProjectPptx(Request $request, Project $project): StreamedResponse
    {
        $user = Auth::user();
        if ($user && !$user->canAccessProject($project)) {
            abort(403, 'Unauthorized.');
        }

        $data = $this->getAggregatedPresentationData($request, $project);
        $slug = Str::slug($project->name . '_Presentation');
        return $this->buildPptxPresentationStream($data, $slug);
    }

    /**
     * Core PPTX Builder with Transposed Matrix ($X \leftrightarrow Y$) Layout.
     */
    protected function buildPptxPresentationStream(array $data, string $filenamePrefix): StreamedResponse
    {
        $ppt = new \PhpOffice\PhpPresentation\PhpPresentation();
        $ppt->getLayout()->setDocumentLayout(\PhpOffice\PhpPresentation\DocumentLayout::LAYOUT_SCREEN_16X9);

        $projectDataList = $data['projectDataList'];
        $slidesConfig = $data['slidesConfig'];
        $periodTitle = $data['periodTitle'];

        // Slide 1: Cover Slide
        $cover = $ppt->getActiveSlide();

        $bar = $cover->createRichTextShape();
        $bar->setOffsetX(0)->setOffsetY(0)->setWidth(15)->setHeight(540);
        $bar->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FF2563EB'));

        if (file_exists(public_path('logos/logo2.png'))) {
            $logoShape = new \PhpOffice\PhpPresentation\Shape\Drawing\File();
            $logoShape->setName('Logo 2')
                      ->setPath(public_path('logos/logo2.png'))
                      ->setHeight(48)
                      ->setOffsetX(50)
                      ->setOffsetY(35);
            $cover->addShape($logoShape);
        }

        $titleShape = $cover->createRichTextShape();
        $titleShape->setOffsetX(50)->setOffsetY(95)->setWidth(860)->setHeight(55);
        $t1 = $titleShape->createTextRun('Play It Forward Zambia');
        $t1->getFont()->setBold(true)->setSize(34)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF0F172A'));

        $subShape = $cover->createRichTextShape();
        $subShape->setOffsetX(50)->setOffsetY(155)->setWidth(860)->setHeight(40);
        $t2 = $subShape->createTextRun($data['isSingleProject'] ? $data['singleProject']->name : 'Programmes Meeting');
        $t2->getFont()->setBold(true)->setSize(22)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF2563EB'));

        $periodShape = $cover->createRichTextShape();
        $periodShape->setOffsetX(50)->setOffsetY(200)->setWidth(860)->setHeight(35);
        $t3 = $periodShape->createTextRun($periodTitle);
        $t3->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF1D4ED8'));

        $projBox = $cover->createRichTextShape();
        $projBox->setOffsetX(50)->setOffsetY(250)->setWidth(840)->setHeight(230);
        $projBox->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FFF8FAFC'));
        $projBox->getBorder()->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFE2E8F0'))->setLineStyle(\PhpOffice\PhpPresentation\Style\Border::LINE_SINGLE);

        $pHeader = $projBox->createTextRun("Compiled Active Projects (" . count($projectDataList) . " Projects):\n\n");
        $pHeader->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF64748B'));

        foreach ($projectDataList as $pData) {
            $pRun = $projBox->createTextRun("• {$pData['project_name']} ({$pData['officer_name']})\n");
            $pRun->getFont()->setSize(11)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF1E293B'));
        }

        $projectColors = ['FF2563EB', 'FF059669', 'FF7C3AED', 'FFD97706', 'FF0891B2', 'FFE11D48'];

        // Slides 2 to 6: 5 Thematic Slides (Transposed Matrix: Columns = Projects, Rows = Thematic Sub-items/Points)
        foreach ($slidesConfig as $slideKey => $slideConfig) {
            $slide = $ppt->createSlide();

            // Left Bar
            $sBar = $slide->createRichTextShape();
            $sBar->setOffsetX(0)->setOffsetY(0)->setWidth(15)->setHeight(540);
            $sBar->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FF2563EB'));

            // Top Right Logo 3
            if (file_exists(public_path('logos/logo3.png'))) {
                $logo3Shape = new \PhpOffice\PhpPresentation\Shape\Drawing\File();
                $logo3Shape->setName('Logo 3')
                           ->setPath(public_path('logos/logo3.png'))
                           ->setHeight(32)
                           ->setOffsetX(800)
                           ->setOffsetY(18);
                $slide->addShape($logo3Shape);
            }

            // Slide Header
            $hShape = $slide->createRichTextShape();
            $hShape->setOffsetX(45)->setOffsetY(18)->setWidth(740)->setHeight(65);
            $metaRun = $hShape->createTextRun("SLIDE {$slideConfig['number']} OF 5 • " . strtoupper($periodTitle) . "\n");
            $metaRun->getFont()->setBold(true)->setSize(9)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF2563EB'));
            $titleRun = $hShape->createTextRun($slideConfig['title']);
            $titleRun->getFont()->setBold(true)->setSize(20)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF0F172A'));

            // Dynamic Transposed Layout:
            // Calculate column coordinates for each Project (Columns = X-axis)
            $projCount = max(1, count($projectDataList));
            $availableWidth = 880;
            $startX = 45;
            $gap = 12;
            $colWidth = ($availableWidth - ($gap * ($projCount - 1))) / $projCount;

            // Project Column Headers (X-Axis: Project Names)
            foreach ($projectDataList as $pIdx => $pData) {
                $pColor = $projectColors[$pIdx % count($projectColors)];
                $curX = $startX + ($pIdx * ($colWidth + $gap));

                $colHeader = $slide->createRichTextShape();
                $colHeader->setOffsetX($curX)->setOffsetY(85)->setWidth($colWidth)->setHeight(45);
                $colHeader->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FFF8FAFC'));
                $colHeader->getBorder()->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFCBD5E1'))->setLineStyle(\PhpOffice\PhpPresentation\Style\Border::LINE_SINGLE);

                $projTitleRun = $colHeader->createTextRun($pData['project_name'] . "\n");
                $projTitleRun->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpPresentation\Style\Color($pColor));

                $officerRun = $colHeader->createTextRun("Lead: " . $pData['officer_name']);
                $officerRun->getFont()->setSize(8.5)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF64748B'));
            }

            // Project Content Cells (Rows = Content / Presentation Points per project)
            $currY = 138;
            $cellHeight = 360;

            foreach ($projectDataList as $pIdx => $pData) {
                $pColor = $projectColors[$pIdx % count($projectColors)];
                $curX = $startX + ($pIdx * ($colWidth + $gap));

                $cell = $slide->createRichTextShape();
                $cell->setOffsetX($curX)->setOffsetY($currY)->setWidth($colWidth)->setHeight($cellHeight);
                $cell->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFFFF'));
                $cell->getBorder()->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFE2E8F0'))->setLineStyle(\PhpOffice\PhpPresentation\Style\Border::LINE_SINGLE);

                $pts = $pData['theme_points'][$slideKey] ?? [];

                if (!empty($pts)) {
                    foreach ($pts as $pt) {
                        // Clean URLs into neat label text while strictly preserving numbers and percentages
                        $cleanPt = ActivityEntry::formatPointText($pt);
                        $ptRun = $cell->createTextRun("• {$cleanPt}\n\n");
                        $ptRun->getFont()->setSize(9.5)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF1E293B'));
                    }
                } else {
                    $emptyRun = $cell->createTextRun("No key presentation points recorded for this period.\n");
                    $emptyRun->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF94A3B8'));
                }
            }
        }

        // Slide 7: Thank You Slide
        $thankSlide = $ppt->createSlide();
        $tBar = $thankSlide->createRichTextShape();
        $tBar->setOffsetX(0)->setOffsetY(0)->setWidth(15)->setHeight(540);
        $tBar->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FF2563EB'));

        if (file_exists(public_path('logos/logo2.png'))) {
            $tLogo = new \PhpOffice\PhpPresentation\Shape\Drawing\File();
            $tLogo->setName('Thank You Logo')
                  ->setPath(public_path('logos/logo2.png'))
                  ->setHeight(60)
                  ->setOffsetX(420)
                  ->setOffsetY(100);
            $thankSlide->addShape($tLogo);
        }

        $tyShape = $thankSlide->createRichTextShape();
        $tyShape->setOffsetX(100)->setOffsetY(180)->setWidth(760)->setHeight(240);

        $tyText = $tyShape->createTextRun("Thank You!\n");
        $tyText->getFont()->setBold(true)->setSize(40)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF0F172A'));

        $tySub = $tyShape->createTextRun("Play It Forward Zambia\n\n");
        $tySub->getFont()->setBold(true)->setSize(22)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF2563EB'));

        $tyQuote = $tyShape->createTextRun("Inspiring and empowering young people and their communities through the power of education, health, and sport.\n\n");
        $tyQuote->getFont()->setSize(14)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF475569'));

        $tyPeriod = $tyShape->createTextRun($periodTitle);
        $tyPeriod->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF1D4ED8'));

        $filename = $filenamePrefix . '_' . date('Ymd_His') . '.pptx';

        $oWriter = \PhpOffice\PhpPresentation\IOFactory::createWriter($ppt, 'PowerPoint2007');

        return response()->streamDownload(function () use ($oWriter) {
            $oWriter->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Export Consolidated PowerPoint-Style Presentation PDF (16:9 Landscape) with Transposed Matrix Layout.
     */
    public function exportConsolidatedPresentation(Request $request): Response
    {
        $data = $this->getAggregatedPresentationData($request);

        $pdf = Pdf::loadView('pdf.consolidated_presentation_pdf', [
            'projects' => $data['projects'],
            'projectDataList' => $data['projectDataList'],
            'slidesConfig' => $data['slidesConfig'],
            'meetingTitle' => 'Play It Forward Zambia',
            'meetingSubtitle' => 'Programmes Meeting',
            'quarter' => $data['periodTitle'],
            'generatedDate' => now()->format('F j, Y'),
            'isSingleProject' => false,
        ])->setPaper('a4', 'landscape')
          ->setOption([
              'isHtml5ParserEnabled' => true,
              'isRemoteEnabled' => true,
              'defaultFont' => 'Helvetica',
          ]);

        $filename = 'PIFZ_Consolidated_Presentation_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Isolated Single Project Presentation PDF (16:9 Landscape).
     */
    public function exportSingleProjectPdf(Request $request, Project $project): Response
    {
        $user = Auth::user();
        if ($user && !$user->canAccessProject($project)) {
            abort(403, 'Unauthorized.');
        }

        $data = $this->getAggregatedPresentationData($request, $project);

        $pdf = Pdf::loadView('pdf.single_project_presentation_pdf', [
            'project' => $project,
            'projectDataList' => $data['projectDataList'],
            'slidesConfig' => $data['slidesConfig'],
            'periodTitle' => $data['periodTitle'],
            'generatedDate' => now()->format('d M Y, H:i'),
        ])->setPaper('a4', 'landscape')
          ->setOption([
              'isHtml5ParserEnabled' => true,
              'isRemoteEnabled' => true,
              'defaultFont' => 'Helvetica',
          ]);

        $filename = Str::slug($project->name . '_Presentation') . '_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Legacy Single PDF export by token.
     */
    public function exportSinglePdfByToken(string $token): Response
    {
        $submission = ProjectSubmission::where('token', $token)->firstOrFail();

        $pdf = Pdf::loadView('pdf.single_project_presentation_pdf', [
            'submission' => $submission,
            'slidesConfig' => self::$slidesConfig,
            'generatedDate' => now()->format('d M Y, H:i'),
        ])->setPaper('a4', 'landscape')
          ->setOption([
              'isHtml5ParserEnabled' => true,
              'isRemoteEnabled' => true,
              'defaultFont' => 'Helvetica',
          ]);

        $filename = Str::slug($submission->project_name . '_Meeting_Slides') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Parse PowerPoint file and return JSON to autofill brief.
     */
    public function parsePptx(Request $request, PptxParserService $parser): JsonResponse
    {
        $request->validate([
            'pptx_file' => 'required|file|max:51200',
        ]);

        try {
            $file = $request->file('pptx_file');
            $parsedProjects = $parser->parse($file->getRealPath());

            if (empty($parsedProjects)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching project slide data could be parsed from the uploaded PowerPoint file.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'project' => $parsedProjects[0],
                'all_projects' => $parsedProjects,
                'message' => 'PowerPoint parsed successfully! Form fields populated.',
            ]);
        } catch (\Throwable $e) {
            Log::error('PPTX Parse Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to parse PowerPoint file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Import PowerPoint presentation into Hub (creates/updates projects & activity entries).
     */
    public function importPptx(Request $request, PptxParserService $parser): RedirectResponse
    {
        $request->validate([
            'pptx_file' => 'required|file|max:51200',
        ]);

        $this->ensureDatabaseReady();

        try {
            $file = $request->file('pptx_file');
            $parsedProjects = $parser->parse($file->getRealPath());

            if (empty($parsedProjects)) {
                return redirect()->route('programmes.hub')->with('error', 'No project slide data could be parsed from the uploaded PowerPoint file.');
            }

            $importedCount = 0;
            foreach ($parsedProjects as $data) {
                if (empty($data['project_name'])) {
                    continue;
                }

                // 1. Create or update Project
                $project = Project::firstOrCreate(
                    ['name' => $data['project_name']],
                    ['status' => 'active']
                );

                // 2. Create Continuous Activity Entry
                ActivityEntry::create([
                    'project_id' => $project->id,
                    'user_id' => Auth::id(),
                    'activity_title' => 'Imported Presentation Deck',
                    'activity_date' => now()->toDateString(),
                    'reporting_period' => $data['reporting_period'] ?? 'Quarter 2 April, May, June 2026',
                    'achievements_points' => implode("\n", array_filter([$data['achievements_milestones'] ?? '', $data['achievements_impact'] ?? '', $data['achievements_stories'] ?? ''])),
                    'challenges_points' => implode("\n", array_filter([$data['challenges_operational'] ?? '', $data['challenges_resources'] ?? '', $data['challenges_risks'] ?? ''])),
                    'learning_points' => implode("\n", array_filter([$data['learning_lessons'] ?? '', $data['learning_feedback'] ?? '', $data['learning_innovation'] ?? ''])),
                    'mne_points' => implode("\n", array_filter([$data['mne_performance'] ?? '', $data['mne_data_quality'] ?? '', $data['mne_evaluation_plans'] ?? ''])),
                    'collab_points' => implode("\n", array_filter([$data['collab_projects'] ?? '', $data['collab_partnerships'] ?? '', $data['collab_cross_learning'] ?? ''])),
                ]);

                // 3. Keep legacy ProjectSubmission updated
                ProjectSubmission::updateOrCreate(
                    ['project_name' => $data['project_name']],
                    $data
                );

                $importedCount++;
            }

            return redirect()->route('programmes.hub')->with('success', "Successfully imported {$importedCount} project presentation(s) from PowerPoint.");
        } catch (\Throwable $e) {
            Log::error('PPTX Import Error: ' . $e->getMessage());
            return redirect()->route('programmes.hub')->with('error', 'Failed to import PowerPoint file: ' . $e->getMessage());
        }
    }

    /**
     * Delete a single legacy submission.
     */
    public function destroy(string $token): RedirectResponse
    {
        $submission = ProjectSubmission::where('token', $token)->firstOrFail();
        $submission->delete();

        return redirect()->route('programmes.hub')->with('success', "Project '{$submission->project_name}' removed.");
    }

    /**
     * Seed sample projects and continuous activity entries for instant testing.
     */
    public function seedSample(): RedirectResponse
    {
        $this->ensureDatabaseReady();
        $this->seedSampleProjectsData();

        return redirect()->route('programmes.hub')->with('success', 'Sample projects and continuous activity logs seeded successfully.');
    }

    /**
     * Ensure database tables and initial user/role configuration exist.
     */
    protected function ensureDatabaseReady(): void
    {
        try {
            if (config('database.default') === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                if ($dbPath && $dbPath !== ':memory:' && !file_exists($dbPath)) {
                    $dbDir = dirname($dbPath);
                    if (!is_dir($dbDir)) {
                        @mkdir($dbDir, 0755, true);
                    }
                    @touch($dbPath);
                }
            }

            // Create users table
            if (!Schema::hasTable('users')) {
                Schema::create('users', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->string('role')->default('project_officer');
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->timestamps();
                });
            }

            // Create projects table
            if (!Schema::hasTable('projects')) {
                Schema::create('projects', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('code')->nullable();
                    $table->text('description')->nullable();
                    $table->string('location')->nullable();
                    $table->string('status')->default('active');
                    $table->timestamps();
                });
            }

            // Create project_user pivot table
            if (!Schema::hasTable('project_user')) {
                Schema::create('project_user', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                    $table->timestamps();
                    $table->unique(['project_id', 'user_id']);
                });
            }

            // Create activity_entries table
            if (!Schema::hasTable('activity_entries')) {
                Schema::create('activity_entries', function (Blueprint $table) {
                    $table->id();
                    $table->uuid('token')->unique();
                    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                    $table->string('activity_title');
                    $table->date('activity_date');
                    $table->string('location')->nullable();
                    $table->string('reporting_period')->nullable();

                    $table->text('achievements_points')->nullable();
                    $table->text('achievements_narrative')->nullable();

                    $table->text('challenges_points')->nullable();
                    $table->text('challenges_narrative')->nullable();

                    $table->text('learning_points')->nullable();
                    $table->text('learning_narrative')->nullable();

                    $table->text('mne_points')->nullable();
                    $table->text('mne_narrative')->nullable();

                    $table->text('collab_points')->nullable();
                    $table->text('collab_narrative')->nullable();

                    $table->timestamps();
                });
            }

            // Ensure Super Admin user exists and has super_admin role
            if (Schema::hasTable('users')) {
                User::whereIn('email', ['admin@dot.org', 'admin@pifzambia.org'])->update(['role' => User::ROLE_SUPER_ADMIN]);
                
                if (User::where('email', 'admin@dot.org')->count() === 0) {
                    User::create([
                        'name' => 'Supervisor (Super Admin)',
                        'email' => 'admin@dot.org',
                        'password' => Hash::make('password'),
                        'role' => User::ROLE_SUPER_ADMIN,
                    ]);
                }

                if (User::where('email', 'admin@pifzambia.org')->count() === 0) {
                    User::create([
                        'name' => 'Super Administrator',
                        'email' => 'admin@pifzambia.org',
                        'password' => Hash::make('password'),
                        'role' => User::ROLE_SUPER_ADMIN,
                    ]);
                }
            }

            // If no projects exist in database, seed core projects
            if (Schema::hasTable('projects') && Project::count() === 0) {
                $this->seedSampleProjectsData();
            }
        } catch (\Throwable $e) {
            Log::warning('Database auto-initialization note: ' . $e->getMessage());
        }
    }

    /**
     * Seed realistic sample projects, officers, assistants, and continuous activity entries.
     */
    protected function seedSampleProjectsData(): void
    {
        // 1. Seed standard role users
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@dot.org'],
            ['name' => 'Supervisor', 'password' => Hash::make('password'), 'role' => User::ROLE_SUPER_ADMIN]
        );

        $officerMwila = User::firstOrCreate(
            ['email' => 'mwila@dot.org'],
            ['name' => 'Mwila Tembo', 'password' => Hash::make('password'), 'role' => User::ROLE_PROJECT_OFFICER]
        );

        $officerFaith = User::firstOrCreate(
            ['email' => 'faith@dot.org'],
            ['name' => 'Faith Musonda', 'password' => Hash::make('password'), 'role' => User::ROLE_PROJECT_OFFICER]
        );

        $officerKelvin = User::firstOrCreate(
            ['email' => 'kelvin@dot.org'],
            ['name' => 'Kelvin Phiri', 'password' => Hash::make('password'), 'role' => User::ROLE_PROJECT_OFFICER]
        );

        $assistantCaristo = User::firstOrCreate(
            ['email' => 'caristo@dot.org'],
            ['name' => 'Caristo Maambo', 'password' => Hash::make('password'), 'role' => User::ROLE_PROJECT_ASSISTANT]
        );

        // 2. Seed Projects and assign users
        $proj1 = Project::firstOrCreate(
            ['name' => 'Football for Health & Life Skills'],
            [
                'code' => 'F4H',
                'location' => 'Livingstone Urban',
                'description' => 'Youth empowerment and health education using structured sports programming.',
                'status' => 'active',
            ]
        );
        $proj1->users()->syncWithoutDetaching([$officerMwila->id, $assistantCaristo->id]);

        $proj2 = Project::firstOrCreate(
            ['name' => 'Girls Empowerment & Mentorship (GEM)'],
            [
                'code' => 'GEM',
                'location' => 'Maramba & Dambwa',
                'description' => 'Safe space circles, menstrual hygiene management, and peer leadership for adolescent girls.',
                'status' => 'active',
            ]
        );
        $proj2->users()->syncWithoutDetaching([$officerFaith->id]);

        $proj3 = Project::firstOrCreate(
            ['name' => 'Youth Leadership & Enterprise Incubator'],
            [
                'code' => 'YLE',
                'location' => 'Zambezi Basin Hub',
                'description' => 'Entrepreneurship incubation, apprenticeship placement, and micro-grant financing for youth.',
                'status' => 'active',
            ]
        );
        $proj3->users()->syncWithoutDetaching([$officerKelvin->id]);

        // 3. Seed continuous activity entries for each project
        $activities = [
            [
                'project_id' => $proj1->id,
                'user_id' => $officerMwila->id,
                'activity_title' => 'Community Health Match Day & Coach Clinic',
                'activity_date' => Carbon::now()->subDays(12)->toDateString(),
                'location' => 'Livingstone Urban Pitch A',
                'reporting_period' => 'Quarter 2 April, May, June 2026',
                'achievements_points' => "• 98% of girls passed foundational health module\n• 100+ youth participants in attendance\n• 1 in 4 coaches completed refresher certification",
                'achievements_narrative' => 'Successfully hosted the weekly life-skills session and certified coaches with 98% attendance compliance.',
                'challenges_points' => "• Unpredictable pitch access during rain\n• Shortage of size 4 footballs",
                'challenges_narrative' => 'Rainfall required moving sessions to partner school halls.',
                'learning_points' => "• Pairing female coaches increased girl enrollment by 40%\n• Introduced 'Fair Play' merit cards",
                'learning_narrative' => 'Co-designing sessions around school timetables doubled girl participation.',
                'mne_points' => "• 95% on track against Q2 milestones\n• Digital attendance verified with 99.2% accuracy",
                'mne_narrative' => 'Bi-weekly M&E spot checks showed zero discrepancy in participant logs.',
                'collab_points' => "• Shared transport logistics with Youth Leadership team\n• MoU signed with District Health Office",
                'collab_narrative' => 'Partnered with local health clinic for free quarterly screenings.',
            ],
            [
                'project_id' => $proj2->id,
                'user_id' => $officerFaith->id,
                'activity_title' => 'Sister Circle Safe Space & Dignity Kit Distribution',
                'activity_date' => Carbon::now()->subDays(6)->toDateString(),
                'location' => 'Maramba Community Hall',
                'reporting_period' => 'Quarter 2 April, May, June 2026',
                'achievements_points' => "• Established 8 safe space circles for 420 girls\n• Delivered 12 menstrual hygiene workshops\n• Distributed 400 dignity kits",
                'achievements_narrative' => 'Dignity kits and mentoring sessions delivered with zero dropouts.',
                'challenges_points' => "• Venue scheduling conflicts with adult evening classes\n• Need additional dignity kit replenishment",
                'challenges_narrative' => 'Community center evening schedule conflicts were resolved by moving to morning slots.',
                'learning_points' => "• Involving mothers in orientations reduced absenteeism\n• Launched 'Sister Circle' peer accountability pairs",
                'learning_narrative' => 'Parental engagement proved vital for adolescent girl consistency.',
                'mne_points' => "• Target reached 105% of quarterly girl goal\n• Bi-weekly register audits completed",
                'mne_narrative' => 'Evaluation shows 76% increase in participant self-efficacy.',
                'collab_points' => "• Partnered with CAMFED for bursary referrals\n• Safeguarding training delivered across all officers",
                'collab_narrative' => 'Cross-programme safeguarding refresher completed for all staff.',
            ],
            [
                'project_id' => $proj3->id,
                'user_id' => $officerKelvin->id,
                'activity_title' => 'Micro-Enterprise Pitch & Grant Disbursement Day',
                'activity_date' => Carbon::now()->subDays(2)->toDateString(),
                'location' => 'Zambezi Hub Training Center',
                'reporting_period' => 'Quarter 2 April, May, June 2026',
                'achievements_points' => "• Graduated 65 youth leaders from business bootcamps\n• Disbursed 18 micro-grants totaling ZMW 45,000\n• 24 youth placed into apprenticeships",
                'achievements_narrative' => 'Youth enterprise cohort pitch day concluded with 18 viable micro-businesses financed.',
                'challenges_points' => "• PACRA registration delays for youth groups\n• High demand for seed grant capital",
                'challenges_narrative' => 'Navigating business licensing delays through Chamber of Commerce linkage.',
                'learning_points' => "• Group-based micro-enterprises showed highest resilience\n• Created WhatsApp peer mastermind network",
                'learning_narrative' => 'Peer accountability groups improved loan repayment by 35%.',
                'mne_points' => "• 100% of planned workshops delivered\n• Monthly cloud financial ledger reconciliation active",
                'mne_narrative' => 'Tracking active cash-flow across all 18 funded youth enterprises.',
                'collab_points' => "• Enterprise alumni supplied tournament sports bibs to Football programme\n• Partnership with Livingstone Chamber of Commerce",
                'collab_narrative' => 'Alumni businesses integrated into organizational procurement supply chain.',
            ],
        ];

        foreach ($activities as $actData) {
            ActivityEntry::firstOrCreate(
                [
                    'project_id' => $actData['project_id'],
                    'activity_title' => $actData['activity_title'],
                    'activity_date' => $actData['activity_date'],
                ],
                $actData
            );
        }
    }
}
