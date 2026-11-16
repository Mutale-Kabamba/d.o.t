<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->name ?? $submission->project_name ?? 'Project' }} - Programmes Report</title>
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

        /* Report Header */
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
            font-size: 19px;
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
            font-size: 9.5px;
            font-weight: 800;
            color: #0f172a;
        }

        /* Pillar Block */
        .pillar-block {
            margin-bottom: 14px;
        }
        .pillar-header {
            font-size: 11px;
            font-weight: 800;
            color: #0d6c60;
            background-color: #f1f5f9;
            border-left: 4px solid #0d6c60;
            border-top: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 5px 10px;
            margin-bottom: 8px;
            page-break-after: avoid;
        }
        .pillar-number {
            color: #4f992f;
            margin-right: 4px;
        }
        .pillar-subtitle {
            font-size: 8px;
            font-weight: 500;
            color: #64748b;
            display: block;
            margin-top: 1px;
        }

        /* Sub-section Block */
        .subsection-block {
            margin: 0 0 9px 10px;
        }
        .subsection-title {
            font-size: 9px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
            page-break-after: avoid;
        }
        .subsection-prompt {
            font-size: 8px;
            color: #64748b;
            font-style: italic;
            margin-bottom: 4px;
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
            margin: 4px 0 4px 10px;
            padding: 5px 8px;
            background-color: #f8fafc;
            border-left: 2.5px solid #4f992f;
            font-size: 8.5px;
            color: #334155;
            line-height: 1.38;
            page-break-inside: avoid;
        }
        .synthesis-box {
            margin: 6px 0 6px 10px;
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
            margin: 2px 0 6px 10px;
            font-size: 8.5px;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>

    @php
        $logo3Base64 = $logo3Base64 ?? '';
        if (empty($logo3Base64) && file_exists(public_path('logos/logo3.png'))) {
            $logo3Base64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logos/logo3.png')));
        }

        $pName = $project->name ?? $submission->project_name ?? 'Project';
        $oName = $project->lead_officer_name ?? $submission->officer_name ?? 'Project Officer';
        $loc = $project->location ?? $submission->location ?? 'Livingstone, Zambia';
        $period = $periodTitle ?? $submission->reporting_period ?? 'All Time Comprehensive Review';

        $primaryPData = !empty($projectDataList) ? $projectDataList[0] : null;
        $entriesCount = $primaryPData['entries_count'] ?? 0;
    @endphp

    <!-- Running Footer on Every Page -->
    <div class="running-footer">
        <table class="running-footer-table">
            <tr>
                <td style="text-align: left;"><strong>Play It Forward Zambia</strong> • {{ $pName }} • {{ $period }}</td>
                <td style="text-align: right;"><span class="page-num"></span></td>
            </tr>
        </table>
    </div>

    <!-- ==================== REPORT HEADER ==================== -->
    <div class="report-header">
        <table class="header-table">
            <tr>
                <td style="vertical-align: middle;">
                    <div class="org-kicker">Play It Forward Zambia • Project Report</div>
                    <h1 class="report-title">{{ $pName }}</h1>
                    <div class="period-badge">{{ $period }}</div>
                </td>
                <td style="text-align: right; vertical-align: middle; width: 110px;">
                    @if(!empty($logo3Base64))
                        <img src="{{ $logo3Base64 }}" class="header-logo" alt="Play It Forward Logo">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Metadata Strip -->
    <table class="meta-strip">
        <tr>
            <td style="width: 30%;">
                <div class="meta-label">Lead Officer</div>
                <div class="meta-value">{{ $primaryPData['officer_name'] ?? $oName }}</div>
            </td>
            <td style="width: 24%;">
                <div class="meta-label">Location</div>
                <div class="meta-value">{{ $primaryPData['location'] ?? $loc }}</div>
            </td>
            <td style="width: 22%;">
                <div class="meta-label">Activities Logged</div>
                <div class="meta-value">{{ $entriesCount }}</div>
            </td>
            <td style="width: 24%;">
                <div class="meta-label">Date Generated</div>
                <div class="meta-value">{{ $generatedDate }}</div>
            </td>
        </tr>
    </table>

    <!-- ==================== THEMATIC PILLARS ==================== -->
    @foreach(($slidesConfig ?? []) as $slideKey => $slide)
        @php
            $sections = $primaryPData['theme_sections'][$slideKey] ?? [];
            $themePts = $primaryPData['theme_points'][$slideKey] ?? [];
            $narrativeText = $primaryPData['theme_narrative_text'][$slideKey] ?? '';

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
                @if(!empty($slide['subtitle']))
                    <span class="pillar-subtitle">{{ $slide['subtitle'] }}</span>
                @endif
            </div>

            @if($hasSectionsContent)
                @foreach($sections as $itemKey => $sec)
                    @if(!empty($sec['points']) || !empty($sec['narrative']) || !empty($sec['media_links']))
                        <div class="subsection-block">
                            <div class="subsection-title">{{ $sec['title'] }}</div>
                            @if(!empty($sec['prompt']))
                                <div class="subsection-prompt">{{ $sec['prompt'] }}</div>
                            @endif

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
            @elseif(!empty($themePts))
                <div class="subsection-block">
                    <ul class="points-list">
                        @foreach($themePts as $pt)
                            <li>{!! \App\Models\ActivityEntry::formatPointHtml($pt, true) !!}</li>
                        @endforeach
                    </ul>
                </div>
            @elseif(isset($submission))
                @foreach($slide['items'] as $itemKey => $item)
                    @php $points = $submission->getPoints($itemKey); @endphp
                    <div class="subsection-block">
                        <div class="subsection-title">{{ $item['title'] }}</div>
                        @if(!empty($points))
                            <ul class="points-list">
                                @foreach($points as $pt)
                                    <li>{!! \App\Models\ProjectSubmission::formatPointHtml($pt, true) !!}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="empty-note">No specific points entered.</div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="empty-note">No entries recorded for {{ strtolower($slide['title']) }} in this period.</div>
            @endif
        </div>
    @endforeach

</body>
</html>
