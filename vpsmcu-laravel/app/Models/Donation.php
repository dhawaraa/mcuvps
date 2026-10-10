<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use SoftDeletes;

    protected $table = 'donations';
    protected $guarded = [];

    protected $casts = [
        'is_tax_deductible' => 'boolean',
        'amount' => 'decimal:2',
        'transfer_date' => 'date',
        'verified_at' => 'datetime',
    ];

    protected $appends = [
        'slip_url',
        'avatar_url',
    ];

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * ดึง URL สลิปหลักฐาน รองรับทั้งโฮสติ้งจริง (ผ่าน /storage.php) และ Local
     */
    public function getSlipUrlAttribute(): ?string
    {
        if (empty($this->slip_path)) {
            return null;
        }
        return url('/storage.php/' . ltrim($this->slip_path, '/'));
    }

    /**
     * ดึง URL ภาพประจำตัวผู้บริจาค
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar_path)) {
            return null;
        }
        return url('/storage.php/' . ltrim($this->avatar_path, '/'));
    }
}
