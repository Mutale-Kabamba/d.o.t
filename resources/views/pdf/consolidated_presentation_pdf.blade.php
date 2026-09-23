<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Play It Forward Zambia - Consolidated Programmes Meeting Presentation</title>
    <style>
        @page {
            size: 297mm 210mm landscape;
            margin: 7mm 10mm 7mm 14mm;
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
            font-size: 10px;
            line-height: 1.35;
        }

        .slide {
            width: 100%;
            page-break-after: always;
            page-break-inside: avoid;
            position: relative;
            background-color: #ffffff;
            padding-bottom: 6px;
        }
        .slide:last-child {
            page-break-after: avoid;
        }

        /* Left Branding Color Bar */
        .brand-bar-left {
            position: absolute;
            left: -14mm;
            top: -7mm;
            bottom: -7mm;
            width: 5mm;
            background-color: #2563eb;
        }

        /* Cover Slide */
        .cover-slide {
            padding-top: 12mm;
            padding-left: 6mm;
        }
        .cover-logo {
            height: 42px;
            margin-bottom: 8px;
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
            border-radius: 14px;
            padding: 4px 14px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 16px;
        }
        .cover-projects-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 16px;
            width: 96%;
        }
        .cover-projects-title {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .project-tag {
            display: inline-block;
            background-color: #ffffff;
            color: #1e293b;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            padding: 3px 8px;
            font-size: 10.5px;
            font-weight: 700;
            margin-right: 6px;
            margin-bottom: 6px;
        }

        /* Slide Header */
        .slide-header {
            margin-bottom: 6px;
            position: relative;
            padding-right: 120px;
        }
        .header-logo3 {
            position: absolute;
            right: 0;
            top: -2px;
            height: 30px;
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
            font-size: 19px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 2px 0;
            letter-spacing: -0.5px;
        }
        .header-rule {
            width: 36px;
            height: 2.5px;
            background-color: #2563eb;
            border-radius: 2px;
            margin-bottom: 6px;
        }

        /* TRANSPOSED TABLE LAYOUT (Columns = Projects, Rows = Content) */
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            table-layout: fixed;
            margin-left: -4px;
            margin-right: -4px;
        }

        .col-header {
            vertical-align: top;
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 9px;
            text-align: left;
        }
        .proj-name {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .proj-officer {
            font-size: 9.5px;
            font-weight: 700;
            color: #2563eb;
        }

        .item-cell {
            vertical-align: top;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 9.5px;
            word-break: break-word;
        }

        .points-list {
            margin: 0;
            padding-left: 14px;
        }
        .points-list li {
            margin-bottom: 4px;
            color: #1e293b;
            line-height: 1.35;
        }
        .empty-points {
            font-size: 9px;
            color: #94a3b8;
            font-style: italic;
        }

        /* Slide Footer */
        .slide-footer {
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1px solid #e2e8f0;
            font-size: 8.5px;
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
            font-size: 38px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 6px 0;
        }
        .ty-sub {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
            margin: 0 0 12px 0;
        }
        .ty-quote {
            font-size: 11px;
            color: #475569;
            max-width: 600px;
            margin: 0 auto 16px auto;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <!-- ==================== SLIDE 0: COVER SLIDE ==================== -->
    <div class="slide cover-slide">
        <div class="brand-bar-left"></div>

        @if(file_exists(public_path('logos/logo2.png')))
            <img src="{{ public_path('logos/logo2.png') }}" class="cover-logo" alt="Logo">
        @endif

        <div class="cover-title">{{ $meetingTitle ?? 'Play It Forward Zambia' }}</div>
        <div class="cover-subtitle">{{ $meetingSubtitle ?? 'Programmes Meeting' }}</div>
        <div class="cover-badge">{{ $quarter }}</div>

        <div class="cover-projects-box">
            <div class="cover-projects-title">Active Projects Compiled ({{ count($projectDataList) }} Active Projects):</div>
            <div>
                @foreach($projectDataList as $pData)
                    <div class="project-tag">
                        <strong>{{ $pData['project_name'] }}</strong> ({{ $pData['officer_name'] }})
                    </div>
                @endforeach
            </div>
        </div>

        <div class="slide-footer" style="margin-top: 20mm;">
            <div class="footer-left">Play It Forward Zambia • Programmes Meeting</div>
            <div class="footer-right">Generated: {{ $generatedDate }} • Slide 1 of 7</div>
        </div>
    </div>

    <!-- ==================== SLIDES 1 to 5: 5 THEMATIC SLIDES (TRANSPOSED MATRIX) ==================== -->
    @php
        $slideNum = 1;
        $borderColors = ['#2563eb', '#059669', '#7c3aed', '#d97706', '#0891b2', '#e11d48'];
        $projCount = max(1, count($projectDataList));
        $colWidthPct = floor(100 / $projCount) . '%';
    @endphp

    @foreach($slidesConfig as $slideKey => $slide)
    <div class="slide">
        <div class="brand-bar-left"></div>

        <!-- Header -->
        <div class="slide-header">
            @if(file_exists(public_path('logos/logo3.png')))
                <img src="{{ public_path('logos/logo3.png') }}" class="header-logo3" alt="Logo">
            @endif
            <div class="slide-meta">SLIDE {{ $slide['number'] }} OF 5 • {{ strtoupper($quarter) }}</div>
            <div class="slide-title">{{ $slide['title'] }}</div>
            <div class="header-rule"></div>
        </div>

        <!-- TRANSPOSED TABLE: Columns = Projects (X-axis), Rows = Points -->
        <table class="items-table">
            <thead>
                <tr>
                    @foreach($projectDataList as $pIdx => $pData)
                    @php
                        $bColor = $borderColors[$pIdx % count($borderColors)];
                    @endphp
                    <th class="col-header" style="width: {{ $colWidthPct }}; border-top: 4px solid {{ $bColor }};">
                        <div class="proj-name">{{ $pData['project_name'] }}</div>
                        <div class="proj-officer">Lead: {{ $pData['officer_name'] }}</div>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($projectDataList as $pIdx => $pData)
                    @php
                        $bColor = $borderColors[$pIdx % count($borderColors)];
                        $points = $pData['theme_points'][$slideKey] ?? [];
                    @endphp
                    <td class="item-cell" style="width: {{ $colWidthPct }}; border-left: 3.5px solid {{ $bColor }};">
                        @if(!empty($points))
                            <ul class="points-list">
                                @foreach($points as $pt)
                                    <li>{!! \App\Models\ActivityEntry::formatPointHtml($pt, true) !!}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="empty-points">No key presentation points recorded.</div>
                        @endif
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="slide-footer">
            <div class="footer-left">Play It Forward Zambia • Programmes Meeting</div>
            <div class="footer-right">Slide {{ $slideNum + 1 }} of 7</div>
        </div>
    </div>
    @php $slideNum++; @endphp
    @endforeach

    <!-- ==================== SLIDE 6: THANK YOU SLIDE ==================== -->
    <div class="slide thank-you-slide">
        <div class="brand-bar-left"></div>

        @if(file_exists(public_path('logos/logo2.png')))
            <img src="{{ public_path('logos/logo2.png') }}" style="height: 48px; margin-bottom: 12px;" alt="Logo">
        @endif

        <div class="ty-title">Thank You!</div>
        <div class="ty-sub">Play It Forward Zambia</div>
        <div class="ty-quote">
            Inspiring and empowering young people and their communities through the power of education, health, and sport.
        </div>
        <div style="font-size: 11px; font-weight: 800; color: #1d4ed8;">
            {{ $quarter }}
        </div>

        <div class="slide-footer" style="margin-top: 25mm;">
            <div class="footer-left">Play It Forward Zambia • Programmes Meeting</div>
            <div class="footer-right">Slide 7 of 7</div>
        </div>
    </div>

</body>
</html>
