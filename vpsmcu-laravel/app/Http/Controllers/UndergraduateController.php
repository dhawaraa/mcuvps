<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUnit;
use App\Models\UgBatch;
use App\Models\UgRegistration;
use App\Models\UgMasterStudent;
use Illuminate\Http\Request;

class UndergraduateController extends Controller
{
    public function create()
    {
        $orgs = OrganizationUnit::orderedForSelect()->get();
        $batches = UgBatch::with('organizationUnit')->where('status', 'OPEN')->orderBy('start_date', 'desc')->get();

        return view('portal.ug_register', compact('orgs', 'batches'));
    }

    public function lookupStudent(Request $request)
    {
        $code = trim($request->input('student_code', ''));
        if (!$code) {
            return response()->json(['found' => false, 'message' => 'กรุณาระบุรหัสนิสิต']);
        }

        $student = UgMasterStudent::with('organizationUnit')->where('student_code', $code)->first();

        if (!$student) {
            return response()->json([
                'found' => false,
                'message' => 'ไม่พบข้อมูลรหัสนิสิตในฐานข้อมูลส่วนกลาง กรุณาตรวจสอบรหัสนิสิต หรือติดต่อเจ้าหน้าที่ส่วนงาน'
            ]);
        }

        return response()->json([
            'found' => true,
            'student' => [
                'student_code' => $student->student_code,
                'citizen_id' => $student->citizen_id,
                'prefix' => $student->prefix,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'chaya' => $student->chaya,
                'degree_level' => $student->degree_level,
                'faculty' => $student->faculty,
                'major' => $student->major,
                'org_unit_id' => $student->org_unit_id,
                'org_unit_name' => $student->organizationUnit->name_th ?? 'มจร',
                'study_year' => $student->study_year,
                'phone' => $student->phone,
                'email' => $student->email,
            ]
        ]);
    }

    public function store(Request $request)
    {
        // ทำความสะอาดฟิลด์ email (ถ้ามี): หากกรอก '-', 'ไม่มี' หรือค่าที่ไม่ใช่อีเมล ให้แปลงเป็น null
        if ($request->has('email')) {
            $rawEmail = trim((string)$request->input('email', ''));
            if ($rawEmail === '' || in_array(mb_strtolower($rawEmail), ['-', '--', '---', 'ไม่มี', 'ไม่มีค่ะ', 'ไม่มีครับ', 'none', 'no', 'n/a', 'null']) || !filter_var($rawEmail, FILTER_VALIDATE_EMAIL)) {
                $request->merge(['email' => filter_var($rawEmail, FILTER_VALIDATE_EMAIL) ? $rawEmail : null]);
            }
        }

        $validated = $request->validate([
            'student_code' => 'required|string',
            'citizen_id' => 'nullable|string',
            'prefix' => 'nullable|string',
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'org_unit_id' => 'required|integer',
            'study_year' => 'nullable|integer',
            'batch_id' => 'required|integer',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'health_conditions' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
        ]);

        $batch = UgBatch::with('organizationUnit')->find($validated['batch_id']);
        if (!$batch) {
            return back()->with('error', 'ไม่พบโครงการปฏิบัติธรรมที่เลือก');
        }

        // Campus-Binding Rule: ตรวจสอบว่าโครงการที่เลือกลงทะเบียนตรงกับวิทยาเขตต้นสังกัดของนิสิตหรือไม่
        if ($batch->org_unit_id != $validated['org_unit_id']) {
            $org = OrganizationUnit::find($validated['org_unit_id']);
            return back()->with('error', 'ข้อผิดพลาด: ตามกฎ Campus-Binding Rule นิสิตต้องเลือกลงทะเบียนเฉพาะโครงการของวิทยาเขต/ส่วนงานต้นสังกัดของตนเอง (' . ($org->name_th ?? '') . ') เท่านั้น');
        }

        // ตรวจสอบการลงทะเบียนซ้ำในโครงการเดียวกัน
        $existing = UgRegistration::where('student_code', $validated['student_code'])
            ->where('batch_id', $validated['batch_id'])
            ->first();

        if ($existing) {
            return back()->with('error', 'นิสิตรหัสนี้นี้เคยลงทะเบียนในโครงการนี้ไว้แล้ว เลขที่ใบสมัคร: ' . $existing->registration_no)
                         ->with('regSuccess', $existing->registration_no);
        }

        // ตรวจสอบที่นั่งว่าง / Quota
        $currentCount = UgRegistration::where('batch_id', $validated['batch_id'])->count();
        if ($batch->max_quota > 0 && $currentCount >= $batch->max_quota) {
            return back()->with('error', 'ขออภัย โครงการนี้มีผู้ลงทะเบียนครบเต็มตามจำนวนที่นั่ง (' . $batch->max_quota . ' ท่าน) แล้ว');
        }

        // สกัดตัวเลข 2 หลักท้ายของปีการศึกษา เช่น 2569 -> 69 (หรือปี พ.ศ. ปัจจุบัน)
        $yearCode = !empty($batch->academic_year) ? substr(trim($batch->academic_year), -2) : substr((string)(date('Y') + 543), -2);

        // ดึงรหัสย่อสังกัด (เช่น AYA, NKI, CMI) จาก organizationUnit
        $org = $batch->organizationUnit ?? OrganizationUnit::find($validated['org_unit_id']);
        $orgCode = 'MCU';
        if ($org) {
            if (!empty($org->province_code)) {
                $orgCode = strtoupper(trim($org->province_code));
            } else {
                $cleanCode = str_replace(['CAMPUS-', 'MCU-'], '', $org->code);
                $orgCode = strtoupper(substr($cleanCode, 0, 3));
            }
        }

        // โครงสร้างรหัสบัตรลงทะเบียน: [ปี พ.ศ. 2 หลัก]-[รหัสย่อสังกัด]-[รหัสนิสิต] เช่น 69-AYA-6601201003
        $studentCode = trim($validated['student_code']);
        $reg_no = "{$yearCode}-{$orgCode}-{$studentCode}";

        // Format full_name according to schema requirement
        $fullName = trim(($validated['prefix'] ?? '') . ' ' . $validated['first_name'] . ' ' . ($validated['last_name'] ?? ''));

        // ดึงข้อมูลเพิ่มเติมจากฐานข้อมูลกลางนิสิต (ถ้ามี) เช่น คณะ สาขา
        $masterStudent = UgMasterStudent::where('student_code', $validated['student_code'])->first();

        $reg = UgRegistration::create([
            'registration_no' => $reg_no,
            'batch_id' => $validated['batch_id'],
            'student_id' => $validated['student_code'],
            'student_code' => $validated['student_code'],
            'citizen_id' => $validated['citizen_id'] ?? '0000000000000',
            'id_card_hash' => !empty($validated['citizen_id']) ? hash('sha256', $validated['citizen_id']) : null,
            'prefix' => $validated['prefix'] ?? null,
            'full_name' => $fullName,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'org_unit_id' => $validated['org_unit_id'],
            'faculty' => $masterStudent->faculty ?? null,
            'major' => $masterStudent->major ?? null,
            'class_year' => $validated['study_year'] ?? 1,
            'study_year' => $validated['study_year'] ?? 1,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'health_conditions' => $validated['health_conditions'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'status' => 'PENDING'
        ]);

        return back()->with('success', 'ส่งคำขอลงทะเบียนสำเร็จเรียบร้อยแล้ว! ข้อมูลของท่านอยู่ระหว่างรอเจ้าหน้าที่ส่วนงานตรวจสอบและอนุมัติสิทธิ์')
                     ->with('regSuccess', $reg_no);
    }

    public function checkStatus(Request $request)
    {
        $search = trim($request->input('search', ''));
        $registrations = collect();
        $student = null;

        if ($search) {
            $student = UgMasterStudent::with('organizationUnit')
                ->where('student_code', $search)
                ->orWhere('citizen_id', $search)
                ->first();

            $registrations = UgRegistration::with(['batch.organizationUnit', 'organizationUnit'])
                ->where('student_code', $search)
                ->orWhere('citizen_id', $search)
                ->orWhere('registration_no', $search)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('portal.ug_check', compact('search', 'student', 'registrations'));
    }

    public function certificate($reg_no)
    {
        $reg = UgRegistration::with(['batch.organizationUnit', 'organizationUnit'])
            ->where('registration_no', $reg_no)
            ->firstOrFail();

        // ต้องผ่านเกณฑ์ COMPLETED เท่านั้น
        if ($reg->status !== 'COMPLETED') {
            return redirect()->route('home')->with('error', 'นิสิตยังไม่ผ่านเกณฑ์การประเมินการปฏิบัติวิปัสสนากรรมฐาน (10 วัน)');
        }

        $verifyUrl = route('cert.verify', ['type' => 'ug', 'code' => $reg->registration_no]);

        return view('portal.certificate_ug', compact('reg', 'verifyUrl'));
    }
}

