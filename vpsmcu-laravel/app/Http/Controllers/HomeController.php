<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUnit;
use App\Models\NewsArticle;
use App\Models\MeditationCalendar;
use App\Models\UgBatch;
use App\Models\UgRegistration;
use App\Models\PublicEvent;
use App\Models\GradStudent;
use App\Models\PublicRegistration;
use App\Models\SiteSetting;
use App\Models\ContactInquiry;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        $recentNews = NewsArticle::with('organizationUnit')
            ->where('status', 'PUBLISHED')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->limit(8)
            ->get();

        // 1. ดึงกำหนดการและปฏิทินปฏิบัติธรรม ป.ตรี ที่เปิดรับสมัคร (UgBatch)
        $batchQuery = UgBatch::with(['organizationUnit', 'registrations'])
            ->where('status', 'OPEN');

        // 2. ดึงกำหนดการภาคประชาชน ที่เปิดรับสมัคร (PublicEvent)
        $publicEventQuery = PublicEvent::with(['organizationUnit'])
            ->withCount(['registrations as active_registrations_count' => function ($q) {
                $q->whereNotIn('status', ['REJECTED', 'CANCELLED']);
            }])
            ->where('status', 'OPEN');

        if ($request->filled('filter_org')) {
            $filterOrg = $request->input('filter_org');
            $batchQuery->where('org_unit_id', $filterOrg);
            $publicEventQuery->where('org_unit_id', $filterOrg);
        }

        $openBatches = $batchQuery->orderBy('start_date', 'asc')->get();
        $openPublicEvents = $publicEventQuery->orderBy('start_date', 'asc')->get();

        return view('portal.index', compact('orgUnits', 'recentNews', 'openBatches', 'openPublicEvents'));
    }

    public function newsIndex(Request $request)
    {
        $query = NewsArticle::with('organizationUnit')
            ->where('status', 'PUBLISHED');

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('org_unit_id')) {
            $query->where('org_unit_id', $request->input('org_unit_id'));
        }

        $newsList = $query->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('portal.news_index', compact('newsList', 'orgUnits'));
    }

    public function newsDetail(Request $request, $id = null)
    {
        // Support /news/{id}, /news_detail.php?id=1, or legacy /news_detail.php?1
        if (!$id) {
            $id = $request->query('id');
        }

        if (!$id) {
            // Check if query string is just numeric like ?1
            $queryString = $request->getQueryString();
            if ($queryString && is_numeric($queryString)) {
                $id = (int)$queryString;
            } else {
                // Check if any query keys are numeric
                foreach ($request->query() as $key => $val) {
                    if (is_numeric($key)) {
                        $id = (int)$key;
                        break;
                    }
                }
            }
        }

        if (!$id) {
            return redirect()->route('news.index');
        }

        $news = NewsArticle::with('organizationUnit')
            ->where('status', 'PUBLISHED')
            ->findOrFail($id);

        // Increment views
        DB::table('news_articles')->where('id', $id)->increment('views');
        $news->views += 1;

        $relatedNews = NewsArticle::with('organizationUnit')
            ->where('status', 'PUBLISHED')
            ->where('id', '!=', $id)
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        return view('portal.news_detail', compact('news', 'relatedNews'));
    }

    public function verifyCertificate(Request $request)
    {
        $type = $request->query('type');
        $code = $request->query('code');

        $result = null;
        $isValid = false;

        if ($type === 'ug') {
            $record = UgRegistration::with(['batch.organizationUnit', 'organizationUnit'])
                ->where('registration_no', $code)
                ->first();

            if ($record && $record->status === 'COMPLETED') {
                $isValid = true;
                $result = [
                    'type' => 'UG',
                    'title' => 'หนังสือรับรองการปฏิบัติวิปัสสนากรรมฐาน (หลักสูตรปริญญาตรี 10 วัน)',
                    'code' => $record->registration_no,
                    'student_code' => $record->student_code,
                    'name' => ($record->prefix ?? '') . $record->first_name . ' ' . ($record->last_name ?? ''),
                    'faculty' => $record->faculty ?? '-',
                    'major' => $record->major ?? '-',
                    'org_unit' => $record->organizationUnit->name_th ?? 'มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย',
                    'details' => 'โครงการ: ' . ($record->batch->title ?? '-') . ' (ปีการศึกษา ' . ($record->batch->academic_year ?? '-') . ')',
                    'date' => $record->updated_at ? \Carbon\Carbon::parse($record->updated_at)->format('d/m/Y') : '-',
                    'status' => 'ผ่านเกณฑ์สมบูรณ์ (Verified)',
                    'score' => $record->evaluation_score ?? 100,
                ];
            }
        } elseif ($type === 'grad') {
            $record = GradStudent::with('organizationUnit')
                ->where('student_code', $code)
                ->orWhere('student_id', $code)
                ->first();

            if ($record && $record->submission_status === 'APPROVED') {
                $isValid = true;
                $levelTh = $record->degree_level ?: 'ระดับบัณฑิตศึกษา';
                $result = [
                    'type' => 'GRAD',
                    'title' => 'หนังสือรับรองผลการสะสมวันปฏิบัติวิปัสสนากรรมฐาน (' . $levelTh . ')',
                    'code' => 'GRAD-CERT-' . ($record->student_code ?? $record->student_id),
                    'student_code' => $record->student_code ?? $record->student_id,
                    'name' => ($record->prefix ?? '') . ($record->full_name ?? ($record->first_name . ' ' . $record->last_name)),
                    'faculty' => $record->faculty ?? '-',
                    'major' => $record->major ?? ($record->program_name ?? '-'),
                    'org_unit' => $record->organizationUnit->name_th ?? 'บัณฑิตวิทยาลัย มจร',
                    'details' => 'สะสมวันปฏิบัติธรรมครบถ้วนตามเกณฑ์ ' . $record->target_days . ' วัน (สะสมจริง ' . $record->accumulated_days . ' วัน)',
                    'date' => $record->approved_at ? \Carbon\Carbon::parse($record->approved_at)->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y'),
                    'status' => 'อนุมัติผ่านเกณฑ์สมบูรณ์ (Verified)',
                    'score' => 'ผ่านเกณฑ์มาตรฐานหลักสูตร',
                ];
            }
        }

        return view('portal.certificate_verify', compact('result', 'isValid', 'code', 'type'));
    }

    public function contact()
    {
        $settings = SiteSetting::getByGroup('contact');
        return view('portal.contact', compact('settings'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:150',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:150',
            'category' => 'required|string|max:100',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:3000',
        ]);

        $ticketNo = 'INQ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        ContactInquiry::create([
            'ticket_no' => $ticketNo,
            'sender_name' => $validated['sender_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'NEW',
        ]);

        return back()->with('success', 'ส่งข้อความติดต่อสอบถามเรียบร้อยแล้ว เจ้าหน้าที่จะติดต่อกลับโดยเร็ว')
                     ->with('ticket_no', $ticketNo);
    }

    public function donation()
    {
        $settings = SiteSetting::getByGroup('donation');
        $contactSettings = SiteSetting::getByGroup('contact');
        
        // สถิติยอดรวมเพื่อสร้างความมั่นใจและความโปร่งใส (ยอดที่ตรวจสอบยืนยันแล้ว)
        $totalDonationsCount = Donation::where('status', 'VERIFIED')->count();
        $totalDonationsAmount = Donation::where('status', 'VERIFIED')->sum('amount');
        
        // รายนามผู้ร่วมบุญล่าสุด (แสดงเฉพาะที่ยืนยันแล้ว ไม่เกิน 6 รายการ)
        $recentDonations = Donation::where('status', 'VERIFIED')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        return view('portal.donation', compact('settings', 'contactSettings', 'totalDonationsCount', 'totalDonationsAmount', 'recentDonations'));
    }

    public function donationSubmit(Request $request)
    {
        // แปลงรูปแบบวันที่ วว/ดด/ปปปป (พ.ศ.) หรือ YYYY-MM-DD ให้เป็น Y-m-d ค.ศ. ก่อน validate
        $rawDate = trim($request->input('transfer_date', ''));
        $parsedDate = null;
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $rawDate, $m)) {
            $day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($m[2], 2, '0', STR_PAD_LEFT);
            $year = (int)$m[3];
            if ($year > 2400) {
                $year -= 543; // แปลง พ.ศ. เป็น ค.ศ.
            }
            $parsedDate = sprintf('%04d-%s-%s', $year, $month, $day);
            $request->merge(['transfer_date' => $parsedDate]);
        }

        // ตั้งค่าเริ่มต้นวันที่ เวลา และบัญชีธนาคาร หากไม่ได้ระบุมาในฟอร์ม
        if (empty($parsedDate)) {
            $parsedDate = date('Y-m-d');
            $request->merge(['transfer_date' => $parsedDate]);
        }
        if (!$request->filled('transfer_time')) {
            $request->merge(['transfer_time' => date('H:i')]);
        }
        if (!$request->filled('bank_account')) {
            $request->merge(['bank_account' => 'ธนาคารทหารไทยธนชาต (ttb) (231-2-93605-3)']);
        }

        // ประมวลผลและรวมที่อยู่แบบแยกฟิลด์ (จังหวัด อำเภอ ตำบล รหัสไปรษณีย์) หากผู้ใช้กรอกมา
        if (!$request->filled('address') && ($request->filled('province') || $request->filled('addr_line'))) {
            $addrLine = trim($request->input('addr_line', ''));
            $subdistrict = trim($request->input('subdistrict', ''));
            $district = trim($request->input('district', ''));
            $province = trim($request->input('province', ''));
            $postalCode = trim($request->input('postal_code', ''));

            $isBkk = ($province === 'กรุงเทพมหานคร');
            $subLabel = $isBkk ? 'แขวง' : 'ต.';
            $distLabel = $isBkk ? 'เขต' : 'อ.';
            $provLabel = $isBkk ? '' : 'จ.';

            $parts = [];
            if ($addrLine) $parts[] = $addrLine;
            if ($subdistrict) $parts[] = "{$subLabel}{$subdistrict}";
            if ($district) $parts[] = "{$distLabel}{$district}";
            if ($province) $parts[] = "{$provLabel}{$province}";
            if ($postalCode) $parts[] = $postalCode;

            if (!empty($parts)) {
                $request->merge(['address' => implode(' ', $parts)]);
            }
        }

        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:20',
            'is_tax_deductible' => 'nullable|boolean',
            'amount' => 'required|numeric|min:1|max:10000000',
            'bank_account' => 'nullable|string|max:150',
            'transfer_date' => 'nullable|date',
            'transfer_time' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:1000',
            'purpose' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:2000',
            'slip' => 'required|file|mimes:jpg,jpeg,png,webp|max:10240', // สลิปต้องเป็นรูปภาพเท่านั้น สูงสุด 10MB
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240', // ภาพประจำตัวผู้บริจาค สูงสุด 10MB
        ], [
            'donor_name.required' => 'กรุณาระบุชื่อ-นามสกุล',
            'amount.required' => 'กรุณาระบุจำนวนบริจาค',
            'amount.min' => 'จำนวนเงินบริจาคต้องไม่น้อยกว่า 1 บาท',
            'slip.required' => 'กรุณาแนบไฟล์สลิปการโอนเงิน',
            'slip.mimes' => 'ไฟล์สลิปหลักฐานต้องเป็นไฟล์รูปภาพ (JPG, PNG หรือ WEBP) เท่านั้น',
            'slip.max' => 'ขนาดไฟล์สลิปต้องไม่เกิน 10MB',
            'avatar.mimes' => 'ไฟล์รูปภาพต้องเป็นไฟล์รูปภาพ (JPG, PNG หรือ WEBP) เท่านั้น',
            'avatar.max' => 'ขนาดไฟล์รูปภาพต้องไม่เกิน 10MB',
        ]);

        // อัปโหลดไฟล์สลิป
        $slipPath = null;
        if ($request->hasFile('slip')) {
            $slipFile = $request->file('slip');
            $extension = $slipFile->getClientOriginalExtension();
            $filename = 'slip_' . date('Ymd_His') . '_' . uniqid() . '.' . $extension;
            $slipPath = $slipFile->storeAs('donations', $filename, 'public');

            // ซิงค์ไฟล์ไปยัง public/storage/donations ทันที (รองรับ shared hosting)
            try {
                $targetDir = public_path('storage/donations');
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0775, true);
                }
                $storedFile = storage_path('app/public/' . $slipPath);
                if (file_exists($storedFile) && !file_exists($targetDir . '/' . $filename)) {
                    @copy($storedFile, $targetDir . '/' . $filename);
                }
            } catch (\Exception $e) {}
        }

        // อัปโหลดภาพประจำตัวผู้บริจาค (สำหรับทำโปสเตอร์อนุโมทนาบุญ)
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $extension = $avatarFile->getClientOriginalExtension();
            $filename = 'donor_avatar_' . date('Ymd_His') . '_' . uniqid() . '.' . $extension;
            $avatarPath = $avatarFile->storeAs('donations/avatars', $filename, 'public');

            // ซิงค์ไฟล์ไปยัง public/storage/donations/avatars ทันที (รองรับ shared hosting)
            try {
                $targetDir = public_path('storage/donations/avatars');
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0775, true);
                }
                $storedFile = storage_path('app/public/' . $avatarPath);
                if (file_exists($storedFile) && !file_exists($targetDir . '/' . $filename)) {
                    @copy($storedFile, $targetDir . '/' . $filename);
                }
            } catch (\Exception $e) {}
        }

        // สร้างรหัสการบริจาค เช่น DON-20261001-XXXX
        $donationNo = 'DON-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $donation = Donation::create([
            'donation_no' => $donationNo,
            'donor_name' => $validated['donor_name'],
            'tax_id' => $validated['tax_id'] ?? null,
            'is_tax_deductible' => $request->boolean('is_tax_deductible'),
            'amount' => $validated['amount'],
            'bank_account' => $validated['bank_account'],
            'transfer_date' => $validated['transfer_date'],
            'transfer_time' => $validated['transfer_time'],
            'slip_path' => $slipPath,
            'avatar_path' => $avatarPath,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'purpose' => $validated['purpose'] ?? 'ร่วมทำบุญสนับสนุนการศึกษาและปฏิบัติวิปัสสนากรรมฐาน',
            'note' => $validated['note'] ?? null,
            'status' => 'PENDING',
        ]);

        return back()->with('success', 'บันทึกข้อมูลการแจ้งบริจาคเรียบร้อยแล้ว เจ้าหน้าที่จะตรวจสอบยอดเงินและออกใบอนุโมทนาบัตรให้ต่อไป')
                     ->with('donation_success_complete', true)
                     ->with('donation_no', $donationNo)
                     ->with('donor_name', $validated['donor_name'])
                     ->with('amount', number_format($validated['amount'], 2))
                     ->with('transfer_date', $donation->transfer_date ? $donation->transfer_date->format('d/m/Y') : $validated['transfer_date'])
                     ->with('transfer_time', $validated['transfer_time'])
                     ->with('bank_account', $validated['bank_account'])
                     ->with('purpose', $donation->purpose)
                     ->with('is_tax_deductible', $donation->is_tax_deductible)
                     ->with('tax_id', $donation->tax_id);
    }
}

