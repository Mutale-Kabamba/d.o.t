<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Play It Forward Zambia — Live Presentation</title>
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
            width: 14px;
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
            padding: 16px 36px 12px 42px;
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
            height: 46px;
            max-width: 160px;
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
                <div style="margin-bottom: 20px;">
                    <img src="{{ asset('logos/logo2.png') }}" alt="Play It Forward Zambia Logo" style="height: 85px; max-width: 260px; object-fit: contain;">
                </div>

                <h1 style="font-size: 52px; font-weight: 900; color: #0f172a; margin: 0 0 8px 0; letter-spacing: -1.2px; line-height: 1.08;">
                    Play It Forward Zambia
                </h1>
                <h2 style="font-size: 32px; font-weight: 800; color: #2563eb; margin: 0 0 12px 0; letter-spacing: -0.5px;">
                    {{ $isSingleProject ? $singleProject->name : 'Programmes Meeting' }}
                </h2>
                <div style="font-size: 20px; font-weight: 700; color: #1d4ed8; margin-bottom: 24px;">
                    {{ $quarter }}
                </div>

                <div style="background-color: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 14px; padding: 22px 28px; width: 94%; max-width: 1400px;">
                    <div style="font-size: 15px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 12px;">
                        Compiled Active Projects ({{ count($projectDataList) }} Active Projects • 2 Projects Per Slide):
                    </div>
                    <ul style="list-style-type: disc !important; list-style-position: outside; margin: 0; padding-left: 24px; display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 10px;">
                        @foreach($projectDataList as $pData)
                            <li style="list-style-type: disc !important; font-size: 17px; color: #1e293b; font-weight: 700; line-height: 1.35;">
                                {{ $pData['project_name'] }} <span style="color: #64748b; font-weight: 600;">({{ $pData['officer_name'] }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== THEMATIC PILLAR SLIDES (2 PROJECTS PER SLIDE) ==================== -->
    @php
        $projectChunks = !empty($projectDataList) ? array_chunk($projectDataList, 2) : [[]];
        $totalChunks = count($projectChunks);
        $totalPillarSlides = count($slidesConfig) * $totalChunks;
        $totalSlides = 1 + $totalPillarSlides + 1; // Cover + Pillar slides + Thank You
        $slideIndex = 1;

        $projectColors = [
            ['title' => '#2563eb', 'badge_bg' => '#eff6ff', 'badge_border' => '#bfdbfe'], // Royal Blue
            ['title' => '#059669', 'badge_bg' => '#ecfdf5', 'badge_border' => '#a7f3d0'], // Emerald Green
            ['title' => '#7c3aed', 'badge_bg' => '#f5f3ff', 'badge_border' => '#ddd6fe'], // Purple / Violet
            ['title' => '#d97706', 'badge_bg' => '#fffbeb', 'badge_border' => '#fde68a'], // Amber / Orange
            ['title' => '#0891b2', 'badge_bg' => '#ecfeff', 'badge_border' => '#a5f3fc'], // Teal / Cyan
            ['title' => '#e11d48', 'badge_bg' => '#fff1f2', 'badge_border' => '#fecdd3'], // Rose / Red
        ];
    @endphp

    @foreach($slidesConfig as $slideKey => $slide)
        @foreach($projectChunks as $chunkIdx => $chunkProjects)
        <div id="slide-{{ $slideIndex }}" class="slide-screen" style="display: none;">
            <div class="thematic-layout">
                <!-- Top Right Logo -->
                <img src="{{ asset('logos/logo3.png') }}" alt="Play It Forward" class="top-right-logo">

                <!-- Header -->
                <div style="margin-bottom: 14px;">
                    <div style="font-size: 14px; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px;">
                        PILLAR {{ $slide['number'] }} OF 5 • {{ strtoupper($quarter) }}@if($totalChunks > 1) • PART {{ $chunkIdx + 1 }} OF {{ $totalChunks }}@endif
                    </div>
                    <h1 style="font-size: 38px; font-weight: 900; color: #0f172a; margin: 0; letter-spacing: -0.8px; line-height: 1.1;">
                        {{ $slide['title'] }}
                    </h1>
                </div>

                <!-- 2-PROJECT CARDS GRID (Columns = 2 Projects Max) -->
                <div style="display: grid; grid-template-columns: repeat({{ count($chunkProjects) === 1 ? 1 : 2 }}, minmax(0, 1fr)); gap: 18px; flex: 1; min-height: 0;">
                    @foreach($chunkProjects as $pIdx => $pData)
                    @php
                        $globalIdx = ($chunkIdx * 2) + $pIdx;
                        $color = $projectColors[$globalIdx % count($projectColors)];
                        $sections = $pData['theme_sections'][$slideKey] ?? [];
                        $points = $pData['theme_points'][$slideKey] ?? [];
                        $hasSections = false;
                        foreach($sections as $s) {
                            if (!empty($s['points'])) { $hasSections = true; break; }
                        }
                    @endphp
                    <div style="display: flex; flex-direction: column; height: 100%; min-height: 0;">
                        <!-- Header Box -->
                        <div style="background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 4px; padding: 12px 16px; margin-bottom: 10px; box-sizing: border-box;">
                            <div style="font-size: 20px; font-weight: 800; color: {{ $color['title'] }}; margin-bottom: 3px;">
                                {{ $pData['project_name'] }}
                            </div>
                            <div style="font-size: 15px; font-weight: 500; color: #475569;">
                                Lead: {{ $pData['officer_name'] }} • {{ $pData['location'] ?? 'Zambia' }}
                            </div>
                        </div>

                        <!-- Content Box -->
                        <div class="slide-scroll" style="background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 4px; padding: 16px 20px; flex: 1; overflow-y: auto; box-sizing: border-box; min-height: 0;">
                            @if($hasSections)
                                <div style="display: flex; flex-direction: column; gap: 14px;">
                                    @foreach($sections as $itemKey => $sec)
                                        @if(!empty($sec['points']))
                                        <div>
                                            <div style="font-size: 15px; font-weight: 800; color: {{ $color['title'] }}; margin-bottom: 6px; display: flex; align-items: center; gap: 7px; text-transform: uppercase; letter-spacing: 0.3px;">
                                                <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: {{ $color['title'] }};"></span>
                                                <span>{{ $sec['title'] }}</span>
                                            </div>
                                            <ul style="list-style-type: disc !important; list-style-position: outside; margin: 0; padding-left: 22px; display: flex; flex-direction: column; gap: 6px;">
                                                @foreach($sec['points'] as $pt)
                                                    <li style="list-style-type: disc !important; font-size: 16px; color: #1e293b; font-weight: 500; line-height: 1.45;">
                                                        {!! \App\Models\ActivityEntry::formatPointHtml($pt, false) !!}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif(!empty($points))
                                <ul style="list-style-type: disc !important; list-style-position: outside; margin: 0; padding-left: 24px; display: flex; flex-direction: column; gap: 9px;">
                                    @foreach($points as $pt)
                                        <li style="list-style-type: disc !important; font-size: 16.5px; color: #1e293b; font-weight: 500; line-height: 1.48; word-break: break-word; overflow-wrap: anywhere;">
                                            {!! \App\Models\ActivityEntry::formatPointHtml($pt, false) !!}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div style="font-size: 16px; color: #64748b; font-style: italic;">
                                    No key presentation points recorded for this period.
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @php $slideIndex++; @endphp
        @endforeach
    @endforeach

    <!-- ==================== FINAL SLIDE: THANK YOU ENDING SLIDE ==================== -->
    <div id="slide-{{ $slideIndex }}" class="slide-screen" style="display: none; align-items: center; justify-content: center;">
        <div style="width: 100%; max-width: 950px; padding: 30px 40px; margin: auto; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <img src="{{ asset('logos/logo2.png') }}" alt="Play It Forward Zambia" style="height: 90px; max-width: 280px; object-fit: contain; margin: 0 auto 24px auto; display: block;">

            <h1 style="font-size: 60px; font-weight: 900; color: #0f172a; margin: 0 auto 10px auto; letter-spacing: -1.2px; line-height: 1.05; text-align: center;">
                Thank You!
            </h1>
            <h2 style="font-size: 32px; font-weight: 800; color: #2563eb; margin: 0 auto 16px auto; text-align: center;">
                Play It Forward Zambia
            </h2>
            <p style="font-size: 19px; color: #475569; max-width: 720px; line-height: 1.55; margin: 0 auto 28px auto; text-align: center;">
                Inspiring and empowering young people and their communities through the power of education, health, and sport.
            </p>
            <div style="font-size: 18px; font-weight: 800; color: #1d4ed8; text-align: center;">
                {{ $quarter }}
            </div>
        </div>
    </div>

    <!-- ==================== FLOATING PRESENTATION HUD ==================== -->
    <div id="hud" class="powerpoint-hud">
        <button type="button" class="hud-btn" onclick="prevSlide()" title="Previous Slide (Left Arrow)">◀</button>
        <span id="slide-indicator" style="color: #ffffff; font-size: 11px; font-weight: 700; padding: 0 4px;">1 / {{ $totalSlides }}</span>
        <button type="button" class="hud-btn" onclick="nextSlide()" title="Next Slide (Right Arrow or Space)">▶</button>
        <span style="color: rgba(255,255,255,0.3);">|</span>
        <button type="button" class="hud-btn" onclick="toggleFullscreen()" title="Toggle Fullscreen (F)">⛶ Fullscreen</button>
        <a href="{{ route('programmes.hub') }}" class="hud-btn" style="color: #f87171;" title="Exit Presentation (Esc)">✕ Exit Hub</a>
    </div>

    <script>
        let currentSlide = 0;
        const totalSlides = {{ $totalSlides }};

        function showSlide(index) {
            if (index < 0) index = 0;
            if (index >= totalSlides) index = totalSlides - 1;

            for (let i = 0; i < totalSlides; i++) {
                const el = document.getElementById('slide-' + i);
                if (el) {
                    el.style.display = (i === index) ? 'flex' : 'none';
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
