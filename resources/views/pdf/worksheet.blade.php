<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cohort Review & Planning: Participant Reflection & Action Worksheet</title>
    <style>
        @page {
            margin: 25px 25px 25px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.35;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 12px;
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
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }
        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin: 4px 0 2px 0;
        }
        .doc-subtitle {
            font-size: 9px;
            color: #475569;
            margin: 0;
        }
        .meta-box {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 12px;
            width: 100%;
        }
        .meta-table {
            width: 100%;
        }
        .meta-table td {
            font-size: 8.5px;
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
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .section-header {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            font-size: 9.5px;
            text-transform: uppercase;
            padding: 5px 8px;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        .section-desc {
            background-color: #f8fafc;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            font-size: 8px;
            font-style: italic;
            color: #64748b;
            padding: 3px 8px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            margin-bottom: 6px;
        }
        .content-table th {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            font-size: 8.5px;
            font-weight: bold;
            text-align: left;
            color: #334155;
        }
        .content-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 8.5px;
            vertical-align: top;
            color: #334155;
        }
        .sub-header-row {
            background-color: #f8fafc;
            color: #0369a1;
            font-weight: bold;
            font-size: 8.5px;
            padding: 4px 8px;
            border: 1px solid #cbd5e1;
        }
        .response-text {
            color: #1e293b;
            white-space: pre-wrap;
            min-height: 25px;
        }
        .empty-text {
            color: #94a3b8;
            font-style: italic;
        }
        .field-card {
            border: 1px solid #cbd5e1;
            border-top: none;
            padding: 6px 8px;
            background-color: #ffffff;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        .field-label {
            font-weight: bold;
            color: #1e293b;
            font-size: 8.5px;
            display: block;
            margin-bottom: 2px;
        }
        .field-val-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px 7px;
            color: #334155;
            font-size: 8.5px;
            margin-bottom: 4px;
        }
        .page-break {
            page-break-before: always;
        }
        .footer-table {
            width: 100%;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            margin-top: 10px;
            font-size: 8px;
            color: #64748b;
        }
        .grid-2 {
            width: 100%;
        }
        .grid-2 td {
            width: 50%;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <!-- PAGE 1: Header + Section 1 (Journey Mapping) -->
    <table class="header-table">
        <tr>
            <td style="width: 75%;">
                <span class="badge-sub">Digital Opportunity Trust &amp; Partner Network</span>
                <div class="doc-title">Cohort Review &amp; Planning: Participant Reflection &amp; Action Worksheet</div>
                <div class="doc-subtitle">Anonymous Performance Evaluation &amp; Strategic Action Plan for Cohorts 2 &amp; 3</div>
            </td>
            <td style="width: 25%; text-align: right; vertical-align: middle;">
                <span class="badge-anon">100% Anonymous</span>
            </td>
        </tr>
    </table>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td><span class="meta-label">Worksheet Type:</span> <span class="meta-val">Cohort 1 Anonymous Feedback &amp; Review</span></td>
                <td><span class="meta-label">Status:</span> <span class="meta-val" style="color: #166534;">Confidential / De-identified</span></td>
                <td style="text-align: right;"><span class="meta-label">Generated:</span> <span class="meta-val">{{ $date ?? date('Y-m-d') }}</span></td>
            </tr>
        </table>
    </div>

    <!-- Section 1: Journey Mapping -->
    <div class="section-box">
        <div class="section-header">1. Participant &amp; Youth Leader Journey Mapping (Section 1 of 8)</div>
        <div class="section-desc">Reflect on the complete journey from initial recruitment through to graduation and post-training linkages.</div>
        
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
                    <td>
                        <div class="response-text">{{ (!empty($s1_recruitment_high)) ? $s1_recruitment_high : '-- (No response entered)' }}</div>
                    </td>
                    <td>
                        <div class="response-text">{{ (!empty($s1_recruitment_challenges)) ? $s1_recruitment_challenges : '-- (No response entered)' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="sub-header-row">B. Graduation &amp; Milestone Completion</td>
                </tr>
                <tr>
                    <td>
                        <div class="response-text">{{ (!empty($s1_grad_high)) ? $s1_grad_high : '-- (No response entered)' }}</div>
                    </td>
                    <td>
                        <div class="response-text">{{ (!empty($s1_grad_challenges)) ? $s1_grad_challenges : '-- (No response entered)' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="sub-header-row">C. End of Journey: BDS Support &amp; Market Linkages</td>
                </tr>
                <tr>
                    <td>
                        <div class="response-text">{{ (!empty($s1_bds_high)) ? $s1_bds_high : '-- (No response entered)' }}</div>
                    </td>
                    <td>
                        <div class="response-text">{{ (!empty($s1_bds_challenges)) ? $s1_bds_challenges : '-- (No response entered)' }}</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- PAGE 2: Section 2 & Section 3 -->
    <div class="page-break"></div>

    <!-- Section 2: Operations -->
    <div class="section-box">
        <div class="section-header">2. Operational Hurdles, Communication &amp; Host Org Placements (Section 2 of 8)</div>
        <div class="field-card">
            <span class="field-label">A. Operational &amp; Communication Hurdles:</span>
            <div class="field-val-box">{{ (!empty($s2_ops_comm)) ? $s2_ops_comm : '-- (No response entered)' }}</div>

            <table class="grid-2" style="margin-top: 4px;">
                <tr>
                    <td style="padding-right: 4px;">
                        <span class="field-label">Host Org Criteria &amp; Placements:</span>
                        <div class="field-val-box">{{ (!empty($s2_host_criteria)) ? $s2_host_criteria : '-- (No response entered)' }}</div>
                    </td>
                    <td style="padding-left: 4px;">
                        <span class="field-label">Standardized Reporting &amp; Coordination Fixes:</span>
                        <div class="field-val-box">{{ (!empty($s2_reporting_fixes)) ? $s2_reporting_fixes : '-- (No response entered)' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Section 3: PACRA & BDS -->
    <div class="section-box">
        <div class="section-header">3. Participant Tracking, PACRA 50% Target &amp; BDS Linkages (Section 3 of 8)</div>
        <div class="field-card">
            <span class="field-label">B. Strategy to Hit the 50% PACRA Goal &amp; Performance Tracker Use:</span>
            <div class="field-val-box">{{ (!empty($s3_pacra_strategy)) ? $s3_pacra_strategy : '-- (No response entered)' }}</div>

            <span class="field-label" style="margin-top: 6px;">C. BDS Linkages &amp; Market Access:</span>
            <div class="field-val-box">{{ (!empty($s3_market_access)) ? $s3_market_access : '-- (No response entered)' }}</div>
        </div>
    </div>

    <!-- PAGE 3: Section 4 & Section 5 -->
    <div class="page-break"></div>

    <!-- Section 4: Community & Safeguarding -->
    <div class="section-box">
        <div class="section-header">4. Community &amp; Family Engagement, Safeguarding &amp; Inclusion (Section 4 of 8)</div>
        <div class="field-card">
            <span class="field-label">A. Household Buy-In &amp; Drop-out Prevention:</span>
            <div class="field-val-box">{{ (!empty($s4_household_buyin)) ? $s4_household_buyin : '-- (No response entered)' }}</div>

            <table style="width: 100%; margin: 6px 0;">
                <tr>
                    <td>
                        <span class="field-label">B. Safeguarding Channels Accessible:</span>
                        <span style="background-color: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 8px;">
                            {{ (!empty($s4_safeguarding_accessible)) ? $s4_safeguarding_accessible : '-- (Not selected)' }}
                        </span>
                    </td>
                </tr>
            </table>

            <span class="field-label">Specific protocols, incident reporting steps, or inclusion measures:</span>
            <div class="field-val-box">{{ (!empty($s4_safeguarding_details)) ? $s4_safeguarding_details : '-- (No response entered)' }}</div>
        </div>
    </div>

    <!-- Section 5: Finance & Market Walk-Throughs -->
    <div class="section-box">
        <div class="section-header">5. Recruitment Strategy, Market Walk-Throughs &amp; Finance (Section 5 of 8)</div>
        <div class="field-card">
            <span class="field-label">A. Recruitment Strategy &amp; Center Market Walk-Throughs:</span>
            <div class="field-val-box">{{ (!empty($s5_recruitment_walkthroughs)) ? $s5_recruitment_walkthroughs : '-- (No response entered)' }}</div>

            <span class="field-label" style="margin-top: 6px;">B. Financial Processes &amp; Stipend Disbursements:</span>
            <div class="field-val-box">{{ (!empty($s5_finance_stipends)) ? $s5_finance_stipends : '-- (No response entered)' }}</div>
        </div>
    </div>

    <!-- PAGE 4: Section 6, 7 & 8 -->
    <div class="page-break"></div>

    <!-- Section 6: YL Transition -->
    <div class="section-box">
        <div class="section-header">6. Youth Leader (YL) Employment Linkages &amp; Transition (Section 6 of 8)</div>
        <div class="field-card">
            <span class="field-label">Key transition barriers faced and practical linkage pathways:</span>
            <div class="field-val-box">{{ (!empty($s6_yl_transition)) ? $s6_yl_transition : '-- (No response entered)' }}</div>
        </div>
    </div>

    <!-- Section 7: Mindset Impact -->
    <div class="section-box">
        <div class="section-header">7. What Changed for You? Personal &amp; Mindset Impact (Section 7 of 8)</div>
        <table class="content-table">
            <tbody>
                <tr>
                    <td style="width: 30%; font-weight: bold; background-color: #f1f5f9;">Skills Gained:</td>
                    <td style="width: 70%;">{{ (!empty($s7_skills_gained)) ? $s7_skills_gained : '-- (No response entered)' }}</td>
                </tr>
                <tr>
                    <td style="width: 30%; font-weight: bold; background-color: #f1f5f9;">Mindset / Confidence Shift:</td>
                    <td style="width: 70%;">{{ (!empty($s7_mindset_shift)) ? $s7_mindset_shift : '-- (No response entered)' }}</td>
                </tr>
                <tr>
                    <td style="width: 30%; font-weight: bold; background-color: #f1f5f9;">Action Taken in Community:</td>
                    <td style="width: 70%;">{{ (!empty($s7_action_taken)) ? $s7_action_taken : '-- (No response entered)' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section 8: Summary Recommendations -->
    <div class="section-box">
        <div class="section-header">8. Summary Recommendations &amp; Final Reflections (Section 8 of 8)</div>
        <div class="field-card">
            <table class="grid-2">
                <tr>
                    <td style="padding-right: 4px;">
                        <span class="field-label" style="color: #065f46;">Top Things That Worked Well:</span>
                        <div class="field-val-box" style="background-color: #f0fdf4; border-color: #bbf7d0;">{{ (!empty($s8_top_worked)) ? $s8_top_worked : '-- (No response entered)' }}</div>
                    </td>
                    <td style="padding-left: 4px;">
                        <span class="field-label" style="color: #9f1239;">Top Challenges / Barriers:</span>
                        <div class="field-val-box" style="background-color: #fff1f2; border-color: #fecdd3;">{{ (!empty($s8_top_barriers)) ? $s8_top_barriers : '-- (No response entered)' }}</div>
                    </td>
                </tr>
            </table>

            <span class="field-label" style="margin-top: 6px;">If you could change ONE thing for the next cohort:</span>
            <div class="field-val-box">{{ (!empty($s8_change_one_thing)) ? $s8_change_one_thing : '-- (No response entered)' }}</div>

            <table class="grid-2" style="margin-top: 4px;">
                <tr>
                    <td style="padding-right: 4px;">
                        <span class="field-label">One word feeling:</span>
                        <div class="field-val-box" style="font-weight: bold; color: #0369a1;">{{ (!empty($s8_one_word)) ? $s8_one_word : '--' }}</div>
                    </td>
                    <td style="padding-left: 4px;">
                        <span class="field-label">Final message for YSO / DOT:</span>
                        <div class="field-val-box">{{ (!empty($s8_final_message)) ? $s8_final_message : '-- (No response entered)' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <table class="footer-table">
        <tr>
            <td style="width: 70%;">
                <strong>Digital Opportunity Trust Continuous Evaluation Framework</strong><br>
                Generated: {{ date('Y-m-d H:i:s') }}
            </td>
            <td style="width: 30%; text-align: right; vertical-align: middle;">
                <span class="badge-anon">Verified Anonymous Report</span>
            </td>
        </tr>
    </table>

</body>
</html>
