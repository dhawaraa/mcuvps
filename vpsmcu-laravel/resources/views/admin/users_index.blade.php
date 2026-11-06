<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้งานและกำหนดสิทธิ์ (Users & Permissions) - VPSMCU Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="/assets/js/lucide.min.js"></script>

    <!-- Tailwind CSS -->
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

    <!-- Main Workspace -->
    <main class="flex-grow p-6 md:p-10 overflow-y-auto">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 mb-8 border-b border-[#D5CEBC] gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30">
                        <i data-lucide="shield-check" class="w-3 h-3 inline mr-1"></i> Central Admin Matrix
                    </span>
                </div>
                <h1 class="text-2xl font-heading font-bold text-[#2C3E2D]">จัดการผู้ใช้งานและกำหนดสิทธิ์ (Users & Permissions)</h1>
                <p class="text-xs text-[#7B8D65] mt-1 font-medium">กำหนดบทบาทผู้ดูแลระบบส่วนกลาง เจ้าหน้าที่ประจำส่วนงาน และบัญชีผู้บริหาร (Executive Dashboard)</p>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="button" onclick="openCreateUserModal()" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center gap-2 shadow-sm">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> เพิ่มผู้ใช้งานใหม่
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-[#5A6B47]/15 border border-[#5A6B47]/30 text-[#2C3E2D] px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2.5 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-[#5A6B47] shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2.5 shadow-sm">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-xs shadow-sm">
                <div class="font-bold mb-1 flex items-center gap-1.5"><i data-lucide="alert-triangle" class="w-4 h-4 text-red-600"></i> ข้อผิดพลาด:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="earth-admin-card p-5 mb-8 flex flex-col md:flex-row justify-between items-center gap-4 bg-white">
            <form method="GET" action="{{ route('admin.users.index') }}" class="w-full flex flex-col md:flex-row items-center gap-3">
                <div class="relative w-full md:w-80">
                    <i data-lucide="search" class="w-4 h-4 text-[#8C8275] absolute left-3.5 top-3"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อผู้ใช้, ชื่อ-สกุล หรืออีเมล..." class="w-full pl-9 pr-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs text-[#2C3E2D] focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                </div>

                <div class="w-full md:w-60">
                    <select name="role_filter" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs text-[#2C3E2D] focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                        <option value="">-- บทบาททั้งหมด (All Roles) --</option>
                        <option value="SUPER_ADMIN" {{ request('role_filter') === 'SUPER_ADMIN' ? 'selected' : '' }}>ผู้ดูแลระบบส่วนกลาง (Super Admin)</option>
                        <option value="CENTRAL_OFFICER" {{ request('role_filter') === 'CENTRAL_OFFICER' ? 'selected' : '' }}>เจ้าหน้าที่ส่วนกลาง (Central Officer)</option>
                        <option value="CAMPUS_ADMIN" {{ request('role_filter') === 'CAMPUS_ADMIN' ? 'selected' : '' }}>เจ้าหน้าที่วิทยาเขต (Campus Admin)</option>
                        <option value="EXECUTIVE" {{ request('role_filter') === 'EXECUTIVE' ? 'selected' : '' }}>ผู้บริหาร (Executive - View only)</option>
                    </select>
                </div>

                @if (request()->filled('search') || request()->filled('role_filter'))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-xs text-[#C86D51] hover:underline flex items-center gap-1">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i> ล้างตัวกรอง
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-4 self-end md:self-center">
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
                <div class="text-xs text-[#7B8D65] whitespace-nowrap font-mono">
                    จำนวนผู้ใช้งาน: <strong class="text-[#2C3E2D] text-sm">{{ $users->total() }}</strong> บัญชี
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="earth-admin-card overflow-hidden">
            <div class="p-5 border-b border-[#EAE5D9] flex justify-between items-center bg-[#FAF8F2]/60">
                <h2 class="font-heading font-bold text-[#2C3E2D] flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-[#5A6B47]"></i>
                    <span>ทำเนียบบัญชีผู้ใช้งานระบบสารสนเทศ</span>
                </h2>
                <span class="text-xs bg-[#FAF8F2] border border-[#EAE5D9] px-3 py-1 rounded-full text-[#7B8D65] font-medium">รวม 4 กลุ่มสิทธิ์ตาม Rule Matrix</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#4A3B32]">
                    <thead class="bg-[#FAF8F2] text-[#4A3B32] font-heading border-b border-[#EAE5D9] uppercase text-[11px]">
                        <tr>
                            <th class="p-4 w-12 text-center">ลำดับ</th>
                            <th class="p-4">ชื่อผู้ใช้ / Username</th>
                            <th class="p-4">ชื่อ-นามสกุล</th>
                            <th class="p-4">บทบาทและระดับสิทธิ์</th>
                            <th class="p-4">ส่วนงานที่สังกัด</th>
                            <th class="p-4 text-center">สถานะ</th>
                            <th class="p-4 text-right">ดำเนินการ (Action)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAE5D9]">
                        @forelse ($users as $idx => $u)
                            <tr class="hover:bg-[#FAF8F2]/80 transition">
                                <td class="p-4 text-center font-mono text-[#8C8275]">{{ $idx + 1 }}</td>
                                <td class="p-4">
                                    <div class="font-mono font-bold text-[#2C3E2D] text-sm flex items-center gap-1.5">
                                        <i data-lucide="user" class="w-3.5 h-3.5 text-[#5A6B47]"></i>
                                        <span>{{ $u->username }}</span>
                                    </div>
                                    <div class="text-[11px] text-[#8C8275] font-mono mt-0.5">{{ $u->email ?: '-' }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-heading font-semibold text-[#2C3E2D] text-sm">{{ $u->full_name }}</div>
                                    <div class="text-[10px] text-[#7B8D65] mt-0.5">
                                        สร้างเมื่อ: {{ \Carbon\Carbon::parse($u->created_at)->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    @if ($u->role === 'SUPER_ADMIN')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#2C3E2D] text-white border border-[#2C3E2D] inline-flex items-center gap-1">
                                            <i data-lucide="shield-check" class="w-3 h-3 text-[#A3B88C]"></i> ผู้ดูแลระบบส่วนกลาง
                                        </span>
                                    @elseif ($u->role === 'CENTRAL_OFFICER')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#5A6B47]/15 text-[#5A6B47] border border-[#5A6B47]/30 inline-flex items-center gap-1">
                                            <i data-lucide="check" class="w-3 h-3 text-[#5A6B47]"></i> เจ้าหน้าที่ส่วนกลาง
                                        </span>
                                    @elseif ($u->role === 'EXECUTIVE')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-900 border border-amber-300 inline-flex items-center gap-1">
                                            <i data-lucide="pie-chart" class="w-3 h-3 text-amber-700"></i> ผู้บริหาร (Executive)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#EAE5D9] text-[#4A3B32] border border-[#D5CEBC] inline-flex items-center gap-1">
                                            <i data-lucide="building" class="w-3 h-3 text-[#7B8D65]"></i> เจ้าหน้าที่วิทยาเขต
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="text-[#2C3E2D] font-medium">
                                        @if ($u->role === 'SUPER_ADMIN' || $u->role === 'CENTRAL_OFFICER' || $u->role === 'EXECUTIVE')
                                            <span class="text-[#5A6B47] font-semibold">ทั่วประเทศ (45 สังกัด)</span>
                                        @else
                                            {{ $u->organizationUnit->name_th ?? 'ส่วนกลาง มจร' }}
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-[#8C8275] font-mono">
                                        {{ $u->organizationUnit->code_provincial ?? $u->organizationUnit->code ?? 'CENTRAL' }}
                                    </div>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if ($u->is_active)
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#E9EFE2] text-[#3D523E] border border-[#CADBC0] inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> ปกติ
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-red-100 text-red-800 border border-red-200 inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> ระงับใช้งาน
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                    <!-- 1. Toggle Active (วางไว้หน้าสุด) -->
                                    @if ($u->id !== session('admin_user.id'))
                                        <a href="{{ url('/admin/users.php?action=toggle_status&id=' . $u->id) }}" 
                                            onclick="return confirm('ยืนยันการเปลี่ยนแปลงสถานะใช้งานของผู้ใช้ท่านนี้?')"
                                            title="{{ $u->is_active ? 'กดเพื่อระงับการใช้งาน' : 'กดเพื่อเปิดใช้งาน' }}" 
                                            class="p-1.5 bg-[#FAF8F2] hover:bg-[#EAE5D9] text-[#4A3B32] rounded-lg border border-[#EAE5D9] transition inline-flex items-center">
                                            <i data-lucide="{{ $u->is_active ? 'pause-circle' : 'play-circle' }}" class="w-3.5 h-3.5 text-[#C86D51]"></i>
                                        </a>
                                    @endif

                                    <!-- 2. Edit User Button -->
                                    <button type="button" onclick="openEditUserModal({
                                         id: {{ $u->id }},
                                         username: @js($u->username),
                                         full_name: @js($u->full_name),
                                         email: @js($u->email ?? ''),
                                         role: @js($u->role),
                                         org_unit_id: {{ $u->org_unit_id ?? 1 }},
                                         is_active: {{ $u->is_active ? 1 : 0 }}
                                     })" title="แก้ไขข้อมูลผู้ใช้" class="p-1.5 bg-[#FAF8F2] hover:bg-[#5A6B47]/15 text-[#5A6B47] rounded-lg border border-[#EAE5D9] transition inline-flex items-center">
                                         <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                     </button>

                                    <!-- 3. Delete User -->
                                    @if ($u->id !== session('admin_user.id') && session('admin_user.role') === 'SUPER_ADMIN')
                                        <a href="{{ url('/admin/users.php?action=delete&id=' . $u->id) }}" 
                                            onclick="return confirm('ยืนยันลบบัญชีผู้ใช้งานนี้ถาวรหรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้')" 
                                            title="ลบบัญชีผู้ใช้" 
                                            class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg border border-red-200 transition inline-flex items-center">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-[#8C8275]">
                                    <i data-lucide="user-x" class="w-8 h-8 mx-auto mb-2 text-[#C86D51]"></i>
                                    <div class="font-medium text-sm text-[#2C3E2D]">ไม่พบข้อมูลผู้ใช้งานตามเงื่อนไขที่ค้นหา</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="p-4 border-t border-[#EAE5D9] bg-[#FAF8F2]/60">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- Modal เพิ่มผู้ใช้งานใหม่ -->
    <div id="createUserModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white border border-[#D5CEBC] rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden animate-fade-in">
            <div class="p-6 bg-[#FAF8F2] border-b border-[#EAE5D9] flex justify-between items-center">
                <h3 class="font-heading font-bold text-lg text-[#2C3E2D] flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-5 h-5 text-[#5A6B47]"></i>
                    <span>เพิ่มบัญชีผู้ใช้งานใหม่</span>
                </h3>
                <button type="button" onclick="closeCreateUserModal()" class="text-[#8C8275] hover:text-[#2C3E2D] p-1 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/users.php') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="action" value="store">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                            ชื่อผู้ใช้ (Username) <span class="text-[#C86D51]">*</span>
                        </label>
                        <input type="text" name="username" required placeholder="เช่น officer_cmi" class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                            รหัสผ่าน (Password) <span class="text-[#C86D51]">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="create_password" name="password" required minlength="6" placeholder="ขั้นต่ำ 6 ตัวอักษร" class="w-full pl-3.5 pr-10 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-mono">
                            <button type="button" onclick="togglePasswordVisibility('create_password', 'create_pass_icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#8C8275] hover:text-[#2C3E2D]" title="ดูรหัสผ่าน">
                                <i id="create_pass_icon" data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                        ชื่อ-นามสกุล / ฉายา <span class="text-[#C86D51]">*</span>
                    </label>
                    <input type="text" name="full_name" required placeholder="เช่น พระมหาธีระ ปญฺญาวชิโร" class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1">อีเมลติดต่อ (Email)</label>
                    <input type="email" name="email" placeholder="officer@mcu.ac.th" class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                            บทบาทและสิทธิ์ (Role Matrix) <span class="text-[#C86D51]">*</span>
                        </label>
                        <select name="role" id="create_role" onchange="toggleOrgUnitField('create_role', 'create_org_box')" required class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-medium">
                            <option value="CAMPUS_ADMIN">เจ้าหน้าที่วิทยาเขต (Campus Admin)</option>
                            <option value="CENTRAL_OFFICER">เจ้าหน้าที่ส่วนกลาง (Central Officer)</option>
                            <option value="SUPER_ADMIN">ผู้ดูแลระบบส่วนกลาง (Super Admin)</option>
                            <option value="EXECUTIVE">ผู้บริหาร (Executive - View Only)</option>
                        </select>
                    </div>

                    <div id="create_org_box">
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                            สังกัดส่วนงาน ({{ count($orgUnits) }} สังกัด)
                        </label>
                        <select name="org_unit_id" class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-medium">
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}">
                                    [{{ $org->code_provincial ?: $org->code }}] {{ $org->name_th }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-3">
                    <button type="button" onclick="closeCreateUserModal()" class="px-4 py-2 border border-[#D5CEBC] rounded-xl text-xs font-medium text-[#4A3B32] hover:bg-[#FAF8F2]">ยกเลิก</button>
                    <button type="submit" class="bg-[#5A6B47] hover:bg-[#2C3E2D] text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i> บันทึกผู้ใช้งาน
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal แก้ไขข้อมูลผู้ใช้งาน -->
    <div id="editUserModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white border border-[#D5CEBC] rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden animate-fade-in">
            <div class="p-6 bg-[#FAF8F2] border-b border-[#EAE5D9] flex justify-between items-center">
                <h3 class="font-heading font-bold text-lg text-[#2C3E2D] flex items-center gap-2">
                    <i data-lucide="edit" class="w-5 h-5 text-[#5A6B47]"></i>
                    <span>แก้ไขข้อมูลและกำหนดสิทธิ์ผู้ใช้</span>
                </h3>
                <button type="button" onclick="closeEditUserModal()" class="text-[#8C8275] hover:text-[#2C3E2D] p-1 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form id="editUserForm" method="POST" action="{{ url('/admin/users.php') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_user_id">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                            ชื่อผู้ใช้ (Username) <span class="text-[#C86D51]">*</span>
                        </label>
                        <input type="text" name="username" id="edit_username" required class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs font-mono focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                            เปลี่ยนรหัสผ่านใหม่ (ว่างไว้ถ้าไม่เปลี่ยน)
                        </label>
                        <div class="relative">
                            <input type="password" id="edit_password" name="password" minlength="6" placeholder="ปล่อยว่างหากไม่ต้องการเปลี่ยน" class="w-full pl-3.5 pr-10 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-mono">
                            <button type="button" onclick="togglePasswordVisibility('edit_password', 'edit_pass_icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#8C8275] hover:text-[#2C3E2D]" title="ดูรหัสผ่าน">
                                <i id="edit_pass_icon" data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                        ชื่อ-นามสกุล / ฉายา <span class="text-[#C86D51]">*</span>
                    </label>
                    <input type="text" name="full_name" id="edit_full_name" required class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#4A3B32] mb-1">อีเมลติดต่อ (Email)</label>
                    <input type="email" name="email" id="edit_email" class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                            บทบาทและสิทธิ์ (Role Matrix) <span class="text-[#C86D51]">*</span>
                        </label>
                        <select name="role" id="edit_role" onchange="toggleOrgUnitField('edit_role', 'edit_org_box')" required class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-medium">
                            <option value="CAMPUS_ADMIN">เจ้าหน้าที่วิทยาเขต (Campus Admin)</option>
                            <option value="CENTRAL_OFFICER">เจ้าหน้าที่ส่วนกลาง (Central Officer)</option>
                            <option value="SUPER_ADMIN">ผู้ดูแลระบบส่วนกลาง (Super Admin)</option>
                            <option value="EXECUTIVE">ผู้บริหาร (Executive - View Only)</option>
                        </select>
                    </div>

                    <div id="edit_org_box">
                        <label class="block text-xs font-semibold text-[#4A3B32] mb-1">
                            สังกัดส่วนงาน ({{ count($orgUnits) }} สังกัด)
                        </label>
                        <select name="org_unit_id" id="edit_org_unit_id" class="w-full px-3.5 py-2 bg-[#FAF8F2] border border-[#D5CEBC] rounded-xl text-xs focus:ring-2 focus:ring-[#5A6B47] focus:outline-none font-medium">
                            @foreach ($orgUnits as $org)
                                <option value="{{ $org->id }}">
                                    [{{ $org->code_provincial ?: $org->code }}] {{ $org->name_th }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded text-[#5A6B47] focus:ring-[#5A6B47]">
                        <span class="text-xs font-semibold text-[#2C3E2D]">เปิดใช้งานบัญชีนี้ (Active Status)</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-[#EAE5D9] flex justify-end gap-3">
                    <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 border border-[#D5CEBC] rounded-xl text-xs font-medium text-[#4A3B32] hover:bg-[#FAF8F2]">ยกเลิก</button>
                    <button type="submit" class="bg-[#2C3E2D] hover:bg-[#3D523E] text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-1.5">
                        <i data-lucide="save" class="w-4 h-4"></i> อัปเดตข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateUserModal() {
            document.getElementById('createUserModal').classList.remove('hidden');
        }
        function closeCreateUserModal() {
            document.getElementById('createUserModal').classList.add('hidden');
        }

        function openEditUserModal(user) {
            document.getElementById('editUserForm').action = "{{ url('/admin/users.php') }}";
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_full_name').value = user.full_name;
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_role').value = user.role;
            document.getElementById('edit_org_unit_id').value = user.org_unit_id;
            document.getElementById('edit_is_active').checked = (user.is_active == 1);
            
            toggleOrgUnitField('edit_role', 'edit_org_box');
            document.getElementById('editUserModal').classList.remove('hidden');
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }

        function toggleOrgUnitField(roleSelectId, orgBoxId) {
            const role = document.getElementById(roleSelectId).value;
            const orgBox = document.getElementById(orgBoxId);
            if (role === 'CAMPUS_ADMIN') {
                orgBox.style.display = 'block';
            } else {
                orgBox.style.display = 'none';
            }
        }

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.setAttribute('data-lucide', 'eye-off');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.setAttribute('data-lucide', 'eye');
                }
            }
            lucide.createIcons();
        }

        // Initialize Lucide Icons
        lucide.createIcons();
    </script>
</body>
</html>
