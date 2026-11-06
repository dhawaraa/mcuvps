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
        Schema::table('students', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('citizen_id');
            $table->integer('age')->nullable()->after('birth_date');
            $table->integer('vassa')->nullable()->after('age')->comment('พรรษา (สำหรับพระสงฆ์)');
            $table->string('nationality', 50)->default('ไทย')->after('vassa');
            $table->string('religion', 50)->default('พุทธ')->after('nationality');
            $table->string('blood_group', 10)->nullable()->after('religion');

            // ที่อยู่ปัจจุบัน
            $table->string('house_no', 50)->nullable()->after('address');
            $table->string('moo', 50)->nullable()->after('house_no');
            $table->string('temple_village', 150)->nullable()->after('moo');
            $table->string('subdistrict', 100)->nullable()->after('temple_village');
            $table->string('district', 100)->nullable()->after('subdistrict');
            $table->string('province', 100)->nullable()->after('district');
            $table->string('postal_code', 20)->nullable()->after('province');

            // ข้อมูลติดต่อเพิ่มเติม
            $table->string('line_id', 100)->nullable()->after('email');
            $table->string('facebook', 150)->nullable()->after('line_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date', 'age', 'vassa', 'nationality', 'religion', 'blood_group',
                'house_no', 'moo', 'temple_village', 'subdistrict', 'district', 'province', 'postal_code',
                'line_id', 'facebook'
            ]);
        });
    }
};
