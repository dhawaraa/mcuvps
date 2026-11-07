<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบจำนวนวันกรรมฐาน ({{ $student->student_code }} - {{ $student->full_name }}) - VPSMCU</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/assets/js/font-resizer.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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
                            forest: '#2C3E2D',
                            olive: '#4D5E3C',
                            bark: '#4A3B32',
                            cream: '#FAF8F2',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Sarabun', sans-serif; 
            background-color: #F6F4EE; 
            color: #2D2A26; 
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }
        .hero-banner-card {
            background-image: linear-gradient(to right, rgba(16, 76, 28, 0.96) 0%, rgba(20, 85, 34, 0.88) 45%, rgba(26, 94, 38, 0.25) 80%, transparent 100%), url('{{ asset("images/hero2image.png") }}');
            background-size: cover;
            background-position: right 25%;
            background-repeat: no-repeat;
            min-height: 180px;
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between selection:bg-[#4D5E3C] selection:text-white">

    <!-- Top Navigation Header (Matching Sample Data/Student-HistoryRequest.png) -->
    <header class="bg-[#FAF8F2]/90 border-b border-[#E7E2D4] w-full sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3.5 group">
                    <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-12 h-12 object-contain drop-shadow-sm group-hover:scale-105 transition">
                    <div>
                        <div class="font-heading font-extrabold text-xl text-[#2C3E2D] tracking-tight leading-tight">
                            VPSMCU
                        </div>
                        <p class="text-xs text-[#6B6357] font-medium">มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย</p>
                    </div>
                </a>

                <!-- Center Pill Tabs (ยื่นคำร้อง e-Document / ตรวจประวัติสะสมวัน) -->
                <div class="hidden md:flex items-center bg-[#EFECE3] p-1 rounded-2xl border border-[#DFDACB]">
                    <a href="{{ route('grad.request') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#FAF8F2] transition flex items-center gap-1.5">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#7A7366]"></i>
                        <span>ยื่นคำร้อง e-Document</span>
                    </a>
                    <a href="{{ route('student.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#4D5E3C] text-white shadow-xs transition flex items-center gap-1.5">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>ตรวจประวัติสะสมวัน</span>
                    </a>
                </div>

                <!-- Right Actions: Language Switcher & Profile / Back to Home -->
                <div class="flex items-center space-x-4">
                    @php
                        $currentLang = session('locale', 'th');
                    @endphp
                    @include('portal.partials.font_resizer')
                    <div class="flex items-center bg-[#E5DFD0] p-1 rounded-xl text-xs font-bold font-mono">
                        <a href="{{ route('lang.switch', 'th') }}" title="ภาษาไทย" class="px-2.5 py-1 rounded-lg transition {{ $currentLang === 'th' ? 'bg-[#4D5E3C] text-white shadow-xs' : 'text-[#605B50] hover:text-[#2C3E2D]' }}">
                            TH
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" title="English" class="px-2.5 py-1 rounded-lg transition {{ $currentLang === 'en' ? 'bg-[#4D5E3C] text-white shadow-xs' : 'text-[#605B50] hover:text-[#2C3E2D]' }}">
                            EN
                        </a>
                    </div>

                    @if(session()->has('student_user'))
                        <a href="{{ route('student.profile') }}" title="ข้อมูลส่วนตัว" class="text-xs sm:text-sm font-semibold text-[#4D5E3C] hover:text-[#2C3E2D] flex items-center gap-1.5 transition">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            <span>ข้อมูลส่วนตัว</span>
                        </a>

                        <a href="{{ route('student.logout') }}" title="ออกจากระบบ" class="text-xs font-semibold text-[#C86D51] hover:text-[#A85238] flex items-center gap-1 transition ml-2">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">ออก</span>
                        </a>
                    @else
                        <a href="{{ route('student.login') }}" title="เข้าสู่ระบบ" class="text-xs sm:text-sm font-semibold bg-[#4D5E3C] hover:bg-[#3D4C30] text-white px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 transition shadow-xs">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            <span>เข้าสู่ระบบนิสิต</span>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content Container (Standard 7xl) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- 1. Top Hero Banner (ตรวจสอบจำนวนวันกรรมฐาน) -->
        <div class="hero-banner-card rounded-3xl p-8 sm:p-10 mb-8 shadow-sm relative overflow-hidden text-white flex flex-col justify-center border border-[#205C29]/40">
            <div class="max-w-2xl relative z-10">
                @if(isset($student) && $student)
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-xs text-xs font-mono font-semibold mb-3 border border-white/30 text-white">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#A5D6A7]"></i>
                        <span>บัญชีผู้ใช้งาน: {{ $student->student_code }}</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight mb-2 text-white">
                        {{ $student->full_name }}
                    </h1>
                    <p class="text-sm sm:text-base text-white/95 font-medium tracking-wide">
                        ตรวจสอบจำนวนวันกรรมฐานและประวัติการเข้าร่วมโครงการ • {{ $student->organizationUnit?->name_th ?? 'มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย' }}
                    </p>
                @else
                    <h1 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight mb-2 text-white">
                        ตรวจสอบจำนวนวันกรรมฐาน
                    </h1>
                    <p class="text-sm sm:text-base text-white/95 font-medium tracking-wide">
                        ตรวจสอบข้อมูลการเข้าร่วมโครงการของนิสิต
                    </p>
                @endif
            </div>
        </div>

        @if (session('search_not_found'))
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600 shrink-0"></i>
                <span>{{ session('search_not_found') }}</span>
            </div>
        @endif

        <!-- 2. ค้นหารหัสนิสิต Bar (Search Box Matching Sample) -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-[#EAE5D9] shadow-xs mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-[#14421B] text-white flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="search" class="w-6 h-6 stroke-[2]"></i>
                </div>
                <div>
                    <h2 class="font-heading font-bold text-base text-[#1E2519]">ค้นหารหัสนิสิต</h2>
                    <p class="text-xs text-[#7D7667]">กรอกรหัสนิสิตเพื่อตรวจสอบข้อมูลวันปฏิบัติธรรม</p>
                </div>
            </div>

            <form method="GET" action="{{ route('student.dashboard') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <div class="relative w-full sm:w-80">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#7D7667]">
                        <i data-lucide="user" class="w-4 h-4 stroke-[1.75]"></i>
                    </span>
                    <input type="text" name="search" value="{{ $searchCode ?? $student->student_code }}" placeholder="เช่น {{ $student->student_code }}"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#DCD6C7] bg-[#FAF8F2] text-xs sm:text-sm text-[#2D2A26] font-mono focus:outline-none focus:ring-1 focus:ring-[#4D5E3C] focus:bg-white placeholder:text-[#9C9484] transition">
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#14421B] hover:bg-[#0E3314] text-white font-medium text-xs sm:text-sm transition shadow-xs flex items-center justify-center gap-2 shrink-0">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>ตรวจสอบ</span>
                </button>
            </form>
        </div>

        <!-- 3. ข้อมูลนิสิต & จำนวนวันสะสม Cards (Side-by-side Matching Sample) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
            
            <!-- การ์ด ข้อมูลนิสิต (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-[#EAE5D9] shadow-xs flex flex-col justify-between">
                <div class="flex items-center gap-2.5 pb-4 mb-6 border-b border-[#F0ECE1]">
                    <i data-lucide="user" class="w-5 h-5 text-[#2D2A26] stroke-[2]"></i>
                    <h2 class="font-heading font-bold text-lg text-[#2D2A26]">ข้อมูลนิสิต</h2>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <!-- Circular Icon/Avatar with Lotus Watermark -->
                    <div class="w-28 h-28 rounded-full bg-[#FAF7F0] border-2 border-[#EFECE3] flex items-center justify-center shrink-0 relative overflow-hidden shadow-inner">
                        @if($student->avatar_url || $student->avatar_path)
                            <img src="{{ $student->avatar_url ?? asset('storage/' . $student->avatar_path) }}" alt="{{ $student->full_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex flex-col items-center justify-center text-[#B5A48B]">
                                <i data-lucide="user" class="w-12 h-12 stroke-[1.5]"></i>
                                <span class="text-[10px] mt-0.5">☸</span>
                            </div>
                        @endif
                    </div>

                    <!-- Detail fields -->
                    <div class="space-y-2.5 text-sm w-full">
                        <div class="flex">
                            <span class="text-[#7D7667] w-24 shrink-0">ชื่อ - สกุล</span>
                            <span class="text-[#7D7667] mr-3">:</span>
                            <span class="font-heading font-bold text-[#1E2519]">{{ $student->full_name }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-[#7D7667] w-24 shrink-0">รหัสนิสิต</span>
                            <span class="text-[#7D7667] mr-3">:</span>
                            <span class="font-mono font-bold text-[#1E2519]">{{ $student->student_code }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-[#7D7667] w-24 shrink-0">ระดับการศึกษา</span>
                            <span class="text-[#7D7667] mr-3">:</span>
                            <span class="text-[#2D2A26] font-medium">
                                {{ $student->degree_level_th }}
                            </span>
                        </div>
                        <div class="flex">
                            <span class="text-[#7D7667] w-24 shrink-0">คณะ</span>
                            <span class="text-[#7D7667] mr-3">:</span>
                            <span class="text-[#2D2A26]">{{ $student->faculty ?: '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-[#7D7667] w-24 shrink-0">สาขาวิชา</span>
                            <span class="text-[#7D7667] mr-3">:</span>
                            <span class="text-[#2D2A26]">{{ $student->major ?: '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-[#7D7667] w-24 shrink-0">สังกัด</span>
                            <span class="text-[#7D7667] mr-3">:</span>
                            <span class="text-[#2D2A26]">{{ $student->organizationUnit?->name_th ?: 'มจร' }}</span>
                        </div>
                    </div>
                </div>

                @if(session()->has('student_user'))
                    <div class="pt-4 mt-4 border-t border-[#F0ECE1] flex justify-end">
                        <a href="{{ route('student.profile') }}" class="text-xs font-semibold text-[#4D5E3C] hover:underline flex items-center gap-1">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> ดูและแก้ไขข้อมูลส่วนตัว
                        </a>
                    </div>
                @endif
            </div>

            <!-- การ์ด จำนวนวันสะสม (5 cols Matching Sample Design) -->
            @php
                // คำนวณวันสะสมรวมตามเกณฑ์ มจร:
                // - ปริญญาเอก: 45 วัน
                // - ปริญญาโท: 30 วัน
                // - ปริญญาตรี: 40 วัน (สะสมปีละ 10 วัน)
                // - ประกาศนียบัตร: 15 วัน
                $targetDays = $student->target_days;
                $accumDays = 0;

                if ($student->degree_level === 'BACHELOR') {
                    $targetDays = 40;
                    $accumDays = $ugCompletedDays;
                } elseif ($gradRecord) {
                    $targetDays = $gradRecord->target_days ?: $student->target_days;
                    $accumDays = $gradRecord->accumulated_days ?: 0;
                } else {
                    $accumDays = 0;
                }
                $percent = $targetDays > 0 ? min(100, round(($accumDays / $targetDays) * 100)) : 0;
                $isPassed = $accumDays >= $targetDays && $targetDays > 0;
            @endphp
            <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 border border-[#EAE5D9] shadow-xs flex flex-col justify-between relative overflow-hidden">
                
                <!-- Background Lotus Floral Watermarks -->
                <div class="absolute left-2 top-20 opacity-15 pointer-events-none">
                    <span class="text-6xl text-[#C8B89E]">🪷</span>
                </div>
                <div class="absolute right-2 top-20 opacity-15 pointer-events-none">
                    <span class="text-6xl text-[#C8B89E]">🪷</span>
                </div>

                <div>
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#F0ECE1]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="bar-chart-2" class="w-5 h-5 text-[#2D2A26] stroke-[2]"></i>
                            <h2 class="font-heading font-bold text-lg text-[#2D2A26]">จำนวนวันสะสม</h2>
                        </div>
                        <div class="text-[11px] text-[#7D7667] flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <span>ข้อมูล ณ วันที่ {{ date('j') }} {{ ['1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม'][date('n')] }} {{ date('Y') + 543 }}</span>
                        </div>
                    </div>

                    <!-- Big Counter Display -->
                    <div class="text-center py-2 relative z-10">
                        <div class="font-heading font-extrabold text-5xl sm:text-6xl text-[#14421B] tracking-tight leading-none mb-2">
                            {{ $accumDays }} <span class="text-2xl sm:text-3xl font-medium text-[#2D2A26]">/ {{ $targetDays }} วัน</span>
                        </div>
                        <p class="text-xs sm:text-sm font-semibold text-[#6B6357]">
                            คิดเป็น <strong class="text-[#14421B]">{{ $percent }}%</strong> ตามเกณฑ์ ({{ $student->target_days_note }})
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4 mb-2">
                        <div class="w-full bg-[#E5DFD0] h-3 rounded-full overflow-hidden">
                            <div class="bg-[#14421B] h-full rounded-full transition-all duration-700" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="text-right text-xs font-mono font-bold text-[#14421B] mt-1">{{ $percent }}%</div>
                    </div>
                </div>

                <!-- Bottom Action Badges/Buttons (ผ่านเกณฑ์แล้ว / ยังไม่ครบเกณฑ์) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-[#F0ECE1] relative z-10">
                    @if($isPassed)
                        <div class="p-3 rounded-2xl bg-[#EBF1E6] border border-[#CDE0C3] flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-[#14421B] text-white flex items-center justify-center shrink-0">
                                <i data-lucide="check" class="w-4 h-4 stroke-[2.5]"></i>
                            </div>
                            <div class="text-[11px] leading-tight">
                                <div class="font-bold text-[#14421B]">ผ่านเกณฑ์แล้ว</div>
                                <div class="text-[#5A6B47] text-[10px]">ครบตามจำนวนวันที่กำหนด</div>
                            </div>
                        </div>

                        <a href="{{ route('grad.request') }}" 
                            class="p-3 rounded-2xl bg-[#14421B] hover:bg-[#0E3314] text-white transition flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-2 text-xs font-medium">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                <span>ยื่นขอหนังสือรับรอง</span>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    @else
                        <div class="p-3 rounded-2xl bg-[#FAF5EB] border border-[#EADBBE] flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-[#C86D51] text-white flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-4 h-4 stroke-[2.5]"></i>
                            </div>
                            <div class="text-[11px] leading-tight">
                                <div class="font-bold text-[#A85238]">ยังไม่ครบเกณฑ์</div>
                                <div class="text-[#7D7266] text-[10px]">สะสม {{ $accumDays }} จาก {{ $targetDays }} วัน</div>
                            </div>
                        </div>

                        <a href="{{ route('home') }}" 
                            class="p-3 rounded-2xl bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#4A3B32] border border-[#D5CEBC] transition flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-2 text-xs font-medium">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#4D5E3C]"></i>
                                <span>ดูโครงการที่เปิดรับ</span>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-[#7D7667]"></i>
                        </a>
                    @endif
                </div>

            </div>

        </div>

        <!-- 4. ประวัติโครงการที่เข้าร่วม Table Card (Matching Sample Data/Student-HistoryRequest.png) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#EAE5D9] shadow-xs mb-8">
            <div class="flex items-center gap-2.5 pb-4 mb-6 border-b border-[#F0ECE1]">
                <i data-lucide="file-text" class="w-5 h-5 text-[#2D2A26] stroke-[2]"></i>
                <h2 class="font-heading font-bold text-lg text-[#2D2A26]">ประวัติโครงการที่เข้าร่วม</h2>
            </div>

            <!-- Table Container with Deep Green Header -->
            <div class="overflow-x-auto rounded-2xl border border-[#DCD6C7]">
                <table class="w-full text-left text-xs sm:text-sm text-[#2D2A26]">
                    <thead class="bg-[#14421B] text-white font-heading font-medium text-xs sm:text-sm">
                        <tr>
                            <th class="py-3.5 px-4 text-center w-14">ลำดับ</th>
                            <th class="py-3.5 px-4">ชื่อโครงการ</th>
                            <th class="py-3.5 px-4 text-center">วันที่</th>
                            <th class="py-3.5 px-4">สถานที่</th>
                            <th class="py-3.5 px-4 text-center whitespace-nowrap">จำนวนวัน</th>
                            <th class="py-3.5 px-4 text-center whitespace-nowrap">เอกสาร</th>
                            <th class="py-3.5 px-4 text-center whitespace-nowrap">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9] bg-white">
                        @php
                            $records = [];
                            if ($gradRecord && $gradRecord->creditEntries->isNotEmpty()) {
                                foreach ($gradRecord->creditEntries as $idx => $entry) {
                                    $records[] = [
                                        'title' => 'โครงการปฏิบัติธรรม ' . ($entry->location_name ?? 'ประจำปี'),
                                        'date' => ($entry->start_date ? \Carbon\Carbon::parse($entry->start_date)->format('j M Y') : '-') . ' - ' . ($entry->end_date ? \Carbon\Carbon::parse($entry->end_date)->format('j M Y') : '-'),
                                        'location' => $entry->location_name ?? '-',
                                        'days' => $entry->days . ' วัน',
                                        'has_doc' => !empty($entry->document_path),
                                        'doc_url' => $entry->document_path ? asset('storage/' . $entry->document_path) : '#',
                                        'status' => 'APPROVED',
                                    ];
                                }
                            } elseif ($ugRegistrations->isNotEmpty()) {
                                foreach ($ugRegistrations as $idx => $reg) {
                                    $records[] = [
                                        'title' => $reg->batch?->title ?? 'โครงการปฏิบัติธรรมประจำปี ' . ($reg->batch?->academic_year ?? ''),
                                        'date' => ($reg->batch?->start_date ? \Carbon\Carbon::parse($reg->batch->start_date)->format('j M Y') : '-') . ' - ' . ($reg->batch?->end_date ? \Carbon\Carbon::parse($reg->batch->end_date)->format('j M Y') : '-'),
                                        'location' => $reg->batch?->location ?? $reg->batch?->organizationUnit?->name_th ?? 'ศูนย์ปฏิบัติธรรม มจร',
                                        'days' => '10 วัน',
                                        'has_doc' => !empty($reg->registration_no),
                                        'doc_url' => $reg->registration_no ? route('ug.certificate', $reg->registration_no) : '#',
                                        'status' => $reg->status,
                                    ];
                                }
                            }
                            if (!empty($publicRegistrations) && $publicRegistrations->isNotEmpty()) {
                                foreach ($publicRegistrations as $pReg) {
                                    $records[] = [
                                        'title' => $pReg->event?->title ?? 'โครงการปฏิบัติธรรมทั่วไป',
                                        'date' => ($pReg->event?->start_date ? \Carbon\Carbon::parse($pReg->event->start_date)->format('j M Y') : '-') . ' - ' . ($pReg->event?->end_date ? \Carbon\Carbon::parse($pReg->event->end_date)->format('j M Y') : '-'),
                                        'location' => $pReg->event?->location ?? $pReg->event?->organizationUnit?->name_th ?? 'ศูนย์ปฏิบัติธรรม มจร',
                                        'days' => ($pReg->event?->days ?? 0) . ' วัน',
                                        'has_doc' => !empty($pReg->registration_no),
                                        'doc_url' => '#',
                                        'status' => $pReg->status,
                                    ];
                                }
                            }
                        @endphp

                        @forelse($records as $index => $r)
                            <tr class="hover:bg-[#FAF8F2] transition">
                                <td class="py-3 px-4 text-center text-[#7D7667] font-mono">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-medium text-[#1E2519]">{{ $r['title'] }}</td>
                                <td class="py-3 px-4 text-center text-[#4A4438] whitespace-nowrap text-xs">{{ $r['date'] }}</td>
                                <td class="py-3 px-4 text-[#4A4438]">{{ $r['location'] }}</td>
                                <td class="py-3 px-4 text-center font-bold text-[#14421B] whitespace-nowrap">{{ $r['days'] }}</td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <a href="{{ $r['doc_url'] }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-[#2D2A26] hover:text-[#14421B] font-medium transition">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5 text-[#7D7667]"></i>
                                        <span>ดูเอกสาร</span>
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    @if($r['status'] === 'APPROVED' || $r['status'] === 'COMPLETED')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#EBF1E6] text-[#14421B] font-medium text-xs">
                                            <i data-lucide="check" class="w-3 h-3 stroke-[2.5]"></i>
                                            <span>ตรวจสอบแล้ว</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#FEF6E7] text-[#B57C1E] font-medium text-xs">
                                            <i data-lucide="clock" class="w-3 h-3"></i>
                                            <span>รอตรวจสอบ</span>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-[#7D7667]">
                                    <div class="flex flex-col items-center justify-center gap-2.5">
                                        <div class="w-12 h-12 rounded-2xl bg-[#FAF8F2] border border-[#EAE5D9] flex items-center justify-center text-[#A8A196]">
                                            <i data-lucide="inbox" class="w-6 h-6 stroke-[1.5]"></i>
                                        </div>
                                        <div class="font-heading font-bold text-sm text-[#4A3B32]">ยังไม่พบประวัติการเข้าร่วมโครงการปฏิบัติธรรม</div>
                                        <div class="text-xs text-[#8C8272]">เมื่อนิสิตลงทะเบียนและผ่านการเข้าร่วมโครงการแล้ว ประวัติจะปรากฏที่นี่</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </main>

    <!-- Bottom Footer Matching Sample Image -->
    <footer class="bg-white/80 border-t border-[#E7E2D4] py-4 text-center text-xs text-[#6B6357] w-full">
        <div class="max-w-7xl mx-auto px-4">
            มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย • ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน (VPSMCU)
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
