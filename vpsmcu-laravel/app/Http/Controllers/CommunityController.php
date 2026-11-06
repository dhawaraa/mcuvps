<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUnit;
use App\Models\PublicEvent;
use App\Models\PublicRegistration;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    public function create()
    {
        $events = PublicEvent::with('organizationUnit')
            ->withCount(['registrations as active_registrations_count' => function ($q) {
                $q->whereNotIn('status', ['REJECTED', 'CANCELLED']);
            }])
            ->where('status', 'OPEN')
            ->orderBy('start_date', 'asc')
            ->get();

        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('portal.public_register', compact('events', 'orgUnits'));
    }

    public function store(Request $request)
    {
        // ทำความสะอาดฟิลด์ email (ถ้ามี): หากกรอก '-', 'ไม่มี' หรือค่าที่ไม่ใช่อีเมล ให้แปลงเป็น null เพื่อไม่ให้ขัดขวางการสมัคร
        if ($request->has('email')) {
            $rawEmail = trim((string)$request->input('email', ''));
            if ($rawEmail === '' || in_array(mb_strtolower($rawEmail), ['-', '--', '---', 'ไม่มี', 'ไม่มีค่ะ', 'ไม่มีครับ', 'none', 'no', 'n/a', 'null']) || !filter_var($rawEmail, FILTER_VALIDATE_EMAIL)) {
                $request->merge(['email' => filter_var($rawEmail, FILTER_VALIDATE_EMAIL) ? $rawEmail : null]);
            }
        }

        $validated = $request->validate([
            'event_id' => 'required|integer',
            'applicant_type' => 'nullable|string',
            'student_id' => 'nullable|string|max:30',
            'degree_level' => 'nullable|string|max:50',
            'faculty' => 'nullable|string|max:150',
            'org_unit_id' => 'nullable|integer',
            'program_name' => 'nullable|string|max:200',
            'citizen_id' => 'required|string|min:8|max:20',
            'prefix' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'buddhist_name' => 'nullable|string',
            'gender' => 'nullable|string',
            'age' => 'nullable|integer|min:1',
            'vassa' => 'nullable|integer|min:0',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'line_id' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'subdistrict' => 'nullable|string',
            'district' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'room_info' => 'nullable|string|max:150',
            'vehicle_info' => 'nullable|string|max:150',
            'food_type' => 'required|string',
            'special_needs' => 'nullable|string',
        ]);

        $event = PublicEvent::findOrFail($validated['event_id']);

        $existing = PublicRegistration::where('event_id', $event->id)
            ->where('citizen_id', $validated['citizen_id'])
            ->first();

        if ($existing) {
            return back()->with('error', 'ท่านได้ลงทะเบียนในโครงการนี้แล้ว เลขที่ใบสมัคร: ' . $existing->registration_no . ' (' . $existing->status . ')')
                         ->with('regSuccess', $existing->registration_no);
        }

        // ตรวจสอบโควตาที่นั่งว่าง (ใครมาก่อนได้สิทธิ์ก่อน - นับทุกคนที่ลงทะเบียนเข้ามาที่ยังไม่ถูกปฏิเสธ/ยกเลิก)
        $currentRegistered = PublicRegistration::where('event_id', $event->id)
            ->whereNotIn('status', ['REJECTED', 'CANCELLED'])
            ->count();
        if ($event->max_quota > 0 && $currentRegistered >= $event->max_quota) {
            return back()->with('error', 'ขออภัย โครงการนี้มีผู้ลงทะเบียนครบเต็มตามจำนวนที่นั่ง (' . $event->max_quota . ' ท่าน) แล้ว');
        }

        $status = 'PENDING';
        
        $buddhistPart = !empty($validated['buddhist_name']) ? ' (' . trim($validated['buddhist_name']) . ')' : '';
        $fullName = trim(($validated['prefix'] ?? '') . ' ' . $validated['first_name'] . ' ' . $validated['last_name'] . $buddhistPart);

        // Derive gender if not provided
        $gender = $validated['gender'] ?? null;
        if (!$gender) {
            $p = trim($validated['prefix'] ?? '');
            if (in_array($p, ['นาง', 'นางสาว', 'แม่ชี', 'อุบาสิกา', 'ด.ญ.'])) {
                $gender = 'FEMALE';
            } elseif (in_array($p, ['นาย', 'พระ', 'พระภิกษุ', 'พระมหา', 'พระครู', 'พระอธิการ', 'สามเณร', 'อุบาสก', 'ด.ช.'])) {
                $gender = 'MALE';
            } else {
                $gender = 'OTHER';
            }
        }
        
        $queueNo = PublicRegistration::where('event_id', $event->id)->count() + 1;
        $regNo = 'PUB-' . date('Ymd') . '-' . str_pad($queueNo, 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($validated, $event, $status, $fullName, $queueNo, $regNo, $gender) {
            PublicRegistration::create([
                'registration_no' => $regNo,
                'event_id' => $event->id,
                'applicant_type' => $validated['applicant_type'] ?? 'PEOPLE',
                'student_id' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['student_id'] ?? null) : null,
                'degree_level' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['degree_level'] ?? null) : null,
                'faculty' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['faculty'] ?? null) : null,
                'org_unit_id' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['org_unit_id'] ?? null) : null,
                'program_name' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['program_name'] ?? null) : null,
                'citizen_id' => $validated['citizen_id'],
                'prefix' => $validated['prefix'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'buddhist_name' => $validated['buddhist_name'] ?? null,
                'full_name' => $fullName,
                'gender' => $gender,
                'age' => $validated['age'] ?? 0,
                'vassa' => $validated['vassa'] ?? 0,
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'line_id' => $validated['line_id'] ?? null,
                'address' => $validated['address'] ?? null,
                'subdistrict' => $validated['subdistrict'] ?? null,
                'district' => $validated['district'] ?? null,
                'province' => $validated['province'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'room_info' => $validated['room_info'] ?? null,
                'vehicle_info' => $validated['vehicle_info'] ?? null,
                'dietary_restriction' => $validated['food_type'] ?? null,
                'congenital_disease' => $validated['special_needs'] ?? null,
                'queue_no' => $queueNo,
                'status' => $status,
                'registered_at' => now(),
            ]);
        });

        $msg = 'บันทึกใบสมัครเข้าร่วมโครงการเรียบร้อยแล้ว! ข้อมูลของท่านอยู่ระหว่างเจ้าหน้าที่ตรวจสอบคุณสมบัติและจัดสรรที่พัก (สถานะ: รอการตรวจสอบ)';

        return back()->with('success', $msg)->with('regSuccess', $regNo);
    }

    public function checkStatus(Request $request)
    {
        $search = trim($request->input('search', ''));
        $registrations = collect();

        if ($search) {
            // Strip any dashes or spaces for phone or citizen_id comparison (e.g., 0818889999)
            $cleanDigits = preg_replace('/[^0-9]/', '', $search);

            $registrations = PublicRegistration::with(['event.organizationUnit', 'organizationUnit'])
                ->where(function ($q) use ($search, $cleanDigits) {
                    $q->where('registration_no', $search)
                      ->orWhere('phone', $search)
                      ->orWhere('citizen_id', $search)
                      ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                      ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                      ->orWhere('last_name', 'LIKE', '%' . $search . '%');
                    if ($cleanDigits) {
                        $q->orWhere(DB::raw("REPLACE(REPLACE(phone, '-', ''), ' ', '')"), $cleanDigits)
                          ->orWhere(DB::raw("REPLACE(REPLACE(citizen_id, '-', ''), ' ', '')"), $cleanDigits);
                    }
                })
                ->orderBy('registered_at', 'desc')
                ->get();
        }

        $orgUnits = OrganizationUnit::orderedForSelect()->get();

        return view('portal.public_check', compact('search', 'registrations', 'orgUnits'));
    }

    public function updateRegistration(Request $request, $id = null)
    {
        $targetId = $id ?? $request->input('id');
        $reg = PublicRegistration::findOrFail($targetId);

        // Security check: Only allow editing if status is PENDING or REJECTED
        if (!in_array($reg->status, ['PENDING', 'REJECTED'])) {
            return back()->with('error', 'ไม่สามารถแก้ไขข้อมูลได้ เนื่องจากใบสมัครได้รับการอนุมัติหรืออยู่ในสถานะที่ไม่เปิดให้แก้ไข');
        }

        // ทำความสะอาดฟิลด์ email (ถ้ามี): หากกรอก '-', 'ไม่มี' หรือค่าที่ไม่ใช่อีเมล ให้แปลงเป็น null เพื่อไม่ให้ขัดขวางการแก้ไข
        if ($request->has('email')) {
            $rawEmail = trim((string)$request->input('email', ''));
            if ($rawEmail === '' || in_array(mb_strtolower($rawEmail), ['-', '--', '---', 'ไม่มี', 'ไม่มีค่ะ', 'ไม่มีครับ', 'none', 'no', 'n/a', 'null']) || !filter_var($rawEmail, FILTER_VALIDATE_EMAIL)) {
                $request->merge(['email' => filter_var($rawEmail, FILTER_VALIDATE_EMAIL) ? $rawEmail : null]);
            }
        }

        $validated = $request->validate([
            'applicant_type' => 'nullable|string',
            'student_id' => 'nullable|string|max:30',
            'degree_level' => 'nullable|string|max:50',
            'faculty' => 'nullable|string|max:150',
            'org_unit_id' => 'nullable|integer',
            'program_name' => 'nullable|string|max:200',
            'prefix' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'buddhist_name' => 'nullable|string',
            'age' => 'nullable|integer|min:1',
            'vassa' => 'nullable|integer|min:0',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'line_id' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'subdistrict' => 'nullable|string',
            'district' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'room_info' => 'nullable|string|max:150',
            'vehicle_info' => 'nullable|string|max:150',
            'food_type' => 'required|string',
            'special_needs' => 'nullable|string',
        ]);

        $buddhistPart = !empty($validated['buddhist_name']) ? ' (' . trim($validated['buddhist_name']) . ')' : '';
        $fullName = trim(($validated['prefix'] ?? '') . ' ' . $validated['first_name'] . ' ' . $validated['last_name'] . $buddhistPart);

        $p = trim($validated['prefix'] ?? '');
        $gender = null;
        if (in_array($p, ['นาง', 'นางสาว', 'แม่ชี', 'อุบาสิกา', 'ด.ญ.'])) {
            $gender = 'FEMALE';
        } elseif (in_array($p, ['นาย', 'พระ', 'พระภิกษุ', 'พระมหา', 'พระครู', 'พระอธิการ', 'สามเณร', 'อุบาสก', 'ด.ช.'])) {
            $gender = 'MALE';
        }

        $updateData = [
            'applicant_type' => $validated['applicant_type'] ?? 'PEOPLE',
            'student_id' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['student_id'] ?? null) : null,
            'degree_level' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['degree_level'] ?? null) : null,
            'faculty' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['faculty'] ?? null) : null,
            'org_unit_id' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['org_unit_id'] ?? null) : null,
            'program_name' => ($validated['applicant_type'] ?? '') === 'STUDENT' ? ($validated['program_name'] ?? null) : null,
            'prefix' => $validated['prefix'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'buddhist_name' => $validated['buddhist_name'] ?? null,
            'full_name' => $fullName,
            'gender' => $gender ?? $reg->gender ?? 'OTHER',
            'age' => $validated['age'] ?? 0,
            'vassa' => $validated['vassa'] ?? 0,
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'line_id' => $validated['line_id'] ?? null,
            'address' => $validated['address'] ?? null,
            'subdistrict' => $validated['subdistrict'] ?? null,
            'district' => $validated['district'] ?? null,
            'province' => $validated['province'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'room_info' => $validated['room_info'] ?? null,
            'vehicle_info' => $validated['vehicle_info'] ?? null,
            'dietary_restriction' => $validated['food_type'] ?? null,
            'congenital_disease' => $validated['special_needs'] ?? null,
        ];

        // If previously REJECTED, reset status back to PENDING for re-examination
        if ($reg->status === 'REJECTED') {
            $updateData['status'] = 'PENDING';
            $updateData['reject_reason'] = null;
        }

        $reg->update($updateData);

        return redirect()->route('public.check', ['search' => $reg->phone])
            ->with('success', 'แก้ไขข้อมูลใบสมัคร (' . $reg->registration_no . ') เรียบร้อยแล้ว');
    }

    /**
     * ค้นหาข้อมูลผู้สมัครเก่าสำหรับ Auto-fill (Auto-populate)
     */
    public function lookupApplicant(Request $request)
    {
        $type = $request->input('type', 'PEOPLE');
        $code = trim($request->input('code', ''));

        if (!$code) {
            return response()->json(['found' => false, 'message' => 'ไม่พบรหัสค้นหา']);
        }

        $cleanDigits = preg_replace('/[^0-9]/', '', $code);

        // 1. ค้นหากรณีเป็นนิสิต มจร (STUDENT)
        if ($type === 'STUDENT') {
            // ค้นจากตารางนิสิต มจร ก่อน (Student Model)
            $student = Student::where('student_code', $code)
                ->orWhere(function ($q) use ($cleanDigits) {
                    if ($cleanDigits && strlen($cleanDigits) >= 8) {
                        $q->where(DB::raw("REPLACE(REPLACE(student_code, '-', ''), ' ', '')"), $cleanDigits);
                    }
                })
                ->first();

            // ค้นจากประวัติการสมัครเดิมใน public_registrations ด้วย
            $prevReg = PublicRegistration::where('student_id', $code)
                ->orderBy('registered_at', 'desc')
                ->first();

            if ($student || $prevReg) {
                // แปลงระดับการศึกษา
                $deg = $prevReg->degree_level ?? null;
                if (!$deg && $student) {
                    $degreeMap = [
                        'BACHELOR' => 'ปริญญาตรี',
                        'MASTER' => 'ปริญญาโท',
                        'DOCTORAL' => 'ปริญญาเอก',
                    ];
                    $deg = $degreeMap[$student->degree_level] ?? $student->degree_level;
                }

                return response()->json([
                    'found' => true,
                    'source' => $student ? 'student_db' : 'registration_history',
                    'data' => [
                        'student_id' => $student->student_code ?? $prevReg->student_id ?? $code,
                        'degree_level' => $deg ?? '',
                        'faculty' => $prevReg->faculty ?? $student->faculty ?? '',
                        'program_name' => $prevReg->program_name ?? $student->major ?? '',
                        'citizen_id' => $prevReg->citizen_id ?? $student->citizen_id ?? '',
                        'prefix' => $student->prefix ?? $prevReg->prefix ?? '',
                        'first_name' => $student->first_name ?? $prevReg->first_name ?? '',
                        'last_name' => $student->last_name ?? $prevReg->last_name ?? '',
                        'buddhist_name' => $student->chaya ?? $prevReg->buddhist_name ?? '',
                        'vassa' => $prevReg->vassa ?? 0,
                        'age' => $prevReg->age ?? null,
                        'phone' => $student->phone ?? $prevReg->phone ?? '',
                        'email' => $student->email ?? $prevReg->email ?? '',
                        'line_id' => $prevReg->line_id ?? '',
                        'address' => $prevReg->address ?? $student->address ?? '',
                        'province' => $prevReg->province ?? '',
                        'district' => $prevReg->district ?? '',
                        'subdistrict' => $prevReg->subdistrict ?? '',
                        'postal_code' => $prevReg->postal_code ?? '',
                        'room_info' => $prevReg->room_info ?? '',
                        'vehicle_info' => $prevReg->vehicle_info ?? '',
                        'dietary_restriction' => $prevReg->dietary_restriction ?? 'NORMAL',
                        'congenital_disease' => $prevReg->congenital_disease ?? '',
                    ],
                ]);
            }
        } else {
            // 2. ค้นหากรณีเป็นประชาชนทั่วไป (PEOPLE) จาก citizen_id
            $prevReg = PublicRegistration::where(function ($q) use ($code, $cleanDigits) {
                    $q->where('citizen_id', $code);
                    if ($cleanDigits && strlen($cleanDigits) >= 10) {
                        $q->orWhere(DB::raw("REPLACE(REPLACE(citizen_id, '-', ''), ' ', '')"), $cleanDigits);
                    }
                })
                ->orderBy('registered_at', 'desc')
                ->first();

            // หากไม่พบใน public_registrations ลองดูในตาราง students (กรณีเคยเป็นนิสิต)
            $student = null;
            if (!$prevReg && $cleanDigits && strlen($cleanDigits) === 13) {
                $student = Student::where('citizen_id', $cleanDigits)->first();
            }

            if ($prevReg || $student) {
                return response()->json([
                    'found' => true,
                    'source' => $prevReg ? 'registration_history' : 'student_db',
                    'data' => [
                        'citizen_id' => $prevReg->citizen_id ?? $student->citizen_id ?? $code,
                        'prefix' => $prevReg->prefix ?? $student->prefix ?? '',
                        'first_name' => $prevReg->first_name ?? $student->first_name ?? '',
                        'last_name' => $prevReg->last_name ?? $student->last_name ?? '',
                        'buddhist_name' => $prevReg->buddhist_name ?? $student->chaya ?? '',
                        'vassa' => $prevReg->vassa ?? 0,
                        'age' => $prevReg->age ?? null,
                        'phone' => $prevReg->phone ?? $student->phone ?? '',
                        'email' => $prevReg->email ?? $student->email ?? '',
                        'line_id' => $prevReg->line_id ?? '',
                        'address' => $prevReg->address ?? $student->address ?? '',
                        'province' => $prevReg->province ?? '',
                        'district' => $prevReg->district ?? '',
                        'subdistrict' => $prevReg->subdistrict ?? '',
                        'postal_code' => $prevReg->postal_code ?? '',
                        'room_info' => $prevReg->room_info ?? '',
                        'vehicle_info' => $prevReg->vehicle_info ?? '',
                        'dietary_restriction' => $prevReg->dietary_restriction ?? 'NORMAL',
                        'congenital_disease' => $prevReg->congenital_disease ?? '',
                    ],
                ]);
            }
        }

        return response()->json(['found' => false, 'message' => 'ไม่พบข้อมูลเดิมในระบบ']);
    }
}


