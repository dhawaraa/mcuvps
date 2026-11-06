<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - ไม่พบหน้าที่ต้องการ | VPSMCU มจร</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
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
                            sand: '#F7F4EA',
                            stone: '#EAE5D9',
                            clay: '#C86D51',
                            clayDark: '#A85238',
                            forest: '#2C3E2D',
                            olive: '#5A6B47',
                            oliveLight: '#7B8D65',
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
            background-color: #F7F5EE;
            color: #2D2A26;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }
    </style>
    <link rel="icon" type="image/png" href="/images/mcu-logo.png">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between selection:bg-[#5A6B47] selection:text-white">

    <!-- Top Bar -->
    <div class="bg-[#243325] text-[#D5CEBC] text-xs py-2 px-4 border-b border-[#1E2B1F]">
        <div class="max-w-7xl mx-auto flex justify-between items-center font-medium">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-[#7B8D65]"></span>
                <span class="text-[#EAE5D9]">ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน (VPSMCU)</span>
            </div>
            <div class="flex items-center space-x-4 text-[#D8D2C2] text-[11px]">
                <span class="flex items-center gap-1.5"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#A3B88C]"></i> มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย (มจร)</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 bg-[#FAF8F2]/95 backdrop-blur-xl border-b border-[#E3DEC9] shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3.5">
                    <a href="/" class="shrink-0 flex items-center">
                        <img src="/images/mcu-logo.png" alt="MCU Logo" class="w-12 h-12 object-contain drop-shadow-sm hover:scale-105 transition">
                    </a>
                    <div>
                        <a href="/" class="font-heading font-extrabold text-xl text-[#2C3E2D] tracking-tight leading-tight flex items-center gap-2">
                            VPSMCU
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#EAE5D9] text-[#4A3B32] border border-[#D5CEBC]">มจร</span>
                        </a>
                        <p class="text-xs text-[#6B6357] font-medium">ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน</p>
                    </div>
                </div>

                <!-- Nav Links -->
                <div class="flex items-center space-x-3">
                    <a href="/" class="px-4 py-2 rounded-xl text-xs font-semibold text-[#4A3B32] hover:bg-[#EAE5D9]/50 transition flex items-center gap-1.5">
                        <i data-lucide="home" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>หน้าแรก</span>
                    </a>
                    <a href="/login.php" class="px-4 py-2 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white rounded-xl text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                        <span>เข้าสู่ระบบ</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Content 404 Hero Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 w-full flex-grow flex items-center justify-center">
        <div class="max-w-2xl w-full text-center">
            
            <!-- Badge & Visual Illustration -->
            <div class="relative inline-block mb-6">
                <div class="w-28 h-28 mx-auto rounded-3xl bg-[#FAF8F2] border-2 border-[#D5CEBC] shadow-lg flex items-center justify-center text-[#C86D51] relative">
                    <i data-lucide="compass" class="w-14 h-14 animate-spin-slow text-[#5A6B47]"></i>
                    <span class="absolute -top-3 -right-3 px-3 py-1 bg-[#C86D51] text-white font-mono font-bold text-xs rounded-full shadow-md">
                        404
                    </span>
                </div>
            </div>

            <!-- Heading & Notice -->
            <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-[#2C3E2D] tracking-tight mb-3">
                ขออภัย ไม่พบหน้าที่ท่านกำลังค้นหา
            </h1>
            <p class="text-sm md:text-base text-[#6B6357] mb-8 leading-relaxed max-w-lg mx-auto">
                หน้าที่ท่านต้องการเข้าถึงอาจถูกย้าย เปลี่ยนชื่อ หรือไม่มีอยู่ในระบบสารสนเทศวิปัสสนากรรมฐาน (มจร)
            </p>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-10">
                <a href="/" class="px-6 py-3 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white rounded-2xl text-sm font-semibold transition shadow-md flex items-center gap-2">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    <span>กลับสู่หน้าหลัก (Portal)</span>
                </a>
                <button type="button" onclick="history.back()" class="px-5 py-3 bg-white hover:bg-[#FAF8F2] text-[#4A3B32] border border-[#D5CEBC] rounded-2xl text-sm font-semibold transition flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>ย้อนกลับหน้าก่อนหน้า</span>
                </button>
            </div>

            <!-- Helpful Navigation Grid -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#EAE5D9] rounded-3xl p-6 shadow-sm text-left">
                <h3 class="font-heading font-bold text-sm text-[#2C3E2D] mb-4 flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>จุดเชื่อมต่อบริการที่ท่านอาจต้องการใช้งาน</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <a href="/#calendar" class="p-3 rounded-2xl bg-[#FAF8F2] hover:bg-[#EAE5D9]/60 border border-[#EAE5D9] transition flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-[#2C3E2D]">ปฏิทินและกำหนดการปฏิบัติธรรม</div>
                            <div class="text-[11px] text-[#7B8D65]">ดูรอบการฝึกอบรมทั้งหมด</div>
                        </div>
                    </a>

                    <a href="/grad_progress.php" class="p-3 rounded-2xl bg-[#FAF8F2] hover:bg-[#EAE5D9]/60 border border-[#EAE5D9] transition flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                            <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-[#2C3E2D]">ตรวจสอบผลการสะสมวัน</div>
                            <div class="text-[11px] text-[#7B8D65]">สำหรับนิสิตระดับบัณฑิตศึกษา</div>
                        </div>
                    </a>

                    <a href="/grad.php" class="p-3 rounded-2xl bg-[#FAF8F2] hover:bg-[#EAE5D9]/60 border border-[#EAE5D9] transition flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-xl bg-[#C86D51]/15 text-[#C86D51] flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                            <i data-lucide="file-check-2" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-[#2C3E2D]">ยื่นคำร้องขอหนังสือรับรอง (e-Doc)</div>
                            <div class="text-[11px] text-[#7B8D65]">ส่งเอกสารเมื่อสะสมครบวัน</div>
                        </div>
                    </a>

                    <a href="/login.php" class="p-3 rounded-2xl bg-[#FAF8F2] hover:bg-[#EAE5D9]/60 border border-[#EAE5D9] transition flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-xl bg-[#2C3E2D]/15 text-[#2C3E2D] flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-[#2C3E2D]">ระบบสำหรับเจ้าหน้าที่และผู้บริหาร</div>
                            <div class="text-[11px] text-[#7B8D65]">เข้าสู่ระบบ Admin Console</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#243325] text-[#D5CEBC] py-6 border-t border-[#1E2B1F] text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:flex sm:justify-between sm:items-center">
            <p>&copy; {{ date('Y') }} สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย (มจร). สงวนลิขสิทธิ์ทั้งหมด</p>
            <p class="text-[11px] text-[#8C8275] mt-2 sm:mt-0">VPSMCU &bull; ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน 45 สังกัดทั่วประเทศ</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
