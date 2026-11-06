<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานสถิติการยื่นคำร้อง - VPSMCU Admin</title>
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
            background: #F7F5EE; 
            color: #2D2A26;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Prompt', sans-serif; }

        .earth-card {
            background: #FFFFFF;
            border: 1px solid #EAE5D9;
            box-shadow: 0 4px 20px -2px rgba(44, 62, 45, 0.05);
            border-radius: 1rem;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            main { padding: 0 !important; }
            .earth-card { border: 1px solid #ddd !important; box-shadow: none !important; }
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mcu-logo.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col md:flex-row selection:bg-[#5A6B47] selection:text-white">

    <!-- Earth Tones Sidebar (Deep Forest & Olive) -->
    @include('admin.layouts.sidebar')

    <!-- Main Content Area: Standard Admin Console Layout -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30">
                        <i data-lucide="scroll-text" class="w-3 h-3 inline mr-1"></i> โมดูล 2: ระดับบัณฑิตศึกษา (ป.โท 30 วัน / ป.เอก 45 วัน)
                    </span>
                    @if (!$isCentralOrSuper)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider bg-[#C86D51]/15 text-[#C86D51] border border-[#C86D51]/30">
                            {{ session('admin_user.org_unit_name', 'เฉพาะวิทยาเขตตนเอง') }}
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider bg-[#2C3E2D]/10 text-[#2C3E2D] border border-[#2C3E2D]/20">
                            ภาพรวมทั้งมหาวิทยาลัย ({{ count($orgUnits) }} สังกัด)
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">รายงานสถิติการยื่นคำร้อง</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">สรุปสถิติผลการสะสมวันปฏิบัติวิปัสสนากรรมฐาน คำร้อง e-Document และการอนุมัติใบรับรอง</p>
            </div>

            <!-- Action Buttons: Print & Approvals link -->
            <div class="flex items-center gap-3 no-print">
                <a href="{{ route('admin.grad.approvals') }}" class="px-3.5 py-2 rounded-xl border border-[#D5CEBC] bg-white text-[#4A3B32] hover:bg-[#EAE5D9] transition text-xs font-medium flex items-center gap-2 shadow-sm">
                    <i data-lucide="file-check-2" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>จัดการคำร้อง e-Doc</span>
                </a>
                <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-[#5A6B47] text-white hover:bg-[#475537] transition text-xs font-medium flex items-center gap-2 shadow-sm">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>พิมพ์รายงาน (Print)</span>
                </button>
            </div>
        </div>

        <!-- Filter Card (No Print) -->
        <div class="earth-card p-5 mb-8 no-print">
            <form method="GET" action="{{ route('admin.grad.sar') }}" class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div class="flex items-center gap-2">
                        <i data-lucide="filter" class="w-4 h-4 text-[#5A6B47]"></i>
                        <span class="text-xs font-bold text-[#4A3B32]">ตัวกรองข้อมูล:</span>
                    </div>

                    <select name="degree_level" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-[#FAF8F2] border border-[#EAE5D9] rounded-lg text-[#2C3E2D] font-medium focus:ring-1 focus:ring-[#5A6B47]">
                        <option value="">-- ทุกระดับการศึกษา --</option>
                        <option value="ประกาศนียบัตร (7 วัน)" {{ request('degree_level') === 'ประกาศนียบัตร (7 วัน)' ? 'selected' : '' }}>ประกาศนียบัตร (7 วัน)</option>
                        <option value="ประกาศนียบัตร (15 วัน)" {{ request('degree_level') === 'ประกาศนียบัตร (15 วัน)' ? 'selected' : '' }}>ประกาศนียบัตร (15 วัน)</option>
                        <option value="ประกาศนียบัตร (30 วัน)" {{ request('degree_level') === 'ประกาศนียบัตร (30 วัน)' ? 'selected' : '' }}>ประกาศนียบัตร (30 วัน)</option>
                        <option value="ประกาศนียบัตร (90วัน)" {{ request('degree_level') === 'ประกาศนียบัตร (90วัน)' ? 'selected' : '' }}>ประกาศนียบัตร (90วัน)</option>
                        <option value="ปริญญาตรีปีละ (10วัน)" {{ request('degree_level') === 'ปริญญาตรีปีละ (10วัน)' ? 'selected' : '' }}>ปริญญาตรีปีละ (10วัน)</option>
                        <option value="ปริญญาโท (30 วัน)" {{ request('degree_level') === 'ปริญญาโท (30 วัน)' || request('degree_level') === 'MASTER' ? 'selected' : '' }}>ปริญญาโท (30 วัน)</option>
                        <option value="ปริญญาเอก (45 วัน)" {{ request('degree_level') === 'ปริญญาเอก (45 วัน)' || request('degree_level') === 'DOCTORAL' ? 'selected' : '' }}>ปริญญาเอก (45 วัน)</option>
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

                    @if (request()->hasAny(['degree_level', 'filter_org']))
                        <a href="{{ route('admin.grad.sar') }}" class="text-xs text-[#C86D51] hover:underline flex items-center gap-1">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i> ล้างตัวกรอง
                        </a>
                    @endif
                </div>

                <div class="text-xs text-[#7B8D65]">
                    ข้อมูล ณ วันที่: <strong>{{ date('d/m/Y') }}</strong>
                </div>
            </form>
        </div>

        <!-- 4 KPI Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- 1. นิสิตบัณฑิตศึกษาทั้งหมด -->
            <div class="earth-card p-5 border-l-4 border-[#2C3E2D]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#7B8D65]">นิสิตในระบบทั้งหมด</span>
                    <div class="w-9 h-9 rounded-xl bg-[#2C3E2D]/10 text-[#2C3E2D] flex items-center justify-center">
                        <i data-lucide="users" class="w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="text-3xl font-heading font-bold text-[#2C3E2D]">{{ number_format($totalStudents) }}</div>
                <div class="text-xs text-[#8C8275] mt-1 flex items-center justify-between">
                    <span>ป.โท: {{ number_format($masterCount) }} รูป/คน</span>
                    <span>ป.เอก: {{ number_format($doctoralCount) }} รูป/คน</span>
                </div>
            </div>

            <!-- 2. อนุมัติผ่านเกณฑ์สมบูรณ์ -->
            <div class="earth-card p-5 border-l-4 border-[#5A6B47]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#5A6B47]">อนุมัติผ่านเกณฑ์สมบูรณ์</span>
                    <div class="w-9 h-9 rounded-xl bg-[#5A6B47]/15 text-[#5A6B47] flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="text-3xl font-heading font-bold text-[#5A6B47]">{{ number_format($approvedCount) }}</div>
                <div class="text-xs text-[#7B8D65] mt-1">
                    อัตราความสำเร็จ: <strong>{{ $totalStudents > 0 ? round(($approvedCount / $totalStudents) * 100, 1) : 0 }}%</strong> ของคำร้อง
                </div>
            </div>

            <!-- 3. รออนุมัติ (Lock & Submitted) -->
            <div class="earth-card p-5 border-l-4 border-[#C86D51]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#C86D51]">ยื่นคำร้องรออนุมัติ</span>
                    <div class="w-9 h-9 rounded-xl bg-[#C86D51]/15 text-[#C86D51] flex items-center justify-center">
                        <i data-lucide="clock" class="w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="text-3xl font-heading font-bold text-[#C86D51]">{{ number_format($submittedCount) }}</div>
                <div class="text-xs text-[#8C8275] mt-1">
                    รอตรวจสอบหลักฐาน 4 รายการ & สลิป
                </div>
            </div>

            <!-- 4. วันปฏิบัติธรรมสะสมรวม -->
            <div class="earth-card p-5 border-l-4 border-[#4A3B32]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#4A3B32]">วันปฏิบัติธรรมสะสมรวม</span>
                    <div class="w-9 h-9 rounded-xl bg-[#4A3B32]/10 text-[#4A3B32] flex items-center justify-center">
                        <i data-lucide="award" class="w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="text-3xl font-heading font-bold text-[#4A3B32]">{{ number_format($totalDays) }} <span class="text-sm font-normal text-[#8C8275]">วัน</span></div>
                <div class="text-xs text-[#7B8D65] mt-1">
                    เฉลี่ยต่อนิสิต: <strong>{{ $avgDays }}</strong> วัน
                </div>
            </div>
        </div>

        <!-- Grid 2 Columns: Degree Comparison & e-Document Attachments Status -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- ตารางเปรียบเทียบ ป.โท (30 วัน) vs ป.เอก (45 วัน) -->
            <div class="earth-card p-6">
                <div class="flex items-center justify-between border-b border-[#EAE5D9] pb-4 mb-5">
                    <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2 text-base">
                        <i data-lucide="git-pull-request" class="w-5 h-5 text-[#5A6B47]"></i>
                        <span>เปรียบเทียบสถิติตามระดับการศึกษา</span>
                    </h2>
                    <span class="text-xs text-[#7B8D65]">เกณฑ์ 30 วัน และ 45 วัน</span>
                </div>

                <div class="space-y-6">
                    <!-- Master (ป.โท 30 วัน) -->
                    <div class="p-4 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9]">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-[#5A6B47]"></span>
                                <strong class="text-sm text-[#2C3E2D]">ปริญญาโท (มหาบัณฑิต - เกณฑ์ 30 วัน)</strong>
                            </div>
                            <span class="text-xs font-mono font-bold text-[#2C3E2D]">{{ number_format($masterCount) }} รูป/คน</span>
                        </div>
                        <div class="w-full bg-[#EAE5D9] rounded-full h-2.5 overflow-hidden mb-2">
                            @php 
                                $masterPct = $masterCount > 0 ? round(($masterApproved / $masterCount) * 100) : 0;
                            @endphp
                            <div class="bg-[#5A6B47] h-2.5 rounded-full" style="width: {{ $masterPct }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-[#8C8275]">
                            <span>อนุมัติผ่าน: <strong class="text-[#5A6B47]">{{ number_format($masterApproved) }}</strong> ({{ $masterPct }}%)</span>
                            <span>รออนุมัติ: <strong class="text-[#C86D51]">{{ number_format($masterSubmitted) }}</strong></span>
                        </div>
                    </div>

                    <!-- Doctoral (ป.เอก 45 วัน) -->
                    <div class="p-4 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9]">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-[#C86D51]"></span>
                                <strong class="text-sm text-[#2C3E2D]">ปริญญาเอก (ดุษฎีบัณฑิต - เกณฑ์ 45 วัน)</strong>
                            </div>
                            <span class="text-xs font-mono font-bold text-[#2C3E2D]">{{ number_format($doctoralCount) }} รูป/คน</span>
                        </div>
                        <div class="w-full bg-[#EAE5D9] rounded-full h-2.5 overflow-hidden mb-2">
                            @php 
                                $docPct = $doctoralCount > 0 ? round(($doctoralApproved / $doctoralCount) * 100) : 0;
                            @endphp
                            <div class="bg-[#C86D51] h-2.5 rounded-full" style="width: {{ $docPct }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-[#8C8275]">
                            <span>อนุมัติผ่าน: <strong class="text-[#5A6B47]">{{ number_format($doctoralApproved) }}</strong> ({{ $docPct }}%)</span>
                            <span>รออนุมัติ: <strong class="text-[#C86D51]">{{ number_format($doctoralSubmitted) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- สถิติการจัดทำเอกสารตอบกลับ e-Document -->
            <div class="earth-card p-6">
                <div class="flex items-center justify-between border-b border-[#EAE5D9] pb-4 mb-5">
                    <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2 text-base">
                        <i data-lucide="file-text" class="w-5 h-5 text-[#C86D51]"></i>
                        <span>สถานะเอกสารตอบกลับ e-Document</span>
                    </h2>
                    <span class="text-xs text-[#7B8D65]">ระบบอัปโหลดตอบกลับ</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9] text-center">
                        <i data-lucide="receipt" class="w-6 h-6 text-[#C86D51] mx-auto mb-1"></i>
                        <div class="text-xs text-[#8C8275] mb-1">แนบสลิปโอนเงินแล้ว</div>
                        <div class="text-xl font-heading font-bold text-[#2C3E2D]">{{ number_format($slipCount) }}</div>
                        <div class="text-[10px] text-[#7B8D65] mt-1">{{ $totalStudents > 0 ? round(($slipCount / $totalStudents) * 100) : 0 }}% ของผู้ยื่น</div>
                    </div>

                    <div class="p-4 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9] text-center">
                        <i data-lucide="award" class="w-6 h-6 text-[#5A6B47] mx-auto mb-1"></i>
                        <div class="text-xs text-[#8C8275] mb-1">ใบรับรองภาษาไทย (PDF)</div>
                        <div class="text-xl font-heading font-bold text-[#5A6B47]">{{ number_format($certThCount) }}</div>
                        <div class="text-[10px] text-[#7B8D65] mt-1">อัปโหลดลงนามแล้ว</div>
                    </div>

                    <div class="p-4 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9] text-center">
                        <i data-lucide="globe" class="w-6 h-6 text-[#4A3B32] mx-auto mb-1"></i>
                        <div class="text-xs text-[#8C8275] mb-1">ใบรับรองภาษาอังกฤษ</div>
                        <div class="text-xl font-heading font-bold text-[#4A3B32]">{{ number_format($certEnCount) }}</div>
                        <div class="text-[10px] text-[#7B8D65] mt-1">หลักสูตรนานาชาติ/ขอ EN</div>
                    </div>

                    <div class="p-4 bg-[#FAF8F2] rounded-2xl border border-[#EAE5D9] text-center">
                        <i data-lucide="file-check" class="w-6 h-6 text-[#5A6B47] mx-auto mb-1"></i>
                        <div class="text-xs text-[#8C8275] mb-1">ใบเสร็จรับเงิน (uprcv)</div>
                        <div class="text-xl font-heading font-bold text-[#2C3E2D]">{{ number_format($receiptCount) }}</div>
                        <div class="text-[10px] text-[#7B8D65] mt-1">ออกใบเสร็จส่งให้นิสิต</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Faculty Breakdown Table -->
        <div class="earth-card overflow-hidden mb-8">
            <div class="p-5 border-b border-[#EAE5D9] bg-[#FAF8F2]/60 flex items-center justify-between">
                <h3 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2 text-sm">
                    <i data-lucide="landmark" class="w-4.5 h-4.5 text-[#5A6B47]"></i>
                    <span>จำแนกสถิติตามคณะสังกัด (Faculty Distribution)</span>
                </h3>
                <span class="text-xs text-[#7B8D65]">รวม {{ count($facultyStats) }} คณะ</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                        <tr>
                            <th class="p-4">คณะสังกัด</th>
                            <th class="p-4 text-center">จำนวนนิสิตทั้งหมด</th>
                            <th class="p-4 text-center">อนุมัติผ่านเกณฑ์</th>
                            <th class="p-4 text-center">ร้อยละความสำเร็จ</th>
                            <th class="p-4">แถบสัดส่วน</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($facultyStats as $f)
                            @php
                                $fPct = $f->total > 0 ? round(($f->approved_count / $f->total) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-[#FAF8F2]/80 transition">
                                <td class="p-4 font-semibold text-[#2C3E2D]">{{ $f->faculty ?: 'ไม่ระบุคณะ' }}</td>
                                <td class="p-4 text-center font-bold font-mono">{{ number_format($f->total) }}</td>
                                <td class="p-4 text-center font-bold text-[#5A6B47] font-mono">{{ number_format($f->approved_count) }}</td>
                                <td class="p-4 text-center font-bold {{ $fPct >= 80 ? 'text-[#5A6B47]' : ($fPct >= 50 ? 'text-[#C86D51]' : 'text-gray-500') }}">{{ $fPct }}%</td>
                                <td class="p-4 w-48">
                                    <div class="w-full bg-[#EAE5D9] rounded-full h-2 overflow-hidden">
                                        <div class="bg-[#5A6B47] h-2 rounded-full" style="width: {{ $fPct }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-[#8C8275]">ไม่พบข้อมูลสถิติตามเงื่อนไข</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Campus Breakdown Table (Top 10) -->
        <div class="earth-card overflow-hidden">
            <div class="p-5 border-b border-[#EAE5D9] bg-[#FAF8F2]/60 flex items-center justify-between">
                <h3 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2 text-sm">
                    <i data-lucide="map-pin" class="w-4.5 h-4.5 text-[#C86D51]"></i>
                    <span>ส่วนงาน/วิทยาเขตที่มีนิสิตยื่นคำร้องสูงสุด (Top 10 Campuses)</span>
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                        <tr>
                            <th class="p-4 w-12 text-center">อันดับ</th>
                            <th class="p-4">วิทยาเขต / ส่วนงาน มจร</th>
                            <th class="p-4 text-center">จำนวนคำร้อง</th>
                            <th class="p-4 text-center">อนุมัติผ่านเกณฑ์</th>
                            <th class="p-4 text-center">ร้อยละความสำเร็จ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($campusStats as $idx => $camp)
                            @php
                                $campPct = $camp->total > 0 ? round(($camp->approved_count / $camp->total) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-[#FAF8F2]/80 transition">
                                <td class="p-4 text-center font-bold text-[#7B8D65]">{{ $idx + 1 }}</td>
                                <td class="p-4 font-semibold text-[#2C3E2D]">{{ $camp->campus_name }}</td>
                                <td class="p-4 text-center font-mono font-bold">{{ number_format($camp->total) }}</td>
                                <td class="p-4 text-center font-mono font-bold text-[#5A6B47]">{{ number_format($camp->approved_count) }}</td>
                                <td class="p-4 text-center font-bold text-[#5A6B47]">{{ $campPct }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-[#8C8275]">ไม่พบข้อมูลสถิติวิทยาเขต</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
