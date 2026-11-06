<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ปรับชื่อสังกัดทางการตามแนวทางที่ถูกต้องของมหาวิทยาลัย
     */
    public function up(): void
    {
        // 1. วิทยาเขตนครน่าน เฉลิมพระเกียรติฯ (มีเครื่องหมาย ฯ)
        DB::table('organization_units')->where('id', 19)->update([
            'name_th' => 'วิทยาเขตนครน่าน เฉลิมพระเกียรติฯ',
            'name_en' => 'Nakhon Nan Campus (Chaloem Phra Kiat)ฯ',
        ]);

        // 2. วิทยาลัยสงฆ์พ่อขุนผาเมือง
        DB::table('organization_units')->where('id', 36)->update([
            'name_th' => 'วิทยาลัยสงฆ์พ่อขุนผาเมือง',
            'name_en' => 'Pho Khun Pha Mueang Sangha College',
        ]);

        // 3. วิทยาลัยสงฆ์กำแพงเพชร
        DB::table('organization_units')->where('id', 48)->update([
            'name_th' => 'วิทยาลัยสงฆ์กำแพงเพชร',
            'name_en' => 'Kamphaeng Phet Sangha College',
        ]);

        // 4. วิทยาลัยสงฆ์สงขลา
        DB::table('organization_units')->where('id', 49)->update([
            'code' => 'SANGHA-SKA',
            'name_th' => 'วิทยาลัยสงฆ์สงขลา',
            'name_en' => 'Songkhla Sangha College',
            'type' => 'SANGHA_COLLEGE',
        ]);

        // 5. หน่วยวิทยบริการ จังหวัดอุตรดิตถ์ (เดิมคือ หน่วยวิทยบริการวัดหมอนไม้)
        DB::table('organization_units')->where('id', 50)->update([
            'code' => 'UNIT-UTT',
            'name_th' => 'หน่วยวิทยบริการ จังหวัดอุตรดิตถ์',
            'name_en' => 'Academic Unit of Uttaradit Province',
            'type' => 'ACADEMIC_UNIT',
        ]);

        // 6. หน่วยวิทยบริการ จังหวัดกาฬสินธุ์
        DB::table('organization_units')->where('id', 51)->update([
            'code' => 'UNIT-KSN',
            'name_th' => 'หน่วยวิทยบริการ จังหวัดกาฬสินธุ์',
            'name_en' => 'Academic Unit of Kalasin Province',
            'type' => 'ACADEMIC_UNIT',
        ]);

        // 7. หน่วยวิทยบริการ จังหวัดสมุทรสงคราม (เดิมคือ หน่วยวิทยบริการวัดอินทาราม จ.สมุทรสงคราม)
        DB::table('organization_units')->where('id', 52)->update([
            'code' => 'UNIT-SKM',
            'name_th' => 'หน่วยวิทยบริการ จังหวัดสมุทรสงคราม',
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

