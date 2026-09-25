<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Play It Forward Zambia — Live Presentation (Transposed View)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            background-color: #ffffff;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            -webkit-user-select: none;
            user-select: none;
        }

        /* Left Branding Color Bar */
        .brand-bar-left {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 16px;
            background-color: #2563eb;
            z-index: 40;
        }

        .slide-screen {
            position: absolute;
            inset: 0;
            width: 100vw;
            height: 100vh;
            background-color: #ffffff;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Cover Slide Layout */
        .cover-layout {
            padding: 36px 50px 30px 60px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Thematic Slide Layout */
        .thematic-layout {
            padding: 20px 36px 14px 44px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-sizing: border-box;
        }

        /* Top Right Logo 3 */
        .top-right-logo {
            position: absolute;
            top: 14px;
            right: 36px;
            height: 52px;
            max-width: 180px;
            object-fit: contain;
            z-index: 30;
        }

        /* Bottom PowerPoint-style HUD */
        .powerpoint-hud {
            position: fixed;
            bottom: 12px;
            left: 30px;
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(10px);
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            opacity: 0.25;
            transition: opacity 0.25s ease-in-out;
        }
        .powerpoint-hud:hover, .powerpoint-hud.active {
            opacity: 1;
        }
        .hud-btn {
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background-color 0.15s;
            text-decoration: none;
        }
        .hud-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Custom Scrollbar for Projector Tables */
        .slide-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .slide-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 5px;
        }
    </style>
</head>
<body class="bg-white text-slate-900">

    <!-- Full Height Left Blue Accent Bar -->
    <div class="brand-bar-left"></div>

    <!-- ==================== SLIDE 0: COVER SLIDE ==================== -->
    <div id="slide-0" class="slide-screen">
        <div class="cover-layout">
            <div>
                <!-- Logo 2 above the name -->
                <div style="margin-bottom: 16px;">
                    <img src="{{ asset('logos/logo2.png') }}" alt="Play It Forward Zambia Logo" style="height: 75px; max-width: 240px; object-fit: contain;">
                </div>

                <h1 style="font-size: 46px; font-weight: 900; color: #0f172a; margin: 0 0 6px 0; letter-spacing: -1px; line-height: 1.08;">
                    Play It Forward Zambia
                </h1>
                <h2 style="font-size: 28px; font-weight: 800; color: #2563eb; margin: 0 0 10px 0; letter-spacing: -0.5px;">
                    {{ $isSingleProject ? $singleProject->name : 'Programmes Meeting' }}
                </h2>
                <div style="font-size: 18px; font-weight: 700; color: #1d4ed8; margin-bottom: 22px;">
                    {{ $quarter }}
                </div>

                <div style="background-color: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 18px 26px; width: 92%; max-width: 1400px;">
                    <div style="font-size: 14px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 10px;">
                        Compiled Active Projects ({{ count($projectDataList) }} Active Projects):
                    </div>
                    <ul style="list-style-type: disc !important; list-style-position: outside; margin: 0; padding-left: 20px; display: flex; flex-direction: column; gap: 6px;">
                        @foreach($projectDataList as $pData)
                            <li style="list-style-type: disc !important; font-size: 16px; color: #1e293b; font-weight: 700; line-height: 1.35;">
                                {{ $pData['project_name'] }} <span style="color: #64748b; font-weight: 600;">({{ $pData['officer_name'] }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== SLIDES 1 to 5: THEMATIC FULL SLIDES (TRANSPOSED MATRIX) ==================== -->
    @php
        $slideIndex = 1;
        $projectColors = [
            [
                'border' => '#2563eb', // Royal Blue
                'badge_bg' => '#eff6ff',
                'badge_text' => '#1d4ed8',
                'badge_border' => '#93c5fd',
            ],
            [
                'border' => '#059669', // Emerald Green
                'badge_bg' => '#ecfdf5',
                'badge_text' => '#047857',
                'badge_border' => '#6ee7b7',
            ],
            [
                'border' => '#7c3aed', // Purple / Violet
                'badge_bg' => '#f5f3ff',
                'badge_text' => '#6d28d9',
                'badge_border' => '#c4b5fd',
            ],
            [
                'border' => '#d97706', // Amber / Orange
                'badge_bg' => '#fffbeb',
                'badge_text' => '#b45309',
                'badge_border' => '#fcd34d',
            ],
            [
                'border' => '#0891b2', // Teal / Cyan
                'badge_bg' => '#ecfeff',
                'badge_text' => '#0e7490',
                'badge_border' => '#67e8f9',
            ],
            [
                'border' => '#e11d48', // Rose / Red
                'badge_bg' => '#fff1f2',
                'badge_text' => '#be123c',
                'badge_border' => '#fda4af',
            ],
        ];
    @endphp

    @foreach($slidesConfig as $slideKey => $slide)
    <div id="slide-{{ $slideIndex }}" class="slide-screen" style="display: none;">
        <div class="thematic-layout">
            <!-- Top Right Logo 3 -->
            <img src="{{ asset('logos/logo3.png') }}" alt="Play It Forward" class="top-right-logo">

            <!-- Header -->
            <div style="margin-bottom: 6px;">
                <div style="font-size: 11.5px; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">
                    SLIDE {{ $slide['number'] }} OF 5 • {{ strtoupper($quarter) }}
                </div>
                <h2 style="font-size: 26px; font-weight: 900; color: #0f172a; margin: 0 0 3px 0; letter-spacing: -0.5px; max-width: 80%;">
                    {{ $slide['title'] }}
                </h2>
                <div style="width: 44px; height: 3.5px; background-color: #2563eb; border-radius: 2px;"></div>
            </div>

            <!-- TRANSPOSED MATRIX TABLE: Columns = Projects (X-axis), Rows = Thematic Presentation Points -->
            <div class="slide-scroll" style="flex: 1; overflow-y: auto; padding-right: 6px;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 12px 8px; table-layout: fixed; margin-left: -6px; margin-right: -6px;">
                    <!-- Column Header Cards (X-Axis: Project Names & Leads) -->
                    <thead>
                        <tr>
                            @foreach($projectDataList as $pIdx => $pData)
                            @php
                                $color = $projectColors[$pIdx % count($projectColors)];
                            @endphp
                            <th style="vertical-align: top; background: #ffffff; border: 1.5px solid #cbd5e1; border-top: 5px solid {{ $color['border'] }}; border-radius: 10px; padding: 10px 14px; text-align: left; box-sizing: border-box;">
                                <div style="font-size: 16px; font-weight: 900; color: #0f172a; margin-bottom: 2px;">
                                    {{ $pData['project_name'] }}
                                </div>
                                <div style="display: flex; items-center; justify-content: space-between; gap: 4px;">
                                    <span style="font-size: 11.5px; font-weight: 700; color: {{ $color['badge_text'] }};">
                                        Lead: {{ $pData['officer_name'] }}
                                    </span>
                                    <span style="font-size: 10.5px; color: #64748b; font-weight: 600;">
                                        {{ $pData['location'] }}
                                    </span>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Transposed Content Row: Each project's discrete sections & qualitative narrative side by side -->
                        <tr>
                            @foreach($projectDataList as $pIdx => $pData)
                            @php
                                $color = $projectColors[$pIdx % count($projectColors)];
                                $sections = $pData['theme_sections'][$slideKey] ?? [];
                                $points = $pData['theme_points'][$slideKey] ?? [];
                                $narrativeText = $pData['theme_narrative_text'][$slideKey] ?? '';
                                $hasSections = false;
                                foreach($sections as $s) {
                                    if (!empty($s['points'])) { $hasSections = true; break; }
                                }
                            @endphp
                            <td style="vertical-align: top; background: #ffffff; border: 1px solid #e2e8f0; border-left: 5px solid {{ $color['border'] }}; border-radius: 9px; padding: 12px 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); box-sizing: border-box; word-break: break-word; overflow-wrap: anywhere;">
                                @if($hasSections)
                                    <div style="display: flex; flex-direction: column; gap: 10px;">
                                        @foreach($sections as $itemKey => $sec)
                                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 7px; padding: 8px 10px;">
                                                <div style="font-size: 12.5px; font-weight: 800; color: {{ $color['border'] }}; margin-bottom: 4px; display: flex; align-items: center; gap: 5px;">
                                                    <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: {{ $color['border'] }};"></span>
                                                    <span>{{ $sec['title'] }}</span>
                                                </div>
                                                @if(!empty($sec['points']))
                                                    <ul style="list-style-type: disc !important; list-style-position: outside; margin: 0; padding-left: 16px; display: flex; flex-direction: column; gap: 4px;">
                                                        @foreach($sec['points'] as $pt)
                                                            <li style="list-style-type: disc !important; font-size: 12px; color: #1e293b; font-weight: 500; line-height: 1.35;">
                                                                {!! \App\Models\ActivityEntry::formatPointHtml($pt, false) !!}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div style="font-size: 11px; color: #94a3b8; font-style: italic;">
                                                        No points recorded for {{ strtolower($sec['title']) }}.
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(!empty($points))
                                    <ul style="list-style-type: disc !important; list-style-position: outside; margin: 0; padding-left: 18px; display: flex; flex-direction: column; gap: 8px;">
                                        @foreach($points as $pt)
                                            <li style="list-style-type: disc !important; font-size: 13px; color: #1e293b; font-weight: 500; line-height: 1.4; word-break: break-word; overflow-wrap: anywhere;">
                                                {!! \App\Models\ActivityEntry::formatPointHtml($pt, false) !!}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div style="font-size: 12px; color: #94a3b8; font-style: italic; padding: 6px 0;">
                                        No key presentation points recorded for this period.
                                    </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div style="padding-top: 6px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 11px; font-weight: 600; color: #64748b;">
                <span>Play It Forward Zambia • {{ $isSingleProject ? $singleProject->name : 'Programmes Meeting' }}</span>
                <span>Slide {{ $slideIndex + 1 }} of 7</span>
            </div>
        </div>
    </div>
    @php $slideIndex++; @endphp
    @endforeach

    <!-- ==================== SLIDE 6: THANK YOU ENDING SLIDE ==================== -->
    <div id="slide-6" class="slide-screen" style="display: none; align-items: center; justify-content: center;">
        <div style="width: 100%; max-width: 900px; padding: 30px 40px; margin: auto; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <img src="{{ asset('logos/logo2.png') }}" alt="Play It Forward Zambia" style="height: 80px; max-width: 260px; object-fit: contain; margin: 0 auto 20px auto; display: block;">

            <h1 style="font-size: 52px; font-weight: 900; color: #0f172a; margin: 0 auto 8px auto; letter-spacing: -1px; line-height: 1.05; text-align: center;">
                Thank You!
            </h1>
            <h2 style="font-size: 26px; font-weight: 800; color: #2563eb; margin: 0 auto 12px auto; text-align: center;">
                Play It Forward Zambia
            </h2>
            <p style="font-size: 16px; color: #475569; max-width: 650px; line-height: 1.5; margin: 0 auto 24px auto; text-align: center;">
                Inspiring and empowering young people and their communities through the power of education, health, and sport.
            </p>
            <div style="font-size: 15px; font-weight: 800; color: #1d4ed8; text-align: center;">
                {{ $quarter }}
            </div>
        </div>
    </div>

    <!-- ==================== FLOATING PRESENTATION HUD ==================== -->
    <div id="hud" class="powerpoint-hud">
        <button type="button" class="hud-btn" onclick="prevSlide()" title="Previous Slide (Left Arrow)">◀</button>
        <span id="slide-indicator" style="color: #ffffff; font-size: 11px; font-weight: 700; padding: 0 4px;">1 / 7</span>
        <button type="button" class="hud-btn" onclick="nextSlide()" title="Next Slide (Right Arrow or Space)">▶</button>
        <span style="color: rgba(255,255,255,0.3);">|</span>
        <button type="button" class="hud-btn" onclick="toggleFullscreen()" title="Toggle Fullscreen (F)">⛶ Fullscreen</button>
        <a href="{{ route('programmes.hub') }}" class="hud-btn" style="color: #f87171;" title="Exit Presentation (Esc)">✕ Exit Hub</a>
    </div>

    <script>
        let currentSlide = 0;
        const totalSlides = 7;

        function showSlide(index) {
            if (index < 0) index = 0;
            if (index >= totalSlides) index = totalSlides - 1;

            for (let i = 0; i < totalSlides; i++) {
                const el = document.getElementById('slide-' + i);
                if (el) {
                    el.style.display = (i === index) ? (i === 6 ? 'flex' : 'flex') : 'none';
                }
            }

            currentSlide = index;
            document.getElementById('slide-indicator').textContent = (currentSlide + 1) + ' / ' + totalSlides;
        }

        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                showSlide(currentSlide + 1);
            }
        }

        function prevSlide() {
            if (currentSlide > 0) {
                showSlide(currentSlide - 1);
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log('Error attempting to enable fullscreen:', err.message);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Keyboard Navigation
        document.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight' || e.key === ' ' || e.key === 'PageDown') {
                e.preventDefault();
                nextSlide();
            } else if (e.key === 'ArrowLeft' || e.key === 'PageUp' || e.key === 'Backspace') {
                e.preventDefault();
                prevSlide();
            } else if (e.key === 'Home') {
                e.preventDefault();
                showSlide(0);
            } else if (e.key === 'End') {
                e.preventDefault();
                showSlide(totalSlides - 1);
            } else if (e.key === 'f' || e.key === 'F') {
                toggleFullscreen();
            } else if (e.key === 'Escape') {
                window.location.href = "{{ route('programmes.hub') }}";
            }
        });

        // Initialize slide 0
        showSlide(0);
    </script>
</body>
</html>
