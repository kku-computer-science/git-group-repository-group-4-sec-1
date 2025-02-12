# Bug Fix: The visualization of the publication status is neither match to what is on Google Scholar nor SCOPUS.

 ## Issue (ปัญหาที่เกิดขึ้น)
    - การแสดงผลของหน้าสถิติที่ระบุจำนวนของผู้เข้าชมงานวิจัยและปริมาณของงานวิจัยนั้นไม่ตรงกับปริมาณที่มีระบุในทั้ง Google Scholar หรือ SCOPUS
 ## fix (สิ่งที่แก้ไข)
    - ทำการเช็คตรวจสอบไฟล์และโค้ดที่มีลิงค์ที่ทำการเชื่อมระหว่างส่วนของ Frontend และ Backend 
    - แก้ไขไฟล์เพื่อให้สามารถดึงข้อมุลที่ส่งมาจาก Backend ไปแสดงผลที่ Frontend ให้ถูกต้อง
 ## Impact on User (ผลที่เกิดกับ User)
    - การแสดงผล (Visualization) ของจำนวนและผลงานที่ตีพิมพ์ของนักวิจัยตรงกับฐานข้อมูลใน Google Scholar และ SCOPUS
 ## Available (วันและเวอร์ชั่นที่จะมีผลการแก้ไขหลังจากนี้)
    12/2/2568


# Bug Fix: The system can no longer connect to the APIs/retrieve the information from the API of the major publications DB such as WOS, SCOPUS, Google Scholar, and TCI

 ## Issue (ปัญหาที่เกิดขึ้น)
    - ระบบไม่สามารถดึงข้อมูลหรือเชื่อมต่อข้อมูลจากเว็ปไซต์เผยแพร่งานวิจัยผ่านทาง API ได้ ทั้งเว็ปไซต์ WOS , SCOPUS , Google Scholar และ TCI
 ## fix (สิ่งที่แก้ไข)
    - ตรวจสอบสถานของ Token ของ API และลักษณะของการให้บริการของ API จากเว็ปที่เผยแพร่งานวิจัย
    - สร้างและแก้ไขตารางที่เก็บข้อมูลงานวิจัยในส่วนต่างๆ
    - ทำการเชื่อมต่อและจัดการ API ของเว็ปไซต์ที่เผยแพร่งานวิจัยใหม่
 ## Impact on User (ผลที่เกิดกับ User)
    - ข้อมูลและจำนวนงานวิจัยของนักวิจัยที่แสดงจะแสดงได้ตรงกับข้อมูลที่มีอยู่ใน API ของ WOS , SCOPUS , GOOGLE Scholar , TCI
 ## Available (วันและเวอร์ชั่นที่จะมีผลการแก้ไขหลังจากนี้)
    12/2/2568

