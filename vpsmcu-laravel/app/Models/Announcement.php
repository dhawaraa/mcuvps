<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';
    protected $guarded = [];

    public function organizationUnit()
    {
        return $this->belongsTo(OrganizationUnit::class, 'org_unit_id');
    }

    public function getLocalizedTitleAttribute()
    {
        if (app()->getLocale() === 'en' && !empty($this->title_en)) {
            return $this->title_en;
        }
        return $this->title;
    }

    public function getLocalizedContentAttribute()
    {
        if (app()->getLocale() === 'en' && !empty($this->content_en)) {
            return $this->content_en;
        }
        return $this->content;
    }

    public function getAttachmentUrlAttribute()
    {
        if (empty($this->attachment_path)) {
            return null;
        }
        if (str_starts_with($this->attachment_path, '/storage/')) {
            return '/storage.php/' . substr($this->attachment_path, 9);
        }
        return $this->attachment_path;
    }

    public function getCategoryLabelAttribute()
    {
        return match ($this->category) {
            'PARTICIPANTS' => 'ประกาศรายชื่อผู้มีสิทธิ์',
            'SCHEDULE' => 'กำหนดการและสถานที่',
            'ORDER' => 'คำสั่ง / ระเบียบปฏิบัติ',
            default => 'ประกาศทั่วไป',
        };
    }

    public function getCategoryBadgeClassAttribute()
    {
        return match ($this->category) {
            'PARTICIPANTS' => 'bg-[#5A6B47]/15 text-[#5A6B47] border-[#5A6B47]/30',
            'SCHEDULE' => 'bg-[#C86D51]/15 text-[#C86D51] border-[#C86D51]/30',
            'ORDER' => 'bg-[#2C3E2D]/15 text-[#2C3E2D] border-[#2C3E2D]/30',
            default => 'bg-[#7B8D65]/15 text-[#7B8D65] border-[#7B8D65]/30',
        };
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'PUBLISHED');
    }

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('announcement_no', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('attachment_name', 'like', "%{$search}%")
              ->orWhere('attachment_text', 'like', "%{$search}%");
        });
    }
}

