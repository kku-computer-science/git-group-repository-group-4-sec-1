# Change logs

<<<<<<< HEAD
<<<<<<< HEAD
## Sprint 1
### New
 - เพิ่ม class PublicationRetrieval เพื่อจัดการดึงข้อมูลงานวิจัย
 - ใน class PublicationRetrieval เพิ่มฟังก์ชันดึงข้อมูล ของ author จาก google scholar
 - ใน class PublicationRetrieval เพิ่มฟังก์ชันดึงข้อมูล ของ paper จาก google scholar และ openAlxe
 - ใน class PublicationRetrieval เพิ่มฟังก์ชันจัดการ proxy ของ web scraping
 - เพิ่ม python script เพื่อค้นหาข้อมูล author จาก google scholar

=======

### New
- เพิ่ม UI ของ Publication status ได้แก่ Citation, h-index, i10-index
>>>>>>> b3d32d6c0aff8de361f5fd53ee0a909c4f84a6e8
=======
## Sprint 1
### New
    -เพิ่มตาราง user_scopus เพื่อเก็บข้อมูล user_ID, scopus_ID, citation, h_index, i10_index, citation_5years_ago, h_index_5years_ago, i10_index_5years_ago, scholar_id
    -เพิ่มModel UserScopus เพื่อเป็นตัวแปรในการเก็บข้อมูลของตารางuser_scopus
    -เพิ่มServices PaperUpdateService เพิ่มข้อมูลCitation และเพิ่มความสัมพันธ์ของผู้วิจัยกับpaperในตารางuser_paper
    -เพิ่มServices UserScopusService เพื่อเพิ่มข้อมูล scopus_ID, scholar_id ในตาราง user_scopus
    -เพิ่มController UserCitation.php เพื่อเพิ่มแลพอัปเดทข้อมูลของUser และ Paper
    -เพิ่มController UpdateUserScholarId.php เพื่อเพิ่มScholarId ในตารางuser_scopus
    -เพิ่มController  UpdatePaperController.php เพื่ออัปเดทงานวิจัยของScopus
    
    - เพิ่ม class PublicationRetrieval เพื่อจัดการดึงข้อมูลงานวิจัย
    - ใน class PublicationRetrieval เพิ่มฟังก์ชันดึงข้อมูล ของ author จาก google scholar
    - ใน class PublicationRetrieval เพิ่มฟังก์ชันดึงข้อมูล ของ paper จาก google scholar และ openAlxe
    - ใน class PublicationRetrieval เพิ่มฟังก์ชันจัดการ proxy ของ web scraping
    - เพิ่ม python script เพื่อค้นหาข้อมูล author จาก google scholar
    - เพิ่ม UI ของ Publication status ได้แก่ Citation, h-index, i10-index
>>>>>>> bd02ba802978deca007ac0a96651501f50b56dde
### Update
    -แก้ไขRoutes/console เพื่อเพิ่มคำสั่งในการเรียกใช้Controller ได้แก้(update:paper-data,update:user_scopus,update:citation) 
    การเรียกใช้ Ex. php artisan update:paper-data
    -แก้ไขModel User เพิ่มความสัมพันธ์ของModel User และ UserScopus
    - ปรับ UI ในหน้า home เพื่อแสดงสถิติงานตีพิมพ์ของ google scholar 
    - ปรับ UI ในหน้า researcher profile เพื่อแสดงสถิติงานตีพิมพ์และงานวิจัยของ google scholar
    - ปรับ UI ในหน้า report ให้สามารถแสดงสถิติงานวิจัย,จำนวนงานวิจัยและสถิติงานวิจัยที่ถูกอ้างอิง,จำนวนงานวิจัยที่ถูกอ้างอิงย้อนหลัง 5 ปีของ google scholar
