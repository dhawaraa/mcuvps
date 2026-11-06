<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('portal.cert_verify_title') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>
    <script src="/assets/js/font-resizer.js"></script>

    <!-- Tailwind CSS -->
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
        body { 
            font-family: 'Sarabun', sans-serif; 
            background: #F4F1EA; 
            color: #2D2A26;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }

        .earth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid #D5CEBC;
            box-shadow: 0 10px 25px -10px rgba(74, 59, 50, 0.08);
            border-radius: 1.25rem;
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col justify-between selection:bg-[#5A6B47] selection:text-white">

    <!-- Top Navigation -->
    <nav class="bg-[#FAF8F2] border-b border-[#E3DEC9] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-10 h-10 object-contain drop-shadow-sm">
                    <div>
                        <div class="font-heading font-bold text-[#2C3E2D] leading-tight">VPSMCU</div>
                        <div class="text-xs text-[#6B6357]">{{ __('portal.cert_verify_sub') }}</div>
                    </div>
                </a>
                <div class="flex items-center gap-3">
                    @php
                        $currentLang = session('locale', 'th');
                    @endphp
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
                        <i data-lucide="arrow-left" class="w-4.5 h-4.5 text-[#5A6B47]"></i> {{ __('portal.nav_back_home') }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full flex-grow flex flex-col justify-center">
        
        <div class="earth-card overflow-hidden">
            
            @if ($isValid && $result)
                <!-- Verification Success Header -->
                <div class="bg-gradient-to-r from-[#2C3E2D] to-[#3D523E] p-6 text-white text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-[#5A6B47] text-white flex items-center justify-center mb-3 shadow-md ring-4 ring-white/10">
                        <i data-lucide="shield-check" class="w-8 h-8 text-[#A3B88C]"></i>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-white/15 text-[#EAE5D9] border border-white/20 inline-block mb-1.5">
                        {{ __('portal.cert_valid_badge') }}
                    </span>
                    <h1 class="text-xl md:text-2xl font-heading font-bold">{{ __('portal.cert_valid_title') }}</h1>
                    <p class="text-xs text-[#D5CEBC] mt-1 font-mono">{{ __('portal.cert_valid_desc') }}</p>
                </div>

                <!-- Verified Details -->
                <div class="p-6 md:p-8 space-y-6">
                    
                    <div class="border-b border-[#EAE5D9] pb-4">
                        <div class="text-xs font-semibold text-[#7B8D65] uppercase font-mono">{{ __('portal.cert_type') }}</div>
                        <h2 class="text-lg font-heading font-bold text-[#2C3E2D] mt-0.5">{{ $result['title'] }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[#7B8D65] font-semibold mb-1">{{ __('portal.cert_recipient') }}</div>
                            <div class="text-base font-heading font-bold text-[#2C3E2D]">{{ $result['name'] }}</div>
                            <div class="text-[11px] font-mono text-[#4A3B32] mt-0.5">{{ __('portal.cert_student_code') }} {{ $result['student_code'] }}</div>
                        </div>

                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[#7B8D65] font-semibold mb-1">{{ __('portal.cert_faculty_org') }}</div>
                            <div class="text-sm font-semibold text-[#2C3E2D]">{{ $result['faculty'] }}</div>
                            <div class="text-[11px] text-[#4A3B32] mt-0.5">{{ $result['org_unit'] }}</div>
                        </div>

                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[#7B8D65] font-semibold mb-1">{{ __('portal.cert_criteria') }}</div>
                            <div class="text-xs text-[#2C3E2D] leading-relaxed">{{ $result['details'] }}</div>
                        </div>

                        <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#EAE5D9]">
                            <div class="text-[#7B8D65] font-semibold mb-1">{{ __('portal.cert_issue_status') }}</div>
                            <div class="text-xs font-semibold text-emerald-800 flex items-center gap-1">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> {{ $result['status'] }}
                            </div>
                            <div class="text-[11px] font-mono text-[#4A3B32] mt-1">{{ __('portal.cert_issue_date') }} {{ $result['date'] }}</div>
                        </div>
                    </div>

                    <!-- Digital Certificate Seal -->
                    <div class="pt-4 border-t border-[#EAE5D9] flex flex-col sm:flex-row justify-between items-center text-xs text-[#7B8D65] gap-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="lock" class="w-4 h-4 text-[#5A6B47]"></i>
                            <span>{{ __('portal.cert_security_code') }} <strong class="font-mono text-[#2C3E2D]">{{ $result['code'] }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($result['type'] === 'UG')
                                <a href="{{ route('ug.certificate', ['reg_no' => $result['code']]) }}" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-2 rounded-xl text-xs font-medium transition flex items-center gap-1.5 shadow-sm">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i> {{ __('portal.cert_view_full') }}
                                </a>
                            @else
                                <a href="{{ route('grad.certificate', ['code' => $result['student_code']]) }}" class="bg-[#2C3E2D] hover:bg-[#3D523E] text-white px-4 py-2 rounded-xl text-xs font-medium transition flex items-center gap-1.5 shadow-sm">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i> {{ __('portal.cert_view_full') }}
                                </a>
                            @endif
                        </div>
                    </div>

                </div>

            @else
                <!-- Verification Failed Header -->
                <div class="bg-[#C86D51] p-6 text-white text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-[#A85238] text-white flex items-center justify-center mb-3 shadow-md ring-4 ring-white/10">
                        <i data-lucide="alert-triangle" class="w-8 h-8 text-[#FAF8F2]"></i>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-black/20 text-[#FAF8F2] inline-block mb-1.5">
                        {{ __('portal.cert_invalid_badge') }}
                    </span>
                    <h1 class="text-xl md:text-2xl font-heading font-bold">{{ __('portal.cert_invalid_title') }}</h1>
                    <p class="text-xs text-[#FAF8F2]/90 mt-1">{{ __('portal.cert_invalid_desc') }}</p>
                </div>

                <div class="p-8 text-center space-y-4">
                    <p class="text-xs text-[#6B6357] max-w-md mx-auto leading-relaxed">
                        {{ __('portal.cert_invalid_contact') }}
                    </p>
                    <div>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2C3E2D] hover:bg-[#3D523E] text-white text-xs font-medium rounded-xl transition shadow-sm">
                            <i data-lucide="home" class="w-4 h-4"></i> {{ __('portal.nav_back_home') }}
                        </a>
                    </div>
                </div>
            @endif

        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-[#FAF8F2] border-t border-[#E3DEC9] py-6 text-center text-xs text-[#8C8275]">
        {{ __('portal.footer_brand') }}
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
