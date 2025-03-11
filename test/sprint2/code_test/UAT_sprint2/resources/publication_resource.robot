*** Settings ***
Library    SeleniumLibrary
Library    OperatingSystem
Library    DateTime
Library    doc_reader.py


*** Variables ***
${BASE_URL}          https://cs0467.cpkkuhost.com
${LOGIN_URL}         ${BASE_URL}/login
${USERNAME}          punhor1@kku.ac.th
${PASSWORD}          123456789
${DOWNLOAD_PATH}     C:/Users/WINDOWS 11/Downloads/

 
${REPORT_PDF}        publication_report_20250224.pdf
${REPORT_DOCX}       publication_report6.docx

# ตัวอย่างข้อมูลที่คาดว่าจะเจอในไฟล์
${EXPECTED_NAME}      รศ.ดร. ปัญญาพล หอระตะ
${EXPECTED_EMAIL}     punhor1@kku.ac.th
${EXPECTED_RESEARCH}  Machine Learning

*** Keywords ***
Login To System
    [Documentation]  เปิดเบราว์เซอร์ ไปที่ /login กรอก Username/Password แล้วล็อกอิน
    Open Browser    ${LOGIN_URL}    chrome
    Input Text      name=username    ${USERNAME}
    Input Text      name=password    ${PASSWORD}
    Click Button    xpath=//button[@type='submit']
    Wait Until Page Contains    Export Report    timeout=10
    # แก้ locator หรือข้อความให้ตรงกับระบบจริง

Click Export Report
    [Documentation]  คลิกที่เมนู Export Report
    Wait Until Element Is Visible    xpath=//span[@class="menu-title" and contains(text(),"Export Report")]
    Click Element                    xpath=//span[@class="menu-title" and contains(text(),"Export Report")]


Click Download PDF
    Wait Until Element Is Visible    xpath=//button[text()="Download as PDF"]    timeout=10
    Click Element                    xpath=//button[text()="Download as PDF"]



Click Download DOCX
    Wait Until Element Is Visible    xpath=//a[contains(text(),"Download as Word")]    timeout=10
    Click Element                    xpath=//a[contains(text(),"Download as Word")]


Verify File Exists
    [Arguments]    ${file_name}
    File Should Exist    ${DOWNLOAD_PATH}${file_name}

Open PDF And Check
    [Arguments]    ${file_name}    ${expected_text}
    ${pdf_text}=    Read Pdf    ${DOWNLOAD_PATH}${file_name}
    Should Contain  ${pdf_text}    ${expected_text}

Open DOCX And Check
    [Arguments]    ${file_name}    ${expected_text}
    ${doc_text}=    Read Docx    ${DOWNLOAD_PATH}${file_name}
    Should Contain  ${doc_text}    ${expected_text}

Check Unauthorized Access
    [Arguments]    ${url}
    [Documentation]  เปิด URL โดยไม่ล็อกอิน
    Open Browser    ${url}    chrome
    Wait Until Page Contains    login    timeout=5
    Close Browser

Export Large CV
    [Documentation]  สมมติว่าผู้ใช้นี้มีงานวิจัย >100 รายการ
    Login To System
    Click Export Report
    Click Download PDF
    Verify File Exists    ${REPORT_PDF}
    Close Browser



Check Export Speed
    [Arguments]    ${pdf_file}    ${docx_file}
    [Documentation]  จับเวลาการ Export ไฟล์ PDF และ DOCX ให้เสร็จภายใน 5 วินาที
    Login To System
    Click Export Report

    ${start_pdf}=    Get Current Date    result_format=timestamp
    Click Download PDF
    ${end_pdf}=      Get Current Date    result_format=timestamp
    ${elapsed_pdf}=  Evaluate    float("${end_pdf}") - float("${start_pdf}")
    Should Be True   ${elapsed_pdf} < 5

    ${start_docx}=   Get Current Date    result_format=timestamp
    Click Download DOCX
    ${end_docx}=     Get Current Date    result_format=timestamp
    ${elapsed_docx}=    Set Variable    0
    ${elapsed_docx}=    Evaluate    float("${end_docx}") - float("${start_docx}")
    Should Be True   ${elapsed_docx} < 5

    Close Browser
