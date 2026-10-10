<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสถานะโมดูลระบบ (Feature Flags) | VPSMCU Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
                            sand: '#F7F5EE',
                            stone: '#EAE5D9',
                            clay: '#C86D51',
                            clayDark: '#A85238',
                            forest: '#2C3E2D',
                            olive: '#5A6B47',
                            bark: '#4A3B32',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Sarabun', sans-serif; 
            background: #F4F1EA; 
            color: #2D2A26;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }

        .earth-admin-card {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(220, 215, 201, 0.85);
            box-shadow: 0 10px 25px -10px rgba(74, 59, 50, 0.05);
            border-radius: 1rem;
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Earth Tones Sidebar (Deep Forest & Olive) -->
    @include('admin.layouts.sidebar')

    <!-- Main Workspace (Layout Standard: flex-grow p-6 md:p-10 overflow-y-auto) -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <span class="text-xs font-bold text-[#5A6B47] uppercase tracking-wider font-mono flex items-center gap-1.5">
                    <i data-lucide="toggle-left" class="w-4 h-4"></i> System Settings & Feature Flags
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-[#2C3E2D] mt-1">จัดการโมดูลระบบ (เปิด/ปิดบริการหน้าบ้าน)</h1>
                <p class="text-xs text-[#6B6357] mt-1">
                    ควบคุมการเปิดหรือปิดการเข้าถึงบริการหน้าบ้านสำหรับโมดูลต่าง ๆ หากปิดระบบ ผู้เข้าชมจะพบหน้าแจ้งเตือน "กำลังอยู่ในช่วงพัฒนา"
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="text-xs bg-white border border-[#D5CEBC] hover:border-[#5A6B47] text-[#2C3E2D] px-3.5 py-2 rounded-xl font-semibold shadow-2xs flex items-center gap-2 transition">
                    <i data-lucide="external-link" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>ตรวจสอบหน้าพอร์ทัลหลัก</span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-[#5A6B47]/10 border-l-4 border-[#5A6B47] p-4 rounded-r-xl mb-6 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2.5 text-xs text-[#2C3E2D] font-medium">
                    <i data-lucide="check-circle" class="w-5 h-5 text-[#5A6B47] shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Notice Banner -->
        <div class="bg-[#FAF8F2] border border-[#D5CEBC] rounded-2xl p-5 mb-8 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center shrink-0">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <div class="text-xs md:text-sm text-[#4A3B32] space-y-1">
                <p class="font-bold text-[#2C3E2D]">ข้อแนะนำการใช้งานระบบเปิด/ปิดโมดูล</p>
                <p class="text-[#6B6357] leading-relaxed">
                    การปิดการใช้งานโมดูลจะมีผลเฉพาะ <span class="font-semibold text-[#A85238]">หน้าบ้านสำหรับประชาชนและนิสิตทั่วไป (Public Portal)</span> เท่านั้น สำหรับระบบหลังบ้าน (Admin Console) เจ้าหน้าที่และผู้ดูแลระบบยังคงสามารถเข้าตรวจสอบและจัดการข้อมูลได้ตามปกติ 100%
                </p>
            </div>
        </div>

        <!-- Module Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $moduleMeta = [
                    'module_public_enabled' => [
                        'name' => 'คอร์สปฏิบัติธรรมสำหรับประชาชน',
                        'sub' => 'Public Meditation Courses',
                        'icon' => 'heart-handshake',
                        'desc' => 'ระบบรับสมัครและตรวจสอบสถานะการสมัครเข้าร่วมปฏิบัติธรรมสำหรับประชาชนทั่วไป',
                        'routes' => ['/public_register.php', '/public_check.php'],
                        'admin_route' => 'admin.public.events',
                        'test_route' => 'public.register'
                    ],
                    'module_ug_enabled' => [
                        'name' => 'ปฏิบัติธรรม ป.ตรี (40 วัน)',
                        'sub' => 'Undergraduate 40-Day Module',
                        'icon' => 'graduation-cap',
                        'desc' => 'ระบบลงทะเบียน ตรวจสอบผล และออกหนังสือรับรองปฏิบัติธรรมสำหรับนิสิตระดับปริญญาตรี',
                        'routes' => ['/ug_register.php', '/ug_check.php'],
                        'admin_route' => 'admin.ug.batches',
                        'test_route' => 'ug.register'
                    ],
                    'module_grad_enabled' => [
                        'name' => 'หนังสือรับรอง บัณฑิตศึกษา (30/45 วัน)',
                        'sub' => 'Graduate Studies Certificate',
                        'icon' => 'scroll-text',
                        'desc' => 'ระบบตรวจสอบประวัติสะสมวัน ยื่นคำร้องขอหนังสือรับรอง และดาวน์โหลด e-Certificate บัณฑิตศึกษา',
                        'routes' => ['/grad_progress.php', '/grad.php'],
                        'admin_route' => 'admin.grad.approvals',
                        'test_route' => 'grad.progress'
                    ],
                    'module_donation_enabled' => [
                        'name' => 'ระบบรับบริจาคเพื่อการปฏิบัติธรรม',
                        'sub' => 'Donation & Meditation Fund',
                        'icon' => 'gift',
                        'desc' => 'ระบบร่วมทำบุญสนับสนุนโครงการ สแกน QR Code บริจาคเงิน และส่งหลักฐานการโอนเงิน',
                        'routes' => ['/donation.php'],
                        'admin_route' => 'admin.donations.index',
                        'test_route' => 'donation'
                    ],
                    'module_student_portal_enabled' => [
                        'name' => 'พอร์ทัลนิสิต มจร',
                        'sub' => 'Student Portal & Dashboard',
                        'icon' => 'user-check',
                        'desc' => 'ระบบเข้าสู่ระบบสำหรับนิสิต มจร เพื่อตรวจสอบประวัติปฏิบัติธรรม ข้อมูลส่วนตัว และเปลี่ยนรหัสผ่าน',
                        'routes' => ['/student/login.php', '/student/dashboard.php'],
                        'admin_route' => 'admin.students.index',
                        'test_route' => 'student.login'
                    ],
                    'module_news_enabled' => [
                        'name' => 'ข่าวสารและประชาสัมพันธ์',
                        'sub' => 'News & Announcements',
                        'icon' => 'newspaper',
                        'desc' => 'หน้าศูนย์ข่าวสาร บทความ กิจกรรม และประกาศอย่างเป็นทางการของสถาบันวิปัสสนาธุระ',
                        'routes' => ['/news.php', '/news/{id}'],
                        'admin_route' => 'admin.news.index',
                        'test_route' => 'news.index'
                    ],
                ];
            @endphp

            @foreach ($modules as $m)
                @php
                    $meta = $moduleMeta[$m->setting_key] ?? [
                        'name' => $m->label ?? $m->setting_key,
                        'sub' => $m->setting_key,
                        'icon' => 'cube',
                        'desc' => 'โมดูลระบบสารสนเทศ',
                        'routes' => [],
                        'admin_route' => 'admin.dashboard',
                        'test_route' => 'home'
                    ];
                    $isActive = ($m->setting_value === '1');
                @endphp

                <div class="earth-admin-card p-6 flex flex-col justify-between transition-all duration-300 {{ $isActive ? 'border-[#5A6B47]/40 shadow-sm' : 'border-[#C86D51]/40 bg-[#FFFDFB] shadow-inner opacity-90' }}">
                    <div>
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $isActive ? 'bg-[#5A6B47]/15 text-[#5A6B47]' : 'bg-[#C86D51]/15 text-[#C86D51]' }}">
                                <i data-lucide="{{ $meta['icon'] }}" class="w-6 h-6"></i>
                            </div>
                            
                            <!-- Status Badge -->
                            @if ($isActive)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30">
                                    <span class="w-2 h-2 rounded-full bg-[#5A6B47] mr-1.5 animate-pulse"></span>
                                    <span>เปิดใช้งาน (Active)</span>
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#C86D51]/15 text-[#C86D51] border border-[#C86D51]/30">
                                    <span class="w-2 h-2 rounded-full bg-[#C86D51] mr-1.5"></span>
                                    <span>ปิดใช้งาน (Closed)</span>
                                </span>
                            @endif
                        </div>

                        <!-- Module Title -->
                        <h2 class="font-heading font-bold text-lg text-[#2C3E2D] mb-0.5">
                            {{ $meta['name'] }}
                        </h2>
                        <div class="text-xs font-mono text-[#8C9B80] mb-3">
                            {{ $meta['sub'] }}
                        </div>

                        <p class="text-xs text-[#5C5549] leading-relaxed mb-4 min-h-[3rem]">
                            {{ $meta['desc'] }}
                        </p>

                        <!-- Impact Routes -->
                        <div class="bg-[#FAF8F2] rounded-xl p-3 border border-[#EAE5D9] mb-5">
                            <div class="text-[11px] font-semibold text-[#4A3B32] mb-1.5 flex items-center gap-1">
                                <i data-lucide="link" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                <span>เส้นทางหน้าบ้านที่ได้รับผลกระทบ:</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($meta['routes'] as $r)
                                    <span class="text-[11px] font-mono bg-white px-2 py-0.5 rounded border border-[#D5CEBC] text-[#4A3B32]">
                                        {{ $r }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Action Section -->
                    <div class="pt-4 border-t border-[#EAE5D9] flex items-center justify-between gap-3">
                        <a href="{{ route($meta['test_route']) }}" target="_blank" class="text-xs text-[#5A6B47] hover:text-[#2C3E2D] font-semibold flex items-center gap-1 transition">
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            <span>ทดสอบหน้าเว็บ</span>
                        </a>

                        <!-- Toggle Form -->
                        <form action="{{ route('admin.modules.toggle') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="setting_key" value="{{ $m->setting_key }}">
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs gap-1.5 {{ $isActive ? 'bg-[#C86D51] hover:bg-[#A85238] text-white' : 'bg-[#5A6B47] hover:bg-[#465337] text-white' }}">
                                <i data-lucide="{{ $isActive ? 'power-off' : 'power' }}" class="w-3.5 h-3.5"></i>
                                <span>{{ $isActive ? 'กดเพื่อปิดโมดูลนี้' : 'กดเพื่อเปิดโมดูลนี้' }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    </main>

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
