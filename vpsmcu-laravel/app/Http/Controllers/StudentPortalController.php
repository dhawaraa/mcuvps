<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\UgRegistration;
use App\Models\GradStudent;
use App\Models\PublicRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentPortalController extends Controller
{
    /**
     * หน้าจอเข้าสู่ระบบนิสิต
     */
    public function showLogin()
    {
        if (session()->has('student_user')) {
            return redirect()->route('student.dashboard');
        }
        return view('portal.student_login');
    }

    /**
     * ดำเนินการเข้าสู่ระบบ
     */
    public function login(Request $request)
    {
        $request->validate([
            'student_code' => 'required|string',
            'password' => 'required|string',
        ]);

        $student = Student::with('organizationUnit')
            ->where('student_code', trim($request->student_code))
            ->first();

        if (!$student || !$student->verifyPassword($request->password)) {
            return back()->withInput()->with('error', 'รหัสนิสิตหรือรหัสผ่านไม่ถูกต้อง โปรดตรวจสอบอีกครั้ง');
        }

        if (!$student->is_active) {
            return back()->withInput()->with('error', 'บัญชีผู้ใช้นี้ถูกระงับการใช้งานชั่วคราว โปรดติดต่อส่วนงานต้นสังกัด');
        }

        // อัปเดตเวลาเข้าสู่ระบบ
        $student->update([
            'last_login_at' => now(),
        ]);

        // บันทึก Session
        session([
            'student_user' => [
                'id' => $student->id,
                'student_code' => $student->student_code,
                'prefix' => $student->prefix,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'chaya' => $student->chaya,
                'full_name' => $student->full_name,
                'degree_level' => $student->degree_level,
                'org_unit_name' => $student->organizationUnit?->name_th ?? 'ส่วนกลาง มจร',
                'faculty' => $student->faculty,
                'major' => $student->major,
                'force_password_change' => $student->force_password_change,
            ]
        ]);

        if ($student->force_password_change) {
            return redirect()->route('student.profile')->with('warning', 'กรุณาตั้งรหัสผ่านใหม่สำหรับการเข้าใช้งานครั้งแรกเพื่อความปลอดภัย');
        }

        return redirect()->route('student.dashboard')->with('success', 'เข้าสู่ระบบสำเร็จ ยินดีต้อนรับ ' . $student->full_name);
    }

    /**
     * ออกจากระบบ
     */
    public function logout()
    {
        session()->forget('student_user');
        return redirect()->route('student.login')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }

    /**
     * หน้าแดชบอร์ดนิสิต (ตรวจสอบจำนวนวันกรรมฐาน - Student-HistoryRequest.png)
     */
    public function dashboard(Request $request)
    {
        $studentSession = session('student_user');
        if (!$studentSession) {
            return redirect()->route('student.login');
        }

        // หากมีการค้นหารหัสนิสิตผ่านช่อง search
        $searchCode = trim($request->input('search', ''));
        if (!empty($searchCode)) {
            $student = Student::with('organizationUnit')->where('student_code', $searchCode)->first();
            if (!$student) {
                $student = Student::with('organizationUnit')->findOrFail($studentSession['id']);
                session()->flash('search_not_found', "ไม่พบข้อมูลนิสิตรหัส {$searchCode}");
            }
        } else {
            $student = Student::with('organizationUnit')->findOrFail($studentSession['id']);
        }

        // 1. ดึงประวัติการลงทะเบียน ป.ตรี (ถ้ามี)
        $ugRegistrations = UgRegistration::with('batch.organizationUnit')
            ->where('student_code', $student->student_code)
            ->orderByDesc('id')
            ->get();

        $ugCompletedDays = $ugRegistrations->where('status', 'COMPLETED')->count() * 10;

        // 2. ดึงประวัติบัณฑิตศึกษา (ถ้ามี)
        $gradRecord = GradStudent::with(['creditEntries', 'organizationUnit'])
            ->where('student_code', $student->student_code)
            ->first();

        // 3. ดึงประวัติการเข้าร่วมคอร์สปฏิบัติธรรมทั่วไป (โมดูล 3)
        $publicRegistrations = PublicRegistration::with('event.organizationUnit')
            ->where('student_id', $student->student_code)
            ->orderByDesc('id')
            ->get();

        return view('portal.student_dashboard', compact(
            'student',
            'ugRegistrations',
            'ugCompletedDays',
            'gradRecord',
            'publicRegistrations',
            'searchCode'
        ));
    }

    /**
     * หน้าข้อมูลส่วนตัวและเปลี่ยนรหัสผ่าน
     */
    public function profile()
    {
        $studentSession = session('student_user');
        if (!$studentSession) {
            return redirect()->route('student.login');
        }

        $student = Student::with('organizationUnit')->findOrFail($studentSession['id']);

        return view('portal.student_profile', compact('student'));
    }

    /**
     * บันทึกการแก้ไขข้อมูลติดต่อ (เบอร์โทร, อีเมล, ที่อยู่, รูปโปรไฟล์)
     */
    public function updateProfile(Request $request)
    {
        $studentSession = session('student_user');
        if (!$studentSession) {
            return redirect()->route('student.login');
        }

        $student = Student::findOrFail($studentSession['id']);

        $request->validate([
            'citizen_id' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'birth_date' => 'nullable|date',
            'age' => 'nullable|integer|min:1|max:120',
            'vassa' => 'nullable|integer|min:0|max:100',
            'nationality' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'blood_group' => 'nullable|string|max:10',
            'house_no' => 'nullable|string|max:50',
            'moo' => 'nullable|string|max:50',
            'temple_village' => 'nullable|string|max:150',
            'subdistrict' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'line_id' => 'nullable|string|max:100',
            'facebook' => 'nullable|string|max:150',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'avatar.image' => 'ไฟล์ที่แนบต้องเป็นรูปภาพเท่านั้น',
            'avatar.max' => 'ขนาดรูปภาพต้องไม่เกิน 5MB',
        ]);

        $updateData = [
            'citizen_id' => $request->citizen_id,
            'phone' => $request->phone,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'age' => $request->age,
            'vassa' => $request->vassa,
            'nationality' => $request->nationality ?? 'ไทย',
            'religion' => $request->religion ?? 'พุทธ',
            'blood_group' => $request->blood_group,
            'house_no' => $request->house_no,
            'moo' => $request->moo,
            'temple_village' => $request->temple_village,
            'subdistrict' => $request->subdistrict,
            'district' => $request->district,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'line_id' => $request->line_id,
            'facebook' => $request->facebook,
        ];

        // อัปโหลดรูปโปรไฟล์
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $filename = 'avatar_' . $student->student_code . '_' . time() . '.' . $avatarFile->getClientOriginalExtension();
            $path = $avatarFile->storeAs('avatars', $filename, 'public');
            $updateData['avatar_path'] = $path;
        }

        $student->update($updateData);

        return back()->with('success', 'บันทึกข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }

    /**
     * เปลี่ยนรหัสผ่าน
     */
    public function updatePassword(Request $request)
    {
        $studentSession = session('student_user');
        if (!$studentSession) {
            return redirect()->route('student.login');
        }

        $student = Student::findOrFail($studentSession['id']);

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'โปรดระบุรหัสผ่านปัจจุบัน',
            'new_password.required' => 'โปรดระบุรหัสผ่านใหม่',
            'new_password.min' => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร',
            'new_password.confirmed' => 'การยืนยันรหัสผ่านใหม่ไม่ตรงกัน',
        ]);

        if (!$student->verifyPassword($request->current_password)) {
            return back()->with('password_error', 'รหัสผ่านปัจจุบันไม่ถูกต้อง');
        }

        $student->update([
            'password' => Hash::make($request->new_password),
            'force_password_change' => false,
        ]);

        // อัปเดตสถานะใน session
        $sess = session('student_user');
        $sess['force_password_change'] = false;
        session(['student_user' => $sess]);

        return back()->with('password_success', 'เปลี่ยนรหัสผ่านใหม่สำเร็จเรียบร้อยแล้ว');
    }
}
