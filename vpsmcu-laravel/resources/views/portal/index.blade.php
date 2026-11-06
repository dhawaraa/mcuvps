<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VPSMCU - {{ __('portal.system_title') }} {{ __('portal.mcu_short') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>
    <script src="/assets/js/font-resizer.js"></script>
    
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
                            sand: '#F7F4EA',       // สีทราย/ครีมธรรมชาติ
                            stone: '#EAE5D9',      // สีก้อนหินอ่อน
                            clay: '#C86D51',       // สีน้ำตาลอิฐ/ดินเผา
                            clayDark: '#A85238',   // สีน้ำตาลอิฐเข้ม
                            forest: '#2C3E2D',     // สีเขียวป่าลึก
                            olive: '#5A6B47',      // สีเขียวมะกอก
                            oliveLight: '#7B8D65', // สีเขียวมะกอกอ่อน
                            bark: '#4A3B32',       // สีเปลือกไม้/ผืนดิน
                            cream: '#FAF8F2',      // สีครีมละมุน
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Sarabun', sans-serif; 
            background-color: #F7F5EE;
            color: #2D2A26;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }
        
        .organic-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(220, 215, 201, 0.85);
            box-shadow: 0 10px 25px -5px rgba(74, 59, 50, 0.05);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .organic-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 32px -8px rgba(74, 59, 50, 0.12);
        }

        .hero-banner {
            background-image: url('{{ asset("images/heroimage.png") }}');
            background-size: cover;
            background-position: center 38%;
            background-repeat: no-repeat;
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
                        <a href="#calendar" onclick="switchCategory('ALL')" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 focus:outline-none whitespace-nowrap py-1">
                            <i data-lucide="calendar" class="w-4 h-4 text-[#4A3B32]"></i>
                            <span>{{ __('portal.nav_calendar') }}</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-[#8C8275] group-hover:rotate-180 transition-transform duration-200"></i>
                        </a>
                        <!-- Dropdown Panel -->
                        <div class="absolute left-0 top-full pt-2 w-64 hidden group-hover:block z-50 transition-all">
                            <div class="bg-white/95 backdrop-blur-md border border-[#D5CEBC] rounded-2xl shadow-xl p-2 space-y-1">
                                <a href="#calendar" onclick="switchCategory('ALL')" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-[#2C3E2D] hover:bg-[#FAF8F2] transition">
                                    <span class="w-8 h-8 rounded-lg bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47] shrink-0">
                                        <i data-lucide="calendar-range" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <div class="font-semibold text-sm">{{ __('portal.nav_all_schedules') }}</div>
                                        <div class="text-xs text-[#7B8D65]">{{ __('portal.nav_all_schedules_desc') }}</div>
                                    </div>
                                </a>
                                <a href="#calendar" onclick="switchCategory('UG')" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-[#2C3E2D] hover:bg-[#FAF8F2] transition">
                                    <span class="w-8 h-8 rounded-lg bg-[#5A6B47]/15 flex items-center justify-center text-[#5A6B47] shrink-0">
                                        <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <div class="font-semibold text-sm text-[#5A6B47]">{{ __('portal.nav_ug_schedules') }}</div>
                                        <div class="text-xs text-[#7B8D65]">{{ __('portal.nav_ug_schedules_desc') }}</div>
                                    </div>
                                </a>
                                <a href="#calendar" onclick="switchCategory('PUBLIC')" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-[#2C3E2D] hover:bg-[#FAF8F2] transition">
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

                    <!-- ฐานข้อมูลนิสิต (เข้าสู่ระบบพอร์ทัลนิสิต) -->
                    <a href="{{ route('student.login') }}" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="users" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>{{ __('portal.nav_database') }}</span>
                    </a>

                    <!-- ประกาศสำคัญ -->
                    <a href="#announcements" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="bell" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>ประกาศสำคัญ</span>
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
        <div id="mobile-menu-drawer" class="hidden xl:hidden bg-[#FAF8F2] border-b border-[#E3DEC9] shadow-lg transition-all animate-fadeIn">
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
                        <a href="#calendar" onclick="switchCategory('ALL'); toggleMobileMenu();" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-[#2C3E2D] hover:bg-white transition">
                            <span class="w-6 h-6 rounded-md bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47] shrink-0">
                                <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i>
                            </span>
                            <div>
                                <div class="font-semibold">{{ __('portal.nav_all_schedules') }}</div>
                                <div class="text-[10px] text-[#7B8D65]">{{ __('portal.nav_all_schedules_desc') }}</div>
                            </div>
                        </a>
                        <a href="#calendar" onclick="switchCategory('UG'); toggleMobileMenu();" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-[#2C3E2D] hover:bg-white transition">
                            <span class="w-6 h-6 rounded-md bg-[#5A6B47]/15 flex items-center justify-center text-[#5A6B47] shrink-0">
                                <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i>
                            </span>
                            <div>
                                <div class="font-semibold text-[#5A6B47]">{{ __('portal.nav_ug_schedules') }}</div>
                                <div class="text-[10px] text-[#7B8D65]">{{ __('portal.nav_ug_schedules_desc') }}</div>
                            </div>
                        </a>
                        <a href="#calendar" onclick="switchCategory('PUBLIC'); toggleMobileMenu();" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-[#2C3E2D] hover:bg-white transition">
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
                    <a href="{{ route('contact') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#4A3B32] hover:bg-white hover:text-[#5A6B47] transition">
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

    <!-- Hero Banner with Background Image (Shifted upwards) -->
    <section class="relative hero-banner w-full min-h-[160px] sm:min-h-[500px] lg:min-h-[580px] flex items-start justify-end overflow-hidden pt-4 sm:pt-12 md:pt-16 pb-3 sm:pb-32">
        <!-- Subtle gradient overlay for readability of right-aligned text -->
        <div class="absolute inset-0 bg-gradient-to-l from-white/90 via-white/40 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
            <div class="flex justify-end">
                <div class="max-w-6xl text-right">
                    <!-- Bold Hero Title: Dynamic localization (TH/EN), locked lines, never wraps -->
                    <h1 class="text-2xl sm:text-5xl md:text-7xl lg:text-[96px] xl:text-[112px] font-heading font-extrabold text-[#663300] tracking-tight leading-[1.05] drop-shadow-xs whitespace-nowrap">
                        {{ __('portal.hero_title_1') }}
                    </h1>
                    <h2 class="text-xl sm:text-4xl md:text-5xl lg:text-[60px] xl:text-[70px] font-heading font-extrabold text-[#CC6600] tracking-tight leading-[1.1] mt-1 sm:mt-2 drop-shadow-xs whitespace-nowrap">
                        {{ __('portal.hero_title_2') }}
                    </h2>
                    <p class="text-[11px] sm:text-base md:text-lg lg:text-xl xl:text-2xl font-heading font-semibold text-[#4A3B32] mt-1 sm:mt-4 tracking-wide whitespace-nowrap">
                        {{ __('portal.hero_subtitle') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4 Overlapping Action Cards Section (2 columns on mobile, 4 columns on desktop) -->
    <section class="relative -mt-2 sm:-mt-20 z-20 px-3 sm:px-6 lg:px-8 mb-10 sm:mb-14">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                
                <!-- Card 1: สมัครปฏิบัติธรรม (Button: #397657, Background: #E9FAE9) -->
                <div class="bg-[#E9FAE9] rounded-2xl sm:rounded-3xl p-4 sm:p-7 border border-[#D0EED0] shadow-md flex flex-col items-center text-center justify-between hover:shadow-lg transition">
                    <div class="flex flex-col items-center w-full">
                        <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-[#397657] text-white flex items-center justify-center mb-2.5 sm:mb-4 shadow-sm">
                            <i data-lucide="user-plus" class="w-5 h-5 sm:w-7 sm:h-7 text-white"></i>
                        </div>
                        <h3 class="font-heading font-bold text-sm sm:text-xl text-[#244E38] mb-1 sm:mb-1.5">
                            {{ __('portal.card_register_title') }}
                        </h3>
                        <p class="text-[11px] sm:text-xs text-[#52735F] leading-tight sm:leading-relaxed mb-3 sm:mb-6 line-clamp-2 sm:line-clamp-none">
                            {{ __('portal.card_register_desc') }}
                        </p>
                    </div>
                    <a href="{{ route('public.register') }}" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-6 py-2 sm:py-2.5 rounded-full text-[11px] sm:text-xs font-semibold bg-[#397657] hover:bg-[#2C5E45] text-white transition shadow-sm w-full sm:w-36">
                        <span>{{ __('portal.card_register_btn') }}</span>
                        <i data-lucide="arrow-right" class="w-3 h-3 sm:w-3.5 sm:h-3.5"></i>
                    </a>
                </div>

                <!-- Card 2: ฐานข้อมูลนิสิต (Button: #357EBC, Background: #E5F7FD) -->
                <div class="bg-[#E5F7FD] rounded-2xl sm:rounded-3xl p-4 sm:p-7 border border-[#C6EDFA] shadow-md flex flex-col items-center text-center justify-between hover:shadow-lg transition">
                    <div class="flex flex-col items-center w-full">
                        <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-[#357EBC] text-white flex items-center justify-center mb-2.5 sm:mb-4 shadow-sm">
                            <i data-lucide="users" class="w-5 h-5 sm:w-7 sm:h-7 text-white"></i>
                        </div>
                        <h3 class="font-heading font-bold text-sm sm:text-xl text-[#1E5079] mb-1 sm:mb-1.5">
                            {{ __('portal.card_database_title') }}
                        </h3>
                        <p class="text-[11px] sm:text-xs text-[#4E7699] leading-tight sm:leading-relaxed mb-3 sm:mb-6 line-clamp-2 sm:line-clamp-none">
                            {{ __('portal.card_database_desc') }}
                        </p>
                    </div>
                    <a href="{{ route('student.login') }}" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-5 py-2 sm:py-2.5 rounded-full text-[11px] sm:text-xs font-semibold bg-[#357EBC] hover:bg-[#286395] text-white transition shadow-sm w-full sm:w-40">
                        <span>{{ __('portal.card_database_btn') }}</span>
                        <i data-lucide="arrow-right" class="w-3 h-3 sm:w-3.5 sm:h-3.5"></i>
                    </a>
                </div>

                <!-- Card 3: ขอหนังสือรับรอง (Button: #A3772C, Background: #FFFEF0) -->
                <div class="bg-[#FFFEF0] rounded-2xl sm:rounded-3xl p-4 sm:p-7 border border-[#F2EFCB] shadow-md flex flex-col items-center text-center justify-between hover:shadow-lg transition">
                    <div class="flex flex-col items-center w-full">
                        <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-[#A3772C] text-white flex items-center justify-center mb-2.5 sm:mb-4 shadow-sm">
                            <i data-lucide="file-text" class="w-5 h-5 sm:w-7 sm:h-7 text-white"></i>
                        </div>
                        <h3 class="font-heading font-bold text-sm sm:text-xl text-[#6E4F1A] mb-1 sm:mb-1.5">
                            {{ __('portal.card_request_cert_title') }}
                        </h3>
                        <p class="text-[11px] sm:text-xs text-[#8A7145] leading-tight sm:leading-relaxed mb-3 sm:mb-6 line-clamp-2 sm:line-clamp-none">
                            {{ __('portal.card_request_cert_desc') }}
                        </p>
                    </div>
                    <a href="{{ route('grad.request') }}" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-6 py-2 sm:py-2.5 rounded-full text-[11px] sm:text-xs font-semibold bg-[#A3772C] hover:bg-[#855F20] text-white transition shadow-sm w-full sm:w-36">
                        <span>{{ __('portal.card_request_cert_btn') }}</span>
                        <i data-lucide="arrow-right" class="w-3 h-3 sm:w-3.5 sm:h-3.5"></i>
                    </a>
                </div>

                <!-- Card 4: ร่วมบริจาค (Button: #A14530, Background: #F5EFE5) -->
                <div class="bg-[#F5EFE5] rounded-2xl sm:rounded-3xl p-4 sm:p-7 border border-[#E7DCCE] shadow-md flex flex-col items-center text-center justify-between hover:shadow-lg transition">
                    <div class="flex flex-col items-center w-full">
                        <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-[#A14530] text-white flex items-center justify-center mb-2.5 sm:mb-4 shadow-sm">
                            <i data-lucide="heart-handshake" class="w-5 h-5 sm:w-7 sm:h-7 text-white"></i>
                        </div>
                        <h3 class="font-heading font-bold text-sm sm:text-xl text-[#6B2C1F] mb-1 sm:mb-1.5">
                            {{ __('portal.card_donation_title') }}
                        </h3>
                        <p class="text-[11px] sm:text-xs text-[#805D54] leading-tight sm:leading-relaxed mb-3 sm:mb-6 line-clamp-2 sm:line-clamp-none">
                            {{ __('portal.card_donation_desc') }}
                        </p>
                    </div>
                    <a href="{{ url('/donation.php') }}" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-5 py-2 sm:py-2.5 rounded-full text-[11px] sm:text-xs font-semibold bg-[#A14530] hover:bg-[#833523] text-white transition shadow-sm w-full sm:w-40">
                        <span>{{ __('portal.card_donation_btn') }}</span>
                        <i data-lucide="arrow-right" class="w-3 h-3 sm:w-3.5 sm:h-3.5"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Meditation Calendar & Schedules Section (Refined to match Home-Portal.png) -->
    <section id="calendar" class="py-8 bg-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Calendar Section Header with Dropdown on Right -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 pb-2 gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-[#2C3E2D]">
                        {{ __('portal.calendar_title') }}
                    </h2>
                    <p class="text-xs text-[#6B6357] mt-1">{{ __('portal.calendar_desc') }}</p>
                </div>
                <div class="w-full md:w-auto">
                    <form method="GET" action="{{ route('home') }}#calendar">
                        <select name="filter_org" onchange="this.form.submit()" class="w-full md:w-72 text-xs bg-white border border-[#D5CEBC] rounded-xl px-4 py-2.5 text-[#4A3B32] focus:outline-none focus:ring-2 focus:ring-[#5A6B47] font-medium shadow-xs">
                            <option value="">{{ __('portal.calendar_filter_all') }}</option>
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}" {{ request('filter_org') == $org->id ? 'selected' : '' }}>
                                    [{{ $org->code_provincial ?: $org->code }}] {{ $currentLang === 'en' && !empty($org->name_en) ? $org->name_en : $org->name_th }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <!-- Category Filter Pills & Legends (Matches Pill Filter in Reference) -->
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <div class="inline-flex p-1 rounded-full bg-white border border-[#D5CEBC] shadow-xs gap-1" id="category-tabs">
                    <button type="button" onclick="switchCategory('ALL')" id="tab-cat-ALL" class="px-4 py-1.5 rounded-full text-xs font-semibold transition flex items-center gap-1.5 bg-[#2C3E2D] text-white shadow-xs">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                        <span>{{ __('portal.calendar_tab_all') }}</span>
                        <span id="badge-count-all" class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20 font-mono">0</span>
                    </button>
                    <button type="button" onclick="switchCategory('UG')" id="tab-cat-UG" class="px-4 py-1.5 rounded-full text-xs font-semibold text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#FAF8F2] transition flex items-center gap-1.5">
                        <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.calendar_tab_ug') }}</span>
                        <span id="badge-count-ug" class="px-1.5 py-0.2 rounded-full text-[10px] bg-[#5A6B47]/15 text-[#5A6B47] font-mono font-bold">0</span>
                    </button>
                    <button type="button" onclick="switchCategory('PUBLIC')" id="tab-cat-PUBLIC" class="px-4 py-1.5 rounded-full text-xs font-semibold text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#FAF8F2] transition flex items-center gap-1.5">
                        <i data-lucide="users" class="w-3.5 h-3.5 text-[#C86D51]"></i>
                        <span>{{ __('portal.calendar_tab_public') }}</span>
                        <span id="badge-count-public" class="px-1.5 py-0.2 rounded-full text-[10px] bg-[#C86D51]/15 text-[#C86D51] font-mono font-bold">0</span>
                    </button>
                </div>

                <div class="text-xs text-[#7B8D65] flex items-center gap-3">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#5A6B47]"></span>
                        <span>{{ __('portal.calendar_legend_ug') }}</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#C86D51]"></span>
                        <span>{{ __('portal.calendar_legend_public') }}</span>
                    </span>
                </div>
            </div>

            <!-- Side-by-Side: Left Calendar Grid & Right Open Projects List -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                
                <!-- Left Column: Interactive Monthly Calendar (7 cols) -->
                <div class="lg:col-span-7 flex flex-col">
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-[#D5CEBC] shadow-xs flex flex-col justify-between h-full">
                        <!-- Calendar Header & Month Navigation -->
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#EAE5D9]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-[#5A6B47]/10 text-[#5A6B47] flex items-center justify-center font-bold">
                                    <i data-lucide="calendar-days" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 id="calendar-month-year" class="font-heading font-bold text-lg text-[#2C3E2D]">
                                        <!-- Dynamic: e.g. พฤศจิกายน 2569 -->
                                    </h3>
                                    <p class="text-[11px] text-[#7B8D65]">{{ __('portal.calendar_hint_click') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button onclick="changeMonth(-1)" class="p-2 rounded-xl border border-[#D5CEBC] hover:bg-[#FAF8F2] text-[#4A3B32] hover:text-[#2C3E2D] transition shadow-2xs" title="{{ __('portal.calendar_prev_month') }}">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </button>
                                <button onclick="goToCurrentMonth()" class="px-3 py-1.5 rounded-xl border border-[#D5CEBC] text-xs font-semibold text-[#5A6B47] hover:bg-[#5A6B47] hover:text-white transition shadow-2xs">
                                    {{ __('portal.calendar_this_month') }}
                                </button>
                                <button onclick="changeMonth(1)" class="p-2 rounded-xl border border-[#D5CEBC] hover:bg-[#FAF8F2] text-[#4A3B32] hover:text-[#2C3E2D] transition shadow-2xs" title="{{ __('portal.calendar_next_month') }}">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Days of Week Header -->
                        <div class="grid grid-cols-7 gap-1 text-center font-heading text-xs font-semibold mb-2">
                            <span class="text-[#C86D51] py-1.5">{{ $currentLang === 'en' ? 'Sun' : 'อา.' }}</span>
                            <span class="text-[#4A3B32] py-1.5">{{ $currentLang === 'en' ? 'Mon' : 'จ.' }}</span>
                            <span class="text-[#4A3B32] py-1.5">{{ $currentLang === 'en' ? 'Tue' : 'อ.' }}</span>
                            <span class="text-[#4A3B32] py-1.5">{{ $currentLang === 'en' ? 'Wed' : 'พ.' }}</span>
                            <span class="text-[#4A3B32] py-1.5">{{ $currentLang === 'en' ? 'Thu' : 'พฤ.' }}</span>
                            <span class="text-[#4A3B32] py-1.5">{{ $currentLang === 'en' ? 'Fri' : 'ศ.' }}</span>
                            <span class="text-[#5A6B47] py-1.5">{{ $currentLang === 'en' ? 'Sat' : 'ส.' }}</span>
                        </div>

                        <!-- Calendar Grid Cells -->
                        <div id="calendar-days-grid" class="grid grid-cols-7 gap-1.5 flex-grow">
                            <!-- Populated via JavaScript -->
                        </div>

                        <!-- Legend & Status -->
                        <div class="pt-4 mt-4 border-t border-[#EAE5D9] flex flex-wrap items-center justify-between gap-3 text-[11px] text-[#6B6357]">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#5A6B47]"></span>
                                    <span>{{ __('portal.calendar_legend_ug') }}</span>
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#C86D51]"></span>
                                    <span>{{ __('portal.calendar_legend_public') }}</span>
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full border-2 border-[#2C3E2D]"></span>
                                    <span>{{ __('portal.calendar_legend_today') }}</span>
                                </span>
                            </div>
                            <span id="calendar-month-event-count" class="font-mono text-[#5A6B47] font-semibold">
                                <!-- e.g. 2 โครงการในเดือนนี้ -->
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: โครงการที่เปิดรับสมัคร (Matches Home-Portal.png right side) -->
                <div class="lg:col-span-5 flex flex-col">
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-[#D5CEBC] shadow-xs flex flex-col h-full max-h-[580px]">
                        <!-- List Header with "ดูทั้งหมด ->" exactly matching reference -->
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#EAE5D9]">
                            <div>
                                <h3 id="events-list-title" class="font-heading font-extrabold text-lg text-[#2C3E2D]">
                                    โครงการที่เปิดรับสมัคร
                                </h3>
                                <p id="events-list-subtitle" class="text-[11px] text-[#7B8D65]">
                                    {{ __('portal.calendar_side_desc') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button id="reset-filter-btn" onclick="filterBySelectedDay(null)" class="hidden text-[11px] text-[#C86D51] hover:underline font-medium flex items-center gap-1">
                                    <i data-lucide="rotate-ccw" class="w-3 h-3"></i> {{ __('portal.calendar_view_all_month') }}
                                </button>
                                <a href="{{ route('ug.register') }}" class="text-xs font-semibold text-[#A85238] hover:text-[#C86D51] flex items-center gap-1 transition">
                                    <span>ดูทั้งหมด</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Scrollable Cards Container -->
                        <div id="calendar-events-container" class="space-y-3.5 overflow-y-auto pr-1 flex-grow">
                            <!-- Populated via JavaScript -->
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Section: ประกาศและคำสั่งสำคัญ (Official Announcements - Document List) -->
    <section class="py-8 bg-transparent" id="announcements">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#D5CEBC] shadow-xs">
                
                <!-- Section Header with Document Icon and "ดูประกาศทั้งหมด ->" -->
                <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-6 border-b border-[#EAE5D9] gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center shrink-0">
                            <i data-lucide="file-text" class="w-6 h-6 text-[#5A6B47]"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-heading font-extrabold text-[#2C3E2D]">ประกาศและคำสั่งสำคัญ</h2>
                            <p class="text-xs text-[#7B8D65]">ประกาศรายชื่อผู้มีสิทธิ์เข้าร่วมปฏิบัติธรรม กำหนดการ และระเบียบปฏิบัติ มจร</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 self-start sm:self-auto">
                        <!-- Quick Search form to search in announcements & PDF text! -->
                        <form action="{{ route('portal.announcements') }}" method="GET" class="relative hidden md:block">
                            <input type="text" name="search" placeholder="ค้นหารายชื่อในประกาศ..." class="pl-8 pr-3 py-1.5 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47] focus:outline-none w-52 font-medium">
                            <i data-lucide="search" class="w-3.5 h-3.5 absolute left-2.5 top-2.5 text-[#8C8275]"></i>
                        </form>
                        <a href="{{ route('portal.announcements') }}" class="text-xs font-semibold text-[#5A6B47] hover:text-[#2C3E2D] flex items-center gap-1 transition whitespace-nowrap">
                            <span>ดูประกาศทั้งหมด</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Document List Layout (ไม่มีภาพปก เน้นเอกสารและการค้นหา) -->
                <div class="divide-y divide-[#EAE5D9]">
                    @forelse ($recentAnnouncements as $anc)
                        <div class="py-4 first:pt-0 last:pb-0 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-[#FAF8F2]/60 -mx-3 px-3 rounded-2xl transition group">
                            
                            <!-- Left Item Details -->
                            <div class="flex items-start gap-3.5 min-w-0 flex-grow">
                                <div class="w-10 h-10 rounded-xl {{ $anc->is_pinned ? 'bg-[#C86D51]/15 text-[#C86D51]' : 'bg-[#5A6B47]/15 text-[#5A6B47]' }} flex items-center justify-center shrink-0 mt-0.5">
                                    @if ($anc->is_pinned)
                                        <i data-lucide="pin" class="w-5 h-5 fill-current"></i>
                                    @else
                                        <i data-lucide="file-text" class="w-5 h-5"></i>
                                    @endif
                                </div>

                                <div class="min-w-0 space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold border {{ $anc->category_badge_class }}">
                                            {{ $anc->category_label }}
                                        </span>
                                        @if ($anc->announcement_no)
                                            <span class="text-[11px] font-mono text-[#6B6357] bg-[#EAE5D9]/70 px-2 py-0.5 rounded border border-[#D5CEBC]">
                                                {{ $anc->announcement_no }}
                                            </span>
                                        @endif
                                        <span class="text-[11px] text-[#8C8275] flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            {{ $anc->published_at ? \Carbon\Carbon::parse($anc->published_at)->format('d/m/Y') : \Carbon\Carbon::parse($anc->created_at)->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    <h3 class="text-sm sm:text-base font-heading font-bold text-[#2D2A26] transition">
                                        @if ($anc->attachment_path)
                                            <button type="button" onclick="openHomePdfPreview('{{ $anc->attachment_url }}', '{{ addslashes($anc->localized_title) }}', '{{ addslashes($anc->attachment_name ?: 'เอกสาร') }}')" class="text-left font-heading font-bold text-[#2D2A26] hover:text-[#5A6B47] transition cursor-pointer">
                                                <span class="hover:underline">{{ $anc->localized_title }}</span>
                                            </button>
                                        @else
                                            <a href="{{ route('portal.announcement.detail', ['id' => $anc->id]) }}" class="hover:text-[#5A6B47] hover:underline transition">
                                                {{ $anc->localized_title }}
                                            </a>
                                        @endif
                                    </h3>

                                    <div class="flex flex-wrap items-center gap-3 text-xs text-[#7B8D65]">
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="building-2" class="w-3 h-3 shrink-0"></i>
                                            {{ $anc->organizationUnit ? $anc->organizationUnit->name : 'สถาบันวิปัสสนาธุระ (ส่วนกลาง)' }}
                                        </span>
                                        <span class="flex items-center gap-1 text-[#8C8275]">
                                            <i data-lucide="eye" class="w-3 h-3 shrink-0"></i>
                                            {{ number_format($anc->views) }} เข้าชม
                                        </span>
                                        <a href="{{ route('portal.announcement.detail', ['id' => $anc->id]) }}" class="text-[#8C8275] hover:text-[#5A6B47] flex items-center gap-0.5 hover:underline" title="เปิดหน้าเฉพาะประกาศนี้">
                                            <i data-lucide="external-link" class="w-3 h-3"></i>
                                            <span>หน้ารายละเอียด</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Actions: Preview & Download -->
                            <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                                @if ($anc->attachment_path)
                                    <button type="button" onclick="openHomePdfPreview('{{ $anc->attachment_url }}', '{{ addslashes($anc->localized_title) }}', '{{ addslashes($anc->attachment_name ?: 'เอกสาร') }}')" class="px-3 py-1.5 rounded-xl border border-[#D5CEBC] bg-white hover:bg-[#FAF8F2] text-xs font-semibold text-[#5A6B47] transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>เปิดดูเอกสาร</span>
                                    </button>
                                    <a href="{{ $anc->attachment_url }}" download="{{ $anc->attachment_name ?: 'announcement.pdf' }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-[#5A6B47] hover:bg-[#2C3E2D] text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
                                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                        <span>ดาวน์โหลด</span>
                                        @if ($anc->attachment_size)
                                            <span class="text-[10px] text-white/80 font-mono">({{ $anc->attachment_size }})</span>
                                        @endif
                                    </a>
                                @else
                                    <a href="{{ route('portal.announcement.detail', ['id' => $anc->id]) }}" class="px-3 py-1.5 rounded-xl border border-[#D5CEBC] bg-white hover:bg-[#FAF8F2] text-xs font-semibold text-[#2C3E2D] transition flex items-center gap-1.5">
                                        <span>อ่านประกาศ</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="py-8 text-center text-[#8C8275]">
                            <p class="text-xs">ยังไม่มีประกาศและคำสั่งในขณะนี้</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </section>

    <!-- News & Activities Section (Matches Home-Portal.png bottom section) -->
    <section class="py-12 bg-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#D5CEBC] shadow-xs">
                
                <!-- Section Header with Megaphone Icon and "ดูข่าวทั้งหมด ->" -->
                <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-6 border-b border-[#EAE5D9] gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#C86D51]/10 text-[#C86D51] flex items-center justify-center shrink-0">
                            <i data-lucide="megaphone" class="w-6 h-6 text-[#C86D51]"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-heading font-extrabold text-[#2C3E2D]">{{ __('portal.news_title') }}</h2>
                            <p class="text-xs text-[#7B8D65]">{{ __('portal.news_sub') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('news.index') }}" class="text-xs font-semibold text-[#A85238] hover:text-[#C86D51] flex items-center gap-1 transition self-start sm:self-auto">
                        <span>{{ __('portal.news_view_all') }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- 3 Cards Layout Matching Reference Image -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse ($recentNews->take(3) as $news)
                        <div class="bg-[#FAF8F2]/60 rounded-2xl overflow-hidden border border-[#EAE5D9] hover:border-[#5A6B47] transition shadow-2xs flex flex-col justify-between group">
                            <div class="relative h-44 w-full overflow-hidden bg-[#FAF8F2]">
                                @if ($news->cover_image)
                                    <img src="{{ $news->cover_image }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[#D5CEBC] bg-[#FAF8F2]">
                                        <i data-lucide="image" class="w-12 h-12 stroke-1"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 flex-grow flex flex-col justify-between bg-white">
                                <div>
                                    <!-- Badges & Date -->
                                    <div class="flex items-center justify-between text-xs mb-2.5">
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-[#FAF4EC] text-[#A85238] border border-[#EFE2D1]">
                                            {{ $news->category_label ?? 'ประกาศ' }}
                                        </span>
                                        <span class="text-[11px] text-[#8C8275] font-mono">
                                            {{ substr($news->published_at ?? $news->created_at, 0, 10) }}
                                        </span>
                                    </div>
                                    <!-- Title -->
                                    <h3 class="font-heading font-bold text-[#2C3E2D] text-sm mb-2 group-hover:text-[#C86D51] transition line-clamp-2 leading-snug">
                                        <a href="{{ route('news.detail', $news->id) }}">
                                            {{ $news->localized_title }}
                                        </a>
                                    </h3>
                                    <!-- Short Content -->
                                    <p class="text-xs text-[#6B6357] line-clamp-2 leading-relaxed mb-4">
                                        {{ strip_tags($news->localized_content) }}
                                    </p>
                                </div>
                                <!-- Read More Link -->
                                <div class="pt-3 border-t border-[#F2EFE7] flex items-center justify-end text-xs">
                                    <a href="{{ route('news.detail', $news->id) }}" class="text-[#A85238] font-semibold flex items-center gap-1 group-hover:text-[#C86D51] transition">
                                        <span>{{ __('portal.news_read_more') }}</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-[#8C8275] bg-white rounded-2xl border border-dashed border-[#D5CEBC]">
                            <p class="text-xs">{{ __('portal.news_empty') }}</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </section>

    <!-- Footer (Deep Forest Palette - Exactly as in Reference) -->
    <footer id="contact" class="bg-[#1F2B20] text-[#D5CEBC] text-xs py-10 border-t border-[#162017] mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
                <div>
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                        <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-8 h-8 object-contain">
                        <span class="font-heading font-bold text-white text-base">{{ __('portal.footer_brand') }}</span>
                    </div>
                    <p class="text-[#A3B88C]">{{ __('portal.footer_address') }}</p>
                    <p class="text-[#8C8275] mt-1">
                        สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย &bull; 
                        <a href="{{ route('contact') }}" class="text-[#A3B88C] hover:text-white underline">ดูช่องทางติดต่อสอบถาม & แผนที่</a>
                    </p>
                </div>
                <div class="flex flex-col items-center md:items-end gap-2.5">
                    @if (Session::has('admin_user'))
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium text-xs border border-white/15 transition shadow-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#A3B88C]"></i>
                            <span>{{ __('portal.nav_admin_panel') }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2C3E2D] hover:bg-[#385039] text-[#EAE5D9] hover:text-white font-medium text-xs border border-[#3E5540] transition shadow-sm group">
                            <i data-lucide="shield" class="w-3.5 h-3.5 text-[#A3B88C] group-hover:text-white transition"></i>
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
        lucide.createIcons();

        const currentLocale = '{{ app()->getLocale() }}';

        // Raw Data from Controller
        const rawBatches = @json($openBatches);
        const rawPublicEvents = @json($openPublicEvents);

        // Standardize both UG Batches and Public Events into unified event objects
        const ugEvents = rawBatches.map(b => ({
            id: 'ug-' + b.id,
            raw_id: b.id,
            type: 'UG',
            type_label: currentLocale === 'en' ? 'Undergraduate (10 Days)' : 'เปิดรับสมัคร',
            title: (currentLocale === 'en' && b.title_en) ? b.title_en : b.title,
            start_date: b.start_date,
            end_date: b.end_date || b.start_date,
            location: (currentLocale === 'en' && b.location_en) ? b.location_en : (b.location || 'อาคาร 72 ปี พระวิสุทธาธิบดี มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย จ.พระนครศรีอยุธยา'),
            max_quota: b.max_quota,
            reg_count: b.registrations ? b.registrations.length : 0,
            academic_year: b.academic_year,
            org_name: b.organization_unit ? ((currentLocale === 'en' && b.organization_unit.name_en) ? b.organization_unit.name_en : b.organization_unit.name_th) : 'มจร',
            org_code: b.organization_unit ? (b.organization_unit.code_provincial || b.organization_unit.code) : 'MCU',
            image: b.cover_image ? b.cover_image : '/images/news/meditation_hall.jpg',
            register_url: "{{ route('ug.register') }}"
        }));

        const publicEvents = rawPublicEvents.map((p, index) => {
            const fallbackImages = [
                '/images/news/lanna_retreat.jpg',
                '/images/news/isan_community.jpg',
                '/images/news/scripture_study.jpg'
            ];
            return {
                id: 'pub-' + p.id,
                raw_id: p.id,
                type: 'PUBLIC',
                type_label: currentLocale === 'en' ? 'General Public' : 'เปิดรับสมัคร',
                title: (currentLocale === 'en' && p.title_en) ? p.title_en : p.title,
                start_date: p.start_date,
                end_date: p.end_date || p.start_date,
                location: (currentLocale === 'en' && p.location_name_en) ? p.location_name_en : (p.location_name || 'อาคาร 72 ปี พระวิสุทธาธิบดี มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย จ.พระนครศรีอยุธยา'),
                max_quota: p.max_quota,
                reg_count: (p.active_registrations_count !== undefined) ? p.active_registrations_count : (p.registrations ? p.registrations.length : (p.confirmed_count || 0)),
                academic_year: null,
                org_name: p.organization_unit ? ((currentLocale === 'en' && p.organization_unit.name_en) ? p.organization_unit.name_en : p.organization_unit.name_th) : 'มจร',
                org_code: p.organization_unit ? (p.organization_unit.code_provincial || p.organization_unit.code) : 'MCU',
                image: p.cover_image ? p.cover_image : fallbackImages[index % fallbackImages.length],
                register_url: "{{ route('public.register') }}?event_id=" + p.id
            };
        });

        const allEvents = [...ugEvents, ...publicEvents];

        // Update Tab Badges Count
        document.getElementById('badge-count-all').textContent = allEvents.length;
        document.getElementById('badge-count-ug').textContent = ugEvents.length;
        document.getElementById('badge-count-public').textContent = publicEvents.length;

        let activeCategory = 'ALL'; // 'ALL', 'UG', 'PUBLIC'

        const thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];
        const thaiMonthsShort = [
            'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
            'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
        ];
        const engMonths = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        const engMonthsShort = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        // Default month: set to current month (เดือนปัจจุบัน)
        let currentDate = new Date();
        let selectedDay = null; // YYYY-MM-DD string or null

        function getFilteredEvents() {
            if (activeCategory === 'UG') return ugEvents;
            if (activeCategory === 'PUBLIC') return publicEvents;
            return allEvents;
        }

        function switchCategory(cat) {
            activeCategory = cat;
            selectedDay = null;

            // Update Tab UI
            const tabs = {
                'ALL': document.getElementById('tab-cat-ALL'),
                'UG': document.getElementById('tab-cat-UG'),
                'PUBLIC': document.getElementById('tab-cat-PUBLIC')
            };

            for (const [key, btn] of Object.entries(tabs)) {
                if (!btn) continue;
                if (key === cat) {
                    btn.className = "px-4 py-1.5 rounded-full text-xs font-semibold transition flex items-center gap-1.5 bg-[#2C3E2D] text-white shadow-xs";
                } else {
                    btn.className = "px-4 py-1.5 rounded-full text-xs font-semibold text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#FAF8F2] transition flex items-center gap-1.5";
                }
            }

            renderCalendar();
        }

        function initCalendar() {
            renderCalendar();
        }

        function changeMonth(offset) {
            currentDate.setMonth(currentDate.getMonth() + offset);
            selectedDay = null;
            renderCalendar();
        }

        function goToCurrentMonth() {
            currentDate = new Date();
            selectedDay = null;
            renderCalendar();
        }

        function filterBySelectedDay(dateStr) {
            selectedDay = dateStr;
            renderCalendar();
        }

        function formatDateRange(startDateStr, endDateStr) {
            if (!startDateStr) return '';
            const sParts = startDateStr.split('-');
            const eParts = (endDateStr || startDateStr).split('-');
            
            const sDay = parseInt(sParts[2], 10);
            const sMonth = parseInt(sParts[1], 10) - 1;
            const sYear = parseInt(sParts[0], 10);

            const eDay = parseInt(eParts[2], 10);
            const eMonth = parseInt(eParts[1], 10) - 1;
            const eYear = parseInt(eParts[0], 10);

            if (currentLocale === 'en') {
                const sY = sYear;
                if (sMonth === eMonth && sYear === eYear) {
                    return `${sDay} - ${eDay} ${engMonthsShort[sMonth]} ${sY}`;
                }
                return `${sDay} ${engMonthsShort[sMonth]} - ${eDay} ${engMonthsShort[eMonth]} ${sY}`;
            } else {
                const thaiYear = sYear + 543;
                if (sMonth === eMonth && sYear === eYear) {
                    return `${sDay} - ${eDay} ${thaiMonthsShort[sMonth]} ${thaiYear}`;
                }
                return `${sDay} ${thaiMonthsShort[sMonth]} - ${eDay} ${thaiMonthsShort[eMonth]} ${thaiYear}`;
            }
        }

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            if (currentLocale === 'en') {
                document.getElementById('calendar-month-year').textContent = `${engMonths[month]} ${year}`;
            } else {
                const thaiYear = year + 543;
                document.getElementById('calendar-month-year').textContent = `${thaiMonths[month]} ${thaiYear}`;
            }

            const firstDayIndex = new Date(year, month, 1).getDay();
            const totalDays = new Date(year, month + 1, 0).getDate();
            const prevMonthTotalDays = new Date(year, month, 0).getDate();

            const grid = document.getElementById('calendar-days-grid');
            grid.innerHTML = '';

            const today = new Date();
            const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;
            const todayDate = today.getDate();

            // 1. Previous month trailing days
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                const dayNum = prevMonthTotalDays - i;
                const cell = document.createElement('div');
                cell.className = 'h-12 sm:h-14 p-1 rounded-2xl bg-transparent text-[#D5CEBC] flex flex-col items-center justify-start text-xs opacity-40 select-none';
                cell.innerHTML = `<span>${dayNum}</span>`;
                grid.appendChild(cell);
            }

            const activeEvents = getFilteredEvents();
            const monthEvents = [];

            // 2. Current month days
            for (let day = 1; day <= totalDays; day++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                // Find events spanning across this date
                const dayEvents = activeEvents.filter(e => {
                    const start = e.start_date.substring(0, 10);
                    const end = (e.end_date || e.start_date).substring(0, 10);
                    return dateStr >= start && dateStr <= end;
                });

                dayEvents.forEach(e => {
                    if (!monthEvents.some(me => me.id === e.id)) {
                        monthEvents.push(e);
                    }
                });

                const hasEvents = dayEvents.length > 0;
                const isSelected = selectedDay === dateStr;
                const isToday = isCurrentMonth && day === todayDate;

                const hasUg = dayEvents.some(e => e.type === 'UG');
                const hasPublic = dayEvents.some(e => e.type === 'PUBLIC');

                const cell = document.createElement('button');
                cell.type = 'button';
                cell.onclick = () => filterBySelectedDay(isSelected ? null : dateStr);

                // Tooltip
                if (hasEvents) {
                    const tooltipText = dayEvents.map(e => `• ${e.title}`).join('\n');
                    cell.title = `${day} ${thaiMonths[month]}:\n${tooltipText}`;
                }

                let cellClasses = 'min-h-[54px] sm:min-h-[60px] p-1.5 rounded-2xl transition flex flex-col items-center justify-between text-xs relative ';

                if (isSelected) {
                    cellClasses += 'bg-[#2C3E2D] text-white font-bold shadow-md ring-2 ring-[#2C3E2D]/50';
                } else if (hasEvents) {
                    cellClasses += 'bg-[#ECE8DC] hover:bg-[#E3DEC9] text-[#2C3E2D] font-semibold border border-[#D5CEBC]';
                } else if (isToday) {
                    cellClasses += 'bg-[#FAF8F2] text-[#5A6B47] font-bold border-2 border-[#2C3E2D]';
                } else {
                    cellClasses += 'hover:bg-[#FAF8F2] text-[#4A3B32] border border-transparent';
                }

                // Dot markers
                let dotsHtml = '';
                if (hasUg && hasPublic) {
                    dotsHtml = `
                        <div class="flex items-center gap-1 mb-0.5">
                            <span class="w-2 h-2 rounded-full ${isSelected ? 'bg-emerald-300' : 'bg-[#5A6B47]'}"></span>
                            <span class="w-2 h-2 rounded-full ${isSelected ? 'bg-orange-300' : 'bg-[#C86D51]'}"></span>
                        </div>
                    `;
                } else if (hasUg) {
                    dotsHtml = `<span class="w-2 h-2 rounded-full ${isSelected ? 'bg-white' : 'bg-[#5A6B47]'} mb-0.5"></span>`;
                } else if (hasPublic) {
                    dotsHtml = `<span class="w-2 h-2 rounded-full ${isSelected ? 'bg-white' : 'bg-[#C86D51]'} mb-0.5"></span>`;
                }

                cell.className = cellClasses;
                cell.innerHTML = `
                    <div class="flex items-center justify-start w-full px-1">
                        <span class="text-[12px] font-medium leading-none">${day}</span>
                    </div>
                    <div class="flex items-center justify-center w-full mt-auto">
                        ${dotsHtml}
                    </div>
                `;
                grid.appendChild(cell);
            }

            // 3. Next month leading days
            const totalCellsRendered = firstDayIndex + totalDays;
            const remainingCells = (7 - (totalCellsRendered % 7)) % 7;
            for (let day = 1; day <= remainingCells; day++) {
                const cell = document.createElement('div');
                cell.className = 'min-h-[54px] sm:min-h-[60px] p-1.5 rounded-2xl bg-transparent text-[#D5CEBC] flex flex-col items-center justify-start text-xs opacity-40 select-none';
                cell.innerHTML = `<span>${day}</span>`;
                grid.appendChild(cell);
            }

            // Month event counter
            document.getElementById('calendar-month-event-count').textContent = currentLocale === 'en' 
                ? `${monthEvents.length} retreats this month`
                : `${monthEvents.length} โครงการในเดือนนี้`;

            // Render Events in right column
            renderEventsList(monthEvents);
        }

        function renderEventsList(monthEvents) {
            const container = document.getElementById('calendar-events-container');
            const title = document.getElementById('events-list-title');
            const subtitle = document.getElementById('events-list-subtitle');
            const resetBtn = document.getElementById('reset-filter-btn');

            let displayEvents = [];
            const activeEvents = getFilteredEvents();

            if (selectedDay) {
                resetBtn.classList.remove('hidden');
                const [y, m, d] = selectedDay.split('-');
                let dateDisplay = '';
                if (currentLocale === 'en') {
                    dateDisplay = `${engMonths[parseInt(m) - 1]} ${parseInt(d)}, ${parseInt(y)}`;
                    title.textContent = `Retreats on ${dateDisplay}`;
                } else {
                    dateDisplay = `${parseInt(d)} ${thaiMonths[parseInt(m) - 1]} ${parseInt(y) + 543}`;
                    title.textContent = `โครงการวันที่ ${dateDisplay}`;
                }
                
                displayEvents = activeEvents.filter(e => {
                    const start = e.start_date.substring(0, 10);
                    const end = (e.end_date || e.start_date).substring(0, 10);
                    return selectedDay >= start && selectedDay <= end;
                });
                subtitle.textContent = currentLocale === 'en'
                    ? `Found ${displayEvents.length} retreats on this date`
                    : `พบ ${displayEvents.length} โครงการในวันที่เลือก`;
            } else {
                resetBtn.classList.add('hidden');
                title.textContent = 'โครงการที่เปิดรับสมัคร';
                subtitle.textContent = currentLocale === 'en'
                    ? `Total ${monthEvents.length} retreats this month`
                    : `มีทั้งหมด ${monthEvents.length} โครงการ`;
                displayEvents = monthEvents.length > 0 ? monthEvents : activeEvents.slice(0, 3);
            }

            if (displayEvents.length === 0) {
                container.innerHTML = `
                    <div class="py-12 text-center text-[#8C8275]">
                        <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-2 text-[#D5CEBC]"></i>
                        <p class="font-medium text-xs text-[#4A3B32]">${currentLocale === 'en' ? '{{ __('portal.calendar_empty_day') }}' : 'ไม่พบโครงการปฏิบัติธรรมในวันที่เลือก'}</p>
                        <p class="text-[11px] text-[#8C8275] mt-1">${currentLocale === 'en' ? '{{ __('portal.calendar_empty_day_sub') }}' : 'คลิกเลือกวันที่มีจุดสี หรือกดปุ่ม "ดูทั้งเดือน"'}</p>
                    </div>
                `;
                lucide.createIcons();
                return;
            }

            let html = '';
            displayEvents.forEach(e => {
                const dateText = formatDateRange(e.start_date, e.end_date);

                html += `
                    <div class="p-3.5 rounded-2xl bg-white hover:bg-[#FAF8F2] border border-[#EAE5D9] hover:border-[#5A6B47] transition flex gap-3.5 group cursor-pointer" onclick="window.location.href='${e.register_url}'">
                        <!-- Thumbnail Image exactly as in reference Home-Portal.png -->
                        <div class="w-24 h-24 sm:w-28 sm:h-24 rounded-xl overflow-hidden bg-[#EAE5D9] shrink-0 border border-[#D5CEBC]">
                            <img src="${e.image}" alt="${e.title}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>

                        <!-- Content info -->
                        <div class="flex flex-col justify-between flex-grow min-w-0">
                            <div>
                                <!-- Green Pill Badge & Date Range -->
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[#388E3C] text-white leading-none">
                                        ${e.type_label}
                                    </span>
                                    <span class="text-xs font-heading font-bold text-[#A85238]">
                                        ${dateText}
                                    </span>
                                </div>
                                <!-- Retreat Title -->
                                <h4 class="font-heading font-bold text-xs sm:text-sm text-[#2C3E2D] group-hover:text-[#C86D51] transition line-clamp-2 leading-snug">
                                    ${e.title}
                                </h4>
                            </div>

                            <!-- Venue location with Map Pin -->
                            <div class="flex items-start gap-1 text-[11px] text-[#6B6357] mt-1 line-clamp-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#C86D51] shrink-0 mt-0.5"></i>
                                <span class="line-clamp-2">${e.location}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            lucide.createIcons();
        }

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

        // Home Announcements PDF Preview Modal
        function openHomePdfPreview(url, title, filename) {
            document.getElementById('home-pdf-title').textContent = title || 'พรีวิวเอกสาร';
            document.getElementById('home-pdf-filename').textContent = filename || url.split('/').pop();
            document.getElementById('home-pdf-download-btn').href = url;
            document.getElementById('home-pdf-external-btn').href = url;
            document.getElementById('home-pdf-iframe').src = url + '#toolbar=1&navpanes=1';
            document.getElementById('home-pdf-modal').classList.remove('hidden');
            if (window.lucide) window.lucide.createIcons();
        }

        function closeHomePdfPreview() {
            document.getElementById('home-pdf-modal').classList.add('hidden');
            document.getElementById('home-pdf-iframe').src = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeHomePdfPreview();
            }
        });

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', initCalendar);
    </script>

    <!-- Home PDF Preview Modal -->
    <div id="home-pdf-modal" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-3 sm:p-6 hidden">
        <div class="bg-white rounded-2xl w-full max-w-5xl h-[92vh] flex flex-col shadow-2xl border border-[#D5CEBC] overflow-hidden">
            <div class="p-4 bg-[#2C3E2D] text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5 min-w-0 pr-4">
                    <div class="w-8 h-8 rounded-lg bg-[#5A6B47] flex items-center justify-center shrink-0">
                        <i data-lucide="file-text" class="w-4 h-4 text-white"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 id="home-pdf-title" class="font-heading font-bold text-sm truncate text-[#FAF8F2]">พรีวิวเอกสาร</h3>
                        <p id="home-pdf-filename" class="text-[11px] text-[#A3B88C] font-mono truncate">filename.pdf</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a id="home-pdf-download-btn" href="#" download class="px-3 py-1.5 rounded-lg bg-[#5A6B47] hover:bg-[#7B8D65] text-white text-xs font-semibold flex items-center gap-1.5 transition">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">ดาวน์โหลด</span>
                    </a>
                    <a id="home-pdf-external-btn" href="#" target="_blank" class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs transition" title="เปิดแท็บใหม่เต็มจอ">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                    </a>
                    <button type="button" onclick="closeHomePdfPreview()" class="p-1.5 text-white/70 hover:text-white rounded-lg transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div class="flex-grow bg-[#525659] relative">
                <iframe id="home-pdf-iframe" src="" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>
</body>
</html>
