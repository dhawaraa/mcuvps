<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('portal.public_register_browser_title') }}</title>
    
    <!-- Fonts: Sarabun & Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/assets/js/lucide.min.js"></script>
    <script src="/assets/js/font-resizer.js"></script>

    <!-- Tailwind CSS -->
    <script src="/assets/js/tailwindcss.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Sarabun', 'sans-serif'],
                        heading: ['Prompt', 'sans-serif'],
                        mono: ['Inter', 'monospace'],
                    },
                    colors: {
                        brand: {
                            green: '#2C7338',
                            greenDark: '#235D2E',
                            greenLight: '#E8F5E9',
                            forest: '#1E4620',
                            clay: '#C86D51',
                            warmBg: '#FBF9F4',
                            border: '#E8E3D7',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Sarabun', sans-serif; 
            background-color: #F8F5EE;
            color: #2D2A26;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }
        .hero-banner-card {
            background-image: linear-gradient(to right, rgba(21, 87, 36, 0.96) 0%, rgba(21, 87, 36, 0.88) 50%, rgba(21, 87, 36, 0.20) 82%, transparent 100%), url('{{ asset("images/hero2image.png") }}');
            background-size: cover;
            background-position: right 15%;
            background-repeat: no-repeat;
            min-height: 220px;
        }
        .main-card {
            background: #FFFFFF;
            border: 1px solid #EAE5D9;
            box-shadow: 0 4px 25px -4px rgba(74, 59, 50, 0.05);
        }
        .step-line {
            height: 2px;
            background-color: #E2DDD2;
        }
        .step-line.active {
            background-color: #2C7338;
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Navigation -->
    <nav class="bg-[#FAF8F2] border-b border-[#E3DEC9] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-10 h-10 object-contain drop-shadow-sm">
                    <div>
                        <div class="font-heading font-bold text-[#2C3E2D] leading-tight">VPSMCU</div>
                        <div class="text-xs text-[#6B6357]">{{ __('portal.university_name') }}</div>
                    </div>
                </a>

                <!-- Module 3 Navigation Tabs: สมัครคอร์ส vs ตรวจสอบสถานะ (Active: สมัครคอร์ส) -->
                <div class="hidden sm:flex items-center bg-[#EAE5D9]/80 p-1 rounded-2xl border border-[#D5CEBC]">
                    <a href="{{ route('public.register') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 bg-[#5A6B47] text-white shadow-sm">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>{{ __('portal.public_tab_register') }}</span>
                    </a>
                    <a href="{{ route('public.check') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#FAF8F2]">
                        <i data-lucide="search" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>{{ __('portal.public_tab_check') }}</span>
                    </a>
                </div>

                <div class="flex items-center space-x-3 sm:space-x-4">
                    @php
                        $currentLang = session('locale', 'th');
                    @endphp
                    <!-- Language Switcher (TH / EN) -->
                    @include('portal.partials.font_resizer')
                    <div class="flex items-center bg-[#EAE5D9] p-0.5 rounded-xl border border-[#D5CEBC] text-xs font-bold font-mono">
                        <a href="{{ route('lang.switch', 'th') }}" title="ภาษาไทย" class="px-2 py-1 rounded-lg transition {{ $currentLang === 'th' ? 'bg-[#5A6B47] text-white shadow-sm' : 'text-[#6B6357] hover:text-[#2C3E2D]' }}">
                            TH
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" title="English" class="px-2 py-1 rounded-lg transition {{ $currentLang === 'en' ? 'bg-[#5A6B47] text-white shadow-sm' : 'text-[#6B6357] hover:text-[#2C3E2D]' }}">
                            EN
                        </a>
                    </div>

                    <a href="{{ route('home') }}" class="text-[#4A3B32] hover:text-[#C86D51] font-semibold text-[15px] flex items-center gap-1.5 transition">
                        <i data-lucide="arrow-left" class="w-4.5 h-4.5 text-[#5A6B47]"></i> <span class="hidden sm:inline">{{ __('portal.nav_back_home') }}</span>
                    </a>
                </div>
            </div>

            <!-- Mobile Sub-Navigation -->
            <div class="flex sm:hidden items-center justify-center pb-3 pt-1 border-t border-[#EAE5D9] gap-2">
                <a href="{{ route('public.register') }}" class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-bold bg-[#5A6B47] text-white">
                    {{ __('portal.public_tab_register') }}
                </a>
                <a href="{{ route('public.check') }}" class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-bold bg-white text-[#4A3B32] border border-[#D5CEBC]">
                    {{ __('portal.public_tab_check') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow space-y-6 sm:space-y-8">

        <!-- 1. Header Banner Card (Dynamic title per step) -->
        <div class="hero-banner-card rounded-2xl p-5 sm:p-9 text-white shadow-sm border border-[#205C29]/40 relative overflow-hidden">
            <div class="max-w-xl">
                <h1 id="banner_title" class="text-xl sm:text-3xl font-heading font-extrabold text-white tracking-tight leading-snug">
                    {{ __('portal.public_banner_step1_title') }}
                </h1>
                <p id="banner_subtitle" class="text-sm sm:text-lg font-heading font-medium text-white/95 mt-0.5">
                    {{ __('portal.public_banner_step1_subtitle') }}
                </p>
                <p id="banner_desc" class="text-xs sm:text-sm text-white/85 mt-2 sm:mt-3 leading-relaxed">
                    {!! __('portal.public_banner_step1_desc') !!}
                </p>
            </div>
        </div>

        <!-- 2. Wizard Stepper (Circular Numbers with Connecting Lines) -->
        @php
            $targetEventId = request('event_id');
            $hasEventIdMatch = $targetEventId ? $events->contains('id', (int)$targetEventId) : false;
            $hasSelectableEvent = false;
            foreach ($events as $eCheck) {
                $q = $eCheck->max_quota ?? 50;
                $c = isset($eCheck->active_registrations_count) 
                    ? $eCheck->active_registrations_count 
                    : ($eCheck->activeRegistrations ? $eCheck->activeRegistrations->count() : ($eCheck->confirmed_count ?? 0));
                if ($eCheck->status === 'OPEN' && ($q - $c) > 0) {
                    $hasSelectableEvent = true;
                    break;
                }
            }
        @endphp
        <div class="max-w-2xl mx-auto px-2">
            <div class="relative flex items-center justify-between">
                <!-- Background Connection Lines -->
                <div class="absolute top-4 sm:top-5 left-8 right-8 sm:left-10 sm:right-10 -translate-y-1/2 flex items-center z-0">
                    <div id="step-connector-1" class="step-line flex-1 transition duration-300"></div>
                    <div id="step-connector-2" class="step-line flex-1 transition duration-300"></div>
                </div>

                <!-- Step 1 Circle & Label -->
                <div id="step-tab-1" class="relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(1)">
                    <div id="step-circle-1" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#1F6B30] text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-xs transition duration-200">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 hidden" id="step-icon-1"></i>
                        <span id="step-num-1">1</span>
                    </div>
                    <span id="step-text-1" class="text-[11px] sm:text-sm font-heading font-bold text-[#2D2A26] mt-1.5 sm:mt-2 text-center whitespace-nowrap">
                        {{ __('portal.public_stepper_step1') }}
                    </span>
                </div>

                <!-- Step 2 Circle & Label -->
                <div id="step-tab-2" class="relative z-10 flex flex-col items-center {{ $hasSelectableEvent ? 'cursor-pointer' : 'cursor-not-allowed opacity-40' }}" onclick="goToStep(2)" title="{{ $hasSelectableEvent ? '' : __('portal.public_no_courses') }}">
                    <div id="step-circle-2" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white border-2 border-[#D5CEBC] text-[#8C8275] flex items-center justify-center font-bold text-xs sm:text-sm shadow-xs transition duration-200">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 hidden" id="step-icon-2"></i>
                        <span id="step-num-2">2</span>
                    </div>
                    <span id="step-text-2" class="text-[11px] sm:text-sm font-heading font-medium text-[#7A7367] mt-1.5 sm:mt-2 text-center whitespace-nowrap">
                        {{ __('portal.public_stepper_step2') }}
                    </span>
                </div>

                <!-- Step 3 Circle & Label -->
                <div id="step-tab-3" class="relative z-10 flex flex-col items-center {{ $hasSelectableEvent ? 'cursor-pointer' : 'cursor-not-allowed opacity-40' }}" onclick="goToStep(3)" title="{{ $hasSelectableEvent ? '' : __('portal.public_no_courses') }}">
                    <div id="step-circle-3" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white border-2 border-[#D5CEBC] text-[#8C8275] flex items-center justify-center font-bold text-xs sm:text-sm shadow-xs transition duration-200">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 hidden" id="step-icon-3"></i>
                        <span id="step-num-3">3</span>
                    </div>
                    <span id="step-text-3" class="text-[11px] sm:text-sm font-heading font-medium text-[#7A7367] mt-1.5 sm:mt-2 text-center whitespace-nowrap">
                        {{ __('portal.public_stepper_step3') }}
                    </span>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-xs">
                <div class="flex items-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mr-2 shrink-0"></i>
                    <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-xs space-y-1">
                <div class="flex items-center text-red-800 font-bold text-sm mb-1">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mr-2 shrink-0"></i>
                    <span>เกิดข้อผิดพลาดในการลงทะเบียน โปรดตรวจสอบข้อมูล:</span>
                </div>
                <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5 ml-7">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('regSuccess'))
            <div class="main-card rounded-2xl p-8 sm:p-12 text-center shadow-lg">
                <div class="w-20 h-20 bg-[#2C7338]/15 text-[#2C7338] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#2C7338]/30">
                    <i data-lucide="check-circle" class="w-10 h-10"></i>
                </div>
                <div class="inline-block px-3.5 py-1 bg-amber-50 text-amber-800 text-xs font-semibold rounded-full border border-amber-200 mb-3">
                    สถานะ: รอเจ้าหน้าที่ตรวจสอบคุณสมบัติ (Pending Review)
                </div>
                <h2 class="text-xl md:text-3xl font-heading font-bold text-[#2C3E2D] mb-2">{{ session('success') }}</h2>
                <p class="text-[#4A3B32] text-sm mb-3">รหัสการลงทะเบียนของท่านคือ:</p>
                <div class="inline-block bg-[#F8F5EE] border-2 border-[#D5CEBC] font-mono font-extrabold text-2xl md:text-3xl px-8 py-3 rounded-xl text-[#C86D51] tracking-wider mb-6">
                    {{ session('regSuccess') }}
                </div>
                <div class="max-w-md mx-auto bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl p-4 text-xs text-[#7B8D65] text-left mb-6 space-y-1.5">
                    <div class="font-bold text-[#2C3E2D] flex items-center gap-1.5 text-sm">
                        <i data-lucide="info" class="w-4 h-4 text-[#2C7338]"></i> ลำดับขั้นตอนถัดไป:
                    </div>
                    <div>1. เจ้าหน้าที่ส่วนงานจะตรวจสอบประวัติและข้อจำกัดทางสุขภาพ/อาหาร</div>
                    <div>2. ตรวจสอบการจัดสรรห้องพัก/อาคารตามเพศสภาพและพรรษา</div>
                    <div>3. เมื่อผ่านการอนุมัติ เจ้าหน้าที่จะปรับสถานะเป็น "อนุมัติสิทธิ์ (Confirmed)" เพื่อเตรียมเข้ารับการอบรม</div>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <button onclick="window.print()" class="bg-[#2C7338] hover:bg-[#235D2E] text-white font-medium px-6 py-2.5 rounded-xl text-sm transition flex items-center gap-2">
                        <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์เอกสารยืนยัน
                    </button>
                    <a href="{{ route('public.register') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium bg-white border border-[#D5CEBC] text-[#4A3B32] hover:bg-[#FAF8F2] transition">
                        ลงทะเบียนให้ท่านอื่น
                    </a>
                </div>
            </div>
        @else

            <!-- Form Card Wrapper -->
            <form id="publicRegisterForm" action="{{ url('/public_register.php') }}" method="POST" novalidate>
                @csrf

                <!-- ============================================================== -->
                <!-- STEP 1: หน้าเลือกรีวิวโครงการ (Step 1) -->
                <!-- ============================================================== -->
                <div id="step-content-1" class="main-card rounded-2xl p-6 sm:p-9 space-y-6">
                    
                    <!-- Section Title with Icon -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center shrink-0">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-heading font-bold text-[#2D2A26]">{{ __('portal.public_sec1_heading') }}</h2>
                            <p class="text-xs text-[#7A7367] mt-0.5">{{ __('portal.public_sec1_subheading') }}</p>
                        </div>
                    </div>

                    <!-- Events List -->
                    <div class="space-y-4">
                        @php
                            $targetEventId = request('event_id');
                            $hasEventIdMatch = $targetEventId ? $events->contains('id', (int)$targetEventId) : false;
                        @endphp
                        @forelse ($events as $index => $ev)
                            @php 
                                $quota = $ev->max_quota ?? 50;
                                // ใครมาก่อนได้สิทธิ์ก่อน: นับผู้สมัครทั้งหมดที่ไม่ถูกยกเลิก/ปฏิเสธ (หรือ fallback confirmed_count)
                                $currentRegistered = isset($ev->active_registrations_count) 
                                    ? $ev->active_registrations_count 
                                    : ($ev->activeRegistrations ? $ev->activeRegistrations->count() : ($ev->confirmed_count ?? 0));
                                $available = max(0, $quota - $currentRegistered);
                                $isFull = ($available <= 0);
                                $percent = $quota > 0 ? min(100, round(($currentRegistered / $quota) * 100)) : 0;
                                $coverImg = $ev->cover_image ? $ev->cover_image : '/images/news/meditation_hall.jpg';
                                $isSelected = $hasEventIdMatch ? ((int)$targetEventId === (int)$ev->id) : ($index === 0);
                            @endphp
                            <label class="event-card group relative block p-4 sm:p-5 border border-[#E8E3D7] rounded-2xl cursor-pointer hover:border-[#2C7338] hover:shadow-xs transition bg-[#FFFFFF]" data-event-id="{{ $ev->id }}">
                                
                                <!-- ================= MOBILE VIEW (Hidden on sm and up) ================= -->
                                <div class="block sm:hidden space-y-3">
                                    <!-- Top Row: Radio, Status Badge & Seats remaining -->
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <input type="radio" name="event_id" value="{{ $ev->id }}" {{ $isSelected && !$isFull ? 'checked' : '' }} {{ $isFull ? 'disabled' : 'required' }} class="event-radio w-5 h-5 text-[#2C7338] focus:ring-[#2C7338] cursor-pointer shrink-0 disabled:opacity-50 disabled:cursor-not-allowed" onchange="onEventRadioChange(this)">
                                            @if ($ev->status === 'OPEN' && !$isFull)
                                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-[#2C7338] text-white shrink-0">
                                                    {{ __('portal.public_status_open') }}
                                                </span>
                                            @elseif ($isFull)
                                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-[#A3432B] text-white shrink-0">
                                                    {{ __('portal.public_status_full_waitlist') }}
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-[#A3432B] text-white shrink-0">
                                                    {{ __('portal.public_status_coming_soon') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] font-medium text-[#2C7338] bg-[#E8F3EA] px-2.5 py-0.5 rounded-full shrink-0">
                                            เหลือ {{ $available }} ที่นั่ง
                                        </div>
                                    </div>

                                    <!-- Event Title (Full width, bold, prominent) -->
                                    <h3 class="font-heading font-bold text-base text-[#2D2A26] leading-snug break-words">
                                        {{ $ev->localized_title }}
                                    </h3>

                                    <!-- Image (Full width banner on mobile) -->
                                    <div class="w-full h-36 rounded-xl overflow-hidden bg-stone-100 border border-[#EAE5D9]">
                                        <img src="{{ asset($coverImg) }}" alt="{{ $ev->localized_title }}" class="w-full h-full object-cover">
                                    </div>

                                    <!-- Target audience -->
                                    <p class="text-xs text-[#7A7367]">
                                        {{ __('portal.public_target_audience') }}
                                    </p>

                                    <!-- Location & Date rows -->
                                    <div class="space-y-1.5 text-xs text-[#6B6357]">
                                        <div class="flex items-start gap-1.5">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#C86D51] shrink-0 mt-0.5"></i>
                                            <span class="break-words leading-tight">{{ $ev->localized_location }}</span>
                                        </div>
                                        <div class="flex items-start gap-1.5">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#7A7367] shrink-0 mt-0.5"></i>
                                            @php
                                                $isEn = app()->getLocale() === 'en';
                                                $sDate = \Carbon\Carbon::parse($ev->start_date);
                                                $eDate = \Carbon\Carbon::parse($ev->end_date);
                                                $thMonths = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
                                                $enMonths = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                                $months = $isEn ? $enMonths : $thMonths;
                                                $yearS = $isEn ? $sDate->year : ($sDate->year + 543);
                                                $yearE = $isEn ? $eDate->year : ($eDate->year + 543);
                                                $nights = $sDate->diffInDays($eDate);
                                                $days = $nights + 1;
                                                $dateStr = ($sDate->format('Y-m') === $eDate->format('Y-m'))
                                                    ? ($sDate->day . ' - ' . $eDate->day . ' ' . $months[$sDate->month] . ' ' . $yearS)
                                                    : ($sDate->day . ' ' . $months[$sDate->month] . ' ' . $yearS . ' - ' . $eDate->day . ' ' . $months[$eDate->month] . ' ' . $yearE);
                                                
                                                $deadlineDate = $sDate->copy()->subDays(3);
                                                $deadlineYear = $isEn ? $deadlineDate->year : ($deadlineDate->year + 543);
                                                $deadlineStr = $deadlineDate->day . ' ' . $months[$deadlineDate->month] . ' ' . $deadlineYear;
                                            @endphp
                                            <span class="break-words leading-tight">{{ $dateStr }} ({{ $nights }} {{ __('portal.public_nights') }} {{ $days }} {{ __('portal.public_days') }})</span>
                                        </div>
                                    </div>

                                    <!-- Bottom Quota & Deadline Box -->
                                    <div class="bg-[#FBF9F4] rounded-xl p-3 border border-[#EFECE5] text-xs space-y-2">
                                        <div class="flex items-center justify-between gap-1.5 text-[#6B6357]">
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="calendar-check" class="w-3.5 h-3.5 text-[#7A7367] shrink-0"></i>
                                                <span>{{ __('portal.public_deadline_label') }}</span>
                                            </div>
                                            <strong class="text-[#2D2A26]">{{ $deadlineStr }}</strong>
                                        </div>
                                        <div class="space-y-1">
                                            <div class="w-full bg-[#EAE5D9] rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-[#2C7338] h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <div class="flex justify-between text-[10px] text-[#7A7367]">
                                                <span>ทั้งหมด {{ $quota }} ที่นั่ง</span>
                                                <span class="font-mono">{{ $percent }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ================= DESKTOP VIEW (sm and up) ================= -->
                                <div class="hidden sm:flex flex-col lg:flex-row items-stretch lg:items-center gap-4">
                                    <div class="flex items-center gap-3.5 shrink-0">
                                        <div class="shrink-0 flex items-center">
                                            <input type="radio" name="event_id" value="{{ $ev->id }}" {{ $isSelected && !$isFull ? 'checked' : '' }} {{ $isFull ? 'disabled' : 'required' }} class="event-radio w-5 h-5 text-[#2C7338] focus:ring-[#2C7338] cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" onchange="onEventRadioChange(this)">
                                        </div>

                                        <div class="w-40 h-28 rounded-xl overflow-hidden bg-stone-100 shrink-0 border border-[#EAE5D9]">
                                            <img src="{{ asset($coverImg) }}" alt="{{ $ev->localized_title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        </div>
                                    </div>

                                    <div class="flex-grow min-w-0 w-full space-y-2">
                                        <div class="space-y-1.5">
                                            <div>
                                                @if ($ev->status === 'OPEN' && !$isFull)
                                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-[#2C7338] text-white inline-block">
                                                        {{ __('portal.public_status_open') }}
                                                    </span>
                                                @elseif ($isFull)
                                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-[#A3432B] text-white inline-block">
                                                        {{ __('portal.public_status_full_waitlist') }}
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-[#A3432B] text-white inline-block">
                                                        {{ __('portal.public_status_coming_soon') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <h3 class="font-heading font-bold text-base text-[#2D2A26] group-hover:text-[#2C7338] transition leading-snug event-title break-words">
                                                {{ $ev->localized_title }}
                                            </h3>
                                        </div>

                                        <p class="text-xs text-[#7A7367]">
                                            {{ __('portal.public_target_audience') }}
                                        </p>

                                        <div class="pt-1 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-xs text-[#6B6357]">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#C86D51] shrink-0"></i>
                                                <span class="event-location break-words">{{ $ev->localized_location }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#7A7367] shrink-0"></i>
                                                <span class="event-dates break-words">{{ $dateStr }} ({{ $nights }} {{ __('portal.public_nights') }} {{ $days }} {{ __('portal.public_days') }})</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full lg:w-48 shrink-0 bg-[#FBF9F4] rounded-xl p-3 border border-[#EFECE5] text-xs space-y-2 mt-2 lg:mt-0">
                                        <div class="flex items-center justify-between sm:justify-start gap-1.5 text-[#6B6357]">
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="calendar-check" class="w-3.5 h-3.5 text-[#7A7367] shrink-0"></i>
                                                <span>{{ __('portal.public_deadline_label') }}</span>
                                            </div>
                                            <strong class="text-[#2D2A26]">{{ $deadlineStr }}</strong>
                                        </div>

                                        <div class="flex items-center justify-between sm:justify-start gap-1.5 text-[#6B6357]">
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="users" class="w-3.5 h-3.5 text-[#2C7338] shrink-0"></i>
                                                <span>{{ __('portal.public_remaining_seats') }}</span>
                                            </div>
                                            <span><strong class="text-[#2C7338]">{{ $available }}</strong> / {{ $quota }} {{ __('portal.public_total_seats_suffix') }}</span>
                                        </div>

                                        <div class="space-y-1">
                                            <div class="w-full bg-[#EAE5D9] rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-[#2C7338] h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <div class="text-right text-[10px] text-[#7A7367] font-mono">
                                                {{ $percent }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </label>
                        @empty
                            <div class="text-center py-10 text-[#7B8D65] bg-[#FAF8F2] rounded-xl border border-dashed border-[#EAE5D9]">
                                {{ __('portal.public_no_courses') }}
                            </div>
                        @endforelse
                    </div>

                    <!-- Step 1 Footer Buttons -->
                    <div class="pt-6 border-t border-[#EAE5D9] flex items-center justify-end gap-4">
                        <a href="{{ route('home') }}" class="text-xs font-semibold text-[#6B6357] hover:text-[#2D2A26] transition">
                            {{ __('portal.public_btn_cancel') }}
                        </a>
                        @if ($hasSelectableEvent)
                            <button type="button" onclick="goToStep(2)" class="bg-[#2C7338] hover:bg-[#235D2E] text-white font-semibold px-6 py-2.5 rounded-xl text-xs sm:text-sm shadow-xs transition flex items-center gap-2">
                                <span>{{ __('portal.public_btn_next_step2') }}</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        @else
                            <button type="button" disabled class="bg-gray-400 text-white font-semibold px-6 py-2.5 rounded-xl text-xs sm:text-sm cursor-not-allowed opacity-60 flex items-center gap-2" title="{{ __('portal.public_no_courses') }}">
                                <span>{{ __('portal.public_btn_next_step2') }}</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- STEP 2: หน้ากรอกข้อมูลผู้สมัคร (Step 2) -->
                <!-- ============================================================== -->
                <div id="step-content-2" class="hidden main-card rounded-2xl p-6 sm:p-9 space-y-7">
                    
                    <!-- Selected Project Preview Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-[#EAE5D9] gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center shrink-0">
                                <i data-lucide="layout-list" class="w-5 h-5"></i>
                            </div>
                            <h2 class="text-lg sm:text-xl font-heading font-bold text-[#2D2A26]">{{ __('portal.public_selected_course_title') }}</h2>
                        </div>
                        <button type="button" onclick="goToStep(1)" class="px-3 py-1.5 rounded-lg border border-[#D5CEBC] bg-white hover:bg-[#FAF8F2] text-xs font-semibold text-[#5A6B47] transition flex items-center gap-1.5 shadow-2xs">
                            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                            <span>{{ __('portal.public_btn_change_course') }}</span>
                        </button>
                    </div>

                    <!-- Selected Project Preview Box (Responsive on mobile) -->
                    <div class="p-4 sm:p-5 border border-[#E8E3D7] rounded-2xl bg-white space-y-3 sm:space-y-0 sm:flex sm:flex-col lg:sm:flex-row sm:items-center sm:gap-4">
                        
                        <!-- Mobile View (block sm:hidden): Full width layout -->
                        <div class="block sm:hidden space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-[#2C7338] text-white">
                                    {{ __('portal.public_status_open') }}
                                </span>
                                <span class="text-xs text-[#7A7367]">
                                    {{ __('portal.public_target_audience') }}
                                </span>
                            </div>

                            <h3 id="selected_ev_title_mob" class="font-heading font-bold text-base text-[#2D2A26] leading-snug break-words">
                                --
                            </h3>

                            <div class="w-full h-36 rounded-xl overflow-hidden bg-stone-100 border border-[#EAE5D9]">
                                <img id="selected_ev_img_mob" src="/images/news/meditation_hall.jpg" alt="Selected Event" class="w-full h-full object-cover">
                            </div>

                            <div class="bg-[#F4FBF5] rounded-xl p-3 border border-[#D7EED9] text-xs space-y-1.5">
                                <div class="flex items-center gap-1.5 text-[#2C7338] font-semibold">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 shrink-0"></i>
                                    <span id="selected_ev_dates_mob" class="break-words">--</span>
                                </div>
                                <div class="flex items-start gap-1.5 text-[#6B6357]">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#C86D51] shrink-0 mt-0.5"></i>
                                    <span id="selected_ev_loc_mob" class="text-[11px] leading-tight break-words">--</span>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop View (hidden sm:flex) -->
                        <div class="hidden sm:flex items-center gap-4 w-full">
                            <div class="w-36 h-24 rounded-xl overflow-hidden bg-stone-100 shrink-0 border border-[#EAE5D9]">
                                <img id="selected_ev_img" src="/images/news/meditation_hall.jpg" alt="Selected Event" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow min-w-0 space-y-1.5">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-[#2C7338] text-white inline-block">
                                    {{ __('portal.public_status_open') }}
                                </span>
                                <h3 id="selected_ev_title" class="font-heading font-bold text-base text-[#2D2A26] leading-snug break-words">
                                    --
                                </h3>
                                <p class="text-xs text-[#7A7367]">
                                    {{ __('portal.public_target_audience') }}
                                </p>
                            </div>
                            <div class="w-60 shrink-0 bg-[#F4FBF5] rounded-xl p-3 border border-[#D7EED9] text-xs space-y-1.5">
                                <div class="flex items-center gap-1.5 text-[#2C7338] font-semibold">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 shrink-0"></i>
                                    <span id="selected_ev_dates" class="break-words">--</span>
                                </div>
                                <div class="flex items-start gap-1.5 text-[#6B6357]">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#C86D51] shrink-0 mt-0.5"></i>
                                    <span id="selected_ev_loc" class="text-[11px] leading-tight break-words">--</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- 1. ข้อมูลผู้สมัครเข้าร่วมโครงการ (Personal Information) -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#2C7338] text-white flex items-center justify-center text-xs font-bold font-mono">1</span>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-[#2D2A26]">{{ __('portal.public_sec2_title') }}</h3>
                        </div>

                        <!-- Status Selector Radio Box -->
                        <div class="p-4 rounded-xl bg-[#FBF9F4] border border-[#EFECE5]">
                            <div class="text-xs text-[#7A7367] mb-2 font-medium">{{ __('portal.public_applicant_status') }}</div>
                            <div class="flex flex-wrap gap-6 text-xs text-[#2D2A26]">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="applicant_type" value="PEOPLE" checked class="w-4 h-4 text-[#2C7338] focus:ring-[#2C7338]" onchange="toggleStudentField(this.value)">
                                    <span>{{ __('portal.public_status_people') }}</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="applicant_type" value="STUDENT" class="w-4 h-4 text-[#2C7338] focus:ring-[#2C7338]" onchange="toggleStudentField(this.value)">
                                    <span>{{ __('portal.public_status_student') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Academic Info Container (Collapsible) -->
                        <div id="studentIdContainer" class="hidden p-4 rounded-xl bg-[#FBF9F4] border border-[#EFECE5] space-y-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#2C7338]">
                                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                                <span>{{ __('portal.public_academic_info_title') }}</span>
                            </div>

                            <!-- Auto-Fill Success / Notice Banner -->
                            <div id="autofill_banner" class="hidden p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between transition">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                    <span id="autofill_banner_text">ดึงข้อมูลเดิมเรียบร้อยแล้ว</span>
                                </div>
                                <span class="text-[10px] text-emerald-600 bg-emerald-100/70 px-2 py-0.5 rounded font-medium">แก้ไขเพิ่มเติมได้</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-[#6B6357]">{{ __('portal.public_student_id') }} <span class="text-red-500">*</span></label>
                                        <span id="spinner_studentid" class="hidden text-[10px] text-[#5A6B47] flex items-center gap-1 font-medium">
                                            <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i> กำลังค้นหา...
                                        </span>
                                    </div>
                                    <input type="text" name="student_id" id="txt_studentid" placeholder="เช่น 6401201001" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs font-mono">
                                </div>
                                <div>
                                    <label class="block text-[#6B6357] mb-1">{{ __('portal.public_degree_level') }} <span class="text-red-500">*</span></label>
                                    <select name="degree_level" id="select_degree_level" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                        <option value="">{{ __('portal.public_select_degree') }}</option>
                                        <option value="ปริญญาตรี">{{ __('portal.public_degree_bachelor') }}</option>
                                        <option value="ปริญญาโท">{{ __('portal.public_degree_master') }}</option>
                                        <option value="ปริญญาเอก">{{ __('portal.public_degree_doctoral') }}</option>
                                        <option value="ประกาศนียบัตร">{{ __('portal.public_degree_cert') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[#6B6357] mb-1">{{ __('portal.public_faculty') }} <span class="text-red-500">*</span></label>
                                    <select name="faculty" id="txt_faculty" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                        <option value="">{{ __('portal.public_select_faculty') }}</option>
                                        <option value="พุทธศาสตร์">{{ __('portal.public_fac_buddhism') }}</option>
                                        <option value="ครุศาสตร์">{{ __('portal.public_fac_education') }}</option>
                                        <option value="สังคมศาสตร์">{{ __('portal.public_fac_social') }}</option>
                                        <option value="มนุษยศาสตร์">{{ __('portal.public_fac_humanities') }}</option>
                                        <option value="บัณฑิตวิทยาลัย">{{ __('portal.public_fac_grad') }}</option>
                                        <option value="IBSC">{{ __('portal.public_fac_ibsc') }}</option>
                                        <option value="วิทยาลัยสงฆ์/วิทยาเขต">{{ __('portal.public_fac_campuses') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[#6B6357] mb-1">{{ __('portal.public_program_name') }} <span class="text-red-500">*</span></label>
                                    <input type="text" name="program_name" id="txt_program_name" placeholder="{{ __('portal.public_program_placeholder') }}" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- Personal Fields Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
                            <div class="sm:col-span-4">
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[#6B6357]">{{ __('portal.public_citizen_id') }} <span class="text-red-500">*</span></label>
                                    <span id="spinner_citizen_id" class="hidden text-[10px] text-[#5A6B47] flex items-center gap-1 font-medium">
                                        <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i> กำลังค้นหาข้อมูลเดิม...
                                    </span>
                                </div>
                                <input type="text" name="citizen_id" id="inp_citizen_id" placeholder="{{ __('portal.public_citizen_id_placeholder') }}" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs font-mono">
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_prefix') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="prefix" id="prefix_select" list="prefix_datalist" placeholder="{{ __('portal.public_prefix_placeholder') }}" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                <datalist id="prefix_datalist">
                                    <option value="นาย">
                                    <option value="นาง">
                                    <option value="นางสาว">
                                    <option value="พระ">
                                    <option value="พระภิกษุ">
                                    <option value="สามเณร">
                                    <option value="แม่ชี">
                                    <option value="อุบาสก">
                                    <option value="อุบาสิกา">
                                </datalist>
                            </div>
                            <div class="sm:col-span-5">
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_first_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" id="inp_first_name" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            </div>

                            <div class="sm:col-span-4">
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_last_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" id="inp_last_name" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            </div>
                            <div class="sm:col-span-4">
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_buddhist_name_label') }}</label>
                                <input type="text" name="buddhist_name" id="inp_buddhist_name" placeholder="{{ __('portal.public_buddhist_name_placeholder') }}" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            </div>
                            <div class="sm:col-span-2" id="vassaWrapper">
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_vassa_label') }}</label>
                                <input type="number" name="vassa" id="inp_vassa" value="0" min="0" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_age') }}</label>
                                <input type="number" name="age" id="inp_age" placeholder="เช่น 45" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            </div>

                            <div class="sm:col-span-4">
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_phone') }} <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" id="inp_phone" placeholder="{{ __('portal.public_phone_placeholder') }}" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs font-mono">
                            </div>
                            <div class="sm:col-span-4">
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_email') }}</label>
                                <input type="text" inputmode="email" name="email" id="inp_email" value="{{ old('email') }}" placeholder="example@gmail.com" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            </div>
                            <div class="sm:col-span-4">
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_line') }}</label>
                                <input type="text" name="line_id" id="inp_line" placeholder="Line ID หรือเบอร์ไลน์" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- 2. ที่อยู่ (Address) -->
                    <div class="space-y-4 pt-3 border-t border-[#EAE5D9]">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#2C7338] text-white flex items-center justify-center text-xs font-bold font-mono">2</span>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-[#2D2A26]">{{ __('portal.public_sec3_title') }}</h3>
                        </div>

                        <div class="space-y-3 text-xs">
                            <div>
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_address') }}</label>
                                <input type="text" name="address" id="inp_address" placeholder="{{ __('portal.public_address_placeholder') }}" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-[#6B6357] mb-1">{{ __('portal.public_province') }} <span class="text-red-500">*</span></label>
                                    <select id="province_select" name="province" required onchange="onProvinceChange(this.value)" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                        <option value="">{{ __('portal.public_select_province') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[#6B6357] mb-1">{{ __('portal.public_district') }} <span class="text-red-500">*</span></label>
                                    <select id="district_select" name="district" required disabled onchange="onDistrictChange(this.value)" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs disabled:bg-stone-100">
                                        <option value="">{{ __('portal.public_select_district_first') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[#6B6357] mb-1">{{ __('portal.public_subdistrict') }} <span class="text-red-500">*</span></label>
                                    <select id="subdistrict_select" name="subdistrict" required disabled onchange="onSubdistrictChange(this.value)" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs disabled:bg-stone-100">
                                        <option value="">{{ __('portal.public_select_subdistrict_first') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[#6B6357] mb-1">{{ __('portal.public_postal_code') }}</label>
                                    <input type="text" id="postal_code_input" name="postal_code" maxlength="5" placeholder="เช่น 13170" class="w-full px-3 py-2 bg-[#FBF9F4] border border-[#D5CEBC] rounded-lg text-xs font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. ข้อมูลห้องพัก ยานพาหนะ และอาหาร (Accommodations & Preferences) -->
                    <div class="space-y-4 pt-3 border-t border-[#EAE5D9]">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#2C7338] text-white flex items-center justify-center text-xs font-bold font-mono">3</span>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-[#2D2A26]">{{ __('portal.public_sec4_title') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_room') }}</label>
                                <select name="room_info_select" id="inp_room_info_select" onchange="toggleCustomRoom(this.value)" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                    <option value="พักรวม">พักรวม</option>
                                    <option value="พักที่อาคาร 92 ปี">พักที่อาคาร 92 ปี</option>
                                    <option value="พักที่อาคารพระพรหมวัชรธีราจารย์">พักที่อาคารพระพรหมวัชรธีราจารย์</option>
                                    <option value="OTHER">อื่น ๆ ระบุ</option>
                                </select>
                                <input type="hidden" name="room_info" id="inp_room_info" value="พักรวม">
                                <div id="room_custom_wrapper" class="hidden mt-2">
                                    <input type="text" id="inp_room_custom" placeholder="ระบุข้อมูลห้องพัก / อาคารที่ต้องการ..." oninput="updateRoomInfoHidden()" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-lg text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5A6B47]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_vehicle') }}</label>
                                <select name="vehicle_info" id="inp_vehicle_info" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                    <option value="แบบตัวเลือก - เดินทางไป-กลับเอง / ขึ้นรถตามที่สถาบันจัดให้">{{ __('portal.public_travel_option_any') }}</option>
                                    <option value="เดินทางโดยรถยนต์ส่วนตัว">{{ __('portal.public_travel_option_car') }}</option>
                                    <option value="เดินทางโดยรถตู้/รถบัสของสถาบัน">{{ __('portal.public_travel_option_bus') }}</option>
                                    <option value="เดินทางโดยรถโดยสารสาธารณะ">{{ __('portal.public_travel_option_public') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_food') }} <span class="text-red-500">*</span></label>
                                <select name="food_type" id="inp_food_type" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                    <option value="NORMAL">{{ __('portal.public_food_normal') }}</option>
                                    <option value="VEGETARIAN">{{ __('portal.public_food_veg') }}</option>
                                    <option value="JAY">{{ __('portal.public_food_jay') }}</option>
                                    <option value="HALAL">{{ __('portal.public_food_halal') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[#6B6357] mb-1">{{ __('portal.public_special_needs') }}</label>
                                <input type="text" name="special_needs" id="inp_special_needs" placeholder="{{ __('portal.public_special_needs_placeholder') }}" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 Footer Buttons -->
                    <div class="pt-6 border-t border-[#EAE5D9] flex items-center justify-end gap-3">
                        <button type="button" onclick="goToStep(1)" class="px-5 py-2.5 rounded-xl border border-[#D5CEBC] text-[#4A3B32] hover:bg-[#FAF8F2] text-xs sm:text-sm font-semibold flex items-center gap-1.5 transition">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            <span>{{ __('portal.public_btn_prev') }}</span>
                        </button>
                        <button type="button" onclick="validateAndGoToStep3()" class="bg-[#2C7338] hover:bg-[#235D2E] text-white font-semibold px-6 py-2.5 rounded-xl text-xs sm:text-sm shadow-xs transition flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>{{ __('portal.public_btn_submit') }}</span>
                        </button>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- STEP 3: หน้ารีวิว กฎ ระเบียบต่างๆ ก่อนการกดยืนยัน (Step 3) -->
                <!-- ============================================================== -->
                <div id="step-content-3" class="hidden main-card rounded-2xl p-6 sm:p-9 space-y-6">
                    
                    <!-- Section Title: สรุปข้อมูลก่อนยืนยัน -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center shrink-0">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-heading font-bold text-[#2D2A26]">{{ __('portal.public_summary_title') }}</h2>
                            <p class="text-xs text-[#7A7367] mt-0.5">{{ __('portal.public_summary_desc') }}</p>
                        </div>
                    </div>

                    <!-- 1. ข้อมูลโครงการที่ท่านเลือก (Card Box) -->
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-2 text-sm font-heading font-bold text-[#2D2A26]">
                            <span class="w-5 h-5 rounded-md bg-[#2C7338] text-white flex items-center justify-center text-[10px]">
                                <i data-lucide="list" class="w-3.5 h-3.5"></i>
                            </span>
                            <span>{{ __('portal.public_summary_course_title') }}</span>
                        </div>

                        <!-- Review Project Preview Box (Responsive on mobile) -->
                        <div class="p-4 sm:p-5 border border-[#E8E3D7] rounded-2xl bg-white space-y-3 sm:space-y-0 sm:flex sm:flex-col lg:sm:flex-row sm:items-center sm:gap-4">
                            
                            <!-- Mobile View (block sm:hidden) -->
                            <div class="block sm:hidden space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-[#2C7338] text-white">
                                        เปิดรับสมัคร
                                    </span>
                                    <span class="text-xs text-[#7A7367]">
                                        สำหรับนิสิต บุคลากร และประชาชนทั่วไป
                                    </span>
                                </div>

                                <h3 id="review_ev_title_mob" class="font-heading font-bold text-base text-[#2D2A26] leading-snug break-words">
                                    --
                                </h3>

                                <div class="w-full h-36 rounded-xl overflow-hidden bg-stone-100 border border-[#EAE5D9]">
                                    <img id="review_ev_img_mob" src="/images/news/meditation_hall.jpg" alt="Event Cover" class="w-full h-full object-cover">
                                </div>

                                <div class="bg-[#F4FBF5] rounded-xl p-3 border border-[#D7EED9] text-xs space-y-1.5">
                                    <div class="flex items-center gap-1.5 text-[#2C7338] font-semibold">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 shrink-0"></i>
                                        <span id="review_ev_dates_mob" class="break-words">--</span>
                                    </div>
                                    <div class="flex items-start gap-1.5 text-[#6B6357]">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#C86D51] shrink-0 mt-0.5"></i>
                                        <span id="review_ev_loc_mob" class="text-[11px] leading-tight break-words">--</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Desktop View (hidden sm:flex) -->
                            <div class="hidden sm:flex items-center gap-4 w-full">
                                <div class="w-36 h-24 rounded-xl overflow-hidden bg-stone-100 shrink-0 border border-[#EAE5D9]">
                                    <img id="review_ev_img" src="/images/news/meditation_hall.jpg" alt="Event Cover" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow min-w-0 space-y-1.5">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-[#2C7338] text-white inline-block">
                                        เปิดรับสมัคร
                                    </span>
                                    <h3 id="review_ev_title" class="font-heading font-bold text-base text-[#2D2A26] leading-snug break-words">
                                        --
                                    </h3>
                                    <p class="text-xs text-[#7A7367]">
                                        สำหรับนิสิต บุคลากร และประชาชนทั่วไป
                                    </p>
                                </div>
                                <div class="w-60 shrink-0 bg-[#F4FBF5] rounded-xl p-3 border border-[#D7EED9] text-xs space-y-1.5">
                                    <div class="flex items-center gap-1.5 text-[#2C7338] font-semibold">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 shrink-0"></i>
                                        <span id="review_ev_dates" class="break-words">--</span>
                                    </div>
                                    <div class="flex items-start gap-1.5 text-[#6B6357]">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#C86D51] shrink-0 mt-0.5"></i>
                                        <span id="review_ev_loc" class="text-[11px] leading-tight break-words">--</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- 2. ข้อมูลผู้สมัครโดยสรุป (Grid List + Edit Link) -->
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-heading font-bold text-[#2D2A26]">
                                <span class="w-5 h-5 rounded-md bg-[#2C7338] text-white flex items-center justify-center text-[10px]">
                                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                </span>
                                <span>{{ __('portal.public_summary_applicant_title') }}</span>
                            </div>
                            <button type="button" onclick="goToStep(2)" class="text-xs text-[#2C7338] hover:underline font-semibold flex items-center gap-1">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                <span>{{ __('portal.public_summary_edit_btn') }}</span>
                            </button>
                        </div>

                        <div class="p-4 sm:p-5 border border-[#E8E3D7] rounded-2xl bg-[#FBF9F4] grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-8 text-xs">
                            <div class="flex justify-between sm:justify-start gap-4">
                                <span class="text-[#7A7367] w-28 shrink-0">{{ __('portal.public_sum_name') }}</span>
                                <span class="text-[#2D2A26] font-semibold">: <span id="sum_name">--</span></span>
                            </div>
                            <div class="flex justify-between sm:justify-start gap-4">
                                <span class="text-[#7A7367] w-28 shrink-0">{{ __('portal.public_sum_province') }}</span>
                                <span class="text-[#2D2A26] font-semibold">: <span id="sum_province">--</span></span>
                            </div>
                            <div class="flex justify-between sm:justify-start gap-4">
                                <span class="text-[#7A7367] w-28 shrink-0">{{ __('portal.public_sum_status') }}</span>
                                <span class="text-[#2D2A26] font-semibold">: <span id="sum_status">ประชาชนทั่วไป</span></span>
                            </div>
                            <div class="flex justify-between sm:justify-start gap-4">
                                <span class="text-[#7A7367] w-28 shrink-0">{{ __('portal.public_sum_food') }}</span>
                                <span class="text-[#2D2A26] font-semibold">: <span id="sum_food">อาหารทั่วไป</span></span>
                            </div>
                            <div class="flex justify-between sm:justify-start gap-4">
                                <span class="text-[#7A7367] w-28 shrink-0">{{ __('portal.public_sum_phone') }}</span>
                                <span class="text-[#2D2A26] font-semibold font-mono">: <span id="sum_phone">--</span></span>
                            </div>
                            <div class="flex justify-between sm:justify-start gap-4">
                                <span class="text-[#7A7367] w-28 shrink-0">{{ __('portal.public_sum_travel') }}</span>
                                <span class="text-[#2D2A26] font-semibold">: <span id="sum_travel">รถยนต์ส่วนตัว</span></span>
                            </div>
                            <div class="flex justify-between sm:justify-start gap-4">
                                <span class="text-[#7A7367] w-28 shrink-0">{{ __('portal.public_sum_email') }}</span>
                                <span class="text-[#2D2A26] font-semibold">: <span id="sum_email">-</span></span>
                            </div>
                            <div class="flex justify-between sm:justify-start gap-4">
                                <span class="text-[#7A7367] w-28 shrink-0">{{ __('portal.public_sum_special') }}</span>
                                <span class="text-[#2D2A26] font-semibold">: <span id="sum_special">-</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. กฎ ระเบียบ และข้อปฏิบัติในการเข้าร่วม (8 Items in 2 Columns) -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#1F6B30] text-white flex items-center justify-center text-xs font-bold font-mono">1</span>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-[#2D2A26]">{{ __('portal.public_rules_title') }}</h3>
                        </div>

                        <div class="p-5 border border-[#E8E3D7] rounded-2xl bg-white grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-xs text-[#2D2A26]">
                            <!-- Col 1 -->
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center text-[10px] font-bold font-mono shrink-0 mt-0.5">1</span>
                                <span class="leading-relaxed">{{ __('portal.public_rules_item_1') }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center text-[10px] font-bold font-mono shrink-0 mt-0.5">5</span>
                                <span class="leading-relaxed">{{ __('portal.public_rules_item_2') }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center text-[10px] font-bold font-mono shrink-0 mt-0.5">2</span>
                                <span class="leading-relaxed">{{ __('portal.public_rules_item_3') }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center text-[10px] font-bold font-mono shrink-0 mt-0.5">6</span>
                                <span class="leading-relaxed">{{ __('portal.public_rules_item_4') }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center text-[10px] font-bold font-mono shrink-0 mt-0.5">3</span>
                                <span class="leading-relaxed">{{ __('portal.public_rules_item_5') }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center text-[10px] font-bold font-mono shrink-0 mt-0.5">7</span>
                                <span class="leading-relaxed">{{ __('portal.public_rules_item_6') }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center text-[10px] font-bold font-mono shrink-0 mt-0.5">4</span>
                                <span class="leading-relaxed">{{ __('portal.public_rules_item_7') }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-[#E8F3EA] text-[#2C7338] flex items-center justify-center text-[10px] font-bold font-mono shrink-0 mt-0.5">8</span>
                                <span class="leading-relaxed">{{ __('portal.public_rules_item_8') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. ข้อควรทราบเพิ่มเติม (4 Items with Megaphone Icon) -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#1F6B30] text-white flex items-center justify-center text-xs font-bold font-mono">2</span>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-[#2D2A26]">{{ __('portal.public_notes_title') }}</h3>
                        </div>

                        <div class="p-4 sm:p-5 border border-[#F4E3C8] rounded-2xl bg-[#FFFDF9] flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <!-- Megaphone Icon -->
                            <div class="w-12 h-12 rounded-2xl bg-[#FFF3DD] text-[#D88D2B] flex items-center justify-center shrink-0">
                                <i data-lucide="megaphone" class="w-6 h-6"></i>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-xs text-[#2D2A26] flex-grow">
                                <div class="flex items-start gap-2">
                                    <span class="w-4 h-4 rounded-full bg-[#F3ECE0] text-[#7A7367] flex items-center justify-center text-[9px] font-mono shrink-0 mt-0.5">1</span>
                                    <span>{{ __('portal.public_notes_item_1') }}</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-4 h-4 rounded-full bg-[#F3ECE0] text-[#7A7367] flex items-center justify-center text-[9px] font-mono shrink-0 mt-0.5">3</span>
                                    <span>{{ __('portal.public_notes_item_2') }}</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-4 h-4 rounded-full bg-[#F3ECE0] text-[#7A7367] flex items-center justify-center text-[9px] font-mono shrink-0 mt-0.5">2</span>
                                    <span>{{ __('portal.public_notes_item_3') }}</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-4 h-4 rounded-full bg-[#F3ECE0] text-[#7A7367] flex items-center justify-center text-[9px] font-mono shrink-0 mt-0.5">4</span>
                                    <span>{{ __('portal.public_notes_item_4') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Checkboxes (Rules & Accuracy Acknowledgement) -->
                    <div class="space-y-2.5 pt-2">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" id="chk_agree_rules" required class="w-4 h-4 text-[#2C7338] border-[#D5CEBC] rounded focus:ring-[#2C7338] mt-0.5">
                            <span class="text-xs text-[#2D2A26] leading-relaxed">
                                {{ __('portal.public_agree_rules_chk') }} <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" id="chk_certify_info" required class="w-4 h-4 text-[#2C7338] border-[#D5CEBC] rounded focus:ring-[#2C7338] mt-0.5">
                            <span class="text-xs text-[#2D2A26] leading-relaxed">
                                {{ __('portal.public_certify_info_chk') }} <span class="text-red-500">*</span>
                            </span>
                        </label>
                    </div>

                    <!-- Step 3 Footer Buttons & Notice -->
                    <div class="pt-6 border-t border-[#EAE5D9] flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-1.5 text-xs text-[#C86D51] order-2 sm:order-1">
                            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                            <span>{{ __('portal.public_cannot_edit_warning') }}</span>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end order-1 sm:order-2">
                            <button type="button" onclick="goToStep(2)" class="px-5 py-2.5 rounded-xl border border-[#D5CEBC] text-[#4A3B32] hover:bg-[#FAF8F2] text-xs sm:text-sm font-semibold flex items-center gap-1.5 transition">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                <span>{{ __('portal.public_btn_prev') }}</span>
                            </button>
                            <button type="submit" id="btn_final_submit" class="bg-[#2C7338] hover:bg-[#235D2E] text-white font-semibold px-7 py-2.5 rounded-xl text-xs sm:text-sm shadow-xs transition flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>{{ __('portal.public_btn_submit') }}</span>
                            </button>
                        </div>
                    </div>

                </div>

            </form>

        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-[#EAE5D9] py-5 text-center text-xs text-[#7B8D65]">
        {{ __('portal.footer_brand') }}
    </footer>

    <!-- Scripts Engine -->
    <script>
        let currentStep = 1;

        // Step Configs for Header Banner
        const stepBannerData = {
            1: {
                title: @json(__('portal.public_banner_step1_title')),
                subtitle: @json(__('portal.public_banner_step1_subtitle')),
                desc: @json(__('portal.public_banner_step1_desc'))
            },
            2: {
                title: @json(__('portal.public_banner_step2_title')),
                subtitle: @json(__('portal.public_banner_step2_subtitle')),
                desc: @json(__('portal.public_banner_step2_desc'))
            },
            3: {
                title: @json(__('portal.public_banner_step3_title')),
                subtitle: @json(__('portal.public_banner_step3_subtitle')),
                desc: @json(__('portal.public_banner_step3_desc'))
            }
        };

        const hasSelectableEvent = {{ $hasSelectableEvent ? 'true' : 'false' }};

        function goToStep(step) {
            if (step === 1) {
                // ย้อนกลับมาขั้นตอนที่ 1 ได้เสมอ
            } else {
                if (!hasSelectableEvent) {
                    alert(@json(__('portal.public_no_courses')));
                    return;
                }
                const selected = document.querySelector('input[name="event_id"]:checked');
                if (!selected) {
                    alert(@json(__('portal.public_alert_select_event')));
                    return;
                }
                syncSelectedEventCard(selected);
            }

            if (step === 3) {
                if (!validateStep2()) {
                    return;
                }
                syncSummaryData();
            }

            // Update Banner
            document.getElementById('banner_title').textContent = stepBannerData[step].title;
            document.getElementById('banner_subtitle').textContent = stepBannerData[step].subtitle;
            document.getElementById('banner_desc').innerHTML = stepBannerData[step].desc;

            // Update Stepper Visuals
            for (let s = 1; s <= 3; s++) {
                const circle = document.getElementById('step-circle-' + s);
                const text = document.getElementById('step-text-' + s);
                const num = document.getElementById('step-num-' + s);
                const icon = document.getElementById('step-icon-' + s);
                const content = document.getElementById('step-content-' + s);
                const tab = document.getElementById('step-tab-' + s);

                if (s < step) {
                    // Completed Step
                    circle.className = 'w-10 h-10 rounded-full bg-[#1F6B30] text-white flex items-center justify-center font-bold text-sm shadow-xs transition duration-200';
                    text.className = 'text-xs sm:text-sm font-heading font-medium text-[#2D2A26] mt-2 text-center whitespace-nowrap';
                    icon.classList.remove('hidden');
                    num.classList.add('hidden');
                } else if (s === step) {
                    // Current Active Step
                    circle.className = 'w-10 h-10 rounded-full bg-[#1F6B30] text-white flex items-center justify-center font-bold text-sm shadow-xs transition duration-200';
                    icon.classList.add('hidden');
                    num.classList.remove('hidden');
                    text.className = 'text-xs sm:text-sm font-heading font-bold text-[#2D2A26] mt-2 text-center whitespace-nowrap';
                } else {
                    // Future Inactive Step
                    circle.className = 'w-10 h-10 rounded-full bg-white border-2 border-[#D5CEBC] text-[#8C8275] flex items-center justify-center font-bold text-sm shadow-xs transition duration-200';
                    text.className = 'text-xs sm:text-sm font-heading font-medium text-[#7A7367] mt-2 text-center whitespace-nowrap';
                    icon.classList.add('hidden');
                    num.classList.remove('hidden');
                }

                if (tab) {
                    if (s === 1 || hasSelectableEvent) {
                        tab.classList.remove('cursor-not-allowed', 'opacity-40');
                        tab.classList.add('cursor-pointer');
                    } else {
                        tab.classList.remove('cursor-pointer');
                        tab.classList.add('cursor-not-allowed', 'opacity-40');
                    }
                }

                // Show/Hide Content
                if (s === step) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            }

            // Update Connector Lines
            const conn1 = document.getElementById('step-connector-1');
            const conn2 = document.getElementById('step-connector-2');
            if (step >= 2) {
                conn1.classList.add('active');
            } else {
                conn1.classList.remove('active');
            }
            if (step >= 3) {
                conn2.classList.add('active');
            } else {
                conn2.classList.remove('active');
            }

            currentStep = step;
            window.scrollTo({ top: 120, behavior: 'smooth' });
            lucide.createIcons();
        }

        function nextToStep(step) {
            goToStep(step);
        }

        function onEventRadioChange(radio) {
            syncSelectedEventCard(radio);
        }

        function syncSelectedEventCard(radio) {
            const card = radio.closest('.event-card');
            if (!card) return;

            const title = card.querySelector('.event-title') ? card.querySelector('.event-title').textContent.trim() : '';
            const loc = card.querySelector('.event-location') ? card.querySelector('.event-location').textContent.trim() : '';
            const dates = card.querySelector('.event-dates') ? card.querySelector('.event-dates').textContent.trim() : '';
            const img = card.querySelector('img') ? card.querySelector('img').src : '';

            // Step 2 Preview
            const sImg = document.getElementById('selected_ev_img');
            const sImgMob = document.getElementById('selected_ev_img_mob');
            const sTitle = document.getElementById('selected_ev_title');
            const sTitleMob = document.getElementById('selected_ev_title_mob');
            const sDates = document.getElementById('selected_ev_dates');
            const sDatesMob = document.getElementById('selected_ev_dates_mob');
            const sLoc = document.getElementById('selected_ev_loc');
            const sLocMob = document.getElementById('selected_ev_loc_mob');
            if (sImg) sImg.src = img;
            if (sImgMob) sImgMob.src = img;
            if (sTitle) sTitle.textContent = title;
            if (sTitleMob) sTitleMob.textContent = title;
            if (sDates) sDates.textContent = dates;
            if (sDatesMob) sDatesMob.textContent = dates;
            if (sLoc) sLoc.textContent = loc;
            if (sLocMob) sLocMob.textContent = loc;

            // Step 3 Review
            const rImg = document.getElementById('review_ev_img');
            const rImgMob = document.getElementById('review_ev_img_mob');
            const rTitle = document.getElementById('review_ev_title');
            const rTitleMob = document.getElementById('review_ev_title_mob');
            const rDates = document.getElementById('review_ev_dates');
            const rDatesMob = document.getElementById('review_ev_dates_mob');
            const rLoc = document.getElementById('review_ev_loc');
            const rLocMob = document.getElementById('review_ev_loc_mob');
            if (rImg) rImg.src = img;
            if (rImgMob) rImgMob.src = img;
            if (rTitle) rTitle.textContent = title;
            if (rTitleMob) rTitleMob.textContent = title;
            if (rDates) rDates.textContent = dates;
            if (rDatesMob) rDatesMob.textContent = dates;
            if (rLoc) rLoc.textContent = loc;
            if (rLocMob) rLocMob.textContent = loc;
        }

        function validateStep2() {
            const citizenId = document.getElementById('inp_citizen_id');
            const firstName = document.getElementById('inp_first_name');
            const lastName = document.getElementById('inp_last_name');
            const phone = document.getElementById('inp_phone');
            const prov = document.getElementById('province_select');
            const dist = document.getElementById('district_select');
            const subdist = document.getElementById('subdistrict_select');

            if (!citizenId.value.trim()) {
                alert(@json(__('portal.public_alert_citizen_id')));
                citizenId.focus();
                return false;
            }
            if (!firstName.value.trim()) {
                alert(@json(__('portal.public_alert_first_name')));
                firstName.focus();
                return false;
            }
            if (!lastName.value.trim()) {
                alert(@json(__('portal.public_alert_last_name')));
                lastName.focus();
                return false;
            }
            if (!phone.value.trim()) {
                alert(@json(__('portal.public_alert_phone')));
                phone.focus();
                return false;
            }
            if (!prov.value) {
                alert(@json(__('portal.public_alert_province')));
                prov.focus();
                return false;
            }
            if (!dist.value) {
                alert(@json(__('portal.public_alert_district')));
                dist.focus();
                return false;
            }
            if (!subdist.value) {
                alert(@json(__('portal.public_alert_subdistrict')));
                subdist.focus();
                return false;
            }

            const appType = document.querySelector('input[name="applicant_type"]:checked').value;
            if (appType === 'STUDENT') {
                const stdId = document.getElementById('txt_studentid');
                const deg = document.getElementById('select_degree_level');
                const fac = document.getElementById('txt_faculty');
                const prog = document.getElementById('txt_program_name');

                if (!stdId.value.trim() || !deg.value || !fac.value.trim() || !prog.value.trim()) {
                    alert(@json(__('portal.public_alert_student_info')));
                    return false;
                }
            }

            return true;
        }

        function validateAndGoToStep3() {
            if (validateStep2()) {
                syncSummaryData();
                goToStep(3);
            }
        }

        function syncSummaryData() {
            const prefix = document.getElementById('prefix_select').value;
            const firstName = document.getElementById('inp_first_name').value.trim();
            const lastName = document.getElementById('inp_last_name').value.trim();
            const buddhistName = document.getElementById('inp_buddhist_name').value.trim();
            const bStr = buddhistName ? ' (' + buddhistName + ')' : '';
            document.getElementById('sum_name').textContent = prefix + ' ' + firstName + ' ' + lastName + bStr;

            document.getElementById('sum_province').textContent = document.getElementById('province_select').value || '-';
            
            const appType = document.querySelector('input[name="applicant_type"]:checked').value;
            document.getElementById('sum_status').textContent = appType === 'STUDENT' ? @json(__('portal.public_status_student')) : @json(__('portal.public_status_people'));

            const foodSel = document.getElementById('inp_food_type');
            document.getElementById('sum_food').textContent = foodSel.options[foodSel.selectedIndex].text;

            document.getElementById('sum_phone').textContent = document.getElementById('inp_phone').value.trim() || '-';
            
            const vehicle = document.getElementById('inp_vehicle_info').value;
            document.getElementById('sum_travel').textContent = vehicle.includes('รถยนต์') ? 'รถยนต์ส่วนตัว' : (vehicle.includes('รถตู้') ? 'รถตู้สถาบัน' : vehicle);

            document.getElementById('sum_email').textContent = document.getElementById('inp_email').value.trim() || '-';
            document.getElementById('sum_special').textContent = document.getElementById('inp_special_needs').value.trim() || @json(__('portal.public_none'));

            const selected = document.querySelector('input[name="event_id"]:checked');
            if (selected) {
                syncSelectedEventCard(selected);
            }
        }

        function toggleCustomRoom(val) {
            const customWrapper = document.getElementById('room_custom_wrapper');
            const customInput = document.getElementById('inp_room_custom');
            const hiddenInput = document.getElementById('inp_room_info');
            if (val === 'OTHER') {
                customWrapper.classList.remove('hidden');
                customInput.focus();
                hiddenInput.value = customInput.value.trim() ? ('อื่น ๆ: ' + customInput.value.trim()) : 'อื่น ๆ';
            } else {
                customWrapper.classList.add('hidden');
                hiddenInput.value = val;
            }
        }

        function updateRoomInfoHidden() {
            const selectVal = document.getElementById('inp_room_info_select').value;
            const customInput = document.getElementById('inp_room_custom');
            const hiddenInput = document.getElementById('inp_room_info');
            if (selectVal === 'OTHER') {
                hiddenInput.value = customInput.value.trim() ? ('อื่น ๆ: ' + customInput.value.trim()) : 'อื่น ๆ';
            } else {
                hiddenInput.value = selectVal;
            }
        }

        function toggleStudentField(val) {
            const el = document.getElementById('studentIdContainer');
            const studentIdInput = document.getElementById('txt_studentid');
            const degreeSelect = document.getElementById('select_degree_level');
            const facultyInput = document.getElementById('txt_faculty');
            const programInput = document.getElementById('txt_program_name');

            if (val === 'STUDENT') {
                el.classList.remove('hidden');
                if (studentIdInput) studentIdInput.required = true;
                if (degreeSelect) degreeSelect.required = true;
                if (facultyInput) facultyInput.required = true;
                if (programInput) programInput.required = true;
            } else {
                el.classList.add('hidden');
                if (studentIdInput) { studentIdInput.value = ''; studentIdInput.required = false; }
                if (degreeSelect) { degreeSelect.value = ''; degreeSelect.required = false; }
                if (facultyInput) { facultyInput.value = ''; facultyInput.required = false; }
                if (programInput) { programInput.value = ''; programInput.required = false; }
            }
        }

        // Thailand Address Cascading Engine
        let thaiProvinces = [];
        let thaiDistricts = [];
        let thaiSubdistricts = [];

        document.addEventListener('DOMContentLoaded', async () => {
            // Initial sync for first radio selection
            const firstRadio = document.querySelector('input[name="event_id"]:checked');
            if (firstRadio) {
                syncSelectedEventCard(firstRadio);
            }

            // Auto-advance to Step 2 if event_id parameter exists in URL and is matched
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('event_id') && firstRadio && !firstRadio.disabled) {
                goToStep(2);
            }

            try {
                const resProv = await fetch('/assets/data/provinces.json');
                thaiProvinces = await resProv.json();
                thaiProvinces.sort((a, b) => a.provinceNameTh.localeCompare(b.provinceNameTh, 'th'));

                const provSelect = document.getElementById('province_select');
                provSelect.innerHTML = '<option value="">-- กรุณาเลือกจังหวัด --</option>';
                thaiProvinces.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.provinceNameTh;
                    opt.textContent = p.provinceNameTh;
                    opt.dataset.provinceCode = p.provinceCode;
                    provSelect.appendChild(opt);
                });

                fetch('/assets/data/districts.json')
                    .then(r => r.json())
                    .then(data => { thaiDistricts = data; });

                fetch('/assets/data/subdistricts.json')
                    .then(r => r.json())
                    .then(data => { thaiSubdistricts = data; });

            } catch (err) {
                console.error('Failed to load address data', err);
            }
        });

        function onProvinceChange(provinceName) {
            const distSelect = document.getElementById('district_select');
            const subSelect = document.getElementById('subdistrict_select');
            const postalInput = document.getElementById('postal_code_input');

            distSelect.innerHTML = '<option value="">-- กำลังโหลดอำเภอ... --</option>';
            distSelect.disabled = true;
            subSelect.innerHTML = '<option value="">-- กรุณาเลือกอำเภอก่อน --</option>';
            subSelect.disabled = true;
            postalInput.value = '';

            if (!provinceName) {
                distSelect.innerHTML = '<option value="">-- กรุณาเลือกจังหวัดก่อน --</option>';
                return;
            }

            const provOption = document.querySelector(`#province_select option[value="${provinceName}"]`);
            const provinceCode = provOption ? parseInt(provOption.dataset.provinceCode) : null;

            const filteredDistricts = thaiDistricts.filter(d => d.provinceCode === provinceCode);
            filteredDistricts.sort((a, b) => a.districtNameTh.localeCompare(b.districtNameTh, 'th'));

            distSelect.innerHTML = '<option value="">-- เลือกอำเภอ / เขต --</option>';
            filteredDistricts.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.districtNameTh;
                opt.textContent = d.districtNameTh;
                opt.dataset.districtCode = d.districtCode;
                distSelect.appendChild(opt);
            });
            distSelect.disabled = false;
        }

        function onDistrictChange(districtName) {
            const subSelect = document.getElementById('subdistrict_select');
            const postalInput = document.getElementById('postal_code_input');

            subSelect.innerHTML = '<option value="">-- กำลังโหลดตำบล... --</option>';
            subSelect.disabled = true;
            postalInput.value = '';

            if (!districtName) {
                subSelect.innerHTML = '<option value="">-- กรุณาเลือกอำเภอก่อน --</option>';
                return;
            }

            const distOption = document.querySelector(`#district_select option[value="${districtName}"]`);
            const districtCode = distOption ? parseInt(distOption.dataset.districtCode) : null;

            const filteredSubdistricts = thaiSubdistricts.filter(s => s.districtCode === districtCode);
            filteredSubdistricts.sort((a, b) => a.subdistrictNameTh.localeCompare(b.subdistrictNameTh, 'th'));

            subSelect.innerHTML = '<option value="">-- เลือกตำบล / แขวง --</option>';
            filteredSubdistricts.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.subdistrictNameTh;
                opt.textContent = s.subdistrictNameTh;
                opt.dataset.postalCode = s.postalCode || '';
                subSelect.appendChild(opt);
            });
            subSelect.disabled = false;
        }

        function onSubdistrictChange(subdistrictName) {
            const subOption = document.querySelector(`#subdistrict_select option[value="${subdistrictName}"]`);
            const postalInput = document.getElementById('postal_code_input');
            if (subOption && subOption.dataset.postalCode) {
                postalInput.value = subOption.dataset.postalCode;
            }
        }

        // ==============================================================
        // AUTO-FILL LOOKUP ENGINE (ดึงข้อมูลผู้สมัครเดิมอัตโนมัติ)
        // ==============================================================
        let lookupTimer = null;
        let lastLookedUpStudentId = '';
        let lastLookedUpCitizenId = '';

        async function triggerApplicantLookup(type) {
            const isStudent = (type === 'STUDENT');
            const inputEl = isStudent ? document.getElementById('txt_studentid') : document.getElementById('inp_citizen_id');
            const spinnerEl = isStudent ? document.getElementById('spinner_studentid') : document.getElementById('spinner_citizen_id');
            const val = inputEl ? inputEl.value.trim() : '';

            if (!val) return;

            // ตรวจสอบเงื่อนไขความยาวขั้นต่ำก่อนยิงค้นหา
            const cleanDigits = val.replace(/[^0-9]/g, '');
            if (isStudent && cleanDigits.length < 8 && val.length < 8) return;
            if (!isStudent && cleanDigits.length < 13 && val.length < 7) return;

            // ป้องกันการยิงค้นหาซ้ำถ้าค่าเดิมไม่เปลี่ยน
            if (isStudent && val === lastLookedUpStudentId) return;
            if (!isStudent && val === lastLookedUpCitizenId) return;

            if (spinnerEl) spinnerEl.classList.remove('hidden');

            try {
                const endpoint = `{{ url('/public_lookup.php') }}?type=${encodeURIComponent(type)}&code=${encodeURIComponent(val)}`;
                const res = await fetch(endpoint, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await res.json();

                if (result.found && result.data) {
                    if (isStudent) lastLookedUpStudentId = val;
                    else lastLookedUpCitizenId = val;

                    populateApplicantFields(result.data, isStudent);
                    showAutofillNotice(result.data);
                }
            } catch (err) {
                console.warn('Auto-fill lookup failed:', err);
            } finally {
                if (spinnerEl) spinnerEl.classList.add('hidden');
            }
        }

        async function populateApplicantFields(data, isStudent) {
            // ฟิลด์นิสิต มจร
            if (isStudent) {
                if (data.student_id && document.getElementById('txt_studentid')) {
                    document.getElementById('txt_studentid').value = data.student_id;
                }
                if (data.degree_level && document.getElementById('select_degree_level')) {
                    document.getElementById('select_degree_level').value = data.degree_level;
                }
                if (data.faculty && document.getElementById('txt_faculty')) {
                    document.getElementById('txt_faculty').value = data.faculty;
                }
                if (data.program_name && document.getElementById('txt_program_name')) {
                    document.getElementById('txt_program_name').value = data.program_name;
                }
            }

            // ฟิลด์ข้อมูลส่วนตัว
            if (data.citizen_id && document.getElementById('inp_citizen_id') && (!document.getElementById('inp_citizen_id').value || isStudent)) {
                document.getElementById('inp_citizen_id').value = data.citizen_id;
            }
            if (data.prefix && document.getElementById('prefix_select')) {
                document.getElementById('prefix_select').value = data.prefix;
            }
            if (data.first_name && document.getElementById('inp_first_name')) {
                document.getElementById('inp_first_name').value = data.first_name;
            }
            if (data.last_name && document.getElementById('inp_last_name')) {
                document.getElementById('inp_last_name').value = data.last_name;
            }
            if (data.buddhist_name && document.getElementById('inp_buddhist_name')) {
                document.getElementById('inp_buddhist_name').value = data.buddhist_name;
            }
            if (data.vassa !== undefined && data.vassa !== null && document.getElementById('inp_vassa')) {
                document.getElementById('inp_vassa').value = data.vassa;
            }
            if (data.age && document.getElementById('inp_age')) {
                document.getElementById('inp_age').value = data.age;
            }
            if (data.phone && document.getElementById('inp_phone')) {
                document.getElementById('inp_phone').value = data.phone;
            }
            if (data.email && document.getElementById('inp_email')) {
                document.getElementById('inp_email').value = data.email;
            }
            if (data.line_id && document.getElementById('inp_line')) {
                document.getElementById('inp_line').value = data.line_id;
            }

            // ฟิลด์ที่อยู่ (Province / District / Subdistrict Cascading)
            if (data.address && document.getElementById('inp_address')) {
                document.getElementById('inp_address').value = data.address;
            }
            if (data.province && document.getElementById('province_select')) {
                document.getElementById('province_select').value = data.province;
                onProvinceChange(data.province);

                if (data.district && document.getElementById('district_select')) {
                    document.getElementById('district_select').value = data.district;
                    onDistrictChange(data.district);

                    if (data.subdistrict && document.getElementById('subdistrict_select')) {
                        document.getElementById('subdistrict_select').value = data.subdistrict;
                        onSubdistrictChange(data.subdistrict);
                    }
                }
            }
            if (data.postal_code && document.getElementById('postal_code_input')) {
                document.getElementById('postal_code_input').value = data.postal_code;
            }

            // ฟิลด์ความต้องการ อาหาร / ยานพาหนะ
            if (data.dietary_restriction && document.getElementById('inp_food_type')) {
                document.getElementById('inp_food_type').value = data.dietary_restriction;
            }
            if (data.congenital_disease && document.getElementById('inp_special_needs')) {
                document.getElementById('inp_special_needs').value = data.congenital_disease;
            }
        }

        function showAutofillNotice(data) {
            const banner = document.getElementById('autofill_banner');
            const textEl = document.getElementById('autofill_banner_text');
            if (banner && textEl) {
                const name = (data.prefix || '') + (data.first_name || '') + ' ' + (data.last_name || '');
                textEl.innerHTML = `พบข้อมูลเดิมของ <strong>${name.trim()}</strong> ระบบได้เติมข้อมูลให้อัตโนมัติเรียบร้อยแล้ว`;
                banner.classList.remove('hidden');
                lucide.createIcons();

                setTimeout(() => {
                    banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            }
        }

        // Attach Debounced & Blur Lookup Events
        document.addEventListener('DOMContentLoaded', () => {
            const stdInput = document.getElementById('txt_studentid');
            const citInput = document.getElementById('inp_citizen_id');

            if (stdInput) {
                stdInput.addEventListener('input', () => {
                    clearTimeout(lookupTimer);
                    lookupTimer = setTimeout(() => triggerApplicantLookup('STUDENT'), 400);
                });
                stdInput.addEventListener('blur', () => triggerApplicantLookup('STUDENT'));
            }

            if (citInput) {
                citInput.addEventListener('input', () => {
                    const currentType = document.querySelector('input[name="applicant_type"]:checked')?.value || 'PEOPLE';
                    clearTimeout(lookupTimer);
                    lookupTimer = setTimeout(() => triggerApplicantLookup(currentType), 400);
                });
                citInput.addEventListener('blur', () => {
                    const currentType = document.querySelector('input[name="applicant_type"]:checked')?.value || 'PEOPLE';
                    triggerApplicantLookup(currentType);
                });
            }
        });

        // Form Submit Handler
        const registerForm = document.getElementById('publicRegisterForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                // Verify event selected
                const selectedEv = document.querySelector('input[name="event_id"]:checked');
                if (!selectedEv) {
                    e.preventDefault();
                    alert(@json(__('portal.public_alert_select_event')));
                    goToStep(1);
                    return false;
                }

                // Verify Step 2 fields
                if (!validateStep2()) {
                    e.preventDefault();
                    goToStep(2);
                    return false;
                }

                // Clean optional email if placeholder, dash, or text
                const emailInp = document.getElementById('inp_email');
                if (emailInp) {
                    const em = emailInp.value.trim().toLowerCase();
                    if (['-', '--', '---', 'ไม่มี', 'ไม่มีค่ะ', 'ไม่มีครับ', 'none', 'no', 'n/a', 'null'].includes(em)) {
                        emailInp.value = '';
                    }
                }

                // Verify Step 3 checkboxes
                const chkRules = document.getElementById('chk_agree_rules');
                const chkCert = document.getElementById('chk_certify_info');
                if (chkRules && !chkRules.checked) {
                    e.preventDefault();
                    alert('กรุณากดยอมรับกฎระเบียบและข้อปฏิบัติตนในการเข้าร่วมโครงการ');
                    chkRules.focus();
                    return false;
                }
                if (chkCert && !chkCert.checked) {
                    e.preventDefault();
                    alert('กรุณากดยืนยันรับรองว่าข้อมูลทั้งหมดเป็นความจริง');
                    chkCert.focus();
                    return false;
                }

                // Prevent multiple clicks & show loading indicator
                const submitBtn = document.getElementById('btn_final_submit');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        กำลังส่งข้อมูล...
                    `;
                }
                return true;
            });
        }
    </script>
    <script>
        lucide.createIcons();
    </script>

</body>
</html>
