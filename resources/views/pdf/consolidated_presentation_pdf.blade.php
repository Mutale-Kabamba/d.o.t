<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $meetingTitle ?? 'Play It Forward Zambia' }} - Programmes Report</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 16mm 14mm 18mm 14mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #1e293b;
            font-size: 9.5px;
            line-height: 1.45;
        }

        /* Running Page Footer */
        .running-footer {
            position: fixed;
            bottom: -11mm;
            left: 0;
            right: 0;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            font-size: 8px;
            color: #64748b;
        }
        .running-footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .page-num::after {
            content: "Page " counter(page);
        }

        /* Top Report Banner */
        .report-header {
            border-bottom: 2px solid #0d6c60;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            height: 38px;
        }
        .org-kicker {
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0d6c60;
            margin-bottom: 2px;
        }
        .report-title {
            font-size: 20px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: -0.3px;
        }
        .period-badge {
            display: inline-block;
            background-color: #ecfdf5;
            color: #0d6c60;
            border: 1px solid #a7f3d0;
            border-radius: 4px;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: 700;
        }

        /* Metadata Strip */
        .meta-strip {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .meta-strip td {
            padding: 7px 10px;
            border-right: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .meta-strip td:last-child {
            border-right: none;
        }
        .meta-label {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 10.5px;
            font-weight: 800;
            color: #0f172a;
        }

        /* Section Headings */
        .section-heading {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0d6c60;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin: 14px 0 8px 0;
            page-break-after: avoid;
        }

        /* Overview Table */
        .overview-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 8.5px;
        }
        .overview-table th {
            background-color: #0d6c60;
            color: #ffffff;
            text-align: left;
            padding: 6px 8px;
            font-weight: 700;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .overview-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: top;
        }
        .overview-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* Project Block */
        .project-section {
            margin-top: 16px;
            margin-bottom: 18px;
        }
        .project-banner {
            background-color: #f1f5f9;
            border-left: 4px solid #0d6c60;
            border-top: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 8px 12px;
            margin-bottom: 10px;
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        .project-title {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 2px 0;
        }
        .project-meta {
            font-size: 8.5px;
            color: #475569;
        }

        /* Pillar Block */
        .pillar-block {
            margin-bottom: 12px;
            padding-left: 2px;
        }
        .pillar-header {
            font-size: 10.5px;
            font-weight: 800;
            color: #0d6c60;
            background-color: #f8fafc;
            border-bottom: 1px solid #cbd5e1;
            padding: 4px 8px;
            margin-bottom: 6px;
            page-break-after: avoid;
        }
        .pillar-number {
            color: #4f992f;
            margin-right: 4px;
        }

        /* Sub-section Block */
        .subsection-block {
            margin: 0 0 8px 8px;
        }
        .subsection-title {
            font-size: 9px;
            font-weight: 800;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
            page-break-after: avoid;
        }
        .points-list {
            margin: 0 0 4px 0;
            padding-left: 16px;
        }
        .points-list li {
            margin-bottom: 3px;
            color: #1e293b;
            line-height: 1.42;
            word-break: break-word;
        }
        .media-row {
            margin: 3px 0 4px 16px;
            font-size: 8px;
            color: #475569;
        }
        .summary-box {
            margin: 4px 0 4px 8px;
            padding: 5px 8px;
            background-color: #f8fafc;
            border-left: 2.5px solid #4f992f;
            font-size: 8.5px;
            color: #334155;
            line-height: 1.38;
            page-break-inside: avoid;
        }
        .synthesis-box {
            margin: 6px 0 6px 8px;
            padding: 6px 10px;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 3px solid #0d6c60;
            font-size: 8.5px;
            color: #1e293b;
            line-height: 1.4;
            page-break-inside: avoid;
        }
        .empty-note {
            margin: 2px 0 6px 8px;
            font-size: 8.5px;
            color: #94a3b8;
            font-style: italic;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    @php
        $logo3Base64 = $logo3Base64 ?? '';
        if (empty($logo3Base64) && file_exists(public_path('logos/logo3.png'))) {
            $logo3Base64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logos/logo3.png')));
        }
        $compiledProjects = $projectDataList ?? [];
        $totalEntries = collect($compiledProjects)->sum('entries_count');
    @endphp

    <!-- Running Footer on Every Page -->
    <div class="running-footer">
        <table class="running-footer-table">
            <tr>
                <td style="text-align: left;"><strong>Play It Forward Zambia</strong> • Programmes Report • {{ $quarter }}</td>
                <td style="text-align: right;"><span class="page-num"></span></td>
            </tr>
        </table>
    </div>

    <!-- ==================== REPORT HEADER ==================== -->
    <div class="report-header">
        <table class="header-table">
            <tr>
                <td style="vertical-align: middle;">
                    <div class="org-kicker">{{ $meetingTitle ?? 'Play It Forward Zambia' }}</div>
                    <h1 class="report-title">Consolidated Programmes Report</h1>
                    <div class="period-badge">{{ $quarter }}</div>
                </td>
                <td style="text-align: right; vertical-align: middle; width: 110px;">
                    @if(!empty($logo3Base64))
                        <img src="{{ $logo3Base64 }}" class="header-logo" alt="Play It Forward Logo">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Summary Metadata Strip -->
    <table class="meta-strip">
        <tr>
            <td style="width: 28%;">
                <div class="meta-label">Reporting Period</div>
                <div class="meta-value" style="font-size: 9.5px;">{{ $quarter }}</div>
            </td>
            <td style="width: 24%;">
                <div class="meta-label">Active Projects</div>
                <div class="meta-value">{{ count($compiledProjects) }}</div>
            </td>
            <td style="width: 24%;">
                <div class="meta-label">Activities Logged</div>
                <div class="meta-value">{{ $totalEntries }}</div>
            </td>
            <td style="width: 24%;">
                <div class="meta-label">Date Generated</div>
                <div class="meta-value" style="font-size: 9.5px;">{{ $generatedDate }}</div>
            </td>
        </tr>
    </table>

    <!-- ==================== EXECUTIVE OVERVIEW TABLE ==================== -->
    <div class="section-heading">Projects Included in This Report</div>
    @if(!empty($compiledProjects))
        <table class="overview-table">
            <thead>
                <tr>
                    <th style="width: 6%;">#</th>
                    <th style="width: 38%;">Project / Initiative</th>
                    <th style="width: 26%;">Lead Officer</th>
                    <th style="width: 18%;">Location</th>
                    <th style="width: 12%; text-align: right;">Activities</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compiledProjects as $idx => $pData)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td><strong>{{ $pData['project_name'] }}</strong></td>
                    <td>{{ $pData['officer_name'] ?: '—' }}</td>
                    <td>{{ $pData['location'] ?: 'Zambia' }}</td>
                    <td style="text-align: right;"><strong>{{ $pData['entries_count'] ?? 0 }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-note">No active projects found for the selected filter criteria.</div>
    @endif

    <!-- ==================== DETAILED PROJECT SECTIONS ==================== -->
    @foreach($compiledProjects as $pIdx => $pData)
        <div class="project-section {{ $pIdx > 0 ? 'page-break' : '' }}">
            <div class="project-banner">
                <h2 class="project-title">{{ $pIdx + 1 }}. {{ $pData['project_name'] }}</h2>
                <div class="project-meta">
                    <strong>Lead Officer:</strong> {{ $pData['officer_name'] ?: 'Unassigned' }}
                    &nbsp;•&nbsp;
                    <strong>Location:</strong> {{ $pData['location'] ?: 'Zambia' }}
                    &nbsp;•&nbsp;
                    <strong>Activities Recorded:</strong> {{ $pData['entries_count'] ?? 0 }}
                </div>
            </div>

            @foreach(($slidesConfig ?? []) as $slideKey => $slide)
                @php
                    $sections = $pData['theme_sections'][$slideKey] ?? [];
                    $points = $pData['theme_points'][$slideKey] ?? [];
                    $narrativeText = $pData['theme_narrative_text'][$slideKey] ?? '';

                    $hasSectionsContent = false;
                    foreach ($sections as $sec) {
                        if (!empty($sec['points']) || !empty($sec['narrative']) || !empty($sec['media_links'])) {
                            $hasSectionsContent = true;
                            break;
                        }
                    }
                @endphp

                <div class="pillar-block">
                    <div class="pillar-header">
                        <span class="pillar-number">Pillar {{ $slide['number'] }}:</span> {{ $slide['title'] }}
                    </div>

                    @if($hasSectionsContent)
                        @foreach($sections as $itemKey => $sec)
                            @if(!empty($sec['points']) || !empty($sec['narrative']) || !empty($sec['media_links']))
                                <div class="subsection-block">
                                    <div class="subsection-title">{{ $sec['title'] }}</div>

                                    @if(!empty($sec['points']))
                                        <ul class="points-list">
                                            @foreach($sec['points'] as $pt)
                                                <li>{!! \App\Models\ActivityEntry::formatPointHtml($pt, true) !!}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if(!empty($sec['media_links']))
                                        <div class="media-row">
                                            <strong>Attached:</strong>
                                            @foreach($sec['media_links'] as $mLink)
                                                {!! \App\Models\ActivityEntry::formatPointHtml($mLink, true) !!}
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(!empty($sec['narrative']))
                                        <div class="summary-box">
                                            <strong>Summary:</strong> {{ $sec['narrative'] }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach

                        @if(!empty($narrativeText))
                            <div class="synthesis-box">
                                <strong>Executive Synthesis:</strong> {{ $narrativeText }}
                            </div>
                        @endif
                    @elseif(!empty($points))
                        <div class="subsection-block">
                            <ul class="points-list">
                                @foreach($points as $pt)
                                    <li>{!! \App\Models\ActivityEntry::formatPointHtml($pt, true) !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="empty-note">No entries recorded for {{ strtolower($slide['title']) }} in this period.</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

</body>
</html>
