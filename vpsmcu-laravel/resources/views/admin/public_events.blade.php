<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคอร์สวิปัสสนากรรมฐาน - VPSMCU Admin</title>
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
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">จัดการคอร์สวิปัสสนากรรมฐาน</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">จัดการคอร์สวิปัสสนากรรมฐานสำหรับประชาชนและนิสิต มจร พร้อมระบบบริหารโควตาและ Waiting List</p>
            </div>
            <button onclick="document.getElementById('add-event-modal').classList.remove('hidden')" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-2.5 rounded-xl text-xs font-medium shadow-md transition flex items-center gap-2 self-start sm:self-auto">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> เพิ่มคอร์สปฏิบัติธรรมใหม่
            </button>
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

        @if (isset($errors) && $errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-xs">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="earth-admin-card p-4 mb-6">
            <form method="GET" action="{{ route('admin.public.events') }}" class="flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-64">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-[#8C8275] absolute left-3 top-2.5"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อคอร์ส หรือสถานที่..." class="w-full pl-9 pr-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                    </div>

                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกสถานะ --</option>
                        <option value="OPEN" {{ request('status') === 'OPEN' ? 'selected' : '' }}>เปิดรับสมัคร (Open)</option>
                        <option value="CLOSED" {{ request('status') === 'CLOSED' ? 'selected' : '' }}>ปิดรับสมัคร (Closed)</option>
                        <option value="COMPLETED" {{ request('status') === 'COMPLETED' ? 'selected' : '' }}>เสร็จสิ้นโครงการ (Completed)</option>
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

                    @if(request()->hasAny(['search', 'status', 'filter_org']))
                        <a href="{{ route('admin.public.events') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i> ล้างตัวกรอง
                        </a>
                    @endif
                </div>

                <div class="text-xs text-[#7B8D65] self-end lg:self-auto whitespace-nowrap">
                    พบทั้งหมด <strong>{{ $events->total() }}</strong> คอร์ส
                </div>
            </form>
        </div>

        <!-- Events Table -->
        <div class="earth-admin-card overflow-hidden">
            <div class="p-5 border-b border-[#EAE5D9] flex justify-between items-center bg-[#FAF8F2]/60">
                <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2 text-sm">
                    <i data-lucide="calendar" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>ตารางคอร์สและโครงการปฏิบัติธรรม</span>
                </h2>
                <span class="text-xs bg-white border border-[#EAE5D9] px-3 py-1 rounded-full text-[#7B8D65] font-medium">ทั้งหมด {{ $events->total() }} รายการ</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                        <tr>
                            <th class="p-4">ชื่อคอร์ส & สถานที่</th>
                            <th class="p-4">ส่วนงานผู้จัด</th>
                            <th class="p-4">ช่วงเวลาจัดอบรม</th>
                            <th class="p-4 text-center">โควตา / สมัครแล้ว</th>
                            <th class="p-4 text-center">สถานะ</th>
                            <th class="p-4 text-right">ดำเนินการ (Action)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($events as $e)
                            @php
                                $regCount = $e->actual_registrations_count ?? ($e->registrations ? $e->registrations->count() : ($e->confirmed_count ?? 0));
                                $quota = $e->max_quota ?? 50;
                                $isFull = ($regCount >= $quota && $quota > 0);
                            @endphp
                            <tr class="hover:bg-[#FAF8F2]/80 transition">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-[#EAE5D9] border border-[#D5CEBC] overflow-hidden shrink-0 flex items-center justify-center">
                                            @if (!empty($e->cover_image))
                                                <img src="{{ $e->cover_image }}" alt="{{ $e->title }}" class="w-full h-full object-cover">
                                            @else
                                                <i data-lucide="image" class="w-5 h-5 text-[#8C8275]"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-heading font-semibold text-[#2C3E2D] text-sm">
                                                {{ $e->title }}
                                            </div>
                                            <div class="text-[#7B8D65] text-[11px] flex items-center gap-1 mt-0.5">
                                                <i data-lucide="map-pin" class="w-3 h-3 text-[#C86D51]"></i>
                                                <span>{{ $e->location_name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-[#FAF8F2] border border-[#EAE5D9] text-[#2C3E2D] font-medium text-[11px]">
                                        {{ $e->organizationUnit->name_th ?? 'มจร ส่วนกลาง' }}
                                    </span>
                                </td>
                                <td class="p-4 font-mono text-[11px]">
                                    @php
                                        $sDate = \Carbon\Carbon::parse($e->start_date);
                                        $eDate = \Carbon\Carbon::parse($e->end_date);
                                    @endphp
                                    <div class="text-[#2C3E2D] font-semibold">{{ $sDate->format('d/m/') . ($sDate->year + 543) }}</div>
                                    <div class="text-[#7B8D65]">ถึง {{ $eDate->format('d/m/') . ($eDate->year + 543) }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="font-mono font-bold {{ $isFull ? 'text-red-600' : 'text-[#5A6B47]' }} text-sm">
                                        {{ $regCount }}
                                    </span>
                                    <span class="text-[#7B8D65]">/ {{ $quota }}</span>
                                    @if ($isFull)
                                        <div class="text-[10px] text-red-600 font-semibold mt-0.5">ที่นั่งเต็ม</div>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if ($e->status === 'OPEN')
                                        <a href="{{ url('/admin/public_events.php?action=status&id=' . $e->id . '&status_val=CLOSED') }}"
                                           onclick="return confirm('ยืนยันปิดรับสมัครคอร์ส ({{ $e->title }}) หรือไม่?')"
                                           title="คลิกเพื่อปิดรับสมัคร (สถานะปัจจุบัน: เปิดรับสมัคร)"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#2C3E2D] hover:bg-[#1E2B1F] text-emerald-400 shadow-sm hover:scale-105 active:scale-95 transition-all">
                                            <i data-lucide="check" class="w-4 h-4 stroke-[2.5]"></i>
                                        </a>
                                    @elseif ($e->status === 'CLOSED')
                                        <a href="{{ url('/admin/public_events.php?action=status&id=' . $e->id . '&status_val=OPEN') }}"
                                           onclick="return confirm('ยืนยันเปิดรับสมัครคอร์ส ({{ $e->title }}) หรือไม่?')"
                                           title="คลิกเพื่อเปิดรับสมัคร (สถานะปัจจุบัน: ปิดรับสมัคร)"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#8E2818] hover:bg-[#6D1B0E] text-rose-100 shadow-sm hover:scale-105 active:scale-95 transition-all">
                                            <i data-lucide="x" class="w-4 h-4 stroke-[2.5]"></i>
                                        </a>
                                    @else
                                        <a href="{{ url('/admin/public_events.php?action=status&id=' . $e->id . '&status_val=OPEN') }}"
                                           onclick="return confirm('ยืนยันเปิดรับสมัครคอร์สนี้ใหม่อีกครั้งหรือไม่?')"
                                           title="คลิกเพื่อเปิดรับสมัครใหม่ (สถานะปัจจุบัน: เสร็จสิ้นโครงการ)"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-stone-700 hover:bg-stone-800 text-stone-200 shadow-sm hover:scale-105 active:scale-95 transition-all">
                                            <i data-lucide="check-check" class="w-4 h-4 stroke-[2.5]"></i>
                                        </a>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- ปุ่มดูรายชื่อผู้สมัครในคอร์สนี้ -->
                                        <a href="{{ route('admin.public.students', ['event_id' => $e->id]) }}" title="ดูรายชื่อผู้สมัครคอร์สนี้" class="p-1.5 text-[#5A6B47] hover:bg-[#5A6B47]/10 rounded-lg transition">
                                            <i data-lucide="users" class="w-4 h-4"></i>
                                        </a>

                                        <!-- ปุ่ม QR Code & ลิงก์รับสมัคร -->
                                        <button type="button" onclick="openQrModal({{ json_encode($e) }})" title="QR Code & ลิงก์รับสมัคร" class="p-1.5 text-[#2C3E2D] hover:bg-[#2C3E2D]/10 rounded-lg transition">
                                            <i data-lucide="qr-code" class="w-4 h-4 text-[#5A6B47]"></i>
                                        </button>

                                        <!-- ปุ่มเปลี่ยนสถานะ -->
                                        @if ($e->status === 'OPEN')
                                            <a href="{{ url('/admin/public_events.php?action=status&id=' . $e->id . '&status_val=CLOSED') }}" title="ปิดรับสมัคร" onclick="return confirm('ยืนยันปิดรับสมัครคอร์สนี้หรือไม่?')" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                                <i data-lucide="lock" class="w-4 h-4"></i>
                                            </a>
                                        @else
                                            <a href="{{ url('/admin/public_events.php?action=status&id=' . $e->id . '&status_val=OPEN') }}" title="เปิดรับสมัคร" onclick="return confirm('ยืนยันเปิดรับสมัครคอร์สนี้หรือไม่?')" class="p-1.5 text-[#5A6B47] hover:bg-[#5A6B47]/10 rounded-lg transition">
                                                <i data-lucide="unlock" class="w-4 h-4"></i>
                                            </a>
                                        @endif

                                        <!-- ปุ่มแก้ไข -->
                                        <button type="button" onclick="openEditModal({{ json_encode($e) }})" title="แก้ไขโครงการ" class="p-1.5 text-[#4A3B32] hover:bg-[#FAF8F2] rounded-lg transition">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>

                                        <!-- ปุ่มลบ -->
                                        <a href="{{ url('/admin/public_events.php?action=delete&id=' . $e->id) }}" onclick="return confirm('ยืนยันลบคอร์สปฏิบัตินี้? ข้อมูลการลงทะเบียนจะถูกลบด้วย')" title="ลบโครงการ" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-[#8C8275]">
                                    <i data-lucide="calendar-x" class="w-8 h-8 mx-auto mb-2 text-[#C86D51]"></i>
                                    <div>ยังไม่มีคอร์ส/โครงการปฏิบัติธรรมในส่วนงานนี้</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($events->hasPages())
                <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2]/60">
                    {{ $events->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- Modal Form: เพิ่มคอร์สปฏิบัติธรรมใหม่ -->
    <div id="add-event-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-xl w-full p-6 md:p-8 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div>
                    <h3 class="text-lg font-heading font-bold text-[#2C3E2D]">เพิ่มคอร์สปฏิบัติธรรมใหม่</h3>
                    <p class="text-xs text-[#7B8D65]">กำหนดช่วงเวลา สถานที่จัด และโควตาที่นั่งรับสมัคร</p>
                </div>
                <button type="button" onclick="document.getElementById('add-event-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ url('/admin/public_events.php') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="action" value="store">

                @if ($isCentralOrSuper)
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">ส่วนงาน/สังกัดเจ้าของโครงการ ({{ count($orgUnits) }} สังกัด) <span class="text-[#C86D51]">*</span></label>
                        <select name="org_unit_id" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}">{{ $org->name_th }} ({{ $org->province_th ?: 'มจร' }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">ชื่อคอร์ส/โครงการปฏิบัติธรรม <span class="text-[#C86D51]">*</span></label>
                        <span class="text-[10px] text-[#8C8275]">(สูงสุดไม่เกิน 255 ตัวอักษร)</span>
                    </div>
                    <input type="text" name="title" maxlength="255" placeholder="เช่น คอร์สพัฒนาจิตเพื่อสันติสุข ประจำปี 2569" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">รายละเอียดโครงการ / กำหนดการสังเขป</label>
                        <span class="text-[10px] text-[#8C8275]">(ถ้ามี)</span>
                    </div>
                    <textarea name="description" rows="3" placeholder="ระบุรายละเอียดโครงการ วัตถุประสงค์ คุณสมบัติผู้สมัคร หรือแนวทางการปฏิบัติตัว..." class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] resize-y"></textarea>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">สถานที่จัดโครงการ / อาคารปฏิบัติธรรม <span class="text-[#C86D51]">*</span></label>
                        <span class="text-[10px] text-[#8C8275]">(สูงสุดไม่เกิน 255 ตัวอักษร)</span>
                    </div>
                    <input type="text" name="location_name" maxlength="255" placeholder="เช่น อาคาร 72 พรรษา หรือ ศูนย์วิปัสสนา มจร วังน้อย" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                </div>

                <!-- ช่องใส่ภาพปกโครงการ (Cover Image) -->
                <div class="p-3 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9]">
                    <label class="block font-semibold text-[#4A3B32] mb-1.5 flex items-center gap-1.5">
                        <i data-lucide="image" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                        <span>ภาพปกโครงการ (Cover Image / Banner)</span>
                        <span class="text-[10px] text-[#7B8D65] font-normal">(แสดงบนปฏิทินและรายการหน้า Portal)</span>
                    </label>
                    <div class="space-y-2">
                        <div>
                            <input type="file" name="cover_file" accept="image/*"
                                class="w-full text-xs text-[#4A3B32] file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-medium file:bg-[#5A6B47] file:text-white hover:file:bg-[#2C3E2D] file:cursor-pointer cursor-pointer">
                            <span class="text-[10px] text-[#8C8275] mt-0.5 block">อัปโหลดไฟล์ภาพ (JPG, PNG, WebP ขนาดไม่เกิน 10MB)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-[#8C8275]">หรือระบุ URL:</span>
                            <input type="text" name="cover_image" placeholder="https://... หรือ /images/..."
                                class="flex-grow px-2.5 py-1 bg-white border border-[#EAE5D9] rounded-lg text-xs font-mono text-[#4A3B32]">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">วันที่เริ่มต้น <span class="text-[#C86D51]">*</span></label>
                        <input type="date" name="start_date" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">วันที่สิ้นสุด <span class="text-[#C86D51]">*</span></label>
                        <input type="date" name="end_date" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">โควตาที่นั่งรับสมัคร (คน) <span class="text-[#C86D51]">*</span></label>
                        <input type="number" name="max_quota" value="50" min="1" max="1000" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">สถานะโครงการ</label>
                        <select name="status" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="OPEN">เปิดรับสมัคร (Open)</option>
                            <option value="CLOSED">ปิดรับสมัคร (Closed)</option>
                            <option value="COMPLETED">เสร็จสิ้นโครงการ (Completed)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('add-event-modal').classList.add('hidden')" class="px-4 py-2 border border-[#EAE5D9] rounded-xl hover:bg-stone-50 font-medium">ยกเลิก</button>
                    <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-5 py-2 rounded-xl font-medium shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i> บันทึกข้อมูลคอร์ส
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Form: แก้ไขคอร์สปฏิบัติธรรม -->
    <div id="edit-event-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-xl w-full p-6 md:p-8 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div>
                    <h3 class="text-lg font-heading font-bold text-[#2C3E2D]">แก้ไขคอร์ส/โครงการปฏิบัติธรรม</h3>
                    <p class="text-xs text-[#7B8D65]">ปรับปรุงช่วงเวลา สถานที่ และโควตาที่นั่ง</p>
                </div>
                <button type="button" onclick="document.getElementById('edit-event-modal').classList.add('hidden')" class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="edit-event-form" method="POST" enctype="multipart/form-data" action="{{ url('/admin/public_events.php') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="action" value="update">
                <input type="hidden" id="edit_event_id" name="id" value="">

                @if ($isCentralOrSuper)
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">ส่วนงานเจ้าของโครงการ <span class="text-[#C86D51]">*</span></label>
                        <select name="org_unit_id" id="edit-org-unit-id" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}">{{ $org->name_th }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">ชื่อคอร์ส/โครงการปฏิบัติธรรม <span class="text-[#C86D51]">*</span></label>
                        <span class="text-[10px] text-[#8C8275]">(สูงสุดไม่เกิน 255 ตัวอักษร)</span>
                    </div>
                    <input type="text" name="title" id="edit-title" maxlength="255" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">รายละเอียดโครงการ / กำหนดการสังเขป</label>
                        <span class="text-[10px] text-[#8C8275]">(ถ้ามี)</span>
                    </div>
                    <textarea name="description" id="edit-description" rows="3" placeholder="ระบุรายละเอียดโครงการ วัตถุประสงค์ คุณสมบัติผู้สมัคร หรือแนวทางการปฏิบัติตัว..." class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] resize-y"></textarea>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">สถานที่จัดโครงการ / อาคารปฏิบัติธรรม <span class="text-[#C86D51]">*</span></label>
                        <span class="text-[10px] text-[#8C8275]">(สูงสุดไม่เกิน 255 ตัวอักษร)</span>
                    </div>
                    <input type="text" name="location_name" id="edit-location" maxlength="255" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                </div>

                <!-- ช่องใส่ภาพปกโครงการ (Cover Image) -->
                <div class="p-3 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9]">
                    <label class="block font-semibold text-[#4A3B32] mb-1.5 flex items-center gap-1.5">
                        <i data-lucide="image" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                        <span>ภาพปกโครงการ (Cover Image / Banner)</span>
                    </label>
                    
                    <div id="edit_cover_preview_container" class="mb-2 hidden">
                        <div class="flex items-center gap-3 p-2 bg-white rounded-xl border border-[#D5CEBC]">
                            <img id="edit_cover_preview" src="" alt="Cover Preview" class="w-16 h-12 object-cover rounded-lg border border-[#EAE5D9]">
                            <div class="text-[11px] text-[#6B6357] flex-grow">
                                <div class="font-medium text-[#2C3E2D]">ภาพปัจจุบัน</div>
                                <div id="edit_cover_url_text" class="text-[10px] text-[#8C8275] truncate max-w-xs font-mono"></div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div>
                            <input type="file" name="cover_file" accept="image/*"
                                class="w-full text-xs text-[#4A3B32] file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-medium file:bg-[#5A6B47] file:text-white hover:file:bg-[#2C3E2D] file:cursor-pointer cursor-pointer">
                            <span class="text-[10px] text-[#8C8275] mt-0.5 block">อัปโหลดภาพใหม่ (JPG, PNG, WebP ขนาดไม่เกิน 10MB)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-[#8C8275]">หรือระบุ URL:</span>
                            <input type="text" id="edit_cover_image" name="cover_image" placeholder="https://... หรือ /images/..."
                                class="flex-grow px-2.5 py-1 bg-white border border-[#EAE5D9] rounded-lg text-xs font-mono text-[#4A3B32]">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">วันที่เริ่มต้น <span class="text-[#C86D51]">*</span></label>
                        <input type="date" name="start_date" id="edit-start-date" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">วันที่สิ้นสุด <span class="text-[#C86D51]">*</span></label>
                        <input type="date" name="end_date" id="edit-end-date" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">โควตาที่นั่งรับสมัคร (คน) <span class="text-[#C86D51]">*</span></label>
                        <input type="number" name="max_quota" id="edit-quota" min="1" max="1000" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">สถานะโครงการ</label>
                        <select name="status" id="edit-status" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="OPEN">เปิดรับสมัคร (Open)</option>
                            <option value="CLOSED">ปิดรับสมัคร (Closed)</option>
                            <option value="COMPLETED">เสร็จสิ้นโครงการ (Completed)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('edit-event-modal').classList.add('hidden')" class="px-4 py-2 border border-[#EAE5D9] rounded-xl hover:bg-stone-50 font-medium">ยกเลิก</button>
                    <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-5 py-2 rounded-xl font-medium shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- QR Code & Share Modal (Earth Tones Style) -->
    <div id="qr-modal" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full border border-[#D5CEBC] shadow-2xl overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="p-5 border-b border-[#EAE5D9] flex justify-between items-center bg-[#FAF8F2]">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47]">
                        <i data-lucide="qr-code" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-[#2C3E2D] text-sm leading-tight">ลิงก์ & QR Code รับสมัคร</h3>
                        <p class="text-[11px] text-[#7B8D65]">สำหรับแชร์หรือดาวน์โหลดไปใช้บนสื่อประชาสัมพันธ์</p>
                    </div>
                </div>
                <button type="button" onclick="closeQrModal()" class="w-8 h-8 rounded-lg text-[#8C8275] hover:text-[#2C3E2D] hover:bg-[#EAE5D9] transition flex items-center justify-center">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5">
                <!-- Course Info Header -->
                <div class="bg-[#FAF8F2] border border-[#EAE5D9] rounded-2xl p-4">
                    <div id="qr-event-title" class="font-heading font-bold text-sm text-[#2C3E2D] line-clamp-2 leading-snug">
                        -
                    </div>
                    <div class="flex items-center gap-2 mt-2 text-xs text-[#7B8D65]">
                        <span id="qr-event-org" class="px-2 py-0.5 rounded-md bg-white border border-[#EAE5D9] text-[#2C3E2D] font-medium text-[10px]">
                            -
                        </span>
                        <span id="qr-event-date" class="text-[11px] font-mono text-[#8C8275]">
                            -
                        </span>
                    </div>
                </div>

                <!-- QR Code Box -->
                <div class="flex flex-col items-center justify-center p-4 bg-[#FAF8F2]/60 border border-[#EAE5D9] rounded-2xl">
                    <div class="bg-white p-3.5 border-2 border-[#D5CEBC] rounded-2xl shadow-sm text-center relative group">
                        <img id="qr-image" crossOrigin="anonymous" src="" alt="Course QR Code" class="w-44 h-44 mx-auto rounded-lg object-contain bg-white" />
                        <div id="qr-loading-spinner" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-2xl hidden">
                            <i data-lucide="loader-2" class="w-6 h-6 text-[#5A6B47] animate-spin"></i>
                        </div>
                    </div>
                    <div class="text-[11px] text-[#7B8D65] mt-2.5 flex items-center gap-1 font-mono">
                        <i data-lucide="scan-line" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                        <span>สแกนด้วยสมาร์ทโฟนเพื่อเข้าสู่หน้าลงทะเบียน</span>
                    </div>
                </div>

                <!-- Copy Link Input Section -->
                <div>
                    <label class="block font-semibold text-[#4A3B32] text-xs mb-1.5 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="link" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                            <span>URL ลงทะเบียนโดยตรง (Direct Registration Link):</span>
                        </span>
                        <span id="copy-status-badge" class="text-[10px] text-emerald-700 font-medium bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 hidden">
                            คัดลอกแล้ว!
                        </span>
                    </label>
                    <div class="flex items-center gap-1.5">
                        <input type="text" id="qr-link-input" readonly class="flex-grow px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs font-mono text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47] select-all cursor-pointer" onclick="this.select()">
                        <button type="button" id="copy-btn" onclick="copyCourseLink()" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-3.5 py-2 rounded-xl text-xs font-medium transition flex items-center gap-1.5 shrink-0 shadow-sm active:scale-95">
                            <i data-lucide="copy" class="w-3.5 h-3.5" id="copy-icon"></i>
                            <span id="copy-text">คัดลอก</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2] flex items-center justify-between gap-3">
                <a id="qr-open-link" href="#" target="_blank" class="px-4 py-2 bg-white hover:bg-[#FAF8F2] text-[#2C3E2D] border border-[#D5CEBC] rounded-xl text-xs font-medium transition flex items-center gap-1.5 shadow-xs">
                    <i data-lucide="external-link" class="w-3.5 h-3.5 text-[#7B8D65]"></i>
                    <span>เปิดดูหน้าสมัคร</span>
                </a>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeQrModal()" class="px-4 py-2 border border-[#EAE5D9] rounded-xl hover:bg-stone-50 font-medium text-xs text-[#6B6357]">
                        ปิด
                    </button>
                    <button type="button" onclick="downloadCourseQr()" class="bg-[#2C3E2D] hover:bg-[#1E2B1F] text-white px-4 py-2 rounded-xl text-xs font-medium shadow-sm transition flex items-center gap-1.5 active:scale-95">
                        <i data-lucide="download" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span>ดาวน์โหลดภาพ QR Code (PNG)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentQrEvent = null;

        function openQrModal(event) {
            currentQrEvent = event;
            const eventId = event.id;
            
            // Build absolute URL for production / current origin
            const baseUrl = window.location.origin;
            const registerUrl = `${baseUrl}/public_register.php?event_id=${eventId}`;

            // Populate Modal Content
            document.getElementById('qr-event-title').textContent = event.title || 'คอร์สปฏิบัติธรรม';
            document.getElementById('qr-event-org').textContent = (event.organization_unit && event.organization_unit.name_th) ? event.organization_unit.name_th : 'มจร';
            
            let dateStr = '';
            if (event.start_date && event.end_date) {
                dateStr = `${event.start_date} ถึง ${event.end_date}`;
            }
            document.getElementById('qr-event-date').textContent = dateStr;

            // Link Input & Open Link Button
            const linkInput = document.getElementById('qr-link-input');
            linkInput.value = registerUrl;
            document.getElementById('qr-open-link').href = registerUrl;

            // QR Code Image (Using QRServer API with High Resolution 400x400)
            const qrImg = document.getElementById('qr-image');
            const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=400x400&margin=10&data=${encodeURIComponent(registerUrl)}`;
            qrImg.src = qrApiUrl;

            // Reset copy button status
            resetCopyButton();

            // Show Modal
            document.getElementById('qr-modal').classList.remove('hidden');
            lucide.createIcons();
        }

        function closeQrModal() {
            document.getElementById('qr-modal').classList.add('hidden');
        }

        // Copy Course URL to Clipboard
        function copyCourseLink() {
            const linkInput = document.getElementById('qr-link-input');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999); // Mobile devices

            navigator.clipboard.writeText(linkInput.value).then(() => {
                const copyBtn = document.getElementById('copy-btn');
                const copyText = document.getElementById('copy-text');
                const copyIcon = document.getElementById('copy-icon');
                const badge = document.getElementById('copy-status-badge');

                copyText.textContent = 'คัดลอกแล้ว!';
                copyBtn.classList.remove('bg-[#5A6B47]', 'hover:bg-[#2C3E2D]');
                copyBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                badge.classList.remove('hidden');

                setTimeout(resetCopyButton, 3000);
            }).catch(() => {
                // Fallback for older browsers
                document.execCommand('copy');
                alert('คัดลอกลิงก์สำเร็จแล้ว!');
            });
        }

        function resetCopyButton() {
            const copyBtn = document.getElementById('copy-btn');
            const copyText = document.getElementById('copy-text');
            const badge = document.getElementById('copy-status-badge');
            if (copyBtn && copyText) {
                copyText.textContent = 'คัดลอก';
                copyBtn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
                copyBtn.classList.add('bg-[#5A6B47]', 'hover:bg-[#2C3E2D]');
            }
            if (badge) {
                badge.classList.add('hidden');
            }
        }

        // Download QR Code as PNG file directly
        function downloadCourseQr() {
            const qrImg = document.getElementById('qr-image');
            if (!qrImg || !qrImg.src) return;

            const filename = `QR-Course-${currentQrEvent ? currentQrEvent.id : 'mcu'}.png`;

            fetch(qrImg.src)
                .then(res => res.blob())
                .then(blob => {
                    const blobUrl = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(blobUrl);
                })
                .catch(() => {
                    // Fallback to opening in a new tab if blob fetch fails
                    window.open(qrImg.src, '_blank');
                });
        }

        // Close modal when pressing ESC or clicking outside
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQrModal();
                const editModal = document.getElementById('edit-event-modal');
                if (editModal) editModal.classList.add('hidden');
            }
        });

        document.getElementById('qr-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQrModal();
            }
        });

        function openEditModal(event) {
            document.getElementById('edit-event-form').action = "{{ url('/admin/public_events.php') }}";
            document.getElementById('edit_event_id').value = event.id;
            document.getElementById('edit-title').value = event.title || '';
            document.getElementById('edit-description').value = event.description || '';
            document.getElementById('edit-location').value = event.location_name || '';
            document.getElementById('edit-start-date').value = event.start_date || '';
            document.getElementById('edit-end-date').value = event.end_date || '';
            document.getElementById('edit-quota').value = event.max_quota || 50;
            document.getElementById('edit-status').value = event.status || 'OPEN';

            // Handle cover image preview and input
            const coverInput = document.getElementById('edit_cover_image');
            const previewContainer = document.getElementById('edit_cover_preview_container');
            const previewImg = document.getElementById('edit_cover_preview');
            const previewUrlText = document.getElementById('edit_cover_url_text');

            if (event.cover_image) {
                coverInput.value = event.cover_image;
                previewImg.src = event.cover_image;
                previewUrlText.textContent = event.cover_image;
                previewContainer.classList.remove('hidden');
            } else {
                coverInput.value = '';
                previewContainer.classList.add('hidden');
            }

            const orgSelect = document.getElementById('edit-org-unit-id');
            if (orgSelect && event.org_unit_id) {
                orgSelect.value = event.org_unit_id;
            }

            document.getElementById('edit-event-modal').classList.remove('hidden');
        }

        lucide.createIcons();
    </script>

</body>
</html>
