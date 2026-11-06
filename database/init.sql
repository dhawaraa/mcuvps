-- MCUVMS Database Schema (MariaDB / MySQL 8.0)
-- ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน มจร 52 ส่วนงาน

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. ตารางสังกัดส่วนจัดการศึกษา 45 แห่ง (Organization Units)
DROP TABLE IF EXISTS `organization_units`;
CREATE TABLE `organization_units` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `name_th` VARCHAR(255) NOT NULL,
  `name_en` VARCHAR(255) NULL,
  `type` ENUM('CENTRAL', 'CAMPUS', 'SANGHA_COLLEGE', 'ACADEMIC_UNIT') NOT NULL DEFAULT 'CAMPUS',
  `province_th` VARCHAR(100) NULL,
  `province_code` VARCHAR(10) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ตารางผู้ใช้งานและบทบาท (Users & Roles)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `org_unit_id` INT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL,
  `role` ENUM('SUPER_ADMIN', 'CENTRAL_OFFICER', 'CAMPUS_ADMIN', 'VIPASSANA_MASTER', 'GRAD_OFFICER', 'PUBLIC_STAFF') NOT NULL DEFAULT 'CAMPUS_ADMIN',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`org_unit_id`) REFERENCES `organization_units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. ตารางข่าวสารประชาสัมพันธ์ (News & Announcements)
DROP TABLE IF EXISTS `news_articles`;
CREATE TABLE `news_articles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `org_unit_id` INT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `cover_image` VARCHAR(500) NULL,
  `category` ENUM('ANNOUNCEMENT', 'MEDITATION', 'ACADEMIC', 'GENERAL') NOT NULL DEFAULT 'GENERAL',
  `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('DRAFT', 'PUBLISHED', 'ARCHIVED') NOT NULL DEFAULT 'PUBLISHED',
  `views` INT NOT NULL DEFAULT 0,
  `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`org_unit_id`) REFERENCES `organization_units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. ตารางปฏิทินปฏิบัติธรรม (Meditation Calendar)
DROP TABLE IF EXISTS `meditation_calendars`;
CREATE TABLE `meditation_calendars` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `org_unit_id` INT NOT NULL,
  `academic_year` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `target_group` ENUM('UG', 'GRAD', 'PUBLIC', 'ALL') NOT NULL DEFAULT 'ALL',
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `total_days` INT NOT NULL,
  `location_name` VARCHAR(255) NOT NULL,
  `max_seats` INT NOT NULL DEFAULT 0,
  `status` ENUM('OPEN', 'CLOSED', 'COMPLETED', 'CANCELLED') NOT NULL DEFAULT 'OPEN',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`org_unit_id`) REFERENCES `organization_units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. โมดูล 1: ปริญญาตรี (10 วัน x 4 ปี)
DROP TABLE IF EXISTS `ug_batches`;
CREATE TABLE `ug_batches` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `org_unit_id` INT NOT NULL,
  `academic_year` INT NOT NULL,
  `batch_no` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `location` VARCHAR(255) NOT NULL DEFAULT 'ศูนย์พัฒนาศาสนศึกษา / อาคารปฏิบัติธรรมประจำวิทยาเขต',
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `max_quota` INT NOT NULL DEFAULT 100,
  `status` ENUM('OPEN', 'CLOSED', 'IN_PROGRESS', 'COMPLETED') NOT NULL DEFAULT 'OPEN',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`org_unit_id`) REFERENCES `organization_units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ug_registrations`;
CREATE TABLE `ug_registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `batch_id` INT NOT NULL,
  `student_id` VARCHAR(20) NOT NULL,
  `citizen_id` VARCHAR(13) NOT NULL,
  `prefix` VARCHAR(20) NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `org_unit_id` INT NOT NULL,
  `faculty` VARCHAR(100) NULL,
  `major` VARCHAR(100) NULL,
  `class_year` TINYINT NOT NULL DEFAULT 1,
  `program_type` ENUM('NORMAL', 'SPECIAL') NOT NULL DEFAULT 'NORMAL',
  `phone` VARCHAR(30) NULL,
  `checkin_token` VARCHAR(64) NOT NULL UNIQUE,
  `checkin_status` TINYINT(1) NOT NULL DEFAULT 0,
  `final_result` ENUM('PENDING', 'PASSED', 'FAILED', 'ABSENT') NOT NULL DEFAULT 'PENDING',
  `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`batch_id`) REFERENCES `ug_batches` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`org_unit_id`) REFERENCES `organization_units` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_student_batch` (`batch_id`, `student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. โมดูล 2: บัณฑิตศึกษา (สะสม 30/45 วัน)
DROP TABLE IF EXISTS `grad_students`;
CREATE TABLE `grad_students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` VARCHAR(20) NOT NULL UNIQUE,
  `citizen_id` VARCHAR(13) NOT NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `degree_level` ENUM('MASTER', 'DOCTORAL') NOT NULL DEFAULT 'MASTER',
  `target_days` INT NOT NULL DEFAULT 30,
  `accumulated_days` INT NOT NULL DEFAULT 0,
  `org_unit_id` INT NOT NULL,
  `faculty` VARCHAR(100) NULL,
  `major` VARCHAR(100) NULL,
  `submission_status` ENUM('ACCUMULATING', 'READY', 'SUBMITTED', 'RETURNED', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'ACCUMULATING',
  `return_reason` TEXT NULL,
  `approved_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`org_unit_id`) REFERENCES `organization_units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `grad_credit_entries`;
CREATE TABLE `grad_credit_entries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` VARCHAR(20) NOT NULL,
  `temple_name` VARCHAR(255) NOT NULL,
  `master_name` VARCHAR(255) NOT NULL,
  `province` VARCHAR(100) NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `days_earned` INT NOT NULL,
  `evidence_file` VARCHAR(500) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES `grad_students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. โมดูล 3: ประชาชนทั่วไป (โควตา + Waiting List + SAR)
DROP TABLE IF EXISTS `public_events`;
CREATE TABLE `public_events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `org_unit_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `max_quota` INT NOT NULL DEFAULT 50,
  `confirmed_count` INT NOT NULL DEFAULT 0,
  `waiting_count` INT NOT NULL DEFAULT 0,
  `location_name` VARCHAR(255) NOT NULL,
  `status` ENUM('OPEN', 'CLOSED', 'COMPLETED') NOT NULL DEFAULT 'OPEN',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`org_unit_id`) REFERENCES `organization_units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `public_registrations`;
CREATE TABLE `public_registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `citizen_id` VARCHAR(13) NOT NULL,
  `prefix` VARCHAR(20) NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `gender` ENUM('MALE', 'FEMALE', 'OTHER') NOT NULL,
  `age` INT NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `province` VARCHAR(100) NULL,
  `emergency_contact` VARCHAR(255) NULL,
  `emergency_phone` VARCHAR(30) NULL,
  `congenital_disease` VARCHAR(255) NULL,
  `dietary_restriction` VARCHAR(100) NULL,
  `queue_no` INT NOT NULL,
  `status` ENUM('CONFIRMED', 'WAITING_LIST', 'CANCELLED', 'ATTENDED') NOT NULL DEFAULT 'CONFIRMED',
  `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `public_events` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_public_event` (`event_id`, `citizen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Seed Data: บัญชีผู้ดูแลระบบตั้งต้น (admin / password)
-- -------------------------------------------------------------
-- รหัสผ่าน hash สำหรับคำว่า 'password'
INSERT INTO `users` (`username`, `password_hash`, `full_name`, `email`, `role`, `org_unit_id`, `is_active`)
VALUES 
('admin', '$2y$12$XDe43h2HZhg2DwbxUyqjMe6mlHaZWN.hf.pmu1LPJ9LkpHFSt.B/S', 'ผู้ดูแลระบบสูงสุด (Super Admin)', 'admin@mcu.ac.th', 'SUPER_ADMIN', NULL, 1),
('central', '$2y$12$XDe43h2HZhg2DwbxUyqjMe6mlHaZWN.hf.pmu1LPJ9LkpHFSt.B/S', 'เจ้าหน้าที่สถาบันวิปัสสนาธุระ ส่วนกลาง (Central Officer)', 'central@mcu.ac.th', 'CENTRAL_OFFICER', 1, 1),
('officer_cmi', '$2y$12$XDe43h2HZhg2DwbxUyqjMe6mlHaZWN.hf.pmu1LPJ9LkpHFSt.B/S', 'เจ้าหน้าที่วิปัสสนา วิทยาเขตเชียงใหม่', 'cmi@mcu.ac.th', 'CAMPUS_ADMIN', 10, 1),
('officer_kkn', '$2y$12$XDe43h2HZhg2DwbxUyqjMe6mlHaZWN.hf.pmu1LPJ9LkpHFSt.B/S', 'เจ้าหน้าที่วิปัสสนา วิทยาเขตขอนแก่น', 'kkn@mcu.ac.th', 'CAMPUS_ADMIN', 11, 1);

-- -------------------------------------------------------------
-- Seed Data: 45 สังกัดส่วนจัดการศึกษาของ มจร ตามฐานข้อมูลทะเบียนกลาง
-- -------------------------------------------------------------
INSERT INTO `organization_units` (`id`, `code`, `name_th`, `type`, `province_th`, `province_code`, `is_active`) VALUES
(1, 'MCU-AYA', 'มจร พระนครศรีอยุธยา', 'CENTRAL', 'พระนครศรีอยุธยา', 'AYA', 1),
(8, 'CAMPUS-NKI', 'วิทยาเขตหนองคาย', 'CAMPUS', 'หนองคาย', 'NKI', 1),
(9, 'CAMPUS-NRT', 'วิทยาเขตนครศรีธรรมราช', 'CAMPUS', 'นครศรีธรรมราช', 'NRT', 1),
(10, 'CAMPUS-CMI', 'วิทยาเขตเชียงใหม่', 'CAMPUS', 'เชียงใหม่', 'CMI', 1),
(11, 'CAMPUS-KKN', 'วิทยาเขตขอนแก่น', 'CAMPUS', 'ขอนแก่น', 'KKN', 1),
(12, 'CAMPUS-NMA', 'วิทยาเขตนครราชสีมา', 'CAMPUS', 'นครราชสีมา', 'NMA', 1),
(13, 'CAMPUS-UBN', 'วิทยาเขตอุบลราชธานี', 'CAMPUS', 'อุบลราชธานี', 'UBN', 1),
(14, 'CAMPUS-PRE', 'วิทยาเขตแพร่', 'CAMPUS', 'แพร่', 'PRE', 1),
(15, 'CAMPUS-SRN', 'วิทยาเขตสุรินทร์', 'CAMPUS', 'สุรินทร์', 'SRN', 1),
(16, 'CAMPUS-PYO', 'วิทยาเขตพะเยา', 'CAMPUS', 'พะเยา', 'PYO', 1),
(17, 'CAMPUS-NPT-BP', 'วิทยาเขตบาฬีศึกษาพุทธโฆส', 'CAMPUS', 'นครปฐม', 'NPT', 1),
(18, 'CAMPUS-NSN', 'วิทยาเขตนครสวรรค์', 'CAMPUS', 'นครสวรรค์', 'NSN', 1),
(19, 'CAMPUS-NAN', 'วิทยาเขตนครน่าน เฉลิมพระเกียรติ', 'CAMPUS', 'น่าน', 'NAN', 1),
(21, 'CAMPUS-NPT-MVBR', 'มหาวชิราลงกรณบาลีเถรวาทราชวิทยาลัย', 'CAMPUS', 'นครปฐม', 'NPT', 1),
(22, 'SANGHA-LEI', 'วิทยาลัยสงฆ์เลย', 'SANGHA_COLLEGE', 'เลย', 'LEI', 1),
(23, 'SANGHA-NPM', 'วิทยาลัยสงฆ์นครพนม', 'SANGHA_COLLEGE', 'นครพนม', 'NPM', 1),
(24, 'SANGHA-LPN', 'วิทยาลัยสงฆ์ลำพูน', 'SANGHA_COLLEGE', 'ลำพูน', 'LPN', 1),
(25, 'SANGHA-PLK-PCN', 'วิทยาลัยสงฆ์พุทธชินราช', 'SANGHA_COLLEGE', 'พิษณุโลก', 'PLK', 1),
(26, 'SANGHA-PTN', 'วิทยาลัยสงฆ์ปัตตานี', 'SANGHA_COLLEGE', 'ปัตตานี', 'PTN', 1),
(27, 'SANGHA-BRM', 'วิทยาลัยสงฆ์บุรีรัมย์', 'SANGHA_COLLEGE', 'บุรีรัมย์', 'BRM', 1),
(28, 'SANGHA-LPG', 'วิทยาลัยสงฆ์นครลำปาง', 'SANGHA_COLLEGE', 'ลำปาง', 'LPG', 1),
(29, 'SANGHA-SSK', 'วิทยาลัยสงฆ์ศรีสะเกษ', 'SANGHA_COLLEGE', 'ศรีสะเกษ', 'SSK', 1),
(30, 'SANGHA-CCO-PTS', 'วิทยาลัยสงฆ์พุทธโสธร', 'SANGHA_COLLEGE', 'ฉะเชิงเทรา', 'CCO', 1),
(31, 'SANGHA-SPB', 'วิทยาลัยสงฆ์สุพรรณบุรีศรีสุวรรณภูมิ', 'SANGHA_COLLEGE', 'สุพรรณบุรี', 'SPB', 1),
(32, 'SANGHA-PCT', 'วิทยาลัยสงฆ์พิจิตร', 'SANGHA_COLLEGE', 'พิจิตร', 'PCT', 1),
(33, 'SANGHA-CPM', 'วิทยาลัยสงฆ์ชัยภูมิ', 'SANGHA_COLLEGE', 'ชัยภูมิ', 'CPM', 1),
(34, 'SANGHA-RET', 'วิทยาลัยสงฆ์ร้อยเอ็ด', 'SANGHA_COLLEGE', 'ร้อยเอ็ด', 'RET', 1),
(35, 'SANGHA-RBR', 'วิทยาลัยสงฆ์ราชบุรี', 'SANGHA_COLLEGE', 'ราชบุรี', 'RBR', 1),
(36, 'SANGHA-PNB-PKP', 'วิทยาลัยสงฆ์พ่อขุนผาเมือง เพชรบูรณ์', 'SANGHA_COLLEGE', 'เพชรบูรณ์', 'PNB', 1),
(37, 'SANGHA-NPT-PSTD', 'วิทยาลัยสงฆ์พุทธปัญญาศรีทวารวดี', 'SANGHA_COLLEGE', 'นครปฐม', 'NPT', 1),
(38, 'SANGHA-MKM', 'วิทยาลัยสงฆ์มหาสารคาม', 'SANGHA_COLLEGE', 'มหาสารคาม', 'MKM', 1),
(39, 'SANGHA-RYG', 'วิทยาลัยสงฆ์ระยอง', 'SANGHA_COLLEGE', 'ระยอง', 'RYG', 1),
(40, 'SANGHA-PBI', 'วิทยาลัยสงฆ์เพชรบุรี', 'SANGHA_COLLEGE', 'เพชรบุรี', 'PBI', 1),
(41, 'SANGHA-TAK', 'วิทยาลัยสงฆ์ตาก', 'SANGHA_COLLEGE', 'ตาก', 'TAK', 1),
(42, 'SANGHA-UTI', 'วิทยาลัยสงฆ์อุทัยธานี', 'SANGHA_COLLEGE', 'อุทัยธานี', 'UTI', 1),
(43, 'SANGHA-CBI', 'วิทยาลัยสงฆ์ชลบุรี', 'SANGHA_COLLEGE', 'ชลบุรี', 'CBI', 1),
(44, 'SANGHA-KRI', 'วิทยาลัยสงฆ์กาญจนบุรี ศรีไพบูลย์', 'SANGHA_COLLEGE', 'กาญจนบุรี', 'KRI', 1),
(45, 'SANGHA-CTI', 'วิทยาลัยสงฆ์จันทบุรี', 'SANGHA_COLLEGE', 'จันทบุรี', 'CTI', 1),
(46, 'SANGHA-CRI', 'วิทยาลัยสงฆ์เชียงราย', 'SANGHA_COLLEGE', 'เชียงราย', 'CRI', 1),
(47, 'SANGHA-SNI', 'วิทยาลัยสงฆ์สุราษฎร์ธานี', 'SANGHA_COLLEGE', 'สุราษฎร์ธานี', 'SNI', 1),
(48, 'SANGHA-KPT', 'วิทยาลัยสงฆ์กำแพงเพชร', 'SANGHA_COLLEGE', 'กำแพงเพชร', 'KPT', 1),
(49, 'UNIT-SKA', 'หน่วยวิทยบริการ วิทยาลัยสงฆ์นครศรีธรรมราช จังหวัดสงขลา', 'ACADEMIC_UNIT', 'สงขลา', 'SKA', 1),
(50, 'UNIT-UTT', 'หน่วยวิทยบริการวัดหมอนไม้', 'ACADEMIC_UNIT', 'อุตรดิตถ์', 'UTT', 1),
(51, 'UNIT-KSN', 'หน่วยวิทยบริการจังหวัดกาฬสินธุ์', 'ACADEMIC_UNIT', 'กาฬสินธุ์', 'KSN', 1),
(52, 'UNIT-SKM', 'หน่วยวิทยบริการวัดอินทาราม จ.สมุทรสงคราม', 'ACADEMIC_UNIT', 'สมุทรสงคราม', 'SKM', 1);

SET FOREIGN_KEY_CHECKS = 1;
