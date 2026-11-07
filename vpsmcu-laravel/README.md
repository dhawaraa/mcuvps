# ระบบสารสนเทศการปฏิบัติวิปัสสนากรรมฐาน มจร (VPSMCU - Laravel Edition)
**Mahachulalongkornrajavidyalaya University Vipassana Meditation Information System**

ระบบบริหารจัดการและประมวลผลข้อมูลการปฏิบัติวิปัสสนากรรมฐาน มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย (มจร) ครอบคลุม 51 ส่วนงานทั่วประเทศ พัฒนาด้วย **Laravel 11, PHP 8.4, MariaDB 10.6, Apache 2.4 และ Tailwind CSS (Blade Views)**  
รองรับการนำไปติดตั้งใช้งานจริงบนโครงสร้างพื้นฐานโฮสติ้งของมหาวิทยาลัย (`Apache/2.4.63 (FreeBSD) PHP/8.4.8 MariaDB` - `https://vps.mcu.ac.th`)

---

## 📌 สรุปสถานะการพัฒนาล่าสุด (Recent Progress & Update Log)

### 1. ระบบพอร์ทัลนิสิต (Student Portal & Profile)
- **หน้าข้อมูลส่วนตัวนิสิต (`/student/profile/`)**:
  - ใช้โครงสร้าง Clean URL มาตรฐานตามแนวทาง RESTful
  - จัดการข้อมูลโปรไฟล์ที่ยังไม่สมบูรณ์ แสดงข้อความ `(ยังไม่ระบุ)` สีเทา พร้อมกล่องเตือนแนะนำให้นิสิตปรับปรุงข้อมูลให้ครบถ้วน
  - ปรับปรุงแถบเมนูด้านบน (Navbar) ให้นิ่ง สะอาดตา นำป้ายรหัสและชื่อที่ซ้ำซ้อนออก เพื่อไม่ให้เบียดเมนูสำคัญ
- **หน้าแดชบอร์ดตรวจสอบประวัติ (`/student/dashboard/`)**:
  - เพิ่มป้ายสถานะ **`🛡️ บัญชีผู้ใช้งาน: [รหัสนิสิต]`** และชื่อ-สกุลนิสิตบน Hero Banner ด้านบน ให้มีอัตลักษณ์เดียวกันกับหน้าโปรไฟล์
  - นำข้อมูล Mock หลอกออกทั้งหมด เริ่มต้นนับจำนวนวันสะสมจากประวัติที่ลงทะเบียนและผ่านการอบรมจริงเท่านั้น

### 2. โมดูลบัณฑิตศึกษาและยื่นคำร้อง e-Document (`/grad/request/` / `/grad_request.php`)
- **ระบบ Auto-Lookup & Autofill**:
  - เมื่อกรอกรหัสนิสิต ระบบจะดึงข้อมูลส่วนตัว (คำนำหน้า, ชื่อ, ฉายา, คณะ, สาขา, สังกัด, ข้อมูลติดต่อ) มากรอกลงในฟอร์มทุกขั้นตอนโดยอัตโนมัติ
  - ตรวจสอบยอดวันสะสมเดิมจากฐานข้อมูลมาแสดงเป็นข้อมูลอ้างอิงทันที
- **เกณฑ์จำนวนวันสะสมตามระดับการศึกษา (Degree Target Days)**:
  - **ปริญญาเอก (Doctoral):** 45 วัน
  - **ปริญญาโท (Master):** 30 วัน
  - **ปริญญาตรี (Bachelor):** 40 วัน (สะสมปีละ 10 วัน รวม 4 ปี)
  - **ประกาศนียบัตร (Certificate):** 15 วัน
- **ปลดล็อกเกณฑ์ขั้นต่ำในการยื่นคำร้อง**:
  - นิสิตสามารถยื่นสะสมวันแบบทยอยยื่นได้ตามจริง (เริ่มต้นตั้งแต่ 1 วันขึ้นไป) โดยไม่ถูกล็อกขั้นต่ำ พร้อมแสดงป้ายเตือนเป้าหมายรวมของหลักสูตร

### 3. ความเข้ากันได้กับโฮสติ้งจริง (Production FreeBSD Apache Directive)
- **Single-Entry Dispatcher & Wrappers**:
  - สร้าง PHP Wrappers (`/grad/request/index.php`, `grad_request.php`, ฯลฯ) เพื่อรองรับข้อจำกัด `AllowOverride None` ของเซิร์ฟเวอร์ `vps.mcu.ac.th`
  - รองรับทั้ง Clean URL และ Query Action (เช่น `?action=lookup&student_code=...`) ได้อย่างไร้รอยต่อ

---

## 🏛️ สถาปัตยกรรมระบบและเครื่องมือหลัก

- **Framework:** Laravel 11.x (PHP 8.4)
- **Container Environment:** Podman Rootless + Compose (SELinux Compliant)
- **Database:** MariaDB 10.6 (`VPSMCU-DB`)
- **Styling:** Tailwind CSS (Earth Tones / Organic Palette - เขียวมะกอก `#5A6B47`, เขียวป่าลึก `#2C3E2D`, ดินเผา `#C86D51`, ทราย/หินอ่อน `#F7F5EE`, เปลือกไม้ `#4A3B32`)
- **Icon System:** Lucide Icons 100%
- **Production Server:** `vps.mcu.ac.th` (202.28.52.24) - FreeBSD + Apache 2.4 + PHP 8.4

---

## ⚡️ คำสั่งสำหรับการพัฒนา (Developer Cheat Sheet)

```bash
# 1. การเปิด/ปิดระบบคอนเทนเนอร์ในเครื่อง (Local Podman)
podman-compose up -d
podman-compose down

# 2. การล้างแคช Blade Template (ต้องทำทุกครั้งหลังแก้ไฟล์ View)
podman exec VPSMCU php artisan view:clear

# 3. การรัน Migration และ Seeder
podman exec VPSMCU php artisan migrate
podman exec VPSMCU php artisan db:seed

# 4. ทดสอบหรือดีบักผ่าน Tinker
podman exec -it VPSMCU php artisan tinker

# 5. การเข้าใช้งาน Local Environment
# หน้าบ้าน (Portal): http://localhost:8086/
# หน้าหลังบ้าน (Admin): http://localhost:8086/login.php (admin / password)
# หน้าพอร์ทัลนิสิต: http://localhost:8086/student/login.php
# จัดการฐานข้อมูล (phpMyAdmin): http://localhost:8088/
```

---

## 🚀 แผนการพัฒนาต่อ (To-Do / Next Steps)

- [ ] **ระบบตรวจสอบเอกสารแนบคำร้อง e-Document หลังบ้าน (`/admin/grad_requests.php`)**:
  - หน้าเจ้าหน้าที่ตรวจสอบและอนุมัติชั่วโมง/วันสะสมของนิสิต
  - ปรับปรุงการดูไฟล์แนบโครงการและใบรับรองผ่าน Modal Preview
- [ ] **ระบบออกใบรับรอง/วุฒิบัตรดิจิทัล (Digital Certificate Generator)**:
  - รองรับการออกเอกสาร PDF หรือ e-Certificate พร้อม QR Code ตรวจสอบความถูกต้องสำหรับนิสิตที่สะสมครบตามเกณฑ์
- [ ] **การเชื่อมโยงข้อมูลการลงทะเบียน ป.ตรี เข้ากับ Dashboard อัตโนมัติ**:
  - เมื่อเจ้าหน้าที่เช็คชื่อเสร็จสิ้น (COMPLETED) ให้ยอดวัน 10 วันต่อปี สะท้อนเข้า Dashboard ทันที
- [ ] **ระบบแจ้งเตือนสถานะคำร้อง (Status Notification)**:
  - เพิ่มแจ้งเตือนผ่านหน้าแดชบอร์ดนิสิตเมื่อเจ้าหน้าที่อนุมัติหรือส่งคำร้องกลับมาให้แก้ไข
