<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประกาศและคำสั่งสำคัญ - {{ __('portal.system_title') }} (VPSMCU)</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
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
                            sand: '#F7F5EE',
                            stone: '#EAE5D9',
                            clay: '#C86D51',
                            clayDark: '#A85238',
                            forest: '#2C3E2D',
                            olive: '#5A6B47',
                            bark: '#4A3B32',
                            cream: '#FAF8F2',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #F7F5EE; color: #2D2A26; }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between selection:bg-[#5A6B47] selection:text-white">

    <!-- Top Info Bar -->
    <div class="bg-[#2C3E2D] text-[#EAE5D9] text-xs py-2 px-4 border-b border-[#3D523E]">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-1 font-medium">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-[#7B8D65] animate-pulse"></span>
                <span>ประกาศและคำสั่งอย่างเป็นทางการ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย</span>
            </div>
            <div class="flex items-center space-x-4 text-[#D8D2C2] text-[11px]">
                <span class="flex items-center gap-1.5"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#A3B88C]"></i> {{ __('portal.institute_name') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 bg-[#FAF8F2]/90 backdrop-blur-xl border-b border-[#E3DEC9] shadow-sm">
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
                            <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded-full bg-[#EAE5D9] text-[#4A3B32] border border-[#D5CEBC]">{{ __('portal.mcu_short') }}</span>
                        </a>
                        <p class="text-xs text-[#6B6357] font-medium">{{ __('portal.system_title') }}</p>
                    </div>
                </div>

                <!-- Nav Links -->
                <nav class="hidden xl:flex items-center space-x-6 text-[15px] font-semibold text-[#4A3B32]">
                    <a href="{{ route('home') }}#calendar" class="hover:text-[#C86D51] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="calendar" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_calendar') }}</span>
                    </a>
                    <a href="{{ route('portal.announcements') }}" class="text-[#5A6B47] border-b-2 border-[#5A6B47] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="file-text" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>ประกาศสำคัญ</span>
                    </a>
                    <a href="{{ route('news.index') }}" class="hover:text-[#C86D51] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="newspaper" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>ข่าวประชาสัมพันธ์</span>
                    </a>
                    <a href="{{ route('ug.register') }}" class="hover:text-[#C86D51] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="graduation-cap" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_ug') }}</span>
                    </a>
                    <a href="{{ route('public.register') }}" class="hover:text-[#C86D51] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="users" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_public') }}</span>
                    </a>
                    <a href="{{ route('donation') }}" class="hover:text-[#C86D51] transition flex items-center gap-1.5 text-[#C86D51] font-bold whitespace-nowrap py-1">
                        <i data-lucide="gift" class="w-4.5 h-4.5 text-[#C86D51]"></i>
                        <span>{{ __('portal.nav_donation') }}</span>
                    </a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center space-x-2.5">
                    @php
                        $currentLang = session('locale', 'th');
                    @endphp
                    @include('portal.partials.font_resizer')
                    <div class="flex items-center bg-[#EAE5D9] p-0.5 rounded-xl border border-[#D5CEBC] text-xs font-bold font-mono">
                        <a href="{{ route('lang.switch', 'th') }}" title="ภาษาไทย" class="px-2 py-1 rounded-lg transition {{ $currentLang === 'th' ? 'bg-[#5A6B47] text-white shadow-sm' : 'text-[#6B6357] hover:text-[#2C3E2D]' }}">TH</a>
                        <a href="{{ route('lang.switch', 'en') }}" title="English" class="px-2 py-1 rounded-lg transition {{ $currentLang === 'en' ? 'bg-[#5A6B47] text-white shadow-sm' : 'text-[#6B6357] hover:text-[#2C3E2D]' }}">EN</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container Standard Width -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow space-y-6">
        
        <!-- Header Banner Card -->
        <div class="bg-gradient-to-r from-[#2C3E2D] to-[#3B523D] text-white rounded-3xl p-6 sm:p-10 shadow-md border border-[#205C29]/30 relative overflow-hidden">
            <div class="max-w-2xl relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-white text-xs font-semibold backdrop-blur-xs mb-3 border border-white/20">
                    <i data-lucide="file-check-2" class="w-3.5 h-3.5 text-[#A3B88C]"></i>
                    <span>เอกสารและประกาศอย่างเป็นทางการ</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-heading font-extrabold tracking-tight leading-tight text-white">
                    ประกาศและคำสั่งสำคัญ
                </h1>
                <p class="text-xs sm:text-sm text-white/85 mt-2 leading-relaxed">
                    ศูนย์รวมประกาศรายชื่อผู้มีสิทธิ์เข้าร่วมปฏิบัติวิปัสสนากรรมฐาน กำหนดการ ตารางอบรม และคำสั่ง มจร ทั่วประเทศ สามารถค้นหารายชื่อของท่านจากไฟล์เอกสาร PDF ได้โดยตรง
                </p>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#D5CEBC] shadow-xs">
            <form method="GET" action="{{ route('portal.announcements') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
                <!-- Search Box (Supports PDF text search!) -->
                <div class="sm:col-span-6 relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-[#8C8275]"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหารายชื่อ, เลขประจำตัว, หัวข้อ หรือคำสำคัญในประกาศ..." class="w-full pl-10 pr-4 py-2.5 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs text-[#2C3E2D] focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                </div>

                <!-- Category Filter -->
                <div class="sm:col-span-3">
                    <select name="category" onchange="this.form.submit()" class="w-full px-3 py-2.5 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs text-[#2C3E2D] focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-medium">
                        <option value="">-- ทุกหมวดหมู่ --</option>
                        <option value="PARTICIPANTS" {{ request('category') === 'PARTICIPANTS' ? 'selected' : '' }}>ประกาศรายชื่อผู้มีสิทธิ์</option>
                        <option value="SCHEDULE" {{ request('category') === 'SCHEDULE' ? 'selected' : '' }}>กำหนดการและสถานที่</option>
                        <option value="ORDER" {{ request('category') === 'ORDER' ? 'selected' : '' }}>คำสั่ง / ระเบียบปฏิบัติ</option>
                        <option value="GENERAL" {{ request('category') === 'GENERAL' ? 'selected' : '' }}>ประกาศทั่วไป</option>
                    </select>
                </div>

                <!-- OrgUnit Filter -->
                <div class="sm:col-span-3">
                    <select name="org_unit_id" onchange="this.form.submit()" class="w-full px-3 py-2.5 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs text-[#2C3E2D] focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-medium">
                        <option value="">-- ทุกส่วนงาน มจร --</option>
                        @foreach ($orgUnits as $ou)
                            <option value="{{ $ou->id }}" {{ request('org_unit_id') == $ou->id ? 'selected' : '' }}>{{ $ou->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Announcements Document List -->
        <div class="bg-white rounded-3xl p-5 sm:p-8 border border-[#D5CEBC] shadow-xs">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#EAE5D9]">
                <div class="text-xs font-semibold text-[#7B8D65]">
                    พบทั้งหมด <strong>{{ $announcementsList->total() }}</strong> ฉบับ
                    @if(request('search'))
                        <span class="text-[#C86D51] ml-1">(คำค้นหา: "{{ request('search') }}")</span>
                    @endif
                </div>
                @if(request()->anyFilled(['search', 'category', 'org_unit_id']))
                    <a href="{{ route('portal.announcements') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1 font-semibold">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i> ล้างตัวกรอง
                    </a>
                @endif
            </div>

            <div class="divide-y divide-[#EAE5D9]">
                @forelse ($announcementsList as $anc)
                    <div class="py-4 sm:py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-[#FAF8F2]/60 -mx-3 px-3 rounded-2xl transition group">
                        
                        <!-- Left Details -->
                        <div class="flex items-start gap-3.5 min-w-0 flex-grow">
                            <!-- Icon / Pin Badge -->
                            <div class="w-10 h-10 rounded-xl {{ $anc->is_pinned ? 'bg-[#C86D51]/15 text-[#C86D51]' : 'bg-[#5A6B47]/15 text-[#5A6B47]' }} flex items-center justify-center shrink-0 mt-0.5">
                                @if ($anc->is_pinned)
                                    <i data-lucide="pin" class="w-5 h-5 fill-current"></i>
                                @else
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                @endif
                            </div>

                            <div class="min-w-0 space-y-1">
                                <!-- Top Tags: Category & No & Date -->
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

                                <!-- Announcement Title -->
                                <h2 class="text-sm sm:text-base font-heading font-bold text-[#2D2A26] transition">
                                    @if ($anc->attachment_path)
                                        <button type="button" onclick="openPdfPreviewModal('{{ $anc->attachment_url }}', '{{ addslashes($anc->localized_title) }}', '{{ addslashes($anc->attachment_name ?: 'เอกสาร') }}')" class="text-left font-heading font-bold text-[#2D2A26] hover:text-[#5A6B47] transition cursor-pointer">
                                            <span class="hover:underline">{{ $anc->localized_title }}</span>
                                        </button>
                                    @else
                                        <a href="{{ route('portal.announcement.detail', ['id' => $anc->id]) }}" class="hover:text-[#5A6B47] hover:underline transition">
                                            {{ $anc->localized_title }}
                                        </a>
                                    @endif
                                </h2>

                                <!-- Subtext details / Department -->
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
                                <button type="button" onclick="openPdfPreviewModal('{{ $anc->attachment_url }}', '{{ addslashes($anc->localized_title) }}', '{{ addslashes($anc->attachment_name ?: 'เอกสาร') }}')" class="px-3 py-1.5 rounded-xl border border-[#D5CEBC] bg-white hover:bg-[#FAF8F2] text-xs font-semibold text-[#5A6B47] transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
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
                                    <span>อ่านรายละเอียด</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="py-12 text-center text-[#8C8275]">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <i data-lucide="file-search" class="w-10 h-10 text-[#D5CEBC]"></i>
                            <p class="text-sm font-semibold text-[#4A3B32]">ไม่พบประกาศที่ตรงกับคำค้นหา</p>
                            <p class="text-xs text-[#8C8275]">ลองเปลี่ยนคำค้นหา หรือคลิก "ล้างตัวกรอง" เพื่อดูประกาศทั้งหมด</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($announcementsList->hasPages())
                <div class="pt-6 mt-4 border-t border-[#EAE5D9]">
                    {{ $announcementsList->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- PDF Preview Modal -->
    <div id="pdf-preview-modal" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-3 sm:p-6 hidden">
        <div class="bg-white rounded-2xl w-full max-w-5xl h-[92vh] flex flex-col shadow-2xl border border-[#D5CEBC] overflow-hidden">
            <div class="p-4 bg-[#2C3E2D] text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5 min-w-0 pr-4">
                    <div class="w-8 h-8 rounded-lg bg-[#5A6B47] flex items-center justify-center shrink-0">
                        <i data-lucide="file-text" class="w-4 h-4 text-white"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 id="pdf-preview-title" class="font-heading font-bold text-sm truncate text-[#FAF8F2]">พรีวิวเอกสาร</h3>
                        <p id="pdf-preview-filename" class="text-[11px] text-[#A3B88C] font-mono truncate">filename.pdf</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a id="pdf-preview-download-btn" href="#" download class="px-3 py-1.5 rounded-lg bg-[#5A6B47] hover:bg-[#7B8D65] text-white text-xs font-semibold flex items-center gap-1.5 transition">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">ดาวน์โหลด</span>
                    </a>
                    <a id="pdf-preview-external-btn" href="#" target="_blank" class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs transition" title="เปิดแท็บใหม่เต็มจอ">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                    </a>
                    <button type="button" onclick="closePdfPreviewModal()" class="p-1.5 text-white/70 hover:text-white rounded-lg transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div class="flex-grow bg-[#525659] relative">
                <iframe id="pdf-preview-iframe" src="" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2C3E2D] text-[#EAE5D9] mt-12 py-8 border-t border-[#3D523E]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-[#A3B88C]">
            <p>© {{ date('Y') + 543 }} สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย (มจร)</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons();
            }
        });

        function openPdfPreviewModal(url, title, filename) {
            document.getElementById('pdf-preview-title').textContent = title || 'พรีวิวเอกสาร';
            document.getElementById('pdf-preview-filename').textContent = filename || url.split('/').pop();
            document.getElementById('pdf-preview-download-btn').href = url;
            document.getElementById('pdf-preview-external-btn').href = url;
            document.getElementById('pdf-preview-iframe').src = url + '#toolbar=1&navpanes=1';
            document.getElementById('pdf-preview-modal').classList.remove('hidden');
            if (window.lucide) window.lucide.createIcons();
        }

        function closePdfPreviewModal() {
            document.getElementById('pdf-preview-modal').classList.add('hidden');
            document.getElementById('pdf-preview-iframe').src = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePdfPreviewModal();
            }
        });
    </script>
</body>
</html>

