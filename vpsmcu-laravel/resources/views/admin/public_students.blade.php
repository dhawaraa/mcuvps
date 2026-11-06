<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทะเบียนรายชื่อผู้สมัครอบรมปฏิบัติธรรม - VPSMCU Admin</title>
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

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">
                    @if (!empty($isTrashTab))
                        ถังขยะรายชื่อผู้สมัคร (Trash Bin)
                    @else
                        ทะเบียนผู้สมัครคอร์สปฏิบัติธรรม
                    @endif
                </h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">
                    @if (!empty($isTrashTab))
                        รายการผู้สมัครที่ถูกลบชั่วคราว สามารถกด "กู้คืน (Restore)" กลับสู่ระบบได้ หรือเลือกลบถาวร
                    @else
                        ตรวจสอบสถานะผู้สมัคร, จัดการข้อมูลห้องพัก ยานพาหนะ และอาหาร พร้อมส่งออกรายชื่อ (Export CSV)
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                @if (!empty($isTrashTab))
                    <a href="{{ url('/admin/public_students.php') }}" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-2.5 rounded-xl text-xs font-medium shadow-sm transition flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับสู่ทะเบียนหลัก
                    </a>
                @else
                    <a href="{{ url('/admin/public_students.php?tab=trash') }}" class="relative bg-white hover:bg-stone-50 text-[#8C8275] hover:text-[#C86D51] border border-[#D5CEBC] px-4 py-2.5 rounded-xl text-xs font-medium shadow-sm transition flex items-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4 text-[#C86D51]"></i> ถังขยะ
                        @if (($trashCount ?? 0) > 0)
                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-[#C86D51] rounded-full">
                                {{ $trashCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ url('/admin/public_students.php?' . http_build_query(array_merge(request()->query(), ['action' => 'export']))) }}" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#2C3E2D] border border-[#D5CEBC] px-4 py-2.5 rounded-xl text-xs font-medium shadow-sm transition flex items-center gap-2">
                        <i data-lucide="download" class="w-4 h-4 text-[#5A6B47]"></i> ส่งออก Excel/CSV
                    </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="bg-[#5A6B47]/10 border border-[#5A6B47]/30 text-[#2C3E2D] px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-[#5A6B47]"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="earth-admin-card p-4 mb-6">
            <form method="GET" action="{{ route('admin.public.students') }}" class="flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-64">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-[#8C8275] absolute left-3 top-2.5"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อ, เบอร์โทร, รหัสนิสิต..." class="w-full pl-9 pr-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                    </div>

                    <select name="event_id" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] max-w-xs truncate">
                        <option value="">-- ทุกคอร์ส/โครงการ --</option>
                        @foreach ($events as $ev)
                            <option value="{{ $ev->id }}" {{ request('event_id') == $ev->id ? 'selected' : '' }}>
                                {{ $ev->title }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกสถานะ --</option>
                        <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>รอตรวจสอบ (Pending)</option>
                        <option value="CONFIRMED" {{ request('status') === 'CONFIRMED' ? 'selected' : '' }}>อนุมัติสิทธิ์แล้ว (Confirmed)</option>
                        <option value="WAITING_LIST" {{ request('status') === 'WAITING_LIST' ? 'selected' : '' }}>รายชื่อสำรอง (Waiting List)</option>
                        <option value="ATTENDED" {{ request('status') === 'ATTENDED' ? 'selected' : '' }}>เข้าร่วมอบรมแล้ว (Attended)</option>
                        <option value="REJECTED" {{ request('status') === 'REJECTED' ? 'selected' : '' }}>ปฏิเสธ/ไม่ผ่าน (Rejected)</option>
                        <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>ยกเลิกสิทธิ์ (Cancelled)</option>
                    </select>

                    <select name="applicant_type" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกกลุ่มผู้สมัคร --</option>
                        <option value="PEOPLE" {{ request('applicant_type') === 'PEOPLE' ? 'selected' : '' }}>ประชาชนทั่วไป</option>
                        <option value="STUDENT" {{ request('applicant_type') === 'STUDENT' ? 'selected' : '' }}>นิสิต มจร</option>
                    </select>

                    @if ($isCentralOrSuper)
                        <select name="filter_org" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] max-w-xs truncate">
                            <option value="">-- ทุกส่วนงาน (52 ส่วนงาน) --</option>
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

                    @if(request()->hasAny(['search', 'event_id', 'status', 'applicant_type', 'filter_org']))
                        <a href="{{ route('admin.public.students') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i> ล้างตัวกรอง
                        </a>
                    @endif
                </div>

                <div class="text-xs text-[#7B8D65] self-end lg:self-auto whitespace-nowrap">
                    พบทั้งหมด <strong>{{ $registrations->total() }}</strong> คน
                </div>
            </form>
        </div>

        <!-- Participants Table with Bulk Actions -->
        <div class="earth-admin-card overflow-hidden">
            <form id="bulk-form" method="POST" action="{{ url('/admin/public_students.php') }}">
                @csrf
                <div class="p-5 border-b border-[#EAE5D9] flex flex-wrap justify-between items-center gap-4 bg-[#FAF8F2]/60">
                    <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2 text-sm">
                        <i data-lucide="list-checks" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>รายชื่อผู้สมัครอบรมในระบบ</span>
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
                        <span class="text-xs bg-white border border-[#EAE5D9] px-3 py-1 rounded-full text-[#7B8D65] font-medium">ทั้งหมด {{ $registrations->total() }} คน</span>
                    </div>
                </div>

                <!-- Bulk Action Bar -->
                <div id="bulk-action-bar" class="p-3 bg-[#5A6B47]/10 border-b border-[#5A6B47]/20 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2 font-medium text-[#2C3E2D]">
                        <i data-lucide="check-square" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span>เลือกแล้ว <strong id="selected-count" class="text-[#5A6B47]">0</strong> รายการ</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if (!empty($isTrashTab))
                            <select name="bulk_action" id="bulk-action-select" class="px-3 py-1.5 text-xs bg-white border border-[#D5CEBC] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="">-- เลือกการจัดการถังขยะ (Trash Action) --</option>
                                <option value="RESTORE">กู้คืนข้อมูลที่เลือก (Restore)</option>
                                <option value="FORCE_DELETE">ลบถาวร (Delete Permanently)</option>
                            </select>
                            <button type="button" onclick="submitBulkAction()" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-1.5 rounded-lg text-xs font-medium shadow-sm transition flex items-center gap-1.5">
                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> นำไปใช้ (Apply)
                            </button>
                        @else
                            <select name="bulk_action" id="bulk-action-select" class="px-3 py-1.5 text-xs bg-white border border-[#D5CEBC] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="">-- เลือกการจัดการจำนวนมาก (Bulk Action) --</option>
                                <option value="CONFIRMED">อนุมัติสิทธิ์เข้าร่วม (Approve/Confirmed)</option>
                                <option value="WAITING_LIST">จัดเป็นรายชื่อสำรอง (Waiting List)</option>
                                <option value="REJECTED">ปฏิเสธคำขอ (Reject)</option>
                                <option value="ATTENDED">บันทึกเข้าร่วมอบรมแล้ว (Attended)</option>
                                <option value="PENDING">ปรับเป็นรอตรวจสอบ (Pending)</option>
                                <option value="CANCELLED">ยกเลิกสิทธิ์ (Cancelled)</option>
                                <option value="DELETE">ย้ายไปถังขยะ (Move to Trash)</option>
                            </select>
                            <button type="button" onclick="submitBulkAction()" class="bg-[#2C3E2D] hover:bg-[#1E2B1F] text-white px-4 py-1.5 rounded-lg text-xs font-medium shadow-sm transition flex items-center gap-1.5">
                                <i data-lucide="play" class="w-3.5 h-3.5"></i> นำไปใช้ (Apply)
                            </button>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-[#4A3B32]">
                        <thead class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                            <tr>
                                <th class="p-4 w-10 text-center">
                                    <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" class="rounded text-[#5A6B47] focus:ring-[#5A6B47]">
                                </th>
                                <th class="p-4">คิว / รหัสสมัคร</th>
                                <th class="p-4">ชื่อ - นามสกุล</th>
                                <th class="p-4">คอร์สที่สมัคร</th>
                                <th class="p-4">ที่อยู่ / ยานพาหนะ / ห้องพัก</th>
                                <th class="p-4">อาหาร & ความต้องการ</th>
                                <th class="p-4 text-center">สถานะการคัดกรอง</th>
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
                                        <span class="font-mono font-bold text-[#C86D51] text-sm">Q-{{ str_pad($r->queue_no, 3, '0', STR_PAD_LEFT) }}</span>
                                        <div class="text-[10px] text-[#8C8275] font-mono mt-0.5">{{ $r->registration_no ?: '-' }}</div>
                                        @if ($r->applicant_type === 'STUDENT')
                                            <div class="mt-1 space-y-0.5">
                                                <span class="inline-block px-1.5 py-0.5 rounded text-[9px] bg-blue-50 text-blue-700 border border-blue-200 font-mono">นิสิต มจร: {{ $r->student_id ?: '-' }}</span>
                                                @if ($r->degree_level || $r->faculty || $r->program_name || $r->organizationUnit)
                                                    <div class="text-[10px] text-[#5A6B47]">
                                                        {{ $r->degree_level }} {{ $r->faculty ? '• ' . $r->faculty : '' }}
                                                    </div>
                                                    @if($r->program_name)
                                                        <div class="text-[9px] text-[#7B8D65] truncate max-w-[180px]" title="{{ $r->program_name }}">
                                                            สาขา: {{ $r->program_name }}
                                                        </div>
                                                    @endif
                                                    @if($r->organizationUnit)
                                                        <div class="text-[9px] text-[#8C8275] truncate max-w-[180px]" title="{{ $r->organizationUnit->name_th }}">
                                                            {{ $r->organizationUnit->name_th }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="font-heading font-semibold text-[#2C3E2D] text-sm">
                                            {{ $r->full_name }}
                                        </div>
                                        <div class="text-[11px] text-[#7B8D65] flex flex-wrap items-center gap-1.5 mt-0.5">
                                            <i data-lucide="phone" class="w-3 h-3 text-[#5A6B47]"></i>
                                            <span>{{ $r->phone }}</span>
                                            @if ($r->line_id)
                                                <span>&bull;</span>
                                                <span class="text-[#2C3E2D]">Line: {{ $r->line_id }}</span>
                                            @endif
                                            <span>&bull;</span>
                                            <span>อายุ {{ $r->age }} ปี</span>
                                            @if ($r->vassa > 0)
                                                <span>&bull; {{ $r->vassa }} พรรษา</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-medium text-[#2C3E2D] max-w-xs truncate" title="{{ $r->event->title ?? '-' }}">
                                            {{ $r->event->title ?? '-' }}
                                        </div>
                                        <div class="text-[10px] text-[#7B8D65] mt-0.5">
                                            {{ $r->event->organizationUnit->name_th ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-[11px] text-[#2C3E2D]">
                                            {{ $r->province ?: 'ไม่ระบุจังหวัด' }}
                                        </div>
                                        @if ($r->room_info)
                                            <div class="text-[10px] text-[#5A6B47] flex items-center gap-1 mt-0.5">
                                                <i data-lucide="home" class="w-3 h-3"></i> {{ $r->room_info }}
                                            </div>
                                        @endif
                                        @if ($r->vehicle_info)
                                            <div class="text-[10px] text-[#7B8D65] flex items-center gap-1 mt-0.5">
                                                <i data-lucide="car" class="w-3 h-3"></i> {{ $r->vehicle_info }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="text-[11px] font-medium text-[#2C3E2D]">
                                            @if ($r->dietary_restriction === 'VEGETARIAN')
                                                <span class="text-[#5A6B47]">มังสวิรัติ</span>
                                            @elseif ($r->dietary_restriction === 'JAY')
                                                <span class="text-amber-700">อาหารเจ</span>
                                            @elseif ($r->dietary_restriction === 'HALAL')
                                                <span class="text-teal-700">ฮาลาล</span>
                                            @else
                                                <span class="text-stone-600">อาหารทั่วไป</span>
                                            @endif
                                        </div>
                                        @if ($r->congenital_disease)
                                            <div class="text-[10px] text-[#C86D51] mt-0.5" title="{{ $r->congenital_disease }}">
                                                * {{ Str::limit($r->congenital_disease, 30) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        @if (!empty($isTrashTab))
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-300">
                                                <i data-lucide="trash-2" class="w-3 h-3 text-rose-600"></i>
                                                <span>อยู่ในถังขยะ</span>
                                            </span>
                                            <div class="text-[9px] text-[#8C8275] mt-1 font-mono">
                                                ลบเมื่อ: {{ $r->deleted_at ? \Carbon\Carbon::parse($r->deleted_at)->format('d/m/Y H:i') : '-' }}
                                            </div>
                                        @elseif ($r->status === 'PENDING')
                                            <a href="{{ url('/admin/public_students.php?action=status&id=' . $r->id . '&status_val=CONFIRMED') }}"
                                               onclick="return confirm('ยืนยันอนุมัติสิทธิ์ (Confirmed) ของ {{ addslashes($r->full_name) }}?')"
                                               title="สถานะ: รอตรวจสอบ (คลิกเพื่ออนุมัติสิทธิ์)"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 hover:bg-emerald-600 text-amber-800 hover:text-white border border-amber-300 hover:border-emerald-600 transition shadow-sm hover:scale-105 active:scale-95 group">
                                                <i data-lucide="clock" class="w-3.5 h-3.5 group-hover:hidden"></i>
                                                <i data-lucide="check" class="w-3.5 h-3.5 hidden group-hover:inline"></i>
                                                <span>รอตรวจสอบ</span>
                                            </a>
                                        @elseif ($r->status === 'CONFIRMED')
                                            <a href="{{ url('/admin/public_students.php?action=status&id=' . $r->id . '&status_val=ATTENDED') }}"
                                               onclick="return confirm('ปรับเป็น เข้าร่วมอบรมแล้ว (Attended) หรือไม่?')"
                                               title="สถานะ: อนุมัติสิทธิ์แล้ว (คลิกเพื่อบันทึกเข้าร่วมอบรมแล้ว)"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-[#2C3E2D] hover:bg-[#1E2B1F] text-emerald-300 hover:text-white border border-[#2C3E2D] transition shadow-sm hover:scale-105 active:scale-95">
                                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5 stroke-[2.5]"></i>
                                                <span>อนุมัติสิทธิ์แล้ว</span>
                                            </a>
                                        @elseif ($r->status === 'WAITING_LIST')
                                            <a href="{{ url('/admin/public_students.php?action=status&id=' . $r->id . '&status_val=CONFIRMED') }}"
                                               onclick="return confirm('เลื่อนจากรายชื่อสำรอง เป็น อนุมัติสิทธิ์ (Confirmed) หรือไม่?')"
                                               title="สถานะ: รายชื่อสำรอง (คลิกเพื่อเลื่อนเป็นอนุมัติสิทธิ์)"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-[#C86D51]/15 hover:bg-[#C86D51] text-[#C86D51] hover:text-white border border-[#C86D51]/30 hover:border-[#C86D51] transition shadow-sm hover:scale-105 active:scale-95">
                                                <i data-lucide="hourglass" class="w-3.5 h-3.5"></i>
                                                <span>รายชื่อสำรอง</span>
                                            </a>
                                        @elseif ($r->status === 'ATTENDED')
                                            <a href="{{ url('/admin/public_students.php?action=status&id=' . $r->id . '&status_val=CONFIRMED') }}"
                                               onclick="return confirm('ปรับกลับเป็น อนุมัติสิทธิ์ (Confirmed) หรือไม่?')"
                                               title="สถานะ: เข้าร่วมอบรมแล้ว (คลิกเพื่อปรับกลับเป็นอนุมัติสิทธิ์)"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-700 hover:bg-emerald-800 text-white border border-emerald-600 transition shadow-sm hover:scale-105 active:scale-95">
                                                <i data-lucide="user-check" class="w-3.5 h-3.5 stroke-[2.5]"></i>
                                                <span>เข้าร่วมแล้ว</span>
                                            </a>
                                        @elseif ($r->status === 'REJECTED')
                                            <a href="{{ url('/admin/public_students.php?action=status&id=' . $r->id . '&status_val=PENDING') }}"
                                               onclick="return confirm('ปรับกลับเป็น รอตรวจสอบ (Pending) หรือไม่?')"
                                               title="สถานะ: ไม่อนุมัติ / ปฏิเสธ (คลิกเพื่อคืนสถานะรอตรวจสอบ) - เหตุผล: {{ $r->reject_reason ?: '-' }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-[#8E2818] hover:bg-[#6D1B0E] text-rose-100 hover:text-white border border-[#8E2818] transition shadow-sm hover:scale-105 active:scale-95">
                                                <i data-lucide="x-circle" class="w-3.5 h-3.5 stroke-[2.5]"></i>
                                                <span>ไม่อนุมัติ</span>
                                            </a>
                                        @else
                                            <a href="{{ url('/admin/public_students.php?action=status&id=' . $r->id . '&status_val=PENDING') }}"
                                               onclick="return confirm('ปรับกลับเป็น รอตรวจสอบ (Pending) หรือไม่?')"
                                               title="สถานะ: ยกเลิกสิทธิ์ (คลิกเพื่อคืนสถานะรอตรวจสอบ)"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-stone-600 hover:bg-stone-700 text-stone-200 hover:text-white border border-stone-500 transition shadow-sm hover:scale-105 active:scale-95">
                                                <i data-lucide="slash" class="w-3.5 h-3.5"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if (!empty($isTrashTab))
                                                <!-- ปุ่มกู้คืนจากถังขยะ -->
                                                <a href="{{ url('/admin/public_students.php?action=restore&id=' . $r->id) }}" onclick="return confirm('ยืนยันกู้คืนข้อมูลผู้สมัคร ({{ addslashes($r->full_name) }}) กลับสู่ระบบหรือไม่?')" title="กู้คืนข้อมูล (Restore)" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-[#5A6B47] hover:bg-[#2C3E2D] text-white rounded-lg shadow-sm transition">
                                                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                    <span>กู้คืน</span>
                                                </a>

                                                <!-- ปุ่มลบถาวร -->
                                                <a href="{{ url('/admin/public_students.php?action=force_delete&id=' . $r->id) }}" onclick="return confirm('คำเตือน: ยืนยันลบข้อมูลผู้สมัคร ({{ addslashes($r->full_name) }}) ออกจากระบบถาวรหรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้')" title="ลบถาวร (Delete Permanently)" class="p-1.5 text-red-600 hover:bg-red-100 rounded-lg border border-red-200 transition">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </a>
                                            @else
                                                <!-- ปุ่มดูข้อมูลผู้สมัคร (View Full Details) -->
                                                <button type="button" onclick="openViewApplicantModal({{ json_encode($r) }})" title="ดูรายละเอียดข้อมูลผู้สมัคร" class="p-1.5 text-[#2C3E2D] hover:bg-[#FAF8F2] rounded-lg border border-[#D5CEBC] transition">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </button>

                                                <!-- ปุ่มแก้ไขข้อมูลผู้สมัคร (ทุกฟิลด์) -->
                                                <button type="button" onclick="openEditApplicantModal({{ json_encode($r) }})" title="แก้ไขข้อมูลผู้สมัคร" class="p-1.5 text-[#5A6B47] hover:bg-[#5A6B47]/15 rounded-lg border border-[#5A6B47]/30 transition">
                                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                                </button>

                                                <!-- ปุ่มอนุมัติสิทธิ์ -->
                                                @if ($r->status !== 'CONFIRMED' && $r->status !== 'ATTENDED')
                                                    <a href="{{ url('/admin/public_students.php?action=approve&id=' . $r->id) }}" onclick="return confirm('ยืนยันอนุมัติสิทธิ์การเข้าร่วมอบรมของ {{ addslashes($r->full_name) }}?')" title="อนุมัติสิทธิ์เข้าร่วม (Approve)" class="p-1.5 text-[#5A6B47] hover:bg-[#5A6B47]/15 rounded-lg border border-[#5A6B47]/30 transition">
                                                        <i data-lucide="check" class="w-4 h-4"></i>
                                                    </a>
                                                @endif

                                                <!-- ปุ่มปฏิเสธสิทธิ์ -->
                                                @if ($r->status !== 'REJECTED')
                                                    <button type="button" onclick="openRejectModal({{ $r->id }}, '{{ addslashes($r->full_name) }}')" title="ปฏิเสธ/ไม่อนุมัติ (Reject)" class="p-1.5 text-amber-700 hover:bg-amber-100 rounded-lg border border-amber-300 transition">
                                                        <i data-lucide="x" class="w-4 h-4"></i>
                                                    </button>
                                                @endif

                                                <!-- ปุ่มย้ายไปถังขยะ -->
                                                <a href="{{ url('/admin/public_students.php?action=delete&id=' . $r->id) }}" onclick="return confirm('ยืนยันย้ายข้อมูลผู้สมัครท่านนี้ไปยังถังขยะหรือไม่? (สามารถกู้คืนได้ภายหลัง)')" title="ย้ายไปถังขยะ" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-[#8C8275]">
                                        <i data-lucide="{{ !empty($isTrashTab) ? 'trash' : 'user-x' }}" class="w-8 h-8 mx-auto mb-2 text-[#C86D51]"></i>
                                        <div>
                                            @if (!empty($isTrashTab))
                                                ไม่มีข้อมูลในถังขยะ
                                            @else
                                                ยังไม่มีข้อมูลผู้สมัครในเงื่อนไขนี้
                                            @endif
                                        </div>
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
            </form>
        </div>

    </main>

    <!-- Modal Form: ระบุเหตุผลการปฏิเสธคำขอ -->
    <div id="reject-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-md w-full p-6 md:p-8">
            <div class="flex justify-between items-center pb-3 border-b border-[#EAE5D9] mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-xl bg-red-100 text-red-700">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-base font-heading font-bold text-[#2C3E2D]">ปฏิเสธคำขอเข้าร่วม</h3>
                </div>
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="reject-form" method="POST" action="{{ url('/admin/public_students.php?action=reject') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="id" id="reject-applicant-id" value="">
                <div>
                    <p class="text-[#4A3B32] mb-2">ผู้สมัคร: <strong id="reject-applicant-name" class="text-[#2C3E2D]"></strong></p>
                    <label class="block font-semibold text-[#4A3B32] mb-1">ระบุเหตุผลในการไม่อนุมัติ <span class="text-[#C86D51]">*</span></label>
                    <select name="reject_reason" id="reject-reason-select" onchange="toggleCustomReason(this.value)" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] mb-2">
                        <option value="คุณสมบัติหรือข้อมูลไม่ผ่านเกณฑ์การอบรม">คุณสมบัติหรือข้อมูลไม่ผ่านเกณฑ์การอบรม</option>
                        <option value="ไม่สามารถติดต่อหมายเลขโทรศัพท์ที่ระบุได้">ไม่สามารถติดต่อหมายเลขโทรศัพท์ที่ระบุได้</option>
                        <option value="ข้อจำกัดด้านสุขภาพไม่เอื้ออำนวยต่อหลักสูตรเข้มข้น">ข้อจำกัดด้านสุขภาพไม่เอื้ออำนวยต่อหลักสูตรเข้มข้น</option>
                        <option value="ห้องพักสำหรับประเภทผู้สมัครเต็มแล้ว">ห้องพักสำหรับประเภทผู้สมัครเต็มแล้ว</option>
                        <option value="OTHER">ระบุเหตุผลอื่น ๆ...</option>
                    </select>
                    <input type="text" name="custom_reason" id="custom-reason-input" placeholder="พิมพ์เหตุผลเพิ่มเติม..." class="hidden w-full px-3 py-2 bg-white border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                </div>

                <div class="pt-3 border-t border-[#EAE5D9] flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="px-4 py-2 border border-[#EAE5D9] rounded-xl hover:bg-stone-50 font-medium">ยกเลิก</button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl font-medium shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> ยืนยันการปฏิเสธ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: ดูรายละเอียดข้อมูลผู้สมัคร (View Full Details Modal) -->
    <div id="view-applicant-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-3xl w-full p-6 md:p-8 overflow-y-auto max-h-[92vh] space-y-5">
            <!-- Header -->
            <div class="flex justify-between items-start pb-4 border-b border-[#EAE5D9]">
                <div>
                    <div class="flex items-center gap-2">
                        <span id="view_app_status_badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold"></span>
                        <span id="view_app_type_badge" class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-stone-100 text-stone-700 border border-stone-200"></span>
                    </div>
                    <h3 id="view_app_full_name" class="text-xl font-heading font-bold text-[#2C3E2D] mt-1.5"></h3>
                    <p class="text-xs text-[#7B8D65] mt-0.5">
                        รหัสการสมัคร: <span id="view_app_reg_no" class="font-mono font-bold text-[#C86D51]"></span>
                        <span class="mx-1.5">•</span>
                        คิวที่: <span id="view_app_queue_no" class="font-mono font-bold text-[#2C3E2D]"></span>
                        <span class="mx-1.5">•</span>
                        ลงทะเบียนเมื่อ: <span id="view_app_registered_at" class="font-medium text-[#4A3B32]"></span>
                    </p>
                </div>
                <button type="button" onclick="document.getElementById('view-applicant-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-xl hover:bg-stone-100 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-4 text-xs">
                <!-- 1. คอร์ส / โครงการที่สมัคร -->
                <div class="p-4 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9]">
                    <div class="text-[11px] font-bold text-[#7B8D65] uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i> ข้อมูลโครงการอบรมที่สมัคร
                    </div>
                    <div class="font-bold text-[#2C3E2D] text-sm" id="view_app_event_title">-</div>
                    <div class="text-[#6B6357] mt-1 flex flex-wrap gap-x-4 gap-y-1">
                        <div>ส่วนงานจัดอบรม: <span id="view_app_event_org" class="font-medium text-[#2C3E2D]">-</span></div>
                        <div>ช่วงเวลาจัดอบรม: <span id="view_app_event_dates" class="font-medium text-[#2C3E2D]">-</span></div>
                    </div>
                </div>

                <!-- 2. ข้อมูลนิสิต มจร (ถ้ามี) -->
                <div id="view_app_student_box" class="p-4 bg-emerald-50/60 rounded-2xl border border-emerald-200/80 hidden">
                    <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-emerald-700"></i> ข้อมูลการศึกษาใน มจร (MCU Student Academic Info)
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <span class="text-[#7B8D65] block">รหัสนิสิต:</span>
                            <span id="view_app_student_id" class="font-mono font-bold text-[#2C3E2D] text-sm">-</span>
                        </div>
                        <div>
                            <span class="text-[#7B8D65] block">ระดับการศึกษา:</span>
                            <span id="view_app_degree_level" class="font-medium text-[#2C3E2D]">-</span>
                        </div>
                        <div>
                            <span class="text-[#7B8D65] block">คณะ:</span>
                            <span id="view_app_faculty" class="font-medium text-[#2C3E2D]">-</span>
                        </div>
                        <div>
                            <span class="text-[#7B8D65] block">สาขาวิชา/หลักสูตร:</span>
                            <span id="view_app_program_name" class="font-medium text-[#2C3E2D]">-</span>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-t border-emerald-200/60 text-[#6B6357]">
                        ส่วนงานต้นสังกัดนิสิต: <span id="view_app_org_unit_name" class="font-medium text-[#2C3E2D]">-</span>
                    </div>
                </div>

                <!-- 3. ข้อมูลส่วนบุคคล & การติดต่อ -->
                <div class="p-4 bg-white rounded-2xl border border-[#EAE5D9]">
                    <div class="text-[11px] font-bold text-[#7B8D65] uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i> ข้อมูลส่วนบุคคล & การติดต่อ
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                        <div>
                            <span class="text-[#8C8275] block">เลขบัตร ปชช. / Passport:</span>
                            <span id="view_app_citizen_id" class="font-mono font-semibold text-[#2C3E2D]">-</span>
                        </div>
                        <div>
                            <span class="text-[#8C8275] block">เพศ / สถานะทางธรรม:</span>
                            <span id="view_app_gender" class="font-medium text-[#2C3E2D]">-</span>
                        </div>
                        <div>
                            <span class="text-[#8C8275] block">อายุ / พรรษา:</span>
                            <span id="view_app_age_vassa" class="font-medium text-[#2C3E2D]">-</span>
                        </div>
                        <div>
                            <span class="text-[#8C8275] block">เบอร์โทรศัพท์:</span>
                            <span id="view_app_phone" class="font-mono font-bold text-[#5A6B47] text-sm">-</span>
                        </div>
                        <div>
                            <span class="text-[#8C8275] block">อีเมล (Email):</span>
                            <span id="view_app_email" class="font-medium text-[#2C3E2D]">-</span>
                        </div>
                        <div>
                            <span class="text-[#8C8275] block">Line ID:</span>
                            <span id="view_app_line_id" class="font-mono font-medium text-[#2C3E2D]">-</span>
                        </div>
                    </div>
                </div>

                <!-- 4. ที่อยู่ติดต่อ -->
                <div class="p-4 bg-white rounded-2xl border border-[#EAE5D9]">
                    <div class="text-[11px] font-bold text-[#7B8D65] uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> ที่อยู่ติดต่อปัจจุบัน
                    </div>
                    <p id="view_app_full_address" class="text-[#2C3E2D] leading-relaxed font-medium">-</p>
                </div>

                <!-- 5. ที่พัก ยานพาหนะ และอาหาร -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-4 bg-white rounded-2xl border border-[#EAE5D9] space-y-2">
                        <div class="text-[11px] font-bold text-[#7B8D65] uppercase tracking-wider flex items-center gap-1.5">
                            <i data-lucide="home" class="w-3.5 h-3.5"></i> การจัดสรรที่พัก & การเดินทาง
                        </div>
                        <div>
                            <span class="text-[#8C8275] block">ห้องพัก / อาคาร:</span>
                            <span id="view_app_room_info" class="font-semibold text-[#2C3E2D]">-</span>
                        </div>
                        <div>
                            <span class="text-[#8C8275] block">ยานพาหนะเดินทาง / ทะเบียนรถ:</span>
                            <span id="view_app_vehicle_info" class="font-medium text-[#2C3E2D]">-</span>
                        </div>
                    </div>

                    <div class="p-4 bg-white rounded-2xl border border-[#EAE5D9] space-y-2">
                        <div class="text-[11px] font-bold text-[#7B8D65] uppercase tracking-wider flex items-center gap-1.5">
                            <i data-lucide="utensils" class="w-3.5 h-3.5"></i> อาหาร & ข้อจำกัดสุขภาพ
                        </div>
                        <div>
                            <span class="text-[#8C8275] block">ประเภทอาหาร:</span>
                            <span id="view_app_dietary" class="font-semibold text-[#2C3E2D]">-</span>
                        </div>
                        <div>
                            <span class="text-[#8C8275] block">โรคประจำตัว / ข้อจำกัดสุขภาพ:</span>
                            <span id="view_app_congenital_disease" class="font-medium text-[#C86D51]">-</span>
                        </div>
                    </div>
                </div>

                <!-- 6. ผู้ติดต่อฉุกเฉิน & เหตุผลกรณีปฏิเสธ -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-4 bg-white rounded-2xl border border-[#EAE5D9]">
                        <div class="text-[11px] font-bold text-[#7B8D65] uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i data-lucide="phone-call" class="w-3.5 h-3.5"></i> ผู้ติดต่อกรณีฉุกเฉิน
                        </div>
                        <div class="text-[#2C3E2D] font-medium" id="view_app_emergency_contact">-</div>
                        <div class="text-[#6B6357] mt-1">เบอร์โทรฉุกเฉิน: <span id="view_app_emergency_phone" class="font-mono font-bold text-[#C86D51]">-</span></div>
                    </div>

                    <div id="view_app_reject_box" class="p-4 bg-amber-50/80 rounded-2xl border border-amber-200">
                        <div class="text-[11px] font-bold text-amber-900 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-amber-800"></i> หมายเหตุ / เหตุผลการคัดกรอง
                        </div>
                        <div id="view_app_reject_reason" class="text-amber-900 font-medium">-</div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-[#EAE5D9] flex flex-wrap justify-between items-center gap-2">
                <button type="button" onclick="window.print()" class="px-4 py-2 bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#2C3E2D] border border-[#D5CEBC] rounded-xl text-xs font-medium transition flex items-center gap-1.5 shadow-2xs">
                    <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์เอกสาร (Print)
                </button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="document.getElementById('view-applicant-modal').classList.add('hidden')" class="px-4 py-2 border border-[#EAE5D9] text-[#4A3B32] hover:bg-stone-50 rounded-xl text-xs font-medium transition">
                        ปิดหน้าต่าง
                    </button>
                    <button type="button" id="btn_switch_to_edit" onclick="" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-1.5">
                        <i data-lucide="edit-3" class="w-4 h-4"></i> แก้ไขข้อมูลผู้สมัคร
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form: แก้ไขข้อมูลผู้สมัครเข้าร่วมอบรม (แก้ไขได้ทุกฟิลด์) -->
    <div id="edit-applicant-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-2xl w-full p-6 md:p-8 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div>
                    <h3 class="text-lg font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                        <i data-lucide="edit-3" class="w-5 h-5 text-[#5A6B47]"></i>
                        <span>แก้ไขข้อมูลผู้สมัครลงทะเบียน</span>
                    </h3>
                    <p class="text-xs text-[#7B8D65]">รหัสการสมัคร: <span id="edit_modal_reg_no" class="font-mono font-bold text-[#C86D51]"></span> | คิวที่: <span id="edit_modal_queue_no" class="font-mono font-bold text-[#2C3E2D]"></span></p>
                </div>
                <button type="button" onclick="document.getElementById('edit-applicant-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="edit-applicant-form" method="POST" action="{{ url('/admin/public_students.php') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_app_id" value="">

                <!-- 1. คอร์สและประเภทผู้สมัคร -->
                <div class="p-3.5 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9] space-y-3">
                    <div class="font-semibold text-[#2C3E2D] flex items-center gap-1.5 text-xs">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#5A6B47]"></i> ข้อมูลโครงการ & ประเภทผู้สมัคร
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">คอร์ส / โครงการที่สมัคร <span class="text-[#C86D51]">*</span></label>
                            <select name="event_id" id="edit_app_event_id" required class="w-full px-3 py-2 bg-white border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                                @foreach ($events as $ev)
                                    <option value="{{ $ev->id }}">{{ $ev->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ประเภทผู้สมัคร <span class="text-[#C86D51]">*</span></label>
                            <select name="applicant_type" id="edit_app_applicant_type" onchange="toggleEditStudentSection(this.value)" required class="w-full px-3 py-2 bg-white border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="PEOPLE">ประชาชนทั่วไป (General Public)</option>
                                <option value="STUDENT">นิสิต มจร (MCU Student)</option>
                            </select>
                        </div>
                    </div>

                    <!-- ส่วนข้อมูลเฉพาะนิสิต มจร -->
                    <div id="edit_app_student_fields" class="pt-2 border-t border-[#EAE5D9] space-y-2 hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block font-medium text-[#4A3B32] mb-0.5">รหัสนิสิต</label>
                                <input type="text" name="student_id" id="edit_app_student_id" placeholder="เช่น 6601201001" class="w-full px-2.5 py-1.5 bg-white border border-[#EAE5D9] rounded-lg text-xs font-mono">
                            </div>
                            <div>
                                <label class="block font-medium text-[#4A3B32] mb-0.5">ระดับการศึกษา</label>
                                <select name="degree_level" id="edit_app_degree_level" class="w-full px-2.5 py-1.5 bg-white border border-[#EAE5D9] rounded-lg text-xs">
                                    <option value="">-- เลือกระดับการศึกษา --</option>
                                    <option value="ปริญญาตรี">ปริญญาตรี</option>
                                    <option value="ปริญญาโท">ปริญญาโท</option>
                                    <option value="ปริญญาเอก">ปริญญาเอก</option>
                                    <option value="ประกาศนียบัตร">ประกาศนียบัตร</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-[#4A3B32] mb-0.5">คณะ</label>
                                <input type="text" name="faculty" id="edit_app_faculty" list="faculty_options" placeholder="เช่น พุทธศาสตร์" class="w-full px-2.5 py-1.5 bg-white border border-[#EAE5D9] rounded-lg text-xs">
                                <datalist id="faculty_options">
                                    <option value="พุทธศาสตร์">
                                    <option value="ครุศาสตร์">
                                    <option value="สังคมศาสตร์">
                                    <option value="มนุษยศาสตร์">
                                    <option value="บัณฑิตวิทยาลัย">
                                    <option value="IBSC">
                                    <option value="วิทยาลัยสงฆ์/วิทยาเขต">
                                </datalist>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <label class="block font-medium text-[#4A3B32] mb-0.5">ส่วนงานต้นสังกัดนิสิต</label>
                                <select name="org_unit_id" id="edit_app_org_unit_id" class="w-full px-2.5 py-1.5 bg-white border border-[#EAE5D9] rounded-lg text-xs">
                                    <option value="">-- เลือกส่วนงาน --</option>
                                    @foreach ($orgUnits as $org)
                                        <option value="{{ $org->id }}">{{ $org->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-[#4A3B32] mb-0.5">สาขาวิชา/หลักสูตร</label>
                                <input type="text" name="program_name" id="edit_app_program_name" placeholder="เช่น สาขาวิชาพระพุทธศาสนา" class="w-full px-2.5 py-1.5 bg-white border border-[#EAE5D9] rounded-lg text-xs">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. ข้อมูลส่วนบุคคล -->
                <div class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">คำนำหน้า <span class="text-[#C86D51]">*</span></label>
                            <input type="text" name="prefix" id="edit_app_prefix" placeholder="นาย, นาง, พระมหา..." required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ชื่อ <span class="text-[#C86D51]">*</span></label>
                            <input type="text" name="first_name" id="edit_app_first_name" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">นามสกุล <span class="text-[#C86D51]">*</span></label>
                            <input type="text" name="last_name" id="edit_app_last_name" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ฉายา/นามแฝง (ถ้ามี)</label>
                            <input type="text" name="buddhist_name" id="edit_app_buddhist_name" placeholder="เช่น ปญฺญาเมธี" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">เพศสภาพ <span class="text-[#C86D51]">*</span></label>
                            <select name="gender" id="edit_app_gender" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="MALE">ชาย (ฆราวาส)</option>
                                <option value="FEMALE">หญิง (ฆราวาส)</option>
                                <option value="MONK">พระภิกษุ (Monk)</option>
                                <option value="NOVICE">สามเณร (Novice)</option>
                                <option value="OTHER">อื่น ๆ</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">อายุ (ปี)</label>
                            <input type="number" name="age" id="edit_app_age" min="0" max="120" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">พรรษา (ถ้ามี)</label>
                            <input type="number" name="vassa" id="edit_app_vassa" min="0" max="100" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">เลขบัตร ปชช./Passport</label>
                            <input type="text" name="citizen_id" id="edit_app_citizen_id" maxlength="30" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                        </div>
                    </div>
                </div>

                <!-- 3. ข้อมูลการติดต่อและที่อยู่ -->
                <div class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">เบอร์โทรศัพท์ <span class="text-[#C86D51]">*</span></label>
                            <input type="text" name="phone" id="edit_app_phone" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">อีเมล</label>
                            <input type="email" name="email" id="edit_app_email" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">Line ID</label>
                            <input type="text" name="line_id" id="edit_app_line_id" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-[#4A3B32] mb-1">ที่อยู่ / วัดต้นสังกัด</label>
                            <input type="text" name="address" id="edit_app_address" placeholder="บ้านเลขที่ ซอย ถนน..." class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ตำบล/แขวง</label>
                            <input type="text" name="subdistrict" id="edit_app_subdistrict" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">อำเภอ/เขต</label>
                            <input type="text" name="district" id="edit_app_district" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">จังหวัด</label>
                            <input type="text" name="province" id="edit_app_province" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">รหัสไปรษณีย์</label>
                            <input type="text" name="postal_code" id="edit_app_postal_code" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                        </div>
                    </div>
                </div>

                <!-- 4. ข้อมูลการอำนวยความสะดวก & สุขภาพ -->
                <div class="p-3.5 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9] space-y-3">
                    <div class="font-semibold text-[#2C3E2D] flex items-center gap-1.5 text-xs">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#5A6B47]"></i> อาหาร ห้องพัก ยานพาหนะ & บุคคลติดต่อฉุกเฉิน
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ประเภทอาหาร</label>
                            <select name="dietary_restriction" id="edit_app_dietary_restriction" class="w-full px-3 py-2 bg-white border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="NORMAL">อาหารทั่วไป</option>
                                <option value="VEGETARIAN">อาหารมังสวิรัติ (Vegetarian)</option>
                                <option value="JAY">อาหารเจ (Jay)</option>
                                <option value="HALAL">อาหารฮาลาล (Halal)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">โรคประจำตัว / ข้อจำกัดสุขภาพ</label>
                            <input type="text" name="congenital_disease" id="edit_app_congenital_disease" placeholder="เช่น ความดัน, ภูมิแพ้, หรือระบุว่า ไม่มี" class="w-full px-3 py-2 bg-white border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">การจัดสรรห้องพัก / อาคาร</label>
                            <input type="text" name="room_info" id="edit_app_room_info" placeholder="เช่น อาคาร 74 ปี ห้อง 302 หรือ กุฏิสงฆ์ โซน A" class="w-full px-3 py-2 bg-white border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ยานพาหนะเดินทาง / ทะเบียนรถ</label>
                            <input type="text" name="vehicle_info" id="edit_app_vehicle_info" placeholder="เช่น รถยนต์ส่วนบุคคล กข 1234 หรือ รถตู้ มจร" class="w-full px-3 py-2 bg-white border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ผู้ติดต่อฉุกเฉิน (ชื่อ & ความสัมพันธ์)</label>
                            <input type="text" name="emergency_contact" id="edit_app_emergency_contact" placeholder="เช่น นางอุษา (มารดา)" class="w-full px-3 py-2 bg-white border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">เบอร์โทรผู้ติดต่อฉุกเฉิน</label>
                            <input type="text" name="emergency_phone" id="edit_app_emergency_phone" placeholder="08X-XXX-XXXX" class="w-full px-3 py-2 bg-white border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                        </div>
                    </div>
                </div>

                <!-- 5. สถานะการสมัครและเหตุผลปฏิเสธ -->
                <div class="p-3.5 bg-amber-50/70 rounded-2xl border border-amber-200/80 space-y-3">
                    <div class="font-semibold text-amber-900 flex items-center gap-1.5 text-xs">
                        <i data-lucide="check-square" class="w-3.5 h-3.5 text-amber-800"></i> สถานะการคัดกรองผลการสมัคร
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-amber-900 mb-1">สถานะผู้สมัคร <span class="text-[#C86D51]">*</span></label>
                            <select name="status" id="edit_app_status" required class="w-full px-3 py-2 bg-white border border-amber-300 rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="PENDING">รอการตรวจสอบ (Pending)</option>
                                <option value="CONFIRMED">อนุมัติสิทธิ์เข้าร่วม (Confirmed)</option>
                                <option value="WAITING_LIST">รายชื่อสำรอง (Waiting List)</option>
                                <option value="ATTENDED">เข้าร่วมอบรมแล้ว (Attended)</option>
                                <option value="REJECTED">ไม่อนุมัติ / ปฏิเสธ (Rejected)</option>
                                <option value="CANCELLED">ยกเลิกสิทธิ์ (Cancelled)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-amber-900 mb-1">หมายเหตุ / เหตุผลการปฏิเสธ (ถ้ามี)</label>
                            <input type="text" name="reject_reason" id="edit_app_reject_reason" placeholder="ระบุเหตุผลกรณีไม่อนุมัติหรือยกเลิก..." class="w-full px-3 py-2 bg-white border border-amber-300 rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('edit-applicant-modal').classList.add('hidden')" class="px-4 py-2 border border-[#EAE5D9] rounded-xl hover:bg-stone-50 font-medium">ยกเลิก</button>
                    <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-5 py-2 rounded-xl font-medium shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i> บันทึกการแก้ไขทุกฟิลด์
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSelectAll(master) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = master.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            document.getElementById('selected-count').innerText = checked;
        }

        function submitBulkAction() {
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            const action = document.getElementById('bulk-action-select').value;

            if (checked === 0) {
                alert('กรุณาเลือกรายการอย่างน้อย 1 รายการ');
                return;
            }

            if (!action) {
                alert('กรุณาเลือกการดำเนินการที่ต้องการ');
                return;
            }

            if (action === 'DELETE') {
                if (!confirm(`ยืนยันการย้ายข้อมูลจำนวน ${checked} รายการ ไปยังถังขยะหรือไม่? (สามารถกู้คืนได้ภายหลัง)`)) {
                    return;
                }
            } else if (action === 'RESTORE') {
                if (!confirm(`ยืนยันการกู้คืนข้อมูลจำนวน ${checked} รายการ กลับสู่ระบบหรือไม่?`)) {
                    return;
                }
            } else if (action === 'FORCE_DELETE') {
                if (!confirm(`คำเตือน: ยืนยันการลบข้อมูลจำนวน ${checked} รายการ ออกจากระบบถาวรหรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้`)) {
                    return;
                }
            } else {
                if (!confirm(`ยืนยันดำเนินการกับข้อมูลจำนวน ${checked} รายการ?`)) {
                    return;
                }
            }

            document.getElementById('bulk-form').submit();
        }

        function openRejectModal(id, name) {
            document.getElementById('reject-applicant-name').innerText = name;
            document.getElementById('reject-applicant-id').value = id;
            document.getElementById('reject-reason-select').value = 'คุณสมบัติหรือข้อมูลไม่ผ่านเกณฑ์การอบรม';
            document.getElementById('custom-reason-input').classList.add('hidden');
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function toggleCustomReason(val) {
            const input = document.getElementById('custom-reason-input');
            if (val === 'OTHER') {
                input.classList.remove('hidden');
                input.required = true;
            } else {
                input.classList.add('hidden');
                input.required = false;
            }
        }

        function toggleEditStudentSection(val) {
            const el = document.getElementById('edit_app_student_fields');
            if (val === 'STUDENT') {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }

        function openViewApplicantModal(data) {
            // Header
            document.getElementById('view_app_reg_no').innerText = data.registration_no || '-';
            document.getElementById('view_app_queue_no').innerText = data.queue_no ? 'Q-' + String(data.queue_no).padStart(3, '0') : '-';
            
            // Full name
            let fullName = (data.prefix || '') + (data.first_name || '') + ' ' + (data.last_name || '');
            if (data.buddhist_name) fullName += ' (' + data.buddhist_name + ')';
            document.getElementById('view_app_full_name').innerText = fullName.trim() || '-';

            // Registration Date
            if (data.registered_at) {
                const regDate = new Date(data.registered_at);
                document.getElementById('view_app_registered_at').innerText = regDate.toLocaleString('th-TH');
            } else {
                document.getElementById('view_app_registered_at').innerText = '-';
            }

            // Status Badge
            const statusBadge = document.getElementById('view_app_status_badge');
            const statusMap = {
                'CONFIRMED': { text: 'อนุมัติสิทธิ์ (Confirmed)', class: 'bg-[#5A6B47] text-white' },
                'PENDING': { text: 'รอตรวจสอบ (Pending)', class: 'bg-amber-100 text-amber-800 border border-amber-300' },
                'WAITING_LIST': { text: 'รายชื่อสำรอง (Waiting List)', class: 'bg-orange-100 text-orange-800 border border-orange-300' },
                'ATTENDED': { text: 'เข้าร่วมแล้ว (Attended)', class: 'bg-[#2C3E2D] text-white' },
                'REJECTED': { text: 'ไม่อนุมัติ (Rejected)', class: 'bg-red-100 text-red-700 border border-red-300' },
                'CANCELLED': { text: 'ยกเลิกสิทธิ์ (Cancelled)', class: 'bg-stone-200 text-stone-700' },
            };
            const sInfo = statusMap[data.status] || { text: data.status, class: 'bg-stone-100 text-stone-700' };
            statusBadge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold ' + sInfo.class;
            statusBadge.innerText = sInfo.text;

            // Type Badge
            const isStudent = (data.applicant_type === 'STUDENT');
            const typeBadge = document.getElementById('view_app_type_badge');
            typeBadge.innerText = isStudent ? 'นิสิต มจร (MCU Student)' : 'ประชาชนทั่วไป (General Public)';

            // Event Info
            document.getElementById('view_app_event_title').innerText = (data.event && data.event.title) ? data.event.title : '-';
            document.getElementById('view_app_event_org').innerText = (data.event && data.event.organization_unit && data.event.organization_unit.name_th) ? data.event.organization_unit.name_th : '-';
            
            let dates = '-';
            if (data.event && data.event.start_date) {
                dates = data.event.start_date + ' ถึง ' + (data.event.end_date || '-');
            }
            document.getElementById('view_app_event_dates').innerText = dates;

            // Student Box
            const stdBox = document.getElementById('view_app_student_box');
            if (isStudent) {
                stdBox.classList.remove('hidden');
                document.getElementById('view_app_student_id').innerText = data.student_id || '-';
                document.getElementById('view_app_degree_level').innerText = data.degree_level || '-';
                document.getElementById('view_app_faculty').innerText = data.faculty || '-';
                document.getElementById('view_app_program_name').innerText = data.program_name || '-';
                document.getElementById('view_app_org_unit_name').innerText = (data.organization_unit && data.organization_unit.name_th) ? data.organization_unit.name_th : '-';
            } else {
                stdBox.classList.add('hidden');
            }

            // Personal & Contact Info
            document.getElementById('view_app_citizen_id').innerText = data.citizen_id || '-';
            
            const genderMap = {
                'MALE': 'ชาย',
                'FEMALE': 'หญิง',
                'MONK': 'พระภิกษุ',
                'NOVICE': 'สามเณร',
                'OTHER': 'ไม่ระบุ'
            };
            document.getElementById('view_app_gender').innerText = genderMap[data.gender] || data.gender || '-';
            
            let ageVassa = (data.age ? data.age + ' ปี' : '-');
            if (data.vassa && data.vassa > 0) ageVassa += ' / ' + data.vassa + ' พรรษา';
            document.getElementById('view_app_age_vassa').innerText = ageVassa;

            document.getElementById('view_app_phone').innerText = data.phone || '-';
            document.getElementById('view_app_email').innerText = data.email || '-';
            document.getElementById('view_app_line_id').innerText = data.line_id || '-';

            // Address
            let addr = data.address || '';
            if (data.subdistrict) addr += ' ต./แขวง ' + data.subdistrict;
            if (data.district) addr += ' อ./เขต ' + data.district;
            if (data.province) addr += ' จ.' + data.province;
            if (data.postal_code) addr += ' ' + data.postal_code;
            document.getElementById('view_app_full_address').innerText = addr.trim() || '-';

            // Lodging, Vehicle & Food
            document.getElementById('view_app_room_info').innerText = data.room_info || 'พักรวมตามที่สถาบันจัดให้';
            document.getElementById('view_app_vehicle_info').innerText = data.vehicle_info || 'เดินทางไป-กลับเอง';
            
            const dietaryMap = {
                'NORMAL': 'อาหารทั่วไป',
                'VEGETARIAN': 'อาหารมังสวิรัติ (Vegetarian)',
                'JAY': 'อาหารเจ (Jay)',
                'HALAL': 'อาหารฮาลาล (Halal)'
            };
            document.getElementById('view_app_dietary').innerText = dietaryMap[data.dietary_restriction] || data.dietary_restriction || 'อาหารทั่วไป';
            document.getElementById('view_app_congenital_disease').innerText = data.congenital_disease || 'ไม่มี / ไม่ระบุ';

            // Emergency Contact
            document.getElementById('view_app_emergency_contact').innerText = data.emergency_contact || '-';
            document.getElementById('view_app_emergency_phone').innerText = data.emergency_phone || '-';

            // Reject Reason
            const rejectBox = document.getElementById('view_app_reject_box');
            if (data.reject_reason) {
                rejectBox.classList.remove('hidden');
                document.getElementById('view_app_reject_reason').innerText = data.reject_reason;
            } else {
                rejectBox.classList.add('hidden');
            }

            // Quick Switch to Edit Modal
            document.getElementById('btn_switch_to_edit').onclick = function() {
                document.getElementById('view-applicant-modal').classList.add('hidden');
                openEditApplicantModal(data);
            };

            document.getElementById('view-applicant-modal').classList.remove('hidden');
            lucide.createIcons();
        }

        function openEditApplicantModal(data) {
            document.getElementById('edit_modal_reg_no').innerText = data.registration_no || '-';
            document.getElementById('edit_modal_queue_no').innerText = data.queue_no ? 'Q-' + String(data.queue_no).padStart(3, '0') : '-';
            document.getElementById('edit_app_id').value = data.id;

            document.getElementById('edit_app_event_id').value = data.event_id || '';
            const appType = (data.applicant_type === 'STUDENT') ? 'STUDENT' : 'PEOPLE';
            document.getElementById('edit_app_applicant_type').value = appType;
            toggleEditStudentSection(appType);

            document.getElementById('edit_app_student_id').value = data.student_id || '';
            document.getElementById('edit_app_degree_level').value = data.degree_level || '';
            document.getElementById('edit_app_faculty').value = data.faculty || '';
            document.getElementById('edit_app_org_unit_id').value = data.org_unit_id || '';
            document.getElementById('edit_app_program_name').value = data.program_name || '';

            document.getElementById('edit_app_prefix').value = data.prefix || '';
            document.getElementById('edit_app_first_name').value = data.first_name || '';
            document.getElementById('edit_app_last_name').value = data.last_name || '';
            document.getElementById('edit_app_buddhist_name').value = data.buddhist_name || '';

            document.getElementById('edit_app_gender').value = data.gender || 'MALE';
            document.getElementById('edit_app_age').value = data.age || '';
            document.getElementById('edit_app_vassa').value = data.vassa || '';
            document.getElementById('edit_app_citizen_id').value = data.citizen_id || '';

            document.getElementById('edit_app_phone').value = data.phone || '';
            document.getElementById('edit_app_email').value = data.email || '';
            document.getElementById('edit_app_line_id').value = data.line_id || '';

            document.getElementById('edit_app_address').value = data.address || '';
            document.getElementById('edit_app_subdistrict').value = data.subdistrict || '';
            document.getElementById('edit_app_district').value = data.district || '';
            document.getElementById('edit_app_province').value = data.province || '';
            document.getElementById('edit_app_postal_code').value = data.postal_code || '';

            document.getElementById('edit_app_dietary_restriction').value = data.dietary_restriction || 'NORMAL';
            document.getElementById('edit_app_congenital_disease').value = data.congenital_disease || '';
            document.getElementById('edit_app_room_info').value = data.room_info || '';
            document.getElementById('edit_app_vehicle_info').value = data.vehicle_info || '';

            document.getElementById('edit_app_emergency_contact').value = data.emergency_contact || '';
            document.getElementById('edit_app_emergency_phone').value = data.emergency_phone || '';

            document.getElementById('edit_app_status').value = data.status || 'PENDING';
            document.getElementById('edit_app_reject_reason').value = data.reject_reason || '';

            document.getElementById('edit-applicant-modal').classList.remove('hidden');
        }

        lucide.createIcons();
    </script>

</body>
</html>
