<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Play It Forward Zambia - Consolidated Programmes Meeting (Q2 2026)</title>
    <style>
        @page {
            size: 297mm 210mm landscape;
            margin: 10mm 14mm 10mm 16mm;
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
            font-size: 12.5px;
            line-height: 1.45;
        }

        .slide {
            width: 100%;
            page-break-after: always;
            page-break-inside: avoid;
            position: relative;
            background-color: #ffffff;
            padding-bottom: 14px;
        }
        .slide:last-child {
            page-break-after: avoid;
        }

        /* Left Branding Color Bar */
        .brand-bar-left {
            position: absolute;
            left: -16mm;
            top: -10mm;
            bottom: -10mm;
            width: 7mm;
            background-color: #2563eb;
        }

        /* Cover Slide */
        .cover-slide {
            padding-top: 16mm;
            padding-left: 10mm;
        }
        .cover-logo {
            height: 52px;
            margin-bottom: 12px;
        }
        .cover-title {
            font-size: 42px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }
        .cover-subtitle {
            font-size: 28px;
            font-weight: 700;
            color: #2563eb;
            margin: 0 0 14px 0;
        }
        .cover-badge {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1.5px solid #bfdbfe;
            border-radius: 20px;
            padding: 6px 18px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 20px;
        }
        .cover-projects-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 20px;
            width: 95%;
        }
        .cover-projects-title {
            font-size: 13px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .project-tag {
            display: inline-block;
            background-color: #ffffff;
            color: #1e293b;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 5px 12px;
            font-size: 13px;
            font-weight: 700;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        /* Slide Header */
        .slide-header {
            margin-bottom: 10px;
            position: relative;
            padding-right: 150px; /* Space for larger logo3 */
        }
        .header-logo3 {
            position: absolute;
            right: 0;
            top: -3px;
            height: 44px;
        }
        .slide-meta {
            font-size: 13px;
            font-weight: 800;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .slide-title {
            font-size: 30px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }
        .header-rule {
            width: 52px;
            height: 4px;
            background-color: #2563eb;
            border-radius: 2px;
            margin-bottom: 10px;
        }

        /* Synchronized Table Layout for 3 Items across Projects */
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 10px;
            table-layout: fixed;
            margin-left: -6px;
            margin-right: -6px;
        }

        .col-header {
            width: 33.33%;
            vertical-align: top;
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 14px;
            text-align: left;
        }
        .col-bar {
            width: 32px;
            height: 3.5px;
            background-color: #2563eb;
            border-radius: 2px;
            margin-bottom: 6px;
        }
        .col-title {
            font-size: 16.5px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .col-prompt {
            font-size: 10.5px;
            color: #64748b;
            font-style: italic;
            line-height: 1.35;
        }

        /* Project Cells in the Synchronized Row */
        .project-cell {
            width: 33.33%;
            vertical-align: top;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
        }

        .project-badge {
            font-size: 12px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 6px;
        }
        .officer-text {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-left: 5px;
        }

        .bullet-list {
            margin: 0;
            padding-left: 14px;
        }
        .bullet-list li {
            font-size: 12px;
            color: #1e293b;
            margin-bottom: 4px;
            line-height: 1.45;
            font-weight: 500;
        }

        /* Thank You Slide */
        .thank-you-slide {
            padding-top: 30mm;
            text-align: center;
        }
        .thank-you-title {
            font-size: 46px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 8px 0;
            letter-spacing: -1px;
        }
        .thank-you-subtitle {
            font-size: 26px;
            font-weight: 800;
            color: #2563eb;
            margin: 0 0 14px 0;
        }
        .thank-you-text {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            max-width: 650px;
            margin: 0 auto 20px auto;
            line-height: 1.5;
        }

        /* Slide Footer */
        .slide-footer {
            margin-top: 8px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            font-size: 8.5px;
            color: #94a3b8;
        }
        .footer-table {
            width: 100%;
        }
        .footer-left {
            text-align: left;
            font-weight: 600;
        }
        .footer-right {
            text-align: right;
        }
    </style>
</head>
<body>

    @php
        $logo2Path = public_path('logos/logo2.png');
        $logo2Base64 = file_exists($logo2Path) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo2Path)) : '';

        $logo3Path = public_path('logos/logo3.png');
        $logo3Base64 = file_exists($logo3Path) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo3Path)) : '';

        $projectColors = [
            [
                'border' => '#2563eb', // Royal Blue
                'badge_bg' => '#eff6ff',
                'badge_text' => '#1d4ed8',
                'badge_border' => '#bfdbfe',
            ],
            [
                'border' => '#059669', // Emerald Green
                'badge_bg' => '#ecfdf5',
                'badge_text' => '#047857',
                'badge_border' => '#a7f3d0',
            ],
            [
                'border' => '#7c3aed', // Purple / Violet
                'badge_bg' => '#f5f3ff',
                'badge_text' => '#6d28d9',
                'badge_border' => '#ddd6fe',
            ],
            [
                'border' => '#d97706', // Amber / Orange
                'badge_bg' => '#fffbeb',
                'badge_text' => '#b45309',
                'badge_border' => '#fde68a',
            ],
            [
                'border' => '#0891b2', // Teal / Cyan
                'badge_bg' => '#ecfeff',
                'badge_text' => '#0e7490',
                'badge_border' => '#a5f3fc',
            ],
            [
                'border' => '#e11d48', // Rose / Red
                'badge_bg' => '#fff1f2',
                'badge_text' => '#be123c',
                'badge_border' => '#fecdd3',
            ],
        ];
        $slideIndex = 1;
        $totalPdfSlides = 7;
    @endphp

    <!-- ==================== SLIDE 1: COVER SLIDE ==================== -->
    <div class="slide cover-slide">
        <div class="brand-bar-left"></div>
        
        <!-- Logo 2 above the name -->
        @if($logo2Base64)
            <img src="{{ $logo2Base64 }}" alt="Play It Forward Zambia" class="cover-logo">
        @endif

        <h1 class="cover-title">{{ $meetingTitle }}</h1>
        <h2 class="cover-subtitle">{{ $meetingSubtitle }}</h2>
        <div class="cover-badge">{{ $quarter }}</div>

        <div class="cover-projects-box">
            <div class="cover-projects-title">Compiled Project Submissions ({{ $submissions->count() }} Active Projects)</div>
            <div>
                @foreach($submissions as $projIndex => $sub)
                    @php
                        $color = $projectColors[$projIndex % count($projectColors)];
                    @endphp
                    <span class="project-tag" style="border-left: 3.5px solid {{ $color['border'] }};">
                        <strong style="color: {{ $color['badge_text'] }};">{{ $sub->project_name }}</strong> 
                        <span style="color: #64748b; font-weight: normal;">({{ $sub->officer_name }})</span>
                    </span>
                @endforeach
            </div>
        </div>

        <div class="slide-footer" style="margin-top: 18mm;">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">Play It Forward Zambia • Consolidated Programmes Meeting</td>
                    <td class="footer-right">Cover Slide • Generated {{ $generatedDate }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ==================== 5 CONSOLIDATED THEMATIC SLIDES ==================== -->
    @foreach($slidesConfig as $slideKey => $slide)
    @php
        $slideIndex++;
    @endphp
    <div class="slide">
        <div class="brand-bar-left"></div>

        <!-- Slide Header -->
        <div class="slide-header">
            <!-- Top Right Logo 3 -->
            @if($logo3Base64)
                <img src="{{ $logo3Base64 }}" alt="Play It Forward" class="header-logo3">
            @endif

            <div class="slide-meta">SLIDE {{ $slide['number'] }} OF 5 • {{ $quarter }}</div>
            <h2 class="slide-title">{{ $slide['title'] }}</h2>
            <div class="header-rule"></div>
        </div>

        <!-- Synchronized Table across 3 Items and all Projects -->
        <table class="items-table">
            <!-- 3 Column Headers matching the slide items -->
            <thead>
                <tr>
                    @foreach($slide['items'] as $itemKey => $item)
                    <th class="col-header">
                        <div class="col-bar"></div>
                        <div class="col-title">{{ $item['title'] }}</div>
                        <div class="col-prompt">{{ $item['prompt'] }}</div>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <!-- Synchronized Project Rows: the cell with most details sets the demarcation baseline! -->
                @foreach($submissions as $projIndex => $sub)
                    @php
                        $color = $projectColors[$projIndex % count($projectColors)];
                    @endphp
                    <tr>
                        @foreach($slide['items'] as $itemKey => $item)
                        @php
                            $points = $sub->getPoints($itemKey);
                        @endphp
                        <td class="project-cell" style="border-left: 4px solid {{ $color['border'] }};">
                            <!-- Project Badge with Distinct Color -->
                            <div>
                                <span class="project-badge" style="background-color: {{ $color['badge_bg'] }}; color: {{ $color['badge_text'] }}; border: 1px solid {{ $color['badge_border'] }};">
                                    {{ $sub->project_name }}
                                </span>
                                <span class="officer-text">• {{ $sub->officer_name }}</span>
                            </div>

                            <!-- Item Points for this Project -->
                            @if(!empty($points))
                                <ul class="bullet-list">
                                    @foreach($points as $pt)
                                        <li>{{ $pt }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div style="font-size: 9px; color: #94a3b8; font-style: italic; padding-left: 2px;">No key points submitted.</div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Slide Footer -->
        <div class="slide-footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">Play It Forward Zambia • Programmes Meeting</td>
                    <td class="footer-right">Slide {{ $slideIndex }} of {{ $totalPdfSlides }}</td>
                </tr>
            </table>
        </div>
    </div>
    @endforeach

    <!-- ==================== SLIDE 7: THANK YOU ENDING SLIDE ==================== -->
    <div class="slide thank-you-slide">
        <div class="brand-bar-left"></div>

        @if($logo2Base64)
            <img src="{{ $logo2Base64 }}" alt="Play It Forward Zambia" style="height: 56px; margin-bottom: 16px;">
        @endif

        <h1 class="thank-you-title">Thank You!</h1>
        <h2 class="thank-you-subtitle">Play It Forward Zambia</h2>
        <p class="thank-you-text">
            Inspiring and empowering young people and their communities through the power of education, health, and sport.
        </p>
        <div class="cover-badge" style="margin-bottom: 24px;">{{ $quarter }}</div>

        <div class="slide-footer" style="margin-top: 15mm;">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">Play It Forward Zambia • Consolidated Programmes Meeting</td>
                    <td class="footer-right">Slide {{ $totalPdfSlides }} of {{ $totalPdfSlides }}</td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
