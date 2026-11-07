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

                    <!-- Feedback Alert for Lookup -->
                    <div id="grad-lookup-feedback" class="mb-4 hidden"></div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                                {{ __('portal.grad_req_student_id') }} <span class="text-[#C86D51]">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input type="text" id="txt_studentid" name="student_id" maxlength="20" required 
                                    placeholder="{{ __('portal.grad_req_student_id_ph') }}" 
                                    value="{{ old('student_id', $loggedStudent?->student_code) }}" 
                                    class="w-full pl-3 pr-10 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                                <button type="button" id="btn-lookup-grad" onclick="lookupGradStudent()" 
                                    title="ดึงข้อมูลโปรไฟล์อัตโนมัติ" 
                                    class="absolute right-1.5 p-1.5 rounded-md bg-[#5A6B47] hover:bg-[#2C3E2D] text-white transition flex items-center justify-center">
                                    <i data-lucide="search" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <span class="text-[10px] text-[#7D7667] mt-1 block">ใส่รหัสนิสิตแล้วกดค้นหาหรือ Enter เพื่อดึงข้อมูล</span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_citizen_id') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_personalid" name="citizen_id" maxlength="20" required placeholder="{{ __('portal.grad_req_citizen_id_ph') }}" value="{{ old('citizen_id', $loggedStudent?->citizen_id) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_prefix') }} <span class="text-[#C86D51]">*</span></label>
                            <select id="txt_titlename" name="prefix" required class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                                @php $selPrefix = old('prefix', $loggedStudent?->prefix); @endphp
                                <option value="พระมหา" {{ $selPrefix === 'พระมหา' ? 'selected' : '' }}>พระมหา</option>
                                <option value="พระครู" {{ $selPrefix === 'พระครู' ? 'selected' : '' }}>พระครู</option>
                                <option value="พระครูปลัด" {{ $selPrefix === 'พระครูปลัด' ? 'selected' : '' }}>พระครูปลัด</option>
                                <option value="พระ" {{ $selPrefix === 'พระ' ? 'selected' : '' }}>พระ</option>
                                <option value="สามเณร" {{ $selPrefix === 'สามเณร' ? 'selected' : '' }}>สามเณร</option>
                                <option value="นาย" {{ $selPrefix === 'นาย' ? 'selected' : '' }}>นาย</option>
                                <option value="นาง" {{ $selPrefix === 'นาง' ? 'selected' : '' }}>นาง</option>
                                <option value="นางสาว" {{ $selPrefix === 'นางสาว' ? 'selected' : '' }}>นางสาว</option>
                                <option value="ดร." {{ $selPrefix === 'ดร.' ? 'selected' : '' }}>ดร.</option>
                                <option value="แม่ชี" {{ $selPrefix === 'แม่ชี' ? 'selected' : '' }}>แม่ชี</option>
                                @if(!empty($selPrefix) && !in_array($selPrefix, ['พระมหา','พระครู','พระครูปลัด','พระ','สามเณร','นาย','นาง','นางสาว','ดร.','แม่ชี']))
                                    <option value="{{ $selPrefix }}" selected>{{ $selPrefix }}</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_nationality') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_nation" name="nationality" value="{{ old('nationality', $loggedStudent?->nationality ?? (session('locale') === 'en' ? 'Thai' : 'ไทย')) }}" required class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_first_name') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_name" name="first_name" required placeholder="{{ __('portal.grad_req_first_name_ph') }}" value="{{ old('first_name', $loggedStudent?->first_name) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_last_name') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_lastname" name="last_name" required placeholder="{{ __('portal.grad_req_last_name_ph') }}" value="{{ old('last_name', $loggedStudent?->last_name) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_buddhist_name') }}</label>
                            <input type="text" id="txt_buddhistname" name="buddhist_name" placeholder="{{ __('portal.grad_req_buddhist_name_ph') }}" value="{{ old('buddhist_name', $loggedStudent?->chaya ?? '-') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_age') }}</label>
                                <input type="number" id="txt_age" name="age" min="15" max="120" placeholder="{{ __('portal.grad_req_age') }}" value="{{ old('age', $loggedStudent?->age) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_vassa') }}</label>
                                <input type="number" id="txt_vassa" name="vassa" min="0" max="100" placeholder="0" value="{{ old('vassa', $loggedStudent?->vassa ?? 0) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
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
                                @php $selDeg = old('degree_level', $loggedStudent?->degree_level); @endphp
                                <option value="ปริญญาเอก (45 วัน)" {{ $selDeg === 'DOCTORAL' || $selDeg === 'ปริญญาเอก (45 วัน)' ? 'selected' : '' }}>ปริญญาเอก (45 วัน)</option>
                                <option value="ปริญญาโท (30 วัน)" {{ $selDeg === 'MASTER' || $selDeg === 'ปริญญาโท (30 วัน)' || empty($selDeg) ? 'selected' : '' }}>ปริญญาโท (30 วัน)</option>
                                <option value="ปริญญาตรีปีละ (10วัน)" {{ $selDeg === 'BACHELOR' || $selDeg === 'ปริญญาตรีปีละ (10วัน)' ? 'selected' : '' }}>ปริญญาตรี (สะสมปีละ 10 วัน รวม 40 วัน)</option>
                                <option value="ประกาศนียบัตร (15 วัน)" {{ $selDeg === 'CERTIFICATE' || $selDeg === 'ประกาศนียบัตร (15 วัน)' ? 'selected' : '' }}>ประกาศนียบัตร (15 วัน)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_faculty') }} <span class="text-[#C86D51]">*</span></label>
                            <select id="txt_faculty" name="faculty" required class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                                @php $selFac = old('faculty', $loggedStudent?->faculty); @endphp
                                <option value="บัณฑิตวิทยาลัย" {{ $selFac === 'บัณฑิตวิทยาลัย' ? 'selected' : '' }}>บัณฑิตวิทยาลัย (Graduate School)</option>
                                <option value="พุทธศาสตร์" {{ str_contains($selFac ?? '', 'พุทธศาสตร์') && !str_contains($selFac ?? '', 'IBSC') ? 'selected' : '' }}>คณะพุทธศาสตร์ (Faculty of Buddhism)</option>
                                <option value="ครุศาสตร์" {{ str_contains($selFac ?? '', 'ครุศาสตร์') ? 'selected' : '' }}>คณะครุศาสตร์ (Faculty of Education)</option>
                                <option value="มนุษยศาสตร์" {{ str_contains($selFac ?? '', 'มนุษยศาสตร์') ? 'selected' : '' }}>คณะมนุษยศาสตร์ (Faculty of Humanities)</option>
                                <option value="สังคมศาสตร์" {{ str_contains($selFac ?? '', 'สังคมศาสตร์') ? 'selected' : '' }}>คณะสังคมศาสตร์ (Faculty of Social Sciences)</option>
                                <option value="IBSC" {{ str_contains($selFac ?? '', 'IBSC') || str_contains($selFac ?? '', 'นานาชาติ') ? 'selected' : '' }}>วิทยาลัยพุทธศาสตร์นานาชาติ (IBSC)</option>
                                @if(!empty($selFac) && !in_array($selFac, ['บัณฑิตวิทยาลัย', 'พุทธศาสตร์', 'ครุศาสตร์', 'มนุษยศาสตร์', 'สังคมศาสตร์', 'IBSC']))
                                    <option value="{{ $selFac }}" selected>{{ $selFac }}</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_program') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_subject" name="program_name" required placeholder="{{ __('portal.grad_req_program_ph') }}" value="{{ old('program_name', $loggedStudent?->major) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_campus') }} <span class="text-[#C86D51]">*</span></label>
                            <select id="txt_zone" name="org_unit_id" required class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                                @foreach ($orgUnits as $org)
                                    <option value="{{ $org->id }}" {{ old('org_unit_id', $loggedStudent?->org_unit_id ?? 1) == $org->id ? 'selected' : '' }}>
                                        {{ session('locale') === 'en' && !empty($org->name_en) ? $org->name_en : $org->name_th }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                                {{ __('portal.grad_req_accumulated_days') }} <span class="text-[#C86D51]">*</span>
                            </label>
                            <input type="number" id="txt_total" name="accumulated_days" min="1" max="120" required placeholder="ระบุจำนวนวันที่ยื่น เช่น 10" value="{{ old('accumulated_days') }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono font-bold text-[#5A6B47] focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                            <span id="target_days_hint" class="text-[10px] text-[#7D7667] mt-1 block">เกณฑ์สะสมรวมหลักสูตร: 30 วัน</span>
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
                            @php
                                $defAddr = trim(($loggedStudent?->house_no ? 'เลขที่ ' . $loggedStudent->house_no . ' ' : '') . ($loggedStudent?->moo ? 'หมู่ ' . $loggedStudent->moo . ' ' : '') . ($loggedStudent?->temple_village ?? ''));
                            @endphp
                            <input type="text" id="txt_address" name="address" required placeholder="{{ __('portal.grad_req_address_ph') }}" value="{{ old('address', $defAddr) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_subdistrict') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_subdistrict" name="subdistrict" required placeholder="{{ __('portal.grad_req_subdistrict') }}" value="{{ old('subdistrict', $loggedStudent?->subdistrict) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_district') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_district" name="district" required placeholder="{{ __('portal.grad_req_district') }}" value="{{ old('district', $loggedStudent?->district) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_province') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_province" name="province" required placeholder="{{ __('portal.grad_req_province') }}" value="{{ old('province', $loggedStudent?->province) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_postcode') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="txt_postcode" name="postcode" maxlength="5" required placeholder="5 digits" value="{{ old('postcode', $loggedStudent?->postal_code) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-[#4A3B32] mb-1">{{ __('portal.grad_req_phone') }} <span class="text-[#C86D51]">*</span></label>
                            <input type="tel" id="txt_tel" name="phone" required placeholder="0812345678" value="{{ old('phone', $loggedStudent?->phone) }}" class="w-full px-3 py-2 border border-[#D5CEBC] rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#5A6B47] bg-[#FAF8F2]">
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
            const hint = document.getElementById('target_days_hint');
            const target = degreeDaysMap[val] || 30;
            if (totalInput) {
                totalInput.placeholder = 'ระบุจำนวนวันที่ยื่น เช่น ' + Math.min(10, target);
                totalInput.min = 1;
            }
            if (hint) {
                hint.innerText = 'เกณฑ์สะสมรวมหลักสูตร: ' + target + ' วัน';
            }
        }

        async function lookupGradStudent() {
            const input = document.getElementById('txt_studentid');
            const code = input ? input.value.trim() : '';
            const feedback = document.getElementById('grad-lookup-feedback');
            const btn = document.getElementById('btn-lookup-grad');

            if (!code) {
                if (feedback) {
                    feedback.className = "mb-4 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-2";
                    feedback.innerHTML = `<i data-lucide="alert-circle" class="w-4 h-4 text-amber-600 shrink-0"></i> <span>กรุณากรอกรหัสนิสิตเพื่อค้นหาข้อมูล</span>`;
                    feedback.classList.remove('hidden');
                    lucide.createIcons();
                }
                input && input.focus();
                return;
            }

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<i data-lucide="loader" class="w-4 h-4 animate-spin"></i>`;
                lucide.createIcons();
            }

            try {
                // รองรับทั้งระบบ Clean URL และ FreeBSD Apache Hosting
                const lookupUrl = `{{ url('/grad_request.php?action=lookup') }}&student_code=` + encodeURIComponent(code);
                const res = await fetch(lookupUrl);
                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}`);
                }
                const data = await res.json();

                if (feedback) feedback.classList.remove('hidden');

                if (data.found && data.student) {
                    const s = data.student;

                    if (feedback) {
                        feedback.className = "mb-4 p-3.5 rounded-xl bg-[#EBF1E6] border border-[#CDE0C3] text-[#2C3E2D] text-xs flex items-center gap-2.5";
                        feedback.innerHTML = `<i data-lucide="check-circle" class="w-4 h-4 text-[#4D5E3C] shrink-0"></i> 
                            <div>พบข้อมูลนิสิตในระบบ: <strong>${s.prefix || ''}${s.first_name || ''} ${s.last_name || ''} ${s.chaya ? '(' + s.chaya + ')' : ''}</strong> (${s.degree_level_th || s.degree_level || ''} • ${s.faculty || ''}) <span class="text-[#5A6B47] font-semibold">ระบบดึงข้อมูลลงแบบฟอร์มให้อัตโนมัติแล้ว</span></div>`;
                        lucide.createIcons();
                    }

                    // 1. หมวดที่ 1: ข้อมูลส่วนตัว
                    if (s.citizen_id) document.getElementById('txt_personalid').value = s.citizen_id;
                    if (s.first_name) document.getElementById('txt_name').value = s.first_name;
                    if (s.last_name) document.getElementById('txt_surname').value = s.last_name;
                    if (s.chaya || s.buddhist_name) document.getElementById('txt_bname').value = s.chaya || s.buddhist_name || '-';
                    if (s.nationality) document.getElementById('txt_nation').value = s.nationality;
                    if (s.age) document.getElementById('txt_age').value = s.age;
                    if (s.vassa !== null && s.vassa !== undefined) document.getElementById('txt_vassa').value = s.vassa;

                    // Prefix select
                    if (s.prefix) {
                        const prefixSelect = document.getElementById('txt_titlename');
                        let foundPrefix = false;
                        for (let i = 0; i < prefixSelect.options.length; i++) {
                            if (prefixSelect.options[i].value === s.prefix || prefixSelect.options[i].text === s.prefix) {
                                prefixSelect.selectedIndex = i;
                                foundPrefix = true;
                                break;
                            }
                        }
                        if (!foundPrefix) {
                            const opt = new Option(s.prefix, s.prefix, true, true);
                            prefixSelect.add(opt);
                        }
                    }

                    // 2. หมวดที่ 2: ข้อมูลการศึกษา
                    const statusSelect = document.getElementById('txt_status');
                    if (s.degree_level === 'DOCTORAL' || s.degree_level_th === 'ปริญญาเอก') {
                        statusSelect.value = 'ปริญญาเอก (45 วัน)';
                    } else if (s.degree_level === 'MASTER' || s.degree_level_th === 'ปริญญาโท') {
                        statusSelect.value = 'ปริญญาโท (30 วัน)';
                    } else if (s.degree_level === 'BACHELOR' || s.degree_level_th === 'ปริญญาตรี') {
                        statusSelect.value = 'ปริญญาตรีปีละ (10วัน)';
                    } else if (s.degree_level === 'CERTIFICATE' || s.degree_level_th === 'ประกาศนียบัตร') {
                        statusSelect.value = 'ประกาศนียบัตร (15 วัน)';
                    }
                    handleDegreeChange(statusSelect.value);

                    // คณะ txt_faculty
                    if (s.faculty) {
                        const facSelect = document.getElementById('txt_faculty');
                        let foundFac = false;
                        for (let i = 0; i < facSelect.options.length; i++) {
                            if (s.faculty.includes(facSelect.options[i].value) || facSelect.options[i].text.includes(s.faculty)) {
                                facSelect.selectedIndex = i;
                                foundFac = true;
                                break;
                            }
                        }
                        if (!foundFac) {
                            const opt = new Option(s.faculty, s.faculty, true, true);
                            facSelect.add(opt);
                        }
                    }

                    // สาขาวิชา txt_subject
                    if (s.major || s.program_name) {
                        document.getElementById('txt_subject').value = s.major || s.program_name;
                    }

                    // สังกัดวิทยาเขต txt_zone
                    if (s.org_unit_id) {
                        document.getElementById('txt_zone').value = s.org_unit_id;
                    }

                    // วันสะสม txt_total
                    if (s.accumulated_days && s.accumulated_days > 0) {
                        document.getElementById('txt_total').value = s.accumulated_days;
                    }

                    // 3. หมวดที่ 3: ที่อยู่และการติดต่อ
                    if (s.address) document.getElementById('txt_address').value = s.address;
                    if (s.subdistrict) document.getElementById('txt_subdistrict').value = s.subdistrict;
                    if (s.district) document.getElementById('txt_district').value = s.district;
                    if (s.province) document.getElementById('txt_province').value = s.province;
                    if (s.postcode) document.getElementById('txt_postcode').value = s.postcode;
                    if (s.phone) document.getElementById('txt_tel').value = s.phone;

                } else {
                    if (feedback) {
                        feedback.className = "mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2";
                        feedback.innerHTML = `<i data-lucide="alert-circle" class="w-4 h-4 text-red-600 shrink-0"></i> <span>${data.message || 'ไม่พบข้อมูลนิสิต'}</span>`;
                        lucide.createIcons();
                    }
                }
            } catch (err) {
                if (feedback) {
                    feedback.className = "mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2";
                    feedback.innerHTML = `<i data-lucide="alert-triangle" class="w-4 h-4 text-red-600 shrink-0"></i> <span>เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง</span>`;
                    lucide.createIcons();
                }
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = `<i data-lucide="search" class="w-4 h-4"></i>`;
                    lucide.createIcons();
                }
            }
        }

        // Enter key in student ID input triggers lookup
        document.getElementById('txt_studentid')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                lookupGradStudent();
            }
        });

        // Blur event on student ID triggers lookup if not already populated
        document.getElementById('txt_studentid')?.addEventListener('blur', function() {
            const val = this.value.trim();
            const nameVal = document.getElementById('txt_name')?.value.trim();
            if (val && !nameVal) {
                lookupGradStudent();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            const statusSelect = document.getElementById('txt_status');
            if (statusSelect) {
                handleDegreeChange(statusSelect.value);
            }
            // Auto lookup if student ID has value but name is still blank
            const studentIdInput = document.getElementById('txt_studentid');
            const nameInput = document.getElementById('txt_name');
            if (studentIdInput && studentIdInput.value.trim() && (!nameInput || !nameInput.value.trim())) {
                lookupGradStudent();
            }
        });

        lucide.createIcons();
    </script>
</body>
</html>
