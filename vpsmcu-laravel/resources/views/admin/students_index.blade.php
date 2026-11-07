<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ฐานข้อมูลนิสิต (Student Master) - VPSMCU Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
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
            background: rgba(255, 255, 255, 0.95);
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

    <!-- Main Content (Standard Layout: <main class="flex-grow p-6 md:p-10 overflow-y-auto"> NO max-w-7xl) -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">ฐานข้อมูลนิสิต (Student Master)</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">ศูนย์กลางข้อมูลนิสิต มจร ทุกระดับการศึกษา (ป.ตรี, ป.โท, ป.เอก) สำหรับทุกโมดูลในระบบ</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('student.login') }}" target="_blank" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#2C3E2D] border border-[#D5CEBC] px-3.5 py-2.5 rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-1.5">
                    <i data-lucide="external-link" class="w-4 h-4 text-[#5A6B47]"></i> หน้าพอร์ทัลนิสิต
                </a>
                <a href="{{ route('admin.ug.import') }}" class="bg-[#5A6B47] hover:bg-[#465337] text-white px-3.5 py-2.5 rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-1.5">
                    <i data-lucide="file-up" class="w-4 h-4"></i> นำเข้าข้อมูล CSV / Sheets
                </a>
                @if ($isSuperAdmin)
                    <form action="{{ url('/admin/students.php?action=clear_all') }}" method="POST" onsubmit="return confirm('คำเตือน: ยืนยันการลบฐานข้อมูลนิสิตทั้งหมดใช่หรือไม่? ข้อมูลนิสิตทั้งหมดจะถูกล้างออกจากระบบ')" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 px-3.5 py-2.5 rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-1.5">
                            <i data-lucide="trash-2" class="w-4 h-4 text-red-600"></i> ลบฐานข้อมูลทั้งหมด
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="bg-[#5A6B47]/10 border border-[#5A6B47]/30 text-[#2C3E2D] px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="check" class="w-5 h-5 text-[#5A6B47]"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Summary Statistics Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
            <div class="earth-admin-card p-4 flex items-center justify-between">
                <div>
                    <div class="text-[11px] text-[#8C8275] font-semibold">นิสิตทั้งหมด</div>
                    <div class="text-2xl font-bold font-mono text-[#2C3E2D] mt-0.5">{{ number_format($stats['total']) }}</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#5A6B47]/10 text-[#5A6B47] flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>

            <div class="earth-admin-card p-4 flex items-center justify-between">
                <div>
                    <div class="text-[11px] text-[#8C8275] font-semibold">ระดับปริญญาตรี</div>
                    <div class="text-2xl font-bold font-mono text-[#5A6B47] mt-0.5">{{ number_format($stats['bachelor']) }}</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#5A6B47]/10 text-[#5A6B47] flex items-center justify-center">
                    <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                </div>
            </div>

            <div class="earth-admin-card p-4 flex items-center justify-between">
                <div>
                    <div class="text-[11px] text-[#8C8275] font-semibold">ระดับปริญญาโท</div>
                    <div class="text-2xl font-bold font-mono text-[#C86D51] mt-0.5">{{ number_format($stats['master']) }}</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#C86D51]/10 text-[#C86D51] flex items-center justify-center">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>
            </div>

            <div class="earth-admin-card p-4 flex items-center justify-between">
                <div>
                    <div class="text-[11px] text-[#8C8275] font-semibold">ระดับปริญญาเอก</div>
                    <div class="text-2xl font-bold font-mono text-[#2C3E2D] mt-0.5">{{ number_format($stats['doctoral']) }}</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#2C3E2D]/10 text-[#2C3E2D] flex items-center justify-center">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
            </div>

            <div class="earth-admin-card p-4 flex items-center justify-between col-span-2 sm:col-span-1">
                <div>
                    <div class="text-[11px] text-[#8C8275] font-semibold">ประกาศนียบัตร</div>
                    <div class="text-2xl font-bold font-mono text-[#A85238] mt-0.5">{{ number_format($stats['certificate'] ?? 0) }}</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#A85238]/10 text-[#A85238] flex items-center justify-center">
                    <i data-lucide="file-badge" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="earth-admin-card p-4 mb-6">
            <form method="GET" action="{{ route('admin.students.index') }}" class="flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-64">
                        <i data-lucide="search" class="w-4 h-4 text-[#8C8275] absolute left-3 top-2.5"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหารหัส, ชื่อ-สกุล, เบอร์โทร..." 
                            class="w-full pl-9 pr-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                    </div>

                    <select name="degree_level" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกระดับการศึกษา --</option>
                        <option value="BACHELOR" {{ request('degree_level') === 'BACHELOR' ? 'selected' : '' }}>ปริญญาตรี</option>
                        <option value="MASTER" {{ request('degree_level') === 'MASTER' ? 'selected' : '' }}>ปริญญาโท</option>
                        <option value="DOCTORAL" {{ request('degree_level') === 'DOCTORAL' ? 'selected' : '' }}>ปริญญาเอก</option>
                        <option value="CERTIFICATE" {{ request('degree_level') === 'CERTIFICATE' ? 'selected' : '' }}>ประกาศนียบัตร</option>
                    </select>

                    @if ($isSuperAdmin)
                        <select name="filter_org" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] max-w-xs truncate">
                            <option value="">-- ทุกสังกัด ({{ count($orgUnits) }} สังกัด) --</option>
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}" {{ request('filter_org') == $org->id ? 'selected' : '' }}>
                                    {{ $org->name_th }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกสถานะ --</option>
                        <option value="ACTIVE" {{ request('status') === 'ACTIVE' ? 'selected' : '' }}>ปกติ (Active)</option>
                        <option value="INACTIVE" {{ request('status') === 'INACTIVE' ? 'selected' : '' }}>ระงับใช้งาน (Inactive)</option>
                    </select>

                    <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-3.5 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> ค้นหา
                    </button>

                    @if(request()->hasAny(['search', 'degree_level', 'filter_org', 'status']))
                        <a href="{{ route('admin.students.index') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i> ล้างตัวกรอง
                        </a>
                    @endif
                </div>

                <div class="text-xs text-[#6B6357]">
                    พบทั้งหมด <strong class="text-[#2C3E2D]">{{ $students->total() }}</strong> รายการ
                </div>
            </form>
        </div>

        <!-- Student Data Table with Bulk Management -->
        <div class="earth-admin-card overflow-hidden">
            <!-- Table Header Bar & Per Page Selector -->
            <div class="p-4 border-b border-[#EAE5D9] flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-[#FAF8F2]/60">
                <div class="flex items-center gap-2">
                    <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2 text-sm">
                        <i data-lucide="users" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>บัญชีรายชื่อนิสิต (Student Master Records)</span>
                    </h2>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-1.5 text-xs text-[#7B8D65]">
                        <span>แสดง:</span>
                        <select onchange="location.href=this.value" class="px-2 py-1 text-xs bg-white border border-[#D5CEBC] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 20, 'page' => 1]) }}" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 50, 'page' => 1]) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 100, 'page' => 1]) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 200, 'page' => 1]) }}" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 'all', 'page' => 1]) }}" {{ request('per_page') === 'all' ? 'selected' : '' }}>ทั้งหมด (All)</option>
                        </select>
                        <span>รายการ/หน้า</span>
                    </div>
                    <span class="text-xs bg-white border border-[#EAE5D9] px-3 py-1 rounded-full text-[#7B8D65] font-medium">
                        แสดง {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} จาก {{ $students->total() }} รายการ
                    </span>
                </div>
            </div>

            <!-- Bulk Management Form (Compatible with Shared Hosting Action Dispatcher) -->
            <form id="bulk-form" method="POST" action="{{ url('/admin/students.php') }}">
                @csrf
                <input type="hidden" name="action" value="bulk">
                <!-- Bulk Action Toolbar -->
                <div id="bulk-action-bar" class="p-3 bg-[#5A6B47]/10 border-b border-[#5A6B47]/20 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2 font-medium text-[#2C3E2D]">
                        <i data-lucide="check-square" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>เลือกแล้ว <strong id="selected-count" class="text-[#5A6B47] text-sm">0</strong> รายการ</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <select name="bulk_action" id="bulk-action-select" onchange="handleBulkActionChange(this.value)" class="px-3 py-1.5 text-xs bg-white border border-[#D5CEBC] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="">-- เลือกการจัดการทีละหลายรายการ (Bulk Actions) --</option>
                            <option value="ACTIVATE">เปิดใช้งานบัญชี (Activate)</option>
                            <option value="DEACTIVATE">ระงับการใช้งานบัญชี (Deactivate)</option>
                            <option value="RESET_PASSWORD">รีเซ็ตรหัสผ่านเป็นค่าเริ่มต้น (Reset Password)</option>
                            <option value="SET_DEGREE_BACHELOR">ปรับระดับเป็น ปริญญาตรี</option>
                            <option value="SET_DEGREE_MASTER">ปรับระดับเป็น ปริญญาโท</option>
                            <option value="SET_DEGREE_DOCTORAL">ปรับระดับเป็น ปริญญาเอก</option>
                            <option value="SET_DEGREE_CERTIFICATE">ปรับระดับเป็น ประกาศนียบัตร</option>
                            @if ($isSuperAdmin)
                                <option value="CHANGE_ORG">ย้ายสังกัดส่วนงาน (Change Organization)</option>
                            @endif
                            <option value="DELETE" class="text-red-600 font-semibold">ลบข้อมูลนิสิตที่เลือก (Delete Selected)</option>
                            @if ($isSuperAdmin)
                                <option value="CLEAR_ALL" class="text-red-700 font-bold bg-red-50">⚠️ ลบฐานข้อมูลนิสิตทั้งหมด (Clear All Database)</option>
                            @endif
                        </select>

                        <!-- Auxiliary input for CHANGE_ORG -->
                        <select name="target_org_unit_id" id="bulk-target-org" class="hidden px-3 py-1.5 text-xs bg-white border border-[#D5CEBC] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] max-w-xs">
                            <option value="">-- เลือกส่วนงานปลายทาง --</option>
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}">{{ $org->name_th }}</option>
                            @endforeach
                        </select>

                        <!-- Auxiliary input for RESET_PASSWORD -->
                        <input type="text" name="bulk_new_password" id="bulk-new-password" placeholder="รหัสผ่านใหม่ (ดีฟอลต์: password)" class="hidden px-3 py-1.5 text-xs bg-white border border-[#D5CEBC] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">

                        <button type="button" onclick="submitBulkAction()" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition flex items-center gap-1.5">
                            <i data-lucide="play" class="w-3.5 h-3.5"></i>
                            <span>นำไปใช้ (Apply)</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-[#2D2A26]">
                        <thead class="bg-[#243325] text-[#D5CEBC] font-semibold text-[11px] uppercase tracking-wider">
                            <tr>
                                <th class="py-3.5 px-3 w-10 text-center">
                                    <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" class="rounded text-[#5A6B47] focus:ring-[#5A6B47] cursor-pointer">
                                </th>
                                <th class="py-3.5 px-3 w-12 text-center">#</th>
                                <th class="py-3.5 px-4">รหัสนิสิต</th>
                                <th class="py-3.5 px-4">ชื่อ - ฉายา/นามสกุล</th>
                                <th class="py-3.5 px-4">ระดับ</th>
                                <th class="py-3.5 px-4">ส่วนงานต้นสังกัด / คณะ</th>
                                <th class="py-3.5 px-4">เบอร์โทรศัพท์ / อีเมล</th>
                                <th class="py-3.5 px-4 text-center">สถานะบัญชี</th>
                                <th class="py-3.5 px-4 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EAE5D9]">
                            @forelse ($students as $index => $s)
                                <tr class="hover:bg-[#FAF8F2] transition">
                                    <td class="py-3.5 px-3 text-center">
                                        <input type="checkbox" name="selected_ids[]" value="{{ $s->id }}" onchange="updateSelectedCount()" class="row-checkbox rounded text-[#5A6B47] focus:ring-[#5A6B47] cursor-pointer">
                                    </td>
                                    <td class="py-3.5 px-3 text-center text-[#8C8275]">
                                        {{ $students->firstItem() + $index }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-[#2C3E2D]">
                                        {{ $s->student_code }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-semibold text-stone-800 text-sm">{{ $s->full_name }}</div>
                                        @if($s->citizen_id)
                                            <div class="text-[10px] text-[#8C8275] font-mono">ID: {{ substr($s->citizen_id, 0, 1) . 'xxxxxxx' . substr($s->citizen_id, -3) }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($s->degree_level === 'BACHELOR')
                                            <span class="px-2 py-0.5 rounded-full bg-[#5A6B47]/10 text-[#5A6B47] font-semibold text-[10px]">ปริญญาตรี</span>
                                        @elseif($s->degree_level === 'MASTER')
                                            <span class="px-2 py-0.5 rounded-full bg-[#C86D51]/10 text-[#C86D51] font-semibold text-[10px]">ปริญญาโท</span>
                                        @elseif($s->degree_level === 'DOCTORAL')
                                            <span class="px-2 py-0.5 rounded-full bg-[#2C3E2D]/10 text-[#2C3E2D] font-semibold text-[10px]">ปริญญาเอก</span>
                                        @elseif($s->degree_level === 'CERTIFICATE')
                                            <span class="px-2 py-0.5 rounded-full bg-[#A85238]/10 text-[#A85238] font-semibold text-[10px]">ประกาศนียบัตร</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full bg-[#EAE5D9] text-[#4A3B32] font-semibold text-[10px]">{{ $s->degree_level }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-medium text-[#2C3E2D] truncate max-w-xs">{{ $s->organizationUnit?->name_th ?? 'ส่วนกลาง มจร' }}</div>
                                        <div class="text-[11px] text-[#8C8275] truncate max-w-xs">{{ $s->faculty ?? '-' }} / {{ $s->major ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="text-[#2C3E2D] font-mono">{{ $s->phone ?? '-' }}</div>
                                        <div class="text-[11px] text-[#8C8275]">{{ $s->email ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        @if($s->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-[10px] border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> ปกติ
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-red-50 text-red-700 font-semibold text-[10px] border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> ระงับใช้งาน
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- แก้ไขข้อมูลทางการ -->
                                            <button type="button" onclick="openEditModal({{ json_encode($s) }})" title="แก้ไขข้อมูลทางการ"
                                                class="p-1.5 rounded-lg bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#5A6B47] border border-[#D5CEBC] transition">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </button>

                                            <!-- รีเซ็ตรหัสผ่าน -->
                                            <button type="button" onclick="openResetPasswordModal('{{ $s->id }}', '{{ $s->student_code }}', '{{ $s->full_name }}')" title="รีเซ็ตรหัสผ่าน"
                                                class="p-1.5 rounded-lg bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#C86D51] border border-[#D5CEBC] transition">
                                                <i data-lucide="key" class="w-4 h-4"></i>
                                            </button>

                                            <!-- เปิด/ปิดใช้งาน -->
                                            <a href="{{ route('admin.students.toggleStatus', $s->id) }}" 
                                                onclick="return confirm('ยืนยันเปลี่ยนสถานะการใช้งานบัญชีนี้?')"
                                                title="{{ $s->is_active ? 'ระงับการใช้งาน' : 'เปิดใช้งาน' }}"
                                                class="p-1.5 rounded-lg bg-[#FAF8F2] hover:bg-[#EAE5D9] {{ $s->is_active ? 'text-stone-500' : 'text-emerald-600' }} border border-[#D5CEBC] transition">
                                                <i data-lucide="{{ $s->is_active ? 'power-off' : 'check' }}" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-xs text-[#8C8275]">
                                        <i data-lucide="user-x" class="w-8 h-8 mx-auto mb-2 text-[#D5CEBC]"></i>
                                        ไม่พบข้อมูลนิสิตตามเงื่อนไขที่ค้นหา
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            @if ($students->hasPages())
                <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2]">
                    {{ $students->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- Modal 1: แก้ไขข้อมูลทางการนิสิต -->
    <div id="edit-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="earth-admin-card bg-white w-full max-w-xl p-6 overflow-hidden">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#EAE5D9]">
                <h3 class="font-heading font-bold text-base text-[#2C3E2D] flex items-center gap-2">
                    <i data-lucide="user-pen" class="w-5 h-5 text-[#5A6B47]"></i>
                    <span>แก้ไขข้อมูลทางการนิสิต</span>
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-stone-400 hover:text-stone-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="edit-form" method="POST" action="" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">รหัสนิสิต (ล็อก)</label>
                        <input type="text" id="edit-student-code" readonly class="w-full px-3 py-1.5 text-xs bg-stone-100 border border-stone-300 rounded-lg font-mono text-stone-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">เลขประจำตัวประชาชน</label>
                        <input type="text" id="edit-citizen-id" name="citizen_id" maxlength="13" class="w-full px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1">ชื่อ - ฉายา/นามสกุล <span class="text-[#C86D51]">*</span></label>
                    <input type="text" id="edit-name" name="name" required class="w-full px-3 py-2 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">ระดับการศึกษา</label>
                        <select id="edit-degree-level" name="degree_level" required class="w-full px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg">
                            <option value="BACHELOR">ปริญญาตรี</option>
                            <option value="MASTER">ปริญญาโท</option>
                            <option value="DOCTORAL">ปริญญาเอก</option>
                            <option value="CERTIFICATE">ประกาศนียบัตร</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">ส่วนงานต้นสังกัด มจร</label>
                        <select id="edit-org-unit" name="org_unit_id" class="w-full px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg">
                            <option value="">-- ไม่ระบุ / ส่วนกลาง --</option>
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}">{{ $org->name_th }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">คณะ</label>
                        <input type="text" id="edit-faculty" name="faculty" class="w-full px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">สาขาวิชา</label>
                        <input type="text" id="edit-major" name="major" class="w-full px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" id="edit-phone" name="phone" class="w-full px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">อีเมล</label>
                        <input type="email" id="edit-email" name="email" class="w-full px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-[#EAE5D9]">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-xs bg-[#FAF8F2] text-[#6B6357] border border-[#D5CEBC] hover:bg-[#EAE5D9]">ยกเลิก</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs bg-[#5A6B47] text-white hover:bg-[#465337] font-semibold">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: รีเซ็ตรหัสผ่านนิสิต -->
    <div id="reset-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="earth-admin-card bg-white w-full max-w-sm p-6 overflow-hidden">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#EAE5D9]">
                <h3 class="font-heading font-bold text-base text-[#2C3E2D] flex items-center gap-2">
                    <i data-lucide="key" class="w-5 h-5 text-[#C86D51]"></i>
                    <span>รีเซ็ตรหัสผ่านนิสิต</span>
                </h3>
                <button type="button" onclick="closeResetModal()" class="text-stone-400 hover:text-stone-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="reset-form" method="POST" action="" class="space-y-4">
                @csrf
                <div class="p-3 rounded-xl bg-[#FAF8F2] border border-[#EAE5D9] text-xs">
                    <div class="text-[#8C8275]">นิสิต:</div>
                    <div class="font-bold text-[#2C3E2D]" id="reset-student-name">-</div>
                    <div class="font-mono text-[11px] text-[#5A6B47]" id="reset-student-code-text">-</div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1">รหัสผ่านใหม่</label>
                    <input type="text" name="new_password" value="12345678" required class="w-full px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg font-mono">
                    <p class="text-[10px] text-[#8C8275] mt-1">ค่าเริ่มต้นแนะนำ: 12345678</p>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="force_change" name="force_change" value="1" checked class="rounded border-[#D5CEBC] text-[#5A6B47] focus:ring-[#5A6B47]">
                    <label for="force_change" class="text-xs text-[#4A3B32]">บังคับเปลี่ยนรหัสผ่านเมื่อเข้าใช้งานครั้งถัดไป</label>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-[#EAE5D9]">
                    <button type="button" onclick="closeResetModal()" class="px-4 py-2 rounded-xl text-xs bg-[#FAF8F2] text-[#6B6357] border border-[#D5CEBC] hover:bg-[#EAE5D9]">ยกเลิก</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs bg-[#C86D51] text-white hover:bg-[#A85238] font-semibold">ยืนยันรีเซ็ต</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(student) {
            document.getElementById('edit-form').action = '/admin/students/update/' + student.id;
            document.getElementById('edit-student-code').value = student.student_code;
            document.getElementById('edit-citizen-id').value = student.citizen_id || '';
            document.getElementById('edit-name').value = student.name || student.full_name || '';
            document.getElementById('edit-degree-level').value = student.degree_level;
            document.getElementById('edit-org-unit').value = student.org_unit_id || '';
            document.getElementById('edit-faculty').value = student.faculty || '';
            document.getElementById('edit-major').value = student.major || '';
            document.getElementById('edit-phone').value = student.phone || '';
            document.getElementById('edit-email').value = student.email || '';
            
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        function openResetPasswordModal(id, code, name) {
            document.getElementById('reset-form').action = '/admin/students/reset-password/' + id;
            document.getElementById('reset-student-name').innerText = name;
            document.getElementById('reset-student-code-text').innerText = 'รหัส: ' + code;
            document.getElementById('reset-modal').classList.remove('hidden');
        }

        function closeResetModal() {
            document.getElementById('reset-modal').classList.add('hidden');
        }

        // --- Bulk Management Functions ---
        function toggleSelectAll(master) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = master.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            const count = checked.length;
            const countEl = document.getElementById('selected-count');
            if (countEl) countEl.innerText = count;

            const all = document.querySelectorAll('.row-checkbox');
            const master = document.getElementById('select-all');
            if (master) {
                master.checked = all.length > 0 && checked.length === all.length;
            }
        }

        function handleBulkActionChange(action) {
            const orgSelect = document.getElementById('bulk-target-org');
            const passInput = document.getElementById('bulk-new-password');
            if (orgSelect) orgSelect.classList.toggle('hidden', action !== 'CHANGE_ORG');
            if (passInput) passInput.classList.toggle('hidden', action !== 'RESET_PASSWORD');
        }

        function submitBulkAction() {
            const actionSelect = document.getElementById('bulk-action-select');
            const action = actionSelect.value;
            if (!action) {
                alert('กรุณาเลือกรูปแบบการจัดการที่ต้องการนำไปใช้ (Bulk Action)');
                actionSelect.focus();
                return;
            }

            if (action === 'CLEAR_ALL') {
                if (confirm('⚠️ คำเตือนความปลอดภัยขั้นสูงสุด: คุณกำลังจะลบข้อมูลนิสิตทั้งหมดในระบบอย่างถาวร ยืนยันดำเนินการหรือไม่?')) {
                    document.getElementById('bulk-form').submit();
                }
                return;
            }

            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (checked.length === 0) {
                alert('กรุณาคลิกเลือกรายการนิสิตในตารางอย่างน้อย 1 รายการ');
                return;
            }

            if (action === 'CHANGE_ORG') {
                const targetOrg = document.getElementById('bulk-target-org').value;
                if (!targetOrg) {
                    alert('กรุณาเลือกส่วนงานปลายทางที่ต้องการย้าย');
                    document.getElementById('bulk-target-org').focus();
                    return;
                }
            }

            const actionText = actionSelect.options[actionSelect.selectedIndex].text;
            let confirmMsg = `ยืนยันดำเนินการ [${actionText}] กับนิสิตที่เลือกจำนวน ${checked.length} รายการ หรือไม่?`;
            if (action === 'DELETE') {
                confirmMsg = `⚠️ คำเตือน: คุณกำลังจะลบข้อมูลนิสิตที่เลือกจำนวน ${checked.length} รายการออกจากฐานข้อมูลอย่างถาวร ยืนยันดำเนินการหรือไม่?`;
            }

            if (confirm(confirmMsg)) {
                document.getElementById('bulk-form').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
