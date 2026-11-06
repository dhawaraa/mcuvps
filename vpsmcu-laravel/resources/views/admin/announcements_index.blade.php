<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการประกาศและเอกสารแนบ - VPSMCU Admin</title>
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

    <!-- Earth Tones Sidebar with Collapsible Submenus -->
    @include('admin.layouts.sidebar')

    <!-- Main Content Standardized Layout -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30">
                        @if ($isCentralOrSuper)
                            <i data-lucide="shield-check" class="w-3 h-3 inline mr-1"></i> ส่วนกลาง: จัดการประกาศทุกส่วนงาน
                        @else
                            <i data-lucide="building-2" class="w-3 h-3 inline mr-1"></i> วิทยาเขต: จัดการประกาศประจำส่วนงาน
                        @endif
                    </span>
                </div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">ระบบจัดการประกาศและเอกสารแนบ</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">เผยแพร่ประกาศรายชื่อผู้มีสิทธิ์เข้าอบรม คำสั่ง กำหนดการ และเอกสารราชการ พร้อมระบบค้นหาข้อมูลในเอกสาร PDF</p>
            </div>
            <button onclick="document.getElementById('add-announcement-modal').classList.remove('hidden')" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2 self-start sm:self-auto">
                <i data-lucide="file-plus" class="w-4 h-4"></i> เพิ่มประกาศใหม่
            </button>
        </div>

        @if (session('success'))
            <div class="bg-[#5A6B47]/10 border border-[#5A6B47]/30 text-[#2C3E2D] px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-[#5A6B47] shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
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
            <form method="GET" action="{{ route('admin.announcements.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-72">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-[#8C8275]"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาหัวข้อ, เลขที่ประกาศ หรือรายชื่อใน PDF..." class="w-full pl-9 pr-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47] focus:outline-none">
                    </div>

                    <select name="category" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกหมวดหมู่ --</option>
                        <option value="PARTICIPANTS" {{ request('category') == 'PARTICIPANTS' ? 'selected' : '' }}>ประกาศรายชื่อผู้มีสิทธิ์</option>
                        <option value="SCHEDULE" {{ request('category') == 'SCHEDULE' ? 'selected' : '' }}>กำหนดการและสถานที่</option>
                        <option value="ORDER" {{ request('category') == 'ORDER' ? 'selected' : '' }}>คำสั่ง / ระเบียบปฏิบัติ</option>
                        <option value="GENERAL" {{ request('category') == 'GENERAL' ? 'selected' : '' }}>ประกาศทั่วไป</option>
                    </select>

                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกสถานะ --</option>
                        <option value="PUBLISHED" {{ request('status') == 'PUBLISHED' ? 'selected' : '' }}>เผยแพร่แล้ว (Published)</option>
                        <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : '' }}>แบบร่าง (Draft)</option>
                        <option value="ARCHIVED" {{ request('status') == 'ARCHIVED' ? 'selected' : '' }}>เก็บถาวร (Archived)</option>
                    </select>

                    <button type="submit" class="px-3 py-1.5 bg-[#2C3E2D] text-white rounded-lg text-xs font-medium hover:bg-[#3D523E] transition flex items-center gap-1.5">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> กรอง
                    </button>
                    @if(request()->anyFilled(['search', 'category', 'status']))
                        <a href="{{ route('admin.announcements.index') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1">
                            <i data-lucide="x" class="w-3 h-3"></i> ล้างตัวกรอง
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5 text-xs text-[#7B8D65]">
                        <span>แสดง:</span>
                        <select onchange="location.href=this.value" class="px-2 py-1 text-xs bg-white border border-[#D5CEBC] rounded-lg text-[#2C3E2D] focus:ring-1 focus:ring-[#5A6B47]">
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => 1]) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 20, 'page' => 1]) }}" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 50, 'page' => 1]) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 100, 'page' => 1]) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span>รายการ/หน้า</span>
                    </div>
                    <div class="text-xs text-[#7B8D65]">
                        ประกาศทั้งหมด <strong>{{ $announcementsList->total() }}</strong> รายการ
                    </div>
                </div>
            </form>
        </div>

        <!-- Announcements Table -->
        <div class="earth-admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#D5CEBC] bg-[#2C3E2D] text-[#FAF8F2] text-xs font-heading">
                            <th class="p-3.5 text-center w-12 whitespace-nowrap">หมุด</th>
                            <th class="p-3.5 whitespace-nowrap">เลขที่ / หมวดหมู่</th>
                            <th class="p-3.5 min-w-[260px]">หัวข้อประกาศ & ส่วนงาน</th>
                            <th class="p-3.5 whitespace-nowrap">เอกสารแนบ (PDF)</th>
                            <th class="p-3.5 text-center whitespace-nowrap">วันที่เผยแพร่</th>
                            <th class="p-3.5 text-center whitespace-nowrap">สถานะ</th>
                            <th class="p-3.5 text-center whitespace-nowrap">ยอดวิว</th>
                            <th class="p-3.5 text-center whitespace-nowrap w-28">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9] text-xs">
                        @forelse ($announcementsList as $anc)
                            <tr class="hover:bg-[#FAF8F2] transition {{ $anc->is_pinned ? 'bg-[#5A6B47]/5' : '' }}">
                                <!-- Pin Toggle -->
                                <td class="p-3.5 text-center align-middle whitespace-nowrap">
                                    <a href="{{ url('/admin/announcements.php?action=toggle_pin&id=' . $anc->id) }}" title="{{ $anc->is_pinned ? 'ยกเลิกการปักหมุด' : 'ปักหมุดประกาศนี้' }}" class="inline-block transition hover:scale-110">
                                        @if ($anc->is_pinned)
                                            <i data-lucide="pin" class="w-4 h-4 text-[#C86D51] fill-current"></i>
                                        @else
                                            <i data-lucide="pin" class="w-4 h-4 text-[#D5CEBC] hover:text-[#5A6B47]"></i>
                                        @endif
                                    </a>
                                </td>

                                <!-- Announcement No & Category -->
                                <td class="p-3.5 align-middle whitespace-nowrap">
                                    <div class="font-mono font-semibold text-[#2C3E2D]">
                                        {{ $anc->announcement_no ?: '-' }}
                                    </div>
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium border mt-1 {{ $anc->category_badge_class }}">
                                        {{ $anc->category_label }}
                                    </span>
                                </td>

                                <!-- Title & Details -->
                                <td class="p-3.5 align-middle">
                                    <div class="font-semibold text-sm text-[#2C3E2D] line-clamp-1" title="{{ $anc->title }}">
                                        {{ $anc->title }}
                                    </div>
                                    @if ($anc->title_en)
                                        <div class="text-[11px] text-[#8C8275] line-clamp-1 italic mt-0.5">
                                            {{ $anc->title_en }}
                                        </div>
                                    @endif
                                    <div class="text-[11px] text-[#7B8D65] flex items-center gap-1 mt-1">
                                        <i data-lucide="building-2" class="w-3 h-3 shrink-0"></i>
                                        <span>{{ $anc->organizationUnit ? $anc->organizationUnit->name : 'ส่วนกลาง (สถาบันวิปัสสนาธุระ)' }}</span>
                                    </div>
                                </td>

                                <!-- Attachment & PDF Preview Action -->
                                <td class="p-3.5 align-middle whitespace-nowrap">
                                    @if ($anc->attachment_path)
                                        <div class="flex items-center gap-2">
                                            <div class="flex flex-col">
                                                <a href="{{ $anc->attachment_url }}" target="_blank" class="flex items-center gap-1.5 text-xs font-semibold text-[#C86D51] hover:underline" title="{{ $anc->attachment_name }}">
                                                    <i data-lucide="file-text" class="w-4 h-4 shrink-0 text-[#C86D51]"></i>
                                                    <span class="max-w-[130px] truncate">{{ $anc->attachment_name ?: 'เอกสารแนบ.pdf' }}</span>
                                                </a>
                                                <span class="text-[10px] text-[#8C8275] font-mono mt-0.5">
                                                    {{ $anc->attachment_size ?: 'PDF' }}
                                                </span>
                                            </div>
                                            <button type="button" onclick="openPdfPreviewModal('{{ $anc->attachment_url }}', '{{ addslashes($anc->title) }}', '{{ addslashes($anc->attachment_name ?: 'เอกสาร') }}')" class="p-1.5 rounded-lg bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#5A6B47] transition border border-[#D5CEBC]" title="เปิดดูเอกสารพรีวิว">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[#8C8275] text-xs italic">ไม่มีไฟล์แนบ</span>
                                    @endif
                                </td>

                                <!-- Published Date -->
                                <td class="p-3.5 text-center align-middle whitespace-nowrap">
                                    <div class="text-xs text-[#4A3B32] font-mono">
                                        {{ $anc->published_at ? \Carbon\Carbon::parse($anc->published_at)->format('d/m/Y') : \Carbon\Carbon::parse($anc->created_at)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-[10px] text-[#8C8275]">
                                        {{ $anc->published_at ? \Carbon\Carbon::parse($anc->published_at)->diffForHumans() : '-' }}
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="p-3.5 text-center align-middle whitespace-nowrap">
                                    @if ($anc->status === 'PUBLISHED')
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-[#5A6B47]/20 text-[#2C3E2D] border border-[#5A6B47]/30">
                                            เผยแพร่แล้ว
                                        </span>
                                    @elseif ($anc->status === 'DRAFT')
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            แบบร่าง
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                            เก็บถาวร
                                        </span>
                                    @endif
                                </td>

                                <!-- Views -->
                                <td class="p-3.5 text-center align-middle whitespace-nowrap font-mono text-xs text-[#6B6357]">
                                    <div class="flex items-center justify-center gap-1">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 text-[#8C8275]"></i>
                                        <span>{{ number_format($anc->views) }}</span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="p-3.5 text-center align-middle whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button" onclick='openEditModal(@json($anc))' class="p-1.5 text-[#5A6B47] hover:bg-[#5A6B47]/10 rounded-lg transition" title="แก้ไขประกาศ">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <a href="{{ url('/admin/announcements.php?action=delete&id=' . $anc->id) }}" onclick="return confirm('ยืนยันที่จะลบประกาศนี้หรือไม่? ข้อมูลจะไม่สามารถกู้คืนได้')" class="p-1.5 text-[#C86D51] hover:bg-red-50 rounded-lg transition" title="ลบประกาศ">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-[#8C8275]">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i data-lucide="file-question" class="w-8 h-8 text-[#D5CEBC]"></i>
                                        <p class="text-sm">ไม่พบข้อมูลประกาศตามเงื่อนไขที่เลือก</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($announcementsList->hasPages())
                <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2]">
                    {{ $announcementsList->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- ========================================== -->
    <!-- 1. Modal เพิ่มประกาศใหม่ -->
    <!-- ========================================== -->
    <div id="add-announcement-modal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-[#D5CEBC]">
            <div class="p-5 border-b border-[#EAE5D9] flex items-center justify-between sticky top-0 bg-white z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center">
                        <i data-lucide="file-plus" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-[#2C3E2D]">เพิ่มประกาศใหม่</h3>
                </div>
                <button type="button" onclick="document.getElementById('add-announcement-modal').classList.add('hidden')" class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ url('/admin/announcements.php') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                @csrf
                <input type="hidden" name="action" value="store">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-[#4A3B32] mb-1">หัวข้อประกาศ (ภาษาไทย) <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="ระบุหัวข้อประกาศ เช่น ประกาศรายชื่อผู้มีสิทธิ์..." class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">เลขที่ประกาศ / คำสั่ง</label>
                        <input type="text" name="announcement_no" placeholder="เช่น วป. ๑๒/๒๕๖๙" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">หมวดหมู่ประกาศ <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47]">
                            <option value="PARTICIPANTS">ประกาศรายชื่อผู้มีสิทธิ์ (Participants)</option>
                            <option value="SCHEDULE">กำหนดการและสถานที่ (Schedule)</option>
                            <option value="ORDER">คำสั่ง / ระเบียบปฏิบัติ (Order & Rules)</option>
                            <option value="GENERAL">ประกาศทั่วไป (General Notice)</option>
                        </select>
                    </div>

                    @if ($isCentralOrSuper)
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ส่วนงานที่ออกประกาศ</label>
                            <select name="org_unit_id" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47]">
                                <option value="">-- ส่วนกลาง (สถาบันวิปัสสนาธุระ) --</option>
                                @foreach ($orgUnits as $ou)
                                    <option value="{{ $ou->id }}">{{ $ou->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">เนื้อหารายละเอียด / ข้อความประกาศ (ย่อ)</label>
                    <textarea name="content" rows="3" placeholder="ระบุรายละเอียด ข้อกำหนด หรือสาระสำคัญของประกาศ..." class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none"></textarea>
                </div>

                <!-- File Attachment Box -->
                <div class="p-4 rounded-xl bg-[#FAF8F2] border border-[#D5CEBC]/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="font-semibold text-[#2C3E2D] flex items-center gap-1.5">
                            <i data-lucide="paperclip" class="w-4 h-4 text-[#5A6B47]"></i>
                            <span>อัปโหลดไฟล์เอกสารแนบ (PDF / DOC / DOCX)</span>
                        </label>
                        <span class="text-[10px] text-[#8C8275]">รองรับไฟล์ขนาดสูงสุด 20MB</span>
                    </div>
                    <input type="file" name="attachment_file" accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full text-xs text-[#4A3B32] file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#5A6B47] file:text-white hover:file:bg-[#2C3E2D] cursor-pointer">
                    <p class="text-[11px] text-[#7B8D65]">
                        <i data-lucide="sparkles" class="w-3 h-3 inline mr-1 text-[#C86D51]"></i>
                        <strong>ระบบอัจฉริยะ:</strong> ระบบจะสกัดข้อความ/รายชื่อในไฟล์ PDF ให้อัตโนมัติ เพื่อให้ผู้ใช้สามารถค้นหารายชื่อผ่านหน้าเว็บได้ทันที
                    </p>
                </div>

                <!-- Custom Search Keywords / Extracted Text -->
                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">
                        คำค้นหา / รายชื่อสำคัญเพิ่มเติมสำหรับระบบสืบค้น (Search Keywords)
                    </label>
                    <textarea name="attachment_text" rows="2" placeholder="พิมพ์ชื่อ-สกุล, เลขประจำตัว หรือคำสำคัญที่ต้องการให้ผู้ใช้งานค้นหาพบในเอกสารนี้ได้..." class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-mono"></textarea>
                    <span class="text-[10px] text-[#8C8275]">สามารถใส่เว้นวรรคหรือขึ้นบรรทัดใหม่ได้ ระบบจะรวมข้อความนี้เข้ากับข้อความที่สกัดจาก PDF</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">สถานะการเผยแพร่ <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47]">
                            <option value="PUBLISHED">เผยแพร่ทันที (Published)</option>
                            <option value="DRAFT">บันทึกเป็นแบบร่าง (Draft)</option>
                            <option value="ARCHIVED">เก็บถาวร (Archived)</option>
                        </select>
                    </div>

                    <div class="flex items-center sm:pt-6">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="is_pinned" value="1" class="w-4 h-4 text-[#5A6B47] border-[#D5CEBC] rounded focus:ring-[#5A6B47]">
                            <span class="font-semibold text-[#2C3E2D]">ปักหมุดประกาศสำคัญไว้บนสุด (Pin)</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('add-announcement-modal').classList.add('hidden')" class="px-4 py-2 bg-white border border-[#D5CEBC] text-[#4A3B32] rounded-xl hover:bg-[#FAF8F2] transition">
                        ยกเลิก
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white font-semibold rounded-xl transition shadow-md flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i> บันทึกและเผยแพร่
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. Modal แก้ไขประกาศ -->
    <!-- ========================================== -->
    <div id="edit-announcement-modal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-[#D5CEBC]">
            <div class="p-5 border-b border-[#EAE5D9] flex items-center justify-between sticky top-0 bg-white z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-[#2C3E2D]">แก้ไขประกาศและเอกสาร</h3>
                </div>
                <button type="button" onclick="document.getElementById('edit-announcement-modal').classList.add('hidden')" class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="edit-announcement-form" action="{{ url('/admin/announcements.php') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                @csrf
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-[#4A3B32] mb-1">หัวข้อประกาศ (ภาษาไทย) <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="edit_title" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">เลขที่ประกาศ / คำสั่ง</label>
                        <input type="text" name="announcement_no" id="edit_announcement_no" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">หมวดหมู่ประกาศ <span class="text-red-500">*</span></label>
                        <select name="category" id="edit_category" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47]">
                            <option value="PARTICIPANTS">ประกาศรายชื่อผู้มีสิทธิ์ (Participants)</option>
                            <option value="SCHEDULE">กำหนดการและสถานที่ (Schedule)</option>
                            <option value="ORDER">คำสั่ง / ระเบียบปฏิบัติ (Order & Rules)</option>
                            <option value="GENERAL">ประกาศทั่วไป (General Notice)</option>
                        </select>
                    </div>

                    @if ($isCentralOrSuper)
                        <div>
                            <label class="block font-semibold text-[#4A3B32] mb-1">ส่วนงานที่ออกประกาศ</label>
                            <select name="org_unit_id" id="edit_org_unit_id" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47]">
                                <option value="">-- ส่วนกลาง (สถาบันวิปัสสนาธุระ) --</option>
                                @foreach ($orgUnits as $ou)
                                    <option value="{{ $ou->id }}">{{ $ou->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">เนื้อหารายละเอียด / ข้อความประกาศ (ย่อ)</label>
                    <textarea name="content" id="edit_content" rows="3" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none"></textarea>
                </div>

                <!-- Current Attachment & Replacement Box -->
                <div class="p-4 rounded-xl bg-[#FAF8F2] border border-[#D5CEBC]/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="font-semibold text-[#2C3E2D] flex items-center gap-1.5">
                            <i data-lucide="paperclip" class="w-4 h-4 text-[#5A6B47]"></i>
                            <span>ไฟล์เอกสารแนบปัจจุบัน</span>
                        </label>
                    </div>
                    
                    <div id="edit_current_attachment_wrapper" class="flex items-center justify-between bg-white p-2.5 rounded-lg border border-[#D5CEBC]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-[#C86D51]"></i>
                            <span id="edit_current_filename" class="font-semibold text-xs text-[#2C3E2D]">--</span>
                            <span id="edit_current_size" class="text-[10px] text-[#8C8275] font-mono">--</span>
                        </div>
                        <label class="flex items-center gap-1.5 text-xs text-red-600 cursor-pointer">
                            <input type="checkbox" name="remove_attachment" value="1" class="w-3.5 h-3.5 rounded text-red-600">
                            <span>ลบไฟล์นี้ออก</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <label class="block font-medium text-[#4A3B32] mb-1 text-[11px]">เลือกไฟล์ใหม่เพื่อแทนที่ (ถ้าต้องการเปลี่ยน):</label>
                        <input type="file" name="attachment_file" accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full text-xs text-[#4A3B32] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#5A6B47] file:text-white hover:file:bg-[#2C3E2D] cursor-pointer">
                    </div>
                </div>

                <!-- Extracted Text Field -->
                <div>
                    <label class="block font-semibold text-[#4A3B32] mb-1">
                        ข้อความ/คำค้นหาในเอกสารสำหรับระบบสืบค้น (Search Index Text)
                    </label>
                    <textarea name="attachment_text" id="edit_attachment_text" rows="3" class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-mono"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <div>
                        <label class="block font-semibold text-[#4A3B32] mb-1">สถานะการเผยแพร่ <span class="text-red-500">*</span></label>
                        <select name="status" id="edit_status" required class="w-full px-3 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47]">
                            <option value="PUBLISHED">เผยแพร่ทันที (Published)</option>
                            <option value="DRAFT">บันทึกเป็นแบบร่าง (Draft)</option>
                            <option value="ARCHIVED">เก็บถาวร (Archived)</option>
                        </select>
                    </div>

                    <div class="flex items-center sm:pt-6">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="is_pinned" id="edit_is_pinned" value="1" class="w-4 h-4 text-[#5A6B47] border-[#D5CEBC] rounded focus:ring-[#5A6B47]">
                            <span class="font-semibold text-[#2C3E2D]">ปักหมุดประกาศสำคัญไว้บนสุด (Pin)</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('edit-announcement-modal').classList.add('hidden')" class="px-4 py-2 bg-white border border-[#D5CEBC] text-[#4A3B32] rounded-xl hover:bg-[#FAF8F2] transition">
                        ยกเลิก
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#5A6B47] hover:bg-[#2C3E2D] text-white font-semibold rounded-xl transition shadow-md flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i> อัปเดตข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 3. Modal พรีวิวเอกสาร PDF (Interactive Viewer) -->
    <!-- ========================================== -->
    <div id="pdf-preview-modal" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-3 sm:p-6 hidden">
        <div class="bg-white rounded-2xl w-full max-w-5xl h-[92vh] flex flex-col shadow-2xl border border-[#D5CEBC] overflow-hidden">
            <!-- Modal Header -->
            <div class="p-4 bg-[#2C3E2D] text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5 min-w-0 pr-4">
                    <div class="w-8 h-8 rounded-lg bg-[#5A6B47] flex items-center justify-center shrink-0">
                        <i data-lucide="file-text" class="w-4 h-4 text-white"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 id="pdf-preview-title" class="font-heading font-bold text-sm truncate text-[#FAF8F2]">พรีวิวเอกสาร</h3>
                        <p id="pdf-preview-filename" class="text-[11px] text-[#A3B88C] font-mono truncate">filename.pdf</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a id="pdf-preview-download-btn" href="#" download class="px-3 py-1.5 rounded-lg bg-[#5A6B47] hover:bg-[#7B8D65] text-white text-xs font-semibold flex items-center gap-1.5 transition">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">ดาวน์โหลด</span>
                    </a>
                    <a id="pdf-preview-external-btn" href="#" target="_blank" class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs transition" title="เปิดแท็บใหม่เต็มจอ">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                    </a>
                    <button type="button" onclick="closePdfPreviewModal()" class="p-1.5 text-white/70 hover:text-white rounded-lg transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body (Iframe) -->
            <div class="flex-grow bg-[#525659] relative">
                <iframe id="pdf-preview-iframe" src="" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons();
            }
        });

        function openEditModal(anc) {
            const form = document.getElementById('edit-announcement-form');
            form.action = "{{ url('/admin/announcements.php') }}";
            document.getElementById('edit_id').value = anc.id;
            document.getElementById('edit_title').value = anc.title || '';
            document.getElementById('edit_announcement_no').value = anc.announcement_no || '';
            document.getElementById('edit_category').value = anc.category || 'PARTICIPANTS';
            document.getElementById('edit_content').value = anc.content || '';
            document.getElementById('edit_status').value = anc.status || 'PUBLISHED';
            document.getElementById('edit_is_pinned').checked = anc.is_pinned == 1;
            document.getElementById('edit_attachment_text').value = anc.attachment_text || '';

            const orgSelect = document.getElementById('edit_org_unit_id');
            if (orgSelect) {
                orgSelect.value = anc.org_unit_id || '';
            }

            const wrapper = document.getElementById('edit_current_attachment_wrapper');
            if (anc.attachment_path) {
                wrapper.classList.remove('hidden');
                document.getElementById('edit_current_filename').textContent = anc.attachment_name || 'เอกสารแนบ';
                document.getElementById('edit_current_size').textContent = `(${anc.attachment_size || 'PDF'})`;
            } else {
                wrapper.classList.add('hidden');
            }

            document.getElementById('edit-announcement-modal').classList.remove('hidden');
            if (window.lucide) window.lucide.createIcons();
        }

        function openPdfPreviewModal(url, title, filename) {
            document.getElementById('pdf-preview-title').textContent = title || 'พรีวิวเอกสาร';
            document.getElementById('pdf-preview-filename').textContent = filename || url.split('/').pop();
            document.getElementById('pdf-preview-download-btn').href = url;
            document.getElementById('pdf-preview-external-btn').href = url;
            document.getElementById('pdf-preview-iframe').src = url + '#toolbar=1&navpanes=1';
            document.getElementById('pdf-preview-modal').classList.remove('hidden');
            if (window.lucide) window.lucide.createIcons();
        }

        function closePdfPreviewModal() {
            document.getElementById('pdf-preview-modal').classList.add('hidden');
            document.getElementById('pdf-preview-iframe').src = '';
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('add-announcement-modal').classList.add('hidden');
                document.getElementById('edit-announcement-modal').classList.add('hidden');
                closePdfPreviewModal();
            }
        });
    </script>
</body>
</html>

