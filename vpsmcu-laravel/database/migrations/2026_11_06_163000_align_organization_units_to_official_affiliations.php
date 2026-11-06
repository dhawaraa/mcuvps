<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ปรับปรุงข้อมูลสังกัด (ส่วนจัดการศึกษา) ทั้ง 45 สังกัดของ มจร ให้สอดคล้องกับฐานข้อมูลทะเบียนกลาง 100%
     */
    public function up(): void
    {
        // 1. ปรับ ID 1 ให้เป็น "มจร พระนครศรีอยุธยา" (ส่วนกลาง)
        DB::table('organization_units')->where('id', 1)->update([
            'code' => 'MCU-AYA',
            'name_th' => 'มจร พระนครศรีอยุธยา',
            'name_en' => 'MCU Ayutthaya (Main Campus)',
            'type' => 'CENTRAL',
            'province_th' => 'พระนครศรีอยุธยา',
            'province_code' => 'AYA',
            'is_active' => 1,
        ]);

        // 2. ปรับชื่อวิทยาเขต / วิทยาลัยสงฆ์ / หน่วยวิทยบริการ ให้ตรงตามทะเบียน มจร 45 สังกัด
        DB::table('organization_units')->where('id', 19)->update([
            'name_th' => 'วิทยาเขตนครน่าน เฉลิมพระเกียรติ',
            'name_en' => 'Nakhon Nan Campus (Chaloem Phra Kiat)',
        ]);

        DB::table('organization_units')->where('id', 36)->update([
            'name_th' => 'วิทยาลัยสงฆ์พ่อขุนผาเมือง เพชรบูรณ์',
            'name_en' => 'Pho Khun Pha Mueang Phetchabun Sangha College',
        ]);

        DB::table('organization_units')->where('id', 48)->update([
            'name_th' => 'วิทยาลัยสงฆ์กำแพงเพชร',
            'name_en' => 'Kamphaeng Phet Sangha College',
        ]);

        DB::table('organization_units')->where('id', 49)->update([
            'code' => 'UNIT-SKA',
            'name_th' => 'หน่วยวิทยบริการ วิทยาลัยสงฆ์นครศรีธรรมราช จังหวัดสงขลา',
            'name_en' => 'Academic Unit of Nakhon Si Thammarat Sangha College, Songkhla',
            'type' => 'ACADEMIC_UNIT',
        ]);

        DB::table('organization_units')->where('id', 50)->update([
            'name_th' => 'หน่วยวิทยบริการวัดหมอนไม้',
            'name_en' => 'Academic Unit of Wat Mon Mai (Uttaradit)',
            'type' => 'ACADEMIC_UNIT',
        ]);

        DB::table('organization_units')->where('id', 51)->update([
            'name_th' => 'หน่วยวิทยบริการจังหวัดกาฬสินธุ์',
            'name_en' => 'Academic Unit of Kalasin Province',
            'type' => 'ACADEMIC_UNIT',
        ]);

        DB::table('organization_units')->where('id', 52)->update([
            'name_th' => 'หน่วยวิทยบริการวัดอินทาราม จ.สมุทรสงคราม',
            'name_en' => 'Academic Unit of Wat Intharam (Samut Songkhram)',
            'type' => 'ACADEMIC_UNIT',
        ]);

        // 3. ปรับสถานะคณะส่วนกลางย่อย (IDs 2-7, 53) ให้ is_active = 0 (เนื่องจากสังกัดหลักคือ "มจร พระนครศรีอยุธยา" ID 1 ส่วนคณะจะถูกจัดเก็บในฟิลด์ faculty)
        DB::table('organization_units')->whereIn('id', [2, 3, 4, 5, 6, 7, 53])->update([
            'is_active' => 0,
        ]);

        // 4. โอนย้ายนิสิตส่วนกลางที่เคยผูกกับคณะย่อย ให้สังกัด "มจร พระนครศรีอยุธยา" (ID 1)
        DB::table('students')->whereIn('org_unit_id', [2, 3, 4, 5, 6, 7, 53])->update([
            'org_unit_id' => 1,
        ]);

        DB::table('ug_master_students')->whereIn('org_unit_id', [2, 3, 4, 5, 6, 7, 53])->update([
            'org_unit_id' => 1,
        ]);

        // 5. โอนย้ายเจ้าหน้าที่และโครงการส่วนกลางที่เคยผูกกับ ID 53 ให้สังกัด "มจร พระนครศรีอยุธยา" (ID 1)
        DB::table('users')->where('org_unit_id', 53)->update(['org_unit_id' => 1]);
        DB::table('ug_batches')->where('org_unit_id', 53)->update(['org_unit_id' => 1]);
        DB::table('ug_registrations')->where('org_unit_id', 53)->update(['org_unit_id' => 1]);
        DB::table('grad_students')->where('org_unit_id', 53)->update(['org_unit_id' => 1]);
        DB::table('public_events')->where('org_unit_id', 53)->update(['org_unit_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('organization_units')->whereIn('id', [2, 3, 4, 5, 6, 7, 53])->update([
            'is_active' => 1,
        ]);
    }
};

