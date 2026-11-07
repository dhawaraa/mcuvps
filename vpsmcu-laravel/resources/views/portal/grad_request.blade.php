<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยื่นคำร้องขอหนังสือรับรองและเอกสาร e-Document (ระดับบัณฑิตศึกษา) - VPSMCU</title>
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
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Navigation Header (Matching Student Dashboard Header) -->
    <header class="bg-[#FAF8F2]/90 border-b border-[#E7E2D4] w-full sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3.5 group">
                    <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-12 h-12 object-contain drop-shadow-sm group-hover:scale-105 transition">
                    <div>
                        <div class="font-heading font-extrabold text-xl text-[#2C3E2D] tracking-tight leading-tight">
                            VPSMCU
                        </div>
                        <p class="text-xs text-[#6B6357] font-medium">{{ __('portal.university_name') }}</p>
                    </div>
                </a>

                <!-- Center Pill Tabs (ยื่นคำร้อง e-Document / ตรวจประวัติสะสมวัน) -->
                <div class="hidden md:flex items-center bg-[#EFECE3] p-1 rounded-2xl border border-[#DFDACB]">
                    <a href="{{ route('grad.request') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#4D5E3C] text-white shadow-xs transition flex items-center gap-1.5">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>ยื่นคำร้อง e-Document</span>
                    </a>
                    <a href="{{ route('student.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-[#4A3B32] hover:text-[#2C3E2D] hover:bg-[#FAF8F2] transition flex items-center gap-1.5">
                        <i data-lucide="search" class="w-4 h-4 text-[#7A7366]"></i>
                        <span>ตรวจประวัติสะสมวัน</span>
                    </a>
                </div>

                <!-- Right Actions: Language Switcher & Profile / Home Link -->
                <div class="flex items-center space-x-4">
                    @php
                        $currentLang = session('locale', 'th');
                    @endphp
                    <!-- Language Switcher (TH / EN) -->
                    @include('portal.partials.font_resizer')
                    <div class="flex items-center bg-[#E5DFD0] p-1 rounded-xl text-xs font-bold font-mono">
                        <a href="{{ route('lang.switch', 'th') }}" title="ภาษาไทย" class="px-2.5 py-1 rounded-lg transition {{ $currentLang === 'th' ? 'bg-[#4D5E3C] text-white shadow-xs' : 'text-[#605B50] hover:text-[#2C3E2D]' }}">
                            TH
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" title="English" class="px-2.5 py-1 rounded-lg transition {{ $currentLang === 'en' ? 'bg-[#4D5E3C] text-white shadow-xs' : 'text-[#605B50] hover:text-[#2C3E2D]' }}">
                            EN
                        </a>
                    </div>

                    @if(session()->has('student_user'))
                        <a href="{{ route('student.profile') }}" title="ข้อมูลส่วนตัว" class="text-xs sm:text-sm font-semibold text-[#4D5E3C] hover:text-[#2C3E2D] flex items-center gap-1.5 transition">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">ข้อมูลส่วนตัว</span>
                        </a>

                        <a href="{{ route('student.logout') }}" title="ออกจากระบบ" class="text-xs font-semibold text-[#C86D51] hover:text-[#A85238] flex items-center gap-1 transition ml-2">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">ออก</span>
                        </a>
                    @else
                        <a href="{{ route('student.login') }}" title="เข้าสู่ระบบนิสิต" class="text-xs sm:text-sm font-semibold bg-[#4D5E3C] hover:bg-[#3D4C30] text-white px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 transition shadow-xs">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            <span>เข้าสู่ระบบนิสิต</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Mobile Sub-Navigation -->
            <div class="flex md:hidden items-center justify-center pb-3 pt-1 border-t border-[#EAE5D9] gap-2">
                <a href="{{ route('grad.request') }}" class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-semibold bg-[#4D5E3C] text-white shadow-2xs">
                    ยื่นคำร้อง e-Document
                </a>
                <a href="{{ route('student.dashboard') }}" class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-semibold bg-white text-[#4A3B32] border border-[#DFDACB]">
                    ตรวจประวัติสะสมวัน
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#243325] via-[#4A3B32] to-[#2C3E2D] rounded-2xl p-6 md:p-8 text-white shadow-md mb-8 border border-[#3D523E]">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/15 backdrop-blur-sm rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-white/20">
                <i data-lucide="file-text" class="w-3.5 h-3.5 text-[#EAE5D9]"></i>
                <span>{{ __('portal.grad_req_header_badge') }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-heading font-bold mb-2">{{ __('portal.grad_req_header_title') }}</h1>
            <p class="text-[#EAE5D9] text-sm leading-relaxed">
                {{ __('portal.grad_req_header_desc') }}
            </p>
        </div>

        @if ($isClosed)
            <div class="bg-[#FBE8E6] border-l-4 border-[#C86D51] p-6 rounded-r-2xl mb-8 shadow-sm">
                <div class="flex items-start gap-3">
                    <i data-lucide="lock" class="w-6 h-6 text-[#C86D51] shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="text-base font-heading font-bold text-[#A85238]">{{ __('portal.grad_req_closed_title') }}</h3>
                        <p class="text-xs text-[#6B6357] mt-1 leading-relaxed">
                            {{ __('portal.grad_req_closed_desc') }}
                        </p>
                        <a href="{{ route('grad.progress') }}" class="inline-flex items-center gap-1.5 text-xs text-[#5A6B47] hover:underline font-semibold mt-3">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i> {{ __('portal.grad_req_closed_back') }}
                        </a>
                    </div>
                </div>
            </div>
        @else

            @if (session('success'))
                <div class="bg-white border border-[#D5CEBC] rounded-2xl p-8 shadow-sm text-center mb-8">
                    <div class="w-16 h-16 bg-[#E9EFE2] text-[#3D523E] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#CADBC0]">
                        <i data-lucide="check-circle" class="w-8 h-8 text-[#5A6B47]"></i>
                    </div>
                    <h2 class="text-2xl font-heading font-bold text-[#2C3E2D] mb-1">{{ __('portal.grad_req_success_title') }}</h2>
                    <p class="text-[#6B6357] text-xs max-w-lg mx-auto mb-4 leading-relaxed">
                        {{ session('success') }}
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <a href="{{ route('grad.progress', ['student_code' => session('student_code')]) }}" class="bg-[#2C3E2D] hover:bg-[#3D523E] text-white font-medium px-6 py-2.5 rounded-lg text-sm transition flex items-center gap-2 shadow-sm">
                            <i data-lucide="search" class="w-4 h-4"></i> {{ __('portal.grad_req_check_status') }}
                        </a>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-[#FBE8E6] border-l-4 border-[#C86D51] p-4 rounded-r-lg mb-6 shadow-sm">
                    <div class="flex items-center">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#C86D51] mr-2 shrink-0"></i>
                        <p class="text-sm text-[#A85238] font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form name="data_form" id="data_form" action="{{ route('grad.request.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#E3DEC9] rounded-2xl shadow-sm overflow-hidden p-6 md:p-8 space-y-8">
                @csrf

                <!-- หมวดที่ 1: ข้อมูลส่วนตัวและประวัติผู้ยื่นคำร้อง -->
                <div>
                    <h2 class="text-base font-heading font-bold text-[#2C3E2D] flex items-center border-b border-[#E3DEC9] pb-3 mb-5">
                        <span class="w-6 h-6 rounded-full bg-[#5A6B47] text-white text-xs font-bold flex items-center justify-center mr-2">1</span>
                        {{ __('portal.grad_req_sec1_title') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_student_id') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_studentid" name="student_id" maxlength="15" required placeholder="{{ __('portal.grad_req_student_id_ph') }}" value="{{ old('student_id') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_citizen_id') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_personalid" name="citizen_id" maxlength="13" required placeholder="{{ __('portal.grad_req_citizen_id_ph') }}" value="{{ old('citizen_id') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_prefix') }} <span class="text-[#C86D51]">*</span></label>
                            <select id="txt_titlename" name="prefix" required class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                                <option value="พระมหา">พระมหา</option>
                                <option value="พระครู">พระครู</option>
                                <option value="พระครูปลัด">พระครูปลัด</option>
                                <option value="พระ">พระ</option>
                                <option value="สามเณร">สามเณร</option>
                                <option value="นาย">นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                                <option value="ดร.">ดร.</option>
                                <option value="แม่ชี">แม่ชี</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_nationality') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_nation" name="nationality" value="{{ old('nationality', session('locale') === 'en' ? 'Thai' : 'ไทย') }}" required class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_first_name') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_name" name="first_name" required placeholder="{{ __('portal.grad_req_first_name_ph') }}" value="{{ old('first_name') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_last_name') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_lastname" name="last_name" required placeholder="{{ __('portal.grad_req_last_name_ph') }}" value="{{ old('last_name') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_buddhist_name') }}</label>
                            <input type="text" id="txt_buddhistname" name="buddhist_name" placeholder="{{ __('portal.grad_req_buddhist_name_ph') }}" value="{{ old('buddhist_name', '-') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_age') }}</label>
                                <input type="number" id="txt_age" name="age" min="15" max="120" placeholder="{{ __('portal.grad_req_age') }}" value="{{ old('age') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_vassa') }}</label>
                                <input type="number" id="txt_vassa" name="vassa" min="0" max="100" placeholder="0" value="{{ old('vassa', 0) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- หมวดที่ 2: ข้อมูลการศึกษาและสังกัดใน มจร -->
                <div>
                    <h2 class="text-base font-heading font-bold text-[#2C3E2D] flex items-center border-b border-[#E3DEC9] pb-3 mb-5">
                        <span class="w-6 h-6 rounded-full bg-[#5A6B47] text-white text-xs font-bold flex items-center justify-center mr-2">2</span>
                        {{ __('portal.grad_req_sec2_title') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_degree') }} <span class="text-[#C86D51]">*</span></label>
                            <select id="txt_status" name="degree_level" required onchange="handleDegreeChange(this.value)" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                                <option value="ปริญญาเอก (45 วัน)">ปริญญาเอก (45 วัน)</option>
                                <option value="ปริญญาโท (30 วัน)" selected>ปริญญาโท (30 วัน)</option>
                                <option value="ปริญญาตรีปีละ (10วัน)">ปริญญาตรี (สะสมปีละ 10 วัน รวม 40 วัน)</option>
                                <option value="ประกาศนียบัตร (15 วัน)">ประกาศนียบัตร (15 วัน)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_faculty') }} <span class="text-[#C86D51]">*</span></label>
                            <select id="txt_faculty" name="faculty" required class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                                <option value="บัณฑิตวิทยาลัย">บัณฑิตวิทยาลัย (Graduate School)</option>
                                <option value="พุทธศาสตร์">คณะพุทธศาสตร์ (Faculty of Buddhism)</option>
                                <option value="ครุศาสตร์">คณะครุศาสตร์ (Faculty of Education)</option>
                                <option value="มนุษยศาสตร์">คณะมนุษยศาสตร์ (Faculty of Humanities)</option>
                                <option value="สังคมศาสตร์">คณะสังคมศาสตร์ (Faculty of Social Sciences)</option>
                                <option value="IBSC">วิทยาลัยพุทธศาสตร์นานาชาติ (IBSC)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_program') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_subject" name="program_name" required placeholder="{{ __('portal.grad_req_program_ph') }}" value="{{ old('program_name') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_campus') }} <span class="text-[#C86D51]">*</span></label>
                            <select id="txt_zone" name="org_unit_id" required class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                                @foreach ($orgUnits as $org)
                                    <option value="{{ $org->id }}" {{ old('org_unit_id', 1) == $org->id ? 'selected' : '' }}>
                                        {{ session('locale') === 'en' && !empty($org->name_en) ? $org->name_en : $org->name_th }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_accumulated_days') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="number" id="txt_total" name="accumulated_days" min="1" max="100" required placeholder="{{ __('portal.grad_req_days_ph') }}" value="{{ old('accumulated_days') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono font-bold text-[#5A6B47] focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                    </div>
                </div>

                <!-- หมวดที่ 3: ข้อมูลที่อยู่และการติดต่อ -->
                <div>
                    <h2 class="text-base font-heading font-bold text-[#2C3E2D] flex items-center border-b border-[#E3DEC9] pb-3 mb-5">
                        <span class="w-6 h-6 rounded-full bg-[#5A6B47] text-white text-xs font-bold flex items-center justify-center mr-2">3</span>
                        {{ __('portal.grad_req_sec3_title') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_address') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_address" name="address" required placeholder="{{ __('portal.grad_req_address_ph') }}" value="{{ old('address') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_subdistrict') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" name="subdistrict" required placeholder="{{ __('portal.grad_req_subdistrict') }}" value="{{ old('subdistrict') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_district') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" name="district" required placeholder="{{ __('portal.grad_req_district') }}" value="{{ old('district') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_province') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" name="province" required placeholder="{{ __('portal.grad_req_province') }}" value="{{ old('province') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_postcode') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_postcode" name="postcode" maxlength="5" required placeholder="5 digits" value="{{ old('postcode') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_phone') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="tel" id="txt_tel" name="phone" required placeholder="0812345678" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                    </div>
                </div>

                <!-- หมวดที่ 4: ไฟล์เอกสารแนบและหลักฐาน (4 รายการตาม e-Doc) -->
                <div>
                    <h2 class="text-base font-heading font-bold text-[#2C3E2D] flex items-center border-b border-[#E3DEC9] pb-3 mb-5">
                        <span class="w-6 h-6 rounded-full bg-[#5A6B47] text-white text-xs font-bold flex items-center justify-center mr-2">4</span>
                        {{ __('portal.grad_req_sec4_title') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ไฟล์ที่ 1: รูปถ่ายนิสิต -->
                        <div class="p-4 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl space-y-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="image" class="w-4 h-4 text-[#5A6B47]"></i>
                                <label class="text-xs font-bold text-[#2C3E2D]">{{ __('portal.grad_req_f1_title') }} <span class="text-[#C86D51]">*</span></label>
                            </div>
                            <p class="text-[11px] text-[#8C8275]">{{ __('portal.grad_req_f1_desc') }}</p>
                            <input type="file" name="file_photo" accept="image/jpeg,image/png" required class="w-full text-xs text-[#4A3B32] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#5A6B47] file:text-white hover:file:bg-[#2C3E2D]">
                        </div>

                        <!-- ไฟล์ที่ 2: PDF ใบบันทึกสอบอารมณ์ -->
                        <div class="p-4 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl space-y-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="file-check" class="w-4 h-4 text-[#5A6B47]"></i>
                                <label class="text-xs font-bold text-[#2C3E2D]">{{ __('portal.grad_req_f2_title') }} <span class="text-[#C86D51]">*</span></label>
                            </div>
                            <p class="text-[11px] text-[#8C8275]">{{ __('portal.grad_req_f2_desc') }}</p>
                            <input type="file" name="file_interview" accept="application/pdf" required class="w-full text-xs text-[#4A3B32] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#2C3E2D] file:text-white hover:file:bg-[#1E2B1F]">
                        </div>

                        <!-- ไฟล์ที่ 3: PDF ใบลงเวลา -->
                        <div class="p-4 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl space-y-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#5A6B47]"></i>
                                <label class="text-xs font-bold text-[#2C3E2D]">{{ __('portal.grad_req_f3_title') }} <span class="text-[#C86D51]">*</span></label>
                            </div>
                            <p class="text-[11px] text-[#8C8275]">{{ __('portal.grad_req_f3_desc') }}</p>
                            <input type="file" name="file_attendance" accept="application/pdf" required class="w-full text-xs text-[#4A3B32] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#2C3E2D] file:text-white hover:file:bg-[#1E2B1F]">
                        </div>

                        <!-- ไฟล์ที่ 4: สลิปโอนเงิน + วันเวลา -->
                        <div class="p-4 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl space-y-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="receipt" class="w-4 h-4 text-[#C86D51]"></i>
                                <label class="text-xs font-bold text-[#2C3E2D]">{{ __('portal.grad_req_f4_title') }} <span class="text-[#C86D51]">*</span></label>
                            </div>
                            <p class="text-[11px] text-[#8C8275]">{{ __('portal.grad_req_f4_desc') }}</p>
                            <input type="file" name="file_slip" accept="image/jpeg,image/png,application/pdf" required class="w-full text-xs text-[#4A3B32] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#C86D51] file:text-white hover:file:bg-[#A85238]">

                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#EAE5D9]">
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_transfer_date') }} <span class="text-[#C86D51]">*</span></label>
                                    <input type="date" id="sdatepickert" name="transfer_date" required value="{{ old('transfer_date', date('Y-m-d')) }}" class="w-full px-2.5 py-1.5 border border-[#D5CEBC] rounded-lg text-xs bg-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_transfer_time') }} <span class="text-[#C86D51]">*</span></label>
                                    <input type="time" id="timet" name="transfer_time" required value="{{ old('transfer_time', date('H:i')) }}" class="w-full px-2.5 py-1.5 border border-[#D5CEBC] rounded-lg text-xs bg-white font-mono">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-[#E3DEC9] flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-[#8C8275] flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>{{ __('portal.grad_req_footer_note') }}</span>
                    </div>

                    <button type="submit" class="w-full sm:w-auto bg-[#5A6B47] hover:bg-[#2C3E2D] text-white font-medium px-8 py-3 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>{{ __('portal.grad_req_submit_btn') }}</span>
                    </button>
                </div>

            </form>
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-[#FAF8F2] border-t border-[#E3DEC9] py-6 text-center text-xs text-[#8C8275]">
        {{ __('portal.footer_brand') }}
    </footer>

    <script>
        const degreeDaysMap = {
            'ปริญญาเอก (45 วัน)': 45,
            'ปริญญาเอก': 45,
            'DOCTORAL': 45,
            'ปริญญาโท (30 วัน)': 30,
            'ปริญญาโท': 30,
            'MASTER': 30,
            'ปริญญาตรีปีละ (10วัน)': 40,
            'ปริญญาตรี': 40,
            'BACHELOR': 40,
            'ประกาศนียบัตร (15 วัน)': 15,
            'ประกาศนียบัตร': 15,
            'CERTIFICATE': 15
        };

        function handleDegreeChange(val) {
            const totalInput = document.getElementById('txt_total');
            const target = degreeDaysMap[val] || 30;
            totalInput.placeholder = 'เกณฑ์ขั้นต่ำ ' + target + ' วัน';
            totalInput.min = target;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            const statusSelect = document.getElementById('txt_status');
            if (statusSelect) {
                handleDegreeChange(statusSelect.value);
            }
        });

        lucide.createIcons();
    </script>
</body>
</html>
