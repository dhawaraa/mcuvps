<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $defaults = [
            [
                'setting_key' => 'module_public_enabled',
                'setting_value' => '1',
                'setting_group' => 'modules',
                'label' => 'โมดูลคอร์สปฏิบัติธรรมสำหรับประชาชน (Public Courses)',
                'field_type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'module_ug_enabled',
                'setting_value' => '1',
                'setting_group' => 'modules',
                'label' => 'โมดูลปฏิบัติธรรม ป.ตรี 40 วัน (Undergraduate)',
                'field_type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'module_grad_enabled',
                'setting_value' => '1',
                'setting_group' => 'modules',
                'label' => 'โมดูลหนังสือรับรอง บัณฑิตศึกษา ป.โท/ป.เอก (Graduate)',
                'field_type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'module_donation_enabled',
                'setting_value' => '1',
                'setting_group' => 'modules',
                'label' => 'โมดูลระบบรับบริจาคเพื่อการปฏิบัติธรรม (Donations)',
                'field_type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'module_student_portal_enabled',
                'setting_value' => '1',
                'setting_group' => 'modules',
                'label' => 'โมดูลพอร์ทัลนิสิต มจร (Student Portal)',
                'field_type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'module_news_enabled',
                'setting_value' => '1',
                'setting_group' => 'modules',
                'label' => 'โมดูลข่าวสารและประชาสัมพันธ์ (News & Articles)',
                'field_type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($defaults as $d) {
            \Illuminate\Support\Facades\DB::table('site_settings')->updateOrInsert(
                ['setting_key' => $d['setting_key']],
                $d
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('site_settings')
            ->where('setting_group', 'modules')
            ->delete();
    }
};
