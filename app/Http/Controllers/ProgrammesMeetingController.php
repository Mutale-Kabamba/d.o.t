<?php

namespace App\Http\Controllers;

use App\Models\ProjectSubmission;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProgrammesMeetingController extends Controller
{
    /**
     * Slide definitions and item metadata matching the template
     */
    public static array $slidesConfig = [
        'achievements' => [
            'number' => 1,
            'title' => 'Project Achievements',
            'icon' => 'trophy',
            'items' => [
                'achievements_milestones' => [
                    'title' => 'Key Milestones',
                    'prompt' => 'Major milestones since our last review and how they align with the overall project objectives.',
                    'placeholder' => '• Reached 1,200 youth participants in weekly sessions\n• Completed mid-term coach refresher trainings\n• Successfully hosted community tournament in Livingstone',
                ],
                'achievements_impact' => [
                    'title' => 'Impact Evidence',
                    'prompt' => 'What evidence do we have that the interventions on each project are creating meaningful change/impact in the communities we serve.',
                    'placeholder' => '• 84% of surveyed participants demonstrated improved life-skills confidence\n• 30% reduction in school absenteeism among active team members',
                ],
                'achievements_stories' => [
                    'title' => 'Success Stories',
                    'prompt' => 'Share examples of positive success - attach a story.',
                    'placeholder' => '• Story: Chileshe (age 17) transitioned from participant to assistant coach and secured a scholarship.',
                ],
            ],
        ],
        'challenges' => [
            'number' => 2,
            'title' => 'Challenges & Risks',
            'icon' => 'shield-alert',
            'items' => [
                'challenges_operational' => [
                    'title' => 'Operational Challenges',
                    'prompt' => 'Challenges faced during implementation of the project activities and how they were addressed.',
                    'placeholder' => '• Severe rainfall disrupted outdoor pitch access; adapted by using partner school halls.',
                ],
                'challenges_resources' => [
                    'title' => 'Resource Gaps',
                    'prompt' => 'Addressing critical gaps across human, financial, and technical resources.',
                    'placeholder' => '• Shortage of first aid kits and training balls; requisition submitted to operations.',
                ],
                'challenges_risks' => [
                    'title' => 'Risk Monitoring',
                    'prompt' => 'Continuous tracking of identified risks and execution of timely mitigation strategies.',
                    'placeholder' => '• Monitored heat index guidelines during peak afternoon sessions with added hydration points.',
                ],
            ],
        ],
        'learning' => [
            'number' => 3,
            'title' => 'Learning and Adaptation',
            'icon' => 'lightbulb',
            'items' => [
                'learning_lessons' => [
                    'title' => 'Lessons Learned',
                    'prompt' => 'Identifying and documenting key insights gained from project activities to guide future implementation.',
                    'placeholder' => '• Co-designing session schedules with school timetables doubled girl-child attendance.',
                ],
                'learning_feedback' => [
                    'title' => 'Community Feedback',
                    'prompt' => 'Are we actively and systematically incorporating feedback from our key stakeholders and participants?',
                    'placeholder' => '• Monthly parent focus groups requested additional weekend financial literacy workshops.',
                ],
                'learning_innovation' => [
                    'title' => 'Innovation',
                    'prompt' => 'Any innovative approaches we should explore and consider to continuously enhance the overall effectiveness of the project.',
                    'placeholder' => '• Piloting digital attendance tracking via mobile tablet app to replace paper registers.',
                ],
            ],
        ],
        'mne' => [
            'number' => 4,
            'title' => 'Monitoring & Evaluation',
            'icon' => 'chart-bar',
            'items' => [
                'mne_performance' => [
                    'title' => 'Performance',
                    'prompt' => 'How are we performing against our plans?',
                    'placeholder' => '• Currently 92% on track against Q2 activity milestones and participant enrollment targets.',
                ],
                'mne_data_quality' => [
                    'title' => 'Data Quality',
                    'prompt' => 'Data quality check systems to ensure accuracy, completeness, and reliability.',
                    'placeholder' => '• Conducted bi-weekly data verification spot checks on 100% of field registers.',
                ],
                'mne_evaluation_plans' => [
                    'title' => 'Evaluation Plans',
                    'prompt' => 'Any evaluation plan for the projects to measure long-term outcomes and impacts.',
                    'placeholder' => '• Scheduled endline participant survey and stakeholder focus group for early Q3.',
                ],
            ],
        ],
        'collab' => [
            'number' => 5,
            'title' => 'Collaboration and Coordination',
            'icon' => 'users',
            'items' => [
                'collab_projects' => [
                    'title' => 'Project Collaboration',
                    'prompt' => 'How are we collaborating across projects to maximize alignment and shared resources?',
                    'placeholder' => '• Shared transport logistics and pitch equipment with the Youth Leadership programme.',
                ],
                'collab_partnerships' => [
                    'title' => 'Partnerships',
                    'prompt' => 'Are there key opportunities to strengthen partnerships with local organisations and stakeholders?',
                    'placeholder' => '• In discussions with local health clinic for free quarterly health screening during tournaments.',
                ],
                'collab_cross_learning' => [
                    'title' => 'Cross Learning',
                    'prompt' => 'Share critical knowledge and best practices across all active projects to improve programming.',
                    'placeholder' => '• Shared our peer-mentorship onboarding handbook with the community coaches cohort.',
                ],
            ],
        ],
    ];

    /**
     * Show the interactive Project Officer Data Collection Brief.
     */
    public function index(): View
    {
        $this->ensureDatabaseReady();

        return view('programmes.project_brief', [
            'slidesConfig' => self::$slidesConfig,
            'defaultPeriod' => 'Quarter 2 April, May, June 2026',
        ]);
    }

    /**
     * Store project officer submission.
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
            $submission = ProjectSubmission::create($validated);

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
     * Ensure database tables and initial user exist without manual CLI commands.
     */
    protected function ensureDatabaseReady(): void
    {
        try {
            if (!Schema::hasTable('project_submissions')) {
                Schema::create('project_submissions', function (Blueprint $table) {
                    $table->id();
                    $table->uuid('token')->unique();
                    $table->string('project_name');
                    $table->string('officer_name');
                    $table->string('location')->nullable();
                    $table->string('reporting_period')->default('Quarter 2 April, May, June 2026');

                    $table->text('achievements_milestones')->nullable();
                    $table->text('achievements_impact')->nullable();
                    $table->text('achievements_stories')->nullable();

                    $table->text('challenges_operational')->nullable();
                    $table->text('challenges_resources')->nullable();
                    $table->text('challenges_risks')->nullable();

                    $table->text('learning_lessons')->nullable();
                    $table->text('learning_feedback')->nullable();
                    $table->text('learning_innovation')->nullable();

                    $table->text('mne_performance')->nullable();
                    $table->text('mne_data_quality')->nullable();
                    $table->text('mne_evaluation_plans')->nullable();

                    $table->text('collab_projects')->nullable();
                    $table->text('collab_partnerships')->nullable();
                    $table->text('collab_cross_learning')->nullable();

                    $table->timestamps();
                });
            }

            if (!Schema::hasTable('users')) {
                Schema::create('users', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->timestamps();
                });
            }

            if (Schema::hasTable('users') && User::count() === 0) {
                User::create([
                    'name' => 'Supervisor',
                    'email' => 'admin@dot.org',
                    'password' => Hash::make('password'),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Database auto-initialization note: ' . $e->getMessage());
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
     * Supervisor Hub: view all submissions, preview, and compile into 16:9 Presentation PDF.
     */
    public function hub(Request $request): View
    {
        $this->ensureDatabaseReady();

        $query = ProjectSubmission::latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                  ->orWhere('officer_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $submissions = $query->get();

        return view('programmes.dashboard', [
            'submissions' => $submissions,
            'slidesConfig' => self::$slidesConfig,
            'search' => $search,
        ]);
    }

    /**
     * Export Single Project Presentation PDF (16:9 Landscape).
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

        $filename = Str::slug($submission->project_name . '_Q2_Meeting_Slides') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Live Fullscreen Web Projector Mode.
     */
    public function projector(Request $request): View
    {
        $submissions = ProjectSubmission::orderBy('project_name')->get();

        if ($submissions->isEmpty()) {
            $this->seedSampleProjectsData();
            $submissions = ProjectSubmission::orderBy('project_name')->get();
        }

        return view('programmes.projector', [
            'submissions' => $submissions,
            'slidesConfig' => self::$slidesConfig,
            'quarter' => 'Quarter 2 April, May, June 2026',
        ]);
    }

    /**
     * Export Native PowerPoint (.pptx) Presentation.
     */
    public function exportConsolidatedPptx(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $submissions = ProjectSubmission::orderBy('project_name')->get();

        if ($submissions->isEmpty()) {
            $this->seedSampleProjectsData();
            $submissions = ProjectSubmission::orderBy('project_name')->get();
        }

        $ppt = new \PhpOffice\PhpPresentation\PhpPresentation();
        $ppt->getLayout()->setDocumentLayout(\PhpOffice\PhpPresentation\DocumentLayout::LAYOUT_SCREEN_16X9);

        // Slide 1: Cover Slide
        $cover = $ppt->getActiveSlide();

        // Blue Left Accent Bar
        $bar = $cover->createRichTextShape();
        $bar->setOffsetX(0)->setOffsetY(0)->setWidth(15)->setHeight(540);
        $bar->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FF2563EB'));

        // Logo 2 on Cover
        if (file_exists(public_path('logos/logo2.png'))) {
            $logoShape = new \PhpOffice\PhpPresentation\Shape\Drawing\File();
            $logoShape->setName('Logo 2')
                      ->setPath(public_path('logos/logo2.png'))
                      ->setHeight(48)
                      ->setOffsetX(50)
                      ->setOffsetY(35);
            $cover->addShape($logoShape);
        }

        // Cover Title
        $titleShape = $cover->createRichTextShape();
        $titleShape->setOffsetX(50)->setOffsetY(95)->setWidth(860)->setHeight(55);
        $t1 = $titleShape->createTextRun('Play It Forward Zambia');
        $t1->getFont()->setBold(true)->setSize(34)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF0F172A'));

        // Cover Subtitle
        $subShape = $cover->createRichTextShape();
        $subShape->setOffsetX(50)->setOffsetY(155)->setWidth(860)->setHeight(40);
        $t2 = $subShape->createTextRun('Programmes Meeting');
        $t2->getFont()->setBold(true)->setSize(22)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF2563EB'));

        // Period Badge
        $periodShape = $cover->createRichTextShape();
        $periodShape->setOffsetX(50)->setOffsetY(200)->setWidth(860)->setHeight(35);
        $t3 = $periodShape->createTextRun('Quarter 2 April, May, June 2026');
        $t3->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF1D4ED8'));

        // Compiled Projects Box
        $projBox = $cover->createRichTextShape();
        $projBox->setOffsetX(50)->setOffsetY(250)->setWidth(840)->setHeight(230);
        $projBox->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FFF8FAFC'));
        $projBox->getBorder()->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFE2E8F0'))->setLineStyle(\PhpOffice\PhpPresentation\Style\Border::LINE_SINGLE);
        
        $pHeader = $projBox->createTextRun("Compiled Project Submissions ({$submissions->count()} Active Projects):\n\n");
        $pHeader->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF64748B'));

        foreach ($submissions as $sub) {
            $pRun = $projBox->createTextRun("• {$sub->project_name} ({$sub->officer_name})\n");
            $pRun->getFont()->setSize(11)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF1E293B'));
        }

        // Color palettes for projects
        $projectColors = ['FF2563EB', 'FF059669', 'FF7C3AED', 'FFD97706', 'FF0891B2', 'FFE11D48'];

        // Slides 2 to 6: 5 Thematic Slides
        foreach (self::$slidesConfig as $slideKey => $slideConfig) {
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
            $metaRun = $hShape->createTextRun("SLIDE {$slideConfig['number']} OF 5 • QUARTER 2 APRIL, MAY, JUNE 2026\n");
            $metaRun->getFont()->setBold(true)->setSize(9)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF2563EB'));
            $titleRun = $hShape->createTextRun($slideConfig['title']);
            $titleRun->getFont()->setBold(true)->setSize(20)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF0F172A'));

            $colX = [45, 345, 645];
            $colWidth = 285;

            // Column Header Cards
            $cIdx = 0;
            foreach ($slideConfig['items'] as $itemKey => $item) {
                $colHeader = $slide->createRichTextShape();
                $colHeader->setOffsetX($colX[$cIdx])->setOffsetY(85)->setWidth($colWidth)->setHeight(50);
                $colHeader->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FFF8FAFC'));
                $colHeader->getBorder()->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFCBD5E1'))->setLineStyle(\PhpOffice\PhpPresentation\Style\Border::LINE_SINGLE);

                $chTitle = $colHeader->createTextRun($item['title'] . "\n");
                $chTitle->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF0F172A'));
                $chPrompt = $colHeader->createTextRun($item['prompt']);
                $chPrompt->getFont()->setItalic(true)->setSize(8)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF64748B'));

                $cIdx++;
            }

            // Project Rows
            $currY = 145;
            foreach ($submissions as $projIndex => $sub) {
                $colorHex = $projectColors[$projIndex % count($projectColors)];

                // Calculate max lines for this project to set row height
                $maxLines = 1;
                foreach ($slideConfig['items'] as $itemKey => $item) {
                    $pts = $sub->getPoints($itemKey);
                    $maxLines = max($maxLines, count($pts));
                }
                $rowHeight = max(60, 24 + ($maxLines * 18));

                $cIdx = 0;
                foreach ($slideConfig['items'] as $itemKey => $item) {
                    $pts = $sub->getPoints($itemKey);

                    $cell = $slide->createRichTextShape();
                    $cell->setOffsetX($colX[$cIdx])->setOffsetY($currY)->setWidth($colWidth)->setHeight($rowHeight);
                    $cell->getFill()->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFFFF'));
                    $cell->getBorder()->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFE2E8F0'))->setLineStyle(\PhpOffice\PhpPresentation\Style\Border::LINE_SINGLE);

                    $pBadge = $cell->createTextRun("{$sub->project_name} • {$sub->officer_name}\n");
                    $pBadge->getFont()->setBold(true)->setSize(9)->setColor(new \PhpOffice\PhpPresentation\Style\Color($colorHex));

                    if (!empty($pts)) {
                        foreach ($pts as $pt) {
                            $ptRun = $cell->createTextRun("• {$pt}\n");
                            $ptRun->getFont()->setSize(9)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF1E293B'));
                        }
                    } else {
                        $emptyRun = $cell->createTextRun("No points entered\n");
                        $emptyRun->getFont()->setItalic(true)->setSize(8)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF94A3B8'));
                    }

                    $cIdx++;
                }

                $currY += $rowHeight + 8;
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

        $tyPeriod = $tyShape->createTextRun("Quarter 2 April, May, June 2026");
        $tyPeriod->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF1D4ED8'));

        $filename = 'PIFZ_Consolidated_Programmes_Meeting_Q2_2026_' . date('Ymd_His') . '.pptx';

        $oWriter = \PhpOffice\PhpPresentation\IOFactory::createWriter($ppt, 'PowerPoint2007');

        return response()->streamDownload(function () use ($oWriter) {
            $oWriter->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Export Consolidated PowerPoint-Style Presentation PDF (16:9 Landscape).
     * Compiles all submissions or selected submissions by grouping corresponding items per project entry!
     */
    public function exportConsolidatedPresentation(Request $request): Response
    {
        $tokens = $request->input('tokens');

        if (!empty($tokens) && is_array($tokens)) {
            $submissions = ProjectSubmission::whereIn('token', $tokens)->orderBy('project_name')->get();
        } else {
            $submissions = ProjectSubmission::orderBy('project_name')->get();
        }

        // If no submissions exist, create default sample ones so presentation is never empty
        if ($submissions->isEmpty()) {
            $this->seedSampleProjectsData();
            $submissions = ProjectSubmission::orderBy('project_name')->get();
        }

        $pdf = Pdf::loadView('pdf.consolidated_presentation_pdf', [
            'submissions' => $submissions,
            'slidesConfig' => self::$slidesConfig,
            'meetingTitle' => 'Play It Forward Zambia',
            'meetingSubtitle' => 'Programmes Meeting',
            'quarter' => 'Quarter 2 April, May, June 2026',
            'generatedDate' => date('F j, Y'),
        ])->setPaper('a4', 'landscape')
          ->setOption([
              'isHtml5ParserEnabled' => true,
              'isRemoteEnabled' => true,
              'defaultFont' => 'Helvetica',
          ]);

        $filename = 'PIFZ_Consolidated_Programmes_Meeting_Q2_2026_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Delete a single submission from hub.
     */
    public function destroy(string $token): RedirectResponse
    {
        $submission = ProjectSubmission::where('token', $token)->firstOrFail();
        $submission->delete();

        return redirect()->route('programmes.hub')->with('success', "Project '{$submission->project_name}' removed.");
    }

    /**
     * Seed sample projects for instant testing and presentation demo.
     */
    public function seedSample(): RedirectResponse
    {
        $this->ensureDatabaseReady();
        $this->seedSampleProjectsData();

        return redirect()->route('programmes.hub')->with('success', 'Sample project submissions seeded successfully for preview.');
    }

    /**
     * Helper to seed realistic Play It Forward Zambia project submissions.
     */
    protected function seedSampleProjectsData(): void
    {
        $samples = [
            [
                'project_name' => 'Football for Health & Life Skills',
                'officer_name' => 'Mwila Tembo',
                'location' => 'Livingstone Urban',
                'reporting_period' => 'Quarter 2 April, May, June 2026',
                'achievements_milestones' => "• Enrolled 850 youth across 14 community pitches\n• Conducted 48 health curriculum integration modules\n• Held inter-community youth festival with 400+ attendees",
                'achievements_impact' => "• 88% knowledge retention on SRHR & hygiene practices\n• 94% regular session attendance rate throughout Q2",
                'achievements_stories' => "• Bwalya (15) became a peer health champion, initiating a hygiene club in his school.",
                'challenges_operational' => "• Unpredictable pitch availability during municipal maintenance weeks; secured school backup grounds.",
                'challenges_resources' => "• High demand for size 4 & 5 footballs and bibs across new expansion zones.",
                'challenges_risks' => "• Heat safety protocols strictly enforced with mandatory 15-min hydration pauses.",
                'learning_lessons' => "• Pairing female coaches with male coaches increased adolescent girl participation by 40%.",
                'learning_feedback' => "• Community elders requested quarterly update forums on curriculum topics.",
                'learning_innovation' => "• Introduced 'Fair Play Cards' rewarding leadership and teamwork over just match scores.",
                'mne_performance' => "• Met 95% of target enrollment and 100% of planned training workshops.",
                'mne_data_quality' => "• Digital weekly attendance sync verified against paper logs with 99.2% accuracy.",
                'mne_evaluation_plans' => "• Longitudinal behavior impact assessment scheduled for late August.",
                'collab_projects' => "• Partnered with Youth Leadership Hub to facilitate soft-skills sessions during match days.",
                'collab_partnerships' => "• Signed MoU with District Health Office for periodic mobile counseling.",
                'collab_cross_learning' => "• Shared gender-inclusive drill guides with Community Coaching Academy.",
            ],
            [
                'project_name' => 'Girls Empowerment & Mentorship (GEM)',
                'officer_name' => 'Faith Musonda',
                'location' => 'Maramba & Dambwa',
                'reporting_period' => 'Quarter 2 April, May, June 2026',
                'achievements_milestones' => "• Established 8 safe space mentorship circles for 420 girls\n• Delivered 12 menstrual hygiene and career pathway workshops\n• Distributed 400 dignity kits with reusable hygiene supplies",
                'achievements_impact' => "• Zero reported dropouts among GEM cohort members this term\n• 76% of participants expressed increased confidence in public speaking",
                'achievements_stories' => "• Natasha (16) negotiated returning to secondary school with parental blessing after our mentor home visit.",
                'challenges_operational' => "• Safe space venue scheduling conflicts with evening adult literacy classes.",
                'challenges_resources' => "• Need additional dignity kit replenishment for expanding cohort in Dambwa.",
                'challenges_risks' => "• Safeguarding reporting channels continuously highlighted to mentors and school focal points.",
                'learning_lessons' => "• Involving mothers in orientation sessions dramatically reduced absenteeism.",
                'learning_feedback' => "• Participants requested more digital skills and computer basics in upcoming modules.",
                'learning_innovation' => "• Launched 'Sister Circle' peer accountability pairs for academic goal tracking.",
                'mne_performance' => "• Target reached 105% of quarterly girl participant goal.",
                'mne_data_quality' => "• Bi-weekly register audits conducted by M&E officer with zero missing logs.",
                'mne_evaluation_plans' => "• Midline self-efficacy survey scheduled for end of next month.",
                'collab_projects' => "• Co-hosted tournament refreshment stalls with Youth Enterprise teams.",
                'collab_partnerships' => "• Collaborated with Campaign for Female Education (CAMFED) on bursary referrals.",
                'collab_cross_learning' => "• Conducted trauma-informed safeguarding refresher for all programme officers.",
            ],
            [
                'project_name' => 'Youth Leadership & Enterprise Incubator',
                'officer_name' => 'Kelvin Phiri',
                'location' => 'Zambezi Basin Hub',
                'reporting_period' => 'Quarter 2 April, May, June 2026',
                'achievements_milestones' => "• Graduated 65 youth leaders from business readiness bootcamps\n• Facilitated 18 micro-enterprise seed grants totaling ZMW 45,000\n• 24 youth placed into local apprenticeships and hospitality roles",
                'achievements_impact' => "• 82% of financed youth businesses remain actively operating after 90 days\n• Average monthly income of youth leaders increased by 60%",
                'achievements_stories' => "• Dalitso established an eco-briquette business now supplying 12 local restaurants.",
                'challenges_operational' => "• Bureaucratic delays in official PACRA business registration certificates for youth groups.",
                'challenges_resources' => "• Demand for seed capital outpaced initial grant fund allocation by 2:1.",
                'challenges_risks' => "• Financial literacy coaching strengthened before disbursement to minimize default risk.",
                'learning_lessons' => "• Group-based micro-enterprises showed higher resilience than individual sole proprietorships.",
                'learning_feedback' => "• Youth leaders asked for longer mentorship periods and pitch competition events.",
                'learning_innovation' => "• Created WhatsApp peer mastermind network for weekly business bookkeeping check-ins.",
                'mne_performance' => "• Achieved 100% of training targets and 90% of business launch targets.",
                'mne_data_quality' => "• Cloud financial ledger tracking with monthly receipts reconciliation.",
                'mne_evaluation_plans' => "• 6-month enterprise revenue and sustainability audit planned for Q4.",
                'collab_projects' => "• Enterprise alumni supplied sports bibs and tournament catering to Football programmes.",
                'collab_partnerships' => "• Formal partnership with Livingstone Chamber of Commerce for mentorship matching.",
                'collab_cross_learning' => "• Trained coaches on integrating financial literacy micro-lessons into warm-ups.",
            ],
        ];

        foreach ($samples as $sample) {
            ProjectSubmission::firstOrCreate(
                ['project_name' => $sample['project_name'], 'reporting_period' => $sample['reporting_period']],
                $sample
            );
        }
    }
}
