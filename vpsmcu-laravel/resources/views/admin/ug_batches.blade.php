<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการโครงการ & กำหนดการปฏิบัติธรรม ป.ตรี - VPSMCU Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

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

        h1,
        h2,
        h3,
        h4,
        .font-heading {
            font-family: 'Prompt', sans-serif;
        }

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
        <div
            class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30">
                        @if ($isCentralOrSuper)
                            <i data-lucide="shield-check" class="w-3 h-3 inline mr-1"></i> ส่วนกลาง: ตรวจสอบและจัดการได้ 52
                            ส่วนงานทั่วประเทศ
                        @else
                            <i data-lucide="building-2" class="w-3 h-3 inline mr-1"></i> สิทธิ์ประจำวิทยาเขต:
                            จัดการเฉพาะกำหนดการของวิทยาเขตตนเอง
                        @endif
                    </span>
                </div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">ปฏิทิน & กำหนดการปฏิบัติธรรม ป.ตรี
                    ประจำปีการศึกษา</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">แต่ละส่วนงานจัดปฏิบัติธรรมปีละ 1 ครั้ง (หลักสูตร 10
                    วัน) เพื่อให้นิสิตเลือกลงทะเบียนตามปีการศึกษา</p>
            </div>
            <button onclick="document.getElementById('add-batch-modal').classList.remove('hidden')"
                class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-2.5 rounded-xl text-xs font-medium shadow-md transition flex items-center gap-2 self-start sm:self-auto">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> เพิ่มกำหนดการประจำปีการศึกษาใหม่
            </button>
        </div>

        @if (session('success'))
            <div
                class="bg-[#5A6B47]/10 border border-[#5A6B47]/30 text-[#2C3E2D] px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-[#5A6B47]"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
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

        <!-- Filter Bar for Central Officers -->
        @if ($isCentralOrSuper)
            <div class="earth-admin-card p-4 mb-6">
                <form method="GET" action="{{ route('admin.ug.batches') }}"
                    class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <i data-lucide="filter" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span class="text-xs font-semibold text-[#4A3B32]">ตัวกรองมุมมองส่วนกลาง:</span>
                        <select name="filter_org" onchange="this.form.submit()"
                            class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="">-- แสดงกำหนดการทุกสังกัด ({{ count($orgUnits) }} สังกัด) --</option>
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}" {{ request('filter_org') == $org->id ? 'selected' : '' }}>
                                    {{ $org->name_th }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-xs text-[#7B8D65]">
                        พบกำหนดการทั้งหมด <strong>{{ $batches->total() }}</strong> รายการ
                    </div>
                </form>
            </div>
        @endif

        <!-- Batches Grid / Table -->
        <div class="earth-admin-card overflow-hidden">
            <div class="p-5 border-b border-[#EAE5D9] flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-[#FAF8F2]/60">
                <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                    <i data-lucide="calendar-check" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>ตารางกำหนดการปฏิบัติธรรมประจำปีการศึกษา</span>
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
                    <span class="text-xs bg-white border border-[#EAE5D9] px-3 py-1 rounded-full text-[#7B8D65] font-medium">ทั้งหมด {{ $batches->total() }} รายการ</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead
                        class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                        <tr>
                            <th class="p-4">ปีการศึกษา</th>
                            <th class="p-4">ชื่อโครงการ & สถานที่</th>
                            <th class="p-4">ส่วนงานเจ้าของโครงการ</th>
                            <th class="p-4">ช่วงเวลาปฏิบัติธรรม (10 วัน)</th>
                            <th class="p-4 text-center">โควตา / สมัครแล้ว</th>
                            <th class="p-4 text-center">สถานะ</th>
                            <th class="p-4 text-right">ดำเนินการ (Action)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($batches as $b)
                            <tr class="hover:bg-[#FAF8F2]/80 transition">
                                <td class="p-4">
                                    <span class="font-mono font-bold text-[#C86D51] text-sm">ปีการศึกษา
                                        {{ $b->academic_year }}</span>
                                    <div class="text-[#7B8D65] text-[11px]">ปฏิบัติธรรมปีละ 1 ครั้ง</div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-[#EAE5D9] border border-[#D5CEBC] overflow-hidden shrink-0 flex items-center justify-center">
                                            @if (!empty($b->cover_image))
                                                <img src="{{ $b->cover_image }}" alt="{{ $b->title }}" class="w-full h-full object-cover">
                                            @else
                                                <i data-lucide="image" class="w-5 h-5 text-[#8C8275]"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-heading font-semibold text-[#2C3E2D] text-sm">
                                                {{ $b->title }}
                                            </div>
                                            <div class="text-[#7B8D65] text-[11px] flex items-center gap-1 mt-0.5">
                                                <i data-lucide="map-pin" class="w-3 h-3 text-[#C86D51]"></i>
                                                <span>{{ $b->location }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span
                                        class="px-2.5 py-1 rounded-lg bg-[#FAF8F2] border border-[#EAE5D9] text-[#2C3E2D] font-medium text-[11px]">
                                        {{ $b->organizationUnit->name_th ?? 'มจร ส่วนกลาง' }}
                                    </span>
                                </td>
                                <td class="p-4 font-mono text-[11px]">
                                    <div class="text-[#2C3E2D] font-semibold">{{ $b->start_date }}</div>
                                    <div class="text-[#7B8D65]">ถึง {{ $b->end_date }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $regCount = $b->registrations->count();
                                        $isFull = $b->max_quota > 0 && $regCount >= $b->max_quota;
                                    @endphp
                                    <span
                                        class="font-mono font-bold {{ $isFull ? 'text-red-600' : 'text-[#5A6B47]' }} text-sm">
                                        {{ $regCount }}
                                    </span>
                                    <span class="text-[#7B8D65]">/ {{ $b->max_quota }}</span>
                                    @if ($isFull)
                                        <div class="text-[10px] text-red-600 font-semibold mt-0.5">ที่นั่งเต็ม</div>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if ($b->status === 'OPEN')
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30">
                                            เปิดรับสมัคร
                                        </span>
                                    @elseif ($b->status === 'CLOSED')
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#C86D51]/15 text-[#C86D51] border border-[#C86D51]/30">
                                            ปิดรับสมัคร
                                        </span>
                                    @elseif ($b->status === 'IN_PROGRESS')
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-300">
                                            กำลังปฏิบัติธรรม
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-stone-100 text-stone-700 border border-stone-300">
                                            เสร็จสิ้นแล้ว
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Quick Status Switch (วางไว้หน้าสุด) -->
                                        @if ($b->status === 'OPEN')
                                            <a href="{{ url('/admin/ug_batches.php?action=status&id=' . $b->id . '&status_val=CLOSED') }}"
                                                title="กดเพื่อปิดรับสมัครชั่วคราว"
                                                class="p-1.5 bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#4A3B32] rounded-lg border border-[#EAE5D9] transition">
                                                <i data-lucide="pause-circle" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @else
                                            <a href="{{ url('/admin/ug_batches.php?action=status&id=' . $b->id . '&status_val=OPEN') }}"
                                                title="กดเพื่อเปิดรับสมัคร"
                                                class="p-1.5 bg-[#5A6B47]/10 hover:bg-[#5A6B47]/20 text-[#5A6B47] rounded-lg border border-[#5A6B47]/30 transition">
                                                <i data-lucide="play-circle" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif

                                        <!-- Edit Batch -->
                                        <button type="button" onclick="openEditBatchModal({
                                                    id: {{ $b->id }},
                                                    org_unit_id: {{ $b->org_unit_id }},
                                                    academic_year: {{ $b->academic_year }},
                                                    title: @js($b->title),
                                                    location: @js($b->location),
                                                    start_date: @js($b->start_date),
                                                    end_date: @js($b->end_date),
                                                    max_quota: {{ $b->max_quota }},
                                                    status: @js($b->status)
                                                })" title="แก้ไขข้อมูลโครงการ"
                                            class="p-1.5 bg-[#FAF8F2] hover:bg-[#5A6B47]/15 text-[#5A6B47] rounded-lg border border-[#EAE5D9] transition">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <!-- Delete -->
                                        <a href="{{ url('/admin/ug_batches.php?action=delete&id=' . $b->id) }}"
                                            onclick="return confirm('ยืนยันลบรอบโครงการนี้หรือไม่? ข้อมูลการลงทะเบียนของนิสิตในรอบนี้จะถูกลบด้วย')"
                                            title="ลบรอบโครงการ"
                                            class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg border border-red-200 transition">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-[#8C8275]">
                                    <i data-lucide="calendar-x" class="w-8 h-8 mx-auto mb-2 text-[#C86D51]"></i>
                                    <div>ยังไม่มีกำหนดการโครงการปฏิบัติธรรมที่บันทึกไว้ในส่วนงานนี้</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($batches->hasPages())
                <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2]/60">
                    {{ $batches->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- Modal Form: เพิ่มกำหนดการโครงการใหม่ -->
    <div id="add-batch-modal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div
            class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-xl w-full p-6 md:p-8 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div>
                    <h3 class="text-lg font-heading font-bold text-[#2C3E2D]">เพิ่มกำหนดการปฏิบัติธรรมประจำปีการศึกษา
                    </h3>
                    <p class="text-xs text-[#7B8D65]">กำหนดช่วงเวลา 10 วัน และโควตานิสิตที่เปิดรับ (จัดปีละ 1 ครั้ง)</p>
                </div>
                <button type="button" onclick="document.getElementById('add-batch-modal').classList.add('hidden')"
                    class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ url('/admin/ug_batches.php') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="action" value="store">

                @if ($isCentralOrSuper)
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">ส่วนงาน/สังกัดเจ้าของโครงการ ({{ count($orgUnits) }} สังกัด) <span
                                class="text-[#C86D51]">*</span></label>
                        <select name="org_unit_id" required
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}">{{ $org->name_th }} ({{ $org->province_th ?: 'มจร' }})</option>
                            @endforeach
                        </select>
                        <span class="text-[10px] text-[#7B8D65] mt-0.5 block">ในฐานะเจ้าหน้าที่ส่วนกลาง
                            ท่านสามารถสร้างกำหนดการแทนวิทยาเขตใดก็ได้</span>
                    </div>
                @endif

                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">ปีการศึกษา (เช่น 2569) <span
                            class="text-[#C86D51]">*</span></label>
                    <input type="number" name="academic_year" value="2569" required
                        class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    <input type="hidden" name="batch_no" value="1">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">ชื่อโครงการปฏิบัติวิปัสสนากรรมฐาน <span
                                class="text-[#C86D51]">*</span></label>
                        <span class="text-[10px] text-[#8C8275]">(สูงสุดไม่เกิน 255 ตัวอักษร)</span>
                    </div>
                    <input type="text" name="title" maxlength="255"
                        placeholder="เช่น โครงการปฏิบัติวิปัสสนากรรมฐาน ประจำปีการศึกษา 2569" required
                        class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">สถานที่จัดกิจกรรม <span
                                class="text-[#C86D51]">*</span></label>
                        <span class="text-[10px] text-[#8C8275]">(สูงสุดไม่เกิน 255 ตัวอักษร)</span>
                    </div>
                    <input type="text" name="location" maxlength="255" placeholder="เช่น อาคาร 72 พรรษา ศูนย์พัฒนาศาสนศึกษา หรือ วัด..."
                        required
                        class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
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

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">วันเริ่มโครงการ (Start Date) <span
                                class="text-[#C86D51]">*</span></label>
                        <input type="date" name="start_date" required
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">วันสิ้นสุดโครงการ (End Date) <span
                                class="text-[#C86D51]">*</span></label>
                        <input type="date" name="end_date" required
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">จำนวนรับสมัครสูงสุด (โควตา) <span
                                class="text-[#C86D51]">*</span></label>
                        <input type="number" name="max_quota" value="100" min="1" required
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">สถานะโครงการ</label>
                        <select name="status"
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="OPEN">เปิดรับสมัคร (Open)</option>
                            <option value="CLOSED">ปิดรับสมัครชั่วคราว (Closed)</option>
                            <option value="IN_PROGRESS">กำลังดำเนินกิจกรรม (In Progress)</option>
                            <option value="COMPLETED">เสร็จสิ้นโครงการ (Completed)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2.5">
                    <button type="button" onclick="document.getElementById('add-batch-modal').classList.add('hidden')"
                        class="px-4 py-2 text-[#6B6357] hover:text-[#2C3E2D] rounded-xl">ยกเลิก</button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white rounded-xl font-medium shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>บันทึกโครงการ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Form: แก้ไขข้อมูลกำหนดการโครงการ -->
    <div id="edit-batch-modal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div
            class="bg-white rounded-3xl border border-[#EAE5D9] shadow-2xl max-w-xl w-full p-6 md:p-8 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center pb-4 border-b border-[#EAE5D9] mb-5">
                <div>
                    <h3 class="text-lg font-heading font-bold text-[#2C3E2D]">แก้ไขกำหนดการปฏิบัติธรรมประจำปีการศึกษา
                    </h3>
                    <p class="text-xs text-[#7B8D65]">แก้ไขข้อมูลโครงการ ช่วงเวลาปฏิบัติธรรม และจำนวนโควตา</p>
                </div>
                <button type="button" onclick="document.getElementById('edit-batch-modal').classList.add('hidden')"
                    class="p-1.5 text-[#8C8275] hover:text-[#2C3E2D] rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="edit-batch-form" action="{{ url('/admin/ug_batches.php') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="action" value="update">
                <input type="hidden" id="edit_batch_id" name="id" value="">

                @if ($isCentralOrSuper)
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">ส่วนงาน/สังกัดเจ้าของโครงการ ({{ count($orgUnits) }} สังกัด) <span
                                class="text-[#C86D51]">*</span></label>
                        <select id="edit_org_unit_id" name="org_unit_id" required
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}">{{ $org->name_th }} ({{ $org->province_th ?: 'มจร' }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">ปีการศึกษา (เช่น 2569) <span
                            class="text-[#C86D51]">*</span></label>
                    <input type="number" id="edit_academic_year" name="academic_year" required
                        class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">ชื่อโครงการปฏิบัติวิปัสสนากรรมฐาน <span
                                class="text-[#C86D51]">*</span></label>
                        <span class="text-[10px] text-[#8C8275]">(สูงสุดไม่เกิน 255 ตัวอักษร)</span>
                    </div>
                    <input type="text" id="edit_title" name="title" maxlength="255" required
                        class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="font-semibold text-[#4A3B32]">สถานที่จัดกิจกรรม <span
                                class="text-[#C86D51]">*</span></label>
                        <span class="text-[10px] text-[#8C8275]">(สูงสุดไม่เกิน 255 ตัวอักษร)</span>
                    </div>
                    <input type="text" id="edit_location" name="location" maxlength="255" required
                        class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
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

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">วันเริ่มโครงการ (Start Date) <span
                                class="text-[#C86D51]">*</span></label>
                        <input type="date" id="edit_start_date" name="start_date" required
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">วันสิ้นสุดโครงการ (End Date) <span
                                class="text-[#C86D51]">*</span></label>
                        <input type="date" id="edit_end_date" name="end_date" required
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">จำนวนรับสมัครสูงสุด (โควตา) <span
                                class="text-[#C86D51]">*</span></label>
                        <input type="number" id="edit_max_quota" name="max_quota" min="1" required
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47] font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">สถานะโครงการ</label>
                        <select id="edit_status" name="status"
                            class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="OPEN">เปิดรับสมัคร (Open)</option>
                            <option value="CLOSED">ปิดรับสมัครชั่วคราว (Closed)</option>
                            <option value="IN_PROGRESS">กำลังดำเนินกิจกรรม (In Progress)</option>
                            <option value="COMPLETED">เสร็จสิ้นโครงการ (Completed)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2.5">
                    <button type="button" onclick="document.getElementById('edit-batch-modal').classList.add('hidden')"
                        class="px-4 py-2 text-[#6B6357] hover:text-[#2C3E2D] rounded-xl">ยกเลิก</button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white rounded-xl font-medium shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>บันทึกการแก้ไข</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditBatchModal(batch) {
            const form = document.getElementById('edit-batch-form');
            form.action = "{{ url('/admin/ug_batches.php') }}";
            document.getElementById('edit_batch_id').value = batch.id;

            const orgSelect = document.getElementById('edit_org_unit_id');
            if (orgSelect) {
                orgSelect.value = batch.org_unit_id;
            }

            document.getElementById('edit_academic_year').value = batch.academic_year;
            document.getElementById('edit_title').value = batch.title;
            document.getElementById('edit_location').value = batch.location;
            document.getElementById('edit_start_date').value = batch.start_date;
            document.getElementById('edit_end_date').value = batch.end_date;
            document.getElementById('edit_max_quota').value = batch.max_quota;
            document.getElementById('edit_status').value = batch.status;

            // Handle cover image preview and input
            const coverInput = document.getElementById('edit_cover_image');
            const previewContainer = document.getElementById('edit_cover_preview_container');
            const previewImg = document.getElementById('edit_cover_preview');
            const previewUrlText = document.getElementById('edit_cover_url_text');

            if (batch.cover_image) {
                coverInput.value = batch.cover_image;
                previewImg.src = batch.cover_image;
                previewUrlText.textContent = batch.cover_image;
                previewContainer.classList.remove('hidden');
            } else {
                coverInput.value = '';
                previewContainer.classList.add('hidden');
            }

            document.getElementById('edit-batch-modal').classList.remove('hidden');
        }

        lucide.createIcons();
    </script>
</body>

</html>