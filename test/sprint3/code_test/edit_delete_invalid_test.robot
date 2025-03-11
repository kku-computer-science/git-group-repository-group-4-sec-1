*** Settings ***
Library  SeleniumLibrary
Library    XML
Suite Setup  Login As Admin
Suite Teardown  Close Browser

*** Keywords ***
Login As Admin
    Open Browser  ${LOGIN_URL}  ${BROWSER}
    Input Text  name=username  ${USERNAME}
    Input Text  name=password  ${PASSWORD}
    Click Button  xpath=//button[@type='submit']
    Maximize Browser Window
    Wait Until Element Is Visible  xpath=//span[@class="menu-title" and contains(text(),"Highlights")]

Suite Teardown  Close Browser

*** Variables ***
${LOGIN_URL}  https://cs0467.cpkkuhost.com/login
${USERNAME}  thanlao@kku.ac.th
${PASSWORD}  123456789
${BROWSER}  chrome

*** Test Cases ***
Invalid Case - เพิ่มข่าวไฮไลท์กรณีที่ไม่กรอกข้อมูล
    [Documentation]  ทดสอบกรณีที่ไม่กรอกข้อมูลในช่องต่างๆ
    Maximize Browser Window 
    Wait Until Element Is Visible  xpath=//span[@class="menu-title" and contains(text(),"Highlights")]
    Click Element  xpath=//span[@class="menu-title" and contains(text(),"Highlights")]
    Sleep    2s
    Wait Until Element Is Visible  xpath=//a[@class="nav-link" and contains(text(),"Manage Highlight")]
    Click Element  xpath=//a[@class="nav-link" and contains(text(),"Manage Highlight")]
    Sleep    2s
    Wait Until Element Is Visible  xpath=//a[contains(text(),'เพิ่มไฮไลท์ใหม่')]
    Click Element  xpath=//a[contains(text(),'เพิ่มไฮไลท์ใหม่')]
    Sleep    2s
    
    # เลื่อนไปที่ด้านล่าง
    Execute JavaScript    window.scrollTo(0, document.body.scrollHeight);
    Sleep    1s

    Wait Until Element Is Visible  xpath=//button[@type='submit' and @class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]  timeout=10s

    Execute JavaScript    document.querySelector("button[type='submit'].btn.btn-primary.mt-4.fw-bold").click();
    Sleep    3s   
    # ตรวจสอบว่ามีข้อความแจ้งเตือนปรากฏ
    Wait Until Page Contains Element  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณากรอกหัวข้อไฮไลท์')]  timeout=10s
    Execute JavaScript    window.scrollBy(0, -200);
    Sleep    3s
    Execute JavaScript    window.scrollBy(0, -200);
    Wait Until Page Contains Element  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณากรอกรายละเอียดไฮไลท์')]  timeout=10s
    Wait Until Page Contains Element  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณาอัปโหลดไฟล์รูปภาพ')]  timeout=10s
    # ตรวจสอบข้อความแจ้งเตือน
    Element Text Should Be  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณากรอกหัวข้อไฮไลท์')]  กรุณากรอกหัวข้อไฮไลท์  
    Element Text Should Be  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณากรอกรายละเอียดไฮไลท์')]  กรุณากรอกรายละเอียดไฮไลท์
    Element Text Should Be  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณาอัปโหลดไฟล์รูปภาพ')]  กรุณาอัปโหลดไฟล์รูปภาพ
    Sleep    3s

Invalid Case - ไม่อัปโหลดไฟล์รูปภาพ
    [Documentation]  ทดสอบกรณีที่ไม่อัปโหลดไฟล์รูปภาพ แต่กรอกข้อมูลอื่นครบ
    Scroll Element Into View     xpath=//input[@type='file']
    Input Text  id=title  ข่าวกิจกรรม ITEX 2025
    Sleep    2s
    Wait Until Element Is Visible  xpath=//div[@contenteditable='true']
    Click Element  xpath=//div[@contenteditable='true']
    Input Text  xpath=//div[@contenteditable='true']  "การเข้าร่วมงาน ITEX 2025"
    Scroll Element Into View  xpath=//button[contains(text(), 'บันทึก')]
    Click Element  xpath=//button[contains(text(), 'บันทึก')]
    Sleep  2s
    Element Text Should Be  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณาอัปโหลดไฟล์รูปภาพ')]  กรุณาอัปโหลดไฟล์รูปภาพ
    Sleep   3s

Invalid Case - ไม่กรอกหัวข้อไฮไลท์
    [Documentation]  ทดสอบกรณีที่ไม่กรอกหัวข้อไฮไลท์ แต่กรอกข้อมูลอื่นครบ

    Scroll Element Into View     xpath=//input[@type='file']
    Sleep  1s
    Wait Until Element Is Visible  xpath=//input[@type='file']
    Choose File  xpath=//input[@type='file']  C:/Users/WINDOWS 11/Downloads/valid.png
    Sleep  1s
    Wait Until Element Is Visible  id=title
    Clear Element Text  id=title
    Wait Until Element Is Visible  xpath=//div[@contenteditable='true']
    Clear Element Text  xpath=//div[@contenteditable='true']
    Input Text  xpath=//div[@contenteditable='true']  "การเข้าร่วมงาน ITEX 2025"
    Sleep  2s    
    Scroll Element Into View  xpath=//button[contains(text(), 'บันทึก')]
    Wait Until Element Is Enabled  xpath=//button[contains(text(), 'บันทึก')]
    Click Element  xpath=//button[contains(text(), 'บันทึก')]
    Sleep  2s
    Wait Until Page Contains Element  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณากรอกหัวข้อไฮไลท์')]  timeout=10s
    Execute JavaScript    window.scrollBy(0, -100);
    # ตรวจสอบว่าแจ้งเตือนปรากฏขึ้น
    Element Text Should Be  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณากรอกหัวข้อไฮไลท์')]  กรุณากรอกหัวข้อไฮไลท์
    Sleep    3s

Invalid Case - ไม่กรอกรายละเอียดไฮไลท์
    [Documentation]  ทดสอบกรณีที่ไม่กรอกรายละเอียดไฮไลท์ แต่กรอกข้อมูลอื่นครบ
    

    Scroll Element Into View     xpath=//input[@type='file']
    Wait Until Element Is Visible  xpath=//input[@type='file']
    Choose File  xpath=//input[@type='file']  C:/Users/WINDOWS 11/Downloads/valid.png
    Sleep    2s    

    Wait Until Element Is Visible  id=title
    Clear Element Text  id=title
    Input Text  id=title  ข่าวกิจกรรม ITEX 2025
    Sleep    2s

    # ล้างข้อมูลจาก WYSIWYG Editor (Trumbowyg)
    Wait Until Element Is Visible  css=.trumbowyg-editor
    Execute JavaScript  
    ...  document.querySelector('.trumbowyg-editor').innerHTML = ''; 
    ...  document.querySelector('.trumbowyg-textarea').value = ''; 
    ...  document.querySelector('.trumbowyg-editor').dispatchEvent(new Event('input', { bubbles: true })); 
    ...  document.querySelector('.trumbowyg-textarea').dispatchEvent(new Event('change', { bubbles: true })); 
    Sleep  2s

    # กดปุ่มบันทึก
    Scroll Element Into View  xpath=//button[contains(text(), 'บันทึก')]
    Wait Until Element Is Enabled  xpath=//button[contains(text(), 'บันทึก')]
    Click Element  xpath=//button[contains(text(), 'บันทึก')]
    Sleep  2s
    Wait Until Page Contains Element  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณากรอกรายละเอียดไฮไลท์')]  timeout=10s
    Execute JavaScript    window.scrollBy(0, -100);

    # ตรวจสอบว่าแจ้งเตือนปรากฏขึ้น
    Element Text Should Be  xpath=//div[contains(@class, 'alert') and contains(text(), 'กรุณากรอกรายละเอียดไฮไลท์')]  กรุณากรอกรายละเอียดไฮไลท์
    
    Wait Until Element Is Visible  
    ...  xpath=//div[contains(@class, 'alert') and contains(normalize-space(text()), 'กรุณากรอกรายละเอียดไฮไลท์')]  
    ...  timeout=15s

    Sleep  3s


Invalid Case - เลือกไฟล์ที่ไม่ใช่ภาพ

    [Documentation]  ทดสอบการอัปโหลดไฟล์ที่ไม่ใช่ไฟล์ภาพ
    Scroll Element Into View     xpath=//input[@type='file']
    Wait Until Element Is Visible  xpath=//input[@type='file']
    Choose File  xpath=//input[@type='file']  C:/Users/WINDOWS 11/Downloads/Dump20250310.sql
    Clear Element Text  id=title
    Input Text  id=title  ข่าวกิจกรรม ITEX 2025
    Wait Until Element Is Visible  xpath=//div[@contenteditable='true']
    Clear Element Text  xpath=//div[@contenteditable='true']
    Input Text  xpath=//div[@contenteditable='true']  "การเข้าร่วมงาน ITEX 2025"
        
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Scroll Element Into View  xpath=//input[@class='select2-search__field']
    Click Element  xpath=//input[@class='select2-search__field']

     # เลือกแท็ก ITEX2025
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Input Text  xpath=//input[@class='select2-search__field']  ITEX2025
    Wait Until Element Is Visible  xpath=//li[@role='option' and contains(text(),'ITEX2025')]
    Click Element  xpath=//li[@role='option' and contains(text(),'ITEX2025')]
    
    # เลือกแท็ก มข
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Input Text  xpath=//input[@class='select2-search__field']  มข
    Wait Until Element Is Visible  xpath=//li[@role='option' and contains(text(),'มข')]
    Click Element  xpath=//li[@role='option' and contains(text(),'มข')]
    Scroll Element Into View  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Wait Until Element Is Visible  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Click Element  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Element Text Should Be  xpath=//div[contains(@class, 'alert') and contains(text(), 'ประเภทไฟล์ไม่ถูกต้อง กรุณาอัปโหลดไฟล์ .jpg, .jpeg หรือ .png')]  ประเภทไฟล์ไม่ถูกต้อง กรุณาอัปโหลดไฟล์ .jpg, .jpeg หรือ .png
    Sleep    3s

Invalid Case - เลือกไฟล์ที่มีขนาดเกิน5MB
    [Documentation]  ทดสอบการอัปโหลดไฟล์ที่มีขนาดเกิน 5MB
    Scroll Element Into View     xpath=//input[@type='file']
    Wait Until Element Is Visible  xpath=//input[@type='file']
    Choose File  xpath=//input[@type='file']  C:/Users/WINDOWS 11/Downloads/over5MBimg.jpg 
    Sleep    3s
    Clear Element Text  id=title
    Input Text  id=title  ข่าวกิจกรรม ITEX 2025
    Sleep    3s
    Wait Until Element Is Visible  xpath=//div[@contenteditable='true']
    Clear Element Text  xpath=//div[@contenteditable='true']
    Input Text  xpath=//div[@contenteditable='true']  "การเข้าร่วมงาน ITEX 2025"
    Sleep    3s    
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Scroll Element Into View  xpath=//input[@class='select2-search__field']
    Click Element  xpath=//input[@class='select2-search__field']
    Sleep    3s
     # เลือกแท็ก ITEX2025
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Input Text  xpath=//input[@class='select2-search__field']  ITEX2025
    Wait Until Element Is Visible  xpath=//li[@role='option' and contains(text(),'ITEX2025')]
    Click Element  xpath=//li[@role='option' and contains(text(),'ITEX2025')]
    Sleep    3s
    # เลือกแท็ก มข
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Input Text  xpath=//input[@class='select2-search__field']  มข
    Wait Until Element Is Visible  xpath=//li[@role='option' and contains(text(),'มข')]
    Click Element  xpath=//li[@role='option' and contains(text(),'มข')]
    Sleep    3s
    Scroll Element Into View  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Sleep    3s
    Wait Until Element Is Visible  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Click Element  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Sleep    3s
    Element Text Should Be  xpath=//div[contains(@class, 'alert') and contains(text(), 'ขนาดไฟล์เกิน 5MB กรุณาอัปโหลดไฟล์ที่มีขนาดเล็กลง')]  ขนาดไฟล์เกิน 5MB กรุณาอัปโหลดไฟล์ที่มีขนาดเล็กลง
    Sleep    3s
Valid Case - แก้ไขข่าว
    [Documentation]  ทดสอบการแก้ไขข่าว
    Maximize Browser Window 

    Wait Until Element Is Visible  xpath=//span[@class="menu-title" and contains(text(),"Highlights")]
    Click Element  xpath=//span[@class="menu-title" and contains(text(),"Highlights")]
    Sleep    3s
    Wait Until Element Is Visible  xpath=//a[@class="nav-link" and contains(text(),"Manage Highlight")]
    Sleep    3s
    Click Element  xpath=//a[@class="nav-link" and contains(text(),"Manage Highlight")]
    Wait Until Element Is Visible  xpath=//a[@class='btn btn-primary btn-sm mx-2' and contains(text(), 'แก้ไข')]
    Sleep    3s
    Execute JavaScript    window.scrollBy(0, 300);
    ${TARGET_TITLE}    Set Variable    วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU
    Wait Until Element Is Visible    xpath=//td[contains(text(), "${TARGET_TITLE}")]  timeout=10s
    Click Element    xpath=//td[contains(normalize-space(.), '${TARGET_TITLE}')]/ancestor::tr//a[contains(@class, 'btn-primary') and contains(text(), 'แก้ไข')]
    Sleep    2s
# กรอกข้อมูลในฟอร์ม
    Wait Until Element Is Visible  xpath=//input[@type='file']
    Choose File  xpath=//input[@type='file']  C:/Users/WINDOWS 11/Downloads/edit_img.png
    Sleep    3s
    # ลบและกรอกชื่อเรื่อง
    Wait Until Element Is Visible  id=title
    Clear Element Text  id=title
    Input Text  id=title  computing
    Sleep    3s
    # ลบและกรอกเนื้อหา
    Wait Until Element Is Visible  xpath=//div[@contenteditable='true']
    Clear Element Text  xpath=//div[@contenteditable='true']
    Input Text  xpath=//div[@contenteditable='true']  "computing2025"
    Sleep    3s
    # ลบแท็กเดิมและเพิ่มแท็กใหม่
    ${tag_elements}=  Get WebElements  xpath=//span[@class='select2-selection__choice']
    FOR  ${tag_element}  IN  @{tag_elements}
        Click Element  xpath=//span[@class='select2-selection__choice__remove']
    END
    Sleep    3s
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Scroll Element Into View  xpath=//input[@class='select2-search__field']
    Click Element  xpath=//input[@class='select2-search__field']
    Sleep    3s
    Input Text  xpath=//input[@class='select2-search__field']  computing
    Wait Until Element Is Visible  xpath=//li[@role='option' and contains(text(),'computing')]
    Click Element  xpath=//li[@role='option' and contains(text(),'computing')]
    Sleep    3s
    # บันทึกฟอร์ม
    Scroll Element Into View  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Wait Until Element Is Visible  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Click Element  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Sleep  5s
    Wait Until Element Is Visible  xpath=//*[contains(text(), "computing")]
    Sleep  2s
    Wait Until Element Is Visible  xpath=//p[contains(text(), "บันทึกการแก้ไขสำเร็จ")]  timeout=10s
    Element Should Contain  xpath=//p[contains(@class, "fs-5")]  บันทึกการแก้ไขสำเร็จ
    Sleep  1s
    Click Element  xpath=//button[@id="closeSuccessModal"]
    Sleep  2s
    
    Wait Until Element Is Visible    xpath=//td[contains(text(), "computing")]  timeout=10s
    Click Element    xpath=//td[contains(text(), "computing")]/following-sibling::td//a[contains(@class, 'btn-dark') and contains(text(), 'พรีวิว')]
    Sleep    2s
    Page Should Contain Element  xpath=//span[@class='tags' and text()='มข']
    Page Should Contain    text=computing
    Page Should Contain    text=computing2025
    Page Should Contain    text=ธารวิภา เหล่าชัย
    Sleep    3s

Valid Case - ลบข่าว
    [Documentation]  ทดสอบการลบข่าว

    Wait Until Element Is Visible  xpath=//span[@class="menu-title" and contains(text(),"Highlights")]
    Click Element  xpath=//span[@class="menu-title" and contains(text(),"Highlights")]
    Sleep    3s
    Wait Until Element Is Visible  xpath=//a[@class="nav-link" and contains(text(),"Manage Highlight")]
    Click Element  xpath=//a[@class="nav-link" and contains(text(),"Manage Highlight")]
    Sleep    3s
    # รอให้ Title ปรากฏในตาราง
    Wait Until Element Is Visible    xpath=//td[contains(text(), "computing")]  timeout=10s
    Click Element    xpath=//td[contains(normalize-space(.), 'computing')]/ancestor::tr//button[contains(@class, 'btn-danger') and contains(text(), 'ลบ')]

    # รอให้ Modal เปิดขึ้นมา
    Wait Until Element Is Visible  
    ...  xpath=//div[@id='deleteModal' and contains(@class, 'show')]  
    ...  timeout=10s

    # ตรวจสอบข้อความใน Modal
    Wait Until Element Is Visible  
    ...  xpath=//p[contains(@class, 'fs-5 lh-base fw-bold') and contains(text(), 'คุณแน่ใจหรือไม่ว่าต้องการลบไฮไลท์นี้')]  
    ...  timeout=10s

    Element Should Contain  
    ...  xpath=//p[contains(@class, 'fs-5 lh-base fw-bold')]  
    ...  คุณแน่ใจหรือไม่ว่าต้องการลบไฮไลท์นี้

    Sleep    3
    Click Element  
    ...  xpath=//button[@type='submit' and contains(@class, 'btn-danger')]

    Sleep    3s

    
    Element Text Should Be  xpath=//div[@class='alert alert-success']  ลบไฮไลท์สำเร็จ
    Sleep    3s
    Close Browser