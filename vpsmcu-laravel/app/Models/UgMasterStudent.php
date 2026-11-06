<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UgMasterStudent extends Model
{
    protected $table = 'ug_master_students';
    protected $guarded = [];

    public function organizationUnit()
    {
        return $this->belongsTo(OrganizationUnit::class, 'org_unit_id');
    }

    public function registrations()
    {
        return $this->hasMany(UgRegistration::class, 'student_code', 'student_code');
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
}
