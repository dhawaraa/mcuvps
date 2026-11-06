<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบข้อมูลนิสิต - VPSMCU</title>
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
                            clayDark: '#A85238',
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
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between selection:bg-[#4D5E3C] selection:text-white">

    <!-- Top Navigation Header Matching Sample Image -->
    <header class="bg-[#FAF8F2]/90 border-b border-[#E7E2D4] w-full">
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
                    <a href="{{ route('student.login') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#4D5E3C] text-white shadow-xs transition flex items-center gap-1.5">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>ตรวจประวัติสะสมวัน</span>
                    </a>
                </div>

                <!-- Right Actions: Language Switcher & Back to Home -->
                <div class="flex items-center space-x-4">
                    @php
                        $currentLang = session('locale', 'th');
                    @endphp
                    <!-- Language Switcher Pill -->
                    @include('portal.partials.font_resizer')
                    <div class="flex items-center bg-[#E5DFD0] p-1 rounded-xl text-xs font-bold font-mono">
                        <a href="{{ route('lang.switch', 'th') }}" title="ภาษาไทย" class="px-2.5 py-1 rounded-lg transition {{ $currentLang === 'th' ? 'bg-[#4D5E3C] text-white shadow-xs' : 'text-[#605B50] hover:text-[#2C3E2D]' }}">
                            TH
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" title="English" class="px-2.5 py-1 rounded-lg transition {{ $currentLang === 'en' ? 'bg-[#4D5E3C] text-white shadow-xs' : 'text-[#605B50] hover:text-[#2C3E2D]' }}">
                            EN
                        </a>
                    </div>

                    <!-- Return Back to Home Link -->
                    <a href="{{ route('home') }}" class="text-xs sm:text-sm font-semibold text-[#4A3B32] hover:text-[#2C3E2D] flex items-center gap-1.5 transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>กลับหน้าหลัก</span>
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content Container with Centered Card -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full flex-grow flex items-center justify-center">
        <div class="w-full max-w-[440px]">
            
            <!-- Login Card (Pure White with Subtle Border & Shadow Matching Sample) -->
            <div class="bg-white rounded-3xl p-8 sm:p-9 border border-[#EAE5D9] shadow-sm">
                
                <!-- Circular Icon Header -->
                <div class="flex justify-center mb-5">
                    <div class="w-16 h-16 rounded-full bg-[#EBF1E6] text-[#4D5E3C] flex items-center justify-center">
                        <i data-lucide="graduation-cap" class="w-8 h-8 stroke-[1.75]"></i>
                    </div>
                </div>

                <!-- Title Heading -->
                <div class="text-center mb-7">
                    <h1 class="text-2xl font-heading font-bold text-[#1E2519] tracking-tight">เข้าสู่ระบบข้อมูลนิสิต</h1>
                </div>

                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="mb-5 p-3 rounded-xl bg-[#EBF1E6] border border-[#CDE0C3] text-[#2C3E2D] text-xs flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#4D5E3C] shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="/student/login.php" class="space-y-4">
                    @csrf

                    <!-- รหัสนิสิต -->
                    <div>
                        <label for="student_code" class="block text-xs font-bold text-[#2D2A26] mb-1.5">
                            รหัสนิสิต
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#7D7667]">
                                <i data-lucide="user" class="w-4 h-4 stroke-[1.75]"></i>
                            </span>
                            <input type="text" id="student_code" name="student_code" value="{{ old('student_code') }}" required autofocus
                                placeholder="กรอกรหัสนิสิต (เช่น 6601201001)"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#DCD6C7] bg-white text-xs sm:text-sm text-[#2D2A26] focus:outline-none focus:ring-1 focus:ring-[#4D5E3C] focus:border-[#4D5E3C] placeholder:text-[#9C9484] transition">
                        </div>
                    </div>

                    <!-- รหัสผ่าน -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-[#2D2A26] mb-1.5">
                            รหัสผ่าน
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#7D7667]">
                                <i data-lucide="lock" class="w-4 h-4 stroke-[1.75]"></i>
                            </span>
                            <input type="password" id="password" name="password" required
                                placeholder="กรอกรหัสผ่าน"
                                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-[#DCD6C7] bg-white text-xs sm:text-sm text-[#2D2A26] focus:outline-none focus:ring-1 focus:ring-[#4D5E3C] focus:border-[#4D5E3C] placeholder:text-[#9C9484] transition">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#7D7667] hover:text-[#2D2A26]">
                                <i id="eye-icon" data-lucide="eye" class="w-4 h-4 stroke-[1.75]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Sub Links: แก้ไขรหัสผ่าน | ลืมรหัสผ่าน -->
                    <div class="flex justify-end items-center space-x-2 pt-0.5 text-xs">
                        <a href="{{ route('student.profile') }}" class="text-[#4D5E3C] hover:underline font-medium">แก้ไขรหัสผ่าน</a>
                        <span class="text-[#D0CABA]">|</span>
                        <a href="javascript:void(0)" onclick="alert('หากลืมรหัสผ่าน กรุณาติดต่อส่วนงานต้นสังกัด หรือใช้วันเดือนปีเกิดในการเข้าสู่ระบบครั้งแรก')" class="text-[#4D5E3C] hover:underline font-medium">ลืมรหัสผ่าน</a>
                    </div>

                    <!-- Submit Button Matching Sample Color (#415332 / Olive Dark) -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 sm:py-3 rounded-xl bg-[#415332] hover:bg-[#344327] text-white font-medium text-sm transition shadow-xs flex items-center justify-center gap-2">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            <span>เข้าสู่ระบบ</span>
                        </button>
                    </div>

                </form>

                <!-- Divider -->
                <div class="my-5 border-t border-[#EAE5D9]"></div>

                <!-- Guidance Info Box Matching Sample Image -->
                <div class="p-3.5 rounded-xl bg-[#F8F6F0] border border-[#EBE6D8] flex items-center gap-3">
                    <div class="text-[#4D5E3C] shrink-0">
                        <i data-lucide="info" class="w-5 h-5 stroke-[1.75]"></i>
                    </div>
                    <div class="text-[11px] text-[#4A4438] leading-tight">
                        <div class="font-bold text-[#2D2A26] mb-0.5">คำแนะนำการเข้าสู่ระบบ</div>
                        <div class="text-[#6B6357]">ใช้รหัสนิสิตกับรหัสผ่านเดียวกันในการเข้าสู่ระบบ</div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- Bottom Footer Matching Sample Image -->
    <footer class="bg-white/80 border-t border-[#E7E2D4] py-4 text-center text-xs text-[#6B6357] w-full">
        <div class="max-w-7xl mx-auto px-4">
            มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย • ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน (VPSMCU)
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
