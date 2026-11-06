<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. ตาราง students
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'name')) {
                $table->string('name', 255)->nullable()->after('password')->comment('ชื่อ - ฉายา/นามสกุล (ฟิลด์เดี่ยว)');
            }
        });

        // แก้ไขชนิดข้อมูลในตาราง students (ใช้คำสั่ง SQL โดยตรงเพื่อความเข้ากันได้ 100% กับ MariaDB)
        DB::statement("ALTER TABLE students MODIFY COLUMN degree_level VARCHAR(50) NOT NULL DEFAULT 'BACHELOR'");
        DB::statement("ALTER TABLE students MODIFY COLUMN prefix VARCHAR(50) NULL DEFAULT ''");
        DB::statement("ALTER TABLE students MODIFY COLUMN first_name VARCHAR(100) NULL DEFAULT ''");
        DB::statement("ALTER TABLE students MODIFY COLUMN last_name VARCHAR(100) NULL DEFAULT ''");
        DB::statement("ALTER TABLE students MODIFY COLUMN chaya VARCHAR(100) NULL DEFAULT ''");

        // 2. ตาราง ug_master_students
        Schema::table('ug_master_students', function (Blueprint $table) {
            if (!Schema::hasColumn('ug_master_students', 'name')) {
                $table->string('name', 255)->nullable()->after('citizen_id')->comment('ชื่อ - ฉายา/นามสกุล (ฟิลด์เดี่ยว)');
            }
        });

        DB::statement("ALTER TABLE ug_master_students MODIFY COLUMN prefix VARCHAR(50) NULL DEFAULT ''");
        DB::statement("ALTER TABLE ug_master_students MODIFY COLUMN first_name VARCHAR(100) NULL DEFAULT ''");
        DB::statement("ALTER TABLE ug_master_students MODIFY COLUMN last_name VARCHAR(100) NULL DEFAULT ''");
        DB::statement("ALTER TABLE ug_master_students MODIFY COLUMN chaya VARCHAR(100) NULL DEFAULT ''");
        DB::statement("ALTER TABLE ug_master_students MODIFY COLUMN degree_level VARCHAR(50) NULL DEFAULT 'ปริญญาตรี'");

        // 3. ปรับปรุงข้อมูลเดิมที่มีอยู่ ให้เติมลงในคอลัมน์ name ทันที
        DB::statement("UPDATE students SET name = TRIM(CONCAT(COALESCE(prefix,''), ' ', COALESCE(first_name,''), ' ', COALESCE(chaya,''), ' ', COALESCE(last_name,''))) WHERE name IS NULL OR name = ''");
        DB::statement("UPDATE ug_master_students SET name = TRIM(CONCAT(COALESCE(prefix,''), ' ', COALESCE(first_name,''), ' ', COALESCE(chaya,''), ' ', COALESCE(last_name,''))) WHERE name IS NULL OR name = ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'name')) {
                $table->dropColumn('name');
            }
        });

        Schema::table('ug_master_students', function (Blueprint $table) {
            if (Schema::hasColumn('ug_master_students', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
