<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->localized_title }} - ประกาศสำคัญ VPSMCU</title>
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
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-xs text-[#7B8D65]">
            <a href="{{ route('home') }}" class="hover:underline flex items-center gap-1">
                <i data-lucide="home" class="w-3.5 h-3.5"></i> หน้าหลัก
            </a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-[#D5CEBC]"></i>
            <a href="{{ route('portal.announcements') }}" class="hover:underline">ประกาศและคำสั่ง</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-[#D5CEBC]"></i>
            <span class="text-[#2C3E2D] font-medium truncate max-w-xs">{{ $announcement->localized_title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Main Content Column -->
            <div class="lg:col-span-8 space-y-6">
                <article class="bg-white rounded-3xl p-6 sm:p-9 border border-[#D5CEBC] shadow-xs space-y-6">
                    
                    <!-- Metadata Header -->
                    <div class="space-y-3 pb-6 border-b border-[#EAE5D9]">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold border {{ $announcement->category_badge_class }}">
                                {{ $announcement->category_label }}
                            </span>
                            @if ($announcement->announcement_no)
                                <span class="text-xs font-mono font-semibold text-[#2C3E2D] bg-[#EAE5D9]/70 px-2.5 py-0.5 rounded border border-[#D5CEBC]">
                                    เลขที่ {{ $announcement->announcement_no }}
                                </span>
                            @endif
                        </div>

                        <h1 class="text-xl sm:text-3xl font-heading font-extrabold text-[#2C3E2D] leading-snug">
                            {{ $announcement->localized_title }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 text-xs text-[#7B8D65] pt-1">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#5A6B47]"></i>
                                {{ $announcement->published_at ? \Carbon\Carbon::parse($announcement->published_at)->format('d F Y') : \Carbon\Carbon::parse($announcement->created_at)->format('d F Y') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="building-2" class="w-4 h-4 text-[#5A6B47]"></i>
                                {{ $announcement->organizationUnit ? $announcement->organizationUnit->name : 'สถาบันวิปัสสนาธุระ (ส่วนกลาง)' }}
                            </span>
                            <span class="flex items-center gap-1.5 text-[#8C8275]">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                {{ number_format($announcement->views) }} เข้าชม
                            </span>
                        </div>
                    </div>

                    <!-- Description Text (if any) -->
                    @if ($announcement->localized_content)
                        <div class="prose max-w-none text-sm text-[#4A3B32] leading-relaxed">
                            {!! nl2br(e($announcement->localized_content)) !!}
                        </div>
                    @endif

                    <!-- Attachment Section & Embedded PDF Viewer -->
                    @if ($announcement->attachment_path)
                        <div class="space-y-4 pt-4 border-t border-[#EAE5D9]">
                            <!-- Document Info Card -->
                            <div class="p-4 sm:p-5 rounded-2xl bg-[#FAF8F2] border border-[#D5CEBC] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-12 h-12 rounded-xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center shrink-0">
                                        <i data-lucide="file-text" class="w-6 h-6"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-heading font-bold text-sm text-[#2C3E2D] truncate" title="{{ $announcement->attachment_name }}">
                                            {{ $announcement->attachment_name ?: 'เอกสารแนบประกาศ.pdf' }}
                                        </div>
                                        <div class="text-xs text-[#7B8D65] font-mono mt-0.5">
                                            ขนาดไฟล์: {{ $announcement->attachment_size ?: 'PDF Document' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <a href="{{ $announcement->attachment_url }}" target="_blank" class="flex-1 sm:flex-initial px-4 py-2 rounded-xl bg-white border border-[#D5CEBC] text-xs font-semibold text-[#5A6B47] hover:bg-[#FAF8F2] transition flex items-center justify-center gap-1.5 shadow-2xs">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                        <span>เปิดเต็มจอ</span>
                                    </a>
                                    <a href="{{ $announcement->attachment_url }}" download="{{ $announcement->attachment_name ?: 'announcement.pdf' }}" target="_blank" class="flex-1 sm:flex-initial px-4 py-2 rounded-xl bg-[#5A6B47] hover:bg-[#2C3E2D] text-white text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                        <span>ดาวน์โหลด</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Embedded Interactive PDF Previewer -->
                            <div class="rounded-2xl border border-[#D5CEBC] overflow-hidden shadow-xs bg-[#525659]">
                                <div class="p-3 bg-[#2C3E2D] text-white flex items-center justify-between text-xs font-medium">
                                    <span class="flex items-center gap-1.5">
                                        <i data-lucide="eye" class="w-4 h-4 text-[#A3B88C]"></i>
                                        <span>แสดงพรีวิวเอกสาร (PDF Viewer)</span>
                                    </span>
                                    <span class="text-[11px] text-[#A3B88C]">ใช้ปุ่มค้นหาในตัว PDF หรือซูมได้อิสระ</span>
                                </div>
                                <iframe src="{{ $announcement->attachment_url }}#toolbar=1&navpanes=1" class="w-full h-[650px] border-0"></iframe>
                            </div>
                        </div>
                    @endif

                </article>
            </div>

            <!-- Sidebar Column -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Back Link Button -->
                <a href="{{ route('portal.announcements') }}" class="w-full py-3 px-4 rounded-2xl bg-white border border-[#D5CEBC] hover:border-[#5A6B47] text-xs font-semibold text-[#2C3E2D] transition flex items-center justify-center gap-2 shadow-2xs">
                    <i data-lucide="arrow-left" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>กลับไปหน้ารวมประกาศทั้งหมด</span>
                </a>

                <!-- Related Announcements -->
                <div class="bg-white rounded-3xl p-6 border border-[#D5CEBC] shadow-xs space-y-4">
                    <h3 class="font-heading font-bold text-base text-[#2C3E2D] flex items-center gap-2 pb-3 border-b border-[#EAE5D9]">
                        <i data-lucide="bell" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>ประกาศล่าสุดอื่น ๆ</span>
                    </h3>

                    <div class="divide-y divide-[#EAE5D9]">
                        @forelse ($relatedAnnouncements as $rel)
                            <div class="py-3 first:pt-0 last:pb-0 group">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border {{ $rel->category_badge_class }}">
                                    {{ $rel->category_label }}
                                </span>
                                <h4 class="text-xs font-heading font-semibold text-[#2D2A26] group-hover:text-[#5A6B47] transition mt-1.5 line-clamp-2">
                                    <a href="{{ route('portal.announcement.detail', ['id' => $rel->id]) }}">
                                        {{ $rel->localized_title }}
                                    </a>
                                </h4>
                                <div class="text-[11px] text-[#8C8275] flex items-center gap-2 mt-1">
                                    <span>{{ $rel->published_at ? \Carbon\Carbon::parse($rel->published_at)->format('d/m/Y') : '-' }}</span>
                                    <span>•</span>
                                    <span class="truncate">{{ $rel->organizationUnit ? $rel->organizationUnit->name : 'ส่วนกลาง' }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-[#8C8275] py-2">ไม่มีประกาศอื่นเพิ่มเติม</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </main>

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
    </script>
</body>
</html>

