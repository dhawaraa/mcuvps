<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถิติและวิเคราะห์เปรียบเทียบสำหรับผู้บริหาร - VPSMCU Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>

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

        .earth-admin-card {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(220, 215, 201, 0.85);
            box-shadow: 0 10px 25px -10px rgba(74, 59, 50, 0.05);
            border-radius: 1.25rem;
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Earth Tones Sidebar -->
    @include('admin.layouts.sidebar')

    <!-- Main Workspace (Layout Rule 5.1: flex-grow p-6 md:p-10 overflow-y-auto, NO max-w-7xl) -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        
        <!-- Header Section (Rule 5.1: border-b border-[#D5CEBC] pb-6 mb-8) -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <span class="text-xs font-bold text-[#C86D51] uppercase tracking-wider font-mono flex items-center gap-1.5">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Executive Intelligence Dashboard
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-[#2C3E2D] mt-1">
                    ระบบสถิติเปรียบเทียบและการวิเคราะห์สำหรับผู้บริหาร
                </h1>
                <p class="text-xs text-[#6B6357] mt-1">
                    ศูนย์กลางข้อมูลระดับชาติ วิเคราะห์ผลลัพธ์และเปรียบเทียบศักยภาพการปฏิบัติวิปัสสนากรรมฐาน มจร
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="bg-white border border-[#D5CEBC] text-[#4A3B32] hover:bg-[#FAF8F2] px-4 py-2.5 rounded-xl text-xs font-semibold shadow-sm inline-flex items-center gap-2 transition">
                    <i data-lucide="printer" class="w-4 h-4 text-[#5A6B47]"></i> พิมพ์รายงานสรุปผู้บริหาร
                </button>
            </div>
        </div>

        <!-- Section 1: สถิติภาพรวมระดับประเทศ (National Overview KPI) -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                    <i data-lucide="globe" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>ดัชนีชี้วัดภาพรวมระดับประเทศ (National Aggregate Key Indicators)</span>
                </h2>
                <span class="text-xs font-mono text-[#7B8D65]">มหาจุฬาลงกรณราชวิทยาลัย</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- 1. ปริญญาตรี -->
                <div class="earth-admin-card p-6 border-l-4 border-l-[#5A6B47]">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#8C8275] font-mono">นิสิต ป.ตรี ทั่วประเทศ</span>
                        <span class="p-2 rounded-xl bg-[#EAE5D9] text-[#2C3E2D]">
                            <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                        </span>
                    </div>
                    <div class="text-3xl font-heading font-extrabold text-[#2C3E2D]">{{ number_format($totalUgNation) }}</div>
                    <div class="mt-3 pt-3 border-t border-[#EAE5D9]/60 flex items-center justify-between text-xs">
                        <span class="text-[#6B6357]">ผ่านเกณฑ์ 10 วัน:</span>
                        <span class="font-bold text-[#5A6B47]">{{ number_format($totalUgPassNation) }} รูป/คน ({{ $ugPassRateNation }}%)</span>
                    </div>
                </div>

                <!-- 2. บัณฑิตศึกษา -->
                <div class="earth-admin-card p-6 border-l-4 border-l-[#C86D51]">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#8C8275] font-mono">บัณฑิตศึกษา ทั่วประเทศ</span>
                        <span class="p-2 rounded-xl bg-[#F3E7E3] text-[#C86D51]">
                            <i data-lucide="scroll-text" class="w-4 h-4"></i>
                        </span>
                    </div>
                    <div class="text-3xl font-heading font-extrabold text-[#2C3E2D]">{{ number_format($totalGradNation) }}</div>
                    <div class="mt-3 pt-3 border-t border-[#EAE5D9]/60 flex items-center justify-between text-xs">
                        <span class="text-[#6B6357]">อนุมัติครบเกณฑ์ (30/45 วัน):</span>
                        <span class="font-bold text-[#C86D51]">{{ number_format($totalGradApprovedNation) }} รูป/คน ({{ $gradApprovedRateNation }}%)</span>
                    </div>
                </div>

                <!-- 3. ประชาชนทั่วไป -->
                <div class="earth-admin-card p-6 border-l-4 border-l-[#2C3E2D]">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#8C8275] font-mono">บริการวิชาการประชาชน</span>
                        <span class="p-2 rounded-xl bg-[#EAE5D9] text-[#4A3B32]">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </span>
                    </div>
                    <div class="text-3xl font-heading font-extrabold text-[#2C3E2D]">{{ number_format($totalPublicNation) }}</div>
                    <div class="mt-3 pt-3 border-t border-[#EAE5D9]/60 flex items-center justify-between text-xs">
                        <span class="text-[#6B6357]">โครงการที่จัดแล้ว:</span>
                        <span class="font-bold text-[#2C3E2D]">{{ number_format($totalEventsNation) }} โครงการ</span>
                    </div>
                </div>

                <!-- 4. ยอดรวมผู้เข้าร่วมวิปัสสนาทั้งมหาวิทยาลัย -->
                <div class="earth-admin-card p-6 bg-gradient-to-br from-[#2C3E2D] to-[#1E2B1F] text-white border-0">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#D5CEBC] font-mono">รวมผู้ร่วมปฏิบัติธรรมทั้งระบบ</span>
                        <span class="p-2 rounded-xl bg-white/10 text-[#EAE5D9]">
                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                        </span>
                    </div>
                    <div class="text-3xl font-heading font-extrabold text-white">
                        {{ number_format($totalUgNation + $totalGradNation + $totalPublicNation) }}
                    </div>
                    <div class="mt-3 pt-3 border-t border-white/10 flex items-center justify-between text-xs text-[#D5CEBC]">
                        <span>ครอบคลุมทุกพันธกิจ:</span>
                        <span class="font-bold text-white">100% ครบ 45 สังกัด</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section 2: เปรียบเทียบข้อมูลระหว่างส่วนงาน (Comparative Analysis: Org A vs Org B) -->
        <div class="earth-admin-card p-6 sm:p-8 mb-10 border border-[#D5CEBC]">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-5 mb-6 border-b border-[#EAE5D9] gap-3">
                <div>
                    <h2 class="text-lg font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                        <i data-lucide="git-compare" class="w-5 h-5 text-[#C86D51]"></i>
                        <span>เครื่องมือเปรียบเทียบข้อมูลระหว่าง 2 ส่วนงาน (Head-to-Head Campus Comparison)</span>
                    </h2>
                    <p class="text-xs text-[#6B6357] mt-1">เลือก 2 ส่วนงานเพื่อวิเคราะห์เปรียบเทียบยอดการเข้าร่วม อัตราผ่านเกณฑ์ และศักยภาพในการจัดการปฏิบัติธรรม</p>
                </div>
            </div>

            <!-- Form Selector -->
            <form action="{{ route('admin.executive.analytics') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end mb-8 bg-[#FAF8F2] p-5 rounded-2xl border border-[#EAE5D9]">
                <div class="md:col-span-5">
                    <label class="block text-xs font-bold text-[#2C3E2D] mb-1.5 font-heading flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#5A6B47]"></span> ส่วนงานที่ 1 (Campus A)
                    </label>
                    <select name="org_a" class="w-full text-xs rounded-xl border border-[#D5CEBC] px-3.5 py-2.5 bg-white text-[#2C3E2D] focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                        @foreach ($allOrgUnits as $ou)
                            <option value="{{ $ou->id }}" {{ $selectedOrgAId == $ou->id ? 'selected' : '' }}>
                                [{{ $ou->province_code ?? $ou->code }}] {{ $ou->name_th }} ({{ $ou->type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 text-center flex items-center justify-center pb-2">
                    <span class="w-10 h-10 rounded-full bg-[#EAE5D9] text-[#4A3B32] font-bold text-xs font-heading flex items-center justify-center shadow-inner">
                        VS
                    </span>
                </div>

                <div class="md:col-span-5">
                    <label class="block text-xs font-bold text-[#2C3E2D] mb-1.5 font-heading flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#C86D51]"></span> ส่วนงานที่ 2 (Campus B)
                    </label>
                    <div class="flex gap-2">
                        <select name="org_b" class="w-full text-xs rounded-xl border border-[#D5CEBC] px-3.5 py-2.5 bg-white text-[#2C3E2D] focus:ring-2 focus:ring-[#C86D51] focus:outline-none">
                            @foreach ($allOrgUnits as $ou)
                                <option value="{{ $ou->id }}" {{ $selectedOrgBId == $ou->id ? 'selected' : '' }}>
                                    [{{ $ou->province_code ?? $ou->code }}] {{ $ou->name_th }} ({{ $ou->type }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-[#2C3E2D] hover:bg-[#1E2B1F] text-white px-5 py-2.5 rounded-xl text-xs font-semibold shadow transition shrink-0 flex items-center gap-1.5">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> เปรียบเทียบ
                        </button>
                    </div>
                </div>
            </form>

            <!-- Comparison Table & Matrix -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead>
                        <tr class="border-b border-[#D5CEBC] bg-[#FAF8F2] text-sm font-heading">
                            <th class="p-4 w-1/3 text-[#6B6357]">เกณฑ์การประเมินและเปรียบเทียบ</th>
                            <th class="p-4 w-1/3 text-[#5A6B47] font-bold border-l border-[#EAE5D9] bg-[#5A6B47]/5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#5A6B47]"></span>
                                    <span>{{ $orgA->name_th ?? 'ส่วนงาน A' }}</span>
                                </div>
                                <div class="text-[11px] font-mono text-[#7B8D65] font-normal mt-0.5">{{ $orgA->type ?? '' }} • {{ $orgA->province_th ?? '' }}</div>
                            </th>
                            <th class="p-4 w-1/3 text-[#C86D51] font-bold border-l border-[#EAE5D9] bg-[#C86D51]/5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#C86D51]"></span>
                                    <span>{{ $orgB->name_th ?? 'ส่วนงาน B' }}</span>
                                </div>
                                <div class="text-[11px] font-mono text-[#A85238] font-normal mt-0.5">{{ $orgB->type ?? '' }} • {{ $orgB->province_th ?? '' }}</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        
                        <!-- Row 1: ผู้เข้าร่วมรวมทุกประเภท -->
                        <tr class="hover:bg-[#FAF8F2]/60">
                            <td class="p-4 font-semibold text-[#2C3E2D]">
                                <div class="flex items-center gap-2 font-heading">
                                    <i data-lucide="layers" class="w-4 h-4 text-[#7B8D65]"></i>
                                    <span>ยอดผู้เข้าร่วมปฏิบัติธรรมรวมทั้งหมด (Grand Total)</span>
                                </div>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#5A6B47]/5 font-heading text-base font-bold text-[#5A6B47]">
                                {{ number_format($metricsA['grand_total'] ?? 0) }} คน
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#C86D51]/5 font-heading text-base font-bold text-[#C86D51]">
                                {{ number_format($metricsB['grand_total'] ?? 0) }} คน
                            </td>
                        </tr>

                        <!-- Row 2: ปริญญาตรี ลงทะเบียน -->
                        <tr class="hover:bg-[#FAF8F2]/60">
                            <td class="p-4">
                                <div class="font-medium text-[#2C3E2D]">1. นิสิตปริญญาตรีที่ลงทะเบียน (UG Enrolled)</div>
                                <div class="text-[11px] text-[#7B8D65]">เกณฑ์ 10 วัน/ปี ตามโครงสร้างหลักสูตร</div>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#5A6B47]/5 font-mono font-semibold">
                                {{ number_format($metricsA['ug_total'] ?? 0) }} รูป/คน
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#C86D51]/5 font-mono font-semibold">
                                {{ number_format($metricsB['ug_total'] ?? 0) }} รูป/คน
                            </td>
                        </tr>

                        <!-- Row 3: ปริญญาตรี ผ่านเกณฑ์ 10 วัน -->
                        <tr class="hover:bg-[#FAF8F2]/60">
                            <td class="p-4">
                                <div class="font-medium text-[#2C3E2D]">2. นิสิต ป.ตรี ที่ผ่านเกณฑ์สมบูรณ์ (UG Completed)</div>
                                <div class="text-[11px] text-[#7B8D65]">ผ่านการประเมินและออกหนังสือรับรองได้</div>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#5A6B47]/5">
                                <span class="font-bold text-[#5A6B47]">{{ number_format($metricsA['ug_completed'] ?? 0) }} รูป/คน</span>
                                <span class="text-[11px] text-[#7B8D65] ml-1">({{ $metricsA['ug_pass_rate'] ?? 0 }}%)</span>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#C86D51]/5">
                                <span class="font-bold text-[#C86D51]">{{ number_format($metricsB['ug_completed'] ?? 0) }} รูป/คน</span>
                                <span class="text-[11px] text-[#A85238] ml-1">({{ $metricsB['ug_pass_rate'] ?? 0 }}%)</span>
                            </td>
                        </tr>

                        <!-- Row 4: บัณฑิตศึกษา ทั้งหมด -->
                        <tr class="hover:bg-[#FAF8F2]/60">
                            <td class="p-4">
                                <div class="font-medium text-[#2C3E2D]">3. นิสิตบัณฑิตศึกษา (ป.โท / ป.เอก)</div>
                                <div class="text-[11px] text-[#7B8D65]">สะสมวันตามเกณฑ์ ป.โท 30 วัน / ป.เอก 45 วัน</div>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#5A6B47]/5 font-mono font-semibold">
                                {{ number_format($metricsA['grad_total'] ?? 0) }} รูป/คน
                                <div class="text-[10px] text-[#7B8D65] mt-0.5">
                                    ป.โท: {{ $metricsA['grad_master'] ?? 0 }} | ป.เอก: {{ $metricsA['grad_doctoral'] ?? 0 }}
                                </div>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#C86D51]/5 font-mono font-semibold">
                                {{ number_format($metricsB['grad_total'] ?? 0) }} รูป/คน
                                <div class="text-[10px] text-[#A85238] mt-0.5">
                                    ป.โท: {{ $metricsB['grad_master'] ?? 0 }} | ป.เอก: {{ $metricsB['grad_doctoral'] ?? 0 }}
                                </div>
                            </td>
                        </tr>

                        <!-- Row 5: บัณฑิตศึกษา อนุมัติสำเร็จ -->
                        <tr class="hover:bg-[#FAF8F2]/60">
                            <td class="p-4">
                                <div class="font-medium text-[#2C3E2D]">4. บัณฑิตศึกษาที่อนุมัติผ่านเกณฑ์ (Grad Approved)</div>
                                <div class="text-[11px] text-[#7B8D65]">สถานะ Lock และได้รับอนุมัติจบเกณฑ์วิปัสสนาแล้ว</div>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#5A6B47]/5">
                                <span class="font-bold text-[#5A6B47]">{{ number_format($metricsA['grad_approved'] ?? 0) }} รูป/คน</span>
                                <span class="text-[11px] text-[#7B8D65] ml-1">({{ $metricsA['grad_pass_rate'] ?? 0 }}%)</span>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#C86D51]/5">
                                <span class="font-bold text-[#C86D51]">{{ number_format($metricsB['grad_approved'] ?? 0) }} รูป/คน</span>
                                <span class="text-[11px] text-[#A85238] ml-1">({{ $metricsB['grad_pass_rate'] ?? 0 }}%)</span>
                            </td>
                        </tr>

                        <!-- Row 6: ภาคประชาชน & สังคม -->
                        <tr class="hover:bg-[#FAF8F2]/60">
                            <td class="p-4">
                                <div class="font-medium text-[#2C3E2D]">5. บริการวิชาการวิปัสสนาแก่สังคม</div>
                                <div class="text-[11px] text-[#7B8D65]">ประชาชนและพุทธศาสนิกชนที่เข้าร่วมโครงการ</div>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#5A6B47]/5 font-mono font-semibold">
                                {{ number_format($metricsA['public_total'] ?? 0) }} คน 
                                <span class="text-[11px] text-[#7B8D65] font-normal">({{ $metricsA['events_count'] ?? 0 }} โครงการ)</span>
                            </td>
                            <td class="p-4 border-l border-[#EAE5D9] bg-[#C86D51]/5 font-mono font-semibold">
                                {{ number_format($metricsB['public_total'] ?? 0) }} คน 
                                <span class="text-[11px] text-[#A85238] font-normal">({{ $metricsB['events_count'] ?? 0 }} โครงการ)</span>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 3: สถิติจำแนกตามประเภทส่วนงาน (Analysis by Org Type) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            
            <div class="earth-admin-card p-6 lg:col-span-2">
                <div class="flex justify-between items-center pb-4 mb-4 border-b border-[#EAE5D9]">
                    <div>
                        <h2 class="text-base font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                            <i data-lucide="pie-chart" class="w-4 h-4 text-[#5A6B47]"></i>
                            <span>การกระจายตัวตามประเภทส่วนงาน (Distribution by Unit Classification)</span>
                        </h2>
                        <p class="text-xs text-[#6B6357]">สัดส่วนการมีส่วนร่วมของส่วนกลาง วิทยาเขต วิทยาลัยสงฆ์ และหน่วยวิทยบริการ</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#FAF8F2] text-[#2C3E2D] font-heading font-semibold border-b border-[#EAE5D9]">
                            <tr>
                                <th class="p-3">ประเภทส่วนงาน</th>
                                <th class="p-3 text-center">จำนวนส่วนงาน</th>
                                <th class="p-3 text-center">นิสิต ป.ตรี</th>
                                <th class="p-3 text-center">บัณฑิตศึกษา</th>
                                <th class="p-3 text-center">ภาคประชาชน</th>
                                <th class="p-3 text-center font-bold">รวมผู้เข้าร่วม</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EAE5D9] text-[#4A3B32]">
                            @foreach ($statsByType as $st)
                                @php
                                    $typeNameTh = match($st->type) {
                                        'CENTRAL' => 'ส่วนกลาง (วังน้อย / กรุงเทพฯ)',
                                        'CAMPUS' => 'วิทยาเขต (14 แห่ง)',
                                        'SANGHA_COLLEGE' => 'วิทยาลัยสงฆ์ (27 แห่ง)',
                                        'ACADEMIC_UNIT' => 'หน่วยวิทยบริการ (4 แห่ง)',
                                        default => $st->type
                                    };
                                    $totalTypeParticipants = $st->ug_count + $st->grad_count + $st->public_count;
                                @endphp
                                <tr class="hover:bg-[#FAF8F2]/60">
                                    <td class="p-3">
                                        <div class="font-semibold text-[#2C3E2D]">{{ $typeNameTh }}</div>
                                        <span class="text-[10px] text-[#7B8D65] font-mono">{{ $st->type }}</span>
                                    </td>
                                    <td class="p-3 text-center font-mono font-medium">{{ $st->org_count }} แห่ง</td>
                                    <td class="p-3 text-center font-mono">{{ number_format($st->ug_count) }}</td>
                                    <td class="p-3 text-center font-mono">{{ number_format($st->grad_count) }}</td>
                                    <td class="p-3 text-center font-mono">{{ number_format($st->public_count) }}</td>
                                    <td class="p-3 text-center font-bold text-[#5A6B47] font-mono">
                                        {{ number_format($totalTypeParticipants) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- กล่องสรุปข้อเสนอแนะเชิงนโยบาย (Executive Policy Insights) -->
            <div class="earth-admin-card p-6 bg-[#FAF8F2] border border-[#D5CEBC] flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-[#2C3E2D] text-sm flex items-center gap-2 mb-3">
                        <i data-lucide="lightbulb" class="w-4 h-4 text-[#C86D51]"></i>
                        <span>ข้อเสนอแนะเชิงบริหาร (Executive Insights)</span>
                    </h3>
                    <ul class="space-y-3 text-xs text-[#5A544A] leading-relaxed">
                        <li class="flex items-start gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-[#5A6B47] shrink-0 mt-0.5"></i>
                            <span><strong>กลุ่มวิทยาเขตภูมิภาค:</strong> มีอัตราการมีส่วนร่วมของนิสิต ป.ตรี สูงสุด ควรกำหนดปฏิทินปฏิบัติธรรมไม่ให้ตรงกับช่วงสอบของแต่ละวิทยาเขต</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-[#5A6B47] shrink-0 mt-0.5"></i>
                            <span><strong>ส่วนกลาง (วังน้อย):</strong> รองรับนิสิตบัณฑิตศึกษาและภาคประชาชนมากที่สุด ควรเพิ่มรอบปฏิบัติธรรมเสาร์-อาทิตย์เพื่อกระจายความหนาแน่น</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-[#5A6B47] shrink-0 mt-0.5"></i>
                            <span><strong>การประกันคุณภาพ:</strong> ยอดประชาชนที่เข้าร่วม <strong>{{ number_format($totalPublicNation) }} คน</strong> สามารถนำไปรายงานผลตัวบ่งชี้บริการวิชาการได้ทันที</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-6 pt-4 border-t border-[#EAE5D9] text-[11px] text-[#7B8D65] flex items-center justify-between font-mono">
                    <span>ข้อมูลอัปเดต: {{ date('d/m/Y H:i') }}</span>
                    <span class="text-[#2C3E2D] font-bold">มจร VPSMCU</span>
                </div>
            </div>

        </div>

        <!-- Section 4: ตารางอันดับส่วนงานที่มีผู้เข้าร่วมสูงสุด (Top Campus Ranking) -->
        <div class="earth-admin-card overflow-hidden">
            <div class="p-6 border-b border-[#EAE5D9] flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                <div>
                    <h2 class="text-base font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                        <i data-lucide="trophy" class="w-4 h-4 text-[#C86D51]"></i>
                        <span>อันดับส่วนงานที่มีการปฏิบัติวิปัสสนากรรมฐานสูงสุด (Campus Performance Ranking)</span>
                    </h2>
                    <p class="text-xs text-[#6B6357]">จัดลำดับตามยอดรวมผู้เข้าร่วมปฏิบัติวิปัสสนากรรมฐานทุกโครงการ</p>
                </div>
                <span class="text-xs text-[#7B8D65] font-mono">แสดงผลส่วนงานที่มีผลการดำเนินงานจริง</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead class="bg-[#FAF8F2] text-[#2C3E2D] font-heading font-semibold border-b border-[#EAE5D9]">
                        <tr>
                            <th class="p-4 w-16 text-center">อันดับ</th>
                            <th class="p-4">รหัสย่อ</th>
                            <th class="p-4">ชื่อส่วนงานภายใน มจร</th>
                            <th class="p-4">ประเภทส่วนงาน</th>
                            <th class="p-4 text-center">ป.ตรี (10 วัน)</th>
                            <th class="p-4 text-center">บัณฑิตศึกษา (30/45 วัน)</th>
                            <th class="p-4 text-center">บริการวิชาการแก่สังคม</th>
                            <th class="p-4 text-center font-bold text-[#5A6B47]">ยอดรวมทั้งหมด</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($topOrgs as $rank => $org)
                            <tr class="hover:bg-[#FAF8F2]/70 transition">
                                <td class="p-4 text-center font-bold">
                                    @if ($rank === 0)
                                        <span class="w-6 h-6 rounded-full bg-amber-400 text-amber-950 font-bold text-xs inline-flex items-center justify-center shadow-sm">1</span>
                                    @elseif ($rank === 1)
                                        <span class="w-6 h-6 rounded-full bg-slate-300 text-slate-800 font-bold text-xs inline-flex items-center justify-center shadow-sm">2</span>
                                    @elseif ($rank === 2)
                                        <span class="w-6 h-6 rounded-full bg-amber-700 text-amber-100 font-bold text-xs inline-flex items-center justify-center shadow-sm">3</span>
                                    @else
                                        <span class="font-mono text-[#8C8275]">{{ $rank + 1 }}</span>
                                    @endif
                                </td>
                                <td class="p-4 font-mono font-bold text-[#C86D51]">
                                    {{ $org->province_code ?? $org->code }}
                                </td>
                                <td class="p-4">
                                    <div class="font-heading font-semibold text-[#2C3E2D] text-sm">{{ $org->name_th }}</div>
                                    <div class="text-[10px] text-[#7B8D65]">{{ $org->province_th ?: 'ส่วนกลาง' }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#EAE5D9] text-[#4A3B32]">
                                        {{ $org->type }}
                                    </span>
                                </td>
                                <td class="p-4 text-center font-mono font-medium">
                                    {{ $org->metrics['ug_total'] }} รูป/คน
                                    <span class="text-[10px] text-[#5A6B47] block">ผ่าน {{ $org->metrics['ug_completed'] }}</span>
                                </td>
                                <td class="p-4 text-center font-mono font-medium">
                                    {{ $org->metrics['grad_total'] }} รูป/คน
                                    <span class="text-[10px] text-[#C86D51] block">อนุมัติ {{ $org->metrics['grad_approved'] }}</span>
                                </td>
                                <td class="p-4 text-center font-mono font-medium">
                                    {{ $org->metrics['public_total'] }} คน
                                </td>
                                <td class="p-4 text-center font-bold text-base font-mono text-[#5A6B47]">
                                    {{ number_format($org->metrics['grand_total']) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-[#8C8275]">
                                    ยังไม่มีข้อมูลสถิติของส่วนงาน
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
