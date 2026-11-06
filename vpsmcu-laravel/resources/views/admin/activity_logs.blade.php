<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการใช้งานระบบ (Activity Logs) | VPSMCU Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons & Tailwind -->
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
            border-radius: 1rem;
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Earth Tones Sidebar (Deep Forest & Olive) -->
    @include('admin.layouts.sidebar')

    <!-- Main Workspace (Layout Standard: flex-grow p-6 md:p-10 overflow-y-auto) -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <span class="text-xs font-bold text-[#5A6B47] uppercase tracking-wider font-mono flex items-center gap-1.5">
                    <i data-lucide="history" class="w-4 h-4"></i> System Settings & Audit Trail
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-[#2C3E2D] mt-1">ประวัติการใช้งานและกิจกรรมในระบบ (Activity Logs)</h1>
                <p class="text-xs text-[#6B6357] mt-1">
                    ตรวจสอบประวัติความเคลื่อนไหวของผู้ใช้งาน, การเข้าสู่ระบบ, การสร้าง แก้ไข และลบข้อมูลในทุกโมดูล
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.activity_logs.index') }}" class="text-xs bg-white border border-[#D5CEBC] hover:border-[#5A6B47] text-[#2C3E2D] px-3.5 py-2 rounded-xl font-semibold shadow-xs flex items-center gap-2 transition">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                    <span>รีเฟรชข้อมูล</span>
                </a>
            </div>
        </div>

        <!-- 3 Summary Metrics Cards (Earth Tones Theme) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
            <!-- Card 1: กิจกรรมทั้งหมดวันนี้ -->
            <div class="earth-admin-card p-5 flex items-center justify-between border-l-4 border-l-[#5A6B47]">
                <div>
                    <div class="text-xs font-medium text-[#7B8D65]">กิจกรรมทั้งหมดวันนี้</div>
                    <div class="text-2xl sm:text-3xl font-heading font-bold text-[#2C3E2D] mt-1">
                        {{ number_format($todayCount) }} <span class="text-xs font-normal text-[#8C8275]">รายการ</span>
                    </div>
                    <div class="text-[11px] text-[#8C8275] mt-1">บันทึกทุก Action ในระบบ</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47] shrink-0">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- Card 2: จำนวนการเข้าสู่ระบบวันนี้ -->
            <div class="earth-admin-card p-5 flex items-center justify-between border-l-4 border-l-[#2C3E2D]">
                <div>
                    <div class="text-xs font-medium text-[#7B8D65]">เข้าสู่ระบบวันนี้ (Login)</div>
                    <div class="text-2xl sm:text-3xl font-heading font-bold text-[#2C3E2D] mt-1">
                        {{ number_format($loginTodayCount) }} <span class="text-xs font-normal text-[#8C8275]">ครั้ง</span>
                    </div>
                    <div class="text-[11px] text-[#8C8275] mt-1">ทั้งสำเร็จและพยายามเข้าใช้</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#2C3E2D]/10 flex items-center justify-center text-[#2C3E2D] shrink-0">
                    <i data-lucide="log-in" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- Card 3: การแก้ไขและจัดการข้อมูลสะสม -->
            <div class="earth-admin-card p-5 flex items-center justify-between border-l-4 border-l-[#C86D51]">
                <div>
                    <div class="text-xs font-medium text-[#7B8D65]">รายการสร้าง/แก้ไข/ลบ</div>
                    <div class="text-2xl sm:text-3xl font-heading font-bold text-[#C86D51] mt-1">
                        {{ number_format($changesCount) }} <span class="text-xs font-normal text-[#8C8275]">รายการ</span>
                    </div>
                    <div class="text-[11px] text-[#8C8275] mt-1">การเปลี่ยนแปลงข้อมูลในระบบ</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#C86D51]/10 flex items-center justify-center text-[#C86D51] shrink-0">
                    <i data-lucide="edit-3" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="earth-admin-card p-5 mb-8">
            <form method="GET" action="{{ route('admin.activity_logs.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- Search Input -->
                    <div class="relative lg:col-span-2">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-[#8C8275] absolute left-3 top-3"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหากิจกรรม, ผู้ใช้งาน, IP Address..." class="w-full pl-9 pr-3 py-2 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                    </div>

                    <!-- Module Filter -->
                    <div>
                        <select name="module" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="">-- ทุกโมดูลระบบ --</option>
                            <option value="AUTH" {{ request('module') === 'AUTH' ? 'selected' : '' }}>ระบบยืนยันตัวตน (Auth)</option>
                            <option value="PUBLIC_EVENTS" {{ request('module') === 'PUBLIC_EVENTS' ? 'selected' : '' }}>คอร์สปฏิบัติธรรม (Events)</option>
                            <option value="PUBLIC_STUDENTS" {{ request('module') === 'PUBLIC_STUDENTS' ? 'selected' : '' }}>ทะเบียนคอร์ส (Public Reg)</option>
                            <option value="UG_BATCHES" {{ request('module') === 'UG_BATCHES' ? 'selected' : '' }}>โครงการ ป.ตรี (UG Batches)</option>
                            <option value="UG_STUDENTS" {{ request('module') === 'UG_STUDENTS' ? 'selected' : '' }}>ทะเบียน ป.ตรี (UG Reg)</option>
                            <option value="GRAD" {{ request('module') === 'GRAD' ? 'selected' : '' }}>บัณฑิตศึกษา (Grad)</option>
                            <option value="DONATIONS" {{ request('module') === 'DONATIONS' ? 'selected' : '' }}>ระบบบริจาค (Donations)</option>
                            <option value="STUDENTS" {{ request('module') === 'STUDENTS' ? 'selected' : '' }}>ฐานข้อมูลนิสิต (Students)</option>
                            <option value="USERS" {{ request('module') === 'USERS' ? 'selected' : '' }}>ผู้ใช้งานระบบ (Users)</option>
                            <option value="MODULES" {{ request('module') === 'MODULES' ? 'selected' : '' }}>จัดการโมดูล (Feature Flags)</option>
                            <option value="ORG_UNITS" {{ request('module') === 'ORG_UNITS' ? 'selected' : '' }}>ส่วนงาน มจร (Org Units)</option>
                        </select>
                    </div>

                    <!-- Action Filter -->
                    <div>
                        <select name="action_type" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="">-- ทุกประเภทกิจกรรม --</option>
                            <option value="LOGIN" {{ request('action_type') === 'LOGIN' ? 'selected' : '' }}>เข้าสู่ระบบ (LOGIN)</option>
                            <option value="LOGOUT" {{ request('action_type') === 'LOGOUT' ? 'selected' : '' }}>ออกจากระบบ (LOGOUT)</option>
                            <option value="LOGIN_FAILED" {{ request('action_type') === 'LOGIN_FAILED' ? 'selected' : '' }}>เข้าสู่ระบบล้มเหลว (FAILED)</option>
                            <option value="CREATE" {{ request('action_type') === 'CREATE' ? 'selected' : '' }}>สร้างข้อมูลใหม่ (CREATE)</option>
                            <option value="UPDATE" {{ request('action_type') === 'UPDATE' ? 'selected' : '' }}>แก้ไขข้อมูล (UPDATE)</option>
                            <option value="STATUS_CHANGE" {{ request('action_type') === 'STATUS_CHANGE' ? 'selected' : '' }}>เปลี่ยนสถานะ (STATUS)</option>
                            <option value="DELETE" {{ request('action_type') === 'DELETE' ? 'selected' : '' }}>ลบข้อมูล (DELETE)</option>
                            <option value="TOGGLE" {{ request('action_type') === 'TOGGLE' ? 'selected' : '' }}>สลับเปิด/ปิด (TOGGLE)</option>
                        </select>
                    </div>

                    <!-- Org Unit Filter (For Central or Super Admin) -->
                    @if ($isCentralOrSuper)
                        <div>
                            <select name="filter_org" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] truncate">
                                <option value="">-- ทุกสังกัด ({{ count($orgUnits) }} สังกัด) --</option>
                                @foreach ($orgUnits as $org)
                                    <option value="{{ $org->id }}" {{ request('filter_org') == $org->id ? 'selected' : '' }}>
                                        {{ $org->name_th }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <!-- Date Range & Submit Toolbar -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-[#EAE5D9]">
                    <div class="flex flex-wrap items-center gap-2 text-xs text-[#6B6357]">
                        <span class="font-medium">ช่วงวันที่:</span>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-2.5 py-1.5 bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-xs text-[#2C3E2D]">
                        <span>ถึง</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-2.5 py-1.5 bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-xs text-[#2C3E2D]">
                        
                        <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-3.5 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5">
                            <i data-lucide="filter" class="w-3.5 h-3.5"></i> กรองข้อมูล
                        </button>

                        @if(request()->hasAny(['search', 'module', 'action_type', 'filter_org', 'start_date', 'end_date']))
                            <a href="{{ route('admin.activity_logs.index') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1 ml-2">
                                <i data-lucide="rotate-ccw" class="w-3 h-3"></i> ล้างตัวกรอง
                            </a>
                        @endif
                    </div>

                    <div class="text-xs text-[#7B8D65]">
                        พบทั้งหมด <strong>{{ $logs->total() }}</strong> รายการ
                    </div>
                </div>
            </form>
        </div>

        <!-- Activity Logs Table Card -->
        <div class="earth-admin-card overflow-hidden">
            <div class="p-5 border-b border-[#EAE5D9] flex justify-between items-center bg-[#FAF8F2]/60">
                <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2 text-sm">
                    <i data-lucide="list" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>ตารางประวัติกิจกรรมและความเคลื่อนไหว</span>
                </h2>
                <span class="text-xs bg-white border border-[#EAE5D9] px-3 py-1 rounded-full text-[#7B8D65] font-medium">
                    แสดง {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} จาก {{ $logs->total() }} รายการ
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                        <tr>
                            <th class="p-4 w-44">วัน-เวลา</th>
                            <th class="p-4 w-48">ผู้ใช้งาน (User)</th>
                            <th class="p-4 text-center w-28">ประเภท (Action)</th>
                            <th class="p-4 text-center w-36">โมดูล (Module)</th>
                            <th class="p-4">รายละเอียดกิจกรรม (Description)</th>
                            <th class="p-4 w-32 font-mono">IP Address</th>
                            <th class="p-4 text-center w-20">ดู</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($logs as $log)
                            @php
                                $actionColors = [
                                    'LOGIN' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'LOGOUT' => 'bg-stone-100 text-stone-700 border-stone-200',
                                    'LOGIN_FAILED' => 'bg-red-50 text-red-700 border-red-200',
                                    'CREATE' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'UPDATE' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'STATUS_CHANGE' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'DELETE' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'TOGGLE' => 'bg-teal-50 text-teal-700 border-teal-200',
                                ];
                                $badgeClass = $actionColors[$log->action] ?? 'bg-stone-50 text-stone-700 border-stone-200';
                            @endphp
                            <tr class="hover:bg-[#FAF8F2]/80 transition">
                                <td class="p-4 font-mono text-[11px]">
                                    <div class="text-[#2C3E2D] font-semibold">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-[#7B8D65]">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} น.
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="font-semibold text-[#2C3E2D]">
                                        {{ $log->user_fullname ?? ($log->username ?? 'ระบบอัตโนมัติ') }}
                                    </div>
                                    <div class="text-[10px] text-[#7B8D65] flex items-center gap-1.5 mt-0.5">
                                        @if ($log->user_role)
                                            <span class="px-1.5 py-0.2 rounded bg-white border border-[#EAE5D9] font-mono">
                                                {{ $log->user_role }}
                                            </span>
                                        @endif
                                        <span>{{ $log->organizationUnit->name_th ?? '' }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border font-mono {{ $badgeClass }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2.5 py-1 rounded-lg bg-[#FAF8F2] border border-[#EAE5D9] text-[#2C3E2D] font-medium text-[10px] font-mono">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="text-[#2C3E2D] line-clamp-2">
                                        {{ $log->description }}
                                    </div>
                                </td>
                                <td class="p-4 font-mono text-[11px] text-[#6B6357]">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                                <td class="p-4 text-center">
                                    <button type="button" onclick="showLogDetails({{ json_encode($log) }})" title="ดูรายละเอียด Log" class="p-1.5 text-[#5A6B47] hover:bg-[#5A6B47]/10 rounded-lg transition">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-[#8C8275]">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-[#C86D51]"></i>
                                    <div class="font-medium">ไม่พบข้อมูลประวัติการใช้งานตามเงื่อนไขที่เลือก</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2]/60">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- Log Details Modal -->
    <div id="log-detail-modal" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full border border-[#D5CEBC] shadow-2xl overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="p-5 border-b border-[#EAE5D9] flex justify-between items-center bg-[#FAF8F2]">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47]">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-[#2C3E2D] text-sm">รายละเอียดกิจกรรมในระบบ</h3>
                        <p class="text-[11px] text-[#7B8D65]">Activity Log Audit Information</p>
                    </div>
                </div>
                <button type="button" onclick="closeLogModal()" class="w-8 h-8 rounded-lg text-[#8C8275] hover:text-[#2C3E2D] hover:bg-[#EAE5D9] transition flex items-center justify-center">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 space-y-4 text-xs">
                <div>
                    <span class="text-[#8C8275] font-medium block mb-1">คำอธิบายกิจกรรม:</span>
                    <div id="modal-desc" class="p-3 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9] text-[#2C3E2D] font-medium leading-relaxed">
                        -
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9]">
                        <span class="text-[#8C8275] block text-[10px] mb-0.5">ประเภทการกระทำ:</span>
                        <span id="modal-action" class="font-bold font-mono text-[#2C3E2D] text-sm">-</span>
                    </div>
                    <div class="p-3 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9]">
                        <span class="text-[#8C8275] block text-[10px] mb-0.5">โมดูลที่เกี่ยวข้อง:</span>
                        <span id="modal-module" class="font-bold font-mono text-[#5A6B47] text-sm">-</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9]">
                        <span class="text-[#8C8275] block text-[10px] mb-0.5">ผู้ใช้งาน:</span>
                        <div id="modal-user" class="font-semibold text-[#2C3E2D]">-</div>
                        <div id="modal-role" class="text-[10px] text-[#7B8D65] font-mono mt-0.5">-</div>
                    </div>
                    <div class="p-3 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9]">
                        <span class="text-[#8C8275] block text-[10px] mb-0.5">วัน-เวลา:</span>
                        <div id="modal-time" class="font-semibold font-mono text-[#2C3E2D]">-</div>
                        <div id="modal-ip" class="text-[10px] text-[#7B8D65] font-mono mt-0.5">IP: -</div>
                    </div>
                </div>

                <!-- User Agent -->
                <div>
                    <span class="text-[#8C8275] font-medium block mb-1">อุปกรณ์และเบราว์เซอร์ (User Agent):</span>
                    <div id="modal-ua" class="p-2.5 bg-[#FAF8F2] rounded-xl border border-[#EAE5D9] text-[#6B6357] font-mono text-[10px] break-all">
                        -
                    </div>
                </div>

                <!-- Details JSON Payload (If any) -->
                <div id="modal-details-container" class="hidden">
                    <span class="text-[#8C8275] font-medium block mb-1">ข้อมูลเพิ่มเติม (Payload JSON):</span>
                    <pre id="modal-details" class="p-3 bg-[#243325] text-emerald-300 rounded-xl text-[11px] font-mono overflow-x-auto max-h-40">-</pre>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2] flex justify-end">
                <button type="button" onclick="closeLogModal()" class="px-5 py-2 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white rounded-xl text-xs font-medium transition shadow-sm">
                    ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>

    <script>
        function showLogDetails(log) {
            document.getElementById('modal-desc').textContent = log.description || '-';
            document.getElementById('modal-action').textContent = log.action || '-';
            document.getElementById('modal-module').textContent = log.module || '-';
            document.getElementById('modal-user').textContent = log.user_fullname || (log.username || 'ระบบอัตโนมัติ');
            document.getElementById('modal-role').textContent = log.user_role ? 'สิทธิ์: ' + log.user_role : '-';
            document.getElementById('modal-time').textContent = log.created_at || '-';
            document.getElementById('modal-ip').textContent = 'IP: ' + (log.ip_address || '-');
            document.getElementById('modal-ua').textContent = log.user_agent || '-';

            const detailsBox = document.getElementById('modal-details-container');
            const detailsPre = document.getElementById('modal-details');
            if (log.details && Object.keys(log.details).length > 0) {
                detailsPre.textContent = JSON.stringify(log.details, null, 2);
                detailsBox.classList.remove('hidden');
            } else {
                detailsBox.classList.add('hidden');
            }

            document.getElementById('log-detail-modal').classList.remove('hidden');
            lucide.createIcons();
        }

        function closeLogModal() {
            document.getElementById('log-detail-modal').classList.add('hidden');
        }

        // Close on ESC
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLogModal();
        });

        // Close on backdrop click
        document.getElementById('log-detail-modal').addEventListener('click', function(e) {
            if (e.target === this) closeLogModal();
        });

        lucide.createIcons();
    </script>

</body>
</html>
