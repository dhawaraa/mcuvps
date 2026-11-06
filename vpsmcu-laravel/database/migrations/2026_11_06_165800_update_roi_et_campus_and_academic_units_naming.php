<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ปรับปรุงสถานะวิทยาลัยสงฆ์ร้อยเอ็ดเป็นวิทยาเขตร้อยเอ็ด และปรับชื่อหน่วยวิทยบริการให้ถูกต้องตามระเบียบ
     */
    public function up(): void
    {
        // 1. ปรับวิทยาลัยสงฆ์ร้อยเอ็ดเป็น "วิทยาเขตร้อยเอ็ด" (ประเภท CAMPUS)
        DB::table('organization_units')->where('id', 34)->update([
            'code' => 'CAMPUS-RET',
            'name_th' => 'วิทยาเขตร้อยเอ็ด',
            'name_en' => 'Roi Et Campus',
            'type' => 'CAMPUS',
        ]);

        // 2. วิทยาเขตนครน่าน เฉลิมพระเกียรติฯ (ยืนยันความถูกต้อง)
        DB::table('organization_units')->where('id', 19)->update([
            'name_th' => 'วิทยาเขตนครน่าน เฉลิมพระเกียรติฯ',
            'name_en' => 'Nakhon Nan Campus (Chaloem Phra Kiat)ฯ',
            'type' => 'CAMPUS',
        ]);

        // 3. หน่วยวิทยบริการจังหวัดอุตรดิตถ์ (ไม่มีเว้นวรรค)
        DB::table('organization_units')->where('id', 50)->update([
            'name_th' => 'หน่วยวิทยบริการจังหวัดอุตรดิตถ์',
            'name_en' => 'Academic Unit of Uttaradit Province',
            'type' => 'ACADEMIC_UNIT',
        ]);

        // 4. หน่วยวิทยบริการจังหวัดกาฬสินธุ์ (ไม่มีเว้นวรรค)
        DB::table('organization_units')->where('id', 51)->update([
            'name_th' => 'หน่วยวิทยบริการจังหวัดกาฬสินธุ์',
            'name_en' => 'Academic Unit of Kalasin Province',
            'type' => 'ACADEMIC_UNIT',
        ]);

        // 5. หน่วยวิทยบริการจังหวัดสมุทรสงคราม (ไม่มีเว้นวรรค)
        DB::table('organization_units')->where('id', 52)->update([
            'name_th' => 'หน่วยวิทยบริการจังหวัดสมุทรสงคราม',
            'name_en' => 'Academic Unit of Samut Songkhram Province',
            'type' => 'ACADEMIC_UNIT',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
