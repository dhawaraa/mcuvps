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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_code', 20)->unique()->comment('รหัสนิสิต มจร');
            $table->string('citizen_id', 13)->nullable()->comment('เลขประจำตัวประชาชน');
            $table->string('password')->comment('รหัสผ่านเข้าระบบ (Hashed)');
            $table->string('prefix', 50)->comment('คำนำหน้านาม เช่น พระ, พระมหา, นาย, นางสาว');
            $table->string('first_name', 100)->comment('ชื่อ');
            $table->string('last_name', 100)->comment('นามสกุล');
            $table->string('chaya', 100)->nullable()->comment('ฉายา/ฉายานาม');
            $table->enum('degree_level', ['BACHELOR', 'MASTER', 'DOCTORAL'])->default('BACHELOR')->comment('ระดับการศึกษา');
            $table->unsignedBigInteger('org_unit_id')->nullable()->comment('วิทยาเขต/ส่วนงานต้นสังกัด');
            $table->string('faculty', 150)->nullable()->comment('คณะ');
            $table->string('major', 150)->nullable()->comment('สาขาวิชา');
            $table->unsignedInteger('study_year')->nullable()->comment('ชั้นปีที่กำลังศึกษา');
            $table->string('phone', 30)->nullable()->comment('เบอร์โทรศัพท์');
            $table->string('email', 150)->nullable()->comment('อีเมล');
            $table->text('address')->nullable()->comment('ที่อยู่ติดต่อปัจจุบัน');
            $table->string('avatar_path', 255)->nullable()->comment('รูปโปรไฟล์นิสิต');
            $table->boolean('force_password_change')->default(false)->comment('บังคับเปลี่ยนรหัสผ่านเมื่อเข้าสู่ระบบครั้งแรก');
            $table->boolean('is_active')->default(true)->comment('สถานะการใช้งาน (1=ปกติ, 0=ระงับ)');
            $table->dateTime('last_login_at')->nullable()->comment('เวลาที่เข้าสู่ระบบล่าสุด');
            $table->timestamps();

            $table->index('org_unit_id');
            $table->index('degree_level');
            $table->index('citizen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
