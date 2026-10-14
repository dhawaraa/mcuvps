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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID ผู้ใช้งาน (User ID)');
            $table->string('username', 100)->nullable()->comment('ชื่อผู้ใช้งาน (Username)');
            $table->string('user_fullname', 255)->nullable()->comment('ชื่อ-นามสกุล ผู้ใช้งาน');
            $table->string('user_role', 50)->nullable()->comment('สิทธิ์การใช้งาน (Role)');
            $table->unsignedBigInteger('org_unit_id')->nullable()->comment('ส่วนงาน มจร ต้นสังกัด');
            $table->string('action', 50)->comment('ประเภทกิจกรรม (LOGIN, LOGOUT, CREATE, UPDATE, DELETE, STATUS_CHANGE, etc.)');
            $table->string('module', 50)->comment('โมดูลที่เกี่ยวข้อง (AUTH, PUBLIC_EVENTS, UG_BATCHES, GRAD, USERS, etc.)');
            $table->text('description')->comment('คำอธิบายรายละเอียดกิจกรรม');
            $table->json('details')->nullable()->comment('ข้อมูลเพิ่มเติมหรือ Payload ในรูปแบบ JSON');
            $table->string('ip_address', 45)->nullable()->comment('IP Address ของผู้ใช้งาน');
            $table->text('user_agent')->nullable()->comment('เบราว์เซอร์และอุปกรณ์ของผู้ใช้งาน');
            $table->timestamp('created_at')->useCurrent()->comment('วันเวลาที่ทำรายการ');

            $table->index('user_id');
            $table->index('action');
            $table->index('module');
            $table->index('org_unit_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
