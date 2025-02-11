# Change logs

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

### Update
    -แก้ไขRoutes/console เพื่อเพิ่มคำสั่งในการเรียกใช้Controller ได้แก้(update:paper-data,update:user_scopus,update:citation) 
    การเรียกใช้ Ex. php artisan update:paper-data
    -แก้ไขModel User เพิ่มความสัมพันธ์ของModel User และ UserScopus
### Bug fix
