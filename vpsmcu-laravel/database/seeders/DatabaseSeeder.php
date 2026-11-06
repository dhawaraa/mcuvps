<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\OrganizationUnit;
use App\Models\UgBatch;
use App\Models\UgMasterStudent;
use App\Models\UgRegistration;
use App\Models\GradStudent;
use App\Models\GradCreditEntry;
use App\Models\PublicEvent;
use App\Models\PublicRegistration;
use App\Models\NewsArticle;
use App\Models\Announcement;
use App\Models\MeditationCalendar;
use App\Models\SiteSetting;
use App\Models\ContactInquiry;
use App\Models\Donation;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with at least 3 comprehensive sample records for all modules.
     */
    public function run(): void
    {
        // 1. เพิ่มผู้ใช้งานตัวอย่าง (Users & Roles) เพิ่มเติมจากเดิม (มี 4 คนแรกอยู่แล้วใน init.sql)
        $sampleUsers = [
            [
                'username' => 'master_somchai',
                'password_hash' => '$2y$12$XDe43h2HZhg2DwbxUyqjMe6mlHaZWN.hf.pmu1LPJ9LkpHFSt.B/S', // password
                'full_name' => 'พระมหาสมชาย ญาณวโร (พระวิปัสสนาจารย์ประจำศูนย์)',
                'email' => 'somchai.mas@mcu.ac.th',
                'role' => 'VIPASSANA_MASTER',
                'org_unit_id' => 1, // บัณฑิตวิทยาลัย / ส่วนกลาง
                'is_active' => 1,
            ],
            [
                'username' => 'grad_officer01',
                'password_hash' => '$2y$12$XDe43h2HZhg2DwbxUyqjMe6mlHaZWN.hf.pmu1LPJ9LkpHFSt.B/S', // password
                'full_name' => 'นายเกรียงไกร สิทธิโชค (เจ้าหน้าที่งานทะเบียนบัณฑิตศึกษา)',
                'email' => 'grad_reg@mcu.ac.th',
                'role' => 'GRAD_OFFICER',
                'org_unit_id' => 1,
                'is_active' => 1,
            ],
            [
                'username' => 'public_staff01',
                'password_hash' => '$2y$12$XDe43h2HZhg2DwbxUyqjMe6mlHaZWN.hf.pmu1LPJ9LkpHFSt.B/S', // password
                'full_name' => 'นางสาวกานดา สุวรรณรัตน์ (เจ้าหน้าที่บริการวิชาการแก่สังคม)',
                'email' => 'public_service@mcu.ac.th',
                'role' => 'PUBLIC_STAFF',
                'org_unit_id' => 10, // วิทยาเขตเชียงใหม่
                'is_active' => 1,
            ],
        ];

        foreach ($sampleUsers as $u) {
            User::updateOrCreate(['username' => $u['username']], $u);
        }

        // 2. กำหนดการและโครงการปฏิบัติธรรม ป.ตรี (UgBatch) - อย่างน้อย 3 โครงการ
        $batches = [
            [
                'id' => 1,
                'org_unit_id' => 1, // มจร ส่วนกลาง อยุธยา
                'academic_year' => 2569,
                'batch_no' => 1,
                'title' => 'โครงการปฏิบัติวิปัสสนากรรมฐานประจำปี 2569 ผลัดที่ 1 (ส่วนกลาง)',
                'title_en' => 'Annual Vipassana Meditation Program 2026 Batch 1 (Central MCU)',
                'location' => 'อาคาร 74 ปี มหาจุฬาลงกรณราชวิทยาลัย วังน้อย พระนครศรีอยุธยา',
                'location_en' => '74th Anniversary Building, MCU Wang Noi, Phra Nakhon Si Ayutthaya',
                'start_date' => '2026-12-15',
                'end_date' => '2026-12-25',
                'max_quota' => 120,
                'status' => 'OPEN',
            ],
            [
                'id' => 2,
                'org_unit_id' => 10, // วิทยาเขตเชียงใหม่
                'academic_year' => 2569,
                'batch_no' => 1,
                'title' => 'โครงการพัฒนาจิตนิสิตปริญญาตรี ประจำปี 2569 ผลัดที่ 1 (ล้านนา)',
                'title_en' => 'Undergraduate Spiritual Development Retreat 2026 Batch 1 (Lanna)',
                'location' => 'ศูนย์ปฏิบัติธรรมมหาจุฬาอาศรม มจร วิทยาเขตเชียงใหม่',
                'location_en' => 'Mahachula Ashram Meditation Center, MCU Chiang Mai Campus',
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-11',
                'max_quota' => 80,
                'status' => 'OPEN',
            ],
            [
                'id' => 3,
                'org_unit_id' => 11, // วิทยาเขตขอนแก่น
                'academic_year' => 2569,
                'batch_no' => 1,
                'title' => 'โครงการปฏิบัติวิปัสสนากรรมฐานเฉลิมพระเกียรติ ประจำปี 2569',
                'title_en' => 'Royal Honor Vipassana Meditation Retreat 2026',
                'location' => 'พุทธมณฑลอีสาน ศาลากลางน้ำ มจร วิทยาเขตขอนแก่น',
                'location_en' => 'Isan Buddhamonthon Water Pavilion, MCU Khon Kaen Campus',
                'start_date' => '2026-10-10',
                'end_date' => '2026-10-20',
                'max_quota' => 100,
                'status' => 'IN_PROGRESS',
            ],
            [
                'id' => 4,
                'org_unit_id' => 9, // วิทยาเขตนครศรีธรรมราช
                'academic_year' => 2568,
                'batch_no' => 2,
                'title' => 'โครงการปฏิบัติวิปัสสนากรรมฐานภาคใต้ ประจำปี 2568',
                'title_en' => 'Southern Regional Vipassana Meditation Retreat 2025',
                'location' => 'ศูนย์ปฏิบัติธรรมเขามหาชัย มจร วิทยาเขตนครศรีธรรมราช',
                'location_en' => 'Khao Mahachai Meditation Center, MCU Nakhon Si Thammarat Campus',
                'start_date' => '2025-12-01',
                'end_date' => '2025-12-11',
                'max_quota' => 90,
                'status' => 'COMPLETED',
            ],
        ];

        foreach ($batches as $b) {
            UgBatch::updateOrCreate(['id' => $b['id']], $b);
        }

        // 3. บัญชีรายชื่อนิสิตในฐานข้อมูลกลาง (UgMasterStudent) - อย่างน้อย 4 รายการ
        $masterStudents = [
            [
                'student_code' => '6601201001',
                'citizen_id' => '1100400123451',
                'prefix' => 'พระมหา',
                'first_name' => 'ปัญญา',
                'last_name' => 'เมธี',
                'chaya' => 'ปญฺญาเมธี',
                'degree_level' => 'ปริญญาตรี',
                'faculty' => 'พุทธศาสตร์',
                'major' => 'พระพุทธศาสนา',
                'org_unit_id' => 1, // ส่วนกลาง
                'study_year' => 3,
                'phone' => '081-234-5678',
                'email' => 'panya.med@mcu.ac.th',
                'is_active' => 1,
            ],
            [
                'student_code' => '6701201002',
                'citizen_id' => '1509900234562',
                'prefix' => 'พระ',
                'first_name' => 'สมคิด',
                'last_name' => 'จิตฺตทนฺโต',
                'chaya' => 'จิตฺตทนฺโต',
                'degree_level' => 'ปริญญาตรี',
                'faculty' => 'ครุศาสตร์',
                'major' => 'การสอนพระพุทธศาสนา',
                'org_unit_id' => 10, // เชียงใหม่
                'study_year' => 2,
                'phone' => '089-876-5432',
                'email' => 'somkid.cit@mcu.ac.th',
                'is_active' => 1,
            ],
            [
                'student_code' => '6801201003',
                'citizen_id' => '1400200345673',
                'prefix' => 'นาย',
                'first_name' => 'ธนภัทร',
                'last_name' => 'สุขเกษม',
                'chaya' => null,
                'degree_level' => 'ปริญญาตรี',
                'faculty' => 'สังคมศาสตร์',
                'major' => 'รัฐประศาสนศาสตร์',
                'org_unit_id' => 11, // ขอนแก่น
                'study_year' => 1,
                'phone' => '084-555-8899',
                'email' => 'thanapat.suk@mcu.ac.th',
                'is_active' => 1,
            ],
            [
                'student_code' => '6501201004',
                'citizen_id' => '1800100456784',
                'prefix' => 'สามเณร',
                'first_name' => 'ธีรเทพ',
                'last_name' => 'โสภณ',
                'chaya' => 'เตชวโร',
                'degree_level' => 'ปริญญาตรี',
                'faculty' => 'มนุษยศาสตร์',
                'major' => 'ภาษาอังกฤษ',
                'org_unit_id' => 1, // ส่วนกลาง
                'study_year' => 4,
                'phone' => '082-333-7744',
                'email' => 'theeratep.sop@mcu.ac.th',
                'is_active' => 1,
            ],
        ];

        foreach ($masterStudents as $ms) {
            UgMasterStudent::updateOrCreate(['student_code' => $ms['student_code']], $ms);
        }

        // 4. การลงทะเบียนนิสิต ป.ตรี (UgRegistration) - อย่างน้อย 4 รายการ (ครบทุกสถานะ COMPLETED, CHECKED_IN, REGISTERED)
        $ugRegistrations = [
            [
                'id' => 1,
                'registration_no' => '69-AYA-6601201001',
                'batch_id' => 1,
                'student_id' => '6601201001',
                'student_code' => '6601201001',
                'citizen_id' => '1100400123451',
                'prefix' => 'พระมหา',
                'first_name' => 'ปัญญา',
                'last_name' => 'เมธี',
                'chaya' => 'ปญฺญาเมธี',
                'full_name' => 'พระมหาปัญญา เมธี',
                'org_unit_id' => 1,
                'faculty' => 'พุทธศาสตร์',
                'major' => 'พระพุทธศาสนา',
                'class_year' => 3,
                'study_year' => 3,
                'program_type' => 'NORMAL',
                'phone' => '081-234-5678',
                'email' => 'panya.med@mcu.ac.th',
                'checkin_token' => 'UGTOKEN-6601201001-AYA',
                'checkin_status' => 1,
                'status' => 'COMPLETED',
                'final_result' => 'PASSED',
                'evaluation_result' => 'PASS',
                'evaluation_score' => 98,
                'checked_in_at' => '2026-12-15 08:30:00',
            ],
            [
                'id' => 2,
                'registration_no' => '69-CMI-6701201002',
                'batch_id' => 2,
                'student_id' => '6701201002',
                'student_code' => '6701201002',
                'citizen_id' => '1509900234562',
                'prefix' => 'พระ',
                'first_name' => 'สมคิด',
                'last_name' => 'จิตฺตทนฺโต',
                'chaya' => 'จิตฺตทนฺโต',
                'full_name' => 'พระสมคิด จิตฺตทนฺโต',
                'org_unit_id' => 10,
                'faculty' => 'ครุศาสตร์',
                'major' => 'การสอนพระพุทธศาสนา',
                'class_year' => 2,
                'study_year' => 2,
                'program_type' => 'NORMAL',
                'phone' => '089-876-5432',
                'email' => 'somkid.cit@mcu.ac.th',
                'checkin_token' => 'UGTOKEN-6701201002-CMI',
                'checkin_status' => 1,
                'status' => 'CHECKED_IN',
                'final_result' => 'PENDING',
                'evaluation_result' => 'PENDING',
                'evaluation_score' => 0,
                'checked_in_at' => '2026-11-01 09:00:00',
            ],
            [
                'id' => 3,
                'registration_no' => '69-KKN-6801201003',
                'batch_id' => 3,
                'student_id' => '6801201003',
                'student_code' => '6801201003',
                'citizen_id' => '1400200345673',
                'prefix' => 'นาย',
                'first_name' => 'ธนภัทร',
                'last_name' => 'สุขเกษม',
                'chaya' => null,
                'full_name' => 'นายธนภัทร สุขเกษม',
                'org_unit_id' => 11,
                'faculty' => 'สังคมศาสตร์',
                'major' => 'รัฐประศาสนศาสตร์',
                'class_year' => 1,
                'study_year' => 1,
                'program_type' => 'NORMAL',
                'phone' => '084-555-8899',
                'email' => 'thanapat.suk@mcu.ac.th',
                'checkin_token' => 'UGTOKEN-6801201003-KKN',
                'checkin_status' => 0,
                'status' => 'REGISTERED',
                'final_result' => 'PENDING',
                'evaluation_result' => 'PENDING',
                'evaluation_score' => 0,
                'checked_in_at' => null,
            ],
            [
                'id' => 4,
                'registration_no' => '69-AYA-6501201004',
                'batch_id' => 1,
                'student_id' => '6501201004',
                'student_code' => '6501201004',
                'citizen_id' => '1800100456784',
                'prefix' => 'สามเณร',
                'first_name' => 'ธีรเทพ',
                'last_name' => 'โสภณ',
                'chaya' => 'เตชวโร',
                'full_name' => 'สามเณรธีรเทพ โสภณ',
                'org_unit_id' => 1,
                'faculty' => 'มนุษยศาสตร์',
                'major' => 'ภาษาอังกฤษ',
                'class_year' => 4,
                'study_year' => 4,
                'program_type' => 'NORMAL',
                'phone' => '082-333-7744',
                'email' => 'theeratep.sop@mcu.ac.th',
                'checkin_token' => 'UGTOKEN-6501201004-AYA',
                'checkin_status' => 1,
                'status' => 'COMPLETED',
                'final_result' => 'PASSED',
                'evaluation_result' => 'PASS',
                'evaluation_score' => 95,
                'checked_in_at' => '2026-12-15 08:45:00',
            ],
        ];

        foreach ($ugRegistrations as $reg) {
            UgRegistration::updateOrCreate(
                ['batch_id' => $reg['batch_id'], 'student_code' => $reg['student_code']],
                $reg
            );
        }

        // 5. บัณฑิตศึกษา (GradStudent & GradCreditEntry) - อย่างน้อย 4 รายการ (ครบเกณฑ์ APPROVED, SUBMITTED, ACCUMULATING)
        $gradStudents = [
            [
                'id' => 1,
                'student_id' => '6501102001',
                'student_code' => '6501102001',
                'citizen_id' => '1100700543211',
                'prefix' => 'พระมหา',
                'first_name' => 'บุญช่วย',
                'last_name' => 'ปุญฺญกาโม',
                'full_name' => 'พระมหาบุญช่วย ปุญฺญกาโม',
                'degree_level' => 'MASTER',
                'target_days' => 30,
                'accumulated_days' => 30,
                'org_unit_id' => 1, // บัณฑิตวิทยาลัย ส่วนกลาง
                'faculty' => 'สังคมศาสตร์',
                'major' => 'การจัดการเชิงพุทธ',
                'program_name' => 'พุทธศาสตรมหาบัณฑิต สาขาวิชาการจัดการเชิงพุทธ',
                'submission_status' => 'APPROVED',
                'approved_at' => '2026-08-20 14:00:00',
                'submitted_at' => '2026-08-15 10:00:00',
            ],
            [
                'id' => 2,
                'student_id' => '6401101002',
                'student_code' => '6401101002',
                'citizen_id' => '1101400654322',
                'prefix' => 'พระครูปลัด',
                'first_name' => 'วินัย',
                'last_name' => 'วรญาโณ',
                'full_name' => 'พระครูปลัดวินัย วรญาโณ',
                'degree_level' => 'DOCTORAL',
                'target_days' => 45,
                'accumulated_days' => 45,
                'org_unit_id' => 1,
                'faculty' => 'พุทธศาสตร์',
                'major' => 'พระพุทธศาสนา',
                'program_name' => 'พุทธศาสตรดุษฎีบัณฑิต สาขาวิชาพระพุทธศาสนา',
                'submission_status' => 'SUBMITTED',
                'approved_at' => null,
                'submitted_at' => '2026-09-20 11:30:00',
            ],
            [
                'id' => 3,
                'student_id' => '6601102003',
                'student_code' => '6601102003',
                'citizen_id' => '3100600765433',
                'prefix' => 'ดร.',
                'first_name' => 'จิรภัทร',
                'last_name' => 'สิทธิเวช',
                'full_name' => 'ดร.จิรภัทร สิทธิเวช',
                'degree_level' => 'MASTER',
                'target_days' => 30,
                'accumulated_days' => 20,
                'org_unit_id' => 10, // เชียงใหม่
                'faculty' => 'ครุศาสตร์',
                'major' => 'การบริหารการศึกษา',
                'program_name' => 'พุทธศาสตรมหาบัณฑิต สาขาวิชาการบริหารการศึกษา',
                'submission_status' => 'ACCUMULATING',
                'approved_at' => null,
                'submitted_at' => null,
            ],
            [
                'id' => 4,
                'student_id' => '6501101004',
                'student_code' => '6501101004',
                'citizen_id' => '1309900876544',
                'prefix' => 'พระมหา',
                'first_name' => 'เฉลิมชัย',
                'last_name' => 'ชวนปญฺโญ',
                'full_name' => 'พระมหาเฉลิมชัย ชวนปญฺโญ',
                'degree_level' => 'DOCTORAL',
                'target_days' => 45,
                'accumulated_days' => 45,
                'org_unit_id' => 11, // ขอนแก่น
                'faculty' => 'พุทธศาสตร์',
                'major' => 'ปรัชญา',
                'program_name' => 'พุทธศาสตรดุษฎีบัณฑิต สาขาวิชาปรัชญา',
                'submission_status' => 'APPROVED',
                'approved_at' => '2026-09-10 15:30:00',
                'submitted_at' => '2026-09-01 09:00:00',
            ],
        ];

        foreach ($gradStudents as $gs) {
            GradStudent::updateOrCreate(['id' => $gs['id']], $gs);
        }

        // รายการบันทึกสะสมวันย่อย (GradCreditEntry) - อย่างน้อย 5 รายการ
        $creditEntries = [
            [
                'student_id' => '6501102001',
                'temple_name' => 'ศูนย์พัฒนาศาสนศึกษาแคมป์สน มจร',
                'venue_name' => 'ศูนย์พัฒนาศาสนศึกษาแคมป์สน มจร อ.เขาค้อ',
                'master_name' => 'พระธรรมวชิรมุนี วิ. (บุญชิต ญาณสํวโร)',
                'province' => 'เพชรบูรณ์',
                'start_date' => '2025-01-10',
                'end_date' => '2025-01-20',
                'days_earned' => 10,
                'days_count' => 10,
                'evidence_file' => '/storage/certificates/cert_6501102001_1.pdf',
            ],
            [
                'student_id' => '6501102001',
                'temple_name' => 'วัดมหาธาตุยุวราชรังสฤษฎิ์ ราชวรมหาวิหาร',
                'venue_name' => 'คณะ 5 ศูนย์วิปัสสนากรรมฐาน วัดมหาธาตุฯ',
                'master_name' => 'พระสุธีรัตนบัณฑิต',
                'province' => 'กรุงเทพมหานคร',
                'start_date' => '2025-05-01',
                'end_date' => '2025-05-11',
                'days_earned' => 10,
                'days_count' => 10,
                'evidence_file' => '/storage/certificates/cert_6501102001_2.pdf',
            ],
            [
                'student_id' => '6501102001',
                'temple_name' => 'ศูนย์วิปัสสนาธุระนานาชาติ มจร วังน้อย',
                'venue_name' => 'อาคารเฉลิมพระเกียรติ มจร วังน้อย',
                'master_name' => 'พระมหาบุญเลิศ อินฺทปญฺโญ',
                'province' => 'พระนครศรีอยุธยา',
                'start_date' => '2025-11-15',
                'end_date' => '2025-11-25',
                'days_earned' => 10,
                'days_count' => 10,
                'evidence_file' => '/storage/certificates/cert_6501102001_3.pdf',
            ],
            [
                'student_id' => '6401101002',
                'temple_name' => 'วัดพิชโสภาราม อ.เขมราฐ',
                'venue_name' => 'ศูนย์วิปัสสนากรรมฐาน วัดพิชโสภาราม',
                'master_name' => 'พระภาวนาวิโรธคุณ วิ.',
                'province' => 'อุบลราชธานี',
                'start_date' => '2024-12-01',
                'end_date' => '2024-12-21',
                'days_earned' => 20,
                'days_count' => 20,
                'evidence_file' => '/storage/certificates/cert_6401101002_1.pdf',
            ],
            [
                'student_id' => '6401101002',
                'temple_name' => 'ศูนย์พัฒนาศาสนศึกษาแคมป์สน มจร',
                'venue_name' => 'มหาจุฬาอาศรม แคมป์สน',
                'master_name' => 'พระธรรมวชิรมุนี วิ.',
                'province' => 'เพชรบูรณ์',
                'start_date' => '2025-12-05',
                'end_date' => '2025-12-30',
                'days_earned' => 25,
                'days_count' => 25,
                'evidence_file' => '/storage/certificates/cert_6401101002_2.pdf',
            ],
            [
                'student_id' => '6601102003',
                'temple_name' => 'ศูนย์วิปัสสนากรรมฐานล้านนา มจร วข.เชียงใหม่',
                'venue_name' => 'มหาจุฬาอาศรม สันป่าตอง',
                'master_name' => 'พระเทพสิงหวราจารย์',
                'province' => 'เชียงใหม่',
                'start_date' => '2026-02-10',
                'end_date' => '2026-03-02',
                'days_earned' => 20,
                'days_count' => 20,
                'evidence_file' => '/storage/certificates/cert_6601102003_1.pdf',
            ],
        ];

        foreach ($creditEntries as $entry) {
            GradCreditEntry::create($entry);
        }

        // 6. โครงการภาคประชาชน (PublicEvent) - อย่างน้อย 3 โครงการ
        $publicEvents = [
            [
                'id' => 1,
                'org_unit_id' => 1, // มจร วังน้อย
                'title' => 'โครงการอบรมวิปัสสนากรรมฐานเพื่อพัฒนาคุณภาพชีวิต ประชาชนทั่วไป รุ่นที่ 24',
                'title_en' => 'Vipassana Meditation for Life Quality Improvement (General Public Batch 24)',
                'start_date' => '2026-10-23',
                'end_date' => '2026-10-25',
                'max_quota' => 60,
                'confirmed_count' => 3,
                'waiting_count' => 1,
                'location_name' => 'อาคาร 74 ปี พระมงคลสิทธิคุณ มจร วังน้อย พระนครศรีอยุธยา',
                'location_name_en' => '74th Anniversary Building, MCU Wang Noi, Phra Nakhon Si Ayutthaya',
                'status' => 'OPEN',
            ],
            [
                'id' => 2,
                'org_unit_id' => 10, // วิทยาเขตเชียงใหม่
                'title' => 'คอร์สเจริญสติภาวนาล้านนา เพื่อสันติสุขในชีวิตการทำงาน (3 วัน 2 คืน)',
                'title_en' => 'Lanna Mindfulness & Meditation Course for Workplace Peace (3 Days 2 Nights)',
                'start_date' => '2026-11-20',
                'end_date' => '2026-11-22',
                'max_quota' => 50,
                'confirmed_count' => 2,
                'waiting_count' => 0,
                'location_name' => 'ศูนย์ปฏิบัติธรรมมหาจุฬาอาศรม มจร วิทยาเขตเชียงใหม่',
                'location_name_en' => 'Mahachula Ashram Meditation Center, MCU Chiang Mai Campus',
                'status' => 'OPEN',
            ],
            [
                'id' => 3,
                'org_unit_id' => 11, // วิทยาเขตขอนแก่น
                'title' => 'โครงการปฏิบัติธรรมบำเพ็ญบุญสำหรับพุทธศาสนิกชนภาคตะวันออกเฉียงเหนือ',
                'title_en' => 'Northeastern Regional Merit-Making Meditation Retreat for Buddhists',
                'start_date' => '2026-12-05',
                'end_date' => '2026-12-07',
                'max_quota' => 100,
                'confirmed_count' => 2,
                'waiting_count' => 0,
                'location_name' => 'ศาลาสมเด็จพระพุฒาจารย์ มจร วิทยาเขตขอนแก่น',
                'location_name_en' => 'Somdet Phra Phutthachan Hall, MCU Khon Kaen Campus',
                'status' => 'OPEN',
            ],
        ];

        foreach ($publicEvents as $pe) {
            PublicEvent::updateOrCreate(['id' => $pe['id']], $pe);
        }

        // รายชื่อผู้ลงทะเบียนภาคประชาชน (PublicRegistration) - อย่างน้อย 4 รายการ
        $publicRegistrations = [
            [
                'id' => 1,
                'registration_no' => 'PUB-20261023-0001',
                'event_id' => 1,
                'citizen_id' => '1100200876541',
                'prefix' => 'นาง',
                'full_name' => 'นางพิมพ์พร ศิริวัฒนา',
                'gender' => 'FEMALE',
                'age' => 45,
                'phone' => '081-888-9999',
                'province' => 'กรุงเทพมหานคร',
                'emergency_contact' => 'นายเกียรติศักดิ์ ศิริวัฒนา (สามี)',
                'emergency_phone' => '081-888-9990',
                'congenital_disease' => 'ไม่มี',
                'dietary_restriction' => 'มังสวิรัติ',
                'queue_no' => 1,
                'status' => 'CONFIRMED',
            ],
            [
                'id' => 2,
                'registration_no' => 'PUB-20261023-0002',
                'event_id' => 1,
                'citizen_id' => '1100400765432',
                'prefix' => 'นาย',
                'full_name' => 'นายธนาธิป เจริญทรัพย์',
                'gender' => 'MALE',
                'age' => 38,
                'phone' => '086-777-6655',
                'province' => 'พระนครศรีอยุธยา',
                'emergency_contact' => 'นางอุษา เจริญทรัพย์ (มารดา)',
                'emergency_phone' => '086-777-6650',
                'congenital_disease' => 'ความดันโลหิตสูง (มียาประจำตัว)',
                'dietary_restriction' => 'อาหารทั่วไป',
                'queue_no' => 2,
                'status' => 'CONFIRMED',
            ],
            [
                'id' => 3,
                'registration_no' => 'PUB-20261023-0003',
                'event_id' => 1,
                'citizen_id' => '1101500654323',
                'prefix' => 'นางสาว',
                'full_name' => 'นางสาวนภัสวรรณ รักษ์ธรรม',
                'gender' => 'FEMALE',
                'age' => 29,
                'phone' => '092-444-1122',
                'province' => 'ปทุมธานี',
                'emergency_contact' => 'นายวิเชียร รักษ์ธรรม (บิดา)',
                'emergency_phone' => '092-444-1120',
                'congenital_disease' => 'ไม่มี',
                'dietary_restriction' => 'อาหารเจ',
                'queue_no' => 3,
                'status' => 'WAITING_LIST',
            ],
            [
                'id' => 4,
                'registration_no' => 'PUB-20261120-0001',
                'event_id' => 2,
                'citizen_id' => '1509900543214',
                'prefix' => 'นาย',
                'full_name' => 'นายสิรภพ เชียงคำ',
                'gender' => 'MALE',
                'age' => 52,
                'phone' => '089-665-4321',
                'province' => 'เชียงใหม่',
                'emergency_contact' => 'นางพิมพา เชียงคำ (ภรรยา)',
                'emergency_phone' => '089-665-4320',
                'congenital_disease' => 'ไม่มี',
                'dietary_restriction' => 'อาหารทั่วไป',
                'queue_no' => 1,
                'status' => 'CONFIRMED',
            ],
        ];

        foreach ($publicRegistrations as $pr) {
            PublicRegistration::updateOrCreate(['id' => $pr['id']], $pr);
        }

        // 7. ข่าวสารประชาสัมพันธ์ (NewsArticle) - อย่างน้อย 4 ข่าวสาร
        $newsArticles = [
            [
                'id' => 1,
                'org_unit_id' => 1, // ส่วนกลาง
                'title' => 'ประกาศกำหนดการโครงการปฏิบัติวิปัสสนากรรมฐาน ประจำปีการศึกษา 2569 นิสิต มจร ทั่วประเทศ',
                'title_en' => 'Official Schedule Announcement: Academic Year 2026 Vipassana Meditation Program for Nationwide MCU Students',
                'content' => 'สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย ขอประกาศกำหนดการเปิดรับลงทะเบียนโครงการปฏิบัติวิปัสสนากรรมฐาน ประจำปีการศึกษา 2569 สำหรับนิสิตระดับปริญญาตรี บัณฑิตศึกษา และประชาชนทั่วไป ขอให้นิสิตตรวจสอบรายชื่อและรอบผลัดของตนเองผ่านระบบ MCUVMS ภายในระยะเวลาที่กำหนด',
                'content_en' => 'Vipassana Meditation Institute, Mahachulalongkornrajavidyalaya University hereby announces the registration schedule for the Academic Year 2026 Vipassana Meditation Program for undergraduate students, graduate students, and the general public. All students are advised to verify their eligibility and schedule through the MCUVMS portal within the designated timeframe.',
                'cover_image' => '/images/news/meditation_hall.jpg',
                'category' => 'ANNOUNCEMENT',
                'is_pinned' => 1,
                'status' => 'PUBLISHED',
                'views' => 1420,
                'published_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
            ],
            [
                'id' => 2,
                'org_unit_id' => 1,
                'title' => 'แนวปฏิบัติการบันทึกสะสมวันปฏิบัติธรรม ระดับปริญญาโท 30 วัน และ ปริญญาเอก 45 วัน',
                'title_en' => 'Retreat Credit Submission Guidelines: Master (30 Days) and Doctoral (45 Days) Degree Requirements',
                'content' => 'บัณฑิตวิทยาลัย มจร แจ้งเตือนนิสิตระดับปริญญาโทและปริญญาเอกทุกคณะและสาขาวิชา ให้ดำเนินการตรวจสอบหลักฐานการเข้าร่วมปฏิบัติธรรมจากสำนักวิปัสสนากรรมฐานที่ได้รับการรับรอง และยื่นคำขออนุมัติผ่านระบบสะสมวันดิจิทัล MCUVMS ก่อนการยื่นขอสอบดุษฎีนิพนธ์/สารนิพนธ์',
                'content_en' => 'The Graduate School of MCU reminds all Master and Doctoral degree students across faculties to verify their certified meditation records and submit their completion requests via the MCUVMS digital tracking system prior to thesis or dissertation defenses.',
                'cover_image' => '/images/news/scripture_study.jpg',
                'category' => 'ACADEMIC',
                'is_pinned' => 1,
                'status' => 'PUBLISHED',
                'views' => 985,
                'published_at' => now()->subDays(5),
                'created_at' => now()->subDays(5),
            ],
            [
                'id' => 3,
                'org_unit_id' => 10, // วิทยาเขตเชียงใหม่
                'title' => 'วิทยาเขตเชียงใหม่ เปิดรับสมัครผู้เข้าร่วมอบรมจิตตภาวนาล้านนา รุ่นที่ 15 รับจำนวนจำกัด 80 ท่าน',
                'title_en' => 'Chiang Mai Campus Opens Registration for 15th Lanna Mental Cultivation Training (Limited to 80 Participants)',
                'content' => 'มจร วิทยาเขตเชียงใหม่ ขอเชิญชวนนิสิตและสาธุชนผู้สนใจ เข้าร่วมโครงการปฏิบัติวิปัสสนากรรมฐานประจำปี ณ ศูนย์ปฏิบัติธรรมมหาจุฬาอาศรม อ.สันป่าตอง จ.เชียงใหม่ ท่ามกลางธรรมชาติที่สงบ สัปปายะ เปิดรับสมัครผ่านระบบออนไลน์แล้ววันนี้',
                'content_en' => 'MCU Chiang Mai Campus warmly invites students and devotees to attend the Annual Vipassana Meditation Program at Mahachula Ashram Meditation Center, San Pa Tong District, Chiang Mai. Experience serene forest sanctuary conditions; register online today.',
                'cover_image' => '/images/news/lanna_retreat.jpg',
                'category' => 'MEDITATION',
                'is_pinned' => 0,
                'status' => 'PUBLISHED',
                'views' => 450,
                'published_at' => now()->subDays(7),
                'created_at' => now()->subDays(7),
            ],
            [
                'id' => 4,
                'org_unit_id' => 11, // ขอนแก่น
                'title' => 'มจร วิทยาเขตขอนแก่น จัดกิจกรรมสัปดาห์ส่งเสริมการปฏิบัติวิปัสสนาธุระและบริการวิชาการแก่ชุมชน',
                'title_en' => 'MCU Khon Kaen Campus Organizes Vipassana Promotion Week and Community Academic Services',
                'content' => 'วิทยาเขตขอนแก่น ร่วมกับคณะสงฆ์จังหวัดขอนแก่น จัดโครงการส่งเสริมสันติสุขชุมชนผ่านการปฏิบัติวิปัสสนากรรมฐานตามแนวสติปัฏฐาน 4 มีประชาชนและนิสิตร่วมกิจกรรมกว่า 300 รูป/คน พร้อมถ่ายทอดสดผ่านระบบเครือข่ายออนไลน์',
                'content_en' => 'Khon Kaen Campus in cooperation with the Khon Kaen Sangha Council organized a community peace promotion retreat rooted in the Four Foundations of Mindfulness (Satipatthana), gathering over 300 monastics and laypersons with live stream coverage.',
                'cover_image' => '/images/news/isan_community.jpg',
                'category' => 'GENERAL',
                'is_pinned' => 0,
                'status' => 'PUBLISHED',
                'views' => 310,
                'published_at' => now()->subDays(10),
                'created_at' => now()->subDays(10),
            ],
        ];

        foreach ($newsArticles as $na) {
            NewsArticle::updateOrCreate(['id' => $na['id']], $na);
        }

        // 7.1 ประกาศและคำสั่ง (Announcement)
        $announcements = [
            [
                'id' => 1,
                'org_unit_id' => 1,
                'announcement_no' => 'วป. ๑๒/๒๕๖๙',
                'title' => 'ประกาศรายชื่อผู้มีสิทธิ์เข้าร่วมโครงการปฏิบัติวิปัสสนากรรมฐาน รุ่นที่ ๑ ประจำปี ๒๕๖๙',
                'title_en' => 'Eligible Participants Announcement: Vipassana Meditation Program Batch 1/2026',
                'content' => 'สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย ขอประกาศรายชื่อผู้มีสิทธิ์เข้าร่วมโครงการปฏิบัติวิปัสสนากรรมฐาน รุ่นที่ ๑ ประจำปี ๒๕๖๙ ขอให้ผู้ผ่านการคัดเลือกตรวจสอบรายชื่อและเตรียมความพร้อมตามเอกสารแนบ',
                'content_en' => 'Vipassana Meditation Institute, MCU announces the list of qualified participants for Batch 1/2026. Please check attached document for participant list and preparation instructions.',
                'category' => 'PARTICIPANTS',
                'attachment_path' => '/storage/announcements/announcement_participants_2569.pdf',
                'attachment_name' => 'ประกาศรายชื่อผู้มีสิทธิ์_รุ่น1_2569.pdf',
                'attachment_size' => '860 B',
                'attachment_mime' => 'application/pdf',
                'attachment_text' => 'พระมหาธนภัทร สุขใจ นายสมคิด จิตมั่นคง นางสาวกานดา วิมุตติ MCU Vipassana Meditation Institute Announcement: Eligible Participants for Meditation Program Batch 1/2569 1. Phra Maha Thanapat Sukjai 2. Mr. Somkid Jitman 3. Ms. Kanda Wimutti',
                'is_pinned' => 1,
                'status' => 'PUBLISHED',
                'views' => 248,
                'published_at' => now()->subDay(),
                'created_at' => now()->subDay(),
            ],
            [
                'id' => 2,
                'org_unit_id' => 1,
                'announcement_no' => 'วป. ๑๐/๒๕๖๙',
                'title' => 'กำหนดการและแนวปฏิบัติการเตรียมความพร้อมสำหรับผู้เข้าร่วมปฏิบัติวิปัสสนากรรมฐาน',
                'title_en' => 'Schedule and Code of Conduct Guidelines for Meditation Retreatants',
                'content' => 'ข้อปฏิบัติการเข้าคอร์ส ระเบียบการแต่งกายชุดขาว การจัดเตรียมของใช้ส่วนตัว และตารางเวลาปฏิบัติธรรมประจำวัน ณ ศูนย์วิปัสสนาธุระ',
                'content_en' => 'Detailed timetable, dress code, personal item preparations, and daily routine schedules at the Vipassana Meditation Center.',
                'category' => 'SCHEDULE',
                'attachment_path' => '/storage/announcements/announcement_participants_2569.pdf',
                'attachment_name' => 'กำหนดการและแนวปฏิบัติ_2569.pdf',
                'attachment_size' => '1.2 MB',
                'attachment_mime' => 'application/pdf',
                'attachment_text' => 'กำหนดการ ข้อปฏิบัติ การแต่งกายชุดขาว ปิดเครื่องมือสื่อสาร อาหารมังสวิรัติ ตารางเวลาทำวัตรเช้าเย็น ปฏิบัติเดินจงกรม นั่งสมาธิ',
                'is_pinned' => 1,
                'status' => 'PUBLISHED',
                'views' => 185,
                'published_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
            ],
            [
                'id' => 3,
                'org_unit_id' => 10,
                'announcement_no' => 'วป.ชร. ๐๕/๒๕๖๙',
                'title' => 'ประกาศรายชื่อผู้ได้รับการคัดเลือกเข้าร่วมอบรมจิตตภาวนาล้านนา วิทยาเขตเชียงใหม่',
                'title_en' => 'Selection Results: 15th Lanna Mental Cultivation Participants (Chiang Mai Campus)',
                'content' => 'มจร วิทยาเขตเชียงใหม่ ประกาศรายชื่อผู้ผ่านการคัดเลือกเข้าร่วมอบรมจิตตภาวนาล้านนา จำนวน ๘๐ ท่าน พร้อมรายละเอียดการรายงานตัว',
                'content_en' => 'MCU Chiang Mai Campus releases the final list of 80 accepted participants for the Lanna Meditation Retreat with reporting details.',
                'category' => 'PARTICIPANTS',
                'attachment_path' => '/storage/announcements/announcement_participants_2569.pdf',
                'attachment_name' => 'รายชื่อผู้ผ่านการคัดเลือก_จิตตภาวนาล้านนา.pdf',
                'attachment_size' => '650 KB',
                'attachment_mime' => 'application/pdf',
                'attachment_text' => 'รายชื่อผู้ได้รับการคัดเลือก พระครูภาวนาวรธรรม พระมหาอุดม สมาธิโก วิปัสสนาจารย์ มจร วิทยาเขตเชียงใหม่ 80 รูป/คน',
                'is_pinned' => 0,
                'status' => 'PUBLISHED',
                'views' => 94,
                'published_at' => now()->subDays(6),
                'created_at' => now()->subDays(6),
            ],
        ];

        foreach ($announcements as $anc) {
            Announcement::updateOrCreate(['id' => $anc['id']], $anc);
        }

        // 8. ปฏิทินปฏิบัติธรรม 52 หน่วยงาน (MeditationCalendar) - อย่างน้อย 3 รายการ
        $meditationCalendars = [
            [
                'id' => 1,
                'org_unit_id' => 1, // พระนครศรีอยุธยา ส่วนกลาง
                'academic_year' => 2569,
                'title' => 'ปฏิทินปฏิบัติวิปัสสนากรรมฐาน นิสิต ป.ตรี ชั้นปีที่ 1-4 ประจำปีการศึกษา 2569',
                'target_group' => 'UG',
                'start_date' => '2026-12-15',
                'end_date' => '2026-12-25',
                'total_days' => 10,
                'location_name' => 'ศูนย์วิปัสสนาธุระนานาชาติ มจร วังน้อย พระนครศรีอยุธยา',
                'max_seats' => 500,
                'status' => 'OPEN',
            ],
            [
                'id' => 2,
                'org_unit_id' => 1,
                'academic_year' => 2569,
                'title' => 'คอร์สปฏิบัติธรรมเจริญสติแบบเข้มข้น ระดับบัณฑิตศึกษา (ป.โท/เอก) รอบสิ้นปี',
                'target_group' => 'GRAD',
                'start_date' => '2026-11-15',
                'end_date' => '2026-11-25',
                'total_days' => 10,
                'location_name' => 'อาคารวิปัสสนาธุระ มจร วังน้อย',
                'max_seats' => 150,
                'status' => 'OPEN',
            ],
            [
                'id' => 3,
                'org_unit_id' => 10, // เชียงใหม่
                'academic_year' => 2569,
                'title' => 'โครงการวิปัสสนากรรมฐานเฉลิมพระเกียรติ สำหรับนิสิตและพุทธศาสนิกชน',
                'target_group' => 'ALL',
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-11',
                'total_days' => 10,
                'location_name' => 'ศูนย์ปฏิบัติธรรมมหาจุฬาอาศรม วข.เชียงใหม่',
                'max_seats' => 200,
                'status' => 'OPEN',
            ],
        ];

        foreach ($meditationCalendars as $mc) {
            MeditationCalendar::updateOrCreate(['id' => $mc['id']], $mc);
        }

        // 9. ตั้งค่าข้อมูลติดต่อระบบ (SiteSetting)
        $settings = [
            ['setting_key' => 'site_name', 'setting_value' => 'ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน (MCUVMS)', 'setting_group' => 'general', 'label' => 'ชื่อระบบ'],
            ['setting_key' => 'contact_address', 'setting_value' => 'สถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย 79 หมู่ 1 ต.ลำไทร อ.วังน้อย จ.พระนครศรีอยุธยา 13170', 'setting_group' => 'contact', 'label' => 'ที่อยู่ติดต่อ'],
            ['setting_key' => 'contact_phone', 'setting_value' => '035-248-000 ต่อ 8100, 8101', 'setting_group' => 'contact', 'label' => 'เบอร์โทรศัพท์ติดต่อ'],
            ['setting_key' => 'contact_email', 'setting_value' => 'vipassana@mcu.ac.th', 'setting_group' => 'contact', 'label' => 'อีเมลติดต่อ'],
            ['setting_key' => 'contact_hours', 'setting_value' => 'วันจันทร์ - วันศุกร์ เวลา 08:30 - 16:30 น. (เว้นวันหยุดราชการและวันธรรมสวนะ)', 'setting_group' => 'contact', 'label' => 'เวลาทำการ'],
            ['setting_key' => 'contact_facebook', 'setting_value' => 'https://facebook.com/mcu.vipassana', 'setting_group' => 'contact', 'label' => 'Facebook Fanpage'],
        ];

        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(['setting_key' => $s['setting_key']], $s);
        }

        // 10. กล่องข้อความติดต่อสอบถาม (ContactInquiry) - อย่างน้อย 3 รายการ
        $inquiries = [
            [
                'id' => 1,
                'ticket_no' => 'INQ-20260920-0001',
                'sender_name' => 'พระมหาสุริยันต์ วรปญฺโญ',
                'phone' => '081-999-1234',
                'email' => 'suriyan@mcu.ac.th',
                'category' => 'ป.ตรี (10 วัน/ปี)',
                'subject' => 'สอบถามการเทียบโอนวันปฏิบัติธรรมกรณีไปปฏิบัติธรรมนอกส่วนงาน',
                'message' => 'เจริญพร ขอสอบถามเรื่องการยื่นหนังสือรับรองการปฏิบัติธรรม 10 วัน จากสำนักปฏิบัติธรรมประจำจังหวัด สามารถนำมาเทียบเป็นผลัดประจำปีของวิทยาเขตได้หรือไม่ ขอคำแนะนำด้วย',
                'status' => 'RESOLVED',
                'admin_notes' => 'เจ้าหน้าที่ได้ตอบกลับทางอีเมลและประสานงานฝ่ายทะเบียนเรียบร้อยแล้ว',
                'resolved_at' => now()->subDays(5),
            ],
            [
                'id' => 2,
                'ticket_no' => 'INQ-20260925-0002',
                'sender_name' => 'ดร.สมบัติ นิมิตรกุล',
                'phone' => '089-123-4567',
                'email' => 'sombat.nim@gmail.com',
                'category' => 'บัณฑิตศึกษา (30/45 วัน)',
                'subject' => 'ขอตรวจสอบสถานะการอนุมัติแฟ้มสะสมวัน ป.เอก (45 วัน)',
                'message' => 'กระผมได้ทำการส่งคำขออนุมัติผลสะสมวันครบ 45 วันเรียบร้อยแล้ว อยากทราบว่าคณะกรรมการจะพิจารณาอนุมัติรอบถัดไปในสัปดาห์ใดเพื่อใช้ประกอบการยื่นขอสอบวิทยานิพนธ์ครับ',
                'status' => 'IN_PROGRESS',
                'admin_notes' => 'อยู่ระหว่างรอการประชุมคณะกรรมการรอบวันที่ 30 กันยายน',
                'resolved_at' => null,
            ],
            [
                'id' => 3,
                'ticket_no' => 'INQ-20260928-0003',
                'sender_name' => 'คุณรัตนาพร มณีรัตน์',
                'phone' => '084-777-8899',
                'email' => 'rattanaporn.m@yahoo.com',
                'category' => 'ภาคประชาชน',
                'subject' => 'สอบถามเรื่องที่พักและสิ่งอำนวยความสะดวกสำหรับผู้สูงอายุ',
                'message' => 'ต้องการพาคุณแม่วัย 68 ปี ไปร่วมปฏิบัติธรรม 3 วัน อยากสอบถามว่าทางศูนย์มีห้องพักชั้นล่างหรือมีสิ่งอำนวยความสะดวกสำหรับผู้สูงอายุหรือไม่คะ',
                'status' => 'NEW',
                'admin_notes' => null,
                'resolved_at' => null,
            ],
        ];

        foreach ($inquiries as $inq) {
            ContactInquiry::updateOrCreate(['id' => $inq['id']], $inq);
        }

        // 11. ตั้งค่าบัญชีธนาคารสำหรับรับบริจาค (SiteSetting group: donation)
        $donationSettings = [
            [
                'setting_key' => 'donation_bank_name',
                'setting_value' => 'ธนาคารทหารไทยธนชาต (ttb)',
                'setting_group' => 'donation',
                'label' => 'ชื่อธนาคาร',
                'field_type' => 'text',
            ],
            [
                'setting_key' => 'donation_account_name',
                'setting_value' => 'เพื่อพัฒนาสถาบันวิปัสสนาธุระ มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย',
                'setting_group' => 'donation',
                'label' => 'ชื่อบัญชี',
                'field_type' => 'text',
            ],
            [
                'setting_key' => 'donation_account_number',
                'setting_value' => '231-2-93605-3',
                'setting_group' => 'donation',
                'label' => 'เลขที่บัญชี',
                'field_type' => 'text',
            ],
            [
                'setting_key' => 'donation_promptpay',
                'setting_value' => '0994000159451',
                'setting_group' => 'donation',
                'label' => 'พร้อมเพย์ (เลขประจำตัวผู้เสียภาษี มจร)',
                'field_type' => 'text',
            ],
            [
                'setting_key' => 'donation_qr_image',
                'setting_value' => 'images/qr-codepayment.jpg',
                'setting_group' => 'donation',
                'label' => 'รูปภาพ QR Code รับบริจาค',
                'field_type' => 'text',
            ],
            [
                'setting_key' => 'donation_info_notes',
                'setting_value' => 'การบริจาคเพื่อสนับสนุนการศึกษาและปฏิบัติวิปัสสนากรรมฐาน สามารถนำไปลดหย่อนภาษีได้ตามที่กฎหมายกำหนด โดยทางมหาวิทยาลัยจะออกใบเสร็จรับเงิน/ใบอนุโมทนาบัตร และเชื่อมโยงข้อมูลระบบ e-Donation ของกรมสรรพากร',
                'setting_group' => 'donation',
                'label' => 'คำชี้แจงการบริจาคและลดหย่อนภาษี',
                'field_type' => 'textarea',
            ],
        ];

        foreach ($donationSettings as $ds) {
            SiteSetting::updateOrCreate(['setting_key' => $ds['setting_key']], $ds);
        }

        // 12. ข้อมูลตัวอย่างการบริจาค (Donations) - 4 รายการครอบคลุมสถานะต่าง ๆ
        $sampleDonations = [
            [
                'id' => 1,
                'donation_no' => 'DON-20260920-0001',
                'donor_name' => 'นายสมเกียรติ สิทธิปัญญากุล',
                'tax_id' => '1100500123456',
                'is_tax_deductible' => 1,
                'amount' => 5000.00,
                'bank_account' => 'ธนาคารกรุงไทย (123-4-56789-0)',
                'transfer_date' => '2026-09-20',
                'transfer_time' => '10:30',
                'slip_path' => null,
                'phone' => '081-456-7890',
                'email' => 'somkiat.sit@gmail.com',
                'address' => '99/12 หมู่บ้านศุภาลัย ถ.พหลโยธิน แขวงลาดยาว เขตจตุจักร กรุงเทพฯ 10900',
                'purpose' => 'สนับสนุนภัตตาหารและน้ำปานะพระวิปัสสนาจารย์และนิสิต',
                'note' => 'ขออุทิศบุญกุศลนี้ให้บรรพบุรุษและเจ้ากรรมนายเวร',
                'status' => 'VERIFIED',
                'admin_notes' => 'ตรวจสอบยอดเงินเข้าบัญชีเรียบร้อย ออกใบอนุโมทนาบัตรเลขที่ MCU-REC-2569/089',
                'verified_by' => 1,
                'verified_at' => now()->subDays(10),
            ],
            [
                'id' => 2,
                'donation_no' => 'DON-20260925-0002',
                'donor_name' => 'นางสาวกุลธิดา เจริญมงคล',
                'tax_id' => '3101700987654',
                'is_tax_deductible' => 1,
                'amount' => 10000.00,
                'bank_account' => 'ธนาคารกรุงไทย (123-4-56789-0)',
                'transfer_date' => '2026-09-25',
                'transfer_time' => '14:15',
                'slip_path' => null,
                'phone' => '089-765-4321',
                'email' => 'kunthida.c@hotmail.com',
                'address' => '45/8 ถ.สุเทพ ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200',
                'purpose' => 'กองทุนพัฒนาอาคารและสถานที่ปฏิบัติธรรม',
                'note' => 'ร่วมทำบุญสร้างบารมี',
                'status' => 'VERIFIED',
                'admin_notes' => 'ยอดเงินตรวจสอบเรียบร้อย ส่งใบเสร็จอิเล็กทรอนิกส์ทางอีเมลแล้ว',
                'verified_by' => 1,
                'verified_at' => now()->subDays(5),
            ],
            [
                'id' => 3,
                'donation_no' => 'DON-20260929-0003',
                'donor_name' => 'อาจารย์ประสิทธิ์ เมตตาธรรม',
                'tax_id' => '1509900334455',
                'is_tax_deductible' => 1,
                'amount' => 2500.00,
                'bank_account' => 'ธนาคารกรุงไทย (123-4-56789-0)',
                'transfer_date' => '2026-09-29',
                'transfer_time' => '09:05',
                'slip_path' => null,
                'phone' => '086-111-2233',
                'email' => 'prasit.met@mcu.ac.th',
                'address' => '79 หมู่ 1 ต.ลำไทร อ.วังน้อย จ.พระนครศรีอยุธยา 13170',
                'purpose' => 'ค่ายานพาหนะและกิจกรรมปฏิบัติธรรมนิสิต ป.ตรี',
                'note' => 'ขอร่วมเป็นเจ้าภาพอุปถัมภ์โครงการปฏิบัติธรรมนิสิต',
                'status' => 'PENDING',
                'admin_notes' => null,
                'verified_by' => null,
                'verified_at' => null,
            ],
            [
                'id' => 4,
                'donation_no' => 'DON-20260930-0004',
                'donor_name' => 'ผู้ไม่ประสงค์ออกนาม (คณะศรัทธาสาธุชน)',
                'tax_id' => null,
                'is_tax_deductible' => 0,
                'amount' => 1000.00,
                'bank_account' => 'ธนาคารกรุงไทย (123-4-56789-0)',
                'transfer_date' => '2026-09-30',
                'transfer_time' => '16:45',
                'slip_path' => null,
                'phone' => '082-333-4455',
                'email' => null,
                'address' => null,
                'purpose' => 'บริจาคทั่วไปบำรุงศูนย์ปฏิบัติธรรม',
                'note' => 'ขอร่วมอนุโมทนาบุญกับทุกท่าน',
                'status' => 'PENDING',
                'admin_notes' => null,
                'verified_by' => null,
                'verified_at' => null,
            ],
        ];

        foreach ($sampleDonations as $d) {
            Donation::updateOrCreate(['id' => $d['id']], $d);
        }
    }
}
