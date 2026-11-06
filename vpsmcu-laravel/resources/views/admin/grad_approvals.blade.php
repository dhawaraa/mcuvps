<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบหนังสือรับรองการปฏิบัติวิปัสสนากรรมฐาน (e-Document) - VPSMCU Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>

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
<body class="antialiased min-h-screen flex flex-col md:flex-row selection:bg-[#5A6B47] selection:text-white">

    <!-- Earth Tones Sidebar (Deep Forest & Olive) with Collapsible Submenus -->
    @include('admin.layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        
        <!-- Top bar -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">ระบบหนังสือรับรองการปฏิบัติวิปัสสนากรรมฐาน (e-Document)</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">เกณฑ์: ป.โท 30 วัน / ป.เอก 45 วัน &bull; ตรวจสอบหลักฐาน 4 รายการ, สลิปโอนเงิน และอัปโหลดเอกสารตอบกลับ</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <!-- สวิตช์เปิด-ปิดระบบรับคำร้อง e-Document (edocconfig.php) -->
                <form action="{{ route('admin.grad.toggleEdoc') }}" method="POST" class="inline-flex items-center gap-2 bg-[#FAF8F2] px-3.5 py-1.5 rounded-xl border border-[#EAE5D9]">
                    @csrf
                    <span class="text-xs text-[#4A3B32] font-semibold flex items-center gap-1.5">
                        <i data-lucide="power" class="w-3.5 h-3.5 {{ ($edocStatus ?? 'Y') === 'Y' ? 'text-[#5A6B47]' : 'text-[#C86D51]' }}"></i>
                        <span>รับคำร้อง:</span>
                    </span>
                    <select name="edoc_status" onchange="this.form.submit()" class="text-xs font-bold rounded-lg px-2 py-1 border {{ ($edocStatus ?? 'Y') === 'Y' ? 'bg-[#E9EFE2] text-[#3D523E] border-[#CADBC0]' : 'bg-[#FBE8E6] text-[#A85238] border-[#ECD9BF]' }}">
                        <option value="Y" {{ ($edocStatus ?? 'Y') === 'Y' ? 'selected' : '' }}>เปิดระบบ (OPEN)</option>
                        <option value="N" {{ ($edocStatus ?? 'Y') === 'N' ? 'selected' : '' }}>ปิดระบบ (CLOSED)</option>
                    </select>
                </form>

                <!-- Export Excel (register2_excel_index.php) -->
                <a href="{{ route('admin.grad.export', request()->all()) }}" class="inline-flex items-center gap-1.5 bg-[#5A6B47] hover:bg-[#475537] text-white px-3.5 py-2 rounded-xl text-xs font-semibold shadow-sm transition">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Export Excel</span>
                </a>

                <div class="text-xs text-[#4A3B32] flex items-center gap-2 bg-[#FAF8F2] px-3.5 py-2 rounded-xl border border-[#EAE5D9]">
                    <i data-lucide="user-check" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>ผู้ใช้งาน: <strong class="text-[#2C3E2D]">{{ Session::get('admin_user')['name'] ?? 'Admin' }}</strong></span>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-[#5A6B47]/10 border border-[#5A6B47]/30 text-[#2C3E2D] px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="check" class="w-5 h-5 text-[#5A6B47]"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-[#C86D51]/10 border border-[#C86D51]/30 text-[#A85238] px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-[#C86D51]"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-sm">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600"></i>
                    <span>เกิดข้อผิดพลาดในการบันทึกข้อมูล:</span>
                </div>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <!-- Filter & Search Bar (9-Dimension Search ตามระบบ e-Document) -->
        <div class="earth-admin-card p-5 mb-6">
            <form method="GET" action="{{ route('admin.grad.approvals') }}" class="space-y-3">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-bold text-[#4A3B32] flex items-center gap-1.5">
                        <i data-lucide="filter" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>มิติการค้นหา:</span>
                    </span>
                    <select name="rdo_perid" id="rdo_perid" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="6" {{ request('rdo_perid', '6') === '6' ? 'selected' : '' }}>-- แสดงทั้งหมด (All) --</option>
                        <option value="1" {{ request('rdo_perid') === '1' ? 'selected' : '' }}>1. รหัสนิสิต (Student ID)</option>
                        <option value="2" {{ request('rdo_perid') === '2' ? 'selected' : '' }}>2. เลขบัตรประชาชน / Passport</option>
                        <option value="3" {{ request('rdo_perid') === '3' ? 'selected' : '' }}>3. ชื่อ-นามสกุล / ฉายา</option>
                        <option value="4" {{ request('rdo_perid') === '4' ? 'selected' : '' }}>4. คณะสังกัด</option>
                        <option value="5" {{ request('rdo_perid') === '5' ? 'selected' : '' }}>5. สาขาวิชา / หลักสูตร</option>
                        <option value="7" {{ request('rdo_perid') === '7' ? 'selected' : '' }}>7. ระดับการศึกษา (MA/PhD)</option>
                        <option value="8" {{ request('rdo_perid') === '8' ? 'selected' : '' }}>8. วันที่ขอเอกสาร</option>
                        <option value="9" {{ request('rdo_perid') === '9' ? 'selected' : '' }}>9. สถานะเอกสาร</option>
                    </select>

                    <div class="flex items-center gap-2 flex-grow max-w-sm">
                        <input type="text" name="search_val" value="{{ request('search_val', request('search')) }}" placeholder="ระบุคำค้นหาตามมิติที่เลือก..." class="w-full px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                    </div>

                    <select name="degree_level" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ระดับการศึกษา --</option>
                        <option value="ประกาศนียบัตร (7 วัน)" {{ request('degree_level') === 'ประกาศนียบัตร (7 วัน)' ? 'selected' : '' }}>ประกาศนียบัตร (7 วัน)</option>
                        <option value="ประกาศนียบัตร (15 วัน)" {{ request('degree_level') === 'ประกาศนียบัตร (15 วัน)' ? 'selected' : '' }}>ประกาศนียบัตร (15 วัน)</option>
                        <option value="ประกาศนียบัตร (30 วัน)" {{ request('degree_level') === 'ประกาศนียบัตร (30 วัน)' ? 'selected' : '' }}>ประกาศนียบัตร (30 วัน)</option>
                        <option value="ประกาศนียบัตร (90วัน)" {{ request('degree_level') === 'ประกาศนียบัตร (90วัน)' ? 'selected' : '' }}>ประกาศนียบัตร (90วัน)</option>
                        <option value="ปริญญาตรีปีละ (10วัน)" {{ request('degree_level') === 'ปริญญาตรีปีละ (10วัน)' ? 'selected' : '' }}>ปริญญาตรีปีละ (10วัน)</option>
                        <option value="ปริญญาโท (30 วัน)" {{ request('degree_level') === 'ปริญญาโท (30 วัน)' || request('degree_level') === 'MASTER' ? 'selected' : '' }}>ปริญญาโท (30 วัน)</option>
                        <option value="ปริญญาเอก (45 วัน)" {{ request('degree_level') === 'ปริญญาเอก (45 วัน)' || request('degree_level') === 'DOCTORAL' ? 'selected' : '' }}>ปริญญาเอก (45 วัน)</option>
                    </select>

                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- สถานะคำร้อง --</option>
                        <option value="SUBMITTED" {{ request('status') === 'SUBMITTED' ? 'selected' : '' }}>รออนุมัติ (Lock)</option>
                        <option value="APPROVED" {{ request('status') === 'APPROVED' ? 'selected' : '' }}>ผ่านเกณฑ์สมบูรณ์</option>
                        <option value="ACCUMULATING" {{ request('status') === 'ACCUMULATING' ? 'selected' : '' }}>กำลังสะสมวัน</option>
                        <option value="REJECTED" {{ request('status') === 'REJECTED' ? 'selected' : '' }}>ส่งกลับแก้ไข</option>
                    </select>

                    @if ($isCentralOrSuper)
                        <select name="filter_org" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="">-- ทุกสังกัด ({{ count($orgUnits) }} สังกัด) --</option>
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}" {{ request('filter_org') == $org->id ? 'selected' : '' }}>
                                    {{ $org->name_th }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>ค้นหา</span>
                    </button>

                    @if(request()->hasAny(['search', 'search_val', 'rdo_perid', 'degree_level', 'status', 'filter_org']))
                        <a href="{{ route('admin.grad.approvals') }}" class="text-xs text-[#C86D51] hover:underline">ล้างตัวกรอง</a>
                    @endif

                    <div class="ml-auto text-xs text-[#7B8D65]">
                        พบทั้งหมด <strong>{{ $students->total() }}</strong> รายการ
                    </div>
                </div>
            </form>
        </div>


        <!-- Approval Table -->
        <form id="bulk-form" method="POST" action="{{ url('/admin/grad_approvals.php') }}">
            @csrf
            <div class="earth-admin-card overflow-hidden">
                <div class="p-5 border-b border-[#EAE5D9] flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-[#FAF8F2]/60">
                    <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                        <i data-lucide="file-check-2" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>รายการยื่นขออนุมัติสะสมวันทั้งหมด</span>
                    </h2>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5 text-xs text-[#7B8D65]">
                            <span>แสดง:</span>
                            <select onchange="location.href=this.value" class="px-2 py-1 text-xs bg-white border border-[#D5CEBC] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => 1]) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 20, 'page' => 1]) }}" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 50, 'page' => 1]) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 100, 'page' => 1]) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 'all', 'page' => 1]) }}" {{ request('per_page') === 'all' ? 'selected' : '' }}>ทั้งหมด (All)</option>
                            </select>
                            <span>รายการ/หน้า</span>
                        </div>
                        <span class="text-xs bg-[#FAF8F2] border border-[#EAE5D9] px-3 py-1 rounded-full text-[#7B8D65] font-medium">ทั้งหมด {{ $students->total() }} รูป/คน</span>
                    </div>
                </div>

                <!-- Bulk Action Bar -->
                <div id="bulk-action-bar" class="p-3 bg-[#5A6B47]/10 border-b border-[#5A6B47]/20 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2 font-medium text-[#2C3E2D]">
                        <i data-lucide="check-square" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>เลือกแล้ว <strong id="selected-count" class="text-[#5A6B47]">0</strong> รายการ</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <select name="bulk_action" id="bulk-action-select" class="px-3 py-1.5 text-xs bg-white border border-[#D5CEBC] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="">-- เลือกการจัดการจำนวนมาก (Bulk Action) --</option>
                            <option value="APPROVE">อนุมัติผลการสะสมวัน (Approve All)</option>
                            <option value="REJECT">ส่งกลับแก้ไขแฟ้มสะสมวัน (Reject All)</option>
                            <option value="RESET">ปรับสถานะกลับเป็น: กำลังสะสมวัน</option>
                        </select>
                        <button type="button" onclick="submitBulkAction()" class="bg-[#2C3E2D] hover:bg-[#1E2B1F] text-white px-4 py-1.5 rounded-lg text-xs font-medium shadow-sm transition flex items-center gap-1.5">
                            <i data-lucide="play" class="w-3.5 h-3.5"></i> นำไปใช้ (Apply)
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-[#4A3B32]">
                        <thead class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                            <tr>
                                <th class="p-4 w-10 text-center">
                                    <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" class="rounded text-[#5A6B47] focus:ring-[#5A6B47]">
                                </th>
                                <th class="p-4">รูปถ่าย / นิสิต</th>
                                <th class="p-4">ระดับ / สังกัด</th>
                                <th class="p-4 text-center">หลักฐาน</th>
                                <th class="p-4 text-center">ค่าธรรมเนียม / สลิป</th>
                                <th class="p-4 text-center">วันสะสม / เกณฑ์</th>
                                <th class="p-4 text-center">สถานะ</th>
                                <th class="p-4 text-right">ดำเนินการ (Action)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EAE5D9]">
                            @forelse ($students as $s)
                                @php 
                                    $isFull = ($s->accumulated_days >= $s->target_days);
                                @endphp
                                <tr class="hover:bg-[#FAF8F2]/80 transition">
                                    <td class="p-4 text-center">
                                        <input type="checkbox" name="selected_ids[]" value="{{ $s->id }}" onchange="updateSelectedCount()" class="row-checkbox rounded text-[#5A6B47] focus:ring-[#5A6B47]">
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            @if (!empty($s->photo_path))
                                                <a href="{{ asset('storage/' . $s->photo_path) }}" target="_blank" title="ดูรูปถ่ายเต็ม">
                                                    <img src="{{ asset('storage/' . $s->photo_path) }}" alt="Photo" class="w-10 h-12 object-cover rounded-md border border-[#D5CEBC] shadow-xs">
                                                </a>
                                            @else
                                                <div class="w-10 h-12 bg-[#FAF8F2] rounded-md border border-[#EAE5D9] flex items-center justify-center text-[#8C8275]">
                                                    <i data-lucide="user" class="w-5 h-5 text-[#B8AFA0]"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-heading font-bold text-[#2C3E2D] text-sm leading-tight">
                                                    {{ $s->prefix . $s->first_name . ' ' . $s->last_name }}
                                                </div>
                                                @if (!empty($s->buddhist_name) && $s->buddhist_name !== '-')
                                                    <div class="text-xs text-[#5A6B47] font-medium">{{ $s->buddhist_name }}</div>
                                                @endif
                                                <div class="font-mono text-[#7B8D65] text-[11px] mt-0.5">ID: {{ $s->student_code ?? $s->student_id }}</div>
                                                @if (!empty($s->phone))
                                                    <div class="text-[10px] text-[#8C8275] flex items-center gap-1"><i data-lucide="phone" class="w-2.5 h-2.5"></i> {{ $s->phone }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border bg-[#5A6B47]/15 text-[#5A6B47] border-[#5A6B47]/30">
                                            {{ $s->degree_level }}
                                        </span>
                                        <div class="text-[#2C3E2D] font-medium mt-1">{{ $s->organizationUnit->name_th ?? 'มจร' }}</div>
                                        <div class="text-[11px] text-[#7B8D65] line-clamp-1" title="{{ $s->program_name }}">{{ $s->program_name }}</div>
                                    </td>
                                    <td class="p-4 text-center space-y-1">
                                        <!-- เอกสารหลักฐาน e-Document (2 รายการ: 1.สอบอารมณ์, 2.ใบลงเวลา) -->
                                        @if (!empty($s->interview_record_path))
                                            <a href="{{ asset('storage/' . $s->interview_record_path) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] bg-[#FAF8F2] text-[#2C3E2D] border border-[#D5CEBC] hover:bg-[#EAE5D9]">
                                                <i data-lucide="file-check" class="w-3 h-3 text-[#5A6B47]"></i> 1. สอบอารมณ์
                                            </a>
                                        @else
                                            <span class="text-[10px] text-[#B8AFA0] block">1. - ไม่มีใบสอบอารมณ์ -</span>
                                        @endif

                                        @if (!empty($s->attendance_record_path))
                                            <a href="{{ asset('storage/' . $s->attendance_record_path) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] bg-[#FAF8F2] text-[#2C3E2D] border border-[#D5CEBC] hover:bg-[#EAE5D9]">
                                                <i data-lucide="calendar" class="w-3 h-3 text-[#5A6B47]"></i> 2. ใบลงเวลา
                                            </a>
                                        @else
                                            <span class="text-[10px] text-[#B8AFA0] block">2. - ไม่มีใบลงเวลา -</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        @if (!empty($s->slip_path))
                                            <div class="inline-flex flex-col items-center">
                                                <a href="{{ asset('storage/' . $s->slip_path) }}" target="_blank" title="เปิดดูสลิปโอนเงิน (โอนเมื่อ: {{ $s->transfer_date ?? '-' }} {{ $s->transfer_time ? substr($s->transfer_time, 0, 5) : '' }})" class="p-1.5 rounded-lg text-[#C86D51] bg-[#C86D51]/10 border border-[#C86D51]/30 hover:bg-[#C86D51]/20 transition inline-flex items-center justify-center">
                                                    <i data-lucide="receipt" class="w-4 h-4"></i>
                                                </a>
                                                @if (!empty($s->transfer_date))
                                                    <span class="text-[9px] text-[#8C8275] mt-0.5 font-mono leading-tight">
                                                        {{ \Carbon\Carbon::parse($s->transfer_date)->format('d/m/y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-[11px] text-[#B8AFA0]">-</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="font-bold text-sm {{ $isFull ? 'text-[#5A6B47]' : 'text-[#C86D51]' }}">
                                            {{ $s->accumulated_days }} / {{ $s->target_days }}
                                        </span> วัน
                                    </td>
                                    <td class="p-4 text-center">
                                        @if ($s->submission_status === 'APPROVED')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30 inline-flex items-center gap-1">
                                                <i data-lucide="check" class="w-3 h-3"></i> ผ่านสมบูรณ์
                                            </span>
                                        @elseif ($s->submission_status === 'SUBMITTED')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#C86D51]/15 text-[#C86D51] border border-[#C86D51]/30 animate-pulse inline-flex items-center gap-1">
                                                <i data-lucide="lock" class="w-3 h-3"></i> รออนุมัติ
                                            </span>
                                        @elseif ($s->submission_status === 'REJECTED')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-red-100 text-red-800 border border-red-200 inline-flex items-center gap-1">
                                                <i data-lucide="rotate-ccw" class="w-3 h-3"></i> ส่งกลับ
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#FAF8F2] text-[#7B8D65] border border-[#EAE5D9] inline-flex items-center gap-1">
                                                <i data-lucide="file-edit" class="w-3 h-3"></i> สะสมวัน
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right space-x-1 whitespace-nowrap">
                                        <!-- 1. ปุ่มสถานะการอนุมัติ -->
                                        @if ($s->submission_status === 'APPROVED')
                                            <a href="{{ route('grad.certificate', ['code' => $s->student_code ?? $s->student_id]) }}" target="_blank" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-2 py-1 rounded-lg text-xs font-medium inline-flex items-center gap-1 shadow-sm transition">
                                                <i data-lucide="award" class="w-3 h-3"></i> ใบรับรอง
                                            </a>
                                        @elseif ($s->submission_status === 'SUBMITTED')
                                            <form action="{{ route('admin.grad.approve') }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันอนุมัติคำร้องและผลสะสมวันของนิสิตท่านนี้?')">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $s->id }}">
                                                <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-2 py-1 rounded-lg text-xs font-medium inline-flex items-center gap-1 shadow-sm transition">
                                                    <i data-lucide="check" class="w-3 h-3"></i> อนุมัติ
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.grad.reject') }}" method="POST" class="inline-block" onsubmit="return confirm('ส่งกลับให้นิสิตแก้ไข?')">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $s->id }}">
                                                <button type="submit" class="bg-[#C86D51] hover:bg-[#A85238] text-white px-2 py-1 rounded-lg text-xs font-medium inline-flex items-center gap-1 shadow-sm transition">
                                                    <i data-lucide="rotate-ccw" class="w-3 h-3"></i> ส่งกลับ
                                                </button>
                                            </form>
                                        @endif

                                        <!-- 2. ปุ่ม Upload เอกสารตอบกลับ (upcert.php, upcert_en.php, uprcv.php) -->
                                        <button type="button"
                                            onclick="openUploadResponseModal({
                                                id: {{ $s->id }},
                                                student_code: @js($s->student_code ?? $s->student_id),
                                                full_name: @js($s->prefix . $s->first_name . ' ' . $s->last_name),
                                                cert_th: @js($s->cert_th_path ? asset('storage/' . $s->cert_th_path) : null),
                                                cert_en: @js($s->cert_en_path ? asset('storage/' . $s->cert_en_path) : null),
                                                receipt: @js($s->receipt_path ? asset('storage/' . $s->receipt_path) : null),
                                                assessment: @js($s->assessment_doc_path ? asset('storage/' . $s->assessment_doc_path) : null)
                                            })"
                                            title="อัปโหลดเอกสารตอบกลับ (ใบรับรองไทย/EN, ใบเสร็จ, บฑ.๒๑)"
                                            class="p-1.5 bg-[#FAF8F2] hover:bg-[#C86D51]/15 text-[#C86D51] rounded-lg border border-[#EAE5D9] transition inline-flex items-center">
                                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <!-- 3. ปุ่มดูรายละเอียด -->
                                        <button type="button"
                                            onclick="openViewGradModal({
                                                id: {{ $s->id }},
                                                student_code: @js($s->student_code ?? $s->student_id),
                                                citizen_id: @js($s->citizen_id ?? '-'),
                                                full_name: @js($s->prefix . $s->first_name . ' ' . $s->last_name . (!empty($s->buddhist_name) && $s->buddhist_name !== '-' ? ' ' . $s->buddhist_name : '')),
                                                degree_level: @js($s->degree_level),
                                                faculty: @js($s->faculty ?? '-'),
                                                program_name: @js($s->program_name ?? '-'),
                                                org_name: @js($s->organizationUnit->name_th ?? 'มจร'),
                                                accumulated_days: {{ $s->accumulated_days }},
                                                target_days: {{ $s->target_days }},
                                                phone: @js($s->phone ?? '-'),
                                                address: @js(trim(($s->address ?? '') . ' ' . ($s->subdistrict ?? '') . ' ' . ($s->district ?? '') . ' ' . ($s->province ?? '') . ' ' . ($s->postcode ?? '')) ?: '-'),
                                                status: @js($s->submission_status),
                                                submitted_at: @js($s->submitted_at ? \Carbon\Carbon::parse($s->submitted_at)->format('d/m/Y H:i น.') : '-'),
                                                approved_at: @js($s->approved_at ? \Carbon\Carbon::parse($s->approved_at)->format('d/m/Y H:i น.') : '-'),
                                                photo: @js($s->photo_path ? asset('storage/' . $s->photo_path) : null),
                                                slip: @js($s->slip_path ? asset('storage/' . $s->slip_path) : null),
                                                interview: @js($s->interview_record_path ? asset('storage/' . $s->interview_record_path) : null),
                                                attendance: @js($s->attendance_record_path ? asset('storage/' . $s->attendance_record_path) : null)
                                            })"
                                            title="ดูรายละเอียดข้อมูล e-Document"
                                            class="p-1.5 bg-[#FAF8F2] hover:bg-[#5A6B47]/15 text-[#2C3E2D] rounded-lg border border-[#EAE5D9] transition inline-flex items-center">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <!-- 4. ปุ่มแก้ไขข้อมูล -->
                                        <button type="button"
                                            onclick="openEditGradModal({
                                                id: {{ $s->id }},
                                                student_code: @js($s->student_code ?? $s->student_id),
                                                citizen_id: @js($s->citizen_id ?? ''),
                                                nationality: @js($s->nationality ?? 'ไทย'),
                                                prefix: @js($s->prefix ?? ''),
                                                first_name: @js($s->first_name),
                                                last_name: @js($s->last_name),
                                                buddhist_name: @js($s->buddhist_name ?? ''),
                                                age: @js($s->age ?? ''),
                                                vassa: @js($s->vassa ?? ''),
                                                degree_level: @js($s->degree_level),
                                                faculty: @js($s->faculty ?? ''),
                                                program_name: @js($s->program_name ?? ''),
                                                org_unit_id: {{ $s->org_unit_id }},
                                                accumulated_days: {{ $s->accumulated_days }},
                                                target_days: {{ $s->target_days }},
                                                address: @js($s->address ?? ''),
                                                subdistrict: @js($s->subdistrict ?? ''),
                                                district: @js($s->district ?? ''),
                                                province: @js($s->province ?? ''),
                                                postcode: @js($s->postcode ?? ''),
                                                phone: @js($s->phone ?? ''),
                                                transfer_date: @js($s->transfer_date ?? ''),
                                                transfer_time: @js($s->transfer_time ?? ''),
                                                photo: @js($s->photo_path ? asset('storage/' . $s->photo_path) : null),
                                                interview: @js($s->interview_record_path ? asset('storage/' . $s->interview_record_path) : null),
                                                attendance: @js($s->attendance_record_path ? asset('storage/' . $s->attendance_record_path) : null),
                                                slip: @js($s->slip_path ? asset('storage/' . $s->slip_path) : null),
                                                submission_status: @js($s->submission_status)
                                            })"
                                            title="แก้ไขข้อมูลนิสิต"
                                            class="p-1.5 bg-[#FAF8F2] hover:bg-[#5A6B47]/15 text-[#5A6B47] rounded-lg border border-[#EAE5D9] transition inline-flex items-center">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <!-- 5. ลิงก์ดูความคืบหน้าหน้าบ้าน -->
                                        <a href="{{ route('grad.progress', ['student_code' => $s->student_code ?? $s->student_id]) }}" target="_blank" title="ดูแฟ้มสะสมวันออนไลน์" class="p-1.5 bg-[#FAF8F2] hover:bg-[#5A6B47]/15 text-[#7B8D65] rounded-lg border border-[#EAE5D9] transition inline-flex items-center">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        </a>

                                        <!-- 6. ปุ่มลบ -->
                                        <a href="{{ route('admin.grad.student.delete', ['id' => $s->id]) }}" onclick="return confirm('ยืนยันลบคำร้องนิสิตบัณฑิตศึกษาท่านนี้หรือไม่?')" title="ลบข้อมูลนิสิต" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg border border-red-200 transition inline-flex items-center">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-[#8C8275]">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-[#D5CEBC]"></i>
                                        <div>ไม่พบรายการยื่นขออนุมัติสะสมวันตามเงื่อนไข</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($students->hasPages())
                    <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2]/60">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </form>

    </main>

    <!-- Modal: ดูรายละเอียดการสะสมวันนิสิตบัณฑิตศึกษา (View Details) -->
    <div id="view-grad-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-xl w-full p-6 md:p-8 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center">
                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-heading font-bold text-[#2C3E2D]">รายละเอียดนิสิตบัณฑิตศึกษา</h3>
                        <p class="text-xs text-[#7B8D65]" id="view_grad_code">รหัสนิสิต</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('view-grad-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-4 text-xs">
                <!-- Status & Progress -->
                <div class="p-3.5 rounded-2xl bg-[#FAF8F2] border border-[#EAE5D9] flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <span class="text-[10px] text-[#7B8D65] block">สถานะการพิจารณา</span>
                        <span id="view_grad_status_badge" class="font-bold text-xs"></span>
                    </div>
                    <div>
                        <span class="text-[10px] text-[#7B8D65] block">วันที่อนุมัติผล</span>
                        <span id="view_grad_approved_at" class="font-mono text-[#2C3E2D] font-medium">-</span>
                    </div>
                </div>

                <!-- Academic Info -->
                <div class="border border-[#EAE5D9] rounded-2xl p-4 space-y-2 bg-white">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 border-b border-[#FAF8F2] pb-2">
                        <i data-lucide="id-card" class="w-3.5 h-3.5 text-[#5A6B47]"></i> ข้อมูลนิสิตและหลักสูตร
                    </h4>
                    <div class="grid grid-cols-2 gap-2 text-[#4A3B32]">
                        <div><span class="text-[#7B8D65]">ชื่อ-สกุล/ฉายา:</span> <strong id="view_grad_name" class="text-[#2C3E2D]"></strong></div>
                        <div><span class="text-[#7B8D65]">เลขบัตร ปชช.:</span> <span id="view_grad_citizen" class="font-mono text-[#2C3E2D]"></span></div>
                        <div><span class="text-[#7B8D65]">ระดับการศึกษา:</span> <span id="view_grad_degree" class="font-medium text-[#2C3E2D]"></span></div>
                        <div><span class="text-[#7B8D65]">คณะ:</span> <span id="view_grad_faculty"></span></div>
                        <div class="col-span-2"><span class="text-[#7B8D65]">สาขาวิชา/หลักสูตร:</span> <span id="view_grad_program"></span></div>
                        <div class="col-span-2"><span class="text-[#7B8D65]">ส่วนงานต้นสังกัด:</span> <strong id="view_grad_org" class="text-[#2C3E2D]"></strong></div>
                        <div><span class="text-[#7B8D65]">เบอร์โทร:</span> <span id="view_grad_phone" class="font-mono"></span></div>
                        <div><span class="text-[#7B8D65]">วันที่ยื่นคำร้อง:</span> <span id="view_grad_submitted_at" class="font-mono"></span></div>
                        <div class="col-span-2"><span class="text-[#7B8D65]">ที่อยู่/วัดสังกัด:</span> <span id="view_grad_address"></span></div>
                    </div>
                </div>

                <!-- Meditation Credit Days -->
                <div class="border border-[#EAE5D9] rounded-2xl p-4 space-y-2 bg-white">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 border-b border-[#FAF8F2] pb-2">
                        <i data-lucide="calendar-check" class="w-3.5 h-3.5 text-[#5A6B47]"></i> ความคืบหน้าการสะสมวันปฏิบัติธรรม
                    </h4>
                    <div class="flex items-center justify-between text-sm py-1">
                        <span class="text-[#4A3B32]">จำนวนวันสะสม / เกณฑ์ที่ต้องผ่าน:</span>
                        <span id="view_grad_days" class="font-bold text-lg text-[#5A6B47]"></span>
                    </div>
                </div>

                <!-- e-Doc Attachments (4 รายการ) -->
                <div class="border border-[#EAE5D9] rounded-2xl p-4 space-y-3 bg-white">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 border-b border-[#FAF8F2] pb-2">
                        <i data-lucide="paperclip" class="w-3.5 h-3.5 text-[#C86D51]"></i> เอกสารแนบและหลักฐาน (4 รายการ)
                    </h4>
                    <div class="grid grid-cols-2 gap-2" id="view_grad_files">
                        <div id="view_file_photo_box" class="p-2.5 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9] text-center">
                            <span class="text-[10px] text-[#7B8D65] block mb-1">1. รูปถ่าย 2x2 นิ้ว</span>
                            <a id="view_file_photo_link" href="#" target="_blank" class="inline-flex items-center gap-1 text-xs text-[#5A6B47] hover:underline font-semibold">
                                <i data-lucide="image" class="w-3.5 h-3.5"></i> เปิดดูรูปถ่าย
                            </a>
                        </div>
                        <div id="view_file_interview_box" class="p-2.5 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9] text-center">
                            <span class="text-[10px] text-[#7B8D65] block mb-1">2. ใบบันทึกสอบอารมณ์</span>
                            <a id="view_file_interview_link" href="#" target="_blank" class="inline-flex items-center gap-1 text-xs text-[#5A6B47] hover:underline font-semibold">
                                <i data-lucide="file-check" class="w-3.5 h-3.5"></i> เปิดดู PDF
                            </a>
                        </div>
                        <div id="view_file_attendance_box" class="p-2.5 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9] text-center">
                            <span class="text-[10px] text-[#7B8D65] block mb-1">3. ใบลงเวลาปฏิบัติ</span>
                            <a id="view_file_attendance_link" href="#" target="_blank" class="inline-flex items-center gap-1 text-xs text-[#5A6B47] hover:underline font-semibold">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> เปิดดู PDF
                            </a>
                        </div>
                        <div id="view_file_slip_box" class="p-2.5 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9] text-center">
                            <span class="text-[10px] text-[#7B8D65] block mb-1">4. สลิปโอนเงิน</span>
                            <a id="view_file_slip_link" href="#" target="_blank" class="inline-flex items-center gap-1 text-xs text-[#C86D51] hover:underline font-semibold">
                                <i data-lucide="receipt" class="w-3.5 h-3.5"></i> เปิดดูสลิป
                            </a>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-[#EAE5D9] flex justify-end">
                    <button type="button" onclick="document.getElementById('view-grad-modal').classList.add('hidden')" class="px-5 py-2 bg-[#2C3E2D] text-white rounded-xl text-xs font-medium hover:bg-[#1E2B1F] transition">
                        ปิดหน้าต่าง
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: อัปโหลดเอกสารตอบกลับ e-Document (Upload Response Modal) -->
    <div id="upload-response-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-lg w-full p-6 md:p-8 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#C86D51]/15 text-[#C86D51] flex items-center justify-center">
                        <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-heading font-bold text-[#2C3E2D]">อัปโหลดเอกสารตอบกลับ (e-Doc Response)</h3>
                        <p class="text-xs text-[#7B8D65]" id="upload_response_subtitle">ส่งไฟล์ใบรับรอง/ใบเสร็จให้นิสิต</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('upload-response-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.grad.uploadResponse') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="student_id" id="upload_student_id">

                <!-- ข้อมูลนิสิตเป้าหมาย -->
                <div class="p-3 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl space-y-1">
                    <div class="text-[11px] text-[#7B8D65]">นิสิต: <strong id="upload_student_name" class="text-[#2C3E2D]"></strong></div>
                    <div class="text-[11px] text-[#7B8D65]">รหัส: <span id="upload_student_code" class="font-mono text-[#5A6B47] font-bold"></span></div>
                </div>

                <!-- สถานะไฟล์ปัจจุบันที่มีอยู่แล้ว -->
                <div class="border border-[#EAE5D9] rounded-xl p-3 space-y-2 bg-white">
                    <div class="font-bold text-[#4A3B32] text-[11px] mb-1">ไฟล์ตอบกลับในระบบปัจจุบัน:</div>
                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div id="stat_cert_th" class="p-1.5 rounded bg-gray-50 border">ใบรับรองไทย: -</div>
                        <div id="stat_cert_en" class="p-1.5 rounded bg-gray-50 border">ใบรับรอง EN: -</div>
                        <div id="stat_receipt" class="p-1.5 rounded bg-gray-50 border">ใบเสร็จรับเงิน: -</div>
                        <div id="stat_assessment" class="p-1.5 rounded bg-gray-50 border">ใบประเมิน บฑ.๒๑: -</div>
                    </div>
                </div>

                <!-- เลือกประเภทเอกสารที่ต้องการอัปโหลด -->
                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">ประเภทเอกสารที่ต้องการอัปโหลด <span class="text-[#C86D51]">*</span></label>
                    <select name="doc_type" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="cert_th">1. ใบรับรองภาษาไทย (upcert.php)</option>
                        <option value="cert_en">2. ใบรับรองภาษาอังกฤษ (upcert_en.php)</option>
                        <option value="receipt">3. ใบเสร็จรับเงินค่าธรรมเนียม (uprcv.php)</option>
                        <option value="assessment">4. ใบประเมินผล บฑ. ๒๑</option>
                    </select>
                </div>

                <!-- เลือกไฟล์ -->
                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">เลือกไฟล์เอกสาร (PDF, JPG, PNG ขนาดไม่เกิน 10MB) <span class="text-[#C86D51]">*</span></label>
                    <input type="file" name="response_file" required accept="application/pdf,image/jpeg,image/png" class="w-full text-xs text-[#4A3B32] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#5A6B47] file:text-white hover:file:bg-[#2C3E2D]">
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2.5">
                    <button type="button" onclick="document.getElementById('upload-response-modal').classList.add('hidden')" class="px-4 py-2 text-[#6B6357] hover:text-[#2C3E2D] rounded-xl">ยกเลิก</button>
                    <button type="submit" class="px-5 py-2 bg-[#C86D51] hover:bg-[#A85238] text-white rounded-xl font-medium shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="upload" class="w-4 h-4"></i>
                        <span>อัปโหลดเอกสาร</span>
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal: แก้ไขข้อมูลนิสิตบัณฑิตศึกษา (Edit Modal ครบทุกฟิลด์) -->
    <div id="edit-grad-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-3xl w-full p-6 md:p-8 overflow-y-auto max-h-[92vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div>
                    <h3 class="text-lg font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                        <i data-lucide="edit" class="w-5 h-5 text-[#5A6B47]"></i>
                        <span>แก้ไขข้อมูลคำร้องนิสิตบัณฑิตศึกษา</span>
                    </h3>
                    <p class="text-xs text-[#7B8D65]">สามารถแก้ไขได้ทุกฟิลด์ เสมือนแบบฟอร์มที่นิสิตยื่นคำร้องขอหนังสือรับรอง e-Document</p>
                </div>
                <button type="button" onclick="document.getElementById('edit-grad-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="edit-grad-form" method="POST" action="{{ url('/admin/grad_approvals.php') }}" enctype="multipart/form-data" class="space-y-6 text-xs">
                @csrf
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_grad_id">

                <!-- หมวด 1: ข้อมูลส่วนตัว -->
                <div class="bg-[#FAF8F2] p-4 rounded-2xl border border-[#EAE5D9] space-y-3">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 pb-2 border-b border-[#EAE5D9]">
                        <span class="w-5 h-5 rounded-full bg-[#5A6B47] text-white text-[10px] font-bold flex items-center justify-center">1</span>
                        ข้อมูลส่วนตัวและประวัติผู้ยื่นคำร้อง
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">รหัสนิสิต <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="edit_grad_code" name="student_code" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">เลข ปชช. / Passport <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="edit_grad_citizen" name="citizen_id" maxlength="13" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">คำนำหน้าชื่อ <span class="text-[#C86D51]">*</span></label>
                            <select id="edit_grad_prefix" name="prefix" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
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
                            <label class="block font-semibold text-[#4A3B32] mb-1">สัญชาติ</label>
                            <input type="text" id="edit_grad_nationality" name="nationality" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>

                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ชื่อ <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="edit_grad_first_name" name="first_name" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">นามสกุล <span class="text-[#C86D51]">*</span></label>
                            <input type="text" id="edit_grad_last_name" name="last_name" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ฉายาทางธรรม</label>
                            <input type="text" id="edit_grad_buddhist_name" name="buddhist_name" placeholder="เช่น ปุญฺญกาโม หรือ -" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-[#4A3B32] mb-1">อายุ (ปี)</label>
                                <input type="number" id="edit_grad_age" name="age" min="15" max="120" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-1 focus:ring-[#5A6B47]">
                            </div>
                            <div>
                                <label class="block font-semibold text-[#4A3B32] mb-1">พรรษา</label>
                                <input type="number" id="edit_grad_vassa" name="vassa" min="0" max="100" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-1 focus:ring-[#5A6B47]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- หมวด 2: ข้อมูลการศึกษาและสังกัดใน มจร -->
                <div class="bg-[#FAF8F2] p-4 rounded-2xl border border-[#EAE5D9] space-y-3">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 pb-2 border-b border-[#EAE5D9]">
                        <span class="w-5 h-5 rounded-full bg-[#5A6B47] text-white text-[10px] font-bold flex items-center justify-center">2</span>
                        ข้อมูลการศึกษาและสังกัดใน มจร
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ระดับการศึกษา <span class="text-[#C86D51]">*</span></label>
                            <select id="edit_grad_degree" name="degree_level" required onchange="updateGradTargetDays(this.value)" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="ประกาศนียบัตร (7 วัน)">ประกาศนียบัตร (7 วัน)</option>
                                <option value="ประกาศนียบัตร (15 วัน)">ประกาศนียบัตร (15 วัน)</option>
                                <option value="ประกาศนียบัตร (30 วัน)">ประกาศนียบัตร (30 วัน)</option>
                                <option value="ประกาศนียบัตร (90วัน)">ประกาศนียบัตร (90วัน)</option>
                                <option value="ปริญญาตรีปีละ (10วัน)">ปริญญาตรีปีละ (10วัน)</option>
                                <option value="ปริญญาโท (30 วัน)">ปริญญาโท (30 วัน)</option>
                                <option value="ปริญญาเอก (45 วัน)">ปริญญาเอก (45 วัน)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">คณะ</label>
                            <select id="edit_grad_faculty" name="faculty" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="บัณฑิตวิทยาลัย">บัณฑิตวิทยาลัย</option>
                                <option value="พุทธศาสตร์">พุทธศาสตร์</option>
                                <option value="ครุศาสตร์">ครุศาสตร์</option>
                                <option value="มนุษยศาสตร์">มนุษยศาสตร์</option>
                                <option value="สังคมศาสตร์">สังคมศาสตร์</option>
                                <option value="IBSC">วิทยาลัยพุทธศาสตร์นานาชาติ (IBSC)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">สาขาวิชา / หลักสูตร</label>
                            <input type="text" id="edit_grad_program" name="program_name" placeholder="เช่น สาขาวิชาการจัดการเชิงพุทธ" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block font-semibold text-[#4A3B32] mb-1">วิทยาเขต / ส่วนงาน มจร <span class="text-[#C86D51]">*</span></label>
                            <select id="edit_grad_org" name="org_unit_id" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                                @foreach ($orgUnits as $org)
                                    <option value="{{ $org->id }}">{{ $org->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- หมวด 3: ข้อมูลที่อยู่และการติดต่อ -->
                <div class="bg-[#FAF8F2] p-4 rounded-2xl border border-[#EAE5D9] space-y-3">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 pb-2 border-b border-[#EAE5D9]">
                        <span class="w-5 h-5 rounded-full bg-[#5A6B47] text-white text-[10px] font-bold flex items-center justify-center">3</span>
                        ข้อมูลที่อยู่และการติดต่อ
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-[#4A3B32] mb-1">ที่อยู่ / วัด / สังกัด</label>
                            <input type="text" id="edit_grad_address" name="address" placeholder="เช่น 79 หมู่ 1 ต.ลำไทร หรือ วัดมหาธาตุฯ" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ตำบล / แขวง</label>
                            <input type="text" id="edit_grad_subdistrict" name="subdistrict" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">อำเภอ / เขต</label>
                            <input type="text" id="edit_grad_district" name="district" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>

                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">จังหวัด</label>
                            <input type="text" id="edit_grad_province" name="province" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">รหัสไปรษณีย์</label>
                            <input type="text" id="edit_grad_postcode" name="postcode" maxlength="5" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-[#4A3B32] mb-1">หมายเลขโทรศัพท์มือถือ</label>
                            <input type="tel" id="edit_grad_phone" name="phone" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                    </div>
                </div>

                <!-- หมวด 4: ไฟล์เอกสารแนบและหลักฐาน (4 รายการ e-Document) -->
                <div class="bg-[#FAF8F2] p-4 rounded-2xl border border-[#EAE5D9] space-y-3">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 pb-2 border-b border-[#EAE5D9]">
                        <span class="w-5 h-5 rounded-full bg-[#5A6B47] text-white text-[10px] font-bold flex items-center justify-center">4</span>
                        ไฟล์เอกสารแนบและหลักฐาน (4 รายการ)
                    </h4>
                    <p class="text-[11px] text-[#8C8275]">สามารถเลือกไฟล์ใหม่เพื่ออัปโหลดแทนที่ไฟล์เดิมได้ หรือปล่อยว่างไว้หากไม่ต้องการเปลี่ยน</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- ไฟล์ 1: รูปถ่าย -->
                        <div class="p-3.5 bg-white border border-[#D5CEBC] rounded-xl space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="font-semibold text-[#2C3E2D] flex items-center gap-1.5">
                                    <i data-lucide="image" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                    1. รูปถ่ายนิสิต (2x2 นิ้ว)
                                </label>
                                <span id="edit_preview_photo" class="text-[10px]"></span>
                            </div>
                            <input type="file" name="file_photo" accept="image/jpeg,image/png" class="w-full text-xs text-[#4A3B32] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#5A6B47] file:text-white hover:file:bg-[#2C3E2D]">
                        </div>

                        <!-- ไฟล์ 2: ใบบันทึกสอบอารมณ์ -->
                        <div class="p-3.5 bg-white border border-[#D5CEBC] rounded-xl space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="font-semibold text-[#2C3E2D] flex items-center gap-1.5">
                                    <i data-lucide="file-check" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                    2. ใบบันทึกสอบอารมณ์ (PDF)
                                </label>
                                <span id="edit_preview_interview" class="text-[10px]"></span>
                            </div>
                            <input type="file" name="file_interview" accept="application/pdf" class="w-full text-xs text-[#4A3B32] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#2C3E2D] file:text-white hover:file:bg-[#1E2B1F]">
                        </div>

                        <!-- ไฟล์ 3: ใบลงเวลา -->
                        <div class="p-3.5 bg-white border border-[#D5CEBC] rounded-xl space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="font-semibold text-[#2C3E2D] flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                    3. ใบลงเวลาปฏิบัติธรรม (PDF)
                                </label>
                                <span id="edit_preview_attendance" class="text-[10px]"></span>
                            </div>
                            <input type="file" name="file_attendance" accept="application/pdf" class="w-full text-xs text-[#4A3B32] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#2C3E2D] file:text-white hover:file:bg-[#1E2B1F]">
                        </div>

                        <!-- ไฟล์ 4: สลิปโอนเงิน -->
                        <div class="p-3.5 bg-white border border-[#D5CEBC] rounded-xl space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="font-semibold text-[#2C3E2D] flex items-center gap-1.5">
                                    <i data-lucide="receipt" class="w-3.5 h-3.5 text-[#C86D51]"></i>
                                    4. สลิปโอนเงินค่าธรรมเนียม
                                </label>
                                <span id="edit_preview_slip" class="text-[10px]"></span>
                            </div>
                            <input type="file" name="file_slip" accept="image/jpeg,image/png,application/pdf" class="w-full text-xs text-[#4A3B32] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#C86D51] file:text-white hover:file:bg-[#A85238]">
                        </div>
                    </div>
                </div>

                <!-- หมวด 5: วันสะสม ข้อมูลสลิปโอนเงิน และสถานะการอนุมัติ -->
                <div class="bg-[#FAF8F2] p-4 rounded-2xl border border-[#EAE5D9] space-y-3">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 pb-2 border-b border-[#EAE5D9]">
                        <span class="w-5 h-5 rounded-full bg-[#5A6B47] text-white text-[10px] font-bold flex items-center justify-center">5</span>
                        การสะสมวัน ข้อมูลการโอนเงิน และสถานะคำร้อง
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">วันสะสม (วัน) <span class="text-[#C86D51]">*</span></label>
                            <input type="number" id="edit_grad_accumulated" name="accumulated_days" min="0" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-mono font-bold text-[#5A6B47] focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">เกณฑ์เป้าหมาย <span class="text-[#C86D51]">*</span></label>
                            <input type="number" id="edit_grad_target" name="target_days" min="1" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">วันที่โอนในสลิป</label>
                            <input type="date" id="edit_grad_transfer_date" name="transfer_date" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">เวลาที่โอนในสลิป</label>
                            <input type="time" id="edit_grad_transfer_time" name="transfer_time" class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">สถานะคำร้อง <span class="text-[#C86D51]">*</span></label>
                            <select id="edit_grad_status" name="submission_status" required class="w-full px-3 py-2 bg-white border border-[#D5CEBC] rounded-xl text-xs font-semibold focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="ACCUMULATING">กำลังสะสมวัน</option>
                                <option value="SUBMITTED">ยื่นขออนุมัติแล้ว (Lock)</option>
                                <option value="APPROVED">อนุมัติผลสมบูรณ์</option>
                                <option value="REJECTED">ส่งกลับแก้ไข</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2.5">
                    <button type="button" onclick="document.getElementById('edit-grad-modal').classList.add('hidden')" class="px-5 py-2.5 text-[#6B6357] hover:text-[#2C3E2D] rounded-xl transition">ยกเลิก</button>
                    <button type="submit" class="px-6 py-2.5 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white rounded-xl font-medium shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>บันทึกการแก้ไขทั้งหมด</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openViewGradModal(data) {
            document.getElementById('view_grad_code').innerText = 'รหัสนิสิต: ' + data.student_code;
            document.getElementById('view_grad_name').innerText = data.full_name;
            document.getElementById('view_grad_citizen').innerText = data.citizen_id || '-';
            document.getElementById('view_grad_degree').innerText = data.degree_level;
            document.getElementById('view_grad_faculty').innerText = data.faculty || '-';
            document.getElementById('view_grad_program').innerText = data.program_name;
            document.getElementById('view_grad_org').innerText = data.org_name;
            document.getElementById('view_grad_phone').innerText = data.phone || '-';
            document.getElementById('view_grad_submitted_at').innerText = data.submitted_at || '-';
            document.getElementById('view_grad_address').innerText = data.address || '-';
            document.getElementById('view_grad_days').innerText = data.accumulated_days + ' / ' + data.target_days + ' วัน';
            document.getElementById('view_grad_approved_at').innerText = data.approved_at;

            // จัดการลิงก์เอกสารแนบ 4 รายการ
            const photoLink = document.getElementById('view_file_photo_link');
            if (data.photo) {
                photoLink.href = data.photo;
                photoLink.classList.remove('opacity-40', 'pointer-events-none');
            } else {
                photoLink.href = '#';
                photoLink.classList.add('opacity-40', 'pointer-events-none');
            }

            const interviewLink = document.getElementById('view_file_interview_link');
            if (data.interview) {
                interviewLink.href = data.interview;
                interviewLink.classList.remove('opacity-40', 'pointer-events-none');
            } else {
                interviewLink.href = '#';
                interviewLink.classList.add('opacity-40', 'pointer-events-none');
            }

            const attendanceLink = document.getElementById('view_file_attendance_link');
            if (data.attendance) {
                attendanceLink.href = data.attendance;
                attendanceLink.classList.remove('opacity-40', 'pointer-events-none');
            } else {
                attendanceLink.href = '#';
                attendanceLink.classList.add('opacity-40', 'pointer-events-none');
            }

            const slipLink = document.getElementById('view_file_slip_link');
            if (data.slip) {
                slipLink.href = data.slip;
                slipLink.classList.remove('opacity-40', 'pointer-events-none');
            } else {
                slipLink.href = '#';
                slipLink.classList.add('opacity-40', 'pointer-events-none');
            }

            const badge = document.getElementById('view_grad_status_badge');
            if (data.status === 'APPROVED') {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30 inline-block';
                badge.innerText = 'อนุมัติผ่านเกณฑ์สมบูรณ์';
            } else if (data.status === 'SUBMITTED') {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-[#C86D51]/15 text-[#C86D51] border border-[#C86D51]/30 inline-block';
                badge.innerText = 'ยื่นขออนุมัติแล้ว (รอตรวจสอบ)';
            } else if (data.status === 'REJECTED') {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-red-100 text-red-800 border border-red-200 inline-block';
                badge.innerText = 'ส่งกลับแก้ไขแฟ้มสะสมวัน';
            } else {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-[#FAF8F2] text-[#7B8D65] border border-[#EAE5D9] inline-block';
                badge.innerText = 'กำลังสะสมวันปฏิบัติธรรม';
            }

            document.getElementById('view-grad-modal').classList.remove('hidden');
        }

        function openUploadResponseModal(data) {
            document.getElementById('upload_student_id').value = data.id;
            document.getElementById('upload_student_name').innerText = data.full_name;
            document.getElementById('upload_student_code').innerText = data.student_code;
            document.getElementById('upload_response_subtitle').innerText = 'ส่งไฟล์ใบรับรอง/ใบเสร็จให้นิสิต ' + data.student_code;

            // สถานะไฟล์ที่มีอยู่เดิม
            const certThEl = document.getElementById('stat_cert_th');
            if (data.cert_th) {
                certThEl.innerHTML = '<span class="text-[#5A6B47] font-semibold">ใบรับรองไทย: มีแล้ว <a href="' + data.cert_th + '" target="_blank" class="underline">(ดู)</a></span>';
            } else {
                certThEl.innerHTML = '<span class="text-gray-400">ใบรับรองไทย: -</span>';
            }

            const certEnEl = document.getElementById('stat_cert_en');
            if (data.cert_en) {
                certEnEl.innerHTML = '<span class="text-[#5A6B47] font-semibold">ใบรับรอง EN: มีแล้ว <a href="' + data.cert_en + '" target="_blank" class="underline">(ดู)</a></span>';
            } else {
                certEnEl.innerHTML = '<span class="text-gray-400">ใบรับรอง EN: -</span>';
            }

            const receiptEl = document.getElementById('stat_receipt');
            if (data.receipt) {
                receiptEl.innerHTML = '<span class="text-[#C86D51] font-semibold">ใบเสร็จรับเงิน: มีแล้ว <a href="' + data.receipt + '" target="_blank" class="underline">(ดู)</a></span>';
            } else {
                receiptEl.innerHTML = '<span class="text-gray-400">ใบเสร็จรับเงิน: -</span>';
            }

            const assessmentEl = document.getElementById('stat_assessment');
            if (data.assessment) {
                assessmentEl.innerHTML = '<span class="text-[#5A6B47] font-semibold">ใบประเมิน บฑ.๒๑: มีแล้ว <a href="' + data.assessment + '" target="_blank" class="underline">(ดู)</a></span>';
            } else {
                assessmentEl.innerHTML = '<span class="text-gray-400">ใบประเมิน บฑ.๒๑: -</span>';
            }

            document.getElementById('upload-response-modal').classList.remove('hidden');
        }


        function openEditGradModal(data) {
            const form = document.getElementById('edit-grad-form');
            form.action = "{{ url('/admin/grad_approvals.php') }}";
            document.getElementById('edit_grad_id').value = data.id;

            // 1. ข้อมูลส่วนตัว
            document.getElementById('edit_grad_code').value = data.student_code || '';
            document.getElementById('edit_grad_citizen').value = data.citizen_id || '';
            document.getElementById('edit_grad_prefix').value = data.prefix || 'พระมหา';
            document.getElementById('edit_grad_nationality').value = data.nationality || 'ไทย';
            document.getElementById('edit_grad_first_name').value = data.first_name || '';
            document.getElementById('edit_grad_last_name').value = data.last_name || '';
            document.getElementById('edit_grad_buddhist_name').value = data.buddhist_name || '';
            document.getElementById('edit_grad_age').value = data.age || '';
            document.getElementById('edit_grad_vassa').value = data.vassa || '';

            // 2. การศึกษาและสังกัด
            document.getElementById('edit_grad_degree').value = data.degree_level || 'MASTER';
            const facultyEl = document.getElementById('edit_grad_faculty');
            if (facultyEl && data.faculty) {
                facultyEl.value = data.faculty;
            }
            document.getElementById('edit_grad_program').value = data.program_name || '';
            const orgSelect = document.getElementById('edit_grad_org');
            if (orgSelect && data.org_unit_id) {
                orgSelect.value = data.org_unit_id;
            }

            // 3. ที่อยู่และการติดต่อ
            document.getElementById('edit_grad_address').value = data.address || '';
            document.getElementById('edit_grad_subdistrict').value = data.subdistrict || '';
            document.getElementById('edit_grad_district').value = data.district || '';
            document.getElementById('edit_grad_province').value = data.province || '';
            document.getElementById('edit_grad_postcode').value = data.postcode || '';
            document.getElementById('edit_grad_phone').value = data.phone || '';

            // 4. แสดงสถานะไฟล์แนบเดิม 4 รายการ
            const photoPrev = document.getElementById('edit_preview_photo');
            photoPrev.innerHTML = data.photo ? `<a href="${data.photo}" target="_blank" class="text-[#5A6B47] hover:underline font-semibold flex items-center gap-0.5"><i data-lucide="eye" class="w-3 h-3"></i> ดูไฟล์เดิม</a>` : `<span class="text-[#8C8275]">- ยังไม่มีไฟล์ -</span>`;

            const interviewPrev = document.getElementById('edit_preview_interview');
            interviewPrev.innerHTML = data.interview ? `<a href="${data.interview}" target="_blank" class="text-[#5A6B47] hover:underline font-semibold flex items-center gap-0.5"><i data-lucide="eye" class="w-3 h-3"></i> ดูไฟล์เดิม</a>` : `<span class="text-[#8C8275]">- ยังไม่มีไฟล์ -</span>`;

            const attendancePrev = document.getElementById('edit_preview_attendance');
            attendancePrev.innerHTML = data.attendance ? `<a href="${data.attendance}" target="_blank" class="text-[#5A6B47] hover:underline font-semibold flex items-center gap-0.5"><i data-lucide="eye" class="w-3 h-3"></i> ดูไฟล์เดิม</a>` : `<span class="text-[#8C8275]">- ยังไม่มีไฟล์ -</span>`;

            const slipPrev = document.getElementById('edit_preview_slip');
            slipPrev.innerHTML = data.slip ? `<a href="${data.slip}" target="_blank" class="text-[#C86D51] hover:underline font-semibold flex items-center gap-0.5"><i data-lucide="receipt" class="w-3 h-3"></i> ดูสลิปเดิม</a>` : `<span class="text-[#8C8275]">- ยังไม่มีสลิป -</span>`;

            // รีเซ็ตช่องเลือกไฟล์
            const fileInputs = form.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => input.value = '');

            // 5. วันสะสม ข้อมูลสลิป และสถานะ
            document.getElementById('edit_grad_accumulated').value = data.accumulated_days || 0;
            document.getElementById('edit_grad_target').value = data.target_days || 30;
            document.getElementById('edit_grad_transfer_date').value = data.transfer_date || '';
            document.getElementById('edit_grad_transfer_time').value = data.transfer_time ? data.transfer_time.substring(0, 5) : '';
            document.getElementById('edit_grad_status').value = data.submission_status || 'ACCUMULATING';

            if (window.lucide) {
                window.lucide.createIcons();
            }

            document.getElementById('edit-grad-modal').classList.remove('hidden');
        }

        const degreeDaysMap = {
            'ประกาศนียบัตร (7 วัน)': 7,
            'ประกาศนียบัตร (15 วัน)': 15,
            'ประกาศนียบัตร (30 วัน)': 30,
            'ประกาศนียบัตร (90วัน)': 90,
            'ปริญญาตรีปีละ (10วัน)': 10,
            'ปริญญาโท (30 วัน)': 30,
            'ปริญญาเอก (45 วัน)': 45,
            'MASTER': 30,
            'DOCTORAL': 45
        };

        function updateGradTargetDays(degree) {
            const targetInput = document.getElementById('edit_grad_target');
            if (targetInput) {
                targetInput.value = degreeDaysMap[degree] || 30;
            }
        }

        function toggleSelectAll(master) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = master.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const count = checkedBoxes.length;
            const countEl = document.getElementById('selected-count');
            if (countEl) countEl.innerText = count;

            const master = document.getElementById('select-all');
            const totalBoxes = document.querySelectorAll('.row-checkbox');
            if (master && totalBoxes.length > 0) {
                master.checked = (checkedBoxes.length === totalBoxes.length);
            }
        }

        function submitBulkAction() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('กรุณาติ๊กเลือกรายการนิสิตที่ต้องการจัดการอย่างน้อย 1 รายการ');
                return;
            }

            const actionSelect = document.getElementById('bulk-action-select');
            const action = actionSelect.value;
            if (!action) {
                alert('กรุณาเลือกรูปแบบการจัดการ (Bulk Action)');
                actionSelect.focus();
                return;
            }

            let confirmText = `ยืนยันดำเนินการ "${actionSelect.options[actionSelect.selectedIndex].text}" กับรายการที่เลือกทั้งหมด ${checkedBoxes.length} รายการหรือไม่?`;
            if (confirm(confirmText)) {
                document.getElementById('bulk-form').submit();
            }
        }

        lucide.createIcons();
    </script>
</body>
</html>
