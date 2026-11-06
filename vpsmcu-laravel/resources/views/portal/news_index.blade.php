<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('portal.news_title') }} - {{ __('portal.system_title') }} (VPSMCU)</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
                <span>{{ __('portal.top_announcement') }}</span>
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

                <!-- Nav Links: ปฏิทิน, ปริญญาตรี, บัณฑิตศึกษา, ประชาชนทั่วไป, ติดต่อ, ร่วมบริจาค -->
                <nav class="hidden xl:flex items-center space-x-6 text-[15px] font-semibold text-[#4A3B32]">
                    <a href="{{ route('home') }}#calendar" class="hover:text-[#C86D51] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="calendar" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_calendar') }}</span>
                    </a>
                    <a href="{{ route('ug.register') }}" class="hover:text-[#C86D51] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="graduation-cap" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_ug') }}</span>
                    </a>
                    <a href="{{ route('grad.request') }}" class="hover:text-[#C86D51] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="scroll" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_grad') }}</span>
                    </a>
                    <a href="{{ route('public.register') }}" class="hover:text-[#C86D51] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="users" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_public') }}</span>
                    </a>
                    <a href="{{ route('contact') }}" class="hover:text-[#C86D51] transition flex items-center gap-1.5 whitespace-nowrap py-1">
                        <i data-lucide="phone-call" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>{{ __('portal.nav_contact') }}</span>
                    </a>
                    <a href="{{ route('donation') }}" class="hover:text-[#C86D51] transition flex items-center gap-1.5 text-[#C86D51] font-bold whitespace-nowrap py-1">
                        <i data-lucide="gift" class="w-4.5 h-4.5 text-[#C86D51]"></i>
                        <span>{{ __('portal.nav_donation') }}</span>
                    </a>
                </nav>

                <!-- Actions: Language Switcher & Auth / Admin Button -->
                <div class="flex items-center space-x-2.5">
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
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Matching Standardized Width -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#2C3E2D] via-[#4A3B32] to-[#5A6B47] rounded-3xl p-6 md:p-8 text-white shadow-lg shadow-[#2C3E2D]/15 mb-8 border border-[#2C3E2D]/20">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/15 backdrop-blur-sm rounded-full text-xs font-semibold uppercase tracking-wider mb-2 border border-white/20 text-[#FAF8F2]">
                <i data-lucide="newspaper" class="w-3.5 h-3.5 text-[#EAE5D9]"></i>
                <span>{{ __('portal.news_badge') }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-heading font-bold mb-2">{{ __('portal.news_title') }} ({{ __('portal.mcu_short') }})</h1>
            <p class="text-[#EAE5D9] text-sm leading-relaxed max-w-3xl">
                {{ app()->getLocale() === 'en' ? 'Stay updated with retreat schedules, announcements, academic publications, and activities from the Vipassana Meditation Institute, Mahachulalongkornrajavidyalaya University.' : 'ติดตามข่าวประกาศ โครงการปฏิบัติธรรมประจำปี บทความวิชาการ และกิจกรรมส่งเสริมการปฏิบัติวิปัสสนาธุระ สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย' }}
            </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-[#E3DEC9] shadow-sm mb-8">
            <form method="GET" action="{{ route('news.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto flex-grow">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-72">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-3 text-[#8C8275]"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('portal.news_search_placeholder') }}" class="w-full pl-9 pr-3 py-2 text-xs bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-[#2C3E2D] font-medium focus:ring-2 focus:ring-[#5A6B47]">
                    </div>

                    <select name="category" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-[#2C3E2D] font-medium focus:ring-2 focus:ring-[#5A6B47]">
                        <option value="">{{ __('portal.news_all_categories') }}</option>
                        <option value="ANNOUNCEMENT" {{ request('category') == 'ANNOUNCEMENT' ? 'selected' : '' }}>{{ __('portal.news_cat_announcement') }}</option>
                        <option value="MEDITATION" {{ request('category') == 'MEDITATION' ? 'selected' : '' }}>{{ __('portal.news_cat_meditation') }}</option>
                        <option value="ACADEMIC" {{ request('category') == 'ACADEMIC' ? 'selected' : '' }}>{{ __('portal.news_cat_academic') }}</option>
                        <option value="GENERAL" {{ request('category') == 'GENERAL' ? 'selected' : '' }}>{{ __('portal.news_cat_general') }}</option>
                    </select>

                    <select name="org_unit_id" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-[#2C3E2D] font-medium focus:ring-2 focus:ring-[#5A6B47] max-w-xs">
                        <option value="">{{ __('portal.news_all_orgs') }}</option>
                        @foreach ($orgUnits as $org)
                            <option value="{{ $org->id }}" {{ request('org_unit_id') == $org->id ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'en' ? ($org->name_en ?? $org->name_th) : $org->name_th }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-4 py-2 bg-[#2C3E2D] text-white rounded-xl text-xs font-semibold hover:bg-[#3D523E] transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> {{ __('portal.news_search_btn') }}
                    </button>

                    @if(request()->anyFilled(['search', 'category', 'org_unit_id']))
                        <a href="{{ route('news.index') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1 font-medium">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i> {{ __('portal.news_clear_filter') }}
                        </a>
                    @endif
                </div>

                <div class="text-xs text-[#7B8D65] font-mono shrink-0">
                    {{ __('portal.news_total_found') }} <strong>{{ $newsList->total() }}</strong> {{ __('portal.news_items') }}
                </div>
            </form>
        </div>

        <!-- News Grid (4 Columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @forelse ($newsList as $item)
                <div class="bg-white rounded-3xl overflow-hidden border border-[#E3DEC9] hover:border-[#5A6B47] transition duration-200 shadow-sm hover:shadow-md flex flex-col justify-between group relative">
                    
                    @if ($item->is_pinned)
                        <div class="absolute top-3 right-3 z-10 bg-[#C86D51] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
                            <i data-lucide="pin" class="w-3 h-3 fill-white"></i> {{ __('portal.news_pinned') }}
                        </div>
                    @endif

                    @if ($item->cover_image)
                        <div class="h-52 w-full overflow-hidden bg-[#FAF8F2] relative flex items-center justify-center border-b border-[#E3DEC9]">
                            <img src="{{ $item->cover_image }}" alt="{{ $item->title }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition duration-300">
                            <div class="absolute bottom-2.5 left-2.5">
                                @if ($item->category === 'ANNOUNCEMENT')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#C86D51] text-white shadow-sm">{{ __('portal.news_cat_announcement') }}</span>
                                @elseif ($item->category === 'MEDITATION')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#5A6B47] text-white shadow-sm">{{ __('portal.news_cat_meditation') }}</span>
                                @elseif ($item->category === 'ACADEMIC')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#4A3B32] text-white shadow-sm">{{ __('portal.news_cat_academic') }}</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-stone-800 text-white shadow-sm">{{ __('portal.news_cat_general') }}</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="h-52 w-full bg-[#FAF8F2] flex items-center justify-center text-[#D5CEBC] border-b border-[#E3DEC9] relative">
                            <i data-lucide="image" class="w-16 h-16 stroke-1"></i>
                            <div class="absolute bottom-2.5 left-2.5">
                                @if ($item->category === 'ANNOUNCEMENT')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#C86D51] text-white shadow-sm">{{ __('portal.news_cat_announcement') }}</span>
                                @elseif ($item->category === 'MEDITATION')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#5A6B47] text-white shadow-sm">{{ __('portal.news_cat_meditation') }}</span>
                                @elseif ($item->category === 'ACADEMIC')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#4A3B32] text-white shadow-sm">{{ __('portal.news_cat_academic') }}</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-stone-800 text-white shadow-sm">{{ __('portal.news_cat_general') }}</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs text-[#8C8275] mb-2 font-mono">
                                <span class="flex items-center gap-1 truncate max-w-[65%]">
                                    <i data-lucide="building" class="w-3 h-3 text-[#5A6B47] shrink-0"></i> 
                                    <span class="truncate">{{ app()->getLocale() === 'en' ? ($item->organizationUnit->name_en ?? $item->organizationUnit->name_th ?? 'Vipassana Institute Central') : ($item->organizationUnit->name_th ?? 'สถาบันวิปัสสนาธุระ ส่วนกลาง') }}</span>
                                </span>
                                <span class="flex items-center gap-1 shrink-0">
                                    <i data-lucide="calendar" class="w-3 h-3"></i> 
                                    {{ substr($item->published_at ?? $item->created_at, 0, 10) }}
                                </span>
                            </div>

                            <h3 class="font-heading font-bold text-[#2C3E2D] text-base mb-2 group-hover:text-[#C86D51] transition line-clamp-2 leading-snug">
                                <a href="{{ route('news.detail', $item->id) }}">
                                    {{ $item->localized_title }}
                                </a>
                            </h3>

                            <p class="text-xs text-[#6B6357] line-clamp-3 leading-relaxed mb-4">
                                {{ strip_tags($item->localized_content) }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-[#F2EFE7] flex items-center justify-between text-xs">
                            <span class="text-[11px] font-semibold text-[#8C8275] flex items-center gap-1 font-mono">
                                <i data-lucide="eye" class="w-3.5 h-3.5 text-[#A3B88C]"></i>
                                {{ number_format($item->views) }} {{ __('portal.news_views') }}
                            </span>
                            <a href="{{ route('news.detail', $item->id) }}" class="text-[#5A6B47] font-semibold flex items-center gap-1 group-hover:text-[#C86D51] transition">
                                <span>{{ __('portal.news_read_full') }}</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-[#8C8275] bg-white rounded-3xl border border-dashed border-[#D5CEBC] p-8">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-[#D5CEBC]"></i>
                    <p class="text-base font-semibold text-[#4A3B32]">{{ __('portal.news_no_results') }}</p>
                    <p class="text-xs text-[#8C8275] mt-1">{{ __('portal.news_no_results_sub') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($newsList->hasPages())
            <div class="bg-white rounded-2xl p-4 border border-[#E3DEC9] flex justify-center shadow-sm">
                {{ $newsList->links() }}
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-[#243325] text-[#D5CEBC] text-xs py-12 border-t border-[#1B271C]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
                <div>
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                        <img src="{{ asset('images/mcu-logo.png') }}" alt="MCU Logo" class="w-8 h-8 object-contain">
                        <span class="font-heading font-bold text-white text-base">{{ __('portal.footer_brand') }}</span>
                    </div>
                    <p class="text-[#A3B88C]">{{ __('portal.footer_address') }}</p>
                    <p class="text-[#8C8275] mt-1">{{ __('portal.institute_name') }} {{ __('portal.university_name') }}</p>
                </div>
                <div class="flex flex-col items-center md:items-end gap-2.5">
                    @if (Session::has('admin_user'))
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium text-xs border border-white/15 transition shadow-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#A3B88C]"></i>
                            <span>{{ __('portal.nav_admin_panel') }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2C3E2D] hover:bg-[#385039] text-[#EAE5D9] hover:text-white font-medium text-xs border border-[#3E5540] transition shadow-sm group">
                            <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-[#A3B88C] group-hover:text-white transition"></i>
                            <span>{{ __('portal.nav_admin_login') }}</span>
                        </a>
                    @endif
                    <div class="text-[10px] text-[#7A7367] font-mono">
                        {{ __('portal.university_name') }} &bull; VPSMCU
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
