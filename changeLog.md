# Change logs

## Sprint 3
### New
- เพิ่ม Model News, NewsTag, NewsImg, Tag, Img เก็บข้อมูลและเชื่อมต่อกับDataBase
- เพิ่ม ตาราง news, news_tag,tag, img ,news_img สำหรับเก็บข้อมูลของข่าว
- เพิ่ม Controller ShowAllNews สำหรับส่งข่าวออกไปแสดงในหน้าเว็บ และการทำฟังก์ชั่นSearch
- เพิ่ม Service GetHighlight สำหรับการจัดการการดึงข้อมูลของข่าว
- เพิ่ม Service HighlightEditor สำหรับ CRUD เพื่อใช้เพื่อจัดการข่าวของ admin staff 
- เพิ่ม Controller ReadNewsController สำหรับใช้อ่านข่าว
- เพิ่ม Controller ManageHighlight สำหรับการจัดการ เพิ่ม ลบ แก้ไข แสดงไฮไลท์ข่าวและการจัดการแท็กของข่าว
- เพิ่มหน้า add.blade สำหรับการเพิ่มข่าว
- เพิ่มหน้า manage.blade สำหรับจัดการข่าว
- เพิ่มหน้า highlight_detail.blade สำหรับการอ่านรายละเอียดข่าว
- เพิ่ม user manual (ภูมิพิพัฒน์)
- ร่างและจัดการหน้า preview highlight , edit highlight , published highlight(ภูมิพิพัฒน์)
### Update
- อัปเดต route สำหรับการจัดการข่าว
- อัปเดต route สำหรับการจัดการแท็กของข่าว
- เพิ่มการแสดงผลในหน้า Home ได้แก่ Highlight ข่าวประชาสัมพันธ์ 
### Fix

## Sprint 2
### New
- เพิ่ม class GetDataReport เพื่อ query ข้อมูลออกไปปริ้นท์
- ใน class GetDataReport เพิ่ม function ส่งออกข้อมูลไปเพื่อไปปริ้นท์ โดยมี fucntion ส่งออกดังนี้ getAuthorData, getPaperData, getOtherWorkData,getBookData
- เพิ่ม document ของวิธีใช้ class GetDataReport
- เพิ่ม pdf template เพื่อจัด format นำไปใช้กับฟังก์ชันสร้าง pdf
- ฟังก์ชันสร้าง pdf และสามารถดาวน์โหลดได้
- สร้างและจัดการหน้า Export Report ใน User profile หลังจาก login
- ฟังก์ชันสร้าง word และสามารถดาวน์โหลดได้
- เพิ่ม class GetReportDocxController เพื่อสร้างไฟล์ word จากข้อมูลที่ได้ใน GetDataReport
- เพิ่ม class UpDateUserPaper เพื่อดึงข้อมูลผู้เขียน และ Update ตาราง user_paper
- เพิ่ม class UpDateUserPaperController เพื่อใช้งาน Services UpDateUserPaper
- เพิ่ม UserManual_sprint2
### Update
- ปรับ route console เพื่อใช้งาน controller UpDateUserPaperController
- ปรับ function getPaperOpenAlxe ใน class PublicationRetrieval โดยแยกส่วนหัวออกมาสร้าง functionใหม่ คือ getDataOpenAlex
### Bug fix
- แก้ไขข้อมูลในตาราง paper และ user_paper
- แก้ปัญหา getPaperOpenAlxe หาข้อมูลไม่เจอ


## Sprint 1
### New
 - เพิ่ม class PublicationRetrieval เพื่อจัดการดึงข้อมูลงานวิจัย
 - ใน class PublicationRetrieval เพิ่มฟังก์ชันดึงข้อมูล ของ author จาก google scholar
 - ใน class PublicationRetrieval เพิ่มฟังก์ชันดึงข้อมูล ของ paper จาก google scholar และ openAlxe
 - ใน class PublicationRetrieval เพิ่มฟังก์ชันจัดการ proxy ของ web scraping
 - เพิ่ม python script เพื่อค้นหาข้อมูล author จาก google scholar
- เพิ่มตาราง user_scopus เพื่อเก็บข้อมูล user_ID, scopus_ID, citation, h_index, i10_index, citation_5years_ago, h_index_5years_ago, i10_index_5years_ago, scholar_id
- เพิ่มModel UserScopus เพื่อเป็นตัวแปรในการเก็บข้อมูลของตารางuser_scopus
- เพิ่มServices PaperUpdateService เพิ่มข้อมูลCitation และเพิ่มความสัมพันธ์ของผู้วิจัยกับpaperในตารางuser_paper
- เพิ่มServices UserScopusService เพื่อเพิ่มข้อมูล scopus_ID, scholar_id ในตาราง user_scopus
- เพิ่มController UserCitation.php เพื่อเพิ่มแลพอัปเดทข้อมูลของUser และ Paper
- เพิ่มController UpdateUserScholarId.php เพื่อเพิ่มScholarId ในตารางuser_scopus
- เพิ่มController  UpdatePaperController.php เพื่ออัปเดทงานวิจัยของScopus
- เพิ่ม UI ของ Publication status ได้แก่ Citation, h-index, i10-index
### Update
- แก้ไขRoutes/console เพื่อเพิ่มคำสั่งในการเรียกใช้Controller ได้แก้(update:paper-data,update:user_scopus,update:citation) 
    การเรียกใช้ Ex. php artisan update:paper-data
- แก้ไขModel User เพิ่มความสัมพันธ์ของModel User และ UserScopus
- ปรับ UI ในหน้า home เพื่อแสดงสถิติงานตีพิมพ์ของ google scholar 
- ปรับ UI ในหน้า researcher profile เพื่อแสดงสถิติงานตีพิมพ์และงานวิจัยของ google scholar
- ปรับ UI ในหน้า report ให้สามารถแสดงสถิติงานวิจัย,จำนวนงานวิจัยและสถิติงานวิจัยที่ถูกอ้างอิง,จำนวนงานวิจัยที่ถูกอ้างอิงย้อนหลัง 5 ปีของ google scholar

### Bug fix
