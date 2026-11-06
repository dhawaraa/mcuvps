<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('portal.ug_check_header_title') }} - VPSMCU</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
                    },
                    colors: {
                        earth: {
                            sand: '#F7F4EA',
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
        body { font-family: 'Sarabun', sans-serif; background-color: #F7F5EE; color: #2D2A26; }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Navigation -->
    <nav class="bg-[#FAF8F2] border-b border-[#E3DEC9] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-10 h-10 object-contain drop-shadow-sm">
                    <div>
                        <div class="font-heading font-bold text-[#2C3E2D] leading-tight">VPSMCU</div>
                        <div class="text-xs text-[#6B6357]">{{ __('portal.university_name') }}</div>
                    </div>
                </a>

                <!-- Module 1 Navigation Tabs: ลงทะเบียน vs ตรวจสอบข้อมูล (Active) -->
                <div class="hidden sm:flex items-center bg-[#EAE5D9]/80 p-1 rounded-2xl border border-[#D5CEBC]">
                    <a href="{{ route('ug.register') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#FAF8F2]">
                        <i data-lucide="user-plus" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>{{ __('portal.ug_tab_register') }}</span>
                    </a>
                    <a href="{{ route('ug.check') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 bg-[#5A6B47] text-white shadow-sm">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>{{ __('portal.ug_tab_check') }}</span>
                    </a>
                </div>

                <div class="flex items-center space-x-3 sm:space-x-4">
                    @php
                        $currentLang = session('locale', 'th');
                    @endphp
                    <!-- Language Switcher (TH / EN) -->
                    @include('portal.partials.font_resizer')
                    <div class="flex items-center bg-[#EAE5D9] p-0.5 rounded-xl border border-[#D5CEBC] text-xs font-bold font-mono">
                        <a href="{{ route('lang.switch', 'th') }}" title="ภาษาไทย" class="px-2 py-1 rounded-lg transition {{ $currentLang === 'th' ? 'bg-[#5A6B47] text-white shadow-sm' : 'text-[#6B6357] hover:text-[#2C3E2D]' }}">
                            TH
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" title="English" class="px-2 py-1 rounded-lg transition {{ $currentLang === 'en' ? 'bg-[#5A6B47] text-white shadow-sm' : 'text-[#6B6357] hover:text-[#2C3E2D]' }}">
                            EN
                        </a>
                    </div>

                    <a href="{{ route('home') }}" class="text-[#4A3B32] hover:text-[#C86D51] font-semibold text-[15px] flex items-center gap-1.5 transition">
                        <i data-lucide="arrow-left" class="w-4.5 h-4.5 text-[#5A6B47]"></i> <span class="hidden sm:inline">{{ __('portal.nav_back_home') }}</span>
                    </a>
                </div>
            </div>

            <!-- Mobile Sub-Navigation -->
            <div class="flex sm:hidden items-center justify-center pb-3 pt-1 border-t border-[#EAE5D9] gap-2">
                <a href="{{ route('ug.register') }}" class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-bold bg-white text-[#4A3B32] border border-[#D5CEBC]">
                    {{ __('portal.ug_tab_register') }}
                </a>
                <a href="{{ route('ug.check') }}" class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-bold bg-[#5A6B47] text-white">
                    {{ __('portal.ug_tab_check') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#2C3E2D] via-[#4A3B32] to-[#A85238] rounded-2xl p-6 md:p-8 text-white shadow-md mb-8 border border-[#3D523E] flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/15 backdrop-blur-sm rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-white/20">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#EAE5D9]"></i>
                    <span>{{ __('portal.ug_check_header_badge') }}</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-heading font-bold mb-2">{{ __('portal.ug_check_header_title') }}</h1>
                <p class="text-[#EAE5D9] text-sm leading-relaxed max-w-2xl">
                    {{ __('portal.ug_check_header_desc') }}
                </p>
            </div>
            <div class="shrink-0 flex flex-wrap gap-2.5">
                <a href="{{ route('student.login') }}" class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white px-4 py-3 rounded-xl font-medium text-sm transition shadow-lg border border-white/20">
                    <i data-lucide="user-round" class="w-4 h-4 text-[#A3B88C]"></i>
                    <span>เข้าสู่ระบบพอร์ทัลนิสิต</span>
                </a>
                <a href="{{ route('ug.register') }}" class="inline-flex items-center gap-2 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-3 rounded-xl font-medium text-sm transition shadow-lg border border-white/10">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>{{ __('portal.ug_tab_register') }}</span>
                </a>
            </div>
        </div>

        <!-- Search Box -->
        <div class="bg-white border border-[#E3DEC9] rounded-2xl p-6 shadow-sm mb-8">
            <form action="{{ route('ug.check') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <i data-lucide="search" class="w-4 h-4 text-[#8C8275] absolute left-3.5 top-3.5"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('portal.ug_check_search_ph') }}" class="w-full pl-10 pr-4 py-2.5 border border-[#D5CEBC] rounded-xl text-sm focus:ring-2 focus:ring-[#5A6B47] font-mono bg-[#FAF8F2] text-[#2C3E2D]">
                </div>
                <button type="submit" class="bg-[#2C3E2D] hover:bg-[#3D523E] text-white font-medium px-6 py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2 shadow-sm">
                    <i data-lucide="search" class="w-4 h-4 text-[#A3B88C]"></i>
                    <span>{{ __('portal.ug_check_btn_search') }}</span>
                </button>
            </form>
            <div class="text-xs text-[#8C8275] mt-2.5 flex items-center gap-1.5">
                <i data-lucide="lightbulb" class="w-3.5 h-3.5 text-[#C86D51]"></i>
                <span>{{ __('portal.ug_check_sample_hint') }} <a href="{{ route('ug.check', ['search' => '6601201001']) }}" class="text-[#C86D51] underline font-mono">6601201001</a></span>
            </div>
        </div>

        @if ($search)
            @if ($student)
                <!-- Student Profile Card -->
                <div class="bg-white border border-[#E3DEC9] rounded-2xl p-6 shadow-sm mb-8">
                    <div class="flex items-center gap-2 border-b border-[#E3DEC9] pb-3 mb-4">
                        <i data-lucide="user" class="w-5 h-5 text-[#5A6B47]"></i>
                        <h2 class="text-base font-heading font-bold text-[#2C3E2D]">{{ __('portal.ug_check_student_info') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[11px] text-[#7B8D65]">{{ __('portal.ug_student_code') }}</div>
                            <div class="text-base font-mono font-bold text-[#2C3E2D]">{{ $student->student_code }}</div>
                            <div class="text-xs text-[#4A3B32] mt-1">{{ $student->prefix }}{{ $student->first_name }} {{ $student->last_name }}</div>
                            @if ($student->chaya)
                                <div class="text-xs text-[#7B8D65]">({{ $student->chaya }})</div>
                            @endif
                        </div>

                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[11px] text-[#7B8D65]">{{ __('portal.ug_faculty') }} / {{ __('portal.ug_major') }}</div>
                            <div class="text-sm font-semibold text-[#2C3E2D]">{{ $student->faculty ?? '-' }}</div>
                            <div class="text-xs text-[#6B6357] mt-0.5">{{ $student->major ?? '-' }} &bull; {{ __('portal.ug_year') }} {{ $student->study_year ?? 1 }}</div>
                        </div>

                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[11px] text-[#7B8D65]">{{ __('portal.ug_org') }}</div>
                            <div class="text-sm font-bold text-[#C86D51]">
                                {{ session('locale') === 'en' && !empty($student->organizationUnit->name_en) ? $student->organizationUnit->name_en : ($student->organizationUnit->name_th ?? 'มจร') }}
                            </div>
                            <div class="text-xs text-[#6B6357] mt-0.5 font-mono">{{ $student->organizationUnit->code ?? '' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Registration Records Section -->
            <div class="bg-white border border-[#E3DEC9] rounded-2xl p-6 shadow-sm mb-8">
                <div class="flex items-center justify-between border-b border-[#E3DEC9] pb-3 mb-4">
                    <div class="flex items-center gap-2">
                        <i data-lucide="clipboard-list" class="w-5 h-5 text-[#5A6B47]"></i>
                        <h2 class="text-base font-heading font-bold text-[#2C3E2D]">{{ __('portal.ug_check_reg_history') }}</h2>
                    </div>
                    <span class="text-xs font-mono px-2.5 py-1 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[#7B8D65]">
                        {{ $registrations->count() }} {{ __('portal.grad_records_unit') }}
                    </span>
                </div>

                @if ($registrations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[#EAE5D9] text-[11px] font-semibold text-[#7B8D65] uppercase tracking-wider bg-[#FAF8F2]">
                                    <th class="py-3 px-4">{{ __('portal.ug_check_th_reg_no') }}</th>
                                    <th class="py-3 px-4">{{ __('portal.ug_check_th_project') }}</th>
                                    <th class="py-3 px-4">{{ __('portal.ug_check_th_dates') }}</th>
                                    <th class="py-3 px-4">{{ __('portal.ug_check_th_status') }}</th>
                                    <th class="py-3 px-4 text-center">{{ __('portal.ug_check_th_action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EAE5D9] text-xs">
                                @foreach ($registrations as $reg)
                                    <tr class="hover:bg-[#FAF8F2]/60 transition">
                                        <td class="py-3.5 px-4 font-mono font-bold text-[#2C3E2D]">
                                            {{ $reg->registration_no }}
                                            <div class="text-[10px] text-[#8C8275] font-normal">
                                                {{ $reg->created_at ? $reg->created_at->format('d/m/Y H:i') : '-' }}
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="font-medium text-[#2C3E2D]">
                                                {{ $reg->batch->batch_name ?? ('รุ่นปีการศึกษา ' . ($reg->batch->academic_year ?? '-')) }}
                                            </div>
                                            <div class="text-[11px] text-[#7B8D65]">
                                                {{ $reg->batch->venue_name ?? ($reg->organizationUnit->name_th ?? '') }}
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap text-[#4A3B32]">
                                            @if ($reg->batch && $reg->batch->start_date)
                                                {{ date('d/m/Y', strtotime($reg->batch->start_date)) }} - {{ date('d/m/Y', strtotime($reg->batch->end_date)) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if ($reg->status === 'COMPLETED')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#E9EFE2] text-[#3D523E] border border-[#CADBC0]">
                                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                                    {{ __('portal.ug_status_completed') }}
                                                </span>
                                            @elseif ($reg->status === 'CHECKED_IN')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <i data-lucide="user-check" class="w-3.5 h-3.5 text-blue-600"></i>
                                                    {{ __('portal.ug_status_checked_in') }}
                                                </span>
                                            @elseif ($reg->status === 'REGISTERED' || $reg->status === 'APPROVED')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#FAF8F2] text-[#5A6B47] border border-[#CADBC0]">
                                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                                    {{ __('portal.ug_status_approved') }}
                                                </span>
                                            @elseif ($reg->status === 'REJECTED')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                                    <i data-lucide="x-circle" class="w-3.5 h-3.5 text-red-600"></i>
                                                    {{ __('portal.ug_status_rejected') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                                                    {{ __('portal.ug_status_pending') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <div class="inline-flex items-center gap-2">
                                                <!-- Open QR Card Modal / Pop-up -->
                                                <button type="button" onclick="openQrModal('{{ $reg->registration_no }}', '{{ $reg->full_name }}', '{{ $reg->batch->batch_name ?? '' }}')" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#2C3E2D] border border-[#D5CEBC] px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5 shadow-sm">
                                                    <i data-lucide="qr-code" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                                    <span>{{ __('portal.ug_btn_view_card') }}</span>
                                                </button>

                                                @if ($reg->status === 'COMPLETED')
                                                    <a href="{{ route('ug.certificate', ['reg_no' => $reg->registration_no]) }}" target="_blank" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5 shadow-sm">
                                                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                                                        <span>{{ __('portal.ug_btn_cert') }}</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-10 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9]">
                        <i data-lucide="calendar-x" class="w-10 h-10 text-[#8C8275] mx-auto mb-2"></i>
                        <div class="text-sm font-semibold text-[#4A3B32]">{{ __('portal.ug_check_no_reg') }}</div>
                        <p class="text-xs text-[#8C8275] mt-1">นิสิตสามารถเลือกลงทะเบียนเข้าร่วมโครงการใหม่ได้ทันที</p>
                        <a href="{{ route('ug.register') }}" class="mt-4 inline-flex items-center gap-1.5 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-5 py-2.5 rounded-xl text-xs font-semibold shadow-sm transition">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                            <span>{{ __('portal.ug_tab_register') }}</span>
                        </a>
                    </div>
                @endif
            </div>
        @else
            <!-- Initial Instruction Card -->
            <div class="bg-white border border-[#E3DEC9] rounded-2xl p-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-[#FAF8F2] text-[#5A6B47] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#EAE5D9]">
                    <i data-lucide="search" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-heading font-bold text-[#2C3E2D] mb-1">กรอกรหัสนิสิตเพื่อค้นหาข้อมูล</h3>
                <p class="text-xs text-[#6B6357] max-w-md mx-auto leading-relaxed">
                    ระบบจะแสดงประวัติการลงทะเบียนวิปัสสนากรรมฐาน (10 วัน/ปี), สถานะการอนุมัติสิทธิ์ พร้อมปุ่มเปิดบัตรลงทะเบียน QR Code และหนังสือรับรอง
                </p>
            </div>
        @endif

    </main>

    <!-- QR Code Modal -->
    <div id="qrModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-[#EAE5D9] relative animate-fade-in">
            <button type="button" onclick="closeQrModal()" class="absolute right-4 top-4 text-[#8C8275] hover:text-[#2C3E2D] transition p-1 rounded-full bg-[#FAF8F2]">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="w-12 h-12 bg-[#FAF8F2] text-[#5A6B47] rounded-full flex items-center justify-center mx-auto mb-3 border border-[#EAE5D9]">
                <i data-lucide="qr-code" class="w-6 h-6"></i>
            </div>
            <h3 class="text-base font-heading font-bold text-[#2C3E2D]" id="modalName">บัตรลงทะเบียนนิสิต</h3>
            <p class="text-xs text-[#7B8D65] mt-0.5" id="modalBatch">-</p>

            <div class="bg-[#FAF8F2] p-4 rounded-2xl border border-[#EAE5D9] my-4 inline-block">
                <img id="modalQrImg" src="" alt="QR Checkin" class="w-44 h-44 mx-auto rounded-lg shadow-sm">
                <div class="text-[11px] font-mono font-bold text-[#2C3E2D] mt-2" id="modalRegNo">-</div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="downloadModalQr()" class="flex-1 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white text-xs font-semibold py-2.5 px-4 rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>ดาวน์โหลด QR</span>
                </button>
                <button type="button" onclick="closeQrModal()" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#4A3B32] text-xs font-semibold py-2.5 px-4 rounded-xl border border-[#D5CEBC] transition">
                    ปิด
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#FAF8F2] border-t border-[#E3DEC9] py-6 text-center text-xs text-[#8C8275]">
        {{ __('portal.footer_brand') }}
    </footer>

    <script>
        let currentRegNo = '';

        function openQrModal(regNo, name, batch) {
            currentRegNo = regNo;
            document.getElementById('modalName').textContent = name || 'บัตรลงทะเบียนนิสิต';
            document.getElementById('modalBatch').textContent = batch || '';
            document.getElementById('modalRegNo').textContent = 'VPSMCU-' + regNo;
            document.getElementById('modalQrImg').src = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=VPSMCU-' + encodeURIComponent(regNo);
            document.getElementById('qrModal').classList.remove('hidden');
        }

        function closeQrModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }

        function downloadModalQr() {
            if (!currentRegNo) return;
            const url = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=VPSMCU-' + encodeURIComponent(currentRegNo);
            fetch(url)
                .then(res => res.blob())
                .then(blob => {
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = 'QR_Checkin_' + currentRegNo + '.png';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                })
                .catch(err => alert('ไม่สามารถดาวน์โหลดภาพ QR ได้: ' + err));
        }

        lucide.createIcons();
    </script>
</body>
</html>
