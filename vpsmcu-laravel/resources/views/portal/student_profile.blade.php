<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลนิสิต ({{ $student->student_code }} - {{ $student->full_name }}) - VPSMCU</title>
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

    <!-- Top Navigation Header (Matching Sample Data/Student-DAta.png) -->
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

                <!-- Right Actions: User Badge, Language Switcher & Logout -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#EFECE3] border border-[#DFDACB] text-xs">
                        <i data-lucide="user-check" class="w-3.5 h-3.5 text-[#4D5E3C]"></i>
                        <span class="font-mono font-bold text-[#2D2A26]">{{ $student->student_code }}</span>
                        <span class="text-[#7A7366]">|</span>
                        <span class="font-medium text-[#4A3B32] truncate max-w-[130px]">{{ $student->full_name }}</span>
                    </div>

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

                    <a href="{{ route('student.logout') }}" title="ออกจากระบบ" class="text-xs font-semibold text-[#C86D51] hover:text-[#A85238] flex items-center gap-1 transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">ออกจากระบบ</span>
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content Container (Standard 7xl) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- 1. Top Hero Banner (ข้อมูลนิสิต - ตรวจสอบและแก้ไขข้อมูลส่วนตัวนิสิต) -->
        <div class="hero-banner-card rounded-3xl p-8 sm:p-10 mb-8 shadow-sm relative overflow-hidden text-white flex flex-col justify-center border border-[#205C29]/40">
            <div class="max-w-2xl relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-xs text-xs font-mono font-semibold mb-3 border border-white/30 text-white">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#A5D6A7]"></i>
                    <span>บัญชีผู้ใช้งาน: {{ $student->student_code }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight mb-2 text-white">
                    {{ $student->full_name }}
                </h1>
                <p class="text-sm sm:text-base text-white/95 font-medium tracking-wide">
                    ตรวจสอบและแก้ไขข้อมูลส่วนตัวนิสิต • {{ $student->organizationUnit?->name_th ?? 'มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย' }}
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#EBF1E6] border border-[#CDE0C3] text-[#2C3E2D] text-xs flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-[#4D5E3C] shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(empty($student->phone) || empty($student->province) || empty($student->citizen_id))
            <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-[#FAF5EB] border border-[#EADBBE] text-[#6E4F18] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#C86D51]/10 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#C86D51]"></i>
                    </div>
                    <div>
                        <div class="font-heading font-bold text-sm text-[#3E3228]">ข้อมูลส่วนตัวของท่านยังไม่ครบถ้วน</div>
                        <div class="text-xs text-[#7D7266]">ระบบยังไม่มีข้อมูลติดต่อและที่อยู่ปัจจุบัน กรุณากดปุ่มเพื่ออัปเดตข้อมูลจริงให้เป็นปัจจุบัน</div>
                    </div>
                </div>
                <button type="button" onclick="openEditProfileModal()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-[#C86D51] hover:bg-[#A85238] text-white transition shrink-0 shadow-xs">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                    <span>กรอกข้อมูลตอนนี้</span>
                </button>
            </div>
        @endif

        <!-- 2. ข้อมูลโปรไฟล์นิสิต Card (Card 1) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#EAE5D9] shadow-xs mb-8">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-4 mb-6 border-b border-[#F0ECE1] gap-4">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="user" class="w-5 h-5 text-[#2D2A26] stroke-[2]"></i>
                    <h2 class="font-heading font-bold text-lg text-[#2D2A26]">ข้อมูลโปรไฟล์นิสิต</h2>
                </div>
                <button type="button" onclick="openEditProfileModal()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-[#415332] hover:bg-[#344327] text-white transition shadow-xs self-start sm:self-auto">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                    <span>แก้ไขข้อมูล</span>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Avatar Column (3 cols) -->
                <div class="lg:col-span-3 flex flex-col items-center text-center">
                    <div class="w-36 h-36 rounded-full bg-[#EAE5D9] border-4 border-white shadow-md overflow-hidden flex items-center justify-center mb-3.5">
                        @if($student->avatar_url || $student->avatar_path)
                            <img src="{{ $student->avatar_url ?? asset('storage/' . $student->avatar_path) }}" alt="{{ $student->full_name }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/heroimage.png') }}" alt="{{ $student->full_name }}" class="w-full h-full object-cover grayscale opacity-70">
                        @endif
                    </div>
                    <button type="button" onclick="openEditProfileModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#2D3826] hover:bg-[#1E2519] text-white transition shadow-xs">
                        <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                        <span>แก้ไขรูปโปรไฟล์</span>
                    </button>
                </div>

                <!-- Profile Info Fields (9 cols) -->
                <div class="lg:col-span-9">
                    <div class="flex flex-wrap items-center gap-2 mb-2.5">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EAE5D9] text-[#4A3B32] font-mono text-xs font-bold border border-[#D5CEBC]">
                            <i data-lucide="id-card" class="w-3.5 h-3.5 text-[#7D7667]"></i>
                            <span>รหัสนิสิต {{ $student->student_code }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#EBF1E6] text-[#2C3E2D] text-xs font-semibold border border-[#CDE0C3]">
                            <i data-lucide="check" class="w-3 h-3 text-[#4D5E3C]"></i>
                            <span>เข้าสู่ระบบแล้ว</span>
                        </span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-heading font-extrabold text-[#1E2519] mb-5 tracking-tight">
                        {{ $student->full_name }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3.5 gap-x-8 text-sm">
                        <!-- ฝั่งซ้าย -->
                        <div class="space-y-3">
                            <div class="flex items-baseline">
                                <span class="text-[#7D7667] w-28 shrink-0">รหัสนิสิต</span>
                                <span class="font-heading font-bold text-[#1E2519] text-lg font-mono">{{ $student->student_code }}</span>
                            </div>
                            <div class="flex items-baseline">
                                <span class="text-[#7D7667] w-28 shrink-0">ระดับการศึกษา</span>
                                <span class="font-medium text-[#2D2A26]">
                                    @if($student->degree_level === 'BACHELOR') ปริญญาตรี
                                    @elseif($student->degree_level === 'MASTER') ปริญญาโท
                                    @else ปริญญาเอก
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-baseline">
                                <span class="text-[#7D7667] w-28 shrink-0">คณะ</span>
                                <span class="font-medium text-[#2D2A26]">{{ $student->faculty ?: '-' }}</span>
                            </div>
                            <div class="flex items-baseline">
                                <span class="text-[#7D7667] w-28 shrink-0">สาขาวิชา</span>
                                <span class="font-medium text-[#2D2A26]">{{ $student->major ?: '-' }}</span>
                            </div>
                        </div>

                        <!-- ฝั่งขวา: สังกัด/หน่วยจัดการศึกษา -->
                        <div class="border-t md:border-t-0 md:border-l border-[#F0ECE1] pt-3 md:pt-0 md:pl-8 space-y-2">
                            <div class="text-[#7D7667] text-xs">สังกัด/หน่วยจัดการศึกษา</div>
                            <div class="font-medium text-[#2D2A26] text-sm leading-relaxed">
                                มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย<br>
                                <span class="text-[#1E2519] font-semibold">{{ $student->organizationUnit?->name_th ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- 3. ข้อมูลส่วนตัว & ที่อยู่ปัจจุบัน Cards (Side-by-side matching layout) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- การ์ด ข้อมูลส่วนตัว -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#EAE5D9] shadow-xs">
                <div class="flex items-center gap-2.5 pb-4 mb-5 border-b border-[#F0ECE1]">
                    <i data-lucide="user-check" class="w-5 h-5 text-[#2D2A26] stroke-[2]"></i>
                    <h2 class="font-heading font-bold text-lg text-[#2D2A26]">ข้อมูลส่วนตัว</h2>
                </div>

                <div class="space-y-3.5 text-sm">
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">เลขบัตรประชาชน</span>
                        <span class="{{ !empty($student->citizen_id) ? 'font-mono text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->citizen_id ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">วัน/เดือน/ปี เกิด</span>
                        <span class="{{ !empty($student->birth_date) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ !empty($student->birth_date) ? $student->thai_birth_date : 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">อายุ</span>
                        <span class="{{ !empty($student->age) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ !empty($student->age) ? $student->age . ' ปี' : 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">พรรษา</span>
                        <span class="{{ !is_null($student->vassa) && $student->vassa !== '' ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ (!is_null($student->vassa) && $student->vassa !== '') ? $student->vassa . ' พรรษา' : 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">สัญชาติ</span>
                        <span class="text-[#2D2A26] font-medium">{{ $student->nationality ?? 'ไทย' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">ศาสนา</span>
                        <span class="text-[#2D2A26] font-medium">{{ $student->religion ?? 'พุทธ' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">หมู่โลหิต</span>
                        <span class="{{ !empty($student->blood_group) ? 'text-[#2D2A26] font-medium font-mono' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->blood_group ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- การ์ด ที่อยู่ปัจจุบัน -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#EAE5D9] shadow-xs">
                <div class="flex items-center gap-2.5 pb-4 mb-5 border-b border-[#F0ECE1]">
                    <i data-lucide="map-pin" class="w-5 h-5 text-[#2D2A26] stroke-[2]"></i>
                    <h2 class="font-heading font-bold text-lg text-[#2D2A26]">ที่อยู่ปัจจุบัน</h2>
                </div>

                <div class="space-y-3.5 text-sm">
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">บ้านเลขที่</span>
                        <span class="{{ !empty($student->house_no) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->house_no ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">หมู่</span>
                        <span class="{{ !empty($student->moo) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ !empty($student->moo) ? 'หมู่ ' . $student->moo : 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">บ้าน/วัด</span>
                        <span class="{{ !empty($student->temple_village) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->temple_village ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">ตำบล</span>
                        <span class="{{ !empty($student->subdistrict) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->subdistrict ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">อำเภอ</span>
                        <span class="{{ !empty($student->district) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->district ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">จังหวัด</span>
                        <span class="{{ !empty($student->province) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->province ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-[#7D7667]">รหัสไปรษณีย์</span>
                        <span class="{{ !empty($student->postal_code) ? 'text-[#2D2A26] font-medium font-mono' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->postal_code ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- 4. ข้อมูลติดต่อ Card (Card 4 Full Width) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#EAE5D9] shadow-xs mb-8">
            <div class="flex items-center gap-2.5 pb-4 mb-5 border-b border-[#F0ECE1]">
                <i data-lucide="phone-call" class="w-5 h-5 text-[#2D2A26] stroke-[2]"></i>
                <h2 class="font-heading font-bold text-lg text-[#2D2A26]">ข้อมูลติดต่อ</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12 text-sm">
                <div class="space-y-3.5">
                    <div class="flex justify-between items-center">
                        <span class="text-[#7D7667]">เบอร์โทรศัพท์</span>
                        <span class="{{ !empty($student->phone) ? 'font-mono text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->phone ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[#7D7667]">อีเมล</span>
                        <span class="{{ !empty($student->email) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->email ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-3.5 border-t md:border-t-0 md:border-l border-[#F0ECE1] pt-3 md:pt-0 md:pl-12">
                    <div class="flex justify-between items-center">
                        <span class="text-[#7D7667]">Line ID (ถ้ามี)</span>
                        <span class="{{ !empty($student->line_id) ? 'text-[#2D2A26] font-medium font-mono' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->line_id ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[#7D7667]">Facebook (ถ้ามี)</span>
                        <span class="{{ !empty($student->facebook) ? 'text-[#2D2A26] font-medium' : 'text-[#A8A196] italic text-xs' }}">
                            {{ $student->facebook ?: 'ยังไม่ระบุ' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Modal: ฟอร์มแก้ไขข้อมูลส่วนตัวนิสิต -->
    <div id="edit-profile-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-5 border-b border-[#EAE5D9]">
                <h3 class="font-heading font-bold text-xl text-[#2D2A26] flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5 text-[#4D5E3C]"></i>
                    <span>แก้ไขข้อมูลส่วนตัวและที่อยู่</span>
                </h3>
                <button type="button" onclick="closeEditProfileModal()" class="text-stone-400 hover:text-stone-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="/student/profile/update.php" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- รูปโปรไฟล์ -->
                <div>
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">อัปโหลดรูปโปรไฟล์ใหม่</label>
                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg" class="text-xs text-[#6B6357] file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#4D5E3C] file:text-white hover:file:bg-[#344327] file:cursor-pointer">
                </div>

                <!-- ข้อมูลส่วนตัว -->
                <div class="pt-2 border-t border-[#EAE5D9]">
                    <div class="text-xs font-bold text-[#4D5E3C] uppercase mb-3">ข้อมูลส่วนตัว</div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">เลขบัตรประชาชน (13 หลัก)</label>
                            <input type="text" name="citizen_id" value="{{ old('citizen_id', $student->citizen_id) }}" placeholder="เช่น 1234567890123" maxlength="30" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">วัน/เดือน/ปีเกิด</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">อายุ</label>
                            <input type="number" name="age" value="{{ old('age', $student->age) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">พรรษา</label>
                            <input type="number" name="vassa" value="{{ old('vassa', $student->vassa) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">สัญชาติ</label>
                            <input type="text" name="nationality" value="{{ old('nationality', $student->nationality ?? 'ไทย') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">ศาสนา</label>
                            <input type="text" name="religion" value="{{ old('religion', $student->religion ?? 'พุทธ') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">หมู่โลหิต</label>
                            <input type="text" name="blood_group" value="{{ old('blood_group', $student->blood_group) }}" placeholder="เช่น A, B, O, AB" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                    </div>
                </div>

                <!-- ที่อยู่ปัจจุบัน -->
                <div class="pt-2 border-t border-[#EAE5D9]">
                    <div class="text-xs font-bold text-[#4D5E3C] uppercase mb-3">ที่อยู่ปัจจุบัน / วัดที่จำพรรษา</div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">บ้านเลขที่</label>
                            <input type="text" name="house_no" value="{{ old('house_no', $student->house_no) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">หมู่</label>
                            <input type="text" name="moo" value="{{ old('moo', $student->moo) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">บ้าน/วัด</label>
                            <input type="text" name="temple_village" value="{{ old('temple_village', $student->temple_village) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">ตำบล</label>
                            <input type="text" name="subdistrict" value="{{ old('subdistrict', $student->subdistrict) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">อำเภอ</label>
                            <input type="text" name="district" value="{{ old('district', $student->district) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">จังหวัด</label>
                            <input type="text" name="province" value="{{ old('province', $student->province) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">รหัสไปรษณีย์</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $student->postal_code) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                    </div>
                </div>

                <!-- ข้อมูลติดต่อ -->
                <div class="pt-2 border-t border-[#EAE5D9]">
                    <div class="text-xs font-bold text-[#4D5E3C] uppercase mb-3">ข้อมูลติดต่อ</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">อีเมล</label>
                            <input type="email" name="email" value="{{ old('email', $student->email) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">Line ID (ถ้ามี)</label>
                            <input type="text" name="line_id" value="{{ old('line_id', $student->line_id) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#6B6357] mb-1">Facebook (ถ้ามี)</label>
                            <input type="text" name="facebook" value="{{ old('facebook', $student->facebook) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-xl text-xs bg-[#FAF8F2]">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2.5 pt-4 border-t border-[#EAE5D9]">
                    <button type="button" onclick="closeEditProfileModal()" class="px-4 py-2 rounded-xl text-xs bg-[#FAF8F2] text-[#6B6357] border border-[#D5CEBC] hover:bg-[#EAE5D9]">ยกเลิก</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs bg-[#415332] text-white hover:bg-[#344327] font-semibold">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bottom Footer Matching Sample Image -->
    <footer class="bg-white/80 border-t border-[#E7E2D4] py-4 text-center text-xs text-[#6B6357] w-full">
        <div class="max-w-7xl mx-auto px-4">
            มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย • ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน (VPSMCU)
        </div>
    </footer>

    <script>
        function openEditProfileModal() {
            document.getElementById('edit-profile-modal').classList.remove('hidden');
        }
        function closeEditProfileModal() {
            document.getElementById('edit-profile-modal').classList.add('hidden');
        }
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
