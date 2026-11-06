<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('portal.ug_header_title') }} - VPSMCU</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>
    <script src="/assets/js/font-resizer.js"></script>

    <script src="/assets/js/tailwindcss.min.js"></script>
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
        body { font-family: 'Sarabun', sans-serif; background-color: #F7F5EE; color: #2D2A26; }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }
        
        /* Custom subtle scrollbar for batches */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F7F5EE;
            border-radius: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D5CEBC;
            border-radius: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #5A6B47;
        }
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

                <!-- Module 1 Navigation Tabs: ลงทะเบียน (Active) vs ตรวจสอบข้อมูล -->
                <div class="hidden sm:flex items-center bg-[#EAE5D9]/80 p-1 rounded-2xl border border-[#D5CEBC]">
                    <a href="{{ route('ug.register') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 bg-[#5A6B47] text-white shadow-sm">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>{{ __('portal.ug_tab_register') }}</span>
                    </a>
                    <a href="{{ route('ug.check') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#FAF8F2]">
                        <i data-lucide="search" class="w-4 h-4 text-[#5A6B47]"></i>
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
                <a href="{{ route('ug.register') }}" class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-bold bg-[#5A6B47] text-white">
                    {{ __('portal.ug_tab_register') }}
                </a>
                <a href="{{ route('ug.check') }}" class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-bold bg-white text-[#4A3B32] border border-[#D5CEBC]">
                    {{ __('portal.ug_tab_check') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- Header Banner (Earth Clay & Forest) -->
        <div class="bg-gradient-to-r from-[#2C3E2D] via-[#4A3B32] to-[#A85238] rounded-2xl p-6 md:p-8 text-white shadow-md mb-8 border border-[#3D523E]">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/15 backdrop-blur-sm rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-white/20">
                <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-[#EAE5D9]"></i>
                <span>{{ __('portal.ug_header_badge') }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-heading font-bold mb-2">{{ __('portal.ug_header_title') }}</h1>
            <p class="text-[#EAE5D9] text-sm leading-relaxed">
                {{ __('portal.ug_header_desc') }}
            </p>
        </div>

        @if (session('error'))
            <div class="bg-[#FBE8E6] border-l-4 border-[#C86D51] p-4 rounded-r-lg mb-6 shadow-sm">
                <div class="flex items-center">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-[#C86D51] mr-2 shrink-0"></i>
                    <p class="text-sm text-[#A85238] font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if (session('regSuccess'))
            <div class="bg-white border border-[#D5CEBC] rounded-2xl p-8 shadow-sm text-center mb-8">
                <div class="w-16 h-16 bg-[#FAF8F2] text-amber-700 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-300">
                    <i data-lucide="clock" class="w-8 h-8"></i>
                </div>
                <h2 class="text-2xl font-heading font-bold text-[#2C3E2D] mb-1">{{ __('portal.ug_success_title') }}</h2>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 border border-amber-200 text-amber-800 rounded-full text-xs font-semibold mb-4">
                    <i data-lucide="info" class="w-3.5 h-3.5"></i>
                    <span>{{ __('portal.ug_success_status') }}</span>
                </div>
                <p class="text-[#6B6357] text-xs max-w-lg mx-auto mb-6">
                    {{ __('portal.ug_success_desc') }}
                </p>
                
                <div class="inline-block bg-[#F7F5EE] border border-[#D5CEBC] rounded-xl p-6 text-left max-w-sm w-full shadow-inner mb-6">
                    <div class="text-xs text-[#8C8275] uppercase font-semibold">{{ __('portal.ug_ref_code') }}</div>
                    <div class="text-lg font-heading font-bold text-[#C86D51] mt-1">{{ session('regSuccess') }}</div>
                    <div class="mt-4 pt-4 border-t border-[#E3DEC9] flex flex-col items-center">
                        <div class="bg-white p-3 border border-[#D5CEBC] rounded-lg shadow-sm text-center">
                            <img id="qr-image" crossOrigin="anonymous" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=VPSMCU-{{ session('regSuccess') }}" alt="QR Check-in" class="w-40 h-40 mx-auto" />
                            <div class="text-[10px] text-[#8C8275] mt-2 font-mono flex items-center justify-center gap-1">
                                <i data-lucide="qr-code" class="w-3 h-3 text-[#5A6B47]"></i> {{ __('portal.ug_qr_verify_code') }}: VPSMCU-{{ session('regSuccess') }}
                            </div>
                        </div>
                        <button type="button" onclick="downloadQRCode('{{ session('regSuccess') }}')" class="mt-3.5 w-full bg-white hover:bg-[#FAF8F2] text-[#2C3E2D] border border-[#5A6B47]/40 hover:border-[#5A6B47] text-xs font-medium py-2 px-3 rounded-lg shadow-sm transition flex items-center justify-center gap-1.5">
                            <i data-lucide="download" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                            <span>{{ __('portal.ug_download_qr') }}</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-3">
                    <button onclick="window.print()" class="bg-[#2C3E2D] hover:bg-[#3D523E] text-[#F7F4EA] font-medium px-6 py-2.5 rounded-lg text-sm transition flex items-center gap-2 shadow-sm">
                        <i data-lucide="printer" class="w-4 h-4"></i> {{ __('portal.ug_print_card') }}
                    </button>
                    <a href="{{ route('ug.register') }}" class="text-[#6B6357] hover:text-[#2C3E2D] text-sm font-medium py-2.5">
                        {{ __('portal.ug_register_more') }}
                    </a>
                </div>
            </div>
        @else

            <form action="{{ route('ug.store') }}" method="POST" class="bg-white border border-[#E3DEC9] rounded-2xl shadow-sm overflow-hidden p-6 md:p-8 space-y-6">
                @csrf
                
                <!-- Section 1: ค้นหาข้อมูลนิสิตด้วยรหัสนิสิต -->
                <div class="bg-[#FAF8F2] border border-[#EAE5D9] rounded-2xl p-5 md:p-6">
                    <h2 class="text-base font-heading font-bold text-[#2C3E2D] flex items-center justify-between border-b border-[#E3DEC9] pb-3 mb-4">
                        <span class="flex items-center">
                            <span class="w-6 h-6 rounded-full bg-[#5A6B47] text-white text-xs font-bold flex items-center justify-center mr-2">1</span>
                            {{ __('portal.ug_step1_title') }}
                        </span>
                        <span class="text-xs text-[#7B8D65] font-normal">{{ __('portal.ug_step1_subtitle') }}</span>
                    </h2>

                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-end w-full">
                        <div class="flex-1 min-w-0">
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                                {{ __('portal.ug_student_code') }} <span class="text-[#C86D51]">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="search" class="w-4 h-4 text-[#8C8275] absolute left-3 top-3"></i>
                                <input type="text" id="input-student-code" name="student_code" placeholder="เช่น 6601201001" required class="w-full pl-9 pr-3 py-2.5 border border-[#D5CEBC] rounded-xl text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-white text-[#2C3E2D]">
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" id="btn-lookup-student" onclick="lookupStudentCode()" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white font-medium px-4 sm:px-5 py-2.5 rounded-xl text-xs shadow-sm transition flex items-center justify-center gap-1.5 h-[42px] whitespace-nowrap">
                                <i data-lucide="user-check" class="w-4 h-4 shrink-0"></i>
                                <span>{{ __('portal.ug_btn_verify') }}</span>
                            </button>
                            <button type="button" id="btn-reset-student" onclick="resetForm()" title="{{ __('portal.ug_btn_reset') }}" class="border border-[#D5CEBC] bg-white hover:bg-[#EAE5D9] text-[#4A3B32] font-medium px-3.5 py-2.5 rounded-xl text-xs shadow-sm transition flex items-center justify-center gap-1.5 h-[42px] whitespace-nowrap">
                                <i data-lucide="rotate-ccw" class="w-4 h-4 text-[#C86D51] shrink-0"></i>
                                <span>{{ __('portal.ug_btn_reset') }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Search Feedback Box -->
                    <div id="lookup-feedback" class="mt-4 hidden"></div>

                    <!-- Student Info Card (After Found) -->
                    <div id="student-info-card" class="mt-4 p-4 rounded-xl border border-[#5A6B47]/30 bg-[#5A6B47]/5 hidden">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                            <div>
                                <div class="text-xs font-bold text-[#2C3E2D] flex items-center gap-2">
                                    <span id="disp-student-name" class="text-sm font-heading font-bold text-[#2C3E2D]"></span>
                                    <span id="disp-degree-level" class="px-2 py-0.5 rounded bg-white border border-[#5A6B47]/30 text-[#5A6B47] text-[11px] font-mono"></span>
                                </div>
                                <div class="text-xs text-[#7B8D65] mt-1 space-x-2">
                                    <span>{{ __('portal.ug_faculty') }}: <strong id="disp-faculty" class="text-[#4A3B32]"></strong></span>
                                    <span>&bull; {{ __('portal.ug_major') }}: <strong id="disp-major" class="text-[#4A3B32]"></strong></span>
                                    <span>&bull; {{ __('portal.ug_year') }}: <strong id="disp-study-year" class="text-[#4A3B32]"></strong></span>
                                </div>
                            </div>
                            <div class="text-left md:text-right">
                                <div class="text-[11px] text-[#7B8D65]">{{ __('portal.ug_org') }}</div>
                                <div id="disp-org-name" class="text-xs font-bold text-[#C86D51]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs populated after verification -->
                <input type="hidden" id="hidden_citizen_id" name="citizen_id">
                <input type="hidden" id="hidden_prefix" name="prefix">
                <input type="hidden" id="hidden_first_name" name="first_name">
                <input type="hidden" id="hidden_last_name" name="last_name">
                <input type="hidden" id="hidden_org_unit_id" name="org_unit_id">
                <input type="hidden" id="hidden_study_year" name="study_year" value="1">

                <!-- Section 2: Choose Academic Year / Program (Locked to Student's Org) -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-[#E3DEC9] pb-3 mb-4 gap-2">
                        <h2 class="text-lg font-heading font-semibold text-[#2C3E2D] flex items-center">
                            <span class="w-6 h-6 rounded-full bg-[#EAE5D9] text-[#4A3B32] text-xs font-bold flex items-center justify-center mr-2">2</span>
                            {{ __('portal.ug_step2_title') }}
                        </h2>
                    </div>

                    <!-- Notice Banner -->
                    <div id="batch-filter-hint" class="bg-[#FAF8F2] border border-[#EAE5D9] text-[#4A3B32] px-4 py-2.5 rounded-xl text-xs mb-4 flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-[#5A6B47] shrink-0"></i>
                        <span id="batch-hint-text">{{ __('portal.ug_step2_hint') }}</span>
                    </div>

                    <div class="space-y-3 max-h-[385px] overflow-y-auto pr-1.5 custom-scrollbar" id="batch-list-container">
                        @forelse ($batches as $batch)
                            <label class="batch-card-label flex items-start p-4 border border-[#E3DEC9] rounded-xl cursor-pointer hover:border-[#5A6B47] hover:bg-[#F7F5EE] transition" data-org-id="{{ $batch->org_unit_id }}">
                                <input type="radio" name="batch_id" value="{{ $batch->id }}" required class="mt-1 text-[#5A6B47] focus:ring-[#5A6B47]">
                                <div class="ml-3 flex-grow">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 text-xs bg-[#5A6B47]/15 text-[#5A6B47] rounded-md font-mono font-bold border border-[#5A6B47]/30">{{ __('portal.ug_academic_year') }} {{ $batch->academic_year }}</span>
                                            <span class="font-heading font-semibold text-[#2C3E2D] text-sm md:text-base">{{ $batch->localized_title }}</span>
                                        </div>
                                        <span class="px-2 py-0.5 text-xs bg-[#E9EFE2] text-[#3D523E] rounded-full font-medium border border-[#CADBC0]">{{ __('portal.ug_open_status') }}</span>
                                    </div>
                                    <div class="text-xs text-[#6B6357] mt-1.5 flex items-center gap-1.5">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#C86D51] shrink-0"></i>
                                        <span>{{ __('portal.ug_venue') }}: {{ $batch->localized_location }} ({{ __('portal.ug_org') }}: <strong class="text-[#2C3E2D]">{{ app()->getLocale() === 'en' ? ($batch->organizationUnit->name_en ?? $batch->organizationUnit->name_th ?? __('portal.mcu_short')) : ($batch->organizationUnit->name_th ?? __('portal.mcu_short')) }}</strong>)</span>
                                    </div>
                                    <div class="text-xs text-[#5A6B47] font-medium mt-1 flex items-center gap-1.5">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#5A6B47] shrink-0"></i>
                                        <span>{{ __('portal.ug_dates') }}: {{ $batch->start_date }} {{ app()->getLocale() === 'en' ? 'to' : 'ถึง' }} {{ $batch->end_date }} ({{ __('portal.ug_course_days') }})</span>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="text-center py-6 text-[#8C8275] bg-[#F7F5EE] rounded-xl border border-dashed border-[#D5CEBC]">
                                <div>{{ __('portal.ug_no_batch_org') }}</div>
                            </div>
                        @endforelse

                        <div id="no-batch-alert" style="display:none;" class="text-center py-8 text-[#8C8275] bg-[#F7F5EE] rounded-xl border border-dashed border-[#D5CEBC]">
                            <i data-lucide="calendar-x" class="w-8 h-8 mx-auto mb-2 text-[#C86D51]"></i>
                            <div class="font-semibold text-[#2C3E2D]">{{ __('portal.ug_no_batch_org') }}</div>
                            <p class="text-xs text-[#7B8D65] mt-1">{{ __('portal.ug_contact_staff') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Contact & Emergency -->
                <div>
                    <h2 class="text-lg font-heading font-semibold text-[#2C3E2D] flex items-center border-b border-[#E3DEC9] pb-3 mb-4">
                        <span class="w-6 h-6 rounded-full bg-[#EAE5D9] text-[#4A3B32] text-xs font-bold flex items-center justify-center mr-2">3</span>
                        {{ __('portal.ug_step3_title') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-[#4A3B32] mb-1">{{ __('portal.ug_phone') }}</label>
                            <input type="tel" id="input_phone" name="phone" placeholder="08xxxxxxxx" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#4A3B32] mb-1">{{ __('portal.ug_email') }}</label>
                            <input type="text" inputmode="email" id="input_email" name="email" placeholder="student@mcu.ac.th" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#4A3B32] mb-1">{{ __('portal.ug_health') }}</label>
                            <textarea name="health_conditions" rows="2" placeholder="เช่น หอบหืด, ความดัน, แพ้อาหาร..." class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#4A3B32] mb-1">{{ __('portal.ug_emergency') }}</label>
                            <textarea name="emergency_contact" rows="2" placeholder="เช่น บิดา/มารดา หรือ อาจารย์ที่ปรึกษา โทร. 08xxxxxxxx" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-[#E3DEC9] flex flex-wrap items-center justify-between gap-3">
                    <button type="button" onclick="resetForm()" class="px-4 py-2.5 text-xs text-[#A85238] hover:text-white hover:bg-[#C86D51] border border-[#D5CEBC] rounded-lg font-medium transition flex items-center gap-1.5">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>{{ __('portal.ug_btn_reset_all') }}</span>
                    </button>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('home') }}" class="px-5 py-2.5 text-[#6B6357] hover:text-[#2C3E2D] text-sm font-medium">{{ __('portal.ug_btn_cancel') }}</a>
                        <button type="submit" id="btn-submit-reg" class="bg-[#2C3E2D] hover:bg-[#3D523E] text-[#F7F4EA] font-medium px-6 py-2.5 rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-[#A3B88C]"></i>
                            <span>{{ __('portal.ug_btn_submit') }}</span>
                        </button>
                    </div>
                </div>
            </form>

        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-[#FAF8F2] border-t border-[#E3DEC9] py-6 text-center text-xs text-[#8C8275]">
        {{ __('portal.footer_brand') }}
    </footer>

    <script>
        lucide.createIcons();

        let verifiedStudent = null;
        const batchLabels = document.querySelectorAll('.batch-card-label');
        const emptyAlert = document.getElementById('no-batch-alert');
        const feedbackBox = document.getElementById('lookup-feedback');
        const studentInfoCard = document.getElementById('student-info-card');
        const hintText = document.getElementById('batch-hint-text');

        function filterBatchesByOrg(orgId, orgName) {
            let visibleCount = 0;

            batchLabels.forEach(label => {
                const batchOrgId = label.getAttribute('data-org-id');
                const radio = label.querySelector('input[type="radio"]');

                if (!orgId || String(batchOrgId) === String(orgId)) {
                    label.style.display = 'flex';
                    visibleCount++;
                } else {
                    label.style.display = 'none';
                    if (radio.checked) {
                        radio.checked = false;
                    }
                }
            });

            if (emptyAlert) {
                emptyAlert.style.display = visibleCount === 0 ? 'block' : 'none';
            }

            if (hintText && orgName) {
                hintText.innerHTML = `แสดงเฉพาะกำหนดการโครงการของ <strong>${orgName}</strong> (ตรงตามส่วนจัดการศึกษาต้นสังกัด)`;
            }
        }

        async function lookupStudentCode() {
            const codeInput = document.getElementById('input-student-code');
            const code = codeInput.value.trim();

            if (!code) {
                alert('กรุณากรอกรหัสนิสิต');
                codeInput.focus();
                return;
            }

            const btn = document.getElementById('btn-lookup-student');
            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> <span>กำลังตรวจสอบ...</span>`;
            lucide.createIcons();

            try {
                const res = await fetch(`{{ route('ug.lookupStudent') }}?student_code=` + encodeURIComponent(code));
                const data = await res.json();

                feedbackBox.classList.remove('hidden');

                if (data.found) {
                    verifiedStudent = data.student;
                    feedbackBox.className = "mt-3 bg-[#5A6B47]/10 border border-[#5A6B47]/30 text-[#2C3E2D] p-3 rounded-xl text-xs flex items-center gap-2";
                    feedbackBox.innerHTML = `<i data-lucide="check-circle" class="w-4 h-4 text-[#5A6B47]"></i> <span>พบข้อมูลนิสิตในระบบ</span>`;

                    // Populate hidden fields
                    document.getElementById('hidden_citizen_id').value = verifiedStudent.citizen_id || '0000000000000';
                    document.getElementById('hidden_prefix').value = verifiedStudent.prefix || 'พระ';
                    document.getElementById('hidden_first_name').value = verifiedStudent.first_name || '';
                    document.getElementById('hidden_last_name').value = verifiedStudent.last_name || '';
                    document.getElementById('hidden_org_unit_id').value = verifiedStudent.org_unit_id;
                    document.getElementById('hidden_study_year').value = verifiedStudent.study_year || 1;

                    if (verifiedStudent.phone) {
                        document.getElementById('input_phone').value = verifiedStudent.phone;
                    }
                    if (verifiedStudent.email) {
                        document.getElementById('input_email').value = verifiedStudent.email;
                    }

                    // Populate display info card
                    let nameTitle = (verifiedStudent.prefix || '') + verifiedStudent.first_name + ' ' + (verifiedStudent.last_name || '');
                    if (verifiedStudent.chaya) {
                        nameTitle += ' (' + verifiedStudent.chaya + ')';
                    }
                    document.getElementById('disp-student-name').innerText = nameTitle;
                    document.getElementById('disp-degree-level').innerText = verifiedStudent.degree_level || 'ปริญญาตรี';
                    document.getElementById('disp-faculty').innerText = verifiedStudent.faculty || '-';
                    document.getElementById('disp-major').innerText = verifiedStudent.major || '-';
                    document.getElementById('disp-study-year').innerText = verifiedStudent.study_year || '1';
                    document.getElementById('disp-org-name').innerText = verifiedStudent.org_unit_name;

                    studentInfoCard.classList.remove('hidden');

                    // Filter batches strictly to student's org
                    filterBatchesByOrg(verifiedStudent.org_unit_id, verifiedStudent.org_unit_name);

                } else {
                    verifiedStudent = null;
                    feedbackBox.className = "mt-3 bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl text-xs flex items-center gap-2";
                    feedbackBox.innerHTML = `<i data-lucide="alert-triangle" class="w-4 h-4 text-red-600"></i> <span>${data.message}</span>`;
                    studentInfoCard.classList.add('hidden');
                }
            } catch (err) {
                feedbackBox.classList.remove('hidden');
                feedbackBox.className = "mt-3 bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl text-xs flex items-center gap-2";
                feedbackBox.innerHTML = `<i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i> <span>เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล กรุณาลองใหม่อีกครั้ง</span>`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<i data-lucide="user-check" class="w-4 h-4"></i> <span>ตรวจสอบข้อมูล</span>`;
                lucide.createIcons();
            }
        }

        function resetForm() {
            // Clear search input
            const codeInput = document.getElementById('input-student-code');
            if (codeInput) {
                codeInput.value = '';
                codeInput.focus();
            }

            // Reset feedback & info card
            if (feedbackBox) {
                feedbackBox.className = 'mt-4 hidden';
                feedbackBox.innerHTML = '';
            }
            if (studentInfoCard) {
                studentInfoCard.classList.add('hidden');
            }

            // Clear hidden inputs
            document.getElementById('hidden_citizen_id').value = '';
            document.getElementById('hidden_prefix').value = '';
            document.getElementById('hidden_first_name').value = '';
            document.getElementById('hidden_last_name').value = '';
            document.getElementById('hidden_org_unit_id').value = '';
            document.getElementById('hidden_study_year').value = '1';

            // Clear contact inputs
            const phoneInput = document.getElementById('input_phone');
            if (phoneInput) phoneInput.value = '';
            const emailInput = document.getElementById('input_email');
            if (emailInput) emailInput.value = '';
            const healthArea = document.querySelector('textarea[name="health_conditions"]');
            if (healthArea) healthArea.value = '';
            const emContact = document.querySelector('textarea[name="emergency_contact"]');
            if (emContact) emContact.value = '';

            // Reset verified student state
            verifiedStudent = null;

            // Reset batch filters to initial view (show all)
            filterBatchesByOrg(null, null);
            if (hintText) {
                hintText.innerHTML = 'กรุณาตรวจสอบรหัสนิสิตในขั้นตอนที่ 1 ระบบจะแสดงกำหนดการปฏิบัติธรรมเฉพาะส่วนงานต้นสังกัดของท่านโดยอัตโนมัติ';
            }

            // Uncheck any selected batch and scroll list back to top
            document.querySelectorAll('input[name="batch_id"]').forEach(radio => radio.checked = false);
            const batchContainer = document.getElementById('batch-list-container');
            if (batchContainer) {
                batchContainer.scrollTop = 0;
            }

            lucide.createIcons();
        }

        // Enter key in input triggers lookup
        document.getElementById('input-student-code')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                lookupStudentCode();
            }
        });

        // Function to download QR code image
        function downloadQRCode(regNo) {
            const qrImg = document.getElementById('qr-image');
            if (!qrImg) return;

            // Fetch the image as blob to allow direct file download
            fetch(qrImg.src)
                .then(response => response.blob())
                .then(blob => {
                    const blobUrl = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = blobUrl;
                    link.download = `QR-VPSMCU-${regNo}.png`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(blobUrl);
                })
                .catch(() => {
                    // Fallback using direct link
                    const link = document.createElement('a');
                    link.href = qrImg.src;
                    link.target = '_blank';
                    link.download = `QR-VPSMCU-${regNo}.png`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
        }
    </script>
</body>
</html>
