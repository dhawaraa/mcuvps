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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_unit_id')->nullable()->comment('ส่วนงาน มจร ต้นสังกัด');
            $table->string('announcement_no', 100)->nullable()->comment('เลขที่ประกาศ / คำสั่ง เช่น ๑๒/๒๕๖๗');
            $table->string('title', 255)->comment('หัวข้อประกาศ');
            $table->string('title_en', 255)->nullable()->comment('หัวข้อประกาศ (อังกฤษ)');
            $table->text('content')->nullable()->comment('เนื้อหารายละเอียดประกาศ');
            $table->text('content_en')->nullable()->comment('เนื้อหารายละเอียดประกาศ (อังกฤษ)');
            $table->string('category', 50)->default('PARTICIPANTS')->comment('หมวดหมู่: PARTICIPANTS (รายชื่อผู้มีสิทธิ์), SCHEDULE (กำหนดการ), ORDER (คำสั่ง), GENERAL (ทั่วไป)');
            $table->string('attachment_path', 500)->nullable()->comment('พาธไฟล์เอกสารแนบ PDF/DOC');
            $table->string('attachment_name', 255)->nullable()->comment('ชื่อไฟล์เดิม');
            $table->string('attachment_size', 50)->nullable()->comment('ขนาดไฟล์ เช่น 1.8 MB');
            $table->string('attachment_mime', 100)->nullable()->default('application/pdf')->comment('MIME type');
            $table->longText('attachment_text')->nullable()->comment('ข้อความที่สกัดจากไฟล์ PDF สำหรับค้นหารายชื่อ');
            $table->boolean('is_pinned')->default(false)->comment('ปักหมุดประกาศสำคัญ');
            $table->enum('status', ['DRAFT', 'PUBLISHED', 'ARCHIVED'])->default('PUBLISHED')->comment('สถานะการเผยแพร่');
            $table->unsignedInteger('views')->default(0)->comment('ยอดเข้าชม');
            $table->timestamp('published_at')->nullable()->comment('วันเวลาที่เผยแพร่');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index('org_unit_id');
            $table->index('category');
            $table->index('status');
            $table->index('is_pinned');
            $table->index('published_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

