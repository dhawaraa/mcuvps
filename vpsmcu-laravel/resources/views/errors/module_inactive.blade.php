<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $info['th'] ?? 'กำลังอยู่ในช่วงพัฒนา' }} | VPSMCU มจร</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>
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
        .font-heading { font-family: 'Prompt', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-[#5A6B47] selection:text-white">

    <!-- Top Navigation Bar (Organic Minimalist) -->
    <nav class="bg-[#2C3E2D] text-white shadow-md border-b border-[#243325]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="{{ route('home') }}" class="flex items-center space-x-3.5 group">
                    <div class="w-11 h-11 rounded-xl bg-white/10 p-1.5 flex items-center justify-center shadow-inner ring-1 ring-white/15 transition-transform group-hover:scale-105">
                        <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="font-heading font-bold text-lg md:text-xl text-white tracking-tight leading-tight">VPSMCU</div>
                        <div class="text-[11px] text-[#A3B88C] font-mono tracking-wider">ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน มจร</div>
                    </div>
                </a>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-white/10 hover:bg-white/20 text-[#EAE5D9] transition-all">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2 text-[#A3B88C]"></i>
                        <span>กลับสู่หน้าหลัก</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 w-full flex-grow flex items-center justify-center">
        <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl border border-[#D5CEBC] p-8 md:p-12 text-center relative overflow-hidden">
            
            <!-- Top Organic Accent Bar -->
            <div class="absolute top-0 left-0 right-0 h-2.5 bg-gradient-to-r from-[#5A6B47] via-[#C86D51] to-[#7B8D65]"></div>
            
            <!-- Icon Badge -->
            <div class="mx-auto w-24 h-24 rounded-3xl bg-[#F7F5EE] border-2 border-[#EAE5D9] flex items-center justify-center shadow-inner mb-6 relative">
                <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-[#C86D51] text-white flex items-center justify-center shadow-md">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <i data-lucide="{{ $info['icon'] ?? 'wrench' }}" class="w-12 h-12 text-[#5A6B47]"></i>
            </div>

            <!-- Status Pill -->
            <div class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-[#EAE5D9] text-[#4A3B32] mb-5 border border-[#D5CEBC]">
                <span class="w-2 h-2 rounded-full bg-[#C86D51] mr-2 animate-pulse"></span>
                <span>อยู่ระหว่างการพัฒนาและเตรียมความพร้อม (Under Development)</span>
            </div>

            <!-- Title & Module Name -->
            <h1 class="text-2xl md:text-3xl font-heading font-bold text-[#2C3E2D] mb-3">
                {{ $info['th'] ?? 'โมดูลระบบนี้' }}
            </h1>
            <p class="text-sm font-mono text-[#7B8D65] font-semibold mb-6">
                {{ $info['en'] ?? 'This Module is Coming Soon' }}
            </p>

            <!-- Description Card -->
            <div class="bg-[#FAF8F2] rounded-2xl p-5 border border-[#EAE5D9] text-[#4A3B32] text-sm md:text-base leading-relaxed mb-8 text-left space-y-2.5">
                <div class="flex items-start space-x-3">
                    <i data-lucide="info" class="w-5 h-5 text-[#5A6B47] shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-[#2C3E2D]">ขออภัยในความไม่สะดวก</p>
                        <p class="text-xs md:text-sm text-[#5C5549] mt-1">
                            ส่วนงานนี้ยังไม่เปิดให้บริการแก่สาธารณะในขณะนี้ เนื่องจากระบบกำลังอยู่ระหว่างการปรับปรุง พัฒนาฟังก์ชันเพิ่มเติม และทดสอบความพร้อมตามระเบียบมหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย (มจร)
                        </p>
                    </div>
                </div>
                <div class="pt-2 border-t border-[#EAE5D9] flex items-center justify-between text-xs text-[#8C9B80]">
                    <span>สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย</span>
                    <span class="font-mono">Status: Module Inactive (503)</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-white bg-[#5A6B47] hover:bg-[#465337] transition shadow-md hover:shadow-lg gap-2">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    <span>กลับสู่หน้าแรก (VPSMCU Portal)</span>
                </a>
                <a href="{{ route('contact') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-[#4A3B32] bg-[#EAE5D9] hover:bg-[#D5CEBC] transition border border-[#D5CEBC] gap-2">
                    <i data-lucide="phone-call" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>ติดต่อสอบถามข้อมูล</span>
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#243325] text-[#D5CEBC] py-6 border-t border-[#1B271C] text-center text-xs">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย (มจร)</p>
            <p class="text-[11px] text-[#8C9B80] mt-1 font-mono">Vipassana Information System (VPSMCU)</p>
        </div>
    </footer>

    <!-- Lucide Icon Renderer -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
