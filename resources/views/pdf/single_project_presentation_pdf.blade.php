<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $submission->project_name }} - Programmes Meeting Presentation (Q2 2026)</title>
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
            font-size: 10.5px;
            line-height: 1.38;
        }

        .slide {
            width: 100%;
            page-break-after: always;
            page-break-inside: avoid;
            position: relative;
            background-color: #ffffff;
            padding-bottom: 8px;
        }
        .slide:last-child {
            page-break-after: avoid;
        }

        /* Left Branding Color Bar */
        .brand-bar-left {
            position: absolute;
            left: -14mm;
            top: -8mm;
            bottom: -8mm;
            width: 5mm;
            background-color: #2563eb;
        }

        /* Cover Slide */
        .cover-slide {
            padding-top: 14mm;
            padding-left: 6mm;
        }
        .cover-logo {
            height: 44px;
            margin-bottom: 10px;
        }
        .cover-title {
            font-size: 34px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 5px 0;
            letter-spacing: -0.5px;
        }
        .cover-subtitle {
            font-size: 22px;
            font-weight: 700;
            color: #2563eb;
            margin: 0 0 12px 0;
        }
        .cover-badge {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            padding: 5px 16px;
            font-size: 11.5px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 16px;
        }
        .project-meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #2563eb;
            border-radius: 8px;
            padding: 14px 20px;
            width: 90%;
        }
        .meta-row {
            margin-bottom: 6px;
            font-size: 11.5px;
        }
        .meta-label {
            font-weight: 800;
            color: #475569;
            width: 140px;
            display: inline-block;
            text-transform: uppercase;
            font-size: 9.5px;
            letter-spacing: 0.5px;
        }
        .meta-val {
            font-weight: 700;
            color: #0f172a;
        }

        /* Slide Header */
        .slide-header {
            margin-bottom: 8px;
            position: relative;
            padding-right: 120px;
        }
        .header-logo3 {
            position: absolute;
            right: 0;
            top: -2px;
            height: 34px;
        }
        .slide-meta {
            font-size: 10px;
            font-weight: 800;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .slide-title {
            font-size: 22px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 3px 0;
            letter-spacing: -0.5px;
        }
        .header-rule {
            width: 42px;
            height: 3px;
            background-color: #2563eb;
            border-radius: 2px;
            margin-bottom: 8px;
        }

        /* 3-Column Items Grid */
        .items-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            table-layout: fixed;
            margin-left: -5px;
            margin-right: -5px;
        }
        .item-card {
            width: 33.33%;
            vertical-align: top;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .item-bar {
            width: 24px;
            height: 3px;
            background-color: #2563eb;
            border-radius: 1.5px;
            margin-bottom: 4px;
        }
        .item-title {
            font-size: 13.5px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .item-prompt {
            font-size: 9px;
            color: #64748b;
            font-style: italic;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .bullet-list {
            margin: 0;
            padding-left: 12px;
        }
        .bullet-list li {
            font-size: 9.5px;
            color: #1e293b;
            margin-bottom: 3.5px;
            line-height: 1.4;
            font-weight: 500;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* Thank You Slide */
        .thank-you-slide {
            padding-top: 22mm;
            text-align: center;
        }
        .thank-you-title {
            font-size: 38px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }
        .thank-you-subtitle {
            font-size: 20px;
            font-weight: 800;
            color: #2563eb;
            margin: 0 0 10px 0;
        }
        .thank-you-text {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            max-width: 550px;
            margin: 0 auto 16px auto;
            line-height: 1.4;
        }

        /* Slide Footer */
        .slide-footer {
            margin-top: 6px;
            border-top: 1px solid #e2e8f0;
            padding-top: 3px;
            font-size: 8px;
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

        <div class="slide-footer" style="margin-top: 14mm;">
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
                                <li>{!! \App\Models\ProjectSubmission::formatPointHtml($pt, true) !!}</li>
                            @endforeach
                        </ul>
                    @else
                        <div style="font-size: 8px; color: #94a3b8; font-style: italic;">No specific points entered.</div>
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
