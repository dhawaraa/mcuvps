<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - เกิดข้อผิดพลาดของระบบ | VPSMCU มจร</title>
    
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
                <span class="inline-block w-2 h-2 rounded-full bg-[#C86D51]"></span>
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

                <div class="flex items-center space-x-3">
                    <a href="/" class="px-4 py-2 rounded-xl text-xs font-semibold text-[#4A3B32] hover:bg-[#EAE5D9]/50 transition flex items-center gap-1.5">
                        <i data-lucide="home" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>หน้าแรก</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Content 500 Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 w-full flex-grow flex items-center justify-center">
        <div class="max-w-xl w-full text-center">
            
            <div class="relative inline-block mb-6">
                <div class="w-24 h-24 mx-auto rounded-3xl bg-[#FAF8F2] border-2 border-[#D5CEBC] shadow-lg flex items-center justify-center text-[#C86D51]">
                    <i data-lucide="alert-triangle" class="w-12 h-12 text-[#C86D51]"></i>
                </div>
            </div>

            <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-[#2C3E2D] tracking-tight mb-3">
                เกิดข้อผิดพลาดของระบบชั่วคราว
            </h1>
            <p class="text-sm md:text-base text-[#6B6357] mb-8 leading-relaxed">
                ระบบกำลังดำเนินการตรวจสอบและแก้ไข กรุณาลองใหม่อีกครั้งในภายหลัง หรือกลับไปยังหน้าหลัก
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="/" class="px-6 py-3 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white rounded-2xl text-sm font-semibold transition shadow-md flex items-center gap-2">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    <span>กลับสู่หน้าหลัก</span>
                </a>
                <button type="button" onclick="location.reload()" class="px-5 py-3 bg-white hover:bg-[#FAF8F2] text-[#4A3B32] border border-[#D5CEBC] rounded-2xl text-sm font-semibold transition flex items-center gap-2">
                    <i data-lucide="rotate-cw" class="w-4 h-4"></i>
                    <span>โหลดใหม่อีกครั้ง</span>
                </button>
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
