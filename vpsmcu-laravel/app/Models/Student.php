<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Student extends Model
{
    protected $table = 'students';

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'force_password_change' => 'boolean',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * แปลงวันเกิดเป็นรูปแบบไทย (เช่น 15 มีนาคม 2544)
     */
    public function getThaiBirthDateAttribute(): string
    {
        if (!$this->birth_date) {
            return '-';
        }
        $thaiMonths = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        $d = $this->birth_date->day;
        $m = $thaiMonths[$this->birth_date->month] ?? '';
        $y = $this->birth_date->year + 543;
        return "{$d} {$m} {$y}";
    }

    /**
     * คำนวณชื่อ-สกุลเต็มตามธรรมเนียมนิยม มจร (ชื่อ - ฉายา/นามสกุล)
     */
    public function getFullNameAttribute(): string
    {
        if (!empty($this->attributes['name'] ?? null)) {
            return $this->attributes['name'];
        }
        if (!empty($this->chaya)) {
            return trim("{$this->prefix}{$this->first_name} {$this->chaya} {$this->last_name}");
        }
        return trim("{$this->prefix}{$this->first_name} {$this->last_name}");
    }

    /**
     * ชื่อ - ฉายา/นามสกุล
     */
    public function getNameAttribute(): string
    {
        return $this->getFullNameAttribute();
    }

    /**
     * สังกัดวิทยาเขต / ส่วนงาน มจร
     */
    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class, 'org_unit_id');
    }

    /**
     * ประวัติการลงทะเบียนปฏิบัติธรรม ป.ตรี
     */
    public function ugRegistrations(): HasMany
    {
        return $this->hasMany(UgRegistration::class, 'student_code', 'student_code');
    }

    /**
     * ข้อมูลสะสมวัน บัณฑิตศึกษา (ป.โท / ป.เอก)
     */
    public function gradStudent(): BelongsTo
    {
        return $this->belongsTo(GradStudent::class, 'student_code', 'student_code');
    }

    /**
     * ดึง URL รูปประจำตัวที่รองรับทั้งโฮสติ้งจริง (ผ่าน /storage.php) และ Local
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar_path)) {
            return null;
        }

        // หากรันบน FreeBSD Shared Hosting (ไม่มี mod_rewrite สำหรับไดเรกทอรี /storage)
        return url('/storage.php/' . ltrim($this->avatar_path, '/'));
    }

    /**
     * ตรวจสอบความถูกต้องของรหัสผ่าน
     */
    public function verifyPassword(string $password): bool
    {
        if (Hash::check($password, $this->password)) {
            return true;
        }
        // ตรวจสอบกรณีรหัสผ่านเป็น plain text (และอัปเกรดเป็น bcrypt อัตโนมัติเมื่อล็อกอิน)
        if ($this->password === $password) {
            $this->update(['password' => Hash::make($password)]);
            return true;
        }
        return false;
    }
}
