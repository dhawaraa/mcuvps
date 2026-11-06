<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>นำเข้าข้อมูลนิสิต ปริญญาตรี (CSV Import) - VPSMCU Admin</title>
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
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30">
                        @if ($isCentralOrSuper)
                            <i data-lucide="shield-check" class="w-3 h-3 inline mr-1"></i> ส่วนกลาง: นำเข้าฐานข้อมูลนิสิตทุกส่วนงาน
                        @else
                            <i data-lucide="building-2" class="w-3 h-3 inline mr-1"></i> วิทยาเขต: นำเข้าเฉพาะนิสิตในสังกัดของตนเอง
                        @endif
                    </span>
                </div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">นำเข้าข้อมูลนิสิต ปริญญาตรี (Student Master Data)</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">นำเข้าไฟล์ CSV ข้อมูลพื้นฐานของนิสิต เพื่อให้นิสิตใช้รหัสนิสิตค้นหาและเลือกลงทะเบียนโครงการเฉพาะส่วนงานตนเอง</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ url('/admin/ug_import.php?action=template') }}" class="bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#4A3B32] border border-[#D5CEBC] px-4 py-2.5 rounded-xl text-xs font-medium transition flex items-center gap-2 shadow-sm">
                    <i data-lucide="download" class="w-4 h-4 text-[#5A6B47]"></i> ดาวน์โหลดไฟล์ตัวอย่าง CSV
                </a>
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

        @if (isset($errors) && $errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-xs">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Import Card (Google Sheets Link or CSV File) -->
        <div class="earth-admin-card p-6 md:p-8 mb-8">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-4 mb-4 border-b border-[#EAE5D9] gap-3">
                <div>
                    <h2 class="font-heading font-bold text-[#2C3E2D] text-base flex items-center gap-2">
                        <i data-lucide="cloud-download" class="w-5 h-5 text-[#5A6B47]"></i>
                        <span>นำเข้าข้อมูลนิสิต (Import Students)</span>
                    </h2>
                    <p class="text-xs text-[#7B8D65] mt-1">
                        โครงสร้าง 6 คอลัมน์มาตรฐาน: <code class="bg-[#FAF8F2] px-2 py-0.5 rounded border border-[#EAE5D9] text-[#C86D51] font-mono">รหัสนิสิต, ชื่อ - ฉายา/นามสกุล, ระดับ, สังกัด, คณะ, สาขาวิชา</code>
                    </p>
                </div>

                <!-- Tab Buttons -->
                <div class="inline-flex rounded-xl bg-[#FAF8F2] p-1 border border-[#EAE5D9] self-start sm:self-auto">
                    <button type="button" onclick="switchImportTab('gsheets')" id="tab-btn-gsheets" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 bg-[#5A6B47] text-white shadow-xs">
                        <i data-lucide="sheet" class="w-3.5 h-3.5"></i>
                        <span>วางลิงก์ Google Sheets</span>
                    </button>
                    <button type="button" onclick="switchImportTab('csv')" id="tab-btn-csv" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 text-[#6B6357] hover:text-[#2C3E2D]">
                        <i data-lucide="file-up" class="w-3.5 h-3.5"></i>
                        <span>อัปโหลดไฟล์ CSV</span>
                    </button>
                </div>
            </div>

            <!-- Tab 1: Google Sheets Form -->
            <div id="tab-content-gsheets">
                <form action="{{ url('/admin/ug_import.php') }}" method="POST" id="form-import-gsheets" onsubmit="return startImportProgress('gsheets')" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">
                            วางลิงก์แชร์ Google Sheets (Share Link) <span class="text-[#C86D51]">*</span>
                        </label>
                        <div class="relative">
                            <i data-lucide="sheet" class="w-4 h-4 text-emerald-600 absolute left-3.5 top-3"></i>
                            <input type="url" name="sheets_url" required placeholder="https://docs.google.com/spreadsheets/d/1eEBboljFoz0m4lx94kr-4RgRLSLG4rf-BS34BtQjp7E/edit?usp=sharing" class="w-full pl-10 pr-3 py-2.5 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47] font-mono">
                        </div>
                        <p class="text-[11px] text-[#7B8D65] mt-1.5 flex items-center gap-1">
                            <i data-lucide="info" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                            <span>คำแนะนำ: ตรวจสอบให้แน่ใจว่าได้เปิดสิทธิ์การแชร์ใน Google Sheets เป็น <strong>"ทุกคนที่มีลิงก์มีสิทธิ์อ่าน (Anyone with the link can view)"</strong> เรียบร้อยแล้ว</span>
                        </p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" id="btn-submit-gsheets" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-6 py-2.5 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                            <i data-lucide="cloud-download" class="w-4 h-4"></i>
                            <span>ดึงข้อมูลและเริ่มนำเข้าจาก Google Sheets</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tab 2: CSV File Upload Form -->
            <div id="tab-content-csv" class="hidden">
                <form action="{{ url('/admin/ug_import.php') }}" method="POST" enctype="multipart/form-data" id="form-import-csv" onsubmit="return startImportProgress('csv')" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1.5">เลือกไฟล์ CSV จากเครื่องคอมพิวเตอร์ <span class="text-[#C86D51]">*</span></label>
                        <input type="file" name="csv_file" accept=".csv,text/csv,text/plain" required class="w-full px-3 py-2.5 bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl text-xs text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47] file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#5A6B47] file:text-white hover:file:bg-[#2C3E2D] transition">
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" id="btn-submit-csv" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-6 py-2.5 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                            <i data-lucide="file-up" class="w-4 h-4"></i>
                            <span>เริ่มนำเข้าจากไฟล์ CSV</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="earth-admin-card p-4 mb-6">
            <form method="GET" action="{{ url('/admin/ug_import.php') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div class="flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4 text-[#5A6B47]"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหารหัสนิสิต, ชื่อ, ฉายา..." class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47] w-56 font-mono">
                    </div>

                    @if ($isCentralOrSuper)
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[#4A3B32] font-semibold">ส่วนงาน:</span>
                            <select name="filter_org" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                                <option value="">-- ทุกส่วนจัดการศึกษา / สังกัด ({{ count($orgUnits) }} สังกัด) --</option>
                                @foreach ($orgUnits as $org)
                                    <option value="{{ $org->id }}" {{ request('filter_org') == $org->id ? 'selected' : '' }}>
                                        {{ $org->name_th }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <button type="submit" class="px-3 py-1.5 bg-[#FAF8F2] hover:bg-[#EAE5D9] border border-[#D5CEBC] text-[#4A3B32] rounded-lg text-xs font-medium transition">
                        ค้นหา
                    </button>
                    @if (request()->hasAny(['search', 'filter_org']))
                        <a href="{{ url('/admin/ug_import.php') }}" class="text-xs text-[#C86D51] hover:underline">ล้างตัวกรอง</a>
                    @endif
                </div>

                <div class="text-xs text-[#7B8D65]">
                    ฐานข้อมูลนิสิตทั้งหมด: <strong>{{ number_format($totalMasterCount) }}</strong> ท่าน
                </div>
            </form>
        </div>

        <!-- Master Students Table -->
        <div class="earth-admin-card overflow-hidden">
            <div class="p-5 border-b border-[#EAE5D9] flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-[#FAF8F2]/60">
                <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>บัญชีรายชื่อนิสิตในฐานข้อมูล (Student Master Records)</span>
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
                    <span class="text-xs bg-white border border-[#EAE5D9] px-3 py-1 rounded-full text-[#7B8D65] font-medium">
                        แสดง {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} จาก {{ $students->total() }} รายการ
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                        <tr>
                            <th class="p-4">รหัสนิสิต</th>
                            <th class="p-4">ชื่อ - ฉายา/นามสกุล</th>
                            <th class="p-4">ระดับการศึกษา</th>
                            <th class="p-4">คณะ / สาขาวิชา</th>
                            <th class="p-4">ส่วนจัดการศึกษา (ต้นสังกัด)</th>
                            <th class="p-4 text-center">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($students as $s)
                            <tr class="hover:bg-[#FAF8F2]/80 transition">
                                <td class="p-4 font-mono font-bold text-[#C86D51]">
                                    {{ $s->student_code }}
                                </td>
                                <td class="p-4">
                                    <div class="font-heading font-semibold text-[#2C3E2D] text-sm">
                                        {{ $s->name ?: trim("{$s->prefix}{$s->first_name} {$s->last_name}") }}
                                        @if ($s->chaya && !str_contains($s->name, $s->chaya))
                                            <span class="text-[#7B8D65] font-normal text-xs">({{ $s->chaya }})</span>
                                        @endif
                                    </div>
                                    <div class="text-[#7B8D65] text-[11px]">
                                        @if ($s->phone) โทร: {{ $s->phone }} @endif
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded bg-[#FAF8F2] border border-[#EAE5D9] text-[#5A6B47] font-semibold text-[11px]">
                                        {{ $s->degree_level }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-medium text-[#2C3E2D]">{{ $s->faculty ?: '-' }}</div>
                                    <div class="text-[#7B8D65] text-[11px]">{{ $s->major ?: '-' }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-[#2C3E2D] font-medium">{{ $s->organizationUnit->name_th ?? 'มจร ส่วนกลาง' }}</div>
                                    <span class="text-[10px] text-[#7B8D65] font-mono">{{ $s->organizationUnit->code_provincial ?? $s->organizationUnit->code }}</span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30">
                                        พร้อมลงทะเบียน
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-[#8C8275]">
                                    <i data-lucide="file-question" class="w-8 h-8 mx-auto mb-2 text-[#C86D51]"></i>
                                    <div>ยังไม่มีข้อมูลนิสิตในฐานข้อมูล หรือไม่พบข้อมูลตามคำค้นหา</div>
                                    <p class="text-[11px] text-[#7B8D65] mt-1">ท่านสามารถใช้แบบฟอร์มอัปโหลดไฟล์ CSV ด้านบน เพื่อนำเข้ารายชื่อนิสิตเข้าสู่ระบบ</p>
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

    </main>

    <!-- Loading / Processing Modal Overlay with Live Progress Bar -->
    <div id="import-loading-overlay" class="hidden fixed inset-0 z-50 bg-[#2D2A26]/75 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-[#D5CEBC] text-center space-y-5 animate-scaleUp">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-[#5A6B47]/10 flex items-center justify-center text-[#5A6B47]">
                <i data-lucide="cloud-download" class="w-8 h-8 animate-bounce" id="import-loading-icon"></i>
            </div>
            
            <div>
                <h3 class="text-lg font-heading font-bold text-[#2C3E2D]" id="import-loading-title">กำลังนำเข้าข้อมูลนิสิต...</h3>
                <p class="text-xs text-[#7B8D65] mt-1" id="import-loading-subtitle">ระบบกำลังดาวน์โหลดและประมวลผลข้อมูล กรุณารอสักครู่</p>
            </div>

            <!-- Progress Bar -->
            <div class="space-y-1.5">
                <div class="w-full bg-[#EAE5D9] rounded-full h-3 overflow-hidden p-0.5">
                    <div id="import-progress-bar" class="bg-gradient-to-r from-[#5A6B47] to-[#7B8D65] h-2 rounded-full transition-all duration-500 ease-out" style="width: 15%"></div>
                </div>
                <div class="flex justify-between text-[11px] font-mono text-[#8C8275]">
                    <span id="import-status-text">กำลังเชื่อมต่อ Google Sheets...</span>
                    <span id="import-percent-text" class="font-bold text-[#5A6B47]">15%</span>
                </div>
            </div>

            <div class="bg-[#FAF8F2] border border-[#EAE5D9] rounded-xl p-3 text-[11px] text-[#6B6357] text-left space-y-1">
                <div class="flex items-center gap-1.5 font-semibold text-[#2C3E2D]">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                    <span>คำแนะนำระหว่างการประมวลผล</span>
                </div>
                <p>• รายการข้อมูลขนาดใหญ่ (13,000+ แถว) ใช้เวลาประมาณ 6 - 8 วินาที</p>
                <p>• ระบบกำลังจับคู่ 45 สังกัดและทำ Batch Upsert สู่ฐานข้อมูล</p>
                <p>• กรุณาอย่าปิดแท็บหรือรีเฟรชหน้าเว็บขณะระบบกำลังทำงาน</p>
            </div>
        </div>
    </div>

    <script>
        function switchImportTab(type) {
            const tabGsheets = document.getElementById('tab-content-gsheets');
            const tabCsv = document.getElementById('tab-content-csv');
            const btnGsheets = document.getElementById('tab-btn-gsheets');
            const btnCsv = document.getElementById('tab-btn-csv');

            if (type === 'gsheets') {
                tabGsheets.classList.remove('hidden');
                tabCsv.classList.add('hidden');
                btnGsheets.className = 'px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 bg-[#5A6B47] text-white shadow-xs';
                btnCsv.className = 'px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 text-[#6B6357] hover:text-[#2C3E2D]';
            } else {
                tabGsheets.classList.add('hidden');
                tabCsv.classList.remove('hidden');
                btnCsv.className = 'px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 bg-[#5A6B47] text-white shadow-xs';
                btnGsheets.className = 'px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 text-[#6B6357] hover:text-[#2C3E2D]';
            }
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        }

        function startImportProgress(type) {
            const overlay = document.getElementById('import-loading-overlay');
            const pBar = document.getElementById('import-progress-bar');
            const pText = document.getElementById('import-percent-text');
            const statusText = document.getElementById('import-status-text');
            const titleText = document.getElementById('import-loading-title');
            const btnGsheets = document.getElementById('btn-submit-gsheets');
            const btnCsv = document.getElementById('btn-submit-csv');

            // Disable submit buttons to prevent double submission
            if (btnGsheets) btnGsheets.disabled = true;
            if (btnCsv) btnCsv.disabled = true;

            if (type === 'gsheets') {
                titleText.innerText = 'กำลังนำเข้าข้อมูลจาก Google Sheets...';
                statusText.innerText = 'กำลังเชื่อมต่อและดาวน์โหลดข้อมูล...';
            } else {
                titleText.innerText = 'กำลังนำเข้าข้อมูลจากไฟล์ CSV...';
                statusText.innerText = 'กำลังอัปโหลดและอ่านข้อมูลไฟล์ CSV...';
            }

            overlay.classList.remove('hidden');
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                lucide.createIcons();
            }

            // Simulate realistic progress stages matching server execution
            let percent = 15;
            pBar.style.width = percent + '%';
            pText.innerText = percent + '%';

            const interval = setInterval(() => {
                if (percent < 35) {
                    percent += 5;
                    pBar.style.width = percent + '%';
                    pText.innerText = percent + '%';
                    statusText.innerText = 'กำลังประมวลผลแถวข้อมูลและถอดรหัส UTF-8...';
                } else if (percent < 65) {
                    percent += 4;
                    pBar.style.width = percent + '%';
                    pText.innerText = percent + '%';
                    statusText.innerText = 'กำลังตรวจสอบและจับคู่ข้อมูล 45 สังกัด...';
                } else if (percent < 90) {
                    percent += 3;
                    pBar.style.width = percent + '%';
                    pText.innerText = percent + '%';
                    statusText.innerText = 'กำลังบันทึกข้อมูลแบบ Batch Upsert ลงฐานข้อมูล...';
                } else if (percent < 96) {
                    percent += 1;
                    pBar.style.width = percent + '%';
                    pText.innerText = percent + '%';
                    statusText.innerText = 'กำลังสรุปผลการนำเข้าข้อมูล...';
                }
            }, 400);

            return true; // Allow form submission to proceed
        }

        lucide.createIcons();
    </script>
</body>
</html>
