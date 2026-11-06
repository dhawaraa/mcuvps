@php
    use App\Models\SiteSetting;
    $contactSettings = SiteSetting::getByGroup('contact');
    $currentRoute = Route::currentRouteName();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('portal.nav_contact') }} | VPSMCU {{ __('portal.system_title') }} {{ __('portal.mcu_short') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>
    <script src="/assets/js/font-resizer.js"></script>

    <!-- Tailwind CSS CDN -->
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
                        earth: {
                            sand: '#F7F5EE',
                            stone: '#EAE5D9',
                            clay: '#C86D51',
                            clayDark: '#A85238',
                            forest: '#2C3E2D',
                            forestDark: '#243325',
                            olive: '#5A6B47',
                            oliveLight: '#7B8D65',
                            bark: '#4A3B32',
                            barkDark: '#2D2A26'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Sarabun', sans-serif; 
            background-color: #FAF8F2; 
            color: #2D2A26;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }
        .organic-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid #EAE5D9;
            box-shadow: 0 10px 30px -10px rgba(74, 59, 50, 0.05);
        }
        .hero-banner-card {
            background-image: linear-gradient(to right, rgba(21, 87, 36, 0.96) 0%, rgba(21, 87, 36, 0.88) 45%, rgba(21, 87, 36, 0.20) 80%, transparent 100%), url('{{ asset("images/hero2image.png") }}');
            background-size: cover;
            background-position: right 25%;
            background-repeat: no-repeat;
            min-height: 200px;
        }
        @media (max-width: 768px) {
            .hero-banner-card {
                background-position: right center;
                min-height: 170px;
            }
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between selection:bg-[#5A6B47] selection:text-white">

    <!-- Top Announcement Bar (Deep Forest - Hidden on mobile) -->
    <div class="hidden sm:block bg-[#243325] text-[#D5CEBC] text-xs py-2 px-4 border-b border-[#1E2B1F]">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-1 font-medium">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-[#7B8D65]"></span>
                <span class="text-[#EAE5D9]">{{ __('portal.top_announcement') }}</span>
            </div>
            <div class="flex items-center space-x-4 text-[#D8D2C2] text-[11px]">
                <span class="flex items-center gap-1.5"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#A3B88C]"></i> {{ __('portal.institute_name') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 bg-[#FAF8F2]/95 backdrop-blur-xl border-b border-[#E3DEC9] shadow-xs transition">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo & Brand (Matching Reference) -->
                <div class="flex items-center space-x-3.5">
                    <a href="{{ route('home') }}" class="shrink-0 flex items-center">
                        <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-12 h-12 object-contain drop-shadow-sm hover:scale-105 transition">
                    </a>
                    <div>
                        <a href="{{ route('home') }}" class="font-heading font-extrabold text-xl text-[#2C3E2D] tracking-tight leading-tight flex items-center gap-2">
                            VPSMCU
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#EAE5D9] text-[#4A3B32] border border-[#D5CEBC]">{{ __('portal.mcu_short') }}</span>
                        </a>
                        <p class="text-xs text-[#6B6357] font-medium">{{ __('portal.system_title') }}</p>
                    </div>
                </div>

                <!-- Nav Links: ปฏิทิน (Dropdown), ตรวจสอบวัน, ยื่นคำร้อง, ฐานข้อมูล, ติดต่อ, ร่วมบริจาค -->
                <nav class="hidden xl:flex items-center space-x-6 text-[15px] font-semibold text-[#4A3B32]">
                    <!-- Schedule Dropdown Menu -->
                    <div class="relative group py-2">
                        <a href="{{ route('home') }}#calendar" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 focus:outline-none whitespace-nowrap py-1">
                            <i data-lucide="calendar" class="w-4 h-4 text-[#4A3B32]"></i>
                            <span>{{ __('portal.nav_calendar') }}</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-[#8C8275] group-hover:rotate-180 transition-transform duration-200"></i>
                        </a>
                        <!-- Dropdown Panel -->
                        <div class="absolute left-0 top-full pt-2 w-64 hidden group-hover:block z-50 transition-all">
                            <div class="bg-white/95 backdrop-blur-md border border-[#D5CEBC] rounded-2xl shadow-xl p-2 space-y-1">
                                <a href="{{ route('home') }}#calendar" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-[#2C3E2D] hover:bg-[#FAF8F2] transition">
                                    <span class="w-8 h-8 rounded-lg bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47] shrink-0">
                                        <i data-lucide="calendar-range" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <div class="font-semibold text-sm">{{ __('portal.nav_all_schedules') }}</div>
                                        <div class="text-xs text-[#7B8D65]">{{ __('portal.nav_all_schedules_desc') }}</div>
                                    </div>
                                </a>
                                <a href="{{ route('home') }}#calendar" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-[#2C3E2D] hover:bg-[#FAF8F2] transition">
                                    <span class="w-8 h-8 rounded-lg bg-[#5A6B47]/15 flex items-center justify-center text-[#5A6B47] shrink-0">
                                        <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <div class="font-semibold text-sm text-[#5A6B47]">{{ __('portal.nav_ug_schedules') }}</div>
                                        <div class="text-xs text-[#7B8D65]">{{ __('portal.nav_ug_schedules_desc') }}</div>
                                    </div>
                                </a>
                                <a href="{{ route('home') }}#calendar" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-[#2C3E2D] hover:bg-[#FAF8F2] transition">
                                    <span class="w-8 h-8 rounded-lg bg-[#C86D51]/15 flex items-center justify-center text-[#C86D51] shrink-0">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <div class="font-semibold text-sm text-[#C86D51]">{{ __('portal.nav_public_schedules') }}</div>
                                        <div class="text-xs text-[#7B8D65]">{{ __('portal.nav_public_schedules_desc') }}</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ตรวจสอบวัน -->
                    <a href="{{ route('grad.progress') }}" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="graduation-cap" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>{{ __('portal.nav_verify_days') }}</span>
                    </a>

                    <!-- ยื่นคำร้อง -->
                    <a href="{{ route('grad.request') }}" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="file-text" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>{{ __('portal.nav_request_cert') }}</span>
                    </a>

                    <!-- ฐานข้อมูล -->
                    <a href="{{ route('ug.check') }}" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="users" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>{{ __('portal.nav_database') }}</span>
                    </a>

                    <!-- ติดต่อ -->
                    <a href="{{ route('contact') }}" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="phone" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>{{ __('portal.nav_contact') }}</span>
                    </a>

                    <!-- ร่วมบริจาค -->
                    <a href="{{ route('donation') }}" class="hover:text-[#A85238] transition flex items-center gap-1.5 text-[#C86D51] font-bold whitespace-nowrap py-1">
                        <i data-lucide="gift" class="w-4.5 h-4.5 text-[#C86D51]"></i>
                        <span>{{ __('portal.nav_donation') }}</span>
                    </a>
                </nav>

                <!-- Actions: Font Resizer, Language Switcher & Hamburger Button -->
                <div class="flex items-center space-x-2 sm:space-x-2.5">
                    <!-- Font Size Resizer (Hidden on tiny screens, accessible in drawer and md+) -->
                    <div class="hidden sm:flex items-center">
                        @include('portal.partials.font_resizer')
                    </div>

                    @php
                        $currentLang = session('locale', 'th');
                    @endphp
                    <div class="flex items-center bg-[#D8D2C2] p-1 rounded-full text-xs font-bold font-mono">
                        <a href="{{ route('lang.switch', 'th') }}" title="ภาษาไทย" class="px-2.5 py-1 rounded-full transition {{ $currentLang === 'th' ? 'bg-[#5A6B47] text-white shadow-xs' : 'text-[#5A544A] hover:text-[#2C3E2D]' }}">
                            TH
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" title="English" class="px-2.5 py-1 rounded-full transition {{ $currentLang === 'en' ? 'bg-[#5A6B47] text-white shadow-xs' : 'text-[#5A544A] hover:text-[#2C3E2D]' }}">
                            EN
                        </a>
                    </div>

                    <!-- Hamburger Button (Visible on screens < xl) -->
                    <button type="button" id="mobile-menu-btn" onclick="toggleMobileMenu()" class="xl:hidden p-2 rounded-xl text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#EAE5D9] transition focus:outline-none" aria-label="Toggle navigation menu">
                        <i data-lucide="menu" id="hamburger-icon" class="w-6 h-6"></i>
                        <i data-lucide="x" id="close-icon" class="w-6 h-6 hidden"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown (Drawer) -->
        <div id="mobile-menu-drawer" class="hidden xl:hidden bg-[#FAF8F2] border-b border-[#E3DEC9] shadow-lg transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 space-y-2">
                <!-- ปฏิทินโครงการ (Collapsible Sub-menu) -->
                <div class="border-b border-[#EAE5D9] pb-2">
                    <button type="button" onclick="toggleMobileCalendar()" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#4A3B32] hover:bg-white hover:text-[#5A6B47] transition">
                        <div class="flex items-center gap-3">
                            <i data-lucide="calendar" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                            <span>{{ __('portal.nav_calendar') }}</span>
                        </div>
                        <i data-lucide="chevron-down" id="mobile-calendar-chevron" class="w-4 h-4 text-[#8C8275] transition-transform duration-200"></i>
                    </button>
                    <!-- Sub-menu Items (Hidden by default) -->
                    <div id="mobile-calendar-sub" class="hidden pl-4 pr-1 py-1 space-y-1 bg-[#F5F2E9]/60 rounded-xl mt-1">
                        <a href="{{ route('home') }}#calendar" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-[#2C3E2D] hover:bg-white transition">
                            <span class="w-6 h-6 rounded-md bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47] shrink-0">
                                <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i>
                            </span>
                            <div>
                                <div class="font-semibold">{{ __('portal.nav_all_schedules') }}</div>
                                <div class="text-[10px] text-[#7B8D65]">{{ __('portal.nav_all_schedules_desc') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('home') }}#calendar" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-[#2C3E2D] hover:bg-white transition">
                            <span class="w-6 h-6 rounded-md bg-[#5A6B47]/15 flex items-center justify-center text-[#5A6B47] shrink-0">
                                <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i>
                            </span>
                            <div>
                                <div class="font-semibold text-[#5A6B47]">{{ __('portal.nav_ug_schedules') }}</div>
                                <div class="text-[10px] text-[#7B8D65]">{{ __('portal.nav_ug_schedules_desc') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('home') }}#calendar" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-[#2C3E2D] hover:bg-white transition">
                            <span class="w-6 h-6 rounded-md bg-[#C86D51]/15 flex items-center justify-center text-[#C86D51] shrink-0">
                                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            </span>
                            <div>
                                <div class="font-semibold text-[#C86D51]">{{ __('portal.nav_public_schedules') }}</div>
                                <div class="text-[10px] text-[#7B8D65]">{{ __('portal.nav_public_schedules_desc') }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- ลิงก์เมนูหลัก -->
                <div class="space-y-1 pt-1">
                    <a href="{{ route('grad.progress') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#4A3B32] hover:bg-white hover:text-[#5A6B47] transition">
                        <i data-lucide="graduation-cap" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_verify_days') }}</span>
                    </a>
                    <a href="{{ route('grad.request') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#4A3B32] hover:bg-white hover:text-[#5A6B47] transition">
                        <i data-lucide="file-text" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_request_cert') }}</span>
                    </a>
                    <a href="{{ route('student.login') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#4A3B32] hover:bg-white hover:text-[#5A6B47] transition">
                        <i data-lucide="users" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_database') }}</span>
                    </a>
                    <a href="{{ route('contact') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#5A6B47] bg-[#5A6B47]/10 hover:bg-[#5A6B47]/20 transition">
                        <i data-lucide="phone" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_contact') }}</span>
                    </a>
                    <a href="{{ route('donation') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold text-[#C86D51] bg-[#C86D51]/10 hover:bg-[#C86D51]/20 transition">
                        <i data-lucide="gift" class="w-4.5 h-4.5 text-[#C86D51]"></i>
                        <span>{{ __('portal.nav_donation') }}</span>
                    </a>
                </div>

                <!-- Mobile Font Size Resizer Row -->
                <div class="sm:hidden pt-3 border-t border-[#EAE5D9] flex items-center justify-between px-2">
                    <span class="text-xs font-semibold text-[#6B6357] flex items-center gap-1.5">
                        <i data-lucide="type" class="w-4 h-4 text-[#5A6B47]"></i>
                        {{ __('portal.font_size_label') }}
                    </span>
                    @include('portal.partials.font_resizer')
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- Header Hero Banner (Matching donation standard) -->
        <div class="hero-banner-card rounded-[28px] p-6 sm:p-10 text-white shadow-md border border-[#205C29]/40 relative overflow-hidden mb-8 flex flex-col justify-center">
            <div class="relative z-10 max-w-xl">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold tracking-tight mb-2 drop-shadow-md text-white">
                    {{ __('portal.contact_header_title') }}
                </h1>
                <p class="text-sm sm:text-base md:text-lg text-emerald-100 font-medium drop-shadow-sm">
                    {{ __('portal.contact_header_desc') }}
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-[#5A6B47]/10 border-l-4 border-[#5A6B47] p-5 rounded-r-2xl mb-8 shadow-sm">
                <div class="flex items-start">
                    <i data-lucide="check-circle" class="w-6 h-6 text-[#5A6B47] mr-3 shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-base font-bold text-[#2C3E2D] font-heading">{{ session('success') }}</h4>
                        @if (session('ticket_no'))
                            <p class="text-xs text-[#4A3B32] mt-1">
                                เลขที่อ้างอิงการติดต่อ (Ticket ID): <strong class="font-mono text-[#C86D51] font-bold text-sm">{{ session('ticket_no') }}</strong> (กรุณาบันทึกไว้เพื่อใช้อ้างอิงการติดตามเรื่อง)
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl mb-8 shadow-sm">
                <div class="flex items-center mb-1">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-2 shrink-0"></i>
                    <h5 class="text-sm font-bold text-red-800">กรุณาตรวจสอบข้อมูลที่กรอก</h5>
                </div>
                <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5 ml-2">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            
            <!-- Left Column: Contact Information Cards (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Main Office Info -->
                <div class="organic-card rounded-3xl p-6 md:p-7 border border-[#EAE5D9]">
                    <div class="flex items-center gap-3 mb-5 pb-4 border-b border-[#EAE5D9]">
                        <div class="w-12 h-12 rounded-2xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center shrink-0 border border-[#5A6B47]/20">
                            <i data-lucide="building" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-mono uppercase text-[#7B8D65] font-semibold">{{ __('portal.contact_office_title') }}</span>
                            <h3 class="font-heading font-bold text-base text-[#2C3E2D] leading-tight">
                                {{ $contactSettings['contact_org_name'] ?? 'สถาบันวิปัสสนาธุระ มจร' }}
                            </h3>
                        </div>
                    </div>

                    <div class="space-y-4 text-xs text-[#4A3B32]">
                        <div class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#C86D51] shrink-0 mt-0.5"></i>
                            <p class="leading-relaxed">
                                {{ $contactSettings['contact_address'] ?? 'อาคาร 75 ปี พระพรหมมังคลาจารย์ มจร วังน้อย อยุธยา' }}
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <i data-lucide="clock" class="w-4 h-4 text-[#5A6B47] shrink-0 mt-0.5"></i>
                            <p class="leading-relaxed">
                                <strong class="text-[#2C3E2D]">{{ __('portal.contact_office_hours_label') }}</strong> {{ $contactSettings['contact_office_hours'] ?? 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Fast Contact Methods (Phone, Hotline, LINE, Email) -->
                <div class="organic-card rounded-3xl p-6 md:p-7 border border-[#EAE5D9]">
                    <h3 class="font-heading font-bold text-base text-[#2C3E2D] mb-4 flex items-center gap-2">
                        <i data-lucide="phone-forwarded" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>{{ __('portal.contact_fast_methods') }}</span>
                    </h3>

                    <div class="space-y-3.5">
                        
                        <!-- Phone -->
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-[#FAF8F2] border border-[#EAE5D9]">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center shrink-0">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] text-[#7B8D65]">{{ __('portal.contact_phone_main') }}</div>
                                    <div class="font-semibold text-xs text-[#2C3E2D] font-mono">{{ $contactSettings['contact_phone'] ?? '035-248-000' }}</div>
                                </div>
                            </div>
                            <a href="tel:{{ preg_replace('/[^0-9]/', '', $contactSettings['contact_phone'] ?? '') }}" class="px-2.5 py-1 bg-white hover:bg-[#5A6B47] hover:text-white border border-[#D5CEBC] rounded-lg text-[11px] font-semibold text-[#4A3B32] transition">
                                {{ __('portal.contact_call_btn') }}
                            </a>
                        </div>

                        <!-- Hotline -->
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-[#FAF8F2] border border-[#EAE5D9]">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#C86D51]/15 text-[#C86D51] flex items-center justify-center shrink-0">
                                    <i data-lucide="smartphone" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] text-[#C86D51]">{{ __('portal.contact_hotline') }}</div>
                                    <div class="font-semibold text-xs text-[#2C3E2D] font-mono">{{ $contactSettings['contact_hotline'] ?? '084-456-4554' }}</div>
                                </div>
                            </div>
                            <a href="tel:{{ preg_replace('/[^0-9]/', '', $contactSettings['contact_hotline'] ?? '') }}" class="px-2.5 py-1 bg-white hover:bg-[#C86D51] hover:text-white border border-[#D5CEBC] rounded-lg text-[11px] font-semibold text-[#4A3B32] transition">
                                {{ __('portal.contact_call_btn') }}
                            </a>
                        </div>

                        <!-- Email -->
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-[#FAF8F2] border border-[#EAE5D9]">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#2C3E2D]/15 text-[#2C3E2D] flex items-center justify-center shrink-0">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] text-[#7B8D65]">{{ __('portal.contact_email_coord') }}</div>
                                    <div class="font-semibold text-xs text-[#2C3E2D] font-mono">{{ $contactSettings['contact_email'] ?? 'vipassana@mcu.ac.th' }}</div>
                                </div>
                            </div>
                            <a href="mailto:{{ $contactSettings['contact_email'] ?? 'vipassana@mcu.ac.th' }}" class="px-2.5 py-1 bg-white hover:bg-[#2C3E2D] hover:text-white border border-[#D5CEBC] rounded-lg text-[11px] font-semibold text-[#4A3B32] transition">
                                {{ __('portal.contact_email_btn') }}
                            </a>
                        </div>

                        <!-- LINE & Social -->
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <a href="https://line.me/ti/p/~{{ ltrim($contactSettings['contact_line_id'] ?? '@mcu.vipassana', '@') }}" target="_blank" class="p-3 rounded-2xl bg-[#06C755]/10 border border-[#06C755]/20 hover:bg-[#06C755]/20 transition flex items-center gap-2.5">
                                <i data-lucide="message-circle" class="w-4 h-4 text-[#06C755]"></i>
                                <div>
                                    <div class="text-[9px] text-[#4A3B32]">LINE ID</div>
                                    <div class="font-bold text-[11px] text-[#2C3E2D] font-mono truncate">{{ $contactSettings['contact_line_id'] ?? '@mcu.vipassana' }}</div>
                                </div>
                            </a>
                            <a href="{{ $contactSettings['contact_facebook'] ?? 'https://facebook.com/vipassanamcu' }}" target="_blank" class="p-3 rounded-2xl bg-[#1877F2]/10 border border-[#1877F2]/20 hover:bg-[#1877F2]/20 transition flex items-center gap-2.5">
                                <i data-lucide="facebook" class="w-4 h-4 text-[#1877F2]"></i>
                                <div>
                                    <div class="text-[9px] text-[#4A3B32]">Facebook</div>
                                    <div class="font-bold text-[11px] text-[#2C3E2D] truncate">{{ __('portal.contact_fb_page') }}</div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Right Column: Interactive Contact Form (7 cols) -->
            <div class="lg:col-span-7">
                <div class="organic-card rounded-3xl p-6 md:p-8 border border-[#EAE5D9]">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-[#EAE5D9]">
                        <div>
                            <h2 class="font-heading font-bold text-xl text-[#2C3E2D]">{{ __('portal.contact_form_title') }}</h2>
                            <p class="text-xs text-[#7B8D65] mt-1">{{ __('portal.contact_form_desc') }}</p>
                        </div>
                        <span class="p-3 rounded-2xl bg-[#5A6B47]/15 text-[#5A6B47]">
                            <i data-lucide="send" class="w-6 h-6"></i>
                        </span>
                    </div>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">
                                    {{ __('portal.contact_sender_name') }} <span class="text-[#C86D51]">*</span>
                                </label>
                                <input type="text" name="sender_name" value="{{ old('sender_name') }}" required placeholder="{{ __('portal.contact_sender_name') }}" class="w-full px-3.5 py-2.5 bg-white border border-[#EAE5D9] rounded-xl text-xs text-[#2D2A26] focus:ring-2 focus:ring-[#5A6B47] focus:border-[#5A6B47]">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">
                                    {{ __('portal.contact_sender_phone') }} <span class="text-[#C86D51]">*</span>
                                </label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="081-234-5678" class="w-full px-3.5 py-2.5 bg-white border border-[#EAE5D9] rounded-xl text-xs text-[#2D2A26] focus:ring-2 focus:ring-[#5A6B47] focus:border-[#5A6B47]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">
                                    {{ __('portal.contact_sender_email') }}
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@mcu.ac.th" class="w-full px-3.5 py-2.5 bg-white border border-[#EAE5D9] rounded-xl text-xs text-[#2D2A26] focus:ring-2 focus:ring-[#5A6B47] focus:border-[#5A6B47]">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">
                                    {{ __('portal.contact_category') }} <span class="text-[#C86D51]">*</span>
                                </label>
                                <select name="category" required class="w-full px-3.5 py-2.5 bg-white border border-[#EAE5D9] rounded-xl text-xs text-[#2D2A26] focus:ring-2 focus:ring-[#5A6B47] focus:border-[#5A6B47]">
                                    <option value="ทั่วไป">{{ __('portal.contact_cat_general') }}</option>
                                    <option value="ปริญญาตรี">{{ __('portal.contact_cat_ug') }}</option>
                                    <option value="บัณฑิตศึกษา">{{ __('portal.contact_cat_grad') }}</option>
                                    <option value="ประชาชนทั่วไป">{{ __('portal.contact_cat_public') }}</option>
                                    <option value="วุฒิบัตร">{{ __('portal.contact_cat_cert') }}</option>
                                    <option value="ปัญหาการใช้งาน">{{ __('portal.contact_cat_system') }}</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">
                                {{ __('portal.contact_subject') }} <span class="text-[#C86D51]">*</span>
                            </label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="{{ __('portal.contact_subject') }}" class="w-full px-3.5 py-2.5 bg-white border border-[#EAE5D9] rounded-xl text-xs text-[#2D2A26] focus:ring-2 focus:ring-[#5A6B47] focus:border-[#5A6B47]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">
                                {{ __('portal.contact_message') }} <span class="text-[#C86D51]">*</span>
                            </label>
                            <textarea name="message" rows="5" required placeholder="{{ __('portal.contact_message') }}..." class="w-full px-3.5 py-2.5 bg-white border border-[#EAE5D9] rounded-xl text-xs text-[#2D2A26] focus:ring-2 focus:ring-[#5A6B47] focus:border-[#5A6B47] leading-relaxed">{{ old('message') }}</textarea>
                        </div>

                        <div class="pt-3 border-t border-[#EAE5D9] flex items-center justify-between">
                            <span class="text-[11px] text-[#7B8D65] flex items-center gap-1">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                {{ __('portal.contact_direct_notice') }}
                            </span>
                            <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white font-medium px-6 py-2.5 rounded-xl text-xs shadow-md transition flex items-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                <span>{{ __('portal.contact_btn_send') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Google Maps Embed Section -->
        @if (!empty($contactSettings['contact_map_embed']))
            <div class="organic-card rounded-3xl p-6 md:p-8 border border-[#EAE5D9] mb-12">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-[#EAE5D9]">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="map" class="w-5 h-5 text-[#5A6B47]"></i>
                        <h3 class="font-heading font-bold text-lg text-[#2C3E2D]">{{ __('portal.contact_map_title') }}</h3>
                    </div>
                    <a href="https://maps.google.com/?q=มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย+วังน้อย" target="_blank" class="text-xs text-[#5A6B47] hover:text-[#2C3E2D] font-semibold flex items-center gap-1 transition">
                        <span>{{ __('portal.contact_open_gmaps') }}</span>
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
                <div class="w-full h-80 sm:h-96 rounded-2xl overflow-hidden border border-[#EAE5D9] shadow-inner">
                    <iframe src="{{ $contactSettings['contact_map_embed'] }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        @endif

    </main>

    <!-- Footer (Organic Earth Forest) -->
    <footer id="contact" class="bg-[#243325] text-[#D5CEBC] text-xs py-12 border-t border-[#1B271C]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
                <div>
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                        <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-8 h-8 object-contain">
                        <span class="font-heading font-bold text-white text-base">{{ __('portal.footer_brand') }}</span>
                    </div>
                    <p class="text-[#A3B88C]">{{ __('portal.footer_address') }}</p>
                    <p class="text-[#8C8275] mt-1">{{ __('portal.institute_name') }} {{ __('portal.university_name') }} &bull; {{ $contactSettings['contact_phone'] ?? '035-248-000' }}</p>
                </div>
                <div class="flex flex-col items-center md:items-end gap-2.5">
                    @if (Session::has('admin_user'))
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium text-xs border border-white/15 transition shadow-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#A3B88C]"></i>
                            <span>{{ __('portal.nav_admin_panel') }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2C3E2D] hover:bg-[#385039] text-[#EAE5D9] hover:text-white font-medium text-xs border border-[#3E5540] transition shadow-sm group">
                            <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-[#A3B88C] group-hover:text-white transition"></i>
                            <span>{{ __('portal.nav_admin_login') }}</span>
                        </a>
                    @endif
                    <div class="text-[10px] text-[#7A7367] font-mono">
                        {{ __('portal.university_name') }} &bull; VPSMCU
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobile-menu-drawer');
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const closeIcon = document.getElementById('close-icon');
            
            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                hamburgerIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                drawer.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
            lucide.createIcons();
        }

        // Mobile Calendar Sub-menu Accordion Toggle
        function toggleMobileCalendar() {
            const sub = document.getElementById('mobile-calendar-sub');
            const chevron = document.getElementById('mobile-calendar-chevron');
            if (sub.classList.contains('hidden')) {
                sub.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                sub.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }

        lucide.createIcons();
    </script>
</body>
</html>
