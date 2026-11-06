<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการการลงทะเบียน ปริญญาตรี (10 วัน/ปี) - VPSMCU Admin (Laravel)</title>
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
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">ทะเบียนนิสิตปริญญาตรีปฏิบัติธรรม (Module 1)</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">เกณฑ์: ปฏิบัติธรรมปีละ 10 วัน ต่อเนื่อง 4 ปีการศึกษา (รวม 40 วัน)</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('admin.ug.scanner') }}" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-3.5 py-2.5 rounded-xl text-xs font-medium shadow-sm transition flex items-center gap-1.5">
                    <i data-lucide="qr-code" class="w-4 h-4"></i> สแกน QR เช็คชื่อ
                </a>
                <a href="{{ route('admin.ug.attendance') }}" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#2C3E2D] border border-[#D5CEBC] px-3.5 py-2.5 rounded-xl text-xs font-medium shadow-sm transition flex items-center gap-1.5">
                    <i data-lucide="printer" class="w-4 h-4 text-[#5A6B47]"></i> พิมพ์ใบเซ็นชื่อ
                </a>
                <a href="{{ route('admin.ug.export') }}" class="bg-[#2C3E2D] hover:bg-[#1E2B1F] text-white px-3.5 py-2.5 rounded-xl text-xs font-medium shadow-sm transition flex items-center gap-1.5">
                    <i data-lucide="download" class="w-4 h-4 text-[#A3B88C]"></i> Export CSV
                </a>
                <a href="{{ route('ug.register') }}" target="_blank" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#2C3E2D] border border-[#D5CEBC] px-3.5 py-2.5 rounded-xl text-xs font-medium shadow-sm transition flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-4 h-4 text-[#5A6B47]"></i> ลงทะเบียนเพิ่ม
                </a>
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

        @if (isset($errors) && $errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-xs">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="earth-admin-card p-4 mb-6">
            <form method="GET" action="{{ route('admin.ug.students') }}" class="flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-64">
                        <i data-lucide="search" class="w-4 h-4 text-[#8C8275] absolute left-3 top-2.5"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหารหัส, ชื่อ-สกุล, เลขที่สมัคร..." class="w-full pl-9 pr-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                    </div>

                    <select name="batch_id" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] max-w-xs truncate">
                        <option value="">-- ทุกโครงการปฏิบัติธรรม --</option>
                        @foreach ($batches as $b)
                            <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>
                                [{{ $b->academic_year }}] {{ $b->title }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกสถานะ --</option>
                        <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>รอตรวจสอบ (Pending)</option>
                        <option value="APPROVED" {{ request('status') === 'APPROVED' ? 'selected' : '' }}>อนุมัติสิทธิ์แล้ว (Approved)</option>
                        <option value="CHECKED_IN" {{ request('status') === 'CHECKED_IN' ? 'selected' : '' }}>กำลังปฏิบัติธรรม (Checked-in)</option>
                        <option value="COMPLETED" {{ request('status') === 'COMPLETED' ? 'selected' : '' }}>ผ่านเกณฑ์ 10 วัน (Completed)</option>
                        <option value="REJECTED" {{ request('status') === 'REJECTED' ? 'selected' : '' }}>ไม่อนุมัติ (Rejected)</option>
                        <option value="REGISTERED" {{ request('status') === 'REGISTERED' ? 'selected' : '' }}>ลงทะเบียนแล้ว (เดิม)</option>
                    </select>

                    @if ($isCentralOrSuper)
                        <select name="filter_org" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] max-w-xs truncate">
                            <option value="">-- ทุกสังกัด ({{ count($orgUnits) }} สังกัด) --</option>
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}" {{ request('filter_org') == $org->id ? 'selected' : '' }}>
                                    {{ $org->name_th }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-3.5 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> ค้นหา
                    </button>

                    @if(request()->hasAny(['search', 'batch_id', 'status', 'filter_org']))
                        <a href="{{ route('admin.ug.students') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i> ล้างตัวกรอง
                        </a>
                    @endif
                </div>
                <div class="text-xs text-[#7B8D65] self-end lg:self-auto whitespace-nowrap">
                    พบทั้งหมด <strong>{{ $registrations->total() }}</strong> รายการ
                </div>
            </form>
        </div>

        <form id="bulk-form" method="POST" action="{{ url('/admin/ug_students.php') }}">
            @csrf
            <div class="earth-admin-card overflow-hidden">
                <div class="p-5 border-b border-[#EAE5D9] flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-[#FAF8F2]/60">
                    <div class="flex items-center gap-2">
                        <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                            <i data-lucide="list" class="w-4 h-4 text-[#5A6B47]"></i>
                            <span>รายชื่อนิสิตที่ลงทะเบียนในระบบ</span>
                        </h2>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
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
                        <span class="text-xs bg-white border border-[#EAE5D9] px-3 py-1 rounded-full text-[#7B8D65] font-medium">ทั้งหมด {{ $registrations->total() }} รายการ</span>
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
                            <option value="APPROVED">อนุมัติสิทธิ์เข้าร่วม (Approve/Confirmed)</option>
                            <option value="REJECTED">ปฏิเสธคำขอ (Reject)</option>
                            <option value="PENDING">ปรับเป็นรอตรวจสอบ (Pending)</option>
                            <option value="CHECKED_IN">เช็คอินรายงานตัว (Checked-in)</option>
                            <option value="COMPLETED">บันทึกผ่านเกณฑ์ 10 วัน (Passed)</option>
                            <option value="DELETE">ลบข้อมูลที่เลือก (Delete)</option>
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
                                <th class="p-4">เลขที่สมัคร / รหัสนิสิต</th>
                                <th class="p-4">ชื่อ - สกุล / ชั้นปี</th>
                                <th class="p-4">ส่วนงานต้นสังกัด</th>
                                <th class="p-4">โครงการที่เข้าปฏิบัติ</th>
                                <th class="p-4 text-center">สถานะ</th>
                                <th class="p-4 text-right">ดำเนินการ (Action)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EAE5D9]">
                            @forelse ($registrations as $r)
                                <tr class="hover:bg-[#FAF8F2]/80 transition">
                                    <td class="p-4 text-center">
                                        <input type="checkbox" name="selected_ids[]" value="{{ $r->id }}" onchange="updateSelectedCount()" class="row-checkbox rounded text-[#5A6B47] focus:ring-[#5A6B47]">
                                    </td>
                                    <td class="p-4">
                                        <span class="font-mono font-bold text-[#C86D51] text-xs">{{ $r->registration_no }}</span>
                                        <div class="font-mono text-[#7B8D65] text-[11px]">{{ $r->student_code }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-heading font-semibold text-[#2C3E2D] text-sm">
                                            {{ $r->prefix . $r->first_name . ' ' . $r->last_name }}
                                        </div>
                                        <div class="text-[#7B8D65] text-[11px]">ชั้นปีที่ {{ $r->study_year }} &bull; โทร: {{ $r->phone }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-[#2C3E2D] font-medium">{{ $r->organizationUnit->name_th ?? 'มจร' }}</div>
                                        <span class="text-[10px] text-[#7B8D65] font-mono">{{ $r->organizationUnit->code_provincial ?? $r->organizationUnit->code }}</span>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-medium text-[#2C3E2D]">{{ $r->batch->title ?? '-' }}</div>
                                        <div class="text-[10px] text-[#7B8D65]">ปีการศึกษา {{ $r->batch->academic_year ?? '-' }}</div>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if ($r->status === 'COMPLETED')
                                            <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30 items-center justify-center gap-1">
                                                <i data-lucide="check-check" class="w-3.5 h-3.5"></i> ผ่านเกณฑ์ 10 วัน
                                            </span>
                                        @elseif ($r->status === 'CHECKED_IN')
                                            <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold bg-[#2C3E2D]/15 text-[#2C3E2D] border border-[#2C3E2D]/30 items-center justify-center gap-1">
                                                <i data-lucide="activity" class="w-3.5 h-3.5"></i> กำลังปฏิบัติธรรม
                                            </span>
                                        @elseif ($r->status === 'APPROVED')
                                            <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold bg-[#5A6B47]/20 text-[#5A6B47] border border-[#5A6B47]/40 items-center justify-center gap-1">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> อนุมัติสิทธิ์แล้ว
                                            </span>
                                        @elseif ($r->status === 'REJECTED')
                                            <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold bg-red-100 text-red-700 border border-red-300 items-center justify-center gap-1" title="{{ $r->reject_reason }}">
                                                <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> ไม่อนุมัติ
                                            </span>
                                        @elseif ($r->status === 'PENDING')
                                            <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-300 items-center justify-center gap-1">
                                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> รอตรวจสอบ
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold bg-[#C86D51]/15 text-[#C86D51] border border-[#C86D51]/30 items-center justify-center gap-1">
                                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> ลงทะเบียนแล้ว
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                        <!-- ปุ่มอนุมัติสิทธิ์ (Approve) -->
                                        @if ($r->status === 'PENDING' || $r->status === 'REGISTERED')
                                            <a href="{{ url('/admin/ug_students.php?action=approve&id=' . $r->id) }}" onclick="return confirm('ยืนยันอนุมัติสิทธิ์การเข้าร่วมโครงการของ {{ addslashes($r->full_name) }}?')" title="อนุมัติสิทธิ์เข้าร่วมโครงการ" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-2.5 py-1.5 rounded-lg text-xs font-medium inline-flex items-center gap-1 shadow-sm transition">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i> อนุมัติสิทธิ์
                                            </a>
                                            <button type="button" onclick="openUgRejectModal({{ $r->id }}, '{{ addslashes($r->full_name) }}')" title="ปฏิเสธสิทธิ์ (Reject)" class="p-1.5 text-amber-700 hover:bg-amber-100 rounded-lg border border-amber-300 transition inline-flex items-center">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @elseif ($r->status === 'APPROVED')
                                            <a href="{{ url('/admin/ug_students.php?action=checkin&id=' . $r->id) }}" class="bg-[#2C3E2D] hover:bg-[#1E2B1F] text-white px-2.5 py-1.5 rounded-lg text-xs font-medium inline-flex items-center gap-1 shadow-sm transition" title="เช็คอินเข้าปฏิบัติธรรม">
                                                <i data-lucide="qr-code" class="w-3.5 h-3.5"></i> เช็คอิน
                                            </a>
                                            <button type="button" onclick="openUgRejectModal({{ $r->id }}, '{{ addslashes($r->full_name) }}')" title="ยกเลิก/ปฏิเสธสิทธิ์" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg border border-red-200 transition inline-flex items-center">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @elseif ($r->status === 'CHECKED_IN')
                                            <a href="{{ url('/admin/ug_students.php?action=complete&id=' . $r->id) }}" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-2.5 py-1.5 rounded-lg text-xs font-medium inline-flex items-center gap-1 shadow-sm transition">
                                                <i data-lucide="check-check" class="w-3.5 h-3.5"></i> ผ่าน 10 วัน
                                            </a>
                                        @elseif ($r->status === 'COMPLETED')
                                            <a href="{{ route('ug.certificate', ['reg_no' => $r->registration_no]) }}" target="_blank" title="ดูหนังสือรับรอง e-Certificate" class="p-1.5 bg-[#5A6B47]/15 hover:bg-[#5A6B47]/25 text-[#5A6B47] rounded-lg border border-[#5A6B47]/30 transition inline-flex items-center gap-1">
                                                <i data-lucide="award" class="w-3.5 h-3.5"></i>
                                                <span class="text-[10px] font-semibold">ใบรับรอง</span>
                                            </a>
                                        @elseif ($r->status === 'REJECTED')
                                            <a href="{{ url('/admin/ug_students.php?action=approve&id=' . $r->id) }}" onclick="return confirm('ยืนยันกลับมาอนุมัติสิทธิ์ให้ {{ addslashes($r->full_name) }} หรือไม่?')" title="กลับมาอนุมัติสิทธิ์" class="p-1.5 text-[#5A6B47] hover:bg-[#5A6B47]/15 rounded-lg border border-[#5A6B47]/30 transition inline-flex items-center">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif

                                        <!-- ปุ่มดูรายละเอียด -->
                                        <button type="button"
                                            onclick="openViewStudentModal({
                                                id: {{ $r->id }},
                                                registration_no: @js($r->registration_no),
                                                student_code: @js($r->student_code),
                                                citizen_id: @js($r->citizen_id),
                                                full_name: @js($r->prefix . $r->first_name . ' ' . $r->last_name),
                                                study_year: {{ $r->study_year ?? 1 }},
                                                phone: @js($r->phone ?? '-'),
                                                email: @js($r->email ?? '-'),
                                                faculty: @js($r->faculty ?? '-'),
                                                major: @js($r->major ?? '-'),
                                                org_name: @js($r->organizationUnit->name_th ?? 'มจร'),
                                                batch_title: @js($r->batch->title ?? '-'),
                                                batch_year: @js($r->batch->academic_year ?? '-'),
                                                batch_dates: @js(($r->batch->start_date ?? '') . ' ถึง ' . ($r->batch->end_date ?? '')),
                                                registered_at: @js($r->created_at ? $r->created_at->format('d/m/Y H:i น.') : '-'),
                                                status: @js($r->status),
                                                reject_reason: @js($r->reject_reason ?? '')
                                            })"
                                            title="ดูรายละเอียดข้อมูลการลงทะเบียน"
                                            class="p-1.5 bg-[#FAF8F2] hover:bg-[#5A6B47]/15 text-[#2C3E2D] rounded-lg border border-[#EAE5D9] transition inline-flex items-center">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <!-- ปุ่มแก้ไขข้อมูล -->
                                        <button type="button"
                                            onclick="openEditStudentModal({
                                                id: {{ $r->id }},
                                                student_code: @js($r->student_code),
                                                citizen_id: @js($r->citizen_id),
                                                prefix: @js($r->prefix),
                                                first_name: @js($r->first_name),
                                                last_name: @js($r->last_name),
                                                study_year: {{ $r->study_year ?? 1 }},
                                                phone: @js($r->phone ?? ''),
                                                email: @js($r->email ?? ''),
                                                faculty: @js($r->faculty ?? ''),
                                                major: @js($r->major ?? ''),
                                                org_unit_id: {{ $r->org_unit_id }},
                                                batch_id: {{ $r->batch_id }},
                                                status: @js($r->status)
                                            })"
                                            title="แก้ไขข้อมูลนิสิต"
                                            class="p-1.5 bg-[#FAF8F2] hover:bg-[#5A6B47]/15 text-[#5A6B47] rounded-lg border border-[#EAE5D9] transition inline-flex items-center">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <!-- ปุ่มลบ -->
                                        <a href="{{ url('/admin/ug_students.php?action=delete&id=' . $r->id) }}" onclick="return confirm('ยืนยันลบข้อมูลการลงทะเบียนของนิสิตท่านนี้หรือไม่?')" title="ลบข้อมูลการลงทะเบียน" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg border border-red-200 transition inline-flex items-center">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-[#8C8275]">
                                        ไม่พบข้อมูลนิสิตตามเงื่อนไขที่เลือก
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($registrations->hasPages())
                    <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2]/60">
                        {{ $registrations->links() }}
                    </div>
                @endif
            </div>
        </form>
    </main>

    <!-- Modal Form: แก้ไขข้อมูลการลงทะเบียนนิสิต -->
    <div id="edit-student-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-xl w-full p-6 md:p-8 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div>
                    <h3 class="text-lg font-heading font-bold text-[#2C3E2D]">แก้ไขข้อมูลนิสิต ปริญญาตรี</h3>
                    <p class="text-xs text-[#7B8D65]">แก้ไขข้อมูลประจำตัว คณะ สาขาวิชา โครงการ และสถานะการปฏิบัติธรรม</p>
                </div>
                <button type="button" onclick="document.getElementById('edit-student-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="edit-student-form" method="POST" action="{{ url('/admin/ug_students.php') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_student_id">

                @if ($isCentralOrSuper)
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">สังกัดของนิสิต ({{ count($orgUnits) }} สังกัด) <span class="text-[#C86D51]">*</span></label>
                        <select id="edit_student_org_id" name="org_unit_id" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}">{{ $org->name_th }} ({{ $org->province_th ?: 'มจร' }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">รหัสนิสิต (Student Code) <span class="text-[#C86D51]">*</span></label>
                        <input type="text" id="edit_student_code" name="student_code" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">เลขประจำตัวประชาชน 13 หลัก <span class="text-[#C86D51]">*</span></label>
                        <input type="text" id="edit_citizen_id" name="citizen_id" maxlength="13" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">คำนำหน้า <span class="text-[#C86D51]">*</span></label>
                        <select id="edit_prefix" name="prefix" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="พระ">พระภิกษุ</option>
                            <option value="สามเณร">สามเณร</option>
                            <option value="แม่ชี">แม่ชี</option>
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">ชื่อ <span class="text-[#C86D51]">*</span></label>
                        <input type="text" id="edit_first_name" name="first_name" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">นามสกุล / ฉายา <span class="text-[#C86D51]">*</span></label>
                        <input type="text" id="edit_last_name" name="last_name" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">ชั้นปีที่ศึกษา <span class="text-[#C86D51]">*</span></label>
                        <select id="edit_study_year" name="study_year" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                            <option value="1">ปีที่ 1</option>
                            <option value="2">ปีที่ 2</option>
                            <option value="3">ปีที่ 3</option>
                            <option value="4">ปีที่ 4</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" id="edit_phone" name="phone" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">อีเมล</label>
                        <input type="email" id="edit_email" name="email" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">คณะ</label>
                        <input type="text" id="edit_faculty" name="faculty" placeholder="เช่น คณะพุทธศาสตร์" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">สาขาวิชา</label>
                        <input type="text" id="edit_major" name="major" placeholder="เช่น พระพุทธศาสนา" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">โครงการปฏิบัติธรรมที่ลงทะเบียน <span class="text-[#C86D51]">*</span></label>
                    <select id="edit_batch_id" name="batch_id" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        @foreach ($batches as $b)
                            <option value="{{ $b->id }}">{{ $b->title }} (ปี {{ $b->academic_year }} &bull; {{ $b->start_date }} ถึง {{ $b->end_date }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">สถานะการปฏิบัติธรรม <span class="text-[#C86D51]">*</span></label>
                    <select id="edit_reg_status" name="status" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="PENDING">รอตรวจสอบ (Pending)</option>
                        <option value="APPROVED">อนุมัติสิทธิ์แล้ว (Approved)</option>
                        <option value="CHECKED_IN">เข้าปฏิบัติธรรม / เช็คอินแล้ว (Checked In)</option>
                        <option value="COMPLETED">ผ่านเกณฑ์ 10 วัน (Completed)</option>
                        <option value="REJECTED">ไม่อนุมัติ (Rejected)</option>
                        <option value="REGISTERED">ลงทะเบียนแล้ว (Registered)</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2.5">
                    <button type="button" onclick="document.getElementById('edit-student-modal').classList.add('hidden')" class="px-4 py-2 text-[#6B6357] hover:text-[#2C3E2D] rounded-xl">ยกเลิก</button>
                    <button type="submit" class="px-5 py-2 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white rounded-xl font-medium shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>บันทึกการแก้ไข</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Form: ดูรายละเอียดข้อมูลนิสิต (View Details) -->
    <div id="view-student-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-xl w-full p-6 md:p-8 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-heading font-bold text-[#2C3E2D]">รายละเอียดการลงทะเบียนนิสิต</h3>
                        <p class="text-xs text-[#7B8D65]" id="view_reg_no_display">รหัสการสมัคร</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('view-student-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-4 text-xs">
                <!-- Status & Identification -->
                <div class="p-3.5 rounded-2xl bg-[#FAF8F2] border border-[#EAE5D9] flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <span class="text-[10px] text-[#7B8D65] block">สถานะปัจจุบัน</span>
                        <span id="view_status_badge" class="font-bold text-xs"></span>
                    </div>
                    <div>
                        <span class="text-[10px] text-[#7B8D65] block">วันที่บันทึกระบบ</span>
                        <span id="view_registered_at" class="font-mono text-[#2C3E2D] font-medium">-</span>
                    </div>
                </div>

                <!-- Personal Info -->
                <div class="border border-[#EAE5D9] rounded-2xl p-4 space-y-2.5 bg-white">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 border-b border-[#FAF8F2] pb-2">
                        <i data-lucide="id-card" class="w-3.5 h-3.5 text-[#5A6B47]"></i> ข้อมูลประจำตัวนิสิต
                    </h4>
                    <div class="grid grid-cols-2 gap-2 text-[#4A3B32]">
                        <div><span class="text-[#7B8D65]">ชื่อ-สกุล:</span> <strong id="view_full_name" class="text-[#2C3E2D]"></strong></div>
                        <div><span class="text-[#7B8D65]">รหัสนิสิต:</span> <span id="view_student_code" class="font-mono font-bold text-[#C86D51]"></span></div>
                        <div><span class="text-[#7B8D65]">เลข ปชช.:</span> <span id="view_citizen_id" class="font-mono"></span></div>
                        <div><span class="text-[#7B8D65]">ชั้นปี:</span> <span id="view_study_year"></span></div>
                        <div><span class="text-[#7B8D65]">คณะ:</span> <span id="view_faculty"></span></div>
                        <div><span class="text-[#7B8D65]">สาขาวิชา:</span> <span id="view_major"></span></div>
                        <div><span class="text-[#7B8D65]">เบอร์โทร:</span> <span id="view_phone" class="font-mono"></span></div>
                        <div><span class="text-[#7B8D65]">อีเมล:</span> <span id="view_email" class="font-mono"></span></div>
                    </div>
                </div>

                <!-- Organization & Batch -->
                <div class="border border-[#EAE5D9] rounded-2xl p-4 space-y-2 bg-white">
                    <h4 class="font-heading font-bold text-[#2C3E2D] text-xs flex items-center gap-1.5 border-b border-[#FAF8F2] pb-2">
                        <i data-lucide="landmark" class="w-3.5 h-3.5 text-[#5A6B47]"></i> สังกัดและโครงการที่ลงทะเบียน
                    </h4>
                    <div class="space-y-1.5 text-[#4A3B32]">
                        <div><span class="text-[#7B8D65]">ส่วนงาน:</span> <strong id="view_org_name" class="text-[#2C3E2D]"></strong></div>
                        <div><span class="text-[#7B8D65]">โครงการ:</span> <span id="view_batch_title" class="font-medium text-[#2C3E2D]"></span></div>
                        <div class="text-[11px] text-[#7B8D65]"><span id="view_batch_dates"></span> (ปีการศึกษา <span id="view_batch_year"></span>)</div>
                    </div>
                </div>

                <div class="pt-3 border-t border-[#EAE5D9] flex justify-end">
                    <button type="button" onclick="document.getElementById('view-student-modal').classList.add('hidden')" class="px-5 py-2 bg-[#2C3E2D] text-white rounded-xl text-xs font-medium hover:bg-[#1E2B1F] transition">
                        ปิดหน้าต่าง
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openViewStudentModal(student) {
            document.getElementById('view_reg_no_display').innerText = 'เลขที่สมัคร: ' + (student.registration_no || '-');
            document.getElementById('view_full_name').innerText = student.full_name || '-';
            document.getElementById('view_student_code').innerText = student.student_code || '-';
            document.getElementById('view_citizen_id').innerText = student.citizen_id || '-';
            document.getElementById('view_study_year').innerText = 'ชั้นปีที่ ' + (student.study_year || 1);
            document.getElementById('view_faculty').innerText = student.faculty || '-';
            document.getElementById('view_major').innerText = student.major || '-';
            document.getElementById('view_phone').innerText = student.phone || '-';
            document.getElementById('view_email').innerText = student.email || '-';
            document.getElementById('view_org_name').innerText = student.org_name || '-';
            document.getElementById('view_batch_title').innerText = student.batch_title || '-';
            document.getElementById('view_batch_dates').innerText = 'ช่วงเวลา: ' + (student.batch_dates || '-');
            document.getElementById('view_batch_year').innerText = student.batch_year || '-';
            document.getElementById('view_registered_at').innerText = student.registered_at || '-';

            const badge = document.getElementById('view_status_badge');
            if (student.status === 'COMPLETED') {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30 inline-block';
                badge.innerText = 'ผ่านเกณฑ์ 10 วัน (Passed)';
            } else if (student.status === 'CHECKED_IN') {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-[#2C3E2D]/15 text-[#2C3E2D] border border-[#2C3E2D]/30 inline-block';
                badge.innerText = 'กำลังปฏิบัติธรรม (Checked In)';
            } else if (student.status === 'APPROVED') {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-[#5A6B47]/20 text-[#5A6B47] border border-[#5A6B47]/40 inline-block';
                badge.innerText = 'อนุมัติสิทธิ์แล้ว (Approved)';
            } else if (student.status === 'REJECTED') {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-red-100 text-red-700 border border-red-300 inline-block';
                badge.innerText = 'ไม่อนุมัติ (Rejected)' + (student.reject_reason ? ' : ' + student.reject_reason : '');
            } else if (student.status === 'PENDING') {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-300 inline-block';
                badge.innerText = 'รอตรวจสอบคุณสมบัติ (Pending)';
            } else {
                badge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-[#C86D51]/15 text-[#C86D51] border border-[#C86D51]/30 inline-block';
                badge.innerText = 'ลงทะเบียนแล้ว (Registered)';
            }

            document.getElementById('view-student-modal').classList.remove('hidden');
        }

        function openUgRejectModal(id, studentName) {
            document.getElementById('reject-student-name').innerText = studentName;
            document.getElementById('reject-student-id').value = id;
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function closeUgRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }

        function openEditStudentModal(student) {
            const form = document.getElementById('edit-student-form');
            form.action = "{{ url('/admin/ug_students.php') }}";
            document.getElementById('edit_student_id').value = student.id;

            const orgSelect = document.getElementById('edit_student_org_id');
            if (orgSelect) {
                orgSelect.value = student.org_unit_id;
            }

            document.getElementById('edit_student_code').value = student.student_code || '';
            document.getElementById('edit_citizen_id').value = student.citizen_id || '';
            document.getElementById('edit_prefix').value = student.prefix || 'พระ';
            document.getElementById('edit_first_name').value = student.first_name || '';
            document.getElementById('edit_last_name').value = student.last_name || '';
            document.getElementById('edit_study_year').value = student.study_year || 1;
            document.getElementById('edit_phone').value = student.phone || '';
            document.getElementById('edit_email').value = student.email || '';
            document.getElementById('edit_faculty').value = student.faculty || '';
            document.getElementById('edit_major').value = student.major || '';
            document.getElementById('edit_batch_id').value = student.batch_id;
            document.getElementById('edit_reg_status').value = student.status || 'PENDING';

            document.getElementById('edit-student-modal').classList.remove('hidden');
        }

        // Bulk Actions JavaScript
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

            let confirmText = `ยืนยันดำเนินการ "${actionSelect.options[actionSelect.selectedIndex].text}" กับนิสิตที่เลือกทั้งหมด ${checkedBoxes.length} รายการหรือไม่?`;
            if (action === 'DELETE') {
                confirmText = `คำเตือน: ยืนยันลบข้อมูลการลงทะเบียนของนิสิตที่เลือกทั้งหมด ${checkedBoxes.length} รายการอย่างถาวรหรือไม่?`;
            }

            if (confirm(confirmText)) {
                document.getElementById('bulk-form').submit();
            }
        }

        lucide.createIcons();
    </script>

    <!-- Reject Modal -->
    <div id="reject-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-[#D5CEBC]">
            <div class="flex items-center gap-3 text-red-600 mb-4">
                <div class="p-2.5 bg-red-100 rounded-xl">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-base text-[#2C3E2D]">ปฏิเสธคำขอลงทะเบียน</h3>
                    <p class="text-xs text-[#7B8D65]">ระบุเหตุผลในการไม่อนุมัติสิทธิ์</p>
                </div>
            </div>
            
            <p class="text-xs text-[#4A3B32] mb-3">
                กำลังปฏิเสธคำขอของนิสิต: <strong id="reject-student-name" class="text-red-700"></strong>
            </p>

            <form id="reject-form" method="POST" action="{{ url('/admin/ug_students.php?action=reject') }}">
                @csrf
                <input type="hidden" name="id" id="reject-student-id" value="">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1">เหตุผลในการปฏิเสธ <span class="text-red-500">*</span></label>
                    <textarea name="reject_reason" required rows="3" class="w-full text-xs rounded-xl border-[#D5CEBC] bg-[#FAF8F2] p-2.5 text-[#2D2A26] focus:border-red-500 focus:ring-1 focus:ring-red-500" placeholder="เช่น ไม่ใช่นิสิตในวิทยาเขตต้นสังกัด, ติดภารกิจอื่น, คุณสมบัติไม่ครบถ้วน"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" onclick="closeUgRejectModal()" class="px-4 py-2 rounded-xl text-xs font-medium text-[#7B8D65] hover:bg-[#FAF8F2] transition">
                        ยกเลิก
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-red-600 hover:bg-red-700 text-white shadow-sm transition flex items-center gap-1.5">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        <span>ยืนยันปฏิเสธสิทธิ์</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
