<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Play It Forward Zambia - Consolidated Programmes Meeting Presentation</title>
    <style>
        @page {
            size: 297mm 210mm landscape;
            margin: 8mm 12mm 8mm 14mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #0f172a;
            font-size: 8.5px;
            line-height: 1.3;
        }

        .slide {
            width: 100%;
            page-break-after: always;
            position: relative;
            background-color: #ffffff;
            padding-bottom: 2px;
        }
        .slide:last-child {
            page-break-after: avoid;
        }

        /* Left Branding Color Bar */
        .brand-bar-left {
            position: absolute;
            left: -14mm;
            top: -8mm;
            height: 196mm;
            width: 5mm;
            background-color: #2563eb;
        }

        /* Cover Slide */
        .cover-slide {
            padding-top: 14mm;
            padding-left: 6mm;
        }
        .cover-logo {
            height: 48px;
            margin-bottom: 10px;
        }
        .cover-title {
            font-size: 32px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }
        .cover-subtitle {
            font-size: 20px;
            font-weight: 700;
            color: #2563eb;
            margin: 0 0 10px 0;
        }
        .cover-badge {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 3px 12px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 14px;
        }
        .cover-projects-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 14px;
            width: 98%;
        }
        .cover-projects-title {
            font-size: 10px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .project-tag {
            display: inline-block;
            background-color: #ffffff;
            color: #1e293b;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 2.5px 7px;
            font-size: 9.5px;
            font-weight: 700;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        /* Slide Header */
        .slide-header {
            margin-bottom: 6px;
            position: relative;
            padding-right: 110px;
        }
        .header-logo3 {
            position: absolute;
            right: 0;
            top: -2px;
            height: 28px;
        }
        .slide-meta {
            font-size: 9px;
            font-weight: 800;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .slide-title {
            font-size: 18px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }

        /* 2-PROJECT COMPACT TABLE LAYOUT */
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            table-layout: fixed;
            margin-left: -4px;
            margin-right: -4px;
        }

        .col-header-box {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 5px 8px;
            margin-bottom: 4px;
            text-align: left;
        }
        .proj-name {
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 1.5px;
        }
        .proj-officer {
            font-size: 8.5px;
            font-weight: 500;
            color: #475569;
        }

        .item-box {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 8px;
            font-size: 8.5px;
            word-break: break-word;
        }

        .points-list {
            margin: 0;
            padding-left: 12px;
        }
        .points-list li {
            margin-bottom: 2px;
            color: #1e293b;
            line-height: 1.28;
            font-size: 8px;
        }
        .empty-points {
            font-size: 8px;
            color: #64748b;
            font-style: italic;
        }

        .section-block {
            margin-bottom: 4px;
        }
        .section-title {
            font-size: 8.5px;
            font-weight: 800;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Slide Footer */
        .slide-footer {
            margin-top: 4px;
            padding-top: 2px;
            border-top: 1px solid #e2e8f0;
            font-size: 7.5px;
            color: #64748b;
            font-weight: 600;
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            text-align: left;
        }
        .footer-right {
            display: table-cell;
            text-align: right;
        }

        /* Thank You Slide */
        .thank-you-slide {
            padding-top: 25mm;
            text-align: center;
        }
        .ty-title {
            font-size: 34px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 4px 0;
        }
        .ty-sub {
            font-size: 17px;
            font-weight: 700;
            color: #2563eb;
            margin: 0 0 8px 0;
        }
        .ty-quote {
            font-size: 10.5px;
            color: #475569;
            max-width: 600px;
            margin: 0 auto 12px auto;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    @php
        $logo3Base64 = $logo3Base64 ?? '';
        if (empty($logo3Base64) && file_exists(public_path('logos/logo3.png'))) {
            $logo3Base64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logos/logo3.png')));
        }
        $borderColors = ['#2563eb', '#059669', '#7c3aed', '#d97706', '#0891b2', '#e11d48'];

        // If slideDeck is not already provided by controller, compute it dynamically
        if (empty($slideDeck)) {
            $slideDeck = \App\Http\Controllers\ProgrammesMeetingController::buildConsolidatedSlideBatches(
                $projectDataList ?? [],
                $slidesConfig ?? [],
                4
            );
        }

        $totalSlides = 1 + count($slideDeck) + 1; // Cover + Pillar Slides + Thank You
    @endphp

    <!-- ==================== SLIDE 0: COVER SLIDE ==================== -->
    <div class="slide cover-slide">
        <div class="brand-bar-left"></div>

        @if(!empty($logo3Base64))
            <img src="{{ $logo3Base64 }}" class="cover-logo" alt="Logo">
        @endif

        <div class="cover-title">{{ $meetingTitle ?? 'Play It Forward Zambia' }}</div>
        <div class="cover-subtitle">{{ $meetingSubtitle ?? 'Programmes Meeting' }}</div>
        <div class="cover-badge">{{ $quarter }}</div>

        <div class="cover-projects-box">
            <div class="cover-projects-title">Active Projects Compiled ({{ count($projectDataList ?? []) }} Active Projects • 2 Projects Per Slide):</div>
            <div>
                @foreach(($projectDataList ?? []) as $pData)
                    <div class="project-tag">
                        <strong>{{ $pData['project_name'] }}</strong> ({{ $pData['officer_name'] }})
                    </div>
                @endforeach
            </div>
        </div>

        <div class="slide-footer" style="margin-top: 14mm;">
            <div class="footer-left">Play It Forward Zambia • Programmes Meeting</div>
            <div class="footer-right">Generated: {{ $generatedDate }} • Slide 1 of {{ $totalSlides }}</div>
        </div>
    </div>

    <!-- ==================== THEMATIC SLIDES (PAGINATED & BALANCED) ==================== -->
    @foreach($slideDeck as $deckIdx => $deckItem)
    @php
        $slide = $deckItem['slide'];
        $chunkProjects = $deckItem['projects'];
        $colWidthPct = count($chunkProjects) === 1 ? '100%' : '50%';
        $slideNum = $deckIdx + 2;

        $metaParts = [];
        $metaParts[] = "PILLAR " . $slide['number'] . " OF 5";
        $metaParts[] = strtoupper($quarter);
        if ($deckItem['totalPairs'] > 1) {
            $startNum = ($deckItem['pairIdx'] * 2) + 1;
            $endNum = min(($deckItem['pairIdx'] + 1) * 2, count($projectDataList ?? []));
            $metaParts[] = "PROJECTS {$startNum}-{$endNum} OF " . count($projectDataList ?? []);
        }
        if ($deckItem['totalParts'] > 1) {
            $metaParts[] = "PART " . $deckItem['part'] . " OF " . $deckItem['totalParts'];
        }
        $metaStr = implode(' • ', $metaParts);
    @endphp
    <div class="slide">
        <div class="brand-bar-left"></div>

        <!-- Header -->
        <div class="slide-header">
            @if(!empty($logo3Base64))
                <img src="{{ $logo3Base64 }}" class="header-logo3" alt="Logo">
            @endif
            <div class="slide-meta">{{ $metaStr }}</div>
            <div class="slide-title">{{ $slide['title'] }}</div>
        </div>

        <!-- 1-Row Table: Each column bundles col-header-box + item-box together -->
        <table class="items-table">
            <tr>
                @foreach($chunkProjects as $pIdx => $pData)
                @php
                    $globalIdx = ($deckItem['pairIdx'] * 2) + $pIdx;
                    $bColor = $borderColors[$globalIdx % count($borderColors)];
                    $slideKey = $deckItem['slideKey'];
                    $sections = $pData['theme_sections'][$slideKey] ?? [];
                    $points = $pData['theme_points'][$slideKey] ?? [];
                    $hasSections = false;
                    foreach($sections as $s) {
                        if (!empty($s['points'])) { $hasSections = true; break; }
                    }
                @endphp
                <td style="width: {{ $colWidthPct }}; padding: 0 4px; vertical-align: top;">
                    <div class="col-header-box">
                        <div class="proj-name" style="color: {{ $bColor }};">{{ $pData['project_name'] }}</div>
                        <div class="proj-officer">Lead: {{ $pData['officer_name'] }} @if(!empty($pData['location'])) • {{ $pData['location'] }} @endif</div>
                    </div>

                    <div class="item-box">
                        @if($hasSections)
                            @foreach($sections as $itemKey => $sec)
                                <div class="section-block">
                                    <div class="section-title" style="color: {{ $bColor }};">• {{ $sec['title'] }}</div>
                                    @if(!empty($sec['points']))
                                        <ul class="points-list">
                                            @foreach($sec['points'] as $pt)
                                                <li>{!! \App\Models\ActivityEntry::formatPointHtml($pt, true) !!}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if(!empty($sec['media_links']))
                                        <div style="margin-top: 3px; font-size: 7px;">
                                            <strong style="color: #64748b;">Attached:</strong>
                                            @foreach($sec['media_links'] as $mLink)
                                                {!! \App\Models\ActivityEntry::formatPointHtml($mLink, true) !!}
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(!empty($sec['narrative']))
                                        <div style="margin-top: 2px; padding: 2px 4px; background-color: #f8fafc; border-left: 2px solid {{ $bColor }}; font-size: 7.5px; color: #475569; line-height: 1.25;">
                                            <strong style="color: #0f172a; font-size: 7px; text-transform: uppercase;">Summary:</strong> {{ $sec['narrative'] }}
                                        </div>
                                    @elseif(empty($sec['points']) && $deckItem['part'] === 1)
                                        <div class="empty-points">No entries recorded for {{ strtolower($sec['title']) }}.</div>
                                    @endif
                                </div>
                            @endforeach

                            @if(!empty($pData['theme_narrative_text'][$slideKey]))
                                <div style="margin-top: 3px; background: #f1f5f9; border-left: 2.5px solid #64748b; border-radius: 3px; padding: 3px 5px; font-size: 7.5px; color: #334155; line-height: 1.25;">
                                    <strong style="font-size: 7px; color: #475569; text-transform: uppercase;">Executive Synthesis:</strong> {{ $pData['theme_narrative_text'][$slideKey] }}
                                </div>
                            @endif
                        @elseif(!empty($points))
                            <ul class="points-list">
                                @foreach($points as $pt)
                                    <li>{!! \App\Models\ActivityEntry::formatPointHtml($pt, true) !!}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="empty-points">No key presentation points recorded for this period.</div>
                        @endif
                    </div>
                </td>
                @endforeach
            </tr>
        </table>

        <!-- Footer -->
        <div class="slide-footer">
            <div class="footer-left">Play It Forward Zambia • Programmes Meeting</div>
            <div class="footer-right">Slide {{ $slideNum }} of {{ $totalSlides }}</div>
        </div>
    </div>
    @endforeach

    <!-- ==================== FINAL SLIDE: THANK YOU ==================== -->
    <div class="slide thank-you-slide">
        <div class="brand-bar-left"></div>

        @if(!empty($logo3Base64))
            <img src="{{ $logo3Base64 }}" style="height: 48px; margin-bottom: 12px;" alt="Logo">
        @endif

        <div class="ty-title">Thank You!</div>
        <div class="ty-sub">Play It Forward Zambia</div>
        <div class="ty-quote">
            Inspiring and empowering young people and their communities through the power of education, health, and sport.
        </div>
        <div style="font-size: 11px; font-weight: 800; color: #1d4ed8;">
            {{ $quarter }}
        </div>

        <div class="slide-footer" style="margin-top: 20mm;">
            <div class="footer-left">Play It Forward Zambia • Programmes Meeting</div>
            <div class="footer-right">Slide {{ $totalSlides }} of {{ $totalSlides }}</div>
        </div>
    </div>

</body>
</html>
