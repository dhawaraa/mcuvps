<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานสถิติการปฏิบัติธรรม ปริญญาตรี (SAR) - VPSMCU Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

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
            background: #F7F5EE; 
            color: #2D2A26;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }

        .earth-card {
            background: #FFFFFF;
            border: 1px solid #EAE5D9;
            box-shadow: 0 4px 20px -2px rgba(44, 62, 45, 0.05);
            border-radius: 1rem;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            main { padding: 0 !important; }
            .earth-card { border: 1px solid #ddd !important; box-shadow: none !important; }
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col md:flex-row selection:bg-[#5A6B47] selection:text-white">

    <!-- Earth Tones Sidebar (Deep Forest & Olive) -->
    @include('admin.layouts.sidebar')

    <!-- Main Content Area: Standard Admin Console Standard -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        
        <!-- Header Section: Standard Admin Console Standard with Marble Divider -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30">
                        <i data-lucide="bar-chart-2" class="w-3 h-3 inline mr-1"></i> โมดูล 1: ระดับปริญญาตรี (10 วัน/ปี รวม 40 วัน)
                    </span>
                    @if (!$isCentralOrSuper)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider bg-[#C86D51]/15 text-[#C86D51] border border-[#C86D51]/30">
                            {{ session('admin_user.org_unit_name', 'เฉพาะวิทยาเขตตนเอง') }}
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider bg-[#2C3E2D]/10 text-[#2C3E2D] border border-[#2C3E2D]/20">
                            ภาพรวมทั้งมหาวิทยาลัย ({{ count($orgUnits) }} สังกัด)
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">รายงานสถิติการปฏิบัติธรรม ปริญญาตรี (SAR)</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">สถิติรายงานการประกันคุณภาพการศึกษา (SAR/EdPEx) อัตราการเข้าร่วม และผลการประเมินการปฏิบัติธรรม</p>
            </div>

            <!-- Action Buttons: Print & Export CSV -->
            <div class="flex items-center gap-3 no-print">
                <button onclick="window.print()" class="px-3.5 py-2 rounded-xl border border-[#D5CEBC] bg-white text-[#4A3B32] hover:bg-[#EAE5D9] transition text-xs font-medium flex items-center gap-2 shadow-sm">
                    <i data-lucide="printer" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>พิมพ์รายงาน (Print)</span>
                </button>
                <a href="{{ route('admin.ug.export', request()->query()) }}" class="px-4 py-2 rounded-xl bg-[#5A6B47] hover:bg-[#465337] text-white transition text-xs font-medium flex items-center gap-2 shadow-sm">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>ส่งออกข้อมูล (Export CSV)</span>
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="earth-card p-5 mb-8 no-print">
            <form method="GET" action="{{ route('admin.ug.sar') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <!-- ปีการศึกษา -->
                <div>
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">ปีการศึกษา</label>
                    <select name="academic_year" class="w-full text-xs rounded-xl border-[#D5CEBC] bg-[#FAF8F2] px-3 py-2 text-[#2D2A26] focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกปีการศึกษา --</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>ปีการศึกษา {{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- โครงการ/รุ่น -->
                <div>
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">โครงการ / รุ่น</label>
                    <select name="batch_id" class="w-full text-xs rounded-xl border-[#D5CEBC] bg-[#FAF8F2] px-3 py-2 text-[#2D2A26] focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกโครงการ --</option>
                        @foreach ($batches as $b)
                            <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->title }} (ปี {{ $b->academic_year }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ส่วนงาน (สำหรับ Central/Super Admin) -->
                @if ($isCentralOrSuper)
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">ส่วนงานต้นสังกัด</label>
                        <select name="org_unit_id" class="w-full text-xs rounded-xl border-[#D5CEBC] bg-[#FAF8F2] px-3 py-2 text-[#2D2A26] focus:border-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="">-- ทุกสังกัด ({{ count($orgUnits) }} สังกัด) --</option>
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}" {{ request('org_unit_id') == $org->id ? 'selected' : '' }}>
                                    [{{ $org->code ?? $org->id }}] {{ $org->name_th }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div></div>
                @endif

                <!-- ปุ่มค้นหาและรีเซ็ต -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full bg-[#5A6B47] hover:bg-[#465337] text-white px-4 py-2 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-2 shadow-sm">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>ประมวลผลสถิติ</span>
                    </button>
                    @if (request()->hasAny(['academic_year', 'batch_id', 'org_unit_id']))
                        <a href="{{ route('admin.ug.sar') }}" class="p-2 rounded-xl border border-[#D5CEBC] text-[#7B8D65] hover:text-[#4A3B32] hover:bg-[#EAE5D9] transition" title="ล้างค่าตัวกรอง">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 4 KPI Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Card 1: ลงทะเบียนทั้งหมด -->
            <div class="earth-card p-5 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#7B8D65] uppercase tracking-wider font-mono">ยอดลงทะเบียนรวม</p>
                        <h3 class="text-2xl font-heading font-bold text-[#2C3E2D] mt-1">{{ number_format($totalRegistered) }} <span class="text-xs font-normal text-[#7B8D65]">รูป/คน</span></h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47]">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] text-[#7B8D65]">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                    <span>รวมนิสิตระดับ ป.ตรี ทุกชั้นปีที่ลงทะเบียน</span>
                </div>
            </div>

            <!-- Card 2: เช็คอินเข้าปฏิบัติธรรม -->
            <div class="earth-card p-5 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#7B8D65] uppercase tracking-wider font-mono">รายงานตัวเช็คอิน</p>
                        <h3 class="text-2xl font-heading font-bold text-[#5A6B47] mt-1">{{ number_format($checkedInCount) }} <span class="text-xs font-normal text-[#7B8D65]">รูป/คน</span></h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47]">
                        <i data-lucide="qr-code" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] text-[#7B8D65]">
                    <span class="font-semibold text-[#5A6B47]">{{ $checkinRate }}%</span>
                    <span>ของนิสิตที่ลงทะเบียนทั้งหมด</span>
                </div>
            </div>

            <!-- Card 3: ผ่านเกณฑ์ 10 วัน -->
            <div class="earth-card p-5 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#7B8D65] uppercase tracking-wider font-mono">ผ่านเกณฑ์สมบูรณ์ (10 วัน)</p>
                        <h3 class="text-2xl font-heading font-bold text-[#2C3E2D] mt-1">{{ number_format($completedCount) }} <span class="text-xs font-normal text-[#7B8D65]">รูป/คน</span></h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#5A6B47]/15 flex items-center justify-center text-[#5A6B47]">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] text-[#5A6B47]">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    <span class="font-semibold">อัตราความสำเร็จ {{ $passRate }}%</span>
                </div>
            </div>

            <!-- Card 4: รอการเข้าปฏิบัติธรรม / ยังไม่เช็คอิน -->
            <div class="earth-card p-5 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#7B8D65] uppercase tracking-wider font-mono">รอดำเนินการ / ไม่ได้เข้า</p>
                        <h3 class="text-2xl font-heading font-bold text-[#C86D51] mt-1">{{ number_format($pendingCount) }} <span class="text-xs font-normal text-[#7B8D65]">รูป/คน</span></h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#C86D51]/10 flex items-center justify-center text-[#C86D51]">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] text-[#C86D51]">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                    <span>ยังไม่ผ่านการรายงานตัว ณ สถานที่อบรม</span>
                </div>
            </div>
        </div>

        <!-- 2 Columns: ชั้นปี vs สมณเพศ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Col 1: สถิติจำแนกตามชั้นปี (ปี 1 - 4) -->
            <div class="lg:col-span-2 earth-card p-6">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#EAE5D9]">
                    <div class="flex items-center gap-2">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-[#5A6B47]"></i>
                        <h2 class="text-base font-heading font-bold text-[#2C3E2D]">สถิติจำแนกตามระดับชั้นปี (ปี 1 - ปี 4)</h2>
                    </div>
                    <span class="text-xs text-[#7B8D65] font-mono">Undergraduate Cohorts</span>
                </div>

                <div class="space-y-4">
                    @forelse ($yearStats as $y)
                        @php
                            $yTotal = $y->total;
                            $yPass = $y->completed;
                            $yPct = $yTotal > 0 ? round(($yPass / $yTotal) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center text-xs mb-1.5">
                                <span class="font-semibold text-[#2C3E2D] flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-[#5A6B47]"></span>
                                    นิสิตชั้นปีที่ {{ $y->study_year ?? '-' }}
                                </span>
                                <span class="text-[#7B8D65]">
                                    ผ่านเกณฑ์ <strong class="text-[#2C3E2D]">{{ number_format($yPass) }}</strong> / {{ number_format($yTotal) }} รูป-คน 
                                    <span class="text-[#5A6B47] font-semibold font-mono ml-1">({{ $yPct }}%)</span>
                                </span>
                            </div>
                            <div class="w-full bg-[#EAE5D9] rounded-full h-3 overflow-hidden">
                                <div class="bg-[#5A6B47] h-3 rounded-full transition-all duration-500" style="width: {{ $yPct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-[#7B8D65]">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto text-[#D5CEBC] mb-2"></i>
                            ไม่พบข้อมูลสถิติตามเงื่อนไขที่เลือก
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Col 2: จำแนกตามสมณเพศ (บรรพชิต vs คฤหัสถ์) -->
            <div class="earth-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#EAE5D9]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user-check" class="w-5 h-5 text-[#5A6B47]"></i>
                            <h2 class="text-base font-heading font-bold text-[#2C3E2D]">สัดส่วนสมณเพศ</h2>
                        </div>
                        <span class="text-xs text-[#7B8D65] font-mono">Status Ratio</span>
                    </div>

                    <div class="space-y-4 my-auto pt-2">
                        <!-- บรรพชิต (พระภิกษุ - สามเณร) -->
                        <div class="p-4 rounded-xl bg-[#FAF8F2] border border-[#EAE5D9]">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-[#2C3E2D] flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-[#C86D51]"></span>
                                    บรรพชิต (พระภิกษุ-สามเณร)
                                </span>
                                <span class="text-sm font-bold font-mono text-[#C86D51]">{{ number_format($monkCount) }}</span>
                            </div>
                            <div class="text-[11px] text-[#7B8D65]">
                                คิดเป็น {{ $totalRegistered > 0 ? round(($monkCount / $totalRegistered) * 100, 1) : 0 }}% ของยอดลงทะเบียน
                            </div>
                        </div>

                        <!-- คฤหัสถ์ (ชาย - หญิง) -->
                        <div class="p-4 rounded-xl bg-[#FAF8F2] border border-[#EAE5D9]">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-[#2C3E2D] flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-[#5A6B47]"></span>
                                    คฤหัสถ์ (ชาย-หญิง)
                                </span>
                                <span class="text-sm font-bold font-mono text-[#5A6B47]">{{ number_format($laymanCount) }}</span>
                            </div>
                            <div class="text-[11px] text-[#7B8D65]">
                                คิดเป็น {{ $totalRegistered > 0 ? round(($laymanCount / $totalRegistered) * 100, 1) : 0 }}% ของยอดลงทะเบียน
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-[#EAE5D9] text-[11px] text-[#7B8D65] flex items-center gap-1.5">
                    <i data-lucide="shield-check" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>เกณฑ์ มจร: ผ่าน 10 วัน/ปีการศึกษา ต่อเนื่อง 4 ปี</span>
                </div>
            </div>
        </div>

        <!-- ตารางรายละเอียดสถิติแยกรายส่วนงาน (Organization Units Breakdown Table) -->
        <div class="earth-card overflow-hidden mb-8">
            <div class="p-6 border-b border-[#EAE5D9] flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <div>
                    <h2 class="text-base font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                        <i data-lucide="building" class="w-5 h-5 text-[#5A6B47]"></i>
                        สถิติแยกตามวิทยาเขต / วิทยาลัยสงฆ์ / สังกัด ({{ count($campusStats ?? []) }} สังกัด)
                    </h2>
                    <p class="text-xs text-[#7B8D65] mt-0.5">ตารางประเมินผลการเข้าร่วมและผลสัมฤทธิ์ตามเกณฑ์ประกันคุณภาพการศึกษา</p>
                </div>
                <div class="text-xs text-[#7B8D65] font-mono">
                    จำนวนส่วนงานที่มีนิสิต: {{ $orgUnitStats->count() }} ส่วนงาน
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead class="bg-[#2C3E2D] text-white uppercase text-[10px] tracking-wider font-mono">
                        <tr>
                            <th scope="col" class="py-3 px-4 text-center w-12">ลำดับ</th>
                            <th scope="col" class="py-3 px-4">ชื่อส่วนงาน / วิทยาเขต</th>
                            <th scope="col" class="py-3 px-4 text-center">ลงทะเบียนรวม</th>
                            <th scope="col" class="py-3 px-4 text-center">เช็คอินเข้าปฏิบัติ</th>
                            <th scope="col" class="py-3 px-4 text-center">ผ่านเกณฑ์ (10 วัน)</th>
                            <th scope="col" class="py-3 px-4 text-center">ยังไม่รายงานตัว</th>
                            <th scope="col" class="py-3 px-4 text-center">% ความสำเร็จ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($orgUnitStats as $index => $stat)
                            @php
                                $pct = $stat->total > 0 ? round(($stat->completed / $stat->total) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-[#FAF8F2] transition">
                                <td class="py-3 px-4 text-center font-mono text-[#7B8D65]">{{ $index + 1 }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-[#2C3E2D]">
                                        {{ $stat->organizationUnit->name_th ?? 'ส่วนงานส่วนกลาง' }}
                                    </div>
                                    <div class="text-[10px] text-[#7B8D65] font-mono">
                                        รหัส: {{ $stat->organizationUnit->code ?? '-' }} | จังหวัด: {{ $stat->organizationUnit->province ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-[#2C3E2D] font-mono">
                                    {{ number_format($stat->total) }}
                                </td>
                                <td class="py-3 px-4 text-center font-mono text-[#5A6B47] font-semibold">
                                    {{ number_format($stat->checked_in) }}
                                </td>
                                <td class="py-3 px-4 text-center font-mono text-[#2C3E2D] font-bold">
                                    <span class="px-2 py-0.5 rounded-full bg-[#5A6B47]/15 text-[#5A6B47]">
                                        {{ number_format($stat->completed) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center font-mono text-[#C86D51]">
                                    {{ number_format($stat->registered_only) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 bg-[#EAE5D9] rounded-full h-2 overflow-hidden">
                                            <div class="bg-[#5A6B47] h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="font-mono font-bold text-xs {{ $pct >= 80 ? 'text-[#5A6B47]' : ($pct >= 50 ? 'text-[#C86D51]' : 'text-red-600') }}">
                                            {{ $pct }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-[#7B8D65]">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto text-[#D5CEBC] mb-2"></i>
                                    ยังไม่พบข้อมูลสถิติของส่วนงานตามตัวกรอง
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Lucide Icon Renderer Directive -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
