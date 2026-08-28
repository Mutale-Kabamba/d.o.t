<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cohort Full Anonymous Review Report</title>
    <style>
        @page {
            margin: 25px 25px 25px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            line-height: 1.35;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .cover-page {
            text-align: center;
            padding: 100px 20px;
        }
        .cover-title {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        .cover-sub {
            font-size: 12px;
            color: #475569;
            margin-bottom: 30px;
        }
        .cover-badge {
            background-color: #e0f2fe;
            color: #0369a1;
            font-weight: bold;
            font-size: 10px;
            padding: 4px 12px;
            border-radius: 6px;
            display: inline-block;
        }
        .cover-meta {
            margin-top: 40px;
            padding: 15px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            display: inline-block;
            text-align: left;
            font-size: 10px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .badge-sub {
            background-color: #e0f2fe;
            color: #0369a1;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
        }
        .badge-anon {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
        }
        .doc-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin: 2px 0;
        }
        .meta-box {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 5px 8px;
            margin-bottom: 10px;
            width: 100%;
        }
        .meta-table {
            width: 100%;
        }
        .meta-table td {
            font-size: 8px;
        }
        .meta-label {
            color: #64748b;
            font-weight: bold;
            text-transform: uppercase;
        }
        .meta-val {
            color: #0f172a;
            font-weight: bold;
        }
        .section-box {
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .section-header {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 4px 7px;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        .section-desc {
            background-color: #f8fafc;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            font-size: 7.5px;
            font-style: italic;
            color: #64748b;
            padding: 2px 7px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            margin-bottom: 4px;
        }
        .content-table th {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            font-size: 8px;
            font-weight: bold;
            text-align: left;
            color: #334155;
        }
        .content-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            font-size: 8px;
            vertical-align: top;
            color: #334155;
        }
        .sub-header-row {
            background-color: #f8fafc;
            color: #0369a1;
            font-weight: bold;
            font-size: 8px;
            padding: 3px 6px;
            border: 1px solid #cbd5e1;
        }
        .response-text {
            color: #1e293b;
            white-space: pre-wrap;
            min-height: 20px;
        }
        .field-card {
            border: 1px solid #cbd5e1;
            border-top: none;
            padding: 5px 7px;
            background-color: #ffffff;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        .field-label {
            font-weight: bold;
            color: #1e293b;
            font-size: 8px;
            display: block;
            margin-bottom: 2px;
        }
        .field-val-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 4px 6px;
            color: #334155;
            font-size: 8px;
            margin-bottom: 3px;
        }
        .page-break {
            page-break-before: always;
        }
        .footer-table {
            width: 100%;
            border-top: 1px solid #cbd5e1;
            padding-top: 5px;
            margin-top: 8px;
            font-size: 7.5px;
            color: #64748b;
        }
        .grid-2 {
            width: 100%;
        }
        .grid-2 td {
            width: 50%;
            vertical-align: top;
        }
        .submission-divider {
            background-color: #0284c7;
            color: #ffffff;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11px;
            border-radius: 4px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

    <!-- MASTER COVER PAGE -->
    <div class="cover-page">
        <span class="cover-badge">Digital Opportunity Trust &amp; Partner Network</span>
        <div class="cover-title">Cohort Review &amp; Strategic Planning</div>
        <div class="cover-sub">Comprehensive Master Report &bull; Sectioned Anonymous Submissions</div>
        
        <div class="cover-meta">
            <div><strong>Report Title:</strong> Full Cohort Participant Reflection &amp; Action Master Compilation</div>
            <div style="margin-top: 4px;"><strong>Total Submissions Compiled:</strong> {{ count($submissions) }}</div>
            <div style="margin-top: 4px;"><strong>Generated Date:</strong> {{ date('F d, Y - H:i') }}</div>
            <div style="margin-top: 4px;"><strong>Confidentiality Status:</strong> 100% De-identified / Anonymous</div>
        </div>
    </div>

    @foreach($submissions as $index => $submission)
        <!-- PAGE BREAK PER SUBMISSION -->
        <div class="page-break"></div>

        <div class="submission-divider">
            Submission #{{ $index + 1 }} of {{ count($submissions) }} &mdash; Token: {{ $submission->token }}
        </div>

        <table class="header-table">
            <tr>
                <td style="width: 75%;">
                    <span class="badge-sub">Cohort 1 Review &bull; Participant Reflection</span>
                    <div class="doc-title">Participant Action Worksheet Report</div>
                </td>
                <td style="width: 25%; text-align: right; vertical-align: middle;">
                    <span class="badge-anon">100% Anonymous</span>
                </td>
            </tr>
        </table>

        <div class="meta-box">
            <table class="meta-table">
                <tr>
                    <td><span class="meta-label">Receipt Token:</span> <span class="meta-val" style="font-family: monospace;">{{ $submission->token }}</span></td>
                    <td><span class="meta-label">Submitted On:</span> <span class="meta-val">{{ $submission->created_at->format('M d, Y H:i') }}</span></td>
                    <td style="text-align: right;">
                        <span class="meta-label">One Word:</span> 
                        <span class="meta-val" style="color: #0369a1;">{{ $submission->s8_one_word ?: '--' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Section 1: Journey Mapping -->
        <div class="section-box">
            <div class="section-header">1. Journey Mapping (Section 1 of 8)</div>
            <table class="content-table">
                <thead>
                    <tr>
                        <th style="width: 50%; color: #065f46;">High Points &amp; Successes</th>
                        <th style="width: 50%; color: #9f1239;">Challenges &amp; Bottlenecks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" class="sub-header-row">A. Recruitment &amp; Application / Mobilization Phase</td>
                    </tr>
                    <tr>
                        <td><div class="response-text">{{ $submission->s1_recruitment_high ?: '--' }}</div></td>
                        <td><div class="response-text">{{ $submission->s1_recruitment_challenges ?: '--' }}</div></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="sub-header-row">B. Graduation &amp; Milestone Completion</td>
                    </tr>
                    <tr>
                        <td><div class="response-text">{{ $submission->s1_grad_high ?: '--' }}</div></td>
                        <td><div class="response-text">{{ $submission->s1_grad_challenges ?: '--' }}</div></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="sub-header-row">C. BDS Support &amp; Market Linkages</td>
                    </tr>
                    <tr>
                        <td><div class="response-text">{{ $submission->s1_bds_high ?: '--' }}</div></td>
                        <td><div class="response-text">{{ $submission->s1_bds_challenges ?: '--' }}</div></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Section 2: Operations -->
        <div class="section-box">
            <div class="section-header">2. Operations &amp; Host Placements (Section 2 of 8)</div>
            <div class="field-card">
                <span class="field-label">A. Operational &amp; Communication Hurdles:</span>
                <div class="field-val-box">{{ $submission->s2_ops_comm ?: '--' }}</div>

                <table class="grid-2">
                    <tr>
                        <td style="padding-right: 4px;">
                            <span class="field-label">Host Org Criteria:</span>
                            <div class="field-val-box">{{ $submission->s2_host_criteria ?: '--' }}</div>
                        </td>
                        <td style="padding-left: 4px;">
                            <span class="field-label">Reporting Fixes:</span>
                            <div class="field-val-box">{{ $submission->s2_reporting_fixes ?: '--' }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Section 3: PACRA & BDS -->
        <div class="section-box">
            <div class="section-header">3. PACRA 50% Target &amp; BDS Linkages (Section 3 of 8)</div>
            <div class="field-card">
                <span class="field-label">B. Strategy for 50% PACRA:</span>
                <div class="field-val-box">{{ $submission->s3_pacra_strategy ?: '--' }}</div>

                <span class="field-label">C. BDS Linkages &amp; Market Access:</span>
                <div class="field-val-box">{{ $submission->s3_market_access ?: '--' }}</div>
            </div>
        </div>

        <!-- Section 4: Community & Safeguarding -->
        <div class="section-box">
            <div class="section-header">4. Community Engagement &amp; Safeguarding (Section 4 of 8)</div>
            <div class="field-card">
                <span class="field-label">A. Household Buy-In &amp; Retention:</span>
                <div class="field-val-box">{{ $submission->s4_household_buyin ?: '--' }}</div>

                <table style="width: 100%; margin: 4px 0;">
                    <tr>
                        <td>
                            <span class="field-label">B. Safeguarding Accessible:</span>
                            <span style="background-color: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 7.5px;">
                                {{ $submission->s4_safeguarding_accessible ?: '-- (Not selected)' }}
                            </span>
                        </td>
                    </tr>
                </table>

                <span class="field-label">Protocols &amp; Inclusion Measures:</span>
                <div class="field-val-box">{{ $submission->s4_safeguarding_details ?: '--' }}</div>
            </div>
        </div>

        <!-- Section 5: Finance & Market Walk-Throughs -->
        <div class="section-box">
            <div class="section-header">5. Recruitment Walk-Throughs &amp; Finance (Section 5 of 8)</div>
            <div class="field-card">
                <span class="field-label">A. Center Market Walk-Through Strategy:</span>
                <div class="field-val-box">{{ $submission->s5_recruitment_walkthroughs ?: '--' }}</div>

                <span class="field-label">B. Financial Processes &amp; Stipend Disbursements:</span>
                <div class="field-val-box">{{ $submission->s5_finance_stipends ?: '--' }}</div>
            </div>
        </div>

        <!-- Section 6: YL Transition -->
        <div class="section-box">
            <div class="section-header">6. Youth Leader Employment Linkages (Section 6 of 8)</div>
            <div class="field-card">
                <span class="field-label">Transition Barriers &amp; Linkages:</span>
                <div class="field-val-box">{{ $submission->s6_yl_transition ?: '--' }}</div>
            </div>
        </div>

        <!-- Section 7: Mindset Impact -->
        <div class="section-box">
            <div class="section-header">7. Personal Growth &amp; Mindset Impact (Section 7 of 8)</div>
            <table class="content-table">
                <tbody>
                    <tr>
                        <td style="width: 30%; font-weight: bold; background-color: #f1f5f9;">Skills Gained:</td>
                        <td style="width: 70%;">{{ $submission->s7_skills_gained ?: '--' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; background-color: #f1f5f9;">Mindset Shift:</td>
                        <td style="width: 70%;">{{ $submission->s7_mindset_shift ?: '--' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; background-color: #f1f5f9;">Community Action:</td>
                        <td style="width: 70%;">{{ $submission->s7_action_taken ?: '--' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Section 8: Summary Recommendations -->
        <div class="section-box">
            <div class="section-header">8. Recommendations &amp; Final Message (Section 8 of 8)</div>
            <div class="field-card">
                <table class="grid-2">
                    <tr>
                        <td style="padding-right: 4px;">
                            <span class="field-label" style="color: #065f46;">Top Things That Worked:</span>
                            <div class="field-val-box" style="background-color: #f0fdf4; border-color: #bbf7d0;">{{ $submission->s8_top_worked ?: '--' }}</div>
                        </td>
                        <td style="padding-left: 4px;">
                            <span class="field-label" style="color: #9f1239;">Top Challenges:</span>
                            <div class="field-val-box" style="background-color: #fff1f2; border-color: #fecdd3;">{{ $submission->s8_top_barriers ?: '--' }}</div>
                        </td>
                    </tr>
                </table>

                <span class="field-label">If you could change ONE thing:</span>
                <div class="field-val-box">{{ $submission->s8_change_one_thing ?: '--' }}</div>

                <table class="grid-2">
                    <tr>
                        <td style="padding-right: 4px;">
                            <span class="field-label">One Word Feeling:</span>
                            <div class="field-val-box" style="font-weight: bold; color: #0369a1;">{{ $submission->s8_one_word ?: '--' }}</div>
                        </td>
                        <td style="padding-left: 4px;">
                            <span class="field-label">Final Message for YSO / DOT:</span>
                            <div class="field-val-box">{{ $submission->s8_final_message ?: '--' }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="footer-table">
            <tr>
                <td style="width: 70%;">
                    Digital Opportunity Trust Master Compilation &bull; Submission Token: {{ $submission->token }}
                </td>
                <td style="width: 30%; text-align: right;">
                    Anonymous Participant Report
                </td>
            </tr>
        </table>
    @endforeach

</body>
</html>
