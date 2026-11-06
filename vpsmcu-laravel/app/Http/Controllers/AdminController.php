<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUnit;
use App\Models\UgBatch;
use App\Models\UgRegistration;
use App\Models\UgMasterStudent;
use App\Models\GradStudent;
use App\Models\PublicRegistration;
use App\Models\PublicEvent;
use App\Models\NewsArticle;
use App\Models\Announcement;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\ContactInquiry;
use App\Models\Donation;
use App\Models\Student;
use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use App\Services\TranslationService;
use App\Services\PdfTextExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (Session::has('admin_user')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = DB::table('users')->where('username', $username)->first();

        if ($user && password_verify($password, $user->password_hash)) {
            Session::put('admin_user', [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->full_name,
                'role' => $user->role,
                'org_unit_id' => $user->org_unit_id,
            ]);

            // บันทึก Log การเข้าสู่ระบบสำเร็จ
            ActivityLogger::log('LOGIN', 'AUTH', "เข้าสู่ระบบสำเร็จ (ผู้ใช้: {$user->username}, สิทธิ์: {$user->role})", null, $user->id, $user->org_unit_id);

            return redirect()->route('admin.dashboard');
        }

        // บันทึก Log การเข้าสู่ระบบล้มเหลว
        ActivityLogger::log('LOGIN_FAILED', 'AUTH', "พยายามเข้าสู่ระบบแต่รหัสผ่านไม่ถูกต้อง (Username: {$username})", ['attempted_username' => $username]);

        return back()->with('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    }

    public function logout()
    {
        $admin = Session::get('admin_user');
        if ($admin) {
            ActivityLogger::log('LOGOUT', 'AUTH', "ออกจากระบบ (ผู้ใช้: {$admin['username']})");
        }
        Session::forget('admin_user');
        return redirect()->route('login');
    }

    public function dashboard()
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');

        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $totalOrgs = OrganizationUnit::where('is_active', 1)->count();
        
        $totalUGQuery = UgRegistration::query();
        $totalGradQuery = GradStudent::query();
        $totalPublicQuery = PublicRegistration::query();

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $totalUGQuery->where('org_unit_id', $admin['org_unit_id']);
            $totalGradQuery->where('org_unit_id', $admin['org_unit_id']);
        }

        $totalUG = $totalUGQuery->count();
        $totalGrad = $totalGradQuery->count();
        $totalPublic = $totalPublicQuery->count();

        // สถิติจำนวนผู้เข้าร่วมปฏิบัติวิปัสสนากรรมฐานแยกตามหน่วยงาน/ส่วนงาน (คำนวณแบบ Batch Query ป้องกัน N+1)
        $ugTotals = UgRegistration::select('org_unit_id', DB::raw('count(*) as count'))
            ->groupBy('org_unit_id')->pluck('count', 'org_unit_id');

        $gradTotals = GradStudent::select('org_unit_id', DB::raw('count(*) as count'))
            ->groupBy('org_unit_id')->pluck('count', 'org_unit_id');

        $publicTotals = PublicRegistration::join('public_events', 'public_registrations.event_id', '=', 'public_events.id')
            ->select('public_events.org_unit_id', DB::raw('count(*) as count'))
            ->groupBy('public_events.org_unit_id')->pluck('count', 'public_events.org_unit_id');

        $campusChartData = OrganizationUnit::where('is_active', 1)
            ->get()
            ->map(function ($org) use ($ugTotals, $gradTotals, $publicTotals) {
                $ug = $ugTotals->get($org->id, 0);
                $grad = $gradTotals->get($org->id, 0);
                $public = $publicTotals->get($org->id, 0);
                $total = $ug + $grad + $public;

                return [
                    'id' => $org->id,
                    'name' => $org->name_th,
                    'short_name' => str_replace(['วิทยาเขต', 'วิทยาลัยสงฆ์'], ['วข.', 'วส.'], $org->name_th),
                    'code' => $org->province_code ?? $org->code,
                    'type' => $org->type,
                    'ug' => $ug,
                    'grad' => $grad,
                    'public' => $public,
                    'total' => $total,
                ];
            })
            ->filter(function ($item) {
                return $item['total'] > 0;
            })
            ->sortByDesc('total')
            ->values();

        return view('admin.dashboard', compact('totalOrgs', 'totalUG', 'totalGrad', 'totalPublic', 'campusChartData'));
    }

    // Module 1: จัดการรอบผลัด/ปฏิทินปฏิบัติธรรม (Batches & Schedules)
    public function ugBatches(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        // Action Dispatcher for Shared Hosting Compatibility (via /admin/ug_batches.php?action=... or POST)
        $action = $request->input('action');
        if ($action === 'store') {
            return $this->ugBatchStore($request);
        }
        if ($action === 'update') {
            return $this->ugBatchUpdate($request, $request->input('id'));
        }
        if ($action === 'status') {
            return $this->ugBatchStatus($request->input('id'), $request->input('status_val'));
        }
        if ($action === 'delete') {
            return $this->ugBatchDelete($request->input('id'));
        }

        $query = UgBatch::with(['organizationUnit', 'registrations'])->orderBy('start_date', 'desc');

        // สิทธิ์การเข้าถึง: ถ้าเป็นเจ้าหน้าที่วิทยาเขต (CAMPUS_ADMIN) จะเห็นเฉพาะรอบของวิทยาเขตตนเอง
        // ถ้าเป็นเจ้าหน้าที่ส่วนกลาง (CENTRAL_OFFICER) หรือ Super Admin จะตรวจสอบได้ครบทุก 52 ส่วนงาน
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        } elseif ($request->filled('filter_org')) {
            $query->where('org_unit_id', $request->input('filter_org'));
        }

        $perPage = $this->getPerPage($request);
        $batches = $query->paginate($perPage)->withQueryString();
        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('admin.ug_batches', compact('batches', 'orgUnits', 'isCentralOrSuper'));
    }

    public function ugBatchStore(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $validated = $request->validate([
            'academic_year' => 'required|integer',
            'batch_no' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_quota' => 'required|integer|min:1',
            'status' => 'required|in:OPEN,CLOSED,IN_PROGRESS,COMPLETED',
            'org_unit_id' => 'nullable|integer',
            'cover_image' => 'nullable|string|max:500',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ]);

        // จัดการอัปโหลดไฟล์ภาพปกโครงการ
        $coverImageUrl = $validated['cover_image'] ?? null;
        if ($request->hasFile('cover_file') && $request->file('cover_file')->isValid()) {
            $path = $request->file('cover_file')->store('batches', 'public');
            $coverImageUrl = Storage::url($path);
        }

        // กำหนดส่วนงานเจ้าของโครงการ:
        // หากเป็น CAMPUS_ADMIN จะล็อกให้อยู่ใน org_unit_id ของตนเองเสมอ
        $org_id = $isCentralOrSuper && !empty($validated['org_unit_id'])
            ? $validated['org_unit_id']
            : ($admin['org_unit_id'] ?? 1);

        // Auto-translate to English
        $titleEn = TranslationService::translateToEnglish($validated['title']);
        $locationEn = TranslationService::translateToEnglish($validated['location']);

        UgBatch::create([
            'org_unit_id' => $org_id,
            'academic_year' => $validated['academic_year'],
            'batch_no' => $validated['batch_no'] ?? 1,
            'title' => $validated['title'],
            'title_en' => $titleEn,
            'location' => $validated['location'],
            'location_en' => $locationEn,
            'cover_image' => $coverImageUrl,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'max_quota' => $validated['max_quota'],
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'บันทึกกำหนดการปฏิบัติวิปัสสนากรรมฐาน ประจำปีการศึกษา ' . $validated['academic_year'] . ' สำเร็จเรียบร้อยแล้ว (พร้อมแปลภาษาอังกฤษอัตโนมัติ)');
    }

    public function ugBatchUpdate(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $batch = UgBatch::findOrFail($id);

        // ตรวจสอบสิทธิ์: อนุญาตเฉพาะ Super Admin, เจ้าหน้าที่ส่วนกลาง (Central Officer) หรือ เจ้าหน้าที่วิทยาเขตที่เป็นเจ้าของส่วนงานโครงการ
        if (!$isCentralOrSuper && $batch->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์แก้ไขข้อมูลโครงการของส่วนงานอื่น');
        }

        $validated = $request->validate([
            'academic_year' => 'required|integer',
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_quota' => 'required|integer|min:1',
            'status' => 'required|in:OPEN,CLOSED,IN_PROGRESS,COMPLETED',
            'org_unit_id' => 'nullable|integer',
            'cover_image' => 'nullable|string|max:500',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ]);

        $coverImageUrl = $validated['cover_image'] ?? $batch->cover_image;
        if ($request->hasFile('cover_file') && $request->file('cover_file')->isValid()) {
            $path = $request->file('cover_file')->store('batches', 'public');
            $coverImageUrl = Storage::url($path);
        }

        $titleEn = ($batch->title !== $validated['title'] || empty($batch->title_en))
            ? TranslationService::translateToEnglish($validated['title'])
            : $batch->title_en;

        $locationEn = ($batch->location !== $validated['location'] || empty($batch->location_en))
            ? TranslationService::translateToEnglish($validated['location'])
            : $batch->location_en;

        $updateData = [
            'academic_year' => $validated['academic_year'],
            'title' => $validated['title'],
            'title_en' => $titleEn,
            'location' => $validated['location'],
            'location_en' => $locationEn,
            'cover_image' => $coverImageUrl,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'max_quota' => $validated['max_quota'],
            'status' => $validated['status'],
        ];

        // อนุญาตให้แก้ไขส่วนงานเจ้าของโครงการเฉพาะ Central / Super Admin
        if ($isCentralOrSuper && !empty($validated['org_unit_id'])) {
            $updateData['org_unit_id'] = $validated['org_unit_id'];
        }

        $batch->update($updateData);

        return back()->with('success', 'แก้ไขข้อมูลกำหนดการโครงการ ' . $batch->title . ' สำเร็จเรียบร้อยแล้ว');
    }

    public function ugBatchStatus($id, $status)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $batch = UgBatch::findOrFail($id);

        // Security Scope Check: ตรวจสอบสิทธิ์เฉพาะส่วนงาน
        if (!$isCentralOrSuper && $batch->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์แก้ไขข้อมูลของส่วนงานอื่น');
        }

        if (in_array($status, ['OPEN', 'CLOSED', 'IN_PROGRESS', 'COMPLETED'])) {
            $batch->update(['status' => $status]);
            return back()->with('success', 'ปรับเปลี่ยนสถานะโครงการเป็น ' . $status . ' สำเร็จ');
        }

        return back();
    }

    public function ugBatchDelete($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $batch = UgBatch::findOrFail($id);

        if (!$isCentralOrSuper && $batch->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ลบข้อมูลของส่วนงานอื่น');
        }

        $batch->delete();
        return back()->with('success', 'ลบกำหนดการโครงการเรียบร้อยแล้ว');
    }

    public function ugImport(Request $request)
    {
        $this->checkAuth();
        if ($request->input('action') === 'template' || $request->has('template')) {
            return $this->ugDownloadTemplate();
        }
        if ($request->isMethod('post')) {
            return $this->ugImportCsv($request);
        }

        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $query = UgMasterStudent::with('organizationUnit')->orderBy('id', 'desc');

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        } elseif ($request->filled('filter_org')) {
            $query->where('org_unit_id', $request->input('filter_org'));
        }

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('student_code', 'LIKE', "%{$s}%")
                  ->orWhere('first_name', 'LIKE', "%{$s}%")
                  ->orWhere('last_name', 'LIKE', "%{$s}%")
                  ->orWhere('chaya', 'LIKE', "%{$s}%")
                  ->orWhere('citizen_id', 'LIKE', "%{$s}%");
            });
        }

        $perPage = $this->getPerPage($request);
        $students = $query->paginate($perPage)->withQueryString();
        $orgUnits = OrganizationUnit::orderedForSelect()->get();
        $totalMasterCount = UgMasterStudent::count();

        return view('admin.ug_import', compact('students', 'orgUnits', 'totalMasterCount', 'isCentralOrSuper'));
    }

    public function ugImportCsv(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        // เพิ่มการตั้งค่าเวลาและหน่วยความจำรองรับไฟล์ใหญ่
        @ini_set('max_execution_time', '600');
        @ini_set('memory_limit', '1024M');

        $isGoogleSheets = $request->filled('sheets_url');

        if ($isGoogleSheets) {
            $request->validate([
                'sheets_url' => 'required|url',
            ], [
                'sheets_url.required' => 'กรุณาระบุลิงก์ Google Sheets',
                'sheets_url.url' => 'รูปแบบลิงก์ Google Sheets ไม่ถูกต้อง',
            ]);

            $sheetsUrl = trim($request->input('sheets_url'));
            if (!preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $sheetsUrl, $matches)) {
                return back()->with('error', 'รูปแบบลิงก์ Google Sheets ไม่ถูกต้อง กรุณาคัดลอกลิงก์แชร์ของ Google Sheets ให้ถูกต้อง (เช่น https://docs.google.com/spreadsheets/d/.../edit?usp=sharing)');
            }
            $sheetId = $matches[1];
            $gidParam = '';
            if (preg_match('/[#&?]gid=([0-9]+)/', $sheetsUrl, $gidMatches)) {
                $gidParam = '&gid=' . $gidMatches[1];
            }
            $exportUrl = "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv{$gidParam}";

            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 45,
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n",
                    'follow_location' => 1,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);
            $csvContent = @file_get_contents($exportUrl, false, $ctx);
            if ($csvContent === false || empty($csvContent)) {
                return back()->with('error', 'ไม่สามารถดึงข้อมูลจาก Google Sheets ได้ กรุณาตรวจสอบว่าได้ตั้งค่าการแชร์ใน Google Sheets เป็น "ทุกคนที่มีลิงก์มีสิทธิ์อ่าน" (Anyone with the link can view) เรียบร้อยแล้ว');
            }

            $path = tempnam(sys_get_temp_dir(), 'vpsmcu_gsheet_');
            file_put_contents($path, $csvContent);
            $isTempFile = true;
            $importSource = 'Google Sheets (' . substr($sheetId, 0, 8) . '...)';
        } else {
            $request->validate([
                'csv_file' => 'required|file|max:20480',
            ], [
                'csv_file.required' => 'กรุณาเลือกไฟล์ CSV ที่ต้องการนำเข้า หรือใช้วิธีวางลิงก์ Google Sheets',
                'csv_file.max' => 'ขนาดไฟล์ต้องไม่เกิน 20 MB',
            ]);

            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            $isTempFile = false;
            $importSource = 'ไฟล์ CSV (' . $file->getClientOriginalName() . ')';
        }

        // โหลด map รายชื่อส่วนงานเพื่อใช้ค้นหาตามชื่อส่วนงานใน CSV
        $orgs = OrganizationUnit::all();
        $orgMap = [];
        foreach ($orgs as $org) {
            $orgMap[mb_strtolower(trim($org->name_th))] = $org->id;
            if ($org->code) {
                $orgMap[mb_strtolower(trim($org->code))] = $org->id;
            }
            if ($org->code_provincial) {
                $orgMap[mb_strtolower(trim($org->code_provincial))] = $org->id;
            }
        }

        $defaultOrgId = ($admin['org_unit_id'] ?? 1) ?: 1;

        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'ไม่สามารถเปิดไฟล์ CSV ได้');
        }

        // ตรวจสอบและตัด BOM (UTF-8 BOM)
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // ตรวจจับตัวคั่นอัตโนมัติ (Delimiter Auto-Detection: Comma, Tab, Semicolon)
        $sampleLine = fgets($handle);
        $delimiter = ',';
        if ($sampleLine !== false) {
            $tabCount = substr_count($sampleLine, "\t");
            $commaCount = substr_count($sampleLine, ",");
            $semiCount = substr_count($sampleLine, ";");
            if ($tabCount > $commaCount && $tabCount > $semiCount) {
                $delimiter = "\t";
            } elseif ($semiCount > $commaCount) {
                $delimiter = ";";
            }
        }
        rewind($handle);
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $startTime = microtime(true);
        $totalProcessed = 0;
        $skipped = 0;
        $rowNumber = 0;

        $ugBatch = [];
        $studentBatch = [];
        $chunkSize = 1000;
        $defaultPassword = Hash::make('password'); // สร้างแฮชรหัสผ่านครั้งเดียวนอกลูป เพื่อประสิทธิภาพสูงสุด

        // รายชื่อส่วนงานและชื่อเรียกอื่นเพื่อความเข้ากันได้กับการนำเข้าข้อมูล
        $specialAliases = [
            'วิทยาเขตนครน่าน เฉลิมพระเกียรติ' => 19, // วิทยาเขตนครน่าน เฉลิมพระเกียรติฯ
            'วิทยาเขตนครน่าน เฉลิมพระเกียรติฯ' => 19,
            'วิทยาลัยสงฆ์ร้อยเอ็ด' => 34, // วิทยาเขตร้อยเอ็ด
            'วิทยาเขตร้อยเอ็ด' => 34,
            'วิทยาลัยสงฆ์พ่อขุนผาเมือง' => 36,
            'วิทยาลัยสงฆ์พ่อขุนผาเมือง เพชรบูรณ์' => 36,
            'วิทยาลัยสงฆ์สงขลา' => 49,
            'หน่วยวิทยบริการ วิทยาลัยสงฆ์นครศรีธรรมราช จังหวัดสงขลา' => 49,
            'หน่วยวิทยบริการ จังหวัดอุตรดิตถ์' => 50,
            'หน่วยวิทยบริการจังหวัดอุตรดิตถ์' => 50,
            'หน่วยวิทยบริการวัดหมอนไม้' => 50,
            'หน่วยวิทยบริการ จังหวัดกาฬสินธุ์' => 51,
            'หน่วยวิทยบริการจังหวัดกาฬสินธุ์' => 51,
            'หน่วยวิทยบริการ จังหวัดสมุทรสงคราม' => 52,
            'หน่วยวิทยบริการจังหวัดสมุทรสงคราม' => 52,
            'หน่วยวิทยบริการวัดอินทาราม จ.สมุทรสงคราม' => 52,
        ];

        while (($row = fgetcsv($handle, 4096, $delimiter, '"', "\\")) !== FALSE) {
            $rowNumber++;

            // 1. ตัด comma/tab และช่องว่างส่วนเกินที่ติดมาตอนท้ายแถว (Strip trailing empty delimiters ,,,,,)
            while (!empty($row) && trim((string)end($row)) === '') {
                array_pop($row);
            }

            // ถ้าเป็นแถวว่างเปล่า ให้ข้ามทันที
            if (empty($row)) {
                $skipped++;
                continue;
            }

            // 2. ทำความสะอาดทุก cell ในแถว (Trim whitespace, leading/trailing commas, quotes)
            $cleanRow = [];
            foreach ($row as $cell) {
                $c = trim((string)$cell);
                // ตัด quotes และ whitespace
                $c = trim($c, "\"' \t\n\r\0\x0B");
                $cleanRow[] = $c;
            }

            // 3. ข้าม Header ถ้าแถวแรกมีคำว่า "รหัสนิสิต" หรือ "student"
            if ($rowNumber === 1 && (str_contains($cleanRow[0] ?? '', 'รหัส') || str_contains(strtolower($cleanRow[0] ?? ''), 'student'))) {
                continue;
            }

            // 4. ดึงและทำความสะอาดรหัสนิสิต (student_code)
            $studentCode = trim($cleanRow[0] ?? '', " \t\n\r\0\x0B,");
            $studentCode = str_replace([',', '"', "'", ' '], '', $studentCode);

            if (empty($studentCode)) {
                $skipped++;
                continue;
            }

            // 5. วิเคราะห์โครงสร้างคอลัมน์ (รองรับทั้งแบบ 6 คอลัมน์จริง และ 8-9 คอลัมน์เดิม)
            // โครงสร้าง 6 คอลัมน์จริง: [0:รหัสนิสิต, 1:ชื่อ - ฉายา/นามสกุล, 2:ระดับ, 3:สังกัด, 4:คณะ, 5:สาขาวิชา]
            if (count($cleanRow) <= 7) {
                $name = $cleanRow[1] ?? '';
                $degreeLevelRaw = $cleanRow[2] ?? 'ปริญญาตรี';
                $affiliation = $cleanRow[3] ?? '';
                $faculty = $cleanRow[4] ?? '';
                $major = $cleanRow[5] ?? '';

                $prefix = '';
                $firstName = $name;
                $lastName = '';
                $chaya = '';
            } else {
                // โครงสร้าง 8-9 คอลัมน์เดิม: [0:รหัส, 1:คำนำหน้า, 2:ชื่อ, 3:นามสกุล, 4:ฉายา, 5:ระดับ, 6:สาขา, 7:คณะ, 8:สังกัด]
                $prefix = $cleanRow[1] ?? '';
                $firstName = $cleanRow[2] ?? '';
                $lastName = $cleanRow[3] ?? '';
                $chaya = $cleanRow[4] ?? '';
                $degreeLevelRaw = $cleanRow[5] ?? 'ปริญญาตรี';
                $major = $cleanRow[6] ?? '';
                $faculty = $cleanRow[7] ?? '';
                $affiliation = $cleanRow[8] ?? '';

                $name = trim("{$prefix}{$firstName} {$chaya} {$lastName}");
            }

            if (empty($name)) {
                $name = 'นิสิต มจร';
            }

            // แปลงระดับการศึกษา
            $degreeLevelTh = $degreeLevelRaw ?: 'ปริญญาตรี';
            $degreeLevelEnum = 'BACHELOR';
            if (str_contains($degreeLevelTh, 'โท') || str_contains(strtoupper($degreeLevelTh), 'MASTER')) {
                $degreeLevelEnum = 'MASTER';
            } elseif (str_contains($degreeLevelTh, 'เอก') || str_contains(strtoupper($degreeLevelTh), 'DOCTOR')) {
                $degreeLevelEnum = 'DOCTORAL';
            } elseif (str_contains($degreeLevelTh, 'ประกาศนียบัตร') || str_contains($degreeLevelTh, 'สัมฤทธิบัตร') || str_contains(strtoupper($degreeLevelTh), 'CERT') || str_contains(strtoupper($degreeLevelTh), 'DIP')) {
                $degreeLevelEnum = 'CERTIFICATE';
            }

            // 6. ตรวจสอบและแมปส่วนจัดการศึกษา (Affiliation & Faculty Mapping)
            $orgUnitId = $defaultOrgId;
            $cleanAff = trim($affiliation);
            $cleanFac = trim($faculty);

            // ตรวจสอบว่าเป็นส่วนกลาง มจร พระนครศรีอยุธยา หรือไม่
            $isCentralAyutthaya = false;
            foreach (['พระนครศรีอยุธยา', 'อยุธยา', 'วังน้อย', 'ส่วนกลาง'] as $kw) {
                if (str_contains($cleanAff, $kw)) {
                    $isCentralAyutthaya = true;
                    break;
                }
            }

            if ($isCentralAyutthaya) {
                $orgUnitId = 1; // มจร พระนครศรีอยุธยา (ส่วนกลาง)
            } else {
                // วิทยาเขต / วิทยาลัยสงฆ์ / หน่วยวิทยบริการ (44 แห่งทั่วประเทศ)
                if (isset($orgMap[mb_strtolower($cleanAff)])) {
                    $orgUnitId = $orgMap[mb_strtolower($cleanAff)];
                } elseif (isset($specialAliases[$cleanAff])) {
                    $orgUnitId = $specialAliases[$cleanAff];
                } elseif (!empty($cleanAff)) {
                    $strippedAff = str_replace([
                        'มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย', 'วิทยาเขต', 'วิทยาลัยสงฆ์',
                        'หน่วยวิทยบริการ', 'จังหวัด', 'จ.', ' '
                    ], '', $cleanAff);

                    $foundOrg = false;
                    foreach ($orgs as $o) {
                        if (in_array($o->type, ['CAMPUS', 'SANGHA_COLLEGE', 'ACADEMIC_UNIT'])) {
                            $strippedOrg = str_replace([
                                'วิทยาเขต', 'วิทยาลัยสงฆ์', 'หน่วยวิทยบริการ', 'จังหวัด', ' '
                            ], '', $o->name_th);
                            if (!empty($strippedOrg) && !empty($strippedAff) && (str_contains($strippedAff, $strippedOrg) || str_contains($strippedOrg, $strippedAff))) {
                                $orgUnitId = $o->id;
                                $foundOrg = true;
                                break;
                            }
                        }
                    }

                    if (!$foundOrg && isset($orgMap[mb_strtolower($cleanAff)])) {
                        $orgUnitId = $orgMap[mb_strtolower($cleanAff)];
                    }
                }
            }

            // ถ้าเป็นเจ้าหน้าที่ประจำวิทยาเขต ล็อก org_unit_id ตามสิทธิ์
            if (!$isCentralOrSuper) {
                $orgUnitId = $admin['org_unit_id'];
            }

            $now = now();

            // จัดเตรียมข้อมูล UgMasterStudent
            $ugBatch[] = [
                'student_code' => $studentCode,
                'name' => $name,
                'prefix' => $prefix,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'chaya' => $chaya,
                'degree_level' => $degreeLevelTh,
                'major' => $major,
                'faculty' => $faculty,
                'org_unit_id' => $orgUnitId,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // จัดเตรียมข้อมูล Student (ฐานข้อมูลนิสิตส่วนกลาง มจร)
            $studentBatch[] = [
                'student_code' => $studentCode,
                'name' => $name,
                'prefix' => $prefix,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'chaya' => $chaya,
                'degree_level' => $degreeLevelEnum,
                'org_unit_id' => $orgUnitId,
                'faculty' => $faculty,
                'major' => $major,
                'password' => $defaultPassword,
                'nationality' => 'ไทย',
                'religion' => 'พุทธ',
                'force_password_change' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $totalProcessed++;

            // เมื่อสะสมครบ Chunk (1,000 แถว) ให้รัน Batch Upsert ทันที
            if (count($ugBatch) >= $chunkSize) {
                UgMasterStudent::upsert(
                    $ugBatch,
                    ['student_code'],
                    ['name', 'prefix', 'first_name', 'last_name', 'chaya', 'degree_level', 'major', 'faculty', 'org_unit_id', 'is_active', 'updated_at']
                );

                Student::upsert(
                    $studentBatch,
                    ['student_code'],
                    ['name', 'prefix', 'first_name', 'last_name', 'chaya', 'degree_level', 'org_unit_id', 'faculty', 'major', 'is_active', 'updated_at']
                );

                $ugBatch = [];
                $studentBatch = [];
            }
        }

        // นำเข้าข้อมูลชุดสุดท้ายที่เหลือ
        if (!empty($ugBatch)) {
            UgMasterStudent::upsert(
                $ugBatch,
                ['student_code'],
                ['name', 'prefix', 'first_name', 'last_name', 'chaya', 'degree_level', 'major', 'faculty', 'org_unit_id', 'is_active', 'updated_at']
            );

            Student::upsert(
                $studentBatch,
                ['student_code'],
                ['name', 'prefix', 'first_name', 'last_name', 'chaya', 'degree_level', 'org_unit_id', 'faculty', 'major', 'is_active', 'updated_at']
            );
        }

        fclose($handle);

        if (!empty($isTempFile) && file_exists($path)) {
            @unlink($path);
        }

        $duration = round(microtime(true) - $startTime, 2);

        ActivityLogger::log('IMPORT', 'STUDENTS', "นำเข้าข้อมูลนิสิตผ่าน {$importSource} สำเร็จ {$totalProcessed} รายการ (ใช้เวลา {$duration} วินาที, ข้าม {$skipped} แถว)", [
            'import_source' => $importSource,
            'total_processed' => $totalProcessed,
            'skipped' => $skipped,
            'duration_seconds' => $duration,
        ]);

        return back()->with('success', "นำเข้าข้อมูลนิสิตผ่าน {$importSource} สำเร็จ {$totalProcessed} รายการเรียบร้อยแล้ว! (ใช้เวลาประมวลผลเพียง {$duration} วินาที, ระบบตัด comma ท้ายแถวและข้ามแถวว่าง {$skipped} แถวอัตโนมัติ) พร้อมซิงค์เข้าฐานข้อมูลกลางและระบบ ป.ตรี สมบูรณ์");
    }

    public function ugDownloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="ug_student_template.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'รหัสนิสิต',
                'ชื่อ - ฉายา/นามสกุล',
                'ระดับการศึกษา',
                'สังกัด',
                'คณะ',
                'สาขาวิชา'
            ]);

            fputcsv($handle, [
                '6901632001',
                'Abishake Barua Bishal',
                'ประกาศนียบัตร',
                'มจร พระนครศรีอยุธยา',
                'มนุษยศาสตร์',
                'ภาษาไทยเพื่อการสื่อสารสากล'
            ]);

            fputcsv($handle, [
                '6601201001',
                'พระมหาเกียรติศักดิ์ กิตฺติสกฺโก',
                'ปริญญาตรี',
                'วิทยาเขตขอนแก่น',
                'พุทธศาสตร์',
                'พระพุทธศาสนา'
            ]);

            fputcsv($handle, [
                '6601201002',
                'นางสาวเพ็ญนารา โพธิรัตน์',
                'ปริญญาตรี',
                'วิทยาเขตเชียงใหม่',
                'สังคมศาสตร์',
                'รัฐศาสตร์'
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function ugStudents(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        // Action Dispatcher for Shared Hosting Compatibility (via /admin/ug_students.php?action=... or POST)
        $action = $request->input('action');
        if ($action === 'update') {
            return $this->ugStudentUpdate($request, $request->input('id'));
        }
        if ($action === 'approve') {
            return $this->ugStudentApprove($request->input('id'));
        }
        if ($action === 'reject') {
            return $this->ugStudentReject($request, $request->input('id'));
        }
        if ($action === 'checkin') {
            return $this->ugCheckin($request->input('id'));
        }
        if ($action === 'complete') {
            return $this->ugComplete($request->input('id'));
        }
        if ($action === 'delete') {
            return $this->ugStudentDelete($request->input('id'));
        }
        if ($action === 'bulk' || $request->has('bulk_action')) {
            return $this->ugStudentsBulkAction($request);
        }

        $query = UgRegistration::with(['batch', 'organizationUnit'])->orderBy('created_at', 'desc');

        // สิทธิ์การเข้าถึงข้อมูลรายชื่อนิสิต:
        // เจ้าหน้าที่วิทยาเขตจะเห็นเฉพาะนิสิตในวิทยาเขตของตนเอง
        // เจ้าหน้าที่ส่วนกลางและ Super Admin จะเห็นของทุกส่วนงานทั่วประเทศ
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        } elseif ($request->filled('filter_org')) {
            $query->where('org_unit_id', $request->input('filter_org'));
        }

        // ค้นหาตามรหัสนิสิต / เลขที่สมัคร / ชื่อ-นามสกุล / เบอร์โทร
        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('student_code', 'LIKE', "%{$s}%")
                  ->orWhere('registration_no', 'LIKE', "%{$s}%")
                  ->orWhere('first_name', 'LIKE', "%{$s}%")
                  ->orWhere('last_name', 'LIKE', "%{$s}%")
                  ->orWhere('full_name', 'LIKE', "%{$s}%")
                  ->orWhere('phone', 'LIKE', "%{$s}%");
            });
        }

        // กรองตามโครงการ / รอบปฏิบัติธรรม
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->input('batch_id'));
        }

        // กรองตามสถานะ
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = $this->getPerPage($request);
        $registrations = $query->paginate($perPage)->withQueryString();
        $orgUnits = OrganizationUnit::orderedForSelect()->get();
        $batches = UgBatch::where('status', '!=', 'COMPLETED')->orderBy('academic_year', 'desc')->orderBy('start_date', 'asc')->get();

        return view('admin.ug_students', compact('registrations', 'orgUnits', 'batches', 'isCentralOrSuper'));
    }

    public function ugStudentUpdate(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = UgRegistration::findOrFail($id);

        // ตรวจสอบสิทธิ์: Super Admin / Central Officer แก้ไขได้ทุกส่วนงาน หรือ เจ้าหน้าที่วิทยาเขตสังกัดเดียวกัน
        if (!$isCentralOrSuper && $reg->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์แก้ไขข้อมูลนิสิตของส่วนงานอื่น');
        }

        $validated = $request->validate([
            'student_code' => 'required|string|max:50',
            'citizen_id' => 'required|string|max:13',
            'prefix' => 'required|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'study_year' => 'required|integer|min:1|max:8',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'faculty' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:100',
            'batch_id' => 'required|integer',
            'status' => 'required|in:REGISTERED,PENDING,APPROVED,CHECKED_IN,COMPLETED,REJECTED',
            'org_unit_id' => 'nullable|integer',
        ]);

        $updateData = [
            'student_code' => $validated['student_code'],
            'citizen_id' => $validated['citizen_id'],
            'prefix' => $validated['prefix'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'full_name' => $validated['prefix'] . $validated['first_name'] . ' ' . $validated['last_name'],
            'study_year' => $validated['study_year'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'faculty' => $validated['faculty'],
            'major' => $validated['major'],
            'batch_id' => $validated['batch_id'],
            'status' => $validated['status'],
        ];

        // อนุญาตให้แก้ไขส่วนงานสังกัดเฉพาะ Central / Super Admin
        if ($isCentralOrSuper && !empty($validated['org_unit_id'])) {
            $updateData['org_unit_id'] = $validated['org_unit_id'];
        }

        $reg->update($updateData);

        return back()->with('success', 'แก้ไขข้อมูลนิสิต (' . $reg->student_code . ' ' . $reg->full_name . ') สำเร็จเรียบร้อยแล้ว');
    }

    public function ugStudentDelete($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = UgRegistration::findOrFail($id);

        if (!$isCentralOrSuper && $reg->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ลบข้อมูลนิสิตของส่วนงานอื่น');
        }

        $reg->delete();
        return back()->with('success', 'ลบข้อมูลการลงทะเบียนของนิสิตเรียบร้อยแล้ว');
    }

    public function ugStudentApprove($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = UgRegistration::findOrFail($id);

        if (!$isCentralOrSuper && $reg->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์จัดการข้อมูลนิสิตของส่วนงานอื่น');
        }

        $reg->status = 'APPROVED';
        $reg->reject_reason = null;
        $reg->save();

        return back()->with('success', 'อนุมัติสิทธิ์เข้าร่วมโครงการให้นิสิต (' . $reg->student_code . ' ' . $reg->full_name . ') เรียบร้อยแล้ว');
    }

    public function ugStudentReject(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = UgRegistration::findOrFail($id);

        if (!$isCentralOrSuper && $reg->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์จัดการข้อมูลนิสิตของส่วนงานอื่น');
        }

        $reason = $request->input('reject_reason', 'คุณสมบัติไม่ตรงตามเกณฑ์ หรือข้อมูลไม่ถูกต้อง');

        $reg->status = 'REJECTED';
        $reg->reject_reason = $reason;
        $reg->save();

        return back()->with('success', 'ปฏิเสธคำขอลงทะเบียนของนิสิต (' . $reg->student_code . ' ' . $reg->full_name . ') เรียบร้อยแล้ว');
    }

    public function ugCheckin($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = UgRegistration::findOrFail($id);

        if (!$isCentralOrSuper && $reg->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์จัดการข้อมูลนิสิตของส่วนงานอื่น');
        }

        $reg->status = 'CHECKED_IN';
        $reg->checked_in_at = now();
        $reg->save();

        return back()->with('success', 'เช็คอินนิสิต (' . $reg->student_code . ' ' . $reg->full_name . ') เรียบร้อยแล้ว');
    }

    public function ugComplete($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = UgRegistration::findOrFail($id);

        if (!$isCentralOrSuper && $reg->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์จัดการข้อมูลนิสิตของส่วนงานอื่น');
        }

        $reg->status = 'COMPLETED';
        $reg->evaluation_result = 'PASS';
        $reg->evaluation_score = 100;
        $reg->save();

        return back()->with('success', 'บันทึกสถานะผ่านเกณฑ์ 10 วัน ให้นิสิต (' . $reg->student_code . ' ' . $reg->full_name . ') เรียบร้อยแล้ว');
    }

    // จัดการจำนวนมาก (Bulk Action) สำหรับทะเบียนนิสิต ป.ตรี
    public function ugStudentsBulkAction(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $action = $request->input('bulk_action');
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'กรุณาเลือกรายการที่ต้องการดำเนินการอย่างน้อย 1 รายการ');
        }

        $query = UgRegistration::whereIn('id', $ids);
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        }

        $count = $query->count();
        if ($count === 0) {
            return back()->with('error', 'ไม่พบรายการที่ท่านมีสิทธิ์ดำเนินการ');
        }

        switch ($action) {
            case 'APPROVED':
                $query->update([
                    'status' => 'APPROVED',
                    'reject_reason' => null,
                ]);
                return back()->with('success', "อนุมัติสิทธิ์เข้าร่วมโครงการจำนวนมากสำเร็จแล้ว ({$count} รายการ)");

            case 'REJECTED':
                $query->update([
                    'status' => 'REJECTED',
                    'reject_reason' => 'ไม่อนุมัติสิทธิ์โดยเจ้าหน้าที่ส่วนงาน (Bulk Action)',
                ]);
                return back()->with('success', "ปฏิเสธคำขอลงทะเบียนจำนวนมากสำเร็จแล้ว ({$count} รายการ)");

            case 'PENDING':
                $query->update([
                    'status' => 'PENDING',
                    'checked_in_at' => null,
                ]);
                return back()->with('success', "ปรับสถานะเป็นรอตรวจสอบคุณสมบัติ ({$count} รายการ)");

            case 'CHECKED_IN':
                $query->update([
                    'status' => 'CHECKED_IN',
                    'checked_in_at' => now(),
                ]);
                return back()->with('success', "เช็คอินรายงานตัวจำนวนมากสำเร็จแล้ว ({$count} รายการ)");

            case 'COMPLETED':
                $query->update([
                    'status' => 'COMPLETED',
                    'evaluation_result' => 'PASS',
                    'evaluation_score' => 100,
                ]);
                return back()->with('success', "บันทึกผ่านเกณฑ์ 10 วัน จำนวนมากสำเร็จแล้ว ({$count} รายการ)");

            case 'DELETE':
                $query->delete();
                return back()->with('success', "ลบข้อมูลการลงทะเบียนจำนวนมากสำเร็จ ({$count} รายการ)");

            default:
                return back()->with('error', 'การดำเนินการไม่ถูกต้อง');
        }
    }

    public function ugScanner(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $batchQuery = UgBatch::with('organizationUnit')->orderBy('academic_year', 'desc')->orderBy('start_date', 'desc');
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $batchQuery->where('org_unit_id', $admin['org_unit_id']);
        }
        $batches = $batchQuery->get();

        $selectedBatchId = $request->input('batch_id', $batches->first()->id ?? null);
        $selectedBatch = $selectedBatchId ? UgBatch::with('organizationUnit')->find($selectedBatchId) : null;

        // สรุปยอดการเช็คอินของโครงการที่เลือก
        $stats = [
            'total' => 0,
            'checked_in' => 0,
            'completed' => 0,
            'registered' => 0,
        ];

        $recentCheckins = collect();

        if ($selectedBatchId) {
            $stats['total'] = UgRegistration::where('batch_id', $selectedBatchId)->count();
            $stats['checked_in'] = UgRegistration::where('batch_id', $selectedBatchId)->where('status', 'CHECKED_IN')->count();
            $stats['completed'] = UgRegistration::where('batch_id', $selectedBatchId)->where('status', 'COMPLETED')->count();
            $stats['registered'] = UgRegistration::where('batch_id', $selectedBatchId)->where('status', 'REGISTERED')->count();

            $recentCheckins = UgRegistration::with('organizationUnit')
                ->where('batch_id', $selectedBatchId)
                ->whereIn('status', ['CHECKED_IN', 'COMPLETED'])
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();
        }

        return view('admin.ug_scanner', compact('batches', 'selectedBatch', 'selectedBatchId', 'stats', 'recentCheckins', 'isCentralOrSuper'));
    }

    public function ugScannerVerify(Request $request)
    {
        $this->checkAuth();
        $code = trim($request->input('code', ''));
        $batchId = $request->input('batch_id');

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลรหัส QR Code หรือเลขที่ลงทะเบียน'
            ]);
        }

        // คลีนค่า code เผื่อส่งมาในรูปแบบ VPSMCU-UG-xxx, MCUVMS-UG-xxx หรือ URL
        $cleanCode = preg_replace('/^(VPSMCU|MCUVMS)-/', '', $code);

        $query = UgRegistration::with(['batch', 'organizationUnit'])
            ->where(function ($q) use ($cleanCode) {
                $q->where('registration_no', $cleanCode)
                  ->orWhere('student_code', $cleanCode)
                  ->orWhere('citizen_id', $cleanCode);
            });

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        $student = $query->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => "ไม่พบข้อมูลนิสิตจากรหัส '{$cleanCode}' ในโครงการนี้ กรุณาตรวจสอบอีกครั้ง"
            ]);
        }

        // ตรวจสอบสถานะการอนุมัติสิทธิ์: นิสิตต้องได้รับอนุมัติ (APPROVED) ก่อน จึงจะมีสิทธิ์เข้าเช็คอิน
        if ($student->status === 'PENDING') {
            return response()->json([
                'success' => false,
                'message' => "คำขอลงทะเบียนของนิสิตท่านนี้ ({$student->student_code} {$student->full_name}) 'อยู่ระหว่างรอเจ้าหน้าที่ตรวจสอบคุณสมบัติ' ยังไม่ได้รับอนุมัติสิทธิ์เข้าร่วมโครงการ"
            ]);
        }

        if ($student->status === 'REJECTED') {
            $reasonText = $student->reject_reason ? " (เหตุผล: {$student->reject_reason})" : "";
            return response()->json([
                'success' => false,
                'message' => "คำขอลงทะเบียนของนิสิตท่านนี้ 'ไม่ผ่านการอนุมัติสิทธิ์'{$reasonText}"
            ]);
        }

        // ตรวจสอบสถานะปัจจุบัน
        $previousStatus = $student->status;
        $alreadyCheckedIn = in_array($previousStatus, ['CHECKED_IN', 'COMPLETED']);

        // อัปเดตสถานะเป็น CHECKED_IN ทันที
        $student->status = 'CHECKED_IN';
        $student->checked_in_at = now();
        $student->save();

        return response()->json([
            'success' => true,
            'already_checked_in' => $alreadyCheckedIn,
            'message' => $alreadyCheckedIn 
                ? 'นิสิตท่านนี้ได้รายงานตัวเช็คอินไปแล้วก่อนหน้า' 
                : 'เช็คอินรายงานตัวสำเร็จเรียบร้อยแล้ว!',
            'student' => [
                'id' => $student->id,
                'registration_no' => $student->registration_no,
                'student_code' => $student->student_code,
                'full_name' => $student->prefix . $student->first_name . ' ' . $student->last_name,
                'org_unit_name' => $student->organizationUnit->name_th ?? 'มจร',
                'faculty' => $student->faculty ?? '-',
                'study_year' => $student->study_year ?? 1,
                'batch_title' => $student->batch->title ?? '-',
                'phone' => $student->phone ?? '-',
                'status' => $student->status,
                'checked_in_at' => $student->checked_in_at ? $student->checked_in_at->format('d/m/Y H:i:s') : date('d/m/Y H:i:s')
            ]
        ]);
    }

    public function ugAttendancePrint(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $batchQuery = UgBatch::with('organizationUnit')->orderBy('academic_year', 'desc')->orderBy('start_date', 'desc');
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $batchQuery->where('org_unit_id', $admin['org_unit_id']);
        }
        $batches = $batchQuery->get();

        $selectedBatchId = $request->input('batch_id', $batches->first()->id ?? null);
        $selectedBatch = $selectedBatchId ? UgBatch::with('organizationUnit')->find($selectedBatchId) : null;

        $registrations = collect();
        if ($selectedBatchId) {
            $registrations = UgRegistration::with('organizationUnit')
                ->where('batch_id', $selectedBatchId)
                ->orderBy('student_code', 'asc')
                ->get();
        }

        return view('admin.ug_attendance', compact('batches', 'selectedBatch', 'selectedBatchId', 'registrations', 'isCentralOrSuper'));
    }

    public function ugExportRegistrar(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $batchId = $request->input('batch_id');
        $statusFilter = $request->input('status', 'ALL');

        $query = UgRegistration::with(['batch', 'organizationUnit'])->orderBy('student_code', 'asc');

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        }

        if ($batchId) {
            $query->where('batch_id', $batchId);
            $batch = UgBatch::find($batchId);
            $batchName = $batch ? "_{$batch->academic_year}" : '';
        } else {
            $batchName = '_ALL';
        }

        if ($statusFilter !== 'ALL') {
            $query->where('status', $statusFilter);
        }

        $records = $query->get();

        $filename = "MCUVMS_UG_Registrar_Export" . $batchName . "_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($records) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel in Thai
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Row
            fputcsv($handle, [
                'ลำดับ',
                'เลขที่สมัคร',
                'รหัสนิสิต',
                'คำนำหน้า',
                'ชื่อ',
                'นามสกุล',
                'ฉายา',
                'ระดับการศึกษา',
                'ชั้นปี',
                'คณะ',
                'สาขาวิชา',
                'ส่วนงานต้นสังกัด',
                'โครงการปฏิบัติธรรม',
                'ปีการศึกษา',
                'เบอร์โทรศัพท์',
                'สถานะการปฏิบัติ',
                'ผลการประเมิน',
                'คะแนน',
                'วันที่เช็คอิน'
            ]);

            $index = 1;
            foreach ($records as $r) {
                $statusTh = match ($r->status) {
                    'COMPLETED' => 'ผ่านเกณฑ์ 10 วัน',
                    'CHECKED_IN' => 'เข้าปฏิบัติธรรมแล้ว',
                    default => 'ลงทะเบียนแล้ว'
                };

                $evalTh = match ($r->evaluation_result) {
                    'PASS' => 'ผ่าน (P)',
                    'FAIL' => 'ไม่ผ่าน (U)',
                    default => ($r->status === 'COMPLETED' ? 'ผ่าน (P)' : 'รอดำเนินการ')
                };

                fputcsv($handle, [
                    $index++,
                    $r->registration_no,
                    $r->student_code,
                    $r->prefix,
                    $r->first_name,
                    $r->last_name,
                    $r->chaya ?? '',
                    'ปริญญาตรี',
                    $r->study_year ?? 1,
                    $r->faculty ?? '',
                    $r->major ?? '',
                    $r->organizationUnit->name_th ?? 'มจร',
                    $r->batch->title ?? '',
                    $r->batch->academic_year ?? '',
                    $r->phone ?? '',
                    $statusTh,
                    $evalTh,
                    $r->evaluation_score ?? ($r->status === 'COMPLETED' ? 100 : 0),
                    $r->checked_in_at ? $r->checked_in_at : ''
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function ugSar(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        // Scope Query ตามสิทธิ์ส่วนงาน
        $baseQuery = UgRegistration::query();
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $baseQuery->where('org_unit_id', $admin['org_unit_id']);
        }

        // ตัวกรองปีการศึกษา / รุ่นโครงการ
        $batchId = $request->input('batch_id');
        $academicYear = $request->input('academic_year');
        $orgUnitId = $request->input('org_unit_id');

        if ($batchId) {
            $baseQuery->where('batch_id', $batchId);
        }
        if ($academicYear) {
            $baseQuery->whereHas('batch', function($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            });
        }
        if ($isCentralOrSuper && $orgUnitId) {
            $baseQuery->where('org_unit_id', $orgUnitId);
        }

        // 1. KPI Cards ภาพรวม
        $totalRegistered = (clone $baseQuery)->count();
        $checkedInCount = (clone $baseQuery)->whereIn('status', ['CHECKED_IN', 'COMPLETED'])->count();
        $completedCount = (clone $baseQuery)->where('status', 'COMPLETED')->count();
        $pendingCount = (clone $baseQuery)->where('status', 'REGISTERED')->count();
        $passRate = $totalRegistered > 0 ? round(($completedCount / $totalRegistered) * 100, 1) : 0;
        $checkinRate = $totalRegistered > 0 ? round(($checkedInCount / $totalRegistered) * 100, 1) : 0;

        // 2. สถิติแยกตามชั้นปี (ปี 1 - 4)
        $yearStats = (clone $baseQuery)
            ->select('study_year', DB::raw('count(*) as total'), 
                     DB::raw("sum(case when status = 'COMPLETED' then 1 else 0 end) as completed"),
                     DB::raw("sum(case when status in ('CHECKED_IN', 'COMPLETED') then 1 else 0 end) as checked_in"))
            ->groupBy('study_year')
            ->orderBy('study_year', 'asc')
            ->get();

        // 3. สถิติแยกตามสมณเพศ/เพศ (บรรพชิต vs คฤหัสถ์)
        $monkPrefixes = ['พระ', 'สามเณร', 'พระมหา', 'พระครู', 'พระปลัด', 'พระสมุห์', 'พระใบฎีกา', 'พระอธิการ', 'แม่ชี'];
        $monkCount = (clone $baseQuery)->where(function($q) use ($monkPrefixes) {
            foreach ($monkPrefixes as $p) {
                $q->orWhere('prefix', 'like', "%{$p}%");
            }
        })->count();
        $laymanCount = max(0, $totalRegistered - $monkCount);

        // 4. สถิติแยกตามส่วนงาน (Organization Units Breakdown)
        $orgUnitStats = UgRegistration::with('organizationUnit')
            ->select('org_unit_id',
                     DB::raw('count(*) as total'),
                     DB::raw("sum(case when status in ('CHECKED_IN', 'COMPLETED') then 1 else 0 end) as checked_in"),
                     DB::raw("sum(case when status = 'COMPLETED' then 1 else 0 end) as completed"),
                     DB::raw("sum(case when status = 'REGISTERED' then 1 else 0 end) as registered_only"))
            ->when(!$isCentralOrSuper && !empty($admin['org_unit_id']), function($q) use ($admin) {
                $q->where('org_unit_id', $admin['org_unit_id']);
            })
            ->when($batchId, function($q) use ($batchId) {
                $q->where('batch_id', $batchId);
            })
            ->when($academicYear, function($q) use ($academicYear) {
                $q->whereHas('batch', function($b) use ($academicYear) {
                    $b->where('academic_year', $academicYear);
                });
            })
            ->when($isCentralOrSuper && $orgUnitId, function($q) use ($orgUnitId) {
                $q->where('org_unit_id', $orgUnitId);
            })
            ->groupBy('org_unit_id')
            ->orderBy('total', 'desc')
            ->get();

        // 5. สถิติแยกตามคณะ (Faculty Breakdown)
        $facultyStats = (clone $baseQuery)
            ->select(DB::raw("COALESCE(NULLIF(faculty, ''), 'ไม่ระบุคณะ') as faculty_name"),
                     DB::raw('count(*) as total'),
                     DB::raw("sum(case when status = 'COMPLETED' then 1 else 0 end) as completed"))
            ->groupBy('faculty_name')
            ->orderBy('total', 'desc')
            ->get();

        // 6. โครงการ/รุ่น และ ส่วนงานสำหรับ Filter Dropdowns
        $batchQuery = UgBatch::with('organizationUnit')->orderBy('academic_year', 'desc')->orderBy('start_date', 'desc');
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $batchQuery->where('org_unit_id', $admin['org_unit_id']);
        }
        $batches = $batchQuery->get();
        $academicYears = UgBatch::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');
        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('admin.ug_sar', compact(
            'totalRegistered',
            'checkedInCount',
            'completedCount',
            'pendingCount',
            'passRate',
            'checkinRate',
            'yearStats',
            'monkCount',
            'laymanCount',
            'orgUnitStats',
            'facultyStats',
            'batches',
            'academicYears',
            'orgUnits',
            'isCentralOrSuper',
            'admin',
            'batchId',
            'academicYear',
            'orgUnitId'
        ));
    }

    public function gradSar(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $baseQuery = GradStudent::query();

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $baseQuery->where('org_unit_id', $admin['org_unit_id']);
        } elseif ($request->filled('filter_org')) {
            $baseQuery->where('org_unit_id', $request->input('filter_org'));
        }

        if ($request->filled('degree_level')) {
            $baseQuery->where('degree_level', $request->input('degree_level'));
        }

        // 1. KPI Totals
        $totalStudents = (clone $baseQuery)->count();
        $masterCount = (clone $baseQuery)->where('degree_level', 'MASTER')->count();
        $doctoralCount = (clone $baseQuery)->where('degree_level', 'DOCTORAL')->count();
        $approvedCount = (clone $baseQuery)->where('submission_status', 'APPROVED')->count();
        $submittedCount = (clone $baseQuery)->where('submission_status', 'SUBMITTED')->count();
        $accumulatingCount = (clone $baseQuery)->where('submission_status', 'ACCUMULATING')->count();
        $rejectedCount = (clone $baseQuery)->where('submission_status', 'REJECTED')->count();

        // 2. สถิติแยกตามระดับการศึกษา & สถานะ (ป.โท / ป.เอก)
        $masterApproved = (clone $baseQuery)->where('degree_level', 'MASTER')->where('submission_status', 'APPROVED')->count();
        $masterSubmitted = (clone $baseQuery)->where('degree_level', 'MASTER')->where('submission_status', 'SUBMITTED')->count();
        $doctoralApproved = (clone $baseQuery)->where('degree_level', 'DOCTORAL')->where('submission_status', 'APPROVED')->count();
        $doctoralSubmitted = (clone $baseQuery)->where('degree_level', 'DOCTORAL')->where('submission_status', 'SUBMITTED')->count();

        // 3. สถิติแยกตามคณะ (Faculty Breakdown)
        $facultyStats = (clone $baseQuery)->select('faculty', DB::raw('count(*) as total'), DB::raw("SUM(CASE WHEN submission_status = 'APPROVED' THEN 1 ELSE 0 END) as approved_count"))
            ->groupBy('faculty')
            ->orderBy('total', 'desc')
            ->get();

        // 4. สถิติการสะสมวันเฉลี่ยและวันรวม
        $totalDays = (clone $baseQuery)->sum('accumulated_days');
        $avgDays = $totalStudents > 0 ? round((clone $baseQuery)->avg('accumulated_days'), 1) : 0;

        // 5. สถิติเอกสารและหลักฐาน e-Document (ใบเสร็จ, สลิป, บันทึกสอบอารมณ์)
        $slipCount = (clone $baseQuery)->whereNotNull('slip_path')->count();
        $certThCount = (clone $baseQuery)->whereNotNull('cert_th_path')->count();
        $certEnCount = (clone $baseQuery)->whereNotNull('cert_en_path')->count();
        $receiptCount = (clone $baseQuery)->whereNotNull('receipt_path')->count();

        // 6. สถิติแยกตามส่วนงาน/วิทยาเขต (Top Campuses)
        $campusStats = (clone $baseQuery)->join('organization_units', 'grad_students.org_unit_id', '=', 'organization_units.id')
            ->select('organization_units.name_th as campus_name', DB::raw('count(grad_students.id) as total'), DB::raw("SUM(CASE WHEN grad_students.submission_status = 'APPROVED' THEN 1 ELSE 0 END) as approved_count"))
            ->groupBy('organization_units.name_th')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('admin.grad_sar', compact(
            'totalStudents',
            'masterCount',
            'doctoralCount',
            'approvedCount',
            'submittedCount',
            'accumulatingCount',
            'rejectedCount',
            'masterApproved',
            'masterSubmitted',
            'doctoralApproved',
            'doctoralSubmitted',
            'facultyStats',
            'totalDays',
            'avgDays',
            'slipCount',
            'certThCount',
            'certEnCount',
            'receiptCount',
            'campusStats',
            'orgUnits',
            'isCentralOrSuper'
        ));
    }

    public function gradApprovals(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        if ($request->has('bulk_action') || $request->input('action') === 'bulk') {
            return $this->gradApprovalsBulkAction($request);
        }
        if ($request->input('action') === 'update') {
            return $this->gradStudentUpdate($request, $request->input('id'));
        }
        if ($request->input('action') === 'approve') {
            return $this->gradApprove($request);
        }
        if ($request->input('action') === 'reject') {
            return $this->gradReject($request);
        }
        if ($request->input('action') === 'delete') {
            return $this->gradStudentDelete($request->input('id'));
        }

        $query = GradStudent::with('organizationUnit')
            ->orderByRaw("FIELD(submission_status, 'SUBMITTED', 'ACCUMULATING', 'APPROVED', 'REJECTED')")
            ->orderBy('submitted_at', 'desc')
            ->orderBy('id', 'desc');

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        } elseif ($request->filled('filter_org')) {
            $query->where('org_unit_id', $request->input('filter_org'));
        }

        // 9-Dimension Search (ตามระบบ e-Document rdo_perid)
        $rdoPerid = $request->input('rdo_perid', '');
        $searchValue = trim($request->input('search_val', ''));

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('student_code', 'LIKE', "%{$s}%")
                  ->orWhere('student_id', 'LIKE', "%{$s}%")
                  ->orWhere('citizen_id', 'LIKE', "%{$s}%")
                  ->orWhere('first_name', 'LIKE', "%{$s}%")
                  ->orWhere('last_name', 'LIKE', "%{$s}%")
                  ->orWhere('buddhist_name', 'LIKE', "%{$s}%")
                  ->orWhere('program_name', 'LIKE', "%{$s}%")
                  ->orWhere('faculty', 'LIKE', "%{$s}%");
            });
        } elseif (!empty($searchValue) || !empty($rdoPerid)) {
            switch ($rdoPerid) {
                case '1': // รหัสนิสิต
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('student_code', 'LIKE', "%{$searchValue}%")
                          ->orWhere('student_id', 'LIKE', "%{$searchValue}%");
                    });
                    break;
                case '2': // เลขประจำตัวประชาชน
                    $query->where('citizen_id', 'LIKE', "%{$searchValue}%");
                    break;
                case '3': // ชื่อ-นามสกุล / ฉายา
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('first_name', 'LIKE', "%{$searchValue}%")
                          ->orWhere('last_name', 'LIKE', "%{$searchValue}%")
                          ->orWhere('buddhist_name', 'LIKE', "%{$searchValue}%");
                    });
                    break;
                case '4': // คณะ
                    $query->where('faculty', 'LIKE', "%{$searchValue}%");
                    break;
                case '5': // สาขาวิชา
                    $query->where('program_name', 'LIKE', "%{$searchValue}%");
                    break;
                case '7': // ระดับการศึกษา
                    if (!empty($searchValue)) {
                        $query->where('degree_level', $searchValue);
                    }
                    break;
                case '8': // วันที่ขอเอกสาร
                    if (!empty($searchValue)) {
                        $query->whereDate('submitted_at', $searchValue);
                    }
                    break;
                case '9': // สถานะเอกสาร
                    if (!empty($searchValue)) {
                        $query->where('submission_status', $searchValue);
                    }
                    break;
                case '6': // แสดงทั้งหมด
                default:
                    if (!empty($searchValue)) {
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('student_code', 'LIKE', "%{$searchValue}%")
                              ->orWhere('first_name', 'LIKE', "%{$searchValue}%")
                              ->orWhere('last_name', 'LIKE', "%{$searchValue}%");
                        });
                    }
                    break;
            }
        }

        if ($request->filled('degree_level')) {
            $query->where('degree_level', $request->input('degree_level'));
        }

        if ($request->filled('status')) {
            $query->where('submission_status', $request->input('status'));
        }

        $perPage = $this->getPerPage($request);
        $students = $query->paginate($perPage)->withQueryString();
        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        $edocSetting = DB::table('system_settings')->where('setting_key', 'edoc_status')->first();
        $edocStatus = $edocSetting ? $edocSetting->setting_value : 'Y';

        return view('admin.grad_approvals', compact('students', 'orgUnits', 'isCentralOrSuper', 'edocStatus'));
    }


    public function gradApprove(Request $request)
    {
        $this->checkAuth();
        $studentId = $request->input('student_id');
        $admin = Session::get('admin_user');

        GradStudent::where('id', $studentId)->update([
            'submission_status' => 'APPROVED',
            'approved_at' => now(),
            'approved_by' => $admin['id'],
        ]);

        return back()->with('success', 'อนุมัติผลการสะสมวันปฏิบัติธรรมของนิสิตเรียบร้อยแล้ว');
    }

    public function gradReject(Request $request)
    {
        $this->checkAuth();
        $studentId = $request->input('student_id');

        GradStudent::where('id', $studentId)->update([
            'submission_status' => 'REJECTED',
        ]);

        return back()->with('success', 'ส่งกลับแก้ไขแฟ้มสะสมวันของนิสิตเรียบร้อยแล้ว');
    }

    public function gradStudentUpdate(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $student = GradStudent::findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $student->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์แก้ไขข้อมูลนิสิตของส่วนงานอื่น');
        }

        $validated = $request->validate([
            'student_code' => 'required|string|max:50',
            'citizen_id' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:50',
            'prefix' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'buddhist_name' => 'nullable|string|max:100',
            'age' => 'nullable|integer|min:1|max:120',
            'vassa' => 'nullable|integer|min:0|max:100',
            'degree_level' => 'required|string|max:100',
            'faculty' => 'nullable|string|max:100',
            'program_name' => 'nullable|string|max:150',
            'target_days' => 'required|integer|min:1',
            'accumulated_days' => 'required|integer|min:0',
            'address' => 'nullable|string|max:255',
            'subdistrict' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:50',
            'transfer_date' => 'nullable|date',
            'transfer_time' => 'nullable|string|max:10',
            'submission_status' => 'required|in:ACCUMULATING,SUBMITTED,APPROVED,REJECTED',
            'org_unit_id' => 'nullable|integer',
            'file_photo' => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            'file_interview' => 'nullable|file|mimes:pdf|max:10240',
            'file_attendance' => 'nullable|file|mimes:pdf|max:10240',
            'file_slip' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:10240',
        ]);

        if ($isCentralOrSuper && !empty($validated['org_unit_id'])) {
            $student->org_unit_id = $validated['org_unit_id'];
        }

        // จัดการอัปโหลดไฟล์ใหม่แทนที่ไฟล์เดิม (ถ้ามีการอัปโหลดไฟล์ใหม่)
        if ($request->hasFile('file_photo')) {
            $student->photo_path = $request->file('file_photo')->store('edoc/photos', 'public');
        }
        if ($request->hasFile('file_interview')) {
            $student->interview_record_path = $request->file('file_interview')->store('edoc/interviews', 'public');
        }
        if ($request->hasFile('file_attendance')) {
            $student->attendance_record_path = $request->file('file_attendance')->store('edoc/attendances', 'public');
        }
        if ($request->hasFile('file_slip')) {
            $student->slip_path = $request->file('file_slip')->store('edoc/slips', 'public');
        }

        $student->student_code = $validated['student_code'];
        $student->citizen_id = $validated['citizen_id'] ?? null;
        $student->nationality = $validated['nationality'] ?? 'ไทย';
        $student->prefix = $validated['prefix'] ?? null;
        $student->first_name = $validated['first_name'];
        $student->last_name = $validated['last_name'];
        $student->buddhist_name = $validated['buddhist_name'] ?? null;
        $student->age = $validated['age'] ?? null;
        $student->vassa = $validated['vassa'] ?? null;
        $student->degree_level = $validated['degree_level'];
        $student->faculty = $validated['faculty'] ?? null;
        $student->program_name = $validated['program_name'] ?? null;
        $student->target_days = $validated['target_days'];
        $student->accumulated_days = $validated['accumulated_days'];
        $student->address = $validated['address'] ?? null;
        $student->subdistrict = $validated['subdistrict'] ?? null;
        $student->district = $validated['district'] ?? null;
        $student->province = $validated['province'] ?? null;
        $student->postcode = $validated['postcode'] ?? null;
        $student->phone = $validated['phone'] ?? null;
        $student->transfer_date = $validated['transfer_date'] ?? null;
        $student->transfer_time = $validated['transfer_time'] ?? null;
        $student->submission_status = $validated['submission_status'];

        if ($validated['submission_status'] === 'APPROVED' && empty($student->approved_at)) {
            $student->approved_at = now();
            $student->approved_by = $admin['id'];
        }

        $student->save();

        return back()->with('success', "แก้ไขข้อมูลนิสิตบัณฑิตศึกษา ({$student->student_code}) เรียบร้อยแล้ว");
    }

    public function gradStudentDelete($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $student = GradStudent::findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $student->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ลบข้อมูลนิสิตของส่วนงานอื่น');
        }

        $student->delete();

        return back()->with('success', 'ลบข้อมูลนิสิตบัณฑิตศึกษาเรียบร้อยแล้ว');
    }

    // อัปโหลดเอกสารตอบกลับ e-Document (upcert.php, upcert_en.php, uprcv.php, assessment)
    public function gradUploadResponse(Request $request)
    {
        $this->checkAuth();
        $request->validate([
            'student_id' => 'required|exists:grad_students,id',
            'doc_type' => 'required|in:cert_th,cert_en,receipt,assessment',
            'response_file' => 'required|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $student = GradStudent::findOrFail($request->input('student_id'));
        $docType = $request->input('doc_type');
        $file = $request->file('response_file');

        switch ($docType) {
            case 'cert_th':
                $path = $file->store('edoc/certs', 'public');
                $student->cert_th_path = $path;
                $label = 'ใบรับรองภาษาไทย';
                break;
            case 'cert_en':
                $path = $file->store('edoc/certs', 'public');
                $student->cert_en_path = $path;
                $label = 'ใบรับรองภาษาอังกฤษ';
                break;
            case 'receipt':
                $path = $file->store('edoc/receipts', 'public');
                $student->receipt_path = $path;
                $label = 'ใบเสร็จรับเงิน';
                break;
            case 'assessment':
                $path = $file->store('edoc/assessments', 'public');
                $student->assessment_doc_path = $path;
                $label = 'ใบประเมินผล บฑ. ๒๑';
                break;
        }

        $student->save();

        return back()->with('success', "อัปโหลด{$label} สำหรับรหัสนิสิต {$student->student_code} สำเร็จเรียบร้อยแล้ว");
    }

    // เปิด-ปิด ระบบรับคำร้อง e-Document (edocconfig.php)
    public function gradToggleEdoc(Request $request)
    {
        $this->checkAuth();
        $status = $request->input('edoc_status') === 'Y' ? 'Y' : 'N';

        DB::table('system_settings')->updateOrInsert(
            ['setting_key' => 'edoc_status'],
            [
                'setting_value' => $status,
                'description' => 'สถานะเปิด-ปิดระบบรับคำร้อง e-Document (Y=เปิด, N=ปิด)',
                'updated_at' => now(),
            ]
        );

        $statusText = ($status === 'Y') ? 'เปิดระบบรับคำร้อง (Open)' : 'ปิดระบบรับคำร้อง (Closed)';
        return back()->with('success', "บันทึกการตั้งค่าระบบ e-Document เป็น: {$statusText} เรียบร้อยแล้ว");
    }

    // Export Excel สำหรับ e-Document บัณฑิตศึกษา (register2_excel_index.php)
    public function gradExportExcel(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $query = GradStudent::with('organizationUnit')->orderBy('id', 'asc');

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        } elseif ($request->filled('filter_org')) {
            $query->where('org_unit_id', $request->input('filter_org'));
        }

        if ($request->filled('degree_level')) {
            $query->where('degree_level', $request->input('degree_level'));
        }

        if ($request->filled('status')) {
            $query->where('submission_status', $request->input('status'));
        }

        $students = $query->get();

        $filename = 'MCU_Grad_eDocument_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($students) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for Excel UTF-8

            fputcsv($out, [
                'ลำดับ',
                'รหัสนิสิต',
                'เลขประจำตัวประชาชน',
                'คำนำหน้า',
                'ชื่อ',
                'นามสกุล',
                'ฉายาทางธรรม',
                'ระดับการศึกษา',
                'คณะ',
                'สาขาวิชา/หลักสูตร',
                'ส่วนงานสังกัด (มจร)',
                'วันสะสม',
                'เกณฑ์เป้าหมาย',
                'เบอร์โทรศัพท์',
                'ที่อยู่/สังกัด',
                'วันที่ยื่นคำร้อง',
                'วันที่โอนเงินในสลิป',
                'เวลาโอน',
                'สถานะคำร้อง',
                'วันที่อนุมัติ',
            ]);

            $i = 1;
            foreach ($students as $s) {
                $degree = ($s->degree_level === 'DOCTORAL') ? 'ปริญญาเอก (ดุษฎีบัณฑิต)' : 'ปริญญาโท (มหาบัณฑิต)';
                $org = $s->organizationUnit->name_th ?? 'มจร';
                $fullAddress = trim("{$s->address} ต.{$s->subdistrict} อ.{$s->district} จ.{$s->province} {$s->postcode}");

                fputcsv($out, [
                    $i++,
                    $s->student_code ?? $s->student_id,
                    $s->citizen_id,
                    $s->prefix,
                    $s->first_name,
                    $s->last_name,
                    $s->buddhist_name ?? '-',
                    $degree,
                    $s->faculty,
                    $s->program_name,
                    $org,
                    $s->accumulated_days,
                    $s->target_days,
                    $s->phone,
                    $fullAddress,
                    $s->submitted_at ?? $s->created_at,
                    $s->transfer_date,
                    $s->transfer_time,
                    $s->submission_status,
                    $s->approved_at,
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function publicSar(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        if ($request->has('bulk_action') || $request->input('action') === 'bulk') {
            return $this->publicSarBulkAction($request);
        }
        if ($request->input('action') === 'update') {
            return $this->publicSarUpdate($request, $request->input('id'));
        }

        $baseQuery = PublicRegistration::query();

        // สิทธิ์การเข้าถึงตามส่วนงาน
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $baseQuery->whereHas('event', function ($q) use ($admin) {
                $q->where('org_unit_id', $admin['org_unit_id']);
            });
        } elseif ($request->filled('filter_org')) {
            $orgId = $request->input('filter_org');
            $baseQuery->whereHas('event', function ($q) use ($orgId) {
                $q->where('org_unit_id', $orgId);
            });
        }

        if ($request->filled('event_id')) {
            $baseQuery->where('event_id', $request->input('event_id'));
        }

        // 1. KPI Cards Summary
        $total_registered = (clone $baseQuery)->count();
        $confirmed_count = (clone $baseQuery)->where('status', 'CONFIRMED')->count();
        $attended_count = (clone $baseQuery)->where('status', 'ATTENDED')->count();
        $waiting_count = (clone $baseQuery)->where('status', 'WAITING_LIST')->count();
        $pending_count = (clone $baseQuery)->where('status', 'PENDING')->count();
        $rejected_count = (clone $baseQuery)->where('status', 'REJECTED')->count();
        $cancelled_count = (clone $baseQuery)->where('status', 'CANCELLED')->count();

        // 2. Gender distribution
        $gender_stats = (clone $baseQuery)->select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get();

        // 3. Age groups
        $age_stats = (clone $baseQuery)->select(
            DB::raw("CASE 
                WHEN age < 25 THEN 'เยาวชน (< 25 ปี)'
                WHEN age BETWEEN 25 AND 45 THEN 'วัยทำงาน (25 - 45 ปี)'
                WHEN age BETWEEN 46 AND 60 THEN 'วัยผู้ใหญ่ (46 - 60 ปี)'
                ELSE 'ผู้สูงอายุ (> 60 ปี)'
            END as age_group"),
            DB::raw('count(*) as count')
        )->groupBy('age_group')->get();

        // 4. Applicant Types (ประชาชนทั่วไป vs นิสิต มจร)
        $type_stats = (clone $baseQuery)->select(
            DB::raw("CASE 
                WHEN applicant_type = 'STUDENT' THEN 'นิสิต มจร'
                ELSE 'ประชาชนทั่วไป'
            END as type_label"),
            DB::raw('count(*) as count')
        )->groupBy('type_label')->get();

        // 5. Dietary restrictions
        $dietary_stats = (clone $baseQuery)->select(
            DB::raw("COALESCE(dietary_restriction, 'NORMAL') as diet_type"),
            DB::raw('count(*) as count')
        )->groupBy('diet_type')->get();

        // 6. Top Provinces
        $province_stats = (clone $baseQuery)->select('province', DB::raw('count(*) as count'))
            ->whereNotNull('province')
            ->where('province', '!=', '')
            ->groupBy('province')
            ->orderBy('count', 'desc')
            ->limit(7)
            ->get();

        // 7. Course/Event Breakdown Summary Table (สถิติเปรียบเทียบแยกตามคอร์ส)
        $eventsQuery = PublicEvent::with(['organizationUnit'])->withCount([
            'registrations as total_applicants',
            'registrations as confirmed_applicants' => function ($q) {
                $q->where('status', 'CONFIRMED');
            },
            'registrations as pending_applicants' => function ($q) {
                $q->where('status', 'PENDING');
            },
            'registrations as attended_applicants' => function ($q) {
                $q->where('status', 'ATTENDED');
            },
            'registrations as waiting_applicants' => function ($q) {
                $q->where('status', 'WAITING_LIST');
            },
            'registrations as student_applicants' => function ($q) {
                $q->where('applicant_type', 'STUDENT');
            },
        ])->orderBy('start_date', 'desc');

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $eventsQuery->where('org_unit_id', $admin['org_unit_id']);
        } elseif ($request->filled('filter_org')) {
            $eventsQuery->where('org_unit_id', $request->input('filter_org'));
        }

        $eventsSummary = $eventsQuery->get();
        $events = PublicEvent::orderBy('start_date', 'desc')->get();
        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('admin.public_sar', compact(
            'total_registered', 'pending_count', 'confirmed_count', 'attended_count',
            'waiting_count', 'rejected_count', 'cancelled_count',
            'gender_stats', 'age_stats', 'type_stats', 'dietary_stats', 'province_stats',
            'eventsSummary', 'events', 'orgUnits', 'isCentralOrSuper'
        ));
    }

    // จัดการจำนวนมาก (Bulk Action) สำหรับการอนุมัติบัณฑิตศึกษา (Module 2)
    public function gradApprovalsBulkAction(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $action = $request->input('bulk_action');
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'กรุณาเลือกรายการที่ต้องการดำเนินการอย่างน้อย 1 รายการ');
        }

        $query = GradStudent::whereIn('id', $ids);
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        }

        $count = $query->count();
        if ($count === 0) {
            return back()->with('error', 'ไม่พบรายการที่ท่านมีสิทธิ์ดำเนินการ');
        }

        switch ($action) {
            case 'APPROVE':
                $query->update([
                    'submission_status' => 'APPROVED',
                    'approved_at' => now(),
                    'approved_by' => $admin['id'],
                ]);
                return back()->with('success', "อนุมัติผลการสะสมวันจำนวนมากสำเร็จแล้ว ({$count} รายการ)");

            case 'REJECT':
                $query->update([
                    'submission_status' => 'REJECTED',
                ]);
                return back()->with('success', "ส่งกลับแก้ไขแฟ้มสะสมวันจำนวนมากเรียบร้อยแล้ว ({$count} รายการ)");

            case 'RESET':
                $query->update([
                    'submission_status' => 'ACCUMULATING',
                ]);
                return back()->with('success', "ปรับสถานะกลับเป็นกำลังสะสมวัน ({$count} รายการ)");

            default:
                return back()->with('error', 'การดำเนินการไม่ถูกต้อง');
        }
    }

    // จัดการจำนวนมาก (Bulk Action) สำหรับทะเบียนประชาชนทั่วไป (Module 3)
    public function publicSarBulkAction(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $action = $request->input('bulk_action');
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'กรุณาเลือกรายการที่ต้องการดำเนินการอย่างน้อย 1 รายการ');
        }

        $query = PublicRegistration::whereIn('id', $ids);
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->whereHas('event', function ($q) use ($admin) {
                $q->where('org_unit_id', $admin['org_unit_id']);
            });
        }

        $count = $query->count();
        if ($count === 0) {
            return back()->with('error', 'ไม่พบรายการที่ท่านมีสิทธิ์ดำเนินการ');
        }

        switch ($action) {
            case 'CONFIRMED':
                $query->update(['status' => 'CONFIRMED']);
                // Recalculate confirmed counts for affected events
                $affectedEvents = PublicRegistration::whereIn('id', $ids)->pluck('event_id')->unique();
                foreach ($affectedEvents as $evId) {
                    $cnt = PublicRegistration::where('event_id', $evId)->where('status', 'CONFIRMED')->count();
                    PublicEvent::where('id', $evId)->update(['confirmed_count' => $cnt]);
                }
                return back()->with('success', "อนุมัติสิทธิ์เข้าร่วม (Confirmed) จำนวน {$count} ท่าน");

            case 'WAITING_LIST':
                $query->update(['status' => 'WAITING_LIST']);
                return back()->with('success', "ปรับสถานะเป็น รายชื่อสำรอง (Waiting List) จำนวน {$count} ท่าน");

            case 'REJECTED':
                $query->update(['status' => 'REJECTED']);
                // Recalculate confirmed counts for affected events
                $affectedEvents = PublicRegistration::whereIn('id', $ids)->pluck('event_id')->unique();
                foreach ($affectedEvents as $evId) {
                    $cnt = PublicRegistration::where('event_id', $evId)->where('status', 'CONFIRMED')->count();
                    PublicEvent::where('id', $evId)->update(['confirmed_count' => $cnt]);
                }
                return back()->with('success', "ปฏิเสธ/ไม่อนุมัติคำขอจำนวน {$count} ท่าน");

            case 'PENDING':
                $query->update(['status' => 'PENDING']);
                return back()->with('success', "ปรับสถานะเป็น รอการตรวจสอบ (Pending) จำนวน {$count} ท่าน");

            case 'ATTENDED':
                $query->update(['status' => 'ATTENDED']);
                return back()->with('success', "บันทึกเข้าร่วมอบรมแล้ว (Attended) จำนวน {$count} ท่าน");

            case 'CANCELLED':
                $query->update(['status' => 'CANCELLED']);
                return back()->with('success', "ปรับสถานะเป็น ยกเลิกการเข้าร่วม จำนวน {$count} ท่าน");

            case 'DELETE':
                $affectedEvents = PublicRegistration::whereIn('id', $ids)->pluck('event_id')->unique();
                $query->delete();
                foreach ($affectedEvents as $evId) {
                    $cnt = PublicRegistration::where('event_id', $evId)->where('status', 'CONFIRMED')->count();
                    PublicEvent::where('id', $evId)->update(['confirmed_count' => $cnt]);
                }
                return back()->with('success', "ย้ายข้อมูลผู้สมัคร ({$count} รายการ) ไปยังถังขยะเรียบร้อยแล้ว (สามารถกู้คืนได้)");

            case 'RESTORE':
                $restoredCount = 0;
                $affectedEvents = [];
                $trashedItems = PublicRegistration::onlyTrashed()->whereIn('id', $ids)->get();
                foreach ($trashedItems as $item) {
                    if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $item->event->org_unit_id != $admin['org_unit_id']) {
                        continue;
                    }
                    $affectedEvents[] = $item->event_id;
                    $item->restore();
                    $restoredCount++;
                }
                foreach (array_unique($affectedEvents) as $evId) {
                    $cnt = PublicRegistration::where('event_id', $evId)->where('status', 'CONFIRMED')->count();
                    PublicEvent::where('id', $evId)->update(['confirmed_count' => $cnt]);
                }
                return back()->with('success', "กู้คืนข้อมูลผู้สมัครจำนวน {$restoredCount} รายการกลับสู่ระบบเรียบร้อยแล้ว");

            case 'FORCE_DELETE':
                $deletedCount = 0;
                $trashedItems = PublicRegistration::onlyTrashed()->whereIn('id', $ids)->get();
                foreach ($trashedItems as $item) {
                    if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $item->event->org_unit_id != $admin['org_unit_id']) {
                        continue;
                    }
                    $item->forceDelete();
                    $deletedCount++;
                }
                return back()->with('success', "ลบข้อมูลผู้สมัครออกจากระบบถาวรจำนวน {$deletedCount} รายการเรียบร้อยแล้ว");

            default:
                return back()->with('error', 'การดำเนินการไม่ถูกต้อง');
        }
    }

    public function publicStudentStatus($id, $status)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = PublicRegistration::with('event')->findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $reg->event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ปรับสถานะผู้สมัครของส่วนงานอื่น');
        }

        $validStatuses = ['PENDING', 'CONFIRMED', 'WAITING_LIST', 'ATTENDED', 'REJECTED', 'CANCELLED'];
        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'สถานะไม่ถูกต้อง');
        }

        $reg->status = $status;
        if ($status === 'CONFIRMED') {
            $reg->reject_reason = null;
        }
        $reg->save();

        // Update event confirmed count
        $cnt = PublicRegistration::where('event_id', $reg->event_id)->where('status', 'CONFIRMED')->count();
        PublicEvent::where('id', $reg->event_id)->update(['confirmed_count' => $cnt]);

        $statusLabels = [
            'PENDING' => 'รอการตรวจสอบ',
            'CONFIRMED' => 'อนุมัติสิทธิ์แล้ว',
            'WAITING_LIST' => 'รายชื่อสำรอง',
            'ATTENDED' => 'เข้าร่วมอบรมแล้ว',
            'REJECTED' => 'ไม่อนุมัติ/ปฏิเสธ',
            'CANCELLED' => 'ยกเลิกสิทธิ์'
        ];
        $label = $statusLabels[$status] ?? $status;

        return redirect(url('/admin/public_students.php'))->with('success', "เปลี่ยนสถานะผู้สมัคร ({$reg->full_name}) เป็น \"{$label}\" เรียบร้อยแล้ว");
    }

    public function publicStudentApprove($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = PublicRegistration::with('event')->findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $reg->event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์อนุมัติผู้สมัครของส่วนงานอื่น');
        }

        $reg->status = 'CONFIRMED';
        $reg->reject_reason = null;
        $reg->save();

        // Update event confirmed count
        $cnt = PublicRegistration::where('event_id', $reg->event_id)->where('status', 'CONFIRMED')->count();
        PublicEvent::where('id', $reg->event_id)->update(['confirmed_count' => $cnt]);

        return back()->with('success', "อนุมัติสิทธิ์การเข้าร่วมอบรมของ ({$reg->full_name}) เรียบร้อยแล้ว");
    }

    public function publicStudentReject(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = PublicRegistration::with('event')->findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $reg->event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ปฏิเสธผู้สมัครของส่วนงานอื่น');
        }

        $reason = $request->input('reject_reason', 'คุณสมบัติหรือข้อมูลไม่ผ่านเกณฑ์การอบรม');
        $reg->status = 'REJECTED';
        $reg->reject_reason = $reason;
        $reg->save();

        // Update event confirmed count
        $cnt = PublicRegistration::where('event_id', $reg->event_id)->where('status', 'CONFIRMED')->count();
        PublicEvent::where('id', $reg->event_id)->update(['confirmed_count' => $cnt]);

        return back()->with('success', "ปฏิเสธคำขอการเข้าร่วมของ ({$reg->full_name}) เรียบร้อยแล้ว (เหตุผล: {$reason})");
    }

    public function publicStudentUpdate(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = PublicRegistration::with('event')->findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $reg->event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์แก้ไขข้อมูลผู้สมัครของส่วนงานอื่น');
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:public_events,id',
            'applicant_type' => 'required|in:PEOPLE,GENERAL,STUDENT',
            'student_id' => 'nullable|string|max:50',
            'degree_level' => 'nullable|string|max:50',
            'faculty' => 'nullable|string|max:100',
            'org_unit_id' => 'nullable|exists:organization_units,id',
            'program_name' => 'nullable|string|max:150',
            'citizen_id' => 'nullable|string|max:30',
            'prefix' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'buddhist_name' => 'nullable|string|max:100',
            'gender' => 'required|in:MALE,FEMALE,MONK,NOVICE,OTHER',
            'age' => 'nullable|integer|min:0|max:120',
            'vassa' => 'nullable|integer|min:0|max:100',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:100',
            'line_id' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'subdistrict' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:150',
            'emergency_phone' => 'nullable|string|max:50',
            'congenital_disease' => 'nullable|string|max:255',
            'dietary_restriction' => 'nullable|string|max:100',
            'room_info' => 'nullable|string|max:150',
            'vehicle_info' => 'nullable|string|max:150',
            'status' => 'required|in:PENDING,CONFIRMED,WAITING_LIST,ATTENDED,REJECTED,CANCELLED',
            'reject_reason' => 'nullable|string|max:255',
        ]);

        $buddhistPart = !empty($validated['buddhist_name']) ? ' (' . trim($validated['buddhist_name']) . ')' : '';
        $fullName = trim(($validated['prefix'] ?? '') . ' ' . $validated['first_name'] . ' ' . $validated['last_name'] . $buddhistPart);
        $validated['full_name'] = $fullName;

        $oldEventId = $reg->event_id;
        $reg->update($validated);

        // Update confirmed count on events
        $eventsToUpdate = array_unique([$oldEventId, $reg->event_id]);
        foreach ($eventsToUpdate as $evId) {
            $cnt = PublicRegistration::where('event_id', $evId)->where('status', 'CONFIRMED')->count();
            PublicEvent::where('id', $evId)->update(['confirmed_count' => $cnt]);
        }

        return back()->with('success', "แก้ไขข้อมูลผู้สมัคร ({$fullName}) สำเร็จเรียบร้อยแล้ว");
    }

    public function publicSarUpdate(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = PublicRegistration::with('event')->findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $reg->event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์แก้ไขข้อมูลผู้เข้าร่วมของส่วนงานอื่น');
        }

        $validated = $request->validate([
            'prefix' => 'nullable|string|max:50',
            'full_name' => 'required|string|max:150',
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'required|in:MALE,FEMALE,OTHER',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:100',
            'province' => 'nullable|string|max:100',
            'dietary_restriction' => 'nullable|string|max:200',
            'medical_condition' => 'nullable|string|max:255',
            'status' => 'required|in:CONFIRMED,WAITING_LIST,ATTENDED,CANCELLED',
        ]);

        $reg->update($validated);

        return back()->with('success', "แก้ไขข้อมูลผู้สมัคร ({$reg->full_name}) เรียบร้อยแล้ว");
    }

    public function publicSarDelete($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = PublicRegistration::with('event')->findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $reg->event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ลบข้อมูลผู้เข้าร่วมของส่วนงานอื่น');
        }

        $eventId = $reg->event_id;
        $reg->delete();

        // Recalculate event confirmed count
        $cnt = PublicRegistration::where('event_id', $eventId)->where('status', 'CONFIRMED')->count();
        PublicEvent::where('id', $eventId)->update(['confirmed_count' => $cnt]);

        return back()->with('success', "ย้ายข้อมูลผู้สมัคร ({$reg->full_name}) ไปยังถังขยะเรียบร้อยแล้ว (สามารถกู้คืนได้)");
    }

    public function publicStudentRestore($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = PublicRegistration::onlyTrashed()->with('event')->findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $reg->event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์กู้คืนข้อมูลผู้สมัครของส่วนงานอื่น');
        }

        $reg->restore();

        // Recalculate event confirmed count if restored record is CONFIRMED
        $cnt = PublicRegistration::where('event_id', $reg->event_id)->where('status', 'CONFIRMED')->count();
        PublicEvent::where('id', $reg->event_id)->update(['confirmed_count' => $cnt]);

        return back()->with('success', "กู้คืนข้อมูลผู้สมัคร ({$reg->full_name}) กลับสู่ระบบเรียบร้อยแล้ว");
    }

    public function publicStudentForceDelete($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $reg = PublicRegistration::onlyTrashed()->with('event')->findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $reg->event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ลบถาวรข้อมูลผู้สมัครของส่วนงานอื่น');
        }

        $name = $reg->full_name;
        $reg->forceDelete();

        return back()->with('success', "ลบข้อมูลผู้สมัคร ({$name}) ออกจากระบบถาวรเรียบร้อยแล้ว");
    }

    public function publicEvents(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        // Action Dispatcher for Shared Hosting Compatibility (via /admin/public_events.php?action=... or POST)
        $action = $request->input('action');
        if ($action === 'store') {
            return $this->publicEventStore($request);
        }
        if ($action === 'update') {
            return $this->publicEventUpdate($request, $request->input('id'));
        }
        if ($action === 'status') {
            return $this->publicEventStatus($request->input('id'), $request->input('status_val'));
        }
        if ($action === 'delete') {
            return $this->publicEventDelete($request->input('id'));
        }

        $query = PublicEvent::with(['organizationUnit', 'registrations'])
            ->withCount([
                'registrations as actual_registrations_count',
                'activeRegistrations as actual_active_count',
                'registrations as actual_confirmed_count' => function ($q) {
                    $q->where('status', 'CONFIRMED');
                }
            ])
            ->orderBy('start_date', 'desc');

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        } elseif ($request->filled('filter_org')) {
            $query->where('org_unit_id', $request->input('filter_org'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('title', 'LIKE', "%{$s}%")
                  ->orWhere('location_name', 'LIKE', "%{$s}%");
            });
        }

        $perPage = $this->getPerPage($request);
        $events = $query->paginate($perPage)->withQueryString();
        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('admin.public_events', compact('events', 'orgUnits', 'isCentralOrSuper'));
    }

    public function publicEventStore(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:3000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_quota' => 'required|integer|min:1',
            'status' => 'required|in:OPEN,CLOSED,COMPLETED',
            'org_unit_id' => 'nullable|integer',
            'cover_image' => 'nullable|string|max:500',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ]);

        $coverImageUrl = $validated['cover_image'] ?? null;
        if ($request->hasFile('cover_file') && $request->file('cover_file')->isValid()) {
            $path = $request->file('cover_file')->store('public_events', 'public');
            $coverImageUrl = Storage::url($path);
        }

        $orgUnitId = ($isCentralOrSuper && !empty($validated['org_unit_id']))
            ? $validated['org_unit_id']
            : ($admin['org_unit_id'] ?? 1);

        // Auto-translate to English
        $titleEn = TranslationService::translateToEnglish($validated['title']);
        $locationEn = TranslationService::translateToEnglish($validated['location_name']);
        $descriptionEn = !empty($validated['description']) ? TranslationService::translateToEnglish($validated['description']) : null;

        $newEvent = PublicEvent::create([
            'org_unit_id' => $orgUnitId,
            'title' => $validated['title'],
            'title_en' => $titleEn,
            'location_name' => $validated['location_name'],
            'location_name_en' => $locationEn,
            'description' => $validated['description'] ?? null,
            'description_en' => $descriptionEn,
            'cover_image' => $coverImageUrl,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'max_quota' => $validated['max_quota'],
            'status' => $validated['status'],
            'confirmed_count' => 0,
            'waiting_count' => 0,
        ]);

        ActivityLogger::log('CREATE', 'PUBLIC_EVENTS', "สร้างคอร์สปฏิบัติธรรมใหม่: {$newEvent->title}", ['event_id' => $newEvent->id, 'max_quota' => $newEvent->max_quota]);

        return back()->with('success', 'สร้างคอร์ส/โครงการปฏิบัติธรรมใหม่สำเร็จเรียบร้อยแล้ว (พร้อมแปลภาษาอังกฤษอัตโนมัติ)');
    }

    public function publicEventUpdate(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $event = PublicEvent::findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์แก้ไขโครงการของส่วนงานอื่น');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:3000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_quota' => 'required|integer|min:1',
            'status' => 'required|in:OPEN,CLOSED,COMPLETED',
            'org_unit_id' => 'nullable|integer',
            'cover_image' => 'nullable|string|max:500',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ]);

        $coverImageUrl = $validated['cover_image'] ?? $event->cover_image;
        if ($request->hasFile('cover_file') && $request->file('cover_file')->isValid()) {
            $path = $request->file('cover_file')->store('public_events', 'public');
            $coverImageUrl = Storage::url($path);
        }

        if ($isCentralOrSuper && !empty($validated['org_unit_id'])) {
            $event->org_unit_id = $validated['org_unit_id'];
        }

        // Auto-translate if changed
        if ($event->title !== $validated['title'] || empty($event->title_en)) {
            $event->title_en = TranslationService::translateToEnglish($validated['title']);
        }
        if ($event->location_name !== $validated['location_name'] || empty($event->location_name_en)) {
            $event->location_name_en = TranslationService::translateToEnglish($validated['location_name']);
        }
        if (!empty($validated['description']) && ($event->description !== $validated['description'] || empty($event->description_en))) {
            $event->description_en = TranslationService::translateToEnglish($validated['description']);
        } elseif (empty($validated['description'])) {
            $event->description_en = null;
        }

        $event->title = $validated['title'];
        $event->location_name = $validated['location_name'];
        $event->description = $validated['description'] ?? null;
        $event->cover_image = $coverImageUrl;
        $event->start_date = $validated['start_date'];
        $event->end_date = $validated['end_date'];
        $event->max_quota = $validated['max_quota'];
        $event->status = $validated['status'];
        $event->save();

        ActivityLogger::log('UPDATE', 'PUBLIC_EVENTS', "แก้ไขข้อมูลคอร์สปฏิบัติธรรม: {$event->title}", ['event_id' => $event->id]);

        return back()->with('success', "แก้ไขข้อมูลโครงการ ({$event->title}) สำเร็จเรียบร้อยแล้ว");
    }

    public function publicEventStatus($id, $status)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $event = PublicEvent::findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ปรับสถานะโครงการของส่วนงานอื่น');
        }

        if (!in_array($status, ['OPEN', 'CLOSED', 'COMPLETED'])) {
            return back()->with('error', 'สถานะไม่ถูกต้อง');
        }

        $event->status = $status;
        $event->save();

        $statusText = ($status === 'OPEN') ? 'เปิดรับสมัคร' : (($status === 'CLOSED') ? 'ปิดรับสมัคร' : 'เสร็จสิ้นโครงการ');
        ActivityLogger::log('STATUS_CHANGE', 'PUBLIC_EVENTS', "เปลี่ยนสถานะคอร์ส ({$event->title}) เป็น \"{$statusText}\"", ['event_id' => $event->id, 'new_status' => $status]);

        return back()->with('success', "เปลี่ยนสถานะโครงการเป็น \"{$statusText}\" เรียบร้อยแล้ว");
    }

    public function publicEventDelete($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $event = PublicEvent::findOrFail($id);

        if (!$isCentralOrSuper && !empty($admin['org_unit_id']) && $event->org_unit_id != $admin['org_unit_id']) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ลบโครงการของส่วนงานอื่น');
        }

        $title = $event->title;
        $event->delete();

        ActivityLogger::log('DELETE', 'PUBLIC_EVENTS', "ลบโครงการปฏิบัติธรรม: {$title}", ['event_id' => $id]);

        return back()->with('success', 'ลบโครงการปฏิบัติธรรมเรียบร้อยแล้ว');
    }

    public function publicStudents(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        // Action Dispatcher for Shared Hosting Compatibility (via /admin/public_students.php?action=... or POST)
        $action = $request->input('action');
        if ($action === 'export') {
            return $this->publicExport($request);
        }
        if ($action === 'update') {
            return $this->publicStudentUpdate($request, $request->input('id'));
        }
        if ($action === 'status') {
            return $this->publicStudentStatus($request->input('id'), $request->input('status_val'));
        }
        if ($action === 'approve') {
            return $this->publicStudentApprove($request->input('id'));
        }
        if ($action === 'reject') {
            return $this->publicStudentReject($request, $request->input('id'));
        }
        if ($action === 'delete') {
            return $this->publicSarDelete($request->input('id'));
        }
        if ($action === 'restore') {
            return $this->publicStudentRestore($request->input('id'));
        }
        if ($action === 'force_delete') {
            return $this->publicStudentForceDelete($request->input('id'));
        }
        if ($action === 'bulk' || $request->has('bulk_action')) {
            return $this->publicSarBulkAction($request);
        }

        $isTrashTab = ($request->input('tab') === 'trash' || $request->input('status') === 'TRASH');

        $query = PublicRegistration::with(['event.organizationUnit', 'organizationUnit'])->orderBy('registered_at', 'desc');

        if ($isTrashTab) {
            $query->onlyTrashed();
        }

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->whereHas('event', function ($q) use ($admin) {
                $q->where('org_unit_id', $admin['org_unit_id']);
            });
        } elseif ($request->filled('filter_org')) {
            $orgId = $request->input('filter_org');
            $query->whereHas('event', function ($q) use ($orgId) {
                $q->where('org_unit_id', $orgId);
            });
        }

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $cleanQueue = preg_replace('/[^0-9]/', '', $s);
            $query->where(function ($q) use ($s, $cleanQueue) {
                $q->where('full_name', 'LIKE', "%{$s}%")
                  ->orWhere('phone', 'LIKE', "%{$s}%")
                  ->orWhere('citizen_id', 'LIKE', "%{$s}%")
                  ->orWhere('registration_no', 'LIKE', "%{$s}%");
                if ($cleanQueue) {
                    $q->orWhere('queue_no', intval($cleanQueue));
                }
            });
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->input('event_id'));
        }

        if (!$isTrashTab && $request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('applicant_type')) {
            $query->where('applicant_type', $request->input('applicant_type'));
        }

        // Count trashed items for badge
        $trashCountQuery = PublicRegistration::onlyTrashed();
        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $trashCountQuery->whereHas('event', function ($q) use ($admin) {
                $q->where('org_unit_id', $admin['org_unit_id']);
            });
        }
        $trashCount = $trashCountQuery->count();

        $perPage = $this->getPerPage($request);
        $registrations = $query->paginate($perPage)->withQueryString();
        $events = PublicEvent::orderBy('start_date', 'desc')->get();
        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('admin.public_students', compact('registrations', 'events', 'orgUnits', 'isCentralOrSuper', 'isTrashTab', 'trashCount'));
    }

    public function publicExport(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $query = PublicRegistration::with(['event.organizationUnit', 'organizationUnit'])->orderBy('registered_at', 'desc');

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->whereHas('event', function ($q) use ($admin) {
                $q->where('org_unit_id', $admin['org_unit_id']);
            });
        } elseif ($request->filled('filter_org')) {
            $orgId = $request->input('filter_org');
            $query->whereHas('event', function ($q) use ($orgId) {
                $q->where('org_unit_id', $orgId);
            });
        }

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $cleanQueue = preg_replace('/[^0-9]/', '', $s);
            $query->where(function ($q) use ($s, $cleanQueue) {
                $q->where('full_name', 'LIKE', "%{$s}%")
                  ->orWhere('phone', 'LIKE', "%{$s}%")
                  ->orWhere('citizen_id', 'LIKE', "%{$s}%")
                  ->orWhere('registration_no', 'LIKE', "%{$s}%");
                if ($cleanQueue) {
                    $q->orWhere('queue_no', intval($cleanQueue));
                }
            });
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->input('event_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('applicant_type')) {
            $query->where('applicant_type', $request->input('applicant_type'));
        }

        $items = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="public_registrations_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($items) {
            $fh = fopen('php://output', 'w');
            // Add BOM for UTF-8 Excel support
            fputs($fh, "\xEF\xBB\xBF");

            fputcsv($fh, [
                'ลำดับคิว',
                'เลขที่ลงทะเบียน',
                'สถานะผู้สมัคร',
                'รหัสนิสิต (ถ้ามี)',
                'ระดับการศึกษา',
                'คณะ',
                'ส่วนจัดการศึกษา (มจร)',
                'หลักสูตร/สาขาวิชา',
                'เลขบัตร ปชช./Passport',
                'คำนำหน้า',
                'ชื่อ-นามสกุล',
                'ฉายา',
                'เพศ',
                'อายุ',
                'พรรษา',
                'เบอร์โทร',
                'ที่อยู่',
                'ตำบล/แขวง',
                'อำเภอ/เขต',
                'จังหวัด',
                'รหัสไปรษณีย์',
                'ห้องพัก/อาคาร',
                'ยานพาหนะ/ทะเบียนรถ',
                'ประเภทอาหาร',
                'ความต้องการพิเศษ',
                'โครงการที่เข้าร่วม',
                'ส่วนงานผู้จัด',
                'สถานะ',
                'วันที่ลงทะเบียน'
            ]);

            foreach ($items as $r) {
                fputcsv($fh, [
                    $r->queue_no,
                    $r->registration_no,
                    $r->applicant_type === 'STUDENT' ? 'นิสิต มจร' : 'ประชาชนทั่วไป',
                    $r->student_id ?: '-',
                    $r->degree_level ?: '-',
                    $r->faculty ?: '-',
                    $r->organizationUnit->name_th ?? '-',
                    $r->program_name ?: '-',
                    "'" . $r->citizen_id,
                    $r->prefix,
                    $r->full_name,
                    $r->buddhist_name ?: '-',
                    $r->gender,
                    $r->age,
                    $r->vassa ?: 0,
                    "'" . $r->phone,
                    $r->address ?: '-',
                    $r->subdistrict ?: '-',
                    $r->district ?: '-',
                    $r->province ?: '-',
                    $r->postal_code ?: '-',
                    $r->room_info ?: '-',
                    $r->vehicle_info ?: '-',
                    $r->dietary_restriction ?: '-',
                    $r->congenital_disease ?: '-',
                    $r->event->title ?? '-',
                    $r->event->organizationUnit->name_th ?? '-',
                    $r->status,
                    $r->registered_at
                ]);
            }
            fclose($fh);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function seedSamplePublicStudents()
    {
        $firstEvent = PublicEvent::first();
        $eventId = $firstEvent ? $firstEvent->id : 1;
        $orgUnitId = $firstEvent ? ($firstEvent->org_unit_id ?: 1) : 1;

        $samples = [
            [
                'registration_no' => 'PUB-20261023-0001',
                'event_id' => $eventId,
                'applicant_type' => 'GENERAL',
                'citizen_id' => '1100200876541',
                'student_id' => null,
                'degree_level' => null,
                'faculty' => null,
                'org_unit_id' => $orgUnitId,
                'program_name' => null,
                'prefix' => 'นาง',
                'first_name' => 'พิมพ์พร',
                'last_name' => 'ศิริวัฒนา',
                'buddhist_name' => null,
                'full_name' => 'นางพิมพ์พร ศิริวัฒนา',
                'gender' => 'FEMALE',
                'age' => 45,
                'vassa' => 0,
                'phone' => '081-888-9999',
                'email' => 'pimporn.s@gmail.com',
                'line_id' => 'pim_meditation',
                'province' => 'กรุงเทพมหานคร',
                'postal_code' => '10200',
                'address' => '124/5 ซอยสุขุมวิท 55 แขวงคลองตันเหนือ',
                'subdistrict' => 'คลองตันเหนือ',
                'district' => 'วัฒนา',
                'emergency_contact' => 'นายเกียรติศักดิ์ ศิริวัฒนา (สามี)',
                'emergency_phone' => '081-888-9990',
                'congenital_disease' => 'ไม่มี',
                'dietary_restriction' => 'มังสวิรัติ',
                'room_info' => 'อาคาร 74 ปี ห้อง 302',
                'vehicle_info' => 'รถยนต์ส่วนบุคคล ทะเบียน กข 1234 กทม.',
                'queue_no' => 1,
                'status' => 'CONFIRMED',
                'registered_at' => '2026-10-01 09:30:00',
            ],
            [
                'registration_no' => 'PUB-20261023-0002',
                'event_id' => $eventId,
                'applicant_type' => 'STUDENT',
                'citizen_id' => '1100400765432',
                'student_id' => '6601201001',
                'degree_level' => 'ปริญญาตรี',
                'faculty' => 'พุทธศาสตร์',
                'org_unit_id' => $orgUnitId,
                'program_name' => 'สาขาวิชาพระพุทธศาสนา',
                'prefix' => 'พระมหา',
                'first_name' => 'ปัญญา',
                'last_name' => 'เมธี',
                'buddhist_name' => 'ปญฺญาเมธี',
                'full_name' => 'พระมหาปัญญา เมธี',
                'gender' => 'MALE',
                'age' => 28,
                'vassa' => 7,
                'phone' => '086-777-6655',
                'email' => 'panya.med@mcu.ac.th',
                'line_id' => 'panya_mcu',
                'province' => 'พระนครศรีอยุธยา',
                'postal_code' => '13170',
                'address' => 'วัดมหาธาตุยุวราชรังสฤษฎิ์ เขตพระนคร',
                'subdistrict' => 'พระบรมมหาราชวัง',
                'district' => 'พระนคร',
                'emergency_contact' => 'พระครูปลัดสมคิด (เจ้าอาวาส)',
                'emergency_phone' => '086-777-6650',
                'congenital_disease' => 'ไม่มี',
                'dietary_restriction' => 'อาหารทั่วไป',
                'room_info' => 'กุฏิสงฆ์ โซน A',
                'vehicle_info' => 'รถตู้มหาวิทยาลัย',
                'queue_no' => 2,
                'status' => 'CONFIRMED',
                'registered_at' => '2026-10-02 10:15:00',
            ],
            [
                'registration_no' => 'PUB-20261023-0003',
                'event_id' => $eventId,
                'applicant_type' => 'GENERAL',
                'citizen_id' => '1101500654323',
                'student_id' => null,
                'degree_level' => null,
                'faculty' => null,
                'org_unit_id' => $orgUnitId,
                'program_name' => null,
                'prefix' => 'นางสาว',
                'first_name' => 'นภัสวรรณ',
                'last_name' => 'รักษ์ธรรม',
                'buddhist_name' => null,
                'full_name' => 'นางสาวนภัสวรรณ รักษ์ธรรม',
                'gender' => 'FEMALE',
                'age' => 29,
                'vassa' => 0,
                'phone' => '092-444-1122',
                'email' => 'napassawan.r@outlook.com',
                'line_id' => 'napas_mind',
                'province' => 'ปทุมธานี',
                'postal_code' => '12120',
                'address' => '88/12 หมู่ 4 ต.คลองหนึ่ง',
                'subdistrict' => 'คลองหนึ่ง',
                'district' => 'คลองหลวง',
                'emergency_contact' => 'นายวิเชียร รักษ์ธรรม (บิดา)',
                'emergency_phone' => '092-444-1120',
                'congenital_disease' => 'ภูมิแพ้อากาศ (พกยาแก้แพ้ส่วนตัว)',
                'dietary_restriction' => 'อาหารเจ',
                'room_info' => 'อาคาร 74 ปี ห้อง 305',
                'vehicle_info' => 'รถโดยสารประจำทาง',
                'queue_no' => 3,
                'status' => 'WAITING_LIST',
                'registered_at' => '2026-10-03 14:20:00',
            ],
            [
                'registration_no' => 'PUB-20261023-0004',
                'event_id' => $eventId,
                'applicant_type' => 'GENERAL',
                'citizen_id' => '1509900543214',
                'student_id' => null,
                'degree_level' => null,
                'faculty' => null,
                'org_unit_id' => $orgUnitId,
                'program_name' => null,
                'prefix' => 'นาย',
                'first_name' => 'สิรภพ',
                'last_name' => 'เชียงคำ',
                'buddhist_name' => null,
                'full_name' => 'นายสิรภพ เชียงคำ',
                'gender' => 'MALE',
                'age' => 52,
                'vassa' => 0,
                'phone' => '089-665-4321',
                'email' => 'siraphop.ck@yahoo.com',
                'line_id' => 'siraphop_c',
                'province' => 'เชียงใหม่',
                'postal_code' => '50200',
                'address' => '45 หมู่ 2 ต.สุเทพ',
                'subdistrict' => 'สุเทพ',
                'district' => 'เมืองเชียงใหม่',
                'emergency_contact' => 'นางพิมพา เชียงคำ (ภรรยา)',
                'emergency_phone' => '089-665-4320',
                'congenital_disease' => 'ความดันโลหิตสูง (มียาประจำตัว)',
                'dietary_restriction' => 'อาหารทั่วไป',
                'room_info' => 'อาคาร 74 ปี ห้อง 201',
                'vehicle_info' => 'รถไฟ / ต่อรถตู้',
                'queue_no' => 4,
                'status' => 'ATTENDED',
                'registered_at' => '2026-10-04 11:00:00',
            ],
            [
                'registration_no' => 'PUB-20261023-0005',
                'event_id' => $eventId,
                'applicant_type' => 'GENERAL',
                'citizen_id' => '1400200345678',
                'student_id' => null,
                'degree_level' => null,
                'faculty' => null,
                'org_unit_id' => $orgUnitId,
                'program_name' => null,
                'prefix' => 'นาย',
                'first_name' => 'ธนากร',
                'last_name' => 'สุขสมบูรณ์',
                'buddhist_name' => null,
                'full_name' => 'นายธนากร สุขสมบูรณ์',
                'gender' => 'MALE',
                'age' => 35,
                'vassa' => 0,
                'phone' => '085-333-2211',
                'email' => 'thanakorn.s@gmail.com',
                'line_id' => 'thanakorn_s',
                'province' => 'นนทบุรี',
                'postal_code' => '11000',
                'address' => '19/88 ซอยงามวงศ์วาน 23',
                'subdistrict' => 'บางเขน',
                'district' => 'เมืองนนทบุรี',
                'emergency_contact' => 'นางวรรณา สุขสมบูรณ์ (มารดา)',
                'emergency_phone' => '085-333-2200',
                'congenital_disease' => 'ไม่มี',
                'dietary_restriction' => 'อาหารทั่วไป',
                'room_info' => 'อาคาร 74 ปี ห้อง 202',
                'vehicle_info' => 'รถยนต์ส่วนบุคคล',
                'queue_no' => 5,
                'status' => 'PENDING',
                'registered_at' => '2026-10-05 16:45:00',
            ],
        ];

        foreach ($samples as $s) {
            PublicRegistration::updateOrCreate(
                ['registration_no' => $s['registration_no']],
                $s
            );
        }

        // Update confirmed count on event
        $cnt = PublicRegistration::where('event_id', $eventId)->where('status', 'CONFIRMED')->count();
        PublicEvent::where('id', $eventId)->update(['confirmed_count' => $cnt]);
    }

    public function newsIndex(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $query = NewsArticle::with('organizationUnit');

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = $this->getPerPage($request);
        // Action Dispatcher for Shared Hosting Compatibility (via /admin/news.php?action=...)
        $action = $request->input('action');
        if ($action === 'store' && $request->isMethod('post')) {
            return $this->newsStore($request);
        }
        if ($action === 'update' && $request->isMethod('post')) {
            $id = $request->input('id');
            return $this->newsUpdate($request, $id);
        }
        if ($action === 'delete') {
            $id = $request->input('id');
            return $this->newsDelete($id);
        }
        if ($action === 'toggle_pin') {
            $id = $request->input('id');
            return $this->newsTogglePin($id);
        }

        $newsList = $query->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('admin.news_index', compact('newsList', 'orgUnits', 'admin', 'isCentralOrSuper'));
    }

    public function newsStore(Request $request)
    {
        $this->checkAuth();

        // Handle update action sent via POST /admin/news.php?action=update or POST with action=update
        if ($request->input('action') === 'update') {
            $updateId = $request->input('id');
            if ($updateId) {
                return $this->newsUpdate($request, $updateId);
            }
        }

        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:ANNOUNCEMENT,MEDITATION,ACADEMIC,GENERAL',
            'status' => 'required|in:DRAFT,PUBLISHED,ARCHIVED',
            'is_pinned' => 'nullable|boolean',
            'cover_image' => 'nullable|string|max:500',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gallery_urls' => 'nullable|string',
            'org_unit_id' => 'nullable|integer',
        ]);

        $coverImageUrl = $validated['cover_image'] ?? null;
        if ($request->hasFile('cover_file') && $request->file('cover_file')->isValid()) {
            $path = $request->file('cover_file')->store('news', 'public');
            $coverImageUrl = Storage::url($path);
        }

        // Process Gallery Images
        $galleryImages = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if ($file && $file->isValid()) {
                    $gPath = $file->store('news/gallery', 'public');
                    $galleryImages[] = Storage::url($gPath);
                }
            }
        }
        if (!empty($validated['gallery_urls'])) {
            $urls = array_filter(array_map('trim', explode("\n", $validated['gallery_urls'])));
            foreach ($urls as $u) {
                if (filter_var($u, FILTER_VALIDATE_URL) || str_starts_with($u, '/')) {
                    $galleryImages[] = $u;
                }
            }
        }

        $orgUnitId = $isCentralOrSuper ? (!empty($validated['org_unit_id']) ? $validated['org_unit_id'] : null) : ($admin['org_unit_id'] ?? null);

        // Auto-translate to English
        $titleEn = TranslationService::translateToEnglish($validated['title']);
        $contentEn = TranslationService::translateToEnglish($validated['content']);

        NewsArticle::create([
            'org_unit_id' => $orgUnitId,
            'title' => $validated['title'],
            'title_en' => $titleEn,
            'content' => $validated['content'],
            'content_en' => $contentEn,
            'cover_image' => $coverImageUrl,
            'gallery_images' => !empty($galleryImages) ? $galleryImages : null,
            'category' => $validated['category'],
            'is_pinned' => $request->has('is_pinned') ? 1 : 0,
            'status' => $validated['status'],
            'views' => 0,
            'published_at' => now(),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'บันทึกและเผยแพร่ข่าวสารเรียบร้อยแล้ว (พร้อมแปลภาษาอังกฤษอัตโนมัติ)');
    }

    public function newsUpdate(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $article = NewsArticle::findOrFail($id);

        if (!$isCentralOrSuper && $article->org_unit_id != $admin['org_unit_id']) {
            return redirect()->route('admin.news.index')->with('error', 'ท่านไม่มีสิทธิ์แก้ไขข่าวสารของหน่วยงานอื่น');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:ANNOUNCEMENT,MEDITATION,ACADEMIC,GENERAL',
            'status' => 'required|in:DRAFT,PUBLISHED,ARCHIVED',
            'is_pinned' => 'nullable|boolean',
            'cover_image' => 'nullable|string|max:500',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gallery_urls' => 'nullable|string',
            'existing_gallery' => 'nullable|array',
            'org_unit_id' => 'nullable|integer',
        ]);

        $coverImageUrl = $validated['cover_image'] ?? $article->cover_image;
        if ($request->hasFile('cover_file') && $request->file('cover_file')->isValid()) {
            $path = $request->file('cover_file')->store('news', 'public');
            $coverImageUrl = Storage::url($path);
        }

        // Process Gallery Images (keep selected existing + add newly uploaded or URLs)
        $currentGallery = $request->input('existing_gallery', []);
        if (!is_array($currentGallery)) {
            $currentGallery = [];
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if ($file && $file->isValid()) {
                    $gPath = $file->store('news/gallery', 'public');
                    $currentGallery[] = Storage::url($gPath);
                }
            }
        }

        if (!empty($validated['gallery_urls'])) {
            $urls = array_filter(array_map('trim', explode("\n", $validated['gallery_urls'])));
            foreach ($urls as $u) {
                if (filter_var($u, FILTER_VALIDATE_URL) || str_starts_with($u, '/')) {
                    $currentGallery[] = $u;
                }
            }
        }

        // Auto-translate if title/content changed
        $titleEn = ($article->title !== $validated['title'] || empty($article->title_en))
            ? TranslationService::translateToEnglish($validated['title'])
            : $article->title_en;

        $contentEn = ($article->content !== $validated['content'] || empty($article->content_en))
            ? TranslationService::translateToEnglish($validated['content'])
            : $article->content_en;

        $updateData = [
            'title' => $validated['title'],
            'title_en' => $titleEn,
            'content' => $validated['content'],
            'content_en' => $contentEn,
            'cover_image' => $coverImageUrl,
            'gallery_images' => !empty($currentGallery) ? array_values(array_unique($currentGallery)) : null,
            'category' => $validated['category'],
            'is_pinned' => $request->has('is_pinned') ? 1 : 0,
            'status' => $validated['status'],
        ];

        if ($isCentralOrSuper) {
            $updateData['org_unit_id'] = !empty($validated['org_unit_id']) ? $validated['org_unit_id'] : null;
        }

        $article->update($updateData);

        return redirect()->route('admin.news.index')->with('success', 'อัปเดตข่าวสารประชาสัมพันธ์เรียบร้อยแล้ว');
    }

    public function newsDelete($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $article = NewsArticle::findOrFail($id);

        if (!$isCentralOrSuper && $article->org_unit_id != $admin['org_unit_id']) {
            return redirect()->route('admin.news.index')->with('error', 'ท่านไม่มีสิทธิ์ลบข่าวสารของหน่วยงานอื่น');
        }

        $article->delete();

        return redirect()->route('admin.news.index')->with('success', 'ลบข่าวสารเรียบร้อยแล้ว');
    }

    public function newsTogglePin($id)
    {
        $this->checkAuth();
        $article = NewsArticle::findOrFail($id);
        $article->is_pinned = $article->is_pinned ? 0 : 1;
        $article->save();

        return redirect()->route('admin.news.index')->with('success', $article->is_pinned ? 'ปักหมุดข่าวแล้ว' : 'ยกเลิกการปักหมุดข่าวแล้ว');
    }

    // ==========================================
    // ระบบประกาศและเอกสารแนบ (Announcements & Document Attachments)
    // ==========================================
    public function announcementsIndex(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $query = Announcement::with('organizationUnit');

        if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
            $query->where('org_unit_id', $admin['org_unit_id']);
        }

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = $this->getPerPage($request);

        // Action Dispatcher for Shared Hosting Compatibility
        $action = $request->input('action');
        if ($action === 'store' && $request->isMethod('post')) {
            return $this->announcementsStore($request);
        }
        if ($action === 'update' && $request->isMethod('post')) {
            $id = $request->input('id');
            return $this->announcementsUpdate($request, $id);
        }
        if ($action === 'delete') {
            $id = $request->input('id');
            return $this->announcementsDelete($id);
        }
        if ($action === 'toggle_pin') {
            $id = $request->input('id');
            return $this->announcementsTogglePin($id);
        }

        $announcementsList = $query->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('admin.announcements_index', compact('announcementsList', 'orgUnits', 'admin', 'isCentralOrSuper'));
    }

    public function announcementsStore(Request $request)
    {
        $this->checkAuth();

        if ($request->input('action') === 'update') {
            $updateId = $request->input('id');
            if ($updateId) {
                return $this->announcementsUpdate($request, $updateId);
            }
        }

        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'announcement_no' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'category' => 'required|in:PARTICIPANTS,SCHEDULE,ORDER,GENERAL',
            'status' => 'required|in:DRAFT,PUBLISHED,ARCHIVED',
            'is_pinned' => 'nullable|boolean',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:20480',
            'attachment_text' => 'nullable|string',
            'org_unit_id' => 'nullable|integer',
        ]);

        $attachmentPath = null;
        $attachmentName = null;
        $attachmentSize = null;
        $attachmentMime = null;
        $attachmentText = $validated['attachment_text'] ?? null;

        if ($request->hasFile('attachment_file') && $request->file('attachment_file')->isValid()) {
            $file = $request->file('attachment_file');
            $attachmentName = $file->getClientOriginalName();
            $bytes = $file->getSize();
            $attachmentSize = $this->formatBytes($bytes);
            $attachmentMime = $file->getClientMimeType() ?: 'application/pdf';

            $path = $file->store('announcements', 'public');
            $attachmentPath = Storage::url($path);

            // Auto-extract text from PDF if uploaded and user didn't specify extra keywords
            if (str_contains($attachmentMime, 'pdf') || str_ends_with(strtolower($attachmentName), '.pdf')) {
                $fullDiskPath = storage_path('app/public/' . $path);
                $extracted = PdfTextExtractor::extractText($fullDiskPath);
                if (!empty($extracted)) {
                    $attachmentText = trim(($attachmentText ? $attachmentText . "\n" : '') . $extracted);
                }
            }
        }

        $orgUnitId = $isCentralOrSuper ? (!empty($validated['org_unit_id']) ? $validated['org_unit_id'] : null) : ($admin['org_unit_id'] ?? null);

        // Auto-translate to English
        $titleEn = TranslationService::translateToEnglish($validated['title']);
        $contentEn = !empty($validated['content']) ? TranslationService::translateToEnglish($validated['content']) : null;

        $anc = Announcement::create([
            'org_unit_id' => $orgUnitId,
            'announcement_no' => $validated['announcement_no'] ?? null,
            'title' => $validated['title'],
            'title_en' => $titleEn,
            'content' => $validated['content'] ?? null,
            'content_en' => $contentEn,
            'category' => $validated['category'],
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_size' => $attachmentSize,
            'attachment_mime' => $attachmentMime,
            'attachment_text' => $attachmentText,
            'is_pinned' => $request->has('is_pinned') ? 1 : 0,
            'status' => $validated['status'],
            'views' => 0,
            'published_at' => now(),
            'created_at' => now(),
        ]);

        ActivityLogger::log('CREATE', 'ANNOUNCEMENTS', "สร้างประกาศใหม่: {$anc->title}" . ($anc->announcement_no ? " (เลขที่ {$anc->announcement_no})" : ""), [
            'id' => $anc->id,
            'category' => $anc->category,
            'has_attachment' => !empty($attachmentPath),
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'บันทึกและเผยแพร่ประกาศเรียบร้อยแล้ว (พร้อมสกัดข้อมูลและแปลภาษาอังกฤษอัตโนมัติ)');
    }

    public function announcementsUpdate(Request $request, $id = null)
    {
        $this->checkAuth();
        if (!$id) {
            $id = $request->input('id');
        }
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $announcement = Announcement::findOrFail($id);

        if (!$isCentralOrSuper && $announcement->org_unit_id != $admin['org_unit_id']) {
            return redirect()->route('admin.announcements.index')->with('error', 'ท่านไม่มีสิทธิ์แก้ไขประกาศของหน่วยงานอื่น');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'announcement_no' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'category' => 'required|in:PARTICIPANTS,SCHEDULE,ORDER,GENERAL',
            'status' => 'required|in:DRAFT,PUBLISHED,ARCHIVED',
            'is_pinned' => 'nullable|boolean',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:20480',
            'attachment_text' => 'nullable|string',
            'remove_attachment' => 'nullable|boolean',
            'org_unit_id' => 'nullable|integer',
        ]);

        $attachmentPath = $announcement->attachment_path;
        $attachmentName = $announcement->attachment_name;
        $attachmentSize = $announcement->attachment_size;
        $attachmentMime = $announcement->attachment_mime;
        $attachmentText = $validated['attachment_text'] ?? $announcement->attachment_text;

        if ($request->has('remove_attachment') && $request->input('remove_attachment')) {
            $attachmentPath = null;
            $attachmentName = null;
            $attachmentSize = null;
            $attachmentMime = null;
            $attachmentText = null;
        }

        if ($request->hasFile('attachment_file') && $request->file('attachment_file')->isValid()) {
            $file = $request->file('attachment_file');
            $attachmentName = $file->getClientOriginalName();
            $bytes = $file->getSize();
            $attachmentSize = $this->formatBytes($bytes);
            $attachmentMime = $file->getClientMimeType() ?: 'application/pdf';

            $path = $file->store('announcements', 'public');
            $attachmentPath = Storage::url($path);

            if (str_contains($attachmentMime, 'pdf') || str_ends_with(strtolower($attachmentName), '.pdf')) {
                $fullDiskPath = storage_path('app/public/' . $path);
                $extracted = PdfTextExtractor::extractText($fullDiskPath);
                if (!empty($extracted)) {
                    $attachmentText = trim(($attachmentText ? $attachmentText . "\n" : '') . $extracted);
                }
            }
        }

        $titleEn = TranslationService::translateToEnglish($validated['title']);
        $contentEn = !empty($validated['content']) ? TranslationService::translateToEnglish($validated['content']) : null;

        $updateData = [
            'announcement_no' => $validated['announcement_no'] ?? null,
            'title' => $validated['title'],
            'title_en' => $titleEn,
            'content' => $validated['content'] ?? null,
            'content_en' => $contentEn,
            'category' => $validated['category'],
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_size' => $attachmentSize,
            'attachment_mime' => $attachmentMime,
            'attachment_text' => $attachmentText,
            'is_pinned' => $request->has('is_pinned') ? 1 : 0,
            'status' => $validated['status'],
        ];

        if ($isCentralOrSuper) {
            $updateData['org_unit_id'] = !empty($validated['org_unit_id']) ? $validated['org_unit_id'] : null;
        }

        $announcement->update($updateData);

        ActivityLogger::log('UPDATE', 'ANNOUNCEMENTS', "แก้ไขประกาศ: {$announcement->title}", [
            'id' => $announcement->id,
            'category' => $announcement->category,
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'อัปเดตประกาศและเอกสารแนบเรียบร้อยแล้ว');
    }

    public function announcementsDelete($id = null)
    {
        $this->checkAuth();
        if (!$id) {
            $id = request('id');
        }
        $admin = Session::get('admin_user');
        $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

        $announcement = Announcement::findOrFail($id);

        if (!$isCentralOrSuper && $announcement->org_unit_id != $admin['org_unit_id']) {
            return redirect()->route('admin.announcements.index')->with('error', 'ท่านไม่มีสิทธิ์ลบประกาศของหน่วยงานอื่น');
        }

        $title = $announcement->title;
        $announcement->delete();

        ActivityLogger::log('DELETE', 'ANNOUNCEMENTS', "ลบประกาศ: {$title}", ['id' => $id]);

        return redirect()->route('admin.announcements.index')->with('success', 'ลบประกาศเรียบร้อยแล้ว');
    }

    public function announcementsTogglePin($id = null)
    {
        $this->checkAuth();
        if (!$id) {
            $id = request('id');
        }
        $announcement = Announcement::findOrFail($id);
        $announcement->is_pinned = $announcement->is_pinned ? 0 : 1;
        $announcement->save();

        return redirect()->route('admin.announcements.index')->with('success', $announcement->is_pinned ? 'ปักหมุดประกาศแล้ว' : 'ยกเลิกการปักหมุดประกาศแล้ว');
    }

    private function formatBytes($bytes, $precision = 1)
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $base = log($bytes, 1024);
        $floor = floor($base);
        return round(pow(1024, $base - $floor), $precision) . ' ' . ($units[$floor] ?? 'MB');
    }

    // ==========================================
    // Rule Matrix: จัดการผู้ใช้งานและกำหนดสิทธิ์ (User & Role Management)
    // ==========================================
    public function usersIndex(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        
        // เฉพาะ SUPER_ADMIN และ CENTRAL_OFFICER เท่านั้นที่มีสิทธิ์เข้าจัดการผู้ใช้งาน
        if (!in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER'])) {
            return redirect()->route('admin.dashboard')->with('error', 'ท่านไม่มีสิทธิ์เข้าถึงเมนูจัดการผู้ใช้งานและกำหนดสิทธิ์');
        }

        $action = $request->input('action');
        if ($action === 'store') {
            return $this->userStore($request);
        }
        if ($action === 'update') {
            return $this->userUpdate($request, $request->input('id'));
        }
        if ($action === 'toggle_status') {
            return $this->userToggleStatus($request->input('id'));
        }
        if ($action === 'delete') {
            return $this->userDelete($request->input('id'));
        }

        $query = User::with('organizationUnit')->orderBy('role', 'asc')->orderBy('id', 'asc');

        if ($request->filled('role_filter')) {
            $query->where('role', $request->input('role_filter'));
        }

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function($q) use ($s) {
                $q->where('username', 'LIKE', "%{$s}%")
                  ->orWhere('full_name', 'LIKE', "%{$s}%")
                  ->orWhere('email', 'LIKE', "%{$s}%");
            });
        }

        $perPage = $this->getPerPage($request);
        $users = $query->paginate($perPage)->withQueryString();
        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('admin.users_index', compact('users', 'orgUnits'));
    }

    public function userStore(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        if (!in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER'])) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์เพิ่มผู้ใช้งาน');
        }

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'role' => 'required|in:SUPER_ADMIN,CENTRAL_OFFICER,CAMPUS_ADMIN,EXECUTIVE',
            'org_unit_id' => 'nullable|integer|exists:organization_units,id',
            'is_active' => 'nullable|boolean',
        ]);

        User::create([
            'username' => trim($validated['username']),
            'password_hash' => password_hash($validated['password'], PASSWORD_BCRYPT),
            'full_name' => trim($validated['full_name']),
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'],
            'org_unit_id' => $validated['role'] === 'CAMPUS_ADMIN' ? $validated['org_unit_id'] : ($validated['org_unit_id'] ?? 1),
            'is_active' => $request->has('is_active') ? 1 : 1,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'เพิ่มผู้ใช้งานและกำหนดสิทธิ์สำเร็จเรียบร้อยแล้ว');
    }

    public function userUpdate(Request $request, $id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        if (!in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER'])) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์แก้ไขผู้ใช้งาน');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'role' => 'required|in:SUPER_ADMIN,CENTRAL_OFFICER,CAMPUS_ADMIN,EXECUTIVE',
            'org_unit_id' => 'nullable|integer|exists:organization_units,id',
            'is_active' => 'nullable|boolean',
        ]);

        $updateData = [
            'username' => trim($validated['username']),
            'full_name' => trim($validated['full_name']),
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'],
            'org_unit_id' => $validated['role'] === 'CAMPUS_ADMIN' ? $validated['org_unit_id'] : ($validated['org_unit_id'] ?? 1),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if (!empty($validated['password'])) {
            $updateData['password_hash'] = password_hash($validated['password'], PASSWORD_BCRYPT);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'อัปเดตข้อมูลผู้ใช้งานและสิทธิ์เรียบร้อยแล้ว');
    }

    public function userToggleStatus($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        if (!in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER'])) {
            return back()->with('error', 'ท่านไม่มีสิทธิ์ระงับผู้ใช้งาน');
        }

        $user = User::findOrFail($id);
        if ($user->id === $admin['id']) {
            return back()->with('error', 'ไม่สามารถปิดการใช้งานบัญชีที่กำลังล็อกอินอยู่ได้');
        }

        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', $user->is_active ? 'เปิดใช้งานบัญชีผู้ใช้แล้ว' : 'ระงับการใช้งานบัญชีผู้ใช้แล้ว');
    }

    public function userDelete($id)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');
        if ($admin['role'] !== 'SUPER_ADMIN') {
            return back()->with('error', 'เฉพาะผู้ดูแลระบบส่วนกลางระดับสูงสุด (Super Admin) เท่านั้นที่มีสิทธิ์ลบบัญชีผู้ใช้');
        }

        $user = User::findOrFail($id);
        if ($user->id === $admin['id']) {
            return back()->with('error', 'ไม่สามารถลบบัญชีที่กำลังล็อกอินอยู่ได้');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'ลบบัญชีผู้ใช้งานเรียบร้อยแล้ว');
    }

    /**
     * Module: ระบบสถิติเปรียบเทียบและการวิเคราะห์สำหรับผู้บริหาร (Executive Analytics & Comparative Dashboard)
     * สิทธิ์ตาม Rule Matrix: ผู้บริหาร (ดู Dashboard ภาพรวม, ดูสถิติระดับส่วนงาน, ดูสถิติระดับประเทศ, เปรียบเทียบข้อมูลระหว่างส่วนงาน)
     */
    public function executiveAnalytics(Request $request)
    {
        $this->checkAuth();
        $admin = Session::get('admin_user');

        // สถิติระดับประเทศ (National Aggregate Stats)
        $totalUgNation = UgRegistration::count();
        $totalUgPassNation = UgRegistration::where('status', 'COMPLETED')->count();
        $totalGradNation = GradStudent::count();
        $totalGradApprovedNation = GradStudent::where('submission_status', 'APPROVED')->count();
        $totalPublicNation = PublicRegistration::count();
        $totalEventsNation = PublicEvent::count();

        // คำนวณอัตราความสำเร็จระดับประเทศ (%)
        $ugPassRateNation = $totalUgNation > 0 ? round(($totalUgPassNation / $totalUgNation) * 100, 1) : 0;
        $gradApprovedRateNation = $totalGradNation > 0 ? round(($totalGradApprovedNation / $totalGradNation) * 100, 1) : 0;

        // สถิติจำแนกตามประเภทส่วนงาน (CENTRAL, CAMPUS, SANGHA_COLLEGE, ACADEMIC_UNIT)
        $statsByType = OrganizationUnit::select('type', DB::raw('count(*) as org_count'))
            ->where('is_active', 1)
            ->groupBy('type')
            ->get()
            ->map(function ($t) {
                $orgIds = OrganizationUnit::where('type', $t->type)->pluck('id');
                $t->ug_count = UgRegistration::whereIn('org_unit_id', $orgIds)->count();
                $t->ug_completed = UgRegistration::whereIn('org_unit_id', $orgIds)->where('status', 'COMPLETED')->count();
                $t->grad_count = GradStudent::whereIn('org_unit_id', $orgIds)->count();
                $t->grad_approved = GradStudent::whereIn('org_unit_id', $orgIds)->where('submission_status', 'APPROVED')->count();
                $t->public_count = PublicRegistration::whereHas('event', function ($q) use ($orgIds) {
                    $q->whereIn('org_unit_id', $orgIds);
                })->count();
                return $t;
            });

        // รายการส่วนงานทั้งหมดสำหรับการเปรียบเทียบ (52 ส่วนงาน)
        $allOrgUnits = OrganizationUnit::orderedForSelect()->get();

        // ดึงสถิติรวมของทุกส่วนงานแบบ Group By ครั้งเดียว (Single Query Batching ป้องกัน N+1)
        $ugTotals = UgRegistration::select('org_unit_id', DB::raw('count(*) as total'), DB::raw("sum(case when status = 'COMPLETED' then 1 else 0 end) as completed"), DB::raw("sum(case when status in ('CHECKED_IN', 'COMPLETED') then 1 else 0 end) as checkin"))
            ->groupBy('org_unit_id')
            ->get()
            ->keyBy('org_unit_id');

        $gradTotals = GradStudent::select('org_unit_id', DB::raw('count(*) as total'), DB::raw("sum(case when degree_level = 'MASTER' then 1 else 0 end) as master_cnt"), DB::raw("sum(case when degree_level = 'DOCTORAL' then 1 else 0 end) as doc_cnt"), DB::raw("sum(case when submission_status = 'APPROVED' then 1 else 0 end) as approved_cnt"), DB::raw("sum(case when submission_status in ('ACCUMULATING', 'SUBMITTED') then 1 else 0 end) as accumulating_cnt"))
            ->groupBy('org_unit_id')
            ->get()
            ->keyBy('org_unit_id');

        $publicTotals = PublicRegistration::join('public_events', 'public_registrations.event_id', '=', 'public_events.id')
            ->select('public_events.org_unit_id', DB::raw('count(*) as total'))
            ->groupBy('public_events.org_unit_id')
            ->get()
            ->keyBy('org_unit_id');

        $eventTotals = PublicEvent::select('org_unit_id', DB::raw('count(*) as total'))
            ->groupBy('org_unit_id')
            ->get()
            ->keyBy('org_unit_id');

        $getOrgMetrics = function ($orgId) use ($ugTotals, $gradTotals, $publicTotals, $eventTotals) {
            if (!$orgId) return null;
            $ug = $ugTotals->get($orgId);
            $grad = $gradTotals->get($orgId);
            $pub = $publicTotals->get($orgId);
            $evt = $eventTotals->get($orgId);

            $ugTotal = $ug ? (int)$ug->total : 0;
            $ugCompleted = $ug ? (int)$ug->completed : 0;
            $ugCheckin = $ug ? (int)$ug->checkin : 0;
            
            $gradTotal = $grad ? (int)$grad->total : 0;
            $gradMaster = $grad ? (int)$grad->master_cnt : 0;
            $gradDoctoral = $grad ? (int)$grad->doc_cnt : 0;
            $gradApproved = $grad ? (int)$grad->approved_cnt : 0;
            $gradAccumulating = $grad ? (int)$grad->accumulating_cnt : 0;

            $publicTotal = $pub ? (int)$pub->total : 0;
            $eventsCount = $evt ? (int)$evt->total : 0;

            $ugPassRate = $ugTotal > 0 ? round(($ugCompleted / $ugTotal) * 100, 1) : 0;
            $gradPassRate = $gradTotal > 0 ? round(($gradApproved / $gradTotal) * 100, 1) : 0;

            return [
                'ug_total' => $ugTotal,
                'ug_completed' => $ugCompleted,
                'ug_checkin' => $ugCheckin,
                'ug_pass_rate' => $ugPassRate,
                'grad_total' => $gradTotal,
                'grad_master' => $gradMaster,
                'grad_doctoral' => $gradDoctoral,
                'grad_approved' => $gradApproved,
                'grad_accumulating' => $gradAccumulating,
                'grad_pass_rate' => $gradPassRate,
                'public_total' => $publicTotal,
                'events_count' => $eventsCount,
                'grand_total' => $ugTotal + $gradTotal + $publicTotal,
            ];
        };

        // ตัวเลือกการเปรียบเทียบส่วนงาน (Comparative Analysis: Org A vs Org B)
        $selectedOrgAId = $request->input('org_a', 10); // default: วิทยาเขตเชียงใหม่
        $selectedOrgBId = $request->input('org_b', 11); // default: วิทยาเขตขอนแก่น

        $orgA = OrganizationUnit::find($selectedOrgAId);
        $orgB = OrganizationUnit::find($selectedOrgBId);

        $metricsA = $getOrgMetrics($selectedOrgAId);
        $metricsB = $getOrgMetrics($selectedOrgBId);

        // อันดับสูงสุดของส่วนงานที่มีผู้เข้าร่วมปฏิบัติวิปัสสนามากที่สุด Top 10 (Ranking)
        $topOrgs = $allOrgUnits
            ->map(function ($org) use ($getOrgMetrics) {
                $m = $getOrgMetrics($org->id);
                $org->metrics = $m;
                return $org;
            })
            ->filter(function ($org) {
                return $org->metrics['grand_total'] > 0;
            })
            ->sortByDesc(function ($org) {
                return $org->metrics['grand_total'];
            })
            ->values();

        return view('admin.executive_analytics', compact(
            'totalUgNation',
            'totalUgPassNation',
            'totalGradNation',
            'totalGradApprovedNation',
            'totalPublicNation',
            'totalEventsNation',
            'ugPassRateNation',
            'gradApprovedRateNation',
            'statsByType',
            'allOrgUnits',
            'selectedOrgAId',
            'selectedOrgBId',
            'orgA',
            'orgB',
            'metricsA',
            'metricsB',
            'topOrgs'
        ));
    }

    protected function getPerPage(Request $request, $default = 20)
    {
        $perPage = strtolower((string)$request->input('per_page', $default));
        if ($perPage === 'all') {
            return 999999;
        }
        $val = (int)$perPage;
        if (in_array($val, [10, 20, 50, 100])) {
            return $val;
        }
        return $default;
    }

    // Organization Units Master Directory (ภายใต้กลุ่ม การตั้งค่า)
    public function orgUnitsIndex(Request $request)
    {
        $this->checkAuth();

        $query = OrganizationUnit::where('is_active', 1)->withCount(['ugRegistrations as ug_count', 'gradStudents as grad_count']);

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('name_th', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('province_th', 'like', "%{$s}%")
                  ->orWhere('province_code', 'like', "%{$s}%");
            });
        }

        if ($request->filled('type')) {
            $type = $request->input('type');
            if (in_array($type, ['COLLEGE', 'SANGHA_COLLEGE'])) {
                $query->whereIn('type', ['COLLEGE', 'SANGHA_COLLEGE']);
            } else {
                $query->where('type', $type);
            }
        }

        $perPage = $this->getPerPage($request, 20);
        $orgUnits = $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();

        $totalOrgs = OrganizationUnit::where('is_active', 1)->count();
        $totalCampuses = OrganizationUnit::where('is_active', 1)->where('type', 'CAMPUS')->count();
        $totalColleges = OrganizationUnit::where('is_active', 1)->whereIn('type', ['COLLEGE', 'SANGHA_COLLEGE'])->count();
        $totalCentral = OrganizationUnit::where('is_active', 1)->where('type', 'CENTRAL')->count();
        $totalAcademicUnits = OrganizationUnit::where('is_active', 1)->where('type', 'ACADEMIC_UNIT')->count();

        return view('admin.org_units', compact('orgUnits', 'totalOrgs', 'totalCampuses', 'totalColleges', 'totalCentral', 'totalAcademicUnits'));
    }

    // Contact Settings & Inquiries Management
    public function contactSettings(Request $request)
    {
        $this->checkAuth();
        
        $settings = SiteSetting::where('setting_group', 'contact')
            ->orderBy('id', 'asc')
            ->get();
            
        $perPage = $this->getPerPage($request, 20);
        $inquiries = ContactInquiry::orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.contact_settings', compact('settings', 'inquiries'));
    }

    public function contactSettingsUpdate(Request $request)
    {
        $this->checkAuth();

        $inputs = $request->except(['_token']);

        foreach ($inputs as $key => $value) {
            SiteSetting::where('setting_key', $key)->update([
                'setting_value' => $value,
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'บันทึกการตั้งค่าข้อมูลติดต่อสอบถามเรียบร้อยแล้ว');
    }

    public function contactInquiryStatus($id, $status)
    {
        $this->checkAuth();

        if (in_array($status, ['NEW', 'READ', 'RESPONDED'])) {
            ContactInquiry::where('id', $id)->update([
                'status' => $status,
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'อัปเดตสถานะข้อความสอบถามเรียบร้อยแล้ว');
    }

    public function contactInquiryDelete($id)
    {
        $this->checkAuth();

        ContactInquiry::where('id', $id)->delete();

        return back()->with('success', 'ลบข้อความสอบถามเรียบร้อยแล้ว');
    }

    // ==========================================
    // Donation Management (ระบบจัดการการบริจาคและสรุปสถิติ)
    // ==========================================
    public function donationsIndex(Request $request)
    {
        $this->checkAuth();

        $action = $request->input('action');
        if ($action === 'export') {
            return $this->donationExport($request);
        }
        if ($action === 'update') {
            return $this->donationUpdate($request, $request->input('id'));
        }
        if ($action === 'status') {
            return $this->donationStatus($request, $request->input('id'));
        }
        if ($action === 'delete') {
            return $this->donationDelete($request->input('id'));
        }
        if ($action === 'restore') {
            return $this->donationRestore($request->input('id'));
        }
        if ($action === 'force_delete') {
            return $this->donationForceDelete($request->input('id'));
        }

        $isTrashTab = ($request->input('tab') === 'trash');
        $query = Donation::with('verifier')->orderBy('created_at', 'desc');

        if ($isTrashTab) {
            $query->onlyTrashed();
        }

        // ฟิลเตอร์สถานะ
        if (!$isTrashTab && $request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }

        // ฟิลเตอร์ลดหย่อนภาษี
        if ($request->filled('tax_deductible') && $request->tax_deductible !== 'ALL') {
            $query->where('is_tax_deductible', $request->tax_deductible == '1');
        }

        // ฟิลเตอร์ช่วงวันที่
        if ($request->filled('date_from')) {
            $query->whereDate('transfer_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transfer_date', '<=', $request->date_to);
        }

        // ค้นหาคำสำคัญ (ชื่อ, เลขผู้เสียภาษี, เลขที่ใบแจ้ง, เบอร์โทร)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('tax_id', 'like', "%{$search}%")
                  ->orWhere('donation_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // จำนวนในถังขยะสำหรับแสดง Badge
        $trashCount = Donation::onlyTrashed()->count();

        // จัดการ Pagination และรายการต่อหน้า
        $perPage = $this->getPerPage($request);
        $donations = $query->paginate($perPage)->withQueryString();

        // สรุปสถิติรวมทั้งหมด (Analytics & Metrics - ไม่นับรายการที่ลบ)
        $metrics = [
            'total_count' => Donation::count(),
            'total_amount' => (float) Donation::sum('amount'),
            'verified_count' => Donation::where('status', 'VERIFIED')->count(),
            'verified_amount' => (float) Donation::where('status', 'VERIFIED')->sum('amount'),
            'pending_count' => Donation::where('status', 'PENDING')->count(),
            'pending_amount' => (float) Donation::where('status', 'PENDING')->sum('amount'),
            'rejected_count' => Donation::where('status', 'REJECTED')->count(),
            'rejected_amount' => (float) Donation::where('status', 'REJECTED')->sum('amount'),
            'tax_deductible_count' => Donation::where('is_tax_deductible', 1)->count(),
            'tax_deductible_amount' => (float) Donation::where('is_tax_deductible', 1)->sum('amount'),
        ];

        // สถิติยอดบริจาคตามเดือน (6 เดือนล่าสุดสำหรับ Chart)
        $monthlyStats = Donation::select(
                DB::raw("DATE_FORMAT(transfer_date, '%Y-%m') as month"),
                DB::raw("COUNT(*) as count"),
                DB::raw("SUM(amount) as total_amount")
            )
            ->whereNotNull('transfer_date')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        // ข้อมูลการตั้งค่าบัญชีรับบริจาค
        $donationSettings = SiteSetting::getByGroup('donation');

        return view('admin.donations_index', compact('donations', 'metrics', 'monthlyStats', 'donationSettings', 'isTrashTab', 'trashCount'));
    }

    public function donationStatus(Request $request, $id)
    {
        $this->checkAuth();

        $donation = Donation::findOrFail($id);
        $status = $request->input('status');
        $adminNotes = $request->input('admin_notes');

        if (!in_array($status, ['PENDING', 'VERIFIED', 'REJECTED'])) {
            return back()->with('error', 'สถานะไม่ถูกต้อง');
        }

        $adminUser = Session::get('admin_user');

        $donation->update([
            'status' => $status,
            'admin_notes' => $adminNotes ?? $donation->admin_notes,
            'verified_by' => in_array($status, ['VERIFIED', 'REJECTED']) ? ($adminUser['id'] ?? null) : null,
            'verified_at' => in_array($status, ['VERIFIED', 'REJECTED']) ? now() : null,
        ]);

        return back()->with('success', "อัปเดตสถานะการบริจาค [{$donation->donation_no}] เป็น {$status} เรียบร้อยแล้ว");
    }

    public function donationUpdate(Request $request, $id)
    {
        $this->checkAuth();

        $donation = Donation::findOrFail($id);

        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:20',
            'is_tax_deductible' => 'nullable|boolean',
            'amount' => 'required|numeric|min:1',
            'bank_account' => 'required|string|max:150',
            'transfer_date' => 'required|date',
            'transfer_time' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:1000',
            'purpose' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:2000',
            'admin_notes' => 'nullable|string|max:1000',
            'status' => 'required|in:PENDING,VERIFIED,REJECTED',
            'slip' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $updateData = [
            'donor_name' => $validated['donor_name'],
            'tax_id' => $validated['tax_id'] ?? null,
            'is_tax_deductible' => $request->boolean('is_tax_deductible'),
            'amount' => $validated['amount'],
            'bank_account' => $validated['bank_account'],
            'transfer_date' => $validated['transfer_date'],
            'transfer_time' => $validated['transfer_time'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'purpose' => $validated['purpose'] ?? null,
            'note' => $validated['note'] ?? null,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'status' => $validated['status'],
        ];

        // หากมีการแนบสลิปใหม่เพิ่มเติม/แทนที่เดิม
        if ($request->hasFile('slip')) {
            // ลบสลิปเก่าหากมี
            if ($donation->slip_path && Storage::disk('public')->exists($donation->slip_path)) {
                Storage::disk('public')->delete($donation->slip_path);
            }
            $slipFile = $request->file('slip');
            $extension = $slipFile->getClientOriginalExtension();
            $filename = 'slip_' . date('Ymd_His') . '_' . uniqid() . '.' . $extension;
            $updateData['slip_path'] = $slipFile->storeAs('donations', $filename, 'public');
        }

        // หากมีการแนบภาพประจำตัวผู้บริจาคใหม่เพิ่มเติม/แทนที่เดิม
        if ($request->hasFile('avatar')) {
            // ลบภาพเก่าหากมี
            if ($donation->avatar_path && Storage::disk('public')->exists($donation->avatar_path)) {
                Storage::disk('public')->delete($donation->avatar_path);
            }
            $avatarFile = $request->file('avatar');
            $extension = $avatarFile->getClientOriginalExtension();
            $filename = 'donor_avatar_' . date('Ymd_His') . '_' . uniqid() . '.' . $extension;
            $updateData['avatar_path'] = $avatarFile->storeAs('donations/avatars', $filename, 'public');
        }

        // หากมีการเปลี่ยนสถานะ
        $adminUser = Session::get('admin_user');
        if (in_array($validated['status'], ['VERIFIED', 'REJECTED'])) {
            $updateData['verified_by'] = $adminUser['id'] ?? null;
            $updateData['verified_at'] = now();
        } else {
            $updateData['verified_by'] = null;
            $updateData['verified_at'] = null;
        }

        $donation->update($updateData);

        return back()->with('success', "แก้ไขและบันทึกข้อมูลการบริจาค [{$donation->donation_no}] เรียบร้อยแล้ว");
    }

    public function donationDelete($id)
    {
        $this->checkAuth();

        $donation = Donation::findOrFail($id);
        $no = $donation->donation_no;
        $name = $donation->donor_name;

        // Soft Delete ไปยังถังขยะ
        $donation->delete();

        return back()->with('success', "ย้ายรายการบริจาค [{$no} - {$name}] ไปยังถังขยะเรียบร้อยแล้ว (สามารถกู้คืนได้)");
    }

    public function donationRestore($id)
    {
        $this->checkAuth();

        $donation = Donation::onlyTrashed()->findOrFail($id);
        $no = $donation->donation_no;
        $name = $donation->donor_name;

        $donation->restore();

        return back()->with('success', "กู้คืนรายการบริจาค [{$no} - {$name}] กลับสู่ระบบเรียบร้อยแล้ว");
    }

    public function donationForceDelete($id)
    {
        $this->checkAuth();

        $donation = Donation::onlyTrashed()->findOrFail($id);
        
        // ลบไฟล์สลิปหากมี
        if ($donation->slip_path && Storage::disk('public')->exists($donation->slip_path)) {
            Storage::disk('public')->delete($donation->slip_path);
        }

        // ลบไฟล์ภาพประจำตัวหากมี
        if ($donation->avatar_path && Storage::disk('public')->exists($donation->avatar_path)) {
            Storage::disk('public')->delete($donation->avatar_path);
        }

        $no = $donation->donation_no;
        $name = $donation->donor_name;
        $donation->forceDelete();

        return back()->with('success', "ลบรายการบริจาค [{$no} - {$name}] ออกจากระบบถาวรเรียบร้อยแล้ว");
    }

    public function donationSettingsUpdate(Request $request)
    {
        $this->checkAuth();

        $keys = [
            'donation_bank_name',
            'donation_account_name',
            'donation_account_number',
            'donation_promptpay',
            'donation_info_notes',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::updateOrInsert(
                    ['setting_key' => $key],
                    [
                        'setting_value' => $request->input($key),
                        'setting_group' => 'donation',
                        'updated_at' => now(),
                    ]
                );
            }
        }

        return back()->with('success', 'บันทึกการตั้งค่าบัญชีรับบริจาคเรียบร้อยแล้ว');
    }

    public function donationExport(Request $request)
    {
        $this->checkAuth();

        $query = Donation::with('verifier')->orderBy('transfer_date', 'desc');

        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }
        if ($request->filled('tax_deductible') && $request->tax_deductible !== 'ALL') {
            $query->where('is_tax_deductible', $request->tax_deductible == '1');
        }

        $donations = $query->get();

        $filename = 'MCU_Donations_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($donations) {
            $output = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel in Thai
            fputs($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'ลำดับ',
                'เลขที่รายการ',
                'ชื่อ-นามสกุล ผู้บริจาค',
                'เลขประจำตัวผู้เสียภาษี',
                'ต้องการลดหย่อนภาษี',
                'จำนวนเงิน (บาท)',
                'บัญชีธนาคารปลายทาง',
                'วันที่โอน',
                'เวลาที่โอน',
                'เบอร์โทรศัพท์',
                'อีเมล',
                'ที่อยู่สำหรับออกใบเสร็จ',
                'วัตถุประสงค์',
                'สถานะ',
                'หมายเหตุเจ้าหน้าที่',
                'วันที่บันทึกระบบ'
            ]);

            $i = 1;
            foreach ($donations as $d) {
                fputcsv($output, [
                    $i++,
                    $d->donation_no,
                    $d->donor_name,
                    $d->tax_id ?? '-',
                    $d->is_tax_deductible ? 'ใช่ (ลดหย่อนภาษี)' : 'ไม่ลดหย่อน',
                    number_format($d->amount, 2, '.', ''),
                    $d->bank_account ?? '-',
                    $d->transfer_date ? $d->transfer_date->format('Y-m-d') : '-',
                    $d->transfer_time ?? '-',
                    $d->phone ?? '-',
                    $d->email ?? '-',
                    $d->address ?? '-',
                    $d->purpose ?? '-',
                    $d->status,
                    $d->admin_notes ?? '-',
                    $d->created_at ? $d->created_at->format('Y-m-d H:i:s') : '-',
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * ระบบจัดการฐานข้อมูลนิสิตส่วนกลาง (Central Student Master)
     */
     public function studentsIndex(Request $request)
     {
         $this->checkAuth();
         $admin = Session::get('admin_user');
         $isSuperAdmin = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

         // Action Dispatcher สำหรับ Shared Hosting Compatibility (POST /students.php?action=bulk หรือมี bulk_action)
         if ($request->input('action') === 'bulk' || $request->has('bulk_action')) {
             return $this->studentsBulkAction($request);
         }

         $query = Student::with('organizationUnit');

         // กรองตามสิทธิ์ส่วนงาน
         if (!$isSuperAdmin && !empty($admin['org_unit_id'])) {
             $query->where('org_unit_id', $admin['org_unit_id']);
         } elseif ($request->filled('filter_org')) {
             $query->where('org_unit_id', $request->filter_org);
         }

         // กรองคำค้นหา
         if ($request->filled('search')) {
             $s = trim($request->search);
             $query->where(function($q) use ($s) {
                 $q->where('student_code', 'like', "%{$s}%")
                   ->orWhere('name', 'like', "%{$s}%")
                   ->orWhere('first_name', 'like', "%{$s}%")
                   ->orWhere('last_name', 'like', "%{$s}%")
                   ->orWhere('chaya', 'like', "%{$s}%")
                   ->orWhere('citizen_id', 'like', "%{$s}%")
                   ->orWhere('faculty', 'like', "%{$s}%")
                   ->orWhere('major', 'like', "%{$s}%")
                   ->orWhere('email', 'like', "%{$s}%")
                   ->orWhere('phone', 'like', "%{$s}%");
             });
         }

         // กรองระดับการศึกษา
         if ($request->filled('degree_level')) {
             $query->where('degree_level', $request->degree_level);
         }

         // กรองสถานะ
         if ($request->filled('status')) {
             $query->where('is_active', $request->status === 'ACTIVE' ? 1 : 0);
         }

         // จำนวนแถวต่อหน้า
         $perPage = $request->input('per_page', 20);
         $pageSize = ($perPage === 'all') ? 5000 : max(10, (int)$perPage);
         $students = $query->orderBy('student_code', 'asc')->paginate($pageSize)->withQueryString();

         $orgUnits = OrganizationUnit::where('is_active', 1)->orderBy('id')->get();

         $stats = [
             'total' => Student::count(),
             'bachelor' => Student::where('degree_level', 'BACHELOR')->count(),
             'master' => Student::where('degree_level', 'MASTER')->count(),
             'doctoral' => Student::where('degree_level', 'DOCTORAL')->count(),
             'certificate' => Student::where('degree_level', 'CERTIFICATE')->count(),
         ];

         return view('admin.students_index', compact('students', 'orgUnits', 'stats', 'isSuperAdmin'));
     }

     /**
      * การจัดการนิสิตทีละหลายรายการ (Bulk Action)
      */
     public function studentsBulkAction(Request $request)
     {
         $this->checkAuth();
         $admin = Session::get('admin_user');
         $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

         $action = $request->input('bulk_action');
         $ids = $request->input('selected_ids', []);

         if (empty($ids) || !is_array($ids)) {
             return back()->with('error', 'กรุณาเลือกรายการนิสิตที่ต้องการดำเนินการอย่างน้อย 1 รายการ');
         }

         $query = Student::whereIn('id', $ids);
         if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
             $query->where('org_unit_id', $admin['org_unit_id']);
         }

         $count = $query->count();
         if ($count === 0) {
             return back()->with('error', 'ไม่พบรายการที่ท่านมีสิทธิ์ดำเนินการ');
         }

         switch ($action) {
             case 'ACTIVATE':
                 $query->update(['is_active' => 1]);
                 ActivityLogger::log('BULK_ACTION', 'STUDENTS', "เปิดใช้งานบัญชีนิสิตจำนวนมาก {$count} รายการ", ['ids' => $ids]);
                 return back()->with('success', "เปิดใช้งานบัญชีนิสิตที่เลือก ({$count} รายการ) สำเร็จเรียบร้อยแล้ว");

             case 'DEACTIVATE':
                 $query->update(['is_active' => 0]);
                 ActivityLogger::log('BULK_ACTION', 'STUDENTS', "ระงับการใช้งานบัญชีนิสิตจำนวนมาก {$count} รายการ", ['ids' => $ids]);
                 return back()->with('success', "ระงับการใช้งานบัญชีนิสิตที่เลือก ({$count} รายการ) เรียบร้อยแล้ว");

             case 'RESET_PASSWORD':
                 $newPass = $request->input('bulk_new_password') ?: 'password';
                 $hashed = Hash::make($newPass);
                 $query->update([
                     'password' => $hashed,
                     'force_password_change' => 1,
                 ]);
                 ActivityLogger::log('BULK_ACTION', 'STUDENTS', "รีเซ็ตรหัสผ่านนิสิตจำนวนมาก {$count} รายการ", ['ids' => $ids]);
                 return back()->with('success', "รีเซ็ตรหัสผ่านนิสิต ({$count} รายการ) เป็น \"{$newPass}\" พร้อมบังคับเปลี่ยนรหัสผ่านเมื่อเข้าสู่ระบบเรียบร้อยแล้ว");

             case 'SET_DEGREE_BACHELOR':
                 $query->update(['degree_level' => 'BACHELOR']);
                 return back()->with('success', "ปรับระดับการศึกษาเป็น ปริญญาตรี ({$count} รายการ) เรียบร้อยแล้ว");

             case 'SET_DEGREE_MASTER':
                 $query->update(['degree_level' => 'MASTER']);
                 return back()->with('success', "ปรับระดับการศึกษาเป็น ปริญญาโท ({$count} รายการ) เรียบร้อยแล้ว");

             case 'SET_DEGREE_DOCTORAL':
                 $query->update(['degree_level' => 'DOCTORAL']);
                 return back()->with('success', "ปรับระดับการศึกษาเป็น ปริญญาเอก ({$count} รายการ) เรียบร้อยแล้ว");

             case 'SET_DEGREE_CERTIFICATE':
                 $query->update(['degree_level' => 'CERTIFICATE']);
                 return back()->with('success', "ปรับระดับการศึกษาเป็น ประกาศนียบัตร ({$count} รายการ) เรียบร้อยแล้ว");

             case 'CHANGE_ORG':
                 $targetOrgId = $request->input('target_org_unit_id');
                 if (!$isCentralOrSuper) {
                     return back()->with('error', 'เฉพาะผู้ดูแลระบบส่วนกลางเท่านั้นที่สามารถเปลี่ยนส่วนงานสังกัดนิสิตได้');
                 }
                 if ($targetOrgId) {
                     $org = OrganizationUnit::find($targetOrgId);
                     $query->update(['org_unit_id' => $targetOrgId]);
                     return back()->with('success', "ย้ายสังกัดส่วนงานนิสิต ({$count} รายการ) ไปยัง \"{$org?->name_th}\" เรียบร้อยแล้ว");
                 }
                 return back()->with('error', 'กรุณาเลือกส่วนงานปลายทางที่ต้องการย้าย');

             case 'DELETE':
                 $studentCodes = (clone $query)->pluck('student_code')->toArray();
                 $query->delete();
                 UgMasterStudent::whereIn('student_code', $studentCodes)->delete();
                 ActivityLogger::log('DELETE', 'STUDENTS', "ลบข้อมูลนิสิตจำนวนมาก {$count} รายการ", ['student_codes' => $studentCodes]);
                 return back()->with('success', "ลบข้อมูลนิสิตที่เลือก ({$count} รายการ) ออกจากฐานข้อมูลเรียบร้อยแล้ว");

             default:
                 return back()->with('error', 'กรุณาเลือกรูปแบบการดำเนินการที่ถูกต้อง');
         }
     }

     /**
      * แก้ไขข้อมูลทางการของนิสิต
      */
     public function studentUpdate(Request $request, $id)
     {
         $this->checkAuth();
         $student = Student::findOrFail($id);

         $request->validate([
             'name' => 'nullable|string|max:255',
             'prefix' => 'nullable|string|max:50',
             'first_name' => 'nullable|string|max:100',
             'last_name' => 'nullable|string|max:100',
             'chaya' => 'nullable|string|max:100',
             'degree_level' => 'required|string|max:50',
             'org_unit_id' => 'nullable|exists:organization_units,id',
             'faculty' => 'nullable|string|max:150',
             'major' => 'nullable|string|max:150',
             'phone' => 'nullable|string|max:30',
             'email' => 'nullable|email|max:150',
             'citizen_id' => 'nullable|string|max:13',
         ]);

         $name = trim($request->input('name', ''));
         if (empty($name)) {
             $name = trim("{$request->prefix}{$request->first_name} {$request->chaya} {$request->last_name}");
         }

         $student->update([
             'name' => $name,
             'prefix' => $request->prefix ?: '',
             'first_name' => $name,
             'last_name' => $request->last_name ?: '',
             'chaya' => $request->chaya ?: '',
             'degree_level' => $request->degree_level,
             'org_unit_id' => $request->org_unit_id,
             'faculty' => $request->faculty,
             'major' => $request->major,
             'phone' => $request->phone,
             'email' => $request->email,
             'citizen_id' => $request->citizen_id,
         ]);

         return back()->with('success', "อัปเดตข้อมูลนิสิต [{$student->student_code}] เรียบร้อยแล้ว");
     }

     /**
      * รีเซ็ตรหัสผ่านนิสิต
      */
     public function studentResetPassword(Request $request, $id)
     {
         $this->checkAuth();
         $student = Student::findOrFail($id);

         $newPass = $request->input('new_password', '12345678');
         if (strlen($newPass) < 6) {
             return back()->with('error', 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร');
         }

         $student->update([
             'password' => Hash::make($newPass),
             'force_password_change' => $request->has('force_change'),
         ]);

         return back()->with('success', "รีเซ็ตรหัสผ่านสำหรับรหัสนิสิต [{$student->student_code}] สำเร็จแล้ว (รหัสผ่านใหม่: {$newPass})");
     }

     /**
      * สลับสถานะเปิดใช้งาน/ระงับบัญชีนิสิต
      */
     public function studentToggleStatus($id)
     {
         $this->checkAuth();
         $student = Student::findOrFail($id);
         $student->update([
             'is_active' => !$student->is_active,
         ]);

         $statusText = $student->is_active ? 'เปิดใช้งาน' : 'ระงับการใช้งาน';
         return back()->with('success', "เปลี่ยนสถานะบัญชีรหัส [{$student->student_code}] เป็น: {$statusText} เรียบร้อยแล้ว");
     }

     /**
      * หน้าจัดการโมดูลระบบ (Feature Flags / System Modules)
      */
     public function modulesIndex(Request $request)
     {
         $this->checkAuth();

         $action = $request->input('action');
         if ($action === 'toggle') {
             return $this->moduleToggle($request);
         }

         $modules = \App\Models\SiteSetting::where('setting_group', 'modules')->get();

         return view('admin.modules_index', compact('modules'));
     }

     /**
      * สลับเปิด/ปิดสถานะโมดูล (Toggle Module Status)
      */
     public function moduleToggle(Request $request)
     {
         $this->checkAuth();
         $key = $request->input('setting_key');
         $current = \App\Models\SiteSetting::get($key, '1');
         $newStatus = ($current === '1') ? '0' : '1';

         \App\Models\SiteSetting::where('setting_key', $key)->update([
             'setting_value' => $newStatus,
             'updated_at' => now(),
         ]);

         $statusText = ($newStatus === '1') ? 'เปิดใช้งาน (Active)' : 'ปิดใช้งาน (Closed / Inactive)';
         ActivityLogger::log('TOGGLE', 'MODULES', "เปลี่ยนสถานะโมดูล [{$key}] เป็น \"{$statusText}\"", ['module_key' => $key, 'new_status' => $newStatus]);

         return back()->with('success', "เปลี่ยนสถานะโมดูล [{$key}] เป็น: {$statusText} เรียบร้อยแล้ว");
     }

     /**
      * แสดงประวัติการใช้งานและกิจกรรมในระบบ (Activity Logs)
      */
     public function activityLogs(Request $request)
     {
         $this->checkAuth();
         $admin = Session::get('admin_user');
         $isCentralOrSuper = in_array($admin['role'], ['SUPER_ADMIN', 'CENTRAL_OFFICER']);

         $query = ActivityLog::with('organizationUnit')->orderBy('created_at', 'desc');

         // สิทธิ์การเข้าถึง: ถ้าเป็นเจ้าหน้าที่วิทยาเขต จะเห็นเฉพาะกิจกรรมของส่วนงานตนเอง
         if (!$isCentralOrSuper && !empty($admin['org_unit_id'])) {
             $query->where('org_unit_id', $admin['org_unit_id']);
         } elseif ($request->filled('filter_org')) {
             $query->where('org_unit_id', $request->input('filter_org'));
         }

         // กรองตาม Module
         if ($request->filled('module')) {
             $query->where('module', $request->input('module'));
         }

         // กรองตาม Action
         if ($request->filled('action_type')) {
             $query->where('action', $request->input('action_type'));
         }

         // กรองตามช่วงวันที่
         if ($request->filled('start_date')) {
             $query->whereDate('created_at', '>=', $request->input('start_date'));
         }
         if ($request->filled('end_date')) {
             $query->whereDate('created_at', '<=', $request->input('end_date'));
         }

         // ค้นหาตามคำอธิบาย, username, หรือ IP
         if ($request->filled('search')) {
             $s = trim($request->input('search'));
             $query->where(function ($q) use ($s) {
                 $q->where('description', 'LIKE', "%{$s}%")
                   ->orWhere('username', 'LIKE', "%{$s}%")
                   ->orWhere('user_fullname', 'LIKE', "%{$s}%")
                   ->orWhere('ip_address', 'LIKE', "%{$s}%");
             });
         }

         $perPage = $this->getPerPage($request);
         $logs = $query->paginate($perPage)->withQueryString();
         $orgUnits = OrganizationUnit::orderedForSelect()->get();

         // สถิติภาพรวม
         $todayCount = ActivityLog::whereDate('created_at', now()->toDateString())->count();
         $loginTodayCount = ActivityLog::whereDate('created_at', now()->toDateString())->where('action', 'LOGIN')->count();
         $changesCount = ActivityLog::whereIn('action', ['CREATE', 'UPDATE', 'DELETE', 'STATUS_CHANGE'])->count();

         return view('admin.activity_logs', compact('logs', 'orgUnits', 'isCentralOrSuper', 'todayCount', 'loginTodayCount', 'changesCount'));
     }

     private function checkAuth()
     {
         if (!Session::has('admin_user')) {
             abort(redirect()->route('login'));
         }
     }
}

