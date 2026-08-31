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
            width: 28px;
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
            padding: 50px 70px 40px 90px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Thematic Slide Layout */
        .thematic-layout {
            padding: 30px 50px 20px 80px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        /* Top Right Logo 3 */
        .top-right-logo {
            position: absolute;
            top: 18px;
            right: 45px;
            height: 78px;
            max-width: 250px;
            object-fit: contain;
            z-index: 30;
        }

        /* Bottom PowerPoint-style HUD */
        .powerpoint-hud {
            position: fixed;
            bottom: 16px;
            left: 42px;
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(10px);
            padding: 6px 12px;
            border-radius: 24px;
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
            font-size: 13px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.15s;
            text-decoration: none;
        }
        .hud-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Custom Scrollbar for Projector Tables */
        .slide-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .slide-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
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
                <!-- Logo 2 above the name (Enlarged) -->
                <div style="margin-bottom: 24px;">
                    <img src="{{ asset('logos/logo2.png') }}" alt="Play It Forward Zambia Logo" style="height: 110px; max-width: 320px; object-fit: contain;">
                </div>

                <h1 style="font-size: 64px; font-weight: 900; color: #0f172a; margin: 0 0 8px 0; letter-spacing: -1.5px; line-height: 1.05;">
                    Play It Forward Zambia
                </h1>
                <h2 style="font-size: 40px; font-weight: 800; color: #2563eb; margin: 0 0 14px 0; letter-spacing: -0.5px;">
                    Programmes Meeting
                </h2>
                <div style="font-size: 24px; font-weight: 700; color: #1d4ed8; margin-bottom: 32px;">
                    {{ $quarter }}
                </div>

                <div style="background-color: #f8fafc; border: 2px solid #e2e8f0; border-radius: 18px; padding: 24px 36px; width: 92%; max-width: 1500px;">
                    <div style="font-size: 18px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 14px;">
                        Compiled Project Submissions ({{ $submissions->count() }} Active Projects):
                    </div>
                    <ul style="list-style-type: disc !important; list-style-position: outside; margin: 0; padding-left: 26px; display: flex; flex-direction: column; gap: 10px;">
                        @foreach($submissions as $sub)
                            <li style="list-style-type: disc !important; font-size: 22px; color: #1e293b; font-weight: 700; line-height: 1.4;">
                                {{ $sub->project_name }} <span style="color: #64748b; font-weight: 600;">({{ $sub->officer_name }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== SLIDES 1 to 5: THEMATIC FULL SLIDES ==================== -->
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
            <div>
                <div style="font-size: 15px; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 3px;">
                    SLIDE {{ $slide['number'] }} OF 5 • {{ $quarter }}
                </div>
                <h2 style="font-size: 44px; font-weight: 900; color: #0f172a; margin: 0 0 4px 0; letter-spacing: -1px; max-width: 80%;">
                    {{ $slide['title'] }}
                </h2>
                <div style="width: 70px; height: 5px; background-color: #2563eb; border-radius: 3px; margin-bottom: 14px;"></div>
            </div>

            <!-- Synchronized Table (3 Columns, Row per Project) -->
            <div class="slide-scroll" style="flex: 1; overflow-y: auto; padding-right: 10px;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 18px 12px; table-layout: fixed; margin-left: -9px; margin-right: -9px;">
                    <!-- Column Header Cards -->
                    <thead>
                        <tr>
                            @foreach($slide['items'] as $itemKey => $item)
                            <th style="width: 33.33%; vertical-align: top; background: #ffffff; border: 2px solid #cbd5e1; border-radius: 14px; padding: 12px 18px; text-align: left;">
                                <div style="width: 40px; height: 4px; background: #2563eb; border-radius: 2px; margin-bottom: 6px;"></div>
                                <div style="font-size: 20px; font-weight: 900; color: #0f172a; margin-bottom: 3px;">{{ $item['title'] }}</div>
                                <div style="font-size: 13px; color: #64748b; font-style: italic; font-weight: 500; line-height: 1.35;">{{ $item['prompt'] }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Project Rows -->
                        @foreach($submissions as $projIndex => $sub)
                            @php
                                $color = $projectColors[$projIndex % count($projectColors)];
                            @endphp
                            <tr>
                                @foreach($slide['items'] as $itemKey => $item)
                                @php
                                    $points = $sub->getPoints($itemKey);
                                @endphp
                                <td style="width: 33.33%; vertical-align: top; background: #ffffff; border: 1.5px solid #e2e8f0; border-left: 7px solid {{ $color['border'] }}; border-radius: 12px; padding: 14px 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                                    <div style="margin-bottom: 10px;">
                                        <span style="font-size: 15px; font-weight: 800; background-color: {{ $color['badge_bg'] }}; color: {{ $color['badge_text'] }}; border: 1.5px solid {{ $color['badge_border'] }}; padding: 4px 10px; border-radius: 8px; display: inline-block;">
                                            {{ $sub->project_name }}
                                        </span>
                                        <span style="font-size: 13px; color: #64748b; font-weight: 700; margin-left: 8px;">• {{ $sub->officer_name }}</span>
                                    </div>

                                    @if(!empty($points))
                                        <ul style="list-style-type: disc !important; list-style-position: outside; margin: 0; padding-left: 22px;">
                                            @foreach($points as $pt)
                                                <li style="list-style-type: disc !important; font-size: 16px; color: #0f172a; font-weight: 600; margin-bottom: 6px; line-height: 1.45;">{{ $pt }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div style="font-size: 13px; color: #94a3b8; font-style: italic; padding-left: 4px;">No key points submitted.</div>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div style="padding-top: 12px; border-top: 1.5px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 13px; font-weight: 700; color: #64748b;">
                <span>Play It Forward Zambia • Programmes Meeting</span>
                <span>Slide {{ $slideIndex + 1 }} of 7</span>
            </div>
        </div>
    </div>
    @php $slideIndex++; @endphp
    @endforeach

    <!-- ==================== SLIDE 6: THANK YOU ENDING SLIDE ==================== -->
    <div id="slide-6" class="slide-screen" style="display: none; align-items: center; justify-content: center;">
        <div style="width: 100%; max-width: 1100px; padding: 40px 60px; margin: auto; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <!-- Logo 2 (Centered) -->
            <img src="{{ asset('logos/logo2.png') }}" alt="Play It Forward Zambia" style="height: 110px; max-width: 320px; object-fit: contain; margin: 0 auto 32px auto; display: block;">

            <h1 style="font-size: 72px; font-weight: 900; color: #0f172a; margin: 0 auto 12px auto; letter-spacing: -1.5px; line-height: 1.05; text-align: center;">
                Thank You!
            </h1>
            <h2 style="font-size: 38px; font-weight: 800; color: #2563eb; margin: 0 auto 16px auto; text-align: center;">
                Play It Forward Zambia
            </h2>
            <p style="font-size: 24px; font-weight: 600; color: #475569; max-width: 900px; margin: 0 auto 36px auto; line-height: 1.5; text-align: center;">
                Inspiring and empowering young people and their communities through the power of education, health, and sport.
            </p>
            <div style="font-size: 20px; font-weight: 700; color: #1d4ed8; background-color: #eff6ff; border: 1.5px solid #bfdbfe; padding: 8px 26px; border-radius: 30px; display: inline-block; margin: 0 auto;">
                {{ $quarter }}
            </div>
        </div>
    </div>

    <!-- ==================== MINIMAL BOTTOM-LEFT SLIDESHOW HUD ==================== -->
    <div id="slideshow-hud" class="powerpoint-hud">
        <button type="button" id="hud-prev" class="hud-btn" title="Previous Slide (←)">
            ◀
        </button>
        <span id="hud-counter" style="font-size: 13px; font-weight: 800; color: #ffffff; padding: 0 6px;">
            1 / 7
        </span>
        <button type="button" id="hud-next" class="hud-btn" title="Next Slide (→)">
            ▶
        </button>
        <span style="color: #64748b;">|</span>
        <button type="button" id="hud-fullscreen" class="hud-btn" title="Toggle Fullscreen (F)">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
            </svg>
        </button>
        <a href="{{ route('programmes.export_consolidated_pptx') }}" class="hud-btn" title="Download PowerPoint (.pptx)">
            PPTX
        </a>
        <a href="{{ route('programmes.export_consolidated_pdf') }}" class="hud-btn" title="Download PDF">
            PDF
        </a>
        <a href="{{ route('programmes.hub') }}" class="hud-btn" title="Exit Presentation" style="color: #f87171;">
            ✕
        </a>
    </div>

    <script>
        let currentSlide = 0;
        const totalSlides = 7;
        const hud = document.getElementById('slideshow-hud');
        const counter = document.getElementById('hud-counter');
        const prevBtn = document.getElementById('hud-prev');
        const nextBtn = document.getElementById('hud-next');
        const fsBtn = document.getElementById('hud-fullscreen');

        function showSlide(index) {
            if (index < 0) index = 0;
            if (index >= totalSlides) index = totalSlides - 1;
            currentSlide = index;

            for (let i = 0; i < totalSlides; i++) {
                const el = document.getElementById('slide-' + i);
                if (el) {
                    el.style.display = (i === currentSlide) ? 'flex' : 'none';
                }
            }

            counter.innerText = `${currentSlide + 1} / ${totalSlides}`;
            prevBtn.style.opacity = (currentSlide === 0) ? '0.4' : '1';
            nextBtn.style.opacity = (currentSlide === totalSlides - 1) ? '0.4' : '1';
        }

        prevBtn.addEventListener('click', () => showSlide(currentSlide - 1));
        nextBtn.addEventListener('click', () => showSlide(currentSlide + 1));

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === ' ' || e.key === 'PageDown' || e.key === 'Enter') {
                e.preventDefault();
                showSlide(currentSlide + 1);
            } else if (e.key === 'ArrowLeft' || e.key === 'Backspace' || e.key === 'PageUp') {
                e.preventDefault();
                showSlide(currentSlide - 1);
            } else if (e.key === 'f' || e.key === 'F') {
                toggleFullscreen();
            }
        });

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        fsBtn.addEventListener('click', toggleFullscreen);

        // Click slide area to advance or go back
        document.addEventListener('click', (e) => {
            if (e.target.closest('#slideshow-hud')) return;
            const x = e.clientX;
            if (x < window.innerWidth * 0.2) {
                showSlide(currentSlide - 1);
            } else {
                showSlide(currentSlide + 1);
            }
        });

        // Mouse hover HUD wake up
        let hudTimer = null;
        document.addEventListener('mousemove', () => {
            hud.classList.add('active');
            clearTimeout(hudTimer);
            hudTimer = setTimeout(() => {
                hud.classList.remove('active');
            }, 3000);
        });

        showSlide(0);
    </script>
</body>
</html>
