<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('portal.public_check_header_title') }} - VPSMCU</title>
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
        .hero-banner-card {
            background-image: linear-gradient(to right, rgba(21, 87, 36, 0.96) 0%, rgba(21, 87, 36, 0.88) 50%, rgba(21, 87, 36, 0.20) 82%, transparent 100%), url('{{ asset("images/hero2image.png") }}');
            background-size: cover;
            background-position: right 15%;
            background-repeat: no-repeat;
            min-height: 220px;
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Navigation -->
    <nav class="bg-[#FAF8F2]/90 backdrop-blur-md border-b border-[#EAE5D9] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-10 h-10 object-contain drop-shadow-sm">
                    <div>
                        <div class="font-heading font-bold text-[#2C3E2D] leading-tight">VPSMCU</div>
                        <div class="text-xs text-[#7B8D65]">{{ __('portal.university_name') }}</div>
                    </div>
                </a>

                <!-- Module 3 Navigation Tabs: สมัครคอร์ส vs ตรวจสอบสถานะ (Active) -->
                <div class="hidden sm:flex items-center bg-[#EAE5D9]/80 p-1 rounded-2xl border border-[#D5CEBC]">
                    <a href="{{ route('public.register') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#FAF8F2]">
                        <i data-lucide="user-plus" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>{{ __('portal.public_tab_register') }}</span>
                    </a>
                    <a href="{{ route('public.check') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 bg-[#5A6B47] text-white shadow-sm">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>{{ __('portal.public_tab_check') }}</span>
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

                    <a href="{{ route('home') }}" class="text-[#4A3B32] hover:text-[#5A6B47] font-semibold text-[15px] flex items-center gap-1.5 transition">
                        <i data-lucide="arrow-left" class="w-4.5 h-4.5 text-[#5A6B47]"></i> <span class="hidden sm:inline">{{ __('portal.nav_back_home') }}</span>
                    </a>
                </div>
            </div>

            <!-- Mobile Sub-Navigation -->
            <div class="flex sm:hidden items-center justify-center pb-3 pt-1 border-t border-[#EAE5D9] gap-2">
                <a href="{{ route('public.register') }}" class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-bold bg-white text-[#4A3B32] border border-[#D5CEBC]">
                    {{ __('portal.public_tab_register') }}
                </a>
                <a href="{{ route('public.check') }}" class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-bold bg-[#5A6B47] text-white">
                    {{ __('portal.public_tab_check') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- Header Banner Card (Hero Image Matching public_register) -->
        <div class="hero-banner-card rounded-2xl p-6 sm:p-9 text-white shadow-sm border border-[#205C29]/40 relative overflow-hidden mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/15 backdrop-blur-sm rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-white/20 text-[#FAF8F2]">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#EAE5D9]"></i>
                    <span>{{ __('portal.public_check_header_badge') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-white tracking-tight leading-snug mb-2">{{ __('portal.public_check_header_title') }}</h1>
                <p class="text-xs sm:text-sm text-white/90 leading-relaxed">
                    {{ __('portal.public_check_header_desc') }}
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('public.register') }}" class="inline-flex items-center gap-2 bg-white hover:bg-[#FAF8F2] text-[#2C3E2D] px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm transition shadow-md">
                    <i data-lucide="user-plus" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>{{ __('portal.public_tab_register') }}</span>
                </a>
            </div>
        </div>

        <!-- Search Box (ค้นหาด้วยเบอร์โทรศัพท์ หรือรหัสการสมัคร) -->
        <div class="bg-white border border-[#E3DEC9] rounded-2xl p-6 shadow-sm mb-8">
            <form action="{{ route('public.check') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <i data-lucide="phone" class="w-4 h-4 text-[#8C8275] absolute left-3.5 top-3.5"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('portal.public_check_search_ph') }}" class="w-full pl-10 pr-4 py-2.5 border border-[#D5CEBC] rounded-xl text-sm focus:ring-2 focus:ring-[#5A6B47] font-mono bg-[#FAF8F2] text-[#2C3E2D]">
                </div>
                <button type="submit" class="bg-[#2C3E2D] hover:bg-[#3D523E] text-white font-medium px-6 py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2 shadow-sm">
                    <i data-lucide="search" class="w-4 h-4 text-[#A3B88C]"></i>
                    <span>{{ __('portal.public_check_btn_search') }}</span>
                </button>
            </form>
            <div class="text-xs text-[#8C8275] mt-2.5 flex items-center gap-1.5">
                <i data-lucide="lightbulb" class="w-3.5 h-3.5 text-[#C86D51]"></i>
                <span>{{ __('portal.public_check_sample_hint') }} <a href="{{ route('public.check', ['search' => '0818889999']) }}" class="text-[#C86D51] underline font-mono">0818889999</a> หรือ <a href="{{ route('public.check', ['search' => 'PUB-20261023-0001']) }}" class="text-[#C86D51] underline font-mono">PUB-20261023-0001</a></span>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border-l-4 border-[#2C7338] p-4 rounded-xl shadow-xs mb-6">
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-[#2C7338] mr-2 shrink-0"></i>
                    <p class="text-xs sm:text-sm text-[#244E38] font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-xs mb-6">
                <div class="flex items-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mr-2 shrink-0"></i>
                    <p class="text-xs sm:text-sm text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($search)
            @if ($registrations->count() > 0)
                @php
                    $firstReg = $registrations->first();
                @endphp
                <!-- Applicant Profile Card -->
                <div class="bg-white border border-[#E3DEC9] rounded-2xl p-6 shadow-sm mb-8">
                    <div class="flex items-center gap-2 border-b border-[#E3DEC9] pb-3 mb-4">
                        <i data-lucide="user" class="w-5 h-5 text-[#5A6B47]"></i>
                        <h2 class="text-base font-heading font-bold text-[#2C3E2D]">{{ __('portal.public_check_profile_title') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[11px] text-[#7B8D65]">{{ __('portal.public_check_profile_name') }}</div>
                            <div class="text-base font-bold text-[#2C3E2D]">{{ $firstReg->full_name }}</div>
                            <div class="text-xs text-[#6B6357] mt-0.5">
                                {{ $firstReg->applicant_type === 'STUDENT' ? __('portal.public_status_student') : __('portal.public_status_people') }} 
                                &bull; {{ __('portal.public_check_profile_gender') }} {{ $firstReg->gender === 'FEMALE' ? __('portal.public_gender_female') : ($firstReg->gender === 'MALE' ? __('portal.public_gender_male') : '-') }}
                            </div>
                            @if ($firstReg->applicant_type === 'STUDENT')
                                <div class="mt-2 pt-2 border-t border-[#EAE5D9] text-[11px] space-y-0.5 text-[#5A6B47]">
                                    <div>{{ __('portal.public_check_profile_student_code') }} <strong class="font-mono text-[#2C3E2D]">{{ $firstReg->student_id ?: '-' }}</strong></div>
                                    @if ($firstReg->degree_level || $firstReg->faculty)
                                        <div>{{ __('portal.public_check_profile_degree') }} {{ $firstReg->degree_level ?: '-' }} &bull; {{ __('portal.public_check_profile_faculty') }} {{ $firstReg->faculty ?: '-' }}</div>
                                    @endif
                                    @if ($firstReg->program_name)
                                        <div>{{ __('portal.public_check_profile_program') }} {{ $firstReg->program_name }}</div>
                                    @endif
                                    @if ($firstReg->organizationUnit)
                                        <div class="text-[#7B8D65]">{{ __('portal.public_check_profile_org') }} {{ $firstReg->organizationUnit->name_th }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[11px] text-[#7B8D65]">{{ __('portal.public_check_profile_phone') }}</div>
                            <div class="text-base font-mono font-bold text-[#2C3E2D]">{{ $firstReg->phone }}</div>
                            <div class="text-xs text-[#6B6357] mt-0.5">
                                {{ __('portal.public_check_profile_province') }} {{ $firstReg->province ?? '-' }}
                            </div>
                        </div>

                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[11px] text-[#7B8D65]">{{ __('portal.public_check_profile_food') }}</div>
                            <div class="text-sm font-semibold text-[#C86D51]">{{ $firstReg->localized_dietary }}</div>
                            <div class="text-xs text-[#6B6357] mt-0.5">
                                {{ __('portal.public_check_profile_special') }} {{ ($firstReg->congenital_disease && $firstReg->congenital_disease !== '-') ? $firstReg->congenital_disease : __('portal.public_none') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration History Table -->
                <div class="bg-white border border-[#E3DEC9] rounded-2xl p-6 shadow-sm mb-8">
                    <div class="flex items-center justify-between border-b border-[#E3DEC9] pb-3 mb-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="clipboard-check" class="w-5 h-5 text-[#5A6B47]"></i>
                            <h2 class="text-base font-heading font-bold text-[#2C3E2D]">
                                <span class="sm:hidden">ประวัติการสมัครคอร์ส</span>
                                <span class="hidden sm:inline">{{ __('portal.public_check_courses_title') }}</span>
                            </h2>
                        </div>
                        <span class="text-xs font-mono px-2.5 py-1 rounded-full bg-[#FAF8F2] border border-[#EAE5D9] text-[#7B8D65]">
                            {{ $registrations->count() }} {{ __('portal.grad_records_unit') }}
                        </span>
                    </div>

                    <!-- 1. Desktop Table View (Hidden on mobile < md) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[#EAE5D9] text-[11px] font-semibold text-[#7B8D65] uppercase tracking-wider bg-[#FAF8F2]">
                                    <th class="py-3 px-4">{{ __('portal.public_check_th_reg_no') }}</th>
                                    <th class="py-3 px-4">{{ __('portal.public_check_th_course') }}</th>
                                    <th class="py-3 px-4">{{ __('portal.public_check_th_dates') }}</th>
                                    <th class="py-3 px-4 text-center">{{ __('portal.public_check_th_queue') }}</th>
                                    <th class="py-3 px-4">{{ __('portal.public_check_th_status') }}</th>
                                    <th class="py-3 px-4 text-center">{{ __('portal.public_check_th_action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EAE5D9] text-xs">
                                @foreach ($registrations as $reg)
                                    <tr class="hover:bg-[#FAF8F2]/60 transition">
                                        <td class="py-3.5 px-4 font-mono font-bold text-[#2C3E2D]">
                                            {{ $reg->registration_no }}
                                            <div class="text-[10px] text-[#8C8275] font-normal">
                                                {{ $reg->registered_at ? date('d/m/Y H:i', strtotime($reg->registered_at)) : '-' }}
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="font-medium text-[#2C3E2D]">
                                                {{ $reg->event->title ?? '-' }}
                                            </div>
                                            <div class="text-[11px] text-[#7B8D65]">
                                                {{ $reg->event->location ?? ($reg->event->organizationUnit->name_th ?? '') }}
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap text-[#4A3B32]">
                                            @if ($reg->event && $reg->event->start_date)
                                                @php
                                                    $sDate = \Carbon\Carbon::parse($reg->event->start_date);
                                                    $eDate = \Carbon\Carbon::parse($reg->event->end_date);
                                                @endphp
                                                {{ $sDate->format('d/m/') . ($sDate->year + 543) }} - {{ $eDate->format('d/m/') . ($eDate->year + 543) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-mono font-bold text-[#5A6B47]">
                                            #{{ $reg->queue_no ?? '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if ($reg->status === 'CONFIRMED')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#E9EFE2] text-[#3D523E] border border-[#CADBC0]">
                                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                                    {{ __('portal.public_status_confirmed') }}
                                                </span>
                                            @elseif ($reg->status === 'WAITING_LIST')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                    <i data-lucide="hourglass" class="w-3.5 h-3.5 text-amber-600"></i>
                                                    {{ __('portal.public_status_waiting') }}
                                                </span>
                                            @elseif ($reg->status === 'REJECTED')
                                                <div>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                                        <i data-lucide="x-circle" class="w-3.5 h-3.5 text-red-600"></i>
                                                        {{ __('portal.public_status_rejected') }}
                                                    </span>
                                                    @if ($reg->reject_reason)
                                                        <div class="text-[10px] text-red-600 mt-1 max-w-[180px] break-words">
                                                            * {{ $reg->reject_reason }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-600"></i>
                                                    {{ __('portal.public_status_pending') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- Edit Button -->
                                                @if (in_array($reg->status, ['PENDING', 'REJECTED']))
                                                    <button type="button" onclick="openEditModal({{ json_encode($reg) }})" class="bg-white hover:bg-amber-50 text-amber-700 border border-amber-300 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 shadow-2xs" title="{{ __('portal.public_check_btn_edit') }}">
                                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-600"></i>
                                                        <span>{{ __('portal.public_check_btn_edit') }}</span>
                                                    </button>
                                                @endif

                                                <!-- Print Slip Button -->
                                                <button type="button" onclick="openSlipModal('{{ $reg->registration_no }}', '{{ $reg->full_name }}', '{{ $reg->event->title ?? '' }}', '{{ $reg->queue_no }}', '{{ $reg->status }}', '{{ $reg->localized_dietary }}')" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#2C3E2D] border border-[#D5CEBC] px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5 shadow-2xs">
                                                    <i data-lucide="printer" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                                    <span>{{ __('portal.public_btn_print_slip') }}</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- 2. Mobile Card List View (Block on < md, beautiful stacked layout) -->
                    <div class="block md:hidden space-y-4">
                        @foreach ($registrations as $reg)
                            @php
                                $sDate = ($reg->event && $reg->event->start_date) ? \Carbon\Carbon::parse($reg->event->start_date) : null;
                                $eDate = ($reg->event && $reg->event->end_date) ? \Carbon\Carbon::parse($reg->event->end_date) : null;
                                $dateStr = ($sDate && $eDate) ? ($sDate->format('d/m/') . ($sDate->year + 543) . ' - ' . $eDate->format('d/m/') . ($eDate->year + 543)) : '-';
                            @endphp
                            <div class="bg-[#FAF8F2] border border-[#EAE5D9] rounded-2xl p-4 space-y-3.5 shadow-xs">
                                
                                <!-- Card Header: Reg No + Status Badge -->
                                <div class="flex items-center justify-between gap-2 border-b border-[#EAE5D9] pb-2.5">
                                    <div>
                                        <div class="text-[10px] text-[#7B8D65]">รหัสการสมัคร</div>
                                        <div class="font-mono font-bold text-sm text-[#C86D51]">{{ $reg->registration_no }}</div>
                                    </div>
                                    <div>
                                        @if ($reg->status === 'CONFIRMED')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#E9EFE2] text-[#3D523E] border border-[#CADBC0]">
                                                <i data-lucide="check-circle" class="w-3 h-3 text-[#5A6B47]"></i>
                                                {{ __('portal.public_status_confirmed') }}
                                            </span>
                                        @elseif ($reg->status === 'WAITING_LIST')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                <i data-lucide="hourglass" class="w-3 h-3 text-amber-600"></i>
                                                {{ __('portal.public_status_waiting') }}
                                            </span>
                                        @elseif ($reg->status === 'REJECTED')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                                <i data-lucide="x-circle" class="w-3 h-3 text-red-600"></i>
                                                {{ __('portal.public_status_rejected') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <i data-lucide="clock" class="w-3 h-3 text-blue-600"></i>
                                                {{ __('portal.public_status_pending') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Course Title & Queue -->
                                <div class="space-y-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-heading font-bold text-sm text-[#2C3E2D] leading-snug break-words flex-grow">
                                            {{ $reg->event->title ?? '-' }}
                                        </h3>
                                        <span class="shrink-0 bg-[#E8F3EA] text-[#2C7338] border border-[#D2E7D5] font-mono font-bold text-xs px-2 py-0.5 rounded-lg">
                                            คิว #{{ $reg->queue_no ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-[#7B8D65] flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-[#C86D51] shrink-0"></i>
                                        <span class="break-words">{{ $reg->event->location ?? ($reg->event->organizationUnit->name_th ?? '-') }}</span>
                                    </div>
                                    <div class="text-[11px] text-[#6B6357] flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3 text-[#7B8D65] shrink-0"></i>
                                        <span>{{ $dateStr }}</span>
                                    </div>
                                </div>

                                @if ($reg->status === 'REJECTED' && $reg->reject_reason)
                                    <div class="p-2 rounded-lg bg-red-50 border border-red-200 text-[11px] text-red-700">
                                        <strong>เหตุผล:</strong> {{ $reg->reject_reason }}
                                    </div>
                                @endif

                                <!-- Meta & Action Buttons -->
                                <div class="pt-2 border-t border-[#EAE5D9] flex items-center justify-between gap-2">
                                    <div class="text-[10px] text-[#8C8275]">
                                        {{ $reg->registered_at ? date('d/m/Y H:i', strtotime($reg->registered_at)) : '-' }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if (in_array($reg->status, ['PENDING', 'REJECTED']))
                                            <button type="button" onclick="openEditModal({{ json_encode($reg) }})" class="bg-white hover:bg-amber-50 text-amber-700 border border-amber-300 px-3 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1 shadow-2xs">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-600"></i>
                                                <span>{{ __('portal.public_check_btn_edit') }}</span>
                                            </button>
                                        @endif

                                        <button type="button" onclick="openSlipModal('{{ $reg->registration_no }}', '{{ $reg->full_name }}', '{{ $reg->event->title ?? '' }}', '{{ $reg->queue_no }}', '{{ $reg->status }}', '{{ $reg->localized_dietary }}')" class="bg-white hover:bg-[#FAF8F2] text-[#2C3E2D] border border-[#D5CEBC] px-3 py-1.5 rounded-xl text-xs font-medium transition flex items-center gap-1 shadow-2xs">
                                            <i data-lucide="printer" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                            <span>{{ __('portal.public_btn_print_slip') }}</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- No Result Found Card -->
                <div class="text-center py-10 bg-white rounded-2xl border border-[#E3DEC9] p-8 shadow-sm">
                    <i data-lucide="phone-missed" class="w-12 h-12 text-[#8C8275] mx-auto mb-3"></i>
                    <h3 class="text-base font-heading font-bold text-[#2C3E2D]">{{ __('portal.public_check_no_result') }}</h3>
                    <p class="text-xs text-[#8C8275] mt-1 max-w-md mx-auto">
                        {{ __('portal.public_check_no_result_desc') }}
                    </p>
                    <a href="{{ route('public.register') }}" class="mt-4 inline-flex items-center gap-1.5 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-5 py-2.5 rounded-xl text-xs font-semibold shadow-sm transition">
                        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                        <span>{{ __('portal.public_tab_register') }}</span>
                    </a>
                </div>
            @endif
        @else
            <!-- Initial Instruction Card -->
            <div class="bg-white border border-[#E3DEC9] rounded-2xl p-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-[#FAF8F2] text-[#5A6B47] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#EAE5D9]">
                    <i data-lucide="search" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-heading font-bold text-[#2C3E2D] mb-1">{{ __('portal.public_check_init_title') }}</h3>
                <p class="text-xs text-[#6B6357] max-w-md mx-auto leading-relaxed">
                    {{ __('portal.public_check_init_desc') }}
                </p>
            </div>
        @endif

    </main>

    <!-- Modal: Edit Registration (แก้ไขข้อมูลใบสมัคร) -->
    <div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 text-left shadow-2xl border border-[#EAE5D9] relative my-8">
            <button type="button" onclick="closeEditModal()" class="absolute right-5 top-5 text-[#8C8275] hover:text-[#2C3E2D] transition p-1.5 rounded-full bg-[#FAF8F2]">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div class="pb-4 border-b border-[#EAE5D9] mb-5">
                <div class="flex items-center gap-2 text-[#2C3E2D]">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-heading font-bold">{{ __('portal.public_check_edit_title') }}</h3>
                        <p class="text-xs text-[#7B8D65]">{{ __('portal.public_check_edit_code') }} <span id="editRegNoLabel" class="font-mono font-bold text-[#2C3E2D]">-</span></p>
                    </div>
                </div>
            </div>

            <form id="editRegistrationForm" method="POST" action="{{ url('/public_check.php') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="id" id="edit_reg_id">

                <!-- Status Selector Radio Box -->
                <div class="p-3.5 rounded-xl bg-[#FBF9F4] border border-[#EFECE5]">
                    <div class="text-xs text-[#7A7367] mb-2 font-medium">สถานะผู้สมัคร (Applicant Status)</div>
                    <div class="flex flex-wrap gap-6 text-xs text-[#2D2A26]">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="applicant_type" id="edit_app_type_people" value="PEOPLE" class="w-4 h-4 text-[#2C7338] focus:ring-[#2C7338]" onchange="toggleEditStudentField(this.value)">
                            <span>ประชาชนทั่วไป (General Public)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="applicant_type" id="edit_app_type_student" value="STUDENT" class="w-4 h-4 text-[#2C7338] focus:ring-[#2C7338]" onchange="toggleEditStudentField(this.value)">
                            <span>นิสิต มจร (MCU Student)</span>
                        </label>
                    </div>
                </div>

                <!-- Academic Info Container (Collapsible) -->
                <div id="editStudentContainer" class="hidden p-3.5 rounded-xl bg-[#FBF9F4] border border-[#EFECE5] space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#2C7338]">
                        <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                        <span>ข้อมูลการศึกษาใน มจร</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[#6B6357] mb-1">รหัสนิสิต มจร</label>
                            <input type="text" name="student_id" id="edit_student_id" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-[#6B6357] mb-1">ระดับการศึกษา</label>
                            <select name="degree_level" id="edit_degree_level" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                <option value="">-- เลือกระดับ --</option>
                                <option value="ปริญญาตรี">ปริญญาตรี</option>
                                <option value="ปริญญาโท">ปริญญาโท</option>
                                <option value="ปริญญาเอก">ปริญญาเอก</option>
                                <option value="ประกาศนียบัตร">ประกาศนียบัตร</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[#6B6357] mb-1">คณะ</label>
                            <input type="text" name="faculty" id="edit_faculty" placeholder="เช่น พุทธศาสตร์" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block text-[#6B6357] mb-1">หลักสูตร / สาขา</label>
                            <input type="text" name="program_name" id="edit_program_name" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-3">
                        <label class="block text-[#6B6357] mb-1">คำนำหน้าชื่อ <span class="text-red-500">*</span></label>
                        <input type="text" name="prefix" id="edit_prefix" list="edit_prefix_datalist" required placeholder="เช่น นาย, พระ, นางสาว" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                        <datalist id="edit_prefix_datalist">
                            <option value="นาย">
                            <option value="นาง">
                            <option value="นางสาว">
                            <option value="พระ">
                            <option value="พระมหา">
                            <option value="พระครู">
                            <option value="พระอธิการ">
                            <option value="สามเณร">
                            <option value="แม่ชี">
                            <option value="อุบาสก">
                            <option value="อุบาสิกา">
                        </datalist>
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block text-[#6B6357] mb-1">ชื่อจริง <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" id="edit_first_name" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                    </div>
                    <div class="sm:col-span-5">
                        <label class="block text-[#6B6357] mb-1">นามสกุล <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" id="edit_last_name" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                    </div>

                    <div class="sm:col-span-4">
                        <label class="block text-[#6B6357] mb-1">ฉายา (เฉพาะพระภิกษุ)</label>
                        <input type="text" name="buddhist_name" id="edit_buddhist_name" placeholder="เช่น ญาณสํวโร" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[#6B6357] mb-1">พรรษา</label>
                        <input type="number" name="vassa" id="edit_vassa" min="0" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[#6B6357] mb-1">อายุ (ปี)</label>
                        <input type="number" name="age" id="edit_age" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block text-[#6B6357] mb-1">เบอร์โทรศัพท์ติดต่อ <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" id="edit_phone" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs font-mono">
                    </div>

                    <div class="sm:col-span-6">
                        <label class="block text-[#6B6357] mb-1">อีเมล</label>
                        <input type="text" inputmode="email" name="email" id="edit_email" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                    </div>
                    <div class="sm:col-span-6">
                        <label class="block text-[#6B6357] mb-1">Line ID</label>
                        <input type="text" name="line_id" id="edit_line_id" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                    </div>
                </div>

                <!-- Address -->
                <div class="space-y-3 pt-2 border-t border-[#EAE5D9]">
                    <div>
                        <label class="block text-[#6B6357] mb-1">ที่อยู่ / วัดต้นสังกัด</label>
                        <input type="text" name="address" id="edit_address" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[#6B6357] mb-1">จังหวัด</label>
                            <select id="edit_province_select" name="province" onchange="onEditProvinceChange(this.value)" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                <option value="">-- เลือกจังหวัด --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[#6B6357] mb-1">อำเภอ / เขต</label>
                            <select id="edit_district_select" name="district" onchange="onEditDistrictChange(this.value)" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                <option value="">-- เลือกอำเภอ --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[#6B6357] mb-1">ตำบล / แขวง</label>
                            <select id="edit_subdistrict_select" name="subdistrict" onchange="onEditSubdistrictChange(this.value)" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                                <option value="">-- เลือกตำบล --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[#6B6357] mb-1">รหัสไปรษณีย์</label>
                            <input type="text" id="edit_postal_code" name="postal_code" maxlength="5" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs font-mono">
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-[#EAE5D9]">
                    <div>
                        <label class="block text-[#6B6357] mb-1">ห้องพัก / อาคาร</label>
                        <select name="room_info_select" id="edit_room_info_select" onchange="toggleEditCustomRoom(this.value)" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            <option value="พักรวม">พักรวม</option>
                            <option value="พักที่อาคาร 92 ปี">พักที่อาคาร 92 ปี</option>
                            <option value="พักที่อาคารพระพรหมวัชรธีราจารย์">พักที่อาคารพระพรหมวัชรธีราจารย์</option>
                            <option value="OTHER">อื่น ๆ ระบุ</option>
                        </select>
                        <input type="hidden" name="room_info" id="edit_room_info" value="พักรวม">
                        <div id="edit_room_custom_wrapper" class="hidden mt-2">
                            <input type="text" id="edit_room_custom" placeholder="ระบุข้อมูลห้องพัก / อาคารที่ต้องการ..." oninput="updateEditRoomInfoHidden()" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-lg text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[#6B6357] mb-1">การเดินทาง</label>
                        <select name="vehicle_info" id="edit_vehicle_info" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            <option value="แบบตัวเลือก - เดินทางไป-กลับเอง / ขึ้นรถตามที่สถาบันจัดให้">แบบตัวเลือก - เดินทางไป-กลับเอง / ขึ้นรถตามที่สถาบันจัดให้</option>
                            <option value="เดินทางโดยรถยนต์ส่วนตัว">เดินทางโดยรถยนต์ส่วนตัว</option>
                            <option value="เดินทางโดยรถตู้/รถบัสของสถาบัน">เดินทางโดยรถตู้/รถบัสของสถาบัน</option>
                            <option value="เดินทางโดยรถโดยสารสาธารณะ">เดินทางโดยรถโดยสารสาธารณะ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[#6B6357] mb-1">ประเภทอาหาร <span class="text-red-500">*</span></label>
                        <select name="food_type" id="edit_food_type" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                            <option value="NORMAL">อาหารทั่วไป</option>
                            <option value="VEGETARIAN">มังสวิรัติ (Vegetarian)</option>
                            <option value="JAY">อาหารเจ</option>
                            <option value="HALAL">ฮาลาล / มุสลิม</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[#6B6357] mb-1">ความต้องการพิเศษ / ข้อจำกัด</label>
                        <input type="text" name="special_needs" id="edit_special_needs" placeholder="เช่น ขอพักชั้นล่าง..." class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-lg text-xs">
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-[#D5CEBC] text-[#4A3B32] hover:bg-[#FAF8F2] font-semibold transition">
                        ยกเลิก
                    </button>
                    <button type="submit" class="bg-[#2C7338] hover:bg-[#235D2E] text-white px-5 py-2 rounded-xl font-semibold shadow-xs transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>บันทึกการแก้ไข</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Slip Modal -->
    <div id="slipModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 text-left shadow-2xl border border-[#EAE5D9] relative animate-fade-in" id="printArea">
            <button type="button" onclick="closeSlipModal()" class="absolute right-4 top-4 text-[#8C8275] hover:text-[#2C3E2D] transition p-1 rounded-full bg-[#FAF8F2]">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            
            <div class="text-center pb-4 border-b border-[#EAE5D9]">
                <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-12 h-12 mx-auto mb-2 object-contain">
                <h3 class="text-base font-heading font-bold text-[#2C3E2D]">{{ __('portal.public_check_slip_mcu_title') }}</h3>
                <p class="text-xs text-[#7B8D65]">{{ __('portal.public_check_slip_subtitle') }}</p>
            </div>

            <div class="my-4 space-y-2.5 text-xs">
                <div class="flex justify-between py-1 border-b border-[#FAF8F2]">
                    <span class="text-[#7B8D65]">{{ __('portal.public_check_slip_reg_code') }}</span>
                    <strong class="font-mono text-[#2C3E2D]" id="modalRegNo">-</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-[#FAF8F2]">
                    <span class="text-[#7B8D65]">{{ __('portal.public_check_slip_fullname') }}</span>
                    <strong class="text-[#2C3E2D]" id="modalName">-</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-[#FAF8F2]">
                    <span class="text-[#7B8D65]">{{ __('portal.public_check_slip_course') }}</span>
                    <strong class="text-[#2C3E2D] text-right" id="modalCourse">-</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-[#FAF8F2]">
                    <span class="text-[#7B8D65]">{{ __('portal.public_check_slip_queue') }}</span>
                    <strong class="font-mono text-[#5A6B47] text-sm" id="modalQueue">-</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-[#FAF8F2]">
                    <span class="text-[#7B8D65]">{{ __('portal.public_check_slip_status') }}</span>
                    <span id="modalStatus">-</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#7B8D65]">{{ __('portal.public_check_slip_food') }}</span>
                    <span id="modalFood" class="text-[#4A3B32]">-</span>
                </div>
            </div>

            <div class="p-3 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9] text-[11px] text-[#6B6357] leading-relaxed">
                {{ __('portal.public_check_slip_notice') }}
            </div>

            <div class="flex items-center gap-2 mt-5">
                <button type="button" onclick="window.print()" class="flex-1 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white text-xs font-semibold py-2.5 px-4 rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>{{ __('portal.public_check_slip_btn_print') }}</span>
                </button>
                <button type="button" onclick="closeSlipModal()" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#4A3B32] text-xs font-semibold py-2.5 px-4 rounded-xl border border-[#D5CEBC] transition">
                    {{ __('portal.public_check_slip_btn_close') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#FAF8F2] border-t border-[#E3DEC9] py-6 text-center text-xs text-[#8C8275]">
        {{ __('portal.footer_brand') }}
    </footer>

    <script>
        function openSlipModal(regNo, name, course, queue, status, food) {
            document.getElementById('modalRegNo').textContent = regNo;
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalCourse').textContent = course;
            document.getElementById('modalQueue').textContent = '#' + queue;
            document.getElementById('modalFood').textContent = food || 'อาหารทั่วไป';
            
            const statusEl = document.getElementById('modalStatus');
            if (status === 'CONFIRMED') {
                statusEl.innerHTML = '<strong class="text-emerald-700">อนุมัติสิทธิ์ (Confirmed)</strong>';
            } else if (status === 'WAITING_LIST') {
                statusEl.innerHTML = '<strong class="text-amber-700">รายชื่อสำรอง (Waiting List)</strong>';
            } else if (status === 'REJECTED') {
                statusEl.innerHTML = '<strong class="text-red-700">ไม่อนุมัติ (Rejected)</strong>';
            } else {
                statusEl.innerHTML = '<strong class="text-blue-700">รอตรวจสอบ (Pending)</strong>';
            }

            document.getElementById('slipModal').classList.remove('hidden');
        }

        function closeSlipModal() {
            document.getElementById('slipModal').classList.add('hidden');
        }

        // Edit Registration Engine
        let thaiProvinces = [];
        let thaiDistricts = [];
        let thaiSubdistricts = [];

        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const resProv = await fetch('/assets/data/provinces.json');
                thaiProvinces = await resProv.json();
                thaiProvinces.sort((a, b) => a.provinceNameTh.localeCompare(b.provinceNameTh, 'th'));

                const provSelect = document.getElementById('edit_province_select');
                provSelect.innerHTML = '<option value="">-- เลือกจังหวัด --</option>';
                thaiProvinces.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.provinceNameTh;
                    opt.textContent = p.provinceNameTh;
                    opt.dataset.provinceCode = p.provinceCode;
                    provSelect.appendChild(opt);
                });

                fetch('/assets/data/districts.json')
                    .then(r => r.json())
                    .then(data => { thaiDistricts = data; });

                fetch('/assets/data/subdistricts.json')
                    .then(r => r.json())
                    .then(data => { thaiSubdistricts = data; });
            } catch (err) {
                console.error('Failed to load address data', err);
            }
        });

        function toggleEditStudentField(val) {
            const container = document.getElementById('editStudentContainer');
            if (val === 'STUDENT') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }

        function openEditModal(reg) {
            document.getElementById('edit_reg_id').value = reg.id;
            document.getElementById('editRegNoLabel').textContent = reg.registration_no;

            // Type
            if (reg.applicant_type === 'STUDENT') {
                document.getElementById('edit_app_type_student').checked = true;
                toggleEditStudentField('STUDENT');
            } else {
                document.getElementById('edit_app_type_people').checked = true;
                toggleEditStudentField('PEOPLE');
            }

            // Student details
            document.getElementById('edit_student_id').value = reg.student_id || '';
            document.getElementById('edit_degree_level').value = reg.degree_level || '';
            document.getElementById('edit_faculty').value = reg.faculty || '';
            document.getElementById('edit_program_name').value = reg.program_name || '';
            const editOrgUnitEl = document.getElementById('edit_org_unit_id');
            if (editOrgUnitEl && reg.org_unit_id) {
                editOrgUnitEl.value = reg.org_unit_id;
            }

            // Personal
            document.getElementById('edit_prefix').value = reg.prefix || 'นาย';
            document.getElementById('edit_first_name').value = reg.first_name || '';
            document.getElementById('edit_last_name').value = reg.last_name || '';
            document.getElementById('edit_buddhist_name').value = reg.buddhist_name || '';
            document.getElementById('edit_age').value = reg.age || '';
            document.getElementById('edit_vassa').value = reg.vassa || 0;
            document.getElementById('edit_phone').value = reg.phone || '';
            document.getElementById('edit_email').value = reg.email || '';
            document.getElementById('edit_line_id').value = reg.line_id || '';

            // Address
            document.getElementById('edit_address').value = reg.address || '';
            document.getElementById('edit_postal_code').value = reg.postal_code || '';

            // Cascading address sync
            if (reg.province) {
                document.getElementById('edit_province_select').value = reg.province;
                onEditProvinceChange(reg.province, reg.district, reg.subdistrict);
            }

            // Preferences
            const stdRooms = ['พักรวม', 'พักที่อาคาร 92 ปี', 'พักที่อาคารพระพรหมวัชรธีราจารย์'];
            const roomVal = reg.room_info || 'พักรวม';
            const roomSelect = document.getElementById('edit_room_info_select');
            const roomCustomWrap = document.getElementById('edit_room_custom_wrapper');
            const roomCustomInput = document.getElementById('edit_room_custom');
            const roomHidden = document.getElementById('edit_room_info');

            if (stdRooms.includes(roomVal)) {
                roomSelect.value = roomVal;
                roomCustomWrap.classList.add('hidden');
                roomCustomInput.value = '';
                roomHidden.value = roomVal;
            } else {
                roomSelect.value = 'OTHER';
                roomCustomWrap.classList.remove('hidden');
                const cleanCustom = roomVal.replace(/^อื่น ๆ:\s*/, '').replace(/^อื่น ๆ\s*/, '');
                roomCustomInput.value = cleanCustom;
                roomHidden.value = roomVal;
            }

            if (reg.vehicle_info) document.getElementById('edit_vehicle_info').value = reg.vehicle_info;
            if (reg.dietary_restriction) {
                const fVal = reg.dietary_restriction;
                const fSelect = document.getElementById('edit_food_type');
                for (let i = 0; i < fSelect.options.length; i++) {
                    if (fSelect.options[i].value === fVal || fSelect.options[i].text.includes(fVal)) {
                        fSelect.selectedIndex = i;
                        break;
                    }
                }
            }
            document.getElementById('edit_special_needs').value = reg.congenital_disease || '';

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function toggleEditCustomRoom(val) {
            const customWrapper = document.getElementById('edit_room_custom_wrapper');
            const customInput = document.getElementById('edit_room_custom');
            const hiddenInput = document.getElementById('edit_room_info');
            if (val === 'OTHER') {
                customWrapper.classList.remove('hidden');
                customInput.focus();
                hiddenInput.value = customInput.value.trim() ? ('อื่น ๆ: ' + customInput.value.trim()) : 'อื่น ๆ';
            } else {
                customWrapper.classList.add('hidden');
                hiddenInput.value = val;
            }
        }

        function updateEditRoomInfoHidden() {
            const selectVal = document.getElementById('edit_room_info_select').value;
            const customInput = document.getElementById('edit_room_custom');
            const hiddenInput = document.getElementById('edit_room_info');
            if (selectVal === 'OTHER') {
                hiddenInput.value = customInput.value.trim() ? ('อื่น ๆ: ' + customInput.value.trim()) : 'อื่น ๆ';
            } else {
                hiddenInput.value = selectVal;
            }
        }

        function onEditProvinceChange(provinceName, selectDistrict = null, selectSubdistrict = null) {
            const distSelect = document.getElementById('edit_district_select');
            const subSelect = document.getElementById('edit_subdistrict_select');

            distSelect.innerHTML = '<option value="">-- เลือกอำเภอ --</option>';
            subSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';

            if (!provinceName) return;

            const provOption = document.querySelector(`#edit_province_select option[value="${provinceName}"]`);
            const provinceCode = provOption ? parseInt(provOption.dataset.provinceCode) : null;

            const filteredDistricts = thaiDistricts.filter(d => d.provinceCode === provinceCode);
            filteredDistricts.sort((a, b) => a.districtNameTh.localeCompare(b.districtNameTh, 'th'));

            filteredDistricts.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.districtNameTh;
                opt.textContent = d.districtNameTh;
                opt.dataset.districtCode = d.districtCode;
                distSelect.appendChild(opt);
            });

            if (selectDistrict) {
                distSelect.value = selectDistrict;
                onEditDistrictChange(selectDistrict, selectSubdistrict);
            }
        }

        function onEditDistrictChange(districtName, selectSubdistrict = null) {
            const subSelect = document.getElementById('edit_subdistrict_select');
            subSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';

            if (!districtName) return;

            const distOption = document.querySelector(`#edit_district_select option[value="${districtName}"]`);
            const districtCode = distOption ? parseInt(distOption.dataset.districtCode) : null;

            const filteredSubdistricts = thaiSubdistricts.filter(s => s.districtCode === districtCode);
            filteredSubdistricts.sort((a, b) => a.subdistrictNameTh.localeCompare(b.subdistrictNameTh, 'th'));

            filteredSubdistricts.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.subdistrictNameTh;
                opt.textContent = s.subdistrictNameTh;
                opt.dataset.postalCode = s.postalCode || '';
                subSelect.appendChild(opt);
            });

            if (selectSubdistrict) {
                subSelect.value = selectSubdistrict;
            }
        }

        function onEditSubdistrictChange(subdistrictName) {
            const subOption = document.querySelector(`#edit_subdistrict_select option[value="${subdistrictName}"]`);
            const postalInput = document.getElementById('edit_postal_code');
            if (subOption && subOption.dataset.postalCode && !postalInput.value) {
                postalInput.value = subOption.dataset.postalCode;
            }
        }

        lucide.createIcons();
    </script>
</body>
</html>
