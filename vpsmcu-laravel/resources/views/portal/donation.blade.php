@php
    $currentRoute = Route::currentRouteName();
    $bankName = $settings['donation_bank_name'] ?? 'ธนาคารทหารไทยธนชาต (ttb)';
    $accName = $settings['donation_account_name'] ?? 'เพื่อพัฒนาสถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย';
    $accNum = $settings['donation_account_number'] ?? '231-2-93605-3';
    $promptpay = $settings['donation_promptpay'] ?? '0994000159451';
    $contactSettings = $contactSettings ?? [];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรับบริจาค | VPSMCU มจร</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>

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
                <span class="text-[#EAE5D9]">มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย — ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน (VPSMCU)</span>
            </div>
            <div class="flex items-center space-x-4 text-[#D8D2C2] text-[11px]">
                <span class="flex items-center gap-1.5"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#A3B88C]"></i> สถาบันวิปัสสนาธุระ</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 bg-[#FAF8F2]/95 backdrop-blur-xl border-b border-[#E3DEC9] shadow-xs transition">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3.5">
                    <a href="{{ route('home') }}" class="shrink-0 flex items-center">
                        <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-12 h-12 object-contain drop-shadow-sm hover:scale-105 transition">
                    </a>
                    <div>
                        <a href="{{ route('home') }}" class="font-heading font-extrabold text-xl text-[#2C3E2D] tracking-tight leading-tight flex items-center gap-2">
                            VPSMCU
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#EAE5D9] text-[#4A3B32] border border-[#D5CEBC]">มจร</span>
                        </a>
                        <p class="text-xs text-[#6B6357] font-medium">ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน</p>
                    </div>
                </div>

                <!-- Nav Links -->
                <nav class="hidden xl:flex items-center space-x-6 text-[15px] font-semibold text-[#4A3B32]">
                    <!-- Schedule Dropdown Menu -->
                    <div class="relative group py-2">
                        <a href="{{ route('home') }}#calendar" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 focus:outline-none whitespace-nowrap py-1">
                            <i data-lucide="calendar" class="w-4 h-4 text-[#4A3B32]"></i>
                            <span>ปฏิทิน</span>
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
                                        <div class="font-semibold text-sm">ตารางโครงการทั้งหมด</div>
                                        <div class="text-xs text-[#7B8D65]">รวม 51 ส่วนงานทั่วประเทศ</div>
                                    </div>
                                </a>
                                <a href="{{ route('home') }}#calendar" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-[#2C3E2D] hover:bg-[#FAF8F2] transition">
                                    <span class="w-8 h-8 rounded-lg bg-[#5A6B47]/15 flex items-center justify-center text-[#5A6B47] shrink-0">
                                        <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <div class="font-semibold text-sm text-[#5A6B47]">ระดับปริญญาตรี</div>
                                        <div class="text-xs text-[#7B8D65]">โครงการภาคบังคับ 10 วัน/ปี</div>
                                    </div>
                                </a>
                                <a href="{{ route('home') }}#calendar" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-[#2C3E2D] hover:bg-[#FAF8F2] transition">
                                    <span class="w-8 h-8 rounded-lg bg-[#C86D51]/15 flex items-center justify-center text-[#C86D51] shrink-0">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <div class="font-semibold text-sm text-[#C86D51]">คอร์สประชาชนทั่วไป</div>
                                        <div class="text-xs text-[#7B8D65]">หลักสูตรระยะสั้นและพิเศษ</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ตรวจสอบวัน -->
                    <a href="{{ route('grad.progress') }}" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="graduation-cap" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>ตรวจสอบวัน</span>
                    </a>

                    <!-- ยื่นคำร้อง -->
                    <a href="{{ route('grad.request') }}" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="file-text" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>ยื่นคำร้อง</span>
                    </a>

                    <!-- ฐานข้อมูล -->
                    <a href="{{ route('ug.check') }}" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="users" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>ฐานข้อมูล</span>
                    </a>

                    <!-- ติดต่อ -->
                    <a href="{{ route('contact') }}" class="hover:text-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="phone" class="w-4.5 h-4.5 text-[#4A3B32]"></i>
                        <span>ติดต่อ</span>
                    </a>

                    <!-- ร่วมบริจาค -->
                    <a href="{{ route('donation') }}" class="hover:text-[#A85238] transition flex items-center gap-1.5 text-[#C86D51] font-bold whitespace-nowrap py-1">
                        <i data-lucide="gift" class="w-4.5 h-4.5 text-[#C86D51]"></i>
                        <span>ร่วมบริจาค</span>
                    </a>
                </nav>

                <!-- Language Switcher & Hamburger Button -->
                <div class="flex items-center space-x-2.5">
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
                            <span>ปฏิทิน</span>
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
                                <div class="font-semibold">ตารางโครงการทั้งหมด</div>
                                <div class="text-[10px] text-[#7B8D65]">รวม 51 ส่วนงานทั่วประเทศ</div>
                            </div>
                        </a>
                        <a href="{{ route('home') }}#calendar" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-[#2C3E2D] hover:bg-white transition">
                            <span class="w-6 h-6 rounded-md bg-[#5A6B47]/15 flex items-center justify-center text-[#5A6B47] shrink-0">
                                <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i>
                            </span>
                            <div>
                                <div class="font-semibold text-[#5A6B47]">ระดับปริญญาตรี</div>
                                <div class="text-[10px] text-[#7B8D65]">โครงการภาคบังคับ 10 วัน/ปี</div>
                            </div>
                        </a>
                        <a href="{{ route('home') }}#calendar" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-[#2C3E2D] hover:bg-white transition">
                            <span class="w-6 h-6 rounded-md bg-[#C86D51]/15 flex items-center justify-center text-[#C86D51] shrink-0">
                                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            </span>
                            <div>
                                <div class="font-semibold text-[#C86D51]">คอร์สประชาชนทั่วไป</div>
                                <div class="text-[10px] text-[#7B8D65]">หลักสูตรระยะสั้นและพิเศษ</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- ลิงก์เมนูหลัก -->
                <div class="space-y-1 pt-1">
                    <a href="{{ route('grad.progress') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#4A3B32] hover:bg-white hover:text-[#5A6B47] transition">
                        <i data-lucide="graduation-cap" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>ตรวจสอบวัน</span>
                    </a>
                    <a href="{{ route('grad.request') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#4A3B32] hover:bg-white hover:text-[#5A6B47] transition">
                        <i data-lucide="file-text" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>ยื่นคำร้อง</span>
                    </a>
                    <a href="{{ route('student.login') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#4A3B32] hover:bg-white hover:text-[#5A6B47] transition">
                        <i data-lucide="users" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>ฐานข้อมูล</span>
                    </a>
                    <a href="{{ route('contact') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#4A3B32] hover:bg-white hover:text-[#5A6B47] transition">
                        <i data-lucide="phone" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>ติดต่อ</span>
                    </a>
                    <a href="{{ route('donation') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold text-[#C86D51] bg-[#C86D51]/10 hover:bg-[#C86D51]/20 transition">
                        <i data-lucide="gift" class="w-4.5 h-4.5 text-[#C86D51]"></i>
                        <span>ร่วมบริจาค</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- Header Hero Banner (Matching public_check standard with full Buddha & Wheel visible) -->
        <div class="hero-banner-card rounded-[28px] p-6 sm:p-10 text-white shadow-md border border-[#205C29]/40 relative overflow-hidden mb-8 flex flex-col justify-center">
            <div class="relative z-10 max-w-xl">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold tracking-tight mb-2 drop-shadow-md text-white">
                    ระบบรับบริจาค
                </h1>
                <p class="text-sm sm:text-base md:text-lg text-emerald-100 font-medium drop-shadow-sm">
                    ร่วมทำบุญอุปถัมภ์โครงการปฏิบัติวิปัสสนากรรมฐาน
                </p>
            </div>
        </div>

        <!-- Success Confirmation View -->
        @if (session('donation_success_complete') || (session('success') && session('donation_no')))
            <div class="bg-white rounded-[28px] p-8 md:p-12 border border-[#EAE5D9] shadow-xl text-center max-w-3xl mx-auto mb-12">
                <div class="w-20 h-20 rounded-full bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center mx-auto mb-6 border-4 border-[#5A6B47]/20 shadow-md">
                    <i data-lucide="check-circle" class="w-10 h-10"></i>
                </div>
                
                <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-xs font-semibold bg-[#5A6B47]/10 text-[#5A6B47] mb-3 border border-[#5A6B47]/20">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span>บันทึกข้อมูลการร่วมบุญเรียบร้อยแล้ว</span>
                </span>
                
                <h2 class="text-2xl md:text-3xl font-heading font-extrabold text-[#2C3E2D] mb-3">
                    ขออนุโมทนาในกุศลศรัทธาของท่าน
                </h2>
                <p class="text-sm md:text-base text-[#6B6357] max-w-xl mx-auto leading-relaxed mb-6">
                    {{ session('success') }}
                </p>

                <!-- รายละเอียดใบแจ้งการบริจาค -->
                <div class="bg-[#FAF8F2] rounded-2xl p-6 border border-[#EAE5D9] text-left max-w-xl mx-auto mb-8 text-xs space-y-3">
                    <div class="flex items-center justify-between pb-3 border-b border-[#EAE5D9]">
                        <span class="text-[#7B8D65] font-semibold">รหัสอ้างอิงการบริจาค:</span>
                        <span class="font-mono text-base font-bold text-[#C86D51]">{{ session('donation_no') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#7B8D65]">ชื่อผู้ร่วมบุญ:</span>
                        <strong class="text-sm text-[#2C3E2D]">{{ session('donor_name') }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#7B8D65]">จำนวนเงินที่ร่วมบริจาค:</span>
                        <strong class="text-sm font-mono text-[#5A6B47] font-bold">{{ session('amount') }} บาท</strong>
                    </div>
                    @if(session('is_tax_deductible'))
                        <div class="flex items-center justify-between pt-2 border-t border-[#EAE5D9]">
                            <span class="text-[#7B8D65]">ความประสงค์รับใบเสร็จ:</span>
                            <span class="text-[#5A6B47] font-semibold flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> ขอรับใบเสร็จ / ใบอนุโมทนาบัตร
                            </span>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('donation') }}" class="px-8 py-3 rounded-2xl bg-[#234E2B] hover:bg-[#1A3B20] text-white font-semibold text-sm shadow-md transition">
                        ร่วมบริจาคทำบุญอีกครั้ง
                    </a>
                    <a href="{{ route('home') }}" class="px-6 py-3 rounded-2xl bg-white hover:bg-[#FAF8F2] text-[#4A3B32] font-semibold text-sm border border-[#D5CEBC] transition">
                        กลับสู่หน้าหลัก
                    </a>
                </div>
            </div>

        @else

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl mb-8 shadow-sm">
                    <div class="flex items-center mb-1">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-2 shrink-0"></i>
                        <h5 class="text-sm font-bold text-red-800">กรุณาตรวจสอบข้อมูล</h5>
                    </div>
                    <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5 ml-2">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Section 1: ช่องทางบัญชีธนาคาร & สแกน QR Code (Card 1: กรอบซ้าย-ขวา สูงเท่ากันสมบูรณ์) -->
            <div class="bg-white rounded-[26px] p-6 md:p-8 border border-[#EAE5D9] shadow-sm mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    
                    <!-- Left: ข้อมูลบัญชีธนาคาร ttb (ความสูงเท่ากันกับฝั่งขวา) -->
                    <div class="lg:col-span-7 bg-[#FAF8F2] rounded-3xl p-5 sm:p-6 md:p-8 border border-[#F0ECE1] flex flex-col justify-between space-y-6">
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 mb-4 sm:mb-6">
                                <!-- ttb Logo Box -->
                                <div class="w-16 h-16 sm:w-24 sm:h-24 md:w-28 md:h-28 bg-white rounded-2xl sm:rounded-3xl p-2.5 sm:p-4.5 flex items-center justify-center shadow-sm border border-[#EAE5D9] shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 120" class="w-full h-auto max-h-10 sm:max-h-16">
                                        <!-- First 't': สดใสสีฟ้าคราม/น้ำเงิน (#0050F0) -->
                                        <path fill="#0050F0" d="m72,72c0,13.25-10.75,24-24,24s-24-10.75-24-24v-24h24v-24h-24V0H0v72c0,26.51,21.49,48,48,48s48-21.49,48-48h-24Z"/>
                                        <!-- Second 't': สดใสสีส้มสด (#F68B1F) -->
                                        <path fill="#F68B1F" d="m144,72c0,13.25-10.75,24-24,24s-24-10.75-24-24v-24h24v-24h-24V0h-24v72c0,26.51,21.49,48,48,48s48-21.49,48-48h-24Z"/>
                                        <!-- 'b': น้ำเงินเข้มคราม (#002D63) -->
                                        <path fill="#002D63" d="m192,96c-13.25,0-24-10.75-24-24s10.74-24,24-24,24,10.75,24,24-10.74,24-24,24m0-72c-8.74,0-16.94,2.34-24,6.42V0h-24v72c0,26.51,21.49,48,48,48s48-21.49,48-48-21.49-48-48-48"/>
                                    </svg>
                                </div>

                                <div class="pt-0 sm:pt-1">
                                    <h2 class="text-lg sm:text-2xl md:text-3xl font-heading font-black text-[#1F2937] leading-tight">
                                        ธนาคารทหารไทยธนชาต (ttb)
                                    </h2>
                                    <div class="mt-1.5 sm:mt-2 text-xs sm:text-base md:text-lg text-[#4B5563] font-medium leading-relaxed">
                                        <span class="text-[#7B8D65] font-semibold">ชื่อบัญชี:</span> เพื่อพัฒนาสถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- เลขบัญชี & ปุ่มคัดลอก: รองรับการแสดงผลทุกหน้าจอ ไม่ถูก truncate บนมือถือ -->
                        <div class="bg-white rounded-2xl p-4 sm:p-5 md:p-6 border border-[#EAE5D9] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 mt-4 sm:mt-6">
                            <div class="w-full sm:w-auto text-center sm:text-left">
                                <span class="block text-[11px] sm:text-xs text-[#7B8D65] font-semibold mb-0.5 sm:hidden">เลขที่บัญชี:</span>
                                <span id="bank-acc-text" class="text-2xl sm:text-3xl md:text-4xl lg:text-[38px] xl:text-[44px] font-black font-mono text-[#C92A2A] tracking-wider sm:tracking-normal leading-none select-all whitespace-nowrap block">
                                    231-2-93605-3
                                </span>
                            </div>

                            <button type="button" onclick="copyToClipboard('bank-acc-text', 'copy-btn-text')" class="w-full sm:w-auto justify-center px-4 py-2.5 sm:px-5 sm:py-2.5 bg-[#FAF8F2] hover:bg-white text-[#374151] hover:text-[#2C3E2D] rounded-xl border border-[#D5CEBC] hover:border-[#9CA3AF] text-xs sm:text-sm font-semibold flex items-center gap-1.5 sm:gap-2 transition shadow-xs hover:shadow-sm shrink-0">
                                <i data-lucide="copy" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#5A6B47]"></i>
                                <span id="copy-btn-text">คัดลอกเลขบัญชี</span>
                            </button>
                        </div>
                    </div>

                    <!-- Right: สแกน QR Code เพื่อร่วมทำบุญ (ข้อความรวมอยู่ในกรอบเดียวกับ QR) -->
                    <div class="lg:col-span-5 bg-[#FAF8F2] rounded-3xl p-6 md:p-8 border border-[#F0ECE1] flex flex-col items-center justify-center">
                        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-[#E5E7EB] shadow-md w-full max-w-[280px] text-center flex flex-col items-center">
                            <!-- ข้อความสแกน QR Code ภายในกรอบเดียวกัน -->
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[11px] sm:text-xs text-[#374151] font-bold mb-3 shadow-2xs">
                                <i data-lucide="qr-code" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                <span>สแกน QR Code เพื่อร่วมทำบุญ</span>
                            </div>

                            <!-- ภาพ QR Code -->
                            <img src="{{ asset('images/qr-codepayment.jpg') }}" alt="สแกน QR Code เพื่อร่วมทำบุญ" class="w-full h-auto rounded-xl object-contain shadow-2xs">
                        </div>
                    </div>

                </div>
            </div>

            <!-- Section 2: ฟอร์มกรอกข้อมูลการบริจาค (Card 2) -->
            <form action="{{ route('donation.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6 mb-12">
                @csrf
                <input type="hidden" name="bank_account" value="ธนาคารทหารไทยธนชาต (ttb) (231-2-93605-3)">

                <!-- กล่องที่ 1: ข้อมูลผู้บริจาคและจำนวนเงิน -->
                <div class="bg-white rounded-[26px] p-6 md:p-8 border border-[#EAE5D9] shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        
                        <!-- 1. จำนวนบริจาค (ซ้าย) -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[#5A6B47] flex items-center justify-center shrink-0">
                                    <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                                </div>
                                <label class="text-xs font-bold text-[#1F2937]">จำนวนบริจาค <span class="text-red-500">*</span></label>
                            </div>
                            <div class="flex rounded-xl overflow-hidden border border-[#D1D5DB] focus-within:border-[#5A6B47] focus-within:ring-1 focus-within:ring-[#5A6B47]">
                                <input type="number" step="0.01" min="1" name="amount" value="{{ old('amount') }}" required placeholder="ระบุจำนวนเงิน" class="w-full px-3.5 py-2.5 text-xs text-[#1F2937] placeholder-gray-400 focus:outline-none">
                                <span class="bg-[#F3F4F6] px-4 py-2.5 text-xs text-[#4B5563] font-medium border-l border-[#D1D5DB] flex items-center">บาท</span>
                            </div>
                        </div>

                        <!-- 2. ชื่อ-นามสกุล (ขวา) -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[#5A6B47] flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                </div>
                                <label class="text-xs font-bold text-[#1F2937]">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                            </div>
                            <input type="text" name="donor_name" value="{{ old('donor_name') }}" required placeholder="ระบุชื่อ-นามสกุล" class="w-full px-3.5 py-2.5 rounded-xl border border-[#D1D5DB] text-xs text-[#1F2937] placeholder-gray-400 focus:outline-none focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                        </div>

                        <!-- 3. เลขประจำตัวผู้เสียภาษี (ซ้าย) -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[#5A6B47] flex items-center justify-center shrink-0">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                </div>
                                <label class="text-xs font-bold text-[#1F2937]">เลขประจำตัวผู้เสียภาษี</label>
                            </div>
                            <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id') }}" maxlength="20" placeholder="ระบุเลขประจำตัวผู้เสียภาษี 13 หลัก" class="w-full px-3.5 py-2.5 rounded-xl border border-[#D1D5DB] text-xs font-mono text-[#1F2937] placeholder-gray-400 focus:outline-none focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                        </div>

                        <!-- 4. เบอร์โทร (ขวา) -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[#5A6B47] flex items-center justify-center shrink-0">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                </div>
                                <label class="text-xs font-bold text-[#1F2937]">เบอร์โทร</label>
                            </div>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="ระบุเบอร์โทรศัพท์" class="w-full px-3.5 py-2.5 rounded-xl border border-[#D1D5DB] text-xs font-mono text-[#1F2937] placeholder-gray-400 focus:outline-none focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                        </div>

                        <!-- 5. เช็คบ็อกซ์: ความประสงค์รับใบเสร็จ / ใบอนุโมทนาบัตร (Full Width) -->
                        <div class="md:col-span-2 p-3.5 rounded-2xl bg-[#FAF8F2] border border-[#EAE5D9] flex flex-wrap items-center justify-between gap-3">
                            <label class="flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="is_tax_deductible" id="is_tax_deductible" value="1" {{ old('is_tax_deductible', '1') ? 'checked' : '' }} onchange="toggleReceiptAddress(this)" class="w-4 h-4 text-[#2D6A4F] rounded border-[#D1D5DB] focus:ring-[#5A6B47]">
                                <span class="ml-2.5 text-xs font-bold text-[#1F2937] flex items-center gap-1.5">
                                    <i data-lucide="receipt" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                    <span>ต้องการรับใบเสร็จรับเงิน / ใบอนุโมทนาบัตร (สามารถนำไปลดหย่อนภาษีได้)</span>
                                </span>
                            </label>
                            <span class="text-[10px] text-[#5A6B47] font-semibold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                ลดหย่อนภาษี e-Donation
                            </span>
                        </div>

                        <!-- 6. ที่อยู่สำหรับจัดส่งใบเสร็จ (ถ้ามี) (Full Width) -->
                        <div class="md:col-span-2 space-y-3" id="receipt-address-container">
                            <div class="flex items-center justify-between pb-1.5 border-b border-[#EAE5D9]">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[#5A6B47] flex items-center justify-center shrink-0">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <label class="text-xs font-bold text-[#1F2937]">ที่อยู่สำหรับจัดส่งใบเสร็จ / ใบอนุโมทนาบัตร (ถ้ามี)</label>
                                </div>
                                <span class="text-[11px] text-[#7B8D65] font-mono hidden sm:inline">จัดส่งทางไปรษณีย์</span>
                            </div>

                            <!-- ที่อยู่ เลขที่ / หมู่ / ซอย / ถนน / อาคาร -->
                            <div>
                                <label class="block text-[11px] font-semibold text-[#4B5563] mb-1">บ้านเลขที่ / หมู่ที่ / อาคาร / ซอย / ถนน</label>
                                <input type="text" id="donation_addr_line" name="addr_line" value="{{ old('addr_line') }}" oninput="updateFullAddress()" placeholder="เช่น 79 หมู่ 1 ตึกวิปัสสนาธุระ ถ.พหลโยธิน" class="w-full px-3.5 py-2.5 rounded-xl border border-[#D1D5DB] text-xs text-[#1F2937] placeholder-gray-400 focus:outline-none focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                            </div>

                            <!-- Cascading Dropdowns: จังหวัด, อำเภอ, ตำบล, รหัสไปรษณีย์ -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#4B5563] mb-1">จังหวัด</label>
                                    <select id="donation_province" name="province" onchange="onDonationProvinceChange(this.value)" class="w-full px-3 py-2 bg-white border border-[#D1D5DB] rounded-xl text-xs text-[#1F2937] focus:outline-none focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                                        <option value="">-- เลือกจังหวัด --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#4B5563] mb-1">อำเภอ / เขต</label>
                                    <select id="donation_district" name="district" disabled onchange="onDonationDistrictChange(this.value)" class="w-full px-3 py-2 bg-white border border-[#D1D5DB] rounded-xl text-xs text-[#1F2937] focus:outline-none focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47] disabled:bg-[#F3F4F6] disabled:text-[#9CA3AF]">
                                        <option value="">-- เลือกอำเภอ / เขต --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#4B5563] mb-1">ตำบล / แขวง</label>
                                    <select id="donation_subdistrict" name="subdistrict" disabled onchange="onDonationSubdistrictChange(this.value)" class="w-full px-3 py-2 bg-white border border-[#D1D5DB] rounded-xl text-xs text-[#1F2937] focus:outline-none focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47] disabled:bg-[#F3F4F6] disabled:text-[#9CA3AF]">
                                        <option value="">-- เลือกตำบล / แขวง --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#4B5563] mb-1">รหัสไปรษณีย์</label>
                                    <input type="text" id="donation_postal_code" name="postal_code" value="{{ old('postal_code') }}" oninput="updateFullAddress()" maxlength="5" placeholder="เช่น 13170" class="w-full px-3.5 py-2 rounded-xl bg-[#FAF8F2] border border-[#D1D5DB] text-xs font-mono text-[#1F2937] focus:outline-none focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                                </div>
                            </div>

                            <!-- Hidden / Compiled Address input for full address compatibility -->
                            <input type="hidden" name="address" id="donation_full_address" value="{{ old('address') }}">
                        </div>

                    </div>
                </div>

                <!-- กล่องที่ 2: อัปโหลดแนบสลิป และ แนบรูปสำหรับทำโปสเตอร์ (Card 3) -->
                <div class="bg-white rounded-[26px] p-6 md:p-8 border border-[#EAE5D9] shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- 1. แนบสลิป (บังคับ) -->
                        <div>
                            <div class="flex items-center gap-2 mb-2.5">
                                <div class="w-7 h-7 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[#5A6B47] flex items-center justify-center shrink-0">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                </div>
                                <label class="text-xs font-bold text-[#1F2937]">แนบสลิป <span class="text-red-500">*</span></label>
                            </div>

                            <div class="border border-dashed border-[#84A98C] bg-[#FAFDF8] hover:bg-[#F3F9F1] rounded-2xl p-6 text-center cursor-pointer transition min-h-[120px] flex flex-col items-center justify-center group" onclick="document.getElementById('slip-file').click()">
                                <input type="file" name="slip" id="slip-file" required accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewSlip(event)" class="hidden">
                                <div class="w-10 h-10 rounded-full bg-white shadow-2xs flex items-center justify-center text-[#2D6A4F] mb-2 group-hover:scale-110 transition">
                                    <i data-lucide="cloud-upload" class="w-6 h-6 stroke-[2]"></i>
                                </div>
                                <span class="text-xs font-semibold text-[#1F2937]" id="slip-file-label">คลิกเพื่ออัปโหลดสลิปการโอนเงิน</span>
                                <span class="text-[10px] text-[#6B7280] mt-1">เฉพาะไฟล์รูปภาพ JPG, PNG, WEBP (ขนาดไม่เกิน 10MB)</span>
                            </div>

                            <!-- Slip Preview -->
                            <div id="slip-preview-box" class="hidden mt-2 p-2 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9] flex items-center gap-2.5">
                                <img id="slip-img-preview" src="#" alt="Slip Preview" class="w-10 h-10 object-cover rounded-lg border border-[#D5CEBC]">
                                <div class="text-[11px] truncate">
                                    <div id="slip-filename" class="font-semibold text-[#2C3E2D] truncate"></div>
                                    <div class="text-[10px] text-[#5A6B47] flex items-center gap-1">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        <span>พร้อมส่งสลิป</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. แนบรูป (สำหรับทำโปสเตอร์) (ไม่บังคับ) -->
                        <div>
                            <div class="flex items-center gap-2 mb-2.5">
                                <div class="w-7 h-7 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[#5A6B47] flex items-center justify-center shrink-0">
                                    <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                </div>
                                <label class="text-xs font-bold text-[#1F2937]">แนบรูป (สำหรับทำโปสเตอร์)</label>
                            </div>

                            <div class="border border-dashed border-[#84A98C] bg-[#FAFDF8] hover:bg-[#F3F9F1] rounded-2xl p-6 text-center cursor-pointer transition min-h-[120px] flex flex-col items-center justify-center group" onclick="document.getElementById('avatar-file').click()">
                                <input type="file" name="avatar" id="avatar-file" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewAvatar(event)" class="hidden">
                                <div class="w-10 h-10 rounded-full bg-white shadow-2xs flex items-center justify-center text-[#2D6A4F] mb-2 group-hover:scale-110 transition">
                                    <i data-lucide="cloud-upload" class="w-6 h-6 stroke-[2]"></i>
                                </div>
                                <span class="text-xs font-semibold text-[#1F2937]" id="avatar-file-label">คลิกเพื่ออัปโหลดรูปภาพ</span>
                                <span class="text-[10px] text-[#6B7280] mt-1">เฉพาะไฟล์รูปภาพ JPG, PNG, WEBP (ขนาดไม่เกิน 10MB)</span>
                            </div>

                            <!-- Avatar Preview -->
                            <div id="avatar-preview-box" class="hidden mt-2 p-2 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9] flex items-center gap-2.5">
                                <img id="avatar-img-preview" src="#" alt="Avatar Preview" class="w-10 h-10 object-cover rounded-lg border border-[#D5CEBC]">
                                <div class="text-[11px] truncate">
                                    <div id="avatar-filename" class="font-semibold text-[#2C3E2D] truncate"></div>
                                    <div class="text-[10px] text-[#5A6B47] flex items-center gap-1">
                                        <i data-lucide="sparkles" class="w-3 h-3 text-[#C86D51]"></i>
                                        <span>พร้อมส่งรูปโปสเตอร์</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ปุ่มส่งข้อมูลการบริจาค (ตรงกลาง) -->
                    <div class="pt-8 text-center">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-10 py-3 bg-[#234E2B] hover:bg-[#1A3B20] text-white font-semibold text-sm rounded-xl shadow-md transition hover:scale-[1.02] active:scale-[0.98]">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            <span>ส่งข้อมูลการบริจาค</span>
                        </button>
                    </div>
                </div>
            </form>

        @endif

    </main>

    <!-- Footer -->
    <footer id="contact" class="bg-[#1C281F] text-[#D5CEBC] text-xs py-10 border-t border-[#152018]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
                <div>
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                        <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-8 h-8 object-contain">
                        <span class="font-heading font-bold text-white text-sm">
                            มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย • ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน (VPSMCU)
                        </span>
                    </div>
                    <p class="text-[#A3B88C] text-xs">มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย 79 หมู่ 1 ต.ลำไทร อ.วังน้อย จ.พระนครศรีอยุธยา 13170</p>
                    <p class="text-[#8C8275] text-xs mt-1">
                        สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย • <a href="{{ route('contact') }}" class="underline hover:text-white">ดูช่องทางติดต่อสอบถาม & แผนที่</a>
                    </p>
                </div>
                <div class="flex flex-col items-center md:items-end gap-2.5">
                    @if (Session::has('admin_user'))
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium text-xs border border-white/15 transition shadow-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#A3B88C]"></i>
                            <span>แผงควบคุมผู้ดูแลระบบ</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#28382B] hover:bg-[#344837] text-[#EAE5D9] hover:text-white font-medium text-xs border border-[#3E5242] transition shadow-sm group">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#A3B88C] group-hover:text-white transition"></i>
                            <span>เจ้าหน้าที่เข้าสู่ระบบ</span>
                        </a>
                    @endif
                    <div class="text-[10px] text-[#7A7367] font-mono">
                        มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย • VPSMCU
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function copyToClipboard(elementId, btnTextId) {
            const text = document.getElementById(elementId).innerText.trim();
            navigator.clipboard.writeText(text).then(() => {
                const btn = document.getElementById(btnTextId);
                const originalText = btn.textContent;
                btn.textContent = 'คัดลอกแล้ว!';
                setTimeout(() => {
                    btn.textContent = originalText;
                }, 2000);
            });
        }

        function previewSlip(event) {
            const input = event.target;
            const label = document.getElementById('slip-file-label');
            const previewBox = document.getElementById('slip-preview-box');
            const imgPreview = document.getElementById('slip-img-preview');
            const filenameText = document.getElementById('slip-filename');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                label.textContent = file.name;
                filenameText.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imgPreview.src = e.target.result;
                        previewBox.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewBox.classList.remove('hidden');
                    imgPreview.src = '/images/mcu-logo.png';
                }
            }
        }

        function previewAvatar(event) {
            const input = event.target;
            const label = document.getElementById('avatar-file-label');
            const previewBox = document.getElementById('avatar-preview-box');
            const imgPreview = document.getElementById('avatar-img-preview');
            const filenameText = document.getElementById('avatar-filename');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                label.textContent = file.name;
                filenameText.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imgPreview.src = e.target.result;
                        previewBox.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewBox.classList.remove('hidden');
                    imgPreview.src = '/images/mcu-logo.png';
                }
            }
        }

        function toggleReceiptAddress(checkbox) {
            const container = document.getElementById('receipt-address-container');
            const taxInput = document.getElementById('tax_id');
            if (checkbox.checked) {
                container.classList.remove('opacity-50');
                if (taxInput) taxInput.placeholder = "ระบุเลขประจำตัวผู้เสียภาษี 13 หลัก (เพื่อลดหย่อนภาษี)";
            } else {
                container.classList.add('opacity-50');
                if (taxInput) taxInput.placeholder = "ระบุเลขประจำตัวผู้เสียภาษี 13 หลัก (ถ้ามี)";
            }
        }

        // Thailand Address Cascading Engine for Donation Receipt
        let donationThaiProvinces = [];
        let donationThaiDistricts = [];
        let donationThaiSubdistricts = [];

        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const resProv = await fetch('/assets/data/provinces.json');
                donationThaiProvinces = await resProv.json();
                donationThaiProvinces.sort((a, b) => a.provinceNameTh.localeCompare(b.provinceNameTh, 'th'));

                const provSelect = document.getElementById('donation_province');
                if (provSelect) {
                    provSelect.innerHTML = '<option value="">-- เลือกจังหวัด --</option>';
                    donationThaiProvinces.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.provinceNameTh;
                        opt.textContent = p.provinceNameTh;
                        opt.dataset.provinceCode = p.provinceCode;
                        provSelect.appendChild(opt);
                    });
                }

                fetch('/assets/data/districts.json')
                    .then(r => r.json())
                    .then(data => { donationThaiDistricts = data; });

                fetch('/assets/data/subdistricts.json')
                    .then(r => r.json())
                    .then(data => { donationThaiSubdistricts = data; });

            } catch (err) {
                console.error('Failed to load address data for donation', err);
            }
        });

        function onDonationProvinceChange(provinceName) {
            const distSelect = document.getElementById('donation_district');
            const subSelect = document.getElementById('donation_subdistrict');
            const postalInput = document.getElementById('donation_postal_code');

            distSelect.innerHTML = '<option value="">-- กำลังโหลดอำเภอ... --</option>';
            distSelect.disabled = true;
            subSelect.innerHTML = '<option value="">-- กรุณาเลือกอำเภอก่อน --</option>';
            subSelect.disabled = true;
            postalInput.value = '';

            if (!provinceName) {
                distSelect.innerHTML = '<option value="">-- กรุณาเลือกจังหวัดก่อน --</option>';
                updateFullAddress();
                return;
            }

            const provOption = document.querySelector(`#donation_province option[value="${provinceName}"]`);
            const provinceCode = provOption ? parseInt(provOption.dataset.provinceCode) : null;

            const filteredDistricts = donationThaiDistricts.filter(d => d.provinceCode === provinceCode);
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
            updateFullAddress();
        }

        function onDonationDistrictChange(districtName) {
            const subSelect = document.getElementById('donation_subdistrict');
            const postalInput = document.getElementById('donation_postal_code');

            subSelect.innerHTML = '<option value="">-- กำลังโหลดตำบล... --</option>';
            subSelect.disabled = true;
            postalInput.value = '';

            if (!districtName) {
                subSelect.innerHTML = '<option value="">-- กรุณาเลือกอำเภอก่อน --</option>';
                updateFullAddress();
                return;
            }

            const distOption = document.querySelector(`#donation_district option[value="${districtName}"]`);
            const districtCode = distOption ? parseInt(distOption.dataset.districtCode) : null;

            const filteredSubdistricts = donationThaiSubdistricts.filter(s => s.districtCode === districtCode);
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
            updateFullAddress();
        }

        function onDonationSubdistrictChange(subdistrictName) {
            const subOption = document.querySelector(`#donation_subdistrict option[value="${subdistrictName}"]`);
            const postalInput = document.getElementById('donation_postal_code');
            if (subOption && subOption.dataset.postalCode) {
                postalInput.value = subOption.dataset.postalCode;
            }
            updateFullAddress();
        }

        function updateFullAddress() {
            const addrLine = (document.getElementById('donation_addr_line')?.value || '').trim();
            const subdistrict = (document.getElementById('donation_subdistrict')?.value || '').trim();
            const district = (document.getElementById('donation_district')?.value || '').trim();
            const province = (document.getElementById('donation_province')?.value || '').trim();
            const postalCode = (document.getElementById('donation_postal_code')?.value || '').trim();

            const isBangkok = (province === 'กรุงเทพมหานคร');
            const subLabel = isBangkok ? 'แขวง' : 'ต.';
            const distLabel = isBangkok ? 'เขต' : 'อ.';
            const provLabel = isBangkok ? '' : 'จ.';

            const parts = [];
            if (addrLine) parts.push(addrLine);
            if (subdistrict) parts.push(`${subLabel}${subdistrict}`);
            if (district) parts.push(`${distLabel}${district}`);
            if (province) parts.push(`${provLabel}${province}`);
            if (postalCode) parts.push(postalCode);

            const fullAddress = parts.join(' ');
            const fullInput = document.getElementById('donation_full_address');
            if (fullInput) {
                fullInput.value = fullAddress;
            }
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

        lucide.createIcons();
    </script>
</body>
</html>
