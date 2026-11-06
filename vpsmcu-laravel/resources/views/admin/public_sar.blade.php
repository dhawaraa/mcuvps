<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานสถิติคอร์สปฏิบัติธรรม - VPSMCU Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>

    <!-- Chart.js -->
    <script src="/assets/js/chart.min.js"></script>

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
<body class="flex min-h-screen">

    <!-- Sidebar Navigation -->
    @include('admin.layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">รายงานสถิติคอร์สปฏิบัติธรรม</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">แดชบอร์ดสรุปผลการจัดคอร์ส สถิติผู้เข้ารับการอบรม สัดส่วนประชากรศาสตร์ และอัตราการครองโควตาที่นั่ง</p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ url('/admin/public_students.php') }}" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#2C3E2D] border border-[#D5CEBC] px-4 py-2.5 rounded-xl text-xs font-medium shadow-sm transition flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-[#5A6B47]"></i> ไปยังทะเบียนผู้สมัคร
                </a>
                <button onclick="window.print()" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-2.5 rounded-xl text-xs font-medium shadow-md transition flex items-center gap-2">
                    <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์รายงานสรุป
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="earth-admin-card p-4 mb-8">
            <form method="GET" action="{{ url('/admin/public_sar.php') }}" class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-semibold text-[#4A3B32] flex items-center gap-1.5">
                        <i data-lucide="filter" class="w-3.5 h-3.5 text-[#5A6B47]"></i> ตัวกรองข้อมูล:
                    </span>

                    <select name="event_id" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] max-w-xs truncate">
                        <option value="">-- สถิติทุกคอร์สปฏิบัติธรรม --</option>
                        @foreach ($events as $ev)
                            <option value="{{ $ev->id }}" {{ request('event_id') == $ev->id ? 'selected' : '' }}>
                                {{ $ev->title }}
                            </option>
                        @endforeach
                    </select>

                    @if ($isCentralOrSuper)
                        <select name="filter_org" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] max-w-xs truncate">
                            <option value="">-- ทุกสังกัดเจ้าของโครงการ ({{ count($orgUnits) }} สังกัด) --</option>
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}" {{ request('filter_org') == $org->id ? 'selected' : '' }}>
                                    {{ $org->name_th }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    @if(request()->hasAny(['event_id', 'filter_org']))
                        <a href="{{ url('/admin/public_sar.php') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i> ล้างตัวกรอง
                        </a>
                    @endif
                </div>

                <div class="text-xs text-[#7B8D65]">
                    โครงการทั้งหมด <strong>{{ $eventsSummary->count() }}</strong> คอร์ส | รวมผู้สมัคร <strong>{{ number_format($total_registered) }}</strong> ราย
                </div>
            </form>
        </div>

        <!-- 1. KPI Cards Row (สถิติหลักภาพรวม) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <!-- Total Applicants -->
            <div class="earth-admin-card p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] text-[#7B8D65] font-medium">ผู้สมัครทั้งหมด</span>
                    <div class="w-7 h-7 rounded-lg bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-heading font-bold text-[#2C3E2D]">{{ number_format($total_registered) }}</div>
                <div class="text-[10px] text-[#8C8275] mt-1">ผู้สนใจเข้าร่วม</div>
            </div>

            <!-- Confirmed -->
            <div class="earth-admin-card p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] text-[#2C7338] font-medium">อนุมัติสิทธิ์แล้ว</span>
                    <div class="w-7 h-7 rounded-lg bg-[#2C7338]/15 text-[#2C7338] flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-heading font-bold text-[#2C7338]">{{ number_format($confirmed_count) }}</div>
                <div class="text-[10px] text-[#7B8D65] mt-1">{{ $total_registered > 0 ? round(($confirmed_count / $total_registered) * 100, 1) : 0 }}% ของทั้งหมด</div>
            </div>

            <!-- Attended -->
            <div class="earth-admin-card p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] text-[#5A6B47] font-medium">เข้าอบรมแล้ว</span>
                    <div class="w-7 h-7 rounded-lg bg-[#5A6B47]/20 text-[#5A6B47] flex items-center justify-center">
                        <i data-lucide="award" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-heading font-bold text-[#5A6B47]">{{ number_format($attended_count) }}</div>
                <div class="text-[10px] text-[#7B8D65] mt-1">ผ่านการปฏิบัติธรรม</div>
            </div>

            <!-- Waiting List -->
            <div class="earth-admin-card p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] text-[#C86D51] font-medium">รายชื่อสำรอง</span>
                    <div class="w-7 h-7 rounded-lg bg-[#C86D51]/15 text-[#C86D51] flex items-center justify-center">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-heading font-bold text-[#C86D51]">{{ number_format($waiting_count) }}</div>
                <div class="text-[10px] text-[#8C8275] mt-1">รอเรียกสิทธิ์ทดแทน</div>
            </div>

            <!-- Pending Review -->
            <div class="earth-admin-card p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] text-amber-700 font-medium">รอการตรวจสอบ</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                        <i data-lucide="hourglass" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-heading font-bold text-amber-700">{{ number_format($pending_count) }}</div>
                <div class="text-[10px] text-[#8C8275] mt-1">รอเจ้าหน้าที่คัดกรอง</div>
            </div>

            <!-- Cancelled / Rejected -->
            <div class="earth-admin-card p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] text-[#8C8275] font-medium">ยกเลิก / ปฏิเสธ</span>
                    <div class="w-7 h-7 rounded-lg bg-stone-100 text-[#8C8275] flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-heading font-bold text-[#8C8275]">{{ number_format($cancelled_count + $rejected_count) }}</div>
                <div class="text-[10px] text-[#8C8275] mt-1">สละสิทธิ์ / ไม่ผ่านเกณฑ์</div>
            </div>
        </div>

        <!-- 2. Charts Row (แผนภูมิสถิติแดชบอร์ด) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Chart 1: Status Distribution Doughnut -->
            <div class="earth-admin-card p-6 flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-[#2C3E2D] text-sm mb-1 flex items-center gap-2">
                        <i data-lucide="pie-chart" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>สัดส่วนสถานะการลงทะเบียน (Status Ratio)</span>
                    </h3>
                    <p class="text-[11px] text-[#7B8D65] mb-4">จำแนกตามขั้นตอนการคัดกรองและการเข้าร่วมอบรม</p>
                </div>
                <div class="relative h-60 flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-[#EAE5D9] text-[11px]">
                    <div class="flex items-center gap-2 text-[#2C3E2D]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2C7338]"></span>
                        <span>อนุมัติสิทธิ์ ({{ $confirmed_count }})</span>
                    </div>
                    <div class="flex items-center gap-2 text-[#2C3E2D]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#5A6B47]"></span>
                        <span>เข้าอบรมแล้ว ({{ $attended_count }})</span>
                    </div>
                    <div class="flex items-center gap-2 text-[#2C3E2D]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#C86D51]"></span>
                        <span>บัญชีสำรอง ({{ $waiting_count }})</span>
                    </div>
                    <div class="flex items-center gap-2 text-[#2C3E2D]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#D97706]"></span>
                        <span>รอตรวจสอบ ({{ $pending_count }})</span>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Age Distribution Bar Chart -->
            <div class="earth-admin-card p-6 flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-[#2C3E2D] text-sm mb-1 flex items-center gap-2">
                        <i data-lucide="bar-chart-2" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>การกระจายตัวตามช่วงอายุ (Age Groups)</span>
                    </h3>
                    <p class="text-[11px] text-[#7B8D65] mb-4">วิเคราะห์กลุ่มประชากรที่สนใจการปฏิบัติธรรม</p>
                </div>
                <div class="relative h-60 flex items-center justify-center">
                    <canvas id="ageChart"></canvas>
                </div>
                <div class="mt-4 pt-4 border-t border-[#EAE5D9] flex justify-between items-center text-xs text-[#7B8D65]">
                    <span>ช่วงอายุยอดนิยม:</span>
                    <strong class="text-[#2C3E2D]">
                        @php
                            $topAge = $age_stats->sortByDesc('count')->first();
                        @endphp
                        {{ $topAge ? $topAge->age_group . ' (' . $topAge->count . ' คน)' : '-' }}
                    </strong>
                </div>
            </div>

            <!-- Chart 3: Applicant Type & Gender Radar / Doughnut -->
            <div class="earth-admin-card p-6 flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-[#2C3E2D] text-sm mb-1 flex items-center gap-2">
                        <i data-lucide="users-2" class="w-4 h-4 text-[#C86D51]"></i>
                        <span>สัดส่วนเพศและประเภทผู้สมัคร</span>
                    </h3>
                    <p class="text-[11px] text-[#7B8D65] mb-4">จำแนกระหว่างประชาชนทั่วไปกับนิสิต มจร</p>
                </div>
                <div class="relative h-60 flex items-center justify-center">
                    <canvas id="genderTypeChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-[#EAE5D9] text-[11px]">
                    <div class="flex items-center gap-2 text-[#2C3E2D]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#5A6B47]"></span>
                        <span>ชาย ({{ $gender_stats->where('gender', 'MALE')->first()->count ?? 0 }})</span>
                    </div>
                    <div class="flex items-center gap-2 text-[#2C3E2D]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#C86D51]"></span>
                        <span>หญิง ({{ $gender_stats->where('gender', 'FEMALE')->first()->count ?? 0 }})</span>
                    </div>
                    <div class="flex items-center gap-2 text-[#2C3E2D]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2C3E2D]"></span>
                        <span>นิสิต มจร ({{ $type_stats->where('type_label', 'นิสิต มจร')->first()->count ?? 0 }})</span>
                    </div>
                    <div class="flex items-center gap-2 text-[#2C3E2D]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#8C8275]"></span>
                        <span>ประชาชน ({{ $type_stats->where('type_label', 'ประชาชนทั่วไป')->first()->count ?? 0 }})</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. Demographics & Operational Readiness Row (อาหาร & ภูมิลำเนา) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <!-- อาหารและความต้องการพิเศษ (Dietary Restrictions) -->
            <div class="earth-admin-card p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="font-heading font-bold text-[#2C3E2D] text-sm flex items-center gap-2">
                            <i data-lucide="utensils" class="w-4 h-4 text-[#5A6B47]"></i>
                            <span>สถิติประเภทอาหารสำหรับผู้ปฏิบัติธรรม (Dietary Preparation)</span>
                        </h3>
                        <p class="text-[11px] text-[#7B8D65] mt-0.5">ข้อมูลสำหรับฝ่ายโรงทานและภัตตาหารเพื่อจัดเตรียมอาหาร</p>
                    </div>
                </div>

                <div class="space-y-3.5">
                    @php
                        $dietaryNames = [
                            'NORMAL' => 'อาหารทั่วไป',
                            'VEGETARIAN' => 'อาหารมังสวิรัติ (Vegetarian)',
                            'JAY' => 'อาหารเจ (Jay)',
                            'HALAL' => 'อาหารฮาลาล (Halal)',
                        ];
                    @endphp
                    @forelse ($dietary_stats as $d)
                        @php
                            $label = $dietaryNames[$d->diet_type] ?? $d->diet_type;
                            $pct = $total_registered > 0 ? round(($d->count / $total_registered) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs mb-1.5 text-[#4A3B32]">
                                <span class="font-medium flex items-center gap-1.5">
                                    <i data-lucide="check" class="w-3 h-3 text-[#5A6B47]"></i> {{ $label }}
                                </span>
                                <span class="font-bold text-[#2C3E2D] font-mono">{{ $d->count }} ท่าน ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-[#FAF8F2] border border-[#EAE5D9] rounded-full h-2 overflow-hidden">
                                <div class="bg-[#5A6B47] h-2 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-[#8C8275] text-center py-6">ยังไม่มีข้อมูลประเภทอาหาร</div>
                    @endforelse
                </div>
            </div>

            <!-- ภูมิลำเนาผู้เข้าร่วม (Top Origin Provinces) -->
            <div class="earth-admin-card p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="font-heading font-bold text-[#2C3E2D] text-sm flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#C86D51]"></i>
                            <span>สถิติจำแนกตามภูมิลำเนา (Top Geographic Origins)</span>
                        </h3>
                        <p class="text-[11px] text-[#7B8D65] mt-0.5">จังหวัดที่มีผู้สมัครเข้าร่วมมากที่สุด</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse ($province_stats as $prov)
                        @php
                            $pctProv = $total_registered > 0 ? round(($prov->count / $total_registered) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs mb-1 text-[#4A3B32]">
                                <span class="font-medium flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#C86D51]"></span> {{ $prov->province }}
                                </span>
                                <span class="font-bold text-[#2C3E2D] font-mono">{{ $prov->count }} คน ({{ $pctProv }}%)</span>
                            </div>
                            <div class="w-full bg-[#FAF8F2] border border-[#EAE5D9] rounded-full h-2 overflow-hidden">
                                <div class="bg-[#C86D51] h-2 rounded-full" style="width: {{ $pctProv }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-[#8C8275] text-center py-6">ยังไม่มีข้อมูลจังหวัด</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- 4. Course Performance Matrix Table (ตารางสถิติเปรียบเทียบแต่ละคอร์ส) -->
        <div class="earth-admin-card overflow-hidden">
            <div class="p-5 border-b border-[#EAE5D9] flex flex-wrap justify-between items-center gap-4 bg-[#FAF8F2]/60">
                <div>
                    <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2 text-base">
                        <i data-lucide="table" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                        <span>สรุปผลการดำเนินงานและอัตราการครองโควตาแยกตามคอร์ส (Course Performance Matrix)</span>
                    </h2>
                    <p class="text-xs text-[#7B8D65] mt-0.5">แสดงรายละเอียดโควตา อัตราการรับสมัคร และสัดส่วนผู้เข้าร่วมของแต่ละโครงการ</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                        <tr>
                            <th class="p-4">ชื่อคอร์ส / โครงการ</th>
                            <th class="p-4">ส่วนงานเจ้าของโครงการ</th>
                            <th class="p-4">ช่วงเวลาจัดอบรม</th>
                            <th class="p-4 text-center">โควตารับ (คน)</th>
                            <th class="p-4 text-center">ผู้สมัครทั้งหมด</th>
                            <th class="p-4 text-center">อนุมัติ (Confirmed)</th>
                            <th class="p-4 text-center">สำรอง (Waiting)</th>
                            <th class="p-4 text-center">อัตราการเต็ม (% Fill Rate)</th>
                            <th class="p-4 text-center">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($eventsSummary as $ev)
                            @php
                                // จำนวนผู้ครองสิทธิ์/ผู้สมัครที่ยังไม่ถูกยกเลิก (CONFIRMED + PENDING + ATTENDED)
                                $activeEnrolled = ($ev->confirmed_applicants ?? 0) + ($ev->pending_applicants ?? 0) + ($ev->attended_applicants ?? 0);
                                $fillRate = $ev->max_quota > 0 ? min(100, round(($activeEnrolled / $ev->max_quota) * 100, 1)) : 0;
                                $confirmedRate = $ev->max_quota > 0 ? min(100, round((($ev->confirmed_applicants ?? 0) / $ev->max_quota) * 100, 1)) : 0;
                            @endphp
                            <tr class="hover:bg-[#FAF8F2]/80 transition">
                                <td class="p-4">
                                    <div class="font-heading font-semibold text-[#2C3E2D] text-sm">
                                        {{ $ev->title }}
                                    </div>
                                    <div class="text-[11px] text-[#7B8D65] mt-0.5 flex items-center gap-1.5">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-[#C86D51]"></i>
                                        <span>{{ $ev->location_name }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="font-medium text-[#2C3E2D]">
                                        {{ $ev->organizationUnit->name_th ?? '-' }}
                                    </div>
                                    <div class="text-[10px] text-[#8C8275]">
                                        {{ $ev->organizationUnit->province_th ?? 'มจร' }}
                                    </div>
                                </td>
                                <td class="p-4 font-mono text-[11px] whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($ev->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($ev->end_date)->format('d/m/Y') }}
                                </td>
                                <td class="p-4 text-center font-bold font-mono text-[#2C3E2D]">
                                    {{ number_format($ev->max_quota) }}
                                </td>
                                <td class="p-4 text-center font-bold font-mono text-[#5A6B47]">
                                    {{ number_format($ev->total_applicants) }}
                                </td>
                                <td class="p-4 text-center font-bold font-mono text-[#2C7338]">
                                    {{ number_format($ev->confirmed_applicants) }}
                                    @if (($ev->pending_applicants ?? 0) > 0)
                                        <div class="text-[10px] text-amber-700 font-normal">รอตรวจ {{ $ev->pending_applicants }}</div>
                                    @endif
                                </td>
                                <td class="p-4 text-center font-bold font-mono text-[#C86D51]">
                                    {{ number_format($ev->waiting_applicants) }}
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 bg-[#FAF8F2] border border-[#EAE5D9] rounded-full h-2 overflow-hidden" title="อนุมัติแล้ว: {{ $confirmedRate }}% | สมัครรวม: {{ $fillRate }}%">
                                            <div class="h-2 rounded-full {{ $fillRate >= 100 ? 'bg-[#C86D51]' : 'bg-[#5A6B47]' }}" style="width: {{ $fillRate }}%"></div>
                                        </div>
                                        <span class="font-mono text-[11px] font-bold text-[#2C3E2D]">{{ $fillRate }}%</span>
                                    </div>
                                    @if ($fillRate > $confirmedRate)
                                        <div class="text-[9px] text-[#7B8D65] mt-0.5 font-mono">อนุมัติ {{ $confirmedRate }}%</div>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if ($ev->status === 'OPEN')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#2C7338]/15 text-[#2C7338] border border-[#2C7338]/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2C7338]"></span> เปิดรับสมัคร
                                        </span>
                                    @elseif ($ev->status === 'CLOSED')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#C86D51]/15 text-[#C86D51] border border-[#C86D51]/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#C86D51]"></span> ปิดรับสมัคร
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#8C8275]/15 text-[#8C8275] border border-[#8C8275]/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#8C8275]"></span> เสร็จสิ้นโครงการ
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-[#8C8275]">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-[#D5CEBC]"></i>
                                    <div>ไม่พบคอร์สปฏิบัติธรรมตามเงื่อนไขที่เลือก</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Status Chart
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['อนุมัติสิทธิ์ (Confirmed)', 'เข้าอบรมแล้ว (Attended)', 'รายชื่อสำรอง (Waiting)', 'รอตรวจสอบ (Pending)', 'ยกเลิก/ปฏิเสธ'],
                    datasets: [{
                        data: [
                            {{ $confirmed_count }},
                            {{ $attended_count }},
                            {{ $waiting_count }},
                            {{ $pending_count }},
                            {{ $cancelled_count + $rejected_count }}
                        ],
                        backgroundColor: ['#2C7338', '#5A6B47', '#C86D51', '#D97706', '#A8A29E'],
                        borderWidth: 2,
                        borderColor: '#FFFFFF',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '68%'
                }
            });

            // 2. Age Distribution Chart
            const ctxAge = document.getElementById('ageChart').getContext('2d');
            const ageLabels = {!! json_encode($age_stats->pluck('age_group')) !!};
            const ageData = {!! json_encode($age_stats->pluck('count')) !!};

            new Chart(ctxAge, {
                type: 'bar',
                data: {
                    labels: ageLabels.length ? ageLabels : ['ไม่มีข้อมูล'],
                    datasets: [{
                        label: 'จำนวนผู้สมัคร (คน)',
                        data: ageData.length ? ageData : [0],
                        backgroundColor: '#5A6B47',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: 'Inter' } },
                            grid: { color: '#EAE5D9' }
                        },
                        x: {
                            ticks: { font: { family: 'Sarabun', size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });

            // 3. Gender and Applicant Type Chart
            const ctxGender = document.getElementById('genderTypeChart').getContext('2d');
            new Chart(ctxGender, {
                type: 'doughnut',
                data: {
                    labels: ['ชาย', 'หญิง', 'นิสิต มจร', 'ประชาชนทั่วไป'],
                    datasets: [{
                        data: [
                            {{ $gender_stats->where('gender', 'MALE')->first()->count ?? 0 }},
                            {{ $gender_stats->where('gender', 'FEMALE')->first()->count ?? 0 }},
                            {{ $type_stats->where('type_label', 'นิสิต มจร')->first()->count ?? 0 }},
                            {{ $type_stats->where('type_label', 'ประชาชนทั่วไป')->first()->count ?? 0 }}
                        ],
                        backgroundColor: ['#5A6B47', '#C86D51', '#2C3E2D', '#8C8275'],
                        borderWidth: 2,
                        borderColor: '#FFFFFF',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '65%'
                }
            });
        });

        lucide.createIcons();
    </script>
</body>
</html>
