<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $submission->project_name }} - Programmes Meeting Presentation (Q2 2026)</title>
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
            padding-left: 8mm;
        }
        .cover-logo {
            height: 52px;
            margin-bottom: 12px;
        }
        .cover-title {
            font-size: 40px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }
        .cover-subtitle {
            font-size: 26px;
            font-weight: 700;
            color: #2563eb;
            margin: 0 0 14px 0;
        }
        .cover-badge {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 16px;
            padding: 6px 18px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 20px;
        }
        .project-meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 5px solid #2563eb;
            border-radius: 10px;
            padding: 18px 24px;
            width: 90%;
        }
        .meta-row {
            margin-bottom: 9px;
            font-size: 13px;
        }
        .meta-label {
            font-weight: 800;
            color: #475569;
            width: 150px;
            display: inline-block;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        .meta-val {
            font-weight: 700;
            color: #0f172a;
        }

        /* Slide Header */
        .slide-header {
            margin-bottom: 12px;
            position: relative;
            padding-right: 150px;
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
            margin-bottom: 12px;
        }

        /* 3-Column Items Grid */
        .items-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 14px 0;
            table-layout: fixed;
            margin-left: -7px;
            margin-right: -7px;
        }
        .item-card {
            width: 33.33%;
            vertical-align: top;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }
        .item-bar {
            width: 32px;
            height: 3.5px;
            background-color: #2563eb;
            border-radius: 2px;
            margin-bottom: 6px;
        }
        .item-title {
            font-size: 16.5px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .item-prompt {
            font-size: 10.5px;
            color: #64748b;
            font-style: italic;
            margin-bottom: 12px;
            line-height: 1.35;
        }

        .bullet-list {
            margin: 0;
            padding-left: 16px;
        }
        .bullet-list li {
            font-size: 12px;
            color: #1e293b;
            margin-bottom: 6px;
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
            margin-top: 10px;
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

        $slideIndex = 1;
        $totalPdfSlides = 7;
    @endphp

    <!-- ==================== SLIDE 1: COVER SLIDE ==================== -->
    <div class="slide cover-slide">
        <div class="brand-bar-left"></div>

        <!-- Logo 2 -->
        @if($logo2Base64)
            <img src="{{ $logo2Base64 }}" alt="Play It Forward Zambia" class="cover-logo">
        @endif

        <h1 class="cover-title">{{ $submission->project_name }}</h1>
        <h2 class="cover-subtitle">Programmes Meeting Presentation</h2>
        <div class="cover-badge">{{ $submission->reporting_period ?? 'Quarter 2 April, May, June 2026' }}</div>

        <div class="project-meta-box">
            <div class="meta-row">
                <span class="meta-label">Project:</span>
                <span class="meta-val">{{ $submission->project_name }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Project Officer:</span>
                <span class="meta-val">{{ $submission->officer_name }}</span>
            </div>
            <div class="meta-row" style="margin-bottom: 0;">
                <span class="meta-label">Submitted:</span>
                <span class="meta-val">{{ $submission->created_at ? $submission->created_at->format('d M Y, H:i') : 'Draft' }}</span>
            </div>
        </div>

        <div class="slide-footer" style="margin-top: 20mm;">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">Play It Forward Zambia • Programmes Meeting</td>
                    <td class="footer-right">Cover Slide • Generated {{ $generatedDate }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ==================== 5 THEMATIC SLIDES ==================== -->
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

            <div class="slide-meta">SLIDE {{ $slide['number'] }} OF 5 • {{ $submission->reporting_period ?? 'Quarter 2 2026' }}</div>
            <h2 class="slide-title">{{ $slide['title'] }}</h2>
            <div class="header-rule"></div>
        </div>

        <!-- 3 Items for this Project -->
        <table class="items-grid">
            <tr>
                @foreach($slide['items'] as $itemKey => $item)
                @php
                    $points = $submission->getPoints($itemKey);
                @endphp
                <td class="item-card">
                    <div class="item-bar"></div>
                    <div class="item-title">{{ $item['title'] }}</div>
                    <div class="item-prompt">{{ $item['prompt'] }}</div>

                    @if(!empty($points))
                        <ul class="bullet-list">
                            @foreach($points as $pt)
                                <li>{{ $pt }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div style="font-size: 9.5px; color: #94a3b8; font-style: italic;">No specific points entered.</div>
                    @endif
                </td>
                @endforeach
            </tr>
        </table>

        <!-- Slide Footer -->
        <div class="slide-footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">{{ $submission->project_name }} • {{ $submission->officer_name }}</td>
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
        <h2 class="thank-you-subtitle">{{ $submission->project_name }}</h2>
        <p class="thank-you-text">
            Play It Forward Zambia • {{ $submission->officer_name }}
        </p>
        <div class="cover-badge" style="margin-bottom: 24px;">{{ $submission->reporting_period ?? 'Quarter 2 April, May, June 2026' }}</div>

        <div class="slide-footer" style="margin-top: 15mm;">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">Play It Forward Zambia • Programmes Meeting</td>
                    <td class="footer-right">Slide {{ $totalPdfSlides }} of {{ $totalPdfSlides }}</td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
