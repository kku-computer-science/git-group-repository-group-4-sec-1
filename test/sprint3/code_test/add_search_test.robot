*** Settings ***
Library  SeleniumLibrary
Library    XML
Library    ../../sprint2/code_test/UAT_sprint2/resources/doc_reader.py
Suite Setup  Login As Admin
Suite Teardown  Close Browser

*** Keywords ***
Login As Admin
    Open Browser  ${LOGIN_URL}  ${BROWSER}
    Input Text  name=username  ${USERNAME}
    Input Text  name=password  ${PASSWORD}
    Click Button  xpath=//button[@type='submit']
    Wait Until Element Is Visible  xpath=//span[@class="menu-title" and contains(text(),"Highlights")]
Suite Teardown  Close Browser

*** Variables ***
${LOGIN_URL}  https://cs0467.cpkkuhost.com/login
${USERNAME}  thanlao@kku.ac.th
${PASSWORD}  123456789
${BROWSER}  chrome
${TARGET_TITLE}    Set Variable    วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU กับมหาวิทยาลัยชั้นนำในเวียดนาม เสริมความร่วมมือด้านการศึกษาและวิจัย


*** Test Cases ***

Valid Case - เพิ่มข่าวไฮไลท์
    [Documentation]  ทดสอบการเพิ่มข่าวไฮไลท์พร้อมไฟล์ภาพ
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
    # กรอกข้อมูลในฟอร์ม
    Wait Until Element Is Visible  xpath=//input[@type='file']
    Choose File  xpath=//input[@type='file']  C:/Users/WINDOWS 11/Downloads/valid.png
    Input Text  id=title  วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU กับมหาวิทยาลัยชั้นนำในเวียดนาม เสริมความร่วมมือด้านการศึกษาและวิจัย
    Sleep    2s
    Wait Until Element Is Visible  xpath=//div[@contenteditable='true']
    Click Element  xpath=//div[@contenteditable='true']
    Input Text  xpath=//div[@contenteditable='true']  "เมื่อวันที่ 27 พฤศจิกายน 2567 วิทยาลัยการคอมพิวเตอร์ มหาวิทยาลัยขอนแก่น นำโดย รศ. ดร.สิรภัทร เชี่ยวชาญวัฒนา คณบดีวิทยาลัยการคอมพิวเตอร์ พร้อมด้วย ผศ. ดร.พุธษดี ศิริแสงตระกูล รองคณบดีฝ่ายวิชาการ ผศ. ดร.ชิตสุธา สุ่มเล็ก รองคณบดีฝ่ายวิจัยและนวัตกรรม ผศ. ดร.คำรณ สุนัติ หัวหน้าสาขาวิทยาการคอมพิวเตอร์ ผศ. ดร.สุมณฑา เกษมวิลาศ อาจารย์ประจำวิทยาลัยการคอมพิวเตอร์ และ นางสาวกษมา อ้อทอง นักวิเทศสัมพันธ์ ได้เดินทางไปยังประเทศเวียดนามเพื่อร่วมลงนามในบันทึกข้อตกลงความร่วมมือทางวิชาการ (MOU) กับมหาวิทยาลัยชั้นนำในเวียดนาม ได้แก่ Pham Ngoc Thach University of Medicine และ University of Science"
    Sleep    2s    
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Scroll Element Into View  xpath=//input[@class='select2-search__field']
    Click Element  xpath=//input[@class='select2-search__field']
    Sleep    2s
     # เลือกแท็ก computing
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Input Text  xpath=//input[@class='select2-search__field']  computing
    Wait Until Element Is Visible  xpath=//li[@role='option' and contains(text(),'computing')]
    Click Element  xpath=//li[@role='option' and contains(text(),'computing')]
    Sleep    2s
    # เลือกแท็ก มข
    Wait Until Element Is Visible  xpath=//input[@class='select2-search__field']
    Input Text  xpath=//input[@class='select2-search__field']  มข
    Wait Until Element Is Visible  xpath=//li[@role='option' and contains(text(),'มข')]
    Click Element  xpath=//li[@role='option' and contains(text(),'มข')]
    Scroll Element Into View  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Wait Until Element Is Visible  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Click Element  xpath=//button[@class='btn btn-primary mt-4 fw-bold' and contains(text(), 'บันทึก')]
    Sleep  3s  
    Wait Until Element Is Visible  xpath=//p[contains(text(), "เพิ่มไฮไลท์สำเร็จ")]  timeout=10s
    Element Should Contain  xpath=//p[contains(@class, "fs-5")]  เพิ่มไฮไลท์สำเร็จ
    Sleep  2s
    Click Element  xpath=//button[@id="closeSuccessModal"]
    Sleep    5s

Valid Case - กดเผยแพร่ข่าวถูกต้อง
    [Documentation]  ทดสอบการเผยแพร่ข่าวไฮไลท์
    Execute JavaScript    window.scrollBy(0, 600);
    Sleep    2s
    ${TARGET_TITLE}    Set Variable    วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU
    Wait Until Element Is Visible    xpath=//td[contains(text(), "${TARGET_TITLE}")]  timeout=10s
    Click Element    xpath=//td[contains(text(), "${TARGET_TITLE}")]/following-sibling::td//a[contains(@class, 'btn-dark') and contains(text(), 'พรีวิว')]
    Sleep    2s
    Scroll Element Into View    xpath=//span[@class='tags' and text()='computing']
    Page Should Contain Element  xpath=//span[@class='tags' and text()='computing']
    Page Should Contain Element  xpath=//span[@class='tags' and text()='มข']
    Page Should Contain    text=วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU กับมหาวิทยาลัยชั้นนำในเวียดนาม เสริมความร่วมมือด้านการศึกษาและวิจัย
    Page Should Contain    text=มื่อวันที่ 27 พฤศจิกายน 2567 วิทยาลัยการคอมพิวเตอร์ มหาวิทยาลัยขอนแก่น นำโดย รศ. ดร.สิรภัทร เชี่ยวชาญวัฒนา คณบดีวิทยาลัยการคอมพิวเตอร์ พร้อมด้วย ผศ. ดร.พุธษดี ศิริแสงตระกูล รองคณบดีฝ่ายวิชาการ ผศ. ดร.ชิตสุธา สุ่มเล็ก รองคณบดีฝ่ายวิจัยและนวัตกรรม ผศ. ดร.คำรณ สุนัติ หัวหน้าสาขาวิทยาการคอมพิวเตอร์ ผศ. ดร.สุมณฑา เกษมวิลาศ อาจารย์ประจำวิทยาลัยการคอมพิวเตอร์ และ นางสาวกษมา อ้อทอง นักวิเทศสัมพันธ์ ได้เดินทางไปยังประเทศเวียดนามเพื่อร่วมลงนามในบันทึกข้อตกลงความร่วมมือทางวิชาการ (MOU) กับมหาวิทยาลัยชั้นนำในเวียดนาม ได้แก่ Pham Ngoc Thach University of Medicine และ University of Science
    Page Should Contain    text=ธารวิภา เหล่าชัย
    Sleep    2s
    Execute JavaScript    window.scrollBy(0, 600);
    Sleep    3s
    Wait Until Element Is Visible  xpath=//button[@class='btn btn-success mx-2 fw-bold' and contains(text(), 'เผยแพร่ทันที')]
    Click Element  xpath=//button[@class='btn btn-success mx-2 fw-bold' and contains(text(), 'เผยแพร่ทันที')]
    Sleep    5s
    Wait Until Element Is Visible  xpath=//p[contains(text(), "เผยแพร่ข่าวเรียบร้อยแล้ว!")]  timeout=10s
    Element Should Contain  xpath=//p[contains(@class, "fs-5")]  เผยแพร่ข่าวเรียบร้อยแล้ว!
    Sleep  2s
    Click Element  xpath=//button[@id="closeSuccessModal"]
    Sleep    5s
    Execute JavaScript    window.scrollBy(0, 200);
    Sleep  2s
valid Case - กดแสดงข่าวในหน้าหลัก
    [Documentation]  ทดสอบการกดแสดงข่าวในหน้าหลัก
    Execute JavaScript    window.scrollBy(0, 200);
    Sleep  2s
    # เปิดเมนู Published Highlight
    Execute JavaScript    window.scrollBy(0, 200);
    Sleep  2s
    Wait Until Element Is Visible  xpath=//a[@class="nav-link" and contains(text(),"Published Highlight")]  timeout=10s
    Click Element  xpath=//a[@class="nav-link" and contains(text(),"Published Highlight")]
    Sleep  3s
     # เลื่อนหาข่าวที่ต้องการ
    ${TARGET_TITLE}  Set Variable  วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU
    # Scroll ลงเพื่อให้แสดงผลข้อมูล
    Execute JavaScript    window.scrollBy(0, 200)
    Sleep  3s
    # เลื่อนหาข่าวที่ต้องการ
    Scroll Element Into View  
    ...  xpath=//h5[starts-with(text(), 'วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU')]
    Sleep  3s
    Wait Until Element Is Visible  
    ...  xpath=//h5[starts-with(text(), 'วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU')]  timeout=10s

    # ตรวจสอบและเลือก Checkbox ที่อยู่ในข่าวนี้
    ${is_checked}=  Run Keyword And Return Status  
    ...  Checkbox Should Be Selected  
    ...  xpath=//h5[starts-with(text(), 'วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU')]/following-sibling::input[@type='checkbox']
    Wait Until Element Is Visible  
    ...  xpath=//h5[starts-with(text(), 'วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU')]/following-sibling::input[@type='checkbox']  timeout=5s
    Execute JavaScript  
    ...  document.evaluate("//h5[starts-with(text(), 'วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU')]/following-sibling::input[@type='checkbox']", document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue.click();
    Sleep  3s
    # กดปุ่มบันทึก
    Wait Until Element Is Visible  
    ...  xpath=//button[@type='submit' and contains(@class, 'btn-success')]  timeout=10s
    
    Wait Until Element Is Enabled  
    ...  xpath=//button[@type='submit' and contains(@class, 'btn-success')]
    Sleep  3s
    Execute JavaScript  
    ...  document.evaluate("//button[@type='submit' and contains(@class, 'btn-success')]", document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue.click();


    # ตรวจสอบข้อความยืนยัน
    Wait Until Element Is Visible  
    ...  xpath=//p[contains(text(), 'เผยแพร่ไฮไลท์เรียบร้อยแล้ว!')]  timeout=10s
    Sleep  3s
    Element Text Should Be  
    ...  xpath=//p[contains(text(), 'เผยแพร่ไฮไลท์เรียบร้อยแล้ว!')]  
    ...  เผยแพร่ไฮไลท์เรียบร้อยแล้ว!

    # กดปุ่มปิด Modal
    Wait Until Element Is Visible  
    ...  xpath=//button[@id="closeSuccessModal"]  timeout=10s
    Sleep  3s
    Wait Until Element Is Enabled  
    ...  xpath=//button[@id="closeSuccessModal"]

    Click Element  
    ...  xpath=//button[@id="closeSuccessModal"]
    Sleep    3s

Valid case - ทดสอบการแสดงผลไฮไลท์ในหน้าหลัก
    [Documentation]  ทดสอบการแสดงผลไฮไลท์ในหน้าหลัก
    ${IMG_SRC}  Set Variable   sethttps://cs0467.cpkkuhost.com/storage/news_banners/Pv83oBcnw90Fsi0j5O4QtJVwcdWgTTnG7Kiyn1EN.png
    ${TITLE_TEXT}  Set Variable  วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU 
    Open Browser  https://cs0467.cpkkuhost.com/  chrome
    Maximize Browser Window

    Wait Until Element Is Visible  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT}')]  
    ...  timeout=10s
    Sleep    3s

    # เลื่อนลงมาตรวจสอบ Title ในการ์ด
    Scroll Element Into View  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT}') and contains(@class, 'card-title')]
    Sleep    2s
    Wait Until Element Is Visible  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT}') and contains(@class, 'card-title')]  
    ...  timeout=10s
    Sleep    2s
    # คลิกที่การ์ดที่มี Title ที่ถูกต้อง
    Click Element  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT}') and contains(@class, 'card-title')]/ancestor::div[contains(@class, 'card')]
    Sleep    5s
    Page Should Contain    วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU กับมหาวิทยาลัยชั้นนำในเวียดนาม เสริมความร่วมมือด้านการศึกษาและวิจัย
    Page Should Contain    เมื่อวันที่ 27 พฤศจิกายน 2567 วิทยาลัยการคอมพิวเตอร์ มหาวิทยาลัยขอนแก่น นำโดย รศ. ดร.สิรภัทร เชี่ยวชาญวัฒนา คณบดีวิทยาลัยการคอมพิวเตอร์ พร้อมด้วย ผศ. ดร.พุธษดี ศิริแสงตระกูล รองคณบดีฝ่ายวิชาการ ผศ. ดร.ชิตสุธา สุ่มเล็ก รองคณบดีฝ่ายวิจัยและนวัตกรรม ผศ. ดร.คำรณ สุนัติ หัวหน้าสาขาวิทยาการคอมพิวเตอร์ ผศ. ดร.สุมณฑา เกษมวิลาศ อาจารย์ประจำวิทยาลัยการคอมพิวเตอร์ และ นางสาวกษมา อ้อทอง นักวิเทศสัมพันธ์ ได้เดินทางไปยังประเทศเวียดนามเพื่อร่วมลงนามในบันทึกข้อตกลงความร่วมมือทางวิชาการ (MOU) กับมหาวิทยาลัยชั้นนำในเวียดนาม ได้แก่ Pham Ngoc Thach University of Medicine และ University of Science
    Page Should Contain    ธารวิภา เหล่าชัย

    
Valid case - ทดสอบการแสดงผลไฮไลท์ในหน้าไฮไลท์
    [Documentation]  ทดสอบการแสดงผลไฮไลท์ในหน้าไฮไลท์
    ${IMG_SRC}  Set Variable   sethttps://cs0467.cpkkuhost.com/storage/news_banners/Pv83oBcnw90Fsi0j5O4QtJVwcdWgTTnG7Kiyn1EN.png
    ${TITLE_TEXT}  Set Variable  วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU 
    Wait Until Element Is Visible  
    ...  xpath=//a[@class="nav-link" and contains(text(), 'Highlight')]  
    ...  timeout=10s
    Click Element  
    ...  xpath=//a[@class="nav-link" and contains(text(), 'Highlight')]

    # ตรวจสอบว่าเปลี่ยนไปยังหน้า "Highlight"
    Wait Until Location Contains  /highlight  timeout=10s

    # ตรวจสอบว่าข่าวที่ต้องการ (${TITLE_TEXT}) ปรากฏในหน้าไฮไลท์
    Wait Until Element Is Visible  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT}')]  
    ...  timeout=10s
    Sleep    5s
    Click Element  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT}') and contains(@class, 'card-title')]/ancestor::div[contains(@class, 'card')]
    Sleep    5s
    Page Should Contain    วิทยาลัยการคอมพิวเตอร์ มข. ลงนาม MOU กับมหาวิทยาลัยชั้นนำในเวียดนาม เสริมความร่วมมือด้านการศึกษาและวิจัย
    Page Should Contain    เมื่อวันที่ 27 พฤศจิกายน 2567 วิทยาลัยการคอมพิวเตอร์ มหาวิทยาลัยขอนแก่น นำโดย รศ. ดร.สิรภัทร เชี่ยวชาญวัฒนา คณบดีวิทยาลัยการคอมพิวเตอร์ พร้อมด้วย ผศ. ดร.พุธษดี ศิริแสงตระกูล รองคณบดีฝ่ายวิชาการ ผศ. ดร.ชิตสุธา สุ่มเล็ก รองคณบดีฝ่ายวิจัยและนวัตกรรม ผศ. ดร.คำรณ สุนัติ หัวหน้าสาขาวิทยาการคอมพิวเตอร์ ผศ. ดร.สุมณฑา เกษมวิลาศ อาจารย์ประจำวิทยาลัยการคอมพิวเตอร์ และ นางสาวกษมา อ้อทอง นักวิเทศสัมพันธ์ ได้เดินทางไปยังประเทศเวียดนามเพื่อร่วมลงนามในบันทึกข้อตกลงความร่วมมือทางวิชาการ (MOU) กับมหาวิทยาลัยชั้นนำในเวียดนาม ได้แก่ Pham Ngoc Thach University of Medicine และ University of Science
    Page Should Contain    ธารวิภา เหล่าชัย
    Sleep    5s

Valid case - ทดสอบการค้นหาจากtitle และเนื้อหา อย่างเดียว
    [Documentation]  ทดสอบการค้นหาจากtitle และเนื้อหา
    ${SEARCH_TEXT}  Set Variable  ขอแสดงความยินดี
    ${TITLE_TEXT_1}  Set Variable  วิทยาลัยการคอมพิวเตอร์ มข. ขอแสดงความยินดีกับ อ. ด
    ${TITLE_TEXT_2}  Set Variable  วิทยาลัยการคอมพิวเตอร์ มข. ขอแสดงความยินดีกับ นายภ
    Maximize Browser Window
    Wait Until Element Is Visible  
    ...  xpath=//a[@class="nav-link" and contains(text(), 'Highlight')]  
    ...  timeout=10s

    Click Element  
    ...  xpath=//a[@class="nav-link" and contains(text(), 'Highlight')]
    
    Sleep    2s
    Wait Until Element Is Visible  
    ...  xpath=//input[@name='search']  
    ...  timeout=10s
    Sleep    2s
    Input Text  
    ...  xpath=//input[@name='search']  
    ...  ${SEARCH_TEXT}
    
    Sleep    2s
    Press Keys  
    ...  xpath=//input[@name='search']  
    ...  RETURN

    Sleep    2s
     # ตรวจสอบว่ามีทั้ง 2 Titles แสดงอยู่
    Wait Until Element Is Visible  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT_1}')]  
    ...  timeout=10s

    Wait Until Element Is Visible  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT_2}')]  
    ...  timeout=10s
    Sleep    4s


Valid case - ทดสอบการค้นหาจากtag อย่างเดียว
    [Documentation]  ทดสอบการค้นหาจากtitle และเนื้อหา
    ${TAG_SEARCH_TEXT}  Set Variable  cpkku
    ${TITLE_TEXT_1}  Set Variable  วิทยาลัยการคอมพิวเตอร์ มข. ขอแสดงความยินดีกับ อ. ด
    ${TITLE_TEXT_2}  Set Variable  วิทยาลัยการคอมพิวเตอร์ มข. ขอแสดงความยินดีกับ นายภ
    ${TITLE_TEXT_3}  Set Variable  วิทยาลัยการคอมพิวเตอร์ มข. ขอแสดงความยินดีกับ ผศ.

    
    Wait Until Element Is Visible  
    ...  xpath=//input[@class='select2-search__field']  
    ...  timeout=10s
    # เคลียร์ช่องค้นหาหลัก
    Clear Element Text  
    ...  xpath=//input[@name='search']

    # เคลียร์แท็กที่เลือกอยู่ (กดปุ่มปิด X ของแต่ละแท็ก)
    ${selected_tags}=  Get WebElements  
    ...  xpath=//span[@class='select2-selection__choice__remove']

    FOR  ${tag}  IN  @{selected_tags}
        Click Element  ${tag}
        Sleep  1s
    END
    Sleep    2s

    # พิมพ์ Tag "cpkku" ลงในช่องค้นหา
    Input Text  
    ...  xpath=//input[@class='select2-search__field']  
    ...  ${TAG_SEARCH_TEXT}
    Sleep    3s
    # รอให้ Option "cpkku" ปรากฏ แล้วคลิกเลือก
    Wait Until Element Is Visible  
    ...  xpath=//li[@role='option' and contains(text(),'${TAG_SEARCH_TEXT}')]  
    ...  timeout=5s
    Sleep    3s
    Click Element  
    ...  xpath=//li[@role='option' and contains(text(),'${TAG_SEARCH_TEXT}')]
    Sleep    3s
    # กดปุ่มค้นหา
    Wait Until Element Is Visible  
    ...  xpath=//button[@type='submit' and contains(text(), 'ค้นหา')]  
    ...  timeout=5s
    Sleep    3s
    Click Element  
    ...  xpath=//button[@type='submit' and contains(text(), 'ค้นหา')]
    Sleep    3s
    # ตรวจสอบว่ามีทั้ง 2 Titles แสดงอยู่โดยใช้ `Wait Until Element Is Visible` เท่านั้น
    Wait Until Element Is Visible  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT_1}')]  
    ...  timeout=10s
    Sleep    1s
    Wait Until Element Is Visible  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT_2}')]  
    ...  timeout=10s
    Sleep    1s
    Wait Until Element Is Visible  
    ...  xpath=//h5[contains(text(), '${TITLE_TEXT_3}')]  
    ...  timeout=10s
    Sleep    3s

Valid Case - ทดสอบการค้นหาด้วย 2 Tags
    [Documentation]  ทดสอบการค้นหาข่าวในหน้า Highlight โดยใช้ 2 Tags "cpkku" และ "satit" แล้วต้องไม่มีผลลัพธ์
    ${TAG_1}  Set Variable  cpkku
    ${TAG_2}  Set Variable  satit
    Wait Until Element Is Visible  
    ...  xpath=//input[@class='select2-search__field']  
    ...  timeout=10s
    # เคลียร์ช่องค้นหาหลัก
    Clear Element Text  
    ...  xpath=//input[@name='search']
    
    # เคลียร์แท็กที่เลือกอยู่ (กดปุ่มปิด X ของแต่ละแท็ก)
    ${selected_tags}=  Get WebElements  
    ...  xpath=//span[@class='select2-selection__choice__remove']

    FOR  ${tag}  IN  @{selected_tags}
        Click Element  ${tag}
        Sleep  1s
    END

    # พิมพ์ Tag "cpkku" ลงในช่องค้นหา
    Input Text  
    ...  xpath=//input[@class='select2-search__field']  
    ...  ${TAG_1}
    Sleep    3s
    # รอให้ Option "cpkku" ปรากฏ แล้วคลิกเลือก
    Wait Until Element Is Visible  
    ...  xpath=//li[@role='option' and contains(text(),'${TAG_1}')]  
    ...  timeout=5s
    Sleep    3s

    Click Element  
    ...  xpath=//li[@role='option' and contains(text(),'${TAG_1}')]
    Sleep    3s
    # พิมพ์ Tag "satit" ลงในช่องค้นหา
    Input Text  
    ...  xpath=//input[@class='select2-search__field']  
    ...  ${TAG_2}
    Sleep    3s
    # รอให้ Option "satit" ปรากฏ แล้วคลิกเลือก
    Wait Until Element Is Visible  
    ...  xpath=//li[@role='option' and contains(text(),'${TAG_2}')]  
    ...  timeout=5s

    Click Element  
    ...  xpath=//li[@role='option' and contains(text(),'${TAG_2}')]

    # กดปุ่มค้นหา
    Wait Until Element Is Visible  
    ...  xpath=//button[@type='submit' and contains(text(), 'ค้นหา')]  
    ...  timeout=5s

    Click Element  
    ...  xpath=//button[@type='submit' and contains(text(), 'ค้นหา')]
    Sleep    3s
    # ตรวจสอบว่ามีข้อความ "ไม่มีไฮไลท์."
    Wait Until Element Is Visible  
    ...  xpath=//p[contains(text(), 'ไม่มีไฮไลท์.')]  
    ...  timeout=10s
    Sleep    3s
    Element Should Contain  
    ...  xpath=//p[contains(text(), 'ไม่มีไฮไลท์.')]  
    ...  ไม่มีไฮไลท์.
    Sleep    3s

*** Test Cases ***
Valid Case - ทดสอบการค้นหาด้วยtitle และtag
    [Documentation]  ทดสอบการค้นหาข่าวในหน้า Highlight โดยใช้ Title และ Tag แล้วต้องไม่มีผลลัพธ์
    ${SEARCH_TEXT}  Set Variable  คำรณ
    ${TAG_TEXT}   Set Variable  Phumin
    ${TITLE_TEXT}  Set Variable  วิทยาลัยการคอมพิวเตอร์ มข. ขอแสดงความยินดีกับ นายภ...
    
    # รอให้ช่องค้นหาหลักปรากฏและสามารถโต้ตอบได้
    Wait Until Element Is Visible And Enabled  xpath=//input[@name='search']  timeout=10s
    
    # ลบแท็กที่เลือกไว้แล้วทีละแท็ก
    ${tags_exist}=  Run Keyword And Return Status  Page Should Contain Element  xpath=//span[@class='select2-selection__choice__remove']
    
    # ทำการลบแท็กทีละอันจนกว่าจะไม่มีแท็กเหลืออยู่
    WHILE  ${tags_exist}
        # ดึง element ใหม่ทุกครั้งก่อนจะคลิก เพื่อหลีกเลี่ยง stale element
        ${first_tag}=  Get WebElement  xpath=(//span[@class='select2-selection__choice__remove'])[1]
        Click Element  ${first_tag}
        Sleep  0.5s
        ${tags_exist}=  Run Keyword And Return Status  Page Should Contain Element  xpath=//span[@class='select2-selection__choice__remove']
    END
    
    # พิมพ์ข้อความค้นหา
    Clear Element Text  xpath=//input[@name='search']
    Input Text  xpath=//input[@name='search']  ${SEARCH_TEXT}
    
    # รอให้ช่องค้นหา Tags ปรากฏและสามารถโต้ตอบได้
    Wait Until Element Is Visible And Enabled  xpath=//input[@class='select2-search__field']  timeout=10s
    
    # พิมพ์ Tag
    Input Text  xpath=//input[@class='select2-search__field']  ${TAG_TEXT}
    
    # รอให้ตัวเลือก Tag ปรากฏแล้วคลิก
    Wait Until Element Is Visible  xpath=//li[@role='option' and contains(text(),'${TAG_TEXT}')]  timeout=10s
    Wait Until Element Is Enabled  xpath=//li[@role='option' and contains(text(),'${TAG_TEXT}')]  timeout=5s
    Click Element  xpath=//li[@role='option' and contains(text(),'${TAG_TEXT}')]
    
    # รอให้ปุ่มค้นหาปรากฏแล้วคลิก
    Wait Until Element Is Visible And Enabled  xpath=//button[@type='submit' and contains(text(), 'ค้นหา')]  timeout=10s
    Click Element  xpath=//button[@type='submit' and contains(text(), 'ค้นหา')]
    
    # ตรวจสอบว่ามี Title ที่ต้องการปรากฏ
    Wait Until Element Is Visible  xpath=//h5[contains(text(), '${TITLE_TEXT}')]  timeout=15s
    Sleep    3s
    Close Browser

*** Keywords ***
Wait Until Element Is Visible And Enabled
    [Arguments]  ${locator}  ${timeout}=10s
    Wait Until Element Is Visible  ${locator}  timeout=${timeout}
    Wait Until Element Is Enabled  ${locator}  timeout=${timeout}


