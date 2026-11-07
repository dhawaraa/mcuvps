<?php

namespace App\Http\Controllers;

use App\Models\GradStudent;
use App\Models\GradCreditEntry;
use App\Models\Student;
use App\Models\UgRegistration;
use App\Models\PublicRegistration;
use Illuminate\Http\Request;

use App\Models\OrganizationUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GraduateController extends Controller
{
    public function index(Request $request)
    {
        $code = trim($request->get('student_code') ?? $request->get('search') ?? '');

        // 1. ถ้านิสิตล็อกอินอยู่แล้ว และไม่ได้ระบุรหัสค้นหา ให้ redirect ไปที่ student.dashboard
        if (empty($code) && session()->has('student_user')) {
            return redirect()->route('student.dashboard');
        }

        // 2. ถ้ามีการระบุรหัสนิสิต ให้ค้นหาและแสดงผลผ่านหน้า student_dashboard
        if (!empty($code)) {
            $student = Student::with('organizationUnit')->where('student_code', $code)->first();
            
            if ($student) {
                // ดึงข้อมูล ป.ตรี
                $ugRegistrations = UgRegistration::with('batch.organizationUnit')
                    ->where('student_code', $student->student_code)
                    ->orderByDesc('id')
                    ->get();
                $ugCompletedDays = $ugRegistrations->where('status', 'COMPLETED')->count() * 10;

                // ดึงข้อมูล บัณฑิตศึกษา
                $gradRecord = GradStudent::with(['creditEntries', 'organizationUnit'])
                    ->where('student_code', $student->student_code)
                    ->first();

                // ดึงข้อมูล คอร์สปฏิบัติธรรมทั่วไป
                $publicRegistrations = PublicRegistration::with('event.organizationUnit')
                    ->where('student_id', $student->student_code)
                    ->orderByDesc('id')
                    ->get();

                $searchCode = $code;
                return view('portal.student_dashboard', compact(
                    'student',
                    'ugRegistrations',
                    'ugCompletedDays',
                    'gradRecord',
                    'publicRegistrations',
                    'searchCode'
                ));
            } else {
                // ไม่พบรหัสนิสิตที่ระบุ
                session()->flash('search_not_found', "ไม่พบข้อมูลนิสิตรหัส {$code}");
                // หากล็อกอินอยู่ ให้เด้งกลับแดชบอร์ดพร้อมแฟลชข้อความ
                if (session()->has('student_user')) {
                    return redirect()->route('student.dashboard')->with('search_not_found', "ไม่พบข้อมูลนิสิตรหัส {$code}");
                }
            }
        }

        // 3. ถ้าไม่ได้ล็อกอินและไม่ได้ระบุรหัสนิสิต (หรือระบุรหัสแล้วแต่ไม่พบ)
        // นำทางไปหน้าเข้าสู่ระบบนิสิตเพื่อความปลอดภัยและเข้าถึงแดชบอร์ด
        return redirect()->route('student.login')->with('warning', 'กรุณาเข้าสู่ระบบด้วยรหัสนิสิต เพื่อตรวจสอบจำนวนวันกรรมฐานและประวัติการปฏิบัติธรรม');
    }

    public function requestForm(Request $request)
    {
        // 1. AJAX Student Lookup for Autofill (ดึงข้อมูลเก่าจากโปรไฟล์อัตโนมัติ)
        if ($request->input('action') === 'lookup' || $request->input('ajax') === 'lookup') {
            $studentCode = trim($request->input('student_code', ''));
            if (empty($studentCode)) {
                return response()->json(['found' => false, 'message' => 'กรุณากรอกรหัสนิสิต']);
            }

            $student = Student::with('organizationUnit')->where('student_code', $studentCode)->first();
            if (!$student) {
                return response()->json(['found' => false, 'message' => "ไม่พบข้อมูลนิสิตรหัส {$studentCode} ในฐานข้อมูล มจร"]);
            }

            // ดึงข้อมูลสะสมวันเดิมจาก GradStudent ถ้ามี
            $gradRecord = GradStudent::with('creditEntries')->where('student_code', $studentCode)->first();
            $accumulatedDays = $gradRecord ? $gradRecord->accumulated_days : 0;

            // จัดการที่อยู่
            $address = trim(($student->house_no ? 'เลขที่ ' . $student->house_no . ' ' : '') .
                           ($student->moo ? 'หมู่ ' . $student->moo . ' ' : '') .
                           ($student->temple_village ? $student->temple_village : ''));

            return response()->json([
                'found' => true,
                'message' => 'พบข้อมูลนิสิตในระบบ',
                'student' => [
                    'student_code' => $student->student_code,
                    'citizen_id' => $student->citizen_id,
                    'prefix' => $student->prefix ?: 'พระ',
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'chaya' => $student->chaya,
                    'buddhist_name' => $student->chaya,
                    'full_name' => $student->full_name,
                    'age' => $student->age,
                    'vassa' => $student->vassa,
                    'nationality' => $student->nationality ?: 'ไทย',
                    'religion' => $student->religion ?: 'พุทธ',
                    'degree_level' => $student->degree_level,
                    'degree_level_th' => $student->degree_level_th,
                    'faculty' => $student->faculty,
                    'major' => $student->major,
                    'program_name' => $student->major,
                    'org_unit_id' => $student->org_unit_id,
                    'org_unit_name' => $student->organizationUnit?->name_th,
                    'address' => $address,
                    'subdistrict' => $student->subdistrict,
                    'district' => $student->district,
                    'province' => $student->province,
                    'postcode' => $student->postal_code,
                    'phone' => $student->phone,
                    'email' => $student->email,
                    'target_days' => $student->target_days,
                    'accumulated_days' => $accumulatedDays,
                ]
            ]);
        }

        // ตรวจสอบสถานะเปิด-ปิดระบบจาก system_settings
        $setting = DB::table('system_settings')->where('setting_key', 'edoc_status')->first();
        $isClosed = ($setting && $setting->setting_value === 'N');

        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        // ดึงข้อมูลนิสิตที่เข้าสู่ระบบไว้ (ถ้ามี) เพื่อ pre-fill ข้อมูล
        $loggedStudent = null;
        if (session()->has('student_user')) {
            $loggedStudent = Student::with('organizationUnit')->find(session('student_user')['id']);
        }

        return view('portal.grad_request', compact('isClosed', 'orgUnits', 'loggedStudent'));
    }

    public function storeRequest(Request $request)
    {
        // ตรวจสอบสถานะเปิด-ปิดระบบ
        $setting = DB::table('system_settings')->where('setting_key', 'edoc_status')->first();
        if ($setting && $setting->setting_value === 'N') {
            return back()->with('error', 'ระบบปิดรับคำร้อง e-Document ชั่วคราว')->withInput();
        }

        $validated = $request->validate([
            'student_id' => 'required|string|max:20',
            'citizen_id' => 'required|string|max:20',
            'prefix' => 'required|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'buddhist_name' => 'nullable|string|max:100',
            'age' => 'nullable|integer|min:10|max:120',
            'vassa' => 'nullable|integer|min:0|max:100',
            'nationality' => 'required|string|max:50',
            'degree_level' => 'required|string|max:100',
            'faculty' => 'required|string|max:100',
            'program_name' => 'required|string|max:150',
            'org_unit_id' => 'required|exists:organization_units,id',
            'accumulated_days' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postcode' => 'required|string|max:10',
            'phone' => 'required|string|max:30',
            'transfer_date' => 'required|date',
            'transfer_time' => 'required',
            'file_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'file_interview' => 'required|mimes:pdf|max:10240',
            'file_attendance' => 'required|mimes:pdf|max:10240',
            'file_slip' => 'required|mimes:jpeg,png,jpg,pdf|max:10240',
        ]);

        $degreeMap = [
            'ประกาศนียบัตร (15 วัน)' => 15,
            'ประกาศนียบัตร (7 วัน)' => 7,
            'ประกาศนียบัตร (30 วัน)' => 30,
            'ประกาศนียบัตร (90วัน)' => 90,
            'ประกาศนียบัตร' => 15,
            'CERTIFICATE' => 15,
            'ปริญญาตรี (40 วัน)' => 40,
            'ปริญญาตรีปีละ (10วัน)' => 10,
            'ปริญญาตรี' => 40,
            'BACHELOR' => 40,
            'ปริญญาโท (30 วัน)' => 30,
            'ปริญญาโท' => 30,
            'MASTER' => 30,
            'ปริญญาเอก (45 วัน)' => 45,
            'ปริญญาเอก' => 45,
            'DOCTORAL' => 45,
        ];

        $targetDays = $degreeMap[$validated['degree_level']] ?? 30;
        // ปลดล็อกเกณฑ์ขั้นต่ำ: อนุญาตให้นิสิตยื่นจำนวนวันสะสมได้ตามจริงตั้งแต่ 1 วันขึ้นไป (เช่น 7, 10, 15 วัน)
        // โดย target_days จะบันทึกเป็นเป้าหมายรวมของหลักสูตร

        // จัดการอัปโหลดไฟล์ทั้ง 4 รายการ
        $photoPath = $request->file('file_photo')->store('edoc/photos', 'public');
        $interviewPath = $request->file('file_interview')->store('edoc/interviews', 'public');
        $attendancePath = $request->file('file_attendance')->store('edoc/attendances', 'public');
        $slipPath = $request->file('file_slip')->store('edoc/slips', 'public');

        $fullName = trim($validated['prefix'] . $validated['first_name'] . ' ' . $validated['last_name']);
        if (!empty($validated['buddhist_name']) && $validated['buddhist_name'] !== '-') {
            $fullName .= ' ' . $validated['buddhist_name'];
        }

        // ตรวจสอบว่ามีข้อมูลเดิมหรือไม่
        $student = GradStudent::where('student_code', $validated['student_id'])
            ->orWhere('student_id', $validated['student_id'])
            ->first();

        $data = [
            'student_id' => $validated['student_id'],
            'student_code' => $validated['student_id'],
            'citizen_id' => $validated['citizen_id'],
            'nationality' => $validated['nationality'],
            'prefix' => $validated['prefix'],
            'full_name' => $fullName,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'buddhist_name' => $validated['buddhist_name'] ?? null,
            'age' => $validated['age'] ?? null,
            'vassa' => $validated['vassa'] ?? 0,
            'degree_level' => $validated['degree_level'],
            'target_days' => $targetDays,
            'accumulated_days' => $validated['accumulated_days'],
            'org_unit_id' => $validated['org_unit_id'],
            'faculty' => $validated['faculty'],
            'program_name' => $validated['program_name'],
            'address' => $validated['address'],
            'subdistrict' => $validated['subdistrict'],
            'district' => $validated['district'],
            'province' => $validated['province'],
            'postcode' => $validated['postcode'],
            'phone' => $validated['phone'],
            'photo_path' => $photoPath,
            'interview_record_path' => $interviewPath,
            'attendance_record_path' => $attendancePath,
            'slip_path' => $slipPath,
            'transfer_date' => $validated['transfer_date'],
            'transfer_time' => $validated['transfer_time'],
            'submission_status' => 'SUBMITTED',
            'submitted_at' => now(),
            'updated_at' => now(),
        ];

        if ($student) {
            $student->update($data);
        } else {
            $data['created_at'] = now();
            $student = GradStudent::create($data);
        }

        return redirect()->route('grad.request')
            ->with('success', "ยื่นคำร้องรหัสนิสิต {$student->student_code} เรียบร้อยแล้ว เจ้าหน้าที่จะทำการตรวจสอบข้อมูลและเอกสารของท่าน")
            ->with('student_code', $student->student_code);
    }

    public function finalSubmit(Request $request)
    {
        $studentId = (int)$request->input('student_id');
        $student = GradStudent::findOrFail($studentId);

        if (in_array($student->submission_status, ['DRAFT', 'ACCUMULATING']) && $student->accumulated_days >= $student->target_days) {
            $student->update([
                'submission_status' => 'SUBMITTED',
                'submitted_at' => now(),
            ]);
            return redirect()->route('grad.progress', ['student_code' => $student->student_code ?? $student->student_id, 'status' => 'submitted']);
        }

        return back()->with('error', 'ไม่สามารถส่งคำขอได้เนื่องจากวันสะสมยังไม่ครบเกณฑ์หรือข้อมูลถูกล็อกแล้ว');
    }

    public function certificate($code)
    {
        $student = GradStudent::with('organizationUnit')
            ->where('student_code', $code)
            ->orWhere('student_id', $code)
            ->firstOrFail();

        // ตรวจสอบสถานะการอนุมัติ (ต้อง APPROVED)
        if ($student->submission_status !== 'APPROVED') {
            return redirect()->route('grad.progress', ['student_code' => $code])
                ->with('error', 'ยังไม่สามารถออกหนังสือรับรองได้เนื่องจากคำขอยังไม่ได้รับการอนุมัติสมบูรณ์');
        }

        $verifyUrl = route('cert.verify', ['type' => 'grad', 'code' => $student->student_code ?? $student->student_id]);

        return view('portal.certificate_grad', compact('student', 'verifyUrl'));
    }
}


