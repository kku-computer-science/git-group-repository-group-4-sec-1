# GetDataReportPrint

<!-- TOC -->

## getPaperData($userId)

### เพื่อ: 
ส่งออกข้อมูลงานวิจัยของ user คนนั้น

### ข้อมูลรับ:
- `userId` (int): รหัสผู้ใช้

### ข้อมูลออก:
```json
[
  {
    "paper_name": "ชื่อ paper",
    "authors": [
      {
        "fname_en": "ชื่อ",
        "lname_en": "นามสกุล",
        "author_type": ลำดับผู้แต่ง
      },
      ...
    ],
    "paper_yearpub": ปีที่ตีพิมพ์,
    "paper_sourcetitle": "ชื่อวารสาร",
    "paper_issue": "ฉบับที่",
    "paper_volume": "เล่มที่",
    "paper_page": "หน้า",
    "paper_url": "url",
    "paper_doi": "doi"
  },
  ...
]
```

### วิธีใช้
```php
use App\Services\GetDataReportPrint;

GetDataReportPrint::getPaperData(2);
```

### สถานะ
- **เสร็จแล้ว**

### แก้ไขล่าสุด
- **แก้ครั้งที่ 3**

---

## getAuthorData($userId)

### เพื่อ: 
ส่งออกข้อมูลของ user คนนั้น

### ข้อมูลรับ:
- `userId` (int): รหัสผู้ใช้

### ข้อมูลออก:
```json
{
  "fname_en": "Ngamnij",
  "lname_en": "Arch-int",
  "academic_ranks_en": "Associate Professor",
  "email": "ngamnij@kku.ac.th",
  "education": [
    {
      "uname": "มหาวิทยาลัยเกษตรศาสตร์",
      "qua_name": "วท.บ. (สถิติ)",
      "year": "2531"
    }
  ],
  "experties": [
    "Semantic Web and Ontology Engineering",
    "Ontology-based Data Integration",
    "Semantic Sentiment Analysis"
  ]
}
```

### วิธีใช้
```php
use App\Services\GetDataReportPrint;

GetDataReportPrint::getAuthorData(2);
```

### สถานะ
- **เสร็จแล้ว**

### แก้ไขล่าสุด
- **แก้ครั้งที่ 1**

---

## getBookData($userId)

### เพื่อ: 
ส่งออกข้อมูลหนังสือของ user คนนั้น

### ข้อมูลรับ:
- `userId` (int): รหัสผู้ใช้

### ข้อมูลออก:
```json
[
  {
    "ac_name": "โครงข่ายประสาทเทียม Artificial Neural Networks",
    "authors": [
      {
        "fname_en": "Sirapat",
        "lname_en": "Chiewchanwattana",
        "author_type": null
      }
    ],
    "ac_year": "2018-01-01",
    "ac_type": "book"
  }
]
```

### วิธีใช้
```php
use App\Services\GetDataReportPrint;

GetDataReportPrint::getBookData(8);
```

### สถานะ
- **เสร็จแล้ว**

### แก้ไขล่าสุด
- **แก้ครั้งที่ 1**

---

## getOtherWorkData($userId)

### เพื่อ: 
ส่งออกข้อมูลทางวิชาการอื่นๆของ user คนนั้น

### ข้อมูลรับ:
- `userId` (int): รหัสผู้ใช้

### ข้อมูลออก:
```json
[
  {
    "ac_name": "สื่อการเรียนการสอนสำหรับเด็กที่มีความบกพร่องทางการได้ยินด้วยเทคโนโลยีเสมือนจริง",
    "authors": [
      {
        "fname_en": "Sirapat",
        "lname_en": "Chiewchanwattana",
        "author_type": 1
      }
    ],
    "ac_year": "2560-05-18",
    "ac_type": "ลิขสิทธิ์"
  }
]
```

### วิธีใช้
```php
use App\Services\GetDataReportPrint;

GetDataReportPrint::getOtherWorkData(8);
```

### สถานะ
- **เสร็จแล้ว**

### แก้ไขล่าสุด
- **แก้ครั้งที่ 1**

